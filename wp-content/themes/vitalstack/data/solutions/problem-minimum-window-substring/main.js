function minWindow(s, t) {
  const need = new Map();
  for (const ch of t) need.set(ch, (need.get(ch) || 0) + 1);
  let missing = t.length;                        // characters still needed
  let left = 0, bestStart = 0, bestLen = Infinity;
  for (let right = 0; right < s.length; right++) {
    const ch = s[right];
    if ((need.get(ch) || 0) > 0) missing--;      // this character was useful
    need.set(ch, (need.get(ch) || 0) - 1);
    while (missing === 0) {                      // window is valid: try to shrink
      if (right - left + 1 < bestLen) {
        bestStart = left;
        bestLen = right - left + 1;
      }
      const out = s[left];
      need.set(out, need.get(out) + 1);
      if (need.get(out) > 0) missing++;          // we just dropped something required
      left++;
    }
  }
  return bestLen === Infinity ? "" : s.slice(bestStart, bestStart + bestLen);
}

console.log(minWindow("ADOBECODEBANC", "ABC"));  // BANC
console.log(minWindow("a", "a"));                // a
console.log(minWindow("a", "aa") === "");        // true
