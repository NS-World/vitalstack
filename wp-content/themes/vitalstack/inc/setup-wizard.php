<?php
/**
 * One-click starter structure for a fresh WordPress install:
 * categories, learning paths, difficulty levels, pages, menus and
 * permalinks. Safe to run more than once: it only creates what is missing
 * and never overwrites anything you have edited.
 *
 * Trust and legal pages are created as drafts so you read and adjust them
 * before they go live.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ── Admin page ────────────────────────────────────────────────────────────── */

function vitalstack_setup_menu() {
	add_theme_page( __( 'VitalStack Setup', 'vitalstack' ), __( 'VitalStack Setup', 'vitalstack' ), 'manage_options', 'vitalstack-setup', 'vitalstack_setup_page' );
}
add_action( 'admin_menu', 'vitalstack_setup_menu' );

function vitalstack_setup_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$report = get_transient( 'vitalstack_setup_report_' . get_current_user_id() );
	delete_transient( 'vitalstack_setup_report_' . get_current_user_id() );
	$drafts = vitalstack_setup_draft_pages();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'VitalStack Setup', 'vitalstack' ); ?></h1>

		<?php if ( $report ) : ?>
			<div class="notice notice-success"><p><strong><?php esc_html_e( 'Done. Here is what was set up:', 'vitalstack' ); ?></strong></p>
				<ul style="list-style:disc;padding-left:20px">
					<?php foreach ( $report as $line ) : ?>
						<li><?php echo esc_html( $line ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<p style="font-size:15px;max-width:760px"><?php esc_html_e( 'Creates the starter structure for a new VitalStack site. It only adds what is missing, so it is safe to run again. Nothing you have already edited is changed.', 'vitalstack' ); ?></p>

		<table class="widefat striped" style="max-width:760px;margin:16px 0">
			<tbody>
				<tr><td><strong><?php esc_html_e( 'Learning paths', 'vitalstack' ); ?></strong></td><td><?php echo esc_html( implode( ', ', wp_list_pluck( vitalstack_setup_paths(), 'name' ) ) ); ?></td></tr>
				<tr><td><strong><?php esc_html_e( 'Blog categories', 'vitalstack' ); ?></strong></td><td><?php echo esc_html( implode( ', ', wp_list_pluck( vitalstack_setup_categories(), 'name' ) ) ); ?></td></tr>
				<tr><td><strong><?php esc_html_e( 'Difficulty levels', 'vitalstack' ); ?></strong></td><td>Beginner, Intermediate, Advanced</td></tr>
				<tr><td><strong><?php esc_html_e( 'Pages (published)', 'vitalstack' ); ?></strong></td><td>Tutorials, Blog, Brain Games, Interview Problems, Daily Challenge, Free Tools, Cheat Sheets, Mock Tests, Career Roadmaps, Contact Us</td></tr>
				<tr><td><strong><?php esc_html_e( 'Pages (drafts to review)', 'vitalstack' ); ?></strong></td><td>About Us, Editorial Policy, Privacy Policy, Terms of Service, Disclaimer, Cookie Policy</td></tr>
				<tr><td><strong><?php esc_html_e( 'Menus', 'vitalstack' ); ?></strong></td><td><?php esc_html_e( 'Main menu, footer Company and Legal menus, assigned to their locations', 'vitalstack' ); ?></td></tr>
				<tr><td><strong><?php esc_html_e( 'Settings', 'vitalstack' ); ?></strong></td><td><?php esc_html_e( 'Post-name permalinks, site tagline, privacy page', 'vitalstack' ); ?></td></tr>
			</tbody>
		</table>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="vitalstack_run_setup">
			<?php wp_nonce_field( 'vitalstack_run_setup' ); ?>
			<p><label><input type="checkbox" name="remove_samples" value="1" checked> <?php esc_html_e( 'Delete WordPress sample content ("Hello world!" post and "Sample Page") if you haven\'t edited it', 'vitalstack' ); ?></label></p>
			<?php submit_button( __( 'Create starter structure', 'vitalstack' ), 'primary large', 'submit', false ); ?>
		</form>

		<?php vitalstack_starter_section(); ?>

		<?php if ( $drafts ) : ?>
			<h2 style="margin-top:32px"><?php esc_html_e( 'Next: review and publish these pages', 'vitalstack' ); ?></h2>
			<p><?php esc_html_e( 'They contain starter text written for VitalStack. Read each one, change anything that is not true for you, then click Publish. AdSense reviewers look at these pages.', 'vitalstack' ); ?></p>
			<ul style="list-style:disc;padding-left:20px">
				<?php foreach ( $drafts as $page ) : ?>
					<li><a href="<?php echo esc_url( get_edit_post_link( $page->ID ) ); ?>"><?php echo esc_html( $page->post_title ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Reminder on the dashboard while starter pages are still drafts.
 */
function vitalstack_setup_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'appearance_page_vitalstack-setup' === $screen->id ) {
		return;
	}
	$done = get_option( 'vitalstack_setup_done' );
	if ( ! $done ) {
		echo '<div class="notice notice-info"><p><strong>VitalStack:</strong> ' . esc_html__( 'Set up categories, learning paths, pages and menus in one click.', 'vitalstack' ) . ' <a class="button button-primary" style="margin-left:8px" href="' . esc_url( admin_url( 'themes.php?page=vitalstack-setup' ) ) . '">' . esc_html__( 'Open VitalStack Setup', 'vitalstack' ) . '</a></p></div>';
		return;
	}
	$drafts = vitalstack_setup_draft_pages();
	if ( $drafts && 'dashboard' === $screen->id ) {
		echo '<div class="notice notice-warning"><p><strong>VitalStack:</strong> ' . esc_html( sprintf( /* translators: %d: number of pages */ _n( '%d starter page is still a draft. Review and publish it before applying for AdSense.', '%d starter pages are still drafts. Review and publish them before applying for AdSense.', count( $drafts ), 'vitalstack' ), count( $drafts ) ) ) . ' <a href="' . esc_url( admin_url( 'themes.php?page=vitalstack-setup' ) ) . '">' . esc_html__( 'See the list', 'vitalstack' ) . '</a></p></div>';
	}
}
add_action( 'admin_notices', 'vitalstack_setup_notice' );

