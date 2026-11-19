<?php
/**
 * The AI Hub page: its own header, background and footer.
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

$vsai_page = vsai_current();
if ( 'view' === $vsai_page ) {
	vsai_render_view( (string) get_query_var( 'vsai_item' ) );
	return;
}
$vsai_sections = vsai_sections();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#070816">
<?php wp_head(); ?>
</head>
<body <?php body_class( 'vsai-body vsai-page-' . $vsai_page ); ?>>
<?php wp_body_open(); ?>
<a class="vsai-skip" href="#vsai-main"><?php esc_html_e( 'Skip to content', 'vitalstack-ai-hub' ); ?></a>
<div class="vsai">
	<canvas class="vsai-bg" aria-hidden="true"></canvas>
	<div class="vsai-grid" aria-hidden="true"></div>
	<header class="vsai-top">
		<a class="vsai-logo" href="<?php echo esc_url( vsai_url() ); ?>" aria-label="<?php esc_attr_e( 'VitalStack AI Hub home', 'vitalstack-ai-hub' ); ?>">
			<span class="vsai-logo-mark" aria-hidden="true"></span>
			<span class="vsai-logo-text">Vital<b>Stack</b> <i>AI</i></span>
		</a>
		<button class="vsai-burger" type="button" aria-expanded="false" aria-controls="vsai-nav"><span></span><span></span><span></span><span class="vsai-sr"><?php esc_html_e( 'Menu', 'vitalstack-ai-hub' ); ?></span></button>
		<nav class="vsai-nav" id="vsai-nav" aria-label="<?php esc_attr_e( 'AI Hub', 'vitalstack-ai-hub' ); ?>">
			<?php foreach ( $vsai_sections as $slug => $s ) : ?>
				<a href="<?php echo esc_url( vsai_url( $slug ) ); ?>"<?php echo $slug === $vsai_page ? ' aria-current="page" class="is-on"' : ''; ?>><span aria-hidden="true"><?php echo esc_html( $s[2] ); ?></span> <?php echo esc_html( $s[1] ); ?></a>
			<?php endforeach; ?>
			<a href="<?php echo esc_url( vsai_url( 'pricing' ) ); ?>"<?php echo 'pricing' === $vsai_page ? ' aria-current="page" class="is-on"' : ''; ?>><?php esc_html_e( 'Pricing', 'vitalstack-ai-hub' ); ?></a>
			<a class="vsai-nav-main" href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'Main site', 'vitalstack-ai-hub' ); ?></a>
			<?php if ( ! vsai_is_member() ) : ?>
				<a class="vsai-pill is-glow" href="<?php echo esc_url( vsai_url( 'pricing' ) ); ?>"><?php esc_html_e( 'Go Unlimited', 'vitalstack-ai-hub' ); ?></a>
			<?php endif; ?>
			<?php if ( is_user_logged_in() ) : ?>
				<a class="vsai-pill<?php echo vsai_is_member() ? ' is-member' : ''; ?>" href="<?php echo esc_url( function_exists( 'vitalstack_account_url' ) ? vitalstack_account_url() : admin_url( 'profile.php' ) ); ?>"><?php echo vsai_is_member() ? vsai_crown_svg() . ' ' . esc_html__( 'AI Pro', 'vitalstack-ai-hub' ) : esc_html__( 'Account', 'vitalstack-ai-hub' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG + escaped text ?></a>
			<?php else : ?>
				<a class="vsai-pill" href="<?php echo esc_url( vsai_signin_url() ); ?>"><?php esc_html_e( 'Sign in', 'vitalstack-ai-hub' ); ?></a>
			<?php endif; ?>
		</nav>
	</header>

	<main id="vsai-main" class="vsai-main">
		<?php
		switch ( $vsai_page ) {
			case 'library':
				vsai_render_library();
				break;
			case 'commands':
				vsai_render_commands();
				break;
			case 'prompts':
				vsai_render_prompts();
				break;
			case 'motion':
				vsai_render_motion();
				break;
			case 'pricing':
				vsai_render_pricing();
				break;
			default:
				vsai_render_home();
		}
		?>
	</main>

	<footer class="vsai-foot">
		<div class="vsai-foot-in">
			<a class="vsai-logo is-small" href="<?php echo esc_url( vsai_url() ); ?>"><span class="vsai-logo-mark" aria-hidden="true"></span><span class="vsai-logo-text">Vital<b>Stack</b> <i>AI</i></span></a>
			<nav aria-label="<?php esc_attr_e( 'Footer', 'vitalstack-ai-hub' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Learn to code', 'vitalstack-ai-hub' ); ?></a>
				<a href="<?php echo esc_url( vsai_unlock_url() ); ?>"><?php esc_html_e( 'Pricing', 'vitalstack-ai-hub' ); ?></a>
				<?php foreach ( array( 'about-us' => __( 'About', 'vitalstack-ai-hub' ), 'contact-us' => __( 'Contact', 'vitalstack-ai-hub' ), 'privacy-policy' => __( 'Privacy', 'vitalstack-ai-hub' ), 'terms-of-service' => __( 'Terms', 'vitalstack-ai-hub' ) ) as $vsai_slug => $vsai_label ) : ?>
					<?php $vsai_p = get_page_by_path( $vsai_slug ); ?>
					<?php if ( $vsai_p && 'publish' === $vsai_p->post_status ) : ?>
						<a href="<?php echo esc_url( get_permalink( $vsai_p ) ); ?>"><?php echo esc_html( $vsai_label ); ?></a>
					<?php endif; ?>
				<?php endforeach; ?>
			</nav>
			<p class="vsai-foot-note"><?php esc_html_e( 'Tool names belong to their owners. Shortcuts and features change often: last checked November 2026.', 'vitalstack-ai-hub' ); ?></p>
		</div>
	</footer>
</div>
<dialog class="vsai-dialog" aria-labelledby="vsai-dialog-title">
	<div class="vsai-dialog-in">
		<button class="vsai-x" type="button" data-close aria-label="<?php esc_attr_e( 'Close', 'vitalstack-ai-hub' ); ?>">×</button>
		<div class="vsai-dialog-body"></div>
	</div>
</dialog>
<?php wp_footer(); ?>
</body>
</html>
