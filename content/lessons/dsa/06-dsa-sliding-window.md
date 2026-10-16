---
title: Sliding Window
slug: dsa-sliding-window
subject: dsa
lesson: 6
difficulty: intermediate
excerpt: Solve "best contiguous subarray or substring" problems in O(n). Fixed-size windows, growing and shrinking windows, and the template behind longest substring, minimum window and more.
---

Many problems ask about a **contiguous** part of an array or string: "the best 3 days in a row", "the longest substring without repeats", "the shortest subarray with sum ≥ 7". Checking every possible part is O(n²) or worse. The **sliding window** pattern does it in **O(n)**.

## The train window analogy

You are on a train looking through a window that shows exactly 3 houses at a time. When the train moves forward, one new house **appears** on the right and one house **disappears** on the left. You don't re-look at the middle houses; you only notice what changed.

That's the whole idea: when the window slides, update your answer using **only the item that entered and the item that left**.

::viz sliding-window {"arr": [2, 1, 5, 1, 3, 2, 7, 1], "k": 3}

## Type 1: Fixed-size window

### Maximum sum of k consecutive numbers

Brute force recalculates each window from scratch: O(n·k). Sliding window adds the new item and subtracts the old one: O(n).

```python
def max_sum_k(arr, k):
    window = sum(arr[:k])
    best = window
    for right in range(k, len(arr)):
        window += arr[right] - arr[right - k]   # one in, one out
        best = max(best, window)
    return best

print(max_sum_k([2, 1, 5, 1, 3, 2, 7, 1], 3))  # 12
```

### Average of every window of size k

```python
def window_averages(arr, k):
    out, window = [], 0
    for i, x in enumerate(arr):
        window += x
        if i >= k:
            window -= arr[i - k]
        if i >= k - 1:
            out.append(window / k)
    return out

print(window_averages([1, 3, 2, 6, 4], 2))  # [2.0, 2.5, 4.0, 5.0]
```

## Type 2: Variable-size window (grow and shrink)

Here the window size changes. The template:

1. Move `right` forward one step at a time, **adding** the new item to the window.
2. While the window **breaks a rule**, move `left` forward, **removing** items, until the rule holds again.
3. Update the answer.

Each item enters once and leaves at most once, so the total work is O(n) even with the inner `while`.

### Longest substring without repeating characters

::viz longest-unique {"s": "abcabcbb"}

```python
def longest_unique(s):
    seen = set()
    left = best = 0
    for right in range(len(s)):
        while s[right] in seen:        # rule broken: a repeat
            seen.remove(s[left])
            left += 1
        seen.add(s[right])
        best = max(best, right - left + 1)
    return best

print(longest_unique("abcabcbb"))  # 3
print(longest_unique("bbbbb"))     # 1
print(longest_unique("pwwkew"))    # 3
print(longest_unique(""))          # 0
```

### Shortest subarray with sum at least target

Positive numbers only. Find the length of the shortest contiguous subarray with sum ≥ target.

```python
def min_subarray_len(target, nums):
    left = total = 0
    best = float("inf")
    for right, x in enumerate(nums):
        total += x
        while total >= target:         # valid: try to shrink
            best = min(best, right - left + 1)
            total -= nums[left]
            left += 1
    return 0 if best == float("inf") else best

print(min_subarray_len(7, [2, 3, 1, 2, 4, 3]))  # 2
print(min_subarray_len(100, [1, 2, 3]))         # 0
```

Notice the difference: for "longest" problems we shrink while the window is **invalid**; for "shortest" problems we shrink while the window is **valid**, recording the answer as we go.

### Longest substring with at most k distinct characters

```python
from collections import defaultdict

def longest_k_distinct(s, k):
    counts = defaultdict(int)
    left = best = 0
    for right, ch in enumerate(s):
        counts[ch] += 1
        while len(counts) > k:
            counts[s[left]] -= 1
            if counts[s[left]] == 0:
                del counts[s[left]]
            left += 1
        best = max(best, right - left + 1)
    return best

print(longest_k_distinct("eceba", 2))   # 3
print(longest_k_distinct("aabbcc", 1))  # 2
```

A dictionary of counts is the window's "memory" in most string window problems.

## Interview level: permutation in a string

Does `s2` contain any rearrangement of `s1` as a substring? Use a **fixed** window of size `len(s1)` and compare letter counts.

```python
from collections import Counter

def check_inclusion(s1, s2):
    k = len(s1)
    need = Counter(s1)
    window = Counter(s2[:k])
    if window == need:
        return True
    for right in range(k, len(s2)):
        window[s2[right]] += 1
        window[s2[right - k]] -= 1
        if window[s2[right - k]] == 0:
            del window[s2[right - k]]
        if window == need:
            return True
    return False

print(check_inclusion("ab", "eidbaooo"))  # True
print(check_inclusion("ab", "eidboaoo"))  # False
```

Comparing two counters costs at most 26 steps for lowercase letters, so the total is still O(n).

## How to recognise the pattern

- The words **contiguous**, **subarray**, **substring**, **consecutive** or **window**.
- "Longest", "shortest", "maximum sum", "at most k", "contains all of…".
- Brute force would check every start and end position.

::tip Sliding window needs a rule you can maintain incrementally. If adding and removing one item can update your "window memory" (a sum, a count, a set), the pattern fits.

::hint When it does NOT work | With negative numbers, "shrink while the sum is too big" breaks, because removing an item could make the sum bigger. Then use prefix sums with a hash map (next lesson).

## Interview corner

Longest Substring Without Repeating Characters, Minimum Size Subarray Sum, Permutation in String, Maximum Average Subarray and Minimum Window Substring are frequently reported at **Amazon, Google, Microsoft, Meta, Uber and Flipkart**. Interviewers expect you to explain why the solution is O(n) despite the nested `while`.

## Common mistakes

- **Recomputing the whole window** each step (that's brute force in disguise).
- **Off-by-one in window length**: it's `right - left + 1`.
- **Forgetting to remove zero counts** from the dictionary, so `len(counts)` is wrong.
- **Using a shrinking window with negative numbers.**

## Exercise

1. In the visualizer, try `k = 1` and `k = 8`. What happens?
2. Write `max_vowels(s, k)`: the maximum number of vowels in any substring of length k.
3. Write `longest_ones(nums, k)`: the longest run of 1s if you may flip at most k zeros. (`[1,1,0,0,1,1,1,0,1]`, k = 1 → 5.)

## Summary

- Sliding window solves contiguous subarray/substring problems in O(n).
- Fixed windows add the new item and remove the old one.
- Variable windows grow with `right` and shrink with `left` while a rule is broken (longest) or kept (shortest).
- A sum, set or count dictionary is the window's memory.

Next, you'll learn **prefix sums**, which answer any range-sum question in O(1).
