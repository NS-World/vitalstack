function canFinish(n, prerequisites) {
  const unlocks = Array.from({ length: n }, () => []);
  const needs = new Array(n).fill(0);
  for (const [course, req] of prerequisites) {
    unlocks[req].push(course);
    needs[course]++;
  }
  const queue = [];
  for (let c = 0; c < n; c++) if (needs[c] === 0) queue.push(c);
  let taken = 0;
  for (let head = 0; head < queue.length; head++) {   // index instead of shift(): O(1)
    taken++;
    for (const nxt of unlocks[queue[head]]) {
      if (--needs[nxt] === 0) queue.push(nxt);
    }
  }
  return taken === n;
}

console.log(canFinish(2, [[1, 0]]));                          // true
console.log(canFinish(2, [[1, 0], [0, 1]]));                  // false
console.log(canFinish(4, [[1, 0], [2, 0], [3, 1], [3, 2]]));  // true
