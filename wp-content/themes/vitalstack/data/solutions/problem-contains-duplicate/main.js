function containsDuplicate(nums) {
  const seen = new Set();
  for (const x of nums) {
    if (seen.has(x)) return true;
    seen.add(x);
  }
  return false;
}

console.log(containsDuplicate([1, 2, 3, 1]));  // true
console.log(containsDuplicate([1, 2, 3, 4]));  // false
console.log(containsDuplicate([]));            // false
