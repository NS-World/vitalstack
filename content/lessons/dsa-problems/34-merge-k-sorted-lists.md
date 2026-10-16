---
title: Merge k Sorted Lists
slug: problem-merge-k-sorted-lists
subject: dsa-problems
lesson: 34
difficulty: advanced
level: Hard
pattern: Heap
companies: Amazon, Meta, Google, Microsoft, Uber, LinkedIn, Airbnb
excerpt: Merge many sorted linked lists into one. A min-heap always tells you which list has the next smallest value.
---

Merging 2 sorted lists is easy. With k lists, a **heap** decides which list goes next.

## The problem

Given k sorted linked lists, merge them into one sorted linked list.

## Example

```text
1 → 4 → 5
1 → 3 → 4
2 → 6
merged: 1 → 1 → 2 → 3 → 4 → 4 → 5 → 6
```

## Hints

::hint Hint 1 | At each step, the next node is the smallest among the k current heads.
::hint Hint 2 | Finding the minimum of k heads by scanning costs O(k). A min-heap does it in O(log k).
::hint Hint 3 | Put (value, list index, node) in the heap; the index breaks ties so nodes are never compared.

## The easy idea: k checkout lines

Several sorted queues of people (shortest first) merge into one door. A supervisor watches only the **front person** of every queue and always lets the shortest of them through; then that queue's next person steps up. A min-heap is the supervisor.

## Solution

```python
import heapq

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

def merge_k_lists(lists):
    heap = [(node.val, i, node) for i, node in enumerate(lists) if node]
    heapq.heapify(heap)
    dummy = tail = ListNode(0)
    while heap:
        val, i, node = heapq.heappop(heap)
        tail.next = node
        tail = node
        if node.next:
            heapq.heappush(heap, (node.next.val, i, node.next))
    return dummy.next

lists = [build([1, 4, 5]), build([1, 3, 4]), build([2, 6])]
print(to_list(merge_k_lists(lists)))  # [1, 1, 2, 3, 4, 4, 5, 6]
print(to_list(merge_k_lists([])))     # []
```

## Alternative: divide and conquer

Merge the lists in pairs (like merge sort): k lists → k/2 → k/4 → … → 1. Same O(N log k) time, no heap needed.

```python
def merge_two(a, b):
    out, i, j = [], 0, 0
    while i < len(a) and j < len(b):
        if a[i] <= b[j]:
            out.append(a[i]); i += 1
        else:
            out.append(b[j]); j += 1
    return out + a[i:] + b[j:]

def merge_k_pairs(lists):
    if not lists:
        return []
    while len(lists) > 1:
        lists = [merge_two(lists[i], lists[i + 1]) if i + 1 < len(lists) else lists[i]
                 for i in range(0, len(lists), 2)]
    return lists[0]

print(merge_k_pairs([[1, 4, 5], [1, 3, 4], [2, 6]]))  # [1, 1, 2, 3, 4, 4, 5, 6]
```

## Complexity

- **Time O(N log k)** for N total nodes: each node is pushed and popped once, each heap operation is O(log k).
- **Space O(k)** for the heap.

Merging lists one by one into a growing result is O(N · k): mention why the heap is better.

## Similar problems

Merge Two Sorted Lists, Kth Smallest Element in a Sorted Matrix, Find K Pairs with Smallest Sums, Smallest Range Covering Elements from K Lists.

Learn the pattern: [Heaps](/tutorials/dsa-heaps/).
