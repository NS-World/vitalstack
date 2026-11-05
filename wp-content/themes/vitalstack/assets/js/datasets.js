/* Datasets Hub (5.0): JSON download and copy-code buttons. The CSV link works without JavaScript. */
(function () {
	'use strict';
	function parseCSV(text) {
		var rows = [], row = [], cell = '', q = false;
		for (var i = 0; i < text.length; i++) {
			var c = text[i];
			if (q) {
				if (c === '"') { if (text[i + 1] === '"') { cell += '"'; i++; } else q = false; } else cell += c;
			} else if (c === '"') q = true;
			else if (c === ',') { row.push(cell); cell = ''; }
			else if (c === '\n') { row.push(cell); rows.push(row); row = []; cell = ''; }
			else if (c !== '\r') cell += c;
		}
		if (cell !== '' || row.length) { row.push(cell); rows.push(row); }
		return rows;
	}
	function toValue(v) { if (v === '') return null; var n = Number(v); return v.trim() !== '' && isFinite(n) && !/^0\d/.test(v) ? n : v; }
	function save(name, blob) {
		var a = document.createElement('a');
		a.href = URL.createObjectURL(blob); a.download = name;
		document.body.appendChild(a); a.click();
		setTimeout(function () { URL.revokeObjectURL(a.href); a.remove(); }, 1000);
	}
	document.querySelectorAll('[data-dataset]').forEach(function (card) {
		var slug = card.getAttribute('data-dataset'), url = card.getAttribute('data-url');
		var jb = card.querySelector('[data-ds-json]'), cb = card.querySelector('[data-ds-copy]');
		if (jb) jb.addEventListener('click', function () {
			var old = jb.textContent; jb.disabled = true; jb.textContent = 'Preparing…';
			fetch(url, { credentials: 'omit' }).then(function (r) { if (!r.ok) throw new Error(); return r.text(); }).then(function (t) {
				var rows = parseCSV(t), head = rows.shift() || [];
				var out = rows.filter(function (r) { return r.length === head.length; }).map(function (r) { var o = {}; head.forEach(function (h, k) { o[h] = toValue(r[k]); }); return o; });
				save(slug + '.json', new Blob([JSON.stringify(out, null, 1)], { type: 'application/json' }));
				jb.textContent = '✓ Downloaded';
			}).catch(function () { jb.textContent = 'Could not prepare JSON'; }).then(function () { setTimeout(function () { jb.textContent = old; jb.disabled = false; }, 2000); });
		});
		if (cb) cb.addEventListener('click', function () {
			var code = card.querySelector('.dsh-code code').textContent;
			var done = function () { var o = cb.textContent; cb.textContent = '✓ Copied'; setTimeout(function () { cb.textContent = o; }, 1500); };
			if (navigator.clipboard) navigator.clipboard.writeText(code).then(done, function () {}); else done();
		});
	});
})();
