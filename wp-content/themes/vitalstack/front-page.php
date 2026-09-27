<?php
/**
 * Homepage.
 *
 * @package VitalStack
 */

get_header();

$vs_paths = vitalstack_learning_paths();

// Keep the homepage on the site's focus: AI and programming, not health.
$vs_excluded_cats = array();
$vs_health        = get_category_by_slug( 'health-digital-wellness' );
if ( $vs_health ) {
	$vs_excluded_cats   = get_term_children( $vs_health->term_id, 'category' );
	$vs_excluded_cats[] = $vs_health->term_id;
}
$vs_latest = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 6,
		'category__not_in'    => $vs_excluded_cats,
		'ignore_sticky_posts' => false,
		'no_found_rows'       => true,
	)
);
?>

<section class="hero">
	<div class="container hero-inner">
		<p class="eyebrow"><?php echo vitalstack_icon( 'spark', 16 ); // phpcs:ignore ?> <?php echo esc_html( get_theme_mod( 'vitalstack_home_kicker', vitalstack_default( 'home_kicker' ) ) ); ?></p>
		<h1 class="hero-title"><?php echo esc_html( get_theme_mod( 'vitalstack_home_title', vitalstack_default( 'home_title' ) ) ); ?></h1>
		<p class="hero-desc"><?php echo esc_html( get_theme_mod( 'vitalstack_home_desc', vitalstack_default( 'home_desc' ) ) ); ?></p>
		<div class="hero-search"><?php get_search_form(); ?></div>
		<?php if ( $vs_paths ) : ?>
			<div class="chips" aria-label="<?php esc_attr_e( 'Popular topics', 'vitalstack' ); ?>">
				<span class="chips-label"><?php esc_html_e( 'Jump to:', 'vitalstack' ); ?></span>
				<?php foreach ( $vs_paths as $vs_path ) : ?>
					<a class="chip" href="<?php echo esc_url( get_term_link( $vs_path ) ); ?>"><?php echo esc_html( $vs_path->name ); ?></a>
				<?php endforeach; ?>
				<a class="chip" href="<?php echo esc_url( vitalstack_category_url( 'artificial-intelligence' ) ); ?>"><?php esc_html_e( 'AI Guides', 'vitalstack' ); ?></a>
				<a class="chip" href="<?php echo esc_url( vitalstack_category_url( 'ai-tools' ) ); ?>"><?php esc_html_e( 'AI Tools', 'vitalstack' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
	<div class="hero-code" aria-hidden="true">
		<div class="code-window">
			<div class="code-window-bar"><span></span><span></span><span></span><em>learn.js</em></div>
<pre><code><span class="t-k">const</span> you = { <span class="t-p">skills</span>: [] };

<span class="t-c">// one lesson at a time</span>
<span class="t-k">for</span> (<span class="t-k">const</span> topic <span class="t-k">of</span> [<span class="t-s">"HTML"</span>, <span class="t-s">"CSS"</span>, <span class="t-s">"JS"</span>, <span class="t-s">"AI"</span>]) {
  you.skills.<span class="t-f">push</span>(topic);
}

console.<span class="t-f">log</span>(<span class="t-s">"Ready to build 🚀"</span>);</code></pre>
		</div>
	</div>
</section>

<?php if ( $vs_paths ) : ?>
<section class="section">
	<div class="container">
		<header class="section-head">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'Learning paths', 'vitalstack' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( 'Pick a path and learn in order', 'vitalstack' ); ?></h2>
				<p class="section-desc"><?php esc_html_e( 'Each path is a sequence of lessons. Start at lesson 1, mark lessons complete, and pick up where you left off.', 'vitalstack' ); ?></p>
			</div>
			<a class="link-arrow" href="<?php echo esc_url( vitalstack_tutorials_url() ); ?>"><?php esc_html_e( 'All tutorials', 'vitalstack' ); ?> <?php echo vitalstack_icon( 'arrow', 16 ); // phpcs:ignore ?></a>
		</header>
		<div class="grid grid-paths">
			<?php
			foreach ( $vs_paths as $vs_path ) :
				$vs_lessons = vitalstack_path_lessons( $vs_path );
				$vs_style   = vitalstack_term_style( $vs_path, 'tutorials' );
				?>
				<article class="path-card" data-path="<?php echo esc_attr( $vs_path->slug ); ?>" data-total="<?php echo count( $vs_lessons ); ?>">
					<span class="path-icon tone-<?php echo esc_attr( $vs_style[1] ); ?>"><?php echo vitalstack_icon( $vs_style[0], 22 ); // phpcs:ignore ?></span>
					<h3 class="path-card-title"><a href="<?php echo esc_url( get_term_link( $vs_path ) ); ?>"><?php echo esc_html( $vs_path->name ); ?></a></h3>
					<ol class="path-card-lessons">
						<?php foreach ( array_slice( $vs_lessons, 0, 4 ) as $vs_lesson ) : ?>
							<li data-lesson="<?php echo (int) $vs_lesson; ?>"><?php echo esc_html( vitalstack_short_title( $vs_lesson ) ); ?></li>
						<?php endforeach; ?>
						<?php if ( count( $vs_lessons ) > 4 ) : ?>
							<li class="more">+<?php echo count( $vs_lessons ) - 4; ?> <?php esc_html_e( 'more', 'vitalstack' ); ?></li>
						<?php endif; ?>
					</ol>
					<div class="path-card-foot">
						<div class="path-progress" aria-hidden="true"><span class="path-progress-bar"></span></div>
						<a class="btn btn-soft btn-sm path-start" href="<?php echo esc_url( get_permalink( $vs_lessons[0] ) ); ?>" data-start-label="<?php esc_attr_e( 'Start path', 'vitalstack' ); ?>" data-resume-label="<?php esc_attr_e( 'Continue', 'vitalstack' ); ?>"><?php esc_html_e( 'Start path', 'vitalstack' ); ?> <?php echo vitalstack_icon( 'arrow', 16 ); // phpcs:ignore ?></a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( $vs_latest->have_posts() ) : ?>
<section class="section section-alt">
	<div class="container">
		<header class="section-head">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'Guides', 'vitalstack' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( 'Latest AI & tech guides', 'vitalstack' ); ?></h2>
			</div>
			<a class="link-arrow" href="<?php echo esc_url( vitalstack_page_url( 'blog' ) ); ?>"><?php esc_html_e( 'All guides', 'vitalstack' ); ?> <?php echo vitalstack_icon( 'arrow', 16 ); // phpcs:ignore ?></a>
		</header>
		<div class="grid grid-feature">
			<?php
			$vs_i = 0;
			while ( $vs_latest->have_posts() ) :
				$vs_latest->the_post();
				vitalstack_card( get_the_ID(), array( 'featured' => 0 === $vs_i ) );
				$vs_i++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
$vs_topic_slugs = array( 'ai-tools', 'ai-career', 'programming-development' );
$vs_topics      = array_filter( array_map( 'get_category_by_slug', $vs_topic_slugs ) );
if ( $vs_topics ) :
	?>
<section class="section">
	<div class="container">
		<header class="section-head">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'Explore', 'vitalstack' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( 'Browse by topic', 'vitalstack' ); ?></h2>
			</div>
		</header>
		<div class="grid grid-3">
			<?php
			foreach ( $vs_topics as $vs_topic ) :
				$vs_style = vitalstack_term_style( $vs_topic );
				$vs_posts = get_posts(
					array(
						'cat'            => $vs_topic->term_id,
						'posts_per_page' => 4,
					)
				);
				?>
				<div class="topic-col">
					<h3 class="topic-title">
						<span class="path-icon path-icon-sm tone-<?php echo esc_attr( $vs_style[1] ); ?>"><?php echo vitalstack_icon( $vs_style[0], 18 ); // phpcs:ignore ?></span>
						<a href="<?php echo esc_url( get_category_link( $vs_topic ) ); ?>"><?php echo esc_html( $vs_topic->name ); ?></a>
					</h3>
					<ul class="topic-list">
						<?php foreach ( $vs_posts as $vs_p ) : ?>
							<li><a href="<?php echo esc_url( get_permalink( $vs_p ) ); ?>"><?php echo esc_html( get_the_title( $vs_p ) ); ?></a></li>
						<?php endforeach; ?>
					</ul>
					<a class="link-arrow" href="<?php echo esc_url( get_category_link( $vs_topic ) ); ?>">
						<?php
						/* translators: %d: number of articles */
						echo esc_html( sprintf( _n( 'See %d article', 'See all %d articles', $vs_topic->count, 'vitalstack' ), $vs_topic->count ) );
						?>
						<?php echo vitalstack_icon( 'arrow', 16 ); // phpcs:ignore ?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="section section-alt">
	<div class="container">
		<header class="section-head section-head-center">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'How we teach', 'vitalstack' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( 'Built for people who are just starting out', 'vitalstack' ); ?></h2>
			</div>
		</header>
		<div class="grid grid-4 values">
			<div class="value">
				<span class="path-icon tone-green"><?php echo vitalstack_icon( 'book', 22 ); // phpcs:ignore ?></span>
				<h3><?php esc_html_e( 'Plain language', 'vitalstack' ); ?></h3>
				<p><?php esc_html_e( 'Every term is explained the first time it appears. No prior knowledge assumed.', 'vitalstack' ); ?></p>
			</div>
			<div class="value">
				<span class="path-icon tone-blue"><?php echo vitalstack_icon( 'code', 22 ); // phpcs:ignore ?></span>
				<h3><?php esc_html_e( 'Learn by doing', 'vitalstack' ); ?></h3>
				<p><?php esc_html_e( 'Examples you can copy, run and change, so you actually remember them.', 'vitalstack' ); ?></p>
			</div>
			<div class="value">
				<span class="path-icon tone-violet"><?php echo vitalstack_icon( 'layers', 22 ); // phpcs:ignore ?></span>
				<h3><?php esc_html_e( 'In the right order', 'vitalstack' ); ?></h3>
				<p><?php esc_html_e( 'Learning paths take you from the basics to real projects, one step at a time.', 'vitalstack' ); ?></p>
			</div>
			<div class="value">
				<span class="path-icon tone-amber"><?php echo vitalstack_icon( 'calendar', 22 ); // phpcs:ignore ?></span>
				<h3><?php esc_html_e( 'Kept up to date', 'vitalstack' ); ?></h3>
				<p><?php esc_html_e( 'Guides are reviewed and updated when tools and best practices change.', 'vitalstack' ); ?></p>
			</div>
		</div>
		<p class="values-foot"><a class="link-arrow" href="<?php echo esc_url( vitalstack_page_url( 'about-us' ) ); ?>"><?php esc_html_e( 'Read about VitalStack', 'vitalstack' ); ?> <?php echo vitalstack_icon( 'arrow', 16 ); // phpcs:ignore ?></a></p>
	</div>
</section>

<?php
if ( ! get_theme_mod( 'vitalstack_hide_news', false ) ) :
	$vs_news = get_posts(
		array(
			'post_type'      => 'news',
			'posts_per_page' => 4,
		)
	);
	if ( $vs_news ) :
		?>
<section class="section">
	<div class="container">
		<header class="section-head">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'News', 'vitalstack' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( 'Recent tech news', 'vitalstack' ); ?></h2>
			</div>
			<a class="link-arrow" href="<?php echo esc_url( get_post_type_archive_link( 'news' ) ); ?>"><?php esc_html_e( 'All news', 'vitalstack' ); ?> <?php echo vitalstack_icon( 'arrow', 16 ); // phpcs:ignore ?></a>
		</header>
		<ul class="news-list">
			<?php foreach ( $vs_news as $vs_n ) : ?>
				<li>
					<time datetime="<?php echo esc_attr( get_the_date( 'c', $vs_n ) ); ?>"><?php echo esc_html( get_the_date( 'M j', $vs_n ) ); ?></time>
					<a href="<?php echo esc_url( get_permalink( $vs_n ) ); ?>"><?php echo esc_html( get_the_title( $vs_n ) ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
		<?php
	endif;
endif;

get_footer();
