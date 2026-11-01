<?php
/**
 * Template Name: AI City (3D tour)
 *
 * A scroll tour of a 3D miniature city where every district explains one
 * Gen AI or AI agent idea in simple words. The text below is plain HTML;
 * assets/js/ai-city.js adds the 3D city behind it when the browser supports WebGL.
 *
 * @package VitalStack
 */

get_header();
$vs_stops = vitalstack_ai_city_stops();
$vs_dict  = function_exists( 'vitalstack_ai_dictionary_url' ) ? vitalstack_ai_dictionary_url() : home_url( '/ai-dictionary/' );
$vs_terms = function_exists( 'vitalstack_ai_terms' ) ? vitalstack_ai_terms() : array();
$vs_json  = array();
foreach ( $vs_stops as $vs_s ) {
	$vs_json[] = array(
		'id'   => $vs_s['id'],
		'x'    => $vs_s['x'],
		'z'    => $vs_s['z'],
		'zoom' => $vs_s['zoom'],
		'mood' => $vs_s['mood'],
	);
}
?>
<div class="aic" data-ai-city>
	<div class="aic-canvas" aria-hidden="true" data-aic-canvas>
		<div class="aic-fallback"></div>
	</div>

	<section class="aic-step aic-intro" data-stop="overview">
		<div class="aic-card aic-card-hero">
			<p class="eyebrow"><?php esc_html_e( 'Scroll to explore', 'vitalstack' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<p class="aic-lead"><?php esc_html_e( 'ChatGPT, Claude, Gemini, RAG, agents, MCP… the last two years brought a whole new language. Take a 5-minute tour of a tiny 3D city where every building is one AI idea, explained in plain words with real-life examples. No coding needed.', 'vitalstack' ); ?></p>
			<div class="aic-guide" data-nova="wave"></div>
			<p class="aic-hint"><span aria-hidden="true">↓</span> <?php esc_html_e( 'Scroll down: the camera flies to each district', 'vitalstack' ); ?></p>
			<ol class="aic-map">
				<?php foreach ( $vs_stops as $vs_i => $vs_s ) : ?>
					<li><a href="#<?php echo esc_attr( $vs_s['id'] ); ?>"><span><?php echo (int) $vs_i + 1; ?></span> <?php echo esc_html( $vs_s['place'] ); ?></a></li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>

	<?php foreach ( $vs_stops as $vs_i => $vs_s ) : ?>
		<section class="aic-step <?php echo 0 === $vs_i % 2 ? 'is-left' : 'is-right'; ?>" id="<?php echo esc_attr( $vs_s['id'] ); ?>" data-stop="<?php echo esc_attr( $vs_s['id'] ); ?>">
			<article class="aic-card">
				<p class="aic-place"><span class="aic-num"><?php echo esc_html( sprintf( '%02d', $vs_i + 1 ) ); ?></span> <?php echo esc_html( $vs_s['place'] ); ?></p>
				<h2><?php echo esc_html( $vs_s['title'] ); ?></h2>
				<div class="aic-say">
					<div class="aic-nova" data-nova="<?php echo esc_attr( $vs_s['mood'] ); ?>"></div>
					<p class="aic-simple"><strong><?php esc_html_e( 'In simple words:', 'vitalstack' ); ?></strong> <?php echo esc_html( $vs_s['simple'] ); ?></p>
				</div>
				<p class="aic-example"><span aria-hidden="true">💡</span> <strong><?php esc_html_e( 'Real-life example:', 'vitalstack' ); ?></strong> <?php echo esc_html( $vs_s['example'] ); ?></p>
				<?php if ( $vs_s['words'] ) : ?>
					<p class="aic-words"><span><?php esc_html_e( 'New words:', 'vitalstack' ); ?></span>
						<?php
						foreach ( $vs_s['words'] as $vs_w ) :
							$vs_label = isset( $vs_terms[ $vs_w ] ) ? $vs_terms[ $vs_w ]['term'] : ucwords( str_replace( '-', ' ', $vs_w ) );
							?>
							<a class="aic-chip" href="<?php echo esc_url( $vs_dict . '#' . $vs_w ); ?>"><?php echo esc_html( $vs_label ); ?></a>
						<?php endforeach; ?>
					</p>
				<?php endif; ?>
				<?php
				$vs_term = get_term_by( 'slug', $vs_s['path'], 'tutorial_category' );
				if ( $vs_term && ! is_wp_error( $vs_term ) ) :
					?>
					<a class="aic-learn" href="<?php echo esc_url( get_term_link( $vs_term ) ); ?>"><?php echo esc_html( sprintf( /* translators: %s: course name */ __( 'Learn it step by step: %s', 'vitalstack' ), $vs_term->name ) ); ?> →</a>
				<?php endif; ?>
			</article>
		</section>
	<?php endforeach; ?>

	<section class="aic-step aic-outro" data-stop="overview">
		<div class="aic-card aic-card-hero">
			<div class="aic-guide" data-nova="cheer"></div>
			<h2><?php esc_html_e( 'You just toured the whole AI stack!', 'vitalstack' ); ?></h2>
			<p class="aic-lead"><?php esc_html_e( 'Data → tokens → embeddings → transformer → training → prompts → RAG → tools → agents → memory → MCP → safety → evals → launch. That is the full journey from raw text to a working AI product.', 'vitalstack' ); ?></p>
			<div class="aic-cta">
				<a class="btn btn-primary" href="<?php echo esc_url( $vs_dict ); ?>"><?php esc_html_e( '📖 Open the AI Dictionary', 'vitalstack' ); ?></a>
				<?php if ( function_exists( 'vitalstack_premium_url' ) ) : ?>
					<a class="btn btn-ghost" href="<?php echo esc_url( vitalstack_premium_url( 'gen-ai-developer' ) ); ?>"><?php esc_html_e( '🤖 Gen AI Developer track', 'vitalstack' ); ?></a>
					<a class="btn btn-ghost" href="<?php echo esc_url( vitalstack_premium_url( 'agentic-ai-engineer' ) ); ?>"><?php esc_html_e( '🦾 Agentic AI Engineer track', 'vitalstack' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	$vs_ad = function_exists( 'vitalstack_ad_unit' ) ? vitalstack_ad_unit( 'display', 'end' ) : '';
	if ( $vs_ad ) :
		?>
		<div class="aic-ad"><?php echo $vs_ad; // phpcs:ignore WordPress.Security.EscapeOutput -- built with escaping ?></div>
	<?php endif; ?>
	<script type="application/json" data-aic-stops><?php echo wp_json_encode( $vs_json ); ?></script>
</div>

<?php
while ( have_posts() ) :
	the_post();
	if ( trim( get_the_content() ) ) :
		?>
		<div class="wrap aic-more entry-content"><?php the_content(); ?></div>
		<?php
	endif;
endwhile;
get_footer();
