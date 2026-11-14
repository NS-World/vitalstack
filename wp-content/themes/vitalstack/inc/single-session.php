<?php
/**
 * One device at a time (4.20.0).
 *
 * A reader's account is signed in on one device at a time. Signing in on a
 * new device signs out every other device automatically:
 *
 *   - The new device shows a friendly popup: "You're signed in here. We
 *     signed you out on Chrome on Windows (2 hours ago)", with a link to
 *     change the password if that wasn't them.
 *   - The old device shows, on its next page or within about a minute on a
 *     page that is already open: "You were signed out on this device because
 *     your account was signed in on Safari on iOS", with "Sign in here".
 *
 * Built on WordPress's own session tokens: the old device's token is
 * destroyed, so its cookie stops working everywhere, including the
 * protected premium lessons. Every sign-in path (password, email code,
 * sign-up, wp-login) goes through wp_set_auth_cookie(), so all are covered.
 *
 * Staff (anyone who can edit posts) are not limited. The rule can be turned
 * off with the vitalstack_single_session filter.
 *
 * Only a short device label ("Chrome on Android", from inc/devices.php) and a
 * time are stored and
 * shown, never the IP address, and only to the account's own devices.
 *
 * It works alongside inc/devices.php (premium: one phone and one computer
 * registered): that decides which devices may open premium lessons, this one
 * makes sure only one of them is signed in at a time.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/** Whether the one-device rule applies to this user. */
function vitalstack_single_session_applies( $user_id ) {
	$user_id = (int) $user_id;
	if ( ! $user_id || user_can( $user_id, 'edit_posts' ) ) {
		return false;
	}
	return (bool) apply_filters( 'vitalstack_single_session', true, $user_id );
}

/**
 * On every sign-in: keep only the new session and sign out the others.
 * Fires inside wp_set_auth_cookie() with the new session's token.
 */
function vitalstack_single_session_on_login( $cookie, $expire, $expiration, $user_id, $scheme, $token ) {
	if ( ! $token || ! vitalstack_single_session_applies( $user_id ) || ! class_exists( 'WP_Session_Tokens' ) ) {
		return;
	}
	$manager  = WP_Session_Tokens::get_instance( $user_id );
	$sessions = $manager->get_all();
	if ( count( $sessions ) <= 1 ) {
		return;
	}
	$keep_hash = hash( 'sha256', $token ); // the default manager stores sessions by the hash of their token
	$others    = array();
	$hashes    = array();
	foreach ( (array) get_user_meta( $user_id, 'session_tokens', true ) as $hash => $s ) {
		if ( $hash !== $keep_hash && isset( $s['expiration'] ) && $s['expiration'] >= time() ) {
			$others[] = array( vitalstack_device_label( $s['ua'] ?? '' ), (int) ( $s['login'] ?? 0 ) );
			$hashes[] = $hash;
		}
	}
	$manager->destroy_others( $token );
	if ( ! $others ) {
		return;
	}
	$here = vitalstack_device_label();
	// For the new device: who was signed out (shown once).
	update_user_meta( $user_id, 'vs_session_notice', array( 't' => time(), 'out' => array_slice( $others, 0, 3 ) ) );
	// For the old devices: who signed in instead (shown when they come back, for 30 days).
	update_user_meta( $user_id, 'vs_session_kicked_by', array( 't' => time(), 'by' => $here, 'h' => array_slice( $hashes, 0, 20 ) ) );
	do_action( 'vitalstack_single_session_kicked', $user_id, $others, $here );
}
add_action( 'set_logged_in_cookie', 'vitalstack_single_session_on_login', 10, 6 );

/* ── The old device: its cookie no longer matches a session ────────────── */

/**
 * WordPress fires this when a cookie's session token was destroyed. If the
 * account was signed in elsewhere recently, remember to explain why, and
 * clear the dead cookie so the device is simply signed out.
 */
