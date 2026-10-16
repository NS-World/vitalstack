---
title: Permutations
slug: problem-permutations
subject: dsa-problems
lesson: 41
difficulty: intermediate
level: Medium
pattern: Backtracking
companies: Microsoft, Amazon, Meta, Google, LinkedIn, Bloomberg
excerpt: List every ordering of a set of numbers. Fill positions one by one with numbers not used yet, then undo and try the next.
---

Subsets ask "take it or not?". Permutations ask "**which one goes next?**"

## The problem

Given distinct numbers, return all possible orderings.

## Example

`[1, 2, 3]` → `[[1,2,3], [1,3,2], [2,1,3], [2,3,1], [3,1,2], [3,2,1]]` (3! = 6)

## Hints

::hint Hint 1 | Fill the first position with any of the n numbers, the second with any of the remaining n − 1, and so on.
::hint Hint 2 | Keep track of which numbers are already used in the current ordering.
::hint Hint 3 | After exploring a choice, undo it (pop and mark unused) before trying the next.

## The easy idea: seating guests

Three friends and three chairs. Choose who sits in chair 1 (3 options). Then chair 2 from who's left (2 options). Chair 3 gets the last person. 3 × 2 × 1 = 6 seatings. Backtracking means: after trying one person in a chair, **stand them up** and try the next.

```text
chair 1:      1           2           3
chair 2:    2   3       1   3       1   2
chair 3:    3   2       3   1       2   1
```

## Solution

```python
def permute(nums):
    out, current, used = [], [], [False] * len(nums)

    def fill():
        if len(current) == len(nums):
            out.append(current[:])
            return
        for i, x in enumerate(nums):
            if not used[i]:
                used[i] = True
                current.append(x)        # choose
                fill()                   # explore
                current.pop()            # un-choose
                used[i] = False

    fill()
    return out

print(permute([1, 2, 3]))  # [[1, 2, 3], [1, 3, 2], [2, 1, 3], [2, 3, 1], [3, 1, 2], [3, 2, 1]]
print(len(permute([1, 2, 3, 4])))  # 24
print(permute([0, 1]))     # [[0, 1], [1, 0]]
```

## The backtracking template

Choose → explore → un-choose. This exact shape solves permutations, combinations, N-Queens, Sudoku and word search:

```text
def backtrack(state):
    if state is complete: record it; return
    for choice in available choices:
        make the choice
        backtrack(state)
        undo the choice
```

## Complexity

- **Time O(n · n!):** n! orderings, each copied in O(n).
- **Space O(n)** for the recursion (plus the output).

## Follow-up: duplicates (Permutations II)

For `[1, 1, 2]`, sort first, and skip a number if it equals the previous one **and** the previous one isn't currently used:

```python
def permute_unique(nums):
    nums.sort()
    out, current, used = [], [], [False] * len(nums)

    def fill():
        if len(current) == len(nums):
            out.append(current[:])
            return
        for i in range(len(nums)):
            if used[i] or (i > 0 and nums[i] == nums[i - 1] and not used[i - 1]):
                continue
            used[i] = True
            current.append(nums[i])
            fill()
            current.pop()
            used[i] = False

    fill()
    return out

print(permute_unique([1, 1, 2]))  # [[1, 1, 2], [1, 2, 1], [2, 1, 1]]
```

## Similar problems

Permutations II, Next Permutation, Letter Case Permutation, N-Queens, Subsets.

Learn the pattern: [Backtracking](/tutorials/dsa-backtracking/).
