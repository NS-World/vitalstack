/**
 * VitalStack front-end behaviour. No dependencies.
 */
(function () {
	'use strict';

	var root = document.documentElement;
	var data = window.vitalstackData || {};

	/* ── Storage (fails quietly when storage is blocked) ──────────────────── */
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
			try { localStorage.setItem(key, JSON.stringify(value)); } catch (e) {}
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

	/* ── Appearance: theme mode + accent colour ───────────────────────────── */
	function syncAppearance() {
		var mode = root.getAttribute('data-theme') || 'light';
		var accent = root.getAttribute('data-accent') || 'green';
		document.querySelectorAll('[data-set-theme]').forEach(function (b) {
			b.setAttribute('aria-pressed', b.getAttribute('data-set-theme') === mode ? 'true' : 'false');
		});
		document.querySelectorAll('[data-set-accent]').forEach(function (b) {
			b.setAttribute('aria-pressed', b.getAttribute('data-set-accent') === accent ? 'true' : 'false');
		});
	}
	document.addEventListener('click', function (e) {
		var t = e.target.closest('[data-set-theme], [data-set-accent]');
		if (!t) return;
		var isMode = t.hasAttribute('data-set-theme');
		var value = t.getAttribute(isMode ? 'data-set-theme' : 'data-set-accent');
		root.setAttribute(isMode ? 'data-theme' : 'data-accent', value);
		try { localStorage.setItem(isMode ? 'vs-theme' : 'vs-accent', value); } catch (err) {}
		syncAppearance();
	});
	syncAppearance();

	var appToggle = document.querySelector('[data-appearance-toggle]');
	var appPanel = document.getElementById('appearance-panel');
	function closeAppearance() {
		if (!appPanel || appPanel.hidden) return;
		appPanel.hidden = true;
		appToggle.setAttribute('aria-expanded', 'false');
	}
	if (appToggle && appPanel) {
		appToggle.addEventListener('click', function (e) {
			e.stopPropagation();
			var open = appPanel.hidden;
			appPanel.hidden = !open;
			appToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			if (open) { var first = appPanel.querySelector('[aria-pressed="true"]') || appPanel.querySelector('button'); if (first) first.focus(); }
		});
		document.addEventListener('click', function (e) { if (!e.target.closest('.appearance')) closeAppearance(); });
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !appPanel.hidden) { closeAppearance(); appToggle.focus(); } });
	}

	/* ── Dialogs: drawer, search ──────────────────────────────────────────── */
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

	/* ── Topic bar: arrows when it overflows, "All courses" panel ──────────── */
	(function () {
		var bar = document.querySelector('.topicbar-row .topicbar-inner');
		if (!bar) return;
		var prev = document.querySelector('[data-topic-scroll="-1"]');
		var next = document.querySelector('[data-topic-scroll="1"]');
		function arrows() {
			var max = bar.scrollWidth - bar.clientWidth;
			if (prev) prev.hidden = bar.scrollLeft <= 2;
			if (next) next.hidden = max <= 2 || bar.scrollLeft >= max - 2;
		}
		[prev, next].forEach(function (b) {
			if (!b) return;
			b.addEventListener('click', function () {
				bar.scrollBy({ left: +b.getAttribute('data-topic-scroll') * Math.max(160, bar.clientWidth * 0.7), behavior: 'smooth' });
			});
		});
		bar.addEventListener('scroll', arrows, { passive: true });
		window.addEventListener('resize', arrows);
		// Bring the course being read into view (centred) on small screens.
		var active = bar.querySelector('a.is-active:not(.topic-home)');
		if (active) {
			arrows();
			var br = bar.getBoundingClientRect(), ar = active.getBoundingClientRect();
			if (ar.right > br.right || ar.left < br.left) {
				bar.style.scrollBehavior = 'auto';
				bar.scrollLeft += ar.left - br.left - (br.width - ar.width) / 2;
				bar.style.scrollBehavior = '';
			}
		}
		// Mouse wheels scroll the bar sideways (trackpads already do).
		bar.addEventListener('wheel', function (e) {
			var max = bar.scrollWidth - bar.clientWidth;
			if (Math.abs(e.deltaY) > Math.abs(e.deltaX) && max > 0 && (e.deltaY < 0 ? bar.scrollLeft > 0 : bar.scrollLeft < max - 1)) {
				bar.scrollLeft += e.deltaY;
				e.preventDefault();
			}
		}, { passive: false });
		arrows();
		if (document.fonts && document.fonts.ready) document.fonts.ready.then(arrows);

		var open = document.querySelector('[data-courses-open]');
		var panel = document.getElementById('courses-panel');
		if (!open || !panel) return;
		var find = panel.querySelector('[data-courses-find]');
		var none = panel.querySelector('.courses-none');
		var phone = window.matchMedia('(max-width: 719px)');
		function setOpen(on) {
			if (on) {
				// On phones the panel is a sheet that starts right under the topic bar.
				var bar = open.closest('.topicbar');
				if (bar) panel.style.setProperty('--courses-top', Math.max(0, Math.round(bar.getBoundingClientRect().bottom)) + 'px');
			}
			panel.hidden = !on;
			open.setAttribute('aria-expanded', on ? 'true' : 'false');
			document.body.classList.toggle('courses-open', on);
			document.body.style.overflow = on && phone.matches ? 'hidden' : '';
			if (on) {
				var a = panel.querySelector('a.is-active');
				if (a) a.scrollIntoView({ block: 'nearest' });
				if (!phone.matches && find) find.focus({ preventScroll: true });
				else if (a) a.focus({ preventScroll: true });
			}
		}
		if (find) {
			find.addEventListener('input', function () {
				var q = find.value.trim().toLowerCase(), shown = 0;
				panel.querySelectorAll('li[data-name]').forEach(function (li) {
					var hit = !q || li.getAttribute('data-name').indexOf(q) !== -1;
					li.hidden = !hit;
					if (hit) shown++;
				});
				panel.querySelectorAll('.courses-group:not(.courses-premium)').forEach(function (g) {
					g.hidden = !g.querySelector('li[data-name]:not([hidden])');
				});
				var prem = panel.querySelector('.courses-premium');
				if (prem) prem.hidden = !!q;
				if (none) none.hidden = shown > 0;
			});
			find.addEventListener('keydown', function (e) {
				if (e.key === 'Enter') {
					var first = panel.querySelector('li[data-name]:not([hidden]) a');
					if (first) { e.preventDefault(); window.location.href = first.href; }
				}
			});
		}
		open.addEventListener('click', function (e) { e.stopPropagation(); setOpen(panel.hidden); });
		panel.querySelectorAll('[data-courses-close]').forEach(function (b) {
			b.addEventListener('click', function () { setOpen(false); open.focus(); });
		});
		document.addEventListener('click', function (e) {
			if (!panel.hidden && !panel.contains(e.target) && !open.contains(e.target)) setOpen(false);
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && !panel.hidden) { setOpen(false); open.focus(); }
		});
	})();

	/* ── Premium trailer: scenes play in turn while it is on screen ──────── */
	document.querySelectorAll('[data-trailer]').forEach(function (tr) {
		var scenes = tr.querySelectorAll('.tr-scene'), dots = tr.querySelectorAll('.tr-dots i');
		var toggle = tr.querySelector('[data-tr-toggle]');
		var still = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var at = -1, timer = null, playing = false, visible = false, paused = false;
		if (still) { tr.classList.add('is-still'); return; }
		function show(i) {
			at = (i + scenes.length) % scenes.length;
			scenes.forEach(function (s, k) { s.classList.toggle('is-on', k === at); });
			dots.forEach(function (d, k) { d.classList.toggle('is-on', k === at); d.classList.toggle('is-done', k < at); });
		}
		function next() { show(at + 1); timer = setTimeout(next, +scenes[at].getAttribute('data-d') || 3500); }
		function play() {
			if (playing) return;
			playing = true; tr.classList.remove('is-paused');
			toggle.textContent = '❚❚'; toggle.setAttribute('aria-label', 'Pause trailer');
			timer = setTimeout(next, +scenes[at].getAttribute('data-d') || 3500);
		}
		function pause() {
			playing = false; clearTimeout(timer); tr.classList.add('is-paused');
			toggle.textContent = '▶'; toggle.setAttribute('aria-label', 'Play trailer');
		}
		toggle.addEventListener('click', function () { paused = playing; if (playing) pause(); else play(); });
		dots.forEach(function (d, k) {
			d.addEventListener('click', function () { clearTimeout(timer); show(k); if (playing) timer = setTimeout(next, +scenes[k].getAttribute('data-d')); });
		});
		show(0);
		if ('IntersectionObserver' in window) {
			new IntersectionObserver(function (es) {
				visible = es[0].isIntersecting;
				if (visible && !paused) play(); else if (!visible && playing) { pause(); }
			}, { threshold: 0.35 }).observe(tr);
		} else {
			play();
		}
	});

	/* ── Account dropdown ─────────────────────────────────────────────────── */
	document.querySelectorAll('[data-dropdown]').forEach(function (btn) {
		var menu = btn.nextElementSibling;
		btn.addEventListener('click', function (e) {
			e.stopPropagation();
			var open = menu.hidden;
			menu.hidden = !open;
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		document.addEventListener('click', function (e) {
			if (!menu.hidden && !menu.contains(e.target)) {
				menu.hidden = true;
				btn.setAttribute('aria-expanded', 'false');
			}
		});
	});

	/* ── Docs sidebar (off-canvas on small screens) ───────────────────────── */
	var side = document.getElementById('doc-side');
	var sideBtn = document.querySelector('[data-toggle-side]');
	var sideBackdrop = null;
	function closeSide() {
		if (!side || !side.classList.contains('is-open')) return;
		side.classList.remove('is-open');
		if (sideBtn) sideBtn.setAttribute('aria-expanded', 'false');
		if (sideBackdrop) { sideBackdrop.remove(); sideBackdrop = null; }
	}
	if (side && sideBtn) {
		sideBtn.addEventListener('click', function () {
			side.classList.add('is-open');
			sideBtn.setAttribute('aria-expanded', 'true');
			sideBackdrop = document.createElement('div');
			sideBackdrop.className = 'side-backdrop';
			sideBackdrop.addEventListener('click', closeSide);
			document.body.appendChild(sideBackdrop);
		});
	}
	// Keep the current item visible in a long sidebar.
	if (side) {
		var cur = side.querySelector('.is-current');
		if (cur && window.innerWidth > 1024) {
			var r = cur.getBoundingClientRect();
			if (r.bottom > window.innerHeight - 40) side.scrollTop = r.top - side.getBoundingClientRect().top - 120;
		}
	}

	/* ── Account page tabs ────────────────────────────────────────────────── */
	var tabs = document.querySelectorAll('[data-tab]');
	tabs.forEach(function (tab) {
		tab.addEventListener('click', function () {
			tabs.forEach(function (t) {
				var on = t === tab;
				t.setAttribute('aria-selected', on ? 'true' : 'false');
				var panel = document.getElementById(t.getAttribute('aria-controls'));
				if (panel) panel.hidden = !on;
			});
			var first = document.querySelector('#' + tab.getAttribute('aria-controls') + ' input:not([type=hidden]):not([tabindex="-1"])');
			if (first) first.focus();
		});
	});

	/* ── Email code step: digits only, submit at 6, resend countdown ──────── */
	document.querySelectorAll('.otp-verify .otp-input').forEach(function (inp) {
		inp.addEventListener('input', function () {
			var v = inp.value.replace(/\D/g, '').slice(0, 6);
			if (v !== inp.value) inp.value = v;
			if (v.length === 6 && inp.form && !inp.form.dataset.sent) {
				inp.form.dataset.sent = '1';
				if (inp.form.requestSubmit) inp.form.requestSubmit(); else inp.form.submit();
			}
		});
	});
	document.querySelectorAll('[data-resend-wait]').forEach(function (b) {
		var left = +b.getAttribute('data-resend-wait'), label = b.textContent;
		b.disabled = true;
		(function tick() {
			if (left <= 0) { b.disabled = false; b.textContent = label; return; }
			b.textContent = label + ' (' + left-- + 's)';
			setTimeout(tick, 1000);
		})();
	});

	/* ── Reading progress ─────────────────────────────────────────────────── */
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

	/* ── "On this page": highlight the section being read ─────────────────── */
	var tocLinks = document.querySelectorAll('.doc-toc .toc-list a');
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
				for (var j = headings.length - 1; j >= 0; j--) {
					if (headings[j].getBoundingClientRect().top < 140) { active = headings[j].id; break; }
				}
			}
			tocLinks.forEach(function (a) { a.classList.remove('is-active'); });
			if (active && byId[active]) byId[active].classList.add('is-active');
		}, { rootMargin: '-110px 0px -60% 0px' });
		headings.forEach(function (h) { obs.observe(h); });
	}
	document.querySelectorAll('.toc-mobile a').forEach(function (a) {
		a.addEventListener('click', function () {
			var d = a.closest('details');
			if (d) d.open = false;
		});
	});

	/* ── Try it Yourself editor ───────────────────────────────────────────── */
	var tryit = document.getElementById('tryit');
	var tryCode = tryit && tryit.querySelector('.tryit-code');
	var tryFrame = tryit && tryit.querySelector('.tryit-result');
	var tryLangEl = tryit && tryit.querySelector('.tryit-lang');
	var tryState = { lang: 'html', original: '' };

	/**
	 * The result runs in a sandboxed iframe (scripts allowed, no access to
	 * this page, its cookies or storage). JavaScript output is captured from
	 * console.log and shown in the result pane.
	 */
	function buildDoc(lang, code) {
		if (lang === 'js') {
			var safe = code.replace(/<\/script/gi, '<\\/script');
			return '<!DOCTYPE html><html><head><meta charset="utf-8"><style>' +
				'body{font:14px/1.55 ui-monospace,Menlo,Consolas,monospace;margin:0;padding:12px;color:#1b1f27}' +
				'.l{padding:6px 4px;border-bottom:1px solid #eee;white-space:pre-wrap}.e{color:#c0262d}.hint{color:#888}' +
				'</style></head><body><div id="o"></div><script>(function(){var o=document.getElementById("o");' +
				'function fmt(x){try{return typeof x==="object"&&x!==null?JSON.stringify(x,null,2):String(x)}catch(e){return String(x)}}' +
				'function w(c,a){var d=document.createElement("div");d.className="l "+c;d.textContent=Array.prototype.map.call(a,fmt).join(" ");o.appendChild(d)}' +
				'console.log=console.info=console.warn=function(){w("",arguments)};console.error=function(){w("e",arguments)};' +
				'window.onerror=function(m){w("e",[m]);};window.addEventListener("unhandledrejection",function(ev){w("e",["Unhandled: "+ev.reason])});' +
				'setTimeout(function(){if(!o.children.length){var d=document.createElement("div");d.className="l hint";d.textContent="(No output yet. Use console.log() to print something.)";o.appendChild(d)}},1500);' +
				'})();<\/script><script>' + safe + '<\/script></body></html>';
		}
		return code;
	}
	function runTryit() {
		if (!tryFrame) return;
		tryFrame.srcdoc = buildDoc(tryState.lang, tryCode.value);
	}
	function openTryit(lang, code) {
		if (!tryit) return;
		tryState.lang = lang;
		tryState.original = code;
		tryCode.value = code;
		tryLangEl.textContent = lang === 'js' ? 'JavaScript' : 'HTML';
		lastFocus = document.activeElement;
		tryit.hidden = false;
		document.body.style.overflow = 'hidden';
		runTryit();
		setTimeout(function () { tryCode.focus(); }, 30);
	}
	function closeTryit() {
		if (!tryit || tryit.hidden) return;
		tryit.hidden = true;
		tryFrame.srcdoc = '';
		document.body.style.overflow = '';
		if (lastFocus) lastFocus.focus();
	}
	if (tryit) {
		tryit.querySelector('[data-tryit-run]').addEventListener('click', runTryit);
		tryit.querySelector('[data-tryit-close]').addEventListener('click', closeTryit);
		tryit.querySelector('[data-tryit-reset]').addEventListener('click', function () {
			tryCode.value = tryState.original;
			runTryit();
		});
		tryCode.addEventListener('keydown', function (e) {
			if (e.key === 'Tab' && !e.shiftKey) {
				e.preventDefault();
				var s = tryCode.selectionStart, en = tryCode.selectionEnd;
				tryCode.value = tryCode.value.slice(0, s) + '  ' + tryCode.value.slice(en);
				tryCode.selectionStart = tryCode.selectionEnd = s + 2;
			}
			if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
				e.preventDefault();
				runTryit();
			}
		});
	}
	document.querySelectorAll('[data-tryit-lang]').forEach(function (b) {
		b.addEventListener('click', function () {
			var code = b.getAttribute('data-tryit-code');
			if (!code) {
				var box = b.closest('.example');
				var c = box && box.querySelector('code');
				code = c ? c.textContent : '';
			}
			openTryit(b.getAttribute('data-tryit-lang'), code);
		});
	});

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			closeDialog(search);
			closeTryit();
			closeSide();
			if (drawer && !drawer.hidden) {
				if (drawerBtn) drawerBtn.setAttribute('aria-expanded', 'false');
				closeDialog(drawer);
			}
		}
		var tag = (e.target && e.target.tagName) || '';
		var typing = /INPUT|TEXTAREA|SELECT/.test(tag) || (e.target && e.target.isContentEditable);
		if (e.key === '/' && !typing && search && search.hidden && (!tryit || tryit.hidden)) {
			e.preventDefault();
			openDialog(search, '.search-field');
		}
	});

	/** Whether a snippet can run in the editor, and as what. */
	function runnableLang(text, hljsLang) {
		var t = text.trim();
		// Any snippet that starts with a lowercase HTML tag, a doctype or an HTML comment.
		// (Capitalised tags like <App /> are JSX and won't run in a plain page.)
		if (/^<!doctype\s/i.test(t) || /^<!--/.test(t) || /^<[a-z][a-z0-9-]*[\s>\/]/.test(t)) return 'html';
		if (hljsLang === 'xml' || hljsLang === 'html' || hljsLang === 'css' || hljsLang === 'sql' || hljsLang === 'java' || hljsLang === 'python') return '';
		if (/^\s*(SELECT|INSERT|UPDATE|DELETE|CREATE|ALTER|DROP)\b/i.test(t) || /\bpublic\s+(static\s+)?(class|void)\b/.test(t)) return '';
		if (hljsLang === 'javascript' || /console\.log\(|document\.|=>|\bfunction\s*\w*\s*\(|\b(let|const|var)\s+\w+\s*=/.test(t)) {
			// Node, React/JSX and TypeScript snippets won't run in a plain page.
			if (/\brequire\(|^\s*import\s|\bexport\s|<[A-Z]\w*|\bprocess\.|:\s*(string|number|boolean)\b|\bapp\.(get|post|listen)\(/m.test(t)) return '';
			return 'js';
		}
		return '';
	}

	/* ── Code blocks: label, copy, try it, highlighting ───────────────────── */
	var langNames = { xml: 'HTML', html: 'HTML', javascript: 'JavaScript', js: 'JavaScript', css: 'CSS', sql: 'SQL', java: 'Java', python: 'Python', json: 'JSON', bash: 'Terminal', shell: 'Terminal', typescript: 'TypeScript', yaml: 'YAML', ini: 'Properties', plaintext: 'Code' };
	function enhanceCode() {
		// Auto-detect only among the languages the lessons use: faster and more accurate.
		if (window.hljs) {
			window.hljs.configure({ languages: ['xml', 'css', 'javascript', 'python', 'sql', 'bash', 'json', 'java', 'typescript', 'plaintext'] });
		}
		document.querySelectorAll('.prose pre').forEach(function (pre) {
			if (pre.closest('.vs-walk')) return; // walk.js draws its own code view
			var code = pre.querySelector('code');
			if (!code) {
				code = document.createElement('code');
				code.innerHTML = pre.innerHTML;
				pre.innerHTML = '';
				pre.appendChild(code);
			}
			var raw = code.textContent;
			var declared = (code.className.match(/language-([\w+-]+)/) || [])[1] || '';   // from the lesson's code fence
			var hl = declared === 'plaintext' ? '' : declared;
			if (window.hljs && !code.classList.contains('hljs')) {
				try {
					window.hljs.highlightElement(code);
					var m = (code.className || '').match(/language-([\w+-]+)/);
					hl = m ? m[1] : hl;
				} catch (e) {}
			}
			var runLang = runnableLang(raw, hl);
			var label = runLang === 'html' ? 'HTML' : runLang === 'js' ? 'JavaScript' : (langNames[hl] || 'Code');

			var barEl = document.createElement('div');
			barEl.className = 'code-bar';
			var lab = document.createElement('span');
			lab.textContent = label === 'Code' || label === 'Terminal' ? label : label + ' Example';
			var actions = document.createElement('div');
			actions.className = 'code-bar-actions';

			var copy = document.createElement('button');
			copy.type = 'button';
			copy.className = 'code-btn';
			copy.textContent = 'Copy';
			copy.addEventListener('click', function () {
				copyText(raw).then(function () {
					copy.textContent = 'Copied ✓';
					copy.classList.add('is-copied');
					setTimeout(function () { copy.textContent = 'Copy'; copy.classList.remove('is-copied'); }, 1600);
				});
			});
			actions.appendChild(copy);

			if (runLang && tryit) {
				var tryBtn = document.createElement('button');
				tryBtn.type = 'button';
				tryBtn.className = 'code-btn code-btn-try';
				tryBtn.innerHTML = 'Try it<span class="code-btn-long"> Yourself</span> ❯';
				tryBtn.addEventListener('click', function () { openTryit(runLang, raw); });
				actions.appendChild(tryBtn);
			}
			barEl.appendChild(lab);
			barEl.appendChild(actions);
			pre.appendChild(barEl);
			pre.classList.add('has-bar');
		});
		if (window.hljs) {
			document.querySelectorAll('.example code').forEach(function (c) {
				try { window.hljs.highlightElement(c); } catch (e) {}
			});
		}
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', enhanceCode);
	} else {
		enhanceCode();
	}

	/* ── Share: copy link ─────────────────────────────────────────────────── */
	document.querySelectorAll('[data-copy-link]').forEach(function (b) {
		b.addEventListener('click', function () {
			copyText(b.getAttribute('data-copy-link')).then(function () {
				b.classList.add('is-copied');
				toast('Link copied');
				setTimeout(function () { b.classList.remove('is-copied'); }, 1600);
			});
		});
	});

	/* ── Learning progress: this browser, synced to the account when signed in ── */
	var progress = store.get('vs-progress', {});
	var lastSeen = store.get('vs-last-lesson', {});

	function doneIn(path) { return progress[path] || []; }
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
			var done = Math.min(doneIn(path).length, total);
			var pct = total ? Math.round(done / total * 100) : 0;
			el.querySelectorAll('.path-progress-bar').forEach(function (b) { b.style.width = pct + '%'; });
			el.classList.toggle('has-progress', done > 0);
			var label = el.querySelector('.path-block-done');
			if (label) {
				label.hidden = !done;
				label.textContent = '· ' + done + ' of ' + total + ' done';
			}
			var start = el.querySelector('.path-start');
			if (start && done && lastSeen[path]) {
				start.href = lastSeen[path];
				var text = start.getAttribute('data-resume-label');
				if (text && start.firstChild && start.firstChild.nodeType === 3) start.firstChild.nodeValue = text + ' ';
			}
		});
	}

	function merge(a, b) {
		var out = {};
		[a, b].forEach(function (src) {
			Object.keys(src || {}).forEach(function (p) {
				out[p] = out[p] || [];
				(src[p] || []).forEach(function (id) {
					id = String(id);
					if (out[p].indexOf(id) === -1) out[p].push(id);
				});
			});
		});
		return out;
	}

	var syncTimer;
	function pushProgress() {
		if (!data.loggedIn || !data.restUrl || !window.fetch) return;
		clearTimeout(syncTimer);
		syncTimer = setTimeout(function () {
			fetch(data.restUrl, {
				method: 'POST',
				credentials: 'same-origin',
				headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': data.restNonce },
				body: JSON.stringify({ progress: progress })
			}).then(function (r) { return r.ok ? r.json() : null; }).then(function (res) {
				if (res && res.xp) document.dispatchEvent(new CustomEvent('vs:xp', { detail: res }));
				if (res && res.newCerts && res.newCerts.length) document.dispatchEvent(new CustomEvent('vs:cert', { detail: res.newCerts[0] }));
				else if (res && res.lockedCerts && res.lockedCerts.length) document.dispatchEvent(new CustomEvent('vs:cert-locked', { detail: res.lockedCerts[0] }));
			}).catch(function () {});
		}, 300);
	}

	var completeBtn = document.querySelector('[data-complete]');
	var syncButton = function () {};
	if (completeBtn) {
		var lessonId = completeBtn.getAttribute('data-complete');
		var lessonPath = completeBtn.getAttribute('data-path');
		lastSeen[lessonPath] = window.location.href.split('#')[0];
		store.set('vs-last-lesson', lastSeen);

		syncButton = function () {
			completeBtn.setAttribute('aria-pressed', doneIn(lessonPath).indexOf(lessonId) !== -1 ? 'true' : 'false');
		};
		completeBtn.addEventListener('click', function () {
			var list = doneIn(lessonPath).slice();
			var i = list.indexOf(lessonId);
			if (i === -1) {
				list.push(lessonId);
				if (window.VS) document.dispatchEvent(new CustomEvent('vs:lesson-complete', { detail: { id: lessonId, path: lessonPath } }));
				else toast('Nice work! Lesson completed 🎉');
			} else {
				list.splice(i, 1);
			}
			progress[lessonPath] = list;
			store.set('vs-progress', progress);
			var pathEl = document.querySelector('[data-path="' + lessonPath + '"][data-total]');
			if (i === -1 && pathEl && list.length >= parseInt(pathEl.getAttribute('data-total'), 10)) {
				var nameEl = pathEl.querySelector('summary');
				document.dispatchEvent(new CustomEvent('vs:path-complete', { detail: { path: lessonPath, name: nameEl ? nameEl.textContent.trim() : '' } }));
			}
			syncButton();
			paintProgress();
			pushProgress();
		});
		syncButton();
	}
	paintProgress();

	if (data.loggedIn && data.restUrl && window.fetch) {
		fetch(data.restUrl, { credentials: 'same-origin', headers: { 'X-WP-Nonce': data.restNonce } })
			.then(function (r) { return r.ok ? r.json() : null; })
			.then(function (res) {
				if (!res) return;
				var server = res.progress || {};
				var merged = merge(server, progress);
				var changed = JSON.stringify(merged) !== JSON.stringify(merge(server, {}));
				progress = merged;
				store.set('vs-progress', progress);
				paintProgress();
				syncButton();
				if (changed) pushProgress();
				if (res.newCerts && res.newCerts.length) document.dispatchEvent(new CustomEvent('vs:cert', { detail: res.newCerts[0] }));
			})
			.catch(function () {});
	}

	/* ── Copying lesson text keeps a link back to the source ─────────────── */
	// Code is left untouched so it still pastes and runs cleanly.
	document.addEventListener('copy', function (e) {
		var sel = window.getSelection ? window.getSelection() : null;
		if (!sel || sel.isCollapsed || !e.clipboardData) return;
		var node = sel.anchorNode && (sel.anchorNode.nodeType === 1 ? sel.anchorNode : sel.anchorNode.parentElement);
		if (!node || !node.closest || !node.closest('.prose') || node.closest('pre, code, textarea, input, .tryit')) return;
		var text = sel.toString();
		if (text.length < 140) return;
		var url = window.location.href.split('#')[0];
		e.clipboardData.setData('text/plain', text + '\n\nSource: ' + document.title + ' – ' + url);
		e.preventDefault();
	});

	/* ── YouTube: load the player only when asked ─────────────────────────── */
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

	/* ── Cookie notice ────────────────────────────────────────────────────── */
	var note = document.getElementById('cookie-note');
	if (note && !store.get('vs-cookie-ok', false)) {
		note.hidden = false;
		var ok = note.querySelector('[data-dismiss-cookie]');
		if (ok) ok.addEventListener('click', function () {
			store.set('vs-cookie-ok', true);
			note.hidden = true;
		});
	}

	/* ── Cheat sheet PDF (signed-in readers; ?pdf=1 after signing up) ─────── */
	var printBtn = document.querySelector('[data-print-sheet]');
	if (printBtn) {
		printBtn.addEventListener('click', function () { window.print(); });
		if (/[?&]pdf=1(&|$)/.test(location.search)) {
			history.replaceState(null, '', location.pathname + location.hash);
			window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 400); });
		}
	}

	/* ── Gentle sign-up reminder after a guest's third lesson ─────────────── */
	// Never blocks the lesson: a small dismissible card, at most once every 3 days.
	if (!data.loggedIn && completeBtn && data.accountUrl) {
		var nudgeSeen = store.get('vs-guest-lessons', []);
		var nudgeId = completeBtn.getAttribute('data-complete');
		if (nudgeSeen.indexOf(nudgeId) === -1) { nudgeSeen.push(nudgeId); store.set('vs-guest-lessons', nudgeSeen.slice(-50)); }
		var nudgeSnooze = store.get('vs-nudge-hide', 0);
		if (nudgeSeen.length >= 3 && Date.now() > nudgeSnooze) {
			var showNudge = function () {
				if (document.querySelector('.nudge') || !store.get('vs-cookie-ok', false)) return;
				var nudgeUrl = new URL(data.accountUrl, location.href);
				nudgeUrl.searchParams.set('tab', 'register');
				nudgeUrl.searchParams.set('subscribe', '1');
				nudgeUrl.searchParams.set('redirect_to', location.href.split('#')[0]);
				var nudgeBox = document.createElement('aside');
				nudgeBox.className = 'nudge';
				nudgeBox.setAttribute('aria-label', 'Create a free account');
				nudgeBox.innerHTML = '<button type="button" class="nudge-close" aria-label="Not now">×</button>' +
					'<p class="nudge-title">🎉 ' + nudgeSeen.length + ' lessons done! Save your progress</p>' +
					'<p class="nudge-text">Create a free account to keep your progress on every device, earn certificates, and get the free <b>30-Day Java Interview Plan</b> (PDF).</p>' +
					'<div class="nudge-actions"><a class="btn btn-primary btn-sm" href="' + nudgeUrl.toString().replace(/"/g, '&quot;') + '">Sign up free</a>' +
					'<button type="button" class="btn btn-ghost btn-sm" data-nudge-later>Not now</button></div>';
				var hideNudge = function () {
					store.set('vs-nudge-hide', Date.now() + 3 * 864e5);
					nudgeBox.classList.remove('is-visible');
					setTimeout(function () { nudgeBox.remove(); }, 300);
				};
				nudgeBox.querySelector('.nudge-close').addEventListener('click', hideNudge);
				nudgeBox.querySelector('[data-nudge-later]').addEventListener('click', hideNudge);
				document.body.appendChild(nudgeBox);
				requestAnimationFrame(function () { nudgeBox.classList.add('is-visible'); });
			};
			// Show it once the reader is into the lesson, not on arrival.
			var nudgeOnScroll = function () {
				if (window.scrollY > document.documentElement.scrollHeight * 0.35) {
					window.removeEventListener('scroll', nudgeOnScroll);
					showNudge();
				}
			};
			window.addEventListener('scroll', nudgeOnScroll, { passive: true });
		}
	}

	/* ── Back to top ──────────────────────────────────────────────────────── */
	document.querySelectorAll('[data-back-to-top]').forEach(function (b) {
		b.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
	});

	/* ── Installable app: service worker + install button ─────────────────── */
	if ('serviceWorker' in navigator && data.sw && (location.protocol === 'https:' || location.hostname === 'localhost')) {
		window.addEventListener('load', function () { navigator.serviceWorker.register(data.sw).catch(function () {}); });
	}
	window.addEventListener('beforeinstallprompt', function (e) {
		e.preventDefault();
		var until = store.get('vs-install-hide', 0);
		if (until && Date.now() < until) return;
		var prompt = e;
		var box = document.createElement('div');
		box.className = 'install-app';
		box.innerHTML = '<button type="button" class="install-app-go">📲 Install the VitalStack app</button><button type="button" class="install-app-x" aria-label="Not now">×</button>';
		document.body.appendChild(box);
		box.querySelector('.install-app-go').addEventListener('click', function () {
			prompt.prompt();
			prompt.userChoice.finally(function () { box.remove(); });
		});
		box.querySelector('.install-app-x').addEventListener('click', function () {
			store.set('vs-install-hide', Date.now() + 30 * 864e5);
			box.remove();
		});
	});
})();
