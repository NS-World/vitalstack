---
title: "Feature Engineering: Creating Features Models Can Learn From"
slug: eda-feature-engineering
subject: eda
lesson: 4
difficulty: intermediate
excerpt: Turn raw columns into powerful features: dates, ratios, aggregates, bins and text lengths. See a weak model jump in accuracy just from better features, with scikit-learn.
---

Experienced data scientists say: "better features beat a better algorithm". A model can only learn from what you give it. Hand it a raw timestamp and it sees a big number; hand it "is it a weekend evening?" and it sees the pattern immediately.

::scene The feature factory
🕰️ | Raw | A raw order time: 2026-03-14 19:42. To a model, just a big meaningless number. | byte
🛠️ | Craft | Pull out the hour, the weekday, "is weekend", "is dinner time". | dev
➗ | Combine | Amount ÷ items = price per item. Orders ÷ months = loyalty. | chip
🚀 | Result | Same algorithm, better features: accuracy jumps. | bugsy
::end

## Date and time features

```python
import pandas as pd

orders = pd.DataFrame({"time": pd.to_datetime(["2026-03-14 19:42", "2026-03-16 09:05", "2026-03-15 13:30"]),
                       "amount": [640, 180, 420], "items": [4, 1, 3]})
orders["hour"] = orders["time"].dt.hour
orders["weekday"] = orders["time"].dt.day_name()
orders["is_weekend"] = orders["time"].dt.dayofweek.isin([5, 6]).astype(int)
orders["dinner"] = orders["hour"].between(19, 22).astype(int)
orders["per_item"] = orders["amount"] / orders["items"]
print(orders.drop(columns="time"))
```

Output:

```text
   amount  items  hour   weekday  is_weekend  dinner  per_item
0     640      4    19  Saturday           1       1     160.0
1     180      1     9    Monday           0       0     180.0
2     420      3    13    Sunday           1       0     140.0
```

## Aggregate features: what a customer usually does

```python
history = pd.DataFrame({"customer": ["A", "A", "A", "B", "B"], "amount": [200, 260, 240, 900, 50]})
agg = history.groupby("customer")["amount"].agg(orders="count", avg_amount="mean", max_amount="max").round(1)
print(agg)
```

Output:

```text
          orders  avg_amount  max_amount
customer
A              3       233.3         260
B              2       475.0         900
```

A new ₹900 order is normal for customer B but very unusual for A: features like "this amount ÷ the customer's average" are the backbone of fraud detection.

## Binning

Sometimes a range matters more than the exact number:

```python
ages = pd.Series([19, 23, 31, 45, 52, 67])
print(pd.cut(ages, bins=[0, 25, 40, 60, 120], labels=["18-25", "26-40", "41-60", "60+"]).tolist())
```

Output:

```text
['18-25', '18-25', '26-40', '41-60', '41-60', '60+']
```

## Proof: features beat algorithms

A circle-shaped pattern can't be separated by a straight line, so logistic regression fails. Add one engineered feature, the distance from the centre, and the same model nails it.

::walk python | One new feature changes everything
x, y = 0.3, 0.4
radius = (x ** 2 + y ** 2) ** 0.5
inside = radius < 1
print(round(radius, 2), inside)
::notes
1: A point's two raw coordinates. // vars: x=0.3, y=0.4
2: The engineered feature: distance from the centre, √(0.09 + 0.16). // deep: In the raw (x, y) space the classes form a ring and a disc: no straight line separates them. In "radius" space, a single threshold does. Good features turn hard problems into easy ones. // vars: radius=0.5
3: One simple rule on the new feature classifies the point. // vars: inside=True
4: The model only needs to learn "radius < 1". // out: 0.5 True
::end

```python
import numpy as np
from sklearn.linear_model import LogisticRegression
from sklearn.model_selection import train_test_split

rng = np.random.default_rng(0)
X = rng.uniform(-2, 2, size=(600, 2))
label = (np.hypot(X[:, 0], X[:, 1]) < 1.2).astype(int)

def score(features):
    a, b, ya, yb = train_test_split(features, label, test_size=0.3, random_state=0)
    return LogisticRegression().fit(a, ya).score(b, yb)

raw = score(X)
engineered = score(np.column_stack([X, np.hypot(X[:, 0], X[:, 1])]))
print(f"raw x, y: {raw:.2f}   + radius feature: {engineered:.2f}")
```

Output:

```text
raw x, y: 0.71   + radius feature: 0.98
```

## A feature-engineering checklist

- **Dates:** hour, weekday, month, is_weekend, is_holiday, days since last event.
- **Ratios:** price per item, spend per visit, clicks per view.
- **Aggregates:** a customer's count, average, max, trend.
- **Text:** length, word count, has a URL, contains "refund".
- **Domain knowledge:** ask experts what they look at. Then turn it into a column.

::tip Keep a list of features you tried and how much each helped. Feature engineering is experiments, not guesswork.

## Summary

- Better features often beat a fancier algorithm.
- Create date parts, ratios, aggregates, bins and text features.
- Distance-from-centre turned a failing linear model into a near-perfect one.
- Compute aggregates from training data only to avoid leakage.

🎉 You've finished **EDA and Feature Engineering**.
