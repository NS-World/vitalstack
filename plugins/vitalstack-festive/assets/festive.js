/* VitalStack Festive: greeting close, character touches, short home burst. */
(function () {
	'use strict';
	var F = window.vsFestive;
	if (!F) return;
	var key = 'vsf-off-' + F.key;

	var band = document.querySelector('[data-vsf-band]');
	if (band) {
		try { if (sessionStorage.getItem(key)) band.hidden = true; } catch (e) {}
		var x = band.querySelector('[data-vsf-close]');
		if (x) x.addEventListener('click', function () { band.hidden = true; try { sessionStorage.setItem(key, '1'); } catch (e) {} });
	}

	// One festive item next to each character (not inside text or tiny icons).
	document.querySelectorAll('svg.mascot').forEach(function (svg, i) {
		if (i > 12) return;
		var w = svg.getBoundingClientRect().width || parseFloat(svg.getAttribute('width')) || 0;
		if (w < 40) return;
		var host = svg.parentNode;
		if (!host || host.querySelector(':scope > .vsf-acc')) return;
		if (getComputedStyle(host).position === 'static') host.classList.add('vsf-acc-wrap');
		var s = document.createElement('span');
		s.className = 'vsf-acc' + (w < 80 ? ' is-small' : '');
		s.setAttribute('aria-hidden', 'true');
		s.textContent = F.acc;
		host.appendChild(s);
	});

	// A short burst on the home page: about 5 seconds, then gone.
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	if (!F.burst || reduce || document.hidden) return;
	try { if (sessionStorage.getItem('vsf-burst-' + F.key)) return; sessionStorage.setItem('vsf-burst-' + F.key, '1'); } catch (e) {}
	var n = window.innerWidth < 600 ? 10 : 18, bits = F.bits && F.bits.length ? F.bits : ['✨'];
	for (var i = 0; i < n; i++) {
		var b = document.createElement('span');
		b.className = 'vsf-bit';
		b.setAttribute('aria-hidden', 'true');
		b.textContent = bits[i % bits.length];
		b.style.left = (Math.random() * 96) + 'vw';
		b.style.fontSize = (16 + Math.random() * 14) + 'px';
		b.style.animationDuration = (3.2 + Math.random() * 2) + 's';
		b.style.animationDelay = (Math.random() * 1.2) + 's';
		b.style.setProperty('--dx', (Math.random() * 80 - 40) + 'px');
		b.style.setProperty('--rot', (Math.random() * 360 - 180) + 'deg');
		b.addEventListener('animationend', function () { this.remove(); });
		document.body.appendChild(b);
	}
})();
