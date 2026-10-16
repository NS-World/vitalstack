---
title: Largest Rectangle in Histogram
slug: problem-largest-rectangle-in-histogram
subject: dsa-problems
lesson: 23
difficulty: advanced
level: Hard
pattern: Monotonic Stack
companies: Google, Amazon, Microsoft, Meta, Uber, Goldman Sachs
excerpt: The biggest rectangle that fits under a bar chart. For each bar, find how far it can stretch left and right with a monotonic stack.
---

A "hard" problem whose solution is a short loop once you see the trick.

## The problem

`heights[i]` is a bar of width 1. Return the area of the largest rectangle that fits entirely inside the histogram.

## Examples

| heights | answer | rectangle |
|---|---|---|
| `[2, 1, 5, 6, 2, 3]` | 10 | height 5, bars 2–3 |
| `[2, 4]` | 4 | the bar of height 4 |

```text
      #
    # #
    # #
    # #   #
#   # # # #
# # # # # #
2 1 5 6 2 3
```

## Hints

::hint Hint 1 | Every rectangle's height equals its SHORTEST bar. So try each bar as "the shortest one".
::hint Hint 2 | For bar i as the shortest, the rectangle stretches left and right until a SHORTER bar blocks it.
::hint Hint 3 | Keep a stack of bars with increasing heights. When a shorter bar arrives, pop: the popped bar's right blocker is the new bar, and its left blocker is the bar below it on the stack.

## Brute force, O(n²)

```python
def largest_rect_slow(heights):
    best = 0
    for i in range(len(heights)):
        low = heights[i]
        for j in range(i, len(heights)):
            low = min(low, heights[j])
            best = max(best, low * (j - i + 1))
    return best

print(largest_rect_slow([2, 1, 5, 6, 2, 3]))  # 10
```

## The easy idea: how far can each bar stretch?

Take the bar of height 5 (index 2). Stretch it sideways: to the right it can cover index 3 (height 6 ≥ 5), but index 4 (height 2) blocks it. To the left, index 1 (height 1) blocks it immediately. Width 2, area 10.

The stack keeps bars in **increasing** height order. When a shorter bar arrives, every taller bar on top has just found its right blocker, so compute its area and pop it.

## Solution

```python
def largest_rectangle(heights):
    stack = []                                  # indexes, heights increasing
    best = 0
    for i, h in enumerate(heights + [0]):       # 0 at the end flushes the stack
        while stack and heights[stack[-1]] > h:
            height = heights[stack.pop()]
            left = stack[-1] if stack else -1   # left blocker
            width = i - left - 1                # i is the right blocker
            best = max(best, height * width)
        stack.append(i)
    return best

print(largest_rectangle([2, 1, 5, 6, 2, 3]))  # 10
print(largest_rectangle([2, 4]))              # 4
print(largest_rectangle([1, 1, 1, 1]))        # 4
```

The extra `0` at the end makes sure every bar is popped and measured.

## Complexity

- **Time O(n):** each bar is pushed and popped once.
- **Space O(n)** for the stack.

## Similar problems

Maximal Rectangle (apply this to each row of a 0/1 matrix), Trapping Rain Water, Daily Temperatures, Sum of Subarray Minimums.

Learn the pattern: [Monotonic Stack](/tutorials/dsa-monotonic-stack/).
