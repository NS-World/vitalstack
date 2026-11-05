<?php
/**
 * Plugin Name: VitalStack AdSense Helper
 * Description: Tools → AdSense Helper. Publish your scheduled lessons now (or N per day) and run an AdSense readiness check: policy pages, placeholders, thin pages, links to unpublished lessons, empty categories, ads.txt and more. Works with any theme; changes nothing until you press a button.
 * Version: 1.1.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: VitalStack
 * License: GPL-2.0-or-later
 * Text Domain: vitalstack-adsense-helper
 *
 * @package VitalStackAdSenseHelper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VSAH_VERSION', '1.1.0' );

/* ── Content helpers ───────────────────────────────────────────────────── */

/**
 * Public post types that hold readable content (posts, lessons, news, pages).
 */
function vsah_content_types() {
	$types = get_post_types( array( 'public' => true ), 'names' );
	unset( $types['attachment'] );
	return array_values( $types );
}

/**
 * Scheduled (future) items, oldest first.
 *
 * @return WP_Post[]
 */
function vsah_scheduled() {
	return get_posts(
		array(
			'post_type'        => vsah_content_types(),
			'post_status'      => 'future',
			'numberposts'      => -1,
			'orderby'          => 'date',
			'order'            => 'ASC',
			'suppress_filters' => true,
		)
	);
}

/**
 * Publishes the given scheduled items now, keeping their order: the first
 * scheduled gets the earliest time, each next one a minute later, all in the past.
 *
 * @param WP_Post[] $posts Items to publish, oldest first.
 * @return int Number published.
 */
