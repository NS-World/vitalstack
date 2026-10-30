<?php
/**
 * Premium payments with Razorpay (UPI, cards, net banking, wallets in ₹;
 * international cards in $).
 *
 * How a purchase works:
 *   1. /pricing/ shows the plans. A signed-in reader picks one.
 *   2. The browser asks our REST API for an order. The price comes from the
 *      server's price list, never from the browser. We create the order at
 *      Razorpay and keep our own record (a private "vs_order" post).
 *   3. Razorpay's checkout takes the payment (we never see card details).
 *   4. The browser sends back Razorpay's signed reply. We check the
 *      HMAC-SHA256 signature with the key secret before giving access.
 *   5. Razorpay's webhook (also signed) confirms the payment too, so access
 *      is given even if the reader closes the tab. Both paths are safe to
 *      repeat: access is granted once per order.
 *
 * Keys: define them in wp-config.php (safest), or enter them in
 * Settings > VitalStack Payments, where secrets are stored encrypted.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/* ── Plans and prices ─────────────────────────────────────────────────── */

/**
 * Plans on sale. Prices are whole rupees / dollars and can be changed in
 * Settings > VitalStack Payments.
 */
function vitalstack_pay_plans() {
	$plans = array(
		'all-month' => array(
			'title'  => __( 'All-Access Monthly', 'vitalstack' ),
			'tracks' => array( 'data-scientist', 'gen-ai-developer' ),
			'days'   => 31,
			'inr'    => 499,
			'usd'    => 12,
			'period' => __( '1 month', 'vitalstack' ),
			'blurb'  => __( 'Try everything for a month.', 'vitalstack' ),
		),
		'data-scientist' => array(
			'title'  => __( 'Data Scientist Track', 'vitalstack' ),
			'tracks' => array( 'data-scientist' ),
			'days'   => 365,
			'inr'    => 2999,
			'usd'    => 59,
			'period' => __( '12 months', 'vitalstack' ),
			'blurb'  => __( 'Statistics, machine learning, SQL, Power BI and 6 projects.', 'vitalstack' ),
		),
		'gen-ai-developer' => array(
			'title'  => __( 'Gen AI Developer Track', 'vitalstack' ),
			'tracks' => array( 'gen-ai-developer' ),
			'days'   => 365,
			'inr'    => 2999,
			'usd'    => 59,
			'period' => __( '12 months', 'vitalstack' ),
			'blurb'  => __( 'LLMs, RAG, AI agents and deploying real AI apps.', 'vitalstack' ),
		),
		'all-year' => array(
			'title'  => __( 'All-Access Yearly', 'vitalstack' ),
			'tracks' => array( 'data-scientist', 'gen-ai-developer' ),
			'days'   => 365,
			'inr'    => 4999,
			'usd'    => 99,
			'period' => __( '12 months', 'vitalstack' ),
			'blurb'  => __( 'Both career tracks and everything we add this year.', 'vitalstack' ),
			'best'   => true,
		),
	);
	$saved = vitalstack_pay_settings()['prices'];
	foreach ( $plans as $id => $p ) {
		foreach ( array( 'inr', 'usd' ) as $cur ) {
			if ( ! empty( $saved[ $id ][ $cur ] ) ) {
				$plans[ $id ][ $cur ] = (int) $saved[ $id ][ $cur ];
			}
		}
	}
	return $plans;
}

function vitalstack_pay_price_label( $amount, $cur ) {
	return 'inr' === $cur ? '₹' . number_format_i18n( $amount ) : '$' . number_format_i18n( $amount );
}

/* ── Settings and keys ────────────────────────────────────────────────── */

function vitalstack_pay_settings() {
	$s = get_option( 'vs_pay', array() );
	return array_merge(
		array(
			'open'        => false,
			'mode'        => 'test',
			'test_id'     => '',
			'test_secret' => '',
			'live_id'     => '',
			'live_secret' => '',
			'webhook'     => '',
			'prices'      => array(),
			'refund_days' => 7,
		),
		is_array( $s ) ? $s : array()
	);
}

/**
 * Encrypt a secret for the database with a key derived from the site's
 * salts (in wp-config.php, not in the database), so a leaked database
 * alone does not reveal the Razorpay secret.
 */
function vitalstack_pay_seal( $plain ) {
	if ( '' === $plain ) {
		return '';
	}
	$key = hash( 'sha256', wp_salt( 'secure_auth' ) . '|vs_pay', true );
	if ( function_exists( 'sodium_crypto_secretbox' ) ) {
		$nonce = random_bytes( SODIUM_CRYPTO_SECRETBOX_NONCEBYTES );
		return 's1:' . base64_encode( $nonce . sodium_crypto_secretbox( $plain, $nonce, $key ) );
	}
	$iv  = random_bytes( 12 );
	$tag = '';
	$ct  = openssl_encrypt( $plain, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag );
	return 'g1:' . base64_encode( $iv . $tag . $ct );
}

