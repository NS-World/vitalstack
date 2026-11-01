---
title: "Project 5: Retail Sales Forecasting"
slug: project-sales-forecasting
subject: ds-projects
lesson: 5
difficulty: intermediate
excerpt: Forecast daily store sales for the next four weeks: calendar and lag features, festival effects, a gradient-boosting forecaster, a backtest against baselines, and a stock-planning recommendation.
---

A store that orders too little runs out of stock and loses sales; too much, and money sits on shelves or food spoils. Forecasting demand is one of the most practical, high-value data science jobs in retail and FMCG.

::scene Stocking up for Diwali
🏪 | The store | A Kirana chain with 40 stores must order stock four weeks ahead. | dev
📅 | Patterns | Weekends are busier, salaries arrive on the 1st, Diwali triples sweets sales. | chip
🤖 | Model | A boosting model learns from calendar features, recent sales and festival flags. | byte
📦 | Plan | The forecast plus a safety buffer becomes the order quantity. Fewer stock-outs, less waste. | bugsy
::end

## Daily sales with real-world patterns

```python
import numpy as np
import pandas as pd

rng = np.random.default_rng(3)
days = pd.date_range("2024-01-01", "2025-12-31", freq="D")
weekday_boost = np.where(days.dayofweek >= 5, 1.35, 1.0)
payday_boost = np.where(days.day <= 3, 1.2, 1.0)
festive = np.where(((days.month == 10) & (days.day >= 20)) | ((days.month == 11) & (days.day <= 5)), 1.8, 1.0)
trend = np.linspace(200, 260, len(days))
sales = pd.Series((trend * weekday_boost * payday_boost * festive * rng.lognormal(0, 0.08, len(days))).round(), index=days)
print(sales.resample("ME").sum().loc["2025-09":"2025-11"].astype(int).to_dict())
```

Output:

```text
{Timestamp('2025-09-30 00:00:00'): 8532, Timestamp('2025-10-31 00:00:00'): 11233, Timestamp('2025-11-30 00:00:00'): 10305}
```

## Features

```python
def make_features(s):
    f = pd.DataFrame({"y": s})
    f["dow"] = s.index.dayofweek
    f["day"] = s.index.day
    f["month"] = s.index.month
    f["festive"] = (((s.index.month == 10) & (s.index.day >= 20)) | ((s.index.month == 11) & (s.index.day <= 5))).astype(int)
    f["lag_28"] = s.shift(28)                      # known 4 weeks ahead
    f["roll_28_mean"] = s.shift(28).rolling(28).mean()
    return f.dropna()

data = make_features(sales)
print(data.columns.tolist(), len(data))
```

Output:

```text
['y', 'dow', 'day', 'month', 'festive', 'lag_28', 'roll_28_mean'] 676
```

::walk python | Why lags must be at least the forecast horizon
horizon = 28
lag = 7
usable = lag >= horizon
safe_lag = max(lag, horizon)
print(usable, safe_lag)
::notes
1: We order stock 28 days ahead, so we forecast 28 days out. // vars: horizon=28
2: A tempting feature: sales 7 days ago. // vars: lag=7
3: But for a day 28 days in the future, "7 days before it" is still in the future: we don't know it yet. Using it would be **leakage**. // deep: This is the most common forecasting bug: features that secretly use future information make backtests look great and real forecasts fail. Either use lags ≥ horizon, or forecast step by step feeding predictions back in. // vars: usable=False
4: So we use lag 28 (or longer) and rolling means that end 28 days back. // vars: safe_lag=28
5: Only information known at ordering time. // out: False 28
::end

## Backtest: train on the past, test on the last 8 weeks

```python
from sklearn.ensemble import HistGradientBoostingRegressor

train, test = data[:"2025-11-05"], data["2025-11-06":]
X_cols = ["dow", "day", "month", "festive", "lag_28", "roll_28_mean"]
model = HistGradientBoostingRegressor(random_state=0).fit(train[X_cols], train["y"])
pred = model.predict(test[X_cols])

def mape(actual, forecast):
    return float(np.mean(np.abs(actual - forecast) / actual) * 100)

seasonal_naive = test["lag_28"]
print(f"model MAPE {mape(test['y'], pred):.1f}%  vs  seasonal-naive MAPE {mape(test['y'], seasonal_naive):.1f}%")
```

Output:

```text
model MAPE 12.2%  vs  seasonal-naive MAPE 33.9%
```

The model beats the naive "same day 4 weeks ago" baseline by a wide margin, mostly because it knows weekdays and paydays.

## From forecast to order

```python
next_4_weeks = pred[-28:].sum()
error_spread = np.std(test["y"] - pred)
safety = 1.65 * error_spread * np.sqrt(28)          # about 95% service level
print(f"forecast {next_4_weeks:,.0f} units + safety stock {safety:,.0f} = order {next_4_weeks + safety:,.0f}")
```

Output:

```text
forecast 7,091 units + safety stock 241 = order 7,331
```

Safety stock covers forecast error so the store rarely runs out. The 1.65 multiplier corresponds to roughly a 95% chance of not stocking out.

## Portfolio checklist

- [ ] Real data: a public retail dataset (for example the Kaggle store sales datasets), with holiday calendars
- [ ] Baselines (naive, seasonal naive) vs model, MAPE per week
- [ ] No leakage: lags ≥ horizon, time-based split
- [ ] Turn the forecast into a business decision (order quantity, staffing)

## Summary

- Retail demand has weekly, payday, festival and trend patterns: encode them as features.
- Use lags at least as long as the forecast horizon to avoid leakage.
- Backtest on the latest period against seasonal-naive.
- Forecast + safety stock = an order the business can use.
