<?php
/**
 * Archives: categories, tags, authors, news, dates.
 *
 * @package VitalStack
 */

get_header();

$vs_term = get_queried_object();
$vs_desc = get_the_archive_description();
?>

<header class="page-hero">
	<div class="container">
		<?php if ( is_author() ) : ?>
			<div class="author-hero">
				<?php echo vitalstack_monogram( get_queried_object_id(), 'xl' ); // phpcs:ignore ?>
				<div>
					<p class="eyebrow"><?php esc_html_e( 'Author', 'vitalstack' ); ?></p>
					<h1 class="page-title"><?php echo esc_html( get_the_author_meta( 'display_name', get_queried_object_id() ) ); ?></h1>
					<?php if ( get_the_author_meta( 'description', get_queried_object_id() ) ) : ?>
						<p class="page-desc"><?php echo esc_html( get_the_author_meta( 'description', get_queried_object_id() ) ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		<?php else : ?>
			<p class="eyebrow">
				<?php
				if ( is_category() ) {
					esc_html_e( 'Topic', 'vitalstack' );
				} elseif ( is_tag() ) {
					esc_html_e( 'Tag', 'vitalstack' );
				} elseif ( is_post_type_archive( 'news' ) || is_tax( 'news_category' ) ) {
					esc_html_e( 'News', 'vitalstack' );
				} else {
					esc_html_e( 'Archive', 'vitalstack' );
				}
				?>
			</p>
			<h1 class="page-title"><?php echo esc_html( wp_strip_all_tags( get_the_archive_title() ) ); ?></h1>
			<?php if ( $vs_desc ) : ?>
				<div class="page-desc"><?php echo wp_kses_post( $vs_desc ); ?></div>
			<?php endif; ?>

			<?php
			// Sub-topic chips for parent categories.
			if ( is_category() && $vs_term instanceof WP_Term ) :
				$vs_children = get_categories(
					array(
						'parent'     => $vs_term->parent ? $vs_term->parent : $vs_term->term_id,
						'hide_empty' => true,
					)
				);
				if ( $vs_children ) :
					?>
					<div class="chips">
						<?php if ( $vs_term->parent ) : ?>
							<a class="chip" href="<?php echo esc_url( get_category_link( $vs_term->parent ) ); ?>"><?php esc_html_e( 'All', 'vitalstack' ); ?></a>
						<?php else : ?>
							<span class="chip is-active"><?php esc_html_e( 'All', 'vitalstack' ); ?></span>
						<?php endif; ?>
						<?php foreach ( $vs_children as $vs_child ) : ?>
							<?php if ( $vs_child->term_id === $vs_term->term_id ) : ?>
								<span class="chip is-active"><?php echo esc_html( $vs_child->name ); ?></span>
							<?php else : ?>
								<a class="chip" href="<?php echo esc_url( get_category_link( $vs_child ) ); ?>"><?php echo esc_html( $vs_child->name ); ?></a>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<?php
				endif;
			endif;
			?>
		<?php endif; ?>
	</div>
</header>

<section class="section section-tight">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="grid grid-3">
				<?php
				while ( have_posts() ) :
					the_post();
					vitalstack_card();
				endwhile;
				?>
			</div>
			<?php vitalstack_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/none' ); ?>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
