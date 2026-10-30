---
title: Your First Model: Linear Regression
slug: ml-linear-regression
subject: machine-learning
lesson: 2
difficulty: beginner
excerpt: Train a linear regression in scikit-learn to predict a number, read what it learned, and make predictions.
---

**Linear regression** is the "hello world" of machine learning. It fits the best straight line through your data and uses that line to predict numbers. Simple, fast, and still used every day in business.

## The idea

Remember the scatter plot of study hours and test scores? The dots went up roughly in a line. Linear regression finds the line:

> score = **slope** × hours + **intercept**

The **slope** says how much the score rises per extra hour. The **intercept** is the predicted score at zero hours. "Training" means finding the slope and intercept that make the line as close as possible to all the dots.

## Train it

```python
import numpy as np
from sklearn.linear_model import LinearRegression

hours = np.array([1, 2, 3, 4, 5, 6, 7, 8]).reshape(-1, 1)
score = np.array([42, 48, 51, 58, 63, 66, 74, 79])

model = LinearRegression()
model.fit(hours, score)

print("slope:", round(model.coef_[0], 2))
print("intercept:", round(model.intercept_, 2))
```

Output:

```text
slope: 5.23
intercept: 36.61
```

The model learned: **each extra hour of study adds about 5.2 marks**, starting from about 37.

`reshape(-1, 1)` turns the hours into one column. scikit-learn always expects `X` as a table (rows × features), even with a single feature.

## Predict

```python
new_hours = np.array([[2.5], [9], [12]])
print(model.predict(new_hours).round(1))
```

Output:

```text
[49.7 83.6 99.3]
```

For 2.5 hours it predicts about 50 marks, for 9 hours about 84.

::hint Careful | 12 hours gives 99.3, close to impossible territory. Our data only went up to 8 hours, so predictions far outside it (**extrapolation**) aren't reliable. A line keeps going forever; real life doesn't.

## How good is the line? R²

`score()` returns **R²** (R-squared): 1.0 means the line explains the data perfectly, 0 means it's no better than guessing the average.

```python
print(round(model.score(hours, score), 3))
```

Output:

```text
0.993
```

0.993 is excellent, but we measured it on the same data the model learned from. That's like grading a student with the questions they studied. The next lesson fixes this with a proper train/test split.

## Several features

Real predictions use many features at once. The code doesn't change, only `X` gets more columns:

```python
import pandas as pd
from sklearn.linear_model import LinearRegression

homes = pd.DataFrame({
    "area_sqft": [850, 1200, 1500, 1800, 2100, 950, 1350, 2400],
    "rooms": [2, 2, 3, 3, 4, 2, 3, 4],
    "age_years": [15, 8, 5, 10, 2, 25, 12, 4],
    "price_lakh": [42, 60, 78, 85, 112, 38, 66, 125],
})
X = homes[["area_sqft", "rooms", "age_years"]]
y = homes["price_lakh"]

model = LinearRegression().fit(X, y)
for name, coef in zip(X.columns, model.coef_):
    print(f"{name}: {coef:.2f}")

new_home = pd.DataFrame({"area_sqft": [1600], "rooms": [3], "age_years": [6]})
print("Predicted price:", round(model.predict(new_home)[0], 1), "lakh")
```

Output:

```text
area_sqft: 0.04
rooms: 4.55
age_years: -0.68
Predicted price: 82.6 lakh
```

Each coefficient is that feature's effect **when the others stay the same**: about 4 lakh per extra 100 sq ft, about 4.5 lakh per extra room, and -0.68 lakh for every year of age.

::tip Pass new data as a DataFrame with the same column names you trained on. scikit-learn checks them and warns you if they don't match, which catches many bugs.

## Summary

- Linear regression fits `y = slope × x + intercept` (one slope per feature).
- `LinearRegression().fit(X, y)` trains; `.coef_` and `.intercept_` show what it learned.
- `.predict(new_X)` makes predictions; `.score()` gives R².
- Don't trust predictions far outside your training data.
