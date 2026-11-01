---
title: "Project 1: Sales Performance Analysis and Dashboard"
slug: project-sales-analysis
subject: ds-projects
lesson: 1
difficulty: intermediate
excerpt: A complete portfolio project: analyse a year of retail sales with pandas, find the top products, regions and trends, build KPI charts, and write the insights a manager can act on.
---

Every company asks the same questions about sales: what's growing, what's falling, where, and why. In this project you answer them end to end, the most common task in data analyst interviews and jobs.

::scene The Monday-morning sales review
☕ | The ask | "How did we do this year, and where should we focus next quarter?" asks the sales head. | dev
🧹 | Prepare | 12 months of orders, cleaned and joined with product and region tables. | chip
🔍 | Analyse | Revenue by month, region and category; growth rates; top and bottom products. | byte
📊 | Present | One page: four KPIs, two charts and three clear recommendations. | bugsy
::end

## The data

```python
import numpy as np
import pandas as pd

rng = np.random.default_rng(42)
n = 3000
dates = pd.to_datetime("2025-01-01") + pd.to_timedelta(rng.integers(0, 365, n), unit="D")
category = rng.choice(["Tea", "Snacks", "Kitchen", "Gifts"], n, p=[0.4, 0.3, 0.2, 0.1])
region = rng.choice(["North", "South", "East", "West"], n)
base = {"Tea": 300, "Snacks": 180, "Kitchen": 650, "Gifts": 900}
festive = np.isin(dates.month, [10, 11]) * (category == "Gifts") * 1.8 + 1
qty = rng.integers(1, 5, n)
sales = pd.DataFrame({"date": dates, "category": category, "region": region, "qty": qty,
                      "revenue": (np.array([base[c] for c in category]) * qty * festive).round()})
print(sales.shape, "| total revenue: Rs", f"{sales['revenue'].sum():,.0f}")
```

Output:

```text
(3000, 5) | total revenue: Rs 3,115,400
```

## KPIs

```python
kpi = {
    "revenue": sales["revenue"].sum(),
    "orders": len(sales),
    "avg_order": sales["revenue"].mean(),
    "units": sales["qty"].sum(),
}
print({k: round(float(v)) for k, v in kpi.items()})
```

Output:

```text
{'revenue': 3115400, 'orders': 3000, 'avg_order': 1038, 'units': 7426}
```

## Trends and breakdowns

```python
monthly = sales.groupby(sales["date"].dt.to_period("M"))["revenue"].sum()
print("best month:", monthly.idxmax(), "| worst month:", monthly.idxmin())

by_cat = sales.groupby("category")["revenue"].sum().sort_values(ascending=False)
print((by_cat / by_cat.sum()).round(3).to_dict())

h1 = sales[sales["date"].dt.month <= 6].groupby("region")["revenue"].sum()
h2 = sales[sales["date"].dt.month > 6].groupby("region")["revenue"].sum()
print("H2 vs H1 growth:", ((h2 / h1 - 1) * 100).round(1).to_dict())
```

Output:

```text
best month: 2025-10 | worst month: 2025-09
{'Kitchen': 0.3, 'Tea': 0.29, 'Gifts': 0.281, 'Snacks': 0.129}
H2 vs H1 growth: {'East': 13.1, 'North': 17.7, 'South': 17.5, 'West': 9.9}
```

::walk python | Turning numbers into an insight
gifts_share = 0.10
gifts_revenue_share = 0.281
multiplier = gifts_revenue_share / gifts_share
insight = f"Gifts are {gifts_share:.0%} of orders but {gifts_revenue_share:.0%} of revenue"
print(insight, round(multiplier, 1))
::notes
1: Gifts are about 10% of orders (from how we generated the data). // vars: gifts_share=0.1
2: But they bring 28.1% of revenue (from the category breakdown). // vars: gifts_revenue_share=0.281
3: Each gift order is worth about 2.8× its share: high-value orders. // vars: multiplier=2.81
4: Write the finding in plain words. // deep: A good insight has a number, a comparison and a "so what". The "so what" here: promote gifting beyond Diwali (Rakhi, weddings, corporate gifting) to smooth out the festive spike. // vars: insight=Gifts are 10% of orders but 28% of revenue
5: This sentence is worth more to a manager than any chart. // out: Gifts are 10% of orders but 28% of revenue 2.8
::end

## Charts for the dashboard

```python
import matplotlib.pyplot as plt

fig, axes = plt.subplots(1, 2, figsize=(11, 3.5))
monthly.plot(ax=axes[0], marker="o", title="Monthly revenue (Rs)")
by_cat.plot.barh(ax=axes[1], title="Revenue by category (Rs)")
plt.tight_layout()
```

Build the same in Power BI or Tableau for the final version: KPI cards on top, the trend line, the category bars and a region slicer.

## Recommendations (the part that gets you hired)

1. **Gifts:** high value and highly seasonal → run gifting campaigns for Rakhi and weddings, not only Diwali.
2. **West region** grew slowest in H2 → investigate delivery times and local pricing there.
3. **September dip** just before the festive season → plan a promotion or new-product launch for the slow month.

## Put it in your portfolio

- GitHub repo: `README.md` (problem, data, approach, 3 insights, screenshot), notebook, dashboard file.
- Use a real public dataset next (for example the Superstore sample data or a Kaggle retail dataset).
- CV line: *"Analysed 3,000 orders across 4 regions; identified gifts as 28% of revenue from 10% of orders; dashboard in Power BI."*

## Summary

- Clean → KPIs → trends → breakdowns → growth → insights → recommendations.
- Every insight needs a number, a comparison and a "so what".
- Present on one page; publish the repo with a clear README.
