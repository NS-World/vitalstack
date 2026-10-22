<?php
/**
 * Mock tests (a timed test per learning path, drawn from its lesson quizzes)
 * and roadmaps (career plans that link the paths, tests and cheat sheets).
 *
 * Both are theme-managed pages (see inc/site-pages.php): data/pages/index.php
 * lists them, and their intro text lives in data/pages/<slug>.html.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

const VITALSTACK_MOCK_QUESTIONS = 20;

/**
 * Learning path term by slug, or null.
 */
function vitalstack_path_term( $slug ) {
	$term = get_term_by( 'slug', sanitize_key( $slug ), 'tutorial_category' );
	return $term && ! is_wp_error( $term ) && ! $term->parent ? $term : null;
}

/**
 * Every quiz question of a path, each tagged with its lesson slug ("l").
 *
 * @return array<int, array{q:string,o:string[],a:int,e:string,l:string}>
 */
function vitalstack_mock_questions( $path_slug ) {
	$out = array();
	foreach ( vitalstack_path_quizzes( $path_slug ) as $lesson => $questions ) {
		foreach ( $questions as $q ) {
			$q['l'] = $lesson;
			$out[]  = $q;
		}
	}
	return $out;
}

/**
 * Published lessons of a path in order: slug => array( title, url ).
 */
function vitalstack_mock_lessons( $path_slug ) {
	$term = vitalstack_path_term( $path_slug );
	$out  = array();
	if ( ! $term ) {
		return $out;
	}
	foreach ( vitalstack_path_lessons( $term ) as $id ) {
		if ( 'publish' !== get_post_status( $id ) ) {
			continue;
		}
		$out[ get_post_field( 'post_name', $id ) ] = array(
			't' => html_entity_decode( get_the_title( $id ), ENT_QUOTES, 'UTF-8' ),
			'u' => get_permalink( $id ),
		);
	}
	return $out;
}

/**
 * The theme-managed page of a given hub that belongs to a path, e.g. the
 * Java mock test or the Java cheat sheet. Returns its URL or ''.
 */
function vitalstack_path_site_page_url( $parent, $path_slug ) {
	foreach ( vitalstack_site_pages_in( $parent ) as $slug => $p ) {
		// Mock tests name their path; cheat sheets are named after it ("java-cheat-sheet", "dsa-big-o-cheat-sheet").
		$match = 'cheat-sheets' === $parent
			? in_array( $slug, array( $path_slug . '-cheat-sheet', $path_slug . '-big-o-cheat-sheet' ), true )
			: ( isset( $p['path'] ) && $p['path'] === $path_slug );
		if ( $match ) {
			return vitalstack_site_page_url( $slug );
		}
	}
	return '';
}

/**
 * Path of the mock test page being viewed, or ''.
 */
function vitalstack_mock_current_path() {
	if ( ! is_page_template( 'page-mock-test.php' ) ) {
		return '';
	}
	$pages = vitalstack_site_pages();
	$slug  = get_post_field( 'post_name', get_queried_object_id() );
	return isset( $pages[ $slug ]['path'] ) ? $pages[ $slug ]['path'] : '';
}

/**
 * A fixed set of sample questions shown on the page (one per lesson, in
 * lesson order), so the page has readable content and stable search results.
 */
function vitalstack_mock_samples( $path_slug, $max = 10 ) {
	$out = array();
	foreach ( vitalstack_path_quizzes( $path_slug ) as $questions ) {
		if ( $questions ) {
			$out[] = $questions[0];
		}
		if ( count( $out ) >= $max ) {
			break;
		}
	}
	return $out;
}

/**
 * Data for the test app (printed as JSON on the page).
 */
function vitalstack_mock_data( $path_slug ) {
	$term      = vitalstack_path_term( $path_slug );
	$questions = vitalstack_mock_questions( $path_slug );
	$count     = min( VITALSTACK_MOCK_QUESTIONS, count( $questions ) );
	return array(
		'path'      => $path_slug,
		'name'      => $term ? $term->name : $path_slug,
		'count'     => $count,
		'minutes'   => $count,
		'questions' => $questions,
		'lessons'   => vitalstack_mock_lessons( $path_slug ),
		'url'       => get_permalink(),
		'title'     => get_the_title(),
	);
}

