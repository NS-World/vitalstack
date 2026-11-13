<?php
/**
 * Plugin Name: VitalStack Marketing
 * Description: Friendly, consent-based emails that bring learners back and explain the Premium offers: welcome series, an offer after sign-in, a reminder after visiting the pricing page, "we miss you" emails for inactive learners, and campaigns you write yourself. Works with the VitalStack theme.
 * Version: 1.0.0
 * Author: VitalStack
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * Text Domain: vitalstack-marketing
 *
 * Rules it always keeps:
 *   - Only learners who confirmed their email AND subscribed to emails get
 *     anything. Every email has a one-click unsubscribe link (the theme's).
 *   - At most one marketing email every few days per person (Settings).
 *   - Offer emails go only to learners without Premium.
 *   - Nothing is sent until you switch it on in Settings → VitalStack Marketing.
 *   - No outside services: emails go through WordPress (use an SMTP plugin
 *     for good delivery).
 *
 * @package VitalStackMarketing
 */

defined( 'ABSPATH' ) || exit;

define( 'VSM_VERSION', '1.0.0' );

/* ── Settings ─────────────────────────────────────────────────────────── */

function vsm_defaults() {
	return array(
		'on'        => 0,   // master switch, off until the owner turns it on
		'welcome'   => 1,
		'login'     => 1,
		'pricing'   => 1,
		'winback'   => 1,
		'gap_days'  => 3,   // minimum days between two marketing emails to one person
		'batch'     => 40,  // campaign emails per 5 minutes
		'waitlist'  => 0,   // send offer emails while sales are closed
		'from_name' => '',
	);
}

function vsm_settings() {
	return wp_parse_args( (array) get_option( 'vsm_settings', array() ), vsm_defaults() );
}

function vsm_ready() {
	return function_exists( 'vitalstack_mail' ) && function_exists( 'vitalstack_is_subscribed' ) && function_exists( 'vitalstack_unsubscribe_url' );
}

/* ── Who may get what ─────────────────────────────────────────────────── */

function vsm_has_premium( $uid ) {
	return function_exists( 'vitalstack_reader_has_any_premium' ) && vitalstack_reader_has_any_premium( $uid );
}

function vsm_sales_open() {
	return function_exists( 'vitalstack_pay_ready' ) && vitalstack_pay_ready();
}

/**
 * Whether this person may get a marketing email now.
 *
 * @param int    $uid   User.
 * @param bool   $offer It talks about prices (only for learners without Premium).
 * @param bool   $gap   Respect the minimum gap between emails.
 */
function vsm_can_send( $uid, $offer = false, $gap = true ) {
	$s    = vsm_settings();
	$user = get_userdata( $uid );
	if ( ! $s['on'] || ! vsm_ready() || ! $user || ! is_email( $user->user_email ) ) {
		return false;
	}
	if ( ! vitalstack_is_subscribed( $uid ) || ! get_user_meta( $uid, 'vs_email_verified', true ) ) {
		return false;
	}
	if ( user_can( $uid, 'edit_posts' ) && ! apply_filters( 'vsm_email_staff', false ) ) {
		return false; // staff test with "Send me a test"
	}
	if ( $offer && ( vsm_has_premium( $uid ) || ( ! vsm_sales_open() && ! $s['waitlist'] ) ) ) {
		return false;
	}
	if ( $gap ) {
		$last = (int) get_user_meta( $uid, 'vsm_last_mail', true );
		if ( $last && time() - $last < max( 1, (int) $s['gap_days'] ) * DAY_IN_SECONDS ) {
			return false;
		}
	}
	return true;
}

function vsm_sent_at( $uid, $flow ) {
	$sent = (array) get_user_meta( $uid, 'vsm_sent', true );
	return isset( $sent[ $flow ] ) ? (int) $sent[ $flow ] : 0;
}

function vsm_mark( $uid, $flow, $ok ) {
	$sent          = (array) get_user_meta( $uid, 'vsm_sent', true );
	$sent[ $flow ] = time();
	update_user_meta( $uid, 'vsm_sent', $sent );
	if ( $ok ) {
		update_user_meta( $uid, 'vsm_last_mail', time() );
	}
	$log = (array) get_option( 'vsm_log', array() );
	array_unshift( $log, array( time(), (int) $uid, $flow, $ok ? 1 : 0 ) );
	update_option( 'vsm_log', array_slice( $log, 0, 300 ), false );
	$stats                  = (array) get_option( 'vsm_stats', array() );
	$stats[ $flow ]         = ( isset( $stats[ $flow ] ) ? (int) $stats[ $flow ] : 0 ) + ( $ok ? 1 : 0 );
	update_option( 'vsm_stats', $stats, false );
}

/* ── Activity we track (on this site only) ────────────────────────────── */

add_action(
	'wp_login',
	function ( $login, $user ) {
		update_user_meta( $user->ID, 'vsm_last_login', time() );
		delete_user_meta( $user->ID, 'vsm_away' ); // back: the "we miss you" series starts over next time
		if ( function_exists( 'vitalstack_pay_locked_currency' ) ) {
			$cur = vitalstack_pay_locked_currency();
			if ( in_array( $cur, array( 'inr', 'usd' ), true ) ) {
				update_user_meta( $user->ID, 'vsm_cur', $cur );
			}
		}
		$s = vsm_settings();
		// An offer a little after signing in, at most once every 14 days.
		if ( $s['on'] && $s['login'] && time() - vsm_sent_at( $user->ID, 'login' ) > 14 * DAY_IN_SECONDS && ! wp_next_scheduled( 'vsm_send_one', array( (int) $user->ID, 'login' ) ) ) {
			wp_schedule_single_event( time() + 45 * MINUTE_IN_SECONDS, 'vsm_send_one', array( (int) $user->ID, 'login' ) );
		}
	},
	20,
	2
);

