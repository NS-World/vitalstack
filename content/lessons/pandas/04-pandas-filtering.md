---
title: Filtering Data
slug: pandas-filtering
subject: pandas
lesson: 4
difficulty: beginner
excerpt: Keep only the rows you need with conditions, combine them with & and |, and use isin, between and query.
---

"Show me the students who scored above 70." "Only orders from Pune." Filtering is the question you'll ask most often, and Pandas answers it with the same mask idea you learned in NumPy.

## How a filter works

A condition on a column gives a True/False **mask**, one value per row. Putting the mask in brackets keeps the True rows. Step through it:

::viz pd-filter {"min": 70}

```python
import pandas as pd

df = pd.DataFrame({
    "name": ["Asha", "Rahul", "Meera", "Kabir", "Zoya", "Dev"],
    "city": ["Pune", "Surat", "Delhi", "Pune", "Delhi", "Surat"],
    "marks": [82, 64, 91, 58, 77, 88],
})
mask = df["marks"] > 70
print(mask.tolist())
print(df[mask])
```

Output:

```text
[True, False, True, False, True, True]
    name   city  marks
0   Asha   Pune     82
2  Meera  Delhi     91
4   Zoya  Delhi     77
5    Dev  Surat     88
```

Usually you write it in one line: `df[df["marks"] > 70]`.

## Combining conditions

Use `&` for "and", `|` for "or", `~` for "not", and wrap each condition in brackets:

```python
print(df[(df["marks"] > 70) & (df["city"] == "Delhi")])
print(df[(df["city"] == "Pune") | (df["marks"] >= 90)])
```

Output:

```text
    name   city  marks
2  Meera  Delhi     91
4   Zoya  Delhi     77
    name   city  marks
0   Asha   Pune     82
2  Meera  Delhi     91
3  Kabir   Pune     58
```

## isin and between

```python
print(df[df["city"].isin(["Pune", "Surat"])]["name"].tolist())
print(df[df["marks"].between(60, 80)]["name"].tolist())
```

Output:

```text
['Asha', 'Rahul', 'Kabir', 'Dev']
['Rahul', 'Zoya']
```

- `isin([...])`: the value is one of these.
- `between(a, b)`: from a to b, both included.

## Text filters

```python
print(df[df["name"].str.startswith("A")]["name"].tolist())
print(df[df["name"].str.contains("e", case=False)]["name"].tolist())
```

Output:

```text
['Asha']
['Meera', 'Dev']
```

## query: filters that read like English

```python
print(df.query("marks > 70 and city != 'Delhi'"))
```

Output:

```text
   name   city  marks
0  Asha   Pune     82
5   Dev  Surat     88
```

Inside `query` you can use plain `and`, `or`, `not`. It's easier to read for long conditions.

## Counting matches

```python
print((df["marks"] > 70).sum())
print(len(df[df["city"] == "Pune"]))
```

Output:

```text
4
2
```

::hint Common mistake | `df[df["marks"] > 70 & df["city"] == "Delhi"]` without brackets gives a confusing error, because `&` is evaluated before `>`. Always bracket each condition.

## Practice

1. Find the students from Surat with marks below 80.
2. Find everyone whose name ends with "a".
3. How many students scored 60 or more?

## Summary

- `df[condition]` keeps rows where the condition is True.
- Combine with `&`, `|`, `~` and brackets.
- `isin`, `between`, `.str.contains` and `query` cover most real filters.
