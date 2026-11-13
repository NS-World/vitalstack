function combinationSum(candidates, target) {
  candidates.sort((a, b) => a - b);
  const out = [], current = [];
  const build = (start, remaining) => {
    if (remaining === 0) {
      out.push([...current]);
      return;
    }
    for (let i = start; i < candidates.length; i++) {
      const c = candidates[i];
      if (c > remaining) break;               // sorted: nothing later fits
      current.push(c);
      build(i, remaining - c);                // i, not i + 1: reuse allowed
      current.pop();
    }
  };
  build(0, target);
  return out;
}

console.log(JSON.stringify(combinationSum([2, 3, 6, 7], 7)));  // [[2,2,3],[7]]
console.log(JSON.stringify(combinationSum([2, 3, 5], 8)));     // [[2,2,2,2],[2,3,3],[3,5]]
console.log(JSON.stringify(combinationSum([2], 1)));           // []
