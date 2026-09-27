<?php
/**
 * Search-engine related behaviour. Titles, descriptions and schema are left
 * to Yoast SEO; the theme only adds what Yoast cannot know.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vitalstack_news_is_noindex() {
	return (bool) get_theme_mod( 'vitalstack_noindex_news', false );
}

function vitalstack_is_news_view() {
	return is_singular( 'news' ) || is_post_type_archive( 'news' ) || is_tax( 'news_category' ) || is_page_template( 'page-news.php' );
}

/**
 * noindex,follow for the News section when enabled in the Customizer.
 * Covers both core robots output and Yoast (which replaces it).
 */
function vitalstack_robots_core( $robots ) {
	if ( vitalstack_news_is_noindex() && vitalstack_is_news_view() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
		unset( $robots['index'] );
	}
	return $robots;
}
add_filter( 'wp_robots', 'vitalstack_robots_core' );

function vitalstack_robots_yoast( $robots ) {
	if ( vitalstack_news_is_noindex() && vitalstack_is_news_view() ) {
		return 'noindex, follow';
	}
	return $robots;
}
add_filter( 'wpseo_robots', 'vitalstack_robots_yoast' );

/**
 * Keep noindexed news out of the XML sitemaps too.
 */
function vitalstack_sitemap_exclude_news( $excluded, $post_type ) {
	return ( 'news' === $post_type && vitalstack_news_is_noindex() ) ? true : $excluded;
}
add_filter( 'wpseo_sitemap_exclude_post_type', 'vitalstack_sitemap_exclude_news', 10, 2 );

function vitalstack_sitemap_exclude_news_tax( $excluded, $taxonomy ) {
	return ( 'news_category' === $taxonomy && vitalstack_news_is_noindex() ) ? true : $excluded;
}
add_filter( 'wpseo_sitemap_exclude_taxonomy', 'vitalstack_sitemap_exclude_news_tax', 10, 2 );

function vitalstack_core_sitemap_exclude_news( $post_types ) {
	if ( vitalstack_news_is_noindex() ) {
		unset( $post_types['news'] );
	}
	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'vitalstack_core_sitemap_exclude_news' );

/**
 * Minimal meta description when Yoast is not active.
 */
function vitalstack_fallback_meta_description() {
	if ( defined( 'WPSEO_VERSION' ) ) {
		return;
	}
	if ( is_front_page() ) {
		$desc = get_bloginfo( 'description' ) ?: vitalstack_default( 'home_desc' );
	} elseif ( is_singular() ) {
		$desc = get_the_excerpt( get_queried_object_id() );
	} else {
		return;
	}
	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( wp_trim_words( wp_strip_all_tags( $desc ), 30, '…' ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'vitalstack_fallback_meta_description', 2 );
