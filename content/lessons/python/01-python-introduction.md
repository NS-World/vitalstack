---
title: Python Introduction
slug: python-introduction
subject: python
lesson: 1
difficulty: beginner
excerpt: What Python is, why it's the most popular first programming language, what people build with it, and your first Python program.
---

Python is a programming language known for being **easy to read** and **powerful at the same time**. It's the most popular language for beginners, and also one of the most used by professionals: in data science, AI and machine learning, automation, web back-ends and scientific research.

## Your first Python program

```python
print("Hello, world!")
print(2 + 3)
print("Python", "is", "fun")
```

Output:

```text
Hello, world!
5
Python is fun
```

`print()` shows a value on the screen. Passing several values separated by commas prints them with spaces in between. Compared with many languages, there's very little extra: no semicolons, no curly braces, no `main` function required.

## Why Python is beginner-friendly

Here's a program that greets a list of people:

```python
names = ["Asha", "Rohan", "Meera"]

for name in names:
    if name.startswith("A"):
        print(f"Hello, {name}! Your name starts with A.")
    else:
        print(f"Hi, {name}!")
```

Even without knowing Python, you can probably guess what it does. That readability is Python's main design goal: code is read far more often than it's written.

## Indentation is part of the language

Notice the spaces at the start of some lines. In Python, **indentation defines blocks of code**. The lines indented under `for` belong to the loop; the lines under `if` belong to the condition.

```python
age = 20
if age >= 18:
    print("You can vote.")      # inside the if (4 spaces)
    print("Don't forget your ID.")
print("This always runs.")       # back at the left: outside the if
```

Other languages use `{ }` for blocks and indentation is just style. In Python it's required, which forces everyone's code to be neatly formatted. The standard is **4 spaces** per level; your editor inserts them when you press Tab.

## What people build with Python

| Area | Examples | Popular libraries |
|---|---|---|
| AI and machine learning | Chatbots, image recognition, recommendations | PyTorch, scikit-learn, Transformers |
| Data analysis | Sales reports, charts, cleaning spreadsheets | pandas, NumPy, Matplotlib |
| Automation | Renaming 1,000 files, filling forms, sending reports | built-in modules, Selenium |
| Web back-ends | APIs and websites (Instagram, Pinterest) | Django, Flask, FastAPI |
| Scripting and DevOps | Server tasks, deployment tools | built-in modules |
| Education and science | Simulations, research | Jupyter notebooks |

Learning Python opens all these doors, and it's the main language for working with AI.

## Python is interpreted

You don't compile Python into a program file first. The **Python interpreter** reads your code and runs it line by line. That makes it quick to experiment: write a line, run it, see the result.

The trade-off: pure Python is slower than languages like C or Go. In practice, heavy work (maths, AI) is done by libraries written in fast languages, and Python acts as the easy-to-write "glue".

## Python 3

There were two major versions: Python 2 and Python 3. Python 2 reached its end of life in 2020. Everything in this course is **Python 3**, and that's the only version you should learn. If an old tutorial writes `print "hello"` without brackets, it's Python 2.

## Comments

```python
# This is a comment. Python ignores it.
print("Comments explain why the code does something.")  # comments can go here too
```

## Where Python runs

- On your computer, after installing it (next lesson).
- In the browser, with our free [Python Online Compiler](/tools/python-online-compiler/) or **Google Colab**. Great for practising right now.
- On servers, in the cloud, even on small devices like the Raspberry Pi.

**Note:** Python examples on VitalStack don't have a Try it Yourself button (that runs only HTML and JavaScript). Copy them into an online Python editor or your own computer.

## How to learn effectively

- **Type the examples yourself** instead of copy-pasting. Your fingers learn the syntax.
- **Change things** and predict the output before running.
- **Read error messages from the bottom up**: the last line says what went wrong; the lines above say where.
- **Build tiny projects** as soon as possible: a tip calculator, a quiz, a file renamer.

## Common mistakes

- **Inconsistent indentation** (mixing tabs and spaces, or 2 and 4 spaces) → `IndentationError`.
- **Missing colon** at the end of `if`, `for`, `def` lines → `SyntaxError`.
- **Capital letters**: `Print()` is not `print()`. Python is case-sensitive.
- **Following Python 2 tutorials.**

## Exercise

1. Open an online Python editor (or install Python in the next lesson) and print your name.
2. Print the result of `365 * 24` (hours in a year).
3. Print three words on one line using commas in `print()`.
4. Change the greeting program to greet four people, with a special message for names starting with "R".

## Summary

- Python is readable, beginner-friendly, and used for AI, data, automation and the web.
- `print()` shows output; indentation (4 spaces) defines code blocks.
- Learn Python 3 only.
- Practise in an online editor or on your own computer.

Next, you'll install Python and set up an editor.
