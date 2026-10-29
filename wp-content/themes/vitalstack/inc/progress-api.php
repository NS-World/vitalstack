<?php
/**
 * Saves signed-in readers' lesson progress to their account, so it follows
 * them across devices. Guests keep progress in localStorage only.
 *
 * GET/POST /wp-json/vitalstack/v1/progress   body: { "progress": { "path-slug": [lessonId, ...] } }
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vitalstack_clean_progress( $raw ) {
	$clean = array();
	if ( ! is_array( $raw ) ) {
		return $clean;
	}
	foreach ( array_slice( $raw, 0, 50, true ) as $path => $ids ) {
		$path = sanitize_key( $path );
		if ( '' === $path || ! is_array( $ids ) ) {
			continue;
		}
		$ids = array_values( array_unique( array_filter( array_map( 'absint', array_slice( $ids, 0, 200 ) ) ) ) );
		if ( $ids ) {
			$clean[ $path ] = array_map( 'strval', $ids );
		}
	}
	return $clean;
}

function vitalstack_get_user_progress( $user_id ) {
	$saved = get_user_meta( $user_id, 'vs_progress', true );
	return is_array( $saved ) ? $saved : array();
}

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'vitalstack/v1',
			'/progress',
			array(
				array(
					'methods'             => 'GET',
					'permission_callback' => 'is_user_logged_in',
					'callback'            => function () {
						$uid = get_current_user_id();
						return array(
							'progress' => (object) vitalstack_get_user_progress( $uid ),
							'newCerts' => function_exists( 'vitalstack_certs_take_pending' ) ? vitalstack_certs_take_pending( $uid ) : array(),
						);
					},
				),
				array(
					'methods'             => 'POST',
					'permission_callback' => 'is_user_logged_in',
					'callback'            => function ( WP_REST_Request $req ) {
						$uid      = get_current_user_id();
						$progress = vitalstack_clean_progress( $req->get_param( 'progress' ) );
						$before   = array();
						foreach ( vitalstack_get_user_progress( $uid ) as $ids ) {
							$before = array_merge( $before, (array) $ids );
						}
						$new = array();
						foreach ( $progress as $ids ) {
							$new = array_merge( $new, array_diff( $ids, $before ) );
						}
						update_user_meta( $uid, 'vs_progress', $progress );
						$gained = function_exists( 'vitalstack_xp_for_lessons' ) ? vitalstack_xp_for_lessons( $uid, array_unique( $new ) ) : 0;
						$certs  = function_exists( 'vitalstack_certs_check' ) ? vitalstack_certs_check( $uid, $progress ) : array();
						return array(
							'newCerts'    => $certs,
							'lockedCerts' => function_exists( 'vitalstack_certs_locked' ) ? vitalstack_certs_locked( $uid, $progress ) : array(),
							'progress' => (object) $progress,
							'xp'       => function_exists( 'vitalstack_xp_stats' ) ? vitalstack_xp_stats( $uid ) : null,
							'gained'   => $gained,
						);
					},
				),
			)
		);
	}
);
