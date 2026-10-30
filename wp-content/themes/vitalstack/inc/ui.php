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
 * Practice pages (mock tests, roadmaps, cheat sheets, tools) that exist and
 * are published: list of array( url, label, icon, active ).
 */
function vitalstack_practice_links() {
	static $links = null;
	if ( null !== $links ) {
		return $links;
	}
	$links = array();
	$pages = array(
		'mock-tests'   => array( __( 'Mock Tests', 'vitalstack' ), '📝' ),
		'roadmaps'     => array( __( 'Roadmaps', 'vitalstack' ), '🗺️' ),
		'cheat-sheets' => array( __( 'Cheat Sheets', 'vitalstack' ), '📄' ),
		'tools'        => array( __( 'Free Tools', 'vitalstack' ), '🛠️' ),
	);
	$current = is_page() ? get_queried_object() : null;
	foreach ( $pages as $slug => $meta ) {
		$page = get_page_by_path( $slug );
		if ( ! $page || 'publish' !== $page->post_status ) {
			continue;
		}
		$active  = $current && ( (int) $current->ID === (int) $page->ID || in_array( (int) $page->ID, array_map( 'intval', get_post_ancestors( $current ) ), true ) );
		$links[] = array( get_permalink( $page ), $meta[0], $meta[1], $active );
	}
	return $links;
}

/**
 * Practice links in the header (wide screens; the topic bar has them on smaller ones).
 */
function vitalstack_header_nav() {
	$links = vitalstack_practice_links();
	if ( ! $links ) {
		return;
	}
	echo '<nav class="topbar-nav" aria-label="' . esc_attr__( 'Practice', 'vitalstack' ) . '">';
	foreach ( $links as $l ) {
		echo '<a href="' . esc_url( $l[0] ) . '"' . ( $l[3] ? ' class="is-active" aria-current="page"' : '' ) . '>' . esc_html( $l[1] ) . '</a>';
	}
	echo '</nav>';
}

/**
 * Practice links at the top of the phone menu, as tiles.
 */
function vitalstack_drawer_practice() {
	$links = vitalstack_practice_links();
	if ( ! $links ) {
		return;
	}
	echo '<nav class="drawer-practice" aria-label="' . esc_attr__( 'Practice', 'vitalstack' ) . '">';
	foreach ( $links as $l ) {
		echo '<a href="' . esc_url( $l[0] ) . '"' . ( $l[3] ? ' class="is-active" aria-current="page"' : '' ) . '><span aria-hidden="true">' . esc_html( $l[2] ) . '</span>' . esc_html( $l[1] ) . '</a>';
	}
	echo '</nav>';
}

/**
 * The phone menu shows the practice pages as tiles, so menu items pointing to
 * them are dropped from the list below to avoid showing them twice.
 */
function vitalstack_drawer_skip_practice_items( $items, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $items;
	}
	$urls = array_map(
		function ( $l ) {
			return untrailingslashit( $l[0] );
		},
		vitalstack_practice_links()
	);
	return array_filter(
		$items,
		function ( $item ) use ( $urls ) {
			return ! in_array( untrailingslashit( $item->url ), $urls, true );
		}
	);
}
add_filter( 'wp_nav_menu_objects', 'vitalstack_drawer_skip_practice_items', 20, 2 );

