---
title: "Graphs: BFS and DFS"
slug: dsa-graphs
subject: dsa
lesson: 22
difficulty: intermediate
excerpt: Model networks of friends, cities and web pages. Adjacency lists, breadth-first and depth-first search animated, connected components, shortest paths in unweighted graphs, and cycle detection.
---

A **graph** is a set of **nodes** (also called vertices) connected by **edges**. Social networks, road maps, airline routes, web links, computer networks and course prerequisites are all graphs. Trees are a special kind of graph (connected, with no cycles). Two exploration techniques, **BFS** and **DFS**, solve a huge number of graph problems.

## The metro map analogy

A metro map is a graph: stations are nodes, tracks are edges. Questions like "can I get from A to F?", "what's the fewest number of stops?" and "which stations are cut off?" are graph questions.

## Kinds of graphs

- **Undirected:** edges go both ways (friendship).
- **Directed:** edges have a direction (Instagram "follows", one-way streets).
- **Weighted:** edges have costs (distance, time, price).
- **Cyclic / acyclic:** whether you can follow edges in a loop back to where you started.

## Representing a graph: the adjacency list

Store, for each node, the list of its neighbours. A `defaultdict(list)` makes this easy:

```python
from collections import defaultdict

edges = [("A", "B"), ("A", "C"), ("B", "D"), ("C", "D"), ("C", "E"), ("D", "F"), ("E", "F")]
graph = defaultdict(list)
for a, b in edges:
    graph[a].append(b)
    graph[b].append(a)          # undirected: add both directions

print(graph["C"])  # ['A', 'D', 'E']
```

Memory is O(V + E) for V nodes and E edges. (An **adjacency matrix**, a V × V grid of True/False, uses O(V²) and only makes sense for very dense graphs.)

## Breadth-first search (BFS): explore in waves

BFS visits the start, then **all** its neighbours, then **their** neighbours… like ripples in water. A **queue** keeps the order.

::viz graph-bfs {"edges": "A-B, A-C, B-D, C-D, C-E, D-F, E-F", "start": "A"}

```python
from collections import deque, defaultdict

def bfs(graph, start):
    visited = {start}
    q = deque([start])
    order = []
    while q:
        node = q.popleft()
        order.append(node)
        for nb in graph[node]:
            if nb not in visited:
                visited.add(nb)            # mark when adding, not when popping
                q.append(nb)
    return order

graph = defaultdict(list)
for a, b in [("A", "B"), ("A", "C"), ("B", "D"), ("C", "D"), ("C", "E"), ("D", "F"), ("E", "F")]:
    graph[a].append(b)
    graph[b].append(a)
print(bfs(graph, "A"))  # ['A', 'B', 'C', 'D', 'E', 'F']
```

**Key property:** in an unweighted graph, BFS reaches every node by the **fewest edges**. That makes it the tool for "minimum number of steps".

### Shortest path length (unweighted)

```python
from collections import deque, defaultdict

def shortest_steps(graph, start, goal):
    q = deque([(start, 0)])
    visited = {start}
    while q:
        node, dist = q.popleft()
        if node == goal:
            return dist
        for nb in graph[node]:
            if nb not in visited:
                visited.add(nb)
                q.append((nb, dist + 1))
    return -1

graph = defaultdict(list)
for a, b in [("A", "B"), ("A", "C"), ("B", "D"), ("C", "D"), ("C", "E"), ("D", "F"), ("E", "F")]:
    graph[a].append(b)
    graph[b].append(a)
print(shortest_steps(graph, "A", "F"))  # 3
print(shortest_steps(graph, "A", "Z"))  # -1
```

## Depth-first search (DFS): go deep, then backtrack

DFS follows one path as far as it can, then backtracks to try other branches. Recursion (or an explicit stack) remembers the way back.

::viz graph-dfs {"edges": "A-B, A-C, B-D, C-D, C-E, D-F, E-F", "start": "A"}

```python
from collections import defaultdict

def dfs(graph, node, visited=None, order=None):
    if visited is None:
        visited, order = set(), []
    visited.add(node)
    order.append(node)
    for nb in graph[node]:
        if nb not in visited:
            dfs(graph, nb, visited, order)
    return order

graph = defaultdict(list)
for a, b in [("A", "B"), ("A", "C"), ("B", "D"), ("C", "D"), ("C", "E"), ("D", "F"), ("E", "F")]:
    graph[a].append(b)
    graph[b].append(a)
print(dfs(graph, "A"))  # ['A', 'B', 'D', 'C', 'E', 'F']
```

