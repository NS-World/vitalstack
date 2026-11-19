<?php
/**
 * URLs: /ai/, /ai/library/, /ai/commands/, /ai/prompts/, /ai/motion/,
 * and /ai/motion/view/<slug>/ (full-screen preview).
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

/** The sections, in menu order: slug => [label, short label, icon, one-line description]. */
function vsai_sections() {
	return array(
		'library'  => array( __( 'AI Library', 'vitalstack-ai-hub' ), __( 'Library', 'vitalstack-ai-hub' ), '📚', __( 'Books, guides and free courses to learn AI, from zero to building agents.', 'vitalstack-ai-hub' ) ),
		'commands' => array( __( 'AI Commands & Shortcuts', 'vitalstack-ai-hub' ), __( 'Commands', 'vitalstack-ai-hub' ), '⌨️', __( 'Keyboard shortcuts, parameters and power moves for ChatGPT, Claude, Gemini, Copilot, Cursor, Midjourney and more.', 'vitalstack-ai-hub' ) ),
		'prompts'  => array( __( 'Prompt Bazaar', 'vitalstack-ai-hub' ), __( 'Prompts', 'vitalstack-ai-hub' ), '🧠', __( 'Ready-to-use prompts for every job, with the tool to use and the kind of result you get.', 'vitalstack-ai-hub' ) ),
		'motion'   => array( __( 'Motion Studio', 'vitalstack-ai-hub' ), __( 'Motion', 'vitalstack-ai-hub' ), '✨', __( 'Animated website sections with live previews: copy the code, or the prompt to build it with AI.', 'vitalstack-ai-hub' ) ),
	);
}

function vsai_url( $section = '' ) {
	return home_url( '/ai/' . ( $section ? $section . '/' : '' ) );
}

function vsai_register_routes() {
	add_rewrite_rule( '^ai/?$', 'index.php?vsai=home', 'top' );
	add_rewrite_rule( '^ai/(library|commands|prompts|motion|pricing)/?$', 'index.php?vsai=$matches[1]', 'top' );
	add_rewrite_rule( '^ai/motion/view/([a-z0-9-]{1,60})/?$', 'index.php?vsai=view&vsai_item=$matches[1]', 'top' );
}
add_action( 'init', 'vsai_register_routes' );

add_filter(
	'query_vars',
	function ( $vars ) {
		$vars[] = 'vsai';
		$vars[] = 'vsai_item';
		return $vars;
	}
);

/** New install or update: make WordPress learn the URLs once. */
add_action(
	'init',
	function () {
		if ( get_option( 'vsai_rewrite_version' ) !== VSAI_VERSION ) {
			flush_rewrite_rules( false );
			update_option( 'vsai_rewrite_version', VSAI_VERSION, false );
		}
	},
	99
);

function vsai_current() {
	$v = (string) get_query_var( 'vsai' );
	return in_array( $v, array( 'home', 'library', 'commands', 'prompts', 'motion', 'pricing', 'view' ), true ) ? $v : '';
}

/** Our own page template; the rest of the site is untouched. */
add_filter(
	'template_include',
	function ( $template ) {
		if ( ! vsai_current() ) {
			return $template;
		}
		global $wp_query;
		$wp_query->is_404  = false;
		$wp_query->is_home = false;
		status_header( 200 );
		if ( is_user_logged_in() ) {
			nocache_headers(); // members see premium items: never cache their copy
		}
		return VSAI_DIR . 'includes/template.php';
	},
	99
);

/** Page titles and descriptions. */
function vsai_meta() {
	$s    = vsai_sections();
	$page = vsai_current();
	if ( 'home' === $page ) {
		return array( __( 'VitalStack AI Hub: library, prompts, commands and motion code', 'vitalstack-ai-hub' ), __( 'Everything AI in one place: an AI library of books and guides, a prompt bazaar for every tool, AI commands and shortcuts, and animated website sections with code. Free and premium.', 'vitalstack-ai-hub' ) );
	}
	if ( 'pricing' === $page ) {
		return array( __( 'AI Pro pricing – VitalStack AI Hub', 'vitalstack-ai-hub' ), __( 'Unlimited access to every premium prompt, motion design, code and AI guide. Monthly, yearly or lifetime. Pay in ₹ or $.', 'vitalstack-ai-hub' ) );
	}
	if ( isset( $s[ $page ] ) ) {
		return array( $s[ $page ][0] . ' – VitalStack AI Hub', $s[ $page ][3] );
	}
	return array( __( 'Motion preview – VitalStack AI Hub', 'vitalstack-ai-hub' ), '' );
}

add_filter(
	'pre_get_document_title',
	function ( $title ) {
		return vsai_current() ? vsai_meta()[0] : $title;
	},
	99
);

add_filter(
	'wp_robots',
	function ( $robots ) {
		if ( 'view' === vsai_current() ) {
			$robots['noindex'] = true; // previews are for people, not search results
		}
		return $robots;
	}
);

/** The hub's pages in the XML sitemap. */
add_action(
	'init',
	function () {
		if ( ! class_exists( 'WP_Sitemaps_Provider' ) ) {
			return;
		}
		require_once VSAI_DIR . 'includes/sitemap.php';
		wp_register_sitemap_provider( 'aihub', new VSAI_Sitemap() );
	},
	20
);
