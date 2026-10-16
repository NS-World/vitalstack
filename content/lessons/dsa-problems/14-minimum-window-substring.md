---
title: Minimum Window Substring
slug: problem-minimum-window-substring
subject: dsa-problems
lesson: 14
difficulty: advanced
level: Hard
pattern: Sliding Window
companies: Meta, Google, Amazon, LinkedIn, Uber, Airbnb, Microsoft
excerpt: Find the smallest part of a string that contains every letter of another string. Expand until valid, then shrink while valid.
---

The "hard" sliding window: **expand until the window works, then shrink it as much as possible.**

## The problem

Given strings `s` and `t`, return the shortest substring of `s` that contains every character of `t` (including duplicates). Return `""` if there is none.

## Examples

| s | t | answer |
|---|---|---|
| `"ADOBECODEBANC"` | `"ABC"` | `"BANC"` |
| `"a"` | `"a"` | `"a"` |
| `"a"` | `"aa"` | `""` |

## Hints

::hint Hint 1 | Count what you NEED from t with a dictionary.
::hint Hint 2 | Move the right edge until the window has everything. Then move the left edge to shrink while it still has everything, recording the best.
::hint Hint 3 | Keep a counter `missing` = how many required characters are still missing, so checking "valid?" is O(1).

## The easy idea: shopping list

You have a shopping list (t). Walk along a street of shops (s), adding items to your basket (expand right). The moment the basket has everything, try returning items from the start of your trip that you don't need (shrink left), as long as the basket stays complete. Each complete basket is a candidate; keep the shortest.

## Solution

```python
from collections import Counter

def min_window(s, t):
    need = Counter(t)
    missing = len(t)                 # characters still needed
    left = 0
    best_start, best_len = 0, float("inf")
    for right, ch in enumerate(s):
        if need[ch] > 0:
            missing -= 1             # this character was useful
        need[ch] -= 1
        while missing == 0:          # window is valid: try to shrink
            if right - left + 1 < best_len:
                best_start, best_len = left, right - left + 1
            need[s[left]] += 1
            if need[s[left]] > 0:
                missing += 1         # we just dropped something required
            left += 1
    return "" if best_len == float("inf") else s[best_start:best_start + best_len]

print(min_window("ADOBECODEBANC", "ABC"))  # BANC
print(min_window("a", "a"))                # a
print(min_window("a", "aa") == "")         # True
```

`need[ch]` can go **negative**: that means the window has extra copies we could give away.

## Complexity

- **Time O(len(s) + len(t)):** both edges only move forward.
- **Space O(k)** for the character counts.

## Template to remember

```text
for right in range(n):
    add s[right]
    while window is valid:
        record answer
        remove s[left]; left += 1
```

Swap "while valid" for "while invalid" and you get the template for "longest window" problems.

## Similar problems

Longest Substring Without Repeating Characters, Permutation in String, Find All Anagrams in a String, Substring with Concatenation of All Words.

Learn the pattern: [Sliding Window](/tutorials/dsa-sliding-window/).
