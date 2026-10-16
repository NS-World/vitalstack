---
title: Heaps and Priority Queues
slug: dsa-heaps
subject: dsa
lesson: 21
difficulty: intermediate
excerpt: Always know the smallest item in O(1) and add or remove in O(log n). Python's heapq, max-heaps with negatives, top-k problems, merging k sorted lists and the running median.
---

A **heap** is a tree-shaped structure that always keeps the **smallest** item (a min-heap) or the **largest** item (a max-heap) at the top. You can peek at it in O(1), and add or remove items in O(log n). That makes heaps perfect for "top k", "k-th largest", scheduling and "always process the most urgent thing next".

## The hospital emergency room analogy

Patients don't wait first-come-first-served; the **most urgent** patient is treated next. When a new patient arrives, they're slotted in by urgency. A heap is exactly that waiting room: a **priority queue**.

## How a heap is organised

A min-heap is a **complete binary tree** (filled level by level, left to right) where **every parent is smaller than or equal to its children**. It's stored in a plain array: for the node at index i, the children are at `2i + 1` and `2i + 2`, and the parent is at `(i − 1) // 2`.

- **Push:** add at the end, then **sift up** (swap with the parent while smaller).
- **Pop:** take the top, move the last item to the top, then **sift down** (swap with the smaller child while bigger).

Both walk one path of the tree: **O(log n)**.

::viz heap {"arr": [5, 3, 8, 1, 9, 2]}

## Python's `heapq`

`heapq` turns a normal list into a **min-heap**. `nums[0]` is always the smallest item, an O(1) peek:

```python
import heapq

nums = [5, 3, 8, 1, 9, 2]
heapq.heapify(nums)          # O(n): rearrange into a heap
print(nums[0])               # 1
heapq.heappush(nums, 0)
print(heapq.heappop(nums))   # 0
print(heapq.heappop(nums))   # 1
print(heapq.nsmallest(2, [7, 2, 9, 4]))  # [2, 4]
```

### Max-heap: store negatives

`heapq` has no max-heap, so push `-x` and negate when popping:

```python
import heapq

max_heap = []
for x in [5, 3, 8, 1]:
    heapq.heappush(max_heap, -x)
print(-heapq.heappop(max_heap))  # 8
print(-max_heap[0])              # 5
```

### Tuples set the priority

Tuples compare by their first item, then the next:

```python
import heapq

tasks = []
heapq.heappush(tasks, (2, "write tests"))
heapq.heappush(tasks, (1, "fix bug"))
heapq.heappush(tasks, (3, "update docs"))
print(heapq.heappop(tasks))  # (1, 'fix bug')
```

## Top k pattern: keep a heap of size k

### K-th largest element

Keep a **min-heap of the k largest seen so far**. Its top is the k-th largest. When it grows beyond k, pop the smallest.

```python
import heapq

def kth_largest(nums, k):
    heap = []
    for x in nums:
        heapq.heappush(heap, x)
        if len(heap) > k:
            heapq.heappop(heap)
    return heap[0]

print(kth_largest([3, 2, 1, 5, 6, 4], 2))           # 5
print(kth_largest([3, 2, 3, 1, 2, 4, 5, 5, 6], 4))  # 4
```

**O(n log k) time, O(k) space**, much better than sorting when k is small, and it works on a **stream** of numbers that never ends.

### K closest points to the origin

Want the k **smallest** distances? Keep a **max-heap** of size k (store negative distances), so the farthest of the kept points is on top, ready to be thrown out.

```python
import heapq

def k_closest(points, k):
    heap = []
    for x, y in points:
        d = x * x + y * y
        heapq.heappush(heap, (-d, x, y))
        if len(heap) > k:
            heapq.heappop(heap)
    return sorted([x, y] for _, x, y in heap)

print(k_closest([[1, 3], [-2, 2], [5, 8], [0, 1]], 2))  # [[-2, 2], [0, 1]]
```

Rule of thumb: **k largest → min-heap; k smallest → max-heap.** The heap's top is the item you'll throw out next.

