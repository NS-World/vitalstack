---
title: Monotonic Stack
slug: dsa-monotonic-stack
subject: dsa
lesson: 17
difficulty: advanced
excerpt: A stack kept in sorted order answers "next greater" and "previous smaller" questions in O(n). Daily temperatures, stock span, and the largest rectangle in a histogram.
---

"For each day, how many days until a warmer temperature?" "For each number, what is the next bigger number to its right?" Checking ahead for every item is O(n²). A **monotonic stack** (a stack whose values stay increasing or decreasing) answers all of them in **one pass, O(n)**.

## The queue of people analogy

People stand in a line, looking right. Each person wants to know: **who is the first taller person ahead of me?**

Walk through the line from left to right, keeping a group of people still **waiting** for their answer. When a tall person arrives, everyone shorter who is waiting has just found their answer, and they leave the group. The tall person then joins the waiting group.

The waiting group is always sorted from tallest (bottom) to shortest (top): a **decreasing stack**.

::viz next-greater {"arr": [2, 1, 2, 4, 3, 1, 5]}

## Next greater element

```python
def next_greater(nums):
    res = [-1] * len(nums)
    stack = []                         # indexes still waiting
    for i, x in enumerate(nums):
        while stack and nums[stack[-1]] < x:
            res[stack.pop()] = x       # x is their next greater
        stack.append(i)
    return res

print(next_greater([2, 1, 2, 4, 3, 1, 5]))  # [4, 2, 4, 5, 5, 5, -1]
print(next_greater([5, 4, 3]))              # [-1, -1, -1]
```

**Why O(n)?** The inner `while` looks scary, but each index is pushed once and popped at most once. Total pushes + pops ≤ 2n.

We store **indexes**, not values, so we know where to write each answer and can compute distances.

## Daily temperatures

For each day, how many days until a warmer one?

```python
def daily_temperatures(temps):
    res = [0] * len(temps)
    stack = []
    for i, t in enumerate(temps):
        while stack and temps[stack[-1]] < t:
            j = stack.pop()
            res[j] = i - j              # distance in days
        stack.append(i)
    return res

print(daily_temperatures([73, 74, 75, 71, 69, 72, 76, 73]))  # [1, 1, 4, 2, 1, 1, 0, 0]
```

Same template, but we store the **distance** instead of the value.

## The four variants

| Question | Loop direction | Pop while top is… |
|---|---|---|
| Next greater (to the right) | left → right | smaller than current |
| Next smaller (to the right) | left → right | bigger than current |
| Previous greater (to the left) | left → right, answer = stack top after popping | smaller or equal |
| Previous smaller (to the left) | left → right, answer = stack top after popping | bigger or equal |

### Previous greater: stock span

The "span" of today's price is how many consecutive days up to today had a price ≤ today's. That is the distance to the **previous greater** price.

```python
def stock_span(prices):
    res, stack = [], []          # stack of indexes with decreasing prices
    for i, p in enumerate(prices):
        while stack and prices[stack[-1]] <= p:
            stack.pop()
        res.append(i - stack[-1] if stack else i + 1)
        stack.append(i)
    return res

print(stock_span([100, 80, 60, 70, 60, 75, 85]))  # [1, 1, 1, 2, 1, 4, 6]
```

## Circular arrays

If the array wraps around, loop through it **twice** (using `i % n`), pushing only during the first pass:

```python
def next_greater_circular(nums):
    n = len(nums)
    res, stack = [-1] * n, []
    for i in range(2 * n):
        x = nums[i % n]
        while stack and nums[stack[-1]] < x:
            res[stack.pop()] = x
        if i < n:
            stack.append(i)
    return res

print(next_greater_circular([1, 2, 1]))  # [2, -1, 2]
```

## The famous hard one: largest rectangle in a histogram

Bars of width 1 with given heights: what's the area of the largest rectangle? For each bar, the widest rectangle using its full height stretches until the **previous smaller** and **next smaller** bars. An **increasing** stack finds both in one pass:

```python
def largest_rectangle(heights):
    stack, best = [], 0                     # increasing stack of indexes
    for i, h in enumerate(heights + [0]):   # extra 0 flushes the stack at the end
        while stack and heights[stack[-1]] > h:
            height = heights[stack.pop()]
            left = stack[-1] if stack else -1
            best = max(best, height * (i - left - 1))
        stack.append(i)
    return best

print(largest_rectangle([2, 1, 5, 6, 2, 3]))  # 10
print(largest_rectangle([2, 4]))              # 4
```

When a bar is popped, the current index i is its next smaller bar, and the new stack top is its previous smaller bar; the width between them is `i - left - 1`.

## How to recognise the pattern

- "Next greater / smaller", "previous greater / smaller", "how many days until…"
- "Span", "visible", "the first element to the left/right that…"
- Histogram, rectangle or "trapping rain water" style problems.
- A brute force that, for each item, scans ahead until a condition is met.

::tip Ask: "For each item, am I looking for the first item to one side that is bigger or smaller?" If yes, it's a monotonic stack.

## Interview corner

Daily Temperatures, Next Greater Element (I and II), Online Stock Span, Largest Rectangle in Histogram, Trapping Rain Water and Remove K Digits are commonly reported at **Amazon, Google, Meta, Microsoft, Uber and Goldman Sachs**. The key thing interviewers check: can you explain why it's O(n) despite the nested loop?

## Common mistakes

- **Storing values when you need indexes** (for distances or writing answers).
- **Using `<` vs `<=` inconsistently**, which matters when values repeat.
- **Forgetting the items left on the stack** at the end (their answer is the default, like −1 or 0).
- **Calling it O(n²)** because of the inner loop.

## Exercise

1. Play the visualizer with `[6, 5, 4, 3, 7]`. Watch how many items one number "frees".
2. Write `next_smaller(nums)`.
3. Write `remove_k_digits(num, k)` returning the smallest number after removing k digits ("1432219", 3 → "1219"). Hint: keep an increasing stack of digits.

## Summary

- A monotonic stack keeps its values sorted by popping items that can no longer matter.
- Each item is pushed and popped once: O(n).
- Decreasing stack → next greater; increasing stack → next smaller.
- Store indexes to compute distances and widths.

Next, you'll learn **queues**, the first-in-first-out partner of the stack.