// A signed-in visit to the pricing page, for a friendly reminder later.
add_action(
	'template_redirect',
	function () {
		$uid = get_current_user_id();
		if ( $uid && get_query_var( 'vs_pricing' ) && ! vsm_has_premium( $uid ) ) {
			update_user_meta( $uid, 'vsm_pricing_seen', time() );
		}
	}
);

/* ── Prices in the reader's currency ──────────────────────────────────── */

function vsm_offer( $uid ) {
	if ( ! function_exists( 'vitalstack_pay_plans' ) ) {
		return null;
	}
	$plan = vitalstack_pay_plans()['all-year'] ?? null;
	if ( ! $plan ) {
		return null;
	}
	$cur  = get_user_meta( $uid, 'vsm_cur', true ) === 'usd' ? 'usd' : 'inr';
	$list = function_exists( 'vitalstack_plan_list_prices' ) ? ( vitalstack_plan_list_prices()['all-year'] ?? null ) : null;
	$now  = (float) $plan[ $cur ];
	$was  = $list ? (float) $list[ 'inr' === $cur ? 0 : 1 ] : 0;
	$fmt  = function ( $n ) use ( $cur ) {
		return function_exists( 'vitalstack_pay_price_label' ) ? vitalstack_pay_price_label( $n, $cur ) : ( 'inr' === $cur ? '₹' . number_format( $n ) : '$' . $n );
	};
	$month = function_exists( 'vitalstack_plan_money' ) ? vitalstack_plan_money( $now / 12, $cur ) : '';
	return array(
		'title' => wp_strip_all_tags( $plan['title'] ),
		'now'   => $fmt( $now ),
		'was'   => $was > $now ? $fmt( $was ) : '',
		'save'  => $was > $now ? (int) round( ( 1 - $now / $was ) * 100 ) : 0,
		'month' => $month,
	);
}

function vsm_link( $url, $flow ) {
	return add_query_arg( array( 'utm_source' => 'email', 'utm_medium' => 'vsm', 'utm_campaign' => $flow ), $url );
}

function vsm_offer_box( $uid, $flow ) {
	$o = vsm_offer( $uid );
	if ( ! $o ) {
		return '';
	}
	$url = vsm_link( function_exists( 'vitalstack_pricing_url' ) ? vitalstack_pricing_url() : home_url( '/pricing/' ), $flow );
	$h   = '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:22px 0;border-radius:14px;background:linear-gradient(135deg,#ff3b5c,#ff8a00);background-color:#ff4d3a"><tr><td style="padding:20px 22px;color:#ffffff">'
		. '<p style="margin:0 0 4px;font-size:13px;font-weight:700;letter-spacing:.04em">🔥 ' . esc_html__( 'LAUNCH OFFER', 'vitalstack-marketing' ) . '</p>'
		. '<p style="margin:0 0 6px;font-size:18px;font-weight:800">' . esc_html( $o['title'] ) . ': ' . esc_html__( 'all 3 career tracks', 'vitalstack-marketing' ) . '</p>'
		. '<p style="margin:0;font-size:30px;font-weight:900">' . esc_html( $o['now'] ) . ' <span style="font-size:15px;font-weight:600">/ ' . esc_html__( 'year', 'vitalstack-marketing' ) . '</span>'
		. ( $o['was'] ? ' <s style="font-size:16px;font-weight:600;opacity:.85">' . esc_html( $o['was'] ) . '</s>' : '' )
		. ( $o['save'] >= 5 ? ' <span style="display:inline-block;margin-left:6px;padding:3px 9px;border-radius:999px;background:#ffd400;color:#3a1500;font-size:13px;font-weight:800">' . esc_html( sprintf( /* translators: %d: percent */ __( 'SAVE %d%%', 'vitalstack-marketing' ), $o['save'] ) ) . '</span>' : '' )
		. '</p>'
		. ( $o['month'] ? '<p style="margin:6px 0 14px;font-size:14px">' . esc_html( sprintf( /* translators: %s: price */ __( 'Just %s a month, paid once. No auto-renewal.', 'vitalstack-marketing' ), $o['month'] ) ) . '</p>' : '' )
		. '<a href="' . esc_url( $url ) . '" style="display:inline-block;background:#ffffff;color:#e2262f;text-decoration:none;font-weight:800;padding:11px 20px;border-radius:999px">' . esc_html( vsm_sales_open() ? __( 'Claim the offer', 'vitalstack-marketing' ) : __( 'Join the waitlist', 'vitalstack-marketing' ) ) . ' →</a>'
		. '</td></tr></table>';
	return $h;
}

/* ── The emails ───────────────────────────────────────────────────────── */

