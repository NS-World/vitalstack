---
title: Topological Sort
slug: dsa-topological-sort
subject: dsa
lesson: 24
difficulty: advanced
excerpt: Order tasks so every prerequisite comes first. Kahn's algorithm with in-degrees (animated), the DFS version, detecting impossible cyclic dependencies, and the course schedule problems.
---

You can't learn AI before Python, or put on shoes before socks. When tasks have **dependencies**, you need an order where every task comes **after** everything it depends on. That order is a **topological sort**, and it's how build tools, package installers, spreadsheet formulas and course planners decide what to do first.

## The getting-dressed analogy

Socks before shoes, underwear before trousers, trousers before belt, shirt before tie… Draw an arrow from each item to the item that must come after it. You get a **directed graph**. A valid dressing order lists every item only after all arrows into it have been "used".

If there were a loop (shoes before socks **and** socks before shoes), no order would be possible. So topological sort only works on a **DAG**: a **D**irected **A**cyclic **G**raph.

## Kahn's algorithm (BFS with in-degrees)

The **in-degree** of a node is the number of arrows pointing **into** it: how many things must happen before it.

1. Compute every node's in-degree.
2. Put all nodes with in-degree 0 (nothing required) in a queue.
3. Repeatedly take a node from the queue, add it to the order, and "remove" its outgoing arrows: decrease each neighbour's in-degree. When a neighbour reaches 0, it's ready: add it to the queue.
4. If the order contains every node, it's valid. If not, there's a **cycle**.

::viz topo-sort {"edges": "Math-Physics, Math-CS, CS-AI, Physics-AI, CS-Web"}

```python
from collections import deque, defaultdict

def topo_sort(n, edges):
    graph = defaultdict(list)
    indeg = [0] * n
    for a, b in edges:              # a must come before b
        graph[a].append(b)
        indeg[b] += 1
    q = deque(i for i in range(n) if indeg[i] == 0)
    order = []
    while q:
        node = q.popleft()
        order.append(node)
        for nb in graph[node]:
            indeg[nb] -= 1
            if indeg[nb] == 0:
                q.append(nb)
    return order if len(order) == n else []   # [] means a cycle

print(topo_sort(4, [(0, 1), (0, 2), (1, 3), (2, 3)]))  # [0, 1, 2, 3]
print(topo_sort(2, [(0, 1), (1, 0)]))                  # []
```

O(V + E): each node enters the queue once and each edge is processed once.

There can be **many** valid orders; any one is accepted unless the problem asks for a specific one (for example, the smallest in dictionary order, which you'd get by using a heap instead of a queue).

## Course schedule (can you finish all courses?)

`prerequisites` contains pairs `[course, required]`. Note the direction: `required` must come **before** `course`.

```python
from collections import deque, defaultdict

def can_finish(num_courses, prerequisites):
    graph = defaultdict(list)
    indeg = [0] * num_courses
    for course, req in prerequisites:
        graph[req].append(course)
        indeg[course] += 1
    q = deque(c for c in range(num_courses) if indeg[c] == 0)
    taken = 0
    while q:
        c = q.popleft()
        taken += 1
        for nxt in graph[c]:
            indeg[nxt] -= 1
            if indeg[nxt] == 0:
                q.append(nxt)
    return taken == num_courses

print(can_finish(2, [[1, 0]]))          # True
print(can_finish(2, [[1, 0], [0, 1]]))  # False
```

Course Schedule II asks for the order itself: return the list instead of the count.

## The DFS version

Do a DFS; a node is added to the order **after** all its descendants are finished (postorder). Reversing that list gives a topological order. Three colours detect cycles: unvisited, **on the current path**, finished.

```python
def topo_dfs(n, edges):
    graph = {i: [] for i in range(n)}
    for a, b in edges:
        graph[a].append(b)
    WHITE, GREY, BLACK = 0, 1, 2
    colour, order = [WHITE] * n, []

    def visit(node):
        colour[node] = GREY                 # on the current path
        for nb in graph[node]:
            if colour[nb] == GREY:
                return False                # back to the path: cycle
            if colour[nb] == WHITE and not visit(nb):
                return False
        colour[node] = BLACK
        order.append(node)
        return True

    for i in range(n):
        if colour[i] == WHITE and not visit(i):
            return []
    return order[::-1]

print(topo_dfs(4, [(0, 1), (0, 2), (1, 3), (2, 3)]))  # [0, 2, 1, 3]
print(topo_dfs(3, [(0, 1), (1, 2), (2, 0)]))          # []
```

Both versions are correct; Kahn's is usually easier to explain and naturally detects cycles by counting.

## Alien dictionary (the famous hard one)

Given words sorted in an unknown alphabet, find the letter order. Compare each pair of neighbouring words: the first differing letters give one edge ("this letter comes before that one"). Then topological sort the letters.

```python
from collections import deque

def alien_order(words):
    graph = {ch: set() for w in words for ch in w}
    indeg = {ch: 0 for ch in graph}
    for a, b in zip(words, words[1:]):
        for x, y in zip(a, b):
            if x != y:
                if y not in graph[x]:
                    graph[x].add(y)
                    indeg[y] += 1
                break
        else:
            if len(a) > len(b):
                return ""                    # "abc" before "ab" is impossible
    q = deque(sorted(ch for ch in indeg if indeg[ch] == 0))
    order = []
    while q:
        ch = q.popleft()
        order.append(ch)
        for nb in sorted(graph[ch]):
            indeg[nb] -= 1
            if indeg[nb] == 0:
                q.append(nb)
    return "".join(order) if len(order) == len(graph) else ""

print(alien_order(["wrt", "wrf", "er", "ett", "rftt"]))  # wertf
```

## How to recognise the pattern

- "Prerequisites", "dependencies", "must happen before", "build order", "schedule".
- "Is it possible to finish / install / compile everything?" (→ cycle check).
- A directed graph where you need an ordering of all nodes.

::tip Draw arrows in the direction "must come first → comes after", and double-check the direction of each input pair. Reversed edges are the most common bug.

## Interview corner

Course Schedule I and II, Alien Dictionary, Minimum Height Trees, Parallel Courses and Sequence Reconstruction are commonly reported at **Amazon, Google, Meta, Microsoft, Uber and Airbnb**. Interviewers expect you to mention cycle detection: "If not every node gets into the order, there's a cycle, so it's impossible."

## Common mistakes

- **Reversing edge direction** from the input pairs.
- **Forgetting nodes with no edges** (they still belong in the order).
- **Not checking for cycles** before returning an order.
- **Using undirected graph logic** on a directed problem.

## Exercise

1. In the visualizer, add the edge `AI-Math`. What happens, and why?
2. Write `build_order(projects, deps)` returning a valid build order or `None`.
3. Modify Kahn's algorithm to return the lexicographically smallest order. Hint: replace the queue with a heap.

## Summary

- Topological sort orders a DAG so every edge goes from earlier to later.
- Kahn's algorithm: start with in-degree 0 nodes, remove edges, add nodes that become ready.
- If the order misses nodes, the graph has a cycle and no order exists.
- Both BFS (Kahn) and DFS (reverse postorder) run in O(V + E).

Next, you'll add weights to edges and find the cheapest routes with **Dijkstra's algorithm**.
