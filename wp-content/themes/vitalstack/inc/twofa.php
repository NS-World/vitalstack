<?php
/**
 * Two-factor sign-in with an authenticator app (Google Authenticator,
 * Microsoft Authenticator…) plus one-time backup codes, and the "fresh
 * security check" (step-up) that buying requires.
 *
 *   - TOTP (RFC 6238): 6 digits, 30-second steps, ±1 step for clock drift,
 *     and a code can't be used twice. The secret is stored encrypted.
 *   - 10 backup codes, each usable once, stored only as hashes.
 *   - Readers without an app keep the email code from inc/otp.php.
 *   - 5 wrong codes in a row: 15-minute pause and an email to the owner.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

const VITALSTACK_STEPUP_TTL = 15 * MINUTE_IN_SECONDS; // how fresh a security check must be to pay

/* ── Base32 and TOTP ──────────────────────────────────────────────────── */

function vitalstack_b32_encode( $bin ) {
	$alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
	$bits  = '';
	foreach ( str_split( $bin ) as $c ) {
		$bits .= str_pad( decbin( ord( $c ) ), 8, '0', STR_PAD_LEFT );
	}
	$out = '';
	foreach ( str_split( $bits, 5 ) as $chunk ) {
		$out .= $alpha[ bindec( str_pad( $chunk, 5, '0' ) ) ];
	}
	return $out;
}

function vitalstack_b32_decode( $str ) {
	$alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
	$str   = strtoupper( preg_replace( '/[^A-Za-z2-7]/', '', (string) $str ) );
	$bits  = '';
	foreach ( str_split( $str ) as $c ) {
		$bits .= str_pad( decbin( strpos( $alpha, $c ) ), 5, '0', STR_PAD_LEFT );
	}
	$out = '';
	foreach ( str_split( $bits, 8 ) as $byte ) {
		if ( 8 === strlen( $byte ) ) {
			$out .= chr( bindec( $byte ) );
		}
	}
	return $out;
}

/**
 * The 6-digit code for one 30-second step.
 */
function vitalstack_totp_at( $secret_bin, $step ) {
	$hash   = hash_hmac( 'sha1', pack( 'N*', 0, $step ), $secret_bin, true );
	$offset = ord( $hash[19] ) & 0xf;
	$num    = ( ( ord( $hash[ $offset ] ) & 0x7f ) << 24 ) | ( ord( $hash[ $offset + 1 ] ) << 16 ) | ( ord( $hash[ $offset + 2 ] ) << 8 ) | ord( $hash[ $offset + 3 ] );
	return str_pad( (string) ( $num % 1000000 ), 6, '0', STR_PAD_LEFT );
}

function vitalstack_totp_secret( $user_id ) {
	$sealed = (string) get_user_meta( $user_id, 'vs_totp_secret', true );
	return '' === $sealed ? '' : vitalstack_b32_decode( vitalstack_pay_open_seal( $sealed ) );
}

function vitalstack_totp_on( $user_id ) {
	return (bool) get_user_meta( $user_id, 'vs_totp_on', true ) && '' !== vitalstack_totp_secret( $user_id );
}

/**
 * Check an app code for a user. Each code works once (replay is refused).
 *
 * @param string $secret_bin Optional: check against this secret (during setup).
 */
function vitalstack_totp_check( $user_id, $code, $secret_bin = '' ) {
	$code   = preg_replace( '/\D/', '', (string) $code );
	$secret = $secret_bin ? $secret_bin : vitalstack_totp_secret( $user_id );
	if ( 6 !== strlen( $code ) || '' === $secret ) {
		return false;
	}
	$now  = (int) floor( time() / 30 );
	$last = (int) get_user_meta( $user_id, 'vs_totp_last', true );
	for ( $d = -1; $d <= 1; $d++ ) {
		if ( $now + $d > $last && hash_equals( vitalstack_totp_at( $secret, $now + $d ), $code ) ) {
			update_user_meta( $user_id, 'vs_totp_last', $now + $d );
			return true;
		}
	}
	return false;
}

/* ── Backup codes ─────────────────────────────────────────────────────── */

