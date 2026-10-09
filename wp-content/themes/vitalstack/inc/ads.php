<?php
/**
 * Google AdSense: the Auto ads / site verification snippet and ads.txt,
 * driven by one Customizer field (the publisher ID, ca-pub-…).
 *
 * Leave the field empty if Site Kit or another plugin already adds the
 * AdSense code, so it is never loaded twice.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The saved publisher ID as "ca-pub-1234567890123456", or '' if unset/invalid.
 */
function vitalstack_adsense_client() {
	$id = trim( (string) get_theme_mod( 'vitalstack_adsense_client', '' ) );
	if ( preg_match( '/^(?:ca-)?pub-(\d{10,20})$/', $id, $m ) ) {
		return 'ca-pub-' . $m[1];
	}
	return '';
}

function vitalstack_sanitize_adsense_client( $value ) {
	$value = trim( (string) $value );
	return preg_match( '/^(?:ca-)?pub-\d{10,20}$/', $value ) ? $value : '';
}

function vitalstack_adsense_customizer( $wp_customize ) {
	$wp_customize->add_section(
		'vitalstack_ads',
		array(
			'title'       => __( 'VitalStack: Google AdSense', 'vitalstack' ),
			'priority'    => 34,
			'description' => __( 'Paste your publisher ID (ca-pub-…) from AdSense → Account → Settings. The theme then adds the AdSense code to every page and serves /ads.txt. Leave empty if Site Kit already places the code.', 'vitalstack' ),
		)
	);
	$wp_customize->add_setting(
		'vitalstack_adsense_client',
		array(
			'default'           => '',
			'sanitize_callback' => 'vitalstack_sanitize_adsense_client',
		)
	);
	$wp_customize->add_control(
		'vitalstack_adsense_client',
		array(
			'label'       => __( 'AdSense publisher ID', 'vitalstack' ),
			'section'     => 'vitalstack_ads',
			'type'        => 'text',
			'input_attrs' => array( 'placeholder' => 'ca-pub-1234567890123456' ),
		)
	);
}
add_action( 'customize_register', 'vitalstack_adsense_customizer' );

/**
 * Auto ads / verification snippet in <head>. Not on the account pages:
 * sign-in and dashboard screens have no publisher content for ads.
 */
function vitalstack_adsense_head() {
	$client = vitalstack_adsense_client();
	if ( ! $client || is_admin() || vitalstack_is_account_page() || is_404() || is_search() ) {
		return;
	}
	printf(
		'<script async src="%s" crossorigin="anonymous"></script>' . "\n",
		esc_url( 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . $client )
	);
}
add_action( 'wp_head', 'vitalstack_adsense_head', 5 );

/**
 * Serve /ads.txt when no physical ads.txt file exists (a real file in the
 * site root always wins because the web server answers it first).
 */
function vitalstack_serve_ads_txt() {
	$client = vitalstack_adsense_client();
	if ( ! $client ) {
		return;
	}
	$path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';
	$home = wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	if ( rtrim( (string) $home, '/' ) . '/ads.txt' !== $path ) {
		return;
	}
	status_header( 200 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'Cache-Control: public, max-age=86400' );
	echo 'google.com, ' . esc_html( substr( $client, 3 ) ) . ', DIRECT, f08c47fec0942fa0' . "\n";
	exit;
}
add_action( 'init', 'vitalstack_serve_ads_txt', 0 );
