function canJump(nums) {
  let farthest = 0;
  for (let i = 0; i < nums.length; i++) {
    if (i > farthest) return false;            // this tile is in the dark
    farthest = Math.max(farthest, i + nums[i]);
  }
  return true;
}

console.log(canJump([2, 3, 1, 1, 4]));  // true
console.log(canJump([3, 2, 1, 0, 4]));  // false
console.log(canJump([0]));              // true
