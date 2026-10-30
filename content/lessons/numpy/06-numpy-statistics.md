---
title: NumPy Statistics and Aggregations
slug: numpy-statistics
subject: numpy
lesson: 6
difficulty: intermediate
excerpt: Sum, mean, median, standard deviation, min, max and argmax, along rows or columns, plus a mini project analysing class results.
---

An **aggregation** turns many numbers into one: the total, the average, the highest. NumPy has a fast function for each, and the `axis` argument lets you do it per row or per column.

## The everyday statistics

```python
import numpy as np

sales = np.array([120, 95, 180, 150, 95, 210, 170])
print(sales.sum(), sales.mean())
print(np.median(sales))
print(sales.min(), sales.max())
print(round(sales.std(), 1))
```

Output:

```text
1020 145.71428571428572
150.0
95 210
40.9
```

- **mean**: the average. **median**: the middle value when sorted; it ignores a few extreme values, so it's often a fairer "typical" number.
- **std** (standard deviation): how spread out the values are. Small = consistent, large = up and down.

## Where is the maximum? argmax

`max()` tells you the value; `argmax()` tells you its **position**, which is often the more useful answer:

```python
import numpy as np

days = np.array(["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"])
sales = np.array([120, 95, 180, 150, 95, 210, 170])
print(sales.argmax(), days[sales.argmax()])
print(days[sales.argmin()])
```

Output:

```text
5 Sat
Tue
```

The best day was Saturday. With a tie (95 on Tuesday and Friday), `argmin` returns the first one.

## axis: per column or per row

For a 2-D table, `axis=0` aggregates **down** each column and `axis=1` **across** each row:

```python
import numpy as np

# rows = students, columns = Maths, Science, English
marks = np.array([[78, 85, 69],
                  [92, 88, 95],
                  [64, 70, 81]])
print(marks.mean(axis=0))
print(marks.mean(axis=1).round(1))
```

Output:

```text
[78.         81.         81.66666667]
[77.3 91.7 71.7]
```

- `axis=0` → one average per **subject** (column).
- `axis=1` → one average per **student** (row).

::tip A trick to remember: the axis you name is the one that disappears. A (3, 3) table with `axis=0` collapses the rows, leaving 3 column results.

## Percentiles

```python
import numpy as np

scores = np.arange(1, 101)
print(np.percentile(scores, [25, 50, 90]))
```

Output:

```text
[25.75 50.5  90.1 ]
```

The 90th percentile means 90% of the values are below it. Exam rank lists and salary reports use this a lot.

## Mini project: class results

Put everything together: who topped, which subject was hardest, and how many students passed every subject.

```python
import numpy as np

names = np.array(["Asha", "Rahul", "Meera", "Kabir", "Zoya"])
subjects = np.array(["Maths", "Science", "English"])
marks = np.array([[78, 85, 69],
                  [92, 88, 95],
                  [64, 70, 81],
                  [45, 58, 62],
                  [88, 79, 91]])

total = marks.sum(axis=1)
print("Topper:", names[total.argmax()], total.max())
print("Hardest subject:", subjects[marks.mean(axis=0).argmin()])
passed_all = (marks >= 50).all(axis=1)
print("Passed every subject:", names[passed_all])
```

Output:

```text
Topper: Rahul 275
Hardest subject: Maths
Passed every subject: ['Asha' 'Rahul' 'Meera' 'Zoya']
```

`(marks >= 50).all(axis=1)` checks, for each student (row), whether **all** their marks are 50 or more. Kabir scored 45 in Maths, so he's not in the list.

## What's next

You now know the core of NumPy. Next, **Pandas** builds on these arrays and adds column names, missing-value handling and easy loading of CSV and Excel files.

## Summary

| Question | Code |
|---|---|
| Total / average / middle | `a.sum()`, `a.mean()`, `np.median(a)` |
| Spread | `a.std()` |
| Position of best / worst | `a.argmax()`, `a.argmin()` |
| Per column / per row | `axis=0` / `axis=1` |
| Every / any value matches | `mask.all()`, `mask.any()` |
