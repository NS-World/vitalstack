---
title: Python Modules
slug: python-modules
subject: python
lesson: 14
difficulty: intermediate
excerpt: Import modules, explore the standard library, write your own modules, understand if __name__ == "__main__", and organise code into packages.
---

As programs grow, one long file becomes hard to manage. **Modules** let you split code into files and reuse it. Python also ships with a huge **standard library** of ready-made modules: "batteries included". Knowing what's in it saves you from reinventing the wheel.

## What is a module?

Any `.py` file is a module. Python's own modules work the same way: `math`, `random`, `datetime` are just Python (or compiled) files installed with Python.

## Importing

```python
import math

print(math.sqrt(81))
print(math.pi)
```

`import math` loads the module once, and you use its contents with `math.name`. The prefix makes it clear where each name comes from.

### Other ways to import

```python
from math import sqrt, pi          # import specific names
print(sqrt(16), pi)

import datetime as dt              # import with a shorter alias
print(dt.date.today().year >= 2024)

from random import choice as pick  # alias a single name
print(pick(["a", "b", "c"]))
```

Avoid `from module import *`: it dumps every name into your file, making it unclear where things come from and risking name clashes.

Some aliases are universal conventions: `import numpy as np`, `import pandas as pd`.

## A tour of the standard library

### datetime: dates and times

```python
from datetime import date, datetime, timedelta

today = date(2024, 5, 1)
deadline = today + timedelta(days=14)
print(deadline)                              # 2024-05-15
print((deadline - today).days)               # 14
print(deadline.strftime("%d %b %Y"))         # 15 May 2024

moment = datetime.fromisoformat("2024-05-01T14:30:00")
print(moment.hour, moment.strftime("%I:%M %p"))   # 14 02:30 PM
```

### random, math and statistics

```python
import statistics

marks = [72, 95, 88, 64, 88]
print(statistics.mean(marks))     # 81.4
print(statistics.median(marks))   # 88
print(statistics.mode(marks))     # 88
```

### os and pathlib: files and folders

```python
from pathlib import Path

folder = Path("reports")
folder.mkdir(exist_ok=True)
(folder / "summary.txt").write_text("All good.")

for path in folder.iterdir():
    print(path.name, path.suffix, path.stat().st_size, "bytes")
```

`pathlib` makes paths work the same on Windows, macOS and Linux. `/` joins path parts.

### json: reading and writing JSON

```python
import json

data = {"name": "Asha", "skills": ["Python", "SQL"], "active": True}
text = json.dumps(data, indent=2)
print(text)
print(json.loads(text)["skills"][0])   # Python
```

More in the "Working with JSON and APIs" lesson.

### collections: smarter containers

```python
from collections import Counter, defaultdict

print(Counter("mississippi").most_common(2))   # [('i', 4), ('s', 4)]

groups = defaultdict(list)
for name, city in [("Asha", "Pune"), ("Rohan", "Delhi"), ("Kabir", "Pune")]:
    groups[city].append(name)
print(dict(groups))
```

### Other useful modules

| Module | For |
|---|---|
| `csv` | Reading and writing CSV spreadsheets |
| `re` | Regular expressions (text patterns) |
| `itertools` | Advanced looping tools |
| `time` | Pausing (`time.sleep`) and timing |
| `sys` | Command-line arguments, Python version |
| `secrets` | Secure random tokens and passwords |
| `sqlite3` | A full SQL database in a file |
| `urllib`, `http` | Basic web requests and servers |
| `unittest` | Writing tests |

The official documentation at docs.python.org lists them all.

## Writing your own module

Create two files in the same folder.

`pricing.py`:

```python
"""Helpers for prices and tax."""

GST_RATE = 0.18

def with_tax(price, rate=GST_RATE):
    return round(price * (1 + rate), 2)

def format_inr(amount):
    return f"₹{amount:,.2f}"
```

`shop.py`:

```text
import pricing

price = pricing.with_tax(1499)
print(pricing.format_inr(price))      # ₹1,768.82
```

Run `python shop.py`. Python finds `pricing.py` because it's in the same folder as the script you ran. Now any other script can reuse your pricing logic.

## if __name__ == "__main__"

When Python runs a file directly, it sets the special variable `__name__` to `"__main__"`. When the file is **imported**, `__name__` is the module's name instead. This lets a file be both a reusable module and a runnable script:

```python
def with_tax(price, rate=0.18):
    return round(price * (1 + rate), 2)

def main():
    print("Quick check:", with_tax(100))

if __name__ == "__main__":
    main()   # runs only when you execute this file directly, not on import
```

Use this pattern in every script: it keeps test code and command-line behaviour from running when someone imports your functions.

## Packages: folders of modules

A **package** is a folder of modules, usually with an `__init__.py` file:

```text
my_app/
├── main.py
└── utils/
    ├── __init__.py
    ├── pricing.py
    └── dates.py
```

Import with dots:

```text
from utils.pricing import with_tax
from utils import dates
```

Large projects (Django, pandas) are organised as packages of packages.

## How Python finds modules

When you `import x`, Python searches, in order:

1. The folder of the script you ran.
2. The standard library.
3. Installed third-party packages (from pip, next-but-one lesson).

That's why you must **never name your file after a module you use**: a file called `random.py` or `json.py` in your folder hides the real module, and `import random` loads yours instead, with confusing errors like "module 'random' has no attribute 'randint'".

## Exploring a module

```python
import math
print(dir(math)[:10])      # names inside the module
help(math.ceil)            # documentation for one function
```

In VS Code, hover over a name or Ctrl+click it to jump to its source.

## Common mistakes

- **Naming your files** `math.py`, `random.py`, `email.py`, `json.py`…
- **`from module import *`.**
- **Circular imports**: `a.py` imports `b.py` which imports `a.py`. Move shared code into a third module.
- **Forgetting `if __name__ == "__main__":`**, so importing a file runs its script code.

## Exercise

1. Use `datetime` to print how many days are left until 31 December of this year.
2. Use `statistics` to find the mean and median of 10 numbers.
3. Create a module `textutils.py` with `word_count(text)` and `reverse_words(text)`, and use it from another file.
4. Add a `main()` function with the `__name__` check to your module and test both running and importing it.
5. Use `Counter` to find the three most common words in a paragraph.

## Summary

- Every `.py` file is a module; `import` reuses its code.
- `from x import y` and `import x as alias` control naming; avoid `import *`.
- The standard library covers dates, files, JSON, maths, randomness and much more.
- Use `if __name__ == "__main__":` for script code, and organise big projects into packages.

Next, you'll read and write files.