/** [subject, inner html, is_offer] for a flow. */
function vsm_compose( $uid, $flow ) {
	$user  = get_userdata( $uid );
	$name  = $user ? esc_html( $user->display_name ) : '';
	$hi    = '<p>' . sprintf( /* translators: %s: name */ esc_html__( 'Hi %s,', 'vitalstack-marketing' ), $name ) . '</p>';
	$site  = esc_html( wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) );
	$btn   = function ( $url, $label ) use ( $flow ) {
		return function_exists( 'vitalstack_email_button' ) ? vitalstack_email_button( vsm_link( $url, $flow ), $label ) : '<p><a href="' . esc_url( vsm_link( $url, $flow ) ) . '">' . esc_html( $label ) . '</a></p>';
	};
	$p     = function ( $t ) {
		return '<p>' . esc_html( $t ) . '</p>';
	};
	$games = function_exists( 'vitalstack_games_url' ) && vitalstack_games_url() ? vitalstack_games_url() : home_url( '/' );
	$streak = (int) get_user_meta( $uid, 'vs_streak_best', true );
	switch ( $flow ) {
		case 'welcome2':
			return array(
				__( 'Your first 15 minutes on VitalStack (a simple plan)', 'vitalstack-marketing' ),
				$hi . $p( __( 'Here is the easiest way to start, even with no tech background:', 'vitalstack-marketing' ) )
				. '<ol style="padding-left:20px"><li>' . esc_html__( 'Open HTML Introduction and finish one lesson (about 5 minutes).', 'vitalstack-marketing' ) . '</li><li>' . esc_html__( 'Take the short quiz at the end: it locks the idea in.', 'vitalstack-marketing' ) . '</li><li>' . esc_html__( 'Tired? Tap the 🎮 Break button at the top for a 2-minute brain game or focus music.', 'vitalstack-marketing' ) . '</li></ol>'
				. $p( __( 'Do this daily and your streak grows. Small steps, every day.', 'vitalstack-marketing' ) )
				. $btn( home_url( '/' ), __( 'Start learning', 'vitalstack-marketing' ) ),
				false,
			);
		case 'welcome5':
			return array(
				__( 'Ready to go further? Your career tracks are waiting', 'vitalstack-marketing' ),
				$hi . $p( __( 'You have seen how VitalStack explains things: step by step, with live animations. Premium takes that all the way to a job-ready level:', 'vitalstack-marketing' ) )
				. '<ul style="padding-left:20px"><li>📊 ' . esc_html__( 'Data Scientist: statistics, machine learning, time series, NLP, MLOps', 'vitalstack-marketing' ) . '</li><li>🤖 ' . esc_html__( 'Gen AI Developer: LLMs, RAG, LangChain, fine-tuning, deploying AI apps', 'vitalstack-marketing' ) . '</li><li>🦾 ' . esc_html__( 'Agentic AI Engineer: agents, tools, memory, MCP, multi-agent systems', 'vitalstack-marketing' ) . '</li></ul>'
				. $p( __( 'Every track has real projects for your CV and a certificate. The first modules are free, so you can try before you decide.', 'vitalstack-marketing' ) )
				. vsm_offer_box( $uid, $flow ),
				true,
			);
		case 'login':
			return array(
				__( 'Good to see you back! Here is the best deal on VitalStack', 'vitalstack-marketing' ),
				$hi . $p( __( 'Thanks for learning with us today. If you are thinking about a career in Data and AI, this is the smartest way to do it:', 'vitalstack-marketing' ) )
				. vsm_offer_box( $uid, $flow )
				. $p( __( 'One payment, all three career tracks, projects, certificates and no ads anywhere on the site.', 'vitalstack-marketing' ) ),
				true,
			);
		case 'pricing':
			return array(
				__( 'Still deciding? Here is a quick way to choose', 'vitalstack-marketing' ),
				$hi . $p( __( 'You looked at our plans recently. Here is the honest short version:', 'vitalstack-marketing' ) )
				. '<ul style="padding-left:20px"><li>' . esc_html__( 'Want one skill? A Career Track covers one track for 12 months.', 'vitalstack-marketing' ) . '</li><li>' . esc_html__( 'Serious about Data and AI? Pro Yearly gives all 3 tracks for about the price of one and a half.', 'vitalstack-marketing' ) . '</li><li>' . esc_html__( 'Not sure yet? Open the free modules of any track first.', 'vitalstack-marketing' ) . '</li></ul>'
				. vsm_offer_box( $uid, $flow )
				. $p( __( 'Questions? Just reply to this email.', 'vitalstack-marketing' ) ),
				true,
			);
		case 'miss7':
			return array(
				__( 'Your next lesson is waiting (it takes 5 minutes)', 'vitalstack-marketing' ),
				$hi . $p( __( 'It has been a few days. The best learners are not the fastest, they just come back. One short lesson today keeps everything fresh.', 'vitalstack-marketing' ) )
				. ( $streak > 1 ? $p( sprintf( /* translators: %d: days */ __( 'Your best streak so far is %d days. Beat it this week?', 'vitalstack-marketing' ), $streak ) ) : '' )
				. $btn( home_url( '/' ), __( 'Continue learning', 'vitalstack-marketing' ) )
				. '<p style="font-size:14px;color:#4b5563">' . esc_html__( 'Short on energy? Play the game of the day instead: it is still practice.', 'vitalstack-marketing' ) . ' <a href="' . esc_url( vsm_link( $games, $flow ) ) . '" style="color:#0a7d4f">' . esc_html__( 'Play now', 'vitalstack-marketing' ) . '</a></p>',
				false,
			);
		case 'miss21':
			return array(
				__( 'We kept your place, plus a reason to come back', 'vitalstack-marketing' ),
				$hi . $p( __( 'Since your last visit we have been adding lessons, games and free tools. Your progress is saved exactly where you left it.', 'vitalstack-marketing' ) )
				. $btn( home_url( '/' ), __( 'Pick up where I left off', 'vitalstack-marketing' ) )
				. ( vsm_can_send( $uid, true, false ) ? $p( __( 'And if you want to go all in, the launch price is still on:', 'vitalstack-marketing' ) ) . vsm_offer_box( $uid, $flow ) : '' ),
				false,
			);
		case 'miss45':
			return array(
				__( 'Should we keep sending you lessons?', 'vitalstack-marketing' ),
				$hi . $p( __( 'We have not seen you in a while. If you are busy, no problem: your account and progress stay safe.', 'vitalstack-marketing' ) )
				. $p( __( 'If you would rather not get these emails, use the unsubscribe link below. One click, no questions.', 'vitalstack-marketing' ) )
				. $btn( home_url( '/' ), __( 'Yes, I still want to learn', 'vitalstack-marketing' ) ),
				false,
			);
	}
	return array( '', '', false );
}

