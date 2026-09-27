  <!DOCTYPE html>
  <html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?>>
  <?php wp_body_open(); ?>

  <!-- Reading Progress Bar (single posts only) -->
  <?php if ( is_singular( 'post' ) ) : ?>
    <div id="reading-progress" role="progressbar" aria-label="<?php esc_attr_e( 'Reading progress', 'vitalstack' ); ?>"></div>
  <?php endif; ?>

  <!-- ─── SITE HEADER ─────────────────────────────────────────────────────── -->
  <header class="site-header" role="banner">
    <div class="container nav-inner">

      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo" rel="home" aria-label="<?php bloginfo( 'name' ); ?> – <?php esc_attr_e( 'Home', 'vitalstack' ); ?>">
        <span class="logo-normal"><?php vitalstack_logo( 'header' ); ?></span>
        <span class="logo-sticky"><?php vitalstack_logo( 'sticky' ); ?></span>
      </a>

      <!-- Primary Navigation Menu -->
      <nav id="primary-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'vitalstack' ); ?>">
        <?php
        wp_nav_menu( array(
          'theme_location' => 'primary',
          'menu_id'        => 'primary-menu',
          'container'      => false,
          'menu_class'     => 'nav-links',
          'fallback_cb'    => 'vitalstack_fallback_nav',
          'walker'         => class_exists( 'Vitalstack_Nav_Walker' ) ? new Vitalstack_Nav_Walker() : null,
        ) );
        ?>
      </nav>

      <!-- Right-side controls -->
      <div class="nav-right">

        <!-- Search toggle (desktop) -->
        <button class="nav-search" id="nav-search-btn" aria-label="<?php esc_attr_e( 'Search articles', 'vitalstack' ); ?>" aria-expanded="false">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
          </svg>
          <span><?php esc_html_e( 'Search articles…', 'vitalstack' ); ?></span>
        </button>

        <!-- Subscribe CTA Button -->
        <a href="https://vitalstack.co.in/contact-us/"
           class="btn btn-primary"
           style="padding:10px 20px;font-size:14px;">
          <?php echo esc_html( get_theme_mod( 'vitalstack_header_cta_label', __( 'Contact Us', 'vitalstack' ) ) ); ?>
        </a>

        <!-- Mobile hamburger — now opens the drawer -->
        <button class="nav-toggle" id="mobile-menu-btn" aria-label="<?php esc_attr_e( 'Open navigation menu', 'vitalstack' ); ?>" aria-expanded="false" aria-controls="mobile-drawer">
          <span></span>
          <span></span>
          <span></span>
        </button>

      </div><!-- /.nav-right -->
    </div><!-- /.nav-inner -->

    <!-- Search Overlay (hidden by default, toggled via JS) -->
    <div id="search-overlay" class="search-overlay" role="search" hidden>
      <div class="container">
        <?php get_search_form(); ?>
      </div>
    </div>
  </header>
  <!-- ─── END SITE HEADER ──────────────────────────────────────────────────── -->


  <!-- ─── MOBILE DRAWER ───────────────────────────────────────────────────── -->
  <div id="mobile-drawer" class="mobile-drawer" aria-hidden="true" role="dialog" aria-label="<?php esc_attr_e( 'Navigation menu', 'vitalstack' ); ?>">

    <!-- Backdrop -->
    <div class="mobile-drawer-overlay" id="mobile-drawer-overlay" aria-hidden="true"></div>

    <!-- Slide-in panel -->
    <div class="mobile-drawer-panel">

      <!-- Panel top bar: logo + close -->
      <div class="mobile-drawer-head">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mobile-drawer-logo" aria-label="<?php bloginfo( 'name' ); ?>">
          <?php vitalstack_logo( 'header' ); ?>
        </a>
        <button class="mobile-drawer-close" id="mobile-drawer-close" aria-label="<?php esc_attr_e( 'Close navigation menu', 'vitalstack' ); ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>

      <!-- Search bar -->
      <div class="mobile-drawer-search">
        <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
          <input type="search" name="s" class="mobile-search-input"
                 placeholder="<?php esc_attr_e( 'Search…', 'vitalstack' ); ?>"
                 value="<?php echo esc_attr( get_search_query() ); ?>"
                 aria-label="<?php esc_attr_e( 'Search articles', 'vitalstack' ); ?>">
          <button type="submit" class="mobile-search-btn" aria-label="<?php esc_attr_e( 'Submit search', 'vitalstack' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
              <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
            </svg>
          </button>
        </form>
      </div>

      <!-- Quick-link feature cards -->
      <div class="mobile-quick-links">

        <a href="<?php echo esc_url( home_url( '/?orderby=date' ) ); ?>" class="mql-item">
          <span class="mql-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
              <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
          </span>
          <span><?php esc_html_e( 'Featured Articles', 'vitalstack' ); ?></span>
        </a>

        <a href="<?php echo esc_url( get_category_link( get_cat_ID( 'Artificial Intelligence' ) ) ); ?>" class="mql-item">
          <span class="mql-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
              <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
          </span>
          <span><?php esc_html_e( 'AI Insights', 'vitalstack' ); ?></span>
        </a>

        <a href="#" class="mql-item">
          <span class="mql-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
          </span>
          <span><?php esc_html_e( 'Health Article', 'vitalstack' ); ?></span>
        </a>

        <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'tutorials' ) ) ); ?>" class="mql-item">
          <span class="mql-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
              <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
          </span>
          <span><?php esc_html_e( 'Tutorials', 'vitalstack' ); ?></span>
        </a>

      </div><!-- /.mobile-quick-links -->

      <!-- Categories section -->
      <div class="mobile-drawer-section">
        <span class="mobile-drawer-section-label"><?php esc_html_e( 'CATEGORIES', 'vitalstack' ); ?></span>
        <div class="mobile-cat-grid">
          <?php
          $cats = get_categories( array(
            'orderby'    => 'count',
            'order'      => 'DESC',
            'number'     => 6,
            'hide_empty' => true,
          ) );
          if ( $cats ) :
            foreach ( $cats as $cat ) : ?>
              <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="mobile-cat-item">
                <?php echo esc_html( $cat->name ); ?>
              </a>
            <?php endforeach;
          else : ?>
            <a href="<?php echo esc_url( get_category_link( get_cat_ID( 'Artificial Intelligence' ) ) ); ?>" class="mobile-cat-item"><?php esc_html_e( 'Artificial Intelligence', 'vitalstack' ); ?></a>
            <a href="<?php echo esc_url( get_category_link( get_cat_ID( 'Health' ) ) ); ?>" class="mobile-cat-item"><?php esc_html_e( 'Health', 'vitalstack' ); ?></a>
            <a href="<?php echo esc_url( get_category_link( get_cat_ID( 'Technology' ) ) ); ?>" class="mobile-cat-item"><?php esc_html_e( 'Technology', 'vitalstack' ); ?></a>
            <a href="<?php echo esc_url( get_category_link( get_cat_ID( 'Wellness' ) ) ); ?>" class="mobile-cat-item"><?php esc_html_e( 'Wellness', 'vitalstack' ); ?></a>
          <?php endif; ?>
        </div>
      </div>

      <hr class="mobile-drawer-divider">

      <!-- Primary nav links (flat, depth 1) -->
      <nav class="mobile-drawer-nav" aria-label="<?php esc_attr_e( 'Mobile navigation', 'vitalstack' ); ?>">
        <?php
        wp_nav_menu( array(
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'mobile-nav-list',
          'depth'          => 1,
          'fallback_cb'    => 'vitalstack_mobile_fallback_nav',
        ) );
        ?>
      </nav>

      <!-- Subscribe CTA inside drawer -->
      <div class="mobile-drawer-cta">
        <a href="https://vitalstack.co.in/contact-us/" class="btn btn-primary" style="width:100%;text-align:center;padding:12px 20px;">
          <?php echo esc_html( get_theme_mod( 'vitalstack_header_cta_label', __( 'Contact Us', 'vitalstack' ) ) ); ?>
        </a>
      </div>

    </div><!-- /.mobile-drawer-panel -->
  </div><!-- /#mobile-drawer -->
  <!-- ─── END MOBILE DRAWER ─────────────────────────────────────────────────── -->


  <?php
  /**
   * Fallback nav: shows page links when no menu is assigned.
   */
  function vitalstack_fallback_nav() {
      echo '<ul class="nav-links" id="primary-menu">';
      echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'vitalstack' ) . '</a></li>';
      echo '<li><a href="' . esc_url( get_category_link( get_cat_ID( 'Artificial Intelligence' ) ) ) . '">' . esc_html__( 'Artificial Intelligence', 'vitalstack' ) . '</a></li>';
      echo '<li><a href="' . esc_url( get_category_link( get_cat_ID( 'Health' ) ) ) . '">' . esc_html__( 'Health', 'vitalstack' ) . '</a></li>';
      echo '<li><a href="' . esc_url( get_permalink( get_page_by_path( 'about' ) ) ) . '">' . esc_html__( 'About', 'vitalstack' ) . '</a></li>';
      echo '<li><a href="' . esc_url( get_permalink( get_page_by_path( 'contact' ) ) ) . '">' . esc_html__( 'Contact', 'vitalstack' ) . '</a></li>';
      echo '</ul>';
  }

  function vitalstack_mobile_fallback_nav() {
      echo '<ul class="mobile-nav-list">';
      echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'vitalstack' ) . '</a></li>';
      echo '<li><a href="' . esc_url( get_category_link( get_cat_ID( 'Artificial Intelligence' ) ) ) . '">' . esc_html__( 'Artificial Intelligence', 'vitalstack' ) . '</a></li>';
      echo '<li><a href="' . esc_url( get_category_link( get_cat_ID( 'Health' ) ) ) . '">' . esc_html__( 'Health', 'vitalstack' ) . '</a></li>';
      echo '<li><a href="' . esc_url( get_permalink( get_page_by_path( 'about' ) ) ) . '">' . esc_html__( 'About', 'vitalstack' ) . '</a></li>';
      echo '<li><a href="' . esc_url( get_permalink( get_page_by_path( 'contact' ) ) ) . '">' . esc_html__( 'Contact', 'vitalstack' ) . '</a></li>';
      echo '</ul>';
  }
  ?>

  <style>
  /* ─── Dual logo: normal vs sticky ────────────────────────────────────────── */
  .nav-logo .logo-normal { display: block; }
  .nav-logo .logo-sticky { display: none; }
  .site-header.is-sticky .nav-logo .logo-normal { display: none; }
  .site-header.is-sticky .nav-logo .logo-sticky { display: block; }

  /* ─── Search overlay ─────────────────────────────────────────────────────── */
  .search-overlay {
    background: var(--vs-white);
    border-top: 1px solid var(--vs-border);
    padding: 16px 0;
    animation: slideDown .2s ease;
  }
  @keyframes slideDown {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .search-overlay .search-form {
    display: flex;
    gap: 8px;
    max-width: 560px;
    margin: 0 auto;
  }
  .search-overlay .search-field {
    flex: 1;
    padding: 12px 16px;
    border: 2px solid var(--vs-green);
    border-radius: 8px;
    font-size: 15px;
    font-family: 'DM Sans', sans-serif;
    color: var(--vs-text);
    background: var(--vs-off-white);
    outline: none;
  }
  .search-overlay .search-submit {
    padding: 12px 20px;
    background: var(--vs-green);
    color: var(--vs-white);
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s;
  }
  .search-overlay .search-submit:hover { background: var(--vs-green-light); }
  </style>

  <script>
  // Search overlay toggle
  (function() {
    var btn     = document.getElementById('nav-search-btn');
    var overlay = document.getElementById('search-overlay');
    if (btn && overlay) {
      btn.addEventListener('click', function() {
        var hidden = overlay.hasAttribute('hidden');
        if (hidden) {
          overlay.removeAttribute('hidden');
          btn.setAttribute('aria-expanded', 'true');
          var field = overlay.querySelector('.search-field');
          if (field) field.focus();
        } else {
          overlay.setAttribute('hidden', '');
          btn.setAttribute('aria-expanded', 'false');
        }
      });
    }
  })();
  </script>
