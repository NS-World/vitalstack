<?php
/**
 * Speed: trim WordPress extras the site doesn't use, and keep page-cache
 * plugins away from pages with personal forms.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Remove emoji scripts (browsers draw emoji natively) and unused head links.
 */
function vitalstack_trim_head() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'wp_generator' );
}
add_action( 'init', 'vitalstack_trim_head' );

/**
 * Pages with sign-in, sign-up, subscribe or contact forms carry security tokens
 * that expire, so they must never be served from a page cache
 * (LiteSpeed Cache, WP Super Cache, W3 Total Cache and others respect these).
 */
function vitalstack_no_cache_for_forms() {
	$is_form_page = ( function_exists( 'vitalstack_is_account_page' ) && vitalstack_is_account_page() )
		|| is_page_template( 'page-contact.php' )
		|| is_page( array( 'contact', 'contact-us' ) );
	if ( ! $is_form_page ) {
		return;
	}
	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true );
	}
	nocache_headers();
	header( 'X-LiteSpeed-Cache-Control: no-cache' );
}
add_action( 'template_redirect', 'vitalstack_no_cache_for_forms', 0 );
