---
title: Maximum Depth of Binary Tree
slug: problem-maximum-depth-of-binary-tree
subject: dsa-problems
lesson: 28
difficulty: beginner
level: Easy
pattern: Trees
companies: Amazon, LinkedIn, Microsoft, Google, Apple, Adobe
excerpt: How many levels does a tree have? A three-line recursive answer, plus the level-by-level BFS version.
---

The "hello world" of tree recursion: trust that the recursive call solves the smaller problem.

## The problem

Return the maximum depth of a binary tree: the number of nodes on the longest path from the root down to a leaf.

## Example

```text
    3
   / \
  9  20
     / \
    15   7
```

Depth = **3** (3 → 20 → 15).

## Hints

::hint Hint 1 | The depth of a tree = 1 + the deeper of its two subtrees.
::hint Hint 2 | An empty tree has depth 0. That's the base case.

## The easy idea: ask your children

Every node asks its two children: "How deep are you?" Each child asks its own children, and so on. Leaves answer 1. Each node replies "1 + the bigger answer I got". The root's reply is the answer. Watch a recursive traversal visit the nodes:

::viz tree-traversal {"tree": [3, 9, 20, null, null, 15, 7], "order": "postorder"}

## Solution: recursion (DFS)

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def max_depth(root):
    if root is None:
        return 0
    return 1 + max(max_depth(root.left), max_depth(root.right))

root = TreeNode(3, TreeNode(9), TreeNode(20, TreeNode(15), TreeNode(7)))
print(max_depth(root))          # 3
print(max_depth(None))          # 0
print(max_depth(TreeNode(1)))   # 1
```

## Solution: level by level (BFS)

Count how many levels a breadth-first search processes:

```python
from collections import deque

class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def max_depth_bfs(root):
    if not root:
        return 0
    q, depth = deque([root]), 0
    while q:
        depth += 1
        for _ in range(len(q)):
            node = q.popleft()
            if node.left:
                q.append(node.left)
            if node.right:
                q.append(node.right)
    return depth

root = TreeNode(3, TreeNode(9), TreeNode(20, TreeNode(15), TreeNode(7)))
print(max_depth_bfs(root))  # 3
```

## Complexity

- **Time O(n):** every node is visited once.
- **Space O(h)** for recursion, where h is the height (O(n) for a skewed tree, O(log n) for a balanced one).

## The template you just learned

```text
def solve(node):
    if node is None: return BASE
    left = solve(node.left)
    right = solve(node.right)
    return COMBINE(node, left, right)
```

Minimum depth, tree size, path sums, balanced check and diameter all use it.

## Similar problems

Minimum Depth of Binary Tree, Balanced Binary Tree, Diameter of Binary Tree, Same Tree, Path Sum.

Learn the pattern: [Binary Trees](/tutorials/dsa-trees/).
