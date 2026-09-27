<?php
/**
 * single-tutorials.php — Single Tutorial Post
 *
 * Sections:
 *   1. Tutorial hero  (category, difficulty, title, subtitle, meta bar)
 *   2. Full-width video player  (YouTube facade / Vimeo / self-hosted / oEmbed)
 *   3. Two-column layout: article body LEFT + sticky sidebar RIGHT
 *      Body:    Gutenberg content · tags footer · author bio · share row
 *      Sidebar: Tutorial Info · TOC · More Topics · Newsletter
 *   4. Related Tutorials — 3-column grid
 *
 * Video types (set via "Video Settings" meta box on each tutorial):
 *   youtube · vimeo · self_hosted · oembed
 *
 * @package VitalStack
 */

get_header();

while ( have_posts() ) :
  the_post();

  $post_id   = get_the_ID();
  $permalink = get_permalink();

  // Category
  $cats     = get_the_terms( $post_id, 'tutorial_category' );
  $cat_name = ( $cats && ! is_wp_error( $cats ) ) ? esc_html( $cats[0]->name ) : 'Tutorial';
  $cat_url  = ( $cats && ! is_wp_error( $cats ) ) ? get_term_link( $cats[0] ) : '#';

  // Difficulty
  $diffs     = get_the_terms( $post_id, 'tutorial_difficulty' );
  $diff_name = ( $diffs && ! is_wp_error( $diffs ) ) ? esc_html( $diffs[0]->name ) : '';
  $diff_slug = ( $diffs && ! is_wp_error( $diffs ) ) ? sanitize_html_class( $diffs[0]->slug ) : '';

  // Video meta
  $duration    = get_post_meta( $post_id, 'tutorial_duration', true );
  $vid_type    = get_post_meta( $post_id, 'tutorial_video_type', true ) ?: 'youtube';
  $type_labels = array( 'youtube' => 'YouTube', 'vimeo' => 'Vimeo', 'self_hosted' => 'Video', 'oembed' => 'Video' );
  $type_label  = $type_labels[ $vid_type ] ?? 'Video';

  // Author
  $author_id  = get_the_author_meta( 'ID' );
  $author_bio = get_the_author_meta( 'description' );
  $initials   = vitalstack_author_initials( $author_id );
  $tags       = get_the_tags();

  // Subtitle
  $subtitle = get_post_meta( $post_id, 'post_subtitle', true );
  if ( ! $subtitle && has_excerpt() ) $subtitle = get_the_excerpt();
?>

<main id="main" class="site-main single-tutorial-page w3-inspired"><div class="vs-reading-progress"><span></span></div>

<!-- ══ 1. HERO ══════════════════════════════════════════════════════════════ -->
<section class="article-hero tut-single-hero"
         aria-label="<?php esc_attr_e( 'Tutorial header', 'vitalstack' ); ?>">
  <div class="hero-grid-overlay" aria-hidden="true"></div>
  <div class="container">
    <div class="article-hero-inner">

      <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'vitalstack' ); ?>">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'vitalstack' ); ?></a>
        <span>/</span>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'tutorials' ) ?: home_url( '/tutorials' ) ); ?>"><?php esc_html_e( 'Tutorials', 'vitalstack' ); ?></a>
        <?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
          <span>/</span>
          <a href="<?php echo esc_url( is_string( $cat_url ) ? $cat_url : '#' ); ?>"><?php echo $cat_name; ?></a>
        <?php endif; ?>
        <span>/</span>
        <span class="breadcrumb-current"><?php the_title(); ?></span>
      </nav>

      <!-- Category + difficulty + tag pills -->
      <div class="article-tags-row">
        <a href="<?php echo esc_url( is_string( $cat_url ) ? $cat_url : '#' ); ?>"
           class="tag tag-tutorial"><?php echo $cat_name; ?></a>
        <?php if ( $diff_name ) : ?>
          <span class="tut-difficulty tut-diff-<?php echo esc_attr( $diff_slug ); ?>">
            <?php echo $diff_name; ?>
          </span>
        <?php endif; ?>
        <?php if ( $tags ) :
          foreach ( array_slice( $tags, 0, 3 ) as $tag ) : ?>
            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
               class="tag tag-tutorial"><?php echo esc_html( $tag->name ); ?></a>
          <?php endforeach;
        endif; ?>
      </div>

      <h1 class="article-title"><?php the_title(); ?></h1>

      <?php if ( $subtitle ) : ?>
        <p class="article-subtitle"><?php echo esc_html( $subtitle ); ?></p>
      <?php endif; ?>

      <!-- Meta bar -->
      <div class="article-meta-bar">

        <div class="meta-author">
          <div class="author-avatar-wrap">
            <?php
            $av = get_avatar( $author_id, 44, '', '', array( 'class' => 'author-avatar-img' ) );
            echo $av ? $av : '<span class="author-initials">' . esc_html( $initials ) . '</span>';
            ?>
          </div>
          <div>
            <div class="meta-author-name">
              <?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ); ?>
            </div>
            <div class="meta-author-role">
              <?php echo esc_html( get_the_author_meta( 'user_title' ) ?: __( 'Instructor', 'vitalstack' ) ); ?>
            </div>
          </div>
        </div>

        <div class="meta-divider" aria-hidden="true"></div>

        <div class="meta-item">
          <div class="meta-label"><?php esc_html_e( 'Published', 'vitalstack' ); ?></div>
          <time class="meta-value" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
            <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?>
          </time>
        </div>

        <?php if ( $duration ) : ?>
        <div class="meta-divider" aria-hidden="true"></div>
        <div class="meta-item">
          <div class="meta-label"><?php esc_html_e( 'Duration', 'vitalstack' ); ?></div>
          <div class="meta-value">⏱ <?php echo esc_html( $duration ); ?></div>
        </div>
        <?php endif; ?>

        <div class="meta-divider" aria-hidden="true"></div>


      </div><!-- /.article-meta-bar -->
    </div><!-- /.article-hero-inner -->
  </div><!-- /.container -->
