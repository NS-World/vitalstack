<?php
/**
 * AI Pro plans, sold through the theme's existing Razorpay checkout
 * (server-side prices, signed verification, receipts, refunds).
 *
 * A purchase stores access under the "ai-hub" key of the reader's vs_premium
 * meta, exactly like a course track, so refunds and renewals already work.
 * Prices can be changed in Settings → VitalStack Payments like any plan.
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

const VSAI_TRACK = 'ai-hub';

/** The AI Pro plans: id => details. */
function vsai_plan_defs() {
	return array(
		'ai-month'    => array(
			'title'  => __( 'AI Pro Monthly', 'vitalstack-ai-hub' ),
			'tracks' => array( VSAI_TRACK ),
			'days'   => 31,
			'inr'    => 399,
			'usd'    => 9,
			'blurb'  => __( 'Every premium prompt, motion design and guide for 31 days. No auto-renewal.', 'vitalstack-ai-hub' ),
		),
		'ai-year'     => array(
			'title'  => __( 'AI Pro Yearly', 'vitalstack-ai-hub' ),
			'tracks' => array( VSAI_TRACK ),
			'days'   => 365,
			'inr'    => 1999,
			'usd'    => 59,
			'best'   => true,
			'blurb'  => __( 'A full year of everything in the AI Hub, including all new drops.', 'vitalstack-ai-hub' ),
		),
		'ai-lifetime' => array(
			'title'  => __( 'AI Pro Lifetime', 'vitalstack-ai-hub' ),
			'tracks' => array( VSAI_TRACK ),
			'days'   => 0,
			'inr'    => 4999,
			'usd'    => 149,
			'blurb'  => __( 'Pay once, keep every current and future AI Hub item forever.', 'vitalstack-ai-hub' ),
		),
	);
}

add_filter(
	'vitalstack_pay_plans',
	function ( $plans ) {
		return array_merge( $plans, vsai_plan_defs() );
	}
);

/**
 * True when the active theme's checkout knows the AI Pro plans (theme 4.20.9+
 * has the vitalstack_pay_plans filter). Without it the plans are shown with
 * their prices but cannot be bought yet.
 */
function vsai_checkout_ready() {
	static $ready = null;
	if ( null === $ready ) {
		$all   = function_exists( 'vitalstack_pay_plans' ) ? vitalstack_pay_plans() : array();
		$ready = isset( $all['ai-month'], $all['ai-year'], $all['ai-lifetime'] );
	}
	return $ready;
}

/** Prices as sold right now (after any change in Settings), per plan and currency. Never empty, never zero. */
function vsai_plan_prices() {
	$defs = vsai_plan_defs();
	$all  = vsai_checkout_ready() ? vitalstack_pay_plans() : $defs;
	$out  = array();
	foreach ( $defs as $id => $def ) {
		$p = isset( $all[ $id ] ) ? array_merge( $def, $all[ $id ] ) : $def;
		foreach ( array( 'inr', 'usd' ) as $cur ) {
			if ( empty( $p[ $cur ] ) || (int) $p[ $cur ] < 1 ) {
				$p[ $cur ] = $def[ $cur ]; // a blank or zero saved price never shows as ₹0
			}
		}
		$out[ $id ] = $p;
	}
	return $out;
}

/** Lowest monthly price as a label, for "from ₹399/month" teasers. */
function vsai_from_price_label() {
	$p   = vsai_plan_prices()['ai-month'];
	$cur = function_exists( 'vitalstack_pay_page_currency' ) && 'usd' === vitalstack_pay_page_currency() ? 'usd' : 'inr';
	return vsai_price( $p[ $cur ], $cur );
}

/** Tell admins when the theme cannot sell AI Pro yet. */
add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'manage_options' ) || vsai_checkout_ready() ) {
			return;
		}
		echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'VitalStack AI Hub:', 'vitalstack-ai-hub' ) . '</strong> ' . esc_html__( 'AI Pro plans cannot be bought yet because the active theme is older than 4.20.9. Upload VitalStack theme 4.20.9 or newer, then purge the cache. Until then the pricing page shows the plans with an "Opening soon" button.', 'vitalstack-ai-hub' ) . '</p></div>';
	}
);

/**
 * After paying, the theme sends the buyer to /premium/<track>/. There is no
 * course page for "ai-hub", so send them to the hub with a thank-you message.
 */
add_action(
	'template_redirect',
	function () {
		$path = (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- only compared
		if ( preg_match( '#/premium/' . VSAI_TRACK . '/?$#', $path ) ) {
			wp_safe_redirect( add_query_arg( 'vsai_paid', '1', vsai_url() ) );
			exit;
		}
	},
	1
);

