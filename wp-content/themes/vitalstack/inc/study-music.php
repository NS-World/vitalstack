<?php
/**
 * Study Music page (5.0): lo-fi beats, rain and calm pads made live in the
 * browser (assets/js/study-music.js) with a Pomodoro timer.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_study_music_url() {
	$page = get_page_by_path( 'study-music' );
	return ( $page && 'publish' === $page->post_status ) ? get_permalink( $page ) : '';
}

function vitalstack_study_music_assets() {
	if ( is_page_template( 'page-study-music.php' ) ) {
		$deps = wp_script_is( 'vitalstack-fun', 'registered' ) ? array( 'vitalstack-fun' ) : array();
		wp_enqueue_script( 'vitalstack-study-music', vitalstack_asset_url( 'assets/js/study-music.js' ), $deps, VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'vitalstack_study_music_assets', 31 );
