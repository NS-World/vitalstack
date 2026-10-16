---
title: Merge Two Sorted Lists
slug: problem-merge-two-sorted-lists
subject: dsa-problems
lesson: 25
difficulty: beginner
level: Easy
pattern: Linked List
companies: Amazon, Microsoft, Apple, Google, Meta, Adobe, Bloomberg
excerpt: Zip two sorted linked lists into one sorted list. The dummy node trick removes every special case.
---

This problem introduces the **dummy node**, a trick that simplifies almost every linked list problem.

## The problem

Merge two sorted linked lists into one sorted list, reusing the existing nodes, and return its head.

## Examples

| list1 | list2 | merged |
|---|---|---|
| 1 → 2 → 4 | 1 → 3 → 4 | 1 → 1 → 2 → 3 → 4 → 4 |
| empty | empty | empty |
| empty | 0 | 0 |

## Hints

::hint Hint 1 | Compare the two front nodes; the smaller one goes next in the result.
::hint Hint 2 | Start with a fake "dummy" node so you never have to special-case the first node.
::hint Hint 3 | When one list runs out, attach the rest of the other list in one step.

## The easy idea: merging two sorted queues

Two queues of people sorted by height merge into one door. Each time, the shorter of the two people at the front goes through. When one queue is empty, the rest of the other walks straight through. It's the merge step of merge sort.

The **dummy node** is like a doorman standing at the front of the new queue: you always have something to attach to, and at the end you return `dummy.next`.

## Solution

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

def merge_two(a, b):
    dummy = tail = ListNode(0)
    while a and b:
        if a.val <= b.val:
            tail.next, a = a, a.next
        else:
            tail.next, b = b, b.next
        tail = tail.next
    tail.next = a or b              # attach whatever is left
    return dummy.next

print(to_list(merge_two(build([1, 2, 4]), build([1, 3, 4]))))  # [1, 1, 2, 3, 4, 4]
print(to_list(merge_two(build([]), build([]))))                # []
print(to_list(merge_two(build([]), build([0]))))               # [0]
```

## Complexity

- **Time O(n + m)**, **space O(1)** (nodes are reused, only pointers change).

## Recursive version

```python
class ListNode:
    def __init__(self, val, next=None):
        self.val, self.next = val, next

def merge_rec(a, b):
    if not a or not b:
        return a or b
    if a.val <= b.val:
        a.next = merge_rec(a.next, b)
        return a
    b.next = merge_rec(a, b.next)
    return b

m = merge_rec(ListNode(1, ListNode(5)), ListNode(2))
print(m.val, m.next.val, m.next.next.val)  # 1 2 5
```

Elegant, but uses O(n + m) stack space.

## Similar problems

Merge k Sorted Lists, Merge Sorted Array, Sort List (merge sort on a linked list), Add Two Numbers.

Learn the pattern: [Linked Lists](/tutorials/dsa-linked-lists/).
