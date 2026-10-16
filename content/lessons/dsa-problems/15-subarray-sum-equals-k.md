---
title: Subarray Sum Equals K
slug: problem-subarray-sum-equals-k
subject: dsa-problems
lesson: 15
difficulty: intermediate
level: Medium
pattern: Prefix Sum
companies: Meta, Google, Amazon, Microsoft, Goldman Sachs, Twitter
excerpt: Count continuous subarrays that add up to k, even with negative numbers. Prefix sums plus a hash map of counts.
---

Sliding windows fail when numbers can be **negative**. Prefix sums + a dictionary don't.

## The problem

Return how many continuous subarrays of `nums` sum to exactly `k`.

## Examples

| nums | k | answer | subarrays |
|---|---|---|---|
| `[1, 1, 1]` | 2 | 2 | [1,1] twice |
| `[1, 2, 3]` | 3 | 2 | [1,2] and [3] |
| `[3, -1, 2, -2, 4]` | 2 | 4 | |

## Hints

::hint Hint 1 | sum(i..j) = prefix[j + 1] − prefix[i]. So a subarray sums to k when two prefix sums differ by exactly k.
::hint Hint 2 | While walking, ask: "How many earlier prefix sums equal current − k?" A dictionary of counts answers instantly.
::hint Hint 3 | Start the dictionary with {0: 1}: the empty prefix, so subarrays starting at index 0 are counted.

## Brute force, O(n²)

```python
def subarray_sum_slow(nums, k):
    count = 0
    for i in range(len(nums)):
        total = 0
        for j in range(i, len(nums)):
            total += nums[j]
            if total == k:
                count += 1
    return count

print(subarray_sum_slow([1, 1, 1], 2))  # 2
```

## The easy idea: kilometre markers

A running total is like the kilometre marker on a highway. The distance between two markers is the sum of the road between them. To find stretches of exactly k km, at each marker ask: "How many earlier markers read `current − k`?"

Here is how prefix sums turn any range sum into one subtraction:

::viz prefix-sum {"arr": [3, -1, 2, -2, 4], "l": 1, "r": 3}

## Solution

```python
def subarray_sum(nums, k):
    seen = {0: 1}                 # prefix sum -> how many times seen
    total = count = 0
    for x in nums:
        total += x
        count += seen.get(total - k, 0)
        seen[total] = seen.get(total, 0) + 1
    return count

print(subarray_sum([1, 1, 1], 2))           # 2
print(subarray_sum([1, 2, 3], 3))           # 2
print(subarray_sum([3, -1, 2, -2, 4], 2))   # 4
```

## Why not a sliding window?

A window assumes adding numbers makes the sum bigger. With negatives, shrinking can make the sum **grow**, so the window can't decide which way to move. Prefix sums don't care about signs.

## Complexity

- **Time O(n)**, **space O(n)** for the dictionary.

## Similar problems

Two Sum (same "look up the partner" trick), Continuous Subarray Sum, Subarray Sums Divisible by K, Contiguous Array, Path Sum III.

Learn the pattern: [Prefix Sums](/tutorials/dsa-prefix-sum/).
