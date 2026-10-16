---
title: Dynamic Programming (1D)
slug: dsa-dynamic-programming
subject: dsa
lesson: 26
difficulty: advanced
excerpt: Dynamic programming without the fear. Remember answers instead of recomputing them. Memoization vs tabulation, climbing stairs and coin change animated, house robber, and a 4-step recipe that works for most DP problems.
---

**Dynamic programming (DP)** sounds scary, but the idea is simple: **if you've already solved a small problem, write the answer down and reuse it.** Don't solve the same thing twice. That's it. Everything else is practice at spotting which small problems to solve.

## The "what is 1+1+1+1+1?" analogy

Write `1+1+1+1+1+1+1+1` on paper and ask a friend for the total. They count: 8. Now add `+1` on the left. They answer 9 instantly. Why? They **remembered** the 8 and only added one more. That's dynamic programming: reuse what you already know.

## Step 1: see the waste in plain recursion

Fibonacci: `fib(n) = fib(n-1) + fib(n-2)`. The plain recursive version recomputes the same values again and again. Play the tree with memo **off**, then switch it **on**:

::viz fib-tree {"n": 5, "memo": "no"}

Without memo, `fib(30)` makes over a million calls. With memo, just 31.

```python
from functools import lru_cache

calls = 0

def fib_slow(n):
    global calls
    calls += 1
    return n if n < 2 else fib_slow(n - 1) + fib_slow(n - 2)

print(fib_slow(20))  # 6765
print(calls)         # 21891

@lru_cache(maxsize=None)
def fib_fast(n):
    return n if n < 2 else fib_fast(n - 1) + fib_fast(n - 2)

print(fib_fast(80))  # 23416728348467685
```

## Two ways to do DP

| Top-down (memoization) | Bottom-up (tabulation) |
|---|---|
| Write the recursion, then cache results | Fill a table from the smallest case upwards |
| Easy to write from the recursive idea | No recursion limit, usually a bit faster |
| `@lru_cache` or a `memo` dict | A list `dp` and a `for` loop |

Both give the same answer and the same big-O. Start with whichever feels natural; interviewers accept both.

## The 4-step recipe

1. **State:** what does `dp[i]` mean, in words? ("the number of ways to reach step i")
2. **Choice / transition:** how is `dp[i]` built from smaller states? ("last move was 1 step or 2 steps")
3. **Base cases:** the smallest answers you know without thinking.
4. **Order and answer:** fill from small to big; which cell holds the final answer?

Say these four out loud in an interview and half the problem is solved.

## Climbing stairs

You climb `n` stairs taking 1 or 2 steps at a time. How many different ways?

- **State:** `dp[i]` = ways to reach stair i.
- **Transition:** you arrived from stair i−1 or i−2, so `dp[i] = dp[i-1] + dp[i-2]`.
- **Base:** `dp[0] = 1` (stand still), `dp[1] = 1`.

::viz climbing-stairs {"n": 7}

```python
def climb_stairs(n):
    dp = [0] * (n + 1)
    dp[0] = 1
    if n >= 1:
        dp[1] = 1
    for i in range(2, n + 1):
        dp[i] = dp[i - 1] + dp[i - 2]
    return dp[n]

print(climb_stairs(3))  # 3
print(climb_stairs(7))  # 21
```

### Space trick: keep only what you need

`dp[i]` only looks back 2 cells, so two variables are enough: O(1) space.

```python
def climb_stairs_small(n):
    a, b = 1, 1          # ways to reach i-2 and i-1
    for _ in range(n - 1):
        a, b = b, a + b
    return b

print(climb_stairs_small(7))   # 21
print(climb_stairs_small(45))  # 1836311903
```

## House robber

Houses in a row hold money. You can't rob two neighbours (the alarm goes off). What's the most you can take?

- **State:** `best[i]` = most money from the first i houses.
- **Choice at house i:** skip it (`best[i-1]`) or rob it (`best[i-2] + money[i]`). Take the bigger one.

```python
def rob(nums):
    prev, cur = 0, 0                  # best up to i-2, best up to i-1
    for x in nums:
        prev, cur = cur, max(cur, prev + x)
    return cur

print(rob([2, 7, 9, 3, 1]))  # 12
print(rob([1, 2, 3, 1]))     # 4
```

For `[2, 7, 9, 3, 1]` the best is 2 + 9 + 1 = 12. The "take it or skip it" choice appears in a huge number of DP problems.

## Coin change (minimum coins)

