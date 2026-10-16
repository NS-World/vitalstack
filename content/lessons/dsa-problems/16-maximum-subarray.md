---
title: Maximum Subarray
slug: problem-maximum-subarray
subject: dsa-problems
lesson: 16
difficulty: intermediate
level: Medium
pattern: Kadane
companies: Amazon, Microsoft, Apple, LinkedIn, Google, Bloomberg, Cisco
excerpt: Find the continuous part of a list with the biggest sum. Kadane's algorithm: keep going while the running sum helps, restart when it hurts.
---

Kadane's algorithm is five lines long and one of the most-asked interview algorithms ever.

## The problem

Return the largest sum of any non-empty **continuous** subarray of `nums`.

## Examples

| nums | answer | subarray |
|---|---|---|
| `[-2, 1, -3, 4, -1, 2, 1, -5, 4]` | 6 | [4, -1, 2, 1] |
| `[1]` | 1 | [1] |
| `[-3, -1, -2]` | -1 | [-1] |

## Hints

::hint Hint 1 | At each position, the best subarray ENDING here either extends the previous one or starts fresh here.
::hint Hint 2 | Extend only if the previous running sum is positive. A negative running sum only drags you down.

## Brute force, O(n²)

```python
def max_subarray_slow(nums):
    best = nums[0]
    for i in range(len(nums)):
        total = 0
        for j in range(i, len(nums)):
            total += nums[j]
            best = max(best, total)
    return best

print(max_subarray_slow([-2, 1, -3, 4, -1, 2, 1, -5, 4]))  # 6
```

## The easy idea: carrying a backpack

You walk along the list carrying a backpack of points. Each number adds or removes points. If your backpack ever goes **negative**, drop it and start fresh: carrying debt can only make future totals smaller. Remember the heaviest the backpack ever got. Watch:

::viz kadane {"arr": [-2, 1, -3, 4, -1, 2, 1, -5, 4]}

## Solution

```python
def max_subarray(nums):
    current = best = nums[0]
    for x in nums[1:]:
        current = max(x, current + x)   # start fresh or extend
        best = max(best, current)
    return best

print(max_subarray([-2, 1, -3, 4, -1, 2, 1, -5, 4]))  # 6
print(max_subarray([1]))                              # 1
print(max_subarray([-3, -1, -2]))                     # -1
```

Starting with `nums[0]` (not 0) handles the all-negative case correctly.

## Complexity

- **Time O(n)**, **space O(1)**.

## Follow-up: return the subarray itself

Remember where the current run started, and save the start and end whenever `best` improves:

```python
def max_subarray_range(nums):
    current, best = nums[0], nums[0]
    start = best_l = best_r = 0
    for i in range(1, len(nums)):
        if current + nums[i] < nums[i]:
            current, start = nums[i], i
        else:
            current += nums[i]
        if current > best:
            best, best_l, best_r = current, start, i
    return nums[best_l:best_r + 1]

print(max_subarray_range([-2, 1, -3, 4, -1, 2, 1, -5, 4]))  # [4, -1, 2, 1]
```

## Similar problems

Maximum Product Subarray, Best Time to Buy and Sell Stock, Maximum Sum Circular Subarray, Largest Sum of Averages.

Learn the pattern: [Kadane's Algorithm](/tutorials/dsa-kadane/).
