<?php
/**
 * Template Name: Blog Page
 *
 * A standalone blog listing page template — completely independent of
 * Settings → Reading "Posts page" assignment.
 *
 * HOW TO USE:
 *   1. Go to Pages → Add New
 *   2. Title it "Blog" (or anything you like)
 *   3. Page Attributes → Template → "Blog Page"
 *   4. Publish the page — your blog listing is live at /blog (or whatever slug you set)
 *
 * LAYOUT:
 *   1. Page hero — title, description, stats (Customizer → Blog Archive Page)
 *   2. Sticky underline filter tab bar (All / per-category)
 *   3. Featured sticky post — full-width dark hero card
 *   4. 2-column card grid for all remaining posts
 *   5. AJAX-powered pagination (falls back to standard WP pagination)
 *   6. Sidebar — Search, Categories, Popular, Tags, Newsletter
 *
 * @package VitalStack
 */

get_header();

/* ─── Pagination & category filter ──────────────────────────────── */
$paged      = max( 1, get_query_var( 'paged' ) ?: ( get_query_var( 'page' ) ?: 1 ) );
$active_cat = isset( $_GET['cat'] ) ? absint( $_GET['cat'] ) : 0;
$sort_by    = sanitize_key( $_GET['sort'] ?? 'date' );
$sort_order = ( $sort_by === 'date_asc' ) ? 'ASC' : 'DESC';
$orderby    = ( $sort_by === 'comment_count' ) ? 'comment_count' : 'date';

/* ─── Main WP_Query ─────────────────────────────────────────────── */
$blog_args = [
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => get_option( 'posts_per_page', 6 ),
    'paged'               => $paged,
    'orderby'             => $orderby,
    'order'               => $sort_order,
    'ignore_sticky_posts' => 0,   // respect sticky posts
];
if ( $active_cat ) {
    $blog_args['cat'] = $active_cat;
}
$blog_query = new WP_Query( $blog_args );
?>

<main id="main" class="site-main blog-page-tpl">

<!-- ══ 1. HERO ══════════════════════════════════════════════════════════ -->
<section class="page-hero blog-hero" aria-label="<?php esc_attr_e( 'Blog header', 'vitalstack' ); ?>">
  <div class="page-header-grid" aria-hidden="true"></div>
  <div class="container ph-inner">

    <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'vitalstack' ); ?>">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'vitalstack' ); ?></a>
      <span>/</span>
      <span><?php the_title(); ?></span>
    </nav>



    <h1><?php the_title(); ?></h1>
    <p><?php esc_html_e( 'Explore our complete library of AI and health insights research-backed, expert-written, and always relevant.', 'vitalstack' ); ?></p>


  </div>
</section>

<!-- ══ 2. FILTER TAB BAR ════════════════════════════════════════════════ -->
<div class="subcat-bar blog-filter-bar" role="navigation"
     aria-label="<?php esc_attr_e( 'Filter posts', 'vitalstack' ); ?>">
  <div class="container">
    <div class="subcat-inner">

      <!-- All tab -->
      <a href="<?php echo esc_url( get_permalink() ); ?>"
         class="subcat-tab <?php echo ! $active_cat ? 'active' : ''; ?>">
        <?php esc_html_e( 'All Posts', 'vitalstack' ); ?>
        <span class="subcat-count"><?php echo (int) wp_count_posts()->publish; ?></span>
      </a>

      <!-- One tab per top-level category -->
      <?php
      $all_cats = get_categories( [ 'hide_empty' => true, 'parent' => 0 ] );
      foreach ( $all_cats as $cat ) :
        $tab_url    = add_query_arg( 'cat', $cat->term_id, get_permalink() );
        $tab_active = ( $active_cat === (int) $cat->term_id );
      ?>
        <a href="<?php echo esc_url( $tab_url ); ?>"
           class="subcat-tab <?php echo $tab_active ? 'active' : ''; ?>">
          <?php echo esc_html( $cat->name ); ?>
          <span class="subcat-count"><?php echo esc_html( $cat->count ); ?></span>
        </a>
      <?php endforeach; ?>

      <!-- Sort dropdown -->
      <div class="sort-wrap">
        <label for="vs-sort-page" class="screen-reader-text">
          <?php esc_html_e( 'Sort by', 'vitalstack' ); ?>
        </label>
        <span><?php esc_html_e( 'Sort:', 'vitalstack' ); ?></span>
        <select id="vs-sort-page" onchange="vitalstackPageSort(this.value)">
          <option value="date"          <?php selected( $sort_by, 'date' ); ?>><?php esc_html_e( 'Latest', 'vitalstack' ); ?></option>
          <option value="comment_count" <?php selected( $sort_by, 'comment_count' ); ?>><?php esc_html_e( 'Popular', 'vitalstack' ); ?></option>
          <option value="date_asc"      <?php selected( $sort_by, 'date_asc' ); ?>><?php esc_html_e( 'Oldest', 'vitalstack' ); ?></option>
        </select>
      </div>
    </div>
  </div>
