<?php
/**
 * Weekly digest: one Sunday email to subscribers with everything published
 * that week, instead of an email for every post.
 *
 * Customizer → VitalStack: Members → "Subscriber emails":
 *   weekly  one digest every Sunday (default)
 *   each    an email for every new post or lesson
 *   off     no automatic emails
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_notify_mode() {
	if ( ! get_theme_mod( 'vitalstack_notify_enabled', true ) ) {
		return 'off';
	}
	$mode = get_theme_mod( 'vitalstack_notify_mode', 'weekly' );
	return in_array( $mode, array( 'weekly', 'each', 'off' ), true ) ? $mode : 'weekly';
}

function vitalstack_digest_customizer( $wp_customize ) {
	$wp_customize->add_setting(
		'vitalstack_notify_mode',
		array(
			'default'           => 'weekly',
			'sanitize_callback' => function ( $v ) {
				return in_array( $v, array( 'weekly', 'each', 'off' ), true ) ? $v : 'weekly';
			},
		)
	);
	$wp_customize->add_control(
		'vitalstack_notify_mode',
		array(
			'label'       => __( 'Subscriber emails', 'vitalstack' ),
			'description' => __( 'A weekly digest keeps subscribers happy when you publish often.', 'vitalstack' ),
			'section'     => 'vitalstack_members',
			'type'        => 'radio',
			'choices'     => array(
				'weekly' => __( 'Weekly digest every Sunday (recommended)', 'vitalstack' ),
				'each'   => __( 'One email for every new post or lesson', 'vitalstack' ),
				'off'    => __( 'Off', 'vitalstack' ),
			),
		)
	);
}
add_action( 'customize_register', 'vitalstack_digest_customizer', 20 );

/**
 * Per-post emails only in "each" mode.
 */
add_action(
	'init',
	function () {
		if ( 'each' !== vitalstack_notify_mode() ) {
			remove_action( 'transition_post_status', 'vitalstack_queue_notification', 10 );
		}
	}
);

/**
 * Schedule the digest for Sunday 09:00 (site time), every week.
 */
function vitalstack_digest_schedule() {
	if ( 'weekly' !== vitalstack_notify_mode() ) {
		wp_clear_scheduled_hook( 'vitalstack_weekly_digest' );
		return;
	}
	if ( ! wp_next_scheduled( 'vitalstack_weekly_digest' ) ) {
		$next = new DateTime( 'next sunday 09:00', wp_timezone() );
		wp_schedule_event( $next->getTimestamp(), 'weekly', 'vitalstack_weekly_digest' );
	}
}
add_action( 'init', 'vitalstack_digest_schedule' );

/**
 * Posts and lessons published in the last 7 days.
 */
function vitalstack_digest_items() {
	return get_posts(
		array(
			'post_type'      => array( 'post', 'tutorials' ),
			'post_status'    => 'publish',
			'posts_per_page' => 12,
			'date_query'     => array( array( 'after' => '7 days ago' ) ),
			'orderby'        => 'date',
			'order'          => 'ASC',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'key'     => '_vs_skip_notify',
					'compare' => 'NOT EXISTS',
				),
			),
		)
	);
}

/**
 * Start a digest run (weekly cron).
 */
function vitalstack_weekly_digest() {
	if ( 'weekly' !== vitalstack_notify_mode() ) {
		return;
	}
	$items = vitalstack_digest_items();
	if ( ! $items ) {
		return; // Nothing new this week: no email.
	}
	$week = vitalstack_xp_week_key();
	if ( get_option( 'vitalstack_digest_sent' ) === $week ) {
		return;
	}
	update_option( 'vitalstack_digest_sent', $week, false );
	update_option( 'vitalstack_digest_items', wp_list_pluck( $items, 'ID' ), false );
	vitalstack_send_digest_batch( 0 );
}
add_action( 'vitalstack_weekly_digest', 'vitalstack_weekly_digest' );

/**
 * The digest body for one reader.
 */
