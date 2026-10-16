---
title: Reverse Linked List
slug: problem-reverse-linked-list
subject: dsa-problems
lesson: 24
difficulty: beginner
level: Easy
pattern: Linked List
companies: Amazon, Microsoft, Apple, Google, Meta, Adobe, Yahoo
excerpt: Flip the direction of every arrow in a linked list with three pointers. Iterative and recursive versions.
---

The most common linked list question. Practise it until you can write it with your eyes closed.

## The problem

Given the head of a singly linked list, reverse it and return the new head.

`1 → 2 → 3 → 4 → 5` becomes `5 → 4 → 3 → 2 → 1`.

## Hints

::hint Hint 1 | Walk through the list and turn each node's `next` arrow around to point backwards.
::hint Hint 2 | Before turning an arrow, save where it pointed, or you'll lose the rest of the list.
::hint Hint 3 | Three variables: `prev`, `cur` and `nxt`.

## The easy idea: turning around a conga line

People in a conga line each hold the shoulders of the person in front. To reverse it, go person by person: remember who was in front (`nxt`), turn around to hold the person behind (`prev`), then step forward. Watch the arrows flip:

::viz reverse-list {"arr": [1, 2, 3, 4, 5]}

## Solution: iterative, O(1) space

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

def reverse_list(head):
    prev, cur = None, head
    while cur:
        nxt = cur.next       # 1. remember the rest
        cur.next = prev      # 2. flip the arrow
        prev = cur           # 3. move prev forward
        cur = nxt            # 4. move cur forward
    return prev              # prev is the new head

print(to_list(reverse_list(build([1, 2, 3, 4, 5]))))  # [5, 4, 3, 2, 1]
print(to_list(reverse_list(build([1]))))              # [1]
print(to_list(reverse_list(None)))                    # []
```

## Solution: recursive

Reverse the rest of the list, then hook the current node onto its end:

```python
class ListNode:
    def __init__(self, val, next=None):
        self.val, self.next = val, next

def reverse_recursive(head):
    if head is None or head.next is None:
        return head
    new_head = reverse_recursive(head.next)
    head.next.next = head      # the next node now points back at me
    head.next = None
    return new_head

head = ListNode(1, ListNode(2, ListNode(3)))
r = reverse_recursive(head)
print(r.val, r.next.val, r.next.next.val)  # 3 2 1
```

## Complexity

| Version | Time | Space |
|---|---|---|
| Iterative | O(n) | O(1) |
| Recursive | O(n) | O(n) call stack |

Interviewers usually prefer the iterative version; be ready to write both.

## Similar problems

Reverse Linked List II (only a section), Palindrome Linked List, Reverse Nodes in k-Group, Reorder List.

Learn the pattern: [Linked Lists](/tutorials/dsa-linked-lists/).
