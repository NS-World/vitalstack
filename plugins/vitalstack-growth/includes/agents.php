<?php
/**
 * The agents: rule-based helpers that run on a schedule inside WordPress.
 *
 *   - Sales agent: orders, revenue, unfinished checkouts, best plans.
 *   - Marketing agent: sign-ups, subscribers, upcoming festivals, share kit.
 *   - Testing & Security agent: checks the pages, payments, email, AdSense
 *     and security settings every day and emails the admin only when
 *     something needs attention. A Monday report sums up the week.
 *
 * No AI service and no outside calls (only the site checking its own
 * pages), no public endpoints: nothing here can be reached or steered by a
 * visitor or a bot. Everything is read-only except the admin's own buttons.
 *
 * @package VitalStackGrowth
 */

defined( 'ABSPATH' ) || exit;

/* ── Sales agent ───────────────────────────────────────────────────────── */

/**
 * Order numbers for the last $days days (live orders only unless $test).
 *
 * @return array{paid:int,created:int,failed:int,refunded:int,revenue:array,plans:array,coupons:array,recent:array}
 */
function vsg_sales( $days, $test = false ) {
	$out = array(
		'paid'     => 0,
		'created'  => 0,
		'failed'   => 0,
		'refunded' => 0,
		'revenue'  => array(),
		'plans'    => array(),
		'coupons'  => array(),
		'recent'   => array(),
	);
	if ( ! post_type_exists( 'vs_order' ) ) {
		return $out;
	}
	$ids = get_posts(
		array(
			'post_type'        => 'vs_order',
			'post_status'      => 'any',
			'numberposts'      => 2000,
			'fields'           => 'ids',
			'date_query'       => array( array( 'after' => gmdate( 'Y-m-d H:i:s', time() - $days * DAY_IN_SECONDS ), 'column' => 'post_date_gmt' ) ),
			'suppress_filters' => true,
			'orderby'          => 'date',
			'order'            => 'DESC',
		)
	);
	$plans = function_exists( 'vitalstack_pay_plans' ) ? vitalstack_pay_plans() : array();
	foreach ( $ids as $id ) {
		$mode = (string) get_post_meta( $id, '_vs_mode', true );
		if ( ! $test && 'live' !== $mode ) {
			continue;
		}
		$status = (string) get_post_meta( $id, '_vs_status', true );
		$plan   = (string) get_post_meta( $id, '_vs_plan', true );
		$cur    = strtoupper( (string) get_post_meta( $id, '_vs_currency', true ) );
		$amount = (int) get_post_meta( $id, '_vs_amount', true ) / 100;
		++$out['created'];
		if ( in_array( $status, array( 'paid', 'refunded', 'disputed' ), true ) ) {
			if ( 'paid' === $status ) {
				++$out['paid'];
				$out['revenue'][ $cur ] = ( $out['revenue'][ $cur ] ?? 0 ) + $amount;
				$name                   = isset( $plans[ $plan ]['title'] ) ? wp_strip_all_tags( $plans[ $plan ]['title'] ) : $plan;
				$out['plans'][ $name ]  = ( $out['plans'][ $name ] ?? 0 ) + 1;
				$coupon                 = (string) get_post_meta( $id, '_vs_coupon', true );
				if ( '' !== $coupon ) {
					$out['coupons'][ $coupon ] = ( $out['coupons'][ $coupon ] ?? 0 ) + 1;
				}
			} else {
				++$out['refunded'];
			}
		} elseif ( 'failed' === $status ) {
			++$out['failed'];
		}
		if ( count( $out['recent'] ) < 10 ) {
			$out['recent'][] = array(
				'date'   => get_the_date( 'Y-m-d H:i', $id ),
				'plan'   => isset( $plans[ $plan ]['title'] ) ? wp_strip_all_tags( $plans[ $plan ]['title'] ) : $plan,
				'amount' => $cur . ' ' . number_format( $amount, 'INR' === $cur ? 0 : 2 ),
				'status' => $status,
				'buyer'  => vsg_mask_email( (string) get_the_author_meta( 'user_email', (int) get_post_field( 'post_author', $id ) ) ),
			);
		}
	}
	arsort( $out['plans'] );
	arsort( $out['coupons'] );
	return $out;
}

