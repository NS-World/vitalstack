---
title: Combination Sum
slug: problem-combination-sum
subject: dsa-problems
lesson: 42
difficulty: intermediate
level: Medium
pattern: Backtracking
companies: Amazon, Airbnb, Meta, Microsoft, Uber, Snapchat
excerpt: Find every combination of numbers (reusable) that adds up to a target. Backtracking with a start index and early stopping.
---

Backtracking plus two classic tricks: a **start index** to avoid duplicate combinations, and **pruning** to stop early.

## The problem

Given distinct positive `candidates` and a `target`, return all unique combinations that sum to `target`. Each number may be used **unlimited** times.

## Examples

| candidates | target | answer |
|---|---|---|
| `[2, 3, 6, 7]` | 7 | `[[2, 2, 3], [7]]` |
| `[2, 3, 5]` | 8 | `[[2, 2, 2, 2], [2, 3, 3], [3, 5]]` |
| `[2]` | 1 | `[]` |

## Hints

::hint Hint 1 | Keep a running list and the amount still needed. When it reaches 0, record the list.
::hint Hint 2 | To avoid both [2, 3, 2] and [2, 2, 3], only allow choosing numbers at or after the current index.
::hint Hint 3 | Sort the candidates. If a candidate is bigger than what's left, all later ones are too: stop the loop.

## The easy idea: paying with coins

You must pay exactly 7 using coins worth 2, 3, 6 and 7 (unlimited supply). To avoid counting the same handful twice in different orders, always pick coins in **non-decreasing** order: once you pick a 3, you may not go back to 2s.

```text
need 7: pick 2 → need 5: pick 2 → need 3: pick 2 → need 1 ✗ (2 too big: stop)
                                         pick 3 → need 0 ✓ [2, 2, 3]
                         pick 3 → need 2 ✗ (3 too big: stop)
        pick 7 → need 0 ✓ [7]
```

## Solution

```python
def combination_sum(candidates, target):
    candidates.sort()
    out, current = [], []

    def build(start, remaining):
        if remaining == 0:
            out.append(current[:])
            return
        for i in range(start, len(candidates)):
            c = candidates[i]
            if c > remaining:
                break                         # sorted: nothing later fits
            current.append(c)
            build(i, remaining - c)           # i, not i + 1: reuse allowed
            current.pop()

    build(0, target)
    return out

print(combination_sum([2, 3, 6, 7], 7))  # [[2, 2, 3], [7]]
print(combination_sum([2, 3, 5], 8))     # [[2, 2, 2, 2], [2, 3, 3], [3, 5]]
print(combination_sum([2], 1))           # []
```

## Complexity

Exponential in the worst case: roughly O(n^(t/m)) where t is the target and m the smallest candidate. Pruning makes it fast in practice. Say this honestly in interviews; nobody expects a polynomial answer here.

## Variations (one-line changes)

| Problem | Change |
|---|---|
| Combination Sum II (each number once, duplicates in input) | recurse with `i + 1`; skip `i > start and nums[i] == nums[i-1]` |
| Combination Sum III (k numbers from 1–9) | candidates = 1..9, stop when length is k |
| Only count the combinations | use DP (Coin Change II) instead |

## Similar problems

Combination Sum II, Combination Sum III, Coin Change II, Subsets, Partition to K Equal Sum Subsets.

Learn the pattern: [Backtracking](/tutorials/dsa-backtracking/).
