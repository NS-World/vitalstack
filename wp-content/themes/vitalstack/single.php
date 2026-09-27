<?php
/**
 * Single guide (blog posts and news), in the same docs layout as tutorials:
 * topic sidebar | article | "on this page".
 *
 * @package VitalStack
 */

get_header();

while ( have_posts() ) :
	the_post();
	$vs_content = vitalstack_get_rendered_content();
	?>
	<div class="doc">
		<aside class="doc-side" id="doc-side" aria-label="<?php esc_attr_e( 'More in this topic', 'vitalstack' ); ?>">
			<div class="doc-side-inner">
				<?php vitalstack_topic_sidebar(); ?>
			</div>
		</aside>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'doc-main' ); ?>>
			<button type="button" class="side-toggle" data-toggle-side aria-controls="doc-side" aria-expanded="false"><?php echo vitalstack_icon( 'menu', 18 ); // phpcs:ignore ?> <?php esc_html_e( 'More in this topic', 'vitalstack' ); ?></button>

			<?php vitalstack_breadcrumbs(); ?>
			<h1 class="doc-title<?php echo mb_strlen( get_the_title() ) > 70 ? ' is-long' : ''; ?>"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="doc-dek"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
			<div class="doc-foot-row doc-byline">
				<?php vitalstack_byline(); ?>
				<?php vitalstack_share(); ?>
			</div>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="doc-hero">
					<?php the_post_thumbnail( 'vitalstack-hero', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
				</figure>
			<?php endif; ?>

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
			$vs_tags = get_the_tags();
			if ( $vs_tags ) :
				?>
				<div class="tag-list">
					<?php foreach ( $vs_tags as $vs_tag ) : ?>
						<a class="chip" href="<?php echo esc_url( get_tag_link( $vs_tag ) ); ?>">#<?php echo esc_html( $vs_tag->name ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php vitalstack_subscribe_box( 'inline' ); ?>
			<?php vitalstack_author_box(); ?>
			<?php vitalstack_related(); ?>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</article>

		<aside class="doc-toc" aria-label="<?php esc_attr_e( 'On this page', 'vitalstack' ); ?>">
			<div class="doc-toc-inner">
				<?php vitalstack_toc(); ?>
			</div>
		</aside>
	</div>
	<?php
endwhile;

get_footer();
