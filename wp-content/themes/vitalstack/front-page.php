<?php
/**
 * Front Page Template — VitalStack Homepage
 * Assigned via: Settings → Reading → "A static page" → Front page
 *
 * Sections:
 *   1. Hero (#hero)
 *   2. About / Mission teaser (#about)
 *   3. Top AI Articles (#ai-section)
 *   4. Top Health Articles (#health-section)
 *   5. Newsletter CTA (#newsletter)
 *
 * @package VitalStack
 */

get_header();
?>

<main id="main" class="site-main">

<!-- ══════════════════════════════════════════════════════════════════════════
     SECTION 1 — HERO
     Text: Customizer → VitalStack → Homepage Settings
     Featured post: mark any post as "sticky" in WordPress to auto-pull it here
═══════════════════════════════════════════════════════════════════════════ -->
<section class="vs-hero" id="hero" aria-label="<?php esc_attr_e( 'Hero', 'vitalstack' ); ?>">
  <div class="hero-bg" aria-hidden="true"></div>
  <div class="hero-grid-overlay" aria-hidden="true"></div>

  <div class="container hero-inner">

    <!-- ── Left Column — Headline & CTAs ── -->
    <div class="hero-left">

      <p class="hero-kicker">
        <?php echo esc_html( get_theme_mod( 'vitalstack_hero_kicker', 'Your intelligence edge' ) ); ?>
      </p>

      <h1>
        <?php
        // Allow <em> in the H1 (set from Customizer)
        echo wp_kses(
          get_theme_mod( 'vitalstack_hero_h1', 'Where <em>AI&nbsp;Meets</em><br>Human&nbsp;Health' ),
          array( 'em' => array(), 'br' => array(), 'strong' => array() )
        );
        ?>
      </h1>

      <p class="hero-desc">
        <?php echo esc_html( get_theme_mod( 'vitalstack_hero_desc', 'Deep-dive articles, research breakdowns, and expert analysis at the intersection of artificial intelligence and modern healthcare written for curious minds.' ) ); ?>
      </p>

      <div class="hero-actions">
        <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"
           class="btn btn-primary">
          <?php esc_html_e( 'Explore Articles', 'vitalstack' ); ?>
        </a>
        <a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"
           class="btn btn-ghost">
          <?php esc_html_e( 'Contact Us', 'vitalstack' ); ?>
        </a>
      </div>

      <!-- Stats bar — edit via Customizer → Homepage — Hero Stats -->
      <div class="hero-stats">
        <div class="hero-stat">
          <span class="stat-num"><?php echo esc_html( get_theme_mod( 'vitalstack_hero_stat1_num', '240+' ) ); ?></span>
          <span class="stat-label"><?php echo esc_html( get_theme_mod( 'vitalstack_hero_stat1_label', 'Published Articles' ) ); ?></span>
        </div>
        <div class="hero-stat">
          <span class="stat-num"><?php echo esc_html( get_theme_mod( 'vitalstack_hero_stat2_num', '18k' ) ); ?></span>
          <span class="stat-label"><?php echo esc_html( get_theme_mod( 'vitalstack_hero_stat2_label', 'Monthly Readers' ) ); ?></span>
        </div>
        <div class="hero-stat">
          <span class="stat-num"><?php echo esc_html( get_theme_mod( 'vitalstack_hero_stat3_num', '2' ) ); ?></span>
          <span class="stat-label"><?php echo esc_html( get_theme_mod( 'vitalstack_hero_stat3_label', 'Expert Categories' ) ); ?></span>
        </div>
      </div>
    </div><!-- /.hero-left -->

    <!-- ── Right Column — Featured Post Card ── -->
    <div class="hero-right" aria-label="<?php esc_attr_e( 'Featured article', 'vitalstack' ); ?>">
      <?php
      // Pull the newest sticky post. If none is sticky, fall back to latest post.
      $sticky_ids = get_option( 'sticky_posts' );
      $featured_args = array(
        'posts_per_page' => 1,
        'post__in'       => ! empty( $sticky_ids ) ? $sticky_ids : array( 0 ),
        'orderby'        => 'date',
        'order'          => 'DESC',
        'ignore_sticky_posts' => 1,
      );
      $featured_query = new WP_Query( $featured_args );

      // If no sticky post exists, just get the latest post
      if ( ! $featured_query->have_posts() ) {
        $featured_query = new WP_Query( array( 'posts_per_page' => 1 ) );
      }

      if ( $featured_query->have_posts() ) :
        $featured_query->the_post();
        $cat        = get_the_category();
        $cat_name   = ! empty( $cat ) ? esc_html( $cat[0]->name ) : 'Article';
        $cat_class  = vitalstack_get_cat_class( get_the_ID() );
        $read_time  = vitalstack_read_time( get_the_ID() );
        $author_id  = get_the_author_meta( 'ID' );
        $initials   = vitalstack_author_initials( $author_id );
      ?>
      <article class="hero-featured">
        <?php if ( has_post_thumbnail() ) : ?>
          <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'vitalstack-hero', array( 'class' => 'hero-featured-img', 'alt' => esc_attr( get_the_title() ) ) ); ?>
          </a>
        <?php else : ?>
          <div class="hero-featured-img hero-featured-placeholder" aria-hidden="true">🧠</div>
        <?php endif; ?>

        <div class="hero-featured-body">
          <span class="tag <?php echo esc_attr( $cat_class ); ?>">
            ⚡ <?php esc_html_e( 'Featured', 'vitalstack' ); ?> · <?php echo $cat_name; ?>
          </span>

          <h2 class="hero-featured-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>

          <div class="hero-featured-meta">
            <span class="avatar">
              <?php
              $avatar = get_avatar( $author_id, 28 );
              echo $avatar ?: esc_html( $initials );
              ?>
            </span>
            <span><?php the_author(); ?></span>
            <span aria-hidden="true">·</span>
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
              <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?>
            </time>
            <span aria-hidden="true">·</span>
            <span><?php echo esc_html( $read_time ); ?></span>
          </div>
        </div>
      </article>
      <?php
      endif;
      wp_reset_postdata();
      ?>
    </div><!-- /.hero-right -->

  </div><!-- /.hero-inner -->
