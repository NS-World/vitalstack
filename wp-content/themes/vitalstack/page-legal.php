<?php
/**
 * Template Name: Legal Page
 * Description: Premium legal pages (Privacy Policy, Terms, etc.)
 */

get_header();
?>

<main id="main" class="site-main legal-page">

  <!-- HERO - Matches Contact/About style -->
  <section class="legal-hero">
    <div class="ph-grid" aria-hidden="true"></div>
    <div class="container">
      <div class="legal-hero-inner">
        <nav class="breadcrumb">
          <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
          <span>/</span>
          <span><?php the_title(); ?></span>
        </nav>
        <h1 class="legal-title"><?php the_title(); ?></h1>
        <p class="last-updated">Last updated: <?php echo get_the_modified_date('F j, Y'); ?></p>
      </div>
    </div>
  </section>

  <!-- CONTENT -->
  <section class="legal-content-section">
    <div class="container">
      <div class="legal-grid">

        <!-- Main Content -->
        <article class="legal-main">
          <?php while ( have_posts() ) : the_post(); ?>
            <div class="legal-body">
              <?php the_content(); ?>
            </div>
          <?php endwhile; ?>
        </article>

        <!-- Sidebar TOC -->
        <aside class="legal-sidebar">
          <div class="toc-card">
            <div class="toc-header">
              <span>📋</span> On This Page
            </div>
            <nav id="legal-toc" class="toc-list"></nav>
          </div>
        </aside>

      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>

<script>
// Auto Table of Contents (same style as single pages)
document.addEventListener('DOMContentLoaded', function() {
  const content = document.querySelector('.legal-body');
  const toc = document.getElementById('legal-toc');
  if (!content || !toc) return;

  const headings = content.querySelectorAll('h2, h3');
  let html = '';

  headings.forEach((h, i) => {
    if (!h.id) h.id = 'legal-' + i;
    const indent = h.tagName === 'H3' ? 'toc-sub' : '';
    html += `<a href="#${h.id}" class="toc-link ${indent}">${h.textContent.trim()}</a>`;
  });

  toc.innerHTML = html;
});
</script>