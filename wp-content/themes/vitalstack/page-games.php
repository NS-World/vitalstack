<?php
/**
 * Template Name: Brain Games
 *
 * Two-minute games that secretly teach algorithms. Each game awards XP
 * (assets/js/games.js) and links to the lesson behind it.
 *
 * @package VitalStack
 */

get_header();
?>

<section class="games-hero">
	<div class="wrap games-hero-inner">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Brain break', 'vitalstack' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<p class="games-hero-sub"><?php esc_html_e( 'Tired of reading? Play for two minutes and come back fresh. Every game secretly trains a real algorithm skill, and earns you XP.', 'vitalstack' ); ?></p>
			<div class="games-daily" data-daily hidden>
				<span class="games-daily-badge">⭐ <?php esc_html_e( 'Game of the day', 'vitalstack' ); ?></span>
				<strong data-daily-name></strong>
				<button type="button" class="btn btn-primary btn-sm" data-daily-play><?php esc_html_e( 'Play now', 'vitalstack' ); ?></button>
			</div>
		</div>
		<div class="games-hero-bot"><?php echo vitalstack_mascot( 'cheer', 150 ); // phpcs:ignore WordPress.Security.EscapeOutput -- fixed markup ?></div>
	</div>
</section>

<div class="wrap games-wrap">
	<div class="games-grid" data-games></div>

	<div class="game-stage" id="game-stage" hidden>
		<div class="game-stage-head">
			<button type="button" class="btn btn-ghost btn-sm" data-game-back>❮ <?php esc_html_e( 'All games', 'vitalstack' ); ?></button>
			<strong class="game-stage-title" data-game-title></strong>
			<span class="game-stage-stats" data-game-stats></span>
		</div>
		<div class="game-stage-body" data-game-body></div>
	</div>

	<?php
	while ( have_posts() ) :
		the_post();
		if ( get_the_content() ) :
			?>
			<div class="prose games-content"><?php the_content(); ?></div>
			<?php
		endif;
	endwhile;
	?>
</div>

<?php
get_footer();
