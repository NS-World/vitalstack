<?php
/**
 * Bug Runner: an endless runner game with Byte (assets/js/runner.js),
 * featured on the games page and on the 404 page.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_runner_assets() {
	if ( is_404() || is_page_template( 'page-games.php' ) || vitalstack_search_is_empty() ) {
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
			<canvas tabindex="0" role="img" aria-label="<?php esc_attr_e( 'Byte the cat runs along a line of code. Jump over bugs and 500 errors, collect coffee and code coins.', 'vitalstack' ); ?>"></canvas>
			<button type="button" class="btn btn-primary vs-runner-play" data-runner-play>▶ <?php esc_html_e( 'Play', 'vitalstack' ); ?></button>
		</div>
		<p class="vs-runner-msg" data-runner-msg aria-live="polite"><?php esc_html_e( 'Tap the game or press Space / ↑ to jump. Tap again in the air for a double jump.', 'vitalstack' ); ?></p>
		<noscript><p class="vs-runner-msg"><?php esc_html_e( 'This game needs JavaScript.', 'vitalstack' ); ?></p></noscript>
	</section>
	<?php
}

/**
 * A search that found nothing.
 */
function vitalstack_search_is_empty() {
	global $wp_query;
	return is_search() && $wp_query && 0 === (int) $wp_query->found_posts;
}

/**
 * Search with no results: Byte, a speech bubble with the search words, and
 * the Bug Runner game, like the 404 page.
 */
function vitalstack_search_empty_fun() {
	$query = get_search_query( false );
	?>
	<div class="search-empty">
		<div class="nf-hero">
			<div class="nf-bot" aria-hidden="true"><?php echo function_exists( 'vitalstack_mascot' ) ? vitalstack_mascot( 'think', 132, '', 'byte' ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput -- fixed SVG ?></div>
			<p class="nf-bubble">
				<?php
				/* translators: %s: the search words */
				echo esc_html( sprintf( __( 'Meow… I searched every folder for “%s” but found nothing. Try a shorter word, or play while you think!', 'vitalstack' ), wp_html_excerpt( trim( $query ), 60, '…' ) ) );
				?>
			</p>
		</div>
		<?php vitalstack_runner_box( '404' ); ?>
	</div>
	<?php
}

