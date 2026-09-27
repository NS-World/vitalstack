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
 * Lesson list for the tutorial sidebar.
 */
function vitalstack_path_nav( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$path    = vitalstack_tutorial_path( $post_id );
	if ( ! $path ) {
		return;
	}
	$lessons = vitalstack_path_lessons( $path );
	$index   = array_search( $post_id, $lessons, true );
	?>
	<nav class="path-nav" aria-labelledby="path-nav-title" data-path="<?php echo esc_attr( $path->slug ); ?>">
		<p class="path-nav-kicker"><?php esc_html_e( 'Learning path', 'vitalstack' ); ?></p>
		<p id="path-nav-title" class="path-nav-title"><a href="<?php echo esc_url( get_term_link( $path ) ); ?>"><?php echo esc_html( $path->name ); ?></a></p>
		<div class="path-progress" aria-hidden="true"><span class="path-progress-bar"></span></div>
		<p class="path-progress-text" data-total="<?php echo count( $lessons ); ?>">
			<?php
			/* translators: 1: lesson number, 2: total lessons */
			echo esc_html( sprintf( __( 'Lesson %1$d of %2$d', 'vitalstack' ), (int) $index + 1, count( $lessons ) ) );
			?>
		</p>
		<ol class="path-lessons">
			<?php foreach ( $lessons as $i => $lesson_id ) : ?>
				<li class="<?php echo $lesson_id === $post_id ? 'is-current' : ''; ?>" data-lesson="<?php echo (int) $lesson_id; ?>">
					<a href="<?php echo esc_url( get_permalink( $lesson_id ) ); ?>" <?php echo $lesson_id === $post_id ? 'aria-current="page"' : ''; ?>>
						<span class="lesson-num"><span class="lesson-n"><?php echo (int) $i + 1; ?></span><?php echo vitalstack_icon( 'check', 14 ); // phpcs:ignore ?></span>
						<span class="lesson-title"><?php echo esc_html( vitalstack_short_title( $lesson_id ) ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ol>
	</nav>
	<?php
}

/**
 * Previous / next lesson links plus a "mark complete" control.
 */
function vitalstack_lesson_pager( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$path    = vitalstack_tutorial_path( $post_id );
	if ( ! $path ) {
		return;
	}
	$lessons = vitalstack_path_lessons( $path );
	$i       = array_search( $post_id, $lessons, true );
	$prev    = ( false !== $i && $i > 0 ) ? $lessons[ $i - 1 ] : 0;
	$next    = ( false !== $i && $i < count( $lessons ) - 1 ) ? $lessons[ $i + 1 ] : 0;
	?>
	<div class="lesson-complete">
		<button type="button" class="btn btn-soft" data-complete="<?php echo (int) $post_id; ?>" data-path="<?php echo esc_attr( $path->slug ); ?>" aria-pressed="false">
			<?php echo vitalstack_icon( 'check', 18 ); // phpcs:ignore ?>
			<span class="when-off"><?php esc_html_e( 'Mark lesson as complete', 'vitalstack' ); ?></span>
			<span class="when-on"><?php esc_html_e( 'Completed', 'vitalstack' ); ?></span>
		</button>
		<p class="lesson-complete-note"><?php esc_html_e( 'Progress is saved in this browser only.', 'vitalstack' ); ?></p>
	</div>
	<nav class="lesson-pager" aria-label="<?php esc_attr_e( 'Lesson navigation', 'vitalstack' ); ?>">
		<?php if ( $prev ) : ?>
			<a class="lesson-pager-link is-prev" href="<?php echo esc_url( get_permalink( $prev ) ); ?>">
				<span class="lesson-pager-label"><?php echo vitalstack_icon( 'arrow-l', 16 ); // phpcs:ignore ?> <?php esc_html_e( 'Previous lesson', 'vitalstack' ); ?></span>
				<span class="lesson-pager-title"><?php echo esc_html( vitalstack_short_title( $prev ) ); ?></span>
			</a>
		<?php else : ?>
			<span></span>
		<?php endif; ?>
		<?php if ( $next ) : ?>
			<a class="lesson-pager-link is-next" href="<?php echo esc_url( get_permalink( $next ) ); ?>">
				<span class="lesson-pager-label"><?php esc_html_e( 'Next lesson', 'vitalstack' ); ?> <?php echo vitalstack_icon( 'arrow', 16 ); // phpcs:ignore ?></span>
				<span class="lesson-pager-title"><?php echo esc_html( vitalstack_short_title( $next ) ); ?></span>
			</a>
		<?php else : ?>
			<a class="lesson-pager-link is-next is-done" href="<?php echo esc_url( vitalstack_tutorials_url() ); ?>">
				<span class="lesson-pager-label"><?php esc_html_e( 'Path complete', 'vitalstack' ); ?> 🎉</span>
				<span class="lesson-pager-title"><?php esc_html_e( 'Choose your next learning path', 'vitalstack' ); ?></span>
			</a>
		<?php endif; ?>
	</nav>
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
