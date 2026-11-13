---
title: Seaborn in Depth: KDE, Violin, Pair Plots and Small Multiples
slug: seaborn-distributions-facets
subject: data-visualization
lesson: 10
difficulty: intermediate
excerpt: Compare distributions with KDE and violin plots, split any chart into small multiples with catplot and FacetGrid, and explore every pair of columns at once with pairplot.
---

Seaborn shines when you want to compare groups. One line of code splits a chart by category, colours it, and adds a legend. This lesson covers the four charts analysts use most when exploring a new dataset.

All examples build their own small dataset, so they run anywhere, including Colab.

## Setting up the data

```python
import numpy as np
import pandas as pd

rng = np.random.default_rng(42)
n = 300
batches = rng.choice(["Weekday", "Weekend", "Evening"], n, p=[0.45, 0.25, 0.30])
base = {"Weekday": 62, "Weekend": 70, "Evening": 58}
hours = np.clip(rng.normal(30, 10, n), 2, 60).round(1)
score = np.array([base[b] for b in batches]) + 0.45 * hours + rng.normal(0, 6, n)
df = pd.DataFrame({
    "batch": batches,
    "study_hours": hours,
    "score": np.clip(score, 0, 100).round(1),
    "attendance": np.clip(rng.normal(80, 10, n), 40, 100).round(0),
})
print(df.shape)
print(df.groupby("batch")["score"].mean().round(1).sort_values(ascending=False).to_dict())
```

Output:

```text
(300, 4)
{'Weekend': 83.8, 'Weekday': 75.5, 'Evening': 69.8}
```

## KDE: a smooth histogram for comparing groups

A **KDE** (kernel density estimate) draws a smooth curve of where values are common. Overlapping histograms get messy; overlapping KDE curves stay readable:

```python
import seaborn as sns
import matplotlib.pyplot as plt

fig, ax = plt.subplots(figsize=(7, 3.8))
sns.kdeplot(data=df, x="score", hue="batch", fill=True, common_norm=False, alpha=0.35, ax=ax)
ax.set_title("Weekend batch scores are the highest", loc="left")
ax.set_xlabel("Exam score")
plt.show()
```

::figure seaborn-kde-batches.svg | Three overlapping smooth density curves of exam scores for Weekday, Weekend and Evening batches, with the Weekend curve furthest to the right

- `hue="batch"` gives each group its own colour and a legend.
- `common_norm=False` scales each curve to its own group, so a small group isn't squashed.

## Violin plots by category

```python
import seaborn as sns
import matplotlib.pyplot as plt

fig, ax = plt.subplots(figsize=(7, 3.8))
sns.violinplot(data=df, x="batch", y="score", order=["Weekend", "Weekday", "Evening"],
               inner="quartile", color="#cfe9dc", ax=ax)
ax.set_title("Score spread per batch (dashed lines = quartiles)", loc="left")
ax.set_xlabel("")
plt.show()
```

::figure seaborn-violin-batches.svg | Violin plots of exam scores for Weekend, Weekday and Evening batches with dashed quartile lines, Weekend highest

## Small multiples in one line: `catplot` and `relplot`

Seaborn's figure-level functions split a chart into a grid with `col=` (and `row=`). Each panel uses the same axes, so comparing is easy:

```python
import seaborn as sns
import matplotlib.pyplot as plt

g = sns.relplot(data=df, x="study_hours", y="score", col="batch",
                col_order=["Weekend", "Weekday", "Evening"], height=3.2, aspect=0.9,
                color="#1d5fd1", alpha=0.6)
g.set_titles("{col_name}")
g.set_axis_labels("Study hours per week", "Score")
g.figure.suptitle("More study, higher scores, in every batch", x=0.02, ha="left", y=1.04)
plt.show()
```

::figure seaborn-relplot-cols.svg | Three scatter plots side by side, one per batch, each showing scores rising with study hours

| Function | Draws | Split with |
|---|---|---|
| `sns.relplot()` | scatter or line | `col=`, `row=`, `hue=` |
| `sns.catplot()` | box, violin, bar, strip | `col=`, `row=`, `hue=` |
| `sns.displot()` | histogram, KDE | `col=`, `row=`, `hue=` |

::tip Figure-level functions (`relplot`, `catplot`, `displot`, `pairplot`) make their own figure, so don't pass `ax=`. Change titles with the returned grid: `g.set_titles()`, `g.set_axis_labels()`.

## Pair plot: every pair of columns at once

When you first open a dataset, `pairplot` shows each numeric column's distribution on the diagonal and every pair as a scatter plot:

```python
import seaborn as sns
import matplotlib.pyplot as plt

g = sns.pairplot(df, vars=["study_hours", "attendance", "score"], hue="batch",
                 corner=True, height=2.1, plot_kws=dict(alpha=0.5, s=18))
g.figure.suptitle("Quick look at every pair of columns", y=1.02)
plt.show()
```

::figure seaborn-pairplot.svg | Corner pair plot of study hours, attendance and score coloured by batch, with density curves on the diagonal and scatter plots below

Reading it: score rises with study hours in every batch, while attendance barely moves score. That's a quick hint about which features matter before you build a model.

::hint Quick check | You want one histogram per city, side by side, same axes. Which function? `sns.displot(data=df, x="value", col="city")`.

## Summary

- `kdeplot(hue=...)` compares group distributions as smooth curves; `violinplot` shows them by category.
- `relplot`, `catplot` and `displot` split any chart into small multiples with `col=` and `row=`.
- `pairplot` gives a first look at every pair of numeric columns, coloured by group.
- Figure-level functions return a grid: style it with `g.set_titles()` and `g.set_axis_labels()`.