function vitalstack_backup_hash( $code ) {
	return hash_hmac( 'sha256', strtolower( preg_replace( '/[^A-Za-z0-9]/', '', $code ) ), wp_salt( 'auth' ) . '|backup' );
}

/**
 * Make 10 new backup codes (the old ones stop working). Returns them once.
 */
function vitalstack_backup_new( $user_id ) {
	$codes = array();
	$keep  = array();
	for ( $i = 0; $i < 10; $i++ ) {
		$raw     = strtolower( wp_generate_password( 8, false, false ) );
		$codes[] = substr( $raw, 0, 4 ) . '-' . substr( $raw, 4 );
		$keep[]  = vitalstack_backup_hash( $raw );
	}
	update_user_meta( $user_id, 'vs_backup_codes', $keep );
	return $codes;
}

function vitalstack_backup_use( $user_id, $code ) {
	$list = (array) get_user_meta( $user_id, 'vs_backup_codes', true );
	$h    = vitalstack_backup_hash( $code );
	foreach ( $list as $k => $v ) {
		if ( hash_equals( (string) $v, $h ) ) {
			unset( $list[ $k ] );
			update_user_meta( $user_id, 'vs_backup_codes', array_values( $list ) );
			if ( function_exists( 'vitalstack_audit' ) ) {
				vitalstack_audit( '2fa_backup_used', $user_id, array( 'left' => count( $list ) ) );
			}
			return true;
		}
	}
	return false;
}

function vitalstack_backup_left( $user_id ) {
	return count( array_filter( (array) get_user_meta( $user_id, 'vs_backup_codes', true ) ) );
}

/* ── Wrong-code pause and the fresh security check ────────────────────── */

function vitalstack_2fa_locked( $user_id ) {
	return (bool) get_transient( 'vs_2fa_lock_' . $user_id );
}

/**
 * Count a wrong second-factor code. The 5th in a row pauses 2FA for 15 minutes.
 */
function vitalstack_2fa_failed( $user_id ) {
	$k = 'vs_2fa_fails_' . $user_id;
	$n = (int) get_transient( $k ) + 1;
	set_transient( $k, $n, 15 * MINUTE_IN_SECONDS );
	if ( $n >= 5 ) {
		delete_transient( $k );
		set_transient( 'vs_2fa_lock_' . $user_id, 1, 15 * MINUTE_IN_SECONDS );
		$user = get_userdata( $user_id );
		if ( $user ) {
			vitalstack_mail(
				$user->user_email,
				__( 'Security alert: 5 wrong sign-in codes', 'vitalstack' ),
				'<p>' . esc_html__( 'Someone entered 5 wrong security codes for your VitalStack account. Code entry is paused for 15 minutes.', 'vitalstack' ) . '</p><p>' . esc_html__( 'If this was not you, someone knows your password: reset it now with “Forgot?” on the sign-in page.', 'vitalstack' ) . '</p>'
			);
		}
		if ( function_exists( 'vitalstack_audit' ) ) {
			vitalstack_audit( '2fa_locked', $user_id, array(), true );
		}
	}
}

function vitalstack_2fa_passed( $user_id ) {
	delete_transient( 'vs_2fa_fails_' . $user_id );
	update_user_meta( $user_id, 'vs_stepup', time() );
}

function vitalstack_stepup_fresh( $user_id ) {
	return time() - (int) get_user_meta( $user_id, 'vs_stepup', true ) < VITALSTACK_STEPUP_TTL;
}

/**
 * App code or backup code for this user. Counts failures and the pause.
 */
function vitalstack_2fa_verify( $user_id, $code ) {
	if ( vitalstack_2fa_locked( $user_id ) ) {
		return false;
	}
	$code = trim( (string) $code );
	$ok   = vitalstack_totp_on( $user_id ) && ( vitalstack_totp_check( $user_id, $code ) || ( strlen( preg_replace( '/[^A-Za-z0-9]/', '', $code ) ) === 8 && vitalstack_backup_use( $user_id, $code ) ) );
	if ( $ok ) {
		vitalstack_2fa_passed( $user_id );
	} else {
		vitalstack_2fa_failed( $user_id );
	}
	return $ok;
}

