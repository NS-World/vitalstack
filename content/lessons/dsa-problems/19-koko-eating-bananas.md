---
title: Koko Eating Bananas
slug: problem-koko-eating-bananas
subject: dsa-problems
lesson: 19
difficulty: intermediate
level: Medium
pattern: Binary Search
companies: Google, Amazon, Meta, Airbnb, DoorDash
excerpt: Binary search on the answer. Find the slowest eating speed that still finishes all bananas in time.
---

This problem teaches a superpower: **binary search on the answer**, even when there's no sorted list in sight.

## The problem

There are piles of bananas, `piles[i]` in each. Koko eats at speed `k` bananas per hour; each hour she picks one pile and eats up to k bananas from it (if the pile has fewer, she finishes it and waits). She has `h` hours. Return the **minimum** k that lets her eat everything in time.

## Examples

| piles | h | answer |
|---|---|---|
| `[3, 6, 7, 11]` | 8 | 4 |
| `[30, 11, 23, 4, 20]` | 5 | 30 |
| `[30, 11, 23, 4, 20]` | 6 | 23 |

## Hints

::hint Hint 1 | For a fixed speed k, you can compute the hours needed: sum of ceil(pile / k).
::hint Hint 2 | If speed k works, every faster speed also works. "Works / doesn't work" flips exactly once as k grows.
::hint Hint 3 | Binary search k between 1 and max(piles) for the first speed that works.

## The easy idea: "too slow / fast enough"

Line up every possible speed: 1, 2, 3, … max pile. They look like this:

```text
speed: 1  2  3  4  5  6 ... 11
works: ✗  ✗  ✗  ✓  ✓  ✓ ... ✓
```

A row of ✗ followed by a row of ✓ is **sorted**. Binary search finds the first ✓ in O(log max) checks instead of trying every speed.

## Solution

```python
def min_eating_speed(piles, h):
    def hours(k):
        return sum((p + k - 1) // k for p in piles)   # ceil(p / k)

    lo, hi = 1, max(piles)
    while lo < hi:
        mid = (lo + hi) // 2
        if hours(mid) <= h:
            hi = mid             # fast enough: try slower
        else:
            lo = mid + 1         # too slow
    return lo

print(min_eating_speed([3, 6, 7, 11], 8))         # 4
print(min_eating_speed([30, 11, 23, 4, 20], 5))   # 30
print(min_eating_speed([30, 11, 23, 4, 20], 6))   # 23
```

`(p + k - 1) // k` is integer ceiling division without floats.

## Complexity

- **Time O(n log m)** for n piles and largest pile m: log m guesses, each costs O(n).
- **Space O(1).**

## The template: binary search on the answer

Whenever a problem asks for the **minimum (or maximum) value that satisfies a condition**, and the condition is monotonic (once true, stays true), use:

```text
lo, hi = smallest possible answer, largest possible answer
while lo < hi:
    mid = (lo + hi) // 2
    if works(mid): hi = mid
    else: lo = mid + 1
```

## Similar problems

Capacity To Ship Packages Within D Days, Split Array Largest Sum, Minimum Number of Days to Make Bouquets, Sqrt(x).

Learn the pattern: [Binary Search](/tutorials/dsa-binary-search/).
