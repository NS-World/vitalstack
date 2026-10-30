---
title: NumPy Indexing and Slicing
slug: numpy-indexing-slicing
subject: numpy
lesson: 3
difficulty: beginner
excerpt: Pick single values, rows, columns and blocks out of an array, and filter it with true/false masks.
---

Once your data is in an array, you need to pull parts out of it: one value, a row, a column, or "every mark above 80". NumPy does all of this with square brackets.

## 1-D arrays work like lists

Positions start at 0, and negative numbers count from the end:

```python
import numpy as np

temps = np.array([31, 33, 29, 35, 34, 30])
print(temps[0])
print(temps[-1])
print(temps[1:4])
print(temps[::2])
```

Output:

```text
31
30
[33 29 35]
[31 29 34]
```

`[1:4]` means "from position 1 up to, but not including, 4". `[::2]` means "every second element".

## 2-D arrays: row, column

For a table, give two positions separated by a comma: `[row, column]`. Watch it step by step:

::viz np-reshape {"rows": 3, "cols": 4}

```python
import numpy as np

m = np.arange(12).reshape(3, 4)
print(m)
print(m[1, 2])
print(m[:, 1])
print(m[0:2, 1:3])
```

Output:

```text
[[ 0  1  2  3]
 [ 4  5  6  7]
 [ 8  9 10 11]]
6
[1 5 9]
[[1 2]
 [5 6]]
```

- `m[1, 2]`: row 1, column 2.
- `m[:, 1]`: the `:` alone means "everything", so this is all rows of column 1.
- `m[0:2, 1:3]`: rows 0–1 and columns 1–2, a small block.

::tip Read `m[a, b]` as "rows a, columns b". If you only give one position, `m[1]`, you get the whole row 1.

## Boolean masks: filtering

This is the feature you'll use most in data analysis. A comparison gives an array of `True`/`False`, called a **mask**. Put the mask inside the brackets to keep only the `True` positions:

```python
import numpy as np

marks = np.array([72, 85, 64, 90, 58, 77])
passed = marks >= 70
print(passed)
print(marks[passed])
print(marks[marks < 60])
```

Output:

```text
[ True  True False  True False  True]
[72 85 90 77]
[58]
```

Combine conditions with `&` (and) and `|` (or). Each condition needs its own brackets:

```python
import numpy as np

marks = np.array([72, 85, 64, 90, 58, 77])
print(marks[(marks >= 70) & (marks < 85)])
print((marks >= 70).sum())
```

Output:

```text
[72 77]
4
```

`True` counts as 1, so `.sum()` of a mask tells you **how many** values match. Here 4 students passed.

::hint Common mistake | Python's `and`/`or` don't work on arrays: `marks > 60 and marks < 80` raises an error. Use `&`, `|` and brackets: `(marks > 60) & (marks < 80)`.

## Changing values

Indexing works on the left of `=` too, which is a fast way to clean data:

```python
import numpy as np

readings = np.array([12, -1, 15, -1, 18])
readings[readings < 0] = 0
print(readings)
```

Output:

```text
[12  0 15  0 18]
```

Every negative (bad) sensor reading became 0 in one line.

## Fancy indexing: a list of positions

```python
import numpy as np

names = np.array(["Asha", "Rahul", "Meera", "Kabir"])
print(names[[0, 2]])
```

Output:

```text
['Asha' 'Meera']
```

## Practice

1. From `np.arange(1, 21)`, keep only the multiples of 3.
2. Make `m = np.arange(16).reshape(4, 4)` and print its last column.
3. Replace every value above 100 in `[95, 120, 80, 150]` with 100.

## Summary

- `a[i]`, `a[start:stop:step]` work like lists.
- 2-D: `m[row, col]`; `:` means "all".
- A comparison makes a mask; `a[mask]` keeps matching values; `mask.sum()` counts them.
- Use `&` and `|` with brackets to combine conditions.
