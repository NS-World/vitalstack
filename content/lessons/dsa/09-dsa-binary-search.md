---
title: Binary Search
slug: dsa-binary-search
subject: dsa
lesson: 9
difficulty: intermediate
excerpt: Halve the search space every step for O(log n). The bug-free template, first and last positions, search insert position, rotated arrays, and "binary search on the answer".
---

**Binary search** finds an item in **sorted** data by checking the middle and throwing away the half that can't contain the answer. For a million items it needs about **20** checks instead of a million. It's simple to describe and famously easy to get subtly wrong, so this lesson gives you a template you can trust.

## The guessing game analogy

"I'm thinking of a number from 1 to 100." The smart first guess is **50**. "Higher!" Now only 51–100 is possible, so guess **75**, and so on. Each guess halves the range: 100 → 50 → 25 → 13 → 7 → 4 → 2 → 1. Never more than 7 guesses. (Play **Binary Hunt** in Brain Games to feel it.)

::viz binary-search {"arr": [2, 5, 8, 12, 16, 23, 38, 56, 72, 91], "target": 23}

## The classic template

```python
def binary_search(arr, target):
    lo, hi = 0, len(arr) - 1
    while lo <= hi:
        mid = (lo + hi) // 2
        if arr[mid] == target:
            return mid
        elif arr[mid] < target:
            lo = mid + 1          # answer is to the right
        else:
            hi = mid - 1          # answer is to the left
    return -1

print(binary_search([2, 5, 8, 12, 16, 23, 38], 23))  # 5
print(binary_search([2, 5, 8, 12, 16, 23, 38], 7))   # -1
```

Three details that prevent most bugs:

1. `while lo <= hi` (with `<=`): a range of one item is still worth checking.
2. `lo = mid + 1` and `hi = mid - 1`: `mid` has already been checked, so exclude it. Otherwise the loop can run forever.
3. The range `[lo, hi]` always contains the answer if it exists. Ask yourself after every line: "Is that still true?"

