<?php
/**
 * Theme-managed pages: cheat sheets, free coding tools, mock tests and roadmaps.
 *
 * The setup wizard creates real WordPress pages (good for SEO and menus)
 * from data/pages/index.php. While a page's own content is empty, its body
 * comes from data/pages/<slug>.html, so theme updates keep it fresh. Write
 * your own content in the editor to replace it.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/**
 * All theme-managed pages: slug => array( title, parent, template, icon, excerpt, order ).
 */
function vitalstack_site_pages() {
	static $pages = null;
	if ( null === $pages ) {
		$file  = VITALSTACK_DIR . '/data/pages/index.php';
		$pages = file_exists( $file ) ? (array) include $file : array();
	}
	return $pages;
}

/**
 * Child pages of a hub ("cheat-sheets", "tools", "mock-tests" or "roadmaps").
 */
function vitalstack_site_pages_in( $parent ) {
	return array_filter(
		vitalstack_site_pages(),
		function ( $p ) use ( $parent ) {
			return $p['parent'] === $parent;
		}
	);
}

/**
 * Page body: the editor content if any, otherwise the theme's built-in text.
 */
function vitalstack_site_page_content( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	if ( '' !== trim( $post->post_content ) ) {
		return apply_filters( 'the_content', $post->post_content );
	}
	$file = VITALSTACK_DIR . '/data/pages/' . sanitize_key( $post->post_name ) . '.html';
	if ( ! file_exists( $file ) ) {
		return '';
	}
	return apply_filters( 'the_content', (string) file_get_contents( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
}

/**
 * URL of a theme-managed page if it exists and is published.
 */
function vitalstack_site_page_url( $slug ) {
	$pages = vitalstack_site_pages();
	$path  = isset( $pages[ $slug ] ) && $pages[ $slug ]['parent'] ? $pages[ $slug ]['parent'] . '/' . $slug : $slug;
	$page  = get_page_by_path( $path );
	return $page && 'publish' === $page->post_status ? get_permalink( $page ) : '';
}

/**
 * Call to action on a hub's cards.
 */
function vitalstack_site_page_card_label( $parent ) {
	$labels = array(
		'tools'      => __( 'Open tool', 'vitalstack' ),
		'mock-tests' => __( 'Start test', 'vitalstack' ),
		'roadmaps'   => __( 'View roadmap', 'vitalstack' ),
	);
	return isset( $labels[ $parent ] ) ? $labels[ $parent ] : __( 'Open cheat sheet', 'vitalstack' );
}

/**
 * Card grid of a hub's pages.
 */
function vitalstack_site_page_cards( $parent ) {
	?>
	<div class="sp-grid">
		<?php
		foreach ( vitalstack_site_pages_in( $parent ) as $slug => $p ) :
			$url = vitalstack_site_page_url( $slug );
			if ( ! $url ) {
				continue;
			}
			?>
			<a class="sp-card" href="<?php echo esc_url( $url ); ?>">
				<span class="sp-icon" aria-hidden="true"><?php echo esc_html( $p['icon'] ); ?></span>
				<b class="sp-title"><?php echo esc_html( $p['title'] ); ?></b>
				<span class="sp-excerpt"><?php echo esc_html( $p['excerpt'] ); ?></span>
				<?php if ( 'mock-tests' === $parent && ! empty( $p['path'] ) && function_exists( 'vitalstack_mock_questions' ) ) : ?>
					<span class="sp-meta">
						<?php
						$vs_n = min( VITALSTACK_MOCK_QUESTIONS, count( vitalstack_mock_questions( $p['path'] ) ) );
						/* translators: 1: number of questions, 2: minutes */
						echo esc_html( sprintf( __( '%1$d questions · %2$d min', 'vitalstack' ), $vs_n, $vs_n ) );
						?>
					</span>
				<?php endif; ?>
				<span class="sp-go"><?php echo esc_html( vitalstack_site_page_card_label( $parent ) ); ?> ❯</span>
			</a>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Scripts for the tool pages.
 */
function vitalstack_site_pages_assets() {
	if ( is_page_template( 'page-tool.php' ) ) {
		wp_enqueue_script( 'vitalstack-tools', vitalstack_asset_url( 'assets/js/tools.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'vitalstack_site_pages_assets', 25 );

/**
 * Meta description for theme-managed pages without one.
 */
function vitalstack_site_page_description() {
	if ( ! is_page() || ( function_exists( 'vitalstack_seo_plugin_active' ) && vitalstack_seo_plugin_active() ) ) {
		return;
	}
	$pages = vitalstack_site_pages();
	$slug  = get_post_field( 'post_name', get_queried_object_id() );
	if ( isset( $pages[ $slug ] ) && $pages[ $slug ]['excerpt'] && ! has_excerpt( get_queried_object_id() ) ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $pages[ $slug ]['excerpt'] ) );
	}
}
add_action( 'wp_head', 'vitalstack_site_page_description', 1 );
