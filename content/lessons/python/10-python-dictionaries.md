---
title: Python Dictionaries
slug: python-dictionaries
subject: python
lesson: 10
difficulty: beginner
excerpt: Store key-value data in dictionaries: read safely with get, add, update and delete keys, loop over items, nest data, count things, and use dictionary comprehensions.
---

A **dictionary** (`dict`) stores data as **key–value pairs**: a word and its meaning, a product and its price, a setting and its value. You look values up by key instead of by position. Dictionaries are everywhere in Python, and they map directly to JSON, the data format of the web.

## Creating dictionaries

```python
user = {
    "name": "Asha",
    "age": 21,
    "city": "Pune",
    "skills": ["Python", "SQL"],
}

prices = {"pen": 20, "notebook": 60, "bag": 899}
empty = {}
from_pairs = dict(name="Rohan", age=25)

print(user)
print(len(prices))     # 3
```

Keys are usually strings, but can be any immutable value (numbers, tuples). Values can be anything, including lists and other dictionaries. Since Python 3.7, dictionaries keep the order in which keys were added.

## Reading values

```python
user = {"name": "Asha", "age": 21}

print(user["name"])                 # Asha
# print(user["email"])              # KeyError: 'email'

print(user.get("email"))            # None: no error
print(user.get("email", "not set")) # not set: default value
print("age" in user)                # True: checks keys
```

Use `dict[key]` when the key must exist (a missing key is a bug), and `.get(key, default)` when it might be missing.

## Adding, changing and deleting

```python
stock = {"pen": 40, "notebook": 12}

stock["eraser"] = 25          # add a new key
stock["pen"] = 35             # change an existing value
stock["notebook"] += 10       # update based on the old value

del stock["eraser"]           # delete a key (KeyError if missing)
removed = stock.pop("pen")    # delete and return the value
print(removed, stock)         # 35 {'notebook': 22}

stock.update({"ruler": 15, "notebook": 30})  # add/overwrite several keys
print(stock)
```

## Looping over dictionaries

```python
scores = {"Asha": 92, "Rohan": 78, "Meera": 85}

for name in scores:                  # loops over keys
    print(name)

for score in scores.values():
    print(score)

for name, score in scores.items():   # keys and values together
    print(f"{name}: {score}")

print(list(scores.keys()))
```

`.items()` is the most common way to loop over a dictionary.

## Sorting by value

```python
scores = {"Asha": 92, "Rohan": 78, "Meera": 85}

ranking = sorted(scores.items(), key=lambda item: item[1], reverse=True)
for position, (name, score) in enumerate(ranking, start=1):
    print(f"{position}. {name} – {score}")

print(max(scores, key=scores.get))   # Asha: key with the highest value
```

`lambda item: item[1]` is a tiny inline function that returns the value of each `(key, value)` pair; you'll learn lambdas in the Functions lesson.

## Nested data

Real data is often dictionaries inside lists inside dictionaries, exactly like JSON from an API:

```python
order = {
    "id": 1042,
    "customer": {"name": "Rohan", "city": "Delhi"},
    "items": [
        {"product": "Notebook", "price": 120, "qty": 3},
        {"product": "Pen set", "price": 250, "qty": 1},
    ],
    "paid": True,
}

print(order["customer"]["city"])          # Delhi
print(order["items"][0]["product"])       # Notebook

total = sum(item["price"] * item["qty"] for item in order["items"])
print(f"Order {order['id']} total: ₹{total}")   # ₹610
```

Notice `order['id']` with **single** quotes inside the double-quoted f-string.

## A list of dictionaries

The most common shape for tabular data:

```python
students = [
    {"name": "Asha", "score": 92, "city": "Pune"},
    {"name": "Rohan", "score": 78, "city": "Delhi"},
    {"name": "Meera", "score": 85, "city": "Pune"},
]

top = [s["name"] for s in students if s["score"] >= 80]
print(top)                                         # ['Asha', 'Meera']

by_score = sorted(students, key=lambda s: s["score"], reverse=True)
print([s["name"] for s in by_score])               # ['Asha', 'Meera', 'Rohan']

average = sum(s["score"] for s in students) / len(students)
print(f"Average: {average:.1f}")                   # 85.0
```

## Counting and grouping

Counting how often things appear is a classic dictionary task:

```python
words = "the cat and the dog and the bird".split()

counts = {}
for word in words:
    counts[word] = counts.get(word, 0) + 1
print(counts)   # {'the': 3, 'cat': 1, 'and': 2, 'dog': 1, 'bird': 1}

from collections import Counter
print(Counter(words).most_common(2))   # [('the', 3), ('and', 2)]
```

Grouping items by a key:

```python
students = [
    {"name": "Asha", "city": "Pune"},
    {"name": "Rohan", "city": "Delhi"},
    {"name": "Meera", "city": "Pune"},
]

by_city = {}
for s in students:
    by_city.setdefault(s["city"], []).append(s["name"])
print(by_city)   # {'Pune': ['Asha', 'Meera'], 'Delhi': ['Rohan']}
```

`setdefault(key, default)` returns the value for the key, first inserting the default if the key is missing. (`collections.defaultdict(list)` does the same job.)

## Dictionary comprehensions

```python
prices = {"pen": 20, "notebook": 60, "bag": 899}

with_tax = {item: round(price * 1.18, 2) for item, price in prices.items()}
cheap = {item: price for item, price in prices.items() if price < 100}
squares = {n: n * n for n in range(1, 6)}

print(with_tax)
print(cheap)     # {'pen': 20, 'notebook': 60}
print(squares)   # {1: 1, 2: 4, 3: 9, 4: 16, 5: 25}
```

## Merging and copying

```python
defaults = {"theme": "light", "font_size": 16}
user_settings = {"theme": "dark"}

settings = defaults | user_settings      # Python 3.9+: later values win
print(settings)                          # {'theme': 'dark', 'font_size': 16}

copy = settings.copy()                   # shallow copy
copy["theme"] = "blue"
print(settings["theme"])                 # still dark
```

## Common mistakes

- **`KeyError`** from `d[key]` on a missing key; use `.get()` or check with `in`.
- **Looping and changing size**: adding or deleting keys while looping over a dict raises an error. Loop over `list(d)` instead.
- **Using mutable values as keys** (lists) → `TypeError`.
- **Expecting `in` to check values**: `"Asha" in scores.values()` checks values; plain `in` checks keys.

## Exercise

1. Create a dictionary of five countries and their capitals. Print each as "Capital of X is Y".
2. Write a word-frequency counter for a sentence of your choice, ignoring case.
3. Given a list of product dicts with name, category and price, group the names by category.
4. Find the most expensive product with `max(..., key=...)`.
5. Make a dict comprehension that maps each word in a list to its length.

## Summary

- Dictionaries map keys to values; read with `d[key]` or safely with `d.get(key, default)`.
- Add or change with `d[key] = value`; remove with `del` or `pop`.
- Loop with `.items()`; sort with `sorted(d.items(), key=...)`.
- Lists of dicts model tables and JSON; `Counter`, `setdefault` and comprehensions make counting and grouping easy.

Next, you'll make decisions with if, elif and else.
