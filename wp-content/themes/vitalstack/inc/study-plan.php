<?php
/**
 * "Data Science in 30 Days" study plan (4.16.0) at /study-plan/.
 *
 * A day-by-day plan through the site's own lessons, for learners without a
 * mentor: setup and Python, NumPy and pandas, charts, then statistics,
 * machine learning and a first project. Lessons are spread over the days
 * automatically, so new lessons fit in without editing the plan. Lessons a
 * reader has completed get a tick (the same progress the lessons use, on
 * this device and synced to the account). Premium lessons are marked, with
 * a way to unlock them.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_study_plan_url() {
	return home_url( '/study-plan/' );
}

add_action(
	'init',
	function () {
		add_rewrite_rule( '^study-plan/?$', 'index.php?vs_plan=1', 'top' );
	}
);
add_filter(
	'query_vars',
	function ( $vars ) {
		$vars[] = 'vs_plan';
		return $vars;
	}
);
add_filter(
	'pre_handle_404',
	function ( $handled ) {
		return get_query_var( 'vs_plan' ) ? true : $handled;
	}
);
// The new rule needs one rewrite flush after the theme update.
add_action(
	'init',
	function () {
		if ( get_option( 'vs_plan_rules' ) !== VITALSTACK_VERSION ) {
			flush_rewrite_rules( false );
			update_option( 'vs_plan_rules', VITALSTACK_VERSION, false );
		}
	},
	99
);

/** The plan: weeks of days; each day names a path (or a practice day). */
function vitalstack_study_plan_spec() {
	return apply_filters(
		'vitalstack_study_plan_spec',
		array(
			array(
				'title' => __( 'Week 1: Set up and learn Python', 'vitalstack' ),
				'goal'  => __( 'Run code in Jupyter or Colab and write small Python programs.', 'vitalstack' ),
				'days'  => array(
					array( 'path' => 'ds-setup', 'title' => __( 'Your toolkit and Anaconda', 'vitalstack' ) ),
					array( 'path' => 'ds-setup', 'title' => __( 'Colab and notebook skills', 'vitalstack' ) ),
					array( 'path' => 'python', 'title' => __( 'Python first steps', 'vitalstack' ) ),
					array( 'path' => 'python', 'title' => __( 'Strings, numbers and lists', 'vitalstack' ) ),
					array( 'path' => 'python', 'title' => __( 'Dictionaries, if and loops', 'vitalstack' ) ),
					array( 'path' => 'python', 'title' => __( 'Functions, modules and files', 'vitalstack' ) ),
					array( 'path' => 'python', 'title' => __( 'Classes, packages and a project', 'vitalstack' ) ),
				),
			),
			array(
				'title' => __( 'Week 2: Numbers and tables', 'vitalstack' ),
				'goal'  => __( 'Load a CSV, clean it, and answer questions with pandas.', 'vitalstack' ),
				'days'  => array(
					array( 'path' => 'numpy', 'title' => __( 'NumPy arrays', 'vitalstack' ) ),
					array( 'path' => 'numpy', 'title' => __( 'NumPy maths and statistics', 'vitalstack' ) ),
					array( 'path' => 'pandas', 'title' => __( 'pandas: reading and selecting', 'vitalstack' ) ),
					array( 'path' => 'pandas', 'title' => __( 'pandas: filtering and cleaning', 'vitalstack' ) ),
					array( 'path' => 'pandas', 'title' => __( 'pandas: new columns and sorting', 'vitalstack' ) ),
					array( 'path' => 'pandas', 'title' => __( 'pandas: groupby and merge', 'vitalstack' ) ),
					array( 'practice' => 'csv', 'title' => __( 'Practice day: explore a real CSV', 'vitalstack' ) ),
				),
			),
			array(
				'title' => __( 'Week 3: Charts that tell a story', 'vitalstack' ),
				'goal'  => __( 'Make clear, honest charts with Matplotlib, Seaborn and Plotly.', 'vitalstack' ),
				'days'  => array(
					array( 'path' => 'data-visualization', 'title' => __( 'First charts', 'vitalstack' ) ),
					array( 'path' => 'data-visualization', 'title' => __( 'Histograms, scatter and Seaborn', 'vitalstack' ) ),
					array( 'path' => 'data-visualization', 'title' => __( 'Relationships and styling', 'vitalstack' ) ),
					array( 'path' => 'data-visualization', 'title' => __( 'Shares, spread and uncertainty', 'vitalstack' ) ),
					array( 'path' => 'data-visualization', 'title' => __( 'Choosing charts, Seaborn in depth', 'vitalstack' ) ),
					array( 'path' => 'data-visualization', 'title' => __( 'Interactive charts with Plotly', 'vitalstack' ) ),
					array( 'practice' => 'quiz', 'title' => __( 'Practice day: mock test and daily challenge', 'vitalstack' ) ),
				),
			),
			array(
				'title' => __( 'Week 4+: Statistics, machine learning and a project', 'vitalstack' ),
				'goal'  => __( 'Test ideas with statistics, train your first models and finish a portfolio project.', 'vitalstack' ),
				'days'  => array(
					array( 'path' => 'statistics', 'title' => __( 'Averages, spread and probability', 'vitalstack' ) ),
					array( 'path' => 'statistics', 'title' => __( 'Distributions and confidence', 'vitalstack' ) ),
					array( 'path' => 'statistics', 'title' => __( 'Tests and correlation', 'vitalstack' ) ),
					array( 'path' => 'machine-learning', 'title' => __( 'What a model is', 'vitalstack' ) ),
					array( 'path' => 'machine-learning', 'title' => __( 'Your first models', 'vitalstack' ) ),
					array( 'path' => 'eda', 'title' => __( 'EDA and data cleaning', 'vitalstack' ) ),
					array( 'path' => 'eda', 'title' => __( 'Features models can learn from', 'vitalstack' ) ),
					array( 'path' => 'ml-in-depth', 'title' => __( 'Regression and trees in depth', 'vitalstack' ) ),
					array( 'path' => 'ds-projects', 'limit' => 1, 'title' => __( 'Your first portfolio project', 'vitalstack' ) ),
				),
			),
		)
	);
}

