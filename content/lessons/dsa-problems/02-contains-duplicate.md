---
title: Contains Duplicate
slug: problem-contains-duplicate
subject: dsa-problems
lesson: 2
difficulty: beginner
level: Easy
pattern: Hashing
companies: Amazon, Apple, Microsoft, Adobe, Yahoo
excerpt: Does any value appear twice? Three solutions from slow to fast, and why a set wins.
---

A perfect first problem: three different solutions, three different complexities.

## The problem

Return `True` if any value appears **at least twice** in `nums`, otherwise `False`.

## Examples

| nums | answer |
|---|---|
| `[1, 2, 3, 1]` | True |
| `[1, 2, 3, 4]` | False |
| `[]` | False |

## Hints

::hint Hint 1 | Comparing every pair works but is O(n²). Can sorting help bring duplicates next to each other?
::hint Hint 2 | A set answers "have I seen this?" in O(1).

## Solution 1: compare every pair, O(n²)

```python
def has_dup_pairs(nums):
    return any(nums[i] == nums[j] for i in range(len(nums)) for j in range(i + 1, len(nums)))

print(has_dup_pairs([1, 2, 3, 1]))  # True
```

## Solution 2: sort, then check neighbours, O(n log n)

```python
def has_dup_sorted(nums):
    nums = sorted(nums)
    return any(nums[i] == nums[i - 1] for i in range(1, len(nums)))

print(has_dup_sorted([1, 2, 3, 4]))  # False
```

## Solution 3: a set, O(n)

Like a bouncer with a guest list: if your name is already on it, you've been here before.

```python
def contains_duplicate(nums):
    seen = set()
    for x in nums:
        if x in seen:
            return True
        seen.add(x)
    return False

print(contains_duplicate([1, 2, 3, 1]))  # True
print(contains_duplicate([1, 2, 3, 4]))  # False
print(contains_duplicate([]))            # False
```

A one-line version: `len(set(nums)) != len(nums)`. It's neat, but the loop stops early as soon as it finds a duplicate.

## Complexity

| Solution | Time | Extra space |
|---|---|---|
| All pairs | O(n²) | O(1) |
| Sort | O(n log n) | O(1) to O(n) |
| Set | O(n) | O(n) |

Mentioning this trade-off (time vs memory) is exactly what interviewers want to hear.

## Walkthrough: the set solution step by step

Take `nums = [4, 1, 7, 1]` and follow the loop:

| step | x | seen before the check | x in seen? | action |
|---|---|---|---|---|
| 1 | 4 | `{}` | no | add 4 |
| 2 | 1 | `{4}` | no | add 1 |
| 3 | 7 | `{4, 1}` | no | add 7 |
| 4 | 1 | `{4, 1, 7}` | **yes** | return True |

The loop stopped at the fourth item. On a list of a million numbers where the duplicate sits near the start, the loop version finishes almost instantly, while `len(set(nums))` always builds the whole set first.

## Common mistakes

- **Using a list instead of a set** for `seen`. `x in some_list` scans the whole list, so the "fast" solution quietly becomes O(n²) again.
- **Sorting in place** with `nums.sort()` when the caller still needs the original order. `sorted(nums)` makes a copy; mention which one you chose and why.
- **Forgetting the empty list.** No values means no duplicates, so the answer is `False`. All three solutions above already handle it.

## Follow-ups interviewers ask

- *What if memory is very limited?* Use the sorting solution: it needs no extra set, at the cost of O(n log n) time.
- *What if duplicates only count when they are at most k positions apart?* Keep a set of just the last k values, a sliding window. That is Contains Duplicate II.
- *What if the numbers are in the range 1 to n?* You can mark visited values inside the list itself and use O(1) extra space.

## Similar problems

Contains Duplicate II (within k positions), Valid Anagram, Single Number, Find the Duplicate Number.

Learn the pattern: [Hashing](/tutorials/dsa-hashing/).
