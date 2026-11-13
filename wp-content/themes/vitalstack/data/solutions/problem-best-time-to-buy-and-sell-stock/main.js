function maxProfit(prices) {
  let cheapest = Infinity, best = 0;
  for (const p of prices) {
    cheapest = Math.min(cheapest, p);
    best = Math.max(best, p - cheapest);
  }
  return best;
}

console.log(maxProfit([7, 1, 5, 3, 6, 4]));  // 5
console.log(maxProfit([7, 6, 4, 3, 1]));     // 0
