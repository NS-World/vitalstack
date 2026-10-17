<?php
/**
 * Template Name: Daily Challenge
 *
 * Today's challenge question, the reader's streak, and the weekly leaderboard.
 *
 * @package VitalStack
 */

get_header();
?>
<section class="games-hero challenge-hero">
	<div class="wrap games-hero-inner">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'One question a day', 'vitalstack' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<p class="games-hero-sub"><?php esc_html_e( 'A new coding question every day, the same for everyone. Answer it to earn XP, keep your streak alive and climb the weekly leaderboard.', 'vitalstack' ); ?></p>
			<div class="challenge-stats">
				<span>🔥 <b data-xp="streak">0</b> <?php esc_html_e( 'day streak', 'vitalstack' ); ?></span>
				<span>⚡ <b data-xp="week">0</b> <?php esc_html_e( 'XP this week', 'vitalstack' ); ?></span>
			</div>
		</div>
		<div class="games-hero-bot" aria-hidden="true"><?php echo vitalstack_mascot( 'wow', 150 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></div>
	</div>
</section>

<div class="wrap challenge-grid">
	<div>
		<?php vitalstack_daily_challenge(); ?>
		<?php
		while ( have_posts() ) :
			the_post();
			if ( get_the_content() ) :
				?>
				<div class="prose challenge-intro"><?php the_content(); ?></div>
				<?php
			endif;
		endwhile;
		?>
		<div class="challenge-more">
			<h2><?php esc_html_e( 'Want more?', 'vitalstack' ); ?></h2>
			<div class="challenge-links">
				<?php if ( vitalstack_games_url() ) : ?>
					<a class="challenge-link" href="<?php echo esc_url( vitalstack_games_url() ); ?>"><span>🎮</span><b><?php esc_html_e( 'Brain games', 'vitalstack' ); ?></b><small><?php esc_html_e( '2-minute coding games', 'vitalstack' ); ?></small></a>
				<?php endif; ?>
				<?php if ( vitalstack_problems_url() ) : ?>
					<a class="challenge-link" href="<?php echo esc_url( vitalstack_problems_url() ); ?>"><span>🧩</span><b><?php esc_html_e( 'Interview problems', 'vitalstack' ); ?></b><small><?php esc_html_e( 'Classic questions, solved simply', 'vitalstack' ); ?></small></a>
				<?php endif; ?>
				<a class="challenge-link" href="<?php echo esc_url( vitalstack_tutorials_url() ); ?>"><span>📚</span><b><?php esc_html_e( 'Lessons with quizzes', 'vitalstack' ); ?></b><small><?php esc_html_e( 'Every lesson ends with a quiz', 'vitalstack' ); ?></small></a>
			</div>
		</div>
	</div>
	<aside>
		<?php vitalstack_leaderboard_block(); ?>
	</aside>
</div>
<?php
get_footer();
