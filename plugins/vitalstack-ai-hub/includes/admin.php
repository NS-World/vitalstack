<?php
/**
 * wp-admin → AI Hub: add your own library items, prompts, command cards and
 * motion sections, free or premium. They appear on top of the built-in ones.
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

function vsai_meta_fields() {
	return array(
		'type'    => __( 'Section', 'vitalstack-ai-hub' ),
		'cat'     => __( 'Category (e.g. Coding, Marketing; for Motion: Hero, Background…)', 'vitalstack-ai-hub' ),
		'summary' => __( 'One-line summary (shown to everyone)', 'vitalstack-ai-hub' ),
		'tool'    => __( 'AI tool (prompts / commands), e.g. ChatGPT, Midjourney', 'vitalstack-ai-hub' ),
		'kind'    => __( 'Prompt kind: text, image, video, audio or code', 'vitalstack-ai-hub' ),
		'by'      => __( 'Library: author or publisher', 'vitalstack-ai-hub' ),
		'level'   => __( 'Library: level (Beginner, Intermediate, Advanced)', 'vitalstack-ai-hub' ),
		'url'     => __( 'Library: link to a free external resource (leave the editor empty)', 'vitalstack-ai-hub' ),
		'output'  => __( 'Prompts: example result · Commands: tips (one per line) · Motion: AI prompt to build it', 'vitalstack-ai-hub' ),
	);
}

add_action(
	'add_meta_boxes_vsai_item',
	function () {
		add_meta_box( 'vsai_meta', __( 'AI Hub details', 'vitalstack-ai-hub' ), 'vsai_meta_box', 'vsai_item', 'normal', 'high' );
	}
);

function vsai_meta_box( $post ) {
	wp_nonce_field( 'vsai_save', 'vsai_nonce' );
	$v = function ( $k ) use ( $post ) {
		return (string) get_post_meta( $post->ID, '_vsai_' . $k, true );
	};
	echo '<p>' . esc_html__( 'The editor above holds: Library = the guide text · Prompts = the prompt · Commands = one row per line as "shortcut | what it does" · Motion = leave empty and paste the code below.', 'vitalstack-ai-hub' ) . '</p><table class="form-table">';
	foreach ( vsai_meta_fields() as $k => $label ) {
		echo '<tr><th><label for="vsai_' . esc_attr( $k ) . '">' . esc_html( $label ) . '</label></th><td>';
		if ( 'type' === $k ) {
			echo '<select id="vsai_type" name="vsai_type">';
			foreach ( vsai_sections() as $slug => $s ) {
				echo '<option value="' . esc_attr( $slug ) . '"' . selected( $v( 'type' ), $slug, false ) . '>' . esc_html( $s[0] ) . '</option>';
			}
			echo '</select>';
		} elseif ( 'output' === $k ) {
			echo '<textarea id="vsai_output" name="vsai_output" rows="5" class="large-text">' . esc_textarea( $v( 'output' ) ) . '</textarea>';
		} else {
			echo '<input id="vsai_' . esc_attr( $k ) . '" name="vsai_' . esc_attr( $k ) . '" class="regular-text" value="' . esc_attr( $v( $k ) ) . '">';
		}
		echo '</td></tr>';
	}
	echo '<tr><th>' . esc_html__( 'Premium', 'vitalstack-ai-hub' ) . '</th><td><label><input type="checkbox" name="vsai_premium" value="1"' . checked( $v( 'premium' ), '1', false ) . '> ' . esc_html__( 'Pro item: only AI Pro members see the full item', 'vitalstack-ai-hub' ) . '</label></td></tr>';
	if ( current_user_can( 'unfiltered_html' ) ) {
		echo '<tr><th><label for="vsai_code">' . esc_html__( 'Motion: full HTML document (HTML + CSS + JS)', 'vitalstack-ai-hub' ) . '</label></th><td><textarea id="vsai_code" name="vsai_code" rows="14" class="large-text code">' . esc_textarea( (string) get_post_meta( $post->ID, '_vsai_code', true ) ) . '</textarea><p class="description">' . esc_html__( 'Runs in a sandbox with no network access. Use the featured image as the poster shown to non-members (premium) or as the example result (image prompts).', 'vitalstack-ai-hub' ) . '</p></td></tr>';
	}
	echo '</table>';
}

add_action(
	'save_post_vsai_item',
	function ( $post_id ) {
		if ( ! isset( $_POST['vsai_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['vsai_nonce'] ), 'vsai_save' ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		foreach ( array_keys( vsai_meta_fields() ) as $k ) {
			if ( ! isset( $_POST[ 'vsai_' . $k ] ) ) {
				continue;
			}
			$raw = wp_unslash( $_POST[ 'vsai_' . $k ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitized per field below
			if ( 'type' === $k ) {
				$val = in_array( $raw, array_keys( vsai_sections() ), true ) ? $raw : 'prompts';
			} elseif ( 'url' === $k ) {
				$val = esc_url_raw( $raw );
			} elseif ( 'output' === $k ) {
				$val = sanitize_textarea_field( $raw );
			} else {
				$val = sanitize_text_field( $raw );
			}
			update_post_meta( $post_id, '_vsai_' . $k, $val );
		}
		update_post_meta( $post_id, '_vsai_premium', empty( $_POST['vsai_premium'] ) ? '0' : '1' );
		if ( isset( $_POST['vsai_code'] ) && current_user_can( 'unfiltered_html' ) ) {
			update_post_meta( $post_id, '_vsai_code', wp_unslash( $_POST['vsai_code'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- admins only; always shown inside a sandbox
		}
	}
);

/** List screen columns. */
add_filter(
	'manage_vsai_item_posts_columns',
	function ( $cols ) {
		$cols['vsai_type']    = __( 'Section', 'vitalstack-ai-hub' );
		$cols['vsai_premium'] = __( 'Premium', 'vitalstack-ai-hub' );
		return $cols;
	}
);
add_action(
	'manage_vsai_item_posts_custom_column',
	function ( $col, $post_id ) {
		if ( 'vsai_type' === $col ) {
			$t = (string) get_post_meta( $post_id, '_vsai_type', true );
			echo esc_html( vsai_sections()[ $t ][0] ?? $t );
		} elseif ( 'vsai_premium' === $col ) {
			echo '1' === get_post_meta( $post_id, '_vsai_premium', true ) ? '👑' : '—';
		}
	},
	10,
	2
);

