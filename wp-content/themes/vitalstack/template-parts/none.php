<?php
/**
 * Empty state for archives and search.
 *
 * @package VitalStack
 */

?>
<div class="empty-state">
	<span class="path-icon tone-slate"><?php echo vitalstack_icon( 'search', 22 ); // phpcs:ignore ?></span>
	<h2><?php esc_html_e( 'Nothing here yet', 'vitalstack' ); ?></h2>
	<p><?php esc_html_e( 'Try another search, or start with one of our learning paths.', 'vitalstack' ); ?></p>
	<?php get_search_form(); ?>
	<p><a class="btn btn-primary" href="<?php echo esc_url( vitalstack_tutorials_url() ); ?>"><?php esc_html_e( 'Browse tutorials', 'vitalstack' ); ?></a></p>
</div>
