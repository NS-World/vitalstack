<?php
/**
 * Email codes: a 6-digit code proves the reader owns their email address.
 *
 *   - Create account: the details are held for 10 minutes and the account
 *     is only created once the code from the email is entered.
 *   - Sign in: after the right password, a code is emailed and must be
 *     entered too (two-step sign-in). "Trust this device" skips the code on
 *     that browser for 30 days.
 *
 * Both can be switched off in Customizer > VitalStack: Accounts & Email
 * (for example if the site can't send email).
 *
 * Security:
 *   - The code is never stored: only an HMAC of it, keyed with the site's
 *     secret and a random per-attempt id that lives in an HttpOnly cookie.
 *   - A code works once, for 10 minutes, with at most 5 tries.
 *   - Sending is limited per email (5 an hour, 60 s apart) and per connection.
 *   - Readers can't skip the code by signing in on wp-login.php instead.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

const VITALSTACK_OTP_TTL       = 600; // seconds a code stays valid
const VITALSTACK_OTP_TRIES     = 5;   // wrong guesses allowed per code
const VITALSTACK_OTP_PER_EMAIL = 5;   // codes per email per hour
const VITALSTACK_OTP_GAP       = 60;  // seconds between codes to one email
const VITALSTACK_OTP_COOKIE    = 'vs_otp';
const VITALSTACK_TRUST_COOKIE  = 'vs_td';
const VITALSTACK_TRUST_DAYS    = 30;  // a trusted browser skips the sign-in code this long
const VITALSTACK_TRUST_MAX     = 5;   // trusted browsers kept per account

function vitalstack_otp_enabled() {
	return (bool) apply_filters( 'vitalstack_otp_enabled', true );
}

/**
 * Whether email codes are on for "signup" or "login".
 */
function vitalstack_codes_on( $purpose ) {
	return vitalstack_otp_enabled() && (bool) get_theme_mod( 'vitalstack_' . $purpose . '_codes', true );
}

/**
 * The pending attempt for this browser, or null.
 */
function vitalstack_otp_pending() {
	$sid = isset( $_COOKIE[ VITALSTACK_OTP_COOKIE ] ) ? sanitize_key( wp_unslash( $_COOKIE[ VITALSTACK_OTP_COOKIE ] ) ) : '';
	if ( strlen( $sid ) !== 32 ) {
		return null;
	}
	$data = get_transient( 'vs_otp_' . hash( 'sha256', $sid ) );
	if ( ! is_array( $data ) || empty( $data['expires'] ) || $data['expires'] < time() ) {
		return null;
	}
	$data['sid'] = $sid;
	return $data;
}

function vitalstack_otp_hash( $sid, $code ) {
	return hash_hmac( 'sha256', $sid . '|' . $code, wp_salt( 'auth' ) );
}

function vitalstack_otp_cookie( $sid, $expires ) {
	setcookie(
		VITALSTACK_OTP_COOKIE,
		$sid,
		array(
			'expires'  => $expires,
			'path'     => COOKIEPATH,
			'domain'   => (string) COOKIE_DOMAIN,
			'secure'   => is_ssl(),
			'httponly' => true,
			'samesite' => 'Lax',
		)
	);
}

function vitalstack_otp_forget( $data ) {
	if ( ! empty( $data['sid'] ) ) {
		delete_transient( 'vs_otp_' . hash( 'sha256', $data['sid'] ) );
	}
	vitalstack_otp_cookie( '', time() - HOUR_IN_SECONDS );
}

/**
 * "a****@gmail.com": enough to recognise, not enough to read off the screen.
 */
function vitalstack_otp_mask( $email ) {
	list( $local, $domain ) = array_pad( explode( '@', $email, 2 ), 2, '' );
	return mb_substr( $local, 0, 1 ) . str_repeat( '•', max( 2, min( 6, mb_strlen( $local ) - 1 ) ) ) . '@' . $domain;
}

function vitalstack_otp_back( $msg, $args = array() ) {
	$args['tab'] = 'code';
	vitalstack_account_redirect( $msg, $args );
}

/**
 * Whether one more code may be emailed to $email now. Counts it when $take.
 */
