<?php
/**
 * Template Name: Contact Page
 *
 * Assign via: Page Editor → Page Attributes → Template → "Contact Page"
 *
 * CONTACT FORM 7 INTEGRATION:
 *   1. Install & activate "Contact Form 7" plugin
 *   2. Go to Contact → Add New → build your form
 *   3. Go to Customizer → Contact Page Settings → paste the Form ID
 *      OR: define('VITALSTACK_CF7_ID', 123); in wp-config.php
 *
 * FALLBACK: If CF7 is not active, the built-in wp_mail() form is used.
 *
 * @package VitalStack
 */

get_header();

/* ─── Detect Contact Form 7 ───────────────────────────────────────── */
$cf7_active  = function_exists( 'wpcf7' ) || class_exists( 'WPCF7' );
$cf7_form_id = 0;

if ( $cf7_active ) {
    if ( defined( 'VITALSTACK_CF7_ID' ) && (int) VITALSTACK_CF7_ID > 0 ) {
        $cf7_form_id = (int) VITALSTACK_CF7_ID;
    } elseif ( get_theme_mod( 'vitalstack_cf7_form_id', 0 ) ) {
        $cf7_form_id = (int) get_theme_mod( 'vitalstack_cf7_form_id', 0 );
    } else {
        // Auto-detect: grab the first published CF7 form
        $cf7_posts = get_posts( [
            'post_type'   => 'wpcf7_contact_form',
            'numberposts' => 1,
            'post_status' => 'publish',
        ] );
        if ( ! empty( $cf7_posts ) ) {
            $cf7_form_id = $cf7_posts[0]->ID;
        }
    }
}

/* ─── Built-in wp_mail() fallback (only when CF7 is NOT active) ─── */
$form_sent  = false;
$form_error = '';
if ( ! $cf7_active
    && isset( $_POST['vs_contact_nonce'] )
    && wp_verify_nonce( $_POST['vs_contact_nonce'], 'vitalstack_contact' ) ) {

    $fname   = sanitize_text_field( $_POST['vs_fname']   ?? '' );
    $lname   = sanitize_text_field( $_POST['vs_lname']   ?? '' );
    $email   = sanitize_email(      $_POST['vs_email']   ?? '' );
    $phone   = sanitize_text_field( $_POST['vs_phone']   ?? '' );
    $subject = sanitize_text_field( $_POST['vs_subject'] ?? '' );
    $topic   = sanitize_text_field( $_POST['vs_topic']   ?? '' );
    $cat     = sanitize_text_field( $_POST['vs_cat']     ?? '' );
    $message = sanitize_textarea_field( $_POST['vs_message'] ?? '' );
    $privacy = isset( $_POST['vs_privacy'] );

    if ( ! $email || ! is_email( $email ) ) {
        $form_error = __( 'Please enter a valid email address.', 'vitalstack' );
    } elseif ( ! $subject || ! $message ) {
        $form_error = __( 'Subject and message are required.', 'vitalstack' );
    } elseif ( ! $privacy ) {
        $form_error = __( 'Please agree to the Privacy Policy to continue.', 'vitalstack' );
    } else {
        $to      = defined( 'VITALSTACK_CONTACT_EMAIL' ) ? VITALSTACK_CONTACT_EMAIL : get_option( 'admin_email' );
        $subj    = '[VitalStack Contact] ' . $subject;
        $body    = "Name: $fname $lname\nEmail: $email\nPhone: $phone\nTopic: $topic\nCategory: $cat\n\n---\n\n$message";
        $headers = [ 'Content-Type: text/plain; charset=UTF-8', "Reply-To: $email" ];
        $form_sent = wp_mail( $to, $subj, $body, $headers );
        if ( ! $form_sent ) {
            $form_error = __( 'Sorry, there was a problem sending your message. Please email us directly.', 'vitalstack' );
        }
    }
}

$topics = [
    'general'      => '💬 ' . __( 'General Inquiry', 'vitalstack' ),
    'write-for-us' => '✍️ ' . __( 'Write for Us',    'vitalstack' ),
    'partnership'  => '📣 ' . __( 'Partnership',      'vitalstack' ),
    'report'       => '🐛 ' . __( 'Report an Issue',  'vitalstack' ),
    'newsletter'   => '📧 ' . __( 'Newsletter',        'vitalstack' ),
];
?>