</section>


<!-- ══════════════════════════════════════════════════════════════════════════
     SECTION 2 — ABOUT / MISSION TEASER
     Text: Edit directly in Page Builder / this template
     WP Location: this template (or use Elementor / block editor section)
═══════════════════════════════════════════════════════════════════════════ -->
<section class="vs-about section-pad" id="about">
  <div class="container about-inner">

    <!-- Visual card (left) — edit via Customizer → Homepage — About Section -->
    <div class="about-visual">
      <div class="about-card-main">
        <div class="about-card-icon" aria-hidden="true"><?php echo esc_html( get_theme_mod( 'vitalstack_about_card_icon', '🔬' ) ); ?></div>
        <h3><?php echo esc_html( get_theme_mod( 'vitalstack_about_card_heading', 'Research-Backed Insights' ) ); ?></h3>
        <p><?php echo esc_html( get_theme_mod( 'vitalstack_about_card_text', 'Every article on VitalStack is thoroughly sourced from peer-reviewed literature, clinical studies, and verified technical documentation — no fluff, only signal.' ) ); ?></p>
        <div class="about-tags">
          <span class="tag ai">Machine Learning</span>
          <span class="tag ai">NLP</span>
          <span class="tag health">Nutrition</span>
          <span class="tag health">Longevity</span>
          <span class="tag ai">Computer Vision</span>
          <span class="tag health">Mental Wellness</span>
        </div>
      </div>
      <div class="about-pill about-pill-tl">
        <span class="about-pill-dot green" aria-hidden="true"></span>
        <?php echo esc_html( get_theme_mod( 'vitalstack_about_pill_top', 'New articles weekly' ) ); ?>
      </div>
      <div class="about-pill about-pill-br">
        <span class="about-pill-dot navy" aria-hidden="true"></span>
        <?php echo esc_html( get_theme_mod( 'vitalstack_about_pill_bottom', 'Expert-reviewed' ) ); ?>
      </div>
    </div><!-- /.about-visual -->

    <!-- Content (right) -->
    <div class="about-content">
      <p class="section-eyebrow"><?php echo esc_html( get_theme_mod( 'vitalstack_about_eyebrow', 'About VitalStack' ) ); ?></p>
      <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'vitalstack_about_heading', 'A Blog Built for the Curious & the Committed' ) ); ?></h2>

      <p><?php echo esc_html( get_theme_mod( 'vitalstack_about_para1', 'VitalStack was founded with a single mission: make cutting-edge knowledge accessible. Whether you\'re a developer exploring AI applications in medicine, a healthcare professional tracking emerging tech, or simply someone passionate about living better — you\'ll find your home here.' ) ); ?></p>
      <p><?php echo esc_html( get_theme_mod( 'vitalstack_about_para2', 'We bridge the gap between technical depth and everyday readability, delivering content that respects your intelligence without leaving you lost in jargon.' ) ); ?></p>

      <!-- 4 Pillars — edit via Customizer → Homepage — About Section -->
      <div class="about-pillars">
        <?php
        $pillars = array(
          array( 'style' => 'g', 'icon' => get_theme_mod( 'vitalstack_pillar1_icon', '🤖' ), 'title' => get_theme_mod( 'vitalstack_pillar1_title', 'Artificial Intelligence' ), 'text' => get_theme_mod( 'vitalstack_pillar1_text', 'LLMs, computer vision, AI ethics, and real-world applications.' ) ),
          array( 'style' => 'n', 'icon' => get_theme_mod( 'vitalstack_pillar2_icon', '💊' ), 'title' => get_theme_mod( 'vitalstack_pillar2_title', 'Health & Wellness' ),        'text' => get_theme_mod( 'vitalstack_pillar2_text', 'Evidence-based nutrition, longevity science, and mental health.' ) ),
          array( 'style' => 'g', 'icon' => get_theme_mod( 'vitalstack_pillar3_icon', '📚' ), 'title' => get_theme_mod( 'vitalstack_pillar3_title', 'Deep Dives' ),               'text' => get_theme_mod( 'vitalstack_pillar3_text', 'Long-form articles that go beyond the surface level every time.' ) ),
          array( 'style' => 'n', 'icon' => get_theme_mod( 'vitalstack_pillar4_icon', '✅' ), 'title' => get_theme_mod( 'vitalstack_pillar4_title', 'Fact-Checked' ),             'text' => get_theme_mod( 'vitalstack_pillar4_text', 'Every claim linked to a credible source or expert opinion.' ) ),
        );
        foreach ( $pillars as $pillar ) : ?>
          <div class="pillar">
            <div class="pillar-icon <?php echo esc_attr( $pillar['style'] ); ?>" aria-hidden="true">
              <?php echo esc_html( $pillar['icon'] ); ?>
            </div>
            <div>
              <h4><?php echo esc_html( $pillar['title'] ); ?></h4>
              <p><?php echo esc_html( $pillar['text'] ); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div><!-- /.about-pillars -->

      <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'about' ) ) ?: '#' ); ?>"
         class="btn btn-primary" style="margin-top:28px;">
        <?php echo esc_html( get_theme_mod( 'vitalstack_about_cta_label', 'Learn More About Us →' ) ); ?>
      </a>
    </div><!-- /.about-content -->

  </div><!-- /.about-inner -->
