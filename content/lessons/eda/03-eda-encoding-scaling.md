---
title: "Encoding Categories and Scaling Numbers"
slug: eda-encoding-scaling
subject: eda
lesson: 3
difficulty: intermediate
excerpt: Turn text categories into numbers with one-hot, label, ordinal and target encoding, and put numbers on the same scale with standardisation and min-max scaling, using pandas and scikit-learn.
---

Models do maths, and maths needs numbers. "Pune", "Gold plan" and "Large" must become numbers, and a salary in lakhs shouldn't drown out an age in years. That's what **encoding** and **scaling** do.

::scene Translating for the model
🏙️ | The problem | The model sees "Pune", "Delhi", "Mumbai" and has no idea what to do with words. | byte
🔢 | Bad idea | Pune=1, Delhi=2, Mumbai=3? Now the model thinks Mumbai is "three times" Pune. | bugsy
💡 | One-hot | Give each city its own yes/no column. No fake order, no fake distance. | chip
📏 | Scaling | Salary in lakhs, age in years: put them on the same scale so neither shouts louder. | dev
::end

## One-hot encoding (categories with no order)

```python
import pandas as pd

df = pd.DataFrame({"city": ["Pune", "Delhi", "Mumbai", "Pune"],
                   "size": ["Small", "Large", "Medium", "Small"],
                   "salary": [600000, 1500000, 900000, 750000],
                   "age": [24, 41, 30, 27]})
print(pd.get_dummies(df["city"], prefix="city", dtype=int))
```

Output:

```text
   city_Delhi  city_Mumbai  city_Pune
0           0            0          1
1           1            0          0
2           0            1          0
3           0            0          1
```

## Ordinal encoding (categories with an order)

Small < Medium < Large has a real order, so numbers are fine, **if you set the order yourself**:

```python
order = {"Small": 0, "Medium": 1, "Large": 2}
df["size_code"] = df["size"].map(order)
print(df[["size", "size_code"]].values.tolist())
```

Output:

```text
[['Small', 0], ['Large', 2], ['Medium', 1], ['Small', 0]]
```

Don't let a tool assign codes alphabetically (Large=0, Medium=1, Small=2): that would scramble the order.

## Target (mean) encoding for many categories

A pincode column might have 20,000 values: one-hot would create 20,000 columns. **Target encoding** replaces each category with the average target for that category (computed on training data only, to avoid leakage):

```python
train = pd.DataFrame({"pincode": ["411001", "411001", "110001", "110001", "110001"],
                      "churned": [1, 0, 0, 0, 1]})
means = train.groupby("pincode")["churned"].mean()
print(means.round(2).to_dict())
```

Output:

```text
{'110001': 0.33, '411001': 0.5}
```

::tip **Data leakage** is the most common silent mistake in ML: using information from the test set (or the future) while training. Fit encoders and scalers on the training data only, then apply them to the test data.

## Scaling numbers

Distance-based and gradient-based models (k-NN, SVM, logistic regression, neural networks) care about scale. Trees don't.

::walk python | Standardisation, by hand
salary = [6.0, 15.0, 9.0, 7.5]
mean = sum(salary) / len(salary)
spread = (sum((s - mean) ** 2 for s in salary) / len(salary)) ** 0.5
z = [round((s - mean) / spread, 2) for s in salary]
print(z)
::notes
1: Salaries in lakhs. // vars: salary=[6.0, 15.0, 9.0, 7.5]
2: The mean: 9.375 lakhs. // vars: mean=9.375
3: The standard deviation: a typical distance from the mean. // deep: This is the population standard deviation (divide by n), which is what scikit-learn's StandardScaler uses. // vars: spread=3.4255
4: Subtract the mean and divide by the spread: now 0 means "average" and ±1 means "one typical step away". // vars: z=[-0.99, 1.65, -0.11, -0.55]
5: Every scaled column now has mean 0 and standard deviation 1, whatever its original units. // out: [-0.99, 1.65, -0.11, -0.55]
::end

The same with scikit-learn, plus min-max scaling to 0–1:

```python
from sklearn.preprocessing import StandardScaler, MinMaxScaler

X = df[["salary", "age"]]
print(StandardScaler().fit_transform(X).round(2))
print(MinMaxScaler().fit_transform(X).round(2))
```

Output:

```text
[[-0.99 -1.01]
 [ 1.65  1.63]
 [-0.11 -0.08]
 [-0.55 -0.54]]
[[0.   0.  ]
 [1.   1.  ]
 [0.33 0.35]
 [0.17 0.18]]
```

| Scaler | Result | Use when |
|---|---|---|
| `StandardScaler` | mean 0, std 1 | Most models; roughly bell-shaped data |
| `MinMaxScaler` | between 0 and 1 | Neural networks, image pixels, bounded features |
| `RobustScaler` | uses median and IQR | Data with outliers |

## See it as a chart: why scaling matters

Salary is in lakhs and age is in tens, so on a raw scale age looks like a flat line next to salary. After standard scaling both are centred on 0 with similar spread, and distance-based models (k-NN, k-means, SVM) treat them fairly.

```python
import matplotlib.pyplot as plt
import numpy as np
import pandas as pd
from sklearn.preprocessing import StandardScaler

rng = np.random.default_rng(3)
df = pd.DataFrame({"salary": rng.normal(900_000, 250_000, 200), "age": rng.normal(31, 6, 200)})
scaled = pd.DataFrame(StandardScaler().fit_transform(df), columns=df.columns)

fig, (left, right) = plt.subplots(1, 2, figsize=(9, 3.3))
left.boxplot([df["salary"], df["age"]], tick_labels=["salary", "age"])
left.set_title("Raw: salary dwarfs age")
right.boxplot([scaled["salary"], scaled["age"]], tick_labels=["salary", "age"])
right.axhline(0, color="#0a7d4f", lw=1, ls="--")
right.set_title("Standard scaled: same footing")
fig.tight_layout()
plt.show()
```

::figure eda-scaling-before-after.svg | Two box plots: on the raw scale age is squashed flat next to salary; after standard scaling both boxes are centred on zero with similar size

Scaling changes the units, not the shape of the data. Tree models don't need it; distance and gradient-based models do.

## Summary

- One-hot for categories without order; ordinal codes (with your own order) for ordered ones.
- Target encoding for very many categories, fitted on training data only.
- Standardise or min-max scale numbers for distance- and gradient-based models; trees don't need it.
- Fit every encoder and scaler on training data only, to avoid leakage.
