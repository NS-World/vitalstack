<?php
/**
 * Devices: premium readers can use one phone (or tablet) and one computer.
 *
 *   - Each browser gets a random device token in an HttpOnly cookie; only
 *     its hash is stored. Every sign-in session remembers which device it
 *     belongs to, so "sign out that device" really ends its sessions.
 *   - Free readers are not limited. The limit is checked when a premium
 *     lesson is opened (inc/protect.php): a third device asks to replace
 *     the old one of the same type, after a fresh security check.
 *   - At most 3 switches in 30 days; after that, support checks the account.
 *   - Staff sign-ins from a new device trigger an email alert.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

const VITALSTACK_DEV_COOKIE   = 'vs_dev';
const VITALSTACK_DEV_SWITCHES = 3; // per 30 days

function vitalstack_device_type( $ua = null ) {
	$ua = null === $ua ? ( isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '' ) : $ua;
	return preg_match( '/Mobi|Android|iPhone|iPad|iPod|Tablet|Silk|Kindle/i', $ua ) ? 'phone' : 'computer';
}

function vitalstack_device_label( $ua = null ) {
	$ua      = null === $ua ? ( isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '' ) : $ua;
	$browser = preg_match( '/Edg\//', $ua ) ? 'Edge' : ( preg_match( '/OPR\/|Opera/', $ua ) ? 'Opera' : ( preg_match( '/SamsungBrowser/', $ua ) ? 'Samsung Internet' : ( preg_match( '/Firefox\//', $ua ) ? 'Firefox' : ( preg_match( '/Chrome\//', $ua ) ? 'Chrome' : ( preg_match( '/Safari\//', $ua ) ? 'Safari' : 'Browser' ) ) ) ) );
	$os      = preg_match( '/Android/', $ua ) ? 'Android' : ( preg_match( '/iPhone|iPad|iPod/', $ua ) ? 'iOS' : ( preg_match( '/Windows/', $ua ) ? 'Windows' : ( preg_match( '/Mac OS X/', $ua ) ? 'Mac' : ( preg_match( '/Linux/', $ua ) ? 'Linux' : 'unknown system' ) ) ) );
	return $browser . ' on ' . $os;
}

/**
 * This browser's device token, created (and sent as a cookie) if missing.
 */
function vitalstack_device_token( $create = true ) {
	$raw = isset( $_COOKIE[ VITALSTACK_DEV_COOKIE ] ) ? preg_replace( '/[^A-Za-z0-9]/', '', (string) wp_unslash( $_COOKIE[ VITALSTACK_DEV_COOKIE ] ) ) : '';
	if ( 40 === strlen( $raw ) ) {
		return $raw;
	}
	if ( ! $create || headers_sent() ) {
		return '';
	}
	$made = wp_generate_password( 40, false, false );
	setcookie(
		VITALSTACK_DEV_COOKIE,
		$made,
		array(
			'expires'  => time() + YEAR_IN_SECONDS,
			'path'     => COOKIEPATH,
			'domain'   => (string) COOKIE_DOMAIN,
			'secure'   => is_ssl(),
			'httponly' => true,
			'samesite' => 'Lax',
		)
	);
	$_COOKIE[ VITALSTACK_DEV_COOKIE ] = $made;
	return $made;
}

function vitalstack_device_hash( $token ) {
	return $token ? hash_hmac( 'sha256', $token, wp_salt( 'auth' ) . '|device' ) : '';
}

function vitalstack_devices( $user_id ) {
	$list = get_user_meta( $user_id, 'vs_devices', true );
	return is_array( $list ) ? $list : array();
}

/**
 * Whether the device limit applies (paying readers; not staff).
 */
function vitalstack_device_limited( $user_id ) {
	return function_exists( 'vitalstack_reader_has_any_premium' ) && vitalstack_reader_has_any_premium( $user_id ) && ! user_can( $user_id, 'edit_posts' );
}

// Every new sign-in session is labelled with the device it was made on.
add_filter(
	'attach_session_information',
	function ( $info ) {
		$info['vs_dev'] = vitalstack_device_hash( vitalstack_device_token() );
		return $info;
	}
);

/**
 * End every session of one device (or all devices when $hash is '').
 */
