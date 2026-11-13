function maxSubarray(nums) {
  let current = nums[0], best = nums[0];
  for (let i = 1; i < nums.length; i++) {
    current = Math.max(nums[i], current + nums[i]);   // start fresh or extend
    best = Math.max(best, current);
  }
  return best;
}

console.log(maxSubarray([-2, 1, -3, 4, -1, 2, 1, -5, 4]));  // 6
console.log(maxSubarray([1]));                              // 1
console.log(maxSubarray([-3, -1, -2]));                     // -1
