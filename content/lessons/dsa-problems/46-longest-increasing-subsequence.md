---
title: Longest Increasing Subsequence
slug: problem-longest-increasing-subsequence
subject: dsa-problems
lesson: 46
difficulty: intermediate
level: Medium
pattern: Dynamic Programming
companies: Microsoft, Amazon, Google, Meta, Apple, Samsung
excerpt: The longest rising sequence you can pick from a list (gaps allowed). An O(n²) DP, then a clever O(n log n) "patience sorting" version.
---

A DP classic with a famous speed-up that interviewers love to ask about.

## The problem

Return the length of the longest **strictly increasing subsequence** of `nums`. A subsequence keeps the original order but may skip items.

## Examples

| nums | answer | one LIS |
|---|---|---|
| `[10, 9, 2, 5, 3, 7, 101, 18]` | 4 | 2, 3, 7, 18 |
| `[0, 1, 0, 3, 2, 3]` | 4 | 0, 1, 2, 3 |
| `[7, 7, 7, 7]` | 1 | 7 |

## Hints

::hint Hint 1 | Let best[i] = length of the longest increasing subsequence that ENDS at index i.
::hint Hint 2 | best[i] = 1 + the largest best[j] for j < i with nums[j] < nums[i].
::hint Hint 3 | For O(n log n): keep `tails[k]` = smallest possible ending value of an increasing subsequence of length k + 1, and binary search where each number fits.

## The easy idea: every number joins the best line it can

Walk left to right. Each number looks back at all earlier, smaller numbers and asks: "Whose sequence can I extend to become the longest?" It joins that one, adding 1.

| i | value | can extend after | best[i] |
|---|---|---|---|
| 0 | 10 | nothing | 1 |
| 1 | 9 | nothing | 1 |
| 2 | 2 | nothing | 1 |
| 3 | 5 | 2 | 2 |
| 4 | 3 | 2 | 2 |
| 5 | 7 | 5 or 3 | 3 |
| 6 | 101 | 7 | 4 |
| 7 | 18 | 7 | 4 |

## Solution 1: DP, O(n²)

```python
def length_of_lis(nums):
    best = [1] * len(nums)
    for i in range(len(nums)):
        for j in range(i):
            if nums[j] < nums[i]:
                best[i] = max(best[i], best[j] + 1)
    return max(best, default=0)

print(length_of_lis([10, 9, 2, 5, 3, 7, 101, 18]))  # 4
print(length_of_lis([0, 1, 0, 3, 2, 3]))            # 4
print(length_of_lis([7, 7, 7, 7]))                  # 1
```

## Solution 2: patience sorting, O(n log n)

Deal the numbers like cards into piles. Each card goes on the **leftmost** pile whose top card is ≥ it; if none, start a new pile on the right. The number of piles is the LIS length. `tails` stores the top card of each pile, which is always sorted, so binary search finds the spot.

```python
from bisect import bisect_left

def lis_fast(nums):
    tails = []
    for x in nums:
        i = bisect_left(tails, x)
        if i == len(tails):
            tails.append(x)       # new, longer pile
        else:
            tails[i] = x          # smaller top for that length
    return len(tails)

print(lis_fast([10, 9, 2, 5, 3, 7, 101, 18]))  # 4
print(lis_fast([0, 1, 0, 3, 2, 3]))            # 4
```

`tails` is **not** the actual subsequence; only its length is meaningful. Say that clearly if asked.

## Complexity

| Method | Time | Space |
|---|---|---|
| DP | O(n²) | O(n) |
| Patience sorting | O(n log n) | O(n) |

## Similar problems

Number of Longest Increasing Subsequence, Russian Doll Envelopes, Longest Common Subsequence, Maximum Length of Pair Chain.

Learn the pattern: [Dynamic Programming](/tutorials/dsa-dynamic-programming/).
