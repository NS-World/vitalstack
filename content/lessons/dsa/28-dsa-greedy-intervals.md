---
title: Greedy Algorithms and Intervals
slug: dsa-greedy-intervals
subject: dsa
lesson: 28
difficulty: intermediate
excerpt: Make the best local choice and never look back. When greedy works (and when it fails), merging intervals animated, meeting rooms, non-overlapping intervals, jump game and gas station.
---

A **greedy** algorithm makes the choice that looks best **right now** and never changes its mind. No tables, no backtracking. When it works, it's the simplest and fastest solution there is. The skill is knowing **when** it works.

## The cinema analogy

You want to watch as many films as possible at a festival; screenings overlap. Which film should you pick first? Not the shortest, not the earliest-starting, but the one that **finishes earliest**: it leaves the most time for everything else. Repeat. That simple rule is provably the best, and it's a classic greedy algorithm.

## When does greedy work?

Greedy is correct when a **locally best choice never ruins the global answer**. You usually convince yourself with an "exchange argument": if an optimal answer made a different choice, you could swap in the greedy choice without making it worse.

Greedy **fails** when an early choice can block a better combination later. Coin change with coins `[1, 3, 4]` and amount 6: greedy takes 4 + 1 + 1 (3 coins) but 3 + 3 (2 coins) is better. That needs DP (lesson 26).

::tip Before trusting greedy, spend one minute hunting for a counter-example with small numbers. If you can't find one and you can explain the swap argument, go greedy.

## Interval problems: sort first

An **interval** is a `[start, end]` pair: a meeting, a booking, a range of numbers. Almost every interval problem starts with **sorting** by start (or by end), so overlapping intervals end up next to each other.

Two intervals `[a, b]` and `[c, d]` (with `a <= c`) overlap when `c <= b`.

## Merge overlapping intervals

Sort by start. Walk through; if the current interval overlaps the last merged one, stretch its end, otherwise start a new one.

::viz merge-intervals {"iv": "1-3, 8-10, 2-6, 15-18, 9-12"}

```python
def merge(intervals):
    intervals.sort(key=lambda iv: iv[0])
    merged = []
    for start, end in intervals:
        if merged and start <= merged[-1][1]:
            merged[-1][1] = max(merged[-1][1], end)   # overlap: extend
        else:
            merged.append([start, end])
    return merged

print(merge([[1, 3], [8, 10], [2, 6], [15, 18], [9, 12]]))  # [[1, 6], [8, 12], [15, 18]]
print(merge([[1, 4], [4, 5]]))                              # [[1, 5]]
```

**O(n log n)** for the sort, then one pass. The `max` matters: `[1, 10]` followed by `[2, 3]` must stay `[1, 10]`.

## Insert an interval

The list is already sorted and non-overlapping. Add everything that ends before the new one, merge everything that overlaps it, then add the rest.

```python
def insert(intervals, new):
    out, i, n = [], 0, len(intervals)
    while i < n and intervals[i][1] < new[0]:
        out.append(intervals[i])
        i += 1
    start, end = new
    while i < n and intervals[i][0] <= end:
        start = min(start, intervals[i][0])
        end = max(end, intervals[i][1])
        i += 1
    out.append([start, end])
    return out + intervals[i:]

print(insert([[1, 2], [3, 5], [6, 7], [8, 10], [12, 16]], [4, 8]))  # [[1, 2], [3, 10], [12, 16]]
```

## Meeting rooms

**Can one person attend every meeting?** Sort by start; any overlap between neighbours means no.

```python
def can_attend(meetings):
    meetings.sort()
    return all(meetings[i][0] >= meetings[i - 1][1] for i in range(1, len(meetings)))

print(can_attend([[0, 30], [5, 10], [15, 20]]))  # False
print(can_attend([[7, 10], [2, 4]]))             # True
```

**How many rooms are needed?** Sort by start and keep a min-heap of end times of meetings in progress. If the earliest-ending room is free by the time the next meeting starts, reuse it.

```python
import heapq

def min_rooms(meetings):
    meetings.sort()
    ends = []
    for start, end in meetings:
        if ends and ends[0] <= start:
            heapq.heappop(ends)             # that room is free again
        heapq.heappush(ends, end)
    return len(ends)

print(min_rooms([[0, 30], [5, 10], [15, 20]]))  # 2
print(min_rooms([[1, 5], [2, 6], [3, 7]]))      # 3
```

