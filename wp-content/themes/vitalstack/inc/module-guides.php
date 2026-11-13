<?php
/**
 * Module guides on premium lessons (4.15.0).
 *
 * Every premium lesson opens with a "Module guide" card: a short history of
 * the tool or idea, how it works step by step, where to run it, and what it
 * is used for at work. It is open on the module's first lesson and folded
 * (one tap to open) on the others. It is shown to everyone, including
 * readers without access, as a preview of what the module covers.
 *
 * The content lives in data/module-guides.php, so it reaches lessons that
 * are already on the site without re-importing them.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_module_guides() {
	static $g = null;
	if ( null === $g ) {
		$file = VITALSTACK_DIR . '/data/module-guides.php';
		$g    = file_exists( $file ) ? (array) include $file : array();
	}
	return $g;
}

/** Title of a module (path) inside a premium track, from the track list. */
function vitalstack_module_guide_title( $path_slug, $track ) {
	$tracks = function_exists( 'vitalstack_premium_tracks' ) ? vitalstack_premium_tracks() : array();
	foreach ( (array) ( $tracks[ $track ]['modules'] ?? array() ) as $m ) {
		if ( ( $m['path'] ?? '' ) === $path_slug ) {
			return $m['title'];
		}
	}
	return '';
}

function vitalstack_module_guide_html( $path_slug, $track, $open = false ) {
	$g = vitalstack_module_guides()[ $path_slug ] ?? null;
	if ( ! $g ) {
		return '';
	}
	$title = vitalstack_module_guide_title( $path_slug, $track );
	$h     = '<details class="mg"' . ( $open ? ' open' : '' ) . '>';
	$h    .= '<summary class="mg-sum"><span class="mg-badge">⭐ ' . esc_html__( 'Module guide', 'vitalstack' ) . '</span>'
		. '<span class="mg-title">' . esc_html( $title ? $title : __( 'This module at a glance', 'vitalstack' ) ) . '</span>'
		. '<span class="mg-hint">' . esc_html( ! empty( $g['history'] ) ? __( 'History · How it works · Where to run it', 'vitalstack' ) : __( 'How it works · Where to run it', 'vitalstack' ) ) . '</span></summary>';
	$h    .= '<div class="mg-body">';
	if ( ! empty( $g['history'] ) ) {
		$h .= '<section class="mg-col mg-history"><p class="mg-h">📜 ' . esc_html__( 'A short history', 'vitalstack' ) . '</p><ol class="mg-time">';
		foreach ( $g['history'] as $e ) {
			$h .= '<li><b>' . esc_html( $e[0] ) . '</b><span>' . esc_html( $e[1] ) . '</span></li>';
		}
		$h .= '</ol></section>';
	}
	$h .= '<section class="mg-col mg-flowcol"><p class="mg-h">⚙️ ' . esc_html__( 'How it works', 'vitalstack' ) . '</p><ol class="mg-flow">';
	foreach ( (array) ( $g['flow'] ?? array() ) as $i => $step ) {
		$h .= '<li><span class="mg-n">' . (int) ( $i + 1 ) . '</span><span>' . esc_html( $step ) . '</span></li>';
	}
	$h .= '</ol></section>';
	$h .= '<section class="mg-col mg-facts">';
	if ( ! empty( $g['run'] ) ) {
		$h .= '<p class="mg-h">🧰 ' . esc_html__( 'Where to run it', 'vitalstack' ) . '</p><p>' . esc_html( $g['run'] ) . '</p>';
		$term  = get_term_by( 'slug', 'ds-setup', 'tutorial_category' );
		if ( $term && ! is_wp_error( $term ) ) {
			$link = get_term_link( $term );
			if ( ! is_wp_error( $link ) ) {
				$h .= '<p class="mg-small"><a href="' . esc_url( $link ) . '">' . esc_html__( 'New to Jupyter or Colab? Set up in 15 minutes', 'vitalstack' ) . ' →</a></p>';
			}
		}
	}
	if ( ! empty( $g['used'] ) ) {
		$h .= '<p class="mg-h">💼 ' . esc_html__( 'Used at work for', 'vitalstack' ) . '</p><p>' . esc_html( $g['used'] ) . '</p>';
	}
	$h .= '</section></div></details>';
	return $h;
}

/** Whether this lesson is the first of its learning path. */
function vitalstack_module_guide_is_first( $post_id, $path ) {
	if ( ! function_exists( 'vitalstack_path_lessons' ) ) {
		return false;
	}
	$lessons = vitalstack_path_lessons( $path );
	if ( ! $lessons ) {
		return false;
	}
	$first = reset( $lessons );
	return (int) ( is_object( $first ) ? $first->ID : $first ) === (int) $post_id;
}

add_filter(
	'the_content',
	function ( $content ) {
		if ( ! is_singular( 'tutorials' ) || ! in_the_loop() || ! is_main_query() || ! function_exists( 'vitalstack_lesson_track' ) ) {
			return $content;
		}
		$track = vitalstack_lesson_track();
		$path  = $track && function_exists( 'vitalstack_tutorial_path' ) ? vitalstack_tutorial_path() : null;
		if ( ! $path ) {
			return $content;
		}
		$card = vitalstack_module_guide_html( $path->slug, $track, vitalstack_module_guide_is_first( get_the_ID(), $path ) );
		return $card ? $card . $content : $content;
	},
	100 // after the premium gate (99), so readers with and without access see it
);
