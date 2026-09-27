<?php
/**
 * single.php — Single Post Template
 *
 * Sections:
 *   1. Article hero (category tag, title, subtitle, author bar, cover image)
 *   2. Two-column layout: article body + sticky sidebar
 *   3. Article body (Gutenberg content, info-boxes, blockquotes, code)
 *   4. Author bio block
 *   5. Share buttons row
 *   6. Subscribe CTA
 *   7. Related Posts (Keep Reading)
 *   8. Sticky sidebar: TOC, Share, Categories, Subscribe
 *
 * @package VitalStack
 */

get_header();

while ( have_posts() ) :
  the_post();

  $post_id    = get_the_ID();
  $cat_class  = vitalstack_get_cat_class( $post_id );
  $cats       = get_the_category( $post_id );
  $cat_name   = ! empty( $cats ) ? esc_html( $cats[0]->name ) : 'Article';
  $cat_url    = ! empty( $cats ) ? get_category_link( $cats[0]->term_id ) : '#';
  $read_time  = vitalstack_read_time( $post_id );
  $author_id  = get_the_author_meta( 'ID' );
  $initials   = vitalstack_author_initials( $author_id );
  $author_bio = get_the_author_meta( 'description' );
  $tags       = get_the_tags();
?>

<main id="main" class="site-main single-post-page">

<!-- ══════════════════════════════════════════════════════════════════════════
     1. ARTICLE HERO
     Content: auto from WP post (title, category, tags, author, date, read time)
     Subtitle: set via ACF custom field "post_subtitle" on each post
     Cover image: set Featured Image on each post (1200×675px recommended)
═══════════════════════════════════════════════════════════════════════════ -->
<section class="article-hero" aria-label="<?php esc_attr_e( 'Article header', 'vitalstack' ); ?>">
  <div class="hero-grid-overlay" aria-hidden="true"></div>
  <div class="container">
    <div class="article-hero-inner">

      <!-- Breadcrumb -->
      <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'vitalstack' ); ?>">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'vitalstack' ); ?></a>
        <span aria-hidden="true">/</span>
        <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>">
          <?php esc_html_e( 'Blog', 'vitalstack' ); ?>
        </a>
        <?php if ( ! empty( $cats ) ) : ?>
          <span aria-hidden="true">/</span>
          <a href="<?php echo esc_url( $cat_url ); ?>"><?php echo $cat_name; ?></a>
        <?php endif; ?>
        <span aria-hidden="true">/</span>
        <span class="breadcrumb-current"><?php the_title(); ?></span>
      </nav>

      <!-- Category + tags row -->
      <div class="article-tags-row">
        <a href="<?php echo esc_url( $cat_url ); ?>" class="tag <?php echo esc_attr( $cat_class ); ?>">
          <?php echo $cat_name; ?>
        </a>
        <?php if ( $tags ) : ?>
          <?php foreach ( array_slice( $tags, 0, 3 ) as $tag ) : ?>
            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
               class="tag <?php echo esc_attr( $cat_class ); ?>">
              <?php echo esc_html( $tag->name ); ?>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Article title -->
      <h1 class="article-title"><?php the_title(); ?></h1>

      <!-- Subtitle — set via custom field "post_subtitle" (ACF or any plugin) -->
      <?php
      $subtitle = get_post_meta( $post_id, 'post_subtitle', true );
      if ( ! $subtitle ) {
        // Fall back to excerpt if no custom subtitle set
        $subtitle = has_excerpt() ? get_the_excerpt() : '';
      }
      if ( $subtitle ) : ?>
        <p class="article-subtitle"><?php echo esc_html( $subtitle ); ?></p>
      <?php endif; ?>

      <!-- Author / date / read-time meta bar -->
      <div class="article-meta-bar">


        <div class="meta-item">
          <div class="meta-label"><?php esc_html_e( 'Published', 'vitalstack' ); ?></div>
          <time class="meta-value" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
            <?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
          </time>
        </div>

        <div class="meta-divider" aria-hidden="true"></div>

        <div class="meta-item">
          <div class="meta-label"><?php esc_html_e( 'Read Time', 'vitalstack' ); ?></div>
          <div class="meta-value"><?php echo esc_html( $read_time ); ?></div>
        </div>

        <?php if ( function_exists( 'pvc_get_post_views' ) ) : /* Post Views Counter plugin */ ?>
          <div class="meta-divider" aria-hidden="true"></div>
          <div class="meta-item">
            <div class="meta-label"><?php esc_html_e( 'Views', 'vitalstack' ); ?></div>
            <div class="meta-value"><?php echo esc_html( pvc_get_post_views( $post_id ) ); ?></div>
          </div>
        <?php endif; ?>

      </div><!-- /.article-meta-bar -->
    </div><!-- /.article-hero-inner -->

    <!-- Cover image or placeholder -->
    <div class="article-cover">
      <?php if ( has_post_thumbnail() ) : ?>
        <?php the_post_thumbnail( 'vitalstack-hero', array(
          'class' => 'article-cover-img',
          'alt'   => esc_attr( get_the_title() ),
        ) ); ?>
      <?php else : ?>
        <div class="article-cover-placeholder" aria-hidden="true">
          <?php echo $cat_class === 'health' ? '🌿' : '🧠'; ?>
        </div>
      <?php endif; ?>
    </div>

  </div><!-- /.container -->
</section>


<!-- ══════════════════════════════════════════════════════════════════════════
     2 + 3 + 4 + 5 + 6. ARTICLE LAYOUT: BODY + STICKY SIDEBAR
