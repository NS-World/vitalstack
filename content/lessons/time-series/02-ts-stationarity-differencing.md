---
title: "Stationarity, Differencing and Autocorrelation"
slug: ts-stationarity-differencing
subject: time-series
lesson: 2
difficulty: intermediate
excerpt: What stationarity means, why forecasting models need it, how differencing and log transforms create it, and how to read autocorrelation (ACF) to spot patterns and choose lags.
---

Most classic forecasting models assume the series is **stationary**: its average and spread don't drift over time. Real data almost never is. Learning to make it stationary is the key step before ARIMA.

::scene Taming a wandering series
🎢 | Wandering | Monthly sales keep climbing. The average in 2023 is nothing like 2025's. | byte
➖ | Difference | Instead of sales, look at the change from last month: +3, +2, +5, −1… | dev
⚖️ | Stable | The changes hover around a steady average. The series is now stationary. | chip
🔁 | Echoes | Autocorrelation shows this month still echoes 12 months ago: seasonality. | bugsy
::end

## Stationary or not?

A stationary series has a constant mean, constant variance and patterns that don't depend on *when* you look.

```python
import numpy as np
import pandas as pd

rng = np.random.default_rng(4)
trending = pd.Series(np.cumsum(rng.normal(1.0, 2.0, 120)) + 50)   # drifts upward
for name, s in (("original", trending), ("differenced", trending.diff().dropna())):
    first, second = s.iloc[:len(s) // 2], s.iloc[len(s) // 2:]
    print(f"{name:>11}: mean {first.mean():6.1f} -> {second.mean():6.1f}")
```

Output:

```text
   original: mean   74.8 ->  132.1
differenced: mean    0.8 ->    1.0
```

The original's mean jumps between halves; the differenced series stays put.

## Differencing, step by step

::walk python | First difference
sales = [100, 104, 109, 111, 118]
diff = [b - a for a, b in zip(sales, sales[1:])]
avg = sum(diff) / len(diff)
print(diff, avg)
::notes
1: A rising series. Its average depends on which months you look at. // vars: sales=[100, 104, 109, 111, 118]
2: Subtract each value from the next: the month-on-month change. // deep: This is "first differencing" (d=1 in ARIMA). For seasonal data you can also take a seasonal difference: this month minus the same month last year (lag 12). // vars: diff=[4, 5, 2, 7]
3: The changes hover around a stable average. // vars: avg=4.5
4: Forecast the change, then add it back to the last value to get a forecast of sales. // out: [4, 5, 2, 7] 4.5
::end

If the swings grow with the level (multiplicative growth), take a **log** first, then difference: `np.log(series).diff()` is roughly the percentage change.

## Autocorrelation (ACF)

Autocorrelation at lag k = correlation between the series and itself shifted by k steps.

```python
months = np.arange(96)
seasonal = pd.Series(10 * np.sin(2 * np.pi * months / 12) + rng.normal(0, 2, 96))
acf = {lag: round(float(seasonal.autocorr(lag)), 2) for lag in (1, 3, 6, 12)}
print(acf)
```

Output:

```text
{1: 0.79, 3: 0.03, 6: -0.91, 12: 0.91}
```

High correlation at lag 12 and strongly negative at lag 6: a yearly cycle (peaks a year apart, troughs half a year from peaks). The ACF plot (`statsmodels.graphics.tsaplots.plot_acf`) shows all lags at once, and the **PACF** helps choose ARIMA's AR order (next lesson).

## Testing for stationarity

The **Augmented Dickey-Fuller (ADF)** test is the standard check (`statsmodels.tsa.stattools.adfuller`). A p-value below 0.05 suggests the series is stationary.

```python
from statsmodels.tsa.stattools import adfuller

print("original p =", round(adfuller(trending)[1], 3))
print("differenced p =", round(adfuller(trending.diff().dropna())[1], 3))
```

Typical result: the original has a large p-value (not stationary); the differenced series a tiny one (stationary).

::tip Don't over-difference. If one difference already makes the series stable, a second one only adds noise. Check the plot and the ADF test after each step.

## See it as a chart: differencing and the ACF

Left: a series that drifts upward is not stationary. Middle: after differencing (each value minus the one before) it wobbles around a steady level. Right: the autocorrelation of a seasonal series peaks again at lag 12, the length of its cycle.

```python
import matplotlib.pyplot as plt
import numpy as np
import pandas as pd

rng = np.random.default_rng(4)
trending = pd.Series(np.cumsum(rng.normal(1.0, 2.0, 120)) + 50)
months = np.arange(96)
seasonal = pd.Series(10 * np.sin(2 * np.pi * months / 12) + rng.normal(0, 2, 96))
lags = range(1, 25)
acf = [seasonal.autocorr(lag) for lag in lags]

fig, axes = plt.subplots(1, 3, figsize=(11, 3.2))
axes[0].plot(trending, color="#c2255c")
axes[0].set_title("Original: drifts up")
diff = trending.diff().dropna()
axes[1].plot(diff, color="#0a7d4f")
axes[1].axhline(diff.mean(), color="#1b1f27", ls="--", lw=1)
axes[1].set_title("Differenced: steady level")
axes[2].bar(lags, acf, color=["#f59e0b" if lag == 12 else "#2563eb" for lag in lags])
axes[2].axhline(0, color="#1b1f27", lw=0.8)
axes[2].set_title("ACF of a seasonal series")
axes[2].set_xlabel("lag (months)")
fig.tight_layout()
plt.show()
```

::figure ts-differencing-acf.svg | Three panels: a series drifting upward, the same series differenced and flat around a dashed mean, and autocorrelation bars that rise again at lag 12

Reading an ACF plot is a core forecasting skill: a slow decay means "difference me", and a spike at lag 12 means a yearly season.

## Summary

- Stationary = stable mean, variance and patterns over time; many models need it.
- Differencing (and logs for growing swings) removes trends.
- ACF reveals memory and seasonality (peaks at lag 12 for yearly patterns).
- Use the ADF test to confirm; don't over-difference.
