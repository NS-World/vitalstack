<?php
/**
 * single-news.php — Single News Post Template
 *
 * Sections:
 *   1. Article hero  (category tags, title, subtitle, author/date/read-time bar, cover image)
 *   2. Two-column layout: sticky sidebar LEFT + article body RIGHT
 *      Sidebar:  📋 Table of Contents (auto-built from H2/H3)
 *                🔗 Share card
 *                📂 Categories widget
 *                📧 Sidebar newsletter
 *      Body:     Gutenberg content  ·  author bio  ·  share row  ·  subscribe CTA  ·  comments
 *   3. Related Posts — "Keep Reading" 3-column grid
 *
 * @package VitalStack
 */

get_header();

while ( have_posts() ) :
  the_post();

  $post_id    = get_the_ID();
  $cat_class  = vitalstack_get_cat_class( $post_id );
  $cats       = get_the_terms( $post_id, 'news_category' );
  $cat_name   = ( $cats && ! is_wp_error( $cats ) ) ? esc_html( $cats[0]->name ) : 'News';
  $cat_url    = ( $cats && ! is_wp_error( $cats ) ) ? get_term_link( $cats[0] ) : '#';
  $read_time  = vitalstack_read_time( $post_id );
  $author_id  = get_the_author_meta( 'ID' );
  $initials   = vitalstack_author_initials( $author_id );
  $author_bio = get_the_author_meta( 'description' );
  $tags       = get_the_tags();
  $subtitle   = get_post_meta( $post_id, 'post_subtitle', true );
  if ( ! $subtitle ) $subtitle = has_excerpt() ? get_the_excerpt() : '';
?>

<main id="main" class="site-main single-post-page">

<!-- ══ 1. HERO ══════════════════════════════════════════════════════════════ -->
<section class="article-hero" aria-label="<?php esc_attr_e( 'Article header', 'vitalstack' ); ?>">
  <div class="hero-grid-overlay" aria-hidden="true"></div>
  <div class="container">
    <div class="article-hero-inner">

      <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'vitalstack' ); ?>">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'vitalstack' ); ?></a>
        <span>/</span>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'news' ) ?: home_url( '/news' ) ); ?>"><?php esc_html_e( 'News', 'vitalstack' ); ?></a>
        <?php if ( ! empty( $cats ) ) : ?>
          <span>/</span>
          <a href="<?php echo esc_url( is_string( $cat_url ) ? $cat_url : '#' ); ?>"><?php echo $cat_name; ?></a>
        <?php endif; ?>
        <span>/</span>
        <span class="breadcrumb-current"><?php the_title(); ?></span>
      </nav>

      <!-- Category + tag pills -->
      <div class="article-tags-row">
        <a href="<?php echo esc_url( is_string( $cat_url ) ? $cat_url : '#' ); ?>"
           class="tag tag-news"><?php echo $cat_name; ?></a>
        <?php if ( $tags ) :
          foreach ( array_slice( $tags, 0, 3 ) as $tag ) : ?>
            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
               class="tag tag-news"><?php echo esc_html( $tag->name ); ?></a>
          <?php endforeach;
        endif; ?>
      </div>

      <!-- Title -->
      <h1 class="article-title"><?php the_title(); ?></h1>

      <!-- Subtitle / excerpt -->
      <?php if ( $subtitle ) : ?>
        <p class="article-subtitle"><?php echo esc_html( $subtitle ); ?></p>
      <?php endif; ?>

      <!-- Author / date / read-time meta bar -->
      <div class="article-meta-bar">

        <div class="meta-author">
          <div class="author-avatar-wrap">
            <?php
            $av = get_avatar( $author_id, 44, '', '', array( 'class' => 'author-avatar-img' ) );
            echo $av ? $av : '<span class="author-initials">' . esc_html( $initials ) . '</span>';
            ?>
          </div>
          <div>
            <div class="meta-author-name"><?php the_author(); ?></div>
            <div class="meta-author-role"><?php echo esc_html( get_the_author_meta( 'user_title' ) ?: __( 'Contributor', 'vitalstack' ) ); ?></div>
          </div>
        </div>

        <div class="meta-divider" aria-hidden="true"></div>

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

        <?php if ( function_exists( 'pvc_get_post_views' ) ) : ?>
          <div class="meta-divider" aria-hidden="true"></div>
          <div class="meta-item">
            <div class="meta-label"><?php esc_html_e( 'Views', 'vitalstack' ); ?></div>
            <div class="meta-value"><?php echo esc_html( pvc_get_post_views( $post_id ) ); ?></div>
          </div>
        <?php endif; ?>

      </div><!-- /.article-meta-bar -->
    </div><!-- /.article-hero-inner -->

    <!-- Cover image -->
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


