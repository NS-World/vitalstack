---
title: Subsets
slug: problem-subsets
subject: dsa-problems
lesson: 40
difficulty: intermediate
level: Medium
pattern: Backtracking
companies: Meta, Amazon, Google, Microsoft, Bloomberg, Uber
excerpt: List every possible subset. For each item, choose "take it" or "skip it"; backtracking explores both.
---

The cleanest introduction to backtracking: every item has exactly two choices.

## The problem

Given distinct numbers, return all possible subsets (the power set), in any order.

## Example

`[1, 2, 3]` → `[[], [1], [2], [1, 2], [3], [1, 3], [2, 3], [1, 2, 3]]` (8 subsets = 2³)

## Hints

::hint Hint 1 | For each number, you either include it or you don't. n numbers → 2ⁿ subsets.
::hint Hint 2 | Recursion: decide for item i, then recurse on item i + 1. When you've decided for all items, record the subset.
::hint Hint 3 | Iterative alternative: start with [[]]; for each number, copy every existing subset and add the number to the copy.

## The easy idea: a pizza order

You're ordering a pizza with toppings mushrooms, olives and peppers. For each topping you say **yes** or **no**. Every different sequence of yes/no answers is a different pizza. Drawing the yes/no choices as a tree gives exactly 2³ = 8 leaves. Watch backtracking walk the tree:

::viz subsets {"arr": [1, 2, 3]}

## Solution: backtracking

```python
def subsets(nums):
    out, current = [], []

    def choose(i):
        if i == len(nums):
            out.append(current[:])      # copy! current keeps changing
            return
        current.append(nums[i])         # take nums[i]
        choose(i + 1)
        current.pop()                   # undo (backtrack)
        choose(i + 1)                   # skip nums[i]

    choose(0)
    return out

print(subsets([1, 2, 3]))  # [[1, 2, 3], [1, 2], [1, 3], [1], [2, 3], [2], [3], []]
print(len(subsets([1, 2, 3, 4])))  # 16
```

`current[:]` makes a copy. Appending `current` itself would store the same list 8 times, and it ends up empty.

## Solution: iterative (cascading)

```python
def subsets_iter(nums):
    out = [[]]
    for x in nums:
        out += [s + [x] for s in out]
    return out

print(subsets_iter([1, 2, 3]))  # [[], [1], [2], [1, 2], [3], [1, 3], [2, 3], [1, 2, 3]]
```

## Complexity

- **Time O(n · 2ⁿ):** 2ⁿ subsets, each up to n long to copy.
- **Space O(n · 2ⁿ)** for the output.

## Follow-up: duplicates (Subsets II)

With `[1, 2, 2]`, sort first and skip a number equal to the previous one **at the same level** of the loop:

```python
def subsets_with_dup(nums):
    nums.sort()
    out = []

    def build(start, current):
        out.append(current[:])
        for i in range(start, len(nums)):
            if i > start and nums[i] == nums[i - 1]:
                continue
            current.append(nums[i])
            build(i + 1, current)
            current.pop()

    build(0, [])
    return out

print(subsets_with_dup([1, 2, 2]))  # [[], [1], [1, 2], [1, 2, 2], [2], [2, 2]]
```

## Similar problems

Subsets II, Permutations, Combination Sum, Letter Combinations of a Phone Number, Generate Parentheses.

Learn the pattern: [Backtracking](/tutorials/dsa-backtracking/).