<main id="main" class="site-main contact-page">

<!-- ══ 1. HERO ══════════════════════════════════════════════════════════ -->
<section class="contact-hero" aria-label="<?php esc_attr_e( 'Contact header', 'vitalstack' ); ?>">
  <div class="ph-grid" aria-hidden="true"></div>
  <div class="container">
    <div class="contact-hero-inner">
      <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'vitalstack' ); ?>">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'vitalstack' ); ?></a>
        <span>/</span><span><?php the_title(); ?></span>
      </nav>
      <h1 class="contact-hero-title"><?php esc_html_e( "We'd Love to Hear From You", 'vitalstack' ); ?></h1>
      <p class="contact-hero-desc"><?php esc_html_e( 'Whether you have a story idea, want to write for us, or just want to say hello - our team reads every message.', 'vitalstack' ); ?></p>
    </div>
  </div>
</section>

<!-- ══ 2. CONTACT LAYOUT ════════════════════════════════════════════════ -->
<div class="container contact-layout">

  <!-- FORM COLUMN -->
  <div class="contact-form-wrap">
    <h2><?php esc_html_e( 'Send Us a Message', 'vitalstack' ); ?></h2>
    <p><?php esc_html_e( "Fill in the form below and we'll get back to you within 24-48 hours on business days.", 'vitalstack' ); ?></p>

    <!-- Topic tabs (visual — works with both CF7 and fallback form) -->
    <div class="topic-tabs" role="group" aria-label="<?php esc_attr_e( 'Message topic', 'vitalstack' ); ?>">
      <?php $first = true; foreach ( $topics as $val => $label ) : ?>
        <button type="button" class="topic-tab <?php echo $first ? 'active' : ''; ?>"
                data-topic="<?php echo esc_attr( $val ); ?>">
          <?php echo esc_html( $label ); ?>
        </button>
      <?php $first = false; endforeach; ?>
    </div>

    <?php if ( $form_sent ) : ?>
      <!-- Success state (fallback form only) -->
      <div class="contact-success" role="alert">
        <div class="success-check">✅</div>
        <h3><?php esc_html_e( 'Message Sent!', 'vitalstack' ); ?></h3>
        <p><?php esc_html_e( "Thanks for reaching out. We'll get back to you within 24–48 hours.", 'vitalstack' ); ?></p>
        <a href="<?php the_permalink(); ?>" class="btn btn-primary" style="margin-top:16px;">
          <?php esc_html_e( 'Send another message', 'vitalstack' ); ?>
        </a>
      </div>

    <?php elseif ( $cf7_active && $cf7_form_id ) : ?>

      <!-- ══ CONTACT FORM 7 ════════════════════════════════════════════
           CF7 handles validation, AJAX submit, success/error messages.
           The .cf7-wrapper CSS below overrides CF7's default styles
           to match VitalStack's design tokens automatically.
      ═════════════════════════════════════════════════════════════════ -->
      <div class="cf7-wrapper">
        <?php echo do_shortcode( '[contact-form-7 id="' . esc_attr( $cf7_form_id ) . '"]' ); ?>
      </div>

    <?php elseif ( $cf7_active && ! $cf7_form_id ) : ?>
      <!-- CF7 active but no form ID set -->
      <div class="form-error-notice" role="alert">
        <strong><?php esc_html_e( 'Contact Form 7 is active but no form is linked.', 'vitalstack' ); ?></strong><br>
        <?php esc_html_e( 'Go to Customizer → Contact Page Settings → CF7 Form ID, or set define(\'VITALSTACK_CF7_ID\', 123) in wp-config.php.', 'vitalstack' ); ?>
      </div>

    <?php else : ?>

      <!-- ══ BUILT-IN FALLBACK FORM (no CF7 installed) ════════════════ -->
      <?php if ( $form_error ) : ?>
        <div class="form-error-notice" role="alert"><?php echo esc_html( $form_error ); ?></div>
      <?php endif; ?>

      <form id="contact-form" method="post" action="<?php the_permalink(); ?>#contact-form"
            novalidate aria-label="<?php esc_attr_e( 'Contact form', 'vitalstack' ); ?>">
        <?php wp_nonce_field( 'vitalstack_contact', 'vs_contact_nonce' ); ?>
        <input type="hidden" name="vs_topic" id="vs_topic_field"
               value="<?php echo esc_attr( $_POST['vs_topic'] ?? 'general' ); ?>">

        <div class="form-grid">
          <div class="form-group">
            <label for="vs_fname"><?php esc_html_e( 'First Name', 'vitalstack' ); ?> <span>*</span></label>
            <input class="form-input" id="vs_fname" name="vs_fname" type="text"
                   placeholder="Arjun" value="<?php echo esc_attr( $_POST['vs_fname'] ?? '' ); ?>"
                   required autocomplete="given-name">
          </div>
          <div class="form-group">
            <label for="vs_lname"><?php esc_html_e( 'Last Name', 'vitalstack' ); ?> <span>*</span></label>
            <input class="form-input" id="vs_lname" name="vs_lname" type="text"
                   placeholder="Kapoor" value="<?php echo esc_attr( $_POST['vs_lname'] ?? '' ); ?>"
                   required autocomplete="family-name">
          </div>
          <div class="form-group">
            <label for="vs_email"><?php esc_html_e( 'Email Address', 'vitalstack' ); ?> <span>*</span></label>
            <input class="form-input" id="vs_email" name="vs_email" type="email"
                   placeholder="arjun@example.com" value="<?php echo esc_attr( $_POST['vs_email'] ?? '' ); ?>"
                   required autocomplete="email">
          </div>
          <div class="form-group">
            <label for="vs_phone">
              <?php esc_html_e( 'Phone', 'vitalstack' ); ?>
              <span class="optional">(<?php esc_html_e( 'optional', 'vitalstack' ); ?>)</span>
            </label>
            <input class="form-input" id="vs_phone" name="vs_phone" type="tel"
                   placeholder="+91 98765 43210" value="<?php echo esc_attr( $_POST['vs_phone'] ?? '' ); ?>"
                   autocomplete="tel">
          </div>
          <div class="form-group full">
            <label for="vs_subject"><?php esc_html_e( 'Subject', 'vitalstack' ); ?> <span>*</span></label>
            <input class="form-input" id="vs_subject" name="vs_subject" type="text"
                   placeholder="<?php esc_attr_e( "What's this about?", 'vitalstack' ); ?>"
                   value="<?php echo esc_attr( $_POST['vs_subject'] ?? '' ); ?>" required>
          </div>
          <div class="form-group full">
            <label for="vs_cat"><?php esc_html_e( 'Which category does this relate to?', 'vitalstack' ); ?></label>
            <select class="form-input form-select" id="vs_cat" name="vs_cat">
              <option value=""><?php esc_html_e( 'Select a category…', 'vitalstack' ); ?></option>
              <?php
              foreach ( [ 'Artificial Intelligence', 'Health & Wellness', 'Both / Not sure', 'General / Other' ] as $opt ) :
              ?>
                <option value="<?php echo esc_attr( $opt ); ?>"
                  <?php selected( $_POST['vs_cat'] ?? '', $opt ); ?>>
                  <?php echo esc_html( $opt ); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group full">
            <label for="vs_message"><?php esc_html_e( 'Your Message', 'vitalstack' ); ?> <span>*</span></label>
            <textarea class="form-input form-textarea" id="vs_message" name="vs_message"
                      placeholder="<?php esc_attr_e( "Tell us what's on your mind…", 'vitalstack' ); ?>"
                      maxlength="1000" required rows="6"><?php echo esc_textarea( $_POST['vs_message'] ?? '' ); ?></textarea>
            <div class="char-count"><span id="char-num">0</span> / 1000 <?php esc_html_e( 'characters', 'vitalstack' ); ?></div>
          </div>
          <div class="form-group full">
            <label class="form-check-label">
              <input type="checkbox" name="vs_newsletter" id="vs_newsletter"
                     <?php checked( true, ! isset( $_POST['vs_contact_nonce'] ) || isset( $_POST['vs_newsletter'] ) ); ?>>
              <?php esc_html_e( 'Subscribe me to the VitalStack weekly digest — AI & health articles, no spam.', 'vitalstack' ); ?>
            </label>
          </div>
          <div class="form-group full">
            <label class="form-check-label">
              <input type="checkbox" name="vs_privacy" id="vs_privacy"
                     <?php checked( isset( $_POST['vs_privacy'] ) ); ?> required>
              <?php printf(
                wp_kses( __( 'I agree to the <a href="%1$s">Privacy Policy</a> and <a href="%2$s">Terms of Service</a>. *', 'vitalstack' ),
                  [ 'a' => [ 'href' => [] ] ] ),
                esc_url( get_permalink( get_page_by_path( 'privacy-policy' ) ) ?: '#' ),
                esc_url( get_permalink( get_page_by_path( 'terms-of-service' ) ) ?: '#' )
              ); ?>
            </label>
          </div>
          <div class="form-group full submit-row">
            <button type="submit" class="btn btn-primary submit-btn">
              <?php esc_html_e( 'Send Message', 'vitalstack' ); ?>
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                   stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
              </svg>
            </button>
            <span class="submit-note"><?php esc_html_e( 'We reply within 24–48 hours', 'vitalstack' ); ?></span>
          </div>
        </div>
      </form>

    <?php endif; ?>
  </div><!-- /.contact-form-wrap -->

  <!-- INFO COLUMN -->
  <div class="contact-info-col">
    <?php
    $info_cards = [
      [ 'icon' => '📧', 'type' => 'green', 'title' => __( 'Email Us', 'vitalstack' ),
        'body' => __( 'For editorial inquiries, partnership proposals, or general questions, drop us an email.', 'vitalstack' ),
        'link_label' => get_theme_mod( 'vitalstack_email_general', 'hello@vitalstack.io' ),
        'link_href'  => 'mailto:' . get_theme_mod( 'vitalstack_email_general', 'hello@vitalstack.io' ) ],
      [ 'icon' => '✍️', 'type' => 'navy', 'title' => __( 'Write for VitalStack', 'vitalstack' ),
        'body' => __( 'Are you an expert in AI or healthcare? We accept guest posts and original research articles.', 'vitalstack' ),
        'link_label' => __( 'View submission guidelines', 'vitalstack' ),
        'link_href'  => get_permalink( get_page_by_path( 'write-for-us' ) ) ?: '#' ],
      [ 'icon' => '📣', 'type' => 'green', 'title' => __( 'Partnerships & Advertising', 'vitalstack' ),
        'body' => __( 'Interested in sponsored content or co-marketing? We work with brands aligned to our mission.', 'vitalstack' ),
        'link_label' => get_theme_mod( 'vitalstack_email_partners', 'partners@vitalstack.io' ),
        'link_href'  => 'mailto:' . get_theme_mod( 'vitalstack_email_partners', 'partners@vitalstack.io' ) ],
    ];
    foreach ( $info_cards as $card ) : ?>
      <div class="info-card">
        <div class="info-card-icon <?php echo esc_attr( $card['type'] ); ?>"><?php echo $card['icon']; ?></div>
        <h3><?php echo esc_html( $card['title'] ); ?></h3>
        <p><?php echo esc_html( $card['body'] ); ?></p>
        <a href="<?php echo esc_url( $card['link_href'] ); ?>" class="info-link">
          <?php echo esc_html( $card['link_label'] ); ?>
        </a>
      </div>
    <?php endforeach; ?>

    <div class="social-panel">
      <h3><?php esc_html_e( 'Follow VitalStack', 'vitalstack' ); ?></h3>
      <p><?php esc_html_e( 'Stay connected and join the conversation.', 'vitalstack' ); ?></p>
      <div class="social-links-col">
        <?php
        $socials = [
          [ 'url' => get_theme_mod( 'vitalstack_social_instagram', '#' ),  'label' => 'Instagram',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>' ],
          [ 'url' => get_theme_mod( 'vitalstack_social_facebook', '#' ),   'label' => 'Facebook',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.791-4.697 4.533-4.697 1.312 0 2.686.236 2.686.236v2.97h-1.514c-1.491 0-1.956.93-1.956 1.886v2.268h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>' ],
          [ 'url' => get_theme_mod( 'vitalstack_social_email', 'mailto:hello@vitalstack.io' ), 'label' => 'Email',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>' ],
          [ 'url' => get_theme_mod( 'vitalstack_social_youtube', '#' ),    'label' => 'YouTube',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>' ],
          [ 'url' => get_theme_mod( 'vitalstack_social_linkedin', '#' ),   'label' => 'LinkedIn',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452z"/></svg>' ],
        ];
        foreach ( $socials as $s ) : ?>
          <a href="<?php echo esc_url( $s['url'] ); ?>" class="social-link-row"
             <?php if ( strpos( $s['url'], '#' ) !== 0 && strpos( $s['url'], 'mailto' ) !== 0 ) echo 'target="_blank" rel="noopener noreferrer"'; ?>>
            <span class="social-link-icon"><?php echo $s['icon']; ?></span>
            <?php echo esc_html( $s['label'] ); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</div><!-- /.contact-layout -->

<!-- Remote banner -->
<div class="remote-team-banner">
  <div class="container remote-inner">
    <span class="remote-icon">🌍</span>
    <div>
      <strong><?php esc_html_e( 'VitalStack — Digital First, Global Reach', 'vitalstack' ); ?></strong>
      <span><?php esc_html_e( 'Remote-first team · Published from across the world', 'vitalstack' ); ?></span>
    </div>
  </div>
</div>

<!-- FAQ -->
<section class="contact-faq-section">
  <div class="container faq-inner">
    <p class="section-eyebrow" style="text-align:center"><?php esc_html_e( 'Frequently Asked Questions', 'vitalstack' ); ?></p>
    <h2 class="section-title faq-heading"><?php esc_html_e( "Got Questions? We've Got Answers.", 'vitalstack' ); ?></h2>
    <?php
    $faqs = [
      [ 'q' => __( 'How long does it take to get a reply?', 'vitalstack' ),
        'a' => __( 'We typically respond within 24–48 business hours. For urgent matters, mention "URGENT" in your subject line.', 'vitalstack' ) ],
      [ 'q' => __( 'Can I pitch an article idea or write for VitalStack?', 'vitalstack' ),
        'a' => __( 'Absolutely! We welcome contributions from domain experts. Select "Write for Us" above and tell us your idea. We review all pitches within 5 business days.', 'vitalstack' ) ],
      [ 'q' => __( 'Do you accept sponsored content or paid placements?', 'vitalstack' ),
        'a' => __( 'We accept a limited number of sponsored articles from brands aligned with our mission. All sponsored content is clearly labelled. Email partners@vitalstack.io for our media kit.', 'vitalstack' ), 'open' => true ],
      [ 'q' => __( 'How do I report a factual error in an article?', 'vitalstack' ),
        'a' => __( 'Select "Report an Issue" above and include a link to the article and the specific claim. We investigate all reports promptly.', 'vitalstack' ) ],
      [ 'q' => __( 'Can I republish or share VitalStack content?', 'vitalstack' ),
        'a' => __( 'Short excerpts (up to 100 words) with a clear attribution link are permitted. For full republication rights, please contact us with details of intended use.', 'vitalstack' ) ],
    ];
    if ( function_exists( 'get_field' ) && get_field( 'faq_items' ) ) {
        $faqs = array_map( fn( $r ) => [ 'q' => $r['faq_question'] ?? '', 'a' => $r['faq_answer'] ?? '' ], get_field( 'faq_items' ) );
    }
    foreach ( $faqs as $faq ) :
      $open = ! empty( $faq['open'] );
    ?>
      <div class="faq-item <?php echo $open ? 'open' : ''; ?>">
        <button class="faq-question" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>">
          <?php echo esc_html( $faq['q'] ); ?>
          <span class="faq-arrow">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="18" height="18">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </span>
        </button>
        <div class="faq-answer" <?php echo $open ? '' : 'hidden'; ?>>
          <p><?php echo esc_html( $faq['a'] ); ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

</main>
<?php get_footer(); ?>

<style id="vs-contact-styles">
.contact-hero{background:linear-gradient(135deg,var(--vs-navy) 0%,var(--vs-navy-mid) 100%);padding:72px 0 56px;position:relative;overflow:hidden}
.ph-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:48px 48px;pointer-events:none}
.contact-hero-inner{position:relative;max-width:640px}
.contact-eyebrow{font-family:'Space Mono',monospace;font-size:11px;letter-spacing:.15em;text-transform:uppercase;color:var(--vs-green-light);margin-bottom:14px}
.contact-hero-title{font-family:'Playfair Display',serif;font-size:clamp(30px,4vw,52px);font-weight:900;color:var(--vs-white);line-height:1.15;margin-bottom:16px}
.contact-hero-desc{font-size:17px;color:rgba(255,255,255,.65);line-height:1.65}
.contact-layout{display:grid;grid-template-columns:1fr 360px;gap:60px;padding:72px 0 80px;align-items:start}
.contact-form-wrap h2{font-family:'Playfair Display',serif;font-size:28px;font-weight:700;margin-bottom:8px}
.contact-form-wrap>p{color:var(--vs-muted);margin-bottom:28px;font-size:15px}
.topic-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:28px}
.topic-tab{padding:8px 16px;border-radius:100px;font-size:13px;font-weight:600;border:2px solid var(--vs-border);background:transparent;color:var(--vs-muted);cursor:pointer;transition:all .2s;font-family:'DM Sans',sans-serif}
.topic-tab:hover,.topic-tab.active{border-color:var(--vs-green);color:var(--vs-green);background:var(--vs-green-pale)}

/* ── CF7 overrides: makes CF7 match VitalStack design ── */
.cf7-wrapper .wpcf7-form{display:flex;flex-direction:column;gap:16px}
.cf7-wrapper .wpcf7-form p{margin:0;display:flex;flex-direction:column;gap:6px}
.cf7-wrapper label{font-size:13px;font-weight:600;color:var(--vs-text)}
.cf7-wrapper input[type="text"],
.cf7-wrapper input[type="email"],
.cf7-wrapper input[type="tel"],
.cf7-wrapper input[type="url"],
.cf7-wrapper input[type="number"],
.cf7-wrapper select,
.cf7-wrapper textarea{
  width:100%;padding:12px 16px;border:1.5px solid var(--vs-border);border-radius:8px;
  font-size:15px;font-family:'DM Sans',sans-serif;color:var(--vs-text);background:var(--vs-white);
  outline:none;transition:border-color .2s,box-shadow .2s;box-sizing:border-box
}
.cf7-wrapper input:focus,
.cf7-wrapper select:focus,
.cf7-wrapper textarea:focus{border-color:var(--vs-green);box-shadow:0 0 0 3px rgba(29,138,78,.12)}
.cf7-wrapper input::placeholder,
.cf7-wrapper textarea::placeholder{color:var(--vs-border)}
.cf7-wrapper textarea{min-height:140px;resize:vertical}
.cf7-wrapper .wpcf7-submit{
  display:inline-flex;align-items:center;gap:8px;
  padding:14px 32px;background:var(--vs-green);color:#fff;border:none;
  border-radius:8px;font-size:16px;font-weight:600;cursor:pointer;
  font-family:'DM Sans',sans-serif;transition:background .2s,transform .2s;
  margin-top:8px;
}
.cf7-wrapper .wpcf7-submit:hover{background:var(--vs-green-light);transform:translateY(-2px)}
.cf7-wrapper .wpcf7-submit:disabled{opacity:.6;cursor:not-allowed;transform:none}
.cf7-wrapper .wpcf7-not-valid-tip{color:#b91c1c;font-size:12px;margin-top:4px}
.cf7-wrapper .wpcf7-response-output{padding:14px 18px;border-radius:8px;font-size:14px;margin-top:12px}
.cf7-wrapper .wpcf7-mail-sent-ok{background:#f0fdf4;border:1px solid #86efac;color:#166534}
.cf7-wrapper .wpcf7-validation-errors,
.cf7-wrapper .wpcf7-mail-sent-ng,
.cf7-wrapper .wpcf7-spam-blocked{background:#fef2f2;border:1px solid #fca5a5;color:#b91c1c}
.cf7-wrapper .wpcf7-acceptance label,.cf7-wrapper .wpcf7-checkbox label{display:flex;align-items:flex-start;gap:10px;font-weight:400;color:var(--vs-muted);cursor:pointer}
.cf7-wrapper .wpcf7-acceptance input[type="checkbox"],
.cf7-wrapper .wpcf7-checkbox input[type="checkbox"]{width:16px;height:16px;margin-top:2px;flex-shrink:0;accent-color:var(--vs-green)}
.cf7-wrapper .wpcf7-list-item{display:block;margin-bottom:6px}
.cf7-wrapper select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;background-size:16px;padding-right:38px}
/* loading spinner */
.cf7-wrapper .wpcf7-spinner{display:inline-block;width:24px;height:24px;vertical-align:middle}

/* ── Fallback form ── */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.form-group{display:flex;flex-direction:column;gap:6px}
.form-group.full{grid-column:1/-1}
.form-group label{font-size:13px;font-weight:600;color:var(--vs-text)}
.form-group label span{color:var(--vs-green);margin-left:2px}
.form-group label .optional{font-weight:400;color:var(--vs-muted);font-size:12px}
.form-input{width:100%;padding:12px 16px;border:1.5px solid var(--vs-border);border-radius:8px;font-size:15px;font-family:'DM Sans',sans-serif;color:var(--vs-text);background:var(--vs-white);outline:none;transition:border-color .2s,box-shadow .2s;box-sizing:border-box}
.form-input:focus{border-color:var(--vs-green);box-shadow:0 0 0 3px rgba(29,138,78,.12)}
.form-input::placeholder{color:var(--vs-border)}
.form-select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;background-size:16px;padding-right:38px;cursor:pointer}
.form-textarea{resize:vertical;min-height:140px}
.char-count{font-size:12px;color:var(--vs-muted);text-align:right;margin-top:4px}
.form-check-label{display:flex;align-items:flex-start;gap:10px;font-size:14px;color:var(--vs-muted);cursor:pointer;line-height:1.5}
.form-check-label input[type="checkbox"]{width:16px;height:16px;margin-top:2px;flex-shrink:0;accent-color:var(--vs-green)}
.form-check-label a{color:var(--vs-green)}
.submit-row{display:flex;align-items:center;gap:16px;flex-wrap:wrap}
.submit-btn{padding:14px 32px;font-size:16px}
.submit-note{font-size:13px;color:var(--vs-muted)}

/* ── Error/Success ── */
.form-error-notice{background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:14px 18px;color:#b91c1c;font-size:14px;margin-bottom:20px}
.contact-success{text-align:center;padding:60px 20px;border:2px dashed var(--vs-green-pale);border-radius:var(--vs-radius)}
.success-check{font-size:48px;margin-bottom:16px}
.contact-success h3{font-family:'Playfair Display',serif;font-size:24px;font-weight:700;margin-bottom:8px}
.contact-success p{color:var(--vs-muted)}

/* ── Info cards ── */
.contact-info-col{display:flex;flex-direction:column;gap:16px}
.info-card{background:var(--vs-white);border:1px solid var(--vs-border);border-radius:var(--vs-radius);padding:24px 28px;opacity:0;transform:translateY(14px);animation:vsInfoIn .5s ease forwards}
.info-card:nth-child(1){animation-delay:.05s}.info-card:nth-child(2){animation-delay:.12s}.info-card:nth-child(3){animation-delay:.19s}
@keyframes vsInfoIn{to{opacity:1;transform:translateY(0)}}
.info-card-icon{font-size:26px;width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:12px}
.info-card-icon.green{background:var(--vs-green-pale)}.info-card-icon.navy{background:var(--vs-navy-light)}
.info-card h3{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;margin-bottom:8px}
.info-card p{font-size:14px;color:var(--vs-muted);line-height:1.65;margin-bottom:12px}
.info-link{font-size:14px;font-weight:600;color:var(--vs-green)}.info-link:hover{color:var(--vs-green-light)}
.social-panel{background:linear-gradient(135deg,var(--vs-navy),var(--vs-navy-mid));border-radius:var(--vs-radius);padding:24px 28px;opacity:0;transform:translateY(14px);animation:vsInfoIn .5s ease .26s forwards}
.social-panel h3{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:#fff;margin-bottom:6px}
.social-panel>p{font-size:14px;color:rgba(255,255,255,.55);margin-bottom:16px}
.social-links-col{display:flex;flex-direction:column;gap:10px}
.social-link-row{display:flex;align-items:center;gap:12px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:10px 14px;font-size:14px;color:rgba(255,255,255,.75);text-decoration:none;transition:background .2s}
.social-link-row:hover{background:rgba(255,255,255,.14);color:#fff}
.social-link-icon{width:28px;height:28px;border-radius:6px;background:rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#fff}

/* ── Remote banner ── */
.remote-team-banner{background:var(--vs-off-white);border-top:1px solid var(--vs-border);border-bottom:1px solid var(--vs-border);padding:20px 0}
.remote-inner{display:flex;align-items:center;gap:16px}
.remote-icon{font-size:28px;flex-shrink:0}
.remote-inner strong{display:block;font-size:15px;font-weight:700;color:var(--vs-text)}
.remote-inner span{font-size:13px;color:var(--vs-muted)}

/* ── FAQ ── */
.contact-faq-section{padding:80px 0}
.faq-heading{text-align:center;margin-bottom:40px}
.faq-item{background:var(--vs-white);border:1px solid var(--vs-border);border-radius:var(--vs-radius);margin-bottom:10px;overflow:hidden}
.faq-question{width:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:20px 24px;font-size:16px;font-weight:600;color:var(--vs-text);background:none;border:none;cursor:pointer;text-align:left;font-family:'DM Sans',sans-serif;transition:color .2s}
.faq-question:hover{color:var(--vs-green)}
.faq-arrow{flex-shrink:0;color:var(--vs-muted);transition:transform .3s}
.faq-item.open .faq-arrow{transform:rotate(180deg)}
.faq-item.open .faq-question{color:var(--vs-green)}
.faq-answer{padding:0 24px 20px}
.faq-answer p{color:var(--vs-muted);font-size:15px;line-height:1.75}

/* ── Responsive ── */
@media(max-width:1024px){.contact-layout{grid-template-columns:1fr}}
@media(max-width:640px){.contact-hero{padding:48px 0 36px}.form-grid{grid-template-columns:1fr}.form-group.full{grid-column:auto}.submit-row{flex-direction:column;align-items:flex-start}.topic-tab{font-size:12px;padding:7px 12px}.contact-layout{padding:48px 0 56px}}
</style>

<script>
/* Topic tabs */
document.querySelectorAll('.topic-tab').forEach(function(btn){
  btn.addEventListener('click',function(){
    document.querySelectorAll('.topic-tab').forEach(function(b){b.classList.remove('active')});
    this.classList.add('active');
    var f=document.getElementById('vs_topic_field');
    if(f) f.value=this.dataset.topic||'';
    /* If CF7 is present, try to update a hidden "topic" field inside the CF7 form */
    var cf7topic=document.querySelector('.cf7-wrapper input[name="vs-topic"],.cf7-wrapper input[name="topic"]');
    if(cf7topic) cf7topic.value=this.dataset.topic||this.textContent.trim();
  });
});
/* Character counter */
var msg=document.getElementById('vs_message'),cn=document.getElementById('char-num');
if(msg&&cn){msg.addEventListener('input',function(){cn.textContent=msg.value.length});cn.textContent=0}
/* FAQ */
document.querySelectorAll('.faq-question').forEach(function(btn){
  btn.addEventListener('click',function(){
    var item=this.closest('.faq-item'),ans=this.nextElementSibling,isOpen=item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(function(fi){
      fi.classList.remove('open');
      fi.querySelector('.faq-question').setAttribute('aria-expanded','false');
      var a=fi.querySelector('.faq-answer');if(a)a.setAttribute('hidden','');
    });
    if(!isOpen){item.classList.add('open');this.setAttribute('aria-expanded','true');if(ans)ans.removeAttribute('hidden')}
  });
});
</script>
