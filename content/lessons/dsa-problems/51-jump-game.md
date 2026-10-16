---
title: Jump Game
slug: problem-jump-game
subject: dsa-problems
lesson: 51
difficulty: intermediate
level: Medium
pattern: Greedy
companies: Amazon, Microsoft, Google, Meta, Apple, Adobe
excerpt: Can you reach the last index if each number is your maximum jump? Track the farthest reachable point in one greedy pass.
---

A problem that looks like it needs DP or backtracking but falls to a single greedy variable.

## The problem

You start at index 0. `nums[i]` is the **maximum** jump length from index i. Return `True` if you can reach the last index.

## Examples

| nums | answer | why |
|---|---|---|
| `[2, 3, 1, 1, 4]` | True | 0 → 1 → 4 |
| `[3, 2, 1, 0, 4]` | False | every path lands on index 3, which has jump 0 |
| `[0]` | True | already there |

## Hints

::hint Hint 1 | You don't need to know WHICH jumps to make, only how far you could possibly get.
::hint Hint 2 | Keep `farthest` = the farthest index reachable so far. At each index i you can stand on, update farthest = max(farthest, i + nums[i]).
::hint Hint 3 | If you ever reach an index i > farthest, you're stuck.

## The easy idea: a torch lighting the path

Walk along a dark corridor. Every tile you stand on lights up the corridor up to `i + nums[i]` tiles ahead. Keep walking while your tile is lit. If you step into darkness, you can't continue. If the light ever reaches the end, you're done.

```text
nums:     2  3  1  1  4
index:    0  1  2  3  4
farthest: 2  4  4  4  8   → reached the end ✓

nums:     3  2  1  0  4
farthest: 3  3  3  3  ✗   index 4 > farthest 3 → stuck
```

## Brute force: DP, O(n²)

```python
def can_jump_dp(nums):
    ok = [False] * len(nums)
    ok[0] = True
    for i in range(len(nums)):
        if ok[i]:
            for j in range(i + 1, min(len(nums), i + nums[i] + 1)):
                ok[j] = True
    return ok[-1]

print(can_jump_dp([2, 3, 1, 1, 4]))  # True
```

## Solution: greedy, O(n)

```python
def can_jump(nums):
    farthest = 0
    for i, jump in enumerate(nums):
        if i > farthest:
            return False                 # this tile is in the dark
        farthest = max(farthest, i + jump)
    return True

print(can_jump([2, 3, 1, 1, 4]))  # True
print(can_jump([3, 2, 1, 0, 4]))  # False
print(can_jump([0]))              # True
```

## Complexity

- **Time O(n)**, **space O(1)**.

## Jump Game II: fewest jumps

Think in BFS levels: all indexes reachable with k jumps form a window. When you reach the end of the current window, you must jump again, and the new window ends at the farthest point seen.

```python
def min_jumps(nums):
    jumps = window_end = farthest = 0
    for i in range(len(nums) - 1):
        farthest = max(farthest, i + nums[i])
        if i == window_end:
            jumps += 1
            window_end = farthest
    return jumps

print(min_jumps([2, 3, 1, 1, 4]))  # 2
print(min_jumps([2, 3, 0, 1, 4]))  # 2
```

## Similar problems

Jump Game II, Jump Game III (jump left or right, BFS), Gas Station, Video Stitching, Minimum Number of Taps to Open to Water a Garden.

Learn the pattern: [Greedy and Intervals](/tutorials/dsa-greedy-intervals/).
