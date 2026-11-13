function largestRectangle(heights) {
  const stack = [];                              // indexes, heights increasing
  let best = 0;
  for (let i = 0; i <= heights.length; i++) {
    const h = i === heights.length ? 0 : heights[i];   // 0 at the end flushes the stack
    while (stack.length && heights[stack.at(-1)] > h) {
      const height = heights[stack.pop()];
      const left = stack.length ? stack.at(-1) : -1;   // left blocker
      const width = i - left - 1;                      // i is the right blocker
      best = Math.max(best, height * width);
    }
    stack.push(i);
  }
  return best;
}

console.log(largestRectangle([2, 1, 5, 6, 2, 3]));  // 10
console.log(largestRectangle([2, 4]));              // 4
console.log(largestRectangle([1, 1, 1, 1]));        // 4
