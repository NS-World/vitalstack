<?php
/**
 * XP, levels and daily streaks.
 *
 * Signed-in readers earn XP on their account; guests keep the same numbers
 * in localStorage (assets/js/fun.js) until they sign in.
 *
 *   Completing a lesson         +10 XP (once per lesson)
 *   Finishing a live visualizer  +2 XP (once per visualizer)
 *   Playing a brain game         +5 to +20 XP (up to 60 XP from games per day)
 *
 * A streak counts consecutive days with at least one XP-earning activity.
 *
 * REST: GET  /wp-json/vitalstack/v1/xp          → stats
 *       POST /wp-json/vitalstack/v1/xp          { type: viz|game, ref, score } → stats
 * Lesson XP is awarded by the progress endpoint when new lessons are marked done.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const VITALSTACK_XP_LESSON    = 10;
const VITALSTACK_XP_VIZ       = 2;
const VITALSTACK_XP_GAME_DAY  = 60;

/**
 * Level from total XP: level n needs 50·(n-1)² XP (0, 50, 200, 450, 800…).
 */
function vitalstack_xp_level( $xp ) {
	$level = (int) floor( sqrt( max( 0, $xp ) / 50 ) ) + 1;
	$from  = 50 * ( $level - 1 ) * ( $level - 1 );
	$to    = 50 * $level * $level;
	return array(
		'level'    => $level,
		'progress' => $to > $from ? round( ( $xp - $from ) / ( $to - $from ), 3 ) : 0,
		'next'     => $to,
	);
}

function vitalstack_xp_today() {
	return wp_date( 'Y-m-d' );
}

function vitalstack_xp_week_key() {
	return wp_date( 'o-W' );
}

/**
 * Current stats for a user.
 *
 * @return array{xp:int,week:int,streak:int,best:int,level:int,levelProgress:float,nextLevel:int,gamesToday:int}
 */
function vitalstack_xp_stats( $user_id ) {
	$xp     = (int) get_user_meta( $user_id, 'vs_xp', true );
	$week   = get_user_meta( $user_id, 'vs_xp_week_key', true ) === vitalstack_xp_week_key() ? (int) get_user_meta( $user_id, 'vs_xp_week', true ) : 0;
	$last   = get_user_meta( $user_id, 'vs_last_active', true );
	$streak = (int) get_user_meta( $user_id, 'vs_streak', true );
	// A streak survives until the end of the day after the last activity.
	if ( $last && $last !== vitalstack_xp_today() && wp_date( 'Y-m-d', strtotime( '-1 day', current_time( 'timestamp' ) ) ) !== $last ) { // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested -- local calendar day
		$streak = 0;
	}
	$games = get_user_meta( $user_id, 'vs_games_day', true ) === vitalstack_xp_today() ? (int) get_user_meta( $user_id, 'vs_games_xp', true ) : 0;
	$lvl   = vitalstack_xp_level( $xp );
	return array(
		'xp'            => $xp,
		'week'          => $week,
		'streak'        => $streak,
		'best'          => max( $streak, (int) get_user_meta( $user_id, 'vs_streak_best', true ) ),
		'level'         => $lvl['level'],
		'levelProgress' => $lvl['progress'],
		'nextLevel'     => $lvl['next'],
		'gamesToday'    => $games,
	);
}

/**
 * Adds XP and updates the week total and the streak.
 *
 * @param int    $user_id User.
 * @param int    $amount  XP to add.
 * @param string $ref     Optional unique reference ("lesson:12"); XP for a ref is given once.
 * @return int XP actually added.
 */