</section>


<!-- ══════════════════════════════════════════════════════════════════════════
     SECTION 3 — TOP AI ARTICLES
     Posts: auto-pulled from category "Artificial Intelligence"
     Count: Customizer → Homepage Settings → AI Section Post Count
═══════════════════════════════════════════════════════════════════════════ -->
<section class="vs-posts-section section-pad" id="ai-section">
  <div class="container">

    <!-- Category banner — edit via Customizer → Homepage — Category Banners -->
    <div class="category-banner ai-banner">
      <div class="category-banner-icon" aria-hidden="true"><?php echo esc_html( get_theme_mod( 'vitalstack_ai_banner_icon', '🤖' ) ); ?></div>
      <div>
        <h3><?php echo esc_html( get_theme_mod( 'vitalstack_ai_banner_heading', 'Artificial Intelligence' ) ); ?></h3>
        <p><?php echo esc_html( get_theme_mod( 'vitalstack_ai_banner_desc', 'Cutting-edge research, tutorials, and insights on AI, ML, and data science.' ) ); ?></p>
      </div>
    </div>

    <div class="section-header">
      <div>
        <p class="section-eyebrow" style="color:var(--vs-navy-mid);">
          <?php echo esc_html( get_theme_mod( 'vitalstack_ai_eyebrow', 'Latest in AI' ) ); ?>
        </p>
        <h2 class="section-title" style="color:var(--vs-navy);">
          <?php echo esc_html( get_theme_mod( 'vitalstack_ai_section_title', 'Top AI Articles' ) ); ?>
        </h2>
      </div>
      <?php
      $ai_cat = get_category_by_slug( 'artificial-intelligence' );
      $ai_cat_url = $ai_cat ? get_category_link( $ai_cat->term_id ) : home_url( '/category/artificial-intelligence' );
      ?>
      <a href="<?php echo esc_url( $ai_cat_url ); ?>" class="view-all">
        <?php echo esc_html( get_theme_mod( 'vitalstack_ai_viewall_label', 'View all AI posts →' ) ); ?>
      </a>
    </div>

    <?php
    $ai_count = (int) get_theme_mod( 'vitalstack_ai_post_count', 3 );

    // Try slug first, then name
    $ai_cat_obj = get_category_by_slug( 'artificial-intelligence' );
    if ( ! $ai_cat_obj ) $ai_cat_obj = get_term_by( 'name', 'Artificial Intelligence', 'category' );

    $ai_query = new WP_Query( array(
      'posts_per_page'      => $ai_count,
      'category__in'        => $ai_cat_obj ? array( $ai_cat_obj->term_id ) : array(),
      'ignore_sticky_posts' => 1,
    ) );

    if ( $ai_query->have_posts() ) : ?>
      <div class="cards-grid">
        <?php while ( $ai_query->have_posts() ) : $ai_query->the_post(); ?>
          <?php vitalstack_article_card( get_the_ID() ); ?>
        <?php endwhile; ?>
      </div>
    <?php else : ?>
      <p class="no-posts-notice">
        <?php esc_html_e( 'No AI articles found. Start publishing posts in the "Artificial Intelligence" category.', 'vitalstack' ); ?>
      </p>
    <?php endif;
    wp_reset_postdata(); ?>

  </div>
