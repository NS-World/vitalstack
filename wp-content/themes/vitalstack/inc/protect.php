<?php
/**
 * Premium lesson protection (design §10).
 *
 *   1. For paying readers the lesson page is only a shell: the premium text
 *      is not in the page source, caches or feeds.
 *   2. The browser asks /vitalstack/v1/lesson/<id> for the body. The server
 *      checks the plan, suspension, device limit and reading speed every
 *      time, logs the open, and sends the body with no-store caching.
 *   3. The body carries an invisible watermark (zero-width characters that
 *      encode the reader's ID) and the page shows a visible one (name, ID,
 *      date), so a leaked copy points to one account.
 *   4. In the browser, selecting, copying, right-click and printing are
 *      turned off for the lesson text. Code keeps its own Copy button.
 *
 * Staff always see lessons directly.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

const VITALSTACK_PROTECT_HOUR = 40;  // different premium lessons per hour
const VITALSTACK_PROTECT_DAY  = 200; // and per day

/**
 * Lessons are served through the API to paying readers (not staff).
 */
function vitalstack_protect_applies( $user_id = null ) {
	$user_id = null === $user_id ? get_current_user_id() : (int) $user_id;
	return $user_id && ! user_can( $user_id, 'edit_posts' ) && empty( $GLOBALS['vitalstack_protect_raw'] );
}

/**
 * The lesson shell: no premium text, just a place the body loads into.
 */
function vitalstack_protect_shell( $post_id ) {
	return '<div class="vs-protected" data-lesson="' . (int) $post_id . '" data-api="' . esc_url( rest_url( 'vitalstack/v1/lesson/' ) ) . '" data-nonce="' . esc_attr( wp_create_nonce( 'wp_rest' ) ) . '">'
		. '<div class="vs-protected-state"><span class="vs-protected-spin" aria-hidden="true"></span> ' . esc_html__( 'Opening your premium lesson…', 'vitalstack' ) . '</div>'
		. '<noscript><p class="notice notice-info">' . esc_html__( 'Premium lessons need JavaScript turned on.', 'vitalstack' ) . '</p></noscript></div>';
}

// The lesson page itself must never be cached for a signed-in premium reader.
add_action(
	'template_redirect',
	function () {
		if ( is_singular( 'tutorials' ) && is_user_logged_in() && function_exists( 'vitalstack_lesson_track' ) && vitalstack_lesson_track( get_queried_object_id() ) ) {
			nocache_headers();
			header( 'Cache-Control: private, no-store, max-age=0', true );
		}
	},
	2
);

/* ── Invisible watermark ──────────────────────────────────────────────── */

// 0 = U+200B, 1 = U+200C, start = U+200D, end = U+2060. They take no space and survive copy-paste.
function vitalstack_watermark_code( $uid ) {
	$check = ord( hash_hmac( 'sha256', (string) $uid, wp_salt( 'auth' ) . '|wm', true )[0] );
	$bits  = str_pad( decbin( (int) $uid ), 32, '0', STR_PAD_LEFT ) . str_pad( decbin( $check ), 8, '0', STR_PAD_LEFT );
	return "\u{200D}" . strtr( $bits, array( '0' => "\u{200B}", '1' => "\u{200C}" ) ) . "\u{2060}";
}

/**
 * Put the reader's mark after the first word of every paragraph and list item.
 */
function vitalstack_watermark( $html, $uid ) {
	$mark = vitalstack_watermark_code( $uid );
	return preg_replace_callback(
		'#(<(?:p|li)(?:\s[^>]*)?>)([^<\s][^<]*?)(\s)#u',
		function ( $m ) use ( $mark ) {
			return $m[1] . $m[2] . $mark . $m[3];
		},
		$html
	);
}

/**
 * Read a mark from leaked text: the reader ID found most often, or 0.
 */
function vitalstack_watermark_read( $text ) {
	preg_match_all( "/\u{200D}([\u{200B}\u{200C}]{40})\u{2060}/u", (string) $text, $m );
	$votes = array();
	foreach ( $m[1] as $code ) {
		$bits  = strtr( $code, array( "\u{200B}" => '0', "\u{200C}" => '1' ) );
		$uid   = bindec( substr( $bits, 0, 32 ) );
		$check = bindec( substr( $bits, 32, 8 ) );
		if ( $uid && ord( hash_hmac( 'sha256', (string) $uid, wp_salt( 'auth' ) . '|wm', true )[0] ) === $check ) {
			$votes[ $uid ] = ( $votes[ $uid ] ?? 0 ) + 1;
		}
	}
	arsort( $votes );
	return $votes ? (int) array_key_first( $votes ) : 0;
}

/* ── Reading-speed limit ──────────────────────────────────────────────── */

/**
 * Count a premium lesson open. Re-opening the same lesson doesn't count.
 *
 * @return bool False when this open goes over the limit (premium pauses).
 */
function vitalstack_protect_count( $uid, $lesson ) {
	foreach ( array( 'h' => array( HOUR_IN_SECONDS, VITALSTACK_PROTECT_HOUR ), 'd' => array( DAY_IN_SECONDS, VITALSTACK_PROTECT_DAY ) ) as $k => $rule ) {
		$key  = 'vs_prem_' . $k . '_' . $uid;
		$seen = get_transient( $key );
		$seen = is_array( $seen ) ? $seen : array( 'until' => time() + $rule[0], 'ids' => array() );
		if ( in_array( $lesson, $seen['ids'], true ) ) {
			continue;
		}
		if ( count( $seen['ids'] ) >= $rule[1] ) {
			set_transient( 'vs_prem_pause_' . $uid, time(), HOUR_IN_SECONDS );
			vitalstack_audit( 'premium_paused', $uid, array( 'rule' => 'h' === $k ? 'hour' : 'day' ), true );
			return false;
		}
		$seen['ids'][] = $lesson;
		set_transient( $key, $seen, max( 1, $seen['until'] - time() ) );
	}
	return true;
}

