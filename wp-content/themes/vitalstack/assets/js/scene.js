/**
 * Lesson films: plays a ::scene block like a short animated movie.
 *
 * Markup (made by tools/lessons_to_wxr.py):
 *   <div class="vs-scene" data-title>
 *     <ol class="vs-scene-shots"><li data-icon data-chapter data-crew>subtitle</li></ol>
 *   </div>
 * Without JavaScript the shots stay a readable numbered list.
 *
 * Each shot: a big set piece that slowly zooms (Ken Burns), letterbox bars,
 * an optional chapter card, a typed subtitle and a crew character.
 *
 * @package VitalStack
 */
(function () {
	'use strict';

	var still = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var SKIES = [
		['#0f2027', '#2c5364'], ['#1a1a40', '#4a2c7a'], ['#0b3d2e', '#1f7a55'], ['#3a1c32', '#9b3d5a'],
		['#102a43', '#2f6f9f'], ['#2b2118', '#8a5a2b'], ['#14213d', '#3f5aa8'], ['#1b2a1b', '#557a3d']
	];
	var MOODS = ['think', 'happy', 'wow', 'cheer'];
	function el(tag, cls, text) { var e = document.createElement(tag); if (cls) e.className = cls; if (text !== undefined) e.textContent = text; return e; }
	function mascot(mood, who) { return window.VS && window.VS.mascot ? window.VS.mascot(mood, who) : ''; }

	function build(host, index) {
		var items = host.querySelectorAll('.vs-scene-shots > li');
		if (!items.length) return;
		var shots = Array.prototype.map.call(items, function (li) {
			return { icon: li.getAttribute('data-icon') || '🎬', chapter: li.getAttribute('data-chapter') || '', crew: li.getAttribute('data-crew') || '', html: li.innerHTML, text: li.textContent };
		});
		var title = host.getAttribute('data-title') || 'Lesson film';
		host.textContent = '';
		host.classList.add('is-ready');
		host.setAttribute('role', 'region');
		host.setAttribute('aria-label', 'Animated film: ' + title);

		var screen = el('div', 'sc-screen');
		var sky = el('div', 'sc-sky'), set = el('div', 'sc-set'), icon = el('span', 'sc-icon');
		set.appendChild(icon);
		var actor = el('div', 'sc-actor'), card = el('div', 'sc-card'), sub = el('p', 'sc-sub');
		sub.setAttribute('aria-live', 'polite');
		var bars = [el('i', 'sc-bar sc-bar-top'), el('i', 'sc-bar sc-bar-bottom')];
		[sky, el('div', 'sc-stars'), set, actor, card, el('div', 'sc-vignette'), bars[0], bars[1], sub].forEach(function (x) { screen.appendChild(x); });
		var poster = el('button', 'sc-poster');
		poster.type = 'button';
		poster.innerHTML = '<span class="sc-poster-play" aria-hidden="true">▶</span><span class="sc-poster-kicker">VitalStack Films</span><strong></strong><span class="sc-poster-len"></span>';
		poster.querySelector('strong').textContent = title;
		poster.querySelector('.sc-poster-len').textContent = shots.length + ' scenes · about ' + Math.max(1, Math.round(shots.length * 5 / 60)) + ' min';
		screen.appendChild(poster);
		host.appendChild(screen);

		var ctr = el('div', 'sc-controls');
		function btn(label, aria, cls) { var b = el('button', 'sc-btn ' + (cls || ''), label); b.type = 'button'; b.setAttribute('aria-label', aria); ctr.appendChild(b); return b; }
		var bBack = btn('◀', 'Previous scene'), bPlay = btn('▶ Play', 'Play', 'sc-play'), bNext = btn('▶|', 'Next scene');
		var track = el('div', 'sc-track');
		var segs = shots.map(function (_, k) { var s = el('button', 'sc-seg'); s.type = 'button'; s.setAttribute('aria-label', 'Scene ' + (k + 1)); s.appendChild(el('i')); s.addEventListener('click', function () { show(k, !!timer); }); track.appendChild(s); return s; });
		ctr.appendChild(track);
		var count = el('span', 'sc-count'); ctr.appendChild(count);
		host.appendChild(ctr);

		var at = -1, timer = null, typer = null, finished = false;
		function dur(s) { return Math.min(9000, 3200 + s.text.length * 45); }
		function type(s) {
			clearInterval(typer);
			if (still) { sub.innerHTML = s.html; return; }
			var n = 0, full = s.text;
			sub.textContent = '';
			typer = setInterval(function () {
				n += 2; sub.textContent = full.slice(0, n);
				if (n >= full.length) { clearInterval(typer); sub.innerHTML = s.html; }
			}, 28);
		}
		function show(k, keepPlaying) {
			at = Math.max(0, Math.min(shots.length - 1, k));
			var s = shots[at], c = SKIES[at % SKIES.length];
			poster.hidden = true; screen.classList.add('is-rolling');
			sky.style.background = 'radial-gradient(120% 90% at 50% 100%, ' + c[1] + ', ' + c[0] + ')';
			icon.textContent = s.icon;
			// Alternate the camera move so consecutive shots don't feel the same.
			set.className = 'sc-set sc-move-' + (at % 4);
			void set.offsetWidth; set.classList.add('is-on');
			actor.innerHTML = s.crew ? mascot(at === shots.length - 1 ? 'cheer' : MOODS[at % MOODS.length], s.crew) : '';
			actor.className = 'sc-actor' + (s.crew ? ' is-on sc-from-' + (at % 2 ? 'right' : 'left') : '');
			card.textContent = s.chapter;
			card.className = 'sc-card' + (s.chapter ? ' is-on' : '');
			type(s);
			segs.forEach(function (g, j) { g.classList.toggle('is-done', j < at); g.classList.toggle('is-now', j === at); g.style.setProperty('--d', dur(s) + 'ms'); });
			count.textContent = (at + 1) + ' / ' + shots.length;
			bBack.disabled = at <= 0; bNext.disabled = at >= shots.length - 1;
			clearTimeout(timer); timer = null;
			if (keepPlaying) { host.classList.add('is-playing'); timer = setTimeout(next, dur(s)); }
			if (at === shots.length - 1 && !finished) {
				finished = true;
				if (window.VS && window.VS.addXP) window.VS.addXP('viz', 'scene-' + location.pathname + '-' + index);
			}
		}
		function next() {
			if (at >= shots.length - 1) { stop(); screen.classList.add('is-ended'); bPlay.textContent = '↺ Replay'; return; }
			show(at + 1, true);
		}
		function play() {
			screen.classList.remove('is-ended');
			bPlay.textContent = '❚❚ Pause'; bPlay.setAttribute('aria-label', 'Pause');
			show(at < 0 || at >= shots.length - 1 ? 0 : at + 1, true);
		}
		function stop() {
			clearTimeout(timer); timer = null; host.classList.remove('is-playing');
			bPlay.textContent = '▶ Play'; bPlay.setAttribute('aria-label', 'Play');
		}
		poster.addEventListener('click', play);
		bPlay.addEventListener('click', function () { if (timer) stop(); else play(); });
		bNext.addEventListener('click', function () { var p = !!timer; show(at + 1, p); });
		bBack.addEventListener('click', function () { var p = !!timer; show(at - 1, p); });
		host.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowRight') { e.preventDefault(); show(at + 1, !!timer); }
			else if (e.key === 'ArrowLeft') { e.preventDefault(); show(at - 1, !!timer); }
		});
		host.tabIndex = 0;
		// Pause when the film scrolls out of view.
		if ('IntersectionObserver' in window) {
			new IntersectionObserver(function (en) { if (!en[0].isIntersecting && timer) stop(); }, { threshold: 0.2 }).observe(host);
		}
		count.textContent = shots.length + ' scenes';
	}

	function start() { document.querySelectorAll('.vs-scene:not(.is-ready)').forEach(build); }
	window.VSScene = start; // for premium lessons loaded after the page
	if (document.readyState === 'complete') start(); else document.addEventListener('DOMContentLoaded', start);
})();
