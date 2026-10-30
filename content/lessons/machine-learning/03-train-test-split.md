---
title: Train/Test Split and Evaluating Models
slug: ml-train-test-split
subject: machine-learning
lesson: 3
difficulty: intermediate
excerpt: Measure a model honestly on data it has never seen, read MAE and R², and spot overfitting.
---

A model that scores 99% on the data it learned from might score 60% on new data. That gap is called **overfitting**: the model memorised the examples instead of learning the pattern. The cure is simple: hide some data from the model and test it on that.

## train_test_split

```python
import numpy as np
from sklearn.model_selection import train_test_split

X = np.arange(20).reshape(10, 2)
y = np.arange(10)
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.3, random_state=42)
print(len(X_train), len(X_test))
print(y_test)
```

Output:

```text
7 3
[8 1 5]
```

- `test_size=0.3` keeps 30% of rows for testing (20–30% is typical).
- Rows are **shuffled** first, so the test set isn't just the last rows.
- `random_state=42` makes the shuffle repeatable. Any number works; 42 is a programmer joke.

## A full evaluation

Let's build a realistic dataset of 200 homes and evaluate properly:

```python
import numpy as np
import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_absolute_error, r2_score

rng = np.random.default_rng(0)
n = 200
area = rng.uniform(600, 2500, n)
rooms = np.clip((area / 550 + rng.normal(0, 0.6, n)).round(), 1, 6)
age = rng.uniform(0, 30, n)
price = 25 + area * 0.045 + rooms * 2 - age * 0.6 + rng.normal(0, 6, n)
X = pd.DataFrame({"area_sqft": area, "rooms": rooms, "age_years": age})
y = price

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
model = LinearRegression().fit(X_train, y_train)
pred = model.predict(X_test)

print("MAE:", round(mean_absolute_error(y_test, pred), 2), "lakh")
print("R² test:", round(r2_score(y_test, pred), 3))
print("R² train:", round(model.score(X_train, y_train), 3))
```

Output:

```text
MAE: 5.65 lakh
R² test: 0.947
R² train: 0.957
```

- **MAE** (mean absolute error): on average, predictions are off by about 5.7 lakh. It's in the same unit as the price, so it's the easiest metric to explain to anyone.
- **R²** on the test set is close to the training R². The model generalises well.

## Spotting overfitting

Compare training and test scores:

| Train score | Test score | Diagnosis |
|---|---|---|
| high | high (close) | 👍 good fit |
| high | much lower | ⚠️ **overfitting**: memorised the training data |
| low | low | ⚠️ **underfitting**: too simple, or the features don't carry the answer |

::tip The usual fixes for overfitting: more data, fewer or better features, or a simpler model. For underfitting: better features or a more powerful model.

## Never look at the test set while building

If you tune your model over and over until the test score looks good, the test set has quietly become training data. Professionals split three ways: **train** to learn, **validation** to tune, **test** once at the very end. A popular shortcut, **cross-validation**, rotates the validation slice:

```python
from sklearn.model_selection import cross_val_score

scores = cross_val_score(LinearRegression(), X, y, cv=5, scoring="r2")
print(scores.round(3), round(scores.mean(), 3))
```

Output:

```text
[0.943 0.952 0.97  0.946 0.938] 0.95
```

5-fold cross-validation trains 5 times, each time testing on a different fifth of the data. Five similar scores mean the result is stable, not a lucky split.

::hint Quick check | Train R² = 0.99, test R² = 0.55. What's happening? Overfitting: the model memorised the training rows. Try a simpler model or more data.

## Summary

- Always evaluate on data the model never saw: `train_test_split`.
- MAE is the average error in real units; R² is how much of the variation is explained.
- A big gap between train and test scores means overfitting.
- Cross-validation gives a more trustworthy score than a single split.
