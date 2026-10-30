<?php
/**
 * Security hardening that a theme can safely do on shared hosting:
 * security headers, brute-force limits for every login path, no XML-RPC,
 * no user enumeration, no file editor in wp-admin.
 *
 * Server-level steps (SSL, backups, 2FA, file permissions) are in
 * docs/SECURITY.md.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

// The theme/plugin file editor lets anyone who steals an admin session run PHP.
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * The visitor's IP for rate limits. REMOTE_ADDR is the only address the
 * client can't fake; a forwarded header is believed only when the request
 * really came through Cloudflare, which sets it.
 */
function vitalstack_remote_ip() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
		return 'unknown';
	}
	if ( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) && vitalstack_ip_is_cloudflare( $ip ) ) {
		$real = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] ) );
		if ( filter_var( $real, FILTER_VALIDATE_IP ) ) {
			return $real;
		}
	}
	return $ip;
}

/**
 * Cloudflare's published edge ranges (cloudflare.com/ips).
 */
function vitalstack_ip_is_cloudflare( $ip ) {
	$ranges = array(
		'173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22', '141.101.64.0/18',
		'108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20', '197.234.240.0/22', '198.41.128.0/17',
		'162.158.0.0/15', '104.16.0.0/13', '104.24.0.0/14', '172.64.0.0/13', '131.0.72.0/22',
		'2400:cb00::/32', '2606:4700::/32', '2803:f800::/32', '2405:b500::/32', '2405:8100::/32',
		'2a06:98c0::/29', '2c0f:f248::/32',
	);
	$addr = @inet_pton( $ip ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
	if ( false === $addr ) {
		return false;
	}
	foreach ( $ranges as $range ) {
		list( $net, $bits ) = explode( '/', $range );
		$net = inet_pton( $net );
		if ( strlen( $net ) !== strlen( $addr ) ) {
			continue;
		}
		$bytes = intdiv( (int) $bits, 8 );
		$rest  = (int) $bits % 8;
		if ( substr( $addr, 0, $bytes ) !== substr( $net, 0, $bytes ) ) {
			continue;
		}
		if ( 0 === $rest ) {
			return true;
		}
		$mask = ( 0xff << ( 8 - $rest ) ) & 0xff;
		if ( ( ord( $addr[ $bytes ] ) & $mask ) === ( ord( $net[ $bytes ] ) & $mask ) ) {
			return true;
		}
	}
	return false;
}

/* ── Security headers ─────────────────────────────────────────────────── */

function vitalstack_security_headers() {
	if ( headers_sent() ) {
		return;
	}
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(self "https://checkout.razorpay.com" "https://api.razorpay.com"), usb=()' );
	header( 'Cross-Origin-Opener-Policy: same-origin-allow-popups' );
	if ( is_ssl() ) {
		header( 'Strict-Transport-Security: max-age=31536000' );
		// Never load a script or image over plain http on an https page, and
		// never let another site show this one inside a frame.
		header( "Content-Security-Policy: upgrade-insecure-requests; frame-ancestors 'self'" );
	}
}
add_action( 'send_headers', 'vitalstack_security_headers' );
add_action( 'login_init', 'vitalstack_security_headers' );
add_action( 'admin_init', 'vitalstack_security_headers' );

/* ── Brute-force protection for every login path ──────────────────────── */

const VITALSTACK_LOGIN_FAILS_PER_ACCOUNT = 10; // per hour, whatever the IP

/**
 * Failed-login counter per account. It is keyed on the account, not the IP,
 * so a botnet can't spread its guesses and a site behind a CDN (one shared
 * IP for everyone) never locks out all visitors at once.
 */
function vitalstack_login_buckets( $username ) {
	$username = strtolower( trim( (string) $username ) );
	if ( '' === $username ) {
		return array();
	}
	if ( is_email( $username ) ) {
		$user     = get_user_by( 'email', $username );
		$username = $user ? strtolower( $user->user_login ) : $username;
	}
	return array( 'vs_lf_user_' . md5( $username ) => VITALSTACK_LOGIN_FAILS_PER_ACCOUNT );
}

function vitalstack_login_is_locked( $username ) {
	foreach ( vitalstack_login_buckets( $username ) as $key => $limit ) {
		if ( (int) get_transient( $key ) >= $limit ) {
			return true;
		}
	}
	return false;
}

/**
 * Refuse the sign-in while the account is locked, even with the
 * right password, so guessing stops paying off. Runs after WordPress has
 * checked the password (priority 20) so its result can't override this.
 * wp-login.php, the theme's sign-in form and the REST/app-password paths
 * all go through this filter.
 *
 * Wrong username and wrong password also get the same message here, so the
 * form can't be used to find out which accounts exist.
 */
function vitalstack_login_guard( $user, $username ) {
	if ( '' === trim( (string) $username ) ) {
		return $user;
	}
	if ( vitalstack_login_is_locked( $username ) ) {
		return new WP_Error(
			'vs_locked',
			sprintf(
				/* translators: %s: lost password URL */
				__( '<strong>Error:</strong> Too many failed sign-in attempts for this account. Please wait an hour and try again, or <a href="%s">reset your password</a>.', 'vitalstack' ),
				esc_url( wp_lostpassword_url() )
			)
		);
	}
	if ( is_wp_error( $user ) && array_intersect( $user->get_error_codes(), array( 'invalid_username', 'invalid_email', 'incorrect_password' ) ) ) {
		return new WP_Error(
			'incorrect_password',
			sprintf(
				/* translators: %s: lost password URL */
				__( '<strong>Error:</strong> The username/email or password is incorrect. <a href="%s">Lost your password?</a>', 'vitalstack' ),
				esc_url( wp_lostpassword_url() )
			)
		);
	}
	return $user;
}
add_filter( 'authenticate', 'vitalstack_login_guard', 100, 2 );

function vitalstack_login_failed( $username ) {
	foreach ( vitalstack_login_buckets( $username ) as $key => $limit ) {
		$count = (int) get_transient( $key );
		if ( $count < $limit ) {
			set_transient( $key, $count + 1, HOUR_IN_SECONDS );
		}
	}
}
add_action( 'wp_login_failed', 'vitalstack_login_failed' );

function vitalstack_login_succeeded( $user_login ) {
	delete_transient( 'vs_lf_user_' . md5( strtolower( $user_login ) ) );
}
add_action( 'wp_login', 'vitalstack_login_succeeded' );

// A password reset unlocks the account straight away.
add_action(
	'after_password_reset',
	function ( $user ) {
		vitalstack_login_succeeded( $user->user_login );
	}
);

/* ── XML-RPC: not used by this site, a favourite brute-force target ───── */

// Jetpack talks to the site over XML-RPC, so it stays on while Jetpack is active.
if ( apply_filters( 'vitalstack_disable_xmlrpc', ! class_exists( 'Jetpack' ) ) ) {
	add_filter( 'xmlrpc_enabled', '__return_false' );
	add_filter( 'xmlrpc_methods', '__return_empty_array' );
	add_filter(
		'wp_headers',
		function ( $headers ) {
			unset( $headers['X-Pingback'] );
			return $headers;
		}
	);
}

/* ── No user enumeration ──────────────────────────────────────────────── */

/**
 * /?author=1 would redirect to /author/<username>/ and reveal login names.
 */
function vitalstack_block_author_scan() {
	if ( is_admin() || ! isset( $_GET['author'] ) || is_user_logged_in() ) { // phpcs:ignore WordPress.Security.NonceVerification -- read-only check
		return;
	}
	wp_safe_redirect( home_url( '/' ), 301 );
	exit;
}
add_action( 'template_redirect', 'vitalstack_block_author_scan', 0 );

/**
 * The public users REST endpoints list every author's login name.
 */
function vitalstack_hide_user_endpoints( $endpoints ) {
	// Writers still need the list for the author picker in the editor.
	if ( current_user_can( 'edit_posts' ) ) {
		return $endpoints;
	}
	foreach ( array_keys( $endpoints ) as $route ) {
		if ( 0 === strpos( $route, '/wp/v2/users' ) ) {
			unset( $endpoints[ $route ] );
		}
	}
	return $endpoints;
}
add_filter( 'rest_endpoints', 'vitalstack_hide_user_endpoints' );

/**
 * oEmbed data includes the author's profile URL with the login name.
 */
add_filter(
	'oembed_response_data',
	function ( $data ) {
		unset( $data['author_url'] );
		return $data;
	}
);

/* ── Smaller things ───────────────────────────────────────────────────── */

// Don't advertise the WordPress version in feeds either.
add_filter( 'the_generator', '__return_empty_string' );


/* ── Accounts ─────────────────────────────────────────────────────────── */

/**
 * Passwords that are among the first tried in any attack, or that are just
 * the email name. Used when readers sign up.
 */
function vitalstack_password_is_common( $password, $email = '' ) {
	$p = strtolower( trim( (string) $password ) );
	if ( $email && strtolower( strstr( $email, '@', true ) ) === $p ) {
		return true;
	}
	if ( 1 === count( array_unique( str_split( $p ) ) ) ) {
		return true; // "aaaaaaaa", "11111111"
	}
	$common = array( '12345678', '123456789', '1234567890', '87654321', 'password', 'password1', 'password123', 'passw0rd', 'qwerty123', 'qwertyuiop', 'iloveyou', 'abc12345', 'abcd1234', '11223344', '12341234', 'admin123', 'welcome1', 'letmein1', 'india123', 'sunshine', 'princess', 'football', 'baseball', 'superman', 'whatever', 'trustno1', 'vitalstack', 'vitalstack123' );
	return in_array( $p, $common, true );
}

/**
 * Application passwords let scripts sign in with a long-lived key that skips
 * the login form (and any two-factor plugin). This site doesn't use them.
 */
add_filter( 'wp_is_application_passwords_available', '__return_false' );

/**
 * "Lost your password?" can be used to flood someone's inbox: at most three
 * reset emails per account per hour.
 */
function vitalstack_limit_password_resets( $errors, $user ) {
	if ( ! $user instanceof WP_User ) {
		return $errors;
	}
	$key   = 'vs_lp_' . $user->ID;
	$count = (int) get_transient( $key );
	if ( $count >= 3 ) {
		$errors->add( 'vs_reset_limit', __( '<strong>Error:</strong> A reset link was already sent several times. Please check your inbox (and spam folder) or try again in an hour.', 'vitalstack' ) );
		return $errors;
	}
	set_transient( $key, $count + 1, HOUR_IN_SECONDS );
	return $errors;
}
add_filter( 'lostpassword_errors', 'vitalstack_limit_password_resets', 10, 2 );
