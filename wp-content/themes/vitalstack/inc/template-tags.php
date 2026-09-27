<?php
/**
 * Reusable markup helpers used across templates.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline SVG icons (Lucide-style, stroke based, inherit currentColor).
 */
function vitalstack_icon( $name, $size = 20 ) {
	$paths = array(
		'search'   => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
		'menu'     => '<path d="M4 6h16M4 12h16M4 18h16"/>',
		'close'    => '<path d="M18 6 6 18M6 6l12 12"/>',
		'sun'      => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
		'moon'     => '<path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>',
		'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'arrow-l'  => '<path d="M19 12H5M11 18l-6-6 6-6"/>',
		'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/>',
		'list'     => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
		'copy'     => '<rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1"/>',
		'check'    => '<path d="m5 12 5 5L20 7"/>',
		'link'     => '<path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7"/><path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7"/>',
		'code'     => '<path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/>',
		'spark'    => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M5.6 5.6l2.8 2.8M15.6 15.6l2.8 2.8M5.6 18.4l2.8-2.8M15.6 8.4l2.8-2.8"/>',
		'book'     => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5z"/><path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5"/>',
		'layers'   => '<path d="m12 2 10 5-10 5L2 7z"/><path d="m2 17 10 5 10-5M2 12l10 5 10-5"/>',
		'database' => '<ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5"/><path d="M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"/>',
		'server'   => '<rect x="3" y="3" width="18" height="7" rx="2"/><rect x="3" y="14" width="18" height="7" rx="2"/><path d="M7 6.5h.01M7 17.5h.01"/>',
		'cpu'      => '<rect x="5" y="5" width="14" height="14" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 2v3M15 2v3M9 19v3M15 19v3M2 9h3M2 15h3M19 9h3M19 15h3"/>',
		'brief'    => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
		'tool'     => '<path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-2.5 2.5-2.4-.6-.6-2.4z"/>',
		'news'     => '<path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9h4"/><path d="M18 14h-8M15 18h-5M10 6h8v4h-8z"/>',
		'heart'    => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1.1L12 21.2l7.8-7.8 1-1.1a5.5 5.5 0 0 0 0-7.7z"/>',
		'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
		'share'    => '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4M15.4 6.5l-6.8 4"/>',
		'play'     => '<path d="m7 4 13 8-13 8z"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return '<svg class="icon icon-' . esc_attr( $name ) . '" width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
}

/**
 * Brand icons for social links (filled).
 */
function vitalstack_brand_icon( $name ) {
	$paths = array(
		'youtube'   => '<path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8zM9.6 15.6V8.4l6.3 3.6z"/>',
		'instagram' => '<path d="M12 2.2c3.2 0 3.6 0 4.8.1 3.3.1 4.8 1.7 4.9 4.9.1 1.3.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 3.2-1.7 4.8-4.9 4.9-1.3.1-1.6.1-4.8.1s-3.6 0-4.8-.1c-3.3-.1-4.8-1.7-4.9-4.9C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.8C2.4 3.9 3.9 2.4 7.2 2.3c1.2-.1 1.6-.1 4.8-.1zM12 0C8.7 0 8.3 0 7.1.1 2.7.3.3 2.7.1 7.1 0 8.3 0 8.7 0 12s0 3.7.1 4.9c.2 4.4 2.6 6.8 7 7 1.2.1 1.6.1 4.9.1s3.7 0 4.9-.1c4.4-.2 6.8-2.6 7-7 .1-1.2.1-1.6.1-4.9s0-3.7-.1-4.9c-.2-4.4-2.6-6.8-7-7C15.7 0 15.3 0 12 0zm0 5.8a6.2 6.2 0 1 0 0 12.4 6.2 6.2 0 0 0 0-12.4zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.4-11.8a1.4 1.4 0 1 0 0 2.9 1.4 1.4 0 0 0 0-2.9z"/>',
		'linkedin'  => '<path d="M20.4 20.5h-3.6v-5.6c0-1.3 0-3-1.8-3s-2.1 1.4-2.1 2.9v5.7H9.3V9h3.4v1.6c.5-.9 1.6-1.8 3.4-1.8 3.6 0 4.3 2.4 4.3 5.5v6.2zM5.3 7.4a2.1 2.1 0 1 1 0-4.1 2.1 2.1 0 0 1 0 4.1zM7.1 20.5H3.6V9h3.5v11.5zM22.2 0H1.8C.8 0 0 .8 0 1.7v20.6c0 .9.8 1.7 1.8 1.7h20.4c1 0 1.8-.8 1.8-1.7V1.7C24 .8 23.2 0 22.2 0z"/>',
		'facebook'  => '<path d="M24 12a12 12 0 1 0-13.9 11.9v-8.4h-3V12h3V9.4c0-3 1.8-4.7 4.5-4.7 1.3 0 2.7.2 2.7.2v3h-1.5c-1.5 0-2 .9-2 1.9V12h3.4l-.5 3.5h-2.9v8.4A12 12 0 0 0 24 12z"/>',
		'x'         => '<path d="M18.2 2.3h3.4l-7.4 8.4 8.7 11.5h-6.8l-5.3-7-6.1 7H1.3l7.9-9L.9 2.3h7l4.8 6.4zm-1.2 17.9h1.9L6.9 4.2H4.9z"/>',
		'github'    => '<path d="M12 .3a12 12 0 0 0-3.8 23.4c.6.1.8-.3.8-.6v-2c-3.3.7-4-1.6-4-1.6-.6-1.4-1.4-1.8-1.4-1.8-1-.7.1-.7.1-.7 1.2.1 1.8 1.2 1.8 1.2 1 1.8 2.8 1.3 3.5 1 .1-.8.4-1.3.7-1.6-2.7-.3-5.5-1.3-5.5-5.9 0-1.3.5-2.4 1.2-3.2-.1-.3-.5-1.5.1-3.2 0 0 1-.3 3.3 1.2a11.5 11.5 0 0 1 6 0c2.3-1.5 3.3-1.2 3.3-1.2.7 1.7.2 2.9.1 3.2.8.8 1.2 1.9 1.2 3.2 0 4.6-2.8 5.6-5.5 5.9.4.4.8 1.1.8 2.2v3.3c0 .3.2.7.8.6A12 12 0 0 0 12 .3"/>',
		'whatsapp'  => '<path d="M17.5 14.4c-.3-.1-1.8-.9-2-1s-.5-.1-.7.1-.8 1-.9 1.2-.3.2-.6.1a8 8 0 0 1-4-3.5c-.3-.5.3-.5.9-1.6.1-.2 0-.4 0-.5l-.9-2.2c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.1.2 2.1 3.2 5.1 4.5 1.9.8 2.6.9 3.6.7.6-.1 1.8-.7 2-1.4.3-.7.3-1.3.2-1.4 0-.2-.3-.3-.6-.4zM12 21.8a9.8 9.8 0 0 1-5-1.4l-.4-.2-3.7 1 1-3.6-.2-.4A9.8 9.8 0 1 1 12 21.8zM20.5 3.5A11.8 11.8 0 0 0 1.9 17.6L.2 24l6.5-1.7A11.8 11.8 0 0 0 24 12a11.7 11.7 0 0 0-3.5-8.5z"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return '<svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
}

/**
 * Estimated reading time in minutes.
 */
function vitalstack_read_minutes( $post_id = null ) {
	$words = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ?: get_the_ID() ) ) );
	return max( 1, (int) ceil( $words / 220 ) );
}

