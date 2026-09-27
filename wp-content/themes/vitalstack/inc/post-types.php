<?php
/**
 * Custom post types, taxonomies and tutorial meta.
 *
 * Slugs, taxonomy names and meta keys are unchanged from v1 so existing
 * content and URLs keep working.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vitalstack_register_content_types() {
	register_post_type(
		'news',
		array(
			'labels'        => array(
				'name'          => __( 'News', 'vitalstack' ),
				'singular_name' => __( 'News Item', 'vitalstack' ),
				'add_new_item'  => __( 'Add New News Item', 'vitalstack' ),
				'edit_item'     => __( 'Edit News Item', 'vitalstack' ),
				'all_items'     => __( 'All News', 'vitalstack' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'rewrite'       => array(
				'slug'       => 'news',
				'with_front' => false,
			),
			'menu_position' => 5,
			'menu_icon'     => 'dashicons-megaphone',
			'supports'      => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
			'show_in_rest'  => true,
			'taxonomies'    => array( 'news_category', 'post_tag' ),
		)
	);

	register_taxonomy(
		'news_category',
		array( 'news' ),
		array(
			'labels'            => array(
				'name'          => __( 'News Categories', 'vitalstack' ),
				'singular_name' => __( 'News Category', 'vitalstack' ),
				'menu_name'     => __( 'Categories', 'vitalstack' ),
			),
			'hierarchical'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'news-category' ),
			'show_in_rest'      => true,
		)
	);

	register_post_type(
		'tutorials',
		array(
			'labels'        => array(
				'name'          => __( 'Tutorials', 'vitalstack' ),
				'singular_name' => __( 'Tutorial', 'vitalstack' ),
				'add_new_item'  => __( 'Add New Tutorial', 'vitalstack' ),
				'edit_item'     => __( 'Edit Tutorial', 'vitalstack' ),
				'all_items'     => __( 'All Tutorials', 'vitalstack' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'rewrite'       => array(
				'slug'       => 'tutorials',
				'with_front' => false,
			),
			'menu_position' => 6,
			'menu_icon'     => 'dashicons-welcome-learn-more',
			// page-attributes gives each tutorial an "Order" field = lesson number in its path.
			'supports'      => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'custom-fields', 'page-attributes' ),
			'show_in_rest'  => true,
			'taxonomies'    => array( 'tutorial_category', 'tutorial_difficulty', 'post_tag' ),
		)
	);

	// Top-level tutorial categories are shown to readers as "Learning Paths".
	register_taxonomy(
		'tutorial_category',
		array( 'tutorials' ),
		array(
			'labels'            => array(
				'name'          => __( 'Learning Paths', 'vitalstack' ),
				'singular_name' => __( 'Learning Path', 'vitalstack' ),
				'menu_name'     => __( 'Learning Paths', 'vitalstack' ),
				'add_new_item'  => __( 'Add New Path / Topic', 'vitalstack' ),
			),
			'description'       => __( 'Top-level terms are learning paths; child terms are topics inside a path.', 'vitalstack' ),
			'hierarchical'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'tutorial-category' ),
			'show_in_rest'      => true,
		)
	);

	register_taxonomy(
		'tutorial_difficulty',
		array( 'tutorials' ),
		array(
			'labels'            => array(
				'name'          => __( 'Difficulty Levels', 'vitalstack' ),
				'singular_name' => __( 'Difficulty', 'vitalstack' ),
				'menu_name'     => __( 'Difficulty', 'vitalstack' ),
			),
			'hierarchical'      => false,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'tutorial-difficulty' ),
			'show_in_rest'      => true,
		)
	);
}
add_action( 'init', 'vitalstack_register_content_types' );

/* ── Tutorial video meta box (optional video per tutorial) ─────────────────── */