═══════════════════════════════════════════════════════════════════════════ -->
<div class="container article-layout">

  <!-- ── STICKY SIDEBAR (LEFT) ── -->
  <aside class="article-sidebar" role="complementary"
         aria-label="<?php esc_attr_e( 'Article sidebar', 'vitalstack' ); ?>">

    <?php if ( is_active_sidebar( 'sidebar-single' ) ) : ?>
      <?php dynamic_sidebar( 'sidebar-single' ); ?>

    <?php else : /* Default sidebar — add widgets via Appearance → Widgets → Single Post Sidebar */ ?>

      <!-- Table of Contents -->
      <div class="toc-card widget">
        <div class="toc-header">
          <span aria-hidden="true">📋</span>
          <?php esc_html_e( 'Table of Contents', 'vitalstack' ); ?>
        </div>
        <nav class="toc-body" aria-label="<?php esc_attr_e( 'Table of contents', 'vitalstack' ); ?>">
          <ul class="toc-list" id="toc-list">
            <!-- Auto-populated by JS from H2/H3 headings in post body -->
            <li class="toc-placeholder">
              <span><?php esc_html_e( 'Loading contents…', 'vitalstack' ); ?></span>
            </li>
          </ul>
        </nav>
      </div><!-- /.toc-card -->

      <!-- Share card -->
      <div class="share-card widget">
        <div class="share-card-title">
          <?php esc_html_e( 'Share this article', 'vitalstack' ); ?>
        </div>
        <div class="share-btns">
          <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>"
             class="share-btn" target="_blank" rel="noopener noreferrer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="14" height="14" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622 5.912-5.622Zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            <?php esc_html_e( 'Share on X', 'vitalstack' ); ?>
          </a>
          <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo rawurlencode( get_permalink() ); ?>"
             class="share-btn" target="_blank" rel="noopener noreferrer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="14" height="14" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452z"/></svg>
            <?php esc_html_e( 'Share on LinkedIn', 'vitalstack' ); ?>
          </a>
          <button class="share-btn" data-action="copy">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            <?php esc_html_e( 'Copy Link', 'vitalstack' ); ?>
          </button>
        </div>
      </div><!-- /.share-card -->

      <!-- Categories widget -->
      <div class="widget sidebar-card">
        <div class="sidebar-card-header">
          <span aria-hidden="true">📂</span>
          <h3 class="widget-title"><?php esc_html_e( 'Categories', 'vitalstack' ); ?></h3>
        </div>
        <div class="sidebar-card-body" style="padding:10px 14px;">
          <ul>
            <?php
            wp_list_categories( array(
              'show_count'   => true,
              'title_li'     => '',
              'hide_empty'   => true,
              'current_category' => ! empty( $cats ) ? $cats[0]->term_id : 0,
            ) );
            ?>
          </ul>
        </div>
      </div><!-- /.categories widget -->

      <!-- Sidebar subscribe -->
      <div class="widget sidebar-newsletter">
        <h3><?php esc_html_e( 'Subscribe for weekly deep-dives', 'vitalstack' ); ?></h3>
        <p><?php esc_html_e( 'AI and health insights straight to your inbox. No spam, ever.', 'vitalstack' ); ?></p>
        <?php if ( shortcode_exists( 'mailchimp' ) ) : ?>
          <?php echo do_shortcode( '[mailchimp]' ); ?>
        <?php else : ?>
          <form class="sidebar-nl-form" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
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

    <?php endif; // end default sidebar ?>

  </aside><!-- /.article-sidebar (left) -->


  <!-- ── ARTICLE BODY ── -->
  <article id="post-<?php echo esc_attr( $post_id ); ?>"
           class="article-body <?php post_class( '' ); ?>"
           itemscope itemtype="https://schema.org/Article">

    <meta itemprop="headline"       content="<?php echo esc_attr( get_the_title() ); ?>">
    <meta itemprop="datePublished"  content="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
    <meta itemprop="dateModified"   content="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>">
    <meta itemprop="author"         content="<?php echo esc_attr( get_the_author() ); ?>">

    <!-- ── Post content (Gutenberg blocks / classic editor) ── -->
    <div class="post-body-content" itemprop="articleBody">
      <?php the_content(); ?>
    </div>

    <!-- ── Author bio block ── -->
    <?php if ( $author_bio ) : ?>
    <div class="author-bio-block">
      <div class="bio-avatar-wrap">
        <?php
        $bio_avatar = get_avatar( $author_id, 64, '', '', array( 'class' => 'bio-avatar-img' ) );
        echo $bio_avatar
          ? $bio_avatar
          : '<span class="bio-initials">' . esc_html( $initials ) . '</span>';
        ?>
      </div>
      <div class="bio-content">
        <div class="bio-name"><?php the_author(); ?></div>
        <div class="bio-role">
          <?php echo esc_html( get_the_author_meta( 'user_title' ) ?: __( 'Contributor', 'vitalstack' ) ); ?>
        </div>
        <p class="bio-text"><?php echo esc_html( $author_bio ); ?></p>
      </div>
    </div>
    <?php endif; ?>

    <!-- ── Share buttons ── -->
    <div class="share-row-inline" aria-label="<?php esc_attr_e( 'Share this article', 'vitalstack' ); ?>">
      <span class="share-label-inline"><?php esc_html_e( 'Share:', 'vitalstack' ); ?></span>

      <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>"
         class="share-btn-inline" target="_blank" rel="noopener noreferrer"
         aria-label="<?php esc_attr_e( 'Share on X (Twitter)', 'vitalstack' ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="14" height="14" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622 5.912-5.622Zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        <?php esc_html_e( 'Share on X', 'vitalstack' ); ?>
      </a>

      <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo rawurlencode( get_permalink() ); ?>"
         class="share-btn-inline" target="_blank" rel="noopener noreferrer"
         aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'vitalstack' ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="14" height="14" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
        <?php esc_html_e( 'Share on LinkedIn', 'vitalstack' ); ?>
      </a>

      <button class="share-btn-inline" data-action="copy"
              aria-label="<?php esc_attr_e( 'Copy link', 'vitalstack' ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
        <?php esc_html_e( 'Copy Link', 'vitalstack' ); ?>
      </button>
    </div><!-- /.share-row-inline -->

    <!-- ── Subscribe CTA block (end of article) ── -->
    <div class="post-end-cta">
      <h3><?php esc_html_e( 'Enjoyed this article?', 'vitalstack' ); ?></h3>
      <p><?php esc_html_e( 'Subscribe for weekly deep-dives on AI and health — straight to your inbox.', 'vitalstack' ); ?></p>
      <?php if ( shortcode_exists( 'mailchimp' ) ) : ?>
        <?php echo do_shortcode( '[mailchimp]' ); ?>
      <?php else : ?>
        <form class="post-cta-form" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
          <input type="hidden" name="action" value="vitalstack_newsletter_signup">
          <?php wp_nonce_field( 'vitalstack_newsletter', 'vs_nonce' ); ?>
          <div class="post-cta-row">
            <input type="email" name="email" class="post-cta-input"
                   placeholder="<?php esc_attr_e( 'your@email.com', 'vitalstack' ); ?>" required>
            <button type="submit" class="btn btn-primary">
              <?php esc_html_e( 'Subscribe →', 'vitalstack' ); ?>
            </button>
          </div>
        </form>
      <?php endif; ?>
    </div><!-- /.post-end-cta -->

    <!-- ══════════════════════════════════════════════════════════════════════
         COMMENTS SECTION
         Uses WordPress native comments_template() with VitalStack styling.
         Enable/disable comments per-post via the Discussion meta box.
    ══════════════════════════════════════════════════════════════════════ -->
    <?php if ( comments_open() || get_comments_number() ) : ?>
    <section class="vs-comments-section" id="comments" aria-label="<?php esc_attr_e( 'Comments', 'vitalstack' ); ?>">

      <h2 class="vs-comments-title">
        <span aria-hidden="true">💬</span>
        <?php
        $comment_count = get_comments_number();
        if ( $comment_count ) {
          printf(
            esc_html( _n( '%s Comment', '%s Comments', $comment_count, 'vitalstack' ) ),
            '<span class="vs-comment-count">' . number_format_i18n( $comment_count ) . '</span>'
          );
        } else {
          esc_html_e( 'Leave a Comment', 'vitalstack' );
        }
        ?>
      </h2>

      <?php
      /* ── Render existing comments ── */
      $args_comment_list = array(
        'style'       => 'ol',
        'short_ping'  => true,
        'avatar_size' => 48,
        'callback'    => 'vitalstack_comment_callback',
      );

      if ( have_comments() ) : ?>
        <ol class="vs-comment-list" id="vs-comment-list">
          <?php wp_list_comments( $args_comment_list ); ?>
        </ol>

        <!-- Pagination for comments -->
        <?php the_comments_navigation( array(
          'prev_text' => '← ' . __( 'Older comments', 'vitalstack' ),
          'next_text' => __( 'Newer comments', 'vitalstack' ) . ' →',
        ) ); ?>

      <?php endif; ?>

      <?php if ( ! comments_open() && get_comments_number() > 0 ) : ?>
        <p class="vs-comments-closed"><?php esc_html_e( 'Comments are closed.', 'vitalstack' ); ?></p>
      <?php endif; ?>

      <?php if ( comments_open() ) : ?>
      <!-- ── Comment form ── -->
      <div class="vs-comment-form-wrap" id="respond">

        <?php
        $commenter     = wp_get_current_commenter();
        $req_fields    = get_option( 'require_name_email' );
        $required_text = $req_fields ? ' <span class="vs-required" aria-hidden="true">*</span>' : '';

        comment_form( array(
          'title_reply'          => '<span aria-hidden="true">✍️</span> ' . __( 'Join the Conversation', 'vitalstack' ),
          'title_reply_to'       => __( 'Reply to %s', 'vitalstack' ),
          'title_reply_before'   => '<h3 class="vs-reply-title" id="reply-title">',
          'title_reply_after'    => '</h3>',
          'cancel_reply_before'  => '<span class="vs-cancel-reply">',
          'cancel_reply_after'   => '</span>',
          'cancel_reply_link'    => __( 'Cancel reply', 'vitalstack' ),
          'label_submit'         => __( 'Post Comment →', 'vitalstack' ),
          'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s vs-submit-btn">%4$s</button>',
          'submit_field'         => '<div class="vs-form-submit">%1$s %2$s</div>',
          'comment_field'        => '<div class="vs-form-row vs-form-full"><label for="comment" class="vs-form-label">' . __( 'Comment', 'vitalstack' ) . $required_text . '</label><textarea id="comment" name="comment" class="vs-form-textarea" rows="6" maxlength="65525" required></textarea></div>',
          'fields'               => array(
            'author' => '<div class="vs-form-row"><label for="author" class="vs-form-label">' . __( 'Name', 'vitalstack' ) . ( $req_fields ? $required_text : '' ) . '</label><input id="author" name="author" type="text" class="vs-form-input" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . ( $req_fields ? ' required' : '' ) . '></div>',
            'email'  => '<div class="vs-form-row"><label for="email" class="vs-form-label">' . __( 'Email', 'vitalstack' ) . ( $req_fields ? $required_text : '' ) . '<span class="vs-field-note">' . __( 'Not published', 'vitalstack' ) . '</span></label><input id="email" name="email" type="email" class="vs-form-input" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . ( $req_fields ? ' required' : '' ) . '></div>',
            'url'    => '<div class="vs-form-row"><label for="url" class="vs-form-label">' . __( 'Website', 'vitalstack' ) . ' <span class="vs-field-note">' . __( 'Optional', 'vitalstack' ) . '</span></label><input id="url" name="url" type="url" class="vs-form-input" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30"></div>',
            'cookies'=> '<div class="vs-form-cookies"><label class="vs-cookie-label"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . ( isset( $_COOKIE[ 'comment_author_' . COOKIEHASH ] ) ? ' checked' : '' ) . '> ' . __( 'Save my name and email for next time.', 'vitalstack' ) . '</label></div>',
          ),
          'comment_notes_before' => '',
          'comment_notes_after'  => '',
          'class_form'           => 'vs-comment-form',
          'class_submit'         => 'btn btn-primary',
          'logged_in_as'         => '',
        ) );
        ?>
      </div><!-- /.vs-comment-form-wrap -->
      <?php endif; // comments_open ?>

    </section><!-- /.vs-comments-section -->
    <?php endif; // comments_open || have_comments ?>

  </article><!-- /.article-body -->

