/**
 * Premium themes (5.0). Premium readers switch skins from the palette
 * button; everyone else can preview one for 20 seconds. Picking a normal
 * theme mode or colour turns the skin off.
 */
(function () {
	'use strict';
	var cfg = window.vitalstackSkins || {}, root = document.documentElement, timer = 0, toast = null;
	function set(skin, keep) {
		if (skin) root.setAttribute('data-skin', skin); else root.removeAttribute('data-skin');
		if (keep) { try { if (skin) localStorage.setItem('vs-skin', skin); else localStorage.removeItem('vs-skin'); } catch (e) {} }
		sync();
	}
	function sync() {
		var cur = root.getAttribute('data-skin') || '';
		document.querySelectorAll('[data-set-skin]').forEach(function (b) { b.setAttribute('aria-pressed', b.getAttribute('data-set-skin') === cur ? 'true' : 'false'); });
	}
	function say(name) {
		if (!toast) {
			toast = document.createElement('div'); toast.className = 'vs-skin-toast'; toast.setAttribute('role', 'status');
			document.body.appendChild(toast);
		}
		toast.textContent = '';
		toast.appendChild(document.createTextNode('⭐ ' + String(cfg.preview || 'Previewing %s.').replace('%s', name) + ' '));
		if (cfg.pricing) { var a = document.createElement('a'); a.href = cfg.pricing; a.textContent = cfg.unlock || 'Unlock'; toast.appendChild(a); }
		toast.hidden = false;
	}
	document.addEventListener('click', function (e) {
		var b = e.target.closest('[data-set-skin]');
		if (b) {
			var skin = b.getAttribute('data-set-skin'), on = root.getAttribute('data-skin') === skin;
			clearTimeout(timer);
			if (cfg.open) { set(on ? '' : skin, true); return; }
			set(skin, false); say((cfg.names && cfg.names[skin]) || skin);
			timer = setTimeout(function () { set('', false); if (toast) toast.hidden = true; }, 20000);
			return;
		}
		if (e.target.closest('[data-set-theme], [data-set-accent]') && root.hasAttribute('data-skin')) { clearTimeout(timer); set('', !!cfg.open); if (toast) toast.hidden = true; }
	});
	sync();
})();
