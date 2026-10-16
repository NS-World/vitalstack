---
title: Longest Common Subsequence
slug: problem-longest-common-subsequence
subject: dsa-problems
lesson: 47
difficulty: intermediate
level: Medium
pattern: Dynamic Programming
companies: Amazon, Google, Microsoft, Meta, Bloomberg, Adobe
excerpt: The longest sequence of letters two strings share in order. The doorway into 2D dynamic programming, with an animated table.
---

The gateway to **2D DP**: two strings, two indexes, one table.

## The problem

Return the length of the longest **common subsequence** of `text1` and `text2`: letters that appear in both, in the same order, not necessarily next to each other.

## Examples

| text1 | text2 | answer | LCS |
|---|---|---|---|
| `"abcde"` | `"ace"` | 3 | "ace" |
| `"abc"` | `"abc"` | 3 | "abc" |
| `"abc"` | `"def"` | 0 | |

## Hints

::hint Hint 1 | Let dp[i][j] = LCS of the first i letters of text1 and the first j letters of text2.
::hint Hint 2 | If the last letters match, dp[i][j] = dp[i−1][j−1] + 1.
::hint Hint 3 | If not, drop one letter from either string: dp[i][j] = max(dp[i−1][j], dp[i][j−1]).

## The easy idea: a grid of small answers

Write text1 down the side and text2 across the top, with an empty row and column for "no letters yet" (all zeros). Fill each cell from its neighbours:

- Letters match → take the **diagonal** cell and add 1 (both strings used that letter).
- No match → copy the **bigger** of the cell above and the cell to the left.

The bottom-right cell is the answer. Watch it fill:

::viz lcs {"a": "ABCDE", "b": "ACE"}

## Solution

```python
def longest_common_subsequence(a, b):
    dp = [[0] * (len(b) + 1) for _ in range(len(a) + 1)]
    for i in range(1, len(a) + 1):
        for j in range(1, len(b) + 1):
            if a[i - 1] == b[j - 1]:
                dp[i][j] = dp[i - 1][j - 1] + 1
            else:
                dp[i][j] = max(dp[i - 1][j], dp[i][j - 1])
    return dp[-1][-1]

print(longest_common_subsequence("abcde", "ace"))  # 3
print(longest_common_subsequence("abc", "abc"))    # 3
print(longest_common_subsequence("abc", "def"))    # 0
```

## Complexity

- **Time O(m × n)**, **space O(m × n)**, reducible to O(min(m, n)) by keeping only the previous row.

## Follow-up: print the subsequence

Walk back from the bottom-right corner: on a match go diagonally (and record the letter), otherwise move towards the bigger neighbour.

```python
def lcs_string(a, b):
    dp = [[0] * (len(b) + 1) for _ in range(len(a) + 1)]
    for i in range(1, len(a) + 1):
        for j in range(1, len(b) + 1):
            dp[i][j] = dp[i-1][j-1] + 1 if a[i-1] == b[j-1] else max(dp[i-1][j], dp[i][j-1])
    out, i, j = [], len(a), len(b)
    while i and j:
        if a[i - 1] == b[j - 1]:
            out.append(a[i - 1]); i -= 1; j -= 1
        elif dp[i - 1][j] >= dp[i][j - 1]:
            i -= 1
        else:
            j -= 1
    return "".join(reversed(out))

print(lcs_string("abcde", "ace"))  # ace
```

## Similar problems

Edit Distance, Shortest Common Supersequence, Delete Operation for Two Strings, Longest Palindromic Subsequence, Uncrossed Lines.

Learn the pattern: [2D Dynamic Programming](/tutorials/dsa-dp-2d/).
