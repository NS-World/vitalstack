---
title: Merge Intervals
slug: problem-merge-intervals
subject: dsa-problems
lesson: 49
difficulty: intermediate
level: Medium
pattern: Intervals
companies: Google, Meta, Amazon, Microsoft, Bloomberg, LinkedIn, Salesforce
excerpt: Combine overlapping time ranges into one. Sort by start, then extend or start a new block in a single sweep.
---

One of the most frequently reported interview problems, and the gateway to every interval question.

## The problem

Given intervals `[start, end]`, merge all overlapping ones and return the result.

## Examples

| intervals | answer |
|---|---|
| `[[1,3],[2,6],[8,10],[15,18]]` | `[[1,6],[8,10],[15,18]]` |
| `[[1,4],[4,5]]` | `[[1,5]]` (touching counts as overlapping) |
| `[[1,10],[2,3]]` | `[[1,10]]` |

## Hints

::hint Hint 1 | Unsorted intervals are hard to compare. Sort by start time first.
::hint Hint 2 | After sorting, an interval overlaps the previous merged one exactly when its start ≤ that one's end.
::hint Hint 3 | When overlapping, the new end is the MAX of both ends (one interval may contain the other).

## The easy idea: booking a meeting room calendar

Lay all meetings on a timeline in order of start time. Walk along: if the next meeting starts before the current block ends, stretch the block; otherwise close the block and start a new one. Watch it:

::viz merge-intervals {"iv": "1-3, 2-6, 8-10, 15-18"}

## Solution

```python
def merge(intervals):
    intervals.sort(key=lambda iv: iv[0])
    merged = []
    for start, end in intervals:
        if merged and start <= merged[-1][1]:
            merged[-1][1] = max(merged[-1][1], end)
        else:
            merged.append([start, end])
    return merged

print(merge([[1, 3], [2, 6], [8, 10], [15, 18]]))  # [[1, 6], [8, 10], [15, 18]]
print(merge([[1, 4], [4, 5]]))                     # [[1, 5]]
print(merge([[1, 10], [2, 3]]))                    # [[1, 10]]
print(merge([[5, 6], [1, 2]]))                     # [[1, 2], [5, 6]]
```

## Complexity

- **Time O(n log n)** for sorting; the sweep is O(n).
- **Space O(n)** for the output.

## Common bugs

- Forgetting to **sort**.
- Writing `merged[-1][1] = end` instead of `max(...)`: breaks `[[1, 10], [2, 3]]`.
- Using `<` instead of `<=` when touching intervals should merge (read the problem statement).

## Related: interval intersection

Two sorted lists of intervals: walk both with two pointers; the overlap of two intervals is `[max(starts), min(ends)]` if that's valid. Advance whichever ends first.

```python
def interval_intersection(a, b):
    i = j = 0
    out = []
    while i < len(a) and j < len(b):
        lo, hi = max(a[i][0], b[j][0]), min(a[i][1], b[j][1])
        if lo <= hi:
            out.append([lo, hi])
        if a[i][1] < b[j][1]:
            i += 1
        else:
            j += 1
    return out

print(interval_intersection([[0, 2], [5, 10]], [[1, 5], [8, 12]]))  # [[1, 2], [5, 5], [8, 10]]
```

## Similar problems

Insert Interval, Meeting Rooms, Meeting Rooms II, Non-overlapping Intervals, Interval List Intersections, Employee Free Time.

Learn the pattern: [Greedy and Intervals](/tutorials/dsa-greedy-intervals/).
