<?php
/**
 * Selling: landing offers, in the style of a big sale.
 *
 *   - A deal card in the home page hero, next to the learning card.
 *   - A deal panel at the top of /pricing/, so prices and savings are seen
 *     before anything else.
 *   - A small card that slides in once every few days on other pages.
 *
 * Honest by design (and as Indian consumer law on dark patterns requires):
 *   - A struck-through "regular price" and "Save X%" appear only for plans
 *     where the admin entered the real price that applies after the offer.
 *   - The countdown appears only when the admin set a real end date. When it
 *     ends, every offer element disappears (also from cached pages) and the
 *     regular prices stop being shown.
 *   - Premium readers and staff never see it (admins can preview).
 *
 * @package VitalStackGrowth
 */

defined( 'ABSPATH' ) || exit;

/** Regular prices from the desk feed the theme's "was" price and "Save X%" while the offer runs. */
add_filter(
	'vitalstack_plan_list_prices',
	function ( $list ) {
		if ( ! vsg_offer_running() ) {
			return $list;
		}
		foreach ( vsg_settings()['regular'] as $plan => $p ) {
			$inr = (int) ( $p[0] ?? 0 );
			$usd = (float) ( $p[1] ?? 0 );
			if ( $inr > 0 || $usd > 0 ) {
				$list[ $plan ] = array( $inr, $usd );
			}
		}
		return $list;
	},
	20
);

/** Admins can see the offer as a visitor would with ?vsg_preview=1. */
function vsg_preview() {
	return isset( $_GET['vsg_preview'] ) && current_user_can( 'manage_options' ); // phpcs:ignore WordPress.Security.NonceVerification -- read-only preview flag
}

function vsg_offer_visible() {
	if ( ! vsg_offer_running() ) {
		return false;
	}
	if ( vsg_preview() ) {
		return true;
	}
	return function_exists( 'vitalstack_offer_visible' ) ? (bool) vitalstack_offer_visible() : ! current_user_can( 'edit_posts' );
}

/** Pro Yearly prices (both currencies) with the regular price and saving when set. */
function vsg_prices() {
	if ( function_exists( 'vitalstack_offer_prices' ) ) {
		return vitalstack_offer_prices();
	}
	return null;
}

/** The biggest saving across plans with a regular price, as a whole percent ('' = either currency). */
function vsg_max_saving( $only = '' ) {
	$plans = vitalstack_pay_plans();
	$max   = 0;
	foreach ( vsg_settings()['regular'] as $id => $p ) {
		if ( empty( $plans[ $id ] ) ) {
			continue;
		}
		foreach ( array( 0 => 'inr', 1 => 'usd' ) as $i => $cur ) {
			if ( $only && $only !== $cur ) {
				continue;
			}
			$was = (float) ( $p[ $i ] ?? 0 );
			$now = (float) $plans[ $id ][ $cur ];
			if ( $was > $now && $now > 0 ) {
				$max = max( $max, (int) round( ( 1 - $now / $was ) * 100 ) );
			}
		}
	}
	return $max;
}

/** A span whose text follows the reader's currency (growth.js). */
function vsg_x( $tag, $class, $inr, $usd ) {
	return '<' . $tag . ' class="' . esc_attr( $class ) . '" data-x-inr="' . esc_attr( $inr ) . '" data-x-usd="' . esc_attr( $usd ) . '"' . ( '' === $inr && '' === $usd ? ' hidden' : '' ) . '>' . esc_html( $inr ) . '</' . $tag . '>';
}

function vsg_cta_url( $medium ) {
	return add_query_arg(
		array(
			'utm_source'   => 'site',
			'utm_medium'   => $medium,
			'utm_campaign' => sanitize_title( vsg_settings()['name'] ),
		),
		vitalstack_pricing_url()
	) . '#plans';
}

function vsg_cta_label() {
	if ( function_exists( 'vitalstack_offer_cta_label' ) ) {
		return vitalstack_offer_cta_label();
	}
	return __( 'See the offer', 'vitalstack-growth' );
}

/** The currency rule the page uses: inr, usd, tz (browser time zone) or '' (reader's choice). */
function vsg_cur_attr() {
	return function_exists( 'vitalstack_pay_page_currency' ) ? (string) vitalstack_pay_page_currency() : 'tz';
}

/** Countdown markup, only when there is a real end date. */
function vsg_countdown() {
	$end = vsg_offer_end();
	if ( ! $end ) {
		return '';
	}
	return '<p class="vsg-count" data-vsg-count aria-live="off"><span class="vsg-count-label">' . esc_html__( 'Offer ends in', 'vitalstack-growth' ) . '</span> <b data-vsg-left>' . esc_html( vsg_left_label( $end - time() ) ) . '</b></p>';
}

