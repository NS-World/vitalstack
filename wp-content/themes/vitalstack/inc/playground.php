<?php
/**
 * Website Playground (4.16.0) at /playground/.
 *
 * Build a small website in the browser: several HTML pages plus CSS and
 * JavaScript, a live preview at phone, tablet and desktop sizes, a console,
 * starter templates, and projects saved on the learner's device.
 *
 * Security:
 *   - The preview runs in an iframe sandbox WITHOUT allow-same-origin, so the
 *     learner's code gets an opaque origin: it can't read this site's
 *     cookies, storage or pages, or act as the signed-in user.
 *   - The preview carries a Content-Security-Policy with connect-src 'none'
 *     and form-action 'none': code in it can't send data anywhere.
 *   - Messages from the preview (page links, console lines) are accepted only
 *     from that iframe and only in the expected shape.
 *   - Nothing is uploaded: projects stay in the browser's local storage.
 *
 * Free: unlimited projects and single-file HTML download. Premium: ZIP export.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_playground_url() {
	return home_url( '/playground/' );
}

add_action(
	'init',
	function () {
		add_rewrite_rule( '^playground/?$', 'index.php?vs_playground=1', 'top' );
	}
);
add_filter(
	'query_vars',
	function ( $vars ) {
		$vars[] = 'vs_playground';
		return $vars;
	}
);
add_filter(
	'pre_handle_404',
	function ( $handled ) {
		return get_query_var( 'vs_playground' ) ? true : $handled;
	}
);

function vitalstack_playground_template() {
	if ( ! get_query_var( 'vs_playground' ) ) {
		return;
	}
	status_header( 200 );
	$title = __( 'Website Playground: Build a Website Online (HTML, CSS, JavaScript)', 'vitalstack' );
	add_filter(
		'pre_get_document_title',
		function () use ( $title ) {
			return $title . ' | ' . get_bloginfo( 'name' );
		}
	);
	add_action(
		'wp_head',
		function () {
			echo '<meta name="description" content="' . esc_attr__( 'Free online website builder for learners: write HTML, CSS and JavaScript across several pages, see a live preview on phone, tablet and desktop, start from templates and download your site.', 'vitalstack' ) . '">' . "\n";
			echo '<link rel="canonical" href="' . esc_url( vitalstack_playground_url() ) . '">' . "\n";
		},
		2
	);
	wp_enqueue_script( 'vitalstack-playground', vitalstack_asset_url( 'assets/js/playground.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	$uid     = get_current_user_id();
	$premium = $uid && function_exists( 'vitalstack_reader_has_any_premium' ) && vitalstack_reader_has_any_premium( $uid );
	$pricing = function_exists( 'vitalstack_pricing_url' ) ? add_query_arg( array( 'utm_source' => 'site', 'utm_medium' => 'playground' ), vitalstack_pricing_url() ) : '';
	get_header();
	?>
	<div class="pg" data-pg data-premium="<?php echo $premium ? '1' : '0'; ?>" data-pricing="<?php echo esc_url( $pricing ); ?>">
		<div class="pg-top wrap">
			<div>
				<p class="eyebrow">🧪 <?php esc_html_e( 'Free · runs in your browser · nothing is uploaded', 'vitalstack' ); ?></p>
				<h1 class="pg-h1"><?php esc_html_e( 'Website Playground', 'vitalstack' ); ?></h1>
				<p class="pg-lead"><?php esc_html_e( 'Build a real multi-page website with HTML, CSS and JavaScript. Pick a template or start blank, watch it live on phone, tablet and desktop, and download it when you are done.', 'vitalstack' ); ?></p>
			</div>
		</div>
		<div class="pg-bar wrap" role="toolbar" aria-label="<?php esc_attr_e( 'Playground tools', 'vitalstack' ); ?>">
			<label class="pg-field"><span class="screen-reader-text"><?php esc_html_e( 'Project', 'vitalstack' ); ?></span><select data-pg-project></select></label>
			<button type="button" class="btn btn-ghost btn-sm" data-pg-new>＋ <?php esc_html_e( 'New project', 'vitalstack' ); ?></button>
			<label class="pg-field"><span class="screen-reader-text"><?php esc_html_e( 'Template', 'vitalstack' ); ?></span><select data-pg-template>
				<option value=""><?php esc_html_e( 'Start from a template…', 'vitalstack' ); ?></option>
				<option value="portfolio"><?php esc_html_e( 'Portfolio (2 pages)', 'vitalstack' ); ?></option>
				<option value="cafe"><?php esc_html_e( 'Café landing page', 'vitalstack' ); ?></option>
				<option value="event"><?php esc_html_e( 'Tech event with countdown', 'vitalstack' ); ?></option>
				<option value="blank"><?php esc_html_e( 'Blank page', 'vitalstack' ); ?></option>
			</select></label>
			<span class="pg-sp"></span>
			<div class="pg-devices" role="radiogroup" aria-label="<?php esc_attr_e( 'Preview size', 'vitalstack' ); ?>">
				<button type="button" role="radio" aria-checked="false" data-pg-size="375" title="<?php esc_attr_e( 'Phone', 'vitalstack' ); ?>">📱<span class="screen-reader-text"><?php esc_html_e( 'Phone', 'vitalstack' ); ?></span></button>
				<button type="button" role="radio" aria-checked="false" data-pg-size="768" title="<?php esc_attr_e( 'Tablet', 'vitalstack' ); ?>">📲<span class="screen-reader-text"><?php esc_html_e( 'Tablet', 'vitalstack' ); ?></span></button>
				<button type="button" role="radio" aria-checked="true" data-pg-size="0" title="<?php esc_attr_e( 'Full width', 'vitalstack' ); ?>">🖥️<span class="screen-reader-text"><?php esc_html_e( 'Full width', 'vitalstack' ); ?></span></button>
			</div>
			<button type="button" class="btn btn-soft btn-sm" data-pg-download>⬇ <?php esc_html_e( 'Download HTML', 'vitalstack' ); ?></button>
			<button type="button" class="btn btn-primary btn-sm" data-pg-zip>🗜️ <?php esc_html_e( 'Export ZIP', 'vitalstack' ); ?><?php echo $premium ? '' : ' <span class="pg-pro">PRO</span>'; ?></button>
		</div>
		<div class="pg-mobile-tabs wrap" role="tablist">
			<button type="button" role="tab" aria-selected="true" data-pg-view="code"><?php esc_html_e( 'Code', 'vitalstack' ); ?></button>
			<button type="button" role="tab" aria-selected="false" data-pg-view="preview"><?php esc_html_e( 'Preview', 'vitalstack' ); ?></button>
		</div>
		<div class="pg-main" data-pg-main data-view="code">
			<section class="pg-code" aria-label="<?php esc_attr_e( 'Code', 'vitalstack' ); ?>">
				<div class="pg-files" role="tablist" data-pg-files></div>
				<label class="screen-reader-text" for="pg-editor"><?php esc_html_e( 'Code editor', 'vitalstack' ); ?></label>
				<textarea id="pg-editor" class="pg-editor" spellcheck="false" autocapitalize="off" autocomplete="off" data-pg-editor></textarea>
				<p class="pg-status" data-pg-status aria-live="polite"></p>
			</section>
			<section class="pg-view" aria-label="<?php esc_attr_e( 'Live preview', 'vitalstack' ); ?>">
				<div class="pg-frame-wrap" data-pg-wrap>
					<iframe class="pg-frame" title="<?php esc_attr_e( 'Live preview of your website', 'vitalstack' ); ?>" sandbox="allow-scripts allow-modals" referrerpolicy="no-referrer" data-pg-frame></iframe>
				</div>
				<details class="pg-console"><summary><?php esc_html_e( 'Console', 'vitalstack' ); ?> <span data-pg-errors></span></summary><pre data-pg-console></pre></details>
			</section>
		</div>
		<div class="wrap pg-help">
			<h2><?php esc_html_e( 'How to use the playground', 'vitalstack' ); ?></h2>
			<ul>
				<li><?php esc_html_e( 'Files: index.html is your home page. Add pages with "+ Page" and link them normally, for example <a href="about.html">.', 'vitalstack' ); ?></li>
				<li><?php esc_html_e( 'style.css and script.js are already linked in the templates with <link href="style.css"> and <script src="script.js">.', 'vitalstack' ); ?></li>
				<li><?php esc_html_e( 'The preview updates as you type. Use the phone and tablet buttons to check your design is responsive.', 'vitalstack' ); ?></li>
				<li><?php esc_html_e( 'Projects are saved in this browser automatically. Download them to keep a copy or publish them (for example on GitHub Pages or Netlify).', 'vitalstack' ); ?></li>
				<li><?php esc_html_e( 'For safety, code in the preview can’t send data over the internet or read this website.', 'vitalstack' ); ?></li>
			</ul>
		</div>
	</div>
	<?php
	get_footer();
	exit;
}
add_action( 'template_redirect', 'vitalstack_playground_template', 6 );
