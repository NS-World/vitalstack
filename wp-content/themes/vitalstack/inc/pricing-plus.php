<?php
/**
 * Pricing extras (4.13.2):
 *   - launch-offer details on each plan card: the earlier list price struck
 *     through, "Save N%" and the price per month;
 *   - days left on the reader's premium access, and a renewal note in the
 *     last 7 days;
 *   - "All sales are final" wording when the refund window is 0.
 *
 * The struck prices are the site's real earlier list prices (before the 4.13
 * launch prices). A badge only shows when today's price is lower.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/** Earlier list prices: plan => [inr, usd]. */
function vitalstack_plan_list_prices() {
	return apply_filters(
		'vitalstack_plan_list_prices',
		array(
			'all-month'           => array( 399, 9 ),
			'data-scientist'      => array( 1999, 39 ),
			'gen-ai-developer'    => array( 1999, 39 ),
			'agentic-ai-engineer' => array( 1999, 39 ),
			'all-year'            => array( 2999, 69 ),
			'lifetime'            => array( 6999, 149 ),
			'combo-ai-builder'    => array( 2499, 49 ),
			'combo-data-ai'       => array( 2499, 49 ),
		)
	);
}

/** Money label for the per-month price: ₹125 or $4.08. */
function vitalstack_plan_money( $amount, $cur ) {
	return 'inr' === $cur ? '₹' . number_format_i18n( round( $amount ) ) : '$' . number_format( $amount, $amount < 10 ? 2 : 0 );
}

/**
 * The offer line under a plan's price. Each part carries both currencies so
 * the page can follow the ₹/$ choice.
 */
function vitalstack_plan_offer( $plan_id, $plan ) {
	$list  = vitalstack_plan_list_prices()[ $plan_id ] ?? null;
	$parts = array();
	foreach ( array( 'inr', 'usd' ) as $i => $cur ) {
		$now  = (int) $plan[ $cur ];
		$was  = $list ? (int) $list[ $i ] : 0;
		$save = $was > $now && $now > 0 ? (int) round( ( 1 - $now / $was ) * 100 ) : 0;
		$parts['was'][ $cur ]  = $save ? vitalstack_pay_price_label( $was, $cur ) : '';
		/* translators: %d: percent saved */
		$parts['save'][ $cur ] = $save >= 5 ? sprintf( __( 'Save %d%%', 'vitalstack' ), $save ) : '';
		if ( 365 === (int) $plan['days'] ) {
			/* translators: %s: price per month */
			$parts['per'][ $cur ] = sprintf( __( 'just %s/month, paid once', 'vitalstack' ), vitalstack_plan_money( $now / 12, $cur ) );
		} elseif ( 0 === (int) $plan['days'] ) {
			$parts['per'][ $cur ] = __( 'pay once, yours for life', 'vitalstack' );
		} else {
			$parts['per'][ $cur ] = '';
		}
	}
	$html = '';
	foreach ( array( 'was' => 's', 'save' => 'b', 'per' => 'span' ) as $k => $tag ) {
		if ( '' === $parts[ $k ]['inr'] && '' === $parts[ $k ]['usd'] ) {
			continue;
		}
		$html .= '<' . $tag . ' class="plan-' . $k . '-x" data-x-inr="' . esc_attr( $parts[ $k ]['inr'] ) . '" data-x-usd="' . esc_attr( $parts[ $k ]['usd'] ) . '"' . ( '' === $parts[ $k ]['inr'] ? ' hidden' : '' ) . '>' . esc_html( $parts[ $k ]['inr'] ) . '</' . $tag . '> ';
	}
	return $html ? '<p class="plan-offer">' . $html . '</p>' : '';
}

/* ── Days left ────────────────────────────────────────────────────────── */

/**
 * Days left on a reader's access to a track: an int, 'life' for no end
 * date, or 0 when there is none.
 */
function vitalstack_premium_days_left( $user_id, $track ) {
	$access = get_user_meta( (int) $user_id, 'vs_premium', true );
	$v      = is_array( $access ) && isset( $access[ $track ] ) ? (int) $access[ $track ] : 0;
	if ( 1 === $v ) {
		return 'life';
	}
	return $v > time() ? (int) ceil( ( $v - time() ) / DAY_IN_SECONDS ) : 0;
}

/** "23 days left" / "No end date" / ''. */
function vitalstack_premium_days_label( $user_id, $track ) {
	$d = vitalstack_premium_days_left( $user_id, $track );
	if ( 'life' === $d ) {
		return __( 'Lifetime access', 'vitalstack' );
	}
	/* translators: %d: days */
	return $d ? sprintf( _n( '%d day left', '%d days left', $d, 'vitalstack' ), $d ) : '';
}

/** The fewest days left across a reader's active, time-limited tracks (0 = none). */
function vitalstack_premium_soonest( $user_id ) {
	$min = 0;
	foreach ( array_keys( vitalstack_premium_tracks() ) as $t ) {
		$d = vitalstack_premium_days_left( $user_id, $t );
		if ( is_int( $d ) && $d > 0 ) {
			$min = $min ? min( $min, $d ) : $d;
		}
	}
	return $min;
}

/** In the last 7 days of a plan: a small renewal note on every page (signed-in pages are not cached). */
function vitalstack_premium_renew_note() {
	$uid = get_current_user_id();
	if ( ! $uid || user_can( $uid, 'edit_posts' ) || ! function_exists( 'vitalstack_pricing_url' ) ) {
		return;
	}
	$d = vitalstack_premium_soonest( $uid );
	if ( ! $d || $d > 7 || get_query_var( 'vs_pricing' ) || isset( $_COOKIE[ 'vs_renew_hide_' . $d ] ) ) {
		return;
	}
	?>
	<div class="renew-note" role="status" data-renew="<?php echo (int) $d; ?>">
		<p>⏳ <?php echo esc_html( sprintf( /* translators: %d: days */ _n( 'Your Premium ends in %d day. Renew now: the new time is added on, nothing is lost.', 'Your Premium ends in %d days. Renew now: the new time is added on, nothing is lost.', $d, 'vitalstack' ), $d ) ); ?></p>
		<a class="btn btn-primary btn-sm" href="<?php echo esc_url( vitalstack_pricing_url() ); ?>"><?php esc_html_e( 'Renew', 'vitalstack' ); ?></a>
		<button type="button" class="renew-note-x" aria-label="<?php esc_attr_e( 'Close', 'vitalstack' ); ?>" onclick="document.cookie='vs_renew_hide_<?php echo (int) $d; ?>=1; path=/; max-age=86400; samesite=lax';this.parentNode.remove();">×</button>
	</div>
	<?php
}
add_action( 'wp_footer', 'vitalstack_premium_renew_note', 5 );

/* ── All sales final ──────────────────────────────────────────────────── */

function vitalstack_sales_final() {
	return function_exists( 'vitalstack_pay_settings' ) && 0 === (int) vitalstack_pay_settings()['refund_days'];
}
