---
title: Two Pointers
slug: dsa-two-pointers
subject: dsa
lesson: 5
difficulty: beginner
excerpt: Solve sorted-array and pair problems in one pass with O(1) memory. Opposite-end pointers, slow and fast writer pointers, palindromes, removing duplicates and Container With Most Water.
---

The **two pointers** pattern uses two indexes that move through an array in a coordinated way. It turns many O(n²) "check every pair" solutions into **O(n)**, without any extra memory.

## The dance partners analogy

Picture students lined up by height, shortest on the left. A teacher wants a pair whose heights add up to exactly 300 cm. Instead of trying every pair, she stands one student at **each end**:

- Too short together? The left student steps aside and the next taller one comes in.
- Too tall together? The right student steps aside for a shorter one.

Each step rules out one student forever. In at most n steps she either finds the pair or knows none exists.

::viz two-pointers {"arr": [1, 3, 4, 6, 8, 11, 15], "target": 14}

## Style 1: Opposite ends (converging)

Start one pointer at each end and move them towards each other. Works when the array is **sorted**, or when you compare the two ends (palindromes).

### Pair with a target sum (sorted input)

```python
def pair_sum(arr, target):
    left, right = 0, len(arr) - 1
    while left < right:
        total = arr[left] + arr[right]
        if total == target:
            return [left, right]
        elif total < target:
            left += 1        # need a bigger sum
        else:
            right -= 1       # need a smaller sum
    return []

print(pair_sum([1, 3, 4, 6, 8, 11, 15], 14))  # [1, 5]
print(pair_sum([1, 2, 3], 10))                # []
```

**Why is it safe to move a pointer?** If `arr[left] + arr[right]` is too small, then `arr[left]` plus **anything** to the left of `right` is even smaller, so `arr[left]` can never be part of the answer. We can drop it.

O(n) time, O(1) space. (For an **unsorted** array, the hash map version from the previous lesson is better.)

### Valid palindrome (ignoring spaces and punctuation)

```python
def is_palindrome(s):
    left, right = 0, len(s) - 1
    while left < right:
        if not s[left].isalnum():
            left += 1
        elif not s[right].isalnum():
            right -= 1
        elif s[left].lower() != s[right].lower():
            return False
        else:
            left += 1
            right -= 1
    return True

print(is_palindrome("A man, a plan, a canal: Panama"))  # True
print(is_palindrome("race a car"))                      # False
```

### Container with most water

Each number is the height of a vertical line. Pick two lines that, with the x-axis, hold the most water.

```python
def max_area(height):
    left, right = 0, len(height) - 1
    best = 0
    while left < right:
        width = right - left
        best = max(best, width * min(height[left], height[right]))
        if height[left] < height[right]:
            left += 1        # the shorter line limits us: move it
        else:
            right -= 1
    return best

print(max_area([1, 8, 6, 2, 5, 4, 8, 3, 7]))  # 49
```

The insight: the water level is set by the **shorter** line. Moving the taller line can only make the width smaller without raising the level, so we always move the shorter one.

## Style 2: Same direction (reader and writer)

One pointer **reads** every item; the other **writes** the items we keep. Perfect for "remove in place" problems.

### Remove duplicates from a sorted array

```python
def remove_duplicates(nums):
    if not nums:
        return 0
    write = 1
    for read in range(1, len(nums)):
        if nums[read] != nums[read - 1]:
            nums[write] = nums[read]
            write += 1
    return write            # length of the unique part

nums = [1, 1, 2, 3, 3, 3, 4]
k = remove_duplicates(nums)
print(k, nums[:k])  # 4 [1, 2, 3, 4]
```

### Move zeroes to the end

```python
def move_zeroes(nums):
    write = 0
    for read in range(len(nums)):
        if nums[read] != 0:
            nums[write], nums[read] = nums[read], nums[write]
            write += 1
    return nums

print(move_zeroes([0, 1, 0, 3, 12]))  # [1, 3, 12, 0, 0]
```

The writer always points at the next position for a non-zero number; swapping pushes zeros backwards while keeping the order of everything else.

## Style 3: Two arrays at once

### Merge two sorted lists

```python
def merge_sorted(a, b):
    i = j = 0
    out = []
    while i < len(a) and j < len(b):
        if a[i] <= b[j]:
            out.append(a[i])
            i += 1
        else:
            out.append(b[j])
            j += 1
    return out + a[i:] + b[j:]

print(merge_sorted([1, 4, 7], [2, 3, 8, 9]))  # [1, 2, 3, 4, 7, 8, 9]
```

This merge step is the core of **merge sort** (lesson 11).

## Interview level: 3Sum

Find all unique triplets that sum to zero. Sort, fix one number, then run the pair-sum two pointers on the rest:

```python
def three_sum(nums):
    nums.sort()
    res = []
    for i in range(len(nums) - 2):
        if i > 0 and nums[i] == nums[i - 1]:
            continue                      # skip duplicate first numbers
        left, right = i + 1, len(nums) - 1
        while left < right:
            s = nums[i] + nums[left] + nums[right]
            if s < 0:
                left += 1
            elif s > 0:
                right -= 1
            else:
                res.append([nums[i], nums[left], nums[right]])
                left += 1
                while left < right and nums[left] == nums[left - 1]:
                    left += 1             # skip duplicate second numbers
    return res

print(three_sum([-1, 0, 1, 2, -1, -4]))  # [[-1, -1, 2], [-1, 0, 1]]
```

O(n²) instead of the brute-force O(n³).

## How to recognise the pattern

- The input is **sorted** (or you can sort it).
- You're looking for a **pair** (or triplet) meeting a condition.
- You must work **in place** with O(1) extra space.
- You compare elements from **both ends** (palindromes, mirror problems).

::tip If a problem says "sorted array" and "pair", your first thought should be two pointers.

## Interview corner

Two Sum II (sorted input), Valid Palindrome, Container With Most Water, 3Sum, Remove Duplicates and Move Zeroes are commonly reported at **Amazon, Meta, Microsoft, Google, Bloomberg and Adobe**. Interviewers often ask *why* moving a pointer can't skip the answer, so practise explaining it.

## Common mistakes

- **Using `<=` instead of `<`** in `while left < right`, letting a pointer pair with itself.
- **Forgetting to move a pointer** in some branch, causing an infinite loop.
- **Not skipping duplicates** in 3Sum, producing repeated triplets.
- **Using converging pointers on unsorted data** where the logic doesn't hold.

## Exercise

1. Try the visualizer with `[2, 4, 5, 9, 12]` and target 16, then 100.
2. Write `squares_sorted(nums)` for a sorted array with negatives: return the squares in sorted order in O(n). (`[-4, -1, 0, 3, 10]` → `[0, 1, 9, 16, 100]`.) Hint: the biggest square is at one of the two ends.
3. Write `is_subsequence(s, t)`: are the letters of s found in t in order? ("abc" in "ahbgdc" → True.)

## Summary

- Two pointers move through data in a coordinated way, usually in one pass: O(n) time, O(1) space.
- Converging pointers: sorted pairs, palindromes, container problems.
- Reader/writer pointers: in-place removal and partitioning.
- Explain *why* each move is safe: that's what interviewers listen for.

Next, you'll learn the **sliding window**, a two-pointer cousin for contiguous subarrays and substrings.
