<?php
/**
 * Template Name: Tutorials Page
 *
 * Displays all "tutorials" CPT entries in a filterable grid.
 * Each card shows a video thumbnail + play icon + duration badge.
 *
 * HOW TO USE:
 *   1. Pages → Add New → Title "Tutorials" (or any title)
 *   2. Page Attributes → Template → "Tutorials Page"
 *   3. Publish — your tutorials listing is live at /tutorials
 *
 * LAYOUT:
 *   1. Page hero (title, description, stats)
 *   2. Category filter tabs + difficulty filter pills
 *   3. Featured (latest) tutorial — wide hero card with live embed
 *   4. Responsive 3-column tutorial card grid
 *   5. Standard WP pagination
 *
 * VIDEO TYPES SUPPORTED (set per tutorial via "Video Settings" meta box):
 *   - YouTube   — facade click-to-play (no cookie until clicked)
 *   - Vimeo     — oEmbed iframe
 *   - Self-hosted — native <video> with poster image
 *   - Other oEmbed — any WordPress-supported oEmbed provider
 *
 * @package VitalStack
 */

get_header();

/* ─── Pagination & filters ──────────────────────────────────────────────── */
$paged       = max( 1, get_query_var( 'paged' ) ?: ( get_query_var( 'page' ) ?: 1 ) );
$active_cat  = isset( $_GET['tut_cat'] )  ? absint( $_GET['tut_cat'] )  : 0;
$active_diff = isset( $_GET['tut_diff'] ) ? absint( $_GET['tut_diff'] ) : 0;

/* ─── Main Query ────────────────────────────────────────────────────────── */
$tut_args = array(
    'post_type'      => 'tutorials',
    'post_status'    => 'publish',
    'posts_per_page' => 10,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
);
$tax_query = array( 'relation' => 'AND' );
if ( $active_cat ) {
    $tax_query[] = array(
        'taxonomy' => 'tutorial_category',
        'field'    => 'term_id',
        'terms'    => $active_cat,
    );
}
if ( $active_diff ) {
    $tax_query[] = array(
        'taxonomy' => 'tutorial_difficulty',
        'field'    => 'term_id',
        'terms'    => $active_diff,
    );
}
if ( count( $tax_query ) > 1 ) {
    $tut_args['tax_query'] = $tax_query;
}
$tut_query = new WP_Query( $tut_args );

/* ─── Taxonomy terms for filters ────────────────────────────────────────── */
$tut_cats  = get_terms( array( 'taxonomy' => 'tutorial_category',  'hide_empty' => true ) );
$tut_diffs = get_terms( array( 'taxonomy' => 'tutorial_difficulty', 'hide_empty' => true ) );
$total_tuts = wp_count_posts( 'tutorials' )->publish;
?>

<main id="main" class="site-main vaibhav tutorials-page-tpl">

<!-- ══ 1. HERO ══════════════════════════════════════════════════════════════ -->
<section class="page-hero tutorials-hero" aria-label="<?php esc_attr_e( 'Tutorials header', 'vitalstack' ); ?>">
  <div class="page-header-grid" aria-hidden="true"></div>
  <div class="container ph-inner">

    <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'vitalstack' ); ?>">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'vitalstack' ); ?></a>
      <span>/</span>
      <span><?php the_title(); ?></span>
    </nav>


    <h1><?php the_title(); ?></h1>
    <p class="tutorials-hero-desc">
      <?php
      $desc = get_the_content();
      if ( $desc ) {
          echo wp_kses_post( wpautop( $desc ) );
      } else {
          esc_html_e( 'Learn step by step — video tutorials covering everything from beginner basics to advanced techniques.', 'vitalstack' );
      }
      ?>
    </p>



  </div>
</section>

