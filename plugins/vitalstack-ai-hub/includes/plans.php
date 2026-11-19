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

/** Prices as sold right now (after any change in Settings), per plan and currency. */
function vsai_plan_prices() {
	$out = array();
	$all = function_exists( 'vitalstack_pay_plans' ) ? vitalstack_pay_plans() : vsai_plan_defs();
	foreach ( array_keys( vsai_plan_defs() ) as $id ) {
		if ( isset( $all[ $id ] ) ) {
			$out[ $id ] = $all[ $id ];
		}
	}
	return $out;
}

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

