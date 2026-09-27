<?php
/**
 * Template Name: About Page
 *
 * Page content from the editor comes first (your own story), followed by
 * standard sections that explain the site, the editorial process and who
 * writes it.
 *
 * @package VitalStack
 */

get_header();

$vs_owner = get_users(
	array(
		'orderby'             => 'post_count',
		'order'               => 'DESC',
		'number'              => 1,
		'has_published_posts' => array( 'post', 'tutorials' ),
	)
);
$vs_owner = $vs_owner ? $vs_owner[0] : null;

while ( have_posts() ) :
	the_post();
	?>
	<header class="page-hero">
		<div class="container narrow">
			<p class="eyebrow"><?php esc_html_e( 'About', 'vitalstack' ); ?></p>
			<h1 class="page-title"><?php the_title(); ?></h1>
			<p class="page-desc"><?php esc_html_e( 'VitalStack helps beginners learn AI and programming through clear, step-by-step guides with real examples.', 'vitalstack' ); ?></p>
		</div>
	</header>

	<section class="section section-tight">
		<div class="container narrow">
			<?php if ( trim( get_the_content() ) ) : ?>
				<div class="prose"><?php the_content(); ?></div>
			<?php endif; ?>

			<div class="prose">
				<h2><?php esc_html_e( 'What you will find here', 'vitalstack' ); ?></h2>
				<ul>
					<li><strong><?php esc_html_e( 'Learning paths:', 'vitalstack' ); ?></strong> <?php esc_html_e( 'ordered tutorials for web development, databases, backend and programming fundamentals.', 'vitalstack' ); ?></li>
					<li><strong><?php esc_html_e( 'AI guides:', 'vitalstack' ); ?></strong> <?php esc_html_e( 'how AI tools, agents and language models work, and how to use them well.', 'vitalstack' ); ?></li>
					<li><strong><?php esc_html_e( 'Career guides:', 'vitalstack' ); ?></strong> <?php esc_html_e( 'skills, roadmaps and practical advice for getting into tech.', 'vitalstack' ); ?></li>
				</ul>

				<h2><?php esc_html_e( 'How we create content', 'vitalstack' ); ?></h2>
				<p><?php esc_html_e( 'Every guide is planned around a real question a beginner has. Code examples are run before they are published. We use AI tools to help with research and first drafts, and a person reviews, tests, edits and takes responsibility for every article before it goes live.', 'vitalstack' ); ?></p>
				<p><?php esc_html_e( 'When tools or best practices change, we update the guide and show the new “Updated” date at the top. If you spot a mistake, tell us and we will fix it.', 'vitalstack' ); ?></p>
				<p><a href="<?php echo esc_url( vitalstack_page_url( 'editorial-policy' ) ); ?>"><?php esc_html_e( 'Read the full editorial policy →', 'vitalstack' ); ?></a></p>
			</div>

			<?php if ( $vs_owner ) : ?>
				<div class="about-owner">
					<h2><?php esc_html_e( 'Who writes VitalStack', 'vitalstack' ); ?></h2>
					<div class="author-box">
						<?php echo vitalstack_monogram( $vs_owner->ID, 'lg' ); // phpcs:ignore ?>
						<div>
							<p class="author-box-name"><a href="<?php echo esc_url( get_author_posts_url( $vs_owner->ID ) ); ?>"><?php echo esc_html( $vs_owner->display_name ); ?></a></p>
							<?php if ( $vs_owner->description ) : ?>
								<p class="author-box-bio"><?php echo esc_html( $vs_owner->description ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="cta-box">
				<h2><?php esc_html_e( 'Questions, corrections or topic ideas?', 'vitalstack' ); ?></h2>
				<p><?php esc_html_e( 'We read every message.', 'vitalstack' ); ?></p>
				<a class="btn btn-primary" href="<?php echo esc_url( vitalstack_page_url( 'contact-us' ) ); ?>"><?php esc_html_e( 'Contact us', 'vitalstack' ); ?></a>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
