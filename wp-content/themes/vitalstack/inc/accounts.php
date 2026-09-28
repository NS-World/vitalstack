<?php
/**
 * Reader accounts: sign up, sign in, email verification and the /account/ page.
 *
 * Readers get the "subscriber" role. They never see wp-admin; everything
 * happens on /account/. Form posts go through admin-post.php with nonces.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ── Settings ──────────────────────────────────────────────────────────────── */

function vitalstack_accounts_enabled() {
	return (bool) get_theme_mod( 'vitalstack_accounts_enabled', true );
}

/* ── Route: /account/ ──────────────────────────────────────────────────────── */

function vitalstack_account_rewrite() {
	add_rewrite_rule( '^account/?$', 'index.php?vs_account=1', 'top' );
}
add_action( 'init', 'vitalstack_account_rewrite' );

function vitalstack_account_query_var( $vars ) {
	$vars[] = 'vs_account';
	return $vars;
}
add_filter( 'query_vars', 'vitalstack_account_query_var' );

/**
 * Flush rewrite rules once per theme version, so uploading a new version
 * (which does not trigger after_switch_theme) still registers /account/.
 */
function vitalstack_maybe_flush_rewrites() {
	if ( get_option( 'vitalstack_rewrite_version' ) !== VITALSTACK_VERSION ) {
		flush_rewrite_rules( false );
		update_option( 'vitalstack_rewrite_version', VITALSTACK_VERSION );
	}
}
add_action( 'init', 'vitalstack_maybe_flush_rewrites', 99 );

function vitalstack_is_account_page() {
	return (bool) get_query_var( 'vs_account' );
}

function vitalstack_account_url( $args = array() ) {
	return add_query_arg( $args, home_url( '/account/' ) );
}

// The account route has no posts; don't let WordPress turn it into a 404.
add_filter(
	'pre_handle_404',
	function ( $handled ) {
		return vitalstack_is_account_page() ? true : $handled;
	}
);

function vitalstack_account_template( $template ) {
	if ( vitalstack_is_account_page() ) {
		status_header( 200 );
		return VITALSTACK_DIR . '/templates/account.php';
	}
	return $template;
}
add_filter( 'template_include', 'vitalstack_account_template' );

/**
 * Account pages must never be served from a page cache (LiteSpeed etc.).
 */
function vitalstack_account_nocache() {
	if ( vitalstack_is_account_page() ) {
		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}
		nocache_headers();
	}
}
add_action( 'template_redirect', 'vitalstack_account_nocache', 1 );

add_filter(
	'document_title_parts',
	function ( $parts ) {
		if ( vitalstack_is_account_page() ) {
			$parts['title'] = is_user_logged_in() ? __( 'Your account', 'vitalstack' ) : __( 'Sign in', 'vitalstack' );
		}
		return $parts;
	},
	20
);
add_filter(
	'wp_robots',
	function ( $robots ) {
		if ( vitalstack_is_account_page() ) {
			$robots['noindex'] = true;
		}
		return $robots;
	}
);
add_filter(
	'wpseo_robots',
	function ( $robots ) {
		return vitalstack_is_account_page() ? 'noindex, follow' : $robots;
	}
);

/* ── Keep readers out of wp-admin ─────────────────────────────────────────── */

function vitalstack_block_admin_for_readers() {
	if ( wp_doing_ajax() || current_user_can( 'edit_posts' ) ) {
		return;
	}
	$script = isset( $_SERVER['SCRIPT_NAME'] ) ? basename( sanitize_text_field( wp_unslash( $_SERVER['SCRIPT_NAME'] ) ) ) : '';
	if ( 'admin-post.php' === $script ) {
		return;
	}
	wp_safe_redirect( vitalstack_account_url() );
	exit;
}
add_action( 'admin_init', 'vitalstack_block_admin_for_readers' );

add_filter(
	'show_admin_bar',
	function ( $show ) {
		return current_user_can( 'edit_posts' ) ? $show : false;
	}
);

// Send readers who use wp-login.php to their account page afterwards.
add_filter(
	'login_redirect',
	function ( $redirect_to, $requested, $user ) {
		if ( $user instanceof WP_User && ! $user->has_cap( 'edit_posts' ) ) {
			return vitalstack_account_url();
		}
		return $redirect_to;
	},
	10,
	3
);

// Point "Register" links at our own form.
add_filter(
	'register_url',
	function ( $url ) {
		return vitalstack_accounts_enabled() ? vitalstack_account_url( array( 'tab' => 'register' ) ) : $url;
	}
);

