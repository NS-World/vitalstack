/**
 * VitalStack live algorithm visualizer.
 *
 * Any element like
 *   <div class="vs-viz" data-viz="binary-search" data-input='{"arr":[1,3,5,7],"target":5}'></div>
 * becomes an interactive player: play / pause / step / back / speed, the
 * Python code with the current line highlighted, Stacky explaining every
 * step, and a form to try your own input.
 *
 * Each algorithm records a list of steps. A step is { note, line, scene },
 * where scene can contain any of these parts, drawn top to bottom:
 *   arrays: [{ name, values, cls: {i: cls}, ptr: {label: i}, range: [l, r], labels }]
 *   vars:   { name: value }
 *   stack / queue / chips: { name, items, hl }
 *   map:    { name, entries: [[k, v]], hl }
 *   list:   { values, next: [i|null], ptr: {label: i}, cls }
 *   tree:   { root: {label, cls, children[]} }  (or binary level-order via toTree())
 *   graph:  { nodes: [{id, x, y}], edges: [[a, b, w]], cls, ecls, directed }
 *   grid:   { rows, rowLabels, colLabels, cls: {"r,c": cls} }
 *   bars:   [{ label, value, cls }]
 *   intervals: { items: [[s, e]], cls, merged }
 *   out:    string
 */
