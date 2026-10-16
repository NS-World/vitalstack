---
title: Python Syntax
slug: python-syntax
subject: python
lesson: 3
difficulty: beginner
excerpt: The basic grammar of Python: statements, indentation and blocks, comments and docstrings, long lines, naming rules and reading error messages.
---

Every language has grammar rules. Python's are few and simple, but strict: one wrong space or a missing colon stops the program. This lesson covers the rules you need for everything that follows.

## Statements

A Python program is a list of **statements**, run from top to bottom. Normally, one statement per line:

```python
price = 250
quantity = 3
total = price * quantity
print(total)
```

No semicolons are needed at the end of lines. (You *can* put two statements on one line with `;`, but nobody does.)

## Blocks and indentation

Statements that end with a **colon** `:` start a **block**: `if`, `else`, `for`, `while`, `def`, `class`, `with`, `try`. The block is everything indented beneath them:

```python
temperature = 31

if temperature > 30:
    print("It's hot.")
    print("Stay hydrated.")
else:
    print("Nice weather.")

print("Done.")
```

Rules:

- The line before a block ends with `:`.
- Every line in the block is indented by the **same** amount, by convention 4 spaces.
- The block ends when the indentation goes back.

Blocks can be nested:

```python
score = 85
submitted = True

if submitted:
    if score >= 80:
        print("Excellent!")
    else:
        print("Submitted.")
else:
    print("Please submit your work.")
```

### Indentation errors

```text
if score > 50:
print("Pass")
```

This gives `IndentationError: expected an indented block`. Mixing tabs and spaces causes similar errors. Let your editor insert 4 spaces when you press Tab (VS Code does this by default for Python).

### An empty block: pass

A block can't be empty. Use `pass` as a placeholder:

```python
def coming_soon():
    pass  # TODO: write this later

coming_soon()
print("No error.")
```

## Comments

```python
# A full-line comment

total = 499 * 3  # a comment after code

# Python has no special multi-line comment.
# Just use several # lines.
```

Write comments that explain **why**, not what. `# add 18% GST` is useful; `# multiply by 1.18` repeats the code.

## Docstrings

A string right at the start of a function, class or file is a **docstring**, the documentation for it:

```python
def area(width, height):
    """Return the area of a rectangle."""
    return width * height

print(area(4, 5))
print(area.__doc__)
```

Editors show docstrings when you hover over the function. Triple quotes `"""` allow several lines.

## Long lines

Keep lines under about 80–100 characters for readability. Inside brackets `()`, `[]` or `{}`, you can break lines freely:

```python
total = (
    120 * 3
    + 250 * 1
    + 299 * 2
)

languages = [
    "HTML",
    "CSS",
    "JavaScript",
    "Python",
]

print(total, len(languages))
```

## Names (identifiers)

Variable and function names:

- Can contain letters, digits and underscores, but can't start with a digit.
- Are case-sensitive: `score`, `Score` and `SCORE` are different.
- Can't be **keywords** like `if`, `for`, `def`, `class`, `True`, `None`, `import`.

Python's naming style (from the official style guide, **PEP 8**):

| Kind | Style | Example |
|---|---|---|
| Variables and functions | snake_case | `total_price`, `get_user()` |
| Constants | UPPER_CASE | `MAX_RETRIES = 3` |
| Classes | PascalCase | `ShoppingCart` |

## Quotes

Strings can use single or double quotes; pick one style and be consistent:

```python
a = 'single'
b = "double"
c = "It's easy"            # double quotes when the text has an apostrophe
d = """Several
lines of text"""
print(a, b, c)
print(d)
```

## Reading error messages

Errors are normal. Python tells you exactly what happened in a **traceback**:

```text
Traceback (most recent call last):
  File "shop.py", line 3, in <module>
    total = price * quantty
NameError: name 'quantty' is not defined. Did you mean: 'quantity'?
```

Read it **from the bottom**:

1. The last line says the **type** of error and a message: a misspelled variable.
2. The lines above show **where**: file `shop.py`, line 3, and the code on that line.

Modern Python even suggests fixes ("Did you mean…?"). Common error types:

| Error | Usually means |
|---|---|
| `SyntaxError` | Grammar problem: missing colon, bracket or quote |
| `IndentationError` | Wrong or inconsistent indentation |
| `NameError` | Using a name that doesn't exist (typo?) |
| `TypeError` | Wrong type, e.g. adding a number to text |
| `ValueError` | Right type, bad value, e.g. `int("abc")` |

## The Zen of Python

Type this in the REPL:

```python
import this
```

It prints 19 short principles behind Python's design, such as "Readability counts" and "Simple is better than complex". They're good advice for any code you write.

## Common mistakes

- **Missing colon** after `if`, `for`, `def`.
- **Mixing tabs and spaces.**
- **Unclosed brackets or quotes**: the error may point to the **next** line, so check the line above too.
- **Using a keyword as a name**, like `class = "A"`.

## Exercise

1. Write an `if/else` that checks whether a number is even, with two `print` lines inside the `if` block.
2. Deliberately remove a colon and read the error. Then remove an indentation and read that error.
3. Write a function with a docstring and print the docstring.
4. Rename these to Python style: `TotalPrice` (a variable), `getuser` (a function), `maxsize` (a constant).

## Summary

- One statement per line; no semicolons.
- A line ending in `:` starts a block; indent the block by 4 spaces.
- `#` starts a comment; `"""docstrings"""` document functions.
- Use snake_case for names, and read tracebacks from the bottom.

Next, you'll store values in variables.