function vitalstack_digest_body( $user, $ids ) {
	$rows = '';
	foreach ( $ids as $id ) {
		$post = get_post( $id );
		if ( ! $post || 'publish' !== $post->post_status ) {
			continue;
		}
		$label = 'tutorials' === $post->post_type ? __( 'Lesson', 'vitalstack' ) : __( 'Guide', 'vitalstack' );
		$path  = 'tutorials' === $post->post_type ? vitalstack_tutorial_path( $post->ID ) : null;
		$rows .= '<tr><td style="padding:12px 0;border-bottom:1px solid #eef0f3">'
			. '<p style="margin:0 0 2px;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#0a7d4f">' . esc_html( $path ? $path->name : $label ) . '</p>'
			. '<a href="' . esc_url( get_permalink( $post ) ) . '" style="font-size:17px;font-weight:700;color:#111827;text-decoration:none">' . esc_html( wp_specialchars_decode( get_the_title( $post ), ENT_QUOTES ) ) . '</a>'
			. '<p style="margin:4px 0 0;font-size:14px;color:#4b5563">' . esc_html( wp_trim_words( get_the_excerpt( $post ), 22 ) ) . '</p>'
			. '</td></tr>';
	}
	$stats  = function_exists( 'vitalstack_xp_stats' ) ? vitalstack_xp_stats( $user->ID ) : null;
	$streak = $stats && $stats['streak'] ? '<p style="margin:0 0 16px;padding:10px 14px;background:#fff3c4;border-radius:8px;font-size:14px">🔥 ' . esc_html( sprintf( /* translators: %d: days */ _n( 'You are on a %d-day streak. Keep it going today!', 'You are on a %d-day streak. Keep it going today!', $stats['streak'], 'vitalstack' ), $stats['streak'] ) ) . '</p>' : '';
	$daily  = function_exists( 'vitalstack_challenge_url' ) && vitalstack_challenge_url() ? vitalstack_email_button( vitalstack_challenge_url(), __( "Try today's challenge", 'vitalstack' ) ) : '';
	return '<p style="margin:0 0 6px;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#0a7d4f">' . esc_html__( 'This week on VitalStack', 'vitalstack' ) . '</p>'
		. '<h1 style="margin:0 0 12px;font-size:24px;line-height:1.25;color:#111827">' . esc_html( sprintf( /* translators: %d: count */ _n( '%d new thing to learn', '%d new things to learn', count( $ids ), 'vitalstack' ), count( $ids ) ) ) . '</h1>'
		. '<p style="margin:0 0 14px">' . sprintf( /* translators: %s: name */ esc_html__( 'Hi %s, here is what we published this week.', 'vitalstack' ), esc_html( $user->display_name ) ) . '</p>'
		. $streak
		. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0">' . $rows . '</table>'
		. $daily;
}

/**
 * Send one batch of the digest, then queue the next.
 */
function vitalstack_send_digest_batch( $after_user_id ) {
	global $wpdb;
	$ids = (array) get_option( 'vitalstack_digest_items', array() );
	if ( ! $ids ) {
		return;
	}
	$users = $wpdb->get_col( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->prepare(
			"SELECT s.user_id FROM {$wpdb->usermeta} s
			INNER JOIN {$wpdb->usermeta} v ON v.user_id = s.user_id AND v.meta_key = 'vs_email_verified' AND v.meta_value = '1'
			WHERE s.meta_key = 'vs_subscribed' AND s.meta_value = '1' AND s.user_id > %d
			ORDER BY s.user_id ASC LIMIT %d",
			(int) $after_user_id,
			VITALSTACK_MAIL_BATCH
		)
	);
	foreach ( $users as $uid ) {
		$user = get_userdata( (int) $uid );
		if ( $user ) {
			/* translators: %d: number of new posts */
			$subject = sprintf( _n( 'This week: %d new lesson for you', 'This week: %d new lessons and guides for you', count( $ids ), 'vitalstack' ), count( $ids ) );
			vitalstack_mail( $user->user_email, $subject, vitalstack_digest_body( $user, $ids ), array( 'unsubscribe' => vitalstack_unsubscribe_url( $user->ID ) ) );
		}
	}
	if ( count( $users ) === VITALSTACK_MAIL_BATCH ) {
		wp_schedule_single_event( time() + 5 * MINUTE_IN_SECONDS, 'vitalstack_send_digest_batch', array( (int) end( $users ) ) );
	}
}
add_action( 'vitalstack_send_digest_batch', 'vitalstack_send_digest_batch' );
