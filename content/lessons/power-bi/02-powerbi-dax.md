---
title: "Power BI Measures and DAX Basics"
slug: powerbi-dax
subject: power-bi
lesson: 2
difficulty: intermediate
excerpt: Write DAX measures in Power BI: SUM, COUNTROWS, DIVIDE, CALCULATE with filters, and time intelligence like YTD and last year. Learn filter context, with pandas equivalents you can run.
---

DAX (Data Analysis Expressions) is Power BI's formula language. It looks like Excel formulas, but works on whole tables and columns. Ten DAX functions cover most business dashboards.

::scene The measure that changes with every click
🧮 | One formula | Total Sales = SUM(Sales[Amount]). One line, written once. | dev
🖱️ | Click Pune | The slicer filters to Pune. The same measure now shows Pune's total. | chip
📅 | Click March | Add March. Now it shows Pune in March. Nothing was rewritten. | byte
🧠 | Filter context | Every visual cell has its own filters, and the measure respects them. That's DAX's superpower. | bugsy
::end

## Measures vs calculated columns

- A **calculated column** is computed row by row and stored (like adding a column in Excel).
- A **measure** is computed on the fly for whatever filters are active. Use measures for totals, averages and ratios.

## Your first measures

```dax
Total Sales   = SUM ( Sales[Amount] )
Orders        = COUNTROWS ( Sales )
Avg Order     = DIVIDE ( [Total Sales], [Orders] )
Tea Sales     = CALCULATE ( [Total Sales], Products[Category] = "Tea" )
Sales YTD     = TOTALYTD ( [Total Sales], 'Date'[Date] )
Sales LY      = CALCULATE ( [Total Sales], SAMEPERIODLASTYEAR ( 'Date'[Date] ) )
Growth %      = DIVIDE ( [Total Sales] - [Sales LY], [Sales LY] )
```

`DIVIDE` returns blank instead of an error when dividing by zero: use it instead of `/`.

## What the measures compute (pandas)

```python
import pandas as pd

sales = pd.DataFrame({
    "date": pd.to_datetime(["2025-01-10", "2025-02-14", "2026-01-08", "2026-02-03", "2026-02-21", "2026-03-05"]),
    "category": ["Tea", "Kitchen", "Tea", "Tea", "Kitchen", "Tea"],
    "city": ["Pune", "Pune", "Pune", "Delhi", "Pune", "Delhi"],
    "amount": [400, 250, 500, 300, 280, 650],
})
total = sales["amount"].sum()
orders = len(sales)
print("Total Sales", total, "| Orders", orders, "| Avg Order", round(total / orders, 1))
print("Tea Sales", sales.loc[sales["category"] == "Tea", "amount"].sum())

y26 = sales[sales["date"].dt.year == 2026]
ytd_feb = y26[y26["date"] <= "2026-02-28"]["amount"].sum()
ly_feb = sales[(sales["date"].dt.year == 2025) & (sales["date"] <= "2025-02-28")]["amount"].sum()
print("Sales YTD (to Feb) 2026:", ytd_feb, "| same period 2025:", ly_feb, "| growth:", f"{(ytd_feb - ly_feb) / ly_feb:.0%}")
```

Output:

```text
Total Sales 2380 | Orders 6 | Avg Order 396.7
Tea Sales 1850
Sales YTD (to Feb) 2026: 1080 | same period 2025: 650 | growth: 66%
```

## Filter context, step by step

::walk python | What a matrix cell sees
rows = [("Pune", "Tea", 500), ("Delhi", "Tea", 300), ("Pune", "Kitchen", 280), ("Delhi", "Tea", 650)]
cell_filters = {"city": "Delhi", "category": "Tea"}
visible = [amt for city, cat, amt in rows if city == cell_filters["city"] and cat == cell_filters["category"]]
total_sales = sum(visible)
print(visible, total_sales)
::notes
1: The Sales table (city, category, amount). // vars: rows=4 sales
2: A matrix visual's cell for the row "Delhi" and column "Tea" applies these filters automatically. // deep: Slicers, page filters and the visual's own rows and columns all add to the filter context. CALCULATE is the function that changes it, for example to ignore a filter (ALL) or add one. // vars: cell_filters={'city': 'Delhi', 'category': 'Tea'}
3: Only rows passing every filter are visible to the measure. // vars: visible=[300, 650]
4: SUM runs on what's visible. // vars: total_sales=950
5: Same measure, different cell, different answer. That's filter context. // out: [300, 650] 950
::end

## CALCULATE: the most important function

`CALCULATE(expression, filters…)` evaluates an expression with modified filters:

```dax
Share of Total = DIVIDE ( [Total Sales], CALCULATE ( [Total Sales], ALL ( Stores ) ) )
```

`ALL(Stores)` removes the city filter for the denominator, so each city shows its share of the grand total.

```python
by_city = sales.groupby("city")["amount"].sum()
print((by_city / by_city.sum()).round(2).to_dict())
```

Output:

```text
{'Delhi': 0.4, 'Pune': 0.6}
```

::tip Name measures clearly, put them in a dedicated "Measures" table, and format them (₹, %, thousands) once in the measure settings. Your report will be easier to build and to hand over.

## Summary

- Use **measures** (not calculated columns) for totals, averages and ratios.
- Core DAX: `SUM`, `COUNTROWS`, `DIVIDE`, `CALCULATE`, `ALL`, `TOTALYTD`, `SAMEPERIODLASTYEAR`.
- Every visual cell has a **filter context**; measures respect it automatically.
- `CALCULATE` changes the filter context.
