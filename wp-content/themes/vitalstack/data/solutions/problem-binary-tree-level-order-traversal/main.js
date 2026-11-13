class TreeNode {
  constructor(val, left = null, right = null) {
    this.val = val;
    this.left = left;
    this.right = right;
  }
}

function levelOrder(root) {
  if (!root) return [];
  const out = [];
  let queue = [root];
  while (queue.length) {
    out.push(queue.map((node) => node.val));                     // this level's values
    queue = queue.flatMap((node) => [node.left, node.right]).filter(Boolean);
  }
  return out;
}

const root = new TreeNode(3, new TreeNode(9), new TreeNode(20, new TreeNode(15), new TreeNode(7)));
console.log(JSON.stringify(levelOrder(root)));  // [[3],[9,20],[15,7]]
console.log(JSON.stringify(levelOrder(null)));  // []