/* ── Setting up the app (account page) ────────────────────────────────── */

function vitalstack_handle_2fa_setup() {
	if ( ! is_user_logged_in() || ! isset( $_POST['_vsnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_2fa' ) ) {
		vitalstack_account_redirect( 'bad_nonce' );
	}
	$uid  = get_current_user_id();
	$step = isset( $_POST['step'] ) ? sanitize_key( $_POST['step'] ) : '';
	$back = array( 'tab' => 'security' );
	if ( vitalstack_rate_limited( '2fa_setup|' . $uid, 20, HOUR_IN_SECONDS ) ) {
		vitalstack_account_redirect( 'rate', $back );
	}
	if ( 'start' === $step ) {
		// A new secret waits 15 minutes for its first code; nothing changes until then.
		set_transient( 'vs_totp_new_' . $uid, vitalstack_pay_seal( vitalstack_b32_encode( random_bytes( 20 ) ) ), 15 * MINUTE_IN_SECONDS );
		vitalstack_account_redirect( '2fa_scan', $back );
	}
	if ( 'confirm' === $step ) {
		$sealed = (string) get_transient( 'vs_totp_new_' . $uid );
		$secret = $sealed ? vitalstack_b32_decode( vitalstack_pay_open_seal( $sealed ) ) : '';
		if ( '' === $secret ) {
			vitalstack_account_redirect( '2fa_expired', $back );
		}
		if ( ! vitalstack_totp_check( $uid, isset( $_POST['code'] ) ? wp_unslash( $_POST['code'] ) : '', $secret ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- digits only
			vitalstack_2fa_failed( $uid );
			vitalstack_account_redirect( '2fa_wrong', array_merge( $back, array( 'scan' => 1 ) ) );
		}
		delete_transient( 'vs_totp_new_' . $uid );
		update_user_meta( $uid, 'vs_totp_secret', $sealed );
		update_user_meta( $uid, 'vs_totp_on', 1 );
		vitalstack_2fa_passed( $uid );
		set_transient( 'vs_backup_show_' . $uid, vitalstack_backup_new( $uid ), 10 * MINUTE_IN_SECONDS );
		vitalstack_2fa_notice( $uid, __( 'Authenticator app turned on', 'vitalstack' ) );
		vitalstack_account_redirect( '2fa_on', $back );
	}
	// Turning it off or new backup codes needs a code from the app (or a backup code).
	if ( in_array( $step, array( 'off', 'codes' ), true ) ) {
		if ( ! vitalstack_2fa_verify( $uid, isset( $_POST['code'] ) ? sanitize_text_field( wp_unslash( $_POST['code'] ) ) : '' ) ) {
			vitalstack_account_redirect( vitalstack_2fa_locked( $uid ) ? '2fa_locked' : '2fa_wrong', $back );
		}
		if ( 'off' === $step ) {
			delete_user_meta( $uid, 'vs_totp_secret' );
			delete_user_meta( $uid, 'vs_totp_on' );
			delete_user_meta( $uid, 'vs_backup_codes' );
			vitalstack_2fa_notice( $uid, __( 'Authenticator app turned off', 'vitalstack' ) );
			vitalstack_account_redirect( '2fa_off', $back );
		}
		set_transient( 'vs_backup_show_' . $uid, vitalstack_backup_new( $uid ), 10 * MINUTE_IN_SECONDS );
		vitalstack_account_redirect( '2fa_codes', $back );
	}
	vitalstack_account_redirect( 'failed', $back );
}
add_action( 'admin_post_vs_2fa', 'vitalstack_handle_2fa_setup' );

function vitalstack_2fa_notice( $user_id, $what ) {
	$user = get_userdata( $user_id );
	if ( $user ) {
		vitalstack_mail( $user->user_email, $what, '<p>' . esc_html( $what ) . '. ' . esc_html__( 'If this was not you, reset your password now and contact us.', 'vitalstack' ) . '</p>' );
	}
	if ( function_exists( 'vitalstack_audit' ) ) {
		vitalstack_audit( '2fa_change', $user_id, array( 'what' => $what ) );
	}
}

add_filter(
	'vitalstack_account_messages',
	function ( $m ) {
		return array_merge(
			$m,
			array(
				'2fa_scan'    => array( 'info', __( 'Add VitalStack to your authenticator app, then type the 6-digit code it shows.', 'vitalstack' ) ),
				'2fa_wrong'   => array( 'error', __( 'That code isn’t right. Check the time on your phone is automatic and try the newest code.', 'vitalstack' ) ),
				'2fa_expired' => array( 'error', __( 'Setup took too long. Start again.', 'vitalstack' ) ),
				'2fa_on'      => array( 'success', __( 'Authenticator app is on. Save your backup codes now: they are shown only once.', 'vitalstack' ) ),
				'2fa_off'     => array( 'success', __( 'Authenticator app turned off. Sign-in uses email codes again.', 'vitalstack' ) ),
				'2fa_codes'   => array( 'success', __( 'New backup codes made. The old ones no longer work.', 'vitalstack' ) ),
				'2fa_locked'  => array( 'error', __( 'Too many wrong codes. Code entry is paused for 15 minutes.', 'vitalstack' ) ),
				'stepup_ok'   => array( 'success', __( 'Security check passed.', 'vitalstack' ) ),
			)
		);
	}
);

/**
 * "Two-step security" card on /account/.
 */
function vitalstack_2fa_panel( $user_id ) {
	$on      = vitalstack_totp_on( $user_id );
	$pending = (string) get_transient( 'vs_totp_new_' . $user_id );
	$show    = get_transient( 'vs_backup_show_' . $user_id );
	$user    = get_userdata( $user_id );
	$form    = function ( $step, $label, $with_code, $cls = 'btn-ghost' ) {
		?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="twofa-form">
			<input type="hidden" name="action" value="vs_2fa">
			<input type="hidden" name="step" value="<?php echo esc_attr( $step ); ?>">
			<?php wp_nonce_field( 'vs_2fa', '_vsnonce' ); ?>
			<?php if ( $with_code ) : ?>
				<input class="otp-input twofa-code" type="text" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="9" placeholder="123456" required aria-label="<?php esc_attr_e( 'Code from your authenticator app', 'vitalstack' ); ?>">
			<?php endif; ?>
			<button type="submit" class="btn <?php echo esc_attr( $cls ); ?> btn-sm"><?php echo esc_html( $label ); ?></button>
		</form>
		<?php
	};
	?>
	<section class="panel twofa-panel" id="security">
		<h2>🛡️ <?php esc_html_e( 'Two-step security', 'vitalstack' ); ?></h2>
		<?php if ( $show ) : ?>
			<?php delete_transient( 'vs_backup_show_' . $user_id ); ?>
			<div class="twofa-codes">
				<p><b><?php esc_html_e( 'Your backup codes (shown once):', 'vitalstack' ); ?></b> <?php esc_html_e( 'each works one time if you lose your phone. Save them somewhere safe.', 'vitalstack' ); ?></p>
				<ol><?php foreach ( (array) $show as $c ) : ?><li><code><?php echo esc_html( $c ); ?></code></li><?php endforeach; ?></ol>
				<button type="button" class="btn btn-ghost btn-sm" data-copy-codes="<?php echo esc_attr( implode( "\n", (array) $show ) ); ?>"><?php esc_html_e( 'Copy all', 'vitalstack' ); ?></button>
			</div>
		<?php endif; ?>
		<?php if ( $on ) : ?>
			<p class="twofa-status is-on">✅ <?php esc_html_e( 'Authenticator app is on.', 'vitalstack' ); ?> <span class="muted small"><?php echo esc_html( sprintf( /* translators: %d: codes */ _n( '%d backup code left', '%d backup codes left', vitalstack_backup_left( $user_id ), 'vitalstack' ), vitalstack_backup_left( $user_id ) ) ); ?></span></p>
			<details class="twofa-more"><summary><?php esc_html_e( 'Manage', 'vitalstack' ); ?></summary>
				<p class="muted small"><?php esc_html_e( 'Enter a code from your app (or a backup code) to confirm.', 'vitalstack' ); ?></p>
				<?php $form( 'codes', __( 'Make new backup codes', 'vitalstack' ), true ); ?>
				<?php $form( 'off', __( 'Turn off the app', 'vitalstack' ), true ); ?>
			</details>
		<?php elseif ( $pending ) : ?>
			<?php
			$key  = vitalstack_pay_open_seal( $pending );
			$uri  = 'otpauth://totp/' . rawurlencode( 'VitalStack:' . $user->user_email ) . '?secret=' . $key . '&issuer=VitalStack&digits=6&period=30';
			?>
			<ol class="twofa-steps">
				<li><?php esc_html_e( 'Open Google Authenticator or Microsoft Authenticator and add an account.', 'vitalstack' ); ?>
					<a class="btn btn-primary btn-sm twofa-open" href="<?php echo esc_attr( $uri ); ?>"><?php esc_html_e( 'Open in my authenticator app', 'vitalstack' ); ?></a></li>
				<li><?php esc_html_e( 'Or choose “Enter a setup key” and type this key:', 'vitalstack' ); ?>
					<code class="twofa-key"><?php echo esc_html( trim( chunk_split( $key, 4, ' ' ) ) ); ?></code>
					<button type="button" class="btn-link" data-copy-codes="<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Copy key', 'vitalstack' ); ?></button></li>
				<li><?php esc_html_e( 'Type the 6-digit code your app shows:', 'vitalstack' ); ?><?php $form( 'confirm', __( 'Turn on', 'vitalstack' ), true, 'btn-primary' ); ?></li>
			</ol>
		<?php else : ?>
			<p><?php esc_html_e( 'You get a code by email when you sign in. For the strongest protection (and faster sign-in), use an authenticator app instead.', 'vitalstack' ); ?></p>
			<?php $form( 'start', __( 'Set up authenticator app', 'vitalstack' ), false, 'btn-primary' ); ?>
		<?php endif; ?>
	</section>
	<?php
}

/* ── Step-up for buying: REST, so the pricing page can do it in place ── */

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'vitalstack/v1',
			'/security/checklist',
			array(
				'methods'             => 'POST',
				'callback'            => 'vitalstack_rest_checklist',
				'permission_callback' => 'is_user_logged_in',
			)
		);
		register_rest_route(
			'vitalstack/v1',
			'/security/stepup',
			array(
				'methods'             => 'POST',
				'callback'            => 'vitalstack_rest_stepup',
				'permission_callback' => 'is_user_logged_in',
			)
		);
	}
);

