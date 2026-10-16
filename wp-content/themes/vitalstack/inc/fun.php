<?php
/**
 * The playful layer: scripts for XP, the mascot, live visualizers and brain
 * games, the header XP chip and the homepage progress card.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * URL of the Brain Games page (created by VitalStack Setup), or ''.
 */
function vitalstack_games_url() {
	$page = get_page_by_path( 'games' );
	return ( $page && 'publish' === $page->post_status ) ? get_permalink( $page ) : '';
}

/**
 * URL of the interview problems page, or ''.
 */
function vitalstack_problems_url() {
	$page = get_page_by_path( 'dsa-problems' );
	return ( $page && 'publish' === $page->post_status ) ? get_permalink( $page ) : '';
}

/**
 * Whether the current request shows a live visualizer.
 */
function vitalstack_needs_viz() {
	if ( is_front_page() || is_page_template( 'page-games.php' ) ) {
		return true;
	}
	if ( is_singular() ) {
		$post = get_queried_object();
		return $post && false !== strpos( (string) $post->post_content, 'vs-viz' );
	}
	return false;
}

function vitalstack_fun_assets() {
	wp_enqueue_script( 'vitalstack-fun', VITALSTACK_URI . '/assets/js/fun.js', array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	if ( vitalstack_needs_viz() ) {
		wp_enqueue_script( 'vitalstack-viz', VITALSTACK_URI . '/assets/js/viz.js', array( 'vitalstack-fun' ), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
	if ( is_page_template( 'page-games.php' ) ) {
		wp_enqueue_script( 'vitalstack-games', VITALSTACK_URI . '/assets/js/games.js', array( 'vitalstack-fun' ), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}

	$fun = array(
		'loggedIn'  => is_user_logged_in(),
		'accountUrl' => vitalstack_account_url( array( 'tab' => 'register' ) ),
		'gamesUrl'  => vitalstack_games_url(),
		'mascot'    => array(
			'happy' => vitalstack_mascot( 'happy', 90 ),
			'cheer' => vitalstack_mascot( 'cheer', 90 ),
			'think' => vitalstack_mascot( 'think', 90 ),
			'wow'   => vitalstack_mascot( 'wow', 90 ),
		),
	);
	if ( is_user_logged_in() ) {
		$fun['xpUrl'] = esc_url_raw( rest_url( 'vitalstack/v1/xp' ) );
		$fun['nonce'] = wp_create_nonce( 'wp_rest' );
		$fun['xp']    = vitalstack_xp_stats( get_current_user_id() );
	}
	wp_localize_script( 'vitalstack-fun', 'vitalstackFun', $fun );
}
add_action( 'wp_enqueue_scripts', 'vitalstack_fun_assets', 20 );

/**
 * Small "🔥 streak · ⚡ XP" chip in the header. Guests see it once they have XP.
 */
function vitalstack_xp_chip() {
	$stats = is_user_logged_in() ? vitalstack_xp_stats( get_current_user_id() ) : null;
	?>
	<a class="xp-chip" href="<?php echo esc_url( vitalstack_account_url() ); ?>" data-xp-chip<?php echo $stats ? '' : ' hidden'; ?> title="<?php esc_attr_e( 'Your streak and XP', 'vitalstack' ); ?>">
		<span class="xp-chip-streak" aria-label="<?php esc_attr_e( 'Day streak', 'vitalstack' ); ?>">🔥 <b data-xp="streak"><?php echo $stats ? (int) $stats['streak'] : 0; ?></b></span>
		<span class="xp-chip-xp" aria-label="<?php esc_attr_e( 'XP', 'vitalstack' ); ?>">⚡ <b data-xp="xp"><?php echo $stats ? (int) $stats['xp'] : 0; ?></b></span>
	</a>
	<?php
}

/**
 * Homepage hero card: XP, level, week XP and streak, plus a rotating
 * "brain game" and "visualizer" slide.
 */
function vitalstack_hero_card() {
	$stats = is_user_logged_in() ? vitalstack_xp_stats( get_current_user_id() ) : array(
		'xp'            => 0,
		'week'          => 0,
		'streak'        => 0,
		'level'         => 1,
		'levelProgress' => 0,
	);
	$games = vitalstack_games_url();
	$dsa   = get_term_by( 'slug', 'dsa', 'tutorial_category' );
	$dsa   = $dsa && $dsa->count ? get_term_link( $dsa ) : '';
	?>
	<div class="hero-card" data-hero-card>
		<div class="hero-card-mascot"><?php echo vitalstack_mascot( 'happy', 86 ); // phpcs:ignore WordPress.Security.EscapeOutput -- built from fixed markup ?></div>
		<?php if ( ! is_user_logged_in() ) : ?>
			<span class="hero-card-lock" title="<?php esc_attr_e( 'Sign in to save your progress', 'vitalstack' ); ?>"><?php echo vitalstack_icon( 'lock', 14 ); // phpcs:ignore ?></span>
		<?php endif; ?>
		<div class="hero-slides">
			<div class="hero-slide is-active">
				<p class="hero-xp">⚡ <span data-xp="xp" data-count><?php echo (int) $stats['xp']; ?></span></p>
				<p class="hero-card-title"><?php esc_html_e( 'Keep learning. Keep leveling up.', 'vitalstack' ); ?></p>
				<div class="hero-level">
					<span><?php esc_html_e( 'Level', 'vitalstack' ); ?> <b data-xp="level"><?php echo (int) $stats['level']; ?></b></span>
					<span class="hero-level-bar"><span data-xp="levelBar" style="width:<?php echo esc_attr( round( $stats['levelProgress'] * 100 ) ); ?>%"></span></span>
				</div>
				<div class="hero-stats">
					<div><span class="hero-stat-label"><?php esc_html_e( 'XP this week', 'vitalstack' ); ?></span><b data-xp="week"><?php echo (int) $stats['week']; ?></b></div>
					<div><span class="hero-flame" aria-hidden="true">🔥</span><b data-xp="streak"><?php echo (int) $stats['streak']; ?></b><span class="hero-stat-label"><?php esc_html_e( 'Day streak', 'vitalstack' ); ?></span></div>
				</div>
			</div>
			<div class="hero-slide">
				<p class="hero-slide-emoji" aria-hidden="true">🧩</p>
				<p class="hero-card-title"><?php esc_html_e( 'Tired? Take a 2-minute brain break', 'vitalstack' ); ?></p>
				<p class="hero-slide-text"><?php esc_html_e( 'Binary Hunt, Bracket Blitz, Big-O Snap, Tower of Hanoi… tiny games that secretly teach algorithms.', 'vitalstack' ); ?></p>
				<?php if ( $games ) : ?>
					<a class="btn btn-soft" href="<?php echo esc_url( $games ); ?>"><?php esc_html_e( 'Play a game', 'vitalstack' ); ?> ❯</a>
				<?php endif; ?>
			</div>
			<div class="hero-slide">
				<p class="hero-slide-emoji" aria-hidden="true">🎬</p>
				<p class="hero-card-title"><?php esc_html_e( 'Watch algorithms move', 'vitalstack' ); ?></p>
				<p class="hero-slide-text"><?php esc_html_e( 'Every DSA pattern comes with a live, step-by-step animation you can play, pause and replay with your own input.', 'vitalstack' ); ?></p>
				<?php if ( $dsa ) : ?>
					<a class="btn btn-soft" href="<?php echo esc_url( $dsa ); ?>"><?php esc_html_e( 'Start DSA', 'vitalstack' ); ?> ❯</a>
				<?php endif; ?>
			</div>
		</div>
		<?php if ( is_user_logged_in() ) : ?>
			<a class="btn btn-primary btn-block btn-lg" href="<?php echo esc_url( vitalstack_account_url() ); ?>"><?php esc_html_e( 'See my progress', 'vitalstack' ); ?></a>
		<?php elseif ( vitalstack_accounts_enabled() ) : ?>
			<a class="btn btn-primary btn-block btn-lg" href="<?php echo esc_url( vitalstack_account_url() ); ?>"><?php esc_html_e( 'Sign in', 'vitalstack' ); ?></a>
		<?php endif; ?>
		<div class="hero-dots" role="tablist" aria-label="<?php esc_attr_e( 'Card slides', 'vitalstack' ); ?>">
			<button type="button" class="is-active" aria-label="1"></button><button type="button" aria-label="2"></button><button type="button" aria-label="3"></button>
		</div>
	</div>
	<?php
}
