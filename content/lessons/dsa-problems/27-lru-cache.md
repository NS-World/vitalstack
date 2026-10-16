---
title: LRU Cache
slug: problem-lru-cache
subject: dsa-problems
lesson: 27
difficulty: intermediate
level: Medium
pattern: Hashing
companies: Amazon, Microsoft, Meta, Google, Apple, Oracle, Uber, Salesforce
excerpt: Design a cache that throws out the least recently used item when full, with O(1) get and put. A hash map plus an ordered list.
---

One of the most-asked **design** questions. It combines a hash map (fast lookup) with an ordering structure (who was used last).

## The problem

Build `LRUCache(capacity)` with:

- `get(key)`: return the value, or -1 if missing. Using a key makes it "recently used".
- `put(key, value)`: insert or update. If the cache is over capacity, remove the **least recently used** key.

Both must be **O(1)**.

## Example

```text
cache = LRUCache(2)
put(1, 1); put(2, 2)
get(1)    → 1     (1 is now the most recent)
put(3, 3)         (full: evict 2, the least recent)
get(2)    → -1
put(4, 4)         (evict 1)
get(1)    → -1
get(3)    → 3
get(4)    → 4
```

## Hints

::hint Hint 1 | A dictionary gives O(1) lookup, but doesn't know which key is oldest.
::hint Hint 2 | You need an order you can update in O(1): move an item to the "recent" end, remove from the "old" end.
::hint Hint 3 | A doubly linked list (or Python's OrderedDict, which is built on one) does exactly that.

## The easy idea: a pile of books on your desk

Every time you use a book, you put it on **top** of the pile. When the desk is full, the book at the **bottom** (unused for longest) goes back to the shelf. The dictionary tells you instantly where each book is in the pile.

## Solution 1: OrderedDict (great to mention first)

```python
from collections import OrderedDict

class LRUCache:
    def __init__(self, capacity):
        self.capacity = capacity
        self.data = OrderedDict()

    def get(self, key):
        if key not in self.data:
            return -1
        self.data.move_to_end(key)          # now most recent
        return self.data[key]

    def put(self, key, value):
        self.data[key] = value
        self.data.move_to_end(key)
        if len(self.data) > self.capacity:
            self.data.popitem(last=False)   # remove least recent

cache = LRUCache(2)
cache.put(1, 1)
cache.put(2, 2)
print(cache.get(1))  # 1
cache.put(3, 3)
print(cache.get(2))  # -1
cache.put(4, 4)
print(cache.get(1), cache.get(3), cache.get(4))  # -1 3 4
```

## Solution 2: build it yourself (what interviewers often want)

A dictionary maps keys to nodes of a doubly linked list. Two dummy nodes, `head` (oldest side) and `tail` (newest side), remove all special cases.

```python
class Node:
    def __init__(self, key=0, val=0):
        self.key, self.val = key, val
        self.prev = self.next = None

class LRU:
    def __init__(self, capacity):
        self.cap, self.map = capacity, {}
        self.head, self.tail = Node(), Node()
        self.head.next, self.tail.prev = self.tail, self.head

    def _remove(self, node):
        node.prev.next, node.next.prev = node.next, node.prev

    def _add_newest(self, node):
        node.prev, node.next = self.tail.prev, self.tail
        self.tail.prev.next = node
        self.tail.prev = node

    def get(self, key):
        if key not in self.map:
            return -1
        node = self.map[key]
        self._remove(node)
        self._add_newest(node)
        return node.val

    def put(self, key, value):
        if key in self.map:
            self._remove(self.map[key])
        node = Node(key, value)
        self.map[key] = node
        self._add_newest(node)
        if len(self.map) > self.cap:
            oldest = self.head.next
            self._remove(oldest)
            del self.map[oldest.key]

c = LRU(2)
c.put(1, 1)
c.put(2, 2)
print(c.get(1))  # 1
c.put(3, 3)
print(c.get(2))  # -1
print(c.get(3))  # 3
```

The node stores its **key** so that when it's evicted from the list, you can also delete it from the dictionary.

## Complexity

- `get` and `put`: **O(1)**.
- Space: **O(capacity)**.

## Similar problems

LFU Cache, Design Twitter, Time Based Key-Value Store, Insert Delete GetRandom O(1).

Learn the patterns: [Hashing](/tutorials/dsa-hashing/) and [Linked Lists](/tutorials/dsa-linked-lists/).
