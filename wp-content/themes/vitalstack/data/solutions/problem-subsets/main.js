function subsets(nums) {
  const out = [], current = [];
  const choose = (i) => {
    if (i === nums.length) {
      out.push([...current]);      // copy! current keeps changing
      return;
    }
    current.push(nums[i]);         // take nums[i]
    choose(i + 1);
    current.pop();                 // undo (backtrack)
    choose(i + 1);                 // skip nums[i]
  };
  choose(0);
  return out;
}

console.log(JSON.stringify(subsets([1, 2, 3])));  // [[1,2,3],[1,2],[1,3],[1],[2,3],[2],[3],[]]
console.log(subsets([1, 2, 3, 4]).length);        // 16