/**
 * One-tap fixes for the buy checklist: action=subscribe or action=confirm (resend the link).
 */
function vitalstack_rest_checklist( WP_REST_Request $req ) {
	$uid = get_current_user_id();
	$act = sanitize_key( (string) $req->get_param( 'action' ) );
	if ( vitalstack_rate_limited( 'checklist|' . $uid, 20, HOUR_IN_SECONDS ) ) {
		return new WP_Error( 'rate', __( 'Too many attempts. Please wait a few minutes and try again.', 'vitalstack' ), array( 'status' => 429 ) );
	}
	if ( 'subscribe' === $act ) {
		vitalstack_set_subscribed( $uid, true );
	} elseif ( 'confirm' === $act && ! vitalstack_is_verified( $uid ) ) {
		if ( vitalstack_rate_limited( 'confirm_mail|' . $uid, 3, HOUR_IN_SECONDS ) ) {
			return new WP_Error( 'rate', __( 'We already sent it. Check your inbox and spam folder.', 'vitalstack' ), array( 'status' => 429 ) );
		}
		vitalstack_send_verification( $uid );
	}
	return array( 'steps' => function_exists( 'vitalstack_pay_checklist' ) ? vitalstack_pay_checklist( $uid ) : array() );
}

/**
 * action=send: email a code (readers without the app).
 * action=check: check an app, backup or email code.
 */
