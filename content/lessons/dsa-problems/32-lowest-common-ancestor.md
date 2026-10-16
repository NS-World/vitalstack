---
title: Lowest Common Ancestor of a Binary Tree
slug: problem-lowest-common-ancestor
subject: dsa-problems
lesson: 32
difficulty: intermediate
level: Medium
pattern: Trees
companies: Meta, Amazon, Microsoft, LinkedIn, Google, Apple
excerpt: Find the deepest node that has both p and q below it. One elegant recursion that reports what it found upwards.
---

The "family tree" question: what's the closest shared ancestor of two people?

## The problem

Given a binary tree and two nodes `p` and `q` in it, return their **lowest common ancestor**: the deepest node that has both `p` and `q` as descendants. A node counts as a descendant of itself.

```text
        3
      /   \
     5     1
    / \   / \
   6   2 0   8
      / \
     7   4
```

| p | q | LCA |
|---|---|---|
| 5 | 1 | 3 |
| 5 | 4 | 5 (5 is an ancestor of 4) |
| 7 | 4 | 2 |

## Hints

::hint Hint 1 | Ask each subtree: "Did you find p or q?"
::hint Hint 2 | If the left subtree found one and the right subtree found the other, the current node is the answer.
::hint Hint 3 | If only one side found something, pass that result upwards.

## The easy idea: reporting up the chain

Every node asks both children: "Did you see p or q down there?" Each child reports back the node it found (or nothing).

- If a node **is** p or q, it reports itself.
- If **both** children report something, p and q are on different sides: **I'm** the lowest common ancestor.
- If only one child reports, pass that report up.

## Solution

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def lca(root, p, q):
    if root is None or root is p or root is q:
        return root
    left = lca(root.left, p, q)
    right = lca(root.right, p, q)
    if left and right:
        return root              # p and q are on different sides
    return left or right         # pass up whatever was found

n7, n4 = TreeNode(7), TreeNode(4)
n2 = TreeNode(2, n7, n4)
n5 = TreeNode(5, TreeNode(6), n2)
n1 = TreeNode(1, TreeNode(0), TreeNode(8))
root = TreeNode(3, n5, n1)
print(lca(root, n5, n1).val)  # 3
print(lca(root, n5, n4).val)  # 5
print(lca(root, n7, n4).val)  # 2
```

## Complexity

- **Time O(n):** each node is visited once.
- **Space O(h)** for the recursion.

## The BST shortcut

In a **binary search tree**, values guide you: if both p and q are smaller than the node, go left; if both are bigger, go right; otherwise the current node is where they split.

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def lca_bst(root, p, q):
    while root:
        if p < root.val and q < root.val:
            root = root.left
        elif p > root.val and q > root.val:
            root = root.right
        else:
            return root

bst = TreeNode(6, TreeNode(2, TreeNode(0), TreeNode(4)), TreeNode(8, TreeNode(7), TreeNode(9)))
print(lca_bst(bst, 2, 8).val)  # 6
print(lca_bst(bst, 2, 4).val)  # 2
```

O(h) time, O(1) space.

## Similar problems

Lowest Common Ancestor of a BST, LCA with Parent Pointers, Distance Between Two Nodes, All Nodes Distance K.

Learn the pattern: [Binary Trees](/tutorials/dsa-trees/).
