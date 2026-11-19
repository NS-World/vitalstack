<?php
/**
 * Who sees premium AI Hub items: any reader with a paid VitalStack plan
 * (any track, monthly, yearly or lifetime), and site staff.
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

function vsai_is_member( $user_id = null ) {
	$user_id = null === $user_id ? get_current_user_id() : (int) $user_id;
	$member  = false;
	if ( $user_id ) {
		if ( user_can( $user_id, 'edit_posts' ) ) {
			$member = true;
		} elseif ( function_exists( 'vitalstack_reader_has_any_premium' ) ) {
			$member = vitalstack_reader_has_any_premium( $user_id );
		}
	}
	return (bool) apply_filters( 'vsai_is_member', $member, $user_id );
}

/** Where "Unlock" buttons go: the pricing page (with tracking), or sign-in. */
function vsai_unlock_url() {
	$url = function_exists( 'vitalstack_pricing_url' ) ? vitalstack_pricing_url() : home_url( '/pricing/' );
	return add_query_arg( array( 'utm_source' => 'ai_hub', 'utm_medium' => 'unlock' ), $url ) . '#plans';
}

function vsai_signin_url() {
	$back = home_url( add_query_arg( array() ) );
	return function_exists( 'vitalstack_account_url' ) ? vitalstack_account_url( array( 'redirect_to' => $back ) ) : wp_login_url( $back );
}
