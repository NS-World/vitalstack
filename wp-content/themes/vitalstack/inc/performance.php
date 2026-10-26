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

/* ── Comments: off by default (spam hurts quality reviews), one switch to enable ── */

function vitalstack_comments_customizer( $wp_customize ) {
	$wp_customize->add_setting(
		'vitalstack_comments_enabled',
		array(
			'default'           => false,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);
	$wp_customize->add_control(
		'vitalstack_comments_enabled',
		array(
			'label'       => __( 'Allow comments and questions on lessons and guides', 'vitalstack' ),
			'description' => __( 'Turn on once you can reply and moderate (install Akismet against spam). Comments always need your approval first.', 'vitalstack' ),
			'section'     => 'vitalstack_members',
			'type'        => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'vitalstack_comments_customizer', 20 );

function vitalstack_comments_enabled() {
	return (bool) get_theme_mod( 'vitalstack_comments_enabled', false );
}

add_filter(
	'comments_open',
	function ( $open, $post_id ) {
		if ( ! vitalstack_comments_enabled() ) {
			return false;
		}
		return in_array( get_post_type( $post_id ), array( 'post', 'tutorials' ), true ) ? true : $open;
	},
	20,
	2
);
add_filter( 'pings_open', '__return_false', 20 );
add_filter(
	'get_comments_number',
	function ( $count ) {
		return vitalstack_comments_enabled() ? $count : 0;
	},
	20
);

/**
 * When comments are on, every new comment waits for approval.
 */
add_filter(
	'pre_comment_approved',
	function ( $approved ) {
		if ( 'spam' === $approved || 'trash' === $approved || current_user_can( 'moderate_comments' ) ) {
			return $approved;
		}
		return 0;
	},
	20
);

/**
 * After a theme update, cached pages still carry the old header and footer.
 * The first admin request on a new theme version clears every page cache we
 * know how to reach (LiteSpeed Cache, WP Super Cache, W3 Total Cache, WP
 * Rocket, plus WordPress's object cache), so visitors get the new pages.
 */
function vitalstack_purge_caches_on_update() {
	if ( VITALSTACK_VERSION === get_option( 'vitalstack_cache_version' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	update_option( 'vitalstack_cache_version', VITALSTACK_VERSION, false );
	do_action( 'litespeed_purge_all' );
	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
	}
	if ( function_exists( 'w3tc_flush_all' ) ) {
		w3tc_flush_all();
	}
	if ( function_exists( 'rocket_clean_domain' ) ) {
		rocket_clean_domain();
	}
	wp_cache_flush();
	delete_transient( 'vs_path_lessons' );
	set_transient( 'vitalstack_cache_purged_' . get_current_user_id(), VITALSTACK_VERSION, HOUR_IN_SECONDS );
}
add_action( 'admin_init', 'vitalstack_purge_caches_on_update' );

/**
 * Tell the admin once that the cache was cleared after the update.
 */
function vitalstack_cache_purged_notice() {
	$version = get_transient( 'vitalstack_cache_purged_' . get_current_user_id() );
	if ( ! $version ) {
		return;
	}
	delete_transient( 'vitalstack_cache_purged_' . get_current_user_id() );
	/* translators: %s: theme version */
	echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( sprintf( __( 'VitalStack %s is active and the page cache was cleared, so visitors see the new version. If you use the Hostinger CDN, also purge it once (hPanel → Performance → CDN).', 'vitalstack' ), $version ) ) . '</p></div>';
}
add_action( 'admin_notices', 'vitalstack_cache_purged_notice' );
