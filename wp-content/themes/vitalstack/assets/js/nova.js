/*
 * Nova: VitalStack's HD vector guide (4.12).
 * One SVG, any size, crisp on every screen. Moods: wave, point, think, cheer, explain, wow.
 * window.VSNova.svg(mood, size, label) returns an SVG string.
 */
(function () {
	'use strict';
	var n = 0;

	function arms(mood, id) {
		var arm = 'url(#' + id + 'b)';
		var hand = '<circle r="11" fill="url(#' + id + 'h)" stroke="#c9d3e3" stroke-width="1.5"/>';
		var left = '<path d="M62 150 Q42 168 46 190" stroke="' + arm + '" stroke-width="18" stroke-linecap="round" fill="none"/><g transform="translate(46 192)">' + hand + '</g>';
		var right;
		if (mood === 'wave' || mood === 'cheer') {
			right = '<g class="nv-wave"><path d="M140 150 Q180 142 188 112" stroke="' + arm + '" stroke-width="18" stroke-linecap="round" fill="none"/><g transform="translate(190 104)">' + hand + '</g></g>';
		} else if (mood === 'point' || mood === 'explain') {
			right = '<path d="M138 152 Q164 150 182 136" stroke="' + arm + '" stroke-width="18" stroke-linecap="round" fill="none"/><g transform="translate(186 133)">' + hand + '<path d="M6 -4 L20 -10" stroke="#c9d3e3" stroke-width="7" stroke-linecap="round"/></g>';
		} else if (mood === 'think') {
			right = '<path d="M138 152 Q150 132 126 120" stroke="' + arm + '" stroke-width="18" stroke-linecap="round" fill="none"/><g transform="translate(122 118)">' + hand + '</g>';
		} else {
			right = '<path d="M138 150 Q158 168 154 190" stroke="' + arm + '" stroke-width="18" stroke-linecap="round" fill="none"/><g transform="translate(154 192)">' + hand + '</g>';
		}
		if (mood === 'cheer') {
			left = '<g class="nv-wave2"><path d="M60 150 Q20 142 32 112" stroke="' + arm + '" stroke-width="18" stroke-linecap="round" fill="none"/><g transform="translate(30 104)">' + hand + '</g></g>';
		}
		return left + right;
	}

	function face(mood, id) {
		var glow = 'url(#' + id + 'e)';
		var eyes, mouth;
		if (mood === 'think') {
			eyes = '<rect x="72" y="70" width="16" height="7" rx="3.5" fill="' + glow + '"/><rect x="112" y="66" width="16" height="16" rx="8" fill="' + glow + '"/>';
			mouth = '<path d="M90 102 L112 98" stroke="#7ff3d2" stroke-width="4" stroke-linecap="round"/>';
		} else if (mood === 'wow') {
			eyes = '<circle cx="80" cy="74" r="10" fill="' + glow + '"/><circle cx="120" cy="74" r="10" fill="' + glow + '"/>';
			mouth = '<ellipse cx="100" cy="102" rx="7" ry="8" fill="#7ff3d2"/>';
		} else if (mood === 'cheer') {
			eyes = '<path d="M70 78 Q80 64 90 78" stroke="' + glow + '" stroke-width="6" stroke-linecap="round" fill="none"/><path d="M110 78 Q120 64 130 78" stroke="' + glow + '" stroke-width="6" stroke-linecap="round" fill="none"/>';
			mouth = '<path d="M84 96 Q100 116 116 96 Z" fill="#7ff3d2"/>';
		} else {
			eyes = '<g class="nv-blink"><rect x="71" y="62" width="18" height="24" rx="9" fill="' + glow + '"/><rect x="111" y="62" width="18" height="24" rx="9" fill="' + glow + '"/></g><circle cx="84" cy="68" r="3" fill="#fff" opacity=".9"/><circle cx="124" cy="68" r="3" fill="#fff" opacity=".9"/>';
			mouth = '<path d="M88 98 Q100 108 112 98" stroke="#7ff3d2" stroke-width="4.5" stroke-linecap="round" fill="none"/>';
		}
		return eyes + mouth + '<circle cx="66" cy="96" r="6" fill="#ff7aa8" opacity=".45"/><circle cx="134" cy="96" r="6" fill="#ff7aa8" opacity=".45"/>';
	}

	function svg(mood, size, label) {
		mood = mood || 'wave';
		size = size || 160;
		var id = 'nv' + (++n) + '_';
		var extra = '';
		if (mood === 'think') extra = '<g class="nv-dots" fill="#a78bfa"><circle cx="160" cy="40" r="5"/><circle cx="174" cy="24" r="7"/><circle cx="190" cy="8" r="5"/></g>';
		if (mood === 'cheer') extra = '<g fill="#fbbf24"><path d="M24 50 l5 12 12 5 -12 5 -5 12 -5 -12 -12 -5 12 -5z"/><path d="M178 40 l4 9 9 4 -9 4 -4 9 -4 -9 -9 -4 9 -4z"/></g>';
		if (mood === 'explain') extra = '<g class="nv-idea" transform="translate(10 -28)"><circle cx="186" cy="60" r="14" fill="#fde68a"/><path d="M180 76 h12" stroke="#f59e0b" stroke-width="4" stroke-linecap="round"/><path d="M186 40 v-8 M204 60 h8 M168 60 h-8 M199 47 l6 -6 M173 47 l-6 -6" stroke="#fbbf24" stroke-width="3" stroke-linecap="round"/></g>';
		return '<svg class="nova nova-' + mood + '" width="' + size + '" height="' + Math.round(size * 1.2) + '" viewBox="0 0 220 264" role="img" aria-label="' + (label || 'Nova, your AI guide') + '">' +
			'<defs>' +
			'<linearGradient id="' + id + 'w" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffffff"/><stop offset=".6" stop-color="#eef2f9"/><stop offset="1" stop-color="#cfd8e8"/></linearGradient>' +
			'<linearGradient id="' + id + 'v" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#1d2a4a"/><stop offset="1" stop-color="#0b1226"/></linearGradient>' +
			'<radialGradient id="' + id + 'e" cx=".5" cy=".4" r=".7"><stop offset="0" stop-color="#e6fffb"/><stop offset=".5" stop-color="#5eead4"/><stop offset="1" stop-color="#14b8a6"/></radialGradient>' +
			'<linearGradient id="' + id + 'b" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#f8fafc"/><stop offset="1" stop-color="#c8d2e4"/></linearGradient>' +
			'<radialGradient id="' + id + 'h" cx=".35" cy=".35" r=".8"><stop offset="0" stop-color="#ffffff"/><stop offset="1" stop-color="#cdd6e6"/></radialGradient>' +
			'<linearGradient id="' + id + 'g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#34c987"/><stop offset="1" stop-color="#0a7d4f"/></linearGradient>' +
			'<radialGradient id="' + id + 'j" cx=".5" cy="0" r="1"><stop offset="0" stop-color="#a5f3fc"/><stop offset=".5" stop-color="#67e8f9" stop-opacity=".6"/><stop offset="1" stop-color="#67e8f9" stop-opacity="0"/></radialGradient>' +
			'</defs>' +
			extra +
			'<ellipse cx="110" cy="252" rx="46" ry="8" fill="#0b1226" opacity=".12" class="nv-shadow"/>' +
			'<g class="nv-float">' +
			'<path d="M92 214 Q110 250 128 214 Z" fill="url(#' + id + 'j)" class="nv-jet"/>' +
			// body
			'<path d="M64 140 Q64 124 80 122 L140 122 Q156 124 156 140 L150 200 Q148 218 128 218 L92 218 Q72 218 70 200 Z" fill="url(#' + id + 'w)" stroke="#c9d3e3" stroke-width="2"/>' +
			'<path d="M80 130 Q110 124 140 130" stroke="#fff" stroke-width="4" stroke-linecap="round" opacity=".8" fill="none"/>' +
			'<circle cx="110" cy="166" r="17" fill="url(#' + id + 'g)"/><path d="M102 166 l6 6 11 -12" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none"/>' +
			'<circle cx="110" cy="166" r="22" fill="none" stroke="#34c987" stroke-width="2" opacity=".35" class="nv-pulse"/>' +
			// head
			'<g class="nv-head">' +
			'<line x1="110" y1="22" x2="110" y2="6" stroke="#c9d3e3" stroke-width="4" stroke-linecap="round"/><circle cx="110" cy="6" r="7" fill="#34c987" class="nv-bulb"/>' +
			'<rect x="42" y="20" width="136" height="110" rx="46" fill="url(#' + id + 'w)" stroke="#c9d3e3" stroke-width="2"/>' +
			'<circle cx="42" cy="78" r="13" fill="url(#' + id + 'g)"/><circle cx="178" cy="78" r="13" fill="url(#' + id + 'g)"/>' +
			'<rect x="56" y="40" width="108" height="78" rx="34" fill="url(#' + id + 'v)"/>' +
			'<path d="M70 50 Q92 42 118 44" stroke="#fff" stroke-width="5" stroke-linecap="round" opacity=".18" fill="none"/>' +
			face(mood, id) +
			'</g>' + arms(mood, id) + '</g></svg>';
	}

	window.VSNova = { svg: svg };
})();
