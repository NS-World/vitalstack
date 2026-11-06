---
title: "Signature Project 2: The 30-Minute Promise (Food Delivery ETA)"
slug: signature-delivery-eta
subject: ds-signature
lesson: 2
difficulty: advanced
excerpt: Predict how long a food order will take across eight Indian cities, clean impossible delivery times, explain the effect of rain and peak hours in minutes, and choose an ETA promise that keeps customers happy.
---

Open any food app and you see a promise: "Arriving in 32 min". Promise too little time and the order is late, the rating drops and support gets angry calls. Promise too much and the customer orders from a competitor. In this project you build the ETA engine behind that promise on the VitalStack **food delivery** dataset (2,500 orders, 8 cities) from the [Datasets Hub](/datasets/).

::scene Behind "Arriving in 32 min"
🛵 | The order | A biryani, 8 km away, at 8 pm, in the rain. How long will it really take? | dev
🧹 | The mess | A few orders say 250 to 350 minutes. Real, or a cancelled order that was never closed? | bugsy
📏 | The model | Each extra kilometre, rain and peak hour adds a number of minutes you can explain. | chip
🎯 | The promise | Add a smart buffer so 9 out of 10 orders arrive on time. | byte
::end

## Load and look

```python
import numpy as np
import pandas as pd

DATA = "https://vitalstack.co.in/wp-content/themes/vitalstack/data/datasets/"
df = pd.read_csv(DATA + "food-delivery.csv")
print(df.shape)
print(df["delivery_time_min"].describe().round(1)[["mean", "50%", "max"]].to_dict())
```

Output:

```text
(2500, 10)
{'mean': 46.1, '50%': 46.0, 'max': 350.0}
```

A maximum of **350 minutes** (almost six hours) for a food order is a red flag. Before any model, we deal with outliers.

## Clean the impossible orders

```python
q1, q3 = df["delivery_time_min"].quantile([0.25, 0.75])
fence = q3 + 1.5 * (q3 - q1)
print("IQR fence:", fence, "minutes; orders above it:", int((df["delivery_time_min"] > fence).sum()))
print(df.nlargest(5, "delivery_time_min")[["distance_km", "prep_time_min", "weather", "delivery_time_min"]].to_string(index=False))
```

Output:

```text
IQR fence: 87.5 minutes; orders above it: 10
 distance_km  prep_time_min weather  delivery_time_min
        8.72             27   Rainy                350
        3.83             11   Clear                311
        4.11             33  Cloudy                288
        8.08             34   Clear                268
        5.47             10  Cloudy                253
```

A 3.8 km order on a clear day cannot take 311 minutes. These are almost certainly orders that were cancelled or never closed in the system. The statistical fence (87.5 minutes) also flags two slow but believable orders, so we use a **business rule** instead: drop anything over 2 hours, and report how many.

```python
clean = df[df["delivery_time_min"] <= 120].copy()
print("removed:", len(df) - len(clean), "orders")
print("mean before:", round(df["delivery_time_min"].mean(), 1), "after:", round(clean["delivery_time_min"].mean(), 1))
```

Output:

```text
removed: 8 orders
mean before: 46.1 after: 45.4
```

Eight bad rows out of 2,500 moved the average by almost a minute. On a squared-error model, their effect would be much bigger.

## What makes an order late?

We call an order **late** if it takes more than 45 minutes.

```python
clean["late"] = (clean["delivery_time_min"] > 45).astype(int)
print("late overall:", round(clean["late"].mean() * 100, 1), "%")
for col in ["weather", "peak_hour"]:
    print(col, clean.groupby(col)["late"].mean().mul(100).round(1).to_dict())
bands = pd.cut(clean["delivery_time_min"], [0, 30, 45, 60, 120], labels=["0-30", "31-45", "46-60", "61-120"])
print("rating by minutes:", clean.groupby(bands, observed=True)["rating"].mean().round(2).to_dict())
```

Output:

```text
late overall: 49.9 %
weather {'Clear': 46.4, 'Cloudy': 42.1, 'Rainy': 68.4}
peak_hour {'No': 42.6, 'Yes': 63.7}
rating by minutes: {'0-30': 4.83, '31-45': 4.48, '46-60': 3.2, '61-120': 1.88}
```

The rating line is the business case in one row: orders under 30 minutes average **4.83 stars**, and orders over an hour average **1.88**. Rain pushes the late rate from about 46% to **68%**, and peak hours to **64%**.

## Build an explainable ETA model

A linear regression is perfect here because every coefficient is "minutes per something", which operations teams understand.

