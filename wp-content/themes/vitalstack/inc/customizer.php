<?php
/**
 * Customizer: a small set of options. Colours and fonts live in style.css.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vitalstack_customize_register( $wp_customize ) {

	/* ── Homepage ── */
	$wp_customize->add_section(
		'vitalstack_homepage',
		array(
			'title'    => __( 'VitalStack: Homepage', 'vitalstack' ),
			'priority' => 30,
		)
	);
	$home_fields = array(
		'vitalstack_home_title'  => array( __( 'Hero heading', 'vitalstack' ), 'text', vitalstack_default( 'home_title' ) ),
		'vitalstack_home_desc'   => array( __( 'Hero description', 'vitalstack' ), 'textarea', vitalstack_default( 'home_desc' ) ),
	);
	foreach ( $home_fields as $id => $f ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $f[2],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $f[0],
				'section' => 'vitalstack_homepage',
				'type'    => $f[1],
			)
		);
	}

	/* ── Content focus ── */
	$wp_customize->add_section(
		'vitalstack_focus',
		array(
			'title'       => __( 'VitalStack: Content Focus', 'vitalstack' ),
			'priority'    => 31,
			'description' => __( 'Controls for the old News section while the site focuses on AI & coding.', 'vitalstack' ),
		)
	);
	$wp_customize->add_setting(
		'vitalstack_hide_news',
		array(
			'default'           => false,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);
	$wp_customize->add_control(
		'vitalstack_hide_news',
		array(
			'label'       => __( 'Hide News from homepage, search and related posts', 'vitalstack' ),
			'section'     => 'vitalstack_focus',
			'type'        => 'checkbox',
		)
	);
	$wp_customize->add_setting(
		'vitalstack_noindex_news',
		array(
			'default'           => false,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);
	$wp_customize->add_control(
		'vitalstack_noindex_news',
		array(
			'label'       => __( 'Ask Google not to index News pages (noindex)', 'vitalstack' ),
			'description' => __( 'Use this for thin or rewritten news items so they stop counting against site quality. Pages stay reachable for visitors.', 'vitalstack' ),
			'section'     => 'vitalstack_focus',
			'type'        => 'checkbox',
		)
	);

	/* ── Accounts & email ── */
	$wp_customize->add_section(
		'vitalstack_members',
		array(
			'title'       => __( 'VitalStack: Accounts & Email', 'vitalstack' ),
			'priority'    => 31,
			'description' => __( 'Reader accounts live at /account/. Emails need working mail delivery: install an SMTP plugin (see the setup guide).', 'vitalstack' ),
		)
	);
	$member_fields = array(
		'vitalstack_accounts_enabled' => __( 'Allow readers to create accounts', 'vitalstack' ),
		'vitalstack_notify_enabled'   => __( 'Email subscribers when a new post or tutorial is published', 'vitalstack' ),
	);
	foreach ( $member_fields as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'vitalstack_members',
				'type'    => 'checkbox',
			)
		);
	}

	/* ── Header & footer ── */
	$wp_customize->add_section(
		'vitalstack_layout',
		array(
			'title'    => __( 'VitalStack: Header & Footer', 'vitalstack' ),
			'priority' => 32,
		)
	);
	$text_fields = array(
		'vitalstack_header_cta_label' => array( __( 'Header button label (empty = hidden)', 'vitalstack' ), __( 'Start learning', 'vitalstack' ), 'sanitize_text_field' ),
		'vitalstack_header_cta_url'   => array( __( 'Header button URL', 'vitalstack' ), '', 'esc_url_raw' ),
		'vitalstack_footer_tagline'   => array( __( 'Footer tagline', 'vitalstack' ), vitalstack_default( 'footer_tagline' ), 'sanitize_text_field' ),
	);
	foreach ( $text_fields as $id => $f ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $f[1],
				'sanitize_callback' => $f[2],
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $f[0],
				'section' => 'vitalstack_layout',
				'type'    => 'text',
			)
		);
	}

	/* ── Social links (keys unchanged from v1) ── */
	foreach ( vitalstack_social_networks() as $id => $label ) {
		$wp_customize->add_setting(
			'vitalstack_social_' . $id,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			'vitalstack_social_' . $id,
			array(
				/* translators: %s: social network name */
				'label'   => sprintf( __( '%s URL', 'vitalstack' ), $label ),
				'section' => 'vitalstack_layout',
				'type'    => 'url',
			)
		);
	}

	/* ── Contact page (keys unchanged from v1) ── */
	$wp_customize->add_section(
		'vitalstack_contact',
		array(
			'title'    => __( 'VitalStack: Contact Page', 'vitalstack' ),
			'priority' => 33,
		)
	);
	$contact_fields = array(
		'vitalstack_cf7_form_id'   => array( __( 'Contact Form 7 form ID', 'vitalstack' ), 'absint' ),
		'vitalstack_email_general' => array( __( 'Contact email', 'vitalstack' ), 'sanitize_email' ),
	);
	foreach ( $contact_fields as $id => $f ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => '',
				'sanitize_callback' => $f[1],
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $f[0],
				'section' => 'vitalstack_contact',
				'type'    => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'vitalstack_customize_register' );

function vitalstack_social_networks() {
	return array(
		'youtube'   => 'YouTube',
		'instagram' => 'Instagram',
		'linkedin'  => 'LinkedIn',
		'facebook'  => 'Facebook',
		'x'         => 'X (Twitter)',
		'github'    => 'GitHub',
	);
}

/**
 * Default copy, in one place.
 */
function vitalstack_default( $key ) {
	$defaults = array(
		'home_title'     => __( 'Learn to Code', 'vitalstack' ),
		'home_desc'      => __( 'Free, step-by-step tutorials for HTML, CSS, JavaScript, SQL, Java and AI, written for complete beginners.', 'vitalstack' ),
		'footer_tagline' => __( 'Clear, practical guides to AI and programming for beginners.', 'vitalstack' ),
	);
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
}
