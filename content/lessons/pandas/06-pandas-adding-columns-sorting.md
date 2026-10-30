---
title: New Columns, Sorting and Counting
slug: pandas-adding-columns-sorting
subject: pandas
lesson: 6
difficulty: intermediate
excerpt: Create columns from calculations and conditions, transform values with map and apply, sort, rank and count.
---

Analysis means asking new questions of your data: "What is each order's total?", "Who are the top 3?", "How many orders per city?". This lesson covers the tools for each.

```python
import pandas as pd

orders = pd.DataFrame({
    "order_id": [101, 102, 103, 104, 105, 106],
    "city": ["Pune", "Delhi", "Pune", "Surat", "Delhi", "Pune"],
    "qty": [2, 1, 5, 3, 2, 1],
    "price": [250, 1200, 80, 400, 650, 2000],
})
```

## Columns from calculations

Maths between columns works row by row, like NumPy:

```python
orders["total"] = orders["qty"] * orders["price"]
orders["gst"] = (orders["total"] * 0.18).round(2)
print(orders)
```

Output:

```text
   order_id   city  qty  price  total    gst
0       101   Pune    2    250    500   90.0
1       102  Delhi    1   1200   1200  216.0
2       103   Pune    5     80    400   72.0
3       104  Surat    3    400   1200  216.0
4       105  Delhi    2    650   1300  234.0
5       106   Pune    1   2000   2000  360.0
```

## Columns from conditions

`np.where(condition, if_true, if_false)` labels every row:

```python
import numpy as np

orders["size"] = np.where(orders["total"] >= 1000, "big", "small")
print(orders[["order_id", "total", "size"]])
```

Output:

```text
   order_id  total   size
0       101    500  small
1       102   1200    big
2       103    400  small
3       104   1200    big
4       105   1300    big
5       106   2000    big
```

For more than two groups, `pd.cut` puts numbers into ranges:

```python
orders["band"] = pd.cut(orders["total"], bins=[0, 500, 1500, 5000], labels=["low", "mid", "high"])
print(orders["band"].tolist())
```

Output:

```text
['low', 'mid', 'low', 'mid', 'mid', 'high']
```

## map and apply

`map` swaps values using a dictionary; `apply` runs your own function on every value:

```python
zones = {"Pune": "West", "Surat": "West", "Delhi": "North"}
orders["zone"] = orders["city"].map(zones)
orders["label"] = orders["order_id"].apply(lambda x: f"ORD-{x}")
print(orders[["city", "zone", "label"]].head(3))
```

Output:

```text
    city   zone    label
0   Pune   West  ORD-101
1  Delhi  North  ORD-102
2   Pune   West  ORD-103
```

::tip Prefer column maths and `np.where` over `apply` when you can: they run inside NumPy and are much faster on big tables. Use `apply` for logic that has no built-in version.

## Sorting and top-N

```python
top = orders.sort_values("total", ascending=False).head(3)
print(top[["order_id", "city", "total"]])
```

Output:

```text
   order_id   city  total
5       106   Pune   2000
4       105  Delhi   1300
3       104  Surat   1200
```

Orders 102 and 104 both total 1200, and with a tie either one can come third. If the order of ties matters, sort by a second column too.

Sort by several columns with lists: `sort_values(["city", "total"], ascending=[True, False])`.

## Counting values

```python
print(orders["city"].value_counts())
```

Output:

```text
city
Pune     3
Delhi    2
Surat    1
Name: count, dtype: int64
```

`value_counts()` is the fastest way to see how often each category appears. Add `normalize=True` to get percentages.

::hint Quick check | To find the 3 smallest orders, use `orders.nsmallest(3, "total")`, and `nlargest` for the biggest: shortcuts for sort + head.

## Summary

- New column = calculation on columns: `df["c"] = df["a"] * df["b"]`.
- Labels: `np.where` for two groups, `pd.cut` for ranges.
- `map(dict)` to translate values, `apply(func)` for custom logic.
- `sort_values`, `nlargest` and `value_counts` answer "top" and "how many".
