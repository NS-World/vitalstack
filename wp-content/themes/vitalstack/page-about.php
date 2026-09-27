<?php
/**
 * Template Name: About Page
 *
 * Assign via: Page Editor → Page Attributes → Template → "About Page"
 * Recommended: create a page called "About" at /about
 *
 * Sections:
 *   1. Hero (stats bar, headline, description)
 *   2. Mission / Why VitalStack Exists
 *   3. Coverage Areas (5 topic cards + "New Topics Weekly")
 *   4. Content Approach (6-step process + stat cards)
 *   5. Trust Pillars (6 reasons to trust)
 *   6. Vision (goals + 3 cards)
 *   7. Connect / Write for Us
 *   8. Newsletter CTA
 *
 * @package VitalStack
 */

get_header();
?>

<main id="main" class="site-main about-page">

<!-- ══════════════════════════════════════════════════════════════════════════
     1. ABOUT HERO
     Text: edit strings below or replace with Customizer / ACF fields
═══════════════════════════════════════════════════════════════════════════ -->
<section class="about-hero" aria-label="<?php esc_attr_e( 'About VitalStack', 'vitalstack' ); ?>">
  <div class="about-hero-bg"    aria-hidden="true"></div>
  <div class="about-hero-grid"  aria-hidden="true"></div>

  <div class="container">
    <div class="about-hero-inner">

      <p class="about-hero-kicker">
        <?php echo esc_html( get_theme_mod( 'vitalstack_aboutpage_eyebrow', 'About VitalStack' ) ); ?>
      </p>

      <h1>
        <?php echo esc_html( get_theme_mod( 'vitalstack_aboutpage_h1', 'A Modern Knowledge Platform for Technology & Digital Health' ) ); ?>
      </h1>

      <p class="about-hero-desc">
        <?php echo esc_html( get_theme_mod( 'vitalstack_aboutpage_hero_desc', 'Welcome to VitalStack — making AI, technology, and health innovations simple, practical, and accessible for everyone. In a world where information feels overwhelming, we cut through the noise.' ) ); ?>
      </p>

      <!-- Stats bar — edit via Customizer → About Page Settings -->
      <div class="about-hero-stats">
        <?php
        $stats = array(
          array( 'num' => get_theme_mod( 'vitalstack_aboutpage_stat1_num', 'Growing' ), 'label' => get_theme_mod( 'vitalstack_aboutpage_stat1_label', 'Community' ) ),
          array( 'num' => get_theme_mod( 'vitalstack_aboutpage_stat2_num', 'Weekly' ), 'label' => get_theme_mod( 'vitalstack_aboutpage_stat2_label', 'Fresh Content' ) ),
          array( 'num' => get_theme_mod( 'vitalstack_aboutpage_stat3_num', '5' ),    'label' => get_theme_mod( 'vitalstack_aboutpage_stat3_label', 'Core Topic Areas' ) ),
          array( 'num' => get_theme_mod( 'vitalstack_aboutpage_stat4_num', '100%' ), 'label' => get_theme_mod( 'vitalstack_aboutpage_stat4_label', 'Original Content' ) ),
        );
        foreach ( $stats as $s ) : ?>
          <div class="about-hero-stat">
            <div class="stat-num"><?php echo esc_html( $s['num'] ); ?></div>
            <div class="stat-label"><?php echo esc_html( $s['label'] ); ?></div>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════════════════════════════════════
     2. MISSION — WHY VITALSTACK EXISTS
