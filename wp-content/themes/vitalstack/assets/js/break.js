/**
 * Break zone (4.13.4): header button + panel with games, focus music and tools.
 *
 * Focus music is made live with the Web Audio API: no audio files, no
 * streaming, nothing sent anywhere. Nothing plays until the reader presses
 * Play. The 25-minute focus timer keeps running across pages (its end time is
 * stored on this device) and suggests a 5-minute game break when it ends.
 *
 * @package VitalStack
 */
(function () {
	'use strict';

	var btn = document.querySelector('[data-break-open]');
	var panel = document.querySelector('[data-break-panel]');
	if (!btn || !panel) return;
	var $ = function (s) { return panel.querySelector(s); };
	var $$ = function (s) { return panel.querySelectorAll(s); };
	var Ctx = window.AudioContext || window.webkitAudioContext;

	var store = {
		get: function (k, d) { try { var v = JSON.parse(localStorage.getItem(k)); return v == null ? d : v; } catch (e) { return d; } },
		set: function (k, v) { try { localStorage.setItem(k, JSON.stringify(v)); } catch (e) {} }
	};
	var prefs = store.get('vs-break', {});
	if (typeof prefs !== 'object' || !prefs) prefs = {};
	var STATIONS = ['lofi', 'rain', 'keys', 'brown'];
	var station = STATIONS.indexOf(prefs.station) > -1 ? prefs.station : 'lofi';
	var vol = typeof prefs.vol === 'number' ? Math.max(0, Math.min(100, prefs.vol)) : 55;
	function savePrefs() { store.set('vs-break', { station: station, vol: vol, tab: prefs.tab, was: playing }); }

	/* ── Open / close ─────────────────────────────────────────────────────── */
	function place() {
		if (window.innerWidth < 640) { panel.style.top = ''; panel.style.right = ''; return; }
		var r = btn.getBoundingClientRect();
		panel.style.top = Math.round(r.bottom + 8) + 'px';
		panel.style.right = Math.max(12, Math.round(window.innerWidth - r.right - 60)) + 'px';
	}
	function open(tab) {
		place();
		panel.hidden = false;
		btn.setAttribute('aria-expanded', 'true');
		document.documentElement.classList.add('break-open');
		select(tab || (doneMsg() ? 'games' : prefs.tab) || 'games');
		var first = panel.querySelector('[aria-selected="true"]');
		if (first) first.focus({ preventScroll: true });
	}
	function close(back) {
		if (panel.hidden) return;
		panel.hidden = true;
		btn.setAttribute('aria-expanded', 'false');
		document.documentElement.classList.remove('break-open');
		if (back) btn.focus({ preventScroll: true });
	}
	btn.addEventListener('click', function (e) { e.stopPropagation(); if (panel.hidden) open(); else close(); });
	$('[data-break-close]').addEventListener('click', function () { close(true); });
	document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(true); });
	document.addEventListener('click', function (e) { if (!panel.hidden && !panel.contains(e.target) && !btn.contains(e.target)) close(); });
	window.addEventListener('resize', function () { if (!panel.hidden) place(); });

	/* ── Tabs ─────────────────────────────────────────────────────────────── */
	var tabs = Array.prototype.slice.call($$('[data-break-tab]'));
	function select(name) {
		if (!panel.querySelector('[data-break-pane="' + name + '"]')) name = tabs[0] && tabs[0].getAttribute('data-break-tab');
		tabs.forEach(function (t) {
			var on = t.getAttribute('data-break-tab') === name;
			t.setAttribute('aria-selected', on ? 'true' : 'false');
			t.tabIndex = on ? 0 : -1;
		});
		$$('[data-break-pane]').forEach(function (p) { p.hidden = p.getAttribute('data-break-pane') !== name; });
		prefs.tab = name; savePrefs();
	}
	tabs.forEach(function (t, i) {
		t.addEventListener('click', function () { select(t.getAttribute('data-break-tab')); });
		t.addEventListener('keydown', function (e) {
			var d = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
			if (!d) return;
			var n = tabs[(i + d + tabs.length) % tabs.length];
			select(n.getAttribute('data-break-tab')); n.focus();
		});
	});

	// A game link to the page we are already on: games.js reads the hash on load.
	$$('[data-break-game]').forEach(function (a) {
		a.addEventListener('click', function (e) {
			var u = new URL(a.href, location.href);
			if (u.pathname === location.pathname && u.hash) { e.preventDefault(); location.hash = u.hash; location.reload(); }
		});
	});

	/* ── Focus music ──────────────────────────────────────────────────────── */
	var ctx = null, out = null, nodes = [], timer = 0, nextT = 0, step = 0, playing = false;
	var playBtn = $('[data-break-play]'), volIn = $('[data-break-vol]');
	if (!Ctx) {
		playBtn.disabled = true;
		$('[data-break-play-txt]').textContent = 'Not supported in this browser';
	}
	volIn.value = vol;
	function hz(m) { return 440 * Math.pow(2, (m - 69) / 12); }
	function noise(kind, secs) {
		var len = Math.floor(ctx.sampleRate * secs), b = ctx.createBuffer(1, len, ctx.sampleRate), d = b.getChannelData(0), last = 0, p = [0, 0, 0];
		for (var i = 0; i < len; i++) {
			var w = Math.random() * 2 - 1;
			if (kind === 'brown') { last = (last + 0.02 * w) / 1.02; d[i] = last * 3.2; }
			else if (kind === 'pink') { p[0] = 0.997 * p[0] + w * 0.029; p[1] = 0.985 * p[1] + w * 0.032; p[2] = 0.95 * p[2] + w * 0.048; d[i] = (p[0] + p[1] + p[2] + w * 0.05) * 1.6; }
			else if (kind === 'crackle') { d[i] = Math.random() < 0.0005 ? w * 0.7 : w * 0.01; }
			else d[i] = w;
		}
		return b;
	}
	function loop(buf, gain, filterType, freq) {
		var s = ctx.createBufferSource(); s.buffer = buf; s.loop = true;
		var g = ctx.createGain(); g.gain.value = gain;
		if (filterType) { var f = ctx.createBiquadFilter(); f.type = filterType; f.frequency.value = freq; s.connect(f); f.connect(g); } else s.connect(g);
		g.connect(out); s.start(); nodes.push(s);
	}
	function tone(m, t, len, type, gain, cutoff) {
		var o = ctx.createOscillator(), g = ctx.createGain(), f = ctx.createBiquadFilter();
		o.type = type; o.frequency.value = hz(m); f.type = 'lowpass'; f.frequency.value = cutoff;
		g.gain.setValueAtTime(0, t); g.gain.linearRampToValueAtTime(gain, t + Math.min(0.08, len / 4));
		g.gain.exponentialRampToValueAtTime(0.0008, t + len);
		o.connect(f); f.connect(g); g.connect(out); o.start(t); o.stop(t + len + 0.05);
	}
	function drum(t, kind) {
		if (kind === 'kick') {
			var o = ctx.createOscillator(), g = ctx.createGain();
			o.frequency.setValueAtTime(120, t); o.frequency.exponentialRampToValueAtTime(42, t + 0.18);
			g.gain.setValueAtTime(0.5, t); g.gain.exponentialRampToValueAtTime(0.001, t + 0.3);
			o.connect(g); g.connect(out); o.start(t); o.stop(t + 0.32);
		} else {
			var s = ctx.createBufferSource(), hg = ctx.createGain(), hf = ctx.createBiquadFilter();
			s.buffer = hatBuf; hf.type = 'highpass'; hf.frequency.value = kind === 'snare' ? 1200 : 6000;
			hg.gain.setValueAtTime(kind === 'snare' ? 0.16 : 0.05, t); hg.gain.exponentialRampToValueAtTime(0.001, t + (kind === 'snare' ? 0.18 : 0.05));
			s.connect(hf); hf.connect(hg); hg.connect(out); s.start(t); s.stop(t + 0.2);
		}
	}
	var hatBuf = null;
	var PROG = { lofi: [[62, 65, 69, 72], [55, 59, 62, 65], [60, 64, 67, 71], [57, 60, 64, 67]], rain: [[57, 60, 64, 67], [53, 57, 60, 64], [60, 64, 67, 71], [55, 59, 62, 67]], keys: [[60, 64, 67, 71], [57, 60, 64, 69], [53, 57, 60, 64], [55, 59, 62, 65]] };
	var PENTA = [0, 2, 4, 7, 9, 12];
	function schedule() {
		var spb = station === 'lofi' ? 60 / 76 / 2 : station === 'keys' ? 60 / 66 / 2 : 60 / 56 / 2; // eighth notes
		while (nextT < ctx.currentTime + 0.25) {
			var bar = Math.floor(step / 8) % 4, beat = step % 8, chord = PROG[station] && PROG[station][bar];
			if (station === 'lofi') {
				if (beat === 0) chord.forEach(function (m) { tone(m - 12, nextT, spb * 8, 'triangle', 0.05, 1300); });
				if (beat === 0 || beat === 5) drum(nextT, 'kick');
				if (beat === 4) drum(nextT, 'snare');
				drum(nextT + (beat % 2 ? spb * 0.12 : 0), 'hat');
				if (Math.random() < 0.3) tone(chord[0] + PENTA[Math.floor(Math.random() * PENTA.length)], nextT, spb * 2, 'sine', 0.035, 2400);
			} else if (station === 'rain') {
				if (beat === 0 && step % 16 === 0) chord.forEach(function (m) { tone(m - 12, nextT, spb * 16, 'sine', 0.045, 900); });
				if (Math.random() < 0.12) tone(chord[1] + 12, nextT, spb * 4, 'sine', 0.02, 1600);
			} else if (station === 'keys') {
				if (beat === 0) tone(chord[0] - 24, nextT, spb * 8, 'sine', 0.06, 600);
				if (beat % 2 === 0 && Math.random() < 0.7) tone(chord[Math.floor(Math.random() * 4)] + (Math.random() < 0.4 ? 12 : 0), nextT, spb * 5, 'triangle', 0.045, 2000);
			}
			nextT += spb; step++;
		}
	}
	function start() {
		if (!Ctx) return;
		if (!ctx) {
			ctx = new Ctx();
			out = ctx.createGain();
			var comp = ctx.createDynamicsCompressor(); comp.threshold.value = -20; comp.ratio.value = 4;
			var master = ctx.createGain(); master.gain.value = 1;
			out.connect(comp); comp.connect(master); master.connect(ctx.destination);
			hatBuf = noise('white', 0.3);
		}
		if (ctx.state === 'suspended') ctx.resume();
		stopNodes();
		out.gain.cancelScheduledValues(ctx.currentTime);
		out.gain.setValueAtTime(0, ctx.currentTime);
		out.gain.linearRampToValueAtTime(vol / 100, ctx.currentTime + 0.8);
		if (station === 'lofi') loop(noise('crackle', 3), 0.6, 'bandpass', 3000);
		if (station === 'rain') { loop(noise('pink', 4), 0.55, 'lowpass', 2600); loop(noise('white', 3), 0.04, 'highpass', 7000); }
		if (station === 'brown') loop(noise('brown', 5), 0.85, 'lowpass', 900);
		step = 0; nextT = ctx.currentTime + 0.05;
		if (station !== 'brown') { schedule(); timer = setInterval(schedule, 60); }
		playing = true;
		// Only one sound at a time: stop the lesson "Focus sounds" if it is playing.
		var fs = document.querySelector('.focus-pill:not([hidden]) .focus-pill-stop');
		if (fs) fs.click();
		var sm = document.querySelector('[data-music] [data-play][aria-pressed="true"]'); // the Study Music room (5.0)
		if (sm) sm.click();
		ui();
	}
	function stopNodes() {
		clearInterval(timer); timer = 0;
		nodes.forEach(function (n) { try { n.stop(); } catch (e) {} });
		nodes = [];
	}
	function stop() {
		if (!ctx) return;
		var t = ctx.currentTime;
		out.gain.cancelScheduledValues(t); out.gain.setValueAtTime(out.gain.value, t); out.gain.linearRampToValueAtTime(0, t + 0.3);
		setTimeout(stopNodes, 320);
		playing = false;
		ui();
	}
	function ui() {
		playBtn.setAttribute('aria-pressed', playing ? 'true' : 'false');
		$('[data-break-play-ico]').textContent = playing ? '❚❚' : '▶';
		$('[data-break-play-txt]').textContent = playing ? 'Pause' : (prefs.was ? 'Resume' : 'Play');
		btn.classList.toggle('is-playing', playing);
		btn.classList.toggle('is-resume', !playing && !!prefs.was);
		$$('[data-station]').forEach(function (b) { b.setAttribute('aria-checked', b.getAttribute('data-station') === station ? 'true' : 'false'); });
		savePrefs();
	}
	playBtn.addEventListener('click', function () { if (playing) { prefs.was = false; stop(); } else start(); });
	$$('[data-station]').forEach(function (b) {
		b.addEventListener('click', function () { station = b.getAttribute('data-station'); if (playing) start(); else ui(); });
	});
	volIn.addEventListener('input', function () {
		vol = +volIn.value;
		if (ctx && playing) out.gain.setTargetAtTime(vol / 100, ctx.currentTime, 0.05);
		savePrefs();
	});
	// The lesson "Focus sounds" player takes over: pause this one.
	document.addEventListener('click', function (e) { if (playing && e.target.closest && e.target.closest('[data-focus-play], [data-music] [data-play]')) stop(); }, true);
	ui();

	/* ── 25-minute focus timer (survives page changes) ────────────────────── */
	var clock = $('[data-break-clock]'), tBtn = $('[data-break-timer]'), tStop = $('[data-break-timer-stop]'), tHead = btn.querySelector('[data-break-time]'), done = $('[data-break-done]');
	var FOCUS = 25 * 60e3, REST = 5 * 60e3;
	function tState() { var s = store.get('vs-break-timer', null); return s && typeof s.end === 'number' ? s : null; }
	function fmt(ms) { var s = Math.max(0, Math.ceil(ms / 1000)); return Math.floor(s / 60) + ':' + ('0' + s % 60).slice(-2); }
	function doneMsg() { var s = tState(); return s && s.end <= Date.now(); }
	function chime() {
		if (!ctx) return;
		[76, 79, 84].forEach(function (m, i) { tone(m, ctx.currentTime + i * 0.18, 0.9, 'sine', 0.12, 4000); });
	}
	var lastLeft = null;
	function tick() {
		var s = tState();
		if (!s) {
			clock.textContent = '25:00'; tBtn.textContent = 'Start 25 min'; tBtn.hidden = false; tStop.hidden = true; tHead.hidden = true;
			btn.classList.remove('is-timer', 'is-done');
			return;
		}
		var left = s.end - Date.now();
		if (left > 0) {
			clock.textContent = fmt(left);
			tHead.textContent = (s.mode === 'rest' ? '🎮 ' : '') + fmt(left); tHead.hidden = false;
			tBtn.hidden = true; tStop.hidden = false;
			btn.classList.add('is-timer'); btn.classList.remove('is-done');
			done.hidden = true;
		} else {
			if (lastLeft !== null && lastLeft > 0) chime();
			tHead.hidden = true; clock.textContent = '0:00';
			tBtn.hidden = false; tStop.hidden = false;
			btn.classList.remove('is-timer'); btn.classList.add('is-done');
			if (s.mode === 'rest') {
				done.textContent = '⏰ Break over. Ready for another 25 minutes?';
				tBtn.textContent = 'Start 25 min';
			} else {
				done.textContent = '🎉 25 minutes of focus done! Take 5: play a quick game or stretch.';
				tBtn.textContent = 'Start 5 min break';
			}
			done.hidden = false;
		}
		lastLeft = left;
	}
	tBtn.addEventListener('click', function () {
		var s = tState(), rest = s && s.end <= Date.now() && s.mode !== 'rest';
		store.set('vs-break-timer', { end: Date.now() + (rest ? REST : FOCUS), mode: rest ? 'rest' : 'focus' });
		if (rest) select('games');
		lastLeft = null; tick();
	});
	tStop.addEventListener('click', function () { try { localStorage.removeItem('vs-break-timer'); } catch (e) {} done.hidden = true; tick(); });
	window.addEventListener('storage', function (e) { if (e.key === 'vs-break-timer') tick(); });
	tick();
	setInterval(tick, 1000);
})();