</div><!-- /.article-layout -->


<!-- ══════════════════════════════════════════════════════════════════════════
     7. RELATED POSTS (Keep Reading)
     Pulls 3 posts from the same category, excludes current post
═══════════════════════════════════════════════════════════════════════════ -->
<?php
$related_args = array(
  'posts_per_page'      => 3,
  'post__not_in'        => array( $post_id ),
  'category__in'        => ! empty( $cats ) ? array( $cats[0]->term_id ) : array(),
  'ignore_sticky_posts' => 1,
  'orderby'             => 'rand',
);
$related_query = new WP_Query( $related_args );

if ( $related_query->have_posts() ) : ?>
  <section class="related-posts-section" aria-label="<?php esc_attr_e( 'Related posts', 'vitalstack' ); ?>">
    <div class="container">
      <h2 class="related-title"><?php esc_html_e( 'Keep Reading', 'vitalstack' ); ?></h2>
      <div class="related-grid">
        <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
          <?php
          $r_id       = get_the_ID();
          $r_cat      = get_the_category( $r_id );
          $r_cat_name = ! empty( $r_cat ) ? esc_html( $r_cat[0]->name ) : 'Article';
          $r_class    = vitalstack_get_cat_class( $r_id );
          $r_time     = vitalstack_read_time( $r_id );
          $r_author   = get_the_author();
          ?>
          <a href="<?php the_permalink(); ?>" class="related-card">
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail( 'vitalstack-thumb', array(
                'class' => 'related-thumb-img',
                'alt'   => esc_attr( get_the_title() ),
              ) ); ?>
            <?php else : ?>
              <div class="related-thumb <?php echo esc_attr( $r_class ); ?>-bg" aria-hidden="true">
                <?php echo $r_class === 'health' ? '🌿' : '🤖'; ?>
              </div>
            <?php endif; ?>
            <div class="related-body">
              <span class="related-tag tag <?php echo esc_attr( $r_class ); ?>"><?php echo $r_cat_name; ?></span>
              <div class="related-title-sm"><?php the_title(); ?></div>
              <div class="related-meta">
                <?php echo esc_html( $r_time ); ?> · <?php echo esc_html( $r_author ); ?>
              </div>
            </div>
          </a>
        <?php endwhile;
        wp_reset_postdata(); ?>
      </div><!-- /.related-grid -->
    </div>
  </section>
