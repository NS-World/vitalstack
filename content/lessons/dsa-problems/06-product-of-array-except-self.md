---
title: Product of Array Except Self
slug: problem-product-of-array-except-self
subject: dsa-problems
lesson: 6
difficulty: intermediate
level: Medium
pattern: Prefix Sum
companies: Amazon, Meta, Microsoft, Apple, Asana, Lyft
excerpt: For every position, multiply everything except that number, without using division. Prefix and suffix products make it O(n).
---

A favourite because the obvious trick (division) is banned, which forces you to discover **prefix products**.

## The problem

Return a list `answer` where `answer[i]` is the product of every number in `nums` **except** `nums[i]`. Don't use division, and aim for O(n).

## Examples

| nums | answer |
|---|---|
| `[1, 2, 3, 4]` | `[24, 12, 8, 6]` |
| `[-1, 1, 0, -3, 3]` | `[0, 0, 9, 0, 0]` |

## Hints

::hint Hint 1 | The answer at i is (product of everything on the LEFT of i) × (product of everything on the RIGHT of i).
::hint Hint 2 | Build the left products in one pass from the start, and the right products in one pass from the end.

## Brute force, O(n²)

```python
def product_except_slow(nums):
    out = []
    for i in range(len(nums)):
        p = 1
        for j, x in enumerate(nums):
            if j != i:
                p *= x
        out.append(p)
    return out

print(product_except_slow([1, 2, 3, 4]))  # [24, 12, 8, 6]
```

## The easy idea: left side × right side

For `[1, 2, 3, 4]`:

| i | left of i | right of i | answer |
|---|---|---|---|
| 0 | 1 | 2·3·4 = 24 | 24 |
| 1 | 1 | 3·4 = 12 | 12 |
| 2 | 1·2 = 2 | 4 | 8 |
| 3 | 1·2·3 = 6 | 1 | 6 |

It's the prefix sum idea with multiplication instead of addition.

## Solution

```python
def product_except_self(nums):
    n = len(nums)
    out = [1] * n
    left = 1
    for i in range(n):              # out[i] = product of everything left of i
        out[i] = left
        left *= nums[i]
    right = 1
    for i in range(n - 1, -1, -1):  # multiply by everything right of i
        out[i] *= right
        right *= nums[i]
    return out

print(product_except_self([1, 2, 3, 4]))       # [24, 12, 8, 6]
print(product_except_self([-1, 1, 0, -3, 3]))  # [0, 0, 9, 0, 0]
```

## Complexity

- **Time O(n):** two passes.
- **Space O(1) extra:** only `left` and `right` (the output list doesn't count).

## Why not divide?

Dividing the total product by `nums[i]` breaks when there's a zero. Interviewers ban it to see if you know prefix techniques.

## Similar problems

Range Sum Query, Trapping Rain Water (also uses left and right maxima), Maximum Product Subarray.

Learn the pattern: [Prefix Sums](/tutorials/dsa-prefix-sum/).
