---
title: SQL JOINs (INNER JOIN)
slug: sql-inner-join
subject: sql
lesson: 11
difficulty: intermediate
excerpt: Combine rows from related tables with INNER JOIN, use table aliases, join three or four tables, and build real reports like order totals per customer.
---

Our data is split across tables: orders store a `customer_id`, not the customer's name; order items store a `product_id`, not the price. A **JOIN** combines rows from related tables so you can see everything together. JOINs are what make relational databases powerful.

## Why data is split

If every order row repeated the customer's name, email and city, then changing a customer's email would mean updating every one of their orders, and a missed one would leave inconsistent data. Instead, each fact is stored **once**, and tables point to each other with ids. This idea is called **normalisation**. JOINs put the pieces back together when you query.

## Your first INNER JOIN

```sql
SELECT orders.id, orders.order_date, customers.name
FROM orders
INNER JOIN customers ON orders.customer_id = customers.id;
```

| id | order_date | name |
|---|---|---|
| 1 | 2024-05-01 | Asha |
| 2 | 2024-05-03 | Rohan |
| 3 | 2024-05-10 | Asha |
| 4 | 2024-05-12 | Meera |
| 5 | 2024-05-15 | Kabir |

- `FROM orders` starts with the orders table.
- `INNER JOIN customers` adds the customers table.
- `ON orders.customer_id = customers.id` says how rows match: an order belongs to the customer whose id equals the order's `customer_id`.
- `table.column` says which table a column comes from. It's required when both tables have a column with the same name (both have `id`).

The word `INNER` is optional: a plain `JOIN` means INNER JOIN.

## What "inner" means

An INNER JOIN returns only rows that have a match **in both tables**. Sara has no orders, so she doesn't appear. The desk lamp has never been ordered, so it won't appear in a join with order items. To include rows without matches, you'll use LEFT JOIN in the next lesson.

## Table aliases

Writing full table names gets long. Give each table a short alias:

```sql
SELECT o.id, o.order_date, o.status, c.name, c.city
FROM orders AS o
JOIN customers AS c ON o.customer_id = c.id
WHERE c.city = 'Pune'
ORDER BY o.order_date;
```

Asha's two orders and Kabir's one. (`AS` is optional for table aliases: `FROM orders o`.) Aliases like `o` and `c` are standard practice in every real codebase.

## Joining three tables

Which products are in each order? `order_items` connects orders and products:

```sql
SELECT oi.order_id, p.name AS product, oi.qty, p.price
FROM order_items oi
JOIN products p ON oi.product_id = p.id
ORDER BY oi.order_id;
```

| order_id | product | qty | price |
|---|---|---|---|
| 1 | Notebook | 3 | 120 |
| 1 | Pen set | 1 | 250 |
| 2 | Backpack | 1 | 1899 |
| 3 | USB cable | 2 | 299 |
| 4 | Notebook | 5 | 120 |
| 4 | Water bottle | 1 | 350 |
| 5 | Headphones | 1 | 1499 |

Add the customer with a third join:

```sql
SELECT c.name AS customer, o.id AS order_id, p.name AS product, oi.qty
FROM orders o
JOIN customers c    ON o.customer_id = c.id
JOIN order_items oi ON oi.order_id = o.id
JOIN products p     ON oi.product_id = p.id
ORDER BY c.name, o.id;
```

Each JOIN adds one table and one matching rule. Read it as a chain: customers ← orders → order_items → products.

## JOIN + GROUP BY: real reports

Now you can calculate what you couldn't before: the value of each order.

```sql
SELECT o.id AS order_id,
       SUM(oi.qty * p.price) AS order_total
FROM orders o
JOIN order_items oi ON oi.order_id = o.id
JOIN products p     ON p.id = oi.product_id
GROUP BY o.id
ORDER BY order_total DESC;
```

| order_id | order_total |
|---|---|
| 2 | 1899 |
| 5 | 1499 |
| 4 | 950 |
| 1 | 610 |
| 3 | 598 |

Total spent per customer:

```sql
SELECT c.name,
       COUNT(DISTINCT o.id)  AS orders,
       SUM(oi.qty * p.price) AS total_spent
FROM customers c
JOIN orders o       ON o.customer_id = c.id
JOIN order_items oi ON oi.order_id = o.id
JOIN products p     ON p.id = oi.product_id
GROUP BY c.id, c.name
ORDER BY total_spent DESC;
```

| name | orders | total_spent |
|---|---|---|
| Rohan | 1 | 1899 |
| Kabir | 1 | 1499 |
| Asha | 2 | 1208 |
| Meera | 1 | 950 |

Note `COUNT(DISTINCT o.id)`: after joining items, Asha's first order appears on two rows (two products), so a plain `COUNT(*)` would count 3 "orders" for her instead of 2. Watch for this **row multiplication** whenever you join a one-to-many relationship and then aggregate.

Best-selling products by units:

```sql
SELECT p.name, SUM(oi.qty) AS units_sold
FROM products p
JOIN order_items oi ON oi.product_id = p.id
GROUP BY p.id, p.name
ORDER BY units_sold DESC;
```

The notebook leads with 8 units.

## JOIN with extra conditions

Filter joined results with WHERE as usual:

```sql
SELECT c.name, o.id, o.status
FROM customers c
JOIN orders o ON o.customer_id = c.id
WHERE o.status <> 'delivered';
```

Asha (order 3, shipped) and Meera (order 4, pending).

## Ambiguous columns

```sql
-- SELECT id, name FROM orders JOIN customers ON customer_id = customers.id;
-- Error: ambiguous column name: id
```

Both tables have `id`, so the database doesn't know which you mean. Always prefix columns with the table alias in joins, even when not strictly required: it makes queries clearer.

## Common mistakes

- **Missing or wrong ON condition**: joining `o.id = c.id` instead of `o.customer_id = c.id` gives nonsense that looks plausible.
- **Forgetting a join condition entirely**, which matches every row with every row (a "Cartesian product": 5 orders × 5 customers = 25 rows).
- **Counting rows after a one-to-many join**; use `COUNT(DISTINCT …)`.
- **Ambiguous column names** without table prefixes.

## Exercise

1. List every order with the customer's name and city.
2. List all products bought by Asha.
3. Show each order's id, date, customer name and number of items.
4. Find the total revenue from delivered orders only.
5. Find each city's total sales.

## Summary

- `JOIN table ON condition` combines rows from related tables.
- INNER JOIN keeps only rows with a match in both tables.
- Use short aliases and prefix every column in joins.
- JOIN + GROUP BY produces real reports; watch for row multiplication when counting.

Next, you'll keep rows without matches using LEFT JOIN.