<?php endif; ?>

</main><!-- /#main -->

<?php endwhile; ?>

<?php get_footer(); ?>


<?php /* ─── Single post CSS ──────────────────────────────────────────────── */ ?>
<style id="vs-single-styles">

/* ── Article hero ── */
.article-hero {
  background: linear-gradient(135deg, var(--vs-navy) 0%, var(--vs-navy-mid) 100%);
  padding: 56px 0 48px;
  position: relative; overflow: hidden;
}
.article-hero-inner { max-width: 760px; }
.article-tags-row { display: flex; gap: 8px; flex-wrap: wrap; margin: 16px 0 20px; }

.article-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(26px, 3.5vw, 46px);
  font-weight: 900; line-height: 1.18;
  color: var(--vs-white); margin-bottom: 16px;
}
.article-subtitle {
  font-size: 18px; line-height: 1.65;
  color: rgba(255,255,255,.65); margin-bottom: 28px; max-width: 680px;
}

/* Meta bar */
.article-meta-bar {
  display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
  padding: 20px 0; border-top: 1px solid rgba(255,255,255,.1);
}
.meta-author { display: flex; align-items: center; gap: 12px; }
.author-avatar-wrap {
  width: 44px; height: 44px; border-radius: 50%;
  overflow: hidden; flex-shrink: 0;
  background: var(--vs-navy-light);
  display: flex; align-items: center; justify-content: center;
}
.author-avatar-img { width: 100%; height: 100%; object-fit: cover; }
.author-initials {
  font-size: 14px; font-weight: 700; color: var(--vs-navy);
}
.meta-author-name { font-size: 14px; font-weight: 600; color: var(--vs-white); }
.meta-author-role { font-size: 12px; color: rgba(255,255,255,.45); }
.meta-divider {
  width: 1px; height: 32px; background: rgba(255,255,255,.12); flex-shrink: 0;
}
.meta-item { }
.meta-label { font-size: 10px; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.4); font-family: 'Space Mono', monospace; margin-bottom: 3px; }
.meta-value { font-size: 14px; font-weight: 600; color: rgba(255,255,255,.85); }

