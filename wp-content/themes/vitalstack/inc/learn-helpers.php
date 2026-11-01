<?php
/**
 * Beginner helpers for lessons (4.12):
 *  - lesson films (::scene) are shown as an illustrated story strip with Nova,
 *    unless the old film player is switched back on in the Customizer;
 *  - the "3D view" button of walkthroughs is hidden unless switched back on;
 *  - "Explain this code": what every import is for and what each line does;
 *  - AI words in the lesson text explain themselves on tap (AI Dictionary).
 * The line-by-line walkthroughs and the interactive animations are unchanged.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vitalstack_films_on() {
	return (bool) get_theme_mod( 'vitalstack_lesson_films', false );
}

function vitalstack_code_3d_on() {
	return (bool) get_theme_mod( 'vitalstack_code_3d', false );
}

function vitalstack_helpers_on() {
	return (bool) get_theme_mod( 'vitalstack_beginner_helpers', true );
}

function vitalstack_learn_customizer( $wp_customize ) {
	$settings = array(
		'vitalstack_beginner_helpers' => array( true, __( 'Beginner helpers in lessons: story strips with Nova, "Explain this code" (what every import and line does) and tap-to-explain AI words', 'vitalstack' ) ),
		'vitalstack_lesson_films'     => array( false, __( 'Old lesson film player (4.10). Off: lesson films show as illustrated story strips instead', 'vitalstack' ) ),
		'vitalstack_code_3d'          => array( false, __( 'Old "3D view" button in code walkthroughs (4.9)', 'vitalstack' ) ),
	);
	foreach ( $settings as $id => $def ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $def[0],
				'sanitize_callback' => 'wp_validate_boolean',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $def[1],
				'section' => 'vitalstack_layout',
				'type'    => 'checkbox',
			)
		);
	}
}
add_action( 'customize_register', 'vitalstack_learn_customizer', 30 );

function vitalstack_learn_body_class( $classes ) {
	if ( ! vitalstack_films_on() ) {
		$classes[] = 'vs-no-films';
	}
	if ( ! vitalstack_code_3d_on() ) {
		$classes[] = 'vs-no-3d';
	}
	return $classes;
}
add_filter( 'body_class', 'vitalstack_learn_body_class' );

/**
 * Lesson-like pages: a single tutorial or post with content.
 */
function vitalstack_learn_page() {
	return is_singular( array( 'tutorials', 'post' ) );
}

function vitalstack_learn_assets() {
	if ( ! vitalstack_films_on() ) {
		// The story strip in learn.js replaces the film player.
		wp_dequeue_script( 'vitalstack-scene' );
	}
	if ( ! vitalstack_learn_page() || ! vitalstack_helpers_on() ) {
		return;
	}
	wp_enqueue_script( 'vitalstack-nova', vitalstack_asset_url( 'assets/js/nova.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_enqueue_script( 'vitalstack-learn', vitalstack_asset_url( 'assets/js/learn.js' ), array( 'vitalstack-nova' ), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	$terms = array();
	if ( function_exists( 'vitalstack_ai_terms' ) ) {
		foreach ( vitalstack_ai_terms() as $slug => $t ) {
			$terms[] = array( $slug, $t['term'], $t['simple'], $t['emoji'] );
		}
	}
	wp_localize_script(
		'vitalstack-learn',
		'vitalstackLearn',
		array(
			'films'   => vitalstack_films_on(),
			'dictUrl' => function_exists( 'vitalstack_ai_dictionary_url' ) ? vitalstack_ai_dictionary_url() : '',
			'terms'   => $terms,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'vitalstack_learn_assets', 99 );
