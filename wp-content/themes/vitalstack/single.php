<?php
/**
 * Single article (blog posts and news).
 *
 * @package VitalStack
 */

get_header();

while ( have_posts() ) :
	the_post();
	$vs_content = vitalstack_get_rendered_content();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'article' ); ?>>
		<header class="article-head container">
			<?php vitalstack_breadcrumbs(); ?>
			<h1 class="article-title<?php echo mb_strlen( get_the_title() ) > 70 ? ' is-long' : ''; ?>"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="article-dek"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
			<div class="article-meta-row">
				<?php vitalstack_byline(); ?>
				<?php vitalstack_share(); ?>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="article-hero container">
				<?php the_post_thumbnail( 'vitalstack-hero', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
			</figure>
		<?php endif; ?>

		<div class="container article-layout">
			<aside class="article-aside">
				<div class="sticky-aside">
					<?php vitalstack_toc(); ?>
					<?php if ( is_active_sidebar( 'sidebar-single' ) ) : ?>
						<div class="aside-widgets"><?php dynamic_sidebar( 'sidebar-single' ); ?></div>
					<?php endif; ?>
				</div>
			</aside>

			<div class="article-main">
				<?php if ( count( $GLOBALS['vitalstack_toc'] ) >= 3 ) : ?>
					<details class="toc-mobile">
						<summary><?php echo vitalstack_icon( 'list', 16 ); // phpcs:ignore ?> <?php esc_html_e( 'On this page', 'vitalstack' ); ?></summary>
						<?php vitalstack_toc(); ?>
					</details>
				<?php endif; ?>

				<div class="prose">
					<?php echo $vs_content; // phpcs:ignore WordPress.Security.EscapeOutput -- filtered post content ?>
				</div>

				<?php
				wp_link_pages(
					array(
						'before' => '<nav class="page-links">',
						'after'  => '</nav>',
					)
				);
				?>

				<footer class="article-foot">
					<?php
					$vs_tags = get_the_tags();
					if ( $vs_tags ) :
						?>
						<div class="tag-list">
							<?php foreach ( $vs_tags as $vs_tag ) : ?>
								<a class="chip" href="<?php echo esc_url( get_tag_link( $vs_tag ) ); ?>">#<?php echo esc_html( $vs_tag->name ); ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<div class="article-foot-share">
						<p><?php esc_html_e( 'Found this useful? Share it with a friend who is learning too.', 'vitalstack' ); ?></p>
						<?php vitalstack_share(); ?>
					</div>
					<?php vitalstack_author_box(); ?>
				</footer>
			</div>
		</div>
	</article>

	<div class="container">
		<?php vitalstack_related(); ?>
		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	</div>
	<?php
endwhile;

get_footer();
