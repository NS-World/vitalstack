---
title: Rotting Oranges
slug: problem-rotting-oranges
subject: dsa-problems
lesson: 36
difficulty: intermediate
level: Medium
pattern: Grids
companies: Amazon, Microsoft, Google, Meta, Bloomberg
excerpt: How many minutes until every orange rots? Multi-source BFS where each level of the queue is one minute.
---

This problem teaches **multi-source BFS**: start the search from many places at once.

## The problem

In a grid, `0` is empty, `1` is a fresh orange and `2` is a rotten orange. Every minute, each rotten orange rots its fresh neighbours (up, down, left, right). Return the minutes until no fresh orange remains, or -1 if that's impossible.

## Examples

| grid | answer |
|---|---|
| `[[2,1,1],[1,1,0],[0,1,1]]` | 4 |
| `[[2,1,1],[0,1,1],[1,0,1]]` | -1 (bottom-left orange is unreachable) |
| `[[0,2]]` | 0 |

## Hints

::hint Hint 1 | The rot spreads in waves, like ripples. Waves = BFS levels.
::hint Hint 2 | Put ALL rotten oranges in the queue at the start, not just one.
::hint Hint 3 | Count fresh oranges first. At the end, if any are left, return -1.

## The easy idea: ripples from many stones

Throw several stones into a pond at once. Ripples spread from all of them simultaneously, one ring per second. The time until the whole pond is rippled is the number of rings. Each rotten orange is a stone; each BFS level is one minute.

```text
minute 0    minute 1    minute 2    minute 3    minute 4
2 1 1       2 2 1       2 2 2       2 2 2       2 2 2
1 1 0       2 1 0       2 2 0       2 2 0       2 2 0
0 1 1       0 1 1       0 1 1       0 2 1       0 2 2
```

## Solution

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
        for _ in range(len(q)):                 # one minute
            r, c = q.popleft()
            for nr, nc in ((r + 1, c), (r - 1, c), (r, c + 1), (r, c - 1)):
                if 0 <= nr < rows and 0 <= nc < cols and grid[nr][nc] == 1:
                    grid[nr][nc] = 2
                    fresh -= 1
                    q.append((nr, nc))
        minutes += 1
    return minutes if fresh == 0 else -1

print(oranges_rotting([[2, 1, 1], [1, 1, 0], [0, 1, 1]]))  # 4
print(oranges_rotting([[2, 1, 1], [0, 1, 1], [1, 0, 1]]))  # -1
print(oranges_rotting([[0, 2]]))                           # 0
```

The `while q and fresh` condition stops as soon as nothing fresh is left, so we don't count an extra empty minute.

## Complexity

- **Time O(rows × cols)**, **space O(rows × cols)** for the queue.

## Similar problems

Walls and Gates, 01 Matrix (distance to nearest 0), As Far from Land as Possible, Shortest Path in Binary Matrix.

Learn the pattern: [Grid Problems](/tutorials/dsa-grids/).
