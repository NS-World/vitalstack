---
title: Daily Temperatures
slug: problem-daily-temperatures
subject: dsa-problems
lesson: 22
difficulty: intermediate
level: Medium
pattern: Monotonic Stack
companies: Amazon, Google, Meta, Microsoft, Uber, Bloomberg
excerpt: How many days until a warmer day? A monotonic stack of waiting days answers it in one pass.
---

The friendliest introduction to the **monotonic stack**: a stack that keeps its items in decreasing order.

## The problem

Given daily temperatures, return a list where `answer[i]` is the number of days you must wait after day i for a **warmer** temperature, or 0 if none comes.

## Example

`[73, 74, 75, 71, 69, 72, 76, 73]` → `[1, 1, 4, 2, 1, 1, 0, 0]`

## Hints

::hint Hint 1 | Brute force looks ahead from every day: O(n²).
::hint Hint 2 | Keep a stack of days still WAITING for a warmer day. Their temperatures are decreasing from bottom to top.
::hint Hint 3 | When a warm day arrives, it answers every waiting day on top of the stack that is colder.

## Brute force, O(n²)

```python
def daily_temps_slow(temps):
    out = [0] * len(temps)
    for i in range(len(temps)):
        for j in range(i + 1, len(temps)):
            if temps[j] > temps[i]:
                out[i] = j - i
                break
    return out

print(daily_temps_slow([73, 74, 75, 71, 69, 72, 76, 73]))  # [1, 1, 4, 2, 1, 1, 0, 0]
```

## The easy idea: a queue of short people

Picture people standing in a line, each waiting to see someone taller arrive. When a tall person shows up, everyone shorter at the back of the line gets their answer and leaves. The line always goes from tallest (front) to shortest (back). That's a monotonic stack. Watch it with "next greater element":

::viz next-greater {"arr": [73, 74, 75, 71, 69, 72, 76, 73]}

## Solution

```python
def daily_temperatures(temps):
    out = [0] * len(temps)
    waiting = []                         # indexes, temps decreasing
    for i, t in enumerate(temps):
        while waiting and temps[waiting[-1]] < t:
            j = waiting.pop()
            out[j] = i - j               # day j finally got a warmer day
        waiting.append(i)
    return out

print(daily_temperatures([73, 74, 75, 71, 69, 72, 76, 73]))  # [1, 1, 4, 2, 1, 1, 0, 0]
print(daily_temperatures([30, 40, 50, 60]))                  # [1, 1, 1, 0]
print(daily_temperatures([30, 20, 10]))                      # [0, 0, 0]
```

Store **indexes** on the stack, not temperatures: you need the index to compute the distance.

## Complexity

- **Time O(n):** each day is pushed once and popped at most once, even though there's a `while` inside a `for`.
- **Space O(n)** for the stack.

## Similar problems

Next Greater Element I and II, Online Stock Span, Largest Rectangle in Histogram, Car Fleet.

Learn the pattern: [Monotonic Stack](/tutorials/dsa-monotonic-stack/).
