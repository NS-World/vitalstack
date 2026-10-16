---
title: Linked Lists
slug: dsa-linked-lists
subject: dsa
lesson: 14
difficulty: intermediate
excerpt: Nodes connected by pointers. Build a linked list in Python, traverse, insert and delete, reverse it with three pointers (animated), merge two sorted lists, and use the dummy-node trick.
---

An array keeps items side by side in memory. A **linked list** keeps each item in its own **node**, and each node holds a **pointer** to the next one. That makes inserting and deleting cheap, but finding the 50th item slow. Linked list questions are interview favourites because they test careful pointer handling.

## The treasure hunt analogy

In a treasure hunt, each clue tells you where the **next** clue is hidden. You can't jump to clue 7; you must follow the chain from clue 1. But adding a new clue in the middle is easy: change one clue to point to the new one, and make the new one point onward.

```text
head
 ↓
[3] → [8] → [5] → [1] → None
```

## A node in Python

```python
class ListNode:
    def __init__(self, val, next=None):
        self.val = val
        self.next = next

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

head = build([3, 8, 5, 1])
print(to_list(head))  # [3, 8, 5, 1]
```

The examples below reuse `ListNode`, `build` and `to_list`; each code block repeats them so it runs on its own.

## Costs: linked list vs array

| Operation | Array | Linked list |
|---|---|---|
| Access item i | O(1) | O(n) (follow the chain) |
| Insert/delete at the front | O(n) | **O(1)** |
| Insert/delete after a known node | O(n) | **O(1)** |
| Search for a value | O(n) | O(n) |
| Extra memory per item | none | one pointer |

Real uses: browser history, music playlists, undo stacks, LRU caches, and the internals of hash maps and queues.

## Insert and delete

```python
class ListNode:
    def __init__(self, val, next=None):
        self.val, self.next = val, next

def to_list(head):
    out = []
    while head:
        out.append(head.val)
        head = head.next
    return out

head = ListNode(3, ListNode(8, ListNode(1)))

# insert 5 after 8
node8 = head.next
node8.next = ListNode(5, node8.next)
print(to_list(head))  # [3, 8, 5, 1]

# delete the node after 3 (the 8)
head.next = head.next.next
print(to_list(head))  # [3, 5, 1]
```

Order matters: when inserting, connect the **new node to the rest first**, then point the previous node at the new one. Otherwise you lose the rest of the list.

## Reverse a linked list (the classic)

Walk through the list, flipping each arrow to point backwards. You need three pointers: `prev`, `curr`, and `nxt` (to remember the rest of the list before you break the link).

::viz reverse-list {"arr": [1, 2, 3, 4, 5]}

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

def reverse(head):
    prev, curr = None, head
    while curr:
        nxt = curr.next        # 1. remember the rest
        curr.next = prev       # 2. flip the arrow
        prev = curr            # 3. move prev forward
        curr = nxt             # 4. move curr forward
    return prev                # new head

print(to_list(reverse(build([1, 2, 3, 4, 5]))))  # [5, 4, 3, 2, 1]
print(to_list(reverse(build([]))))               # []
```

O(n) time, O(1) space. Practise drawing the four steps until you can write them without thinking.

### Recursive version

```python
class ListNode:
    def __init__(self, val, next=None):
        self.val, self.next = val, next

def reverse_rec(head):
    if head is None or head.next is None:
        return head
    new_head = reverse_rec(head.next)   # reverse the rest
    head.next.next = head               # the next node now points back to me
    head.next = None
    return new_head

h = reverse_rec(ListNode(1, ListNode(2, ListNode(3))))
print(h.val, h.next.val, h.next.next.val)  # 3 2 1
```

## The dummy node trick

Many list operations have a special case for the **head** (it might be deleted, or the result list starts empty). A **dummy** node placed before the head removes the special case.

### Merge two sorted lists

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
    tail.next = a or b          # attach whatever is left
    return dummy.next

print(to_list(merge_two(build([1, 2, 4]), build([1, 3, 4]))))  # [1, 1, 2, 3, 4, 4]
```

### Remove all nodes with a value

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

def remove_value(head, val):
    dummy = ListNode(0, head)
    curr = dummy
    while curr.next:
        if curr.next.val == val:
            curr.next = curr.next.next     # skip it
        else:
            curr = curr.next
    return dummy.next

print(to_list(remove_value(build([6, 1, 6, 2, 6]), 6)))  # [1, 2]
```

Without the dummy, deleting the first 6 would need extra code.

## Doubly linked lists

Each node also points to the **previous** node. Deleting a node you already hold becomes O(1), and you can walk backwards. Python's `collections.deque` and the classic **LRU cache** (hash map + doubly linked list) use this idea.

## Interview corner

Reverse Linked List, Merge Two Sorted Lists, Remove Nth Node From End, Linked List Cycle, Reorder List, Add Two Numbers and LRU Cache are commonly reported at **Amazon, Microsoft, Meta, Google, Apple and Adobe**. Interviewers watch for null-pointer bugs: always ask "what if the list is empty or has one node?"

::tip Draw boxes and arrows on paper (or in the visualizer) before coding. Linked list bugs are almost always "I lost the rest of the list".

## Common mistakes

- **Breaking a link before saving `next`**, losing the rest of the list.
- **Forgetting `None` checks** (`curr.next.next` when `curr.next` is None).
- **Returning the old head** after reversing.
- **Special-casing the head** instead of using a dummy node.

## Exercise

1. Play the reverse visualizer with your own values, and say each of the four steps out loud.
2. Write `length(head)` and `get(head, i)`.
3. Write `delete_duplicates(head)` for a **sorted** list (`1→1→2→3→3` → `1→2→3`).
4. Write `add_two_numbers(a, b)` where digits are stored in reverse order (`2→4→3` + `5→6→4` = `7→0→8`, because 342 + 465 = 807).

## Summary

- A linked list is nodes connected by `next` pointers; access is O(n), but insert/delete at a known place is O(1).
- Reverse with `prev`, `curr`, `nxt`: save, flip, move, move.
- A dummy node removes special cases for the head.
- Always check empty and one-node lists.

Next, you'll learn the **fast and slow pointers** trick for finding middles and cycles.
