<?php
/**
 * AdSense readiness notice (5.0): tells admins, on the Dashboard and Pages
 * screens, which pages AdSense reviewers expect are missing, still drafts, or
 * still contain "[your …]" placeholders, with a link to each one.
 *
 * Legal pages are created as drafts on purpose (the owner should read them
 * before publishing); this notice only reminds, it never publishes anything.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/**
 * slug => title of the pages AdSense reviewers look for.
 */
function vitalstack_adsense_required_pages() {
	return array(
		'about-us'         => __( 'About Us', 'vitalstack' ),
		'contact-us'       => __( 'Contact Us', 'vitalstack' ),
		'privacy-policy'   => __( 'Privacy Policy', 'vitalstack' ),
		'terms-of-service' => __( 'Terms of Service', 'vitalstack' ),
		'disclaimer'       => __( 'Disclaimer', 'vitalstack' ),
		'editorial-policy' => __( 'Editorial Policy', 'vitalstack' ),
		'cookie-policy'    => __( 'Cookie Policy', 'vitalstack' ),
	);
}

/**
 * Problems found: list of array( title, problem, edit URL or '' ).
 */
function vitalstack_adsense_page_problems() {
	$out = array();
	foreach ( vitalstack_adsense_required_pages() as $slug => $title ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( ! $page || 'trash' === $page->post_status ) {
			$out[] = array( $title, __( 'missing', 'vitalstack' ), '' );
			continue;
		}
		$edit = (string) get_edit_post_link( $page->ID, 'raw' );
		if ( 'publish' !== $page->post_status ) {
			$out[] = array( $title, __( 'not published yet', 'vitalstack' ), $edit );
		} elseif ( preg_match( '/\[(your|one honest line)[^\]]*\]/i', (string) $page->post_content ) ) {
			$out[] = array( $title, __( 'still has a [your …] placeholder', 'vitalstack' ), $edit );
		}
	}
	return $out;
}

function vitalstack_adsense_readiness_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && ! in_array( $screen->id, array( 'dashboard', 'edit-page' ), true ) ) {
		return;
	}
	$problems = vitalstack_adsense_page_problems();
	if ( ! $problems ) {
		return;
	}
	echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'AdSense readiness:', 'vitalstack' ) . '</strong> ' . esc_html__( 'reviewers expect these pages to be published and complete.', 'vitalstack' ) . '</p><ul style="list-style:disc;margin-left:20px">';
	foreach ( $problems as $p ) {
		echo '<li>' . ( $p[2] ? '<a href="' . esc_url( $p[2] ) . '">' . esc_html( $p[0] ) . '</a>' : esc_html( $p[0] ) ) . ': ' . esc_html( $p[1] ) . '</li>';
	}
	echo '</ul>';
	if ( array_filter( $problems, function ( $p ) { return '' === $p[2]; } ) ) {
		echo '<p>' . esc_html__( 'Missing pages are created by Appearance → VitalStack Setup.', 'vitalstack' ) . '</p>';
	}
	echo '</div>';
}
add_action( 'admin_notices', 'vitalstack_adsense_readiness_notice' );
