/**
 * VitalStack front-end behaviour. No dependencies.
 */
(function () {
	'use strict';

	var root = document.documentElement;

	/* ── Storage (fails quietly in private mode / blocked storage) ─────────── */
	var store = {
		get: function (key, fallback) {
			try {
				var v = localStorage.getItem(key);
				return v === null ? fallback : JSON.parse(v);
			} catch (e) {
				return fallback;
			}
		},
		set: function (key, value) {
			try {
				localStorage.setItem(key, JSON.stringify(value));
			} catch (e) {}
		}
	};

	/* ── Toast ─────────────────────────────────────────────────────────────── */
	var toastEl;
	function toast(msg) {
		if (!toastEl) {
			toastEl = document.createElement('div');
			toastEl.className = 'toast';
			toastEl.setAttribute('role', 'status');
			document.body.appendChild(toastEl);
		}
		toastEl.textContent = msg;
		toastEl.classList.add('is-visible');
		clearTimeout(toastEl._t);
		toastEl._t = setTimeout(function () { toastEl.classList.remove('is-visible'); }, 1800);
	}

	function copyText(text) {
		if (navigator.clipboard && window.isSecureContext) {
			return navigator.clipboard.writeText(text);
		}
		return new Promise(function (resolve, reject) {
			var ta = document.createElement('textarea');
			ta.value = text;
			ta.setAttribute('readonly', '');
			ta.style.position = 'fixed';
			ta.style.opacity = '0';
			document.body.appendChild(ta);
			ta.select();
			try { document.execCommand('copy') ? resolve() : reject(); } catch (e) { reject(e); }
			document.body.removeChild(ta);
		});
	}

	/* ── Dark mode ─────────────────────────────────────────────────────────── */
	function currentTheme() {
		var explicit = root.getAttribute('data-theme');
		if (explicit) return explicit;
		return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
	}
	document.querySelectorAll('[data-toggle-theme]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var next = currentTheme() === 'dark' ? 'light' : 'dark';
			root.setAttribute('data-theme', next);
			try { localStorage.setItem('vs-theme', next); } catch (e) {}
		});
	});

	/* ── Dialogs: drawer + search ──────────────────────────────────────────── */
	var lastFocus = null;
	function openDialog(el, focusSelector) {
		if (!el) return;
		lastFocus = document.activeElement;
		el.hidden = false;
		document.body.style.overflow = 'hidden';
		var f = el.querySelector(focusSelector || 'input, button, a');
		if (f) setTimeout(function () { f.focus(); }, 30);
	}
	function closeDialog(el) {
		if (!el || el.hidden) return;
		el.hidden = true;
		document.body.style.overflow = '';
		if (lastFocus) lastFocus.focus();
	}

	var drawer = document.getElementById('drawer');
	var search = document.getElementById('search-modal');
	var drawerBtn = document.querySelector('[data-open-drawer]');

	if (drawerBtn) {
		drawerBtn.addEventListener('click', function () {
			drawerBtn.setAttribute('aria-expanded', 'true');
			openDialog(drawer, '.search-field');
		});
	}
	document.querySelectorAll('[data-close-drawer]').forEach(function (b) {
		b.addEventListener('click', function () {
			if (drawerBtn) drawerBtn.setAttribute('aria-expanded', 'false');
			closeDialog(drawer);
		});
	});
	document.querySelectorAll('[data-open-search]').forEach(function (b) {
		b.addEventListener('click', function () { openDialog(search, '.search-field'); });
	});
	document.querySelectorAll('[data-close-search]').forEach(function (b) {
		b.addEventListener('click', function () { closeDialog(search); });
	});

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			closeDialog(search);
			if (drawer && !drawer.hidden) {
				if (drawerBtn) drawerBtn.setAttribute('aria-expanded', 'false');
				closeDialog(drawer);
			}
		}
		var tag = (e.target && e.target.tagName) || '';
		var typing = /INPUT|TEXTAREA|SELECT/.test(tag) || (e.target && e.target.isContentEditable);
		if (e.key === '/' && !typing && search && search.hidden) {
			e.preventDefault();
			openDialog(search, '.search-field');
		}
	});

	/* ── Reading progress ──────────────────────────────────────────────────── */
	var bar = document.querySelector('.reading-progress span');
	var article = document.querySelector('.prose');
	if (bar && article) {
		var ticking = false;
		var update = function () {
			var rect = article.getBoundingClientRect();
			var total = rect.height - window.innerHeight * 0.6;
			var pct = total > 0 ? Math.min(100, Math.max(0, (-rect.top + window.innerHeight * 0.2) / total * 100)) : 0;
			bar.style.width = pct + '%';
			ticking = false;
		};
		window.addEventListener('scroll', function () {
			if (!ticking) { ticking = true; requestAnimationFrame(update); }
		}, { passive: true });
		update();
	}

	/* ── Table of contents: highlight the section being read ───────────────── */
	var tocLinks = document.querySelectorAll('.article-aside .toc-list a');
	if (tocLinks.length && 'IntersectionObserver' in window) {
		var byId = {};
		tocLinks.forEach(function (a) { byId[decodeURIComponent(a.hash.slice(1))] = a; });
		var headings = Object.keys(byId).map(function (id) { return document.getElementById(id); }).filter(Boolean);
		var visible = {};
		var obs = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) { visible[en.target.id] = en.isIntersecting; });
			var active = null;
			for (var i = 0; i < headings.length; i++) {
				if (visible[headings[i].id]) { active = headings[i].id; break; }
			}
			if (!active) {
				// Nothing in view: use the last heading above the viewport.
				for (var j = headings.length - 1; j >= 0; j--) {
					if (headings[j].getBoundingClientRect().top < 120) { active = headings[j].id; break; }
				}
			}
			tocLinks.forEach(function (a) { a.classList.remove('is-active'); });
			if (active && byId[active]) {
				byId[active].classList.add('is-active');
				var box = byId[active].closest('.sticky-aside');
				if (box) {
					var r = byId[active].getBoundingClientRect(), br = box.getBoundingClientRect();
					if (r.top < br.top || r.bottom > br.bottom) box.scrollTop += r.top - br.top - br.height / 2;
				}
			}
		}, { rootMargin: '-80px 0px -60% 0px' });
		headings.forEach(function (h) { obs.observe(h); });
	}

	// Close the mobile TOC after choosing a section.
	document.querySelectorAll('.toc-mobile a').forEach(function (a) {
		a.addEventListener('click', function () {
			var d = a.closest('details');
			if (d) d.open = false;
		});
	});

	/* ── Code blocks: language label, copy button, highlighting ────────────── */
	// Runs on DOMContentLoaded so the deferred highlight.js script has loaded.
	var copyLabel = 'Copy';
	function enhanceCode() {
		document.querySelectorAll('.prose pre').forEach(function (pre) {
			var code = pre.querySelector('code');
			if (!code) {
				code = document.createElement('code');
				code.innerHTML = pre.innerHTML;
				pre.innerHTML = '';
				pre.appendChild(code);
			}
			if (window.hljs && !code.classList.contains('hljs')) {
				try { window.hljs.highlightElement(code); } catch (e) {}
			}
			var lang = '';
			var m = (code.className || '').match(/language-([\w+-]+)/);
			if (m && m[1] !== 'undefined' && m[1] !== 'plaintext') lang = m[1];

			var barEl = document.createElement('div');
			barEl.className = 'code-bar';
			var label = document.createElement('span');
			label.textContent = lang ? lang.toUpperCase() : 'CODE';
			var btn = document.createElement('button');
			btn.type = 'button';
			btn.className = 'code-copy';
			btn.textContent = copyLabel;
			btn.setAttribute('aria-label', 'Copy code to clipboard');
			btn.addEventListener('click', function () {
				copyText(code.innerText).then(function () {
					btn.textContent = 'Copied ✓';
					btn.classList.add('is-copied');
					setTimeout(function () { btn.textContent = copyLabel; btn.classList.remove('is-copied'); }, 1600);
				});
			});
			barEl.appendChild(label);
			barEl.appendChild(btn);
			pre.appendChild(barEl);
		});
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', enhanceCode);
	} else {
		enhanceCode();
	}

	/* ── Share: copy link ──────────────────────────────────────────────────── */
	document.querySelectorAll('[data-copy-link]').forEach(function (b) {
		b.addEventListener('click', function () {
			copyText(b.getAttribute('data-copy-link')).then(function () {
				b.classList.add('is-copied');
				toast('Link copied');
				setTimeout(function () { b.classList.remove('is-copied'); }, 1600);
			});
		});
	});

	/* ── Learning progress (stored per browser) ────────────────────────────── */
	var progress = store.get('vs-progress', {});
	var lastSeen = store.get('vs-last-lesson', {});

	function doneIn(path) {
		return progress[path] || [];
	}
	function isDone(id) {
		id = String(id);
		return Object.keys(progress).some(function (p) { return progress[p].indexOf(id) !== -1; });
	}

	function paintProgress() {
		document.querySelectorAll('[data-lesson]').forEach(function (li) {
			li.classList.toggle('is-done', isDone(li.getAttribute('data-lesson')));
		});
		document.querySelectorAll('[data-path][data-total]').forEach(function (el) {
			var path = el.getAttribute('data-path');
			var total = parseInt(el.getAttribute('data-total'), 10) || 0;
			var done = doneIn(path).length;
			var pct = total ? Math.round(Math.min(done, total) / total * 100) : 0;
			el.querySelectorAll('.path-progress-bar').forEach(function (b) { b.style.width = pct + '%'; });
			var label = el.querySelector('.path-block-done');
			if (label) {
				label.hidden = !done;
				label.textContent = '· ' + Math.min(done, total) + ' of ' + total + ' done';
			}
			var start = el.querySelector('.path-start');
			if (start && done && lastSeen[path]) {
				start.href = lastSeen[path];
				var text = start.getAttribute('data-resume-label');
				if (text && start.firstChild && start.firstChild.nodeType === 3) start.firstChild.nodeValue = text + ' ';
			}
		});
		var nav = document.querySelector('.path-nav');
		if (nav) {
			var p = nav.getAttribute('data-path');
			var t = nav.querySelectorAll('[data-lesson]').length;
			var d = doneIn(p).length;
			var b = nav.querySelector('.path-progress-bar');
			if (b) b.style.width = (t ? Math.round(Math.min(d, t) / t * 100) : 0) + '%';
		}
	}

	var completeBtn = document.querySelector('[data-complete]');
	if (completeBtn) {
		var lessonId = completeBtn.getAttribute('data-complete');
		var lessonPath = completeBtn.getAttribute('data-path');
		lastSeen[lessonPath] = window.location.href.split('#')[0];
		store.set('vs-last-lesson', lastSeen);

		var sync = function () {
			completeBtn.setAttribute('aria-pressed', doneIn(lessonPath).indexOf(lessonId) !== -1 ? 'true' : 'false');
		};
		completeBtn.addEventListener('click', function () {
			var list = doneIn(lessonPath).slice();
			var i = list.indexOf(lessonId);
			if (i === -1) {
				list.push(lessonId);
				toast('Nice work! Lesson completed 🎉');
			} else {
				list.splice(i, 1);
			}
			progress[lessonPath] = list;
			store.set('vs-progress', progress);
			sync();
			paintProgress();
		});
		sync();
	}
	paintProgress();

	/* ── YouTube: load the player only when asked ──────────────────────────── */
	document.querySelectorAll('.yt-facade').forEach(function (f) {
		f.addEventListener('click', function () {
			var iframe = document.createElement('iframe');
			iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(f.getAttribute('data-vid')) + '?autoplay=1&rel=0';
			iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
			iframe.allowFullscreen = true;
			iframe.title = 'Video';
			f.replaceWith(iframe);
		});
	});

	/* ── Cookie notice ─────────────────────────────────────────────────────── */
	var note = document.getElementById('cookie-note');
	if (note && !store.get('vs-cookie-ok', false)) {
		note.hidden = false;
		var ok = note.querySelector('[data-dismiss-cookie]');
		if (ok) ok.addEventListener('click', function () {
			store.set('vs-cookie-ok', true);
			note.hidden = true;
		});
	}

	/* ── Back to top ───────────────────────────────────────────────────────── */
	document.querySelectorAll('[data-back-to-top]').forEach(function (b) {
		b.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
	});
})();