function vsah_publish_now( $posts ) {
	$now   = current_time( 'timestamp' ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested -- local time is what post_date stores
	$count = count( $posts );
	$done  = 0;
	foreach ( array_values( $posts ) as $i => $post ) {
		$local  = gmdate( 'Y-m-d H:i:s', $now - ( $count - $i ) * 60 );
		$result = wp_update_post(
			array(
				'ID'            => $post->ID,
				'post_status'   => 'publish',
				'post_date'     => $local,
				'post_date_gmt' => get_gmt_from_date( $local ),
				'edit_date'     => true,
			),
			true
		);
		if ( ! is_wp_error( $result ) && 'publish' === get_post_status( $post->ID ) ) {
			$done++;
		}
	}
	return $done;
}

/**
 * Reschedules items to $per_day a day, starting tomorrow at $hour:00 site
 * time, one hour apart within a day, keeping their order.
 *
 * @param WP_Post[] $posts   Items, oldest first.
 * @param int       $per_day Items per day (1–50).
 * @param int       $hour    First hour of the day (0–23).
 * @return array{0:int,1:string} Count rescheduled and the last date.
 */
function vsah_reschedule( $posts, $per_day, $hour = 9 ) {
	$per_day = max( 1, min( 50, (int) $per_day ) );
	$hour    = max( 0, min( 23, (int) $hour ) );
	$gap     = max( 10, (int) floor( ( 24 - $hour ) * 60 / $per_day ) ); // minutes between items
	$gap     = min( 60, $gap );
	$base    = strtotime( gmdate( 'Y-m-d', current_time( 'timestamp' ) ) . ' 00:00:00' ) + DAY_IN_SECONDS + $hour * HOUR_IN_SECONDS; // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
	$done    = 0;
	$last    = '';
	foreach ( array_values( $posts ) as $i => $post ) {
		$day    = (int) floor( $i / $per_day );
		$slot   = $i % $per_day;
		$local  = gmdate( 'Y-m-d H:i:s', $base + $day * DAY_IN_SECONDS + $slot * $gap * 60 );
		$result = wp_update_post(
			array(
				'ID'            => $post->ID,
				'post_status'   => 'future',
				'post_date'     => $local,
				'post_date_gmt' => get_gmt_from_date( $local ),
				'edit_date'     => true,
			),
			true
		);
		if ( ! is_wp_error( $result ) ) {
			$done++;
			$last = $local;
		}
	}
	return array( $done, $last );
}

/**
 * Plain-text word count of post content (shortcodes and HTML removed).
 */
function vsah_word_count( $content ) {
	$text = wp_strip_all_tags( strip_shortcodes( (string) $content ) );
	return count( preg_split( '/\s+/u', trim( $text ), -1, PREG_SPLIT_NO_EMPTY ) );
}

/**
 * Post content without code: <pre>/<code> blocks and code-block comments.
 * Lesson code such as `const [todos, setTodos]` or `<a href="...">` is
 * example text, not a placeholder or a real link.
 */
function vsah_strip_code( $content ) {
	$content = preg_replace( '#<pre\b[^>]*>.*?</pre>#is', ' ', (string) $content );
	$content = preg_replace( '#<code\b[^>]*>.*?</code>#is', ' ', $content );
	return preg_replace( '#<!--\s*wp:(?:code|preformatted|html)\b.*?<!--\s*/wp:(?:code|preformatted|html)\s*-->#is', ' ', $content );
}

/**
 * The publisher ID as "pub-1234567890123456" from the theme setting, Site
 * Kit, or the saved ads.txt field, or '' if none is known.
 */
function vsah_publisher_id() {
	$candidates = array(
		(string) get_option( 'vsah_publisher_id', '' ),
		(string) get_theme_mod( 'vitalstack_adsense_client', '' ),
	);
	$sitekit = get_option( 'googlesitekit_adsense_settings' );
	if ( is_array( $sitekit ) ) {
		$candidates[] = isset( $sitekit['clientID'] ) ? (string) $sitekit['clientID'] : '';
		$candidates[] = isset( $sitekit['accountID'] ) ? (string) $sitekit['accountID'] : '';
	}
	foreach ( $candidates as $c ) {
		if ( preg_match( '/pub-(\d{10,20})/', $c, $m ) ) {
			return 'pub-' . $m[1];
		}
	}
	return '';
}

/**
 * Current /ads.txt text: the real file if there is one, otherwise what the
 * site answers over HTTP (the theme can serve it virtually).
 */
function vsah_read_ads_txt() {
	$file = ABSPATH . 'ads.txt';
	if ( file_exists( $file ) ) {
		return (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local file
	}
	$res = wp_remote_get(
		add_query_arg( 'vsah', time(), home_url( '/ads.txt' ) ),
		array(
			'timeout'   => 8,
			'sslverify' => false, // Loopback request to this same site.
		)
	);
	if ( ! is_wp_error( $res ) && 200 === (int) wp_remote_retrieve_response_code( $res ) ) {
		return (string) wp_remote_retrieve_body( $res );
	}
	return '';
}

/**
 * Writes the google.com line into the real ads.txt in the site root,
 * keeping any other lines already there.
 *
 * @return bool|string True on success, or an error message.
 */
function vsah_write_ads_txt( $pub ) {
	$file    = ABSPATH . 'ads.txt';
	$line    = 'google.com, ' . $pub . ', DIRECT, f08c47fec0942fa0';
	$current = file_exists( $file ) ? (string) file_get_contents( $file ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$lines   = preg_split( '/\r\n|\r|\n/', $current );
	$lines   = array_filter(
		$lines,
		function ( $l ) {
			return '' !== trim( $l ) && ! preg_match( '/^\s*google\.com\s*,/i', $l );
		}
	);
	array_unshift( $lines, $line );
	$ok = @file_put_contents( $file, implode( "\n", $lines ) . "\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions,WordPress.PHP.NoSilencedErrors -- one small file in the site root
	return false === $ok ? __( 'Could not write ads.txt in the site root (no permission). Create it in Hostinger → File Manager → public_html instead.', 'vitalstack-adsense-helper' ) : true;
}

/**
 * Refreshed lessons shipped with the plugin (updates.json).
 *
 * @return array<int, array{slug:string,title:string,content:string}>
 */
function vsah_bundled_updates() {
	$file = __DIR__ . '/updates.json';
	if ( ! file_exists( $file ) ) {
		return array();
	}
	$data = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	return isset( $data['items'] ) && is_array( $data['items'] ) ? $data['items'] : array();
}

/**
 * Bundled updates whose published post is shorter than the bundled version.
 *
 * @return array<int, array{0:WP_Post,1:array}>
 */
function vsah_pending_updates() {
	$out = array();
	foreach ( vsah_bundled_updates() as $item ) {
		if ( empty( $item['slug'] ) || empty( $item['content'] ) ) {
			continue;
		}
		$post = get_page_by_path( $item['slug'], OBJECT, vsah_content_types() );
		if ( $post && vsah_word_count( $post->post_content ) < vsah_word_count( $item['content'] ) ) {
			$out[] = array( $post, $item );
		}
	}
	return $out;
}

/* ── Readiness checks ──────────────────────────────────────────────────── */

/**
 * Finds a page by any of the given slugs (published or not).
 */
function vsah_find_page( $slugs ) {
	foreach ( (array) $slugs as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			return $page;
		}
	}
	return null;
}

/**
 * Runs every check. Each result: [status pass|warn|fail, title, detail, items[], fix].
 *
 * @return array<int, array>
 */
function vsah_checks() {
	$out   = array();
	$types = vsah_content_types();

	// 1. Search engines allowed.
	$public = (int) get_option( 'blog_public', 1 );
	$out[]  = array(
		$public ? 'pass' : 'fail',
		__( 'Search engines can see the site', 'vitalstack-adsense-helper' ),
		$public ? __( 'Settings → Reading allows search engines.', 'vitalstack-adsense-helper' ) : __( '"Discourage search engines" is ticked. AdSense and Google cannot review a hidden site.', 'vitalstack-adsense-helper' ),
		array(),
		$public ? '' : __( 'Settings → Reading → untick "Discourage search engines from indexing this site" → Save.', 'vitalstack-adsense-helper' ),
	);

	// 2. HTTPS and permalinks.
	$https  = 0 === strpos( home_url(), 'https://' );
	$pretty = (bool) get_option( 'permalink_structure' );
	$out[]  = array(
		$https && $pretty ? 'pass' : 'warn',
		__( 'HTTPS and clean links', 'vitalstack-adsense-helper' ),
		( $https ? __( 'The site uses HTTPS.', 'vitalstack-adsense-helper' ) : __( 'The site address is not HTTPS.', 'vitalstack-adsense-helper' ) ) . ' ' . ( $pretty ? __( 'Permalinks are readable.', 'vitalstack-adsense-helper' ) : __( 'Permalinks are "Plain" (?p=123).', 'vitalstack-adsense-helper' ) ),
		array(),
		$https && $pretty ? '' : __( 'Settings → General: use https:// in both addresses. Settings → Permalinks: choose "Post name".', 'vitalstack-adsense-helper' ),
	);

	// 3. Required pages published.
	$required = array(
		__( 'About Us', 'vitalstack-adsense-helper' )         => array( 'about-us', 'about' ),
		__( 'Contact Us', 'vitalstack-adsense-helper' )       => array( 'contact-us', 'contact' ),
		__( 'Privacy Policy', 'vitalstack-adsense-helper' )   => array( 'privacy-policy', 'privacy' ),
		__( 'Terms of Service', 'vitalstack-adsense-helper' ) => array( 'terms-of-service', 'terms', 'terms-and-conditions' ),
		__( 'Disclaimer', 'vitalstack-adsense-helper' )       => array( 'disclaimer' ),
		__( 'Cookie Policy', 'vitalstack-adsense-helper' )    => array( 'cookie-policy', 'cookies' ),
		__( 'Editorial Policy', 'vitalstack-adsense-helper' ) => array( 'editorial-policy' ),
	);
	$missing = array();
	$draft   = array();
	foreach ( $required as $label => $slugs ) {
		$page = vsah_find_page( $slugs );
		if ( ! $page ) {
			$missing[] = $label;
		} elseif ( 'publish' !== $page->post_status ) {
			$draft[] = array( $label . ' (' . $page->post_status . ')', get_edit_post_link( $page->ID, 'raw' ) );
		}
	}
	$core_missing = array_intersect( $missing, array( __( 'About Us', 'vitalstack-adsense-helper' ), __( 'Contact Us', 'vitalstack-adsense-helper' ), __( 'Privacy Policy', 'vitalstack-adsense-helper' ) ) );
	$status       = $core_missing || $draft ? 'fail' : ( $missing ? 'warn' : 'pass' );
	$items        = $draft;
	foreach ( $missing as $m ) {
		$items[] = array( $m . ' — ' . __( 'missing', 'vitalstack-adsense-helper' ), '' );
	}
	$out[] = array(
		$status,
		__( 'About, Contact, Privacy and legal pages are published', 'vitalstack-adsense-helper' ),
		'pass' === $status ? __( 'All trust pages are live.', 'vitalstack-adsense-helper' ) : __( 'AdSense reviewers look for these pages. Pages still in draft count as missing.', 'vitalstack-adsense-helper' ),
		$items,
		'pass' === $status ? '' : __( 'Open each page, read it, fill in anything in [square brackets], then Publish. Missing pages: Appearance → VitalStack Setup → Run setup creates them.', 'vitalstack-adsense-helper' ),
	);

	// 4. Privacy policy mentions Google advertising cookies.
	$privacy = vsah_find_page( array( 'privacy-policy', 'privacy' ) );
	if ( ! $privacy ) {
		$pid     = (int) get_option( 'wp_page_for_privacy_policy' );
		$privacy = $pid ? get_post( $pid ) : null;
	}
	$text = $privacy ? strtolower( wp_strip_all_tags( $privacy->post_content ) ) : '';
	$ok   = $privacy && false !== strpos( $text, 'google' ) && false !== strpos( $text, 'cookie' ) && ( false !== strpos( $text, 'advertis' ) || false !== strpos( $text, 'adsense' ) );
	$out[] = array(
		$ok ? 'pass' : 'fail',
		__( 'Privacy Policy explains Google ads and cookies', 'vitalstack-adsense-helper' ),
		$ok ? __( 'It mentions Google, advertising and cookies, as AdSense requires.', 'vitalstack-adsense-helper' ) : __( 'AdSense requires your privacy policy to say that Google and other vendors use cookies to show ads, and how to opt out.', 'vitalstack-adsense-helper' ),
		array(),
		$ok ? '' : __( 'Add a section "Advertising (Google AdSense)" explaining Google\'s advertising cookies and a link to https://adssettings.google.com.', 'vitalstack-adsense-helper' ),
	);

	// 5. Placeholders and sample content in anything published.
	$published = get_posts(
		array(
			'post_type'        => $types,
			'post_status'      => 'publish',
			'numberposts'      => -1,
			'suppress_filters' => true,
		)
	);
	$placeholders = array();
	$patterns     = array( '/\[(your\b|one honest|add |insert\b|todo\b|tbd\b)[^\]]{0,80}\]/i', '/lorem ipsum/i', '/\bcoming soon\b/i', '/under construction/i', '/this is an example page/i', '/welcome to wordpress\. this is your first post/i' );
	foreach ( $published as $p ) {
		$prose = $p->post_title . ' ' . vsah_strip_code( $p->post_content );
		foreach ( $patterns as $re ) {
			if ( preg_match( $re, $prose, $m ) ) {
				$placeholders[] = array( get_the_title( $p ) . ' — “' . wp_strip_all_tags( $m[0] ) . '”', get_edit_post_link( $p->ID, 'raw' ) );
				break;
			}
		}
		if ( in_array( $p->post_name, array( 'hello-world', 'sample-page' ), true ) ) {
			$placeholders[] = array( get_the_title( $p ) . ' — ' . __( 'default WordPress sample', 'vitalstack-adsense-helper' ), get_edit_post_link( $p->ID, 'raw' ) );
		}
	}
	$out[] = array(
		$placeholders ? 'fail' : 'pass',
		__( 'No placeholders, "coming soon" or sample content', 'vitalstack-adsense-helper' ),
		$placeholders ? __( 'Unfinished text makes the whole site look "under construction", a common rejection reason.', 'vitalstack-adsense-helper' ) : __( 'No unfinished text found in published content.', 'vitalstack-adsense-helper' ),
		array_slice( $placeholders, 0, 40 ),
		$placeholders ? __( 'Edit each item: replace the bracketed text with real words, or delete sample posts and pages (Hello world!, Sample Page).', 'vitalstack-adsense-helper' ) : '',
	);

	// 6. Enough content.
	$articles = array_filter(
		$published,
		function ( $p ) {
			return 'page' !== $p->post_type;
		}
	);
	$n      = count( $articles );
	$status = $n >= 30 ? 'pass' : ( $n >= 15 ? 'warn' : 'fail' );
	$out[]  = array(
		$status,
		__( 'Enough published articles and lessons', 'vitalstack-adsense-helper' ),
		/* translators: %d: number of published items */
		sprintf( __( '%d published articles and lessons (excluding pages).', 'vitalstack-adsense-helper' ), $n ),
		array(),
		'pass' === $status ? '' : __( 'Aim for at least 30 substantial, original articles before applying.', 'vitalstack-adsense-helper' ),
	);

	// 7. Thin pages.
	$thin = array();
	foreach ( $articles as $p ) {
		$words = vsah_word_count( $p->post_content );
		if ( $words < 300 ) {
			$thin[] = array( get_the_title( $p ) . ' — ' . $words . ' ' . __( 'words', 'vitalstack-adsense-helper' ), get_edit_post_link( $p->ID, 'raw' ) );
		}
	}
	$share  = $n ? count( $thin ) / $n : 0;
	$out[]  = array(
		! $thin ? 'pass' : ( $share > 0.2 ? 'fail' : 'warn' ),
		__( 'No thin articles (under 300 words)', 'vitalstack-adsense-helper' ),
		$thin ? sprintf( /* translators: %d: count */ __( '%d published items have under 300 words. "Low value content" is the most common AdSense rejection.', 'vitalstack-adsense-helper' ), count( $thin ) ) : __( 'Every published article has at least 300 words.', 'vitalstack-adsense-helper' ),
		array_slice( $thin, 0, 40 ),
		$thin ? ( vsah_pending_updates() ? __( 'Press "Update thin lessons" above: the plugin has longer versions of some of these. Expand the rest with explanation and examples, or switch them to Draft until they are complete.', 'vitalstack-adsense-helper' ) : __( 'Expand these with explanation and examples, merge them into a related lesson, or switch them to Draft until they are complete.', 'vitalstack-adsense-helper' ) ) : '',
	);

	// 8. Links to content that is not published yet (drip-publishing problem).
	$home   = wp_parse_url( home_url(), PHP_URL_HOST );
	$broken = array();
	$seen   = array();
	foreach ( $published as $p ) {
		if ( ! preg_match_all( '/href=["\']([^"\'#?]+)/i', vsah_strip_code( $p->post_content ), $mm ) ) {
			continue;
		}
		foreach ( $mm[1] as $url ) {
			$host = wp_parse_url( $url, PHP_URL_HOST );
			if ( $host && $host !== $home ) {
				continue;
			}
			$path = trim( (string) wp_parse_url( $url, PHP_URL_PATH ), '/' );
			if ( '' === $path || preg_match( '#^(wp-|category/|tag/|tutorial-category/|author/|feed|page/)#', $path ) ) {
				continue;
			}
			$slug = basename( $path );
			if ( '' === sanitize_title( $slug ) ) {
				continue; // "...", "./" and similar: nothing a post could be called.
			}
			$target = get_page_by_path( $slug, OBJECT, $types );
			if ( ! $target ) {
				$target = get_page_by_path( $path, OBJECT, $types );
			}
			// Only content a visitor could have expected: auto-drafts and trash are never linked on purpose.
			if ( $target && in_array( $target->post_status, array( 'future', 'draft', 'pending', 'private' ), true ) ) {
				$key = $p->ID . '>' . $target->ID;
				if ( isset( $seen[ $key ] ) ) {
					continue;
				}
				$seen[ $key ] = 1;
				$broken[]     = array( get_the_title( $p ) . ' → ' . get_the_title( $target ) . ' (' . $target->post_status . ')', get_edit_post_link( $p->ID, 'raw' ) );
			}
		}
	}
	$out[] = array(
		$broken ? 'fail' : 'pass',
		__( 'No links to lessons that are not published yet', 'vitalstack-adsense-helper' ),
		$broken ? sprintf( /* translators: %d: count */ __( '%d links point to scheduled or draft content, so visitors and Google get "page not found".', 'vitalstack-adsense-helper' ), count( $broken ) ) : __( 'Every internal link points to a published page.', 'vitalstack-adsense-helper' ),
		array_slice( $broken, 0, 40 ),
		$broken ? __( 'Publish the scheduled lessons (button above) and this fixes itself.', 'vitalstack-adsense-helper' ) : '',
	);

	// 9. Empty categories and learning paths.
	$empty = array();
	foreach ( array( 'category', 'tutorial_category' ) as $tax ) {
		if ( ! taxonomy_exists( $tax ) ) {
			continue;
		}
		$terms = get_terms(
			array(
				'taxonomy'   => $tax,
				'hide_empty' => false,
			)
		);
		if ( is_wp_error( $terms ) ) {
			continue;
		}
		foreach ( $terms as $t ) {
			if ( 0 === (int) $t->count && 'uncategorized' !== $t->slug ) {
				$empty[] = array( $t->name . ' (' . $tax . ')', get_edit_term_link( $t->term_id, $tax ) );
			}
		}
	}
	$out[] = array(
		$empty ? 'warn' : 'pass',
		__( 'No empty categories or learning paths', 'vitalstack-adsense-helper' ),
		$empty ? __( 'Empty listing pages look unfinished, especially when they are in a menu.', 'vitalstack-adsense-helper' ) : __( 'Every category and learning path has published content.', 'vitalstack-adsense-helper' ),
		array_slice( $empty, 0, 40 ),
		$empty ? __( 'Publish lessons for them, or remove them from menus until they have content.', 'vitalstack-adsense-helper' ) : '',
	);

	// 10. AdSense code and ads.txt.
	$client      = trim( (string) get_theme_mod( 'vitalstack_adsense_client', '' ) );
	$sitekit     = defined( 'GOOGLESITEKIT_VERSION' );
	$pub         = vsah_publisher_id();
	$has_ads_txt = (bool) preg_match( '/google\.com\s*,\s*pub-\d+/i', vsah_read_ads_txt() );
	$has_code    = '' !== $client || $sitekit;
	if ( $has_code && $has_ads_txt ) {
		$fix = '';
	} elseif ( ! $has_code ) {
		$fix = __( 'Appearance → Customize → VitalStack: Google AdSense: paste your ca-pub-… ID (or connect Site Kit). Then use "Create ads.txt" above and purge the cache.', 'vitalstack-adsense-helper' );
	} else {
		$fix = __( 'Use "Create ads.txt" above: it writes a real ads.txt file in the site root with your publisher ID. Then LiteSpeed Cache → Purge All and open yoursite/ads.txt to confirm.', 'vitalstack-adsense-helper' );
	}
	$out[] = array(
		$has_code && $has_ads_txt ? 'pass' : 'fail',
		__( 'AdSense code and ads.txt', 'vitalstack-adsense-helper' ),
		( $has_code ? ( $sitekit && '' === $client ? __( 'The AdSense code is placed by Site Kit.', 'vitalstack-adsense-helper' ) : __( 'The AdSense code is set up.', 'vitalstack-adsense-helper' ) ) : __( 'No AdSense publisher ID found.', 'vitalstack-adsense-helper' ) ) . ' ' . ( $has_ads_txt ? __( '/ads.txt lists your publisher ID.', 'vitalstack-adsense-helper' ) : __( '/ads.txt was not found or has no google.com line.', 'vitalstack-adsense-helper' ) ) . ( $pub ? ' ' . sprintf( /* translators: %s: publisher ID */ __( 'Publisher ID: %s.', 'vitalstack-adsense-helper' ), $pub ) : '' ),
		array(),
		$fix,
	);

	// 11. Maintenance / coming-soon plugins.
	$blockers = array();
	foreach ( (array) get_option( 'active_plugins', array() ) as $plugin ) {
		if ( preg_match( '/coming-soon|maintenance|under-construction|seedprod|wp-maintenance-mode|cmp-coming/i', $plugin ) ) {
			$blockers[] = array( $plugin, admin_url( 'plugins.php' ) );
		}
	}
	$out[] = array(
		$blockers ? 'fail' : 'pass',
		__( 'No maintenance or "coming soon" mode', 'vitalstack-adsense-helper' ),
		$blockers ? __( 'A maintenance or coming-soon plugin is active: reviewers may see a placeholder page.', 'vitalstack-adsense-helper' ) : __( 'No maintenance or coming-soon plugin is active.', 'vitalstack-adsense-helper' ),
		$blockers,
		$blockers ? __( 'Deactivate it before applying.', 'vitalstack-adsense-helper' ) : '',
	);

	return $out;
}

/* ── Admin page ────────────────────────────────────────────────────────── */

function vsah_menu() {
	add_management_page( __( 'AdSense Helper', 'vitalstack-adsense-helper' ), __( 'AdSense Helper', 'vitalstack-adsense-helper' ), 'manage_options', 'vsah', 'vsah_page' );
}
add_action( 'admin_menu', 'vsah_menu' );

/**
 * Handles the two buttons. Only administrators, only with a valid nonce.
 */
function vsah_handle() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'vitalstack-adsense-helper' ) );
	}
	check_admin_referer( 'vsah_action' );
	$action = isset( $_POST['vsah_do'] ) ? sanitize_key( wp_unslash( $_POST['vsah_do'] ) ) : '';
	$posts  = vsah_scheduled();
	$msg    = '';
	if ( 'publish_all' === $action ) {
		$n   = vsah_publish_now( $posts );
		/* translators: %d: count */
		$msg = sprintf( __( 'Published %d scheduled items.', 'vitalstack-adsense-helper' ), $n );
	} elseif ( 'per_day' === $action ) {
		$per   = isset( $_POST['vsah_per_day'] ) ? absint( $_POST['vsah_per_day'] ) : 5;
		$first = isset( $_POST['vsah_first_now'] ) ? absint( $_POST['vsah_first_now'] ) : 0;
		$now   = $first ? array_slice( $posts, 0, $per ) : array();
		$later = $first ? array_slice( $posts, $per ) : $posts;
		$a     = vsah_publish_now( $now );
		list( $b, $last ) = vsah_reschedule( $later, $per );
		/* translators: 1: published now, 2: rescheduled, 3: per day, 4: last date */
		$msg = sprintf( __( 'Published %1$d now and rescheduled %2$d at %3$d a day (last one on %4$s).', 'vitalstack-adsense-helper' ), $a, $b, max( 1, min( 50, $per ) ), $last ? $last : '—' );
	} elseif ( 'ads_txt' === $action ) {
		$raw = isset( $_POST['vsah_pub'] ) ? sanitize_text_field( wp_unslash( $_POST['vsah_pub'] ) ) : '';
		if ( preg_match( '/pub-(\d{10,20})/', $raw, $m ) ) {
			update_option( 'vsah_publisher_id', 'pub-' . $m[1], false );
			$res = vsah_write_ads_txt( 'pub-' . $m[1] );
			/* translators: %s: URL of ads.txt */
			$msg = true === $res ? sprintf( __( 'ads.txt saved. Check it at %s', 'vitalstack-adsense-helper' ), home_url( '/ads.txt' ) ) : $res;
		} else {
			$msg = __( 'That is not a publisher ID. It looks like pub-1234567890123456 (AdSense → Account → Settings → Account information).', 'vitalstack-adsense-helper' );
		}
	} elseif ( 'update_thin' === $action ) {
		$n = 0;
		foreach ( vsah_pending_updates() as $pair ) {
			list( $post, $item ) = $pair;
			$result = wp_update_post(
				array(
					'ID'           => $post->ID,
					'post_content' => wp_slash( $item['content'] ),
				),
				true
			);
			if ( ! is_wp_error( $result ) ) {
				$n++;
			}
		}
		/* translators: %d: count */
		$msg = sprintf( __( 'Updated %d lessons with their longer versions. The old text is kept in each post\'s Revisions.', 'vitalstack-adsense-helper' ), $n );
	}
	if ( function_exists( 'do_action' ) ) {
		do_action( 'litespeed_purge_all' ); // LiteSpeed Cache: refresh pages and sitemaps.
	}
	set_transient( 'vsah_msg_' . get_current_user_id(), $msg, 300 );
	wp_safe_redirect( admin_url( 'tools.php?page=vsah' ) );
	exit;
}
add_action( 'admin_post_vsah', 'vsah_handle' );

