<?php
/**
 * Launch-offer banners on the site (4.13.3), in the style of the posters:
 *   - a band on the home page, above the premium section;
 *   - a compact card at the end of free lessons and articles.
 *
 * Shown to visitors and free readers only (never to premium readers or staff).
 * Prices come from the live plan list and carry both currencies; the page
 * shows ₹ in India and $ elsewhere (share.js), like the pricing page.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_offer_visible() {
	if ( ! function_exists( 'vitalstack_pay_plans' ) || ! function_exists( 'vitalstack_pricing_url' ) ) {
		return false;
	}
	$uid = get_current_user_id();
	if ( $uid && ( user_can( $uid, 'edit_posts' ) || ( function_exists( 'vitalstack_reader_has_any_premium' ) && vitalstack_reader_has_any_premium( $uid ) ) ) ) {
		return false;
	}
	return (bool) apply_filters( 'vitalstack_offer_visible', true );
}

/** Price parts for the Pro Yearly plan in both currencies. */
function vitalstack_offer_prices() {
	$plan = vitalstack_pay_plans()['all-year'] ?? null;
	if ( ! $plan ) {
		return null;
	}
	$list = function_exists( 'vitalstack_plan_list_prices' ) ? ( vitalstack_plan_list_prices()['all-year'] ?? null ) : null;
	$out  = array();
	foreach ( array( 'inr', 'usd' ) as $i => $cur ) {
		$now  = (int) $plan[ $cur ];
		$was  = $list ? (int) $list[ $i ] : 0;
		$save = $was > $now ? (int) round( ( 1 - $now / $was ) * 100 ) : 0;
		$out[ $cur ] = array(
			'now'  => vitalstack_pay_price_label( $now, $cur ),
			'was'  => $save ? vitalstack_pay_price_label( $was, $cur ) : '',
			'save' => $save >= 5 ? $save . '%' : '',
			'per'  => function_exists( 'vitalstack_plan_money' ) ? vitalstack_plan_money( $now / 12, $cur ) : '',
		);
	}
	return $out;
}

/** A span that follows the reader's currency. */
function vitalstack_offer_x( $tag, $class, $inr, $usd ) {
	return '<' . $tag . ' class="' . esc_attr( $class ) . '" data-x-inr="' . esc_attr( $inr ) . '" data-x-usd="' . esc_attr( $usd ) . '"' . ( '' === $inr ? ' hidden' : '' ) . '>' . esc_html( $inr ) . '</' . $tag . '>';
}

function vitalstack_offer_cta_label() {
	return 'waitlist' === ( function_exists( 'vitalstack_pay_action' ) ? vitalstack_pay_action() : '' ) ? __( 'Join the waitlist', 'vitalstack' ) : __( 'Get Pro Yearly', 'vitalstack' );
}