O(log n) time, O(1) space. (In languages with fixed-size integers, `mid = lo + (hi - lo) // 2` avoids overflow; Python doesn't need it, but interviewers like seeing you know.)

## The "boundary" template: first position where a condition becomes true

Many problems aren't "find x" but "find the **first** index where something becomes true". Example: the first index with a value ≥ target. The condition looks like `False False False True True` across the array, and we want the first True.

```python
def lower_bound(arr, target):
    lo, hi = 0, len(arr)          # note: hi = len(arr)
    while lo < hi:
        mid = (lo + hi) // 2
        if arr[mid] >= target:
            hi = mid              # mid might be the answer: keep it
        else:
            lo = mid + 1
    return lo                     # first index with arr[i] >= target

print(lower_bound([1, 3, 5, 5, 5, 8], 5))  # 2
print(lower_bound([1, 3, 5, 5, 5, 8], 6))  # 5
print(lower_bound([1, 3, 5, 5, 5, 8], 9))  # 6
```

Here the loop is `while lo < hi`, and `hi = mid` (not `mid - 1`) because mid could be the answer. When the loop ends, `lo == hi` is the boundary.

Python's `bisect` module does exactly this:

```python
from bisect import bisect_left, bisect_right

arr = [1, 3, 5, 5, 5, 8]
print(bisect_left(arr, 5))   # 2
print(bisect_right(arr, 5))  # 5
```

`bisect_left` gives the index of the first 5; `bisect_right` gives the index just after the last 5.

### First and last position of a value

```python
from bisect import bisect_left, bisect_right

def search_range(nums, target):
    first = bisect_left(nums, target)
    if first == len(nums) or nums[first] != target:
        return [-1, -1]
    return [first, bisect_right(nums, target) - 1]

print(search_range([5, 7, 7, 8, 8, 10], 8))  # [3, 4]
print(search_range([5, 7, 7, 8, 8, 10], 6))  # [-1, -1]
```

### Search insert position

Where would the target go to keep the list sorted? That is `lower_bound`:

```python
from bisect import bisect_left

print(bisect_left([1, 3, 5, 6], 5))  # 2
print(bisect_left([1, 3, 5, 6], 2))  # 1
print(bisect_left([1, 3, 5, 6], 7))  # 4
```

## Search in a rotated sorted array

`[4, 5, 6, 7, 0, 1, 2]` was sorted, then rotated. At any `mid`, **one half is still sorted**. Check whether the target lies in that sorted half; if not, it's in the other.

```python
def search_rotated(nums, target):
    lo, hi = 0, len(nums) - 1
    while lo <= hi:
        mid = (lo + hi) // 2
        if nums[mid] == target:
            return mid
        if nums[lo] <= nums[mid]:                 # left half is sorted
            if nums[lo] <= target < nums[mid]:
                hi = mid - 1
            else:
                lo = mid + 1
        else:                                     # right half is sorted
            if nums[mid] < target <= nums[hi]:
                lo = mid + 1
            else:
                hi = mid - 1
    return -1

print(search_rotated([4, 5, 6, 7, 0, 1, 2], 0))  # 4
print(search_rotated([4, 5, 6, 7, 0, 1, 2], 3))  # -1
```

## Binary search on the answer

Sometimes there's no array to search, but the **answer** itself has a range, and you can test "does this answer work?". If the test goes `False False True True` as the answer grows, binary search the answer.

### Integer square root

```python
def int_sqrt(x):
    lo, hi = 0, x
    while lo < hi:
        mid = (lo + hi + 1) // 2      # round up so the loop always shrinks
        if mid * mid <= x:
            lo = mid
        else:
            hi = mid - 1
    return lo

print(int_sqrt(8))   # 2
print(int_sqrt(16))  # 4
```

### Koko eating bananas

Koko eats piles of bananas at speed k per hour (one pile per hour at most). What's the **slowest** speed that finishes within h hours? Faster always works if slower works, so binary search k:

```python
def min_eating_speed(piles, h):
    def hours(k):
        return sum((p + k - 1) // k for p in piles)   # ceil(p / k)

    lo, hi = 1, max(piles)
    while lo < hi:
        mid = (lo + hi) // 2
        if hours(mid) <= h:
            hi = mid          # fast enough: try slower
        else:
            lo = mid + 1
    return lo

print(min_eating_speed([3, 6, 7, 11], 8))  # 4
```

This "minimise the maximum" / "find the smallest value that works" trick appears in many hard-sounding problems (shipping packages in D days, splitting arrays…).

## How to recognise the pattern

- The data is **sorted** (or partly sorted, like rotated).
- The problem asks for O(log n).
- "Find the minimum / maximum value such that a condition holds," where the condition flips only once.

::tip Write the condition as a function that returns True/False, check it flips only once (False…False True…True), then binary search for the first True.

## Interview corner

Binary Search, Search in Rotated Sorted Array, Find First and Last Position, Find Minimum in Rotated Array and Koko Eating Bananas are commonly reported at **Google, Amazon, Meta, Microsoft, LinkedIn and Uber**. Interviewers watch closely for off-by-one errors: walk through a 2-element example out loud before saying you're done.

## Common mistakes

- **Infinite loops** from `lo = mid` with `mid = (lo + hi) // 2` (round up in that case).
- **Mixing templates**: `while lo <= hi` goes with `hi = mid - 1`; `while lo < hi` goes with `hi = mid`.
- **Using binary search on unsorted data.**
- **Not checking that the found index is really the target** (as in `search_range`).

## Exercise

1. In the visualizer, search for 91 and then for 2. Count the steps.
2. Write `find_min_rotated(nums)` returning the smallest value in a rotated sorted array in O(log n).
3. Write `ship_within_days(weights, days)`: the minimum ship capacity to ship all packages in order within the given days.

## Summary

- Binary search halves the search space each step: O(log n).
- Template 1 (`lo <= hi`) finds an exact value; template 2 (`lo < hi`) finds a boundary.
- `bisect_left` / `bisect_right` give first and after-last positions.
- "Binary search on the answer" solves optimisation problems with a once-flipping condition.

Next, you'll learn how **sorting** works, and why O(n log n) is the magic number.
