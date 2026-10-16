---
title: Binary Search Trees
slug: dsa-bst
subject: dsa
lesson: 20
difficulty: intermediate
excerpt: Trees kept in sorted order. Search and insert in O(log n) (animated), validate a BST with bounds, find the k-th smallest with inorder traversal, and the lowest common ancestor shortcut.
---

A **binary search tree (BST)** is a binary tree with one rule: for every node, **everything in its left subtree is smaller and everything in its right subtree is bigger**. That rule turns the tree into a sorted structure where searching, inserting and deleting take **O(log n)** when the tree is balanced, like binary search but on a tree.

## The "higher or lower" signpost analogy

Imagine a road with signposts: at each junction, a sign says "numbers below 50 → left, above 50 → right". To find house 37, you never visit the right side of the first junction at all. Each junction halves what's left.

```text
            8
          /   \
         3     10
        / \      \
       1   6      14
          / \
         4   7
```

Every number left of 8 is smaller than 8; every number in 3's right subtree (6, 4, 7) is between 3 and 8.

## Search

::viz bst-search {"tree": [8, 3, 10, 1, 6, null, 14, null, null, 4, 7], "target": 7}

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def insert(node, val):
    if not node:
        return TreeNode(val)
    if val < node.val:
        node.left = insert(node.left, val)
    elif val > node.val:
        node.right = insert(node.right, val)
    return node                         # duplicates are ignored

def search(node, target):
    while node:
        if target == node.val:
            return True
        node = node.left if target < node.val else node.right
    return False

root = None
for v in [8, 3, 10, 1, 6, 14, 4, 7]:
    root = insert(root, v)
print(search(root, 7))   # True
print(search(root, 5))   # False
```

Each step goes down one level, so search and insert are **O(h)** where h is the height.

## The balance problem

Insert `1, 2, 3, 4, 5` in order and every node goes right: the "tree" becomes a line, and h = n. Search degrades to **O(n)**. **Self-balancing** trees (AVL trees, red-black trees) rotate nodes to keep h ≈ log n. You rarely code them in interviews, but you should know they exist, and that they're what make sorted maps (like Java's `TreeMap` or C++'s `std::map`) O(log n).

| Operation | Balanced BST | Unbalanced worst case |
|---|---|---|
| Search / insert / delete | O(log n) | O(n) |
| In-order traversal (sorted output) | O(n) | O(n) |
| Min / max | O(log n) (leftmost / rightmost) | O(n) |

## Inorder traversal = sorted order

Because left < node < right, an **inorder** traversal visits values in sorted order. This one fact solves many BST problems.

### K-th smallest element

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def insert(node, val):
    if not node:
        return TreeNode(val)
    if val < node.val: node.left = insert(node.left, val)
    else: node.right = insert(node.right, val)
    return node

def kth_smallest(root, k):
    stack, node = [], root
    while stack or node:
        while node:                  # go as far left as possible
            stack.append(node)
            node = node.left
        node = stack.pop()
        k -= 1
        if k == 0:
            return node.val
        node = node.right

root = None
for v in [5, 3, 6, 2, 4, 1]:
    root = insert(root, v)
print(kth_smallest(root, 3))  # 3
```

This iterative inorder stops as soon as it finds the k-th value: O(h + k).

## Validate a BST

A common trap: checking only that `left.val < node.val < right.val` is **not enough**. In the tree below, 6 is right of 3 (fine) but it's also in 5's left subtree, where everything must be smaller than 5.

```text
      5
     / \
    3   8
     \
      6     ← breaks the rule
```

Pass down the allowed **range** for each node:

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def is_valid_bst(node, low=float("-inf"), high=float("inf")):
    if not node:
        return True
    if not (low < node.val < high):
        return False
    return (is_valid_bst(node.left, low, node.val) and
            is_valid_bst(node.right, node.val, high))

good = TreeNode(5, TreeNode(3, None, TreeNode(4)), TreeNode(8))
bad = TreeNode(5, TreeNode(3, None, TreeNode(6)), TreeNode(8))
print(is_valid_bst(good))  # True
print(is_valid_bst(bad))   # False
```

(Alternative: do an inorder traversal and check the values are strictly increasing.)

## Lowest common ancestor in a BST

The ordering gives a shortcut: if both values are smaller than the node, the answer is on the left; if both are bigger, on the right; otherwise this node **splits** them and is the answer.

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def insert(node, val):
    if not node:
        return TreeNode(val)
    if val < node.val: node.left = insert(node.left, val)
    else: node.right = insert(node.right, val)
    return node

def lca_bst(node, p, q):
    while node:
        if p < node.val and q < node.val:
            node = node.left
        elif p > node.val and q > node.val:
            node = node.right
        else:
            return node.val

root = None
for v in [6, 2, 8, 0, 4, 7, 9, 3, 5]:
    root = insert(root, v)
print(lca_bst(root, 2, 8))  # 6
print(lca_bst(root, 3, 5))  # 4
```

O(h) and no recursion needed.

## Build a balanced BST from a sorted array

Pick the **middle** as the root, then build the left and right halves the same way:

```python
class TreeNode:
    def __init__(self, val, left=None, right=None):
        self.val, self.left, self.right = val, left, right

def sorted_to_bst(nums):
    if not nums:
        return None
    mid = len(nums) // 2
    return TreeNode(nums[mid], sorted_to_bst(nums[:mid]), sorted_to_bst(nums[mid + 1:]))

def height(n):
    return 0 if not n else 1 + max(height(n.left), height(n.right))

root = sorted_to_bst(list(range(1, 16)))
print(root.val, height(root))  # 8 4
```

15 values, height 4: perfectly balanced.

## Deleting a node (the idea)

- **Leaf:** just remove it.
- **One child:** replace the node with its child.
- **Two children:** replace its value with the **inorder successor** (the smallest value in its right subtree), then delete that successor.

## Interview corner

Validate BST, Kth Smallest in a BST, LCA of a BST, Convert Sorted Array to BST, Insert/Delete in a BST and BST Iterator are commonly reported at **Amazon, Microsoft, Google, Meta and Bloomberg**. The validate-BST trap (only checking direct children) is a favourite way to separate candidates.

::tip In any BST problem, ask: "Can inorder traversal (sorted order) or the left-smaller / right-bigger rule let me skip half the tree?"

## Common mistakes

- **Validating only parent–child pairs** instead of passing bounds.
- **Assuming O(log n)** without mentioning that the tree must be balanced.
- **Forgetting duplicates**: decide whether they go left, right, or are rejected.
- **Recursing into both sides** when the BST rule says only one side can contain the answer.

## Exercise

1. In the visualizer, search for 4, then for 13. How many nodes are skipped?
2. Write `min_value(root)` and `max_value(root)`.
3. Write `range_sum(root, low, high)`: the sum of values between low and high, skipping subtrees that can't contain them.
4. Write `delete(root, key)` using the three cases above.

## Summary

- BST rule: left subtree smaller, right subtree bigger, for every node.
- Search/insert/delete are O(h): O(log n) when balanced, O(n) when skewed.
- Inorder traversal gives sorted order.
- Validate with min/max bounds; use the ordering to skip half the tree.

Next, you'll learn **heaps**, the structure that always knows the smallest (or largest) item.
