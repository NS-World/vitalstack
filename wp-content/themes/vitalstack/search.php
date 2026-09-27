<?php
/**
 * search.php — Search Results Template
 *
 * Renders search results with the same styled sidebar-left layout
 * as the main blog page (page-blog.php).
 *
 * @package VitalStack
 */

get_header();

$search_query = get_search_query();
?>

<main id="main" class="site-main">

<!-- Page hero -->
<section class="page-hero" aria-label="<?php esc_attr_e( 'Search results header', 'vitalstack' ); ?>">
  <div class="container" style="position:relative;">

    <!-- Breadcrumb -->
    <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'vitalstack' ); ?>">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'vitalstack' ); ?></a>
      <span>/</span>
      <span><?php esc_html_e( 'Search Results', 'vitalstack' ); ?></span>
    </nav>

    <p class="section-eyebrow"><?php esc_html_e( 'Search Results', 'vitalstack' ); ?></p>
    <h1>
      <?php
      printf(
        /* translators: %s: search query */
        esc_html__( 'Results for: "%s"', 'vitalstack' ),
        '<span style="color:var(--vs-green);">' . esc_html( $search_query ) . '</span>'
      );
      ?>
    </h1>
    <?php
    $found = $wp_query->found_posts;
    printf(
      '<p>' . esc_html( _n( '%s article found', '%s articles found', $found, 'vitalstack' ) ) . '</p>',
      '<strong>' . number_format_i18n( $found ) . '</strong>'
    );
    ?>

  </div>
</section>