/**
 * The most relevant term for a post, whatever its type.
 * For tutorials this is the top-level learning path.
 */
function vitalstack_primary_term( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$type    = get_post_type( $post_id );

	if ( 'tutorials' === $type ) {
		return vitalstack_tutorial_path( $post_id );
	}

	$taxonomy = 'news' === $type ? 'news_category' : 'category';

	// Respect Yoast's "primary category" when set.
	$primary_id = (int) get_post_meta( $post_id, '_yoast_wpseo_primary_' . $taxonomy, true );
	if ( $primary_id ) {
		$term = get_term( $primary_id, $taxonomy );
		if ( $term && ! is_wp_error( $term ) && has_term( $term->term_id, $taxonomy, $post_id ) ) {
			return $term;
		}
	}

	$terms = get_the_terms( $post_id, $taxonomy );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return null;
	}
	// Prefer the most specific (child) term.
	usort(
		$terms,
		function ( $a, $b ) {
			return (int) ( 0 === $a->parent ) - (int) ( 0 === $b->parent );
		}
	);
	return $terms[0];
}

/**
 * Picks an icon + colour family for a term, from its name.
 */
function vitalstack_term_style( $term, $post_type = 'post' ) {
	$name = $term ? strtolower( $term->slug . ' ' . $term->name ) : '';
	$map  = array(
		'database|sql|mongo'                        => array( 'database', 'amber' ),
		'backend|api|server|java'                   => array( 'server', 'violet' ),
		'frontend|html|css|javascript|react|web'    => array( 'code', 'blue' ),
		'fundamental|basics|oop|programming'        => array( 'layers', 'teal' ),
		'career|skill|job|income'                   => array( 'brief', 'rose' ),
		'tool|prompt|shortcut'                      => array( 'tool', 'amber' ),
		'artificial|ai|machine|ml|llm|agent|nlp'    => array( 'cpu', 'green' ),
		'health|wellness|mental|fitness'            => array( 'heart', 'rose' ),
	);
	foreach ( $map as $pattern => $style ) {
		if ( preg_match( '/' . $pattern . '/', $name ) ) {
			return $style;
		}
	}
	if ( 'news' === $post_type ) {
		return array( 'news', 'slate' );
	}
	if ( 'tutorials' === $post_type ) {
		return array( 'book', 'teal' );
	}
	return array( 'spark', 'green' );
}

