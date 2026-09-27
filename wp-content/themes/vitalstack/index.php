<?php
/**
 * index.php — Main Template File (WordPress fallback)
 *
 * WordPress uses this when no more-specific template exists.
 * When a static front page IS assigned (Settings → Reading),
 * WordPress uses front-page.php instead, so this file serves
 * as the blog listing fallback.
 *
 * For a dedicated blog listing page, create blog.php (Point 4).
 *
 * @package VitalStack
 */

get_header();
?>

<main id="main" class="site-main">

  <!-- Page hero banner -->
  <div class="page-hero">
    <div class="container">
      <p class="section-eyebrow"><?php esc_html_e( 'Our Articles', 'vitalstack' ); ?></p>
      <h1><?php esc_html_e( 'All Articles', 'vitalstack' ); ?></h1>
      <p><?php esc_html_e( 'Explore our complete library of AI and health insights — deep-dives written for curious minds.', 'vitalstack' ); ?></p>
    </div>
  </div>

  <div class="container content-sidebar-wrap" style="padding-top:48px;padding-bottom:80px;">

    <!-- Main content column -->
    <div id="primary" class="content-area">

      <?php if ( have_posts() ) : ?>

        <!-- Filter pills placeholder (use FacetWP or Filter Everything plugin) -->
        <div class="filter-row">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
             class="filter-btn <?php echo ! is_category() ? 'active' : ''; ?>">
            <?php esc_html_e( 'All Posts', 'vitalstack' ); ?>
          </a>
          <?php
          $main_cats = get_categories( array( 'hide_empty' => true, 'number' => 10 ) );
          foreach ( $main_cats as $cat ) : ?>
            <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
               class="filter-btn <?php echo is_category( $cat->term_id ) ? 'active' : ''; ?>">
              <?php echo esc_html( $cat->name ); ?>
            </a>
          <?php endforeach; ?>
        </div>

        <div class="cards-grid" style="margin-top:28px;">
          <?php while ( have_posts() ) : the_post(); ?>
            <?php vitalstack_article_card( get_the_ID() ); ?>
          <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrap">
          <?php
          the_posts_pagination( array(
            'mid_size'  => 2,
            'prev_text' => '← ' . __( 'Prev', 'vitalstack' ),
            'next_text' => __( 'Next', 'vitalstack' ) . ' →',
            'class'     => 'pagination',
          ) );
          ?>
        </div>

      <?php else : ?>

        <div class="no-posts-notice">
          <h2><?php esc_html_e( 'Nothing found yet.', 'vitalstack' ); ?></h2>
          <p><?php esc_html_e( 'Start publishing posts to see them here.', 'vitalstack' ); ?></p>
          <?php get_search_form(); ?>
        </div>

      <?php endif; ?>
    </div><!-- /#primary -->

    <!-- Sidebar -->
    <aside id="secondary" class="sidebar" role="complementary">
      <?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-blog' ); ?>
      <?php else : ?>
        <!-- Default sidebar content — add widgets via Appearance → Widgets → Blog Sidebar -->
        <div class="widget">
          <h3 class="widget-title"><?php esc_html_e( 'Categories', 'vitalstack' ); ?></h3>
          <ul>
            <?php
            wp_list_categories( array(
              'show_count' => true,
              'title_li'   => '',
              'hide_empty' => true,
            ) );
            ?>
          </ul>
        </div>
        <div class="widget">
          <h3 class="widget-title"><?php esc_html_e( 'Search', 'vitalstack' ); ?></h3>
          <?php get_search_form(); ?>
        </div>
      <?php endif; ?>
    </aside>

  </div><!-- /.content-sidebar-wrap -->

</main>

<?php get_footer(); ?>

<style id="vs-index-styles">
/* Filter row */
.filter-row {
  display: flex; gap: 10px; flex-wrap: wrap;
}
.filter-btn {
  padding: 8px 18px;
  border-radius: 100px;
  border: 2px solid var(--vs-border);
  font-size: 13px; font-weight: 600;
  color: var(--vs-muted);
  background: var(--vs-white);
  cursor: pointer;
  transition: all .2s;
  text-decoration: none;
}
.filter-btn:hover,
.filter-btn.active {
  border-color: var(--vs-green);
  color: var(--vs-green);
  background: var(--vs-green-pale);
}
</style>