</section>


<!-- ══════════════════════════════════════════════════════════════════════════
     SECTION 4 — TOP HEALTH ARTICLES
     Posts: auto-pulled from category "Health & Wellness"
     Count: Customizer → Homepage Settings → Health Section Post Count
═══════════════════════════════════════════════════════════════════════════ -->
<section class="vs-posts-section section-pad bg-off-white" id="health-section">
  <div class="container">

    <!-- Category banner -->
    <div class="category-banner health-banner">
      <div class="category-banner-icon" aria-hidden="true">
        <?php echo esc_html( get_theme_mod( 'vitalstack_health_banner_icon', '🌿' ) ); ?>
      </div>
      <div>
        <h3>
          <?php echo esc_html( get_theme_mod( 'vitalstack_health_banner_heading', 'Health & Digital Wellness' ) ); ?>
        </h3>
        <p>
          <?php echo esc_html( get_theme_mod( 'vitalstack_health_banner_desc', 'Evidence-based guides on nutrition, longevity, mental health, and the science of feeling great.' ) ); ?>
        </p>
      </div>
    </div>

    <div class="section-header">
      <div>
        <p class="section-eyebrow" style="color:var(--vs-green);">
          <?php echo esc_html( get_theme_mod( 'vitalstack_health_eyebrow', 'Latest in Health' ) ); ?>
        </p>
        <h2 class="section-title" style="color:var(--vs-navy);">
          <?php echo esc_html( get_theme_mod( 'vitalstack_health_section_title', 'Top Health Articles' ) ); ?>
        </h2>
      </div>

      <?php
      // ✅ Get category by correct slug
      $health_cat = get_category_by_slug( 'health-digital-wellness' );

      // fallback if slug fails
      if ( ! $health_cat ) {
          $health_cat = get_term_by( 'name', 'Health & Digital Wellness', 'category' );
      }

      // fallback URL
      $health_cat_url = $health_cat 
        ? get_category_link( $health_cat->term_id ) 
        : home_url( '/category/health-digital-wellness/' );
      ?>

      <a href="<?php echo esc_url( $health_cat_url ); ?>" class="view-all">
        <?php echo esc_html( get_theme_mod( 'vitalstack_health_viewall_label', 'View all health posts →' ) ); ?>
      </a>
    </div>

    <?php
    $health_count = (int) get_theme_mod( 'vitalstack_health_post_count', 3 );

    // ✅ Query (includes child categories automatically)
    $health_query = new WP_Query( array(
      'posts_per_page'      => $health_count,
      'cat'                 => $health_cat ? $health_cat->term_id : 0,
      'ignore_sticky_posts' => 1,
    ) );

    if ( $health_query->have_posts() ) : ?>
      <div class="cards-grid">
        <?php while ( $health_query->have_posts() ) : $health_query->the_post(); ?>
          <?php vitalstack_article_card( get_the_ID() ); ?>
        <?php endwhile; ?>
      </div>
    <?php else : ?>
      <p class="no-posts-notice">
        <?php esc_html_e( 'No health articles found. Start publishing posts in the "Health & Digital Wellness" category or its subcategories.', 'vitalstack' ); ?>
      </p>
    <?php endif;

    wp_reset_postdata();
    ?>

  </div>
