<?php
/**
 * UI components for the v3 layout: topic bar, account menu, subscribe box,
 * doc-style sidebars.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'after_setup_theme',
	function () {
		register_nav_menus( array( 'topics' => __( 'Topic Bar (dark bar under the header)', 'vitalstack' ) ) );
	},
	11
);

/**
 * Short subject name for a tutorial: "HTML Tutorial for Beginners" → "HTML".
 * A custom field "subject_label" overrides it.
 */
function vitalstack_subject_label( $post_id ) {
	$custom = get_post_meta( $post_id, 'subject_label', true );
	if ( $custom ) {
		return $custom;
	}
	$t = vitalstack_short_title( $post_id );
	if ( preg_match( '/^what\s+is\s+(.+?)\?/i', $t, $m ) ) {
		$t = $m[1];
	}
	$t = preg_replace( '/\s+(tutorial|guide|for beginners|explained simply|with projects|basics)\b.*$/i', '', $t );
	if ( mb_strlen( $t ) > 16 && false !== stripos( $t, ' in ' ) ) {
		$t = trim( substr( $t, 0, stripos( $t, ' in ' ) ) );
	}
	return trim( $t );
}

/**
 * Links in the dark topic bar. Uses the "Topic Bar" menu when assigned,
 * otherwise every tutorial (as a subject) followed by the main guide topics.
 */
function vitalstack_topic_links() {
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations['topics'] ) ) {
		$items = wp_get_nav_menu_items( $locations['topics'] );
		foreach ( (array) $items as $item ) {
			if ( (int) $item->menu_item_parent ) {
				continue;
			}
			$active = ( is_singular() || is_tax() || is_category() ) && (int) $item->object_id === get_queried_object_id();
			echo '<a href="' . esc_url( $item->url ) . '"' . ( $active ? ' class="is-active" aria-current="page"' : '' ) . '>' . esc_html( $item->title ) . '</a>';
		}
		return;
	}

	$current = is_singular( 'tutorials' ) ? get_queried_object_id() : 0;
	foreach ( vitalstack_learning_paths() as $path ) {
		foreach ( vitalstack_path_lessons( $path ) as $lesson_id ) {
			echo '<a href="' . esc_url( get_permalink( $lesson_id ) ) . '"' . ( $lesson_id === $current ? ' class="is-active" aria-current="page"' : '' ) . '>' . esc_html( vitalstack_subject_label( $lesson_id ) ) . '</a>';
		}
	}
	$extra = array(
		'artificial-intelligence' => __( 'AI Guides', 'vitalstack' ),
		'ai-tools'                => __( 'AI Tools', 'vitalstack' ),
		'ai-career'               => __( 'Careers', 'vitalstack' ),
	);
	echo '<span class="topic-sep" aria-hidden="true"></span>';
	foreach ( $extra as $slug => $label ) {
		$cat = get_category_by_slug( $slug );
		if ( ! $cat ) {
			continue;
		}
		$active = is_category( $cat->term_id ) || ( is_singular( 'post' ) && has_category( $cat->term_id, get_queried_object_id() ) );
		echo '<a href="' . esc_url( get_category_link( $cat ) ) . '"' . ( $active ? ' class="is-active"' : '' ) . '>' . esc_html( $label ) . '</a>';
	}
	echo '<a href="' . esc_url( vitalstack_page_url( 'blog' ) ) . '">' . esc_html__( 'All guides', 'vitalstack' ) . '</a>';
}

/**
 * Sign in / Sign up buttons, or the signed-in reader's menu.
 */
function vitalstack_account_menu() {
	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();
		?>
		<div class="account-menu">
			<button type="button" class="account-btn" data-dropdown aria-expanded="false" aria-haspopup="true">
				<?php echo vitalstack_monogram( $user->ID, 'sm' ); // phpcs:ignore ?>
				<span class="account-name"><?php echo esc_html( $user->display_name ); ?></span>
			</button>
			<div class="dropdown" hidden>
				<a href="<?php echo esc_url( vitalstack_account_url() ); ?>"><?php echo vitalstack_icon( 'user', 16 ); // phpcs:ignore ?> <?php esc_html_e( 'My account & progress', 'vitalstack' ); ?></a>
				<?php if ( current_user_can( 'edit_posts' ) ) : ?>
					<a href="<?php echo esc_url( admin_url() ); ?>"><?php echo vitalstack_icon( 'layers', 16 ); // phpcs:ignore ?> <?php esc_html_e( 'Dashboard', 'vitalstack' ); ?></a>
				<?php endif; ?>
				<a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php echo vitalstack_icon( 'logout', 16 ); // phpcs:ignore ?> <?php esc_html_e( 'Sign out', 'vitalstack' ); ?></a>
			</div>
		</div>
		<?php
		return;
	}
	if ( ! vitalstack_accounts_enabled() ) {
		return;
	}
	?>
	<a class="btn btn-ghost btn-sm auth-link" href="<?php echo esc_url( vitalstack_account_url() ); ?>"><?php esc_html_e( 'Sign in', 'vitalstack' ); ?></a>
	<a class="btn btn-primary btn-sm auth-link" href="<?php echo esc_url( vitalstack_account_url( array( 'tab' => 'register' ) ) ); ?>"><?php esc_html_e( 'Sign up', 'vitalstack' ); ?></a>
	<?php
}

