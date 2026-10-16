---
title: Binary Trees and Traversals
slug: dsa-trees
subject: dsa
lesson: 19
difficulty: intermediate
excerpt: Nodes with left and right children. Build trees in Python, watch inorder, preorder, postorder and level-order traversals, and solve depth, invert, diameter and path-sum problems with recursion.
---

A **tree** stores data in a hierarchy: a family tree, folders on your computer, the HTML of a web page, a company's org chart. A **binary tree** is a tree where each node has at most **two children**: left and right. Tree problems are among the most common in interviews, and nearly all of them use the same recursive thinking.

## Vocabulary in one picture

```text
          1        ← root
        /   \
       2     3     ← children of 1
      / \     \
     4   5     6   ← leaves (no children)
```

- **Root:** the top node. **Leaf:** a node with no children.
- **Parent/child:** 2 is the parent of 4 and 5.
- **Depth of a node:** steps from the root. **Height of the tree:** the longest root-to-leaf path (here 3 levels).
- **Subtree:** any node plus everything below it, which is itself a tree. That's why recursion fits trees perfectly.

## A tree node in Python

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

root = TreeNode(1,
                TreeNode(2, TreeNode(4), TreeNode(5)),
                TreeNode(3, None, TreeNode(6)))
print(root.left.right.val)  # 5
```

Trees are often written in **level order** with `None` for missing children: `[1, 2, 3, 4, 5, None, 6]`. Each example below includes a small `build` helper that turns that list into nodes.

## The four traversals

A traversal visits every node once: O(n). The order is what differs.

- **Preorder** (node, left, right): copy a tree, print a folder structure.
- **Inorder** (left, node, right): in a binary search tree, this gives **sorted order**.
- **Postorder** (left, right, node): delete a tree, compute sizes of folders (children before parent).
- **Level order** (breadth-first): level by level, using a queue.

Switch the order in the visualizer and watch the output and the call stack:

::viz tree-traversal {"tree": [1, 2, 3, 4, 5, null, 6], "order": "inorder"}

```python
from collections import deque

class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def build(values):
    if not values or values[0] is None:
        return None
    nodes = [None if v is None else TreeNode(v) for v in values]
    for i, node in enumerate(nodes):
        if node:
            if 2 * i + 1 < len(nodes): node.left = nodes[2 * i + 1]
            if 2 * i + 2 < len(nodes): node.right = nodes[2 * i + 2]
    return nodes[0]

def preorder(node):
    return [node.val] + preorder(node.left) + preorder(node.right) if node else []

def inorder(node):
    return inorder(node.left) + [node.val] + inorder(node.right) if node else []

def postorder(node):
    return postorder(node.left) + postorder(node.right) + [node.val] if node else []

def level_order(root):
    if not root:
        return []
    out, q = [], deque([root])
    while q:
        level = []
        for _ in range(len(q)):            # one whole level at a time
            node = q.popleft()
            level.append(node.val)
            if node.left: q.append(node.left)
            if node.right: q.append(node.right)
        out.append(level)
    return out

root = build([1, 2, 3, 4, 5, None, 6])
print(preorder(root))     # [1, 2, 4, 5, 3, 6]
print(inorder(root))      # [4, 2, 5, 1, 3, 6]
print(postorder(root))    # [4, 5, 2, 6, 3, 1]
print(level_order(root))  # [[1], [2, 3], [4, 5, 6]]
```

The `for _ in range(len(q))` trick processes exactly one level per round, which is how you solve "per level" questions (level averages, right side view, zigzag order).

## The recursive recipe for tree problems

1. **What should my function return for one node?** (height, sum, True/False…)
2. **Base case:** what does an empty tree (`None`) return?
3. **Combine:** compute the answer from `f(node.left)` and `f(node.right)`.

### Maximum depth

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def max_depth(node):
    if not node:
        return 0
    return 1 + max(max_depth(node.left), max_depth(node.right))

root = TreeNode(3, TreeNode(9), TreeNode(20, TreeNode(15), TreeNode(7)))
print(max_depth(root))  # 3
```

