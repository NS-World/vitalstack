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
						return array( 'progress' => (object) vitalstack_get_user_progress( get_current_user_id() ) );
					},
				),
				array(
					'methods'             => 'POST',
					'permission_callback' => 'is_user_logged_in',
					'callback'            => function ( WP_REST_Request $req ) {
						$progress = vitalstack_clean_progress( $req->get_param( 'progress' ) );
						update_user_meta( get_current_user_id(), 'vs_progress', $progress );
						return array( 'progress' => (object) $progress );
					},
				),
			)
		);
	}
);
