<?php
/**
 * 4.20.7: tidy up the previous site's URLs that Google still crawls.
 *
 * From the Search Console exports of 18 Nov 2026 (404, 5xx, crawled-not-indexed):
 * - Old URLs with a clear new home get a 301 redirect, so their links and
 *   visitors land on the matching page.
 * - Old pages that are gone for good (health/news posts, old tags, lorem ipsum
 *   test pages, cut-off links) answer "410 Gone" instead of "404 Not found".
 *   Visitors still see the normal page-not-found screen; Google drops a 410 sooner.
 * - Feeds and date archives are kept for readers but marked noindex: they only
 *   repeat the lessons and posts, which are indexed themselves.
 *
 * Only requests that would otherwise be a 404 are touched (except the
 * redirect map), so no existing page changes.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/** Path of the current request without the site's sub-folder, e.g. "/news-page/page/2/". */
function vitalstack_old_url_path() {
	$path = (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- only compared below
	$base = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	if ( '/' !== $base && 0 === strpos( $path, rtrim( $base, '/' ) ) ) {
		$path = substr( $path, strlen( rtrim( $base, '/' ) ) );
	}
	return '/' . ltrim( strtolower( $path ), '/' );
}

/** First published page among $slugs, as a URL, or ''. */
function vitalstack_old_url_page( $slugs ) {
	foreach ( (array) $slugs as $slug ) {
		$p = get_page_by_path( $slug );
		if ( $p && 'publish' === $p->post_status ) {
			return get_permalink( $p );
		}
	}
	return '';
}

/** Learning path URL for the first slug that exists, or ''. */
function vitalstack_old_url_path_term( $slugs ) {
	foreach ( (array) $slugs as $slug ) {
		$t = get_term_by( 'slug', $slug, 'tutorial_category' );
		if ( $t && ! is_wp_error( $t ) ) {
			$link = get_term_link( $t );
			return is_wp_error( $link ) ? '' : $link;
		}
	}
	return '';
}

/**
 * Where an old URL should go now, or '' to leave it alone.
 *
 * @param string $path Lower-case path from vitalstack_old_url_path().
 */
function vitalstack_old_url_target( $path ) {
	$tutorials = vitalstack_old_url_page( array( 'tutorials' ) );
	$blog      = vitalstack_old_url_page( array( 'blog' ) );
	$exact     = array(
		'/terms-and-conditions/' => vitalstack_old_url_page( array( 'terms-of-service', 'terms' ) ),
		'/tutorials-page/'       => $tutorials,
		'/tutorial-category/html-tutorial/'  => vitalstack_old_url_path_term( array( 'html' ) ),
		'/tutorial-category/react-tutorial/' => vitalstack_old_url_path_term( array( 'react' ) ),
		'/tutorial-category/backend-development/' => vitalstack_old_url_path_term( array( 'spring-boot', 'spring', 'java' ) ),
		'/tutorials/java-tutorial-for-beginners-2026-complete-guide/' => vitalstack_old_url_path_term( array( 'java' ) ),
		'/tutorials/oop-concepts-in-java/' => vitalstack_old_url_path_term( array( 'java' ) ),
		'/tutorials/mongodb-tutorial-for-beginners/' => vitalstack_old_url_path_term( array( 'mongodb' ) ),
		'/tutorials/rest-api-tutorial-for-beginners/' => vitalstack_old_url_path_term( array( 'spring-boot', 'spring', 'java' ) ),
		'/tutorials/html-tutorial-for-beginners/' => vitalstack_old_url_path_term( array( 'html' ) ),
		'/tutorials/react-tutorial-for-beginners-with-projects/' => vitalstack_old_url_path_term( array( 'react' ) ),
		'/free-ai-tools-for-students-2026/' => vitalstack_old_url_page( array( 'tools' ) ),
		'/prompt-engineering-mastery/' => vitalstack_old_url_page( array( 'how-to-write-better-prompts' ) ),
		'/best-ai-coding-tools-2026/' => vitalstack_old_url_page( array( 'ai-coding-assistants-guide' ) ),
	);
	$key = trailingslashit( $path );
	if ( isset( $exact[ $key ] ) ) {
		return $exact[ $key ] ? $exact[ $key ] : $tutorials;
	}
	// The old blog listing and its pages/filters.
	if ( preg_match( '#^/(news-page|category/technology)(/|$)#', $path ) ) {
		return $blog;
	}
	return '';
}

/** Whether a 404 request is a page of the previous site that is gone for good. */
function vitalstack_old_url_is_gone( $path ) {
	// phpcs:disable WordPress.Security.NonceVerification -- read-only checks of the URL
	if ( isset( $_GET['subcat'] ) || isset( $_GET['news_cat'] ) || ( isset( $_GET['post_type'], $_GET['p'] ) ) ) {
		return true;
	}
	// phpcs:enable
	return (bool) preg_match(
		'#^/(tag|category/health-digital-wellness|news-category|author)/'   // old tags, health section, news categories, deleted authors
		. '|lorem-ipsum'                                                  // test pages
		. '|-/?$'                                                         // links cut off mid-word ("/how-screen-time-")
		. '|^/wp-content/themes/vitalstack_v2_updated/'                   // the old theme folder
		. '|^/[a-z0-9-]+/?$#',                                            // old single posts at the root that no longer exist
		$path
	);
}

/** Redirect or 410 for old URLs. Runs before the theme's other 404 helpers. */
function vitalstack_old_urls() {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}
	$path   = vitalstack_old_url_path();
	$target = is_404() || 0 === strpos( $path, '/news-page' ) ? vitalstack_old_url_target( $path ) : '';
	if ( $target ) {
		wp_safe_redirect( $target, 301 );
		exit;
	}
	if ( is_404() && vitalstack_old_url_is_gone( $path ) ) {
		status_header( 410 );
		nocache_headers();
	}
}
add_action( 'template_redirect', 'vitalstack_old_urls', 0 );

/** Feeds stay for feed readers but are not search results. */
function vitalstack_feed_noindex() {
	if ( is_feed() && ! headers_sent() ) {
		header( 'X-Robots-Tag: noindex, follow', true );
	}
}
add_action( 'template_redirect', 'vitalstack_feed_noindex', 1 );

/** Date archives (/2026/03/) only repeat posts: noindex, follow. */
function vitalstack_date_noindex( $robots ) {
	if ( is_date() && ! ( function_exists( 'vitalstack_seo_plugin_active' ) && vitalstack_seo_plugin_active() ) ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
		unset( $robots['index'] );
	}
	return $robots;
}
add_filter( 'wp_robots', 'vitalstack_date_noindex' );
