---
title: Big O Notation
slug: dsa-big-o
subject: dsa
lesson: 2
difficulty: beginner
excerpt: Measure how an algorithm's work grows with input size. Learn O(1), O(log n), O(n), O(n log n), O(n²) and O(2ⁿ) with a live chart, how to read complexity from code, and space complexity.
---

How fast is your code? "0.2 seconds on my laptop" isn't a useful answer: a different computer, different data, and the number changes. **Big O notation** answers a better question: **how does the amount of work grow as the input gets bigger?**

## The chai stall analogy

Imagine three ways to serve chai to a queue of n people:

- **O(1):** you hand out pre-made cups from a table. 1 person or 1,000, it takes the same few seconds to pick up a cup.
- **O(n):** you make each cup when the person arrives. Twice the people, twice the time.
- **O(n²):** before serving each person, you ask **every other person** in the queue if they want sugar. 10 people means 100 questions; 1,000 people means 1,000,000. The stall collapses.

Big O describes this **growth**, ignoring small details like "each cup takes 30 seconds".

## Watch the growth

Press **Play** and watch what happens to each complexity as n doubles:

::viz big-o

At n = 64, O(n²) needs 4,096 steps while O(log n) needs only 6. That gap is the whole point of learning DSA.

## The common complexities, from fast to slow

| Big O | Name | Example | n = 1,000,000 |
|---|---|---|---|
| O(1) | Constant | Read `arr[5]`, dictionary lookup | 1 step |
| O(log n) | Logarithmic | Binary search | ~20 steps |
| O(n) | Linear | Loop over a list once | 1,000,000 steps |
| O(n log n) | Linearithmic | Good sorting (merge sort, Python's sort) | ~20,000,000 steps |
| O(n²) | Quadratic | Compare every pair | 1,000,000,000,000 steps |
| O(2ⁿ) | Exponential | All subsets, naive recursion | impossible |

A computer does very roughly 10⁸ simple steps per second. So for a million items, O(n log n) finishes in a fraction of a second, while O(n²) would take hours.

## Reading Big O from code

### O(1): no loop over the input

```python
def first_item(items):
    return items[0]          # one step, whatever the size

print(first_item([7, 8, 9]))  # 7
```

### O(n): one loop

```python
def total(items):
    s = 0
    for x in items:          # runs n times
        s += x
    return s

print(total([1, 2, 3, 4]))  # 10
```

### O(n²): a loop inside a loop

```python
def count_pairs(items):
    count = 0
    for i in range(len(items)):
        for j in range(i + 1, len(items)):   # nested: about n²/2 pairs
            count += 1
    return count

print(count_pairs([1, 2, 3, 4]))  # 6
```

### O(log n): halving

```python
def halvings(n):
    steps = 0
    while n > 1:
        n //= 2              # the problem halves each time
        steps += 1
    return steps

print(halvings(1000000))  # 19
```

Whenever the remaining work is **cut in half** each step, think O(log n).

## The rules of simplification

Big O keeps only what matters for huge inputs:

1. **Drop constants.** 3n steps is O(n). n/2 is O(n).
2. **Keep the biggest term.** n² + n + 100 is O(n²): for large n, the n² part dwarfs the rest.
3. **Different inputs, different letters.** Looping over list `a` then list `b` is O(a + b), not O(n). A loop over `a` inside a loop over `b` is O(a × b).
4. **Sequential steps add, nested steps multiply.** Two separate loops: O(n + n) = O(n). Nested loops: O(n × n) = O(n²).

```python
def example(nums):
    for x in nums:            # O(n)
        print(x)
    for x in nums:            # + O(n)
        for y in nums:        # × O(n)
            pass
    # total: O(n) + O(n²) = O(n²)
```

## Hidden costs in Python

Some single lines hide loops:

| Code | Complexity | Why |
|---|---|---|
| `x in my_list` | O(n) | Checks items one by one |
| `x in my_set` / `x in my_dict` | O(1) average | Hashing jumps straight there |
| `my_list.insert(0, x)` | O(n) | Shifts every item right |
| `my_list.append(x)` | O(1) | Adds at the end |
| `my_list.pop(0)` | O(n) | Shifts everything left (use `collections.deque` instead) |
| `sorted(my_list)` | O(n log n) | Python's Timsort |
| `my_list[a:b]` | O(b − a) | Copies the slice |
| `"".join(parts)` | O(total length) | Much better than `+=` in a loop |

The classic trap: `if x in my_list` **inside** a loop is secretly O(n²). Switching the list to a set makes it O(n).

## Space complexity

Big O also measures **memory**. How much extra space does the algorithm use as n grows?

```python
def reversed_copy(items):
    return items[::-1]          # makes a new list: O(n) extra space

def reverse_in_place(items):
    i, j = 0, len(items) - 1
    while i < j:                # only two variables: O(1) extra space
        items[i], items[j] = items[j], items[i]
        i += 1
        j -= 1
    return items

print(reversed_copy([1, 2, 3]))     # [3, 2, 1]
print(reverse_in_place([1, 2, 3]))  # [3, 2, 1]
```

Recursion uses space too: each call waiting on the **call stack** takes memory, so a recursion that goes n levels deep uses O(n) space.

## Best, average and worst case

Linear search finds the target immediately if it's first (best case), or checks everything if it's last or missing (worst case). Interviews usually ask for the **worst case**, unless stated otherwise. Some structures are quoted with their **average** case, like hash maps (O(1) average).

## Interview corner

At Google, Amazon, Microsoft and nearly every other company, you are expected to **state the time and space complexity of every solution you write**, without being asked. A typical exchange:

> "My brute force compares every pair, so it's O(n²) time and O(1) space. With a hash set I can get O(n) time at the cost of O(n) space."

Practise saying that sentence for every problem in this course.

::hint Quick check | Code that loops over n items and does a dictionary lookup inside the loop is O(n), not O(n²), because each lookup is O(1).

## Common mistakes

- **Counting lines instead of growth.** Ten lines with no loops are still O(1).
- **Forgetting hidden loops** like `in` on a list, `insert(0, …)` or slicing.
- **Calling two separate loops O(n²).** They add (O(n)), they don't multiply.
- **Forgetting the recursion call stack** in space complexity.

## Exercise

Give the time complexity of each:

1. A loop from 1 to n that prints `i * i`.
2. Two nested loops, both from 1 to n, followed by one more loop from 1 to n.
3. A while loop that starts at n and does `n = n // 3` until n reaches 0.
4. Checking `if word in banned_list` for every word in a document of m words, with k banned words. How can you make it O(m)?

Then play **Big-O Snap** in Brain Games to test your speed.

## Summary

- Big O describes how work **grows** with input size, not exact seconds.
- From fast to slow: O(1) < O(log n) < O(n) < O(n log n) < O(n²) < O(2ⁿ).
- Drop constants, keep the biggest term; sequential adds, nested multiplies.
- Watch for hidden loops, and always state time **and** space complexity.

Next, you'll explore the most basic data structure of all: arrays and strings.
