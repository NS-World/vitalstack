<?php
/**
 * Search-engine related behaviour. With an SEO plugin (Yoast, Rank Math…)
 * the plugin owns titles, descriptions and canonicals; without one, the theme
 * adds sensible ones.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vitalstack_news_is_noindex() {
	return (bool) get_theme_mod( 'vitalstack_noindex_news', false );
}

function vitalstack_is_news_view() {
	return is_singular( 'news' ) || is_post_type_archive( 'news' ) || is_tax( 'news_category' ) || is_page_template( 'page-news.php' );
}

/**
 * noindex,follow for the News section when enabled in the Customizer.
 * Covers both core robots output and Yoast (which replaces it).
 */
function vitalstack_robots_core( $robots ) {
	if ( vitalstack_news_is_noindex() && vitalstack_is_news_view() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
		unset( $robots['index'] );
	}
	return $robots;
}
add_filter( 'wp_robots', 'vitalstack_robots_core' );

function vitalstack_robots_yoast( $robots ) {
	if ( vitalstack_news_is_noindex() && vitalstack_is_news_view() ) {
		return 'noindex, follow';
	}
	return $robots;
}
add_filter( 'wpseo_robots', 'vitalstack_robots_yoast' );

/**
 * Keep noindexed news out of the XML sitemaps too.
 */
function vitalstack_sitemap_exclude_news( $excluded, $post_type ) {
	return ( 'news' === $post_type && vitalstack_news_is_noindex() ) ? true : $excluded;
}
add_filter( 'wpseo_sitemap_exclude_post_type', 'vitalstack_sitemap_exclude_news', 10, 2 );

function vitalstack_sitemap_exclude_news_tax( $excluded, $taxonomy ) {
	return ( 'news_category' === $taxonomy && vitalstack_news_is_noindex() ) ? true : $excluded;
}
add_filter( 'wpseo_sitemap_exclude_taxonomy', 'vitalstack_sitemap_exclude_news_tax', 10, 2 );

function vitalstack_core_sitemap_exclude_news( $post_types ) {
	if ( vitalstack_news_is_noindex() ) {
		unset( $post_types['news'] );
	}
	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'vitalstack_core_sitemap_exclude_news' );

/**
 * Descriptions for the theme's hub pages, whose content comes from the theme.
 */
function vitalstack_hub_descriptions() {
	return array(
		'tutorials'       => __( 'Free step-by-step programming tutorials: HTML, CSS, JavaScript, Python, Java, Spring Boot, React, Angular, SQL and DSA, with live animations, quizzes and exercises.', 'vitalstack' ),
		'blog'            => __( 'Practical guides on AI tools, AI careers and programming, written for beginners and tested before they are published.', 'vitalstack' ),
		'games'           => __( 'Free two-minute brain games for programmers: predict Java output, spot OOP pillars, sort, search and more. Practise and earn XP.', 'vitalstack' ),
		'dsa-problems'    => __( 'Classic coding interview problems with step-by-step solutions, complexity and animated walkthroughs, filterable by pattern and level.', 'vitalstack' ),
		'daily-challenge' => __( 'One new coding question every day. Answer it, keep your streak going and climb the weekly leaderboard.', 'vitalstack' ),
		'cheat-sheets'    => __( 'Free printable cheat sheets for Java, Python, JavaScript, SQL, HTML, CSS, Spring Boot, React, Angular, Git and Big O.', 'vitalstack' ),
		'tools'           => __( 'Free online coding tools: Python compiler, SQL playground, JSON formatter, regex tester and HTML editor. Nothing to install.', 'vitalstack' ),
		'mock-tests'      => __( 'Free timed mock tests for Java, Python, SQL, JavaScript, Spring Boot, React, Angular, DSA and more, with every answer explained.', 'vitalstack' ),
		'roadmaps'        => __( 'Free career roadmaps: Java full stack, frontend, Python and a 12-week DSA plan for placements, with lessons and mock tests for every stage.', 'vitalstack' ),
		'contact-us'      => __( 'Contact VitalStack: report a mistake, suggest a lesson or ask a question. We usually reply within 2 working days.', 'vitalstack' ),
	);
}

/**
 * Meta description when no SEO plugin is active: excerpt, hub text, term
 * description, or the start of the content.
 */
