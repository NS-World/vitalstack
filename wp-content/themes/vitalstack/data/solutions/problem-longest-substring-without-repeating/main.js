function lengthOfLongestSubstring(s) {
  const last = new Map();                   // letter -> last index seen
  let left = 0, best = 0;
  for (let right = 0; right < s.length; right++) {
    const ch = s[right];
    if (last.has(ch) && last.get(ch) >= left) left = last.get(ch) + 1;   // jump past the old copy
    last.set(ch, right);
    best = Math.max(best, right - left + 1);
  }
  return best;
}

console.log(lengthOfLongestSubstring("abcabcbb"));  // 3
console.log(lengthOfLongestSubstring("bbbbb"));     // 1
console.log(lengthOfLongestSubstring("pwwkew"));    // 3
console.log(lengthOfLongestSubstring(""));          // 0
console.log(lengthOfLongestSubstring("abba"));      // 2
