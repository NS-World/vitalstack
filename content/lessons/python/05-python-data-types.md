---
title: Python Data Types
slug: python-data-types
subject: python
lesson: 5
difficulty: beginner
excerpt: Python's built-in types (int, float, str, bool, None, list, tuple, dict and set), checking types, converting between them, and truthy and falsy values.
---

Every value in Python has a **type** that decides what you can do with it. You can add numbers, uppercase text, and append to lists, but not the other way round. This lesson is a tour of Python's built-in types; the following lessons explore each in depth.

## Overview

```python
count = 42                         # int: whole number
price = 19.99                      # float: decimal number
name = "Asha"                      # str: text
is_member = True                   # bool: True or False
nothing = None                     # NoneType: "no value"
scores = [92, 87, 95]              # list: ordered, changeable
point = (10, 20)                   # tuple: ordered, unchangeable
user = {"name": "Asha", "age": 21} # dict: key → value pairs
tags = {"python", "sql"}           # set: unique items, no order

for value in [count, price, name, is_member, nothing, scores, point, user, tags]:
    print(type(value).__name__, value)
```

## Numbers: int and float

```python
print(7 + 2, 7 - 2, 7 * 2)   # 9 5 14
print(7 / 2)                 # 3.5: / always gives a float
print(7 // 2)                # 3: whole-number (floor) division
print(7 % 2)                 # 1: remainder
print(2 ** 10)               # 1024: power
print(10 ** 30)              # ints can be as big as you like
```

- `int` has no size limit.
- `float` has about 15–17 significant digits, and some decimals can't be stored exactly: `0.1 + 0.2` gives `0.30000000000000004`. The Numbers lesson covers how to handle this.

## Strings: str

```python
greeting = "Hello"
print(greeting + ", world")   # joining
print(greeting * 3)           # HelloHelloHello
print(len(greeting))          # 5
print(greeting[0])            # H
```

Strings are **immutable**: methods like `.upper()` return a new string. Full details in the Strings lesson.

## Booleans: bool

```python
is_open = True
is_raining = False
print(5 > 3)          # True
print(5 == 3)         # False
print(True + True)    # 2: True behaves like 1, False like 0
```

`True` and `False` start with capital letters.

## None

`None` represents "no value" or "nothing yet":

```python
winner = None

if winner is None:
    print("No winner yet.")
```

Compare with `None` using `is` (not `==`). Functions that don't return anything return `None`.

## Collections

| Type | Example | Ordered | Changeable | Duplicates |
|---|---|---|---|---|
| `list` | `[1, 2, 2]` | Yes | Yes | Yes |
| `tuple` | `(1, 2, 2)` | Yes | No | Yes |
| `dict` | `{"a": 1}` | Yes (insertion order) | Yes | Keys unique |
| `set` | `{1, 2}` | No | Yes | No |

Each gets its own lesson. Quick taste:

```python
cart = ["pen", "notebook"]
cart.append("eraser")
print(cart)

prices = {"pen": 20, "notebook": 60}
print(prices["notebook"])

unique_cities = set(["Pune", "Delhi", "Pune"])
print(unique_cities)  # {'Pune', 'Delhi'} (order may vary)
```

## Checking types

```python
print(type(3.0))                  # <class 'float'>
print(isinstance(3, int))         # True
print(isinstance("3", (int, float)))  # False: checks against several types
```

Prefer `isinstance()` in real code; it also handles subclasses.

## Converting between types

```python
print(int("42") + 8)        # 50
print(float("3.5") * 2)     # 7.0
print(str(99) + " points")  # "99 points"
print(int(7.9))             # 7: int() cuts off the decimals (doesn't round)
print(round(7.9))           # 8
print(bool(0), bool(""), bool("hi"))  # False False True
print(list("abc"))          # ['a', 'b', 'c']
```

### Why conversion matters: input()

`input()` always returns a **string**, even if the user types a number:

```python
# age = input("Your age: ")   # user types 20
age = "20"                    # what input() gives you
# print(age + 1)              # TypeError: can only concatenate str (not "int") to str
print(int(age) + 1)           # 21
```

Python never converts between text and numbers automatically. That's stricter than JavaScript, and it catches bugs early.

Converting invalid text raises an error:

```python
try:
    int("twelve")
except ValueError as error:
    print("Error:", error)
```

You'll learn `try/except` in the Errors and Exceptions lesson.

## Truthy and falsy

In an `if` or `while`, every value counts as true or false. These are **falsy**:

```python
falsy_values = [False, None, 0, 0.0, "", [], (), {}, set()]
for value in falsy_values:
    print(repr(value), bool(value))
```

Everything else is truthy, including `"0"`, `"False"` and `[0]`. This allows short checks:

```python
name = ""
if not name:
    print("Please enter your name.")

items = ["pen"]
if items:
    print(f"You have {len(items)} item(s).")
```

## Mutable vs immutable

- **Immutable** (can't change after creation): `int`, `float`, `str`, `bool`, `None`, `tuple`.
- **Mutable** (can change): `list`, `dict`, `set`.

This matters when several names refer to the same object, and when using values as dictionary keys (keys must be immutable).

## Common mistakes

- **Adding a string and a number**: `"Age: " + 21` → TypeError. Use an f-string or `str()`.
- **Forgetting that `input()` returns text.**
- **Lower-case `true` / `false` / `none`** → NameError.
- **Expecting `int(7.9)` to round.**
- **Using `== None`** instead of `is None`.

## Exercise

1. Create one variable of each type in this lesson and print its type name.
2. Ask (or pretend to ask) for two numbers as strings, convert them, and print their sum.
3. Print `7 / 2`, `7 // 2` and `7 % 2` and explain each result.
4. Predict `bool()` of `"0"`, `0`, `[]`, `[0]` and `None`, then check.
5. Convert `"3.99"` to a float, multiply by 3, and print it rounded to 2 decimals.

## Summary

- Basic types: `int`, `float`, `str`, `bool`, `None`.
- Collections: `list`, `tuple`, `dict`, `set`.
- Check types with `type()` or `isinstance()`; convert with `int()`, `float()`, `str()`, `bool()`, `list()`.
- `input()` returns strings; Python never mixes text and numbers automatically.

Next, you'll work with text in depth: strings.