</section>


<!-- ══════════════════════════════════════════════════════════════════════════
     SECTION 5 — LATEST NEWS
     Posts: auto-pulled from "news" custom post type (3 most recent)
     Links to: /news/ page
═══════════════════════════════════════════════════════════════════════════ -->
<section class="vs-posts-section section-pad" id="news-section">
  <div class="container">

    <div class="category-banner news-banner">
      <div class="category-banner-icon" aria-hidden="true">📰</div>
      <div>
        <h3><?php esc_html_e( 'Latest News', 'vitalstack' ); ?></h3>
        <p><?php esc_html_e( 'Stay ahead of the curve - the latest tech news, industry updates, and innovations, curated for you.', 'vitalstack' ); ?></p>
      </div>
    </div>

    <div class="section-header">
      <div>
        <p class="section-eyebrow" style="color:var(--vs-navy-mid);">
          <?php esc_html_e( 'What\'s Happening', 'vitalstack' ); ?>
        </p>
        <h2 class="section-title" style="color:var(--vs-navy);">
          <?php esc_html_e( 'Top Stories', 'vitalstack' ); ?>
        </h2>
      </div>
      <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="view-all">
        <?php esc_html_e( 'View all news →', 'vitalstack' ); ?>
      </a>
    </div>

    <?php
    $news_query = new WP_Query( array(
      'post_type'           => 'news',
      'posts_per_page'      => 3,
      'orderby'             => 'date',
      'order'               => 'DESC',
      'ignore_sticky_posts' => 1,
      'post_status'         => 'publish',
    ) );

    if ( $news_query->have_posts() ) : ?>
      <div class="cards-grid vs-news-grid">
        <?php while ( $news_query->have_posts() ) : $news_query->the_post(); ?>
          <?php vitalstack_news_card( get_the_ID() ); ?>
        <?php endwhile; ?>
      </div>
    <?php else : ?>
      <p class="no-posts-notice">
        <?php esc_html_e( 'No news articles found. Start publishing posts using the "News" post type.', 'vitalstack' ); ?>
      </p>
    <?php endif;
    wp_reset_postdata(); ?>

  </div>
</section>


<!-- ══════════════════════════════════════════════════════════════════════════
     SECTION 6 — LATEST TUTORIALS
     Posts: auto-pulled from "tutorials" custom post type (3 most recent)
     Links to: /tutorials/ page
