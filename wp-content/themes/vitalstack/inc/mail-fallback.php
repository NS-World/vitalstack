<?php
/**
 * 4.20.1: sign-in codes keep arriving even when an SMTP plugin is set up wrong.
 *
 * If a theme email (sign-in / sign-up code, receipts, lesson emails) fails
 * through an SMTP plugin (wrong password, wrong port, From address the
 * mailbox does not own), it is sent once more with the server's built-in
 * mail, the way it went before the SMTP plugin was installed. The SMTP
 * error is saved and shown to admins so the setting can be fixed.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

const VITALSTACK_MAIL_ERR_OPT = 'vitalstack_mail_error';

/**
 * Remember why the last email failed (admins only ever see it).
 *
 * @param WP_Error $error From wp_mail().
 */
function vitalstack_mail_record_error( $error ) {
	if ( ! is_wp_error( $error ) ) {
		return;
	}
	$msg = wp_strip_all_tags( (string) $error->get_error_message() );
	// Never store anything that looks like a password or an address list.
	$msg = preg_replace( '/[^\s@]+@[^\s@]+/', '[email]', $msg );
	$old = get_option( VITALSTACK_MAIL_ERR_OPT );
	update_option(
		VITALSTACK_MAIL_ERR_OPT,
		array(
			't'        => time(),
			'msg'      => mb_substr( $msg, 0, 300 ),
			'smtp'     => function_exists( 'vitalstack_mail_smtp_plugin' ) ? vitalstack_mail_smtp_plugin() : '',
			'fallback' => is_array( $old ) && isset( $old['fallback'] ) ? (int) $old['fallback'] : 0,
			'retrying' => ! empty( $GLOBALS['vitalstack_mail_retrying'] ),
		),
		false
	);
}
add_action( 'wp_mail_failed', 'vitalstack_mail_record_error' );

/**
 * A normal (non-backup) send worked again: the SMTP setting is fixed, so
 * the admin warning goes away by itself.
 */
function vitalstack_mail_clear_error() {
	if ( empty( $GLOBALS['vitalstack_mail_retrying'] ) && false !== get_option( VITALSTACK_MAIL_ERR_OPT ) ) {
		delete_option( VITALSTACK_MAIL_ERR_OPT );
	}
}
add_action( 'wp_mail_succeeded', 'vitalstack_mail_clear_error' );

/** Switches this one send to the server's built-in mail. */
function vitalstack_mail_force_builtin( $phpmailer ) {
	$phpmailer->isMail();
	if ( empty( $phpmailer->Sender ) && is_email( $phpmailer->From ) ) {
		$phpmailer->Sender = $phpmailer->From;
	}
}

/**
 * Called by vitalstack_mail() when wp_mail() returned false.
 * Retries once with built-in mail, only when an SMTP plugin is active
 * (without one, built-in mail already failed and a retry would not help).
 *
 * @return bool Whether the backup send worked.
 */
function vitalstack_mail_retry( $to, $subject, $html, $headers ) {
	static $busy = false;
	$smtp = function_exists( 'vitalstack_mail_smtp_plugin' ) ? vitalstack_mail_smtp_plugin() : '';
	if ( $busy || '' === $smtp || ! apply_filters( 'vitalstack_mail_fallback', true, $to ) ) {
		return false;
	}
	$busy                                = true;
	$GLOBALS['vitalstack_mail_retrying'] = true;
	add_action( 'phpmailer_init', 'vitalstack_mail_force_builtin', PHP_INT_MAX );
	$sent = wp_mail( $to, $subject, $html, $headers );
	remove_action( 'phpmailer_init', 'vitalstack_mail_force_builtin', PHP_INT_MAX );
	$GLOBALS['vitalstack_mail_retrying'] = false;
	$busy                                = false;
	if ( $sent ) {
		$err = get_option( VITALSTACK_MAIL_ERR_OPT );
		if ( is_array( $err ) ) {
			$err['fallback'] = ( isset( $err['fallback'] ) ? (int) $err['fallback'] : 0 ) + 1;
			update_option( VITALSTACK_MAIL_ERR_OPT, $err, false );
		}
	}
	return $sent;
}

/** Admin warning while the SMTP plugin is failing. */
function vitalstack_mail_error_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$err = get_option( VITALSTACK_MAIL_ERR_OPT );
	if ( ! is_array( $err ) || empty( $err['t'] ) || time() - (int) $err['t'] > 7 * DAY_IN_SECONDS ) {
		return;
	}
	$when = human_time_diff( (int) $err['t'], time() );
	echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'VitalStack: emails are failing through your SMTP plugin.', 'vitalstack' ) . '</strong> ';
	/* translators: 1: plugin name, 2: time ago, 3: error text */
	echo esc_html( sprintf( __( '%1$s could not send an email %2$s ago: "%3$s".', 'vitalstack' ), $err['smtp'] ? $err['smtp'] : __( 'The mail system', 'vitalstack' ), $when, $err['msg'] ) ) . '</p>';
	if ( ! empty( $err['fallback'] ) ) {
		/* translators: %d: number of emails */
		echo '<p>' . esc_html( sprintf( _n( 'So readers were not blocked, %d email (sign-in codes etc.) was sent with the server’s built-in mail instead. That works, but can land in spam more often.', 'So readers were not blocked, %d emails (sign-in codes etc.) were sent with the server’s built-in mail instead. That works, but can land in spam more often.', (int) $err['fallback'], 'vitalstack' ), (int) $err['fallback'] ) ) . '</p>';
	}
	echo '<p>' . esc_html__( 'Fix: open the SMTP plugin settings and check the SMTP host, port + encryption (smtp.hostinger.com, 465 + SSL), the username (the full mailbox address), its password, and that the From email is that same mailbox. Then send a test email. This warning disappears after the next email goes through normally.', 'vitalstack' ) . '</p></div>';
}
add_action( 'admin_notices', 'vitalstack_mail_error_notice' );
