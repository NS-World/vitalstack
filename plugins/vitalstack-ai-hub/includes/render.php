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
		if ( 'pricing' === vsai_current() && function_exists( 'vitalstack_asset_url' ) && defined( 'VITALSTACK_VERSION' ) ) {
			wp_enqueue_script( 'vitalstack-pay', vitalstack_asset_url( 'assets/js/pay.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
		}
		wp_enqueue_script( 'vsai', VSAI_URL . 'assets/hub.js', array(), VSAI_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
		wp_localize_script(
			'vsai',
			'VSAI',
			array(
				'copied' => __( 'Copied ✓', 'vitalstack-ai-hub' ),
				'copy'   => __( 'Copy', 'vitalstack-ai-hub' ),
				'rest'   => esc_url_raw( rest_url( 'vsai/v1/copy' ) ),
				'suggest' => esc_url_raw( rest_url( 'vsai/v1/suggest' ) ),
				'more'   => __( 'Show all', 'vitalstack-ai-hub' ),
				'less'   => __( 'Show less', 'vitalstack-ai-hub' ),
				'pro'    => __( 'Pro', 'vitalstack-ai-hub' ),
				'nonce'  => is_user_logged_in() ? wp_create_nonce( 'wp_rest' ) : '',
				'fail'   => __( 'Could not copy. Please try again.', 'vitalstack-ai-hub' ),
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
	if ( in_array( $page, array( 'prompts', 'motion' ), true ) ) {
		$st  = vsai_list_state_full( $page );
		$url = vsai_list_url( $page, array( 'cat' => $st['cat'], 'pg' => $st['pg'] ) ); // canonical keeps category + page, drops search and price
	}
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

function vsai_crown_svg() {
	return '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path fill="currentColor" d="M3 7.5l4.6 4.1L12 4.5l4.4 7.1L21 7.5l-1.8 10.2H4.8zM5 19.2h14v1.6H5z"/></svg>';
}

function vsai_badge( $premium ) {
	return $premium ? '<span class="vsai-crown" role="img" aria-label="' . esc_attr__( 'Pro', 'vitalstack-ai-hub' ) . '" title="' . esc_attr__( 'Pro', 'vitalstack-ai-hub' ) . '">' . vsai_crown_svg() . '</span>' : '';
}

function vsai_lock( $what = '' ) {
	$html  = '<div class="vsai-lock"><div class="vsai-lock-top"><span class="vsai-crown is-lg" aria-hidden="true">' . vsai_crown_svg() . '</span><div><strong>' . esc_html( $what ? $what : __( 'Unlock everything with AI Pro', 'vitalstack-ai-hub' ) ) . '</strong>';
	$html .= '<p>' . esc_html( sprintf( /* translators: %s: monthly price */ __( 'Every Pro prompt, motion design, full code and guide. Unlimited copies. From %s/month, no auto-renewal.', 'vitalstack-ai-hub' ), vsai_from_price_label() ) ) . '</p></div></div>';
	$html .= '<div class="vsai-lock-btns"><a class="vsai-btn is-white" href="' . esc_url( vsai_unlock_url() ) . '">' . esc_html__( 'Go Unlimited', 'vitalstack-ai-hub' ) . ' →</a>';
	if ( ! is_user_logged_in() ) {
		$html .= '<a class="vsai-btn is-dark" href="' . esc_url( vsai_signin_url() ) . '">' . esc_html__( 'Create free account', 'vitalstack-ai-hub' ) . '</a>';
	}
	return $html . '</div></div>';
}

/** Section heading with search and filters (client-side for small lists, server-side + pages for big ones). */
function vsai_head( $section, $count, $cats, $extra = '', $state = null ) {
	$s = vsai_sections()[ $section ];
	?>
	<section class="vsai-hero is-small">
		<p class="vsai-eyebrow"><span aria-hidden="true"><?php echo esc_html( $s[2] ); ?></span> VitalStack AI</p>
		<h1 class="vsai-h1"><?php echo esc_html( $state && $state['cat_name'] ? $state['cat_name'] . ' · ' . $s[0] : $s[0] ); ?></h1>
		<p class="vsai-lead"><?php echo esc_html( $s[3] ); ?></p>
		<?php echo $extra; // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts by the caller ?>
	</section>
	<?php if ( $state ) : ?>
	<div class="vsai-tools" data-filter-root data-server="1">
		<form class="vsai-search" role="search" method="get" action="<?php echo esc_url( vsai_url( $section ) ); ?>">
			<label class="vsai-sr" for="vsai-q"><?php esc_html_e( 'Search', 'vitalstack-ai-hub' ); ?></label>
			<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="M20 20l-4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
			<input id="vsai-q" type="search" name="q" maxlength="80" value="<?php echo esc_attr( $state['q'] ); ?>" placeholder="<?php echo esc_attr( sprintf( /* translators: %d: number of items */ __( 'Search %d items…', 'vitalstack-ai-hub' ), $count ) ); ?>">
			<?php if ( $state['price'] ) : ?><input type="hidden" name="price" value="<?php echo esc_attr( $state['price'] ); ?>"><?php endif; ?>
		</form>
		<nav class="vsai-chips" aria-label="<?php esc_attr_e( 'Category', 'vitalstack-ai-hub' ); ?>">
			<a class="vsai-chip<?php echo $state['cat'] ? '' : ' is-on'; ?>" href="<?php echo esc_url( vsai_list_url( $section, array_merge( $state, array( 'cat' => '' ) ) ) ); ?>"<?php echo $state['cat'] ? '' : ' aria-current="page"'; ?>><?php esc_html_e( 'All', 'vitalstack-ai-hub' ); ?></a>
			<?php foreach ( $cats as $c ) : $slug = sanitize_title( $c ); ?>
				<a class="vsai-chip<?php echo $slug === $state['cat'] ? ' is-on' : ''; ?>" href="<?php echo esc_url( vsai_list_url( $section, array_merge( $state, array( 'cat' => $slug ) ) ) ); ?>"<?php echo $slug === $state['cat'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $c ); ?></a>
			<?php endforeach; ?>
		</nav>
		<nav class="vsai-seg" aria-label="<?php esc_attr_e( 'Price', 'vitalstack-ai-hub' ); ?>">
			<?php foreach ( array( '' => __( 'All', 'vitalstack-ai-hub' ), 'free' => __( 'Free', 'vitalstack-ai-hub' ), 'pro' => '' ) as $k => $label ) : ?>
				<a class="<?php echo $k === $state['price'] ? 'is-on' : ''; ?>" href="<?php echo esc_url( vsai_list_url( $section, array_merge( $state, array( 'price' => $k ) ) ) ); ?>"<?php echo 'pro' === $k ? ' aria-label="' . esc_attr__( 'Pro only', 'vitalstack-ai-hub' ) . '"' : ''; ?>><?php echo 'pro' === $k ? vsai_crown_svg() : esc_html( $label ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></a>
			<?php endforeach; ?>
		</nav>
		<p class="vsai-count" aria-live="polite"><?php echo esc_html( vsai_range_text( $state ) ); ?></p>
	</div>
	<?php else : ?>
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
			<button type="button" data-price="pro" aria-label="<?php esc_attr_e( 'Pro only', 'vitalstack-ai-hub' ); ?>"><?php echo vsai_crown_svg(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></button>
		</div>
		<p class="vsai-count" aria-live="polite" data-count></p>
	</div>
	<?php endif; ?>
	<?php
}

/* ── Big lists: filters in the URL, one page at a time ─────────────────── */

/** Items per page of the paged sections. */
function vsai_per_page( $section ) {
	return (int) apply_filters( 'vsai_per_page', 'motion' === $section ? 24 : 30, $section );
}

/**
 * Filter state from the URL: ?q=&cat=&price=&pg=. Values are validated
 * (category must exist, price free|pro, page a positive number).
 */
function vsai_list_state( $section ) {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only filters
	$get = function ( $k ) {
		return isset( $_GET[ $k ] ) ? sanitize_title( wp_unslash( $_GET[ $k ] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitize_title
	};
	$q     = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
	$price = isset( $_GET['price'] ) ? sanitize_key( wp_unslash( $_GET['price'] ) ) : '';
	$kind  = isset( $_GET['kind'] ) ? sanitize_key( wp_unslash( $_GET['kind'] ) ) : '';
	$pg    = isset( $_GET['pg'] ) ? absint( $_GET['pg'] ) : 1;
	// phpcs:enable
	$cat  = $get( 'cat' );
	$sub  = $get( 'sub' );
	$tool = $get( 'tool' );
	$q    = function_exists( 'mb_substr' ) ? mb_substr( $q, 0, 80 ) : substr( $q, 0, 80 );
	$st   = array(
		'section'   => $section,
		'q'         => $q,
		'cat'       => '',
		'cat_name'  => '',
		'sub'       => '',
		'sub_name'  => '',
		'tool'      => '',
		'tool_name' => '',
		'kind'      => '',
		'price'     => in_array( $price, array( 'free', 'pro' ), true ) ? $price : '',
		'pg'        => max( 1, min( 5000, $pg ) ),
		'asked'     => max( 1, $pg ),
		'total'     => 0,
		'pages'     => 1,
		'per'       => vsai_per_page( $section ),
		'bad'       => false,
	);
	$store = 'prompts' === $section && vsai_store_ready();
	$cats  = $store ? vsai_store_facet( 'cat' ) : array_map( function ( $c ) {
		return array( 'name' => $c, 'slug' => sanitize_title( $c ) );
	}, vsai_categories( $section ) );
	foreach ( $cats as $c ) {
		if ( $c['slug'] === $cat ) {
			$st['cat']      = $cat;
			$st['cat_name'] = $c['name'];
		}
	}
	if ( $store && $st['cat'] && $sub ) {
		foreach ( vsai_store_facet( 'sub', $st['cat'] ) as $c ) {
			if ( $c['slug'] === $sub ) {
				$st['sub']      = $sub;
				$st['sub_name'] = $c['name'];
			}
		}
	}
	if ( $store && $tool ) {
		foreach ( vsai_store_facet( 'tool' ) as $c ) {
			if ( $c['slug'] === $tool ) {
				$st['tool']      = $tool;
				$st['tool_name'] = $c['name'];
			}
		}
	}
	if ( 'prompts' === $section && in_array( $kind, array( 'text', 'image', 'video', 'audio', 'code' ), true ) ) {
		$st['kind'] = $kind;
	}
	// Unknown values in the URL: redirect to the clean URL (see routes.php).
	$st['bad'] = ( '' !== $cat && '' === $st['cat'] ) || ( '' !== $sub && '' === $st['sub'] ) || ( '' !== $tool && '' === $st['tool'] ) || ( '' !== $kind && '' === $st['kind'] );
	return $st;
}

/** The state with totals filled in and the page clamped (memoised per request). */
function vsai_list_state_full( $section ) {
	static $memo = array();
	if ( ! isset( $memo[ $section ] ) ) {
		$state = vsai_list_state( $section );
		if ( 'prompts' === $section && vsai_store_ready() ) {
			$r              = vsai_store_query( $state, $state['pg'], $state['per'] );
			$state['items'] = $r['items'];
			$state['total'] = $r['total'];
			$state['pages'] = $r['pages'];
			$state['pg']    = $r['page'];
		} else {
			$state['items'] = vsai_list_page( vsai_list_sorted( $section ), $state, function ( $it ) use ( $section ) {
				return vsai_list_text( $section, $it );
			} );
		}
		$memo[ $section ] = $state;
	}
	return $memo[ $section ];
}

/** Items of a paged section in display order (featured motion designs first). */
function vsai_list_sorted( $section ) {
	$items = vsai_items( $section );
	if ( 'motion' === $section ) {
		usort(
			$items,
			function ( $a, $b ) {
				return (int) ! empty( $b['featured'] ) - (int) ! empty( $a['featured'] );
			}
		);
	}
	return $items;
}

/** Searchable text of an item. Pro prompt text is never searched, so a search cannot reveal it. */
function vsai_list_text( $section, $it ) {
	if ( 'prompts' === $section ) {
		return $it['title'] . ' ' . $it['cat'] . ' ' . $it['summary'] . ' ' . $it['tool'] . ' ' . $it['kind'] . ( $it['premium'] ? '' : ' ' . $it['prompt'] );
	}
	return $it['title'] . ' ' . $it['cat'] . ' ' . ( $it['summary'] ?? '' ) . ' ' . ( $it['tags'] ?? '' );
}

/** URL of a list page for a state (empty values and page 1 left out). */
function vsai_list_url( $section, $state ) {
	$args = array();
	foreach ( array( 'q', 'cat', 'sub', 'tool', 'kind', 'price' ) as $k ) {
		if ( ! empty( $state[ $k ] ) ) {
			$args[ $k ] = $state[ $k ];
		}
	}
	if ( ! empty( $state['pg'] ) && (int) $state['pg'] > 1 ) {
		$args['pg'] = (int) $state['pg'];
	}
	return $args ? add_query_arg( array_map( 'rawurlencode', $args ), vsai_url( $section ) ) : vsai_url( $section );
}

/**
 * Apply the state to the items and return this page's slice. $text is a
 * callback giving the searchable text of an item.
 */
function vsai_list_page( $items, &$state, $text ) {
	$words = array_filter( preg_split( '/\s+/', strtolower( $state['q'] ) ) );
	$keep  = array();
	foreach ( $items as $it ) {
		if ( $state['cat'] && sanitize_title( $it['cat'] ) !== $state['cat'] ) {
			continue;
		}
		if ( ! empty( $state['kind'] ) && ( $it['kind'] ?? '' ) !== $state['kind'] ) {
			continue;
		}
		if ( 'free' === $state['price'] && $it['premium'] ) {
			continue;
		}
		if ( 'pro' === $state['price'] && ! $it['premium'] ) {
			continue;
		}
		if ( $words ) {
			$hay = strtolower( call_user_func( $text, $it ) );
			foreach ( $words as $w ) {
				if ( false === strpos( $hay, $w ) ) {
					continue 2;
				}
			}
		}
		$keep[] = $it;
	}
	$state['total'] = count( $keep );
	$state['pages'] = max( 1, (int) ceil( $state['total'] / $state['per'] ) );
	$state['pg']    = min( $state['pg'], $state['pages'] );
	return array_slice( $keep, ( $state['pg'] - 1 ) * $state['per'], $state['per'] );
}

/** "Showing 31–60 of 1,042". */
function vsai_range_text( $state ) {
	if ( ! $state['total'] ) {
		return __( 'No results', 'vitalstack-ai-hub' );
	}
	$from = ( $state['pg'] - 1 ) * $state['per'] + 1;
	$to   = min( $state['total'], $state['pg'] * $state['per'] );
	/* translators: 1: first item number, 2: last item number, 3: total */
	return sprintf( __( 'Showing %1$s–%2$s of %3$s', 'vitalstack-ai-hub' ), number_format_i18n( $from ), number_format_i18n( $to ), number_format_i18n( $state['total'] ) );
}

/** Numbered pages: 1 … 4 5 [6] 7 8 … 42, with previous / next. */
function vsai_pager( $state ) {
	if ( $state['pages'] < 2 ) {
		return;
	}
	$cur  = $state['pg'];
	$last = $state['pages'];
	$show = array_unique( array_filter( array( 1, $cur - 2, $cur - 1, $cur, $cur + 1, $cur + 2, $last ), function ( $n ) use ( $last ) {
		return $n >= 1 && $n <= $last;
	} ) );
	sort( $show );
	$link = function ( $n ) use ( $state ) {
		return esc_url( vsai_list_url( $state['section'], array_merge( $state, array( 'pg' => $n ) ) ) );
	};
	echo '<nav class="vsai-pager" aria-label="' . esc_attr__( 'Pages', 'vitalstack-ai-hub' ) . '">';
	echo $cur > 1 ? '<a class="vsai-pg is-arrow" rel="prev" href="' . $link( $cur - 1 ) . '">← ' . esc_html__( 'Previous', 'vitalstack-ai-hub' ) . '</a>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in $link
	$prev = 0;
	foreach ( $show as $n ) {
		if ( $n - $prev > 1 ) {
			echo '<span class="vsai-pg is-gap" aria-hidden="true">…</span>';
		}
		echo $n === $cur ? '<span class="vsai-pg is-on" aria-current="page">' . (int) $n . '</span>' : '<a class="vsai-pg" href="' . $link( $n ) . '">' . (int) $n . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput
		$prev = $n;
	}
	echo $cur < $last ? '<a class="vsai-pg is-arrow" rel="next" href="' . $link( $cur + 1 ) . '">' . esc_html__( 'Next', 'vitalstack-ai-hub' ) . ' →</a>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '</nav>';
}

/** Nothing found: say so and offer a way back. */
function vsai_no_results( $state ) {
	if ( $state['total'] ) {
		return;
	}
	echo '<p class="vsai-empty">' . esc_html__( 'Nothing matches. Try another word or category.', 'vitalstack-ai-hub' ) . ' <a href="' . esc_url( vsai_url( $state['section'] ) ) . '">' . esc_html__( 'Show everything', 'vitalstack-ai-hub' ) . '</a></p>';
}

/** data-* attributes every card carries for the filters. */
function vsai_card_attrs( $it, $extra_text = '' ) {
	if ( false === $extra_text ) { // server-filtered lists need no search text in the page
		return ' data-cat="' . esc_attr( $it['cat'] ) . '" data-price="' . ( $it['premium'] ? 'pro' : 'free' ) . '"';
	}
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
		$counts[ $t ] = 'prompts' === $t ? vsai_prompt_total() : count( vsai_items( $t ) );
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
			<p class="vsai-lead"><?php esc_html_e( 'Learn AI from the best books and guides, copy prompts that work, master every AI tool’s shortcuts, and drop animated sections into your website. Free to start, Pro when you want more.', 'vitalstack-ai-hub' ); ?></p>
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
			<h2><?php esc_html_e( 'Free to explore. Pro to build without limits.', 'vitalstack-ai-hub' ); ?></h2>
			<p><?php esc_html_e( 'Every section has free items you can use today. AI Pro unlocks every Pro motion design with full code and build prompts, the in-depth guides, pro prompt packs and advanced workflows, with unlimited copies and new drops every month.', 'vitalstack-ai-hub' ); ?></p>
		</div>
		<?php if ( vsai_is_member() ) : ?>
			<span class="vsai-pill is-member"><?php echo vsai_crown_svg(); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php esc_html_e( 'You have full access', 'vitalstack-ai-hub' ); ?></span>
		<?php else : ?>
			<a class="vsai-btn is-glow" href="<?php echo esc_url( vsai_unlock_url() ); ?>"><?php esc_html_e( 'Go Unlimited', 'vitalstack-ai-hub' ); ?> →</a>
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
	echo vsai_course_band(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
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
			echo $open ? wp_kses_post( $it['body'] ) : vsai_lock( __( 'This is a Pro guide', 'vitalstack-ai-hub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- vsai_lock escapes its parts
			echo '</article></template>';
		}
		echo '</article>';
	}
	echo '</div>';
	vsai_no_results( $state );
	vsai_pager( $state );
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
			echo vsai_lock( sprintf( __( '%d more power moves with AI Pro', 'vitalstack-ai-hub' ), max( 0, count( $it['rows'] ) - 3 ) + count( $it['tips'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
		echo '</article>';
	}
	echo '</div><p class="vsai-empty" data-empty hidden>' . esc_html__( 'Nothing matches. Try another word or category.', 'vitalstack-ai-hub' ) . '</p>';
}

/* ── Prompt Bazaar ─────────────────────────────────────────────────────── */

/** Icons for prompt categories (decorative). */
function vsai_cat_icon( $cat ) {
	$map = array(
		'Image Generation' => '🎨', 'Social Media' => '📱', 'Business' => '💼', 'Marketing' => '📣', 'Sales' => '🤝', 'Customer Support' => '💬',
		'E-commerce' => '🛒', 'Travel & Life' => '🧳', 'Study & Exams' => '📚', 'Teaching' => '🧑‍🏫', 'Coding' => '💻', 'Frameworks & Tools' => '🧰',
		'DSA & Interviews' => '🧩', 'Career & Jobs' => '🚀', 'HR & Workplace' => '🏢', 'Productivity' => '⏱️', 'YouTube & Creators' => '▶️', 'Video & Reels' => '🎬',
		'Music & Audio' => '🎵', 'Writing' => '✍️', 'Health & Fitness' => '💪', 'Food & Cooking' => '🍳', 'Language Learning' => '🗣️', 'Personal Finance' => '💰',
		'Personal Growth' => '🌱', 'Data & Excel' => '📊', 'AI Agents & Automation' => '🤖', 'Product & UX' => '🧭', 'Website Builders' => '🌐', 'Cybersecurity' => '🛡️',
		'DevOps & Cloud' => '☁️',
	);
	return $map[ $cat ] ?? '✨';
}

/** One prompt card. Pro prompt text is never sent to non-members. */
function vsai_prompt_card( $it, $member ) {
	$open = ! $it['premium'] || $member;
	$h    = '<article class="vsai-prompt" id="p-' . esc_attr( $it['id'] ) . '"' . vsai_card_attrs( $it, false ) . '>';
	$h   .= '<header><span class="vsai-tool">' . esc_html( $it['tool'] ) . '</span><span class="vsai-tag">' . esc_html( ! empty( $it['sub'] ) && $it['sub'] !== $it['cat'] ? $it['sub'] : $it['cat'] ) . '</span>' . vsai_badge( $it['premium'] ) . '</header>';
	$h   .= '<h3>' . esc_html( $it['title'] ) . '</h3><p class="vsai-sum">' . esc_html( $it['summary'] ) . '</p>';
	if ( $open ) {
		$has = (bool) preg_match( '/\[[A-Z0-9][^\]\n]{0,60}\]/', $it['prompt'] );
		$h  .= '<div class="vsai-code is-clamp"><pre data-copy-src>' . esc_html( $it['prompt'] ) . '</pre></div>';
		$h  .= '<div class="vsai-pbtns"><button type="button" class="vsai-btn is-white is-sm" data-copy-p>' . esc_html__( 'Copy', 'vitalstack-ai-hub' ) . '</button>';
		if ( $has ) {
			$h .= '<button type="button" class="vsai-btn is-dark is-sm" data-fill title="' . esc_attr__( 'Type your details into a form and copy the finished prompt', 'vitalstack-ai-hub' ) . '">✎ ' . esc_html__( 'Fill in & copy', 'vitalstack-ai-hub' ) . '</button>';
		}
		$h .= '<button type="button" class="vsai-btn is-ghost is-sm" data-expand aria-expanded="false">' . esc_html__( 'Show all', 'vitalstack-ai-hub' ) . '</button></div>';
	} else {
		$h .= '<div class="vsai-code is-blur" aria-hidden="true"><pre>' . esc_html( str_repeat( '████ ███ ██████ ████ ███████ ██ ████ ', 3 ) ) . '</pre></div>';
		$h .= '<div class="vsai-plock"><span class="vsai-crown" aria-hidden="true">' . vsai_crown_svg() . '</span><span>' . esc_html( sprintf( /* translators: %s: monthly price */ __( 'Unlock every Pro prompt from %s/month', 'vitalstack-ai-hub' ), vsai_from_price_label() ) ) . '</span><a class="vsai-btn is-white is-sm" href="' . esc_url( vsai_unlock_url() ) . '">' . esc_html__( 'Go Unlimited', 'vitalstack-ai-hub' ) . '</a></div>';
	}
	$h .= '<details class="vsai-out"><summary>' . esc_html__( 'What you get', 'vitalstack-ai-hub' ) . '</summary>';
	if ( ! empty( $it['image'] ) ) {
		$h .= '<img src="' . esc_url( $it['image'] ) . '" alt="' . esc_attr( sprintf( /* translators: %s: prompt title */ __( 'Example result of the prompt: %s', 'vitalstack-ai-hub' ), $it['title'] ) ) . '" loading="lazy">';
	}
	$h .= '<div class="vsai-out-body">' . nl2br( esc_html( $it['output'] ) ) . '</div></details>';
	return $h . '</article>';
}

/** Prompt Bazaar: search with suggestions, category grid, filters, cards, pages. */
function vsai_render_prompts() {
	$member = vsai_is_member();
	$state  = vsai_list_state_full( 'prompts' );
	$store  = vsai_store_ready();
	$total  = vsai_prompt_total();
	$cats   = $store ? vsai_store_facet( 'cat' ) : array_map( function ( $c ) {
		return array( 'name' => $c, 'slug' => sanitize_title( $c ), 'n' => 0 );
	}, vsai_categories( 'prompts' ) );
	$filtered = $state['cat'] || $state['q'] || $state['sub'] || $state['tool'] || $state['kind'] || $state['price'] || $state['pg'] > 1;
	$s        = vsai_sections()['prompts'];
	$base     = array_merge( $state, array( 'pg' => 1 ) );
	?>
	<section class="vsai-hero is-small vsai-phero">
		<p class="vsai-eyebrow"><span aria-hidden="true"><?php echo esc_html( $s[2] ); ?></span> VitalStack AI</p>
		<h1 class="vsai-h1"><?php echo esc_html( $state['cat_name'] ? $state['cat_name'] . ( $state['sub_name'] ? ' · ' . $state['sub_name'] : '' ) . ' prompts' : $s[0] ); ?></h1>
		<p class="vsai-lead"><?php echo esc_html( sprintf( /* translators: %s: number of prompts */ __( '%s ready-to-use prompts for ChatGPT, Claude, Gemini, Midjourney and more. Find one, fill in your details, copy, done.', 'vitalstack-ai-hub' ), number_format_i18n( $total ) ) ); ?></p>
		<form class="vsai-bigsearch" role="search" method="get" action="<?php echo esc_url( vsai_url( 'prompts' ) ); ?>" data-suggest-form>
			<label class="vsai-sr" for="vsai-q"><?php esc_html_e( 'Search prompts', 'vitalstack-ai-hub' ); ?></label>
			<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="M20 20l-4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
			<input id="vsai-q" type="search" name="q" maxlength="80" autocomplete="off" value="<?php echo esc_attr( $state['q'] ); ?>" placeholder="<?php esc_attr_e( 'Try “Instagram captions for a bakery” or “Python unit tests”', 'vitalstack-ai-hub' ); ?>" role="combobox" aria-expanded="false" aria-controls="vsai-sugg" aria-autocomplete="list">
			<?php if ( $state['price'] ) : ?><input type="hidden" name="price" value="<?php echo esc_attr( $state['price'] ); ?>"><?php endif; ?>
			<button type="submit" class="vsai-btn is-white is-sm"><?php esc_html_e( 'Search', 'vitalstack-ai-hub' ); ?></button>
			<ul id="vsai-sugg" class="vsai-sugg" role="listbox" hidden></ul>
		</form>
		<p class="vsai-how"><?php esc_html_e( 'Tip: press “Fill in & copy” to type your details into a simple form — the prompt fills itself.', 'vitalstack-ai-hub' ); ?></p>
	</section>

	<?php if ( ! $filtered ) : ?>
		<section class="vsai-catgrid" aria-label="<?php esc_attr_e( 'Browse by category', 'vitalstack-ai-hub' ); ?>">
			<?php foreach ( $cats as $c ) : ?>
				<a class="vsai-catcard" href="<?php echo esc_url( vsai_list_url( 'prompts', array( 'cat' => $c['slug'] ) ) ); ?>"><span class="vsai-catic" aria-hidden="true"><?php echo esc_html( vsai_cat_icon( $c['name'] ) ); ?></span><b><?php echo esc_html( $c['name'] ); ?></b><?php if ( $c['n'] ) : ?><small><?php echo esc_html( number_format_i18n( $c['n'] ) ); ?></small><?php endif; ?></a>
			<?php endforeach; ?>
		</section>
	<?php endif; ?>

	<div class="vsai-tools vsai-pfilters" data-filter-root data-server="1">
		<nav class="vsai-chips" aria-label="<?php esc_attr_e( 'Category', 'vitalstack-ai-hub' ); ?>">
			<a class="vsai-chip<?php echo $state['cat'] ? '' : ' is-on'; ?>" href="<?php echo esc_url( vsai_list_url( 'prompts', array_merge( $base, array( 'cat' => '', 'sub' => '' ) ) ) ); ?>"><?php esc_html_e( 'All', 'vitalstack-ai-hub' ); ?></a>
			<?php foreach ( $cats as $c ) : ?>
				<a class="vsai-chip<?php echo $c['slug'] === $state['cat'] ? ' is-on' : ''; ?>"<?php echo $c['slug'] === $state['cat'] ? ' aria-current="page"' : ''; ?> href="<?php echo esc_url( vsai_list_url( 'prompts', array_merge( $base, array( 'cat' => $c['slug'], 'sub' => '' ) ) ) ); ?>"><?php echo esc_html( vsai_cat_icon( $c['name'] ) . ' ' . $c['name'] ); ?></a>
			<?php endforeach; ?>
		</nav>
		<form class="vsai-selects" method="get" action="<?php echo esc_url( vsai_url( 'prompts' ) ); ?>" data-autosubmit>
			<?php foreach ( array( 'q', 'cat', 'price', 'kind' ) as $k ) : if ( $state[ $k ] ) : ?><input type="hidden" name="<?php echo esc_attr( $k ); ?>" value="<?php echo esc_attr( $state[ $k ] ); ?>"><?php endif; endforeach; ?>
			<?php if ( $store && $state['cat'] ) : $subs = vsai_store_facet( 'sub', $state['cat'] ); if ( count( $subs ) > 1 ) : ?>
				<label><span><?php esc_html_e( 'Topic', 'vitalstack-ai-hub' ); ?></span><select name="sub"><option value=""><?php esc_html_e( 'All topics', 'vitalstack-ai-hub' ); ?></option><?php foreach ( $subs as $o ) : ?><option value="<?php echo esc_attr( $o['slug'] ); ?>"<?php selected( $o['slug'], $state['sub'] ); ?>><?php echo esc_html( $o['name'] . ' (' . number_format_i18n( $o['n'] ) . ')' ); ?></option><?php endforeach; ?></select></label>
			<?php endif; endif; ?>
			<?php if ( $store ) : ?>
				<label><span><?php esc_html_e( 'AI tool', 'vitalstack-ai-hub' ); ?></span><select name="tool"><option value=""><?php esc_html_e( 'Any tool', 'vitalstack-ai-hub' ); ?></option><?php foreach ( vsai_store_facet( 'tool' ) as $o ) : ?><option value="<?php echo esc_attr( $o['slug'] ); ?>"<?php selected( $o['slug'], $state['tool'] ); ?>><?php echo esc_html( $o['name'] ); ?></option><?php endforeach; ?></select></label>
			<?php endif; ?>
			<noscript><button type="submit" class="vsai-btn is-ghost is-sm"><?php esc_html_e( 'Apply', 'vitalstack-ai-hub' ); ?></button></noscript>
		</form>
		<nav class="vsai-seg" aria-label="<?php esc_attr_e( 'Type', 'vitalstack-ai-hub' ); ?>">
			<?php foreach ( array( '' => __( 'All', 'vitalstack-ai-hub' ), 'text' => __( 'Text', 'vitalstack-ai-hub' ), 'image' => __( 'Image', 'vitalstack-ai-hub' ), 'video' => __( 'Video', 'vitalstack-ai-hub' ), 'audio' => __( 'Audio', 'vitalstack-ai-hub' ), 'code' => __( 'Code', 'vitalstack-ai-hub' ) ) as $k => $label ) : ?>
				<a class="<?php echo $k === $state['kind'] ? 'is-on' : ''; ?>" href="<?php echo esc_url( vsai_list_url( 'prompts', array_merge( $base, array( 'kind' => $k ) ) ) ); ?>"><?php echo esc_html( $label ); ?></a>
			<?php endforeach; ?>
		</nav>
		<nav class="vsai-seg" aria-label="<?php esc_attr_e( 'Price', 'vitalstack-ai-hub' ); ?>">
			<?php foreach ( array( '' => __( 'All', 'vitalstack-ai-hub' ), 'free' => __( 'Free', 'vitalstack-ai-hub' ), 'pro' => '' ) as $k => $label ) : ?>
				<a class="<?php echo $k === $state['price'] ? 'is-on' : ''; ?>" href="<?php echo esc_url( vsai_list_url( 'prompts', array_merge( $base, array( 'price' => $k ) ) ) ); ?>"<?php echo 'pro' === $k ? ' aria-label="' . esc_attr__( 'Pro only', 'vitalstack-ai-hub' ) . '"' : ''; ?>><?php echo 'pro' === $k ? vsai_crown_svg() : esc_html( $label ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></a>
			<?php endforeach; ?>
		</nav>
		<p class="vsai-count" aria-live="polite"><?php echo esc_html( vsai_range_text( $state ) ); ?><?php if ( $filtered ) : ?> · <a href="<?php echo esc_url( vsai_url( 'prompts' ) ); ?>"><?php esc_html_e( 'Clear filters', 'vitalstack-ai-hub' ); ?></a><?php endif; ?></p>
	</div>
	<?php
	echo '<div class="vsai-prompts" data-list>';
	foreach ( $state['items'] as $n => $it ) {
		echo vsai_prompt_card( $it, $member ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
		if ( 8 === $n && ! $member ) {
			echo vsai_pro_band(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
		}
	}
	echo '</div>';
	vsai_no_results( $state );
	vsai_pager( $state );
	echo vsai_course_band(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
	echo '<template id="vsai-fill"><div class="vsai-fillbox"><p class="vsai-eyebrow">' . esc_html__( 'Fill in & copy', 'vitalstack-ai-hub' ) . '</p><h2 id="vsai-dialog-title" data-fill-title></h2><p class="vsai-lead">' . esc_html__( 'Type your details. Leave a box empty to keep the placeholder.', 'vitalstack-ai-hub' ) . '</p><div class="vsai-fillgrid"><div data-fill-fields></div><pre class="vsai-fillprev" data-fill-preview aria-live="polite"></pre></div><div class="vsai-pbtns"><button type="button" class="vsai-btn is-white" data-fill-copy>' . esc_html__( 'Copy finished prompt', 'vitalstack-ai-hub' ) . '</button></div></div></template>';
}

/**
 * Career-track cross-sell (the theme's premium courses) with live prices.
 * Shown at the end of hub pages; honest copy, no fake urgency.
 */
function vsai_course_band() {
	if ( ! function_exists( 'vitalstack_pay_plans' ) ) {
		return '';
	}
	$plans = vitalstack_pay_plans();
	$cur   = function_exists( 'vitalstack_pay_page_currency' ) && 'usd' === vitalstack_pay_page_currency() ? 'usd' : 'inr';
	$cards = '';
	foreach ( array( 'agentic-ai-engineer', 'gen-ai-developer', 'data-scientist' ) as $id ) {
		if ( empty( $plans[ $id ] ) || empty( $plans[ $id ][ $cur ] ) ) {
			continue;
		}
		$p      = $plans[ $id ];
		$cards .= '<li><b>' . esc_html( $p['title'] ) . '</b><span>' . esc_html( $p['blurb'] ?? '' ) . '</span><em>' . esc_html( vsai_price( (int) $p[ $cur ], $cur ) ) . ' · ' . esc_html( $p['period'] ?? '' ) . '</em></li>';
	}
	if ( ! $cards ) {
		return '';
	}
	$url = function_exists( 'vitalstack_pricing_url' ) ? vitalstack_pricing_url() : home_url( '/pricing/' );
	return '<section class="vsai-courses"><div><p class="vsai-eyebrow">' . esc_html__( 'Go beyond prompts', 'vitalstack-ai-hub' ) . '</p><h2>' . esc_html__( 'Build a career in AI with a guided track', 'vitalstack-ai-hub' ) . '</h2><p>' . esc_html__( 'Step-by-step lessons, real projects and a certificate — made for beginners and working developers alike.', 'vitalstack-ai-hub' ) . '</p><a class="vsai-btn is-white" href="' . esc_url( $url ) . '">' . esc_html__( 'See career tracks', 'vitalstack-ai-hub' ) . ' →</a></div><ul>' . $cards . '</ul></section>';
}

/** A one-line AI Pro band shown once in long lists for non-members (honest price, no countdowns). */
function vsai_pro_band() {
	return '<aside class="vsai-proband"><span class="vsai-crown is-lg" aria-hidden="true">' . vsai_crown_svg() . '</span><div><b>' . esc_html__( 'Unlock everything with AI Pro', 'vitalstack-ai-hub' ) . '</b><p>' . esc_html( sprintf( /* translators: %s: monthly price */ __( 'Every Pro prompt and motion design, full code, unlimited copies and an ad-free site — from %s/month. No auto-renewal.', 'vitalstack-ai-hub' ), vsai_from_price_label() ) ) . '</p></div><a class="vsai-btn is-glow" href="' . esc_url( vsai_unlock_url() ) . '">' . esc_html__( 'See plans', 'vitalstack-ai-hub' ) . ' →</a></aside>';
}

/* ── Motion Studio ─────────────────────────────────────────────────────── */

/** The snippet wrapped so it can't reach the network, cookies or the page around it. */
function vsai_sandboxed( $code ) {
	$csp = "<meta http-equiv=\"Content-Security-Policy\" content=\"default-src 'none'; style-src 'unsafe-inline'; script-src 'unsafe-inline'; img-src data: blob:; font-src data:; media-src data: blob:\">";
	return false !== stripos( $code, '<head>' ) ? str_ireplace( '<head>', '<head>' . $csp, $code ) : $csp . $code;
}

function vsai_poster( $it ) {
	$file = ! empty( $it['poster'] ) ? 'assets/posters/g/' . sanitize_file_name( $it['poster'] ) . '.webp' : 'assets/posters/' . sanitize_file_name( $it['id'] ) . '.webp';
	return file_exists( VSAI_DIR . $file ) ? VSAI_URL . $file : '';
}

function vsai_view_url( $it, $demo = false ) {
	$url = home_url( '/ai/motion/view/' . $it['id'] . '/' );
	return $demo ? add_query_arg( 'demo', '1', $url ) : $url;
}

/** Copies left, as a sentence for the panel. */
function vsai_left_text() {
	$left = vsai_copies_left();
	if ( null === $left ) {
		return __( 'Unlimited copies with AI Pro', 'vitalstack-ai-hub' );
	}
	/* translators: %d: copies left */
	return is_user_logged_in() ? sprintf( _n( '%d free copy left today', '%d free copies left today', $left, 'vitalstack-ai-hub' ), $left ) : sprintf( _n( '%d free copy left', '%d free copies left', $left, 'vitalstack-ai-hub' ), $left );
}

/** 3D showcase: a slowly turning ring of design posters (CSS 3D, no JS, pauses on hover). */
function vsai_motion_showcase() {
	$cards = array();
	foreach ( vsai_items( 'motion' ) as $it ) {
		$poster = vsai_poster( $it );
		if ( $poster && ( ! empty( $it['featured'] ) || $it['premium'] ) ) {
			$key = $poster;
			if ( ! isset( $cards[ $key ] ) ) {
				$cards[ $key ] = $it;
			}
		}
		if ( count( $cards ) >= 14 ) {
			break;
		}
	}
	if ( count( $cards ) < 6 ) {
		return;
	}
	$n = count( $cards );
	echo '<section class="vsai-show" aria-label="' . esc_attr__( 'Featured designs', 'vitalstack-ai-hub' ) . '"><div class="vsai-show-ring" style="--n:' . (int) $n . '">';
	$i = 0;
	foreach ( $cards as $poster => $it ) {
		echo '<a class="vsai-show-card" style="--i:' . (int) $i . '" href="' . esc_url( add_query_arg( 'q', rawurlencode( $it['title'] ), vsai_url( 'motion' ) ) ) . '"><img src="' . esc_url( $poster ) . '" alt="" loading="lazy"><span>' . esc_html( $it['title'] ) . ( $it['premium'] ? ' ' . vsai_crown_svg() : '' ) . '</span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG
		++$i;
	}
	echo '</div></section>';
}

function vsai_render_motion() {
	$items  = vsai_items( 'motion' );
	$member = vsai_is_member();
	$extra = '<p class="vsai-how">' . esc_html__( 'Every design is one HTML file: no library, no build step. Copy the build prompt into Claude, ChatGPT, Cursor or Lovable to make it yours, or copy the finished code into any page or the VitalStack Website Builder.', 'vitalstack-ai-hub' ) . '</p>';
	$state = vsai_list_state_full( 'motion' );
	$all   = count( $items );
	$free  = count( array_filter( $items, function ( $i ) {
		return ! $i['premium'];
	} ) );
	$extra .= '<ul class="vsai-stats"><li><b>' . esc_html( number_format_i18n( $all ) ) . '</b> ' . esc_html__( 'animated designs', 'vitalstack-ai-hub' ) . '</li><li><b>' . esc_html( number_format_i18n( $free ) ) . '</b> ' . esc_html__( 'free to copy', 'vitalstack-ai-hub' ) . '</li><li><b>' . esc_html( number_format_i18n( count( vsai_categories( 'motion' ) ) ) ) . '</b> ' . esc_html__( 'categories', 'vitalstack-ai-hub' ) . '</li><li><b>3D</b> ' . esc_html__( 'and scroll effects', 'vitalstack-ai-hub' ) . '</li></ul>';
	$items = $state['items'];
	$filtered = $state['cat'] || $state['q'] || $state['price'] || $state['pg'] > 1;
	if ( ! $filtered ) {
		vsai_motion_showcase();
	}
	vsai_head( 'motion', $all, vsai_categories( 'motion' ), $extra, $state );
	echo '<div class="vsai-motion" data-list>';
	foreach ( $items as $n => $it ) {
		if ( 8 === $n && ! $member ) {
			echo vsai_pro_band(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
		}
		$open   = ! $it['premium'] || $member;
		$poster = vsai_poster( $it );
		echo '<article class="vsai-tile' . ( ! empty( $it['featured'] ) ? ' is-featured' : '' ) . '"' . vsai_card_attrs( $it, false ) . '>';
		echo '<button type="button" class="vsai-frame" data-open="#mo-' . esc_attr( $it['id'] ) . '" aria-label="' . esc_attr( sprintf( /* translators: %s: design name */ __( 'Open %s', 'vitalstack-ai-hub' ), $it['title'] ) ) . '">';
		if ( $open ) {
			echo '<iframe title="' . esc_attr( $it['title'] ) . '" sandbox="allow-scripts" tabindex="-1" loading="lazy" data-src="' . esc_url( vsai_view_url( $it, true ) ) . '"></iframe>';
		}
		if ( $poster && ! $open ) {
			echo '<img src="' . esc_url( $poster ) . '" alt="" loading="lazy">';
		} elseif ( ! $open ) {
			echo '<span class="vsai-poster" ' . vsai_hue( $it['title'] ) . '></span>'; // phpcs:ignore WordPress.Security.EscapeOutput -- integers only
		}
		echo '</button><div class="vsai-tile-meta"><h3>' . esc_html( $it['title'] ) . ' <span>' . esc_html( $it['cat'] ) . '</span></h3>';
		if ( $it['premium'] ) {
			echo '<button type="button" class="vsai-ico-btn is-pro" data-open="#mo-' . esc_attr( $it['id'] ) . '" aria-label="' . esc_attr__( 'Pro design: open', 'vitalstack-ai-hub' ) . '">' . vsai_crown_svg() . '</button>'; // phpcs:ignore WordPress.Security.EscapeOutput
		} else {
			echo '<button type="button" class="vsai-ico-btn" data-copy-mo="' . esc_attr( $it['id'] ) . '" data-part="prompt" aria-label="' . esc_attr__( 'Copy build prompt', 'vitalstack-ai-hub' ) . '" title="' . esc_attr__( 'Copy build prompt', 'vitalstack-ai-hub' ) . '"><svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><rect x="8" y="8" width="12" height="12" rx="2.5" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M16 8V6.5A2.5 2.5 0 0 0 13.5 4h-7A2.5 2.5 0 0 0 4 6.5v7A2.5 2.5 0 0 0 6.5 16H8" fill="none" stroke="currentColor" stroke-width="1.8"/></svg></button>';
		}
		echo '</div>';
		// Detail panel (opened in the dialog). No code or prompt text here: both come from the server on Copy.
		echo '<template id="mo-' . esc_attr( $it['id'] ) . '"><div class="vsai-mo2"><div class="vsai-mo2-stage">';
		echo $open ? '<iframe title="' . esc_attr( $it['title'] ) . '" sandbox="allow-scripts" data-src="' . esc_url( vsai_view_url( $it ) ) . '"></iframe>' : ( $poster ? '<img src="' . esc_url( $poster ) . '" alt="">' : '<span class="vsai-poster" ' . vsai_hue( $it['title'] ) . '></span>' ); // phpcs:ignore WordPress.Security.EscapeOutput
		if ( $open ) {
			echo '<div class="vsai-dev" role="group" aria-label="' . esc_attr__( 'Preview size', 'vitalstack-ai-hub' ) . '"><button type="button" data-dev="desk" aria-pressed="true">' . esc_html__( 'Desktop', 'vitalstack-ai-hub' ) . '</button><button type="button" data-dev="phone" aria-pressed="false">' . esc_html__( 'Phone', 'vitalstack-ai-hub' ) . '</button></div>';
		}
		echo '</div><aside class="vsai-mo2-side"><p class="vsai-eyebrow">' . esc_html( $it['cat'] ) . '</p><h2 id="vsai-dialog-title">' . esc_html( $it['title'] ) . ' ' . vsai_badge( $it['premium'] ) . '</h2><p class="vsai-sum">' . esc_html( $it['summary'] ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput
		if ( $open ) {
			echo '<div class="vsai-mo2-btns"><button type="button" class="vsai-btn is-white" data-copy-mo="' . esc_attr( $it['id'] ) . '" data-part="prompt">' . esc_html__( 'Copy build prompt', 'vitalstack-ai-hub' ) . '</button>';
			echo '<button type="button" class="vsai-btn is-dark" data-copy-mo="' . esc_attr( $it['id'] ) . '" data-part="code">' . esc_html__( 'Copy code', 'vitalstack-ai-hub' ) . '</button>';
			echo '<button type="button" class="vsai-btn is-ghost is-sm" data-copy-mo="' . esc_attr( $it['id'] ) . '" data-part="code" data-download="' . esc_attr( $it['id'] ) . '.html">' . esc_html__( 'Download .html', 'vitalstack-ai-hub' ) . '</button>';
			echo '<a class="vsai-btn is-ghost is-sm" href="' . esc_url( vsai_view_url( $it ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'Full screen', 'vitalstack-ai-hub' ) . ' ↗</a></div>';
			echo '<p class="vsai-left" data-left>' . esc_html( vsai_left_text() ) . '</p>';
		} else {
			echo vsai_lock( __( 'This is a Pro design', 'vitalstack-ai-hub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
		echo '<ul class="vsai-checks"><li>' . esc_html__( 'One HTML file, no libraries', 'vitalstack-ai-hub' ) . '</li><li>' . esc_html__( 'Responsive + reduced-motion ready', 'vitalstack-ai-hub' ) . '</li><li>' . esc_html__( 'Detailed build prompt for AI tools', 'vitalstack-ai-hub' ) . '</li><li>' . esc_html__( 'Use in your and your clients’ sites', 'vitalstack-ai-hub' ) . '</li></ul>';
		echo '</aside></div></template></article>';
	}
	echo '</div>';
	vsai_no_results( $state );
	vsai_pager( $state );
	echo vsai_course_band(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
	echo '<p class="vsai-note">' . esc_html__( 'Licence: use the designs in your own and your clients’ websites. Reselling or republishing them as a template or prompt pack is not allowed.', 'vitalstack-ai-hub' ) . '</p>';
	vsai_gate_template();
}

/** The "free copies used" sheet the copy buttons open. */
function vsai_gate_template() {
	echo '<template id="vsai-gate"><div class="vsai-gate"><span class="vsai-crown is-lg" aria-hidden="true">' . vsai_crown_svg() . '</span><h2 id="vsai-dialog-title">' . esc_html__( 'Copy without limits', 'vitalstack-ai-hub' ) . '</h2><p class="vsai-lead" data-gate-msg></p>'; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<a class="vsai-btn is-white is-wide" href="' . esc_url( vsai_unlock_url() ) . '">' . esc_html__( 'Go Unlimited', 'vitalstack-ai-hub' ) . ' →</a>';
	if ( ! is_user_logged_in() ) {
		echo '<a class="vsai-btn is-dark is-wide" href="' . esc_url( vsai_signin_url() ) . '">' . esc_html__( 'Create free account', 'vitalstack-ai-hub' ) . '</a>';
	}
	echo '<ul class="vsai-checks"><li>' . esc_html__( 'Every Pro motion design and full code', 'vitalstack-ai-hub' ) . '</li><li>' . esc_html__( 'Pro prompt packs and AI guides', 'vitalstack-ai-hub' ) . '</li><li>' . esc_html__( 'New drops every month', 'vitalstack-ai-hub' ) . '</li></ul></div></template>';
}

/** /ai/motion/view/<slug>/: the design alone, full screen (members only for Pro). */
function vsai_render_view( $slug ) {
	$it = vsai_find( 'motion', sanitize_key( $slug ) );
	if ( ! $it || ( $it['premium'] && ! vsai_is_member() ) ) {
		wp_safe_redirect( vsai_url( 'motion' ) );
		exit;
	}
	header( 'X-Robots-Tag: noindex' );
	header( "Content-Security-Policy: default-src 'none'; style-src 'unsafe-inline'; script-src 'unsafe-inline'; img-src data: blob:; font-src data:; media-src data: blob:; frame-ancestors 'self'; sandbox allow-scripts" );
	if ( $it['premium'] ) {
		nocache_headers();
	}
	$code = vsai_motion_code( $it );
	// ?demo=1 (gallery tiles): scroll stories play themselves so the tile shows the motion.
	if ( isset( $_GET['demo'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- display only
		$demo = '<style>html{scrollbar-width:none}html::-webkit-scrollbar{display:none}</style><script>(function(){var d=1,y=0;function s(){var m=document.documentElement.scrollHeight-innerHeight;if(m<20)return;y+=d*Math.max(2,m/700);if(y>=m){y=m;d=-1}else if(y<=0){y=0;d=1}scrollTo(0,y);requestAnimationFrame(s)}setTimeout(s,1500)})();</script>';
		$code = false !== stripos( $code, '</body>' ) ? str_ireplace( '</body>', $demo . '</body>', $code ) : $code . $demo;
	}
	echo $code; // phpcs:ignore WordPress.Security.EscapeOutput -- the design's own HTML document, isolated by the sandbox CSP above
	exit;
}

/* ── AI Pro pricing ────────────────────────────────────────────────────── */

function vsai_price( $amount, $cur ) {
	return function_exists( 'vitalstack_pay_price_label' ) ? vitalstack_pay_price_label( $amount, $cur ) : ( 'usd' === $cur ? '$' . number_format_i18n( $amount ) : '₹' . number_format_i18n( $amount ) );
}

function vsai_render_pricing() {
	$plans  = vsai_plan_prices();
	$action = function_exists( 'vitalstack_pay_action' ) && vsai_checkout_ready() ? vitalstack_pay_action() : 'waitlist';
	$lock   = function_exists( 'vitalstack_pay_page_currency' ) ? vitalstack_pay_page_currency() : '';
	$m      = $plans['ai-month'] ?? null;
	if ( isset( $_GET['vsai_paid'] ) && vsai_is_member() ) { // phpcs:ignore WordPress.Security.NonceVerification -- message only
		echo '<p class="vsai-paid">🎉 ' . esc_html__( 'Payment received. Everything in the AI Hub is unlocked for you.', 'vitalstack-ai-hub' ) . '</p>';
	}
	?>
	<section class="vsai-hero is-small is-center">
		<p class="vsai-eyebrow"><span class="vsai-dot" aria-hidden="true"></span> <?php esc_html_e( 'AI Pro', 'vitalstack-ai-hub' ); ?></p>
		<h1 class="vsai-h1 is-xl"><?php esc_html_e( 'Build faster.', 'vitalstack-ai-hub' ); ?> <span class="vsai-grad"><?php esc_html_e( 'Ship prettier.', 'vitalstack-ai-hub' ); ?></span></h1>
		<p class="vsai-lead"><?php esc_html_e( 'Unlimited access to every Pro motion design, full code, build prompts, prompt packs and AI guides. New drops every month. One payment, no auto-renewal.', 'vitalstack-ai-hub' ); ?></p>
	</section>
	<section class="vsai-pricing" id="plans" data-pay data-cur-lock="<?php echo esc_attr( $lock ); ?>" data-action="<?php echo esc_attr( $action ); ?>"<?php if ( 'buy' === $action ) : ?> data-api="<?php echo esc_url( rest_url( 'vitalstack/v1/pay/' ) ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_rest' ) ); ?>"<?php endif; ?>>
		<?php if ( ! $lock ) : ?>
			<div class="vsai-cur" role="radiogroup" aria-label="<?php esc_attr_e( 'Currency', 'vitalstack-ai-hub' ); ?>"><button type="button" role="radio" aria-checked="false" data-cur="usd">$ USD</button><button type="button" role="radio" aria-checked="true" data-cur="inr">₹ INR</button></div>
		<?php endif; ?>
		<p class="vsai-cur-note" data-cur-note></p>
		<p class="pay-msg" role="alert" hidden></p>
		<div class="vsai-plans">
			<article class="vsai-plan">
				<h2><?php esc_html_e( 'Free', 'vitalstack-ai-hub' ); ?></h2>
				<p class="vsai-plan-price"><span><?php echo esc_html( vsai_price( 0, 'usd' === $lock ? 'usd' : 'inr' ) ); ?></span><small><?php esc_html_e( 'forever', 'vitalstack-ai-hub' ); ?></small></p>
				<p class="vsai-plan-blurb"><?php esc_html_e( 'Explore the whole hub and use the free items.', 'vitalstack-ai-hub' ); ?></p>
				<ul class="vsai-checks"><li><?php esc_html_e( 'Free motion designs + live previews', 'vitalstack-ai-hub' ); ?></li><li><?php esc_html_e( '2 copies as a visitor, 10/day with a free account', 'vitalstack-ai-hub' ); ?></li><li><?php esc_html_e( 'Free prompts, guides and shortcut cards', 'vitalstack-ai-hub' ); ?></li></ul>
				<a class="vsai-btn is-ghost is-wide" href="<?php echo esc_url( is_user_logged_in() ? vsai_url( 'motion' ) : vsai_signin_url() ); ?>"><?php echo is_user_logged_in() ? esc_html__( 'Keep exploring', 'vitalstack-ai-hub' ) : esc_html__( 'Create free account', 'vitalstack-ai-hub' ); ?></a>
			</article>
			<?php
			foreach ( $plans as $id => $p ) :
				$per = '';
				if ( 'ai-year' === $id && $m ) {
					$save = (int) round( 100 - 100 * $p['usd'] / max( 1, $m['usd'] * 12 ) );
					/* translators: %d: percent saved */
					$per = sprintf( __( 'Save %d%% vs monthly', 'vitalstack-ai-hub' ), $save );
				} elseif ( 'ai-lifetime' === $id ) {
					$per = __( 'Launch price: goes up as the library grows', 'vitalstack-ai-hub' );
				} else {
					$per = __( 'Try everything for a month', 'vitalstack-ai-hub' );
				}
				$unit = 'ai-month' === $id ? __( '/month', 'vitalstack-ai-hub' ) : ( 'ai-year' === $id ? __( '/year', 'vitalstack-ai-hub' ) : __( 'once', 'vitalstack-ai-hub' ) );
				?>
				<article class="vsai-plan<?php echo ! empty( $p['best'] ) ? ' is-best' : ''; ?>" data-plan-card>
					<?php if ( ! empty( $p['best'] ) ) : ?><span class="vsai-plan-tag"><?php esc_html_e( 'Most popular', 'vitalstack-ai-hub' ); ?></span><?php endif; ?>
					<h2><?php echo esc_html( $p['title'] ); ?></h2>
					<p class="vsai-plan-price"><span class="plan-amount" data-inr="<?php echo esc_attr( vsai_price( $p['inr'], 'inr' ) ); ?>" data-usd="<?php echo esc_attr( vsai_price( $p['usd'], 'usd' ) ); ?>"><?php echo esc_html( vsai_price( 'usd' === $lock ? $p['usd'] : $p['inr'], 'usd' === $lock ? 'usd' : 'inr' ) ); ?></span><small><?php echo esc_html( $unit ); ?></small></p>
					<p class="vsai-plan-per"><?php echo esc_html( $per ); ?></p>
					<p class="vsai-plan-blurb"><?php echo esc_html( $p['blurb'] ?? '' ); ?></p>
					<ul class="vsai-checks"><li><?php esc_html_e( 'All Pro motion designs + full code', 'vitalstack-ai-hub' ); ?></li><li><?php esc_html_e( 'Detailed build prompts, unlimited copies', 'vitalstack-ai-hub' ); ?></li><li><?php esc_html_e( 'Pro prompt packs, AI guides, power commands', 'vitalstack-ai-hub' ); ?></li><li><?php esc_html_e( 'Ad-free VitalStack + new monthly drops', 'vitalstack-ai-hub' ); ?></li></ul>
					<?php if ( 'buy' === $action ) : ?>
						<button type="button" class="vsai-btn <?php echo ! empty( $p['best'] ) ? 'is-glow' : 'is-white'; ?> is-wide" data-buy="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Get AI Pro', 'vitalstack-ai-hub' ); ?></button>
					<?php elseif ( 'signin' === $action ) : ?>
						<a class="vsai-btn <?php echo ! empty( $p['best'] ) ? 'is-glow' : 'is-white'; ?> is-wide" href="<?php echo esc_url( vsai_signin_url() ); ?>"><?php esc_html_e( 'Create free account to buy', 'vitalstack-ai-hub' ); ?></a>
					<?php else : ?>
						<span class="vsai-btn is-ghost is-wide" aria-disabled="true"><?php esc_html_e( 'Opening soon', 'vitalstack-ai-hub' ); ?></span>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
		<ul class="vsai-trust"><li>🔒 <?php esc_html_e( 'Secure checkout by Razorpay', 'vitalstack-ai-hub' ); ?></li><li>💳 <?php esc_html_e( 'UPI, cards, net banking · international cards', 'vitalstack-ai-hub' ); ?></li><li>🔁 <?php esc_html_e( 'No auto-renewal, ever', 'vitalstack-ai-hub' ); ?></li><li>⚡ <?php esc_html_e( 'Instant access', 'vitalstack-ai-hub' ); ?></li></ul>
	</section>

	<section class="vsai-compare">
		<h2 class="vsai-h2"><?php esc_html_e( 'Free vs AI Pro', 'vitalstack-ai-hub' ); ?></h2>
		<div class="vsai-table"><table><thead><tr><th></th><th><?php esc_html_e( 'Free', 'vitalstack-ai-hub' ); ?></th><th><?php esc_html_e( 'AI Pro', 'vitalstack-ai-hub' ); ?></th></tr></thead><tbody>
		<?php
		$rows = array(
			array( __( 'Browse the whole hub + live previews', 'vitalstack-ai-hub' ), '✓', '✓' ),
			array( __( 'Copy free designs and prompts', 'vitalstack-ai-hub' ), __( '2 as visitor · 10/day', 'vitalstack-ai-hub' ), __( 'Unlimited', 'vitalstack-ai-hub' ) ),
			array( __( 'Pro motion designs + full code', 'vitalstack-ai-hub' ), '—', '✓' ),
			array( __( 'Detailed build prompts for AI tools', 'vitalstack-ai-hub' ), __( 'Free items', 'vitalstack-ai-hub' ), __( 'All items', 'vitalstack-ai-hub' ) ),
			array( __( 'Pro prompt packs + AI guides', 'vitalstack-ai-hub' ), '—', '✓' ),
			array( __( 'Pro shortcut cards (Cursor, SD, agents…)', 'vitalstack-ai-hub' ), '—', '✓' ),
			array( __( 'Ads', 'vitalstack-ai-hub' ), __( 'Yes', 'vitalstack-ai-hub' ), __( 'No ads', 'vitalstack-ai-hub' ) ),
		);
		foreach ( $rows as $r ) {
			echo '<tr><th scope="row">' . esc_html( $r[0] ) . '</th><td>' . esc_html( $r[1] ) . '</td><td>' . esc_html( $r[2] ) . '</td></tr>';
		}
		?>
		</tbody></table></div>
	</section>

	<section class="vsai-faq">
		<h2 class="vsai-h2"><?php esc_html_e( 'Questions', 'vitalstack-ai-hub' ); ?></h2>
		<?php
		$faq = array(
			array( __( 'Does it renew automatically?', 'vitalstack-ai-hub' ), __( 'No. You pay once for the period you choose. We email you before it ends; renew only if you want to.', 'vitalstack-ai-hub' ) ),
			array( __( 'Can I use the designs for clients?', 'vitalstack-ai-hub' ), __( 'Yes, in your own and your clients’ websites and apps. You can’t resell or republish them as a template or prompt pack.', 'vitalstack-ai-hub' ) ),
			array( __( 'Which AI tools do the prompts work with?', 'vitalstack-ai-hub' ), __( 'Any good coding assistant: Claude, ChatGPT, Gemini, Cursor, GitHub Copilot, Lovable, Bolt or v0. Each prompt describes the whole design so the tool can rebuild it.', 'vitalstack-ai-hub' ) ),
			array( __( 'Do I get the courses too?', 'vitalstack-ai-hub' ), __( 'AI Pro is the AI Hub. Career tracks with projects and certificates are on the main Premium page and are sold separately.', 'vitalstack-ai-hub' ) ),
			array( __( 'Is it safe to pay?', 'vitalstack-ai-hub' ), __( 'Payments run on Razorpay. We never see your card or UPI PIN, and access is given only after Razorpay confirms the payment.', 'vitalstack-ai-hub' ) ),
			array( __( 'Refunds?', 'vitalstack-ai-hub' ), __( 'Digital items are delivered instantly, so sales are final. Try the free designs and copies first; if something is broken, contact us and we will fix it.', 'vitalstack-ai-hub' ) ),
		);
		foreach ( $faq as $q ) {
			echo '<details class="vsai-q"><summary>' . esc_html( $q[0] ) . '</summary><p>' . esc_html( $q[1] ) . '</p></details>';
		}
		?>
	</section>
	<?php
}
