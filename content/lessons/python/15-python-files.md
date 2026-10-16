---
title: Python Files
slug: python-files
subject: python
lesson: 15
difficulty: intermediate
excerpt: Read and write text files safely with open() and with, process files line by line, append data, work with CSV files, and handle paths with pathlib.
---

Programs often need to keep data after they stop: logs, settings, reports, exported spreadsheets. Python makes reading and writing files simple. In this lesson you'll work with text files and CSV files, the format every spreadsheet program understands.

## Writing a file

```python
with open("notes.txt", "w", encoding="utf-8") as f:
    f.write("First line\n")
    f.write("Second line\n")

print("Saved.")
```

- `open(filename, mode)` opens a file and returns a file object.
- Mode `"w"` means **write**: it creates the file, or **erases** it if it already exists.
- `\n` adds a line break; `write` doesn't add one for you.
- `encoding="utf-8"` makes sure all characters (₹, é, Hindi, emoji) are saved correctly on every operating system.

### Why `with`?

`with` automatically **closes** the file when the block ends, even if an error happens. An unclosed file may not have all its data saved, and the operating system can run out of file handles. Always use `with` for files.

## Reading a file

```python
with open("notes.txt", "w", encoding="utf-8") as f:
    f.write("First line\nSecond line\nThird line\n")

with open("notes.txt", encoding="utf-8") as f:
    content = f.read()           # the whole file as one string

print(content)
```

Mode `"r"` (read) is the default.

### Line by line

For big files, read one line at a time instead of loading everything into memory:

```python
with open("notes.txt", "w", encoding="utf-8") as f:
    f.write("apple\nbanana\n\ncherry\n")

with open("notes.txt", encoding="utf-8") as f:
    for number, line in enumerate(f, start=1):
        line = line.strip()          # remove the \n at the end
        if not line:
            continue                 # skip empty lines
        print(number, line)
```

Each line includes its `\n`, so `.strip()` it. You can also get a list of lines with `f.read().splitlines()`.

## Appending

Mode `"a"` adds to the end of the file without erasing it. Perfect for logs:

```python
from datetime import datetime

def log(message):
    with open("app.log", "a", encoding="utf-8") as f:
        f.write(f"{datetime.now():%Y-%m-%d %H:%M:%S} {message}\n")

log("App started")
log("User signed in")

with open("app.log", encoding="utf-8") as f:
    print(f.read())
```

## File modes

| Mode | Meaning |
|---|---|
| `"r"` | Read (default); error if the file doesn't exist |
| `"w"` | Write; creates or **overwrites** |
| `"a"` | Append; creates or adds to the end |
| `"x"` | Create; error if the file already exists |
| `"rb"`, `"wb"` | Read/write **binary** data (images, PDFs) |

Be careful with `"w"`: opening an existing file in write mode deletes its contents immediately.

## Handling missing files

```python
try:
    with open("does_not_exist.txt", encoding="utf-8") as f:
        print(f.read())
except FileNotFoundError:
    print("File not found. Check the name and folder.")
```

You'll learn `try/except` fully in the next lesson.

## pathlib: the modern way to handle paths

```python
from pathlib import Path

reports = Path("reports")
reports.mkdir(exist_ok=True)               # create the folder if needed

file = reports / "summary.txt"             # join paths with /
file.write_text("Sales: ₹12,400\n", encoding="utf-8")
print(file.read_text(encoding="utf-8"))

print(file.exists(), file.name, file.stem, file.suffix)
print(file.resolve())                       # the full absolute path

for txt in reports.glob("*.txt"):           # find files by pattern
    print("Found:", txt)
```

`Path` works on Windows, macOS and Linux without worrying about `\` vs `/`. For small files, `read_text` and `write_text` are a one-line alternative to `open`.

## Where is the file?

Relative paths like `"notes.txt"` are relative to the **current working directory**: the folder you ran Python from, which isn't always where the script lives. To use files next to your script:

```text
from pathlib import Path
HERE = Path(__file__).parent
data_file = HERE / "data.txt"
```

## CSV files

CSV (comma-separated values) is the simplest spreadsheet format. Excel and Google Sheets can open and save it. Use the `csv` module rather than splitting on commas yourself, because values can contain commas inside quotes.

### Writing CSV

```python
import csv

products = [
    {"name": "Notebook", "price": 120, "stock": 50},
    {"name": "Pen set", "price": 250, "stock": 30},
    {"name": "Backpack, large", "price": 1899, "stock": 12},
]

with open("products.csv", "w", newline="", encoding="utf-8") as f:
    writer = csv.DictWriter(f, fieldnames=["name", "price", "stock"])
    writer.writeheader()
    writer.writerows(products)

print(open("products.csv", encoding="utf-8").read())
```

Note `newline=""`, which the csv module needs to avoid blank lines on Windows. "Backpack, large" is automatically quoted because it contains a comma.

### Reading CSV

```python
import csv

with open("products.csv", "w", newline="", encoding="utf-8") as f:
    f.write("name,price,stock\nNotebook,120,50\nPen set,250,30\n\"Backpack, large\",1899,12\n")

with open("products.csv", newline="", encoding="utf-8") as f:
    reader = csv.DictReader(f)
    total_value = 0
    for row in reader:
        value = int(row["price"]) * int(row["stock"])
        total_value += value
        print(f"{row['name']:<16} ₹{value:>7,}")

print(f"Inventory value: ₹{total_value:,}")
```

`DictReader` gives each row as a dictionary keyed by the header row. All values are **strings**, so convert numbers with `int()` or `float()`.

## Reading files in other encodings

If you get a `UnicodeDecodeError`, the file was saved with a different encoding. Files from older Windows programs are often `"cp1252"`; Excel's "CSV UTF-8" format uses `"utf-8-sig"`. Try `open(name, encoding="utf-8-sig")`.

## Common mistakes

- **Forgetting `with`**, leaving files open.
- **Using `"w"` when you meant `"a"`**, erasing data.
- **Forgetting `\n`** when writing lines.
- **Not stripping lines** when reading, leaving `\n` in your data.
- **Treating CSV values as numbers** without converting.
- **Wrong working directory**, so the file "isn't found" even though it exists.

## Exercise

1. Write a program that saves five of your favourite films to `films.txt`, one per line, then reads them back with numbers.
2. Add a "journal" function that appends today's date and a message to `journal.txt`.
3. Count the lines, words and characters in a text file.
4. Write a CSV of three students with name and score, then read it and print the average score.
5. Use `pathlib` to list every `.txt` file in a folder with its size.

## Summary

- `with open(path, mode, encoding="utf-8") as f:` opens files safely; they close automatically.
- `"r"` reads, `"w"` overwrites, `"a"` appends; loop over a file to read line by line.
- `pathlib.Path` builds paths and reads/writes small files easily.
- The `csv` module (`DictReader`, `DictWriter`) handles spreadsheets correctly; convert values from strings.

Next, you'll handle errors gracefully with try and except.