/* Cover image / placeholder */
.article-cover {
  margin-top: 40px;
  border-radius: 16px; overflow: hidden;
  max-height: 420px;
}
.article-cover-img {
  width: 100%; max-height: 420px; object-fit: cover; display: block;
}
.article-cover-placeholder {
  height: 280px; display: flex; align-items: center; justify-content: center;
  font-size: 80px;
  background: linear-gradient(135deg, rgba(29,138,78,.25), rgba(18,32,69,.5));
}

/* ── Article layout — sidebar LEFT ── */
.article-layout {
  display: grid;
  grid-template-columns: 280px 1fr;   /* sidebar first = left column */
  gap: 56px;
  padding-top: 56px;
  padding-bottom: 80px;
  align-items: start;
}

/* ── Article body content ── */
.article-body { min-width: 0; }
.post-body-content { font-size: 17px; line-height: 1.78; color: var(--vs-text); }
.post-body-content h2 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(20px, 2.4vw, 28px); font-weight: 700;
  margin: 48px 0 18px; color: var(--vs-text);
  scroll-margin-top: 90px;
}
.post-body-content h3 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(17px, 2vw, 22px); font-weight: 700;
  margin: 36px 0 14px; color: var(--vs-text);
  scroll-margin-top: 90px;
}
.post-body-content p   { margin-bottom: 22px; }
.post-body-content strong { font-weight: 600; }
.post-body-content a   { color: var(--vs-green); border-bottom: 1px solid var(--vs-green-pale); transition: color .2s; }
.post-body-content a:hover { color: var(--vs-green-light); border-bottom-color: var(--vs-green-light); }
.post-body-content ul,
.post-body-content ol  { margin: 20px 0 20px 24px; }
.post-body-content ul  { list-style: disc; }
.post-body-content ol  { list-style: decimal; }
.post-body-content li  { margin-bottom: 8px; line-height: 1.7; }

/* Code blocks */
.post-body-content pre {
  background: var(--vs-navy); border-radius: var(--vs-radius);
  padding: 24px; margin: 32px 0; overflow-x: auto;
}
.post-body-content pre code {
  font-family: 'Space Mono', monospace; font-size: 13px;
  color: var(--vs-green-light); line-height: 1.7; background: none; border: none; padding: 0;
}
.post-body-content code {
  font-family: 'Space Mono', monospace; font-size: 13px;
  background: var(--vs-off-white); border: 1px solid var(--vs-border);
  padding: 2px 7px; border-radius: 4px; color: var(--vs-navy);
}

/* Blockquote */
.post-body-content blockquote {
  border-left: 4px solid var(--vs-green);
  padding: 20px 24px;
  background: var(--vs-off-white);
  border-radius: 0 var(--vs-radius) var(--vs-radius) 0;
  margin: 32px 0;
}
.post-body-content blockquote p {
  font-family: 'Playfair Display', serif; font-size: 19px; font-style: italic;
  color: var(--vs-navy); margin-bottom: 8px;
}
.post-body-content blockquote cite { font-size: 13px; color: var(--vs-muted); font-style: normal; }

/* Info-box / key-takeaway (custom Gutenberg blocks or HTML blocks) */
.post-body-content .info-box,
.post-body-content .wp-block-info-box {
  background: var(--vs-navy-light); border-radius: var(--vs-radius);
  padding: 20px 24px; margin: 28px 0;
  border-left: 4px solid var(--vs-navy-mid);
}
.post-body-content .info-box-title { font-weight: 700; margin-bottom: 8px; }
.post-body-content .key-takeaway {
  background: var(--vs-green-pale); border-radius: var(--vs-radius);
  padding: 20px 24px; margin: 28px 0;
  border-left: 4px solid var(--vs-green);
}
.post-body-content .kt-label { font-weight: 700; color: var(--vs-green); margin-bottom: 8px; }
.post-body-content .kt-text  { font-size: 15px; color: var(--vs-text); line-height: 1.65; }

/* Gutenberg image blocks */
.post-body-content figure { margin: 32px 0; }
.post-body-content figure img { border-radius: var(--vs-radius); width: 100%; }
.post-body-content figcaption { font-size: 13px; color: var(--vs-muted); text-align: center; margin-top: 8px; }

/* ── Author bio block ── */
.author-bio-block {
  display: flex; gap: 20px; align-items: flex-start;
  background: var(--vs-off-white); border: 1px solid var(--vs-border);
  border-radius: var(--vs-radius); padding: 28px; margin: 48px 0 32px;
}
.bio-avatar-wrap {
  width: 64px; height: 64px; border-radius: 50%; overflow: hidden; flex-shrink: 0;
  background: var(--vs-navy-light); display: flex; align-items: center; justify-content: center;
}
.bio-avatar-img { width: 100%; height: 100%; object-fit: cover; }
.bio-initials { font-size: 20px; font-weight: 700; color: var(--vs-navy); }
.bio-name { font-size: 16px; font-weight: 700; color: var(--vs-text); margin-bottom: 2px; }
.bio-role { font-size: 12px; color: var(--vs-muted); margin-bottom: 10px; }
.bio-text { font-size: 14px; color: var(--vs-muted); line-height: 1.65; }

