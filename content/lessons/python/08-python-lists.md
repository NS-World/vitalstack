---
title: Python Lists
slug: python-lists
subject: python
lesson: 8
difficulty: beginner
excerpt: Create, index, slice and change lists; add, remove, sort and search items; copy lists safely; and write list comprehensions.
---

A **list** is an ordered collection of items: a to-do list, a playlist, a set of exam scores. Lists are the most used data structure in Python, and they're very flexible: they can grow, shrink, be sorted and hold any kind of value.

## Creating lists

```python
fruits = ["apple", "banana", "cherry"]
scores = [92, 87, 95, 78]
mixed = ["Asha", 21, True, None]
empty = []
letters = list("abc")       # ['a', 'b', 'c']
numbers = list(range(1, 6)) # [1, 2, 3, 4, 5]

print(fruits, len(fruits))
print(numbers)
```

## Indexing and slicing

Lists use the same indexing and slicing as strings:

```python
colours = ["red", "green", "blue", "yellow", "purple"]
print(colours[0])     # red
print(colours[-1])    # purple
print(colours[1:3])   # ['green', 'blue']
print(colours[:2])    # ['red', 'green']
print(colours[::-1])  # reversed copy
```

## Lists are mutable

Unlike strings, you can change a list in place:

```python
tasks = ["wake up", "code", "sleep"]
tasks[1] = "code in Python"
print(tasks)
```

## Adding items

```python
cart = ["pen"]
cart.append("notebook")            # add one item to the end
cart.insert(0, "bag")              # insert at a position
cart.extend(["eraser", "ruler"])   # add several items
print(cart)   # ['bag', 'pen', 'notebook', 'eraser', 'ruler']

combined = [1, 2] + [3, 4]         # + makes a new list
print(combined)
```

`append([1, 2])` would add the list as **one** item; `extend` adds each item separately.

## Removing items

```python
items = ["a", "b", "c", "d", "b"]

items.remove("b")      # removes the FIRST "b"
print(items)           # ['a', 'c', 'd', 'b']

last = items.pop()     # removes and returns the last item
print(last, items)     # b ['a', 'c', 'd']

first = items.pop(0)   # removes and returns item at index 0
print(first, items)    # a ['c', 'd']

del items[0]           # delete by index
print(items)           # ['d']

items.clear()          # remove everything
print(items)           # []
```

`remove` raises a `ValueError` if the item isn't there, so check with `in` first when unsure.

## Searching

```python
langs = ["python", "sql", "javascript", "sql"]
print("sql" in langs)        # True
print(langs.index("sql"))    # 1: first position
print(langs.count("sql"))    # 2
```

## Sorting

```python
nums = [42, 7, 19, 3]
nums.sort()                  # sorts the list in place
print(nums)                  # [3, 7, 19, 42]
nums.sort(reverse=True)
print(nums)                  # [42, 19, 7, 3]

names = ["rohan", "Asha", "meera"]
print(sorted(names))                  # ['Asha', 'meera', 'rohan']: capitals first
print(sorted(names, key=str.lower))   # ['Asha', 'meera', 'rohan']: case-insensitive
print(sorted(names, key=len))         # ['Asha', 'rohan', 'meera']: by length
print(names)                          # unchanged: sorted() returns a new list
```

- `list.sort()` changes the list and returns `None`.
- `sorted(list)` returns a new sorted list and leaves the original alone.
- `key=` sorts by a calculated value; very powerful with lists of dictionaries (lesson 10).

A common bug: `nums = nums.sort()` sets `nums` to `None`.

## Looping over lists

```python
scores = [72, 95, 88]

for score in scores:
    print(score)

for position, score in enumerate(scores, start=1):
    print(f"{position}. {score}")

names = ["Asha", "Rohan", "Meera"]
for name, score in zip(names, scores):
    print(f"{name}: {score}")
```

- `enumerate` gives you the position along with each item.
- `zip` walks through several lists side by side.

## Built-in functions with lists

```python
scores = [72, 95, 88, 64]
print(len(scores), sum(scores), min(scores), max(scores))
print(f"Average: {sum(scores) / len(scores):.1f}")
print(any(s < 65 for s in scores))   # True: is any score below 65?
print(all(s >= 60 for s in scores))  # True: are all scores at least 60?
```

## Copying lists

```python
original = [1, 2, 3]
alias = original           # NOT a copy: same list, two names
copy1 = original.copy()    # a real copy
copy2 = original[:]        # also a copy
copy3 = list(original)     # also a copy

original.append(4)
print(alias)   # [1, 2, 3, 4]
print(copy1)   # [1, 2, 3]
```

These are **shallow** copies: if the list contains other lists, those inner lists are still shared. Use `copy.deepcopy()` for a full copy of nested data.

## List comprehensions

A compact way to build a new list from another:

```python
numbers = [1, 2, 3, 4, 5, 6]

squares = [n * n for n in numbers]
evens = [n for n in numbers if n % 2 == 0]
labels = [f"Item {n}" for n in numbers[:3]]

print(squares)   # [1, 4, 9, 16, 25, 36]
print(evens)     # [2, 4, 6]
print(labels)    # ['Item 1', 'Item 2', 'Item 3']
```

Read `[n * n for n in numbers if n > 2]` as "n squared, for each n in numbers, if n is greater than 2". The same with a loop:

```python
numbers = [1, 2, 3, 4, 5, 6]
result = []
for n in numbers:
    if n > 2:
        result.append(n * n)
print(result)    # [9, 16, 25, 36]
```

Comprehensions are idiomatic Python. Keep them short; if one gets complicated, use a normal loop.

## Nested lists

```python
grid = [
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9],
]
print(grid[1][2])                     # 6: row 1, column 2
print([row[0] for row in grid])       # [1, 4, 7]: first column
```

## Unpacking

```python
first, second, *rest = ["gold", "silver", "bronze", "4th", "5th"]
print(first, second, rest)   # gold silver ['bronze', '4th', '5th']
```

## Common mistakes

- **`nums = nums.sort()`**, which sets nums to None.
- **`b = a` thinking it copies.**
- **Changing a list while looping over it** (removing items skips elements). Loop over a copy or build a new list with a comprehension.
- **`IndexError`** from using an index equal to `len(list)`.

## Exercise

1. Create a list of five cities. Add one, insert one at the start, and remove the third.
2. Sort a list of numbers in descending order without changing the original.
3. From `[3, 8, 12, 5, 20, 7]`, make a list of only the numbers over 6, doubled, with a comprehension.
4. Print a numbered shopping list with `enumerate`.
5. Given two lists of names and ages, print "Name is Age" using `zip`.

## Summary

- Lists are ordered and mutable; index and slice them like strings.
- `append`, `insert`, `extend` add; `remove`, `pop`, `del`, `clear` remove.
- `sort()` changes the list; `sorted()` returns a new one; use `key=` for custom order.
- Copy with `.copy()`; build lists concisely with comprehensions.

Next, you'll meet two more collections: tuples and sets.