═══════════════════════════════════════════════════════════════════════════ -->
<section class="mission-section section-pad" aria-labelledby="mission-heading">
  <div class="container">
    <div class="mission-inner">

      <!-- Left: text content -->
      <div class="mission-content fade-up">
        <p class="section-eyebrow"><?php esc_html_e( 'Our Mission', 'vitalstack' ); ?></p>
        <h2 id="mission-heading" class="section-title">
          <?php echo esc_html( get_theme_mod( 'vitalstack_aboutpage_mission_heading', 'Why VitalStack Exists' ) ); ?>
        </h2>

        <p><?php echo esc_html( get_theme_mod( 'vitalstack_aboutpage_mission_para1', 'In a world where Artificial Intelligence, automation, and health innovations evolve every day, information can feel overwhelming. Many websites either overcomplicate topics or publish shallow content without real clarity.' ) ); ?></p>
        <p><?php echo esc_html( get_theme_mod( 'vitalstack_aboutpage_mission_para2', 'VitalStack was created to solve that problem.' ) ); ?></p>

        <div class="mission-highlight">
          <p><?php echo esc_html( get_theme_mod( 'vitalstack_aboutpage_mission_statement', 'To simplify technology and digital health so anyone can understand, apply, and benefit from it.' ) ); ?></p>
        </div>

        <p><?php echo esc_html( get_theme_mod( 'vitalstack_aboutpage_mission_para3', 'Whether you are a student exploring AI, a beginner learning tech basics, a professional upgrading digital skills, or a curious reader following health innovations — VitalStack provides clear guidance that helps you grow with confidence.' ) ); ?></p>

        <!-- Audience list -->
        <div class="mission-audience">
          <?php
          $audience = array(
            __( 'Students exploring AI & technology',       'vitalstack' ),
            __( 'Beginners learning tech basics',           'vitalstack' ),
            __( 'Professionals upgrading digital skills',   'vitalstack' ),
            __( 'Curious readers following health innovations', 'vitalstack' ),
          );
          foreach ( $audience as $item ) : ?>
            <div class="audience-item">
              <div class="audience-dot" aria-hidden="true"></div>
              <?php echo esc_html( $item ); ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Right: visual card -->
      <div class="mission-visual fade-up">
        <div class="mission-floating-pill pill-tl" aria-hidden="true">
          <div class="pill-dot green"></div>
          <?php echo esc_html( get_theme_mod( 'vitalstack_aboutpage_value1_badge', '100% Original Content' ) ); ?>
        </div>

        <div class="mission-card-main">
          <div class="mission-card-icon" aria-hidden="true">🎯</div>
          <h3><?php echo esc_html( get_theme_mod( 'vitalstack_aboutpage_value1_title', 'Clarity Above All' ) ); ?></h3>
          <p><?php echo esc_html( get_theme_mod( 'vitalstack_aboutpage_value1_text', 'We believe complex ideas should be explained in a clear, structured, and beginner-friendly way — without confusion, hype, or unnecessary jargon. Our focus is always clarity, accuracy, and usefulness.' ) ); ?></p>
        </div>

        <div class="mission-floating-pill pill-br" aria-hidden="true">
          <div class="pill-dot navy"></div>
          <?php echo esc_html( get_theme_mod( 'vitalstack_aboutpage_value2_badge', 'No Clickbait. Ever.' ) ); ?>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════════════════════════════════════
     3. COVERAGE AREAS — 5 TOPIC CARDS
═══════════════════════════════════════════════════════════════════════════ -->
<section class="coverage-section section-pad bg-off-white" aria-labelledby="coverage-heading">
  <div class="container">

    <div class="coverage-header fade-up">
      <p class="section-eyebrow"><?php esc_html_e( 'What We Cover', 'vitalstack' ); ?></p>
      <h2 id="coverage-heading" class="section-title">
        <?php esc_html_e( 'Well-Researched Content Across 5 Key Areas', 'vitalstack' ); ?>
      </h2>
      <p><?php esc_html_e( 'We publish practical, well-researched content designed to inform and empower — across the most important areas shaping today\'s world.', 'vitalstack' ); ?></p>
    </div>

    <div class="coverage-grid">
      <?php
      $areas = array(
        array( 'icon' => '🤖', 'type' => 'ai',   'title' => __( 'Artificial Intelligence', 'vitalstack' ),   'tag' => 'AI',     'desc' => __( 'Beginner guides, AI tools, automation strategies, real-world applications, and future trends explained in plain language.', 'vitalstack' ) ),
        array( 'icon' => '📊', 'type' => 'ai',   'title' => __( 'Machine Learning & Data', 'vitalstack' ),   'tag' => 'ML',     'desc' => __( 'Foundations, concepts explained simply, and practical use cases — from data pipelines to model deployment.', 'vitalstack' ) ),
        array( 'icon' => '⚡', 'type' => 'ai',   'title' => __( 'Emerging Technologies', 'vitalstack' ),     'tag' => 'Tech',   'desc' => __( 'SaaS, automation platforms, no-code tools, digital transformation, and the latest business tech trends.', 'vitalstack' ) ),
        array( 'icon' => '💡', 'type' => 'ai',   'title' => __( 'Technology Basics', 'vitalstack' ),         'tag' => 'Basics', 'desc' => __( 'Easy explanations for beginners who want to understand how modern technology works, step by step.', 'vitalstack' ) ),
        array( 'icon' => '🌿', 'type' => 'hlth', 'title' => __( 'Digital Health & Innovation', 'vitalstack' ), 'tag' => 'Health', 'desc' => __( 'AI in healthcare, health-tech platforms, wearable technology, and modern wellness advancements evidence-based and accessible.', 'vitalstack' ) ),
      );
      foreach ( $areas as $i => $area ) :
        $tag_class = $area['type'] === 'hlth' ? 'health' : 'ai';
      ?>
        <div class="coverage-card fade-up">
          <div class="coverage-icon <?php echo esc_attr( $area['type'] ); ?>-icon" aria-hidden="true">
            <?php echo esc_html( $area['icon'] ); ?>
          </div>
          <h3>
            <?php echo esc_html( $area['title'] ); ?>
            <span class="card-tag <?php echo esc_attr( $tag_class ); ?>">
              <?php echo esc_html( $area['tag'] ); ?>
            </span>
          </h3>
          <p><?php echo esc_html( $area['desc'] ); ?></p>
        </div>
      <?php endforeach; ?>

      <!-- "New Topics Weekly" card -->
      <div class="coverage-card coverage-card-dark fade-up">
        <div aria-hidden="true" style="font-size:40px;margin-bottom:12px;">📚</div>
        <h3><?php esc_html_e( 'New Topics Weekly', 'vitalstack' ); ?></h3>
        <p><?php esc_html_e( 'Fresh, relevant content researched and published every week without fail.', 'vitalstack' ); ?></p>
      </div>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════════════════════════════════════════════
     4. CONTENT APPROACH — HOW EVERY ARTICLE GETS MADE