function vitalstack_device_sign_out( $user_id, $hash = '' ) {
	$sessions = get_user_meta( $user_id, 'session_tokens', true );
	if ( is_array( $sessions ) ) {
		foreach ( $sessions as $verifier => $s ) {
			if ( '' === $hash || ( isset( $s['vs_dev'] ) && hash_equals( (string) $s['vs_dev'], $hash ) ) ) {
				unset( $sessions[ $verifier ] );
			}
		}
		update_user_meta( $user_id, 'session_tokens', $sessions );
	}
	$list = vitalstack_devices( $user_id );
	if ( '' === $hash ) {
		$list = array();
	} else {
		unset( $list[ $hash ] );
	}
	update_user_meta( $user_id, 'vs_devices', $list );
}

/**
 * Is this browser allowed to open premium lessons for this reader?
 *
 * @return true|array True, or ['other' => device row] when another device of the same type holds the place.
 */
function vitalstack_device_check( $user_id ) {
	if ( ! vitalstack_device_limited( $user_id ) ) {
		return true;
	}
	$hash = vitalstack_device_hash( vitalstack_device_token() );
	if ( '' === $hash ) {
		return true; // can't set a cookie on this response; the next request will
	}
	$list = vitalstack_devices( $user_id );
	$type = vitalstack_device_type();
	if ( isset( $list[ $hash ] ) ) {
		if ( time() - (int) $list[ $hash ]['last'] > HOUR_IN_SECONDS ) {
			$list[ $hash ]['last'] = time();
			update_user_meta( $user_id, 'vs_devices', $list );
		}
		return true;
	}
	foreach ( $list as $h => $d ) {
		if ( $d['type'] === $type ) {
			return array( 'other' => $d + array( 'hash' => $h ) );
		}
	}
	// A free place for this kind of device: take it.
	$list[ $hash ] = array( 'type' => $type, 'label' => vitalstack_device_label(), 'first' => time(), 'last' => time() );
	update_user_meta( $user_id, 'vs_devices', $list );
	return true;
}

/**
 * Replace the other device of this type with this one (after a fresh security check).
 *
 * @return true|WP_Error
 */
function vitalstack_device_switch( $user_id ) {
	$check = vitalstack_device_check( $user_id );
	if ( true === $check ) {
		return true;
	}
	$times = array_filter(
		(array) get_user_meta( $user_id, 'vs_device_switches', true ),
		function ( $t ) {
			return $t > time() - 30 * DAY_IN_SECONDS;
		}
	);
	if ( count( $times ) >= VITALSTACK_DEV_SWITCHES ) {
		if ( function_exists( 'vitalstack_audit' ) ) {
			vitalstack_audit( 'device_switch_blocked', $user_id, array(), true );
		}
		return new WP_Error( 'switch_limit', __( 'You have switched devices 3 times in 30 days. Please write to us and we’ll sort it out.', 'vitalstack' ), array( 'status' => 403 ) );
	}
	$old = $check['other'];
	vitalstack_device_sign_out( $user_id, $old['hash'] );
	$times[] = time();
	update_user_meta( $user_id, 'vs_device_switches', array_values( $times ) );
	$list                                                        = vitalstack_devices( $user_id );
	$list[ vitalstack_device_hash( vitalstack_device_token() ) ] = array( 'type' => vitalstack_device_type(), 'label' => vitalstack_device_label(), 'first' => time(), 'last' => time() );
	update_user_meta( $user_id, 'vs_devices', $list );
	$user = get_userdata( $user_id );
	if ( $user ) {
		vitalstack_mail(
			$user->user_email,
			__( 'Your premium lessons moved to a new device', 'vitalstack' ),
			'<p>' . esc_html( sprintf( /* translators: 1: new device, 2: old device */ __( 'Premium is now on %1$s. %2$s was signed out.', 'vitalstack' ), vitalstack_device_label(), $old['label'] ) ) . '</p><p>' . esc_html__( 'Not you? Reset your password now: it signs out every device.', 'vitalstack' ) . '</p>'
		);
	}
	if ( function_exists( 'vitalstack_audit' ) ) {
		vitalstack_audit( 'device_switch', $user_id, array( 'from' => $old['label'], 'to' => vitalstack_device_label() ) );
	}
	return true;
}

// A new password signs out every device.
add_action(
	'after_password_reset',
	function ( $user ) {
		vitalstack_device_sign_out( $user->ID );
	}
);

/* ── Staff: alert on sign-in from a new device ────────────────────────── */

