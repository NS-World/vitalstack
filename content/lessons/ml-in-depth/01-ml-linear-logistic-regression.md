---
title: "Linear and Logistic Regression, Step by Step"
slug: ml-linear-logistic-regression
subject: ml-in-depth
lesson: 1
difficulty: intermediate
excerpt: Predict numbers with linear regression and yes/no outcomes with logistic regression. Understand the line, the sigmoid, coefficients and probabilities, with scikit-learn.
---

Two of the oldest models in machine learning are still the first ones professionals try, because they are fast, explainable and surprisingly strong. **Linear regression** predicts a number (a price, a delivery time). **Logistic regression** predicts a probability of yes/no (will this customer leave?).

::scene Drawing the best line
📈 | Scatter | Flat sizes on one axis, rents on the other. The dots rise from left to right. | dev
📏 | The line | Linear regression finds the straight line with the smallest total squared error. | chip
🎚️ | Squash | For yes/no questions, the sigmoid squashes any number into a probability between 0 and 1. | byte
✅ | Decide | Above 50%? Predict "yes". The coefficients tell you why. | bugsy
::end

## Linear regression: rent from flat size

```python
import numpy as np
from sklearn.linear_model import LinearRegression

size = np.array([[450], [600], [750], [900], [1100], [1300]])     # square feet
rent = np.array([14000, 18000, 21500, 26000, 31000, 36500])      # rupees per month

model = LinearRegression().fit(size, rent)
print("rent per extra sq ft: Rs", round(model.coef_[0], 1))
print("base (intercept): Rs", round(model.intercept_))
print("predicted rent for 1000 sq ft: Rs", round(model.predict([[1000]])[0]))
print("R² on training data:", round(model.score(size, rent), 3))
```

Output:

```text
rent per extra sq ft: Rs 26.4
base (intercept): Rs 2018
predicted rent for 1000 sq ft: Rs 28468
R² on training data: 0.999
```

The model is just `rent ≈ intercept + coef × size`. The coefficient is its explanation: each extra square foot adds about ₹26 a month.

## How the line is chosen: least squares

::walk python | The error the line minimises
actual = [14000, 18000, 21500]
predicted = [14150, 18050, 21950]
errors = [a - p for a, p in zip(actual, predicted)]
sse = sum(e ** 2 for e in errors)
print(errors, sse)
::notes
1: Three real rents. // vars: actual=[14000, 18000, 21500]
2: What one candidate line predicts for them. // vars: predicted=[14150, 18050, 21950]
3: The errors (residuals): actual minus predicted. // vars: errors=[-150, -50, -450]
4: Square and add them. Squaring makes all errors positive and punishes big misses more. // deep: Linear regression picks the intercept and slope that make this sum as small as possible. There is an exact formula for it (the normal equation), so no trial and error is needed. // vars: sse=227500
5: Every possible line has a total like this; the regression line has the smallest one. // out: [-150, -50, -450] 227500
::end

## Logistic regression: will the customer churn?

```python
from sklearn.linear_model import LogisticRegression

# months as a customer, support complaints last month
X = np.array([[2, 3], [3, 4], [1, 2], [24, 0], [36, 1], [18, 0], [5, 3], [30, 0], [4, 2], [20, 1]])
churned = np.array([1, 1, 1, 0, 0, 0, 1, 0, 1, 0])

clf = LogisticRegression().fit(X, churned)
for customer in ([3, 3], [28, 0]):
    p = clf.predict_proba([customer])[0, 1]
    print(customer, "-> churn probability", round(p, 2))
print("coefficients (months, complaints):", clf.coef_.round(2).tolist()[0])
```

Output:

```text
[3, 3] -> churn probability 0.99
[28, 0] -> churn probability 0.0
coefficients (months, complaints): [-0.56, 0.1]
```

A new customer with complaints is very likely to leave; a loyal, happy one isn't. The signs of the coefficients say *why*: more months → less churn, more complaints → more churn.

## The sigmoid

Logistic regression computes a score like linear regression, then squashes it with the **sigmoid** function into 0–1:

```python
import math

def sigmoid(z):
    return 1 / (1 + math.exp(-z))

for z in (-4, -1, 0, 1, 4):
    print(z, round(sigmoid(z), 3))
```

Output:

```text
-4 0.018
-1 0.269
0 0.5
1 0.731
4 0.982
```

A score of 0 means 50/50; big positive scores mean "almost certainly yes".

::tip Always scale features before logistic regression when they have very different ranges (lesson on scaling), and check the coefficients make business sense. A model that says "more complaints → less churn" has a data problem.

## See it as a chart: a line for numbers, an S-curve for yes/no

Linear regression fits a straight line to rent; logistic regression squeezes any score through the sigmoid, so its output is always a probability between 0 and 1.

```python
import matplotlib.pyplot as plt
import numpy as np
from sklearn.linear_model import LinearRegression

size = np.array([[450], [600], [750], [900], [1100], [1300]])
rent = np.array([14000, 18000, 21500, 26000, 31000, 36500])
model = LinearRegression().fit(size, rent)
z = np.linspace(-8, 8, 200)

fig, (left, right) = plt.subplots(1, 2, figsize=(10, 3.6))
left.scatter(size, rent, s=60, color="#1b1f27", zorder=3)
grid = np.linspace(400, 1350, 50).reshape(-1, 1)
left.plot(grid, model.predict(grid), color="#0a7d4f", lw=2.5)
left.set_xlabel("flat size (sq ft)")
left.set_ylabel("rent (₹/month)")
left.set_title("Linear regression")
right.plot(z, 1 / (1 + np.exp(-z)), color="#2563eb", lw=2.5)
right.axhline(0.5, color="#c2255c", ls="--", lw=1)
right.text(-7.5, 0.55, "threshold 0.5", color="#c2255c")
right.set_xlabel("score z = w·x + b")
right.set_ylabel("P(churn)")
right.set_title("Logistic regression: the sigmoid")
fig.tight_layout()
plt.show()
```

::figure mlid-line-and-sigmoid.svg | Left: rent rising in a straight line with flat size; right: the S-shaped sigmoid going from 0 to 1 with a dashed threshold at 0.5

The sigmoid is steep in the middle and flat at the ends: a small change in score matters a lot for borderline customers and hardly at all for clear cases.

## Summary

- Linear regression: number = intercept + coefficients × features, fitted by least squares.
- Logistic regression: a linear score passed through the sigmoid → probability.
- Coefficients explain the model; check their signs make sense.
- Both are fast, explainable baselines: always try them first.
