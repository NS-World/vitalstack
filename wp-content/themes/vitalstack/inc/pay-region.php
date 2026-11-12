<?php
/**
 * Which currency a reader pays in (4.13.1): readers in India pay in ₹,
 * everyone else in $.
 *
 * The server decides for every quote and order, so a visitor can't pick the
 * cheaper currency:
 *   1. The visitor's country, when the CDN or host sends it (Cloudflare
 *      CF-IPCountry, CloudFront, GeoIP/LiteSpeed headers).
 *   2. Otherwise the device time zone the page reports in a cookie
 *      (Asia/Kolkata = India).
 *   3. With neither, ₹ (most visitors are in India).
 *
 * A phone number isn't used: checking one needs an SMS code (a cost per
 * sign-up), and Razorpay asks for the phone at checkout anyway.
 *
 * Settings → VitalStack Payments → "Currency" can switch this to "readers
 * choose" (the old ₹/$ switch) or "₹ only".
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/** "auto" (by location), "choose" (reader picks) or "inr" (₹ for everyone). */
function vitalstack_pay_currency_rule() {
	$rule = vitalstack_pay_settings()['cur_rule'] ?? 'auto';
	return in_array( $rule, array( 'auto', 'choose', 'inr' ), true ) ? $rule : 'auto';
}

/** Two-letter country from the CDN or host, or ''. */
function vitalstack_pay_visitor_country() {
	foreach ( array( 'HTTP_CF_IPCOUNTRY', 'HTTP_CLOUDFRONT_VIEWER_COUNTRY', 'HTTP_X_COUNTRY_CODE', 'HTTP_X_GEO_COUNTRY', 'GEOIP_COUNTRY_CODE', 'HTTP_GEOIP_COUNTRY_CODE' ) as $key ) {
		$v = isset( $_SERVER[ $key ] ) ? strtoupper( substr( sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) ), 0, 2 ) ) : '';
		if ( preg_match( '/^[A-Z]{2}$/', $v ) && ! in_array( $v, array( 'XX', 'T1', 'A1', 'A2', 'EU', 'AP' ), true ) ) {
			return $v; // XX unknown, T1 Tor, A1/A2 anonymous proxy or satellite
		}
	}
	return '';
}

/** "in", "out" or "" from the time zone cookie the page sets. */
function vitalstack_pay_visitor_tz() {
	$tz = isset( $_COOKIE['vs_tz'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['vs_tz'] ) ) : '';
	if ( '' === $tz || ! preg_match( '#^[A-Za-z_]+(/[A-Za-z0-9_+-]+){0,2}$#', $tz ) ) {
		return '';
	}
	return in_array( $tz, array( 'Asia/Kolkata', 'Asia/Calcutta' ), true ) ? 'in' : 'out';
}

/**
 * The currency this visitor pays in, or '' when readers may choose.
 */
function vitalstack_pay_locked_currency() {
	$rule = vitalstack_pay_currency_rule();
	if ( 'choose' === $rule ) {
		return '';
	}
	if ( 'inr' === $rule ) {
		return 'inr';
	}
	$country = vitalstack_pay_visitor_country();
	if ( $country ) {
		return 'IN' === $country ? 'inr' : 'usd';
	}
	return 'out' === vitalstack_pay_visitor_tz() ? 'usd' : 'inr';
}

/**
 * For the pricing page: "inr"/"usd" when the server knows, "tz" when the page
 * may be cached for everyone (signed out) and the browser should use the same
 * time-zone rule, or "" when readers choose.
 */
function vitalstack_pay_page_currency() {
	$rule = vitalstack_pay_currency_rule();
	if ( 'choose' === $rule ) {
		return '';
	}
	if ( 'inr' === $rule ) {
		return 'inr';
	}
	return is_user_logged_in() ? vitalstack_pay_locked_currency() : 'tz';
}
