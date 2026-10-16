---
title: SQL ORDER BY
slug: sql-order-by
subject: sql
lesson: 5
difficulty: beginner
excerpt: Sort query results with ORDER BY, ascending and descending, by several columns and by calculated values, handle NULLs, and get top-N results with LIMIT.
---

Without instructions, a database returns rows in whatever order is convenient for it, and that order can change. `ORDER BY` sorts the result exactly how you want: cheapest first, newest first, alphabetical.

## Sorting ascending

```sql
SELECT name, price
FROM products
ORDER BY price;
```

| name | price |
|---|---|
| Notebook | 120 |
| Pen set | 250 |
| USB cable | 299 |
| Water bottle | 350 |
| Desk lamp | 899 |
| Headphones | 1499 |
| Backpack | 1899 |

Ascending (smallest first, A→Z, oldest date first) is the default. You can write `ASC` to be explicit.

## Sorting descending

```sql
SELECT name, price
FROM products
ORDER BY price DESC;
```

`DESC` reverses the order: most expensive first. Common uses: newest posts first (`ORDER BY created_at DESC`), highest scores first.

## Sorting text and dates

```sql
SELECT name FROM customers ORDER BY name;                -- Asha, Kabir, Meera, Rohan, Sara
SELECT name, joined FROM customers ORDER BY joined DESC; -- newest members first: Sara first
```

Text is sorted alphabetically according to the database's **collation** (its rules for comparing text), which also decides whether uppercase and lowercase are treated the same.

## Sorting by several columns

```sql
SELECT name, city
FROM customers
ORDER BY city, name;
```

| name | city |
|---|---|
| Rohan | Delhi |
| Meera | Jaipur |
| Sara | Mumbai |
| Asha | Pune |
| Kabir | Pune |

The rows are sorted by city first; rows with the same city (Pune) are then sorted by name. Each column can have its own direction:

```sql
SELECT name, category, price
FROM products
ORDER BY category ASC, price DESC;
```

Categories A→Z, and inside each category, the most expensive product first.

## Sorting by a calculated value or alias

```sql
SELECT name, price, stock, price * stock AS stock_value
FROM products
ORDER BY stock_value DESC;
```

Unlike WHERE, ORDER BY runs **after** SELECT, so you can use the alias. Backpack (22,788) comes first.

You can also sort by an expression that isn't in the SELECT list:

```sql
SELECT name FROM products ORDER BY LENGTH(name);   -- shortest names first
```

## Sorting by column position (avoid)

```sql
SELECT name, price FROM products ORDER BY 2 DESC;  -- 2 = the second column (price)
```

This works but breaks silently if someone changes the column list. Use names.

## Top-N queries with LIMIT

Combine ORDER BY with LIMIT to get "the top N":

```sql
-- The 3 most expensive products
SELECT name, price
FROM products
ORDER BY price DESC
LIMIT 3;
```

Backpack, Headphones, Desk lamp.

```sql
-- The most recent order
SELECT id, order_date
FROM orders
ORDER BY order_date DESC
LIMIT 1;
```

Order 5 (2024-05-15).

### Pagination

```sql
-- Page 1: rows 1–3
SELECT name, price FROM products ORDER BY name LIMIT 3 OFFSET 0;
-- Page 2: rows 4–6
SELECT name, price FROM products ORDER BY name LIMIT 3 OFFSET 3;
```

Always include ORDER BY when paging, or the same row could appear on two pages. Make the order **unique** too: if two rows tie, add the id as a tie-breaker, e.g. `ORDER BY price DESC, id`.

## Where do NULLs go?

```sql
SELECT name, email FROM customers ORDER BY email;
```

Sara's NULL email appears **first** in MySQL and SQLite, but **last** in PostgreSQL (for ascending order). To control it:

```sql
-- PostgreSQL and SQLite 3.30+:
SELECT name, email FROM customers ORDER BY email NULLS LAST;

-- Works everywhere: sort by "is it null?" first
SELECT name, email
FROM customers
ORDER BY CASE WHEN email IS NULL THEN 1 ELSE 0 END, email;
```

(`CASE` works like if/else inside SQL; you'll see it again later.)

## Full query order so far

```sql
SELECT name, category, price
FROM products
WHERE stock > 0
ORDER BY price DESC
LIMIT 3;
```

The clauses must be written in this order: `SELECT → FROM → WHERE → ORDER BY → LIMIT`. Here: in-stock products only, most expensive first, top 3 → Backpack, Desk lamp, Water bottle.

## Common mistakes

- **Forgetting DESC** for "newest" or "highest" queries.
- **LIMIT without ORDER BY**, giving random-looking results.
- **Writing ORDER BY before WHERE.**
- **Ties in pagination** without a unique tie-breaker.

## Exercise

1. List all products from cheapest to most expensive.
2. List customers alphabetically by city, then by name in reverse.
3. Find the two products with the most stock.
4. Show the oldest order.
5. Show page 2 of products when sorted by name, with 2 products per page.

## Summary

- `ORDER BY column` sorts ascending; add `DESC` for descending.
- Sort by several columns, each with its own direction, and by aliases or expressions.
- Combine ORDER BY with LIMIT for top-N lists and pagination; add a tie-breaker.
- NULL placement differs between databases; control it with `NULLS LAST` or CASE.

Next, you'll add new rows with INSERT.
