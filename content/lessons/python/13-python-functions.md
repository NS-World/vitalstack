---
title: Python Functions
slug: python-functions
subject: python
lesson: 13
difficulty: beginner
excerpt: Define functions with def, use parameters, return values, default and keyword arguments, *args and **kwargs, type hints, docstrings, scope and lambda.
---

A **function** is a named, reusable block of code. Functions let you write logic once and use it everywhere, give meaningful names to steps, and test pieces of a program separately. Every good Python program is built from small functions.

## Defining and calling a function

```python
def greet():
    print("Hello! Welcome to VitalStack.")

greet()
greet()
```

- `def` starts the definition, followed by the name, brackets and a colon.
- The indented block is the function's **body**.
- `greet()` calls (runs) it. Defining a function doesn't run it.

## Parameters and arguments

```python
def greet(name):
    print(f"Hello, {name}!")

greet("Asha")
greet("Rohan")
```

`name` is a **parameter** (a placeholder); `"Asha"` is an **argument** (the actual value).

## Returning values

```python
def add(a, b):
    return a + b

result = add(3, 4)
print(result)               # 7
print(add(add(1, 2), 10))   # 13
```

`return` sends a value back to the caller and **ends** the function. A function without `return` returns `None`.

```python
def describe_age(age):
    if age < 0:
        return "invalid"
    if age < 18:
        return "minor"
    return "adult"

print(describe_age(25), describe_age(12), describe_age(-1))
```

### print vs return

```python
def double_print(n):
    print(n * 2)       # shows it, returns None

def double_return(n):
    return n * 2       # gives it back

a = double_print(5)    # prints 10
b = double_return(5)   # prints nothing
print(a, b)            # None 10
```

Return values so other code can use them; print only when the job is to show something.

### Returning several values

```python
def stats(numbers):
    return min(numbers), max(numbers), sum(numbers) / len(numbers)

low, high, average = stats([72, 95, 88, 64])
print(low, high, round(average, 1))   # 64 95 79.8
```

Python returns a tuple, which you can unpack.

## Default values

```python
def make_tea(sugar=1, milk=True):
    return f"Tea with {sugar} sugar(s){' and milk' if milk else ''}"

print(make_tea())
print(make_tea(2))
print(make_tea(0, milk=False))
```

Parameters with defaults must come after those without.

**Never use a mutable default** like `[]` or `{}`:

```python
def add_item(item, basket=None):
    if basket is None:
        basket = []          # a fresh list every call
    basket.append(item)
    return basket

print(add_item("pen"))       # ['pen']
print(add_item("book"))      # ['book'], not ['pen', 'book']
```

A default `[]` is created **once** and shared by every call, a famous Python gotcha. Use `None` and create the list inside.

## Keyword arguments

You can pass arguments by name, in any order:

```python
def create_user(name, role="student", active=True):
    return {"name": name, "role": role, "active": active}

print(create_user("Asha", active=False))
print(create_user(role="teacher", name="Rohan"))
```

Keyword arguments make calls self-explaining: `send_email(to=..., subject=..., urgent=True)` is clearer than `send_email(..., ..., True)`.

## *args and **kwargs

```python
def total(*prices):
    return sum(prices)

print(total(10, 20, 30))      # 60

def describe(**details):
    for key, value in details.items():
        print(f"{key}: {value}")

describe(name="Meera", city="Jaipur", age=24)
```

- `*args` collects extra positional arguments into a tuple.
- `**kwargs` collects extra keyword arguments into a dictionary.

You can also **unpack** into a call:

```python
def area(width, height):
    return width * height

size = (4, 5)
print(area(*size))                     # 20
print(area(**{"width": 2, "height": 3}))  # 6
```

## Docstrings and type hints

```python
def price_with_tax(price: float, rate: float = 0.18) -> float:
    """Return the price including tax, rounded to 2 decimals.

    rate is a fraction: 0.18 means 18%.
    """
    return round(price * (1 + rate), 2)

print(price_with_tax(1000))
help(price_with_tax)
```

- The **docstring** documents what the function does; `help()` and editors show it.
- **Type hints** (`price: float`, `-> float`) document the expected types. Python doesn't enforce them, but editors and tools like mypy use them to catch mistakes. They're very common in modern code.

## Scope

Variables created inside a function are **local**: they exist only during the call.

```python
def calculate():
    result = 6 * 7
    return result

print(calculate())
# print(result)   # NameError: result only existed inside the function
```

Functions can **read** variables from outside, but to **reassign** one you'd need the `global` keyword, which is usually a sign of bad design. Pass values in as parameters and return results instead.

```python
TAX = 0.18                  # a module-level constant: fine to read

def with_tax(price):
    return price * (1 + TAX)

print(with_tax(100))
```

## Functions are objects

You can store functions in variables, put them in lists and pass them to other functions:

```python
def shout(text):
    return text.upper() + "!"

def whisper(text):
    return text.lower() + "…"

for style in (shout, whisper):
    print(style("Hello"))

words = ["banana", "Apple", "cherry"]
print(sorted(words, key=str.lower))   # pass a function as the key
```

## Lambda: tiny anonymous functions

```python
square = lambda n: n * n
print(square(6))   # 36

students = [("Asha", 92), ("Rohan", 78), ("Meera", 85)]
print(sorted(students, key=lambda s: s[1], reverse=True))
```

`lambda params: expression` creates a one-line function without a name. Use lambdas for short `key=` functions; use `def` for anything longer.

## Writing good functions

- **One job per function**, with a name that says what it does: `calculate_total`, `is_valid_email`.
- **Short**: if it doesn't fit on your screen, split it.
- **Input through parameters, output through return**, no hidden global changes.
- **Docstring** for anything non-obvious.

```python
def is_valid_email(email: str) -> bool:
    """Very basic email check: one @ and a dot after it."""
    if email.count("@") != 1:
        return False
    local, domain = email.split("@")
    return bool(local) and "." in domain

print(is_valid_email("asha@example.com"))  # True
print(is_valid_email("asha@example"))      # False
```

## Common mistakes

- **Forgetting to call** the function (`greet` instead of `greet()`).
- **Forgetting `return`**, then getting `None`.
- **Mutable default arguments.**
- **Too many positional parameters**; use keyword arguments for clarity.
- **Relying on global variables** instead of parameters.

## Exercise

1. Write `celsius_to_fahrenheit(c)` and test it with 0, 37 and 100.
2. Write `is_even(n)` returning True or False, and use it with a list comprehension to filter evens.
3. Write `average(*numbers)` that returns 0 for no numbers instead of crashing.
4. Write `format_price(amount, currency="₹", decimals=2)` returning a string like "₹1,499.00".
5. Sort a list of `(product, price)` tuples by price with a lambda.

## Summary

- `def name(params):` defines a function; `name(args)` calls it; `return` gives back a value.
- Defaults, keyword arguments, `*args` and `**kwargs` make functions flexible; avoid mutable defaults.
- Docstrings and type hints document functions.
- Local variables live only inside the call; functions are objects you can pass around; lambdas are one-line functions.

Next, you'll split code into modules and use Python's standard library.
