<?php
/**
 * One-click import of the bundled lessons and guides
 * (starter-content/content.json, built by tools/build_starter_content.py).
 *
 * Shown on Appearance → VitalStack Setup. Safe to run more than once:
 * anything whose slug already exists (in any status, including trash) is
 * skipped, so your edits are never overwritten.
 *
 * Three modes:
 *  - draft:    everything arrives as a draft to review and publish yourself;
 *  - schedule: published automatically, a few items per day, in course order;
 *  - publish:  everything goes live immediately.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The bundled items, or an empty array when the file is missing.
 *
 * @return array[]
 */
function vitalstack_starter_items() {
	static $items = null;
	if ( null === $items ) {
		$file  = get_template_directory() . '/starter-content/content.json';
		$data  = is_readable( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : null; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file
		$items = isset( $data['items'] ) && is_array( $data['items'] ) ? $data['items'] : array();
	}
	return $items;
}

/**
 * Item counts per learning path plus guides, for the summary table.
 *
 * @return array<string,int>
 */
function vitalstack_starter_counts() {
	$counts = array();
	foreach ( vitalstack_starter_items() as $item ) {
		$key            = 'post' === $item['type'] ? 'guides' : $item['subject'];
		$counts[ $key ] = isset( $counts[ $key ] ) ? $counts[ $key ] + 1 : 1;
	}
	return $counts;
}

/**
 * The existing post for an item's slug, in any status.
 */
function vitalstack_starter_existing( $item ) {
	$found = get_posts(
		array(
			'name'             => $item['slug'],
			'post_type'        => $item['type'],
			'post_status'      => array( 'publish', 'future', 'draft', 'pending', 'private', 'trash' ),
			'numberposts'      => 1,
			'suppress_filters' => true,
		)
	);
	return $found ? $found[0] : null;
}

/**
 * Term id for a slug, creating the term when it doesn't exist yet.
 */
function vitalstack_starter_term( $taxonomy, $slug, $name ) {
	$term = get_term_by( 'slug', $slug, $taxonomy );
	if ( $term ) {
		return (int) $term->term_id;
	}
	$made = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
	return is_wp_error( $made ) ? 0 : (int) $made['term_id'];
}

/**
 * Imports every missing item.
 *
 * @param string $mode    draft | schedule | publish.
 * @param int    $per_day Items per day in schedule mode.
 * @param bool   $notify  Email subscribers when scheduled items go live.
 * @return string[] Report lines.
 */
function vitalstack_import_starter_content( $mode = 'draft', $per_day = 2, $notify = false ) {
	$items = vitalstack_starter_items();
	if ( ! $items ) {
		return array( __( 'The starter content file is missing from the theme (starter-content/content.json).', 'vitalstack' ) );
	}
	if ( function_exists( 'set_time_limit' ) ) {
		set_time_limit( 300 ); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged -- a long one-off admin import
	}
	wp_defer_term_counting( true );

	$per_day   = max( 1, min( 20, (int) $per_day ) );
	$created   = array(
		'tutorials' => 0,
		'post'      => 0,
	);
	$skipped   = 0;
	$scheduled = 0;
	$last_date = '';
	// Scheduled items go out at 09:00 site time, the first ones tomorrow.
	$start = new DateTime( 'tomorrow 09:00', wp_timezone() );

	foreach ( $items as $item ) {
		if ( vitalstack_starter_existing( $item ) ) {
			++$skipped;
			continue;
		}

		$status = 'publish' === $mode ? 'publish' : 'draft';
		$date   = '';
		if ( 'schedule' === $mode ) {
			$slot = new DateTime( $start->format( 'Y-m-d H:i:s' ), wp_timezone() );
			$slot->modify( '+' . intdiv( $scheduled, $per_day ) . ' days' );
			$slot->modify( '+' . ( ( $scheduled % $per_day ) * 4 ) . ' hours' );
			$date      = $slot->format( 'Y-m-d H:i:s' );
			$last_date = $date;
			$status    = 'future';
			++$scheduled;
		}

		$postarr = array(
			'post_type'    => $item['type'],
			'post_status'  => $status,
			'post_title'   => $item['title'],
			'post_name'    => $item['slug'],
			'post_content' => $item['content'],
			'post_excerpt' => $item['excerpt'],
			'post_author'  => get_current_user_id(),
			'menu_order'   => 'tutorials' === $item['type'] ? (int) $item['lesson'] : 0,
			'meta_input'   => array( '_vs_starter' => 1 ),
		);
		if ( $date ) {
			$postarr['post_date']     = $date;
			$postarr['post_date_gmt'] = get_gmt_from_date( $date );
		}
		// Bulk-imported items never email subscribers, except scheduled ones when asked:
		// those go out one by one, like normal new posts.
		if ( ! ( 'schedule' === $mode && $notify ) ) {
			$postarr['meta_input']['_vs_skip_notify'] = '1';
		}

		// wp_insert_post() expects slashed data; without this, backslashes in code examples would be lost.
		$post_id = wp_insert_post( wp_slash( $postarr ), true );
		if ( is_wp_error( $post_id ) ) {
			continue;
		}

		if ( 'tutorials' === $item['type'] ) {
			$subject_names = array(
				'html'       => 'HTML',
				'css'        => 'CSS',
				'javascript' => 'JavaScript',
				'sql'        => 'SQL',
				'python'     => 'Python',
			);
			$path_name = isset( $subject_names[ $item['subject'] ] ) ? $subject_names[ $item['subject'] ] : ucfirst( $item['subject'] );
			wp_set_object_terms( $post_id, vitalstack_starter_term( 'tutorial_category', $item['subject'], $path_name ), 'tutorial_category' );
			wp_set_object_terms( $post_id, vitalstack_starter_term( 'tutorial_difficulty', $item['difficulty'], ucfirst( $item['difficulty'] ) ), 'tutorial_difficulty' );
		} else {
			$category_names = wp_list_pluck( vitalstack_setup_categories(), 'name', 'slug' );
			$name           = isset( $category_names[ $item['category'] ] ) ? $category_names[ $item['category'] ] : ucwords( str_replace( '-', ' ', $item['category'] ) );
			wp_set_post_categories( $post_id, array( vitalstack_starter_term( 'category', $item['category'], $name ) ) );
			if ( ! empty( $item['tags'] ) ) {
				wp_set_post_tags( $post_id, $item['tags'] );
			}
		}
		++$created[ $item['type'] ];
	}

	wp_defer_term_counting( false );

	$report   = array();
	$report[] = sprintf(
		/* translators: 1: number of lessons, 2: number of guides */
		__( 'Imported %1$d lessons and %2$d guides.', 'vitalstack' ),
		$created['tutorials'],
		$created['post']
	);
	if ( $skipped ) {
		/* translators: %d: number of items */
		$report[] = sprintf( __( 'Skipped %d items that already exist (nothing was overwritten).', 'vitalstack' ), $skipped );
	}
	if ( 'draft' === $mode && array_sum( $created ) ) {
		$report[] = __( 'Everything is a draft: open Tutorials and Posts, review each item and click Publish.', 'vitalstack' );
	} elseif ( 'schedule' === $mode && $scheduled ) {
		$report[] = sprintf(
			/* translators: 1: items per day, 2: date of the last item */
			__( 'Scheduled %1$d per day, starting tomorrow at 09:00. The last item goes live on %2$s. Review upcoming items under Tutorials/Posts → Scheduled.', 'vitalstack' ),
			$per_day,
			mysql2date( get_option( 'date_format' ), $last_date )
		);
	} elseif ( 'publish' === $mode && array_sum( $created ) ) {
		$report[] = __( 'Everything is live. Subscribers were not emailed about the bulk import.', 'vitalstack' );
	}
	return $report;
}

/* ── Admin UI (rendered inside the VitalStack Setup page) ───────────────── */

function vitalstack_starter_section() {
	$counts = vitalstack_starter_counts();
	if ( ! $counts ) {
		return;
	}
	$names    = array(
		'html'       => 'HTML',
		'css'        => 'CSS',
		'javascript' => 'JavaScript',
		'sql'        => 'SQL',
		'python'     => 'Python',
		'guides'     => __( 'Guides (AI, AI Tools, AI Career, Programming)', 'vitalstack' ),
	);
	$imported = (int) ( new WP_Query(
		array(
			'post_type'      => array( 'tutorials', 'post' ),
			'post_status'    => 'any',
			'meta_key'       => '_vs_starter', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- admin-only count
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	) )->found_posts;
	$total    = array_sum( $counts );
	?>
	<h2 style="margin-top:40px"><?php esc_html_e( 'Starter content: lessons and guides', 'vitalstack' ); ?></h2>
	<p style="font-size:14px;max-width:760px">
		<?php
		printf(
			/* translators: %d: number of items */
			esc_html__( '%d ready-made pieces of content, with code examples, exercises and summaries. Run "Create starter structure" first so the learning paths and categories exist.', 'vitalstack' ),
			(int) $total
		);
		?>
	</p>
	<table class="widefat striped" style="max-width:760px;margin:12px 0">
		<tbody>
			<?php foreach ( $counts as $key => $count ) : ?>
				<tr><td><strong><?php echo esc_html( isset( $names[ $key ] ) ? $names[ $key ] : ucfirst( $key ) ); ?></strong></td><td><?php echo esc_html( $count ); ?></td></tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php if ( $imported ) : ?>
		<p><strong>
			<?php
			/* translators: 1: imported count, 2: total */
			printf( esc_html__( 'Already on this site: %1$d of %2$d. Importing again only adds what is missing.', 'vitalstack' ), (int) $imported, (int) $total );
			?>
		</strong></p>
	<?php endif; ?>
	<div style="max-width:760px;background:#fff8e5;border-left:4px solid #dba617;padding:10px 14px;margin:12px 0">
		<?php esc_html_e( 'For AdSense and long-term search traffic, the content should be checked and made yours: read each piece, test the examples, and add your own notes or screenshots. "Drafts" or "a few per day" give you time to do that, and a steady publishing rhythm looks natural to readers and search engines.', 'vitalstack' ); ?>
	</div>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="vitalstack_import_starter">
		<?php wp_nonce_field( 'vitalstack_import_starter' ); ?>
		<fieldset style="margin:8px 0 12px">
			<p><label><input type="radio" name="mode" value="draft" checked> <strong><?php esc_html_e( 'Import as drafts', 'vitalstack' ); ?></strong> <?php esc_html_e( '(recommended): review, then publish one by one', 'vitalstack' ); ?></label></p>
			<p><label><input type="radio" name="mode" value="schedule"> <strong><?php esc_html_e( 'Publish automatically, a few per day', 'vitalstack' ); ?></strong></label>
				<select name="per_day" aria-label="<?php esc_attr_e( 'Items per day', 'vitalstack' ); ?>">
					<?php foreach ( array( 1, 2, 3, 4, 5 ) as $n ) : ?>
						<option value="<?php echo esc_attr( $n ); ?>" <?php selected( 2, $n ); ?>><?php echo esc_html( sprintf( /* translators: %d: number */ _n( '%d per day', '%d per day', $n, 'vitalstack' ), $n ) ); ?></option>
					<?php endforeach; ?>
				</select>
				<?php esc_html_e( 'in course order (HTML first), with guides in between', 'vitalstack' ); ?>
			</p>
			<p style="margin-left:24px"><label><input type="checkbox" name="notify" value="1"> <?php esc_html_e( 'Email subscribers as each scheduled item goes live', 'vitalstack' ); ?></label></p>
			<p><label><input type="radio" name="mode" value="publish"> <strong><?php esc_html_e( 'Publish everything now', 'vitalstack' ); ?></strong> <?php esc_html_e( '(subscribers are not emailed)', 'vitalstack' ); ?></label></p>
		</fieldset>
		<?php submit_button( __( 'Import starter content', 'vitalstack' ), 'secondary large', 'vs-import-starter', false ); ?>
	</form>
	<?php
}

function vitalstack_handle_import_starter() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'vitalstack' ) );
	}
	check_admin_referer( 'vitalstack_import_starter' );
	$mode    = isset( $_POST['mode'] ) ? sanitize_key( wp_unslash( $_POST['mode'] ) ) : 'draft';
	$mode    = in_array( $mode, array( 'draft', 'schedule', 'publish' ), true ) ? $mode : 'draft';
	$per_day = isset( $_POST['per_day'] ) ? absint( $_POST['per_day'] ) : 2;
	$report  = vitalstack_import_starter_content( $mode, $per_day, ! empty( $_POST['notify'] ) );
	set_transient( 'vitalstack_setup_report_' . get_current_user_id(), $report, 10 * MINUTE_IN_SECONDS );
	wp_safe_redirect( admin_url( 'themes.php?page=vitalstack-setup' ) );
	exit;
}
add_action( 'admin_post_vitalstack_import_starter', 'vitalstack_handle_import_starter' );
