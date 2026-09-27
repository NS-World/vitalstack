<?php
/**
 * VitalStack Theme Functions
 *
 * @package VitalStack
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ─────────────────────────────────────────────────────────────────────────────
   THEME SETUP
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_setup() {
    // Text domain
    load_theme_textdomain( 'vitalstack', get_template_directory() . '/languages' );

    // Automatic feed links
    add_theme_support( 'automatic-feed-links' );

    // Title tag managed by WordPress
    add_theme_support( 'title-tag' );

    // Post thumbnails / featured images
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'vitalstack-hero',    1200, 675, true );  // Hero / OG image
    add_image_size( 'vitalstack-card',     800, 500, true );  // Card thumbnails
    add_image_size( 'vitalstack-thumb',    400, 280, true );  // Related / grid thumbs
    add_image_size( 'vitalstack-avatar',    80,  80, true );  // Author avatars

    // HTML5 markup
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ) );

    // Customizer selective refresh
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Editor styles
    add_theme_support( 'editor-styles' );
    add_editor_style( 'style.css' );

    // Wide / full alignment in block editor
    add_theme_support( 'align-wide' );

    // Post formats (optional — can extend later)
    add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'quote', 'video' ) );

    // Register navigation menus
    register_nav_menus( array(
        'primary'         => esc_html__( 'Primary Navigation', 'vitalstack' ),
        'footer-cats'     => esc_html__( 'Footer — Categories', 'vitalstack' ),
        'footer-company'  => esc_html__( 'Footer — Company', 'vitalstack' ),
        'footer-legal'    => esc_html__( 'Footer — Legal', 'vitalstack' ),
    ) );
}
add_action( 'after_setup_theme', 'vitalstack_setup' );


/* ─────────────────────────────────────────────────────────────────────────────
   SUBCATEGORY FILTER — makes the tab bar on category.php actually filter posts
   When ?subcat=<term_id> is present on a category archive, we narrow the main
   query to that child category only.  Without this hook the tabs change the URL
   but WordPress ignores the extra parameter and keeps showing every post in the
   parent category.
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_filter_by_subcat( WP_Query $query ) {
    // Only touch the main front-end query on category archives
    if ( is_admin() || ! $query->is_main_query() || ! $query->is_category() ) {
        return;
    }

    $subcat = isset( $_GET['subcat'] ) ? absint( $_GET['subcat'] ) : 0;

    if ( $subcat > 0 ) {
        // Verify the term actually exists to avoid empty-result surprises
        $term = get_term( $subcat, 'category' );
        if ( $term && ! is_wp_error( $term ) ) {
            $query->set( 'cat', $subcat );
        }
    }
}
add_action( 'pre_get_posts', 'vitalstack_filter_by_subcat' );


/* ─────────────────────────────────────────────────────────────────────────────
   CONTENT WIDTH
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'vitalstack_content_width', 740 );
}
add_action( 'after_setup_theme', 'vitalstack_content_width', 0 );


/* ─────────────────────────────────────────────────────────────────────────────
   ENQUEUE STYLES & SCRIPTS
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_scripts() {
    // Google Fonts: Playfair Display, DM Sans, Space Mono
    wp_enqueue_style(
        'vitalstack-google-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&family=Space+Mono:wght@400;700&display=swap',
        array(),
        null
    );

    // Main stylesheet
    wp_enqueue_style(
        'vitalstack-style',
        get_stylesheet_uri(),
        array( 'vitalstack-google-fonts' ),
        wp_get_theme()->get( 'Version' )
    );

    // Main JS (sticky header, mobile menu, reading progress, FAQ accordion)
    wp_enqueue_script(
        'vitalstack-main',
        get_template_directory_uri() . '/js/main.js',
        array(),
        wp_get_theme()->get( 'Version' ),
        true
    );

    // Pass AJAX URL & nonce to JS
    wp_localize_script( 'vitalstack-main', 'vitalstackData', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'vitalstack-nonce' ),
    ) );

    // Comment reply script (only on singular with comments open)
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'vitalstack_scripts' );


/* ─────────────────────────────────────────────────────────────────────────────
   REGISTER WIDGET AREAS (SIDEBARS)
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_widgets_init() {
    // Blog sidebar
    register_sidebar( array(
        'name'          => esc_html__( 'Blog Sidebar', 'vitalstack' ),
        'id'            => 'sidebar-blog',
        'description'   => esc_html__( 'Widgets shown in the blog listing sidebar.', 'vitalstack' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    // Single post sidebar
    register_sidebar( array(
        'name'          => esc_html__( 'Single Post Sidebar', 'vitalstack' ),
        'id'            => 'sidebar-single',
        'description'   => esc_html__( 'Widgets shown in the single post sidebar (TOC, categories, subscribe).', 'vitalstack' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    // Category page sidebar
    register_sidebar( array(
        'name'          => esc_html__( 'Category Sidebar', 'vitalstack' ),
        'id'            => 'sidebar-category',
        'description'   => esc_html__( 'Widgets shown in category archive sidebars.', 'vitalstack' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    // Footer widget areas (4 columns)
    for ( $i = 1; $i <= 4; $i++ ) {
        register_sidebar( array(
            /* translators: %d: footer column number */
            'name'          => sprintf( esc_html__( 'Footer Area %d', 'vitalstack' ), $i ),
            'id'            => 'footer-' . $i,
            /* translators: %d: footer column number */
            'description'   => sprintf( esc_html__( 'Footer column %d widget area.', 'vitalstack' ), $i ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h5>',
            'after_title'   => '</h5>',
        ) );
    }

    // Footer bottom bar (copyright / social)
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Bottom Bar', 'vitalstack' ),
        'id'            => 'footer-bottom',
        'description'   => esc_html__( 'Social icons / copyright override widget.', 'vitalstack' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '',
        'after_title'   => '',
    ) );
}
add_action( 'widgets_init', 'vitalstack_widgets_init' );