/** "2d 04h 12m" for the first paint; growth.js keeps it ticking. */
function vsg_left_label( $secs ) {
	$secs = max( 0, (int) $secs );
	$d    = intdiv( $secs, DAY_IN_SECONDS );
	$h    = intdiv( $secs % DAY_IN_SECONDS, HOUR_IN_SECONDS );
	$m    = intdiv( $secs % HOUR_IN_SECONDS, MINUTE_IN_SECONDS );
	return ( $d ? $d . 'd ' : '' ) . sprintf( '%02dh %02dm', $h, $m );
}

/** Facts that make the value clear, all counted from the site itself. */
function vsg_benefits() {
	$tracks  = function_exists( 'vitalstack_premium_tracks' ) ? count( vitalstack_premium_tracks() ) : 0;
	$lessons = (int) ( wp_count_posts( 'tutorials' )->publish ?? 0 );
	$out     = array();
	if ( $tracks ) {
		/* translators: %d: number of career tracks */
		$out[] = sprintf( _n( '%d career track', 'All %d career tracks', $tracks, 'vitalstack-growth' ), $tracks );
	}
	if ( $lessons > 50 ) {
		/* translators: %d: lessons, rounded down to the nearest 50 */
		$out[] = sprintf( __( '%d+ lessons with live animations', 'vitalstack-growth' ), (int) ( floor( $lessons / 50 ) * 50 ) );
	}
	$out[] = __( 'Real projects and a shareable certificate', 'vitalstack-growth' );
	$out[] = __( 'One payment, no auto-renewal', 'vitalstack-growth' );
	return $out;
}

/** The offer's wrapper attributes: currency rule and end time for growth.js. */
function vsg_attrs( $where ) {
	$end = vsg_offer_end();
	return ' data-vsg-offer="' . esc_attr( $where ) . '" data-vsg-cur="' . esc_attr( vsg_cur_attr() ) . '"' . ( $end ? ' data-vsg-ends="' . esc_attr( $end * 1000 ) . '"' : '' );
}

/** Price block: now, regular (struck) and saving, in both currencies. */
function vsg_price_html( $p ) {
	$h  = '<p class="vsg-price">';
	$h .= vsg_x( 'b', 'vsg-now', $p['inr']['now'], $p['usd']['now'] );
	$h .= ' ' . vsg_x( 's', 'vsg-was', $p['inr']['was'], $p['usd']['was'] );
	$h .= ' ' . vsg_x( 'span', 'vsg-save', $p['inr']['save'] ? sprintf( /* translators: %s: percent */ __( 'Save %s', 'vitalstack-growth' ), $p['inr']['save'] ) : '', $p['usd']['save'] ? sprintf( /* translators: %s: percent */ __( 'Save %s', 'vitalstack-growth' ), $p['usd']['save'] ) : '' );
	$h .= '</p>';
	if ( $p['inr']['per'] ) {
		/* translators: %s: price per month */
		$h .= '<p class="vsg-per">' . vsg_x( 'span', '', sprintf( __( 'Pro Yearly · just %s/month, paid once', 'vitalstack-growth' ), $p['inr']['per'] ), sprintf( __( 'Pro Yearly · just %s/month, paid once', 'vitalstack-growth' ), $p['usd']['per'] ) ) . '</p>';
	}
	return $h;
}

function vsg_trust_line() {
	return '<p class="vsg-trust">🔒 ' . esc_html__( 'Secure Razorpay checkout · UPI, cards, net banking · Instant access', 'vitalstack-growth' ) . '</p>';
}

/* ── Home page: deal card in the hero ──────────────────────────────────── */

