---
title: Merging Tables and a Mini Project
slug: pandas-merge-project
subject: pandas
lesson: 8
difficulty: intermediate
excerpt: Join tables with merge, stack them with concat, then answer real business questions in a small sales analysis project.
---

Data is usually split across tables: customers in one, orders in another, products in a third. To answer "which city spends the most?", you first have to bring them together. That's `merge` (like SQL's JOIN) and `concat`.

## merge: matching rows by a key

Step through how rows are matched, and switch between inner, left and outer:

::viz pd-merge {"how": "inner"}

```python
import pandas as pd

students = pd.DataFrame({"id": [1, 2, 3, 4], "name": ["Asha", "Rahul", "Meera", "Kabir"]})
scores = pd.DataFrame({"id": [2, 3, 5], "score": [78, 91, 66]})

print(pd.merge(students, scores, on="id", how="inner"))
print(pd.merge(students, scores, on="id", how="left"))
```

Output:

```text
   id   name  score
0   2  Rahul     78
1   3  Meera     91
   id   name  score
0   1   Asha    NaN
1   2  Rahul   78.0
2   3  Meera   91.0
3   4  Kabir    NaN
```

| how | Keeps |
|---|---|
| `inner` | only keys found in **both** tables |
| `left` | every row of the left table |
| `right` | every row of the right table |
| `outer` | every key from either table |

If the key column has different names, use `left_on="student_id", right_on="id"`.

## concat: stacking tables

When two tables have the same columns (January and February orders), put one under the other:

```python
jan = pd.DataFrame({"product": ["Pen", "Book"], "units": [10, 4]})
feb = pd.DataFrame({"product": ["Pen", "Bag"], "units": [12, 1]})
both = pd.concat([jan, feb], ignore_index=True)
print(both)
```

Output:

```text
  product  units
0     Pen     10
1    Book      4
2     Pen     12
3     Bag      1
```

`ignore_index=True` renumbers the rows 0, 1, 2, 3 instead of repeating 0, 1, 0, 1.

## Mini project: which city brings the most revenue?

We have orders and customers in two tables. Let's answer three questions a manager would ask.

```python
customers = pd.DataFrame({
    "customer_id": [1, 2, 3, 4],
    "name": ["Asha", "Rahul", "Meera", "Kabir"],
    "city": ["Pune", "Surat", "Delhi", "Pune"],
})
orders = pd.DataFrame({
    "order_id": [101, 102, 103, 104, 105, 106, 107],
    "customer_id": [1, 2, 1, 3, 4, 3, 1],
    "amount": [500, 1200, 300, 2200, 800, 650, 900],
})

data = pd.merge(orders, customers, on="customer_id", how="left")

# 1. Revenue per city, highest first
print(data.groupby("city")["amount"].sum().sort_values(ascending=False))

# 2. Best customer
spend = data.groupby("name")["amount"].sum()
print("Best customer:", spend.idxmax(), spend.max())

# 3. Average order value
print("Average order:", round(data["amount"].mean(), 2))
```

Output:

```text
city
Delhi    2850
Pune     2500
Surat    1200
Name: amount, dtype: int64
Best customer: Meera 2850
Average order: 935.71
```

`idxmax()` returns the **label** of the largest value (here the customer's name), like `argmax` in NumPy.

::tip This merge → groupby → sort pattern answers a huge share of real business questions. Practise it on any dataset you can find: cricket scores, your phone's screen time, or a Kaggle CSV.

## What you can do now

You've covered the core of Pandas: loading, selecting, filtering, cleaning, new columns, grouping and joining. Next, turn these numbers into charts in the **Data Visualization** course.

## Summary

- `pd.merge(a, b, on="key", how="inner|left|right|outer")` joins tables like SQL.
- `pd.concat([a, b], ignore_index=True)` stacks tables with the same columns.
- merge → groupby → sort answers most "who / where / how much" questions.
