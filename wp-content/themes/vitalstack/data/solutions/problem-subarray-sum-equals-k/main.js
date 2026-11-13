function subarraySum(nums, k) {
  const seen = new Map([[0, 1]]);          // prefix sum -> how many times seen
  let total = 0, count = 0;
  for (const x of nums) {
    total += x;
    count += seen.get(total - k) || 0;
    seen.set(total, (seen.get(total) || 0) + 1);
  }
  return count;
}

console.log(subarraySum([1, 1, 1], 2));          // 2
console.log(subarraySum([1, 2, 3], 3));          // 2
console.log(subarraySum([3, -1, 2, -2, 4], 2));  // 4