/**
 * The plan filled with lessons: [ weeks => [ title, goal, days => [ n, title, lessons|practice ] ] ].
 * Each path's lessons are shared evenly over the days that name it.
 */
function vitalstack_study_plan() {
	$spec = vitalstack_study_plan_spec();
	// How many days ask for each path.
	$want = array();
	foreach ( $spec as $w ) {
		foreach ( $w['days'] as $d ) {
			if ( ! empty( $d['path'] ) ) {
				$want[ $d['path'] ] = ( $want[ $d['path'] ] ?? 0 ) + 1;
			}
		}
	}
	$queue = array();
	$track = array();
	foreach ( array_keys( $want ) as $slug ) {
		$term = get_term_by( 'slug', $slug, 'tutorial_category' );
		if ( ! $term || is_wp_error( $term ) || ! function_exists( 'vitalstack_path_lessons' ) ) {
			$queue[ $slug ] = array();
			continue;
		}
		$queue[ $slug ] = array_values( vitalstack_path_lessons( $term ) );
		$track[ $slug ] = function_exists( 'vitalstack_path_track' ) ? vitalstack_path_track( $slug ) : '';
	}
	$left = $want;
	$n    = 0;
	$out  = array();
	foreach ( $spec as $w ) {
		$days = array();
		foreach ( $w['days'] as $d ) {
			++$n;
			$day = array( 'n' => $n, 'title' => $d['title'], 'lessons' => array(), 'practice' => $d['practice'] ?? '' );
			if ( ! empty( $d['path'] ) ) {
				$slug = $d['path'];
				$take = ! empty( $d['limit'] ) ? (int) $d['limit'] : (int) ceil( count( $queue[ $slug ] ) / max( 1, $left[ $slug ] ) );
				--$left[ $slug ];
				foreach ( array_splice( $queue[ $slug ], 0, $take ) as $post ) {
					$p = get_post( $post );
					if ( $p ) {
						$day['lessons'][] = array(
							'id'      => (int) $p->ID,
							'title'   => get_the_title( $p ),
							'url'     => get_permalink( $p ),
							'path'    => $slug,
							'premium' => ! empty( $track[ $slug ] ),
						);
					}
				}
			}
			if ( $day['lessons'] || $day['practice'] ) {
				$days[] = $day;
			} else {
				--$n; // a path that isn't on this site yet: skip the day
			}
		}
		$out[] = array( 'title' => $w['title'], 'goal' => $w['goal'], 'days' => $days );
	}
	return $out;
}

