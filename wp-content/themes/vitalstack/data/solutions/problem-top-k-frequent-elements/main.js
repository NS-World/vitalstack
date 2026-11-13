function topKBuckets(nums, k) {
  const counts = new Map();
  for (const x of nums) counts.set(x, (counts.get(x) || 0) + 1);
  const shelves = Array.from({ length: nums.length + 1 }, () => []);   // shelves[c] = values seen c times
  for (const [value, c] of counts) shelves[c].push(value);
  const out = [];
  for (let c = shelves.length - 1; c > 0; c--) {
    for (const value of shelves[c]) {
      out.push(value);
      if (out.length === k) return out;
    }
  }
  return out;
}

console.log(topKBuckets([1, 1, 1, 2, 2, 3], 2));        // [ 1, 2 ]
console.log(topKBuckets([5, 5, 6, 6, 6, 7, 5, 5], 1));  // [ 5 ]