/* ── Helpers ───────────────────────────────────────────────────────────────── */

/**
 * Visitor IP for rate limiting. Behind a CDN/proxy (Hostinger CDN,
 * Cloudflare) REMOTE_ADDR is the proxy, so prefer the forwarded client IP;
 * otherwise every visitor would share one rate-limit bucket.
 */
function vitalstack_client_ip() {
	foreach ( array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' ) as $key ) {
		if ( empty( $_SERVER[ $key ] ) ) {
			continue;
		}
		$ip = trim( explode( ',', sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) ) )[0] );
		if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			return $ip;
		}
	}
	return 'unknown';
}

/**
 * Simple fixed-window rate limiter.
 *
 * @return bool True when the limit has been reached.
 */
function vitalstack_rate_limited( $bucket, $limit, $window, $increment = true ) {
	$key   = 'vs_rl_' . md5( $bucket );
	$count = (int) get_transient( $key );
	if ( $count >= $limit ) {
		return true;
	}
	if ( $increment ) {
		set_transient( $key, $count + 1, $window );
	}
	return false;
}

/**
 * Redirect back to the account page (or a safe custom URL) with a message code.
 */
function vitalstack_account_redirect( $msg, $args = array(), $to = '' ) {
	$to = $to ? wp_validate_redirect( $to, vitalstack_account_url() ) : vitalstack_account_url();
	$args['vs_msg'] = $msg;
	wp_safe_redirect( add_query_arg( $args, $to ) );
	exit;
}

function vitalstack_requested_redirect() {
	// phpcs:ignore WordPress.Security.NonceVerification -- only used to build a validated redirect
	$raw = isset( $_REQUEST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) : '';
	return $raw ? wp_validate_redirect( $raw, '' ) : '';
}

function vitalstack_account_messages() {
	return array(
		'login_failed'   => array( 'error', __( 'That email/username and password don’t match. Try again, or reset your password.', 'vitalstack' ) ),
		'rate'           => array( 'error', __( 'Too many attempts. Please wait a few minutes and try again.', 'vitalstack' ) ),
		'bad_nonce'      => array( 'error', __( 'Your session expired. Please try again.', 'vitalstack' ) ),
		'missing'        => array( 'error', __( 'Please fill in all fields.', 'vitalstack' ) ),
		'bad_email'      => array( 'error', __( 'Please enter a valid email address.', 'vitalstack' ) ),
		'email_exists'   => array( 'error', __( 'An account with this email already exists. Sign in instead.', 'vitalstack' ) ),
		'weak_password'  => array( 'error', __( 'Use a password with at least 8 characters.', 'vitalstack' ) ),
		'closed'         => array( 'error', __( 'New sign-ups are closed right now.', 'vitalstack' ) ),
		'failed'         => array( 'error', __( 'Something went wrong. Please try again.', 'vitalstack' ) ),
		'registered'     => array( 'success', __( 'Welcome to VitalStack! We sent you an email: click the link in it to confirm your address.', 'vitalstack' ) ),
		'welcome_back'   => array( 'success', __( 'You are signed in.', 'vitalstack' ) ),
		'verified'       => array( 'success', __( 'Email confirmed. You will now get new lessons by email.', 'vitalstack' ) ),
		'verify_invalid' => array( 'error', __( 'That confirmation link is invalid or has expired. Send a new one below.', 'vitalstack' ) ),
		'verify_sent'    => array( 'success', __( 'Confirmation email sent. Check your inbox (and the spam folder).', 'vitalstack' ) ),
		'subscribed'     => array( 'success', __( 'Subscribed! We’ll email you when new lessons and guides are published.', 'vitalstack' ) ),
		'subscribed_unv' => array( 'info', __( 'Subscribed! Confirm your email address first: we only send to confirmed addresses.', 'vitalstack' ) ),
		'unsubscribed'   => array( 'success', __( 'You are unsubscribed and won’t get any more emails from us.', 'vitalstack' ) ),
		'saved'          => array( 'success', __( 'Saved.', 'vitalstack' ) ),
		'contact_sent'   => array( 'success', __( 'Thanks! Your message has been sent. We usually reply within 2 working days.', 'vitalstack' ) ),
		'contact_failed' => array( 'error', __( 'Sorry, your message could not be sent. Please email us directly instead.', 'vitalstack' ) ),
	);
}

/* ── Subscription state ───────────────────────────────────────────────────── */

function vitalstack_is_subscribed( $user_id ) {
	return (bool) get_user_meta( $user_id, 'vs_subscribed', true );
}

