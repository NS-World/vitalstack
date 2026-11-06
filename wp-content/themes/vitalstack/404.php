<?php
/**
 * 404 page.
 *
 * @package VitalStack
 */

get_header();
?>
<section class="section">
	<div class="container narrow">
		<div class="empty-state">
			<div class="nf-hero">
				<div class="nf-bot" aria-hidden="true"><?php echo function_exists( 'vitalstack_mascot' ) ? vitalstack_mascot( 'think', 132, '', 'byte' ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput -- fixed SVG ?></div>
				<p class="nf-bubble"><?php esc_html_e( 'Meow… I searched every folder. This page is not here.', 'vitalstack' ); ?></p>
			</div>
			<p class="error-code">404</p>
			<h1><?php esc_html_e( 'This page wandered off', 'vitalstack' ); ?></h1>
			<p><?php esc_html_e( 'The link may be old or mistyped. Search for what you were looking for, or start from a learning path.', 'vitalstack' ); ?></p>
			<?php get_search_form(); ?>
			<p><a class="btn btn-primary" href="<?php echo esc_url( vitalstack_tutorials_url() ); ?>"><?php esc_html_e( 'Browse tutorials', 'vitalstack' ); ?></a></p>
		</div>
		<?php if ( function_exists( 'vitalstack_runner_box' ) ) : ?>
			<div class="nf-game">
				<h2 class="nf-game-title"><?php esc_html_e( 'Lost? Play while you are here', 'vitalstack' ); ?></h2>
				<?php vitalstack_runner_box( '404' ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
