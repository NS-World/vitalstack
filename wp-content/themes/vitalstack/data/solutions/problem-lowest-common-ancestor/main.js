class TreeNode {
  constructor(val, left = null, right = null) {
    this.val = val;
    this.left = left;
    this.right = right;
  }
}

function lca(root, p, q) {
  if (!root || root === p || root === q) return root;
  const left = lca(root.left, p, q);
  const right = lca(root.right, p, q);
  if (left && right) return root;   // p and q are on different sides
  return left || right;             // pass up whatever was found
}

const n7 = new TreeNode(7), n4 = new TreeNode(4);
const n2 = new TreeNode(2, n7, n4);
const n5 = new TreeNode(5, new TreeNode(6), n2);
const n1 = new TreeNode(1, new TreeNode(0), new TreeNode(8));
const root = new TreeNode(3, n5, n1);
console.log(lca(root, n5, n1).val);  // 3
console.log(lca(root, n5, n4).val);  // 5
console.log(lca(root, n7, n4).val);  // 2
