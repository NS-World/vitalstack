---
title: Python Cheat Sheet
slug: python-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: 🐍
order: 10
excerpt: Python syntax on one page - variables, strings, lists, dicts, loops, functions, classes, files, errors and comprehensions. Free and printable as PDF.
---
## Basics

```python
name = "Asha"            # str
age = 21                 # int
price = 499.5            # float
is_member = True         # bool
nothing = None
print(f"{name} is {age}")          # f-string
x, y = 1, 2; x, y = y, x           # swap
type(age)                          # <class 'int'>
int("42"), float("3.5"), str(10)   # conversions
```

| Operator | Meaning |
|---|---|
| `//` | floor division: `7 // 2 == 3` |
| `%` | remainder: `7 % 2 == 1` |
| `**` | power: `2 ** 10 == 1024` |
| `==`, `!=`, `is` | equal, not equal, same object |
| `and`, `or`, `not` | logic |
| `in` | membership: `"a" in "java"` |

## Strings

```python
s = "  Learn Python  "
s.strip()           # "Learn Python"
s.lower(), s.upper(), s.title()
s.replace("Python", "Java")
"a,b,c".split(",")   # ['a', 'b', 'c']
"-".join(["a", "b"]) # "a-b"
s.startswith("  L"), "Py" in s
"python"[0], "python"[-1], "python"[1:4]   # 'p', 'n', 'yth'
f"{3.14159:.2f}", f"{1234567:,}"           # '3.14', '1,234,567'
```

## Lists

```python
nums = [5, 2, 9]
nums.append(4); nums.insert(0, 1)
nums.pop(); nums.remove(9)
nums.sort(); sorted(nums, reverse=True)
len(nums), sum(nums), max(nums), min(nums)
nums[1:3], nums[::-1]          # slice, reversed copy
squares = [n * n for n in range(5)]
evens = [n for n in nums if n % 2 == 0]
copy = nums.copy()             # b = a does NOT copy
```

## Tuples and sets

```python
point = (3, 4); x, y = point          # immutable
tags = {"java", "sql"}                 # unique items
tags.add("python"); "sql" in tags      # O(1) lookup
a | b, a & b, a - b                    # union, intersection, difference
unique = list(set(items))
```

## Dictionaries

```python
user = {"name": "Asha", "city": "Pune"}
user["age"] = 21
user.get("phone", "N/A")          # safe read
del user["city"]
for key, value in user.items(): ...
counts = {}
for w in words: counts[w] = counts.get(w, 0) + 1
from collections import Counter, defaultdict
Counter(words).most_common(3)
{k: v for k, v in user.items() if v}
```

## Conditions and loops

```python
if marks >= 90:   grade = "A+"
elif marks >= 75: grade = "A"
else:             grade = "B"
label = "Adult" if age >= 18 else "Minor"

for i in range(1, 6): ...          # 1..5
for i, item in enumerate(items): ...
for a, b in zip(list1, list2): ...
while n > 0: n //= 10
break, continue

match command:
    case "start": ...
    case "stop" | "quit": ...
    case _: ...
```

## Functions

```python
def greet(name, greeting="Hello"):
    """Return a greeting."""
    return f"{greeting}, {name}!"

greet("Ravi"); greet(name="Ravi", greeting="Hi")
def total(*nums): return sum(nums)
def config(**opts): return opts
square = lambda x: x * x
sorted(people, key=lambda p: p["age"])
def add(item, items=None):          # never use a mutable default
    items = items or []
```

## Classes

```python
from dataclasses import dataclass

class Account:
    rate = 0.04                         # class attribute
    def __init__(self, owner, balance=0):
        self.owner = owner
        self.balance = balance
    def deposit(self, amount):
        if amount <= 0: raise ValueError("Positive only")
        self.balance += amount
    def __str__(self): return f"{self.owner}: ₹{self.balance}"

class Savings(Account):
    def add_interest(self): self.balance *= 1 + self.rate

@dataclass
class Point:
    x: int
    y: int = 0
```

## Files and JSON

```python
from pathlib import Path
import json, csv

Path("notes.txt").write_text("hello")
text = Path("notes.txt").read_text()
with open("log.txt", "a", encoding="utf-8") as f:
    f.write("new line\n")
data = json.loads('{"a": 1}'); json.dumps(data, indent=2)
with open("data.csv", newline="") as f:
    for row in csv.DictReader(f): ...
```

## Errors

```python
try:
    n = int(input("Number: "))
except ValueError as e:
    print("Not a number:", e)
else:
    print("OK")
finally:
    print("done")
raise ValueError("Invalid amount")
```

## Modules and packages

```bash
python -m venv .venv && source .venv/bin/activate   # Windows: .venv\Scripts\activate
pip install requests
pip freeze > requirements.txt
```

```python
import math, random
from datetime import date, datetime, timedelta
math.sqrt(16); random.randint(1, 6); date.today()
if __name__ == "__main__":
    main()
```