function vsm_send( $uid, $flow, $gap = true, $custom = null ) {
	$user = get_userdata( $uid );
	if ( ! $user ) {
		return false;
	}
	if ( $custom ) {
		list( $subject, $body, $offer ) = $custom;
	} else {
		list( $subject, $body, $offer ) = vsm_compose( $uid, $flow );
	}
	if ( '' === $subject || ! vsm_can_send( $uid, $offer, $gap ) ) {
		return false;
	}
	$s     = vsm_settings();
	$extra = array( 'unsubscribe' => vitalstack_unsubscribe_url( $uid ) );
	$extra['footer'] = esc_html__( 'You get this because you subscribed to emails from', 'vitalstack-marketing' ) . ' <a href="' . esc_url( home_url( '/' ) ) . '" style="color:#0a7d4f">' . esc_html( wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) ) . '</a>.';
	$from  = trim( (string) $s['from_name'] );
	$cb    = null;
	if ( '' !== $from ) {
		$cb = function () use ( $from ) {
			return $from;
		};
		add_filter( 'wp_mail_from_name', $cb );
	}
	$ok = (bool) vitalstack_mail( $user->user_email, $subject, $body, $extra );
	if ( $cb ) {
		remove_filter( 'wp_mail_from_name', $cb );
	}
	vsm_mark( $uid, $flow, $ok );
	return $ok;
}

add_action(
	'vsm_send_one',
	function ( $uid, $flow ) {
		if ( 'login' === $flow && vsm_settings()['login'] ) {
			vsm_send( (int) $uid, 'login' );
		}
	},
	10,
	2
);

/* ── Hourly: welcome series, pricing reminder, we miss you ────────────── */

add_filter(
	'cron_schedules',
	function ( $s ) {
		$s['vsm_5min'] = array( 'interval' => 300, 'display' => 'Every 5 minutes (VitalStack Marketing)' );
		return $s;
	}
);

function vsm_schedule() {
	if ( ! wp_next_scheduled( 'vsm_hourly' ) ) {
		wp_schedule_event( time() + 120, 'hourly', 'vsm_hourly' );
	}
	if ( ! wp_next_scheduled( 'vsm_campaign_tick' ) ) {
		wp_schedule_event( time() + 60, 'vsm_5min', 'vsm_campaign_tick' );
	}
}
add_action( 'init', 'vsm_schedule' );

register_deactivation_hook(
	__FILE__,
	function () {
		wp_clear_scheduled_hook( 'vsm_hourly' );
		wp_clear_scheduled_hook( 'vsm_campaign_tick' );
		wp_clear_scheduled_hook( 'vsm_send_one' );
	}
);

/** Subscribed, confirmed learners (ids), newest first, limited. */
function vsm_audience( $extra = array(), $limit = 200 ) {
	$args = array(
		'fields'     => 'ID',
		'number'     => $limit,
		'orderby'    => 'ID',
		'order'      => 'DESC',
		'meta_query' => array_merge(
			array(
				'relation' => 'AND',
				array( 'key' => 'vs_subscribed', 'value' => '1' ),
				array( 'key' => 'vs_email_verified', 'value' => '1' ),
			),
			$extra
		),
	);
	return array_map( 'intval', get_users( $args ) );
}

function vsm_hourly() {
	$s = vsm_settings();
	if ( ! $s['on'] || ! vsm_ready() ) {
		return;
	}
	$now  = time();
	$sent = 0;
	$cap  = 150; // per hour, to stay friendly with the mail server
	foreach ( vsm_audience( array(), 500 ) as $uid ) {
		if ( $sent >= $cap ) {
			break;
		}
		$user = get_userdata( $uid );
		if ( ! $user ) {
			continue;
		}
		$age  = $now - strtotime( $user->user_registered . ' UTC' );
		$last = (int) get_user_meta( $uid, 'vsm_last_login', true );
		$last = $last ? $last : strtotime( $user->user_registered . ' UTC' );
		$flow = '';
		if ( $s['welcome'] && $age > 2 * DAY_IN_SECONDS && $age < 10 * DAY_IN_SECONDS && ! vsm_sent_at( $uid, 'welcome2' ) ) {
			$flow = 'welcome2';
		} elseif ( $s['welcome'] && $age > 5 * DAY_IN_SECONDS && $age < 14 * DAY_IN_SECONDS && ! vsm_sent_at( $uid, 'welcome5' ) && vsm_can_send( $uid, true ) ) {
			$flow = 'welcome5';
		} elseif ( $s['pricing'] && ( $seen = (int) get_user_meta( $uid, 'vsm_pricing_seen', true ) ) && $now - $seen > DAY_IN_SECONDS && $now - $seen < 4 * DAY_IN_SECONDS && $now - vsm_sent_at( $uid, 'pricing' ) > 30 * DAY_IN_SECONDS && vsm_can_send( $uid, true ) ) {
			$flow = 'pricing';
		} elseif ( $s['winback'] ) {
			$away = (array) get_user_meta( $uid, 'vsm_away', true );
			foreach ( array( 'miss45' => 45, 'miss21' => 21, 'miss7' => 7 ) as $f => $days ) {
				if ( $now - $last > $days * DAY_IN_SECONDS ) {
					if ( empty( $away[ $f ] ) ) {
						$flow = $f;
					}
					break;
				}
			}
		}
		if ( $flow && vsm_send( $uid, $flow ) ) {
			$sent++;
			if ( 0 === strpos( $flow, 'miss' ) ) {
				$away          = (array) get_user_meta( $uid, 'vsm_away', true );
				$away[ $flow ] = $now;
				update_user_meta( $uid, 'vsm_away', $away );
			}
		}
	}
}
add_action( 'vsm_hourly', 'vsm_hourly' );

