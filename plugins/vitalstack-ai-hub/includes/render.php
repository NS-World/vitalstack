<?php
/**
 * Rendering of the AI Hub pages. Premium text, prompts and code are only
 * printed for members; everyone else gets the title, summary and a lock card.
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

/* ── Assets, head tags, and the button on the main site ───────────────── */

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( ! vsai_current() ) {
			return;
		}
		wp_enqueue_style( 'vsai', VSAI_URL . 'assets/hub.css', array(), VSAI_VERSION );
		wp_enqueue_script( 'vsai', VSAI_URL . 'assets/hub.js', array(), VSAI_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
		wp_localize_script(
			'vsai',
			'VSAI',
			array(
				'copied' => __( 'Copied ✓', 'vitalstack-ai-hub' ),
				'copy'   => __( 'Copy', 'vitalstack-ai-hub' ),
			)
		);
	},
	20
);

/** Our own description, canonical and share tags instead of the theme's generic ones. */
add_action(
	'template_redirect',
	function () {
		if ( ! vsai_current() ) {
			return;
		}
		remove_action( 'wp_head', 'vitalstack_fallback_meta_description', 2 );
		remove_action( 'wp_head', 'vitalstack_archive_canonical', 3 );
		remove_action( 'wp_head', 'vitalstack_open_graph', 5 );
		remove_action( 'wp_head', 'rel_canonical' );
		add_action( 'wp_head', 'vsai_head_tags', 1 );
	}
);

function vsai_head_tags() {
	list( $title, $desc ) = vsai_meta();
	$page = vsai_current();
	$url  = 'home' === $page ? vsai_url() : ( 'view' === $page ? '' : vsai_url( $page ) );
	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	if ( $url ) {
		echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
		echo '<meta property="og:type" content="website"><meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	}
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '"><meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:image" content="' . esc_url( VSAI_URL . 'assets/og.png' ) . '"><meta name="twitter:card" content="summary_large_image">' . "\n";
}

/** "AI Hub" first in the main site's practice links (theme 4.20.8+ filter). */
add_filter(
	'vitalstack_practice_links',
	function ( $links ) {
		array_unshift( $links, array( vsai_url(), __( 'AI Hub', 'vitalstack-ai-hub' ), '✨', (bool) vsai_current() ) );
		return $links;
	}
);
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( vsai_current() ) {
			return;
		}
		$css = 'a[href$="/ai/"]{position:relative}'
			. '.topbar-nav a[href$="/ai/"],.topic-practice a[href$="/ai/"]{background:linear-gradient(90deg,#7c3aed,#06b6d4);color:#fff!important;border-radius:999px;font-weight:700}'
			. '.drawer-practice a[href$="/ai/"]{background:linear-gradient(135deg,#1e1b4b,#0e7490);color:#fff!important;border-color:transparent}';
		wp_register_style( 'vsai-link', false, array(), VSAI_VERSION );
		wp_enqueue_style( 'vsai-link' );
		wp_add_inline_style( 'vsai-link', $css );
	},
	30
);

/* ── Shared pieces ─────────────────────────────────────────────────────── */

function vsai_badge( $premium ) {
	return $premium
		? '<span class="vsai-tag is-pro" title="' . esc_attr__( 'Premium', 'vitalstack-ai-hub' ) . '"><span aria-hidden="true">👑</span> ' . esc_html__( 'Premium', 'vitalstack-ai-hub' ) . '</span>'
		: '<span class="vsai-tag is-free">' . esc_html__( 'Free', 'vitalstack-ai-hub' ) . '</span>';
}

