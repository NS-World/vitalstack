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

## Walkthrough: buckets for `[1, 1, 1, 2, 2, 3]`, k = 2

The counts are `{1: 3, 2: 2, 3: 1}`. We put each value on the shelf numbered by its count:

| shelf (count) | values |
|---|---|
| 6, 5, 4 | empty |
| 3 | `[1]` |
| 2 | `[2]` |
| 1 | `[3]` |

Reading from the top shelf down, we collect 1, then 2. That's k = 2 values, so we stop and return `[1, 2]`. A value can never appear more than n times, which is why n + 1 shelves are always enough.

## Which solution should you write in an interview?

Start by saying the sort solution out loud: it's short and obviously correct. Then offer the heap, which is the answer most interviewers expect, and mention bucket sort as the O(n) option. `heapq.nlargest` keeps only k items in its heap, which matters when n is huge and k is small, such as the top 10 hashtags out of millions.

## Common mistakes

- **Returning the counts instead of the values.** The question wants `[1, 2]`, not `[3, 2]`.
- **Using `counts.most_common()` without k.** It sorts everything; `counts.most_common(k)` is fine and uses a heap internally.
- **Off-by-one in the buckets.** You need `len(nums) + 1` shelves so that a value appearing n times has a shelf numbered n.

## Follow-ups interviewers ask

- *What if two values have the same count?* Ask which one should win. For "Top K Frequent Words", ties go to the alphabetically smaller word.
- *What if the numbers arrive as an endless stream?* Keep running counts in a dictionary and a heap of size k, updating as each number arrives.

## Similar problems

Top K Frequent Words, Kth Largest Element, Sort Characters by Frequency, K Closest Points to Origin.

Learn the pattern: [Heaps](/tutorials/dsa-heaps/).
