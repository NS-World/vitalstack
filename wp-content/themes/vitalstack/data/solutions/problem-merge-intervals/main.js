function merge(intervals) {
  intervals.sort((x, y) => x[0] - y[0]);
  const merged = [];
  for (const [start, end] of intervals) {
    const last = merged.at(-1);
    if (last && start <= last[1]) last[1] = Math.max(last[1], end);
    else merged.push([start, end]);
  }
  return merged;
}

console.log(JSON.stringify(merge([[1, 3], [2, 6], [8, 10], [15, 18]])));  // [[1,6],[8,10],[15,18]]
console.log(JSON.stringify(merge([[1, 4], [4, 5]])));                     // [[1,5]]
console.log(JSON.stringify(merge([[1, 10], [2, 3]])));                    // [[1,10]]
console.log(JSON.stringify(merge([[5, 6], [1, 2]])));                     // [[1,2],[5,6]]
