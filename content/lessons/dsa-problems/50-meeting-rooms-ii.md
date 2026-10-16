---
title: Meeting Rooms II
slug: problem-meeting-rooms-ii
subject: dsa-problems
lesson: 50
difficulty: intermediate
level: Medium
pattern: Intervals
companies: Google, Amazon, Meta, Microsoft, Uber, Bloomberg, Oracle
excerpt: How many meeting rooms do you need? Sort by start and keep a min-heap of end times, or sweep through start and end events.
---

A real-world scheduling question: the office manager wants to know how many rooms to book.

## The problem

Given meeting times `[start, end]`, return the minimum number of rooms needed so no meetings clash. A meeting ending at time t frees its room for one starting at t.

## Examples

| meetings | answer |
|---|---|
| `[[0,30],[5,10],[15,20]]` | 2 |
| `[[7,10],[2,4]]` | 1 |
| `[[1,5],[2,6],[3,7]]` | 3 |

## Hints

::hint Hint 1 | Process meetings in order of start time.
::hint Hint 2 | When a meeting starts, can it reuse the room that frees up EARLIEST? A min-heap of end times tells you that in O(1).
::hint Hint 3 | Alternative: the answer is the maximum number of meetings happening at the same moment.

## The easy idea: rooms with "free at" signs

Each booked room has a sign: "free at 30", "free at 10". When the next meeting arrives (in start order), look at the room that frees **earliest**. If it's already free, reuse it (change its sign). Otherwise, open a new room. The heap keeps the earliest sign on top.

```text
[0,30]  → open room A (free at 30)              rooms: {30}
[5,10]  → earliest free is 30 > 5: open room B   rooms: {10, 30}
[15,20] → earliest free is 10 <= 15: reuse B     rooms: {20, 30}
answer: 2 rooms
```

## Solution: min-heap

```python
import heapq

def min_meeting_rooms(meetings):
    meetings.sort()
    free_at = []                            # end times of rooms in use
    for start, end in meetings:
        if free_at and free_at[0] <= start:
            heapq.heapreplace(free_at, end) # reuse the earliest-free room
        else:
            heapq.heappush(free_at, end)    # open a new room
    return len(free_at)

print(min_meeting_rooms([[0, 30], [5, 10], [15, 20]]))  # 2
print(min_meeting_rooms([[7, 10], [2, 4]]))             # 1
print(min_meeting_rooms([[1, 5], [2, 6], [3, 7]]))      # 3
print(min_meeting_rooms([]))                            # 0
```

## Solution: sweep line

Separate the start and end times and sort each. Walk through time: every start needs a room, every end frees one. The busiest moment is the answer.

```python
def min_rooms_sweep(meetings):
    starts = sorted(s for s, _ in meetings)
    ends = sorted(e for _, e in meetings)
    rooms = best = j = 0
    for s in starts:
        while j < len(ends) and ends[j] <= s:
            rooms -= 1                     # a meeting ended
            j += 1
        rooms += 1                         # this meeting starts
        best = max(best, rooms)
    return best

print(min_rooms_sweep([[0, 30], [5, 10], [15, 20]]))  # 2
```

## Complexity

- **Time O(n log n)** (sorting and heap operations).
- **Space O(n).**

## Meeting Rooms I (the easy version)

"Can one person attend everything?" Sort and check that each meeting starts after the previous one ends.

## Similar problems

Meeting Rooms, Merge Intervals, Car Pooling, My Calendar I/II, Minimum Number of Arrows to Burst Balloons.

Learn the pattern: [Greedy and Intervals](/tutorials/dsa-greedy-intervals/).