function vitalstack_is_verified( $user_id ) {
	return (bool) get_user_meta( $user_id, 'vs_email_verified', true );
}

function vitalstack_set_subscribed( $user_id, $on ) {
	update_user_meta( $user_id, 'vs_subscribed', $on ? 1 : 0 );
	if ( $on ) {
		update_user_meta( $user_id, 'vs_subscribed_at', time() );
	}
}

/**
 * HMAC signature for one-click unsubscribe links (no login needed).
 */
function vitalstack_unsubscribe_sig( $user_id ) {
	return hash_hmac( 'sha256', 'unsubscribe|' . (int) $user_id, wp_salt( 'auth' ) );
}

function vitalstack_unsubscribe_url( $user_id ) {
	return vitalstack_account_url(
		array(
			'vs_unsub' => (int) $user_id,
			'sig'      => vitalstack_unsubscribe_sig( $user_id ),
		)
	);
}

/* ── Email verification ───────────────────────────────────────────────────── */

function vitalstack_send_verification( $user_id ) {
	$user = get_userdata( $user_id );
	if ( ! $user ) {
		return false;
	}
	$token = wp_generate_password( 32, false );
	update_user_meta( $user_id, 'vs_verify_hash', hash( 'sha256', $token ) );
	update_user_meta( $user_id, 'vs_verify_time', time() );

	$link = vitalstack_account_url(
		array(
			'vs_verify' => $token,
			'uid'       => $user_id,
		)
	);
	$body = '<p>' . sprintf( /* translators: %s: name */ esc_html__( 'Hi %s,', 'vitalstack' ), esc_html( $user->display_name ) ) . '</p>'
		. '<p>' . esc_html__( 'Thanks for creating a VitalStack account. Please confirm your email address so we can send you new lessons and guides.', 'vitalstack' ) . '</p>'
		. vitalstack_email_button( $link, __( 'Confirm my email', 'vitalstack' ) )
		. '<p style="color:#6b7280;font-size:13px">' . esc_html__( 'If you did not create this account, you can ignore this email.', 'vitalstack' ) . '</p>';

	return vitalstack_mail( $user->user_email, __( 'Confirm your email for VitalStack', 'vitalstack' ), $body );
}

/* ── Request handlers ─────────────────────────────────────────────────────── */

