<?php
/**
 * The AI Hub pages in wp-sitemap.xml.
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

class VSAI_Sitemap extends WP_Sitemaps_Provider {
	public function __construct() {
		$this->name        = 'aihub';
		$this->object_type = 'aihub';
	}

	public function get_url_list( $page_num, $object_subtype = '' ) {
		if ( $page_num > 1 ) {
			return array();
		}
		$urls = array( array( 'loc' => vsai_url() ) );
		foreach ( array_merge( array_keys( vsai_sections() ), array( 'pricing' ) ) as $s ) {
			$urls[] = array( 'loc' => vsai_url( $s ) );
		}
		// One landing page per category of the big sections.
		$pc = vsai_store_ready() ? wp_list_pluck( vsai_store_facet( 'cat' ), 'slug' ) : array_map( 'sanitize_title', vsai_categories( 'prompts' ) );
		foreach ( $pc as $slug ) {
			$urls[] = array( 'loc' => vsai_list_url( 'prompts', array( 'cat' => $slug ) ) );
		}
		foreach ( vsai_categories( 'motion' ) as $c ) {
			$urls[] = array( 'loc' => vsai_list_url( 'motion', array( 'cat' => sanitize_title( $c ) ) ) );
		}
		return $urls;
	}

	public function get_max_num_pages( $object_subtype = '' ) {
		return 1;
	}
}