function vitalstack_setup_draft_pages() {
	$out = array();
	foreach ( vitalstack_setup_pages() as $slug => $def ) {
		if ( 'draft' !== $def['status'] ) {
			continue;
		}
		$page = get_page_by_path( $slug );
		if ( $page && 'draft' === $page->post_status ) {
			$out[] = $page;
		}
	}
	return $out;
}

/* ── Runner ───────────────────────────────────────────────────────────────── */

function vitalstack_handle_setup() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'vitalstack' ) );
	}
	check_admin_referer( 'vitalstack_run_setup' );
	$report = vitalstack_run_setup( ! empty( $_POST['remove_samples'] ) );
	set_transient( 'vitalstack_setup_report_' . get_current_user_id(), $report, 10 * MINUTE_IN_SECONDS );
	wp_safe_redirect( admin_url( 'themes.php?page=vitalstack-setup' ) );
	exit;
}
add_action( 'admin_post_vitalstack_run_setup', 'vitalstack_handle_setup' );

/**
 * Creates whatever is missing. Returns a human-readable report.
 *
 * @param bool $remove_samples Delete untouched WordPress sample content.
 * @return string[]
 */
function vitalstack_run_setup( $remove_samples = true ) {
	$report = array();

	// Learning paths (top-level terms), with their display order.
	foreach ( vitalstack_setup_paths() as $i => $path ) {
		$term = get_term_by( 'slug', $path['slug'], 'tutorial_category' );
		if ( ! $term ) {
			$res = wp_insert_term(
				$path['name'],
				'tutorial_category',
				array(
					'slug'        => $path['slug'],
					'description' => $path['description'],
				)
			);
			if ( ! is_wp_error( $res ) ) {
				update_term_meta( $res['term_id'], 'vs_order', $i + 1 );
				/* translators: %s: name */
				$report[] = sprintf( __( 'Learning path created: %s', 'vitalstack' ), $path['name'] );
			}
		} elseif ( (int) get_term_meta( $term->term_id, 'vs_order', true ) !== $i + 1 ) {
			// Keep the theme's order when new paths are added in an update.
			update_term_meta( $term->term_id, 'vs_order', $i + 1 );
		}
	}

	foreach ( array( 'Beginner', 'Intermediate', 'Advanced' ) as $level ) {
		if ( ! term_exists( $level, 'tutorial_difficulty' ) ) {
			wp_insert_term( $level, 'tutorial_difficulty' );
			/* translators: %s: level */
			$report[] = sprintf( __( 'Difficulty level created: %s', 'vitalstack' ), $level );
		}
	}

	// Blog categories.
	foreach ( vitalstack_setup_categories() as $cat ) {
		if ( ! get_category_by_slug( $cat['slug'] ) ) {
			$res = wp_insert_term(
				$cat['name'],
				'category',
				array(
					'slug'        => $cat['slug'],
					'description' => $cat['description'],
				)
			);
			if ( ! is_wp_error( $res ) ) {
				/* translators: %s: name */
				$report[] = sprintf( __( 'Category created: %s', 'vitalstack' ), $cat['name'] );
			}
		}
	}
	// New posts should land in a real topic, not "Uncategorized".
	$default_cat = get_category_by_slug( 'artificial-intelligence' );
	if ( $default_cat && 1 === (int) get_option( 'default_category' ) ) {
		update_option( 'default_category', $default_cat->term_id );
	}

	// Pages.
	$author = get_current_user_id();
	$ids    = array();
	foreach ( vitalstack_setup_pages() as $slug => $def ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( ! $page ) {
			// WordPress ships a draft "Privacy Policy" page; reuse it rather than duplicating.
			if ( 'privacy-policy' === $slug ) {
				$existing = (int) get_option( 'wp_page_for_privacy_policy' );
				$page     = $existing ? get_post( $existing ) : null;
				if ( $page && 'page' === $page->post_type && 'trash' !== $page->post_status ) {
					wp_update_post(
						array(
							'ID'           => $page->ID,
							'post_name'    => 'privacy-policy',
							'post_content' => $def['content'],
							'post_status'  => 'draft',
						)
					);
					update_post_meta( $page->ID, '_wp_page_template', $def['template'] );
					$ids[ $slug ] = $page->ID;
					$report[]     = __( 'Privacy Policy page filled with starter text (draft)', 'vitalstack' );
					continue;
				}
			}
			$id = wp_insert_post(
				array(
					'post_type'    => 'page',
					'post_title'   => $def['title'],
					'post_name'    => $slug,
					'post_content' => $def['content'],
					'post_status'  => $def['status'],
					'post_author'  => $author,
					'page_template' => $def['template'],
				)
			);
			if ( $id && ! is_wp_error( $id ) ) {
				$ids[ $slug ] = $id;
				/* translators: 1: title, 2: status */
				$report[] = sprintf( __( 'Page created: %1$s (%2$s)', 'vitalstack' ), $def['title'], 'draft' === $def['status'] ? __( 'draft, review it', 'vitalstack' ) : __( 'published', 'vitalstack' ) );
			}
		} else {
			$ids[ $slug ] = $page->ID;
			// A fresh install's own draft Privacy Policy still holds WordPress's
			// placeholder ("Suggested text: …"); replace that, never real text.
			if ( 'privacy-policy' === $slug && 'draft' === $page->post_status && false !== strpos( $page->post_content, 'Suggested text:' ) ) {
				wp_update_post(
					array(
						'ID'           => $page->ID,
						'post_content' => $def['content'],
					)
				);
				update_post_meta( $page->ID, '_wp_page_template', $def['template'] );
				$report[] = __( 'Privacy Policy page filled with starter text (draft)', 'vitalstack' );
			}
		}
	}
	if ( ! empty( $ids['privacy-policy'] ) ) {
		update_option( 'wp_page_for_privacy_policy', $ids['privacy-policy'] );
	}

	// Theme-managed child pages: cheat sheets, tools, mock tests and roadmaps (body comes from the theme while empty).
	if ( function_exists( 'vitalstack_site_pages' ) ) {
		foreach ( vitalstack_site_pages() as $slug => $def ) {
			$parent = isset( $ids[ $def['parent'] ] ) ? (int) $ids[ $def['parent'] ] : 0;
			if ( ! $parent || get_page_by_path( $def['parent'] . '/' . $slug ) ) {
				continue;
			}
			$id = wp_insert_post(
				array(
					'post_type'     => 'page',
					'post_title'    => $def['title'],
					'post_name'     => $slug,
					'post_excerpt'  => $def['excerpt'],
					'post_content'  => '',
					'post_status'   => 'publish',
					'post_parent'   => $parent,
					'post_author'   => $author,
					'menu_order'    => (int) $def['order'],
					'page_template' => $def['template'],
				)
			);
			if ( $id && ! is_wp_error( $id ) ) {
				/* translators: %s: page title */
				$report[] = sprintf( __( 'Page created: %s', 'vitalstack' ), $def['title'] );
			}
		}
	}

	// Newer menu links, added once to the main menu the wizard created earlier.
	$main = wp_get_nav_menu_object( 'VitalStack Main' );
	if ( $main ) {
		$have = array();
		foreach ( (array) wp_get_nav_menu_items( $main->term_id ) as $item ) {
			$have[] = (int) $item->object_id;
		}
		$pos = count( $have );
		foreach ( array( array( 'page', 'daily-challenge', __( 'Daily Challenge', 'vitalstack' ) ), array( 'page', 'tools', __( 'Free Tools', 'vitalstack' ) ), array( 'page', 'cheat-sheets', __( 'Cheat Sheets', 'vitalstack' ) ), array( 'page', 'mock-tests', __( 'Mock Tests', 'vitalstack' ) ), array( 'page', 'roadmaps', __( 'Roadmaps', 'vitalstack' ) ) ) as $item ) {
			if ( ! empty( $ids[ $item[1] ] ) && ! in_array( (int) $ids[ $item[1] ], $have, true ) ) {
				vitalstack_setup_add_menu_item( $main->term_id, $item, $ids, ++$pos );
				/* translators: %s: menu label */
				$report[] = sprintf( __( 'Menu link added: %s', 'vitalstack' ), $item[2] );
			}
		}
	}

	// Menus (only created and assigned if missing).
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$menus     = array(
		'primary'        => array( 'VitalStack Main', vitalstack_setup_main_menu_items( $ids ) ),
		'footer-company' => array( 'VitalStack Company', array( array( 'page', 'about-us' ), array( 'page', 'editorial-policy' ), array( 'page', 'contact-us' ) ) ),
		'footer-legal'   => array( 'VitalStack Legal', array( array( 'page', 'privacy-policy' ), array( 'page', 'terms-of-service' ), array( 'page', 'disclaimer' ), array( 'page', 'cookie-policy' ) ) ),
	);
	foreach ( $menus as $location => $menu ) {
		if ( ! empty( $locations[ $location ] ) && wp_get_nav_menu_object( $locations[ $location ] ) ) {
			continue;
		}
		$menu_obj = wp_get_nav_menu_object( $menu[0] );
		$menu_id  = $menu_obj ? $menu_obj->term_id : wp_create_nav_menu( $menu[0] );
		if ( is_wp_error( $menu_id ) ) {
			continue;
		}
		if ( ! $menu_obj ) {
			foreach ( $menu[1] as $pos => $item ) {
				vitalstack_setup_add_menu_item( $menu_id, $item, $ids, $pos + 1 );
			}
		}
		$locations[ $location ] = $menu_id;
		/* translators: %s: menu name */
		$report[] = sprintf( __( 'Menu set up: %s', 'vitalstack' ), $menu[0] );
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	// Settings.
	if ( '/%postname%/' !== get_option( 'permalink_structure' ) ) {
		// set_permalink_structure() also refreshes the in-memory rewrite object,
		// so the flush below builds post URLs for the new structure (update_option alone doesn't).
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		// Post types only register their URL rules once pretty permalinks are on,
		// so rebuild the rules again on the next page load as well.
		delete_option( 'vitalstack_rewrite_version' );
		$report[] = __( 'Permalinks set to "Post name"', 'vitalstack' );
	}
	$tagline = get_option( 'blogdescription' );
	if ( '' === $tagline || 'Just another WordPress site' === $tagline ) {
		update_option( 'blogdescription', __( 'Learn to code and use AI, explained simply', 'vitalstack' ) );
		$report[] = __( 'Site tagline set', 'vitalstack' );
	}
	flush_rewrite_rules( false );

	if ( $remove_samples ) {
		$report = array_merge( $report, vitalstack_setup_remove_samples() );
	}

	update_option( 'vitalstack_setup_done', time() );
	if ( ! $report ) {
		$report[] = __( 'Everything was already set up. Nothing changed.', 'vitalstack' );
	}
	return $report;
}

function vitalstack_setup_main_menu_items( $ids ) {
	return array(
		array( 'page', 'tutorials' ),
		array( 'page', 'dsa-problems', __( 'Interview Qs', 'vitalstack' ) ),
		array( 'page', 'games', __( 'Games', 'vitalstack' ) ),
		array( 'category', 'artificial-intelligence', __( 'AI Guides', 'vitalstack' ) ),
		array( 'category', 'ai-tools' ),
		array( 'category', 'ai-career', __( 'Careers', 'vitalstack' ) ),
		array( 'page', 'blog', __( 'All guides', 'vitalstack' ) ),
		array( 'page', 'about-us', __( 'About', 'vitalstack' ) ),
	);
}

function vitalstack_setup_add_menu_item( $menu_id, $item, $ids, $position ) {
	$args = array(
		'menu-item-status'   => 'publish',
		'menu-item-position' => $position,
	);
	if ( 'page' === $item[0] ) {
		$page_id = isset( $ids[ $item[1] ] ) ? $ids[ $item[1] ] : 0;
		if ( ! $page_id ) {
			return;
		}
		$args['menu-item-type']      = 'post_type';
		$args['menu-item-object']    = 'page';
		$args['menu-item-object-id'] = $page_id;
	} else {
		$term = get_category_by_slug( $item[1] );
		if ( ! $term ) {
			return;
		}
		$args['menu-item-type']      = 'taxonomy';
		$args['menu-item-object']    = 'category';
		$args['menu-item-object-id'] = $term->term_id;
	}
	if ( ! empty( $item[2] ) ) {
		$args['menu-item-title'] = $item[2];
	}
	wp_update_nav_menu_item( $menu_id, 0, $args );
}

/**
 * Deletes WordPress's sample post, page and comment, only if unedited.
 */
function vitalstack_setup_remove_samples() {
	$report = array();
	$hello  = get_page_by_path( 'hello-world', OBJECT, 'post' );
	if ( $hello && false !== strpos( $hello->post_content, 'Welcome to WordPress' ) ) {
		wp_delete_post( $hello->ID, true );
		$report[] = __( 'Deleted the sample "Hello world!" post', 'vitalstack' );
	}
	$sample = get_page_by_path( 'sample-page', OBJECT, 'page' );
	if ( $sample && false !== strpos( $sample->post_content, 'example page' ) ) {
		wp_delete_post( $sample->ID, true );
		$report[] = __( 'Deleted the "Sample Page"', 'vitalstack' );
	}
	return $report;
}

/* ── Starter definitions ──────────────────────────────────────────────────── */

function vitalstack_setup_paths() {
	// One path per subject, like a classic tutorial site: the topic bar shows
	// these subjects, and each subject's lessons fill the left sidebar.
	return array(
		array(
			'slug'        => 'html',
			'name'        => 'HTML',
			'description' => 'The language for building web pages. Start here if you are new to web development.',
		),
		array(
			'slug'        => 'css',
			'name'        => 'CSS',
			'description' => 'The language for styling web pages: colours, layouts, Flexbox, Grid and responsive design.',
		),
		array(
			'slug'        => 'javascript',
			'name'        => 'JavaScript',
			'description' => 'The language that makes web pages interactive, from your first variable to working with the DOM.',
		),
		array(
			'slug'        => 'sql',
			'name'        => 'SQL',
			'description' => 'The language for storing and querying data in databases like MySQL and PostgreSQL.',
		),
		array(
			'slug'        => 'python',
			'name'        => 'Python',
			'description' => 'A beginner-friendly language used for automation, data and AI.',
		),
		array(
			'slug'        => 'java',
			'name'        => 'Java',
			'description' => 'Core Java from your first program to collections, streams and exceptions, with animations of memory and the call stack.',
		),
		array(
			'slug'        => 'java-oop',
			'name'        => 'Java OOP',
			'description' => 'Object-oriented programming in Java: classes, objects, the four pillars and SOLID, with animated diagrams.',
		),
		array(
			'slug'        => 'servlet',
			'name'        => 'Servlet & JSP',
			'description' => 'How Java web apps answer HTTP requests: servlets, the lifecycle, filters, sessions and JSP.',
		),
		array(
			'slug'        => 'jpa',
			'name'        => 'JPA',
			'description' => 'The Java Persistence API: map classes to tables, relationships, JPQL and the persistence context.',
		),
		array(
			'slug'        => 'hibernate',
			'name'        => 'Hibernate',
			'description' => 'The most popular JPA implementation: sessions, caching, lazy loading, the N+1 problem and performance.',
		),
		array(
			'slug'        => 'spring',
			'name'        => 'Spring',
			'description' => 'The Spring Framework: IoC, dependency injection, beans, AOP, transactions and Spring MVC.',
		),
		array(
			'slug'        => 'spring-boot',
			'name'        => 'Spring Boot',
			'description' => 'Build production-ready REST APIs fast: auto-configuration, Spring Data JPA, validation, security and testing.',
		),
		array(
			'slug'        => 'jmix',
			'name'        => 'Jmix',
			'description' => 'Build full-stack business apps in Java with Jmix: entities, views, data access, security and deployment.',
		),
		array(
			'slug'        => 'react',
			'name'        => 'React',
			'description' => 'Build modern user interfaces with React: components, props, state, hooks, routing and data fetching.',
		),
		array(
			'slug'        => 'angular',
			'name'        => 'Angular',
			'description' => 'Build large web apps with Angular: components, templates, signals, services, routing, forms and RxJS.',
		),
		array(
			'slug'        => 'dsa',
			'name'        => 'DSA',
			'description' => 'Data structures and algorithms, one pattern at a time, with live animations you can play, pause and replay.',
		),
		array(
			'slug'        => 'dsa-problems',
			'name'        => 'Interview Problems',
			'description' => 'Classic coding interview problems solved step by step: brute force first, then the optimal pattern, in Python.',
		),
		// Data and AI paths (added in 4.5; at the end so existing paths keep their order).
		array(
			'slug'        => 'numpy',
			'name'        => 'NumPy',
			'description' => 'Fast maths on whole arrays of numbers: the foundation of data science and AI in Python, with animated arrays.',
		),
		array(
			'slug'        => 'pandas',
			'name'        => 'Pandas',
			'description' => 'Load, clean, filter, group and join tables of data in Python, explained step by step with animated tables.',
		),
		array(
			'slug'        => 'data-visualization',
			'name'        => 'Data Visualization',
			'description' => 'Turn numbers into clear charts with Matplotlib and Seaborn, and learn which chart answers which question.',
		),
		array(
			'slug'        => 'machine-learning',
			'name'        => 'Machine Learning',
			'description' => 'How machines learn from data: your first models with scikit-learn, from train/test split to accuracy.',
		),
		array(
			'slug'        => 'prompt-engineering',
			'name'        => 'Prompt Engineering',
			'description' => 'Get great answers from ChatGPT, Claude and Gemini: clear prompts, examples, roles, step-by-step thinking and safe use of AI tools.',
		),
	);
}

function vitalstack_setup_categories() {
	return array(
		array(
			'slug'        => 'artificial-intelligence',
			'name'        => 'Artificial Intelligence',
			'description' => 'How AI, machine learning, language models and AI agents work, explained for beginners.',
		),
		array(
			'slug'        => 'ai-tools',
			'name'        => 'AI Tools',
			'description' => 'Practical guides to using AI tools well: prompts, workflows and honest comparisons.',
		),
		array(
			'slug'        => 'ai-career',
			'name'        => 'AI Career',
			'description' => 'Skills, learning roadmaps and practical advice for getting into tech and AI jobs.',
		),
		array(
			'slug'        => 'programming-development',
			'name'        => 'Programming',
			'description' => 'Coding guides, comparisons and project walkthroughs beyond the step-by-step tutorials.',
		),
	);
}

/**
 * Converts a compact list of [type, text] rows into block markup, so pages
 * open cleanly in the block editor. Text may contain basic inline HTML.
 */
function vitalstack_setup_blocks( $rows ) {
	$out = '';
	foreach ( $rows as $row ) {
		list( $type, $text ) = $row;
		if ( 'h2' === $type ) {
			$out .= "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">{$text}</h2>\n<!-- /wp:heading -->\n\n";
		} elseif ( 'ul' === $type ) {
			$items = '';
			foreach ( $text as $li ) {
				$items .= "<!-- wp:list-item -->\n<li>{$li}</li>\n<!-- /wp:list-item -->\n";
			}
			$out .= "<!-- wp:list -->\n<ul class=\"wp-block-list\">{$items}</ul>\n<!-- /wp:list -->\n\n";
		} else {
			$out .= "<!-- wp:paragraph -->\n<p>{$text}</p>\n<!-- /wp:paragraph -->\n\n";
		}
	}
	return $out;
}

function vitalstack_setup_pages() {
	$site    = esc_html( wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) );
	$site    = $site ? $site : 'VitalStack';
	$email   = function_exists( 'vitalstack_contact_email' ) ? vitalstack_contact_email() : 'contact@vitalstack.co.in';
	$mail    = '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
	$contact = '<a href="/contact-us/">contact page</a>';
	$user    = wp_get_current_user();
	$name    = $user && $user->exists() ? esc_html( $user->display_name ) : '';
	$date    = esc_html( wp_date( 'F j, Y' ) );

	return array(
		'tutorials'        => array(
			'title'    => 'Tutorials',
			'status'   => 'publish',
			'template' => 'page-tutorials.php',
			'content'  => '',
		),
		'blog'             => array(
			'title'    => 'Blog',
			'status'   => 'publish',
			'template' => 'page-blog.php',
			'content'  => '',
		),
		'games'            => array(
			'title'    => 'Brain Games',
			'status'   => 'publish',
			'template' => 'page-games.php',
			'content'  => '',
		),
		'dsa-problems'     => array(
			'title'    => 'Interview Problems',
			'status'   => 'publish',
			'template' => 'page-dsa-problems.php',
			'content'  => '',
		),
		'daily-challenge'  => array(
			'title'    => 'Daily Challenge',
			'status'   => 'publish',
			'template' => 'page-daily-challenge.php',
			'content'  => '',
		),
		'cheat-sheets'     => array(
			'title'    => 'Cheat Sheets',
			'status'   => 'publish',
			'template' => 'page-cheat-sheets.php',
			'content'  => '',
		),
		'tools'            => array(
			'title'    => 'Free Coding Tools',
			'status'   => 'publish',
			'template' => 'page-tools.php',
			'content'  => '',
		),
		'mock-tests'       => array(
			'title'    => 'Mock Tests',
			'status'   => 'publish',
			'template' => 'page-mock-tests.php',
			'content'  => '',
		),
		'roadmaps'         => array(
			'title'    => 'Career Roadmaps',
			'status'   => 'publish',
			'template' => 'page-roadmaps.php',
			'content'  => '',
		),
		'contact-us'       => array(
			'title'    => 'Contact Us',
			'status'   => 'publish',
			'template' => 'page-contact.php',
			'content'  => '',
		),
		'about-us'         => array(
			'title'    => 'About Us',
			'status'   => 'draft',
			'template' => 'page-about.php',
			'content'  => vitalstack_setup_blocks(
				array(
					array( 'h2', 'Why ' . $site . ' exists' ),
					array( 'p', 'When I started learning to code, most tutorials either assumed I already knew things I didn\'t, or buried the useful part under pages of theory. I spent a lot of time stuck on small problems that one clear sentence would have solved.' ),
					array( 'p', $site . ' is the site I wish I\'d had then: <strong>plain-language guides to programming and AI, in the order you should learn them</strong>, with examples you can actually run.' ),
					array( 'h2', 'Who it\'s for' ),
					array( 'ul', array( 'Students and beginners learning their first programming language', 'People switching careers into tech', 'Anyone who wants to use AI tools properly instead of guessing' ) ),
					array( 'h2', 'Our promise' ),
					array( 'ul', array( '<strong>No jargon without an explanation.</strong>', '<strong>Every code example is tested</strong> before it\'s published.', '<strong>Honest about limits.</strong> If something is hard, outdated or not worth learning yet, we say so.', '<strong>Kept current.</strong> Guides are updated when tools change, and the "Updated" date at the top of each article tells you when.' ) ),
					array( 'p', $site . ' is written and maintained by ' . ( $name ? $name : '[your pen name]' ) . ', [one honest line about you, for example "a self-taught developer from India"].' ),
				)
			),
		),
		'editorial-policy' => array(
			'title'    => 'Editorial Policy',
			'status'   => 'draft',
			'template' => '',
			'content'  => vitalstack_setup_blocks(
				array(
					array( 'p', $site . ' publishes beginner-friendly guides to programming and artificial intelligence. This page explains how those guides are made.' ),
					array( 'h2', 'What we publish' ),
					array( 'ul', array( '<strong>Tutorials</strong> organised into learning paths, from first steps to small projects.', '<strong>Guides</strong> explaining AI concepts and tools and how to use them well.', '<strong>Career guides</strong> on skills and roadmaps for getting into tech.' ) ),
					array( 'p', 'We do not publish sponsored content disguised as editorial content. If we ever publish a sponsored post or use affiliate links, it will be clearly labelled.' ),
					array( 'h2', 'How a guide is made' ),
					array( 'ul', array( '<strong>A real question.</strong> Each guide starts from a question beginners actually ask.', '<strong>Research.</strong> We read official documentation and primary sources first, and link to them where it helps.', '<strong>Drafting.</strong> We use AI tools to help with research, outlines and first drafts. AI output is never published as-is.', '<strong>Testing.</strong> Every code example is run before publishing.', '<strong>Editing.</strong> A person reviews every article for accuracy, clarity and usefulness, and takes responsibility for what is published.' ) ),
					array( 'h2', 'Updates and corrections' ),
					array( 'p', 'Technology changes quickly. We review popular guides regularly and update them when something changes. The date at the top of each article shows when it was last updated. If you find a mistake, please tell us through the ' . $contact . ' or at ' . $mail . '.' ),
					array( 'h2', 'Advertising' ),
					array( 'p', $site . ' is free to read and is supported by display advertising (Google AdSense). Advertisers have no influence on what we write, and ads are always visually separate from article content.' ),
				)
			),
		),
		'privacy-policy'   => array(
			'title'    => 'Privacy Policy',
			'status'   => 'draft',
			'template' => 'page-legal.php',
			'content'  => vitalstack_setup_blocks(
				array(
					array( 'p', '<em>Last updated: ' . $date . '</em>' ),
					array( 'p', 'This Privacy Policy explains what information ' . $site . ' ("we", "us") collects when you use this website, how we use it, and the choices you have. If you have questions, contact us at ' . $mail . '.' ),
					array( 'h2', 'Information we collect' ),
					array( 'ul', array( '<strong>Account information.</strong> If you create an account: your name, email address and password (stored only in encrypted, hashed form), your email preferences, and which lessons you have marked as complete.', '<strong>Messages.</strong> If you use the contact form: your name, email address and message.', '<strong>Comments.</strong> If you comment: your name, email address and comment. Your email is never published.', '<strong>Usage data.</strong> Like most websites, our server and analytics tools receive technical data such as your IP address, browser type, pages visited and the time of your visit.', '<strong>Data stored in your browser.</strong> Your dark-mode choice and lesson progress (when not signed in) are saved in your own browser and are not sent to us.' ) ),
					array( 'h2', 'How we use information' ),
					array( 'ul', array( 'To run your account and save your learning progress.', 'To confirm your email address and, only if you subscribe, to email you when new lessons and guides are published.', 'To reply to messages you send us.', 'To understand which content is useful and improve the site.', 'To keep the site secure and prevent spam and abuse.' ) ),
					array( 'p', 'We do not sell, rent or trade your personal information.' ),
					array( 'h2', 'Advertising (Google AdSense)' ),
					array( 'p', 'We use Google AdSense to show ads. Third-party vendors, including Google, use cookies to serve ads based on your prior visits to this and other websites. Google\'s use of advertising cookies enables it and its partners to serve ads to you based on your visits to this site and/or other sites on the Internet.' ),
					array( 'p', 'You can opt out of personalised advertising by visiting <a href="https://adssettings.google.com" rel="nofollow">Google Ads Settings</a>, or opt out of some third-party vendors\' use of cookies at <a href="https://www.aboutads.info/choices/" rel="nofollow">www.aboutads.info</a>. Learn more in <a href="https://policies.google.com/technologies/partner-sites" rel="nofollow">how Google uses information from sites that use its services</a>.' ),
					array( 'h2', 'Analytics' ),
					array( 'p', 'We may use Google Analytics to understand how visitors use the site. It collects usage data through cookies. See <a href="https://policies.google.com/privacy" rel="nofollow">Google\'s Privacy Policy</a>.' ),
					array( 'h2', 'Emails and unsubscribing' ),
					array( 'p', 'We only send new-lesson emails to confirmed email addresses that chose to subscribe. Every email has a one-click unsubscribe link, and you can turn emails off any time on your account page.' ),
					array( 'h2', 'How long we keep data' ),
					array( 'p', 'We keep account data while your account exists. Contact messages are kept as long as needed to reply and follow up. You can ask us to delete your account and its data at any time.' ),
					array( 'h2', 'Your rights' ),
					array( 'p', 'You can ask us to access, correct, export or delete the personal information we hold about you by emailing ' . $mail . '. We will respond within 30 days.' ),
					array( 'h2', 'Children' ),
					array( 'p', 'This site is not directed at children under 13, and we do not knowingly collect personal information from them.' ),
					array( 'h2', 'Changes' ),
					array( 'p', 'We may update this policy. The date at the top shows the latest version.' ),
				)
			),
		),
		'terms-of-service' => array(
			'title'    => 'Terms of Service',
			'status'   => 'draft',
			'template' => 'page-legal.php',
			'content'  => vitalstack_setup_blocks(
				array(
					array( 'p', '<em>Last updated: ' . $date . '</em>' ),
					array( 'p', 'By using ' . $site . ' you agree to these terms. If you do not agree, please do not use the site.' ),
					array( 'h2', 'Using our content' ),
					array( 'p', 'You may read, share links to and learn from our tutorials and guides. You may use the code examples in your own projects, personal or commercial. You may not copy or republish our articles, in full or in large parts, on other websites or platforms without written permission.' ),
					array( 'h2', 'Accounts' ),
					array( 'p', 'You are responsible for keeping your password safe and for activity on your account. Give accurate information when you sign up. We may suspend accounts used for spam, abuse or anything unlawful.' ),
					array( 'h2', 'Comments and messages' ),
					array( 'p', 'Do not post anything unlawful, abusive, misleading or spam. We may edit or remove comments at our discretion.' ),
					array( 'h2', 'Code examples and accuracy' ),
					array( 'p', 'Code examples are provided "as is" for learning. We test them, but we cannot guarantee they are free of errors or suitable for your use. Review and test code before using it in production.' ),
					array( 'h2', 'Limitation of liability' ),
					array( 'p', 'To the extent permitted by law, ' . $site . ' is not liable for any loss or damage arising from your use of the site or its content.' ),
					array( 'h2', 'Links to other sites' ),
					array( 'p', 'We link to other websites for reference. We are not responsible for their content or practices.' ),
					array( 'h2', 'Changes and governing law' ),
					array( 'p', 'We may update these terms; the date above shows the latest version. These terms are governed by the laws of India.' ),
					array( 'p', 'Questions? Email ' . $mail . '.' ),
				)
			),
		),
		'disclaimer'       => array(
			'title'    => 'Disclaimer',
			'status'   => 'draft',
			'template' => 'page-legal.php',
			'content'  => vitalstack_setup_blocks(
				array(
					array( 'p', '<em>Last updated: ' . $date . '</em>' ),
					array( 'h2', 'Educational content' ),
					array( 'p', 'Everything on ' . $site . ' is for general educational purposes. We work hard to keep tutorials and guides accurate and up to date, but technology changes quickly and mistakes can happen. Always check official documentation for critical work.' ),
					array( 'h2', 'No professional advice' ),
					array( 'p', 'Career and tool guides reflect our research and opinion. They are not financial, legal or professional advice. Make important decisions based on your own situation, and consult a qualified professional where needed.' ),
					array( 'h2', 'Use of AI' ),
					array( 'p', 'We use AI tools to help research and draft some content. A person reviews, tests and edits every article before it is published.' ),
					array( 'h2', 'Advertising and external links' ),
					array( 'p', 'This site shows ads through Google AdSense and links to external websites. We do not control and are not responsible for the content of ads or external sites. If we ever use affiliate links, we will say so on that page.' ),
					array( 'p', 'Found an error? Please tell us via the ' . $contact . '.' ),
				)
			),
		),
		'cookie-policy'    => array(
			'title'    => 'Cookie Policy',
			'status'   => 'draft',
			'template' => 'page-legal.php',
			'content'  => vitalstack_setup_blocks(
				array(
					array( 'p', '<em>Last updated: ' . $date . '</em>' ),
					array( 'p', 'Cookies are small text files a website stores in your browser. This page explains which ones ' . $site . ' uses.' ),
					array( 'h2', 'Cookies we use' ),
					array( 'ul', array( '<strong>Essential:</strong> sign-in cookies that keep you logged in to your account, and security cookies for forms.', '<strong>Preferences:</strong> your dark-mode choice, cookie-notice dismissal and lesson progress are stored in your browser\'s local storage.', '<strong>Analytics:</strong> if enabled, Google Analytics cookies that help us understand which pages are useful.', '<strong>Advertising:</strong> Google AdSense and its partners use cookies to show ads and measure them. Google may use these cookies to show ads based on your visits to this and other sites.' ) ),
					array( 'h2', 'Managing cookies' ),
					array( 'p', 'You can delete or block cookies in your browser settings. Blocking essential cookies will stop sign-in from working. You can control personalised ads at <a href="https://adssettings.google.com" rel="nofollow">Google Ads Settings</a>. Visitors in the EU/UK are asked for consent through Google\'s consent message.' ),
					array( 'p', 'Questions? Email ' . $mail . '.' ),
				)
			),
		),
	);
}
