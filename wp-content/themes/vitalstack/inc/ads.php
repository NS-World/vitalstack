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
	if ( ! $client || is_admin() || vitalstack_is_account_page() || get_query_var( 'vs_cert' ) || is_404() || is_search() ) {
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

/* ── Manual ad slots ──────────────────────────────────────────────────────
 * Optional. With only the publisher ID set, Google's Auto ads choose the
 * places. Add ad unit IDs (AdSense → Ads → By ad unit) to also show ads in
 * fixed, reader-friendly spots: inside articles after the 2nd heading, at
 * the end of articles, and in the sticky sidebar next to lessons.
 * Never on sign-in, account, certificate, quiz or game areas.
 */

function vitalstack_ads_slot_customizer( $wp_customize ) {
	$fields = array(
		'vitalstack_ad_slot_article' => __( 'In-article ad unit ID (optional)', 'vitalstack' ),
		'vitalstack_ad_slot_display' => __( 'Display ad unit ID (optional)', 'vitalstack' ),
	);
	foreach ( $fields as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => '',
				'sanitize_callback' => function ( $v ) {
					return preg_match( '/^\d{6,12}$/', trim( (string) $v ) ) ? trim( (string) $v ) : '';
				},
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'       => $label,
				'description' => __( 'The number from data-ad-slot="…" in the ad code. Leave empty to rely on Auto ads.', 'vitalstack' ),
				'section'     => 'vitalstack_ads',
				'type'        => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'vitalstack_ads_slot_customizer', 20 );

/**
 * Whether manual ads may appear on the current page at all.
 */
function vitalstack_ads_allowed() {
	if ( ! vitalstack_adsense_client() || is_admin() || is_404() || is_search() || is_preview() ) {
		return false;
	}
	if ( vitalstack_is_account_page() || get_query_var( 'vs_cert' ) ) {
		return false;
	}
	if ( is_page_template( array( 'page-games.php', 'page-daily-challenge.php', 'page-mock-test.php' ) ) ) {
		return false;
	}
	return true;
}

/**
 * One ad unit's markup.
 *
 * @param string $type article|display.
 * @param string $where Extra class for placement.
 */
function vitalstack_ad_unit( $type, $where = '' ) {
	if ( ! vitalstack_ads_allowed() ) {
		return '';
	}
	$slot = get_theme_mod( 'article' === $type ? 'vitalstack_ad_slot_article' : 'vitalstack_ad_slot_display', '' );
	if ( ! $slot ) {
		return '';
	}
	$attrs = 'article' === $type
		? 'data-ad-layout="in-article" data-ad-format="fluid" style="display:block;text-align:center"'
		: 'data-ad-format="auto" data-full-width-responsive="true" style="display:block"';
	return '<aside class="ad-slot ad-' . esc_attr( $type ) . ( $where ? ' ad-' . esc_attr( $where ) : '' ) . '" aria-label="' . esc_attr__( 'Advertisement', 'vitalstack' ) . '">'
		. '<span class="ad-label">' . esc_html__( 'Advertisement', 'vitalstack' ) . '</span>'
		. '<ins class="adsbygoogle" ' . $attrs . ' data-ad-client="' . esc_attr( vitalstack_adsense_client() ) . '" data-ad-slot="' . esc_attr( $slot ) . '"></ins>'
		. '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>'
		. '</aside>';
}

/**
 * In-article ad after the 2nd heading and a display ad at the end of long
 * lessons and guides (the main article only).
 */
function vitalstack_ads_in_content( $content ) {
	if ( ! is_singular( array( 'post', 'tutorials' ) ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$article = vitalstack_ad_unit( 'article', 'inline' );
	if ( $article ) {
		$count   = 0;
		$content = preg_replace_callback(
			'/<h2\b/i',
			function ( $m ) use ( &$count, $article ) {
				$count++;
				return 3 === $count ? $article . $m[0] : $m[0];
			},
			$content
		);
	}
	if ( str_word_count( wp_strip_all_tags( $content ) ) > 700 ) {
		$content .= vitalstack_ad_unit( 'display', 'end' );
	}
	return $content;
}
add_filter( 'the_content', 'vitalstack_ads_in_content', 30 );

/**
 * Sticky ad under the "On this page" column (wide screens only).
 */
function vitalstack_ad_sidebar() {
	echo vitalstack_ad_unit( 'display', 'side' ); // phpcs:ignore WordPress.Security.EscapeOutput -- built with escaping
}
