<?php
/**
 * Template Name: Mock Test
 *
 * A timed test for one learning path. The questions come from the path's
 * lesson quizzes (data/quizzes/); assets/js/mocktest.js runs the test.
 * The intro text comes from the editor, or from the theme while it's empty.
 *
 * @package VitalStack
 */

get_header();
while ( have_posts() ) :
	the_post();
	$vs_pages   = vitalstack_site_pages();
	$vs_slug    = get_post_field( 'post_name' );
	$vs_meta    = isset( $vs_pages[ $vs_slug ] ) ? $vs_pages[ $vs_slug ] : array( 'icon' => '📝', 'excerpt' => '', 'path' => '' );
	$vs_path    = isset( $vs_meta['path'] ) ? $vs_meta['path'] : '';
	$vs_data    = vitalstack_mock_data( $vs_path );
	$vs_term    = vitalstack_path_term( $vs_path );
	$vs_hub     = vitalstack_site_page_url( 'mock-tests' );
	$vs_sheet   = vitalstack_path_site_page_url( 'cheat-sheets', $vs_path );
	$vs_samples = vitalstack_mock_samples( $vs_path );
	?>
	<div class="wrap mock-wrap">
		<?php vitalstack_breadcrumbs(); ?>
		<header class="sheet-head">
			<span class="sheet-icon" aria-hidden="true"><?php echo esc_html( $vs_meta['icon'] ); ?></span>
			<div>
				<h1 class="sheet-title"><?php the_title(); ?></h1>
				<?php if ( $vs_meta['excerpt'] ) : ?>
					<p class="sheet-sub"><?php echo esc_html( $vs_meta['excerpt'] ); ?></p>
				<?php endif; ?>
			</div>
		</header>

		<?php if ( $vs_data['count'] ) : ?>
			<section class="mock" id="mock-test" aria-label="<?php esc_attr_e( 'Mock test', 'vitalstack' ); ?>">
				<div class="mock-start">
					<div class="mock-start-bot" aria-hidden="true"><?php echo vitalstack_mascot( 'think', 96, '', 'bugsy' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></div>
					<ul class="mock-facts">
						<li><b><?php echo (int) $vs_data['count']; ?></b> <?php esc_html_e( 'random questions', 'vitalstack' ); ?></li>
						<li><b><?php echo (int) $vs_data['minutes']; ?></b> <?php esc_html_e( 'minutes', 'vitalstack' ); ?></li>
						<li><b><?php echo (int) count( $vs_data['questions'] ); ?></b> <?php esc_html_e( 'in the question bank', 'vitalstack' ); ?></li>
					</ul>
					<p class="mock-best" hidden></p>
					<button type="button" class="btn btn-primary mock-go" data-mock-start><?php esc_html_e( 'Start the test', 'vitalstack' ); ?> ❯</button>
					<p class="mock-note"><?php esc_html_e( 'No sign-up needed. Answers and explanations are shown at the end.', 'vitalstack' ); ?></p>
				</div>
				<noscript><p><?php esc_html_e( 'The test needs JavaScript. You can still read the sample questions below.', 'vitalstack' ); ?></p></noscript>
			</section>
			<script type="application/json" id="mock-data"><?php echo wp_json_encode( $vs_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP ); ?></script>
		<?php endif; ?>

		<div class="mock-about">
			<div class="prose">
				<h2><?php esc_html_e( 'About this test', 'vitalstack' ); ?></h2>
				<?php echo vitalstack_site_page_content(); // phpcs:ignore WordPress.Security.EscapeOutput -- filtered content ?>
				<p><?php esc_html_e( 'Each attempt picks new questions at random from the question bank. You can go back and change answers until you finish or the timer runs out. At the end you see your score, the right answer and a short explanation for every question, and the lessons to revise for the ones you missed.', 'vitalstack' ); ?></p>

				<?php if ( $vs_data['lessons'] ) : ?>
					<h2><?php esc_html_e( 'Topics covered', 'vitalstack' ); ?></h2>
					<ol class="mock-topics">
						<?php foreach ( $vs_data['lessons'] as $vs_lesson ) : ?>
							<li><a href="<?php echo esc_url( $vs_lesson['u'] ); ?>"><?php echo esc_html( $vs_lesson['t'] ); ?></a></li>
						<?php endforeach; ?>
					</ol>
				<?php endif; ?>

				<?php if ( $vs_samples ) : ?>
					<h2><?php esc_html_e( 'Sample questions with answers', 'vitalstack' ); ?></h2>
					<div class="mock-samples">
						<?php foreach ( $vs_samples as $vs_i => $vs_q ) : ?>
							<div class="mock-sample">
								<p class="mock-sample-q"><b><?php echo (int) $vs_i + 1; ?>.</b> <?php echo vitalstack_quiz_text( $vs_q['q'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
								<ol class="mock-sample-o" type="A">
									<?php foreach ( $vs_q['o'] as $vs_opt ) : ?>
										<li><?php echo vitalstack_quiz_text( $vs_opt ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></li>
									<?php endforeach; ?>
								</ol>
								<details>
									<summary><?php esc_html_e( 'Show answer', 'vitalstack' ); ?></summary>
									<p><b><?php echo esc_html( chr( 65 + (int) $vs_q['a'] ) ); ?>.</b> <?php echo vitalstack_quiz_text( $vs_q['o'][ $vs_q['a'] ] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?><?php if ( ! empty( $vs_q['e'] ) ) : ?> — <?php echo vitalstack_quiz_text( $vs_q['e'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?><?php endif; ?></p>
								</details>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<aside class="mock-side">
				<?php if ( $vs_term ) : ?>
					<a class="mock-side-link" href="<?php echo esc_url( get_term_link( $vs_term ) ); ?>">📘 <?php echo esc_html( sprintf( /* translators: %s: subject */ __( '%s lessons', 'vitalstack' ), $vs_term->name ) ); ?></a>
				<?php endif; ?>
				<?php if ( $vs_sheet ) : ?>
					<a class="mock-side-link" href="<?php echo esc_url( $vs_sheet ); ?>">📄 <?php esc_html_e( 'Cheat sheet', 'vitalstack' ); ?></a>
				<?php endif; ?>
				<?php if ( $vs_hub ) : ?>
					<a class="mock-side-link" href="<?php echo esc_url( $vs_hub ); ?>">📝 <?php esc_html_e( 'All mock tests', 'vitalstack' ); ?></a>
				<?php endif; ?>
			</aside>
		</div>
	</div>
	<?php
endwhile;
get_footer();