═══════════════════════════════════════════════════════════════════════════ -->
<section class="approach-section section-pad" aria-labelledby="approach-heading">
  <div class="container">
    <div class="approach-inner">

      <!-- Left: steps -->
      <div class="approach-content fade-up">
        <p class="section-eyebrow" style="color:var(--vs-navy);">
          <?php esc_html_e( 'Our Content Approach', 'vitalstack' ); ?>
        </p>
        <h2 id="approach-heading" class="section-title">
          <?php esc_html_e( 'How Every Article Gets Made', 'vitalstack' ); ?>
        </h2>
        <p><?php esc_html_e( 'Every article published on VitalStack follows a structured process designed to deliver maximum clarity and real-world value not just page views.', 'vitalstack' ); ?></p>

        <div class="approach-steps">
          <?php
          $steps = array(
            array( 'n' => '01', 'title' => __( 'Topic Research Based on Current Trends',   'vitalstack' ), 'desc' => __( 'We identify topics that matter right now, based on real reader questions and emerging developments.', 'vitalstack' ) ),
            array( 'n' => '02', 'title' => __( 'Clear, Beginner-Friendly Explanations',    'vitalstack' ), 'desc' => __( 'Complex ideas are broken down into plain language - no assumed expertise, no unnecessary jargon.', 'vitalstack' ) ),
            array( 'n' => '03', 'title' => __( 'Practical Examples and Real-World Context','vitalstack' ), 'desc' => __( 'Every concept is grounded with examples you can actually apply, not just theory on a page.', 'vitalstack' ) ),
            array( 'n' => '04', 'title' => __( 'Updated Insights as Technology Evolves',   'vitalstack' ), 'desc' => __( 'We revisit and refresh articles as the landscape changes - so you\'re always reading current information.', 'vitalstack' ) ),
            array( 'n' => '05', 'title' => __( 'Clean Formatting for Easy Reading',        'vitalstack' ), 'desc' => __( 'Structured layouts, clear headings, and scannable content ensure every article respects your time.', 'vitalstack' ) ),
          );
          foreach ( $steps as $step ) : ?>
            <div class="approach-step">
              <div class="step-num" aria-hidden="true"><?php echo esc_html( $step['n'] ); ?></div>
              <div class="step-body">
                <h4><?php echo esc_html( $step['title'] ); ?></h4>
                <p><?php echo esc_html( $step['desc'] ); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="approach-pledge">
          <p>
            <?php
            echo wp_kses(
              __( 'We prioritize <strong>helpful content over hype</strong>. No clickbait. No misleading claims. No copied material. Just honest, well-crafted writing that makes you smarter.', 'vitalstack' ),
              array( 'strong' => array() )
            );
            ?>
          </p>
        </div>
      </div>

      <!-- Right: stat cards -->
      <div class="approach-visual fade-up">
        <?php
        $a_stats = array(
          array( 'num' => '350+', 'label' => __( 'Readers trust VitalStack every month', 'vitalstack' ),        'featured' => true ),
          array( 'num' => '45+',    'label' => __( 'In-depth articles published', 'vitalstack' ),                  'green' => true ),
          array( 'num' => '5+',      'label' => __( 'Structured content steps per article', 'vitalstack' ) ),
          array( 'num' => '0',       'label' => __( 'Sponsored or misleading posts', 'vitalstack' ),                'green' => true ),
          array( 'num' => 'Weekly',  'label' => __( 'New content published - consistently, reliably', 'vitalstack' ), 'wide' => true ),
        );
        foreach ( $a_stats as $as ) :
          $classes = 'approach-stat-card';
          if ( ! empty( $as['featured'] ) ) $classes .= ' featured';
          if ( ! empty( $as['wide'] )     ) $classes .= ' wide';
          $num_color = ! empty( $as['featured'] ) ? 'color:var(--vs-white)' : ( ! empty( $as['green'] ) ? 'color:var(--vs-green)' : 'color:var(--vs-navy)' );
        ?>
          <div class="<?php echo esc_attr( $classes ); ?>">
            <div class="stat-num" style="<?php echo esc_attr( $num_color ); ?>">
              <?php echo esc_html( $as['num'] ); ?>
            </div>
            <div class="stat-label"><?php echo esc_html( $as['label'] ); ?></div>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════════════════════════════════════
     5. TRUST PILLARS