function vitalstack_single_session_bad_token( $cookie_elements ) {
	static $busy = false;
	if ( $busy ) {
		return;
	}
	$busy = true;
	$user = isset( $cookie_elements['username'] ) ? get_user_by( 'login', $cookie_elements['username'] ) : null;
	if ( ! $user ) {
		return;
	}
	$k    = get_user_meta( $user->ID, 'vs_session_kicked_by', true );
	$mine = isset( $cookie_elements['token'] ) && is_array( $k ) && in_array( hash( 'sha256', (string) $cookie_elements['token'] ), (array) ( $k['h'] ?? array() ), true );
	// Only sessions this rule ended get the explanation (not a device the reader signed out by hand).
	if ( $mine && ! empty( $k['t'] ) && time() - (int) $k['t'] < 30 * DAY_IN_SECONDS ) {
		$GLOBALS['vitalstack_kicked'] = array( 'by' => (string) $k['by'], 't' => (int) $k['t'] );
		// The dead cookie is cleared later (vitalstack_session_clear_dead_cookie), never here:
		// this runs while WordPress is still working out the user, and clearing cookies now
		// would ask for the user again and loop.
	}
	$busy = false;
}

/** Remove the dead sign-in cookie once the user is known (called from the page and the ping). */
function vitalstack_session_clear_dead_cookie() {
	static $done = false;
	if ( $done || empty( $GLOBALS['vitalstack_kicked'] ) || headers_sent() || is_user_logged_in() ) {
		return;
	}
	$done = true;
	wp_clear_auth_cookie();
}
add_action( 'auth_cookie_bad_session_token', 'vitalstack_single_session_bad_token' );

/**
 * Did this browser's sign-in cookie belong to a session this rule ended?
 * Works on its own (plugins may check the user before the theme loads, so
 * the action above can come too early). The cookie's signature is checked
 * exactly like WordPress does, so a forged cookie learns nothing.
 *
 * @return array{by:string,t:int}|null
 */
function vitalstack_session_kicked_info() {
	if ( ! empty( $GLOBALS['vitalstack_kicked'] ) ) {
		return $GLOBALS['vitalstack_kicked'];
	}
	if ( is_user_logged_in() ) {
		return null;
	}
	foreach ( array( 'logged_in', 'auth' ) as $scheme ) {
		$el = wp_parse_auth_cookie( '', $scheme );
		if ( ! $el || empty( $el['token'] ) || (int) $el['expiration'] < time() ) {
			continue;
		}
		$user = get_user_by( 'login', $el['username'] );
		if ( ! $user ) {
			continue;
		}
		// Same rule as wp_validate_auth_cookie() (WordPress 6.8+ uses the end of newer hashes).
		$frag = ( 0 === strpos( $user->user_pass, '$P$' ) || 0 === strpos( $user->user_pass, '$2y$' ) ) ? substr( $user->user_pass, 8, 4 ) : substr( $user->user_pass, -4 );
		$key  = wp_hash( $el['username'] . '|' . $frag . '|' . $el['expiration'] . '|' . $el['token'], $scheme );
		if ( ! hash_equals( hash_hmac( 'sha256', $el['username'] . '|' . $el['expiration'] . '|' . $el['token'], $key ), $el['hmac'] ) ) {
			continue; // not a cookie this site signed
		}
		vitalstack_single_session_bad_token( $el );
		if ( ! empty( $GLOBALS['vitalstack_kicked'] ) ) {
			return $GLOBALS['vitalstack_kicked'];
		}
	}
	return null;
}

/** "5 minutes ago" for a timestamp. */
function vitalstack_session_ago( $t ) {
	/* translators: %s: time, for example "5 minutes" */
	return $t ? sprintf( __( '%s ago', 'vitalstack' ), human_time_diff( (int) $t, time() ) ) : '';
}

/* ── Pings from open pages: is this device still signed in? ────────────── */