/** a***@g***.com: enough to recognise, not enough to leak in a screenshot or email. */
function vsg_mask_email( $email ) {
	if ( ! is_email( $email ) ) {
		return '—';
	}
	list( $u, $d ) = explode( '@', $email, 2 );
	$parts         = explode( '.', $d );
	$tld           = array_pop( $parts );
	return substr( $u, 0, 1 ) . '***@' . substr( implode( '.', $parts ), 0, 1 ) . '***.' . $tld;
}

/** ₹1,499 + $49 style revenue label. */
function vsg_money( $rev ) {
	$r = array();
	foreach ( $rev as $cur => $amt ) {
		if ( '' === $cur && ! $amt ) {
			continue;
		}
		$r[] = ( 'INR' === $cur ? '₹' : ( 'USD' === $cur ? '$' : $cur . ' ' ) ) . number_format( $amt, 'INR' === $cur ? 0 : 2 );
	}
	return $r ? implode( ' + ', $r ) : '0';
}

/** Share of started checkouts that were paid, as a whole percent. */
function vsg_conversion( $s ) {
	return $s['created'] ? (int) round( $s['paid'] / $s['created'] * 100 ) : 0;
}

/** Tips from the numbers: plain rules, not guesses. */
function vsg_sales_tips( $s7, $s30 ) {
	$tips = array();
	if ( function_exists( 'vitalstack_pay_ready' ) && ! vitalstack_pay_ready() ) {
		$tips[] = __( 'Sales are closed, so visitors can only join the waitlist. Open sales in Settings → VitalStack Payments when Razorpay is ready.', 'vitalstack-growth' );
	}
	if ( $s7['created'] >= 5 && vsg_conversion( $s7 ) < 30 ) {
		$tips[] = __( 'Many checkouts started this week but few were paid. Try a test purchase on a phone, and check that UPI works and that the price shown is the price charged.', 'vitalstack-growth' );
	}
	if ( ! $s30['paid'] && vsg_offer_running() && ! vsg_offer_end() ) {
		$tips[] = __( 'No sales in 30 days. A real deadline helps people decide: set an end date for the offer (a countdown appears automatically).', 'vitalstack-growth' );
	}
	if ( vsg_offer_running() && ! vsg_max_saving() ) {
		$tips[] = __( 'The offer shows no saving. If prices go up after the offer, enter the regular prices in the Offer tab: "Save X%" and the struck-through price then appear on the site.', 'vitalstack-growth' );
	}
	if ( $s30['failed'] >= 3 ) {
		$tips[] = __( 'Several payments failed this month. Check that International payments are on in Razorpay if you sell in dollars.', 'vitalstack-growth' );
	}
	return $tips;
}

/* ── Marketing agent ───────────────────────────────────────────────────── */

function vsg_marketing() {
	global $wpdb;
	$since7  = gmdate( 'Y-m-d H:i:s', time() - 7 * DAY_IN_SECONDS );
	$since30 = gmdate( 'Y-m-d H:i:s', time() - 30 * DAY_IN_SECONDS );
	// phpcs:disable WordPress.DB.DirectDatabaseQuery
	$signups7  = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->users} WHERE user_registered >= %s", $since7 ) );
	$signups30 = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->users} WHERE user_registered >= %s", $since30 ) );
	$subs      = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->usermeta} WHERE meta_key = %s AND meta_value = %s", 'vs_subscribed', '1' ) );
	$subs_ok   = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->usermeta} s JOIN {$wpdb->usermeta} v ON v.user_id = s.user_id AND v.meta_key = %s AND v.meta_value = %s WHERE s.meta_key = %s AND s.meta_value = %s", 'vs_email_verified', '1', 'vs_subscribed', '1' ) );
	$users     = (int) ( count_users()['total_users'] ?? 0 );
	// phpcs:enable WordPress.DB.DirectDatabaseQuery
	$fest = array();
	if ( function_exists( 'vsf_festivals' ) ) {
		$today = current_time( 'Y-m-d' );
		foreach ( vsf_festivals() as $key => $f ) {
			foreach ( array_chunk( (array) ( $f['dates'] ?? array() ), 2 ) as $win ) {
				if ( 2 === count( $win ) && strlen( $win[0] ) === 10 && $win[0] >= $today ) {
					$fest[] = array( $win[0], $f['name'] );
				}
			}
		}
		sort( $fest );
		$fest = array_slice( $fest, 0, 4 );
	}
	return array(
		'signups7'  => $signups7,
		'signups30' => $signups30,
		'users'     => $users,
		'subs'      => $subs,
		'subs_ok'   => $subs_ok,
		'festivals' => $fest,
		'vsm'       => function_exists( 'vsm_settings' ) ? ! empty( vsm_settings()['on'] ) : null,
	);
}

