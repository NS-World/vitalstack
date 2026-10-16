---
title: Validate Binary Search Tree
slug: problem-validate-binary-search-tree
subject: dsa-problems
lesson: 31
difficulty: intermediate
level: Medium
pattern: BST
companies: Amazon, Meta, Microsoft, Bloomberg, Google, Apple
excerpt: Is a tree a valid BST? Checking only parent and child is a trap; pass an allowed range down the tree instead.
---

A problem with a famous trap. Checking that each node is bigger than its left child and smaller than its right child is **not enough**.

## The problem

Return `True` if the tree is a valid binary search tree: for **every** node, all values in its left subtree are smaller and all values in its right subtree are bigger.

## Examples

```text
    2              5
   / \            / \
  1   3          1   6
                    / \
 → True            3   7     → False
```

The second tree fails: 3 sits in the **right** subtree of 5, so it must be bigger than 5.

## Hints

::hint Hint 1 | Each node must lie in a RANGE determined by all its ancestors, not just its parent.
::hint Hint 2 | Going left, the upper limit becomes the parent's value. Going right, the lower limit becomes the parent's value.
::hint Hint 3 | Alternative: an inorder traversal of a BST is strictly increasing.

## The trap

In the second tree, every parent-child pair looks fine: 1 < 5 < 6, and 3 < 6 < 7. Yet 3 is in the right subtree of 5, so it must be bigger than 5. Comparing only with the parent misses this; you need to check each node against **all** its ancestors.

## The easy idea: a narrowing window

The root may be anything: range (−∞, +∞). Step left from a node with value v: everything there must be below v. Step right: everything must be above v. Each step narrows the window. Watch a BST search narrow the range the same way:

::viz bst-search {"tree": [8, 3, 10, 1, 6, null, 14, null, null, 4, 7], "target": 7}

## Solution: pass the range down

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def is_valid_bst(root, low=float("-inf"), high=float("inf")):
    if root is None:
        return True
    if not (low < root.val < high):
        return False
    return (is_valid_bst(root.left, low, root.val) and
            is_valid_bst(root.right, root.val, high))

print(is_valid_bst(TreeNode(2, TreeNode(1), TreeNode(3))))       # True
bad = TreeNode(5, TreeNode(1), TreeNode(6, TreeNode(3), TreeNode(7)))
print(is_valid_bst(bad))                                         # False
print(is_valid_bst(TreeNode(2, TreeNode(2))))                    # False
```

## Solution: inorder must increase

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def is_valid_inorder(root):
    prev = float("-inf")
    stack, node = [], root
    while stack or node:
        while node:
            stack.append(node)
            node = node.left
        node = stack.pop()
        if node.val <= prev:
            return False
        prev = node.val
        node = node.right
    return True

print(is_valid_inorder(TreeNode(8, TreeNode(3, TreeNode(1), TreeNode(6)), TreeNode(10))))  # True
```

## Complexity

- **Time O(n)**, **space O(h)**.

## Similar problems

Kth Smallest Element in a BST, Lowest Common Ancestor of a BST, Recover Binary Search Tree, Convert Sorted Array to BST.

Learn the pattern: [Binary Search Trees](/tutorials/dsa-bst/).
