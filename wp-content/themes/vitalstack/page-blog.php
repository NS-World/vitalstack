<?php
/**
 * Template Name: Blog Page
 *
 * All guides (blog posts), newest first.
 *
 * @package VitalStack
 */

get_header();

$vs_paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$vs_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'posts_per_page' => 12,
		'paged'          => $vs_paged,
	)
);
$vs_tops  = get_categories(
	array(
		'parent'     => 0,
		'hide_empty' => true,
		'exclude'    => array( get_option( 'default_category' ) ),
	)
);
?>

<header class="page-hero">
	<div class="container">
		<p class="eyebrow"><?php esc_html_e( 'Guides', 'vitalstack' ); ?></p>
		<h1 class="page-title"><?php esc_html_e( 'All guides', 'vitalstack' ); ?></h1>
		<p class="page-desc"><?php esc_html_e( 'Practical explainers on AI, tools, careers and programming.', 'vitalstack' ); ?></p>
		<?php if ( $vs_tops ) : ?>
			<div class="chips">
				<span class="chip is-active"><?php esc_html_e( 'All', 'vitalstack' ); ?></span>
				<?php foreach ( $vs_tops as $vs_cat ) : ?>
					<?php foreach ( array_merge( array( $vs_cat ), get_categories( array( 'parent' => $vs_cat->term_id ) ) ) as $vs_c ) : ?>
						<a class="chip" href="<?php echo esc_url( get_category_link( $vs_c ) ); ?>"><?php echo esc_html( $vs_c->name ); ?></a>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
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
