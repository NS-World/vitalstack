<?php
/**
 * Single tutorial: a lesson inside a learning path.
 *
 * @package VitalStack
 */

get_header();

while ( have_posts() ) :
	the_post();
	$vs_content = vitalstack_get_rendered_content();
	$vs_path    = vitalstack_tutorial_path();
	$vs_levels  = get_the_terms( get_the_ID(), 'tutorial_difficulty' );
	$vs_level   = ( $vs_levels && ! is_wp_error( $vs_levels ) ) ? $vs_levels[0]->name : '';
	$vs_video   = vitalstack_tutorial_video();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'article article--lesson' ); ?>>
		<div class="container lesson-layout">
			<aside class="lesson-aside">
				<div class="sticky-aside">
					<?php vitalstack_path_nav(); ?>
				</div>
			</aside>

			<div class="lesson-main">
				<header class="article-head">
					<?php vitalstack_breadcrumbs(); ?>
					<div class="lesson-badges">
						<?php if ( $vs_path ) : ?>
							<span class="pill pill-teal"><?php echo esc_html( $vs_path->name ); ?></span>
						<?php endif; ?>
						<?php if ( $vs_level ) : ?>
							<span class="pill pill-slate"><?php echo esc_html( $vs_level ); ?></span>
						<?php endif; ?>
					</div>
					<h1 class="article-title<?php echo mb_strlen( get_the_title() ) > 70 ? ' is-long' : ''; ?>"><?php the_title(); ?></h1>
					<div class="article-meta-row">
						<?php vitalstack_byline(); ?>
						<?php vitalstack_share(); ?>
					</div>
				</header>

				<?php
				if ( $vs_video ) {
					echo $vs_video; // phpcs:ignore WordPress.Security.EscapeOutput -- built with escaping
				} elseif ( has_post_thumbnail() ) {
					echo '<figure class="article-hero">';
					the_post_thumbnail( 'vitalstack-hero', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) );
					echo '</figure>';
				}
				?>

				<?php if ( count( $GLOBALS['vitalstack_toc'] ) >= 3 ) : ?>
					<details class="toc-mobile toc-lesson">
						<summary><?php echo vitalstack_icon( 'list', 16 ); // phpcs:ignore ?> <?php esc_html_e( 'In this lesson', 'vitalstack' ); ?></summary>
						<?php vitalstack_toc(); ?>
					</details>
				<?php endif; ?>

				<div class="prose">
					<?php echo $vs_content; // phpcs:ignore WordPress.Security.EscapeOutput -- filtered post content ?>
				</div>

				<?php vitalstack_lesson_pager(); ?>

				<footer class="article-foot">
					<?php vitalstack_author_box(); ?>
				</footer>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
