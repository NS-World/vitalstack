---
title: Best Time to Buy and Sell Stock
slug: problem-best-time-to-buy-and-sell-stock
subject: dsa-problems
lesson: 12
difficulty: beginner
level: Easy
pattern: Greedy
companies: Amazon, Meta, Microsoft, Google, Goldman Sachs, Bloomberg, Uber
excerpt: Buy once, sell once, maximise profit. Track the cheapest price so far in a single pass.
---

One pass, two variables. The classic "keep the best so far" trick.

## The problem

`prices[i]` is a stock's price on day i. Buy on one day and sell on a **later** day. Return the maximum profit, or 0 if no profit is possible.

## Examples

| prices | answer | why |
|---|---|---|
| `[7, 1, 5, 3, 6, 4]` | 5 | buy at 1, sell at 6 |
| `[7, 6, 4, 3, 1]` | 0 | prices only fall |

## Hints

::hint Hint 1 | If you sell on day i, the best day to have bought is the cheapest day BEFORE i.
::hint Hint 2 | Walk through the days keeping the cheapest price seen so far.

## Brute force, O(n²)

```python
def max_profit_slow(prices):
    best = 0
    for buy in range(len(prices)):
        for sell in range(buy + 1, len(prices)):
            best = max(best, prices[sell] - prices[buy])
    return best

print(max_profit_slow([7, 1, 5, 3, 6, 4]))  # 5
```

## The easy idea: "if only I'd bought at the lowest point"

Walk through the days with a notebook holding one number: the **cheapest price so far**. Each day, ask: "If I sold today, what's my profit?" Keep the best answer.

| day | price | cheapest so far | profit if sold today | best |
|---|---|---|---|---|
| 0 | 7 | 7 | 0 | 0 |
| 1 | 1 | 1 | 0 | 0 |
| 2 | 5 | 1 | 4 | 4 |
| 3 | 3 | 1 | 2 | 4 |
| 4 | 6 | 1 | 5 | 5 |
| 5 | 4 | 1 | 3 | 5 |

## Solution

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

## Complexity

- **Time O(n)**, **space O(1)**.

## The family of stock problems

- **Unlimited transactions (II):** add every upward step: `sum(max(0, b - a) for a, b in zip(prices, prices[1:]))`.
- **At most 2 or k transactions (III, IV):** dynamic programming with states.
- **With cooldown or fee:** DP with "holding" and "not holding" states.

```python
def max_profit_many(prices):
    return sum(max(0, b - a) for a, b in zip(prices, prices[1:]))

print(max_profit_many([7, 1, 5, 3, 6, 4]))  # 7
```

## Similar problems

Maximum Subarray (Kadane is the same idea in disguise), Best Time to Buy and Sell Stock II, III and with Cooldown.

Learn the pattern: [Kadane's Algorithm](/tutorials/dsa-kadane/).
