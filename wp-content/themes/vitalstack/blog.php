<?php
/**
 * Blog Listing Template (blog.php)
 *
 * Sections:
 *   1. Page hero banner with stats
 *   2. Filter / tab bar (All / AI / Health) + Sort
 *   3. Featured sticky post + standard post rows
 *   4. WP Pagination
 *   5. Sidebar (Search, Categories, Popular, Tags, Newsletter)
 *
 * @package VitalStack
 */

get_header();
?>
<main id="main" class="site-main">

<!-- ══ 1. HERO ══════════════════════════════════════════════════════════════ -->
<section class="page-hero blog-hero" aria-label="<?php esc_attr_e('Blog header','vitalstack'); ?>">
  <div class="page-header-grid" aria-hidden="true"></div>
  <div class="container ph-inner">

    <nav class="breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb','vitalstack'); ?>">
      <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home','vitalstack'); ?></a>
      <span aria-hidden="true">/</span>
      <span><?php esc_html_e('Blog','vitalstack'); ?></span>
    </nav>



    <h1><?php esc_html_e('All Articles','vitalstack'); ?></h1>
    <p><?php esc_html_e('Explore our complete library of AI and health insights — research-backed, expert-written, and always relevant.','vitalstack'); ?></p>

  </div>
</section>

<!-- ══ 2. FILTER TAB BAR ══════════════════════════════════════════════════ -->
<div class="subcat-bar blog-filter-bar" role="navigation" aria-label="<?php esc_attr_e('Filter posts','vitalstack'); ?>">
  <div class="container">
    <div class="subcat-inner">
      <?php $is_cat_filter = isset($_GET['cat']) && absint($_GET['cat']) > 0; ?>
      <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>"
         class="subcat-tab <?php echo !$is_cat_filter ? 'active' : ''; ?>">
        <?php esc_html_e('All Posts','vitalstack'); ?>
        <span class="subcat-count"><?php echo wp_count_posts()->publish; ?></span>
      </a>
      <?php
      $cats = get_categories(array('hide_empty'=>true,'parent'=>0));
      foreach ($cats as $cat) :
        $active = is_category($cat->term_id) || ($is_cat_filter && absint($_GET['cat'])===$cat->term_id);
      ?>
        <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"
           class="subcat-tab <?php echo $active ? 'active' : ''; ?>">
          <?php echo esc_html($cat->name); ?>
          <span class="subcat-count"><?php echo esc_html($cat->count); ?></span>
        </a>
      <?php endforeach; ?>

      <div class="sort-wrap">
        <label for="vs-sort" class="screen-reader-text"><?php esc_html_e('Sort by','vitalstack'); ?></label>
        <span><?php esc_html_e('Sort:','vitalstack'); ?></span>
        <select id="vs-sort" onchange="vitalstackSort(this.value)">
          <option value="date"><?php esc_html_e('Latest','vitalstack'); ?></option>
          <option value="comment_count"><?php esc_html_e('Popular','vitalstack'); ?></option>
          <option value="date_asc"><?php esc_html_e('Oldest','vitalstack'); ?></option>
        </select>
      </div>
    </div>
  </div>
</div>

