<?php
/**
 * Security audit log: who did what, when, from where. Kept 180 days.
 *
 * Tools → VitalStack Security shows recent events, lets the owner suspend
 * an account in one click, and reads the hidden watermark from leaked text.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

const VITALSTACK_AUDIT_DB = '1';

function vitalstack_audit_table() {
	global $wpdb;
	return $wpdb->prefix . 'vs_audit';
}

add_action(
	'init',
	function () {
		if ( get_option( 'vs_audit_db' ) === VITALSTACK_AUDIT_DB ) {
			return;
		}
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta(
			'CREATE TABLE ' . vitalstack_audit_table() . " (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			t int(10) unsigned NOT NULL,
			uid bigint(20) unsigned NOT NULL DEFAULT 0,
			event varchar(40) NOT NULL,
			ip varchar(45) NOT NULL DEFAULT '',
			data text NULL,
			PRIMARY KEY  (id),
			KEY uid_t (uid, t),
			KEY event_t (event, t)
			) " . $wpdb->get_charset_collate() . ';'
		);
		update_option( 'vs_audit_db', VITALSTACK_AUDIT_DB, false );
	}
);

/**
 * Record an event. $alert also emails the owner (at most once an hour per event).
 */
function vitalstack_audit( $event, $uid = 0, $data = array(), $alert = false ) {
	global $wpdb;
	$wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		vitalstack_audit_table(),
		array(
			't'     => time(),
			'uid'   => (int) $uid,
			'event' => substr( sanitize_key( $event ), 0, 40 ),
			'ip'    => function_exists( 'vitalstack_remote_ip' ) ? vitalstack_remote_ip() : '',
			'data'  => $data ? wp_json_encode( $data ) : null,
		),
		array( '%d', '%d', '%s', '%s', '%s' )
	);
	if ( $alert && ! get_transient( 'vs_alert_' . $event ) ) {
		set_transient( 'vs_alert_' . $event, 1, HOUR_IN_SECONDS );
		$user = $uid ? get_userdata( $uid ) : null;
		vitalstack_mail(
			get_option( 'admin_email' ),
			/* translators: %s: event */
			sprintf( __( 'VitalStack security alert: %s', 'vitalstack' ), $event ),
			'<p>' . esc_html( $event . ( $user ? ' · ' . $user->user_email . ' (#' . $uid . ')' : '' ) ) . '</p><p>' . esc_html( $data ? wp_json_encode( $data ) : '' ) . '</p><p><a href="' . esc_url( admin_url( 'tools.php?page=vs-security' ) ) . '">' . esc_html__( 'Open the security log', 'vitalstack' ) . '</a></p>'
		);
	}
}

// Sign-ins and failures go into the log too.
add_action(
	'wp_login',
	function ( $login, $user ) {
		vitalstack_audit( 'login', $user->ID );
	},
	20,
	2
);
add_action(
	'wp_login_failed',
	function ( $login ) {
		$u = get_user_by( is_email( $login ) ? 'email' : 'login', $login );
		vitalstack_audit( 'login_failed', $u ? $u->ID : 0 );
	}
);

// Keep 180 days.
add_action(
	'vitalstack_audit_prune',
	function () {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . vitalstack_audit_table() . ' WHERE t < %d', time() - 180 * DAY_IN_SECONDS ) ); // phpcs:ignore WordPress.DB
	}
);
add_action(
	'init',
	function () {
		if ( ! wp_next_scheduled( 'vitalstack_audit_prune' ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'vitalstack_audit_prune' );
		}
	}
);

/* ── Suspension ───────────────────────────────────────────────────────── */

function vitalstack_suspended( $uid ) {
	return (bool) get_user_meta( $uid, 'vs_suspended', true );
}

/**
 * Suspend (or restore) an account: premium stops, every device is signed out.
 */
function vitalstack_suspend( $uid, $on, $why = '' ) {
	if ( $on ) {
		update_user_meta( $uid, 'vs_suspended', time() );
		if ( function_exists( 'vitalstack_device_sign_out' ) ) {
			vitalstack_device_sign_out( $uid );
		}
		WP_Session_Tokens::get_instance( $uid )->destroy_all();
	} else {
		delete_user_meta( $uid, 'vs_suspended' );
	}
	vitalstack_audit( $on ? 'suspended' : 'restored', $uid, array( 'why' => $why, 'by' => get_current_user_id() ) );
}

/* ── Tools → VitalStack Security ──────────────────────────────────────── */

add_action(
	'admin_menu',
	function () {
		add_management_page( __( 'VitalStack Security', 'vitalstack' ), __( 'VitalStack Security', 'vitalstack' ), 'manage_options', 'vs-security', 'vitalstack_security_page' );
	}
);

