<?php
/**
 * archive.php — Generic Archive Template
 *
 * Used for: date archives, author archives, tag archives.
 * Category archives use category.php (Point 5).
 *
 * @package VitalStack
 */

get_header();

// Determine archive title & description
$archive_title = get_the_archive_title();
$archive_desc  = get_the_archive_description();
?>

<main id="main" class="site-main">

<!-- Page hero -->
<section class="page-hero" aria-label="<?php esc_attr_e( 'Archive header', 'vitalstack' ); ?>">
  <div class="container" style="position:relative;">

    <?php
    // Get archive title & description
    $archive_title = get_the_archive_title();
    $archive_desc  = get_the_archive_description();

    // Optional: remove unwanted <span> from title (WordPress adds it for dates)
    // Uncomment if you want plain text instead
    // $archive_title = str_replace( array('<span>', '</span>'), '', $archive_title );
    ?>

    <!-- Breadcrumb -->
    <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'vitalstack' ); ?>">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <?php esc_html_e( 'Home', 'vitalstack' ); ?>
      </a>
      <span>/</span>
      <span><?php echo wp_kses_post( $archive_title ); ?></span>
    </nav>

    <!-- Title -->
    <h1><?php echo wp_kses_post( $archive_title ); ?></h1>

    <!-- Description -->
    <?php if ( ! empty( $archive_desc ) ) : ?>
      <p><?php echo wp_kses_post( $archive_desc ); ?></p>
    <?php endif; ?>

  </div>
</section>

  <!-- Archive layout — sidebar LEFT -->
  <div class="container content-sidebar-wrap">

    <aside id="secondary" class="sidebar" role="complementary">
      <?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-blog' ); ?>
      <?php else : ?>
        <div class="widget">
          <h3 class="widget-title"><?php esc_html_e( 'Search', 'vitalstack' ); ?></h3>
          <?php get_search_form(); ?>
        </div>
        <div class="widget">
          <h3 class="widget-title"><?php esc_html_e( 'Categories', 'vitalstack' ); ?></h3>
          <ul><?php wp_list_categories( array( 'show_count' => true, 'title_li' => '', 'hide_empty' => true ) ); ?></ul>
        </div>
      <?php endif; ?>
    </aside>

    <div id="primary" class="content-area">
      <?php if ( have_posts() ) : ?>
        <div class="cards-grid">
          <?php while ( have_posts() ) : the_post(); ?>
            <?php vitalstack_article_card( get_the_ID() ); ?>
          <?php endwhile; ?>
        </div>
        <nav class="pagination-wrap" aria-label="<?php esc_attr_e( 'Archive pagination', 'vitalstack' ); ?>">
          <?php
          the_posts_pagination( array(
            'mid_size'  => 2,
            'prev_text' => '← ' . __( 'Prev', 'vitalstack' ),
            'next_text' => __( 'Next', 'vitalstack' ) . ' →',
          ) );
          ?>
        </nav>
      <?php else : ?>
        <div class="no-posts-notice">
          <h2><?php esc_html_e( 'Nothing found.', 'vitalstack' ); ?></h2>
          <p><?php esc_html_e( 'Try browsing a different archive or using the search below.', 'vitalstack' ); ?></p>
          <?php get_search_form(); ?>
        </div>
      <?php endif; ?>
    </div>

  </div>

<style>
.content-sidebar-wrap {
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 40px;
  padding-top: 48px;
  padding-bottom: 80px;
  align-items: start;
}
.sidebar { position: sticky; top: 100px; display: flex; flex-direction: column; gap: 20px; }
@media (max-width: 1024px) {
  .content-sidebar-wrap { grid-template-columns: 1fr; }
  .sidebar { position: static; }
}
</style>

</main>

<?php get_footer(); ?>
