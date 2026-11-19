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
	$items = is_array( $items ) ? $items : array();
	// Generated catalogue (tools/aihub/gen_*.py), added after the hand-made items.
	$gen = VSAI_DIR . 'includes/data/' . $type . '-gen.php';
	if ( file_exists( $gen ) ) {
		$more = include $gen;
		if ( is_array( $more ) && isset( $more['rows'] ) ) {
			$more = vsai_motion_gen_items( $more );
		}
		$items = is_array( $more ) ? array_merge( $items, $more ) : $items;
	}
	return $items;
}

/**
 * Find one motion design without expanding all 10,000 (used by the preview
 * view and the copy endpoint, which run once per tile).
 */
function vsai_find_motion( $id ) {
	if ( ! preg_match( '/^[a-z0-9-]{1,60}$/', $id ) ) {
		return null;
	}
	foreach ( array_merge( vsai_custom( 'motion' ), (array) include VSAI_DIR . 'includes/data/motion.php' ) as $it ) {
		if ( (string) $it['id'] === $id ) {
			return apply_filters( 'vsai_find_motion', $it );
		}
	}
	$gen = VSAI_DIR . 'includes/data/motion-gen.php';
	$d   = file_exists( $gen ) ? include $gen : array();
	if ( ! isset( $d['rows'] ) ) {
		foreach ( (array) $d as $it ) {
			if ( isset( $it['id'] ) && $it['id'] === $id ) {
				return $it;
			}
		}
		return null;
	}
	foreach ( $d['rows'] as $r ) {
		$e = $d['engines'][ $r[0] ] ?? null;
		$t = $d['themes'][ $r[1] ] ?? null;
		$p = $d['palettes'][ $r[2] ] ?? null;
		if ( $e && $t && $p && trim( strtolower( preg_replace( '/[^a-z0-9]+/i', '-', $e[0] . '-' . $t[0] . '-' . strtok( $p, ' ' ) ) ), '-' ) === $id ) {
			$one = vsai_motion_gen_items( array( 'engines' => $d['engines'], 'themes' => $d['themes'], 'palettes' => $d['palettes'], 'rows' => array( $r ) ) );
			return $one ? $one[0] : null;
		}
	}
	return null;
}

/**
 * Expand the compact generated motion catalogue (engine, theme, palette per
 * row) into normal items. Ids and texts match tools/aihub/gen_motion.py.
 */
function vsai_motion_gen_items( $d ) {
	$out = array();
	foreach ( $d['rows'] as $r ) {
		list( $ei, $ti, $pi, $pro, $sh ) = $r;
		if ( ! isset( $d['engines'][ $ei ], $d['themes'][ $ti ], $d['palettes'][ $pi ] ) ) {
			continue;
		}
		$e       = $d['engines'][ $ei ];
		$t       = $d['themes'][ $ti ];
		$word    = strtok( $d['palettes'][ $pi ], ' ' );
		$pword   = strtolower( preg_replace( '/[^a-z0-9]+/i', '-', $word ) );
		$summary = str_replace( '{label}', $t[2], $e[4] );
		$summary = preg_replace( '/\b([Aa]) (?=[AEIOUaeiou])/', '$1n ', $summary );
		$out[]   = array(
			'id'      => trim( strtolower( preg_replace( '/[^a-z0-9]+/i', '-', $e[0] . '-' . $t[0] . '-' . $word ) ), '-' ),
			'title'   => $word . ' ' . $e[1] . ' · ' . $t[1],
			'cat'     => $e[2],
			'premium' => (bool) $pro,
			'summary' => ucfirst( $summary ),
			'tags'    => $e[3] . ' ' . strtolower( $t[1] ) . ' ' . strtolower( $d['palettes'][ $pi ] ),
			'src'     => 'gen2',
			'shard'   => $e[0] . '-' . (int) $sh,
			'poster'  => $e[0] . '-' . $pword,
		);
	}
	return $out;
}