/**
 * Links in the dark topic bar. Uses the "Topic Bar" menu when assigned,
 * otherwise one link per learning path (subject), pointing at its first
 * lesson, followed by the main guide topics.
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

	$current_path = is_singular( 'tutorials' ) ? vitalstack_tutorial_path() : null;
	if ( is_tax( 'tutorial_category' ) ) {
		$current_path = get_queried_object();
		while ( $current_path && $current_path->parent ) {
			$current_path = get_term( $current_path->parent, 'tutorial_category' );
		}
	}
	$firsts = array();
	foreach ( vitalstack_learning_paths() as $path ) {
		$firsts[] = vitalstack_path_lessons( $path )[0];
	}
	_prime_post_caches( $firsts, false, false );
	foreach ( vitalstack_learning_paths() as $path ) {
		$lessons = vitalstack_path_lessons( $path );
		$active  = $current_path && $current_path->term_id === $path->term_id;
		$url     = get_permalink( $lessons[0] );
		// The problem bank opens on its filterable list rather than problem #1.
		if ( 'dsa-problems' === $path->slug && vitalstack_problems_url() ) {
			$url    = vitalstack_problems_url();
			$active = $active || is_page( 'dsa-problems' );
		}
		echo '<a href="' . esc_url( $url ) . '"' . ( $active ? ' class="is-active" aria-current="true"' : '' ) . '>' . esc_html( $path->name ) . '</a>';
	}
	if ( vitalstack_games_url() ) {
		echo '<a class="topic-games' . ( is_page( 'games' ) ? ' is-active' : '' ) . '" href="' . esc_url( vitalstack_games_url() ) . '">🎮 ' . esc_html__( 'Games', 'vitalstack' ) . '</a>';
	}
	foreach ( vitalstack_practice_links() as $l ) {
		echo '<a class="topic-practice' . ( $l[3] ? ' is-active' : '' ) . '" href="' . esc_url( $l[0] ) . '">' . esc_html( $l[2] . ' ' . $l[1] ) . '</a>';
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
 * "All courses" panel under the topic bar: every learning path, grouped,
 * so no course is hidden off the edge of the bar on any screen size.
 */
