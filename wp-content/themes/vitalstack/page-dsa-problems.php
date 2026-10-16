<?php
/**
 * Template Name: Interview Problems
 *
 * Filterable list of the lessons in the "dsa-problems" learning path, with
 * pattern, difficulty, companies and solved status.
 *
 * @package VitalStack
 */

get_header();

$vs_term     = get_term_by( 'slug', 'dsa-problems', 'tutorial_category' );
$vs_problems = $vs_term ? vitalstack_path_lessons( $vs_term ) : array();
_prime_post_caches( $vs_problems, false, true );
$vs_patterns = array();
$vs_companies = array();
foreach ( $vs_problems as $vs_id ) {
	$vs_pattern = get_post_meta( $vs_id, '_vs_pattern', true );
	if ( $vs_pattern ) {
		$vs_patterns[ $vs_pattern ] = ( $vs_patterns[ $vs_pattern ] ?? 0 ) + 1;
	}
	foreach ( array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $vs_id, '_vs_companies', true ) ) ) ) as $vs_c ) {
		$vs_companies[ $vs_c ] = ( $vs_companies[ $vs_c ] ?? 0 ) + 1;
	}
}
ksort( $vs_patterns );
arsort( $vs_companies );
$vs_dsa = get_term_by( 'slug', 'dsa', 'tutorial_category' );
?>

<section class="problems-hero">
	<div class="wrap problems-hero-inner">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Coding interview prep', 'vitalstack' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<p class="problems-hero-sub"><?php esc_html_e( 'Classic problems asked by big tech companies, each solved the easy way: understand the problem, try brute force, spot the pattern, then write the clean solution in Python.', 'vitalstack' ); ?></p>
			<div class="problems-stats" data-path="dsa-problems" data-total="<?php echo count( $vs_problems ); ?>">
				<span><b><?php echo count( $vs_problems ); ?></b> <?php esc_html_e( 'problems', 'vitalstack' ); ?></span>
				<span><b><?php echo count( $vs_patterns ); ?></b> <?php esc_html_e( 'patterns', 'vitalstack' ); ?></span>
				<span class="problems-progress"><span class="path-progress"><span class="path-progress-bar"></span></span><span class="path-block-done" hidden></span></span>
			</div>
			<?php if ( $vs_dsa && $vs_dsa->count ) : ?>
				<p class="problems-new"><?php esc_html_e( 'New to DSA?', 'vitalstack' ); ?> <a href="<?php echo esc_url( get_term_link( $vs_dsa ) ); ?>"><?php esc_html_e( 'Learn the patterns first', 'vitalstack' ); ?> ❯</a></p>
			<?php endif; ?>
		</div>
		<div class="problems-hero-bot"><?php echo vitalstack_mascot( 'think', 140 ); // phpcs:ignore WordPress.Security.EscapeOutput -- fixed markup ?></div>
	</div>
</section>

