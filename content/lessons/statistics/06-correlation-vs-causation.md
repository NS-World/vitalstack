---
title: Correlation vs Causation
slug: correlation-vs-causation
subject: statistics
lesson: 6
difficulty: intermediate
excerpt: Measure how two variables move together with Pearson and Spearman correlation, read a correlation matrix, and learn why correlation alone never proves cause.
---

Ice-cream sales and drowning cases rise and fall together. Should we ban ice cream? Of course not: **summer** drives both. This lesson is about measuring "moving together" and, just as important, not over-reading it.

## Correlation: one number for "move together"

The **Pearson correlation coefficient (r)** runs from −1 to +1:

- **+1**: perfect straight-line rise (one goes up, the other goes up).
- **0**: no straight-line relationship.
- **−1**: perfect straight-line fall.

Rough guide: |r| below 0.3 is weak, 0.3 to 0.7 moderate, above 0.7 strong.

```python
import pandas as pd

df = pd.DataFrame({
    "study_hours": [1, 2, 2, 3, 4, 5, 5, 6, 7, 8],
    "marks":       [35, 42, 40, 50, 55, 62, 60, 68, 74, 80],
    "phone_hours": [7, 6, 6, 5, 5, 4, 3, 3, 2, 1],
})
print(df.corr().round(2))
```

Output:

```text
             study_hours  marks  phone_hours
study_hours         1.00   1.00        -0.99
marks               1.00   1.00        -0.98
phone_hours        -0.99  -0.98         1.00
```

`df.corr()` gives a **correlation matrix**: every column against every other. Study hours and marks: 1.00 after rounding (almost a perfect straight line). Phone hours and marks: strongly negative. The diagonal is always 1 (every column correlates perfectly with itself).

```python
import matplotlib.pyplot as plt
import seaborn as sns

sns.heatmap(df.corr(), annot=True, cmap="RdYlGn", vmin=-1, vmax=1)
plt.title("Correlation matrix")
plt.show()
```

::figure stat-corr-heatmap.svg | Heatmap of the correlation matrix for study hours, marks and phone hours

## Correlation only sees straight lines

Pearson's r measures **linear** relationships. A perfect curve can score near zero:

```python
import numpy as np

x = np.arange(-5, 6)
y = x ** 2
print("x:", x)
print("y:", y)
print("Pearson r =", round(np.corrcoef(x, y)[0, 1], 3))
```

Output:

```text
x: [-5 -4 -3 -2 -1  0  1  2  3  4  5]
y: [25 16  9  4  1  0  1  4  9 16 25]
Pearson r = 0.0
```

y is completely determined by x, yet r = 0. **Always plot your data** before trusting a correlation.

For relationships that go steadily up (or down) but not in a straight line, use **Spearman's** rank correlation:

```python
import pandas as pd

growth = pd.DataFrame({"x": [1, 2, 3, 4, 5, 6, 7, 8], "y": [1, 2, 4, 8, 16, 32, 64, 128]})
print("Pearson :", round(growth["x"].corr(growth["y"]), 3))
print("Spearman:", round(growth["x"].corr(growth["y"], method="spearman"), 3))
```

Output:

```text
Pearson : 0.85
Spearman: 1.0
```

Spearman compares **ranks**, so any always-increasing relationship gets 1.0. It is also far less affected by outliers.

## One outlier can fake a correlation

```python
import numpy as np

rng = np.random.default_rng(3)
a = rng.normal(size=20)
b = rng.normal(size=20)
print("no relation   r =", round(np.corrcoef(a, b)[0, 1], 2))

a_out = np.append(a, 10)
b_out = np.append(b, 10)
print("+ one outlier r =", round(np.corrcoef(a_out, b_out)[0, 1], 2))
```

Output:

```text
no relation   r = -0.26
+ one outlier r = 0.73
```

Twenty unrelated points plus one extreme point, and suddenly r looks "strong". Check for outliers (lesson 1) before reporting a correlation.

## Correlation is not causation

When A and B are correlated, there are at least four possible stories:

1. **A causes B.** More study hours → higher marks.
2. **B causes A.** Higher marks → students enjoy the subject → they study more.
3. **A third factor causes both** (a **confounder**). Summer → ice cream and swimming → drownings.
4. **Coincidence.** Look at enough variables and some will line up by chance.

Here is a confounder at work, built in code so you can see the truth:

```python
import numpy as np
import pandas as pd

rng = np.random.default_rng(8)
temperature = rng.uniform(15, 40, size=365)                  # the hidden cause
ice_cream = 20 * temperature + rng.normal(0, 60, size=365)
drownings = 0.3 * temperature + rng.normal(0, 1.5, size=365)

days = pd.DataFrame({"temp": temperature, "ice_cream": ice_cream, "drownings": drownings})
print("ice cream vs drownings r =", round(days["ice_cream"].corr(days["drownings"]), 2))

hot = days[days["temp"].between(30, 32)]
print("same, on days of 30-32 °C only r =", round(hot["ice_cream"].corr(hot["drownings"]), 2))
```

Output:

```text
ice cream vs drownings r = 0.72
same, on days of 30-32 °C only r = -0.19
```

Across the year the correlation looks strong. Hold temperature (nearly) fixed and it falls to about −0.2, which is just noise: ice cream never caused anything. Comparing like with like is called **controlling for** a variable.

::tip How do you prove cause? With an **experiment**: randomly split people into groups and change only one thing (that is exactly an A/B test from the last lesson). Randomisation spreads confounders evenly across groups, so a difference in outcome can only come from your change.

## Summary

- `df.corr()` → Pearson r from −1 to +1; `method="spearman"` for ranked or curved relationships.
- Correlation sees only straight lines, and one outlier can fake it: **plot first**.
- Correlation can come from cause, reverse cause, a confounder or chance.
- Only a randomised experiment (or very careful analysis) shows causation.

🎉 You have finished **Statistics and Probability**. You can now summarise data, reason with probability, estimate with confidence intervals, test ideas with p-values, and spot misleading correlations: the core toolkit of every data scientist.
