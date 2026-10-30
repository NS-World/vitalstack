<?php
/**
 * Sign in or sign up with a one-time code sent by email (no password).
 *
 * It sits next to the password forms, which keep working as before:
 *   1. The reader enters their email (and, for a new account, their name).
 *   2. We email a 6-digit code. Nothing on the page says whether the email
 *      already has an account, so the form can't be used to look up users.
 *   3. The right code signs them in, or creates the account. Entering the
 *      code proves they own the address, so it also counts as confirming it.
 *
 * Security:
 *   - The code is never stored: only an HMAC of it, keyed with the site's
 *     secret and a random per-attempt id that lives in an HttpOnly cookie.
 *   - A code works once, for 10 minutes, with at most 5 tries.
 *   - Sending is limited per email (5 an hour, 60 s apart) and per connection.
 *   - Administrators keep using their password (and any two-factor plugin).
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

const VITALSTACK_OTP_TTL       = 600; // seconds a code stays valid
const VITALSTACK_OTP_TRIES     = 5;   // wrong guesses allowed per code
const VITALSTACK_OTP_PER_EMAIL = 5;   // codes per email per hour
const VITALSTACK_OTP_GAP       = 60;  // seconds between codes to one email
const VITALSTACK_OTP_COOKIE    = 'vs_otp';

function vitalstack_otp_enabled() {
	return (bool) apply_filters( 'vitalstack_otp_enabled', true );
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

/* ── Step 1: send a code ──────────────────────────────────────────────── */

function vitalstack_handle_otp_send() {
	if ( ! vitalstack_otp_enabled() ) {
		vitalstack_account_redirect( 'closed' );
	}
	if ( ! isset( $_POST['_vsnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_otp_send' ) ) {
		vitalstack_otp_back( 'bad_nonce' );
	}
	$redirect = vitalstack_requested_redirect();
	$back     = array( 'redirect_to' => $redirect );
	if ( ! empty( $_POST['website'] ) ) { // honeypot
		vitalstack_otp_back( 'otp_sent', $back );
	}

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$name  = isset( $_POST['display_name'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST['display_name'] ) ), 0, 60 ) : '';
	if ( ! is_email( $email ) ) {
		vitalstack_otp_back( 'bad_email', $back );
	}
	$email = strtolower( $email );

	$key_mail = 'otp_mail|' . md5( $email );
	if ( vitalstack_rate_limited( 'otp_ip|' . vitalstack_client_ip(), 10, HOUR_IN_SECONDS, false )
		|| vitalstack_rate_limited( 'otp_edge|' . vitalstack_remote_ip(), 60, HOUR_IN_SECONDS, false )
		|| vitalstack_rate_limited( $key_mail, VITALSTACK_OTP_PER_EMAIL, HOUR_IN_SECONDS, false )
		|| get_transient( 'vs_otp_gap_' . md5( $email ) ) ) {
		vitalstack_otp_back( 'otp_wait', $back );
	}
	vitalstack_rate_limited( 'otp_ip|' . vitalstack_client_ip(), 10, HOUR_IN_SECONDS );
	vitalstack_rate_limited( 'otp_edge|' . vitalstack_remote_ip(), 60, HOUR_IN_SECONDS );
	vitalstack_rate_limited( $key_mail, VITALSTACK_OTP_PER_EMAIL, HOUR_IN_SECONDS );
	set_transient( 'vs_otp_gap_' . md5( $email ), 1, VITALSTACK_OTP_GAP );

	// A previous attempt from this browser is replaced, never extended.
	$old = vitalstack_otp_pending();
	if ( $old ) {
		delete_transient( 'vs_otp_' . hash( 'sha256', $old['sid'] ) );
	}

	$user = get_user_by( 'email', $email );
	// New accounts only when sign-ups are open; the reply looks the same either way.
	if ( ! $user && ! vitalstack_accounts_enabled() ) {
		vitalstack_otp_back( 'otp_sent', $back );
	}

	$sid  = strtolower( wp_generate_password( 32, false, false ) );
	$code = str_pad( (string) random_int( 0, 999999 ), 6, '0', STR_PAD_LEFT );
	set_transient(
		'vs_otp_' . hash( 'sha256', $sid ),
		array(
			'email'     => $email,
			'name'      => $name,
			'subscribe' => ! empty( $_POST['subscribe'] ),
			'hash'      => vitalstack_otp_hash( $sid, $code ),
			'tries'     => 0,
			'expires'   => time() + VITALSTACK_OTP_TTL,
			'mask'      => vitalstack_otp_mask( $email ),
		),
		VITALSTACK_OTP_TTL
	);
	vitalstack_otp_cookie( $sid, time() + VITALSTACK_OTP_TTL );

	$body = '<p>' . esc_html__( 'Your VitalStack sign-in code is:', 'vitalstack' ) . '</p>'
		. '<p style="font-size:34px;font-weight:800;letter-spacing:8px;margin:12px 0 18px;color:#0a7d4f">' . esc_html( $code ) . '</p>'
		. '<p>' . esc_html__( 'Enter it on the VitalStack page where you asked for it. It works once and expires in 10 minutes.', 'vitalstack' ) . '</p>'
		. '<p style="color:#6b7280;font-size:13px">' . esc_html__( 'Didn’t ask for a code? Ignore this email: nobody can sign in without it. Never share this code with anyone, VitalStack will never ask you for it.', 'vitalstack' ) . '</p>';
	/* translators: %s: the one-time code */
	vitalstack_mail( $email, sprintf( __( '%s is your VitalStack code', 'vitalstack' ), $code ), $body );

	vitalstack_otp_back( 'otp_sent', $back );
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
	if ( isset( $_POST['_vsnonce'] ) && wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_otp_reset' ) ) {
		$data = vitalstack_otp_pending();
		if ( $data ) {
			vitalstack_otp_forget( $data );
		}
	}
	vitalstack_otp_back( '', array( 'redirect_to' => vitalstack_requested_redirect() ) );
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
			)
		);
	}
);

