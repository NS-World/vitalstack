<?php
/**
 * The catalogue: built-in items (includes/data/*.php, updated with the plugin)
 * plus items the admin adds in wp-admin → AI Hub (custom post type).
 *
 * Every item: id, title, cat, premium (bool), summary, and per type:
 * - library: kind (resource|guide), by, url, level, body (HTML, guides only)
 * - prompts: tool, kind (text|image|video|audio|code), prompt, output, image (URL, optional)
 * - commands: tool, rows [[keys, what], …], tips (list)
 * - motion: tags, code (full HTML document), prompt (to build it with AI)
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

function vsai_types() {
	return array( 'library', 'prompts', 'commands', 'motion' );
}

function vsai_register_cpt() {
	register_post_type(
		'vsai_item',
		array(
			'labels'          => array(
				'name'          => __( 'AI Hub items', 'vitalstack-ai-hub' ),
				'singular_name' => __( 'AI Hub item', 'vitalstack-ai-hub' ),
				'add_new_item'  => __( 'Add AI Hub item', 'vitalstack-ai-hub' ),
				'edit_item'     => __( 'Edit AI Hub item', 'vitalstack-ai-hub' ),
				'menu_name'     => __( 'AI Hub', 'vitalstack-ai-hub' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_rest'    => false,
			'menu_icon'       => 'dashicons-superhero',
			'supports'        => array( 'title', 'editor', 'thumbnail' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'vsai_register_cpt' );

/** Built-in items of one type. */
function vsai_builtin( $type ) {
	if ( ! in_array( $type, vsai_types(), true ) ) {
		return array();
	}
	$items = include VSAI_DIR . 'includes/data/' . $type . '.php';
	return is_array( $items ) ? $items : array();
}

/** Items added in wp-admin, in the same shape as the built-in ones. */
function vsai_custom( $type ) {
	$posts = get_posts(
		array(
			'post_type'      => 'vsai_item',
			'post_status'    => 'publish',
			'posts_per_page' => 500,
			'meta_key'       => '_vsai_type', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => $type, // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
	$out = array();
	foreach ( $posts as $p ) {
		$m    = function ( $k ) use ( $p ) {
			return (string) get_post_meta( $p->ID, '_vsai_' . $k, true );
		};
		$item = array(
			'id'      => 'c' . $p->ID,
			'title'   => get_the_title( $p ),
			'cat'     => $m( 'cat' ) ? $m( 'cat' ) : __( 'More', 'vitalstack-ai-hub' ),
			'premium' => '1' === $m( 'premium' ),
			'summary' => $m( 'summary' ),
			'tool'    => $m( 'tool' ),
			'kind'    => $m( 'kind' ),
			'url'     => $m( 'url' ),
			'by'      => $m( 'by' ),
			'level'   => $m( 'level' ),
		);
		if ( 'library' === $type ) {
			$item['body'] = apply_filters( 'the_content', $p->post_content ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- core hook
			$item['kind'] = $item['url'] && ! trim( wp_strip_all_tags( $p->post_content ) ) ? 'resource' : 'guide';
		} elseif ( 'prompts' === $type ) {
			$item['prompt'] = $p->post_content;
			$item['output'] = $m( 'output' );
			$item['image']  = has_post_thumbnail( $p ) ? (string) get_the_post_thumbnail_url( $p, 'large' ) : '';
		} elseif ( 'commands' === $type ) {
			$rows = array();
			foreach ( preg_split( '/\r?\n/', $p->post_content ) as $line ) {
				$parts = array_map( 'trim', explode( '|', wp_strip_all_tags( $line ), 2 ) );
				if ( 2 === count( $parts ) && '' !== $parts[0] ) {
					$rows[] = $parts;
				}
			}
			$item['rows'] = $rows;
			$item['tips'] = array_filter( array_map( 'trim', explode( "\n", $m( 'output' ) ) ) );
		} elseif ( 'motion' === $type ) {
			$item['code']   = (string) get_post_meta( $p->ID, '_vsai_code', true );
			$item['prompt'] = $m( 'output' );
			$item['tags']   = $m( 'cat' );
		}
		$out[] = $item;
	}
	return $out;
}

/** Built-in + custom, custom first (newest additions on top). */
function vsai_items( $type ) {
	static $cache = array();
	if ( ! isset( $cache[ $type ] ) ) {
		$cache[ $type ] = apply_filters( 'vsai_items', array_merge( vsai_custom( $type ), vsai_builtin( $type ) ), $type );
	}
	return $cache[ $type ];
}

function vsai_find( $type, $id ) {
	foreach ( vsai_items( $type ) as $it ) {
		if ( (string) $it['id'] === (string) $id ) {
			return $it;
		}
	}
	return null;
}

/** Categories of a type in first-seen order. */
function vsai_categories( $type ) {
	$cats = array();
	foreach ( vsai_items( $type ) as $it ) {
		$cats[ $it['cat'] ] = true;
	}
	return array_keys( $cats );
}
