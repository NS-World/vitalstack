---
title: "An EDA Checklist for Any New Dataset"
slug: eda-checklist
subject: eda
lesson: 1
difficulty: intermediate
excerpt: Exploratory Data Analysis in seven steps: shape, types, missing values, duplicates, distributions, relationships and surprises. A repeatable pandas checklist for every new dataset.
---

A new dataset is like a new city. Before you plan a route, you walk around: which roads exist, which are broken, where the traffic is. **Exploratory Data Analysis (EDA)** is that walk. Skip it and your model will learn from typos, duplicates and missing values without you ever knowing.

::scene The data detective
🗂️ | Case file | A CSV of 10,000 food delivery orders lands on your desk. Your manager wants a model by Friday. | dev
🔍 | Look first | Before any model, you open the file and ask: what is in here, really? | chip
🧩 | Clues | Missing ratings, a delivery time of minus five minutes, the same order twice. | bugsy
📊 | Patterns | Rainy days are slower. Weekend orders are bigger. Now you know what matters. | byte
✅ | Ready | Clean data, clear questions. Now the model has a fair chance. | dev
::end

## The seven-step checklist

Let's build a small delivery dataset with realistic problems and walk through it.

```python
import numpy as np
import pandas as pd

rng = np.random.default_rng(7)
n = 12
df = pd.DataFrame({
    "order_id": [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 110, 111],
    "city": ["Pune", "Mumbai", "pune", "Delhi", "Mumbai", "Delhi", "Pune", "Mumbai", "Delhi", "Pune", "Pune", "Mumbai"],
    "minutes": [32, 41, 28, -5, 55, 38, 30, 47, 120, 35, 35, 44],
    "amount": [250, 480, 199, 320, None, 610, 275, 390, 450, 300, 300, None],
    "rating": [5, 4, None, 3, 2, 4, 5, 4, 1, 5, 5, 4],
})
print(df.shape)
```

Output:

```text
(12, 5)
```

**Step 1: shape and types.** How many rows and columns, and is every column the type you expect?

```python
print(df.dtypes)
```

Output:

```text
order_id      int64
city         object
minutes       int64
amount      float64
rating      float64
dtype: object
```

`rating` became `float64` because one value is missing: pandas stores missing numbers as `NaN`, which is a float.

**Step 2: missing values.**

```python
print(df.isna().sum())
```

Output:

```text
order_id    0
city        0
minutes     0
amount      2
rating      1
dtype: int64
```

**Step 3: duplicates.** Order 110 appears twice:

```python
print(df.duplicated().sum(), "exact duplicate row(s)")
print(df["order_id"].duplicated().sum(), "repeated order_id(s)")
```

Output:

```text
1 exact duplicate row(s)
1 repeated order_id(s)
```

**Step 4: inconsistent categories.** "pune" and "Pune" should be one city:

```python
print(df["city"].value_counts().to_dict())
print(df["city"].str.title().value_counts().to_dict())
```

Output:

```text
{'Pune': 4, 'Mumbai': 4, 'Delhi': 3, 'pune': 1}
{'Pune': 5, 'Mumbai': 4, 'Delhi': 3}
```

**Step 5: impossible and extreme values.** `describe()` shows them at a glance:

```python
print(df["minutes"].describe()[["min", "50%", "max"]])
```

Output:

```text
min     -5.0
50%     36.5
max    120.0
Name: minutes, dtype: float64
```

A delivery can't take −5 minutes: that's a data error. 120 minutes is possible (heavy rain?) but unusual: lesson 2 decides what to do with it.

**Step 6: relationships.** Are slow deliveries rated lower?

```python
print(round(df["minutes"].corr(df["rating"]), 2))
```

Output:

```text
-0.63
```

A clear negative correlation: the longer the wait, the worse the rating. That's the kind of finding a business cares about.

**Step 7: write down what you found.** EDA is only useful if it turns into decisions.