/**
 * The "Email code" panel on /account/.
 *
 * @param string $redirect Where to go after signing in.
 * @param bool   $subscribe Tick "email me new lessons" by default.
 * @param bool   $active    Whether this tab is the open one.
 */
function vitalstack_otp_panel( $redirect, $subscribe, $active ) {
	$pending = vitalstack_otp_pending();
	?>
	<div id="panel-code" role="tabpanel" aria-labelledby="tab-code" class="auth-form otp-panel" <?php echo $active ? '' : 'hidden'; ?>>
		<?php if ( $pending ) : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="otp-verify">
				<input type="hidden" name="action" value="vs_otp_verify">
				<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect ); ?>">
				<?php wp_nonce_field( 'vs_otp_verify', '_vsnonce' ); ?>
				<p class="otp-sent-to">
					<?php
					/* translators: %s: masked email address */
					echo esc_html( sprintf( __( 'Enter the 6-digit code we sent to %s', 'vitalstack' ), $pending['mask'] ) );
					?>
				</p>
				<label class="field">
					<span><?php esc_html_e( 'Code', 'vitalstack' ); ?></span>
					<input class="otp-input" type="text" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code" required>
				</label>
				<button type="submit" class="btn btn-primary btn-block"><?php esc_html_e( 'Continue', 'vitalstack' ); ?></button>
			</form>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="otp-restart">
				<input type="hidden" name="action" value="vs_otp_reset">
				<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect ); ?>">
				<?php wp_nonce_field( 'vs_otp_reset', '_vsnonce' ); ?>
				<button type="submit" class="btn-link"><?php esc_html_e( 'Use a different email or send a new code', 'vitalstack' ); ?></button>
			</form>
		<?php else : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="vs_otp_send">
				<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect ); ?>">
				<?php wp_nonce_field( 'vs_otp_send', '_vsnonce' ); ?>
				<div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
				<p class="otp-intro"><?php esc_html_e( 'No password needed. We email you a 6-digit code: new here? The same code creates your free account.', 'vitalstack' ); ?></p>
				<label class="field">
					<span><?php esc_html_e( 'Email', 'vitalstack' ); ?></span>
					<input type="email" name="email" autocomplete="email" required>
				</label>
				<label class="field">
					<span><?php esc_html_e( 'Your name', 'vitalstack' ); ?> <small class="muted"><?php esc_html_e( '(only for a new account)', 'vitalstack' ); ?></small></span>
					<input type="text" name="display_name" autocomplete="name" maxlength="60">
				</label>
				<label class="check"><input type="checkbox" name="subscribe" value="1" <?php checked( $subscribe ); ?>> <?php esc_html_e( 'Email me when new lessons and guides are published', 'vitalstack' ); ?></label>
				<button type="submit" class="btn btn-primary btn-block"><?php esc_html_e( 'Email me a code', 'vitalstack' ); ?></button>
			</form>
		<?php endif; ?>
	</div>
	<?php
}
