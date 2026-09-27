<!-- ─── SITE FOOTER ─────────────────────────────────────────────────────── -->
<footer class="site-footer" role="contentinfo">
  <div class="container">

    <div class="footer-grid">

      <!-- Col 1 — Brand (Footer Area 1 widget or fallback) -->
      <div class="footer-brand">
        <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
          <?php dynamic_sidebar( 'footer-1' ); ?>
        <?php else : ?>
          <!-- Default brand block — upload logo via Customizer → Footer Settings → Footer Logo -->
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
            <?php vitalstack_logo( 'footer' ); ?>
          </a>
          <p>
            <?php echo wp_kses_post( get_theme_mod(
              'vitalstack_footer_tagline',
              'Bringing you the sharpest insights at the intersection of Artificial Intelligence and Human Health. Knowledge that keeps you ahead.'
            ) ); ?>
          </p>
        <?php endif; ?>
      </div>

      <!-- Col 2 — Categories (Footer Area 2 widget or fallback nav) -->
      <div class="footer-col">
        <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
          <?php dynamic_sidebar( 'footer-2' ); ?>
        <?php else : ?>
          <h5><?php esc_html_e( 'Categories', 'vitalstack' ); ?></h5>
          <?php
          wp_nav_menu( array(
            'theme_location' => 'footer-cats',
            'container'      => false,
            'menu_class'     => '',
            'fallback_cb'    => 'vitalstack_footer_cats_fallback',
            'depth'          => 1,
          ) );
          ?>
        <?php endif; ?>
      </div>

      <!-- Col 3 — Company (Footer Area 3 widget or fallback nav) -->
      <div class="footer-col">
        <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
          <?php dynamic_sidebar( 'footer-3' ); ?>
        <?php else : ?>
          <h5><?php esc_html_e( 'Company', 'vitalstack' ); ?></h5>
          <?php
          wp_nav_menu( array(
            'theme_location' => 'footer-company',
            'container'      => false,
            'menu_class'     => '',
            'fallback_cb'    => 'vitalstack_footer_company_fallback',
            'depth'          => 1,
          ) );
          ?>
        <?php endif; ?>
      </div>

      <!-- Col 4 — Legal (Footer Area 4 widget or fallback nav) -->
      <div class="footer-col">
        <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
          <?php dynamic_sidebar( 'footer-4' ); ?>
        <?php else : ?>
          <h5><?php esc_html_e( 'Legal', 'vitalstack' ); ?></h5>
          <?php
          wp_nav_menu( array(
            'theme_location' => 'footer-legal',
            'container'      => false,
            'menu_class'     => '',
            'fallback_cb'    => 'vitalstack_footer_legal_fallback',
            'depth'          => 1,
          ) );
          ?>
        <?php endif; ?>
      </div>

    </div><!-- /.footer-grid -->

    <!-- Footer Bottom Bar -->
    <div class="footer-bottom">

      <?php if ( is_active_sidebar( 'footer-bottom' ) ) : ?>
        <?php dynamic_sidebar( 'footer-bottom' ); ?>
      <?php else : ?>

        <!-- Copyright text -->
        <span>
          <?php
          echo wp_kses_post( get_theme_mod(
            'vitalstack_copyright',
            '&copy; ' . gmdate( 'Y' ) . ' VitalStack. All rights reserved.'
          ) );
          ?>
        </span>

        <!-- Social icons -->
        <div class="footer-socials" aria-label="<?php esc_attr_e( 'Social media links', 'vitalstack' ); ?>">

          <?php
          $social_links = array(
            'instagram'  => array(
              'url'   => get_theme_mod( 'vitalstack_social_instagram', '#' ),
              'label' => __( 'Instagram', 'vitalstack' ),
              'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>',
            ),
            'facebook'   => array(
              'url'   => get_theme_mod( 'vitalstack_social_facebook', '#' ),
              'label' => __( 'Facebook', 'vitalstack' ),
              'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.791-4.697 4.533-4.697 1.312 0 2.686.236 2.686.236v2.97h-1.514c-1.491 0-1.956.93-1.956 1.886v2.268h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>',
            ),
            'email'      => array(
              'url'   => get_theme_mod( 'vitalstack_social_email', 'mailto:hello@vitalstack.io' ),
              'label' => __( 'Email', 'vitalstack' ),
              'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
            ),
            'youtube'    => array(
              'url'   => get_theme_mod( 'vitalstack_social_youtube', '#' ),
              'label' => __( 'YouTube', 'vitalstack' ),
              'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
            ),
            'linkedin'   => array(
              'url'   => get_theme_mod( 'vitalstack_social_linkedin', '#' ),
              'label' => __( 'LinkedIn', 'vitalstack' ),
              'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
            ),
          );

          foreach ( $social_links as $network => $data ) :
            if ( $data['url'] && '#' !== $data['url'] ) :
          ?>
            <a href="<?php echo esc_url( $data['url'] ); ?>"
               class="social-btn"
               aria-label="<?php echo esc_attr( $data['label'] ); ?>"
               <?php if ( 'email' !== $network ) : ?>
                 target="_blank" rel="noopener noreferrer"
               <?php endif; ?>>
              <?php echo $data['icon']; ?>
            </a>
          <?php
            else :
          ?>
            <!-- <?php echo esc_html( $network ); ?> link not set — update in Customizer → Social Media Links -->
            <a href="#" class="social-btn" aria-label="<?php echo esc_attr( $data['label'] ); ?>">
              <?php echo $data['icon']; ?>
            </a>
          <?php
            endif;
          endforeach;
          ?>
        </div><!-- /.footer-socials -->

      <?php endif; ?>

    </div><!-- /.footer-bottom -->

  </div><!-- /.container -->
