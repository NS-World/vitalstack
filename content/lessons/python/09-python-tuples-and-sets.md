---
title: Python Tuples and Sets
slug: python-tuples-and-sets
subject: python
lesson: 9
difficulty: beginner
excerpt: Use tuples for fixed groups of values and unpacking, and sets for unique items, fast membership tests and set operations like union and intersection.
---

Lists are great general-purpose collections, but Python has two more that are better for specific jobs. **Tuples** hold a fixed group of values that shouldn't change. **Sets** hold unique items and answer "is this in here?" extremely fast.

## Tuples

A tuple is like a list that **can't be changed** after it's created. It's written with round brackets:

```python
point = (10, 20)
rgb = (37, 99, 235)
person = ("Asha", 21, "Pune")

print(point[0])      # 10
print(person[-1])    # Pune
print(len(rgb))      # 3
print(rgb[0:2])      # (37, 99)
```

Indexing, slicing, `len`, `in`, `count` and `index` all work just like with lists. What you can't do is change it:

```python
point = (10, 20)
# point[0] = 15       # TypeError: 'tuple' object does not support item assignment
point = (15, 20)      # you can make a new tuple and reassign the name
print(point)
```

### The comma makes the tuple

```python
a = (5)      # just the number 5 in brackets
b = (5,)     # a tuple with one item
c = 5, 6     # brackets are optional
print(type(a), type(b), type(c))
```

### Tuple unpacking

Tuples shine when you unpack them into variables:

```python
person = ("Meera", 24, "Jaipur")
name, age, city = person
print(f"{name} ({age}) lives in {city}")

# Functions can return several values as a tuple
def min_max(numbers):
    return min(numbers), max(numbers)

low, high = min_max([7, 2, 9, 4])
print(low, high)   # 2 9

# Swapping uses tuple packing and unpacking
x, y = 1, 2
x, y = y, x
print(x, y)
```

### When to use a tuple instead of a list

- The data is a **fixed record** whose positions have meaning: coordinates `(x, y)`, an RGB colour, a date `(2024, 5, 1)`, a database row.
- You want to **protect** the data from accidental changes.
- You need it as a **dictionary key** or set item (lists can't be keys because they're mutable).

```python
distances = {
    ("Pune", "Mumbai"): 150,
    ("Delhi", "Agra"): 233,
}
print(distances[("Pune", "Mumbai")])
```

A rule of thumb: lists for many items of the **same kind**; tuples for a few items of **different kinds** that belong together.

### Named tuples

When positions get confusing, give them names:

```python
from collections import namedtuple

Product = namedtuple("Product", ["name", "price", "stock"])
pen = Product("Pen set", 250, 30)
print(pen.name, pen.price)   # readable access by name
print(pen[1])                # still works by index
```

## Sets

A set is an **unordered** collection of **unique** items, written with curly braces:

```python
tags = {"python", "sql", "python", "html"}
print(tags)          # {'python', 'sql', 'html'}: duplicate removed, order not guaranteed
print(len(tags))     # 3
```

### Removing duplicates

The most common use of sets:

```python
cities = ["Pune", "Delhi", "Pune", "Jaipur", "Delhi"]
unique = set(cities)
print(unique)              # 3 unique cities
print(sorted(unique))      # ['Delhi', 'Jaipur', 'Pune']: as a sorted list

# Keep the original order while removing duplicates:
print(list(dict.fromkeys(cities)))   # ['Pune', 'Delhi', 'Jaipur']
```

### Fast membership tests

```python
banned_words = {"spam", "scam", "free money"}
message = "this is not spam"

print("spam" in message.split())   # True
print("hello" in banned_words)     # False
```

Checking `in` on a list looks at every item one by one. A set uses hashing and finds the answer almost instantly, even with millions of items. If you check membership often, use a set.

### Adding and removing

```python
skills = {"html", "css"}
skills.add("javascript")
skills.update(["python", "sql"])
skills.discard("css")      # no error if missing
# skills.remove("java")    # KeyError if missing
print(skills)
```

Sets have no indexes (`skills[0]` is an error), because they have no order.

**Empty set:** `{}` creates an empty **dictionary**. Use `set()` for an empty set.

### Set operations

```python
python_devs = {"Asha", "Rohan", "Meera", "Kabir"}
sql_devs = {"Meera", "Kabir", "Sara"}

print(python_devs | sql_devs)   # union: knows either
print(python_devs & sql_devs)   # intersection: knows both → {'Meera', 'Kabir'}
print(python_devs - sql_devs)   # difference: Python but not SQL → {'Asha', 'Rohan'}
print(python_devs ^ sql_devs)   # symmetric difference: exactly one of them

print({"Meera"} <= sql_devs)    # subset check: True
```

The same operations exist as methods: `union`, `intersection`, `difference`, `issubset`.

### A practical example

```python
yesterday = {"asha@x.com", "rohan@x.com", "meera@x.com"}
today = {"rohan@x.com", "meera@x.com", "kabir@x.com"}

print("New subscribers:", today - yesterday)
print("Unsubscribed:", yesterday - today)
print("Stayed:", len(today & yesterday))
```

Comparing two lists of ids or emails is a one-liner with sets.

### What can go in a set

Set items (and dictionary keys) must be **hashable**: immutable types like numbers, strings and tuples. Lists and dictionaries can't go in a set. `frozenset` is an immutable set, which can itself go in a set.

## Choosing a collection

| Need | Use |
|---|---|
| Ordered items you'll change | `list` |
| A fixed record of a few values | `tuple` |
| Unique items, fast lookups, comparisons | `set` |
| Look up values by a key | `dict` (next lesson) |

## Common mistakes

- **`(5)` instead of `(5,)`** for a one-item tuple.
- **`{}` for an empty set** (it's a dict).
- **Expecting a set to keep order** or support indexing.
- **Putting lists into sets** → `TypeError: unhashable type: 'list'`.

## Exercise

1. Create a tuple for a book (title, author, year) and unpack it into three variables.
2. Write a function that returns the average and the highest of a list as a tuple, and unpack the result.
3. Remove duplicates from `[3, 1, 3, 2, 1, 5]` and print them sorted.
4. Given two sets of students in two clubs, print who is in both, who is in only the first, and everyone.
5. Check whether a username is in a set of 5 reserved names.

## Summary

- Tuples are ordered and immutable; great for fixed records, unpacking and dictionary keys.
- Sets hold unique, unordered items; great for removing duplicates and fast `in` checks.
- Set operations `|`, `&`, `-`, `^` compare collections in one line.
- `set()` makes an empty set; `{}` makes an empty dict.

Next, you'll learn dictionaries: Python's key–value collection.
