<?php
/**
 * Learning paths: top-level "tutorial_category" terms, with lessons ordered
 * by the tutorial's "Order" field (menu_order), then publish date.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All learning paths that contain at least one tutorial.
 *
 * @return WP_Term[]
 */
function vitalstack_learning_paths() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'tutorial_category',
			'parent'     => 0,
			'hide_empty' => false,
		)
	);
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	$paths = array_values(
		array_filter(
			$terms,
			function ( $t ) {
				return count( vitalstack_path_lessons( $t ) ) > 0;
			}
		)
	);
	// Biggest paths first; they are the best starting points.
	usort(
		$paths,
		function ( $a, $b ) {
			return count( vitalstack_path_lessons( $b ) ) - count( vitalstack_path_lessons( $a ) ) ?: strcmp( $a->name, $b->name );
		}
	);
	return $paths;
}

/**
 * Lessons in a path (including tutorials filed under its child topics).
 *
 * @return int[] Post IDs in lesson order.
 */
function vitalstack_path_lessons( $term ) {
	static $cache = array();
	if ( isset( $cache[ $term->term_id ] ) ) {
		return $cache[ $term->term_id ];
	}
	$cache[ $term->term_id ] = get_posts(
		array(
			'post_type'        => 'tutorials',
			'posts_per_page'   => 100,
			'fields'           => 'ids',
			'orderby'          => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
			'tax_query'        => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'taxonomy'         => 'tutorial_category',
					'terms'            => $term->term_id,
					'include_children' => true,
				),
			),
			'suppress_filters' => false,
		)
	);
	return $cache[ $term->term_id ];
}

/**
 * The learning path (top-level term) a tutorial belongs to.
 */
function vitalstack_tutorial_path( $post_id = null ) {
	$terms = get_the_terms( $post_id ?: get_the_ID(), 'tutorial_category' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return null;
	}
	foreach ( $terms as $term ) {
		$top = $term;
		while ( $top->parent ) {
			$parent = get_term( $top->parent, 'tutorial_category' );
			if ( ! $parent || is_wp_error( $parent ) ) {
				break;
			}
			$top = $parent;
		}
		return $top;
	}
	return null;
}

/**
 * "Mark lesson as complete" toggle. Progress is stored in the browser and,
 * for signed-in readers, synced to their account.
 */
function vitalstack_lesson_complete_button() {
	$path = vitalstack_tutorial_path();
	if ( ! $path ) {
		return;
	}
	?>
	<div class="lesson-complete">
		<button type="button" class="btn btn-soft" data-complete="<?php echo (int) get_the_ID(); ?>" data-path="<?php echo esc_attr( $path->slug ); ?>" aria-pressed="false">
			<?php echo vitalstack_icon( 'check', 18 ); // phpcs:ignore ?>
			<span class="when-off"><?php esc_html_e( 'Mark this lesson as complete', 'vitalstack' ); ?></span>
			<span class="when-on"><?php esc_html_e( 'Lesson completed', 'vitalstack' ); ?></span>
		</button>
		<p class="lesson-complete-note">
			<?php
			if ( is_user_logged_in() ) {
				esc_html_e( 'Progress is saved to your account.', 'vitalstack' );
			} else {
				printf(
					/* translators: %s: sign-up link */
					esc_html__( 'Progress is saved in this browser. %s to keep it on every device.', 'vitalstack' ),
					'<a href="' . esc_url( vitalstack_account_url( array( 'tab' => 'register', 'redirect_to' => get_permalink() ) ) ) . '">' . esc_html__( 'Create a free account', 'vitalstack' ) . '</a>'
				);
			}
			?>
		</p>
	</div>
	<?php
}

/**
 * A tutorial title without the "(2026 Guide)"-style suffix, for compact lists.
 */
function vitalstack_short_title( $post_id ) {
	$title = html_entity_decode( get_the_title( $post_id ), ENT_QUOTES );
	$title = preg_replace( '/\s*\((?:[^)]*\b(?:guide|20\d\d)\b[^)]*)\)\s*/i', ' ', $title );
	$title = preg_replace( '/\s*:\s.*$/', '', $title );
	return trim( $title );
}

/**
 * Card grid for one path (used on the tutorials hub and path archive).
 */
function vitalstack_path_block( $term, $heading_level = 2 ) {
	$lessons = vitalstack_path_lessons( $term );
	$style   = vitalstack_term_style( $term, 'tutorials' );
	$tag     = 'h' . (int) $heading_level;
	?>
	<section class="path-block" data-path="<?php echo esc_attr( $term->slug ); ?>" data-total="<?php echo count( $lessons ); ?>">
		<header class="path-block-head">
			<span class="path-icon tone-<?php echo esc_attr( $style[1] ); ?>"><?php echo vitalstack_icon( $style[0], 22 ); // phpcs:ignore ?></span>
			<div>
				<<?php echo $tag; // phpcs:ignore ?> class="path-block-title"><a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a></<?php echo $tag; // phpcs:ignore ?>>
				<p class="path-block-meta">
					<?php
					/* translators: %d: number of lessons */
					echo esc_html( sprintf( _n( '%d lesson', '%d lessons', count( $lessons ), 'vitalstack' ), count( $lessons ) ) );
					?>
					<span class="path-block-done" hidden></span>
				</p>
			</div>
		</header>
		<?php if ( $term->description ) : ?>
			<p class="path-block-desc"><?php echo esc_html( $term->description ); ?></p>
		<?php endif; ?>
		<div class="grid grid-3">
			<?php
			foreach ( $lessons as $i => $lesson_id ) {
				/* translators: %d: lesson number */
				vitalstack_card( $lesson_id, array( 'lesson' => sprintf( __( 'Lesson %d', 'vitalstack' ), $i + 1 ) ) );
			}
			?>
		</div>
	</section>
	<?php
}
