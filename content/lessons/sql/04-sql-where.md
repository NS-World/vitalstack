---
title: SQL WHERE
slug: sql-where
subject: sql
lesson: 4
difficulty: beginner
excerpt: Filter rows with WHERE using comparison operators, AND, OR and NOT, IN, BETWEEN, LIKE patterns and IS NULL, and learn why NULL needs special handling.
---

Tables can have millions of rows, and you usually want only a few: orders from today, products under ₹500, customers in Pune. The `WHERE` clause filters rows so only those matching a condition are returned.

## Basic WHERE

```sql
SELECT name, city
FROM customers
WHERE city = 'Pune';
```

| name | city |
|---|---|
| Asha | Pune |
| Kabir | Pune |

The condition is checked for every row; only rows where it's true are kept.

## Comparison operators

| Operator | Meaning |
|---|---|
| `=` | Equal |
| `<>` or `!=` | Not equal |
| `>` `<` | Greater / less than |
| `>=` `<=` | Greater / less than or equal |

```sql
SELECT name, price
FROM products
WHERE price < 500;
```

Returns Notebook (120), Pen set (250), USB cable (299) and Water bottle (350).

```sql
SELECT name, stock FROM products WHERE stock = 0;            -- out of stock: Headphones
SELECT id, status FROM orders WHERE status <> 'delivered';   -- orders 3 and 4
SELECT name, joined FROM customers WHERE joined >= '2024-03-01'; -- Kabir and Sara
```

Dates in `'YYYY-MM-DD'` format compare correctly.

## AND, OR, NOT

```sql
-- Both conditions must be true
SELECT name, category, price
FROM products
WHERE category = 'electronics' AND price < 1000;
```

Returns USB cable and Desk lamp.

```sql
-- At least one condition must be true
SELECT name, city
FROM customers
WHERE city = 'Pune' OR city = 'Mumbai';
```

Returns Asha, Kabir and Sara.

```sql
-- NOT reverses a condition
SELECT name, category FROM products WHERE NOT category = 'electronics';
```

### Use brackets with AND and OR

`AND` is evaluated before `OR`, which can give surprising results:

```sql
-- Intended: electronics or bags, but only under 1000
SELECT name, category, price
FROM products
WHERE category = 'electronics' OR category = 'bags' AND price < 1000;
-- Wrong! Read as: electronics OR (bags AND under 1000) → includes Headphones at 1499

SELECT name, category, price
FROM products
WHERE (category = 'electronics' OR category = 'bags') AND price < 1000;
-- Correct: USB cable and Desk lamp
```

Whenever you mix AND and OR, add brackets.

## IN: match any value in a list

```sql
SELECT name, city
FROM customers
WHERE city IN ('Pune', 'Mumbai', 'Delhi');
```

Shorter and clearer than several ORs. `NOT IN` excludes the list.

## BETWEEN: ranges

```sql
SELECT name, price
FROM products
WHERE price BETWEEN 250 AND 900;
```

Returns Pen set (250), USB cable (299), Water bottle (350) and Desk lamp (899). `BETWEEN` **includes** both ends.

```sql
SELECT id, order_date
FROM orders
WHERE order_date BETWEEN '2024-05-01' AND '2024-05-10';
```

Orders 1, 2 and 3. Careful with date-**time** columns: `'2024-05-10'` means midnight at the start of that day, so later times on the 10th are excluded. For timestamps, use `>= '2024-05-01' AND < '2024-05-11'`.

## LIKE: text patterns

```sql
SELECT name FROM products WHERE name LIKE 'Pen%';     -- starts with "Pen"
SELECT name FROM products WHERE name LIKE '%lamp';    -- ends with "lamp"
SELECT name FROM products WHERE name LIKE '%o%';      -- contains "o"
SELECT name FROM customers WHERE name LIKE '_ara';    -- 4 letters ending "ara": Sara
```

- `%` matches any number of characters (including none).
- `_` matches exactly one character.

Case sensitivity of LIKE differs: MySQL and SQLite usually ignore case for English letters; PostgreSQL is case-sensitive (use `ILIKE` there to ignore case).

`LIKE '%word%'` can't use an index (lesson 14), so it's slow on huge tables. Large sites use full-text search instead.

## NULL needs IS NULL

```sql
SELECT name, email FROM customers WHERE email = NULL;   -- returns nothing!
SELECT name, email FROM customers WHERE email IS NULL;  -- Sara
SELECT name FROM customers WHERE email IS NOT NULL;     -- the other four
```

`NULL` means "unknown", and comparing anything with unknown gives unknown, never true, not even `NULL = NULL`. Always use `IS NULL` / `IS NOT NULL`.

This also affects `<>`: `WHERE city <> 'Pune'` does **not** return rows where city is NULL. Add `OR city IS NULL` if you want them.

## Filtering on calculated values

```sql
SELECT name, price, stock, price * stock AS stock_value
FROM products
WHERE price * stock > 10000;
```

Returns Backpack (22,788), USB cable (22,425) and Water bottle (14,000). In most databases you must repeat the expression in WHERE rather than use the alias `stock_value`, because WHERE runs before SELECT.

## Common mistakes

- **`= NULL`** instead of `IS NULL`.
- **Double quotes** around text: `WHERE city = "Pune"` fails in PostgreSQL.
- **Mixing AND/OR without brackets.**
- **Forgetting that BETWEEN includes both ends**, or that date-times on the end date are excluded.
- **Comparing numbers stored as text.**

## Exercise

1. Find all products in the stationery category.
2. Find products that cost more than ₹300 and have at least 10 in stock.
3. Find customers who joined in February 2024.
4. Find orders that are pending or shipped, using `IN`.
5. Find products whose name contains the letter "e" and that cost under ₹500.
6. Find customers without an email address.

## Summary

- `WHERE` keeps only rows that match a condition.
- Use comparison operators, `AND`, `OR`, `NOT` and brackets to build conditions.
- `IN`, `BETWEEN` and `LIKE` match lists, ranges and patterns.
- Test for missing values with `IS NULL`, never `= NULL`.

Next, you'll sort results with ORDER BY.
