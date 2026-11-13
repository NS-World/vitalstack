function rob(nums) {
  let twoBack = 0, oneBack = 0;
  for (const money of nums) {
    [twoBack, oneBack] = [oneBack, Math.max(oneBack, twoBack + money)];   // skip or rob
  }
  return oneBack;
}

console.log(rob([1, 2, 3, 1]));     // 4
console.log(rob([2, 7, 9, 3, 1]));  // 12
console.log(rob([2, 1, 1, 2]));     // 4
console.log(rob([]));               // 0