### Invert (mirror) a tree

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def invert(node):
    if node:
        node.left, node.right = invert(node.right), invert(node.left)
    return node

root = invert(TreeNode(4, TreeNode(2), TreeNode(7)))
print(root.left.val, root.right.val)  # 7 2
```

### Same tree

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def same(a, b):
    if not a or not b:
        return a is b                      # both None → True
    return a.val == b.val and same(a.left, b.left) and same(a.right, b.right)

print(same(TreeNode(1, TreeNode(2)), TreeNode(1, TreeNode(2))))  # True
print(same(TreeNode(1, TreeNode(2)), TreeNode(1, None, TreeNode(2))))  # False
```

### Path sum: is there a root-to-leaf path with this total?

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def has_path_sum(node, target):
    if not node:
        return False
    if not node.left and not node.right:
        return node.val == target
    rest = target - node.val
    return has_path_sum(node.left, rest) or has_path_sum(node.right, rest)

root = TreeNode(5, TreeNode(4, TreeNode(11, TreeNode(7), TreeNode(2))), TreeNode(8))
print(has_path_sum(root, 22))  # True
print(has_path_sum(root, 99))  # False
```

## A "return one thing, track another" problem: diameter

The diameter is the longest path between any two nodes (it might not pass through the root). The function returns a **height**, while updating the best **diameter** on the side:

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def diameter(root):
    best = 0
    def height(node):
        nonlocal best
        if not node:
            return 0
        l, r = height(node.left), height(node.right)
        best = max(best, l + r)            # path through this node
        return 1 + max(l, r)
    height(root)
    return best

root = TreeNode(1, TreeNode(2, TreeNode(4), TreeNode(5)), TreeNode(3))
print(diameter(root))  # 3
```

This shape (return X, but track Y with `nonlocal`) also solves Balanced Binary Tree and Binary Tree Maximum Path Sum.

## Lowest common ancestor

The deepest node that has both p and q below it (a node counts as its own descendant):

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def lca(node, p, q):
    if not node or node.val in (p, q):
        return node
    left, right = lca(node.left, p, q), lca(node.right, p, q)
    if left and right:
        return node                       # p and q are on different sides
    return left or right

root = TreeNode(3, TreeNode(5, TreeNode(6), TreeNode(2)), TreeNode(1, TreeNode(0), TreeNode(8)))
print(lca(root, 6, 2).val)  # 5
print(lca(root, 6, 8).val)  # 3
```

## Complexity

Visiting every node: **O(n) time**. Recursion depth equals the tree's height h, so **O(h) space**: O(log n) for a balanced tree, but O(n) for a "stick" tree that's really a linked list.

## Interview corner

Maximum Depth, Invert Binary Tree, Same Tree, Level Order Traversal, Diameter, Lowest Common Ancestor, Path Sum, Right Side View and Serialize/Deserialize are commonly reported at **Amazon, Google, Microsoft, Meta, Apple and LinkedIn**. Always state the recursive contract ("returns the height of this subtree") before coding.

::tip For any tree problem, first ask: "If I knew the answer for the left and right subtrees, how would I get the answer for this node?"

## Common mistakes

- **Forgetting the `None` base case.**
- **Treating a node with one child as a leaf** in root-to-leaf problems.
- **Using a list as a queue** (`pop(0)`) in level order.
- **Mixing up height and depth**, or counting edges vs nodes inconsistently.

## Exercise

1. In the visualizer, try all four orders on `[1, 2, 3, 4, 5, 6, 7]`.
2. Write `count_nodes(root)` and `sum_leaves(root)`.
3. Write `right_side_view(root)`: the last value of each level.
4. Write `is_balanced(root)`: every node's subtrees differ in height by at most 1.

## Summary

- A binary tree node has up to two children; every subtree is a tree, so recursion fits.
- Traversals: preorder, inorder, postorder (DFS) and level order (BFS with a queue).
- Recipe: define the return value, handle `None`, combine left and right answers.
- O(n) time, O(h) space for recursion depth.

Next, you'll learn **binary search trees**, trees kept in sorted order for fast searching.
