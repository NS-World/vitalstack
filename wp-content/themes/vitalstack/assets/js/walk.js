/**
 * Code walkthrough: plays a code example one line at a time.
 *
 * Markup (made by tools/lessons_to_wxr.py from a ::walk block):
 *   <div class="vs-walk" data-lang data-title>
 *     <pre class="vs-walk-src"><code>…code…</code></pre>
 *     <ol class="vs-walk-steps"><li data-line="3" data-deep data-vars data-out>…</li></ol>
 *   </div>
 * Without JavaScript the code and the numbered notes are still readable.
 *
 * For each step the line types itself in, a pointer moves to it, a crew
 * character explains it (Simple or Deep dive), the variables panel shows
 * what changed and printed text appears in the output console.
 *
 * @package VitalStack
 */
(function () {
	'use strict';

	var still = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	function el(tag, cls, text) { var e = document.createElement(tag); if (cls) e.className = cls; if (text !== undefined) e.textContent = text; return e; }
	function mascot(mood) { return window.VS && window.VS.mascot ? window.VS.mascot(mood) : ''; }
	var SPEEDS = { slow: 5200, normal: 3600, fast: 2200 };

	function build(host, index) {
		var codeEl = host.querySelector('.vs-walk-src code');
		var items = host.querySelectorAll('.vs-walk-steps > li');
		if (!codeEl || !items.length) return;
		var lines = codeEl.textContent.replace(/\n$/, '').split('\n');
		var steps = Array.prototype.map.call(items, function (li) {
			var label = li.querySelector('b'); if (label) label.remove();
			var vars = {}; try { vars = JSON.parse(li.getAttribute('data-vars') || '{}'); } catch (e) {}
			return { line: +li.getAttribute('data-line') - 1, html: li.innerHTML.trim(), deep: li.getAttribute('data-deep') || '', vars: vars, out: li.getAttribute('data-out') || '' };
		});
		var lang = host.getAttribute('data-lang') || 'code';
		var title = host.getAttribute('data-title') || 'Line by line';
		var hasDeep = steps.some(function (s) { return s.deep; });
		var hasVars = steps.some(function (s) { return Object.keys(s.vars).length; });
		var hasOut = steps.some(function (s) { return s.out; });

		host.textContent = '';
		host.classList.add('is-ready');
		host.setAttribute('role', 'region');
		host.setAttribute('aria-label', 'Code walkthrough: ' + title);
		host.tabIndex = 0;

		var head = el('div', 'wk-head');
		head.appendChild(el('span', 'wk-badge', '▶ Line by line'));
		head.appendChild(el('span', 'wk-title', title));
		var depth = null;
		if (hasDeep) {
			depth = el('div', 'wk-depth'); depth.setAttribute('role', 'group'); depth.setAttribute('aria-label', 'Explanation depth');
			['Simple', 'Deep dive'].forEach(function (lab, k) { var b = el('button', '', lab); b.type = 'button'; b.setAttribute('aria-pressed', k ? 'false' : 'true'); depth.appendChild(b); });
			head.appendChild(depth);
		}
		host.appendChild(head);

		var body = el('div', 'wk-body');
		var codePane = el('div', 'wk-code'); codePane.setAttribute('aria-hidden', 'true');
		var rows = lines.map(function (txt, n) {
			var row = el('div', 'wk-line');
			row.appendChild(el('span', 'wk-num', String(n + 1)));
			var t = el('code', 'wk-text language-' + lang); t.textContent = txt || ' ';
			row.appendChild(t);
			row.addEventListener('click', function () { var k = firstStepFor(n); if (k > -1) { stop(); go(k); } });
			codePane.appendChild(row);
			return row;
		});
		var pointer = el('span', 'wk-pointer', '▶'); codePane.appendChild(pointer);
		if (window.hljs) rows.forEach(function (r) { try { window.hljs.highlightElement(r.querySelector('.wk-text')); } catch (e) {} });
		body.appendChild(codePane);

		var side = el('div', 'wk-side');
		var bubble = el('div', 'wk-bubble'); bubble.setAttribute('aria-live', 'polite');
		var bot = el('div', 'wk-bot'); bot.innerHTML = mascot('think');
		var say = el('div', 'wk-say');
		bubble.appendChild(bot); bubble.appendChild(say); side.appendChild(bubble);
		var varsBox = null, outBox = null;
		if (hasVars) { var vp = el('div', 'wk-panel'); vp.appendChild(el('p', 'wk-panel-title', 'Variables')); varsBox = el('div', 'wk-vars'); vp.appendChild(varsBox); side.appendChild(vp); }
		if (hasOut) { var op = el('div', 'wk-panel wk-console'); op.appendChild(el('p', 'wk-panel-title', 'Output')); outBox = el('pre', 'wk-out'); op.appendChild(outBox); side.appendChild(op); }
		body.appendChild(side);
		host.appendChild(body);

		var ctr = el('div', 'wk-controls');
		function btn(label, aria, cls) { var b = el('button', 'wk-btn ' + (cls || ''), label); b.type = 'button'; b.setAttribute('aria-label', aria); ctr.appendChild(b); return b; }
		var bReset = btn('⏮', 'Start again'), bBack = btn('◀', 'Previous line'), bPlay = btn('▶ Play', 'Play', 'wk-play'), bNext = btn('▶|', 'Next line');
		var speed = el('select', 'wk-speed'); speed.setAttribute('aria-label', 'Speed');
		[['slow', '🐢 Slow'], ['normal', '🚶 Normal'], ['fast', '🚀 Fast']].forEach(function (o) { var op = el('option', '', o[1]); op.value = o[0]; if (o[0] === 'normal') op.selected = true; speed.appendChild(op); });
		ctr.appendChild(speed);
		var count = el('span', 'wk-count'); ctr.appendChild(count);
		host.appendChild(ctr);
		var dots = el('div', 'wk-dots');
		steps.forEach(function (_, k) { var d = el('button', 'wk-dot'); d.type = 'button'; d.setAttribute('aria-label', 'Go to step ' + (k + 1)); d.addEventListener('click', function () { stop(); go(k); }); dots.appendChild(d); });
		host.appendChild(dots);

		var at = -1, timer = null, deep = false, finished = false;
		function firstStepFor(n) { for (var k = 0; k < steps.length; k++) if (steps[k].line === n) return k; return -1; }

		function typeLine(row) {
			var t = row.querySelector('.wk-text');
			if (still || t.getAttribute('data-typed')) return;
			t.setAttribute('data-typed', '1');
			var full = t.innerHTML, text = t.textContent, n = 0;
			// Reveal the line character by character, then restore the highlighted markup.
			t.textContent = '';
			var iv = setInterval(function () {
				n += Math.max(1, Math.ceil(text.length / 24));
				t.textContent = text.slice(0, n);
				if (n >= text.length) { clearInterval(iv); t.innerHTML = full; }
			}, 22);
		}

		function render() {
			var s = steps[at];
			var reach = Math.max.apply(null, steps.slice(0, at + 1).map(function (x) { return x.line; }));
			rows.forEach(function (r, n) {
				var seen = n <= reach;
				r.classList.toggle('is-now', n === s.line);
				r.classList.toggle('is-done', seen && n !== s.line);
				r.classList.toggle('is-later', !seen && n !== s.line);
			});
			typeLine(rows[s.line]);
			pointer.style.transform = 'translateY(' + rows[s.line].offsetTop + 'px)';
			pointer.classList.add('is-on');
			// Keep the current line visible inside the code box.
			var top = rows[s.line].offsetTop, h = codePane.clientHeight;
			if (top < codePane.scrollTop || top > codePane.scrollTop + h - 40) codePane.scrollTop = Math.max(0, top - h / 3);

			say.innerHTML = '<p class="wk-line-label">Line ' + (s.line + 1) + '</p><p class="wk-text-say"></p>';
			var txt = say.querySelector('.wk-text-say');
			if (deep && s.deep) txt.textContent = s.deep; else txt.innerHTML = s.html;
			bubble.classList.remove('is-pop'); void bubble.offsetWidth; bubble.classList.add('is-pop');
			bot.innerHTML = mascot(at === steps.length - 1 ? 'cheer' : (s.out ? 'wow' : 'think'));

			if (varsBox) {
				var state = {}, changed = {};
				steps.slice(0, at + 1).forEach(function (x, k) { Object.keys(x.vars).forEach(function (v) { if (k === at && state[v] !== x.vars[v]) changed[v] = 1; state[v] = x.vars[v]; }); });
				varsBox.textContent = '';
				var names = Object.keys(state);
				if (!names.length) varsBox.appendChild(el('span', 'wk-empty', 'Nothing stored yet'));
				names.forEach(function (v) {
					var c = el('span', 'wk-var' + (changed[v] ? ' is-changed' : ''));
					c.appendChild(el('b', '', v)); c.appendChild(el('span', '', state[v]));
					varsBox.appendChild(c);
				});
			}
			if (outBox) {
				outBox.textContent = steps.slice(0, at + 1).filter(function (x) { return x.out; }).map(function (x) { return x.out; }).join('\n');
				if (s.out) { outBox.classList.remove('is-new'); void outBox.offsetWidth; outBox.classList.add('is-new'); }
			}
			count.textContent = 'Step ' + (at + 1) + ' / ' + steps.length;
			Array.prototype.forEach.call(dots.children, function (d, k) { d.classList.toggle('is-on', k === at); d.classList.toggle('is-done', k < at); });
			bBack.disabled = at <= 0; bNext.disabled = at >= steps.length - 1;
			if (at === steps.length - 1 && !finished) {
				finished = true;
				host.classList.add('is-finished');
				if (window.VS && window.VS.addXP) window.VS.addXP('viz', 'walk-' + location.pathname + '-' + index);
			}
		}
		function go(k) { at = Math.max(0, Math.min(steps.length - 1, k)); render(); }
		function tick() {
			if (at >= steps.length - 1) { stop(); return; }
			go(at + 1);
			timer = setTimeout(tick, SPEEDS[speed.value] + (steps[at].out ? 800 : 0));
		}
		function play() {
			if (at >= steps.length - 1) { rows.forEach(function (r) { r.querySelector('.wk-text').removeAttribute('data-typed'); }); at = -1; }
			bPlay.textContent = '❚❚ Pause'; bPlay.setAttribute('aria-label', 'Pause'); host.classList.add('is-playing');
			clearTimeout(timer);
			if (at < 0) { go(0); timer = setTimeout(tick, SPEEDS[speed.value]); } else tick();
		}
		function stop() { clearTimeout(timer); timer = null; bPlay.textContent = '▶ Play'; bPlay.setAttribute('aria-label', 'Play'); host.classList.remove('is-playing'); }

		bPlay.addEventListener('click', function () { if (timer) stop(); else play(); });
		bNext.addEventListener('click', function () { stop(); go(at + 1); });
		bBack.addEventListener('click', function () { stop(); go(at - 1); });
		bReset.addEventListener('click', function () { stop(); rows.forEach(function (r) { r.querySelector('.wk-text').removeAttribute('data-typed'); }); go(0); });
		host.addEventListener('keydown', function (e) {
			if (e.target.tagName === 'SELECT') return;
			if (e.key === 'ArrowRight') { e.preventDefault(); stop(); go(at + 1); }
			else if (e.key === 'ArrowLeft') { e.preventDefault(); stop(); go(at - 1); }
			else if (e.key === ' ' && e.target === host) { e.preventDefault(); if (timer) stop(); else play(); }
		});
		if (depth) {
			depth.addEventListener('click', function (e) {
				if (e.target.tagName !== 'BUTTON') return;
				deep = e.target.textContent === 'Deep dive';
				Array.prototype.forEach.call(depth.children, function (b) { b.setAttribute('aria-pressed', b === e.target ? 'true' : 'false'); });
				render();
			});
		}
		go(0);
	}

	// After every deferred script (syntax highlighting, mascots) has run.
	function start() { document.querySelectorAll('.vs-walk:not(.is-ready)').forEach(build); }
	window.VSWalk = start; // for premium lessons loaded after the page
	if (document.readyState === 'complete') start(); else document.addEventListener('DOMContentLoaded', start);
})();