/** Ready-to-post messages with tracked links, built from the site's own pages. */
function vsg_share_kit() {
	$utm  = function ( $url, $src ) {
		return add_query_arg( array( 'utm_source' => $src, 'utm_medium' => 'social', 'utm_campaign' => 'share_kit' ), $url );
	};
	$kit  = array();
	$site = wp_strip_all_tags( get_bloginfo( 'name' ) );
	if ( function_exists( 'vitalstack_pricing_url' ) && vsg_offer_running() ) {
		$p = function_exists( 'vitalstack_offer_prices' ) ? vitalstack_offer_prices() : null;
		$kit[] = array(
			__( 'The offer', 'vitalstack-growth' ),
			sprintf(
				/* translators: 1: offer name, 2: site, 3: price */
				__( '🔥 %1$s at %2$s: every career track (Data Science, Gen AI, AI Agents) with projects and a certificate, %3$s for a whole year. One payment, no auto-renewal.', 'vitalstack-growth' ),
				vsg_settings()['name'],
				$site,
				$p ? $p['inr']['now'] . ' / ' . $p['usd']['now'] : ''
			),
			$utm( vitalstack_pricing_url(), 'share' ),
		);
	}
	$pages = array(
		array( 'dsa-problems', __( 'Interview practice', 'vitalstack-growth' ), __( '51 classic coding interview problems, each solved step by step in Python, Java, C++ and JavaScript, with an animation of how the solution works. Free.', 'vitalstack-growth' ) ),
		array( 'tools', __( 'Free tools', 'vitalstack-growth' ), __( 'Run Python, practise SQL and build a whole website in your browser, nothing to install. Free tools for learners.', 'vitalstack-growth' ) ),
	);
	foreach ( $pages as $pg ) {
		$page = get_page_by_path( $pg[0] );
		if ( $page && 'publish' === $page->post_status ) {
			$kit[] = array( $pg[1], $pg[2], $utm( get_permalink( $page ), 'share' ) );
		}
	}
	if ( function_exists( 'vitalstack_study_plan_url' ) ) {
		$kit[] = array( __( 'Study plan', 'vitalstack-growth' ), __( 'Want to learn data science but don’t know where to start? A free 30-day plan: 15–45 minutes a day, from setup to your first project.', 'vitalstack-growth' ), $utm( vitalstack_study_plan_url(), 'share' ) );
	}
	if ( function_exists( 'vitalstack_playground_url' ) ) {
		$kit[] = array( __( 'Website Builder', 'vitalstack-growth' ), __( 'Build your first website in the browser: pick a template, edit HTML, CSS and JavaScript, see it live on phone and desktop, and download it.', 'vitalstack-growth' ), $utm( vitalstack_playground_url(), 'share' ) );
	}
	return $kit;
}

/* ── Testing & Security agent ──────────────────────────────────────────── */

/** One check: array( status ok|warn|bad, area, label, detail, fix ). */
function vsg_check( $status, $area, $label, $detail, $fix = '' ) {
	return array( $status, $area, $label, $detail, $fix );
}