/* ── Campaigns you write (sent in small batches) ──────────────────────── */

function vsm_campaign_html( $c, $uid ) {
	$user = get_userdata( $uid );
	$body = '<p>' . sprintf( /* translators: %s: name */ esc_html__( 'Hi %s,', 'vitalstack-marketing' ), esc_html( $user ? $user->display_name : '' ) ) . '</p>';
	if ( '' !== $c['heading'] ) {
		$body .= '<h2 style="margin:0 0 12px;font-size:22px;line-height:1.3">' . esc_html( $c['heading'] ) . '</h2>';
	}
	$body .= wpautop( wp_kses_post( $c['message'] ) );
	if ( ! empty( $c['offer'] ) ) {
		$body .= vsm_offer_box( $uid, 'campaign' );
	}
	if ( '' !== $c['button'] && '' !== $c['url'] && function_exists( 'vitalstack_email_button' ) ) {
		$body .= vitalstack_email_button( vsm_link( $c['url'], 'campaign-' . $c['id'] ), $c['button'] );
	}
	return $body;
}

function vsm_campaign_tick() {
	$c = get_option( 'vsm_campaign' );
	if ( ! is_array( $c ) || 'sending' !== ( $c['status'] ?? '' ) || ! vsm_settings()['on'] ) {
		return;
	}
	$args = array(
		'fields'     => 'ID',
		'number'     => max( 5, min( 200, (int) vsm_settings()['batch'] ) ),
		'orderby'    => 'ID',
		'order'      => 'ASC',
		'meta_query' => array(
			'relation' => 'AND',
			array( 'key' => 'vs_subscribed', 'value' => '1' ),
			array( 'key' => 'vs_email_verified', 'value' => '1' ),
		),
	);
	global $wpdb;
	$after = (int) $c['cursor'];
	$where = function ( $q ) use ( $after, $wpdb ) {
		$q->query_where .= $wpdb->prepare( " AND {$wpdb->users}.ID > %d", $after );
	};
	add_action( 'pre_user_query', $where );
	$ids = array_map( 'intval', get_users( $args ) );
	remove_action( 'pre_user_query', $where );
	if ( ! $ids ) {
		$c['status']   = 'done';
		$c['finished'] = time();
		update_option( 'vsm_campaign', $c, false );
		return;
	}
	foreach ( $ids as $uid ) {
		$c['cursor'] = $uid;
		$seg         = $c['segment'];
		if ( 'free' === $seg && vsm_has_premium( $uid ) ) {
			continue;
		}
		if ( 'inactive' === $seg ) {
			$last = (int) get_user_meta( $uid, 'vsm_last_login', true );
			if ( $last && time() - $last < 14 * DAY_IN_SECONDS ) {
				continue;
			}
		}
		// Premium learners still get the campaign, just without the offer box.
		$cu          = $c;
		$cu['offer'] = ! empty( $c['offer'] ) && ! vsm_has_premium( $uid ) && ( vsm_sales_open() || vsm_settings()['waitlist'] );
		if ( vsm_send( $uid, 'campaign', false, array( $c['subject'], vsm_campaign_html( $cu, $uid ), false ) ) ) {
			$c['sent'] = (int) $c['sent'] + 1;
		}
	}
	update_option( 'vsm_campaign', $c, false );
}
add_action( 'vsm_campaign_tick', 'vsm_campaign_tick' );

/* ── Admin page ───────────────────────────────────────────────────────── */

add_action(
	'admin_menu',
	function () {
		add_options_page( __( 'VitalStack Marketing', 'vitalstack-marketing' ), __( 'VitalStack Marketing', 'vitalstack-marketing' ), 'manage_options', 'vitalstack-marketing', 'vsm_admin_page' );
	}
);

add_filter(
	'plugin_action_links_' . plugin_basename( __FILE__ ),
	function ( $links ) {
		array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=vitalstack-marketing' ) ) . '">' . esc_html__( 'Settings', 'vitalstack-marketing' ) . '</a>' );
		return $links;
	}
);

