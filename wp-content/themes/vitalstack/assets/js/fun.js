/**
 * VitalStack fun layer: XP and streaks, celebrations with Stacky the robot,
 * the header XP chip and the homepage hero card.
 *
 * Exposes window.VS for viz.js and games.js:
 *   VS.addXP(type, ref, score) → Promise<{ gained, stats }>
 *   VS.celebrate(title, text, mood, xp)
 *   VS.mascot(mood) → SVG markup
 */
(function () {
	'use strict';

	var cfg = window.vitalstackFun || {};
	var LESSON_XP = 10, VIZ_XP = 2, GAME_DAY_CAP = 60, QUIZ_XP = 2, DAILY_XP = 15;

	var store = {
		get: function (k, f) { try { var v = localStorage.getItem(k); return v === null ? f : JSON.parse(v); } catch (e) { return f; } },
		set: function (k, v) { try { localStorage.setItem(k, JSON.stringify(v)); } catch (e) {} }
	};

	/* ── Dates (local calendar) ───────────────────────────────────────────── */
	function ymd(d) {
		return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
	}
	function today() { return ymd(new Date()); }
	function yesterday() { var d = new Date(); d.setDate(d.getDate() - 1); return ymd(d); }
	function weekKey() {
		var d = new Date(); d.setHours(0, 0, 0, 0);
		d.setDate(d.getDate() + 3 - (d.getDay() + 6) % 7);
		var w1 = new Date(d.getFullYear(), 0, 4);
		var w = 1 + Math.round(((d - w1) / 864e5 - 3 + (w1.getDay() + 6) % 7) / 7);
		return d.getFullYear() + '-' + String(w).padStart(2, '0');
	}

	function levelOf(xp) {
		var level = Math.floor(Math.sqrt(Math.max(0, xp) / 50)) + 1;
		var from = 50 * (level - 1) * (level - 1), to = 50 * level * level;
		return { level: level, progress: (xp - from) / (to - from), next: to };
	}

	/* ── Guest XP (this browser only) ─────────────────────────────────────── */
	function guest() {
		var g = store.get('vs-xp', null) || { xp: 0, week: 0, weekKey: weekKey(), streak: 0, best: 0, last: '', refs: {}, gamesDay: '', gamesXp: 0 };
		if (g.weekKey !== weekKey()) { g.week = 0; g.weekKey = weekKey(); }
		if (g.last && g.last !== today() && g.last !== yesterday()) g.streak = 0;
		return g;
	}
	function guestStats(g) {
		var l = levelOf(g.xp);
		return { xp: g.xp, week: g.week, streak: g.streak, best: g.best, level: l.level, levelProgress: l.progress, nextLevel: l.next };
	}
	function guestAward(amount, ref) {
		var g = guest();
		if (ref) {
			if (g.refs[ref]) return { gained: 0, stats: guestStats(g) };
			g.refs[ref] = 1;
		}
		g.xp += amount; g.week += amount;
		if (g.last !== today()) {
			g.streak = g.last === yesterday() ? g.streak + 1 : 1;
			g.best = Math.max(g.best, g.streak);
			g.last = today();
		}
		store.set('vs-xp', g);
		return { gained: amount, stats: guestStats(g) };
	}

	var stats = cfg.loggedIn && cfg.xp ? cfg.xp : guestStats(guest());

	/* ── Painting stats everywhere ────────────────────────────────────────── */
	function countUp(el, to) {
		var from = parseInt(el.textContent, 10) || 0;
		if (from === to || !window.requestAnimationFrame) { el.textContent = to; return; }
		var t0 = null, dur = 700;
		function step(t) {
			t0 = t0 || t;
			var p = Math.min(1, (t - t0) / dur);
			el.textContent = Math.round(from + (to - from) * (1 - Math.pow(1 - p, 3)));
			if (p < 1) requestAnimationFrame(step);
		}
		requestAnimationFrame(step);
	}
	function paint(s) {
		stats = s;
		document.querySelectorAll('[data-xp]').forEach(function (el) {
			var k = el.getAttribute('data-xp');
			if (k === 'levelBar') { el.style.width = Math.round((s.levelProgress || 0) * 100) + '%'; return; }
			if (s[k] === undefined) return;
			if (el.hasAttribute('data-count')) countUp(el, s[k]); else el.textContent = s[k];
		});
		document.querySelectorAll('[data-xp-chip]').forEach(function (c) {
			if (s.xp > 0 || cfg.loggedIn) c.hidden = false;
		});
	}

	/* ── Mascot and celebrations ──────────────────────────────────────────── */
	function mascot(mood) { return (cfg.mascot && (cfg.mascot[mood] || cfg.mascot.happy)) || ''; }

	function confetti(host) {
		var colours = ['#34c987', '#f5b301', '#4f8cff', '#ff6b8b', '#a970ff'];
		for (var i = 0; i < 26; i++) {
			var c = document.createElement('i');
			c.className = 'confetti';
			c.style.setProperty('--x', (Math.random() * 260 - 130) + 'px');
			c.style.setProperty('--r', (Math.random() * 720 - 360) + 'deg');
			c.style.background = colours[i % colours.length];
			c.style.left = (40 + Math.random() * 20) + '%';
			c.style.animationDelay = (Math.random() * 0.15) + 's';
			host.appendChild(c);
		}
	}

	var popTimer;
	function celebrate(title, text, mood, xp) {
		var old = document.querySelector('.celebrate');
		if (old) old.remove();
		var box = document.createElement('div');
		box.className = 'celebrate';
		box.setAttribute('role', 'status');
		box.innerHTML = '<button type="button" class="celebrate-close" aria-label="Close">×</button>' +
			'<div class="celebrate-mascot">' + mascot(mood || 'cheer') + '</div>' +
			'<div class="celebrate-text"><strong></strong><span></span>' + (xp ? '<em class="celebrate-xp">+' + xp + ' XP</em>' : '') + '</div>';
		box.querySelector('strong').textContent = title;
		box.querySelector('span').textContent = text || '';
		if (!cfg.loggedIn && cfg.accountUrl) {
			var a = document.createElement('a');
			a.href = cfg.accountUrl; a.className = 'celebrate-link'; a.textContent = 'Sign up to save your XP ❯';
			box.querySelector('.celebrate-text').appendChild(a);
		}
		document.body.appendChild(box);
		if (!window.matchMedia || !window.matchMedia('(prefers-reduced-motion: reduce)').matches) confetti(box);
		requestAnimationFrame(function () { box.classList.add('is-visible'); });
		box.querySelector('.celebrate-close').addEventListener('click', function () { box.remove(); });
		clearTimeout(popTimer);
		popTimer = setTimeout(function () { box.classList.remove('is-visible'); setTimeout(function () { box.remove(); }, 400); }, 4200);
	}

	/* ── Awarding XP ─────────────────────────────────────────────────────── */
	function addXP(type, ref, score) {
		if (!cfg.loggedIn || !cfg.xpUrl || !window.fetch) {
			var amount = 0;
			if (type === 'viz') amount = VIZ_XP;
			else if (type === 'lesson') amount = LESSON_XP;
			else if (type === 'quiz') amount = Math.max(0, Math.min(10, score || 0)) * QUIZ_XP;
			else if (type === 'daily') amount = score > 0 ? DAILY_XP : 0;
			else if (type === 'mock') { amount = Math.max(0, Math.min(20, score || 0)) * QUIZ_XP; ref = ref + ':' + today(); }
			else if (type === 'game') {
				var g = guest();
				var used = g.gamesDay === today() ? g.gamesXp : 0;
				amount = Math.max(0, Math.min(5 + Math.round(Math.max(0, Math.min(100, score || 0)) * 0.15), GAME_DAY_CAP - used));
				g.gamesDay = today(); g.gamesXp = used + amount; store.set('vs-xp', g);
				ref = '';
			}
			var res = guestAward(amount, ref ? type + ':' + ref : '');
			paint(res.stats);
			return Promise.resolve(res);
		}
		return fetch(cfg.xpUrl, {
			method: 'POST', credentials: 'same-origin',
			headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': cfg.nonce },
			body: JSON.stringify({ type: type, ref: ref || '', score: score || 0 })
		}).then(function (r) { return r.json(); }).then(function (s) {
			paint(s);
			return { gained: s.gained || 0, stats: s };
		}).catch(function () { return { gained: 0, stats: stats }; });
	}

	window.VS = { addXP: addXP, celebrate: celebrate, mascot: mascot, stats: function () { return stats; } };

	/* ── Lesson completion (events from main.js) ─────────────────────────── */
	var praise = ['Lesson complete!', 'Nailed it!', 'Brain +1!', 'You are on fire!', 'Great job!'];
	document.addEventListener('vs:lesson-complete', function (e) {
		var id = e.detail && e.detail.id;
		var title = praise[Math.floor(Math.random() * praise.length)];
		if (cfg.loggedIn) {
			celebrate(title, 'Keep the streak going: next lesson is one click away.', 'cheer', LESSON_XP);
		} else {
			var res = guestAward(LESSON_XP, 'lesson:' + id);
			paint(res.stats);
			celebrate(title, res.stats.streak > 1 ? res.stats.streak + '-day streak! Come back tomorrow to keep it.' : 'Your streak has started. Come back tomorrow!', 'cheer', res.gained);
		}
	});
	function certPopup(title, text, href, label) {
		celebrate(title, text, 'cheer', 0);
		var box = document.querySelector('.celebrate .celebrate-text');
		if (!box) return;
		var old = box.querySelector('.celebrate-link'); if (old) old.remove();
		var a = document.createElement('a');
		a.href = href; a.className = 'celebrate-link'; a.textContent = label;
		box.appendChild(a);
		clearTimeout(popTimer);
	}
	document.addEventListener('vs:cert', function (e) {
		var c = e.detail || {};
		setTimeout(function () { certPopup('Course complete! 🎓', 'You finished every ' + (c.course || '') + ' lesson. Your certificate is ready (+100 XP).', c.url, 'View my certificate ❯'); }, 1200);
	});
	document.addEventListener('vs:path-complete', function (e) {
		if (cfg.loggedIn) return; // signed-in readers get vs:cert from the server
		var d = e.detail || {};
		setTimeout(function () { certPopup('You finished ' + (d.name || 'the course') + '! 🎓', 'Create a free account to get your certificate. Your progress on this device is kept.', (cfg.accountUrl || '/account/') + (String(cfg.accountUrl).indexOf('?') > -1 ? '&' : '?') + 'tab=register', 'Get my free certificate ❯'); }, 1200);
	});
	document.addEventListener('vs:xp', function (e) {
		if (e.detail && e.detail.xp) paint(e.detail.xp);
	});

	/* ── "Finished? Mark it complete" nudge near the end of a lesson ─────── */
	var complete = document.querySelector('[data-complete]');
	if (complete && 'IntersectionObserver' in window) {
		var nudged = false;
		var obs = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (!en.isIntersecting || nudged || complete.getAttribute('aria-pressed') === 'true') return;
				nudged = true;
				var tip = document.createElement('div');
				tip.className = 'mascot-nudge';
				tip.innerHTML = '<div class="mascot-nudge-bot">' + mascot('think') + '</div><p>Finished reading? Mark it complete for <b>+10 XP</b> 🎯</p><button type="button" class="mascot-nudge-close" aria-label="Close">×</button>';
				document.body.appendChild(tip);
				requestAnimationFrame(function () { tip.classList.add('is-visible'); });
				tip.querySelector('.mascot-nudge-close').addEventListener('click', function () { tip.remove(); });
				setTimeout(function () { tip.classList.remove('is-visible'); setTimeout(function () { tip.remove(); }, 400); }, 7000);
				complete.addEventListener('click', function () { tip.remove(); }, { once: true });
			});
		}, { threshold: 1 });
		obs.observe(complete);
	}

	/* ── Hero card slides ─────────────────────────────────────────────────── */
	document.querySelectorAll('[data-hero-card]').forEach(function (card) {
		var slides = card.querySelectorAll('.hero-slide');
		var dots = card.querySelectorAll('.hero-dots button');
		var i = 0, timer;
		function show(n) {
			i = (n + slides.length) % slides.length;
			slides.forEach(function (s, k) { s.classList.toggle('is-active', k === i); });
			dots.forEach(function (d, k) { d.classList.toggle('is-active', k === i); d.setAttribute('aria-selected', k === i ? 'true' : 'false'); });
		}
		function start() { clearInterval(timer); timer = setInterval(function () { show(i + 1); }, 6000); }
		dots.forEach(function (d, k) { d.addEventListener('click', function () { show(k); start(); }); });
		card.addEventListener('mouseenter', function () { clearInterval(timer); });
		card.addEventListener('mouseleave', start);
		if (!window.matchMedia || !window.matchMedia('(prefers-reduced-motion: reduce)').matches) start();
	});

	/* ── Interview problems: filters and random pick ─────────────────────── */
	document.querySelectorAll('[data-problems]').forEach(function (wrap) {
		var rows = [].slice.call(wrap.querySelectorAll('tbody tr'));
		var text = wrap.querySelector('[data-filter-text]'), pattern = wrap.querySelector('[data-filter-pattern]');
		var company = wrap.querySelector('[data-filter-company]'), hide = wrap.querySelector('[data-filter-hide]');
		var empty = wrap.querySelector('.problems-empty'), level = '';
		function apply() {
			var q = text ? text.value.trim().toLowerCase() : '', shown = 0;
			rows.forEach(function (r) {
				var ok = (!q || r.getAttribute('data-title').indexOf(q) !== -1 || r.getAttribute('data-pattern').toLowerCase().indexOf(q) !== -1) &&
					(!level || r.getAttribute('data-level') === level) &&
					(!pattern || !pattern.value || r.getAttribute('data-pattern') === pattern.value) &&
					(!company || !company.value || (', ' + r.getAttribute('data-companies') + ',').indexOf(', ' + company.value + ',') !== -1) &&
					(!hide || !hide.checked || !r.classList.contains('is-done'));
				r.hidden = !ok; if (ok) shown++;
			});
			if (empty) empty.hidden = shown > 0;
		}
		wrap.querySelectorAll('[data-filter-level]').forEach(function (b) {
			b.addEventListener('click', function () {
				wrap.querySelectorAll('[data-filter-level]').forEach(function (x) { x.classList.remove('is-active'); });
				b.classList.add('is-active'); level = b.getAttribute('data-filter-level'); apply();
			});
		});
		[text, pattern, company, hide].forEach(function (f) { if (f) f.addEventListener(f.tagName === 'INPUT' && f.type === 'search' ? 'input' : 'change', apply); });
		var rnd = wrap.querySelector('[data-random-problem]');
		if (rnd) rnd.addEventListener('click', function () {
			var pool = rows.filter(function (r) { return !r.hidden && !r.classList.contains('is-done'); });
			if (!pool.length) pool = rows.filter(function (r) { return !r.hidden; });
			if (!pool.length) return;
			var pick = pool[Math.floor(Math.random() * pool.length)];
			pick.classList.add('is-picked');
			pick.scrollIntoView({ behavior: 'smooth', block: 'center' });
			setTimeout(function () { window.location.href = pick.querySelector('a').href; }, 900);
		});
	});

	/* ── Stacky in tip boxes ───────────────────────────────────────────────── */
	document.querySelectorAll('.vs-tip').forEach(function (tip) {
		if (tip.querySelector('.mascot')) return;
		var m = document.createElement('span');
		m.innerHTML = mascot('happy');
		if (m.firstChild) tip.insertBefore(m.firstChild, tip.firstChild);
	});

	paint(stats);
	document.querySelectorAll('[data-count]').forEach(function (el) {
		var to = parseInt(el.textContent, 10) || 0;
		el.textContent = '0';
		countUp(el, to);
	});

	/* ── Quizzes and the daily challenge ──────────────────────────────────── */
	function wireQuestion(fs, onAnswer) {
		var answer = parseInt(fs.getAttribute('data-answer'), 10);
		fs.querySelectorAll('.quiz-opt').forEach(function (b) {
			b.addEventListener('click', function () {
				if (fs.classList.contains('is-answered')) return;
				var pick = parseInt(b.getAttribute('data-i'), 10), right = pick === answer;
				fs.classList.add('is-answered', right ? 'is-right' : 'is-wrong');
				fs.querySelectorAll('.quiz-opt').forEach(function (o) {
					var i = parseInt(o.getAttribute('data-i'), 10);
					o.disabled = true;
					if (i === answer) o.classList.add('is-correct');
					if (i === pick && !right) o.classList.add('is-picked');
				});
				var ex = fs.querySelector('.quiz-explain');
				if (ex) ex.hidden = false;
				onAnswer(right);
			});
		});
	}
	function resetQuestion(fs) {
		fs.classList.remove('is-answered', 'is-right', 'is-wrong');
		fs.querySelectorAll('.quiz-opt').forEach(function (o) { o.disabled = false; o.classList.remove('is-correct', 'is-picked'); });
		var ex = fs.querySelector('.quiz-explain');
		if (ex) ex.hidden = true;
	}

	document.querySelectorAll('[data-quiz]').forEach(function (quiz) {
		var slug = quiz.getAttribute('data-quiz');
		var qs = quiz.querySelectorAll('.quiz-q');
		var result = quiz.querySelector('.quiz-result'), scoreEl = quiz.querySelector('.quiz-score');
		var answered = 0, correct = 0, first = true;
		qs.forEach(function (fs) {
			wireQuestion(fs, function (right) {
				answered++; if (right) correct++;
				if (answered < qs.length) return;
				var perfect = correct === qs.length;
				scoreEl.textContent = 'You got ' + correct + ' of ' + qs.length + ' right. ' + (perfect ? 'Perfect!' : correct >= qs.length / 2 ? 'Nice work, review the ones you missed.' : 'Re-read the lesson and try again.');
				result.hidden = false;
				if (first) {
					first = false;
					addXP('quiz', slug, correct).then(function (res) {
						if (res.gained) scoreEl.textContent += ' +' + res.gained + ' XP';
						if (perfect) celebrate('Perfect quiz!', 'Every answer right. Your brain is levelling up.', 'cheer', res.gained);
					});
				}
			});
		});
		var retry = quiz.querySelector('[data-quiz-retry]');
		if (retry) retry.addEventListener('click', function () {
			answered = 0; correct = 0; result.hidden = true;
			qs.forEach(resetQuestion);
			qs[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
		});
	});

	document.querySelectorAll('[data-daily]').forEach(function (box) {
		var date = box.getAttribute('data-daily'), key = 'vs-daily-' + date;
		var fs = box.querySelector('.quiz-q'), out = box.querySelector('.daily-result');
		if (!fs) return;
		var done = store.get(key, null);
		var saved = done !== null;
		wireQuestion(fs, function (right) {
			if (saved) return;
			saved = true;
			var picked = fs.querySelector('.quiz-opt.is-picked') || fs.querySelector('.quiz-opt.is-correct');
			store.set(key, parseInt(picked.getAttribute('data-i'), 10));
			if (out) { out.hidden = false; out.textContent = right ? 'Correct! Come back tomorrow for a new challenge.' : 'Not this time. The right answer is highlighted. New challenge tomorrow!'; }
			addXP('daily', date, right ? 1 : 0).then(function (res) {
				if (right) celebrate('Daily challenge done!', 'Keep your streak alive tomorrow too.', 'cheer', res.gained);
			});
		});
		if (done !== null) {
			var b = fs.querySelector('.quiz-opt[data-i="' + done + '"]');
			if (b) b.click();
			if (out) { out.hidden = false; out.textContent = 'You already answered today\'s challenge. New one tomorrow!'; }
		}
	});

	/* ── Leaderboard tabs ─────────────────────────────────────────────────── */
	document.querySelectorAll('[data-board]').forEach(function (board) {
		board.querySelectorAll('[data-board-tab]').forEach(function (tab) {
			tab.addEventListener('click', function () {
				var which = tab.getAttribute('data-board-tab');
				board.querySelectorAll('[data-board-tab]').forEach(function (t) { var on = t === tab; t.classList.toggle('is-active', on); t.setAttribute('aria-selected', on ? 'true' : 'false'); });
				board.querySelectorAll('[data-board-panel]').forEach(function (p) { p.hidden = p.getAttribute('data-board-panel') !== which; });
			});
		});
	});
})();
