---
title: Coin Change
slug: problem-coin-change
subject: dsa-problems
lesson: 45
difficulty: intermediate
level: Medium
pattern: Dynamic Programming
companies: Amazon, Google, Microsoft, Goldman Sachs, Apple, Bloomberg, Paytm
excerpt: The fewest coins that make an amount. Why greedy fails, and how a DP table builds every amount from smaller ones.
---

The problem that proves greedy isn't always right, and DP is.

## The problem

Given coin values `coins` (unlimited supply) and an `amount`, return the **fewest** coins that add up to it, or -1 if it's impossible.

## Examples

| coins | amount | answer |
|---|---|---|
| `[1, 2, 5]` | 11 | 3 (5 + 5 + 1) |
| `[1, 3, 4]` | 6 | 2 (3 + 3) |
| `[2]` | 3 | -1 |
| `[1]` | 0 | 0 |

## Hints

::hint Hint 1 | Greedy (largest coin first) gives 4 + 1 + 1 for amount 6 with coins [1, 3, 4]. But 3 + 3 is better.
::hint Hint 2 | Suppose the last coin is c. Then you need best(amount − c) + 1 coins. Try every c.
::hint Hint 3 | Fill best[0], best[1], … best[amount] in order. best[0] = 0.

## The easy idea: build every amount from smaller ones

To make 6, try each coin as the **last** coin:

- last coin 1 → best way to make 5, plus 1
- last coin 3 → best way to make 3, plus 1
- last coin 4 → best way to make 2, plus 1

If we already know the best answers for 5, 3 and 2, we just pick the smallest. So solve every amount from 0 upwards. Watch the table:

::viz coin-change {"coins": [1, 3, 4], "amount": 6}

## Solution

```python
def coin_change(coins, amount):
    INF = float("inf")
    best = [0] + [INF] * amount
    for a in range(1, amount + 1):
        for c in coins:
            if c <= a:
                best[a] = min(best[a], best[a - c] + 1)
    return best[amount] if best[amount] != INF else -1

print(coin_change([1, 2, 5], 11))  # 3
print(coin_change([1, 3, 4], 6))   # 2
print(coin_change([2], 3))         # -1
print(coin_change([1], 0))         # 0
```

## Complexity

- **Time O(amount × number of coins)**, **space O(amount)**.

## Coin Change II: count the ways

How many combinations make the amount? Put the **coin loop outside**, so each combination is built in one fixed coin order and counted once:

```python
def coin_ways(coins, amount):
    ways = [1] + [0] * amount
    for c in coins:
        for a in range(c, amount + 1):
            ways[a] += ways[a - c]
    return ways[amount]

print(coin_ways([1, 2, 5], 5))  # 4
print(coin_ways([2], 3))        # 0
```

Swapping the loops would count 1+2 and 2+1 separately (permutations instead of combinations), a classic interview trap.

## Similar problems

Coin Change II, Perfect Squares, Minimum Cost For Tickets, Combination Sum IV, Word Break.

Learn the pattern: [Dynamic Programming](/tutorials/dsa-dynamic-programming/).
