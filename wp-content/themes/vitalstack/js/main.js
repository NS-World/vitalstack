/**
 * VitalStack — Main JavaScript
 * Handles: sticky header, mobile drawer nav, reading progress, FAQ accordion
 */

(function () {
  'use strict';

  // ── Sticky Header ──────────────────────────────────────────────────────────
  const header = document.querySelector('.site-header');
  if (header) {
    const onScroll = () => {
      header.classList.toggle('scrolled', window.scrollY > 80);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  // ── Desktop: submenu toggle arrows (unchanged) ─────────────────────────────
  const navList = document.querySelector('.nav-links');
  if (navList) {
    navList.querySelectorAll('li.menu-item-has-children').forEach(function(li) {
      var btn = document.createElement('button');
      btn.className = 'sub-menu-toggle';
      btn.setAttribute('aria-expanded', 'false');
      btn.setAttribute('aria-label', 'Toggle submenu');
      btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      btn.addEventListener('click', function(e) {
        e.stopPropagation();
        var subMenu = li.querySelector(':scope > .sub-menu');
        var isOpen  = subMenu && subMenu.classList.contains('sub-menu-open');
        navList.querySelectorAll('li.menu-item-has-children').forEach(function(sib) {
          if (sib !== li) {
            var sibSub = sib.querySelector(':scope > .sub-menu');
            var sibBtn = sib.querySelector(':scope > .sub-menu-toggle');
            if (sibSub) sibSub.classList.remove('sub-menu-open');
            if (sibBtn) sibBtn.setAttribute('aria-expanded', 'false');
          }
        });
        if (subMenu) {
          subMenu.classList.toggle('sub-menu-open', !isOpen);
          btn.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
        }
      });
      li.appendChild(btn);
    });
  }

  // ── Mobile Drawer ──────────────────────────────────────────────────────────
  const drawer      = document.getElementById('mobile-drawer');
  const openBtn     = document.getElementById('mobile-menu-btn');
  const closeBtn    = document.getElementById('mobile-drawer-close');
  const overlay     = document.getElementById('mobile-drawer-overlay');

  function openDrawer() {
    if (!drawer) return;
    drawer.classList.add('is-open');
    drawer.setAttribute('aria-hidden', 'false');
    document.body.classList.add('mobile-nav-open');
    if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
    // Focus the close button for accessibility
    if (closeBtn) setTimeout(function() { closeBtn.focus(); }, 350);
  }

  function closeDrawer() {
    if (!drawer) return;
    drawer.classList.remove('is-open');
    drawer.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('mobile-nav-open');
    if (openBtn) {
      openBtn.setAttribute('aria-expanded', 'false');
      openBtn.focus();
    }
  }

  if (openBtn)  openBtn.addEventListener('click', openDrawer);
  if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
  if (overlay)  overlay.addEventListener('click', closeDrawer);

  // Close on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && drawer && drawer.classList.contains('is-open')) {
      closeDrawer();
    }
  });

  // Close drawer when a link inside it is tapped (navigating away)
//   if (drawer) {
//     drawer.querySelectorAll('a').forEach(function(link) {
//       link.addEventListener('click', function() {
//         // Only close if navigating to a different page (not hash-only)
//         if (!link.getAttribute('href').startsWith('#')) {
//           closeDrawer();
//         }
//       });
//     });
//   }

    // Close drawer when a link inside it is tapped (improved for reliability)
    if (drawer) {
      drawer.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function() {
          const href = (link.getAttribute('href') || '').trim();
          // Close drawer for normal page links, but not for # anchors or javascript
          if (href && !href.startsWith('#') && !href.startsWith('javascript:')) {
            setTimeout(() => {
              closeDrawer();
            }, 150);
          }
        });
      });
    }

  // ── Reading Progress Bar ───────────────────────────────────────────────────
  const progressBar = document.getElementById('reading-progress');
  if (progressBar) {
    const updateProgress = () => {
      const scrollTop = window.scrollY;
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      progressBar.style.width = (docHeight > 0 ? Math.round((scrollTop / docHeight) * 100) : 0) + '%';
    };
    window.addEventListener('scroll', updateProgress, { passive: true });
  }

  // ── FAQ Accordion ──────────────────────────────────────────────────────────
  const faqButtons = document.querySelectorAll('.faq-question');
  faqButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
      const answer   = btn.nextElementSibling;
      const expanded = btn.getAttribute('aria-expanded') === 'true';
      faqButtons.forEach((b) => {
        b.setAttribute('aria-expanded', 'false');
        const a = b.nextElementSibling;
        if (a) { a.style.maxHeight = null; a.style.opacity = 0; }
      });
      if (!expanded && answer) {
        btn.setAttribute('aria-expanded', 'true');
        answer.style.maxHeight = answer.scrollHeight + 'px';
        answer.style.opacity   = 1;
      }
    });
  });

  // ── Smooth scroll for anchor links ────────────────────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', (e) => {
      const target = document.querySelector(anchor.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const offset = header ? header.offsetHeight + 16 : 80;
        window.scrollTo({
          top: target.getBoundingClientRect().top + window.scrollY - offset,
          behavior: 'smooth',
        });
      }
    });
  });

  // ── Contact page topic tabs ───────────────────────────────────────────────
  const topicTabs = document.querySelectorAll('.topic-tab');
  topicTabs.forEach((tab) => {
    tab.addEventListener('click', () => {
      topicTabs.forEach((t) => t.classList.remove('active'));
      tab.classList.add('active');
      const hiddenTopic = document.querySelector('input[name="topic"]');
      if (hiddenTopic) hiddenTopic.value = tab.dataset.topic || tab.textContent.trim();
    });
  });

  // ── Share buttons ─────────────────────────────────────────────────────────
  const copyLinkBtn = document.querySelector('.share-btn[data-action="copy"]');
  if (copyLinkBtn) {
    copyLinkBtn.addEventListener('click', () => {
      navigator.clipboard.writeText(window.location.href).then(() => {
        const orig = copyLinkBtn.textContent;
        copyLinkBtn.textContent = '✓ Copied!';
        setTimeout(() => { copyLinkBtn.textContent = orig; }, 2000);
      });
    });
  }

  // ── YouTube Facade — click-to-play ────────────────────────────────────────
  document.querySelectorAll('.tut-yt-facade').forEach(function(facade) {
    function loadVideo() {
      var vid = facade.dataset.vid;
      if (!vid) return;
      var iframe = document.createElement('iframe');
      iframe.src = 'https://www.youtube-nocookie.com/embed/' + vid + '?autoplay=1&rel=0';
      iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
      iframe.allowFullscreen = true;
      iframe.title = facade.getAttribute('aria-label') || 'YouTube video player';
      facade.innerHTML = '';
      facade.appendChild(iframe);
      facade.style.backgroundImage = 'none';
    }
    facade.addEventListener('click', loadVideo);
    facade.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); loadVideo(); }
    });
  });

})();
