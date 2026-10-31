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
 * A sample line-by-line walkthrough for /pricing/ (the Bayes one from the
 * Statistics module), so visitors see how premium lessons explain code.
 */
function vitalstack_walk_demo() {
	$code  = "def bayes(prior, hit_rate, false_alarm):\n    true_pos = hit_rate * prior\n    false_pos = false_alarm * (1 - prior)\n    return true_pos / (true_pos + false_pos)\n\np = bayes(prior=0.01, hit_rate=0.99, false_alarm=0.05)\nprint(round(p, 3))";
	$steps = array(
		array( 1, __( 'We define a function with three inputs. Nothing runs yet: Python just remembers the recipe.', 'vitalstack' ), __( 'def creates a function object named bayes. Its body runs only when it is called on line 6.', 'vitalstack' ), array(), '' ),
		array( 6, __( 'Now we call it: 1% of people are sick, the test catches 99%, and 5% of healthy people get a false alarm.', 'vitalstack' ), '', array( 'prior' => '0.01', 'hit_rate' => '0.99', 'false_alarm' => '0.05' ), '' ),
		array( 2, __( 'Share of everyone who is sick and tests positive: 0.99 × 0.01.', 'vitalstack' ), __( 'This is P(positive | sick) × P(sick) = P(positive and sick).', 'vitalstack' ), array( 'true_pos' => '0.0099' ), '' ),
		array( 3, __( 'Share who are healthy but test positive: 0.05 × 0.99. Five times more than the real cases!', 'vitalstack' ), __( '1 − prior is P(healthy) = 0.99. This is P(positive and healthy).', 'vitalstack' ), array( 'false_pos' => '0.0495' ), '' ),
		array( 4, __( 'Of all positives, how many are real? 0.0099 ÷ 0.0594 ≈ 0.167.', 'vitalstack' ), __( 'The denominator is P(positive), the evidence. The result is the posterior P(sick | positive).', 'vitalstack' ), array( 'result' => '0.16666666666666669' ), '' ),
		array( 6, __( 'The answer comes back and is stored in p.', 'vitalstack' ), '', array( 'p' => '0.16666666666666669' ), '' ),
		array( 7, __( 'Only 16.7%: a positive test for a rare disease is usually a false alarm.', 'vitalstack' ), '', array(), '0.167' ),
	);
	$out = '<div class="vs-walk" data-lang="python" data-title="' . esc_attr__( 'Bayes’ theorem as a function', 'vitalstack' ) . '"><pre class="vs-walk-src"><code class="language-python">' . esc_html( $code ) . '</code></pre><ol class="vs-walk-steps">';
	foreach ( $steps as $s ) {
		$attrs = ' data-line="' . (int) $s[0] . '"';
		$attrs .= $s[2] ? ' data-deep="' . esc_attr( $s[2] ) . '"' : '';
		$attrs .= $s[3] ? ' data-vars="' . esc_attr( wp_json_encode( $s[3] ) ) . '"' : '';
		$attrs .= $s[4] ? ' data-out="' . esc_attr( $s[4] ) . '"' : '';
		$out   .= '<li' . $attrs . '><b>' . esc_html( sprintf( /* translators: %d: line */ __( 'Line %d:', 'vitalstack' ), $s[0] ) ) . '</b> ' . esc_html( $s[1] ) . '</li>';
	}
	return $out . '</ol></div>';
}

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