<!-- ══ 2. FILTER BAR ════════════════════════════════════════════════════════ -->
<section class="filter-bar-wrap tutorials-filter-bar" aria-label="<?php esc_attr_e( 'Filter tutorials', 'vitalstack' ); ?>">
  <div class="container">

    <?php if ( ! empty( $tut_cats ) && ! is_wp_error( $tut_cats ) ) : ?>
    <nav class="filter-bar tut-cat-filter" role="tablist" aria-label="<?php esc_attr_e( 'Filter by topic', 'vitalstack' ); ?>">
      <a href="<?php echo esc_url( get_permalink() ); ?>"
         class="filter-tab<?php echo ! $active_cat ? ' is-active' : ''; ?>"
         role="tab" aria-selected="<?php echo ! $active_cat ? 'true' : 'false'; ?>">
        <?php esc_html_e( 'All Topics', 'vitalstack' ); ?>
      </a>
      <?php foreach ( $tut_cats as $tc ) :
        $tab_url   = add_query_arg( array( 'tut_cat' => $tc->term_id, 'tut_diff' => $active_diff ?: '' ), get_permalink() );
        $is_active = ( $active_cat === (int) $tc->term_id );
      ?>
      <a href="<?php echo esc_url( $tab_url ); ?>"
         class="filter-tab<?php echo $is_active ? ' is-active' : ''; ?>"
         role="tab" aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>">
        <?php echo esc_html( $tc->name ); ?>
        <span class="filter-tab-count"><?php echo (int) $tc->count; ?></span>
      </a>
      <?php endforeach; ?>
    </nav>
    <?php endif; ?>

    <?php if ( ! empty( $tut_diffs ) && ! is_wp_error( $tut_diffs ) ) : ?>
    <div class="tut-diff-pills" aria-label="<?php esc_attr_e( 'Filter by difficulty', 'vitalstack' ); ?>">
      <span class="tut-diff-label"><?php esc_html_e( 'Level:', 'vitalstack' ); ?></span>
      <a href="<?php echo esc_url( add_query_arg( array( 'tut_cat' => $active_cat ?: '', 'tut_diff' => '' ), get_permalink() ) ); ?>"
         class="tut-diff-pill<?php echo ! $active_diff ? ' is-active' : ''; ?>">
        <?php esc_html_e( 'All', 'vitalstack' ); ?>
      </a>
      <?php foreach ( $tut_diffs as $td ) :
        $pill_url  = add_query_arg( array( 'tut_cat' => $active_cat ?: '', 'tut_diff' => $td->term_id ), get_permalink() );
        $is_active = ( $active_diff === (int) $td->term_id );
      ?>
      <a href="<?php echo esc_url( $pill_url ); ?>"
         class="tut-diff-pill tut-diff-<?php echo esc_attr( sanitize_html_class( $td->slug ) ); ?><?php echo $is_active ? ' is-active' : ''; ?>">
        <?php echo esc_html( $td->name ); ?>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </div>
</section>