function vsai_lock( $what = '' ) {
	$member_note = is_user_logged_in()
		? esc_html__( 'Included in every VitalStack paid plan: Pro Monthly, Pro Yearly, any career track or Founder Lifetime.', 'vitalstack-ai-hub' )
		: esc_html__( 'Already a member? Sign in to open it.', 'vitalstack-ai-hub' );
	$html  = '<div class="vsai-lock"><span class="vsai-lock-ico" aria-hidden="true">🔒</span><div>';
	$html .= '<strong>' . esc_html( $what ? $what : __( 'Premium item', 'vitalstack-ai-hub' ) ) . '</strong><p>' . $member_note . '</p></div>';
	$html .= '<div class="vsai-lock-btns"><a class="vsai-btn is-glow" href="' . esc_url( vsai_unlock_url() ) . '">' . esc_html__( 'Unlock all premium', 'vitalstack-ai-hub' ) . '</a>';
	if ( ! is_user_logged_in() ) {
		$html .= '<a class="vsai-btn is-ghost" href="' . esc_url( vsai_signin_url() ) . '">' . esc_html__( 'Sign in', 'vitalstack-ai-hub' ) . '</a>';
	}
	return $html . '</div></div>';
}

/** Section heading with search and filters. */
function vsai_head( $section, $count, $cats, $extra = '' ) {
	$s = vsai_sections()[ $section ];
	?>
	<section class="vsai-hero is-small">
		<p class="vsai-eyebrow"><span aria-hidden="true"><?php echo esc_html( $s[2] ); ?></span> VitalStack AI</p>
		<h1 class="vsai-h1"><?php echo esc_html( $s[0] ); ?></h1>
		<p class="vsai-lead"><?php echo esc_html( $s[3] ); ?></p>
		<?php echo $extra; // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts by the caller ?>
	</section>
	<div class="vsai-tools" data-filter-root>
		<label class="vsai-search"><span class="vsai-sr"><?php esc_html_e( 'Search', 'vitalstack-ai-hub' ); ?></span>
			<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="M20 20l-4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
			<input type="search" data-q placeholder="<?php echo esc_attr( sprintf( /* translators: %d: number of items */ __( 'Search %d items…', 'vitalstack-ai-hub' ), $count ) ); ?>">
		</label>
		<div class="vsai-chips" role="group" aria-label="<?php esc_attr_e( 'Category', 'vitalstack-ai-hub' ); ?>">
			<button type="button" class="vsai-chip is-on" data-cat=""><?php esc_html_e( 'All', 'vitalstack-ai-hub' ); ?></button>
			<?php foreach ( $cats as $c ) : ?>
				<button type="button" class="vsai-chip" data-cat="<?php echo esc_attr( $c ); ?>"><?php echo esc_html( $c ); ?></button>
			<?php endforeach; ?>
		</div>
		<div class="vsai-seg" role="group" aria-label="<?php esc_attr_e( 'Price', 'vitalstack-ai-hub' ); ?>">
			<button type="button" class="is-on" data-price=""><?php esc_html_e( 'All', 'vitalstack-ai-hub' ); ?></button>
			<button type="button" data-price="free"><?php esc_html_e( 'Free', 'vitalstack-ai-hub' ); ?></button>
			<button type="button" data-price="pro">👑 <?php esc_html_e( 'Premium', 'vitalstack-ai-hub' ); ?></button>
		</div>
		<p class="vsai-count" aria-live="polite" data-count></p>
	</div>
	<?php
}

/** data-* attributes every card carries for the filters. */
function vsai_card_attrs( $it, $extra_text = '' ) {
	return ' data-cat="' . esc_attr( $it['cat'] ) . '" data-price="' . ( $it['premium'] ? 'pro' : 'free' ) . '" data-text="' . esc_attr( strtolower( $it['title'] . ' ' . $it['cat'] . ' ' . ( $it['summary'] ?? '' ) . ' ' . $extra_text ) ) . '"';
}

/** A 2-colour gradient that is stable for a title (book covers, tiles). */
function vsai_hue( $text ) {
	$h = hexdec( substr( md5( $text ), 0, 6 ) ) % 360;
	return 'style="--h1:' . (int) $h . ';--h2:' . (int) ( ( $h + 70 ) % 360 ) . '"';
}

/* ── Home ──────────────────────────────────────────────────────────────── */