function vsah_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$msg = get_transient( 'vsah_msg_' . get_current_user_id() );
	if ( $msg ) {
		delete_transient( 'vsah_msg_' . get_current_user_id() );
	}
	$scheduled = vsah_scheduled();
	$checks    = vsah_checks();
	$icons     = array(
		'pass' => '✅',
		'warn' => '⚠️',
		'fail' => '❌',
	);
	$fails     = count(
		array_filter(
			$checks,
			function ( $c ) {
				return 'fail' === $c[0];
			}
		)
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'AdSense Helper', 'vitalstack-adsense-helper' ); ?></h1>
		<?php if ( $msg ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo esc_html( $msg ); ?></p></div>
		<?php endif; ?>

		<h2><?php esc_html_e( '1. Scheduled content', 'vitalstack-adsense-helper' ); ?></h2>
		<?php if ( ! $scheduled ) : ?>
			<p><?php esc_html_e( 'Nothing is scheduled. Everything is published.', 'vitalstack-adsense-helper' ); ?></p>
		<?php else : ?>
			<p>
				<?php
				/* translators: 1: count, 2: last date */
				echo esc_html( sprintf( __( '%1$d items are scheduled. At the current pace the last one goes live on %2$s. AdSense only reviews what is published today.', 'vitalstack-adsense-helper' ), count( $scheduled ), get_the_date( '', end( $scheduled ) ) ) );
				reset( $scheduled );
				?>
			</p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin:12px 0 8px">
				<?php wp_nonce_field( 'vsah_action' ); ?>
				<input type="hidden" name="action" value="vsah">
				<input type="hidden" name="vsah_do" value="publish_all">
				<button type="submit" class="button button-primary button-hero" onclick="return confirm('<?php echo esc_js( __( 'Publish every scheduled item now?', 'vitalstack-adsense-helper' ) ); ?>')"><?php echo esc_html( sprintf( /* translators: %d: count */ __( 'Publish all %d now (recommended)', 'vitalstack-adsense-helper' ), count( $scheduled ) ) ); ?></button>
			</form>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin:8px 0 16px">
				<?php wp_nonce_field( 'vsah_action' ); ?>
				<input type="hidden" name="action" value="vsah">
				<input type="hidden" name="vsah_do" value="per_day">
				<?php esc_html_e( 'Or publish', 'vitalstack-adsense-helper' ); ?>
				<input type="number" name="vsah_per_day" value="10" min="1" max="50" style="width:70px">
				<?php esc_html_e( 'per day from tomorrow', 'vitalstack-adsense-helper' ); ?>
				<label style="margin-left:8px"><input type="checkbox" name="vsah_first_now" value="1" checked> <?php esc_html_e( 'and publish the first batch today', 'vitalstack-adsense-helper' ); ?></label>
				<button type="submit" class="button"><?php esc_html_e( 'Reschedule', 'vitalstack-adsense-helper' ); ?></button>
			</form>
			<details><summary><?php esc_html_e( 'See the scheduled list', 'vitalstack-adsense-helper' ); ?></summary>
				<table class="widefat striped" style="max-width:900px;margin-top:8px"><thead><tr><th><?php esc_html_e( 'Title', 'vitalstack-adsense-helper' ); ?></th><th><?php esc_html_e( 'Type', 'vitalstack-adsense-helper' ); ?></th><th><?php esc_html_e( 'Scheduled for', 'vitalstack-adsense-helper' ); ?></th></tr></thead><tbody>
				<?php foreach ( $scheduled as $p ) : ?>
					<tr><td><a href="<?php echo esc_url( get_edit_post_link( $p->ID ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></td><td><?php echo esc_html( $p->post_type ); ?></td><td><?php echo esc_html( $p->post_date ); ?></td></tr>
				<?php endforeach; ?>
				</tbody></table>
			</details>
		<?php endif; ?>

		<h2 style="margin-top:28px"><?php esc_html_e( '2. Quick fixes', 'vitalstack-adsense-helper' ); ?></h2>
		<?php $updates = vsah_pending_updates(); ?>
		<?php if ( $updates ) : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin:8px 0 16px">
				<?php wp_nonce_field( 'vsah_action' ); ?>
				<input type="hidden" name="action" value="vsah">
				<input type="hidden" name="vsah_do" value="update_thin">
				<p style="margin:0 0 6px">
					<?php
					/* translators: %s: list of lesson titles */
					echo esc_html( sprintf( __( 'Longer versions are ready for: %s.', 'vitalstack-adsense-helper' ), implode( ', ', array_map( 'get_the_title', wp_list_pluck( $updates, 0 ) ) ) ) );
					?>
				</p>
				<button type="submit" class="button button-primary"><?php echo esc_html( sprintf( /* translators: %d: count */ __( 'Update thin lessons (%d)', 'vitalstack-adsense-helper' ), count( $updates ) ) ); ?></button>
			</form>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin:8px 0 16px">
			<?php wp_nonce_field( 'vsah_action' ); ?>
			<input type="hidden" name="action" value="vsah">
			<input type="hidden" name="vsah_do" value="ads_txt">
			<label for="vsah_pub"><?php esc_html_e( 'AdSense publisher ID for ads.txt:', 'vitalstack-adsense-helper' ); ?></label>
			<input type="text" id="vsah_pub" name="vsah_pub" value="<?php echo esc_attr( vsah_publisher_id() ); ?>" placeholder="pub-1234567890123456" style="width:240px">
			<button type="submit" class="button"><?php esc_html_e( 'Create ads.txt', 'vitalstack-adsense-helper' ); ?></button>
			<br><span class="description"><?php esc_html_e( 'Writes a real ads.txt file in the site root (other lines in it are kept). Find the ID in AdSense → Account → Settings → Account information.', 'vitalstack-adsense-helper' ); ?></span>
		</form>

		<h2 style="margin-top:28px"><?php esc_html_e( '3. AdSense readiness check', 'vitalstack-adsense-helper' ); ?></h2>
		<p><?php echo $fails ? esc_html( sprintf( /* translators: %d: count */ _n( '%d item must be fixed before you apply.', '%d items must be fixed before you apply.', $fails, 'vitalstack-adsense-helper' ), $fails ) ) : esc_html__( 'No blocking problems found. You are ready to apply (or request a review).', 'vitalstack-adsense-helper' ); ?></p>
		<table class="widefat" style="max-width:1100px">
			<tbody>
			<?php foreach ( $checks as $c ) : ?>
				<tr>
					<td style="width:34px;font-size:20px;vertical-align:top"><?php echo esc_html( $icons[ $c[0] ] ); ?></td>
					<td>
						<strong><?php echo esc_html( $c[1] ); ?></strong><br>
						<span><?php echo esc_html( $c[2] ); ?></span>
						<?php if ( $c[3] ) : ?>
							<ul style="margin:6px 0 0 18px;list-style:disc">
								<?php foreach ( $c[3] as $it ) : ?>
									<li><?php echo $it[1] ? '<a href="' . esc_url( $it[1] ) . '">' . esc_html( $it[0] ) . '</a>' : esc_html( $it[0] ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<?php if ( $c[4] ) : ?>
							<p style="margin:6px 0 0"><em><?php esc_html_e( 'How to fix:', 'vitalstack-adsense-helper' ); ?></em> <?php echo esc_html( $c[4] ); ?></p>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<p style="margin-top:16px;color:#646970"><?php esc_html_e( 'This page only reads your site. Nothing changes until you press a button above. You can delete the plugin when you are done.', 'vitalstack-adsense-helper' ); ?></p>
	</div>
	<?php
}
