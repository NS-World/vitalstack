<?php
/**
 * Offer strip (4.14.0): a bright, scrolling strip above the header on every
 * page, so every visitor sees the launch offer.
 *
 *   - Prices come from the live plan list, in ₹ for India and $ elsewhere
 *     (same rule as the pricing page; share.js picks the currency).
 *   - Honest: the struck price is the real earlier price; no fake countdown.
 *   - Never shown to premium readers or staff, nor on the pricing page itself.
 *   - Visitors can close it for a day. Motion stops for "reduce motion".
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_offer_bar_visible() {
	if ( is_admin() || ! function_exists( 'vitalstack_offer_visible' ) || ! vitalstack_offer_visible() ) {
		return false;
	}
	if ( get_query_var( 'vs_pricing' ) || ( function_exists( 'vitalstack_is_account_page' ) && vitalstack_is_account_page() ) ) {
		return false;
	}
	return (bool) apply_filters( 'vitalstack_offer_bar_visible', true );
}

/** Hide before first paint if the visitor closed it today (no layout jump). */
function vitalstack_offer_bar_head() {
	if ( ! vitalstack_offer_bar_visible() ) {
		return;
	}
	echo "<script>try{if(+localStorage.getItem('vs-obar-off')>Date.now())document.documentElement.classList.add('obar-off')}catch(e){}</script>\n";
}
add_action( 'wp_head', 'vitalstack_offer_bar_head', 1 );

function vitalstack_offer_bar() {
	if ( ! vitalstack_offer_bar_visible() || ! ( $p = vitalstack_offer_prices() ) ) {
		return;
	}
	$rule  = function_exists( 'vitalstack_pay_currency_rule' ) ? vitalstack_pay_currency_rule() : 'auto';
	$cur   = function_exists( 'vitalstack_pay_page_currency' ) ? vitalstack_pay_page_currency() : '';
	$ref   = function_exists( 'vitalstack_ref_settings' ) ? vitalstack_ref_settings() : array();
	$save  = vitalstack_offer_x( 'b', '', $p['inr']['save'] ? sprintf( /* translators: %s: percent */ __( 'Save %s', 'vitalstack' ), $p['inr']['save'] ) : '', $p['usd']['save'] ? sprintf( /* translators: %s: percent */ __( 'Save %s', 'vitalstack' ), $p['usd']['save'] ) : '' );
	$items = array(
		'<span class="obar-hot">🔥 ' . esc_html__( 'Launch sale', 'vitalstack' ) . '</span>',
		esc_html__( 'Pro Yearly', 'vitalstack' ) . ' ' . vitalstack_offer_x( 'b', 'obar-price', $p['inr']['now'], $p['usd']['now'] ) . ' ' . vitalstack_offer_x( 's', '', $p['inr']['was'], $p['usd']['was'] ) . ' ' . $save,
		esc_html__( 'All 3 career tracks: Data Scientist · Gen AI Developer · Agentic AI Engineer', 'vitalstack' ),
		vitalstack_offer_x( 'span', '', $p['inr']['per'] ? sprintf( /* translators: %s: price */ __( 'Just %s a month, paid once', 'vitalstack' ), $p['inr']['per'] ) : '', $p['usd']['per'] ? sprintf( /* translators: %s: price */ __( 'Just %s a month, paid once', 'vitalstack' ), $p['usd']['per'] ) : '' ),
	);
	if ( ! empty( $ref['on'] ) && ! empty( $ref['percent'] ) ) {
		/* translators: %d: percent */
		$items[] = '🎁 ' . esc_html( sprintf( __( 'Invite a friend: they get %d%% off', 'vitalstack' ), (int) $ref['percent'] ) );
	}
	$items[] = '🎓 ' . esc_html__( 'Certificates · real projects · no ads', 'vitalstack' );
	$items   = (array) apply_filters( 'vitalstack_offer_bar_items', $items ); // e.g. a festival greeting first (items are HTML, already escaped)
	$run     = '<span class="obar-item">' . implode( '</span><span class="obar-dot" aria-hidden="true">✦</span><span class="obar-item">', $items ) . '</span><span class="obar-dot" aria-hidden="true">✦</span>';
	?>
	<div class="obar<?php echo esc_attr( (string) apply_filters( 'vitalstack_offer_bar_class', '' ) ); ?>" data-offer data-obar data-cur-rule="<?php echo esc_attr( $rule ); ?>" data-cur="<?php echo esc_attr( $cur ); ?>" role="region" aria-label="<?php esc_attr_e( 'Launch offer', 'vitalstack' ); ?>">
		<a class="obar-track" href="<?php echo esc_url( add_query_arg( array( 'utm_source' => 'site', 'utm_medium' => 'offer_bar' ), vitalstack_pricing_url() ) ); ?>">
			<span class="obar-run"><?php echo $run; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?></span>
			<span class="obar-run" aria-hidden="true"><?php echo $run; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?></span>
		</a>
		<a class="obar-cta" href="<?php echo esc_url( add_query_arg( array( 'utm_source' => 'site', 'utm_medium' => 'offer_bar' ), vitalstack_pricing_url() ) ); ?>"><?php echo esc_html( function_exists( 'vitalstack_offer_cta_label' ) && 'waitlist' === ( function_exists( 'vitalstack_pay_action' ) ? vitalstack_pay_action() : '' ) ? __( 'Join waitlist', 'vitalstack' ) : __( 'Claim deal', 'vitalstack' ) ); ?> →</a>
		<button type="button" class="obar-x" data-obar-close aria-label="<?php esc_attr_e( 'Hide the offer for today', 'vitalstack' ); ?>">✕</button>
	</div>
	<?php
}
add_action( 'wp_body_open', 'vitalstack_offer_bar', 5 );
