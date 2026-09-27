</main>

<?php if ( ! vitalstack_is_account_page() ) : ?>
	<section class="subscribe-band">
		<div class="wrap">
			<?php vitalstack_subscribe_box( 'band' ); ?>
		</div>
	</section>
<?php endif; ?>

<footer class="site-footer">
	<div class="wrap footer-grid">
		<div class="footer-brand">
			<a class="logo logo-invert" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php vitalstack_logo(); ?></a>
			<p><?php echo esc_html( get_theme_mod( 'vitalstack_footer_tagline', vitalstack_default( 'footer_tagline' ) ) ); ?></p>
			<?php vitalstack_social_links(); ?>
		</div>

		<div class="footer-col">
			<h2 class="footer-title"><?php esc_html_e( 'Tutorials', 'vitalstack' ); ?></h2>
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

	<div class="wrap footer-bottom">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Tutorials are simplified to help learning; always test code before using it in production.', 'vitalstack' ); ?></p>
		<button type="button" class="back-to-top" data-back-to-top><?php esc_html_e( 'Back to top ↑', 'vitalstack' ); ?></button>
	</div>
</footer>

<div class="cookie-note" id="cookie-note" hidden>
	<p>
		<?php esc_html_e( 'We use cookies for sign-in, basic analytics and to show ads that keep this site free.', 'vitalstack' ); ?>
		<a href="<?php echo esc_url( vitalstack_page_url( 'cookie-policy' ) ); ?>"><?php esc_html_e( 'Learn more', 'vitalstack' ); ?></a>
	</p>
	<button type="button" class="btn btn-primary btn-sm" data-dismiss-cookie><?php esc_html_e( 'Got it', 'vitalstack' ); ?></button>
</div>

<div class="tryit" id="tryit" hidden>
	<div class="tryit-panel" role="dialog" aria-modal="true" aria-labelledby="tryit-title">
		<div class="tryit-head">
			<p id="tryit-title" class="tryit-title"><?php echo vitalstack_icon( 'code', 18 ); // phpcs:ignore ?> <?php esc_html_e( 'Try it yourself', 'vitalstack' ); ?> <span class="tryit-lang"></span></p>
			<div class="tryit-actions">
				<button type="button" class="btn btn-primary btn-sm" data-tryit-run><?php echo vitalstack_icon( 'play', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Run', 'vitalstack' ); ?></button>
				<button type="button" class="btn btn-ghost btn-sm" data-tryit-reset><?php esc_html_e( 'Reset', 'vitalstack' ); ?></button>
				<button type="button" class="icon-btn" data-tryit-close aria-label="<?php esc_attr_e( 'Close editor', 'vitalstack' ); ?>"><?php echo vitalstack_icon( 'close' ); // phpcs:ignore ?></button>
			</div>
		</div>
		<div class="tryit-body">
			<div class="tryit-pane">
				<p class="tryit-pane-label"><?php esc_html_e( 'Code', 'vitalstack' ); ?> <span class="muted"><?php esc_html_e( '(edit me)', 'vitalstack' ); ?></span></p>
				<textarea class="tryit-code" spellcheck="false" autocapitalize="off" autocomplete="off" aria-label="<?php esc_attr_e( 'Code editor', 'vitalstack' ); ?>"></textarea>
			</div>
			<div class="tryit-pane">
				<p class="tryit-pane-label"><?php esc_html_e( 'Result', 'vitalstack' ); ?></p>
				<iframe class="tryit-result" title="<?php esc_attr_e( 'Result', 'vitalstack' ); ?>" sandbox="allow-scripts allow-modals"></iframe>
			</div>
		</div>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
