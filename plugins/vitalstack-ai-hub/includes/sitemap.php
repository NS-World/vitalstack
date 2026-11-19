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
		return $urls;
	}

	public function get_max_num_pages( $object_subtype = '' ) {
		return 1;
	}
}
