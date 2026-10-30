---
title: Cleaning Data
slug: pandas-cleaning-data
subject: pandas
lesson: 5
difficulty: intermediate
excerpt: Find and fix missing values, duplicates, wrong types and messy text, the work that takes most of a data analyst's day.
---

Analysts say 80% of the job is cleaning data. Real files have empty cells, the same row twice, numbers stored as text and names typed five different ways. Clean data first, or every answer after it is wrong.

Here's a messy table, like a real export:

```python
import pandas as pd
import numpy as np

df = pd.DataFrame({
    "name": ["Asha", "rahul ", "Meera", "Kabir", "Asha", "Zoya"],
    "city": ["Pune", "Surat", None, "Pune", "Pune", "Delhi"],
    "marks": [82, np.nan, 90, np.nan, 82, 74],
    "fees": ["1,200", "900", "1,500", "1,100", "1,200", "N/A"],
})
print(df)
```

Output:

```text
     name   city  marks   fees
0    Asha   Pune   82.0  1,200
1  rahul   Surat    NaN    900
2   Meera   None   90.0  1,500
3   Kabir   Pune    NaN  1,100
4    Asha   Pune   82.0  1,200
5    Zoya  Delhi   74.0    N/A
```

## 1. Missing values

First count them, column by column:

```python
print(df.isna().sum())
```

Output:

```text
name     0
city     1
marks    2
fees     0
dtype: int64
```

Then decide, column by column: **drop** the rows, or **fill** the gaps. Compare both:

::viz pd-missing {"fix": "fillna(mean)"}

```python
df["marks"] = df["marks"].fillna(df["marks"].mean())
df["city"] = df["city"].fillna("Unknown")
print(df[["name", "city", "marks"]])
```

Output:

```text
     name     city  marks
0    Asha     Pune   82.0
1  rahul     Surat   82.0
2   Meera  Unknown   90.0
3   Kabir     Pune   82.0
4    Asha     Pune   82.0
5    Zoya    Delhi   74.0
```

The mean of 82, 90, 82 and 74 is 82, so both gaps got 82. `df.dropna()` would instead remove every row with a gap.

::tip Fill numbers with the mean or median, categories with "Unknown" or the most common value. Drop rows only when very few are missing, or when the missing column is the thing you want to predict.

## 2. Duplicates

```python
print(df.duplicated().sum())
df = df.drop_duplicates()
print(len(df))
```

Output:

```text
1
5
```

Row 4 was an exact copy of row 0, so it's gone.

## 3. Wrong types: numbers stored as text

`fees` looks like numbers but is text because of the commas and the "N/A". Remove the commas, then convert; `errors="coerce"` turns anything that still isn't a number into NaN:

```python
df["fees"] = pd.to_numeric(df["fees"].str.replace(",", ""), errors="coerce")
print(df["fees"].tolist())
print(df["fees"].dtype)
```

Output:

```text
[1200.0, 900.0, 1500.0, 1100.0, nan]
float64
```

Now you can do maths on fees: `df["fees"].sum()`.

## 4. Messy text

`"rahul "` has a trailing space and a lower-case letter, so it would count as a different person from "Rahul". The `.str` methods fix a whole column at once:

```python
df["name"] = df["name"].str.strip().str.title()
print(df["name"].tolist())
```

Output:

```text
['Asha', 'Rahul', 'Meera', 'Kabir', 'Zoya']
```

Other handy ones: `.str.lower()`, `.str.upper()`, `.str.replace("old", "new")`, `.str.split(",")`.

## 5. Rename columns

```python
df = df.rename(columns={"marks": "score", "fees": "fees_inr"})
print(df.columns.tolist())
```

Output:

```text
['name', 'city', 'score', 'fees_inr']
```

## A cleaning checklist

1. `df.info()` and `df.isna().sum()`: what's missing, what types are wrong?
2. Fix types (`pd.to_numeric`, `pd.to_datetime`, `astype`).
3. Clean text (`str.strip`, `str.title`).
4. Handle missing values (`fillna` or `dropna`).
5. Remove duplicates.
6. Check again with `describe()`: do min and max look possible?

::hint Quick check | Marks of `-5` or `950` in an exam out of 100 aren't missing, but they're wrong. `describe()` shows them immediately in the min and max rows.

## Summary

- `isna().sum()` counts missing values; `fillna` / `dropna` fix them.
- `drop_duplicates()` removes repeated rows.
- `pd.to_numeric(..., errors="coerce")` turns text into numbers.
- `.str` methods clean a whole text column at once.
