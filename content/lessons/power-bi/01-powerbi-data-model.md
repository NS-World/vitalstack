---
title: "Power BI: Connecting Data and Building the Data Model"
slug: powerbi-data-model
subject: power-bi
lesson: 1
difficulty: beginner
excerpt: Get started with Power BI Desktop: connect to Excel or SQL, clean data in Power Query, and build a star schema with fact and dimension tables and the right relationships.
---

Power BI is Microsoft's dashboard tool, used in a huge number of companies to turn spreadsheets and databases into interactive reports. Data analyst job posts in India list it constantly. The secret to good Power BI reports isn't fancy charts; it's a clean **data model**.

::scene From messy Excel to a clean model
📊 | Excel chaos | Twelve monthly sales sheets, slightly different columns in each. | bugsy
🔌 | Get Data | Power BI connects to Excel, CSV, SQL Server, MySQL, web pages and many more sources. | chip
🧽 | Power Query | Clean once: fix types, remove blanks, combine the twelve sheets. Saved as repeatable steps. | dev
⭐ | Star schema | One Sales fact table in the middle, Product, Date and Store tables around it. | byte
::end

## The Power BI workflow

1. **Get Data:** Home → Get Data → Excel / SQL Server / Web….
2. **Transform (Power Query):** clean and reshape. Every step is recorded and re-runs on refresh.
3. **Model:** create relationships between tables, add measures (lesson 2).
4. **Visualise:** drag fields onto charts, add slicers (lesson 3).
5. **Publish:** share to the Power BI Service so others can view it in a browser; schedule refreshes.

Power BI Desktop is free for Windows. Sharing reports with colleagues generally needs a Pro or similar licence.

## Facts and dimensions: the star schema

- A **fact table** records events with numbers: each sale with quantity and amount. Many rows.
- **Dimension tables** describe things: products, stores, dates. Few rows, many descriptive columns.
- Each dimension connects to the fact table with a **one-to-many** relationship (one product, many sales).

Here's the same idea in pandas, which also shows why it works:

```python
import pandas as pd

products = pd.DataFrame({"product_id": [1, 2, 3], "product": ["Masala chai", "Green tea", "Steel mug"],
                         "category": ["Tea", "Tea", "Kitchen"]})
stores = pd.DataFrame({"store_id": [10, 20], "city": ["Pune", "Delhi"]})
sales = pd.DataFrame({"date": pd.to_datetime(["2026-01-05", "2026-01-05", "2026-02-11", "2026-02-12", "2026-02-20"]),
                      "product_id": [1, 3, 1, 2, 1], "store_id": [10, 10, 20, 20, 10],
                      "qty": [3, 1, 5, 2, 4], "amount": [360, 250, 600, 300, 480]})

model = sales.merge(products, on="product_id").merge(stores, on="store_id")
print(model.groupby(["category", "city"])["amount"].sum())
```

Output:

```text
category  city
Kitchen   Pune     250
Tea       Delhi    900
          Pune     840
Name: amount, dtype: int64
```

In Power BI you don't write the merge: you draw the relationship once in **Model view**, and every visual automatically "walks" from Sales to Product and Store.

## A proper date table

Time intelligence (month-to-date, last year) needs a **date table** with one row per day, marked as a date table:

::walk python | Building a date dimension
import pandas as pd
dates = pd.date_range("2026-01-01", "2026-12-31", freq="D")
cal = pd.DataFrame({"date": dates})
cal["month"] = cal["date"].dt.strftime("%b")
cal["quarter"] = "Q" + cal["date"].dt.quarter.astype(str)
print(len(cal), cal.iloc[45].tolist()[1:])
::notes
1: pandas for the date logic.
2: Every single day of 2026: no gaps, even days with no sales. // deep: Time-intelligence functions like TOTALYTD and SAMEPERIODLASTYEAR need a continuous date column. Gaps break them silently. // vars: dates=365 days
3: One row per day. // vars: cal=365 rows
4: A month name column for chart labels. // vars: month=Jan, Feb, …
5: A quarter column like "Q1". // vars: quarter=Q1…Q4
6: 365 rows. Day 46 (15 February) is in Feb, Q1. In Power BI you'd build this with DAX's CALENDAR function or Power Query. // out: 365 ['Feb', 'Q1']
::end

## Power Query tips

- Set correct **data types** first (dates as Date, amounts as Decimal).
- **Remove** columns you don't need: smaller models are faster.
- Use **Append** to stack similar tables (12 monthly sheets) and **Merge** to join.
- **Unpivot** wide tables (Jan, Feb, Mar columns) into long ones (Month, Value): visuals and DAX prefer long data.

::tip Avoid one giant flat table with everything repeated. A star schema is smaller, faster and makes measures much simpler. It's the single most important Power BI skill.

## Summary

- Workflow: Get Data → Power Query → Model → Visualise → Publish.
- Star schema: one fact table, several dimension tables, one-to-many relationships.
- Always add a continuous date table for time calculations.
- Clean in Power Query once; it re-runs on every refresh.
