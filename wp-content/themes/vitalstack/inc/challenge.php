<?php
/**
 * Daily challenge (one quiz question per day, the same for everyone) and the
 * weekly XP leaderboard.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Today's question: array( q, o, a, e, path, slug ) or null when no quizzes exist.
 */
function vitalstack_daily_question( $date = '' ) {
	$date = $date ? $date : vitalstack_xp_today();
	$key  = 'vs_daily_' . $date;
	$hit  = get_transient( $key );
	if ( is_array( $hit ) ) {
		return $hit;
	}
	$pool = array();
	foreach ( glob( VITALSTACK_DIR . '/data/quizzes/*.php' ) as $file ) {
		$path = basename( $file, '.php' );
		foreach ( vitalstack_path_quizzes( $path ) as $slug => $questions ) {
			foreach ( $questions as $i => $q ) {
				$pool[] = array( $path, $slug, $i );
			}
		}
	}
	if ( ! $pool ) {
		return null;
	}
	sort( $pool );
	$pick = $pool[ crc32( 'vs-daily-' . $date ) % count( $pool ) ];
	$all  = vitalstack_path_quizzes( $pick[0] );
	$q    = $all[ $pick[1] ][ $pick[2] ];
	$q['path'] = $pick[0];
	$q['slug'] = $pick[1];
	set_transient( $key, $q, DAY_IN_SECONDS );
	return $q;
}

/**
 * The daily challenge card.
 *
 * @param bool $compact Smaller version for the homepage.
 */
