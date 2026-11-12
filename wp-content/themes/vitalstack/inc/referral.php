<?php
/**
 * Invite friends (4.13).
 *
 *   - Every reader gets an invite code (VS + 6 letters/digits) and a link,
 *     https://site/?ref=CODE, that works on any page.
 *   - Opening an invite link (signed out) keeps the code in a cookie for 30
 *     days and redirects to the clean address, so search engines and ads only
 *     ever see one URL per page.
 *   - An account created with that cookie is linked to the friend who invited it.
 *   - The new reader gets a discount on their first premium purchase with the
 *     code; on /pricing/ it is filled in for them.
 *   - The inviter earns Pro days (every track): for each friend who buys,
 *     once the refund window has passed, and for every few friends who really
 *     start learning (at least one lesson completed).
 *
 * Rewards are worked out from the records each time (orders, progress), never
 * from counters a visitor can push, and are capped per year. A refunded order
 * or a fake, duplicate or throw-away account never earns anything.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

const VITALSTACK_REF_COOKIE = 'vs_ref';

/* ── Settings ─────────────────────────────────────────────────────────── */

function vitalstack_ref_settings() {
	$s = get_option( 'vs_ref', array() );
	return array_merge(
		array(
			'on'          => true,
			'percent'     => 10, // friend's discount on their first purchase
			'paid_days'   => 30, // Pro days for the inviter per friend who buys
			'active_need' => 3,  // friends who complete a lesson…
			'active_days' => 7,  // …earn the inviter this many Pro days
			'cap_paid'    => 12, // rewards of each kind per 12 months
			'cap_active'  => 12,
		),
		is_array( $s ) ? $s : array()
	);
}

/* ── Codes ────────────────────────────────────────────────────────────── */

function vitalstack_ref_is_code( $code ) {
	return (bool) preg_match( '/^VS[A-HJ-NP-Z2-9]{6}$/', (string) $code );
}

/** The reader's invite code, made the first time it is needed. */
function vitalstack_ref_code( $user_id ) {
	$user_id = (int) $user_id;
	if ( ! $user_id ) {
		return '';
	}
	$code = (string) get_user_meta( $user_id, 'vs_ref_code', true );
	if ( vitalstack_ref_is_code( $code ) ) {
		return $code;
	}
	$abc = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // no 0/O or 1/I to misread
	do {
		$code = 'VS';
		for ( $i = 0; $i < 6; $i++ ) {
			$code .= $abc[ random_int( 0, 31 ) ];
		}
	} while ( vitalstack_ref_owner( $code ) );
	update_user_meta( $user_id, 'vs_ref_code', $code );
	return $code;
}

/** User ID that owns an invite code, or 0. */
function vitalstack_ref_owner( $code ) {
	$code = strtoupper( trim( (string) $code ) );
	if ( ! vitalstack_ref_is_code( $code ) ) {
		return 0;
	}
	$ids = get_users(
		array(
			'meta_key'   => 'vs_ref_code', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value' => $code, // phpcs:ignore WordPress.DB.SlowDBQuery
			'number'     => 1,
			'fields'     => 'ID',
		)
	);
	return $ids ? (int) $ids[0] : 0;
}

function vitalstack_ref_link( $user_id ) {
	return add_query_arg( 'ref', vitalstack_ref_code( $user_id ), home_url( '/' ) );
}

/* ── Opening an invite link ───────────────────────────────────────────── */

