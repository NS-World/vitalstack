---
title: Valid Palindrome
slug: problem-valid-palindrome
subject: dsa-problems
lesson: 8
difficulty: beginner
level: Easy
pattern: Two Pointers
companies: Meta, Amazon, Microsoft, Apple, Spotify
excerpt: Does a sentence read the same backwards, ignoring spaces and punctuation? Two pointers walking towards each other.
---

"A man, a plan, a canal: Panama" reads the same backwards once you drop everything except letters and digits.

## The problem

Return `True` if `s` is a palindrome after converting to lowercase and keeping only letters and digits.

## Examples

| s | answer |
|---|---|
| `"A man, a plan, a canal: Panama"` | True |
| `"race a car"` | False |
| `" "` | True |

## Hints

::hint Hint 1 | Simplest: clean the string, then compare it with its reverse.
::hint Hint 2 | For O(1) extra space, put one pointer at each end and move them inwards, skipping characters that aren't letters or digits.

## Solution 1: clean and reverse

```python
def is_palindrome_simple(s):
    clean = [ch.lower() for ch in s if ch.isalnum()]
    return clean == clean[::-1]

print(is_palindrome_simple("A man, a plan, a canal: Panama"))  # True
```

O(n) time but O(n) extra space for the cleaned copy.

## The easy idea: two people walking towards each other

One person starts at the left end, one at the right. Each skips over spaces and punctuation, then both compare their letters. If they ever differ, it's not a palindrome. When they meet in the middle, it is.

## Solution 2: two pointers, O(1) space

```python
def is_palindrome(s):
    left, right = 0, len(s) - 1
    while left < right:
        while left < right and not s[left].isalnum():
            left += 1
        while left < right and not s[right].isalnum():
            right -= 1
        if s[left].lower() != s[right].lower():
            return False
        left += 1
        right -= 1
    return True

print(is_palindrome("A man, a plan, a canal: Panama"))  # True
print(is_palindrome("race a car"))                      # False
print(is_palindrome(" "))                               # True
```

## Complexity

- **Time O(n):** each pointer moves across the string once.
- **Space O(1).**

## Follow-up: Valid Palindrome II

"Can you make it a palindrome by deleting **at most one** character?" When the pointers disagree, try skipping the left character **or** the right one, and check the rest:

```python
def valid_palindrome_ii(s):
    def check(lo, hi):
        return s[lo:hi + 1] == s[lo:hi + 1][::-1]
    lo, hi = 0, len(s) - 1
    while lo < hi:
        if s[lo] != s[hi]:
            return check(lo + 1, hi) or check(lo, hi - 1)
        lo += 1
        hi -= 1
    return True

print(valid_palindrome_ii("abca"))  # True
print(valid_palindrome_ii("abc"))   # False
```

## Similar problems

Valid Palindrome II, Palindrome Linked List, Longest Palindromic Substring, Reverse String.

Learn the pattern: [Two Pointers](/tutorials/dsa-two-pointers/).
