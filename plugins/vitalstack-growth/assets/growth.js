/* VitalStack Growth Desk: offer currency, real countdown, slide-in card. */
(function () {
	'use strict';
	var offers = Array.prototype.slice.call(document.querySelectorAll('[data-vsg-offer]'));
	if (!offers.length) return;

	function store(k, v) { try { if (v === undefined) return localStorage.getItem(k); localStorage.setItem(k, v); } catch (e) { return null; } }
	function tzIndia() { try { return /^Asia\/(Kolkata|Calcutta)$/.test(Intl.DateTimeFormat().resolvedOptions().timeZone || ''); } catch (e) { return true; } }
	function currency(rule) {
		if (rule === 'inr' || rule === 'usd') return rule;
		if (rule === '') { var saved = store('vs-cur'); if (saved === 'inr' || saved === 'usd') return saved; }
		return tzIndia() ? 'inr' : 'usd';
	}

	/* Prices and savings in the reader's currency. */
	offers.forEach(function (box) {
		var cur = currency(box.getAttribute('data-vsg-cur') || 'tz');
		box.querySelectorAll('[data-x-inr]').forEach(function (el) {
			var t = el.getAttribute('data-x-' + cur) || '';
			el.textContent = t;
			el.hidden = t === '';
		});
	});

	/* Countdown to the real end date set by the admin; everything goes when it ends. */
	function left(ms) {
		var s = Math.max(0, Math.floor(ms / 1000)), d = Math.floor(s / 86400), h = Math.floor(s % 86400 / 3600), m = Math.floor(s % 3600 / 60), sec = s % 60;
		function p(n) { return (n < 10 ? '0' : '') + n; }
		return (d ? d + 'd ' : '') + p(h) + 'h ' + p(m) + 'm ' + p(sec) + 's';
	}
	function tick() {
		var now = Date.now(), live = 0;
		offers.forEach(function (box) {
			if (!box.isConnected) return;
			var end = parseInt(box.getAttribute('data-vsg-ends') || '0', 10);
			if (end && now >= end) { box.remove(); return; }
			live++;
			if (end) box.querySelectorAll('[data-vsg-left]').forEach(function (el) { el.textContent = left(end - now); });
		});
		return live;
	}
	if (tick() && offers.some(function (b) { return b.hasAttribute('data-vsg-ends'); })) {
		var timer = setInterval(function () { if (!tick()) clearInterval(timer); }, 1000);
	}

	/* "See all plans" scrolls to the plan cards. */
	document.querySelectorAll('[data-vsg-jump]').forEach(function (a) {
		a.addEventListener('click', function (e) {
			var t = document.getElementById('plans') || document.querySelector('.pricing-grid');
			if (!t) return;
			e.preventDefault();
			t.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' });
		});
	});

	/* Slide-in card: once every few days, after 12 seconds or half the page. */
	var slide = document.querySelector('[data-vsg-slide]');
	if (!slide || !slide.isConnected) return;
	var days = parseInt(slide.getAttribute('data-days') || '3', 10);
	var last = parseInt(store('vsg-slide') || '0', 10);
	if (last && Date.now() - last < days * 86400000) { slide.remove(); return; }
	var shown = false, retry = 0, waiting = false;
	function visible(el) { if (!el || el === slide) return false; var r = el.getBoundingClientRect(); return r.width > 0 && r.height > 0 && getComputedStyle(el).visibility !== 'hidden'; }
	function busy() {
		// Never on top of another popup: a dialog, the study buddy tip, or (on small screens) the cookie note.
		var sel = '[role="dialog"][aria-modal="true"], .mascot-nudge.is-visible, .vsa-panel:not([hidden])' + (innerWidth < 760 ? ', .cookie-note' : '');
		return Array.prototype.some.call(document.querySelectorAll(sel), visible);
	}
	function show() {
		if (shown || waiting || !slide.isConnected) return;
		if (busy()) {
			if (retry++ < 36) { waiting = true; setTimeout(function () { waiting = false; show(); }, 5000); }
			return;
		}
		shown = true;
		slide.hidden = false;
		requestAnimationFrame(function () { slide.classList.add('is-in'); });
		store('vsg-slide', String(Date.now()));
		window.removeEventListener('scroll', onScroll);
	}
	function hide() {
		slide.classList.remove('is-in');
		setTimeout(function () { slide.remove(); }, 250);
		store('vsg-slide', String(Date.now()));
	}
	function onScroll() {
		var h = document.documentElement;
		if ((h.scrollTop + innerHeight) / Math.max(1, h.scrollHeight) > 0.5) { window.removeEventListener('scroll', onScroll); show(); }
	}
	setTimeout(show, 12000);
	window.addEventListener('scroll', onScroll, { passive: true });
	slide.querySelectorAll('[data-vsg-close]').forEach(function (b) { b.addEventListener('click', hide); });
	document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && shown && slide.isConnected) hide(); });
})();
