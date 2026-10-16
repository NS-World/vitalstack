---
title: 2D Dynamic Programming
slug: dsa-dp-2d
subject: dsa
lesson: 27
difficulty: advanced
excerpt: DP tables with rows and columns. Unique paths in a grid, longest common subsequence animated cell by cell, edit distance (how spell-checkers work) and the 0/1 knapsack.
---

Some problems need **two** numbers to describe a small subproblem: a row and a column in a grid, or a position in each of two strings. Then the DP table becomes a **2D grid**, and you fill it cell by cell. Once you see the pattern, these problems become surprisingly mechanical.

## The spreadsheet analogy

Think of a spreadsheet where each cell's formula only uses the cell **above**, the cell to the **left**, and maybe the **diagonal**. Fill row by row, left to right, and every cell's inputs are ready when you reach it. That's 2D DP.

## Unique paths in a grid

A robot starts top-left and can only move **right** or **down**. How many paths reach the bottom-right of an `m × n` grid?

- **State:** `dp[r][c]` = paths to cell (r, c).
- **Transition:** you came from above or from the left: `dp[r][c] = dp[r-1][c] + dp[r][c-1]`.
- **Base:** the first row and first column have exactly 1 path each.

```python
def unique_paths(m, n):
    dp = [[1] * n for _ in range(m)]
    for r in range(1, m):
        for c in range(1, n):
            dp[r][c] = dp[r - 1][c] + dp[r][c - 1]
    return dp[m - 1][n - 1]

print(unique_paths(3, 3))  # 6
print(unique_paths(3, 7))  # 28
```

### With obstacles

A blocked cell has 0 paths through it:

```python
def paths_with_obstacles(grid):
    rows, cols = len(grid), len(grid[0])
    dp = [[0] * cols for _ in range(rows)]
    for r in range(rows):
        for c in range(cols):
            if grid[r][c] == 1:
                dp[r][c] = 0
            elif r == 0 and c == 0:
                dp[r][c] = 1
            else:
                up = dp[r - 1][c] if r > 0 else 0
                left = dp[r][c - 1] if c > 0 else 0
                dp[r][c] = up + left
    return dp[-1][-1]

print(paths_with_obstacles([[0, 0, 0], [0, 1, 0], [0, 0, 0]]))  # 2
```

### Minimum path sum

Same shape, but take the **minimum** instead of adding:

```python
def min_path_sum(grid):
    rows, cols = len(grid), len(grid[0])
    dp = [row[:] for row in grid]
    for r in range(rows):
        for c in range(cols):
            if r == 0 and c == 0:
                continue
            up = dp[r - 1][c] if r > 0 else float("inf")
            left = dp[r][c - 1] if c > 0 else float("inf")
            dp[r][c] += min(up, left)
    return dp[-1][-1]

print(min_path_sum([[1, 3, 1], [1, 5, 1], [4, 2, 1]]))  # 7
```

The cheapest route is 1 → 3 → 1 → 1 → 1.

## Longest common subsequence (LCS)

The longest sequence of letters that appears in **both** strings in the same order (gaps allowed). It's how `diff` tools and DNA comparison work.

- **State:** `dp[i][j]` = LCS length of the first i letters of `a` and the first j letters of `b`.
- **If the letters match:** extend the diagonal: `dp[i][j] = dp[i-1][j-1] + 1`.
- **If not:** drop one letter from either string: `dp[i][j] = max(dp[i-1][j], dp[i][j-1])`.
- **Base:** row 0 and column 0 are 0 (an empty string has nothing in common).

Watch the table fill. When the letters match, the cell takes the diagonal value plus one:

::viz lcs {"a": "ABCBDA", "b": "BDCAB"}

```python
def lcs(a, b):
    dp = [[0] * (len(b) + 1) for _ in range(len(a) + 1)]
    for i in range(1, len(a) + 1):
        for j in range(1, len(b) + 1):
            if a[i - 1] == b[j - 1]:
                dp[i][j] = dp[i - 1][j - 1] + 1
            else:
                dp[i][j] = max(dp[i - 1][j], dp[i][j - 1])
    return dp[-1][-1]

print(lcs("ABCBDA", "BDCAB"))   # 3
print(lcs("abcde", "ace"))     # 3
print(lcs("abc", "xyz"))       # 0
```

**Time and space O(len(a) × len(b)).** Notice the `i - 1` and `j - 1` when reading letters: the table has one extra row and column for the empty prefix.