function vitalstack_otp_can_send( $email, $take = true ) {
	$key_mail = 'otp_mail|' . md5( $email );
	if ( vitalstack_rate_limited( 'otp_ip|' . vitalstack_client_ip(), 10, HOUR_IN_SECONDS, false )
		|| vitalstack_rate_limited( 'otp_edge|' . vitalstack_remote_ip(), 60, HOUR_IN_SECONDS, false )
		|| vitalstack_rate_limited( $key_mail, VITALSTACK_OTP_PER_EMAIL, HOUR_IN_SECONDS, false )
		|| get_transient( 'vs_otp_gap_' . md5( $email ) ) ) {
		return false;
	}
	if ( $take ) {
		vitalstack_rate_limited( 'otp_ip|' . vitalstack_client_ip(), 10, HOUR_IN_SECONDS );
		vitalstack_rate_limited( 'otp_edge|' . vitalstack_remote_ip(), 60, HOUR_IN_SECONDS );
		vitalstack_rate_limited( $key_mail, VITALSTACK_OTP_PER_EMAIL, HOUR_IN_SECONDS );
		set_transient( 'vs_otp_gap_' . md5( $email ), 1, VITALSTACK_OTP_GAP );
	}
	return true;
}

/**
 * A fresh 6-digit code.
 */
function vitalstack_otp_new_code() {
	return str_pad( (string) random_int( 0, 999999 ), 6, '0', STR_PAD_LEFT );
}

/**
 * Email the code. The wording depends on what it is for.
 */
function vitalstack_otp_mail( $email, $code, $purpose ) {
	$what = array(
		'signup' => array( __( 'Your code to finish creating your VitalStack account is:', 'vitalstack' ), /* translators: %s: code */ __( '%s is your VitalStack sign-up code', 'vitalstack' ) ),
		'login'  => array( __( 'Someone (hopefully you) signed in to VitalStack with your password. Your sign-in code is:', 'vitalstack' ), /* translators: %s: code */ __( '%s is your VitalStack sign-in code', 'vitalstack' ) ),
		'code'   => array( __( 'Your VitalStack sign-in code is:', 'vitalstack' ), /* translators: %s: code */ __( '%s is your VitalStack code', 'vitalstack' ) ),
	);
	$w    = isset( $what[ $purpose ] ) ? $what[ $purpose ] : $what['code'];
	$body = '<p>' . esc_html( $w[0] ) . '</p>'
		. '<p style="font-size:34px;font-weight:800;letter-spacing:8px;margin:12px 0 18px;color:#0a7d4f">' . esc_html( $code ) . '</p>'
		. '<p>' . esc_html__( 'Enter it on the VitalStack page where you asked for it. It works once and expires in 10 minutes.', 'vitalstack' ) . '</p>'
		. ( 'login' === $purpose
			? '<p style="color:#b42318;font-size:13px">' . esc_html__( 'Wasn’t you? Your password is known to someone else: reset it now with “Forgot?” on the sign-in page. They still can’t get in without this code.', 'vitalstack' ) . '</p>'
			: '' )
		. '<p style="color:#6b7280;font-size:13px">' . esc_html__( 'Didn’t ask for a code? Ignore this email. Never share this code with anyone, VitalStack will never ask you for it.', 'vitalstack' ) . '</p>';
	return vitalstack_mail( $email, sprintf( $w[1], $code ), $body );
}

/**
 * Start a code check for this browser: email a code and remember what to do
 * once it is entered. Ends the request with a redirect to the code screen.
 *
 * @param string $purpose "signup" or "login".
 * @param string $email   Where the code goes.
 * @param array  $payload What the verify step needs (never the code itself).
 */
