---
title: Edit Distance
slug: problem-edit-distance
subject: dsa-problems
lesson: 48
difficulty: advanced
level: Hard
pattern: Dynamic Programming
companies: Google, Amazon, Microsoft, Meta, Bloomberg, Grammarly
excerpt: The fewest insertions, deletions and replacements to turn one word into another. The algorithm behind spell-checkers and DNA comparison.
---

When your phone suggests "hello" for "helo", it's using edit distance.

## The problem

Return the minimum number of operations (insert a letter, delete a letter, replace a letter) to convert `word1` into `word2`.

## Examples

| word1 | word2 | answer | how |
|---|---|---|---|
| `"horse"` | `"ros"` | 3 | horse → rorse → rose → ros |
| `"intention"` | `"execution"` | 5 | |
| `""` | `"abc"` | 3 | insert 3 letters |

## Hints

::hint Hint 1 | Let dp[i][j] = edit distance between the first i letters of word1 and the first j letters of word2.
::hint Hint 2 | Base cases: turning i letters into nothing costs i deletions; turning nothing into j letters costs j insertions.
::hint Hint 3 | If the last letters match, no cost: dp[i−1][j−1]. Otherwise 1 + min(replace: diagonal, delete: above, insert: left).

## The easy idea: three moves in a grid

Build a table like LCS, with an extra row and column for the empty prefix. Each cell asks: "What was my cheapest last move?"

- **Letters equal:** free, take the diagonal.
- **Replace:** diagonal + 1.
- **Delete** a letter from word1: cell above + 1.
- **Insert** a letter into word1: cell to the left + 1.

```text
        ""  r  o  s
    ""   0  1  2  3
    h    1  1  2  3
    o    2  2  1  2
    r    3  2  2  2
    s    4  3  3  2
    e    5  4  4  3   ← answer
```

## Solution

```python
def min_distance(a, b):
    dp = [[0] * (len(b) + 1) for _ in range(len(a) + 1)]
    for i in range(len(a) + 1):
        dp[i][0] = i                          # delete everything
    for j in range(len(b) + 1):
        dp[0][j] = j                          # insert everything
    for i in range(1, len(a) + 1):
        for j in range(1, len(b) + 1):
            if a[i - 1] == b[j - 1]:
                dp[i][j] = dp[i - 1][j - 1]
            else:
                dp[i][j] = 1 + min(dp[i - 1][j - 1],   # replace
                                   dp[i - 1][j],       # delete
                                   dp[i][j - 1])       # insert
    return dp[-1][-1]

print(min_distance("horse", "ros"))            # 3
print(min_distance("intention", "execution"))  # 5
print(min_distance("", "abc"))                 # 3
print(min_distance("same", "same"))            # 0
```

## Complexity

- **Time O(m × n)**, **space O(m × n)**, or O(n) keeping just two rows.

## Interview tip

Say the meaning of one cell out loud before coding: "dp[i][j] is the cheapest way to turn the first i letters of word1 into the first j letters of word2." Then the three moves explain themselves, and the interviewer sees you truly understand it.

## Similar problems

Longest Common Subsequence, One Edit Distance, Delete Operation for Two Strings, Minimum ASCII Delete Sum, Regular Expression Matching.

Learn the pattern: [2D Dynamic Programming](/tutorials/dsa-dp-2d/).
