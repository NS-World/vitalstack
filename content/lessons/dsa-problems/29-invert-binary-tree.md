---
title: Invert Binary Tree
slug: problem-invert-binary-tree
subject: dsa-problems
lesson: 29
difficulty: beginner
level: Easy
pattern: Trees
companies: Google, Amazon, Meta, Microsoft, Apple
excerpt: Mirror a binary tree by swapping every node's left and right children. Short, famous and a perfect recursion warm-up.
---

Made famous by a tweet from a well-known developer who was asked it in an interview. It's short, but it checks that you really understand recursion.

## The problem

Mirror a binary tree: every node's left and right children swap places. Return the root.

```text
     4                4
   /   \            /   \
  2     7    →     7     2
 / \   / \        / \   / \
1   3 6   9      9   6 3   1
```

## Hints

::hint Hint 1 | At each node, swap its two children.
::hint Hint 2 | Then do the same for both subtrees. The order (swap first or recurse first) doesn't matter.

## The easy idea: a mirror

Hold the tree up to a mirror. Every left becomes right and every right becomes left, at every level. So: swap my two children, then ask each child to mirror itself.

## Solution: recursion

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def invert(root):
    if root:
        root.left, root.right = invert(root.right), invert(root.left)
    return root

def level_values(root):
    out, level = [], [root] if root else []
    while level:
        out += [n.val for n in level]
        level = [c for n in level for c in (n.left, n.right) if c]
    return out

root = TreeNode(4, TreeNode(2, TreeNode(1), TreeNode(3)), TreeNode(7, TreeNode(6), TreeNode(9)))
print(level_values(invert(root)))  # [4, 7, 2, 9, 6, 3, 1]
print(invert(None))                # None
```

## Solution: iterative with a queue

```python
from collections import deque

class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def invert_iterative(root):
    q = deque([root] if root else [])
    while q:
        node = q.popleft()
        node.left, node.right = node.right, node.left
        for child in (node.left, node.right):
            if child:
                q.append(child)
    return root

r = invert_iterative(TreeNode(1, TreeNode(2), TreeNode(3)))
print(r.left.val, r.right.val)  # 3 2
```

## Complexity

- **Time O(n):** each node is swapped once.
- **Space O(h)** for recursion, or O(w) for the queue (w = widest level).

## Similar problems

Symmetric Tree (is a tree its own mirror?), Same Tree, Flip Equivalent Binary Trees, Merge Two Binary Trees.

Learn the pattern: [Binary Trees](/tutorials/dsa-trees/).
