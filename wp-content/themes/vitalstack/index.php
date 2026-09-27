<?php
/**
 * Fallback template (also the posts page when one is set in Settings → Reading).
 *
 * @package VitalStack
 */

get_header();
?>

<header class="page-hero">
	<div class="container">
		<p class="eyebrow"><?php esc_html_e( 'Guides', 'vitalstack' ); ?></p>
		<h1 class="page-title"><?php echo is_home() && ! is_front_page() ? esc_html( single_post_title( '', false ) ) : esc_html__( 'Latest guides', 'vitalstack' ); ?></h1>
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
