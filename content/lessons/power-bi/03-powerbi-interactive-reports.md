---
title: "Interactive Power BI Reports: Slicers, Drill-Down and Design"
slug: powerbi-interactive-reports
subject: power-bi
lesson: 3
difficulty: beginner
excerpt: Design a Power BI dashboard people actually use: KPI cards, the right chart for each question, slicers, drill-down, tooltips, and a layout checklist, with a Python mock-up of the same report.
---

A dashboard has one job: help someone make a decision in under a minute. This lesson turns your model and measures into a clean, interactive sales report.

::scene A dashboard the manager loves
🎯 | Question | The sales head asks: "How are we doing this month, and where should I look?" | dev
🔢 | KPI cards | Big numbers at the top: Sales, Orders, Avg Order, Growth vs last year. | chip
📉 | Trend | A line chart shows the monthly trend. A dip in March stands out. | byte
🖱️ | Drill | Click March, drill into cities: Delhi caused the dip. Decision made in 40 seconds. | bugsy
::end

## The layout that works

1. **Top row: KPI cards** for the 3–5 numbers that matter.
2. **Middle: trend** (line chart over time) and **comparison** (bar chart by category or city).
3. **Side or top: slicers** (date range, city, category).
4. **Bottom: detail** (a table or matrix) for people who want to dig.

## Picking the right visual

| Question | Visual |
|---|---|
| How is it changing over time? | Line chart |
| Which is biggest? | Bar chart (sorted) |
| What share does each part have? | Bar chart, or a donut with only 2–4 parts |
| Where? | Filled map / map |
| How do two numbers relate? | Scatter chart |
| What makes up the change? | Waterfall chart |
| Exact numbers by two categories | Matrix |

## Interactivity

- **Slicers** filter the whole page. Use dropdown or "between" styles to save space.
- **Cross-filtering:** clicking a bar filters the other visuals. Adjust it in Format → Edit interactions.
- **Drill-down:** put Year → Quarter → Month (or Region → City → Store) in a visual's axis and turn on drill mode.
- **Tooltips:** extra measures shown on hover; even a whole mini report page as a tooltip.
- **Drill-through:** right-click a city → open a detailed page filtered to that city.
- **Bookmarks and buttons** switch between views (chart vs table) like an app.

## A Python mock-up of the report

The same KPI-card-plus-trend logic, in pandas, to check your numbers before building visuals:

```python
import pandas as pd

sales = pd.DataFrame({
    "month": pd.period_range("2026-01", periods=6, freq="M").astype(str).tolist() * 2,
    "city": ["Pune"] * 6 + ["Delhi"] * 6,
    "amount": [52, 55, 61, 58, 66, 70, 40, 44, 29, 47, 51, 54],
})
monthly = sales.groupby("month")["amount"].sum()
print("KPI  total:", monthly.sum(), "| best month:", monthly.idxmax(), "| worst month:", monthly.idxmin())
print(sales.pivot_table(index="month", columns="city", values="amount").loc["2026-02":"2026-04"])
```

Output:

```text
KPI  total: 627 | best month: 2026-06 | worst month: 2026-03
city     Delhi  Pune
month
2026-02   44.0  55.0
2026-03   29.0  61.0
2026-04   47.0  58.0
```

The "drill-down" into March shows Pune grew while Delhi dropped: exactly what the dashboard should surface.

::walk python | A KPI card with a growth arrow
this_month, last_month = 124, 108
growth = (this_month - last_month) / last_month
arrow = "▲" if growth > 0 else "▼"
label = f"₹{this_month}k {arrow} {growth:.0%}"
print(label)
::notes
1: This month's and last month's sales (₹ thousands). // vars: this_month=124, last_month=108
2: Growth as a fraction: (124 − 108) ÷ 108. // deep: In DAX you'd write DIVIDE([Total Sales] - [Sales LM], [Sales LM]), with Sales LM using DATEADD('Date'[Date], -1, MONTH). // vars: growth=0.1481
3: An up arrow for growth, down for decline. Add conditional formatting (green/red) in Power BI. // vars: arrow=▲
4: Format as a card label. // vars: label=₹124k ▲ 15%
5: One glance tells the story. // out: ₹124k ▲ 15%
::end

## Design checklist

- [ ] One page answers one main question; title it with that question.
- [ ] 5–7 visuals at most per page.
- [ ] Consistent colours: one highlight colour for what matters.
- [ ] Sorted bar charts; no 3D charts; no pie charts with 10 slices.
- [ ] Numbers formatted (₹, %, K/M) and units in titles.
- [ ] Test on the phone layout (View → Mobile layout).
- [ ] Row-level security if different people should see different data (each regional manager sees only their region).

::tip Build the report with the person who'll use it. Ask them "what decision will you make from this?" and design for that, not for every column you have.

## Summary

- KPIs on top, trend and comparison in the middle, slicers to the side, details at the bottom.
- Choose visuals by the question; bars and lines answer most of them.
- Slicers, cross-filtering, drill-down, tooltips and drill-through make it interactive.
- Check numbers in Python or with a matrix before polishing visuals.

🎉 You've finished **Dashboards with Power BI**.
