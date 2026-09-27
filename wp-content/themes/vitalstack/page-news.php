<?php
/**
 * Template Name: News Page
 *
 * Displays all "news" custom post type entries in a filterable grid.
 *
 * HOW TO USE:
 *   1. Pages → Add New → Title "Tech News" (or any title)
 *   2. Page Attributes → Template → "News Page"
 *   3. Publish — your news listing is live at /tech-news (or your slug)
 *
 * LAYOUT:
 *   1. Page hero (title, description, stats)
 *   2. Category filter tab bar (news_category taxonomy)
 *   3. Featured/latest news — full-width hero card
 *   4. Responsive 3-column news card grid
 *   5. Standard WP pagination
 *
 * CUSTOM FIELDS (optional, per news post):
 *   news_source  — e.g. "TechCrunch", "Reuters" — shown as a source badge
 *
 * @package VitalStack
 */

get_header();

/* ─── Pagination & filter ───────────────────────────────────────────────── */
$paged       = max( 1, get_query_var( 'paged' ) ?: ( get_query_var( 'page' ) ?: 1 ) );
$active_term = isset( $_GET['news_cat'] ) ? absint( $_GET['news_cat'] ) : 0;

/* ─── Main Query ────────────────────────────────────────────────────────── */
$news_args = array(
    'post_type'      => 'news',
    'post_status'    => 'publish',
    'posts_per_page' => 9,   // 3-column grid — multiples of 3 look cleanest
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
);
if ( $active_term ) {
    $news_args['tax_query'] = array(
        array(
            'taxonomy' => 'news_category',
            'field'    => 'term_id',
            'terms'    => $active_term,
        ),
    );
}
$news_query = new WP_Query( $news_args );

/* ─── All news categories for tab bar ──────────────────────────────────── */
$news_cats = get_terms( array(
    'taxonomy'   => 'news_category',
    'hide_empty' => true,
) );

/* ─── Total count ───────────────────────────────────────────────────────── */
$total_news = wp_count_posts( 'news' )->publish;
?>

<main id="main" class="site-main news-page-tpl">

<!-- ══ 1. HERO ══════════════════════════════════════════════════════════════ -->
<section class="page-hero news-hero" aria-label="<?php esc_attr_e( 'News header', 'vitalstack' ); ?>">
  <div class="page-header-grid" aria-hidden="true"></div>
  <div class="container ph-inner">
    <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'vitalstack' ); ?>">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
      <span>/</span>
      <span>News</span>
    </nav>
    <h1>News</h1>
    <p class="news-hero-desc">
      Stay ahead of the curve - the latest tech news, industry updates, and innovations, curated for you.
    </p>
  </div>
</section>




<!-- ══ 2. FILTER TAB BAR ════════════════════════════════════════════════════ -->
<?php 
$news_cats = get_terms( array(
    'taxonomy'   => 'news_category',
    'hide_empty' => true,
    'exclude'    => array( get_term_by( 'name', 'News', 'news_category' )->term_id ) // Exclude "News" category
) ); 

if ( ! empty( $news_cats ) && ! is_wp_error( $news_cats ) ) : 
?>
<section class="filter-bar-wrap news-filter-bar" aria-label="<?php esc_attr_e( 'Filter news by category', 'vitalstack' ); ?>">
  <div class="container">
    <nav class="filter-bar" role="tablist">
      <a href="<?php echo esc_url( get_permalink() ); ?>"
         class="filter-tab<?php echo ! $active_term ? ' is-active' : ''; ?>"
         role="tab"
         aria-selected="<?php echo ! $active_term ? 'true' : 'false'; ?>">
        <?php esc_html_e( 'All', 'vitalstack' ); ?>
      </a>
      
      <?php foreach ( $news_cats as $ncat ) : 
        $tab_url = add_query_arg( 'news_cat', $ncat->term_id, get_permalink() );
        $is_active = ( $active_term === (int) $ncat->term_id );
      ?>
      <a href="<?php echo esc_url( $tab_url ); ?>"
         class="filter-tab<?php echo $is_active ? ' is-active' : ''; ?>"
         role="tab"
         aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>">
        <?php echo esc_html( $ncat->name ); ?>
        <span class="filter-tab-count"><?php echo (int) $ncat->count; ?></span>
      </a>
      <?php endforeach; ?>
    </nav>
  </div>
</section>
<?php endif; ?>