function vsai_render_home() {
	$counts = array();
	foreach ( vsai_types() as $t ) {
		$counts[ $t ] = count( vsai_items( $t ) );
	}
	$rows = 0;
	foreach ( vsai_items( 'commands' ) as $c ) {
		$rows += count( $c['rows'] );
	}
	?>
	<section class="vsai-hero">
		<div class="vsai-hero-copy">
			<p class="vsai-eyebrow"><span class="vsai-dot" aria-hidden="true"></span> <?php esc_html_e( 'New: the VitalStack AI Hub', 'vitalstack-ai-hub' ); ?></p>
			<h1 class="vsai-h1 is-xl"><?php esc_html_e( 'Your whole', 'vitalstack-ai-hub' ); ?> <span class="vsai-grad"><?php esc_html_e( 'AI universe', 'vitalstack-ai-hub' ); ?></span>, <?php esc_html_e( 'in one place', 'vitalstack-ai-hub' ); ?></h1>
			<p class="vsai-lead"><?php esc_html_e( 'Learn AI from the best books and guides, copy prompts that work, master every AI tool’s shortcuts, and drop animated sections into your website. Free to start, premium when you want more.', 'vitalstack-ai-hub' ); ?></p>
			<div class="vsai-cta">
				<a class="vsai-btn is-glow" href="<?php echo esc_url( vsai_url( 'prompts' ) ); ?>"><?php esc_html_e( 'Open the Prompt Bazaar', 'vitalstack-ai-hub' ); ?></a>
				<a class="vsai-btn is-ghost" href="<?php echo esc_url( vsai_url( 'motion' ) ); ?>"><?php esc_html_e( 'See Motion Studio', 'vitalstack-ai-hub' ); ?></a>
			</div>
			<ul class="vsai-stats">
				<li><b><?php echo (int) $counts['library']; ?></b> <?php esc_html_e( 'books & guides', 'vitalstack-ai-hub' ); ?></li>
				<li><b><?php echo (int) $counts['prompts']; ?></b> <?php esc_html_e( 'prompts', 'vitalstack-ai-hub' ); ?></li>
				<li><b><?php echo (int) $rows; ?></b> <?php esc_html_e( 'commands', 'vitalstack-ai-hub' ); ?></li>
				<li><b><?php echo (int) $counts['motion']; ?></b> <?php esc_html_e( 'motion sections', 'vitalstack-ai-hub' ); ?></li>
			</ul>
		</div>
		<div class="vsai-orb" aria-hidden="true"><canvas class="vsai-orb-c"></canvas><span class="vsai-orb-ring"></span><span class="vsai-orb-ring is-2"></span></div>
	</section>

	<section class="vsai-doors" aria-label="<?php esc_attr_e( 'Sections', 'vitalstack-ai-hub' ); ?>">
		<?php foreach ( vsai_sections() as $slug => $s ) : ?>
			<a class="vsai-door" href="<?php echo esc_url( vsai_url( $slug ) ); ?>" data-tilt>
				<span class="vsai-door-ico" aria-hidden="true"><?php echo esc_html( $s[2] ); ?></span>
				<h2><?php echo esc_html( $s[0] ); ?></h2>
				<p><?php echo esc_html( $s[3] ); ?></p>
				<span class="vsai-door-go"><?php esc_html_e( 'Explore', 'vitalstack-ai-hub' ); ?> →</span>
			</a>
		<?php endforeach; ?>
	</section>

	<section class="vsai-band">
		<div>
			<h2><?php esc_html_e( 'Free for everyone. Premium for builders.', 'vitalstack-ai-hub' ); ?></h2>
			<p><?php esc_html_e( 'Every section has free items you can use today. Premium unlocks the in-depth guides, pro prompt packs, advanced workflows and the full code of premium motion sections, and it comes with every VitalStack paid plan, together with the career tracks and certificates.', 'vitalstack-ai-hub' ); ?></p>
		</div>
		<?php if ( vsai_is_member() ) : ?>
			<span class="vsai-pill is-member">👑 <?php esc_html_e( 'You have full access', 'vitalstack-ai-hub' ); ?></span>
		<?php else : ?>
			<a class="vsai-btn is-glow" href="<?php echo esc_url( vsai_unlock_url() ); ?>"><?php esc_html_e( 'See plans', 'vitalstack-ai-hub' ); ?></a>
		<?php endif; ?>
	</section>

	<section class="vsai-picks">
		<h2 class="vsai-h2"><?php esc_html_e( 'Start here', 'vitalstack-ai-hub' ); ?></h2>
		<div class="vsai-grid3">
			<?php
			$picks = array(
				array( 'prompts', vsai_items( 'prompts' )[0] ?? null ),
				array( 'library', vsai_items( 'library' )[0] ?? null ),
				array( 'motion', vsai_items( 'motion' )[0] ?? null ),
			);
			foreach ( $picks as $p ) {
				if ( ! $p[1] ) {
					continue;
				}
				$it = $p[1];
				echo '<a class="vsai-mini" href="' . esc_url( vsai_url( $p[0] ) ) . '"><span class="vsai-mini-k">' . esc_html( vsai_sections()[ $p[0] ][1] ) . '</span><b>' . esc_html( $it['title'] ) . '</b><span>' . esc_html( $it['summary'] ?? '' ) . '</span></a>';
			}
			?>
		</div>
	</section>
	<?php
}

