---
title: Merge Sort and Quick Sort
slug: dsa-merge-quick-sort
subject: dsa
lesson: 11
difficulty: intermediate
excerpt: The two classic O(n log n) sorting algorithms, animated. Divide and conquer, merging sorted halves, partitioning around a pivot, and Quickselect for the k-th largest element.
---

Simple sorts compare almost every pair: O(n²). **Merge sort** and **quick sort** use **divide and conquer** to reach **O(n log n)**: split the problem into smaller pieces, solve them, and combine. These ideas also solve many other problems, so they are worth understanding deeply.

## Divide and conquer in one sentence

> If a problem is too big, split it into smaller copies of itself, solve those, and combine the answers.

Sorting 1,000 exam papers alone is tiring. Split the pile between two friends, who each split theirs again… until everyone has one paper (already "sorted"). Then pairs of friends **merge** their sorted piles, and the merged piles merge again, all the way back up.

## Merge sort

1. **Split** the list into two halves.
2. **Sort** each half (by calling merge sort again: recursion).
3. **Merge** the two sorted halves into one sorted list.

::viz merge-sort {"arr": [38, 27, 43, 3, 9, 82, 10]}

```python
def merge_sort(arr):
    if len(arr) <= 1:
        return arr                       # one item is already sorted
    mid = len(arr) // 2
    left = merge_sort(arr[:mid])
    right = merge_sort(arr[mid:])
    return merge(left, right)

def merge(a, b):
    out, i, j = [], 0, 0
    while i < len(a) and j < len(b):
        if a[i] <= b[j]:                 # <= keeps the sort stable
            out.append(a[i])
            i += 1
        else:
            out.append(b[j])
            j += 1
    return out + a[i:] + b[j:]

print(merge_sort([38, 27, 43, 3, 9, 82, 10]))  # [3, 9, 10, 27, 38, 43, 82]
```

### Why O(n log n)?

- Splitting in half repeatedly gives **log n levels** (8 items → 4 → 2 → 1 is 3 levels).
- At each level, merging touches every item once: **O(n) per level**.
- Total: **O(n log n)**, always, even in the worst case.

The cost: merging needs **O(n) extra memory** for the new lists.

## Quick sort

1. Pick a **pivot** value.
2. **Partition**: move smaller values to its left and bigger values to its right. Now the pivot is in its final place.
3. Quick sort the left part and the right part.

::viz quick-sort {"arr": [10, 80, 30, 90, 40, 50, 70]}

```python
def quick_sort(arr, lo=0, hi=None):
    if hi is None:
        hi = len(arr) - 1
    if lo < hi:
        p = partition(arr, lo, hi)
        quick_sort(arr, lo, p - 1)
        quick_sort(arr, p + 1, hi)
    return arr

def partition(arr, lo, hi):
    pivot = arr[hi]                     # Lomuto scheme: last item as pivot
    i = lo - 1                          # end of the "smaller" zone
    for j in range(lo, hi):
        if arr[j] < pivot:
            i += 1
            arr[i], arr[j] = arr[j], arr[i]
    arr[i + 1], arr[hi] = arr[hi], arr[i + 1]
    return i + 1

print(quick_sort([10, 80, 30, 90, 40, 50, 70]))  # [10, 30, 40, 50, 70, 80, 90]
```

### Speed and the pivot problem

- **Average:** O(n log n), and very fast in practice because it works in place with good memory access.
- **Worst case:** O(n²), when the pivot is always the smallest or largest (for example, an already sorted list with "last item" as pivot).
- **Fix:** choose a **random** pivot. The worst case becomes astronomically unlikely.

```python
import random

def quick_sort_random(arr):
    if len(arr) <= 1:
        return arr
    pivot = random.choice(arr)
    smaller = [x for x in arr if x < pivot]
    equal = [x for x in arr if x == pivot]
    bigger = [x for x in arr if x > pivot]
    return quick_sort_random(smaller) + equal + quick_sort_random(bigger)

print(quick_sort_random([3, 6, 1, 8, 1, 9, 2]))  # [1, 1, 2, 3, 6, 8, 9]
```