function vitalstack_ref_capture() {
	if ( is_admin() || ! isset( $_GET['ref'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- a public link
		return;
	}
	$code = strtoupper( sanitize_text_field( wp_unslash( $_GET['ref'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
	if ( vitalstack_ref_settings()['on'] && ! is_user_logged_in() && vitalstack_ref_owner( $code ) ) {
		$opts = array(
			'expires'  => time() + 30 * DAY_IN_SECONDS,
			'path'     => COOKIEPATH ? COOKIEPATH : '/',
			'domain'   => COOKIE_DOMAIN,
			'secure'   => is_ssl(),
			'httponly' => true,
			'samesite' => 'Lax',
		);
		setcookie( VITALSTACK_REF_COOKIE, $code, $opts );
		// A second, readable cookie only tells the page to show the welcome note,
		// so cached pages stay the same for everyone.
		setcookie( 'vs_ref_hi', '1', array( 'httponly' => false ) + $opts );
	}
	nocache_headers();
	wp_safe_redirect( remove_query_arg( 'ref' ), 302 );
	exit;
}
add_action( 'template_redirect', 'vitalstack_ref_capture', 0 );

/** A new account made after opening an invite link is linked to the inviter. */
function vitalstack_ref_on_register( $user_id ) {
	// Sign-up runs through admin-post.php, so is_admin() can't be used here; an
	// administrator adding users in the dashboard is the case to skip.
	if ( current_user_can( 'create_users' ) || empty( $_COOKIE[ VITALSTACK_REF_COOKIE ] ) || ! vitalstack_ref_settings()['on'] ) {
		return;
	}
	$owner = vitalstack_ref_owner( sanitize_text_field( wp_unslash( $_COOKIE[ VITALSTACK_REF_COOKIE ] ) ) );
	if ( $owner && $owner !== (int) $user_id ) {
		update_user_meta( $user_id, 'vs_ref_by', $owner );
		update_user_meta( $user_id, 'vs_ref_at', time() );
	}
	if ( ! headers_sent() ) {
		setcookie( 'vs_ref_hi', '', time() - 3600, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN );
	}
}
add_action( 'user_register', 'vitalstack_ref_on_register' );

/* ── The friend's discount ────────────────────────────────────────────── */

/** Whether a reader already has a paid order. */
function vitalstack_ref_has_paid( $user_id ) {
	$ids = get_posts(
		array(
			'post_type'   => 'vs_order',
			'post_status' => 'any',
			'author'      => (int) $user_id,
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_key'    => '_vs_status', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'  => 'paid', // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	return ! empty( $ids );
}

/**
 * Price with an invite code, for vitalstack_pay_price(). Only called for codes
 * that are not one of the site's own coupons.
 *
 * @return array|WP_Error|null [minor units, code, label]; null if it isn't an invite code.
 */
function vitalstack_ref_price( $code, $plan, $cur, $base ) {
	$s = vitalstack_ref_settings();
	if ( ! $s['on'] || ! vitalstack_ref_is_code( $code ) ) {
		return null;
	}
	$owner = vitalstack_ref_owner( $code );
	if ( ! $owner ) {
		return null;
	}
	$uid = get_current_user_id();
	if ( $uid && $owner === $uid ) {
		return new WP_Error( 'bad_coupon', __( 'That’s your own invite code. Share it with friends: they save, and you earn free Pro days.', 'vitalstack' ), array( 'status' => 400 ) );
	}
	if ( $uid && vitalstack_ref_has_paid( $uid ) ) {
		return new WP_Error( 'bad_coupon', __( 'Invite codes work on your first purchase only.', 'vitalstack' ), array( 'status' => 400 ) );
	}
	$pct   = max( 1, min( 50, (int) $s['percent'] ) );
	$minor = max( 100, (int) round( $base * 100 * ( 100 - $pct ) / 100 ) );
	return array( $minor, strtoupper( $code ), vitalstack_pay_price_label( $minor / 100, $cur ) );
}

/** The invite code a signed-in reader may use on /pricing/, or ''. */
function vitalstack_ref_code_for_buyer( $user_id ) {
	$owner = (int) get_user_meta( $user_id, 'vs_ref_by', true );
	if ( ! $owner || ! vitalstack_ref_settings()['on'] || vitalstack_ref_has_paid( $user_id ) ) {
		return '';
	}
	$code = (string) get_user_meta( $owner, 'vs_ref_code', true );
	return vitalstack_ref_is_code( $code ) ? $code : '';
}

/* ── Who counts ───────────────────────────────────────────────────────── */

/**
 * Throw-away inbox services. Accounts on these never count for rewards and
 * can't sign up (see vitalstack_email_is_disposable()).
 */
function vitalstack_disposable_domains() {
	return apply_filters(
		'vitalstack_disposable_domains',
		array( 'mailinator.com', 'guerrillamail.com', 'guerrillamail.net', 'guerrillamailblock.com', 'sharklasers.com', 'grr.la', '10minutemail.com', '10minutemail.net', 'tempmail.com', 'temp-mail.org', 'temp-mail.io', 'tempmail.dev', 'tempmailo.com', 'tempail.com', 'tempr.email', 'yopmail.com', 'yopmail.net', 'trashmail.com', 'trashmail.de', 'getnada.com', 'nada.email', 'dispostable.com', 'maildrop.cc', 'mailnesia.com', 'moakt.com', 'emailondeck.com', 'fakeinbox.com', 'throwawaymail.com', 'mintemail.com', 'mohmal.com', 'burnermail.io', 'mailcatch.com', 'spambox.us', 'spamgourmet.com', 'mytemp.email', 'emailfake.com', 'fakemail.net', 'luxusmail.org', 'inboxkitten.com', 'mail.tm', 'internxt.com', 'dropmail.me', 'minuteinbox.com', 'tmpmail.org', 'tmpmail.net', 'linshiyouxiang.net', 'cock.li', 'discard.email', 'mailpoof.com', '1secmail.com', '1secmail.org', '1secmail.net', 'harakirimail.com', 'jetable.org', 'mvrht.com', 'zetmail.com', 'tmail.ws', 'emltmp.com', 'tempinbox.com', 'spam4.me', 'mailforspam.com', 'temporary-mail.net', 'byom.de', 'trbvm.com' )
	);
}

function vitalstack_email_is_disposable( $email ) {
	$domain = strtolower( (string) substr( strrchr( (string) $email, '@' ), 1 ) );
	return '' !== $domain && in_array( $domain, vitalstack_disposable_domains(), true );
}

/**
 * One key per real inbox: Gmail ignores dots and "+anything", and most
 * services ignore "+anything", so a.b+1@gmail.com and ab@gmail.com are one person.
 */
function vitalstack_ref_email_key( $email ) {
	$email = strtolower( trim( (string) $email ) );
	if ( false === strpos( $email, '@' ) ) {
		return $email;
	}
	list( $local, $domain ) = explode( '@', $email, 2 );
	$local = preg_replace( '/\+.*$/', '', $local );
	if ( in_array( $domain, array( 'gmail.com', 'googlemail.com' ), true ) ) {
		$local  = str_replace( '.', '', $local );
		$domain = 'gmail.com';
	}
	return $local . '@' . $domain;
}

/** Friends who joined with this reader's link. */
function vitalstack_ref_friends( $user_id ) {
	return get_users(
		array(
			'meta_key'   => 'vs_ref_by', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value' => (int) $user_id, // phpcs:ignore WordPress.DB.SlowDBQuery
			'number'     => 1000,
			'fields'     => array( 'ID', 'user_email' ),
		)
	);
}

/** Friends who count as really learning: real, unique inboxes with a completed lesson. */
function vitalstack_ref_active_friends( $user_id ) {
	$me   = get_userdata( $user_id );
	$seen = $me ? array( vitalstack_ref_email_key( $me->user_email ) => true ) : array();
	$out  = array();
	foreach ( vitalstack_ref_friends( $user_id ) as $f ) {
		$key = vitalstack_ref_email_key( $f->user_email );
		if ( isset( $seen[ $key ] ) || vitalstack_email_is_disposable( $f->user_email ) ) {
			continue;
		}
		$seen[ $key ] = true;
		$done         = 0;
		$progress     = get_user_meta( $f->ID, 'vs_progress', true );
		foreach ( is_array( $progress ) ? $progress : array() as $list ) {
			$done += is_array( $list ) ? count( array_filter( $list, 'is_numeric' ) ) : 0; // lesson IDs only
		}
		if ( $done >= 1 ) {
			$out[] = (int) $f->ID;
		}
	}
	return $out;
}

/**
 * Paid orders that used this reader's code, split into "settled" (past the
 * refund window, still paid) and "pending". Practice (demo) orders never count.
 */
function vitalstack_ref_orders( $user_id ) {
	$code = (string) get_user_meta( $user_id, 'vs_ref_code', true );
	$out  = array(
		'settled' => array(),
		'pending' => array(),
	);
	if ( ! vitalstack_ref_is_code( $code ) ) {
		return $out;
	}
	$wait = max( 0, (int) vitalstack_pay_settings()['refund_days'] ) * DAY_IN_SECONDS;
	$ids  = get_posts(
		array(
			'post_type'   => 'vs_order',
			'post_status' => 'any',
			'numberposts' => 500,
			'fields'      => 'ids',
			'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'key'   => '_vs_coupon',
					'value' => $code,
				),
				array(
					'key'   => '_vs_status',
					'value' => 'paid',
				),
			),
		)
	);
	$buyers = array();
	foreach ( $ids as $oid ) {
		$buyer = (int) get_post_field( 'post_author', $oid );
		if ( $buyer === (int) $user_id || isset( $buyers[ $buyer ] ) || 'demo' === get_post_meta( $oid, '_vs_mode', true ) ) {
			continue;
		}
		$buyers[ $buyer ] = true;
		$paid_at          = (int) get_post_meta( $oid, '_vs_paid_at', true );
		$out[ $paid_at && $paid_at + $wait <= time() ? 'settled' : 'pending' ][] = (int) $oid;
	}
	return $out;
}

/* ── Rewards ──────────────────────────────────────────────────────────── */

/** Add Pro days to every premium track (time already owned is kept). */
function vitalstack_ref_grant_days( $user_id, $days ) {
	$access = get_user_meta( $user_id, 'vs_premium', true );
	$access = is_array( $access ) ? $access : array();
	foreach ( array_keys( vitalstack_premium_tracks() ) as $track ) {
		$now = isset( $access[ $track ] ) ? (int) $access[ $track ] : 0;
		if ( 1 === $now ) {
			continue; // already has it with no end date
		}
		$access[ $track ] = max( time(), $now ) + (int) $days * DAY_IN_SECONDS;
	}
	update_user_meta( $user_id, 'vs_premium', $access );
}

/**
 * Give any rewards this reader has earned and not yet received. Safe to call
 * often: it only adds what the records show is still owed.
 *
 * @return array The reward log.
 */
function vitalstack_ref_settle( $user_id ) {
	$user_id = (int) $user_id;
	$log     = get_user_meta( $user_id, 'vs_ref_log', true );
	$log     = is_array( $log ) ? $log : array();
	$s       = vitalstack_ref_settings();
	if ( ! $user_id || ! $s['on'] || ! get_user_meta( $user_id, 'vs_ref_code', true ) ) {
		return $log;
	}
	// One settle at a time per reader, so two requests can't pay twice.
	$lock = 'vs_ref_lock_' . $user_id;
	if ( ! add_option( $lock, time(), '', 'no' ) ) {
		if ( (int) get_option( $lock ) > time() - 60 ) {
			return $log;
		}
		update_option( $lock, time(), false );
	}
	$year   = time() - YEAR_IN_SECONDS;
	$recent = function ( $type ) use ( &$log, $year ) {
		return count(
			array_filter(
				$log,
				function ( $r ) use ( $type, $year ) {
					return $r['type'] === $type && $r['at'] > $year;
				}
			)
		);
	};
	$changed = false;

	$done_orders = wp_list_pluck( wp_list_filter( $log, array( 'type' => 'paid' ) ), 'ref' );
	foreach ( vitalstack_ref_orders( $user_id )['settled'] as $oid ) {
		if ( in_array( $oid, $done_orders, true ) || $recent( 'paid' ) >= (int) $s['cap_paid'] ) {
			continue;
		}
		vitalstack_ref_grant_days( $user_id, (int) $s['paid_days'] );
		$log[]   = array( 'type' => 'paid', 'ref' => $oid, 'days' => (int) $s['paid_days'], 'at' => time() );
		$changed = true;
	}

	$need   = max( 1, (int) $s['active_need'] );
	$groups = intdiv( count( vitalstack_ref_active_friends( $user_id ) ), $need );
	$given  = count( wp_list_filter( $log, array( 'type' => 'active' ) ) );
	for ( $g = $given; $g < $groups && $recent( 'active' ) < (int) $s['cap_active']; $g++ ) {
		vitalstack_ref_grant_days( $user_id, (int) $s['active_days'] );
		$log[]   = array( 'type' => 'active', 'ref' => $g + 1, 'days' => (int) $s['active_days'], 'at' => time() );
		$changed = true;
	}

	if ( $changed ) {
		update_user_meta( $user_id, 'vs_ref_log', $log );
	}
	delete_option( $lock );
	return $log;
}
add_action(
	'wp_login',
	function ( $login, $user ) {
		vitalstack_ref_settle( $user->ID );
	},
	20,
	2
);

/** Numbers for the account card. */
function vitalstack_ref_stats( $user_id ) {
	$log    = vitalstack_ref_settle( $user_id );
	$orders = vitalstack_ref_orders( $user_id );
	return array(
		'joined'  => count( vitalstack_ref_friends( $user_id ) ),
		'active'  => count( vitalstack_ref_active_friends( $user_id ) ),
		'paid'    => count( $orders['settled'] ) + count( $orders['pending'] ),
		'pending' => count( $orders['pending'] ),
		'days'    => array_sum( wp_list_pluck( $log, 'days' ) ),
	);
}

/* ── Account card ─────────────────────────────────────────────────────── */

/** Share text with the link; also used by the share buttons. */
function vitalstack_ref_share_text() {
	/* translators: %s: site name */
	return sprintf( __( 'I’m learning coding and AI for free on %s: animated lessons, games and mock tests. Join with my link:', 'vitalstack' ), get_bloginfo( 'name' ) );
}

function vitalstack_ref_panel( $user_id ) {
	$s = vitalstack_ref_settings();
	if ( ! $s['on'] || ! $user_id ) {
		return;
	}
	$link  = vitalstack_ref_link( $user_id );
	$code  = vitalstack_ref_code( $user_id );
	$st    = vitalstack_ref_stats( $user_id );
	$need  = max( 1, (int) $s['active_need'] );
	$step  = $st['active'] % $need;
	$text  = vitalstack_ref_share_text();
	$share = array(
		'whatsapp' => array( 'WhatsApp', 'https://wa.me/?text=' . rawurlencode( $text . ' ' . $link ) ),
		'telegram' => array( 'Telegram', 'https://t.me/share/url?url=' . rawurlencode( $link ) . '&text=' . rawurlencode( $text ) ),
		'linkedin' => array( 'LinkedIn', 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $link ) ),
		'x'        => array( 'X', 'https://twitter.com/intent/tweet?text=' . rawurlencode( $text ) . '&url=' . rawurlencode( $link ) ),
		'email'    => array( __( 'Email', 'vitalstack' ), 'mailto:?subject=' . rawurlencode( get_bloginfo( 'name' ) ) . '&body=' . rawurlencode( $text . ' ' . $link ) ),
	);
	?>
	<section class="panel ref-panel" data-ref>
		<h2>🎁 <?php esc_html_e( 'Invite friends, get Pro free', 'vitalstack' ); ?></h2>
		<ul class="ref-rules">
			<li><?php echo esc_html( sprintf( /* translators: %d: percent */ __( 'Friends get %d%% off their first premium plan.', 'vitalstack' ), (int) $s['percent'] ) ); ?></li>
			<li><?php echo esc_html( sprintf( /* translators: %d: days */ __( 'You get %d days of Pro for every friend who buys.', 'vitalstack' ), (int) $s['paid_days'] ) ); ?></li>
			<li><?php echo esc_html( sprintf( /* translators: 1: friends, 2: days */ __( 'And %2$d days of Pro for every %1$d friends who join and finish a lesson.', 'vitalstack' ), $need, (int) $s['active_days'] ) ); ?></li>
		</ul>
		<label class="ref-label" for="vs-ref-link"><?php esc_html_e( 'Your invite link', 'vitalstack' ); ?></label>
		<div class="ref-copy">
			<input id="vs-ref-link" type="text" readonly value="<?php echo esc_attr( $link ); ?>" data-ref-link>
			<button type="button" class="btn btn-primary btn-sm" data-ref-copy><?php esc_html_e( 'Copy', 'vitalstack' ); ?></button>
		</div>
		<p class="muted small"><?php echo esc_html( sprintf( /* translators: %s: invite code */ __( 'Or share your code: %s', 'vitalstack' ), $code ) ); ?></p>
		<div class="ref-share">
			<button type="button" class="btn btn-soft btn-sm" data-ref-native data-text="<?php echo esc_attr( $text ); ?>" hidden>📤 <?php esc_html_e( 'Share', 'vitalstack' ); ?></button>
			<?php foreach ( $share as $key => $s2 ) : ?>
				<a class="ref-btn is-<?php echo esc_attr( $key ); ?>" href="<?php echo esc_url( $s2[1] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $s2[0] ); ?></a>
			<?php endforeach; ?>
		</div>
		<div class="ref-stats">
			<div><b><?php echo (int) $st['joined']; ?></b><span><?php esc_html_e( 'joined', 'vitalstack' ); ?></span></div>
			<div><b><?php echo (int) $st['active']; ?></b><span><?php esc_html_e( 'learning', 'vitalstack' ); ?></span></div>
			<div><b><?php echo (int) $st['paid']; ?></b><span><?php esc_html_e( 'bought', 'vitalstack' ); ?></span></div>
			<div><b><?php echo (int) $st['days']; ?></b><span><?php esc_html_e( 'Pro days earned', 'vitalstack' ); ?></span></div>
		</div>
		<p class="ref-next">
			<span class="ref-bar" aria-hidden="true"><i style="width:<?php echo (int) round( 100 * $step / $need ); ?>%"></i></span>
			<?php echo esc_html( sprintf( /* translators: 1: friends still needed, 2: days */ _n( '%1$d more learning friend for your next %2$d free Pro days.', '%1$d more learning friends for your next %2$d free Pro days.', $need - $step, 'vitalstack' ), $need - $step, (int) $s['active_days'] ) ); ?>
		</p>
		<?php if ( $st['pending'] ) : ?>
			<p class="muted small"><?php echo esc_html( sprintf( /* translators: %d: purchases */ _n( '%d purchase is inside its refund window; its reward arrives after that.', '%d purchases are inside their refund window; their rewards arrive after that.', $st['pending'], 'vitalstack' ), $st['pending'] ) ); ?></p>
		<?php endif; ?>
	</section>
	<?php
}

/* ── /pricing/ ────────────────────────────────────────────────────────── */

/** Note on /pricing/: the friend's code for invited readers, the offer for everyone else. */
function vitalstack_ref_pricing_note() {
	$s = vitalstack_ref_settings();
	if ( ! $s['on'] ) {
		return;
	}
	$uid  = get_current_user_id();
	$code = $uid ? vitalstack_ref_code_for_buyer( $uid ) : '';
	if ( $code ) {
		echo '<p class="pricing-soon ref-applied" data-ref-apply="' . esc_attr( $code ) . '">🎁 ' . esc_html( sprintf( /* translators: %d: percent */ __( 'Your friend’s invite gives you %d%% off your first plan. It’s already applied below.', 'vitalstack' ), (int) $s['percent'] ) ) . '</p>';
		return;
	}
	$url = $uid ? vitalstack_account_url() . '#invite' : vitalstack_account_url( array( 'tab' => 'register' ) );
	echo '<p class="pricing-soon ref-offer">🎁 ' . esc_html( sprintf( /* translators: 1: friends, 2: days, 3: days */ __( 'Invite friends: %1$d friends who start learning = %2$d days of Pro free, and %3$d days for every friend who buys.', 'vitalstack' ), (int) $s['active_need'], (int) $s['active_days'], (int) $s['paid_days'] ) ) . ' <a href="' . esc_url( $url ) . '">' . esc_html__( 'Get your invite link', 'vitalstack' ) . ' ❯</a></p>';
}

/* ── Assets and the welcome note ──────────────────────────────────────── */

function vitalstack_ref_assets() {
	wp_enqueue_script( 'vitalstack-share', vitalstack_asset_url( 'assets/js/share.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_localize_script(
		'vitalstack-share',
		'vitalstackShare',
		array(
			'copied'   => __( 'Copied!', 'vitalstack' ),
			'source'   => __( 'Source', 'vitalstack' ),
			'site'     => get_bloginfo( 'name' ),
			'loggedIn' => is_user_logged_in(),
			'welcome'  => vitalstack_ref_settings()['on'] ? sprintf( /* translators: %d: percent */ __( '🎁 A friend invited you! Create a free account, and get %d%% off your first premium plan.', 'vitalstack' ), (int) vitalstack_ref_settings()['percent'] ) : '',
			'join'     => __( 'Join free', 'vitalstack' ),
			'joinUrl'  => vitalstack_account_url( array( 'tab' => 'register' ) ),
			'close'    => __( 'Close', 'vitalstack' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'vitalstack_ref_assets', 33 );

/* ── Admin: Users → Invites ───────────────────────────────────────────── */

add_action(
	'admin_menu',
	function () {
		add_users_page( __( 'VitalStack invites', 'vitalstack' ), __( 'Invites', 'vitalstack' ), 'manage_options', 'vs-invites', 'vitalstack_ref_admin_page' );
	}
);

function vitalstack_ref_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s = vitalstack_ref_settings();
	// The readers whose friends joined, most first.
	global $wpdb;
	$rows = $wpdb->get_results( "SELECT meta_value AS uid, COUNT(*) AS n FROM {$wpdb->usermeta} WHERE meta_key = 'vs_ref_by' GROUP BY meta_value ORDER BY n DESC LIMIT 50" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$fields = array(
		'percent'     => __( 'Friend’s discount on the first plan (%)', 'vitalstack' ),
		'paid_days'   => __( 'Pro days for the inviter per friend who buys', 'vitalstack' ),
		'active_need' => __( 'Learning friends needed for a reward', 'vitalstack' ),
		'active_days' => __( 'Pro days for that reward', 'vitalstack' ),
		'cap_paid'    => __( 'Max "friend bought" rewards per reader per 12 months', 'vitalstack' ),
		'cap_active'  => __( 'Max "friends learning" rewards per reader per 12 months', 'vitalstack' ),
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'VitalStack invites', 'vitalstack' ); ?></h1>
		<p><?php esc_html_e( 'Readers share their invite link from their account page. Friends get a discount on their first plan; the inviter earns Pro days. Purchases count after the refund window; "learning" friends are real, unique inboxes (no throw-away email) with at least one lesson completed.', 'vitalstack' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="vs_ref_save">
			<?php wp_nonce_field( 'vs_ref_save' ); ?>
			<table class="form-table" role="presentation">
				<tr><th><?php esc_html_e( 'Invite program', 'vitalstack' ); ?></th><td><label><input type="checkbox" name="on" value="1" <?php checked( $s['on'] ); ?>> <?php esc_html_e( 'On', 'vitalstack' ); ?></label></td></tr>
				<?php foreach ( $fields as $k => $label ) : ?>
					<tr><th><label for="vs-ref-<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $label ); ?></label></th><td><input id="vs-ref-<?php echo esc_attr( $k ); ?>" type="number" min="0" max="<?php echo 'percent' === $k ? 50 : 365; ?>" name="<?php echo esc_attr( $k ); ?>" value="<?php echo (int) $s[ $k ]; ?>" class="small-text"></td></tr>
				<?php endforeach; ?>
			</table>
			<?php submit_button(); ?>
		</form>
		<h2><?php esc_html_e( 'Top inviters', 'vitalstack' ); ?></h2>
		<table class="widefat striped">
			<thead><tr><th><?php esc_html_e( 'Reader', 'vitalstack' ); ?></th><th><?php esc_html_e( 'Code', 'vitalstack' ); ?></th><th><?php esc_html_e( 'Joined', 'vitalstack' ); ?></th><th><?php esc_html_e( 'Learning', 'vitalstack' ); ?></th><th><?php esc_html_e( 'Bought', 'vitalstack' ); ?></th><th><?php esc_html_e( 'Pro days given', 'vitalstack' ); ?></th></tr></thead>
			<tbody>
				<?php if ( ! $rows ) : ?>
					<tr><td colspan="6"><?php esc_html_e( 'No invites yet.', 'vitalstack' ); ?></td></tr>
				<?php endif; ?>
				<?php foreach ( $rows as $r ) : ?>
					<?php
					$u = get_userdata( (int) $r->uid );
					if ( ! $u ) {
						continue;
					}
					$o   = vitalstack_ref_orders( $u->ID );
					$log = (array) get_user_meta( $u->ID, 'vs_ref_log', true );
					?>
					<tr>
						<td><a href="<?php echo esc_url( get_edit_user_link( $u->ID ) ); ?>"><?php echo esc_html( $u->display_name ); ?></a></td>
						<td><code><?php echo esc_html( (string) get_user_meta( $u->ID, 'vs_ref_code', true ) ); ?></code></td>
						<td><?php echo (int) $r->n; ?></td>
						<td><?php echo count( vitalstack_ref_active_friends( $u->ID ) ); ?></td>
						<td><?php echo count( $o['settled'] ) + count( $o['pending'] ); ?></td>
						<td><?php echo (int) array_sum( wp_list_pluck( array_filter( $log, 'is_array' ), 'days' ) ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}

function vitalstack_ref_save() {
	if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'vs_ref_save' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'vitalstack' ) );
	}
	$s       = vitalstack_ref_settings();
	$s['on'] = ! empty( $_POST['on'] );
	foreach ( array( 'percent', 'paid_days', 'active_need', 'active_days', 'cap_paid', 'cap_active' ) as $k ) {
		if ( isset( $_POST[ $k ] ) ) {
			$s[ $k ] = min( 'percent' === $k ? 50 : 365, absint( $_POST[ $k ] ) );
		}
	}
	$s['active_need'] = max( 1, $s['active_need'] );
	update_option( 'vs_ref', $s, false );
	wp_safe_redirect( admin_url( 'users.php?page=vs-invites&updated=1' ) );
	exit;
}
add_action( 'admin_post_vs_ref_save', 'vitalstack_ref_save' );
