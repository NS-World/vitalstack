<?php
/**
 * Template Name: Cheat Sheets (hub)
 *
 * @package VitalStack
 */

get_header();
?>
<section class="games-hero sp-hero">
	<div class="wrap games-hero-inner">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Free · Printable · Updated', 'vitalstack' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<p class="games-hero-sub"><?php esc_html_e( 'Everything you need on one page: syntax, examples and the gotchas people forget. Keep them open while you code, or save them as PDF.', 'vitalstack' ); ?></p>
		</div>
		<div class="games-hero-bot" aria-hidden="true"><?php echo vitalstack_mascot( 'happy', 150, '', 'dev' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></div>
	</div>
</section>
<div class="wrap sp-wrap">
	<?php vitalstack_site_page_cards( 'cheat-sheets' ); ?>
	<?php
	while ( have_posts() ) :
		the_post();
		if ( get_the_content() ) :
			?>
			<div class="prose sp-extra"><?php the_content(); ?></div>
			<?php
		endif;
	endwhile;
	?>
</div>
<?php
get_footer();