/**
 * "Get new lessons by email" box.
 * Signed-out readers are sent to sign up first (subscribing needs an account).
 *
 * @param string $variant 'band' (full-width footer band) or 'inline' (end of article).
 */
function vitalstack_subscribe_box( $variant = 'inline' ) {
	if ( ! vitalstack_accounts_enabled() ) {
		return;
	}
	$here = is_singular() ? get_permalink() : home_url( add_query_arg( array() ) );
	?>
	<div class="subscribe subscribe-<?php echo esc_attr( $variant ); ?>">
		<div class="subscribe-text">
			<span class="subscribe-icon" aria-hidden="true"><?php echo vitalstack_icon( 'bell', 22 ); // phpcs:ignore ?></span>
			<div>
				<p class="subscribe-title"><?php esc_html_e( 'Get new lessons in your inbox', 'vitalstack' ); ?></p>
				<p class="subscribe-desc"><?php esc_html_e( 'One email when we publish a new tutorial or guide. No spam, unsubscribe in one click.', 'vitalstack' ); ?></p>
			</div>
		</div>
		<div class="subscribe-action">
			<?php if ( ! is_user_logged_in() ) : ?>
				<a class="btn btn-primary" href="<?php echo esc_url( vitalstack_account_url( array( 'tab' => 'register', 'subscribe' => 1, 'redirect_to' => $here ) ) ); ?>"><?php esc_html_e( 'Subscribe free', 'vitalstack' ); ?></a>
				<a class="subscribe-alt" href="<?php echo esc_url( vitalstack_account_url( array( 'tab' => 'login', 'subscribe' => 1, 'redirect_to' => $here ) ) ); ?>"><?php esc_html_e( 'Have an account? Sign in', 'vitalstack' ); ?></a>
			<?php elseif ( vitalstack_is_subscribed( get_current_user_id() ) ) : ?>
				<p class="subscribe-done"><?php echo vitalstack_icon( 'check', 18 ); // phpcs:ignore ?> <?php esc_html_e( 'You’re subscribed', 'vitalstack' ); ?></p>
				<a class="subscribe-alt" href="<?php echo esc_url( vitalstack_account_url() ); ?>"><?php esc_html_e( 'Manage', 'vitalstack' ); ?></a>
			<?php else : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="vs_subscribe">
					<input type="hidden" name="subscribe" value="1">
					<input type="hidden" name="redirect_to" value="<?php echo esc_url( $here ); ?>">
					<?php wp_nonce_field( 'vs_subscribe', '_vsnonce' ); ?>
					<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Subscribe', 'vitalstack' ); ?></button>
				</form>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Account notice from ?vs_msg=… (shown on /account/ and on any page we redirect back to).
 */
function vitalstack_flash() {
	// phpcs:ignore WordPress.Security.NonceVerification -- display only
	$code = isset( $_GET['vs_msg'] ) ? sanitize_key( $_GET['vs_msg'] ) : '';
	$msgs = vitalstack_account_messages();
	if ( ! $code || ! isset( $msgs[ $code ] ) ) {
		return;
	}
	echo '<div class="notice notice-' . esc_attr( $msgs[ $code ][0] ) . '" role="status">' . esc_html( $msgs[ $code ][1] ) . '</div>';
}

/**
 * Left sidebar for blog posts: other guides in the same topic.
 */
function vitalstack_topic_sidebar() {
	$term = vitalstack_primary_term();
	if ( ! $term ) {
		return;
	}
	$posts = get_posts(
		array(
			'post_type'      => get_post_type(),
			'posts_per_page' => 20,
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'taxonomy' => $term->taxonomy,
					'terms'    => $term->term_id,
				),
			),
		)
	);
	if ( ! $posts ) {
		return;
	}
	$current = get_queried_object_id();
	?>
	<nav class="side-nav" aria-label="<?php echo esc_attr( $term->name ); ?>">
		<p class="side-nav-title"><a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a></p>
		<ul>
			<?php foreach ( $posts as $p ) : ?>
				<li><a href="<?php echo esc_url( get_permalink( $p ) ); ?>"<?php echo $p->ID === $current ? ' class="is-current" aria-current="page"' : ''; ?>><?php echo esc_html( get_the_title( $p ) ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
}

