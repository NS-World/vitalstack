<?php
/**
 * Premium trailer: a 25-second animated story, made of HTML, CSS and the
 * crew characters (no video file to download, so it costs almost nothing
 * to load). Shown on /pricing/ and in the homepage premium band.
 *
 * It plays when it scrolls into view, loops, and can be paused. Readers who
 * prefer reduced motion get the scenes as a static, readable strip.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lowest monthly price, for the last scene.
 */
function vitalstack_trailer_price() {
	if ( ! function_exists( 'vitalstack_pay_plans' ) ) {
		return '';
	}
	$p = vitalstack_pay_plans();
	return isset( $p['all-month'] ) ? vitalstack_pay_price_label( $p['all-month']['inr'], 'inr' ) : '';
}

/**
 * The trailer markup.
 *
 * @param string $context "pricing" or "home" (changes the last button only).
 */
function vitalstack_premium_trailer( $context = 'home' ) {
	$cta_url = function_exists( 'vitalstack_pricing_url' ) ? vitalstack_pricing_url() : home_url( '/' );
	if ( 'pricing' === $context ) {
		$cta_url = '#plan-all-year';
	}
	$price = vitalstack_trailer_price();
	$char  = function ( $who, $mood, $size ) {
		return vitalstack_character( $who, $mood, $size, 'tr-char' );
	};
	?>
	<figure class="trailer" data-trailer aria-roledescription="<?php esc_attr_e( 'animated trailer', 'vitalstack' ); ?>" aria-label="<?php esc_attr_e( 'VitalStack Premium in 25 seconds', 'vitalstack' ); ?>">
		<div class="tr-stage">
			<section class="tr-scene tr-s1" data-d="3200">
				<div class="tr-cast"><?php echo $char( 'dev', 'happy', 92 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></div>
				<p class="tr-kicker"><?php esc_html_e( 'Meet Dev.', 'vitalstack' ); ?></p>
				<p class="tr-big"><?php esc_html_e( 'He wants to become a Data Scientist.', 'vitalstack' ); ?></p>
				<p class="tr-small"><?php esc_html_e( 'Zero coding. Zero maths fear. Let’s go. 👇', 'vitalstack' ); ?></p>
			</section>

			<section class="tr-scene tr-s2" data-d="3600">
				<p class="tr-step"><span>1</span> <?php esc_html_e( 'Python + NumPy', 'vitalstack' ); ?></p>
				<pre class="tr-code"><code><span class="tr-type">prices * 1.18   # GST on every row</span></code></pre>
				<div class="tr-arr" aria-hidden="true">
					<?php foreach ( array( 100, 250, 80, 400 ) as $i => $v ) : ?>
						<span style="--i:<?php echo (int) $i; ?>"><b><?php echo (int) $v; ?></b><i><?php echo esc_html( number_format( $v * 1.18, 0 ) ); ?></i></span>
					<?php endforeach; ?>
				</div>
				<p class="tr-small"><?php esc_html_e( 'Watch every value change. No loops needed.', 'vitalstack' ); ?></p>
			</section>

			<section class="tr-scene tr-s3" data-d="3600">
				<p class="tr-step"><span>2</span> <?php esc_html_e( 'Pandas: real data', 'vitalstack' ); ?></p>
				<div class="tr-bars" aria-hidden="true">
					<?php foreach ( array( array( 'Pune', 78 ), array( 'Delhi', 92 ), array( 'Surat', 61 ), array( 'Patna', 70 ) ) as $i => $b ) : ?>
						<div style="--v:<?php echo (int) $b[1]; ?>;--i:<?php echo (int) $i; ?>"><span><?php echo esc_html( $b[0] ); ?></span><i></i><b><?php echo (int) $b[1]; ?></b></div>
					<?php endforeach; ?>
				</div>
				<p class="tr-small"><code>df.groupby("city")["marks"].mean()</code></p>
			</section>

			<section class="tr-scene tr-s4" data-d="3600">
				<p class="tr-step"><span>3</span> <?php esc_html_e( 'Statistics you can see', 'vitalstack' ); ?></p>
				<svg class="tr-bell" viewBox="0 0 300 120" aria-hidden="true">
					<path class="tr-tail" d="M226 108 C 240 108 262 109 296 110 L296 112 L226 112 Z"/>
					<path class="tr-curve" d="M4 110 C 70 110 100 12 150 12 C 200 12 230 110 296 110"/>
					<line x1="226" y1="20" x2="226" y2="112" class="tr-mark"/>
				</svg>
				<p class="tr-big tr-p">p = 0.035 → <?php esc_html_e( 'not luck!', 'vitalstack' ); ?></p>
			</section>

			<section class="tr-scene tr-s5" data-d="3600">
				<p class="tr-step"><span>4</span> <?php esc_html_e( 'Machine learning', 'vitalstack' ); ?></p>
				<svg class="tr-ml" viewBox="0 0 300 140" aria-hidden="true">
					<?php foreach ( array( array( 30, 118 ), array( 60, 104 ), array( 85, 96 ), array( 110, 88 ), array( 140, 70 ), array( 165, 66 ), array( 195, 50 ), array( 220, 44 ), array( 250, 28 ), array( 275, 20 ) ) as $i => $pt ) : ?>
						<circle cx="<?php echo (int) $pt[0]; ?>" cy="<?php echo (int) $pt[1]; ?>" r="5" style="--i:<?php echo (int) $i; ?>"/>
					<?php endforeach; ?>
					<line class="tr-fit" x1="20" y1="124" x2="285" y2="16"/>
				</svg>
				<div class="tr-cast tr-cast-sm"><?php echo $char( 'chip', 'wow', 64 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></div>
				<p class="tr-small"><?php esc_html_e( 'model.fit() → the line learns itself.', 'vitalstack' ); ?></p>
			</section>

			<section class="tr-scene tr-s6" data-d="3800">
				<p class="tr-step"><span>5</span> <?php esc_html_e( 'Projects + certificate', 'vitalstack' ); ?></p>
				<div class="tr-cert" aria-hidden="true">
					<span class="tr-cert-title"><?php esc_html_e( 'Certificate of Completion', 'vitalstack' ); ?></span>
					<span class="tr-cert-name">Dev</span>
					<span class="tr-cert-course"><?php esc_html_e( 'Data Scientist Career Track', 'vitalstack' ); ?></span>
					<span class="tr-seal">★</span>
				</div>
				<div class="tr-cast tr-cast-pair"><?php echo $char( 'dev', 'cheer', 70 ) . $char( 'byte', 'cheer', 60 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></div>
			</section>

			<section class="tr-scene tr-s7" data-d="4200">
				<p class="premium-badge">⭐ VitalStack Premium</p>
				<p class="tr-big"><?php esc_html_e( 'Don’t just read it. Watch it happen.', 'vitalstack' ); ?></p>
				<p class="tr-small"><?php esc_html_e( 'Data Scientist + Gen AI Developer career tracks', 'vitalstack' ); ?></p>
				<?php if ( $price ) : ?>
					<p class="tr-price"><?php echo esc_html( sprintf( /* translators: %s: price */ __( 'from %s / month', 'vitalstack' ), $price ) ); ?></p>
				<?php endif; ?>
				<a class="btn btn-primary tr-cta" href="<?php echo esc_url( $cta_url ); ?>"><?php echo 'pricing' === $context ? esc_html__( 'Choose your plan', 'vitalstack' ) : esc_html__( 'See plans', 'vitalstack' ); ?> ❯</a>
			</section>
		</div>
		<figcaption class="tr-bar">
			<button type="button" class="tr-play" data-tr-toggle aria-label="<?php esc_attr_e( 'Pause trailer', 'vitalstack' ); ?>">❚❚</button>
			<span class="tr-dots" aria-hidden="true"><?php echo str_repeat( '<i></i>', 7 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static ?></span>
			<span class="tr-label"><?php esc_html_e( 'Premium in 25 seconds', 'vitalstack' ); ?></span>
		</figcaption>
	</figure>
	<?php
}
