<?php
/**
 * 4.20.2: signing out really signs out.
 *
 * "Trust this device" lets a browser skip the emailed code when its
 * session simply ran out. It must never skip the code after the reader
 * signed out, or after the account was signed in on another device:
 *
 * - Sign out forgets this browser's trust (and any code attempt in progress).
 * - A new sign-in keeps the trust of this browser only. With one device at a
 *   time, every other browser was just signed out, so it must use the full
 *   password + code flow again.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/** The trust key (HMAC) of this browser's trust cookie for $user_id, or ''. */
function vitalstack_trust_key_here( $user_id ) {
	if ( ! defined( 'VITALSTACK_TRUST_COOKIE' ) || empty( $_COOKIE[ VITALSTACK_TRUST_COOKIE ] ) ) {
		return '';
	}
	$raw = sanitize_text_field( wp_unslash( $_COOKIE[ VITALSTACK_TRUST_COOKIE ] ) );
	if ( ! preg_match( '/^(\d+)\.([A-Za-z0-9]{40})$/', $raw, $m ) || (int) $m[1] !== (int) $user_id ) {
		return '';
	}
	return hash_hmac( 'sha256', $m[2], wp_salt( 'auth' ) );
}

/** Expire a cookie set by the theme. */
function vitalstack_expire_cookie( $name, $httponly = true ) {
	if ( headers_sent() ) {
		return;
	}
	setcookie(
		$name,
		'',
		array(
			'expires'  => time() - YEAR_IN_SECONDS,
			'path'     => COOKIEPATH,
			'domain'   => (string) COOKIE_DOMAIN,
			'secure'   => is_ssl(),
			'httponly' => $httponly,
			'samesite' => 'Lax',
		)
	);
	unset( $_COOKIE[ $name ] );
}

/**
 * Sign out: this browser is no longer trusted and any half-done code
 * attempt is dropped, so the next sign-in asks for password + code.
 *
 * @param int $user_id The reader who signed out (WP 5.5+).
 */
function vitalstack_auth_on_logout( $user_id = 0 ) {
	$user_id = (int) $user_id;
	if ( $user_id ) {
		$key  = vitalstack_trust_key_here( $user_id );
		$list = get_user_meta( $user_id, 'vs_trusted', true );
		if ( $key && is_array( $list ) && isset( $list[ $key ] ) ) {
			unset( $list[ $key ] );
			update_user_meta( $user_id, 'vs_trusted', $list );
		}
	}
	if ( defined( 'VITALSTACK_TRUST_COOKIE' ) ) {
		vitalstack_expire_cookie( VITALSTACK_TRUST_COOKIE );
	}
	if ( defined( 'VITALSTACK_OTP_COOKIE' ) && function_exists( 'vitalstack_otp_pending' ) ) {
		$pending = vitalstack_otp_pending();
		if ( $pending && function_exists( 'vitalstack_otp_forget' ) ) {
			vitalstack_otp_forget( $pending );
		}
		vitalstack_expire_cookie( VITALSTACK_OTP_COOKIE );
	}
}
add_action( 'wp_logout', 'vitalstack_auth_on_logout', 5 );

/**
 * A reader signed in: keep the trust of this browser only (one device at a
 * time). Runs inside wp_set_auth_cookie(), before "Trust this device" adds
 * a fresh entry for this browser.
 *
 * @param string $cookie Unused.
 * @param int    $expire Unused.
 * @param int    $exp    Unused.
 * @param int    $user_id Reader.
 */
function vitalstack_auth_prune_trust( $cookie, $expire, $exp, $user_id ) {
	$user_id = (int) $user_id;
	if ( ! $user_id || ( function_exists( 'vitalstack_single_session_applies' ) && ! vitalstack_single_session_applies( $user_id ) ) ) {
		return;
	}
	$list = get_user_meta( $user_id, 'vs_trusted', true );
	if ( ! is_array( $list ) || ! $list ) {
		return;
	}
	$key  = vitalstack_trust_key_here( $user_id );
	$keep = ( $key && isset( $list[ $key ] ) ) ? array( $key => $list[ $key ] ) : array();
	if ( count( $keep ) !== count( $list ) ) {
		update_user_meta( $user_id, 'vs_trusted', $keep );
	}
}
add_action( 'set_logged_in_cookie', 'vitalstack_auth_prune_trust', 20, 4 );
