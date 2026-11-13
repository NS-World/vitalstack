<?php
/**
 * Settings and helpers.
 *
 * @package VitalStackGrowth
 */

defined( 'ABSPATH' ) || exit;

function vsg_defaults() {
	return array(
		'offer_on'      => 1,
		'name'          => 'Launch Sale',
		'ends'          => '',      // Y-m-d H:i in the site's time zone; '' = no end date, no countdown
		'regular'       => array(), // plan id => array( inr, usd ): the real price after the offer
		'home'          => 1,       // deal card in the home page hero
		'pricing'       => 1,       // deal panel at the top of /pricing/
		'slide'         => 1,       // small offer card that slides in once every few days
		'slide_days'    => 3,
		'slide_lessons' => 1,       // also on lesson pages
		'daily_test'    => 1,       // Testing agent: daily check, email only when something is wrong
		'weekly_report' => 1,       // Monday report: sales, sign-ups, health
		'report_email'  => '',      // '' = the site admin email
	);
}

function vsg_settings() {
	$s = wp_parse_args( (array) get_option( 'vsg_settings', array() ), vsg_defaults() );
	$s['regular'] = is_array( $s['regular'] ) ? $s['regular'] : array();
	return $s;
}

/** The theme functions the offer needs. */
function vsg_theme_ready() {
	return function_exists( 'vitalstack_pay_plans' ) && function_exists( 'vitalstack_pricing_url' ) && function_exists( 'vitalstack_pay_price_label' );
}

/** Offer end as a UTC timestamp, or 0 when there is no end date. */
function vsg_offer_end() {
	$ends = (string) vsg_settings()['ends'];
	if ( '' === $ends ) {
		return 0;
	}
	$d = date_create_immutable_from_format( 'Y-m-d H:i', $ends, wp_timezone() );
	return $d ? $d->getTimestamp() : 0;
}

/** The offer is switched on and has not ended. */
function vsg_offer_running() {
	$s   = vsg_settings();
	$end = vsg_offer_end();
	return vsg_theme_ready() && ! empty( $s['offer_on'] ) && ( ! $end || time() < $end );
}

/** Where the report emails go. */
function vsg_report_email() {
	$e = (string) vsg_settings()['report_email'];
	return is_email( $e ) ? $e : get_option( 'admin_email' );
}