function vitalstack_pay_open_seal( $sealed ) {
	$key = hash( 'sha256', wp_salt( 'secure_auth' ) . '|vs_pay', true );
	$raw = base64_decode( (string) substr( (string) $sealed, 3 ), true );
	if ( false === $raw ) {
		return '';
	}
	if ( 0 === strpos( $sealed, 's1:' ) && function_exists( 'sodium_crypto_secretbox_open' ) && strlen( $raw ) > SODIUM_CRYPTO_SECRETBOX_NONCEBYTES ) {
		$out = sodium_crypto_secretbox_open( substr( $raw, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES ), substr( $raw, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES ), $key );
		return false === $out ? '' : $out;
	}
	if ( 0 === strpos( $sealed, 'g1:' ) && strlen( $raw ) > 28 ) {
		$out = openssl_decrypt( substr( $raw, 28 ), 'aes-256-gcm', $key, OPENSSL_RAW_DATA, substr( $raw, 0, 12 ), substr( $raw, 12, 16 ) );
		return false === $out ? '' : $out;
	}
	return '';
}

/**
 * The keys in use: [key_id, key_secret, webhook_secret, mode].
 */
function vitalstack_pay_keys() {
	$s    = vitalstack_pay_settings();
	$mode = 'live' === $s['mode'] ? 'live' : 'test';
	$id   = defined( 'VITALSTACK_RZP_KEY_ID' ) ? VITALSTACK_RZP_KEY_ID : $s[ $mode . '_id' ];
	$sec  = defined( 'VITALSTACK_RZP_KEY_SECRET' ) ? VITALSTACK_RZP_KEY_SECRET : vitalstack_pay_open_seal( $s[ $mode . '_secret' ] );
	$hook = defined( 'VITALSTACK_RZP_WEBHOOK_SECRET' ) ? VITALSTACK_RZP_WEBHOOK_SECRET : vitalstack_pay_open_seal( $s['webhook'] );
	if ( defined( 'VITALSTACK_RZP_KEY_ID' ) ) {
		$mode = 0 === strpos( VITALSTACK_RZP_KEY_ID, 'rzp_live_' ) ? 'live' : 'test';
	}
	return array( (string) $id, (string) $sec, (string) $hook, $mode );
}

/**
 * Payments can be taken: sales switched on and keys present.
 */
function vitalstack_pay_ready() {
	list( $id, $sec ) = vitalstack_pay_keys();
	return vitalstack_pay_settings()['open'] && '' !== $id && '' !== $sec;
}

/* ── Order records (private; only admins see them) ───────────────────── */

