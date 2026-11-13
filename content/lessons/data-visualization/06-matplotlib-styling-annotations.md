---
title: Matplotlib Styling and Annotations: Make Charts Explain Themselves
slug: matplotlib-styling-annotations
subject: data-visualization
lesson: 6
difficulty: intermediate
excerpt: Highlight what matters with colour, arrows, reference lines and labels, use built-in styles, and export sharp charts for reports and slides.
---

A chart is finished when a reader understands it in five seconds without you standing next to them. This lesson shows the small touches that get you there: one highlight colour, an arrow pointing at the key moment, a target line, clean axes, and export settings that look sharp on any screen.

## The anatomy of a figure

Every Matplotlib chart is built from the same parts. Knowing their names makes the documentation easy to read:

```text
Figure (the whole image)
└── Axes (one chart area)
    ├── Title                 ax.set_title()
    ├── x-axis / y-axis       ax.set_xlabel(), ax.set_ylabel(), ax.set_xlim()
    ├── Ticks and tick labels ax.set_xticks(), ax.tick_params()
    ├── Spines (the 4 borders) ax.spines["top"].set_visible(False)
    ├── Plotted data          ax.plot(), ax.bar(), ax.scatter()
    ├── Legend                ax.legend()
    └── Annotations           ax.annotate(), ax.text(), ax.axhline()
```

## Highlight one thing with colour

Grey for context, one strong colour for the point you want to make:

```python
import matplotlib.pyplot as plt

cities = ["Pune", "Delhi", "Mumbai", "Chennai", "Kolkata", "Jaipur"]
growth = [38, 12, 9, 15, 7, 11]
colors = ["#0a7d4f" if c == "Pune" else "#c9ced6" for c in cities]

fig, ax = plt.subplots(figsize=(7, 3.8))
bars = ax.bar(cities, growth, color=colors)
ax.bar_label(bars, fmt="%d%%")
ax.set_ylim(0, 44)
ax.set_title("Pune grew 3x faster than any other city", loc="left", fontweight="bold")
ax.set_ylabel("Growth in orders (%)")
for side in ["top", "right"]:
    ax.spines[side].set_visible(False)
plt.show()
```

::figure matplotlib-highlight-bar.svg | Bar chart with Pune in green at 38 percent and other cities in light grey, all below 16 percent

Notice three habits: the **title states the conclusion**, the values are written on the bars, and the top and right borders are gone because they add nothing.

## Point at the moment with an arrow

`ax.annotate()` writes text and draws an arrow to a data point:

```python
import matplotlib.pyplot as plt

months = list(range(1, 13))
users = [200, 230, 250, 260, 270, 640, 700, 760, 800, 850, 910, 980]

fig, ax = plt.subplots(figsize=(7, 3.8))
ax.plot(months, users, color="#1d5fd1", linewidth=2.5, marker="o")
ax.annotate(
    "App launched on Play Store",
    xy=(6, 640), xytext=(1.5, 820),
    arrowprops=dict(arrowstyle="->", color="#333", lw=1.4),
    fontsize=11,
)
ax.axhline(750, color="tab:orange", linestyle="--", linewidth=1.5)
ax.text(1, 765, "Goal: 750 users", va="bottom", color="tab:orange")
ax.set_xticks(months, ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"])
ax.set_title("Monthly active users doubled after the app launch", loc="left")
ax.set_ylabel("Users")
ax.spines[["top", "right"]].set_visible(False)
plt.show()
```

::figure matplotlib-annotate-arrow.svg | Line chart of monthly users with an arrow pointing to June labelled App launched on Play Store, and a dashed orange goal line at 750

- `xy` is the point the arrow touches; `xytext` is where the text sits.
- `axhline` / `axvline` draw horizontal or vertical reference lines (targets, averages, launch dates).
- `ax.text(x, y, "...")` puts free text anywhere in data coordinates.

## Shade a period

`axvspan` shades a range, perfect for "during the sale" or "lockdown":

```python
import matplotlib.pyplot as plt

days = list(range(1, 31))
sales = [50, 52, 49, 55, 53, 51, 54, 56, 58, 120, 135, 140, 130, 62,
         60, 59, 61, 63, 60, 62, 64, 66, 65, 67, 70, 69, 71, 72, 74, 75]

fig, ax = plt.subplots(figsize=(7, 3.8))
ax.axvspan(10, 13, color="#ffd400", alpha=0.35, label="Diwali sale")
ax.plot(days, sales, color="#333")
ax.set_title("The 4-day sale brought 2.5x daily sales", loc="left")
ax.set_xlabel("Day of month")
ax.set_ylabel("Orders")
ax.legend(frameon=False)
ax.spines[["top", "right"]].set_visible(False)
plt.show()
```

::figure matplotlib-axvspan.svg | Daily orders line chart with days 10 to 13 shaded yellow as the Diwali sale, where orders jump from about 55 to 140

## Built-in styles

Matplotlib ships with ready-made looks. Try them in one line:

```python
import matplotlib.pyplot as plt

print(sorted(s for s in plt.style.available if not s.startswith("_"))[:6])
```

```text
plt.style.use("seaborn-v0_8-whitegrid")   # clean grid, good default
plt.style.use("ggplot")                   # grey background like R's ggplot2
plt.style.use("dark_background")          # for dark slides
plt.style.use("default")                  # back to normal
```

To change one chart only, use `with plt.style.context("ggplot"):` around the plotting code.

## Colour that works for everyone

- Use **one highlight colour** and grey for the rest.
- About 1 in 12 men has red-green colour blindness: don't rely on red vs green alone. Add labels, or use blue vs orange.
- For values from low to high, use a **sequential** colour map like `"viridis"` or `"Blues"`. Avoid rainbow (`"jet"`): it invents boundaries that aren't in the data.

## Export sharp charts

```text
fig.savefig("growth.png", dpi=200, bbox_inches="tight")     # slides, WhatsApp, docs
fig.savefig("growth.svg", bbox_inches="tight")              # websites: sharp at any zoom
fig.savefig("growth.pdf", bbox_inches="tight")              # print and reports
```

- `dpi=200` makes PNGs crisp on phones and projectors.
- `bbox_inches="tight"` trims the white border so labels aren't cut off.
- Call `savefig` **before** `plt.show()`.

::hint Quick check | Your boss looks at your line chart and asks "So what happened in June?". What would you add? An `ax.annotate()` arrow at June with the reason, and a title that states the conclusion.

## Summary

- Title = the conclusion; values on bars; remove the top and right spines.
- Grey for context, one colour for the point; don't rely on red vs green.
- `annotate()` for arrows, `axhline()`/`axvline()` for targets, `axvspan()` to shade a period.
- `plt.style.use()` changes the whole look; `savefig(..., dpi=200, bbox_inches="tight")` exports cleanly.
