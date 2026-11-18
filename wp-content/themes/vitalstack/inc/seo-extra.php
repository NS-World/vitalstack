<?php
/**
 * 4.20.6: two small search fixes found in an on-page audit of every sitemap URL.
 *
 * - The 51 interview problem lessons get a search-friendly page title that says
 *   what people search for ("Two Sum: Python, Java, C++ & JS Solution"). Their
 *   plain title was the same as the concept lesson ("Binary Search" twice).
 * - A learning-path archive that has a landing page with the same address
 *   (/tutorial-category/dsa-problems/ and /dsa-problems/) points Google to the
 *   landing page and leaves the sitemap, so the two don't compete as duplicates.
 *
 * Nothing on the pages themselves changes.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/** Interview problem lessons: keyword-rich <title>. */
function vitalstack_seo_problem_title( $parts ) {
	if ( ! is_singular( 'tutorials' ) || ( function_exists( 'vitalstack_seo_plugin_active' ) && vitalstack_seo_plugin_active() ) ) {
		return $parts;
	}
	$post = get_queried_object();
	if ( $post instanceof WP_Post && 0 === strpos( $post->post_name, 'problem-' ) && ! empty( $parts['title'] ) ) {
		/* translators: %s: problem name, e.g. Two Sum */
		$parts['title'] = sprintf( __( '%s: Python, Java, C++ & JS Solution', 'vitalstack' ), $parts['title'] );
	}
	return $parts;
}
add_filter( 'document_title_parts', 'vitalstack_seo_problem_title', 20 );

/** The landing page that shares a learning path's slug, or null. */
function vitalstack_seo_path_landing( $term ) {
	if ( ! $term instanceof WP_Term || 'tutorial_category' !== $term->taxonomy ) {
		return null;
	}
	$page = get_page_by_path( $term->slug );
	return ( $page && 'publish' === $page->post_status ) ? $page : null;
}

/** Canonical of such an archive = the landing page (replaces the archive's own canonical). */
function vitalstack_seo_path_canonical() {
	if ( ! is_tax( 'tutorial_category' ) || is_paged() || ( function_exists( 'vitalstack_seo_plugin_active' ) && vitalstack_seo_plugin_active() ) ) {
		return;
	}
	$page = vitalstack_seo_path_landing( get_queried_object() );
	if ( ! $page ) {
		return;
	}
	remove_action( 'wp_head', 'vitalstack_archive_canonical', 3 );
	echo '<link rel="canonical" href="' . esc_url( get_permalink( $page ) ) . '">' . "\n";
}
add_action( 'wp_head', 'vitalstack_seo_path_canonical', 1 );

/** Keep those archives out of the sitemap (the landing page is in it). */
function vitalstack_seo_sitemap_skip_landing_paths( $args, $taxonomy ) {
	if ( 'tutorial_category' !== $taxonomy ) {
		return $args;
	}
	$skip = array();
	foreach ( get_terms( array( 'taxonomy' => 'tutorial_category', 'hide_empty' => false, 'fields' => 'id=>slug' ) ) as $id => $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page && 'publish' === $page->post_status ) {
			$skip[] = (int) $id;
		}
	}
	if ( $skip ) {
		$args['exclude'] = array_merge( isset( $args['exclude'] ) ? (array) $args['exclude'] : array(), $skip );
	}
	return $args;
}
add_filter( 'wp_sitemaps_taxonomies_query_args', 'vitalstack_seo_sitemap_skip_landing_paths', 10, 2 );
