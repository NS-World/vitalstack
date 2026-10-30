---
title: Python Online Compiler
slug: python-online-compiler
parent: tools
template: page-tool.php
icon: 🐍
order: 10
excerpt: Write and run Python 3 code online, free, with input() support. Runs in your browser: no install, no sign-up.
---
## How to use the Python online compiler

1. Type or paste your code in **main.py**.
2. If your program calls `input()`, put the answers in the **Input** box, one line per `input()` call.
3. Press **Run** (or **Ctrl + Enter**). The output appears on the right.

The first run downloads Python into your browser, which takes a few seconds. After that, runs are instant, even offline in the same tab.

## What you can do here

- Practise every example from the [Python tutorials](/tutorial-category/python/) and the [DSA course](/tutorial-category/dsa/).
- Use the standard library: `math`, `random`, `datetime`, `collections`, `itertools`, `json`, `re` and more.
- Test interview solutions before you write them on a whiteboard.
- **Data science in the browser:** `import numpy`, `pandas`, `matplotlib`, `seaborn`, `scipy` and `scikit-learn` just work. The library downloads the first time you import it (a few seconds), and `plt.show()` draws your chart right below the output. Pick an example from the **Examples** menu to start.

## A few programs to try

```python
# FizzBuzz
for n in range(1, 16):
    print("FizzBuzz" if n % 15 == 0 else "Fizz" if n % 3 == 0 else "Buzz" if n % 5 == 0 else n)
```

```python
# Count words in a sentence
from collections import Counter
text = "the quick brown fox jumps over the lazy dog the end"
print(Counter(text.split()).most_common(3))
```

## Good to know

- **Private:** your code runs on your own device and is never uploaded.
- **Time limit:** a program that runs longer than 20 seconds is stopped, so an accidental infinite loop can't freeze the page. Press **Stop** any time.
- **Saved automatically:** your last program stays in the editor next time you visit (on this device).
- **Limits:** there is no internet access from your code, and files you create disappear when you leave the page. Packages that need a server (like databases or web frameworks) won't run here.

## Frequently asked questions

**Which Python version is this?** Python 3.12, compiled to WebAssembly (Pyodide).

**Why does the first run take a few seconds?** Python itself is downloaded once, then cached by your browser.

**Can I use `input()`?** Yes. Fill the Input box before you press Run; each line answers one `input()` call.