<!-- ══ 3 + 4. FEATURED + GRID ═══════════════════════════════════════════════ -->
<section class="tut-content-section">
  <div class="container">

    <?php if ( ! $tut_query->have_posts() ) : ?>
      <div class="no-results">
        <h2><?php esc_html_e( 'No tutorials found', 'vitalstack' ); ?></h2>
        <p><?php esc_html_e( 'Try a different topic or difficulty level.', 'vitalstack' ); ?></p>
        <a class="btn btn-primary" href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'View All Tutorials', 'vitalstack' ); ?></a>
      </div>

    <?php else :
      $first       = true;
      $grid_opened = false;
      $is_filtered = ( $active_cat || $active_diff );
    ?>

      <?php
      /*
       * FIX: When either category or difficulty filter is active, no featured
       * card is shown and every post goes straight to the grid.  Open the
       * wrapper BEFORE the loop to guarantee it always exists.
       */
      if ( $is_filtered ) :
      ?>
        <div class="tut-grid">
        <?php $grid_opened = true; ?>
      <?php endif; ?>

      <?php while ( $tut_query->have_posts() ) : $tut_query->the_post(); ?>

        <?php if ( $first && $paged === 1 && ! $is_filtered ) :
          // ── Featured tutorial — wide hero card ─────────────────────────
          $first    = false;
          $f_id     = get_the_ID();
          $f_link   = get_permalink();
          $f_cats   = get_the_terms( $f_id, 'tutorial_category' );
          $f_cat    = ( $f_cats && ! is_wp_error( $f_cats ) ) ? $f_cats[0]->name : 'Tutorial';
          $f_diffs  = get_the_terms( $f_id, 'tutorial_difficulty' );
          $f_diff   = ( $f_diffs && ! is_wp_error( $f_diffs ) ) ? $f_diffs[0]->name : '';
          $f_dur    = get_post_meta( $f_id, 'tutorial_duration', true );
          $f_type   = get_post_meta( $f_id, 'tutorial_video_type', true ) ?: 'youtube';
        ?>
          <div class="tut-featured-wrap">
            <article class="tut-featured-card">
              <!-- Video embed left column -->
              <div class="tut-featured-video">
                <?php echo vitalstack_tutorial_video( $f_id, 'full' ); ?>
              </div>

              <!-- Info right column -->
              <div class="tut-featured-body">
                <div class="tut-card-meta">
                  <span class="tag tag-tutorial"><?php echo esc_html( $f_cat ); ?></span>

                </div>
                <h2 class="tut-featured-title">
                  <a href="<?php echo esc_url( $f_link ); ?>"><?php the_title(); ?></a>
                </h2>
                <p class="tut-featured-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                <div class="tut-featured-meta-row">
                  <?php if ( $f_dur ) : ?>
                    <span class="tut-meta-item">⏱ <?php echo esc_html( $f_dur ); ?></span>
                  <?php endif; ?>
                  <span class="tut-meta-item">
                    <?php
                    $type_labels = array( 'youtube' => '▶ YouTube', 'vimeo' => '🎞 Vimeo', 'self_hosted' => '📁 Video', 'oembed' => '🔗 Video' );
                    echo esc_html( $type_labels[ $f_type ] ?? '▶ Video' );
                    ?>
                  </span>
                  <span class="tut-meta-item">📅 <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></span>
                </div>
                <a class="btn btn-primary" href="<?php echo esc_url( $f_link ); ?>">
                  <?php esc_html_e( 'Watch Tutorial', 'vitalstack' ); ?> →
                </a>
              </div>
            </article>
          </div>

          <!-- Open grid for remaining tutorials -->
          <div class="tut-grid">
          <?php $grid_opened = true; ?>

        <?php else :
          // ── Standard grid card ──────────────────────────────────────────
          $first = false;
          vitalstack_tutorial_card( get_the_ID() );
        endif; ?>

      <?php endwhile; ?>

      <?php if ( $grid_opened ) : ?>
        </div><!-- /.tut-grid -->
      <?php endif; ?>

    <?php endif; // have_posts ?>

    <?php wp_reset_postdata(); ?>

    <!-- ══ 5. PAGINATION ════════════════════════════════════════════════════ -->
    <?php if ( $tut_query->max_num_pages > 1 ) : ?>
    <nav class="pagination-wrap" aria-label="<?php esc_attr_e( 'Tutorials pagination', 'vitalstack' ); ?>">
      <?php
      $big      = 999999;
      $add_args = array_filter( array(
          'tut_cat'  => $active_cat  ?: '',
          'tut_diff' => $active_diff ?: '',
      ) );
      echo paginate_links( array(
          'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
          'format'    => '?paged=%#%',
          'current'   => $paged,
          'total'     => $tut_query->max_num_pages,
          'prev_text' => '← ' . __( 'Previous', 'vitalstack' ),
          'next_text' => __( 'Next', 'vitalstack' ) . ' →',
          'add_args'  => $add_args ?: false,
      ) );
      ?>
    </nav>
    <?php endif; ?>

  </div><!-- /.container -->
</section>

<?php vitalstack_newsletter_section(); ?>

</main><!-- /#main -->

<?php get_footer(); ?>
