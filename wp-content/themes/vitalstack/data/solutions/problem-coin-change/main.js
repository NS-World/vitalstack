function coinChange(coins, amount) {
  const best = [0, ...new Array(amount).fill(Infinity)];
  for (let a = 1; a <= amount; a++) {
    for (const c of coins) {
      if (c <= a) best[a] = Math.min(best[a], best[a - c] + 1);
    }
  }
  return best[amount] === Infinity ? -1 : best[amount];
}

console.log(coinChange([1, 2, 5], 11));  // 3
console.log(coinChange([1, 3, 4], 6));   // 2
console.log(coinChange([2], 3));         // -1
console.log(coinChange([1], 0));         // 0