function vitalstack_handle_login() {
	if ( ! isset( $_POST['_vsnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_login' ) ) {
		vitalstack_account_redirect( 'bad_nonce', array( 'tab' => 'login' ) );
	}
	$redirect = vitalstack_requested_redirect();
	$login    = isset( $_POST['log'] ) ? sanitize_text_field( wp_unslash( $_POST['log'] ) ) : '';
	$bucket   = 'login|' . vitalstack_client_ip() . '|' . strtolower( $login );
	if ( vitalstack_rate_limited( $bucket, 8, 15 * MINUTE_IN_SECONDS, false ) ) {
		vitalstack_account_redirect( 'rate', array( 'tab' => 'login' ) );
	}

	$password = isset( $_POST['pwd'] ) ? (string) wp_unslash( $_POST['pwd'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- passwords are not sanitized
	if ( '' === $login || '' === $password ) {
		vitalstack_account_redirect( 'missing', array( 'tab' => 'login' ) );
	}
	if ( is_email( $login ) ) {
		$by_email = get_user_by( 'email', $login );
		if ( $by_email ) {
			$login = $by_email->user_login;
		}
	}

	$user = wp_signon(
		array(
			'user_login'    => $login,
			'user_password' => $password,
			'remember'      => ! empty( $_POST['rememberme'] ),
		),
		is_ssl()
	);
	if ( is_wp_error( $user ) ) {
		vitalstack_rate_limited( $bucket, 8, 15 * MINUTE_IN_SECONDS );
		vitalstack_account_redirect(
			'login_failed',
			array(
				'tab'         => 'login',
				'redirect_to' => $redirect,
			)
		);
	}

	if ( ! empty( $_POST['subscribe'] ) ) {
		vitalstack_set_subscribed( $user->ID, true );
		vitalstack_account_redirect( vitalstack_is_verified( $user->ID ) ? 'subscribed' : 'subscribed_unv', array(), $redirect );
	}
	vitalstack_account_redirect( 'welcome_back', array(), $redirect );
}
add_action( 'admin_post_nopriv_vs_login', 'vitalstack_handle_login' );
add_action( 'admin_post_vs_login', 'vitalstack_handle_login' );

function vitalstack_handle_register() {
	if ( ! isset( $_POST['_vsnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_register' ) ) {
		vitalstack_account_redirect( 'bad_nonce', array( 'tab' => 'register' ) );
	}
	if ( ! vitalstack_accounts_enabled() ) {
		vitalstack_account_redirect( 'closed', array( 'tab' => 'register' ) );
	}
	$redirect = vitalstack_requested_redirect();
	$back     = array(
		'tab'         => 'register',
		'redirect_to' => $redirect,
	);

	// Honeypot: real people never fill this hidden field.
	if ( ! empty( $_POST['website'] ) ) {
		vitalstack_account_redirect( 'failed', $back );
	}
	if ( vitalstack_rate_limited( 'register|' . vitalstack_client_ip(), 10, HOUR_IN_SECONDS ) ) {
		vitalstack_account_redirect( 'rate', $back );
	}

	$name     = isset( $_POST['display_name'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST['display_name'] ) ), 0, 60 ) : '';
	$email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$password = isset( $_POST['pwd'] ) ? (string) wp_unslash( $_POST['pwd'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

	if ( '' === $name || '' === $email || '' === $password ) {
		vitalstack_account_redirect( 'missing', $back );
	}
	if ( ! is_email( $email ) ) {
		vitalstack_account_redirect( 'bad_email', $back );
	}
	if ( email_exists( $email ) ) {
		vitalstack_account_redirect(
			'email_exists',
			array(
				'tab'         => 'login',
				'redirect_to' => $redirect,
			)
		);
	}
	if ( mb_strlen( $password ) < 8 ) {
		vitalstack_account_redirect( 'weak_password', $back );
	}

	// Username from the email's local part, made unique.
	$base = sanitize_user( strtolower( strstr( $email, '@', true ) ), true );
	$base = $base ? mb_substr( $base, 0, 40 ) : 'reader';
	$user_login = $base;
	$n          = 1;
	while ( username_exists( $user_login ) ) {
		$user_login = $base . ++$n;
	}

	$user_id = wp_insert_user(
		array(
			'user_login'   => $user_login,
			'user_email'   => $email,
			'user_pass'    => $password,
			'display_name' => $name,
			'first_name'   => $name,
			'nickname'     => $name,
			'role'         => 'subscriber',
		)
	);
	if ( is_wp_error( $user_id ) ) {
		vitalstack_account_redirect( 'failed', $back );
	}

	update_user_meta( $user_id, 'vs_email_verified', 0 );
	vitalstack_set_subscribed( $user_id, ! empty( $_POST['subscribe'] ) );
	vitalstack_send_verification( $user_id );

	wp_set_current_user( $user_id );
	wp_set_auth_cookie( $user_id, true, is_ssl() );

	vitalstack_account_redirect( 'registered', array(), $redirect );
}
add_action( 'admin_post_nopriv_vs_register', 'vitalstack_handle_register' );
add_action( 'admin_post_vs_register', 'vitalstack_handle_register' );

function vitalstack_handle_subscribe() {
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( vitalstack_account_url( array( 'tab' => 'register' ) ) );
		exit;
	}
	if ( ! isset( $_POST['_vsnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_subscribe' ) ) {
		vitalstack_account_redirect( 'bad_nonce' );
	}
	$uid      = get_current_user_id();
	$on       = ! empty( $_POST['subscribe'] );
	$redirect = vitalstack_requested_redirect();
	vitalstack_set_subscribed( $uid, $on );
	if ( ! $on ) {
		vitalstack_account_redirect( 'unsubscribed', array(), $redirect );
	}
	vitalstack_account_redirect( vitalstack_is_verified( $uid ) ? 'subscribed' : 'subscribed_unv', array(), $redirect );
}
add_action( 'admin_post_vs_subscribe', 'vitalstack_handle_subscribe' );
add_action( 'admin_post_nopriv_vs_subscribe', 'vitalstack_handle_subscribe' );

function vitalstack_handle_resend_verification() {
	if ( ! is_user_logged_in() || ! isset( $_POST['_vsnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_resend' ) ) {
		vitalstack_account_redirect( 'bad_nonce' );
	}
	$uid = get_current_user_id();
	if ( vitalstack_rate_limited( 'resend|' . $uid, 3, HOUR_IN_SECONDS ) ) {
		vitalstack_account_redirect( 'rate' );
	}
	vitalstack_send_verification( $uid );
	vitalstack_account_redirect( 'verify_sent' );
}
add_action( 'admin_post_vs_resend', 'vitalstack_handle_resend_verification' );

function vitalstack_handle_profile() {
	if ( ! is_user_logged_in() || ! isset( $_POST['_vsnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_profile' ) ) {
		vitalstack_account_redirect( 'bad_nonce' );
	}
	$name = isset( $_POST['display_name'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST['display_name'] ) ), 0, 60 ) : '';
	if ( '' === $name ) {
		vitalstack_account_redirect( 'missing' );
	}
	wp_update_user(
		array(
			'ID'           => get_current_user_id(),
			'display_name' => $name,
			'first_name'   => $name,
		)
	);
	vitalstack_account_redirect( 'saved' );
}
add_action( 'admin_post_vs_profile', 'vitalstack_handle_profile' );

/**
 * Link-based actions on /account/: email verification and one-click
 * unsubscribe (GET from the email, or POST from mail clients that support
 * RFC 8058 one-click unsubscribe).
 */
function vitalstack_handle_account_links() {
	if ( ! vitalstack_is_account_page() ) {
		return;
	}
	// phpcs:disable WordPress.Security.NonceVerification -- links are authenticated by token / HMAC
	if ( isset( $_GET['vs_verify'], $_GET['uid'] ) ) {
		$uid   = absint( $_GET['uid'] );
		$token = sanitize_text_field( wp_unslash( $_GET['vs_verify'] ) );
		$hash  = (string) get_user_meta( $uid, 'vs_verify_hash', true );
		$time  = (int) get_user_meta( $uid, 'vs_verify_time', true );
		if ( $hash && hash_equals( $hash, hash( 'sha256', $token ) ) && ( time() - $time ) < 7 * DAY_IN_SECONDS ) {
			update_user_meta( $uid, 'vs_email_verified', 1 );
			delete_user_meta( $uid, 'vs_verify_hash' );
			delete_user_meta( $uid, 'vs_verify_time' );
			vitalstack_account_redirect( 'verified' );
		}
		vitalstack_account_redirect( 'verify_invalid' );
	}

	if ( isset( $_REQUEST['vs_unsub'], $_REQUEST['sig'] ) ) {
		$uid = absint( $_REQUEST['vs_unsub'] );
		$sig = sanitize_text_field( wp_unslash( $_REQUEST['sig'] ) );
		if ( $uid && hash_equals( vitalstack_unsubscribe_sig( $uid ), $sig ) ) {
			vitalstack_set_subscribed( $uid, false );
			if ( 'POST' === ( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				status_header( 200 );
				exit;
			}
			vitalstack_account_redirect( 'unsubscribed' );
		}
		vitalstack_account_redirect( 'failed' );
	}
	// phpcs:enable
}
add_action( 'template_redirect', 'vitalstack_handle_account_links', 5 );

/* ── Admin: subscriber column + count ─────────────────────────────────────── */

add_filter(
	'manage_users_columns',
	function ( $cols ) {
		$cols['vs_sub'] = __( 'Email updates', 'vitalstack' );
		return $cols;
	}
);
add_filter(
	'manage_users_custom_column',
	function ( $out, $col, $user_id ) {
		if ( 'vs_sub' !== $col ) {
			return $out;
		}
		if ( ! vitalstack_is_subscribed( $user_id ) ) {
			return '—';
		}
		return vitalstack_is_verified( $user_id ) ? __( 'Subscribed ✓', 'vitalstack' ) : __( 'Subscribed (email not confirmed)', 'vitalstack' );
	},
	10,
	3
);

function vitalstack_subscriber_count() {
	$q = new WP_User_Query(
		array(
			'count_total' => true,
			'number'      => 1,
			'fields'      => 'ID',
			'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'key'   => 'vs_subscribed',
					'value' => '1',
				),
				array(
					'key'   => 'vs_email_verified',
					'value' => '1',
				),
			),
		)
	);
	return (int) $q->get_total();
}

add_action(
	'wp_dashboard_setup',
	function () {
		wp_add_dashboard_widget(
			'vitalstack_subscribers',
			__( 'VitalStack subscribers', 'vitalstack' ),
			function () {
				$users = count_users();
				$readers = isset( $users['avail_roles']['subscriber'] ) ? (int) $users['avail_roles']['subscriber'] : 0;
				echo '<p style="font-size:28px;font-weight:700;margin:0">' . (int) vitalstack_subscriber_count() . '</p>';
				echo '<p>' . esc_html__( 'confirmed email subscribers', 'vitalstack' ) . '</p>';
				/* translators: %d: number of reader accounts */
				echo '<p>' . esc_html( sprintf( __( '%d reader accounts in total.', 'vitalstack' ), $readers ) ) . '</p>';
			}
		);
	}
);