/** The site's own key pages answer with 200 (the site asking itself; no outside service). */
function vsg_check_pages() {
	$urls = array( __( 'Home', 'vitalstack-growth' ) => home_url( '/' ) );
	if ( function_exists( 'vitalstack_pricing_url' ) ) {
		$urls[ __( 'Pricing', 'vitalstack-growth' ) ] = vitalstack_pricing_url();
	}
	if ( function_exists( 'vitalstack_playground_url' ) ) {
		$urls[ __( 'Website Builder', 'vitalstack-growth' ) ] = vitalstack_playground_url();
	}
	$lesson = get_posts( array( 'post_type' => 'tutorials', 'numberposts' => 1, 'post_status' => 'publish', 'fields' => 'ids' ) );
	if ( $lesson ) {
		$urls[ __( 'A lesson', 'vitalstack-growth' ) ] = get_permalink( $lesson[0] );
	}
	$out = array();
	foreach ( $urls as $name => $url ) {
		$r    = wp_remote_get( $url, array( 'timeout' => 8, 'redirection' => 3, 'user-agent' => 'VitalStack-Growth-Desk-Check' ) );
		$code = is_wp_error( $r ) ? 0 : (int) wp_remote_retrieve_response_code( $r );
		$out[] = 200 === $code
			? vsg_check( 'ok', __( 'Pages', 'vitalstack-growth' ), $name, sprintf( /* translators: %s: url */ __( '%s opens.', 'vitalstack-growth' ), $url ) )
			: vsg_check( is_wp_error( $r ) ? 'warn' : 'bad', __( 'Pages', 'vitalstack-growth' ), $name, is_wp_error( $r ) ? sprintf( /* translators: 1: url, 2: error */ __( 'Could not check %1$s (%2$s). Some hosts block a site from opening itself; open it in a browser.', 'vitalstack-growth' ), $url, $r->get_error_message() ) : sprintf( /* translators: 1: url, 2: HTTP code */ __( '%1$s answered %2$d.', 'vitalstack-growth' ), $url, $code ), __( 'Open the page; after an update, save Settings → Permalinks once.', 'vitalstack-growth' ) );
	}
	return $out;
}

