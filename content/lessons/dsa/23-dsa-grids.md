---
title: Grid Problems (Flood Fill and BFS)
slug: dsa-grids
subject: dsa
lesson: 23
difficulty: intermediate
excerpt: Treat a 2D grid as a graph. Count islands with flood fill (animated), find the largest area, spread through a grid with multi-source BFS like rotting oranges, and find shortest paths in a maze.
---

A huge share of interview "graph" problems are actually **grids**: maps of land and water, mazes, game boards, images. The trick is to see a grid as a graph: **every cell is a node**, and it's connected to its neighbours **up, down, left and right**. Then everything from the previous lesson applies.

## The paint bucket analogy

In a paint program, the **bucket tool** fills a whole region of the same colour with one click. It starts at the clicked pixel and spreads to every touching pixel of the same colour, and from those to their neighbours… That's **flood fill**, a DFS or BFS on a grid.

## Moving around a grid

```python
DIRS = [(1, 0), (-1, 0), (0, 1), (0, -1)]   # down, up, right, left

def neighbours(grid, r, c):
    for dr, dc in DIRS:
        nr, nc = r + dr, c + dc
        if 0 <= nr < len(grid) and 0 <= nc < len(grid[0]):
            yield nr, nc

grid = [[0, 0, 0], [0, 0, 0]]
print(list(neighbours(grid, 0, 0)))  # [(1, 0), (0, 1)]
```

The bounds check (`0 <= nr < rows`) appears in every grid solution. Some problems also allow the 4 diagonal moves: add `(1, 1), (1, -1), (-1, 1), (-1, -1)`.

## Number of islands

`1` is land and `0` is water. An island is a group of land cells connected up/down/left/right. Scan the grid; each time you find unvisited land, count a new island and **sink** (flood fill) all of it so it's never counted again.

::viz islands {"grid": "11000, 11010, 00100, 00011"}

```python
def num_islands(grid):
    rows, cols = len(grid), len(grid[0])

    def sink(r, c):
        if r < 0 or c < 0 or r >= rows or c >= cols or grid[r][c] != "1":
            return
        grid[r][c] = "0"                      # mark visited by sinking
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
```

O(rows × cols): every cell is visited a constant number of times. Changing the grid in place saves memory; if you mustn't modify the input, use a `visited` set instead.

## Largest island area

Make the flood fill **return** how many cells it sank:

```python
def max_area(grid):
    rows, cols = len(grid), len(grid[0])

    def area(r, c):
        if r < 0 or c < 0 or r >= rows or c >= cols or grid[r][c] != 1:
            return 0
        grid[r][c] = 0
        return 1 + area(r + 1, c) + area(r - 1, c) + area(r, c + 1) + area(r, c - 1)

    return max((area(r, c) for r in range(rows) for c in range(cols)), default=0)

grid = [[0, 1, 1, 0], [0, 1, 0, 0], [1, 0, 0, 1], [1, 1, 0, 1]]
print(max_area(grid))  # 3
```

## Shortest path in a maze (BFS)

DFS finds **a** path; BFS finds the **shortest** one. Count steps level by level:

```python
from collections import deque

def shortest_path(maze, start, goal):
    rows, cols = len(maze), len(maze[0])
    q = deque([(start[0], start[1], 0)])
    seen = {start}
    while q:
        r, c, d = q.popleft()
        if (r, c) == goal:
            return d
        for dr, dc in [(1, 0), (-1, 0), (0, 1), (0, -1)]:
            nr, nc = r + dr, c + dc
            if 0 <= nr < rows and 0 <= nc < cols and maze[nr][nc] == "." and (nr, nc) not in seen:
                seen.add((nr, nc))
                q.append((nr, nc, d + 1))
    return -1

maze = ["..#.",
        ".##.",
        "....",
        "#.#."]
print(shortest_path(maze, (0, 0), (0, 3)))  # 7
```

## Multi-source BFS: rotting oranges

Every minute, rotten oranges (2) rot their fresh neighbours (1). How many minutes until no fresh orange is left? Start BFS from **all rotten oranges at once**; each BFS level is one minute.

```python
from collections import deque

def oranges_rotting(grid):
    rows, cols = len(grid), len(grid[0])
    q, fresh = deque(), 0
    for r in range(rows):
        for c in range(cols):
            if grid[r][c] == 2:
                q.append((r, c))
            elif grid[r][c] == 1:
                fresh += 1
    minutes = 0
    while q and fresh:
        for _ in range(len(q)):               # one minute = one level
            r, c = q.popleft()
            for dr, dc in [(1, 0), (-1, 0), (0, 1), (0, -1)]:
                nr, nc = r + dr, c + dc
                if 0 <= nr < rows and 0 <= nc < cols and grid[nr][nc] == 1:
                    grid[nr][nc] = 2
                    fresh -= 1
                    q.append((nr, nc))
        minutes += 1
    return minutes if fresh == 0 else -1

print(oranges_rotting([[2, 1, 1], [1, 1, 0], [0, 1, 1]]))  # 4
print(oranges_rotting([[2, 1, 1], [0, 1, 1], [1, 0, 1]]))  # -1
```

The same multi-source idea solves "distance from each cell to the nearest 0", "walls and gates" and fire-spreading problems.

## Flood fill (the paint bucket itself)

```python
def flood_fill(image, sr, sc, new):
    old = image[sr][sc]
    if old == new:
        return image
    def fill(r, c):
        if 0 <= r < len(image) and 0 <= c < len(image[0]) and image[r][c] == old:
            image[r][c] = new
            fill(r + 1, c); fill(r - 1, c); fill(r, c + 1); fill(r, c - 1)
    fill(sr, sc)
    return image

print(flood_fill([[1, 1, 1], [1, 1, 0], [1, 0, 1]], 1, 1, 2))  # [[2, 2, 2], [2, 2, 0], [2, 0, 1]]
```

The `old == new` check prevents an infinite loop.

## How to recognise the pattern

- A 2D grid or matrix with "connected", "region", "island", "spread", "maze", "steps".
- "Minimum steps / minutes" → BFS (multi-source if many starting points).
- "Count groups / largest group" → flood fill with DFS or BFS.

::tip For grid BFS, put `(row, col, distance)` in the queue, or process the queue level by level and count levels.

## Interview corner

Number of Islands is one of the most reported interview questions anywhere, especially at **Amazon, Google, Microsoft, Meta and Uber**, along with Max Area of Island, Rotting Oranges, Flood Fill, Surrounded Regions, Shortest Path in Binary Matrix and Word Search. Expect follow-ups: "Can you do it without modifying the grid?" and "What about diagonals?"

## Common mistakes

- **Missing the bounds check** (or checking it after indexing into the grid).
- **Not marking cells visited**, so they're counted again or loop forever.
- **Using DFS for a shortest-path question.**
- **Starting multi-source BFS from only one source.**

## Exercise

1. In the visualizer, try `111, 101, 111` (a ring). How many islands? Then `101, 010, 101`.
2. Write `island_perimeter(grid)` for a grid with exactly one island.
3. Write `nearest_zero(grid)`: for every cell, the distance to the nearest 0. Hint: multi-source BFS from all 0s.

## Summary

- A grid is a graph: cells are nodes, up/down/left/right are edges.
- Flood fill (DFS/BFS) counts and measures regions: O(rows × cols).
- BFS gives shortest paths; multi-source BFS models spreading over time.
- Always check bounds and mark visited cells.

Next, you'll order tasks with dependencies using **topological sort**.
