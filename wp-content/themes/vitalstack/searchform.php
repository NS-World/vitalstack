<?php
/**
 * Search form.
 *
 * @package VitalStack
 */

$vs_field_id = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $vs_field_id ); ?>"><?php esc_html_e( 'Search for:', 'vitalstack' ); ?></label>
	<?php echo vitalstack_icon( 'search', 18 ); // phpcs:ignore ?>
	<input type="search" id="<?php echo esc_attr( $vs_field_id ); ?>" class="search-field" placeholder="<?php esc_attr_e( 'Search tutorials and guides…', 'vitalstack' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" autocomplete="off">
	<button type="submit" class="search-submit"><?php esc_html_e( 'Search', 'vitalstack' ); ?></button>
</form>