/**
 * Left sidebar for tutorials: current path expanded, other paths collapsed.
 */
function vitalstack_lessons_sidebar() {
	$current_path = vitalstack_tutorial_path();
	$current      = get_queried_object_id();
	foreach ( vitalstack_learning_paths() as $path ) {
		$is_current = $current_path && $path->term_id === $current_path->term_id;
		$lessons    = vitalstack_path_lessons( $path );
		?>
		<details class="side-group" data-path="<?php echo esc_attr( $path->slug ); ?>" data-total="<?php echo count( $lessons ); ?>" <?php echo $is_current ? 'open' : ''; ?>>
			<summary><?php echo esc_html( $path->name ); ?></summary>
			<div class="path-progress" aria-hidden="true"><span class="path-progress-bar"></span></div>
			<ol class="side-lessons">
				<?php foreach ( $lessons as $i => $lesson_id ) : ?>
					<li data-lesson="<?php echo (int) $lesson_id; ?>" class="<?php echo $lesson_id === $current ? 'is-current' : ''; ?>">
						<a href="<?php echo esc_url( get_permalink( $lesson_id ) ); ?>"<?php echo $lesson_id === $current ? ' aria-current="page"' : ''; ?>>
							<span class="lesson-num"><span class="lesson-n"><?php echo (int) $i + 1; ?></span><?php echo vitalstack_icon( 'check', 12 ); // phpcs:ignore ?></span>
							<?php echo esc_html( vitalstack_short_title( $lesson_id ) ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ol>
		</details>
		<?php
	}
}

/**
 * Previous / Next buttons for a lesson (top and bottom of the page).
 */
function vitalstack_lesson_buttons() {
	$path = vitalstack_tutorial_path();
	if ( ! $path ) {
		return;
	}
	$lessons = vitalstack_path_lessons( $path );
	$i       = array_search( get_the_ID(), $lessons, true );
	$prev    = ( false !== $i && $i > 0 ) ? $lessons[ $i - 1 ] : 0;
	$next    = ( false !== $i && $i < count( $lessons ) - 1 ) ? $lessons[ $i + 1 ] : 0;
	?>
	<div class="step-buttons">
		<?php if ( $prev ) : ?>
			<a class="btn btn-primary" href="<?php echo esc_url( get_permalink( $prev ) ); ?>" rel="prev">❮ <?php esc_html_e( 'Previous', 'vitalstack' ); ?></a>
		<?php else : ?>
			<a class="btn btn-primary" href="<?php echo esc_url( get_term_link( $path ) ); ?>">❮ <?php echo esc_html( $path->name ); ?></a>
		<?php endif; ?>
		<?php if ( $next ) : ?>
			<a class="btn btn-primary" href="<?php echo esc_url( get_permalink( $next ) ); ?>" rel="next"><?php esc_html_e( 'Next', 'vitalstack' ); ?> ❯</a>
		<?php else : ?>
			<a class="btn btn-primary" href="<?php echo esc_url( vitalstack_tutorials_url() ); ?>"><?php esc_html_e( 'All tutorials', 'vitalstack' ); ?> ❯</a>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Example code shown on the homepage per learning path (kept short and runnable).
 */
function vitalstack_path_example( $term ) {
	$slug = $term ? $term->slug . ' ' . strtolower( $term->name ) : '';
	if ( preg_match( '/database|sql|mongo/', $slug ) ) {
		return array( 'SQL', "SELECT name, city\nFROM customers\nWHERE country = 'India'\nORDER BY name;", '' );
	}
	if ( preg_match( '/backend|api|java|server/', $slug ) ) {
		return array( 'JavaScript', "// Call a REST API and read JSON\nfetch('https://jsonplaceholder.typicode.com/users/1')\n  .then(res => res.json())\n  .then(user => console.log(user.name));", 'js' );
	}
	if ( preg_match( '/fundamental|basic|programming|oop/', $slug ) ) {
		return array( 'JavaScript', "for (let i = 1; i <= 5; i++) {\n  console.log('Step ' + i + ': keep learning!');\n}", 'js' );
	}
	return array( 'HTML', "<!DOCTYPE html>\n<html>\n<body>\n\n<h1>My First Heading</h1>\n<p>My first paragraph.</p>\n\n</body>\n</html>", 'html' );
}
