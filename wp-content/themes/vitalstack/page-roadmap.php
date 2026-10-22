<?php
/**
 * Template Name: Roadmap
 *
 * A career roadmap. The body comes from the editor, or from the theme while
 * the page is empty; [[path:slug]] markers become learning-path cards with
 * the reader's progress (see vitalstack_roadmap_render()).
 *
 * @package VitalStack
 */

get_header();
while ( have_posts() ) :
	the_post();
	$vs_pages = vitalstack_site_pages();
	$vs_slug  = get_post_field( 'post_name' );
	$vs_meta  = isset( $vs_pages[ $vs_slug ] ) ? $vs_pages[ $vs_slug ] : array( 'icon' => '🗺️', 'excerpt' => '' );
	$vs_hub   = vitalstack_site_page_url( 'roadmaps' );
	?>
	<div class="wrap roadmap-wrap">
		<?php vitalstack_breadcrumbs(); ?>
		<header class="sheet-head">
			<span class="sheet-icon" aria-hidden="true"><?php echo esc_html( $vs_meta['icon'] ); ?></span>
			<div>
				<h1 class="sheet-title"><?php the_title(); ?></h1>
				<?php if ( $vs_meta['excerpt'] ) : ?>
					<p class="sheet-sub"><?php echo esc_html( $vs_meta['excerpt'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $vs_hub ) : ?>
				<div class="sheet-actions"><a class="btn btn-ghost btn-sm" href="<?php echo esc_url( $vs_hub ); ?>"><?php esc_html_e( 'All roadmaps', 'vitalstack' ); ?></a></div>
			<?php endif; ?>
		</header>
		<div class="prose roadmap"><?php echo vitalstack_roadmap_render( vitalstack_site_page_content() ); // phpcs:ignore WordPress.Security.EscapeOutput -- filtered content ?></div>
		<?php vitalstack_subscribe_box( 'inline' ); ?>
	</div>
	<?php
endwhile;
get_footer();