</div>

<!-- ══ 3–5. BLOG LAYOUT ════════════════════════════════════════════════ -->
<div class="container blog-layout">

  <!-- MAIN CONTENT -->
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
                <div class="cat-link <?php echo ( $active_cat === $tc->term_id ) ? 'active' : ''; ?>">
                  <div class="cat-left">
                    <span class="cat-icon"><?php echo $icon; ?></span>
                    <a href="<?php echo esc_url( add_query_arg( 'cat', $tc->term_id, get_permalink() ) ); ?>">
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
                          <a href="<?php echo esc_url( add_query_arg( 'cat', $sub->term_id, get_permalink() ) ); ?>">
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

      <!-- Popular posts -->
      <div class="widget sidebar-card">
        <div class="sidebar-card-header">
          <span>🔥</span>
          <h3 class="widget-title"><?php esc_html_e( 'Popular This Week', 'vitalstack' ); ?></h3>
        </div>
        <div class="sidebar-card-body">
          <?php
          $popular = new WP_Query( [ 'posts_per_page' => 4, 'orderby' => 'comment_count', 'ignore_sticky_posts' => 1 ] );
          if ( $popular->have_posts() ) : $n = 1; ?>
            <div class="pop-list">
              <?php while ( $popular->have_posts() ) : $popular->the_post(); ?>
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

      <!-- Tags -->
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

  </aside>

  <main id="primary" class="blog-main" role="main">

    <?php if ( $blog_query->have_posts() ) : ?>

      <?php
      $sticky_ids   = get_option( 'sticky_posts' );
      $shown_sticky = false;
      $grid_posts   = [];   // non-featured posts collected for 2-col grid

      while ( $blog_query->have_posts() ) :
        $blog_query->the_post();
        $pid       = get_the_ID();
        $cat_class = vitalstack_get_cat_class( $pid );
        $cat       = get_the_category( $pid );
        $cat_name  = ! empty( $cat ) ? esc_html( $cat[0]->name ) : 'Article';
        $read_time = vitalstack_read_time( $pid );
        $author_id = get_post_field( 'post_author', $pid );
        $initials  = vitalstack_author_initials( $author_id );
        $is_sticky = in_array( $pid, $sticky_ids, true );
        $is_feat   = $is_sticky && ! $shown_sticky && $paged === 1 && ! $active_cat;
        if ( $is_feat ) $shown_sticky = true;

        if ( $is_feat ) :
      ?>

        <!-- FEATURED full-width card (first sticky, page 1 only) -->
        <article id="post-<?php echo esc_attr( $pid ); ?>" class="featured-post-card">
          <span class="featured-badge">⭐ <?php esc_html_e( 'Featured', 'vitalstack' ); ?></span>

          <a href="<?php the_permalink(); ?>" class="featured-thumb-wrap" tabindex="-1" aria-hidden="true">
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail( 'vitalstack-hero', [ 'class' => 'featured-thumb-img', 'alt' => esc_attr( get_the_title() ) ] ); ?>
            <?php else : ?>
              <div class="featured-thumb-placeholder <?php echo esc_attr( $cat_class ); ?>-bg">
                <?php echo $cat_class === 'health' ? '🌿' : '🤖'; ?>
              </div>
            <?php endif; ?>
          </a>

          <div class="featured-body">
            <div class="post-meta">
              <span class="tag <?php echo esc_attr( $cat_class ); ?>"><?php echo $cat_name; ?></span>
              <span class="read-time-light">· <?php echo esc_html( $read_time ); ?></span>
            </div>
            <h2 class="featured-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p class="featured-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
            <div class="featured-footer">
              <div class="post-author-light">
                <span class="avatar"><?php echo get_avatar( $author_id, 24 ) ?: esc_html( $initials ); ?></span>
                <?php the_author(); ?>
                <span aria-hidden="true">·</span>
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                  <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?>
                </time>
              </div>
              <a href="<?php the_permalink(); ?>" class="read-link-light">
                <?php esc_html_e( 'Read article →', 'vitalstack' ); ?>
              </a>
            </div>
          </div>
        </article>

      <?php else :
        // Collect non-featured posts
        $grid_posts[] = [
          'pid'       => $pid,
          'cat_class' => $cat_class,
          'cat_name'  => $cat_name,
          'read_time' => $read_time,
          'author_id' => $author_id,
          'initials'  => $initials,
          'title'     => get_the_title(),
          'permalink' => get_permalink(),
          'excerpt'   => get_the_excerpt(),
          'date_c'    => get_the_date( 'c' ),
          'date_fmt'  => get_the_date( 'M j, Y' ),
          'author'    => get_the_author(),
          'has_thumb' => has_post_thumbnail(),
        ];
      endif;
      endwhile;
      wp_reset_postdata();
      ?>

      <!-- 2-COLUMN CARD GRID -->
      <?php if ( ! empty( $grid_posts ) ) : ?>
        <?php if ( $shown_sticky ) : ?>
          <div class="grid-section-label">
            <span><?php esc_html_e( 'Latest Articles', 'vitalstack' ); ?></span>
          </div>
        <?php endif; ?>

        <div class="blog-cards-grid" id="blog-cards-grid">
          <?php foreach ( $grid_posts as $i => $p ) :
            $delay = round( min( $i * 0.07, 0.42 ), 2 );
          ?>
            <article id="post-<?php echo esc_attr( $p['pid'] ); ?>"
                     class="blog-card"
                     style="animation-delay:<?php echo $delay; ?>s">

              <!-- Thumbnail -->
              <a href="<?php echo esc_url( $p['permalink'] ); ?>"
                 class="blog-card-thumb-wrap" tabindex="-1" aria-hidden="true">
                <?php if ( $p['has_thumb'] ) : ?>
                  <?php echo get_the_post_thumbnail( $p['pid'], 'vitalstack-card', [
                    'class' => 'blog-card-thumb-img',
                    'alt'   => esc_attr( $p['title'] ),
                  ] ); ?>
                <?php else : ?>
                  <div class="blog-card-thumb-placeholder <?php echo esc_attr( $p['cat_class'] ); ?>-bg">
                    <?php echo $p['cat_class'] === 'health' ? '🌿' : '🤖'; ?>
                  </div>
                <?php endif; ?>
              </a>

              <!-- Body -->
              <div class="blog-card-body">
                <div class="blog-card-meta">
                  <span class="tag <?php echo esc_attr( $p['cat_class'] ); ?>"><?php echo $p['cat_name']; ?></span>
                  <span class="read-time">· <?php echo esc_html( $p['read_time'] ); ?></span>
                </div>

                <h2 class="blog-card-title">
                  <a href="<?php echo esc_url( $p['permalink'] ); ?>"><?php echo esc_html( $p['title'] ); ?></a>
                </h2>

                <p class="blog-card-excerpt"><?php echo esc_html( $p['excerpt'] ); ?></p>

                <div class="blog-card-footer">
                  <div class="post-author">
                    <span class="avatar">
                      <?php echo get_avatar( $p['author_id'], 24 ) ?: esc_html( $p['initials'] ); ?>
                    </span>
                    <?php echo esc_html( $p['author'] ); ?>
                    <span aria-hidden="true">·</span>
                    <time datetime="<?php echo esc_attr( $p['date_c'] ); ?>">
                      <?php echo esc_html( $p['date_fmt'] ); ?>
                    </time>
                  </div>
                  <a href="<?php echo esc_url( $p['permalink'] ); ?>" class="read-link-sm">
                    <?php esc_html_e( 'Read →', 'vitalstack' ); ?>
                  </a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div><!-- /.blog-cards-grid -->
      <?php endif; ?>

    <?php else : ?>
      <div class="no-posts-notice">
        <h2><?php esc_html_e( 'No articles found.', 'vitalstack' ); ?></h2>
        <p><?php esc_html_e( 'Try a different filter or check back soon — new content is published weekly.', 'vitalstack' ); ?></p>
        <?php get_search_form(); ?>
      </div>
    <?php endif; ?>

    <!-- Pagination -->
    <nav class="pagination-wrap" aria-label="<?php esc_attr_e( 'Posts pagination', 'vitalstack' ); ?>">
      <?php
      $big = 999999999;
      echo paginate_links( [
        'base'      => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
        'format'    => '?paged=%#%',
        'current'   => $paged,
        'total'     => $blog_query->max_num_pages,
        'prev_text' => '← ' . __( 'Prev', 'vitalstack' ),
        'next_text' => __( 'Next', 'vitalstack' ) . ' →',
        'add_args'  => array_filter( [
            'cat'  => $active_cat ?: null,
            'sort' => ( $sort_by !== 'date' ) ? $sort_by : null,
        ] ),
      ] );
      ?>
    </nav>

  </main><!-- /#primary -->

  <!-- SIDEBAR -->