<!-- ══ 2. ARTICLE LAYOUT: SIDEBAR + BODY ═══════════════════════════════════ -->
<div class="container article-layout">

  <!-- ── STICKY SIDEBAR (LEFT) ── -->
  <aside class="article-sidebar" role="complementary"
         aria-label="<?php esc_attr_e( 'Article sidebar', 'vitalstack' ); ?>">

    <!-- Table of Contents (auto-built by JS) -->
    <div class="toc-card widget">
      <div class="toc-header">
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

    <!-- Share card -->
    <div class="share-card widget">
      <div class="share-card-title"><?php esc_html_e( 'Share article', 'vitalstack' ); ?></div>
      <div class="share-btns">
        <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>"
           class="share-btn" target="_blank" rel="noopener noreferrer">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="13" height="13" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622 5.912-5.622Zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          <?php esc_html_e( 'Share on X', 'vitalstack' ); ?>
        </a>
        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo rawurlencode( get_permalink() ); ?>"
           class="share-btn" target="_blank" rel="noopener noreferrer">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="13" height="13" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
          <?php esc_html_e( 'Share on LinkedIn', 'vitalstack' ); ?>
        </a>
        <button class="share-btn" data-action="copy">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="13" height="13" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
          <?php esc_html_e( 'Copy Link', 'vitalstack' ); ?>
        </button>
      </div>
    </div>

    <!-- Categories -->
    <div class="sidebar-card widget">
      <div class="sidebar-card-header">
        <span aria-hidden="true">📂</span>
        <span><?php esc_html_e( 'Categories', 'vitalstack' ); ?></span>
      </div>
      <div class="sidebar-card-body">
        <?php
        $news_terms = get_terms( array( 'taxonomy' => 'news_category', 'hide_empty' => true ) );
        if ( $news_terms && ! is_wp_error( $news_terms ) ) : ?>
          <ul class="sidebar-cat-list">
            <?php foreach ( $news_terms as $nt ) : ?>
              <li>
                <a href="<?php echo esc_url( get_term_link( $nt ) ); ?>">
                  <?php echo esc_html( $nt->name ); ?>
                  <span class="sidebar-cat-count"><?php echo (int) $nt->count; ?></span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>

    <!-- Sidebar newsletter -->
    <div class="sidebar-newsletter widget">
      <h3><?php esc_html_e( 'Stay Informed', 'vitalstack' ); ?></h3>
      <p><?php esc_html_e( 'AI and health insights — weekly, no spam.', 'vitalstack' ); ?></p>
      <?php if ( shortcode_exists( 'mailchimp' ) ) : ?>
        <?php echo do_shortcode( '[mailchimp]' ); ?>
      <?php else : ?>
        <form class="sidebar-nl-form" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
          <input type="hidden" name="action" value="vitalstack_newsletter_signup">
          <?php wp_nonce_field( 'vitalstack_newsletter', 'vs_nonce' ); ?>
          <input class="nl-input" type="email" name="email"
                 placeholder="<?php esc_attr_e( 'your@email.com', 'vitalstack' ); ?>" required>
          <button type="submit" class="nl-btn"><?php esc_html_e( 'Subscribe Free →', 'vitalstack' ); ?></button>
        </form>
      <?php endif; ?>
    </div>

  </aside><!-- /.article-sidebar -->


  <!-- ── ARTICLE BODY (RIGHT) ── -->
  <article id="post-<?php echo esc_attr( $post_id ); ?>"
           class="article-body <?php post_class( '' ); ?>"
           itemscope itemtype="https://schema.org/Article">

    <meta itemprop="headline"      content="<?php echo esc_attr( get_the_title() ); ?>">
    <meta itemprop="datePublished" content="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
    <meta itemprop="dateModified"  content="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>">
    <meta itemprop="author"        content="<?php echo esc_attr( get_the_author() ); ?>">

    <!-- Post content -->
    <div class="post-body-content" itemprop="articleBody">
      <?php the_content(); ?>
    </div>

    <!-- Author bio -->
    <?php if ( $author_bio ) : ?>
    <div class="author-bio-block">
      <div class="bio-avatar-wrap">
        <?php
        $av = get_avatar( $author_id, 64, '', '', array( 'class' => 'bio-avatar-img' ) );
        echo $av ? $av : '<span class="bio-initials">' . esc_html( $initials ) . '</span>';
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

    <!-- Inline share row -->
    <div class="share-row-inline" aria-label="<?php esc_attr_e( 'Share this article', 'vitalstack' ); ?>">
      <span class="share-label-inline"><?php esc_html_e( 'Share:', 'vitalstack' ); ?></span>

      <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>"
         class="share-btn-inline" target="_blank" rel="noopener noreferrer">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="13" height="13" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622 5.912-5.622Zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        <?php esc_html_e( 'X (Twitter)', 'vitalstack' ); ?>
      </a>

      <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo rawurlencode( get_permalink() ); ?>"
         class="share-btn-inline" target="_blank" rel="noopener noreferrer">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="13" height="13" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452z"/></svg>
        <?php esc_html_e( 'LinkedIn', 'vitalstack' ); ?>
      </a>

      <button class="share-btn-inline" data-action="copy">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="13" height="13" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
        <?php esc_html_e( 'Copy Link', 'vitalstack' ); ?>
      </button>
    </div>

    <!-- Subscribe CTA at end of article -->
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
    </div>

    <!-- Comments -->
    <?php if ( comments_open() || get_comments_number() ) : ?>
    <section class="vs-comments-section" id="comments"
             aria-label="<?php esc_attr_e( 'Comments', 'vitalstack' ); ?>">
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

      <?php if ( have_comments() ) : ?>
        <ol class="vs-comment-list" id="vs-comment-list">
          <?php wp_list_comments( array(
            'style'       => 'ol',
            'short_ping'  => true,
            'avatar_size' => 44,
            'callback'    => 'vitalstack_comment_callback',
          ) ); ?>
        </ol>
        <?php the_comments_navigation( array(
          'prev_text' => '← ' . __( 'Older comments', 'vitalstack' ),
          'next_text' => __( 'Newer comments', 'vitalstack' ) . ' →',
        ) ); ?>
      <?php endif; ?>

      <?php if ( ! comments_open() && get_comments_number() > 0 ) : ?>
        <p class="vs-comments-closed"><?php esc_html_e( 'Comments are closed.', 'vitalstack' ); ?></p>
      <?php endif; ?>

      <?php if ( comments_open() ) : ?>
      <div class="vs-comment-form-wrap" id="respond">
        <?php
        $commenter  = wp_get_current_commenter();
        $req_fields = get_option( 'require_name_email' );
        $req_star   = $req_fields ? ' <span class="vs-required" aria-hidden="true">*</span>' : '';

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
          'comment_field'        => '<div class="vs-form-row vs-form-full"><label for="comment" class="vs-form-label">' . __( 'Comment', 'vitalstack' ) . $req_star . '</label><textarea id="comment" name="comment" class="vs-form-textarea" rows="6" required></textarea></div>',
          'fields'               => array(
            'author' => '<div class="vs-form-row"><label for="author" class="vs-form-label">' . __( 'Name', 'vitalstack' ) . ( $req_fields ? $req_star : '' ) . '</label><input id="author" name="author" type="text" class="vs-form-input" value="' . esc_attr( $commenter['comment_author'] ) . '"' . ( $req_fields ? ' required' : '' ) . '></div>',
            'email'  => '<div class="vs-form-row"><label for="email" class="vs-form-label">' . __( 'Email', 'vitalstack' ) . ( $req_fields ? $req_star : '' ) . ' <span class="vs-field-note">' . __( 'Not published', 'vitalstack' ) . '</span></label><input id="email" name="email" type="email" class="vs-form-input" value="' . esc_attr( $commenter['comment_author_email'] ) . '"' . ( $req_fields ? ' required' : '' ) . '></div>',
            'url'    => '<div class="vs-form-row"><label for="url" class="vs-form-label">' . __( 'Website', 'vitalstack' ) . ' <span class="vs-field-note">' . __( 'Optional', 'vitalstack' ) . '</span></label><input id="url" name="url" type="url" class="vs-form-input" value="' . esc_attr( $commenter['comment_author_url'] ) . '"></div>',
            'cookies'=> '<div class="vs-form-cookies"><label class="vs-cookie-label"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . ( isset( $_COOKIE[ 'comment_author_' . COOKIEHASH ] ) ? ' checked' : '' ) . '> ' . __( 'Save my name and email for next time.', 'vitalstack' ) . '</label></div>',
          ),
          'comment_notes_before' => '',
          'comment_notes_after'  => '',
          'class_form'           => 'vs-comment-form',
          'class_submit'         => 'btn btn-primary',
          'logged_in_as'         => '',
        ) );
        ?>
      </div>
      <?php endif; ?>

    </section>
    <?php endif; ?>

  </article><!-- /.article-body -->

