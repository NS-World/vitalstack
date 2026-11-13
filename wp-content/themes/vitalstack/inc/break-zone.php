<?php
/**
 * Break zone (4.13.4): one header button for when a reader needs a break.
 *
 * It opens a small panel with three tabs:
 *   - Games: the game of the day and every brain game, one tap away;
 *   - Focus music: lo-fi, rain, calm keys and brown noise made live in the
 *     browser (no audio files, nothing streamed or sent anywhere), with a
 *     25-minute focus timer that suggests a 5-minute game break;
 *   - Tools: the free online tools.
 *
 * Nothing plays until the reader presses Play. Settings stay on the device.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_break_enabled() {
	return ! is_admin() && (bool) apply_filters( 'vitalstack_break_enabled', true );
}

/** The brain games (ids match assets/js/games.js, opened with #play-<id>). */
function vitalstack_break_games() {
	return apply_filters(
		'vitalstack_break_games',
		array(
			'binary-hunt'       => array( '🎯', __( 'Binary Hunt', 'vitalstack' ) ),
			'bracket-blitz'     => array( '⚡', __( 'Bracket Blitz', 'vitalstack' ) ),
			'big-o-snap'        => array( '⏱️', __( 'Big-O Snap', 'vitalstack' ) ),
			'sort-sprint'       => array( '📊', __( 'Sort Sprint', 'vitalstack' ) ),
			'memory-match'      => array( '🃏', __( 'Memory Match', 'vitalstack' ) ),
			'hanoi'             => array( '🗼', __( 'Tower of Hanoi', 'vitalstack' ) ),
			'pattern-detective' => array( '🕵️', __( 'Pattern Detective', 'vitalstack' ) ),
			'java-output'       => array( '☕', __( 'Java Output', 'vitalstack' ) ),
			'oop-pillars'       => array( '🏛️', __( 'OOP Pillars', 'vitalstack' ) ),
			'framework-flash'   => array( '⚡', __( 'Framework Flash', 'vitalstack' ) ),
			'ai-makers'         => array( '🤖', __( 'Who Made This AI?', 'vitalstack' ) ),
			'ai-terms'          => array( '🧠', __( 'AI Word Rush', 'vitalstack' ) ),
			'prompt-duel'       => array( '🥊', __( 'Prompt Duel', 'vitalstack' ) ),
		)
	);
}

/** Free tools that exist on this site: slug => [icon, short name, url]. */
function vitalstack_break_tools() {
	$short = array(
		'python-online-compiler' => __( 'Python compiler', 'vitalstack' ),
		'sql-playground'         => __( 'SQL playground', 'vitalstack' ),
		'html-online-editor'     => __( 'HTML/CSS/JS editor', 'vitalstack' ),
		'json-formatter'         => __( 'JSON formatter', 'vitalstack' ),
		'regex-tester'           => __( 'Regex tester', 'vitalstack' ),
		'csv-data-explorer'      => __( 'CSV explorer', 'vitalstack' ),
		'prompt-builder'         => __( 'Prompt builder', 'vitalstack' ),
		'ai-cost-calculator'     => __( 'AI cost calculator', 'vitalstack' ),
		'ab-test-calculator'     => __( 'A/B test calculator', 'vitalstack' ),
		'sample-size-calculator' => __( 'Sample size', 'vitalstack' ),
	);
	$out = array();
	if ( ! function_exists( 'vitalstack_site_pages_in' ) ) {
		return $out;
	}
	$pages = vitalstack_site_pages_in( 'tools' );
	foreach ( $short as $slug => $name ) {
		if ( empty( $pages[ $slug ] ) ) {
			continue;
		}
		$url = vitalstack_site_page_url( $slug );
		if ( $url ) {
			$out[ $slug ] = array( $pages[ $slug ]['icon'], $name, $url );
		}
	}
	if ( function_exists( 'vitalstack_playground_url' ) ) {
		$out['playground'] = array( '🧪', __( 'Website playground', 'vitalstack' ), vitalstack_playground_url() );
	}
	return $out;
}

function vitalstack_break_hub_url( $slug ) {
	$page = get_page_by_path( $slug );
	return ( $page && 'publish' === $page->post_status ) ? get_permalink( $page ) : '';
}

