<?php
/**
 * Template Name: Roadmaps (hub)
 *
 * @package VitalStack
 */

get_header();
$vs_tests = vitalstack_site_page_url( 'mock-tests' );
?>
<section class="games-hero sp-hero">
	<div class="wrap games-hero-inner">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Free · Step by step · Track your progress', 'vitalstack' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<p class="games-hero-sub"><?php esc_html_e( 'What to learn, in what order, and how long it takes. Each stage links to free lessons, a mock test and a cheat sheet, and fills in as you complete lessons.', 'vitalstack' ); ?></p>
		</div>
		<div class="games-hero-bot" aria-hidden="true"><?php echo vitalstack_mascot( 'happy', 150, '', 'dev' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></div>
	</div>
</section>
<div class="wrap sp-wrap">
	<?php vitalstack_site_page_cards( 'roadmaps' ); ?>
	<?php if ( $vs_tests ) : ?>
		<p class="sp-cross"><?php esc_html_e( 'Already learning?', 'vitalstack' ); ?> <a href="<?php echo esc_url( $vs_tests ); ?>"><?php esc_html_e( 'Test yourself with a free mock test', 'vitalstack' ); ?> ❯</a></p>
	<?php endif; ?>
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
