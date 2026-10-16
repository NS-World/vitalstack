---
title: Python Loops
slug: python-loops
subject: python
lesson: 12
difficulty: beginner
excerpt: Repeat code with for and while loops, use range, enumerate and zip, control loops with break, continue and else, and avoid common loop bugs.
---

Loops let a program repeat work: process every line in a file, every product in a list, or keep asking until the user types a valid answer. Python has two loops: `for` (go through a collection) and `while` (repeat while a condition is true).

## for loops

```python
fruits = ["apple", "banana", "cherry"]

for fruit in fruits:
    print(f"I like {fruit}")
```

`for item in collection:` takes each item in turn. It works with lists, tuples, strings, sets, dictionaries, files and more.

```python
for letter in "Hi!":
    print(letter)

prices = {"pen": 20, "bag": 899}
for item, price in prices.items():
    print(f"{item}: ₹{price}")
```

## range(): counting

```python
for i in range(5):
    print(i)           # 0, 1, 2, 3, 4

for i in range(1, 6):
    print(i)           # 1 to 5

for i in range(10, 0, -2):
    print(i)           # 10, 8, 6, 4, 2
```

`range(start, stop, step)` produces numbers from `start` up to, but **not including**, `stop`. With one argument it starts at 0.

```python
n = 7
for i in range(1, 11):
    print(f"{n} × {i} = {n * i}")
```

## enumerate and zip

```python
tasks = ["Write code", "Test", "Deploy"]
for number, task in enumerate(tasks, start=1):
    print(f"{number}. {task}")

names = ["Asha", "Rohan", "Meera"]
scores = [92, 78, 85]
for name, score in zip(names, scores):
    print(f"{name} scored {score}")
```

Avoid writing `for i in range(len(tasks)):` and then `tasks[i]`. `enumerate` and `zip` are clearer and less error-prone.

## Accumulating results

Many loops build up a result:

```python
cart = [("Notebook", 120, 3), ("Pen set", 250, 1), ("USB cable", 299, 2)]

total = 0
expensive = []
for name, price, qty in cart:
    total += price * qty
    if price > 200:
        expensive.append(name)

print(f"Total: ₹{total}")              # ₹1208
print("Items over ₹200:", expensive)
```

## while loops

`while` repeats as long as its condition is true. Use it when you don't know in advance how many rounds you need:

```python
balance = 1000
years = 0

while balance < 2000:
    balance *= 1.08          # 8% interest per year
    years += 1

print(f"Doubled after {years} years: ₹{balance:,.2f}")
```

Make sure something in the loop changes the condition, or it will run forever. If a program gets stuck, press **Ctrl + C** in the terminal to stop it.

### Input validation with while

```python
answers = iter(["abc", "-5", "42"])   # pretend the user types these in turn

while True:
    text = next(answers)              # in a real program: text = input("Enter a positive number: ")
    if text.isdigit() and int(text) > 0:
        number = int(text)
        break
    print(f"'{text}' is not a positive number, try again.")

print(f"Thanks! You entered {number}.")
```

`while True` with a `break` is a common pattern for "keep asking until the answer is valid". (`iter` and `next` here just simulate typing so the example runs on its own.)

## break and continue

```python
numbers = [4, 9, -1, 16, 25]

for n in numbers:
    if n < 0:
        print("Found a negative number, stopping.")
        break
    print(n ** 0.5)

for n in range(1, 11):
    if n % 3 == 0:
        continue        # skip multiples of 3
    print(n, end=" ")
print()
```

- `break` leaves the loop immediately.
- `continue` skips the rest of this round and starts the next one.

`print(..., end=" ")` prints a space instead of a new line at the end.

## Loop else

A `for` or `while` loop can have an `else` block that runs only if the loop finished **without** `break`:

```python
users = ["asha", "rohan", "meera"]
search = "kabir"

for user in users:
    if user == search:
        print("Found!")
        break
else:
    print(f"{search} not found.")
```

It's perfect for search loops: "if we never found it, do this".

## Nested loops

```python
for row in range(1, 4):
    for col in range(1, 4):
        print(row * col, end="\t")
    print()
```

Output:

```text
1	2	3
2	4	6
3	6	9
```

The inner loop runs completely for every round of the outer loop.

## Loops vs comprehensions

When a loop just builds a list, a comprehension is shorter:

```python
prices = [120, 250, 299, 1499]

# loop
with_tax = []
for p in prices:
    with_tax.append(round(p * 1.18))

# comprehension: same result
with_tax = [round(p * 1.18) for p in prices]
print(with_tax)
```

And built-in functions often replace loops entirely: `sum()`, `min()`, `max()`, `any()`, `all()`, `sorted()`.

## Don't change a list while looping over it

```python
numbers = [1, 2, 2, 3, 4]
for n in numbers:
    if n == 2:
        numbers.remove(n)
print(numbers)   # [1, 2, 3, 4]: one 2 was skipped!

numbers = [1, 2, 2, 3, 4]
numbers = [n for n in numbers if n != 2]
print(numbers)   # [1, 3, 4]: correct
```

Removing items shifts the rest, so the loop skips one. Build a new list instead.

## Common mistakes

- **Off-by-one with range**: `range(1, 10)` stops at 9.
- **Infinite while loops** that never update their condition.
- **`for i in range(len(x))`** when you only need the items.
- **Modifying a list while iterating it.**
- **Indentation**: a line accidentally inside (or outside) the loop.

## Exercise

1. Print the numbers 1–20, but "Fizz" for multiples of 3, "Buzz" for multiples of 5, and "FizzBuzz" for both.
2. Sum all even numbers from 1 to 100 with a loop, then with `sum(range(...))`.
3. Count the vowels in a sentence.
4. Use a `while` loop to find how many years it takes ₹10,000 to reach ₹20,000 at 7% per year.
5. Search a list of names with a `for ... else` loop and print a message if the name isn't found.

## Summary

- `for item in collection` loops over items; `range()` produces numbers to count with.
- `enumerate` adds positions; `zip` walks several collections together.
- `while` repeats while a condition is true; `break` exits, `continue` skips ahead.
- A loop's `else` runs when no `break` happened; prefer comprehensions and built-ins for simple loops.

Next, you'll organise code into reusable functions.