## Watch step 5 catch the impossible value

::walk python | Flag values that can't be true
minutes = [32, 41, -5, 55, 120]
bad = [m for m in minutes if m <= 0]
long = [m for m in minutes if m > 90]
print("impossible:", bad, "| check:", long)
::notes
1: Five delivery times, in minutes. // vars: minutes=[32, 41, -5, 55, 120]
2: A delivery time of zero or less is impossible: those are **errors** to fix or remove. // deep: This is a domain rule, not statistics. Always ask someone who knows the business which values can't happen. // vars: bad=[-5]
3: Over 90 minutes is possible but unusual: a value to **check**, not delete automatically. // vars: long=[120]
4: Two different lists, two different decisions. // out: impossible: [-5] | check: [120]
::end

## The checklist as a reusable function

```python
def eda_report(frame):
    return {
        "rows": len(frame),
        "missing": int(frame.isna().sum().sum()),
        "duplicate_rows": int(frame.duplicated().sum()),
        "numeric_cols": list(frame.select_dtypes("number").columns),
    }

print(eda_report(df))
```

Output:

```text
{'rows': 12, 'missing': 3, 'duplicate_rows': 1, 'numeric_cols': ['order_id', 'minutes', 'amount', 'rating']}
```

::tip Note that `order_id` is numeric but meaningless as a number: averaging IDs makes no sense. Part of EDA is deciding which columns are **identifiers**, which are **categories** and which are real **measurements**.

## Your EDA notes for this dataset

| Finding | Decision |
|---|---|
| Order 110 duplicated | Drop the duplicate |
| "pune" vs "Pune" | Standardise with `.str.title()` |
| −5 minutes | Error: set to missing |
| 120 minutes | Keep, but check with the operations team |
| 2 missing amounts, 1 missing rating | Decide in lesson 2 |
| Longer wait → lower rating | Key business insight |

## See it as a chart: the checklist at a glance

Two quick plots answer two checklist steps at once: how many values are missing in each column, and whether any numbers are impossible or extreme.

```python
import matplotlib.pyplot as plt
import pandas as pd

df = pd.DataFrame({
    "order_id": [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 110, 111],
    "minutes": [32, 41, 28, -5, 55, 38, 30, 47, 120, 35, 35, 44],
    "amount": [250, 480, 199, 320, None, 610, 275, 390, 450, 300, 300, None],
    "rating": [5, 4, None, 3, 2, 4, 5, 4, 1, 5, 5, 4],
})
fig, (left, right) = plt.subplots(1, 2, figsize=(9, 3.3))
missing = df.isna().sum()
left.bar(missing.index, missing.values, color=["#c2255c" if v else "#cfd4dc" for v in missing.values])
left.set_title("Missing values per column")
left.set_ylabel("count")
right.boxplot(df["minutes"], vert=False, widths=0.5)
right.scatter([-5], [1], s=90, facecolors="none", edgecolors="#c2255c", lw=2)
right.annotate("-5 minutes: impossible", (-5, 1), (5, 1.3), arrowprops={"arrowstyle": "->"}, color="#c2255c")
right.annotate("120: check it", (120, 1), (80, 1.3), arrowprops={"arrowstyle": "->"})
right.set_yticks([])
right.set_xlabel("delivery minutes")
right.set_title("Spread and outliers")
fig.tight_layout()
plt.show()
```

::figure eda-checklist-at-a-glance.svg | Left: bar chart with gaps in amount and rating; right: box plot of delivery minutes with -5 circled as impossible and 120 marked as an outlier to check

Make these two plots the first thing you do with any new table. They take seconds and catch the problems that silently break averages and models.

## Summary

- EDA = walk around the data before modelling.
- Seven steps: shape and types, missing values, duplicates, categories, impossible/extreme values, relationships, written decisions.
- `isna`, `duplicated`, `value_counts`, `describe` and `corr` do most of the work.
- Every finding should lead to a decision.
