<?php
/**
 * Premium themes and combo packs (5.0).
 *
 *  - Six premium colour themes ("skins") with a gently animated header line.
 *    Premium readers can switch to them from the palette button; everyone
 *    else can preview one for 20 seconds. A skin is only cosmetic, so it is
 *    remembered in this browser (localStorage) and applied before first paint
 *    for premium readers only.
 *  - Two-track combo packs, added through the vitalstack_pay_plans filter.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/**
 * slug => array( label, swatch CSS gradient ).
 */
function vitalstack_skins() {
	return array(
		'neon'     => array( __( 'Midnight Neon', 'vitalstack' ), 'linear-gradient(135deg,#0b1020,#22d3ee 55%,#e879f9)' ),
		'ocean'    => array( __( 'Deep Ocean', 'vitalstack' ), 'linear-gradient(135deg,#0b2545,#13315c 45%,#38bdf8)' ),
		'sakura'   => array( __( 'Sakura', 'vitalstack' ), 'linear-gradient(135deg,#fff1f5,#fbcfe8 50%,#db2777)' ),
		'forest'   => array( __( 'Forest', 'vitalstack' ), 'linear-gradient(135deg,#f3f7ee,#a7c48a 50%,#2f6b3a)' ),
		'sunset'   => array( __( 'Sunset', 'vitalstack' ), 'linear-gradient(135deg,#fff7ed,#fdba74 45%,#e11d48)' ),
		'terminal' => array( __( 'Hacker Terminal', 'vitalstack' ), 'linear-gradient(135deg,#050805,#0b3d16 55%,#39ff14)' ),
	);
}

function vitalstack_skins_unlocked() {
	return function_exists( 'vitalstack_reader_has_any_premium' ) && vitalstack_reader_has_any_premium();
}

/**
 * Apply a premium reader's saved skin before first paint (after the theme
 * bootstrap at priority 0).
 */
function vitalstack_skins_bootstrap() {
	if ( ! vitalstack_skins_unlocked() ) {
		return;
	}
	?>
	<script>
	(function(){try{var s=localStorage.getItem('vs-skin');if(<?php echo wp_json_encode( array_keys( vitalstack_skins() ) ); ?>.indexOf(s)>-1){document.documentElement.setAttribute('data-skin',s);}}catch(e){}})();
	</script>
	<?php
}
add_action( 'wp_head', 'vitalstack_skins_bootstrap', 1 );

/**
 * Skin swatches inside the palette popover and the mobile drawer.
 */
function vitalstack_skins_controls() {
	$open = vitalstack_skins_unlocked();
	?>
	<p class="appearance-label"><?php esc_html_e( 'Premium themes', 'vitalstack' ); ?> ⭐</p>
	<div class="appearance-skins">
		<?php foreach ( vitalstack_skins() as $slug => $skin ) : ?>
			<button type="button" data-set-skin="<?php echo esc_attr( $slug ); ?>" aria-pressed="false"<?php echo $open ? '' : ' data-locked="1"'; ?> title="<?php echo esc_attr( $open ? $skin[0] : sprintf( /* translators: %s: theme name */ __( '%s (Premium: tap to preview)', 'vitalstack' ), $skin[0] ) ); ?>" aria-label="<?php echo esc_attr( $skin[0] ); ?>" style="--sw: <?php echo esc_attr( $skin[1] ); ?>">
				<?php if ( ! $open ) : ?><span class="skin-lock" aria-hidden="true">🔒</span><?php endif; ?>
			</button>
		<?php endforeach; ?>
	</div>
	<?php
}
add_action( 'vitalstack_appearance_controls_end', 'vitalstack_skins_controls' );

function vitalstack_skins_assets() {
	if ( is_admin() ) {
		return;
	}
	wp_enqueue_script( 'vitalstack-skins', vitalstack_asset_url( 'assets/js/skins.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_localize_script(
		'vitalstack-skins',
		'vitalstackSkins',
		array(
			'open'    => vitalstack_skins_unlocked(),
			'pricing' => function_exists( 'vitalstack_pricing_url' ) ? esc_url_raw( vitalstack_pricing_url() ) : '',
			'names'   => wp_list_pluck( vitalstack_skins(), 0 ),
			'preview' => __( 'Previewing %s for 20 seconds.', 'vitalstack' ),
			'unlock'  => __( 'Unlock all premium themes', 'vitalstack' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'vitalstack_skins_assets', 27 );

/* ── Combo packs ──────────────────────────────────────────────────────── */

function vitalstack_combo_plans( $plans ) {
	$tracks = function_exists( 'vitalstack_premium_tracks' ) ? vitalstack_premium_tracks() : array();
	$combos = array(
		'combo-ai-builder' => array(
			'title'  => __( 'AI Builder Combo', 'vitalstack' ),
			'tracks' => array( 'gen-ai-developer', 'agentic-ai-engineer' ),
			'blurb'  => __( 'Gen AI Developer + Agentic AI Engineer: build LLM apps, then turn them into agents.', 'vitalstack' ),
		),
		'combo-data-ai'    => array(
			'title'  => __( 'Data + AI Combo', 'vitalstack' ),
			'tracks' => array( 'data-scientist', 'gen-ai-developer' ),
			'blurb'  => __( 'Data Scientist + Gen AI Developer: analyse data, then build AI on top of it.', 'vitalstack' ),
		),
	);
	foreach ( $combos as $id => $c ) {
		if ( isset( $plans[ $id ] ) || array_diff( $c['tracks'], array_keys( $tracks ) ) ) {
			continue; // a track was renamed or removed: never sell a pack that grants nothing
		}
		$plans[ $id ] = $c + array(
			'days'   => 365,
			'inr'    => 2499,
			'usd'    => 49,
			'period' => __( '12 months', 'vitalstack' ),
			'combo'  => true,
		);
	}
	return $plans;
}
add_filter( 'vitalstack_pay_plans', 'vitalstack_combo_plans' );
