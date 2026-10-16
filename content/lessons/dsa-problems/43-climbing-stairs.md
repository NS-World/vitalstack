---
title: Climbing Stairs
slug: problem-climbing-stairs
subject: dsa-problems
lesson: 43
difficulty: beginner
level: Easy
pattern: Dynamic Programming
companies: Amazon, Google, Adobe, Apple, Microsoft, Uber
excerpt: How many ways to climb n stairs taking 1 or 2 steps? The gentlest introduction to dynamic programming.
---

If dynamic programming scares you, start here. It's Fibonacci wearing a disguise.

## The problem

You climb a staircase of `n` steps. Each move is 1 or 2 steps. How many distinct ways can you reach the top?

## Examples

| n | answer | ways |
|---|---|---|
| 2 | 2 | 1+1, 2 |
| 3 | 3 | 1+1+1, 1+2, 2+1 |
| 5 | 8 | |

## Hints

::hint Hint 1 | Think about your LAST move. You arrived at step n from step n − 1 or from step n − 2.
::hint Hint 2 | So ways(n) = ways(n − 1) + ways(n − 2).
::hint Hint 3 | Plain recursion repeats work. Build up from the bottom instead, remembering only the last two answers.

## Brute force: plain recursion, O(2ⁿ)

```python
def climb_slow(n):
    if n <= 1:
        return 1
    return climb_slow(n - 1) + climb_slow(n - 2)

print(climb_slow(5))  # 8
```

Correct, but `climb_slow(40)` makes hundreds of millions of calls, because it solves the same small problems over and over.

## The easy idea: fill in a table from the bottom

How many ways to stand on step 0? 1 (do nothing). Step 1? 1. Every later step = the two steps below it added together. Watch the table fill in, each cell built from the two before it:

::viz climbing-stairs {"n": 7}

## Solution: bottom-up, O(n) time, O(1) space

```python
def climb_stairs(n):
    a, b = 1, 1                 # ways to reach step i-2 and i-1
    for _ in range(n - 1):
        a, b = b, a + b
    return b

print(climb_stairs(2))   # 2
print(climb_stairs(3))   # 3
print(climb_stairs(5))   # 8
print(climb_stairs(45))  # 1836311903
```

## Solution: top-down with memo

Same recursion as the brute force, plus a cache:

```python
from functools import lru_cache

@lru_cache(maxsize=None)
def climb_memo(n):
    if n <= 1:
        return 1
    return climb_memo(n - 1) + climb_memo(n - 2)

print(climb_memo(45))  # 1836311903
```

## Complexity

| Version | Time | Space |
|---|---|---|
| Plain recursion | O(2ⁿ) | O(n) |
| Memo | O(n) | O(n) |
| Two variables | O(n) | O(1) |

## Follow-ups

- **Steps of 1, 2 or 3?** `ways(n) = ways(n-1) + ways(n-2) + ways(n-3)`.
- **Each step has a cost?** Min Cost Climbing Stairs: take the min instead of the sum.

## Similar problems

Min Cost Climbing Stairs, Fibonacci Number, House Robber, Decode Ways, Unique Paths.

Learn the pattern: [Dynamic Programming](/tutorials/dsa-dynamic-programming/).