function vsm_admin_post() {
	if ( ! current_user_can( 'manage_options' ) || empty( $_POST['vsm_action'] ) ) {
		return '';
	}
	check_admin_referer( 'vsm_admin' );
	$act = sanitize_key( wp_unslash( $_POST['vsm_action'] ) );
	if ( 'save' === $act ) {
		$in  = wp_unslash( $_POST );
		$new = array();
		foreach ( array( 'on', 'welcome', 'login', 'pricing', 'winback', 'waitlist' ) as $k ) {
			$new[ $k ] = empty( $in[ $k ] ) ? 0 : 1;
		}
		$new['gap_days']  = max( 1, min( 30, (int) ( $in['gap_days'] ?? 3 ) ) );
		$new['batch']     = max( 5, min( 200, (int) ( $in['batch'] ?? 40 ) ) );
		$new['from_name'] = sanitize_text_field( $in['from_name'] ?? '' );
		update_option( 'vsm_settings', $new, false );
		return __( 'Settings saved.', 'vitalstack-marketing' );
	}
	$c = array(
		'id'      => substr( md5( uniqid( '', true ) ), 0, 8 ),
		'subject' => sanitize_text_field( wp_unslash( $_POST['subject'] ?? '' ) ),
		'heading' => sanitize_text_field( wp_unslash( $_POST['heading'] ?? '' ) ),
		'message' => wp_kses_post( wp_unslash( $_POST['message'] ?? '' ) ),
		'button'  => sanitize_text_field( wp_unslash( $_POST['button'] ?? '' ) ),
		'url'     => esc_url_raw( wp_unslash( $_POST['url'] ?? '' ) ),
		'segment' => in_array( sanitize_key( wp_unslash( $_POST['segment'] ?? '' ) ), array( 'all', 'free', 'inactive' ), true ) ? sanitize_key( wp_unslash( $_POST['segment'] ) ) : 'free',
		'offer'   => empty( $_POST['offer'] ) ? 0 : 1,
	);
	if ( 'test' === $act || 'queue' === $act ) {
		if ( '' === $c['subject'] || '' === trim( wp_strip_all_tags( $c['message'] ) ) ) {
			return __( 'Please write a subject and a message.', 'vitalstack-marketing' );
		}
	}
	if ( 'test' === $act ) {
		$me = wp_get_current_user();
		if ( ! function_exists( 'vitalstack_mail' ) ) {
			return __( 'The VitalStack theme is not active.', 'vitalstack-marketing' );
		}
		$ok = vitalstack_mail( $me->user_email, '[TEST] ' . $c['subject'], vsm_campaign_html( $c, $me->ID ), array( 'unsubscribe' => vitalstack_unsubscribe_url( $me->ID ) ) );
		return $ok ? sprintf( /* translators: %s: email */ __( 'Test sent to %s.', 'vitalstack-marketing' ), $me->user_email ) : __( 'The test could not be sent. Check your mail (SMTP) setup.', 'vitalstack-marketing' );
	}
	if ( 'queue' === $act ) {
		$cur = get_option( 'vsm_campaign' );
		if ( is_array( $cur ) && 'sending' === ( $cur['status'] ?? '' ) ) {
			return __( 'A campaign is already sending. Wait for it to finish or stop it first.', 'vitalstack-marketing' );
		}
		$c['status']  = 'sending';
		$c['cursor']  = 0;
		$c['sent']    = 0;
		$c['started'] = time();
		update_option( 'vsm_campaign', $c, false );
		return vsm_settings()['on'] ? __( 'Campaign queued. It goes out in small batches every 5 minutes.', 'vitalstack-marketing' ) : __( 'Campaign queued, but Marketing is switched off: switch it on to start sending.', 'vitalstack-marketing' );
	}
	if ( 'stop' === $act ) {
		$cur = get_option( 'vsm_campaign' );
		if ( is_array( $cur ) ) {
			$cur['status'] = 'stopped';
			update_option( 'vsm_campaign', $cur, false );
		}
		return __( 'Campaign stopped.', 'vitalstack-marketing' );
	}
	if ( 'preview' === $act ) {
		$flow = sanitize_key( wp_unslash( $_POST['flow'] ?? 'welcome2' ) );
		list( $subj, $body ) = vsm_compose( get_current_user_id(), $flow );
		if ( '' === $subj || ! function_exists( 'vitalstack_mail' ) ) {
			return __( 'Unknown email.', 'vitalstack-marketing' );
		}
		$me = wp_get_current_user();
		$ok = vitalstack_mail( $me->user_email, '[TEST] ' . $subj, $body, array( 'unsubscribe' => vitalstack_unsubscribe_url( $me->ID ) ) );
		return $ok ? sprintf( /* translators: %s: email */ __( 'Sample sent to %s.', 'vitalstack-marketing' ), $me->user_email ) : __( 'The sample could not be sent. Check your mail (SMTP) setup.', 'vitalstack-marketing' );
	}
	return '';
}