function vitalstack_security_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	global $wpdb;
	// phpcs:disable WordPress.Security.NonceVerification -- read-only filters; changes go through admin-post with a nonce
	$who   = isset( $_GET['who'] ) ? sanitize_text_field( wp_unslash( $_GET['who'] ) ) : '';
	$user  = $who ? get_user_by( is_email( $who ) ? 'email' : 'id', $who ) : null;
	$found = null;
	if ( isset( $_POST['leak'] ) && check_admin_referer( 'vs_leak' ) ) {
		$found = vitalstack_watermark_read( wp_unslash( $_POST['leak'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- decoded only
	}
	// phpcs:enable
	$rows = $user
		? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . vitalstack_audit_table() . ' WHERE uid = %d ORDER BY id DESC LIMIT 200', $user->ID ) ) // phpcs:ignore WordPress.DB
		: $wpdb->get_results( 'SELECT * FROM ' . vitalstack_audit_table() . ' ORDER BY id DESC LIMIT 200' ); // phpcs:ignore WordPress.DB
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'VitalStack Security', 'vitalstack' ); ?></h1>
		<h2><?php esc_html_e( 'Find who leaked a lesson', 'vitalstack' ); ?></h2>
		<form method="post">
			<?php wp_nonce_field( 'vs_leak' ); ?>
			<p><?php esc_html_e( 'Paste text copied from a leaked premium lesson (a Telegram post, a PDF, a website). Every premium reader gets an invisible mark in their copy.', 'vitalstack' ); ?></p>
			<textarea name="leak" rows="4" class="large-text"></textarea>
			<?php submit_button( __( 'Read watermark', 'vitalstack' ), 'secondary', 'submit', false ); ?>
		</form>
		<?php if ( null !== $found ) : ?>
			<?php $lu = $found ? get_userdata( $found ) : null; ?>
			<div class="notice <?php echo $lu ? 'notice-warning' : 'notice-info'; ?>"><p>
				<?php echo $lu ? esc_html( sprintf( 'Mark found: reader #%d %s (%s).', $found, $lu->display_name, $lu->user_email ) ) . ' <a href="' . esc_url( admin_url( 'tools.php?page=vs-security&who=' . $found ) ) . '">' . esc_html__( 'See their log', 'vitalstack' ) . '</a>' : esc_html__( 'No mark found. Paste a longer piece (a few paragraphs), copied as text, not retyped.', 'vitalstack' ); ?>
			</p></div>
		<?php endif; ?>

		<h2><?php esc_html_e( 'Activity log', 'vitalstack' ); ?></h2>
		<form method="get"><input type="hidden" name="page" value="vs-security">
			<input type="search" name="who" value="<?php echo esc_attr( $who ); ?>" placeholder="<?php esc_attr_e( 'Email or user ID', 'vitalstack' ); ?>"> <?php submit_button( __( 'Filter', 'vitalstack' ), 'secondary', '', false ); ?>
		</form>
		<?php if ( $user ) : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin:10px 0">
				<input type="hidden" name="action" value="vs_suspend"><input type="hidden" name="uid" value="<?php echo (int) $user->ID; ?>">
				<?php wp_nonce_field( 'vs_suspend_' . $user->ID ); ?>
				<?php if ( vitalstack_suspended( $user->ID ) ) : ?>
					<strong><?php esc_html_e( 'This account is suspended.', 'vitalstack' ); ?></strong> <?php submit_button( __( 'Restore account', 'vitalstack' ), 'secondary', 'restore', false ); ?>
				<?php else : ?>
					<?php submit_button( __( 'Suspend account and sign out every device', 'vitalstack' ), 'delete', 'suspend', false ); ?>
				<?php endif; ?>
			</form>
		<?php endif; ?>
		<table class="widefat striped"><thead><tr><th><?php esc_html_e( 'When', 'vitalstack' ); ?></th><th><?php esc_html_e( 'Reader', 'vitalstack' ); ?></th><th><?php esc_html_e( 'Event', 'vitalstack' ); ?></th><th>IP</th><th><?php esc_html_e( 'Details', 'vitalstack' ); ?></th></tr></thead><tbody>
			<?php foreach ( (array) $rows as $r ) : ?>
				<?php $ru = $r->uid ? get_userdata( (int) $r->uid ) : null; ?>
				<tr><td><?php echo esc_html( wp_date( 'j M Y H:i', (int) $r->t ) ); ?></td>
					<td><?php echo $ru ? '<a href="' . esc_url( admin_url( 'tools.php?page=vs-security&who=' . (int) $r->uid ) ) . '">' . esc_html( $ru->user_email ) . '</a>' : '—'; ?></td>
					<td><code><?php echo esc_html( $r->event ); ?></code></td><td><?php echo esc_html( $r->ip ); ?></td><td><?php echo esc_html( (string) $r->data ); ?></td></tr>
			<?php endforeach; ?>
			<?php if ( ! $rows ) : ?><tr><td colspan="5"><?php esc_html_e( 'Nothing logged yet.', 'vitalstack' ); ?></td></tr><?php endif; ?>
		</tbody></table>
	</div>
	<?php
}

add_action(
	'admin_post_vs_suspend',
	function () {
		$uid = isset( $_POST['uid'] ) ? absint( $_POST['uid'] ) : 0;
		if ( ! current_user_can( 'manage_options' ) || ! $uid || ! check_admin_referer( 'vs_suspend_' . $uid ) || user_can( $uid, 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'vitalstack' ), 403 );
		}
		vitalstack_suspend( $uid, isset( $_POST['suspend'] ), 'admin' );
		wp_safe_redirect( admin_url( 'tools.php?page=vs-security&who=' . $uid ) );
		exit;
	}
);
