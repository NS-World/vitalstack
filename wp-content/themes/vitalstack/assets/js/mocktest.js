/**
 * Mock test: a timed test of random questions from one learning path.
 *
 * Reads its data from <script id="mock-data"> (see page-mock-test.php):
 * { path, name, count, minutes, questions: [{q,o,a,e,l}], lessons: {slug: {t,u}}, url, title }
 * Awards XP through window.VS (fun.js) and keeps the best score in this browser.
 */
(function () {
	'use strict';

	var root = document.getElementById('mock-test');
	var dataEl = document.getElementById('mock-data');
	if (!root || !dataEl) return;
	var data;
	try { data = JSON.parse(dataEl.textContent); } catch (e) { return; }
	if (!data.questions || !data.questions.length) return;

	var bestKey = 'vs-mock-best-' + data.path;
	var store = {
		get: function (k) { try { return JSON.parse(localStorage.getItem(k)); } catch (e) { return null; } },
		set: function (k, v) { try { localStorage.setItem(k, JSON.stringify(v)); } catch (e) {} }
	};

	function esc(s) {
		return String(s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; });
	}
	// Same rule as the PHP side: `code` becomes <code>, everything else is escaped.
	function fmt(s) { return esc(s).replace(/`([^`]+)`/g, '<code>$1</code>'); }
	function mmss(sec) { sec = Math.max(0, sec); return Math.floor(sec / 60) + ':' + ('0' + (sec % 60)).slice(-2); }
	function shuffle(list) {
		var a = list.slice();
		for (var i = a.length - 1; i > 0; i--) { var j = Math.floor(Math.random() * (i + 1)); var t = a[i]; a[i] = a[j]; a[j] = t; }
		return a;
	}

	var qs, answers, current, left, timer, startedAt;

	function showBest() {
		var best = store.get(bestKey);
		var el = root.querySelector('.mock-best');
		if (el && best) {
			el.hidden = false;
			el.textContent = 'Your best score: ' + best.score + '/' + best.of + ' (' + Math.round(best.score / best.of * 100) + '%)';
		}
	}

	function start() {
		qs = shuffle(data.questions).slice(0, data.count);
		answers = qs.map(function () { return -1; });
		current = 0;
		left = data.minutes * 60;
		startedAt = Date.now();
		root.classList.add('is-running');
		root.innerHTML =
			'<div class="mock-bar">' +
				'<span class="mock-count"></span>' +
				'<div class="mock-progress" aria-hidden="true"><span></span></div>' +
				'<span class="mock-timer" role="timer" aria-label="Time left"></span>' +
			'</div>' +
			'<div class="mock-q" aria-live="polite"></div>' +
			'<div class="mock-nav">' +
				'<button type="button" class="btn btn-ghost btn-sm" data-prev>❮ Previous</button>' +
				'<button type="button" class="btn btn-primary btn-sm" data-next>Next ❯</button>' +
				'<button type="button" class="btn btn-primary btn-sm" data-finish hidden>Finish test</button>' +
			'</div>' +
			'<div class="mock-dots" aria-label="Jump to question"></div>' +
			'<p class="mock-keys">Tip: press 1–4 to answer, ← → to move.</p>';
		var dots = root.querySelector('.mock-dots');
		qs.forEach(function (q, i) {
			var d = document.createElement('button');
			d.type = 'button'; d.className = 'mock-dot'; d.textContent = i + 1;
			d.setAttribute('aria-label', 'Question ' + (i + 1));
			d.addEventListener('click', function () { go(i); });
			dots.appendChild(d);
		});
		root.querySelector('[data-prev]').addEventListener('click', function () { go(current - 1); });
		root.querySelector('[data-next]').addEventListener('click', function () { go(current + 1); });
		root.querySelector('[data-finish]').addEventListener('click', confirmFinish);
		document.addEventListener('keydown', onKey);
		tick();
		timer = setInterval(tick, 1000);
		render();
		root.scrollIntoView({ behavior: 'smooth', block: 'start' });
	}

	function tick() {
		var el = root.querySelector('.mock-timer');
		if (el) {
			el.textContent = '⏱ ' + mmss(left);
			el.classList.toggle('is-low', left <= 60);
		}
		if (left <= 0) { finish(true); return; }
		left--;
	}

	function render() {
		var q = qs[current];
		root.querySelector('.mock-count').textContent = 'Question ' + (current + 1) + ' of ' + qs.length;
		var done = answers.filter(function (a) { return a !== -1; }).length;
		root.querySelector('.mock-progress span').style.width = Math.round(done / qs.length * 100) + '%';
		var html = '<p class="mock-q-text">' + fmt(q.q) + '</p><div class="quiz-options">';
		q.o.forEach(function (opt, i) {
			html += '<button type="button" class="quiz-opt mock-opt' + (answers[current] === i ? ' is-chosen' : '') + '" data-i="' + i + '" aria-pressed="' + (answers[current] === i) + '">' +
				'<span class="quiz-letter">' + String.fromCharCode(65 + i) + '</span><span>' + fmt(opt) + '</span></button>';
		});
		html += '</div>';
		var box = root.querySelector('.mock-q');
		box.innerHTML = html;
		box.querySelectorAll('.mock-opt').forEach(function (b) {
			b.addEventListener('click', function () { choose(parseInt(b.getAttribute('data-i'), 10)); });
		});
		root.querySelector('[data-prev]').disabled = current === 0;
		var last = current === qs.length - 1;
		root.querySelector('[data-next]').hidden = last;
		root.querySelector('[data-finish]').hidden = !last && done < qs.length;
		root.querySelectorAll('.mock-dot').forEach(function (d, i) {
			d.classList.toggle('is-answered', answers[i] !== -1);
			d.classList.toggle('is-current', i === current);
		});
	}

	function choose(i) {
		answers[current] = i;
		render();
		// Move on by itself after a short pause, like a real test app.
		if (current < qs.length - 1) setTimeout(function () { if (answers[current] === i) go(current + 1); }, 350);
	}

	function go(i) {
		if (i < 0 || i >= qs.length) return;
		current = i;
		render();
	}

	function onKey(e) {
		if (!root.classList.contains('is-running') || e.altKey || e.ctrlKey || e.metaKey) return;
		var t = e.target && e.target.tagName;
		if (t === 'INPUT' || t === 'TEXTAREA') return;
		var n = parseInt(e.key, 10);
		if (n >= 1 && n <= qs[current].o.length) { choose(n - 1); e.preventDefault(); }
		else if (e.key === 'ArrowRight') go(current + 1);
		else if (e.key === 'ArrowLeft') go(current - 1);
	}

	function confirmFinish() {
		var open = answers.filter(function (a) { return a === -1; }).length;
		if (open && !window.confirm(open + ' question' + (open > 1 ? 's are' : ' is') + ' not answered yet. Finish anyway?')) return;
		finish(false);
	}

	function finish(timeUp) {
		clearInterval(timer);
		document.removeEventListener('keydown', onKey);
		root.classList.remove('is-running');
		var correct = 0, wrong = {};
		qs.forEach(function (q, i) {
			if (answers[i] === q.a) correct++;
			else wrong[q.l] = (wrong[q.l] || 0) + 1;
		});
		var total = qs.length, pct = Math.round(correct / total * 100);
		var secs = Math.min(data.minutes * 60, Math.round((Date.now() - startedAt) / 1000));
		var best = store.get(bestKey);
		var isBest = !best || correct > best.score;
		if (isBest) store.set(bestKey, { score: correct, of: total });

		var verdict = pct >= 90 ? 'Outstanding! You are interview-ready on this topic.'
			: pct >= 70 ? 'Great work! Revise the topics below to reach 90%.'
			: pct >= 50 ? 'Good start. Revise the topics below and try again.'
			: 'Keep going: work through the lessons below, then retake the test.';

		var html = '<div class="mock-result">' +
			'<div class="mock-ring" style="--pct:' + pct + '"><b>' + pct + '%</b></div>' +
			'<div class="mock-result-text">' +
				(timeUp ? '<p class="mock-timeup">⏰ Time is up!</p>' : '') +
				'<h2>' + correct + ' / ' + total + ' correct</h2>' +
				'<p class="mock-verdict">' + esc(verdict) + '</p>' +
				'<p class="mock-stats">Time: ' + mmss(secs) + (isBest && best ? ' · 🏅 New best score!' : '') + '<span class="mock-xp"></span></p>' +
			'</div></div>';

		var slugs = Object.keys(wrong).sort(function (a, b) { return wrong[b] - wrong[a]; });
		if (slugs.length) {
			html += '<div class="mock-revise"><h3>Revise these lessons</h3><ul>';
			slugs.forEach(function (s) {
				var l = data.lessons[s];
				var label = l ? l.t : s.replace(/-/g, ' ');
				html += '<li>' + (l ? '<a href="' + esc(l.u) + '">' + esc(label) + '</a>' : esc(label)) + ' <span class="muted">· ' + wrong[s] + ' wrong</span></li>';
			});
			html += '</ul></div>';
		}

		var shareText = 'I scored ' + correct + '/' + total + ' in the ' + data.title + ' on VitalStack. Can you beat me?';
		var u = encodeURIComponent(data.url), t = encodeURIComponent(shareText);
		html += '<div class="mock-actions">' +
			'<button type="button" class="btn btn-primary" data-retake>Retake with new questions</button>' +
			'<a class="btn btn-ghost" target="_blank" rel="noopener" href="https://wa.me/?text=' + t + '%20' + u + '">Share on WhatsApp</a>' +
			'<a class="btn btn-ghost" target="_blank" rel="noopener" href="https://www.linkedin.com/sharing/share-offsite/?url=' + u + '">LinkedIn</a>' +
			'<a class="btn btn-ghost" target="_blank" rel="noopener" href="https://twitter.com/intent/tweet?text=' + t + '&url=' + u + '">X</a>' +
			'</div>';

		html += '<details class="mock-review" open><summary>Review all answers</summary><ol>';
		qs.forEach(function (q, i) {
			var mine = answers[i], ok = mine === q.a;
			html += '<li class="' + (ok ? 'is-right' : 'is-wrong') + '"><p class="mock-q-text">' + fmt(q.q) + '</p>' +
				'<p>' + (ok ? '✅' : '❌') + ' Your answer: ' + (mine === -1 ? '<i>not answered</i>' : '<b>' + String.fromCharCode(65 + mine) + '.</b> ' + fmt(q.o[mine])) + '</p>' +
				(ok ? '' : '<p>✔️ Correct: <b>' + String.fromCharCode(65 + q.a) + '.</b> ' + fmt(q.o[q.a]) + '</p>') +
				(q.e ? '<p class="mock-explain">' + fmt(q.e) + '</p>' : '') + '</li>';
		});
		html += '</ol></details>';

		root.innerHTML = html;
		root.querySelector('[data-retake]').addEventListener('click', start);
		root.scrollIntoView({ behavior: 'smooth', block: 'start' });

		if (window.VS && window.VS.addXP) {
			window.VS.addXP('mock', data.path, correct).then(function (res) {
				var xpEl = root.querySelector('.mock-xp');
				if (res && res.gained && xpEl) xpEl.textContent = ' · +' + res.gained + ' XP';
				if (pct >= 80 && window.VS.celebrate) window.VS.celebrate(pct === 100 ? 'Perfect score!' : 'Great score!', correct + ' of ' + total + ' in the ' + data.name + ' test.', 'cheer', res ? res.gained : 0);
			});
		}
	}

	function wireStart() {
		var btn = root.querySelector('[data-mock-start]');
		if (btn) btn.addEventListener('click', start);
		showBest();
	}
	wireStart();
})();