</footer>

<!-- Cookie Consent Banner (Nirav) -->
<!-- FINAL MOBILE-FRIENDLY COOKIE BANNER -->
<div id="mobile-cookie-banner" style="position:fixed; bottom:0; left:0; right:0; background:#0f172a; color:#e2e8f0; z-index:999999; border-top:4px solid #22c55e; display:none; box-shadow:0 -5px 25px rgba(0,0,0,0.5);">
  <div style="max-width:1280px; margin:0 auto; padding:16px 20px; display:flex; flex-direction:column; gap:16px;">
    
    <p style="margin:0; font-size:14.5px; line-height:1.55;">
      We use cookies to enhance your experience, analyze traffic, and serve personalized ads via Google AdSense. 
      By continuing, you agree to our <a href="/cookie-policy/" style="color:#22c55e;">Cookie Policy</a>.
    </p>
    
    <div style="display:flex; flex-wrap:wrap; gap:10px; justify-content:center;">
      <button onclick="mobileReject()" style="padding:11px 20px; background:transparent; color:#cbd5e1; border:2px solid #64748b; border-radius:8px; cursor:pointer; flex:1; max-width:140px;">Reject</button>
      <button onclick="mobileAccept()" style="padding:11px 20px; background:#22c55e; color:#0f172a; border:none; border-radius:8px; font-weight:600; cursor:pointer; flex:1; max-width:180px;">Accept All Cookies</button>
      <a href="/cookie-policy/" style="padding:11px 20px; background:transparent; color:#cbd5e1; border:2px solid #64748b; border-radius:8px; text-decoration:none; flex:1; max-width:140px; text-align:center;">Settings</a>
    </div>
  </div>
</div>

<script>
function mobileAccept() {
  if (typeof gtag !== 'undefined') gtag('consent', 'update', {'ad_storage':'granted','analytics_storage':'granted'});
  localStorage.setItem('mobileCookie', 'true');
  document.getElementById('mobile-cookie-banner').style.display = 'none';
}

function mobileReject() {
  if (typeof gtag !== 'undefined') gtag('consent', 'update', {'ad_storage':'denied','analytics_storage':'denied'});
  localStorage.setItem('mobileCookie', 'true');
  document.getElementById('mobile-cookie-banner').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
  if (!localStorage.getItem('mobileCookie')) {
    document.getElementById('mobile-cookie-banner').style.display = 'block';
  }
});
</script>


<script>
function sendOTPSimple() {
    const email = document.getElementById('otp-email').value.trim();
    if (!email) {
        alert("Please enter your email");
        return;
    }

    fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=send_otp&email=' + encodeURIComponent(email)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('email-step').style.display = 'none';
            document.getElementById('otp-step').style.display = 'block';
        } else {
            alert(data.data?.message || "Failed to send OTP");
        }
    })
    .catch(() => alert("Connection error. Please try again."));
}

function verifyOTPSimple() {
    const email = document.getElementById('otp-email').value.trim();
    const otp = document.getElementById('otp-code').value.trim();

    fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=verify_otp_subscribe&email=' + encodeURIComponent(email) + '&otp=' + otp
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('otp-step').style.display = 'none';
            document.getElementById('success-msg').style.display = 'block';
        } else {
            alert(data.data?.message || "Invalid OTP");
        }
    });
}

function backToEmailSimple() {
    document.getElementById('otp-step').style.display = 'none';
    document.getElementById('email-step').style.display = 'block';
}
</script>


<!-- ─── END SITE FOOTER ──────────────────────────────────────────────────── -->

<?php wp_footer(); ?>
</body>
</html>

<?php
/* ─── Footer fallback menu functions ──────────────────────────────────────── */

function vitalstack_footer_cats_fallback() {
    echo '<ul>';
    $cats = array( 'Artificial Intelligence', 'Health & Wellness', 'Longevity', 'Nutrition', 'Mental Health' );
    foreach ( $cats as $cat ) {
        $term = get_term_by( 'name', $cat, 'category' );
        $url  = $term ? get_category_link( $term->term_id ) : '#';
        echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $cat ) . '</a></li>';
    }
    echo '</ul>';
}

function vitalstack_footer_company_fallback() {
    echo '<ul>';
    $pages = array(
        __( 'About Us', 'vitalstack' )    => 'about',
        __( 'Write for Us', 'vitalstack' ) => 'write-for-us',
        __( 'Advertise', 'vitalstack' )   => 'advertise',
        __( 'Newsletter', 'vitalstack' )  => '#newsletter',
        __( 'Contact', 'vitalstack' )     => 'contact',
    );
    foreach ( $pages as $label => $slug ) {
        $url = ( strpos( $slug, '#' ) === 0 )
            ? $slug
            : get_permalink( get_page_by_path( $slug ) );
        echo '<li><a href="' . esc_url( $url ?: '#' ) . '">' . esc_html( $label ) . '</a></li>';
    }
    echo '</ul>';
}

function vitalstack_footer_legal_fallback() {
    echo '<ul>';
    $pages = array(
        __( 'Privacy Policy', 'vitalstack' )  => 'privacy-policy',
        __( 'Terms of Service', 'vitalstack' ) => 'terms-of-service',
        __( 'Cookie Policy', 'vitalstack' )   => 'cookie-policy',
        __( 'Disclaimer', 'vitalstack' )      => 'disclaimer',
    );
    foreach ( $pages as $label => $slug ) {
        $url = get_permalink( get_page_by_path( $slug ) );
        echo '<li><a href="' . esc_url( $url ?: '#' ) . '">' . esc_html( $label ) . '</a></li>';
    }
    echo '</ul>';
}
?>
