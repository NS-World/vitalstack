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
 * Front-end assets. One stylesheet, one deferred script, and a syntax
 * highlighter only on pages that actually contain code.
 */
function vitalstack_assets() {
	wp_enqueue_style(
		'vitalstack-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Source+Code+Pro:wght@400;600&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'vitalstack-style', get_stylesheet_uri(), array( 'vitalstack-fonts' ), VITALSTACK_VERSION );

	wp_enqueue_script(
		'vitalstack-main',
		VITALSTACK_URI . '/assets/js/main.js',
		array(),
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
	wp_localize_script( 'vitalstack-main', 'vitalstackData', $data );

	// Syntax highlighting only where there is code. Colours come from style.css.
	if ( is_front_page() || ( is_singular() && vitalstack_post_has_code() ) ) {
		wp_enqueue_script(
			'vitalstack-hljs',
			'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js',
			array(),
			null,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'vitalstack_assets' );

function vitalstack_post_has_code() {
	$content = get_post_field( 'post_content', get_queried_object_id() );
	return false !== strpos( $content, '<pre' ) || false !== strpos( $content, '<code' );
}

function vitalstack_resource_hints( $hints, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$hints[] = array( 'href' => 'https://fonts.googleapis.com' );
		$hints[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'vitalstack_resource_hints', 10, 2 );

/**
 * Apply the saved colour scheme before first paint so dark mode never flashes.
 */
function vitalstack_theme_bootstrap_script() {
	?>
	<script>
	(function(){try{var t=localStorage.getItem('vs-theme');if(t==='dark'||t==='light'){document.documentElement.setAttribute('data-theme',t);}}catch(e){}})();
	</script>
	<?php
}
add_action( 'wp_head', 'vitalstack_theme_bootstrap_script', 0 );

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