function vitalstack_code_start( $purpose, $email, $payload ) {
	$email = strtolower( $email );
	$back  = array( 'redirect_to' => isset( $payload['redirect'] ) ? $payload['redirect'] : '' );
	$old   = vitalstack_otp_pending();
	// The same sign-in from the same browser while a code is still valid:
	// reuse it instead of sending another email.
	if ( $old && 'login' === $purpose && isset( $old['purpose'], $old['uid'] ) && 'login' === $old['purpose'] && (int) $old['uid'] === (int) $payload['uid'] ) {
		vitalstack_otp_back( 'code_again', $back );
	}
	if ( ! vitalstack_otp_can_send( $email ) ) {
		vitalstack_account_redirect( 'otp_wait', array( 'tab' => 'signup' === $purpose ? 'register' : 'login' ) + $back );
	}
	if ( $old ) {
		delete_transient( 'vs_otp_' . hash( 'sha256', $old['sid'] ) );
	}
	$sid  = strtolower( wp_generate_password( 32, false, false ) );
	$code = vitalstack_otp_new_code();
	$data = array_merge(
		$payload,
		array(
			'purpose' => $purpose,
			'email'   => $email,
			'hash'    => vitalstack_otp_hash( $sid, $code ),
			'tries'   => 0,
			'sends'   => 1,
			'expires' => time() + VITALSTACK_OTP_TTL,
			'mask'    => vitalstack_otp_mask( $email ),
		)
	);
	if ( ! vitalstack_otp_mail( $email, $code, $purpose ) ) {
		vitalstack_account_redirect( 'mail_failed', array( 'tab' => 'signup' === $purpose ? 'register' : 'login' ) + $back );
	}
	set_transient( 'vs_otp_' . hash( 'sha256', $sid ), $data, VITALSTACK_OTP_TTL );
	vitalstack_otp_cookie( $sid, time() + VITALSTACK_OTP_TTL );
	vitalstack_otp_back( 'signup' === $purpose ? 'code_signup' : 'code_login', $back );
}

/* ── Trusted browsers (skip the sign-in code for 30 days) ─────────────── */

function vitalstack_trust_device( $user_id ) {
	$token = wp_generate_password( 40, false, false );
	$list  = get_user_meta( $user_id, 'vs_trusted', true );
	$list  = is_array( $list ) ? array_filter(
		$list,
		function ( $exp ) {
			return $exp > time();
		}
	) : array();
	$list[ hash_hmac( 'sha256', $token, wp_salt( 'auth' ) ) ] = time() + VITALSTACK_TRUST_DAYS * DAY_IN_SECONDS;
	arsort( $list );
	update_user_meta( $user_id, 'vs_trusted', array_slice( $list, 0, VITALSTACK_TRUST_MAX, true ) );
	setcookie(
		VITALSTACK_TRUST_COOKIE,
		$user_id . '.' . $token,
		array(
			'expires'  => time() + VITALSTACK_TRUST_DAYS * DAY_IN_SECONDS,
			'path'     => COOKIEPATH,
			'domain'   => (string) COOKIE_DOMAIN,
			'secure'   => is_ssl(),
			'httponly' => true,
			'samesite' => 'Lax',
		)
	);
}

function vitalstack_device_trusted( $user_id ) {
	$raw = isset( $_COOKIE[ VITALSTACK_TRUST_COOKIE ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ VITALSTACK_TRUST_COOKIE ] ) ) : '';
	if ( ! preg_match( '/^(\d+)\.([A-Za-z0-9]{40})$/', $raw, $m ) || (int) $m[1] !== (int) $user_id ) {
		return false;
	}
	$list = get_user_meta( $user_id, 'vs_trusted', true );
	$key  = hash_hmac( 'sha256', $m[2], wp_salt( 'auth' ) );
	return is_array( $list ) && isset( $list[ $key ] ) && $list[ $key ] > time();
}

// A new password (reset or changed) forgets every trusted browser.
add_action(
	'after_password_reset',
	function ( $user ) {
		delete_user_meta( $user->ID, 'vs_trusted' );
	}
);
add_action(
	'profile_update',
	function ( $user_id, $old ) {
		$new = get_userdata( $user_id );
		if ( $new && $old && $new->user_pass !== $old->user_pass ) {
			delete_user_meta( $user_id, 'vs_trusted' );
		}
	},
	10,
	2
);

/**
 * Readers sign in on /account/ (password + code). A reader's password alone
 * on wp-login.php would skip the code, so it is refused there. Staff keep
 * wp-login.php (and any two-factor plugin they use).
 */
function vitalstack_codes_guard( $user ) {
	if ( ! $user instanceof WP_User || ! empty( $GLOBALS['vitalstack_code_login'] ) || ! vitalstack_codes_on( 'login' ) ) {
		return $user;
	}
	if ( user_can( $user, 'edit_posts' ) ) {
		return $user;
	}
	return new WP_Error(
		'vs_use_account',
		sprintf(
			/* translators: %s: link to the account page */
			__( 'Please sign in on %s: we email you a code to keep your account safe.', 'vitalstack' ),
			'<a href="' . esc_url( vitalstack_account_url() ) . '">' . esc_html__( 'your account page', 'vitalstack' ) . '</a>'
		)
	);
}
add_filter( 'authenticate', 'vitalstack_codes_guard', 110 );

