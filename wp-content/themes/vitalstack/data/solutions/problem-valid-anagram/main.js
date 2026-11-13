function isAnagram(s, t) {
  if (s.length !== t.length) return false;
  const count = new Map();
  for (const ch of s) count.set(ch, (count.get(ch) || 0) + 1);
  for (const ch of t) {
    if (!count.get(ch)) return false;
    count.set(ch, count.get(ch) - 1);
  }
  return true;
}

console.log(isAnagram("anagram", "nagaram"));  // true
console.log(isAnagram("rat", "car"));          // false
console.log(isAnagram("ab", "a"));             // false