Coins `[1, 3, 4]`, amount 6. Greedy (biggest coin first) gives 4 + 1 + 1 = **3 coins**, but 3 + 3 = **2 coins** is better. Greedy fails, DP doesn't.

- **State:** `dp[a]` = fewest coins that make amount a.
- **Transition:** try every coin c as the last coin: `dp[a] = min(dp[a - c] + 1)`.
- **Base:** `dp[0] = 0`; impossible amounts stay infinity.

::viz coin-change {"coins": [1, 3, 4], "amount": 6}

```python
def coin_change(coins, amount):
    INF = float("inf")
    dp = [0] + [INF] * amount
    for a in range(1, amount + 1):
        for c in coins:
            if c <= a and dp[a - c] + 1 < dp[a]:
                dp[a] = dp[a - c] + 1
    return dp[amount] if dp[amount] != INF else -1

print(coin_change([1, 3, 4], 6))   # 2
print(coin_change([1, 2, 5], 11))  # 3
print(coin_change([2], 3))         # -1
```

**Time O(amount × coins), space O(amount).**

### Counting the ways instead

How many **combinations** make the amount? Loop over coins on the **outside** so each combination is counted once (1+2 and 2+1 are the same):

```python
def coin_ways(coins, amount):
    ways = [1] + [0] * amount
    for c in coins:
        for a in range(c, amount + 1):
            ways[a] += ways[a - c]
    return ways[amount]

print(coin_ways([1, 2, 5], 5))  # 4
```

The 4 ways: 5, 2+2+1, 2+1+1+1, 1+1+1+1+1.

## Longest increasing subsequence

The longest list of numbers, in order (not necessarily next to each other), that keeps going up.

- **State:** `dp[i]` = length of the longest increasing subsequence **ending at** i.
- **Transition:** for every earlier `j` with a smaller value, `dp[i] = max(dp[j] + 1)`.

```python
def lis(nums):
    dp = [1] * len(nums)
    for i in range(len(nums)):
        for j in range(i):
            if nums[j] < nums[i]:
                dp[i] = max(dp[i], dp[j] + 1)
    return max(dp, default=0)

print(lis([10, 9, 2, 5, 3, 7, 101, 18]))  # 4
```

One answer is 2, 3, 7, 18. This is O(n²); a clever binary search version runs in O(n log n), a good follow-up to mention.

## Word break

Can the string be split into dictionary words? `ok[i]` means "the first i letters can be split".

```python
def word_break(s, words):
    words = set(words)
    ok = [True] + [False] * len(s)
    for i in range(1, len(s) + 1):
        for j in range(i):
            if ok[j] and s[j:i] in words:
                ok[i] = True
                break
    return ok[len(s)]

print(word_break("leetcode", ["leet", "code"]))                        # True
print(word_break("catsandog", ["cats", "dog", "sand", "and", "cat"]))  # False
```

## How to recognise a DP problem

- "**How many ways**…", "**minimum / maximum** cost…", "**is it possible**…".
- Choices at each step (take or skip, step 1 or 2, which coin).
- The brute-force recursion **repeats the same subproblems** (draw the tree!).
- Greedy seems tempting but you can find a counter-example.

::tip Stuck? Write the plain recursive solution first, add `@lru_cache`, and you already have a correct DP. Convert to a table afterwards if needed.

## Interview corner

Climbing Stairs, House Robber, Coin Change, Longest Increasing Subsequence, Word Break, Decode Ways, Maximum Product Subarray and Partition Equal Subset Sum are commonly reported at **Amazon, Google, Microsoft, Meta, Adobe and Goldman Sachs**. Interviewers love it when you start with recursion, point out the repeated work, and then say "so I'll memoize it".

## Common mistakes

- **Not defining the state in words** before coding.
- **Wrong base cases** (often `dp[0]`).
- **Wrong loop order** in counting problems (combinations vs permutations).
- **Using greedy** where DP is needed (coin change with `[1, 3, 4]`).

## Exercise

1. In the coin change visualizer, try coins `[2, 5]` and amount 3. Why is the answer impossible?
2. Write `min_cost_stairs(cost)`: each step has a cost, you can climb 1 or 2, start at step 0 or 1.
3. Write `decode_ways(s)`: "12" can be "AB" (1, 2) or "L" (12), so 2 ways.

## Summary

- DP = recursion + remembering answers to repeated subproblems.
- Top-down (memo) and bottom-up (table) are equally valid.
- Recipe: state, transition, base cases, order.
- Classics: climbing stairs, house robber, coin change, LIS, word break.

Next, you'll extend DP to **two dimensions**: grids and pairs of strings.
