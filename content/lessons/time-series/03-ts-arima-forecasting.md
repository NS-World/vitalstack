---
title: "ARIMA Forecasting in Python"
slug: ts-arima-forecasting
subject: time-series
lesson: 3
difficulty: intermediate
excerpt: Understand AR, I and MA, fit ARIMA and seasonal SARIMA models with statsmodels, forecast with confidence intervals, and evaluate on a time-based test set, plus a from-scratch AR model you can run.
---

ARIMA is the classic forecasting model, used for demand planning, finance and operations. The name is three ideas glued together: **AutoRegressive**, **Integrated**, **Moving Average**.

::scene ARIMA, the three-part machine
🔙 | AR (p) | "Next month looks like a mix of the last p months." | dev
➖ | I (d) | "First difference the series d times so it stops wandering." | chip
🌊 | MA (q) | "Correct using the last q forecast errors." | byte
📦 | Seasonal | SARIMA adds the same three parts at lag 12 for yearly patterns. | bugsy
::end

## AR from scratch: predict from lags

An AR(1) model says `next = c + φ × current + noise`. You can fit it with ordinary linear regression on lagged values:

```python
import numpy as np
import pandas as pd
from sklearn.linear_model import LinearRegression

rng = np.random.default_rng(5)
x = [50.0]
for _ in range(199):
    x.append(10 + 0.8 * x[-1] + rng.normal(0, 2))        # true model: c=10, phi=0.8
s = pd.Series(x)

frame = pd.DataFrame({"y": s, "lag1": s.shift(1)}).dropna()
ar = LinearRegression().fit(frame[["lag1"]], frame["y"])
print("phi ≈", round(ar.coef_[0], 2), "| c ≈", round(ar.intercept_, 1))
```

Output:

```text
phi ≈ 0.82 | c ≈ 8.7
```

The regression lands close to the true φ = 0.8 and c = 10 (noise makes it slightly off): that's all an AR model is.

::walk python | Forecasting several steps with AR(1)
c, phi = 10.0, 0.8
last = 60.0
forecasts = []
for step in range(3):
    last = c + phi * last
    forecasts.append(round(last, 1))
print(forecasts)
::notes
1: The fitted AR(1) parameters. // vars: c=10.0, phi=0.8
2: The latest observed value. // vars: last=60.0
3: Collect three future steps.
4: Forecast three steps ahead.
5: Each forecast feeds the next: 10 + 0.8 × 60 = 58. // deep: Multi-step forecasts drift towards the long-run mean c ÷ (1 − φ) = 50, and their uncertainty grows with every step. That's why forecast intervals widen. // vars: last=58.0
6: Save it, then repeat: 56.4, then 55.1. // vars: forecasts=[58.0, 56.4, 55.1]
7: Heading back towards 50, the series' average. // out: [58.0, 56.4, 55.1]
::end

## ARIMA with statsmodels

```python
from statsmodels.tsa.arima.model import ARIMA
from statsmodels.tsa.statespace.sarimax import SARIMAX

train, test = sales[:-6], sales[-6:]               # keep the last 6 months for testing
model = SARIMAX(train, order=(1, 1, 1), seasonal_order=(1, 1, 0, 12)).fit(disp=False)
fc = model.get_forecast(steps=6)
print(fc.predicted_mean.round(1))
print(fc.conf_int().round(1))                      # 95% interval for each month
```

- `order=(p, d, q)`: AR lags, differences, MA lags.
- `seasonal_order=(P, D, Q, s)`: the same at the seasonal lag `s` (12 for monthly data).
- Choose orders with ACF/PACF plots, or compare AIC values of candidates (lower is better); `pmdarima.auto_arima` searches automatically.

## Evaluate like the future

```python
actual = np.array([210, 198, 225, 240, 232, 260])
forecast = np.array([205, 204, 218, 236, 241, 251])
mae = np.mean(np.abs(actual - forecast))
mape = np.mean(np.abs(actual - forecast) / actual) * 100
print(f"MAE {mae:.1f} units | MAPE {mape:.1f}%")
```

Output:

```text
MAE 6.7 units | MAPE 2.9%
```

Use a **time-based split** (or rolling-origin evaluation, refitting as each month arrives) and always compare against the seasonal-naive baseline from lesson 1.

## Beyond ARIMA

| Method | When it shines |
|---|---|
| Exponential smoothing (Holt-Winters) | Simple trend + seasonality, robust |
| Prophet | Business data with holidays (Diwali, Eid) and missing days |
| Gradient boosting on lag features | Many related series, extra drivers (price, promotions, weather) |
| LSTM / deep learning | Huge datasets with complex patterns (Deep Learning module) |

::tip Forecasts are ranges, not single numbers. Show the interval: "between 230 and 255 units, most likely 241" helps a planner far more than "241".

## See it as a chart: a forecast against what really happened

Fit the AR(1) model on the first 180 points, then forecast the last 20 one step at a time from the real previous value. Plotting forecast against actual is the honest test of any forecasting model.

```python
import matplotlib.pyplot as plt
import numpy as np
import pandas as pd
from sklearn.linear_model import LinearRegression

rng = np.random.default_rng(5)
x = [50.0]
for _ in range(199):
    x.append(10 + 0.8 * x[-1] + rng.normal(0, 2))
s = pd.Series(x)
frame = pd.DataFrame({"y": s, "lag1": s.shift(1)}).dropna()
train, test = frame.iloc[:179], frame.iloc[179:]
ar = LinearRegression().fit(train[["lag1"]], train["y"])
forecast = ar.predict(test[["lag1"]])
mae = np.mean(np.abs(test["y"] - forecast))

fig, ax = plt.subplots(figsize=(8.5, 3.6))
ax.plot(s.index[140:], s.iloc[140:], color="#1b1f27", lw=1.4, label="actual")
ax.plot(test.index, forecast, color="#0a7d4f", lw=2.2, marker="o", ms=4, label=f"AR(1) one-step forecast (MAE {mae:.2f})")
ax.axvspan(test.index[0], test.index[-1], color="#e3f3eb", label="test period")
ax.set_xlabel("time step")
ax.legend(frameon=False, loc="upper center", bbox_to_anchor=(0.5, -0.18), ncol=3)
fig.tight_layout()
plt.show()
```

::figure ts-ar-forecast.svg | A black actual series with a shaded test period at the end, where green forecast dots follow the actual line closely

The forecast follows the shape but never catches the surprises: those are the random shocks no model can predict. Always compare against a simple baseline (like "same as last value") before trusting a model.

## Summary

- AR uses past values, I differences for stationarity, MA uses past errors; SARIMA adds seasonal terms.
- An AR model is regression on lags; forecasts drift to the mean and widen in uncertainty.
- Fit with statsmodels' `ARIMA`/`SARIMAX`; choose orders by ACF/PACF or AIC.
- Evaluate on the future (MAE, MAPE) against simple baselines.

🎉 You've finished **Time Series Forecasting**.
