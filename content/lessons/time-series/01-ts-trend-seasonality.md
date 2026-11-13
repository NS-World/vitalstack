---
title: "Time Series: Trend, Seasonality and Moving Averages"
slug: ts-trend-seasonality
subject: time-series
lesson: 1
difficulty: intermediate
excerpt: Break any time series into trend, seasonality and noise, smooth it with moving averages, and make your first simple forecasts with pandas.
---

Sales per day, website visitors per hour, electricity use, stock prices, monsoon rainfall: these are **time series**, measurements taken in order over time. Order is what makes them special: yesterday tells you a lot about today.

::scene Reading the heartbeat of a business
📈 | Trend | Over three years, a chai brand's sales slowly climb. That's the trend. | dev
🎆 | Seasonality | Every October–November, sales jump: Diwali gifting. It repeats every year. | chip
🌧️ | Noise | A random rainy Tuesday dips sales. That's noise. | byte
🔮 | Forecast | Trend + season gives a forecast for next Diwali, with a margin for noise. | bugsy
::end

## The three parts of a time series

**Series = trend + seasonality + noise** (additive). When seasonal swings grow as the level grows, it's multiplicative: trend × seasonality × noise.

```python
import numpy as np
import pandas as pd

rng = np.random.default_rng(1)
months = pd.period_range("2023-01", periods=36, freq="M")
trend = np.linspace(100, 160, 36)
season = 25 * np.isin(months.month, [10, 11])            # festive boost in Oct-Nov
noise = rng.normal(0, 4, 36)
sales = pd.Series(trend + season + noise, index=months).round(1)
print(sales["2024-08":"2024-12"])
```

Output:

```text
2024-08    131.5
2024-09    134.3
2024-10    159.9
2024-11    167.9
2024-12    143.5
Freq: M, dtype: float64
```

## Moving averages: see the trend

A **moving average** smooths noise by averaging a window of recent values. A 12-month window also cancels yearly seasonality.

::walk python | A 3-point moving average
values = [10, 14, 12, 18, 16]
window = 3
smooth = [round(sum(values[i - window + 1:i + 1]) / window, 1) for i in range(window - 1, len(values))]
print(smooth)
::notes
1: Five daily sales numbers, jumping around. // vars: values=[10, 14, 12, 18, 16]
2: Average each value with the two before it. // vars: window=3
3: (10+14+12)/3, (14+12+18)/3, (12+18+16)/3: the first two days have no full window. // deep: In pandas this is series.rolling(3).mean(). A wider window is smoother but reacts later to real changes: always a trade-off. // vars: smooth=[12.0, 14.7, 15.3]
4: The bumps are smoothed out; the upward trend is clearer. // out: [12.0, 14.7, 15.3]
::end

```python
ma12 = sales.rolling(12).mean()
print("trend (12-month average):", ma12.dropna().iloc[[0, 12, -1]].round(1).tolist())
```

Output:

```text
trend (12-month average): [114.5, 134.2, 154.5]
```

## Measuring seasonality

Compare each month to the trend and average by month of year:

```python
detrended = sales - sales.rolling(12, center=True).mean()
seasonal = detrended.groupby(detrended.index.month).mean().round(1)
print(seasonal.loc[[1, 6, 10, 11]].to_dict())
```

Output:

```text
{1: -9.6, 6: -3.3, 10: 22.3, 11: 24.8}
```

October and November sit more than 20 above the trend: the festive effect, discovered from data. (`statsmodels`' `seasonal_decompose` does this in one call.)

## Simple forecasts (baselines)

Always compare fancy models against simple baselines:

| Baseline | Forecast | Good for |
|---|---|---|
| Naive | next = last value | Random-walk data (stock prices) |
| Seasonal naive | next = same month last year | Strongly seasonal data |
| Moving average | next = average of last k | Flat, noisy data |
| Drift | last value + average change | Steady trends |

```python
last_year_nov = sales["2024-11"]
trend_step = (sales.iloc[-1] - sales.iloc[-13]) / 12
print("seasonal naive for Nov 2025:", last_year_nov, "| with drift:", round(last_year_nov + 12 * trend_step, 1))
print("actual Nov 2025:", sales["2025-11"])
```

Output:

```text
seasonal naive for Nov 2025: 167.9 | with drift: 187.1
actual Nov 2025: 185.9
```

::tip Never shuffle time series when splitting into train and test. Train on the past, test on the future: otherwise the model "sees the future" and scores look unrealistically good.

## See it as a chart: trend plus a festive bump

Plot the 36 months of sales with the 12-month moving average on top. The moving average smooths away the season and shows the trend; the shaded months are October and November, where the festive bump appears every year.

```python
import matplotlib.pyplot as plt
import numpy as np
import pandas as pd

rng = np.random.default_rng(1)
months = pd.period_range("2023-01", periods=36, freq="M")
trend = np.linspace(100, 160, 36)
season = 25 * np.isin(months.month, [10, 11])
sales = pd.Series(trend + season + rng.normal(0, 4, 36), index=months.to_timestamp())

fig, ax = plt.subplots(figsize=(8.5, 3.6))
for start in sales.index[sales.index.month == 10]:
    ax.axvspan(start, start + pd.DateOffset(months=2), color="#f59e0b", alpha=0.15)
ax.plot(sales, color="#0a7d4f", marker="o", ms=3, label="monthly sales")
ax.plot(sales.rolling(12).mean(), color="#c2255c", lw=2.5, label="12-month moving average (trend)")
ax.set_ylabel("units sold")
ax.legend(frameon=False, loc="upper left")
ax.set_title("Shaded: October–November every year")
fig.tight_layout()
plt.show()
```

::figure ts-trend-seasonality.svg | Three years of monthly sales rising steadily with a spike every October and November, and a smooth pink moving-average line showing the upward trend

The three parts are all visible: the **trend** (the pink line climbs), the **seasonality** (the same bump in the same months) and the **noise** (small wiggles around both).

## Summary

- Time series = trend + seasonality + noise.
- Rolling averages reveal the trend; a 12-month window cancels yearly seasonality.
- Seasonal effects = average gap from trend by month.
- Beat simple baselines (naive, seasonal naive, drift) before trusting complex models.
