<?php
/**
 * Article content enhancements: heading anchors, table of contents,
 * scrollable tables.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Collected headings of the article being rendered.
 *
 * @var array<int, array{level:int, id:string, text:string}>
 */
$GLOBALS['vitalstack_toc'] = array();

/**
 * Adds an id to every h2/h3 in the main article and records it for the TOC.
 */
function vitalstack_prepare_content( $content ) {
	if ( ! is_singular() || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$used                      = array();
	$GLOBALS['vitalstack_toc'] = array();

	$content = preg_replace_callback(
		'#<h([23])([^>]*)>(.*?)</h\1>#is',
		function ( $m ) use ( &$used ) {
			$level = (int) $m[1];
			$attrs = $m[2];
			$text  = trim( wp_strip_all_tags( $m[3] ) );
			if ( '' === $text ) {
				return $m[0];
			}

			if ( preg_match( '/\bid=["\']([^"\']+)["\']/', $attrs, $idm ) ) {
				$id = $idm[1];
			} else {
				$id = sanitize_title( $text );
				$id = $id ? $id : 'section';
				$base = $id;
				$n    = 2;
				while ( isset( $used[ $id ] ) ) {
					$id = $base . '-' . $n++;
				}
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			}
			$used[ $id ] = true;

			$GLOBALS['vitalstack_toc'][] = array(
				'level' => $level,
				'id'    => $id,
				'text'  => html_entity_decode( $text, ENT_QUOTES ),
			);

			return '<h' . $level . $attrs . '>' . $m[3] . '<a class="heading-anchor" href="#' . esc_attr( $id ) . '" aria-label="' . esc_attr__( 'Link to this section', 'vitalstack' ) . '">#</a></h' . $level . '>';
		},
		$content
	);

	// Wide tables scroll inside their own box instead of breaking the layout on phones.
	$content = preg_replace( '#<table#i', '<div class="table-scroll"><table', $content );
	$content = preg_replace( '#</table>#i', '</table></div>', $content );

	return $content;
}
add_filter( 'the_content', 'vitalstack_prepare_content', 20 );

/**
 * Renders the table of contents collected while rendering the content.
 * Call after the content has been rendered.
 */
function vitalstack_toc( $min = 3 ) {
	$items = $GLOBALS['vitalstack_toc'];
	if ( count( $items ) < $min ) {
		return;
	}
	?>
	<nav class="toc" aria-labelledby="toc-title">
		<p id="toc-title" class="toc-title"><?php echo vitalstack_icon( 'list', 16 ); // phpcs:ignore ?> <?php esc_html_e( 'On this page', 'vitalstack' ); ?></p>
		<ol class="toc-list">
			<?php foreach ( $items as $item ) : ?>
				<li class="toc-l<?php echo (int) $item['level']; ?>"><a href="#<?php echo esc_attr( $item['id'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a></li>
			<?php endforeach; ?>
		</ol>
	</nav>
	<?php
}

/**
 * Renders the post content once and returns it, so the TOC can be built
 * before the markup is printed.
 */
function vitalstack_get_rendered_content() {
	$content = apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
	return str_replace( ']]>', ']]&gt;', $content );
}

/**
 * One-time wording fix for lessons imported before 4.3.0: the Python
 * introduction pointed readers to another site's compiler instead of ours.
 * Writes the row directly so no "updated" email or revision is triggered.
 */
function vitalstack_fix_lesson_wording() {
	if ( get_option( 'vitalstack_wording_fix' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	update_option( 'vitalstack_wording_fix', 1, false );

	global $wpdb;
	$old = 'with free tools like <strong>Google Colab</strong>, <strong>Replit</strong> or <strong>Programiz Online Compiler</strong>.';
	$new = 'with our free <a href="' . esc_url( home_url( '/tools/python-online-compiler/' ) ) . '">Python Online Compiler</a> or <strong>Google Colab</strong>.';
	$ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'tutorials' AND post_content LIKE %s", '%' . $wpdb->esc_like( 'Programiz Online Compiler' ) . '%' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	foreach ( $ids as $id ) {
		$content = get_post_field( 'post_content', $id, 'raw' );
		if ( false !== strpos( $content, $old ) ) {
			$wpdb->update( $wpdb->posts, array( 'post_content' => str_replace( $old, $new, $content ) ), array( 'ID' => $id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			clean_post_cache( $id );
		}
	}
}
add_action( 'admin_init', 'vitalstack_fix_lesson_wording' );
