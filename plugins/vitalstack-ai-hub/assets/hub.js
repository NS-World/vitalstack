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
	if (root && list) {
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

	/* Live previews load only when visible (keeps the page fast). */
	var loadFrame = function (f) { if (f.hasAttribute('data-srcdoc')) { f.setAttribute('srcdoc', f.getAttribute('data-srcdoc')); f.removeAttribute('data-srcdoc'); } };
	if ('IntersectionObserver' in window) {
		var io = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { loadFrame(e.target); io.unobserve(e.target); } }); }, { rootMargin: '200px' });
		document.querySelectorAll('.vsai-frame iframe[data-srcdoc]').forEach(function (f) { io.observe(f); });
	} else {
		document.querySelectorAll('.vsai-frame iframe[data-srcdoc]').forEach(loadFrame);
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

	/* Dialog: fill it from the item's <template>. */
	var dlg = document.querySelector('.vsai-dialog'), body = dlg && dlg.querySelector('.vsai-dialog-body'), opener = null;
	var closeDlg = function () { if (dlg.open) dlg.close(); };
	document.addEventListener('click', function (e) {
		var o = e.target.closest('[data-open]');
		if (!o || !dlg || !body) return;
		var tpl = document.querySelector(o.getAttribute('data-open'));
		if (!tpl) return;
		opener = o; body.innerHTML = ''; body.appendChild(tpl.content.cloneNode(true));
		body.querySelectorAll('iframe[data-srcdoc]').forEach(loadFrame);
		if (typeof dlg.showModal === 'function') dlg.showModal(); else dlg.setAttribute('open', '');
		var x = dlg.querySelector('[data-close]'); if (x) x.focus();
	});
	if (dlg) {
		dlg.addEventListener('click', function (e) { if (e.target === dlg || e.target.closest('[data-close]')) closeDlg(); });
		dlg.addEventListener('close', function () { body.innerHTML = ''; if (opener) opener.focus(); });
	}
})();
