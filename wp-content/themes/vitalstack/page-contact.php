<?php
/**
 * Template Name: Contact Page
 *
 * @package VitalStack
 */

get_header();

$vs_form_id = (int) get_theme_mod( 'vitalstack_cf7_form_id', 0 );
$vs_email   = get_theme_mod( 'vitalstack_email_general', '' ) ?: 'contact@' . wp_parse_url( home_url(), PHP_URL_HOST );

while ( have_posts() ) :
	the_post();
	?>
	<header class="page-hero">
		<div class="container narrow">
			<p class="eyebrow"><?php esc_html_e( 'Contact', 'vitalstack' ); ?></p>
			<h1 class="page-title"><?php the_title(); ?></h1>
			<p class="page-desc"><?php esc_html_e( 'Found a mistake, have a question about a tutorial, or want us to cover a topic? Send a message.', 'vitalstack' ); ?></p>
		</div>
	</header>

	<section class="section section-tight">
		<div class="container narrow contact-grid">
			<div class="contact-main">
				<?php if ( $vs_form_id && shortcode_exists( 'contact-form-7' ) ) : ?>
					<div class="form-card"><?php echo do_shortcode( '[contact-form-7 id="' . $vs_form_id . '"]' ); ?></div>
				<?php elseif ( trim( get_the_content() ) ) : ?>
					<div class="prose"><?php the_content(); ?></div>
				<?php endif; ?>
			</div>
			<aside class="contact-side">
				<div class="info-card">
					<span class="path-icon tone-green"><?php echo vitalstack_icon( 'mail', 20 ); // phpcs:ignore ?></span>
					<h2><?php esc_html_e( 'Email', 'vitalstack' ); ?></h2>
					<p><a href="mailto:<?php echo esc_attr( antispambot( $vs_email ) ); ?>"><?php echo esc_html( antispambot( $vs_email ) ); ?></a></p>
					<p class="muted"><?php esc_html_e( 'We usually reply within 2 working days.', 'vitalstack' ); ?></p>
				</div>
				<div class="info-card">
					<h2><?php esc_html_e( 'Good reasons to write', 'vitalstack' ); ?></h2>
					<ul>
						<li><?php esc_html_e( 'An error or outdated step in a guide', 'vitalstack' ); ?></li>
						<li><?php esc_html_e( 'A question about a tutorial', 'vitalstack' ); ?></li>
						<li><?php esc_html_e( 'A topic you want explained', 'vitalstack' ); ?></li>
					</ul>
				</div>
			</aside>
		</div>
	</section>
	<?php
endwhile;

get_footer();
