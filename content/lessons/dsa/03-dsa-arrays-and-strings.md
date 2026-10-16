---
title: Arrays and Strings
slug: dsa-arrays-and-strings
subject: dsa
lesson: 3
difficulty: beginner
excerpt: How arrays live in memory, why indexing is O(1) but inserting is O(n), linear search, in-place techniques, and classic string problems like palindromes and reversing words.
---

Arrays are the foundation of almost every other data structure. Strings are just arrays of characters. Most interview problems start with "given an array…" or "given a string…", so let's understand exactly how they work and what each operation costs.

## The train analogy

An array is like a **train**: coaches in a row, numbered 0, 1, 2, 3… Because every coach is the same size and they are joined in order, the conductor can walk straight to coach 7 without checking coaches 0 to 6. That is why **reading `arr[i]` is O(1)**.

But adding a coach in the **middle** of the train means uncoupling and shifting every coach behind it. That is why **inserting or deleting in the middle is O(n)**.

```text
index:   0    1    2    3    4
       [ 12 | 7  | 31 | 4  | 19 ]
```

## Costs at a glance (Python lists)

| Operation | Example | Cost |
|---|---|---|
| Read / write by index | `a[3]`, `a[3] = 9` | O(1) |
| Add / remove at the end | `a.append(x)`, `a.pop()` | O(1) |
| Add / remove at the front or middle | `a.insert(0, x)`, `a.pop(0)` | O(n) |
| Search for a value | `x in a`, `a.index(x)` | O(n) |
| Length | `len(a)` | O(1) |
| Slice | `a[2:6]` | O(k) for k items |

## Linear search: the simplest algorithm

When the array is not sorted, the only way to find a value is to check items one by one:

::viz linear-search {"arr": [12, 7, 31, 4, 19, 25], "target": 19}

```python
def linear_search(arr, target):
    for i in range(len(arr)):
        if arr[i] == target:
            return i
    return -1

print(linear_search([12, 7, 31, 4, 19], 19))  # 4
print(linear_search([12, 7, 31, 4, 19], 5))   # -1
```

O(n) time, O(1) space. If you search the same array many times, it is worth **sorting it once** and using binary search, or putting the values in a **set**.

## Pattern: do it in one pass

Many array problems can be solved by walking through the array **once** while remembering a few things. Finding the maximum is the simplest example:

```python
def find_max(arr):
    best = arr[0]
    for x in arr[1:]:
        if x > best:
            best = x
    return best

print(find_max([3, 9, 2, 14, 6]))  # 14
```

Ask yourself: "As I walk through, what do I need to remember?" For the maximum, just one number.

### Second largest in one pass

```python
def second_largest(arr):
    first = second = float("-inf")
    for x in arr:
        if x > first:
            first, second = x, first
        elif first > x > second:
            second = x
    return second if second != float("-inf") else None

print(second_largest([3, 9, 2, 14, 6]))  # 9
print(second_largest([5, 5, 5]))         # None
```

## Pattern: work in place

"In place" means changing the array itself instead of building a new one: O(1) extra space. Interviewers often ask for it.

### Reverse an array in place

```python
def reverse_in_place(arr):
    left, right = 0, len(arr) - 1
    while left < right:
        arr[left], arr[right] = arr[right], arr[left]
        left += 1
        right -= 1
    return arr

print(reverse_in_place([1, 2, 3, 4, 5]))  # [5, 4, 3, 2, 1]
```

Two indexes walk towards each other, swapping as they go. This is your first taste of the **two pointers** pattern (lesson 5).

### Rotate an array by k

Rotating `[1, 2, 3, 4, 5, 6, 7]` right by 3 gives `[5, 6, 7, 1, 2, 3, 4]`. A neat in-place trick: reverse everything, then reverse the two parts.

```python
def rotate(nums, k):
    n = len(nums)
    k %= n

    def rev(i, j):
        while i < j:
            nums[i], nums[j] = nums[j], nums[i]
            i += 1
            j -= 1

    rev(0, n - 1)   # [7, 6, 5, 4, 3, 2, 1]
    rev(0, k - 1)   # [5, 6, 7, 4, 3, 2, 1]
    rev(k, n - 1)   # [5, 6, 7, 1, 2, 3, 4]
    return nums

print(rotate([1, 2, 3, 4, 5, 6, 7], 3))  # [5, 6, 7, 1, 2, 3, 4]
```

## Strings are arrays of characters

In Python, strings are **immutable**: you can't change one character in place. Building a string with `+=` in a loop can cost O(n²) because each step copies the whole string. Collect pieces in a list and `"".join()` them once instead.

### Is it a palindrome?

A palindrome reads the same both ways: "racecar", "madam".

```python
def is_palindrome(s):
    left, right = 0, len(s) - 1
    while left < right:
        if s[left] != s[right]:
            return False
        left += 1
        right -= 1
    return True

print(is_palindrome("racecar"))  # True
print(is_palindrome("chai"))     # False
```

### Reverse the words in a sentence

```python
def reverse_words(sentence):
    return " ".join(reversed(sentence.split()))

print(reverse_words("learn  DSA   the easy way"))  # way easy the DSA learn
```

`split()` with no argument also handles extra spaces.

### Count characters

```python
def char_counts(s):
    counts = {}
    for ch in s:
        counts[ch] = counts.get(ch, 0) + 1
    return counts

print(char_counts("banana"))  # {'b': 1, 'a': 3, 'n': 2}
```

Counting with a dictionary is the bridge to the next lesson: **hashing**.

## 2D arrays (matrices)

A grid is a list of lists. `grid[r][c]` reads row r, column c.

```python
grid = [
    [1, 2, 3],
    [4, 5, 6],
]
transposed = [[grid[r][c] for r in range(len(grid))] for c in range(len(grid[0]))]
print(transposed)  # [[1, 4], [2, 5], [3, 6]]
```

Visiting every cell of an r × c grid is O(r × c).

## Interview corner

Array and string problems are the most common warm-up questions at **Amazon, Microsoft, Adobe, Infosys, TCS, Accenture** and nearly every company. Interviewers check:

- Do you handle **edge cases**: empty array, one item, all items equal, negative numbers?
- Can you do it **in place** with O(1) extra space when asked?
- Do you avoid hidden O(n²) costs like `insert(0, …)` in a loop or string `+=`?

::tip Before coding, always ask: "Can the array be empty? Can it contain negatives or duplicates?" It shows care, and often changes the solution.

## Common mistakes

- **Off-by-one errors**: the last index is `len(arr) - 1`.
- **Changing a list while looping over it**, which skips items.
- **String `+=` in a loop** on long strings.
- **Forgetting `k %= n`** in rotation problems when k is larger than the array.

## Exercise

1. Write `move_zeros(nums)` that moves all zeros to the end, keeping the order of other numbers, in place. (`[0, 1, 0, 3, 12]` → `[1, 3, 12, 0, 0]`)
2. Write `is_anagram(a, b)` that checks whether two words use the same letters (`"listen"`, `"silent"` → True).
3. Write `max_profit(prices)`: buy one day, sell a later day, maximum profit. Hint: in one pass, remember the cheapest price so far.

## Summary

- Arrays give O(1) access by index but O(n) insert/delete in the middle.
- Unsorted search is linear: O(n).
- Many problems are solved in **one pass** by remembering the right few values.
- In-place techniques use O(1) extra space; strings are immutable in Python, so join pieces.

Next, you'll learn the most useful tool in all of DSA: **hash maps and sets**.