/** Home page band. */
function vitalstack_offer_band() {
	if ( ! vitalstack_offer_visible() || ! ( $p = vitalstack_offer_prices() ) ) {
		return;
	}
	$rule = function_exists( 'vitalstack_pay_currency_rule' ) ? vitalstack_pay_currency_rule() : 'auto';
	?>
	<section class="offer-band" data-offer data-cur-rule="<?php echo esc_attr( $rule ); ?>" data-cur="<?php echo esc_attr( function_exists( 'vitalstack_pay_page_currency' ) ? vitalstack_pay_page_currency() : '' ); ?>" aria-label="<?php esc_attr_e( 'Launch offer', 'vitalstack' ); ?>">
		<div class="wrap offer-band-inner">
			<div class="offer-copy">
				<p class="offer-chip">⭐ <?php esc_html_e( 'Pro Yearly · Launch price', 'vitalstack' ); ?></p>
				<h2><?php esc_html_e( 'Learn', 'vitalstack' ); ?> <em><?php esc_html_e( 'Data & AI', 'vitalstack' ); ?></em> <?php esc_html_e( 'the visual way', 'vitalstack' ); ?></h2>
				<p class="offer-sub"><?php esc_html_e( 'All 3 career tracks: Data Scientist, Gen AI Developer and Agentic AI Engineer. Animated lessons, real projects, certificates.', 'vitalstack' ); ?></p>
				<ul class="offer-tracks">
					<li>📊 <?php esc_html_e( 'Data Scientist', 'vitalstack' ); ?></li>
					<li>🤖 <?php esc_html_e( 'Gen AI Developer', 'vitalstack' ); ?></li>
					<li>🦾 <?php esc_html_e( 'Agentic AI Engineer', 'vitalstack' ); ?></li>
				</ul>
			</div>
			<div class="offer-deal">
				<?php if ( $p['inr']['save'] || $p['usd']['save'] ) : ?>
					<p class="offer-save"><small><?php esc_html_e( 'Save', 'vitalstack' ); ?></small><?php echo vitalstack_offer_x( 'b', 'offer-save-n', $p['inr']['save'], $p['usd']['save'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
				<?php endif; ?>
				<p class="offer-price"><?php echo vitalstack_offer_x( 'span', 'offer-now', $p['inr']['now'], $p['usd']['now'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="offer-per">/<?php esc_html_e( 'year', 'vitalstack' ); ?></span></p>
				<p class="offer-was"><?php echo vitalstack_offer_x( 's', '', $p['inr']['was'], $p['usd']['was'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo vitalstack_offer_x( 'span', 'offer-month', $p['inr']['per'] ? sprintf( /* translators: %s: price per month */ __( 'just %s/month', 'vitalstack' ), $p['inr']['per'] ) : '', $p['usd']['per'] ? sprintf( /* translators: %s: price per month */ __( 'just %s/month', 'vitalstack' ), $p['usd']['per'] ) : '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<a class="btn offer-cta" href="<?php echo esc_url( vitalstack_pricing_url() ); ?>"><?php echo esc_html( vitalstack_offer_cta_label() ); ?> →</a>
				<p class="offer-fine"><?php esc_html_e( 'One-time payment · no auto-renewal · free modules to try first', 'vitalstack' ); ?></p>
			</div>
			<div class="offer-bot" aria-hidden="true"><?php echo function_exists( 'vitalstack_mascot' ) ? vitalstack_mascot( 'cheer', 120, '', 'dev' ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></div>
		</div>
	</section>
	<?php
}

/** Compact card after free lessons and articles. */
function vitalstack_offer_card_html() {
	$p = vitalstack_offer_prices();
	if ( ! $p ) {
		return '';
	}
	$rule = function_exists( 'vitalstack_pay_currency_rule' ) ? vitalstack_pay_currency_rule() : 'auto';
	return '<aside class="offer-card" data-offer data-cur-rule="' . esc_attr( $rule ) . '" data-cur="' . esc_attr( function_exists( 'vitalstack_pay_page_currency' ) ? vitalstack_pay_page_currency() : '' ) . '">'
		. '<div class="offer-card-text"><p class="offer-chip">⭐ ' . esc_html__( 'Liked this lesson?', 'vitalstack' ) . '</p>'
		. '<p class="offer-card-title">' . esc_html__( 'Go deeper with all 3 premium career tracks', 'vitalstack' ) . '</p>'
		. '<p class="offer-card-sub">' . esc_html__( 'Data Scientist · Gen AI Developer · Agentic AI Engineer: projects and certificates.', 'vitalstack' ) . '</p></div>'
		. '<div class="offer-card-deal">'
		. ( $p['inr']['save'] || $p['usd']['save'] ? '<span class="offer-card-save">' . esc_html__( 'Save', 'vitalstack' ) . ' ' . vitalstack_offer_x( 'b', '', $p['inr']['save'], $p['usd']['save'] ) . '</span>' : '' )
		. '<p class="offer-price">' . vitalstack_offer_x( 'span', 'offer-now', $p['inr']['now'], $p['usd']['now'] ) . '<span class="offer-per">/' . esc_html__( 'year', 'vitalstack' ) . '</span></p>'
		. '<p class="offer-was">' . vitalstack_offer_x( 's', '', $p['inr']['was'], $p['usd']['was'] ) . '</p>'
		. '<a class="btn offer-cta" href="' . esc_url( vitalstack_pricing_url() ) . '">' . esc_html( vitalstack_offer_cta_label() ) . ' →</a></div></aside>';
}

add_filter(
	'the_content',
	function ( $content ) {
		if ( ! is_singular( array( 'tutorials', 'post' ) ) || ! in_the_loop() || ! is_main_query() || ! vitalstack_offer_visible() ) {
			return $content;
		}
		if ( function_exists( 'vitalstack_lesson_track' ) && vitalstack_lesson_track( get_the_ID() ) ) {
			return $content; // premium lessons already show their own unlock box
		}
		return $content . vitalstack_offer_card_html();
	},
	40
);
