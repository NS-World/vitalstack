<?php
/**
 * Interview problems: the solution in four languages (4.17.0).
 *
 * Each interview problem lesson explains the idea in Python. Its main
 * solution now also comes in Java, C++ and JavaScript, as tabs:
 * Python | Java | C++ | JavaScript. The learner's choice is remembered, so
 * every problem opens in their language.
 *
 * The extra solutions live in data/solutions/<lesson-slug>/ (Main.java,
 * main.cpp, main.js). They are full programs with the same test calls as
 * the Python one, and tools/check_solutions.py compiles and runs all of them
 * and checks they print the same answers as the Python solution.
 * Because the code lives in the theme, it shows on lessons already on the
 * site without re-importing them.
 *
 * Without JavaScript the four solutions show one under another, each with
 * its language name; the script turns them into tabs.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/** Languages after Python: key => [file, label, highlight.js class]. */
function vitalstack_solution_langs() {
	return array(
		'java' => array( 'Main.java', 'Java', 'java' ),
		'cpp'  => array( 'main.cpp', 'C++', 'cpp' ),
		'js'   => array( 'main.js', 'JavaScript', 'javascript' ),
	);
}

/** The other-language solutions of a lesson, or an empty array. */
function vitalstack_solution_files( $slug ) {
	if ( ! is_string( $slug ) || ! preg_match( '/^[a-z0-9-]{1,80}$/', $slug ) ) {
		return array();
	}
	$dir = VITALSTACK_DIR . '/data/solutions/' . $slug;
	if ( ! is_dir( $dir ) ) {
		return array();
	}
	$out = array();
	foreach ( vitalstack_solution_langs() as $key => $l ) {
		$file = $dir . '/' . $l[0];
		if ( is_readable( $file ) ) {
			$code = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions -- local theme file
			if ( '' !== trim( $code ) ) {
				$out[ $key ] = rtrim( $code );
			}
		}
	}
	return $out;
}

/**
 * Offset of the Python solution block to put in tabs, or -1.
 *
 * The heading is "Solution", else the last "Solution N: …" (the best one),
 * else the first "Solution: …". The block is the first Python code block
 * after that heading (tools/check_solutions.py uses the same rule).
 */
function vitalstack_solution_block( $html, &$len ) {
	if ( ! preg_match_all( '#<h2\b[^>]*>(.*?)</h2>#is', $html, $m, PREG_OFFSET_CAPTURE ) ) {
		return -1;
	}
	$exact = $numbered = $any = null;
	foreach ( $m[1] as $i => $h ) {
		$text = trim( html_entity_decode( wp_strip_all_tags( $h[0] ), ENT_QUOTES, 'UTF-8' ) );
		if ( ! preg_match( '/^Solution\b/', $text ) ) {
			continue;
		}
		$end = $m[0][ $i ][1] + strlen( $m[0][ $i ][0] );
		if ( 'Solution' === $text && null === $exact ) {
			$exact = $end;
		} elseif ( preg_match( '/^Solution \d/', $text ) ) {
			$numbered = $end;
		} elseif ( null === $any ) {
			$any = $end;
		}
	}
	$from = null !== $exact ? $exact : ( null !== $numbered ? $numbered : $any );
	if ( null === $from ) {
		return -1;
	}
	if ( ! preg_match( '#<pre\b[^>]*>\s*<code\b[^>]*\blanguage-python\b[^>]*>.*?</code>\s*</pre>#is', $html, $pm, PREG_OFFSET_CAPTURE, $from ) ) {
		return -1;
	}
	// Only if no other heading sits between the solution heading and the block.
	if ( preg_match( '#<h[1-3]\b#i', substr( $html, $from, $pm[0][1] - $from ) ) ) {
		return -1;
	}
	$len = strlen( $pm[0][0] );
	return $pm[0][1];
}

function vitalstack_solution_tabs_html( $python_pre, $files ) {
	static $n = 0;
	++$n;
	$id    = 'sol' . $n;
	$langs = vitalstack_solution_langs();
	$h     = '<div class="sol-tabs" data-sol-tabs id="' . esc_attr( $id ) . '">';
	$h    .= '<p class="sol-note">🌐 ' . esc_html__( 'This solution in Python, Java, C++ and JavaScript, tested to give the same answers:', 'vitalstack' ) . '</p>';
	$h    .= '<div class="sol-panel" data-sol-lang="python" data-sol-label="Python"><p class="sol-lab">Python</p>' . $python_pre . '</div>';
	foreach ( $files as $key => $code ) {
		$h .= '<div class="sol-panel" data-sol-lang="' . esc_attr( $key ) . '" data-sol-label="' . esc_attr( $langs[ $key ][1] ) . '"><p class="sol-lab">' . esc_html( $langs[ $key ][1] ) . '</p>'
			. '<pre class="wp-block-code"><code class="language-' . esc_attr( $langs[ $key ][2] ) . '">' . esc_html( $code ) . '</code></pre></div>';
	}
	return $h . '</div>';
}

function vitalstack_solution_tabs_filter( $content ) {
	if ( is_feed() || 'tutorials' !== get_post_type() || false === strpos( $content, 'language-python' ) ) {
		return $content;
	}
	$post = get_post();
	$path = function_exists( 'vitalstack_tutorial_path' ) && $post ? vitalstack_tutorial_path( $post->ID ) : null;
	if ( ! $path || 'dsa-problems' !== $path->slug ) {
		return $content;
	}
	$files = vitalstack_solution_files( $post->post_name );
	if ( ! $files ) {
		return $content;
	}
	$len = 0;
	$at  = vitalstack_solution_block( $content, $len );
	if ( $at < 0 ) {
		return $content;
	}
	return substr( $content, 0, $at ) . vitalstack_solution_tabs_html( substr( $content, $at, $len ), $files ) . substr( $content, $at + $len );
}
// After the premium gate (99): a locked preview has no solution block, so nothing changes there.
add_filter( 'the_content', 'vitalstack_solution_tabs_filter', 101 );

function vitalstack_solution_tabs_assets() {
	if ( ! is_singular( 'tutorials' ) || ! function_exists( 'vitalstack_tutorial_path' ) ) {
		return;
	}
	$path = vitalstack_tutorial_path();
	if ( $path && 'dsa-problems' === $path->slug ) {
		wp_enqueue_script( 'vitalstack-solution-tabs', vitalstack_asset_url( 'assets/js/solution-tabs.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'vitalstack_solution_tabs_assets', 35 );
