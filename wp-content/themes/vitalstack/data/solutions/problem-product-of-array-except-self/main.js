function productExceptSelf(nums) {
  const n = nums.length;
  const out = new Array(n).fill(1);
  let left = 1;
  for (let i = 0; i < n; i++) {          // out[i] = product of everything left of i
    out[i] = left;
    left *= nums[i];
  }
  let right = 1;
  for (let i = n - 1; i >= 0; i--) {     // multiply by everything right of i
    out[i] *= right;
    right *= nums[i];
  }
  return out.map((v) => v + 0);          // turn -0 into 0
}

console.log(productExceptSelf([1, 2, 3, 4]));       // [ 24, 12, 8, 6 ]
console.log(productExceptSelf([-1, 1, 0, -3, 3]));  // [ 0, 0, 9, 0, 0 ]
