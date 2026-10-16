---
title: Kth Largest Element in an Array
slug: problem-kth-largest-element
subject: dsa-problems
lesson: 33
difficulty: intermediate
level: Medium
pattern: Heap
companies: Meta, Amazon, Microsoft, Google, LinkedIn, Spotify
excerpt: Find the k-th largest number without sorting everything. A min-heap of size k, plus quickselect for an average O(n).
---

A favourite because it has three good answers, each with a different trade-off.

## The problem

Return the k-th largest element in `nums` (in sorted order, not the k-th distinct).

## Examples

| nums | k | answer |
|---|---|---|
| `[3, 2, 1, 5, 6, 4]` | 2 | 5 |
| `[3, 2, 3, 1, 2, 4, 5, 5, 6]` | 4 | 4 |

## Hints

::hint Hint 1 | Sorting works in O(n log n). Can you avoid sorting everything?
::hint Hint 2 | Keep only the k largest numbers seen so far in a MIN-heap. The smallest of them (the top) is the k-th largest.
::hint Hint 3 | Quickselect partitions like quicksort but only recurses into one side: O(n) on average.

## Solution 1: sort, O(n log n)

```python
def kth_largest_sort(nums, k):
    return sorted(nums, reverse=True)[k - 1]

print(kth_largest_sort([3, 2, 1, 5, 6, 4], 2))  # 5
```

## The easy idea: a VIP list with k seats

A club has only k VIP seats. Each new number arrives; if the seats are full and the newcomer beats the weakest VIP, the weakest leaves. At the end, the weakest VIP is the k-th largest overall. A min-heap keeps "the weakest VIP" at the top. Watch a heap organise itself:

::viz heap {"arr": [3, 2, 1, 5, 6, 4]}

## Solution 2: min-heap of size k, O(n log k)

```python
import heapq

def kth_largest(nums, k):
    heap = []
    for x in nums:
        heapq.heappush(heap, x)
        if len(heap) > k:
            heapq.heappop(heap)          # the weakest VIP leaves
    return heap[0]

print(kth_largest([3, 2, 1, 5, 6, 4], 2))           # 5
print(kth_largest([3, 2, 3, 1, 2, 4, 5, 5, 6], 4))  # 4
```

Works on an endless **stream** of numbers too, using only O(k) memory.

## Solution 3: quickselect, O(n) average

Pick a pivot, split into bigger, equal and smaller groups, and continue only in the group that contains the answer.

```python
import random

def quickselect(nums, k):
    pivot = random.choice(nums)
    bigger = [x for x in nums if x > pivot]
    equal = [x for x in nums if x == pivot]
    smaller = [x for x in nums if x < pivot]
    if k <= len(bigger):
        return quickselect(bigger, k)
    if k <= len(bigger) + len(equal):
        return pivot
    return quickselect(smaller, k - len(bigger) - len(equal))

print(quickselect([3, 2, 1, 5, 6, 4], 2))           # 5
print(quickselect([3, 2, 3, 1, 2, 4, 5, 5, 6], 4))  # 4
```

## Complexity

| Method | Time | Space |
|---|---|---|
| Sort | O(n log n) | O(n) |
| Heap of size k | O(n log k) | O(k) |
| Quickselect | O(n) average, O(n²) worst | O(n) here (in-place versions use O(1)) |

## Similar problems

Top K Frequent Elements, K Closest Points to Origin, Kth Largest Element in a Stream, Find Median from Data Stream.

Learn the pattern: [Heaps](/tutorials/dsa-heaps/).