<!-- ══ 3-5. BLOG LAYOUT ═══════════════════════════════════════════════════ -->
<div class="container blog-layout">

  <aside id="secondary" class="sidebar" role="complementary" aria-label="<?php esc_attr_e('Blog sidebar','vitalstack'); ?>">

    <?php if (is_active_sidebar('sidebar-blog')) : ?>
      <?php dynamic_sidebar('sidebar-blog'); ?>
    <?php else : ?>

      <!-- Search -->
      <div class="widget sidebar-card">
        <div class="sidebar-card-header">
          <span>🔍</span>
          <h3 class="widget-title"><?php esc_html_e( 'Search Articles', 'vitalstack' ); ?></h3>
        </div>
        <div class="sidebar-card-body"><?php get_search_form(); ?></div>
      </div>

      <div class="widget sidebar-card">
        <div class="sidebar-card-header"><span>📂</span><h3 class="widget-title"><?php esc_html_e('Categories','vitalstack'); ?></h3></div>
        <div class="sidebar-card-body" style="padding:12px 14px;">
          <ul class="cat-tree">
            <?php
            $top_cats = get_categories(array('hide_empty'=>true,'parent'=>0));
            foreach ($top_cats as $top_cat) :
              $sub_cats = get_categories(array('hide_empty'=>true,'parent'=>$top_cat->term_id));
              $icon = (strpos(strtolower($top_cat->slug),'health')!==false) ? '🌿' : '🤖';
            ?>
              <li class="cat-item">
                <div class="cat-link <?php echo is_category($top_cat->term_id)?'active':''; ?>">
                  <div class="cat-left">
                    <span class="cat-icon" aria-hidden="true"><?php echo $icon; ?></span>
                    <a href="<?php echo esc_url(get_category_link($top_cat->term_id)); ?>"><?php echo esc_html($top_cat->name); ?></a>
                  </div>
                  <div class="cat-right">
                    <span class="cat-count"><?php echo esc_html($top_cat->count); ?></span>
                    <?php if (!empty($sub_cats)) : ?>
                      <button class="cat-toggle-btn" aria-label="<?php esc_attr_e('Toggle subcategories','vitalstack'); ?>">▶</button>
                    <?php endif; ?>
                  </div>
                </div>
                <?php if (!empty($sub_cats)) : ?>
                  <ul class="cat-children">
                    <?php foreach ($sub_cats as $sub) : ?>
                      <li>
                        <div class="cat-child-link">
                          <a href="<?php echo esc_url(get_category_link($sub->term_id)); ?>"><?php echo esc_html($sub->name); ?></a>
                          <span class="sub-count"><?php echo esc_html($sub->count); ?></span>
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

      <div class="widget sidebar-card">
        <div class="sidebar-card-header"><span>🔥</span><h3 class="widget-title"><?php esc_html_e('Popular This Week','vitalstack'); ?></h3></div>
        <div class="sidebar-card-body">
          <?php
          $popular = new WP_Query(array('posts_per_page'=>4,'orderby'=>'comment_count','ignore_sticky_posts'=>1));
          if ($popular->have_posts()) : $pop_num=1; ?>
            <div class="pop-list">
              <?php while ($popular->have_posts()) : $popular->the_post(); ?>
                <div class="pop-item">
                  <span class="pop-num"><?php echo str_pad($pop_num++,2,'0',STR_PAD_LEFT); ?></span>
                  <div>
                    <div class="pop-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
                    <div class="pop-meta"><?php $pc=get_the_category(); $pcn=!empty($pc)?esc_html($pc[0]->name):''; echo $pcn.' · '.esc_html(vitalstack_read_time(get_the_ID())); ?></div>
                  </div>
                </div>
              <?php endwhile; wp_reset_postdata(); ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="widget sidebar-card">
        <div class="sidebar-card-header"><span>🏷️</span><h3 class="widget-title"><?php esc_html_e('Popular Tags','vitalstack'); ?></h3></div>
        <div class="sidebar-card-body">
          <div class="tag-cloud vs-tag-cloud">
            <?php wp_tag_cloud(array('smallest'=>12,'largest'=>14,'unit'=>'px','number'=>16,'format'=>'flat','separator'=>'','orderby'=>'count','order'=>'DESC')); ?>
          </div>
        </div>
      </div>

      <div class="widget sidebar-newsletter">
        <h3><?php esc_html_e('Stay Informed','vitalstack'); ?></h3>
        <p><?php esc_html_e('Get the best AI & health articles delivered weekly.','vitalstack'); ?></p>
        <?php if (shortcode_exists('mailchimp')) { echo do_shortcode('[mailchimp]'); } else { ?>
          <form class="sidebar-nl-form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
            <input type="hidden" name="action" value="vitalstack_newsletter_signup">
            <?php wp_nonce_field('vitalstack_newsletter','vs_nonce'); ?>
            <input class="nl-input" type="email" name="email" placeholder="<?php esc_attr_e('your@email.com','vitalstack'); ?>" required>
            <button type="submit" class="nl-btn"><?php esc_html_e('Subscribe Free →','vitalstack'); ?></button>
          </form>
        <?php } ?>
      </div>

    <?php endif; ?>
  </aside>

  <main id="primary" class="blog-main" role="main">

    <?php if (have_posts()) : ?>
      <?php
      $sticky_ids = get_option('sticky_posts');
      $shown_stickies = false;
      while (have_posts()) :
        the_post();
        $post_id   = get_the_ID();
        $cat_class = vitalstack_get_cat_class($post_id);
        $cat       = get_the_category($post_id);
        $cat_name  = !empty($cat) ? esc_html($cat[0]->name) : 'Article';
        $read_time = vitalstack_read_time($post_id);
        $author_id = get_post_field('post_author',$post_id);
        $initials  = vitalstack_author_initials($author_id);
        $is_sticky = in_array($post_id,$sticky_ids,true);
        $is_featured = $is_sticky && !$shown_stickies;
        if ($is_featured) $shown_stickies = true;
      ?>

        <?php if ($is_featured) : ?>
        <article id="post-<?php echo esc_attr($post_id); ?>" class="featured-post-card">
          <span class="featured-badge">⭐ <?php esc_html_e('Featured','vitalstack'); ?></span>
          <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>" class="featured-thumb-wrap" tabindex="-1" aria-hidden="true">
              <?php the_post_thumbnail('vitalstack-hero',array('class'=>'featured-thumb-img','alt'=>esc_attr(get_the_title()))); ?>
            </a>
          <?php else : ?>
            <div class="featured-thumb-wrap featured-thumb-placeholder <?php echo esc_attr($cat_class); ?>-bg" aria-hidden="true">
              <?php echo $cat_class==='health' ? '🌿' : '🤖'; ?>
            </div>
          <?php endif; ?>
          <div class="featured-body">
            <div class="post-meta">
              <span class="tag <?php echo esc_attr($cat_class); ?>"><?php echo $cat_name; ?></span>
              <span class="read-time-light">· <?php echo esc_html($read_time); ?></span>
            </div>
            <h2 class="featured-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p class="featured-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
            <div class="featured-footer">
              <div class="post-author-light">
                <span class="avatar"><?php echo get_avatar($author_id,24) ?: esc_html($initials); ?></span>
                <?php the_author(); ?>
                <span aria-hidden="true">·</span>
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('M j, Y')); ?></time>
              </div>
              <a href="<?php the_permalink(); ?>" class="read-link-light"><?php esc_html_e('Read article →','vitalstack'); ?></a>
            </div>
          </div>
        </article>

        <?php else : ?>
        <article id="post-<?php echo esc_attr($post_id); ?>" class="post-row">
          <a href="<?php the_permalink(); ?>" class="post-row-thumb-wrap" tabindex="-1" aria-hidden="true">
            <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('vitalstack-thumb',array('class'=>'post-row-thumb-img','alt'=>esc_attr(get_the_title()))); ?>
            <?php else : ?>
              <div class="post-row-thumb <?php echo esc_attr($cat_class); ?>-bg" aria-hidden="true">
                <?php echo $cat_class==='health' ? '🌿' : '🤖'; ?>
              </div>
            <?php endif; ?>
          </a>
          <div class="post-row-body">
            <div class="post-meta">
              <span class="tag <?php echo esc_attr($cat_class); ?>"><?php echo $cat_name; ?></span>
              <span class="read-time">· <?php echo esc_html($read_time); ?></span>
            </div>
            <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p class="post-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
            <div class="post-footer">
              <div class="post-author">
                <span class="avatar"><?php echo get_avatar($author_id,24) ?: esc_html($initials); ?></span>
                <?php the_author(); ?>
                <span aria-hidden="true">·</span>
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('M j, Y')); ?></time>
              </div>
              <a href="<?php the_permalink(); ?>" class="read-link-sm"><?php esc_html_e('Read article →','vitalstack'); ?></a>
            </div>
          </div>
        </article>
        <?php endif; ?>

      <?php endwhile; ?>

    <?php else : ?>
      <div class="no-posts-notice">
        <h2><?php esc_html_e('No articles found.','vitalstack'); ?></h2>
        <p><?php esc_html_e('Try a different filter or check back soon — new content is published weekly.','vitalstack'); ?></p>
        <?php get_search_form(); ?>
      </div>
    <?php endif; ?>

    <nav class="pagination-wrap" aria-label="<?php esc_attr_e('Posts pagination','vitalstack'); ?>">
      <?php the_posts_pagination(array(
        'mid_size'           => 2,
        'prev_text'          => '← '.__('Prev','vitalstack'),
        'next_text'          => __('Next','vitalstack').' →',
        'before_page_number' => '<span class="screen-reader-text">'.__('Page','vitalstack').' </span>',
      )); ?>
    </nav>

  </main>

  <!-- ── SIDEBAR ── -->

</div><!-- /.blog-layout -->
</main>
<?php get_footer(); ?>

<style id="vs-blog-styles">
/* ── Hero ── */
.blog-hero{background:var(--vs-navy);padding:60px 0;position:relative;overflow:hidden}
.blog-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 50% 100% at 90% 50%,rgba(29,52,97,.8) 0%,transparent 70%);pointer-events:none}
.page-header-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:48px 48px;pointer-events:none}
.ph-inner{position:relative}
.blog-hero-badge{display:inline-flex;align-items:center;gap:10px;background:rgba(29,52,97,.6);border:1px solid rgba(255,255,255,.12);border-radius:10px;padding:10px 18px;font-family:'Space Mono',monospace;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.6);margin-bottom:18px}
.blog-hero h1{font-family:'Playfair Display',serif;font-size:clamp(32px,5vw,52px);font-weight:900;color:var(--vs-white);margin:0 0 12px;line-height:1.1}
.ph-inner > p{font-size:16px;color:rgba(255,255,255,.55);max-width:540px;line-height:1.7;margin-bottom:28px}
.ph-meta{display:flex;align-items:center;gap:0;flex-wrap:wrap;padding-top:24px;border-top:1px solid rgba(255,255,255,.1)}
.ph-stat{font-size:14px;color:rgba(255,255,255,.55);padding-right:24px}
.ph-stat strong{color:var(--vs-white);font-weight:700}
.ph-stat-divider{width:1px;height:16px;background:rgba(255,255,255,.15);margin-right:24px;flex-shrink:0}