function vitalstack_rest_stepup( WP_REST_Request $req ) {
	$uid    = get_current_user_id();
	$action = sanitize_key( (string) $req->get_param( 'action' ) );
	if ( vitalstack_2fa_locked( $uid ) ) {
		return new WP_Error( 'locked', __( 'Too many wrong codes. Code entry is paused for 15 minutes.', 'vitalstack' ), array( 'status' => 429 ) );
	}
	if ( 'send' === $action ) {
		$user = wp_get_current_user();
		if ( ! vitalstack_otp_can_send( strtolower( $user->user_email ) ) ) {
			return new WP_Error( 'wait', __( 'Please wait a minute before asking for another code.', 'vitalstack' ), array( 'status' => 429 ) );
		}
		$code = vitalstack_otp_new_code();
		set_transient( 'vs_stepup_code_' . $uid, array( 'hash' => vitalstack_otp_hash( 'stepup' . $uid, $code ), 'tries' => 0 ), 10 * MINUTE_IN_SECONDS );
		if ( ! vitalstack_otp_mail( $user->user_email, $code, 'login' ) ) {
			return new WP_Error( 'mail', __( 'We couldn’t send the email right now. Please try again in a few minutes.', 'vitalstack' ), array( 'status' => 502 ) );
		}
		return array( 'sent' => true, 'to' => vitalstack_otp_mask( $user->user_email ) );
	}
	$code = trim( (string) $req->get_param( 'code' ) );
	$ok   = false;
	$mail = get_transient( 'vs_stepup_code_' . $uid );
	if ( is_array( $mail ) && 6 === strlen( preg_replace( '/\D/', '', $code ) ) && hash_equals( $mail['hash'], vitalstack_otp_hash( 'stepup' . $uid, preg_replace( '/\D/', '', $code ) ) ) ) {
		delete_transient( 'vs_stepup_code_' . $uid );
		vitalstack_2fa_passed( $uid );
		$ok = true;
	} elseif ( vitalstack_totp_on( $uid ) ) {
		$ok = vitalstack_2fa_verify( $uid, $code );
	} else {
		vitalstack_2fa_failed( $uid );
		if ( is_array( $mail ) && ++$mail['tries'] >= 5 ) {
			delete_transient( 'vs_stepup_code_' . $uid );
		} elseif ( is_array( $mail ) ) {
			set_transient( 'vs_stepup_code_' . $uid, $mail, 10 * MINUTE_IN_SECONDS );
		}
	}
	if ( ! $ok ) {
		return new WP_Error( 'wrong', __( 'That code isn’t right. Try the newest code.', 'vitalstack' ), array( 'status' => 400 ) );
	}
	return array( 'ok' => true );
}

