<?php
/**
 * Email: a branded HTML template, and "new post" notifications to
 * confirmed subscribers, sent in small batches through WP-Cron so
 * publishing stays fast and shared-hosting mail limits are respected.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VITALSTACK_MAIL_BATCH', 40 );

/**
 * Sends an HTML email wrapped in the VitalStack template.
 *
 * @param string $to      Recipient.
 * @param string $subject Subject.
 * @param string $body    Inner HTML (already escaped).
 * @param array  $extra   { @type string $unsubscribe Unsubscribe URL. }
 */
function vitalstack_mail( $to, $subject, $body, $extra = array() ) {
	$site    = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$headers = array( 'Content-Type: text/html; charset=UTF-8' );

	$footer = esc_html__( 'You are receiving this because you have an account on', 'vitalstack' ) . ' <a href="' . esc_url( home_url( '/' ) ) . '" style="color:#0a7d4f">' . esc_html( $site ) . '</a>.';
	if ( ! empty( $extra['unsubscribe'] ) ) {
		$headers[] = 'List-Unsubscribe: <' . esc_url_raw( $extra['unsubscribe'] ) . '>';
		$headers[] = 'List-Unsubscribe-Post: List-Unsubscribe=One-Click';
		$footer   .= ' <a href="' . esc_url( $extra['unsubscribe'] ) . '" style="color:#6b7280">' . esc_html__( 'Unsubscribe', 'vitalstack' ) . '</a>';
	}

	$html = '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width"></head>'
		. '<body style="margin:0;padding:0;background:#f3f5f7;font-family:-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;color:#1f2937">'
		. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f5f7;padding:24px 12px"><tr><td align="center">'
		. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb">'
		. '<tr><td style="background:#1b2230;padding:18px 28px"><a href="' . esc_url( home_url( '/' ) ) . '" style="color:#ffffff;font-size:20px;font-weight:800;text-decoration:none">Vital<span style="color:#34d399">Stack</span></a></td></tr>'
		. '<tr><td style="padding:28px;font-size:16px;line-height:1.6">' . $body . '</td></tr>'
		. '<tr><td style="padding:18px 28px;border-top:1px solid #e5e7eb;font-size:12px;line-height:1.5;color:#6b7280">' . $footer . '</td></tr>'
		. '</table></td></tr></table></body></html>';

	return wp_mail( $to, $subject, $html, $headers );
}

function vitalstack_email_button( $url, $label ) {
	return '<p style="margin:24px 0"><a href="' . esc_url( $url ) . '" style="display:inline-block;background:#0a7d4f;color:#ffffff;text-decoration:none;font-weight:700;padding:12px 22px;border-radius:8px">' . esc_html( $label ) . '</a></p>';
}

/* ── Opt-out per post ─────────────────────────────────────────────────────── */

function vitalstack_notify_meta_box() {
	foreach ( array( 'post', 'tutorials' ) as $type ) {
		add_meta_box( 'vitalstack_notify', __( 'Email subscribers', 'vitalstack' ), 'vitalstack_notify_meta_box_html', $type, 'side', 'default' );
	}
}
add_action( 'add_meta_boxes', 'vitalstack_notify_meta_box' );

function vitalstack_notify_meta_box_html( $post ) {
	wp_nonce_field( 'vs_notify_box', 'vs_notify_nonce' );
	$sent = get_post_meta( $post->ID, '_vs_notified', true );
	if ( $sent ) {
		/* translators: %s: date */
		echo '<p>' . esc_html( sprintf( __( 'Subscribers were emailed on %s.', 'vitalstack' ), wp_date( get_option( 'date_format' ), (int) $sent ) ) ) . '</p>';
		return;
	}
	$skip = get_post_meta( $post->ID, '_vs_skip_notify', true );
	echo '<label><input type="checkbox" name="vs_skip_notify" value="1" ' . checked( $skip, '1', false ) . '> ' . esc_html__( 'Don’t email subscribers when this is published', 'vitalstack' ) . '</label>';
}

