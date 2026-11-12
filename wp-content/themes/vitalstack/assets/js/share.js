/* VitalStack 4.13: invite link sharing, the invited-friend welcome note and
   source links on copied lesson text. */
(function () {
	'use strict';
	var S = window.vitalstackShare || {};

	/* Device time zone for the server's ₹/$ choice when the CDN sends no country (4.13.1). */
	try {
		var tz = Intl.DateTimeFormat().resolvedOptions().timeZone || '';
		if (tz && document.cookie.indexOf('vs_tz=' + encodeURIComponent(tz)) === -1) {
			document.cookie = 'vs_tz=' + encodeURIComponent(tz) + '; path=/; max-age=31536000; samesite=lax' + (location.protocol === 'https:' ? '; secure' : '');
		}
	} catch (e) {}

	/* Account page: copy and share the invite link. */
	var panel = document.querySelector('[data-ref]');
	if (panel) {
		var input = panel.querySelector('[data-ref-link]');
		var copyBtn = panel.querySelector('[data-ref-copy]');
		if (copyBtn && input) {
			copyBtn.addEventListener('click', function () {
				var done = function () { var t = copyBtn.textContent; copyBtn.textContent = S.copied || 'Copied!'; setTimeout(function () { copyBtn.textContent = t; }, 1800); };
				if (navigator.clipboard && navigator.clipboard.writeText) navigator.clipboard.writeText(input.value).then(done, function () { input.select(); document.execCommand('copy'); done(); });
				else { input.select(); document.execCommand('copy'); done(); }
			});
			input.addEventListener('focus', function () { input.select(); });
		}
		var native = panel.querySelector('[data-ref-native]');
		if (native && navigator.share && input) {
			native.hidden = false;
			native.addEventListener('click', function () { navigator.share({ title: S.site || document.title, text: native.getAttribute('data-text') || '', url: input.value }).catch(function () {}); });
		}
		if (location.hash === '#invite') panel.scrollIntoView({ block: 'start' });
		panel.id = panel.id || 'invite';
	}

	/* /pricing/: fill in and apply the friend's invite code. */
	var apply = document.querySelector('[data-ref-apply]');
	if (apply) {
		var code = apply.getAttribute('data-ref-apply');
		var box = document.querySelector('[data-coupon]');
		var go = document.querySelector('[data-coupon-apply]');
		if (box && go && code) {
			var details = box.closest('details');
			if (details) details.open = true;
			box.value = code;
			setTimeout(function () { go.click(); }, 50);
		}
	}

	/* Welcome note for someone who opened a friend's invite link (signed out). */
	if (!S.loggedIn && S.welcome && /(?:^|;\s*)vs_ref_hi=1/.test(document.cookie) && !/[?&]tab=register/.test(location.search)) {
		var bar = document.createElement('div');
		bar.className = 'ref-welcome';
		bar.setAttribute('role', 'status');
		var p = document.createElement('p'); p.textContent = S.welcome;
		var a = document.createElement('a'); a.className = 'btn btn-primary btn-sm'; a.href = S.joinUrl; a.textContent = S.join;
		var x = document.createElement('button'); x.type = 'button'; x.className = 'ref-welcome-x'; x.setAttribute('aria-label', S.close || 'Close'); x.textContent = '×';
		x.addEventListener('click', function () { bar.remove(); document.cookie = 'vs_ref_hi=; Max-Age=0; path=/'; });
		bar.appendChild(p); bar.appendChild(a); bar.appendChild(x);
		document.body.appendChild(bar);
	}

	/* Copied lesson text keeps a link back to where it came from. Code blocks,
	   form fields and short snippets copy exactly as selected. */
	var article = document.body.classList.contains('single') ? document.querySelector('main .prose') : null;
	if (article) {
		document.addEventListener('copy', function (e) {
			var sel = window.getSelection();
			if (!sel || sel.isCollapsed || !e.clipboardData) return;
			var node = sel.anchorNode && (sel.anchorNode.nodeType === 1 ? sel.anchorNode : sel.anchorNode.parentElement);
			if (!node || !article.contains(node) || node.closest('pre, code, input, textarea, [contenteditable], .tool, .vs-protected')) return;
			var text = sel.toString();
			if (text.length < 200) return;
			var url = location.origin + location.pathname;
			e.clipboardData.setData('text/plain', text + '\n\n' + (S.source || 'Source') + ': ' + (S.site ? S.site + ' · ' : '') + url);
			e.preventDefault();
		});
	}

	/* All-courses panel (4.13.1): on phones the count shows as a number pill and
	   each course name stays on one line, shrinking a little if it must. */
	var coursesPanel = document.getElementById('courses-panel');
	if (coursesPanel) {
		coursesPanel.querySelectorAll('.courses-group small').forEach(function (s) {
			var m = /^\s*(\d+)\s+(.+)$/.exec(s.textContent);
			if (!m || s.querySelector('b')) return;
			s.setAttribute('title', s.textContent.trim());
			s.textContent = '';
			var n = document.createElement('b'); n.textContent = m[1];
			var u = document.createElement('i'); u.textContent = ' ' + m[2];
			s.appendChild(n); s.appendChild(u);
		});
		var fitNames = function () {
			var phone = window.matchMedia('(max-width: 719px)').matches;
			coursesPanel.querySelectorAll('.courses-group a > span').forEach(function (el) {
				if (!el.title) el.title = el.textContent.trim();
				el.style.fontSize = '';
				if (el.textContent !== el.title) el.textContent = el.title; // full name back first
				if (!phone || !el.offsetParent) return;
				var size = parseFloat(getComputedStyle(el).fontSize) || 15;
				var shrink = function (min) { while (el.scrollWidth > el.clientWidth + 1 && size > min) { size -= 0.5; el.style.fontSize = size + 'px'; } };
				shrink(12);
				if (el.scrollWidth > el.clientWidth + 1) {
					// Still too long: a shorter form for phones ("and" → "&", no bracketed extra).
					el.textContent = el.title.replace(/\s+and\s+/g, ' & ').replace(/\s*\([^)]*\)\s*$/, '');
					shrink(11);
					if (el.scrollWidth > el.clientWidth + 1 && el.title.indexOf(':') > 0) {
						el.textContent = el.title.split(':')[0]; // "Generative Models: GANs…" → "Generative Models"
					}
				}
			});
		};
		document.querySelectorAll('[data-courses-open]').forEach(function (b) {
			b.addEventListener('click', function () { setTimeout(fitNames, 60); });
		});
		var fitTimer;
		window.addEventListener('resize', function () { clearTimeout(fitTimer); fitTimer = setTimeout(fitNames, 150); });
		if (!coursesPanel.hidden && coursesPanel.offsetParent) fitNames();
	}

	/* Bottom notes sit above the cookie banner while it is open (4.13.2). */
	var cookieNote = document.getElementById('cookie-note');
	var liftNotes = function () {
		var h = cookieNote && !cookieNote.hidden && getComputedStyle(cookieNote).display !== 'none' ? Math.ceil(cookieNote.getBoundingClientRect().height) + 12 : 0; // fixed elements have no offsetParent
		document.documentElement.style.setProperty('--vs-cookie-h', h + 'px');
	};
	if (cookieNote) {
		liftNotes();
		new MutationObserver(liftNotes).observe(cookieNote, { attributes: true, attributeFilter: ['hidden', 'class', 'style'] });
		window.addEventListener('resize', liftNotes);
	}

	/* Pricing offer line (4.13.2): follows the ₹/$ shown in each card. */
	var payRoot = document.querySelector('[data-pay]');
	if (payRoot) {
		var syncOffer = function () {
			payRoot.querySelectorAll('[data-plan-card]').forEach(function (card) {
				var amt = card.querySelector('.plan-amount');
				var cur = amt && /^\s*\$/.test(amt.textContent) ? 'usd' : 'inr';
				card.querySelectorAll('[data-x-inr]').forEach(function (el) {
					var t = el.getAttribute('data-x-' + cur) || '';
					if (el.textContent !== t) el.textContent = t; // only real changes, so the observer settles
					if (el.hidden !== !t) el.hidden = !t;
				});
			});
		};
		syncOffer();
		payRoot.addEventListener('click', function (e) { if (e.target.closest('[data-cur]')) setTimeout(syncOffer, 30); });
		new MutationObserver(syncOffer).observe(payRoot, { subtree: true, characterData: true, childList: true });
	}
})();
