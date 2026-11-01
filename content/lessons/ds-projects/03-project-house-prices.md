---
title: "Project 3: House Price Prediction"
slug: project-house-prices
subject: ds-projects
lesson: 3
difficulty: intermediate
excerpt: Predict flat prices from size, location and amenities: log-transformed targets, one-hot locations, linear vs random forest vs boosting, MAE in lakhs, and error analysis.
---

What should a 2BHK in Pune's Baner cost? Property sites, banks and buyers all want that number. House-price prediction is the classic **regression** project: predict a number, measure how far off you are, and explain what drives the price.

::scene The pricing desk
🏢 | The listing | 980 sq ft, 2BHK, Baner, 3 years old, with parking. Price? | dev
📍 | Location | Location dominates: the same flat costs very different amounts in different localities. | chip
📐 | Model | The model learns price per square foot by locality, then adjusts for age and amenities. | byte
🎯 | Accuracy | Off by a few lakh on average: good enough to flag overpriced listings. | bugsy
::end

## Data

```python
import numpy as np
import pandas as pd

rng = np.random.default_rng(11)
n = 2500
locality = rng.choice(["Baner", "Hinjewadi", "Kothrud", "Wakad", "Hadapsar"], n)
rate = {"Baner": 11500, "Hinjewadi": 7800, "Kothrud": 13000, "Wakad": 8500, "Hadapsar": 7200}
sqft = rng.normal(1000, 280, n).clip(400, 2200).round()
age = rng.integers(0, 25, n)
parking = rng.random(n) < 0.6
price = (np.array([rate[l] for l in locality]) * sqft * (1 - 0.008 * age) * np.where(parking, 1.04, 1.0)
         * rng.lognormal(0, 0.08, n)) / 1e5                       # price in lakhs
df = pd.DataFrame({"locality": locality, "sqft": sqft, "age": age, "parking": parking.astype(int), "price_lakh": price.round(1)})
print(df.groupby("locality")["price_lakh"].median().round(1).sort_values().to_dict())
```

Output:

```text
{'Hadapsar': 66.8, 'Hinjewadi': 70.1, 'Wakad': 78.8, 'Baner': 107.6, 'Kothrud': 117.4}
```

## Model comparison

```python
from sklearn.compose import ColumnTransformer
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import OneHotEncoder
from sklearn.linear_model import LinearRegression
from sklearn.ensemble import RandomForestRegressor, HistGradientBoostingRegressor
from sklearn.compose import TransformedTargetRegressor
from sklearn.model_selection import train_test_split
from sklearn.metrics import mean_absolute_error

X, y = df.drop(columns="price_lakh"), df["price_lakh"]
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=0)
prep = ColumnTransformer([("loc", OneHotEncoder(handle_unknown="ignore"), ["locality"])], remainder="passthrough")

candidates = {
    "linear": LinearRegression(),
    "linear on log(price)": TransformedTargetRegressor(LinearRegression(), func=np.log, inverse_func=np.exp),
    "random forest": RandomForestRegressor(n_estimators=300, random_state=0),
    "boosting": HistGradientBoostingRegressor(random_state=0),
}
for name, model in candidates.items():
    pipe = Pipeline([("prep", prep), ("m", model)]).fit(X_train, y_train)
    print(f"{name:>21}: MAE {mean_absolute_error(y_test, pipe.predict(X_test)):.2f} lakh")
```

Output:

```text
               linear: MAE 7.11 lakh
 linear on log(price): MAE 7.38 lakh
        random forest: MAE 6.64 lakh
             boosting: MAE 6.20 lakh
```

Boosting captures the "locality × size" interaction (price per sq ft differs by area) best.

::walk python | Why prices are often modelled in logs
prices = [45, 60, 90, 250]
logs = [round(__import__("math").log(p), 2) for p in prices]
ratio_small = round(60 / 45, 2)
ratio_big = round(250 / 90, 2)
print(logs, ratio_small, ratio_big)
::notes
1: Flat prices in lakhs: a few very expensive flats stretch the scale. // vars: prices=[45, 60, 90, 250]
2: The log turns multiplicative differences into additive ones and squashes the long tail. // deep: Errors on expensive flats stop dominating training. It didn't help here because our synthetic noise is small, but on real listing data (with crores-priced outliers) it usually does. Always test both. // vars: logs=[3.81, 4.09, 4.5, 5.52]
3: 60 vs 45 is +33%. // vars: ratio_small=1.33
4: 250 vs 90 is +178%. In logs both are simple differences. // vars: ratio_big=2.78
5: Price effects are usually percentages ("parking adds 4%"), which logs model naturally. // out: [3.81, 4.09, 4.5, 5.52] 1.33 2.78
::end

## Error analysis

```python
best = Pipeline([("prep", prep), ("m", HistGradientBoostingRegressor(random_state=0))]).fit(X_train, y_train)
errors = X_test.assign(actual=y_test, predicted=best.predict(X_test).round(1))
errors["abs_err"] = (errors["actual"] - errors["predicted"]).abs()
print(errors.groupby("locality")["abs_err"].mean().round(2).sort_values().to_dict())
```

Output:

```text
{'Hinjewadi': 4.28, 'Hadapsar': 4.67, 'Wakad': 5.83, 'Baner': 7.16, 'Kothrud': 9.15}
```

Errors are larger in the costliest localities in absolute lakhs: report percentage error (MAPE) too, and consider separate models or more features (floor, view, society amenities) for premium areas.

## Portfolio checklist

- [ ] Real data next: a public housing dataset (Kaggle has Indian city listings) with honest cleaning notes
- [ ] Baseline (median price per locality) vs models, in lakhs
- [ ] Error analysis by segment
- [ ] A tiny Streamlit "price estimator" app as a demo (see the Gen AI project for Streamlit basics)

## Summary

- Regression project: clean → encode locations → compare models → MAE in business units.
- Boosting handles interactions like locality × size well; test log targets.
- Error analysis by segment shows where the model needs more features.
