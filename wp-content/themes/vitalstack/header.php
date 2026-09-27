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
	<div class="topbar">
		<div class="wrap topbar-inner">
			<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<?php vitalstack_logo(); ?>
			</a>

			<button type="button" class="topbar-search" data-open-search>
				<?php echo vitalstack_icon( 'search', 18 ); // phpcs:ignore ?>
				<span><?php esc_html_e( 'Search tutorials…', 'vitalstack' ); ?></span>
				<kbd aria-hidden="true">/</kbd>
			</button>

			<div class="topbar-actions">
				<button type="button" class="icon-btn search-mobile" data-open-search aria-label="<?php esc_attr_e( 'Search', 'vitalstack' ); ?>"><?php echo vitalstack_icon( 'search' ); // phpcs:ignore ?></button>
				<button type="button" class="icon-btn" data-toggle-theme aria-label="<?php esc_attr_e( 'Toggle dark mode', 'vitalstack' ); ?>">
					<span class="theme-icon-light"><?php echo vitalstack_icon( 'moon' ); // phpcs:ignore ?></span>
					<span class="theme-icon-dark"><?php echo vitalstack_icon( 'sun' ); // phpcs:ignore ?></span>
				</button>
				<?php vitalstack_account_menu(); ?>
				<button type="button" class="icon-btn menu-toggle" data-open-drawer aria-controls="drawer" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'vitalstack' ); ?>"><?php echo vitalstack_icon( 'menu' ); // phpcs:ignore ?></button>
			</div>
		</div>
	</div>

	<nav class="topicbar" aria-label="<?php esc_attr_e( 'Topics', 'vitalstack' ); ?>">
		<div class="wrap topicbar-inner">
			<a class="topic-home<?php echo is_front_page() ? ' is-active' : ''; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Home', 'vitalstack' ); ?>"><?php echo vitalstack_icon( 'home', 18 ); // phpcs:ignore ?></a>
			<?php vitalstack_topic_links(); ?>
		</div>
	</nav>
</header>

<div class="drawer" id="drawer" hidden>
	<div class="drawer-backdrop" data-close-drawer></div>
	<div class="drawer-panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menu', 'vitalstack' ); ?>">
		<div class="drawer-head">
			<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php vitalstack_logo(); ?></a>
			<button type="button" class="icon-btn" data-close-drawer aria-label="<?php esc_attr_e( 'Close menu', 'vitalstack' ); ?>"><?php echo vitalstack_icon( 'close' ); // phpcs:ignore ?></button>
		</div>
		<?php get_search_form(); ?>
		<?php if ( ! is_user_logged_in() && vitalstack_accounts_enabled() ) : ?>
			<div class="drawer-auth">
				<a class="btn btn-primary" href="<?php echo esc_url( vitalstack_account_url( array( 'tab' => 'register' ) ) ); ?>"><?php esc_html_e( 'Sign up free', 'vitalstack' ); ?></a>
				<a class="btn btn-ghost" href="<?php echo esc_url( vitalstack_account_url() ); ?>"><?php esc_html_e( 'Sign in', 'vitalstack' ); ?></a>
			</div>
		<?php endif; ?>
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
		<p class="search-modal-hint"><?php esc_html_e( 'Try: HTML forms, JavaScript loops, SQL joins, AI agents', 'vitalstack' ); ?></p>
		<button type="button" class="icon-btn search-modal-close" data-close-search aria-label="<?php esc_attr_e( 'Close search', 'vitalstack' ); ?>"><?php echo vitalstack_icon( 'close' ); // phpcs:ignore ?></button>
	</div>
</div>

<main id="main" class="site-main">
<?php if ( ! vitalstack_is_account_page() && isset( $_GET['vs_msg'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification ?>
	<div class="wrap flash-wrap"><?php vitalstack_flash(); ?></div>
<?php endif; ?>
