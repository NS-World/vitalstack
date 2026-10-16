---
title: Longest Consecutive Sequence
slug: problem-longest-consecutive-sequence
subject: dsa-problems
lesson: 7
difficulty: intermediate
level: Medium
pattern: Hashing
companies: Google, Amazon, Meta, Microsoft, Spotify
excerpt: Find the longest run of consecutive numbers in an unsorted list in O(n), by only counting from the start of each run.
---

A neat set trick: only start counting at the **beginning** of a run.

## The problem

Given an unsorted list, return the length of the longest sequence of consecutive integers (like 1, 2, 3, 4). Aim for O(n).

## Examples

| nums | answer | the run |
|---|---|---|
| `[100, 4, 200, 1, 3, 2]` | 4 | 1, 2, 3, 4 |
| `[0, 3, 7, 2, 5, 8, 4, 6, 0, 1]` | 9 | 0 to 8 |
| `[]` | 0 | |

## Hints

::hint Hint 1 | Sorting gives O(n log n). To beat it, put everything in a set for O(1) lookups.
::hint Hint 2 | A number x is the START of a run only if x − 1 is not in the set. Only count upward from starts.

## Solution 1: sort, O(n log n)

```python
def longest_run_sorted(nums):
    nums = sorted(set(nums))
    best = cur = 1 if nums else 0
    for a, b in zip(nums, nums[1:]):
        cur = cur + 1 if b == a + 1 else 1
        best = max(best, cur)
    return best

print(longest_run_sorted([100, 4, 200, 1, 3, 2]))  # 4
```

## The easy idea: find the start of each run

Imagine numbered houses scattered on a street. To measure each row of houses, go to a house whose left neighbour is **missing** (a start), then walk right counting houses. Never start in the middle of a row, so each house is walked past only once.

## Solution 2: set, O(n)

```python
def longest_consecutive(nums):
    present = set(nums)
    best = 0
    for x in present:
        if x - 1 not in present:          # x starts a run
            length = 1
            while x + length in present:
                length += 1
            best = max(best, length)
    return best

print(longest_consecutive([100, 4, 200, 1, 3, 2]))          # 4
print(longest_consecutive([0, 3, 7, 2, 5, 8, 4, 6, 0, 1]))  # 9
print(longest_consecutive([]))                              # 0
```

## Complexity

- **Time O(n):** the inner `while` looks like a nested loop, but each number is counted at most once in total, because counting only starts from run beginnings.
- **Space O(n)** for the set.

Explaining why the nested loop is still O(n) is the key moment in this interview.

## Similar problems

Contains Duplicate, Missing Number, First Missing Positive, Longest Increasing Subsequence (a different "longest").

Learn the pattern: [Hashing](/tutorials/dsa-hashing/).