═══════════════════════════════════════════════════════════════════════════ -->
<section class="vs-posts-section section-pad bg-off-white" id="tutorials-section">
  <div class="container">

    <div class="category-banner tutorials-banner">
      <div class="category-banner-icon" aria-hidden="true">🎓</div>
      <div>
        <h3><?php esc_html_e( 'Tutorials', 'vitalstack' ); ?></h3>
        <p><?php esc_html_e( 'Learn step by step - video tutorials covering everything from beginner basics to advanced techniques.', 'vitalstack' ); ?></p>
      </div>
    </div>

    <div class="section-header">
      <div>
        <p class="section-eyebrow" style="color:var(--vs-green);">
          <?php esc_html_e( 'Learn Something New', 'vitalstack' ); ?>
        </p>
        <h2 class="section-title" style="color:var(--vs-navy);">
          <?php esc_html_e( 'Latest Tutorials', 'vitalstack' ); ?>
        </h2>
      </div>
      <a href="<?php echo esc_url( home_url( '/tutorials/' ) ); ?>" class="view-all">
        <?php esc_html_e( 'View all tutorials →', 'vitalstack' ); ?>
      </a>
    </div>

    <?php
    $tut_query = new WP_Query( array(
      'post_type'           => 'tutorials',
      'posts_per_page'      => 3,
      'orderby'             => 'date',
      'order'               => 'DESC',
      'ignore_sticky_posts' => 1,
      'post_status'         => 'publish',
    ) );

    if ( $tut_query->have_posts() ) : ?>
      <div class="cards-grid vs-tut-grid">
        <?php while ( $tut_query->have_posts() ) : $tut_query->the_post(); ?>
          <?php vitalstack_tutorial_card( get_the_ID() ); ?>
        <?php endwhile; ?>
      </div>
    <?php else : ?>
      <p class="no-posts-notice">
        <?php esc_html_e( 'No tutorials found. Start publishing posts using the "Tutorials" post type.', 'vitalstack' ); ?>
      </p>
    <?php endif;
    wp_reset_postdata(); ?>

  </div>
</section>


<!-- ══════════════════════════════════════════════════════════════════════════
     SECTION 7 — NEWSLETTER CTA
     Text: Customizer → Homepage Settings → Newsletter section
     Integration: connect to Mailchimp / ConvertKit / MailerLite plugin
═══════════════════════════════════════════════════════════════════════════ -->
<?php vitalstack_newsletter_section(); ?>

</main><!-- /#main -->

<?php get_footer(); ?>


<?php /* ─── Homepage-specific CSS ──────────────────────────────────────────── */ ?>
<style id="vs-homepage-styles">

/* ── Hero ── */
.vs-hero {
  background: var(--vs-navy);
  position: relative;
  overflow: hidden;
  padding: 90px 0 80px;
}
.hero-bg {
  position: absolute; inset: 0;
  background:
    radial-gradient(ellipse 60% 80% at 80% 50%, rgba(29,138,78,.18) 0%, transparent 70%),
    radial-gradient(ellipse 40% 60% at 20% 80%, rgba(37,176,103,.1) 0%, transparent 60%);
  pointer-events: none;
}
.hero-grid-overlay {
  position: absolute; inset: 0;
  background-image:
    linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
    linear-gradient(90deg,rgba(255,255,255,.03) 1px, transparent 1px);
  background-size: 48px 48px;
  pointer-events: none;
}
.hero-inner {
  position: relative;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
}
.hero-kicker {
  font-family: 'Space Mono', monospace;
  font-size: 11px; letter-spacing: .15em; text-transform: uppercase;
  color: var(--vs-green-light); margin-bottom: 20px;
  display: flex; align-items: center; gap: 10px;
}
.hero-kicker::before {
  content: ''; display: block; width: 28px; height: 2px;
  background: var(--vs-green-light);
}
.vs-hero h1 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(36px, 4.5vw, 58px);
  font-weight: 900; line-height: 1.1;
  color: var(--vs-white); margin-bottom: 22px;
}
.vs-hero h1 em { font-style: italic; color: var(--vs-green-light); }
.hero-desc {
  font-size: 17px; line-height: 1.7;
  color: rgba(255,255,255,.65); margin-bottom: 36px; max-width: 460px;
}
.hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }
.hero-stats {
  display: flex; gap: 32px; margin-top: 48px; padding-top: 32px;
  border-top: 1px solid rgba(255,255,255,.1);
}
.stat-num {
  font-family: 'Playfair Display', serif;
  font-size: 32px; font-weight: 700; color: var(--vs-white); line-height: 1;
}
.stat-label { font-size: 12px; color: rgba(255,255,255,.5); margin-top: 4px; font-weight: 500; }

