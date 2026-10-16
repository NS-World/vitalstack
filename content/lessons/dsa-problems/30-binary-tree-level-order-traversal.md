---
title: Binary Tree Level Order Traversal
slug: problem-binary-tree-level-order-traversal
subject: dsa-problems
lesson: 30
difficulty: intermediate
level: Medium
pattern: Trees
companies: Amazon, Meta, Microsoft, LinkedIn, Bloomberg, Apple
excerpt: Return the tree's values level by level. BFS with a queue, processing one whole level per loop.
---

Breadth-first search on a tree, with one twist: group the values **by level**.

## The problem

Return the node values level by level, left to right, as a list of lists.

```text
    3
   / \
  9  20
     / \
    15   7
```

Answer: `[[3], [9, 20], [15, 7]]`

## Hints

::hint Hint 1 | A queue visits nodes in order of their distance from the root.
::hint Hint 2 | At the start of each loop, the queue holds exactly one full level. Its length tells you how many nodes to take.

## The easy idea: floors of a building

Visit the building floor by floor. On each floor, greet everyone from left to right, and write down who lives on the floor below them. When the floor is done, move to the list you just wrote. Watch the queue:

::viz tree-traversal {"tree": [3, 9, 20, null, null, 15, 7], "order": "levelorder"}

## Solution

```python
from collections import deque

class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def level_order(root):
    if not root:
        return []
    out, q = [], deque([root])
    while q:
        level = []
        for _ in range(len(q)):          # exactly this level's nodes
            node = q.popleft()
            level.append(node.val)
            if node.left:
                q.append(node.left)
            if node.right:
                q.append(node.right)
        out.append(level)
    return out

root = TreeNode(3, TreeNode(9), TreeNode(20, TreeNode(15), TreeNode(7)))
print(level_order(root))  # [[3], [9, 20], [15, 7]]
print(level_order(None))  # []
```

## Complexity

- **Time O(n)**, **space O(w)** where w is the widest level (up to n/2 for a full tree).

## Easy variations (change one line)

```python
from collections import deque

class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def levels(root):
    out, q = [], deque([root] if root else [])
    while q:
        out.append([n.val for n in q])
        for _ in range(len(q)):
            n = q.popleft()
            q.extend(c for c in (n.left, n.right) if c)
    return out

root = TreeNode(1, TreeNode(2, TreeNode(4)), TreeNode(3, None, TreeNode(5)))
lv = levels(root)
print(lv)                                                  # [[1], [2, 3], [4, 5]]
print([lvl[-1] for lvl in lv])                             # [1, 3, 5]
print([l if i % 2 == 0 else l[::-1] for i, l in enumerate(lv)])  # [[1], [3, 2], [4, 5]]
print([sum(l) / len(l) for l in lv])                       # [1.0, 2.5, 4.5]
```

- **Right side view:** the last value of each level.
- **Zigzag order:** reverse every other level.
- **Average of levels:** sum ÷ count per level.

## Similar problems

Binary Tree Right Side View, Zigzag Level Order Traversal, Average of Levels, Minimum Depth, Populating Next Right Pointers.

Learn the pattern: [Binary Trees](/tutorials/dsa-trees/).