/* ── Share row (inline, below article) ── */
.share-row-inline {
  display: flex; align-items: center; gap: 10px;
  flex-wrap: wrap; margin: 8px 0 32px;
  padding: 20px 24px;
  background: var(--vs-off-white); border: 1px solid var(--vs-border);
  border-radius: var(--vs-radius);
}
.share-label-inline { font-size: 13px; font-weight: 600; color: var(--vs-muted); }
.share-btn-inline {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 16px; border-radius: 8px;
  font-size: 13px; font-weight: 600;
  border: 1px solid var(--vs-border); background: var(--vs-white);
  color: var(--vs-text); cursor: pointer; transition: all .2s;
  text-decoration: none; font-family: 'DM Sans', sans-serif;
}
.share-btn-inline:hover { background: var(--vs-navy); color: var(--vs-white); border-color: var(--vs-navy); }

/* ── Post-end subscribe CTA ── */
.post-end-cta {
  background: linear-gradient(135deg, var(--vs-navy) 0%, var(--vs-navy-mid) 100%);
  border-radius: var(--vs-radius); padding: 40px;
  text-align: center; margin: 32px 0 0;
}
.post-end-cta h3 {
  font-family: 'Playfair Display', serif;
  font-size: 24px; font-weight: 700; color: var(--vs-white); margin-bottom: 8px;
}
.post-end-cta p { color: rgba(255,255,255,.65); font-size: 15px; margin-bottom: 20px; }
.post-cta-row { display: flex; gap: 10px; max-width: 440px; margin: 0 auto; }
.post-cta-input {
  flex: 1; padding: 13px 16px; border-radius: 8px;
  border: 1px solid rgba(255,255,255,.2); background: rgba(255,255,255,.1);
  color: var(--vs-white); font-size: 15px; font-family: 'DM Sans', sans-serif; outline: none;
}
.post-cta-input::placeholder { color: rgba(255,255,255,.4); }
.post-cta-input:focus { border-color: var(--vs-green-light); }

/* ── Sticky sidebar ── */
.article-sidebar {
  position: sticky; top: 84px;
  display: flex; flex-direction: column; gap: 16px;
}

/* TOC card */
.toc-card {
  background: var(--vs-white); border: 1px solid var(--vs-border);
  border-radius: var(--vs-radius); overflow: hidden;
}
.toc-header {
  display: flex; align-items: center; gap: 8px;
  padding: 14px 16px; background: var(--vs-off-white);
  border-bottom: 1px solid var(--vs-border);
  font-family: 'Space Mono', monospace; font-size: 11px;
  letter-spacing: .1em; text-transform: uppercase;
  color: var(--vs-muted); font-weight: 700;
}
.toc-body { padding: 12px 0; }
.toc-list { list-style: none; }
.toc-item a {
  display: block; padding: 8px 16px;
  font-size: 13px; color: var(--vs-muted);
  border-left: 2px solid transparent;
  transition: all .2s; line-height: 1.4;
}
.toc-item a:hover { color: var(--vs-green); border-left-color: var(--vs-green-pale); }
.toc-item.active a { color: var(--vs-green); border-left-color: var(--vs-green); font-weight: 600; }
.toc-item.sub a { padding-left: 28px; font-size: 12px; }
.toc-placeholder { padding: 12px 16px; font-size: 13px; color: var(--vs-border); }

/* Share card */
.share-card {
  background: var(--vs-white); border: 1px solid var(--vs-border);
  border-radius: var(--vs-radius); padding: 16px;
}
.share-card-title {
  font-family: 'Space Mono', monospace; font-size: 11px;
  letter-spacing: .1em; text-transform: uppercase;
  color: var(--vs-muted); font-weight: 700; margin-bottom: 12px;
}
.share-btns { display: flex; flex-direction: column; gap: 8px; }
.share-btn {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 14px; border-radius: 8px;
  border: 1px solid var(--vs-border); background: var(--vs-white);
  font-size: 13px; font-weight: 600; color: var(--vs-text);
  cursor: pointer; transition: all .2s; font-family: 'DM Sans', sans-serif;
  text-decoration: none;
}
.share-btn:hover { background: var(--vs-navy); color: var(--vs-white); border-color: var(--vs-navy); }

/* Sidebar categories list override */
.article-sidebar .sidebar-card .widget ul { list-style: none; }
.article-sidebar .sidebar-card .widget ul li { border-bottom: 1px solid var(--vs-border); }
.article-sidebar .sidebar-card .widget ul li:last-child { border-bottom: none; }
.article-sidebar .sidebar-card .widget ul li a {
  display: flex; justify-content: space-between;
  padding: 9px 0; font-size: 13px; color: var(--vs-text);
}
.article-sidebar .sidebar-card .widget ul li a:hover { color: var(--vs-green); }
.article-sidebar .sidebar-card .widget ul li.current-cat > a { color: var(--vs-green); font-weight: 600; }