═══════════════════════════════════════════════════════════════════════════ -->
<section class="trust-section section-pad bg-off-white" aria-labelledby="trust-heading">
  <div class="container">

    <div class="trust-header fade-up">
      <p class="section-eyebrow"><?php esc_html_e( 'Why Readers Trust VitalStack', 'vitalstack' ); ?></p>
      <h2 id="trust-heading" class="section-title">
        <?php esc_html_e( 'Trust Is Built Through Transparency', 'vitalstack' ); ?>
      </h2>
      <p><?php esc_html_e( 'We focus on building a platform that readers can rely on not just for traffic, but for long-term value.', 'vitalstack' ); ?></p>
    </div>

    <div class="trust-pillars">
      <?php
      $pillars = array(
        array( 'icon' => '📐', 'type' => 'g', 'title' => __( 'Clear and Structured Explanations',       'vitalstack' ), 'desc' => __( 'Every article is organized for maximum readability with logical structure, clean headings, and no filler content.', 'vitalstack' ) ),
        array( 'icon' => '✍️', 'type' => 'n', 'title' => __( 'Original, Human-Written Content',          'vitalstack' ), 'desc' => __( 'All our content is thoughtfully crafted by human writers who care about accuracy and clarity. No automated or copied material.', 'vitalstack' ) ),
        array( 'icon' => '🔍', 'type' => 'g', 'title' => __( 'Practical Insights, Not Generic Summaries','vitalstack' ), 'desc' => __( 'We go beyond surface-level takes to provide actionable insights grounded in real-world context and current trends.', 'vitalstack' ) ),
        array( 'icon' => '⚖️', 'type' => 'n', 'title' => __( 'Ethical Publishing Standards',             'vitalstack' ), 'desc' => __( 'We never publish sponsored content disguised as editorial, misleading headlines, or unverified claims. Period.', 'vitalstack' ) ),
        array( 'icon' => '🔄', 'type' => 'g', 'title' => __( 'Continuous Improvement',                   'vitalstack' ), 'desc' => __( 'We actively update older content and improve our editorial process because technology evolves and so should we.', 'vitalstack' ) ),
        array( 'icon' => '🎯', 'type' => 'n', 'title' => __( 'Long-Term Value Over Quick Traffic',       'vitalstack' ), 'desc' => __( "Our goal isn't just traffic it's to be the resource you actually come back to when you need to understand something.", 'vitalstack' ) ),
      );
      foreach ( $pillars as $p ) : ?>
        <div class="trust-pillar fade-up">
          <div class="trust-icon <?php echo esc_attr( $p['type'] ); ?>" aria-hidden="true">
            <?php echo esc_html( $p['icon'] ); ?>
          </div>
          <div>
            <h4><?php echo esc_html( $p['title'] ); ?></h4>
            <p><?php echo esc_html( $p['desc'] ); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════════════════════════════════════════════
     6. VISION
