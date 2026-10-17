## python-introduction
Q: What does indentation do in Python?
- [x] It defines code blocks
- [ ] Nothing, it's style only
- [ ] It marks comments
> Inconsistent indentation causes errors.

Q: Python is…
- [x] Interpreted: code runs line by line without a separate compile step
- [ ] Compiled to .exe only
- [ ] Only for web pages
> python script.py runs it directly.

Q: How do you write a comment?
- [x] `# comment`
- [ ] `// comment`
- [ ] `<!-- comment -->`
> Everything after # on the line is ignored.

## python-installing-python
Q: On Windows, which installer option is important?
- [x] Add python.exe to PATH
- [ ] Install for Python 2
- [ ] Disable pip
> Otherwise the terminal can't find python.

Q: What is the REPL?
- [x] The interactive shell where you type code and see results immediately
- [ ] A code editor
- [ ] A package
> Start it by typing python.

Q: How do you check your Python version?
- [x] `python --version`
- [ ] `python -check`
- [ ] `pip version`
> Use Python 3.10 or newer.

## python-syntax
Q: Python is case-sensitive, so `Name` and `name` are…
- [x] Different variables
- [ ] The same variable
- [ ] Errors
> Use consistent lowercase_with_underscores.

Q: What is a docstring?
- [x] A string right after def/class that documents it
- [ ] A comment with #
- [ ] A file name
> Tools like help() show docstrings.

Q: What usually causes IndentationError?
- [x] Mixing tabs and spaces or misaligned blocks
- [ ] Long lines
- [ ] Using quotes
> Use 4 spaces per level.

## python-variables
Q: How do you swap a and b?
- [x] `a, b = b, a`
- [ ] `swap(a, b)`
- [ ] `a = b; b = a`
> Tuple unpacking swaps in one line.

Q: How do you print "Asha is 21" with f-strings?
- [x] `print(f"{name} is {age}")`
- [ ] `print("{name} is {age}")`
- [ ] `print(f(name, age))`
> The f prefix enables {} expressions.

Q: Python constants are written…
- [x] In UPPER_CASE by convention
- [ ] With const
- [ ] With final
> Python doesn't enforce them.

## python-data-types
Q: What is `type(3.0)`?
- [x] float
- [ ] int
- [ ] decimal
> Numbers with a decimal point are floats.

Q: Which is mutable?
- [x] list
- [ ] tuple
- [ ] str
> Strings and tuples are immutable.

Q: What does `int("42") + 1` give?
- [x] 43
- [ ] "421"
- [ ] Error
> Convert strings before arithmetic.

## python-strings
Q: What does `"python"[1:4]` return?
- [x] "yth"
- [ ] "pyt"
- [ ] "ytho"
> The end index is excluded.

Q: What does `"  hi  ".strip()` return?
- [x] "hi"
- [ ] "  hi"
- [ ] "hi  "
> strip removes surrounding whitespace.

Q: How do you join ["a", "b", "c"] with commas?
- [x] `",".join(["a", "b", "c"])`
- [ ] `["a","b","c"].join(",")`
- [ ] `join(",", list)`
> join is a string method.

## python-numbers
Q: What does `7 // 2` give?
- [x] 3
- [ ] 3.5
- [ ] 4
> // is floor division; / always gives a float.

Q: What does `7 % 2` give?
- [x] 1
- [ ] 3
- [ ] 0
> % is the remainder.

Q: For exact money calculations use…
- [x] decimal.Decimal
- [ ] float
- [ ] round() everywhere
> Floats have tiny rounding errors.

## python-lists
Q: How do you add an item to the end of a list?
- [x] `items.append(x)`
- [ ] `items.add(x)`
- [ ] `items.push(x)`
> insert(i, x) adds at a position.

Q: What does `[n * n for n in range(4)]` produce?
- [x] [0, 1, 4, 9]
- [ ] [1, 4, 9, 16]
- [ ] [0, 1, 2, 3]
> A list comprehension.

Q: `b = a` for a list means…
- [x] Both names refer to the same list
- [ ] b is a copy
- [ ] Error
> Use a.copy() or a[:] to copy.

## python-tuples-and-sets
Q: What's special about a set?
- [x] No duplicates and fast membership checks
- [ ] It keeps insertion order for indexing
- [ ] It can't change
> {1, 2, 2} becomes {1, 2}.

Q: Can you change a tuple after creating it?
- [ ] Yes
- [x] No, tuples are immutable
- [ ] Only the first item
> Use tuples for fixed records like coordinates.

Q: How do you create an empty set?
- [x] `set()`
- [ ] `{}`
- [ ] `[]`
> {} creates an empty dictionary.

## python-dictionaries
Q: How do you read a key safely with a default?
- [x] `d.get("key", 0)`
- [ ] `d["key"] or 0`
- [ ] `d.read("key")`
> d["missing"] raises KeyError.

Q: How do you loop over keys and values?
- [x] `for k, v in d.items():`
- [ ] `for k, v in d:`
- [ ] `for d in k, v:`
> items() yields pairs.

Q: Can a list be a dictionary key?
- [ ] Yes
- [x] No, keys must be immutable (hashable)
- [ ] Only empty lists
> Use a tuple instead.