function vitalstack_daily_challenge( $compact = false ) {
	$q = vitalstack_daily_question();
	if ( ! $q ) {
		return;
	}
	$date   = vitalstack_xp_today();
	$lesson = get_page_by_path( $q['slug'], OBJECT, 'tutorials' );
	$term   = get_term_by( 'slug', $q['path'], 'tutorial_category' );
	?>
	<section class="daily<?php echo $compact ? ' is-compact' : ''; ?>" data-daily="<?php echo esc_attr( $date ); ?>">
		<div class="daily-head">
			<span class="daily-badge">🔥 <?php esc_html_e( 'Daily challenge', 'vitalstack' ); ?></span>
			<span class="daily-meta"><?php echo esc_html( wp_date( 'l, j F' ) ); ?><?php echo $term ? ' · ' . esc_html( $term->name ) : ''; ?> · +<?php echo (int) VITALSTACK_XP_DAILY; ?> XP</span>
		</div>
		<?php echo vitalstack_quiz_question_markup( $q, 1, 'daily' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
		<p class="daily-result" hidden aria-live="polite"></p>
		<?php if ( $lesson ) : ?>
			<p class="daily-learn"><?php esc_html_e( 'Learn it:', 'vitalstack' ); ?> <a href="<?php echo esc_url( get_permalink( $lesson ) ); ?>"><?php echo esc_html( get_the_title( $lesson ) ); ?> ❯</a></p>
		<?php endif; ?>
	</section>
	<?php
}

/**
 * Short public name: "Nirav S." from "Nirav Shah".
 */
function vitalstack_public_name( $name ) {
	$parts = preg_split( '/\s+/', trim( (string) $name ) );
	if ( count( $parts ) > 1 ) {
		return $parts[0] . ' ' . mb_strtoupper( mb_substr( end( $parts ), 0, 1 ) ) . '.';
	}
	return $parts[0] ? $parts[0] : __( 'Learner', 'vitalstack' );
}

/**
 * Top learners: this week ('week') or all time ('all').
 *
 * @return array<int, array{id:int,name:string,xp:int}>
 */
function vitalstack_leaderboard( $period = 'week', $limit = 20 ) {
	global $wpdb;
	$week = vitalstack_xp_week_key();
	$key  = 'vs_board_' . $period . '_' . ( 'week' === $period ? $week : 'all' );
	$hit  = get_transient( $key );
	if ( is_array( $hit ) ) {
		return $hit;
	}
	if ( 'week' === $period ) {
		$sql = $wpdb->prepare(
			"SELECT u.ID AS id, u.display_name AS name, CAST(w.meta_value AS UNSIGNED) AS xp
			FROM {$wpdb->users} u
			JOIN {$wpdb->usermeta} k ON k.user_id = u.ID AND k.meta_key = 'vs_xp_week_key' AND k.meta_value = %s
			JOIN {$wpdb->usermeta} w ON w.user_id = u.ID AND w.meta_key = 'vs_xp_week'
			LEFT JOIN {$wpdb->usermeta} h ON h.user_id = u.ID AND h.meta_key = 'vs_leaderboard_hide'
			WHERE ( h.meta_value IS NULL OR h.meta_value = '' OR h.meta_value = '0' ) AND CAST(w.meta_value AS UNSIGNED) > 0
			ORDER BY xp DESC, u.ID ASC LIMIT %d",
			$week,
			$limit
		);
	} else {
		$sql = $wpdb->prepare(
			"SELECT u.ID AS id, u.display_name AS name, CAST(x.meta_value AS UNSIGNED) AS xp
			FROM {$wpdb->users} u
			JOIN {$wpdb->usermeta} x ON x.user_id = u.ID AND x.meta_key = 'vs_xp'
			LEFT JOIN {$wpdb->usermeta} h ON h.user_id = u.ID AND h.meta_key = 'vs_leaderboard_hide'
			WHERE ( h.meta_value IS NULL OR h.meta_value = '' OR h.meta_value = '0' ) AND CAST(x.meta_value AS UNSIGNED) > 0
			ORDER BY xp DESC, u.ID ASC LIMIT %d",
			$limit
		);
	}
	$rows = array();
	foreach ( (array) $wpdb->get_results( $sql ) as $r ) { // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery
		$rows[] = array(
			'id'   => (int) $r->id,
			'name' => vitalstack_public_name( $r->name ),
			'xp'   => (int) $r->xp,
		);
	}
	set_transient( $key, $rows, 10 * MINUTE_IN_SECONDS );
	return $rows;
}

/**
 * Leaderboard table with a week / all-time switch.
 */
function vitalstack_leaderboard_block() {
	$me = get_current_user_id();
	?>
	<section class="board" data-board>
		<div class="board-head">
			<h2><?php esc_html_e( 'Leaderboard', 'vitalstack' ); ?></h2>
			<div class="board-tabs" role="tablist">
				<button type="button" role="tab" class="chip is-active" data-board-tab="week" aria-selected="true"><?php esc_html_e( 'This week', 'vitalstack' ); ?></button>
				<button type="button" role="tab" class="chip" data-board-tab="all" aria-selected="false"><?php esc_html_e( 'All time', 'vitalstack' ); ?></button>
			</div>
		</div>
		<?php foreach ( array( 'week', 'all' ) as $period ) : ?>
			<?php $rows = vitalstack_leaderboard( $period ); ?>
			<ol class="board-list" data-board-panel="<?php echo esc_attr( $period ); ?>"<?php echo 'all' === $period ? ' hidden' : ''; ?>>
				<?php if ( ! $rows ) : ?>
					<li class="board-empty"><?php esc_html_e( 'No one yet. Finish a lesson or a quiz and take the top spot!', 'vitalstack' ); ?></li>
				<?php endif; ?>
				<?php foreach ( $rows as $i => $row ) : ?>
					<li class="<?php echo $row['id'] === $me ? 'is-me' : ''; ?>">
						<span class="board-rank"><?php echo 0 === $i ? '🥇' : ( 1 === $i ? '🥈' : ( 2 === $i ? '🥉' : (int) ( $i + 1 ) ) ); ?></span>
						<span class="board-avatar" aria-hidden="true"><?php echo esc_html( mb_strtoupper( mb_substr( $row['name'], 0, 1 ) ) ); ?></span>
						<span class="board-name"><?php echo esc_html( $row['name'] ); ?><?php echo $row['id'] === $me ? ' <em>(' . esc_html__( 'you', 'vitalstack' ) . ')</em>' : ''; ?></span>
						<b class="board-xp"><?php echo esc_html( number_format_i18n( $row['xp'] ) ); ?> XP</b>
					</li>
				<?php endforeach; ?>
			</ol>
		<?php endforeach; ?>
		<?php if ( ! $me && vitalstack_accounts_enabled() ) : ?>
			<p class="board-join"><a class="btn btn-primary btn-sm" href="<?php echo esc_url( vitalstack_account_url( array( 'tab' => 'register' ) ) ); ?>"><?php esc_html_e( 'Create a free account to join the leaderboard', 'vitalstack' ); ?></a></p>
		<?php else : ?>
			<p class="muted small"><?php esc_html_e( 'Weekly scores reset every Monday. You can hide your name in your account settings.', 'vitalstack' ); ?></p>
		<?php endif; ?>
	</section>
	<?php
}

/**
 * New XP changes the leaderboard: drop the cached lists.
 */
add_action(
	'updated_user_meta',
	function ( $meta_id, $user_id, $meta_key ) {
		if ( 'vs_xp' === $meta_key ) {
			delete_transient( 'vs_board_week_' . vitalstack_xp_week_key() );
			delete_transient( 'vs_board_all_all' );
		}
	},
	10,
	3
);

function vitalstack_challenge_url() {
	$page = get_page_by_path( 'daily-challenge' );
	return $page && 'publish' === $page->post_status ? get_permalink( $page ) : '';
}
