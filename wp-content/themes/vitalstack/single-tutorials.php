<?php
/**
 * Single tutorial, in a docs layout:
 * lessons sidebar | lesson content | "on this page".
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
	<div class="doc">
		<aside class="doc-side" id="doc-side" aria-label="<?php esc_attr_e( 'Lessons', 'vitalstack' ); ?>">
			<div class="doc-side-inner">
				<p class="doc-side-title"><?php esc_html_e( 'Tutorials', 'vitalstack' ); ?></p>
				<?php vitalstack_lessons_sidebar(); ?>
			</div>
		</aside>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'doc-main' ); ?>>
			<button type="button" class="side-toggle" data-toggle-side aria-controls="doc-side" aria-expanded="false"><?php echo vitalstack_icon( 'menu', 18 ); // phpcs:ignore ?> <?php esc_html_e( 'Lessons', 'vitalstack' ); ?></button>

			<?php vitalstack_breadcrumbs(); ?>
			<h1 class="doc-title<?php echo mb_strlen( get_the_title() ) > 70 ? ' is-long' : ''; ?>"><?php the_title(); ?></h1>
			<div class="doc-meta">
				<?php if ( $vs_path ) : ?>
					<span class="pill pill-green"><?php echo esc_html( $vs_path->name ); ?></span>
				<?php endif; ?>
				<?php if ( $vs_level ) : ?>
					<span class="pill pill-slate"><?php echo esc_html( $vs_level ); ?></span>
				<?php endif; ?>
				<span class="muted"><?php echo vitalstack_icon( 'clock', 14 ); // phpcs:ignore ?> <?php echo esc_html( sprintf( /* translators: %d minutes */ __( '%d min read', 'vitalstack' ), vitalstack_read_minutes() ) ); ?></span>
				<span class="muted"><?php esc_html_e( 'Updated', 'vitalstack' ); ?> <time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php echo esc_html( get_the_modified_date() ); ?></time></span>
			</div>

			<?php
			$vs_pmeta = array(
				'pattern'   => get_post_meta( get_the_ID(), '_vs_pattern', true ),
				'level'     => get_post_meta( get_the_ID(), '_vs_level', true ),
				'companies' => get_post_meta( get_the_ID(), '_vs_companies', true ),
			);
			if ( array_filter( $vs_pmeta ) ) :
				?>
				<div class="problem-meta">
					<?php if ( $vs_pmeta['level'] ) : ?><span class="level-badge level-<?php echo esc_attr( strtolower( $vs_pmeta['level'] ) ); ?>"><?php echo esc_html( $vs_pmeta['level'] ); ?></span><?php endif; ?>
					<?php if ( $vs_pmeta['pattern'] ) : ?><span class="pattern-chip"><?php echo esc_html( $vs_pmeta['pattern'] ); ?></span><?php endif; ?>
					<?php if ( $vs_pmeta['companies'] ) : ?><span><?php esc_html_e( 'Often asked at:', 'vitalstack' ); ?> <?php echo esc_html( $vs_pmeta['companies'] ); ?></span><?php endif; ?>
				</div>
			<?php endif; ?>

			<?php vitalstack_lesson_buttons(); ?>

			<?php
			if ( $vs_video ) {
				echo $vs_video; // phpcs:ignore WordPress.Security.EscapeOutput -- built with escaping
			}
			?>

			<?php if ( count( $GLOBALS['vitalstack_toc'] ) >= 3 ) : ?>
				<details class="toc-mobile">
					<summary><?php echo vitalstack_icon( 'list', 16 ); // phpcs:ignore ?> <?php esc_html_e( 'In this lesson', 'vitalstack' ); ?></summary>
					<?php vitalstack_toc(); ?>
				</details>
			<?php endif; ?>

			<div class="prose">
				<?php echo $vs_content; // phpcs:ignore WordPress.Security.EscapeOutput -- filtered post content ?>
			</div>

			<?php vitalstack_lesson_quiz(); ?>

			<div class="lesson-end">
				<?php vitalstack_lesson_complete_button(); ?>
				<?php vitalstack_lesson_buttons(); ?>
			</div>

			<?php vitalstack_subscribe_box( 'inline' ); ?>

			<div class="doc-foot">
				<div class="doc-foot-row">
					<?php vitalstack_byline(); ?>
					<?php vitalstack_share(); ?>
				</div>
			</div>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</article>

		<aside class="doc-toc" aria-label="<?php esc_attr_e( 'On this page', 'vitalstack' ); ?>">
			<div class="doc-toc-inner">
				<?php vitalstack_toc(); ?>
				<?php vitalstack_ad_sidebar(); ?>
			</div>
		</aside>
	</div>
	<?php
endwhile;

get_footer();
