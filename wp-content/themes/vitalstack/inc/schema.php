<?php
/**
 * Structured data (JSON-LD) for rich results, and IndexNow pings.
 *
 * Yoast/Rank Math already describe articles, breadcrumbs and the site, so
 * those parts are printed only when no SEO plugin is active. Courses and
 * learning resources are always added: SEO plugins don't describe them.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_schema_org() {
	$logo = function_exists( 'vitalstack_brand_url' ) ? vitalstack_brand_url( 'icon-512.png' ) : '';
	return array(
		'@type' => 'Organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
		'logo'  => $logo,
		'sameAs' => array_values( function_exists( 'vitalstack_social_urls' ) ? vitalstack_social_urls() : array() ),
	);
}

/**
 * Course description for one learning path.
 */
function vitalstack_schema_course( $term ) {
	$lessons = vitalstack_path_lessons( $term );
	$desc    = $term->description ? $term->description : sprintf( /* translators: %s: subject */ __( 'Free step-by-step %s course with examples, quizzes and exercises.', 'vitalstack' ), $term->name );
	$hours   = max( 1, (int) round( count( $lessons ) * 12 / 60 ) );
	return array(
		'@type'             => 'Course',
		'name'              => sprintf( /* translators: %s: subject */ __( '%s Tutorial for Beginners', 'vitalstack' ), $term->name ),
		'description'       => wp_strip_all_tags( $desc ),
		'url'               => get_term_link( $term ),
		'provider'          => vitalstack_schema_org(),
		'inLanguage'        => 'en',
		'isAccessibleForFree' => true,
		'educationalLevel'  => 'Beginner',
		'numberOfLessons'   => count( $lessons ),
		'offers'            => array(
			'@type'    => 'Offer',
			'category' => 'Free',
			'price'    => 0,
			'priceCurrency' => 'USD',
		),
		'hasCourseInstance' => array(
			'@type'          => 'CourseInstance',
			'courseMode'     => 'Online',
			'courseWorkload' => 'PT' . $hours . 'H',
		),
	);
}

function vitalstack_print_schema( $data ) {
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
}

function vitalstack_schema() {
	$seo_plugin = function_exists( 'vitalstack_seo_plugin_active' ) && vitalstack_seo_plugin_active();

	if ( is_tax( 'tutorial_category' ) ) {
		$term = get_queried_object();
		if ( $term && ! $term->parent ) {
			vitalstack_print_schema( array( '@context' => 'https://schema.org' ) + vitalstack_schema_course( $term ) );
		}
	}

	// Tutorials hub: a list of courses (eligible for the course carousel).
	if ( is_page( 'tutorials' ) || is_post_type_archive( 'tutorials' ) ) {
		$items = array();
		foreach ( vitalstack_learning_paths() as $i => $term ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $i + 1,
				'item'     => vitalstack_schema_course( $term ),
			);
		}
		if ( $items ) {
			vitalstack_print_schema(
				array(
					'@context'        => 'https://schema.org',
					'@type'           => 'ItemList',
					'itemListElement' => $items,
				)
			);
		}
	}

	if ( is_singular( 'tutorials' ) ) {
		$post = get_queried_object();
		$path = vitalstack_tutorial_path( $post->ID );
		$data = array(
			'@context'            => 'https://schema.org',
			'@type'               => 'LearningResource',
			'name'                => get_the_title( $post ),
			'description'         => wp_strip_all_tags( get_the_excerpt( $post ) ),
			'url'                 => get_permalink( $post ),
			'learningResourceType' => 'Tutorial',
			'educationalLevel'    => 'Beginner',
			'inLanguage'          => 'en',
			'isAccessibleForFree' => true,
			'datePublished'       => get_the_date( 'c', $post ),
			'dateModified'        => vitalstack_updated_date( 'c', $post ),
			'author'              => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', $post->post_author ),
			),
			'publisher'           => vitalstack_schema_org(),
		);
		if ( $path ) {
			$data['isPartOf'] = vitalstack_schema_course( $path );
			$data['teaches']  = $path->name;
		}
		vitalstack_print_schema( $data );
	}

	if ( $seo_plugin ) {
		return;
	}

	// Without an SEO plugin: site, breadcrumbs and articles.
	if ( is_front_page() ) {
		vitalstack_print_schema(
			array(
				'@context'        => 'https://schema.org',
				'@type'           => 'WebSite',
				'name'            => get_bloginfo( 'name' ),
				'url'             => home_url( '/' ),
				'publisher'       => vitalstack_schema_org(),
				'potentialAction' => array(
					'@type'       => 'SearchAction',
					'target'      => home_url( '/?s={search_term_string}' ),
					'query-input' => 'required name=search_term_string',
				),
			)
		);
	}

	if ( is_singular( array( 'tutorials', 'post' ) ) ) {
		$post   = get_queried_object();
		$crumbs = array( array( home_url( '/' ), __( 'Home', 'vitalstack' ) ) );
		if ( 'tutorials' === $post->post_type ) {
			$crumbs[] = array( vitalstack_tutorials_url(), __( 'Tutorials', 'vitalstack' ) );
			$path     = vitalstack_tutorial_path( $post->ID );
			if ( $path ) {
				$crumbs[] = array( get_term_link( $path ), $path->name );
			}
		} else {
			$cat = get_the_category( $post->ID );
			if ( $cat ) {
				$crumbs[] = array( get_category_link( $cat[0] ), $cat[0]->name );
			}
		}
		$crumbs[] = array( get_permalink( $post ), get_the_title( $post ) );
		$list     = array();
		foreach ( $crumbs as $i => $c ) {
			$list[] = array(
				'@type'    => 'ListItem',
				'position' => $i + 1,
				'name'     => wp_strip_all_tags( $c[1] ),
				'item'     => $c[0],
			);
		}
		vitalstack_print_schema(
			array(
				'@context'        => 'https://schema.org',
				'@type'           => 'BreadcrumbList',
				'itemListElement' => $list,
			)
		);

		$article = array(
			'@context'         => 'https://schema.org',
			'@type'            => 'tutorials' === $post->post_type ? 'TechArticle' : 'Article',
			'headline'         => wp_strip_all_tags( get_the_title( $post ) ),
			'description'      => wp_strip_all_tags( get_the_excerpt( $post ) ),
			'mainEntityOfPage' => get_permalink( $post ),
			'datePublished'    => get_the_date( 'c', $post ),
			'dateModified'     => vitalstack_updated_date( 'c', $post ),
			'author'           => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', $post->post_author ),
				'url'   => get_author_posts_url( $post->post_author ),
			),
			'publisher'        => vitalstack_schema_org(),
			'image'            => has_post_thumbnail( $post ) ? get_the_post_thumbnail_url( $post, 'large' ) : vitalstack_brand_url( 'share.png' ),
		);
		vitalstack_print_schema( $article );
	}
}
add_action( 'wp_head', 'vitalstack_schema', 15 );

