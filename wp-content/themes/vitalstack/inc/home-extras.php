<?php
/**
 * Home page extras (4.13.3).
 *
 * A playful sample certificate for the home page. It is deliberately not the
 * real certificate design (no real seal, pattern or signature), so the real
 * one can't be copied from the home page. Real certificates are issued only
 * to signed-in readers with a confirmed email who subscribe to lesson emails
 * (see vitalstack_cert_steps()).
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_cert_teaser( $course ) {
	$dots = '';
	foreach ( array( array( 8, 12, '#34c987' ), array( 88, 8, '#7c4dff' ), array( 94, 62, '#ffd400' ), array( 4, 70, '#ff7b8a' ), array( 70, 90, '#34c987' ), array( 22, 92, '#7c4dff' ) ) as $d ) {
		$dots .= '<i style="left:' . (int) $d[0] . '%;top:' . (int) $d[1] . '%;background:' . esc_attr( $d[2] ) . '"></i>';
	}
	ob_start();
	?>
	<div class="cert-teaser" role="img" aria-label="<?php esc_attr_e( 'Sample certificate preview. Your real certificate unlocks after you sign in and subscribe.', 'vitalstack' ); ?>">
		<div class="ct-paper">
			<span class="ct-ribbon"><?php esc_html_e( 'Sample', 'vitalstack' ); ?></span>
			<span class="ct-confetti" aria-hidden="true"><?php echo $dots; // phpcs:ignore WordPress.Security.EscapeOutput -- built above ?></span>
			<p class="ct-brand">🎓 <?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
			<p class="ct-title"><?php esc_html_e( 'Certificate of Completion', 'vitalstack' ); ?></p>
			<p class="ct-small"><?php esc_html_e( 'This is to certify that', 'vitalstack' ); ?></p>
			<p class="ct-name"><span><?php esc_html_e( 'Your Name Here', 'vitalstack' ); ?></span></p>
			<p class="ct-small"><?php esc_html_e( 'has completed the course', 'vitalstack' ); ?></p>
			<p class="ct-course"><?php echo esc_html( $course ); ?></p>
			<div class="ct-foot" aria-hidden="true"><span class="ct-line"></span><span class="ct-medal">⭐</span><span class="ct-line"></span></div>
		</div>
		<p class="ct-lock">🔒 <?php esc_html_e( 'Sign in and subscribe to unlock yours', 'vitalstack' ); ?></p>
	</div>
	<?php
	return ob_get_clean();
}