### Top k frequent words

```python
import heapq
from collections import Counter

def top_k_frequent(words, k):
    counts = Counter(words)
    return heapq.nsmallest(k, counts, key=lambda w: (-counts[w], w))

print(top_k_frequent(["i", "love", "dsa", "i", "love", "coding"], 2))  # ['i', 'love']
```

## Merge k sorted lists

Put the first item of every list in a heap. Pop the smallest, then push the next item from the same list.

```python
import heapq

def merge_k_sorted(lists):
    heap = [(lst[0], i, 0) for i, lst in enumerate(lists) if lst]
    heapq.heapify(heap)
    out = []
    while heap:
        val, i, j = heapq.heappop(heap)
        out.append(val)
        if j + 1 < len(lists[i]):
            heapq.heappush(heap, (lists[i][j + 1], i, j + 1))
    return out

print(merge_k_sorted([[1, 4, 7], [2, 5, 8], [0, 3, 6, 9]]))  # [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
```

O(N log k) for N total items across k lists. The index `i` in the tuple also breaks ties so Python never has to compare anything else.

## Two heaps: the running median

Keep the smaller half in a **max-heap** and the bigger half in a **min-heap**, balanced in size. The median is at the tops.

```python
import heapq

class MedianFinder:
    def __init__(self):
        self.low = []    # max-heap (negatives): smaller half
        self.high = []   # min-heap: bigger half

    def add(self, x):
        heapq.heappush(self.low, -x)
        heapq.heappush(self.high, -heapq.heappop(self.low))   # keep order
        if len(self.high) > len(self.low):
            heapq.heappush(self.low, -heapq.heappop(self.high))  # keep balance

    def median(self):
        if len(self.low) > len(self.high):
            return -self.low[0]
        return (-self.low[0] + self.high[0]) / 2

mf = MedianFinder()
for x in [5, 15, 1, 3]:
    mf.add(x)
print(mf.median())  # 4.0
```

Each `add` is O(log n); `median` is O(1).

## Scheduling: the most urgent first

Process meeting rooms, CPU tasks or delivery orders by always taking the earliest-ending or highest-priority item: that's a heap. Dijkstra's shortest path algorithm (lesson 25) is a heap-powered BFS.

## How to recognise the pattern

- "K largest / smallest / most frequent / closest".
- "K-th largest", "median", "merge k sorted…".
- "Always take the smallest / earliest / cheapest next" (greedy with a priority).
- A stream of data where you can't sort everything at once.

::tip When a problem says "k", think heap of size k. When it says "always the smallest next", think priority queue.

## Interview corner

Kth Largest Element, Top K Frequent Elements, K Closest Points, Merge k Sorted Lists, Find Median from Data Stream, Task Scheduler and Meeting Rooms II are commonly reported at **Amazon, Google, Meta, Microsoft, Uber and LinkedIn**. Say the trade-off: "Sorting is O(n log n); a size-k heap is O(n log k)."

## Common mistakes

- **Forgetting `heapq` is a min-heap** (negate for a max-heap).
- **Reading `heap[-1]` as the largest**: only `heap[0]` is guaranteed.
- **Choosing the wrong heap type** for top-k (largest needs a min-heap).
- **Comparing unorderable objects** in tuples; add a tie-breaking index.

## Exercise

1. Play the heap visualizer with `[9, 4, 7, 1, 8, 2]`. Count the swaps during each push.
2. Write `last_stone_weight(stones)`: repeatedly smash the two heaviest stones; return the last weight (or 0).
3. Write `sort_k_sorted(nums, k)` where each item is at most k positions from its sorted place, in O(n log k).

## Summary

- A heap keeps the min (or max) on top: peek O(1), push/pop O(log n), heapify O(n).
- Python's `heapq` is a min-heap; negate values for a max-heap.
- Top-k: keep a heap of size k (k largest → min-heap).
- Merge k lists, running median (two heaps) and scheduling are classic heap problems.

Next, you'll leave trees behind and explore **graphs** with BFS and DFS.