═══════════════════════════════════════════════════════════════════════════ -->
<section class="vision-section section-pad" aria-labelledby="vision-heading">
  <div class="container">
    <div class="vision-inner">

      <!-- Left: goals -->
      <div class="vision-content fade-up">
        <p class="section-eyebrow"><?php esc_html_e( 'Our Vision', 'vitalstack' ); ?></p>
        <h2 id="vision-heading" class="section-title">
          <?php esc_html_e( 'Growing Into a Reliable Digital Knowledge Hub', 'vitalstack' ); ?>
        </h2>
        <p><?php esc_html_e( 'We aim to grow VitalStack into a platform where learning never stops and where technology and health innovation feel accessible to everyone, regardless of background.', 'vitalstack' ); ?></p>
        <p><?php esc_html_e( 'As technology continues to reshape industries, we want VitalStack to be the platform that helps people adapt, learn, and grow without confusion.', 'vitalstack' ); ?></p>

        <div class="vision-goals">
          <?php
          $goals = array(
            array( 'icon' => '🎓', 'text' => __( 'Beginners can learn confidently',  'vitalstack' ) ),
            array( 'icon' => '📡', 'text' => __( 'Professionals can stay updated',   'vitalstack' ) ),
            array( 'icon' => '🧭', 'text' => __( 'Technology feels understandable',  'vitalstack' ) ),
            array( 'icon' => '💊', 'text' => __( 'Health innovation feels accessible','vitalstack' ) ),
          );
          foreach ( $goals as $g ) : ?>
            <div class="vision-goal">
              <div class="vision-goal-icon" aria-hidden="true"><?php echo esc_html( $g['icon'] ); ?></div>
              <?php echo esc_html( $g['text'] ); ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Right: vision cards -->
      <div class="vision-cards fade-up">
        <?php
        $vcards = array(
          array( 'icon' => '🌐', 'title' => __( 'Global Accessibility',         'vitalstack' ), 'desc' => __( "Knowledge shouldn't have barriers. We write for readers everywhere, making advanced concepts approachable for all levels.", 'vitalstack' ) ),
          array( 'icon' => '🚀', 'title' => __( 'Staying Ahead of the Curve',   'vitalstack' ), 'desc' => __( 'From GPT models to wearable health tech, we cover emerging innovations as they happen not months later.', 'vitalstack' ) ),
          array( 'icon' => '🤝', 'title' => __( 'A Community of Curious Minds', 'vitalstack' ), 'desc' => __( "We're building more than a blog a knowledge community where readers, writers, and thinkers come together.", 'vitalstack' ) ),
        );
        foreach ( $vcards as $vc ) : ?>
          <div class="vision-card">
            <div class="vision-card-icon" aria-hidden="true"><?php echo esc_html( $vc['icon'] ); ?></div>
            <div>
              <h4><?php echo esc_html( $vc['title'] ); ?></h4>
              <p><?php echo esc_html( $vc['desc'] ); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════════════════════════════════════
     7. CONNECT / WRITE FOR US
═══════════════════════════════════════════════════════════════════════════ -->
<section class="connect-section section-pad bg-off-white" aria-labelledby="connect-heading">
  <div class="container">
    <div class="connect-inner">

      <!-- Left: connect content -->
      <div class="connect-content fade-up">
        <p class="section-eyebrow"><?php esc_html_e( 'Connect With Us', 'vitalstack' ); ?></p>
        <h2 id="connect-heading" class="section-title">
          <?php esc_html_e( "We'd Love to Hear From You", 'vitalstack' ); ?>
        </h2>
        <p><?php esc_html_e( 'VitalStack is built with and for our community. Whether you want to share feedback, suggest a topic, explore collaboration, or contribute as a guest writer we welcome it all.', 'vitalstack' ); ?></p>

        <div class="connect-list">
          <?php
          $connects = array(
            array( 'icon' => '💬', 'text' => __( 'Share feedback on our content',     'vitalstack' ) ),
            array( 'icon' => '💡', 'text' => __( "Suggest topics you'd like covered", 'vitalstack' ) ),
            array( 'icon' => '🤝', 'text' => __( 'Explore collaboration ideas',        'vitalstack' ) ),
            array( 'icon' => '✍️', 'text' => __( 'Guest contribution inquiries',       'vitalstack' ) ),
          );
          foreach ( $connects as $c ) : ?>
            <div class="connect-item">
              <div class="connect-check" aria-hidden="true"><?php echo esc_html( $c['icon'] ); ?></div>
              <?php echo esc_html( $c['text'] ); ?>
            </div>
          <?php endforeach; ?>
        </div>

        <p style="font-size:15px;color:var(--vs-muted);margin-top:20px;">
          <?php
          printf(
            /* translators: %s: contact page link */
            wp_kses( __( 'If you\'d like to get in touch, please visit our <a href="%s">Contact Page</a>.', 'vitalstack' ), array( 'a' => array( 'href' => array() ) ) ),
            esc_url( get_permalink( get_page_by_path( 'contact' ) ) ?: home_url( '/contact' ) )
          );
          ?>
        </p>
      </div>

      <!-- Right: Write for Us CTA box -->
      <div class="connect-cta-box fade-up">
        <div class="connect-cta-icon" aria-hidden="true">✍️</div>
        <h3><?php esc_html_e( 'Write for VitalStack', 'vitalstack' ); ?></h3>
        <p><?php esc_html_e( 'Are you knowledgeable about AI, technology, or digital health? We welcome guest contributors who share our commitment to clarity, accuracy, and genuine helpfulness.', 'vitalstack' ); ?></p>
        <div class="connect-cta-actions">
          <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ?: home_url( '/contact' ) ); ?>"
             class="btn btn-primary">
            <?php esc_html_e( 'Get in Touch →', 'vitalstack' ); ?>
          </a>
          <a href="#newsletter" class="btn btn-outline">
            <?php esc_html_e( 'Subscribe to Newsletter', 'vitalstack' ); ?>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════════════════════════════════════
     8. NEWSLETTER CTA
