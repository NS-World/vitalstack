function bisectLeft(arr, x) {
  let lo = 0, hi = arr.length;
  while (lo < hi) {
    const mid = (lo + hi) >> 1;
    if (arr[mid] < x) lo = mid + 1;
    else hi = mid;
  }
  return lo;
}

function lisFast(nums) {
  const tails = [];
  for (const x of nums) {
    const i = bisectLeft(tails, x);
    if (i === tails.length) tails.push(x);   // new, longer pile
    else tails[i] = x;                       // smaller top for that length
  }
  return tails.length;
}

console.log(lisFast([10, 9, 2, 5, 3, 7, 101, 18]));  // 4
console.log(lisFast([0, 1, 0, 3, 2, 3]));            // 4
