---
title: Spread and Uncertainty: Box Plots, Violin Plots, Error Bars and Bands
slug: matplotlib-distributions-uncertainty
subject: data-visualization
lesson: 8
difficulty: intermediate
excerpt: Compare whole distributions with box and violin plots, read outliers and quartiles, and show uncertainty honestly with error bars and confidence bands.
---

An average hides a lot. Two classes can both average 70 marks while one has everyone between 65 and 75 and the other ranges from 30 to 100. This lesson shows charts that reveal the **spread**, and charts that show how **sure** we are about a number.

## Box plots: five numbers in one picture

A box plot draws the five-number summary of each group:

```text
   o          ← outlier (unusually far from the rest)
   ─┬─        ← whisker: the largest value that is not an outlier
    │
  ┌─┴─┐       ← Q3: 75% of values are below this line
  │   │
  ├───┤       ← median: the middle value
  │   │
  └─┬─┘       ← Q1: 25% of values are below this line
    │
   ─┴─        ← whisker: the smallest value that is not an outlier
```

The box holds the middle 50% of the data (the **interquartile range**, IQR). Points further than 1.5 × IQR from the box are drawn as outliers.

```python
import numpy as np
import matplotlib.pyplot as plt

rng = np.random.default_rng(7)
marks = {
    "Class A": rng.normal(70, 5, 60).clip(0, 100),
    "Class B": rng.normal(70, 16, 60).clip(0, 100),
    "Class C": np.append(rng.normal(78, 7, 58), [35, 32]).clip(0, 100),
}

fig, ax = plt.subplots(figsize=(7, 4))
ax.boxplot(list(marks.values()), tick_labels=list(marks.keys()), widths=0.5,
           patch_artist=True, boxprops=dict(facecolor="#d7f3e6", color="#0a7d4f"),
           medianprops=dict(color="#0a7d4f", linewidth=2))
ax.set_ylabel("Marks")
ax.set_title("Same average for A and B, very different spread", loc="left")
ax.spines[["top", "right"]].set_visible(False)
plt.show()
```

::figure matplotlib-boxplot-classes.svg | Box plots of marks for three classes: Class A narrow around 70, Class B wide around 70, Class C higher around 78 with two low outliers

Reading it: Class A is consistent, Class B is all over the place, and Class C did best overall but two students need help (the outliers).

## Violin plots: the full shape

A violin plot shows the whole shape of the distribution, so you can see two peaks, which a box plot would hide:

```python
import numpy as np
import matplotlib.pyplot as plt

rng = np.random.default_rng(3)
beginners = rng.normal(35, 8, 300)
mixed = np.concatenate([rng.normal(30, 6, 150), rng.normal(75, 6, 150)])
advanced = rng.normal(80, 7, 300)

fig, ax = plt.subplots(figsize=(7, 4))
parts = ax.violinplot([beginners, mixed, advanced], showmedians=True)
for body in parts["bodies"]:
    body.set_facecolor("#1d5fd1")
    body.set_alpha(0.35)
ax.set_xticks([1, 2, 3], ["Beginner batch", "Mixed batch", "Advanced batch"])
ax.set_ylabel("Quiz score")
ax.set_title("The mixed batch has two groups, not one", loc="left")
ax.spines[["top", "right"]].set_visible(False)
plt.show()
```

::figure matplotlib-violin.svg | Violin plots of quiz scores: beginner batch centred near 35, advanced near 80, and the mixed batch with two bulges near 30 and 75

The "mixed batch" violin has two bulges: half the students struggle and half are strong. Its median (around 50) describes almost nobody, which is exactly why you look at the shape.

## Error bars: how sure is each average?

An average from 10 people is less certain than one from 1,000. Error bars show that range:

```python
import matplotlib.pyplot as plt

plans = ["Free", "Pro Monthly", "Pro Yearly"]
avg_minutes = [18, 34, 41]
error = [2, 5, 4]   # e.g. a 95% confidence interval half-width

fig, ax = plt.subplots(figsize=(7, 3.8))
ax.bar(plans, avg_minutes, yerr=error, capsize=8, color=["#c9ced6", "#34c987", "#0a7d4f"])
ax.set_ylabel("Average minutes per day")
ax.set_title("Paid learners study about twice as long", loc="left")
ax.spines[["top", "right"]].set_visible(False)
plt.show()
```

::figure matplotlib-errorbars.svg | Bar chart of average daily study minutes: Free 18, Pro Monthly 34, Pro Yearly 41, each with an error bar showing its uncertainty

If two error bars overlap a lot, be careful about claiming one group is really higher; the statistics lessons show how to test it properly.

## Confidence bands around a line

For a line (a trend or a forecast), shade the uncertain range with `fill_between`:

```python
import numpy as np
import matplotlib.pyplot as plt

weeks = np.arange(1, 13)
forecast = 100 + 12 * weeks
spread = 4 * weeks          # uncertainty grows the further ahead we look

fig, ax = plt.subplots(figsize=(7, 3.8))
ax.plot(weeks, forecast, color="#0a7d4f", linewidth=2.5, label="Forecast")
ax.fill_between(weeks, forecast - spread, forecast + spread, color="#0a7d4f", alpha=0.18, label="Likely range")
ax.set_xlabel("Week")
ax.set_ylabel("Sign-ups")
ax.set_title("Sign-up forecast: the further ahead, the less certain", loc="left")
ax.legend(frameon=False, loc="upper left")
ax.spines[["top", "right"]].set_visible(False)
plt.show()
```

::figure matplotlib-confidence-band.svg | Forecast line of weekly sign-ups rising from about 110 to 245, with a green shaded band that widens from week 1 to week 12

You will see this band in the Time Series and Machine Learning lessons: an honest forecast always shows its range.

::hint Quick check | Two salary groups have the same median, but one box is three times taller. What does that tell you? The taller box has far more variety in salaries; the median alone would hide it.

## Summary

- Box plot = median, quartiles, whiskers and outliers; great for comparing groups.
- Violin plot = the full shape; it reveals two peaks a box plot hides.
- `yerr=` with `capsize=` adds error bars; overlapping bars mean "be careful".
- `fill_between()` shades a confidence band around a line or forecast.