function vitalstack_study_plan_practice( $kind ) {
	$page = function ( $slug ) {
		$p = get_page_by_path( $slug );
		return $p && 'publish' === $p->post_status ? get_permalink( $p ) : '';
	};
	if ( 'csv' === $kind ) {
		$csv = function_exists( 'vitalstack_site_page_url' ) ? vitalstack_site_page_url( 'csv-data-explorer' ) : '';
		return array(
			__( 'Open the CSV Data Explorer, load any CSV (or the sample), and write down 3 facts about the data: the biggest group, a missing-value problem, and one surprise.', 'vitalstack' ),
			array_filter( array( __( 'CSV Data Explorer', 'vitalstack' ) => $csv, __( 'Python compiler', 'vitalstack' ) => function_exists( 'vitalstack_site_page_url' ) ? vitalstack_site_page_url( 'python-online-compiler' ) : '' ) ),
		);
	}
	return array(
		__( 'Take a mock test to see what stuck, then solve today’s daily challenge. Re-read one lesson where you lost points.', 'vitalstack' ),
		array_filter( array( __( 'Mock tests', 'vitalstack' ) => $page( 'mock-tests' ), __( 'Daily challenge', 'vitalstack' ) => $page( 'daily-challenge' ) ) ),
	);
}

function vitalstack_study_plan_template() {
	if ( ! get_query_var( 'vs_plan' ) ) {
		return;
	}
	status_header( 200 );
	$title = __( 'Data Science in 30 Days: A Free Study Plan for Beginners', 'vitalstack' );
	add_filter(
		'pre_get_document_title',
		function () use ( $title ) {
			return $title . ' | ' . get_bloginfo( 'name' );
		}
	);
	add_action(
		'wp_head',
		function () {
			echo '<meta name="description" content="' . esc_attr__( 'A day-by-day plan to learn data science from zero: set up Jupyter and Colab, Python, NumPy, pandas, charts, statistics and machine learning, 20–40 minutes a day.', 'vitalstack' ) . '">' . "\n";
			echo '<link rel="canonical" href="' . esc_url( vitalstack_study_plan_url() ) . '">' . "\n";
		},
		2
	);
	wp_enqueue_script( 'vitalstack-plan', vitalstack_asset_url( 'assets/js/plan.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	$plan  = vitalstack_study_plan();
	$total = 0;
	foreach ( $plan as $w ) {
		foreach ( $w['days'] as $d ) {
			$total += count( $d['lessons'] );
		}
	}
	$days = 0;
	foreach ( $plan as $w ) {
		$days += count( $w['days'] );
	}
	$prem_url = function_exists( 'vitalstack_pricing_url' ) ? add_query_arg( array( 'utm_source' => 'site', 'utm_medium' => 'study_plan' ), vitalstack_pricing_url() ) : '';
	get_header();
	?>
	<div class="plan-page" data-plan>
		<section class="plan-hero">
			<div class="wrap">
				<p class="eyebrow">🗓️ <?php esc_html_e( 'Free study plan · no mentor needed', 'vitalstack' ); ?></p>
				<h1><?php echo esc_html( $title ); ?></h1>
				<p class="plan-lead"><?php echo esc_html( sprintf( /* translators: 1: days, 2: lessons */ __( '%1$d days, %2$d lessons, 20–40 minutes a day. Follow it in order: each day builds on the one before. Lessons you finish get a tick here automatically.', 'vitalstack' ), $days, $total ) ); ?></p>
				<div class="plan-meter" aria-live="polite">
					<div class="plan-bar"><span data-plan-bar style="width:0"></span></div>
					<p><b data-plan-count>0</b> / <?php echo (int) $total; ?> <?php esc_html_e( 'lessons done', 'vitalstack' ); ?></p>
				</div>
				<a class="btn btn-primary" href="#plan-day-1" data-plan-next><?php esc_html_e( 'Start with Day 1', 'vitalstack' ); ?> →</a>
			</div>
		</section>
		<div class="wrap plan-wrap">
			<?php foreach ( $plan as $wi => $w ) : ?>
				<section class="plan-week" aria-labelledby="plan-week-<?php echo (int) $wi; ?>">
					<h2 id="plan-week-<?php echo (int) $wi; ?>"><?php echo esc_html( $w['title'] ); ?></h2>
					<p class="plan-goal">🎯 <?php echo esc_html( $w['goal'] ); ?></p>
					<ol class="plan-days">
						<?php foreach ( $w['days'] as $d ) : ?>
							<li class="plan-day" id="plan-day-<?php echo (int) $d['n']; ?>" data-day="<?php echo (int) $d['n']; ?>">
								<div class="plan-day-head">
									<span class="plan-day-n"><?php echo esc_html( sprintf( /* translators: %d: day */ __( 'Day %d', 'vitalstack' ), $d['n'] ) ); ?></span>
									<h3><?php echo esc_html( $d['title'] ); ?></h3>
									<span class="plan-day-min"><?php echo $d['lessons'] ? esc_html( sprintf( /* translators: %d: minutes */ __( '~%d min', 'vitalstack' ), min( 45, max( 15, 12 * count( $d['lessons'] ) ) ) ) ) : esc_html__( '~30 min', 'vitalstack' ); ?></span>
								</div>
								<?php if ( $d['lessons'] ) : ?>
									<ul class="plan-lessons">
										<?php foreach ( $d['lessons'] as $l ) : ?>
											<li data-lesson="<?php echo (int) $l['id']; ?>" data-path="<?php echo esc_attr( $l['path'] ); ?>">
												<span class="plan-tick" aria-hidden="true"></span>
												<a href="<?php echo esc_url( $l['url'] ); ?>"><?php echo esc_html( $l['title'] ); ?></a>
												<?php if ( $l['premium'] ) : ?><span class="plan-prem">⭐ <?php esc_html_e( 'Premium', 'vitalstack' ); ?></span><?php endif; ?>
												<span class="screen-reader-text plan-sr-done"><?php esc_html_e( '(done)', 'vitalstack' ); ?></span>
											</li>
										<?php endforeach; ?>
									</ul>
								<?php else : ?>
									<?php list( $txt, $links ) = vitalstack_study_plan_practice( $d['practice'] ); ?>
									<p class="plan-practice"><?php echo esc_html( $txt ); ?></p>
									<p class="plan-links">
										<?php foreach ( $links as $label => $url ) : ?>
											<a class="btn btn-soft btn-sm" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a>
										<?php endforeach; ?>
									</p>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ol>
					<?php if ( 3 === $wi && $prem_url && ! ( function_exists( 'vitalstack_reader_has_any_premium' ) && is_user_logged_in() && vitalstack_reader_has_any_premium( get_current_user_id() ) ) ) : ?>
						<div class="plan-upsell">
							<p><b><?php esc_html_e( 'Week 4 goes deeper with Premium lessons.', 'vitalstack' ); ?></b> <?php esc_html_e( 'You can open each lesson to see its module guide and preview. Unlock the whole Data Scientist track (statistics, EDA, machine learning in depth, projects and a certificate) when you are ready.', 'vitalstack' ); ?></p>
							<a class="btn btn-primary" href="<?php echo esc_url( $prem_url ); ?>"><?php esc_html_e( 'See Premium plans', 'vitalstack' ); ?> →</a>
						</div>
					<?php endif; ?>
				</section>
			<?php endforeach; ?>
			<section class="plan-tips">
				<h2><?php esc_html_e( 'How to stick to it', 'vitalstack' ); ?></h2>
				<ul>
					<li><?php esc_html_e( 'Same time every day, even 20 minutes. A streak beats a long weekend session.', 'vitalstack' ); ?></li>
					<li><?php esc_html_e( 'Type the code yourself in Jupyter or Colab; don’t only read it.', 'vitalstack' ); ?></li>
					<li><?php esc_html_e( 'Take the quiz at the end of every lesson, and press "Mark this lesson as complete".', 'vitalstack' ); ?></li>
					<li><?php esc_html_e( 'Use the 🎮 Break button’s 25-minute focus timer, then take a 5-minute game break.', 'vitalstack' ); ?></li>
					<li><?php esc_html_e( 'Missed a day? Just continue tomorrow from the next unticked lesson.', 'vitalstack' ); ?></li>
				</ul>
			</section>
		</div>
	</div>
	<?php
	get_footer();
	exit;
}
add_action( 'template_redirect', 'vitalstack_study_plan_template', 6 );
