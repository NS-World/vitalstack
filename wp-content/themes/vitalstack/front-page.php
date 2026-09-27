<?php
/**
 * Homepage: search-first hero, one band per learning path, AI guides,
 * editor promo and latest guides.
 *
 * @package VitalStack
 */

get_header();

$vs_paths = vitalstack_learning_paths();
$vs_first = ( $vs_paths && vitalstack_path_lessons( $vs_paths[0] ) ) ? vitalstack_path_lessons( $vs_paths[0] )[0] : 0;

// Keep the homepage on the site's focus: AI and programming, not health.
$vs_excluded = array();
$vs_health   = get_category_by_slug( 'health-digital-wellness' );
if ( $vs_health ) {
	$vs_excluded   = get_term_children( $vs_health->term_id, 'category' );
	$vs_excluded[] = $vs_health->term_id;
}
?>

<section class="home-hero">
	<div class="wrap home-hero-inner">
		<h1><?php echo esc_html( get_theme_mod( 'vitalstack_home_title', vitalstack_default( 'home_title' ) ) ); ?></h1>
		<p class="home-hero-sub"><?php echo esc_html( get_theme_mod( 'vitalstack_home_desc', vitalstack_default( 'home_desc' ) ) ); ?></p>
		<div class="home-search"><?php get_search_form(); ?></div>
		<?php if ( $vs_first ) : ?>
			<p class="home-hero-start">
				<?php esc_html_e( 'Not sure where to begin?', 'vitalstack' ); ?>
				<a href="<?php echo esc_url( get_permalink( $vs_first ) ); ?>"><?php echo esc_html( sprintf( /* translators: %s: subject */ __( 'Start with %s', 'vitalstack' ), vitalstack_subject_label( $vs_first ) ) ); ?> ❯</a>
			</p>
		<?php endif; ?>
	</div>
</section>

