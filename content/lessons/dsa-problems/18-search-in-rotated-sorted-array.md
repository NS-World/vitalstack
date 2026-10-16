---
title: Search in Rotated Sorted Array
slug: problem-search-in-rotated-sorted-array
subject: dsa-problems
lesson: 18
difficulty: intermediate
level: Medium
pattern: Binary Search
companies: Meta, Amazon, Microsoft, Google, LinkedIn, Bloomberg, Uber
excerpt: A sorted list was rotated at an unknown point. Binary search still works, because one half is always sorted.
---

The key insight: when you cut a rotated sorted list in half, **at least one half is still perfectly sorted**.

## The problem

A sorted list of distinct numbers was rotated, e.g. `[0, 1, 2, 4, 5, 6, 7]` became `[4, 5, 6, 7, 0, 1, 2]`. Find `target`'s index in O(log n), or return -1.

## Examples

| nums | target | answer |
|---|---|---|
| `[4, 5, 6, 7, 0, 1, 2]` | 0 | 4 |
| `[4, 5, 6, 7, 0, 1, 2]` | 3 | -1 |
| `[1]` | 0 | -1 |

## Hints

::hint Hint 1 | Compare `nums[lo]` with `nums[mid]`. If `nums[lo] <= nums[mid]`, the left half is sorted.
::hint Hint 2 | In a sorted half, you can check in O(1) whether the target lies inside it. If yes, search there; otherwise search the other half.

## The easy idea: a clock face

Imagine the numbers written around a clock that was turned. Cut it in the middle: one side runs smoothly upward (sorted), the other side contains the "jump". For the smooth side you can tell instantly whether the target is between its ends. That decides which half to keep.

```text
[4, 5, 6, 7, 0, 1, 2]
 lo       mid      hi
 4 <= 7  → left half [4..7] is sorted
 target 0 is not in 4..7 → search the right half
```

## Solution

```python
def search_rotated(nums, target):
    lo, hi = 0, len(nums) - 1
    while lo <= hi:
        mid = (lo + hi) // 2
        if nums[mid] == target:
            return mid
        if nums[lo] <= nums[mid]:                    # left half sorted
            if nums[lo] <= target < nums[mid]:
                hi = mid - 1
            else:
                lo = mid + 1
        else:                                        # right half sorted
            if nums[mid] < target <= nums[hi]:
                lo = mid + 1
            else:
                hi = mid - 1
    return -1

print(search_rotated([4, 5, 6, 7, 0, 1, 2], 0))  # 4
print(search_rotated([4, 5, 6, 7, 0, 1, 2], 3))  # -1
print(search_rotated([1], 0))                    # -1
print(search_rotated([3, 1], 1))                 # 1
```

## Related: find the minimum

The minimum is where the "jump" is. Compare the middle with the **right** end:

```python
def find_min(nums):
    lo, hi = 0, len(nums) - 1
    while lo < hi:
        mid = (lo + hi) // 2
        if nums[mid] > nums[hi]:
            lo = mid + 1          # the drop is to the right
        else:
            hi = mid
    return nums[lo]

print(find_min([4, 5, 6, 7, 0, 1, 2]))  # 0
print(find_min([11, 13, 15, 17]))       # 11
```

## Complexity

- **Time O(log n)**, **space O(1)**.

## Similar problems

Find Minimum in Rotated Sorted Array, Search in Rotated Sorted Array II (with duplicates), Find Peak Element, Binary Search.

Learn the pattern: [Binary Search](/tutorials/dsa-binary-search/).