add_action(
	'wp_login',
	function ( $login, $user ) {
		if ( ! $user instanceof WP_User || ! user_can( $user, 'edit_posts' ) ) {
			return;
		}
		$hash = vitalstack_device_hash( vitalstack_device_token() );
		$seen = (array) get_user_meta( $user->ID, 'vs_staff_devices', true );
		if ( $hash && ! in_array( $hash, $seen, true ) ) {
			$seen[] = $hash;
			update_user_meta( $user->ID, 'vs_staff_devices', array_slice( $seen, -20 ) );
			vitalstack_mail(
				$user->user_email,
				__( 'VitalStack: staff sign-in from a new device', 'vitalstack' ),
				'<p>' . esc_html( sprintf( /* translators: 1: device, 2: IP */ __( 'Your account signed in from %1$s (IP %2$s). If this was not you, change your password at once.', 'vitalstack' ), vitalstack_device_label(), vitalstack_remote_ip() ) ) . '</p>'
			);
			if ( function_exists( 'vitalstack_audit' ) ) {
				vitalstack_audit( 'staff_new_device', $user->ID, array( 'device' => vitalstack_device_label() ) );
			}
		}
	},
	10,
	2
);

/* ── "My devices" on /account/ ────────────────────────────────────────── */

function vitalstack_handle_device_out() {
	if ( ! is_user_logged_in() || ! isset( $_POST['_vsnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_device_out' ) ) {
		vitalstack_account_redirect( 'bad_nonce' );
	}
	$uid  = get_current_user_id();
	$hash = isset( $_POST['device'] ) ? sanitize_key( $_POST['device'] ) : '';
	if ( 'all' === $hash ) {
		vitalstack_device_sign_out( $uid );
		wp_logout();
		vitalstack_account_redirect( 'devices_all', array( 'tab' => 'login' ) );
	}
	if ( preg_match( '/^[a-f0-9]{64}$/', $hash ) && isset( vitalstack_devices( $uid )[ $hash ] ) ) {
		vitalstack_device_sign_out( $uid, $hash );
	}
	vitalstack_account_redirect( 'device_out' );
}
add_action( 'admin_post_vs_device_out', 'vitalstack_handle_device_out' );

add_filter(
	'vitalstack_account_messages',
	function ( $m ) {
		$m['device_out']  = array( 'success', __( 'That device is signed out and its place is free.', 'vitalstack' ) );
		$m['devices_all'] = array( 'success', __( 'Signed out everywhere. Sign in again on this device.', 'vitalstack' ) );
		return $m;
	}
);

function vitalstack_devices_panel( $user_id ) {
	$list = vitalstack_devices( $user_id );
	$mine = vitalstack_device_hash( vitalstack_device_token( false ) );
	?>
	<section class="panel devices-panel">
		<h2>📱 <?php esc_html_e( 'My devices', 'vitalstack' ); ?></h2>
		<?php if ( vitalstack_device_limited( $user_id ) ) : ?>
			<p class="muted small"><?php esc_html_e( 'Premium works on one phone or tablet and one computer.', 'vitalstack' ); ?></p>
		<?php endif; ?>
		<?php if ( ! $list ) : ?>
			<p class="muted small"><?php esc_html_e( 'Devices appear here when you open a premium lesson.', 'vitalstack' ); ?></p>
		<?php else : ?>
			<ul class="devices-list">
				<?php foreach ( $list as $h => $d ) : ?>
					<li>
						<span class="devices-icon" aria-hidden="true"><?php echo 'phone' === $d['type'] ? '📱' : '💻'; ?></span>
						<span class="devices-name"><?php echo esc_html( $d['label'] ); ?><?php echo hash_equals( $h, (string) $mine ) ? ' <b>(' . esc_html__( 'this device', 'vitalstack' ) . ')</b>' : ''; ?><small><?php echo esc_html( sprintf( /* translators: %s: time ago */ __( 'Last used %s ago', 'vitalstack' ), human_time_diff( (int) $d['last'] ) ) ); ?></small></span>
						<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
							<input type="hidden" name="action" value="vs_device_out"><input type="hidden" name="device" value="<?php echo esc_attr( $h ); ?>">
							<?php wp_nonce_field( 'vs_device_out', '_vsnonce' ); ?>
							<button type="submit" class="btn-link"><?php esc_html_e( 'Sign out', 'vitalstack' ); ?></button>
						</form>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="vs_device_out"><input type="hidden" name="device" value="all">
			<?php wp_nonce_field( 'vs_device_out', '_vsnonce' ); ?>
			<button type="submit" class="btn btn-ghost btn-sm"><?php esc_html_e( 'Sign out everywhere', 'vitalstack' ); ?></button>
		</form>
	</section>
	<?php
}
