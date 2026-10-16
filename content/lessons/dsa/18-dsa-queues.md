---
title: Queues and Deques
slug: dsa-queues
subject: dsa
lesson: 18
difficulty: intermediate
excerpt: First in, first out. Use collections.deque for O(1) queues, build a queue from two stacks, process things in order, and solve sliding window maximum with a monotonic deque.
---

A **queue** works like a line at a ticket counter: the first person to join is the first to be served. **First In, First Out (FIFO)**. Queues manage tasks in order: print jobs, messages between servers, and, most importantly for DSA, the order in which **breadth-first search** explores trees and graphs.

## The ticket counter analogy

New people join at the **back**. The counter serves from the **front**. Nobody jumps the line. A **deque** (double-ended queue, pronounced "deck") is a line where people can join or leave at **both** ends.

## Use `collections.deque`, not a list

`list.pop(0)` shifts every remaining item: O(n). A deque does both ends in O(1):

```python
from collections import deque

q = deque()
q.append("Asha")       # join at the back
q.append("Rohan")
q.append("Meera")
print(q.popleft())     # Asha
print(q[0])            # Rohan
print(len(q))          # 2
```

`popleft()` serves Asha from the front; `q[0]` peeks at the next person, Rohan.

| Operation | `deque` | `list` |
|---|---|---|
| add at back | `append` O(1) | `append` O(1) |
| remove from front | `popleft` O(1) | `pop(0)` **O(n)** |
| add at front | `appendleft` O(1) | `insert(0, x)` **O(n)** |
| remove from back | `pop` O(1) | `pop` O(1) |

## Queue vs stack

| | Stack | Queue |
|---|---|---|
| Order | Last in, first out | First in, first out |
| Python | `list` | `collections.deque` |
| Explores | Deep first (DFS) | Level by level (BFS) |
| Everyday example | Undo | Ticket counter |

## Level-order processing (a BFS preview)

A queue processes items in the order they were discovered: first everything one step away, then everything two steps away. Here's a tree visited level by level (you'll study trees in the next lesson):

::viz tree-traversal {"tree": [1, 2, 3, 4, 5, null, 6], "order": "levelorder"}

## Recent calls counter

Count requests in the last 3,000 ms. New times join the back; times that are too old leave from the front.

```python
from collections import deque

class RecentCounter:
    def __init__(self):
        self.q = deque()

    def ping(self, t):
        self.q.append(t)
        while self.q[0] < t - 3000:
            self.q.popleft()
        return len(self.q)

rc = RecentCounter()
print([rc.ping(t) for t in [1, 100, 3001, 3002]])  # [1, 2, 3, 3]
```

Each time is added and removed once, so each `ping` is O(1) on average.

## Queue using two stacks

A classic design question. Push into an `inbox` stack; when you need the front, pour the inbox into an `outbox` stack (which reverses the order):

```python
class QueueFromStacks:
    def __init__(self):
        self.inbox, self.outbox = [], []

    def push(self, x):
        self.inbox.append(x)

    def pop(self):
        self.peek()
        return self.outbox.pop()

    def peek(self):
        if not self.outbox:
            while self.inbox:
                self.outbox.append(self.inbox.pop())
        return self.outbox[-1]

q = QueueFromStacks()
for x in [1, 2, 3]:
    q.push(x)
print(q.pop(), q.pop())  # 1 2
q.push(4)
print(q.pop(), q.pop())  # 3 4
```

Each item moves between stacks at most once, so operations are **amortised O(1)**.

## The monotonic deque: sliding window maximum

Find the maximum of every window of size k. Keep a deque of indexes whose values are **decreasing**. The front is always the current maximum; remove indexes that slide out of the window from the front, and smaller values that can never be a maximum from the back.

```python
from collections import deque

def max_sliding_window(nums, k):
    dq, out = deque(), []
    for i, x in enumerate(nums):
        while dq and nums[dq[-1]] <= x:
            dq.pop()                    # smaller values can never win now
        dq.append(i)
        if dq[0] <= i - k:
            dq.popleft()                # the front slid out of the window
        if i >= k - 1:
            out.append(nums[dq[0]])
    return out

print(max_sliding_window([1, 3, -1, -3, 5, 3, 6, 7], 3))  # [3, 3, 5, 5, 6, 7]
```

O(n) instead of O(n·k). It combines three patterns from this course: sliding window, monotonic stack and deque.

## Priority queues (a preview)

Sometimes "first in" isn't fair: a hospital serves the most urgent patient first. A **priority queue** always serves the smallest (or largest) item next. In Python that's `heapq`, covered in the Heaps lesson.

## How to recognise the pattern

- "Process in the order received", "first come first served".
- "Level by level", "minimum number of steps": BFS uses a queue.
- Sliding windows where you need the max/min: a monotonic deque.
- Design questions: implement a queue with stacks, a circular queue, a rate limiter.

::tip In Python interviews, always import deque for queues. Saying "list.pop(0) is O(n), so I'll use a deque" earns you points.

## Interview corner

Implement Queue using Stacks, Number of Recent Calls, Sliding Window Maximum, Design Circular Queue and every BFS question (Binary Tree Level Order Traversal, Rotting Oranges, Word Ladder) are commonly reported at **Amazon, Google, Microsoft, Meta and Uber**.

## Common mistakes

- **Using `list.pop(0)`** in a loop, making BFS O(n²).
- **Forgetting to remove expired items** from the front of a window.
- **Popping from an empty deque** (`IndexError`).
- **Storing values instead of indexes** in the monotonic deque, so you can't tell when they leave the window.

## Exercise

1. Use a deque to simulate a hot-potato game: n players in a circle, every k-th player is removed. Who is last?
2. Write `first_unique_stream()` that returns the first non-repeating character after each new character of a stream. Hint: a queue plus a counter.
3. Implement a fixed-size circular queue class with `enqueue`, `dequeue` and `is_full`.

## Summary

- A queue is FIFO; use `collections.deque` for O(1) at both ends.
- Queues drive level-by-level processing (BFS).
- Two stacks can make a queue with amortised O(1) operations.
- A monotonic deque gives the maximum of every sliding window in O(n).

Next, you'll explore **binary trees**: traversals, depth and level order.
