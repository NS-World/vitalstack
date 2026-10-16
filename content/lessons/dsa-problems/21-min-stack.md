---
title: Min Stack
slug: problem-min-stack
subject: dsa-problems
lesson: 21
difficulty: intermediate
level: Medium
pattern: Stack
companies: Amazon, Bloomberg, Microsoft, Google, Oracle
excerpt: Design a stack that returns its minimum in O(1). Store the minimum-so-far alongside every item.
---

A design question that tests whether you can trade a little memory for speed.

## The problem

Design a stack with `push(x)`, `pop()`, `top()` and `get_min()`, **all in O(1)**.

## Example

```text
push(-2), push(0), push(-3)
get_min() → -3
pop()
top()     → 0
get_min() → -2
```

## Hints

::hint Hint 1 | Scanning for the minimum is O(n). You need the answer ready at all times.
::hint Hint 2 | When you push x, the new minimum is min(x, current minimum). Store that together with x.
::hint Hint 3 | When you pop, the previous minimum is automatically back on top.

## The easy idea: sticky notes

Each plate you add to the pile gets a sticky note saying "smallest plate at or below me". The top plate's note always has the answer. When you remove a plate, the note on the next plate is still correct, because it was written when that plate was on top.

```text
push -2 → (-2, min -2)
push  0 → ( 0, min -2)
push -3 → (-3, min -3)   ← get_min() reads -3
pop     → back to ( 0, min -2)
```

## Solution

```python
class MinStack:
    def __init__(self):
        self.items = []                   # (value, min at or below)

    def push(self, x):
        current_min = min(x, self.items[-1][1]) if self.items else x
        self.items.append((x, current_min))

    def pop(self):
        self.items.pop()

    def top(self):
        return self.items[-1][0]

    def get_min(self):
        return self.items[-1][1]

s = MinStack()
s.push(-2)
s.push(0)
s.push(-3)
print(s.get_min())  # -3
s.pop()
print(s.top())      # 0
print(s.get_min())  # -2
```

## Complexity

- Every operation **O(1)** time.
- **O(n)** space (each item stores one extra number).

## Follow-up: save memory

Keep a second stack of minimums, pushing only when a new value is **less than or equal to** the current minimum. Pop from it only when the popped value equals its top. Same O(1) time; less memory when the minimum rarely changes.

## Similar problems

Max Stack, Implement Queue using Stacks, Online Stock Span, Sliding Window Maximum.

Learn the pattern: [Stacks](/tutorials/dsa-stacks/).
