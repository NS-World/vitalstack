<?php
/**
 * Site environment (5.0): PRODUCTION, UAT or DEV.
 *
 * Test copies of the site (UAT, DEV, Hostinger temporary domains) must never
 * be indexed by Google or show ads: a second copy of the same lessons is
 * duplicate content and can hurt the real site's search ranking and AdSense
 * review. On a test copy this file:
 *  - sends "noindex, nofollow" on every page and blocks crawlers in robots.txt;
 *  - removes the AdSense code and ad units;
 *  - shows a small "DEV · 5.0.0-dev" ribbon so testers know where they are.
 *
 * The environment comes from (first match wins):
 *  1. Customizer → Site identity → "This copy of the site is";
 *  2. WP_ENVIRONMENT_TYPE in wp-config.php (development/staging/local);
 *  3. the address: *.hostingersite.com, localhost, *.local, dev./staging./uat. hosts.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return string production|uat|dev
 */
function vitalstack_env() {
	static $env = null;
	if ( null !== $env ) {
		return $env;
	}
	$set = (string) get_theme_mod( 'vitalstack_env', 'auto' );
	if ( in_array( $set, array( 'production', 'uat', 'dev' ), true ) ) {
		return $env = $set;
	}
	$type = function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production';
	if ( 'development' === $type || 'local' === $type ) {
		return $env = 'dev';
	}
	if ( 'staging' === $type ) {
		return $env = 'uat';
	}
	$host = strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) );
	if ( preg_match( '/(^|\.)dev\.|(^|\.)localhost$|\.local$|\.test$/', $host ) ) {
		return $env = 'dev';
	}
	if ( preg_match( '/hostingersite\.com$|(^|\.)(staging|uat|stage)\.|\.preview\./', $host ) ) {
		return $env = 'uat';
	}
	return $env = 'production';
}

function vitalstack_is_staging() {
	return 'production' !== vitalstack_env();
}

function vitalstack_env_customizer( $wp_customize ) {
	$wp_customize->add_setting(
		'vitalstack_env',
		array(
			'default'           => 'auto',
			'sanitize_callback' => function ( $v ) {
				return in_array( $v, array( 'auto', 'production', 'uat', 'dev' ), true ) ? $v : 'auto';
			},
		)
	);
	$wp_customize->add_control(
		'vitalstack_env',
		array(
			'label'       => __( 'This copy of the site is', 'vitalstack' ),
			'description' => __( 'UAT and DEV copies are hidden from Google and show no ads, so they never compete with the real site. "Automatic" treats *.hostingersite.com, localhost and dev./staging. addresses as test copies.', 'vitalstack' ),
			'section'     => 'title_tagline',
			'type'        => 'select',
			'choices'     => array(
				'auto'       => __( 'Automatic', 'vitalstack' ),
				'production' => __( 'PRODUCTION (live site)', 'vitalstack' ),
				'uat'        => __( 'UAT (testing copy)', 'vitalstack' ),
				'dev'        => __( 'DEV (development copy)', 'vitalstack' ),
			),
		)
	);
}
add_action( 'customize_register', 'vitalstack_env_customizer', 40 );

/* ── Test copies stay out of Google and show no ads ───────────────────── */

function vitalstack_env_robots( $robots ) {
	if ( vitalstack_is_staging() ) {
		$robots            = array_diff_key( $robots, array_flip( array( 'index', 'follow', 'max-image-preview' ) ) );
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'vitalstack_env_robots', 999 );

function vitalstack_env_robots_txt( $output, $public ) {
	if ( vitalstack_is_staging() ) {
		return "# Test copy of VitalStack: not for search engines.\nUser-agent: *\nDisallow: /\n";
	}
	return $output;
}
add_filter( 'robots_txt', 'vitalstack_env_robots_txt', 999, 2 );

function vitalstack_env_headers() {
	if ( vitalstack_is_staging() && ! headers_sent() ) {
		header( 'X-Robots-Tag: noindex, nofollow', true );
	}
}
add_action( 'send_headers', 'vitalstack_env_headers' );

function vitalstack_env_no_ads() {
	if ( vitalstack_is_staging() ) {
		remove_action( 'wp_head', 'vitalstack_adsense_head', 5 );
	}
}
add_action( 'wp', 'vitalstack_env_no_ads' );

function vitalstack_env_sitemaps( $enabled ) {
	return vitalstack_is_staging() ? false : $enabled;
}
add_filter( 'wp_sitemaps_enabled', 'vitalstack_env_sitemaps' );

/* ── Ribbon ───────────────────────────────────────────────────────────── */

function vitalstack_env_ribbon() {
	if ( ! vitalstack_is_staging() ) {
		return;
	}
	$env = strtoupper( vitalstack_env() );
	echo '<div class="vs-env-ribbon" role="note"><strong>' . esc_html( $env ) . '</strong> · ' . esc_html( VITALSTACK_VERSION ) . ' · ' . esc_html__( 'test copy, hidden from Google, no ads', 'vitalstack' ) . '</div>';
}
add_action( 'wp_body_open', 'vitalstack_env_ribbon', 1 );

function vitalstack_env_admin_bar( $bar ) {
	if ( ! vitalstack_is_staging() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$bar->add_node(
		array(
			'id'    => 'vs-env',
			'title' => esc_html( strtoupper( vitalstack_env() ) . ' · ' . VITALSTACK_VERSION ),
			'href'  => admin_url( 'customize.php?autofocus[control]=vitalstack_env' ),
		)
	);
}
add_action( 'admin_bar_menu', 'vitalstack_env_admin_bar', 100 );

/**
 * Admin warning when practice checkout is still on in production.
 */
function vitalstack_env_launch_notice() {
	if ( ! current_user_can( 'manage_options' ) || vitalstack_is_staging() || ! function_exists( 'vitalstack_pay_settings' ) ) {
		return;
	}
	$s = vitalstack_pay_settings();
	if ( ! empty( $s['demo'] ) ) {
		echo '<div class="notice notice-error"><p><strong>VitalStack:</strong> ' . esc_html__( 'Practice checkout (demo payments) is still ON on the live site. Turn it off in Settings → VitalStack Payments before launch.', 'vitalstack' ) . '</p></div>';
	}
}
add_action( 'admin_notices', 'vitalstack_env_launch_notice' );