## Edit distance (how spell-checkers work)

The fewest **insertions, deletions or replacements** to turn one word into another. "horse" → "ros" needs 3.

- If the letters match, no cost: take the diagonal.
- Otherwise, 1 + the best of: replace (diagonal), delete (above), insert (left).
- Base: turning a prefix into an empty string costs its length.

```python
def edit_distance(a, b):
    dp = [[0] * (len(b) + 1) for _ in range(len(a) + 1)]
    for i in range(len(a) + 1):
        dp[i][0] = i
    for j in range(len(b) + 1):
        dp[0][j] = j
    for i in range(1, len(a) + 1):
        for j in range(1, len(b) + 1):
            if a[i - 1] == b[j - 1]:
                dp[i][j] = dp[i - 1][j - 1]
            else:
                dp[i][j] = 1 + min(dp[i - 1][j - 1],   # replace
                                   dp[i - 1][j],       # delete
                                   dp[i][j - 1])       # insert
    return dp[-1][-1]

print(edit_distance("horse", "ros"))            # 3
print(edit_distance("intention", "execution"))  # 5
print(edit_distance("", "abc"))                 # 3
```

## 0/1 knapsack

A bag holds weight `W`. Each item has a weight and value and can be taken **once**. Maximise the value.

- **State:** `best[w]` = best value using capacity w (with the items seen so far).
- **For each item:** skip it, or take it: `best[w - weight] + value`.
- Loop capacity **downwards** so each item is used at most once.

```python
def knapsack(items, W):
    best = [0] * (W + 1)
    for weight, value in items:
        for w in range(W, weight - 1, -1):
            best[w] = max(best[w], best[w - weight] + value)
    return best[W]

print(knapsack([(1, 1), (3, 4), (4, 5), (5, 7)], 7))  # 9
```

The best choice is the items weighing 3 and 4 (values 4 + 5 = 9). This 1D trick is a compressed 2D table (items × capacity). "Partition equal subset sum" and "target sum" are knapsack in disguise.

## Longest palindromic substring (expand around centre)

Not every 2D-looking problem needs a table. For palindromes, try each centre and expand outwards: O(n²) time, O(1) space.

```python
def longest_palindrome(s):
    best = ""
    for centre in range(len(s)):
        for lo, hi in ((centre, centre), (centre, centre + 1)):
            while lo >= 0 and hi < len(s) and s[lo] == s[hi]:
                lo -= 1
                hi += 1
            if hi - lo - 1 > len(best):
                best = s[lo + 1:hi]
    return best

print(longest_palindrome("babad"))   # bab
print(longest_palindrome("cbbd"))    # bb
```

## How to recognise 2D DP

- **Two strings** to compare (LCS, edit distance, interleaving, regex matching).
- **A grid** with moves only right/down.
- **Items × capacity** (knapsack and subset sums).
- The state needs two indices to be described.

::tip Draw the table on paper with the empty prefix as row 0 and column 0. Fill 3 or 4 cells by hand before coding: the formula usually jumps out.

## Interview corner

Unique Paths, Minimum Path Sum, Longest Common Subsequence, Edit Distance, Partition Equal Subset Sum, Longest Palindromic Substring, Interleaving String and Maximal Square are commonly reported at **Google, Amazon, Microsoft, Meta, Apple and Bloomberg**. Explaining what a single cell means ("dp[i][j] is the edit distance between the first i and first j letters") impresses more than the code itself.

## Common mistakes

- **Off-by-one** between table indices and string indices.
- **Forgetting the base row and column.**
- **Looping capacity upwards** in 0/1 knapsack (that allows reusing items).
- **Allocating with `[[0] * n] * m`**, which makes every row the same list. Use a list comprehension.

## Exercise

1. In the LCS visualizer, try `DYNAMIC` and `PROGRAMMING`. Which letters form the LCS?
2. Write `maximal_square(matrix)`: the biggest all-1 square. Hint: `dp[r][c] = 1 + min(up, left, diagonal)`.
3. Write `can_partition(nums)`: can the list be split into two halves with equal sums? Hint: knapsack to `sum // 2`.

## Summary

- 2D DP fills a table where each cell uses neighbours above, left or diagonal.
- Grids: unique paths and minimum path sum.
- Strings: LCS and edit distance with an extra row/column for empty prefixes.
- Knapsack: items × capacity, compressible to 1D by looping capacity downwards.

Next, you'll learn when the **greedy** choice is enough and master **interval** problems.
