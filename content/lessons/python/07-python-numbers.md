---
title: Python Numbers
slug: python-numbers
subject: python
lesson: 7
difficulty: beginner
excerpt: Integers and floats, all arithmetic operators, rounding, the math and random modules, exact money calculations with Decimal, and formatting numbers.
---

Python is a favourite language for anything involving numbers, from shopping carts to scientific research. This lesson covers Python's number types, arithmetic, rounding, and the modules that handle maths, randomness and money.

## int and float

```python
students = 32          # int
average = 78.5         # float
big = 1_000_000        # underscores make big numbers readable
print(students, average, big)
print(type(students), type(average))
```

- `int`: whole numbers of any size. `2 ** 200` works fine.
- `float`: decimal numbers with about 15–17 digits of precision.
- An operation with a float gives a float: `3 + 2.0` is `5.0`.

## Arithmetic operators

```python
a, b = 17, 5
print(a + b)    # 22
print(a - b)    # 12
print(a * b)    # 85
print(a / b)    # 3.4: true division, always a float
print(a // b)   # 3: floor division
print(a % b)    # 2: remainder
print(a ** b)   # 1419857: power
print(-a // b)  # -4: floor division rounds DOWN, towards minus infinity
```

`//` and `%` are very useful together:

```python
total_seconds = 3725
hours = total_seconds // 3600
minutes = (total_seconds % 3600) // 60
seconds = total_seconds % 60
print(f"{hours}h {minutes}m {seconds}s")   # 1h 2m 5s

print(divmod(17, 5))   # (3, 2): quotient and remainder in one step
```

Operator precedence follows maths rules: `**` first, then `* / // %`, then `+ -`. Use brackets when in doubt.

## Float precision

```python
print(0.1 + 0.2)              # 0.30000000000000004
print(0.1 + 0.2 == 0.3)       # False

import math
print(math.isclose(0.1 + 0.2, 0.3))  # True
```

Like in every language, binary floats can't store some decimals exactly. Compare floats with `math.isclose()`, round when displaying, and use `Decimal` for money (below).

## Rounding

```python
print(round(3.14159, 2))   # 3.14
print(round(7.5))          # 8
print(round(2.5))          # 2 (!)
print(round(3.5))          # 4

import math
print(math.floor(4.7))     # 4: always down
print(math.ceil(4.2))      # 5: always up
print(math.trunc(-4.7))    # -4: drop the decimals
```

`round()` uses **banker's rounding**: exact halves round to the nearest **even** number, so `round(2.5)` is 2. This avoids bias in large statistics, but surprises people. For "school" rounding of money, use `Decimal`.

`math.ceil` is ideal for counting pages or boxes: 47 items at 10 per page need `math.ceil(47 / 10)` = 5 pages.

## Useful built-in functions

```python
numbers = [12, 7, 25, 3]
print(abs(-9))          # 9
print(min(numbers))     # 3
print(max(numbers))     # 25
print(sum(numbers))     # 47
print(sum(numbers) / len(numbers))  # 11.75: average
print(pow(2, 8))        # 256
```

## The math module

```python
import math

print(math.sqrt(144))         # 12.0
print(math.pi)                # 3.141592653589793
print(math.factorial(5))      # 120
print(math.gcd(24, 36))       # 12
print(math.log10(1000))       # 3.0
print(round(math.sin(math.pi / 2), 2))  # 1.0

radius = 7
area = math.pi * radius ** 2
print(f"Circle area: {area:.2f}")       # 153.94
```

`import math` loads the module; you'll learn more about modules in lesson 14.

## Random numbers

```python
import random

print(random.randint(1, 6))          # a dice roll: 1 to 6 inclusive
print(random.random())               # float from 0.0 up to 1.0
print(random.choice(["rock", "paper", "scissors"]))

cards = ["A", "K", "Q", "J"]
random.shuffle(cards)
print(cards)
print(random.sample(range(1, 50), 6))  # 6 unique lottery numbers
```

For passwords, tokens or anything security-related, use the `secrets` module instead: `secrets.token_hex(16)`.

## Exact decimals for money: Decimal

```python
from decimal import Decimal, ROUND_HALF_UP

price = Decimal("19.99")
qty = 3
total = price * qty
print(total)                         # 59.97 exactly

print(Decimal("0.1") + Decimal("0.2"))   # 0.3 exactly

tax = (total * Decimal("0.18")).quantize(Decimal("0.01"), rounding=ROUND_HALF_UP)
print(tax)                           # 10.79
print(Decimal("2.5").quantize(Decimal("1"), rounding=ROUND_HALF_UP))  # 3
```

- Create Decimals from **strings** (`Decimal("19.99")`), not floats, or the float's imprecision comes along.
- `quantize` rounds to a fixed number of places with the rounding rule you choose.

Banks, shops and accounting software use Decimal (or store amounts as whole paise/cents in ints).

## Converting and validating numbers

```python
print(int("42"), float("3.5"), int(3.99))   # 42 3.5 3
print(int("ff", 16))                        # 255: from hexadecimal
print(bin(10), hex(255))                    # 0b1010 0xff

text = "12a"
if text.isdigit():
    print(int(text))
else:
    print(f"'{text}' is not a whole number")
```

## Formatting numbers

```python
amount = 1234567.891
print(f"{amount:,.2f}")     # 1,234,567.89
print(f"{0.256:.1%}")       # 25.6%
print(f"{42:05d}")          # 00042
print(f"{1500000:.2e}")     # 1.50e+06
```

For Indian-style grouping (12,34,567) or currency symbols for any country, the third-party `babel` package handles locale-aware formatting.

## Common mistakes

- **Comparing floats with `==`.** Use `math.isclose`.
- **Expecting `round(2.5)` to be 3.**
- **Floats for money** in serious applications. Use `Decimal` or integer paise.
- **`Decimal(0.1)`** instead of `Decimal("0.1")`.
- **Integer vs true division confusion**: `/` always returns a float in Python 3.

## Exercise

1. Convert 500 minutes into hours and minutes using `//` and `%`.
2. Calculate the area and circumference of a circle with radius 10, rounded to 2 decimals.
3. Simulate rolling two dice 5 times and print each total.
4. Calculate 3 items at ₹19.99 plus 18% tax using `Decimal`, rounded to 2 places.
5. Find how many pages you need for 123 search results at 20 per page.

## Summary

- `int` for whole numbers, `float` for decimals; `/` gives floats, `//` floors, `%` gives remainders.
- Floats can be imprecise: compare with `math.isclose`, use `Decimal` for money.
- `round()` uses banker's rounding; `math.floor`/`ceil` always go down/up.
- The `math`, `random`, `decimal` and `secrets` modules extend Python's number skills.

Next, you'll store collections of values in lists.
