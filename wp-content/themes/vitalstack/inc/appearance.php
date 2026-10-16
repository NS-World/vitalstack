<?php
/**
 * Appearance: theme modes (light, dark, sepia, auto) and accent colours.
 *
 * The site owner picks the defaults in Appearance → Customize → Colours & theme.
 * Readers can override both from the palette button; their choice is kept in
 * localStorage and applied before first paint.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme modes: slug => label.
 */
function vitalstack_appearance_modes() {
	return array(
		'light' => __( 'Light', 'vitalstack' ),
		'dark'  => __( 'Dark', 'vitalstack' ),
		'sepia' => __( 'Sepia', 'vitalstack' ),
		'auto'  => __( 'Auto', 'vitalstack' ),
	);
}

/**
 * Accent colours: slug => array( label, swatch ).
 */
function vitalstack_appearance_accents() {
	return array(
		'green'  => array( __( 'Green', 'vitalstack' ), '#0a7d4f' ),
		'blue'   => array( __( 'Blue', 'vitalstack' ), '#1d5fd1' ),
		'purple' => array( __( 'Purple', 'vitalstack' ), '#6d3fd1' ),
		'orange' => array( __( 'Orange', 'vitalstack' ), '#c2410c' ),
		'pink'   => array( __( 'Pink', 'vitalstack' ), '#be2856' ),
	);
}

function vitalstack_default_mode() {
	$mode = get_theme_mod( 'vitalstack_default_mode', 'light' );
	return array_key_exists( $mode, vitalstack_appearance_modes() ) ? $mode : 'light';
}

function vitalstack_default_accent() {
	$accent = get_theme_mod( 'vitalstack_default_accent', 'green' );
	return array_key_exists( $accent, vitalstack_appearance_accents() ) ? $accent : 'green';
}

/**
 * Customizer: site-wide defaults.
 */
function vitalstack_appearance_customize( $wp_customize ) {
	$wp_customize->add_section(
		'vitalstack_appearance',
		array(
			'title'       => __( 'Colours & theme', 'vitalstack' ),
			'description' => __( 'What new visitors see. Each reader can still switch from the palette button in the header.', 'vitalstack' ),
			'priority'    => 35,
		)
	);
	$wp_customize->add_setting(
		'vitalstack_default_mode',
		array(
			'default'           => 'light',
			'sanitize_callback' => function ( $v ) {
				return array_key_exists( $v, vitalstack_appearance_modes() ) ? $v : 'light';
			},
		)
	);
	$wp_customize->add_control(
		'vitalstack_default_mode',
		array(
			'label'   => __( 'Default theme', 'vitalstack' ),
			'section' => 'vitalstack_appearance',
			'type'    => 'radio',
			'choices' => vitalstack_appearance_modes(),
		)
	);
	$wp_customize->add_setting(
		'vitalstack_default_accent',
		array(
			'default'           => 'green',
			'sanitize_callback' => function ( $v ) {
				return array_key_exists( $v, vitalstack_appearance_accents() ) ? $v : 'green';
			},
		)
	);
	$wp_customize->add_control(
		'vitalstack_default_accent',
		array(
			'label'   => __( 'Default accent colour', 'vitalstack' ),
			'section' => 'vitalstack_appearance',
			'type'    => 'radio',
			'choices' => wp_list_pluck( vitalstack_appearance_accents(), 0 ),
		)
	);
}
add_action( 'customize_register', 'vitalstack_appearance_customize' );

/**
 * Apply the reader's (or the site's default) mode and accent before first paint.
 */
function vitalstack_theme_bootstrap_script() {
	$modes   = array_keys( vitalstack_appearance_modes() );
	$accents = array_keys( vitalstack_appearance_accents() );
	?>
	<script>
	(function(){var d=document.documentElement,m=<?php echo wp_json_encode( vitalstack_default_mode() ); ?>,a=<?php echo wp_json_encode( vitalstack_default_accent() ); ?>;
	try{var t=localStorage.getItem('vs-theme'),c=localStorage.getItem('vs-accent');if(<?php echo wp_json_encode( $modes ); ?>.indexOf(t)>-1){m=t;}if(<?php echo wp_json_encode( $accents ); ?>.indexOf(c)>-1){a=c;}}catch(e){}
	d.setAttribute('data-theme',m);d.setAttribute('data-accent',a);})();
	</script>
	<?php
}
add_action( 'wp_head', 'vitalstack_theme_bootstrap_script', 0 );

/**
 * The mode buttons and accent swatches. Used in the header popover and the mobile drawer.
 */
function vitalstack_appearance_controls() {
	?>
	<p class="appearance-label"><?php esc_html_e( 'Theme', 'vitalstack' ); ?></p>
	<div class="appearance-modes">
		<?php foreach ( vitalstack_appearance_modes() as $slug => $label ) : ?>
			<button type="button" data-set-theme="<?php echo esc_attr( $slug ); ?>" aria-pressed="false">
				<span class="mode-swatch mode-swatch-<?php echo esc_attr( $slug ); ?>" aria-hidden="true"></span><?php echo esc_html( $label ); ?>
			</button>
		<?php endforeach; ?>
	</div>
	<p class="appearance-label"><?php esc_html_e( 'Colour', 'vitalstack' ); ?></p>
	<div class="appearance-accents">
		<?php foreach ( vitalstack_appearance_accents() as $slug => $accent ) : ?>
			<button type="button" data-set-accent="<?php echo esc_attr( $slug ); ?>" aria-pressed="false" aria-label="<?php echo esc_attr( $accent[0] ); ?>" title="<?php echo esc_attr( $accent[0] ); ?>" style="--sw: <?php echo esc_attr( $accent[1] ); ?>"></button>
		<?php endforeach; ?>
	</div>
	<p class="appearance-note"><?php esc_html_e( 'Saved on this device.', 'vitalstack' ); ?></p>
	<?php
}

/**
 * Header palette button with its popover.
 */
function vitalstack_appearance_button() {
	?>
	<div class="appearance">
		<button type="button" class="icon-btn" data-appearance-toggle aria-expanded="false" aria-controls="appearance-panel" aria-label="<?php esc_attr_e( 'Theme and colours', 'vitalstack' ); ?>" title="<?php esc_attr_e( 'Theme and colours', 'vitalstack' ); ?>">
			<?php echo vitalstack_icon( 'palette' ); // phpcs:ignore ?>
		</button>
		<div class="appearance-panel" id="appearance-panel" hidden>
			<?php vitalstack_appearance_controls(); ?>
		</div>
	</div>
	<?php
}
