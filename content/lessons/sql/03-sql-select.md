---
title: SQL SELECT
slug: sql-select
subject: sql
lesson: 3
difficulty: beginner
excerpt: Read data with SELECT: choose columns, avoid SELECT * in real code, rename columns with AS, calculate new columns, remove duplicates with DISTINCT and limit results.
---

`SELECT` is the statement you'll write more than any other. It reads data from one or more tables and returns a result table. This lesson covers the core of SELECT; the next lessons add filtering, sorting and grouping.

All examples use the practice shop database from the previous lesson.

## Selecting all columns

```sql
SELECT * FROM customers;
```

`*` means "every column". The result has all 5 customers with id, name, email, city and joined date.

`SELECT *` is great for a quick look at a table. In application code, list the columns you need instead: it's faster, clearer, and doesn't break if someone adds a column later.

## Selecting specific columns

```sql
SELECT name, city
FROM customers;
```

| name | city |
|---|---|
| Asha | Pune |
| Rohan | Delhi |
| Meera | Jaipur |
| Kabir | Pune |
| Sara | Mumbai |

Columns appear in the order you list them.

## Column aliases with AS

```sql
SELECT name AS customer, city AS location
FROM customers;
```

`AS` renames a column **in the result** (the table itself doesn't change). Aliases make results clearer and are essential for calculated columns. Use double quotes (or backticks in MySQL) if the alias has spaces: `AS "Customer name"`.

## Calculated columns

You can do maths and other operations right in the SELECT list:

```sql
SELECT name,
       price,
       price * 1.18 AS price_with_tax,
       stock,
       price * stock AS stock_value
FROM products;
```

| name | price | price_with_tax | stock | stock_value |
|---|---|---|---|---|
| Notebook | 120 | 141.6 | 50 | 6000 |
| Pen set | 250 | 295 | 30 | 7500 |
| Headphones | 1499 | 1768.82 | 0 | 0 |
| … | | | | |

The database calculates the new columns for every row. (How many decimals are shown depends on your database tool.)

### Joining text

```sql
-- Standard SQL, PostgreSQL and SQLite:
SELECT name || ' from ' || city AS label FROM customers;

-- MySQL:
-- SELECT CONCAT(name, ' from ', city) AS label FROM customers;
```

Result: "Asha from Pune", "Rohan from Delhi"… `CONCAT()` also works in PostgreSQL and newer SQLite versions.

## Removing duplicates with DISTINCT

```sql
SELECT city FROM customers;           -- Pune appears twice
SELECT DISTINCT city FROM customers;  -- each city once
```

| city |
|---|
| Pune |
| Delhi |
| Jaipur |
| Mumbai |

`DISTINCT` applies to the whole row of selected columns: `SELECT DISTINCT category, stock` returns each unique **combination**.

```sql
SELECT DISTINCT category FROM products;
```

This gives the four categories: stationery, electronics, bags, accessories.

## Limiting the number of rows

```sql
-- MySQL, PostgreSQL, SQLite:
SELECT name, price FROM products LIMIT 3;

-- Skip the first 3, then take 3 (page 2):
SELECT name, price FROM products LIMIT 3 OFFSET 3;

-- SQL Server uses: SELECT TOP 3 name, price FROM products;
```

`LIMIT` is how websites show "10 results per page". Without `ORDER BY` (lesson 5), which rows you get first is **not guaranteed**, so always combine LIMIT with ORDER BY in real queries.

## SELECT without a table

```sql
SELECT 2 + 3 AS answer, 'hello' AS word, CURRENT_DATE AS today;
```

Handy for testing expressions and functions.

## Formatting long queries

```sql
SELECT
  name,
  category,
  price
FROM products;
```

One column per line, keywords on their own lines: easy to read and easy to comment out a single column with `--` while testing.

## How a SELECT is processed

Although you write SELECT first, the database logically does this:

1. **FROM**: pick the table.
2. **WHERE**: filter rows (next lesson).
3. **SELECT**: choose and calculate columns.
4. **ORDER BY**: sort.
5. **LIMIT**: cut the result.

This explains why, for example, you can't use a column alias inside WHERE in most databases: WHERE runs before the alias is created.

## Common mistakes

- **Comma after the last column**: `SELECT name, city, FROM customers` is a syntax error.
- **Missing comma between columns**: `SELECT name city` treats `city` as an alias for `name`!
- **SELECT * in application code.**
- **Expecting a fixed order** without ORDER BY.

## Exercise

1. Select the name and price of every product.
2. Show each product's name and its price after a 10% discount, as `sale_price`.
3. List each distinct order status in the `orders` table.
4. Show the first 2 rows of `order_items`.
5. Show customer names with their city in one column like "Asha (Pune)".

## Summary

- `SELECT columns FROM table` reads data; `*` means all columns.
- `AS` renames result columns; you can calculate new columns with maths and text functions.
- `DISTINCT` removes duplicate rows; `LIMIT`/`OFFSET` restrict and page results.
- List columns explicitly in real code.

Next, you'll filter rows with WHERE.