<!-- Search results layout — sidebar LEFT, matches blog page -->
<div class="container blog-layout">

  <!-- ══ SIDEBAR ══════════════════════════════════════════════════════ -->
  <aside id="secondary" class="sidebar" role="complementary"
         aria-label="<?php esc_attr_e( 'Blog sidebar', 'vitalstack' ); ?>">

    <!-- Search -->
    <div class="widget sidebar-card">
      <div class="sidebar-card-header">
        <span>🔍</span>
        <h3 class="widget-title"><?php esc_html_e( 'Search Articles', 'vitalstack' ); ?></h3>
      </div>
      <div class="sidebar-card-body"><?php get_search_form(); ?></div>
    </div>

    <!-- Categories -->
    <div class="widget sidebar-card">
      <div class="sidebar-card-header">
        <span>📂</span>
        <h3 class="widget-title"><?php esc_html_e( 'Categories', 'vitalstack' ); ?></h3>
      </div>
      <div class="sidebar-card-body" style="padding:12px 14px;">
        <ul class="cat-tree">
          <?php
          $top_cats = get_categories( [ 'hide_empty' => true, 'parent' => 0 ] );
          foreach ( $top_cats as $tc ) :
            $sub_cats = get_categories( [ 'hide_empty' => true, 'parent' => $tc->term_id ] );
            $icon     = strpos( strtolower( $tc->slug ), 'health' ) !== false ? '🌿' : '🤖';
          ?>
            <li class="cat-item">
              <div class="cat-link">
                <div class="cat-left">
                  <span class="cat-icon"><?php echo $icon; ?></span>
                  <a href="<?php echo esc_url( get_category_link( $tc->term_id ) ); ?>">
                    <?php echo esc_html( $tc->name ); ?>
                  </a>
                </div>
                <div class="cat-right">
                  <span class="cat-count"><?php echo esc_html( $tc->count ); ?></span>
                  <?php if ( ! empty( $sub_cats ) ) : ?>
                    <button class="cat-toggle-btn" aria-label="<?php esc_attr_e( 'Toggle', 'vitalstack' ); ?>">▶</button>
                  <?php endif; ?>
                </div>
              </div>
              <?php if ( ! empty( $sub_cats ) ) : ?>
                <ul class="cat-children">
                  <?php foreach ( $sub_cats as $sub ) : ?>
                    <li>
                      <div class="cat-child-link">
                        <a href="<?php echo esc_url( get_category_link( $sub->term_id ) ); ?>">
                          <?php echo esc_html( $sub->name ); ?>
                        </a>
                        <span class="sub-count"><?php echo esc_html( $sub->count ); ?></span>
                      </div>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <!-- Recent Posts -->
    <div class="widget sidebar-card">
      <div class="sidebar-card-header">
        <span>🔥</span>
        <h3 class="widget-title"><?php esc_html_e( 'Recent Posts', 'vitalstack' ); ?></h3>
      </div>
      <div class="sidebar-card-body">
        <?php
        $recent = new WP_Query( [ 'posts_per_page' => 5, 'orderby' => 'date', 'order' => 'DESC', 'ignore_sticky_posts' => 1 ] );
        if ( $recent->have_posts() ) : $n = 1; ?>
          <div class="pop-list">
            <?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
              <div class="pop-item">
                <span class="pop-num"><?php echo str_pad( $n++, 2, '0', STR_PAD_LEFT ); ?></span>
                <div>
                  <div class="pop-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
                  <div class="pop-meta"><?php
                    $pc = get_the_category();
                    echo ( ! empty( $pc ) ? esc_html( $pc[0]->name ) . ' · ' : '' ) . esc_html( vitalstack_read_time( get_the_ID() ) );
                  ?></div>
                </div>
              </div>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Popular Tags -->
    <div class="widget sidebar-card">
      <div class="sidebar-card-header">
        <span>🏷️</span>
        <h3 class="widget-title"><?php esc_html_e( 'Popular Tags', 'vitalstack' ); ?></h3>
      </div>
      <div class="sidebar-card-body">
        <div class="vs-tag-cloud">
          <?php wp_tag_cloud( [ 'smallest' => 12, 'largest' => 14, 'unit' => 'px', 'number' => 16, 'format' => 'flat', 'separator' => '', 'orderby' => 'count', 'order' => 'DESC' ] ); ?>
        </div>
      </div>
    </div>

    <!-- Newsletter -->
    <div class="widget sidebar-newsletter">
      <h3><?php esc_html_e( 'Stay Informed', 'vitalstack' ); ?></h3>
      <p><?php esc_html_e( 'Get the best AI & health articles delivered weekly.', 'vitalstack' ); ?></p>
      <?php if ( shortcode_exists( 'mailchimp' ) ) {
        echo do_shortcode( '[mailchimp]' );
      } else { ?>
        <form class="sidebar-nl-form" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
          <input type="hidden" name="action" value="vitalstack_newsletter_signup">
          <?php wp_nonce_field( 'vitalstack_newsletter', 'vs_nonce' ); ?>
          <input class="nl-input" type="email" name="email"
                 placeholder="<?php esc_attr_e( 'your@email.com', 'vitalstack' ); ?>" required>
          <button type="submit" class="nl-btn"><?php esc_html_e( 'Subscribe Free →', 'vitalstack' ); ?></button>
        </form>
      <?php } ?>
    </div>

  </aside><!-- /.sidebar -->

  <!-- ══ MAIN CONTENT ═════════════════════════════════════════════════ -->
  <main id="primary" class="blog-main" role="main">

    <?php if ( have_posts() ) : ?>

      <div class="blog-cards-grid">
        <?php while ( have_posts() ) : the_post(); ?>
          <?php vitalstack_article_card( get_the_ID() ); ?>
        <?php endwhile; ?>
      </div>

      <!-- Pagination -->
      <nav class="pagination-wrap" aria-label="<?php esc_attr_e( 'Search results pagination', 'vitalstack' ); ?>">
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
        <h2><?php esc_html_e( 'No results found.', 'vitalstack' ); ?></h2>
        <p>
          <?php
          printf(
            esc_html__( 'Sorry, nothing matched "%s". Try a different keyword.', 'vitalstack' ),
            '<strong>' . esc_html( $search_query ) . '</strong>'
          );
          ?>
        </p>
        <?php get_search_form(); ?>
      </div>

    <?php endif; ?>

  </main><!-- /#primary -->

</div><!-- /.blog-layout -->

</main>

