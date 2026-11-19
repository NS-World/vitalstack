<?php
/**
 * Prompt store: the 100,000-prompt catalogue lives in its own database table
 * so list pages, filters and search stay fast. The plugin ships the data as
 * compressed parts (data/prompts/part-NNN.php, which exit when opened in a
 * browser) and imports them in small steps in the background.
 *
 * Until the first import finishes, the hub keeps using the built-in PHP lists.
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

const VSAI_DATA_VERSION = '2.0.0';

function vsai_store_table() {
	global $wpdb;
	return $wpdb->prefix . 'vsai_prompts';
}

/** State of the import: version, next step, done flag, full-text support. */
function vsai_store_state() {
	$s = get_option( 'vsai_store', array() );
	return wp_parse_args( is_array( $s ) ? $s : array(), array( 'version' => '', 'step' => 0, 'done' => '', 'ft' => 0, 'error' => '' ) );
}

/** True once a full catalogue has been imported (an older complete import also counts while upgrading). */
function vsai_store_ready() {
	static $ready = null;
	if ( null === $ready ) {
		$ready = '' !== vsai_store_state()['done'];
	}
	return $ready;
}

/** The compressed parts that ship with the plugin. */
function vsai_store_parts() {
	$files = glob( VSAI_DIR . 'data/prompts/part-*.php' );
	sort( $files );
	return $files ? $files : array();
}

