/* VitalStack AI Hub: background, 3D orb, tilt, filters, dialogs, copy and download. No libraries. */
(function () {
	'use strict';
	var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var T = window.VSAI || { copied: 'Copied ✓', copy: 'Copy' };

	/* Starry background (lightweight, pauses when the tab is hidden). */
	var bg = document.querySelector('.vsai-bg');
	if (bg && bg.getContext) {
		var bx = bg.getContext('2d'), stars = [], bw, bh;
		var sizeBg = function () {
			var d = Math.min(window.devicePixelRatio || 1, 2);
			bw = bg.width = innerWidth * d; bh = bg.height = innerHeight * d;
			var n = Math.min(160, Math.round(innerWidth * innerHeight / 9000));
			stars = [];
			for (var i = 0; i < n; i++) stars.push({ x: Math.random() * bw, y: Math.random() * bh, r: (Math.random() * 1.4 + .3) * d, s: Math.random() * .3 + .05, p: Math.random() * 6 });
		};
		sizeBg(); addEventListener('resize', sizeBg);
		var drawBg = function (t) {
			bx.clearRect(0, 0, bw, bh);
			var g = bx.createRadialGradient(bw * .8, 0, 0, bw * .8, 0, bw * .7);
			g.addColorStop(0, 'rgba(139,92,246,.18)'); g.addColorStop(1, 'rgba(139,92,246,0)');
			bx.fillStyle = g; bx.fillRect(0, 0, bw, bh);
			for (var i = 0; i < stars.length; i++) {
				var s = stars[i];
				bx.globalAlpha = .35 + .65 * Math.abs(Math.sin(t / 1400 + s.p));
				bx.fillStyle = '#c7d2fe'; bx.beginPath(); bx.arc(s.x, s.y, s.r, 0, 7); bx.fill();
				if (!reduce) { s.y -= s.s; if (s.y < 0) s.y = bh; }
			}
			bx.globalAlpha = 1;
			if (!reduce && !document.hidden) requestAnimationFrame(drawBg);
		};
		requestAnimationFrame(drawBg);
		document.addEventListener('visibilitychange', function () { if (!document.hidden && !reduce) requestAnimationFrame(drawBg); });
	}

	/* 3D orb: points on a sphere, rotated and projected, with links between near neighbours. */
	var orb = document.querySelector('.vsai-orb-c');
	if (orb && orb.getContext) {
		var ox = orb.getContext('2d'), pts = [], N = 220, ow, oh, rx = 0, ry = 0, tx = 0, ty = 0;
		for (var i = 0; i < N; i++) {
			var y = 1 - (i / (N - 1)) * 2, rad = Math.sqrt(1 - y * y), th = i * 2.399963;
			pts.push([Math.cos(th) * rad, y, Math.sin(th) * rad]);
		}
		var sizeOrb = function () { var d = Math.min(window.devicePixelRatio || 1, 2); ow = orb.width = orb.offsetWidth * d; oh = orb.height = orb.offsetHeight * d; };
		sizeOrb(); addEventListener('resize', sizeOrb);
		orb.parentNode.addEventListener('pointermove', function (e) { var r = orb.getBoundingClientRect(); tx = ((e.clientY - r.top) / r.height - .5) * .8; ty = ((e.clientX - r.left) / r.width - .5) * .8; });
		var a = 0;
		var drawOrb = function () {
			a += reduce ? 0 : .0035; rx += (tx - rx) * .05; ry += (ty - ry) * .05;
			var R = Math.min(ow, oh) * .36, cx = ow / 2, cy = oh / 2, sa = Math.sin(a + ry), ca = Math.cos(a + ry), sb = Math.sin(rx + .35), cb = Math.cos(rx + .35), P = [];
			for (var i = 0; i < N; i++) {
				var p = pts[i], x = p[0] * ca - p[2] * sa, z = p[0] * sa + p[2] * ca, y2 = p[1] * cb - z * sb, z2 = p[1] * sb + z * cb, k = 2.4 / (2.4 + z2);
				P.push([cx + x * R * k, cy + y2 * R * k, z2]);
			}
			ox.clearRect(0, 0, ow, oh);
			ox.lineWidth = 1;
			for (var i = 0; i < N; i++) {
				for (var j = i + 1; j < Math.min(N, i + 14); j++) {
					var dx = P[i][0] - P[j][0], dy = P[i][1] - P[j][1], d2 = dx * dx + dy * dy, lim = R * R * .06;
					if (d2 < lim) {
						var depth = (2 - P[i][2] - P[j][2]) / 4;
						ox.strokeStyle = 'rgba(125,211,252,' + (depth * .5 * (1 - d2 / lim)) + ')';
						ox.beginPath(); ox.moveTo(P[i][0], P[i][1]); ox.lineTo(P[j][0], P[j][1]); ox.stroke();
					}
				}
			}
			for (var i = 0; i < N; i++) {
				var dd = (1 - P[i][2]) / 2;
				ox.fillStyle = 'hsla(' + (250 - dd * 70) + ',90%,' + (60 + dd * 20) + '%,' + (.25 + dd * .75) + ')';
				ox.beginPath(); ox.arc(P[i][0], P[i][1], (1 + dd * 2.2) * (ow / 400), 0, 7); ox.fill();
			}
			if (!document.hidden) requestAnimationFrame(drawOrb);
		};
		requestAnimationFrame(drawOrb);
		document.addEventListener('visibilitychange', function () { if (!document.hidden) requestAnimationFrame(drawOrb); });
	}

	/* 3D tilt + glow on the section doors. */
	document.querySelectorAll('[data-tilt]').forEach(function (el) {
		el.addEventListener('pointermove', function (e) {
			var r = el.getBoundingClientRect(), px = (e.clientX - r.left) / r.width, py = (e.clientY - r.top) / r.height;
			el.style.setProperty('--mx', px * 100 + '%'); el.style.setProperty('--my', py * 100 + '%');
			if (!reduce) el.style.transform = 'rotateY(' + (px - .5) * 12 + 'deg) rotateX(' + (.5 - py) * 12 + 'deg)';
		});
		el.addEventListener('pointerleave', function () { el.style.transform = ''; });
	});

	/* Phone menu. */
	var burger = document.querySelector('.vsai-burger'), nav = document.getElementById('vsai-nav');
	if (burger && nav) {
		burger.addEventListener('click', function () {
			var open = burger.getAttribute('aria-expanded') !== 'true';
			burger.setAttribute('aria-expanded', open ? 'true' : 'false'); nav.classList.toggle('is-open', open);
		});
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && nav.classList.contains('is-open')) { burger.click(); burger.focus(); } });
	}

	/* Search + category + free/premium filters. */
	var root = document.querySelector('[data-filter-root]'), list = document.querySelector('[data-list]');
	if (root && list && !root.hasAttribute('data-server')) {
		var q = root.querySelector('[data-q]'), countEl = root.querySelector('[data-count]'), empty = document.querySelector('[data-empty]');
		var state = { q: '', cat: '', price: '' };
		var cards = Array.prototype.slice.call(list.children);
		var apply = function () {
			var n = 0, words = state.q.toLowerCase().split(/\s+/).filter(Boolean);
			cards.forEach(function (c) {
				var text = c.getAttribute('data-text') || '';
				var ok = (!state.cat || c.getAttribute('data-cat') === state.cat) && (!state.price || c.getAttribute('data-price') === state.price) && words.every(function (w) { return text.indexOf(w) !== -1; });
				c.hidden = !ok; if (ok) n++;
			});
			if (countEl) countEl.textContent = n + ' / ' + cards.length;
			if (empty) empty.hidden = n !== 0;
		};
		var t;
		q && q.addEventListener('input', function () { clearTimeout(t); t = setTimeout(function () { state.q = q.value.trim(); apply(); }, 120); });
		root.querySelectorAll('[data-cat]').forEach(function (b) {
			b.addEventListener('click', function () {
				root.querySelectorAll('[data-cat]').forEach(function (x) { x.classList.remove('is-on'); x.setAttribute('aria-pressed', 'false'); });
				b.classList.add('is-on'); b.setAttribute('aria-pressed', 'true'); state.cat = b.getAttribute('data-cat'); apply();
			});
		});
		root.querySelectorAll('[data-price]').forEach(function (b) {
			b.addEventListener('click', function () {
				root.querySelectorAll('[data-price]').forEach(function (x) { x.classList.remove('is-on'); x.setAttribute('aria-pressed', 'false'); });
				b.classList.add('is-on'); b.setAttribute('aria-pressed', 'true'); state.price = b.getAttribute('data-price'); apply();
			});
		});
		var hash = decodeURIComponent((location.hash || '').slice(1));
		if (hash && q) { q.value = hash.replace(/-/g, ' '); state.q = q.value; }
		apply();
	}

	/* Live previews: load when a tile is near the screen, unload when it leaves (saves CPU and battery). */
	var loadFrame = function (f) { if (f.hasAttribute('data-src') && f.getAttribute('data-on') !== '1') { f.setAttribute('data-on', '1'); f.src = f.getAttribute('data-src'); } };
	var unloadFrame = function (f) { if (f.getAttribute('data-on') === '1') { f.setAttribute('data-on', '0'); f.src = 'about:blank'; } };
	var tileFrames = document.querySelectorAll('.vsai-frame iframe[data-src]');
	if ('IntersectionObserver' in window) {
		var io = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) loadFrame(e.target); else unloadFrame(e.target); }); }, { rootMargin: '150px' });
		tileFrames.forEach(function (f) { io.observe(f); });
	} else {
		tileFrames.forEach(loadFrame);
	}

	/* Copy buttons (works in the page and in the dialog). */
	var copyText = function (text, btn) {
		var done = function () { var l = btn.textContent; btn.textContent = T.copied; setTimeout(function () { btn.textContent = l; }, 1400); };
		if (navigator.clipboard && window.isSecureContext) { navigator.clipboard.writeText(text).then(done, function () {}); return; }
		var ta = document.createElement('textarea'); ta.value = text; ta.setAttribute('readonly', ''); ta.style.position = 'fixed'; ta.style.opacity = '0';
		document.body.appendChild(ta); ta.select(); try { document.execCommand('copy'); done(); } catch (e) {} document.body.removeChild(ta);
	};
	document.addEventListener('click', function (e) {
		var b = e.target.closest('[data-copy]');
		if (b) { var src = b.parentNode.querySelector('[data-copy-src]'); if (src) copyText(src.textContent, b); return; }
		var dl = e.target.closest('[data-download]');
		if (dl) {
			var pane = dl.closest('.vsai-mo').querySelector('[data-pane="code"] [data-copy-src]');
			if (!pane) return;
			var url = URL.createObjectURL(new Blob([pane.textContent], { type: 'text/html' }));
			var a = document.createElement('a'); a.href = url; a.download = dl.getAttribute('data-download'); document.body.appendChild(a); a.click(); a.remove();
			setTimeout(function () { URL.revokeObjectURL(url); }, 1000);
			return;
		}
		var tab = e.target.closest('[data-tab]');
		if (tab) {
			var box = tab.closest('.vsai-mo');
			box.querySelectorAll('[data-tab]').forEach(function (x) { x.classList.toggle('is-on', x === tab); });
			box.querySelectorAll('[data-pane]').forEach(function (p) { p.hidden = p.getAttribute('data-pane') !== tab.getAttribute('data-tab'); });
		}
	});

	/* Motion Studio: code and prompts come from the server, within the copy limits. */
	var gate = function (msg) {
		var tpl = document.getElementById('vsai-gate'), dlgEl = document.querySelector('.vsai-dialog');
		if (!tpl || !dlgEl) { alert(msg); return; }
		var b = dlgEl.querySelector('.vsai-dialog-body'); b.innerHTML = ''; b.appendChild(tpl.content.cloneNode(true));
		var m = b.querySelector('[data-gate-msg]'); if (m) m.textContent = msg;
		if (!dlgEl.open) { if (typeof dlgEl.showModal === 'function') dlgEl.showModal(); else dlgEl.setAttribute('open', ''); }
	};
	var fetchItem = function (id, part) {
		var h = { 'Content-Type': 'application/json' };
		if (T.nonce) h['X-WP-Nonce'] = T.nonce;
		return fetch(T.rest, { method: 'POST', credentials: 'same-origin', headers: h, body: JSON.stringify({ id: id, part: part }) })
			.then(function (r) { return r.json().catch(function () { return {}; }).then(function (j) { if (!r.ok) { var e = new Error(j.message || T.fail); e.code = j.code; throw e; } return j; }); });
	};
	document.addEventListener('click', function (e) {
		var b = e.target.closest('[data-copy-mo]');
		if (!b) return;
		e.preventDefault(); e.stopPropagation();
		var id = b.getAttribute('data-copy-mo'), part = b.getAttribute('data-part') || 'prompt', file = b.getAttribute('data-download');
		var req = fetchItem(id, part);
		var done = function (j) {
			if (j && j.note) document.querySelectorAll('[data-left]').forEach(function (el) { el.textContent = j.note; });
			var l = b.getAttribute('aria-label') || b.textContent; if (b.textContent.trim()) { b.textContent = file ? '✓' : T.copied; setTimeout(function () { b.textContent = l; }, 1400); } else { b.classList.add('is-done'); setTimeout(function () { b.classList.remove('is-done'); }, 1400); }
		};
		var fail = function (err) { if (err && /^(login|limit|upgrade)$/.test(err.code || '')) gate(err.message); else gate(err && err.message ? err.message : T.fail); };
		if (file) {
			req.then(function (j) {
				var url = URL.createObjectURL(new Blob([j.text], { type: 'text/html' }));
				var a = document.createElement('a'); a.href = url; a.download = file; document.body.appendChild(a); a.click(); a.remove();
				setTimeout(function () { URL.revokeObjectURL(url); }, 1000); done(j);
			}).catch(fail);
			return;
		}
		// Safari needs the clipboard write to start inside the click: hand it a promise of the text.
		if (window.ClipboardItem && navigator.clipboard && navigator.clipboard.write && window.isSecureContext) {
			var holder = { j: null };
			var blob = req.then(function (j) { holder.j = j; return new Blob([j.text], { type: 'text/plain' }); });
			navigator.clipboard.write([new ClipboardItem({ 'text/plain': blob })]).then(function () { done(holder.j); }).catch(function () {
				req.then(function (j) { copyText(j.text, b); done(j); }).catch(fail);
			});
			req.catch(fail);
			return;
		}
		req.then(function (j) { copyText(j.text, b); done(j); }).catch(fail);
	}, true);

	/* Previews render at a real desktop size (1280×800) or phone size (390×844) and are scaled to fit,
	   so every screen sees the design as it was made, never a squeezed layout. */
	var fitBox = function (box) {
		var f = box.querySelector('iframe'); if (!f) return;
		var phone = box.getAttribute('data-device') === 'phone', vw = phone ? 390 : 1280, vh = phone ? 844 : 800;
		var s = Math.min(box.clientWidth / vw, box.clientHeight / vh) || 0.25;
		f.style.width = vw + 'px'; f.style.height = vh + 'px'; f.style.transform = 'translate(-50%,-50%) scale(' + s + ')';
	};
	var ro = 'ResizeObserver' in window ? new ResizeObserver(function (es) { es.forEach(function (e) { fitBox(e.target); }); }) : null;
	var watchFit = function (root) { root.querySelectorAll('.vsai-frame, .vsai-mo2-stage').forEach(function (b) { fitBox(b); if (ro) ro.observe(b); }); };
	watchFit(document);
	if (!ro) window.addEventListener('resize', function () { watchFit(document); });
	document.addEventListener('click', function (e) {
		var d = e.target.closest('[data-dev]'); if (!d) return;
		var stage = d.closest('.vsai-mo2') && d.closest('.vsai-mo2').querySelector('.vsai-mo2-stage'); if (!stage) return;
		stage.setAttribute('data-device', d.getAttribute('data-dev'));
		d.parentNode.querySelectorAll('[data-dev]').forEach(function (x) { x.setAttribute('aria-pressed', x === d ? 'true' : 'false'); });
		fitBox(stage);
	});

	/* Dialog: fill it from the item's <template>. */
	var dlg = document.querySelector('.vsai-dialog'), body = dlg && dlg.querySelector('.vsai-dialog-body'), opener = null;
	var closeDlg = function () { if (dlg.open) dlg.close(); };
	document.addEventListener('click', function (e) {
		var o = e.target.closest('[data-open]');
		if (!o || !dlg || !body) return;
		var tpl = document.querySelector(o.getAttribute('data-open'));
		if (!tpl) return;
		opener = o; body.innerHTML = ''; body.appendChild(tpl.content.cloneNode(true));
		body.querySelectorAll('iframe[data-src]').forEach(loadFrame);
		if (typeof dlg.showModal === 'function') dlg.showModal(); else dlg.setAttribute('open', '');
		watchFit(body);
		var x = dlg.querySelector('[data-close]'); if (x) x.focus();
	});
	if (dlg) {
		dlg.addEventListener('click', function (e) { if (e.target === dlg || e.target.closest('[data-close]')) closeDlg(); });
		dlg.addEventListener('close', function () { body.innerHTML = ''; if (opener) opener.focus(); });
	}
	/* ── Prompt Bazaar 2.0 ── */
	/* Copy a prompt, expand long prompts, auto-apply topic/tool selects. */
	document.addEventListener('click', function (e) {
		var c = e.target.closest('[data-copy-p]');
		if (c) { var src = c.closest('.vsai-prompt').querySelector('[data-copy-src]'); if (src) copyText(src.textContent, c); return; }
		var x = e.target.closest('[data-expand]');
		if (x) {
			var code = x.closest('.vsai-prompt').querySelector('.vsai-code'); var open = code.classList.toggle('is-open');
			x.setAttribute('aria-expanded', open ? 'true' : 'false'); x.textContent = open ? (T.less || 'Show less') : (T.more || 'Show all');
		}
	});
	document.querySelectorAll('form[data-autosubmit] select').forEach(function (sel) {
		sel.addEventListener('change', function () { sel.form.submit(); });
	});

	/* Fill in & copy: every [PLACEHOLDER] becomes a text box; the preview updates as you type. */
	document.addEventListener('click', function (e) {
		var b = e.target.closest('[data-fill]'); if (!b || !dlg || !body) return;
		var card = b.closest('.vsai-prompt'), tpl = document.getElementById('vsai-fill'); if (!card || !tpl) return;
		var text = card.querySelector('[data-copy-src]').textContent;
		var keys = []; text.replace(/\[([A-Z0-9][^\]\n]{0,60})\]/g, function (m, k) { if (keys.indexOf(k) === -1) keys.push(k); return m; });
		opener = b; body.innerHTML = ''; body.appendChild(tpl.content.cloneNode(true));
		body.querySelector('[data-fill-title]').textContent = card.querySelector('h3').textContent;
		var fields = body.querySelector('[data-fill-fields]'), prev = body.querySelector('[data-fill-preview]'), vals = {};
		var render = function () {
			prev.textContent = text.replace(/\[([A-Z0-9][^\]\n]{0,60})\]/g, function (m, k) { return vals[k] ? vals[k] : m; });
		};
		keys.slice(0, 24).forEach(function (k, i) {
			var id = 'vsai-f' + i, lab = document.createElement('label'); lab.setAttribute('for', id);
			lab.textContent = k.charAt(0) + k.slice(1).toLowerCase().replace(/\s*\/\s*/g, ' / ');
			var inp = document.createElement(k.length > 30 ? 'textarea' : 'input'); inp.id = id; inp.placeholder = k; inp.maxLength = 2000;
			if (inp.tagName === 'TEXTAREA') inp.rows = 2;
			inp.addEventListener('input', function () { vals[k] = inp.value.trim(); render(); });
			fields.appendChild(lab); fields.appendChild(inp);
		});
		render();
		body.querySelector('[data-fill-copy]').addEventListener('click', function (ev) { copyText(prev.textContent, ev.currentTarget); });
		if (typeof dlg.showModal === 'function') dlg.showModal(); else dlg.setAttribute('open', '');
		var first = fields.querySelector('input,textarea'); if (first) first.focus();
	});

	/* Search suggestions (titles only) with keyboard support. */
	var sf = document.querySelector('[data-suggest-form]');
	if (sf && T.suggest && window.fetch) {
		var si = sf.querySelector('input[name="q"]'), list = sf.querySelector('.vsai-sugg'), timer, active = -1, last = '';
		var close = function () { list.hidden = true; si.setAttribute('aria-expanded', 'false'); active = -1; };
		var paint = function (items) {
			list.innerHTML = '';
			if (!items.length) { close(); return; }
			items.forEach(function (it, i) {
				var li = document.createElement('li'); li.setAttribute('role', 'option'); li.id = 'vsai-sg' + i;
				var a = document.createElement('a'); a.href = it.url; a.textContent = it.title;
				var small = document.createElement('small'); small.textContent = it.cat + (it.pro ? ' · ' + (T.pro || 'Pro') : '');
				a.appendChild(small); li.appendChild(a); list.appendChild(li);
			});
			list.hidden = false; si.setAttribute('aria-expanded', 'true');
		};
		si.addEventListener('input', function () {
			clearTimeout(timer); var q = si.value.trim();
			if (q.length < 2) { close(); return; }
			timer = setTimeout(function () {
				if (q === last) return; last = q;
				fetch(T.suggest + (T.suggest.indexOf('?') > -1 ? '&' : '?') + 'q=' + encodeURIComponent(q), { credentials: 'same-origin' })
					.then(function (r) { return r.ok ? r.json() : []; }).then(function (j) { if (si.value.trim() === q) paint(Array.isArray(j) ? j : []); }).catch(close);
			}, 220);
		});
		si.addEventListener('keydown', function (e) {
			var opts = list.querySelectorAll('li'); if (list.hidden || !opts.length) return;
			if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
				e.preventDefault(); active = (active + (e.key === 'ArrowDown' ? 1 : -1) + opts.length) % opts.length;
				opts.forEach(function (o, i) { o.classList.toggle('is-on', i === active); }); si.setAttribute('aria-activedescendant', opts[active].id);
			} else if (e.key === 'Enter' && active > -1) { e.preventDefault(); location.href = opts[active].querySelector('a').href; }
			else if (e.key === 'Escape') { close(); }
		});
		document.addEventListener('click', function (e) { if (!sf.contains(e.target)) close(); });
	}
})();