function vitalstack_tutorial_meta_box() {
	add_meta_box( 'vitalstack_tutorial_video', __( 'Tutorial Video (optional)', 'vitalstack' ), 'vitalstack_tutorial_video_callback', 'tutorials', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'vitalstack_tutorial_meta_box' );

function vitalstack_tutorial_video_callback( $post ) {
	wp_nonce_field( 'vitalstack_tutorial_video_save', 'vitalstack_tutorial_video_nonce' );
	$type = get_post_meta( $post->ID, 'tutorial_video_type', true ) ?: 'youtube';
	$url  = get_post_meta( $post->ID, 'tutorial_video_url', true );
	$file = get_post_meta( $post->ID, 'tutorial_video_file', true );
	$dur  = get_post_meta( $post->ID, 'tutorial_duration', true );
	?>
	<p><?php esc_html_e( 'Leave the URL empty if this tutorial has no video. Nothing video-related is shown to readers then.', 'vitalstack' ); ?></p>
	<p>
		<label for="tutorial_video_type"><strong><?php esc_html_e( 'Video type', 'vitalstack' ); ?></strong></label><br>
		<select name="tutorial_video_type" id="tutorial_video_type">
			<option value="youtube" <?php selected( $type, 'youtube' ); ?>>YouTube</option>
			<option value="vimeo" <?php selected( $type, 'vimeo' ); ?>>Vimeo</option>
			<option value="self_hosted" <?php selected( $type, 'self_hosted' ); ?>><?php esc_html_e( 'Self-hosted (MP4/WebM)', 'vitalstack' ); ?></option>
			<option value="oembed" <?php selected( $type, 'oembed' ); ?>><?php esc_html_e( 'Other oEmbed URL', 'vitalstack' ); ?></option>
		</select>
	</p>
	<p>
		<label for="tutorial_video_url"><strong><?php esc_html_e( 'Video URL (YouTube / Vimeo / oEmbed)', 'vitalstack' ); ?></strong></label><br>
		<input type="url" class="widefat" name="tutorial_video_url" id="tutorial_video_url" value="<?php echo esc_attr( $url ); ?>">
	</p>
	<p>
		<label for="tutorial_video_file"><strong><?php esc_html_e( 'Self-hosted file URL', 'vitalstack' ); ?></strong></label><br>
		<input type="url" class="widefat" name="tutorial_video_file" id="tutorial_video_file" value="<?php echo esc_attr( $file ); ?>">
	</p>
	<p>
		<label for="tutorial_duration"><strong><?php esc_html_e( 'Video duration (e.g. 12:34)', 'vitalstack' ); ?></strong></label><br>
		<input type="text" name="tutorial_duration" id="tutorial_duration" value="<?php echo esc_attr( $dur ); ?>">
	</p>
	<?php
}

function vitalstack_tutorial_video_save( $post_id ) {
	if ( ! isset( $_POST['vitalstack_tutorial_video_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_key( $_POST['vitalstack_tutorial_video_nonce'] ), 'vitalstack_tutorial_video_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$fields = array(
		'tutorial_video_type' => 'sanitize_key',
		'tutorial_video_url'  => 'esc_url_raw',
		'tutorial_video_file' => 'esc_url_raw',
		'tutorial_duration'   => 'sanitize_text_field',
	);
	foreach ( $fields as $key => $sanitizer ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, call_user_func( $sanitizer, wp_unslash( $_POST[ $key ] ) ) );
		}
	}
}
add_action( 'save_post_tutorials', 'vitalstack_tutorial_video_save' );

/**
 * Video embed markup for a tutorial, or '' when it has no video.
 * YouTube uses a click-to-load facade so the page stays fast.
 */
function vitalstack_tutorial_video( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$type    = get_post_meta( $post_id, 'tutorial_video_type', true ) ?: 'youtube';
	$url     = get_post_meta( $post_id, 'tutorial_video_url', true );
	$file    = get_post_meta( $post_id, 'tutorial_video_file', true );

	if ( 'self_hosted' === $type && $file ) {
		$poster = has_post_thumbnail( $post_id ) ? ' poster="' . esc_url( get_the_post_thumbnail_url( $post_id, 'vitalstack-hero' ) ) . '"' : '';
		return '<div class="video-wrap"><video controls playsinline preload="metadata"' . $poster . '><source src="' . esc_url( $file ) . '"></video></div>';
	}
	if ( ! $url ) {
		return '';
	}
	if ( 'youtube' === $type && preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $url, $m ) ) {
		$thumb = 'https://i.ytimg.com/vi/' . $m[1] . '/hqdefault.jpg';
		return '<div class="video-wrap"><button type="button" class="yt-facade" data-vid="' . esc_attr( $m[1] ) . '" style="background-image:url(' . esc_url( $thumb ) . ')" aria-label="' . esc_attr__( 'Play video', 'vitalstack' ) . '"><span class="yt-play" aria-hidden="true"></span></button></div>';
	}
	$embed = wp_oembed_get( $url, array( 'width' => 1200 ) );
	return $embed ? '<div class="video-wrap">' . $embed . '</div>' : '';
}
