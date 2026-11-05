/**
 * Study Music (5.0): lo-fi beats, rain and calm pads made live with the Web
 * Audio API (no audio files, no streaming, nothing sent anywhere), with a
 * visualiser, a mixer and a Pomodoro timer.
 *
 * Nothing plays until the visitor presses Play. Settings and the day's
 * Pomodoro count are remembered on this device only.
 */
(function () {
	'use strict';

	var root = document.querySelector('[data-music]');
	var Ctx = window.AudioContext || window.webkitAudioContext;
	if (!root) return;
	var $ = function (s) { return root.querySelector(s); };
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function load() { try { return JSON.parse(localStorage.getItem('vs-music') || '{}') || {}; } catch (e) { return {}; } }
	function save() { try { localStorage.setItem('vs-music', JSON.stringify(prefs)); } catch (e) {} }
	var prefs = load();

	/* ── Stations (MIDI note numbers for chords) ──────────────────────────── */
	var STATIONS = {
		lofi: { name: 'Lo-fi Focus', emoji: '☕', bpm: 76, bars: 1, wave: 'triangle', cutoff: 1500, mel: 0.35, drums: 'lofi', mix: { music: 70, drums: 55, rain: 15, vinyl: 45 }, hue: 265,
			prog: [[62, 65, 69, 72], [55, 59, 62, 65], [60, 64, 67, 71], [57, 60, 64, 67]], scale: [0, 2, 4, 7, 9] },
		rain: { name: 'Rainy Night', emoji: '🌧️', bpm: 62, bars: 2, wave: 'sine', cutoff: 1100, mel: 0.45, drums: null, mix: { music: 60, drums: 0, rain: 70, vinyl: 10 }, hue: 205,
			prog: [[57, 60, 64, 67], [53, 57, 60, 64], [60, 64, 67, 71], [55, 59, 62, 67]], scale: [0, 3, 5, 7, 10] },
		space: { name: 'Deep Space', emoji: '🌌', bpm: 48, bars: 2, wave: 'sawtooth', cutoff: 650, mel: 0.12, drums: null, mix: { music: 70, drums: 0, rain: 0, vinyl: 0 }, hue: 285,
			prog: [[50, 57, 62, 64], [48, 55, 60, 62], [46, 53, 58, 62], [48, 55, 60, 67]], scale: [0, 2, 5, 7, 9] },
		cafe: { name: 'Café Chill', emoji: '🥐', bpm: 90, bars: 1, wave: 'triangle', cutoff: 2200, mel: 0.4, drums: 'brush', mix: { music: 65, drums: 45, rain: 0, vinyl: 30 }, hue: 25,
			prog: [[65, 69, 72, 76], [62, 65, 69, 72], [67, 71, 74, 77], [60, 64, 67, 71]], scale: [0, 2, 4, 7, 9] },
		noise: { name: 'Brown Noise', emoji: '🌊', bpm: 60, bars: 1, wave: 'sine', cutoff: 800, mel: 0, drums: null, mix: { music: 0, drums: 0, rain: 75, vinyl: 0 }, hue: 160,
			prog: null, brown: true }
	};
	var station = STATIONS[prefs.station] ? prefs.station : 'lofi';
	var mix = Object.assign({}, STATIONS[station].mix, prefs.mix || {});
	var master = typeof prefs.vol === 'number' ? prefs.vol : 60;

	/* ── Audio graph ──────────────────────────────────────────────────────── */
	var ctx, out, analyser, bus = {}, filt, delay, fb, noiseW, noiseB, crackle, rainSrc, vinylSrc, rainLP, timer = 0, nextTime = 0, step = 0, bar = 0, playing = false;
	function hz(m) { return 440 * Math.pow(2, (m - 69) / 12); }
	function noiseBuffer(kind, secs) {
		var len = ctx.sampleRate * secs, b = ctx.createBuffer(1, len, ctx.sampleRate), d = b.getChannelData(0), last = 0;
		for (var i = 0; i < len; i++) {
			var w = Math.random() * 2 - 1;
			if (kind === 'brown') { last = (last + 0.02 * w) / 1.02; d[i] = last * 3.5; }
			else if (kind === 'crackle') { d[i] = Math.random() < 0.0004 ? (Math.random() * 2 - 1) * 0.8 : w * 0.012; }
			else d[i] = w;
		}
		return b;
	}
	function build() {
		ctx = new Ctx();
		out = ctx.createGain(); out.gain.value = master / 100;
		var comp = ctx.createDynamicsCompressor(); comp.threshold.value = -18; comp.ratio.value = 4;
		analyser = ctx.createAnalyser(); analyser.fftSize = 256; analyser.smoothingTimeConstant = 0.82;
		out.connect(comp); comp.connect(analyser); analyser.connect(ctx.destination);
		['music', 'drums', 'rain', 'vinyl'].forEach(function (k) { bus[k] = ctx.createGain(); bus[k].connect(out); });
		filt = ctx.createBiquadFilter(); filt.type = 'lowpass'; filt.Q.value = 0.7; filt.connect(bus.music);
		delay = ctx.createDelay(1); delay.delayTime.value = 0.36; fb = ctx.createGain(); fb.gain.value = 0.28;
		var wet = ctx.createGain(); wet.gain.value = 0.22;
		filt.connect(delay); delay.connect(fb); fb.connect(delay); delay.connect(wet); wet.connect(bus.music);
		noiseW = noiseBuffer('white', 2); noiseB = noiseBuffer('brown', 4); crackle = noiseBuffer('crackle', 3);
		rainLP = ctx.createBiquadFilter(); rainLP.type = 'lowpass'; rainLP.frequency.value = 1400;
		var rainHP = ctx.createBiquadFilter(); rainHP.type = 'highpass'; rainHP.frequency.value = 250;
		rainLP.connect(rainHP); rainHP.connect(bus.rain);
		vinylSrc = ctx.createBufferSource(); vinylSrc.buffer = crackle; vinylSrc.loop = true;
		var vHP = ctx.createBiquadFilter(); vHP.type = 'highpass'; vHP.frequency.value = 900;
		vinylSrc.connect(vHP); vHP.connect(bus.vinyl); vinylSrc.start();
	}
	function setRain() {
		if (rainSrc) { try { rainSrc.stop(); } catch (e) {} rainSrc.disconnect(); }
		var S = STATIONS[station];
		rainSrc = ctx.createBufferSource(); rainSrc.buffer = S.brown ? noiseB : noiseW; rainSrc.loop = true;
		rainLP.frequency.value = S.brown ? 600 : 1400;
		rainSrc.connect(rainLP); rainSrc.start();
	}
	function applyMix() {
		if (!ctx) return;
		var t = ctx.currentTime;
		bus.music.gain.setTargetAtTime(mix.music / 100 * 0.5, t, 0.2);
		bus.drums.gain.setTargetAtTime(mix.drums / 100 * 0.9, t, 0.2);
		bus.rain.gain.setTargetAtTime(mix.rain / 100 * 0.35, t, 0.2);
		bus.vinyl.gain.setTargetAtTime(mix.vinyl / 100 * 0.6, t, 0.2);
		out.gain.setTargetAtTime(master / 100, t, 0.1);
		filt.frequency.setTargetAtTime(STATIONS[station].cutoff, t, 0.3);
	}

	/* ── Instruments ──────────────────────────────────────────────────────── */
	function pad(note, t, len, wave) {
		[0, 7].forEach(function (det) {
			var o = ctx.createOscillator(), g = ctx.createGain();
			o.type = wave; o.frequency.value = hz(note); o.detune.value = det - 3.5;
			g.gain.setValueAtTime(0, t); g.gain.linearRampToValueAtTime(0.06, t + Math.min(0.6, len * 0.3)); g.gain.setTargetAtTime(0, t + len * 0.85, len * 0.08);
			o.connect(g); g.connect(filt); o.start(t); o.stop(t + len + 1);
		});
	}
	function pluck(note, t, vel) {
		var o = ctx.createOscillator(), o2 = ctx.createOscillator(), g = ctx.createGain();
		o.type = 'sine'; o.frequency.value = hz(note); o2.type = 'triangle'; o2.frequency.value = hz(note) * 2;
		var g2 = ctx.createGain(); g2.gain.value = 0.15;
		g.gain.setValueAtTime(0, t); g.gain.linearRampToValueAtTime(0.11 * vel, t + 0.008); g.gain.exponentialRampToValueAtTime(0.0008, t + 1.6);
		o.connect(g); o2.connect(g2); g2.connect(g); g.connect(filt);
		o.start(t); o2.start(t); o.stop(t + 1.7); o2.stop(t + 1.7);
	}
	function bass(note, t, len) {
		var o = ctx.createOscillator(), g = ctx.createGain();
		o.type = 'sine'; o.frequency.value = hz(note - 24);
		g.gain.setValueAtTime(0, t); g.gain.linearRampToValueAtTime(0.16, t + 0.02); g.gain.setTargetAtTime(0, t + len * 0.8, 0.08);
		o.connect(g); g.connect(bus.music); o.start(t); o.stop(t + len + 0.6);
	}
	function kick(t, v) {
		var o = ctx.createOscillator(), g = ctx.createGain();
		o.frequency.setValueAtTime(130, t); o.frequency.exponentialRampToValueAtTime(42, t + 0.18);
		g.gain.setValueAtTime(0.9 * v, t); g.gain.exponentialRampToValueAtTime(0.001, t + 0.45);
		o.connect(g); g.connect(bus.drums); o.start(t); o.stop(t + 0.5);
	}
	function hiss(t, v, freq, type, dur) {
		var s = ctx.createBufferSource(), f = ctx.createBiquadFilter(), g = ctx.createGain();
		s.buffer = noiseW; f.type = type; f.frequency.value = freq;
		g.gain.setValueAtTime(v, t); g.gain.exponentialRampToValueAtTime(0.001, t + dur);
		s.connect(f); f.connect(g); g.connect(bus.drums); s.start(t, Math.random()); s.stop(t + dur + 0.05);
	}

	/* ── Sequencer (16 steps per bar, scheduled slightly ahead) ───────────── */
	function schedule() {
		var S = STATIONS[station], spb = 60 / S.bpm / 4, ahead = document.hidden ? 2.5 : 0.25;
		while (nextTime < ctx.currentTime + ahead) {
			var t = nextTime, s = step % 16, swing = (s % 2) ? spb * 0.18 : 0;
			if (S.prog) {
				var chord = S.prog[Math.floor(bar / S.bars) % S.prog.length], barLen = spb * 16 * S.bars;
				if (s === 0 && bar % S.bars === 0) { chord.forEach(function (n) { pad(n, t, barLen, S.wave); }); bass(chord[0], t, barLen / 2); }
				if (s === 8 && S.drums) bass(chord[0], t, spb * 8);
				if (s % 2 === 0 && Math.random() < S.mel) {
					var root = chord[0] % 12 + 72, deg = S.scale[Math.floor(Math.random() * S.scale.length)];
					pluck(root + deg - (Math.random() < 0.3 ? 12 : 0), t + swing, 0.6 + Math.random() * 0.4);
				}
			}
			if (S.drums === 'lofi') {
				if (s === 0 || s === 10 || (s === 7 && Math.random() < 0.3)) kick(t, s ? 0.7 : 1);
				if (s === 4 || s === 12) hiss(t, 0.35, 1800, 'bandpass', 0.18);
				if (s % 2 === 0) hiss(t + swing, s % 4 ? 0.06 : 0.1, 7000, 'highpass', 0.05);
			} else if (S.drums === 'brush') {
				if (s === 0 || s === 8) kick(t, 0.55);
				if (s === 4 || s === 12) hiss(t, 0.16, 2500, 'bandpass', 0.3);
				hiss(t + swing, 0.035 + (s % 4 === 2 ? 0.03 : 0), 5000, 'highpass', 0.09);
			}
			nextTime += spb; step++;
			if (step % 16 === 0) bar++;
		}
	}

	/* ── Controls ─────────────────────────────────────────────────────────── */
	var playBtn = $('[data-play]'), nowEl = $('[data-now]'), stage = $('[data-stage]');
	function paintStation() {
		var S = STATIONS[station];
		nowEl.textContent = S.emoji + ' ' + S.name;
		root.style.setProperty('--mu-hue', S.hue);
		Array.prototype.forEach.call(root.querySelectorAll('[data-station]'), function (b) { b.setAttribute('aria-pressed', b.getAttribute('data-station') === station ? 'true' : 'false'); });
		Array.prototype.forEach.call(root.querySelectorAll('[data-mix]'), function (r) { r.value = mix[r.getAttribute('data-mix')]; });
	}
	function play() {
		if (!Ctx) return;
		if (!ctx) { build(); setRain(); }
		if (ctx.state === 'suspended') ctx.resume();
		applyMix();
		nextTime = ctx.currentTime + 0.1; step = 0; bar = 0;
		clearInterval(timer); timer = setInterval(schedule, 100); schedule();
		playing = true; playBtn.setAttribute('aria-pressed', 'true'); playBtn.textContent = '⏸ Pause'; root.classList.add('is-playing');
		if ('mediaSession' in navigator && window.MediaMetadata) {
			try { navigator.mediaSession.metadata = new MediaMetadata({ title: STATIONS[station].name, artist: 'VitalStack Study Music' }); } catch (e) {}
		}
		draw();
	}
	function pause() {
		clearInterval(timer); playing = false;
		if (ctx) ctx.suspend();
		playBtn.setAttribute('aria-pressed', 'false'); playBtn.textContent = '▶ Play'; root.classList.remove('is-playing');
	}
	playBtn.addEventListener('click', function () { playing ? pause() : play(); });
	root.addEventListener('click', function (e) {
		var b = e.target.closest('[data-station]');
		if (!b) return;
		station = b.getAttribute('data-station'); mix = Object.assign({}, STATIONS[station].mix);
		prefs.station = station; prefs.mix = mix; save(); paintStation();
		if (ctx) { setRain(); applyMix(); bar = 0; }
		if (!playing) play();
	});
	Array.prototype.forEach.call(root.querySelectorAll('[data-mix]'), function (r) {
		r.addEventListener('input', function () { mix[r.getAttribute('data-mix')] = +r.value; prefs.mix = mix; save(); applyMix(); });
	});
	var vol = $('[data-vol]');
	vol.value = master;
	vol.addEventListener('input', function () { master = +vol.value; prefs.vol = master; save(); applyMix(); });
	if (!Ctx) { playBtn.disabled = true; nowEl.textContent = 'Your browser does not support Web Audio.'; }

	/* ── Visualiser ───────────────────────────────────────────────────────── */
	var canvas = $('[data-viz]'), g2 = canvas.getContext('2d'), data = new Uint8Array(128), raf = 0;
	function size() {
		var r = stage.getBoundingClientRect(), dpr = Math.min(2, window.devicePixelRatio || 1);
		canvas.width = Math.max(1, Math.round(r.width * dpr)); canvas.height = Math.max(1, Math.round(r.height * dpr));
		g2.setTransform(dpr, 0, 0, dpr, 0, 0);
		if (!playing) frame(true);
	}
	function frame(idle) {
		var w = canvas.width / Math.min(2, window.devicePixelRatio || 1), h = canvas.height / Math.min(2, window.devicePixelRatio || 1);
		var hue = STATIONS[station].hue, cx = w / 2, cy = h / 2, R = Math.min(w, h) * 0.24, n = 64, tm = Date.now() / 1000;
		g2.clearRect(0, 0, w, h);
		if (analyser && !idle) analyser.getByteFrequencyData(data); else for (var z = 0; z < 128; z++) data[z] = 0;
		var avg = 0; for (var k = 0; k < 32; k++) avg += data[k]; avg /= 32 * 255;
		var glow = g2.createRadialGradient(cx, cy, R * 0.2, cx, cy, R * (2.2 + avg));
		glow.addColorStop(0, 'hsla(' + hue + ',90%,65%,' + (0.35 + avg * 0.5) + ')'); glow.addColorStop(1, 'hsla(' + hue + ',90%,50%,0)');
		g2.fillStyle = glow; g2.fillRect(0, 0, w, h);
		for (var i = 0; i < n; i++) {
			var v = data[Math.floor(i * 1.6)] / 255, a = (i / n) * Math.PI * 2 + (reduce ? 0 : tm * 0.15);
			var len = 6 + v * R * 1.1 + (idle ? Math.sin(i * 0.6 + tm) * 2 : 0);
			g2.strokeStyle = 'hsla(' + (hue + i * 2) + ',85%,' + (60 + v * 20) + '%,0.9)';
			g2.lineWidth = Math.max(2, R * 0.05); g2.lineCap = 'round';
			g2.beginPath(); g2.moveTo(cx + Math.cos(a) * R, cy + Math.sin(a) * R); g2.lineTo(cx + Math.cos(a) * (R + len), cy + Math.sin(a) * (R + len)); g2.stroke();
		}
		g2.beginPath(); g2.arc(cx, cy, R * (0.92 + avg * 0.12), 0, Math.PI * 2);
		g2.fillStyle = 'hsla(' + hue + ',60%,18%,0.85)'; g2.fill();
	}
	function draw() {
		cancelAnimationFrame(raf);
		(function loop() { frame(false); if (playing && !document.hidden) raf = requestAnimationFrame(loop); })();
	}
	document.addEventListener('visibilitychange', function () { if (!document.hidden && playing) draw(); });
	window.addEventListener('resize', size);
	paintStation(); size();

	/* ── Pomodoro timer ───────────────────────────────────────────────────── */
	var MODES = { focus: 25, short: 5, long: 15 }, mode = 'focus', left = MODES.focus * 60, tick = 0, running = false, baseTitle = document.title;
	var tEl = $('[data-time]'), ring = $('[data-ring]'), tBtn = $('[data-tstart]'), doneEl = $('[data-done]');
	var today = new Date().toISOString().slice(0, 10);
	if (prefs.day !== today) { prefs.day = today; prefs.done = 0; }
	function fmt(s) { return ('0' + Math.floor(s / 60)).slice(-2) + ':' + ('0' + s % 60).slice(-2); }
	function paintTimer() {
		tEl.textContent = fmt(left);
		var full = MODES[mode] * 60, C = 2 * Math.PI * 54;
		ring.style.strokeDasharray = C; ring.style.strokeDashoffset = C * (1 - left / full);
		doneEl.textContent = String(prefs.done || 0);
		document.title = running ? '(' + fmt(left) + ') ' + baseTitle : baseTitle;
		Array.prototype.forEach.call(root.querySelectorAll('[data-mode]'), function (b) { b.setAttribute('aria-pressed', b.getAttribute('data-mode') === mode ? 'true' : 'false'); });
	}
	function chime() {
		if (!Ctx) return;
		if (!ctx) { build(); setRain(); applyMix(); }
		if (ctx.state === 'suspended') ctx.resume();
		[72, 76, 79].forEach(function (n, i) { pluck(n, ctx.currentTime + 0.05 + i * 0.18, 1); });
	}
	function setMode(m) { mode = m; left = MODES[m] * 60; stopTimer(); paintTimer(); }
	function stopTimer() { clearInterval(tick); running = false; tBtn.textContent = '▶ Start'; tBtn.setAttribute('aria-pressed', 'false'); paintTimer(); }
	var endAt = 0;
	function startTimer() {
		running = true; tBtn.textContent = '⏸ Pause'; tBtn.setAttribute('aria-pressed', 'true');
		endAt = Date.now() + left * 1000;
		clearInterval(tick);
		tick = setInterval(function () {
			left = Math.max(0, Math.round((endAt - Date.now()) / 1000));
			if (left <= 0) {
				stopTimer(); chime();
				if (mode === 'focus') {
					prefs.done = (prefs.done || 0) + 1; save();
					if (window.VS && window.VS.addXP) window.VS.addXP('viz', 'study-music');
					setMode(prefs.done % 4 === 0 ? 'long' : 'short');
				} else setMode('focus');
				return;
			}
			paintTimer();
		}, 250);
		paintTimer();
	}
	tBtn.addEventListener('click', function () { running ? stopTimer() : startTimer(); });
	$('[data-treset]').addEventListener('click', function () { setMode(mode); });
	Array.prototype.forEach.call(root.querySelectorAll('[data-mode]'), function (b) { b.addEventListener('click', function () { setMode(b.getAttribute('data-mode')); }); });
	save(); paintTimer();
	root.classList.add('is-ready');
})();
