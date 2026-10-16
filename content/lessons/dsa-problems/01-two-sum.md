---
title: Two Sum
slug: problem-two-sum
subject: dsa-problems
lesson: 1
difficulty: beginner
level: Easy
pattern: Hashing
companies: Amazon, Google, Meta, Microsoft, Apple, Adobe
excerpt: Find two numbers that add up to a target. The most famous interview warm-up, solved in one pass with a hash map.
---

The most famous interview question of all. If you learn one hash map trick, learn this one.

## The problem

Given a list of numbers `nums` and a number `target`, return the **indexes** of the two numbers that add up to `target`. Exactly one answer exists, and you can't use the same item twice.

## Examples

| nums | target | answer | why |
|---|---|---|---|
| `[2, 7, 11, 15]` | 9 | `[0, 1]` | 2 + 7 = 9 |
| `[3, 2, 4]` | 6 | `[1, 2]` | 2 + 4 = 6 |
| `[3, 3]` | 6 | `[0, 1]` | 3 + 3 = 6 |

## Hints

::hint Hint 1 | For each number x, which other number would you need? Write it down: `target - x`.
::hint Hint 2 | How can you check "have I already seen `target - x`?" in O(1) instead of scanning the list again?
::hint Hint 3 | A dictionary from value to index. Check first, then store.

## Brute force: try every pair

```python
def two_sum_slow(nums, target):
    for i in range(len(nums)):
        for j in range(i + 1, len(nums)):
            if nums[i] + nums[j] == target:
                return [i, j]
    return []

print(two_sum_slow([2, 7, 11, 15], 9))  # [0, 1]
```

Correct, but O(n²): 10,000 numbers means about 50 million pair checks.

## The easy idea: remember what you've seen

Walk through the list once. For each number, ask the dictionary: "Is my **partner** (`target - x`) already here?" If yes, done. If not, store this number and move on. Press play and watch the dictionary grow:

::viz two-sum {"arr": [2, 7, 11, 15, 3, 6], "target": 9}

## Solution

```python
def two_sum(nums, target):
    seen = {}                          # value -> index
    for i, x in enumerate(nums):
        partner = target - x
        if partner in seen:
            return [seen[partner], i]
        seen[x] = i                    # store AFTER checking
    return []

print(two_sum([2, 7, 11, 15], 9))  # [0, 1]
print(two_sum([3, 2, 4], 6))       # [1, 2]
print(two_sum([3, 3], 6))          # [0, 1]
```

Storing **after** checking stops a number from pairing with itself (`[3, 2, 4]`, target 6, must not return `[0, 0]`).

## Complexity

- **Time O(n):** one pass, each dictionary lookup is O(1) on average.
- **Space O(n):** the dictionary can hold every number.

## Follow-ups interviewers ask

- *The array is sorted.* Use two pointers for O(1) space (see Two Sum II).
- *Return all pairs / count pairs.* Keep counts in the dictionary instead of a single index.

## Similar problems

Two Sum II (sorted input), 3Sum, 4Sum, Subarray Sum Equals K, Contains Duplicate.

Learn the pattern: [Hashing](/tutorials/dsa-hashing/).