function vitalstack_session_ping() {
	nocache_headers();
	$uid = get_current_user_id(); // a destroyed token makes this 0
	// Public on purpose (a signed-out device has no nonce); it only reads, and only answers about a
	// cookie this site signed. Signed-out callers are rate-limited per IP so bots can't hammer it.
	if ( ! $uid && function_exists( 'vitalstack_rate_limited' ) && function_exists( 'vitalstack_remote_ip' ) && vitalstack_rate_limited( 'vss|' . vitalstack_remote_ip(), 30, 10 * MINUTE_IN_SECONDS ) ) {
		wp_send_json( array( 'in' => false ) );
	}
	$out = array( 'in' => (bool) $uid );
	$k   = $uid ? null : vitalstack_session_kicked_info();
	if ( $k ) {
		$out['kicked'] = array(
			'by'  => $k['by'],
			'ago' => vitalstack_session_ago( $k['t'] ),
		);
	}
	vitalstack_session_clear_dead_cookie();
	wp_send_json( $out );
}
add_action( 'wp_ajax_vs_session', 'vitalstack_session_ping' );
add_action( 'wp_ajax_nopriv_vs_session', 'vitalstack_session_ping' );

/* ── Popups ────────────────────────────────────────────────────────────── */

function vitalstack_session_popup( $kind, $title, $lines, $primary, $secondary = null ) {
	?>
	<div class="vss" data-vss="<?php echo esc_attr( $kind ); ?>" role="dialog" aria-modal="true" aria-labelledby="vss-title-<?php echo esc_attr( $kind ); ?>">
		<div class="vss-card">
			<p class="vss-icon" aria-hidden="true"><?php echo 'here' === $kind ? '🔐' : '👋'; ?></p>
			<h2 class="vss-title" id="vss-title-<?php echo esc_attr( $kind ); ?>"><?php echo esc_html( $title ); ?></h2>
			<?php foreach ( $lines as $l ) : ?>
				<p class="vss-text"><?php echo wp_kses( $l, array( 'b' => array() ) ); ?></p>
			<?php endforeach; ?>
			<div class="vss-actions">
				<a class="btn btn-primary vss-go" href="<?php echo esc_url( $primary[1] ); ?>" data-vss-close><?php echo esc_html( $primary[0] ); ?></a>
				<?php if ( $secondary ) : ?>
					<a class="vss-link" href="<?php echo esc_url( $secondary[1] ); ?>"><?php echo esc_html( $secondary[0] ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
}

/** On the new device, once: which devices were signed out. */
function vitalstack_session_notice_here() {
	$uid = get_current_user_id();
	if ( ! $uid ) {
		return;
	}
	$n = get_user_meta( $uid, 'vs_session_notice', true );
	if ( ! is_array( $n ) || empty( $n['out'] ) ) {
		return;
	}
	delete_user_meta( $uid, 'vs_session_notice' );
	if ( time() - (int) $n['t'] > DAY_IN_SECONDS ) {
		return;
	}
	$names = array();
	foreach ( $n['out'] as $o ) {
		/* translators: 1: device such as "Chrome on Windows", 2: "signed in 2 days ago" */
		$names[] = '<b>' . esc_html( $o[0] ) . '</b>' . ( $o[1] ? ' (' . esc_html( sprintf( __( 'signed in %s', 'vitalstack' ), vitalstack_session_ago( $o[1] ) ) ) . ')' : '' );
	}
	$account = function_exists( 'vitalstack_account_url' ) ? vitalstack_account_url() : home_url( '/' );
	vitalstack_session_popup(
		'here',
		__( 'You’re signed in on this device', 'vitalstack' ),
		array(
			__( 'Your VitalStack account works on one device at a time, so your progress and premium access stay yours.', 'vitalstack' ),
			/* translators: %s: list of devices */
			sprintf( __( 'We signed you out on: %s.', 'vitalstack' ), implode( ', ', $names ) ),
		),
		array( __( 'Got it, continue', 'vitalstack' ), '#' ),
		array( __( 'Not you? Change your password', 'vitalstack' ), wp_lostpassword_url( $account ) )
	);
}

/** On the old device: why it was signed out. */
function vitalstack_session_notice_kicked() {
	if ( is_user_logged_in() || empty( $GLOBALS['vitalstack_kicked'] ) ) {
		return;
	}
	$k       = $GLOBALS['vitalstack_kicked'];
	$account = function_exists( 'vitalstack_account_url' ) ? vitalstack_account_url() : wp_login_url();
	vitalstack_session_popup(
		'out',
		__( 'You were signed out on this device', 'vitalstack' ),
		array(
			/* translators: 1: device such as "Safari on iOS", 2: "5 minutes ago" */
			sprintf( __( 'Your account was signed in on %1$s %2$s. A VitalStack account works on one device at a time.', 'vitalstack' ), '<b>' . esc_html( $k['by'] ) . '</b>', esc_html( vitalstack_session_ago( $k['t'] ) ) ),
			__( 'Want to keep learning here? Sign in again: the other device will be signed out instead. Your progress is saved to your account.', 'vitalstack' ),
		),
		array( __( 'Sign in on this device', 'vitalstack' ), $account ),
		array( __( 'Wasn’t you? Reset your password', 'vitalstack' ), wp_lostpassword_url( $account ) )
	);
}

function vitalstack_session_footer() {
	if ( is_admin() ) {
		return;
	}
	vitalstack_session_notice_here();
	vitalstack_session_notice_kicked();
	if ( is_user_logged_in() && vitalstack_single_session_applies( get_current_user_id() ) ) {
		// Open pages ask every 90 seconds (while visible) whether this device is still signed in.
		$account = function_exists( 'vitalstack_account_url' ) ? vitalstack_account_url() : wp_login_url();
		printf(
			'<template id="vss-out-tpl" data-ping="%1$s" data-signin="%2$s" data-reset="%3$s" data-title="%4$s" data-text="%5$s" data-text2="%6$s" data-go="%7$s" data-link="%8$s"></template>',
			esc_url( admin_url( 'admin-ajax.php?action=vs_session' ) ),
			esc_url( $account ),
			esc_url( wp_lostpassword_url( $account ) ),
			esc_attr__( 'You were signed out on this device', 'vitalstack' ),
			/* translators: 1: device, 2: time ago. Placeholders are filled in by the page. */
			esc_attr__( 'Your account was signed in on %1$s %2$s. A VitalStack account works on one device at a time.', 'vitalstack' ),
			esc_attr__( 'Want to keep learning here? Sign in again: the other device will be signed out instead. Your progress is saved to your account.', 'vitalstack' ),
			esc_attr__( 'Sign in on this device', 'vitalstack' ),
			esc_attr__( 'Wasn’t you? Reset your password', 'vitalstack' )
		);
	}
}
add_action( 'wp_footer', 'vitalstack_session_footer', 5 );

function vitalstack_session_assets() {
	if ( is_admin() ) {
		return;
	}
	$need = ! empty( $GLOBALS['vitalstack_kicked'] ) || ( is_user_logged_in() && vitalstack_single_session_applies( get_current_user_id() ) );
	if ( $need ) {
		wp_enqueue_script( 'vitalstack-session', vitalstack_asset_url( 'assets/js/session.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'vitalstack_session_assets', 36 );

/** Pages for a device that was just signed out must not be cached. */
add_action(
	'template_redirect',
	function () {
		if ( ! is_admin() && ( isset( $_COOKIE[ LOGGED_IN_COOKIE ] ) ) ) {
			vitalstack_session_kicked_info();
		}
		if ( ! empty( $GLOBALS['vitalstack_kicked'] ) ) {
			vitalstack_session_clear_dead_cookie();
			nocache_headers();
			if ( ! defined( 'DONOTCACHEPAGE' ) ) {
				define( 'DONOTCACHEPAGE', true );
			}
		}
	},
	1
);