/* ── IndexNow: tell Bing, Yandex and others about new pages right away ── */

function vitalstack_indexnow_key() {
	$key = get_option( 'vitalstack_indexnow_key' );
	if ( ! $key ) {
		$key = wp_generate_password( 32, false, false );
		$key = strtolower( preg_replace( '/[^a-zA-Z0-9]/', '', $key ) );
		update_option( 'vitalstack_indexnow_key', $key, false );
	}
	return $key;
}

/**
 * Serve /<key>.txt so search engines can verify the key.
 */
function vitalstack_indexnow_serve_key() {
	$path = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';
	if ( ! preg_match( '#/([a-z0-9]{32})\.txt$#', $path, $m ) ) {
		return;
	}
	if ( get_option( 'vitalstack_indexnow_key' ) !== $m[1] ) {
		return;
	}
	header( 'Content-Type: text/plain; charset=utf-8' );
	echo esc_html( $m[1] );
	exit;
}
add_action( 'init', 'vitalstack_indexnow_serve_key', 0 );

/**
 * Ping IndexNow when a public post, lesson or page is published or updated.
 */
function vitalstack_indexnow_ping( $new_status, $old_status, $post ) {
	if ( 'publish' !== $new_status || wp_is_post_revision( $post ) || wp_is_post_autosave( $post ) ) {
		return;
	}
	if ( ! in_array( $post->post_type, array( 'post', 'page', 'tutorials' ), true ) || ! get_option( 'blog_public' ) ) {
		return;
	}
	$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
	if ( ! $host || 'localhost' === $host || preg_match( '/\.(local|test)$|hostingersite\.com$/', $host ) ) {
		return; // Temporary and local domains are never worth announcing.
	}
	if ( get_transient( 'vs_indexnow_' . $post->ID ) ) {
		return; // At most one ping per post per hour.
	}
	set_transient( 'vs_indexnow_' . $post->ID, 1, HOUR_IN_SECONDS );
	wp_remote_get(
		add_query_arg(
			array(
				'url' => rawurlencode( get_permalink( $post ) ),
				'key' => vitalstack_indexnow_key(),
			),
			'https://api.indexnow.org/indexnow'
		),
		array(
			'timeout'  => 3,
			'blocking' => false,
		)
	);
}
add_action( 'transition_post_status', 'vitalstack_indexnow_ping', 20, 3 );
add_action( 'init', 'vitalstack_indexnow_key' );
