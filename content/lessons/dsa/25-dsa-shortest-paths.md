---
title: Shortest Paths (Dijkstra)
slug: dsa-shortest-paths
subject: dsa
lesson: 25
difficulty: advanced
excerpt: Find the cheapest route in a weighted graph. Dijkstra's algorithm with a heap (animated), why it needs non-negative weights, network delay time, cheapest flights with k stops, and union-find for connectivity.
---

BFS finds the path with the **fewest edges**. But real maps have **costs**: 5 km on one road, 2 km on another, a toll here, traffic there. To find the **cheapest** path in a weighted graph, use **Dijkstra's algorithm**, the idea behind map apps and network routing.

## The rumour spreading analogy

A rumour starts in your town at time 0. Roads take different times to travel. The rumour always reaches the **closest** not-yet-reached town next. Once a town hears it, that time is final: nothing can arrive earlier by a longer road, because all times are positive.

Dijkstra simulates exactly that: repeatedly finalise the closest unfinished node, then update its neighbours.

::viz dijkstra {"edges": "A-B:4, A-C:2, C-B:1, B-D:5, C-D:8, C-E:10, D-E:2", "start": "A"}

Notice A → C → B costs 3, cheaper than the direct road A → B costing 4.

## The algorithm

1. Set `dist[start] = 0`, everything else infinity.
2. Use a **min-heap** of `(distance, node)`. Pop the closest node.
3. If it was already finalised with a smaller distance, skip it (an old heap entry).
4. For each neighbour, if going through this node is cheaper, update its distance and push it.

```python
import heapq
from collections import defaultdict

def dijkstra(graph, start):
    dist = {start: 0}
    heap = [(0, start)]
    while heap:
        d, node = heapq.heappop(heap)
        if d > dist[node]:
            continue                          # stale entry
        for nb, w in graph[node]:
            nd = d + w
            if nd < dist.get(nb, float("inf")):
                dist[nb] = nd
                heapq.heappush(heap, (nd, nb))
    return dist

graph = defaultdict(list)
for a, b, w in [("A", "B", 4), ("A", "C", 2), ("C", "B", 1), ("B", "D", 5), ("C", "D", 8), ("C", "E", 10), ("D", "E", 2)]:
    graph[a].append((b, w))
    graph[b].append((a, w))
print(dict(sorted(dijkstra(graph, "A").items())))  # {'A': 0, 'B': 3, 'C': 2, 'D': 8, 'E': 10}
```

**Time: O((V + E) log V)** with a heap.

### Why no negative weights?

Dijkstra trusts that once a node is the closest, no later path can be shorter. A negative edge could break that promise (a longer route might suddenly become cheaper). For graphs with negative weights, use **Bellman-Ford** (O(V·E)), which relaxes every edge V−1 times.

## Recovering the actual path

Remember each node's **previous** node when you improve its distance, then walk backwards from the goal:

```python
import heapq
from collections import defaultdict

def shortest_path(graph, start, goal):
    dist, prev = {start: 0}, {}
    heap = [(0, start)]
    while heap:
        d, node = heapq.heappop(heap)
        if node == goal:
            break
        if d > dist[node]:
            continue
        for nb, w in graph[node]:
            if d + w < dist.get(nb, float("inf")):
                dist[nb], prev[nb] = d + w, node
                heapq.heappush(heap, (d + w, nb))
    path, cur = [], goal
    while cur in prev or cur == start:
        path.append(cur)
        if cur == start:
            break
        cur = prev[cur]
    return path[::-1], dist.get(goal)

graph = defaultdict(list)
for a, b, w in [("A", "B", 4), ("A", "C", 2), ("C", "B", 1), ("B", "D", 5), ("C", "D", 8), ("C", "E", 10), ("D", "E", 2)]:
    graph[a].append((b, w))
    graph[b].append((a, w))
print(shortest_path(graph, "A", "E"))  # (['A', 'C', 'B', 'D', 'E'], 10)
```

## Network delay time

A signal is sent from node k; edges are directed with travel times. How long until every node receives it? That's the **largest** shortest distance:

```python
import heapq
from collections import defaultdict

def network_delay(times, n, k):
    graph = defaultdict(list)
    for u, v, w in times:
        graph[u].append((v, w))
    dist = {}
    heap = [(0, k)]
    while heap:
        d, node = heapq.heappop(heap)
        if node in dist:
            continue
        dist[node] = d                        # first pop = final distance
        for nb, w in graph[node]:
            if nb not in dist:
                heapq.heappush(heap, (d + w, nb))
    return max(dist.values()) if len(dist) == n else -1

print(network_delay([[2, 1, 1], [2, 3, 1], [3, 4, 1]], 4, 2))  # 2
```

This "finalise on first pop" style is a neat alternative way to write Dijkstra.

## Cheapest flights within k stops

With a limit on the number of stops, plain Dijkstra isn't enough. A simple, reliable method is **Bellman-Ford limited to k + 1 rounds**: in each round, relax every flight using the previous round's prices.

```python
def cheapest_flight(n, flights, src, dst, k):
    INF = float("inf")
    price = [INF] * n
    price[src] = 0
    for _ in range(k + 1):                    # at most k stops = k + 1 flights
        new = price[:]
        for u, v, w in flights:
            if price[u] + w < new[v]:
                new[v] = price[u] + w
        price = new
    return -1 if price[dst] == INF else price[dst]

flights = [[0, 1, 100], [1, 2, 100], [2, 3, 100], [0, 2, 500]]
print(cheapest_flight(4, flights, 0, 3, 1))  # 600
print(cheapest_flight(4, flights, 0, 3, 2))  # 300
```

Copying `price` each round guarantees we use at most one more flight per round.

## Bonus tool: union-find (disjoint set)

Not a shortest-path algorithm, but often used alongside graph problems: it tracks **which nodes are connected** as edges are added, nearly in O(1) per operation.

```python
class UnionFind:
    def __init__(self, n):
        self.parent = list(range(n))

    def find(self, x):
        while self.parent[x] != x:
            self.parent[x] = self.parent[self.parent[x]]   # path compression
            x = self.parent[x]
        return x

    def union(self, a, b):
        ra, rb = self.find(a), self.find(b)
        if ra == rb:
            return False                 # already connected: this edge makes a cycle
        self.parent[ra] = rb
        return True

uf = UnionFind(5)
print(uf.union(0, 1), uf.union(1, 2), uf.union(0, 2))  # True True False
print(uf.find(0) == uf.find(2))                        # True
```

Union-find powers **Kruskal's minimum spanning tree** (connect all cities with the least total road), "redundant connection" and "number of provinces".

## Which shortest-path tool?

| Graph | Tool | Time |
|---|---|---|
| Unweighted | BFS | O(V + E) |
| Non-negative weights | Dijkstra (heap) | O((V + E) log V) |
| Negative weights or "at most k edges" | Bellman-Ford | O(V · E) or O(k · E) |
| Weights 0 or 1 only | 0-1 BFS with a deque | O(V + E) |
| All pairs, small V | Floyd-Warshall | O(V³) |

## Interview corner

Network Delay Time, Cheapest Flights Within K Stops, Path With Minimum Effort, Swim in Rising Water, Redundant Connection and Min Cost to Connect All Points are commonly reported at **Google, Amazon, Uber, Meta, Microsoft and Airbnb**. Interviewers like to hear why Dijkstra fails with negative weights and why you skip stale heap entries.

::tip Dijkstra is "BFS with a priority queue". If all weights are equal, it behaves exactly like BFS.

## Common mistakes

- **Not skipping stale heap entries**, which makes the algorithm much slower.
- **Using Dijkstra with negative weights.**
- **Forgetting directed vs undirected** when building the graph.
- **Updating `price` in place** in limited-stops Bellman-Ford (allows too many flights per round).

## Exercise

1. In the visualizer, change `C-B:1` to `C-B:5`. Which route to B wins now?
2. Write `min_effort_path(heights)`: the path from top-left to bottom-right minimising the biggest height difference between steps. Hint: Dijkstra where the "distance" is the max difference so far.
3. Use union-find to count connected components for `n = 6` and edges `[(0,1), (2,3), (3,4)]`.

## Summary

- Dijkstra finds cheapest paths with non-negative weights using a min-heap: O((V + E) log V).
- Skip stale entries; keep `prev` pointers to rebuild the path.
- Bellman-Ford handles negative weights and "at most k edges" limits.
- Union-find tracks connectivity and detects cycles as edges are added.

Next, you'll learn the most feared and most rewarding topic in interviews: **dynamic programming**.
