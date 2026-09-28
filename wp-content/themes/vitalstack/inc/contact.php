<?php
/**
 * Contact: site email address, a built-in contact form (used when
 * Contact Form 7 is not available), and a sensible "From" address for
 * all site mail.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VITALSTACK_CONTACT_EMAIL', 'contact@vitalstack.co.in' );

function vitalstack_contact_email() {
	$email = get_theme_mod( 'vitalstack_email_general', '' );
	return is_email( $email ) ? $email : VITALSTACK_CONTACT_EMAIL;
}

/**
 * WordPress sends from "wordpress@domain" by default, a mailbox that
 * doesn't exist and hurts deliverability. Use the real contact address
 * unless an SMTP plugin has already set something else.
 */
add_filter(
	'wp_mail_from',
	function ( $from ) {
		return 0 === strpos( $from, 'wordpress@' ) ? vitalstack_contact_email() : $from;
	}
);
add_filter(
	'wp_mail_from_name',
	function ( $name ) {
		return 'WordPress' === $name ? wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) : $name;
	}
);

/**
 * The Contact Form 7 form to show: the one chosen in the Customizer,
 * otherwise the site's first CF7 form. 0 when CF7 isn't active.
 */
function vitalstack_cf7_form_id() {
	if ( ! shortcode_exists( 'contact-form-7' ) ) {
		return 0;
	}
	$id = (int) get_theme_mod( 'vitalstack_cf7_form_id', 0 );
	if ( $id && 'wpcf7_contact_form' === get_post_type( $id ) ) {
		return $id;
	}
	$forms = get_posts(
		array(
			'post_type'      => 'wpcf7_contact_form',
			'posts_per_page' => 1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'fields'         => 'ids',
		)
	);
	return $forms ? (int) $forms[0] : 0;
}

function vitalstack_contact_topics() {
	return array(
		'question' => __( 'Question about a tutorial', 'vitalstack' ),
		'error'    => __( 'Report a mistake', 'vitalstack' ),
		'topic'    => __( 'Suggest a topic', 'vitalstack' ),
		'account'  => __( 'Help with my account', 'vitalstack' ),
		'other'    => __( 'Something else', 'vitalstack' ),
	);
}

/**
 * Built-in contact form markup.
 */
function vitalstack_contact_form() {
	$user = wp_get_current_user();
	?>
	<form class="auth-form contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="vs_contact">
		<input type="hidden" name="redirect_to" value="<?php echo esc_url( get_permalink() ); ?>">
		<?php wp_nonce_field( 'vs_contact', '_vsnonce' ); ?>
		<div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
		<div class="field-row">
			<label class="field">
				<span><?php esc_html_e( 'Your name', 'vitalstack' ); ?></span>
				<input type="text" name="name" autocomplete="name" maxlength="80" required value="<?php echo esc_attr( $user->exists() ? $user->display_name : '' ); ?>">
			</label>
			<label class="field">
				<span><?php esc_html_e( 'Your email', 'vitalstack' ); ?></span>
				<input type="email" name="email" autocomplete="email" required value="<?php echo esc_attr( $user->exists() ? $user->user_email : '' ); ?>">
			</label>
		</div>
		<label class="field">
			<span><?php esc_html_e( 'Topic', 'vitalstack' ); ?></span>
			<select name="topic">
				<?php foreach ( vitalstack_contact_topics() as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label class="field">
			<span><?php esc_html_e( 'Message', 'vitalstack' ); ?></span>
			<textarea name="message" rows="6" maxlength="5000" required></textarea>
		</label>
		<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Send message', 'vitalstack' ); ?></button>
	</form>
	<?php
}

function vitalstack_handle_contact() {
	$back = vitalstack_requested_redirect();
	$back = $back ? $back : vitalstack_page_url( 'contact-us' );
	$fail = function ( $code ) use ( $back ) {
		wp_safe_redirect( add_query_arg( 'vs_msg', $code, $back ) );
		exit;
	};

	if ( ! isset( $_POST['_vsnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_contact' ) ) {
		$fail( 'bad_nonce' );
	}
	if ( ! empty( $_POST['website'] ) ) {
		$fail( 'contact_sent' ); // Bot: pretend it worked.
	}
	if ( vitalstack_rate_limited( 'contact|' . vitalstack_client_ip(), 5, HOUR_IN_SECONDS ) ) {
		$fail( 'rate' );
	}

	$name    = isset( $_POST['name'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST['name'] ) ), 0, 80 ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$message = isset( $_POST['message'] ) ? mb_substr( sanitize_textarea_field( wp_unslash( $_POST['message'] ) ), 0, 5000 ) : '';
	$topics  = vitalstack_contact_topics();
	$topic   = isset( $_POST['topic'] ) ? sanitize_key( $_POST['topic'] ) : 'other';
	$topic   = isset( $topics[ $topic ] ) ? $topic : 'other';

	if ( '' === $name || '' === trim( $message ) ) {
		$fail( 'missing' );
	}
	if ( ! is_email( $email ) ) {
		$fail( 'bad_email' );
	}

	$body = '<p><strong>' . esc_html__( 'From:', 'vitalstack' ) . '</strong> ' . esc_html( $name ) . ' &lt;' . esc_html( $email ) . '&gt;</p>'
		. '<p><strong>' . esc_html__( 'Topic:', 'vitalstack' ) . '</strong> ' . esc_html( $topics[ $topic ] ) . '</p>'
		. ( is_user_logged_in() ? '<p><strong>' . esc_html__( 'Account:', 'vitalstack' ) . '</strong> ' . esc_html__( 'signed-in reader', 'vitalstack' ) . ' (#' . (int) get_current_user_id() . ')</p>' : '' )
		. '<div style="margin-top:16px;padding:14px 16px;background:#f3f5f7;border-radius:8px;white-space:pre-wrap">' . esc_html( $message ) . '</div>'
		. '<p style="color:#6b7280;font-size:13px">' . esc_html__( 'Reply to this email to answer them directly.', 'vitalstack' ) . '</p>';

	$sent = vitalstack_mail(
		vitalstack_contact_email(),
		/* translators: 1: topic, 2: name */
		sprintf( __( '[Contact] %1$s: %2$s', 'vitalstack' ), $topics[ $topic ], $name ),
		$body,
		array(
			// Strip characters that could break or inject into the header.
			'reply_to' => trim( str_replace( array( '<', '>', '"', ',', ';', "\r", "\n" ), '', $name ) ) . ' <' . $email . '>',
			'footer'   => esc_html__( 'Sent from the contact form on', 'vitalstack' ) . ' ' . esc_html( home_url( '/' ) ),
		)
	);

	$fail( $sent ? 'contact_sent' : 'contact_failed' );
}
add_action( 'admin_post_vs_contact', 'vitalstack_handle_contact' );
add_action( 'admin_post_nopriv_vs_contact', 'vitalstack_handle_contact' );
