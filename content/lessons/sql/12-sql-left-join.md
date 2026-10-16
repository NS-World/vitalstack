---
title: SQL LEFT JOIN
slug: sql-left-join
subject: sql
lesson: 12
difficulty: intermediate
excerpt: Keep every row from the first table with LEFT JOIN, find rows without matches, count zero values correctly, and meet RIGHT, FULL and self joins.
---

An INNER JOIN drops rows that have no match. Often, that's exactly the data you want: customers who have **never** ordered, products **nobody** bought, posts with **zero** comments. **LEFT JOIN** keeps every row from the first table, matched or not.

## LEFT JOIN

```sql
SELECT c.name, o.id AS order_id, o.order_date
FROM customers c
LEFT JOIN orders o ON o.customer_id = c.id
ORDER BY c.name;
```

| name | order_id | order_date |
|---|---|---|
| Asha | 1 | 2024-05-01 |
| Asha | 3 | 2024-05-10 |
| Kabir | 5 | 2024-05-15 |
| Meera | 4 | 2024-05-12 |
| Rohan | 2 | 2024-05-03 |
| Sara | NULL | NULL |

Every customer from the **left** table (the one in FROM) appears. Where there's no matching order, the order columns are filled with `NULL`. Sara is now included.

Compare with INNER JOIN, which returned the same rows **except** Sara.

## Finding rows with no match

The most common use: find what's missing. Keep the LEFT JOIN, then filter on NULL in the right table:

```sql
-- Customers who have never ordered
SELECT c.name, c.city
FROM customers c
LEFT JOIN orders o ON o.customer_id = c.id
WHERE o.id IS NULL;
```

Sara (Mumbai). Perfect for a "send a welcome discount" email list.

```sql
-- Products that have never been sold
SELECT p.name, p.stock
FROM products p
LEFT JOIN order_items oi ON oi.product_id = p.id
WHERE oi.product_id IS NULL;
```

Only the desk lamp has never sold. Maybe it needs a better photo or a lower price.

## Counting with zeros

INNER JOIN reports leave out the zeros. LEFT JOIN includes them:

```sql
SELECT c.name, COUNT(o.id) AS orders
FROM customers c
LEFT JOIN orders o ON o.customer_id = c.id
GROUP BY c.id, c.name
ORDER BY orders DESC, c.name;
```

| name | orders |
|---|---|
| Asha | 2 |
| Kabir | 1 |
| Meera | 1 |
| Rohan | 1 |
| Sara | 0 |

Important: use `COUNT(o.id)`, not `COUNT(*)`. For Sara there's still one row (with NULLs), so `COUNT(*)` would say 1. `COUNT(o.id)` counts only non-NULL order ids, giving the correct 0.

## Sums with COALESCE

```sql
SELECT p.name,
       COALESCE(SUM(oi.qty), 0) AS units_sold
FROM products p
LEFT JOIN order_items oi ON oi.product_id = p.id
GROUP BY p.id, p.name
ORDER BY units_sold DESC;
```

The desk lamp shows 0 instead of NULL, because `SUM` of nothing is NULL and `COALESCE` turns it into 0.

## WHERE vs ON with LEFT JOIN

This is the trickiest part of LEFT JOIN. Compare:

```sql
-- A: condition in WHERE
SELECT c.name, o.id, o.status
FROM customers c
LEFT JOIN orders o ON o.customer_id = c.id
WHERE o.status = 'delivered';
```

```sql
-- B: condition in ON
SELECT c.name, o.id, o.status
FROM customers c
LEFT JOIN orders o ON o.customer_id = c.id AND o.status = 'delivered';
```

- **A** returns only Rohan, Asha and Kabir with their delivered orders. The WHERE runs after the join and removes rows where `o.status` is NULL, so Sara and Meera disappear: the LEFT JOIN has effectively become an INNER JOIN.
- **B** returns **all five** customers: those with a delivered order show it; Meera and Sara show NULLs.

Rule: conditions on the **right** table go in `ON` if you want to keep all left rows. Conditions on the **left** table go in `WHERE`.

## Order of tables matters

`A LEFT JOIN B` keeps all of A. `B LEFT JOIN A` keeps all of B. Start FROM the table whose rows you want to keep completely.

## RIGHT JOIN and FULL OUTER JOIN

```sql
-- RIGHT JOIN keeps all rows of the right table:
SELECT c.name, o.id
FROM orders o
RIGHT JOIN customers c ON o.customer_id = c.id;
-- Same result as: customers LEFT JOIN orders
```

`RIGHT JOIN` is just a LEFT JOIN written backwards. Most developers always use LEFT JOIN and order the tables accordingly; it's easier to read. (SQLite added RIGHT JOIN in version 3.39.)

`FULL OUTER JOIN` keeps all rows from **both** tables, with NULLs on either side where there's no match. It's supported by PostgreSQL, SQL Server and SQLite 3.39+, but not MySQL. It's mostly used for comparing two lists, e.g. reconciling two systems.

## Self join

A table can be joined to itself with two aliases. For example, customers from the same city:

```sql
SELECT a.name AS customer, b.name AS neighbour, a.city
FROM customers a
JOIN customers b ON a.city = b.city AND a.id < b.id;
```

Asha and Kabir (both Pune). `a.id < b.id` avoids pairing someone with themselves and listing each pair twice. Self joins are also used for hierarchies, like an `employees` table where `manager_id` points to another employee.

## Join types at a glance

| Join | Rows returned |
|---|---|
| `INNER JOIN` | Only matching pairs |
| `LEFT JOIN` | All left rows + matching right rows (NULL if none) |
| `RIGHT JOIN` | All right rows + matching left rows |
| `FULL OUTER JOIN` | All rows from both sides |
| `CROSS JOIN` | Every combination (rarely needed) |

## Common mistakes

- **Filtering the right table in WHERE**, which silently turns a LEFT JOIN into an INNER JOIN.
- **`COUNT(*)` instead of `COUNT(right.id)`**, giving 1 instead of 0.
- **Forgetting COALESCE**, showing NULL instead of 0 in reports.
- **Putting the "keep all" table on the wrong side.**

## Exercise

1. List all products with the total quantity sold, including 0 for unsold products.
2. Find customers who have never ordered.
3. Show every customer with the number of delivered orders (0 if none). Hint: put the status condition in ON.
4. List every order with the number of items in it, including orders with no items. (Insert an empty order first to test.)
5. Find pairs of products in the same category with a self join.

## Summary

- LEFT JOIN keeps every row from the left table and fills missing matches with NULL.
- `WHERE right.id IS NULL` finds rows without a match.
- Use `COUNT(right.id)` and `COALESCE(SUM(...), 0)` for correct zeros.
- Conditions on the right table belong in ON to keep all left rows.

Next, you'll protect your data with constraints and keys.
