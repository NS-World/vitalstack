<?php
/**
 * Template Name: Mock Tests (hub)
 *
 * @package VitalStack
 */

get_header();
$vs_roadmaps = vitalstack_site_page_url( 'roadmaps' );
?>
<section class="games-hero sp-hero">
	<div class="wrap games-hero-inner">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Free · Timed · Instant score', 'vitalstack' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<p class="games-hero-sub"><?php esc_html_e( 'Practise like the real exam: 20 random questions, a timer, your score and the topics to revise. Every question has an explanation, and you get new questions every time.', 'vitalstack' ); ?></p>
		</div>
		<div class="games-hero-bot" aria-hidden="true"><?php echo vitalstack_mascot( 'think', 150 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></div>
	</div>
</section>
<div class="wrap sp-wrap">
	<?php vitalstack_site_page_cards( 'mock-tests' ); ?>
	<?php if ( $vs_roadmaps ) : ?>
		<p class="sp-cross"><?php esc_html_e( 'Not sure what to learn next?', 'vitalstack' ); ?> <a href="<?php echo esc_url( $vs_roadmaps ); ?>"><?php esc_html_e( 'See the career roadmaps', 'vitalstack' ); ?> ❯</a></p>
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
