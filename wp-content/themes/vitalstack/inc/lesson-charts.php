<?php
/**
 * Charts and diagrams in the data science lessons (4.18.0).
 *
 * Statistics, Machine Learning, EDA, ML in Depth, Time Series, Deep
 * Learning, NumPy and pandas lessons each gained a "See it as a chart"
 * section: a real chart drawn by the lesson's own tested Python code. The
 * four shortest interview problems gained a "Common mistakes and edge cases"
 * section the same way.
 *
 * The sections live in data/lesson-charts.php (built from the lesson files
 * by tools/build_lesson_charts.py). This filter adds each one to its lesson,
 * before the heading it belongs above (normally "Summary"), so lessons that
 * were imported before the charts existed get them straight away. A lesson
 * that already shows the figure (imported later, or edited by hand) is left
 * as it is, so nothing is ever doubled. The database is never changed.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_lesson_charts() {
	static $c = null;
	if ( null === $c ) {
		$file = VITALSTACK_DIR . '/data/lesson-charts.php';
		$c    = file_exists( $file ) ? (array) include $file : array();
	}
	return $c;
}

/**
 * The lesson being rendered. The protected premium lesson API renders a
 * lesson with setup_postdata(), which fires "the_post" but does not set the
 * global post, so the post is remembered from that action too.
 */
function vitalstack_lesson_charts_post( $set = null ) {
	static $current = null;
	if ( $set instanceof WP_Post ) {
		$current = $set;
	}
	return $current;
}
add_action(
	'the_post',
	function ( $post ) {
		vitalstack_lesson_charts_post( $post );
	}
);

/** Add the missing chart sections to one lesson's raw block content. */
function vitalstack_lesson_charts_apply( $content, $slug ) {
	$items = vitalstack_lesson_charts()[ $slug ] ?? array();
	foreach ( $items as $item ) {
		if ( false !== strpos( $content, $item['figure'] ) ) {
			continue; // already in the lesson ('figure' is the chart file, or a text section's heading)
		}
		$at = false;
		if ( '' !== $item['before'] && preg_match( '#<!-- wp:heading[^>]*-->\s*<h2\b[^>]*>\s*' . preg_quote( esc_html( $item['before'] ), '#' ) . '\s*</h2>#', $content, $m, PREG_OFFSET_CAPTURE ) ) {
			$at = $m[0][1];
		} elseif ( '' !== $item['before'] && preg_match( '#<h2\b[^>]*>\s*' . preg_quote( esc_html( $item['before'] ), '#' ) . '\s*</h2>#', $content, $m, PREG_OFFSET_CAPTURE ) ) {
			$at = $m[0][1];
		}
		$block   = $item['html'] . "\n\n";
		$content = false === $at ? rtrim( $content ) . "\n\n" . $block : substr( $content, 0, $at ) . $block . substr( $content, $at );
	}
	return $content;
}

function vitalstack_lesson_charts_filter( $content ) {
	if ( is_feed() ) {
		return $content;
	}
	$post = get_post();
	$seen = vitalstack_lesson_charts_post();
	if ( ! empty( $GLOBALS['vitalstack_protect_raw'] ) && $seen ) {
		$post = $seen; // the protected lesson API
	}
	if ( ! $post || 'tutorials' !== $post->post_type || ! isset( vitalstack_lesson_charts()[ $post->post_name ] ) ) {
		return $content;
	}
	return vitalstack_lesson_charts_apply( $content, $post->post_name );
}
// Priority 8: before blocks (9) and shortcodes (11) render, so the sections are rendered exactly like the rest of the lesson.
add_filter( 'the_content', 'vitalstack_lesson_charts_filter', 8 );