function vsg_checks( $pages = true ) {
	$c = array();
	// Payments.
	if ( function_exists( 'vitalstack_pay_keys' ) ) {
		$ps                          = vitalstack_pay_settings();
		list( $id, $sec, $hook, $m ) = vitalstack_pay_keys();
		$open                        = ! empty( $ps['open'] );
		$c[] = $open && $id && $sec
			? vsg_check( 'live' === $m ? 'ok' : 'warn', __( 'Selling', 'vitalstack-growth' ), __( 'Payments', 'vitalstack-growth' ), 'live' === $m ? __( 'Sales are open in Live mode.', 'vitalstack-growth' ) : __( 'Sales are open in Test mode: no real money is taken.', 'vitalstack-growth' ), 'live' === $m ? '' : __( 'Settings → VitalStack Payments: switch to Live with the live keys.', 'vitalstack-growth' ) )
			: vsg_check( 'warn', __( 'Selling', 'vitalstack-growth' ), __( 'Payments', 'vitalstack-growth' ), $open ? __( 'Sales are open but the Razorpay keys are missing.', 'vitalstack-growth' ) : __( 'Sales are closed: visitors see "Join the waitlist".', 'vitalstack-growth' ), __( 'Follow the go-live guide, step 5.', 'vitalstack-growth' ) );
		if ( $open ) {
			$c[] = $hook
				? vsg_check( 'ok', __( 'Selling', 'vitalstack-growth' ), __( 'Payment webhook', 'vitalstack-growth' ), __( 'A webhook secret is saved, so payments are confirmed even if the buyer closes the tab.', 'vitalstack-growth' ) )
				: vsg_check( 'bad', __( 'Selling', 'vitalstack-growth' ), __( 'Payment webhook', 'vitalstack-growth' ), __( 'No webhook secret: a buyer who closes the tab during payment may not get access automatically.', 'vitalstack-growth' ), __( 'Razorpay → Webhooks: add the URL shown in Settings → VitalStack Payments, and paste the same secret there.', 'vitalstack-growth' ) );
		}
	}
	// Offer.
	$s   = vsg_settings();
	$end = vsg_offer_end();
	if ( ! empty( $s['offer_on'] ) && $end && time() >= $end ) {
		$c[] = vsg_check( 'warn', __( 'Selling', 'vitalstack-growth' ), __( 'Offer', 'vitalstack-growth' ), __( 'The offer has ended, so the site no longer shows it.', 'vitalstack-growth' ), __( 'Growth Desk → Offer: set a new end date or a new offer.', 'vitalstack-growth' ) );
	}
	if ( function_exists( 'vsf_settings' ) && 'force' === ( vsf_settings()['mode'] ?? '' ) ) {
		$c[] = vsg_check( 'warn', __( 'Selling', 'vitalstack-growth' ), __( 'Festive look', 'vitalstack-growth' ), __( 'The Festive plugin is forced to one festival, so its sale name shows even outside the festival dates.', 'vitalstack-growth' ), __( 'Settings → VitalStack Festive: choose Auto.', 'vitalstack-growth' ) );
	}
	// Email.
	if ( function_exists( 'vitalstack_mail_checks_cached' ) ) {
		$bad = array();
		foreach ( vitalstack_mail_checks_cached() as $m ) {
			if ( 'bad' === $m[0] ) {
				$bad[] = $m[1];
			}
		}
		$c[] = $bad
			? vsg_check( 'bad', __( 'Email', 'vitalstack-growth' ), __( 'Inbox, not spam', 'vitalstack-growth' ), sprintf( /* translators: %s: failing checks */ __( 'Failing: %s. Sign-in codes and offers may land in spam.', 'vitalstack-growth' ), implode( ', ', $bad ) ), __( 'Tools → Email check shows the exact fix for each line.', 'vitalstack-growth' ) )
			: vsg_check( 'ok', __( 'Email', 'vitalstack-growth' ), __( 'Inbox, not spam', 'vitalstack-growth' ), __( 'SMTP and the email DNS records look right.', 'vitalstack-growth' ) );
	}
	// AdSense and search.
	$c[] = get_option( 'blog_public' )
		? vsg_check( 'ok', __( 'AdSense & search', 'vitalstack-growth' ), __( 'Visible to Google', 'vitalstack-growth' ), __( 'Search engines may index the site.', 'vitalstack-growth' ) )
		: vsg_check( 'bad', __( 'AdSense & search', 'vitalstack-growth' ), __( 'Visible to Google', 'vitalstack-growth' ), __( '"Discourage search engines" is ticked: Google and AdSense can\'t review the site. (Fine on a test copy only.)', 'vitalstack-growth' ), __( 'Settings → Reading: untick it.', 'vitalstack-growth' ) );
	if ( function_exists( 'vitalstack_adsense_client' ) ) {
		$c[] = vitalstack_adsense_client()
			? vsg_check( 'ok', __( 'AdSense & search', 'vitalstack-growth' ), __( 'AdSense code and ads.txt', 'vitalstack-growth' ), __( 'The publisher ID is set; the theme adds the code and serves /ads.txt.', 'vitalstack-growth' ) )
			: vsg_check( 'warn', __( 'AdSense & search', 'vitalstack-growth' ), __( 'AdSense code and ads.txt', 'vitalstack-growth' ), __( 'No publisher ID yet.', 'vitalstack-growth' ), __( 'After AdSense gives you an ID: Appearance → Customize → VitalStack: Google AdSense.', 'vitalstack-growth' ) );
	}
	if ( function_exists( 'vsah_checks' ) ) {
		$fails = array();
		foreach ( vsah_checks() as $a ) {
			if ( 'fail' === $a[0] && false === stripos( $a[1], 'ads.txt' ) ) {
				$fails[] = $a[1];
			}
		}
		$c[] = $fails
			? vsg_check( 'bad', __( 'AdSense & search', 'vitalstack-growth' ), __( 'AdSense readiness', 'vitalstack-growth' ), implode( ' · ', $fails ), __( 'Tools → AdSense Helper shows each item and how to fix it.', 'vitalstack-growth' ) )
			: vsg_check( 'ok', __( 'AdSense & search', 'vitalstack-growth' ), __( 'AdSense readiness', 'vitalstack-growth' ), __( 'The AdSense Helper finds nothing to fix.', 'vitalstack-growth' ) );
	}
	// Security.
	$c[] = is_ssl() || 0 === strpos( home_url(), 'https://' )
		? vsg_check( 'ok', __( 'Security', 'vitalstack-growth' ), 'HTTPS', __( 'The site uses HTTPS.', 'vitalstack-growth' ) )
		: vsg_check( 'bad', __( 'Security', 'vitalstack-growth' ), 'HTTPS', __( 'The site address is not HTTPS.', 'vitalstack-growth' ), __( 'Settings → General: use https:// (hPanel gives a free SSL certificate).', 'vitalstack-growth' ) );
	if ( username_exists( 'admin' ) && user_can( username_exists( 'admin' ), 'manage_options' ) ) {
		$c[] = vsg_check( 'warn', __( 'Security', 'vitalstack-growth' ), __( 'Admin username', 'vitalstack-growth' ), __( 'An administrator is called "admin", the first name every attack tries.', 'vitalstack-growth' ), __( 'Users → Add New: create an administrator with another username and a strong password, sign in with it, then delete "admin" and give its content to the new user.', 'vitalstack-growth' ) );
	}
	$admins = get_users( array( 'role' => 'administrator', 'fields' => array( 'ID', 'user_login' ) ) );
	if ( function_exists( 'vitalstack_totp_on' ) ) {
		$no2fa = array();
		foreach ( $admins as $a ) {
			if ( ! vitalstack_totp_on( (int) $a->ID ) ) {
				$no2fa[] = $a->user_login;
			}
		}
		$c[] = $no2fa
			? vsg_check( 'warn', __( 'Security', 'vitalstack-growth' ), __( 'Two-factor sign-in', 'vitalstack-growth' ), sprintf( /* translators: %s: usernames */ __( 'Administrators without an authenticator app: %s.', 'vitalstack-growth' ), implode( ', ', $no2fa ) ), __( 'Each admin: sign in on the site → Account → two-factor → scan the QR code.', 'vitalstack-growth' ) )
			: vsg_check( 'ok', __( 'Security', 'vitalstack-growth' ), __( 'Two-factor sign-in', 'vitalstack-growth' ), __( 'Every administrator uses an authenticator app.', 'vitalstack-growth' ) );
	}
	if ( count( $admins ) > 3 ) {
		$c[] = vsg_check( 'warn', __( 'Security', 'vitalstack-growth' ), __( 'Administrators', 'vitalstack-growth' ), sprintf( /* translators: %d: count */ __( '%d administrator accounts. Each one is a way in.', 'vitalstack-growth' ), count( $admins ) ), __( 'Users: give people the lowest role they need (Editor or Author).', 'vitalstack-growth' ) );
	}
	if ( defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		$c[] = vsg_check( 'bad', __( 'Security', 'vitalstack-growth' ), __( 'Error messages', 'vitalstack-growth' ), __( 'PHP errors are shown on pages, which can reveal file paths.', 'vitalstack-growth' ), __( 'wp-config.php: set WP_DEBUG to false (or WP_DEBUG_DISPLAY to false).', 'vitalstack-growth' ) );
	}
	$c[] = version_compare( PHP_VERSION, '8.1', '>=' )
		? vsg_check( 'ok', __( 'Security', 'vitalstack-growth' ), 'PHP', sprintf( /* translators: %s: version */ __( 'PHP %s gets security fixes.', 'vitalstack-growth' ), PHP_VERSION ) )
		: vsg_check( 'warn', __( 'Security', 'vitalstack-growth' ), 'PHP', sprintf( /* translators: %s: version */ __( 'PHP %s is old.', 'vitalstack-growth' ), PHP_VERSION ), __( 'hPanel → Advanced → PHP Configuration: choose 8.2 or newer.', 'vitalstack-growth' ) );
	$core    = get_site_transient( 'update_core' );
	$plugins = get_site_transient( 'update_plugins' );
	$pending = ( isset( $core->updates[0]->response ) && 'upgrade' === $core->updates[0]->response ? 1 : 0 ) + ( isset( $plugins->response ) ? count( (array) $plugins->response ) : 0 );
	$c[] = $pending
		? vsg_check( 'warn', __( 'Security', 'vitalstack-growth' ), __( 'Updates', 'vitalstack-growth' ), sprintf( /* translators: %d: count */ _n( '%d update waiting (WordPress or plugins).', '%d updates waiting (WordPress or plugins).', $pending, 'vitalstack-growth' ), $pending ), __( 'Dashboard → Updates (back up first).', 'vitalstack-growth' ) )
		: vsg_check( 'ok', __( 'Security', 'vitalstack-growth' ), __( 'Updates', 'vitalstack-growth' ), __( 'WordPress and plugins are up to date.', 'vitalstack-growth' ) );
	$c[] = defined( 'DISALLOW_FILE_EDIT' ) && DISALLOW_FILE_EDIT
		? vsg_check( 'ok', __( 'Security', 'vitalstack-growth' ), __( 'Code editor', 'vitalstack-growth' ), __( 'The theme and plugin file editors are switched off, so a stolen admin login can\'t change code.', 'vitalstack-growth' ) )
		: vsg_check( 'warn', __( 'Security', 'vitalstack-growth' ), __( 'Code editor', 'vitalstack-growth' ), __( 'The file editors are on.', 'vitalstack-growth' ), __( 'wp-config.php: define( \'DISALLOW_FILE_EDIT\', true );', 'vitalstack-growth' ) );
	if ( $pages ) {
		$c = array_merge( vsg_check_pages(), $c );
	}
	return $c;
}

