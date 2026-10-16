---
title: House Robber
slug: problem-house-robber
subject: dsa-problems
lesson: 44
difficulty: intermediate
level: Medium
pattern: Dynamic Programming
companies: Amazon, Google, Microsoft, Cisco, Airbnb, Apple
excerpt: Take the most money without robbing two neighbouring houses. At each house, choose "rob it" or "skip it", and remember the best so far.
---

The classic "take it or skip it" DP. Once you get this one, dozens of problems open up.

## The problem

`nums[i]` is the money in house i along a street. You can't rob two **adjacent** houses. Return the maximum you can take.

## Examples

| nums | answer | houses robbed |
|---|---|---|
| `[1, 2, 3, 1]` | 4 | 1 + 3 |
| `[2, 7, 9, 3, 1]` | 12 | 2 + 9 + 1 |
| `[2, 1, 1, 2]` | 4 | 2 + 2 |

## Hints

::hint Hint 1 | Greedy (take every other house) fails on [2, 1, 1, 2]. You need to compare options.
::hint Hint 2 | At house i: skip it → best so far stays best(i − 1). Rob it → best(i − 2) + nums[i].
::hint Hint 3 | best(i) = max(best(i − 1), best(i − 2) + nums[i]). Only the last two values are needed.

## The easy idea: a choice at every door

Walk down the street. At each door, you ask one question:

- "If I **skip** this house, I keep my best total from the previous house."
- "If I **rob** it, I add its money to my best total from **two** houses back (the neighbour must be skipped)."

Pick whichever is bigger, then move on.

| house | money | skip it | rob it | best |
|---|---|---|---|---|
| 0 | 2 | 0 | 2 | 2 |
| 1 | 7 | 2 | 7 | 7 |
| 2 | 9 | 7 | 2 + 9 = 11 | 11 |
| 3 | 3 | 11 | 7 + 3 = 10 | 11 |
| 4 | 1 | 11 | 11 + 1 = 12 | 12 |

## Solution

```python
def rob(nums):
    two_back, one_back = 0, 0
    for money in nums:
        two_back, one_back = one_back, max(one_back, two_back + money)
    return one_back

print(rob([1, 2, 3, 1]))     # 4
print(rob([2, 7, 9, 3, 1]))  # 12
print(rob([2, 1, 1, 2]))     # 4
print(rob([]))               # 0
```

## Complexity

- **Time O(n)**, **space O(1)**.

## Follow-up: houses in a circle (House Robber II)

The first and last houses are now neighbours. So either skip the first house or skip the last one, and take the better result:

```python
def rob_line(nums):
    a, b = 0, 0
    for x in nums:
        a, b = b, max(b, a + x)
    return b

def rob_circle(nums):
    if len(nums) == 1:
        return nums[0]
    return max(rob_line(nums[1:]), rob_line(nums[:-1]))

print(rob_circle([2, 3, 2]))     # 3
print(rob_circle([1, 2, 3, 1]))  # 4
```

## Similar problems

House Robber II, House Robber III (houses in a tree), Delete and Earn, Maximum Sum of Non-Adjacent Elements.

Learn the pattern: [Dynamic Programming](/tutorials/dsa-dynamic-programming/).
