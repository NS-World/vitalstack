<?php
/**
 * Template Name: Contact Page
 *
 * Form priority: a [contact-form-7] shortcode in the page content, then the
 * Contact Form 7 form (Customizer ID or the site's first CF7 form), then the
 * theme's built-in form. So the page always has a working form.
 *
 * @package VitalStack
 */

get_header();

$vs_email = vitalstack_contact_email();

while ( have_posts() ) :
	the_post();
	$vs_content      = get_the_content();
	$vs_has_cf7_code = false !== strpos( $vs_content, '[contact-form-7' );
	$vs_form_id      = $vs_has_cf7_code ? 0 : vitalstack_cf7_form_id();
	?>
	<header class="page-hero">
		<div class="container narrow">
			<p class="eyebrow"><?php esc_html_e( 'Contact', 'vitalstack' ); ?></p>
			<h1 class="page-title"><?php the_title(); ?></h1>
			<p class="page-desc"><?php esc_html_e( 'Found a mistake, have a question about a tutorial, or want us to cover a topic? Send a message and we will get back to you.', 'vitalstack' ); ?></p>
		</div>
	</header>

	<section class="section section-tight">
		<div class="container narrow contact-grid">
			<div class="contact-main">
				<div class="form-card">
					<h2 class="form-card-title"><?php esc_html_e( 'Send us a message', 'vitalstack' ); ?></h2>
					<?php
					if ( $vs_has_cf7_code ) {
						the_content();
					} elseif ( $vs_form_id ) {
						echo do_shortcode( '[contact-form-7 id="' . $vs_form_id . '"]' ); // phpcs:ignore WordPress.Security.EscapeOutput
					} else {
						vitalstack_contact_form();
					}
					?>
				</div>
			</div>
			<aside class="contact-side">
				<div class="info-card">
					<span class="path-icon tone-green"><?php echo vitalstack_icon( 'mail', 20 ); // phpcs:ignore ?></span>
					<h2><?php esc_html_e( 'Email us directly', 'vitalstack' ); ?></h2>
					<p><a href="mailto:<?php echo esc_attr( antispambot( $vs_email ) ); ?>"><?php echo esc_html( antispambot( $vs_email ) ); ?></a></p>
					<p class="muted"><?php esc_html_e( 'We usually reply within 2 working days.', 'vitalstack' ); ?></p>
				</div>
				<div class="info-card">
					<h2><?php esc_html_e( 'Good reasons to write', 'vitalstack' ); ?></h2>
					<ul>
						<li><?php esc_html_e( 'An error or outdated step in a guide', 'vitalstack' ); ?></li>
						<li><?php esc_html_e( 'A question about a tutorial', 'vitalstack' ); ?></li>
						<li><?php esc_html_e( 'A topic you want explained', 'vitalstack' ); ?></li>
						<li><?php esc_html_e( 'Help with your VitalStack account', 'vitalstack' ); ?></li>
					</ul>
				</div>
			</aside>
		</div>
	</section>
	<?php
endwhile;

get_footer();
