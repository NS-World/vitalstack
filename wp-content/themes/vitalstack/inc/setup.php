<?php
/**
 * Theme supports, menus, assets.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vitalstack_setup() {
	load_theme_textdomain( 'vitalstack', VITALSTACK_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 64,
			'width'       => 240,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	add_image_size( 'vitalstack-hero', 1200, 675, true );
	add_image_size( 'vitalstack-card', 800, 450, true );

	// Menu locations are unchanged from v1 so existing menu assignments survive.
	register_nav_menus(
		array(
			'primary'        => __( 'Primary Navigation', 'vitalstack' ),
			'footer-cats'    => __( 'Footer — Topics', 'vitalstack' ),
			'footer-company' => __( 'Footer — Company', 'vitalstack' ),
			'footer-legal'   => __( 'Footer — Legal', 'vitalstack' ),
		)
	);

	$GLOBALS['content_width'] = 760;
}
add_action( 'after_setup_theme', 'vitalstack_setup' );

function vitalstack_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Article Sidebar (below contents)', 'vitalstack' ),
			'id'            => 'sidebar-single',
			'description'   => __( 'Optional widgets under the table of contents on articles.', 'vitalstack' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'vitalstack_widgets_init' );

/**
 * URL of a theme asset, preferring its minified copy in assets/build/ while the
 * source still matches the hash recorded by tools/build_assets.py.
 *
 * @param string $source Path relative to the theme, e.g. "assets/js/main.js".
 */
function vitalstack_asset_url( $source ) {
	static $manifest = null;
	if ( null === $manifest ) {
		$file     = VITALSTACK_DIR . '/assets/build/manifest.json';
		$manifest = file_exists( $file ) ? (array) json_decode( (string) file_get_contents( $file ), true ) : array(); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}
	$name = 'style.css' === $source ? 'style.min.css' : preg_replace( '/\.js$/', '.min.js', basename( $source ) );
	if ( ! ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG )
		&& isset( $manifest[ $source ] )
		&& file_exists( VITALSTACK_DIR . '/assets/build/' . $name )
		&& md5_file( VITALSTACK_DIR . '/' . $source ) === $manifest[ $source ] ) {
		return VITALSTACK_URI . '/assets/build/' . $name;
	}
	return VITALSTACK_URI . '/' . $source;
}

/**
 * Inter, served from the theme (one 28 KB variable file, weights 400–800),
 * so pages don't wait on two extra servers for fonts.
 */
function vitalstack_font_face() {
	$font = VITALSTACK_URI . '/assets/fonts/inter-latin-var.woff2';
	printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( $font ) );
	printf( "<style>@font-face{font-family:Inter;font-style:normal;font-weight:400 800;font-display:swap;src:url(%s) format('woff2')}</style>\n", esc_url( $font ) );
}
add_action( 'wp_head', 'vitalstack_font_face', 1 );

/**
 * Front-end assets. One stylesheet, deferred scripts, and a syntax
 * highlighter only on pages that actually contain code.
 */
function vitalstack_assets() {
	wp_enqueue_style( 'vitalstack-style', vitalstack_asset_url( 'style.css' ), array(), VITALSTACK_VERSION );

	// Syntax highlighting only where there is code; loaded before main.js so
	// code blocks are coloured on first run. Colours come from style.css.
	$main_deps = array();
	// Cheat sheets and tool guides keep their code in theme files, not post content.
	if ( is_front_page() || is_page_template( array( 'page-cheat-sheet.php', 'page-tool.php' ) ) || ( is_singular() && vitalstack_post_has_code() ) ) {
		wp_enqueue_script(
			'vitalstack-hljs',
			VITALSTACK_URI . '/assets/vendor/highlight.min.js',
			array(),
			'11.9.0',
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
		$main_deps[] = 'vitalstack-hljs';
	}

	wp_enqueue_script(
		'vitalstack-main',
		vitalstack_asset_url( 'assets/js/main.js' ),
		$main_deps,
		VITALSTACK_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	$data = array(
		'loggedIn'   => is_user_logged_in(),
		'accountUrl' => vitalstack_account_url(),
	);
	if ( is_user_logged_in() ) {
		$data['restUrl']   = esc_url_raw( rest_url( 'vitalstack/v1/progress' ) );
		$data['restNonce'] = wp_create_nonce( 'wp_rest' );
	}
	wp_localize_script( 'vitalstack-main', 'vitalstackData', apply_filters( 'vitalstack_script_data', $data ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'vitalstack_assets' );

function vitalstack_post_has_code() {
	$content = get_post_field( 'post_content', get_queried_object_id() );
	return false !== strpos( $content, '<pre' ) || false !== strpos( $content, '<code' );
}


function vitalstack_excerpt_length() {
	return 26;
}
add_filter( 'excerpt_length', 'vitalstack_excerpt_length' );

function vitalstack_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'vitalstack_excerpt_more' );

function vitalstack_body_classes( $classes ) {
	if ( is_singular( array( 'post', 'news', 'tutorials' ) ) ) {
		$classes[] = 'is-article';
	}
	return $classes;
}
add_filter( 'body_class', 'vitalstack_body_classes' );

/**
 * Include tutorials in search and in the main feed alongside posts.
 */
function vitalstack_search_post_types( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_search() ) {
		$types = array( 'post', 'tutorials', 'page' );
		if ( ! get_theme_mod( 'vitalstack_hide_news', false ) ) {
			$types[] = 'news';
		}
		$query->set( 'post_type', $types );
	}
}
add_action( 'pre_get_posts', 'vitalstack_search_post_types' );

// "Category: Technology" → "Technology".
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );
