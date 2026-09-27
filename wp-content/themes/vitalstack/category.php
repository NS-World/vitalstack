<?php
/**
 * category.php — Category Archive Template
 *
 * Sections:
 *   1. Category hero (name, description, stats, icon)
 *   2. Subcategory underline tab bar
 *   3. Top-3 featured grid + post rows + pagination
 *   4. Sidebar: search, all categories tree, popular in cat, tags, newsletter
 *
 * @package VitalStack
 */

get_header();

$cat_obj   = get_queried_object();
$cat_id    = $cat_obj->term_id;
$cat_name  = $cat_obj->name;
$cat_slug  = $cat_obj->slug;
$cat_desc  = $cat_obj->description;
$cat_count = $cat_obj->count;
$cat_class = (strpos(strtolower($cat_slug),'health')!==false) ? 'health' : 'ai';
$cat_icon  = get_term_meta($cat_id,'category_icon',true);
if (!$cat_icon) $cat_icon = ($cat_class==='health') ? '🌿' : '🤖';

$sub_cats   = get_categories(array('parent'=>$cat_id,'hide_empty'=>false));
$active_sub = isset($_GET['subcat']) ? absint($_GET['subcat']) : 0;
$sub_count  = count($sub_cats);
?>

<main id="main" class="site-main category-page">

<!-- ══ 1. CATEGORY HERO ══════════════════════════════════════════════════ -->
<section class="cat-hero" aria-label="<?php echo esc_attr($cat_name); ?> category">
  <div class="cat-hero-grid" aria-hidden="true"></div>
  <div class="container cat-hero-inner">

    <div class="cat-hero-content">
      <div class="ph-breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home','vitalstack'); ?></a>
        <span>/</span>
        <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>"><?php esc_html_e('Blog','vitalstack'); ?></a>
        <span>/</span>
        <span style="color:rgba(255,255,255,.7)"><?php echo esc_html($cat_name); ?></span>
      </div>

      <h1 class="cat-hero-title"><?php echo esc_html($cat_name); ?></h1>

      <?php if ($cat_desc) : ?>
        <!-- ===========Nirav Code below =====<p class="cat-hero-desc"><?php echo esc_html($cat_desc); ?></p> -->
        <p class="cat-hero-desc"><?php echo wp_kses_post($cat_desc); ?></p>
      <?php else : ?>
        <p class="cat-hero-desc">
          <?php printf(esc_html__('Browse all articles in %s — research-backed, expert-written, always relevant.','vitalstack'),esc_html($cat_name)); ?>
        </p>
      <?php endif; ?>

      <div class="cat-stats">
        <div>
          <div class="cat-stat-num"><?php echo esc_html($cat_count); ?></div>
          <div class="cat-stat-label"><?php esc_html_e('Articles','vitalstack'); ?></div>
        </div>
        <?php if ($sub_count>0) : ?>
          <div>
            <div class="cat-stat-num"><?php echo esc_html($sub_count); ?></div>
            <div class="cat-stat-label"><?php esc_html_e('Sub-categories','vitalstack'); ?></div>
          </div>
        <?php endif; ?>
        <div>
          <div class="cat-stat-num"><?php esc_html_e('14k','vitalstack'); ?></div>
          <div class="cat-stat-label"><?php esc_html_e('Monthly Readers','vitalstack'); ?></div>
        </div>
      </div>
    </div>

    <div class="cat-hero-icon" aria-hidden="true"><?php echo esc_html($cat_icon); ?></div>

  </div>
</section>

<!-- ══ 2. SUBCATEGORY TABS ════════════════════════════════════════════════ -->
<?php if (!empty($sub_cats)) : ?>
  <div class="subcat-bar" role="navigation" aria-label="<?php esc_attr_e('Filter by sub-category','vitalstack'); ?>">
    <div class="container">
      <div class="subcat-inner">
        <a href="<?php echo esc_url(get_category_link($cat_id)); ?>"
           class="subcat-tab <?php echo !$active_sub ? 'active' : ''; ?>">
          <?php esc_html_e('All','vitalstack'); ?>
          <span class="subcat-count"><?php echo esc_html($cat_count); ?></span>
        </a>
        <?php foreach ($sub_cats as $sub) :
          $sub_url   = add_query_arg('subcat',$sub->term_id,get_category_link($cat_id));
          $is_active = ($active_sub===$sub->term_id);
        ?>
          <a href="<?php echo esc_url($sub_url); ?>"
             class="subcat-tab <?php echo $is_active ? 'active' : ''; ?>">
            <?php echo esc_html($sub->name); ?>
            <span class="subcat-count"><?php echo esc_html($sub->count); ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- ══ 3+4. MAIN LAYOUT ══════════════════════════════════════════════════ -->
<div class="container blog-layout" style="padding-top:48px;padding-bottom:80px;">

  <aside id="secondary" class="sidebar" role="complementary" aria-label="<?php esc_attr_e('Category sidebar','vitalstack'); ?>">

    <?php if (is_active_sidebar('sidebar-category')) : ?>
      <?php dynamic_sidebar('sidebar-category'); ?>
    <?php else : ?>

      <div class="widget sidebar-card">
        <div class="sidebar-card-header"><span>🔍</span><h3 class="widget-title"><?php esc_html_e('Search Articles','vitalstack'); ?></h3></div>
        <div class="sidebar-card-body"><?php get_search_form(); ?></div>
      </div>

      <div class="widget sidebar-card">
        <div class="sidebar-card-header"><span>📂</span><h3 class="widget-title"><?php esc_html_e('All Categories','vitalstack'); ?></h3></div>
        <div class="sidebar-card-body" style="padding:10px 14px;">
          <ul class="cat-tree">
            <?php
            $top_cats = get_categories(array('hide_empty'=>true,'parent'=>0));
            foreach ($top_cats as $top) :
              $top_subs   = get_categories(array('hide_empty'=>true,'parent'=>$top->term_id));
              $top_icon   = (strpos(strtolower($top->slug),'health')!==false) ? '🌿' : '🤖';
              $is_current = ($top->term_id===$cat_id);
              $is_health  = (strpos(strtolower($top->slug),'health')!==false);
            ?>
              <li class="cat-item">
                <div class="cat-link <?php echo $is_current?'active':''; ?> <?php echo $is_health?'health-link':''; ?>">
                  <div class="cat-left">
                    <span class="cat-icon" aria-hidden="true"><?php echo $top_icon; ?></span>
                    <a href="<?php echo esc_url(get_category_link($top->term_id)); ?>"><?php echo esc_html($top->name); ?></a>
                  </div>
                  <div class="cat-right">
                    <span class="cat-count"><?php echo esc_html($top->count); ?></span>
                    <?php if (!empty($top_subs)) : ?>
                      <button class="cat-toggle-btn <?php echo $is_current?'open':''; ?>" aria-label="<?php esc_attr_e('Toggle sub-categories','vitalstack'); ?>">▶</button>
                    <?php endif; ?>
                  </div>
                </div>
                <?php if (!empty($top_subs)) : ?>
                  <ul class="cat-children <?php echo $is_current?'open':''; ?>">
                    <?php foreach ($top_subs as $sub) :
                      $sub_active = ($sub->term_id===$cat_id) || ($active_sub===$sub->term_id);
                    ?>
                      <li>
                        <div class="cat-child-link <?php echo $sub_active?'current':''; ?>">
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
        <div class="sidebar-card-header">
          <span>🔥</span>
          <h3 class="widget-title"><?php printf(esc_html__('Popular in %s','vitalstack'),esc_html($cat_name)); ?></h3>
        </div>
        <div class="sidebar-card-body">
          <?php
          $popular_in_cat = new WP_Query(array('posts_per_page'=>4,'category__in'=>array($cat_id),'orderby'=>'comment_count','ignore_sticky_posts'=>1));
          if ($popular_in_cat->have_posts()) : $pop_n=1; ?>
            <div class="pop-list">
              <?php while ($popular_in_cat->have_posts()) : $popular_in_cat->the_post(); ?>
                <div class="pop-item">
                  <span class="pop-num"><?php echo str_pad($pop_n++,2,'0',STR_PAD_LEFT); ?></span>
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
        <div class="sidebar-card-header">
          <span>🏷️</span>
          <h3 class="widget-title"><?php printf(esc_html__('%s Tags','vitalstack'),esc_html($cat_name)); ?></h3>
        </div>
        <div class="sidebar-card-body">
          <div class="tag-cloud vs-tag-cloud">
            <?php
            $cat_tags = get_tags(array('number'=>12,'orderby'=>'count','order'=>'DESC'));
            foreach ($cat_tags as $tag) : ?>
              <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"><?php echo esc_html($tag->name); ?></a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <div class="widget sidebar-newsletter">
        <h3><?php printf(esc_html__('%s Updates Weekly','vitalstack'),esc_html($cat_name)); ?></h3>
        <p><?php esc_html_e('Get the best articles straight to your inbox.','vitalstack'); ?></p>
        <?php if (shortcode_exists('mailchimp')) : echo do_shortcode('[mailchimp]'); else : ?>
          <form class="sidebar-nl-form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
            <input type="hidden" name="action" value="vitalstack_newsletter_signup">
            <?php wp_nonce_field('vitalstack_newsletter','vs_nonce'); ?>
            <input class="nl-input" type="email" name="email" placeholder="<?php esc_attr_e('your@email.com','vitalstack'); ?>" required>
            <button type="submit" class="nl-btn"><?php esc_html_e('Subscribe Free →','vitalstack'); ?></button>
          </form>
        <?php endif; ?>
      </div>

    <?php endif; ?>
  </aside>

  <main id="primary" class="blog-main" role="main">

    <?php if (have_posts()) : ?>
      <?php
      $all_posts = array();
      while (have_posts()) { the_post(); $all_posts[] = get_the_ID(); }
      $top_posts  = array_slice($all_posts,0,3);
      $more_posts = array_slice($all_posts,3);
      ?>

      <!-- ── Top 3 featured grid ── -->
      <div class="top-posts-grid">
        <?php foreach ($top_posts as $idx => $pid) :
          $p          = get_post($pid);
          $p_cat      = get_the_category($pid);
          $p_cat_name = !empty($p_cat) ? esc_html($p_cat[0]->name) : $cat_name;
          $p_class    = vitalstack_get_cat_class($pid);
          $p_time     = vitalstack_read_time($pid);
          $p_author_id = $p->post_author;
          $p_initials = vitalstack_author_initials($p_author_id);
          $is_main    = ($idx===0);
        ?>
          <a href="<?php echo esc_url(get_permalink($pid)); ?>"
             class="top-card <?php echo $is_main ? 'main' : ''; ?>"
             aria-label="<?php echo esc_attr(get_the_title($pid)); ?>">

            <?php if (has_post_thumbnail($pid)) : ?>
              <?php echo get_the_post_thumbnail($pid,$is_main?'vitalstack-card':'vitalstack-thumb',array('class'=>'top-card-thumb-img','alt'=>esc_attr(get_the_title($pid)))); ?>
            <?php else : ?>
              <div class="top-card-thumb <?php echo esc_attr($p_class); ?>-bg" aria-hidden="true">
                <?php echo esc_html($cat_icon); ?>
              </div>
            <?php endif; ?>

            <div class="top-card-body">
              <div class="post-meta">
                <span class="tag <?php echo esc_attr($p_class); ?>"><?php echo $p_cat_name; ?></span>
                <span class="read-time">· <?php echo esc_html($p_time); ?></span>
              </div>
              <h2 class="top-title"><?php echo esc_html(get_the_title($pid)); ?></h2>
              <?php if ($is_main) : ?>
                <p class="top-excerpt"><?php echo esc_html(get_the_excerpt($pid)); ?></p>
              <?php endif; ?>
              <div class="top-footer">
                <div class="post-author-sm">
                  <span class="avatar"><?php echo get_avatar($p_author_id,24) ?: esc_html($p_initials); ?></span>
                  <?php echo esc_html(get_the_author_meta('display_name',$p_author_id)); ?>
                  <?php if ($is_main) : ?>
                    <span aria-hidden="true">·</span>
                    <time datetime="<?php echo esc_attr(get_the_date('c',$pid)); ?>"><?php echo esc_html(get_the_date('M j, Y',$pid)); ?></time>
                  <?php endif; ?>
                </div>
                <span class="read-link"><?php esc_html_e('Read →','vitalstack'); ?></span>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- ── More articles ── -->
      <?php if (!empty($more_posts)) : ?>
        <div class="section-label">
          <span><?php esc_html_e('More Articles','vitalstack'); ?></span>
        </div>

        <div class="post-list">
          <?php foreach ($more_posts as $pid) :
            $p          = get_post($pid);
            $p_cat      = get_the_category($pid);
            $p_cat_name = !empty($p_cat) ? esc_html($p_cat[0]->name) : $cat_name;
            $p_class    = vitalstack_get_cat_class($pid);
            $p_time     = vitalstack_read_time($pid);
            $p_author_id = $p->post_author;
            $p_initials = vitalstack_author_initials($p_author_id);
          ?>
            <article class="post-row" id="post-<?php echo esc_attr($pid); ?>">
              <a href="<?php echo esc_url(get_permalink($pid)); ?>" class="post-row-thumb-wrap" tabindex="-1" aria-hidden="true">
                <?php if (has_post_thumbnail($pid)) : ?>
                  <?php echo get_the_post_thumbnail($pid,'vitalstack-thumb',array('class'=>'post-row-thumb-img','alt'=>esc_attr(get_the_title($pid)))); ?>
                <?php else : ?>
                  <div class="post-row-thumb <?php echo esc_attr($p_class); ?>-bg" aria-hidden="true">
                    <?php echo esc_html($cat_icon); ?>
                  </div>
                <?php endif; ?>
              </a>
              <div class="post-row-body">
                <div class="post-meta">
                  <span class="tag <?php echo esc_attr($p_class); ?>"><?php echo $p_cat_name; ?></span>
                  <span class="read-time">· <?php echo esc_html($p_time); ?></span>
                </div>
                <h2 class="post-title">
                  <a href="<?php echo esc_url(get_permalink($pid)); ?>"><?php echo esc_html(get_the_title($pid)); ?></a>
                </h2>
                <p class="post-excerpt"><?php echo esc_html(get_the_excerpt($pid)); ?></p>
                <div class="post-footer">
                  <div class="post-author">
                    <span class="avatar"><?php echo get_avatar($p_author_id,24) ?: esc_html($p_initials); ?></span>
                    <?php echo esc_html(get_the_author_meta('display_name',$p_author_id)); ?>
                    <span aria-hidden="true">·</span>
                    <time datetime="<?php echo esc_attr(get_the_date('c',$pid)); ?>"><?php echo esc_html(get_the_date('M j, Y',$pid)); ?></time>
                  </div>
                  <a href="<?php echo esc_url(get_permalink($pid)); ?>" class="read-link-sm"><?php esc_html_e('Read article →','vitalstack'); ?></a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    <?php else : ?>
      <div class="no-posts-notice">
        <h2><?php printf(esc_html__('No articles in %s yet.','vitalstack'),esc_html($cat_name)); ?></h2>
        <p><?php esc_html_e('Check back soon — new content is published every week.','vitalstack'); ?></p>
        <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-primary" style="margin-top:16px;">
          <?php esc_html_e('← Browse All Articles','vitalstack'); ?>
        </a>
      </div>
    <?php endif; ?>

    <nav class="pagination-wrap" aria-label="<?php esc_attr_e('Category pagination','vitalstack'); ?>">
      <?php the_posts_pagination(array('mid_size'=>2,'prev_text'=>'← '.__('Prev','vitalstack'),'next_text'=>__('Next','vitalstack').' →')); ?>
    </nav>

  </main>

  <!-- ── SIDEBAR ── -->

</div>
</main>
<?php get_footer(); ?>

<style id="vs-category-styles">
/* ── Category hero ── */
.cat-hero{background:var(--vs-navy);padding:60px 0;position:relative;overflow:hidden}
.cat-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 50% 100% at 90% 50%,rgba(29,52,97,.8) 0%,transparent 70%);pointer-events:none}
.cat-hero-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:48px 48px;pointer-events:none}
.cat-hero-inner{position:relative;display:grid;grid-template-columns:1fr auto;align-items:center;gap:40px}
.ph-breadcrumb{display:flex;align-items:center;gap:8px;font-size:13px;color:rgba(255,255,255,.4);margin-bottom:16px;font-family:'Space Mono',monospace}
.ph-breadcrumb a{color:rgba(255,255,255,.4);transition:color .2s}
.ph-breadcrumb a:hover{color:var(--vs-green-light)}
.cat-badge-lg{display:inline-flex;align-items:center;gap:10px;background:rgba(29,52,97,.6);border:1px solid rgba(255,255,255,.12);border-radius:10px;padding:10px 18px;font-family:'Space Mono',monospace;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.6);margin-bottom:18px}
.cat-hero-title{font-family:'Playfair Display',serif;font-size:clamp(34px,4vw,52px);font-weight:900;color:#fff;margin-bottom:14px;line-height:1.1}
.cat-hero-desc{font-size:16px;color:rgba(255,255,255,.55);max-width:540px;line-height:1.7;margin-bottom:28px}
.cat-stats{display:flex;gap:28px}
.cat-stat-num{font-family:'Playfair Display',serif;font-size:28px;font-weight:700;color:#fff;line-height:1}
.cat-stat-label{font-size:12px;color:rgba(255,255,255,.4);margin-top:3px}
.cat-hero-icon{font-size:100px;opacity:.15;line-height:1;user-select:none;pointer-events:none;flex-shrink:0}

/* ── Subcategory tab bar (underline style) ── */
.subcat-bar{background:var(--vs-white);border-bottom:1px solid var(--vs-border);position:sticky;top:68px;z-index:90}
.subcat-inner{display:flex;align-items:center;gap:4px;overflow-x:auto;scrollbar-width:none}
.subcat-inner::-webkit-scrollbar{display:none}
.subcat-tab{padding:16px 20px;font-size:14px;font-weight:500;color:var(--vs-muted);cursor:pointer;border-bottom:3px solid transparent;white-space:nowrap;transition:all .2s;display:flex;align-items:center;gap:7px;text-decoration:none;flex-shrink:0}
.subcat-tab:hover{color:var(--vs-navy)}
.subcat-tab.active{color:var(--vs-navy);border-color:var(--vs-navy);font-weight:600}
.subcat-count{font-family:'Space Mono',monospace;font-size:10px;background:var(--vs-navy-light);color:var(--vs-navy);border-radius:4px;padding:2px 7px}

/* ── Blog layout: 2-col ── */
.blog-layout{display:grid;grid-template-columns:316px 1fr;gap:40px;align-items:start}

/* ── Top 3 posts grid ── */
.top-posts-grid{display:grid;grid-template-columns:1.6fr 1fr;grid-template-rows:auto auto;gap:20px;margin-bottom:36px}
.top-card{background:var(--vs-white);border:1px solid var(--vs-border);border-radius:var(--vs-radius);overflow:hidden;cursor:pointer;transition:all .25s;display:flex;flex-direction:column;text-decoration:none;opacity:0;transform:translateY(14px);animation:vsCatCardIn .5s ease forwards}
.top-card:hover{transform:translateY(-4px);box-shadow:var(--vs-card-hover)}
@keyframes vsCatCardIn{to{opacity:1;transform:translateY(0)}}
.top-card:nth-child(1){animation-delay:.05s}
.top-card:nth-child(2){animation-delay:.12s}
.top-card:nth-child(3){animation-delay:.19s}
.top-card.main{grid-row:1/3}
.top-card-thumb{display:flex;align-items:center;justify-content:center;font-size:52px}
.top-card.main .top-card-thumb{height:240px}
.top-card:not(.main) .top-card-thumb{height:130px;font-size:36px}
.top-card-thumb-img{width:100%;object-fit:cover;display:block;transition:transform .4s}
.top-card.main .top-card-thumb-img{height:240px}
.top-card:not(.main) .top-card-thumb-img{height:130px}
.top-card:hover .top-card-thumb-img{transform:scale(1.04)}
.top-card-body{padding:20px;flex:1;display:flex;flex-direction:column}
.post-meta{display:flex;align-items:center;gap:10px;margin-bottom:10px;flex-wrap:wrap}
.tag{display:inline-flex;align-items:center;gap:5px;font-family:'Space Mono',monospace;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;border-radius:4px;padding:4px 10px}
.tag.ai{background:var(--vs-navy-light);color:var(--vs-navy)}
.tag.health{background:var(--vs-green-pale);color:var(--vs-green)}
.read-time{font-family:'Space Mono',monospace;font-size:10px;color:var(--vs-muted)}
.top-title{font-family:'Playfair Display',serif;font-size:19px;font-weight:700;line-height:1.3;color:var(--vs-navy);margin-bottom:8px;transition:color .2s}
.top-card.main .top-title{font-size:22px}
.top-card:hover .top-title{color:var(--vs-green)}
.top-excerpt{font-size:13px;color:var(--vs-muted);line-height:1.7;flex:1;margin-bottom:14px}
.top-footer{display:flex;align-items:center;justify-content:space-between;padding-top:12px;border-top:1px solid var(--vs-border);font-size:12px;color:var(--vs-muted);margin-top:auto}
.post-author-sm{display:flex;align-items:center;gap:7px}
.read-link{font-weight:600;color:var(--vs-green);font-size:12px;white-space:nowrap}
.read-link:hover{color:var(--vs-green-light)}

/* ── Section divider ── */
.section-label{display:flex;align-items:center;gap:14px;margin-bottom:20px}
.section-label span{font-family:'Space Mono',monospace;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:var(--vs-muted)}
.section-label::after{content:'';flex:1;height:1px;background:var(--vs-border)}

/* ── Post rows ── */
.post-list{display:flex;flex-direction:column;gap:18px}
.blog-main .post-row{display:grid;grid-template-columns:180px 1fr;background:var(--vs-white);border:1px solid var(--vs-border);border-radius:var(--vs-radius);overflow:hidden;cursor:pointer;transition:all .25s;opacity:0;transform:translateY(12px);animation:vsCatRowIn .5s ease forwards}
.blog-main .post-row:hover{transform:translateY(-3px);box-shadow:var(--vs-card-hover)}
@keyframes vsCatRowIn{to{opacity:1;transform:translateY(0)}}
.blog-main .post-list .post-row:nth-child(1){animation-delay:.05s}
.blog-main .post-list .post-row:nth-child(2){animation-delay:.12s}
.blog-main .post-list .post-row:nth-child(3){animation-delay:.19s}
.post-row-thumb-wrap{display:block;overflow:hidden}
.post-row-thumb-img{width:100%;height:100%;min-height:150px;object-fit:cover;display:block;transition:transform .4s}
.blog-main .post-row:hover .post-row-thumb-img{transform:scale(1.05)}
.post-row-thumb{min-height:150px;display:flex;align-items:center;justify-content:center;font-size:42px}
.post-row-body{padding:20px;display:flex;flex-direction:column;justify-content:space-between}
.post-title{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;line-height:1.35;color:var(--vs-navy);margin-bottom:8px;transition:color .2s}
.post-title a{color:inherit}
.blog-main .post-row:hover .post-title a{color:var(--vs-green)}
.post-excerpt{font-size:13px;color:var(--vs-muted);line-height:1.7;margin-bottom:12px}
.post-footer{display:flex;align-items:center;justify-content:space-between;padding-top:10px;border-top:1px solid var(--vs-border);flex-wrap:wrap;gap:6px}
.post-author{display:flex;align-items:center;gap:7px;font-size:12px;color:var(--vs-muted)}
.read-link-sm{font-size:12px;font-weight:600;color:var(--vs-green);white-space:nowrap}
.read-link-sm:hover{color:var(--vs-green-light)}
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
.cat-link{display:flex;align-items:center;justify-content:space-between;padding:9px 10px;border-radius:7px;cursor:pointer;transition:background .2s;user-select:none}
.cat-link:hover,.cat-link.active{background:var(--vs-navy-light);color:var(--vs-navy);font-weight:600}
.cat-link.health-link:hover,.cat-link.health-active{background:var(--vs-green-pale);color:var(--vs-green)}
.cat-left{display:flex;align-items:center;gap:9px;font-size:13px;font-weight:500;color:var(--vs-text)}
.cat-left a{font-size:13px;font-weight:500;color:var(--vs-text)}
.cat-left a:hover{color:var(--vs-green)}
.cat-icon{font-size:18px}
.cat-right{display:flex;align-items:center;gap:6px}
.cat-count{font-family:'Space Mono',monospace;font-size:10px;background:var(--vs-off-white);border:1px solid var(--vs-border);border-radius:4px;padding:2px 6px;color:var(--vs-muted)}
.cat-toggle-btn{background:none;border:none;cursor:pointer;font-size:11px;color:var(--vs-muted);transition:transform .3s;padding:2px}
.cat-toggle-btn.open{transform:rotate(90deg)}
.cat-children{list-style:none;margin-left:14px;border-left:2px solid var(--vs-border);padding-left:10px;display:none;flex-direction:column;gap:2px;margin-top:2px;margin-bottom:4px}
.cat-children.open{display:flex}
.cat-child-link{display:flex;align-items:center;justify-content:space-between;padding:7px 8px;border-radius:6px;font-size:12px;color:var(--vs-muted);cursor:pointer;transition:all .2s}
.cat-child-link:hover{background:var(--vs-off-white);color:var(--vs-text)}
.cat-child-link.current{background:var(--vs-navy-light);color:var(--vs-navy);font-weight:600}
.cat-child-link a{color:inherit;font-size:12px}
.sub-count{font-size:10px;color:var(--vs-muted)}
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

/* Responsive */
@media(max-width:1024px){.blog-layout{grid-template-columns:1fr}.sidebar{position:static;top:auto}}
@media(max-width:768px){.cat-hero-icon{display:none}.cat-hero-inner{grid-template-columns:1fr}.top-posts-grid{grid-template-columns:1fr}.top-card.main{grid-row:auto}.top-card.main .top-card-thumb-img,.top-card:not(.main) .top-card-thumb-img{height:200px}.subcat-bar{top:0}}
@media(max-width:640px){.blog-main .post-row{grid-template-columns:1fr}.post-row-thumb-img{height:180px;min-height:180px}.cat-hero{padding:40px 0 32px}.cat-stats{gap:20px}}
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
</script>
