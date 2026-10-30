<?php
/**
 * /premium/<track>/: the syllabus of a premium career track.
 * Included by vitalstack_premium_template() with $t (track) and $slug set.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

$vs_has       = vitalstack_user_has_premium( $slug );
$vs_waiting   = is_user_logged_in() && get_user_meta( get_current_user_id(), 'vs_waitlist_' . $slug, true );
$vs_free_mods = array_filter(
	$t['modules'],
	function ( $m ) {
		return ! empty( $m['path'] );
	}
);
$vs_lessons   = 0;
foreach ( $t['modules'] as $vs_m ) {
	$vs_lessons += empty( $vs_m['path'] ) ? count( $vs_m['lessons'] ) : vitalstack_premium_free_count( $vs_m['path'] );
}
?>
<section class="premium-hero">
	<div class="wrap premium-hero-inner">
		<div class="premium-hero-text">
			<p class="premium-badge"><?php echo function_exists( 'vitalstack_pay_ready' ) && vitalstack_pay_ready() ? esc_html__( 'Premium career track', 'vitalstack' ) : esc_html__( 'Premium career track · Coming soon', 'vitalstack' ); ?></p>
			<h1><span aria-hidden="true"><?php echo esc_html( $t['icon'] ); ?></span> <?php echo esc_html( sprintf( /* translators: %s: track */ __( '%s Career Track', 'vitalstack' ), $t['title'] ) ); ?></h1>
			<p class="premium-lead"><?php echo esc_html( $t['tagline'] ); ?></p>
			<ul class="premium-facts">
				<li><b><?php echo (int) count( $t['modules'] ); ?></b> <?php esc_html_e( 'modules', 'vitalstack' ); ?></li>
				<li><b><?php echo (int) $vs_lessons; ?>+</b> <?php esc_html_e( 'lessons', 'vitalstack' ); ?></li>
				<li><b><?php echo (int) count( $vs_free_mods ); ?></b> <?php esc_html_e( 'modules free today', 'vitalstack' ); ?></li>
				<li><b>🎓</b> <?php esc_html_e( 'certificate', 'vitalstack' ); ?></li>
			</ul>
			<?php vitalstack_flash(); ?>
			<div class="premium-cta">
				<?php if ( $vs_has ) : ?>
					<p class="premium-has">✅ <?php esc_html_e( 'You have access to this track.', 'vitalstack' ); ?></p>
				<?php elseif ( function_exists( 'vitalstack_pay_ready' ) && vitalstack_pay_ready() ) : ?>
					<a class="btn btn-primary btn-lg" href="<?php echo esc_url( vitalstack_pricing_url() . '#plan-' . $slug ); ?>"><?php esc_html_e( 'Unlock this track', 'vitalstack' ); ?></a>
					<p class="premium-small"><?php esc_html_e( 'Pay in ₹ with UPI or cards, or in $ worldwide. One-time payment.', 'vitalstack' ); ?></p>
				<?php elseif ( $vs_waiting ) : ?>
					<p class="premium-has">✅ <?php esc_html_e( 'You’re on the waitlist. We’ll email you when it opens.', 'vitalstack' ); ?></p>
				<?php else : ?>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="vs_waitlist">
						<input type="hidden" name="track" value="<?php echo esc_attr( $slug ); ?>">
						<?php if ( is_user_logged_in() ) : ?>
							<?php wp_nonce_field( 'vs_waitlist_' . $slug, '_vsnonce' ); ?>
						<?php endif; ?>
						<button type="submit" class="btn btn-primary btn-lg"><?php esc_html_e( 'Join the waitlist (free)', 'vitalstack' ); ?></button>
					</form>
					<p class="premium-small"><?php esc_html_e( 'Get the early-bird price when it opens. Start the free modules today.', 'vitalstack' ); ?></p>
					<?php if ( function_exists( 'vitalstack_pricing_url' ) ) : ?>
						<p class="premium-small"><a href="<?php echo esc_url( vitalstack_pricing_url() ); ?>"><?php esc_html_e( 'See launch prices', 'vitalstack' ); ?> ❯</a></p>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="premium-hero-bot" aria-hidden="true"><?php echo vitalstack_mascot( 'cheer', 150, '', 'gen-ai-developer' === $slug ? 'chip' : 'dev' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></div>
	</div>
</section>

<section class="section">
	<div class="wrap premium-body">
		<div class="premium-main">
			<h2><?php esc_html_e( 'What you will be able to do', 'vitalstack' ); ?></h2>
			<ul class="premium-outcomes">
				<?php foreach ( $t['outcomes'] as $vs_o ) : ?>
					<li><?php echo esc_html( $vs_o ); ?></li>
				<?php endforeach; ?>
			</ul>

			<h2><?php esc_html_e( 'Syllabus', 'vitalstack' ); ?></h2>
			<ol class="premium-modules">
				<?php
				foreach ( $t['modules'] as $vs_i => $vs_m ) :
					$vs_free = ! empty( $vs_m['path'] );
					$vs_term = $vs_free ? get_term_by( 'slug', $vs_m['path'], 'tutorial_category' ) : null;
					$vs_n    = $vs_free ? vitalstack_premium_free_count( $vs_m['path'] ) : count( $vs_m['lessons'] );
					?>
					<li class="premium-module <?php echo $vs_free ? 'is-free' : 'is-locked'; ?>">
						<details <?php echo 0 === $vs_i ? 'open' : ''; ?>>
							<summary>
								<span class="premium-module-num"><?php echo (int) $vs_i + 1; ?></span>
								<span class="premium-module-title"><?php echo esc_html( $vs_m['title'] ); ?></span>
								<span class="premium-module-tag"><?php echo $vs_free ? '✅ ' . esc_html__( 'Free now', 'vitalstack' ) : '🔒 ' . esc_html__( 'Premium', 'vitalstack' ); ?></span>
							</summary>
							<div class="premium-module-body">
								<?php if ( $vs_free ) : ?>
									<p><?php echo esc_html( $vs_m['about'] ); ?></p>
									<?php if ( $vs_term && $vs_n ) : ?>
										<a class="btn btn-ghost btn-sm" href="<?php echo esc_url( get_term_link( $vs_term ) ); ?>"><?php echo esc_html( sprintf( /* translators: %d: lessons */ _n( 'Start the free course (%d lesson)', 'Start the free course (%d lessons)', $vs_n, 'vitalstack' ), $vs_n ) ); ?> ❯</a>
									<?php else : ?>
										<p class="muted small"><?php esc_html_e( 'Free lessons are being published. Check back soon.', 'vitalstack' ); ?></p>
									<?php endif; ?>
								<?php else : ?>
									<ul class="premium-lessons">
										<?php foreach ( $vs_m['lessons'] as $vs_l ) : ?>
											<li><span aria-hidden="true">🔒</span> <?php echo esc_html( $vs_l ); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</div>
						</details>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>

		<aside class="premium-side">
			<div class="panel">
				<h2><?php esc_html_e( 'Who it’s for', 'vitalstack' ); ?></h2>
				<p><?php echo esc_html( $t['for'] ); ?></p>
				<h2><?php esc_html_e( 'How it works', 'vitalstack' ); ?></h2>
				<ol class="premium-steps">
					<li><?php esc_html_e( 'Start the free modules today.', 'vitalstack' ); ?></li>
					<li><?php esc_html_e( 'Join the waitlist to hear first when premium opens.', 'vitalstack' ); ?></li>
					<li><?php esc_html_e( 'Unlock the rest, build the projects and earn your certificate.', 'vitalstack' ); ?></li>
				</ol>
				<p class="muted small"><?php esc_html_e( 'Every lesson has worked examples, live animations, quizzes and practice, like our free courses.', 'vitalstack' ); ?></p>
			</div>
		</aside>
	</div>
</section>
