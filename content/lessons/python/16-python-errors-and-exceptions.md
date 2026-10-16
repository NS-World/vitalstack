---
title: Python Errors and Exceptions
slug: python-errors-and-exceptions
subject: python
lesson: 16
difficulty: intermediate
excerpt: Handle errors with try, except, else and finally, catch specific exceptions, raise your own, create custom exception classes, and read tracebacks.
---

Things go wrong: users type letters where numbers belong, files go missing, networks drop. When Python hits a problem it can't handle, it raises an **exception** and stops the program. With `try` and `except`, you can catch exceptions and respond sensibly instead of crashing.

## Syntax errors vs exceptions

- A **syntax error** means the code isn't valid Python (missing colon, unclosed bracket). The program doesn't start at all. You fix these by correcting the code.
- An **exception** happens while the program runs: the code is valid, but something fails at that moment.

```python
numbers = [1, 2, 3]
# print(numbers[10])   # IndexError: list index out of range
# int("abc")           # ValueError: invalid literal for int() with base 10: 'abc'
# 10 / 0               # ZeroDivisionError: division by zero
# {"a": 1}["b"]        # KeyError: 'b'
# "age" + 5            # TypeError: can only concatenate str (not "int") to str
print("Each commented line above would raise a different exception.")
```

## try and except

```python
text = "abc"

try:
    number = int(text)
    print(f"You entered {number}")
except ValueError:
    print(f"'{text}' is not a whole number.")

print("The program keeps running.")
```

- Code that might fail goes in `try`.
- If an exception of the named type happens, Python jumps to the matching `except` block.
- If nothing fails, the `except` block is skipped.

## Catch specific exceptions

```python
def safe_divide(a, b):
    try:
        return a / b
    except ZeroDivisionError:
        print("Can't divide by zero.")
        return None
    except TypeError:
        print("Both values must be numbers.")
        return None

print(safe_divide(10, 2))
print(safe_divide(10, 0))
print(safe_divide(10, "2"))
```

Several `except` blocks handle different problems differently. You can also catch several types in one block: `except (ValueError, TypeError):`.

### Don't catch everything blindly

```python
# Avoid:
try:
    result = 10 / 0
except:                # catches EVERYTHING, even Ctrl+C and typos in your own code
    print("Something went wrong")
```

A bare `except:` (or `except Exception:` used carelessly) hides real bugs: a misspelled variable becomes "something went wrong" with no clue why. Catch the specific exceptions you expect and know how to handle.

## Getting the error message

```python
try:
    value = int("12.5")
except ValueError as error:
    print("Problem:", error)
    print("Type:", type(error).__name__)
```

`as error` gives you the exception object, whose text explains the problem. It's useful for logging.

## else and finally

```python
def read_number(text):
    try:
        number = float(text)
    except ValueError:
        print(f"Invalid: {text!r}")
    else:
        print(f"Valid number: {number}")     # runs only if no exception
    finally:
        print("Check complete.")             # always runs

read_number("3.14")
read_number("pi")
```

- `else` runs only when the `try` block succeeded. Keep the `try` block small and put the follow-up code in `else`, so you don't accidentally catch errors from it.
- `finally` always runs, error or not. It's for clean-up: closing connections, deleting temporary files, stopping a timer. (`with` blocks do this automatically for files.)

## A real pattern: retrying user input

```python
attempts = iter(["twelve", "-3", "12"])   # simulated user input

while True:
    raw = next(attempts)                   # in a real program: raw = input("Quantity: ")
    try:
        quantity = int(raw)
        if quantity <= 0:
            raise ValueError("must be positive")
    except ValueError as error:
        print(f"'{raw}' isn't valid ({error}). Try again.")
    else:
        break

print(f"Ordering {quantity} items.")
```

## Raising exceptions

Your own functions should raise exceptions when they receive bad input, rather than returning wrong results silently:

```python
def withdraw(balance, amount):
    if amount <= 0:
        raise ValueError("Amount must be positive.")
    if amount > balance:
        raise ValueError(f"Insufficient funds: balance is {balance}.")
    return balance - amount

print(withdraw(1000, 300))

try:
    withdraw(1000, 5000)
except ValueError as error:
    print("Error:", error)
```

`raise ExceptionType("message")` stops the function and passes the problem to the caller, who decides what to do (show a message, retry, log it). This separates **detecting** a problem from **handling** it.

## Custom exceptions

For bigger programs, define your own exception types so callers can catch exactly your errors:

```python
class InsufficientFundsError(Exception):
    """Raised when a withdrawal is larger than the balance."""

class Account:
    def __init__(self, balance):
        self.balance = balance

    def withdraw(self, amount):
        if amount > self.balance:
            raise InsufficientFundsError(f"Need {amount}, have {self.balance}")
        self.balance -= amount

account = Account(500)
try:
    account.withdraw(800)
except InsufficientFundsError as error:
    print("Declined:", error)
```

You'll learn classes properly in the next lesson; a custom exception just needs `class Name(Exception):`.

## EAFP: Python's style

Python code often follows **"Easier to Ask Forgiveness than Permission"**: try the operation and handle the exception, instead of checking everything first.

```python
settings = {"theme": "dark"}

# Look before you leap (LBYL)
if "font_size" in settings:
    size = settings["font_size"]
else:
    size = 16

# EAFP
try:
    size = settings["font_size"]
except KeyError:
    size = 16

print(size)
```

Both are fine; here `settings.get("font_size", 16)` is shortest of all. EAFP is especially useful for files and networks, where the situation can change between checking and acting.

## Reading tracebacks

When an exception isn't caught, Python prints a traceback:

```text
Traceback (most recent call last):
  File "shop.py", line 12, in <module>
    total = checkout(cart)
  File "shop.py", line 7, in checkout
    return sum(item["price"] for item in cart)
  File "shop.py", line 7, in <genexpr>
    return sum(item["price"] for item in cart)
KeyError: 'price'
```

Read it bottom-up: the last line is the error (`KeyError: 'price'`, an item without a price). Above it is the chain of calls, ending with the exact line that failed. Look at the deepest line in **your** code first.

## Common exception types

| Exception | Typical cause |
|---|---|
| `ValueError` | Right type, wrong value: `int("abc")` |
| `TypeError` | Wrong type: `"a" + 1` |
| `KeyError` | Missing dictionary key |
| `IndexError` | List index out of range |
| `AttributeError` | Object has no such attribute or method |
| `FileNotFoundError` | File doesn't exist |
| `ZeroDivisionError` | Dividing by zero |
| `ImportError` / `ModuleNotFoundError` | Module not installed or misspelled |

## Common mistakes

- **Bare `except:`** that hides bugs.
- **Huge `try` blocks** that catch errors from unrelated lines.
- **Silently ignoring errors** (`except: pass`), so problems go unnoticed.
- **Returning error codes** like `-1` instead of raising exceptions.

## Exercise

1. Write `to_int(text)` that returns an int, or `None` with a printed message if conversion fails.
2. Write `get_item(items, index)` that returns the item or `"not found"` using `try/except IndexError`.
3. Read a file name from a variable and print its contents, with a friendly message if it doesn't exist.
4. Write `set_age(age)` that raises `ValueError` for ages below 0 or above 130, and call it inside `try`.
5. Create a custom `OutOfStockError` and raise it from a `buy(product, qty)` function.

## Summary

- Exceptions are runtime errors; `try`/`except` catches them so your program can respond.
- Catch specific exception types; use `as error` to read the message.
- `else` runs on success; `finally` always runs for clean-up.
- `raise` signals problems in your own functions; custom exceptions make errors precise.

Next, you'll model real-world things with classes and objects.
