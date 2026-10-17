<?php
/**
 * Template Name: Free Tool
 *
 * A browser-based tool (chosen by the page slug) followed by its guide text.
 *
 * @package VitalStack
 */

get_header();
while ( have_posts() ) :
	the_post();
	$vs_pages = vitalstack_site_pages();
	$vs_slug  = get_post_field( 'post_name' );
	$vs_meta  = isset( $vs_pages[ $vs_slug ] ) ? $vs_pages[ $vs_slug ] : array( 'icon' => '🛠️', 'excerpt' => '' );
	?>
	<div class="wrap tool-wrap">
		<?php vitalstack_breadcrumbs(); ?>
		<header class="sheet-head">
			<span class="sheet-icon" aria-hidden="true"><?php echo esc_html( $vs_meta['icon'] ); ?></span>
			<div>
				<h1 class="sheet-title"><?php the_title(); ?></h1>
				<?php if ( $vs_meta['excerpt'] ) : ?>
					<p class="sheet-sub"><?php echo esc_html( $vs_meta['excerpt'] ); ?></p>
				<?php endif; ?>
			</div>
		</header>
		<div class="tool" data-tool="<?php echo esc_attr( $vs_slug ); ?>">
			<noscript><p class="notice notice-info"><?php esc_html_e( 'This tool needs JavaScript.', 'vitalstack' ); ?></p></noscript>
		</div>
		<div class="prose tool-guide"><?php echo vitalstack_site_page_content(); // phpcs:ignore WordPress.Security.EscapeOutput -- filtered content ?></div>
		<?php if ( vitalstack_site_page_url( 'tools' ) ) : ?>
			<p><a class="btn btn-ghost btn-sm" href="<?php echo esc_url( vitalstack_site_page_url( 'tools' ) ); ?>"><?php esc_html_e( 'More free tools', 'vitalstack' ); ?> ❯</a></p>
		<?php endif; ?>
	</div>
	<?php
endwhile;
get_footer();
