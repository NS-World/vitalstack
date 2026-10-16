---
title: Prefix Sums
slug: dsa-prefix-sum
subject: dsa
lesson: 7
difficulty: intermediate
excerpt: Precompute running totals once and answer any range sum in O(1). Then combine prefix sums with a hash map to count subarrays that sum to k, even with negative numbers.
---

"What is the total of items 3 to 7?" Answering that once is easy: add them up. Answering it **a million times** for different ranges is slow if each answer needs a loop. **Prefix sums** do the work once up front, then every range question costs a single subtraction.

## The cricket scoreboard analogy

A scoreboard shows the **total runs after each over**: 6, 14, 20, 31, 45… To know how many runs were scored in overs 3 to 5, you don't replay the overs. You take the total after over 5 and subtract the total after over 2. Done.

That running total is a **prefix sum**.

::viz prefix-sum {"arr": [3, 1, 4, 1, 5, 9, 2, 6], "l": 2, "r": 5}

## Building prefix sums

`prefix[i]` = sum of the first `i` items. We add a 0 at the start so the formula works for ranges starting at index 0.

```python
def build_prefix(arr):
    prefix = [0]
    for x in arr:
        prefix.append(prefix[-1] + x)
    return prefix

def range_sum(prefix, l, r):          # sum of arr[l..r], inclusive
    return prefix[r + 1] - prefix[l]

arr = [3, 1, 4, 1, 5, 9, 2, 6]
p = build_prefix(arr)
print(p)                   # [0, 3, 4, 8, 9, 14, 23, 25, 31]
print(range_sum(p, 2, 5))  # 19
print(range_sum(p, 0, 0))  # 3
```

Building: O(n) once. Each query: O(1). For q queries that's O(n + q) instead of O(n·q).

Python's `itertools.accumulate(arr)` builds the running totals too (without the leading 0).

## Problem: find the pivot index

The pivot is where the sum on the left equals the sum on the right.

```python
def pivot_index(nums):
    total = sum(nums)
    left = 0
    for i, x in enumerate(nums):
        if left == total - left - x:
            return i
        left += x
    return -1

print(pivot_index([1, 7, 3, 6, 5, 6]))  # 3
print(pivot_index([1, 2, 3]))           # -1
```

We only need the running left sum; the right sum is `total - left - x`.

## Problem: product of array except self

Return an array where each item is the product of all the **other** items, without division. Use a prefix product from the left and a suffix product from the right:

```python
def product_except_self(nums):
    n = len(nums)
    out = [1] * n
    left = 1
    for i in range(n):
        out[i] = left
        left *= nums[i]
    right = 1
    for i in range(n - 1, -1, -1):
        out[i] *= right
        right *= nums[i]
    return out

print(product_except_self([1, 2, 3, 4]))  # [24, 12, 8, 6]
```

O(n) time, O(1) extra space besides the output. A favourite at **Amazon, Meta and Microsoft**.

## Prefix sums + hash map: count subarrays with sum k

This is where prefix sums become a superpower. Count how many contiguous subarrays add up to k. Numbers may be **negative**, so a sliding window won't work.

The key idea: a subarray from i+1 to j sums to k when

```text
prefix[j] - prefix[i] = k   →   prefix[i] = prefix[j] - k
```

So as we walk through, we ask: **"How many earlier prefix sums equal `current - k`?"** A hash map counts how often each prefix sum has appeared.

```python
def subarray_sum(nums, k):
    count = 0
    current = 0
    seen = {0: 1}              # the empty prefix
    for x in nums:
        current += x
        count += seen.get(current - k, 0)
        seen[current] = seen.get(current, 0) + 1
    return count

print(subarray_sum([1, 1, 1], 2))         # 2
print(subarray_sum([1, 2, 3], 3))         # 2
print(subarray_sum([3, -1, 2, -2, 4], 2)) # 4
```

`seen = {0: 1}` handles subarrays that start at index 0. This one pattern solves a whole family: subarrays divisible by k, equal numbers of 0s and 1s, and more.

### Longest subarray with equal 0s and 1s

Treat 0 as −1. Then "equal 0s and 1s" means "sum is 0". Remember the **first** index where each prefix sum appeared:

```python
def find_max_length(nums):
    first = {0: -1}
    current = best = 0
    for i, x in enumerate(nums):
        current += 1 if x == 1 else -1
        if current in first:
            best = max(best, i - first[current])
        else:
            first[current] = i
    return best

print(find_max_length([0, 1, 0, 0, 1, 1, 0]))  # 6
```

## 2D prefix sums (a peek)

For grids, `P[r][c]` stores the sum of the rectangle from (0,0) to (r−1,c−1). Any rectangle sum is then four lookups. It's the same idea in two dimensions and powers "range sum query 2D" problems.

## How to recognise the pattern

- Many questions about **sums of ranges**.
- "Count subarrays with sum / property X", especially with **negative numbers**.
- "Left part vs right part" comparisons (pivot, product except self).

::tip When you see "subarray sum equals k", think: prefix sum + hash map counting `current - k`.

## Interview corner

Subarray Sum Equals K, Product of Array Except Self, Find Pivot Index, Range Sum Query and Contiguous Array are commonly reported at **Meta, Amazon, Google, Microsoft and Goldman Sachs**. The hash map version is a classic "do you really understand it?" question: be ready to explain why `seen` starts with `{0: 1}`.

## Common mistakes

- **Off-by-one in the formula**: it's `prefix[r + 1] - prefix[l]` with the leading 0.
- **Forgetting `{0: 1}`** and missing subarrays that start at index 0.
- **Updating `seen` before counting**, which can count an empty subarray when k = 0.
- **Trying sliding window with negative numbers.**

## Exercise

1. In the visualizer, query the whole array (l = 0, r = 7). What is `prefix[8] - prefix[0]`?
2. Write `running_sum(nums)` that returns the running totals (`[1, 2, 3]` → `[1, 3, 6]`).
3. Write `count_divisible(nums, k)`: count subarrays whose sum is divisible by k. Hint: use `current % k` as the hash map key.

## Summary

- `prefix[i]` stores the sum of the first i items; any range sum is one subtraction.
- Prefix and suffix products solve "everything except me" problems.
- Prefix sums + a hash map count subarrays with sum k in O(n), even with negatives.
- Remember the leading 0 and the `{0: 1}` starting entry.

Next, you'll learn **Kadane's algorithm** for the maximum subarray: a beautiful one-pass idea.
