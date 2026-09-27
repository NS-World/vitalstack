<?php
/**
 * Search results.
 *
 * @package VitalStack
 */

get_header();
global $wp_query;
?>

<header class="page-hero">
	<div class="container">
		<p class="eyebrow"><?php esc_html_e( 'Search', 'vitalstack' ); ?></p>
		<h1 class="page-title">
			<?php
			/* translators: %s: search query */
			printf( esc_html__( 'Results for “%s”', 'vitalstack' ), esc_html( get_search_query() ) );
			?>
		</h1>
		<p class="page-desc">
			<?php
			/* translators: %d: number of results */
			echo esc_html( sprintf( _n( '%d result', '%d results', (int) $wp_query->found_posts, 'vitalstack' ), (int) $wp_query->found_posts ) );
			?>
		</p>
		<div class="page-search"><?php get_search_form(); ?></div>
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