/**
 * Article card used in every grid.
 *
 * @param int   $post_id Post ID.
 * @param array $args    { @type bool $featured Larger layout. @type string $lesson Lesson label. }
 */
function vitalstack_card( $post_id = null, $args = array() ) {
	$post_id  = $post_id ?: get_the_ID();
	$args     = wp_parse_args(
		$args,
		array(
			'featured' => false,
			'lesson'   => '',
		)
	);
	$type     = get_post_type( $post_id );
	$term     = vitalstack_primary_term( $post_id );
	$style    = vitalstack_term_style( $term, $type );
	$link     = get_permalink( $post_id );
	$title    = get_the_title( $post_id );
	$classes  = 'card' . ( $args['featured'] ? ' card--featured' : '' );
	$type_lbl = array(
		'tutorials' => __( 'Tutorial', 'vitalstack' ),
		'news'      => __( 'News', 'vitalstack' ),
	);
	?>
	<article class="<?php echo esc_attr( $classes ); ?>">
		<a class="card-media tone-<?php echo esc_attr( $style[1] ); ?>" href="<?php echo esc_url( $link ); ?>" tabindex="-1" aria-hidden="true">
			<?php
			$thumb = get_the_post_thumbnail(
				$post_id,
				$args['featured'] ? 'vitalstack-hero' : 'vitalstack-card',
				array(
					'alt'     => '',
					'loading' => 'lazy',
				)
			);
			if ( $thumb ) {
				echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput
			} else {
				echo '<span class="card-media-icon">' . vitalstack_icon( $style[0], 40 ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
			}
			?>
		</a>
		<div class="card-body">
			<div class="card-meta">
				<?php if ( $args['lesson'] ) : ?>
					<span class="pill pill-<?php echo esc_attr( $style[1] ); ?>"><?php echo esc_html( $args['lesson'] ); ?></span>
				<?php elseif ( $term ) : ?>
					<span class="pill pill-<?php echo esc_attr( $style[1] ); ?>"><?php echo esc_html( $term->name ); ?></span>
				<?php endif; ?>
				<?php if ( isset( $type_lbl[ $type ] ) && ! $args['lesson'] ) : ?>
					<span class="card-type"><?php echo esc_html( $type_lbl[ $type ] ); ?></span>
				<?php endif; ?>
			</div>
			<h3 class="card-title"><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a></h3>
			<?php if ( $args['featured'] ) : ?>
				<p class="card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt( $post_id ), 32 ) ); ?></p>
			<?php else : ?>
				<p class="card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt( $post_id ), 18 ) ); ?></p>
			<?php endif; ?>
			<div class="card-foot">
				<span><?php echo vitalstack_icon( 'clock', 14 ); // phpcs:ignore ?> <?php echo esc_html( sprintf( /* translators: %d minutes */ __( '%d min read', 'vitalstack' ), vitalstack_read_minutes( $post_id ) ) ); ?></span>
				<time datetime="<?php echo esc_attr( get_the_modified_date( 'c', $post_id ) ); ?>"><?php echo esc_html( get_the_modified_date( 'M j, Y', $post_id ) ); ?></time>
			</div>
		</div>
	</article>
	<?php
}

/**
 * Two-letter monogram avatar. Deliberately not Gravatar: authors can write
 * under a pen name without exposing a personal email-linked photo.
 */
function vitalstack_monogram( $author_id, $size = 'md' ) {
	$name     = get_the_author_meta( 'display_name', $author_id );
	$parts    = preg_split( '/\s+/', trim( $name ) );
	$initials = strtoupper( mb_substr( $parts[0], 0, 1 ) . ( count( $parts ) > 1 ? mb_substr( end( $parts ), 0, 1 ) : '' ) );
	return '<span class="monogram monogram-' . esc_attr( $size ) . '" aria-hidden="true">' . esc_html( $initials ) . '</span>';
}

/**
 * Byline under an article title.
 */
function vitalstack_byline() {
	$author_id = (int) get_post_field( 'post_author', get_the_ID() );
	$published = get_the_date( 'c' );
	$modified  = get_the_modified_date( 'c' );
	$updated   = ( strtotime( $modified ) - strtotime( $published ) ) > DAY_IN_SECONDS;
	?>
	<div class="byline">
		<?php echo vitalstack_monogram( $author_id ); // phpcs:ignore ?>
		<div class="byline-text">
			<a class="byline-author" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ); ?></a>
			<span class="byline-meta">
				<?php if ( $updated ) : ?>
					<?php esc_html_e( 'Updated', 'vitalstack' ); ?> <time datetime="<?php echo esc_attr( $modified ); ?>"><?php echo esc_html( get_the_modified_date() ); ?></time>
				<?php else : ?>
					<time datetime="<?php echo esc_attr( $published ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				<?php endif; ?>
				<span aria-hidden="true">·</span>
				<?php echo esc_html( sprintf( /* translators: %d minutes */ __( '%d min read', 'vitalstack' ), vitalstack_read_minutes() ) ); ?>
			</span>
		</div>
	</div>
	<?php
}

