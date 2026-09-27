</main>

<footer class="site-footer">
	<div class="container footer-grid">
		<div class="footer-brand">
			<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php vitalstack_logo(); ?></a>
			<p><?php echo esc_html( get_theme_mod( 'vitalstack_footer_tagline', vitalstack_default( 'footer_tagline' ) ) ); ?></p>
			<?php vitalstack_social_links(); ?>
		</div>

		<div class="footer-col">
			<h2 class="footer-title"><?php esc_html_e( 'Learn', 'vitalstack' ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer-cats',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => 'vitalstack_footer_learn_fallback',
				)
			);
			?>
		</div>

		<div class="footer-col">
			<h2 class="footer-title"><?php esc_html_e( 'VitalStack', 'vitalstack' ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer-company',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => 'vitalstack_footer_company_fallback',
				)
			);
			?>
		</div>

		<div class="footer-col">
			<h2 class="footer-title"><?php esc_html_e( 'Legal', 'vitalstack' ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer-legal',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => 'vitalstack_footer_legal_fallback',
				)
			);
			?>
		</div>
	</div>

	<div class="container footer-bottom">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Written for learners, by learners.', 'vitalstack' ); ?></p>
		<button type="button" class="back-to-top" data-back-to-top><?php esc_html_e( 'Back to top ↑', 'vitalstack' ); ?></button>
	</div>
</footer>

<div class="cookie-note" id="cookie-note" hidden>
	<p>
		<?php esc_html_e( 'We use cookies for basic analytics and to show ads that keep this site free.', 'vitalstack' ); ?>
		<a href="<?php echo esc_url( vitalstack_page_url( 'cookie-policy' ) ); ?>"><?php esc_html_e( 'Learn more', 'vitalstack' ); ?></a>
	</p>
	<button type="button" class="btn btn-primary btn-sm" data-dismiss-cookie><?php esc_html_e( 'Got it', 'vitalstack' ); ?></button>
</div>

<?php wp_footer(); ?>
</body>
</html>