<!-- ══ 3 + 4. FEATURED CARD + GRID ═════════════════════════════════════════ -->
<section class="news-content-section">
  <div class="container">

    <?php if ( ! $news_query->have_posts() ) : ?>
      <div class="no-results">
        <h2><?php esc_html_e( 'No news found', 'vitalstack' ); ?></h2>
        <p><?php esc_html_e( 'Check back soon — or try a different category.', 'vitalstack' ); ?></p>
      </div>

    <?php else :
      $first_post  = true;
      $grid_opened = false;
    ?>

      <?php
      /*
       * FIX: When a category filter is active every post goes straight into
       * the grid (no featured card).  Open the wrapper BEFORE the loop so the
       * grid is always present.
       */
      if ( $active_term ) :
      ?>
        <div class="news-grid">
        <?php $grid_opened = true; ?>
      <?php endif; ?>

      <?php while ( $news_query->have_posts() ) : $news_query->the_post(); ?>

        <?php if ( $first_post && $paged === 1 && ! $active_term ) :
          // ── Featured (first) news item — full-width hero card ──────────
          $first_post  = false;
          $f_id        = get_the_ID();
          $f_permalink = get_permalink();
          $f_terms     = get_the_terms( $f_id, 'news_category' );
          $f_cat       = ( $f_terms && ! is_wp_error( $f_terms ) ) ? $f_terms[0]->name : 'News';
          $f_source    = get_post_meta( $f_id, 'news_source', true );
        ?>
          <div class="news-featured-wrap">
            <article class="news-featured-card <?php echo has_post_thumbnail() ? 'has-thumb' : 'no-thumb'; ?>">
              <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php echo esc_url( $f_permalink ); ?>" class="news-featured-img-link" tabindex="-1" aria-hidden="true">
                  <?php the_post_thumbnail( 'vitalstack-hero', array( 'class' => 'news-featured-img', 'alt' => esc_attr( get_the_title() ) ) ); ?>
                </a>
              <?php endif; ?>
              <div class="news-featured-body">
                <div class="news-card-meta">
                  <span class="tag tag-news"><?php echo esc_html( $f_cat ); ?></span>
                  <span class="news-featured-badge"><?php esc_html_e( 'Latest', 'vitalstack' ); ?></span>
                  <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></time>
                  <?php if ( $f_source ) : ?>
                    <span class="news-source">· <?php echo esc_html( $f_source ); ?></span>
                  <?php endif; ?>
                </div>
                <h2 class="news-featured-title">
                  <a href="<?php echo esc_url( $f_permalink ); ?>"><?php the_title(); ?></a>
                </h2>
                <p class="news-featured-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                <a class="btn btn-primary" href="<?php echo esc_url( $f_permalink ); ?>">
                  <?php esc_html_e( 'Read Full Story', 'vitalstack' ); ?> →
                </a>
              </div>
            </article>
          </div>

          <!-- Grid for remaining (non-featured) posts -->
          <div class="news-grid">
          <?php $grid_opened = true; ?>

        <?php else :
          // ── Standard grid card ──────────────────────────────────────────
          $first_post = false;
          vitalstack_news_card( get_the_ID() );
        endif; ?>

      <?php endwhile; ?>

      <?php if ( $grid_opened ) : ?>
        </div><!-- /.news-grid -->
      <?php endif; ?>

    <?php endif; // have_posts ?>

    <?php wp_reset_postdata(); ?>

    <!-- ══ 5. PAGINATION ════════════════════════════════════════════════════ -->
    <?php if ( $news_query->max_num_pages > 1 ) : ?>
    <nav class="pagination-wrap" aria-label="<?php esc_attr_e( 'News pagination', 'vitalstack' ); ?>">
      <?php
      $big = 999999;
      echo paginate_links( array(
          'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
          'format'    => '?paged=%#%',
          'current'   => $paged,
          'total'     => $news_query->max_num_pages,
          'prev_text' => '← ' . __( 'Previous', 'vitalstack' ),
          'next_text' => __( 'Next', 'vitalstack' ) . ' →',
          'add_args'  => $active_term ? array( 'news_cat' => $active_term ) : false,
      ) );
      ?>
    </nav>
    <?php endif; ?>

  </div><!-- /.container -->
</section>

<?php vitalstack_newsletter_section(); ?>

</main><!-- /#main -->

<?php get_footer(); ?>
