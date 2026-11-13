function ladderLength(begin, end, wordList) {
  const words = new Set(wordList);
  if (!words.has(end)) return 0;
  const queue = [[begin, 1]];
  const seen = new Set([begin]);
  for (let head = 0; head < queue.length; head++) {
    const [word, steps] = queue[head];
    if (word === end) return steps;
    for (let i = 0; i < word.length; i++) {
      for (let code = 97; code <= 122; code++) {          // 'a'..'z'
        const nxt = word.slice(0, i) + String.fromCharCode(code) + word.slice(i + 1);
        if (words.has(nxt) && !seen.has(nxt)) {
          seen.add(nxt);
          queue.push([nxt, steps + 1]);
        }
      }
    }
  }
  return 0;
}

console.log(ladderLength("hit", "cog", ["hot", "dot", "dog", "lot", "log", "cog"]));  // 5
console.log(ladderLength("hit", "cog", ["hot", "dot", "dog", "lot", "log"]));         // 0
