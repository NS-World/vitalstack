---
title: Python Strings
slug: python-strings
subject: python
lesson: 6
difficulty: beginner
excerpt: Create strings, index and slice them, use the essential string methods, format text with f-strings, and loop over characters.
---

Strings hold text, and text is everywhere: names, messages, file contents, web pages, AI prompts. Python's strings come with dozens of built-in methods that make text processing short and readable.

## Creating strings

```python
single = 'Hello'
double = "It's Python"
multi = """Line one
Line two"""
escaped = "She said \"hi\"\nNew line\tTab"

print(single)
print(double)
print(multi)
print(escaped)
```

Escape sequences: `\n` (new line), `\t` (tab), `\\` (backslash), `\"` and `\'` (quotes).

A **raw string** (prefix `r`) ignores escapes, which is handy for Windows paths and regular expressions:

```python
print(r"C:\new\test")   # backslashes kept as they are
```

## Indexing

```python
word = "Python"
print(word[0])    # P: first character
print(word[5])    # n
print(word[-1])   # n: last character
print(word[-2])   # o
print(len(word))  # 6
```

Indexes start at 0. Negative indexes count from the end. `word[6]` would raise an `IndexError`.

## Slicing

`text[start:stop]` takes characters from `start` up to, but **not including**, `stop`:

```python
word = "Programming"
print(word[0:3])    # Pro
print(word[3:7])    # gram
print(word[:3])     # Pro: from the start
print(word[3:])     # gramming: to the end
print(word[-4:])    # ming: last 4
print(word[::2])    # Pormig: every second character
print(word[::-1])   # gnimmargorP: reversed
```

Slices never raise errors for out-of-range values; they just return what's available.

## Strings are immutable

```python
name = "asha"
# name[0] = "A"          # TypeError: 'str' object does not support item assignment
name = name.capitalize() # create a new string and reassign
print(name)              # Asha
```

Every string method returns a **new** string. Remember to store the result.

## Changing case

```python
s = "learn PYTHON fast"
print(s.upper())       # LEARN PYTHON FAST
print(s.lower())       # learn python fast
print(s.title())       # Learn Python Fast
print(s.capitalize())  # Learn python fast
print(s.swapcase())    # LEARN python FAST
```

## Cleaning: strip

```python
raw = "   asha@example.com  \n"
print(repr(raw.strip()))     # 'asha@example.com'
print(repr(raw.lstrip()))    # only the left side
print("--hello--".strip("-"))  # hello
```

Always `.strip()` user input and lines read from files.

## Searching

```python
text = "Python is easy. Python is powerful."

print("easy" in text)             # True
print(text.startswith("Python"))  # True
print(text.endswith("."))         # True
print(text.find("is"))            # 7: first position, or -1 if not found
print(text.count("Python"))       # 2
```

`in` is the most Pythonic way to check if text contains something.

## Replacing, splitting and joining

```python
sentence = "I like cats. Cats are great."
print(sentence.replace("cats", "dogs"))   # replaces every "cats", but not "Cats": case-sensitive

csv_line = "Asha,21,Pune"
parts = csv_line.split(",")
print(parts)                 # ['Asha', '21', 'Pune']

words = "  many   spaces here ".split()  # no argument: split on any whitespace
print(words)                 # ['many', 'spaces', 'here']

print(" | ".join(["HTML", "CSS", "JS"]))  # HTML | CSS | JS
print("-".join("abc"))                    # a-b-c
```

`split` turns a string into a list; `join` (called on the separator) turns a list back into a string. Together they solve many text tasks.

## Checking content

```python
print("12345".isdigit())     # True
print("hello".isalpha())     # True
print("abc123".isalnum())    # True
print("   ".isspace())       # True
print("Hello".istitle())     # True
```

Useful for simple validation, e.g. checking a PIN code is all digits.

## f-strings in depth

```python
name = "Meera"
price = 1499.5
qty = 3
ratio = 0.4567

print(f"{name} bought {qty} items")
print(f"Total: ₹{price * qty:,.2f}")   # ₹4,498.50
print(f"Ratio: {ratio:.1%}")           # 45.7%
print(f"|{name:<10}|")                 # left-align in 10 characters
print(f"|{name:>10}|")                 # right-align
print(f"|{name:^10}|")                 # centre
print(f"{7:03d}")                      # 007: zero-padded
print(f"{name.upper()} has {len(name)} letters")
```

Format specs after the colon: `,` (thousands separator), `.2f` (2 decimals), `%` (percentage), `<` `>` `^` (alignment and width), `03d` (zero-padding). Alignment is perfect for printing simple tables.

## Looping over a string

```python
word = "banana"
vowels = 0
for letter in word:
    if letter in "aeiou":
        vowels += 1
print(f"{word} has {vowels} vowels")
```

## Comparing strings

```python
print("apple" == "apple")        # True
print("Apple" == "apple")        # False: case matters
print("Apple".lower() == "apple".lower())  # True
print("apple" < "banana")        # True: alphabetical
```

For case-insensitive comparisons of international text, `.casefold()` is more thorough than `.lower()`.

## A practical example: make a URL slug

```python
def slugify(title):
    cleaned = "".join(ch if ch.isalnum() or ch == " " else "" for ch in title)
    return "-".join(cleaned.lower().split())

print(slugify("  Python Strings: The Complete Basics! "))
# python-strings-the-complete-basics
```

The `ch for ch in title` part is a generator expression, a compact loop you'll understand better after the Lists and Loops lessons.

## Common mistakes

- **Forgetting strings are immutable**: `name.upper()` alone does nothing visible; assign the result.
- **Off-by-one slices**: `s[0:3]` gives 3 characters, indexes 0, 1, 2.
- **Case-sensitive comparisons and replacements.**
- **Joining non-strings**: `", ".join([1, 2])` fails; convert first: `", ".join(map(str, [1, 2]))`.

## Exercise

1. Take `"  hello WORLD  "` and turn it into `"Hello world"`.
2. Print the first 3 and last 3 characters of your full name.
3. Check if a word is a palindrome using slicing (`word == word[::-1]`).
4. Count how many times "the" appears in a sentence, ignoring case.
5. Turn `"red,green,blue"` into `"RED / GREEN / BLUE"`.
6. Print a 3-row table of names and scores, aligned with f-string widths.

## Summary

- Index with `s[i]`, slice with `s[start:stop:step]`; indexes start at 0, negatives count from the end.
- Strings are immutable; methods return new strings.
- Key methods: `strip`, `lower`/`upper`, `replace`, `split`, `join`, `find`, `count`, `startswith`.
- f-strings with format specs handle numbers, percentages and alignment.

Next, you'll work with numbers and maths in Python.
