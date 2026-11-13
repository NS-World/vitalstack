---
title: Parts of a Whole: Pie, Donut, Stacked Bar and Area Charts
slug: matplotlib-pie-stacked-area
subject: data-visualization
lesson: 7
difficulty: intermediate
excerpt: Show shares and totals honestly with donut charts, 100% stacked bars and stacked area charts, and learn when a simple bar chart is the better choice.
---

"What share comes from each part?" is one of the most common questions in reports: budget split, traffic sources, market share. This lesson shows four ways to answer it, and the one rule that decides which to use.

## The rule: few parts, one moment → donut; many parts or comparisons → bars

People are bad at comparing angles and good at comparing lengths. So:

| Question | Best chart |
|---|---|
| Share of 2–5 parts, at one moment | Donut (or pie) |
| Share of many parts, or exact comparison | Sorted bar chart |
| Shares across several groups | 100% stacked bar |
| How a total and its parts change over time | Stacked area |

## A donut chart done right

```python
import matplotlib.pyplot as plt

sources = ["Google search", "YouTube", "Instagram", "Direct"]
visits = [52, 23, 15, 10]
colors = ["#0a7d4f", "#1d5fd1", "#e1306c", "#c9ced6"]

fig, ax = plt.subplots(figsize=(5.5, 5.5))
wedges, texts, autotexts = ax.pie(
    visits, labels=sources, colors=colors, startangle=90, counterclock=False,
    autopct="%1.0f%%", pctdistance=0.78, wedgeprops=dict(width=0.42, edgecolor="white"),
)
for t in autotexts:
    t.set_color("white")
    t.set_fontweight("bold")
ax.text(0, 0, "100k\nvisits", ha="center", va="center", fontsize=14, fontweight="bold")
ax.set_title("Half of our visitors come from Google search")
plt.show()
```

::figure matplotlib-donut.svg | Donut chart of website visits: Google search 52 percent, YouTube 23, Instagram 15, Direct 10, with 100k visits written in the centre

What makes it good:

- only 4 parts, largest first, starting at 12 o'clock (`startangle=90, counterclock=False`);
- the percentages are written on the slices;
- `wedgeprops=dict(width=0.42)` makes the hole, and the centre shows the total;
- the title says the conclusion.

::tip Never use 3D pie charts or "exploded" slices. Tilting the pie makes front slices look bigger than they are, so the chart lies.

## When the pie fails: use sorted bars

Eight states with similar shares are impossible to compare as slices. The same data as sorted horizontal bars is instantly readable:

```python
import matplotlib.pyplot as plt

states = ["Maharashtra", "Karnataka", "Tamil Nadu", "Delhi", "Gujarat", "Telangana", "Kerala", "Others"]
share = [21, 16, 13, 12, 10, 9, 7, 12]

order = sorted(range(len(states) - 1), key=lambda i: share[i]) 
labels = ["Others"] + [states[i] for i in order]
values = [share[-1]] + [share[i] for i in order]

fig, ax = plt.subplots(figsize=(7, 4))
bars = ax.barh(labels, values, color=["#c9ced6"] + ["#1d5fd1"] * (len(values) - 1))
ax.bar_label(bars, fmt="%d%%", padding=3)
ax.set_title("Share of students by state", loc="left")
ax.set_xlabel("% of students")
ax.spines[["top", "right"]].set_visible(False)
plt.show()
```

::figure matplotlib-sorted-share-bars.svg | Horizontal bar chart of student share by state, sorted with Maharashtra at the top at 21 percent and Others in grey at the bottom

## 100% stacked bars: compare shares across groups

```python
import numpy as np
import matplotlib.pyplot as plt

years = ["2023", "2024", "2025"]
mobile = np.array([48, 61, 72])
desktop = np.array([45, 34, 24])
tablet = np.array([7, 5, 4])

fig, ax = plt.subplots(figsize=(7, 3.8))
ax.bar(years, mobile, label="Mobile", color="#0a7d4f")
ax.bar(years, desktop, bottom=mobile, label="Desktop", color="#1d5fd1")
ax.bar(years, tablet, bottom=mobile + desktop, label="Tablet", color="#c9ced6")
for i, m in enumerate(mobile):
    ax.text(i, m / 2, f"{m}%", ha="center", va="center", color="white", fontweight="bold")
ax.set_ylim(0, 100)
ax.set_ylabel("% of visits")
ax.set_title("Mobile is now 72% of all visits", loc="left")
ax.legend(ncol=3, frameon=False, loc="upper center", bbox_to_anchor=(0.5, -0.08))
ax.spines[["top", "right"]].set_visible(False)
plt.show()
```

::figure matplotlib-stacked-100.svg | One hundred percent stacked bars for 2023, 2024 and 2025 showing mobile share rising from 48 to 72 percent while desktop falls

The trick is `bottom=`: each new layer starts where the previous one ended. Put the most important part at the bottom, where it's easiest to compare.

## Stacked area: the total and its parts over time

```python
import matplotlib.pyplot as plt

months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun"]
free = [800, 950, 1100, 1300, 1500, 1750]
monthly = [60, 80, 95, 120, 150, 170]
yearly = [20, 35, 55, 80, 110, 150]

fig, ax = plt.subplots(figsize=(7, 3.8))
ax.stackplot(months, yearly, monthly, free,
             labels=["Pro Yearly", "Pro Monthly", "Free"],
             colors=["#0a7d4f", "#34c987", "#dfe3e8"])
ax.set_title("Learners grew every month; paid plans grew fastest", loc="left")
ax.set_ylabel("Learners")
ax.legend(loc="upper left", frameon=False)
ax.spines[["top", "right"]].set_visible(False)
plt.show()
```

::figure matplotlib-stackplot.svg | Stacked area chart of learners from January to June, with Pro Yearly at the bottom, Pro Monthly in the middle and Free on top, all growing

::hint Quick check | You want to show how 12 product categories split the budget. Donut or bars? Sorted bars: with 12 parts, slices become too thin to compare.

## Summary

- Lengths beat angles: use donuts only for 2–5 parts at one moment, biggest first.
- Write percentages on the chart and put the total in the donut's centre.
- `bottom=` builds stacked bars; `ax.stackplot()` builds stacked areas.
- Many parts or close values → sorted bar chart. No 3D or exploded pies, ever.