/** Header button (called from header.php). */
function vitalstack_break_button() {
	if ( ! vitalstack_break_enabled() ) {
		return;
	}
	?>
	<button type="button" class="break-btn" data-break-open aria-controls="break-panel" aria-expanded="false" aria-haspopup="dialog" title="<?php esc_attr_e( 'Take a break: games, focus music and tools', 'vitalstack' ); ?>">
		<span class="break-btn-ico" aria-hidden="true">🎮</span>
		<span class="break-btn-txt"><?php esc_html_e( 'Break', 'vitalstack' ); ?></span>
		<span class="break-btn-eq" aria-hidden="true"><i></i><i></i><i></i></span>
		<span class="break-btn-time" data-break-time hidden></span>
	</button>
	<?php
}

/** The panel, printed once in the footer. */
function vitalstack_break_panel() {
	if ( ! vitalstack_break_enabled() ) {
		return;
	}
	$games_url = function_exists( 'vitalstack_games_url' ) ? vitalstack_games_url() : '';
	$tools_url = vitalstack_break_hub_url( 'tools' );
	$music_url = function_exists( 'vitalstack_study_music_url' ) ? vitalstack_study_music_url() : '';
	$city_url  = vitalstack_break_hub_url( 'ai-city' );
	$games     = $games_url ? vitalstack_break_games() : array();
	$tools     = vitalstack_break_tools();
	$ids       = array_keys( $games );
	$daily     = $ids ? $ids[ (int) floor( time() / DAY_IN_SECONDS ) % count( $ids ) ] : '';
	?>
	<div class="break-panel" id="break-panel" role="dialog" aria-modal="false" aria-labelledby="break-title" data-break-panel hidden>
		<div class="break-head">
			<p class="break-title" id="break-title">☕ <?php esc_html_e( 'Break zone', 'vitalstack' ); ?></p>
			<button type="button" class="break-x" data-break-close aria-label="<?php esc_attr_e( 'Close', 'vitalstack' ); ?>">✕</button>
		</div>
		<p class="break-done" data-break-done hidden></p>
		<div class="break-tabs" role="tablist">
			<?php if ( $games ) : ?>
				<button type="button" role="tab" id="break-tab-games" aria-controls="break-games" aria-selected="true" data-break-tab="games">🎮 <?php esc_html_e( 'Games', 'vitalstack' ); ?></button>
			<?php endif; ?>
			<button type="button" role="tab" id="break-tab-music" aria-controls="break-music" aria-selected="false" data-break-tab="music">🎧 <span class="bt-long"><?php esc_html_e( 'Focus music', 'vitalstack' ); ?></span><span class="bt-short"><?php esc_html_e( 'Music', 'vitalstack' ); ?></span></button>
			<?php if ( $tools ) : ?>
				<button type="button" role="tab" id="break-tab-tools" aria-controls="break-tools" aria-selected="false" data-break-tab="tools">🛠️ <?php esc_html_e( 'Tools', 'vitalstack' ); ?></button>
			<?php endif; ?>
		</div>

		<?php if ( $games ) : ?>
			<div class="break-pane" role="tabpanel" id="break-games" aria-labelledby="break-tab-games" data-break-pane="games">
				<a class="break-daily" href="<?php echo esc_url( $games_url . '#play-' . $daily ); ?>" data-break-game>
					<span class="break-daily-ico" aria-hidden="true"><?php echo esc_html( $games[ $daily ][0] ); ?></span>
					<span><small><?php esc_html_e( 'Game of the day', 'vitalstack' ); ?></small><b><?php echo esc_html( $games[ $daily ][1] ); ?></b></span>
					<span class="break-go" aria-hidden="true">▶</span>
				</a>
				<div class="break-grid">
					<?php foreach ( $games as $id => $g ) : ?>
						<a href="<?php echo esc_url( $games_url . '#play-' . $id ); ?>" data-break-game><span aria-hidden="true"><?php echo esc_html( $g[0] ); ?></span><?php echo esc_html( $g[1] ); ?></a>
					<?php endforeach; ?>
					<a href="<?php echo esc_url( $games_url ); ?>"><span aria-hidden="true">🏃</span><?php esc_html_e( 'Bug Runner', 'vitalstack' ); ?></a>
					<?php if ( $city_url ) : ?>
						<a href="<?php echo esc_url( $city_url ); ?>"><span aria-hidden="true">🏙️</span><?php esc_html_e( 'AI City', 'vitalstack' ); ?></a>
					<?php endif; ?>
				</div>
				<a class="break-more" href="<?php echo esc_url( $games_url ); ?>"><?php esc_html_e( 'All games, scores and XP', 'vitalstack' ); ?> →</a>
			</div>
		<?php endif; ?>

		<div class="break-pane" role="tabpanel" id="break-music" aria-labelledby="break-tab-music" data-break-pane="music" hidden>
			<div class="break-stations" role="radiogroup" aria-label="<?php esc_attr_e( 'Station', 'vitalstack' ); ?>">
				<button type="button" role="radio" aria-checked="true" data-station="lofi">☕<b><?php esc_html_e( 'Lo-fi', 'vitalstack' ); ?></b></button>
				<button type="button" role="radio" aria-checked="false" data-station="rain">🌧️<b><?php esc_html_e( 'Rain', 'vitalstack' ); ?></b></button>
				<button type="button" role="radio" aria-checked="false" data-station="keys">🎹<b><?php esc_html_e( 'Calm keys', 'vitalstack' ); ?></b></button>
				<button type="button" role="radio" aria-checked="false" data-station="brown">🌊<b><?php esc_html_e( 'Brown noise', 'vitalstack' ); ?></b></button>
			</div>
			<div class="break-player">
				<button type="button" class="break-play" data-break-play aria-pressed="false"><span data-break-play-ico aria-hidden="true">▶</span> <span data-break-play-txt><?php esc_html_e( 'Play', 'vitalstack' ); ?></span></button>
				<label class="break-vol"><span aria-hidden="true">🔊</span><span class="screen-reader-text"><?php esc_html_e( 'Volume', 'vitalstack' ); ?></span><input type="range" min="0" max="100" value="55" data-break-vol></label>
			</div>
			<div class="break-timer">
				<p><b><?php esc_html_e( 'Focus timer', 'vitalstack' ); ?></b> <span class="muted"><?php esc_html_e( '25 min study, then a 5 min game break', 'vitalstack' ); ?></span></p>
				<div class="break-timer-row">
					<span class="break-clock" data-break-clock>25:00</span>
					<button type="button" class="btn btn-sm btn-primary" data-break-timer><?php esc_html_e( 'Start 25 min', 'vitalstack' ); ?></button>
					<button type="button" class="btn btn-sm btn-ghost" data-break-timer-stop hidden><?php esc_html_e( 'Stop', 'vitalstack' ); ?></button>
				</div>
			</div>
			<p class="break-note muted"><?php esc_html_e( 'Music is made live in your browser: no downloads, nothing is sent anywhere. It pauses when you open another page; tap Play to resume.', 'vitalstack' ); ?></p>
			<?php if ( $music_url ) : ?>
				<a class="break-more" href="<?php echo esc_url( $music_url ); ?>"><?php esc_html_e( 'Open the full Study Music room', 'vitalstack' ); ?> →</a>
			<?php endif; ?>
		</div>

		<?php if ( $tools ) : ?>
			<div class="break-pane" role="tabpanel" id="break-tools" aria-labelledby="break-tab-tools" data-break-pane="tools" hidden>
				<div class="break-grid is-tools">
					<?php foreach ( $tools as $t ) : ?>
						<a href="<?php echo esc_url( $t[2] ); ?>"><span aria-hidden="true"><?php echo esc_html( $t[0] ); ?></span><?php echo esc_html( $t[1] ); ?></a>
					<?php endforeach; ?>
				</div>
				<?php if ( $tools_url ) : ?>
					<a class="break-more" href="<?php echo esc_url( $tools_url ); ?>"><?php esc_html_e( 'All free tools', 'vitalstack' ); ?> →</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}
add_action( 'wp_footer', 'vitalstack_break_panel', 5 );

function vitalstack_break_assets() {
	if ( vitalstack_break_enabled() ) {
		wp_enqueue_script( 'vitalstack-break', vitalstack_asset_url( 'assets/js/break.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'vitalstack_break_assets', 33 );