function vitalstack_mock_assets() {
	if ( vitalstack_mock_current_path() ) {
		wp_enqueue_script( 'vitalstack-mocktest', vitalstack_asset_url( 'assets/js/mocktest.js' ), array( 'vitalstack-fun' ), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'vitalstack_mock_assets', 25 );

/**
 * schema.org Quiz for the sample questions on a mock test page.
 */
function vitalstack_mock_schema() {
	$path = vitalstack_mock_current_path();
	if ( ! $path ) {
		return;
	}
	$clean = function ( $s ) {
		return wp_strip_all_tags( str_replace( '`', '', $s ) );
	};
	$parts = array();
	foreach ( vitalstack_mock_samples( $path ) as $q ) {
		$answer = array(
			'@type' => 'Answer',
			'text'  => $clean( $q['o'][ $q['a'] ] ),
		);
		if ( ! empty( $q['e'] ) ) {
			$answer['answerExplanation'] = array(
				'@type' => 'Comment',
				'text'  => $clean( $q['e'] ),
			);
		}
		$parts[] = array(
			'@type'           => 'Question',
			'eduQuestionType' => 'Multiple choice',
			'name'            => $clean( $q['q'] ),
			'text'            => $clean( $q['q'] ),
			'acceptedAnswer'  => $answer,
		);
	}
	if ( ! $parts ) {
		return;
	}
	$term = vitalstack_path_term( $path );
	$data = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Quiz',
		'name'             => get_the_title( get_queried_object() ),
		'about'            => array(
			'@type' => 'Thing',
			'name'  => $term ? $term->name : $path,
		),
		'educationalLevel' => 'beginner',
		'hasPart'          => $parts,
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
}
add_action( 'wp_head', 'vitalstack_mock_schema', 20 );

/**
 * XP for a finished mock test: per correct answer, once per path per day.
 */
function vitalstack_mock_xp( $gained, $type, $ref, $score, $user_id ) {
	if ( 'mock' !== $type || ! $ref ) {
		return $gained;
	}
	$path = sanitize_key( $ref );
	$max  = min( VITALSTACK_MOCK_QUESTIONS, count( vitalstack_mock_questions( $path ) ) );
	if ( ! $max ) {
		return $gained;
	}
	$score = max( 0, min( $max, (int) $score ) );
	return $gained + vitalstack_xp_award( $user_id, $score * VITALSTACK_XP_QUIZ_PER_ANSWER, 'mock:' . $path . ':' . vitalstack_xp_today() );
}
add_filter( 'vitalstack_xp_activity', 'vitalstack_mock_xp', 10, 5 );

/* ── Roadmaps ─────────────────────────────────────────────────────────── */

/**
 * Card for one learning path inside a roadmap: lesson count, progress
 * (painted by main.js from the reader's saved progress) and quick links.
 */
function vitalstack_roadmap_path_card( $path_slug ) {
	$term = vitalstack_path_term( $path_slug );
	if ( ! $term ) {
		return '';
	}
	$lessons = vitalstack_path_lessons( $term );
	$total   = count( array_filter( $lessons, function ( $id ) { return 'publish' === get_post_status( $id ); } ) );
	$links   = array();
	if ( $total ) {
		$links[] = '<a class="btn btn-primary btn-sm path-start" data-resume-label="' . esc_attr__( 'Continue', 'vitalstack' ) . '" href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html__( 'Start lessons', 'vitalstack' ) . ' ❯</a>';
	}
	$test = vitalstack_path_site_page_url( 'mock-tests', $path_slug );
	if ( $test ) {
		$links[] = '<a class="btn btn-ghost btn-sm" href="' . esc_url( $test ) . '">' . esc_html__( 'Mock test', 'vitalstack' ) . '</a>';
	}
	$sheet = vitalstack_path_site_page_url( 'cheat-sheets', $path_slug );
	if ( $sheet ) {
		$links[] = '<a class="btn btn-ghost btn-sm" href="' . esc_url( $sheet ) . '">' . esc_html__( 'Cheat sheet', 'vitalstack' ) . '</a>';
	}
	$count = $total
		/* translators: %d: number of lessons */
		? sprintf( _n( '%d lesson', '%d lessons', $total, 'vitalstack' ), $total )
		: __( 'Lessons coming soon', 'vitalstack' );

	return '<div class="rm-path" data-path="' . esc_attr( $term->slug ) . '" data-total="' . (int) $total . '">'
		. '<div class="rm-path-head"><b class="rm-path-name">' . esc_html( $term->name ) . '</b>'
		. '<span class="rm-path-count">' . esc_html( $count ) . ' <span class="path-block-done" hidden></span></span></div>'
		. '<div class="path-progress"><span class="path-progress-bar" style="width:0"></span></div>'
		. ( $links ? '<div class="rm-path-links">' . implode( '', $links ) . '</div>' : '' )
		. '</div>';
}

/**
 * Replaces [[path:slug]] markers in roadmap content with path cards.
 */
function vitalstack_roadmap_render( $html ) {
	return preg_replace_callback(
		'#(?:<p>\s*)?\[\[path:([a-z0-9-]+)\]\](?:\s*</p>)?#',
		function ( $m ) {
			return vitalstack_roadmap_path_card( $m[1] );
		},
		$html
	);
}