═══════════════════════════════════════════════════════════════════════════ -->
<?php vitalstack_newsletter_section(); ?>

</main><!-- /#main -->

<?php get_footer(); ?>


<?php /* ─── About page CSS ──────────────────────────────────────────────── */ ?>
<style id="vs-about-styles">

/* ── Hero ── */
.about-hero {
  background: linear-gradient(135deg, var(--vs-navy) 0%, var(--vs-navy-mid) 100%);
  padding: 90px 0 80px;
  position: relative; overflow: hidden;
}
.about-hero-bg {
  position: absolute; inset: 0;
  background:
    radial-gradient(ellipse 55% 70% at 85% 40%, rgba(29,138,78,.15) 0%, transparent 70%),
    radial-gradient(ellipse 40% 55% at 15% 80%, rgba(37,176,103,.08) 0%, transparent 60%);
  pointer-events: none;
}
.about-hero-grid {
  position: absolute; inset: 0;
  background-image:
    linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
  background-size: 48px 48px; pointer-events: none;
}
.about-hero-inner { position: relative; max-width: 820px; }
.about-hero-kicker {
  font-family: 'Space Mono', monospace; font-size: 11px;
  letter-spacing: .15em; text-transform: uppercase;
  color: var(--vs-green-light); margin-bottom: 18px;
  display: flex; align-items: center; gap: 10px;
}
.about-hero-kicker::before {
  content: ''; display: block; width: 28px; height: 2px;
  background: var(--vs-green-light);
}
.about-hero h1 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(32px, 4.5vw, 56px); font-weight: 900;
  line-height: 1.12; color: var(--vs-white); margin-bottom: 20px;
}
.about-hero h1 em { font-style: italic; color: var(--vs-green-light); }
.about-hero-desc {
  font-size: 18px; color: rgba(255,255,255,.65);
  line-height: 1.7; max-width: 600px; margin-bottom: 48px;
}
.about-hero-stats {
  display: flex; gap: 40px; flex-wrap: wrap;
  padding-top: 32px; border-top: 1px solid rgba(255,255,255,.1);
}
.about-hero-stat .stat-num {
  font-family: 'Playfair Display', serif;
  font-size: 34px; font-weight: 700; color: var(--vs-white); line-height: 1;
}
.about-hero-stat .stat-label {
  font-size: 12px; color: rgba(255,255,255,.45); margin-top: 5px; font-weight: 500;
}

