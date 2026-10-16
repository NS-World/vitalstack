---
title: Trapping Rain Water
slug: problem-trapping-rain-water
subject: dsa-problems
lesson: 11
difficulty: advanced
level: Hard
pattern: Two Pointers
companies: Amazon, Google, Goldman Sachs, Meta, Microsoft, Apple, Bloomberg
excerpt: How much rain collects between bars of different heights? The water above each bar depends on the tallest walls on its left and right.
---

A famous "hard" problem that becomes easy once you think about **one bar at a time**.

## The problem

`height[i]` is a bar of width 1. After rain, how many units of water are trapped between the bars?

## Example

`[0, 1, 0, 2, 1, 0, 1, 3, 2, 1, 2, 1]` → **6** (`#` is a bar, `~` is trapped water)

```text
              #
      # ~ ~ ~ # # ~ #
  # ~ # # ~ # # # # # #
0 1 0 2 1 0 1 3 2 1 2 1
```

## Hints

::hint Hint 1 | Look at one bar. Water above it rises to the LOWER of (tallest bar on its left, tallest bar on its right), minus its own height.
::hint Hint 2 | Precompute "tallest to the left" and "tallest to the right" for every position with two passes.
::hint Hint 3 | For O(1) space: two pointers. The side with the smaller max is the one whose water you already know.

## The key formula

For each position i:

`water[i] = min(max_left[i], max_right[i]) - height[i]`

Think of a bathtub: water rises only as high as the shorter of its two walls.

## Solution 1: precompute both sides, O(n) space

```python
def trap_arrays(height):
    n = len(height)
    if n == 0:
        return 0
    left = [0] * n
    right = [0] * n
    left[0] = height[0]
    for i in range(1, n):
        left[i] = max(left[i - 1], height[i])
    right[-1] = height[-1]
    for i in range(n - 2, -1, -1):
        right[i] = max(right[i + 1], height[i])
    return sum(min(left[i], right[i]) - height[i] for i in range(n))

print(trap_arrays([0, 1, 0, 2, 1, 0, 1, 3, 2, 1, 2, 1]))  # 6
```

## Solution 2: two pointers, O(1) space

Keep the tallest wall seen from each end. Whichever side has the **smaller** tallest wall is the limiting side, so its water is already decided: compute it and move that pointer inwards.

```python
def trap(height):
    left, right = 0, len(height) - 1
    left_max = right_max = 0
    water = 0
    while left < right:
        if height[left] < height[right]:
            left_max = max(left_max, height[left])
            water += left_max - height[left]
            left += 1
        else:
            right_max = max(right_max, height[right])
            water += right_max - height[right]
            right -= 1
    return water

print(trap([0, 1, 0, 2, 1, 0, 1, 3, 2, 1, 2, 1]))  # 6
print(trap([4, 2, 0, 3, 2, 5]))                    # 9
print(trap([]))                                    # 0
```

## Complexity

| Method | Time | Space |
|---|---|---|
| Check left/right for each bar | O(n²) | O(1) |
| Precomputed arrays | O(n) | O(n) |
| Two pointers | O(n) | O(1) |

A monotonic stack solution also exists (it fills water layer by layer); mention it if asked for alternatives.

## Similar problems

Container With Most Water, Product of Array Except Self, Largest Rectangle in Histogram, Trapping Rain Water II (3D, uses a heap).

Learn the pattern: [Two Pointers](/tutorials/dsa-two-pointers/).