/* ── Schedules and emails ──────────────────────────────────────────────── */

function vsg_schedule() {
	if ( ! wp_next_scheduled( 'vsg_daily' ) ) {
		wp_schedule_event( strtotime( 'tomorrow 03:17' ), 'daily', 'vsg_daily' );
	}
	if ( ! wp_next_scheduled( 'vsg_weekly' ) ) {
		wp_schedule_event( strtotime( 'next monday 08:13' ), 'weekly', 'vsg_weekly' );
	}
}
add_action( 'init', 'vsg_schedule' );

function vsg_unschedule() {
	wp_clear_scheduled_hook( 'vsg_daily' );
	wp_clear_scheduled_hook( 'vsg_weekly' );
}

/** Plain-text report lines for the checks that need attention. */
function vsg_problem_lines( $checks ) {
	$lines = array();
	foreach ( $checks as $c ) {
		if ( 'ok' !== $c[0] ) {
			$lines[] = ( 'bad' === $c[0] ? '✗ ' : '! ' ) . $c[1] . ' / ' . $c[2] . ': ' . $c[3] . ( $c[4] ? "\n    → " . $c[4] : '' );
		}
	}
	return $lines;
}

function vsg_send( $subject, $body ) {
	$site = wp_strip_all_tags( get_bloginfo( 'name' ) );
	return wp_mail( vsg_report_email(), '[' . $site . '] ' . $subject, $body . "\n\n— " . __( 'Growth Desk', 'vitalstack-growth' ) . ' · ' . admin_url( 'admin.php?page=vitalstack-growth' ) );
}

