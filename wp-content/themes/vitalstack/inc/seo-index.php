<?php
/**
 * 4.20.5: a clean sitemap and faster indexing.
 *
 * - Locked premium lessons are noindex (only a short preview is on the page),
 *   so they no longer appear in the XML sitemap either. A sitemap that lists
 *   noindex pages makes Search Console report them as errors.
 * - Once after each theme update, every URL in the sitemap is sent to
 *   IndexNow (Bing, Yandex, Seznam, Naver...) in one batch. New and updated
 *   pages are already announced one by one (inc/schema.php).
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/** IDs of published premium (locked) lessons, cached for 12 hours. */
function vitalstack_premium_lesson_ids() {
	$ids = get_transient( 'vs_premium_lesson_ids' );
	if ( is_array( $ids ) ) {
		return $ids;
	}
	$ids = array();
	if ( function_exists( 'vitalstack_lesson_track' ) ) {
		$all = get_posts(
			array(
				'post_type'      => 'tutorials',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		foreach ( $all as $id ) {
			if ( vitalstack_lesson_track( $id ) ) {
				$ids[] = (int) $id;
			}
		}
	}
	set_transient( 'vs_premium_lesson_ids', $ids, 12 * HOUR_IN_SECONDS );
	return $ids;
}

/** Lessons or paths changed: rebuild the list. */
function vitalstack_premium_lesson_ids_flush() {
	delete_transient( 'vs_premium_lesson_ids' );
}
add_action( 'save_post_tutorials', 'vitalstack_premium_lesson_ids_flush' );
add_action( 'set_object_terms', 'vitalstack_premium_lesson_ids_flush' );
add_action( 'edited_tutorial_category', 'vitalstack_premium_lesson_ids_flush' );

/** WordPress core sitemap (wp-sitemap.xml). */
function vitalstack_sitemap_skip_premium( $args, $post_type ) {
	if ( 'tutorials' === $post_type ) {
		$skip = vitalstack_premium_lesson_ids();
		if ( $skip ) {
			$args['post__not_in'] = array_merge( isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(), $skip );
		}
	}
	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'vitalstack_sitemap_skip_premium', 10, 2 );

/** Yoast SEO and Rank Math sitemaps, if one of them is used instead. */
add_filter(
	'wpseo_exclude_from_sitemap_by_post_ids',
	function ( $ids ) {
		return array_merge( (array) $ids, vitalstack_premium_lesson_ids() );
	}
);
add_filter(
	'rank_math/sitemap/posts_to_exclude',
	function ( $ids ) {
		return array_merge( (array) $ids, vitalstack_premium_lesson_ids() );
	}
);

/* ── One IndexNow batch after each theme update ───────────────────────── */

/** Every URL the core sitemap lists (after the exclusions above). */
function vitalstack_sitemap_urls( $max = 10000 ) {
	$urls   = array();
	$server = function_exists( 'wp_sitemaps_get_server' ) ? wp_sitemaps_get_server() : null;
	if ( ! $server || ! $server->sitemaps_enabled() ) {
		return $urls;
	}
	foreach ( $server->registry->get_providers() as $provider ) {
		$subtypes = array_keys( (array) $provider->get_object_subtypes() );
		if ( ! $subtypes ) {
			$subtypes = array( '' );
		}
		foreach ( $subtypes as $sub ) {
			$pages = max( 1, (int) $provider->get_max_num_pages( $sub ) );
			for ( $p = 1; $p <= $pages; $p++ ) {
				foreach ( (array) $provider->get_url_list( $p, $sub ) as $row ) {
					if ( ! empty( $row['loc'] ) ) {
						$urls[] = $row['loc'];
					}
					if ( count( $urls ) >= $max ) {
						return array_values( array_unique( $urls ) );
					}
				}
			}
		}
	}
	return array_values( array_unique( $urls ) );
}

/** Whether this site's address is a real public domain. */
function vitalstack_indexnow_host_ok() {
	$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
	return $host && 'localhost' !== $host && ! preg_match( '/\.(local|test)$|hostingersite\.com$/', $host ) && get_option( 'blog_public' );
}

/** Schedule the batch once per theme version. */
function vitalstack_indexnow_bulk_schedule() {
	if ( ! defined( 'VITALSTACK_VERSION' ) || get_option( 'vs_indexnow_bulk' ) === VITALSTACK_VERSION || ! vitalstack_indexnow_host_ok() ) {
		return;
	}
	if ( ! wp_next_scheduled( 'vitalstack_indexnow_bulk' ) ) {
		wp_schedule_single_event( time() + 5 * MINUTE_IN_SECONDS, 'vitalstack_indexnow_bulk' );
	}
}
add_action( 'admin_init', 'vitalstack_indexnow_bulk_schedule' );

/** Send the batch (runs in WP-Cron, not during a page view). */
function vitalstack_indexnow_bulk_send() {
	if ( ! vitalstack_indexnow_host_ok() || ! function_exists( 'vitalstack_indexnow_key' ) ) {
		return;
	}
	$urls = vitalstack_sitemap_urls();
	if ( ! $urls ) {
		return;
	}
	$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
	$key  = vitalstack_indexnow_key();
	$res  = wp_remote_post(
		'https://api.indexnow.org/indexnow',
		array(
			'timeout' => 20,
			'headers' => array( 'Content-Type' => 'application/json; charset=utf-8' ),
			'body'    => wp_json_encode(
				array(
					'host'        => $host,
					'key'         => $key,
					'keyLocation' => home_url( '/' . $key . '.txt' ),
					'urlList'     => $urls,
				)
			),
		)
	);
	$code = is_wp_error( $res ) ? 0 : (int) wp_remote_retrieve_response_code( $res );
	update_option( 'vs_indexnow_last', array( 't' => time(), 'n' => count( $urls ), 'code' => $code ), false );
	if ( $code >= 200 && $code < 300 ) {
		update_option( 'vs_indexnow_bulk', VITALSTACK_VERSION, false );
	}
}
add_action( 'vitalstack_indexnow_bulk', 'vitalstack_indexnow_bulk_send' );
