---
title: DSA and Big-O Cheat Sheet
slug: dsa-big-o-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: 🧮
order: 100
excerpt: Big-O complexities of data structures and algorithms, the interview pattern finder (which pattern for which problem) and Python templates for binary search, BFS, DFS, sliding window and DP. Printable.
---
## Big-O from fastest to slowest

| Big-O | Name | n = 1,000,000 | Example |
|---|---|---|---|
| O(1) | constant | 1 | dict lookup, array index |
| O(log n) | logarithmic | ~20 | binary search |
| O(n) | linear | 1M | one loop |
| O(n log n) | linearithmic | ~20M | good sorting |
| O(n²) | quadratic | 10¹² ❌ | nested loops |
| O(2ⁿ) | exponential | ∞ ❌ | all subsets |
| O(n!) | factorial | ∞ ❌ | all permutations |

Rule of thumb: about 10⁸ simple operations per second. n ≤ 10⁵ needs O(n log n) or better.

## Data structure costs

| Structure | Access | Search | Insert | Delete |
|---|---|---|---|---|
| Array / list | O(1) | O(n) | O(n) (end: O(1)) | O(n) |
| Hash map / set | – | O(1) avg | O(1) avg | O(1) avg |
| Linked list | O(n) | O(n) | O(1) at node | O(1) at node |
| Stack / queue (deque) | – | O(n) | O(1) | O(1) |
| Binary heap | min O(1) | O(n) | O(log n) | pop O(log n) |
| Balanced BST | O(log n) | O(log n) | O(log n) | O(log n) |

## Sorting

| Algorithm | Best | Average | Worst | Space | Stable |
|---|---|---|---|---|---|
| Bubble / insertion | O(n) | O(n²) | O(n²) | O(1) | yes |
| Selection | O(n²) | O(n²) | O(n²) | O(1) | no |
| Merge sort | O(n log n) | O(n log n) | O(n log n) | O(n) | yes |
| Quick sort | O(n log n) | O(n log n) | O(n²) | O(log n) | no |
| Heap sort | O(n log n) | O(n log n) | O(n log n) | O(1) | no |
| Timsort (Python, Java objects) | O(n) | O(n log n) | O(n log n) | O(n) | yes |

## Which pattern?

| Problem says… | Try |
|---|---|
| sorted array, find a target/boundary | binary search |
| pair/triple summing to a target | hash map, or sort + two pointers |
| longest/shortest contiguous subarray or substring | sliding window |
| range sums, subarray sum = k | prefix sum (+ hash map) |
| max subarray sum | Kadane |
| "seen before?", counting, grouping | hash set / map |
| matching brackets, undo, nearest previous | stack |
| next greater / smaller element | monotonic stack |
| top k, k-th largest, merge k lists | heap |
| linked list middle or cycle | fast & slow pointers |
| tree depth, paths, subtree questions | DFS recursion |
| level by level, shortest path (unweighted) | BFS |
| islands, flood fill, mazes | grid DFS/BFS |
| dependencies, ordering | topological sort |
| weighted shortest path | Dijkstra |
| all combinations / permutations | backtracking |
| "number of ways", "min cost", overlapping subproblems | dynamic programming |
| intervals, meetings | sort + sweep / greedy |

## Templates (Python)

```python
# Binary search: first index where condition is true
lo, hi = 0, len(a)
while lo < hi:
    mid = (lo + hi) // 2
    if a[mid] >= target: hi = mid
    else: lo = mid + 1
```

```python
# Sliding window: longest valid window
left = best = 0
for right, x in enumerate(s):
    add(x)
    while not valid():
        remove(s[left]); left += 1
    best = max(best, right - left + 1)
```

```python
# BFS
from collections import deque
q, seen = deque([start]), {start}
while q:
    node = q.popleft()
    for nxt in graph[node]:
        if nxt not in seen:
            seen.add(nxt); q.append(nxt)
```

```python
# DFS on a tree
def depth(node):
    if not node: return 0
    return 1 + max(depth(node.left), depth(node.right))
```

```python
# Backtracking
def backtrack(path, choices):
    if done(path): result.append(path[:]); return
    for c in choices:
        path.append(c); backtrack(path, next_choices(c)); path.pop()
```

```python
# DP (bottom-up)
dp = [0] * (n + 1); dp[0] = 1
for i in range(1, n + 1):
    dp[i] = dp[i - 1] + (dp[i - 2] if i >= 2 else 0)
```

```python
# Heap: k largest
import heapq
heap = []
for x in nums:
    heapq.heappush(heap, x)
    if len(heap) > k: heapq.heappop(heap)
```

## Interview checklist

1. Clarify inputs, outputs, constraints, edge cases.
2. Walk through an example by hand.
3. Say the brute force and its Big-O.
4. Find the pattern; explain the better approach.
5. Code it cleanly, talking as you go.
6. Test with examples and edge cases (empty, one item, duplicates, negatives); state time and space.