/** Daily: the Testing agent runs and emails only when something is red. */
add_action(
	'vsg_daily',
	function () {
		$checks = vsg_checks();
		update_option( 'vsg_last_check', array( time(), $checks ), false );
		if ( empty( vsg_settings()['daily_test'] ) ) {
			return;
		}
		$bad = array_filter( $checks, function ( $c ) { return 'bad' === $c[0]; } );
		if ( $bad ) {
			vsg_send( __( 'Testing agent: something needs you', 'vitalstack-growth' ), implode( "\n", vsg_problem_lines( $bad ) ) );
		}
	}
);

/** Monday: the week in numbers. */
function vsg_weekly_body() {
	$s = vsg_sales( 7 );
	$m = vsg_marketing();
	$lines   = array();
	$lines[] = __( 'SALES (last 7 days, live)', 'vitalstack-growth' );
	/* translators: 1: paid orders, 2: revenue, 3: started checkouts, 4: percent */
	$lines[] = sprintf( __( 'Paid orders: %1$d · Revenue: %2$s · Checkouts started: %3$d (%4$d%% paid)', 'vitalstack-growth' ), $s['paid'], vsg_money( $s['revenue'] ), $s['created'], vsg_conversion( $s ) );
	foreach ( array_slice( $s['plans'], 0, 3, true ) as $plan => $n ) {
		$lines[] = '  · ' . $plan . ': ' . $n;
	}
	$lines[] = '';
	$lines[] = __( 'MARKETING', 'vitalstack-growth' );
	/* translators: 1: sign-ups, 2: subscribers, 3: confirmed subscribers */
	$lines[] = sprintf( __( 'New sign-ups: %1$d · Subscribers: %2$d (%3$d confirmed and reachable)', 'vitalstack-growth' ), $m['signups7'], $m['subs'], $m['subs_ok'] );
	foreach ( $m['festivals'] as $f ) {
		/* translators: 1: festival, 2: date */
		$lines[] = '  · ' . sprintf( __( 'Coming up: %1$s on %2$s (plan an offer email a week before)', 'vitalstack-growth' ), $f[1], $f[0] );
	}
	foreach ( vsg_sales_tips( $s, vsg_sales( 30 ) ) as $t ) {
		$lines[] = '  → ' . $t;
	}
	$lines[] = '';
	$lines[] = __( 'SITE HEALTH', 'vitalstack-growth' );
	$p       = vsg_problem_lines( vsg_checks() );
	$lines   = array_merge( $lines, $p ? $p : array( __( 'Everything checked is fine.', 'vitalstack-growth' ) ) );
	return implode( "\n", $lines );
}

add_action(
	'vsg_weekly',
	function () {
		if ( ! empty( vsg_settings()['weekly_report'] ) ) {
			vsg_send( __( 'Your week: sales, sign-ups and site health', 'vitalstack-growth' ), vsg_weekly_body() );
		}
	}
);
