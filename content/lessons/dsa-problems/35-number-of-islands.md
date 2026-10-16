---
title: Number of Islands
slug: problem-number-of-islands
subject: dsa-problems
lesson: 35
difficulty: intermediate
level: Medium
pattern: Grids
companies: Amazon, Google, Microsoft, Meta, Uber, Bloomberg, Apple, Oracle
excerpt: Count groups of connected land in a grid. Each time you step on new land, count it and flood fill the whole island.
---

Possibly the most-asked graph question of all time, and it doesn't even look like a graph.

## The problem

A grid has `"1"` (land) and `"0"` (water). An island is land connected up, down, left or right. Count the islands.

## Example

```text
1 1 0 0 0
1 1 0 1 0
0 0 1 0 0
0 0 0 1 1
```

Answer: **4**

## Hints

::hint Hint 1 | Treat each land cell as a node connected to its land neighbours.
::hint Hint 2 | Scan the grid. When you find land you haven't visited, that's a new island.
::hint Hint 3 | Immediately "sink" the whole island with DFS or BFS so its other cells aren't counted again.

## The easy idea: the paint bucket

Scan the map like reading a book. Whenever you find unpainted land, add 1 to the counter and use the **paint bucket tool**: it spreads to every connected land cell and paints them. Painted land is never counted again. Watch it:

::viz islands {"grid": "11000, 11010, 00100, 00011"}

## Solution: DFS

```python
def num_islands(grid):
    rows, cols = len(grid), len(grid[0])

    def sink(r, c):
        if r < 0 or c < 0 or r >= rows or c >= cols or grid[r][c] != "1":
            return
        grid[r][c] = "0"
        sink(r + 1, c); sink(r - 1, c); sink(r, c + 1); sink(r, c - 1)

    count = 0
    for r in range(rows):
        for c in range(cols):
            if grid[r][c] == "1":
                count += 1
                sink(r, c)
    return count

grid = [list("11000"), list("11010"), list("00100"), list("00011")]
print(num_islands(grid))  # 4
print(num_islands([list("11110"), list("11010"), list("11000"), list("00000")]))  # 1
```

## Solution: BFS (no recursion limit)

```python
from collections import deque

def num_islands_bfs(grid):
    rows, cols = len(grid), len(grid[0])
    seen, count = set(), 0
    for r in range(rows):
        for c in range(cols):
            if grid[r][c] == "1" and (r, c) not in seen:
                count += 1
                seen.add((r, c))
                q = deque([(r, c)])
                while q:
                    cr, cc = q.popleft()
                    for nr, nc in ((cr + 1, cc), (cr - 1, cc), (cr, cc + 1), (cr, cc - 1)):
                        if 0 <= nr < rows and 0 <= nc < cols and grid[nr][nc] == "1" and (nr, nc) not in seen:
                            seen.add((nr, nc))
                            q.append((nr, nc))
    return count

print(num_islands_bfs(["11000", "11010", "00100", "00011"]))  # 4
```

This version doesn't modify the grid (uses a `seen` set), a common follow-up request.

## Complexity

- **Time O(rows × cols)**, **space O(rows × cols)** in the worst case (all land).

## Follow-ups

- **Diagonals count too?** Add the 4 diagonal directions.
- **Largest island area?** Make the fill return its size.
- **Islands added one at a time?** Union-find (Number of Islands II).

## Similar problems

Max Area of Island, Rotting Oranges, Surrounded Regions, Number of Closed Islands, Pacific Atlantic Water Flow.

Learn the pattern: [Grid Problems](/tutorials/dsa-grids/).
