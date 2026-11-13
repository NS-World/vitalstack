function maxArea(height) {
  let left = 0, right = height.length - 1, best = 0;
  while (left < right) {
    const water = (right - left) * Math.min(height[left], height[right]);
    best = Math.max(best, water);
    if (height[left] < height[right]) left++;   // move the shorter wall
    else right--;
  }
  return best;
}

console.log(maxArea([1, 8, 6, 2, 5, 4, 8, 3, 7]));  // 49
console.log(maxArea([1, 1]));                       // 1
console.log(maxArea([4, 3, 2, 1, 4]));              // 16
