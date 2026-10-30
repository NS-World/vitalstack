<?php
/**
 * Focus sounds on lessons: an optional player for gamma (40 Hz) and beta
 * (14–30 Hz) beats. The sound is made in the browser by assets/js/focus.js;
 * nothing plays unless the reader presses Play.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_focus_enabled() {
	return is_singular( 'tutorials' ) && (bool) apply_filters( 'vitalstack_focus_sounds', true );
}

function vitalstack_focus_assets() {
	if ( vitalstack_focus_enabled() ) {
		wp_enqueue_script( 'vitalstack-focus', vitalstack_asset_url( 'assets/js/focus.js' ), array( 'vitalstack-fun' ), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'vitalstack_focus_assets', 30 );

/**
 * The 🎧 button in the lesson's meta row (shown once the script is ready).
 */
function vitalstack_focus_button() {
	if ( ! vitalstack_focus_enabled() ) {
		return;
	}
	?>
	<button type="button" class="focus-btn" data-focus-open aria-controls="focus-panel" aria-expanded="false" hidden>🎧 <?php esc_html_e( 'Focus sounds', 'vitalstack' ); ?></button>
	<?php
}

/**
 * The player, opened by the button.
 */
function vitalstack_focus_panel() {
	if ( ! vitalstack_focus_enabled() ) {
		return;
	}
	?>
	<section class="focus-panel" id="focus-panel" aria-label="<?php esc_attr_e( 'Focus sounds', 'vitalstack' ); ?>" hidden>
		<div class="focus-head">
			<p class="focus-title">🎧 <?php esc_html_e( 'Focus sounds', 'vitalstack' ); ?></p>
			<button type="button" class="focus-close" data-focus-close aria-label="<?php esc_attr_e( 'Close', 'vitalstack' ); ?>">×</button>
		</div>
		<p class="focus-intro"><?php esc_html_e( 'Soft beat sounds that many learners like while reading. Pick one, press Play, and keep studying.', 'vitalstack' ); ?></p>
		<form class="focus-form">
			<fieldset class="focus-modes">
				<legend class="screen-reader-text"><?php esc_html_e( 'Sound', 'vitalstack' ); ?></legend>
				<label class="focus-mode"><input type="radio" name="mode" value="gamma" checked> <span><b><?php esc_html_e( 'Gamma · 40 Hz', 'vitalstack' ); ?></b><small><?php esc_html_e( 'Deep focus for hard topics', 'vitalstack' ); ?></small></span></label>
				<label class="focus-mode"><input type="radio" name="mode" value="beta"> <span><b><?php esc_html_e( 'Beta · 14–30 Hz', 'vitalstack' ); ?></b><small><?php esc_html_e( 'Alert, active reading and practice', 'vitalstack' ); ?></small></span></label>
			</fieldset>
			<label class="focus-row" data-beta-row hidden><span><?php esc_html_e( 'Beta rate', 'vitalstack' ); ?> <output data-beta-out>18 Hz</output></span><input type="range" name="beta" min="14" max="30" step="1" value="18"></label>
			<div class="focus-grid">
				<label class="focus-row"><span><?php esc_html_e( 'Listening on', 'vitalstack' ); ?></span>
					<select name="out"><option value="phones"><?php esc_html_e( 'Headphones (binaural)', 'vitalstack' ); ?></option><option value="speakers"><?php esc_html_e( 'Speakers (pulse)', 'vitalstack' ); ?></option></select></label>
				<label class="focus-row"><span><?php esc_html_e( 'Background', 'vitalstack' ); ?></span>
					<select name="bed"><option value="brown"><?php esc_html_e( 'Soft rain (brown noise)', 'vitalstack' ); ?></option><option value="pink"><?php esc_html_e( 'Light rain (pink noise)', 'vitalstack' ); ?></option><option value="none"><?php esc_html_e( 'None', 'vitalstack' ); ?></option></select></label>
				<label class="focus-row"><span><?php esc_html_e( 'Timer', 'vitalstack' ); ?></span>
					<select name="mins"><option value="25"><?php esc_html_e( '25 min (Pomodoro)', 'vitalstack' ); ?></option><option value="50"><?php esc_html_e( '50 min', 'vitalstack' ); ?></option><option value="0"><?php esc_html_e( 'No timer', 'vitalstack' ); ?></option></select></label>
				<label class="focus-row"><span><?php esc_html_e( 'Volume', 'vitalstack' ); ?></span><input type="range" name="vol" min="0" max="100" value="25"></label>
			</div>
			<button type="submit" class="btn btn-primary" data-focus-play aria-pressed="false">▶ <?php esc_html_e( 'Play', 'vitalstack' ); ?></button>
		</form>
		<p class="focus-note"><?php esc_html_e( 'Keep the volume low. These sounds are for comfort while studying, not a medical treatment. If you have epilepsy or are sensitive to pulsing sounds, use the background noise only.', 'vitalstack' ); ?></p>
	</section>
	<?php
}
