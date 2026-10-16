---
title: Backtracking
slug: dsa-backtracking
subject: dsa
lesson: 13
difficulty: intermediate
excerpt: Explore every choice with choose, explore, un-choose. Subsets, permutations, combination sum, generate parentheses and word search, with a live decision tree.
---

"Generate **all** subsets." "List **every** way to arrange these letters." "Find **any** path through this maze." When a problem asks you to explore all possibilities, the tool is **backtracking**: try a choice, explore everything that follows from it, then **undo** the choice and try the next one.

## The maze analogy

Walking through a maze, at each fork you pick a path. If you hit a dead end, you **walk back** to the last fork and try a different path. You never explore the same path twice, and you eventually try them all.

That "walk back" is **backtracking**.

## The template: choose, explore, un-choose

```python
def backtrack(state):
    if is_complete(state):
        save(state)
        return
    for choice in choices(state):
        make(choice)          # choose
        backtrack(state)      # explore
        undo(choice)          # un-choose
```

Every backtracking solution is a version of these lines.

## Subsets: take it or leave it

For each item, there are two choices: **take it** or **leave it**. The choices form a decision tree; each path from top to bottom is one subset.

::viz subsets {"arr": [1, 2, 3]}

```python
def subsets(nums):
    res, path = [], []

    def backtrack(i):
        if i == len(nums):
            res.append(path[:])          # save a copy!
            return
        path.append(nums[i])             # choose: take nums[i]
        backtrack(i + 1)                 # explore
        path.pop()                       # un-choose
        backtrack(i + 1)                 # explore without nums[i]

    backtrack(0)
    return res

print(subsets([1, 2, 3]))
# [[1, 2, 3], [1, 2], [1, 3], [1], [2, 3], [2], [3], []]
```

n items give 2ⁿ subsets, so the time is O(n · 2ⁿ) (each subset takes O(n) to copy). Exponential is fine here: the output itself is exponential.

`path[:]` saves a **copy**. Saving `path` itself would save a reference that keeps changing, and you'd end up with a list of empty lists.

## Permutations: every order

At each position, choose any item not used yet:

```python
def permutations(nums):
    res, path, used = [], [], [False] * len(nums)

    def backtrack():
        if len(path) == len(nums):
            res.append(path[:])
            return
        for i in range(len(nums)):
            if used[i]:
                continue
            used[i] = True
            path.append(nums[i])
            backtrack()
            path.pop()
            used[i] = False

    backtrack()
    return res

print(permutations([1, 2, 3]))
# [[1, 2, 3], [1, 3, 2], [2, 1, 3], [2, 3, 1], [3, 1, 2], [3, 2, 1]]
print(len(permutations([1, 2, 3, 4])))  # 24
```

n! permutations, O(n · n!) time.

## Combination sum: pruning

Find all combinations of candidates (reusable) that add up to a target. **Pruning**: stop exploring as soon as the sum is too big. Sorting first makes pruning easy.

```python
def combination_sum(candidates, target):
    candidates.sort()
    res, path = [], []

    def backtrack(start, remaining):
        if remaining == 0:
            res.append(path[:])
            return
        for i in range(start, len(candidates)):
            c = candidates[i]
            if c > remaining:
                break                    # prune: bigger ones won't fit either
            path.append(c)
            backtrack(i, remaining - c)  # i, not i + 1: c can be reused
            path.pop()

    backtrack(0, target)
    return res

print(combination_sum([2, 3, 6, 7], 7))  # [[2, 2, 3], [7]]
print(combination_sum([2, 3, 5], 8))     # [[2, 2, 2, 2], [2, 3, 3], [3, 5]]
```

Starting each loop at `start` (not 0) avoids duplicates like [2, 2, 3] and [3, 2, 2].

## Generate parentheses

List all valid combinations of n pairs of brackets. Only add `(` if some remain, and only add `)` if it would still be valid:

```python
def generate_parentheses(n):
    res = []

    def backtrack(s, opened, closed):
        if len(s) == 2 * n:
            res.append(s)
            return
        if opened < n:
            backtrack(s + "(", opened + 1, closed)
        if closed < opened:
            backtrack(s + ")", opened, closed + 1)

    backtrack("", 0, 0)
    return res

print(generate_parentheses(3))  # ['((()))', '(()())', '(())()', '()(())', '()()()']
```

Here the strings are rebuilt on each call (`s + "("`), so there's nothing to undo: immutable state makes "un-choose" automatic.

## Word search in a grid

Can the word be formed by moving up/down/left/right through adjacent letters, without reusing a cell?

```python
def exist(board, word):
    rows, cols = len(board), len(board[0])

    def dfs(r, c, i):
        if i == len(word):
            return True
        if r < 0 or c < 0 or r >= rows or c >= cols or board[r][c] != word[i]:
            return False
        saved, board[r][c] = board[r][c], "#"     # choose: mark as used
        found = (dfs(r + 1, c, i + 1) or dfs(r - 1, c, i + 1) or
                 dfs(r, c + 1, i + 1) or dfs(r, c - 1, i + 1))
        board[r][c] = saved                       # un-choose
        return found

    return any(dfs(r, c, 0) for r in range(rows) for c in range(cols))

grid = [list("ABCE"), list("SFCS"), list("ADEE")]
print(exist(grid, "ABCCED"))  # True
print(exist(grid, "ABCB"))    # False
```

## How to recognise the pattern

- "Find **all** …", "list **every** …", "generate all combinations / permutations / subsets".
- "Is there **any** way to …" when you can't do better than trying options (puzzles, N-Queens, Sudoku).
- Small input sizes (n ≤ 15 or so): a hint that exponential time is expected.

::tip Draw the decision tree for a tiny input before coding. The levels tell you what to loop over; the leaves tell you when to save.

## Interview corner

Subsets, Permutations, Combination Sum, Generate Parentheses, Word Search, Letter Combinations of a Phone Number and N-Queens are commonly reported at **Amazon, Google, Meta, Microsoft, Uber and Bloomberg**. Interviewers check that you undo your choices correctly and can explain the exponential complexity.

## Common mistakes

- **Saving `path` instead of `path[:]`.**
- **Forgetting to undo** (`pop()`, unmark a cell), which corrupts other branches.
- **Duplicate results** from starting loops at 0 instead of `start`.
- **No pruning**, making the search far slower than needed.

## Exercise

1. Play the subsets visualizer with `[1, 2, 3, 4]`. How many leaves are there?
2. Write `letter_combinations(digits)` for phone keypad letters (`"23"` → `["ad","ae","af","bd",…]`).
3. Write `subsets_with_dup(nums)` for input with duplicates (`[1, 2, 2]` → 6 unique subsets). Hint: sort, then skip equal neighbours at the same level.

## Summary

- Backtracking explores choices with **choose → explore → un-choose**.
- Subsets: take or leave each item (2ⁿ). Permutations: pick any unused item (n!).
- Prune branches that can't succeed; start loops at `start` to avoid duplicates.
- Save copies of the current path, and always undo changes.

Next, you'll learn **linked lists**, the data structure made of pointers.
