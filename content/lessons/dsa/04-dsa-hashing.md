---
title: "Hashing: Hash Maps and Sets"
slug: dsa-hashing
subject: dsa
lesson: 4
difficulty: beginner
excerpt: The most useful tool in DSA. How hash maps and sets give O(1) lookups, and the patterns they unlock: seen-before checks, counting, Two Sum, anagram groups and more.
---

If you learn only one data structure for interviews, make it the **hash map** (a Python `dict`) and its sibling the **hash set** (`set`). They answer "have I seen this before?" and "how many times?" in **O(1)** on average, which turns many slow O(n²) solutions into fast O(n) ones.

## The cloakroom analogy

At a wedding hall cloakroom, you hand over your bag and get **token number 47**. Later, the attendant doesn't search every shelf: they go straight to **shelf 47**. That's hashing:

1. A **hash function** turns a key (like `"apple"`) into a number.
2. That number picks a **bucket** (a shelf) in an internal array.
3. Looking up the key repeats the same calculation and jumps straight to the right bucket.

No searching, so lookup, insert and delete are all **O(1) on average**.

```text
"apple"  --hash-->  3  --> bucket 3: ("apple", 120)
"banana" --hash-->  7  --> bucket 7: ("banana", 40)
```

Sometimes two keys land in the same bucket (a **collision**). Good implementations handle this so well that, on average, operations stay O(1). In the rare worst case they degrade to O(n), which is why we say "average".

## Set vs map

| | `set` | `dict` |
|---|---|---|
| Stores | Unique values | Key → value pairs |
| Question it answers | "Is x here?" | "What is stored for x?" |
| Example | `seen = {3, 8, 11}` | `count = {"a": 3, "b": 1}` |

Keys must be **immutable** (numbers, strings, tuples). Lists can't be keys.

## Pattern 1: "Have I seen this before?"

### Contains duplicate

```python
def contains_duplicate(nums):
    seen = set()
    for x in nums:
        if x in seen:        # O(1) check
            return True
        seen.add(x)
    return False

print(contains_duplicate([4, 1, 7, 1]))  # True
print(contains_duplicate([4, 1, 7]))     # False
```

Brute force compares every pair: O(n²). With a set: **O(n) time, O(n) space**. This trade (more memory for much more speed) is the heart of hashing.

## Pattern 2: Store what you need, look up what you're missing

### Two Sum (the most famous interview problem)

Given a list and a target, return the indexes of two numbers that add up to the target.

Brute force tries every pair: O(n²). The hashing insight: for each number x, the partner we need is `target - x`. Remember every number we have seen (with its index) and **look up the missing partner**.

::viz two-sum {"arr": [2, 7, 11, 15, 3, 6], "target": 9}

```python
def two_sum(nums, target):
    seen = {}                      # value -> index
    for i, x in enumerate(nums):
        need = target - x
        if need in seen:
            return [seen[need], i]
        seen[x] = i
    return []

print(two_sum([2, 7, 11, 15], 9))   # [0, 1]
print(two_sum([3, 2, 4], 6))        # [1, 2]
print(two_sum([1, 2], 7))           # []
```

Why check **before** storing? So a number can't pair with itself: in `[3, 2, 4]` with target 6, the first 3 must not match itself.

## Pattern 3: Counting

### Valid anagram

Two words are anagrams if they use the same letters the same number of times.

```python
from collections import Counter

def is_anagram(a, b):
    return Counter(a) == Counter(b)

print(is_anagram("listen", "silent"))  # True
print(is_anagram("chai", "chat"))      # False
```

`Counter` is a dictionary that counts things. By hand it looks like this:

```python
def count_letters(word):
    counts = {}
    for ch in word:
        counts[ch] = counts.get(ch, 0) + 1
    return counts

print(count_letters("letter"))  # {'l': 1, 'e': 2, 't': 2, 'r': 1}
```

### First unique character

```python
from collections import Counter

def first_unique(s):
    counts = Counter(s)
    for i, ch in enumerate(s):
        if counts[ch] == 1:
            return i
    return -1

print(first_unique("leetcode"))      # 0
print(first_unique("loveleetcode"))  # 2
print(first_unique("aabb"))          # -1
```

Two passes, both O(n): count first, then find.

### Top k frequent elements

```python
from collections import Counter

def top_k_frequent(nums, k):
    return [x for x, _ in Counter(nums).most_common(k)]

print(top_k_frequent([1, 1, 1, 2, 2, 3], 2))  # [1, 2]
```

## Pattern 4: Grouping by a key

### Group anagrams

All anagrams share the same **sorted** letters. Use that as the dictionary key:

```python
from collections import defaultdict

def group_anagrams(words):
    groups = defaultdict(list)
    for w in words:
        groups["".join(sorted(w))].append(w)
    return list(groups.values())

print(group_anagrams(["eat", "tea", "tan", "ate", "nat", "bat"]))
# [['eat', 'tea', 'ate'], ['tan', 'nat'], ['bat']]
```

Choosing the right **key** is the creative part of grouping problems.

## Pattern 5: Longest consecutive sequence

Find the length of the longest run of consecutive numbers (in any order) in O(n): `[100, 4, 200, 1, 3, 2]` → 4 (1, 2, 3, 4).

```python
def longest_consecutive(nums):
    s = set(nums)
    best = 0
    for x in s:
        if x - 1 not in s:          # x starts a run
            length = 1
            while x + length in s:
                length += 1
            best = max(best, length)
    return best

print(longest_consecutive([100, 4, 200, 1, 3, 2]))  # 4
```

The trick "only start counting at the beginning of a run" means each number is visited a constant number of times: O(n).

## When to reach for a hash map

- "Have I seen…?", "Is there a pair…?", "How many times…?"
- You need to find something by **value**, not by position.
- A brute-force solution has an inner loop that **searches** for something.

::tip Whenever you write a nested loop where the inner loop searches for something, ask: "Could a set or dictionary find it in O(1) instead?"

## Interview corner

Two Sum is famously one of the most reported interview questions anywhere; **Amazon, Google, Microsoft, Meta, Apple and Adobe** all use hashing problems like Group Anagrams, Top K Frequent and Longest Consecutive Sequence. Interviewers want to hear you say the trade-off: "This uses O(n) extra space to get O(n) time."

## Common mistakes

- **Pairing a number with itself** in Two Sum (store after checking).
- **Using a list for membership** (`x in list` is O(n)).
- **Using mutable keys** like lists: convert to a tuple or string.
- **Forgetting the O(n) space cost** when stating complexity.

## Exercise

1. Write `intersection(a, b)` returning the unique numbers in both lists.
2. Write `is_isomorphic(s, t)`: can letters in s be replaced to get t, consistently? ("egg", "add" → True; "foo", "bar" → False.)
3. Write `subarray_count(nums, k)` that counts pairs `i < j` with `nums[i] + nums[j] == k` in O(n). Hint: count how many times you've seen each value.

## Summary

- Hash maps and sets give **O(1) average** lookup, insert and delete.
- Patterns: seen-before, store-and-look-up (Two Sum), counting, grouping by a key.
- They trade O(n) memory for big speed gains.
- Reach for them whenever an inner loop is searching.

Next, you'll learn **two pointers**, the pattern for sorted arrays and pairs that needs no extra memory at all.
