---
title: Binary Search
slug: problem-binary-search
subject: dsa-problems
lesson: 17
difficulty: beginner
level: Easy
pattern: Binary Search
companies: Google, Amazon, Microsoft, Apple, Meta, Adobe
excerpt: Find a target in a sorted list in O(log n). The bug-free template, first and last position, and search insert position.
---

Simple to describe, famously easy to get wrong. Learn one template and use it everywhere.

## The problem

Given a **sorted** list `nums` and a `target`, return the target's index, or -1 if it's not there. Your solution must be O(log n).

## Examples

| nums | target | answer |
|---|---|---|
| `[-1, 0, 3, 5, 9, 12]` | 9 | 4 |
| `[-1, 0, 3, 5, 9, 12]` | 2 | -1 |

## Hints

::hint Hint 1 | Look at the middle. If it's too small, the target can only be in the right half.
::hint Hint 2 | Keep `lo` and `hi` as the range still possible. Stop when `lo > hi`.

## The easy idea: the number guessing game

"I'm thinking of a number from 1 to 100." Guess 50. "Higher!" Now it's 51–100: guess 75. Each guess halves the range, so 7 guesses are always enough for 100 numbers. Play it:

::viz binary-search {"arr": [-1, 0, 3, 5, 9, 12], "target": 9}

## Solution

```python
def search(nums, target):
    lo, hi = 0, len(nums) - 1
    while lo <= hi:
        mid = (lo + hi) // 2
        if nums[mid] == target:
            return mid
        if nums[mid] < target:
            lo = mid + 1           # target is to the right
        else:
            hi = mid - 1           # target is to the left
    return -1

print(search([-1, 0, 3, 5, 9, 12], 9))  # 4
print(search([-1, 0, 3, 5, 9, 12], 2))  # -1
print(search([5], 5))                   # 0
```

## Complexity

- **Time O(log n):** a million items need about 20 steps.
- **Space O(1).**

## Variation: first and last position

With duplicates, find the **first** index where `nums[i] >= target` (a "lower bound"). Don't stop when you find the target; keep searching left.

```python
def lower_bound(nums, target):
    lo, hi = 0, len(nums)
    while lo < hi:
        mid = (lo + hi) // 2
        if nums[mid] < target:
            lo = mid + 1
        else:
            hi = mid
    return lo

def search_range(nums, target):
    first = lower_bound(nums, target)
    if first == len(nums) or nums[first] != target:
        return [-1, -1]
    return [first, lower_bound(nums, target + 1) - 1]

print(search_range([5, 7, 7, 8, 8, 10], 8))  # [3, 4]
print(search_range([5, 7, 7, 8, 8, 10], 6))  # [-1, -1]
print(lower_bound([1, 3, 5, 6], 2))          # 1
```

`lower_bound` is also the answer to **Search Insert Position**: where would the target go to keep the list sorted?

## Common bugs

- `while lo < hi` vs `while lo <= hi` mixed up with `hi = mid` vs `hi = mid - 1`: pick one template and stick to it.
- Infinite loops when `lo = mid` (the range never shrinks).

## Similar problems

Search Insert Position, Find First and Last Position, Search in Rotated Sorted Array, Koko Eating Bananas, Sqrt(x).

Learn the pattern: [Binary Search](/tutorials/dsa-binary-search/).