</section>


<!-- ══ 2. VIDEO PLAYER ══════════════════════════════════════════════════════ -->
<section class="tut-video-section"
         aria-label="<?php esc_attr_e( 'Tutorial video', 'vitalstack' ); ?>">
  <div class="container tut-video-container">
    <?php echo vitalstack_tutorial_video( $post_id, 'full' ); ?>
  </div>
</section>


<!-- ══ 3. CONTENT + SIDEBAR ════════════════════════════════════════════════ -->
<div class="container article-layout">
  <div class="article-layout-inner">

        <!-- ── Sticky Sidebar (RIGHT) ── -->
    <aside class="article-sidebar"
           aria-label="<?php esc_attr_e( 'Tutorial sidebar', 'vitalstack' ); ?>">
      <div class="sidebar-sticky">

        <!-- Tutorial Info card -->
        <div class="sidebar-widget tut-info-widget">
          <div class="sidebar-widget-title">
            <span aria-hidden="true">🎬</span>
            <?php esc_html_e( 'Tutorial Info', 'vitalstack' ); ?>
          </div>
          <ul class="tut-info-list">
            <?php if ( $cat_name ) : ?>
            <li>
              <span class="tut-info-label"><?php esc_html_e( 'Topic', 'vitalstack' ); ?></span>
              <span><?php echo $cat_name; ?></span>
            </li>
            <?php endif; ?>
            <?php if ( $diff_name ) : ?>
            <li>
              <span class="tut-info-label"><?php esc_html_e( 'Level', 'vitalstack' ); ?></span>
              <span class="tut-difficulty tut-diff-<?php echo esc_attr( $diff_slug ); ?>">
                <?php echo $diff_name; ?>
              </span>
            </li>
            <?php endif; ?>
            <?php if ( $duration ) : ?>
            <li>
              <span class="tut-info-label"><?php esc_html_e( 'Duration', 'vitalstack' ); ?></span>
              <span>⏱ <?php echo esc_html( $duration ); ?></span>
            </li>
            <?php endif; ?>

            <li>
              <span class="tut-info-label"><?php esc_html_e( 'Published', 'vitalstack' ); ?></span>
              <span><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></span>
            </li>
          </ul>
        </div>

        <!-- Table of Contents (auto-built by JS) -->
        <div class="sidebar-widget toc-card">
          <div class="sidebar-widget-title toc-header">
            <span aria-hidden="true">📋</span>
            <?php esc_html_e( 'Contents', 'vitalstack' ); ?>
          </div>
          <nav class="toc-body" aria-label="<?php esc_attr_e( 'Table of contents', 'vitalstack' ); ?>">
            <ul class="toc-list" id="toc-list">
              <li class="toc-placeholder">
                <span><?php esc_html_e( 'Loading…', 'vitalstack' ); ?></span>
              </li>
            </ul>
          </nav>
        </div>

        <!-- More Topics (tutorial categories) -->
        <?php
        $all_tut_cats = get_terms( array( 'taxonomy' => 'tutorial_category', 'hide_empty' => true ) );
        if ( $all_tut_cats && ! is_wp_error( $all_tut_cats ) ) : ?>
        <div class="sidebar-widget">
          <div class="sidebar-widget-title">
            <span aria-hidden="true">🗂</span>
            <?php esc_html_e( 'More Topics', 'vitalstack' ); ?>
          </div>
          <ul class="sidebar-cat-list">
            <?php foreach ( $all_tut_cats as $tc ) : ?>
              <li>
                <a href="<?php echo esc_url( get_term_link( $tc ) ); ?>">
                  <?php echo esc_html( $tc->name ); ?>
                  <span class="sidebar-cat-count"><?php echo (int) $tc->count; ?></span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- Sidebar newsletter -->
        <div class="sidebar-newsletter">
          <h3><?php esc_html_e( 'Stay Sharp', 'vitalstack' ); ?></h3>
          <p><?php esc_html_e( 'New tutorials weekly — straight to your inbox.', 'vitalstack' ); ?></p>
          <?php if ( shortcode_exists( 'mailchimp' ) ) : ?>
            <?php echo do_shortcode( '[mailchimp]' ); ?>
          <?php else : ?>
            <form class="sidebar-nl-form" method="post"
                  action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
              <input type="hidden" name="action" value="vitalstack_newsletter_signup">
              <?php wp_nonce_field( 'vitalstack_newsletter', 'vs_nonce' ); ?>
              <input class="nl-input" type="email" name="email"
                     placeholder="<?php esc_attr_e( 'your@email.com', 'vitalstack' ); ?>" required>
              <button type="submit" class="nl-btn">
                <?php esc_html_e( 'Subscribe Free →', 'vitalstack' ); ?>
              </button>
            </form>
          <?php endif; ?>
        </div>

      </div><!-- /.sidebar-sticky -->
    </aside><!-- /.article-sidebar -->

    <!-- ── Main Article Body (LEFT) ── -->
    <article class="article-body tut-article-body"
             id="article-content"
             itemscope itemtype="https://schema.org/LearningResource">

      <meta itemprop="name"          content="<?php echo esc_attr( get_the_title() ); ?>">
      <meta itemprop="datePublished" content="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
      <meta itemprop="author"        content="<?php echo esc_attr( get_the_author_meta( 'display_name', $author_id ) ); ?>">

      <!-- Tutorial description / notes -->
      <div class="post-body-content">
        <?php the_content(); ?>
      </div>

      <!-- Tags footer -->
      <?php if ( $tags ) : ?>
        <div class="article-tags-footer">
          <strong><?php esc_html_e( 'Tags:', 'vitalstack' ); ?></strong>
          <?php foreach ( $tags as $tag ) : ?>
            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
               class="tag tag-tutorial"><?php echo esc_html( $tag->name ); ?></a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Author bio -->
      <?php if ( $author_bio ) : ?>
      <div class="author-bio-block">
        <div class="author-bio-avatar">
          <?php
          $av = get_avatar( $author_id, 64 );
          if ( $av ) echo $av;
          else echo '<div class="author-avatar-initials">' . esc_html( $initials ) . '</div>';
          ?>
        </div>
        <div class="author-bio-info">
          <div class="author-bio-name">
            <?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ); ?>
          </div>
          <p class="author-bio-text"><?php echo esc_html( $author_bio ); ?></p>
        </div>
      </div>
      <?php endif; ?>

      <!-- Share row -->
      <div class="share-row" aria-label="<?php esc_attr_e( 'Share this tutorial', 'vitalstack' ); ?>">
        <span class="share-label"><?php esc_html_e( 'Share:', 'vitalstack' ); ?></span>

        <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( $permalink ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>"
           class="share-btn share-x" target="_blank" rel="noopener noreferrer">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="13" height="13" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622 5.912-5.622Zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          X (Twitter)
        </a>

        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo rawurlencode( $permalink ); ?>"
           class="share-btn share-linkedin" target="_blank" rel="noopener noreferrer">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="13" height="13" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452z"/></svg>
          LinkedIn
        </a>

        <button class="share-btn share-copy"
                onclick="navigator.clipboard.writeText('<?php echo esc_js( $permalink ); ?>').then(function(){this.textContent='✓ Copied!';}.bind(this));"
                aria-label="<?php esc_attr_e( 'Copy link', 'vitalstack' ); ?>">
          🔗 <?php esc_html_e( 'Copy Link', 'vitalstack' ); ?>
        </button>
      </div>

    </article><!-- /.article-body -->




  </div><!-- /.article-layout-inner -->
</div><!-- /.container.article-layout -->


<!-- ══ 4. RELATED TUTORIALS ════════════════════════════════════════════════ -->
<?php
$related_args = array(
  'post_type'      => 'tutorials',
  'posts_per_page' => 3,
  'post__not_in'   => array( $post_id ),
  'orderby'        => 'rand',
);
if ( $cats && ! is_wp_error( $cats ) ) {
  $related_args['tax_query'] = array( array(
    'taxonomy' => 'tutorial_category',
    'field'    => 'term_id',
    'terms'    => wp_list_pluck( $cats, 'term_id' ),
  ) );
}
$related = new WP_Query( $related_args );
if ( $related->have_posts() ) : ?>
  <section class="related-posts-section"
           aria-label="<?php esc_attr_e( 'Related tutorials', 'vitalstack' ); ?>">
    <div class="container">
      <h2 class="related-posts-title"><?php esc_html_e( 'More Tutorials', 'vitalstack' ); ?></h2>
      <div class="related-grid">
        <?php while ( $related->have_posts() ) : $related->the_post();
          $r_id       = get_the_ID();
          $r_cats     = get_the_terms( $r_id, 'tutorial_category' );
          $r_cat_name = ( $r_cats && ! is_wp_error( $r_cats ) ) ? esc_html( $r_cats[0]->name ) : 'Tutorial';
          $r_dur      = get_post_meta( $r_id, 'tutorial_duration', true );
        ?>
          <a href="<?php the_permalink(); ?>" class="related-card">
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail( 'vitalstack-thumb', array(
                'class' => 'related-thumb-img',
                'alt'   => esc_attr( get_the_title() ),
              ) ); ?>
            <?php else : ?>
              <div class="related-thumb"
                   style="background:linear-gradient(135deg,#0f1a2e,#1a1a4e);"
                   aria-hidden="true">🎬</div>
            <?php endif; ?>
            <div class="related-body">
              <span class="related-tag tag tag-tutorial"><?php echo $r_cat_name; ?></span>
              <div class="related-title-sm"><?php the_title(); ?></div>
              <div class="related-meta">
                <?php if ( $r_dur ) echo esc_html( $r_dur ) . ' · '; ?>
                <?php the_author(); ?>
              </div>
            </div>
          </a>
        <?php endwhile;
        wp_reset_postdata(); ?>
      </div>
    </div>
  </section>
<?php endif; ?>

</main><!-- /#main -->

<?php endwhile; ?>

<?php get_footer(); ?>

<script>
/* ─── Auto-build Table of Contents from H2/H3 in tutorial body ─── */
(function () {
  var tocList     = document.getElementById( 'toc-list' );
  var bodyContent = document.querySelector( '.post-body-content' );
  if ( ! tocList || ! bodyContent ) return;

  var headings = bodyContent.querySelectorAll( 'h2, h3' );
  if ( ! headings.length ) { tocList.innerHTML = ''; return; }

  var html = '', idx = 0;
  headings.forEach( function ( h ) {
    if ( ! h.id ) h.id = 'tut-h-' + ( idx++ );
    var isSub = h.tagName === 'H3';
    html += '<li class="toc-item' + ( isSub ? ' sub' : '' ) + '">' +
            '<a href="#' + h.id + '">' + h.textContent.trim() + '</a></li>';
  } );
  tocList.innerHTML = html;

  var tocLinks = tocList.querySelectorAll( 'a' );
  var observer = new IntersectionObserver( function ( entries ) {
    entries.forEach( function ( entry ) {
      if ( entry.isIntersecting ) {
        tocLinks.forEach( function ( l ) { l.closest( '.toc-item' ).classList.remove( 'active' ); } );
        var active = tocList.querySelector( 'a[href="#' + entry.target.id + '"]' );
        if ( active ) active.closest( '.toc-item' ).classList.add( 'active' );
      }
    } );
  }, { rootMargin: '-10% 0px -75% 0px' } );
  headings.forEach( function ( h ) { observer.observe( h ); } );
} )();
</script>


<script>
window.addEventListener('scroll',function(){
 const el=document.querySelector('.vs-reading-progress span');
 if(!el)return;
 const h=document.documentElement;
 const p=(h.scrollTop)/(h.scrollHeight-h.clientHeight)*100;
 el.style.width=p+'%';
});
</script>
