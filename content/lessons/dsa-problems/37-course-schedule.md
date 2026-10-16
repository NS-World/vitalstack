---
title: Course Schedule
slug: problem-course-schedule
subject: dsa-problems
lesson: 37
difficulty: intermediate
level: Medium
pattern: Topological Sort
companies: Amazon, Google, Meta, Microsoft, Uber, Apple, Airbnb
excerpt: Can you finish every course given the prerequisites? Only if there's no cycle. Kahn's algorithm takes courses as soon as they're unlocked.
---

A dependency problem in disguise: it's really asking "does this directed graph have a cycle?"

## The problem

There are `n` courses labelled 0 to n−1. `prerequisites[i] = [a, b]` means you must take `b` before `a`. Return `True` if you can finish all courses.

## Examples

| n | prerequisites | answer |
|---|---|---|
| 2 | `[[1, 0]]` | True (take 0, then 1) |
| 2 | `[[1, 0], [0, 1]]` | False (each needs the other) |
| 4 | `[[1, 0], [2, 0], [3, 1], [3, 2]]` | True |

## Hints

::hint Hint 1 | Draw an arrow b → a for each pair. You can finish everything only if the arrows never form a loop.
::hint Hint 2 | Count how many prerequisites each course still needs (its in-degree). Courses needing 0 can be taken right now.
::hint Hint 3 | Taking a course unlocks others: decrease their counts. If you manage to take all n courses, the answer is True.

## The easy idea: unlocking levels in a game

In a game, some levels unlock only after others are beaten. Start with every level that's already unlocked. Each time you beat one, cross it off the requirements of the levels it unlocks. If you eventually beat every level, there was no impossible loop. Watch it:

::viz topo-sort {"edges": "Math-Physics, Math-CS, CS-AI, Physics-AI, CS-Web"}

## Solution: Kahn's algorithm

```python
from collections import deque

def can_finish(n, prerequisites):
    unlocks = [[] for _ in range(n)]
    needs = [0] * n
    for course, req in prerequisites:
        unlocks[req].append(course)
        needs[course] += 1
    q = deque(c for c in range(n) if needs[c] == 0)
    taken = 0
    while q:
        c = q.popleft()
        taken += 1
        for nxt in unlocks[c]:
            needs[nxt] -= 1
            if needs[nxt] == 0:
                q.append(nxt)
    return taken == n

print(can_finish(2, [[1, 0]]))                          # True
print(can_finish(2, [[1, 0], [0, 1]]))                  # False
print(can_finish(4, [[1, 0], [2, 0], [3, 1], [3, 2]]))  # True
```

## Course Schedule II: return the order

Same code; collect the courses as you take them:

```python
from collections import deque

def find_order(n, prerequisites):
    unlocks = [[] for _ in range(n)]
    needs = [0] * n
    for course, req in prerequisites:
        unlocks[req].append(course)
        needs[course] += 1
    q = deque(c for c in range(n) if needs[c] == 0)
    order = []
    while q:
        c = q.popleft()
        order.append(c)
        for nxt in unlocks[c]:
            needs[nxt] -= 1
            if needs[nxt] == 0:
                q.append(nxt)
    return order if len(order) == n else []

print(find_order(4, [[1, 0], [2, 0], [3, 1], [3, 2]]))  # [0, 1, 2, 3]
print(find_order(2, [[1, 0], [0, 1]]))                  # []
```

## Complexity

- **Time O(V + E)** for V courses and E prerequisites.
- **Space O(V + E)** for the graph.

## Watch out

The pair `[a, b]` means **b first**. Getting the arrow direction backwards is the most common bug.

## Similar problems

Course Schedule II, Alien Dictionary, Minimum Height Trees, Parallel Courses, Find All Possible Recipes.

Learn the pattern: [Topological Sort](/tutorials/dsa-topological-sort/).