</div><!-- /.blog-layout -->

</main>
<?php get_footer(); ?>

<style id="vs-blog-page-styles">
/* ── Hero ── */
.blog-hero{background:var(--vs-navy);padding:60px 0;position:relative;overflow:hidden}
.blog-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 50% 100% at 90% 50%,rgba(29,52,97,.8) 0%,transparent 70%);pointer-events:none}
.page-header-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:48px 48px;pointer-events:none}
.ph-inner{position:relative}
.blog-hero-badge{display:inline-flex;align-items:center;gap:10px;background:rgba(29,52,97,.6);border:1px solid rgba(255,255,255,.12);border-radius:10px;padding:10px 18px;font-family:'Space Mono',monospace;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.6);margin-bottom:18px}
.blog-hero h1{font-family:'Playfair Display',serif;font-size:clamp(32px,5vw,52px);font-weight:900;color:var(--vs-white);margin:0 0 12px;line-height:1.1}
.ph-inner>p{font-size:16px;color:rgba(255,255,255,.55);max-width:540px;line-height:1.7;margin-bottom:28px}
.ph-meta{display:flex;align-items:center;gap:0;flex-wrap:wrap;padding-top:24px;border-top:1px solid rgba(255,255,255,.1)}
.ph-stat{font-size:14px;color:rgba(255,255,255,.55);padding-right:24px}
.ph-stat strong{color:var(--vs-white);font-weight:700}
.ph-stat-divider{width:1px;height:16px;background:rgba(255,255,255,.15);margin-right:24px;flex-shrink:0}