/**
 * Breadcrumb trail. Yoast outputs BreadcrumbList schema; this is the visible trail.
 */
function vitalstack_breadcrumbs() {
	$crumbs = array( array( home_url( '/' ), __( 'Home', 'vitalstack' ) ) );

	if ( is_singular( 'tutorials' ) ) {
		$crumbs[] = array( vitalstack_tutorials_url(), __( 'Tutorials', 'vitalstack' ) );
		$path     = vitalstack_tutorial_path();
		if ( $path ) {
			$crumbs[] = array( get_term_link( $path ), $path->name );
		}
	} elseif ( is_singular( 'news' ) ) {
		$crumbs[] = array( get_post_type_archive_link( 'news' ), __( 'News', 'vitalstack' ) );
	} elseif ( is_singular( 'post' ) ) {
		$term = vitalstack_primary_term();
		if ( $term ) {
			if ( $term->parent ) {
				$parent = get_term( $term->parent, 'category' );
				if ( $parent && ! is_wp_error( $parent ) ) {
					$crumbs[] = array( get_term_link( $parent ), $parent->name );
				}
			}
			$crumbs[] = array( get_term_link( $term ), $term->name );
		}
	} elseif ( is_tax( 'tutorial_category' ) ) {
		$crumbs[] = array( vitalstack_tutorials_url(), __( 'Tutorials', 'vitalstack' ) );
	}
	?>
	<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'vitalstack' ); ?>">
		<ol>
			<?php foreach ( $crumbs as $c ) : ?>
				<li><a href="<?php echo esc_url( $c[0] ); ?>"><?php echo esc_html( $c[1] ); ?></a></li>
			<?php endforeach; ?>
		</ol>
	</nav>
	<?php
}

/**
 * Author box at the end of articles. Uses the WordPress profile bio.
 */