function vitalstack_xp_award( $user_id, $amount, $ref = '' ) {
	$amount = max( 0, (int) $amount );
	if ( ! $user_id || ! $amount ) {
		return 0;
	}
	if ( $ref ) {
		$refs = get_user_meta( $user_id, 'vs_xp_refs', true );
		$refs = is_array( $refs ) ? $refs : array();
		if ( isset( $refs[ $ref ] ) ) {
			return 0;
		}
		$refs[ $ref ] = 1;
		if ( count( $refs ) > 3000 ) {
			$refs = array_slice( $refs, -3000, null, true );
		}
		update_user_meta( $user_id, 'vs_xp_refs', $refs );
	}

	update_user_meta( $user_id, 'vs_xp', (int) get_user_meta( $user_id, 'vs_xp', true ) + $amount );

	$week_key = vitalstack_xp_week_key();
	$week     = get_user_meta( $user_id, 'vs_xp_week_key', true ) === $week_key ? (int) get_user_meta( $user_id, 'vs_xp_week', true ) : 0;
	update_user_meta( $user_id, 'vs_xp_week_key', $week_key );
	update_user_meta( $user_id, 'vs_xp_week', $week + $amount );

	$today = vitalstack_xp_today();
	$last  = get_user_meta( $user_id, 'vs_last_active', true );
	if ( $last !== $today ) {
		$yesterday = wp_date( 'Y-m-d', strtotime( '-1 day', current_time( 'timestamp' ) ) ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested -- local calendar day
		$streak    = $last === $yesterday ? (int) get_user_meta( $user_id, 'vs_streak', true ) + 1 : 1;
		update_user_meta( $user_id, 'vs_streak', $streak );
		update_user_meta( $user_id, 'vs_last_active', $today );
		if ( $streak > (int) get_user_meta( $user_id, 'vs_streak_best', true ) ) {
			update_user_meta( $user_id, 'vs_streak_best', $streak );
		}
	}
	return $amount;
}

/**
 * XP for a game result: 5 for playing, up to 20 for a great score, within the daily cap.
 */
function vitalstack_xp_for_game( $user_id, $score ) {
	$score = max( 0, min( 100, (int) $score ) );
	$want  = 5 + (int) round( $score * 0.15 );
	$today = vitalstack_xp_today();
	$used  = get_user_meta( $user_id, 'vs_games_day', true ) === $today ? (int) get_user_meta( $user_id, 'vs_games_xp', true ) : 0;
	$give  = max( 0, min( $want, VITALSTACK_XP_GAME_DAY - $used ) );
	update_user_meta( $user_id, 'vs_games_day', $today );
	update_user_meta( $user_id, 'vs_games_xp', $used + $give );
	return $give;
}

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'vitalstack/v1',
			'/xp',
			array(
				array(
					'methods'             => 'GET',
					'permission_callback' => 'is_user_logged_in',
					'callback'            => function () {
						return vitalstack_xp_stats( get_current_user_id() );
					},
				),
				array(
					'methods'             => 'POST',
					'permission_callback' => 'is_user_logged_in',
					'callback'            => function ( WP_REST_Request $req ) {
						$uid    = get_current_user_id();
						$type   = sanitize_key( (string) $req->get_param( 'type' ) );
						$ref    = substr( preg_replace( '/[^a-z0-9:_-]/', '', strtolower( (string) $req->get_param( 'ref' ) ) ), 0, 80 );
						$gained = 0;
						if ( 'viz' === $type && $ref ) {
							$gained = vitalstack_xp_award( $uid, VITALSTACK_XP_VIZ, 'viz:' . $ref );
						} elseif ( 'game' === $type ) {
							$gained = vitalstack_xp_award( $uid, vitalstack_xp_for_game( $uid, (int) $req->get_param( 'score' ) ) );
						}
						return array_merge( vitalstack_xp_stats( $uid ), array( 'gained' => $gained ) );
					},
				),
			)
		);
	}
);

/**
 * Lesson XP: called by the progress endpoint with the lessons newly marked done.
 *
 * @param int   $user_id User.
 * @param int[] $ids     Lesson ids.
 * @return int XP added.
 */
function vitalstack_xp_for_lessons( $user_id, $ids ) {
	$total = 0;
	foreach ( array_slice( $ids, 0, 200 ) as $id ) {
		if ( 'tutorials' === get_post_type( (int) $id ) ) {
			$total += vitalstack_xp_award( $user_id, VITALSTACK_XP_LESSON, 'lesson:' . (int) $id );
		}
	}
	return $total;
}
