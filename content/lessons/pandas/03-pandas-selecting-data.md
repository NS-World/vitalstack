---
title: Selecting Rows and Columns
slug: pandas-selecting-data
subject: pandas
lesson: 3
difficulty: beginner
excerpt: Choose columns, and pick rows by label with loc or by position with iloc.
---

Before you can analyse data, you need to point at the part you care about. Pandas has three tools: square brackets for columns, `loc` for labels and `iloc` for positions.

We'll use this table in every example:

```python
import pandas as pd

df = pd.DataFrame({
    "name": ["Asha", "Rahul", "Meera", "Kabir", "Zoya"],
    "city": ["Pune", "Surat", "Delhi", "Pune", "Delhi"],
    "marks": [82, 64, 91, 58, 77],
    "age": [20, 21, 19, 22, 20],
})
```

## Columns

```python
print(df["name"].tolist())
print(df[["name", "marks"]])
```

Output:

```text
['Asha', 'Rahul', 'Meera', 'Kabir', 'Zoya']
    name  marks
0   Asha     82
1  Rahul     64
2  Meera     91
3  Kabir     58
4   Zoya     77
```

One name in brackets → a Series. A **list** of names → a DataFrame.

## iloc: by position

`iloc` works like NumPy: integer positions, starting at 0, end not included.

```python
print(df.iloc[0])
print(df.iloc[1:3, 0:2])
```

Output:

```text
name     Asha
city     Pune
marks      82
age        20
Name: 0, dtype: object
    name   city
1  Rahul  Surat
2  Meera  Delhi
```

## loc: by label

`loc` uses the index labels and column **names**. Unlike `iloc`, the end of a label slice **is included**:

```python
print(df.loc[2, "name"])
print(df.loc[1:3, ["name", "city"]])
```

Output:

```text
Meera
    name   city
1  Rahul  Surat
2  Meera  Delhi
3  Kabir   Pune
```

It's even more useful when the index has meaningful labels:

```python
people = df.set_index("name")
print(people.loc["Zoya"])
print(people.loc[["Asha", "Meera"], "marks"])
```

Output:

```text
city     Delhi
marks       77
age         20
Name: Zoya, dtype: object
name
Asha     82
Meera    91
Name: marks, dtype: int64
```

::tip Rule of thumb: use `loc` when you know names, `iloc` when you know positions. In real analysis `loc` is used far more, because row numbers change after filtering and sorting.

## Changing a value safely

Use `loc` with both row and column to update a cell:

```python
df.loc[3, "marks"] = 61
print(df.loc[3])
```

Output:

```text
name     Kabir
city      Pune
marks       61
age         22
Name: 3, dtype: object
```

::hint Avoid | `df["marks"][3] = 61` (two separate brackets) may change a temporary copy instead of your table, and Pandas warns about it. Always use `df.loc[row, column] = value`.

## Summary

| You want | Code |
|---|---|
| One column | `df["col"]` |
| Several columns | `df[["a", "b"]]` |
| Rows by position | `df.iloc[0:3]` |
| Rows/columns by label | `df.loc[1:3, ["a", "b"]]` (end included) |
| Change a cell | `df.loc[row, "col"] = value` |
