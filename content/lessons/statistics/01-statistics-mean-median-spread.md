---
title: Mean, Median, Spread and the Five-Number Summary
slug: statistics-mean-median-spread
subject: statistics
lesson: 1
difficulty: beginner
excerpt: Describe any column of numbers in five seconds: mean vs median, range, IQR, standard deviation and the five-number summary, with Python.
---

Your manager drops a spreadsheet of 10,000 delivery times on your desk and asks: "So, how are we doing?" Nobody wants 10,000 numbers. They want **two or three numbers that tell the story**. That is what descriptive statistics is: a summary you can say in one breath.

Every summary answers two questions:

1. **Centre:** what is a typical value?
2. **Spread:** how much do values differ from each other?

## Centre: mean and median

The **mean** is the everyday "average": add everything, divide by how many. The **median** is the middle value after sorting.

```python
import numpy as np

salaries = np.array([22, 25, 25, 28, 30, 31, 150])   # ₹ thousand per month
print("mean:  ", round(salaries.mean(), 2))
print("median:", np.median(salaries))
```

Output:

```text
mean:   44.43
median: 28.0
```

Six people earn between 22 and 31 thousand. The boss earns 150. The mean says the "typical" salary is about 44 thousand, which is more than **six of the seven people** earn. The median, 28, is honest.

::viz stat-center {"data": [22, 25, 25, 28, 30, 31, 150]}

::tip Rule of thumb: if the mean is far from the median, the data is **skewed** (has a long tail or outliers). Report the median, like news reports do for house prices and incomes.

The **mode** is the most common value. It is most useful for categories ("most popular phone brand"), not for measurements:

```python
from statistics import mode

print(mode([22, 25, 25, 28, 30, 31, 150]))
print(mode(["UPI", "card", "UPI", "cash", "UPI"]))
```

Output:

```text
25
UPI
```

## Spread: how different are the values?

Two cricket batters both average 40 runs. One scores 38, 41, 40, 39, 42. The other scores 0, 95, 2, 88, 15. Same mean, completely different players. Spread tells them apart.

```python
import numpy as np

steady = np.array([38, 41, 40, 39, 42])
risky = np.array([0, 95, 2, 88, 15])

for name, runs in [("steady", steady), ("risky", risky)]:
    print(name, "mean", runs.mean(), "range", runs.max() - runs.min(), "std", round(runs.std(ddof=1), 1))
```

Output:

```text
steady mean 40.0 range 4 std 1.6
risky mean 40.0 range 95 std 47.4
```

- **Range** = max − min. Simple, but one extreme value changes it completely.
- **Standard deviation (std)** = roughly "the typical distance from the mean". Small std: values huddle around the mean. Large std: they are scattered.

::hint Why ddof=1? | When your numbers are a **sample** (some batters' innings, not every innings ever played), dividing by n − 1 instead of n gives a fairer estimate of the real spread. Pandas uses n − 1 by default; NumPy needs `ddof=1`.

### How standard deviation is calculated

```python
import numpy as np

x = np.array([38, 41, 40, 39, 42])
mean = x.mean()
deviations = x - mean
squared = deviations ** 2
variance = squared.sum() / (len(x) - 1)
print("deviations:", deviations)
print("squared:   ", squared)
print("variance:  ", variance)
print("std:       ", round(variance ** 0.5, 3))
```

Output:

```text
deviations: [-2.  1.  0. -1.  2.]
squared:    [4. 1. 0. 1. 4.]
variance:   2.5
std:        1.581
```

Step by step: how far is each value from the mean, square those distances (so negatives don't cancel positives), average them (that is the **variance**), then take the square root to get back to the original units (runs). That is the standard deviation.

## Quartiles and the IQR

Sort the data and cut it into four equal parts. The cut points are the **quartiles**:

- **Q1** (25th percentile): a quarter of values are below it.
- **Q2** (50th percentile): the median.
- **Q3** (75th percentile): three quarters are below it.

The **interquartile range (IQR)** = Q3 − Q1 is the spread of the middle half. Outliers can't move it, which makes it a robust partner for the median.

```python
import numpy as np

times = np.array([18, 20, 21, 22, 22, 23, 25, 26, 28, 30, 31, 75])   # delivery minutes
q1, q2, q3 = np.percentile(times, [25, 50, 75])
iqr = q3 - q1
print("Q1", q1, "median", q2, "Q3", q3, "IQR", iqr)

low, high = q1 - 1.5 * iqr, q3 + 1.5 * iqr
print("outlier fences:", low, high)
print("outliers:", times[(times < low) | (times > high)])
```

Output:

```text
Q1 21.75 median 24.0 Q3 28.5 IQR 6.75
outlier fences: 11.625 38.625
outliers: [75]
```

The **1.5 × IQR rule** is the standard way to flag outliers (it is exactly what a box plot draws as dots). The 75-minute delivery is flagged: maybe the rider got lost, maybe it is a data entry error. Either way, look at it before trusting averages.

## The five-number summary in one line

Minimum, Q1, median, Q3, maximum: five numbers that sketch the whole distribution. Pandas gives you all of it (plus mean, std and count) with `describe()`:

```python
import pandas as pd

df = pd.DataFrame({"minutes": [18, 20, 21, 22, 22, 23, 25, 26, 28, 30, 31, 75]})
print(df["minutes"].describe().round(2))
```

Output:

```text
count    12.00
mean     28.42
std      15.20
min      18.00
25%      21.75
50%      24.00
75%      28.50
max      75.00
Name: minutes, dtype: float64
```

Read it like a sentence: "12 deliveries, typically 24 minutes (median), the middle half took between about 21.75 and 28.5 minutes, and at least one took 75." The mean (28.42) is pulled up by that one slow order.

## Summary

| Question | Robust to outliers | Sensitive to outliers |
|---|---|---|
| Centre | median | mean |
| Spread | IQR | standard deviation, range |

- Always look at **both** a centre and a spread.
- Mean far from median? The data is skewed: prefer median and IQR.
- `df.describe()` is the first line of every data analysis.

Next lesson: probability, and the one formula that fixes most people's wrong intuition about it.