<div class="wrap problems-wrap" data-problems>
	<?php if ( ! $vs_problems ) : ?>
		<p class="muted"><?php esc_html_e( 'Problems appear here once they are published (Tutorials → Learning Path "Interview Problems").', 'vitalstack' ); ?></p>
	<?php else : ?>
		<div class="problems-filters">
			<input type="search" class="problems-search" placeholder="<?php esc_attr_e( 'Search problems…', 'vitalstack' ); ?>" aria-label="<?php esc_attr_e( 'Search problems', 'vitalstack' ); ?>" data-filter-text>
			<div class="problems-levels" role="group" aria-label="<?php esc_attr_e( 'Difficulty', 'vitalstack' ); ?>">
				<?php foreach ( array( '' => __( 'All', 'vitalstack' ), 'Easy' => __( 'Easy', 'vitalstack' ), 'Medium' => __( 'Medium', 'vitalstack' ), 'Hard' => __( 'Hard', 'vitalstack' ) ) as $vs_v => $vs_l ) : ?>
					<button type="button" class="chip<?php echo '' === $vs_v ? ' is-active' : ''; ?>" data-filter-level="<?php echo esc_attr( $vs_v ); ?>"><?php echo esc_html( $vs_l ); ?></button>
				<?php endforeach; ?>
			</div>
			<select data-filter-pattern aria-label="<?php esc_attr_e( 'Pattern', 'vitalstack' ); ?>">
				<option value=""><?php esc_html_e( 'All patterns', 'vitalstack' ); ?></option>
				<?php foreach ( $vs_patterns as $vs_p => $vs_n ) : ?>
					<option value="<?php echo esc_attr( $vs_p ); ?>"><?php echo esc_html( $vs_p . ' (' . $vs_n . ')' ); ?></option>
				<?php endforeach; ?>
			</select>
			<select data-filter-company aria-label="<?php esc_attr_e( 'Company', 'vitalstack' ); ?>">
				<option value=""><?php esc_html_e( 'All companies', 'vitalstack' ); ?></option>
				<?php foreach ( $vs_companies as $vs_c => $vs_n ) : ?>
					<option value="<?php echo esc_attr( $vs_c ); ?>"><?php echo esc_html( $vs_c . ' (' . $vs_n . ')' ); ?></option>
				<?php endforeach; ?>
			</select>
			<label class="problems-hide"><input type="checkbox" data-filter-hide> <?php esc_html_e( 'Hide solved', 'vitalstack' ); ?></label>
			<button type="button" class="btn btn-soft btn-sm" data-random-problem>🎲 <?php esc_html_e( 'Random problem', 'vitalstack' ); ?></button>
		</div>

		<div class="problems-table-wrap">
			<table class="problems-table">
				<thead>
					<tr>
						<th scope="col" class="col-status"><span class="screen-reader-text"><?php esc_html_e( 'Solved', 'vitalstack' ); ?></span></th>
						<th scope="col">#</th>
						<th scope="col"><?php esc_html_e( 'Problem', 'vitalstack' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Pattern', 'vitalstack' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Difficulty', 'vitalstack' ); ?></th>
						<th scope="col" class="col-companies"><?php esc_html_e( 'Often asked at', 'vitalstack' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ( $vs_problems as $vs_n => $vs_id ) :
						$vs_level   = get_post_meta( $vs_id, '_vs_level', true );
						$vs_pattern = get_post_meta( $vs_id, '_vs_pattern', true );
						$vs_comp    = (string) get_post_meta( $vs_id, '_vs_companies', true );
						?>
						<tr data-lesson="<?php echo (int) $vs_id; ?>" data-level="<?php echo esc_attr( $vs_level ); ?>" data-pattern="<?php echo esc_attr( $vs_pattern ); ?>" data-companies="<?php echo esc_attr( $vs_comp ); ?>" data-title="<?php echo esc_attr( strtolower( get_the_title( $vs_id ) ) ); ?>">
							<td class="col-status"><span class="lesson-num"><?php echo vitalstack_icon( 'check', 12 ); // phpcs:ignore ?></span></td>
							<td class="muted"><?php echo (int) $vs_n + 1; ?></td>
							<td><a href="<?php echo esc_url( get_permalink( $vs_id ) ); ?>"><?php echo esc_html( get_the_title( $vs_id ) ); ?></a></td>
							<td><?php if ( $vs_pattern ) : ?><span class="pattern-chip"><?php echo esc_html( $vs_pattern ); ?></span><?php endif; ?></td>
							<td><?php if ( $vs_level ) : ?><span class="level-badge level-<?php echo esc_attr( strtolower( $vs_level ) ); ?>"><?php echo esc_html( $vs_level ); ?></span><?php endif; ?></td>
							<td class="col-companies"><?php echo esc_html( $vs_comp ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<p class="problems-empty muted" hidden><?php esc_html_e( 'No problems match these filters.', 'vitalstack' ); ?></p>
		</div>
		<p class="problems-note muted"><?php esc_html_e( 'Company names show where a problem is commonly reported in interviews. Questions vary by team and year, so learn the pattern rather than memorising answers.', 'vitalstack' ); ?></p>
	<?php endif; ?>
</div>

<?php
get_footer();
