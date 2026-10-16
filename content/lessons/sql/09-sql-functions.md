---
title: SQL Functions (COUNT, SUM, AVG)
slug: sql-functions
subject: sql
lesson: 9
difficulty: beginner
excerpt: Summarise data with aggregate functions COUNT, SUM, AVG, MIN and MAX, understand how they treat NULL, and use common text, number and date functions.
---

Often you don't want a list of rows but an answer: How many customers do we have? What's the average price? When was the last order? **Aggregate functions** calculate one value from many rows. SQL also has many **scalar functions** that work on one value at a time, for text, numbers and dates.

The examples use the original practice data from lesson 2. If you've changed it in the last lessons, re-run the setup script for matching results.

## COUNT

```sql
SELECT COUNT(*) AS customers FROM customers;   -- 5
```

`COUNT(*)` counts rows. Combine it with WHERE:

```sql
SELECT COUNT(*) AS pune_customers FROM customers WHERE city = 'Pune';  -- 2
```

### COUNT(column) ignores NULL

```sql
SELECT COUNT(*) AS all_rows,
       COUNT(email) AS with_email
FROM customers;
```

| all_rows | with_email |
|---|---|
| 5 | 4 |

`COUNT(column)` only counts rows where that column is **not NULL**. Sara has no email, so she isn't counted.

### COUNT(DISTINCT …)

```sql
SELECT COUNT(DISTINCT city) AS cities FROM customers;  -- 4 (Pune counted once)
SELECT COUNT(DISTINCT customer_id) AS buyers FROM orders;  -- 4 customers have ordered
```

## SUM

```sql
SELECT SUM(stock) AS units_in_stock FROM products;           -- 215
SELECT SUM(price * stock) AS inventory_value FROM products;  -- 79905
```

`SUM` adds up numbers. You can sum an expression, like price × stock for each row.

## AVG

```sql
SELECT AVG(price) AS average_price FROM products;   -- about 759.43
SELECT ROUND(AVG(price), 2) AS average_price FROM products;
```

`AVG` calculates the mean. `ROUND(value, 2)` keeps 2 decimals.

**NULLs are ignored** by SUM and AVG too. If some products had a NULL price, AVG would divide by the number of products **with** a price. Usually that's what you want, but be aware of it.

## MIN and MAX

```sql
SELECT MIN(price) AS cheapest, MAX(price) AS most_expensive FROM products;  -- 120, 1899
SELECT MIN(order_date) AS first_order, MAX(order_date) AS latest_order FROM orders;
```

MIN and MAX work on numbers, dates and text (alphabetically first/last).

## Several aggregates at once

```sql
SELECT COUNT(*)              AS products,
       SUM(stock)            AS units,
       ROUND(AVG(price), 2)  AS avg_price,
       MIN(price)            AS min_price,
       MAX(price)            AS max_price
FROM products
WHERE category = 'electronics';
```

| products | units | avg_price | min_price | max_price |
|---|---|---|---|---|
| 3 | 83 | 899.0 | 299 | 1499 |

A one-row summary of the electronics category.

### You can't mix aggregates with normal columns (yet)

```sql
SELECT name, MAX(price) FROM products;
```

This asks for one value (MAX) and a column with 7 values at the same time. PostgreSQL and strict MySQL return an error; SQLite returns a result but it's easy to misread. To find **which** product is the most expensive, sort and limit:

```sql
SELECT name, price FROM products ORDER BY price DESC LIMIT 1;
```

To get aggregates **per group** (per category, per city), you need GROUP BY, the next lesson.

## Useful text functions

```sql
SELECT UPPER(name)          AS upper_name,
       LOWER(email)         AS lower_email,
       LENGTH(name)         AS name_length,
       SUBSTR(name, 1, 3)   AS first_three,
       TRIM('  hi  ')       AS trimmed,
       REPLACE(city, 'Pune', 'Poona') AS old_name
FROM customers;
```

- `SUBSTR(text, start, length)` (SUBSTRING in some databases) starts counting at 1.
- Text joining: `||` in PostgreSQL/SQLite, `CONCAT()` in MySQL.

## Number functions

```sql
SELECT ROUND(1499 * 1.18, 2) AS with_tax,   -- 1768.82
       ROUND(17.5)           AS rounded,     -- 18
       ABS(-42)              AS absolute,    -- 42
       10 % 3                AS remainder;   -- 1 (MOD(10, 3) in some databases)
```

Also `CEIL`/`CEILING` and `FLOOR` in MySQL and PostgreSQL.

## Handling NULL with COALESCE

```sql
SELECT name, COALESCE(email, 'no email') AS contact
FROM customers;
```

`COALESCE(a, b, …)` returns the first value that isn't NULL. Perfect for display defaults, and for treating NULL as 0 in calculations: `COALESCE(discount, 0)`.

## Date functions

Date functions differ the most between databases:

| Task | MySQL | PostgreSQL | SQLite |
|---|---|---|---|
| Today | `CURDATE()` | `CURRENT_DATE` | `DATE('now')` |
| Now | `NOW()` | `NOW()` | `DATETIME('now')` |
| Year of a date | `YEAR(d)` | `EXTRACT(YEAR FROM d)` | `STRFTIME('%Y', d)` |
| Add 7 days | `d + INTERVAL 7 DAY` | `d + INTERVAL '7 days'` | `DATE(d, '+7 days')` |

```sql
-- SQLite: month of each order
SELECT id, order_date, STRFTIME('%m', order_date) AS month FROM orders;

-- Customers who joined in the last 30 days (MySQL):
-- SELECT name FROM customers WHERE joined >= CURDATE() - INTERVAL 30 DAY;
```

`CURRENT_DATE` and `CURRENT_TIMESTAMP` work in all three.

## CASE: if/else in a query

```sql
SELECT name, price,
       CASE
         WHEN price < 300 THEN 'budget'
         WHEN price < 1000 THEN 'mid-range'
         ELSE 'premium'
       END AS price_band
FROM products;
```

CASE is the Swiss Army knife of SQL: labels, custom sorting, and conditional counting:

```sql
SELECT COUNT(*) AS total,
       SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) AS delivered
FROM orders;   -- 5 total, 3 delivered
```

## Common mistakes

- **Expecting `COUNT(column)` to count NULLs.**
- **Mixing aggregates and plain columns** without GROUP BY.
- **Integer division**: in PostgreSQL and SQLite, `7 / 2` is `3`. Use `7.0 / 2` or `CAST` for decimals.
- **Copying date functions** between databases without checking.

## Exercise

1. Count how many orders have the status 'delivered'.
2. Find the total number of items sold (sum of `qty` in `order_items`).
3. Find the average price of stationery, rounded to 2 decimals.
4. Find the earliest and latest customer join dates.
5. Show every customer's name and email, with "–" where the email is missing.
6. Label each product's stock as 'out', 'low' (under 20) or 'ok'.

## Summary

- `COUNT`, `SUM`, `AVG`, `MIN` and `MAX` turn many rows into one value.
- `COUNT(*)` counts rows; `COUNT(column)`, SUM and AVG ignore NULLs.
- Text, number and date functions transform single values; date functions vary by database.
- `COALESCE` replaces NULLs; `CASE` adds if/else logic.

Next, you'll calculate aggregates per group with GROUP BY.
