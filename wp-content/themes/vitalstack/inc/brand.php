<?php
/**
 * Brand assets: favicon, app icons, web manifest and a default share image.
 *
 * Favicons are printed only while no Site Icon is set in Appearance → Customize →
 * Site Identity, so an uploaded icon always wins. Open Graph tags are printed only
 * when no SEO plugin handles them.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_brand_url( $file ) {
	return VITALSTACK_URI . '/assets/brand/' . $file;
}

function vitalstack_brand_icons() {
	if ( ! has_site_icon() ) {
		printf( '<link rel="icon" href="%s" sizes="any">' . "\n", esc_url( vitalstack_brand_url( 'favicon.ico' ) ) );
		printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( vitalstack_brand_url( 'favicon.svg' ) ) );
		printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( vitalstack_brand_url( 'apple-touch-icon.png' ) ) );
	}
	printf( '<link rel="manifest" href="%s">' . "\n", esc_url( vitalstack_brand_url( 'site.webmanifest' ) ) );
	echo '<meta name="theme-color" content="#0a7d4f">' . "\n";
}
add_action( 'wp_head', 'vitalstack_brand_icons', 3 );
add_action( 'login_head', 'vitalstack_brand_icons' );
add_action( 'admin_head', 'vitalstack_brand_icons' );

/**
 * True when an SEO plugin prints its own Open Graph tags.
 */
function vitalstack_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

/**
 * Basic Open Graph / Twitter tags so shared links show a title and an image.
 */
function vitalstack_open_graph() {
	if ( vitalstack_seo_plugin_active() || is_404() ) {
		return;
	}
	$title = wp_get_document_title();
	$desc  = get_bloginfo( 'description' );
	$url   = home_url( add_query_arg( array() ) );
	$image = vitalstack_brand_url( 'share.png' );
	$type  = 'website';
	if ( is_singular() ) {
		$post  = get_queried_object();
		$url   = get_permalink( $post );
		$type  = 'article';
		$desc  = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 30, '…' );
		if ( has_post_thumbnail( $post ) ) {
			$image = get_the_post_thumbnail_url( $post, 'large' );
		}
	}
	$tags = array(
		'og:site_name'   => get_bloginfo( 'name' ),
		'og:type'        => $type,
		'og:title'       => $title,
		'og:description' => $desc,
		'og:url'         => $url,
		'og:image'       => $image,
	);
	foreach ( $tags as $property => $content ) {
		if ( $content ) {
			printf( '<meta property="%s" content="%s">' . "\n", esc_attr( $property ), esc_attr( $content ) );
		}
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}
add_action( 'wp_head', 'vitalstack_open_graph', 5 );

/**
 * Yoast: use the share image when a page has no image of its own.
 */
function vitalstack_yoast_default_image( $image ) {
	return $image ? $image : vitalstack_brand_url( 'share.png' );
}
add_filter( 'wpseo_opengraph_image', 'vitalstack_yoast_default_image' );
add_filter( 'wpseo_twitter_image', 'vitalstack_yoast_default_image' );

/**
 * Login screen: show the VitalStack mark instead of the WordPress logo.
 */
function vitalstack_login_logo() {
	?>
	<style>
		#login h1 a { background-image: url(<?php echo esc_url( vitalstack_brand_url( 'logo-mark.svg' ) ); ?>); background-size: 72px; width: 72px; height: 72px; }
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'vitalstack_login_logo' );
add_filter( 'login_headerurl', function () { return home_url( '/' ); } );
add_filter( 'login_headertext', function () { return get_bloginfo( 'name' ); } );