<style id="vs-search-styles">
/* ── Sidebar layout (same as blog page) ── */
.blog-layout{display:grid;grid-template-columns:316px 1fr;gap:40px;padding-top:48px;padding-bottom:80px;align-items:start}
.sidebar{position:sticky;top:128px;display:flex;flex-direction:column;gap:22px}
.sidebar-card{background:var(--vs-white);border:1px solid var(--vs-border);border-radius:var(--vs-radius);overflow:hidden}
.sidebar-card-header{padding:14px 18px;border-bottom:1px solid var(--vs-border);display:flex;align-items:center;gap:8px}
.sidebar-card-header h3,.sidebar-card-header .widget-title{font-family:'Playfair Display',serif;font-size:16px;font-weight:700;color:var(--vs-navy);margin:0}
.sidebar-card-body{padding:14px 16px}
.cat-tree{list-style:none;display:flex;flex-direction:column;gap:2px}
.cat-link{display:flex;align-items:center;justify-content:space-between;padding:9px 10px;border-radius:7px;cursor:pointer;transition:background .2s}
.cat-link:hover,.cat-link.active{background:var(--vs-navy-light)}
.cat-left{display:flex;align-items:center;gap:9px;font-size:13px;font-weight:500}
.cat-left a{font-size:13px;font-weight:500;color:var(--vs-text)}.cat-left a:hover{color:var(--vs-green)}
.cat-icon{font-size:18px}
.cat-right{display:flex;align-items:center;gap:6px}
.cat-count{font-family:'Space Mono',monospace;font-size:10px;background:var(--vs-off-white);border:1px solid var(--vs-border);border-radius:4px;padding:2px 6px;color:var(--vs-muted)}
.cat-toggle-btn{background:none;border:none;cursor:pointer;font-size:10px;color:var(--vs-muted);transition:transform .25s;padding:2px}
.cat-toggle-btn.open{transform:rotate(90deg)}
.cat-children{list-style:none;margin-left:14px;border-left:2px solid var(--vs-border);padding-left:10px;display:none;flex-direction:column;gap:2px;margin-top:2px}
.cat-children.open{display:flex}
.cat-child-link{display:flex;align-items:center;justify-content:space-between;padding:7px 8px;border-radius:6px;font-size:12px;color:var(--vs-muted);transition:background .2s}
.cat-child-link:hover{background:var(--vs-off-white)}
.cat-child-link a{color:var(--vs-muted);font-size:12px}.cat-child-link a:hover{color:var(--vs-green)}
.sub-count{font-size:11px;color:var(--vs-border)}
.pop-list{display:flex;flex-direction:column;gap:12px}
.pop-item{display:flex;gap:10px;align-items:flex-start;padding:9px;border-radius:7px;transition:background .2s}
.pop-item:hover{background:var(--vs-off-white)}
.pop-num{font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:var(--vs-border);line-height:1;flex-shrink:0;width:22px}
.pop-title{font-size:12px;font-weight:600;color:var(--vs-navy);line-height:1.4;margin-bottom:3px}
.pop-title a{color:inherit}.pop-item:hover .pop-title a{color:var(--vs-green)}
.pop-meta{font-size:11px;color:var(--vs-muted)}
.vs-tag-cloud{display:flex;flex-wrap:wrap;gap:7px}
.vs-tag-cloud a{display:inline-flex;padding:5px 11px;border-radius:50px;font-size:11px!important;font-weight:500;border:1.5px solid var(--vs-border);color:var(--vs-muted);transition:all .2s}
.vs-tag-cloud a:hover{border-color:var(--vs-navy);color:var(--vs-navy);background:var(--vs-navy-light)}
.sidebar-newsletter{background:var(--vs-navy);border-radius:var(--vs-radius);padding:22px;text-align:center}
.sidebar-newsletter h3{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:#fff;margin-bottom:7px}
.sidebar-newsletter p{font-size:12px;color:rgba(255,255,255,.5);line-height:1.6;margin-bottom:14px}
.sidebar-nl-form{display:flex;flex-direction:column;gap:10px}
.nl-input{width:100%;padding:10px 13px;border:none;border-radius:6px;font-size:13px;font-family:'DM Sans',sans-serif;outline:none}
.nl-btn{width:100%;padding:10px;background:var(--vs-green);color:#fff;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif}
.nl-btn:hover{background:var(--vs-green-light)}

/* ── Results grid ── */
.blog-cards-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:24px}

/* ── Pagination ── */
.pagination-wrap{margin:36px 0}
.pagination-wrap .nav-links{display:flex;gap:8px;flex-wrap:wrap;align-items:center;justify-content:center}
.pagination-wrap .page-numbers{display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 12px;border-radius:7px;border:1.5px solid var(--vs-border);font-size:13px;font-weight:600;color:var(--vs-muted);transition:all .2s;text-decoration:none}
.pagination-wrap .page-numbers:hover,.pagination-wrap .page-numbers.current{background:var(--vs-navy);color:#fff;border-color:var(--vs-navy)}

/* ── No results ── */
.no-posts-notice{background:var(--vs-white);border:1px solid var(--vs-border);border-radius:var(--vs-radius);padding:48px 32px;text-align:center}
.no-posts-notice h2{font-family:'Playfair Display',serif;color:var(--vs-navy);margin-bottom:8px}
.no-posts-notice p{color:var(--vs-muted);margin-bottom:20px}

/* ── Responsive ── */
@media(max-width:1024px){.blog-layout{grid-template-columns:1fr}.sidebar{position:static}}
@media(max-width:640px){.blog-cards-grid{grid-template-columns:1fr}}
</style>

<script>
/* Category accordion toggle */
document.querySelectorAll('.cat-toggle-btn').forEach(function(btn){
  btn.addEventListener('click',function(e){
    e.preventDefault();
    var children=this.closest('.cat-item').querySelector('.cat-children');
    if(children){children.classList.toggle('open');this.classList.toggle('open');}
  });
});
</script>

<?php get_footer(); ?>
