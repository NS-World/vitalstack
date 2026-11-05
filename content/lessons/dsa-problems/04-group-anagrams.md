---
title: Group Anagrams
slug: problem-group-anagrams
subject: dsa-problems
lesson: 4
difficulty: intermediate
level: Medium
pattern: Hashing
companies: Amazon, Meta, Google, Microsoft, Uber, Goldman Sachs
excerpt: Put words with the same letters into groups. The trick is choosing a good dictionary key.
---

The secret to many hash map problems is choosing the right **key**. Here the key is "the letters of the word, sorted".

## The problem

Given a list of words, group the anagrams together. Any order of groups is fine.

## Example

`["eat", "tea", "tan", "ate", "nat", "bat"]` → `[["eat", "tea", "ate"], ["tan", "nat"], ["bat"]]`

## Hints

::hint Hint 1 | All anagrams look identical once their letters are sorted: "eat", "tea" and "ate" all become "aet".
::hint Hint 2 | Use that sorted form as a dictionary key; the value is the list of words in the group.

## Brute force

Compare every word with every group using an anagram check: O(n² · k). Too slow for big lists.

## The easy idea: a label for every group

Think of sorting mail into pigeonholes. Each word gets a label (its sorted letters), and goes into the pigeonhole with that label.

| word | label |
|---|---|
| eat | aet |
| tea | aet |
| tan | ant |
| ate | aet |
| nat | ant |
| bat | abt |

## Solution

```python
from collections import defaultdict

def group_anagrams(words):
    groups = defaultdict(list)
    for w in words:
        key = "".join(sorted(w))
        groups[key].append(w)
    return list(groups.values())

print(group_anagrams(["eat", "tea", "tan", "ate", "nat", "bat"]))  # [['eat', 'tea', 'ate'], ['tan', 'nat'], ['bat']]
print(group_anagrams([""]))                                       # [['']]
```

### Faster key: letter counts

Sorting each word is O(k log k). A tuple of 26 counts is O(k):

```python
from collections import defaultdict

def group_anagrams_counts(words):
    groups = defaultdict(list)
    for w in words:
        counts = [0] * 26
        for ch in w:
            counts[ord(ch) - ord("a")] += 1
        groups[tuple(counts)].append(w)       # lists can't be keys; tuples can
    return list(groups.values())

print(group_anagrams_counts(["abc", "bca", "xyz"]))  # [['abc', 'bca'], ['xyz']]
```

## Complexity

- Sorted key: **O(n · k log k)** time for n words of length k.
- Count key: **O(n · k)** time.
- Space **O(n · k)** to store the groups.

## Walkthrough: building the groups

Follow the dictionary as each word arrives:

| word | key | groups after this word |
|---|---|---|
| eat | aet | `{aet: [eat]}` |
| tea | aet | `{aet: [eat, tea]}` |
| tan | ant | `{aet: [eat, tea], ant: [tan]}` |
| ate | aet | `{aet: [eat, tea, ate], ant: [tan]}` |
| nat | ant | `{aet: [eat, tea, ate], ant: [tan, nat]}` |
| bat | abt | `{aet: [...], ant: [...], abt: [bat]}` |

Every word is touched once, and the dictionary does the grouping for us. `defaultdict(list)` creates an empty list the first time a key appears, so we never need an `if key not in groups` check.

## Common mistakes

- **Using a list as a key.** `sorted(w)` returns a list, and lists can't be dictionary keys because they can change. Join it into a string or convert it to a tuple.
- **Using `set(w)` as the key.** It loses repeated letters, so `"aab"` and `"ab"` would land in the same group.
- **Returning the dictionary itself** instead of `list(groups.values())`. The question asks for a list of groups, not the labels.

## Follow-ups interviewers ask

- *Which key is better, sorted string or counts?* For short words the sorted string is simpler and fast enough. For long words the 26-count tuple wins because it avoids sorting.
- *How would you return the groups sorted by size?* `sorted(groups.values(), key=len, reverse=True)`.
- *What if the input has uppercase letters?* Lowercase each word for the key, but store the original word in the group.

## Similar problems

Valid Anagram, Group Shifted Strings, Find All Anagrams in a String.

Learn the pattern: [Hashing](/tutorials/dsa-hashing/).
