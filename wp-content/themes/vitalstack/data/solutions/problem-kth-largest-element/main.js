function quickselect(nums, k) {
  const pivot = nums[Math.floor(Math.random() * nums.length)];
  const bigger = nums.filter((x) => x > pivot);
  const equal = nums.filter((x) => x === pivot).length;
  const smaller = nums.filter((x) => x < pivot);
  if (k <= bigger.length) return quickselect(bigger, k);
  if (k <= bigger.length + equal) return pivot;
  return quickselect(smaller, k - bigger.length - equal);
}

console.log(quickselect([3, 2, 1, 5, 6, 4], 2));           // 5
console.log(quickselect([3, 2, 3, 1, 2, 4, 5, 5, 6], 4));  // 4
