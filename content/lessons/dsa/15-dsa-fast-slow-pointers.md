---
title: Fast and Slow Pointers
slug: dsa-fast-slow-pointers
subject: dsa
lesson: 15
difficulty: intermediate
excerpt: The tortoise and hare technique. Find the middle of a linked list, detect cycles and where they start, remove the n-th node from the end, and spot cycles in number sequences.
---

Two pointers moving through a linked list at **different speeds** reveal its shape: where the middle is, whether it loops back on itself, and where the loop begins. This is the **fast and slow pointers** pattern, also called **Floyd's tortoise and hare**. It uses O(1) memory where other methods need a hash set.

## The running track analogy

Two friends run on a track. The hare runs twice as fast as the tortoise.

- On a **straight** road, the hare reaches the end first, and at that moment the tortoise is exactly **halfway**.
- On a **circular** track, the hare eventually **laps** the tortoise. If they ever meet, the track must be a loop.

::viz fast-slow {"arr": [1, 2, 3, 4, 5, 6, 7]}

## Find the middle

```python
class ListNode:
    def __init__(self, val, next=None):
        self.val, self.next = val, next

def build(values):
    head = None
    for v in reversed(values):
        head = ListNode(v, head)
    return head

def middle(head):
    slow = fast = head
    while fast and fast.next:
        slow = slow.next
        fast = fast.next.next
    return slow

print(middle(build([1, 2, 3, 4, 5])).val)     # 3
print(middle(build([1, 2, 3, 4, 5, 6])).val)  # 4
```

For an even length, this returns the **second** middle. One pass, O(1) space, no need to count the length first.

## Detect a cycle

If the list has a loop, `fast` never reaches None; it keeps going round and eventually lands on the same node as `slow`.

```python
class ListNode:
    def __init__(self, val, next=None):
        self.val, self.next = val, next

def has_cycle(head):
    slow = fast = head
    while fast and fast.next:
        slow = slow.next
        fast = fast.next.next
        if slow is fast:
            return True
    return False

a, b, c, d = ListNode(1), ListNode(2), ListNode(3), ListNode(4)
a.next, b.next, c.next = b, c, d
print(has_cycle(a))   # False
d.next = b            # 4 points back to 2: a loop
print(has_cycle(a))   # True
```

**Why must they meet?** Once both are in the loop, the gap between them shrinks by one node every step (fast gains one step on slow each time). A gap that shrinks by one must reach zero.

The hash-set alternative ("have I visited this node?") also works, but uses O(n) memory.

## Find where the cycle starts

After they meet, put one pointer back at the head and move **both one step at a time**. They meet again exactly at the start of the cycle.

```python
class ListNode:
    def __init__(self, val, next=None):
        self.val, self.next = val, next

def cycle_start(head):
    slow = fast = head
    while fast and fast.next:
        slow, fast = slow.next, fast.next.next
        if slow is fast:
            p = head
            while p is not slow:
                p, slow = p.next, slow.next
            return p
    return None

a, b, c, d, e = (ListNode(v) for v in [1, 2, 3, 4, 5])
a.next, b.next, c.next, d.next, e.next = b, c, d, e, c   # 5 points back to 3
print(cycle_start(a).val)  # 3
```

The reason (in one line): the distance from the head to the cycle start equals the distance from the meeting point to the cycle start, going round the loop. It's a classic interview "explain why" question; drawing it with distances x, y and z makes it clear.

## Remove the n-th node from the end

Give the fast pointer a head start of n nodes. When it reaches the end, the slow pointer is just before the node to remove.

```python
class ListNode:
    def __init__(self, val, next=None):
        self.val, self.next = val, next

def build(values):
    head = None
    for v in reversed(values):
        head = ListNode(v, head)
    return head

def to_list(head):
    out = []
    while head:
        out.append(head.val)
        head = head.next
    return out

def remove_nth_from_end(head, n):
    dummy = ListNode(0, head)
    fast = slow = dummy
    for _ in range(n):
        fast = fast.next
    while fast.next:
        fast, slow = fast.next, slow.next
    slow.next = slow.next.next
    return dummy.next

print(to_list(remove_nth_from_end(build([1, 2, 3, 4, 5]), 2)))  # [1, 2, 3, 5]
print(to_list(remove_nth_from_end(build([1]), 1)))              # []
```

Same idea, different speeds: here both move at speed 1, but with a fixed **gap**.

## Palindrome linked list

Find the middle, reverse the second half, then compare the halves:

```python
class ListNode:
    def __init__(self, val, next=None):
        self.val, self.next = val, next

def build(values):
    head = None
    for v in reversed(values):
        head = ListNode(v, head)
    return head

def is_palindrome(head):
    slow = fast = head
    while fast and fast.next:
        slow, fast = slow.next, fast.next.next
    prev = None
    while slow:                                   # reverse the second half
        slow.next, prev, slow = prev, slow, slow.next
    left, right = head, prev
    while right:
        if left.val != right.val:
            return False
        left, right = left.next, right.next
    return True

print(is_palindrome(build([1, 2, 2, 1])))  # True
print(is_palindrome(build([1, 2, 3])))     # False
```

O(n) time, O(1) space.

## Beyond linked lists: happy numbers

Replace a number with the sum of the squares of its digits. If you reach 1 it's "happy"; otherwise you loop forever. A loop in a sequence is a cycle, so tortoise and hare works on numbers too:

```python
def is_happy(n):
    def step(x):
        return sum(int(d) ** 2 for d in str(x))
    slow, fast = n, step(n)
    while fast != 1 and slow != fast:
        slow = step(slow)
        fast = step(step(fast))
    return fast == 1

print(is_happy(19))  # True
print(is_happy(2))   # False
```

The same trick finds the duplicate number in an array where values point to indexes (Find the Duplicate Number).

## How to recognise the pattern

- Linked list **middle**, **cycle**, or "k-th from the end".
- "Do it in one pass" or "with O(1) extra memory" on a linked list.
- A sequence where each value determines the next, and you need to detect a loop.

::tip Two pointers at different speeds find positions relative to the length (middle, k from the end) without ever computing the length.

## Interview corner

Linked List Cycle (I and II), Middle of the Linked List, Remove Nth Node From End, Palindrome Linked List, Happy Number and Find the Duplicate Number are commonly reported at **Amazon, Microsoft, Google, Meta, Apple and Goldman Sachs**. Expect the follow-up "Can you do it in O(1) space?" and "Why do they meet?"

## Common mistakes

- **Checking only `fast`** in the loop condition: you need `fast and fast.next`.
- **Comparing values instead of nodes** for cycle detection (`slow is fast`, not `slow.val == fast.val`).
- **Starting `fast` one step ahead** inconsistently, which shifts the middle.
- **Forgetting a dummy node** when the head itself may be removed.

## Exercise

1. Play the visualizer with 8 values. Which middle does slow land on?
2. Write `kth_from_end(head, k)` returning the value k nodes from the end.
3. Write `reorder(head)`: `1→2→3→4→5` becomes `1→5→2→4→3`. Hint: middle + reverse + merge.

## Summary

- Slow moves 1 step, fast moves 2: when fast ends, slow is at the middle.
- If fast ever meets slow, there's a cycle; reset one to the head to find its start.
- A fixed gap finds the k-th node from the end in one pass.
- The idea works on any "next value" sequence, not just linked lists.

Next, you'll learn the **stack**, the last-in-first-out structure behind undo buttons and bracket matching.