/* Featured card */
.hero-featured {
  background: rgba(255,255,255,.06);
  border: 1px solid rgba(255,255,255,.12);
  border-radius: 16px; overflow: hidden;
  animation: vsFloat 6s ease-in-out infinite;
}
@keyframes vsFloat {
  0%,100% { transform: translateY(0); }
  50%      { transform: translateY(-10px); }
}
.hero-featured-img {
  width: 100%; height: 220px; object-fit: cover;
}
.hero-featured-placeholder {
  display: flex; align-items: center; justify-content: center;
  font-size: 64px;
  background: linear-gradient(135deg, rgba(29,138,78,.3), rgba(18,32,69,.5));
}
.hero-featured-body { padding: 20px 22px; }
.hero-featured-title {
  font-family: 'Playfair Display', serif;
  font-size: 18px; font-weight: 700;
  color: var(--vs-white); margin: 12px 0;
  line-height: 1.35;
}
.hero-featured-title a { color: inherit; }
.hero-featured-title a:hover { color: var(--vs-green-light); }
.hero-featured-meta {
  display: flex; align-items: center; gap: 8px;
  font-size: 13px; color: rgba(255,255,255,.55); flex-wrap: wrap;
}
.avatar {
  width: 28px; height: 28px; border-radius: 50%;
  background: var(--vs-navy-light);
  display: inline-flex; align-items: center; justify-content: center;
  font-size: 10px; font-weight: 700; color: var(--vs-navy);
  overflow: hidden; flex-shrink: 0;
}
.avatar img { width: 100%; height: 100%; object-fit: cover; }

/* ── About section ── */
.about-inner {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
}
.about-visual { position: relative; }
.about-card-main {
  background: linear-gradient(135deg, var(--vs-navy) 0%, var(--vs-navy-mid) 100%);
  border-radius: 20px; padding: 36px;
  border: 1px solid rgba(255,255,255,.08);
}
.about-card-icon { font-size: 48px; margin-bottom: 16px; }
.about-card-main h3 {
  font-family: 'Playfair Display', serif;
  font-size: 22px; font-weight: 700; color: var(--vs-white); margin-bottom: 12px;
}
.about-card-main p { font-size: 14px; color: rgba(255,255,255,.65); line-height: 1.7; }
.about-tags { margin-top: 20px; display: flex; gap: 8px; flex-wrap: wrap; }
.about-pill {
  position: absolute;
  background: var(--vs-white);
  border-radius: 100px;
  padding: 8px 16px;
  font-size: 12px; font-weight: 600; color: var(--vs-text);
  display: flex; align-items: center; gap: 8px;
  box-shadow: var(--vs-card-shadow);
}
.about-pill-tl { top: -16px; left: -16px; }
.about-pill-br { bottom: -16px; right: -16px; }
.about-pill-dot {
  width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
}
.about-pill-dot.green { background: var(--vs-green); }
.about-pill-dot.navy  { background: var(--vs-navy); }

.about-content .section-title { margin-bottom: 20px; }
.about-content p { color: var(--vs-muted); line-height: 1.7; margin-bottom: 14px; font-size: 15px; }

