---
title: SQL UPDATE
slug: sql-update
subject: sql
lesson: 7
difficulty: beginner
excerpt: Change existing rows with UPDATE and SET, always target rows with WHERE, update several columns and calculated values, and use transactions as a safety net.
---

Data changes all the time: a customer moves city, a price goes up, an order is shipped. `UPDATE` changes values in existing rows. It's simple to write, and also the statement most likely to cause an accident, so this lesson covers safety habits too.

## Basic UPDATE

```sql
UPDATE customers
SET city = 'Bengaluru'
WHERE id = 2;

SELECT name, city FROM customers WHERE id = 2;  -- Rohan, Bengaluru
```

- `UPDATE table` names the table.
- `SET column = value` gives the new value.
- `WHERE` chooses **which rows** to change.

## The golden rule: never forget WHERE

```sql
-- DON'T RUN THIS
-- UPDATE customers SET city = 'Bengaluru';
```

Without WHERE, **every row** in the table is updated. All your customers now live in Bengaluru, and there's no undo button. This mistake has hurt many real companies.

Safe habits:

1. **Write the WHERE clause first**, then the SET part.
2. **Run a SELECT with the same WHERE** before updating, to see exactly which rows will change:

```sql
SELECT * FROM products WHERE category = 'stationery';
-- Looks right? Now:
UPDATE products SET price = price * 1.10 WHERE category = 'stationery';
```

3. **Target rows by primary key** (`WHERE id = 2`) when you mean one specific row; names and emails can be duplicated or mistyped.

## Updating several columns

```sql
UPDATE products
SET price = 1299, stock = 25
WHERE id = 3;
```

Separate the assignments with commas.

## Calculated updates

The new value can use the current values:

```sql
-- Restock: add 10 to the headphones' stock
UPDATE products SET stock = stock + 10 WHERE id = 3;

-- 10% discount on all electronics
UPDATE products SET price = price * 0.90 WHERE category = 'electronics';

-- Text changes
UPDATE products SET name = UPPER(name) WHERE id = 8;
```

`stock = stock + 10` reads the value before the update, adds 10 and stores the result. For counters (likes, views, stock), this is safer than reading the value in your application and writing it back, because two users updating at once won't overwrite each other.

## Updating many rows on purpose

```sql
UPDATE orders
SET status = 'delivered'
WHERE status = 'shipped' AND order_date < '2024-05-20';
```

Most tools report how many rows were changed ("1 row affected"). If that number surprises you, investigate.

## Setting a value to NULL

```sql
UPDATE customers SET email = NULL WHERE id = 4;
```

Note: in SET, `=` assigns (so `= NULL` is correct here). It's only in **WHERE** that you need `IS NULL`.

## Conditional values with CASE

```sql
UPDATE products
SET category = CASE
  WHEN price >= 1000 THEN 'premium'
  ELSE category
END
WHERE category = 'electronics';
```

`CASE WHEN … THEN … ELSE … END` chooses a value per row, like an if/else.

## Updating using another table

Sometimes the new value depends on another table. A portable way uses a subquery (a query inside a query):

```sql
-- Mark customers who have at least one order as 'active'
ALTER TABLE customers ADD COLUMN status VARCHAR(20) DEFAULT 'new';

UPDATE customers
SET status = 'active'
WHERE id IN (SELECT customer_id FROM orders);

SELECT name, status FROM customers;
```

Everyone except Sara (and any customers you added without orders) becomes active. MySQL and PostgreSQL also have `UPDATE … JOIN` / `UPDATE … FROM` syntax for this.

## Transactions: an undo button

A **transaction** groups statements so they either all happen or none do. Until you `COMMIT`, you can `ROLLBACK`:

```sql
BEGIN;

UPDATE products SET price = price * 2;   -- oops, forgot WHERE!
SELECT name, price FROM products;        -- everything doubled

ROLLBACK;                                -- undo everything since BEGIN

SELECT name, price FROM products;        -- back to normal
```

When everything looks right, use `COMMIT;` instead of `ROLLBACK;` to save the changes permanently.

Transactions are essential for operations that must happen together, like moving money:

```sql
BEGIN;
-- UPDATE accounts SET balance = balance - 500 WHERE id = 1;
-- UPDATE accounts SET balance = balance + 500 WHERE id = 2;
COMMIT;
```

If anything fails between the two updates, a rollback ensures money is never lost or created. (Some tools and settings commit automatically after each statement unless you start a transaction with `BEGIN`.)

## Common mistakes

- **Forgetting WHERE.** Every row changes.
- **Using `IS NULL` in SET** or `= NULL` in WHERE (the opposite of correct).
- **Updating by name** when names aren't unique.
- **Read-modify-write in application code** for counters, instead of `SET x = x + 1`.
- **Big updates on live data without a transaction or backup.**

## Exercise

1. Change Meera's city to "Udaipur". Check the result with SELECT first and after.
2. Increase the price of all stationery by 5%.
3. Set the stock of the out-of-stock product to 20.
4. Inside a transaction, set every order to 'cancelled', look at the result, then roll back.
5. Mark order 4 as 'shipped'.

## Summary

- `UPDATE table SET column = value WHERE condition` changes existing rows.
- Without WHERE, every row changes: write WHERE first and test it with SELECT.
- Values can be calculated from current values (`stock = stock + 10`) or chosen with CASE.
- Transactions (`BEGIN`, `COMMIT`, `ROLLBACK`) make changes all-or-nothing and undoable.

Next, you'll remove rows with DELETE.
