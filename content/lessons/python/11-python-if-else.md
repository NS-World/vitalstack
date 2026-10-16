---
title: Python If/Else
slug: python-if-else
subject: python
lesson: 11
difficulty: beginner
excerpt: Make decisions with if, elif and else, combine conditions with and, or and not, use membership and identity tests, conditional expressions, and match statements.
---

Programs become useful when they can decide: approve or reject, show a warning or not, charge shipping or give it free. Python's `if` statement runs a block of code only when a condition is true.

## if

```python
temperature = 34

if temperature > 30:
    print("It's hot today.")
    print("Remember to drink water.")

print("Have a nice day.")
```

The condition is followed by a colon, and the block is indented. If the condition is false, the indented lines are skipped.

## else and elif

```python
age = 16

if age >= 18:
    print("You can vote.")
else:
    print(f"You can vote in {18 - age} years.")
```

For more than two options, use `elif` ("else if"):

```python
score = 76

if score >= 90:
    grade = "A"
elif score >= 75:
    grade = "B"
elif score >= 60:
    grade = "C"
elif score >= 40:
    grade = "D"
else:
    grade = "F"

print(f"Score {score}: grade {grade}")   # Score 76: grade B
```

Python checks each condition from the top and runs **only the first** block whose condition is true. That's why the order matters: 95 is also `>= 75`, but it's caught by the first condition.

## Comparison operators

| Operator | Meaning |
|---|---|
| `==` | Equal |
| `!=` | Not equal |
| `<` `>` | Less / greater than |
| `<=` `>=` | Less / greater than or equal |

Python lets you **chain** comparisons, just like in maths:

```python
age = 25
if 18 <= age < 60:
    print("Working age")
```

## and, or, not

```python
age = 22
has_ticket = True
is_vip = False

if age >= 18 and has_ticket:
    print("Welcome to the concert.")

if is_vip or age < 12:
    print("Free entry.")
else:
    print("Standard entry.")

if not has_ticket:
    print("Please buy a ticket.")
```

Python uses the words `and`, `or` and `not` instead of symbols. `and` is evaluated before `or`; use brackets to make mixed conditions clear.

## Truthy checks

Empty strings, empty collections, `0` and `None` count as false:

```python
username = "   ".strip()
cart = []

if not username:
    print("Please enter a username.")

if cart:
    print(f"{len(cart)} items in cart")
else:
    print("Your cart is empty.")
```

This is the Pythonic way: write `if cart:` rather than `if len(cart) > 0:`.

## Membership: in

```python
day = "sat"
if day in ("sat", "sun"):
    print("Weekend!")

email = "asha@example.com"
if "@" not in email:
    print("Invalid email")
else:
    print("Looks like an email")
```

`in` works with strings, lists, tuples, sets and dictionary keys, and replaces long chains of `or`.

## Identity: is

Use `is` to compare with `None` (and `True`/`False` in rare cases):

```python
result = None
if result is None:
    print("No result yet.")
```

`==` asks "are these equal?"; `is` asks "is this the very same object?". For `None`, always use `is`.

## Nested conditions and guard clauses

Nested ifs work but get hard to read:

```python
def can_checkout(user, cart):
    if user is not None:
        if cart:
            if user.get("address"):
                return "OK"
            else:
                return "Add an address"
        else:
            return "Cart is empty"
    else:
        return "Please log in"

print(can_checkout({"address": "Pune"}, ["book"]))
```

The same logic with **guard clauses** (return early on problems):

```python
def can_checkout(user, cart):
    if user is None:
        return "Please log in"
    if not cart:
        return "Cart is empty"
    if not user.get("address"):
        return "Add an address"
    return "OK"

print(can_checkout({"address": "Pune"}, []))
print(can_checkout(None, ["book"]))
```

Flat code is easier to read and extend. (You'll learn `def` and `return` in the Functions lesson.)

## Conditional expressions (one-line if)

```python
age = 20
status = "adult" if age >= 18 else "minor"
print(status)

items = 3
print(f"{items} item{'s' if items != 1 else ''}")
```

`value_if_true if condition else value_if_false`: handy for choosing between two values. Use a full `if` statement for anything longer.

## match: choosing from many patterns (Python 3.10+)

```python
command = "stop"

match command:
    case "start":
        print("Starting…")
    case "stop" | "quit":
        print("Stopping.")
    case "help":
        print("Commands: start, stop, help")
    case _:
        print(f"Unknown command: {command}")
```

- `case "stop" | "quit"` matches either value.
- `case _` matches anything else (like `else`).
- No `break` needed; only one case runs.

`match` can also unpack structures:

```python
point = (0, 5)

match point:
    case (0, 0):
        print("Origin")
    case (0, y):
        print(f"On the y-axis at {y}")
    case (x, 0):
        print(f"On the x-axis at {x}")
    case (x, y):
        print(f"At {x}, {y}")
```

For simple value checks, `if/elif` is often clearer; `match` shines with many fixed options or structured data.

## A practical example: shipping cost

```python
def shipping_cost(total, is_member, city):
    if is_member:
        return 0
    if total >= 500:
        return 0
    if city in {"Pune", "Mumbai"}:
        return 30
    return 60

print(shipping_cost(350, False, "Pune"))    # 30
print(shipping_cost(350, False, "Delhi"))   # 60
print(shipping_cost(800, False, "Delhi"))   # 0
print(shipping_cost(100, True, "Delhi"))    # 0
```

## Common mistakes

- **`=` instead of `==`** in a condition: `if x = 5:` is a SyntaxError in Python (good, it catches the bug).
- **Missing colon** or wrong indentation.
- **Wrong order of elif branches.**
- **`if x == "a" or "b":`** is always true; write `if x in ("a", "b"):`.
- **`== None`** instead of `is None`.

## Exercise

1. Ask for a number (or set one) and print whether it's positive, negative or zero.
2. Write a BMI category checker: under 18.5, 18.5–24.9, 25–29.9, 30 and above.
3. Given an hour (0–23), print "Good morning", "Good afternoon" or "Good evening".
4. Check if a year is a leap year: divisible by 4, except centuries, unless divisible by 400.
5. Write a `match` statement for a simple calculator that handles `+`, `-`, `*` and `/`.

## Summary

- `if`, `elif` and `else` run different blocks based on conditions; the first true branch wins.
- Combine conditions with `and`, `or` and `not`; chain comparisons like `0 < x < 10`.
- Use `in` for membership, `is None` for None, and truthy checks for empty values.
- Conditional expressions and `match` handle short choices and many fixed options.

Next, you'll repeat actions with for and while loops.