function vitalstack_author_box() {
	$author_id = (int) get_post_field( 'post_author', get_the_ID() );
	$bio       = get_the_author_meta( 'description', $author_id );
	?>
	<aside class="author-box">
		<?php echo vitalstack_monogram( $author_id, 'lg' ); // phpcs:ignore ?>
		<div>
			<p class="author-box-label"><?php esc_html_e( 'Written by', 'vitalstack' ); ?></p>
			<p class="author-box-name"><a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ); ?></a></p>
			<?php if ( $bio ) : ?>
				<p class="author-box-bio"><?php echo esc_html( $bio ); ?></p>
			<?php endif; ?>
			<p class="author-box-links">
				<a href="<?php echo esc_url( vitalstack_page_url( 'editorial-policy' ) ); ?>"><?php esc_html_e( 'How we write and fact-check', 'vitalstack' ); ?></a>
				<span aria-hidden="true">·</span>
				<a href="<?php echo esc_url( vitalstack_page_url( 'contact-us' ) ); ?>"><?php esc_html_e( 'Report an error', 'vitalstack' ); ?></a>
			</p>
		</div>
	</aside>
	<?php
}

/**
 * Share buttons. WhatsApp first: it is how most Indian readers share.
 */
function vitalstack_share() {
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( html_entity_decode( get_the_title(), ENT_QUOTES ) );
	$links = array(
		'whatsapp' => array( 'WhatsApp', 'https://wa.me/?text=' . $title . '%20' . $url ),
		'linkedin' => array( 'LinkedIn', 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url ),
		'x'        => array( 'X', 'https://x.com/intent/post?url=' . $url . '&text=' . $title ),
	);
	?>
	<div class="share">
		<span class="share-label"><?php esc_html_e( 'Share', 'vitalstack' ); ?></span>
		<?php foreach ( $links as $key => $l ) : ?>
			<a class="share-btn" href="<?php echo esc_url( $l[1] ); ?>" target="_blank" rel="noopener nofollow" aria-label="<?php echo esc_attr( sprintf( /* translators: %s network */ __( 'Share on %s', 'vitalstack' ), $l[0] ) ); ?>"><?php echo vitalstack_brand_icon( $key ); // phpcs:ignore ?></a>
		<?php endforeach; ?>
		<button type="button" class="share-btn" data-copy-link="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php esc_attr_e( 'Copy link', 'vitalstack' ); ?>"><?php echo vitalstack_icon( 'link', 18 ); // phpcs:ignore ?></button>
	</div>
	<?php
}

/**
 * Related articles: same term first, then latest of the same type.
 */
function vitalstack_related( $count = 3 ) {
	$post_id = get_the_ID();
	$type    = get_post_type();
	$term    = vitalstack_primary_term();
	$args    = array(
		'post_type'           => $type,
		'posts_per_page'      => $count,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);
	if ( $term ) {
		$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
			array(
				'taxonomy' => $term->taxonomy,
				'terms'    => $term->term_id,
			),
		);
	}
	$q = new WP_Query( $args );
	if ( $q->post_count < $count && $term ) {
		unset( $args['tax_query'] );
		$args['post__not_in']   = array_merge( array( $post_id ), wp_list_pluck( $q->posts, 'ID' ) );
		$args['posts_per_page'] = $count - $q->post_count;
		$extra                  = new WP_Query( $args );
		$q->posts               = array_merge( $q->posts, $extra->posts );
	}
	if ( empty( $q->posts ) ) {
		return;
	}
	?>
	<section class="related" aria-labelledby="related-title">
		<h2 id="related-title" class="section-title"><?php esc_html_e( 'Keep reading', 'vitalstack' ); ?></h2>
		<div class="grid grid-3">
			<?php
			foreach ( $q->posts as $p ) {
				vitalstack_card( $p->ID );
			}
			?>
		</div>
	</section>
	<?php
}

function vitalstack_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => vitalstack_icon( 'arrow-l', 16 ) . '<span class="screen-reader-text">' . __( 'Previous page', 'vitalstack' ) . '</span>',
			'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'vitalstack' ) . '</span>' . vitalstack_icon( 'arrow', 16 ),
		)
	);
}