/* ── Related posts ── */
.related-posts-section {
  background: var(--vs-off-white);
  padding: 64px 0;
  border-top: 1px solid var(--vs-border);
}
.related-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(22px, 2.5vw, 30px); font-weight: 900;
  color: var(--vs-navy); margin-bottom: 32px;
}
.related-grid {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;
}
.related-card {
  background: var(--vs-white); border: 1px solid var(--vs-border);
  border-radius: var(--vs-radius); overflow: hidden;
  box-shadow: var(--vs-card-shadow); transition: transform .3s, box-shadow .3s;
  text-decoration: none; display: block;
}
.related-card:hover { transform: translateY(-4px); box-shadow: var(--vs-card-hover); }
.related-thumb { height: 160px; display: flex; align-items: center; justify-content: center; font-size: 48px; }
.related-thumb-img { width: 100%; height: 160px; object-fit: cover; display: block; }
.related-body { padding: 16px; }
.related-tag { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
.related-tag.ai     { color: var(--vs-navy); }
.related-tag.health { color: var(--vs-green); }
.related-title-sm {
  font-family: 'Playfair Display', serif; font-size: 15px;
  font-weight: 700; color: var(--vs-text); line-height: 1.35;
  margin: 8px 0 6px;
}
.related-card:hover .related-title-sm { color: var(--vs-green); }
.related-meta { font-size: 12px; color: var(--vs-muted); }

/* Responsive */
@media (max-width: 1024px) {
  .article-layout { grid-template-columns: 1fr; }
  .article-sidebar { position: static; }
  .related-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 640px) {
  .article-hero { padding: 40px 0 32px; }
  .article-meta-bar { gap: 12px; }
  .meta-divider { display: none; }
  .post-cta-row { flex-direction: column; }
  .related-grid { grid-template-columns: 1fr; }
  .author-bio-block { flex-direction: column; }
}
/* ══════════════════════════════════════════════════════════════════════
   COMMENTS SECTION
══════════════════════════════════════════════════════════════════════ */

.vs-comments-section {
  margin-top: 56px;
  border-top: 2px solid var(--vs-border);
  padding-top: 48px;
}

/* Title */
.vs-comments-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(20px, 2.2vw, 26px);
  font-weight: 800;
  color: var(--vs-navy);
  margin-bottom: 32px;
  display: flex; align-items: center; gap: 10px;
}
.vs-comment-count {
  display: inline-flex; align-items: center; justify-content: center;
  min-width: 28px; height: 28px; padding: 0 8px;
  background: var(--vs-green); color: #fff;
  border-radius: 14px; font-size: 13px; font-weight: 700;
  font-family: 'Space Mono', monospace;
}

/* Comment list */
.vs-comment-list {
  list-style: none;
  display: flex; flex-direction: column; gap: 0;
  margin-bottom: 48px;
}
.vs-comment-item {
  padding: 28px 0;
  border-bottom: 1px solid var(--vs-border);
}
.vs-comment-item:first-child { border-top: 1px solid var(--vs-border); }
.vs-comment-inner { display: flex; gap: 16px; }

/* Avatar */
.vs-comment-avatar {
  flex-shrink: 0; width: 44px; height: 44px;
  border-radius: 50%; overflow: hidden;
  background: var(--vs-navy-light);
  display: flex; align-items: center; justify-content: center;
}
.vs-comment-avatar img { width: 100%; height: 100%; object-fit: cover; }

/* Body */
.vs-comment-body { flex: 1; min-width: 0; }
.vs-comment-meta {
  display: flex; align-items: center; gap: 10px;
  flex-wrap: wrap; margin-bottom: 10px;
}
.vs-comment-author {
  font-size: 14px; font-weight: 700; color: var(--vs-text);
}
.vs-comment-author a { color: inherit; text-decoration: none; }
.vs-comment-author a:hover { color: var(--vs-green); }
.vs-comment-date {
  font-size: 12px; color: var(--vs-muted);
  font-family: 'Space Mono', monospace;
}
.vs-comment-date a { color: inherit; text-decoration: none; }
.vs-awaiting-moderation {
  display: inline-block; font-size: 11px; font-weight: 600;
  background: #fff3cd; color: #856404;
  border-radius: 4px; padding: 2px 7px;
  border: 1px solid #ffeeba;
}
.vs-comment-text {
  font-size: 15px; line-height: 1.75; color: var(--vs-text);
}
.vs-comment-text p { margin-bottom: 12px; }
.vs-comment-text p:last-child { margin-bottom: 0; }

/* Reply link */
.vs-comment-reply { margin-top: 12px; }
.vs-comment-reply .comment-reply-link {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 12px; font-weight: 600; color: var(--vs-muted);
  text-decoration: none; transition: color .2s;
  border: 1px solid var(--vs-border); border-radius: 6px;
  padding: 5px 12px; background: var(--vs-off-white);
}
.vs-comment-reply .comment-reply-link:hover {
  color: var(--vs-green); border-color: var(--vs-green);
  background: var(--vs-green-pale);
}

/* Nested children */
.vs-comment-children {
  list-style: none;
  padding-left: 40px;
  margin-top: 0;
  border-left: 2px solid var(--vs-border);
}
.vs-comment-children .vs-comment-item {
  padding: 20px 0 20px 20px;
  border-bottom: none;
}
.vs-comment-children .vs-comment-item:not(:last-child) {
  border-bottom: 1px solid var(--vs-border);
}

/* Comments closed notice */
.vs-comments-closed {
  font-size: 14px; color: var(--vs-muted);
  padding: 16px 20px; background: var(--vs-off-white);
  border-radius: var(--vs-radius); border: 1px solid var(--vs-border);
  margin-bottom: 32px;
}