/* ── Mission ── */
.mission-inner {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 60px; align-items: center;
}
.mission-content .section-title { margin-bottom: 20px; }
.mission-content p { color: var(--vs-muted); font-size: 15px; line-height: 1.75; margin-bottom: 16px; }
.mission-highlight {
  background: linear-gradient(135deg, var(--vs-green-pale), #d4edd8);
  border-left: 4px solid var(--vs-green);
  border-radius: 0 var(--vs-radius) var(--vs-radius) 0;
  padding: 18px 22px; margin: 24px 0;
}
.mission-highlight p {
  font-family: 'Playfair Display', serif; font-size: 17px;
  font-style: italic; color: var(--vs-navy); margin: 0; font-weight: 600;
}
.mission-highlight em { font-style: italic; color: var(--vs-green); }
.mission-audience { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
.audience-item {
  display: flex; align-items: center; gap: 12px;
  font-size: 14px; color: var(--vs-text); font-weight: 500;
}
.audience-dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: var(--vs-green); flex-shrink: 0;
}

/* Mission visual */
.mission-visual { position: relative; }
.mission-card-main {
  background: linear-gradient(135deg, var(--vs-navy) 0%, var(--vs-navy-mid) 100%);
  border-radius: 20px; padding: 40px;
  border: 1px solid rgba(255,255,255,.08);
}
.mission-card-icon { font-size: 44px; margin-bottom: 16px; }
.mission-card-main h3 {
  font-family: 'Playfair Display', serif;
  font-size: 22px; font-weight: 700; color: var(--vs-white); margin-bottom: 12px;
}
.mission-card-main p { font-size: 14px; color: rgba(255,255,255,.65); line-height: 1.7; }
.mission-floating-pill {
  position: absolute;
  background: var(--vs-white); border-radius: 100px;
  padding: 8px 16px; font-size: 12px; font-weight: 600;
  color: var(--vs-text); display: flex; align-items: center; gap: 8px;
  box-shadow: var(--vs-card-shadow); z-index: 2;
}
.pill-tl { top: -16px; left: -16px; }
.pill-br { bottom: -16px; right: -16px; }
.pill-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.pill-dot.green { background: var(--vs-green); }
.pill-dot.navy  { background: var(--vs-navy); }

/* ── Coverage grid ── */
.coverage-header { text-align: center; max-width: 640px; margin: 0 auto 48px; }
.coverage-header p { color: var(--vs-muted); font-size: 15px; margin-top: 12px; }
.coverage-grid {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;
}
.coverage-card {
  background: var(--vs-white); border: 1px solid var(--vs-border);
  border-radius: var(--vs-radius); padding: 28px;
  transition: transform .3s, box-shadow .3s;
}
.coverage-card:hover { transform: translateY(-4px); box-shadow: var(--vs-card-hover); }
.coverage-icon {
  width: 52px; height: 52px; border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  font-size: 26px; margin-bottom: 16px;
}
.ai-icon   { background: var(--vs-navy-light); }
.hlth-icon { background: var(--vs-green-pale); }
.coverage-card h3 {
  font-family: 'Playfair Display', serif; font-size: 17px;
  font-weight: 700; color: var(--vs-text); margin-bottom: 10px;
  display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.card-tag {
  font-family: 'Space Mono', monospace; font-size: 10px;
  font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
  padding: 3px 8px; border-radius: 4px;
}
.card-tag.ai     { background: var(--vs-navy-light); color: var(--vs-navy); }
.card-tag.health { background: var(--vs-green-pale); color: var(--vs-green); }
.coverage-card p { font-size: 14px; color: var(--vs-muted); line-height: 1.7; }
.coverage-card-dark {
  background: linear-gradient(135deg, var(--vs-navy) 0%, var(--vs-navy-mid) 100%);
  border-color: transparent; align-items: center; justify-content: center;
  text-align: center;
}
.coverage-card-dark h3 { color: var(--vs-white); justify-content: center; }
.coverage-card-dark p  { color: rgba(255,255,255,.6); }

/* ── Approach ── */
.approach-inner {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 60px; align-items: start;
}
.approach-content .section-title { margin-bottom: 16px; }
.approach-content > p { color: var(--vs-muted); font-size: 15px; line-height: 1.75; margin-bottom: 28px; }
.approach-steps { display: flex; flex-direction: column; gap: 20px; margin-bottom: 28px; }
.approach-step { display: flex; gap: 18px; align-items: flex-start; }
.step-num {
  font-family: 'Space Mono', monospace; font-size: 22px; font-weight: 700;
  color: var(--vs-border); line-height: 1; flex-shrink: 0; min-width: 32px;
}
.step-body h4 { font-size: 15px; font-weight: 700; color: var(--vs-text); margin-bottom: 4px; }
.step-body p  { font-size: 13px; color: var(--vs-muted); line-height: 1.65; }
.approach-pledge {
  background: var(--vs-off-white); border: 1px solid var(--vs-border);
  border-radius: var(--vs-radius); padding: 18px 22px;
}
.approach-pledge p { font-size: 14px; color: var(--vs-text); line-height: 1.7; margin: 0; }

.approach-visual {
  display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
  position: sticky; top: 90px;
}
.approach-stat-card {
  background: var(--vs-white); border: 1px solid var(--vs-border);
  border-radius: var(--vs-radius); padding: 22px;
}
.approach-stat-card.featured {
  background: linear-gradient(135deg, var(--vs-navy) 0%, var(--vs-navy-mid) 100%);
  border-color: transparent;
}
.approach-stat-card.featured .stat-label { color: rgba(255,255,255,.55); }
.approach-stat-card.wide { grid-column: 1 / -1; }
.approach-stat-card .stat-num {
  font-family: 'Playfair Display', serif; font-size: 28px;
  font-weight: 700; line-height: 1; margin-bottom: 6px;
}
.approach-stat-card .stat-label { font-size: 12px; color: var(--vs-muted); line-height: 1.5; }

/* ── Trust pillars ── */
.trust-header { text-align: center; max-width: 600px; margin: 0 auto 48px; }
.trust-header p { color: var(--vs-muted); font-size: 15px; margin-top: 12px; }
.trust-pillars {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;
}
.trust-pillar { display: flex; gap: 16px; align-items: flex-start; }
.trust-icon {
  width: 44px; height: 44px; border-radius: 12px; font-size: 22px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.trust-icon.g { background: var(--vs-green-pale); }
.trust-icon.n { background: var(--vs-navy-light); }
.trust-pillar h4 { font-size: 15px; font-weight: 700; color: var(--vs-text); margin-bottom: 6px; }
.trust-pillar p  { font-size: 13px; color: var(--vs-muted); line-height: 1.65; }

/* ── Vision ── */
.vision-inner {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 60px; align-items: center;
}
.vision-content .section-title { margin-bottom: 16px; }
.vision-content > p { color: var(--vs-muted); font-size: 15px; line-height: 1.75; margin-bottom: 14px; }
.vision-goals { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 24px; }
.vision-goal {
  display: flex; align-items: center; gap: 12px;
  background: var(--vs-off-white); border: 1px solid var(--vs-border);
  border-radius: 10px; padding: 12px 16px;
  font-size: 14px; font-weight: 600; color: var(--vs-text);
}
.vision-goal-icon { font-size: 20px; flex-shrink: 0; }
.vision-cards { display: flex; flex-direction: column; gap: 16px; }
.vision-card {
  display: flex; gap: 16px; align-items: flex-start;
  background: var(--vs-white); border: 1px solid var(--vs-border);
  border-radius: var(--vs-radius); padding: 22px;
  box-shadow: var(--vs-card-shadow);
  transition: transform .3s, box-shadow .3s;
}
.vision-card:hover { transform: translateY(-3px); box-shadow: var(--vs-card-hover); }
.vision-card-icon {
  font-size: 28px; width: 48px; height: 48px;
  background: var(--vs-off-white); border-radius: 12px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.vision-card h4 { font-size: 15px; font-weight: 700; color: var(--vs-text); margin-bottom: 5px; }
.vision-card p  { font-size: 13px; color: var(--vs-muted); line-height: 1.65; }

/* ── Connect ── */
.connect-inner {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 60px; align-items: start;
}
.connect-content .section-title { margin-bottom: 16px; }
.connect-content > p { color: var(--vs-muted); font-size: 15px; line-height: 1.75; margin-bottom: 20px; }
.connect-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px; }
.connect-item {
  display: flex; align-items: center; gap: 14px;
  font-size: 15px; font-weight: 500; color: var(--vs-text);
}
.connect-check { font-size: 20px; width: 36px; text-align: center; flex-shrink: 0; }
.connect-cta-box {
  background: linear-gradient(135deg, var(--vs-navy) 0%, var(--vs-navy-mid) 100%);
  border-radius: 20px; padding: 40px;
  border: 1px solid rgba(255,255,255,.08);
}
.connect-cta-icon { font-size: 40px; margin-bottom: 16px; }
.connect-cta-box h3 {
  font-family: 'Playfair Display', serif; font-size: 24px;
  font-weight: 700; color: var(--vs-white); margin-bottom: 12px;
}
.connect-cta-box p { font-size: 15px; color: rgba(255,255,255,.65); line-height: 1.7; margin-bottom: 24px; }
.connect-cta-actions { display: flex; flex-direction: column; gap: 12px; }
.connect-cta-actions .btn-outline {
  color: var(--vs-white); border-color: rgba(255,255,255,.3);
}
.connect-cta-actions .btn-outline:hover { background: rgba(255,255,255,.1); }

/* ── Fade-up animation ── */
.fade-up {
  opacity: 0; transform: translateY(24px);
  transition: opacity .6s ease, transform .6s ease;
}
.fade-up.visible { opacity: 1; transform: translateY(0); }

/* Responsive */
@media (max-width: 1024px) {
  .trust-pillars { grid-template-columns: repeat(2, 1fr); }
  .coverage-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
  .mission-inner, .approach-inner, .vision-inner, .connect-inner {
    grid-template-columns: 1fr;
  }
  .approach-visual { position: static; }
  .pill-tl, .pill-br { display: none; }
  .about-hero-stats { gap: 24px; }
  .trust-pillars { grid-template-columns: 1fr; }
  .coverage-grid { grid-template-columns: 1fr; }
  .vision-goals  { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
  .about-hero { padding: 60px 0; }
  .about-hero h1 { font-size: clamp(26px, 8vw, 38px); }
  .approach-visual { grid-template-columns: 1fr; }
}
</style>

<script>
/* ─── Scroll-triggered fade-up ─────────────────────────────────────────── */
(function () {
  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.fade-up').forEach(function (el) {
    observer.observe(el);
  });
})();
</script>
