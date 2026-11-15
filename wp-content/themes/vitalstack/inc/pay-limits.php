<?php
/**
 * 4.20.4: checkout limits that fit testing.
 *
 * Live mode keeps the strict anti-abuse limits (a real buyer never opens
 * checkout 10 times an hour). Test mode allows far more tries so the site
 * owner can test again and again, and an admin testing in test mode is not
 * limited at all in practice.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/**
 * The per-hour limit for one payment action.
 *
 * @param int    $live   Limit in live mode.
 * @param string $action order | quote | verify.
 */
function vitalstack_pay_limit( $live, $action = '' ) {
	$keys  = function_exists( 'vitalstack_pay_keys' ) ? vitalstack_pay_keys() : array( '', '', '', 'live' );
	$test  = 'live' !== $keys[3];
	$limit = (int) $live;
	if ( $test ) {
		$limit = current_user_can( 'manage_options' ) ? 1000 : $limit * 6;
	}
	return max( 1, (int) apply_filters( 'vitalstack_pay_limit', $limit, $action, $test ) );
}
