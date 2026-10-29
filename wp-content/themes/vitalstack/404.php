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
			<div class="error-bot" aria-hidden="true"><?php echo vitalstack_mascot( 'think', 110 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></div>
			<p class="error-code">404</p>
			<h1><?php esc_html_e( 'This page wandered off', 'vitalstack' ); ?></h1>
			<p><?php esc_html_e( 'The link may be old or mistyped. Search for what you were looking for, or start from a learning path.', 'vitalstack' ); ?></p>
			<?php get_search_form(); ?>
			<p><a class="btn btn-primary" href="<?php echo esc_url( vitalstack_tutorials_url() ); ?>"><?php esc_html_e( 'Browse tutorials', 'vitalstack' ); ?></a></p>
		</div>
	</div>
</section>
<?php
get_footer();
