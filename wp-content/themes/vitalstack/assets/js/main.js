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
		var htmlStart = /^<(!doctype|html|head|body|div|p|h[1-6]|button|form|input|label|ul|ol|table|a\s|img|span|section|header|nav|main|footer|article|script|style|select|textarea)\b/i;
		if (htmlStart.test(t)) return 'html';
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
	var langNames = { xml: 'HTML', html: 'HTML', javascript: 'JavaScript', js: 'JavaScript', css: 'CSS', sql: 'SQL', java: 'Java', python: 'Python', json: 'JSON', bash: 'Terminal', shell: 'Terminal', typescript: 'TypeScript', plaintext: 'Code' };
	function enhanceCode() {
		document.querySelectorAll('.prose pre').forEach(function (pre) {
			var code = pre.querySelector('code');
			if (!code) {
				code = document.createElement('code');
				code.innerHTML = pre.innerHTML;
				pre.innerHTML = '';
				pre.appendChild(code);
			}
			var raw = code.textContent;
			var hl = '';
			if (window.hljs && !code.classList.contains('hljs')) {
				try {
					window.hljs.highlightElement(code);
					var m = (code.className || '').match(/language-([\w+-]+)/);
					hl = m ? m[1] : '';
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
				tryBtn.textContent = 'Try it Yourself ❯';
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
				toast('Nice work! Lesson completed 🎉');
			} else {
				list.splice(i, 1);
			}
			progress[lessonPath] = list;
			store.set('vs-progress', progress);
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
			})
			.catch(function () {});
	}

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

	/* ── Back to top ──────────────────────────────────────────────────────── */
	document.querySelectorAll('[data-back-to-top]').forEach(function (b) {
		b.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
	});
})();
