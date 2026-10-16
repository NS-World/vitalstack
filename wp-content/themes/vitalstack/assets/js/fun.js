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
	var LESSON_XP = 10, VIZ_XP = 2, GAME_DAY_CAP = 60;

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

	paint(stats);
	document.querySelectorAll('[data-count]').forEach(function (el) {
		var to = parseInt(el.textContent, 10) || 0;
		el.textContent = '0';
		countUp(el, to);
	});
})();