/* ── Filter tab bar ── */
.blog-filter-bar{background:var(--vs-white);border-bottom:1px solid var(--vs-border);position:sticky;top:68px;z-index:90}
.subcat-inner{display:flex;align-items:center;gap:4px;overflow-x:auto;scrollbar-width:none}
.subcat-inner::-webkit-scrollbar{display:none}
.subcat-tab{display:inline-flex;align-items:center;gap:7px;padding:16px 20px;font-size:14px;font-weight:500;color:var(--vs-muted);cursor:pointer;border-bottom:3px solid transparent;white-space:nowrap;transition:all .2s;text-decoration:none;flex-shrink:0}
.subcat-tab:hover{color:var(--vs-navy)}
.subcat-tab.active{color:var(--vs-navy);border-color:var(--vs-navy);font-weight:600}
.subcat-count{font-family:'Space Mono',monospace;font-size:10px;background:var(--vs-navy-light);color:var(--vs-navy);border-radius:4px;padding:2px 7px}
.sort-wrap{margin-left:auto;display:flex;align-items:center;gap:8px;font-size:13px;color:var(--vs-muted);flex-shrink:0;padding:0 4px}
.sort-wrap select{padding:6px 10px;border:1px solid var(--vs-border);border-radius:6px;font-size:13px;font-family:'DM Sans',sans-serif;color:var(--vs-text);background:var(--vs-white);cursor:pointer;outline:none}
.sort-wrap select:focus{border-color:var(--vs-green)}