/* ── The lesson API ───────────────────────────────────────────────────── */

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'vitalstack/v1',
			'/lesson/(?P<id>\d+)',
			array(
				'methods'             => 'GET',
				'callback'            => 'vitalstack_rest_lesson',
				'permission_callback' => 'is_user_logged_in',
				'args'                => array(
					'id' => array(
						'validate_callback' => function ( $v ) {
							return is_numeric( $v );
						},
					),
				),
			)
		);
		register_rest_route(
			'vitalstack/v1',
			'/lesson/switch-device',
			array(
				'methods'             => 'POST',
				'callback'            => 'vitalstack_rest_switch_device',
				'permission_callback' => 'is_user_logged_in',
			)
		);
	}
);

function vitalstack_protect_error( $code, $msg, $status, $extra = array() ) {
	return new WP_Error( $code, $msg, array_merge( array( 'status' => $status ), $extra ) );
}

function vitalstack_rest_lesson( WP_REST_Request $req ) {
	$uid  = get_current_user_id();
	$id   = (int) $req->get_param( 'id' );
	$post = get_post( $id );
	if ( ! $post || 'tutorials' !== $post->post_type || 'publish' !== $post->post_status ) {
		return vitalstack_protect_error( 'not_found', __( 'Lesson not found.', 'vitalstack' ), 404 );
	}
	$track = vitalstack_lesson_track( $id );
	if ( ! $track || ! vitalstack_user_has_premium( $track, $uid ) ) {
		return vitalstack_protect_error( 'no_access', __( 'This lesson is part of a premium track you don’t have yet.', 'vitalstack' ), 403 );
	}
	if ( function_exists( 'vitalstack_suspended' ) && vitalstack_suspended( $uid ) ) {
		return vitalstack_protect_error( 'suspended', __( 'This account is suspended. Please contact us.', 'vitalstack' ), 403 );
	}
	if ( vitalstack_protect_applies( $uid ) ) {
		$dev = vitalstack_device_check( $uid );
		if ( true !== $dev ) {
			return vitalstack_protect_error( 'device_switch', __( 'Premium is open on another device of this kind.', 'vitalstack' ), 409, array( 'other' => $dev['other']['label'], 'type' => $dev['other']['type'] ) );
		}
		$fresh = function_exists( 'vitalstack_stepup_fresh' ) && vitalstack_stepup_fresh( $uid );
		if ( get_transient( 'vs_prem_pause_' . $uid ) && ! $fresh ) {
			return vitalstack_protect_error( 'paused', __( 'You opened a lot of lessons very quickly, so premium is paused for safety. Confirm it’s you to continue.', 'vitalstack' ), 429 );
		}
		if ( ! $fresh && ! vitalstack_protect_count( $uid, $id ) ) {
			return vitalstack_protect_error( 'paused', __( 'You opened a lot of lessons very quickly, so premium is paused for safety. Confirm it’s you to continue.', 'vitalstack' ), 429 );
		}
		if ( $fresh ) {
			delete_transient( 'vs_prem_pause_' . $uid );
			vitalstack_protect_count( $uid, $id );
		}
	}
	vitalstack_audit( 'lesson_open', $uid, array( 'lesson' => $id ) );

	// Render exactly like the page would, then stamp it.
	$GLOBALS['vitalstack_protect_raw'] = true;
	setup_postdata( $post );
	$html = apply_filters( 'the_content', $post->post_content ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- core filter
	wp_reset_postdata();
	unset( $GLOBALS['vitalstack_protect_raw'] );
	$user = wp_get_current_user();
	$res  = new WP_REST_Response(
		array(
			'html' => vitalstack_watermark( $html, $uid ),
			'mark' => $user->display_name . ' · #' . $uid . ' · ' . wp_date( 'j M Y' ),
		)
	);
	$res->header( 'Cache-Control', 'private, no-store, max-age=0' );
	return $res;
}

/**
 * Move premium to this device (needs a security check in the last 15 minutes).
 */
function vitalstack_rest_switch_device( WP_REST_Request $req ) {
	$uid = get_current_user_id();
	if ( ! function_exists( 'vitalstack_stepup_fresh' ) || ! vitalstack_stepup_fresh( $uid ) ) {
		return vitalstack_protect_error( 'stepup', __( 'Please confirm it’s you first.', 'vitalstack' ), 403, array( 'method' => vitalstack_totp_on( $uid ) ? 'app' : 'email' ) );
	}
	$done = vitalstack_device_switch( $uid );
	return is_wp_error( $done ) ? $done : array( 'ok' => true );
}

/* ── Assets ───────────────────────────────────────────────────────────── */

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( is_singular( 'tutorials' ) && is_user_logged_in() && vitalstack_lesson_track( get_queried_object_id() ) && vitalstack_protect_applies() ) {
			wp_enqueue_script( 'vitalstack-protect', vitalstack_asset_url( 'assets/js/protect.js' ), array( 'vitalstack-main' ), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
			// The animations a lesson might need, since its body arrives later.
			if ( function_exists( 'vitalstack_asset_url' ) ) {
				wp_enqueue_script( 'vitalstack-viz', vitalstack_asset_url( 'assets/js/viz.js' ), array( 'vitalstack-fun' ), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
				wp_enqueue_script( 'vitalstack-walk', vitalstack_asset_url( 'assets/js/walk.js' ), array( 'vitalstack-fun' ), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
			}
		}
	},
	20
);
