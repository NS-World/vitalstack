---
title: Pandas Introduction
slug: pandas-introduction
subject: pandas
lesson: 1
difficulty: beginner
excerpt: What Pandas is, the two building blocks Series and DataFrame, and your first look at a table of data.
---

**Pandas** is Python's spreadsheet. It loads tables of data from CSV, Excel or databases and lets you filter, clean, group and summarise them with a few lines of code. Data analysts and data scientists spend most of their day in Pandas.

It's built on NumPy, so every column is a fast array, but it adds what spreadsheets have: **column names**, **row labels** and handling of **missing values**.

## Install and import

```bash
pip install pandas
```

```python
import pandas as pd
```

`pd` is the short name everyone uses, like `np` for NumPy.

## Series: one column

A **Series** is a single column of values with an index (row labels):

```python
import pandas as pd

marks = pd.Series([72, 85, 64], index=["Asha", "Rahul", "Meera"])
print(marks)
print(marks["Rahul"])
```

Output:

```text
Asha     72
Rahul    85
Meera    64
dtype: int64
85
```

## DataFrame: a whole table

A **DataFrame** is a table: several Series that share the same index. The easiest way to make one is from a dictionary, where each key becomes a column:

```python
import pandas as pd

df = pd.DataFrame({
    "name": ["Asha", "Rahul", "Meera", "Kabir"],
    "city": ["Pune", "Surat", "Delhi", "Pune"],
    "marks": [82, 64, 91, 58],
})
print(df)
```

Output:

```text
    name   city  marks
0   Asha   Pune     82
1  Rahul  Surat     64
2  Meera  Delhi     91
3  Kabir   Pune     58
```

The numbers on the left (0, 1, 2, 3) are the **index**. Pandas adds them automatically.

## First look at any table

These five lines are the first thing professionals run on a new dataset:

```python
print(df.shape)
print(df.columns.tolist())
print(df.dtypes)
```

Output:

```text
(4, 3)
['name', 'city', 'marks']
name     object
city     object
marks     int64
dtype: object
```

- `shape`: (rows, columns).
- `columns`: the column names.
- `dtypes`: the type of each column. Text shows as `object`.

```python
print(df.describe())
```

Output:

```text
           marks
count   4.000000
mean   73.750000
std    15.370426
min    58.000000
25%    62.500000
50%    73.000000
75%    84.250000
max    91.000000
```

`describe()` gives count, mean, spread and quartiles for every number column at once: a full summary in one line.

::tip `df.head()` shows the first 5 rows and `df.tail()` the last 5. With a million-row file, always look at the head first instead of printing everything.

## A column is a Series

```python
print(df["marks"].mean())
print(df["city"].unique())
```

Output:

```text
73.75
['Pune' 'Surat' 'Delhi']
```

::hint Quick check | `df["marks"]` returns a Series (one column). `df[["name", "marks"]]`, with double brackets, returns a smaller DataFrame with two columns.

## Summary

- Pandas = tables in Python; import as `pd`.
- **Series** is one labelled column; **DataFrame** is a table of Series.
- Start every analysis with `shape`, `columns`, `dtypes`, `head()` and `describe()`.
