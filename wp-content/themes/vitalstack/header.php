<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="color-scheme" content="light dark">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'vitalstack' ); ?></a>

<?php if ( is_singular( array( 'post', 'news', 'tutorials' ) ) ) : ?>
	<div class="reading-progress" aria-hidden="true"><span></span></div>
<?php endif; ?>

<header class="site-header">
	<div class="container header-inner">
		<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<?php vitalstack_logo(); ?>
		</a>

		<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'vitalstack' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'menu',
					'depth'          => 2,
					'fallback_cb'    => 'vitalstack_default_menu',
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<button type="button" class="icon-btn" data-open-search aria-label="<?php esc_attr_e( 'Search', 'vitalstack' ); ?>">
				<?php echo vitalstack_icon( 'search' ); // phpcs:ignore ?>
				<kbd class="kbd-hint" aria-hidden="true">/</kbd>
			</button>
			<button type="button" class="icon-btn" data-toggle-theme aria-label="<?php esc_attr_e( 'Toggle dark mode', 'vitalstack' ); ?>">
				<span class="theme-icon-light"><?php echo vitalstack_icon( 'moon' ); // phpcs:ignore ?></span>
				<span class="theme-icon-dark"><?php echo vitalstack_icon( 'sun' ); // phpcs:ignore ?></span>
			</button>
			<?php
			$cta_label = get_theme_mod( 'vitalstack_header_cta_label', __( 'Start learning', 'vitalstack' ) );
			$cta_url   = get_theme_mod( 'vitalstack_header_cta_url', '' ) ?: vitalstack_tutorials_url();
			if ( $cta_label ) :
				?>
				<a class="btn btn-primary btn-sm header-cta" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_label ); ?></a>
			<?php endif; ?>
			<button type="button" class="icon-btn menu-toggle" data-open-drawer aria-controls="drawer" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'vitalstack' ); ?>">
				<?php echo vitalstack_icon( 'menu' ); // phpcs:ignore ?>
			</button>
		</div>
	</div>
</header>

<div class="drawer" id="drawer" hidden>
	<div class="drawer-backdrop" data-close-drawer></div>
	<div class="drawer-panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menu', 'vitalstack' ); ?>">
		<div class="drawer-head">
			<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php vitalstack_logo(); ?></a>
			<button type="button" class="icon-btn" data-close-drawer aria-label="<?php esc_attr_e( 'Close menu', 'vitalstack' ); ?>"><?php echo vitalstack_icon( 'close' ); // phpcs:ignore ?></button>
		</div>
		<?php get_search_form(); ?>
		<nav aria-label="<?php esc_attr_e( 'Mobile', 'vitalstack' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'drawer-menu',
					'depth'          => 2,
					'fallback_cb'    => 'vitalstack_default_menu',
				)
			);
			?>
		</nav>
	</div>
</div>

<div class="search-modal" id="search-modal" hidden>
	<div class="search-modal-backdrop" data-close-search></div>
	<div class="search-modal-panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Search', 'vitalstack' ); ?>">
		<?php get_search_form(); ?>
		<p class="search-modal-hint"><?php esc_html_e( 'Try: JavaScript, AI agents, prompt engineering, SQL', 'vitalstack' ); ?></p>
		<button type="button" class="icon-btn search-modal-close" data-close-search aria-label="<?php esc_attr_e( 'Close search', 'vitalstack' ); ?>"><?php echo vitalstack_icon( 'close' ); // phpcs:ignore ?></button>
	</div>
</div>

<main id="main" class="site-main">
