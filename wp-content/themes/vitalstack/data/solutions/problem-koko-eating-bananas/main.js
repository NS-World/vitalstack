function minEatingSpeed(piles, h) {
  const hours = (k) => piles.reduce((sum, p) => sum + Math.ceil(p / k), 0);
  let lo = 1, hi = Math.max(...piles);
  while (lo < hi) {
    const mid = Math.floor((lo + hi) / 2);
    if (hours(mid) <= h) hi = mid;   // fast enough: try slower
    else lo = mid + 1;               // too slow
  }
  return lo;
}

console.log(minEatingSpeed([3, 6, 7, 11], 8));        // 4
console.log(minEatingSpeed([30, 11, 23, 4, 20], 5));  // 30
console.log(minEatingSpeed([30, 11, 23, 4, 20], 6));  // 23
