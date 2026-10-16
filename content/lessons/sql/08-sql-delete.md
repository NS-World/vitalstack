---
title: SQL DELETE
slug: sql-delete
subject: sql
lesson: 8
difficulty: beginner
excerpt: Remove rows with DELETE safely, understand how foreign keys protect related data, compare DELETE, TRUNCATE and DROP, and when to use soft deletes instead.
---

`DELETE` removes rows from a table. Like UPDATE, it's easy to write and easy to get badly wrong, so you'll learn the safe way to use it, and when not to delete at all.

## Basic DELETE

```sql
DELETE FROM pune_customers
WHERE id = 4;

SELECT * FROM pune_customers;  -- only Asha is left
```

(`pune_customers` is the copy table from the INSERT lesson. If you don't have it, practise on any test table.)

- `DELETE FROM table` names the table.
- `WHERE` chooses which rows to remove.

## The golden rule, again: WHERE

```sql
-- DON'T RUN THIS ON REAL DATA
-- DELETE FROM customers;
```

Without WHERE, **every row is deleted**. The table stays, but it's empty. Use the same habits as UPDATE:

1. Write and run a **SELECT** with the WHERE clause first.
2. Check the number of rows.
3. Change `SELECT *` to `DELETE` and run it, ideally inside a transaction.

```sql
SELECT * FROM order_items WHERE order_id = 6;  -- check
BEGIN;
DELETE FROM order_items WHERE order_id = 6;
-- check the result, then:
COMMIT;   -- or ROLLBACK;
```

## Deleting several rows

```sql
DELETE FROM products
WHERE stock = 0 AND category = 'electronics';
```

Any condition that works in SELECT works in DELETE: `IN`, `BETWEEN`, `LIKE`, subqueries.

```sql
-- Remove items that belong to cancelled orders
DELETE FROM order_items
WHERE order_id IN (SELECT id FROM orders WHERE status = 'cancelled');
```

## Foreign keys protect related data

What happens if you delete a customer who has orders?

```sql
PRAGMA foreign_keys = ON;   -- SQLite only: turn on foreign key checks

DELETE FROM customers WHERE id = 1;
-- Error: FOREIGN KEY constraint failed
```

Asha has orders that point to her id. Deleting her would leave "orphaned" orders that belong to nobody, so the database refuses. (MySQL with InnoDB and PostgreSQL check foreign keys automatically.)

You decide what should happen when you create the table:

```sql
-- customer_id INTEGER REFERENCES customers(id) ON DELETE CASCADE
--   deleting a customer also deletes their orders

-- customer_id INTEGER REFERENCES customers(id) ON DELETE SET NULL
--   their orders stay, with customer_id set to NULL

-- (default) ON DELETE RESTRICT / NO ACTION
--   the delete is refused while related rows exist
```

`CASCADE` is convenient but powerful: one DELETE can remove thousands of related rows. Use it deliberately. You'll see more in the Constraints and Keys lesson.

## DELETE vs TRUNCATE vs DROP

| Statement | Removes | Table remains? | Can use WHERE? |
|---|---|---|---|
| `DELETE FROM t WHERE …` | Matching rows | Yes | Yes |
| `DELETE FROM t` | All rows, one by one | Yes | — |
| `TRUNCATE TABLE t` | All rows, very fast | Yes | No |
| `DROP TABLE t` | The whole table and its data | No | No |

`TRUNCATE` (not available in SQLite) is used to empty big tables quickly, for example log or test tables. In MySQL it also resets the auto-increment counter and can't be rolled back. `DROP` removes the table completely.

## Soft deletes: often better than deleting

Many applications never really delete important data. Instead, they mark it as deleted:

```sql
ALTER TABLE products ADD COLUMN deleted_at TIMESTAMP;

-- "Delete" a product
UPDATE products SET deleted_at = CURRENT_TIMESTAMP WHERE id = 8;

-- Normal queries ignore deleted products
SELECT name FROM products WHERE deleted_at IS NULL;

-- Restore it
UPDATE products SET deleted_at = NULL WHERE id = 8;
```

Benefits:

- Mistakes can be undone.
- Old orders still show the product they contained.
- You keep history for reports and audits.

WordPress does this with its Trash: a trashed post has `post_status = 'trash'` until it's permanently deleted.

The downside: every query needs `WHERE deleted_at IS NULL`, and privacy laws may require truly deleting personal data when a user asks. Many apps soft-delete first, then permanently delete after 30 days.

## Backups

The real safety net is a **backup**. Before big changes on a live site, export the database (Hostinger's panel, phpMyAdmin's Export, or `mysqldump`). If something goes wrong, you can restore it.

## Common mistakes

- **DELETE without WHERE.**
- **Not checking with SELECT first.**
- **Surprise cascades**: deleting one row removes much more than expected.
- **Deleting data you need for history** (old orders, invoices) instead of soft-deleting.
- **Thinking DELETE frees disk space immediately**: databases often reuse the space later rather than shrinking the file.

## Exercise

1. Create a test table, insert five rows, then delete two of them with one statement.
2. With foreign keys on, try to delete a customer who has orders. Read the error.
3. Delete the order items of order 6 inside a transaction, check, then roll back.
4. Add a `deleted_at` column to a test table, soft-delete one row, and write a SELECT that hides it.

## Summary

- `DELETE FROM table WHERE condition` removes rows; without WHERE it removes all of them.
- Always SELECT first and use transactions for important deletes.
- Foreign keys refuse, cascade or nullify deletes of related rows, depending on your rules.
- `TRUNCATE` empties a table fast; `DROP` removes it; soft deletes keep data recoverable.

Next, you'll summarise data with aggregate functions like COUNT, SUM and AVG.