/* Pagination */
.comments-navigation {
  margin: 24px 0 40px;
  display: flex; align-items: center; justify-content: space-between;
}
.comments-navigation .nav-links { display: flex; gap: 12px; width: 100%; justify-content: space-between; }
.comments-navigation a {
  font-size: 13px; font-weight: 600; color: var(--vs-text);
  border: 1px solid var(--vs-border); border-radius: 8px;
  padding: 8px 16px; text-decoration: none; transition: all .2s;
  background: var(--vs-white);
}
.comments-navigation a:hover { background: var(--vs-navy); color: #fff; border-color: var(--vs-navy); }

/* ── Comment Form ── */
.vs-comment-form-wrap {
  background: var(--vs-off-white);
  border: 1px solid var(--vs-border);
  border-radius: var(--vs-radius);
  padding: 36px;
  margin-top: 8px;
}
.vs-reply-title {
  font-family: 'Playfair Display', serif;
  font-size: 20px; font-weight: 700;
  color: var(--vs-navy); margin-bottom: 24px;
  display: flex; align-items: center; gap: 8px;
}
.vs-cancel-reply { margin-left: 12px; }
.vs-cancel-reply a {
  font-size: 13px; color: var(--vs-muted); font-family: 'DM Sans', sans-serif;
  text-decoration: none; font-weight: 500;
}
.vs-cancel-reply a:hover { color: var(--vs-green); }

.vs-comment-form {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}
.vs-form-row {
  display: flex; flex-direction: column; gap: 6px;
}
.vs-form-full { grid-column: 1 / -1; }
.vs-form-label {
  font-size: 12px; font-weight: 700; text-transform: uppercase;
  letter-spacing: .07em; color: var(--vs-muted);
  font-family: 'Space Mono', monospace;
  display: flex; align-items: center; gap: 8px;
}
.vs-required { color: var(--vs-green); font-size: 14px; }
.vs-field-note {
  font-size: 10px; font-weight: 400; color: var(--vs-border);
  text-transform: none; letter-spacing: 0; font-family: 'DM Sans', sans-serif;
}
.vs-form-input,
.vs-form-textarea {
  width: 100%; padding: 12px 14px;
  border: 1px solid var(--vs-border); border-radius: 8px;
  background: var(--vs-white); color: var(--vs-text);
  font-size: 15px; font-family: 'DM Sans', sans-serif;
  transition: border-color .2s, box-shadow .2s; outline: none;
  box-sizing: border-box;
}
.vs-form-input:focus,
.vs-form-textarea:focus {
  border-color: var(--vs-green);
  box-shadow: 0 0 0 3px var(--vs-green-pale);
}
.vs-form-textarea { resize: vertical; min-height: 130px; }

.vs-form-cookies { grid-column: 1 / -1; }
.vs-cookie-label {
  display: flex; align-items: center; gap: 8px;
  font-size: 13px; color: var(--vs-muted); cursor: pointer;
}
.vs-cookie-label input[type="checkbox"] { accent-color: var(--vs-green); }

.vs-form-submit { grid-column: 1 / -1; display: flex; align-items: center; gap: 16px; }
.vs-submit-btn {
  padding: 13px 28px; border-radius: 8px;
  background: var(--vs-navy); color: var(--vs-white);
  font-size: 15px; font-weight: 700; font-family: 'DM Sans', sans-serif;
  border: none; cursor: pointer; transition: background .2s, transform .15s;
  letter-spacing: .02em;
}
.vs-submit-btn:hover { background: var(--vs-green); transform: translateY(-1px); }

/* Logged-in note */
.logged-in-as {
  grid-column: 1 / -1;
  font-size: 13px; color: var(--vs-muted); margin-bottom: 8px;
}
.logged-in-as a { color: var(--vs-green); }

/* Responsive adjustments for comments */
@media (max-width: 640px) {
  .vs-comment-form { grid-template-columns: 1fr; }
  .vs-comment-form-wrap { padding: 24px; }
  .vs-comment-children { padding-left: 20px; }
  .vs-comment-inner { gap: 12px; }
}
</style>

<script>
/* ─── Auto-build Table of Contents from H2/H3 in post body ─── */
(function () {
  var tocList    = document.getElementById('toc-list');
  var bodyContent = document.querySelector('.post-body-content');
  if (!tocList || !bodyContent) return;

  var headings = bodyContent.querySelectorAll('h2, h3');
  if (!headings.length) {
    tocList.innerHTML = '';
    return;
  }

  var html = '';
  var idx  = 0;
  headings.forEach(function (h) {
    if (!h.id) {
      h.id = 'vs-heading-' + (idx++);
    }
    var isSub  = h.tagName === 'H3';
    var text   = h.textContent.trim();
    html += '<li class="toc-item' + (isSub ? ' sub' : '') + '">' +
            '<a href="#' + h.id + '">' + text + '</a></li>';
  });
  tocList.innerHTML = html;

  /* TOC active-item on scroll */
  var tocLinks = tocList.querySelectorAll('a');
  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        tocLinks.forEach(function (l) { l.closest('.toc-item').classList.remove('active'); });
        var active = tocList.querySelector('a[href="#' + entry.target.id + '"]');
        if (active) active.closest('.toc-item').classList.add('active');
      }
    });
  }, { rootMargin: '-10% 0px -80% 0px' });

  headings.forEach(function (h) { observer.observe(h); });
})();
</script>
