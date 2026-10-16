---
title: SQL GROUP BY
slug: sql-group-by
subject: sql
lesson: 10
difficulty: intermediate
excerpt: Calculate totals per category, per customer and per month with GROUP BY, filter groups with HAVING, and understand the full order in which a query runs.
---

Aggregate functions give you one answer for the whole table. `GROUP BY` gives you one answer **per group**: products per category, orders per customer, sales per month. It's the heart of every report and dashboard.

## Grouping by one column

```sql
SELECT category, COUNT(*) AS products
FROM products
GROUP BY category;
```

| category | products |
|---|---|
| accessories | 1 |
| bags | 1 |
| electronics | 3 |
| stationery | 2 |

How it works:

1. The database puts rows with the same `category` into groups.
2. It runs `COUNT(*)` separately for each group.
3. It returns one row per group.

## Several aggregates per group

```sql
SELECT category,
       COUNT(*)             AS products,
       SUM(stock)           AS units,
       ROUND(AVG(price), 2) AS avg_price,
       MAX(price)           AS top_price
FROM products
GROUP BY category
ORDER BY units DESC;
```

| category | products | units | avg_price | top_price |
|---|---|---|---|---|
| electronics | 3 | 83 | 899.0 | 1499 |
| stationery | 2 | 80 | 185.0 | 250 |
| accessories | 1 | 40 | 350.0 | 350 |
| bags | 1 | 12 | 1899.0 | 1899 |

## The rule for SELECT with GROUP BY

Every column in SELECT must either be **in the GROUP BY** or **inside an aggregate function**.

```sql
-- Wrong: which product name should represent the whole category?
-- SELECT category, name, COUNT(*) FROM products GROUP BY category;
```

Each group has several names, so there's no single answer. PostgreSQL and strict MySQL give an error; SQLite silently picks one, which is misleading. Group by the columns you want to show.

## Grouping by several columns

```sql
SELECT customer_id, status, COUNT(*) AS orders
FROM orders
GROUP BY customer_id, status
ORDER BY customer_id;
```

| customer_id | status | orders |
|---|---|---|
| 1 | delivered | 1 |
| 1 | shipped | 1 |
| 2 | delivered | 1 |
| 3 | pending | 1 |
| 4 | delivered | 1 |

One row per unique **combination** of customer and status.

## Grouping order items

```sql
SELECT order_id,
       COUNT(*) AS lines,
       SUM(qty) AS units
FROM order_items
GROUP BY order_id;
```

| order_id | lines | units |
|---|---|---|
| 1 | 2 | 4 |
| 2 | 1 | 1 |
| 3 | 1 | 2 |
| 4 | 2 | 6 |
| 5 | 1 | 1 |

To add order **totals in rupees**, you need prices from the products table. That's a JOIN, coming in the next lesson.

## Grouping by a calculated value

```sql
-- SQLite: orders per month
SELECT STRFTIME('%Y-%m', order_date) AS month, COUNT(*) AS orders
FROM orders
GROUP BY month;

-- MySQL:      GROUP BY DATE_FORMAT(order_date, '%Y-%m')
-- PostgreSQL: GROUP BY TO_CHAR(order_date, 'YYYY-MM')
```

```sql
SELECT CASE WHEN price < 500 THEN 'under 500' ELSE '500 and up' END AS band,
       COUNT(*) AS products
FROM products
GROUP BY band;
```

Most databases (MySQL, PostgreSQL, SQLite) let you GROUP BY a SELECT alias; in others, repeat the expression.

## WHERE vs HAVING

- `WHERE` filters **rows before** grouping.
- `HAVING` filters **groups after** aggregating.

```sql
-- Categories with more than one product
SELECT category, COUNT(*) AS products
FROM products
GROUP BY category
HAVING COUNT(*) > 1;
```

electronics (3) and stationery (2).

You can't use WHERE here, because the count doesn't exist until after grouping.

Both together:

```sql
-- Among products in stock, categories whose average price is over 300
SELECT category, ROUND(AVG(price), 2) AS avg_price
FROM products
WHERE stock > 0                 -- rows: drop out-of-stock products first
GROUP BY category
HAVING AVG(price) > 300         -- groups: keep expensive categories
ORDER BY avg_price DESC;
```

| category | avg_price |
|---|---|
| bags | 1899.0 |
| electronics | 599.0 |
| accessories | 350.0 |

Electronics averages 599 here, not 899, because the out-of-stock headphones were removed by WHERE **before** grouping.

## Customers with more than one order

```sql
SELECT customer_id, COUNT(*) AS orders
FROM orders
GROUP BY customer_id
HAVING COUNT(*) >= 2;
```

Customer 1 (Asha) with 2 orders. A classic "loyal customers" query.

## The full logical order of a query

You write clauses in this order:

```sql
SELECT … FROM … WHERE … GROUP BY … HAVING … ORDER BY … LIMIT …
```

The database logically processes them in this order:

1. **FROM**: get the rows (and join tables).
2. **WHERE**: filter rows.
3. **GROUP BY**: form groups.
4. **HAVING**: filter groups.
5. **SELECT**: calculate output columns and aliases.
6. **ORDER BY**: sort.
7. **LIMIT**: cut.

Knowing this order explains most "why doesn't this work?" moments, for example why WHERE can't use an aggregate.

## Common mistakes

- **Selecting a column that isn't grouped or aggregated.**
- **Using WHERE for aggregate conditions** (`WHERE COUNT(*) > 1`); use HAVING.
- **Using HAVING for row conditions** that belong in WHERE; it works but is slower and less clear.
- **Forgetting ORDER BY**: grouped results aren't guaranteed to be sorted.

## Exercise

1. Count customers per city, most customers first.
2. Show the total stock and number of products per category, only for categories with at least 50 units.
3. Count orders per status.
4. In `order_items`, find products (by `product_id`) that were ordered more than once in total quantity.
5. Count how many products fall into 'budget' (<300), 'mid' (300–999) and 'premium' (1000+) bands.

## Summary

- `GROUP BY` makes one result row per group; aggregates are calculated per group.
- Every selected column must be grouped or aggregated.
- `WHERE` filters rows before grouping; `HAVING` filters groups after.
- Logical order: FROM → WHERE → GROUP BY → HAVING → SELECT → ORDER BY → LIMIT.

Next, you'll combine tables with INNER JOIN.