function vitalstack_notify_meta_save( $post_id ) {
	if ( ! isset( $_POST['vs_notify_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['vs_notify_nonce'] ), 'vs_notify_box' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, '_vs_skip_notify', empty( $_POST['vs_skip_notify'] ) ? '' : '1' );
}
add_action( 'save_post', 'vitalstack_notify_meta_save' );

/* ── Queue on publish ─────────────────────────────────────────────────────── */

function vitalstack_queue_notification( $new_status, $old_status, $post ) {
	if ( 'publish' !== $new_status || 'publish' === $old_status ) {
		return;
	}
	if ( ! in_array( $post->post_type, array( 'post', 'tutorials' ), true ) || wp_is_post_revision( $post ) ) {
		return;
	}
	if ( ! get_theme_mod( 'vitalstack_notify_enabled', true ) ) {
		return;
	}
	// The checkbox is saved on save_post, which runs after this hook, so read it from the request too.
	// phpcs:ignore WordPress.Security.NonceVerification -- only reading a flag; the meta box saves it with a nonce
	$skip_now = isset( $_POST['vs_notify_nonce'] ) && ! empty( $_POST['vs_skip_notify'] );
	if ( $skip_now || get_post_meta( $post->ID, '_vs_skip_notify', true ) || get_post_meta( $post->ID, '_vs_notified', true ) ) {
		return;
	}
	update_post_meta( $post->ID, '_vs_notified', time() );
	// A short delay lets the author fix a typo before emails go out.
	wp_schedule_single_event( time() + 2 * MINUTE_IN_SECONDS, 'vitalstack_send_notifications', array( $post->ID, 0 ) );
}
add_action( 'transition_post_status', 'vitalstack_queue_notification', 10, 3 );

/**
 * Sends one batch, then schedules the next batch if there are more subscribers.
 * Uses "last user ID sent" as a cursor, so new sign-ups mid-send are safe.
 */
function vitalstack_send_notifications( $post_id, $after_user_id ) {
	$post = get_post( $post_id );
	if ( ! $post || 'publish' !== $post->post_status ) {
		return;
	}
	// The block editor saves meta boxes after the publish request, so the
	// "don't email" checkbox is only reliable once we get here.
	if ( get_post_meta( $post_id, '_vs_skip_notify', true ) ) {
		return;
	}

	global $wpdb;
	$ids = $wpdb->get_col( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->prepare(
			"SELECT s.user_id FROM {$wpdb->usermeta} s
			INNER JOIN {$wpdb->usermeta} v ON v.user_id = s.user_id AND v.meta_key = 'vs_email_verified' AND v.meta_value = '1'
			WHERE s.meta_key = 'vs_subscribed' AND s.meta_value = '1' AND s.user_id > %d
			ORDER BY s.user_id ASC LIMIT %d",
			(int) $after_user_id,
			VITALSTACK_MAIL_BATCH
		)
	);
	if ( ! $ids ) {
		return;
	}

	$title   = wp_specialchars_decode( get_the_title( $post ), ENT_QUOTES );
	$link    = get_permalink( $post );
	$excerpt = wp_trim_words( get_the_excerpt( $post ), 45 );
	$is_tut  = 'tutorials' === $post->post_type;
	$label   = $is_tut ? __( 'New lesson', 'vitalstack' ) : __( 'New guide', 'vitalstack' );
	$image   = get_the_post_thumbnail_url( $post, 'vitalstack-card' );
	$minutes = vitalstack_read_minutes( $post->ID );

	foreach ( $ids as $uid ) {
		$user = get_userdata( (int) $uid );
		if ( ! $user ) {
			continue;
		}
		$unsub = vitalstack_unsubscribe_url( $user->ID );
		$body  = '<p style="margin:0 0 6px;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#0a7d4f">' . esc_html( $label ) . '</p>'
			. '<h1 style="margin:0 0 14px;font-size:24px;line-height:1.25;color:#111827">' . esc_html( $title ) . '</h1>'
			. ( $image ? '<a href="' . esc_url( $link ) . '"><img src="' . esc_url( $image ) . '" alt="" width="504" style="width:100%;max-width:504px;height:auto;border-radius:8px;margin:0 0 16px"></a>' : '' )
			. '<p style="margin:0 0 8px">' . sprintf( /* translators: %s: name */ esc_html__( 'Hi %s,', 'vitalstack' ), esc_html( $user->display_name ) ) . '</p>'
			. '<p style="margin:0 0 8px">' . esc_html( $excerpt ) . '</p>'
			/* translators: %d: minutes */
			. '<p style="margin:0;color:#6b7280;font-size:14px">' . esc_html( sprintf( __( '%d min read', 'vitalstack' ), $minutes ) ) . '</p>'
			. vitalstack_email_button( $link, $is_tut ? __( 'Start the lesson', 'vitalstack' ) : __( 'Read the guide', 'vitalstack' ) );

		/* translators: 1: label, 2: title */
		vitalstack_mail( $user->user_email, sprintf( __( '%1$s: %2$s', 'vitalstack' ), $label, $title ), $body, array( 'unsubscribe' => $unsub ) );
	}

	if ( count( $ids ) === VITALSTACK_MAIL_BATCH ) {
		wp_schedule_single_event( time() + 5 * MINUTE_IN_SECONDS, 'vitalstack_send_notifications', array( $post_id, (int) end( $ids ) ) );
	}
}
add_action( 'vitalstack_send_notifications', 'vitalstack_send_notifications', 10, 2 );
