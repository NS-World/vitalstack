---
title: "Kadane's Algorithm: Maximum Subarray"
slug: dsa-kadane
subject: dsa
lesson: 8
difficulty: intermediate
excerpt: Find the contiguous subarray with the biggest sum in one pass. The "extend or restart" idea behind Kadane's algorithm, the stock-trading variant, and maximum product.
---

**Problem:** given numbers (some negative), find the contiguous subarray with the **largest sum**. For `[-2, 1, -3, 4, -1, 2, 1, -5, 4]` the answer is `[4, -1, 2, 1]` with sum **6**.

Checking every subarray is O(n²) (or O(n³) if you re-add each one). **Kadane's algorithm** does it in **one pass, O(n), with O(1) memory**. It is short, elegant, and one of the most frequently asked interview questions.

## The road trip analogy

You're on a road trip collecting money from friends along the way (positive numbers) and paying tolls (negative numbers). At each town you ask yourself one question:

> "Is the trip so far **helping** me, or **dragging me down**?"

If your running total has gone **negative**, carrying it forward can only hurt. You'd be better off **starting a fresh trip from this town**. Meanwhile, you remember the best total you have ever had.

That's Kadane: at each number, either **extend** the current run or **restart** from here.

::viz kadane {"arr": [-2, 1, -3, 4, -1, 2, 1, -5, 4]}

## The code

```python
def max_subarray(nums):
    cur = best = nums[0]
    for x in nums[1:]:
        cur = max(x, cur + x)     # extend, or restart at x
        best = max(best, cur)
    return best

print(max_subarray([-2, 1, -3, 4, -1, 2, 1, -5, 4]))  # 6
print(max_subarray([5, 4, -1, 7, 8]))                 # 23
print(max_subarray([-3, -1, -2]))                     # -1
```

- `cur` = the best sum of a subarray that **ends exactly here**.
- `best` = the best sum seen anywhere so far.

Starting with `nums[0]` (not 0) makes the all-negative case correct: the answer is the largest single number, −1.

## Why does it work?

Any subarray ending at position i either:

1. is just `[nums[i]]` on its own, or
2. is a subarray ending at i−1, **plus** `nums[i]`.

The best of option 2 is `cur + nums[i]` (because `cur` is already the best ending at i−1). So the best ending at i is `max(nums[i], cur + nums[i])`. That's dynamic programming in disguise (lesson 26): each answer builds on the previous one.

## Also return the subarray itself

```python
def max_subarray_with_range(nums):
    cur = best = nums[0]
    start = best_start = best_end = 0
    for i in range(1, len(nums)):
        if nums[i] > cur + nums[i]:
            cur = nums[i]
            start = i                 # restart here
        else:
            cur += nums[i]
        if cur > best:
            best, best_start, best_end = cur, start, i
    return best, nums[best_start:best_end + 1]

print(max_subarray_with_range([-2, 1, -3, 4, -1, 2, 1, -5, 4]))  # (6, [4, -1, 2, 1])
```

## Variant: best time to buy and sell a stock

Given daily prices, buy once and sell later for the maximum profit. Same spirit: walk once, remembering the **cheapest price so far**.

```python
def max_profit(prices):
    cheapest = float("inf")
    best = 0
    for p in prices:
        cheapest = min(cheapest, p)
        best = max(best, p - cheapest)
    return best

print(max_profit([7, 1, 5, 3, 6, 4]))  # 5
print(max_profit([7, 6, 4, 3, 1]))     # 0
```

Buy at 1, sell at 6: profit 5. If prices only fall, don't trade: 0.

## Variant: maximum product subarray

With products, a **negative × negative** becomes positive, so the smallest (most negative) product can suddenly become the largest. Track both:

```python
def max_product(nums):
    hi = lo = best = nums[0]
    for x in nums[1:]:
        candidates = (x, hi * x, lo * x)
        hi, lo = max(candidates), min(candidates)
        best = max(best, hi)
    return best

print(max_product([2, 3, -2, 4]))     # 6
print(max_product([-2, 3, -4]))       # 24
print(max_product([-2, 0, -1]))       # 0
```

## Variant: maximum circular subarray

If the array wraps around, the best subarray is either a normal one (Kadane), or it wraps, which means it's the **total minus the minimum subarray**:

```python
def max_circular(nums):
    def kadane(arr):
        cur = best = arr[0]
        for x in arr[1:]:
            cur = max(x, cur + x)
            best = max(best, cur)
        return best

    best_normal = kadane(nums)
    if best_normal < 0:
        return best_normal                     # all negative
    min_sub = -kadane([-x for x in nums])      # minimum subarray
    return max(best_normal, sum(nums) - min_sub)

print(max_circular([5, -3, 5]))     # 10
print(max_circular([-3, -2, -3]))   # -2
```

## How to recognise the pattern

- "Maximum (or minimum) sum of a **contiguous** subarray."
- "Best profit from one buy and one sell."
- Any running decision of the form "keep going or start over".

::tip Kadane is a sliding window that never needs a left pointer: the "restart" decision replaces the shrinking.

## Interview corner

Maximum Subarray and Best Time to Buy and Sell Stock are among the most reported interview problems at **Amazon, Microsoft, Google, Apple, LinkedIn and Bloomberg**; Maximum Product Subarray is a common follow-up. Expect to be asked: "What if all numbers are negative?" and "Can you return the subarray, not just the sum?"

## Common mistakes

- **Starting `best` at 0**, which returns 0 for an all-negative array.
- **Resetting `cur` to 0** instead of to the current number.
- **Forgetting the negative × negative case** in the product variant.
- **Selling before buying** in the stock problem (update `cheapest` first, then profit is ≥ 0).

## Exercise

1. Play the visualizer with `[1, -2, 3, 10, -4, 7, 2, -5]`. What is the best subarray?
2. Write `min_subarray(nums)` that returns the smallest contiguous sum.
3. Write `max_profit_many(prices)` where you may buy and sell many times (but hold one share at a time). Hint: add up every upward step.

## Summary

- Kadane: at each number, extend the current run or restart; remember the best.
- O(n) time, O(1) space; start from `nums[0]` to handle all-negative input.
- Stock profit: remember the cheapest price so far.
- Products need both the max and the min because of negatives.

Next, you'll master **binary search**, the O(log n) workhorse of sorted data.