/** A short help page under AI Hub. */
add_action(
	'admin_menu',
	function () {
		add_submenu_page(
			'edit.php?post_type=vsai_item',
			__( 'AI Hub overview', 'vitalstack-ai-hub' ),
			__( 'Overview', 'vitalstack-ai-hub' ),
			'edit_posts',
			'vsai-overview',
			function () {
				echo '<div class="wrap"><h1>' . esc_html__( 'VitalStack AI Hub', 'vitalstack-ai-hub' ) . '</h1><p><a class="button button-primary" href="' . esc_url( vsai_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'Open the AI Hub', 'vitalstack-ai-hub' ) . '</a></p><table class="widefat striped" style="max-width:640px"><thead><tr><th>' . esc_html__( 'Section', 'vitalstack-ai-hub' ) . '</th><th>' . esc_html__( 'Built-in', 'vitalstack-ai-hub' ) . '</th><th>' . esc_html__( 'Added by you', 'vitalstack-ai-hub' ) . '</th><th>' . esc_html__( 'Premium', 'vitalstack-ai-hub' ) . '</th></tr></thead><tbody>';
				foreach ( vsai_sections() as $slug => $s ) {
					$all = vsai_items( $slug );
					$pro = count( array_filter( $all, function ( $i ) { return $i['premium']; } ) );
					echo '<tr><td><a href="' . esc_url( vsai_url( $slug ) ) . '" target="_blank" rel="noopener">' . esc_html( $s[0] ) . '</a></td><td>' . (int) count( vsai_builtin( $slug ) ) . '</td><td>' . (int) count( vsai_custom( $slug ) ) . '</td><td>' . (int) $pro . '</td></tr>';
				}
				echo '</tbody></table><p>' . esc_html__( 'Pro items unlock with an AI Pro plan (monthly, yearly or lifetime; change prices in Settings → VitalStack Payments). Visitors get 2 free Motion copies, free accounts 10 a day. The “AI Hub” button appears first in the main site’s practice links.', 'vitalstack-ai-hub' ) . '</p></div>';
			}
		);
	}
);