## python-if-else
Q: Which keyword checks another condition after if?
- [x] elif
- [ ] else if
- [ ] elseif
> Python uses elif.

Q: How do you check for None?
- [x] `if x is None:`
- [ ] `if x == None:` is preferred
- [ ] `if x = None:`
> is checks identity.

Q: What does `"a" in "java"` return?
- [x] True
- [ ] False
- [ ] Error
> in checks membership in strings, lists, dicts and sets.

## python-loops
Q: What does `range(1, 5)` produce?
- [x] 1, 2, 3, 4
- [ ] 1, 2, 3, 4, 5
- [ ] 0, 1, 2, 3, 4
> The stop value is excluded.

Q: Which gives index and value together?
- [x] enumerate(items)
- [ ] range(items)
- [ ] zip(items)
> zip pairs items from several lists.

Q: Why not remove items from a list while looping over it?
- [x] Items get skipped; build a new list instead
- [ ] It's faster
- [ ] Python forbids it with an error always
> Use a comprehension to filter.

## python-functions
Q: What does a function return without return?
- [x] None
- [ ] 0
- [ ] ""
> Return values explicitly.

Q: What does `*args` collect?
- [x] Extra positional arguments as a tuple
- [ ] Keyword arguments
- [ ] Nothing
> **kwargs collects keyword arguments as a dict.

Q: Why is `def add(item, items=[])` dangerous?
- [x] The default list is shared between calls
- [ ] It's a syntax error
- [ ] Lists can't be defaults ever
> Use items=None and create a list inside.

## python-modules
Q: What does `if __name__ == "__main__":` do?
- [x] Runs code only when the file is executed directly, not imported
- [ ] Defines main()
- [ ] Imports the module
> Keeps modules reusable.

Q: Which module gives random numbers?
- [x] random
- [ ] math
- [ ] os
> random.randint(1, 6) rolls a die.

Q: A package is…
- [x] A folder of modules
- [ ] A single function
- [ ] A zip file only
> Import with dotted names.

## python-files
Q: Why use `with open(...) as f`?
- [x] The file is closed automatically, even on errors
- [ ] It's faster
- [ ] It's required for reading
> A context manager.

Q: Which mode appends to a file?
- [x] "a"
- [ ] "w"
- [ ] "r"
> "w" overwrites.

Q: Which module reads CSV files properly?
- [x] csv
- [ ] json
- [ ] pathlib only
> csv.DictReader gives dicts per row.

## python-errors-and-exceptions
Q: Which catches a bad int conversion?
- [x] `except ValueError:`
- [ ] `except TypeError:`
- [ ] `except KeyError:`
> int("abc") raises ValueError.

Q: When does `finally` run?
- [x] Always, after try/except
- [ ] Only on errors
- [ ] Only without errors
> Use it for cleanup.

Q: How do you raise your own error?
- [x] `raise ValueError("Amount must be positive")`
- [ ] `throw ValueError()`
- [ ] `error("...")`
> Custom exceptions subclass Exception.

## python-classes-and-objects
Q: What is `__init__`?
- [x] The initialiser that sets up a new object's attributes
- [ ] The destructor
- [ ] A module
> It runs when you create an object.

Q: What is `self`?
- [x] The current object, passed automatically to methods
- [ ] A keyword for classes
- [ ] The parent class
> Always the first method parameter.

Q: What do dataclasses generate?
- [x] __init__, __repr__ and __eq__
- [ ] Database tables
- [ ] Web pages
> Less boilerplate for data classes.

## python-pip-and-packages
Q: Why use a virtual environment?
- [x] Each project gets its own isolated packages and versions
- [ ] It makes Python faster
- [ ] It's required by pip
> python -m venv .venv.

Q: How do you record dependencies?
- [x] `pip freeze > requirements.txt`
- [ ] `pip save`
- [ ] Copy site-packages
> pip install -r requirements.txt restores them.

Q: What should you check before installing a package?
- [x] The exact name, popularity and maintenance (beware typosquatting)
- [ ] Nothing
- [ ] Only the logo
> Malicious look-alike packages exist.

## python-json-and-apis
Q: json.dumps converts…
- [x] Python objects to JSON text
- [ ] JSON text to Python
- [ ] Files to strings
> json.loads does the reverse.

Q: In JSON, Python's True becomes…
- [x] true
- [ ] True
- [ ] "True"
> None becomes null.

Q: Why check the response status when calling an API?
- [x] Errors like 404 or 500 return data you shouldn't treat as success
- [ ] It's optional decoration
- [ ] Python requires it
> requests has response.raise_for_status().

## python-project-expense-tracker
Q: How are expenses stored between runs?
- [x] Saved to a JSON (or CSV) file and loaded at start
- [ ] Kept in memory only
- [ ] Printed to the screen
> Persistence makes it a real tool.

Q: Why a separate function for getting valid input?
- [x] It retries until the user enters valid data, keeping the main code clean
- [ ] Python requires it
- [ ] For speed
> Reusable input helpers.

Q: How are totals per category computed?
- [x] A dictionary that adds each expense's amount to its category
- [ ] By sorting
- [ ] By deleting rows
> The counting/grouping pattern.
