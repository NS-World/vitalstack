---
title: Container With Most Water
slug: problem-container-with-most-water
subject: dsa-problems
lesson: 9
difficulty: intermediate
level: Medium
pattern: Two Pointers
companies: Amazon, Google, Meta, Microsoft, Goldman Sachs, Adobe
excerpt: Pick two walls that hold the most water. Start wide and always move the shorter wall inwards.
---

A beautiful greedy two-pointer argument that interviewers love to hear explained.

## The problem

`height[i]` is the height of a vertical wall at position i. Choose two walls; together with the ground they form a container. The water it holds is `width × min(height of the two walls)`. Return the maximum.

## Example

`height = [1, 8, 6, 2, 5, 4, 8, 3, 7]` → **49** (walls at positions 1 and 8: width 7, shorter wall 7).

## Hints

::hint Hint 1 | Start with the widest container: one pointer at each end.
::hint Hint 2 | The water is limited by the SHORTER wall. Moving the taller wall inwards can never help. Which one should you move?

## Brute force, O(n²)

```python
def max_area_slow(height):
    best = 0
    for i in range(len(height)):
        for j in range(i + 1, len(height)):
            best = max(best, (j - i) * min(height[i], height[j]))
    return best

print(max_area_slow([1, 8, 6, 2, 5, 4, 8, 3, 7]))  # 49
```

## The easy idea: always move the short wall

Start with the widest container. Every move inward loses width, so the only hope of more water is a **taller** short wall. Moving the tall wall can't raise the minimum (the short wall still limits it), so move the **short** wall. Each step throws away a wall that can never be part of a better answer.

## Solution

```python
def max_area(height):
    left, right = 0, len(height) - 1
    best = 0
    while left < right:
        water = (right - left) * min(height[left], height[right])
        best = max(best, water)
        if height[left] < height[right]:
            left += 1
        else:
            right -= 1
    return best

print(max_area([1, 8, 6, 2, 5, 4, 8, 3, 7]))  # 49
print(max_area([1, 1]))                       # 1
print(max_area([4, 3, 2, 1, 4]))              # 16
```

## Complexity

- **Time O(n):** the pointers move towards each other, n steps in total.
- **Space O(1).**

## Similar problems

Trapping Rain Water (sounds similar, different idea), Two Sum II, 3Sum, Boats to Save People.

Learn the pattern: [Two Pointers](/tutorials/dsa-two-pointers/).
