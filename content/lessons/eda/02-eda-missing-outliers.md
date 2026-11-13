---
title: "Missing Values and Outliers: Find, Decide, Fix"
slug: eda-missing-outliers
subject: eda
lesson: 2
difficulty: intermediate
excerpt: Why data goes missing (MCAR, MAR, MNAR), when to drop or fill, and how to find outliers with the IQR and z-score methods, with pandas code you can reuse.
---

Missing values and outliers are where most real-world data science time goes. There is no single right answer, only **good decisions you can explain**. This lesson gives you the decision rules.

::scene The case of the missing ratings
❓ | Gaps | 15% of customer ratings are blank. Do you delete those rows? Fill them in? | bugsy
🎲 | Random? | If ratings are missing at random, filling with the median is fine. | chip
😠 | Not random | But what if unhappy customers skip the rating? Then the blanks ARE the signal. | dev
🏷️ | Flag it | You fill the blanks and add a column "rating_missing", so the model can learn from it. | byte
::end

## Why data goes missing

| Type | Meaning | Example | Safe to fill with mean/median? |
|---|---|---|---|
| **MCAR**: missing completely at random | Nothing explains the gap | A sensor glitch drops random readings | Yes |
| **MAR**: missing at random | Another column explains it | Older customers skip the "app version" field | Fill using that column (e.g. by group) |
| **MNAR**: missing not at random | The missing value itself explains it | Unhappy customers skip the rating | Careful: add a "was missing" flag |

## Options for missing values

```python
import numpy as np
import pandas as pd

df = pd.DataFrame({
    "city": ["Pune", "Pune", "Delhi", "Delhi", "Delhi", "Mumbai"],
    "amount": [250, np.nan, 600, 640, np.nan, 480],
    "rating": [5, 4, np.nan, 2, 3, np.nan],
})

dropped = df.dropna()
print("drop rows with any gap:", len(df), "->", len(dropped))

filled = df.copy()
filled["amount"] = filled["amount"].fillna(filled.groupby("city")["amount"].transform("median"))
filled["rating_missing"] = filled["rating"].isna().astype(int)
filled["rating"] = filled["rating"].fillna(filled["rating"].median())
print(filled)
```

Output:

```text
drop rows with any gap: 6 -> 2
     city  amount  rating  rating_missing
0    Pune   250.0     5.0               0
1    Pune   250.0     4.0               0
2   Delhi   600.0     3.5               1
3   Delhi   640.0     2.0               0
4   Delhi   620.0     3.0               0
5  Mumbai   480.0     3.5               1
```

Dropping threw away two thirds of the data. Filling kept every row: `amount` was filled with the **median of the same city** (MAR thinking), and `rating` got a median fill plus a **flag** in case the gaps mean something (MNAR thinking).

::tip Rule of thumb: drop rows only when very few are missing (a few percent) and they look random. Otherwise fill, and add a "was missing" flag for anything that might not be random.

## Finding outliers: the IQR method

The interquartile range (IQR) is the middle 50% of the data. Values more than 1.5 × IQR beyond the quartiles are flagged.

::walk python | IQR fences, step by step
import numpy as np
x = np.array([28, 30, 32, 35, 35, 38, 41, 44, 47, 120])
q1, q3 = np.percentile(x, [25, 75])
iqr = q3 - q1
low, high = q1 - 1.5 * iqr, q3 + 1.5 * iqr
print(x[(x < low) | (x > high)])
::notes
1: NumPy for percentiles.
2: Ten delivery times in minutes. One looks suspicious. // vars: x=[28, 30, 32, 35, 35, 38, 41, 44, 47, 120]
3: The 25th and 75th percentiles: a quarter of values are below **q1**, a quarter above **q3**. // vars: q1=32.75, q3=43.25
4: The IQR: how wide the middle half is. // vars: iqr=10.5
5: The fences: 1.5 × IQR beyond each quartile. // deep: The 1.5 multiplier is a convention from John Tukey's box plots. Use 3 instead of 1.5 to flag only extreme outliers. // vars: low=17.0, high=59.0
6: Anything outside the fences is flagged. Only 120. // out: [120]
::end

## The z-score method

A z-score says how many standard deviations a value is from the mean. Values beyond about ±3 are rare in roughly bell-shaped data.

```python
x = pd.Series([28, 30, 32, 35, 35, 38, 41, 44, 47, 120])
z = (x - x.mean()) / x.std()
print(z.round(2).tolist())
```

Output:

```text
[-0.63, -0.55, -0.48, -0.37, -0.37, -0.26, -0.15, -0.04, 0.07, 2.77]
```

Notice 120 gets z ≈ 2.8, just under 3. One big outlier inflates the standard deviation and partly hides itself. That's why the IQR method is often preferred for small or skewed data.

## What to do with an outlier

1. **Error?** (−5 minutes, age 250) → fix it or set it to missing.
2. **Real but rare?** (120 minutes in a storm) → keep it; consider **capping** (winsorising) or a model that's robust to outliers.
3. **The thing you're looking for?** (fraud, machine failure) → never delete it: that's your signal.

```python
capped = x.clip(upper=x.quantile(0.95))
print(round(capped.max(), 1), "<- capped at the 95th percentile")
```

Output:

```text
87.1 <- capped at the 95th percentile
```

## See it as a chart: the IQR fences

The IQR method draws two fences: Q1 − 1.5×IQR and Q3 + 1.5×IQR. Anything outside is flagged for a closer look.

```python
import matplotlib.pyplot as plt
import numpy as np

minutes = np.array([28, 30, 31, 32, 33, 35, 35, 36, 38, 40, 41, 44, 47, 52, 95, 120])
q1, q3 = np.percentile(minutes, [25, 75])
iqr = q3 - q1
low, high = q1 - 1.5 * iqr, q3 + 1.5 * iqr
outlier = (minutes < low) | (minutes > high)

fig, ax = plt.subplots(figsize=(8, 2.8))
ax.axvspan(q1, q3, color="#e3f3eb", label=f"middle 50% (Q1 {q1:.0f} to Q3 {q3:.0f})")
ax.axvline(low, color="#f59e0b", ls="--", label=f"fences {low:.1f} and {high:.1f}")
ax.axvline(high, color="#f59e0b", ls="--")
jitter = np.random.default_rng(0).uniform(-0.2, 0.2, len(minutes))
ax.scatter(minutes[~outlier], jitter[~outlier], s=50, color="#0a7d4f")
ax.scatter(minutes[outlier], jitter[outlier], s=80, color="#c2255c", label="outliers")
ax.set_ylim(-1, 1)
ax.set_yticks([])
ax.set_xlabel("delivery minutes")
ax.legend(frameon=False, loc="upper right", fontsize=9)
fig.tight_layout()
plt.show()
```

::figure eda-iqr-fences.svg | Delivery times as dots with the middle 50% shaded and two dashed fences; the 95 and 120 minute deliveries sit beyond the upper fence in pink

The method only **flags** values. Whether 95 minutes is an error or a real (very late) delivery is a question for the business, not for the formula.

## Summary

- Know why data is missing: MCAR, MAR or MNAR.
- Drop rarely; fill by group where it makes sense; add "was missing" flags.
- Find outliers with IQR fences (robust) or z-scores (for bell-shaped data).
- Errors get fixed, real rare values get kept or capped, and signals are never deleted.
