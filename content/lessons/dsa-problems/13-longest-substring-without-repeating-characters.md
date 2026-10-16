---
title: Longest Substring Without Repeating Characters
slug: problem-longest-substring-without-repeating
subject: dsa-problems
lesson: 13
difficulty: intermediate
level: Medium
pattern: Sliding Window
companies: Amazon, Google, Meta, Microsoft, Bloomberg, Adobe, Apple
excerpt: The textbook sliding window problem. Grow the window to the right; when a letter repeats, shrink from the left.
---

If you master this problem, you understand sliding windows.

## The problem

Return the length of the longest **substring** (continuous part) of `s` with no repeated characters.

## Examples

| s | answer | the substring |
|---|---|---|
| `"abcabcbb"` | 3 | "abc" |
| `"bbbbb"` | 1 | "b" |
| `"pwwkew"` | 3 | "wke" |
| `""` | 0 | |

## Hints

::hint Hint 1 | Checking every substring is O(n³) or O(n²). Instead, keep one window that is always valid.
::hint Hint 2 | Move the right edge forward one letter at a time. If that letter is already in the window, move the left edge past its previous position.
::hint Hint 3 | A dictionary storing each letter's last index lets the left edge jump in one step.

## Brute force, O(n²)

```python
def longest_unique_slow(s):
    best = 0
    for i in range(len(s)):
        seen = set()
        for j in range(i, len(s)):
            if s[j] in seen:
                break
            seen.add(s[j])
            best = max(best, j - i + 1)
    return best

print(longest_unique_slow("abcabcbb"))  # 3
```

## The easy idea: a caterpillar

The window is a caterpillar crawling along the string. Its head eats the next letter. If that letter is already inside the caterpillar, its tail moves forward until the old copy is gone. Record the longest the caterpillar ever gets. Press play:

::viz longest-unique {"s": "abcabcbb"}

## Solution

```python
def length_of_longest_substring(s):
    last = {}                        # letter -> last index seen
    left = best = 0
    for right, ch in enumerate(s):
        if ch in last and last[ch] >= left:
            left = last[ch] + 1      # jump past the old copy
        last[ch] = right
        best = max(best, right - left + 1)
    return best

print(length_of_longest_substring("abcabcbb"))  # 3
print(length_of_longest_substring("bbbbb"))     # 1
print(length_of_longest_substring("pwwkew"))    # 3
print(length_of_longest_substring(""))          # 0
print(length_of_longest_substring("abba"))      # 2
```

The `last[ch] >= left` check matters: in "abba", when the second `a` arrives, the old `a` is already outside the window, so the left edge must not move backwards.

## Complexity

- **Time O(n):** each index is visited once by `right`; `left` only moves forward.
- **Space O(k)** for k different characters.

## Similar problems

Longest Repeating Character Replacement, Minimum Window Substring, Permutation in String, Fruit Into Baskets.

Learn the pattern: [Sliding Window](/tutorials/dsa-sliding-window/).
