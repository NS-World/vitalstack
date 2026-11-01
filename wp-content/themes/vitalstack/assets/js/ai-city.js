/*
 * AI City (4.12): a 3D miniature island where every district is one Gen AI or
 * agent idea. The camera tours the districts as the reader scrolls. Text lives
 * in the page HTML; this file only draws the world behind it.
 * Needs three.min.js (global THREE). Without WebGL the page keeps its gradient sky.
 */
(function () {
	'use strict';
	var root = document.querySelector('[data-ai-city]');
	if (!root) return;

	// Nova the guide, in every card.
	function drawNova() {
		if (!window.VSNova) return;
		root.querySelectorAll('[data-nova]').forEach(function (el) {
			var big = el.classList.contains('aic-guide');
			el.innerHTML = window.VSNova.svg(el.getAttribute('data-nova'), big ? 130 : 84);
		});
	}
	drawNova();

	var stopsEl = root.querySelector('[data-aic-stops]');
	var STOPS = [];
	try { STOPS = JSON.parse(stopsEl.textContent); } catch (e) { return; }
	var SPREAD = 1.5; // districts need room to breathe
	STOPS.forEach(function (s) { s.x *= SPREAD; s.z *= SPREAD; });
	var holder = root.querySelector('[data-aic-canvas]');
	if (!window.THREE || !holder) return;
	var T = window.THREE;

	var testCanvas = document.createElement('canvas');
	var gl = testCanvas.getContext('webgl2') || testCanvas.getContext('webgl');
	if (!gl) return;

	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var small = window.innerWidth < 900;

	var renderer = new T.WebGLRenderer({ antialias: !small, alpha: true, powerPreference: 'high-performance' });
	renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, small ? 1.5 : 1.75));
	renderer.outputColorSpace = T.SRGBColorSpace;
	renderer.shadowMap.enabled = !small;
	renderer.shadowMap.type = T.PCFSoftShadowMap;
	holder.appendChild(renderer.domElement);
	root.classList.add('is-3d');

	var scene = new T.Scene();
	scene.fog = new T.Fog(0xeaf3fb, 260, 520);

	var VIEW = 120;
	var camera = new T.OrthographicCamera(-1, 1, 1, -1, -500, 1000);
	var camDir = new T.Vector3(1, 1.05, 1.15).normalize();

	// Light: soft sky + warm sun.
	scene.add(new T.HemisphereLight(0xffffff, 0xb9d7c4, 1.05));
	var sun = new T.DirectionalLight(0xfff3e0, 1.5);
	sun.position.set(60, 110, 40);
	sun.castShadow = !small;
	sun.shadow.mapSize.set(2048, 2048);
	var sc = sun.shadow.camera; sc.left = -90; sc.right = 90; sc.top = 90; sc.bottom = -90; sc.near = 10; sc.far = 300;
	sun.shadow.bias = -0.0005;
	scene.add(sun);

	// Helpers
	var mats = {};
	function mat(color, opts) {
		var key = color + JSON.stringify(opts || {});
		if (!mats[key]) {
			var o = { color: color, roughness: 0.75, metalness: 0.05, flatShading: false };
			for (var k in opts) o[k] = opts[k];
			mats[key] = new T.MeshStandardMaterial(o);
		}
		return mats[key];
	}
	function glow(color, strength) { return mat(color, { emissive: color, emissiveIntensity: strength || 0.8, roughness: 0.4 }); }
	function mesh(geo, m, x, y, z) {
		var o = new T.Mesh(geo, m);
		o.position.set(x || 0, y || 0, z || 0);
		o.castShadow = !small; o.receiveShadow = !small;
		return o;
	}
	function box(w, h, d, color, x, y, z, r) {
		var g = new T.BoxGeometry(w, h, d);
		var o = mesh(g, typeof color === 'object' ? color : mat(color), x, (y || 0) + h / 2, z);
		if (r) o.rotation.y = r;
		return o;
	}
	function cyl(rt, rb, h, color, x, y, z, seg) {
		return mesh(new T.CylinderGeometry(rt, rb, h, seg || 24), typeof color === 'object' ? color : mat(color), x, (y || 0) + h / 2, z);
	}
	function sphere(r, color, x, y, z) { return mesh(new T.SphereGeometry(r, 24, 16), typeof color === 'object' ? color : mat(color), x, y, z); }
	function cone(r, h, color, x, y, z, seg) { return mesh(new T.ConeGeometry(r, h, seg || 20), typeof color === 'object' ? color : mat(color), x, (y || 0) + h / 2, z); }

	function label(text, color) {
		var c = document.createElement('canvas'); c.width = 512; c.height = 128;
		var x = c.getContext('2d');
		x.font = '700 54px system-ui, -apple-system, Segoe UI, sans-serif';
		var w = Math.min(500, x.measureText(text).width + 56);
		x.fillStyle = 'rgba(255,255,255,0.94)';
		var rx = (512 - w) / 2;
		x.beginPath(); if (x.roundRect) x.roundRect(rx, 24, w, 80, 40); else x.rect(rx, 24, w, 80); x.fill();
		x.fillStyle = color || '#0a7d4f'; x.textAlign = 'center'; x.textBaseline = 'middle';
		x.fillText(text, 256, 66);
		var tex = new T.CanvasTexture(c); tex.colorSpace = T.SRGBColorSpace; tex.anisotropy = 4;
		var sp = new T.Sprite(new T.SpriteMaterial({ map: tex, transparent: true, depthWrite: false, fog: false }));
		sp.scale.set(26, 6.5, 1);
		return sp;
	}

	var anim = []; // functions(t, dt)
	var districts = {};
	var C = { green: 0x34c987, deep: 0x0a7d4f, mint: 0xbdf0d8, teal: 0x2dd4bf, sky: 0x60a5fa, violet: 0xa78bfa, pink: 0xf472b6, amber: 0xfbbf24, orange: 0xfb923c, red: 0xef4444, white: 0xf8fafc, cream: 0xfff7ed, slate: 0x334155, navy: 0x1e293b, stone: 0xe2e8f0 };

	// ── Island, water, roads ───────────────────────────────────────────────
	var water = mesh(new T.CircleGeometry(400, 64), mat(0xbfe4f5, { roughness: 0.25, metalness: 0.1 }), 0, -1.6, 0);
	water.rotation.x = -Math.PI / 2; water.castShadow = false; scene.add(water);
	var island = cyl(118, 123, 4, mat(0xdff3e6), 0, -4, 9, 72); island.scale.z = 0.86; scene.add(island);
	var beach = cyl(125, 129, 2.2, mat(0xf3e7c9), 0, -4.6, 9, 72); beach.scale.z = 0.86; scene.add(beach);
	var plaza = cyl(13, 13, 0.4, mat(0xf1f5f9), -2, 0, 0, 40); scene.add(plaza);

	function road(ax, az, bx, bz) {
		var dx = bx - ax, dz = bz - az, len = Math.hypot(dx, dz);
		var r = box(len, 0.25, 4.2, mat(0xf8fafc), (ax + bx) / 2, 0, (az + bz) / 2, -Math.atan2(dz, dx));
		r.castShadow = false; scene.add(r);
		var line = box(len, 0.3, 0.4, mat(0xcbd5e1), (ax + bx) / 2, 0, (az + bz) / 2, -Math.atan2(dz, dx));
		line.castShadow = false; scene.add(line);
	}

	function tree(x, z, s) {
		s = s || 1;
		var g = new T.Group();
		g.add(cyl(0.5 * s, 0.6 * s, 2.2 * s, mat(0x9a6b4f), 0, 0, 0, 8));
		g.add(cone(2.2 * s, 4.6 * s, mat(0x2fb377), 0, 1.6 * s, 0, 10));
		g.add(cone(1.7 * s, 3.4 * s, mat(0x3fcf8a), 0, 3.6 * s, 0, 10));
		g.position.set(x, 0, z);
		scene.add(g);
	}

	// ── Districts ──────────────────────────────────────────────────────────
	var build = {
		'data-mines': function (g) {
			g.add(cone(9, 10, mat(0xc7b299), -4, 0, -3, 7));
			g.add(cone(7, 7, mat(0xd6c4ac), 5, 0, -5, 7));
			var cols = [C.sky, C.pink, C.amber, C.violet, C.green, C.orange];
			for (var i = 0; i < 18; i++) g.add(box(1.8, 1.8, 1.8, cols[i % 6], -8 + (i % 6) * 2.1, Math.floor(i / 6) * 1.9, 6 - Math.floor(i / 6) * 0.6));
			var rail = mesh(new T.TorusGeometry(7, 0.18, 6, 48), mat(0x94a3b8), 2, 0.3, 4); rail.rotation.x = Math.PI / 2; g.add(rail);
			var cart = new T.Group(); cart.add(box(2.4, 1.4, 1.6, mat(0x64748b), 0, 0.4, 0)); cart.add(box(2, 0.8, 1.2, C.amber, 0, 1.6, 0)); g.add(cart);
			anim.push(function (t) { var a = t * 0.6; cart.position.set(2 + Math.cos(a) * 7, 0, 4 + Math.sin(a) * 7); cart.rotation.y = -a; });
		},
		'token-factory': function (g) {
			g.add(box(12, 8, 9, mat(0xfde68a), -4, 0, -2));
			g.add(box(12.4, 1, 9.4, mat(0xf59e0b), -4, 8, -2));
			g.add(cyl(1.2, 1.4, 9, mat(0x94a3b8), -7, 8, -4, 12));
			var smoke = []; for (var s = 0; s < 3; s++) { var p = sphere(1.3, mat(0xffffff, { transparent: true, opacity: 0.8 }), -7, 18, -4); g.add(p); smoke.push(p); }
			g.add(box(22, 1, 3.6, mat(0x475569), 6, 0.6, 6));
			var tiles = [], cols = [C.sky, C.pink, C.violet, C.green, C.orange];
			for (var i = 0; i < 7; i++) { var tl = box(2.2, 1.6, 2.2, cols[i % 5], 0, 1.6, 6); g.add(tl); tiles.push(tl); }
			anim.push(function (t) {
				tiles.forEach(function (tl, i) { var u = ((t * 0.35 + i / 7) % 1); tl.position.x = -4 + u * 20; tl.rotation.y = u * 3; });
				smoke.forEach(function (p, i) { var u = (t * 0.25 + i / 3) % 1; p.position.y = 17 + u * 9; p.position.x = -7 + u * 3; p.scale.setScalar(0.6 + u * 1.4); p.material.opacity = 0.8 * (1 - u); });
			});
		},
		'embedding-park': function (g) {
			var plate = box(26, 0.4, 22, mat(0xf0fdf4), 0, 0, 0); g.add(plate);
			for (var i = -2; i <= 2; i++) { g.add(box(26, 0.45, 0.15, mat(0xbbf7d0), 0, 0, i * 5)); g.add(box(0.15, 0.45, 22, mat(0xbbf7d0), i * 6, 0, 0)); }
			var clusters = [[-6, -4, C.violet], [6, -3, C.amber], [0, 6, C.sky]];
			var dots = [];
			var rnd = mulberry(7);
			clusters.forEach(function (c) {
				for (var k = 0; k < 9; k++) {
					var d = sphere(0.75, glow(c[2], 0.35), c[0] + (rnd() - 0.5) * 7, 2 + rnd() * 4, c[1] + (rnd() - 0.5) * 6);
					d.userData.y = d.position.y; d.userData.p = rnd() * 6; g.add(d); dots.push(d);
				}
			});
			anim.push(function (t) { dots.forEach(function (d) { d.position.y = d.userData.y + Math.sin(t * 1.4 + d.userData.p) * 0.6; }); });
		},
		'transformer-tower': function (g) {
			var cols = [C.sky, C.violet, C.pink, C.amber, C.green, C.teal];
			var bands = [];
			for (var i = 0; i < 10; i++) {
				g.add(cyl(5.5 - i * 0.18, 5.6 - i * 0.18, 3, mat(0xf8fafc), 0, i * 3.6, 0, 8));
				var b = cyl(5.75 - i * 0.18, 5.75 - i * 0.18, 0.6, glow(cols[i % 6], 0.6), 0, i * 3.6 + 3, 0, 8); g.add(b); bands.push(b);
			}
			g.add(cone(4, 6, mat(C.deep), 0, 36, 0, 8));
			var orb = sphere(1.4, glow(C.green, 1.2), 0, 43.5, 0); g.add(orb);
			var beams = [];
			for (var k = 0; k < 6; k++) {
				var a = k / 6 * Math.PI * 2;
				var beam = box(0.35, 26, 0.35, glow(cols[k], 1), Math.cos(a) * 9, 4, Math.sin(a) * 9);
				beam.castShadow = false; g.add(beam); beams.push(beam);
				g.add(sphere(1, glow(cols[k], 0.5), Math.cos(a) * 9, 4, Math.sin(a) * 9));
			}
			anim.push(function (t) {
				bands.forEach(function (b, i) { b.material.emissiveIntensity = 0.3 + 0.7 * Math.max(0, Math.sin(t * 3 - i * 0.6)); });
				beams.forEach(function (b, k) { b.scale.y = 0.4 + 0.6 * Math.abs(Math.sin(t * 1.5 + k)); b.position.y = 4 + b.scale.y * 13; });
				orb.position.y = 43.5 + Math.sin(t * 2) * 0.6;
			});
		},
		'training-gym': function (g) {
			g.add(box(14, 7, 10, mat(0xe0e7ff), -3, 0, -2));
			g.add(box(14.6, 0.8, 10.6, mat(0x6366f1), -3, 7, -2));
			var bars = [];
			[9, 7.4, 6, 4.6, 3.6, 2.8, 2.2].forEach(function (h, i) { var b = box(1.6, h, 1.6, glow(i < 2 ? C.red : (i < 4 ? C.amber : C.green), 0.25), 6 + i * 2.2, 0, 7); g.add(b); bars.push(b); });
			var ring = mesh(new T.TorusGeometry(4, 0.7, 10, 32), mat(C.violet), -3, 13, -2); g.add(ring);
			var db = new T.Group(); db.add(cyl(0.4, 0.4, 7, mat(0x64748b), 0, -3.5, 0, 8)); db.add(cyl(1.6, 1.6, 1, mat(0x1e293b), 0, -3.6, 0)); db.add(cyl(1.6, 1.6, 1, mat(0x1e293b), 0, 2.6, 0)); db.rotation.z = Math.PI / 2; db.position.set(-3, 9.5, 5); g.add(db);
			anim.push(function (t) { ring.rotation.y = t * 0.8; ring.rotation.x = Math.sin(t) * 0.3; db.position.y = 9.5 + Math.abs(Math.sin(t * 2)) * 2; bars.forEach(function (b, i) { b.scale.y = 1 + 0.08 * Math.sin(t * 2 + i); }); });
		},
		'prompt-cafe': function (g) {
			g.add(box(12, 7, 9, mat(C.cream), 0, 0, 0));
			for (var i = 0; i < 6; i++) g.add(box(2, 0.6, 3, i % 2 ? mat(C.white) : mat(C.pink), -5 + i * 2, 6.4, 5.5));
			g.add(box(12.6, 1, 9.6, mat(0xbe185d), 0, 7, 0));
			var bubble = new T.Group();
			var bb = sphere(3.4, mat(C.white), 0, 0, 0); bb.scale.set(1.5, 1, 0.6); bubble.add(bb);
			var tail = cone(1, 2.4, mat(C.white), -2, -3.6, 0, 8); tail.rotation.z = 0.6; bubble.add(tail);
			[-2, 0, 2].forEach(function (x) { bubble.add(sphere(0.6, mat(C.navy), x, 0, 2)); });
			bubble.position.set(0, 15, 0); g.add(bubble);
			[[-8, 8], [8, 8]].forEach(function (p) { g.add(cyl(1.8, 1.8, 0.3, mat(C.white), p[0], 2.6, p[1])); g.add(cyl(0.2, 0.2, 2.6, mat(0x94a3b8), p[0], 0, p[1], 8)); g.add(cyl(2.4, 0.2, 2.4, mat(C.amber), p[0], 4.4, p[1], 12)); });
			anim.push(function (t) { bubble.position.y = 15 + Math.sin(t * 1.6) * 0.8; bubble.rotation.y = Math.sin(t * 0.7) * 0.3; });
		},
		'rag-library': function (g) {
			g.add(box(16, 1.2, 11, mat(0xe2e8f0), 0, 0, 0));
			g.add(box(14, 8, 9, mat(0xfefce8), 0, 1.2, -0.5));
			for (var i = 0; i < 5; i++) g.add(cyl(0.6, 0.6, 8, mat(C.white), -6 + i * 3, 1.2, 4.6, 12));
			var roof = mesh(new T.CylinderGeometry(0.1, 10, 4, 4), mat(0x92400e), 0, 11.2, -0.5); roof.rotation.y = Math.PI / 4; roof.scale.z = 0.7; g.add(roof);
			var cols = [C.red, C.sky, C.green, C.amber, C.violet, C.pink];
			for (var s = 0; s < 3; s++) { g.add(box(7, 0.4, 2, mat(0x78350f), 13, s * 3.2, 0)); for (var b = 0; b < 6; b++) g.add(box(0.8, 2.4, 1.6, cols[(b + s) % 6], 10.2 + b * 1.1, s * 3.2 + 0.4, 0)); }
			var book = box(2.2, 0.6, 1.6, glow(C.sky, 0.6), 0, 0, 0); g.add(book);
			anim.push(function (t) { var u = (t * 0.3) % 1; book.position.set(13 - u * 13, 9 + Math.sin(u * Math.PI) * 8, 6 - u * 2); book.rotation.y = u * 6; });
		},
		'tool-workshop': function (g) {
			g.add(box(12, 6, 9, mat(0xfee2e2), 0, 0, 0));
			var roof = mesh(new T.CylinderGeometry(0.1, 8.5, 4, 4), mat(C.red), 0, 8, 0); roof.rotation.y = Math.PI / 4; g.add(roof);
			var wrench = new T.Group();
			wrench.add(box(1.2, 10, 0.8, mat(0x94a3b8, { metalness: 0.6, roughness: 0.3 }), 0, -5, 0));
			var head = mesh(new T.TorusGeometry(2, 0.8, 8, 20, Math.PI * 1.5), mat(0x94a3b8, { metalness: 0.6, roughness: 0.3 }), 0, 6, 0); head.rotation.z = Math.PI * 0.75; wrench.add(head);
			wrench.position.set(10, 8, 2); g.add(wrench);
			var gear = mesh(new T.TorusGeometry(2.6, 0.9, 6, 12), mat(C.amber), -9, 4, 4); g.add(gear);
			anim.push(function (t) { wrench.rotation.z = Math.sin(t * 1.2) * 0.5; wrench.rotation.y = t * 0.4; gear.rotation.z = t; });
		},
		'agent-hq': function (g) {
			g.add(cyl(6, 7, 14, mat(0xecfdf5), 0, 0, 0, 8));
			g.add(cyl(7.2, 7.2, 1, mat(C.deep), 0, 14, 0, 8));
			g.add(cyl(3, 4, 4, mat(C.white), 0, 15, 0, 16));
			var dish = mesh(new T.SphereGeometry(3.4, 20, 10, 0, Math.PI * 2, 0, Math.PI / 2.4), mat(C.white), 0, 21, 0); dish.rotation.x = Math.PI; g.add(dish);
			var beacon = sphere(1, glow(C.green, 1.3), 0, 22, 0); g.add(beacon);
			anim.push(function (t) { dish.rotation.z = Math.sin(t) * 0.4; beacon.material.emissiveIntensity = 0.6 + Math.abs(Math.sin(t * 3)); });
		},
		'memory-vault': function (g) {
			g.add(box(11, 9, 9, mat(0xcbd5e1, { metalness: 0.4, roughness: 0.35 }), 0, 0, 0));
			var door = mesh(new T.CylinderGeometry(3.2, 3.2, 0.8, 32), mat(0x94a3b8, { metalness: 0.7, roughness: 0.25 }), 0, 4.5, 4.9); door.rotation.x = Math.PI / 2; g.add(door);
			var wheel = mesh(new T.TorusGeometry(1.6, 0.25, 8, 24), mat(C.amber, { metalness: 0.6 }), 0, 4.5, 5.5); g.add(wheel);
			for (var i = 0; i < 3; i++) for (var j = 0; j < 2; j++) g.add(box(2.6, 1.6, 0.6, glow([C.violet, C.sky, C.green][i], 0.25), 7.6 + j * 2.9, i * 2.2, 3));
			anim.push(function (t) { wheel.rotation.z = t * 0.9; });
		},
		'mcp-port': function (g) {
			g.add(box(26, 1, 6, mat(0xb45309), 0, -0.4, 0));
			for (var i = 0; i < 6; i++) g.add(cyl(0.5, 0.5, 4, mat(0x78350f), -11 + i * 4.4, -3, 3, 8));
			var crane = new T.Group(); crane.add(box(1.2, 14, 1.2, mat(C.amber), 0, 0, 0)); crane.add(box(12, 1, 1, mat(C.amber), 4, 13, 0)); var hook = box(0.3, 4, 0.3, mat(0x475569), 9, 9, 0); crane.add(hook);
			crane.position.set(-9, 0.6, -1); g.add(crane);
			var plug = new T.Group(); plug.add(box(4, 3, 2, mat(C.navy), 0, 0, 0)); plug.add(box(0.6, 1.6, 0.6, mat(0xe2e8f0), -0.9, 3, 0)); plug.add(box(0.6, 1.6, 0.6, mat(0xe2e8f0), 0.9, 3, 0));
			plug.position.set(7, 1, 0); g.add(plug);
			var boats = [];
			[C.sky, C.pink, C.violet].forEach(function (c, i) {
				var b = new T.Group(); var hull = box(5, 1.6, 2.4, mat(C.white), 0, 0, 0); b.add(hull); b.add(box(2.2, 2, 1.8, mat(c), 0, 1.6, 0)); b.userData.p = i / 3; g.add(b); boats.push(b);
			});
			[[-34, 22], [0, 30], [34, 22]].forEach(function (p) { var isle = cyl(6, 7, 2, mat(0xdff3e6), p[0], -2.4, p[1], 24); g.add(isle); g.add(cone(2.2, 5, mat(0x2fb377), p[0], -0.4, p[1], 10)); });
			anim.push(function (t) {
				crane.rotation.y = Math.sin(t * 0.5) * 0.6;
				boats.forEach(function (b, i) {
					var targets = [[-34, 22], [0, 30], [34, 22]][i];
					var u = (t * 0.08 + b.userData.p) % 1, k = u < 0.5 ? u * 2 : 2 - u * 2;
					b.position.set(targets[0] * k, -1 + Math.sin(t * 2 + i) * 0.2, 6 + (targets[1] - 8) * k);
					b.rotation.y = Math.atan2(targets[0], targets[1] - 6) + (u < 0.5 ? 0 : Math.PI) - Math.PI / 2;
				});
			});
		},
		'guardrail-gate': function (g) {
			g.add(box(6, 10, 6, mat(0xe2e8f0), -9, 0, 0)); g.add(box(6, 10, 6, mat(0xe2e8f0), 9, 0, 0));
			g.add(box(24, 3, 6, mat(0xcbd5e1), 0, 10, 0));
			for (var i = 0; i < 6; i++) g.add(box(2, 2, 6.2, mat(0xe2e8f0), -10 + i * 4, 13, 0));
			var shield = new T.Group();
			var face = mesh(new T.CylinderGeometry(4, 4, 0.8, 6), glow(C.green, 0.35), 0, 0, 0); face.rotation.x = Math.PI / 2; shield.add(face);
			var tick = box(0.9, 3.4, 0.4, mat(C.white), 0.8, -1.4, 0.6); tick.rotation.z = -0.6; shield.add(tick);
			var tick2 = box(0.9, 1.8, 0.4, mat(C.white), -1.2, -1.8, 0.6); tick2.rotation.z = 0.8; shield.add(tick2);
			shield.position.set(0, 21, 0); g.add(shield);
			var bar = box(14, 0.8, 0.8, mat(C.red), 0, 0, 0); var barG = new T.Group(); barG.add(bar); bar.position.x = 7; barG.position.set(-7, 4, 5); g.add(barG);
			anim.push(function (t) { shield.rotation.y = Math.sin(t) * 0.5; barG.rotation.z = Math.max(0, Math.sin(t * 0.7)) * 1.2; });
		},
		'eval-lab': function (g) {
			var dome = mesh(new T.SphereGeometry(8, 28, 14, 0, Math.PI * 2, 0, Math.PI / 2), mat(0xe0f2fe, { roughness: 0.2, metalness: 0.1 }), 0, 0, 0); g.add(dome);
			var tubes = [];
			[C.green, C.amber, C.sky, C.pink].forEach(function (c, i) { g.add(cyl(0.9, 0.9, 6, mat(C.white, { transparent: true, opacity: 0.6 }), 10 + i * 2.4, 0, 4, 12)); var liq = cyl(0.8, 0.8, 3, glow(c, 0.5), 10 + i * 2.4, 0, 4, 12); g.add(liq); tubes.push(liq); });
			var check = new T.Group(); var c1 = box(1, 4, 1, glow(C.green, 0.9), 1, 0, 0); c1.rotation.z = -0.6; check.add(c1); var c2 = box(1, 2.2, 1, glow(C.green, 0.9), -1.1, -0.6, 0); c2.rotation.z = 0.8; check.add(c2);
			check.position.set(0, 13, 0); g.add(check);
			anim.push(function (t) { tubes.forEach(function (l, i) { l.scale.y = 0.6 + 0.4 * Math.abs(Math.sin(t + i)); }); check.rotation.y = t; });
		},
		'launch-pad': function (g) {
			g.add(cyl(9, 10, 1.6, mat(0x94a3b8), 0, 0, 0, 8));
			for (var i = 0; i < 4; i++) { var a = i / 4 * Math.PI * 2 + Math.PI / 4; g.add(box(1, 12, 1, mat(C.orange), Math.cos(a) * 7, 1.6, Math.sin(a) * 7)); }
			var rocket = new T.Group();
			rocket.add(cyl(2.2, 2.2, 11, mat(C.white), 0, 0, 0, 20));
			rocket.add(cone(2.2, 5, mat(C.red), 0, 11, 0, 20));
			rocket.add(sphere(1, glow(C.sky, 0.6), 0, 7.5, 2));
			[0, 1, 2].forEach(function (k) { var f = box(0.4, 4, 3, mat(C.red), 0, 0, 0); var a = k / 3 * Math.PI * 2; f.position.set(Math.cos(a) * 2.2, 0, Math.sin(a) * 2.2); f.rotation.y = -a; rocket.add(f); });
			var flame = cone(1.6, 4, glow(C.amber, 1.2), 0, -4, 0, 12); flame.rotation.x = Math.PI; rocket.add(flame);
			rocket.position.set(0, 2, 0); g.add(rocket);
			anim.push(function (t) { rocket.position.y = 2 + Math.abs(Math.sin(t * 0.8)) * 3; flame.scale.y = 0.7 + Math.random() * 0.5; });
		}
	};

	function mulberry(a) { return function () { a |= 0; a = a + 0x6D2B79F5 | 0; var t = Math.imul(a ^ a >>> 15, 1 | a); t = t + Math.imul(t ^ t >>> 7, 61 | t) ^ t; return ((t ^ t >>> 14) >>> 0) / 4294967296; }; }

	var placeNames = {};
	root.querySelectorAll('.aic-step[id]').forEach(function (s) { var p = s.querySelector('.aic-place'); if (p) placeNames[s.id] = p.textContent.replace(/^\s*\d+\s*/, '').trim(); });

	STOPS.forEach(function (s) {
		if (s.id !== 'agent-hq') road(-2, 0, s.x, s.z);
	});
	STOPS.forEach(function (s) {
		var g = new T.Group();
		g.position.set(s.x, 0, s.z);
		var pad = cyl(15, 15, 0.3, mat(0xffffff, { transparent: true, opacity: 0.55 }), 0, 0, 0, 40); pad.castShadow = false; g.add(pad);
		var ring = mesh(new T.RingGeometry(15.5, 17, 48), glow(C.green, 0.9), 0, 0.35, 0); ring.rotation.x = -Math.PI / 2; ring.visible = false; ring.castShadow = false; g.add(ring);
		if (build[s.id]) build[s.id](g);
		var lb = label(placeNames[s.id] || s.id); lb.position.set(0, s.id === 'transformer-tower' ? 52 : 26, 0); g.add(lb);
		scene.add(g);
		districts[s.id] = { group: g, ring: ring, label: lb };
	});

	// Agent bots: little Nova-coloured robots running the plan → act → observe loop.
	var botPath = new T.CatmullRomCurve3([
		new T.Vector3(-3, 0.4, 9), new T.Vector3(-39, 0.4, 18), new T.Vector3(-21, 0.4, 33),
		new T.Vector3(-33, 0.4, -6), new T.Vector3(-6, 0.4, -21), new T.Vector3(15, 0.4, -12)
	], true);
	var bots = [];
	for (var b = 0; b < 4; b++) {
		var bot = new T.Group();
		bot.add(cyl(1.1, 1.3, 2, mat(C.white), 0, 0, 0, 16));
		bot.add(sphere(1.25, mat(C.white), 0, 2.9, 0));
		bot.add(box(1.6, 0.9, 0.3, mat(C.navy), 0, 2.45, 1.05));
		bot.add(sphere(0.25, glow(C.teal, 1.4), -0.35, 2.9, 1.2));
		bot.add(sphere(0.25, glow(C.teal, 1.4), 0.35, 2.9, 1.2));
		bot.add(sphere(0.35, glow(C.green, 1.2), 0, 4.5, 0));
		var pk = box(1.2, 1, 1, [C.amber, C.sky, C.pink, C.violet][b], 0, 1, -1.3); bot.add(pk);
		bot.userData.p = b / 4;
		scene.add(bot); bots.push(bot);
	}
	anim.push(function (t) {
		bots.forEach(function (bot, i) {
			var u = (t * 0.035 + bot.userData.p) % 1;
			var p = botPath.getPoint(Math.min(u, 0.9995)), q = botPath.getPoint(Math.min((u + 0.005) % 1, 0.9995));
			bot.position.set(p.x, p.y + Math.abs(Math.sin(t * 8 + i)) * 0.4, p.z);
			bot.rotation.y = Math.atan2(q.x - p.x, q.z - p.z);
		});
	});

	// Scenery
	var rnd = mulberry(42);
	for (var tcount = 0; tcount < 140; tcount++) {
		var ang = rnd() * Math.PI * 2, rad = 30 + rnd() * 80;
		var tx = Math.cos(ang) * rad, tz = 9 + Math.sin(ang) * rad * 0.82;
		var clear = Math.hypot(tx + 3, tz) > 18 && STOPS.every(function (s) { return Math.hypot(s.x - tx, s.z - tz) > 19; });
		if (clear) tree(tx, tz, 0.8 + rnd() * 0.6);
	}
	var clouds = [];
	for (var cc = 0; cc < 7; cc++) {
		var cl = new T.Group();
		for (var p2 = 0; p2 < 4; p2++) { var puff = sphere(3 + rnd() * 3, mat(0xffffff, { transparent: true, opacity: 0.92, roughness: 1 }), p2 * 4 - 6, rnd() * 2, rnd() * 3); puff.castShadow = false; cl.add(puff); }
		cl.position.set(-120 + rnd() * 240, 60 + rnd() * 20, -80 + rnd() * 160);
		cl.userData.s = 2 + rnd() * 3; scene.add(cl); clouds.push(cl);
	}
	anim.push(function (t, dt) { clouds.forEach(function (c) { c.position.x += c.userData.s * dt; if (c.position.x > 140) c.position.x = -140; }); });

	// ── Camera tour ─────────────────────────────────────────────────────────
	var overview = { x: -2, z: 12, zoom: small ? 0.42 : 0.6 };
	var sections = Array.prototype.slice.call(root.querySelectorAll('.aic-step'));
	var keys = sections.map(function (sec) {
		var id = sec.getAttribute('data-stop');
		var s = STOPS.filter(function (k) { return k.id === id; })[0];
		return s ? { id: id, x: s.x, z: s.z, zoom: small ? s.zoom * 0.75 : s.zoom } : { id: 'overview', x: overview.x, z: overview.z, zoom: overview.zoom };
	});
	var cur = { x: overview.x, z: overview.z, zoom: overview.zoom };
	var goal = { x: cur.x, z: cur.z, zoom: cur.zoom };
	var active = '';

	function ease(t) { return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2; }
	function readScroll() {
		var mid = window.innerHeight * 0.5, idx = 0, frac = 0;
		for (var i = 0; i < sections.length; i++) {
			var r = sections[i].getBoundingClientRect();
			var c = r.top + r.height / 2;
			if (c <= mid) { idx = i; var n = sections[i + 1]; if (n) { var r2 = n.getBoundingClientRect(); var c2 = r2.top + r2.height / 2; frac = Math.min(1, Math.max(0, (mid - c) / (c2 - c))); } else frac = 0; }
		}
		var a = keys[idx], b = keys[Math.min(idx + 1, keys.length - 1)], e = ease(frac);
		// Fly out a little between districts, like a drone.
		var lift = Math.sin(frac * Math.PI) * 0.35;
		goal.x = a.x + (b.x - a.x) * e;
		goal.z = a.z + (b.z - a.z) * e;
		goal.zoom = (a.zoom + (b.zoom - a.zoom) * e) * (1 - lift * (a.id === b.id ? 0 : 1));
		var now = frac < 0.5 ? a.id : b.id;
		if (now !== active) {
			Object.keys(districts).forEach(function (k) { var on = k === now; districts[k].ring.visible = on; districts[k].label.scale.set(on ? 30 : 17, on ? 7.5 : 4.25, 1); districts[k].label.material.opacity = on || now === 'overview' ? 1 : 0.7; });
			active = now;
		}
	}

	function size() {
		var w = holder.clientWidth || window.innerWidth, h = holder.clientHeight || window.innerHeight;
		var aspect = w / h;
		var view = aspect < 0.8 ? VIEW * 1.25 : VIEW;
		camera.left = -view * aspect / 2; camera.right = view * aspect / 2; camera.top = view / 2; camera.bottom = -view / 2;
		camera.updateProjectionMatrix();
		renderer.setSize(w, h, false);
	}

	var target = new T.Vector3();
	function place() {
		target.set(cur.x, 0, cur.z);
		// On wide screens, push the district away from the card side.
		var sideShift = window.innerWidth >= 900 ? 24 / cur.zoom : 0;
		var sec = active && document.getElementById(active);
		var dir = sec && sec.classList.contains('is-left') ? -1 : (sec && sec.classList.contains('is-right') ? 1 : 0);
		camera.position.copy(target).addScaledVector(camDir, 200);
		camera.lookAt(target);
		camera.zoom = cur.zoom;
		camera.updateMatrixWorld();
		if (dir) {
			var right = new T.Vector3().setFromMatrixColumn(camera.matrixWorld, 0);
			camera.position.addScaledVector(right, dir * sideShift);
		}
		// On phones the card sits at the bottom, so look a bit lower.
		if (window.innerWidth < 900) {
			var up = new T.Vector3().setFromMatrixColumn(camera.matrixWorld, 1);
			camera.position.addScaledVector(up, -16 / cur.zoom);
		}
		camera.updateProjectionMatrix();
	}

	var visible = true, last = performance.now(), clock = 0, raf = 0;
	function frame(now) {
		raf = 0;
		var dt = Math.min(0.05, (now - last) / 1000); last = now;
		readScroll();
		var k = reduce ? 1 : 1 - Math.pow(0.0025, dt);
		cur.x += (goal.x - cur.x) * k; cur.z += (goal.z - cur.z) * k; cur.zoom += (goal.zoom - cur.zoom) * k;
		if (!reduce) { clock += dt; anim.forEach(function (f) { f(clock, dt); }); }
		var cloudy = cur.zoom < 1.05;
		clouds.forEach(function (c) { c.visible = cloudy; });
		place();
		renderer.render(scene, camera);
		if (visible && !document.hidden && !reduce) raf = requestAnimationFrame(frame);
	}
	function kick() { if (!raf && visible) raf = requestAnimationFrame(frame); }

	size();
	if (reduce) anim.forEach(function (f) { f(0, 0); });
	window.addEventListener('resize', function () { size(); kick(); }, { passive: true });
	window.addEventListener('scroll', kick, { passive: true });
	document.addEventListener('visibilitychange', kick);
	if ('IntersectionObserver' in window) {
		new IntersectionObserver(function (es) {
			visible = es[0].isIntersecting;
			holder.style.visibility = visible ? '' : 'hidden';
			if (visible) { last = performance.now(); kick(); }
		}).observe(root);
	}
	kick();
})();
