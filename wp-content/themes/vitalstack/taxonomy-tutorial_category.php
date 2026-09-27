<?php
/**
 * A learning path (or a topic inside one).
 *
 * @package VitalStack
 */

get_header();

$vs_term = get_queried_object();
$vs_path = $vs_term;
while ( $vs_path->parent ) {
	$vs_path = get_term( $vs_path->parent, 'tutorial_category' );
}
$vs_lessons = vitalstack_path_lessons( $vs_term );
$vs_style   = vitalstack_term_style( $vs_path, 'tutorials' );
?>

<header class="page-hero">
	<div class="container">
		<?php vitalstack_breadcrumbs(); ?>
		<div class="path-hero" data-path="<?php echo esc_attr( $vs_path->slug ); ?>" data-total="<?php echo count( vitalstack_path_lessons( $vs_path ) ); ?>">
			<span class="path-icon path-icon-lg tone-<?php echo esc_attr( $vs_style[1] ); ?>"><?php echo vitalstack_icon( $vs_style[0], 28 ); // phpcs:ignore ?></span>
			<div>
				<p class="eyebrow"><?php echo $vs_term->parent ? esc_html( $vs_path->name ) : esc_html__( 'Learning path', 'vitalstack' ); ?></p>
				<h1 class="page-title"><?php echo esc_html( $vs_term->name ); ?></h1>
				<p class="page-desc">
					<?php
					if ( $vs_term->description ) {
						echo esc_html( $vs_term->description );
					} else {
						/* translators: 1: number of lessons, 2: path name */
						echo esc_html( sprintf( _n( '%1$d lesson on %2$s, in the order we recommend.', '%1$d lessons on %2$s, in the order we recommend.', count( $vs_lessons ), 'vitalstack' ), count( $vs_lessons ), $vs_term->name ) );
					}
					?>
				</p>
				<?php if ( $vs_lessons ) : ?>
					<div class="path-hero-actions">
						<a class="btn btn-primary path-start" href="<?php echo esc_url( get_permalink( $vs_lessons[0] ) ); ?>" data-start-label="<?php esc_attr_e( 'Start with lesson 1', 'vitalstack' ); ?>" data-resume-label="<?php esc_attr_e( 'Continue where you left off', 'vitalstack' ); ?>"><?php esc_html_e( 'Start with lesson 1', 'vitalstack' ); ?> <?php echo vitalstack_icon( 'arrow', 16 ); // phpcs:ignore ?></a>
						<div class="path-progress path-progress-wide" aria-hidden="true"><span class="path-progress-bar"></span></div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</header>

<section class="section section-tight">
	<div class="container">
		<?php if ( $vs_lessons ) : ?>
			<ol class="lesson-timeline" data-path="<?php echo esc_attr( $vs_path->slug ); ?>">
				<?php foreach ( $vs_lessons as $vs_i => $vs_lesson ) : ?>
					<li data-lesson="<?php echo (int) $vs_lesson; ?>">
						<span class="lesson-num"><span class="lesson-n"><?php echo (int) $vs_i + 1; ?></span><?php echo vitalstack_icon( 'check', 14 ); // phpcs:ignore ?></span>
						<div class="lesson-timeline-body">
							<h2><a href="<?php echo esc_url( get_permalink( $vs_lesson ) ); ?>"><?php echo esc_html( get_the_title( $vs_lesson ) ); ?></a></h2>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $vs_lesson ), 28 ) ); ?></p>
							<span class="lesson-timeline-meta"><?php echo vitalstack_icon( 'clock', 14 ); // phpcs:ignore ?> <?php echo esc_html( sprintf( /* translators: %d minutes */ __( '%d min read', 'vitalstack' ), vitalstack_read_minutes( $vs_lesson ) ) ); ?></span>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		<?php else : ?>
			<?php get_template_part( 'template-parts/none' ); ?>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
