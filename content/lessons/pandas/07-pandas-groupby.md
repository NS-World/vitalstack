---
title: Grouping and Summarising with groupby
slug: pandas-groupby
subject: pandas
lesson: 7
difficulty: intermediate
excerpt: Answer "per city", "per month", "per product" questions with groupby, agg and pivot tables.
---

Most business questions contain the word **per**: sales per city, average marks per class, orders per month. In Pandas the answer is `groupby`. It works in three steps: **split** the rows into groups, **apply** a calculation to each group, and **combine** the results. Watch it happen:

::viz pd-groupby {"agg": "mean"}

```python
import pandas as pd

df = pd.DataFrame({
    "name": ["Asha", "Rahul", "Meera", "Kabir", "Zoya", "Dev"],
    "city": ["Pune", "Surat", "Delhi", "Pune", "Delhi", "Surat"],
    "marks": [82, 64, 91, 58, 77, 88],
})
print(df.groupby("city")["marks"].mean())
```

Output:

```text
city
Delhi    84.0
Pune     70.0
Surat    76.0
Name: marks, dtype: float64
```

Read it as: "group by city, take the marks column, compute the mean". The cities become the index, sorted A to Z.

## Several statistics at once: agg

```python
summary = df.groupby("city")["marks"].agg(["count", "mean", "max"])
print(summary)
```

Output:

```text
       count  mean  max
city                   
Delhi      2  84.0   91
Pune       2  70.0   82
Surat      2  76.0   88
```

Name the result columns yourself with "named aggregation":

```python
summary = df.groupby("city").agg(
    students=("name", "count"),
    average=("marks", "mean"),
    best=("marks", "max"),
).reset_index()
print(summary)
```

Output:

```text
    city  students  average  best
0  Delhi         2     84.0    91
1   Pune         2     70.0    82
2  Surat         2     76.0    88
```

`reset_index()` turns the city index back into a normal column, which is easier to sort, chart or save.

## A real example: sales by month and product

```python
sales = pd.DataFrame({
    "month": ["Jan", "Jan", "Jan", "Feb", "Feb", "Feb"],
    "product": ["Pen", "Book", "Bag", "Pen", "Book", "Bag"],
    "revenue": [1500, 4200, 8500, 1800, 3900, 9600],
})
print(sales.groupby("month", sort=False)["revenue"].sum())
```

Output:

```text
month
Jan    14200
Feb    15300
Name: revenue, dtype: int64
```

`sort=False` keeps the months in the order they appear instead of alphabetical (which would put Feb first).

## Pivot tables

A **pivot table** puts one category down the side and another across the top, exactly like Excel:

```python
table = sales.pivot_table(index="product", columns="month", values="revenue", aggfunc="sum")
print(table[["Jan", "Feb"]])
```

Output:

```text
month     Jan   Feb
product            
Bag      8500  9600
Book     4200  3900
Pen      1500  1800
```

Now it's easy to see Book sales dropped in February while Bag and Pen grew.

::tip Group by several columns with a list: `df.groupby(["city", "gender"])["marks"].mean()` gives one row per city–gender pair.

::hint Quick check | `df.groupby("city").size()` counts rows in each group, including rows with missing values; `count()` skips missing values. For "how many orders per city", `size()` is usually what you want.

## Summary

- `df.groupby("col")["value"].sum()`: split, apply, combine.
- `.agg([...])` or named aggregation for several results at once.
- `reset_index()` makes the groups a normal column again.
- `pivot_table(index=..., columns=..., values=..., aggfunc=...)` for Excel-style tables.
