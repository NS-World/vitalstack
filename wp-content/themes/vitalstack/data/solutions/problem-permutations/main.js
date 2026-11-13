function permute(nums) {
  const out = [], current = [], used = new Array(nums.length).fill(false);
  const fill = () => {
    if (current.length === nums.length) {
      out.push([...current]);
      return;
    }
    nums.forEach((x, i) => {
      if (!used[i]) {
        used[i] = true;
        current.push(x);         // choose
        fill();                  // explore
        current.pop();           // un-choose
        used[i] = false;
      }
    });
  };
  fill();
  return out;
}

console.log(JSON.stringify(permute([1, 2, 3])));  // [[1,2,3],[1,3,2],[2,1,3],[2,3,1],[3,1,2],[3,2,1]]
console.log(permute([1, 2, 3, 4]).length);        // 24
console.log(JSON.stringify(permute([0, 1])));     // [[0,1],[1,0]]
