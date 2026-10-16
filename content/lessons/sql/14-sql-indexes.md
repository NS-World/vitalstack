---
title: SQL Indexes
slug: sql-indexes
subject: sql
lesson: 14
difficulty: intermediate
excerpt: How indexes make queries fast, when to create them, composite indexes and column order, reading EXPLAIN plans, and the costs of too many indexes.
---

With 7 products, every query is instant. With 7 million, a query that scans every row can take seconds, and a busy website runs thousands of queries a minute. **Indexes** let the database find rows without reading the whole table. They're the single biggest tool for database performance.

## The book index analogy

To find "foreign keys" in a 500-page book, you don't read every page. You check the index at the back: "foreign keys … page 312". A database index works the same way: a separate, **sorted** structure that maps column values to the rows that contain them.

Without an index, the database does a **full table scan**: it checks every row. With one, it jumps almost straight to the matches, even in a table with millions of rows.

## Creating an index

```sql
CREATE INDEX idx_orders_customer_id ON orders (customer_id);
```

- `idx_orders_customer_id` is the index's name (a common convention: `idx_table_column`).
- `ON orders (customer_id)` says which table and column.

Queries filtering, joining or sorting on `customer_id` can now use it:

```sql
SELECT * FROM orders WHERE customer_id = 1;
```

You don't change your queries; the database decides automatically when an index helps.

## Indexes you already have

- Every **PRIMARY KEY** is indexed automatically.
- Every **UNIQUE** constraint creates an index (that's how the database checks uniqueness quickly).
- **Foreign keys** are indexed automatically in MySQL (InnoDB), but **not** in PostgreSQL or SQLite. Index them yourself there, because joins use them constantly.

## Seeing the query plan with EXPLAIN

`EXPLAIN` shows how the database will run a query:

```sql
-- SQLite:
EXPLAIN QUERY PLAN SELECT * FROM orders WHERE status = 'pending';
-- SCAN orders                      ← full table scan

CREATE INDEX idx_orders_status ON orders (status);

EXPLAIN QUERY PLAN SELECT * FROM orders WHERE status = 'pending';
-- SEARCH orders USING INDEX idx_orders_status (status=?)   ← uses the index
```

In MySQL and PostgreSQL, write `EXPLAIN SELECT …`. Look for "full scan" / "Seq Scan" / "type: ALL" on big tables: that's where indexes help. PostgreSQL's `EXPLAIN ANALYZE` also runs the query and shows real timings.

(On tiny tables, databases often choose a scan anyway, because it's faster than using the index.)

## What to index

Index columns that appear often in:

- **WHERE** filters: `WHERE email = ?`, `WHERE status = ?`.
- **JOIN** conditions: foreign keys like `orders.customer_id`, `order_items.product_id`.
- **ORDER BY**: `ORDER BY created_at DESC` on large tables (latest posts, newest orders).

Start from real slow queries. Most databases can log slow queries (MySQL's slow query log, PostgreSQL's `pg_stat_statements`).

## Composite indexes and column order

An index can cover several columns:

```sql
CREATE INDEX idx_orders_customer_date ON orders (customer_id, order_date);
```

It's sorted by `customer_id` first, then by `order_date` within each customer, like a phone book sorted by surname, then first name. It helps:

```sql
SELECT * FROM orders WHERE customer_id = 1;                                -- yes (first column)
SELECT * FROM orders WHERE customer_id = 1 AND order_date >= '2024-05-05'; -- yes, both columns
SELECT * FROM orders WHERE customer_id = 1 ORDER BY order_date DESC;       -- yes, even the sort
SELECT * FROM orders WHERE order_date >= '2024-05-05';                     -- no (not the first column)
```

This is the **leftmost prefix** rule: an index can be used for its first column, or first two, and so on, but not for a later column on its own. Put the column used for equality (`=`) first and range or sort columns after it.

## When indexes can't be used

```sql
-- Function on the column: the index on email can't be used
SELECT * FROM customers WHERE LOWER(email) = 'asha@example.com';

-- Leading wildcard: like searching a phone book for names ending in "-sharma"
SELECT * FROM products WHERE name LIKE '%lamp';

-- These CAN use an index:
SELECT * FROM customers WHERE email = 'asha@example.com';
SELECT * FROM products WHERE name LIKE 'Desk%';
```

Store data in a consistent form (e.g. emails lower-cased on insert) so you can compare directly. PostgreSQL and MySQL 8 also support **expression indexes**, such as an index on `LOWER(email)`. For searching words inside text, use full-text search.

## Unique indexes

```sql
CREATE UNIQUE INDEX idx_customers_email ON customers (email);
```

An index that also enforces uniqueness, the same as a UNIQUE constraint.

## The cost of indexes

Indexes aren't free:

- **Slower writes:** every INSERT, UPDATE and DELETE must also update every index on the table.
- **Disk space:** each index is a copy of its columns, sorted.
- **Maintenance:** unused indexes still cost on every write.

So don't index every column. Index what your queries actually need, and remove indexes nobody uses.

| Good candidates | Poor candidates |
|---|---|
| Foreign keys used in joins | Columns never used in WHERE / JOIN / ORDER BY |
| Columns in frequent WHERE filters | Tiny tables |
| Columns used for sorting large lists | Columns with only 2–3 values, unless combined in a composite index |
| Unique lookups like email or slug | Tables with constant heavy writes and rare reads |

## Indexes in WordPress

WordPress's `wp_posts` table has indexes on `post_name` (slug lookups), on `(post_type, post_status, post_date, ID)` for listing posts, and on `post_author`. That's why loading a post by its URL stays fast even with thousands of posts. Plugins that search unindexed meta values (`wp_postmeta.meta_value`) are a common cause of slow WordPress sites.

## Removing an index

```sql
DROP INDEX idx_orders_status;              -- SQLite / PostgreSQL
-- DROP INDEX idx_orders_status ON orders; -- MySQL
```

## Common mistakes

- **No index on foreign keys** in PostgreSQL/SQLite, making joins slow.
- **Indexing everything**, slowing down every write.
- **Wrong column order** in composite indexes.
- **Wrapping indexed columns in functions** in WHERE.
- **Guessing instead of measuring** with EXPLAIN.

## Exercise

1. Run `EXPLAIN QUERY PLAN` (SQLite) or `EXPLAIN` on `SELECT * FROM order_items WHERE product_id = 1`. Then create an index and compare.
2. Create a composite index on `orders (status, order_date)`. Which of these could use it: filter by status; by status and date; by date only?
3. List the indexes you'd add to the shop database, and explain each one.
4. Rewrite `WHERE LOWER(city) = 'pune'` so it could use an index on `city`.

## Summary

- Indexes are sorted lookup structures that avoid full table scans.
- Primary keys and UNIQUE columns are indexed automatically; index foreign keys yourself where needed.
- Composite indexes follow the leftmost prefix rule; order the columns carefully.
- Indexes speed up reads but slow down writes; measure with EXPLAIN.

Next, you'll design and query a complete library database in the SQL project.