/* ── Tab bar ── */
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

/* ── Layout ── */
.blog-layout{display:grid;grid-template-columns:316px 1fr;gap:40px;padding-top:48px;padding-bottom:80px;align-items:start}

/* ── Featured post ── */
.featured-post-card{background:linear-gradient(135deg,var(--vs-navy) 0%,var(--vs-navy-mid) 100%);border-radius:var(--vs-radius);overflow:hidden;margin-bottom:20px;position:relative;box-shadow:var(--vs-card-shadow);opacity:0;transform:translateY(12px);animation:vsRowIn .5s ease .05s forwards}
.featured-badge{position:absolute;top:16px;left:16px;background:var(--vs-green);color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:100px;font-family:'Space Mono',monospace;letter-spacing:.06em;text-transform:uppercase;z-index:2}
.featured-thumb-wrap{display:block;overflow:hidden;max-height:280px}
.featured-thumb-img{width:100%;height:280px;object-fit:cover;display:block;transition:transform .4s}
.featured-post-card:hover .featured-thumb-img{transform:scale(1.04)}
.featured-thumb-placeholder{min-height:180px;display:flex;align-items:center;justify-content:center;font-size:72px}
.featured-body{padding:28px 32px;display:flex;flex-direction:column;gap:10px}
.featured-title{font-family:'Playfair Display',serif;font-size:clamp(20px,2vw,28px);font-weight:700;color:#fff;line-height:1.25;margin:4px 0}
.featured-title a{color:#fff}
.featured-title a:hover{color:var(--vs-green-light)}
.featured-excerpt{font-size:15px;color:rgba(255,255,255,.6);line-height:1.7}
.featured-footer{display:flex;align-items:center;justify-content:space-between;padding-top:16px;border-top:1px solid rgba(255,255,255,.12);flex-wrap:wrap;gap:8px;margin-top:auto}
.post-author-light{display:flex;align-items:center;gap:8px;font-size:13px;color:rgba(255,255,255,.65)}
.read-link-light{font-size:13px;font-weight:600;color:var(--vs-green-light);white-space:nowrap}
.read-link-light:hover{color:#fff}
.read-time-light{font-family:'Space Mono',monospace;font-size:10px;color:rgba(255,255,255,.45)}

/* ── Post rows ── */
.blog-main .post-row{display:grid;grid-template-columns:180px 1fr;background:var(--vs-white);border:1px solid var(--vs-border);border-radius:var(--vs-radius);overflow:hidden;box-shadow:var(--vs-card-shadow);transition:all .25s;cursor:pointer;opacity:0;transform:translateY(12px);animation:vsRowIn .5s ease forwards;margin-bottom:18px}
.blog-main .post-row:hover{transform:translateY(-3px);box-shadow:var(--vs-card-hover)}
@keyframes vsRowIn{to{opacity:1;transform:translateY(0)}}
.blog-main .post-row:nth-child(2){animation-delay:.08s}
.blog-main .post-row:nth-child(3){animation-delay:.14s}
.blog-main .post-row:nth-child(4){animation-delay:.20s}
.blog-main .post-row:nth-child(5){animation-delay:.26s}
.blog-main .post-row:nth-child(6){animation-delay:.32s}
.post-row-thumb-wrap{display:block;overflow:hidden}
.post-row-thumb-img{width:100%;height:100%;min-height:150px;object-fit:cover;display:block;transition:transform .4s}
.blog-main .post-row:hover .post-row-thumb-img{transform:scale(1.05)}
.post-row-thumb{width:100%;min-height:150px;display:flex;align-items:center;justify-content:center;font-size:42px}
.post-row-body{padding:20px;display:flex;flex-direction:column;justify-content:space-between}
.post-meta{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.post-title{font-family:'Playfair Display',serif;font-size:clamp(16px,1.5vw,19px);font-weight:700;color:var(--vs-navy);line-height:1.35;margin:8px 0}
.post-title a{color:inherit}
.blog-main .post-row:hover .post-title a{color:var(--vs-green)}
.post-excerpt{font-size:13px;color:var(--vs-muted);line-height:1.7;margin-bottom:12px;display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden}
.post-footer{display:flex;align-items:center;justify-content:space-between;padding-top:10px;border-top:1px solid var(--vs-border);flex-wrap:wrap;gap:6px}
.post-author{display:flex;align-items:center;gap:7px;font-size:12px;color:var(--vs-muted)}
.read-time{font-family:'Space Mono',monospace;font-size:10px;color:var(--vs-muted)}
.read-link-sm{font-size:12px;font-weight:600;color:var(--vs-green);white-space:nowrap}
.read-link-sm:hover{color:var(--vs-green-light)}
.tag{display:inline-flex;align-items:center;gap:5px;font-family:'Space Mono',monospace;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;border-radius:4px;padding:4px 10px}
.tag.ai{background:var(--vs-navy-light);color:var(--vs-navy)}
.tag.health{background:var(--vs-green-pale);color:var(--vs-green)}
.ai-bg{background:linear-gradient(135deg,#0d1b3e 0%,#1d3461 50%,#1a5c8a 100%)}
.health-bg{background:linear-gradient(135deg,#0d3320 0%,#1d8a4e 50%,#25b067 100%)}
.avatar{width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,var(--vs-green),var(--vs-navy-mid));display:inline-flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;color:#fff;flex-shrink:0;overflow:hidden}
.avatar img{width:100%;height:100%;object-fit:cover}

/* ── Sidebar ── */
.sidebar{position:sticky;top:128px;display:flex;flex-direction:column;gap:22px}
.sidebar-card{background:var(--vs-white);border:1px solid var(--vs-border);border-radius:var(--vs-radius);overflow:hidden}
.sidebar-card-header{padding:14px 18px;border-bottom:1px solid var(--vs-border);display:flex;align-items:center;gap:8px}
.sidebar-card-header h3,.sidebar-card-header .widget-title{font-family:'Playfair Display',serif;font-size:16px;font-weight:700;color:var(--vs-navy);margin:0}
.sidebar-card-header>span{font-size:17px}
.sidebar-card-body{padding:14px 16px}
.cat-tree{list-style:none;display:flex;flex-direction:column;gap:2px}
.cat-link{display:flex;align-items:center;justify-content:space-between;padding:9px 10px;border-radius:7px;cursor:pointer;transition:background .2s}
.cat-link:hover,.cat-link.active{background:var(--vs-navy-light);color:var(--vs-navy)}
.cat-left{display:flex;align-items:center;gap:9px;font-size:13px;font-weight:500;color:var(--vs-text)}
.cat-left a{font-size:13px;font-weight:500;color:var(--vs-text)}
.cat-left a:hover{color:var(--vs-green)}
.cat-icon{font-size:18px}
.cat-right{display:flex;align-items:center;gap:6px}
.cat-count{font-family:'Space Mono',monospace;font-size:10px;background:var(--vs-off-white);border:1px solid var(--vs-border);border-radius:4px;padding:2px 6px;color:var(--vs-muted)}
.cat-toggle-btn{background:none;border:none;cursor:pointer;font-size:10px;color:var(--vs-muted);transition:transform .25s;padding:2px}
.cat-toggle-btn.open{transform:rotate(90deg)}
.cat-children{list-style:none;margin-left:14px;border-left:2px solid var(--vs-border);padding-left:10px;display:none;flex-direction:column;gap:2px;margin-top:2px;margin-bottom:4px}
.cat-children.open{display:flex}
.cat-child-link{display:flex;align-items:center;justify-content:space-between;padding:7px 8px;border-radius:6px;font-size:12px;color:var(--vs-muted);cursor:pointer;transition:all .2s}
.cat-child-link:hover{background:var(--vs-off-white);color:var(--vs-text)}
.cat-child-link a{color:var(--vs-muted);font-size:12px}
.cat-child-link a:hover{color:var(--vs-green)}
.sub-count{font-size:11px;color:var(--vs-border)}
.pop-list{display:flex;flex-direction:column;gap:12px}
.pop-item{display:flex;gap:10px;align-items:flex-start;cursor:pointer;padding:9px;border-radius:7px;transition:background .2s}
.pop-item:hover{background:var(--vs-off-white)}
.pop-num{font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:var(--vs-border);line-height:1;flex-shrink:0;width:22px}
.pop-title{font-size:12px;font-weight:600;color:var(--vs-navy);line-height:1.4;margin-bottom:3px}
.pop-item:hover .pop-title{color:var(--vs-green)}
.pop-title a{color:inherit}
.pop-meta{font-size:11px;color:var(--vs-muted)}
.vs-tag-cloud{display:flex;flex-wrap:wrap;gap:7px}
.vs-tag-cloud a{display:inline-flex;padding:5px 11px;border-radius:50px;font-size:11px!important;font-weight:500;border:1.5px solid var(--vs-border);color:var(--vs-muted);cursor:pointer;transition:all .2s}
.vs-tag-cloud a:hover{border-color:var(--vs-navy);color:var(--vs-navy);background:var(--vs-navy-light)}
.sidebar-newsletter{background:var(--vs-navy);border-radius:var(--vs-radius);padding:22px;text-align:center}
.sidebar-newsletter h3{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:#fff;margin-bottom:7px}
.sidebar-newsletter p{font-size:12px;color:rgba(255,255,255,.5);line-height:1.6;margin-bottom:14px}
.sidebar-nl-form{display:flex;flex-direction:column;gap:10px}
.nl-input{width:100%;padding:10px 13px;border:none;border-radius:6px;font-size:13px;font-family:'DM Sans',sans-serif;outline:none}
.nl-btn{width:100%;padding:10px;background:var(--vs-green);color:#fff;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer}
.nl-btn:hover{background:var(--vs-green-light)}

/* ── Pagination ── */
.pagination-wrap{margin:36px 0}
.pagination-wrap .nav-links{display:flex;gap:8px;flex-wrap:wrap;align-items:center;justify-content:center}
.pagination-wrap .page-numbers{display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 12px;border-radius:7px;border:1.5px solid var(--vs-border);font-size:13px;font-weight:600;color:var(--vs-muted);transition:all .2s}
.pagination-wrap .page-numbers:hover,.pagination-wrap .page-numbers.current{background:var(--vs-navy);color:#fff;border-color:var(--vs-navy)}
.pagination-wrap .page-numbers.dots{border-color:transparent;background:transparent;cursor:default}

/* ── No posts ── */
.no-posts-notice{background:var(--vs-white);border:1px solid var(--vs-border);border-radius:var(--vs-radius);padding:48px 32px;text-align:center}
.no-posts-notice h2{font-family:'Playfair Display',serif;color:var(--vs-navy);margin-bottom:8px}
.no-posts-notice p{color:var(--vs-muted);margin-bottom:20px}

/* ── Responsive ── */
@media(max-width:1024px){.blog-layout{grid-template-columns:1fr}.sidebar{position:static;top:auto}}
@media(max-width:640px){.blog-main .post-row{grid-template-columns:1fr}.post-row-thumb-img{height:180px;min-height:180px}.featured-body{padding:20px}.blog-filter-bar{top:0}}
</style>

<script>
document.querySelectorAll('.cat-toggle-btn').forEach(function(btn){
  btn.addEventListener('click',function(e){
    e.preventDefault();
    var item=this.closest('.cat-item');
    var children=item?item.querySelector('.cat-children'):null;
    if(children){children.classList.toggle('open');this.classList.toggle('open');}
  });
});
function vitalstackSort(value){
  var url=new URL(window.location.href);
  if(value==='date_asc'){url.searchParams.set('orderby','date');url.searchParams.set('order','ASC');}
  else{url.searchParams.set('orderby',value);url.searchParams.set('order','DESC');}
  window.location.href=url.toString();
}
</script>
