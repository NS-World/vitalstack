---
title: Top K Frequent Elements
slug: problem-top-k-frequent-elements
subject: dsa-problems
lesson: 5
difficulty: intermediate
level: Medium
pattern: Heap
companies: Amazon, Meta, Google, Microsoft, Yelp, Oracle
excerpt: Return the k most common numbers. Count with a dictionary, then pick the top k with a heap or bucket sort.
---

"What are the top 10 trending hashtags?" is this exact problem.

## The problem

Given `nums` and `k`, return the `k` most frequent values (any order).

## Examples

| nums | k | answer |
|---|---|---|
| `[1, 1, 1, 2, 2, 3]` | 2 | `[1, 2]` |
| `[4]` | 1 | `[4]` |

## Hints

::hint Hint 1 | Step one is always counting: a dictionary from value to count.
::hint Hint 2 | Sorting all counts is O(n log n). A heap of size k gives O(n log k).
::hint Hint 3 | Counts are between 1 and n. Could you use the count as a list index (buckets)?

## Step 1: count

```python
from collections import Counter

print(Counter([1, 1, 1, 2, 2, 3]))  # Counter({1: 3, 2: 2, 3: 1})
```

## Solution 1: sort by count, O(n log n)

```python
from collections import Counter

def top_k_sort(nums, k):
    counts = Counter(nums)
    return sorted(counts, key=counts.get, reverse=True)[:k]

print(top_k_sort([1, 1, 1, 2, 2, 3], 2))  # [1, 2]
```

## Solution 2: heap, O(n log k)

```python
import heapq
from collections import Counter

def top_k_frequent(nums, k):
    counts = Counter(nums)
    return heapq.nlargest(k, counts, key=counts.get)

print(top_k_frequent([1, 1, 1, 2, 2, 3], 2))  # [1, 2]
print(top_k_frequent([4], 1))                 # [4]
```

## Solution 3: bucket sort, O(n)

Make shelves numbered 1 to n. Put each value on the shelf matching its count. Then read shelves from the top down.

```python
from collections import Counter

def top_k_buckets(nums, k):
    counts = Counter(nums)
    shelves = [[] for _ in range(len(nums) + 1)]
    for value, c in counts.items():
        shelves[c].append(value)
    out = []
    for c in range(len(shelves) - 1, 0, -1):
        for value in shelves[c]:
            out.append(value)
            if len(out) == k:
                return out
    return out

print(top_k_buckets([1, 1, 1, 2, 2, 3], 2))        # [1, 2]
print(top_k_buckets([5, 5, 6, 6, 6, 7, 5, 5], 1))  # [5]
```

## Complexity

| Method | Time | Space |
|---|---|---|
| Sort | O(n log n) | O(n) |
| Heap | O(n log k) | O(n) |
| Buckets | O(n) | O(n) |

## Similar problems

Top K Frequent Words, Kth Largest Element, Sort Characters by Frequency, K Closest Points to Origin.

Learn the pattern: [Heaps](/tutorials/dsa-heaps/).
