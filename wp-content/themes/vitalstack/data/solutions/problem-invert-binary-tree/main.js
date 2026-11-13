class TreeNode {
  constructor(val, left = null, right = null) {
    this.val = val;
    this.left = left;
    this.right = right;
  }
}

function invert(root) {
  if (root) [root.left, root.right] = [invert(root.right), invert(root.left)];
  return root;
}

function levelValues(root) {
  const out = [];
  let level = root ? [root] : [];
  while (level.length) {
    out.push(...level.map((n) => n.val));
    level = level.flatMap((n) => [n.left, n.right]).filter(Boolean);
  }
  return out;
}

const root = new TreeNode(4, new TreeNode(2, new TreeNode(1), new TreeNode(3)),
                             new TreeNode(7, new TreeNode(6), new TreeNode(9)));
console.log(levelValues(invert(root)));  // [4, 7, 2, 9, 6, 3, 1]
console.log(invert(null));               // null
