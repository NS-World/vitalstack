/**
 * VitalStack Brain Games: two-minute games that teach algorithms, Java, OOP and frameworks.
 * Each game ends with a score (0-100) that earns XP through window.VS.
 */
(function () {
	'use strict';

	var grid = document.querySelector('[data-games]');
	var stage = document.getElementById('game-stage');
	if (!grid || !stage) return;
	var cfg = window.vitalstackFun || {};
	var lessonBase = cfg.lessonBase || '/tutorials/';
	var body = stage.querySelector('[data-game-body]');
	var titleEl = stage.querySelector('[data-game-title]');
	var statsEl = stage.querySelector('[data-game-stats]');

	/* ── Helpers ──────────────────────────────────────────────────────────── */
	function h(tag, attrs, kids) {
		var e = document.createElement(tag);
		Object.keys(attrs || {}).forEach(function (k) {
			if (k === 'text') e.textContent = attrs[k];
			else if (k === 'html') e.innerHTML = attrs[k];
			else if (k.slice(0, 2) === 'on') e.addEventListener(k.slice(2), attrs[k]);
			else e.setAttribute(k, attrs[k]);
		});
		(kids || []).forEach(function (c) { if (c) e.appendChild(typeof c === 'string' ? document.createTextNode(c) : c); });
		return e;
	}
	function shuffle(a) { a = a.slice(); for (var i = a.length - 1; i > 0; i--) { var j = Math.floor(Math.random() * (i + 1)); var t = a[i]; a[i] = a[j]; a[j] = t; } return a; }
	function rand(n) { return Math.floor(Math.random() * n); }
	function mascot(m) { return window.VS ? window.VS.mascot(m) : ''; }

	var timers = [];
	function every(fn, ms) { var t = setInterval(fn, ms); timers.push(t); return t; }
	function later(fn, ms) { var t = setTimeout(fn, ms); timers.push(t); return t; }
	function clearTimers() { timers.forEach(function (t) { clearInterval(t); clearTimeout(t); }); timers = []; }
	var keyHandler = null;
	function onKey(fn) { keyHandler = fn; }
	document.addEventListener('keydown', function (e) { if (keyHandler && !stage.hidden) keyHandler(e); });

	function setStats(t) { statsEl.textContent = t; }
	function flash(el, cls) { el.classList.remove(cls); void el.offsetWidth; el.classList.add(cls); }

	/* ── Games ────────────────────────────────────────────────────────────── */
	var GAMES = [];

	GAMES.push({
		id: 'binary-hunt', name: 'Binary Hunt', emoji: '🎯', tag: 'Binary search',
		desc: 'I am thinking of a number from 1 to 100. Find it in 7 guesses or fewer.',
		learn: ['Binary search', 'dsa-binary-search'],
		play: function (api) {
			var secret = 1 + rand(100), lo = 1, hi = 100, guesses = 0;
			var bar = h('div', { class: 'hunt-bar' }), win = h('div', { class: 'hunt-window' });
			bar.appendChild(win);
			var msg = h('p', { class: 'game-msg', text: 'Tip: always guess the middle of what is left.' });
			var input = h('input', { type: 'number', min: '1', max: '100', class: 'game-input', 'aria-label': 'Your guess' });
			var bot = h('div', { class: 'game-bot', html: mascot('think') });
			function paint() {
				win.style.left = ((lo - 1) / 100 * 100) + '%';
				win.style.width = ((hi - lo + 1) / 100 * 100) + '%';
				win.textContent = lo + ' – ' + hi;
				api.stats('Guesses: ' + guesses);
			}
			var done = false;
			function guess(n) {
				if (done) return;
				if (!n || n < 1 || n > 100) { msg.textContent = 'Type a number from 1 to 100.'; return; }
				guesses++;
				api.stats('Guesses: ' + guesses);
				if (n === secret) {
					done = true;
					bot.innerHTML = mascot('cheer');
					msg.textContent = 'Yes! It was ' + secret + '.';
					var score = guesses <= 7 ? 100 : guesses === 8 ? 70 : guesses === 9 ? 50 : 30;
					later(function () { api.finish(score, 'Found ' + secret + ' in ' + guesses + ' guesses. Binary search never needs more than 7 for 1–100, because each guess halves the range: 100 → 50 → 25 → 13 → 7 → 4 → 2 → 1.'); }, 900);
					return;
				}
				if (n < secret) { lo = Math.max(lo, n + 1); msg.textContent = n + ' is too low. Go higher ⬆'; }
				else { hi = Math.min(hi, n - 1); msg.textContent = n + ' is too high. Go lower ⬇'; }
				bot.innerHTML = mascot(guesses > 6 ? 'wow' : 'think');
				flash(win, 'is-shrink');
				input.value = ''; input.focus();
				paint();
			}
			var form = h('form', { class: 'game-row', onsubmit: function (e) { e.preventDefault(); guess(parseInt(input.value, 10)); } }, [input, h('button', { class: 'btn btn-primary', type: 'submit', text: 'Guess' }), h('button', { class: 'btn btn-ghost', type: 'button', text: 'Guess the middle', onclick: function () { guess(Math.floor((lo + hi) / 2)); } })]);
			api.body.appendChild(h('div', { class: 'game-card' }, [bot, h('p', { class: 'game-big', text: '1 … 100' }), bar, msg, form]));
			paint(); input.focus();
		}
	});

	function randomBrackets() {
		var open = '([{', close = ')]}', s = '', st = [], len = 2 * (2 + rand(4));
		while (s.length < len) {
			if (st.length && (rand(2) || st.length * 2 >= len - s.length)) s += close[st.pop()];
			else { var k = rand(3); st.push(k); s += open[k]; }
		}
		while (st.length) s += close[st.pop()];
		return s;
	}
	function isValid(s) {
		var st = [], pairs = { ')': '(', ']': '[', '}': '{' };
		for (var i = 0; i < s.length; i++) {
			if ('([{'.indexOf(s[i]) !== -1) st.push(s[i]);
			else if (!st.length || st.pop() !== pairs[s[i]]) return false;
		}
		return !st.length;
	}
	function breakIt(s) {
		var a = s.split(''), i = rand(a.length), kind = rand(3), map = { '(': '[', '[': '{', '{': '(', ')': ']', ']': '}', '}': ')' };
		if (kind === 0) a[i] = map[a[i]];
		else if (kind === 1) a.splice(i, 1);
		else { var j = Math.min(a.length - 1, i + 1); var t = a[i]; a[i] = a[j]; a[j] = t; }
		var out = a.join('');
		return isValid(out) || !out ? breakIt(s) : out;
	}

	GAMES.push({
		id: 'bracket-blitz', name: 'Bracket Blitz', emoji: '⚡', tag: 'Stacks',
		desc: 'Valid or broken brackets? Answer as many as you can in 45 seconds.',
		learn: ['Stacks', 'dsa-stacks'],
		play: function (api) {
			var time = 45, right = 0, wrong = 0, current = '', answer = true;
			var show = h('p', { class: 'game-brackets' }), msg = h('p', { class: 'game-msg', text: 'Keys: ← broken, → valid' });
			function next() { var v = randomBrackets(); answer = rand(2) === 0; current = answer ? v : breakIt(v); show.textContent = current; flash(show, 'is-pop'); }
			function pick(v) {
				if (time <= 0) return;
				if (v === answer) { right++; msg.textContent = '✓ Correct!'; flash(card, 'is-good'); }
				else { wrong++; msg.textContent = '✗ It was ' + (answer ? 'valid' : 'broken') + ': ' + current; flash(card, 'is-bad'); }
				api.stats('⏱ ' + time + 's · ✓ ' + right + ' · ✗ ' + wrong);
				next();
			}
			var card = h('div', { class: 'game-card' }, [
				show, msg,
				h('div', { class: 'game-row game-row-center' }, [
					h('button', { class: 'btn btn-lg game-no', type: 'button', text: '✗ Broken', onclick: function () { pick(false); } }),
					h('button', { class: 'btn btn-lg game-yes', type: 'button', text: '✓ Valid', onclick: function () { pick(true); } })
				])
			]);
			api.body.appendChild(card);
			onKey(function (e) { if (e.key === 'ArrowRight') pick(true); if (e.key === 'ArrowLeft') pick(false); });
			next();
			api.stats('⏱ 45s');
			every(function () {
				time--;
				api.stats('⏱ ' + time + 's · ✓ ' + right + ' · ✗ ' + wrong);
				if (time <= 0) { clearTimers(); onKey(null); api.finish(Math.max(0, Math.min(100, right * 8 - wrong * 6)), right + ' correct, ' + wrong + ' wrong. Your brain just did what a stack does: remember the last open bracket and match it with the next closing one.'); }
			}, 1000);
		}
	});

	var BIGO = [
		['x = arr[5]', 'O(1)', 'Reading one index takes the same time for any array size.'],
		['for x in arr:\n    print(x)', 'O(n)', 'One loop over n items.'],
		['for x in arr:\n    for y in arr:\n        print(x, y)', 'O(n²)', 'A loop inside a loop: n × n.'],
		['while n > 1:\n    n = n // 2', 'O(log n)', 'Halving each time: log₂ n steps.'],
		['arr.sort()', 'O(n log n)', 'Good general sorting algorithms are n log n.'],
		['total = sum(arr)', 'O(n)', 'sum() still has to visit every item.'],
		['seen = set(arr)\nprint(42 in seen)', 'O(n)', 'Building the set is O(n); the lookup itself is O(1).'],
		['for i in range(n):\n    for j in range(i):\n        pass', 'O(n²)', '0 + 1 + 2 + … + (n−1) = n(n−1)/2, still n².'],
		['lo, hi = 0, n - 1\nwhile lo <= hi:\n    mid = (lo + hi) // 2\n    ...', 'O(log n)', 'Binary search halves the range each step.'],
		['d = {"a": 1}\nd["b"] = 2', 'O(1)', 'Dictionary insert is O(1) on average.'],
		['for i in range(n):\n    arr[i] = 0\nfor j in range(n):\n    print(j)', 'O(n)', 'Two separate loops: n + n = 2n, which is still O(n).'],
		['def f(n):\n    if n <= 1: return n\n    return f(n-1) + f(n-2)', 'O(2ⁿ)', 'Each call makes two more calls: exponential.'],
		['for i in range(10):\n    print(i)', 'O(1)', 'The loop runs 10 times no matter how big the input is.'],
		['i = 1\nwhile i < n:\n    i = i * 2', 'O(log n)', 'Doubling reaches n in log₂ n steps.'],
		['merge_sort(arr)', 'O(n log n)', 'log n levels of splitting, n work per level.'],
		['arr.insert(0, x)', 'O(n)', 'Inserting at the front shifts every other item.']
	];
	GAMES.push({
		id: 'big-o-snap', name: 'Big-O Snap', emoji: '⏱️', tag: 'Complexity',
		desc: 'See the code, snap the time complexity. 8 quick rounds.',
		learn: ['Big O notation', 'dsa-big-o'],
		play: function (api) {
			var qs = shuffle(BIGO).slice(0, 8), k = 0, right = 0, opts = ['O(1)', 'O(log n)', 'O(n)', 'O(n log n)', 'O(n²)', 'O(2ⁿ)'];
			var code = h('pre', { class: 'game-code' }), msg = h('p', { class: 'game-msg' }), row = h('div', { class: 'game-row game-row-center game-options' });
			var card = h('div', { class: 'game-card' }, [h('p', { class: 'game-q', text: 'What is the time complexity?' }), code, row, msg]);
			api.body.appendChild(card);
			function round() {
				var q = qs[k]; code.textContent = q[0]; msg.textContent = ''; row.innerHTML = '';
				api.stats('Round ' + (k + 1) + ' / ' + qs.length + ' · ✓ ' + right);
				opts.forEach(function (o) {
					row.appendChild(h('button', { class: 'btn btn-ghost', type: 'button', text: o, onclick: function () {
						if (row.dataset.locked) return; row.dataset.locked = '1';
						var ok = o === q[1]; if (ok) right++;
						msg.textContent = (ok ? '✓ ' : '✗ It is ' + q[1] + '. ') + q[2];
						flash(card, ok ? 'is-good' : 'is-bad');
						row.querySelectorAll('button').forEach(function (b) { if (b.textContent === q[1]) b.classList.add('is-right'); });
						later(function () { delete row.dataset.locked; k++; if (k < qs.length) round(); else api.finish(Math.round(right / qs.length * 100), right + ' of ' + qs.length + ' correct. Spotting complexity fast is the first step of every interview answer.'); }, ok ? 1100 : 2400);
					} }));
				});
			}
			round();
		}
	});

	GAMES.push({
		id: 'sort-sprint', name: 'Sort Sprint', emoji: '📊', tag: 'Sorting',
		desc: 'Sort the bars by swapping neighbours. Can you use the minimum number of swaps?',
		learn: ['Sorting', 'dsa-sorting'],
		play: function (api) {
			var vals = shuffle([1, 2, 3, 4, 5, 6, 7]);
			while (vals.every(function (v, i) { return v === i + 1; })) vals = shuffle(vals);
			var inv = 0; for (var i = 0; i < vals.length; i++) for (var j = i + 1; j < vals.length; j++) if (vals[i] > vals[j]) inv++;
			var swaps = 0, secs = 0;
			var row = h('div', { class: 'sort-row' }), msg = h('p', { class: 'game-msg', text: 'Fewest possible swaps: ' + inv + '. Click ⇄ between two bars to swap them.' });
			function draw() {
				row.innerHTML = '';
				vals.forEach(function (v, i) {
					row.appendChild(h('div', { class: 'sort-bar' + (v === i + 1 ? ' is-home' : ''), style: 'height:' + (24 + v * 22) + 'px', text: String(v) }));
					if (i < vals.length - 1) row.appendChild(h('button', { class: 'sort-swap', type: 'button', 'aria-label': 'Swap ' + v + ' and ' + vals[i + 1], text: '⇄', onclick: function () {
						var t = vals[i]; vals[i] = vals[i + 1]; vals[i + 1] = t; swaps++;
						api.stats('Swaps: ' + swaps + ' · ⏱ ' + secs + 's');
						draw();
						if (vals.every(function (x, k) { return x === k + 1; })) {
							clearTimers();
							api.finish(Math.round(100 * inv / Math.max(swaps, inv)), 'Sorted with ' + swaps + ' swaps (best possible: ' + inv + ') in ' + secs + 's. Each useful swap fixes one "inversion": a pair in the wrong order. Bubble sort does exactly this.');
						}
					} }));
				});
			}
			api.body.appendChild(h('div', { class: 'game-card' }, [row, msg]));
			draw();
			every(function () { secs++; api.stats('Swaps: ' + swaps + ' · ⏱ ' + secs + 's'); }, 1000);
		}
	});

	var PAIRS = [['Stack', 'Last in, first out'], ['Queue', 'First in, first out'], ['Binary search', 'O(log n)'], ['Hash map', 'O(1) lookup'], ['BFS', 'Uses a queue'], ['DFS', 'Goes deep first'], ['Merge sort', 'O(n log n)'], ['Min-heap', 'Smallest on top'], ['Linked list', 'Nodes + pointers'], ['Memoization', 'Cache old answers'], ['Two pointers', 'Start at both ends'], ['Recursion', 'A function calls itself']];
	GAMES.push({
		id: 'memory-match', name: 'Memory Match', emoji: '🃏', tag: 'Concepts',
		desc: 'Flip cards and match each DSA idea with its meaning.',
		learn: ['DSA introduction', 'dsa-introduction'],
		play: function (api) {
			var pairs = shuffle(PAIRS).slice(0, 6), cards = [], open = [], moves = 0, found = 0;
			pairs.forEach(function (p, k) { cards.push({ k: k, t: p[0], term: true }); cards.push({ k: k, t: p[1] }); });
			cards = shuffle(cards);
			var gridEl = h('div', { class: 'mem-grid' });
			cards.forEach(function (c) {
				var b = h('button', { class: 'mem-card' + (c.term ? ' is-term' : ''), type: 'button', 'aria-label': 'Hidden card' }, [h('span', { class: 'mem-back', text: '?' }), h('span', { class: 'mem-front', text: c.t })]);
				b.addEventListener('click', function () {
					if (b.classList.contains('is-open') || open.length === 2) return;
					b.classList.add('is-open'); b.setAttribute('aria-label', c.t); open.push({ b: b, c: c });
					if (open.length === 2) {
						moves++;
						api.stats('Moves: ' + moves + ' · Pairs: ' + found + ' / 6');
						var a = open[0], z = open[1];
						if (a.c.k === z.c.k) {
							a.b.classList.add('is-match'); z.b.classList.add('is-match'); open = []; found++;
							api.stats('Moves: ' + moves + ' · Pairs: ' + found + ' / 6');
							if (found === 6) later(function () { api.finish(Math.max(20, Math.min(100, 100 - (moves - 6) * 8)), 'All pairs in ' + moves + ' moves (perfect is 6). These ideas come up in almost every DSA interview.'); }, 600);
						} else later(function () { a.b.classList.remove('is-open'); z.b.classList.remove('is-open'); a.b.setAttribute('aria-label', 'Hidden card'); z.b.setAttribute('aria-label', 'Hidden card'); open = []; }, 900);
					}
				});
				gridEl.appendChild(b);
			});
			api.body.appendChild(h('div', { class: 'game-card' }, [gridEl]));
			api.stats('Moves: 0 · Pairs: 0 / 6');
		}
	});

	GAMES.push({
		id: 'hanoi', name: 'Tower of Hanoi', emoji: '🗼', tag: 'Recursion',
		desc: 'Move the tower to the last rod. Never put a bigger disk on a smaller one.',
		learn: ['Recursion', 'dsa-recursion'],
		play: function (api) {
			var n = 3, rods, picked = null, moves = 0, best;
			var wrap = h('div', { class: 'hanoi' }), msg = h('p', { class: 'game-msg' });
			var level = h('div', { class: 'game-row game-row-center' }, [3, 4, 5].map(function (k) {
				return h('button', { class: 'btn btn-ghost btn-sm' + (k === 3 ? ' is-right' : ''), type: 'button', text: k + ' disks', onclick: function () { level.querySelectorAll('button').forEach(function (b) { b.classList.remove('is-right'); }); this.classList.add('is-right'); start(k); } });
			}));
			function start(k) { n = k; rods = [[], [], []]; for (var d = n; d >= 1; d--) rods[0].push(d); picked = null; moves = 0; best = Math.pow(2, n) - 1; msg.textContent = 'Click a rod to pick up its top disk, then click another rod to drop it. Best possible: ' + best + ' moves.'; draw(); }
			function draw() {
				wrap.innerHTML = '';
				rods.forEach(function (rod, r) {
					var el = h('button', { class: 'hanoi-rod' + (picked === r ? ' is-picked' : ''), type: 'button', 'aria-label': 'Rod ' + (r + 1) });
					el.appendChild(h('span', { class: 'hanoi-pole' }));
					rod.forEach(function (d, i) { el.appendChild(h('span', { class: 'hanoi-disk d' + d + (picked === r && i === rod.length - 1 ? ' is-up' : ''), style: 'width:' + (24 + d * 22) + 'px' })); });
					el.addEventListener('click', function () { click(r); });
					wrap.appendChild(el);
				});
				api.stats('Moves: ' + moves + ' · Best: ' + best);
			}
			function click(r) {
				if (picked === null) { if (rods[r].length) picked = r; draw(); return; }
				if (picked === r) { picked = null; draw(); return; }
				var d = rods[picked][rods[picked].length - 1], top = rods[r][rods[r].length - 1];
				if (top && top < d) { msg.textContent = 'A bigger disk can\'t go on a smaller one!'; flash(wrap, 'is-shake'); picked = null; draw(); return; }
				rods[r].push(rods[picked].pop()); picked = null; moves++; draw();
				if (rods[2].length === n) api.finish(Math.round(100 * best / moves), 'Done in ' + moves + ' moves (best: ' + best + '). The recursive trick: move n−1 disks out of the way, move the biggest, then move the n−1 disks back on top. That is 2ⁿ − 1 moves.');
			}
			api.body.appendChild(h('div', { class: 'game-card' }, [level, wrap, msg]));
			start(3);
		}
	});

	var PATTERNS = [
		['Find two numbers in a SORTED array that add up to a target.', 'Two pointers', 'Sorted + pair → move pointers inward from both ends.'],
		['Longest substring without repeating characters.', 'Sliding window', 'Contiguous run + a condition → grow and shrink a window.'],
		['Is there a pair that sums to k in an UNSORTED array, in O(n)?', 'Hash map', 'Remember what you have seen; look up target − x in O(1).'],
		['Shortest path in a maze where every step costs the same.', 'BFS', 'Unweighted shortest path → breadth-first search.'],
		['Count the islands in a grid of land and water.', 'DFS / flood fill', 'Each unvisited land cell starts a new flood fill.'],
		['Find the k largest numbers in a stream.', 'Heap', 'Keep a min-heap of size k.'],
		['Generate all subsets / permutations.', 'Backtracking', 'Choose → explore → un-choose.'],
		['Number of ways to climb n stairs taking 1 or 2 steps.', 'Dynamic programming', 'Answer depends on smaller answers: dp[i] = dp[i-1] + dp[i-2].'],
		['Find a target in a sorted, rotated array in O(log n).', 'Binary search', 'Sorted (even partly) + log n → binary search.'],
		['Detect a cycle in a linked list with O(1) memory.', 'Fast & slow pointers', 'If there is a loop, the fast pointer laps the slow one.'],
		['Check if brackets are balanced.', 'Stack', 'The most recent open bracket must close first: LIFO.'],
		['Next warmer day for each day in a temperature list.', 'Monotonic stack', 'Keep waiting days on a stack until a warmer one arrives.'],
		['Order courses so every prerequisite comes first.', 'Topological sort', 'Dependencies in a directed graph → topological order.'],
		['Merge all overlapping meetings.', 'Sort + greedy', 'Sort by start, then sweep and merge.'],
		['Sum of any subarray range, answered many times quickly.', 'Prefix sums', 'Precompute running totals; each query is one subtraction.'],
		['Maximum sum of a contiguous subarray.', "Kadane's algorithm", 'Keep the best sum ending here; restart when it goes negative.']
	];
	GAMES.push({
		id: 'pattern-detective', name: 'Pattern Detective', emoji: '🕵️', tag: 'Interview skill',
		desc: 'Read a classic interview problem and name the pattern that cracks it.',
		learn: ['Interview problems', ''],
		play: function (api) {
			var qs = shuffle(PATTERNS).slice(0, 8), k = 0, right = 0;
			var q = h('p', { class: 'game-q' }), row = h('div', { class: 'game-row game-row-center game-options' }), msg = h('p', { class: 'game-msg' });
			var card = h('div', { class: 'game-card' }, [h('div', { class: 'game-bot', html: mascot('think') }), q, row, msg]);
			api.body.appendChild(card);
			function round() {
				var item = qs[k];
				var wrong = shuffle(PATTERNS.filter(function (p) { return p[1] !== item[1]; }).map(function (p) { return p[1]; })).slice(0, 3);
				q.textContent = item[0]; msg.textContent = ''; row.innerHTML = '';
				api.stats('Case ' + (k + 1) + ' / ' + qs.length + ' · ✓ ' + right);
				shuffle(wrong.concat([item[1]])).forEach(function (o) {
					row.appendChild(h('button', { class: 'btn btn-ghost', type: 'button', text: o, onclick: function () {
						if (row.dataset.locked) return; row.dataset.locked = '1';
						var ok = o === item[1]; if (ok) right++;
						msg.textContent = (ok ? '✓ Case solved! ' : '✗ It is ' + item[1] + '. ') + item[2];
						flash(card, ok ? 'is-good' : 'is-bad');
						row.querySelectorAll('button').forEach(function (b) { if (b.textContent === item[1]) b.classList.add('is-right'); });
						later(function () { delete row.dataset.locked; k++; if (k < qs.length) round(); else api.finish(Math.round(right / qs.length * 100), right + ' of ' + qs.length + ' cases solved. Recognising the pattern is 80% of solving an interview problem.'); }, ok ? 1300 : 2600);
					} }));
				});
			}
			round();
		}
	});

	/* ── Java, OOP and framework games ───────────────────────────────────── */

	/**
	 * Shared multiple-choice round runner.
	 * items: [{ q, code?, a, opts: [..], e }]; seconds: optional time limit for the whole game.
	 */
	function choiceGame(api, items, o) {
		var k = 0, right = 0, left = o.seconds || 0;
		var code = h('pre', { class: 'game-code' }), q = h('p', { class: 'game-q' }), row = h('div', { class: 'game-row game-row-center game-options' }), msg = h('p', { class: 'game-msg' });
		var card = h('div', { class: 'game-card' }, [o.bot ? h('div', { class: 'game-bot', html: mascot(o.bot) }) : null, q, code, row, msg]);
		api.body.appendChild(card);
		function stats() { api.stats((o.seconds ? '⏱ ' + left + 's · ' : 'Round ' + (k + 1) + ' / ' + items.length + ' · ') + '✓ ' + right); }
		function end() {
			var total = o.seconds ? Math.max(k, 1) : items.length;
			var score = o.seconds ? Math.min(100, right * 10) : Math.round(right / items.length * 100);
			api.finish(score, o.summary(right, total));
		}
		if (o.seconds) {
			every(function () { left--; stats(); if (left <= 0) end(); }, 1000);
		}
		function round() {
			var it = items[k];
			q.textContent = it.q; code.hidden = !it.code; code.textContent = it.code || '';
			msg.textContent = ''; row.innerHTML = ''; stats();
			shuffle(it.opts.concat([it.a])).forEach(function (opt) {
				row.appendChild(h('button', { class: 'btn btn-ghost', type: 'button', text: opt, onclick: function () {
					if (row.dataset.locked) return; row.dataset.locked = '1';
					var ok = opt === it.a; if (ok) right++;
					msg.textContent = (ok ? '✓ ' : '✗ It is ' + it.a + '. ') + it.e;
					flash(card, ok ? 'is-good' : 'is-bad');
					row.querySelectorAll('button').forEach(function (b) { if (b.textContent === it.a) b.classList.add('is-right'); });
					later(function () {
						delete row.dataset.locked; k++;
						if (k < items.length) round(); else end();
					}, o.seconds ? (ok ? 500 : 1400) : (ok ? 1100 : 2400));
				} }));
			});
		}
		round();
	}

	var JAVA_OUT = [
		['System.out.println(7 / 2);', '3', ['3.5', '4', '3.0'], 'int / int is integer division.'],
		['System.out.println("1" + 2 + 3);', '123', ['6', '15', '"6"'], 'Once a String appears, + joins text left to right.'],
		['System.out.println(1 + 2 + "3");', '33', ['123', '6', '15'], '1 + 2 is added first (3), then joined with "3".'],
		['int a = 5;\nint b = a++;\nSystem.out.println(a + " " + b);', '6 5', ['6 6', '5 5', '5 6'], 'a++ returns the old value, then increments.'],
		['String s = "java";\ns.toUpperCase();\nSystem.out.println(s);', 'java', ['JAVA', 'Java', 'null'], 'Strings are immutable; the result was thrown away.'],
		['System.out.println(10 % 3);', '1', ['3', '0', '3.33'], '% gives the remainder.'],
		['System.out.println((int) 9.99);', '9', ['10', '9.99', '9.0'], 'Casting truncates; it does not round.'],
		['System.out.println(Math.round(2.5));', '3', ['2', '2.5', '3.0'], 'Math.round rounds half up and returns a long.'],
		['String a = "hi";\nString b = new String("hi");\nSystem.out.println(a == b);', 'false', ['true', 'hi', 'error'], '== compares references; new String creates a new object.'],
		['String a = "hi";\nString b = new String("hi");\nSystem.out.println(a.equals(b));', 'true', ['false', 'hi', 'error'], 'equals compares the characters.'],
		['int[] x = {1, 2, 3};\nint[] y = x;\ny[0] = 9;\nSystem.out.println(x[0]);', '9', ['1', '0', 'error'], 'x and y point to the same array.'],
		['System.out.println(Integer.MAX_VALUE + 1);', '-2147483648', ['2147483648', 'error', '0'], 'int overflow wraps around silently.'],
		["char c = 'A';\nc++;\nSystem.out.println(c);", 'B', ['A', '66', 'A1'], 'char is a number underneath; ++ moves to the next letter.'],
		["System.out.println('A' + 1);", '66', ['B', 'A1', 'error'], "'A' is 65; char + int is an int."],
		['StringBuilder sb = new StringBuilder("abc");\nsb.reverse();\nSystem.out.println(sb);', 'cba', ['abc', 'bca', 'null'], 'StringBuilder is mutable: reverse changes it.'],
		['List<Integer> l = new ArrayList<>(List.of(1, 2, 3));\nl.remove(1);\nSystem.out.println(l);', '[1, 3]', ['[2, 3]', '[1, 2]', 'error'], 'remove(int) removes by index, not by value.'],
		['System.out.println(0.1 + 0.2 == 0.3);', 'false', ['true', '0.3', 'error'], 'Floating point can\'t represent these exactly.'],
		['String s = null;\nSystem.out.println(s + "!");', 'null!', ['!', 'NullPointerException', 'error'], 'Concatenation turns null into "null".'],
		['int x = 10;\nx += 5 * 2;\nSystem.out.println(x);', '20', ['30', '25', '15'], '5 * 2 runs first, then x = 10 + 10.'],
		['System.out.println("Java".substring(1, 3));', 'av', ['ava', 'Ja', 'va'], 'The end index is excluded.'],
		['System.out.println(Math.max(3, 7) + Math.min(3, 7));', '10', ['7', '37', '73'], '7 + 3.']
	];

	GAMES.push({
		id: 'java-output', name: 'Java Output Predictor', emoji: '☕', tag: 'Java',
		desc: 'Read the Java snippet and predict exactly what it prints. 8 tricky rounds.',
		learn: ['Java operators', 'java-operators'],
		play: function (api) {
			var items = shuffle(JAVA_OUT).slice(0, 8).map(function (r) { return { q: 'What does this print?', code: r[0], a: r[1], opts: r[2], e: r[3] }; });
			choiceGame(api, items, { bot: 'think', summary: function (r, t) { return r + ' of ' + t + ' predicted correctly. These are the exact traps interviewers use in Java MCQ rounds.'; } });
		}
	});

	var PILLARS = ['Encapsulation', 'Inheritance', 'Polymorphism', 'Abstraction', 'Composition'];
	var OOP_CASES = [
		['BankAccount keeps balance private and changes it only through deposit() and withdraw().', 'Encapsulation', 'Data is protected behind methods with rules.'],
		['SavingsAccount extends Account and reuses its withdraw() method.', 'Inheritance', 'A subclass reuses and extends a parent class.'],
		['A loop calls shape.area() on circles and squares, and each calculates differently.', 'Polymorphism', 'One call, behaviour decided by the real object.'],
		['You call list.add() without knowing how ArrayList grows its internal array.', 'Abstraction', 'You use what it does, not how it does it.'],
		['A Car class has an Engine field instead of extending Engine.', 'Composition', 'Has-a relationships use composition.'],
		['UpiPayment and CardPayment implement Payment; checkout just calls payment.pay().', 'Polymorphism', 'Code works with the interface; each class behaves its own way.'],
		['An abstract ReportGenerator fixes the steps; subclasses only fill in body().', 'Abstraction', 'The template hides the details behind abstract methods.'],
		['Every field is private and the setter rejects invalid emails.', 'Encapsulation', 'Validation guards the object\'s state.'],
		['Manager extends Employee and calls super.annualPay() before adding a bonus.', 'Inheritance', 'super reuses the parent\'s logic.'],
		['A Library object holds a List<Book> and manages loans.', 'Composition', 'The library has books; it isn\'t a book.'],
		['You press the brake pedal without knowing how the hydraulics work.', 'Abstraction', 'A simple interface hides complex internals.'],
		['An immutable Money class has final fields and returns new objects for changes.', 'Encapsulation', 'Nobody can change its internal state.'],
		['Puppy extends Dog, which extends Animal, so a Puppy can eat().', 'Inheritance', 'Methods are found by walking up the hierarchy.'],
		['print(int) and print(String) share a name; Java picks one by argument type.', 'Polymorphism', 'Overloading is compile-time polymorphism.']
	];

	GAMES.push({
		id: 'oop-pillars', name: 'OOP Pillar Match', emoji: '🏛️', tag: 'Java OOP',
		desc: 'Read a design scenario and name the OOP idea behind it. 8 rounds.',
		learn: ['OOP introduction', 'java-oop-introduction'],
		play: function (api) {
			var items = shuffle(OOP_CASES).slice(0, 8).map(function (r) {
				return { q: r[0], a: r[1], opts: shuffle(PILLARS.filter(function (p) { return p !== r[1]; })).slice(0, 3), e: r[2] };
			});
			choiceGame(api, items, { bot: 'think', summary: function (r, t) { return r + ' of ' + t + ' matched. "Explain the four pillars with examples" is asked in almost every Java interview.'; } });
		}
	});

	var FLASH = {
		Spring: [['Wrap a service method in a database transaction', '@Transactional'], ['A controller whose methods return JSON', '@RestController'], ['Read {id} from /courses/{id}', '@PathVariable'], ['Read ?q=java from the URL', '@RequestParam'], ['Turn the JSON body into an object', '@RequestBody'], ['Pick the default bean when there are several', '@Primary'], ['Run Bean Validation on a parameter', '@Valid'], ['Map a class to a database table', '@Entity'], ['Optimistic locking column', '@Version'], ['Handle exceptions for all controllers', '@RestControllerAdvice'], ['Mark the Spring Boot main class', '@SpringBootApplication'], ['Load a course with its lessons in one query', '@EntityGraph']],
		React: [['Store a value that changes and re-renders', 'useState'], ['Run a side effect after rendering', 'useEffect'], ['Read a value from a provider', 'useContext'], ['Cache an expensive calculation', 'useMemo'], ['Keep the same function between renders', 'useCallback'], ['Hold a DOM node without re-rendering', 'useRef'], ['Manage state with actions and a reducer', 'useReducer'], ['Read :slug from the current URL', 'useParams']],
		Angular: [['Repeat an element for each item', '@for'], ['Writable reactive state', 'signal()'], ['A derived, cached reactive value', 'computed()'], ['Send an event from child to parent', 'output()'], ['Receive data from the parent', 'input()'], ['Get a service instance in a component', 'inject()'], ['Cancel the previous search request', 'switchMap'], ['Two-way bind an input field', '[(ngModel)]']]
	};

	GAMES.push({
		id: 'framework-flash', name: 'Framework Flash', emoji: '⚡', tag: 'Spring · React · Angular',
		desc: 'Name the annotation, hook or API for each job. As many as you can in 60 seconds!',
		learn: ['Spring Boot', 'spring-boot-introduction'],
		play: function (api) {
			var items = [];
			Object.keys(FLASH).forEach(function (fw) {
				FLASH[fw].forEach(function (r) {
					var others = shuffle(FLASH[fw].filter(function (x) { return x[1] !== r[1]; }).map(function (x) { return x[1]; })).slice(0, 3);
					items.push({ q: fw + ': ' + r[0], a: r[1], opts: others, e: '' });
				});
			});
			choiceGame(api, shuffle(items), { seconds: 60, bot: 'happy', summary: function (r, t) { return r + ' correct out of ' + t + ' answered in 60 seconds. Each correct answer is worth 10 points.'; } });
		}
	});

	/* ── Menu, stage and results ─────────────────────────────────────────── */
	var best = {};
	try { best = JSON.parse(localStorage.getItem('vs-game-best') || '{}'); } catch (e) {}

	function cardFor(g) {
		var c = h('button', { class: 'game-tile', type: 'button', onclick: function () { start(g); } }, [
			h('span', { class: 'game-tile-emoji', text: g.emoji }),
			h('span', { class: 'game-tile-tag', text: g.tag }),
			h('strong', { text: g.name }),
			h('span', { class: 'game-tile-desc', text: g.desc }),
			h('span', { class: 'game-tile-foot' }, [h('span', { text: '~2 min' }), h('span', { class: 'game-tile-best', text: best[g.id] ? '🏆 Best ' + best[g.id] : 'Play ❯' })])
		]);
		return c;
	}
	function menu() {
		grid.innerHTML = '';
		GAMES.forEach(function (g) { grid.appendChild(cardFor(g)); });
	}

	function start(g) {
		clearTimers(); onKey(null);
		grid.hidden = true; stage.hidden = false;
		titleEl.textContent = g.emoji + ' ' + g.name;
		body.innerHTML = ''; statsEl.textContent = '';
		var finished = false;
		var api = {
			body: body,
			stats: setStats,
			finish: function (score, summary) { if (finished) return; finished = true; clearTimers(); onKey(null); results(g, score, summary); }
		};
		g.play(api);
		stage.scrollIntoView({ behavior: 'smooth', block: 'start' });
	}

	function results(g, score, summary) {
		score = Math.max(0, Math.min(100, Math.round(score)));
		if (!best[g.id] || score > best[g.id]) { best[g.id] = score; try { localStorage.setItem('vs-game-best', JSON.stringify(best)); } catch (e) {} }
		var stars = score >= 80 ? 3 : score >= 50 ? 2 : 1;
		var xp = h('p', { class: 'result-xp', text: '…' });
		var learn = g.learn[1] ? h('a', { class: 'btn btn-ghost', href: lessonBase + g.learn[1] + '/', text: 'Learn it: ' + g.learn[0] + ' ❯' }) : (cfg.problemsUrl ? h('a', { class: 'btn btn-ghost', href: cfg.problemsUrl, text: 'Practice interview problems ❯' }) : null);
		body.innerHTML = '';
		body.appendChild(h('div', { class: 'game-card game-result' }, [
			h('div', { class: 'game-bot', html: mascot(stars === 3 ? 'cheer' : stars === 2 ? 'happy' : 'wow') }),
			h('p', { class: 'result-stars', text: '★'.repeat(stars) + '☆'.repeat(3 - stars) }),
			h('p', { class: 'result-score', text: 'Score ' + score }),
			xp,
			h('p', { class: 'result-summary', text: summary }),
			h('div', { class: 'game-row game-row-center' }, [
				h('button', { class: 'btn btn-primary', type: 'button', text: 'Play again', onclick: function () { start(g); } }),
				h('button', { class: 'btn btn-ghost', type: 'button', text: 'Other games', onclick: back }),
				learn
			])
		]));
		if (window.VS) window.VS.addXP('game', g.id, score).then(function (res) {
			xp.textContent = res.gained ? '+' + res.gained + ' XP' : 'Daily game XP limit reached. Come back tomorrow!';
			if (res.gained && stars === 3) window.VS.celebrate('Brilliant!', g.name + ': 3 stars!', 'cheer', res.gained);
		}); else xp.textContent = '';
	}

	function back() { clearTimers(); onKey(null); stage.hidden = true; grid.hidden = false; menu(); grid.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
	stage.querySelector('[data-game-back]').addEventListener('click', back);

	menu();

	// Game of the day: the same game for everyone on a given date.
	var daily = GAMES[Math.floor(Date.now() / 864e5) % GAMES.length];
	var dEl = document.querySelector('[data-daily]');
	if (dEl) {
		dEl.hidden = false;
		dEl.querySelector('[data-daily-name]').textContent = daily.emoji + ' ' + daily.name;
		dEl.querySelector('[data-daily-play]').addEventListener('click', function () { start(daily); });
	}
	var m = window.location.hash.match(/^#play-(.+)$/);
	if (m) GAMES.forEach(function (g) { if (g.id === m[1]) start(g); });
})();