```python
from sklearn.linear_model import LinearRegression
from sklearn.model_selection import train_test_split
from sklearn.metrics import mean_absolute_error

clean["peak"] = (clean["peak_hour"] == "Yes").astype(int)
clean["rain"] = (clean["weather"] == "Rainy").astype(int)
X = clean[["distance_km", "prep_time_min", "peak", "rain"]]
y = clean["delivery_time_min"]
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.25, random_state=0)

eta = LinearRegression().fit(X_train, y_train)
print({k: round(float(v), 2) for k, v in zip(X.columns, eta.coef_)})
pred = eta.predict(X_test)
print("model MAE:", round(mean_absolute_error(y_test, pred), 1), "min")
print("guess-the-average MAE:", round(mean_absolute_error(y_test, np.full(len(y_test), y_train.mean())), 1), "min")
```

Output:

```text
{'distance_km': 3.19, 'prep_time_min': 1.02, 'peak': 7.71, 'rain': 9.91}
model MAE: 3.1 min
guess-the-average MAE: 12.0 min
```

Read it like an operations manager: **every kilometre adds 3.2 minutes, rain adds about 10, peak hour adds about 8**, and each minute of kitchen time adds one minute to delivery. The model is wrong by about 3 minutes on average, four times better than guessing the average.

## Turn the prediction into a promise

If we show the raw prediction, half the orders arrive a bit late, because the prediction is a middle estimate. So we add a **buffer**, chosen from the model's past errors.

::walk python | How a buffer turns a guess into a promise
predicted = 58
buffer = 5
promise = predicted + buffer
print("Arriving in", promise, "min")
::notes
1: The model predicts 58 minutes for this order. // vars: predicted=58
2: Past errors show that adding about 5 minutes covers 9 out of 10 orders. // vars: buffer=5
3: The promise shown in the app is the prediction plus the buffer. // deep: This buffer is the 90th percentile of the model's errors on training data. A bigger buffer means fewer late orders but a less tempting ETA. Some apps use different buffers for rain or new cities. // vars: promise=63
4: The customer sees one honest number. // out: Arriving in 63 min
::end

```python
errors = y_train - eta.predict(X_train)
buffer = np.percentile(errors, 90)
print("90th percentile buffer:", round(buffer, 1), "min")
for b in [0, 5, 8, 10]:
    on_time = (y_test <= pred + b).mean() * 100
    print(f"buffer {b:>2} min: {on_time:.1f}% on time")
```

Output:

```text
90th percentile buffer: 5.1 min
buffer  0 min: 49.9% on time
buffer  5 min: 88.1% on time
buffer  8 min: 96.3% on time
buffer 10 min: 98.6% on time
```

Without a buffer, the app breaks its promise half the time. A **5-minute** buffer keeps 88% of promises, and 8 minutes keeps 96%. Which to choose is a product decision: a longer promise costs some orders, and a broken promise costs ratings.

## Try three real orders

```python
orders = pd.DataFrame({"distance_km": [3, 8, 8], "prep_time_min": [15, 25, 25],
                       "peak": [0, 1, 1], "rain": [0, 0, 1]},
                      index=["3 km, quiet, clear", "8 km, peak, clear", "8 km, peak, rain"])
orders["promise_min"] = (eta.predict(orders) + buffer).round()
print(orders["promise_min"].to_dict())
```

Output:

```text
{'3 km, quiet, clear': 29.0, '8 km, peak, clear': 63.0, '8 km, peak, rain': 73.0}
```

Same restaurant, same distance: rain adds 10 minutes to the promise. That is exactly the kind of number a city manager uses to plan extra riders on monsoon evenings.

::tip The dataset's ratings drop sharply after 45 minutes. A strong extra step is to predict the **chance of being late** (a classifier) and send a ₹20 coupon automatically when it is above 70%, then estimate how many 1-star ratings that saves.

## Make it yours

- **City effects:** add one-hot city columns. Is Hyderabad slower even after distance and rain?
- **Quantile model:** train `GradientBoostingRegressor(loss="quantile", alpha=0.9)` to predict the 90% promise directly, and compare it with the buffer approach.
- **Rider planning:** for rainy peak hours, estimate how many extra riders keep the late rate under 30%.

## Portfolio checklist

- [ ] Outliers found, explained and removed with a clear business rule
- [ ] Late rate by weather and peak hour, and ratings by delivery time
- [ ] Linear ETA model with coefficients explained in minutes
- [ ] MAE compared with a naive baseline
- [ ] Buffer chosen from errors, with an on-time table
- [ ] One recommendation for operations, backed by a number
