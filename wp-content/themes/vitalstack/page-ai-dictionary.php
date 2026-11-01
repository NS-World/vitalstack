<?php
/**
 * Template Name: AI Dictionary
 *
 * Gen AI and AI agent words in plain language, grouped and searchable. Every
 * term is normal HTML with its own #anchor, so it can be linked from anywhere.
 *
 * @package VitalStack
 */

get_header();
$vs_terms  = vitalstack_ai_terms();
$vs_groups = vitalstack_ai_term_groups();
$vs_city   = function_exists( 'vitalstack_ai_city_url' ) ? vitalstack_ai_city_url() : home_url( '/ai-city/' );
?>
<section class="aid-hero">
	<div class="wrap aid-hero-inner">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'No jargon. Promise.', 'vitalstack' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<p class="aid-sub"><?php echo esc_html( sprintf( /* translators: %d: number of words */ __( '%d words you keep hearing about ChatGPT, Gen AI and AI agents, each explained in one or two simple sentences with a real-life example. Made for students, working professionals and anyone without a tech background.', 'vitalstack' ), count( $vs_terms ) ) ); ?></p>
			<label class="aid-search"><span class="screen-reader-text"><?php esc_html_e( 'Search the dictionary', 'vitalstack' ); ?></span><span aria-hidden="true">🔎</span><input type="search" placeholder="<?php esc_attr_e( 'Search a word: token, RAG, agent…', 'vitalstack' ); ?>" data-aid-search autocomplete="off"></label>
			<div class="aid-filters" role="group" aria-label="<?php esc_attr_e( 'Filter by topic', 'vitalstack' ); ?>">
				<button type="button" class="aid-filter" aria-pressed="true" data-aid-filter=""><?php esc_html_e( 'All', 'vitalstack' ); ?></button>
				<?php foreach ( $vs_groups as $vs_key => $vs_g ) : ?>
					<button type="button" class="aid-filter" aria-pressed="false" data-aid-filter="<?php echo esc_attr( $vs_key ); ?>"><?php echo esc_html( $vs_g[0] . ' ' . $vs_g[1] ); ?></button>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="aid-nova" data-nova="explain" aria-hidden="true"></div>
	</div>
</section>

<div class="wrap aid-wrap">
	<p class="aid-count" data-aid-count aria-live="polite"></p>
 <?php
	$vs_gi = 0;
	foreach ( $vs_groups as $vs_key => $vs_g ) :
		if ( 2 === $vs_gi || 4 === $vs_gi ) {
			echo function_exists( 'vitalstack_ad_unit' ) ? vitalstack_ad_unit( 'display', 'inline' ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput -- built with escaping
		}
		$vs_gi++;
		?>
		<section class="aid-group" data-aid-group="<?php echo esc_attr( $vs_key ); ?>">
			<h2><span aria-hidden="true"><?php echo esc_html( $vs_g[0] ); ?></span> <?php echo esc_html( $vs_g[1] ); ?></h2>
			<div class="aid-grid">
				<?php
				foreach ( $vs_terms as $vs_slug => $vs_t ) :
					if ( $vs_t['cat'] !== $vs_key ) {
						continue;
					}
					$vs_term = $vs_t['path'] ? get_term_by( 'slug', $vs_t['path'], 'tutorial_category' ) : null;
					?>
					<article class="aid-card" id="<?php echo esc_attr( $vs_slug ); ?>" data-aid-card data-cat="<?php echo esc_attr( $vs_key ); ?>">
						<div class="aid-card-head">
							<span class="aid-emoji" aria-hidden="true"><?php echo esc_html( $vs_t['emoji'] ); ?></span>
							<h3><?php echo esc_html( $vs_t['term'] ); ?></h3>
							<a class="aid-anchor" href="#<?php echo esc_attr( $vs_slug ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: term */ __( 'Link to %s', 'vitalstack' ), $vs_t['term'] ) ); ?>">#</a>
						</div>
						<p class="aid-simple"><?php echo esc_html( $vs_t['simple'] ); ?></p>
						<p class="aid-example"><span aria-hidden="true">💡</span> <?php echo esc_html( $vs_t['example'] ); ?></p>
						<?php if ( $vs_term && ! is_wp_error( $vs_term ) ) : ?>
							<a class="aid-learn" href="<?php echo esc_url( get_term_link( $vs_term ) ); ?>"><?php echo esc_html( sprintf( /* translators: %s: course */ __( 'Learn more in %s', 'vitalstack' ), $vs_term->name ) ); ?> →</a>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endforeach; ?>
	<p class="aid-empty" data-aid-empty hidden><?php esc_html_e( 'No word matches that search yet. Try a shorter word.', 'vitalstack' ); ?></p>

	<aside class="aid-city">
		<div class="aid-city-nova" data-nova="wave" aria-hidden="true"></div>
		<div>
			<h2><?php esc_html_e( 'See these words come alive in 3D', 'vitalstack' ); ?></h2>
			<p><?php esc_html_e( 'Take the AI City tour: a tiny 3D city where tokens ride conveyor belts, agents run between workshops and MCP boats sail to every island.', 'vitalstack' ); ?></p>
			<a class="btn btn-primary" href="<?php echo esc_url( $vs_city ); ?>"><?php esc_html_e( '🏙️ Start the AI City tour', 'vitalstack' ); ?></a>
		</div>
	</aside>
	<?php
	while ( have_posts() ) :
		the_post();
		if ( trim( get_the_content() ) ) :
			?>
			<div class="entry-content aid-more"><?php the_content(); ?></div>
			<?php
		endif;
	endwhile;
	?>
</div>
<?php
get_footer();
