<?php
/**
 * Copy limits for Motion Studio (code and build prompts), checked on the server.
 *
 * - Visitors: 2 free copies (per network + browser, 30 days), then "create a free account".
 * - Free account: 10 copies of free items per day.
 * - AI Pro: unlimited, including premium items.
 *
 * The code and prompts are not in the page: the browser asks for one item at a
 * time and only gets it when the limits allow. A per-IP ceiling stops scraping.
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

const VSAI_GUEST_COPIES = 2;
const VSAI_FREE_DAILY   = 10;

/**
 * Who a visitor is for the free-copy count: the connecting address + browser.
 * Forwarded-for headers are ignored here on purpose (anyone can fake them).
 */
function vsai_client_key() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$ua = isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ), 0, 200 ) : '';
	return hash_hmac( 'sha256', $ip . '|' . $ua, wp_salt( 'nonce' ) );
}

/** Scraping ceiling key: signed-in readers by account, visitors by connecting address. */
function vsai_ip_key( $edge = false ) {
	if ( ! $edge && is_user_logged_in() ) {
		return hash_hmac( 'sha256', 'u' . get_current_user_id(), wp_salt( 'nonce' ) );
	}
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	return hash_hmac( 'sha256', $ip, wp_salt( 'nonce' ) );
}

/** Copies left right now for this visitor (null = unlimited). */
function vsai_copies_left() {
	if ( vsai_is_member() ) {
		return null;
	}
	if ( is_user_logged_in() ) {
		$used = (int) get_user_meta( get_current_user_id(), 'vsai_copies_' . gmdate( 'Ymd' ), true );
		return max( 0, VSAI_FREE_DAILY - $used );
	}
	return max( 0, VSAI_GUEST_COPIES - (int) get_transient( 'vsai_g_' . substr( vsai_client_key(), 0, 40 ) ) );
}

function vsai_count_copy() {
	if ( vsai_is_member() ) {
		return;
	}
	if ( is_user_logged_in() ) {
		$k = 'vsai_copies_' . gmdate( 'Ymd' );
		update_user_meta( get_current_user_id(), $k, (int) get_user_meta( get_current_user_id(), $k, true ) + 1 );
		return;
	}
	$k = 'vsai_g_' . substr( vsai_client_key(), 0, 40 );
	set_transient( $k, (int) get_transient( $k ) + 1, 30 * DAY_IN_SECONDS );
}

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'vsai/v1',
			'/copy',
			array(
				'methods'             => 'POST',
				'permission_callback' => '__return_true', // limits are enforced inside; logged-in calls need a valid nonce (REST cookie auth)
				'args'                => array(
					'id'   => array( 'type' => 'string', 'required' => true, 'pattern' => '^[a-z0-9-]{1,60}$' ),
					'part' => array( 'type' => 'string', 'required' => true, 'enum' => array( 'code', 'prompt' ) ),
				),
				'callback'            => 'vsai_rest_copy',
			)
		);
	}
);

function vsai_rest_copy( WP_REST_Request $req ) {
	nocache_headers();
	// Ceilings, members included: 120 an hour per account (or visitor address) and
	// 3,000 an hour per connecting address (a CDN edge can carry many visitors). Stops bulk scraping.
	foreach ( array( array( 'vsai_ip_' . substr( vsai_ip_key(), 0, 40 ), 120 ), array( 'vsai_edge_' . substr( vsai_ip_key( true ), 0, 40 ), 3000 ) ) as $c ) {
		$hits = (int) get_transient( $c[0] );
		if ( $hits >= $c[1] ) {
			return new WP_Error( 'rate', __( 'Too many requests. Please try again later.', 'vitalstack-ai-hub' ), array( 'status' => 429 ) );
		}
		set_transient( $c[0], $hits + 1, HOUR_IN_SECONDS );
	}

	$it = vsai_find( 'motion', (string) $req['id'] );
	if ( ! $it ) {
		return new WP_Error( 'not_found', __( 'This item no longer exists.', 'vitalstack-ai-hub' ), array( 'status' => 404 ) );
	}
	if ( $it['premium'] && ! vsai_is_member() ) {
		return new WP_Error( 'upgrade', __( 'This is a Pro design. Go unlimited to copy it.', 'vitalstack-ai-hub' ), array( 'status' => 402, 'login' => ! is_user_logged_in() ) );
	}
	$left = vsai_copies_left();
	if ( null !== $left && $left < 1 ) {
		return is_user_logged_in()
			? new WP_Error( 'limit', __( 'You have used today’s free copies. Go unlimited, or come back tomorrow.', 'vitalstack-ai-hub' ), array( 'status' => 429, 'login' => false ) )
			: new WP_Error( 'login', __( 'You’ve reached your free copy limit. Create a free account or upgrade to keep copying.', 'vitalstack-ai-hub' ), array( 'status' => 401, 'login' => true ) );
	}
	vsai_count_copy();
	$text = 'code' === $req['part'] ? (string) $it['code'] : vsai_full_prompt( $it );
	return rest_ensure_response(
		array(
			'text' => $text,
			'left' => vsai_copies_left(),
			'note' => vsai_left_text(),
		)
	);
}
