/**
 * Focus sounds for reading: gamma (40 Hz) or beta (14–30 Hz) beats, made
 * live with the Web Audio API (no audio files are downloaded).
 *
 *  - Headphones: binaural beat, a slightly different tone in each ear; the
 *    difference between them is the beat (e.g. 200 Hz + 240 Hz = 40 Hz).
 *  - Speakers: the same tone pulsed on and off at the beat rate (isochronic).
 *  - Optional soft background noise, a timer, and a small "now playing" pill.
 *
 * Nothing plays until the reader presses Play. Settings are remembered on
 * this device only.
 *
 * @package VitalStack
 */
(function () {
	'use strict';

	var btn = document.querySelector('[data-focus-open]');
	var panel = document.getElementById('focus-panel');
	var Ctx = window.AudioContext || window.webkitAudioContext;
	if (!btn || !panel || !Ctx) return;
	btn.hidden = false;

	var store = {
		get: function () { try { return JSON.parse(localStorage.getItem('vs-focus') || '{}'); } catch (e) { return {}; } },
		set: function (v) { try { localStorage.setItem('vs-focus', JSON.stringify(v)); } catch (e) {} }
	};
	var prefs = Object.assign({ mode: 'gamma', beta: 18, out: 'phones', bed: 'brown', vol: 25, mins: 25 }, store.get());

	var $ = function (sel) { return panel.querySelector(sel); };
	var form = $('form');
	var betaRow = $('[data-beta-row]');
	var betaOut = $('[data-beta-out]');
	var playBtn = $('[data-focus-play]');
	var pill = document.createElement('div');
	pill.className = 'focus-pill';
	pill.hidden = true;
	pill.innerHTML = '<span class="focus-pill-dot" aria-hidden="true"></span><span class="focus-pill-text"></span><button type="button" class="focus-pill-stop" aria-label="Stop focus sounds">■</button>';
	document.body.appendChild(pill);

	// Restore saved choices into the form.
	form.elements.mode.value = prefs.mode;
	form.elements.beta.value = prefs.beta;
	form.elements.out.value = prefs.out;
	form.elements.bed.value = prefs.bed;
	form.elements.vol.value = prefs.vol;
	form.elements.mins.value = String(prefs.mins);

	function beatHz() { return form.elements.mode.value === 'gamma' ? 40 : +form.elements.beta.value; }
	function label() { return (form.elements.mode.value === 'gamma' ? 'Gamma' : 'Beta') + ' ' + beatHz() + ' Hz'; }
	function syncForm() {
		betaRow.hidden = form.elements.mode.value !== 'beta';
		betaOut.textContent = form.elements.beta.value + ' Hz';
		prefs = { mode: form.elements.mode.value, beta: +form.elements.beta.value, out: form.elements.out.value, bed: form.elements.bed.value, vol: +form.elements.vol.value, mins: +form.elements.mins.value };
		store.set(prefs);
	}

	/* ── Audio graph ─────────────────────────────────────────────────── */
	var ctx = null, master = null, nodes = [], endsAt = 0, ticker = null;
	var CARRIER = 220; // a soft, low tone

	function noiseBuffer(kind) {
		var len = ctx.sampleRate * 4, buf = ctx.createBuffer(1, len, ctx.sampleRate), d = buf.getChannelData(0);
		var last = 0, b0 = 0, b1 = 0, b2 = 0;
		for (var i = 0; i < len; i++) {
			var w = Math.random() * 2 - 1;
			if (kind === 'brown') { last = (last + 0.02 * w) / 1.02; d[i] = last * 3.5; }
			else { b0 = 0.99765 * b0 + w * 0.099; b1 = 0.963 * b1 + w * 0.2965; b2 = 0.57 * b2 + w * 1.0527; d[i] = (b0 + b1 + b2 + w * 0.1848) * 0.11; }
		}
		return buf;
	}
	function osc(freq) { var o = ctx.createOscillator(); o.type = 'sine'; o.frequency.value = freq; nodes.push(o); return o; }

	function build() {
		var beat = beatHz();
		var tones = ctx.createGain(); tones.gain.value = 0.35; tones.connect(master); nodes.push(tones);
		if (form.elements.out.value === 'phones') {
			// Binaural: each ear gets its own tone.
			var merger = ctx.createChannelMerger(2); merger.connect(tones); nodes.push(merger);
			var l = osc(CARRIER), r = osc(CARRIER + beat);
			l.connect(merger, 0, 0); r.connect(merger, 0, 1);
			l.start(); r.start();
		} else {
			// Isochronic: one tone, its volume pulsed at the beat rate.
			var tone = osc(CARRIER), amp = ctx.createGain(); amp.gain.value = 0.5; nodes.push(amp);
			var lfo = osc(beat), depth = ctx.createGain(); depth.gain.value = 0.5; nodes.push(depth);
			lfo.connect(depth); depth.connect(amp.gain);
			tone.connect(amp); amp.connect(tones);
			tone.start(); lfo.start();
		}
		if (form.elements.bed.value !== 'none') {
			var src = ctx.createBufferSource(); src.buffer = noiseBuffer(form.elements.bed.value); src.loop = true;
			var bed = ctx.createGain(); bed.gain.value = 0.5;
			var lp = ctx.createBiquadFilter(); lp.type = 'lowpass'; lp.frequency.value = 900;
			src.connect(lp); lp.connect(bed); bed.connect(master);
			nodes.push(src, bed, lp); src.start();
		}
	}
	function teardown() {
		nodes.forEach(function (n) { try { if (n.stop) n.stop(); } catch (e) {} try { n.disconnect(); } catch (e) {} });
		nodes = [];
	}
	function volume() { return (+form.elements.vol.value / 100) * 0.6; }

	function start() {
		if (!ctx) { ctx = new Ctx(); master = ctx.createGain(); master.gain.value = 0; master.connect(ctx.destination); }
		if (ctx.state === 'suspended') ctx.resume();
		teardown(); build();
		master.gain.cancelScheduledValues(ctx.currentTime);
		master.gain.setTargetAtTime(volume(), ctx.currentTime, 0.6); // gentle fade-in
		var mins = +form.elements.mins.value;
		endsAt = mins ? Date.now() + mins * 60000 : 0;
		playBtn.textContent = '■ Stop';
		playBtn.setAttribute('aria-pressed', 'true');
		btn.classList.add('is-on');
		pill.hidden = false;
		clearInterval(ticker); ticker = setInterval(tick, 1000); tick();
	}
	function stop() {
		if (ctx && master) {
			master.gain.setTargetAtTime(0, ctx.currentTime, 0.25);
			setTimeout(function () { teardown(); if (ctx && ctx.state === 'running') ctx.suspend(); }, 900);
		}
		clearInterval(ticker); ticker = null;
		playBtn.textContent = '▶ Play';
		playBtn.setAttribute('aria-pressed', 'false');
		btn.classList.remove('is-on');
		pill.hidden = true;
	}
	function playing() { return !!ticker; }
	function tick() {
		var text = '🎧 ' + label();
		if (endsAt) {
			var left = Math.max(0, Math.round((endsAt - Date.now()) / 1000));
			text += ' · ' + Math.floor(left / 60) + ':' + ('0' + (left % 60)).slice(-2);
			if (!left) {
				stop();
				if (window.VS && window.VS.celebrate) window.VS.celebrate('Focus session done! 🎯', 'Great work. Take a 5-minute break, then go again.', 'cheer', 0);
				return;
			}
		}
		pill.querySelector('.focus-pill-text').textContent = text;
	}

	/* ── UI ──────────────────────────────────────────────────────────── */
	function setOpen(on) {
		panel.hidden = !on;
		btn.setAttribute('aria-expanded', on ? 'true' : 'false');
	}
	btn.addEventListener('click', function () { setOpen(panel.hidden); });
	$('[data-focus-close]').addEventListener('click', function () { setOpen(false); btn.focus(); });
	document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !panel.hidden) setOpen(false); });

	form.addEventListener('input', function (e) {
		syncForm();
		if (e.target.name === 'vol' && playing()) master.gain.setTargetAtTime(volume(), ctx.currentTime, 0.1);
	});
	form.addEventListener('change', function (e) {
		syncForm();
		// Changing the sound itself restarts it smoothly; the timer keeps running.
		if (playing() && ['mode', 'beta', 'out', 'bed'].indexOf(e.target.name) !== -1) {
			var keep = endsAt; teardown(); build(); endsAt = keep; tick();
		}
		if (playing() && e.target.name === 'mins') { var m = +form.elements.mins.value; endsAt = m ? Date.now() + m * 60000 : 0; tick(); }
	});
	form.addEventListener('submit', function (e) { e.preventDefault(); if (playing()) stop(); else start(); });
	pill.querySelector('.focus-pill-stop').addEventListener('click', stop);
	pill.querySelector('.focus-pill-text').addEventListener('click', function () { setOpen(true); panel.scrollIntoView({ block: 'nearest', behavior: 'smooth' }); });
	syncForm();
})();
