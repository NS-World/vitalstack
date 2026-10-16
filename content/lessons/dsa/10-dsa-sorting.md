---
title: Sorting Basics
slug: dsa-sorting
subject: dsa
lesson: 10
difficulty: beginner
excerpt: Bubble, selection and insertion sort animated step by step, what stable sorting means, Python's built-in sort with custom keys, and when sorting first makes a problem easy.
---

Sorting puts data in order, and a surprising number of problems become easy **once the data is sorted**: duplicates sit next to each other, binary search works, and two pointers become possible. In this lesson you'll see the simple sorting algorithms move, then learn to use Python's fast built-in sort like a pro.

## The playing cards analogy

When you pick up cards one at a time and slide each new card into the right place in your hand, you're doing **insertion sort**. When you repeatedly compare neighbours and swap them until nothing moves, you're doing **bubble sort**. Everyone already knows these algorithms; we're just giving them names.

## Bubble sort

Compare each pair of neighbours and swap if they're in the wrong order. After one full pass, the largest value has "bubbled" to the end.

::viz bubble-sort {"arr": [5, 1, 4, 2, 8, 3]}

```python
def bubble_sort(arr):
    n = len(arr)
    for end in range(n - 1, 0, -1):
        swapped = False
        for i in range(end):
            if arr[i] > arr[i + 1]:
                arr[i], arr[i + 1] = arr[i + 1], arr[i]
                swapped = True
        if not swapped:          # already sorted: stop early
            break
    return arr

print(bubble_sort([5, 1, 4, 2, 8, 3]))  # [1, 2, 3, 4, 5, 8]
```

O(n²) in the worst case; O(n) if the data is already sorted (thanks to the `swapped` check).

## Selection sort

Find the smallest remaining item and put it next in line.

```python
def selection_sort(arr):
    n = len(arr)
    for i in range(n):
        smallest = i
        for j in range(i + 1, n):
            if arr[j] < arr[smallest]:
                smallest = j
        arr[i], arr[smallest] = arr[smallest], arr[i]
    return arr

print(selection_sort([29, 10, 14, 37, 13]))  # [10, 13, 14, 29, 37]
```

Always O(n²), but it makes at most n swaps, which matters when writing data is expensive.

## Insertion sort

Keep the left part sorted; take the next item and shift bigger items right until it fits.

::viz insertion-sort {"arr": [7, 3, 9, 1, 5, 2]}

```python
def insertion_sort(arr):
    for i in range(1, len(arr)):
        key = arr[i]
        j = i - 1
        while j >= 0 and arr[j] > key:
            arr[j + 1] = arr[j]
            j -= 1
        arr[j + 1] = key
    return arr

print(insertion_sort([7, 3, 9, 1, 5, 2]))  # [1, 2, 3, 5, 7, 9]
```

O(n²) worst case, but **very fast on nearly sorted data** (close to O(n)). That's why real-world sorts like Python's Timsort use insertion sort on small chunks.

## Comparing the simple sorts

| Algorithm | Best | Worst | Extra space | Stable? |
|---|---|---|---|---|
| Bubble | O(n) | O(n²) | O(1) | Yes |
| Selection | O(n²) | O(n²) | O(1) | No |
| Insertion | O(n) | O(n²) | O(1) | Yes |
| Merge (next lesson) | O(n log n) | O(n log n) | O(n) | Yes |
| Quick (next lesson) | O(n log n) | O(n²) | O(log n) | No |
| Python `sorted` (Timsort) | O(n) | O(n log n) | O(n) | Yes |

### What does "stable" mean?

A **stable** sort keeps items with equal keys in their original order. If you sort students by marks and two students both have 90, a stable sort keeps them in the order they were before (for example, alphabetical). This lets you sort by several keys in steps.

## Python's built-in sorting (use this in real code)

```python
nums = [5, 2, 9, 1]
print(sorted(nums))                 # [1, 2, 5, 9]
nums.sort(reverse=True)             # in place
print(nums)                         # [9, 5, 2, 1]
```

### Sorting with a key

```python
words = ["banana", "kiwi", "apple", "fig"]
print(sorted(words, key=len))                    # ['fig', 'kiwi', 'apple', 'banana']
print(sorted(words, key=lambda w: (len(w), w)))  # ['fig', 'kiwi', 'apple', 'banana']

students = [("Asha", 92), ("Rohan", 85), ("Meera", 92)]
print(sorted(students, key=lambda s: (-s[1], s[0])))  # [('Asha', 92), ('Meera', 92), ('Rohan', 85)]
```

A tuple key sorts by the first part, then breaks ties with the next. A minus sign sorts numbers descending.

## Sorting first makes problems easy

### Are any two meetings overlapping?

```python
def can_attend_all(meetings):
    meetings.sort()                          # by start time
    for i in range(1, len(meetings)):
        if meetings[i][0] < meetings[i - 1][1]:
            return False
    return True

print(can_attend_all([[0, 30], [5, 10], [15, 20]]))  # False
print(can_attend_all([[7, 10], [2, 4]]))             # True
```

After sorting, only **neighbours** can overlap. O(n log n).

### Largest number from digits

```python
from functools import cmp_to_key

def largest_number(nums):
    strs = [str(n) for n in nums]
    strs.sort(key=cmp_to_key(lambda a, b: -1 if a + b > b + a else 1))
    result = "".join(strs)
    return "0" if result[0] == "0" else result

print(largest_number([3, 30, 34, 5, 9]))  # 9534330
```

A custom comparison: put `a` before `b` if `a + b` makes a bigger number than `b + a`.

## Counting sort: faster than n log n (sometimes)

When values are small integers in a known range, count them instead of comparing:

```python
def counting_sort(arr, max_value):
    counts = [0] * (max_value + 1)
    for x in arr:
        counts[x] += 1
    out = []
    for value, c in enumerate(counts):
        out.extend([value] * c)
    return out

print(counting_sort([3, 1, 4, 1, 5, 2], 5))  # [1, 1, 2, 3, 4, 5]
```

O(n + k) for values 0..k. Great for ages, marks out of 100, or letters.

## Interview corner

You'll rarely be asked to code bubble sort, but **Sort Colors** (Dutch national flag), **Meeting Rooms**, **Largest Number**, **Merge Intervals** and "sort then two pointers" problems are common at **Amazon, Microsoft, Google, Bloomberg and Goldman Sachs**. Know that comparison sorting can't beat O(n log n) in general, and when counting sort can.

::tip Stuck on a problem? Ask: "Would this be easy if the data were sorted?" Sorting costs O(n log n), which is often acceptable.

## Common mistakes

- **Using `nums = nums.sort()`**: `.sort()` returns None.
- **Forgetting that sorting changes the original positions**: copy indexes first if you need them.
- **Sorting inside a loop**, turning O(n log n) into O(n² log n).
- **Assuming an unstable sort keeps tie order.**

## Exercise

1. Play **Sort Sprint** in Brain Games. How does your swap count compare with bubble sort?
2. Write `sort_colors(nums)` that sorts a list of 0s, 1s and 2s in one pass. Hint: three pointers.
3. Sort a list of `(name, city, age)` tuples by city, then by age descending.

## Summary

- Bubble, selection and insertion sort are simple O(n²) algorithms; insertion sort shines on nearly sorted data.
- Stable sorts keep the order of equal items.
- In real code use `sorted()` / `.sort()` with `key=`; tuples sort by several keys.
- Sorting first often unlocks two pointers, binary search or greedy solutions.

Next, you'll learn the fast sorts that power the real world: **merge sort and quick sort**.
