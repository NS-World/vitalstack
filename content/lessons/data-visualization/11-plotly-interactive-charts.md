---
title: Interactive Charts with Plotly: Hover, Zoom and Share
slug: plotly-interactive-charts
subject: data-visualization
lesson: 11
difficulty: intermediate
excerpt: Build interactive line, bar, scatter and bubble charts with Plotly Express, add hover details and facets, and save charts as web pages anyone can open.
---

Matplotlib and Seaborn make pictures. **Plotly** makes charts you can play with: hover to see exact values, drag to zoom, click legend items to hide a series. They work in Jupyter, Colab, dashboards and web pages, which makes them great for sharing with managers and clients.

## Install and the one import you need

Plotly is pre-installed in Google Colab. On your laptop:

```bash
pip install plotly            # or: conda install -c conda-forge plotly
```

**Plotly Express** (`px`) builds a full chart from a DataFrame in one line:

```python
import plotly.express as px
import pandas as pd

df = pd.DataFrame({
    "month": ["Jan", "Feb", "Mar", "Apr", "May", "Jun"] * 2,
    "city": ["Pune"] * 6 + ["Delhi"] * 6,
    "orders": [120, 135, 150, 145, 170, 190, 140, 130, 125, 160, 165, 175],
})

fig = px.line(df, x="month", y="orders", color="city", markers=True,
              title="Monthly orders: Pune overtook Delhi in May")
fig.show()
```

::figure plotly-line.svg | Interactive-style Plotly line chart of monthly orders for Pune and Delhi, Pune rising above Delhi in May

In a notebook, this chart is interactive: hover over a point to see the month, city and orders, drag a box to zoom in, and double-click to zoom back out.

## Bar charts with text on the bars

```python
import plotly.express as px

languages = ["Python", "JavaScript", "Java", "SQL", "C++"]
students = [420, 310, 280, 190, 120]

fig = px.bar(x=languages, y=students, text=students,
             labels={"x": "", "y": "Students"},
             title="Most popular course: Python")
fig.update_traces(marker_color="#0a7d4f", textposition="outside")
fig.update_layout(yaxis_range=[0, 480])
fig.show()
```

::figure plotly-bar.svg | Plotly bar chart of students per language with values above each bar, Python highest at 420

`update_traces()` changes how the data is drawn (colour, labels); `update_layout()` changes the frame (axes, title, size, legend).

## Scatter and bubble charts with rich hover

Extra columns can go into colour, size and the hover box:

```python
import numpy as np
import pandas as pd
import plotly.express as px

rng = np.random.default_rng(5)
cities = ["Pune", "Delhi", "Mumbai", "Chennai", "Kolkata", "Jaipur", "Indore", "Kochi"]
data = pd.DataFrame({
    "city": cities,
    "learners": rng.integers(800, 6000, len(cities)),
    "avg_minutes": rng.integers(12, 45, len(cities)),
    "paid_share": rng.uniform(2, 14, len(cities)).round(1),
    "region": ["West", "North", "West", "South", "East", "North", "Central", "South"],
})

fig = px.scatter(data, x="avg_minutes", y="paid_share", size="learners", color="region",
                 hover_name="city", hover_data={"learners": True, "region": False},
                 size_max=45, labels={"avg_minutes": "Average minutes per day", "paid_share": "Paid learners (%)"},
                 title="Learners, study time and paid share by city")
fig.show()
```

::figure plotly-bubble.svg | Plotly bubble chart of cities: average daily minutes on the x-axis, paid learner percentage on the y-axis, bubble size by number of learners, coloured by region

- `hover_name` becomes the bold title of the hover box.
- `hover_data` adds (or hides) columns in the hover box.
- `size=` turns a scatter into a **bubble chart**.

## Facets and animation

Like Seaborn, Plotly splits charts with `facet_col=` and `facet_row=`. It can also animate over time with `animation_frame=`:

```text
fig = px.scatter(df, x="gdp_per_person", y="life_expectancy", size="population",
                 color="continent", animation_frame="year", log_x=True)
```

The chart gets a play button and a year slider: a classic way to show how countries changed over decades.

## Save and share

```text
fig.write_html("orders.html")                     # an interactive web page, opens in any browser
fig.write_html("orders.html", include_plotlyjs="cdn")   # much smaller file, needs internet
fig.write_image("orders.png", scale=2)            # a static picture (needs: pip install kaleido)
```

Send the `.html` file on WhatsApp or email and the reader gets the full interactive chart, no Python needed.

## Matplotlib, Seaborn or Plotly?

| Need | Best choice |
|---|---|
| Full control, print-quality pictures, papers | Matplotlib |
| Quick statistical charts and group comparisons | Seaborn |
| Interactive charts for notebooks, web pages and dashboards | Plotly |
| A full interactive web app with filters | Plotly charts inside Dash or Streamlit |

::hint Quick check | Your manager wants to hover over each point to see the customer's name. Which library and which argument? Plotly, with `hover_name="customer"`.

## Summary

- `import plotly.express as px`, then one line such as `px.line(df, x=..., y=..., color=...)`.
- Charts are interactive in notebooks: hover, zoom, hide series from the legend.
- `size=`, `color=`, `hover_name=` and `hover_data=` pack more columns into one chart.
- `facet_col=` splits, `animation_frame=` animates, `write_html()` shares an interactive page.
