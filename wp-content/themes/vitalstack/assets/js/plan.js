/**
 * Study plan (4.16.0): ticks lessons the reader has completed (the same
 * "vs-progress" store the lessons use; it syncs to the account when signed
 * in), fills the progress bar and points the main button at the next day.
 *
 * @package VitalStack
 */
(function () {
	'use strict';

	var root = document.querySelector('[data-plan]');
	if (!root) return;
	var progress = {};
	try { progress = JSON.parse(localStorage.getItem('vs-progress') || '{}') || {}; } catch (e) { progress = {}; }
	function done(path, id) {
		var list = progress[path];
		return Array.isArray(list) && (list.indexOf(id) !== -1 || list.indexOf(Number(id)) !== -1 || list.indexOf(String(id)) !== -1);
	}

	var items = root.querySelectorAll('[data-lesson]'), count = 0, next = null;
	items.forEach(function (li) {
		var ok = done(li.getAttribute('data-path'), li.getAttribute('data-lesson'));
		li.classList.toggle('is-done', ok);
		if (ok) count++;
		else if (!next) next = li.closest('[data-day]');
	});
	root.querySelectorAll('[data-day]').forEach(function (day) {
		var ls = day.querySelectorAll('[data-lesson]');
		if (ls.length && day.querySelectorAll('[data-lesson].is-done').length === ls.length) day.classList.add('is-done');
	});

	var c = root.querySelector('[data-plan-count]'), bar = root.querySelector('[data-plan-bar]'), btn = root.querySelector('[data-plan-next]');
	if (c) c.textContent = String(count);
	if (bar && items.length) bar.style.width = Math.round(count / items.length * 100) + '%';
	if (btn && count > 0) {
		if (next) {
			btn.href = '#' + next.id;
			btn.textContent = 'Continue with Day ' + next.getAttribute('data-day') + ' →';
			next.classList.add('is-next');
		} else {
			btn.textContent = 'Plan complete! 🎉 See what’s next';
			btn.href = '#plan-day-' + root.querySelectorAll('[data-day]').length;
		}
	}
})();
