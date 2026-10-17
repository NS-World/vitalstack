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
		foreach ( array( 'pattern', 'companies', 'level' ) as $vs_key ) {
			if ( ! empty( $item['meta'][ $vs_key ] ) ) {
				$postarr['meta_input'][ '_vs_' . $vs_key ] = sanitize_text_field( $item['meta'][ $vs_key ] );
			}
		}
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
			$subject_names = wp_list_pluck( vitalstack_setup_paths(), 'name', 'slug' );
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
	$names           = wp_list_pluck( vitalstack_setup_paths(), 'name', 'slug' );
	$names['guides'] = __( 'Guides (AI, AI Tools, AI Career, Programming)', 'vitalstack' );
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
	$status   = vitalstack_starter_status_counts();
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
		<p>
			<?php
			/* translators: 1: published, 2: scheduled, 3: drafts */
			printf( esc_html__( 'Published: %1$d · Scheduled: %2$d · Drafts: %3$d', 'vitalstack' ), (int) $status['publish'], (int) $status['future'], (int) $status['draft'] );
			?>
		</p>
	<?php endif; ?>
	<?php if ( $status['draft'] + $status['future'] ) : ?>
		<div style="max-width:760px;background:#e7f5ee;border-left:4px solid #0a7d4f;padding:12px 16px;margin:16px 0">
			<h3 style="margin:0 0 6px"><?php esc_html_e( 'Publish the imported items', 'vitalstack' ); ?></h3>
			<p style="margin:0 0 8px"><?php esc_html_e( 'Drafts and scheduled items are not visible on the site. A subject (HTML, CSS…) appears in the top bar and on the homepage only after its first lesson is published.', 'vitalstack' ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="vitalstack_publish_starter">
				<?php wp_nonce_field( 'vitalstack_publish_starter' ); ?>
				<p><label><input type="radio" name="mode" value="schedule" checked> <strong><?php esc_html_e( 'Publish a few per day', 'vitalstack' ); ?></strong></label>
					<select name="per_day" aria-label="<?php esc_attr_e( 'Items per day', 'vitalstack' ); ?>">
						<?php foreach ( array( 1, 2, 3, 4, 5, 10 ) as $n ) : ?>
							<option value="<?php echo esc_attr( $n ); ?>" <?php selected( 3, $n ); ?>><?php echo esc_html( sprintf( /* translators: %d: number */ _n( '%d per day', '%d per day', $n, 'vitalstack' ), $n ) ); ?></option>
						<?php endforeach; ?>
					</select>
					<?php esc_html_e( 'starting now, in course order', 'vitalstack' ); ?>
				</p>
				<p><label><input type="radio" name="mode" value="publish"> <strong><?php esc_html_e( 'Publish all now', 'vitalstack' ); ?></strong></label></p>
				<p><label><input type="checkbox" name="notify" value="1"> <?php esc_html_e( 'Email subscribers about each item as it goes live (only for "a few per day")', 'vitalstack' ); ?></label></p>
				<?php submit_button( __( 'Publish imported items', 'vitalstack' ), 'primary', 'vs-publish-starter', false ); ?>
			</form>
		</div>
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

/**
 * How many imported items are published, scheduled and still drafts.
 *
 * @return array{publish:int,future:int,draft:int}
 */
function vitalstack_starter_status_counts() {
	$out = array(
		'publish' => 0,
		'future'  => 0,
		'draft'   => 0,
	);
	foreach ( array_keys( $out ) as $status ) {
		$out[ $status ] = (int) ( new WP_Query(
			array(
				'post_type'      => array( 'tutorials', 'post' ),
				'post_status'    => 'draft' === $status ? array( 'draft', 'pending' ) : $status,
				'meta_key'       => '_vs_starter', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- admin-only count
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		) )->found_posts;
	}
	return $out;
}

/**
 * Publishes imported drafts (and re-plans scheduled items), in course order.
 *
 * @param string $mode    publish | schedule.
 * @param int    $per_day Items per day in schedule mode; the first ones go live now.
 * @param bool   $notify  Email subscribers as scheduled items go live.
 * @return string[] Report lines.
 */
function vitalstack_publish_starter_items( $mode = 'schedule', $per_day = 3, $notify = false ) {
	$order = array();
	foreach ( vitalstack_starter_items() as $i => $item ) {
		$order[ $item['type'] . ':' . $item['slug'] ] = $i;
	}
	$posts = get_posts(
		array(
			'post_type'        => array( 'tutorials', 'post' ),
			'post_status'      => array( 'draft', 'pending', 'future' ),
			'meta_key'         => '_vs_starter', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- one-off admin action
			'numberposts'      => -1,
			'suppress_filters' => true,
		)
	);
	usort(
		$posts,
		function ( $a, $b ) use ( $order ) {
			$ka = $a->post_type . ':' . $a->post_name;
			$kb = $b->post_type . ':' . $b->post_name;
			return ( isset( $order[ $ka ] ) ? $order[ $ka ] : PHP_INT_MAX ) <=> ( isset( $order[ $kb ] ) ? $order[ $kb ] : PHP_INT_MAX );
		}
	);
	if ( ! $posts ) {
		return array( __( 'There are no imported drafts or scheduled items left to publish.', 'vitalstack' ) );
	}
	if ( function_exists( 'set_time_limit' ) ) {
		set_time_limit( 300 ); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged -- a long one-off admin action
	}

	$per_day = max( 1, min( 20, (int) $per_day ) );
	$now     = new DateTime( 'now', wp_timezone() );
	$n       = 0;
	$last    = '';
	foreach ( $posts as $post ) {
		// Drafts the author edited by hand are published too; the choice of what to import was theirs.
		if ( 'schedule' === $mode && $notify ) {
			delete_post_meta( $post->ID, '_vs_skip_notify' );
		} else {
			update_post_meta( $post->ID, '_vs_skip_notify', '1' );
		}
		if ( 'schedule' === $mode && $n >= $per_day ) {
			$slot = clone $now;
			$slot->modify( '+' . intdiv( $n, $per_day ) . ' days' );
			$slot->setTime( 9 + ( $n % $per_day ) * max( 1, intdiv( 12, $per_day ) ), 0 );
			$date   = $slot->format( 'Y-m-d H:i:s' );
			$status = 'future';
			$last   = $date;
		} else {
			// Spread "now" items a minute apart so the newest-first lists keep course order.
			$slot = clone $now;
			$slot->modify( '-' . ( count( $posts ) - $n ) . ' minutes' );
			$date   = $slot->format( 'Y-m-d H:i:s' );
			$status = 'publish';
		}
		wp_update_post(
			array(
				'ID'            => $post->ID,
				'post_status'   => $status,
				'post_date'     => $date,
				'post_date_gmt' => get_gmt_from_date( $date ),
				'edit_date'     => true,
			)
		);
		++$n;
	}

	if ( 'publish' === $mode ) {
		/* translators: %d: number of items */
		return array( sprintf( __( 'Published %d items. The subjects now appear in the top bar and on the homepage.', 'vitalstack' ), $n ) );
	}
	$report = array(
		/* translators: %d: number of items */
		sprintf( __( 'Published the first %d items now.', 'vitalstack' ), min( $n, $per_day ) ),
	);
	if ( $last ) {
		$report[] = sprintf(
			/* translators: 1: items per day, 2: date */
			__( 'The rest go live %1$d per day from tomorrow; the last on %2$s. Scheduled publishing needs WP-Cron (see THEME-V3-SETUP.md §3).', 'vitalstack' ),
			$per_day,
			mysql2date( get_option( 'date_format' ), $last )
		);
	}
	return $report;
}

function vitalstack_handle_publish_starter() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'vitalstack' ) );
	}
	check_admin_referer( 'vitalstack_publish_starter' );
	$mode   = isset( $_POST['mode'] ) && 'publish' === $_POST['mode'] ? 'publish' : 'schedule';
	$report = vitalstack_publish_starter_items( $mode, isset( $_POST['per_day'] ) ? absint( $_POST['per_day'] ) : 3, ! empty( $_POST['notify'] ) );
	set_transient( 'vitalstack_setup_report_' . get_current_user_id(), $report, 10 * MINUTE_IN_SECONDS );
	wp_safe_redirect( admin_url( 'themes.php?page=vitalstack-setup' ) );
	exit;
}
add_action( 'admin_post_vitalstack_publish_starter', 'vitalstack_handle_publish_starter' );

/**
 * Reminder on the dashboard and the Tutorials/Posts lists while imported
 * items are drafts and nothing of them is live yet.
 */
function vitalstack_starter_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'edit-tutorials', 'edit-post' ), true ) ) {
		return;
	}
	$status = vitalstack_starter_status_counts();
	if ( ! $status['draft'] ) {
		return;
	}
	echo '<div class="notice notice-warning"><p><strong>VitalStack:</strong> ' . esc_html(
		sprintf(
			/* translators: %d: number of drafts */
			_n( '%d imported lesson or guide is still a draft, so it is not visible on the site.', '%d imported lessons and guides are still drafts, so they are not visible on the site.', $status['draft'], 'vitalstack' ),
			$status['draft']
		)
	) . ' <a class="button button-primary" style="margin-left:8px" href="' . esc_url( admin_url( 'themes.php?page=vitalstack-setup' ) ) . '">' . esc_html__( 'Publish them', 'vitalstack' ) . '</a></p></div>';
}
add_action( 'admin_notices', 'vitalstack_starter_notice' );
