---
title: Valid Anagram
slug: problem-valid-anagram
subject: dsa-problems
lesson: 3
difficulty: beginner
level: Easy
pattern: Hashing
companies: Amazon, Google, Microsoft, Bloomberg, Uber
excerpt: Are two words made of exactly the same letters? Count letters with a dictionary or Counter.
---

"listen" and "silent" use the same letters, just shuffled. That's an **anagram**.

## The problem

Given strings `s` and `t`, return `True` if `t` is an anagram of `s`.

## Examples

| s | t | answer |
|---|---|---|
| `"anagram"` | `"nagaram"` | True |
| `"rat"` | `"car"` | False |
| `"ab"` | `"a"` | False |

## Hints

::hint Hint 1 | Two words are anagrams when every letter appears the same number of times in both.
::hint Hint 2 | Count letters with a dictionary. Or sort both words and compare.

## Solution 1: sort both

```python
def is_anagram_sorted(s, t):
    return sorted(s) == sorted(t)

print(is_anagram_sorted("listen", "silent"))  # True
```

O(n log n) because of sorting. Simple and perfectly fine to mention first.

## Solution 2: count letters, O(n)

Imagine two bags of Scrabble tiles. Count each letter in the first bag, then remove letters for the second bag. If every count ends at zero, the bags match.

```python
def is_anagram(s, t):
    if len(s) != len(t):
        return False
    count = {}
    for ch in s:
        count[ch] = count.get(ch, 0) + 1
    for ch in t:
        if count.get(ch, 0) == 0:
            return False
        count[ch] -= 1
    return True

print(is_anagram("anagram", "nagaram"))  # True
print(is_anagram("rat", "car"))          # False
print(is_anagram("ab", "a"))             # False
```

With Python's `Counter` it's one line:

```python
from collections import Counter

print(Counter("anagram") == Counter("nagaram"))  # True
```

## Complexity

- **Time O(n)** for counting.
- **Space O(1)** for lowercase English (at most 26 keys), O(k) for k different characters in general.

## Walkthrough: counting "rat" vs "car"

Both words have three letters, so the length check passes. After counting `s = "rat"` the dictionary is `{'r': 1, 'a': 1, 't': 1}`. Now we walk through `t = "car"`:

| letter in t | count before | action |
|---|---|---|
| c | 0 (missing) | return False |

The very first letter of `t` is not in `s` at all, so we stop. For `"anagram"` and `"nagaram"` every letter finds a matching count, every count drops back to zero, and the function returns `True`.

## Why check the length first?

If the lengths differ, the words can't be anagrams, and we skip all the counting. It also protects the second loop: without it, `"ab"` and `"a"` would pass because every letter of `"a"` is found in `"ab"`, even though the extra `"b"` is never used.

## Common mistakes

- **Comparing sets** with `set(s) == set(t)`. Sets forget how many times a letter appears, so `"aab"` and `"abb"` would wrongly look like anagrams.
- **Ignoring case and spaces** when the question includes them. Ask first: is `"Dormitory"` an anagram of `"dirty room"`? If yes, lowercase both words and remove spaces before counting.
- **Assuming only a to z.** With Unicode input (emojis, accented letters), a fixed array of 26 counts breaks, but a dictionary or `Counter` still works.

## Follow-ups interviewers ask

- *What if the inputs contain Unicode characters?* Use a dictionary instead of a 26-slot list.
- *Can you do it with one dictionary pass?* Yes: add 1 for each letter of `s` and subtract 1 for each letter of `t` in the same loop, then check every count is zero.

## Similar problems

Group Anagrams, Find All Anagrams in a String, Ransom Note, Permutation in String.

Learn the pattern: [Hashing](/tutorials/dsa-hashing/).