function vitalstack_courses_panel() {
	$paths = vitalstack_learning_paths();
	if ( ! $paths ) {
		return;
	}
	$groups = array(
		'web'  => array( __( 'Web development', 'vitalstack' ), '🌐', array( 'html', 'css', 'javascript', 'typescript', 'react', 'angular', 'node', 'nodejs', 'bootstrap', 'tailwind' ) ),
		'code' => array( __( 'Programming & DSA', 'vitalstack' ), '💻', array( 'python', 'java', 'java-oop', 'c', 'cpp', 'sql', 'dsa', 'dsa-problems', 'git' ) ),
		'java' => array( __( 'Java backend', 'vitalstack' ), '☕', array( 'servlet', 'jsp', 'jdbc', 'jpa', 'hibernate', 'spring', 'spring-boot', 'microservices', 'jmix' ) ),
		'data' => array( __( 'Data & AI', 'vitalstack' ), '🤖', array( 'numpy', 'pandas', 'data-visualization', 'statistics', 'machine-learning', 'deep-learning', 'prompt-engineering', 'generative-ai' ) ),
		'more' => array( __( 'More courses', 'vitalstack' ), '📚', array() ),
	);
	$sorted = array_fill_keys( array_keys( $groups ), array() );
	foreach ( $paths as $path ) {
		$key = 'more';
		foreach ( $groups as $g => $meta ) {
			if ( in_array( $path->slug, $meta[2], true ) ) {
				$key = $g;
				break;
			}
		}
		$sorted[ $key ][] = $path;
	}
	$current = is_singular( 'tutorials' ) ? vitalstack_tutorial_path() : null;
	?>
	<div class="courses-panel" id="courses-panel" hidden>
		<div class="wrap courses-panel-inner">
			<div class="courses-panel-head">
				<p class="courses-panel-title"><?php esc_html_e( 'All courses', 'vitalstack' ); ?> <span><?php echo esc_html( sprintf( /* translators: %d: courses */ _n( '%d free course', '%d free courses', count( $paths ), 'vitalstack' ), count( $paths ) ) ); ?></span></p>
				<button type="button" class="icon-btn courses-close" data-courses-close aria-label="<?php esc_attr_e( 'Close all courses', 'vitalstack' ); ?>"><?php echo vitalstack_icon( 'close', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></button>
			</div>
			<div class="courses-grid">
				<?php
				foreach ( $sorted as $g => $list ) :
					if ( ! $list ) {
						continue;
					}
					?>
					<section class="courses-group">
						<h2><span aria-hidden="true"><?php echo esc_html( $groups[ $g ][1] ); ?></span> <?php echo esc_html( $groups[ $g ][0] ); ?></h2>
						<ul>
							<?php
							foreach ( $list as $path ) :
								$lessons = vitalstack_path_lessons( $path );
								$url     = ( 'dsa-problems' === $path->slug && vitalstack_problems_url() ) ? vitalstack_problems_url() : get_term_link( $path );
								$active  = $current && $current->term_id === $path->term_id;
								?>
								<li><a href="<?php echo esc_url( is_wp_error( $url ) ? get_permalink( $lessons[0] ) : $url ); ?>"<?php echo $active ? ' class="is-active" aria-current="true"' : ''; ?>><span><?php echo esc_html( $path->name ); ?></span> <small><?php echo esc_html( sprintf( /* translators: %d: lessons */ _n( '%d lesson', '%d lessons', count( $lessons ), 'vitalstack' ), count( $lessons ) ) ); ?></small></a></li>
							<?php endforeach; ?>
						</ul>
					</section>
				<?php endforeach; ?>
				<?php if ( function_exists( 'vitalstack_premium_tracks' ) ) : ?>
					<section class="courses-group courses-premium">
						<h2><span aria-hidden="true">⭐</span> <?php esc_html_e( 'Premium career tracks', 'vitalstack' ); ?></h2>
						<ul>
							<?php foreach ( vitalstack_premium_tracks() as $slug => $t ) : ?>
								<li><a href="<?php echo esc_url( vitalstack_premium_url( $slug ) ); ?>"><span><?php echo esc_html( $t['icon'] . ' ' . $t['title'] ); ?></span> <small><?php echo esc_html( sprintf( /* translators: %d: modules */ __( '%d modules', 'vitalstack' ), count( $t['modules'] ) ) ); ?></small></a></li>
							<?php endforeach; ?>
						</ul>
					</section>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
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
				<p class="subscribe-desc"><?php echo function_exists( 'vitalstack_notify_mode' ) && 'weekly' === vitalstack_notify_mode() ? esc_html__( 'One email every Sunday with the week’s new tutorials and guides. No spam, unsubscribe in one click.', 'vitalstack' ) : esc_html__( 'One email when we publish a new tutorial or guide. No spam, unsubscribe in one click.', 'vitalstack' ); ?></p>
			</div>
		</div>
		<div class="subscribe-action">
			<?php if ( ! is_user_logged_in() ) : ?>
				<a class="btn btn-primary" href="<?php echo esc_url( vitalstack_account_url( array( 'tab' => 'register', 'subscribe' => 1, 'redirect_to' => $here ) ) ); ?>"><?php esc_html_e( 'Subscribe free', 'vitalstack' ); ?></a>
				<a class="subscribe-alt" href="<?php echo esc_url( vitalstack_account_url( array( 'tab' => 'login', 'subscribe' => 1, 'redirect_to' => $here ) ) ); ?>"><?php esc_html_e( 'Have an account? Sign in', 'vitalstack' ); ?></a>
			<?php elseif ( vitalstack_is_subscribed( get_current_user_id() ) && ! vitalstack_is_verified( get_current_user_id() ) ) : ?>
				<p class="subscribe-pending"><?php esc_html_e( 'Confirm your email to start getting lessons', 'vitalstack' ); ?></p>
				<a class="btn btn-primary btn-sm" href="<?php echo esc_url( vitalstack_account_url() ); ?>"><?php esc_html_e( 'Confirm email', 'vitalstack' ); ?></a>
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
	$paths        = vitalstack_learning_paths();
	// Load every lesson in one query instead of one query per link.
	$all = array();
	foreach ( $paths as $path ) {
		$all = array_merge( $all, vitalstack_path_lessons( $path ) );
	}
	_prime_post_caches( $all, false, false );
	foreach ( $paths as $path ) {
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

	// Last lesson of a subject: carry on into the next subject (HTML → CSS → JavaScript…).
	$next_path = null;
	if ( ! $next ) {
		$paths = vitalstack_learning_paths();
		foreach ( $paths as $k => $p ) {
			if ( $p->term_id === $path->term_id && isset( $paths[ $k + 1 ] ) ) {
				$next_path = $paths[ $k + 1 ];
				break;
			}
		}
	}
	?>
	<div class="step-buttons">
		<?php if ( $prev ) : ?>
			<a class="btn btn-primary" href="<?php echo esc_url( get_permalink( $prev ) ); ?>" rel="prev">❮ <?php esc_html_e( 'Previous', 'vitalstack' ); ?></a>
		<?php else : ?>
			<a class="btn btn-primary" href="<?php echo esc_url( get_term_link( $path ) ); ?>">❮ <?php echo esc_html( $path->name ); ?></a>
		<?php endif; ?>
		<?php if ( $next ) : ?>
			<a class="btn btn-primary" href="<?php echo esc_url( get_permalink( $next ) ); ?>" rel="next"><?php esc_html_e( 'Next', 'vitalstack' ); ?> ❯</a>
		<?php elseif ( $next_path ) : ?>
			<a class="btn btn-primary" href="<?php echo esc_url( get_permalink( vitalstack_path_lessons( $next_path )[0] ) ); ?>">
				<?php
				/* translators: %s: next subject name */
				echo esc_html( sprintf( __( 'Next: %s', 'vitalstack' ), $next_path->name ) );
				?>
				❯</a>
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
	$key = $term ? ' ' . $term->slug . ' ' . strtolower( $term->name ) . ' ' : '';
	// Checked in order; "javascript" must be tested before "java".
	$examples = array(
		'/javascript|\bjs\b/'                 => array( 'JavaScript', "const name = 'VitalStack';\n\nfor (let i = 1; i <= 3; i++) {\n  console.log('Lesson ' + i + ' on ' + name);\n}", 'js' ),
		'/\bcss\b/'                           => array( 'CSS', "<!DOCTYPE html>\n<html>\n<head>\n<style>\n  body { font-family: sans-serif; }\n  h1 { color: #0a7d4f; text-align: center; }\n  p { background: #e3f3eb; padding: 12px; }\n</style>\n</head>\n<body>\n\n<h1>Styled with CSS</h1>\n<p>Change the colours and press Run.</p>\n\n</body>\n</html>", 'html' ),
		'/python/'                            => array( 'Python', "name = \"VitalStack\"\n\nfor i in range(1, 4):\n    print(f\"Lesson {i} on {name}\")", '' ),
		'/database|sql|mongo/'                => array( 'SQL', "SELECT name, city\nFROM customers\nWHERE country = 'India'\nORDER BY name;", '' ),
		'/react/'                             => array( 'React', "function Welcome({ name }) {\n  return <h1>Hello, {name}!</h1>;\n}", '' ),
		'/\bjava\b|backend|api|server/'       => array( 'JavaScript', "// Call a REST API and read JSON\nfetch('https://jsonplaceholder.typicode.com/users/1')\n  .then(res => res.json())\n  .then(user => console.log(user.name));", 'js' ),
		'/fundamental|basic|programming|oop/' => array( 'JavaScript', "for (let i = 1; i <= 5; i++) {\n  console.log('Step ' + i + ': keep learning!');\n}", 'js' ),
	);
	foreach ( $examples as $pattern => $example ) {
		if ( preg_match( $pattern, $key ) ) {
			return $example;
		}
	}
	return array( 'HTML', "<!DOCTYPE html>\n<html>\n<body>\n\n<h1>My First Heading</h1>\n<p>My first paragraph.</p>\n\n</body>\n</html>", 'html' );
}
