function groupAnagrams(words) {
  const groups = new Map();                         // keeps first-seen order
  for (const w of words) {
    const key = [...w].sort().join("");             // "eat" -> "aet"
    if (!groups.has(key)) groups.set(key, []);
    groups.get(key).push(w);
  }
  return [...groups.values()];
}

console.log(JSON.stringify(groupAnagrams(["eat", "tea", "tan", "ate", "nat", "bat"])));
// [["eat","tea","ate"],["tan","nat"],["bat"]]
console.log(JSON.stringify(groupAnagrams([""])));  // [[""]]
