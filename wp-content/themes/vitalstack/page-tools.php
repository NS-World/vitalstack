<?php
/**
 * Template Name: Free Tools (hub)
 *
 * @package VitalStack
 */

get_header();
?>
<section class="games-hero sp-hero">
	<div class="wrap games-hero-inner">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Free · No sign-up · Runs in your browser', 'vitalstack' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<p class="games-hero-sub"><?php esc_html_e( 'Run Python, practise SQL, format JSON and test regular expressions right here. Your code never leaves your browser.', 'vitalstack' ); ?></p>
		</div>
		<div class="games-hero-bot" aria-hidden="true"><?php echo vitalstack_mascot( 'wow', 150, '', 'chip' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></div>
	</div>
</section>
<div class="wrap sp-wrap">
	<?php vitalstack_site_page_cards( 'tools' ); ?>
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
