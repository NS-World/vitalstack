---
title: Linked List Cycle
slug: problem-linked-list-cycle
subject: dsa-problems
lesson: 26
difficulty: beginner
level: Easy
pattern: Fast & Slow Pointers
companies: Amazon, Microsoft, Bloomberg, Goldman Sachs, Oracle, Yahoo
excerpt: Does a linked list loop back on itself? A tortoise and a hare running at different speeds will meet if there's a loop.
---

Floyd's "tortoise and hare" is one of the most elegant tricks in computer science.

## The problem

Return `True` if the linked list contains a cycle (some node's `next` points back to an earlier node).

## Examples

```text
3 → 2 → 0 → -4
    ↑        |
    └────────┘      → True

1 → 2 → None        → False
```

## Hints

::hint Hint 1 | Simple version: store every visited node in a set. Seeing one twice means a cycle. That's O(n) space.
::hint Hint 2 | For O(1) space: two pointers, one moving 1 step, one moving 2 steps.
::hint Hint 3 | If there's a cycle, the fast one laps the slow one and they meet. If not, fast reaches the end.

## Solution 1: a set, O(n) space

```python
class ListNode:
    def __init__(self, val):
        self.val, self.next = val, None

def has_cycle_set(head):
    seen = set()
    while head:
        if head in seen:
            return True
        seen.add(head)
        head = head.next
    return False

a, b = ListNode(1), ListNode(2)
a.next, b.next = b, a
print(has_cycle_set(a))  # True
```

## The easy idea: runners on a circular track

Two runners start together; one runs twice as fast. On a straight road, the fast one simply reaches the finish. On a **circular** track, the fast runner eventually catches up with the slow one from behind. Meeting = loop. Watch the pointers:

::viz fast-slow {"arr": [1, 2, 3, 4, 5, 6, 7]}

## Solution 2: fast and slow pointers, O(1) space

```python
class ListNode:
    def __init__(self, val):
        self.val, self.next = val, None

def has_cycle(head):
    slow = fast = head
    while fast and fast.next:
        slow = slow.next
        fast = fast.next.next
        if slow is fast:
            return True
    return False

nodes = [ListNode(v) for v in [3, 2, 0, -4]]
for x, y in zip(nodes, nodes[1:]):
    x.next = y
print(has_cycle(nodes[0]))   # False
nodes[-1].next = nodes[1]    # -4 points back to 2
print(has_cycle(nodes[0]))   # True
print(has_cycle(None))       # False
```

## Complexity

- **Time O(n):** once both are inside the loop, the gap shrinks by 1 each step.
- **Space O(1).**

## Follow-up: where does the cycle start?

After they meet, move one pointer back to the head. Move both **one** step at a time; they meet exactly at the cycle's start.

```python
class ListNode:
    def __init__(self, val):
        self.val, self.next = val, None

def cycle_start(head):
    slow = fast = head
    while fast and fast.next:
        slow, fast = slow.next, fast.next.next
        if slow is fast:
            slow = head
            while slow is not fast:
                slow, fast = slow.next, fast.next
            return slow
    return None

nodes = [ListNode(v) for v in [3, 2, 0, -4]]
for x, y in zip(nodes, nodes[1:]):
    x.next = y
nodes[-1].next = nodes[1]
print(cycle_start(nodes[0]).val)  # 2
```

## Similar problems

Linked List Cycle II, Middle of the Linked List, Happy Number, Find the Duplicate Number.

Learn the pattern: [Fast and Slow Pointers](/tutorials/dsa-fast-slow-pointers/).