Iterative DFS with a stack (avoids Python's recursion limit on big graphs):

```python
def dfs_iterative(graph, start):
    visited, stack, order = set(), [start], []
    while stack:
        node = stack.pop()
        if node in visited:
            continue
        visited.add(node)
        order.append(node)
        for nb in reversed(graph[node]):    # reversed: same order as recursion
            if nb not in visited:
                stack.append(nb)
    return order

g = {"A": ["B", "C"], "B": ["A", "D"], "C": ["A", "D", "E"], "D": ["B", "C", "F"], "E": ["C", "F"], "F": ["D", "E"]}
print(dfs_iterative(g, "A"))  # ['A', 'B', 'D', 'C', 'E', 'F']
```

Both BFS and DFS are **O(V + E)**: each node and edge is handled once.

## BFS or DFS?

| Use BFS when… | Use DFS when… |
|---|---|
| You need the **shortest** path (fewest edges) | You need to explore **everything** reachable |
| Processing level by level | Detecting cycles, topological order, backtracking |
| The answer is likely close to the start | Paths, connected components, "is there a way…" |

For simply visiting everything, either works.

## Connected components

How many separate groups (friend circles, islands of network) are there? Start a traversal from every unvisited node; each start is a new component.

```python
def count_components(n, edges):
    graph = {i: [] for i in range(n)}
    for a, b in edges:
        graph[a].append(b)
        graph[b].append(a)
    visited, count = set(), 0
    for node in range(n):
        if node not in visited:
            count += 1
            stack = [node]
            while stack:
                cur = stack.pop()
                if cur in visited:
                    continue
                visited.add(cur)
                stack.extend(graph[cur])
    return count

print(count_components(5, [(0, 1), (1, 2), (3, 4)]))  # 2
```

## Cycle detection in an undirected graph

During DFS, reaching an already-visited node that isn't the one you came from means there's a cycle.

```python
def has_cycle(n, edges):
    graph = {i: [] for i in range(n)}
    for a, b in edges:
        graph[a].append(b)
        graph[b].append(a)
    visited = set()

    def dfs(node, parent):
        visited.add(node)
        for nb in graph[node]:
            if nb not in visited:
                if dfs(nb, node):
                    return True
            elif nb != parent:
                return True
        return False

    return any(dfs(i, -1) for i in range(n) if i not in visited)

print(has_cycle(4, [(0, 1), (1, 2), (2, 3)]))          # False
print(has_cycle(4, [(0, 1), (1, 2), (2, 0), (2, 3)]))  # True
```

For directed graphs, cycles are found with "currently on the path" colouring or with topological sort (lesson 24).

## Clone a graph (a classic)

Copy every node and edge. A dictionary from original → copy doubles as the visited set:

```python
class Node:
    def __init__(self, val, neighbors=None):
        self.val, self.neighbors = val, neighbors or []

def clone(node, copies=None):
    if node is None:
        return None
    if copies is None:
        copies = {}
    if node in copies:
        return copies[node]
    copy = Node(node.val)
    copies[node] = copy
    copy.neighbors = [clone(nb, copies) for nb in node.neighbors]
    return copy

a, b = Node(1), Node(2)
a.neighbors, b.neighbors = [b], [a]
c = clone(a)
print(c.val, c.neighbors[0].val, c is not a)  # 1 2 True
```

## Interview corner

Number of Islands, Clone Graph, Course Schedule, Number of Connected Components, Graph Valid Tree, Word Ladder, Rotting Oranges and Pacific Atlantic Water Flow are commonly reported at **Amazon, Google, Meta, Microsoft, Uber and Airbnb**. Interviewers expect you to choose BFS vs DFS deliberately and to state O(V + E).

::tip Mark nodes as visited when you **add** them to the queue, not when you pop them. Otherwise the same node can be queued many times.

## Common mistakes

- **Forgetting the visited set**, causing infinite loops on cycles.
- **Adding only one direction** of an undirected edge.
- **Marking visited too late** in BFS.
- **Hitting the recursion limit** with recursive DFS on huge graphs; use an explicit stack.

## Exercise

1. In the BFS visualizer, change the start to "F". Does the order still go in waves?
2. Write `can_reach(graph, a, b)` with DFS.
3. Write `bipartite(graph)`: can the nodes be coloured with 2 colours so every edge joins different colours? Hint: BFS and alternate colours.

## Summary

- A graph is nodes plus edges; store it as an adjacency list.
- BFS (queue) explores in waves and finds shortest paths in unweighted graphs.
- DFS (recursion or stack) goes deep; good for components, cycles and paths.
- Both are O(V + E); always keep a visited set.

Next, you'll apply BFS and DFS to the most common interview graphs of all: **grids**.