.about-pillars { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 28px; }
.pillar { display: flex; gap: 14px; align-items: flex-start; }
.pillar-icon {
  width: 40px; height: 40px; border-radius: 10px; font-size: 20px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.pillar-icon.g { background: var(--vs-green-pale); }
.pillar-icon.n { background: var(--vs-navy-light); }
.pillar h4 { font-size: 14px; font-weight: 700; color: var(--vs-text); margin-bottom: 4px; }
.pillar p  { font-size: 13px; color: var(--vs-muted); line-height: 1.5; }

/* ── Category banner ── */
.category-banner {
  display: flex; align-items: center; gap: 20px;
  border-radius: var(--vs-radius); padding: 20px 28px; margin-bottom: 32px;
}
.ai-banner     { background: var(--vs-navy-light); }
.health-banner { background: var(--vs-green-pale); }
.category-banner-icon { font-size: 36px; flex-shrink: 0; }
.category-banner h3 {
  font-family: 'Playfair Display', serif;
  font-size: 20px; font-weight: 700;
  color: var(--vs-navy); margin-bottom: 4px;
}
.category-banner p { font-size: 14px; color: var(--vs-muted); }

/* No-posts notice */
.no-posts-notice {
  text-align: center; padding: 48px; color: var(--vs-muted);
  border: 2px dashed var(--vs-border); border-radius: var(--vs-radius);
}

/* ── News & Tutorials banners ── */
.news-banner {
  background: linear-gradient(135deg, #e8f0fe 0%, #d2e3fc 100%);
}
.news-banner h3 { color: #1a3c8a; }
.tutorials-banner {
  background: linear-gradient(135deg, #fef9e7 0%, #fdf2c8 100%);
}
.tutorials-banner h3 { color: #7a5200; }

/* ── News card styles (homepage) ── */
.vs-news-grid .news-card,
.vs-tut-grid .tut-card {
  background: var(--vs-white);
  border: 1px solid var(--vs-border);
  border-radius: var(--vs-radius);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: box-shadow .25s, transform .25s;
}
.vs-news-grid .news-card:hover,
.vs-tut-grid .tut-card:hover {
  box-shadow: 0 8px 28px rgba(0,0,0,.10);
  transform: translateY(-3px);
}
.vs-news-grid .news-card-img,
.vs-tut-grid .tut-card-thumb-link img {
  width: 100%; height: 200px; object-fit: cover; display: block;
}
.vs-news-grid .news-card-body,
.vs-tut-grid .tut-card-body {
  padding: 18px 20px; display: flex; flex-direction: column; flex: 1; gap: 10px;
}
.vs-news-grid .news-card-meta,
.vs-tut-grid .tut-card-meta {
  display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
}
.vs-news-grid .news-card-title,
.vs-tut-grid .tut-card-title {
  font-family: 'Playfair Display', serif;
  font-size: 16px; font-weight: 700; line-height: 1.35; color: var(--vs-navy);
  margin: 0;
}
.vs-news-grid .news-card-title a,
.vs-tut-grid .tut-card-title a { color: inherit; }
.vs-news-grid .news-card-title a:hover,
.vs-tut-grid .tut-card-title a:hover { color: var(--vs-green); }
.vs-news-grid .news-card-excerpt,
.vs-tut-grid .tut-card-excerpt {
  font-size: 13px; color: var(--vs-muted); line-height: 1.65; flex: 1;
  display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 3; overflow: hidden;
}
.vs-news-grid .news-card-footer {
  display: flex; align-items: center; justify-content: space-between;
  padding-top: 12px; border-top: 1px solid var(--vs-border); margin-top: auto;
}
.author-line { display: flex; align-items: center; gap: 7px; font-size: 12px; color: var(--vs-muted); }
.author-avatar {
  width: 24px; height: 24px; border-radius: 50%;
  background: var(--vs-navy-light); overflow: hidden;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: 9px; font-weight: 700; color: var(--vs-navy);
}
.author-avatar img { width: 100%; height: 100%; object-fit: cover; }
.read-more { font-size: 12px; font-weight: 600; color: var(--vs-green); white-space: nowrap; }
.read-more:hover { color: var(--vs-green-light); }

/* Tag badges */
.tag-news    { background: #e8f0fe; color: #1a3c8a; }
.tag-tutorial { background: #fef9e7; color: #7a5200; }
.news-source { font-size: 11px; color: var(--vs-muted); }
.tut-duration-badge {
  position: absolute; bottom: 8px; right: 8px;
  background: rgba(0,0,0,.7); color: #fff;
  font-family: 'Space Mono', monospace; font-size: 10px;
  padding: 3px 8px; border-radius: 4px;
}
.tut-card-thumb-link { position: relative; display: block; }
.tut-card-thumb-link img { width: 100%; height: 200px; object-fit: cover; display: block; }
.tut-difficulty {
  font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 4px;
  text-transform: uppercase; letter-spacing: .06em;
}
.tut-diff-beginner   { background: #d4edda; color: #155724; }
.tut-diff-intermediate { background: #fff3cd; color: #856404; }
.tut-diff-advanced   { background: #f8d7da; color: #721c24; }
.tut-type-badge { font-size: 11px; color: var(--vs-muted); }

/* Responsive */
@media (max-width: 900px) {
  .hero-inner    { grid-template-columns: 1fr; }
  .hero-right    { display: none; }
  .about-inner   { grid-template-columns: 1fr; }
  .about-pill-tl, .about-pill-br { display: none; }
  .about-pillars { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
  .vs-hero { padding: 60px 0; }
  .hero-stats { flex-wrap: wrap; gap: 20px; }
  .category-banner { flex-direction: column; align-items: flex-start; }
}
</style>