<?php
$vs_tones = array( 'mint', 'sun', 'sky', 'stone' );
foreach ( $vs_paths as $vs_i => $vs_path ) :
	$vs_lessons = vitalstack_path_lessons( $vs_path );
	$vs_example = vitalstack_path_example( $vs_path );
	$vs_tone    = $vs_tones[ $vs_i % count( $vs_tones ) ];
	?>
	<section class="subject subject-<?php echo esc_attr( $vs_tone ); ?>" data-path="<?php echo esc_attr( $vs_path->slug ); ?>" data-total="<?php echo count( $vs_lessons ); ?>">
		<div class="wrap subject-inner<?php echo $vs_i % 2 ? ' is-flipped' : ''; ?>">
			<div class="subject-text">
				<h2 class="subject-title"><?php echo esc_html( $vs_path->name ); ?></h2>
				<p class="subject-desc">
					<?php
					echo esc_html(
						$vs_path->description ? $vs_path->description : sprintf(
							/* translators: %d: number of lessons */
							_n( '%d beginner-friendly lesson, in the right order.', '%d beginner-friendly lessons, in the right order.', count( $vs_lessons ), 'vitalstack' ),
							count( $vs_lessons )
						)
					);
					?>
				</p>
				<ol class="subject-lessons">
					<?php foreach ( $vs_lessons as $vs_n => $vs_lesson ) : ?>
						<li data-lesson="<?php echo (int) $vs_lesson; ?>">
							<a href="<?php echo esc_url( get_permalink( $vs_lesson ) ); ?>">
								<span class="lesson-num"><span class="lesson-n"><?php echo (int) $vs_n + 1; ?></span><?php echo vitalstack_icon( 'check', 12 ); // phpcs:ignore ?></span>
								<?php echo esc_html( vitalstack_subject_label( $vs_lesson ) ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ol>
				<div class="subject-actions">
					<a class="btn btn-primary btn-lg path-start" href="<?php echo esc_url( get_permalink( $vs_lessons[0] ) ); ?>" data-resume-label="<?php esc_attr_e( 'Continue learning', 'vitalstack' ); ?>"><?php esc_html_e( 'Start learning', 'vitalstack' ); ?> ❯</a>
					<a class="btn btn-dark btn-lg" href="<?php echo esc_url( get_term_link( $vs_path ) ); ?>"><?php esc_html_e( 'See all lessons', 'vitalstack' ); ?></a>
				</div>
				<div class="path-progress subject-progress" aria-hidden="true"><span class="path-progress-bar"></span></div>
			</div>
			<div class="subject-example">
				<div class="example">
					<p class="example-label"><?php echo esc_html( sprintf( /* translators: %s: language */ __( '%s Example', 'vitalstack' ), $vs_example[0] ) ); ?></p>
					<pre class="example-code"><code class="language-<?php echo esc_attr( strtolower( $vs_example[0] ) ); ?>"><?php echo esc_html( $vs_example[1] ); ?></code></pre>
					<?php if ( $vs_example[2] ) : ?>
						<button type="button" class="btn btn-primary" data-tryit-lang="<?php echo esc_attr( $vs_example[2] ); ?>"><?php esc_html_e( 'Try it Yourself', 'vitalstack' ); ?> ❯</button>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
<?php endforeach; ?>

<?php
$vs_ai = get_posts(
	array(
		'post_type'        => 'post',
		'posts_per_page'   => 6,
		'category__not_in' => $vs_excluded,
	)
);
if ( $vs_ai ) :
	?>
	<section class="home-section">
		<div class="wrap">
			<div class="home-section-head">
				<h2><?php esc_html_e( 'AI Guides', 'vitalstack' ); ?></h2>
				<p><?php esc_html_e( 'Understand AI tools, agents and prompts, and use them well.', 'vitalstack' ); ?></p>
			</div>
			<div class="grid grid-3">
				<?php
				foreach ( $vs_ai as $vs_p ) {
					vitalstack_card( $vs_p->ID );
				}
				?>
			</div>
			<p class="center"><a class="btn btn-dark" href="<?php echo esc_url( vitalstack_page_url( 'blog' ) ); ?>"><?php esc_html_e( 'Browse all guides', 'vitalstack' ); ?> ❯</a></p>
		</div>
	</section>
<?php endif; ?>

<section class="home-editor">
	<div class="wrap home-editor-inner">
		<div>
			<h2><?php esc_html_e( 'Code Editor', 'vitalstack' ); ?></h2>
			<p><?php esc_html_e( 'Edit code and see the result instantly, right in your browser. Every HTML and JavaScript example in our tutorials has a “Try it Yourself” button.', 'vitalstack' ); ?></p>
			<button type="button" class="btn btn-primary btn-lg" data-tryit-lang="html" data-tryit-code="<?php echo esc_attr( "<!DOCTYPE html>\n<html>\n<head>\n<style>\n  body { font-family: sans-serif; padding: 20px; }\n  h1 { color: #0a7d4f; }\n</style>\n</head>\n<body>\n\n<h1>Hello, VitalStack!</h1>\n<p>Change this text and press Run.</p>\n<button onclick=\"this.textContent = 'Clicked!'\">Click me</button>\n\n</body>\n</html>" ); ?>"><?php esc_html_e( 'Try the editor', 'vitalstack' ); ?> ❯</button>
		</div>
		<div class="editor-mock" aria-hidden="true">
			<div class="editor-mock-bar"><span></span><span></span><span></span></div>
			<div class="editor-mock-body">
<pre><span class="t-tag">&lt;h1&gt;</span>Hello, VitalStack!<span class="t-tag">&lt;/h1&gt;</span>
<span class="t-tag">&lt;p&gt;</span>Change this text and press Run.<span class="t-tag">&lt;/p&gt;</span>
<span class="t-tag">&lt;button</span> <span class="t-attr">onclick</span>=<span class="t-str">"…"</span><span class="t-tag">&gt;</span>Click me<span class="t-tag">&lt;/button&gt;</span></pre>
				<div class="editor-mock-result"><strong>Hello, VitalStack!</strong><span>Change this text and press Run.</span><em>Click me</em></div>
			</div>
		</div>
	</div>
</section>

<?php if ( ! is_user_logged_in() && vitalstack_accounts_enabled() ) : ?>
	<section class="home-account">
		<div class="wrap home-account-inner">
			<div>
				<h2><?php esc_html_e( 'Track your progress: it’s free', 'vitalstack' ); ?></h2>
				<p><?php esc_html_e( 'Create an account to save completed lessons on every device and get new tutorials by email.', 'vitalstack' ); ?></p>
			</div>
			<div class="home-account-actions">
				<a class="btn btn-primary btn-lg" href="<?php echo esc_url( vitalstack_account_url( array( 'tab' => 'register' ) ) ); ?>"><?php esc_html_e( 'Sign up free', 'vitalstack' ); ?></a>
				<a class="btn btn-ghost btn-lg" href="<?php echo esc_url( vitalstack_account_url() ); ?>"><?php esc_html_e( 'Sign in', 'vitalstack' ); ?></a>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
if ( ! get_theme_mod( 'vitalstack_hide_news', false ) ) :
	$vs_news = get_posts(
		array(
			'post_type'      => 'news',
			'posts_per_page' => 5,
		)
	);
	if ( $vs_news ) :
		?>
		<section class="home-section">
			<div class="wrap narrow">
				<div class="home-section-head">
					<h2><?php esc_html_e( 'Tech news', 'vitalstack' ); ?></h2>
				</div>
				<ul class="news-list">
					<?php foreach ( $vs_news as $vs_n ) : ?>
						<li><time datetime="<?php echo esc_attr( get_the_date( 'c', $vs_n ) ); ?>"><?php echo esc_html( get_the_date( 'M j', $vs_n ) ); ?></time> <a href="<?php echo esc_url( get_permalink( $vs_n ) ); ?>"><?php echo esc_html( get_the_title( $vs_n ) ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
		<?php
	endif;
endif;

get_footer();