/* ── Library ───────────────────────────────────────────────────────────── */

function vsai_render_library() {
	$items  = vsai_items( 'library' );
	$member = vsai_is_member();
	vsai_head( 'library', count( $items ), vsai_categories( 'library' ) );
	echo '<div class="vsai-shelf" data-list>';
	foreach ( $items as $it ) {
		$is_guide = 'guide' === $it['kind'];
		$open     = ! $it['premium'] || $member;
		echo '<article class="vsai-book"' . vsai_card_attrs( $it, $it['by'] ?? '' ) . '>';
		echo '<div class="vsai-cover" ' . vsai_hue( $it['title'] ) . '><span class="vsai-cover-k">' . esc_html( $is_guide ? __( 'VitalStack guide', 'vitalstack-ai-hub' ) : __( 'Free resource', 'vitalstack-ai-hub' ) ) . '</span><b>' . esc_html( $it['title'] ) . '</b><span class="vsai-cover-by">' . esc_html( $it['by'] ?? '' ) . '</span></div>';
		echo '<div class="vsai-book-meta">' . vsai_badge( $it['premium'] ) . ( ! empty( $it['level'] ) ? '<span class="vsai-tag">' . esc_html( $it['level'] ) . '</span>' : '' ) . '<span class="vsai-tag">' . esc_html( $it['cat'] ) . '</span></div>';
		echo '<h3>' . esc_html( $it['title'] ) . '</h3><p>' . esc_html( $it['summary'] ) . '</p>';
		if ( ! $is_guide && ! empty( $it['url'] ) ) {
			echo '<a class="vsai-btn is-ghost is-sm" href="' . esc_url( $it['url'] ) . '" target="_blank" rel="noopener">' . esc_html__( 'Open (free, external)', 'vitalstack-ai-hub' ) . ' ↗</a>';
		} else {
			echo '<button type="button" class="vsai-btn is-sm' . ( $open ? '' : ' is-locked' ) . '" data-open="#lib-' . esc_attr( $it['id'] ) . '">' . ( $open ? esc_html__( 'Read guide', 'vitalstack-ai-hub' ) : '🔒 ' . esc_html__( 'Preview', 'vitalstack-ai-hub' ) ) . '</button>';
			echo '<template id="lib-' . esc_attr( $it['id'] ) . '"><article class="vsai-read"><p class="vsai-eyebrow">' . esc_html( $it['cat'] ) . ' · ' . esc_html( $it['level'] ?? '' ) . '</p><h2 id="vsai-dialog-title">' . esc_html( $it['title'] ) . '</h2><p class="vsai-lead">' . esc_html( $it['summary'] ) . '</p>';
			echo $open ? wp_kses_post( $it['body'] ) : vsai_lock( __( 'This guide is premium', 'vitalstack-ai-hub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- vsai_lock escapes its parts
			echo '</article></template>';
		}
		echo '</article>';
	}
	echo '</div><p class="vsai-empty" data-empty hidden>' . esc_html__( 'Nothing matches. Try another word or category.', 'vitalstack-ai-hub' ) . '</p>';
	echo '<p class="vsai-note">' . esc_html__( 'External resources are free and legal to read (official sites, open books and free courses). Paid books are not copied here: our own guides teach the same ideas in plain words.', 'vitalstack-ai-hub' ) . '</p>';
}

/* ── Commands & shortcuts ──────────────────────────────────────────────── */

function vsai_render_commands() {
	$items  = vsai_items( 'commands' );
	$member = vsai_is_member();
	vsai_head( 'commands', count( $items ), vsai_categories( 'commands' ) );
	echo '<div class="vsai-cmds" data-list>';
	foreach ( $items as $it ) {
		$open  = ! $it['premium'] || $member;
		$rows  = $open ? $it['rows'] : array_slice( $it['rows'], 0, 3 );
		$words = implode( ' ', array_map( 'implode', $it['rows'] ) );
		echo '<article class="vsai-cmd"' . vsai_card_attrs( $it, $it['tool'] . ' ' . ( $open ? $words : '' ) ) . '>';
		echo '<header><span class="vsai-cmd-ico" ' . vsai_hue( $it['tool'] ) . ' aria-hidden="true">' . esc_html( mb_substr( $it['tool'], 0, 1 ) ) . '</span><div><h3>' . esc_html( $it['title'] ) . '</h3><p>' . esc_html( $it['summary'] ) . '</p></div>' . vsai_badge( $it['premium'] ) . '</header>';
		echo '<table class="vsai-keys"><tbody>';
		foreach ( $rows as $r ) {
			echo '<tr><th scope="row"><code>' . esc_html( $r[0] ) . '</code></th><td>' . esc_html( $r[1] ) . '</td></tr>';
		}
		echo '</tbody></table>';
		if ( $open && ! empty( $it['tips'] ) ) {
			echo '<ul class="vsai-tips">';
			foreach ( $it['tips'] as $t ) {
				echo '<li>' . esc_html( $t ) . '</li>';
			}
			echo '</ul>';
		}
		if ( ! $open ) {
			/* translators: %d: number of hidden rows */
			echo vsai_lock( sprintf( __( '%d more power moves are premium', 'vitalstack-ai-hub' ), max( 0, count( $it['rows'] ) - 3 ) + count( $it['tips'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
		echo '</article>';
	}
	echo '</div><p class="vsai-empty" data-empty hidden>' . esc_html__( 'Nothing matches. Try another word or category.', 'vitalstack-ai-hub' ) . '</p>';
}

/* ── Prompt Bazaar ─────────────────────────────────────────────────────── */

function vsai_render_prompts() {
	$items  = vsai_items( 'prompts' );
	$member = vsai_is_member();
	$tools  = array();
	foreach ( $items as $it ) {
		$tools[ $it['tool'] ] = true;
	}
	$extra = '<p class="vsai-how">' . esc_html__( 'How to use: copy a prompt, replace the [BRACKETS] with your details, paste it into the tool shown. Results vary a little each time: run it twice and keep the best.', 'vitalstack-ai-hub' ) . '</p>';
	vsai_head( 'prompts', count( $items ), vsai_categories( 'prompts' ), $extra );
	echo '<div class="vsai-prompts" data-list>';
	foreach ( $items as $it ) {
		$open = ! $it['premium'] || $member;
		echo '<article class="vsai-prompt"' . vsai_card_attrs( $it, $it['tool'] . ' ' . $it['kind'] . ( $open ? ' ' . $it['prompt'] : '' ) ) . '>';
		echo '<header><span class="vsai-tool">' . esc_html( $it['tool'] ) . '</span><span class="vsai-tag">' . esc_html( $it['cat'] ) . '</span>' . vsai_badge( $it['premium'] ) . '</header>';
		echo '<h3>' . esc_html( $it['title'] ) . '</h3><p class="vsai-sum">' . esc_html( $it['summary'] ) . '</p>';
		if ( $open ) {
			echo '<div class="vsai-code"><pre data-copy-src>' . esc_html( $it['prompt'] ) . '</pre><button type="button" class="vsai-copy" data-copy>' . esc_html__( 'Copy', 'vitalstack-ai-hub' ) . '</button></div>';
		} else {
			echo '<div class="vsai-code is-blur" aria-hidden="true"><pre>' . esc_html( str_repeat( '████ ███ ██████ ████ ███████ ██ ████ ', 4 ) ) . '</pre></div>';
		}
		echo '<details class="vsai-out"><summary>' . esc_html__( 'What you get', 'vitalstack-ai-hub' ) . '</summary>';
		if ( ! empty( $it['image'] ) ) {
			echo '<img src="' . esc_url( $it['image'] ) . '" alt="' . esc_attr( sprintf( /* translators: %s: prompt title */ __( 'Example result of the prompt: %s', 'vitalstack-ai-hub' ), $it['title'] ) ) . '" loading="lazy">';
		}
		echo '<div class="vsai-out-body">' . nl2br( esc_html( $it['output'] ) ) . '</div></details>';
		if ( ! $open ) {
			echo vsai_lock( __( 'Premium prompt', 'vitalstack-ai-hub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
		echo '</article>';
	}
	echo '</div><p class="vsai-empty" data-empty hidden>' . esc_html__( 'Nothing matches. Try another word or category.', 'vitalstack-ai-hub' ) . '</p>';
}

/* ── Motion Studio ─────────────────────────────────────────────────────── */

/** The snippet wrapped so it can't reach the network, cookies or the page around it. */
function vsai_sandboxed( $code ) {
	$csp = "<meta http-equiv=\"Content-Security-Policy\" content=\"default-src 'none'; style-src 'unsafe-inline'; script-src 'unsafe-inline'; img-src data: blob:; font-src data:; media-src data: blob:\">";
	return false !== stripos( $code, '<head>' ) ? str_ireplace( '<head>', '<head>' . $csp, $code ) : $csp . $code;
}

function vsai_poster( $it ) {
	$file = 'assets/posters/' . sanitize_file_name( $it['id'] ) . '.webp';
	return file_exists( VSAI_DIR . $file ) ? VSAI_URL . $file : '';
}

function vsai_render_motion() {
	$items  = vsai_items( 'motion' );
	$member = vsai_is_member();
	$extra  = '<p class="vsai-how">' . esc_html__( 'Every section is plain HTML, CSS and JavaScript: no library, no build step. Copy it into any page, a WordPress “Custom HTML” block or the VitalStack Website Builder, or give its prompt to an AI coding tool to adapt it.', 'vitalstack-ai-hub' ) . '</p>';
	vsai_head( 'motion', count( $items ), vsai_categories( 'motion' ), $extra );
	echo '<div class="vsai-motion" data-list>';
	foreach ( $items as $it ) {
		$open   = ! $it['premium'] || $member;
		$poster = vsai_poster( $it );
		echo '<article class="vsai-tile"' . vsai_card_attrs( $it, $it['tags'] ?? '' ) . '>';
		echo '<button type="button" class="vsai-frame" data-open="#mo-' . esc_attr( $it['id'] ) . '" aria-label="' . esc_attr( sprintf( /* translators: %s: section name */ __( 'Open %s', 'vitalstack-ai-hub' ), $it['title'] ) ) . '">';
		if ( $open ) {
			echo '<iframe title="' . esc_attr( $it['title'] ) . '" sandbox="allow-scripts" loading="lazy" tabindex="-1" data-srcdoc="' . esc_attr( vsai_sandboxed( $it['code'] ) ) . '"></iframe>';
		} elseif ( $poster ) {
			echo '<img src="' . esc_url( $poster ) . '" alt="" loading="lazy">';
		} else {
			echo '<span class="vsai-poster" ' . vsai_hue( $it['title'] ) . '></span>';
		}
		echo '</button><div class="vsai-tile-meta"><h3>' . esc_html( $it['title'] ) . ' <span>' . esc_html( $it['cat'] ) . '</span></h3>' . ( $it['premium'] ? '<span class="vsai-crown" title="' . esc_attr__( 'Premium', 'vitalstack-ai-hub' ) . '">👑</span>' : '<span class="vsai-free-dot" title="' . esc_attr__( 'Free', 'vitalstack-ai-hub' ) . '">' . esc_html__( 'Free', 'vitalstack-ai-hub' ) . '</span>' ) . '</div>';
		// Dialog content.
		echo '<template id="mo-' . esc_attr( $it['id'] ) . '"><div class="vsai-mo"><h2 id="vsai-dialog-title">' . esc_html( $it['title'] ) . ' ' . vsai_badge( $it['premium'] ) . '</h2><p class="vsai-sum">' . esc_html( $it['summary'] ) . '</p>';
		if ( $open ) {
			echo '<div class="vsai-mo-stage"><iframe title="' . esc_attr( $it['title'] ) . '" sandbox="allow-scripts" data-srcdoc="' . esc_attr( vsai_sandboxed( $it['code'] ) ) . '"></iframe></div>';
			echo '<div class="vsai-mo-bar"><a class="vsai-btn is-ghost is-sm" href="' . esc_url( home_url( '/ai/motion/view/' . $it['id'] . '/' ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'Full screen', 'vitalstack-ai-hub' ) . ' ↗</a><button type="button" class="vsai-btn is-ghost is-sm" data-download="' . esc_attr( $it['id'] ) . '.html">' . esc_html__( 'Download .html', 'vitalstack-ai-hub' ) . '</button></div>';
			echo '<div class="vsai-tabs"><button type="button" class="is-on" data-tab="code">' . esc_html__( 'Code', 'vitalstack-ai-hub' ) . '</button><button type="button" data-tab="prompt">' . esc_html__( 'AI prompt', 'vitalstack-ai-hub' ) . '</button></div>';
			echo '<div class="vsai-code" data-pane="code"><pre data-copy-src>' . esc_html( $it['code'] ) . '</pre><button type="button" class="vsai-copy" data-copy>' . esc_html__( 'Copy', 'vitalstack-ai-hub' ) . '</button></div>';
			echo '<div class="vsai-code" data-pane="prompt" hidden><pre data-copy-src>' . esc_html( $it['prompt'] ) . '</pre><button type="button" class="vsai-copy" data-copy>' . esc_html__( 'Copy', 'vitalstack-ai-hub' ) . '</button></div>';
		} else {
			echo $poster ? '<div class="vsai-mo-stage"><img src="' . esc_url( $poster ) . '" alt=""></div>' : '';
			echo vsai_lock( __( 'Live preview, full code and AI prompt are premium', 'vitalstack-ai-hub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
		echo '</div></template></article>';
	}
	echo '</div><p class="vsai-empty" data-empty hidden>' . esc_html__( 'Nothing matches. Try another word or category.', 'vitalstack-ai-hub' ) . '</p>';
	echo '<p class="vsai-note">' . esc_html__( 'You may use the code in your own and your clients’ websites. Please don’t resell or republish the sections themselves as a code pack.', 'vitalstack-ai-hub' ) . '</p>';
}

/** /ai/motion/view/<slug>/: the section alone, full screen (members only for premium). */
function vsai_render_view( $slug ) {
	$it = vsai_find( 'motion', sanitize_key( $slug ) );
	if ( ! $it || ( $it['premium'] && ! vsai_is_member() ) ) {
		wp_safe_redirect( vsai_url( 'motion' ) );
		exit;
	}
	header( 'X-Robots-Tag: noindex' );
	header( "Content-Security-Policy: default-src 'none'; style-src 'unsafe-inline'; script-src 'unsafe-inline'; img-src data: blob:; font-src data:; media-src data: blob:; frame-ancestors 'self'; sandbox allow-scripts" );
	echo $it['code']; // phpcs:ignore WordPress.Security.EscapeOutput -- the section's own HTML document, isolated by the CSP above
	exit;
}
