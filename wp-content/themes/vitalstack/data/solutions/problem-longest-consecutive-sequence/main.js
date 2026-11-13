function longestConsecutive(nums) {
  const present = new Set(nums);
  let best = 0;
  for (const x of present) {
    if (!present.has(x - 1)) {               // x starts a run
      let length = 1;
      while (present.has(x + length)) length++;
      best = Math.max(best, length);
    }
  }
  return best;
}

console.log(longestConsecutive([100, 4, 200, 1, 3, 2]));          // 4
console.log(longestConsecutive([0, 3, 7, 2, 5, 8, 4, 6, 0, 1]));  // 9
console.log(longestConsecutive([]));                              // 0
