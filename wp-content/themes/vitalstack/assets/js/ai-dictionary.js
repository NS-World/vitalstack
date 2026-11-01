/* AI Dictionary (4.12): search, topic filters and Nova. Works on the page's own HTML. */
(function () {
	'use strict';
	if (window.VSNova) document.querySelectorAll('[data-nova]').forEach(function (el) { el.innerHTML = window.VSNova.svg(el.getAttribute('data-nova'), el.classList.contains('aid-nova') ? 170 : 110); });
	var input = document.querySelector('[data-aid-search]');
	var cards = Array.prototype.slice.call(document.querySelectorAll('[data-aid-card]'));
	if (!input || !cards.length) return;
	var groups = Array.prototype.slice.call(document.querySelectorAll('[data-aid-group]'));
	var filters = Array.prototype.slice.call(document.querySelectorAll('[data-aid-filter]'));
	var count = document.querySelector('[data-aid-count]');
	var empty = document.querySelector('[data-aid-empty]');
	var cat = '';
	function norm(s) { return (s || '').toLowerCase().replace(/[^a-z0-9 ]+/g, ' '); }
	cards.forEach(function (c) { c._text = norm(c.textContent); });
	function apply() {
		var q = norm(input.value).trim(), shown = 0;
		cards.forEach(function (c) {
			var ok = (!cat || c.getAttribute('data-cat') === cat) && (!q || q.split(/\s+/).every(function (w) { return c._text.indexOf(w) !== -1; }));
			c.hidden = !ok; if (ok) shown++;
		});
		groups.forEach(function (g) { g.hidden = !g.querySelector('[data-aid-card]:not([hidden])'); });
		empty.hidden = shown > 0;
		count.textContent = (q || cat) ? shown + (shown === 1 ? ' word' : ' words') : '';
	}
	input.addEventListener('input', apply);
	filters.forEach(function (b) {
		b.addEventListener('click', function () {
			cat = b.getAttribute('data-aid-filter');
			filters.forEach(function (x) { x.setAttribute('aria-pressed', x === b ? 'true' : 'false'); });
			apply();
		});
	});
	// Opening /ai-dictionary/#rag highlights that card.
	function flash() {
		var id = decodeURIComponent(location.hash.slice(1));
		var el = id && document.getElementById(id);
		if (el && el.hasAttribute('data-aid-card')) { el.classList.remove('is-flash'); void el.offsetWidth; el.classList.add('is-flash'); }
	}
	window.addEventListener('hashchange', flash); flash();
})();