/* ── Main layout ── */
.blog-layout{display:grid;grid-template-columns:316px 1fr;gap:40px;padding-top:48px;padding-bottom:80px;align-items:start}

/* ── Featured card ── */
.featured-post-card{background:linear-gradient(135deg,var(--vs-navy) 0%,var(--vs-navy-mid) 100%);border-radius:var(--vs-radius);overflow:hidden;margin-bottom:24px;position:relative;box-shadow:var(--vs-card-shadow);opacity:0;transform:translateY(12px);animation:vsBlogCardIn .5s ease .05s forwards}
.featured-badge{position:absolute;top:16px;left:16px;background:var(--vs-green);color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:100px;font-family:'Space Mono',monospace;letter-spacing:.06em;text-transform:uppercase;z-index:2}
.featured-thumb-wrap{display:block;overflow:hidden;max-height:300px}
.featured-thumb-img{width:100%;height:300px;object-fit:cover;display:block;transition:transform .4s}
.featured-post-card:hover .featured-thumb-img{transform:scale(1.03)}
.featured-thumb-placeholder{min-height:200px;display:flex;align-items:center;justify-content:center;font-size:72px}
.featured-body{padding:28px 32px;display:flex;flex-direction:column;gap:10px}
.featured-title{font-family:'Playfair Display',serif;font-size:clamp(20px,2.2vw,30px);font-weight:700;color:#fff;line-height:1.25;margin:4px 0}
.featured-title a{color:#fff}.featured-title a:hover{color:var(--vs-green-light)}
.featured-excerpt{font-size:15px;color:rgba(255,255,255,.6);line-height:1.7}
.featured-footer{display:flex;align-items:center;justify-content:space-between;padding-top:16px;border-top:1px solid rgba(255,255,255,.12);flex-wrap:wrap;gap:8px;margin-top:auto}
.post-author-light{display:flex;align-items:center;gap:8px;font-size:13px;color:rgba(255,255,255,.65)}
.read-link-light{font-size:13px;font-weight:600;color:var(--vs-green-light);white-space:nowrap}
.read-link-light:hover{color:#fff}
.read-time-light{font-family:'Space Mono',monospace;font-size:10px;color:rgba(255,255,255,.45)}

/* ── Grid section label ── */
.grid-section-label{display:flex;align-items:center;gap:16px;margin:8px 0 20px}
.grid-section-label::before,.grid-section-label::after{content:'';flex:1;height:1px;background:var(--vs-border)}
.grid-section-label span{font-family:'Space Mono',monospace;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:var(--vs-muted);white-space:nowrap}

/* ── 2-column card grid ── */
.blog-cards-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:22px}
.blog-card{background:var(--vs-white);border:1px solid var(--vs-border);border-radius:var(--vs-radius);overflow:hidden;box-shadow:var(--vs-card-shadow);transition:transform .3s,box-shadow .3s;display:flex;flex-direction:column;opacity:0;transform:translateY(14px);animation:vsBlogCardIn .55s ease forwards}
@keyframes vsBlogCardIn{to{opacity:1;transform:translateY(0)}}
.blog-card:hover{transform:translateY(-4px);box-shadow:var(--vs-card-hover)}
.blog-card-thumb-wrap{display:block;overflow:hidden}
.blog-card-thumb-img{width:100%;height:196px;object-fit:cover;display:block;transition:transform .4s}
.blog-card:hover .blog-card-thumb-img{transform:scale(1.04)}
.blog-card-thumb-placeholder{height:196px;display:flex;align-items:center;justify-content:center;font-size:52px}
.blog-card-body{padding:20px;display:flex;flex-direction:column;flex:1}
.blog-card-meta{display:flex;align-items:center;gap:8px;margin-bottom:10px}
.blog-card-title{font-family:'Playfair Display',serif;font-size:clamp(16px,1.5vw,19px);font-weight:700;line-height:1.35;color:var(--vs-text);margin-bottom:10px}
.blog-card-title a{color:inherit}
.blog-card:hover .blog-card-title a{color:var(--vs-green)}
.blog-card-excerpt{font-size:13px;color:var(--vs-muted);line-height:1.65;flex:1;margin-bottom:16px;display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;overflow:hidden}
.blog-card-footer{display:flex;align-items:center;justify-content:space-between;padding-top:14px;border-top:1px solid var(--vs-border);flex-wrap:wrap;gap:6px;margin-top:auto}
.post-author{display:flex;align-items:center;gap:7px;font-size:12px;color:var(--vs-muted)}
.read-link-sm{font-size:12px;font-weight:600;color:var(--vs-green);white-space:nowrap}
.read-link-sm:hover{color:var(--vs-green-light)}
.read-time{font-family:'Space Mono',monospace;font-size:10px;color:var(--vs-muted)}

/* ── Shared tag + avatar ── */
.tag{display:inline-flex;align-items:center;gap:5px;font-family:'Space Mono',monospace;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;border-radius:4px;padding:4px 10px}
.tag.ai{background:var(--vs-navy-light);color:var(--vs-navy)}
.tag.health{background:var(--vs-green-pale);color:var(--vs-green)}
.avatar{width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,var(--vs-green),var(--vs-navy-mid));display:inline-flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;color:#fff;flex-shrink:0;overflow:hidden}
.avatar img{width:100%;height:100%;object-fit:cover}
.ai-bg{background:linear-gradient(135deg,#0d1b3e 0%,#1d3461 50%,#1a5c8a 100%)}
.health-bg{background:linear-gradient(135deg,#0d3320 0%,#1d8a4e 50%,#25b067 100%)}

/* ── Sidebar ── */
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

/* ── Pagination ── */
.pagination-wrap{margin:36px 0}
.pagination-wrap .nav-links{display:flex;gap:8px;flex-wrap:wrap;align-items:center;justify-content:center}
.pagination-wrap .page-numbers{display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 12px;border-radius:7px;border:1.5px solid var(--vs-border);font-size:13px;font-weight:600;color:var(--vs-muted);transition:all .2s;text-decoration:none}
.pagination-wrap .page-numbers:hover,.pagination-wrap .page-numbers.current{background:var(--vs-navy);color:#fff;border-color:var(--vs-navy)}
.pagination-wrap .page-numbers.dots{border-color:transparent;background:transparent;cursor:default}

/* ── No posts ── */
.no-posts-notice{background:var(--vs-white);border:1px solid var(--vs-border);border-radius:var(--vs-radius);padding:48px 32px;text-align:center}
.no-posts-notice h2{font-family:'Playfair Display',serif;color:var(--vs-navy);margin-bottom:8px}
.no-posts-notice p{color:var(--vs-muted);margin-bottom:20px}

/* ── Responsive ── */
@media(max-width:1024px){.blog-layout{grid-template-columns:1fr}.sidebar{position:static}}
@media(max-width:640px){.blog-cards-grid{grid-template-columns:1fr}.featured-body{padding:20px}.blog-filter-bar{top:0}}
</style>

<script>
/* Category toggle */
document.querySelectorAll('.cat-toggle-btn').forEach(function(btn){
  btn.addEventListener('click',function(e){
    e.preventDefault();
    var children=this.closest('.cat-item').querySelector('.cat-children');
    if(children){children.classList.toggle('open');this.classList.toggle('open')}
  });
});
/* Sort redirect */
function vitalstackPageSort(value){
  var url=new URL(window.location.href);
  if(value==='date'){url.searchParams.delete('sort');}
  else{url.searchParams.set('sort',value);}
  window.location.href=url.toString();
}
</script>