/* ── Admins: app code on wp-login.php, alert on a new device ──────────── */

add_action(
	'login_form',
	function () {
		echo '<p><label for="vs_totp">' . esc_html__( 'Authenticator code (if you turned it on)', 'vitalstack' ) . '</label><input type="text" name="vs_totp" id="vs_totp" class="input" inputmode="numeric" autocomplete="one-time-code" maxlength="9"></p>';
	}
);

/**
 * Staff who turned the app on must type its code on wp-login.php too.
 */
function vitalstack_2fa_wp_login( $user ) {
	if ( ! $user instanceof WP_User || ! empty( $GLOBALS['vitalstack_code_login'] ) || ! user_can( $user, 'edit_posts' ) || ! vitalstack_totp_on( $user->ID ) ) {
		return $user;
	}
	// Only the wp-login.php form posts this field.
	if ( ! isset( $_POST['log'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- core login form has no nonce
		return $user;
	}
	$code = isset( $_POST['vs_totp'] ) ? sanitize_text_field( wp_unslash( $_POST['vs_totp'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	if ( vitalstack_2fa_verify( $user->ID, $code ) ) {
		return $user;
	}
	return new WP_Error( 'vs_2fa', __( 'Enter the 6-digit code from your authenticator app (or a backup code).', 'vitalstack' ) );
}
add_filter( 'authenticate', 'vitalstack_2fa_wp_login', 120 );