/* ── Send a new code for the attempt on screen ─────────────────────── */

function vitalstack_handle_code_resend() {
	if ( ! isset( $_POST['_vsnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_code_resend' ) ) {
		vitalstack_otp_back( 'bad_nonce' );
	}
	$back = array( 'redirect_to' => vitalstack_requested_redirect() );
	$data = vitalstack_otp_pending();
	if ( ! $data || empty( $data['purpose'] ) ) {
		vitalstack_account_redirect( 'otp_expired', $back );
	}
	if ( $data['sends'] >= 3 || ! vitalstack_otp_can_send( $data['email'] ) ) {
		vitalstack_otp_back( 'otp_wait', $back );
	}
	$code = vitalstack_otp_new_code();
	$sid  = $data['sid'];
	unset( $data['sid'] );
	// A new code replaces the old one; the attempt does not get longer than one TTL from now.
	$data['hash']    = vitalstack_otp_hash( $sid, $code );
	$data['tries']   = 0;
	$data['expires'] = time() + VITALSTACK_OTP_TTL;
	++$data['sends'];
	if ( ! vitalstack_otp_mail( $data['email'], $code, $data['purpose'] ) ) {
		vitalstack_otp_back( 'mail_failed', $back );
	}
	set_transient( 'vs_otp_' . hash( 'sha256', $sid ), $data, VITALSTACK_OTP_TTL );
	vitalstack_otp_cookie( $sid, $data['expires'] );
	vitalstack_otp_back( 'code_resent', $back );
}
add_action( 'admin_post_nopriv_vs_code_resend', 'vitalstack_handle_code_resend' );
add_action( 'admin_post_vs_code_resend', 'vitalstack_handle_code_resend' );

// The old "Email code" tab (sign in with a code only) is gone: password +
// code is stronger. Old forms still open in someone's browser land on Sign in.
function vitalstack_handle_otp_send() {
	vitalstack_account_redirect( '', array( 'tab' => 'login' ) );
}
add_action( 'admin_post_nopriv_vs_otp_send', 'vitalstack_handle_otp_send' );
add_action( 'admin_post_vs_otp_send', 'vitalstack_handle_otp_send' );

/* ── Step 2: check the code ───────────────────────────────────────────── */

function vitalstack_handle_otp_verify() {
	if ( ! isset( $_POST['_vsnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_otp_verify' ) ) {
		vitalstack_otp_back( 'bad_nonce' );
	}
	$redirect = vitalstack_requested_redirect();
	$back     = array( 'redirect_to' => $redirect );
	$data     = vitalstack_otp_pending();
	if ( ! $data ) {
		vitalstack_otp_back( 'otp_expired', $back );
	}

	// Count the try before comparing, so parallel requests can't get extra guesses.
	++$data['tries'];
	$left = $data['expires'] - time();
	$sid  = $data['sid'];
	unset( $data['sid'] );
	set_transient( 'vs_otp_' . hash( 'sha256', $sid ), $data, max( 1, $left ) );
	$data['sid'] = $sid;

	$code = isset( $_POST['code'] ) ? preg_replace( '/\D/', '', (string) wp_unslash( $_POST['code'] ) ) : '';
	if ( $data['tries'] > VITALSTACK_OTP_TRIES ) {
		vitalstack_otp_forget( $data );
		vitalstack_otp_back( 'otp_expired', $back );
	}
	if ( 6 !== strlen( $code ) || ! hash_equals( $data['hash'], vitalstack_otp_hash( $sid, $code ) ) ) {
		vitalstack_otp_back( $data['tries'] >= VITALSTACK_OTP_TRIES ? 'otp_expired' : 'otp_wrong', $back );
	}
	vitalstack_otp_forget( $data );

	if ( isset( $data['purpose'] ) && 'signup' === $data['purpose'] ) {
		vitalstack_code_finish_signup( $data, $redirect );
	}
	if ( isset( $data['purpose'] ) && 'login' === $data['purpose'] ) {
		vitalstack_code_finish_login( $data, $redirect );
	}

	// Attempts started from the old "Email code" tab, before this update.
	$user = get_user_by( 'email', $data['email'] );
	if ( $user && user_can( $user, 'manage_options' ) ) {
		vitalstack_account_redirect( 'otp_admin', array( 'tab' => 'login' ) );
	}
	$new = false;
	if ( ! $user ) {
		if ( ! vitalstack_accounts_enabled() ) {
			vitalstack_account_redirect( 'closed' );
		}
		$user = vitalstack_otp_create_user( $data['email'], $data['name'] );
		if ( ! $user ) {
			vitalstack_otp_back( 'failed', $back );
		}
		$new = true;
	}

	// The code reached their inbox, so the address is confirmed.
	update_user_meta( $user->ID, 'vs_email_verified', 1 );
	if ( ! empty( $data['subscribe'] ) ) {
		vitalstack_set_subscribed( $user->ID, true );
	}

	wp_set_current_user( $user->ID );
	wp_set_auth_cookie( $user->ID, true, is_ssl() );
	do_action( 'wp_login', $user->user_login, $user );

	vitalstack_account_redirect( $new ? 'otp_welcome' : 'welcome_back', array(), $redirect );
}
add_action( 'admin_post_nopriv_vs_otp_verify', 'vitalstack_handle_otp_verify' );
add_action( 'admin_post_vs_otp_verify', 'vitalstack_handle_otp_verify' );

/**
 * Right sign-up code: now the account is created, already confirmed.
 */
function vitalstack_code_finish_signup( $data, $redirect ) {
	if ( ! vitalstack_accounts_enabled() ) {
		vitalstack_account_redirect( 'closed', array( 'tab' => 'register' ) );
	}
	// Someone may have taken the address in the last 10 minutes.
	if ( email_exists( $data['email'] ) ) {
		vitalstack_account_redirect( 'email_exists', array( 'tab' => 'login', 'redirect_to' => $redirect ) );
	}
	$user = vitalstack_otp_create_user( $data['email'], $data['name'] );
	if ( ! $user ) {
		vitalstack_account_redirect( 'failed', array( 'tab' => 'register', 'redirect_to' => $redirect ) );
	}
	// The password they chose was kept only as its hash; put it on the account.
	global $wpdb;
	$wpdb->update( $wpdb->users, array( 'user_pass' => $data['pass'] ), array( 'ID' => $user->ID ) );
	clean_user_cache( $user->ID );

	update_user_meta( $user->ID, 'vs_email_verified', 1 );
	vitalstack_set_subscribed( $user->ID, ! empty( $data['subscribe'] ) );
	vitalstack_send_welcome( $user->ID );

	wp_set_current_user( $user->ID );
	wp_set_auth_cookie( $user->ID, true, is_ssl() );
	vitalstack_trust_device( $user->ID );
	vitalstack_account_redirect( 'otp_welcome', array(), $redirect );
}

/**
 * Right sign-in code: finish the sign-in that the password started.
 */
function vitalstack_code_finish_login( $data, $redirect ) {
	$user = get_userdata( (int) $data['uid'] );
	// The account must still exist and still use the address the code went to.
	if ( ! $user || strtolower( $user->user_email ) !== $data['email'] ) {
		vitalstack_account_redirect( 'failed', array( 'tab' => 'login' ) );
	}
	update_user_meta( $user->ID, 'vs_email_verified', 1 );
	wp_set_current_user( $user->ID );
	wp_set_auth_cookie( $user->ID, ! empty( $data['remember'] ), is_ssl() );
	do_action( 'wp_login', $user->user_login, $user );
	if ( ! empty( $_POST['trust'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- checked in the verify handler
		vitalstack_trust_device( $user->ID );
	}
	if ( ! empty( $data['subscribe'] ) ) {
		vitalstack_set_subscribed( $user->ID, true );
		vitalstack_account_redirect( 'subscribed', array(), $redirect );
	}
	vitalstack_account_redirect( 'welcome_back', array(), $redirect );
}

/**
 * Welcome email for an account confirmed with a code (it replaces the
 * "confirm your email" link email, and carries the same free gift).
 */
function vitalstack_send_welcome( $user_id ) {
	$user = get_userdata( $user_id );
	if ( ! $user ) {
		return false;
	}
	$body = '<p>' . sprintf( /* translators: %s: name */ esc_html__( 'Hi %s,', 'vitalstack' ), esc_html( $user->display_name ) ) . '</p>'
		. '<p>' . esc_html__( 'Welcome to VitalStack! Your account is ready and your email is confirmed. Pick a course, learn with live animations, and earn your certificate.', 'vitalstack' ) . '</p>'
		. vitalstack_email_button( vitalstack_account_url(), __( 'Open my dashboard', 'vitalstack' ) )
		. ( function_exists( 'vitalstack_gift_url' ) ? '<p>🎁 ' . esc_html__( 'Your free 30-Day Java Interview Plan is ready:', 'vitalstack' ) . ' <a href="' . esc_url( vitalstack_gift_url() ) . '" style="color:#0a7d4f;font-weight:700">' . esc_html__( 'download the PDF', 'vitalstack' ) . '</a>.</p>' : '' );
	return vitalstack_mail( $user->user_email, __( 'Welcome to VitalStack', 'vitalstack' ), $body );
}

/**
 * A reader account for a confirmed email. It gets a long random password, so
 * the reader can keep using codes, or set a password later with "Forgot?".
 */
function vitalstack_otp_create_user( $email, $name ) {
	$name = '' !== trim( $name ) ? $name : ucfirst( strstr( $email, '@', true ) );
	$base = sanitize_user( strtolower( strstr( $email, '@', true ) ), true );
	$base = $base ? mb_substr( $base, 0, 40 ) : 'reader';
	$login = $base;
	$n     = 1;
	while ( username_exists( $login ) ) {
		$login = $base . ++$n;
	}
	$id = wp_insert_user(
		array(
			'user_login'   => $login,
			'user_email'   => $email,
			'user_pass'    => wp_generate_password( 32, true, true ),
			'display_name' => $name,
			'first_name'   => $name,
			'nickname'     => $name,
			'role'         => 'subscriber',
		)
	);
	return is_wp_error( $id ) ? null : get_userdata( $id );
}

/* ── Start over ───────────────────────────────────────────────────────── */

function vitalstack_handle_otp_reset() {
	$data = null;
	if ( isset( $_POST['_vsnonce'] ) && wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_otp_reset' ) ) {
		$data = vitalstack_otp_pending();
		if ( $data ) {
			vitalstack_otp_forget( $data );
		}
	}
	$tab = isset( $data['purpose'] ) && 'signup' === $data['purpose'] ? 'register' : 'login';
	vitalstack_account_redirect( '', array( 'tab' => $tab, 'redirect_to' => vitalstack_requested_redirect() ) );
}
add_action( 'admin_post_nopriv_vs_otp_reset', 'vitalstack_handle_otp_reset' );
add_action( 'admin_post_vs_otp_reset', 'vitalstack_handle_otp_reset' );

add_filter(
	'vitalstack_account_messages',
	function ( $msgs ) {
		return array_merge(
			$msgs,
			array(
				'otp_sent'    => array( 'success', __( 'Check your inbox: we sent a 6-digit code (look in the spam folder too). It expires in 10 minutes.', 'vitalstack' ) ),
				'otp_wrong'   => array( 'error', __( 'That code isn’t right. Check the latest email and try again.', 'vitalstack' ) ),
				'otp_expired' => array( 'error', __( 'That code has expired or was tried too many times. Ask for a new one.', 'vitalstack' ) ),
				'otp_wait'    => array( 'error', __( 'Please wait a minute before asking for another code.', 'vitalstack' ) ),
				'otp_welcome' => array( 'success', __( 'Welcome to VitalStack! Your account is ready and your email is confirmed.', 'vitalstack' ) ),
				'otp_admin'   => array( 'info', __( 'Site administrators sign in with their password.', 'vitalstack' ) ),
				'code_signup' => array( 'success', __( 'Almost done! We emailed you a 6-digit code (check spam too). Enter it below to create your account.', 'vitalstack' ) ),
				'code_login'  => array( 'success', __( 'Password correct. For your safety we emailed you a 6-digit code (check spam too). Enter it below to sign in.', 'vitalstack' ) ),
				'code_again'  => array( 'info', __( 'Enter the code we already emailed you. You can ask for a new one below.', 'vitalstack' ) ),
				'code_resent' => array( 'success', __( 'New code sent. Only the newest code works.', 'vitalstack' ) ),
				'mail_failed' => array( 'error', __( 'We couldn’t send the email right now. Please try again in a few minutes.', 'vitalstack' ) ),
			)
		);
	}
);

/**
 * "Check your email" step on /account/, for a code sent at sign-up or sign-in.
 *
 * @param array  $pending  The attempt (vitalstack_otp_pending()).
 * @param string $redirect Where to go afterwards.
 */
function vitalstack_code_panel( $pending, $redirect ) {
	$purpose = isset( $pending['purpose'] ) ? $pending['purpose'] : 'code';
	$titles  = array(
		'signup' => __( 'Confirm your email', 'vitalstack' ),
		'login'  => __( 'Two-step sign-in', 'vitalstack' ),
		'code'   => __( 'Enter your code', 'vitalstack' ),
	);
	$steps   = 'signup' === $purpose
		? array( __( 'Your details', 'vitalstack' ), __( 'Email code', 'vitalstack' ), __( 'Account ready', 'vitalstack' ) )
		: array( __( 'Password', 'vitalstack' ), __( 'Email code', 'vitalstack' ), __( 'Signed in', 'vitalstack' ) );
	?>
	<div class="auth-form otp-panel code-step" id="panel-code">
		<ol class="code-steps" aria-label="<?php esc_attr_e( 'Progress', 'vitalstack' ); ?>">
			<?php foreach ( $steps as $i => $label ) : ?>
				<li class="<?php echo 0 === $i ? 'is-done' : ( 1 === $i ? 'is-now' : '' ); ?>"<?php echo 1 === $i ? ' aria-current="step"' : ''; ?>><span><?php echo 0 === $i ? '✓' : (int) $i + 1; ?></span> <?php echo esc_html( $label ); ?></li>
			<?php endforeach; ?>
		</ol>
		<div class="code-head">
			<span class="code-icon" aria-hidden="true">✉️</span>
			<h2><?php echo esc_html( isset( $titles[ $purpose ] ) ? $titles[ $purpose ] : $titles['code'] ); ?></h2>
			<p class="otp-sent-to">
				<?php
				/* translators: %s: masked email address */
				echo esc_html( sprintf( __( 'We emailed a 6-digit code to %s. It expires in 10 minutes.', 'vitalstack' ), $pending['mask'] ) );
				?>
			</p>
		</div>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="otp-verify">
			<input type="hidden" name="action" value="vs_otp_verify">
			<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect ); ?>">
			<?php wp_nonce_field( 'vs_otp_verify', '_vsnonce' ); ?>
			<label class="field">
				<span><?php esc_html_e( '6-digit code', 'vitalstack' ); ?></span>
				<input class="otp-input" type="text" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code" placeholder="••••••" required autofocus>
			</label>
			<?php if ( 'login' === $purpose ) : ?>
				<label class="check"><input type="checkbox" name="trust" value="1" checked> <?php esc_html_e( 'Trust this device for 30 days (don’t tick on a shared computer)', 'vitalstack' ); ?></label>
			<?php endif; ?>
			<button type="submit" class="btn btn-primary btn-block"><?php echo 'signup' === $purpose ? esc_html__( 'Verify & create my account', 'vitalstack' ) : esc_html__( 'Verify & sign in', 'vitalstack' ); ?></button>
		</form>
		<div class="code-actions">
			<?php if ( ! empty( $pending['purpose'] ) ) : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="vs_code_resend">
					<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect ); ?>">
					<?php wp_nonce_field( 'vs_code_resend', '_vsnonce' ); ?>
					<button type="submit" class="btn-link" data-resend-wait="60"><?php esc_html_e( 'Didn’t get it? Send a new code', 'vitalstack' ); ?></button>
				</form>
			<?php endif; ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="vs_otp_reset">
				<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect ); ?>">
				<?php wp_nonce_field( 'vs_otp_reset', '_vsnonce' ); ?>
				<button type="submit" class="btn-link"><?php echo 'signup' === $purpose ? esc_html__( 'Wrong email? Start again', 'vitalstack' ) : esc_html__( 'Cancel and go back', 'vitalstack' ); ?></button>
			</form>
		</div>
		<p class="code-tip muted small"><?php esc_html_e( 'Tip: no email after a minute? Look in Spam or Promotions, and search for “VitalStack”.', 'vitalstack' ); ?></p>
	</div>
	<?php
}