function vitalstack_fallback_meta_description() {
	if ( vitalstack_seo_plugin_active() ) {
		return;
	}
	$desc = '';
	if ( is_front_page() ) {
		// The homepage subtitle (Customize → Homepage) says more than the short tagline.
		$desc = get_theme_mod( 'vitalstack_home_desc', vitalstack_default( 'home_desc' ) ) ?: get_bloginfo( 'description' );
	} elseif ( is_singular() ) {
		$id   = get_queried_object_id();
		$hubs = vitalstack_hub_descriptions();
		$slug = get_post_field( 'post_name', $id );
		$desc = get_the_excerpt( $id );
		if ( is_page() && isset( $hubs[ $slug ] ) && ! has_excerpt( $id ) ) {
			$desc = $hubs[ $slug ];
		} elseif ( is_page() && function_exists( 'vitalstack_site_pages' ) && isset( vitalstack_site_pages()[ $slug ] ) && ! has_excerpt( $id ) ) {
			return; // vitalstack_site_page_description() prints it.
		}
		if ( mb_strlen( trim( wp_strip_all_tags( $desc ) ) ) < 40 ) {
			$desc = wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $id ) ) );
		}
	} elseif ( is_post_type_archive( 'tutorials' ) ) {
		$desc = vitalstack_hub_descriptions()['tutorials'];
	} elseif ( is_category() || is_tax( 'tutorial_category' ) ) {
		$term = get_queried_object();
		$desc = $term->description;
		if ( ! $desc ) {
			$desc = is_category()
				/* translators: %s: topic */
				? sprintf( __( 'Guides about %s on VitalStack: practical, tested and written for beginners.', 'vitalstack' ), $term->name )
				/* translators: %s: subject */
				: sprintf( __( 'Free %s tutorial: step-by-step lessons with examples, live animations, quizzes and exercises.', 'vitalstack' ), $term->name );
		}
	}
	$desc = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $desc ) ) );
	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( wp_trim_words( $desc, 30, '…' ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'vitalstack_fallback_meta_description', 2 );

/**
 * Canonical link for the homepage and archives (WordPress adds one only for
 * single posts and pages). Paginated archives point to their own page.
 */
function vitalstack_archive_canonical() {
	if ( vitalstack_seo_plugin_active() || is_singular() || is_search() || is_404() ) {
		return;
	}
	if ( ! ( is_front_page() || is_home() || is_category() || is_tag() || is_tax() || is_post_type_archive() || is_author() ) ) {
		return;
	}
	$paged = max( 1, (int) get_query_var( 'paged' ) );
	$url   = remove_query_arg( array_keys( $_GET ), get_pagenum_link( $paged ) ); // phpcs:ignore WordPress.Security.NonceVerification -- only stripping query args
	echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
}
add_action( 'wp_head', 'vitalstack_archive_canonical', 3 );

/**
 * Thin listing pages (tags, difficulty levels, the single author archive)
 * repeat what the learning paths and categories already show. Keep them out
 * of search results and the sitemap so Google sees only the real content.
 */
function vitalstack_is_thin_archive() {
	return is_tag() || is_tax( 'tutorial_difficulty' ) || is_author();
}

function vitalstack_robots_thin( $robots ) {
	if ( ! vitalstack_seo_plugin_active() && vitalstack_is_thin_archive() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
		unset( $robots['index'] );
	}
	return $robots;
}
add_filter( 'wp_robots', 'vitalstack_robots_thin' );

function vitalstack_sitemap_skip_thin_taxonomies( $taxonomies ) {
	unset( $taxonomies['post_tag'], $taxonomies['tutorial_difficulty'] );
	return $taxonomies;
}
add_filter( 'wp_sitemaps_taxonomies', 'vitalstack_sitemap_skip_thin_taxonomies' );

function vitalstack_sitemap_skip_users( $provider, $name ) {
	return 'users' === $name ? false : $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'vitalstack_sitemap_skip_users', 10, 2 );

/**
 * Old social posts link to vitalstack.co.in/news/<slug>/ from the previous
 * site. When that page no longer exists, send the visitor somewhere useful
 * instead of a 404: a guide or lesson with the same slug if there is one,
 * otherwise the guides page.
 */
function vitalstack_redirect_old_news() {
	if ( ! is_404() ) {
		return;
	}
	$path = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- only compared and sanitized below
	if ( ! preg_match( '#^news(?:-category)?(?:/|$)#', $path ) ) {
		return;
	}
	$slug   = sanitize_title( basename( $path ) );
	$target = '';
	if ( $slug && ! in_array( $slug, array( 'news', 'news-category' ), true ) ) {
		$match = get_posts(
			array(
				'name'             => $slug,
				'post_type'        => array( 'post', 'tutorials' ),
				'post_status'      => 'publish',
				'numberposts'      => 1,
				'suppress_filters' => true,
			)
		);
		if ( $match ) {
			$target = get_permalink( $match[0] );
		}
	}
	if ( ! $target ) {
		$blog   = get_page_by_path( 'blog' );
		$target = $blog && 'publish' === $blog->post_status ? get_permalink( $blog ) : home_url( '/' );
	}
	wp_safe_redirect( $target, 301 );
	exit;
}
add_action( 'template_redirect', 'vitalstack_redirect_old_news', 1 );
