class TreeNode {
  constructor(val, left = null, right = null) {
    this.val = val;
    this.left = left;
    this.right = right;
  }
}

function isValidBst(root, low = -Infinity, high = Infinity) {
  if (!root) return true;
  if (!(low < root.val && root.val < high)) return false;
  return isValidBst(root.left, low, root.val) && isValidBst(root.right, root.val, high);
}

console.log(isValidBst(new TreeNode(2, new TreeNode(1), new TreeNode(3))));  // true
const bad = new TreeNode(5, new TreeNode(1), new TreeNode(6, new TreeNode(3), new TreeNode(7)));
console.log(isValidBst(bad));                                                // false
console.log(isValidBst(new TreeNode(2, new TreeNode(2))));                   // false
