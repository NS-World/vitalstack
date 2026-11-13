---
title: Choosing the Right Chart (and 5 Mistakes That Make Charts Lie)
slug: choosing-the-right-chart
subject: data-visualization
lesson: 9
difficulty: intermediate
excerpt: A simple question-first guide to picking the right chart, plus before-and-after fixes for cut axes, spaghetti lines, dual axes, too many colours and missing context.
---

Picking a chart is not about taste. Start from the **question** your reader has, and the chart almost chooses itself. Then avoid the few mistakes that make charts misleading, even by accident.

## Start from the question

| Your reader asks… | Use | Matplotlib | Seaborn |
|---|---|---|---|
| How did it change over time? | Line chart | `ax.plot()` | `sns.lineplot()` |
| Which is bigger? | Bar chart (sorted) | `ax.bar()`, `ax.barh()` | `sns.barplot()` |
| How are values spread? | Histogram, box, violin | `ax.hist()`, `ax.boxplot()` | `sns.histplot()`, `sns.boxplot()` |
| Are two numbers related? | Scatter plot | `ax.scatter()` | `sns.scatterplot()`, `sns.regplot()` |
| What share is each part? (2–5 parts) | Donut / 100% stacked bar | `ax.pie()` | (use Matplotlib) |
| How do many variables relate? | Heatmap of correlations | `ax.imshow()` | `sns.heatmap()` |
| How do parts of a total change over time? | Stacked area | `ax.stackplot()` | (use Matplotlib) |

```text
Is there time on the x-axis?  ── yes ──► line chart (area if showing parts of a total)
        │ no
Comparing categories?  ── yes ──► sorted bar chart (horizontal if names are long)
        │ no
Looking at one variable's spread?  ── yes ──► histogram (one group) / box or violin (many groups)
        │ no
Two numeric variables?  ── yes ──► scatter plot (+ trend line)
        │ no
Shares of a whole?  ── yes ──► donut for ≤ 5 parts, bars otherwise
```

## Mistake 1: cutting the y-axis of a bar chart

Bar length is how readers compare. Start the axis at 52 and a 3-point difference looks like double:

```python
import matplotlib.pyplot as plt

teams = ["Team A", "Team B"]
score = [54, 57]

fig, (bad, good) = plt.subplots(1, 2, figsize=(8, 3.6))
bad.bar(teams, score, color=["#c9ced6", "#e5383b"])
bad.set_ylim(52, 58)
bad.set_title("Misleading: B looks 3x better")
good.bar(teams, score, color=["#c9ced6", "#0a7d4f"])
good.set_ylim(0, 65)
good.bar_label(good.containers[0])
good.set_title("Honest: B is a little better")
for ax in (bad, good):
    ax.spines[["top", "right"]].set_visible(False)
fig.tight_layout()
plt.show()
```

::figure chart-mistake-cut-axis.svg | Two bar charts of the same scores 54 and 57: the left one starts the axis at 52 so Team B looks three times bigger, the right one starts at zero and shows a small difference

Line charts may zoom in (they show change, not size), but bar charts must start at zero.

## Mistake 2: spaghetti lines

Eight lines in one chart can't be read. Use **small multiples**: one small chart per series, same axes, the one you care about highlighted:

```python
import numpy as np
import matplotlib.pyplot as plt

rng = np.random.default_rng(1)
months = np.arange(1, 13)
cities = ["Pune", "Delhi", "Mumbai", "Chennai", "Kolkata", "Jaipur"]
data = {c: 100 + np.cumsum(rng.normal(3 if c == "Pune" else 1, 4, 12)) for c in cities}

fig, axes = plt.subplots(2, 3, figsize=(8, 4.2), sharex=True, sharey=True)
for ax, city in zip(axes.flat, cities):
    for other in cities:
        ax.plot(months, data[other], color="#e3e6ea", linewidth=1)
    ax.plot(months, data[city], color="#0a7d4f" if city == "Pune" else "#1d5fd1", linewidth=2.2)
    ax.set_title(city, fontsize=11)
    ax.spines[["top", "right"]].set_visible(False)
fig.suptitle("Orders per city: each panel highlights one city", x=0.02, ha="left")
fig.tight_layout()
plt.show()
```

::figure chart-small-multiples.svg | Six small line charts in a 2 by 3 grid, one per city, each highlighting its city in colour against the other cities in light grey, with Pune rising fastest

## Mistake 3: two y-axes

A chart with a left axis for "orders" and a right axis for "ad spend" lets you make any two lines cross wherever you like, just by changing the scales. Readers then see a relationship that the chart's design created. Prefer **two charts stacked**, sharing the x-axis (`plt.subplots(2, 1, sharex=True)`), or plot the two numbers against each other in a scatter plot.

## Mistake 4: too many colours

Colour should carry meaning. If every bar has its own colour, the reader hunts for a legend that tells them nothing. Use one colour for all, plus one highlight, as in the styling lesson.

## Mistake 5: no context

"Sales: 1,240" means nothing alone. Is that good? Add a comparison: last year, the target, the average, or the range. A reference line (`ax.axhline()`), a shaded period (`ax.axvspan()`) or a title like "Sales up 18% vs last June" turns a number into an insight.

## A 30-second checklist before you share

1. Does the **title state the conclusion**?
2. Do bar charts start at **zero**?
3. Are the axes labelled, with **units** (₹, %, minutes)?
4. Is there **one highlight colour**, and is red vs green not the only difference?
5. Is there **context**: a target, average or comparison?
6. Would it still work printed in black and white, or on a phone screen?

::hint Quick check | You want to show how 30 students' study hours relate to their exam marks. Which chart? A scatter plot, one dot per student, with hours on x and marks on y, plus a trend line if you like.

## Summary

- Choose from the reader's question: time → line, compare → bars, spread → histogram/box, relation → scatter, share → donut or stacked bars.
- Bar charts start at zero; line charts may zoom.
- Replace spaghetti with small multiples, two y-axes with two stacked charts.
- One highlight colour, a conclusion title and some context make any chart honest and clear.
