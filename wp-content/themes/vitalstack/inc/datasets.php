<?php
/**
 * Datasets Hub (5.0): free practice datasets for data analysts, with a
 * column dictionary, preview, practice questions and CSV/JSON download.
 * The data is synthetic (tools/build_datasets.py) and contains no real people.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vitalstack_datasets() {
	static $sets = null;
	if ( null === $sets ) {
		$file = VITALSTACK_DIR . '/data/datasets/index.php';
		$sets = is_readable( $file ) ? (array) include $file : array();
	}
	return $sets;
}

function vitalstack_dataset_url( $slug ) {
	return VITALSTACK_URI . '/data/datasets/' . rawurlencode( sanitize_key( $slug ) ) . '.csv';
}

function vitalstack_datasets_page_url() {
	$page = get_page_by_path( 'datasets' );
	return $page ? get_permalink( $page ) : home_url( '/datasets/' );
}

function vitalstack_datasets_assets() {
	if ( is_page_template( 'page-datasets.php' ) ) {
		wp_enqueue_script( 'vitalstack-datasets', vitalstack_asset_url( 'assets/js/datasets.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'vitalstack_datasets_assets', 26 );
