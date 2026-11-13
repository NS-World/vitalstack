function threeSum(nums) {
  nums.sort((a, b) => a - b);               // numeric sort (default sort compares text)
  const out = [];
  for (let i = 0; i < nums.length - 2; i++) {
    if (i > 0 && nums[i] === nums[i - 1]) continue;   // same first number: skip
    if (nums[i] > 0) break;                           // all remaining are positive
    let lo = i + 1, hi = nums.length - 1;
    while (lo < hi) {
      const total = nums[i] + nums[lo] + nums[hi];
      if (total < 0) lo++;
      else if (total > 0) hi--;
      else {
        out.push([nums[i], nums[lo], nums[hi]]);
        lo++;
        hi--;
        while (lo < hi && nums[lo] === nums[lo - 1]) lo++;   // skip duplicate seconds
      }
    }
  }
  return out;
}

console.log(JSON.stringify(threeSum([-1, 0, 1, 2, -1, -4])));  // [[-1,-1,2],[-1,0,1]]
console.log(JSON.stringify(threeSum([0, 1, 1])));              // []
console.log(JSON.stringify(threeSum([0, 0, 0, 0])));           // [[0,0,0]]
