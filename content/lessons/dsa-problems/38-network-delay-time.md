---
title: Network Delay Time
slug: problem-network-delay-time
subject: dsa-problems
lesson: 38
difficulty: intermediate
level: Medium
pattern: Graphs
companies: Google, Amazon, Microsoft, Uber, Akamai
excerpt: How long until a signal reaches every computer? Dijkstra's shortest paths, then take the slowest arrival.
---

The friendliest Dijkstra problem: compute all shortest paths from one source, then read off the biggest.

## The problem

`times[i] = [u, v, w]` means a signal from node u reaches node v after w time units (one-way). Nodes are labelled 1 to n. A signal is sent from node `k`. Return how long until **all** nodes receive it, or -1 if some never do.

## Examples

| times | n | k | answer |
|---|---|---|---|
| `[[2,1,1],[2,3,1],[3,4,1]]` | 4 | 2 | 2 |
| `[[1,2,1]]` | 2 | 1 | 1 |
| `[[1,2,1]]` | 2 | 2 | -1 |

## Hints

::hint Hint 1 | Every node receives the signal at its SHORTEST distance from k.
::hint Hint 2 | Weighted edges with non-negative weights → Dijkstra with a min-heap.
::hint Hint 3 | The answer is the largest of those shortest distances (if every node was reached).

## The easy idea: rumour spreading

A rumour starts in one town. It always reaches the nearest town that hasn't heard it yet next. Once a town hears it, that time is final. The rumour is "complete" when the last town hears it. A heap keeps the next-closest town ready. Watch Dijkstra:

::viz dijkstra {"edges": "A-B:4, A-C:2, C-B:1, B-D:5, C-D:8, C-E:10, D-E:2", "start": "A"}

## Solution

```python
import heapq
from collections import defaultdict

def network_delay_time(times, n, k):
    graph = defaultdict(list)
    for u, v, w in times:
        graph[u].append((v, w))
    arrived = {}                          # node -> final time
    heap = [(0, k)]
    while heap:
        t, node = heapq.heappop(heap)
        if node in arrived:
            continue                      # already reached faster
        arrived[node] = t
        for nb, w in graph[node]:
            if nb not in arrived:
                heapq.heappush(heap, (t + w, nb))
    return max(arrived.values()) if len(arrived) == n else -1

print(network_delay_time([[2, 1, 1], [2, 3, 1], [3, 4, 1]], 4, 2))  # 2
print(network_delay_time([[1, 2, 1]], 2, 1))                        # 1
print(network_delay_time([[1, 2, 1]], 2, 2))                        # -1
print(network_delay_time([[1, 2, 4], [1, 3, 1], [3, 2, 1]], 3, 1))  # 2
```

The last example shows why plain BFS fails: the direct edge 1 → 2 costs 4, but 1 → 3 → 2 costs only 2.

## Complexity

- **Time O(E log V)** with a heap.
- **Space O(V + E).**

## Similar problems

Cheapest Flights Within K Stops, Path With Minimum Effort, Path with Maximum Probability, Swim in Rising Water.

Learn the pattern: [Shortest Paths](/tutorials/dsa-shortest-paths/).
