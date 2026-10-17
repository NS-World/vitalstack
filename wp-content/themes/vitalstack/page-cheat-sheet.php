<?php
/**
 * Template Name: Cheat Sheet
 *
 * One printable cheat sheet. Body comes from the editor, or from the theme's
 * built-in text while the page is empty (see inc/site-pages.php).
 *
 * @package VitalStack
 */

get_header();
while ( have_posts() ) :
	the_post();
	$vs_pages   = vitalstack_site_pages();
	$vs_slug    = get_post_field( 'post_name' );
	$vs_meta    = isset( $vs_pages[ $vs_slug ] ) ? $vs_pages[ $vs_slug ] : array( 'icon' => '📄', 'excerpt' => '' );
	$vs_content = vitalstack_site_page_content();
	$vs_hub     = vitalstack_site_page_url( 'cheat-sheets' );
	?>
	<div class="wrap sheet-wrap">
		<?php vitalstack_breadcrumbs(); ?>
		<header class="sheet-head">
			<span class="sheet-icon" aria-hidden="true"><?php echo esc_html( $vs_meta['icon'] ); ?></span>
			<div>
				<h1 class="sheet-title"><?php the_title(); ?></h1>
				<?php if ( $vs_meta['excerpt'] ) : ?>
					<p class="sheet-sub"><?php echo esc_html( $vs_meta['excerpt'] ); ?></p>
				<?php endif; ?>
			</div>
			<div class="sheet-actions">
				<button type="button" class="btn btn-primary btn-sm" onclick="window.print()"><?php esc_html_e( 'Download PDF', 'vitalstack' ); ?></button>
				<?php if ( $vs_hub ) : ?>
					<a class="btn btn-ghost btn-sm" href="<?php echo esc_url( $vs_hub ); ?>"><?php esc_html_e( 'All cheat sheets', 'vitalstack' ); ?></a>
				<?php endif; ?>
			</div>
		</header>
		<div class="prose sheet"><?php echo $vs_content; // phpcs:ignore WordPress.Security.EscapeOutput -- filtered content ?></div>
		<p class="sheet-foot muted"><?php echo esc_html( sprintf( /* translators: 1: site, 2: url */ __( 'From %1$s · %2$s', 'vitalstack' ), get_bloginfo( 'name' ), preg_replace( '#^https?://#', '', get_permalink() ) ) ); ?></p>
	</div>
	<?php
endwhile;
get_footer();
