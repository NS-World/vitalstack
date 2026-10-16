---
title: 3Sum
slug: problem-three-sum
subject: dsa-problems
lesson: 10
difficulty: intermediate
level: Medium
pattern: Two Pointers
companies: Meta, Amazon, Google, Microsoft, Apple, Adobe, Bloomberg
excerpt: Find all unique triplets that sum to zero. Sort, fix one number, then run two pointers on the rest, skipping duplicates.
---

3Sum = **fix one number** + **Two Sum on a sorted list**. The tricky part is avoiding duplicate triplets.

## The problem

Return all **unique** triplets `[a, b, c]` from `nums` with `a + b + c == 0`.

## Examples

| nums | answer |
|---|---|
| `[-1, 0, 1, 2, -1, -4]` | `[[-1, -1, 2], [-1, 0, 1]]` |
| `[0, 1, 1]` | `[]` |
| `[0, 0, 0, 0]` | `[[0, 0, 0]]` |

## Hints

::hint Hint 1 | Sort first. Then for each number `nums[i]`, you need two numbers after it that sum to `-nums[i]`.
::hint Hint 2 | On a sorted list, that's two pointers: too small → move left up; too big → move right down.
::hint Hint 3 | Skip a number equal to the previous one (for i, and after finding a triplet) to avoid duplicates.

## Brute force, O(n³)

Check every triplet and put sorted triplets in a set. It works for tiny inputs only.

## The easy idea

After sorting `[-4, -1, -1, 0, 1, 2]`:

- Fix `-4`: need two numbers summing to 4 from `[-1, -1, 0, 1, 2]`: none.
- Fix `-1`: need 1 from `[-1, 0, 1, 2]`: `(-1, 2)` and `(0, 1)` → two triplets.
- The next `-1` is the same as before: **skip** it, or we'd repeat triplets.

Here is the two-pointer step after fixing the first `-1`: search the rest, `[-1, 0, 1, 2]`, for a pair summing to 1:

::viz two-pointers {"arr": [-1, 0, 1, 2], "target": 1}

## Solution

```python
def three_sum(nums):
    nums.sort()
    out = []
    for i in range(len(nums) - 2):
        if i > 0 and nums[i] == nums[i - 1]:
            continue                            # same first number: skip
        if nums[i] > 0:
            break                               # all remaining are positive
        lo, hi = i + 1, len(nums) - 1
        while lo < hi:
            total = nums[i] + nums[lo] + nums[hi]
            if total < 0:
                lo += 1
            elif total > 0:
                hi -= 1
            else:
                out.append([nums[i], nums[lo], nums[hi]])
                lo += 1
                hi -= 1
                while lo < hi and nums[lo] == nums[lo - 1]:
                    lo += 1                     # skip duplicate seconds
    return out

print(three_sum([-1, 0, 1, 2, -1, -4]))  # [[-1, -1, 2], [-1, 0, 1]]
print(three_sum([0, 1, 1]))              # []
print(three_sum([0, 0, 0, 0]))           # [[0, 0, 0]]
```

## Complexity

- **Time O(n²):** sorting is O(n log n), then n two-pointer passes of O(n).
- **Space O(1)** extra (ignoring the output and the sort).

## Similar problems

Two Sum II, 3Sum Closest, 4Sum, 3Sum Smaller, Valid Triangle Number.

Learn the pattern: [Two Pointers](/tutorials/dsa-two-pointers/).