/** Create or update the table. Full-text index only where the database supports it (MySQL / MariaDB). */
function vsai_store_install() {
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	$t       = vsai_store_table();
	$charset = $wpdb->get_charset_collate();
	dbDelta(
		"CREATE TABLE $t (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		slug varchar(64) NOT NULL,
		title varchar(255) NOT NULL,
		cat varchar(64) NOT NULL,
		cat_slug varchar(64) NOT NULL,
		sub varchar(120) NOT NULL DEFAULT '',
		sub_slug varchar(120) NOT NULL DEFAULT '',
		tool varchar(80) NOT NULL DEFAULT '',
		tool_slug varchar(80) NOT NULL DEFAULT '',
		kind varchar(16) NOT NULL DEFAULT 'text',
		premium tinyint(1) NOT NULL DEFAULT 0,
		summary text NOT NULL,
		prompt mediumtext NOT NULL,
		output text NOT NULL,
		search text NOT NULL,
		rnk int(11) NOT NULL DEFAULT 3,
		source varchar(16) NOT NULL DEFAULT 'gen',
		ver varchar(16) NOT NULL DEFAULT '',
		PRIMARY KEY  (id),
		UNIQUE KEY slug (slug),
		KEY cat_rank (cat_slug,rnk,id),
		KEY sub_slug (sub_slug),
		KEY tool_slug (tool_slug),
		KEY kind (kind),
		KEY rank_id (rnk,id)
		) $charset;"
	);
	$ft = 0;
	if ( ! defined( 'SQLITE_DB_DROPIN_VERSION' ) && ! defined( 'DB_ENGINE' ) ) {
		$has = $wpdb->get_var( $wpdb->prepare( 'SHOW INDEX FROM `' . esc_sql( $t ) . '` WHERE Key_name = %s', 'search_ft' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table name is ours
		if ( ! $has ) {
			// nosemgrep: php-wpdb-unprepared -- fixed DDL on our own table name; identifiers cannot be prepared and no input is involved.
			$wpdb->query( 'ALTER TABLE `' . esc_sql( $t ) . '` ADD FULLTEXT KEY search_ft (search)' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery.SchemaChange -- one-time schema change on our own table
			$has = $wpdb->get_var( $wpdb->prepare( 'SHOW INDEX FROM `' . esc_sql( $t ) . '` WHERE Key_name = %s', 'search_ft' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
		$ft = $has ? 1 : 0;
	}
	return $ft;
}

/** Normalise one prompt for the table. */
function vsai_store_row( $it, $rank, $source ) {
	$prompt = (string) ( $it['prompt'] ?? '' );
	$sub    = (string) ( $it['sub'] ?? '' );
	return array(
		'slug'      => substr( sanitize_title( (string) $it['id'] ), 0, 64 ),
		'title'     => wp_strip_all_tags( (string) $it['title'] ),
		'cat'       => (string) $it['cat'],
		'cat_slug'  => sanitize_title( (string) $it['cat'] ),
		'sub'       => $sub,
		'sub_slug'  => sanitize_title( $sub ),
		'tool'      => (string) ( $it['tool'] ?? '' ),
		'tool_slug' => sanitize_title( (string) ( $it['tool'] ?? '' ) ),
		'kind'      => in_array( $it['kind'] ?? '', array( 'text', 'image', 'video', 'audio', 'code' ), true ) ? $it['kind'] : 'text',
		'premium'   => empty( $it['premium'] ) ? 0 : 1,
		'summary'   => (string) ( $it['summary'] ?? '' ),
		'prompt'    => $prompt,
		'output'    => (string) ( $it['output'] ?? '' ),
		// Pro prompt text is never searchable, so a search can never reveal it.
		'search'    => strtolower( $it['title'] . ' ' . $it['cat'] . ' ' . $sub . ' ' . ( $it['tool'] ?? '' ) . ' ' . ( $it['kind'] ?? '' ) . ' ' . ( $it['summary'] ?? '' ) ),
		'rnk'       => (int) $rank,
		'source'    => $source,
		'ver'       => VSAI_DATA_VERSION,
	);
}

/** Insert or update rows in batches with prepared statements. */
function vsai_store_upsert( $rows ) {
	global $wpdb;
	$t    = vsai_store_table();
	$cols = array( 'slug', 'title', 'cat', 'cat_slug', 'sub', 'sub_slug', 'tool', 'tool_slug', 'kind', 'premium', 'summary', 'prompt', 'output', 'search', 'rnk', 'source', 'ver' );
	$fmt  = '(' . implode( ',', array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s' ) ) . ')';
	$upd  = array();
	foreach ( $cols as $c ) {
		if ( 'slug' !== $c ) {
			$upd[] = "$c=VALUES($c)";
		}
	}
	foreach ( array_chunk( $rows, 200 ) as $chunk ) {
		$vals = array();
		$args = array();
		foreach ( $chunk as $r ) {
			$vals[] = $fmt;
			foreach ( $cols as $c ) {
				$args[] = $r[ $c ];
			}
		}
		$sql = "INSERT INTO $t (" . implode( ',', $cols ) . ') VALUES ' . implode( ',', $vals ) . ' ON DUPLICATE KEY UPDATE ' . implode( ',', $upd );
		$wpdb->query( $wpdb->prepare( $sql, $args ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery -- placeholders built above; table name is ours
	}
}

/** Read one shipped part (gzip after the PHP guard). */
function vsai_store_read_part( $file ) {
	$raw = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local plugin file
	$pos = false === $raw ? false : strpos( $raw, '__halt_compiler();' );
	if ( false === $pos ) {
		return array();
	}
	$json = gzdecode( substr( $raw, $pos + strlen( '__halt_compiler();' ) ) );
	if ( false === $json ) {
		return array();
	}
	$out = array();
	foreach ( explode( "\n", $json ) as $line ) {
		if ( '' !== $line ) {
			$it = json_decode( $line, true );
			if ( is_array( $it ) && ! empty( $it['id'] ) && ! empty( $it['title'] ) && ! empty( $it['cat'] ) ) {
				$out[] = $it;
			}
		}
	}
	return $out;
}

/**
 * Run one import step: step 0 creates the table and imports the built-in and
 * admin-added prompts; each following step imports one part (5,000 prompts).
 * Returns true when the import is complete.
 */
function vsai_store_step() {
	$st = vsai_store_state();
	if ( VSAI_DATA_VERSION === $st['version'] && VSAI_DATA_VERSION === $st['done'] ) {
		return true;
	}
	if ( VSAI_DATA_VERSION !== $st['version'] ) {
		$st = array_merge( $st, array( 'version' => VSAI_DATA_VERSION, 'step' => 0, 'error' => '' ) );
	}
	if ( get_transient( 'vsai_store_lock' ) ) {
		return false; // another request is importing
	}
	set_transient( 'vsai_store_lock', 1, 120 );
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 120 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors -- not allowed on some hosts
	}
	$parts = vsai_store_parts();
	$step  = (int) $st['step'];
	if ( 0 === $step ) {
		$st['ft'] = vsai_store_install();
		$rows     = array();
		foreach ( vsai_builtin( 'prompts' ) as $i => $it ) {
			$rows[] = vsai_store_row( $it, $i < 42 ? 1 : 2, 'builtin' );
		}
		foreach ( vsai_custom( 'prompts' ) as $it ) {
			$rows[] = vsai_store_row( $it, 0, 'custom' );
		}
		vsai_store_upsert( $rows );
	} elseif ( isset( $parts[ $step - 1 ] ) ) {
		$rows = array();
		foreach ( vsai_store_read_part( $parts[ $step - 1 ] ) as $it ) {
			$rows[] = vsai_store_row( $it, 3, 'gen' );
		}
		if ( ! $rows ) {
			$st['error'] = 'Could not read ' . basename( $parts[ $step - 1 ] );
		}
		vsai_store_upsert( $rows );
	}
	++$step;
	$finished = $step > count( $parts );
	if ( $finished ) {
		global $wpdb;
		// Remove generated rows from older data versions that no longer exist.
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . vsai_store_table() . ' WHERE source = %s AND ver <> %s', 'gen', VSAI_DATA_VERSION ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery -- table name is ours
		$st['done'] = VSAI_DATA_VERSION;
		vsai_store_flush_cache();
	}
	$st['step'] = $step;
	update_option( 'vsai_store', $st, false );
	delete_transient( 'vsai_store_lock' );
	return $finished;
}

/** Background import: one step per cron run, each run schedules the next. */
add_action(
	'vsai_store_import',
	function () {
		if ( ! vsai_store_step() ) {
			wp_schedule_single_event( time() + 5, 'vsai_store_import' );
		}
	}
);
add_action(
	'init',
	function () {
		$st = vsai_store_state();
		if ( VSAI_DATA_VERSION !== $st['done'] && ! wp_next_scheduled( 'vsai_store_import' ) && ! get_transient( 'vsai_store_lock' ) ) {
			wp_schedule_single_event( time() + 2, 'vsai_store_import' );
		}
	},
	30
);
/** Admins also move the import forward while they browse wp-admin, in case WP-Cron is slow. */
add_action(
	'admin_init',
	function () {
		if ( current_user_can( 'manage_options' ) && VSAI_DATA_VERSION !== vsai_store_state()['done'] && ! wp_doing_ajax() ) {
			vsai_store_step();
		}
	}
);
add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$st = vsai_store_state();
		if ( VSAI_DATA_VERSION === $st['done'] ) {
			return;
		}
		$total = count( vsai_store_parts() ) + 1;
		/* translators: 1: steps done, 2: total steps */
		echo '<div class="notice notice-info"><p><strong>' . esc_html__( 'VitalStack AI Hub:', 'vitalstack-ai-hub' ) . '</strong> ' . esc_html( sprintf( __( 'Importing the 100,000-prompt catalogue (step %1$d of %2$d). It runs in the background; refresh any admin page to speed it up. The hub keeps working meanwhile.', 'vitalstack-ai-hub' ), min( $total, (int) $st['step'] ), $total ) ) . ( $st['error'] ? ' ' . esc_html( $st['error'] ) : '' ) . '</p></div>';
	}
);

/** Admin-added prompts stay in sync with the table. */
add_action(
	'save_post_vsai_item',
	function ( $post_id ) {
		if ( ! vsai_store_ready() || wp_is_post_revision( $post_id ) ) {
			return;
		}
		global $wpdb;
		$t = vsai_store_table();
		$wpdb->query( $wpdb->prepare( "DELETE FROM $t WHERE slug = %s", 'c' . (int) $post_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery -- table name is ours
		if ( 'publish' === get_post_status( $post_id ) && 'prompts' === get_post_meta( $post_id, '_vsai_type', true ) ) {
			foreach ( vsai_custom( 'prompts' ) as $it ) {
				if ( 'c' . $post_id === $it['id'] ) {
					vsai_store_upsert( array( vsai_store_row( $it, 0, 'custom' ) ) );
				}
			}
		}
		vsai_store_flush_cache();
	},
	20
);
add_action(
	'before_delete_post',
	function ( $post_id ) {
		if ( 'vsai_item' === get_post_type( $post_id ) && vsai_store_ready() ) {
			global $wpdb;
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . vsai_store_table() . ' WHERE slug = %s', 'c' . (int) $post_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery
			vsai_store_flush_cache();
		}
	}
);

/* ── Queries ──────────────────────────────────────────────────────────── */

/** Cache generation number: bumping it invalidates every cached count and facet. */
function vsai_store_cache_gen() {
	return (int) get_option( 'vsai_store_gen', 1 );
}
function vsai_store_flush_cache() {
	update_option( 'vsai_store_gen', vsai_store_cache_gen() + 1, false );
}

/** WHERE clause + args for a filter state. */
function vsai_store_where( $f ) {
	global $wpdb;
	$w = array( 'rnk >= %d' ); // always true; keeps every query prepared
	$a = array( 0 );
	foreach ( array( 'cat' => 'cat_slug', 'sub' => 'sub_slug', 'tool' => 'tool_slug' ) as $k => $col ) {
		if ( ! empty( $f[ $k ] ) ) {
			$w[] = "$col = %s";
			$a[] = $f[ $k ];
		}
	}
	if ( ! empty( $f['kind'] ) ) {
		$w[] = 'kind = %s';
		$a[] = $f['kind'];
	}
	if ( 'free' === ( $f['price'] ?? '' ) ) {
		$w[] = 'premium = 0';
	} elseif ( 'pro' === ( $f['price'] ?? '' ) ) {
		$w[] = 'premium = 1';
	}
	$stop  = array( 'a', 'an', 'the', 'for', 'and', 'or', 'of', 'to', 'in', 'on', 'with', 'my', 'how', 'what', 'is', 'are', 'by', 'at', 'from', 'it', 'be', 'as', 'that', 'this' );
	$words = array_slice( array_values( array_diff( array_filter( preg_split( '/[^\p{L}\p{N}#+.]+/u', strtolower( (string) ( $f['q'] ?? '' ) ) ) ), $stop ) ), 0, 6 );
	if ( $words ) {
		$long = array_filter( $words, function ( $x ) {
			return mb_strlen( $x ) >= 3 && ! preg_match( '/[#+.]/', $x );
		} );
		if ( vsai_store_state()['ft'] && count( $long ) === count( $words ) ) {
			$w[] = 'MATCH(search) AGAINST (%s IN BOOLEAN MODE)';
			$a[] = implode( ' ', array_map( function ( $x ) {
				return '+' . $x . '*';
			}, $words ) );
		} else {
			foreach ( $words as $x ) {
				$w[] = 'search LIKE %s';
				$a[] = '%' . $wpdb->esc_like( $x ) . '%';
			}
		}
	}
	return array( implode( ' AND ', $w ), $a );
}

/** One page of prompts and the total count for a filter state. */
function vsai_store_query( $f, $page, $per ) {
	global $wpdb;
	$t = vsai_store_table();
	list( $where, $args ) = vsai_store_where( $f );
	// Counts of filter combinations are cached for a day; searches only for this request (keeps the options table small).
	$ck     = 'vsai_c_' . vsai_store_cache_gen() . '_' . md5( $where . '|' . implode( '|', $args ) );
	$search = '' !== trim( (string) ( $f['q'] ?? '' ) );
	$total  = $search ? wp_cache_get( $ck, 'vsai' ) : get_transient( $ck );
	if ( false === $total ) {
		$total = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $t WHERE $where", $args ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.NotPrepared -- where built with placeholders
		$search ? wp_cache_set( $ck, $total, 'vsai', HOUR_IN_SECONDS ) : set_transient( $ck, $total, DAY_IN_SECONDS );
	}
	$pages = max( 1, (int) ceil( $total / $per ) );
	$page  = min( max( 1, (int) $page ), $pages );
	$q     = "SELECT slug AS id, title, cat, sub, tool, kind, premium, summary, prompt, output FROM $t WHERE $where ORDER BY rnk ASC, id ASC LIMIT %d OFFSET %d";
	$rows  = $wpdb->get_results( $wpdb->prepare( $q, array_merge( $args, array( $per, ( $page - 1 ) * $per ) ) ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery
	foreach ( (array) $rows as &$r ) {
		$r['premium'] = (bool) (int) $r['premium'];
		$r['image']   = '';
	}
	return array( 'items' => (array) $rows, 'total' => $total, 'pages' => $pages, 'page' => $page );
}

/** Facet values with counts: categories, sub-topics (within a category), tools. Cached. */
function vsai_store_facet( $col, $cat = '' ) {
	global $wpdb;
	$t   = vsai_store_table();
	$ck  = 'vsai_f_' . vsai_store_cache_gen() . '_' . md5( $col . '|' . $cat );
	$out = get_transient( $ck );
	if ( false !== $out ) {
		return $out;
	}
	$map  = array( 'cat' => array( 'cat', 'cat_slug' ), 'sub' => array( 'sub', 'sub_slug' ), 'tool' => array( 'tool', 'tool_slug' ) );
	$c    = $map[ $col ];
	$sql  = "SELECT {$c[0]} AS name, {$c[1]} AS slug, COUNT(*) AS n FROM $t";
	$sql .= $cat ? $wpdb->prepare( ' WHERE cat_slug = %s', $cat ) : '';
	$sql .= " GROUP BY {$c[0]}, {$c[1]} ORDER BY " . ( 'cat' === $col ? 'MIN(rnk) ASC, n DESC' : 'n DESC, name ASC' ) . ' LIMIT 400';
	$out  = array();
	foreach ( (array) $wpdb->get_results( $sql, ARRAY_A ) as $r ) { // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery -- built from fixed columns and a prepared value
		if ( '' !== $r['slug'] ) {
			$out[] = array( 'name' => $r['name'], 'slug' => $r['slug'], 'n' => (int) $r['n'] );
		}
	}
	set_transient( $ck, $out, DAY_IN_SECONDS );
	return $out;
}

/** Total number of prompts (table when ready, else the built-in lists). */
function vsai_prompt_total() {
	if ( ! vsai_store_ready() ) {
		return count( vsai_items( 'prompts' ) );
	}
	$n = 0;
	foreach ( vsai_store_facet( 'cat' ) as $c ) {
		$n += $c['n'];
	}
	return $n;
}

/** Title suggestions for the search box (titles only, never prompt text). */
function vsai_store_suggest( $q ) {
	global $wpdb;
	$q = trim( strtolower( $q ) );
	if ( mb_strlen( $q ) < 2 ) {
		return array();
	}
	$ck  = 'vsai_s_' . vsai_store_cache_gen() . '_' . md5( $q );
	$out = wp_cache_get( $ck, 'vsai' );
	if ( false !== $out ) {
		return $out;
	}
	list( $where, $args ) = vsai_store_where( array( 'q' => $q ) );
	$rows = $wpdb->get_results( $wpdb->prepare( 'SELECT title, cat, cat_slug, premium FROM ' . vsai_store_table() . " WHERE $where ORDER BY rnk ASC, id ASC LIMIT 8", $args ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery
	$out  = array();
	foreach ( (array) $rows as $r ) {
		$out[] = array( 'title' => $r['title'], 'cat' => $r['cat'], 'pro' => (bool) (int) $r['premium'], 'url' => add_query_arg( 'q', rawurlencode( $r['title'] ), vsai_url( 'prompts' ) ) );
	}
	wp_cache_set( $ck, $out, 'vsai', HOUR_IN_SECONDS );
	return $out;
}

/** GET /wp-json/vsai/v1/suggest?q= — up to 8 matching titles for the search box. */
add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'vsai/v1',
			'/suggest',
			array(
				'methods'             => 'GET',
				'permission_callback' => '__return_true', // public, read-only titles
				'args'                => array(
					'q' => array( 'type' => 'string', 'required' => true, 'maxLength' => 80, 'sanitize_callback' => 'sanitize_text_field' ),
				),
				'callback'            => function ( WP_REST_Request $req ) {
					$key  = 'vsai_sg_' . substr( vsai_ip_key(), 0, 40 );
					$hits = (int) get_transient( $key );
					if ( $hits >= 600 ) {
						return new WP_Error( 'rate', __( 'Too many requests. Please try again later.', 'vitalstack-ai-hub' ), array( 'status' => 429 ) );
					}
					set_transient( $key, $hits + 1, HOUR_IN_SECONDS );
					$res = rest_ensure_response( vsai_store_ready() ? vsai_store_suggest( (string) $req['q'] ) : array() );
					$res->header( 'Cache-Control', 'public, max-age=600' );
					return $res;
				},
			)
		);
	}
);