function vitalstack_social_links() {
	$out = '';
	foreach ( vitalstack_social_networks() as $id => $label ) {
		$url = get_theme_mod( 'vitalstack_social_' . $id, '' );
		if ( ! $url || '#' === $url ) {
			continue;
		}
		$out .= '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr( $label ) . '">' . vitalstack_brand_icon( $id ) . '</a>';
	}
	if ( $out ) {
		echo '<div class="social">' . $out . '</div>'; // phpcs:ignore
	}
}

/**
 * URL of a page by slug, falling back to home.
 */
function vitalstack_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

function vitalstack_tutorials_url() {
	$page = get_page_by_path( 'tutorials' );
	return $page ? get_permalink( $page ) : get_post_type_archive_link( 'tutorials' );
}

function vitalstack_logo() {
	// custom_logo, or the logo uploaded through the v1 theme's own setting.
	$logo_id = get_theme_mod( 'custom_logo' ) ?: get_theme_mod( 'vitalstack_header_logo' );
	if ( $logo_id && wp_attachment_is_image( $logo_id ) ) {
		echo wp_get_attachment_image(
			$logo_id,
			'full',
			false,
			array(
				'class'   => 'logo-img',
				'alt'     => get_bloginfo( 'name' ),
				'loading' => 'eager',
			)
		);
		return;
	}
	echo '<span class="logo-mark" aria-hidden="true">' . vitalstack_icon( 'layers', 20 ) . '</span><span class="logo-text">Vital<span>Stack</span></span>'; // phpcs:ignore
}

/* ── Menu fallbacks (used until menus are assigned in Appearance → Menus) ── */

function vitalstack_menu_list( $items, $class = 'menu' ) {
	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $items as $item ) {
		echo '<li><a href="' . esc_url( $item[0] ) . '">' . esc_html( $item[1] ) . '</a></li>';
	}
	echo '</ul>';
}

function vitalstack_category_url( $slug ) {
	$term = get_category_by_slug( $slug );
	return $term ? get_category_link( $term ) : home_url( '/' );
}

function vitalstack_default_menu( $args = array() ) {
	vitalstack_menu_list(
		array(
			array( vitalstack_tutorials_url(), __( 'Tutorials', 'vitalstack' ) ),
			array( vitalstack_category_url( 'artificial-intelligence' ), __( 'AI Guides', 'vitalstack' ) ),
			array( vitalstack_category_url( 'ai-tools' ), __( 'AI Tools', 'vitalstack' ) ),
			array( vitalstack_category_url( 'ai-career' ), __( 'Careers', 'vitalstack' ) ),
			array( vitalstack_page_url( 'about-us' ), __( 'About', 'vitalstack' ) ),
		),
		isset( $args['menu_class'] ) ? $args['menu_class'] : 'menu'
	);
}

function vitalstack_footer_learn_fallback() {
	$items = array();
	foreach ( vitalstack_learning_paths() as $path ) {
		$items[] = array( get_term_link( $path ), $path->name );
	}
	$items[] = array( vitalstack_category_url( 'artificial-intelligence' ), __( 'AI Guides', 'vitalstack' ) );
	$items[] = array( vitalstack_category_url( 'ai-career' ), __( 'AI Careers', 'vitalstack' ) );
	vitalstack_menu_list( $items );
}

function vitalstack_footer_company_fallback() {
	vitalstack_menu_list(
		array(
			array( vitalstack_page_url( 'about-us' ), __( 'About', 'vitalstack' ) ),
			array( vitalstack_page_url( 'editorial-policy' ), __( 'Editorial Policy', 'vitalstack' ) ),
			array( vitalstack_page_url( 'contact-us' ), __( 'Contact', 'vitalstack' ) ),
		)
	);
}

function vitalstack_footer_legal_fallback() {
	vitalstack_menu_list(
		array(
			array( vitalstack_page_url( 'privacy-policy' ), __( 'Privacy Policy', 'vitalstack' ) ),
			array( vitalstack_page_url( 'terms-of-service' ), __( 'Terms of Service', 'vitalstack' ) ),
			array( vitalstack_page_url( 'disclaimer' ), __( 'Disclaimer', 'vitalstack' ) ),
			array( vitalstack_page_url( 'cookie-policy' ), __( 'Cookie Policy', 'vitalstack' ) ),
		)
	);
}