This version is easy to read (and handles duplicates well), though it uses extra memory.

## Merge sort vs quick sort

| | Merge sort | Quick sort |
|---|---|---|
| Worst case | O(n log n) | O(n²) (rare with a random pivot) |
| Extra memory | O(n) | O(log n) (in-place version) |
| Stable? | Yes | No |
| Typical use | Linked lists, external sorting, when stability matters | General in-memory sorting |

Python's built-in Timsort is a clever mix of merge sort and insertion sort.

## Using the ideas beyond sorting

### Quickselect: the k-th largest element in O(n) on average

Partition once, then only recurse into the **one side** that contains the answer:

```python
import random

def kth_largest(nums, k):
    target = len(nums) - k             # index in sorted order
    lo, hi = 0, len(nums) - 1
    nums = nums[:]
    while True:
        r = random.randint(lo, hi)
        nums[r], nums[hi] = nums[hi], nums[r]
        pivot, i = nums[hi], lo
        for j in range(lo, hi):
            if nums[j] < pivot:
                nums[i], nums[j] = nums[j], nums[i]
                i += 1
        nums[i], nums[hi] = nums[hi], nums[i]
        if i == target:
            return nums[i]
        if i < target:
            lo = i + 1
        else:
            hi = i - 1

print(kth_largest([3, 2, 1, 5, 6, 4], 2))  # 5
```

On average each step throws away about half: n + n/2 + n/4 + … ≈ 2n, so O(n).

### Counting inversions with merge sort

An **inversion** is a pair i < j with `a[i] > a[j]`: a measure of "how unsorted" a list is. While merging, whenever we take from the right half, every remaining item in the left half forms an inversion with it:

```python
def count_inversions(arr):
    def sort(a):
        if len(a) <= 1:
            return a, 0
        mid = len(a) // 2
        left, x = sort(a[:mid])
        right, y = sort(a[mid:])
        out, i, j, inv = [], 0, 0, x + y
        while i < len(left) and j < len(right):
            if left[i] <= right[j]:
                out.append(left[i])
                i += 1
            else:
                out.append(right[j])
                inv += len(left) - i
                j += 1
        return out + left[i:] + right[j:], inv
    return sort(arr)[1]

print(count_inversions([2, 4, 1, 3, 5]))  # 3
```

O(n log n) instead of checking all pairs. (That's also the minimum number of adjacent swaps to sort the list, the target in **Sort Sprint**.)

## Interview corner

Sort an Array, Kth Largest Element (Quickselect or a heap), Sort List (merge sort on a linked list), Count Inversions and Merge k Sorted Lists are commonly reported at **Amazon, Microsoft, Google, Meta and Adobe**. Be ready to compare the two sorts: worst case, memory and stability.

::tip If an interviewer says "do better than sorting", think Quickselect (O(n) average) or a heap (O(n log k)).

## Common mistakes

- **Forgetting the base case** in recursion (`len(arr) <= 1`), causing infinite recursion.
- **Using `<` instead of `<=` in merge**, which makes merge sort unstable.
- **Always picking the first/last pivot on sorted input**, hitting O(n²).
- **Off-by-one in partition boundaries.**

## Exercise

1. Play merge sort and quick sort on `[5, 4, 3, 2, 1]`. Which needs more steps? Why?
2. Write `merge_sort_desc(arr)` that sorts in descending order.
3. Use the counting-inversions code to count inversions in `[8, 4, 2, 1]`. (Answer: 6.)

## Summary

- Divide and conquer: split, solve smaller copies, combine.
- Merge sort: always O(n log n), stable, O(n) extra memory.
- Quick sort: average O(n log n), in place, O(n²) worst case; use a random pivot.
- Partitioning gives Quickselect (k-th element in O(n) average); merging counts inversions.

Next, you'll understand the engine behind both: **recursion**.