function vsg_home_card() {
	if ( empty( vsg_settings()['home'] ) || ! vsg_offer_visible() || ! ( $p = vsg_prices() ) ) {
		return;
	}
	$s = vsg_settings();
	?>
	<aside class="vsg-deal"<?php echo vsg_attrs( 'home' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in vsg_attrs ?> aria-label="<?php echo esc_attr( $s['name'] ); ?>">
		<p class="vsg-badge">🔥 <?php echo esc_html( $s['name'] ); ?></p>
		<?php echo vsg_price_html( $p ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in vsg_price_html ?>
		<ul class="vsg-list">
			<?php foreach ( array_slice( vsg_benefits(), 0, 3 ) as $b ) : ?>
				<li><?php echo esc_html( $b ); ?></li>
			<?php endforeach; ?>
		</ul>
		<?php echo vsg_countdown(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in vsg_countdown ?>
		<a class="vsg-cta" href="<?php echo esc_url( vsg_cta_url( 'deal_home' ) ); ?>"><?php echo esc_html( vsg_cta_label() ); ?> ❯</a>
		<?php echo vsg_trust_line(); // phpcs:ignore WordPress.Security.EscapeOutput -- static text ?>
	</aside>
	<?php
}
add_action( 'vitalstack_home_hero_aside', 'vsg_home_card' );

/* ── Pricing page: deal panel at the top ───────────────────────────────── */

function vsg_pricing_panel() {
	if ( empty( vsg_settings()['pricing'] ) || ! vsg_offer_visible() || ! ( $p = vsg_prices() ) ) {
		return;
	}
	$s     = vsg_settings();
	$label = function ( $n ) {
		/* translators: %d: percent */
		return $n >= 5 ? ' · ' . sprintf( __( 'save up to %d%%', 'vitalstack-growth' ), $n ) : '';
	};
	?>
	<div class="vsg-panel"<?php echo vsg_attrs( 'pricing' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in vsg_attrs ?>>
		<div class="vsg-panel-main">
			<p class="vsg-badge">🔥 <?php echo esc_html( $s['name'] ); ?><?php echo vsg_x( 'span', 'vsg-upto', $label( vsg_max_saving( 'inr' ) ), $label( vsg_max_saving( 'usd' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in vsg_x ?></p>
			<?php echo vsg_price_html( $p ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in vsg_price_html ?>
			<?php echo vsg_countdown(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in vsg_countdown ?>
		</div>
		<div class="vsg-panel-side">
			<ul class="vsg-list">
				<?php foreach ( vsg_benefits() as $b ) : ?>
					<li><?php echo esc_html( $b ); ?></li>
				<?php endforeach; ?>
			</ul>
			<a class="vsg-cta" href="#plans" data-vsg-jump><?php esc_html_e( 'See all plans', 'vitalstack-growth' ); ?> ↓</a>
		</div>
	</div>
	<?php
}
add_action( 'vitalstack_pricing_hero_offer', 'vsg_pricing_panel' );

/* ── Slide-in card on other pages ──────────────────────────────────────── */

function vsg_slide_allowed() {
	$s = vsg_settings();
	if ( empty( $s['slide'] ) || is_admin() || is_feed() || is_404() ) {
		return false;
	}
	if ( get_query_var( 'vs_pricing' ) || get_query_var( 'vs_playground' ) || is_page( array( 'account', 'checkout' ) ) ) {
		return false; // pricing already sells; the builder and account pages need the whole screen
	}
	if ( is_singular( 'tutorials' ) && empty( $s['slide_lessons'] ) ) {
		return false;
	}
	return vsg_offer_visible();
}

function vsg_slide() {
	if ( ! vsg_slide_allowed() || ! ( $p = vsg_prices() ) ) {
		return;
	}
	$s = vsg_settings();
	?>
	<div class="vsg-slide" id="vsg-slide" role="dialog" aria-modal="false" aria-labelledby="vsg-slide-title" data-vsg-slide data-days="<?php echo esc_attr( max( 1, (int) $s['slide_days'] ) ); ?>"<?php echo vsg_attrs( 'slide' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in vsg_attrs ?> hidden>
		<button type="button" class="vsg-x" data-vsg-close aria-label="<?php esc_attr_e( 'Close offer', 'vitalstack-growth' ); ?>">✕</button>
		<p class="vsg-badge" id="vsg-slide-title">🔥 <?php echo esc_html( $s['name'] ); ?></p>
		<?php echo vsg_price_html( $p ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in vsg_price_html ?>
		<ul class="vsg-list">
			<?php foreach ( array_slice( vsg_benefits(), 0, 3 ) as $b ) : ?>
				<li><?php echo esc_html( $b ); ?></li>
			<?php endforeach; ?>
		</ul>
		<?php echo vsg_countdown(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in vsg_countdown ?>
		<a class="vsg-cta" href="<?php echo esc_url( vsg_cta_url( 'deal_slide' ) ); ?>"><?php echo esc_html( vsg_cta_label() ); ?> ❯</a>
		<button type="button" class="vsg-later" data-vsg-close><?php esc_html_e( 'Maybe later', 'vitalstack-growth' ); ?></button>
	</div>
	<?php
}
add_action( 'wp_footer', 'vsg_slide', 20 );

/* ── Assets: only where an offer can show ──────────────────────────────── */

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( ! vsg_offer_visible() ) {
			return;
		}
		wp_enqueue_style( 'vitalstack-growth', VSG_URL . 'assets/growth.css', array(), VSG_VERSION );
		wp_enqueue_script( 'vitalstack-growth', VSG_URL . 'assets/growth.js', array(), VSG_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	},
	40
);
