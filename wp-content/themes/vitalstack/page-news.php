<?php
/**
 * Template Name: News Page
 *
 * @package VitalStack
 */

get_header();

$vs_paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$vs_query = new WP_Query(
	array(
		'post_type'      => 'news',
		'posts_per_page' => 12,
		'paged'          => $vs_paged,
	)
);
?>

<header class="page-hero">
	<div class="container">
		<p class="eyebrow"><?php esc_html_e( 'News', 'vitalstack' ); ?></p>
		<h1 class="page-title"><?php esc_html_e( 'Tech news', 'vitalstack' ); ?></h1>
	</div>
</header>

<section class="section section-tight">
	<div class="container">
		<?php if ( $vs_query->have_posts() ) : ?>
			<div class="grid grid-3">
				<?php
				while ( $vs_query->have_posts() ) :
					$vs_query->the_post();
					vitalstack_card();
				endwhile;
				?>
			</div>
			<nav class="navigation pagination" aria-label="<?php esc_attr_e( 'Pages', 'vitalstack' ); ?>">
				<div class="nav-links">
					<?php
					echo paginate_links( // phpcs:ignore
						array(
							'total'     => $vs_query->max_num_pages,
							'current'   => $vs_paged,
							'mid_size'  => 1,
							'prev_text' => '←',
							'next_text' => '→',
						)
					);
					?>
				</div>
			</nav>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/none' ); ?>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
