<?php
/**
 * Bug Runner: an endless runner game with Byte (assets/js/runner.js),
 * featured on the games page and on the 404 page.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_runner_assets() {
	if ( is_404() || is_page_template( 'page-games.php' ) ) {
		wp_enqueue_script( 'vitalstack-runner', vitalstack_asset_url( 'assets/js/runner.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'vitalstack_runner_assets', 32 );

/**
 * The game box.
 *
 * @param string $context games|404.
 */
function vitalstack_runner_box( $context = 'games' ) {
	$context = '404' === $context ? '404' : 'games';
	?>
	<section class="vs-runner" data-runner data-context="<?php echo esc_attr( $context ); ?>" aria-label="<?php esc_attr_e( 'Bug Runner game', 'vitalstack' ); ?>">
		<div class="vs-runner-head">
			<strong class="vs-runner-title">🏃 <?php esc_html_e( 'Bug Runner', 'vitalstack' ); ?></strong>
			<span class="vs-runner-score" data-runner-score aria-live="off"></span>
			<button type="button" class="vs-runner-sound" data-runner-sound aria-pressed="false" aria-label="<?php esc_attr_e( 'Sound', 'vitalstack' ); ?>">🔇</button>
		</div>
		<div class="vs-runner-stage">
			<canvas tabindex="0" role="img" aria-label="<?php esc_attr_e( 'Dev, a pixel coder hero, runs along a line of code. Jump over bugs and 500 errors, collect coffee and code coins.', 'vitalstack' ); ?>"></canvas>
			<button type="button" class="btn btn-primary vs-runner-play" data-runner-play>▶ <?php esc_html_e( 'Play', 'vitalstack' ); ?></button>
		</div>
		<p class="vs-runner-msg" data-runner-msg aria-live="polite"><?php esc_html_e( 'Tap the game or press Space / ↑ to jump. Tap again in the air for a double jump.', 'vitalstack' ); ?></p>
		<noscript><p class="vs-runner-msg"><?php esc_html_e( 'This game needs JavaScript.', 'vitalstack' ); ?></p></noscript>
	</section>
	<?php
}

/**
 * The Bug Runner hero (Dev in a green cap and orange hoodie) as a pixel SVG,
 * drawn from the same 16 x 20 rows as assets/js/runner.js.
 *
 * @param int $size Height in px.
 */
function vitalstack_runner_hero_svg( $size = 140 ) {
	$pal  = array( 'K' => '#1b1f27', 'R' => '#0a7d4f', 'w' => '#ffffff', 'H' => '#3a2618', 'S' => '#f4c7a1', 's' => '#dca27c', 'E' => '#1b1f27', 'M' => '#b9534a', 'O' => '#f97316', 'o' => '#c2410c', 'J' => '#2563eb', 'W' => '#ffffff', 'B' => '#64748b', 'q' => '#94a3b8' );
	$rows = array(
		'.............qq.', // a "?" above the cap
		'............q..q',
		'..............q.',
		'.............q..',
		'................',
		'.............q..',
		'......KKKKK.....',
		'....KKRRRRRKK...',
		'...KRRRwwwRRRK..',
		'...KRRRRRRRRRRK.',
		'...KRRRRRRRRRRRK',
		'...KHHSSSSESSK..',
		'...KHSsSSSESSSK.',
		'...KHSSSSSSSSSSK',
		'....KSSSSSMMSSK.',
		'.....KKSSSSSKK..',
		'....KOOOOOOOOK..',
		'...BKOOOOOOOOOK.',
		'...BKOOOOwOOSSK.',
		'...BKOOOOwOOOOK.',
		'...BKoooooooooK.',
		'....KJJJJJJJJK..',
		'....KJJJJJJK....',
		'.....KJJJJK.....',
		'.....KWWWWK.....',
		'.....KKKKKK.....',
	);
	$rects = '';
	foreach ( $rows as $y => $row ) {
		$len = strlen( $row );
		for ( $x = 0; $x < $len; $x++ ) {
			$c = $row[ $x ];
			if ( '.' !== $c && isset( $pal[ $c ] ) ) {
				$rects .= '<rect x="' . $x . '" y="' . $y . '" width="1.05" height="1.05" fill="' . $pal[ $c ] . '"/>';
			}
		}
	}
	$h = absint( $size );
	$w = (int) round( $h * 16 / count( $rows ) );
	return '<svg class="vs-hero" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 ' . count( $rows ) . '" width="' . $w . '" height="' . $h . '" shape-rendering="crispEdges" role="img" aria-label="' . esc_attr__( 'Dev, the Bug Runner hero, looking puzzled', 'vitalstack' ) . '">' . $rects . '</svg>';
}