## Non-overlapping intervals (the cinema problem)

Remove the fewest intervals so the rest don't overlap. Equivalent: **keep the most**. Sort by **end** and keep each interval that starts after the last kept one ends.

```python
def erase_overlaps(intervals):
    intervals.sort(key=lambda iv: iv[1])
    kept, last_end = 0, float("-inf")
    for start, end in intervals:
        if start >= last_end:
            kept += 1
            last_end = end
    return len(intervals) - kept

print(erase_overlaps([[1, 2], [2, 3], [3, 4], [1, 3]]))  # 1
print(erase_overlaps([[1, 2], [1, 2], [1, 2]]))          # 2
```

"Minimum arrows to burst balloons" is the same idea.

## Jump game

Each number says how far you **may** jump from that position. Can you reach the end? Track the farthest reachable index; if you ever stand beyond it, you're stuck.

```python
def can_jump(nums):
    farthest = 0
    for i, step in enumerate(nums):
        if i > farthest:
            return False
        farthest = max(farthest, i + step)
    return True

print(can_jump([2, 3, 1, 1, 4]))  # True
print(can_jump([3, 2, 1, 0, 4]))  # False
```

### Minimum jumps

Think of BFS levels: all positions reachable with k jumps form a window; the next window ends at the farthest point reachable from it.

```python
def min_jumps(nums):
    jumps, window_end, farthest = 0, 0, 0
    for i in range(len(nums) - 1):
        farthest = max(farthest, i + nums[i])
        if i == window_end:
            jumps += 1
            window_end = farthest
    return jumps

print(min_jumps([2, 3, 1, 1, 4]))  # 2
```

## Gas station

A circular road with gas stations. If the total gas is at least the total cost, a solution exists. Start from the station right **after** the point where your tank last went negative.

```python
def start_station(gas, cost):
    if sum(gas) < sum(cost):
        return -1
    tank, start = 0, 0
    for i in range(len(gas)):
        tank += gas[i] - cost[i]
        if tank < 0:
            start, tank = i + 1, 0
    return start

print(start_station([1, 2, 3, 4, 5], [3, 4, 5, 1, 2]))  # 3
```

## More greedy classics

```python
def max_profit(prices):
    # Buy and sell as many times as you like: take every upward step.
    return sum(max(0, b - a) for a, b in zip(prices, prices[1:]))

def assign_cookies(greed, sizes):
    greed.sort()
    sizes.sort()
    child = 0
    for s in sizes:
        if child < len(greed) and s >= greed[child]:
            child += 1
    return child

print(max_profit([7, 1, 5, 3, 6, 4]))         # 7
print(assign_cookies([1, 2, 3], [1, 1]))      # 1
print(assign_cookies([1, 2], [1, 2, 3]))      # 2
```

## How to recognise the pattern

- **Intervals, meetings, bookings, ranges** → sort, then sweep.
- "Maximum number of non-overlapping…" → sort by **end**.
- "Can you reach…", "minimum number of jumps/stops" with simple rules → track the farthest point.
- Sorting followed by one pass, where each decision is final.

## Interview corner

Merge Intervals is one of the most reported questions at **Google, Meta, Amazon, Microsoft, Bloomberg and LinkedIn**, along with Insert Interval, Meeting Rooms II, Non-overlapping Intervals, Jump Game, Gas Station, Task Scheduler and Partition Labels. Interviewers often ask "why is greedy correct here?" so be ready with the one-sentence swap argument.

## Common mistakes

- **Forgetting to sort** the intervals first.
- **Sorting by the wrong key** (start vs end).
- **Using `<` vs `<=`** inconsistently: do intervals that touch at a point overlap? Read the problem.
- **Trusting greedy without a check**: try a small counter-example first.

## Exercise

1. In the visualizer, try `1-4, 2-3, 5-7, 6-8`. How many merged intervals are left?
2. Write `partition_labels(s)`: split a string into as many parts as possible so each letter appears in only one part.
3. Write `min_arrows(points)` for the balloon problem.

## Summary

- Greedy takes the best local choice and never undoes it; check with a counter-example hunt.
- Interval problems: sort first, then a single sweep.
- Merge: sort by start and extend; keep the most: sort by end.
- Jump game and gas station track a running best in one pass.

Next, put everything together with the **interview playbook**: patterns, communication and a practice plan.
