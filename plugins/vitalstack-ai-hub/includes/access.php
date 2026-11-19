<?php
/**
 * Who sees premium AI Hub items: readers with an active AI Pro plan
 * (monthly, yearly or lifetime) and site staff.
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

function vsai_is_member( $user_id = null ) {
	$user_id = null === $user_id ? get_current_user_id() : (int) $user_id;
	$member  = false;
	if ( $user_id ) {
		if ( user_can( $user_id, 'edit_posts' ) ) {
			$member = true; // site staff review everything
		} elseif ( function_exists( 'vitalstack_user_has_premium' ) ) {
			$member = vitalstack_user_has_premium( VSAI_TRACK, $user_id ); // an active AI Pro plan
		}
	}
	return (bool) apply_filters( 'vsai_is_member', $member, $user_id );
}

/** Where "Unlock" buttons go: the pricing page (with tracking), or sign-in. */
function vsai_unlock_url() {
	return vsai_url( 'pricing' );
}

function vsai_signin_url() {
	$back = home_url( add_query_arg( array() ) );
	return function_exists( 'vitalstack_account_url' ) ? vitalstack_account_url( array( 'redirect_to' => $back ) ) : wp_login_url( $back );
}
