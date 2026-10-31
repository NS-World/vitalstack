<?php
/**
 * /pricing/: premium plans in ₹ and $. Included by vitalstack_pricing_template().
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

$vs_plans  = vitalstack_pay_plans();
$vs_action = vitalstack_pay_action();
$vs_tracks = vitalstack_premium_tracks();
$vs_refund = (int) vitalstack_pay_settings()['refund_days'];
$vs_signin = vitalstack_account_url(
	array(
		'tab'         => 'register',
		'redirect_to' => vitalstack_pricing_url(),
	)
);
// The yearly bundle is only called cheaper when the prices really make it so.
if ( $vs_plans['all-year']['inr'] < $vs_plans['data-scientist']['inr'] + $vs_plans['gen-ai-developer']['inr'] ) {
	$vs_save = $vs_plans['data-scientist']['inr'] + $vs_plans['gen-ai-developer']['inr'] - $vs_plans['all-year']['inr'];
}
$vs_order = array( 'all-month', 'data-scientist', 'all-year', 'gen-ai-developer' );
$vs_feats = array(
	'all-month'        => array( __( 'Both career tracks', 'vitalstack' ), __( 'All premium lessons, quizzes and projects', 'vitalstack' ), __( 'Good for a quick sprint before interviews', 'vitalstack' ) ),
	'data-scientist'   => array( __( '13 modules, beginner to job-ready', 'vitalstack' ), __( 'Statistics, ML, SQL, Power BI', 'vitalstack' ), __( '6 portfolio projects + certificate', 'vitalstack' ) ),
	'all-year'         => array( __( 'Data Scientist + Gen AI Developer', 'vitalstack' ), __( 'Every new premium module this year', 'vitalstack' ), __( 'Both certificates', 'vitalstack' ) ),
	'gen-ai-developer' => array( __( '12 modules, Python to AI apps', 'vitalstack' ), __( 'LLMs, RAG, AI agents, deployment', 'vitalstack' ), __( 'Real AI projects + certificate', 'vitalstack' ) ),
);
if ( ! empty( $vs_save ) ) {
	/* translators: %s: amount saved */
	$vs_feats['all-year'][] = sprintf( __( 'Save %s vs buying both tracks', 'vitalstack' ), vitalstack_pay_price_label( $vs_save, 'inr' ) );
}
?>
<section class="pricing-hero">
	<div class="wrap pricing-hero-inner">
		<div>
			<p class="premium-badge">⭐ <?php esc_html_e( 'VitalStack Premium', 'vitalstack' ); ?></p>
			<h1><?php esc_html_e( 'Learn the skills companies are hiring for right now', 'vitalstack' ); ?></h1>
			<p class="premium-lead"><?php esc_html_e( 'Data science and Gen AI, explained step by step with live animations you can play with. Build real projects, earn a certificate, and walk into interviews ready.', 'vitalstack' ); ?></p>
			<?php vitalstack_flash(); ?>
			<?php if ( vitalstack_pay_demo_for() ) : ?>
				<p class="pricing-soon pricing-demo">🧪 <?php esc_html_e( 'Practice checkout is on: only you (and your test emails) see these Buy buttons. No real money moves.', 'vitalstack' ); ?></p>
			<?php elseif ( 'waitlist' === $vs_action ) : ?>
				<p class="pricing-soon">🚀 <?php esc_html_e( 'Premium opens soon. These are the launch prices: join the waitlist to be first in.', 'vitalstack' ); ?></p>
			<?php endif; ?>
		</div>
		<div class="pricing-hero-bot" aria-hidden="true"><?php echo vitalstack_mascot( 'cheer', 140, '', 'dev' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></div>
	</div>
</section>

<section class="section pricing-trailer">
	<div class="wrap trailer-wrap"><?php vitalstack_premium_trailer( 'pricing' ); ?></div>
</section>

<section class="section pricing" data-pay data-action="<?php echo esc_attr( $vs_action ); ?>"<?php if ( 'buy' === $vs_action ) : ?> data-api="<?php echo esc_url( rest_url( 'vitalstack/v1/pay/' ) ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_rest' ) ); ?>"<?php endif; ?>>
	<div class="wrap">
		<div class="pricing-cur" role="radiogroup" aria-label="<?php esc_attr_e( 'Currency', 'vitalstack' ); ?>">
			<button type="button" role="radio" aria-checked="true" data-cur="inr">🇮🇳 ₹ INR</button>
			<button type="button" role="radio" aria-checked="false" data-cur="usd">🌍 $ USD</button>
		</div>
		<p class="pricing-cur-note muted small" data-cur-note><?php esc_html_e( 'Pay with UPI, cards, net banking or wallets.', 'vitalstack' ); ?></p>

		<div class="pricing-grid">
			<?php
			foreach ( $vs_order as $vs_id ) :
				$vs_p = $vs_plans[ $vs_id ];
				?>
				<article class="plan<?php echo empty( $vs_p['best'] ) ? '' : ' is-best'; ?>" id="plan-<?php echo esc_attr( $vs_id ); ?>">
					<?php if ( ! empty( $vs_p['best'] ) ) : ?>
						<p class="plan-ribbon"><?php esc_html_e( 'Best value', 'vitalstack' ); ?></p>
					<?php endif; ?>
					<h2><?php echo esc_html( $vs_p['title'] ); ?></h2>
					<p class="plan-blurb"><?php echo esc_html( $vs_p['blurb'] ); ?></p>
					<p class="plan-price">
						<span class="plan-amount" data-inr="<?php echo esc_attr( vitalstack_pay_price_label( $vs_p['inr'], 'inr' ) ); ?>" data-usd="<?php echo esc_attr( vitalstack_pay_price_label( $vs_p['usd'], 'usd' ) ); ?>"><?php echo esc_html( vitalstack_pay_price_label( $vs_p['inr'], 'inr' ) ); ?></span>
						<span class="plan-period">/ <?php echo esc_html( $vs_p['period'] ); ?></span>
					</p>
					<p class="plan-alt muted small" data-alt-inr="<?php echo esc_attr( sprintf( /* translators: %s: price */ __( '%s outside India', 'vitalstack' ), vitalstack_pay_price_label( $vs_p['usd'], 'usd' ) ) ); ?>" data-alt-usd="<?php echo esc_attr( sprintf( /* translators: %s: price */ __( '%s in India', 'vitalstack' ), vitalstack_pay_price_label( $vs_p['inr'], 'inr' ) ) ); ?>"><?php echo esc_html( sprintf( /* translators: %s: price */ __( '%s outside India', 'vitalstack' ), vitalstack_pay_price_label( $vs_p['usd'], 'usd' ) ) ); ?></p>
					<ul class="plan-feats">
						<?php foreach ( $vs_feats[ $vs_id ] as $vs_f ) : ?>
							<li><?php echo esc_html( $vs_f ); ?></li>
						<?php endforeach; ?>
					</ul>
					<div class="plan-cta">
						<?php if ( 'buy' === $vs_action ) : ?>
							<button type="button" class="btn <?php echo empty( $vs_p['best'] ) ? 'btn-ghost' : 'btn-primary'; ?> btn-block" data-buy="<?php echo esc_attr( $vs_id ); ?>"><?php esc_html_e( 'Get it now', 'vitalstack' ); ?></button>
						<?php elseif ( 'signin' === $vs_action ) : ?>
							<a class="btn <?php echo empty( $vs_p['best'] ) ? 'btn-ghost' : 'btn-primary'; ?> btn-block" href="<?php echo esc_url( $vs_signin ); ?>"><?php esc_html_e( 'Create free account to buy', 'vitalstack' ); ?></a>
						<?php else : ?>
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
								<input type="hidden" name="action" value="vs_waitlist">
								<input type="hidden" name="track" value="<?php echo esc_attr( $vs_p['tracks'][0] ); ?>">
								<?php if ( is_user_logged_in() ) : ?>
									<?php wp_nonce_field( 'vs_waitlist_' . $vs_p['tracks'][0], '_vsnonce' ); ?>
								<?php endif; ?>
								<button type="submit" class="btn <?php echo empty( $vs_p['best'] ) ? 'btn-ghost' : 'btn-primary'; ?> btn-block"><?php esc_html_e( 'Join the waitlist', 'vitalstack' ); ?></button>
							</form>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<p class="pay-msg" role="alert" hidden></p>
		<p class="pricing-trust">
			<span>🔒 <?php esc_html_e( 'Secure checkout by Razorpay', 'vitalstack' ); ?></span>
			<span>💳 <?php esc_html_e( 'We never see your card or UPI PIN', 'vitalstack' ); ?></span>
			<span>🔁 <?php esc_html_e( 'One-time payment, no auto-renewal', 'vitalstack' ); ?></span>
			<?php if ( $vs_refund ) : ?>
				<span>↩️ <?php echo esc_html( sprintf( /* translators: %d: days */ _n( '%d-day refund if it’s not for you', '%d-day refund if it’s not for you', $vs_refund, 'vitalstack' ), $vs_refund ) ); ?></span>
			<?php endif; ?>
		</p>
	</div>
</section>

<section class="section pricing-walk">
	<div class="wrap trailer-wrap">
		<h2 class="section-title"><?php esc_html_e( 'How premium explains code: every line, step by step', 'vitalstack' ); ?></h2>
		<p class="premium-lead"><?php esc_html_e( 'Press Play. Watch the pointer move, the variables change and the output appear. Switch to “Deep dive” for the toppers’ version.', 'vitalstack' ); ?></p>
		<div class="prose"><?php echo vitalstack_walk_demo(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?></div>
	</div>
</section>

<section class="section pricing-why">
	<div class="wrap">
		<h2 class="section-title"><?php esc_html_e( 'Why learners choose Premium', 'vitalstack' ); ?></h2>
		<div class="why-grid">
			<div class="why-card"><span aria-hidden="true">🎬</span><h3><?php esc_html_e( 'See it, don’t just read it', 'vitalstack' ); ?></h3><p><?php esc_html_e( 'Every idea has a live animation: watch a model learn, a query run or a probability change as you move a slider.', 'vitalstack' ); ?></p></div>
			<div class="why-card"><span aria-hidden="true">🧪</span><h3><?php esc_html_e( 'Code that really runs', 'vitalstack' ); ?></h3><p><?php esc_html_e( 'Every example is tested and shows its exact output, so you can trust it and copy it into your own projects.', 'vitalstack' ); ?></p></div>
			<div class="why-card"><span aria-hidden="true">💼</span><h3><?php esc_html_e( 'Projects for your CV', 'vitalstack' ); ?></h3><p><?php esc_html_e( 'Guided projects on real-style data that you can put on GitHub and talk about in interviews.', 'vitalstack' ); ?></p></div>
			<div class="why-card"><span aria-hidden="true">🎓</span><h3><?php esc_html_e( 'Certificate that’s earned', 'vitalstack' ); ?></h3><p><?php esc_html_e( 'Finish the lessons and quizzes to unlock a certificate with your name you can share on LinkedIn.', 'vitalstack' ); ?></p></div>
			<div class="why-card"><span aria-hidden="true">🗣️</span><h3><?php esc_html_e( 'Simple English, zero jargon', 'vitalstack' ); ?></h3><p><?php esc_html_e( 'Made for students and career-switchers from any background. No maths degree needed.', 'vitalstack' ); ?></p></div>
			<div class="why-card"><span aria-hidden="true">🚀</span><h3><?php esc_html_e( 'Always up to date', 'vitalstack' ); ?></h3><p><?php esc_html_e( 'AI moves fast. New modules on the latest tools are added during your plan at no extra cost.', 'vitalstack' ); ?></p></div>
		</div>

		<h2 class="section-title"><?php esc_html_e( 'Free vs Premium', 'vitalstack' ); ?></h2>
		<div class="compare-wrap">
			<table class="compare">
				<thead><tr><th scope="col"></th><th scope="col"><?php esc_html_e( 'Free', 'vitalstack' ); ?></th><th scope="col">⭐ <?php esc_html_e( 'Premium', 'vitalstack' ); ?></th></tr></thead>
				<tbody>
					<tr><th scope="row"><?php esc_html_e( 'All free courses (Python, Java, Web, SQL, DSA, NumPy, Pandas, ML basics, Prompt Engineering)', 'vitalstack' ); ?></th><td>✅</td><td>✅</td></tr>
					<tr><th scope="row"><?php esc_html_e( 'Live animations, quizzes, games, mock tests', 'vitalstack' ); ?></th><td>✅</td><td>✅</td></tr>
					<tr><th scope="row"><?php esc_html_e( 'Statistics & probability for data science', 'vitalstack' ); ?></th><td>—</td><td>✅</td></tr>
					<tr><th scope="row"><?php esc_html_e( 'Advanced ML, deep learning, LLMs, RAG and AI agents', 'vitalstack' ); ?></th><td>—</td><td>✅</td></tr>
					<tr><th scope="row"><?php esc_html_e( 'Guided portfolio projects', 'vitalstack' ); ?></th><td>—</td><td>✅</td></tr>
					<tr><th scope="row"><?php esc_html_e( 'Career track certificate', 'vitalstack' ); ?></th><td>—</td><td>✅</td></tr>
				</tbody>
			</table>
		</div>

		<h2 class="section-title"><?php esc_html_e( 'The career tracks', 'vitalstack' ); ?></h2>
		<div class="why-grid">
			<?php foreach ( $vs_tracks as $vs_slug => $vs_t ) : ?>
				<a class="why-card track-link" href="<?php echo esc_url( vitalstack_premium_url( $vs_slug ) ); ?>"><span aria-hidden="true"><?php echo esc_html( $vs_t['icon'] ); ?></span><h3><?php echo esc_html( $vs_t['title'] ); ?></h3><p><?php echo esc_html( $vs_t['tagline'] ); ?></p><b><?php esc_html_e( 'See the full syllabus', 'vitalstack' ); ?> ❯</b></a>
			<?php endforeach; ?>
		</div>

		<h2 class="section-title"><?php esc_html_e( 'Questions', 'vitalstack' ); ?></h2>
		<div class="faq">
			<details><summary><?php esc_html_e( 'How do I pay?', 'vitalstack' ); ?></summary><p><?php esc_html_e( 'In India: UPI (Google Pay, PhonePe, Paytm), debit or credit card, net banking or wallets, in rupees. Outside India: international cards in US dollars. Payments are handled by Razorpay, a secure payment company regulated in India.', 'vitalstack' ); ?></p></details>
			<details><summary><?php esc_html_e( 'Is it a subscription? Will I be charged again?', 'vitalstack' ); ?></summary><p><?php esc_html_e( 'No. You pay once for the period you choose. Nothing renews automatically. If you buy again before it ends, the new time is added on.', 'vitalstack' ); ?></p></details>
			<details><summary><?php esc_html_e( 'When do I get access?', 'vitalstack' ); ?></summary><p><?php esc_html_e( 'Right after the payment succeeds. We also email you a receipt. Your access is shown on your account page.', 'vitalstack' ); ?></p></details>
			<?php if ( $vs_refund ) : ?>
				<details><summary><?php esc_html_e( 'What if it’s not for me?', 'vitalstack' ); ?></summary><p><?php echo esc_html( sprintf( /* translators: %d: days */ __( 'Write to us within %d days of paying and we will refund you in full. No long forms.', 'vitalstack' ), $vs_refund ) ); ?></p></details>
			<?php endif; ?>
			<details><summary><?php esc_html_e( 'Do I need to know coding already?', 'vitalstack' ); ?></summary><p><?php esc_html_e( 'No. Both tracks start from zero with our free Python lessons, and every step builds on the one before.', 'vitalstack' ); ?></p></details>
			<details><summary><?php esc_html_e( 'Will free lessons stay free?', 'vitalstack' ); ?></summary><p><?php esc_html_e( 'Yes. Everything that is free today stays free. Premium adds the deeper career material on top.', 'vitalstack' ); ?></p></details>
		</div>
	</div>
</section>