</div><!-- /.article-layout -->


<!-- ══ 3. RELATED POSTS ═════════════════════════════════════════════════════ -->
<?php
$related_args = array(
  'post_type'           => 'news',
  'posts_per_page'      => 3,
  'post__not_in'        => array( $post_id ),
  'ignore_sticky_posts' => 1,
  'orderby'             => 'rand',
);
if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
  $related_args['tax_query'] = array( array(
    'taxonomy' => 'news_category',
    'field'    => 'term_id',
    'terms'    => $cats[0]->term_id,
  ) );
}
$related_query = new WP_Query( $related_args );
if ( $related_query->have_posts() ) : ?>
  <section class="related-posts-section"
           aria-label="<?php esc_attr_e( 'Related articles', 'vitalstack' ); ?>">
    <div class="container">
      <h2 class="related-title"><?php esc_html_e( 'Keep Reading', 'vitalstack' ); ?></h2>
      <div class="related-grid">
        <?php while ( $related_query->have_posts() ) : $related_query->the_post();
          $r_id       = get_the_ID();
          $r_terms    = get_the_terms( $r_id, 'news_category' );
          $r_cat_name = ( $r_terms && ! is_wp_error( $r_terms ) ) ? esc_html( $r_terms[0]->name ) : 'News';
          $r_class    = vitalstack_get_cat_class( $r_id );
          $r_time     = vitalstack_read_time( $r_id );
        ?>
          <a href="<?php the_permalink(); ?>" class="related-card">
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail( 'vitalstack-thumb', array(
                'class' => 'related-thumb-img',
                'alt'   => esc_attr( get_the_title() ),
              ) ); ?>
            <?php else : ?>
              <div class="related-thumb" style="background:linear-gradient(135deg,#1e3a5f,#0f172a);" aria-hidden="true">
                <?php echo $r_class === 'health' ? '🌿' : '🤖'; ?>
              </div>
            <?php endif; ?>
            <div class="related-body">
              <span class="related-tag tag tag-news"><?php echo $r_cat_name; ?></span>
              <div class="related-title-sm"><?php the_title(); ?></div>
              <div class="related-meta">
                <?php echo esc_html( $r_time ); ?> · <?php the_author(); ?>
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
/* ─── Auto-build Table of Contents from H2/H3 in article body ─── */
(function () {
  var tocList    = document.getElementById( 'toc-list' );
  var bodyContent = document.querySelector( '.post-body-content' );
  if ( ! tocList || ! bodyContent ) return;

  var headings = bodyContent.querySelectorAll( 'h2, h3' );
  if ( ! headings.length ) { tocList.innerHTML = ''; return; }

  var html = '', idx = 0;
  headings.forEach( function ( h ) {
    if ( ! h.id ) h.id = 'vs-h-' + ( idx++ );
    var isSub = h.tagName === 'H3';
    html += '<li class="toc-item' + ( isSub ? ' sub' : '' ) + '">' +
            '<a href="#' + h.id + '">' + h.textContent.trim() + '</a></li>';
  } );
  tocList.innerHTML = html;

  /* Highlight active heading on scroll */
  var tocLinks = tocList.querySelectorAll( 'a' );
  var observer = new IntersectionObserver( function ( entries ) {
    entries.forEach( function ( entry ) {
      if ( entry.isIntersecting ) {
        tocLinks.forEach( function ( l ) { l.closest( '.toc-item' ).classList.remove( 'active' ); } );
        var active = tocList.querySelector( 'a[href="#' + entry.target.id + '"]' );
        if ( active ) active.closest( '.toc-item' ).classList.add( 'active' );
      }
    } );
  }, { rootMargin: '-10% 0px -80% 0px' } );
  headings.forEach( function ( h ) { observer.observe( h ); } );

  /* Copy link button */
  document.querySelectorAll( '[data-action="copy"]' ).forEach( function ( btn ) {
    btn.addEventListener( 'click', function () {
      navigator.clipboard.writeText( window.location.href ).then( function () {
        var orig = btn.innerHTML;
        btn.textContent = '✓ Copied!';
        setTimeout( function () { btn.innerHTML = orig; }, 2000 );
      } );
    } );
  } );
} )();
</script>