/**
 * Code + build prompt of a generated motion design, loaded only when needed
 * (full-screen view or a copy). The files exit when opened directly.
 */
function vsai_motion_payload( $it ) {
	static $cache = array();
	if ( ! empty( $it['src'] ) && 'gen2' === $it['src'] ) {
		static $shards = array();
		$sh = (string) ( $it['shard'] ?? '' );
		if ( ! preg_match( '/^[a-z0-9-]{1,60}$/', $sh ) ) {
			return array();
		}
		if ( ! isset( $shards[ $sh ] ) ) {
			$shards[ $sh ] = array();
			$file          = VSAI_DIR . 'data/motion/' . $sh . '.php';
			$raw           = file_exists( $file ) ? file_get_contents( $file ) : false; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local plugin file
			$pos           = false === $raw ? false : strpos( $raw, '__halt_compiler();' );
			if ( false !== $pos ) {
				$json = gzdecode( substr( $raw, $pos + strlen( '__halt_compiler();' ) ) );
				$data = false === $json ? null : json_decode( $json, true );
				$shards[ $sh ] = is_array( $data ) ? $data : array();
			}
		}
		$row = $shards[ $sh ][ $it['id'] ] ?? null;
		return is_array( $row ) ? array( 'code' => (string) $row[0], 'spec' => (string) $row[1] ) : array();
	}
	if ( empty( $it['src'] ) || 'gen' !== $it['src'] || ! preg_match( '/^[a-z0-9-]{1,60}$/', (string) $it['id'] ) ) {
		return array();
	}
	if ( ! isset( $cache[ $it['id'] ] ) ) {
		$file                  = VSAI_DIR . 'includes/data/motion-gen/' . $it['id'] . '.php';
		$data                  = file_exists( $file ) ? include $file : array();
		$cache[ $it['id'] ] = is_array( $data ) ? $data : array();
	}
	return $cache[ $it['id'] ];
}

/** The HTML document of a motion item. */
function vsai_motion_code( $it ) {
	if ( isset( $it['code'] ) && '' !== (string) $it['code'] ) {
		return (string) $it['code'];
	}
	$p = vsai_motion_payload( $it );
	return isset( $p['code'] ) ? (string) $p['code'] : '';
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
	if ( 'motion' === $type ) {
		return vsai_find_motion( (string) $id );
	}
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

/**
 * The full build prompt of a motion item: its own detailed spec when it has
 * one, otherwise the short brief wrapped in a complete, structured prompt.
 */
function vsai_full_prompt( $it ) {
	if ( ! empty( $it['spec'] ) ) {
		return $it['spec'];
	}
	$payload = vsai_motion_payload( $it );
	if ( ! empty( $payload['spec'] ) ) {
		return (string) $payload['spec'];
	}
	$brief = trim( (string) ( $it['prompt'] ?? '' ) );
	return '# ' . $it['title'] . " — build prompt (VitalStack Motion Studio)\n\n"
		. "## Goal\n" . $it['summary'] . "\nBuild it as ONE self-contained `index.html`: inline CSS and vanilla JavaScript, no libraries, no external images or fonts, no build step.\n\n"
		. "## What to build\n" . $brief . "\n\n"
		. "## Requirements\n"
		. "- Responsive from 320 px phones to 1920 px desktops; no horizontal page scroll.\n"
		. "- 60 fps: animate transform and opacity; use requestAnimationFrame for JS motion and pause work when the tab is hidden.\n"
		. "- Respect `prefers-reduced-motion: reduce` with a calm or static version.\n"
		. "- Semantic HTML, real buttons and links, visible focus styles, readable contrast.\n"
		. "- Use CSS custom properties for colours and sizes so the design is easy to re-theme.\n\n"
		. "## Acceptance checklist\n"
		. "- Looks and moves as described at desktop and phone sizes.\n"
		. "- No console errors; works offline from a single file.\n"
		. '- Keywords: ' . ( $it['tags'] ?? '' ) . "\n";
}
