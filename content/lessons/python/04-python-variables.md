---
title: Python Variables
slug: python-variables
subject: python
lesson: 4
difficulty: beginner
excerpt: Create variables with assignment, understand that names refer to objects, assign several values at once, swap values, use constants, and print variables with f-strings.
---

A **variable** is a name that refers to a value, so you can use and change that value later. Python makes variables very simple: there's no special keyword, you just assign.

## Creating variables

```python
name = "Asha"
age = 21
height_m = 1.62
is_student = True

print(name, age, height_m, is_student)
```

`=` is the **assignment** operator: it stores the value on the right under the name on the left. A variable is created the first time you assign to it. No `let`, `var` or type declaration is needed.

## Changing variables

```python
score = 10
print(score)   # 10

score = 15
print(score)   # 15

score = score + 5
print(score)   # 20
```

`score = score + 5` means: take the current value of `score`, add 5, store the result back in `score`.

### Shortcut operators

```python
points = 100
points += 20   # points = points + 20 → 120
points -= 5    # 115
points *= 2    # 230
points //= 3   # 76 (whole-number division)
print(points)
```

Python has **no** `++` or `--` operators. Use `count += 1`.

## Variables can change type

```python
value = 42
print(type(value))   # <class 'int'>

value = "forty-two"
print(type(value))   # <class 'str'>
```

Python is **dynamically typed**: a variable can refer to any type of value, and `type()` tells you what it currently is. It's flexible, but changing a variable's type midway usually makes code confusing, so avoid it.

## Names refer to objects

In Python, a variable is a **label** attached to a value (an object), not a box containing it. Two names can point to the same object:

```python
a = [1, 2, 3]
b = a          # b refers to the SAME list
b.append(4)
print(a)       # [1, 2, 3, 4]: a changed too!
```

For numbers and strings, this never surprises you, because they can't be changed in place (they're **immutable**). For lists and dictionaries (which are **mutable**), remember that assignment doesn't copy. Use `b = a.copy()` when you need an independent copy. You'll see this again in the Lists lesson.

## Several assignments at once

```python
x, y, z = 1, 2, 3
print(x, y, z)

first, last = "Meera Shah".split()
print(first)
print(last)

a = b = 0       # both names refer to 0
print(a, b)
```

The number of names must match the number of values, or you get a `ValueError`.

### Swapping without a temporary variable

```python
left = "tea"
right = "coffee"

left, right = right, left
print(left, right)   # coffee tea
```

## Constants

Python has no true constants, but by convention names in **UPPER_CASE** shouldn't be changed:

```python
GST_RATE = 0.18
MAX_LOGIN_ATTEMPTS = 5

price = 1000
print(price * (1 + GST_RATE))
```

Putting such values in named constants at the top of a file makes the code self-explaining and easy to update.

## Naming variables well

```python
# Hard to understand
x = 1499
y = 3
z = x * y

# Clear
unit_price = 1499
quantity = 3
order_total = unit_price * quantity
print(order_total)
```

Rules and conventions:

- Use **snake_case**: lower-case words joined with underscores.
- Be descriptive: `user_count`, not `uc`.
- Booleans read well as questions: `is_active`, `has_paid`.
- Don't use names of built-in functions, like `list`, `str`, `sum`, `max` or `input`: you'd hide the real function.

```python
sum = 10          # bad: hides the built-in sum()
# sum([1, 2, 3])  # TypeError: 'int' object is not callable
del sum           # removes our variable, restoring the built-in
print(sum([1, 2, 3]))
```

## Printing variables with f-strings

The easiest way to combine text and variables is an **f-string**: put `f` before the quotes and variables inside `{ }`:

```python
name = "Rohan"
lessons_done = 7
total_lessons = 20

print(f"{name} has finished {lessons_done} of {total_lessons} lessons.")
print(f"Progress: {lessons_done / total_lessons:.0%}")
print(f"Next lesson: {lessons_done + 1}")
```

Output:

```text
Rohan has finished 7 of 20 lessons.
Progress: 35%
Next lesson: 8
```

- Any expression can go inside `{ }`.
- After a colon you can add a **format**: `:.2f` for 2 decimals, `:.0%` for a percentage, `:,` for thousands separators.

```python
amount = 1234567.891
print(f"{amount:,.2f}")   # 1,234,567.89
```

A handy trick for debugging: `print(f"{order_total=}")` prints `order_total=4497`.

## Deleting variables

`del name` removes a name. You'll rarely need it.

## Common mistakes

- **Using a variable before assigning it** → `NameError`.
- **Typos in names**: `totl` instead of `total` creates a new variable or a NameError.
- **Shadowing built-ins** like `list`, `str` or `input`.
- **Forgetting the `f`** in f-strings, which prints the braces literally.
- **Thinking `b = a` copies a list.**

## Exercise

1. Create variables for a product's name, price and quantity, and print a line like "3 × Notebook = ₹360" with an f-string.
2. Start with `balance = 1000`, apply `-= 250` and `+= 75`, then print it.
3. Swap two variables in one line.
4. Create a constant `DISCOUNT = 0.1` and print a price after the discount with 2 decimals.
5. Show that `b = a` for a list doesn't copy it, then fix it with `.copy()`.

## Summary

- `name = value` creates or updates a variable; no keyword or type is needed.
- Names refer to objects; assigning a list to another name doesn't copy it.
- Use snake_case names, UPPER_CASE for constants, and never shadow built-ins.
- f-strings (`f"{value}"`) are the easiest way to show variables in text.

Next, you'll learn Python's built-in data types.
