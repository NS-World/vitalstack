function climbStairs(n) {
  let a = 1, b = 1;              // ways to reach step i-2 and i-1
  for (let i = 0; i < n - 1; i++) [a, b] = [b, a + b];
  return b;
}

console.log(climbStairs(2));   // 2
console.log(climbStairs(3));   // 3
console.log(climbStairs(5));   // 8
console.log(climbStairs(45));  // 1836311903
