<?php
/**
 * Sign-up gift: the "30-Day Java Interview Plan" PDF, downloadable by any
 * signed-in reader from /account/?vs_gift=java-plan. The file lives in
 * data/gifts/ (closed to direct requests by its .htaccess) and is rebuilt
 * with tools/build_gift.py.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Gift files: key => array( file, download name, title ).
 */
function vitalstack_gifts() {
	return array(
		'java-plan' => array(
			'file'  => 'java-30-day-interview-plan.pdf',
			'name'  => 'VitalStack-30-Day-Java-Interview-Plan.pdf',
			'title' => __( '30-Day Java Interview Plan', 'vitalstack' ),
		),
	);
}

function vitalstack_gift_url( $key = 'java-plan' ) {
	return vitalstack_account_url( array( 'vs_gift' => $key ) );
}

/**
 * Serves a gift to signed-in readers; guests are sent to sign up first and
 * come straight back to the download.
 */
function vitalstack_serve_gift() {
	if ( ! vitalstack_is_account_page() || ! isset( $_GET['vs_gift'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- read-only download
		return;
	}
	$key   = sanitize_key( wp_unslash( $_GET['vs_gift'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
	$gifts = vitalstack_gifts();
	if ( ! isset( $gifts[ $key ] ) ) {
		return;
	}
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect(
			vitalstack_account_url(
				array(
					'tab'         => 'register',
					'subscribe'   => 1,
					'redirect_to' => rawurlencode( vitalstack_gift_url( $key ) ),
				)
			)
		);
		exit;
	}
	$file = VITALSTACK_DIR . '/data/gifts/' . $gifts[ $key ]['file'];
	if ( ! is_readable( $file ) ) {
		return;
	}
	update_user_meta( get_current_user_id(), 'vs_gift_' . $key, time() );
	nocache_headers();
	header( 'Content-Type: application/pdf' );
	header( 'Content-Disposition: attachment; filename="' . $gifts[ $key ]['name'] . '"' );
	header( 'Content-Length: ' . filesize( $file ) );
	header( 'X-Robots-Tag: noindex' );
	readfile( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	exit;
}
add_action( 'template_redirect', 'vitalstack_serve_gift', 1 );

/**
 * "Your free gift" panel on the reader dashboard.
 */
function vitalstack_gift_panel() {
	$gift = vitalstack_gifts()['java-plan'];
	?>
	<section class="panel gift-panel">
		<p class="gift-tag">🎁 <?php esc_html_e( 'Your free gift', 'vitalstack' ); ?></p>
		<h2><?php echo esc_html( $gift['title'] ); ?></h2>
		<p class="muted"><?php esc_html_e( 'A day-by-day plan for Java interviews and placements: core Java, OOP, SQL, Spring Boot and DSA, with a lesson, practice and a mock test for every day.', 'vitalstack' ); ?></p>
		<a class="btn btn-primary btn-sm" href="<?php echo esc_url( vitalstack_gift_url() ); ?>"><?php esc_html_e( 'Download PDF', 'vitalstack' ); ?></a>
	</section>
	<?php
}