(function () {
	'use strict';

	var A = {};
	function clone(o) { return JSON.parse(JSON.stringify(o)); }
	function recorder() {
		var steps = [];
		var fn = function (note, line, scene) { steps.push({ note: note, line: line || 0, scene: clone(scene || {}) }); };
		fn.steps = steps;
		return fn;
	}
	function range(n) { var a = []; for (var i = 0; i < n; i++) a.push(i); return a; }
	function clsMap(pairs) { var m = {}; pairs.forEach(function (p) { if (p[0] !== null && p[0] !== undefined && p[0] >= 0) m[p[0]] = p[1]; }); return m; }

	/* ═══ Algorithms ═══════════════════════════════════════════════════════ */

	A['big-o'] = {
		title: 'How fast does work grow?',
		inputs: [],
		defaults: {},
		code: ['# Operations needed for an input of size n', 'O(1)       # constant: same work for any n', 'O(log n)   # halving each step (binary search)', 'O(n)       # one pass over the data', 'O(n log n) # good sorting (merge sort)', 'O(n^2)     # loop inside a loop'],
		run: function () {
			var r = recorder();
			[1, 2, 4, 8, 16, 32, 64].forEach(function (n) {
				var lg = Math.max(1, Math.round(Math.log2(n) * 10) / 10);
				r('n = ' + n + ': O(n²) needs ' + n * n + ' steps while O(log n) needs only ' + lg + '. ' + (n >= 32 ? 'See how the quadratic bar explodes? That is why we look for better patterns.' : 'Double n and watch which bars grow fastest.'), 0, {
					vars: { n: n },
					bars: [
						{ label: 'O(1)', value: 1, cls: 'good' },
						{ label: 'O(log n)', value: lg, cls: 'good' },
						{ label: 'O(n)', value: n, cls: 'ok' },
						{ label: 'O(n log n)', value: Math.round(n * Math.log2(Math.max(2, n))), cls: 'ok' },
						{ label: 'O(n²)', value: n * n, cls: 'bad' }
					]
				});
			});
			return r.steps;
		}
	};

	A['linear-search'] = {
		title: 'Linear search',
		inputs: [{ key: 'arr', label: 'Array', type: 'array' }, { key: 'target', label: 'Target', type: 'number' }],
		defaults: { arr: [4, 8, 15, 16, 23, 42], target: 23 },
		code: ['def linear_search(arr, target):', '    for i in range(len(arr)):', '        if arr[i] == target:', '            return i', '    return -1'],
		run: function (inp) {
			var a = inp.arr, t = inp.target, r = recorder();
			r('We look at the items one by one, from left to right, until we find ' + t + '.', 1, { arrays: [{ values: a }], vars: { target: t } });
			for (var i = 0; i < a.length; i++) {
				var cls = {}; for (var k = 0; k < i; k++) cls[k] = 'dim'; cls[i] = 'active';
				r('Check index ' + i + ': is ' + a[i] + ' equal to ' + t + '?', 3, { arrays: [{ values: a, cls: cls, ptr: { i: i } }], vars: { target: t, i: i } });
				if (a[i] === t) {
					cls[i] = 'found';
					r('Found ' + t + ' at index ' + i + ' after ' + (i + 1) + ' checks. In the worst case we check all n items: O(n).', 4, { arrays: [{ values: a, cls: cls, ptr: { i: i } }], vars: { target: t, i: i }, out: 'return ' + i });
					return r.steps;
				}
			}
			r(t + ' is not in the array, so we return -1. We had to check every item.', 5, { arrays: [{ values: a, cls: clsMap(a.map(function (_, i) { return [i, 'dim']; })) }], out: 'return -1' });
			return r.steps;
		}
	};

	A['binary-search'] = {
		title: 'Binary search',
		inputs: [{ key: 'arr', label: 'Sorted array', type: 'array', sorted: true }, { key: 'target', label: 'Target', type: 'number' }],
		defaults: { arr: [2, 5, 8, 12, 16, 23, 38, 56, 72, 91], target: 23 },
		code: ['def binary_search(arr, target):', '    lo, hi = 0, len(arr) - 1', '    while lo <= hi:', '        mid = (lo + hi) // 2', '        if arr[mid] == target:', '            return mid', '        elif arr[mid] < target:', '            lo = mid + 1', '        else:', '            hi = mid - 1', '    return -1'],
		run: function (inp) {
			var a = inp.arr, t = inp.target, r = recorder(), lo = 0, hi = a.length - 1, n = 0;
			function cls(mid, found) {
				var c = {};
				a.forEach(function (_, i) { if (i < lo || i > hi) c[i] = 'dim'; });
				if (mid !== undefined) c[mid] = found ? 'found' : 'active';
				return c;
			}
			r('The array is sorted, so we can throw away half of it after every check. lo and hi mark the part that can still contain ' + t + '.', 2, { arrays: [{ values: a, cls: cls(), ptr: { lo: lo, hi: hi }, range: [lo, hi] }], vars: { target: t } });
			while (lo <= hi) {
				var mid = Math.floor((lo + hi) / 2); n++;
				r('Middle of ' + lo + '…' + hi + ' is index ' + mid + ' (value ' + a[mid] + ').', 4, { arrays: [{ values: a, cls: cls(mid), ptr: { lo: lo, mid: mid, hi: hi }, range: [lo, hi] }], vars: { target: t, checks: n } });
				if (a[mid] === t) {
					r('arr[mid] == ' + t + '! Found in just ' + n + ' checks. Each check halves the search space: O(log n).', 6, { arrays: [{ values: a, cls: cls(mid, true), ptr: { mid: mid } }], vars: { target: t, checks: n }, out: 'return ' + mid });
					return r.steps;
				}
				if (a[mid] < t) {
					r(a[mid] + ' < ' + t + ', so the target must be to the RIGHT. Throw away the left half.', 8, { arrays: [{ values: a, cls: cls(mid), ptr: { lo: lo, mid: mid, hi: hi }, range: [lo, hi] }], vars: { target: t, checks: n } });
					lo = mid + 1;
				} else {
					r(a[mid] + ' > ' + t + ', so the target must be to the LEFT. Throw away the right half.', 10, { arrays: [{ values: a, cls: cls(mid), ptr: { lo: lo, mid: mid, hi: hi }, range: [lo, hi] }], vars: { target: t, checks: n } });
					hi = mid - 1;
				}
			}
			r('lo passed hi: nothing left to search. ' + t + ' is not in the array.', 11, { arrays: [{ values: a, cls: cls() }], vars: { target: t, checks: n }, out: 'return -1' });
			return r.steps;
		}
	};

	A['two-pointers'] = {
		title: 'Two pointers: pair with a target sum',
		inputs: [{ key: 'arr', label: 'Sorted array', type: 'array', sorted: true }, { key: 'target', label: 'Target sum', type: 'number' }],
		defaults: { arr: [1, 3, 4, 6, 8, 11, 15], target: 14 },
		code: ['def pair_sum(arr, target):', '    left, right = 0, len(arr) - 1', '    while left < right:', '        total = arr[left] + arr[right]', '        if total == target:', '            return [left, right]', '        elif total < target:', '            left += 1', '        else:', '            right -= 1', '    return []'],
		run: function (inp) {
			var a = inp.arr, t = inp.target, r = recorder(), L = 0, R = a.length - 1;
			r('Start one pointer at each end. Because the array is sorted, moving left makes the sum bigger and moving right makes it smaller.', 2, { arrays: [{ values: a, ptr: { left: L, right: R } }], vars: { target: t } });
			while (L < R) {
				var s = a[L] + a[R], c = {}; c[L] = 'active'; c[R] = 'active';
				r(a[L] + ' + ' + a[R] + ' = ' + s, 4, { arrays: [{ values: a, cls: c, ptr: { left: L, right: R } }], vars: { target: t, total: s } });
				if (s === t) { c[L] = c[R] = 'found'; r('Equal to ' + t + '! Found the pair in one pass: O(n) time, O(1) extra space.', 6, { arrays: [{ values: a, cls: c, ptr: { left: L, right: R } }], vars: { target: t, total: s }, out: 'return [' + L + ', ' + R + ']' }); return r.steps; }
				if (s < t) { r(s + ' is too small. The only way to grow the sum is to move left → right.', 8, { arrays: [{ values: a, cls: c, ptr: { left: L, right: R } }], vars: { target: t, total: s } }); L++; }
				else { r(s + ' is too big. Move right ← left to shrink the sum.', 10, { arrays: [{ values: a, cls: c, ptr: { left: L, right: R } }], vars: { target: t, total: s } }); R--; }
			}
			r('The pointers met: no pair adds up to ' + t + '.', 11, { arrays: [{ values: a }], out: 'return []' });
			return r.steps;
		}
	};

	A['sliding-window'] = {
		title: 'Sliding window: best sum of k in a row',
		inputs: [{ key: 'arr', label: 'Array', type: 'array' }, { key: 'k', label: 'Window size k', type: 'number' }],
		defaults: { arr: [2, 1, 5, 1, 3, 2, 7, 1], k: 3 },
		code: ['def max_sum(arr, k):', '    window = sum(arr[:k])', '    best = window', '    for right in range(k, len(arr)):', '        window += arr[right] - arr[right - k]', '        best = max(best, window)', '    return best'],
		run: function (inp) {
			var a = inp.arr, k = Math.max(1, Math.min(inp.k, a.length)), r = recorder();
			var w = 0; for (var i = 0; i < k; i++) w += a[i];
			var best = w, bestAt = 0;
			r('Add up the first ' + k + ' items: the first window.', 2, { arrays: [{ values: a, range: [0, k - 1] }], vars: { window: w, best: best } });
			for (var right = k; right < a.length; right++) {
				var left = right - k, c = {}; c[right] = 'add'; c[left] = 'remove';
				r('Slide right: add ' + a[right] + ' coming in, subtract ' + a[left] + ' going out. No need to re-add the whole window!', 5, { arrays: [{ values: a, cls: c, range: [left + 1, right] }], vars: { window: w + a[right] - a[left], best: best } });
				w += a[right] - a[left];
				if (w > best) { best = w; bestAt = left + 1; }
				r('best = max(best, ' + w + ') = ' + best, 6, { arrays: [{ values: a, range: [left + 1, right] }], vars: { window: w, best: best } });
			}
			var fc = {}; for (var j = bestAt; j < bestAt + k; j++) fc[j] = 'found';
			r('Done in one pass: O(n) instead of O(n·k). The best window sums to ' + best + '.', 7, { arrays: [{ values: a, cls: fc, range: [bestAt, bestAt + k - 1] }], vars: { best: best }, out: 'return ' + best });
			return r.steps;
		}
	};

	A['longest-unique'] = {
		title: 'Sliding window: longest substring without repeats',
		inputs: [{ key: 's', label: 'String', type: 'string' }],
		defaults: { s: 'abcabcbb' },
		code: ['def longest_unique(s):', '    seen = set()', '    left = best = 0', '    for right in range(len(s)):', '        while s[right] in seen:', '            seen.remove(s[left])', '            left += 1', '        seen.add(s[right])', '        best = max(best, right - left + 1)', '    return best'],
		run: function (inp) {
			var s = String(inp.s).slice(0, 16).split(''), r = recorder(), seen = [], left = 0, best = 0;
			r('The window [left, right] always holds letters with no repeats. The set remembers which letters are inside.', 2, { arrays: [{ values: s }], chips: { name: 'seen', items: [] }, vars: { best: 0 } });
			for (var right = 0; right < s.length; right++) {
				var c = {}; c[right] = 'active';
				r('Try to add "' + s[right] + '".', 5, { arrays: [{ values: s, cls: c, range: [left, right], ptr: { left: left, right: right } }], chips: { name: 'seen', items: seen }, vars: { best: best } });
				while (seen.indexOf(s[right]) !== -1) {
					var c2 = {}; c2[left] = 'remove'; c2[right] = 'active';
					r('"' + s[right] + '" is already in the window. Shrink from the left: drop "' + s[left] + '".', 6, { arrays: [{ values: s, cls: c2, range: [left, right], ptr: { left: left, right: right } }], chips: { name: 'seen', items: seen, hl: s[left] }, vars: { best: best } });
					seen.splice(seen.indexOf(s[left]), 1); left++;
				}
				seen.push(s[right]);
				best = Math.max(best, right - left + 1);
				r('Window "' + s.slice(left, right + 1).join('') + '" has length ' + (right - left + 1) + '. best = ' + best + '.', 9, { arrays: [{ values: s, range: [left, right], ptr: { left: left, right: right } }], chips: { name: 'seen', items: seen, hl: s[right] }, vars: { best: best } });
			}
			r('Each letter enters and leaves the window at most once: O(n).', 10, { arrays: [{ values: s }], vars: { best: best }, out: 'return ' + best });
			return r.steps;
		}
	};

	A['prefix-sum'] = {
		title: 'Prefix sums: any range sum in O(1)',
		inputs: [{ key: 'arr', label: 'Array', type: 'array' }, { key: 'l', label: 'Range start', type: 'number' }, { key: 'r', label: 'Range end', type: 'number' }],
		defaults: { arr: [3, 1, 4, 1, 5, 9, 2, 6], l: 2, r: 5 },
		code: ['def build_prefix(arr):', '    prefix = [0]', '    for x in arr:', '        prefix.append(prefix[-1] + x)', '    return prefix', '', 'def range_sum(prefix, l, r):', '    return prefix[r + 1] - prefix[l]'],
		run: function (inp) {
			var a = inp.arr, r = recorder(), p = [0];
			var lo = Math.max(0, Math.min(inp.l, a.length - 1)), hi = Math.max(lo, Math.min(inp.r, a.length - 1));
			r('prefix[i] = sum of the first i items. Start with prefix[0] = 0.', 2, { arrays: [{ name: 'arr', values: a }, { name: 'prefix', values: p }] });
			a.forEach(function (x, i) {
				p.push(p[p.length - 1] + x);
				var c = {}; c[i] = 'active'; var pc = {}; pc[i + 1] = 'add';
				r('prefix[' + (i + 1) + '] = prefix[' + i + '] + ' + x + ' = ' + p[i + 1], 4, { arrays: [{ name: 'arr', values: a, cls: c }, { name: 'prefix', values: p, cls: pc }] });
			});
			var ac = {}; for (var i = lo; i <= hi; i++) ac[i] = 'found';
			var pc2 = {}; pc2[hi + 1] = 'add'; pc2[lo] = 'remove';
			r('Sum of arr[' + lo + '…' + hi + '] = prefix[' + (hi + 1) + '] − prefix[' + lo + '] = ' + p[hi + 1] + ' − ' + p[lo] + ' = ' + (p[hi + 1] - p[lo]) + '. One subtraction, no loop!', 8, { arrays: [{ name: 'arr', values: a, cls: ac, range: [lo, hi] }, { name: 'prefix', values: p, cls: pc2 }], out: 'range_sum = ' + (p[hi + 1] - p[lo]) });
			return r.steps;
		}
	};

	A['kadane'] = {
		title: "Kadane's algorithm: maximum subarray",
		inputs: [{ key: 'arr', label: 'Array', type: 'array' }],
		defaults: { arr: [-2, 1, -3, 4, -1, 2, 1, -5, 4] },
		code: ['def max_subarray(nums):', '    cur = best = nums[0]', '    for x in nums[1:]:', '        cur = max(x, cur + x)', '        best = max(best, cur)', '    return best'],
		run: function (inp) {
			var a = inp.arr, r = recorder(), cur = a[0], best = a[0], start = 0, bs = 0, be = 0;
			r('cur = best sum of a subarray ending HERE. Start with the first number.', 2, { arrays: [{ values: a, range: [0, 0], ptr: { i: 0 } }], vars: { cur: cur, best: best } });
			for (var i = 1; i < a.length; i++) {
				var extend = cur + a[i], c = {}; c[i] = 'active';
				if (a[i] > extend) { cur = a[i]; start = i; r('Extending gives ' + extend + ', but ' + a[i] + ' alone is better: the old part was dragging us down. Start fresh at ' + a[i] + '.', 4, { arrays: [{ values: a, cls: c, range: [start, i], ptr: { i: i } }], vars: { cur: cur, best: best } }); }
				else { cur = extend; r('Extend the run: cur = ' + (cur - a[i]) + ' + ' + a[i] + ' = ' + cur + '.', 4, { arrays: [{ values: a, cls: c, range: [start, i], ptr: { i: i } }], vars: { cur: cur, best: best } }); }
				if (cur > best) { best = cur; bs = start; be = i; r('New record! best = ' + best, 5, { arrays: [{ values: a, range: [start, i], ptr: { i: i } }], vars: { cur: cur, best: best } }); }
			}
			var fc = {}; for (var j = bs; j <= be; j++) fc[j] = 'found';
			r('The best subarray is [' + a.slice(bs, be + 1).join(', ') + '] with sum ' + best + '. One pass: O(n).', 6, { arrays: [{ values: a, cls: fc, range: [bs, be] }], vars: { best: best }, out: 'return ' + best });
			return r.steps;
		}
	};

	A['two-sum'] = {
		title: 'Hash map: Two Sum',
		inputs: [{ key: 'arr', label: 'Numbers', type: 'array' }, { key: 'target', label: 'Target', type: 'number' }],
		defaults: { arr: [2, 7, 11, 15, 3, 6], target: 9 },
		code: ['def two_sum(nums, target):', '    seen = {}  # value -> index', '    for i, x in enumerate(nums):', '        need = target - x', '        if need in seen:', '            return [seen[need], i]', '        seen[x] = i', '    return []'],
		run: function (inp) {
			var a = inp.arr, t = inp.target, r = recorder(), seen = [];
			function ents() { return seen.map(function (e) { return [e[0], e[1]]; }); }
			r('A hash map remembers every number we have seen and where. Looking something up in it takes O(1).', 2, { arrays: [{ values: a }], map: { name: 'seen', entries: [] }, vars: { target: t } });
			for (var i = 0; i < a.length; i++) {
				var need = t - a[i], c = {}; c[i] = 'active';
				var hit = seen.filter(function (e) { return e[0] === need; })[0];
				r('x = ' + a[i] + '. We need ' + t + ' − ' + a[i] + ' = ' + need + '. Is ' + need + ' in the map?', 5, { arrays: [{ values: a, cls: c, ptr: { i: i } }], map: { name: 'seen', entries: ents(), hl: need }, vars: { target: t, need: need } });
				if (hit) {
					c[hit[1]] = 'found'; c[i] = 'found';
					r('Yes! ' + need + ' was at index ' + hit[1] + '. Answer: [' + hit[1] + ', ' + i + ']. One pass, O(n), instead of checking every pair O(n²).', 6, { arrays: [{ values: a, cls: c }], map: { name: 'seen', entries: ents(), hl: need }, out: 'return [' + hit[1] + ', ' + i + ']' });
					return r.steps;
				}
				seen.push([a[i], i]);
				r('No. Remember ' + a[i] + ' → index ' + i + ' for later.', 7, { arrays: [{ values: a, cls: c, ptr: { i: i } }], map: { name: 'seen', entries: ents(), hl: a[i] }, vars: { target: t } });
			}
			r('No two numbers add up to ' + t + '.', 8, { arrays: [{ values: a }], map: { name: 'seen', entries: ents() }, out: 'return []' });
			return r.steps;
		}
	};

	A['bubble-sort'] = {
		title: 'Bubble sort',
		inputs: [{ key: 'arr', label: 'Array', type: 'array' }],
		defaults: { arr: [5, 1, 4, 2, 8, 3] },
		code: ['def bubble_sort(arr):', '    n = len(arr)', '    for end in range(n - 1, 0, -1):', '        for i in range(end):', '            if arr[i] > arr[i + 1]:', '                arr[i], arr[i + 1] = arr[i + 1], arr[i]', '    return arr'],
		run: function (inp) {
			var a = inp.arr.slice(), r = recorder(), n = a.length, done = [];
			function base() { var c = {}; done.forEach(function (d) { c[d] = 'found'; }); return c; }
			r('Compare neighbours and swap them if they are in the wrong order. Big values "bubble" to the end.', 2, { arrays: [{ values: a }] });
			for (var end = n - 1; end > 0; end--) {
				for (var i = 0; i < end; i++) {
					var c = base(); c[i] = 'active'; c[i + 1] = 'active';
					r('Compare ' + a[i] + ' and ' + a[i + 1] + '.', 5, { arrays: [{ values: a, cls: c }] });
					if (a[i] > a[i + 1]) {
						var tmp = a[i]; a[i] = a[i + 1]; a[i + 1] = tmp;
						c[i] = 'swap'; c[i + 1] = 'swap';
						r(a[i + 1] + ' > ' + a[i] + ', so swap them.', 6, { arrays: [{ values: a, cls: c }] });
					}
				}
				done.push(end);
				r(a[end] + ' is now in its final place.', 3, { arrays: [{ values: a, cls: base() }] });
			}
			done.push(0);
			r('Sorted! Two nested loops make bubble sort O(n²): fine for tiny lists, too slow for big ones.', 7, { arrays: [{ values: a, cls: base() }], out: '[' + a.join(', ') + ']' });
			return r.steps;
		}
	};

	A['insertion-sort'] = {
		title: 'Insertion sort',
		inputs: [{ key: 'arr', label: 'Array', type: 'array' }],
		defaults: { arr: [7, 3, 9, 1, 5, 2] },
		code: ['def insertion_sort(arr):', '    for i in range(1, len(arr)):', '        key = arr[i]', '        j = i - 1', '        while j >= 0 and arr[j] > key:', '            arr[j + 1] = arr[j]', '            j -= 1', '        arr[j + 1] = key', '    return arr'],
		run: function (inp) {
			var a = inp.arr.slice(), r = recorder();
			r('Like sorting playing cards in your hand: the left part is always sorted, and we insert the next card into it.', 1, { arrays: [{ values: a, range: [0, 0] }] });
			for (var i = 1; i < a.length; i++) {
				var key = a[i], j = i - 1, c = {}; c[i] = 'active';
				r('Take key = ' + key + ' and find its place in the sorted part.', 3, { arrays: [{ values: a, cls: c, range: [0, i - 1] }], vars: { key: key } });
				while (j >= 0 && a[j] > key) {
					a[j + 1] = a[j];
					var c2 = {}; c2[j + 1] = 'swap';
					r(a[j] + ' > ' + key + ': shift it one step right.', 6, { arrays: [{ values: a, cls: c2, range: [0, i], ptr: { j: j } }], vars: { key: key } });
					j--;
				}
				a[j + 1] = key;
				var c3 = {}; c3[j + 1] = 'found';
				r('Drop ' + key + ' into index ' + (j + 1) + '.', 8, { arrays: [{ values: a, cls: c3, range: [0, i] }], vars: { key: key } });
			}
			r('Sorted. O(n²) in the worst case, but very fast when the list is almost sorted.', 9, { arrays: [{ values: a, cls: clsMap(a.map(function (_, i) { return [i, 'found']; })) }], out: '[' + a.join(', ') + ']' });
			return r.steps;
		}
	};

	A['merge-sort'] = {
		title: 'Merge sort',
		inputs: [{ key: 'arr', label: 'Array', type: 'array' }],
		defaults: { arr: [38, 27, 43, 3, 9, 82, 10] },
		code: ['def merge_sort(arr):', '    if len(arr) <= 1:', '        return arr', '    mid = len(arr) // 2', '    left = merge_sort(arr[:mid])', '    right = merge_sort(arr[mid:])', '    return merge(left, right)', '', 'def merge(a, b):', '    out, i, j = [], 0, 0', '    while i < len(a) and j < len(b):', '        if a[i] <= b[j]:', '            out.append(a[i]); i += 1', '        else:', '            out.append(b[j]); j += 1', '    return out + a[i:] + b[j:]'],
		run: function (inp) {
			var a = inp.arr.slice(), r = recorder();
			function sort(lo, hi, depth) {
				if (hi - lo <= 1) return;
				var mid = Math.floor((lo + hi) / 2);
				r('Split [' + a.slice(lo, hi).join(', ') + '] into two halves.', 4, { arrays: [{ values: a, range: [lo, hi - 1], cls: clsMap([[mid, 'active']]) }], vars: { depth: depth } });
				sort(lo, mid, depth + 1); sort(mid, hi, depth + 1);
				var L = a.slice(lo, mid), R = a.slice(mid, hi), out = [], i = 0, j = 0;
				while (i < L.length && j < R.length) {
					if (L[i] <= R[j]) out.push(L[i++]); else out.push(R[j++]);
					r('Merge: take the smaller front item → ' + out[out.length - 1], 12, { arrays: [{ values: a, range: [lo, hi - 1] }, { name: 'left', values: L, cls: clsMap([[i - 1 < L.length ? i : -1, 'active']]) }, { name: 'right', values: R, cls: clsMap([[j < R.length ? j : -1, 'active']]) }, { name: 'merged', values: out }], vars: { depth: depth } });
				}
				out = out.concat(L.slice(i)).concat(R.slice(j));
				for (var k = 0; k < out.length; k++) a[lo + k] = out[k];
				var c = {}; for (k = lo; k < hi; k++) c[k] = 'found';
				r('Merged into [' + out.join(', ') + '].', 16, { arrays: [{ values: a, cls: c, range: [lo, hi - 1] }], vars: { depth: depth } });
			}
			r('Divide and conquer: split until pieces have one item, then merge sorted pieces back together.', 1, { arrays: [{ values: a }] });
			sort(0, a.length, 0);
			r('Sorted! log n levels of splitting × n work per level = O(n log n).', 7, { arrays: [{ values: a, cls: clsMap(a.map(function (_, i) { return [i, 'found']; })) }], out: '[' + a.join(', ') + ']' });
			return r.steps;
		}
	};

	A['quick-sort'] = {
		title: 'Quick sort (partition)',
		inputs: [{ key: 'arr', label: 'Array', type: 'array' }],
		defaults: { arr: [10, 80, 30, 90, 40, 50, 70] },
		code: ['def quick_sort(arr, lo=0, hi=None):', '    if hi is None: hi = len(arr) - 1', '    if lo < hi:', '        p = partition(arr, lo, hi)', '        quick_sort(arr, lo, p - 1)', '        quick_sort(arr, p + 1, hi)', '    return arr', '', 'def partition(arr, lo, hi):', '    pivot = arr[hi]', '    i = lo - 1', '    for j in range(lo, hi):', '        if arr[j] < pivot:', '            i += 1', '            arr[i], arr[j] = arr[j], arr[i]', '    arr[i + 1], arr[hi] = arr[hi], arr[i + 1]', '    return i + 1'],
		run: function (inp) {
			var a = inp.arr.slice(), r = recorder(), placed = {};
			function base() { var c = {}; Object.keys(placed).forEach(function (k) { c[k] = 'found'; }); return c; }
			function qs(lo, hi) {
				if (lo > hi) return;
				if (lo === hi) { placed[lo] = 1; return; }
				var pivot = a[hi], i = lo - 1;
				var c = base(); c[hi] = 'pivot';
				r('Pick pivot = ' + pivot + '. Goal: smaller numbers to its left, bigger to its right.', 10, { arrays: [{ values: a, cls: c, range: [lo, hi] }], vars: { pivot: pivot } });
				for (var j = lo; j < hi; j++) {
					c = base(); c[hi] = 'pivot'; c[j] = 'active';
					var ptr = { j: j }; if (i >= lo) ptr.i = i;
					if (a[j] < pivot) {
						i++;
						var t = a[i]; a[i] = a[j]; a[j] = t;
						c[i] = 'swap';
						ptr = { i: i, j: j };
						r(a[i] + ' < ' + pivot + ': move it into the "small" zone (swap with index ' + i + ').', 15, { arrays: [{ values: a, cls: c, range: [lo, hi], ptr: ptr }], vars: { pivot: pivot } });
					} else {
						r(a[j] + ' ≥ ' + pivot + ': leave it in the "big" zone.', 13, { arrays: [{ values: a, cls: c, range: [lo, hi], ptr: ptr }], vars: { pivot: pivot } });
					}
				}
				var tt = a[i + 1]; a[i + 1] = a[hi]; a[hi] = tt;
				placed[i + 1] = 1;
				r('Put the pivot right after the small zone. ' + pivot + ' is now in its final place!', 16, { arrays: [{ values: a, cls: base(), range: [lo, hi] }], vars: { pivot: pivot } });
				qs(lo, i); qs(i + 2, hi);
			}
			r('Quick sort picks a pivot, partitions around it, then sorts each side.', 1, { arrays: [{ values: a }] });
			qs(0, a.length - 1);
			a.forEach(function (_, i) { placed[i] = 1; });
			r('Sorted! Average O(n log n); worst case O(n²) if the pivot is always the smallest or largest.', 7, { arrays: [{ values: a, cls: base() }], out: '[' + a.join(', ') + ']' });
			return r.steps;
		}
	};

	A['valid-parentheses'] = {
		title: 'Stack: valid parentheses',
		inputs: [{ key: 's', label: 'Brackets', type: 'string' }],
		defaults: { s: '{[()()]}' },
		code: ['def is_valid(s):', '    pairs = {")": "(", "]": "[", "}": "{"}', '    stack = []', '    for ch in s:', '        if ch in "([{":', '            stack.append(ch)', '        elif not stack or stack.pop() != pairs[ch]:', '            return False', '    return not stack'],
		run: function (inp) {
			var s = String(inp.s).slice(0, 18).split(''), r = recorder(), st = [], pairs = { ')': '(', ']': '[', '}': '{' };
			r('A stack is like a pile of plates: last in, first out. Every closing bracket must match the most recent open one.', 3, { arrays: [{ values: s }], stack: { name: 'stack', items: [] } });
			for (var i = 0; i < s.length; i++) {
				var ch = s[i], c = {}; c[i] = 'active';
				if ('([{'.indexOf(ch) !== -1) {
					st.push(ch);
					r('"' + ch + '" opens a group: push it on the stack.', 6, { arrays: [{ values: s, cls: c, ptr: { ch: i } }], stack: { name: 'stack', items: st, hl: st.length - 1 } });
				} else {
					var top = st.length ? st[st.length - 1] : null;
					if (top !== pairs[ch]) {
						c[i] = 'bad';
						r('"' + ch + '" needs "' + pairs[ch] + '" on top, but the top is ' + (top ? '"' + top + '"' : 'empty') + '. Invalid!', 8, { arrays: [{ values: s, cls: c, ptr: { ch: i } }], stack: { name: 'stack', items: st }, out: 'return False' });
						return r.steps;
					}
					st.pop();
					c[i] = 'found';
					r('"' + ch + '" matches the top "' + top + '": pop it.', 7, { arrays: [{ values: s, cls: c, ptr: { ch: i } }], stack: { name: 'stack', items: st } });
				}
			}
			r(st.length ? 'Some brackets were never closed: invalid.' : 'The stack is empty: every bracket was matched. Valid!', 9, { arrays: [{ values: s }], stack: { name: 'stack', items: st }, out: 'return ' + (st.length ? 'False' : 'True') });
			return r.steps;
		}
	};

	A['next-greater'] = {
		title: 'Monotonic stack: next greater element',
		inputs: [{ key: 'arr', label: 'Array', type: 'array' }],
		defaults: { arr: [2, 1, 2, 4, 3, 1, 5] },
		code: ['def next_greater(nums):', '    res = [-1] * len(nums)', '    stack = []  # indexes, values decreasing', '    for i, x in enumerate(nums):', '        while stack and nums[stack[-1]] < x:', '            res[stack.pop()] = x', '        stack.append(i)', '    return res'],
		run: function (inp) {
			var a = inp.arr, r = recorder(), res = a.map(function () { return -1; }), st = [];
			function sv() { return st.map(function (i) { return a[i] + ' (i' + i + ')'; }); }
			r('The stack keeps indexes still waiting for a bigger number, with values decreasing from bottom to top.', 3, { arrays: [{ name: 'nums', values: a }, { name: 'res', values: res }], stack: { name: 'waiting', items: [] } });
			for (var i = 0; i < a.length; i++) {
				var c = {}; c[i] = 'active';
				r('Next number: ' + a[i] + '.', 4, { arrays: [{ name: 'nums', values: a, cls: c, ptr: { i: i } }, { name: 'res', values: res }], stack: { name: 'waiting', items: sv() } });
				while (st.length && a[st[st.length - 1]] < a[i]) {
					var j = st.pop(); res[j] = a[i];
					var rc = {}; rc[j] = 'found'; var nc = {}; nc[i] = 'active'; nc[j] = 'found';
					r(a[j] + ' finally found a bigger number to its right: ' + a[i] + '. Pop it and record the answer.', 6, { arrays: [{ name: 'nums', values: a, cls: nc, ptr: { i: i } }, { name: 'res', values: res, cls: rc }], stack: { name: 'waiting', items: sv() } });
				}
				st.push(i);
				r('Push index ' + i + ' (' + a[i] + ') to wait for its bigger number.', 7, { arrays: [{ name: 'nums', values: a, cls: c, ptr: { i: i } }, { name: 'res', values: res }], stack: { name: 'waiting', items: sv(), hl: st.length - 1 } });
			}
			r('Whatever is still waiting has no bigger number: -1. Each index is pushed and popped once: O(n).', 8, { arrays: [{ name: 'nums', values: a }, { name: 'res', values: res }], stack: { name: 'waiting', items: sv() }, out: '[' + res.join(', ') + ']' });
			return r.steps;
		}
	};

	A['reverse-list'] = {
		title: 'Linked list: reverse it',
		inputs: [{ key: 'arr', label: 'List values', type: 'array' }],
		defaults: { arr: [1, 2, 3, 4, 5] },
		code: ['def reverse(head):', '    prev, curr = None, head', '    while curr:', '        nxt = curr.next', '        curr.next = prev', '        prev = curr', '        curr = nxt', '    return prev'],
		run: function (inp) {
			var v = inp.arr.slice(0, 8), r = recorder(), next = v.map(function (_, i) { return i + 1 < v.length ? i + 1 : null; });
			var prev = null, curr = 0;
			function ptr(extra) { var p = {}; if (prev !== null) p.prev = prev; if (curr !== null && curr < v.length) p.curr = curr; Object.keys(extra || {}).forEach(function (k) { if (extra[k] !== null) p[k] = extra[k]; }); return p; }
			r('Each node points to the next one. We will flip every arrow, one node at a time, with three pointers.', 2, { list: { values: v, next: next, ptr: ptr() } });
			while (curr !== null) {
				var nxt = next[curr];
				r('Save nxt = ' + (nxt === null ? 'None' : v[nxt]) + ' so we don\'t lose the rest of the list.', 4, { list: { values: v, next: next, ptr: ptr({ nxt: nxt }), cls: clsMap([[curr, 'active']]) } });
				next[curr] = prev;
				r('Flip the arrow: ' + v[curr] + ' now points back to ' + (prev === null ? 'None' : v[prev]) + '.', 5, { list: { values: v, next: next, ptr: ptr({ nxt: nxt }), cls: clsMap([[curr, 'swap']]) } });
				prev = curr; curr = nxt;
				r('Move prev and curr one step forward.', 7, { list: { values: v, next: next, ptr: ptr() } });
			}
			r('curr is None: done! prev is the new head. O(n) time, O(1) extra space.', 8, { list: { values: v, next: next, ptr: { head: prev }, cls: clsMap(v.map(function (_, i) { return [i, 'found']; })) }, out: 'return node ' + v[prev] });
			return r.steps;
		}
	};

	A['fast-slow'] = {
		title: 'Fast & slow pointers: find the middle',
		inputs: [{ key: 'arr', label: 'List values', type: 'array' }],
		defaults: { arr: [1, 2, 3, 4, 5, 6, 7] },
		code: ['def middle(head):', '    slow = fast = head', '    while fast and fast.next:', '        slow = slow.next', '        fast = fast.next.next', '    return slow'],
		run: function (inp) {
			var v = inp.arr.slice(0, 9), r = recorder(), next = v.map(function (_, i) { return i + 1 < v.length ? i + 1 : null; });
			var s = 0, f = 0;
			r('The tortoise and the hare: slow moves 1 step, fast moves 2. When fast reaches the end, slow is in the middle.', 2, { list: { values: v, next: next, ptr: { slow: s, fast: f } } });
			while (f !== null && next[f] !== null) {
				s = next[s]; f = next[next[f]];
				var p = { slow: s }; if (f !== null) p.fast = f;
				r('slow → ' + v[s] + ', fast → ' + (f === null ? 'end' : v[f]) + '.', 5, { list: { values: v, next: next, ptr: p, cls: clsMap([[s, 'active']]) } });
			}
			r('Fast can\'t move any further, so slow (' + v[s] + ') is the middle. One pass, no counting needed. The same trick detects cycles: if there is a loop, fast eventually catches slow.', 6, { list: { values: v, next: next, ptr: { slow: s }, cls: clsMap([[s, 'found']]) }, out: 'return ' + v[s] });
			return r.steps;
		}
	};

	/* Trees ----------------------------------------------------------------- */
	function toTree(level, cls) {
		if (!level.length || level[0] === null) return null;
		var nodes = level.map(function (v, i) { return v === null ? null : { label: v, i: i, children: [] }; });
		nodes.forEach(function (n, i) {
			if (!n) return;
			var l = nodes[2 * i + 1], rr = nodes[2 * i + 2];
			if (l || rr) n.children = [l || { label: '', ghost: true, children: [] }, rr || { label: '', ghost: true, children: [] }];
			if (cls && cls[i]) n.cls = cls[i];
		});
		return nodes[0];
	}

	A['tree-traversal'] = {
		title: 'Binary tree traversals',
		inputs: [{ key: 'tree', label: 'Tree (level order, null = empty)', type: 'array' }, { key: 'order', label: 'Order', type: 'choice', options: ['inorder', 'preorder', 'postorder', 'levelorder'] }],
		defaults: { tree: [1, 2, 3, 4, 5, null, 6], order: 'inorder' },
		codes: {
			inorder: ['def inorder(node, out):', '    if not node:', '        return', '    inorder(node.left, out)', '    out.append(node.val)   # visit', '    inorder(node.right, out)'],
			preorder: ['def preorder(node, out):', '    if not node:', '        return', '    out.append(node.val)   # visit', '    preorder(node.left, out)', '    preorder(node.right, out)'],
			postorder: ['def postorder(node, out):', '    if not node:', '        return', '    postorder(node.left, out)', '    postorder(node.right, out)', '    out.append(node.val)   # visit'],
			levelorder: ['from collections import deque', 'def level_order(root):', '    out, q = [], deque([root])', '    while q:', '        node = q.popleft()', '        out.append(node.val)   # visit', '        if node.left: q.append(node.left)', '        if node.right: q.append(node.right)', '    return out']
		},
		run: function (inp) {
			var t = inp.tree, order = inp.order || 'inorder', r = recorder(), out = [], cls = {};
			this.code = this.codes[order] || this.codes.inorder;
			var visitLine = { inorder: 5, preorder: 4, postorder: 6, levelorder: 6 }[order];
			var names = { inorder: 'left → node → right', preorder: 'node → left → right', postorder: 'left → right → node', levelorder: 'level by level, using a queue' };
			r(order + ' visits ' + names[order] + '.', 1, { tree: { root: toTree(t, cls) }, chips: { name: 'output', items: [] } });
			function visit(i, callStack) {
				cls[i] = 'found'; out.push(t[i]);
				r('Visit ' + t[i] + '.', visitLine, { tree: { root: toTree(t, cls) }, chips: { name: 'output', items: out, hl: t[i] }, stack: callStack ? { name: 'call stack', items: callStack } : undefined });
			}
			if (order === 'levelorder') {
				var q = [0];
				while (q.length) {
					var i = q.shift(); var c2 = clone(cls); c2[i] = 'active';
					r('Take ' + t[i] + ' from the front of the queue.', 5, { tree: { root: toTree(t, c2) }, queue: { name: 'queue', items: [t[i]].concat(q.map(function (k) { return t[k]; })), hl: 0 }, chips: { name: 'output', items: out } });
					visit(i);
					[2 * i + 1, 2 * i + 2].forEach(function (k) { if (k < t.length && t[k] !== null) q.push(k); });
					r('Add its children to the back of the queue.', 8, { tree: { root: toTree(t, cls) }, queue: { name: 'queue', items: q.map(function (k) { return t[k]; }) }, chips: { name: 'output', items: out } });
				}
			} else {
				var calls = [];
				(function go(i) {
					if (i >= t.length || t[i] === null) return;
					calls.push(String(t[i]));
					var c3 = clone(cls); c3[i] = c3[i] || 'active';
					r('Call on node ' + t[i] + '.', 1, { tree: { root: toTree(t, c3) }, stack: { name: 'call stack', items: calls }, chips: { name: 'output', items: out } });
					if (order === 'preorder') visit(i, calls);
					go(2 * i + 1);
					if (order === 'inorder') visit(i, calls);
					go(2 * i + 2);
					if (order === 'postorder') visit(i, calls);
					calls.pop();
				})(0);
			}
			r('Done: ' + out.join(', ') + '. Every node is visited once: O(n).', 0, { tree: { root: toTree(t, cls) }, chips: { name: 'output', items: out } , out: '[' + out.join(', ') + ']' });
			return r.steps;
		}
	};

	A['bst-search'] = {
		title: 'Binary search tree: search',
		inputs: [{ key: 'tree', label: 'BST (level order)', type: 'array' }, { key: 'target', label: 'Target', type: 'number' }],
		defaults: { tree: [8, 3, 10, 1, 6, null, 14, null, null, 4, 7], target: 7 },
		code: ['def search(node, target):', '    while node:', '        if target == node.val:', '            return node', '        elif target < node.val:', '            node = node.left', '        else:', '            node = node.right', '    return None'],
		run: function (inp) {
			var t = inp.tree, x = inp.target, r = recorder(), cls = {}, i = 0, steps = 0;
			r('In a BST, everything left of a node is smaller and everything right is bigger. So each step picks ONE side.', 1, { tree: { root: toTree(t, cls) }, vars: { target: x } });
			while (i < t.length && t[i] !== null) {
				steps++;
				var c = clone(cls); c[i] = 'active';
				if (t[i] === x) { c[i] = 'found'; r('Found ' + x + ' after ' + steps + ' steps. Balanced BST: O(log n).', 4, { tree: { root: toTree(t, c) }, vars: { target: x }, out: 'found ' + x }); return r.steps; }
				var left = x < t[i];
				r(x + (left ? ' < ' : ' > ') + t[i] + ': go ' + (left ? 'left' : 'right') + ', skipping the whole other side.', left ? 6 : 8, { tree: { root: toTree(t, c) }, vars: { target: x } });
				cls[i] = 'dim'; i = left ? 2 * i + 1 : 2 * i + 2;
			}
			r(x + ' is not in the tree.', 9, { tree: { root: toTree(t, cls) }, vars: { target: x }, out: 'None' });
			return r.steps;
		}
	};

	A['heap'] = {
		title: 'Min-heap: push and pop',
		inputs: [{ key: 'arr', label: 'Numbers to push', type: 'array' }],
		defaults: { arr: [5, 3, 8, 1, 9, 2] },
		code: ['import heapq', 'heap = []', 'for x in nums:', '    heapq.heappush(heap, x)   # sift up', '', 'smallest = heapq.heappop(heap)   # sift down', '# heap[0] is always the minimum'],
		run: function (inp) {
			var h = [], r = recorder();
			function scene(cls, extra) { var s = { tree: { root: toTree(h, cls) }, arrays: [{ name: 'heap array', values: h.slice(), cls: cls }] }; Object.keys(extra || {}).forEach(function (k) { s[k] = extra[k]; }); return s; }
			r('A min-heap is a tree where every parent is smaller than its children, stored in a plain array. The smallest is always at the top.', 2, scene({}));
			inp.arr.slice(0, 10).forEach(function (x) {
				h.push(x); var i = h.length - 1;
				r('Push ' + x + ': add it at the end.', 4, scene(clsMap([[i, 'active']])));
				while (i > 0 && h[Math.floor((i - 1) / 2)] > h[i]) {
					var p = Math.floor((i - 1) / 2), t = h[p]; h[p] = h[i]; h[i] = t;
					r(h[i] + ' > ' + h[p] + ': swap with the parent (sift up).', 4, scene(clsMap([[p, 'swap'], [i, 'swap']])));
					i = p;
				}
			});
			var top = h[0]; h[0] = h[h.length - 1]; h.pop();
			r('Pop the minimum (' + top + '). Move the last item to the top…', 6, scene(clsMap([[0, 'active']]), { out: 'popped ' + top }));
			var i2 = 0;
			for (;;) {
				var l = 2 * i2 + 1, rr = 2 * i2 + 2, m = i2;
				if (l < h.length && h[l] < h[m]) m = l;
				if (rr < h.length && h[rr] < h[m]) m = rr;
				if (m === i2) break;
				var tt = h[m]; h[m] = h[i2]; h[i2] = tt;
				r('…and sift it down: swap with the smaller child.', 6, scene(clsMap([[m, 'swap'], [i2, 'swap']]), { out: 'popped ' + top }));
				i2 = m;
			}
			r('Heap order restored. Push and pop are O(log n); peeking at the minimum is O(1). Perfect for "top k" and scheduling problems.', 7, scene(clsMap([[0, 'found']]), { out: 'popped ' + top }));
			return r.steps;
		}
	};

	/* Recursion trees --------------------------------------------------------- */
	A['fib-tree'] = {
		title: 'Recursion: Fibonacci call tree',
		inputs: [{ key: 'n', label: 'n (1-6)', type: 'number' }, { key: 'memo', label: 'Use memo', type: 'choice', options: ['no', 'yes'] }],
		defaults: { n: 5, memo: 'no' },
		code: ['def fib(n, memo=None):', '    if n <= 1:', '        return n', '    if memo is not None and n in memo:', '        return memo[n]', '    result = fib(n - 1, memo) + fib(n - 2, memo)', '    if memo is not None:', '        memo[n] = result', '    return result'],
		run: function (inp) {
			var n = Math.max(1, Math.min(6, inp.n)), useMemo = inp.memo === 'yes', r = recorder(), memo = {}, calls = 0;
			var root = { label: 'fib(' + n + ')', children: [] };
			function snap(note, line) { r(note, line, { tree: { root: root }, vars: { calls: calls }, map: useMemo ? { name: 'memo', entries: Object.keys(memo).map(function (k) { return [k, memo[k]]; }) } : undefined }); }
			function go(k, node) {
				calls++; node.cls = 'active';
				snap('Call fib(' + k + ').', 1);
				if (k <= 1) { node.cls = 'found'; node.label = 'fib(' + k + ')=' + k; snap('Base case: fib(' + k + ') = ' + k + '.', 3); return k; }
				if (useMemo && memo[k] !== undefined) { node.cls = 'memo'; node.label = 'fib(' + k + ')=' + memo[k]; snap('Already in the memo! Reuse ' + memo[k] + ' without recomputing.', 5); return memo[k]; }
				var a = { label: 'fib(' + (k - 1) + ')', children: [] }, b = { label: 'fib(' + (k - 2) + ')', children: [] };
				node.children = [a, b]; node.cls = 'wait';
				var res = go(k - 1, a) + go(k - 2, b);
				if (useMemo) memo[k] = res;
				node.cls = 'found'; node.label = 'fib(' + k + ')=' + res;
				snap('fib(' + k + ') = ' + res + '.', 9);
				return res;
			}
			var ans = go(n, root);
			snap(useMemo ? 'With a memo we made only ' + calls + ' calls: O(n). That is dynamic programming!' : 'Notice the repeated calls (fib(2), fib(3)…)? ' + calls + ' calls in total: about O(2ⁿ). Switch "Use memo" to yes and replay.', 9);
			r.steps[r.steps.length - 1].scene.out = 'fib(' + n + ') = ' + ans;
			return r.steps;
		}
	};

	A['subsets'] = {
		title: 'Backtracking: all subsets',
		inputs: [{ key: 'arr', label: 'Items (up to 4)', type: 'array' }],
		defaults: { arr: [1, 2, 3] },
		code: ['def subsets(nums):', '    res, path = [], []', '    def backtrack(i):', '        if i == len(nums):', '            res.append(path[:])', '            return', '        path.append(nums[i])      # choose', '        backtrack(i + 1)', '        path.pop()                # un-choose', '        backtrack(i + 1)', '    backtrack(0)', '    return res'],
		run: function (inp) {
			var a = inp.arr.slice(0, 4), r = recorder(), res = [], path = [];
			var root = { label: '{}', children: [] };
			function snap(note, line) { r(note, line, { tree: { root: root }, chips: { name: 'path', items: path.slice() }, stack: { name: 'results', items: res.map(function (s) { return '{' + s.join(',') + '}'; }) } }); }
			function bt(i, node) {
				node.cls = 'active';
				if (i === a.length) { res.push(path.slice()); node.cls = 'found'; snap('Reached the end: save {' + path.join(',') + '}.', 5); return; }
				var yes = { label: '+' + a[i], children: [] }, no = { label: '−' + a[i], children: [] };
				node.children = [yes, no];
				snap('Decision for ' + a[i] + ': take it, or leave it?', 7);
				node.cls = 'wait';
				path.push(a[i]); bt(i + 1, yes);
				path.pop(); snap('Back up (backtrack) and try NOT taking ' + a[i] + '.', 9);
				bt(i + 1, no);
				node.cls = 'done';
			}
			bt(0, root);
			snap('Every path from the top to a leaf is one subset: 2ⁿ = ' + res.length + ' subsets. Choose → explore → un-choose is the heart of all backtracking.', 12);
			return r.steps;
		}
	};

	/* Graphs ------------------------------------------------------------------ */
	function parseEdges(str, weighted) {
		return String(str).split(',').map(function (e) {
			var m = e.trim().match(/^([A-Za-z0-9]+)\s*-\s*([A-Za-z0-9]+)(?:\s*:\s*(\d+))?$/);
			return m ? [m[1], m[2], weighted ? parseInt(m[3] || '1', 10) : undefined] : null;
		}).filter(Boolean);
	}
	function layout(edges) {
		var ids = [];
		edges.forEach(function (e) { [e[0], e[1]].forEach(function (x) { if (ids.indexOf(x) === -1) ids.push(x); }); });
		ids.sort();
		return ids.map(function (id, i) {
			var ang = -Math.PI / 2 + i * 2 * Math.PI / ids.length;
			return { id: id, x: Math.round(160 + 120 * Math.cos(ang)), y: Math.round(140 + 105 * Math.sin(ang)) };
		});
	}
	function adj(edges, directed) {
		var g = {};
		edges.forEach(function (e) {
			(g[e[0]] = g[e[0]] || []).push(e[1]);
			if (!directed) (g[e[1]] = g[e[1]] || []).push(e[0]);
			g[e[1]] = g[e[1]] || [];
		});
		Object.keys(g).forEach(function (k) { g[k].sort(); });
		return g;
	}

	A['graph-bfs'] = {
		title: 'Graph: breadth-first search',
		inputs: [{ key: 'edges', label: 'Edges', type: 'string' }, { key: 'start', label: 'Start', type: 'string' }],
		defaults: { edges: 'A-B, A-C, B-D, C-D, C-E, D-F, E-F', start: 'A' },
		code: ['from collections import deque', 'def bfs(graph, start):', '    visited = {start}', '    q = deque([start])', '    order = []', '    while q:', '        node = q.popleft()', '        order.append(node)', '        for nb in graph[node]:', '            if nb not in visited:', '                visited.add(nb)', '                q.append(nb)', '    return order'],
		run: function (inp) {
			var edges = parseEdges(inp.edges), nodes = layout(edges), g = adj(edges), r = recorder();
			var s = g[inp.start] ? inp.start : nodes[0].id, visited = [s], q = [s], order = [], cls = {};
			cls[s] = 'wait';
			function scene(extra) { var sc = { graph: { nodes: nodes, edges: edges, cls: clone(cls) }, queue: { name: 'queue', items: q.slice() }, chips: { name: 'order', items: order.slice() } }; Object.keys(extra || {}).forEach(function (k) { sc[k] = extra[k]; }); return sc; }
			r('BFS explores in waves: first the start, then all its neighbours, then their neighbours… A queue (first in, first out) keeps the order.', 4, scene());
			while (q.length) {
				var node = q.shift(); order.push(node); cls[node] = 'active';
				r('Take ' + node + ' from the front of the queue and visit it.', 7, scene());
				g[node].forEach(function (nb) {
					if (visited.indexOf(nb) === -1) {
						visited.push(nb); q.push(nb); cls[nb] = 'wait';
						var ec = {}; ec[node + '-' + nb] = 'active'; ec[nb + '-' + node] = 'active';
						r('Neighbour ' + nb + ' is new: mark it and add it to the back of the queue.', 12, scene({ graph: { nodes: nodes, edges: edges, cls: clone(cls), ecls: ec } }));
					}
				});
				cls[node] = 'found';
			}
			r('Visited every reachable node: ' + order.join(' → ') + '. O(V + E). In an unweighted graph, BFS finds shortest paths!', 13, scene({ out: order.join(' → ') }));
			return r.steps;
		}
	};

	A['graph-dfs'] = {
		title: 'Graph: depth-first search',
		inputs: [{ key: 'edges', label: 'Edges', type: 'string' }, { key: 'start', label: 'Start', type: 'string' }],
		defaults: { edges: 'A-B, A-C, B-D, C-D, C-E, D-F, E-F', start: 'A' },
		code: ['def dfs(graph, node, visited, order):', '    visited.add(node)', '    order.append(node)', '    for nb in graph[node]:', '        if nb not in visited:', '            dfs(graph, nb, visited, order)', '    return order'],
		run: function (inp) {
			var edges = parseEdges(inp.edges), nodes = layout(edges), g = adj(edges), r = recorder();
			var s = g[inp.start] ? inp.start : nodes[0].id, visited = [], order = [], cls = {}, calls = [];
			function scene(ec, extra) { var sc = { graph: { nodes: nodes, edges: edges, cls: clone(cls), ecls: ec || {} }, stack: { name: 'call stack', items: calls.slice() }, chips: { name: 'order', items: order.slice() } }; Object.keys(extra || {}).forEach(function (k) { sc[k] = extra[k]; }); return sc; }
			r('DFS goes as deep as possible down one path, then backtracks. Recursion (or a stack) remembers the way back.', 1, scene());
			(function go(node, from) {
				visited.push(node); order.push(node); calls.push(node); cls[node] = 'active';
				var ec = {}; if (from) { ec[from + '-' + node] = 'active'; ec[node + '-' + from] = 'active'; }
				r('Visit ' + node + ' and dive deeper.', 3, scene(ec));
				g[node].forEach(function (nb) { if (visited.indexOf(nb) === -1) go(nb, node); });
				cls[node] = 'found'; calls.pop();
				r('All neighbours of ' + node + ' are done: backtrack.', 6, scene());
			})(s, null);
			r('Order: ' + order.join(' → ') + '. Also O(V + E). DFS is great for paths, cycles and connected parts.', 7, scene(null, { out: order.join(' → ') }));
			return r.steps;
		}
	};

	A['topo-sort'] = {
		title: "Topological sort (Kahn's algorithm)",
		inputs: [{ key: 'edges', label: 'Directed edges (a-b means a before b)', type: 'string' }],
		defaults: { edges: 'Math-Physics, Math-CS, CS-AI, Physics-AI, CS-Web' },
		code: ['from collections import deque', 'def topo_sort(graph, indeg):', '    q = deque(n for n in graph if indeg[n] == 0)', '    order = []', '    while q:', '        node = q.popleft()', '        order.append(node)', '        for nb in graph[node]:', '            indeg[nb] -= 1', '            if indeg[nb] == 0:', '                q.append(nb)', '    return order  # shorter than graph = cycle'],
		run: function (inp) {
			var edges = parseEdges(inp.edges), nodes = layout(edges), g = adj(edges, true), r = recorder(), indeg = {}, cls = {}, order = [];
			Object.keys(g).forEach(function (k) { indeg[k] = 0; });
			edges.forEach(function (e) { indeg[e[1]]++; });
			var q = Object.keys(g).filter(function (k) { return indeg[k] === 0; }).sort();
			function scene(extra) { var sc = { graph: { nodes: nodes, edges: edges, cls: clone(cls), directed: true }, map: { name: 'in-degree', entries: Object.keys(indeg).sort().map(function (k) { return [k, indeg[k]]; }) }, queue: { name: 'ready', items: q.slice() }, chips: { name: 'order', items: order.slice() } }; Object.keys(extra || {}).forEach(function (k) { sc[k] = extra[k]; }); return sc; }
			q.forEach(function (k) { cls[k] = 'wait'; });
			r('In-degree = how many things must come first. Anything with in-degree 0 is ready to go.', 3, scene());
			while (q.length) {
				var n = q.shift(); order.push(n); cls[n] = 'found';
				r('Do ' + n + ' next.', 7, scene());
				g[n].forEach(function (nb) {
					indeg[nb]--;
					if (indeg[nb] === 0) { q.push(nb); cls[nb] = 'wait'; r(nb + ' has no more prerequisites: it is ready.', 11, scene({ map: { name: 'in-degree', entries: Object.keys(indeg).sort().map(function (k) { return [k, indeg[k]]; }), hl: nb } })); }
					else r(nb + ' still waits for ' + indeg[nb] + ' more.', 9, scene({ map: { name: 'in-degree', entries: Object.keys(indeg).sort().map(function (k) { return [k, indeg[k]]; }), hl: nb } }));
				});
			}
			var ok = order.length === Object.keys(g).length;
			r(ok ? 'A valid order: ' + order.join(' → ') + '. Used for course schedules, build systems and task planning.' : 'Some nodes never became ready: the graph has a cycle, so no order exists.', 12, scene({ out: ok ? order.join(' → ') : 'cycle!' }));
			return r.steps;
		}
	};

	A['dijkstra'] = {
		title: "Dijkstra's shortest paths",
		inputs: [{ key: 'edges', label: 'Weighted edges (A-B:4)', type: 'string' }, { key: 'start', label: 'Start', type: 'string' }],
		defaults: { edges: 'A-B:4, A-C:2, C-B:1, B-D:5, C-D:8, C-E:10, D-E:2', start: 'A' },
		code: ['import heapq', 'def dijkstra(graph, start):', '    dist = {start: 0}', '    pq = [(0, start)]', '    while pq:', '        d, node = heapq.heappop(pq)', '        if d > dist[node]:', '            continue', '        for nb, w in graph[node]:', '            if d + w < dist.get(nb, float("inf")):', '                dist[nb] = d + w', '                heapq.heappush(pq, (d + w, nb))', '    return dist'],
		run: function (inp) {
			var edges = parseEdges(inp.edges, true), nodes = layout(edges), r = recorder(), g = {};
			edges.forEach(function (e) { (g[e[0]] = g[e[0]] || []).push([e[1], e[2]]); (g[e[1]] = g[e[1]] || []).push([e[0], e[2]]); });
			var s = g[inp.start] ? inp.start : nodes[0].id, dist = {}, pq = [[0, s]], cls = {}, done = {};
			dist[s] = 0;
			function dl() { return nodes.map(function (n) { return [n.id, dist[n.id] === undefined ? '∞' : dist[n.id]]; }); }
			function scene(ec, hl) { return { graph: { nodes: nodes, edges: edges, cls: clone(cls), ecls: ec || {} }, map: { name: 'dist', entries: dl(), hl: hl }, queue: { name: 'priority queue', items: pq.slice().sort(function (a, b) { return a[0] - b[0]; }).map(function (p) { return p[1] + ':' + p[0]; }) } }; }
			r('Always expand the closest node not yet finished. A priority queue hands it to us.', 4, scene());
			while (pq.length) {
				pq.sort(function (a, b) { return a[0] - b[0]; });
				var top = pq.shift(), d = top[0], n = top[1];
				if (done[n]) { r('Old entry for ' + n + ' (' + d + '): we already found a shorter path. Skip.', 8, scene()); continue; }
				done[n] = 1; cls[n] = 'found';
				r('Closest unfinished node: ' + n + ' at distance ' + d + '. This distance is now final.', 6, scene(null, n));
				(g[n] || []).forEach(function (p) {
					var nb = p[0], w = p[1], ec = {}; ec[n + '-' + nb] = 'active'; ec[nb + '-' + n] = 'active';
					if (!done[nb] && (dist[nb] === undefined || d + w < dist[nb])) {
						dist[nb] = d + w; pq.push([d + w, nb]); cls[nb] = 'wait';
						r('Through ' + n + ', ' + nb + ' costs ' + d + ' + ' + w + ' = ' + (d + w) + '. Better than before: update.', 11, scene(ec, nb));
					}
				});
			}
			var fin = scene(); fin.out = dl().map(function (d) { return d[0] + ': ' + d[1]; }).join(', ');
			r('Shortest distances from ' + s + ' are final. O((V + E) log V). Note: Dijkstra needs non-negative weights.', 13, fin);
			return r.steps;
		}
	};

	A['islands'] = {
		title: 'Grid flood fill: number of islands',
		inputs: [{ key: 'grid', label: 'Grid rows (1 = land)', type: 'string' }],
		defaults: { grid: '11000, 11010, 00100, 00011' },
		code: ['def num_islands(grid):', '    rows, cols = len(grid), len(grid[0])', '    def sink(r, c):', '        if r < 0 or c < 0 or r >= rows or c >= cols or grid[r][c] != "1":', '            return', '        grid[r][c] = "0"', '        for dr, dc in ((1,0), (-1,0), (0,1), (0,-1)):', '            sink(r + dr, c + dc)', '    count = 0', '    for r in range(rows):', '        for c in range(cols):', '            if grid[r][c] == "1":', '                count += 1', '                sink(r, c)', '    return count'],
		run: function (inp) {
			var g = String(inp.grid).split(',').map(function (row) { return row.trim().slice(0, 9).split(''); }).filter(function (x) { return x.length; }).slice(0, 7);
			var rr = recorder(), cls = {}, count = 0, colours = ['isl1', 'isl2', 'isl3', 'isl4', 'isl5'];
			function scene(extra) { var s = { grid: { rows: g.map(function (row) { return row.slice(); }), cls: clone(cls) }, vars: { islands: count } }; Object.keys(extra || {}).forEach(function (k) { s[k] = extra[k]; }); return s; }
			rr('Scan the grid. Each time we find unvisited land, it is a new island: then "sink" all land connected to it so we never count it twice.', 9, scene());
			for (var r = 0; r < g.length; r++) for (var c = 0; c < g[r].length; c++) {
				if (g[r][c] !== '1' || cls[r + ',' + c]) continue;
				count++;
				var col = colours[(count - 1) % colours.length], stack = [[r, c]];
				cls[r + ',' + c] = 'active';
				rr('New land at (' + r + ', ' + c + '): island #' + count + '!', 13, scene());
				while (stack.length) {
					var p = stack.pop(), pr = p[0], pc = p[1];
					cls[pr + ',' + pc] = col;
					rr('Sink (' + pr + ', ' + pc + ') and spread to its land neighbours.', 6, scene());
					[[1, 0], [-1, 0], [0, 1], [0, -1]].forEach(function (d) {
						var nr = pr + d[0], nc = pc + d[1];
						if (nr >= 0 && nc >= 0 && nr < g.length && nc < g[nr].length && g[nr][nc] === '1' && !cls[nr + ',' + nc]) { cls[nr + ',' + nc] = 'active'; stack.push([nr, nc]); }
					});
				}
			}
			rr('Found ' + count + ' island' + (count === 1 ? '' : 's') + '. Every cell is visited once: O(rows × cols).', 15, scene({ out: 'return ' + count }));
			return rr.steps;
		}
	};

	/* Dynamic programming ---------------------------------------------------- */
	A['climbing-stairs'] = {
		title: 'DP: climbing stairs',
		inputs: [{ key: 'n', label: 'Stairs n', type: 'number' }],
		defaults: { n: 7 },
		code: ['def climb(n):', '    dp = [0] * (n + 1)', '    dp[0] = dp[1] = 1', '    for i in range(2, n + 1):', '        dp[i] = dp[i - 1] + dp[i - 2]', '    return dp[n]'],
		run: function (inp) {
			var n = Math.max(1, Math.min(14, inp.n)), r = recorder(), dp = new Array(n + 1).fill('');
			dp[0] = 1; dp[1] = 1;
			r('You can climb 1 or 2 steps at a time. dp[i] = number of ways to reach step i. There is 1 way to stand at step 0 and 1 way to reach step 1.', 3, { arrays: [{ name: 'dp', values: dp, cls: clsMap([[0, 'found'], [1, 'found']]) }] });
			for (var i = 2; i <= n; i++) {
				dp[i] = dp[i - 1] + dp[i - 2];
				r('To land on step ' + i + ', your last move came from step ' + (i - 1) + ' or step ' + (i - 2) + ': dp[' + i + '] = ' + dp[i - 1] + ' + ' + dp[i - 2] + ' = ' + dp[i] + '.', 5, { arrays: [{ name: 'dp', values: dp, cls: clsMap([[i, 'add'], [i - 1, 'active'], [i - 2, 'active']]) }] });
			}
			r('Each answer reuses earlier answers instead of recomputing them: O(n). That is dynamic programming.', 6, { arrays: [{ name: 'dp', values: dp, cls: clsMap([[n, 'found']]) }], out: 'return ' + dp[n] });
			return r.steps;
		}
	};

	A['coin-change'] = {
		title: 'DP: coin change (fewest coins)',
		inputs: [{ key: 'coins', label: 'Coins', type: 'array' }, { key: 'amount', label: 'Amount', type: 'number' }],
		defaults: { coins: [1, 3, 4], amount: 6 },
		code: ['def coin_change(coins, amount):', '    INF = float("inf")', '    dp = [0] + [INF] * amount', '    for a in range(1, amount + 1):', '        for c in coins:', '            if c <= a:', '                dp[a] = min(dp[a], dp[a - c] + 1)', '    return dp[amount] if dp[amount] != INF else -1'],
		run: function (inp) {
			var coins = inp.coins.filter(function (c) { return c > 0; }), amt = Math.max(1, Math.min(14, inp.amount)), r = recorder();
			var dp = [0]; for (var k = 1; k <= amt; k++) dp.push('∞');
			r('dp[a] = fewest coins to make amount a. dp[0] = 0 (no coins needed).', 3, { arrays: [{ name: 'dp', values: dp, cls: clsMap([[0, 'found']]) }], chips: { name: 'coins', items: coins } });
			for (var a = 1; a <= amt; a++) {
				coins.forEach(function (c) {
					if (c <= a && dp[a - c] !== '∞') {
						var cand = dp[a - c] + 1;
						var better = dp[a] === '∞' || cand < dp[a];
						if (better) dp[a] = cand;
						r('Amount ' + a + ' with a ' + c + '-coin: dp[' + (a - c) + '] + 1 = ' + cand + (better ? ' → new best!' : ', not better than ' + dp[a] + '.'), 7, { arrays: [{ name: 'dp', values: dp, cls: clsMap([[a, better ? 'add' : 'active'], [a - c, 'active']]) }], chips: { name: 'coins', items: coins, hl: c } });
					}
				});
			}
			r('dp[' + amt + '] = ' + dp[amt] + '. O(amount × coins). Greedy (always the biggest coin) would give 4+1+1 = 3 coins here; DP finds 3+3 = 2.', 8, { arrays: [{ name: 'dp', values: dp, cls: clsMap([[amt, 'found']]) }], out: 'return ' + (dp[amt] === '∞' ? -1 : dp[amt]) });
			return r.steps;
		}
	};

	A['lcs'] = {
		title: 'DP table: longest common subsequence',
		inputs: [{ key: 'a', label: 'Text 1', type: 'string' }, { key: 'b', label: 'Text 2', type: 'string' }],
		defaults: { a: 'ABCBDA', b: 'BDCAB' },
		code: ['def lcs(a, b):', '    dp = [[0] * (len(b) + 1) for _ in range(len(a) + 1)]', '    for i in range(1, len(a) + 1):', '        for j in range(1, len(b) + 1):', '            if a[i - 1] == b[j - 1]:', '                dp[i][j] = dp[i - 1][j - 1] + 1', '            else:', '                dp[i][j] = max(dp[i - 1][j], dp[i][j - 1])', '    return dp[-1][-1]'],
		run: function (inp) {
			var a = String(inp.a).slice(0, 8), b = String(inp.b).slice(0, 8), r = recorder();
			var dp = []; for (var i = 0; i <= a.length; i++) { dp.push([]); for (var j = 0; j <= b.length; j++) dp[i].push(i && j ? '' : 0); }
			function scene(cls) { return { grid: { rows: dp, rowLabels: [''].concat(a.split('')), colLabels: [''].concat(b.split('')), cls: cls || {} } }; }
			r('dp[i][j] = LCS length of the first i letters of "' + a + '" and the first j letters of "' + b + '". Row and column 0 are empty strings: 0.', 2, scene());
			for (i = 1; i <= a.length; i++) for (j = 1; j <= b.length; j++) {
				var c = {};
				if (a[i - 1] === b[j - 1]) {
					dp[i][j] = dp[i - 1][j - 1] + 1; c[i + ',' + j] = 'add'; c[(i - 1) + ',' + (j - 1)] = 'active';
					r('"' + a[i - 1] + '" matches! Take the diagonal + 1 = ' + dp[i][j] + '.', 6, scene(c));
				} else {
					dp[i][j] = Math.max(dp[i - 1][j], dp[i][j - 1]); c[i + ',' + j] = 'add'; c[(i - 1) + ',' + j] = 'active'; c[i + ',' + (j - 1)] = 'active';
					r('"' + a[i - 1] + '" ≠ "' + b[j - 1] + '": best of above and left = ' + dp[i][j] + '.', 8, scene(c));
				}
			}
			var fc = {}; fc[a.length + ',' + b.length] = 'found';
			r('The bottom-right cell holds the answer: ' + dp[a.length][b.length] + '. O(n × m) time. Same table idea solves edit distance and many string problems.', 9, { grid: scene(fc).grid, out: 'return ' + dp[a.length][b.length] });
			return r.steps;
		}
	};

	A['merge-intervals'] = {
		title: 'Greedy: merge overlapping intervals',
		inputs: [{ key: 'iv', label: 'Intervals (1-3, 2-6, …)', type: 'string' }],
		defaults: { iv: '1-3, 8-10, 2-6, 15-18, 9-12' },
		code: ['def merge(intervals):', '    intervals.sort()', '    merged = [intervals[0]]', '    for start, end in intervals[1:]:', '        if start <= merged[-1][1]:', '            merged[-1][1] = max(merged[-1][1], end)', '        else:', '            merged.append([start, end])', '    return merged'],
		run: function (inp) {
			var iv = String(inp.iv).split(',').map(function (x) { var m = x.trim().match(/^(\d+)\s*-\s*(\d+)$/); return m ? [+m[1], +m[2]] : null; }).filter(Boolean).slice(0, 8);
			var r = recorder();
			if (!iv.length) return [{ note: 'Enter intervals like 1-3, 2-6.', line: 1, scene: {} }];
			r('Here are the intervals as bars on a number line.', 1, { intervals: { items: iv } });
			iv.sort(function (x, y) { return x[0] - y[0]; });
			r('Sort them by start. Now overlapping intervals sit next to each other.', 2, { intervals: { items: iv } });
			var merged = [iv[0].slice()];
			r('Start the result with the first interval.', 3, { intervals: { items: iv, cls: clsMap([[0, 'active']]), merged: merged } });
			for (var i = 1; i < iv.length; i++) {
				var last = merged[merged.length - 1], c = clsMap([[i, 'active']]);
				if (iv[i][0] <= last[1]) {
					last[1] = Math.max(last[1], iv[i][1]);
					r('[' + iv[i].join(', ') + '] starts before [' + last[0] + ', …] ends: they overlap. Stretch the last merged interval to ' + last[1] + '.', 6, { intervals: { items: iv, cls: c, merged: merged } });
				} else {
					merged.push(iv[i].slice());
					r('[' + iv[i].join(', ') + '] starts after the last one ends: no overlap. Start a new merged interval.', 8, { intervals: { items: iv, cls: c, merged: merged } });
				}
			}
			r('Merged: ' + merged.map(function (m) { return '[' + m.join(', ') + ']'; }).join(' ') + '. Sorting costs O(n log n); the sweep is O(n).', 9, { intervals: { items: iv, merged: merged }, out: JSON.stringify(merged) });
			return r.steps;
		}
	};

	/* ═══ Renderer ═════════════════════════════════════════════════════════ */

	function el(tag, cls, text) { var e = document.createElement(tag); if (cls) e.className = cls; if (text !== undefined) e.textContent = text; return e; }
	var SVGNS = 'http://www.w3.org/2000/svg';
	function sv(tag, attrs) { var e = document.createElementNS(SVGNS, tag); Object.keys(attrs || {}).forEach(function (k) { e.setAttribute(k, attrs[k]); }); return e; }

	function renderArray(host, a) {
		var wrap = el('div', 'vz-array');
		if (a.name) wrap.appendChild(el('div', 'vz-label', a.name));
		var row = el('div', 'vz-cells');
		var cw = 46;
		a.values.forEach(function (v, i) {
			var cell = el('div', 'vz-cell' + (a.cls && a.cls[i] ? ' is-' + a.cls[i] : '') + (a.range && (i < a.range[0] || i > a.range[1]) ? ' is-out' : '') + (a.range && i >= a.range[0] && i <= a.range[1] ? ' is-in' : ''));
			cell.appendChild(el('span', 'vz-idx', String(i)));
			cell.appendChild(el('span', 'vz-val', v === null || v === undefined ? '' : String(v)));
			row.appendChild(cell);
		});
		wrap.appendChild(row);
		var ptrs = Object.keys(a.ptr || {});
		if (ptrs.length) {
			var pr = el('div', 'vz-ptrs');
			pr.style.width = (a.values.length * cw) + 'px';
			var byIdx = {};
			ptrs.forEach(function (k) { var i = a.ptr[k]; (byIdx[i] = byIdx[i] || []).push(k); });
			Object.keys(byIdx).forEach(function (i) {
				var p = el('div', 'vz-ptr', '▲ ' + byIdx[i].join(' '));
				p.style.left = (i * cw + cw / 2) + 'px';
				p.setAttribute('data-key', byIdx[i].join('-'));
				pr.appendChild(p);
			});
			wrap.appendChild(pr);
		}
		host.appendChild(wrap);
	}

	function renderBox(host, data, kind) {
		var wrap = el('div', 'vz-box vz-' + kind);
		wrap.appendChild(el('div', 'vz-label', data.name || kind));
		var items = el('div', 'vz-items');
		if (!data.items.length) items.appendChild(el('span', 'vz-empty', 'empty'));
		data.items.forEach(function (it, i) {
			var hl = (kind === 'stack' || kind === 'queue') ? data.hl === i : (data.hl !== undefined && String(data.hl) === String(it));
			items.appendChild(el('span', 'vz-item' + (hl ? ' is-hl' : ''), String(it)));
		});
		wrap.appendChild(items);
		if (kind === 'stack') wrap.appendChild(el('div', 'vz-note-small', '▲ top'));
		if (kind === 'queue') wrap.appendChild(el('div', 'vz-note-small', 'front ◀  ▶ back'));
		host.appendChild(wrap);
	}

	function renderMap(host, m) {
		var wrap = el('div', 'vz-box vz-map');
		wrap.appendChild(el('div', 'vz-label', m.name || 'map'));
		var t = el('div', 'vz-map-grid');
		if (!m.entries.length) t.appendChild(el('span', 'vz-empty', 'empty'));
		m.entries.forEach(function (e) {
			var hl = m.hl !== undefined && String(m.hl) === String(e[0]);
			var row = el('div', 'vz-kv' + (hl ? ' is-hl' : ''));
			row.appendChild(el('b', '', String(e[0])));
			row.appendChild(el('span', '', '→ ' + e[1]));
			t.appendChild(row);
		});
		wrap.appendChild(t);
		host.appendChild(wrap);
	}

	function renderVars(host, vars) {
		var wrap = el('div', 'vz-vars');
		Object.keys(vars).forEach(function (k) {
			var p = el('span', 'vz-var');
			p.appendChild(el('span', '', k + ' = '));
			p.appendChild(el('b', '', String(vars[k])));
			wrap.appendChild(p);
		});
		host.appendChild(wrap);
	}

	function renderList(host, l) {
		var n = l.values.length, w = 70, h = 110, svg = sv('svg', { class: 'vz-svg vz-list', viewBox: '0 0 ' + (n * w + 40) + ' ' + h, width: n * w + 40, height: h });
		var defs = sv('defs'); var mk = sv('marker', { id: 'vzarr', viewBox: '0 0 10 10', refX: '9', refY: '5', markerWidth: '7', markerHeight: '7', orient: 'auto-start-reverse' });
		mk.appendChild(sv('path', { d: 'M0,0 L10,5 L0,10 z', class: 'vz-arrowhead' })); defs.appendChild(mk); svg.appendChild(defs);
		l.values.forEach(function (v, i) {
			var x = 20 + i * w, y = 40, nx = l.next[i];
			if (nx === null) {
				svg.appendChild(sv('text', { x: x + 23, y: y + 52, class: 'vz-null' })).textContent = '∅';
			} else if (nx === i + 1) {
				svg.appendChild(sv('line', { x1: x + 46, y1: y + 18, x2: x + w - 2, y2: y + 18, class: 'vz-edge', 'marker-end': 'url(#vzarr)' }));
			} else if (nx === i - 1) {
				svg.appendChild(sv('path', { d: 'M' + (x + 4) + ',' + (y + 28) + ' Q' + (x - 12) + ',' + (y + 48) + ' ' + (x - 24) + ',' + (y + 30), class: 'vz-edge vz-edge-back', 'marker-end': 'url(#vzarr)', fill: 'none' }));
			} else {
				var tx = 20 + nx * w + 23;
				svg.appendChild(sv('path', { d: 'M' + (x + 23) + ',' + (y + 36) + ' C' + (x + 23) + ',' + (y + 70) + ' ' + tx + ',' + (y + 70) + ' ' + tx + ',' + (y + 38), class: 'vz-edge', 'marker-end': 'url(#vzarr)', fill: 'none' }));
			}
			var g = sv('g', { class: 'vz-node' + (l.cls && l.cls[i] ? ' is-' + l.cls[i] : '') });
			g.appendChild(sv('rect', { x: x, y: y, width: 46, height: 36, rx: 8 }));
			var t = sv('text', { x: x + 23, y: y + 23 }); t.textContent = v; g.appendChild(t);
			svg.appendChild(g);
		});
		var by = {};
		Object.keys(l.ptr || {}).forEach(function (k) { var i = l.ptr[k]; if (i !== null && i !== undefined) (by[i] = by[i] || []).push(k); });
		Object.keys(by).forEach(function (i) {
			var t = sv('text', { x: 20 + i * w + 23, y: 22, class: 'vz-ptr-text' }); t.textContent = by[i].join(' ') + ' ▼'; svg.appendChild(t);
		});
		var wrap = el('div', 'vz-scroll'); wrap.appendChild(svg); host.appendChild(wrap);
	}

	function renderTree(host, root) {
		if (!root) return;
		var leaves = 0, depth = 0;
		(function measure(n, d) {
			depth = Math.max(depth, d);
			if (!n.children || !n.children.length) { n._x = leaves++; return; }
			n.children.forEach(function (c) { measure(c, d + 1); });
			n._x = (n.children[0]._x + n.children[n.children.length - 1]._x) / 2;
		})(root, 0);
		var labelW = 0;
		(function lw(n) { labelW = Math.max(labelW, String(n.label).length); (n.children || []).forEach(lw); })(root);
		var gap = Math.max(52, labelW * 8 + 18), W = Math.max(1, leaves) * gap + 20, H = (depth + 1) * 66 + 10;
		var svg = sv('svg', { class: 'vz-svg vz-tree', viewBox: '0 0 ' + W + ' ' + H, width: W, height: H });
		function pos(n, d) { return { x: 10 + n._x * gap + gap / 2, y: 26 + d * 66 }; }
		(function edges(n, d) {
			var p = pos(n, d);
			(n.children || []).forEach(function (c) {
				if (c.ghost) return;
				var q = pos(c, d + 1);
				svg.appendChild(sv('line', { x1: p.x, y1: p.y, x2: q.x, y2: q.y, class: 'vz-edge' }));
				edges(c, d + 1);
			});
		})(root, 0);
		(function nodes(n, d) {
			if (n.ghost) return;
			var p = pos(n, d), lab = String(n.label), rw = Math.max(34, lab.length * 8 + 14);
			var g = sv('g', { class: 'vz-node' + (n.cls ? ' is-' + n.cls : '') });
			g.appendChild(sv('rect', { x: p.x - rw / 2, y: p.y - 17, width: rw, height: 34, rx: 17 }));
			var t = sv('text', { x: p.x, y: p.y + 5 }); t.textContent = lab; g.appendChild(t);
			svg.appendChild(g);
			(n.children || []).forEach(function (c) { nodes(c, d + 1); });
		})(root, 0);
		var wrap = el('div', 'vz-scroll'); wrap.appendChild(svg); host.appendChild(wrap);
	}

	function renderGraph(host, g) {
		var svg = sv('svg', { class: 'vz-svg vz-graph', viewBox: '0 0 320 280', width: 320, height: 280 });
		var pos = {}; g.nodes.forEach(function (n) { pos[n.id] = n; });
		if (g.directed) {
			var defs = sv('defs'); var mk = sv('marker', { id: 'vzgarr', viewBox: '0 0 10 10', refX: '10', refY: '5', markerWidth: '7', markerHeight: '7', orient: 'auto' });
			mk.appendChild(sv('path', { d: 'M0,0 L10,5 L0,10 z', class: 'vz-arrowhead' })); defs.appendChild(mk); svg.appendChild(defs);
		}
		g.edges.forEach(function (e) {
			var a = pos[e[0]], b = pos[e[1]]; if (!a || !b) return;
			var dx = b.x - a.x, dy = b.y - a.y, len = Math.sqrt(dx * dx + dy * dy) || 1, R = 20;
			var attrs = { x1: a.x + dx / len * R, y1: a.y + dy / len * R, x2: b.x - dx / len * (R + 2), y2: b.y - dy / len * (R + 2), class: 'vz-edge' + (g.ecls && g.ecls[e[0] + '-' + e[1]] ? ' is-' + g.ecls[e[0] + '-' + e[1]] : '') };
			if (g.directed) attrs['marker-end'] = 'url(#vzgarr)';
			svg.appendChild(sv('line', attrs));
			if (e[2] !== undefined) { var t = sv('text', { x: (a.x + b.x) / 2 + 6, y: (a.y + b.y) / 2 - 4, class: 'vz-weight' }); t.textContent = e[2]; svg.appendChild(t); }
		});
		g.nodes.forEach(function (n) {
			var c = sv('g', { class: 'vz-node' + (g.cls && g.cls[n.id] ? ' is-' + g.cls[n.id] : '') });
			var lab = String(n.id), rw = Math.max(40, lab.length * 8 + 14);
			c.appendChild(lab.length > 2 ? sv('rect', { x: n.x - rw / 2, y: n.y - 18, width: rw, height: 36, rx: 18 }) : sv('circle', { cx: n.x, cy: n.y, r: 20 }));
			var t = sv('text', { x: n.x, y: n.y + 5 }); t.textContent = lab; c.appendChild(t);
			svg.appendChild(c);
		});
		var wrap = el('div', 'vz-scroll'); wrap.appendChild(svg); host.appendChild(wrap);
	}

	function renderGrid(host, gr) {
		var t = el('table', 'vz-grid');
		if (gr.colLabels) {
			var hr = el('tr'); hr.appendChild(el('th'));
			gr.colLabels.forEach(function (c) { hr.appendChild(el('th', '', c)); });
			t.appendChild(hr);
		}
		gr.rows.forEach(function (row, r) {
			var tr = el('tr');
			if (gr.rowLabels) tr.appendChild(el('th', '', gr.rowLabels[r]));
			row.forEach(function (v, c) {
				var k = gr.cls && gr.cls[r + ',' + c];
				tr.appendChild(el('td', (k ? 'is-' + k : '') + (v === '1' ? ' is-land' : v === '0' ? ' is-water' : ''), String(v)));
			});
			t.appendChild(tr);
		});
		var wrap = el('div', 'vz-scroll'); wrap.appendChild(t); host.appendChild(wrap);
	}

	function renderBars(host, bars) {
		var max = Math.max.apply(null, bars.map(function (b) { return b.value; }).concat([1]));
		var wrap = el('div', 'vz-bars');
		bars.forEach(function (b) {
			var row = el('div', 'vz-bar-row');
			row.appendChild(el('span', 'vz-bar-label', b.label));
			var track = el('span', 'vz-bar-track'); var fill = el('span', 'vz-bar-fill is-' + (b.cls || 'ok'));
			fill.style.width = Math.max(1.5, b.value / max * 100) + '%'; track.appendChild(fill);
			row.appendChild(track);
			row.appendChild(el('b', 'vz-bar-value', String(b.value)));
			wrap.appendChild(row);
		});
		host.appendChild(wrap);
	}

	function renderIntervals(host, iv) {
		var all = iv.items.concat(iv.merged || []), max = Math.max.apply(null, all.map(function (x) { return x[1]; })) + 1;
		var wrap = el('div', 'vz-intervals');
		function bar(x, cls, row) {
			var b = el('div', 'vz-iv ' + (cls ? 'is-' + cls : ''), x[0] + '–' + x[1]);
			b.style.left = (x[0] / max * 100) + '%'; b.style.width = Math.max(4, (x[1] - x[0]) / max * 100) + '%';
			row.appendChild(b);
		}
		iv.items.forEach(function (x, i) { var row = el('div', 'vz-iv-row'); bar(x, iv.cls && iv.cls[i], row); wrap.appendChild(row); });
		if (iv.merged) {
			wrap.appendChild(el('div', 'vz-label', 'merged'));
			var mr = el('div', 'vz-iv-row'); iv.merged.forEach(function (x) { bar(x, 'found', mr); }); wrap.appendChild(mr);
		}
		var axis = el('div', 'vz-iv-axis');
		for (var i = 0; i <= max; i += Math.max(1, Math.round(max / 10))) { var t = el('span', '', String(i)); t.style.left = (i / max * 100) + '%'; axis.appendChild(t); }
		wrap.appendChild(axis);
		host.appendChild(wrap);
	}

	function renderScene(stage, s) {
		stage.innerHTML = '';
		if (s.vars) renderVars(stage, s.vars);
		if (s.bars) renderBars(stage, s.bars);
		if (s.graph) renderGraph(stage, s.graph);
		if (s.tree) renderTree(stage, s.tree.root);
		if (s.list) renderList(stage, s.list);
		if (s.grid) renderGrid(stage, s.grid);
		if (s.intervals) renderIntervals(stage, s.intervals);
		(s.arrays || []).forEach(function (a) { renderArray(stage, a); });
		var side = el('div', 'vz-side');
		if (s.stack) renderBox(side, s.stack, 'stack');
		if (s.queue) renderBox(side, s.queue, 'queue');
		if (s.map) renderMap(side, s.map);
		if (s.chips) renderBox(side, s.chips, 'chips');
		if (side.children.length) stage.appendChild(side);
		if (s.out) { var o = el('div', 'vz-out'); o.appendChild(el('span', '', 'Output ')); o.appendChild(el('code', '', s.out)); stage.appendChild(o); }
	}

	/* ═══ Player ═══════════════════════════════════════════════════════════ */

	function parseInput(def, raw) {
		if (def.type === 'array') return String(raw).split(/[\s,]+/).filter(function (x) { return x !== ''; }).slice(0, 14).map(function (x) { return x === 'null' ? null : (isNaN(+x) ? x : +x); });
		if (def.type === 'number') return parseInt(raw, 10) || 0;
		return String(raw);
	}

	function mount(host) {
		var name = host.getAttribute('data-viz');
		var algo = A[name];
		if (!algo) { host.textContent = 'Unknown visualizer: ' + name; return; }
		var input = Object.assign({}, algo.defaults);
		try { Object.assign(input, JSON.parse(host.getAttribute('data-input') || '{}')); } catch (e) {}
		var title = host.getAttribute('data-title') || algo.title;
		var ref = name + (host.getAttribute('data-ref') ? '-' + host.getAttribute('data-ref') : '');

		host.innerHTML = '';
		host.classList.add('viz');
		var head = el('div', 'viz-head');
		head.appendChild(el('span', 'viz-badge', '● LIVE'));
		head.appendChild(el('strong', 'viz-title', title));
		host.appendChild(head);

		var body = el('div', 'viz-body');
		var stage = el('div', 'viz-stage');
		var codeBox = el('pre', 'viz-code');
		body.appendChild(stage); body.appendChild(codeBox);
		host.appendChild(body);

		var explain = el('div', 'viz-explain');
		var bot = el('div', 'viz-bot'); bot.innerHTML = window.VS ? window.VS.mascot('think') : '';
		var note = el('p', 'viz-note'); note.setAttribute('aria-live', 'polite');
		explain.appendChild(bot); explain.appendChild(note);
		host.appendChild(explain);

		var ctr = el('div', 'viz-controls');
		function btn(label, aria, cls) { var b = el('button', 'viz-btn ' + (cls || ''), label); b.type = 'button'; b.setAttribute('aria-label', aria); ctr.appendChild(b); return b; }
		var bReset = btn('⏮', 'Restart'), bBack = btn('◀', 'Previous step'), bPlay = btn('▶ Play', 'Play', 'viz-play'), bNext = btn('▶|', 'Next step');
		var speedWrap = el('label', 'viz-speed'); speedWrap.appendChild(el('span', '', 'Speed'));
		var speed = el('input'); speed.type = 'range'; speed.min = '1'; speed.max = '5'; speed.value = '3'; speed.setAttribute('aria-label', 'Speed');
		speedWrap.appendChild(speed); ctr.appendChild(speedWrap);
		var count = el('span', 'viz-count'); ctr.appendChild(count);
		var progress = el('div', 'viz-progress'); var pfill = el('span'); progress.appendChild(pfill);
		host.appendChild(ctr); host.appendChild(progress);

		if (algo.inputs.length) {
			var form = el('form', 'viz-input');
			form.appendChild(el('span', 'viz-input-title', '✏️ Try your own input'));
			algo.inputs.forEach(function (d) {
				var lab = el('label'); lab.appendChild(el('span', '', d.label));
				var f;
				if (d.type === 'choice') {
					f = el('select');
					d.options.forEach(function (o) { var op = el('option', '', o); op.value = o; if (String(input[d.key]) === o) op.selected = true; f.appendChild(op); });
				} else {
					f = el('input'); f.type = 'text';
					f.value = Array.isArray(input[d.key]) ? input[d.key].map(function (x) { return x === null ? 'null' : x; }).join(', ') : input[d.key];
				}
				f.name = d.key; lab.appendChild(f); form.appendChild(lab);
			});
			var go = el('button', 'btn btn-primary btn-sm', 'Visualize'); go.type = 'submit'; form.appendChild(go);
			form.addEventListener('submit', function (e) {
				e.preventDefault();
				algo.inputs.forEach(function (d) {
					var v = parseInput(d, form.elements[d.key].value);
					if (d.sorted && Array.isArray(v)) v = v.filter(function (x) { return typeof x === 'number'; }).sort(function (a, b) { return a - b; });
					input[d.key] = v;
				});
				build(); play();
			});
			host.appendChild(form);
		}

		var steps = [], i = 0, timer = null, awarded = false;
		function build() {
			stop();
			try { steps = algo.run(clone(input)); } catch (err) { steps = [{ note: 'That input could not be visualized. Check the format and try again.', line: 0, scene: {} }]; }
			if (!steps.length) steps = [{ note: 'Nothing to show.', line: 0, scene: {} }];
			codeBox.innerHTML = '';
			(algo.code || []).forEach(function (line, n) {
				var ln = el('span', 'viz-line'); ln.setAttribute('data-n', n + 1);
				ln.appendChild(el('span', 'viz-ln', String(n + 1)));
				ln.appendChild(document.createTextNode(line || ' '));
				codeBox.appendChild(ln);
			});
			i = 0; show();
		}
		function show() {
			var s = steps[i];
			renderScene(stage, s.scene);
			note.textContent = s.note;
			codeBox.querySelectorAll('.viz-line').forEach(function (l) { l.classList.toggle('is-current', +l.getAttribute('data-n') === s.line); });
			var cur = codeBox.querySelector('.is-current');
			if (cur && codeBox.scrollHeight > codeBox.clientHeight) codeBox.scrollTop = cur.offsetTop - codeBox.clientHeight / 2;
			count.textContent = 'Step ' + (i + 1) + ' / ' + steps.length;
			pfill.style.width = ((i + 1) / steps.length * 100) + '%';
			bBack.disabled = i === 0; bNext.disabled = i === steps.length - 1;
			var last = i === steps.length - 1;
			if (window.VS) bot.innerHTML = window.VS.mascot(last ? 'cheer' : (s.scene.out ? 'wow' : 'think'));
			if (last) {
				stop();
				if (!awarded && window.VS) {
					awarded = true;
					window.VS.addXP('viz', ref).then(function (res) { if (res && res.gained) window.VS.celebrate('Visualization complete!', 'You watched ' + title + ' from start to finish.', 'cheer', res.gained); });
				}
			}
		}
		function delay() { return [1600, 1150, 800, 500, 260][+speed.value - 1]; }
		function tick() { if (i < steps.length - 1) { i++; show(); timer = setTimeout(tick, delay()); } else stop(); }
		function play() { if (i >= steps.length - 1) i = 0, show(); bPlay.textContent = '⏸ Pause'; bPlay.setAttribute('aria-label', 'Pause'); clearTimeout(timer); timer = setTimeout(tick, delay()); }
		function stop() { clearTimeout(timer); timer = null; bPlay.textContent = '▶ Play'; bPlay.setAttribute('aria-label', 'Play'); }
		bPlay.addEventListener('click', function () { if (timer) stop(); else play(); });
		bNext.addEventListener('click', function () { stop(); if (i < steps.length - 1) { i++; show(); } });
		bBack.addEventListener('click', function () { stop(); if (i > 0) { i--; show(); } });
		bReset.addEventListener('click', function () { stop(); i = 0; show(); });
		host.tabIndex = 0;
		host.addEventListener('keydown', function (e) {
			if (e.target.closest && e.target.closest('form')) return;
			if (e.key === 'ArrowRight') { bNext.click(); e.preventDefault(); }
			if (e.key === 'ArrowLeft') { bBack.click(); e.preventDefault(); }
			if (e.key === ' ') { bPlay.click(); e.preventDefault(); }
		});
		build();
	}

	window.VSViz = { algorithms: A, mount: mount };
	// Build each visualizer only when it is about to scroll into view.
	function init() {
		var hosts = document.querySelectorAll('.vs-viz:not(.viz)');
		if (!('IntersectionObserver' in window)) { hosts.forEach(mount); return; }
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (e.isIntersecting) { io.unobserve(e.target); mount(e.target); }
			});
		}, { rootMargin: '600px 0px' });
		hosts.forEach(function (h) { io.observe(h); });
	}
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