function vsm_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$msg   = vsm_admin_post();
	$s     = vsm_settings();
	$c     = get_option( 'vsm_campaign' );
	$stats = (array) get_option( 'vsm_stats', array() );
	$names = array(
		'welcome2' => __( 'Welcome: first steps (day 2)', 'vitalstack-marketing' ),
		'welcome5' => __( 'Welcome: career tracks + offer (day 5)', 'vitalstack-marketing' ),
		'login'    => __( 'Offer after signing in', 'vitalstack-marketing' ),
		'pricing'  => __( 'Reminder after visiting pricing', 'vitalstack-marketing' ),
		'miss7'    => __( 'We miss you (7 days)', 'vitalstack-marketing' ),
		'miss21'   => __( 'We miss you (21 days)', 'vitalstack-marketing' ),
		'miss45'   => __( 'Still want lessons? (45 days)', 'vitalstack-marketing' ),
		'campaign' => __( 'Campaigns', 'vitalstack-marketing' ),
	);
	$audience = vsm_ready() ? count( vsm_audience( array(), 100000 ) ) : 0;
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'VitalStack Marketing', 'vitalstack-marketing' ); ?></h1>
		<?php if ( ! vsm_ready() ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'This plugin needs the VitalStack theme to be active.', 'vitalstack-marketing' ); ?></p></div>
		<?php endif; ?>
		<?php if ( $msg ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo esc_html( $msg ); ?></p></div>
		<?php endif; ?>
		<p><?php echo esc_html( sprintf( /* translators: %d: people */ __( '%d learners have confirmed their email and subscribed: only they can get these emails. Every email has a one-click unsubscribe link.', 'vitalstack-marketing' ), $audience ) ); ?></p>

		<form method="post">
			<?php wp_nonce_field( 'vsm_admin' ); ?>
			<input type="hidden" name="vsm_action" value="save">
			<h2><?php esc_html_e( 'Automatic emails', 'vitalstack-marketing' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr><th><?php esc_html_e( 'Marketing', 'vitalstack-marketing' ); ?></th><td><label><input type="checkbox" name="on" value="1" <?php checked( $s['on'] ); ?>> <b><?php esc_html_e( 'On: send the emails below', 'vitalstack-marketing' ); ?></b></label><p class="description"><?php esc_html_e( 'Off by default. Set up an SMTP plugin first (for example with your Hostinger email) so emails land in the inbox, not spam.', 'vitalstack-marketing' ); ?></p></td></tr>
				<tr><th><?php esc_html_e( 'Emails', 'vitalstack-marketing' ); ?></th><td>
					<label><input type="checkbox" name="welcome" value="1" <?php checked( $s['welcome'] ); ?>> <?php esc_html_e( 'Welcome series (day 2: first steps; day 5: career tracks and offer)', 'vitalstack-marketing' ); ?></label><br>
					<label><input type="checkbox" name="login" value="1" <?php checked( $s['login'] ); ?>> <?php esc_html_e( 'Offer 45 minutes after a learner signs in (at most every 14 days)', 'vitalstack-marketing' ); ?></label><br>
					<label><input type="checkbox" name="pricing" value="1" <?php checked( $s['pricing'] ); ?>> <?php esc_html_e( 'Friendly reminder 1–3 days after visiting the pricing page without buying', 'vitalstack-marketing' ); ?></label><br>
					<label><input type="checkbox" name="winback" value="1" <?php checked( $s['winback'] ); ?>> <?php esc_html_e( '"We miss you" after 7, 21 and 45 days away', 'vitalstack-marketing' ); ?></label><br>
					<label><input type="checkbox" name="waitlist" value="1" <?php checked( $s['waitlist'] ); ?>> <?php esc_html_e( 'Send offer emails even while sales are closed (they say "Join the waitlist")', 'vitalstack-marketing' ); ?></label>
				</td></tr>
				<tr><th><label for="vsm-gap"><?php esc_html_e( 'Gap between emails', 'vitalstack-marketing' ); ?></label></th><td><input id="vsm-gap" type="number" min="1" max="30" name="gap_days" value="<?php echo esc_attr( $s['gap_days'] ); ?>" class="small-text"> <?php esc_html_e( 'days (one marketing email per person at most this often)', 'vitalstack-marketing' ); ?></td></tr>
				<tr><th><label for="vsm-batch"><?php esc_html_e( 'Campaign speed', 'vitalstack-marketing' ); ?></label></th><td><input id="vsm-batch" type="number" min="5" max="200" name="batch" value="<?php echo esc_attr( $s['batch'] ); ?>" class="small-text"> <?php esc_html_e( 'emails every 5 minutes (keep it low on shared hosting)', 'vitalstack-marketing' ); ?></td></tr>
				<tr><th><label for="vsm-from"><?php esc_html_e( 'Sender name', 'vitalstack-marketing' ); ?></label></th><td><input id="vsm-from" type="text" name="from_name" value="<?php echo esc_attr( $s['from_name'] ); ?>" placeholder="<?php esc_attr_e( 'e.g. Riya from VitalStack', 'vitalstack-marketing' ); ?>" class="regular-text"></td></tr>
			</table>
			<?php submit_button( __( 'Save settings', 'vitalstack-marketing' ) ); ?>
		</form>

		<form method="post" style="margin:8px 0 24px">
			<?php wp_nonce_field( 'vsm_admin' ); ?>
			<input type="hidden" name="vsm_action" value="preview">
			<label for="vsm-flow"><?php esc_html_e( 'See an automatic email in your inbox:', 'vitalstack-marketing' ); ?></label>
			<select id="vsm-flow" name="flow"><?php foreach ( $names as $k => $n ) : if ( 'campaign' === $k ) { continue; } ?><option value="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $n ); ?></option><?php endforeach; ?></select>
			<?php submit_button( __( 'Send me a sample', 'vitalstack-marketing' ), 'secondary', 'submit', false ); ?>
		</form>

		<h2><?php esc_html_e( 'Write a campaign', 'vitalstack-marketing' ); ?></h2>
		<?php if ( is_array( $c ) && 'sending' === ( $c['status'] ?? '' ) ) : ?>
			<div class="notice notice-info inline"><p><?php echo esc_html( sprintf( /* translators: 1: subject, 2: number */ __( 'Sending "%1$s": %2$d sent so far.', 'vitalstack-marketing' ), $c['subject'], (int) $c['sent'] ) ); ?></p>
				<form method="post"><?php wp_nonce_field( 'vsm_admin' ); ?><input type="hidden" name="vsm_action" value="stop"><?php submit_button( __( 'Stop this campaign', 'vitalstack-marketing' ), 'delete small', 'submit', false ); ?></form></div>
		<?php elseif ( is_array( $c ) ) : ?>
			<p class="description"><?php echo esc_html( sprintf( /* translators: 1: subject, 2: status, 3: number */ __( 'Last campaign: "%1$s" (%2$s, %3$d sent).', 'vitalstack-marketing' ), $c['subject'], $c['status'], (int) $c['sent'] ) ); ?></p>
		<?php endif; ?>
		<form method="post">
			<?php wp_nonce_field( 'vsm_admin' ); ?>
			<table class="form-table" role="presentation">
				<tr><th><label for="vsm-subject"><?php esc_html_e( 'Subject', 'vitalstack-marketing' ); ?></label></th><td><input id="vsm-subject" name="subject" class="large-text" maxlength="120" placeholder="<?php esc_attr_e( 'Diwali special: all 3 career tracks at the launch price', 'vitalstack-marketing' ); ?>"></td></tr>
				<tr><th><label for="vsm-heading"><?php esc_html_e( 'Heading', 'vitalstack-marketing' ); ?></label></th><td><input id="vsm-heading" name="heading" class="large-text" maxlength="120"></td></tr>
				<tr><th><label for="vsm-message"><?php esc_html_e( 'Message', 'vitalstack-marketing' ); ?></label></th><td><textarea id="vsm-message" name="message" rows="7" class="large-text"></textarea><p class="description"><?php esc_html_e( 'Plain text, or simple HTML (bold, links, lists). "Hi <name>," is added for you.', 'vitalstack-marketing' ); ?></p></td></tr>
				<tr><th><?php esc_html_e( 'Offer box', 'vitalstack-marketing' ); ?></th><td><label><input type="checkbox" name="offer" value="1" checked> <?php esc_html_e( 'Add the Pro Yearly offer box (₹ or $ for each person; only learners without Premium get it)', 'vitalstack-marketing' ); ?></label></td></tr>
				<tr><th><label for="vsm-button"><?php esc_html_e( 'Button', 'vitalstack-marketing' ); ?></label></th><td><input id="vsm-button" name="button" maxlength="40" placeholder="<?php esc_attr_e( 'Start learning', 'vitalstack-marketing' ); ?>"> <input name="url" type="url" class="regular-text" placeholder="<?php echo esc_attr( home_url( '/' ) ); ?>"></td></tr>
				<tr><th><?php esc_html_e( 'Send to', 'vitalstack-marketing' ); ?></th><td>
					<label><input type="radio" name="segment" value="free" checked> <?php esc_html_e( 'Subscribers without Premium', 'vitalstack-marketing' ); ?></label><br>
					<label><input type="radio" name="segment" value="all"> <?php esc_html_e( 'All subscribers', 'vitalstack-marketing' ); ?></label><br>
					<label><input type="radio" name="segment" value="inactive"> <?php esc_html_e( 'Subscribers who have not signed in for 14+ days', 'vitalstack-marketing' ); ?></label>
				</td></tr>
			</table>
			<p>
				<button type="submit" class="button" name="vsm_action" value="test"><?php esc_html_e( 'Send me a test', 'vitalstack-marketing' ); ?></button>
				<button type="submit" class="button button-primary" name="vsm_action" value="queue" onclick="return confirm('<?php echo esc_js( __( 'Send this campaign to the selected subscribers?', 'vitalstack-marketing' ) ); ?>')"><?php esc_html_e( 'Send campaign', 'vitalstack-marketing' ); ?></button>
			</p>
		</form>

		<h2><?php esc_html_e( 'Sent so far', 'vitalstack-marketing' ); ?></h2>
		<table class="widefat striped" style="max-width:640px"><tbody>
			<?php foreach ( $names as $k => $n ) : ?>
				<tr><td><?php echo esc_html( $n ); ?></td><td style="text-align:right"><b><?php echo (int) ( $stats[ $k ] ?? 0 ); ?></b></td></tr>
			<?php endforeach; ?>
		</tbody></table>
		<p class="description"><?php esc_html_e( 'Links carry utm_source=email, so Google Analytics shows how many visits and sales each email brings.', 'vitalstack-marketing' ); ?></p>
		<h3><?php esc_html_e( 'Recent emails', 'vitalstack-marketing' ); ?></h3>
		<table class="widefat striped" style="max-width:760px"><thead><tr><th><?php esc_html_e( 'When', 'vitalstack-marketing' ); ?></th><th><?php esc_html_e( 'Learner', 'vitalstack-marketing' ); ?></th><th><?php esc_html_e( 'Email', 'vitalstack-marketing' ); ?></th><th><?php esc_html_e( 'Result', 'vitalstack-marketing' ); ?></th></tr></thead><tbody>
			<?php
			foreach ( array_slice( (array) get_option( 'vsm_log', array() ), 0, 30 ) as $row ) :
				$u = get_userdata( (int) $row[1] );
				?>
				<tr><td><?php echo esc_html( wp_date( 'j M, H:i', (int) $row[0] ) ); ?></td><td><?php echo esc_html( $u ? $u->display_name : '#' . (int) $row[1] ); ?></td><td><?php echo esc_html( $names[ $row[2] ] ?? $row[2] ); ?></td><td><?php echo $row[3] ? '✅' : '⚠️'; ?></td></tr>
			<?php endforeach; ?>
		</tbody></table>
		<p class="description"><?php esc_html_e( 'Tip: WordPress runs these timed tasks when someone visits the site. For exact timing, add a real cron job in hPanel that opens wp-cron.php every 5 minutes.', 'vitalstack-marketing' ); ?></p>
	</div>
	<?php
}