/* ─────────────────────────────────────────────────────────────────────────────
   CUSTOMIZER SETTINGS
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_customize_register( $wp_customize ) {

    /* ── COLORS ── */
    $wp_customize->add_section( 'vitalstack_colors', array(
        'title'    => __( 'VitalStack Colors', 'vitalstack' ),
        'priority' => 30,
    ) );

    $color_settings = array(
        'primary_green'   => array( 'label' => 'Primary Green',   'default' => '#1d8a4e' ),
        'green_light'     => array( 'label' => 'Green Light',      'default' => '#25b067' ),
        'green_pale'      => array( 'label' => 'Green Pale',       'default' => '#e8f5ee' ),
        'navy_dark'       => array( 'label' => 'Navy Dark',        'default' => '#122045' ),
        'navy_mid'        => array( 'label' => 'Navy Mid',         'default' => '#1d3461' ),
        'navy_light'      => array( 'label' => 'Navy Light',       'default' => '#e8ecf5' ),
        'off_white'       => array( 'label' => 'Off White (Body BG)', 'default' => '#f7f9f7' ),
        'body_text'       => array( 'label' => 'Body Text',        'default' => '#1a1a2e' ),
        'muted_text'      => array( 'label' => 'Muted Text',       'default' => '#6b7280' ),
        'border_color'    => array( 'label' => 'Border Color',     'default' => '#dde3dd' ),
        'footer_bg'       => array( 'label' => 'Footer Background', 'default' => '#0d1b36' ),
    );

    foreach ( $color_settings as $id => $args ) {
        $wp_customize->add_setting( 'vitalstack_color_' . $id, array(
            'default'           => $args['default'],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
            'vitalstack_color_' . $id, array(
                'label'   => __( $args['label'], 'vitalstack' ),
                'section' => 'vitalstack_colors',
            )
        ) );
    }

    /* ── TYPOGRAPHY ── */
    $wp_customize->add_section( 'vitalstack_typography', array(
        'title'    => __( 'VitalStack Typography', 'vitalstack' ),
        'priority' => 35,
    ) );

    // Heading font
    $wp_customize->add_setting( 'vitalstack_heading_font', array(
        'default'           => 'Playfair Display',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'vitalstack_heading_font', array(
        'label'   => __( 'Heading Font', 'vitalstack' ),
        'section' => 'vitalstack_typography',
        'type'    => 'text',
    ) );

    // Body font
    $wp_customize->add_setting( 'vitalstack_body_font', array(
        'default'           => 'DM Sans',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'vitalstack_body_font', array(
        'label'   => __( 'Body Font', 'vitalstack' ),
        'section' => 'vitalstack_typography',
        'type'    => 'text',
    ) );

    /* ── HEADER SETTINGS ── */
    $wp_customize->add_section( 'vitalstack_header', array(
        'title'    => __( 'Header Settings', 'vitalstack' ),
        'priority' => 40,
    ) );

    // ── Normal (non-sticky) header logo ──────────────────────────────────────
    $wp_customize->add_setting( 'vitalstack_header_logo', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize,
        'vitalstack_header_logo', array(
            'label'       => __( 'Header Logo (Normal / Default)', 'vitalstack' ),
            'description' => __( 'Logo displayed in the normal (non-sticky) header state. Recommended height: 42 px. Leave empty to use the WordPress Site Logo.', 'vitalstack' ),
            'section'     => 'vitalstack_header',
            'mime_type'   => 'image',
        )
    ) );

    // Normal header logo display height
    $wp_customize->add_setting( 'vitalstack_header_logo_height', array(
        'default'           => 42,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_header_logo_height', array(
        'label'       => __( 'Header Logo Height (px)', 'vitalstack' ),
        'description' => __( 'Controls the CSS height of the normal header logo.', 'vitalstack' ),
        'section'     => 'vitalstack_header',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 20, 'max' => 120, 'step' => 1 ),
    ) );

    // ── Sticky header logo ────────────────────────────────────────────────────
    $wp_customize->add_setting( 'vitalstack_sticky_logo', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize,
        'vitalstack_sticky_logo', array(
            'label'       => __( 'Sticky Header Logo (on scroll)', 'vitalstack' ),
            'description' => __( 'Separate logo shown when the header becomes sticky on scroll. Useful for a darker/smaller logo variant. Leave empty to reuse the normal header logo.', 'vitalstack' ),
            'section'     => 'vitalstack_header',
            'mime_type'   => 'image',
        )
    ) );

    // Sticky logo display height
    $wp_customize->add_setting( 'vitalstack_sticky_logo_height', array(
        'default'           => 36,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_sticky_logo_height', array(
        'label'       => __( 'Sticky Header Logo Height (px)', 'vitalstack' ),
        'description' => __( 'Controls the CSS height of the sticky header logo.', 'vitalstack' ),
        'section'     => 'vitalstack_header',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 20, 'max' => 120, 'step' => 1 ),
    ) );

    $wp_customize->add_setting( 'vitalstack_sticky_header', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'vitalstack_sticky_header', array(
        'label'   => __( 'Enable Sticky Header', 'vitalstack' ),
        'section' => 'vitalstack_header',
        'type'    => 'checkbox',
    ) );

    $wp_customize->add_setting( 'vitalstack_header_cta_label', array(
        'default'           => 'Subscribe',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_header_cta_label', array(
        'label'   => __( 'Header CTA Button Label', 'vitalstack' ),
        'section' => 'vitalstack_header',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'vitalstack_header_cta_url', array(
        'default'           => '#newsletter',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_header_cta_url', array(
        'label'   => __( 'Header CTA Button URL', 'vitalstack' ),
        'section' => 'vitalstack_header',
        'type'    => 'url',
    ) );

    /* ── FOOTER SETTINGS ── */
    $wp_customize->add_section( 'vitalstack_footer', array(
        'title'    => __( 'Footer Settings', 'vitalstack' ),
        'priority' => 45,
    ) );

    // Footer logo (separate from the WP Custom Logo)
    $wp_customize->add_setting( 'vitalstack_footer_logo', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize,
        'vitalstack_footer_logo', array(
            'label'       => __( 'Footer Logo', 'vitalstack' ),
            'description' => __( 'Upload a logo for the footer brand column. Use a light/white version for the dark footer background. Recommended height: 36 px. Leave empty to use the default WordPress Site Logo.', 'vitalstack' ),
            'section'     => 'vitalstack_footer',
            'mime_type'   => 'image',
        )
    ) );

    // Footer logo display height
    $wp_customize->add_setting( 'vitalstack_footer_logo_height', array(
        'default'           => 36,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_footer_logo_height', array(
        'label'       => __( 'Footer Logo Height (px)', 'vitalstack' ),
        'description' => __( 'Controls the CSS height of the footer logo image.', 'vitalstack' ),
        'section'     => 'vitalstack_footer',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 20, 'max' => 120, 'step' => 1 ),
    ) );

    $wp_customize->add_setting( 'vitalstack_footer_tagline', array(
        'default'           => 'Bringing you the sharpest insights at the intersection of Artificial Intelligence and Human Health. Knowledge that keeps you ahead.',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_footer_tagline', array(
        'label'   => __( 'Footer Brand Tagline', 'vitalstack' ),
        'section' => 'vitalstack_footer',
        'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'vitalstack_copyright', array(
        'default'           => '© ' . gmdate( 'Y' ) . ' VitalStack. All rights reserved.',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_copyright', array(
        'label'   => __( 'Copyright Text', 'vitalstack' ),
        'section' => 'vitalstack_footer',
        'type'    => 'text',
    ) );

    /* ── SOCIAL LINKS ── */
    $wp_customize->add_section( 'vitalstack_social', array(
        'title'    => __( 'Social Media Links', 'vitalstack' ),
        'priority' => 50,
    ) );

    $social_networks = array(
        'instagram'  => 'Instagram URL',
        'facebook'   => 'Facebook URL',
        'email'      => 'Email / Contact URL',
        'youtube'    => 'YouTube URL',
        'linkedin'   => 'LinkedIn URL',
    );

    foreach ( $social_networks as $id => $label ) {
        $wp_customize->add_setting( 'vitalstack_social_' . $id, array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( 'vitalstack_social_' . $id, array(
            'label'   => __( $label, 'vitalstack' ),
            'section' => 'vitalstack_social',
            'type'    => 'url',
        ) );
    }

    /* ── HOMEPAGE SETTINGS ── */
    $wp_customize->add_section( 'vitalstack_homepage', array(
        'title'    => __( 'Homepage Settings', 'vitalstack' ),
        'priority' => 55,
    ) );

    // Hero section
    $wp_customize->add_setting( 'vitalstack_hero_kicker', array(
        'default'           => 'Your intelligence edge',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_hero_kicker', array(
        'label'   => __( 'Hero Eyebrow Text', 'vitalstack' ),
        'section' => 'vitalstack_homepage',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'vitalstack_hero_h1', array(
        'default'           => 'Where AI Meets <em>Human Health</em>',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_hero_h1', array(
        'label'       => __( 'Hero H1 (HTML allowed)', 'vitalstack' ),
        'section'     => 'vitalstack_homepage',
        'type'        => 'textarea',
    ) );

    $wp_customize->add_setting( 'vitalstack_hero_desc', array(
        'default'           => 'Deep-dive articles, research breakdowns, and actionable insights at the intersection of artificial intelligence and modern healthcare.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_hero_desc', array(
        'label'   => __( 'Hero Description', 'vitalstack' ),
        'section' => 'vitalstack_homepage',
        'type'    => 'textarea',
    ) );

    // AI / Health section post counts
    $wp_customize->add_setting( 'vitalstack_ai_post_count', array(
        'default'           => 3,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'vitalstack_ai_post_count', array(
        'label'   => __( 'AI Section Post Count', 'vitalstack' ),
        'section' => 'vitalstack_homepage',
        'type'    => 'number',
    ) );

    $wp_customize->add_setting( 'vitalstack_health_post_count', array(
        'default'           => 3,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'vitalstack_health_post_count', array(
        'label'   => __( 'Health Section Post Count', 'vitalstack' ),
        'section' => 'vitalstack_homepage',
        'type'    => 'number',
    ) );

    // Newsletter section
    $wp_customize->add_setting( 'vitalstack_newsletter_heading', array(
        'default'           => 'Stay Sharp. Stay Informed.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_newsletter_heading', array(
        'label'   => __( 'Newsletter Section Heading', 'vitalstack' ),
        'section' => 'vitalstack_homepage',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'vitalstack_newsletter_body', array(
        'default'           => 'Join 18,000+ readers who get our best AI and health articles delivered weekly — no spam, unsubscribe any time.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_newsletter_body', array(
        'label'   => __( 'Newsletter Section Body', 'vitalstack' ),
        'section' => 'vitalstack_homepage',
        'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'vitalstack_newsletter_note', array(
        'default'           => 'Free forever · No credit card · Weekly digest',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_newsletter_note', array(
        'label'   => __( 'Newsletter Fine Print', 'vitalstack' ),
        'section' => 'vitalstack_homepage',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'vitalstack_newsletter_btn', array(
        'default'           => 'Subscribe Free →',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_newsletter_btn', array(
        'label'   => __( 'Newsletter Button Label', 'vitalstack' ),
        'section' => 'vitalstack_homepage',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'vitalstack_show_newsletter', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'vitalstack_show_newsletter', array(
        'label'   => __( 'Show Newsletter Section', 'vitalstack' ),
        'section' => 'vitalstack_homepage',
        'type'    => 'checkbox',
    ) );

    /* ── HOMEPAGE — HERO STATS ── */
    $wp_customize->add_section( 'vitalstack_hero_stats', array(
        'title'    => __( 'Homepage — Hero Stats', 'vitalstack' ),
        'priority' => 56,
        'panel'    => '',
    ) );

    $hero_stats = array(
        array( 'id' => 'vitalstack_hero_stat1_num',   'label' => 'Stat 1 Number',   'default' => '240+' ),
        array( 'id' => 'vitalstack_hero_stat1_label', 'label' => 'Stat 1 Label',    'default' => 'Published Articles' ),
        array( 'id' => 'vitalstack_hero_stat2_num',   'label' => 'Stat 2 Number',   'default' => '18k' ),
        array( 'id' => 'vitalstack_hero_stat2_label', 'label' => 'Stat 2 Label',    'default' => 'Monthly Readers' ),
        array( 'id' => 'vitalstack_hero_stat3_num',   'label' => 'Stat 3 Number',   'default' => '2' ),
        array( 'id' => 'vitalstack_hero_stat3_label', 'label' => 'Stat 3 Label',    'default' => 'Expert Categories' ),
    );
    foreach ( $hero_stats as $s ) {
        $wp_customize->add_setting( $s['id'], array(
            'default'           => $s['default'],
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( $s['id'], array(
            'label'   => __( $s['label'], 'vitalstack' ),
            'section' => 'vitalstack_hero_stats',
            'type'    => 'text',
        ) );
    }

    /* ── HOMEPAGE — ABOUT SECTION ── */
    $wp_customize->add_section( 'vitalstack_about_section', array(
        'title'    => __( 'Homepage — About Section', 'vitalstack' ),
        'priority' => 57,
    ) );

    $about_fields = array(
        array( 'id' => 'vitalstack_about_card_icon',    'label' => 'Card Icon (emoji)',              'default' => '🔬',                   'type' => 'text' ),
        array( 'id' => 'vitalstack_about_card_heading', 'label' => 'Card Heading',                  'default' => 'Research-Backed Insights', 'type' => 'text' ),
        array( 'id' => 'vitalstack_about_card_text',    'label' => 'Card Body Text',                'default' => 'Every article on VitalStack is thoroughly sourced from peer-reviewed literature, clinical studies, and verified technical documentation — no fluff, only signal.', 'type' => 'textarea' ),
        array( 'id' => 'vitalstack_about_pill_top',     'label' => 'Floating Pill (top-left)',      'default' => 'New articles weekly',     'type' => 'text' ),
        array( 'id' => 'vitalstack_about_pill_bottom',  'label' => 'Floating Pill (bottom-right)',  'default' => 'Expert-reviewed',         'type' => 'text' ),
        array( 'id' => 'vitalstack_about_eyebrow',      'label' => 'Eyebrow Label',                 'default' => 'About VitalStack',        'type' => 'text' ),
        array( 'id' => 'vitalstack_about_heading',      'label' => 'Section Heading',               'default' => 'A Blog Built for the Curious & the Committed', 'type' => 'text' ),
        array( 'id' => 'vitalstack_about_para1',        'label' => 'Paragraph 1',                   'default' => 'VitalStack was founded with a single mission: make cutting-edge knowledge accessible. Whether you\'re a developer exploring AI applications in medicine, a healthcare professional tracking emerging tech, or simply someone passionate about living better — you\'ll find your home here.', 'type' => 'textarea' ),
        array( 'id' => 'vitalstack_about_para2',        'label' => 'Paragraph 2',                   'default' => 'We bridge the gap between technical depth and everyday readability, delivering content that respects your intelligence without leaving you lost in jargon.', 'type' => 'textarea' ),
        array( 'id' => 'vitalstack_about_cta_label',    'label' => 'CTA Button Label',              'default' => 'Learn More About Us →',   'type' => 'text' ),
        // Pillar 1
        array( 'id' => 'vitalstack_pillar1_icon',  'label' => 'Pillar 1 Icon',  'default' => '🤖', 'type' => 'text' ),
        array( 'id' => 'vitalstack_pillar1_title', 'label' => 'Pillar 1 Title', 'default' => 'Artificial Intelligence', 'type' => 'text' ),
        array( 'id' => 'vitalstack_pillar1_text',  'label' => 'Pillar 1 Text',  'default' => 'LLMs, computer vision, AI ethics, and real-world applications.', 'type' => 'textarea' ),
        // Pillar 2
        array( 'id' => 'vitalstack_pillar2_icon',  'label' => 'Pillar 2 Icon',  'default' => '💊', 'type' => 'text' ),
        array( 'id' => 'vitalstack_pillar2_title', 'label' => 'Pillar 2 Title', 'default' => 'Health & Wellness', 'type' => 'text' ),
        array( 'id' => 'vitalstack_pillar2_text',  'label' => 'Pillar 2 Text',  'default' => 'Evidence-based nutrition, longevity science, and mental health.', 'type' => 'textarea' ),
        // Pillar 3
        array( 'id' => 'vitalstack_pillar3_icon',  'label' => 'Pillar 3 Icon',  'default' => '📚', 'type' => 'text' ),
        array( 'id' => 'vitalstack_pillar3_title', 'label' => 'Pillar 3 Title', 'default' => 'Deep Dives', 'type' => 'text' ),
        array( 'id' => 'vitalstack_pillar3_text',  'label' => 'Pillar 3 Text',  'default' => 'Long-form articles that go beyond the surface level every time.', 'type' => 'textarea' ),
        // Pillar 4
        array( 'id' => 'vitalstack_pillar4_icon',  'label' => 'Pillar 4 Icon',  'default' => '✅', 'type' => 'text' ),
        array( 'id' => 'vitalstack_pillar4_title', 'label' => 'Pillar 4 Title', 'default' => 'Fact-Checked', 'type' => 'text' ),
        array( 'id' => 'vitalstack_pillar4_text',  'label' => 'Pillar 4 Text',  'default' => 'Every claim linked to a credible source or expert opinion.', 'type' => 'textarea' ),
    );
    foreach ( $about_fields as $f ) {
        $wp_customize->add_setting( $f['id'], array(
            'default'           => $f['default'],
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( $f['id'], array(
            'label'   => __( $f['label'], 'vitalstack' ),
            'section' => 'vitalstack_about_section',
            'type'    => $f['type'],
        ) );
    }

    /* ── HOMEPAGE — AI & HEALTH BANNERS ── */
    $wp_customize->add_section( 'vitalstack_category_banners', array(
        'title'    => __( 'Homepage — Category Banners', 'vitalstack' ),
        'priority' => 58,
    ) );

    $banner_fields = array(
        array( 'id' => 'vitalstack_ai_banner_icon',      'label' => 'AI Banner Icon',          'default' => '🤖' ),
        array( 'id' => 'vitalstack_ai_banner_heading',   'label' => 'AI Banner Heading',       'default' => 'Artificial Intelligence' ),
        array( 'id' => 'vitalstack_ai_banner_desc',      'label' => 'AI Banner Description',   'default' => 'Cutting-edge research, tutorials, and insights on AI, ML, and data science.' ),
        array( 'id' => 'vitalstack_ai_eyebrow',          'label' => 'AI Section Eyebrow',      'default' => 'Latest in AI' ),
        array( 'id' => 'vitalstack_ai_section_title',    'label' => 'AI Section Title',        'default' => 'Top AI Articles' ),
        array( 'id' => 'vitalstack_ai_viewall_label',    'label' => 'AI "View All" Link Text', 'default' => 'View all AI posts →' ),
        array( 'id' => 'vitalstack_health_banner_icon',  'label' => 'Health Banner Icon',      'default' => '🌿' ),
        array( 'id' => 'vitalstack_health_banner_heading','label' => 'Health Banner Heading',  'default' => 'Health & Wellness' ),
        array( 'id' => 'vitalstack_health_banner_desc',  'label' => 'Health Banner Description','default' => 'Evidence-based guides on nutrition, longevity, mental health, and the science of feeling great.' ),
        array( 'id' => 'vitalstack_health_eyebrow',      'label' => 'Health Section Eyebrow',  'default' => 'Latest in Health' ),
        array( 'id' => 'vitalstack_health_section_title','label' => 'Health Section Title',    'default' => 'Top Health Articles' ),
        array( 'id' => 'vitalstack_health_viewall_label','label' => 'Health "View All" Link Text','default' => 'View all health posts →' ),
    );
    foreach ( $banner_fields as $f ) {
        $wp_customize->add_setting( $f['id'], array(
            'default'           => $f['default'],
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( $f['id'], array(
            'label'   => __( $f['label'], 'vitalstack' ),
            'section' => 'vitalstack_category_banners',
            'type'    => 'text',
        ) );
    }

    /* ── NEWSLETTER — TRUST CHIPS ── */
    $wp_customize->add_section( 'vitalstack_trust_chips', array(
        'title'    => __( 'Newsletter — Trust Labels', 'vitalstack' ),
        'priority' => 59,
    ) );

    $trust_fields = array(
        array( 'id' => 'vitalstack_trust_chip1', 'label' => 'Trust Label 1', 'default' => 'Healthcare Professionals' ),
        array( 'id' => 'vitalstack_trust_chip2', 'label' => 'Trust Label 2', 'default' => 'AI Researchers' ),
        array( 'id' => 'vitalstack_trust_chip3', 'label' => 'Trust Label 3', 'default' => 'Tech Enthusiasts' ),
    );
    foreach ( $trust_fields as $f ) {
        $wp_customize->add_setting( $f['id'], array(
            'default'           => $f['default'],
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( $f['id'], array(
            'label'   => __( $f['label'], 'vitalstack' ),
            'section' => 'vitalstack_trust_chips',
            'type'    => 'text',
        ) );
    }

    /* ── ABOUT PAGE SETTINGS ── */
    $wp_customize->add_section( 'vitalstack_about_page', array(
        'title'    => __( 'About Page Settings', 'vitalstack' ),
        'priority' => 61,
    ) );

    $about_page_fields = array(
        // Hero
        array( 'id' => 'vitalstack_aboutpage_eyebrow',    'label' => 'Hero Eyebrow',       'default' => 'About VitalStack',                         'type' => 'text' ),
        array( 'id' => 'vitalstack_aboutpage_h1',         'label' => 'Hero Heading',        'default' => 'A Modern Knowledge Platform for Technology & Digital Health', 'type' => 'text' ),
        array( 'id' => 'vitalstack_aboutpage_hero_desc',  'label' => 'Hero Description',    'default' => 'Welcome to VitalStack — making AI, technology, and health innovations simple, practical, and accessible for everyone.', 'type' => 'textarea' ),
        // Stats
        array( 'id' => 'vitalstack_aboutpage_stat1_num',  'label' => 'Stat 1 Number',  'default' => '18K+',  'type' => 'text' ),
        array( 'id' => 'vitalstack_aboutpage_stat1_label','label' => 'Stat 1 Label',   'default' => 'Monthly Readers',   'type' => 'text' ),
        array( 'id' => 'vitalstack_aboutpage_stat2_num',  'label' => 'Stat 2 Number',  'default' => '200+',  'type' => 'text' ),
        array( 'id' => 'vitalstack_aboutpage_stat2_label','label' => 'Stat 2 Label',   'default' => 'Articles Published','type' => 'text' ),
        array( 'id' => 'vitalstack_aboutpage_stat3_num',  'label' => 'Stat 3 Number',  'default' => '5',     'type' => 'text' ),
        array( 'id' => 'vitalstack_aboutpage_stat3_label','label' => 'Stat 3 Label',   'default' => 'Core Topic Areas',  'type' => 'text' ),
        array( 'id' => 'vitalstack_aboutpage_stat4_num',  'label' => 'Stat 4 Number',  'default' => '100%',  'type' => 'text' ),
        array( 'id' => 'vitalstack_aboutpage_stat4_label','label' => 'Stat 4 Label',   'default' => 'Original Content',  'type' => 'text' ),
        // Mission
        array( 'id' => 'vitalstack_aboutpage_mission_heading', 'label' => 'Mission Heading',   'default' => 'Why VitalStack Exists',   'type' => 'text' ),
        array( 'id' => 'vitalstack_aboutpage_mission_para1',   'label' => 'Mission Paragraph 1','default' => 'In a world where Artificial Intelligence, automation, and health innovations evolve every day, information can feel overwhelming. Many websites either overcomplicate topics or publish shallow content without real clarity.', 'type' => 'textarea' ),
        array( 'id' => 'vitalstack_aboutpage_mission_para2',   'label' => 'Mission Paragraph 2','default' => 'VitalStack was created to solve that problem.', 'type' => 'textarea' ),
        array( 'id' => 'vitalstack_aboutpage_mission_statement','label' => 'Mission Statement (highlighted)', 'default' => 'To simplify technology and digital health so anyone can understand, apply, and benefit from it.', 'type' => 'textarea' ),
        array( 'id' => 'vitalstack_aboutpage_mission_para3',   'label' => 'Mission Paragraph 3','default' => 'Whether you are a student exploring AI, a beginner learning tech basics, a professional upgrading digital skills, or a curious reader following health innovations — VitalStack provides clear guidance that helps you grow with confidence.', 'type' => 'textarea' ),
        // Values
        array( 'id' => 'vitalstack_aboutpage_value1_badge', 'label' => 'Value 1 Badge',  'default' => '100% Original Content', 'type' => 'text' ),
        array( 'id' => 'vitalstack_aboutpage_value1_title', 'label' => 'Value 1 Title',  'default' => 'Clarity Above All',     'type' => 'text' ),
        array( 'id' => 'vitalstack_aboutpage_value1_text',  'label' => 'Value 1 Text',   'default' => 'We believe complex ideas should be explained in a clear, structured, and beginner-friendly way — without confusion, hype, or unnecessary jargon. Our focus is always clarity, accuracy, and usefulness.', 'type' => 'textarea' ),
        array( 'id' => 'vitalstack_aboutpage_value2_badge', 'label' => 'Value 2 Badge',  'default' => 'No Clickbait. Ever.',   'type' => 'text' ),
    );
    foreach ( $about_page_fields as $f ) {
        $wp_customize->add_setting( $f['id'], array(
            'default'           => $f['default'],
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( $f['id'], array(
            'label'   => __( $f['label'], 'vitalstack' ),
            'section' => 'vitalstack_about_page',
            'type'    => $f['type'],
        ) );
    }

    /* ── CONTACT PAGE SETTINGS ── */
    $wp_customize->add_section( 'vitalstack_contact_page', array(
        'title'    => __( 'Contact Page Settings', 'vitalstack' ),
        'priority' => 62,
    ) );

    $contact_fields = array(
        array( 'id' => 'vitalstack_contact_eyebrow',          'label' => 'Page Eyebrow',             'default' => 'Get in Touch',          'type' => 'text' ),
        array( 'id' => 'vitalstack_contact_heading',          'label' => 'Page Heading',             'default' => "We'd Love to Hear From You", 'type' => 'text' ),
        array( 'id' => 'vitalstack_contact_subheading',       'label' => 'Page Sub-Description',     'default' => 'Whether you have a story idea, want to write for us, or just want to say hello — our team reads every message.', 'type' => 'textarea' ),
        array( 'id' => 'vitalstack_contact_form_heading',     'label' => 'Form Section Heading',     'default' => 'Send Us a Message',     'type' => 'text' ),
        array( 'id' => 'vitalstack_contact_form_desc',        'label' => 'Form Section Description', 'default' => "Fill in the form below and we'll get back to you within 24–48 hours on business days.", 'type' => 'textarea' ),
        array( 'id' => 'vitalstack_contact_success_heading',  'label' => 'Success Heading',          'default' => 'Message Sent!',          'type' => 'text' ),
        array( 'id' => 'vitalstack_contact_success_text',     'label' => 'Success Body Text',        'default' => "Thanks for reaching out. We'll get back to you within 24–48 hours.", 'type' => 'textarea' ),
        array( 'id' => 'vitalstack_contact_reply_time',       'label' => 'Reply Time Note',          'default' => 'We reply within 24–48 hours', 'type' => 'text' ),
        array( 'id' => 'vitalstack_contact_write_heading',    'label' => 'Write for Us — Heading',   'default' => 'Write for VitalStack',  'type' => 'text' ),
        array( 'id' => 'vitalstack_contact_write_text',       'label' => 'Write for Us — Body Text', 'default' => 'Are you an expert in AI or healthcare? We accept guest posts and original research articles.', 'type' => 'textarea' ),
        array( 'id' => 'vitalstack_contact_write_link_label', 'label' => 'Write for Us — Link Label','default' => 'View submission guidelines', 'type' => 'text' ),
        array( 'id' => 'vitalstack_contact_partner_heading',  'label' => 'Partnerships — Heading',   'default' => 'Partnerships & Advertising', 'type' => 'text' ),
        array( 'id' => 'vitalstack_contact_partner_text',     'label' => 'Partnerships — Body Text', 'default' => 'Interested in sponsored content or co-marketing? We work with brands aligned to our mission.', 'type' => 'textarea' ),
    );
    foreach ( $contact_fields as $f ) {
        $wp_customize->add_setting( $f['id'], array(
            'default'           => $f['default'],
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( $f['id'], array(
            'label'   => __( $f['label'], 'vitalstack' ),
            'section' => 'vitalstack_contact_page',
            'type'    => $f['type'],
        ) );
    }

    /* ── BLOG ARCHIVE SETTINGS ── */
    $wp_customize->add_section( 'vitalstack_blog_archive', array(
        'title'    => __( 'Blog Archive Page', 'vitalstack' ),
        'priority' => 60,
    ) );

    $wp_customize->add_setting( 'vitalstack_blog_stat1', array(
        'default'           => '240+ Articles Published',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_blog_stat1', array(
        'label'   => __( 'Blog Stat 1', 'vitalstack' ),
        'section' => 'vitalstack_blog_archive',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'vitalstack_blog_stat2', array(
        'default'           => '2 Categories',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_blog_stat2', array(
        'label'   => __( 'Blog Stat 2', 'vitalstack' ),
        'section' => 'vitalstack_blog_archive',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'vitalstack_blog_stat3', array(
        'default'           => 'Weekly New Content',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_blog_stat3', array(
        'label'   => __( 'Blog Stat 3', 'vitalstack' ),
        'section' => 'vitalstack_blog_archive',
        'type'    => 'text',
    ) );

    /* ── CONTACT PAGE SETTINGS ── */
    $wp_customize->add_section( 'vitalstack_contact_settings', array(
        'title'       => __( 'Contact Page Settings', 'vitalstack' ),
        'description' => __( 'Configure Contact Form 7 integration and contact email addresses.', 'vitalstack' ),
        'priority'    => 65,
    ) );

    // CF7 Form ID
    $wp_customize->add_setting( 'vitalstack_cf7_form_id', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'vitalstack_cf7_form_id', array(
        'label'       => __( 'Contact Form 7 — Form ID', 'vitalstack' ),
        'description' => __( 'Enter the ID of your CF7 form (found in Contact → your form list). Leave 0 to auto-detect the first available form. The built-in wp_mail() form is used if CF7 is not installed.', 'vitalstack' ),
        'section'     => 'vitalstack_contact_settings',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 0, 'step' => 1, 'placeholder' => '0' ),
    ) );

    // General contact email
    $wp_customize->add_setting( 'vitalstack_email_general', array(
        'default'           => 'hello@vitalstack.io',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_email_general', array(
        'label'       => __( 'General Contact Email', 'vitalstack' ),
        'description' => __( 'Displayed on the Contact page info card and used as the wp_mail() destination.', 'vitalstack' ),
        'section'     => 'vitalstack_contact_settings',
        'type'        => 'email',
    ) );

    // Partners email
    $wp_customize->add_setting( 'vitalstack_email_partners', array(
        'default'           => 'partners@vitalstack.io',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'vitalstack_email_partners', array(
        'label'       => __( 'Partnerships Email', 'vitalstack' ),
        'description' => __( 'Displayed in the Partnerships & Advertising info card.', 'vitalstack' ),
        'section'     => 'vitalstack_contact_settings',
        'type'        => 'email',
    ) );
}
add_action( 'customize_register', 'vitalstack_customize_register' );


/* ─────────────────────────────────────────────────────────────────────────────
   OUTPUT CUSTOMIZER CSS VARIABLES (live preview compatible)
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_customizer_css() {
    $colors = array(
        '--vs-green'        => get_theme_mod( 'vitalstack_color_primary_green', '#1d8a4e' ),
        '--vs-green-light'  => get_theme_mod( 'vitalstack_color_green_light',   '#25b067' ),
        '--vs-green-pale'   => get_theme_mod( 'vitalstack_color_green_pale',    '#e8f5ee' ),
        '--vs-navy'         => get_theme_mod( 'vitalstack_color_navy_dark',     '#122045' ),
        '--vs-navy-mid'     => get_theme_mod( 'vitalstack_color_navy_mid',      '#1d3461' ),
        '--vs-navy-light'   => get_theme_mod( 'vitalstack_color_navy_light',    '#e8ecf5' ),
        '--vs-off-white'    => get_theme_mod( 'vitalstack_color_off_white',     '#f7f9f7' ),
        '--vs-text'         => get_theme_mod( 'vitalstack_color_body_text',     '#1a1a2e' ),
        '--vs-muted'        => get_theme_mod( 'vitalstack_color_muted_text',    '#6b7280' ),
        '--vs-border'       => get_theme_mod( 'vitalstack_color_border_color',  '#dde3dd' ),
    );

    $css = ':root {';
    foreach ( $colors as $var => $value ) {
        $css .= $var . ':' . sanitize_hex_color( $value ) . ';';
    }
    $css .= '}';

    // Footer background
    $footer_bg = get_theme_mod( 'vitalstack_color_footer_bg', '#0d1b36' );
    $css .= '.site-footer{background:' . sanitize_hex_color( $footer_bg ) . ';}';

    // Logo heights
    $header_h = (int) get_theme_mod( 'vitalstack_header_logo_height', 42 );
    $sticky_h = (int) get_theme_mod( 'vitalstack_sticky_logo_height', 36 );
    $footer_h = (int) get_theme_mod( 'vitalstack_footer_logo_height', 36 );
    $css .= '.vs-header-logo-img{height:' . $header_h . 'px !important;width:auto;}';
    $css .= '.vs-sticky-logo-img{height:' . $sticky_h . 'px !important;width:auto;}';
    $css .= '.vs-footer-logo-img{height:' . $footer_h . 'px !important;width:auto;}';

    wp_add_inline_style( 'vitalstack-style', $css );
}
add_action( 'wp_enqueue_scripts', 'vitalstack_customizer_css', 20 );


/* ─────────────────────────────────────────────────────────────────────────────
   HELPER: RENDER HEADER (NORMAL), STICKY HEADER, OR FOOTER LOGO
   Usage:
     vitalstack_logo( 'header' );   // normal header logo — in header.php
     vitalstack_logo( 'sticky' );   // sticky bar logo   — in header.php
     vitalstack_logo( 'footer' );   // footer logo       — in footer.php
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_logo( $location = 'header' ) {
    // Map location → customizer key → fallback height
    $map = array(
        'header' => array( 'mod' => 'vitalstack_header_logo', 'height_mod' => 'vitalstack_header_logo_height', 'default_h' => 42, 'class' => 'vs-header-logo-img' ),
        'sticky' => array( 'mod' => 'vitalstack_sticky_logo',  'height_mod' => 'vitalstack_sticky_logo_height',  'default_h' => 36, 'class' => 'vs-sticky-logo-img' ),
        'footer' => array( 'mod' => 'vitalstack_footer_logo',  'height_mod' => 'vitalstack_footer_logo_height',  'default_h' => 36, 'class' => 'vs-footer-logo-img' ),
    );

    $cfg           = isset( $map[ $location ] ) ? $map[ $location ] : $map['header'];
    $attachment_id = (int) get_theme_mod( $cfg['mod'], 0 );
    $height        = (int) get_theme_mod( $cfg['height_mod'], $cfg['default_h'] );
    $site_name     = get_bloginfo( 'name' );
    $img_class     = $cfg['class'];

    // ── 1. Custom logo uploaded for this specific location ────────────────
    if ( $attachment_id ) {
        $src = wp_get_attachment_image_url( $attachment_id, 'full' );
        if ( $src ) {
            echo '<img src="' . esc_url( $src ) . '"'
               . ' alt="' . esc_attr( $site_name ) . '"'
               . ' height="' . esc_attr( $height ) . '"'
               . ' style="height:' . esc_attr( $height ) . 'px;width:auto;display:block;"'
               . ' class="' . esc_attr( $img_class ) . '"'
               . ' loading="eager" decoding="async">';
            return;
        }
    }

    // ── 2. For sticky: fall back to the normal header logo if no sticky logo set ──
    if ( 'sticky' === $location ) {
        $header_id = (int) get_theme_mod( 'vitalstack_header_logo', 0 );
        if ( $header_id ) {
            $src = wp_get_attachment_image_url( $header_id, 'full' );
            if ( $src ) {
                $h = (int) get_theme_mod( 'vitalstack_header_logo_height', 42 );
                echo '<img src="' . esc_url( $src ) . '"'
                   . ' alt="' . esc_attr( $site_name ) . '"'
                   . ' height="' . esc_attr( $h ) . '"'
                   . ' style="height:' . esc_attr( $h ) . 'px;width:auto;display:block;"'
                   . ' class="' . esc_attr( $img_class ) . '"'
                   . ' loading="eager" decoding="async">';
                return;
            }
        }
    }

    // ── 3. Fall back to WordPress Custom Logo (Appearance → Site Identity) ──
    if ( has_custom_logo() ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $src            = wp_get_attachment_image_url( $custom_logo_id, 'full' );
        if ( $src ) {
            echo '<img src="' . esc_url( $src ) . '"'
               . ' alt="' . esc_attr( $site_name ) . '"'
               . ' height="' . esc_attr( $height ) . '"'
               . ' style="height:' . esc_attr( $height ) . 'px;width:auto;display:block;"'
               . ' class="' . esc_attr( $img_class ) . '"'
               . ' loading="eager" decoding="async">';
            return;
        }
    }

    // ── 4. Final fallback: site name as text ──────────────────────────────
    $footer_style = ( 'footer' === $location ) ? 'color:#fff;' : '';
    $font_size    = ( 'footer' === $location ) ? '22' : '20';
    echo '<span class="vs-logo-text" style="font-family:\'Playfair Display\',serif;font-size:' . $font_size . 'px;font-weight:900;' . $footer_style . '">'
       . esc_html( $site_name )
       . '</span>';
}



function vitalstack_get_cat_class( $post_id = null ) {
    $cats = get_the_category( $post_id );
    if ( empty( $cats ) ) return 'ai';
    $slug = strtolower( $cats[0]->slug );
    if ( strpos( $slug, 'health' ) !== false ) return 'health';
    return 'ai';
}

/* ─────────────────────────────────────────────────────────────────────────────
   HELPER: ESTIMATED READ TIME
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_read_time( $post_id = null ) {
    $content   = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( strip_tags( $content ) );
    $minutes   = max( 1, (int) ceil( $word_count / 200 ) );
    /* translators: %d: number of minutes */
    return sprintf( _n( '%d min read', '%d min read', $minutes, 'vitalstack' ), $minutes );
}

/* ─────────────────────────────────────────────────────────────────────────────
   HELPER: AUTHOR INITIALS AVATAR (fallback)
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_author_initials( $author_id = null ) {
    if ( ! $author_id ) $author_id = get_the_author_meta( 'ID' );
    $name = get_the_author_meta( 'display_name', $author_id );
    $parts = explode( ' ', trim( $name ) );
    $initials = strtoupper( substr( $parts[0], 0, 1 ) );
    if ( count( $parts ) > 1 ) $initials .= strtoupper( substr( end( $parts ), 0, 1 ) );
    return $initials;
}

/* ─────────────────────────────────────────────────────────────────────────────
   HELPER: ARTICLE CARD TEMPLATE PART
   Call: vitalstack_article_card( get_the_ID() );
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_article_card( $post_id = null ) {
    if ( ! $post_id ) $post_id = get_the_ID();
    $cat_class = vitalstack_get_cat_class( $post_id );
    $cat       = get_the_category( $post_id );
    $cat_name  = ! empty( $cat ) ? esc_html( $cat[0]->name ) : 'Article';
    $read_time = vitalstack_read_time( $post_id );
    $author_id = get_post_field( 'post_author', $post_id );
    $initials  = vitalstack_author_initials( $author_id );
    $permalink = get_permalink( $post_id );
    $title     = get_the_title( $post_id );
    $excerpt   = get_the_excerpt( $post_id );
    $author    = get_the_author_meta( 'display_name', $author_id );
    ?>
    <article class="article-card">
        <?php if ( has_post_thumbnail( $post_id ) ) : ?>
            <a href="<?php echo esc_url( $permalink ); ?>">
                <?php echo get_the_post_thumbnail( $post_id, 'vitalstack-card', array( 'class' => 'article-card-img', 'alt' => esc_attr( $title ) ) ); ?>
            </a>
        <?php endif; ?>
        <div class="article-card-body">
            <div class="article-card-meta">
                <span class="tag <?php echo esc_attr( $cat_class ); ?>"><?php echo $cat_name; ?></span>
                <span><?php echo get_the_date( 'M j, Y', $post_id ); ?></span>
                <span>· <?php echo esc_html( $read_time ); ?></span>
            </div>
            <h3 class="article-card-title">
                <a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
            </h3>
            <p class="article-card-excerpt"><?php echo esc_html( $excerpt ); ?></p>
            <div class="article-card-footer">
                <div class="author-line">
                    <div class="author-avatar">
                        <?php
                        $avatar = get_avatar( $author_id, 28 );
                        if ( $avatar ) {
                            echo $avatar;
                        } else {
                            echo esc_html( $initials );
                        }
                        ?>
                    </div>
                    <span><?php echo esc_html( $author ); ?></span>
                </div>
                <a class="read-more" href="<?php echo esc_url( $permalink ); ?>">Read article →</a>
            </div>
        </div>
    </article>
    <?php
}

/* ─────────────────────────────────────────────────────────────────────────────
   NEWSLETTER SECTION TEMPLATE
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_newsletter_section() {
    if ( ! get_theme_mod( 'vitalstack_show_newsletter', true ) ) return;
    $heading   = get_theme_mod( 'vitalstack_newsletter_heading', 'Stay Sharp. Stay Informed.' );
    $body      = get_theme_mod( 'vitalstack_newsletter_body', 'Join 18,000+ readers who get our best AI and health articles delivered weekly.' );
    $note      = get_theme_mod( 'vitalstack_newsletter_note', 'Free forever · No credit card · Weekly digest' );
    $btn_label = get_theme_mod( 'vitalstack_newsletter_btn', 'Subscribe Free →' );
    ?>
    <section id="newsletter" class="newsletter-section">
        <div class="container">
            <p class="section-eyebrow">Newsletter</p>
            <h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
            <p class="newsletter-desc"><?php echo esc_html( $body ); ?></p>
            <?php
            // Use a newsletter plugin shortcode if available, otherwise output a basic form
            if ( shortcode_exists( 'mailchimp' ) ) {
                echo do_shortcode( '[mailchimp]' );
            } elseif ( shortcode_exists( 'convertkit_form' ) ) {
                echo do_shortcode( '[convertkit_form]' );
            } else { ?>
                <form class="newsletter-form" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
                    <?php wp_nonce_field( 'vitalstack_newsletter', 'vs_nonce' ); ?>
                    <input type="hidden" name="action" value="vitalstack_newsletter_signup">
                    <input type="email" name="email" placeholder="<?php esc_attr_e( 'Enter your email address', 'vitalstack' ); ?>" required>
                    <button type="submit" class="btn btn-primary"><?php echo esc_html( $btn_label ); ?></button>
                </form>
                <p class="newsletter-note"><?php echo esc_html( $note ); ?></p>
                <div class="trust-chips">
                    <?php
                    $chips = array(
                        get_theme_mod( 'vitalstack_trust_chip1', 'Healthcare Professionals' ),
                        get_theme_mod( 'vitalstack_trust_chip2', 'AI Researchers' ),
                        get_theme_mod( 'vitalstack_trust_chip3', 'Tech Enthusiasts' ),
                    );
                    foreach ( $chips as $chip ) :
                        if ( $chip ) : ?>
                        <span class="trust-chip">✓ <?php echo esc_html( $chip ); ?></span>
                    <?php endif; endforeach; ?>
                </div>
            <?php } ?>
        </div>
    </section>
    <?php
}

/* ─────────────────────────────────────────────────────────────────────────────
   EXCERPT LENGTH
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_excerpt_length( $length ) {
    return 30; // ~180 characters
}
add_filter( 'excerpt_length', 'vitalstack_excerpt_length' );

function vitalstack_excerpt_more( $more ) {
    return '…';
}
add_filter( 'excerpt_more', 'vitalstack_excerpt_more' );


/* ─────────────────────────────────────────────────────────────────────────────
   DOCUMENT TITLE SEPARATOR
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_document_title_separator( $sep ) {
    return '|';
}
add_filter( 'document_title_separator', 'vitalstack_document_title_separator' );


/* ─────────────────────────────────────────────────────────────────────────────
   BODY CLASSES
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_body_classes( $classes ) {
    if ( is_singular() && ! is_front_page() ) {
        $classes[] = 'single-content';
    }
    if ( is_active_sidebar( 'sidebar-blog' ) ) {
        $classes[] = 'has-sidebar';
    }
    return $classes;
}
add_filter( 'body_class', 'vitalstack_body_classes' );


/* ─────────────────────────────────────────────────────────────────────────────
   BLOCK EDITOR SETTINGS
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_block_editor_settings() {
    add_theme_support( 'editor-color-palette', array(
        array( 'name' => 'Primary Green', 'slug' => 'primary-green', 'color' => '#1d8a4e' ),
        array( 'name' => 'Navy Dark',     'slug' => 'navy-dark',     'color' => '#122045' ),
        array( 'name' => 'Navy Mid',      'slug' => 'navy-mid',      'color' => '#1d3461' ),
        array( 'name' => 'Off White',     'slug' => 'off-white',     'color' => '#f7f9f7' ),
        array( 'name' => 'Green Pale',    'slug' => 'green-pale',    'color' => '#e8f5ee' ),
    ) );
}
add_action( 'after_setup_theme', 'vitalstack_block_editor_settings' );


/* ─────────────────────────────────────────────────────────────────────────────
   PRECONNECT TO GOOGLE FONTS
───────────────────────────────────────────────────────────────────────────── */
function vitalstack_preconnect_google_fonts( $hints, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $hints[] = array( 'href' => 'https://fonts.googleapis.com' );
        $hints[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' );
    }
    return $hints;
}
add_filter( 'wp_resource_hints', 'vitalstack_preconnect_google_fonts', 10, 2 );


/* ─────────────────────────────────────────────────────────────────────────────
   COMMENT CALLBACK — renders each comment with VitalStack styling
   Called by wp_list_comments() in single.php
───────────────────────────────────────────────────────────────────────────── */
if ( ! function_exists( 'vitalstack_comment_callback' ) ) :
function vitalstack_comment_callback( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    $tag                = ( 'div' === $args['style'] ) ? 'div' : 'li';
    $add_below          = 'vs-comment-' . $comment->comment_ID;
    ?>
    <<?php echo $tag; ?> <?php comment_class( 'vs-comment-item', $comment ); ?> id="comment-<?php comment_ID(); ?>">

      <div class="vs-comment-inner">

        <!-- Avatar -->
        <div class="vs-comment-avatar">
          <?php echo get_avatar( $comment, 44 ); ?>
        </div>

        <!-- Body -->
        <div class="vs-comment-body">
          <div class="vs-comment-meta">
            <span class="vs-comment-author">
              <?php comment_author_link( $comment ); ?>
            </span>
            <time class="vs-comment-date" datetime="<?php comment_date( 'c', $comment ); ?>">
              <a href="<?php echo htmlspecialchars( get_comment_link( $comment, $args ) ); ?>">
                <?php
                /* translators: 1: date, 2: time */
                printf( '%s · %s',
                    get_comment_date( '', $comment ),
                    get_comment_time( '', false, false, $comment )
                );
                ?>
              </a>
            </time>
            <?php if ( '0' === $comment->comment_approved ) : ?>
              <em class="vs-awaiting-moderation"><?php esc_html_e( 'Awaiting moderation', 'vitalstack' ); ?></em>
            <?php endif; ?>
          </div>

          <div class="vs-comment-text">
            <?php comment_text( $comment ); ?>
          </div>

          <?php if ( $args['max_depth'] !== $depth && comments_open() ) : ?>
          <div class="vs-comment-reply">
            <?php
            comment_reply_link( array_merge( $args, array(
                'add_below' => $add_below,
                'depth'     => $depth,
                'max_depth' => $args['max_depth'],
                'before'    => '',
                'after'     => '',
                'reply_text'=> '↩ ' . __( 'Reply', 'vitalstack' ),
            ) ) );
            ?>
          </div>
          <?php endif; ?>

        </div><!-- /.vs-comment-body -->
      </div><!-- /.vs-comment-inner -->

      <?php
      /* Open children wrapper (closed by wp_list_comments itself) */
      if ( $args['has_children'] ) :
        echo '<ol class="vs-comment-children">';
      endif;
      // Note: wp_list_comments automatically closes the <ol> and the <li>/<div>
      // when $args['end-callback'] is not set — we rely on that default.
} // end vitalstack_comment_callback
endif;


/* ═════════════════════════════════════════════════════════════════════════════
   NEWS CUSTOM POST TYPE
   Registers the "news" post type with full editorial support.
   Flush permalinks after activating: Settings → Permalinks → Save.
═════════════════════════════════════════════════════════════════════════════ */
function vitalstack_register_cpt_news() {
    $labels = array(
        'name'                  => _x( 'News', 'Post type general name', 'vitalstack' ),
        'singular_name'         => _x( 'News Item', 'Post type singular name', 'vitalstack' ),
        'menu_name'             => _x( 'News', 'Admin menu text', 'vitalstack' ),
        'name_admin_bar'        => _x( 'News Item', 'Add New on Toolbar', 'vitalstack' ),
        'add_new'               => __( 'Add New', 'vitalstack' ),
        'add_new_item'          => __( 'Add New News Item', 'vitalstack' ),
        'new_item'              => __( 'New News Item', 'vitalstack' ),
        'edit_item'             => __( 'Edit News Item', 'vitalstack' ),
        'view_item'             => __( 'View News Item', 'vitalstack' ),
        'all_items'             => __( 'All News', 'vitalstack' ),
        'search_items'          => __( 'Search News', 'vitalstack' ),
        'not_found'             => __( 'No news items found.', 'vitalstack' ),
        'not_found_in_trash'    => __( 'No news items found in Trash.', 'vitalstack' ),
        'featured_image'        => __( 'News Cover Image', 'vitalstack' ),
        'set_featured_image'    => __( 'Set cover image', 'vitalstack' ),
        'remove_featured_image' => __( 'Remove cover image', 'vitalstack' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'news', 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-megaphone',
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
        'show_in_rest'       => true,   // Block editor support
        'taxonomies'         => array( 'news_category', 'post_tag' ),
    );

    register_post_type( 'news', $args );
}
add_action( 'init', 'vitalstack_register_cpt_news' );


/* ─── News Category Taxonomy ─────────────────────────────────────────────── */
function vitalstack_register_taxonomy_news_category() {
    $labels = array(
        'name'              => _x( 'News Categories', 'taxonomy general name', 'vitalstack' ),
        'singular_name'     => _x( 'News Category', 'taxonomy singular name', 'vitalstack' ),
        'search_items'      => __( 'Search News Categories', 'vitalstack' ),
        'all_items'         => __( 'All News Categories', 'vitalstack' ),
        'parent_item'       => __( 'Parent Category', 'vitalstack' ),
        'parent_item_colon' => __( 'Parent Category:', 'vitalstack' ),
        'edit_item'         => __( 'Edit Category', 'vitalstack' ),
        'update_item'       => __( 'Update Category', 'vitalstack' ),
        'add_new_item'      => __( 'Add New Category', 'vitalstack' ),
        'new_item_name'     => __( 'New Category Name', 'vitalstack' ),
        'menu_name'         => __( 'Categories', 'vitalstack' ),
    );
    register_taxonomy( 'news_category', array( 'news' ), array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'news-category' ),
        'show_in_rest'      => true,
    ) );
}
add_action( 'init', 'vitalstack_register_taxonomy_news_category' );


/* ─── News Article Card helper ───────────────────────────────────────────── */
/**
 * Renders a single news card — used in page-news.php grid.
 *
 * @param int $post_id
 */
function vitalstack_news_card( $post_id = null ) {
    if ( ! $post_id ) $post_id = get_the_ID();
    $permalink = get_permalink( $post_id );
    $title     = get_the_title( $post_id );
    $excerpt   = get_the_excerpt( $post_id );
    $date      = get_the_date( 'M j, Y', $post_id );
    $author_id = get_post_field( 'post_author', $post_id );
    $author    = get_the_author_meta( 'display_name', $author_id );
    $initials  = vitalstack_author_initials( $author_id );

    // News category (custom taxonomy)
    $terms     = get_the_terms( $post_id, 'news_category' );
    $cat_name  = ( $terms && ! is_wp_error( $terms ) ) ? esc_html( $terms[0]->name ) : 'News';
    $cat_link  = ( $terms && ! is_wp_error( $terms ) ) ? get_term_link( $terms[0] ) : '#';

    // Source meta (optional — set via custom field "news_source")
    $source    = get_post_meta( $post_id, 'news_source', true );
    ?>
    <article class="news-card">
        <?php if ( has_post_thumbnail( $post_id ) ) : ?>
            <a href="<?php echo esc_url( $permalink ); ?>" class="news-card-thumb-link" tabindex="-1" aria-hidden="true">
                <?php echo get_the_post_thumbnail( $post_id, 'vitalstack-card', array( 'class' => 'news-card-img', 'alt' => esc_attr( $title ) ) ); ?>
            </a>
        <?php endif; ?>
        <div class="news-card-body">
            <div class="news-card-meta">
                <a href="<?php echo esc_url( is_string( $cat_link ) ? $cat_link : '#' ); ?>" class="tag tag-news"><?php echo $cat_name; ?></a>
                <time datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>"><?php echo esc_html( $date ); ?></time>
                <?php if ( $source ) : ?>
                    <span class="news-source">· <?php echo esc_html( $source ); ?></span>
                <?php endif; ?>
            </div>
            <h3 class="news-card-title">
                <a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
            </h3>
            <p class="news-card-excerpt"><?php echo esc_html( $excerpt ); ?></p>
            <div class="news-card-footer">
                <div class="author-line">
                    <div class="author-avatar">
                        <?php
                        $avatar = get_avatar( $author_id, 28 );
                        if ( $avatar ) echo $avatar;
                        else echo esc_html( $initials );
                        ?>
                    </div>
                    <span><?php echo esc_html( $author ); ?></span>
                </div>
                <a class="read-more" href="<?php echo esc_url( $permalink ); ?>">Read →</a>
            </div>
        </div>
    </article>
    <?php
}


/* ═════════════════════════════════════════════════════════════════════════════
   TUTORIALS CUSTOM POST TYPE
   Supports YouTube embeds, self-hosted video, Vimeo, and any oEmbed URL.
   Flush permalinks after activating: Settings → Permalinks → Save.
═════════════════════════════════════════════════════════════════════════════ */
function vitalstack_register_cpt_tutorials() {
    $labels = array(
        'name'                  => _x( 'Tutorials', 'Post type general name', 'vitalstack' ),
        'singular_name'         => _x( 'Tutorial', 'Post type singular name', 'vitalstack' ),
        'menu_name'             => _x( 'Tutorials', 'Admin menu text', 'vitalstack' ),
        'name_admin_bar'        => _x( 'Tutorial', 'Add New on Toolbar', 'vitalstack' ),
        'add_new'               => __( 'Add New', 'vitalstack' ),
        'add_new_item'          => __( 'Add New Tutorial', 'vitalstack' ),
        'new_item'              => __( 'New Tutorial', 'vitalstack' ),
        'edit_item'             => __( 'Edit Tutorial', 'vitalstack' ),
        'view_item'             => __( 'View Tutorial', 'vitalstack' ),
        'all_items'             => __( 'All Tutorials', 'vitalstack' ),
        'search_items'          => __( 'Search Tutorials', 'vitalstack' ),
        'not_found'             => __( 'No tutorials found.', 'vitalstack' ),
        'not_found_in_trash'    => __( 'No tutorials found in Trash.', 'vitalstack' ),
        'featured_image'        => __( 'Tutorial Thumbnail', 'vitalstack' ),
        'set_featured_image'    => __( 'Set thumbnail', 'vitalstack' ),
        'remove_featured_image' => __( 'Remove thumbnail', 'vitalstack' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'tutorials', 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-video-alt3',
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
        'show_in_rest'       => true,
        'taxonomies'         => array( 'tutorial_category', 'tutorial_difficulty', 'post_tag' ),
    );

    register_post_type( 'tutorials', $args );
}
add_action( 'init', 'vitalstack_register_cpt_tutorials' );


/* ─── Tutorial Category Taxonomy ─────────────────────────────────────────── */
function vitalstack_register_taxonomy_tutorial_category() {
    register_taxonomy( 'tutorial_category', array( 'tutorials' ), array(
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'tutorial-category' ),
        'show_in_rest'      => true,
        'labels'            => array(
            'name'          => _x( 'Tutorial Categories', 'taxonomy general name', 'vitalstack' ),
            'singular_name' => _x( 'Category', 'taxonomy singular name', 'vitalstack' ),
            'menu_name'     => __( 'Categories', 'vitalstack' ),
            'add_new_item'  => __( 'Add New Category', 'vitalstack' ),
            'edit_item'     => __( 'Edit Category', 'vitalstack' ),
            'all_items'     => __( 'All Categories', 'vitalstack' ),
        ),
    ) );
}
add_action( 'init', 'vitalstack_register_taxonomy_tutorial_category' );


/* ─── Tutorial Difficulty Taxonomy ───────────────────────────────────────── */
function vitalstack_register_taxonomy_tutorial_difficulty() {
    register_taxonomy( 'tutorial_difficulty', array( 'tutorials' ), array(
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'tutorial-difficulty' ),
        'show_in_rest'      => true,
        'labels'            => array(
            'name'          => _x( 'Difficulty Levels', 'taxonomy general name', 'vitalstack' ),
            'singular_name' => _x( 'Difficulty', 'taxonomy singular name', 'vitalstack' ),
            'menu_name'     => __( 'Difficulty', 'vitalstack' ),
            'add_new_item'  => __( 'Add Difficulty Level', 'vitalstack' ),
            'all_items'     => __( 'All Levels', 'vitalstack' ),
        ),
    ) );
}
add_action( 'init', 'vitalstack_register_taxonomy_tutorial_difficulty' );


/* ─── Tutorial Meta Box — Video Settings ─────────────────────────────────── */
/**
 * Adds a "Video Settings" meta box to the tutorial post editor.
 *
 * Supported fields:
 *   tutorial_video_type      — youtube | vimeo | self_hosted | oembed
 *   tutorial_video_url       — YouTube / Vimeo / oEmbed URL
 *   tutorial_video_file      — Self-hosted file URL (mp4 / webm)
 *   tutorial_video_poster    — Poster image URL (for self-hosted)
 *   tutorial_duration        — Human-readable duration e.g. "12:34"
 *   tutorial_difficulty_meta — Beginner / Intermediate / Advanced (legacy flat)
 */
function vitalstack_tutorial_meta_box() {
    add_meta_box(
        'vitalstack_tutorial_video',
        __( 'Video Settings', 'vitalstack' ),
        'vitalstack_tutorial_video_callback',
        'tutorials',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'vitalstack_tutorial_meta_box' );

function vitalstack_tutorial_video_callback( $post ) {
    wp_nonce_field( 'vitalstack_tutorial_video_save', 'vitalstack_tutorial_video_nonce' );
    $type    = get_post_meta( $post->ID, 'tutorial_video_type',   true ) ?: 'youtube';
    $url     = get_post_meta( $post->ID, 'tutorial_video_url',    true );
    $file    = get_post_meta( $post->ID, 'tutorial_video_file',   true );
    $poster  = get_post_meta( $post->ID, 'tutorial_video_poster', true );
    $dur     = get_post_meta( $post->ID, 'tutorial_duration',     true );
    ?>
    <style>
        .vs-meta-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .vs-meta-field { display:flex; flex-direction:column; gap:6px; }
        .vs-meta-field label { font-weight:600; font-size:13px; }
        .vs-meta-field input, .vs-meta-field select {
            width:100%; padding:8px 10px; border:1px solid #ddd;
            border-radius:4px; font-size:13px;
        }
        .vs-meta-field .description { font-size:12px; color:#666; margin:0; }
        .vs-meta-section-title {
            font-weight:700; font-size:13px; text-transform:uppercase;
            letter-spacing:.05em; color:#555; margin:16px 0 8px;
            padding-bottom:6px; border-bottom:1px solid #eee;
        }
        #vs-url-row, #vs-file-row { margin-top:0; }
    </style>
    <div class="vs-meta-grid">
        <div class="vs-meta-field">
            <label for="tutorial_video_type"><?php esc_html_e( 'Video Type', 'vitalstack' ); ?></label>
            <select name="tutorial_video_type" id="tutorial_video_type">
                <option value="youtube"      <?php selected( $type, 'youtube' ); ?>>▶ YouTube</option>
                <option value="vimeo"        <?php selected( $type, 'vimeo' ); ?>>🎞 Vimeo</option>
                <option value="self_hosted"  <?php selected( $type, 'self_hosted' ); ?>>📁 Self-Hosted (MP4/WebM)</option>
                <option value="oembed"       <?php selected( $type, 'oembed' ); ?>>🔗 Other oEmbed URL</option>
            </select>
        </div>
        <div class="vs-meta-field">
            <label for="tutorial_duration"><?php esc_html_e( 'Duration', 'vitalstack' ); ?></label>
            <input type="text" name="tutorial_duration" id="tutorial_duration"
                   value="<?php echo esc_attr( $dur ); ?>" placeholder="e.g. 12:34" />
            <p class="description"><?php esc_html_e( 'Shown on the tutorial card.', 'vitalstack' ); ?></p>
        </div>
    </div>

    <div id="vs-url-row" class="vs-meta-field" style="margin-top:12px;">
        <label for="tutorial_video_url"><?php esc_html_e( 'Video URL (YouTube / Vimeo / oEmbed)', 'vitalstack' ); ?></label>
        <input type="url" name="tutorial_video_url" id="tutorial_video_url"
               value="<?php echo esc_attr( $url ); ?>"
               placeholder="https://www.youtube.com/watch?v=XXXXXXXXXX" />
        <p class="description"><?php esc_html_e( 'Paste the full YouTube, Vimeo, or oEmbed-compatible URL.', 'vitalstack' ); ?></p>
    </div>

    <div id="vs-file-row" class="vs-meta-grid" style="margin-top:12px;">
        <div class="vs-meta-field">
            <label for="tutorial_video_file"><?php esc_html_e( 'Self-Hosted Video File URL', 'vitalstack' ); ?></label>
            <input type="url" name="tutorial_video_file" id="tutorial_video_file"
                   value="<?php echo esc_attr( $file ); ?>"
                   placeholder="https://example.com/video.mp4" />
            <p class="description"><?php esc_html_e( 'MP4 or WebM file URL from your Media Library or CDN.', 'vitalstack' ); ?></p>
        </div>
        <div class="vs-meta-field">
            <label for="tutorial_video_poster"><?php esc_html_e( 'Video Poster / Thumbnail URL', 'vitalstack' ); ?></label>
            <input type="url" name="tutorial_video_poster" id="tutorial_video_poster"
                   value="<?php echo esc_attr( $poster ); ?>"
                   placeholder="https://example.com/poster.jpg" />
            <p class="description"><?php esc_html_e( 'Shown before the video plays (optional — falls back to featured image).', 'vitalstack' ); ?></p>
        </div>
    </div>

    <script>
    (function(){
        var sel = document.getElementById('tutorial_video_type');
        var urlRow = document.getElementById('vs-url-row');
        var fileRow = document.getElementById('vs-file-row');
        function toggle(){
            var v = sel.value;
            urlRow.style.display  = (v === 'self_hosted') ? 'none' : 'block';
            fileRow.style.display = (v === 'self_hosted') ? 'grid' : 'none';
        }
        sel.addEventListener('change', toggle);
        toggle();
    })();
    </script>
    <?php
}

function vitalstack_tutorial_video_save( $post_id ) {
    if ( ! isset( $_POST['vitalstack_tutorial_video_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['vitalstack_tutorial_video_nonce'], 'vitalstack_tutorial_video_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = array(
        'tutorial_video_type'   => 'sanitize_key',
        'tutorial_video_url'    => 'esc_url_raw',
        'tutorial_video_file'   => 'esc_url_raw',
        'tutorial_video_poster' => 'esc_url_raw',
        'tutorial_duration'     => 'sanitize_text_field',
    );
    foreach ( $fields as $key => $sanitizer ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, call_user_func( $sanitizer, $_POST[ $key ] ) );
        }
    }
}
add_action( 'save_post_tutorials', 'vitalstack_tutorial_video_save' );


/* ─── Tutorial Video Embed Helper ────────────────────────────────────────── */
/**
 * Returns the video embed HTML for a tutorial post.
 *
 * @param  int    $post_id
 * @param  string $size    'full' (default) or 'card' (thumbnail only)
 * @return string          Safe HTML
 */
function vitalstack_tutorial_video( $post_id = null, $size = 'full' ) {
    if ( ! $post_id ) $post_id = get_the_ID();

    $type   = get_post_meta( $post_id, 'tutorial_video_type',   true ) ?: 'youtube';
    $url    = get_post_meta( $post_id, 'tutorial_video_url',    true );
    $file   = get_post_meta( $post_id, 'tutorial_video_file',   true );
    $poster = get_post_meta( $post_id, 'tutorial_video_poster', true );

    // Fallback poster to featured image
    if ( ! $poster && has_post_thumbnail( $post_id ) ) {
        $poster = get_the_post_thumbnail_url( $post_id, 'vitalstack-hero' );
    }

    ob_start();

    if ( $size === 'card' ) {
        // Card mode — just show thumbnail with play icon overlay
        echo '<div class="tut-card-thumb">';
        if ( $poster ) {
            echo '<img src="' . esc_url( $poster ) . '" alt="' . esc_attr( get_the_title( $post_id ) ) . '" class="tut-card-img" loading="lazy">';
        }
        echo '<div class="tut-play-overlay" aria-hidden="true"><span class="tut-play-icon">▶</span></div>';
        echo '</div>';
    } else {
        // Full embed mode
        echo '<div class="tut-video-wrap">';

        switch ( $type ) {

            case 'youtube':
                if ( $url ) {
                    // Extract video ID
                    $vid = '';
                    if ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $url, $m ) ) {
                        $vid = $m[1];
                    }
                    if ( $vid ) {
                        $thumb = $poster ?: 'https://img.youtube.com/vi/' . $vid . '/maxresdefault.jpg';
                        echo '<div class="tut-yt-facade" data-vid="' . esc_attr( $vid ) . '" style="background-image:url(' . esc_url( $thumb ) . ');" tabindex="0" role="button" aria-label="' . esc_attr__( 'Play video', 'vitalstack' ) . '">';
                        echo '<div class="tut-play-overlay"><span class="tut-play-icon">▶</span></div>';
                        echo '</div>';
                    }
                }
                break;

            case 'vimeo':
                if ( $url ) {
                    $embed = wp_oembed_get( $url, array( 'width' => 1200 ) );
                    if ( $embed ) echo '<div class="tut-oembed-wrap">' . $embed . '</div>';
                }
                break;

            case 'self_hosted':
                if ( $file ) {
                    $ext    = strtolower( pathinfo( parse_url( $file, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
                    $mime   = ( $ext === 'webm' ) ? 'video/webm' : 'video/mp4';
                    $poster_attr = $poster ? ' poster="' . esc_url( $poster ) . '"' : '';
                    echo '<video class="tut-self-video" controls playsinline preload="metadata"' . $poster_attr . '>';
                    echo '<source src="' . esc_url( $file ) . '" type="' . esc_attr( $mime ) . '">';
                    esc_html_e( 'Your browser does not support the video tag.', 'vitalstack' );
                    echo '</video>';
                }
                break;

            case 'oembed':
            default:
                if ( $url ) {
                    $embed = wp_oembed_get( $url, array( 'width' => 1200 ) );
                    if ( $embed ) echo '<div class="tut-oembed-wrap">' . $embed . '</div>';
                }
                break;
        }

        echo '</div><!-- /.tut-video-wrap -->';
    }

    return ob_get_clean();
}


/* ─── Tutorial Card Helper ───────────────────────────────────────────────── */
/**
 * Renders a single tutorial card for the page-tutorials.php grid.
 *
 * @param int $post_id
 */
function vitalstack_tutorial_card( $post_id = null ) {
    if ( ! $post_id ) $post_id = get_the_ID();
    $permalink = get_permalink( $post_id );
    $title     = get_the_title( $post_id );
    $excerpt   = get_the_excerpt( $post_id );
    $duration  = get_post_meta( $post_id, 'tutorial_duration', true );
    $vid_type  = get_post_meta( $post_id, 'tutorial_video_type', true ) ?: 'youtube';

    // Category
    $cats     = get_the_terms( $post_id, 'tutorial_category' );
    $cat_name = ( $cats && ! is_wp_error( $cats ) ) ? esc_html( $cats[0]->name ) : 'Tutorial';
    $cat_link = ( $cats && ! is_wp_error( $cats ) ) ? get_term_link( $cats[0] ) : '#';

    // Difficulty
    $diffs     = get_the_terms( $post_id, 'tutorial_difficulty' );
    $diff_name = ( $diffs && ! is_wp_error( $diffs ) ) ? esc_html( $diffs[0]->name ) : '';
    $diff_slug = ( $diffs && ! is_wp_error( $diffs ) ) ? sanitize_html_class( $diffs[0]->slug ) : '';

    // Video type icon
    $type_icons = array(
        'youtube'     => '▶ YouTube',
        'vimeo'       => '🎞 Vimeo',
        'self_hosted' => '📁 Video',
        'oembed'      => '🔗 Video',
    );
    $type_label = isset( $type_icons[ $vid_type ] ) ? $type_icons[ $vid_type ] : '▶ Video';
    ?>
    <article class="tut-card">
        <a href="<?php echo esc_url( $permalink ); ?>" class="tut-card-thumb-link" tabindex="-1" aria-hidden="true">
            <?php echo vitalstack_tutorial_video( $post_id, 'card' ); ?>
            <?php if ( $duration ) : ?>
                <span class="tut-duration-badge"><?php echo esc_html( $duration ); ?></span>
            <?php endif; ?>
        </a>
        <div class="tut-card-body">
            <div class="tut-card-meta">
                <a href="<?php echo esc_url( is_string( $cat_link ) ? $cat_link : '#' ); ?>" class="tag tag-tutorial"><?php echo $cat_name; ?></a>
                <?php if ( $diff_name ) : ?>
                    <span class="tut-difficulty tut-diff-<?php echo esc_attr( $diff_slug ); ?>"><?php echo $diff_name; ?></span>
                <?php endif; ?>
                <span class="tut-type-badge"><?php echo esc_html( $type_label ); ?></span>
            </div>
            <h3 class="tut-card-title">
                <a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
            </h3>
            <p class="tut-card-excerpt"><?php echo esc_html( $excerpt ); ?></p>
            <a class="read-more" href="<?php echo esc_url( $permalink ); ?>"><?php esc_html_e( 'Watch Tutorial →', 'vitalstack' ); ?></a>
        </div>
    </article>
    <?php
}

function vitalstack_custom_meta_tags() {
    if (is_front_page()) { // Apply only on homepage
        echo '<title>AI, Technology & Digital Health Platform | VitalStack Blogs, News & Tutorials</title>' . "\n";
        echo '<meta name="description" content="VitalStack is a modern knowledge platform for AI, technology, and digital health. Explore in-depth blogs, latest news, and beginner-friendly tutorials designed to simplify complex topics and help you stay ahead." />' . "\n";
    }
}
add_action('wp_head', 'vitalstack_custom_meta_tags', 1);



add_filter( 'get_the_archive_title', function ( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    }
    return $title;
});


//Nirav Code
// =============================================
// FIXED OTP NEWSLETTER (Simple + Working)
// =============================================

add_action('wp_ajax_send_otp', 'vitalstack_send_otp_simple');
add_action('wp_ajax_nopriv_send_otp', 'vitalstack_send_otp_simple');

function vitalstack_send_otp_simple() {
    $email = sanitize_email($_POST['email']);

    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email']);
    }

    $otp = rand(100000, 999999);
    set_transient('otp_' . md5($email), $otp, 900); // 15 min

    $subject = "Your OTP for VitalStack Newsletter";
    $message = "Your OTP is: <strong>$otp</strong><br><br>This code expires in 15 minutes.";

    if (wp_mail($email, $subject, $message)) {
        wp_send_json_success(['message' => 'OTP sent']);
    } else {
        wp_send_json_error(['message' => 'Failed to send email']);
    }
}

add_action('wp_ajax_verify_otp_subscribe', 'vitalstack_verify_otp_simple');
add_action('wp_ajax_nopriv_verify_otp_subscribe', 'vitalstack_verify_otp_simple');

function vitalstack_verify_otp_simple() {
    $email = sanitize_email($_POST['email']);
    $otp   = sanitize_text_field($_POST['otp']);

    $saved_otp = get_transient('otp_' . md5($email));

    if ($saved_otp && $saved_otp == $otp) {
        delete_transient('otp_' . md5($email));
        do_action('vitalstack_newsletter_subscribe', $email);
        wp_send_json_success(['message' => 'Subscribed successfully']);
    } else {
        wp_send_json_error(['message' => 'Invalid OTP']);
    }
}

// OTP Box Shortcode
function vitalstack_otp_newsletter_box() {
    ob_start();
    ?>
    <div class="stay-informed-otp-box">
        <h3>Stay Informed</h3>
        <p>AI and health insights — weekly, no spam.</p>

        <div id="email-step">
            <input type="email" id="otp-email" placeholder="your@email.com" required>
            <button onclick="sendOTPSimple()" class="subscribe-btn">Send OTP</button>
        </div>

        <div id="otp-step" style="display:none;">
            <input type="text" id="otp-code" placeholder="Enter 6-digit OTP" maxlength="6">
            <button onclick="verifyOTPSimple()" class="subscribe-btn">Verify & Subscribe</button>
            <button onclick="backToEmailSimple()" class="back-btn">← Back</button>
        </div>

        <div id="success-msg" style="display:none; color:#22c55e; font-weight:600; margin-top:15px;">
            ✅ Successfully subscribed!
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/* ═════════════════════════════════════════════════════════════════════════════
   CLEAN ARCHIVE TITLES — Removes "Archives:", "Category:", etc.
═════════════════════════════════════════════════════════════════════════════ */
function vitalstack_clean_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = get_the_author();
    } else {
        $title = str_replace( array( 'Archives: ', 'Category: ', 'Tag: ', 'Author: ' ), '', $title );
    }
    return trim( $title );
}
add_filter( 'get_the_archive_title', 'vitalstack_clean_archive_title' );

/* Optional: Also clean in breadcrumb if needed */
function vitalstack_clean_breadcrumb_title( $title ) {
    return str_replace( array( 'Archives: ', 'Category: ', 'Tag: ' ), '', $title );
}
add_filter( 'the_archive_title', 'vitalstack_clean_breadcrumb_title' );

add_shortcode('stay_informed_otp', 'vitalstack_otp_newsletter_box');