function vitalstack_pay_register_orders() {
	register_post_type(
		'vs_order',
		array(
			'labels'              => array(
				'name'          => __( 'Premium orders', 'vitalstack' ),
				'singular_name' => __( 'Premium order', 'vitalstack' ),
				'menu_name'     => __( 'Orders', 'vitalstack' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'menu_icon'           => 'dashicons-cart',
			'menu_position'       => 58,
			'supports'            => array( 'title' ),
			'capabilities'        => array(
				'create_posts'       => 'do_not_allow',
				'edit_post'          => 'manage_options',
				'read_post'          => 'manage_options',
				'delete_post'        => 'manage_options',
				'edit_posts'         => 'manage_options',
				'edit_others_posts'  => 'manage_options',
				'publish_posts'      => 'manage_options',
				'read_private_posts' => 'manage_options',
				'delete_posts'       => 'manage_options',
			),
			'map_meta_cap'        => false,
		)
	);
}
add_action( 'init', 'vitalstack_pay_register_orders' );

add_filter(
	'manage_vs_order_posts_columns',
	function () {
		return array(
			'title'     => __( 'Order', 'vitalstack' ),
			'vs_reader' => __( 'Reader', 'vitalstack' ),
			'vs_amount' => __( 'Amount', 'vitalstack' ),
			'vs_status' => __( 'Status', 'vitalstack' ),
			'date'      => __( 'Date', 'vitalstack' ),
		);
	}
);
add_action(
	'manage_vs_order_posts_custom_column',
	function ( $col, $id ) {
		if ( 'vs_reader' === $col ) {
			$u = get_userdata( (int) get_post_field( 'post_author', $id ) );
			echo $u ? esc_html( $u->display_name . ' <' . $u->user_email . '>' ) : '—';
		} elseif ( 'vs_amount' === $col ) {
			$cur = get_post_meta( $id, '_vs_currency', true );
			echo esc_html( vitalstack_pay_price_label( (int) get_post_meta( $id, '_vs_amount', true ) / 100, 'INR' === $cur ? 'inr' : 'usd' ) . ' ' . $cur );
		} elseif ( 'vs_status' === $col ) {
			$st = get_post_meta( $id, '_vs_status', true );
			echo esc_html( 'paid' === $st ? '✅ Paid' : ( 'failed' === $st ? '❌ Failed' : '⏳ Not paid' ) );
			if ( 'test' === get_post_meta( $id, '_vs_mode', true ) ) {
				echo ' <em>(test)</em>';
			}
		}
	},
	10,
	2
);

function vitalstack_pay_find_order( $rzp_order_id ) {
	$ids = get_posts(
		array(
			'post_type'      => 'vs_order',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_vs_rzp_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => $rzp_order_id, // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	return $ids ? (int) $ids[0] : 0;
}

/* ── Giving access ────────────────────────────────────────────────────── */

/**
 * Mark an order paid and give its access, exactly once per order.
 *
 * @return bool True when the order is (now or already) paid.
 */
function vitalstack_pay_complete( $order_id, $payment_id ) {
	if ( 'paid' === get_post_meta( $order_id, '_vs_status', true ) ) {
		return true;
	}
	// add_option() fails if the row exists (a unique key in the database), so
	// two requests finishing the same order at once can't both grant it.
	if ( ! add_option( 'vs_pay_done_' . $order_id, time(), '', 'no' ) ) {
		return true;
	}
	$uid  = (int) get_post_field( 'post_author', $order_id );
	$plan = get_post_meta( $order_id, '_vs_plan', true );
	$all  = vitalstack_pay_plans();
	if ( ! $uid || ! isset( $all[ $plan ] ) ) {
		return false;
	}
	$p      = $all[ $plan ];
	$access = get_user_meta( $uid, 'vs_premium', true );
	$access = is_array( $access ) ? $access : array();
	$until  = 0;
	foreach ( $p['tracks'] as $track ) {
		$now = isset( $access[ $track ] ) ? (int) $access[ $track ] : 0;
		if ( 1 === $now ) {
			continue; // already has it with no end date
		}
		// Buying again before the end adds the time on, it never cuts it short.
		$access[ $track ] = max( time(), $now ) + $p['days'] * DAY_IN_SECONDS;
		$until            = max( $until, $access[ $track ] );
	}
	update_user_meta( $uid, 'vs_premium', $access );
	update_post_meta( $order_id, '_vs_status', 'paid' );
	update_post_meta( $order_id, '_vs_payment', $payment_id );
	update_post_meta( $order_id, '_vs_paid_at', time() );
	update_post_meta( $order_id, '_vs_until', $until );
	vitalstack_pay_receipt( $order_id );
	do_action( 'vitalstack_premium_paid', $uid, $plan, $order_id );
	return true;
}

function vitalstack_pay_receipt( $order_id ) {
	$user = get_userdata( (int) get_post_field( 'post_author', $order_id ) );
	$plan = vitalstack_pay_plans()[ get_post_meta( $order_id, '_vs_plan', true ) ] ?? null;
	if ( ! $user || ! $plan ) {
		return;
	}
	$cur   = get_post_meta( $order_id, '_vs_currency', true );
	$until = (int) get_post_meta( $order_id, '_vs_until', true );
	$rows  = array(
		__( 'Plan', 'vitalstack' )       => $plan['title'],
		__( 'Amount', 'vitalstack' )     => vitalstack_pay_price_label( (int) get_post_meta( $order_id, '_vs_amount', true ) / 100, 'INR' === $cur ? 'inr' : 'usd' ) . ' ' . $cur,
		__( 'Access until', 'vitalstack' ) => $until ? wp_date( get_option( 'date_format' ), $until ) : __( 'No end date', 'vitalstack' ),
		__( 'Payment ID', 'vitalstack' ) => get_post_meta( $order_id, '_vs_payment', true ),
		__( 'Order ID', 'vitalstack' )   => get_post_meta( $order_id, '_vs_rzp_order', true ),
	);
	$table = '<table style="border-collapse:collapse;margin:12px 0">';
	foreach ( $rows as $k => $v ) {
		$table .= '<tr><td style="padding:6px 14px 6px 0;color:#6b7280">' . esc_html( $k ) . '</td><td style="padding:6px 0;font-weight:700">' . esc_html( $v ) . '</td></tr>';
	}
	$table .= '</table>';
	$body = '<p>' . sprintf( /* translators: %s: name */ esc_html__( 'Hi %s,', 'vitalstack' ), esc_html( $user->display_name ) ) . '</p>'
		. '<p>' . esc_html__( 'Thank you! Your payment was received and your premium access is active.', 'vitalstack' ) . '</p>'
		. $table
		. vitalstack_email_button( vitalstack_account_url(), __( 'Start learning', 'vitalstack' ) )
		. '<p style="color:#6b7280;font-size:13px">' . esc_html__( 'Keep this email as your receipt. Questions about your payment? Just reply to this email.', 'vitalstack' ) . '</p>';
	vitalstack_mail( $user->user_email, __( 'Your VitalStack Premium receipt', 'vitalstack' ), $body );
}

/* ── REST API: create order, confirm payment, webhook ─────────────────── */

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'vitalstack/v1',
			'/pay/order',
			array(
				'methods'             => 'POST',
				'callback'            => 'vitalstack_pay_rest_order',
				'permission_callback' => 'is_user_logged_in',
			)
		);
		register_rest_route(
			'vitalstack/v1',
			'/pay/verify',
			array(
				'methods'             => 'POST',
				'callback'            => 'vitalstack_pay_rest_verify',
				'permission_callback' => 'is_user_logged_in',
			)
		);
		register_rest_route(
			'vitalstack/v1',
			'/pay/webhook',
			array(
				'methods'             => 'POST',
				'callback'            => 'vitalstack_pay_rest_webhook',
				'permission_callback' => '__return_true', // checked by signature
			)
		);
	}
);

function vitalstack_pay_error( $code, $message, $status = 400 ) {
	return new WP_Error( $code, $message, array( 'status' => $status ) );
}

function vitalstack_pay_rest_order( WP_REST_Request $req ) {
	if ( ! vitalstack_pay_ready() ) {
		return vitalstack_pay_error( 'closed', __( 'Premium sales open soon. Join the waitlist and we will email you.', 'vitalstack' ), 403 );
	}
	$uid   = get_current_user_id();
	$plans = vitalstack_pay_plans();
	$plan  = sanitize_key( (string) $req->get_param( 'plan' ) );
	$cur   = 'usd' === sanitize_key( (string) $req->get_param( 'currency' ) ) ? 'usd' : 'inr';
	if ( ! isset( $plans[ $plan ] ) ) {
		return vitalstack_pay_error( 'bad_plan', __( 'Please pick a plan.', 'vitalstack' ) );
	}
	if ( vitalstack_rate_limited( 'pay_order|' . $uid, 10, HOUR_IN_SECONDS ) ) {
		return vitalstack_pay_error( 'rate', __( 'Too many attempts. Please wait a few minutes and try again.', 'vitalstack' ), 429 );
	}
	list( $key_id, $secret, , $mode ) = vitalstack_pay_keys();
	$amount = (int) $plans[ $plan ][ $cur ] * 100; // paise / cents
	$order  = wp_insert_post(
		array(
			'post_type'   => 'vs_order',
			'post_status' => 'private',
			'post_author' => $uid,
			'post_title'  => $plans[ $plan ]['title'],
		),
		true
	);
	if ( is_wp_error( $order ) ) {
		return vitalstack_pay_error( 'failed', __( 'Something went wrong. Please try again.', 'vitalstack' ), 500 );
	}
	$res = wp_remote_post(
		'https://api.razorpay.com/v1/orders',
		array(
			'timeout' => 20,
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $key_id . ':' . $secret ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions
				'Content-Type'  => 'application/json',
			),
			'body'    => wp_json_encode(
				array(
					'amount'   => $amount,
					'currency' => strtoupper( $cur ),
					'receipt'  => 'vs_' . $order,
					'notes'    => array(
						'user' => (string) $uid,
						'plan' => $plan,
					),
				)
			),
		)
	);
	$body = is_wp_error( $res ) ? null : json_decode( wp_remote_retrieve_body( $res ), true );
	if ( ! is_array( $body ) || empty( $body['id'] ) || ! preg_match( '/^order_[A-Za-z0-9]{6,40}$/', $body['id'] ) || (int) $body['amount'] !== $amount ) {
		wp_delete_post( $order, true );
		$why = is_array( $body ) && ! empty( $body['error']['description'] ) ? $body['error']['description'] : '';
		if ( $why ) {
			error_log( 'VitalStack payments: Razorpay order failed: ' . sanitize_text_field( $why ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions
		}
		return vitalstack_pay_error( 'gateway', 'usd' === $cur ? __( 'Payments in US dollars are not available right now. Please try ₹ (INR) or try again later.', 'vitalstack' ) : __( 'The payment service is not responding. Please try again in a minute.', 'vitalstack' ), 502 );
	}
	update_post_meta( $order, '_vs_rzp_order', $body['id'] );
	update_post_meta( $order, '_vs_plan', $plan );
	update_post_meta( $order, '_vs_amount', $amount );
	update_post_meta( $order, '_vs_currency', strtoupper( $cur ) );
	update_post_meta( $order, '_vs_status', 'created' );
	update_post_meta( $order, '_vs_mode', $mode );

	$user = wp_get_current_user();
	return array(
		'key'         => $key_id,
		'order_id'    => $body['id'],
		'amount'      => $amount,
		'currency'    => strtoupper( $cur ),
		'name'        => get_bloginfo( 'name' ),
		'description' => $plans[ $plan ]['title'] . ' · ' . $plans[ $plan ]['period'],
		'prefill'     => array(
			'name'  => $user->display_name,
			'email' => $user->user_email,
		),
	);
}

function vitalstack_pay_rest_verify( WP_REST_Request $req ) {
	$oid = (string) $req->get_param( 'razorpay_order_id' );
	$pid = (string) $req->get_param( 'razorpay_payment_id' );
	$sig = (string) $req->get_param( 'razorpay_signature' );
	if ( ! preg_match( '/^order_[A-Za-z0-9]{6,40}$/', $oid ) || ! preg_match( '/^pay_[A-Za-z0-9]{6,40}$/', $pid ) || ! preg_match( '/^[a-f0-9]{64}$/', $sig ) ) {
		return vitalstack_pay_error( 'bad_reply', __( 'We could not confirm this payment. If money was taken, it will be confirmed automatically within a few minutes, or contact us.', 'vitalstack' ) );
	}
	if ( vitalstack_rate_limited( 'pay_verify|' . get_current_user_id(), 20, HOUR_IN_SECONDS ) ) {
		return vitalstack_pay_error( 'rate', __( 'Too many attempts. Please wait a few minutes and try again.', 'vitalstack' ), 429 );
	}
	$order = vitalstack_pay_find_order( $oid );
	// Only the reader who started the order can complete it from the browser.
	if ( ! $order || (int) get_post_field( 'post_author', $order ) !== get_current_user_id() ) {
		return vitalstack_pay_error( 'not_found', __( 'Order not found.', 'vitalstack' ), 404 );
	}
	list( , $secret ) = vitalstack_pay_keys();
	if ( '' === $secret || ! hash_equals( hash_hmac( 'sha256', $oid . '|' . $pid, $secret ), $sig ) ) {
		update_post_meta( $order, '_vs_bad_sig', time() );
		return vitalstack_pay_error( 'bad_signature', __( 'We could not confirm this payment. If money was taken, it will be confirmed automatically within a few minutes, or contact us.', 'vitalstack' ) );
	}
	vitalstack_pay_complete( $order, $pid );
	$plan   = get_post_meta( $order, '_vs_plan', true );
	$tracks = vitalstack_pay_plans()[ $plan ]['tracks'] ?? array( 'data-scientist' );
	return array(
		'ok'       => true,
		'redirect' => add_query_arg( 'vs_msg', 'paid', vitalstack_premium_url( $tracks[0] ) ),
	);
}

function vitalstack_pay_rest_webhook( WP_REST_Request $req ) {
	list( , , $hook_secret ) = vitalstack_pay_keys();
	$raw = $req->get_body();
	$sig = (string) $req->get_header( 'x_razorpay_signature' );
	if ( '' === $hook_secret || '' === $sig || ! hash_equals( hash_hmac( 'sha256', $raw, $hook_secret ), $sig ) ) {
		return new WP_REST_Response( array( 'ok' => false ), 400 );
	}
	$event = json_decode( $raw, true );
	$pay   = $event['payload']['payment']['entity'] ?? null;
	$type  = $event['event'] ?? '';
	if ( ! is_array( $pay ) || empty( $pay['order_id'] ) ) {
		return new WP_REST_Response( array( 'ok' => true ), 200 ); // not about an order of ours
	}
	$order = vitalstack_pay_find_order( (string) $pay['order_id'] );
	if ( ! $order ) {
		return new WP_REST_Response( array( 'ok' => true ), 200 );
	}
	if ( in_array( $type, array( 'payment.captured', 'order.paid' ), true ) ) {
		// The amount paid must be the amount we asked for.
		if ( (int) $pay['amount'] !== (int) get_post_meta( $order, '_vs_amount', true ) || strtoupper( (string) $pay['currency'] ) !== get_post_meta( $order, '_vs_currency', true ) ) {
			update_post_meta( $order, '_vs_mismatch', wp_json_encode( array( $pay['amount'], $pay['currency'] ) ) );
			return new WP_REST_Response( array( 'ok' => true ), 200 );
		}
		vitalstack_pay_complete( $order, sanitize_text_field( (string) $pay['id'] ) );
	} elseif ( 'payment.failed' === $type && 'paid' !== get_post_meta( $order, '_vs_status', true ) ) {
		update_post_meta( $order, '_vs_status', 'failed' );
	}
	return new WP_REST_Response( array( 'ok' => true ), 200 );
}

add_filter(
	'vitalstack_account_messages',
	function ( $msgs ) {
		$msgs['paid'] = array( 'success', __( 'Payment received 🎉 Your premium access is active. We emailed you a receipt.', 'vitalstack' ) );
		return $msgs;
	}
);

/* ── /pricing/ page ───────────────────────────────────────────────────── */

function vitalstack_pricing_url() {
	return home_url( '/pricing/' );
}

add_action(
	'init',
	function () {
		add_rewrite_rule( '^pricing/?$', 'index.php?vs_pricing=1', 'top' );
	}
);
add_filter(
	'query_vars',
	function ( $vars ) {
		$vars[] = 'vs_pricing';
		return $vars;
	}
);
add_filter(
	'pre_handle_404',
	function ( $handled ) {
		return get_query_var( 'vs_pricing' ) ? true : $handled;
	}
);

function vitalstack_pricing_template() {
	if ( ! get_query_var( 'vs_pricing' ) ) {
		return;
	}
	status_header( 200 );
	if ( is_user_logged_in() ) {
		nocache_headers(); // the page carries a per-reader security token
	}
	add_filter(
		'pre_get_document_title',
		function () {
			return __( 'Premium plans and pricing', 'vitalstack' ) . ' | ' . get_bloginfo( 'name' );
		}
	);
	add_action(
		'wp_head',
		function () {
			echo '<meta name="description" content="' . esc_attr__( 'VitalStack Premium: Data Scientist and Gen AI Developer career tracks with animated lessons, projects and certificates. Pay in ₹ with UPI or cards, or in $ worldwide.', 'vitalstack' ) . '">' . "\n";
			echo '<link rel="canonical" href="' . esc_url( vitalstack_pricing_url() ) . '">' . "\n";
		},
		2
	);
	wp_enqueue_script( 'vitalstack-pay', vitalstack_asset_url( 'assets/js/pay.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	get_header();
	include VITALSTACK_DIR . '/templates/pricing.php';
	get_footer();
	exit;
}
add_action( 'template_redirect', 'vitalstack_pricing_template', 6 );

/**
 * What a reader can do on /pricing/ for a plan: "buy", "signin", or "waitlist".
 */
function vitalstack_pay_action() {
	if ( ! vitalstack_pay_ready() ) {
		return 'waitlist';
	}
	return is_user_logged_in() ? 'buy' : 'signin';
}

/* ── Dashboard card ───────────────────────────────────────────────────── */

/**
 * "My premium" card on /account/: active access, or a short reason to upgrade.
 */
function vitalstack_pay_panel( $user_id ) {
	$tracks = vitalstack_premium_tracks();
	$access = get_user_meta( $user_id, 'vs_premium', true );
	$access = is_array( $access ) ? $access : array();
	$active = array();
	foreach ( $tracks as $slug => $t ) {
		$v = isset( $access[ $slug ] ) ? (int) $access[ $slug ] : 0;
		if ( 1 === $v || $v > time() ) {
			$active[ $slug ] = $v;
		}
	}
	?>
	<section class="panel pay-panel">
		<h2>⭐ <?php esc_html_e( 'Premium', 'vitalstack' ); ?></h2>
		<?php if ( $active ) : ?>
			<ul class="pay-panel-list">
				<?php foreach ( $active as $slug => $v ) : ?>
					<li>
						<a href="<?php echo esc_url( vitalstack_premium_url( $slug ) ); ?>"><?php echo esc_html( $tracks[ $slug ]['icon'] . ' ' . $tracks[ $slug ]['title'] ); ?></a>
						<span class="muted small"><?php echo 1 === $v ? esc_html__( 'No end date', 'vitalstack' ) : esc_html( sprintf( /* translators: %s: date */ __( 'Active until %s', 'vitalstack' ), wp_date( get_option( 'date_format' ), $v ) ) ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<p><?php esc_html_e( 'Become job-ready as a Data Scientist or Gen AI Developer: step-by-step animated lessons, real projects and a certificate.', 'vitalstack' ); ?></p>
			<a class="btn btn-primary btn-sm" href="<?php echo esc_url( vitalstack_pricing_url() ); ?>"><?php esc_html_e( 'See plans', 'vitalstack' ); ?></a>
		<?php endif; ?>
	</section>
	<?php
}

/* ── Settings > VitalStack Payments ───────────────────────────────────── */

add_action(
	'admin_menu',
	function () {
		add_options_page( __( 'VitalStack Payments', 'vitalstack' ), __( 'VitalStack Payments', 'vitalstack' ), 'manage_options', 'vs-payments', 'vitalstack_pay_settings_page' );
	}
);

function vitalstack_pay_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s     = vitalstack_pay_settings();
	$plans = vitalstack_pay_plans();
	list( $kid, $ksec, $khook, $kmode ) = vitalstack_pay_keys();
	$const = defined( 'VITALSTACK_RZP_KEY_ID' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'VitalStack Payments (Razorpay)', 'vitalstack' ); ?></h1>
		<?php if ( isset( $_GET['saved'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Saved.', 'vitalstack' ); ?></p></div>
		<?php endif; ?>
		<p>
			<?php esc_html_e( 'Status:', 'vitalstack' ); ?>
			<strong><?php echo vitalstack_pay_ready() ? esc_html( 'live' === $kmode ? __( '🟢 Taking real payments', 'vitalstack' ) : __( '🟡 Test mode (no real money)', 'vitalstack' ) ) : esc_html__( '⚪ Sales closed: readers see “Join the waitlist”', 'vitalstack' ); ?></strong>
		</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="vs_pay_settings">
			<?php wp_nonce_field( 'vs_pay_settings', '_vsnonce' ); ?>
			<table class="form-table" role="presentation">
				<tr><th><?php esc_html_e( 'Sales', 'vitalstack' ); ?></th><td><label><input type="checkbox" name="open" value="1" <?php checked( $s['open'] ); ?>> <?php esc_html_e( 'Open: readers can buy premium plans', 'vitalstack' ); ?></label></td></tr>
				<?php if ( $const ) : ?>
					<tr><th><?php esc_html_e( 'Keys', 'vitalstack' ); ?></th><td><?php esc_html_e( 'Set in wp-config.php (VITALSTACK_RZP_KEY_ID / _KEY_SECRET / _WEBHOOK_SECRET).', 'vitalstack' ); ?> <code><?php echo esc_html( $kid ); ?></code></td></tr>
				<?php else : ?>
					<tr><th><?php esc_html_e( 'Mode', 'vitalstack' ); ?></th><td>
						<label><input type="radio" name="mode" value="test" <?php checked( 'live' !== $s['mode'] ); ?>> <?php esc_html_e( 'Test (use Razorpay test keys, no real money)', 'vitalstack' ); ?></label><br>
						<label><input type="radio" name="mode" value="live" <?php checked( 'live' === $s['mode'] ); ?>> <?php esc_html_e( 'Live', 'vitalstack' ); ?></label>
					</td></tr>
					<?php foreach ( array( 'test' => __( 'Test', 'vitalstack' ), 'live' => __( 'Live', 'vitalstack' ) ) as $m => $label ) : ?>
						<tr><th><?php echo esc_html( $label . ' Key ID' ); ?></th><td><input class="regular-text" name="<?php echo esc_attr( $m ); ?>_id" value="<?php echo esc_attr( $s[ $m . '_id' ] ); ?>" placeholder="rzp_<?php echo esc_attr( $m ); ?>_..." autocomplete="off"></td></tr>
						<tr><th><?php echo esc_html( $label . ' Key Secret' ); ?></th><td><input class="regular-text" type="password" name="<?php echo esc_attr( $m ); ?>_secret" value="" placeholder="<?php echo $s[ $m . '_secret' ] ? esc_attr__( '•••••••• saved (leave empty to keep)', 'vitalstack' ) : ''; ?>" autocomplete="new-password"></td></tr>
					<?php endforeach; ?>
					<tr><th><?php esc_html_e( 'Webhook Secret', 'vitalstack' ); ?></th><td><input class="regular-text" type="password" name="webhook" value="" placeholder="<?php echo $s['webhook'] ? esc_attr__( '•••••••• saved (leave empty to keep)', 'vitalstack' ) : ''; ?>" autocomplete="new-password">
						<p class="description"><?php esc_html_e( 'Secrets are stored encrypted. They are never shown again.', 'vitalstack' ); ?></p></td></tr>
				<?php endif; ?>
				<tr><th><?php esc_html_e( 'Webhook URL', 'vitalstack' ); ?></th><td><code><?php echo esc_html( rest_url( 'vitalstack/v1/pay/webhook' ) ); ?></code>
					<p class="description"><?php esc_html_e( 'In Razorpay Dashboard > Settings > Webhooks, add this URL with the events payment.captured, order.paid and payment.failed, and the same webhook secret as above.', 'vitalstack' ); ?></p>
					<p class="description"><?php echo $khook ? '✅ ' . esc_html__( 'Webhook secret set.', 'vitalstack' ) : '⚠️ ' . esc_html__( 'No webhook secret yet: payments still work, but a reader who closes the tab right after paying needs you to give access by hand.', 'vitalstack' ); ?></p></td></tr>
				<tr><th><?php esc_html_e( 'Prices', 'vitalstack' ); ?></th><td>
					<table class="widefat striped" style="max-width:560px"><thead><tr><th><?php esc_html_e( 'Plan', 'vitalstack' ); ?></th><th>₹ INR</th><th>$ USD</th></tr></thead><tbody>
					<?php foreach ( $plans as $id => $p ) : ?>
						<tr><td><?php echo esc_html( $p['title'] . ' (' . $p['period'] . ')' ); ?></td>
							<td><input type="number" min="1" max="1000000" step="1" name="price[<?php echo esc_attr( $id ); ?>][inr]" value="<?php echo (int) $p['inr']; ?>" style="width:100px"></td>
							<td><input type="number" min="1" max="100000" step="1" name="price[<?php echo esc_attr( $id ); ?>][usd]" value="<?php echo (int) $p['usd']; ?>" style="width:90px"></td></tr>
					<?php endforeach; ?>
					</tbody></table>
					<p class="description"><?php esc_html_e( 'US dollar payments need “International payments” switched on in your Razorpay account.', 'vitalstack' ); ?></p>
				</td></tr>
				<tr><th><?php esc_html_e( 'Refund window', 'vitalstack' ); ?></th><td><input type="number" min="0" max="60" name="refund_days" value="<?php echo (int) $s['refund_days']; ?>" style="width:70px"> <?php esc_html_e( 'days (shown on the pricing page; 0 hides it)', 'vitalstack' ); ?></td></tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<p><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=vs_order' ) ); ?>"><?php esc_html_e( 'See all orders', 'vitalstack' ); ?></a> · <a href="<?php echo esc_url( vitalstack_pricing_url() ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Open the pricing page', 'vitalstack' ); ?></a></p>
	</div>
	<?php
}

function vitalstack_pay_save_settings() {
	if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['_vsnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_pay_settings' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'vitalstack' ), 403 );
	}
	$s         = vitalstack_pay_settings();
	$s['open'] = ! empty( $_POST['open'] );
	if ( isset( $_POST['mode'] ) ) {
		$s['mode'] = 'live' === $_POST['mode'] ? 'live' : 'test';
	}
	foreach ( array( 'test', 'live' ) as $m ) {
		if ( isset( $_POST[ $m . '_id' ] ) ) {
			$id                 = sanitize_text_field( wp_unslash( $_POST[ $m . '_id' ] ) );
			$s[ $m . '_id' ] = preg_match( '/^rzp_' . $m . '_[A-Za-z0-9]{6,40}$/', $id ) ? $id : '';
		}
		$secret = isset( $_POST[ $m . '_secret' ] ) ? trim( (string) wp_unslash( $_POST[ $m . '_secret' ] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		if ( '' !== $secret && preg_match( '/^[A-Za-z0-9]{10,64}$/', $secret ) ) {
			$s[ $m . '_secret' ] = vitalstack_pay_seal( $secret );
		}
	}
	$hook = isset( $_POST['webhook'] ) ? trim( (string) wp_unslash( $_POST['webhook'] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	if ( '' !== $hook && strlen( $hook ) <= 200 ) {
		$s['webhook'] = vitalstack_pay_seal( $hook );
	}
	$prices = isset( $_POST['price'] ) && is_array( $_POST['price'] ) ? wp_unslash( $_POST['price'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	foreach ( array_keys( vitalstack_pay_plans() ) as $id ) {
		foreach ( array( 'inr' => 1000000, 'usd' => 100000 ) as $cur => $max ) {
			$v = isset( $prices[ $id ][ $cur ] ) ? absint( $prices[ $id ][ $cur ] ) : 0;
			if ( $v >= 1 && $v <= $max ) {
				$s['prices'][ $id ][ $cur ] = $v;
			}
		}
	}
	$s['refund_days'] = isset( $_POST['refund_days'] ) ? min( 60, absint( $_POST['refund_days'] ) ) : $s['refund_days'];
	update_option( 'vs_pay', $s, false );
	wp_safe_redirect( admin_url( 'options-general.php?page=vs-payments&saved=1' ) );
	exit;
}
add_action( 'admin_post_vs_pay_settings', 'vitalstack_pay_save_settings' );
