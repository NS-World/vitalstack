<?php
/**
 * Template Name: Tutorials Page
 *
 * Tutorials hub: every learning path with its lessons in order.
 * Also used for the /tutorials/ post type archive.
 *
 * @package VitalStack
 */

get_header();

$vs_paths = vitalstack_learning_paths();
$vs_intro = '';
if ( is_page() ) {
	while ( have_posts() ) {
		the_post();
		$vs_intro = trim( get_the_content() ) ? apply_filters( 'the_content', get_the_content() ) : ''; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
	}
}
?>

<header class="page-hero">
	<div class="container">
		<p class="eyebrow"><?php esc_html_e( 'Tutorials', 'vitalstack' ); ?></p>
		<h1 class="page-title"><?php esc_html_e( 'Learn to code, one lesson at a time', 'vitalstack' ); ?></h1>
		<p class="page-desc"><?php esc_html_e( 'Choose a learning path and follow the lessons in order. Mark each lesson complete as you go. Your progress is saved in this browser.', 'vitalstack' ); ?></p>
		<?php if ( $vs_paths ) : ?>
			<div class="chips">
				<?php foreach ( $vs_paths as $vs_path ) : ?>
					<a class="chip" href="#path-<?php echo esc_attr( $vs_path->slug ); ?>"><?php echo esc_html( $vs_path->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</header>

<section class="section section-tight">
	<div class="container">
		<?php if ( $vs_intro ) : ?>
			<div class="prose prose-intro"><?php echo $vs_intro; // phpcs:ignore ?></div>
		<?php endif; ?>

		<?php
		if ( $vs_paths ) {
			foreach ( $vs_paths as $vs_path ) {
				echo '<div id="path-' . esc_attr( $vs_path->slug ) . '" class="path-anchor"></div>';
				vitalstack_path_block( $vs_path );
			}
		} else {
			get_template_part( 'template-parts/none' );
		}
		?>
	</div>
</section>

<?php
get_footer();
