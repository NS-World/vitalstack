---
title: SQL INSERT
slug: sql-insert
subject: sql
lesson: 6
difficulty: beginner
excerpt: Add rows with INSERT INTO, list columns explicitly, insert several rows at once, use defaults and auto-generated ids, copy rows with INSERT ... SELECT, and avoid SQL injection.
---

So far you've read data. Now you'll add it. Every sign-up, new post or order on a website ends in an `INSERT` statement.

## Basic INSERT

```sql
INSERT INTO customers (id, name, email, city, joined)
VALUES (6, 'Neha', 'neha@example.com', 'Chennai', '2024-06-01');
```

- `INSERT INTO table (columns)` lists the columns you're filling.
- `VALUES (…)` gives the values **in the same order**.
- Text and dates go in single quotes; numbers don't.

Check it:

```sql
SELECT * FROM customers WHERE id = 6;
```

## Always list the columns

You *can* leave out the column list:

```sql
INSERT INTO customers VALUES (7, 'Vikram', 'vikram@example.com', 'Kochi', '2024-06-02');
```

But then you must give a value for **every** column in the table's exact order. If someone later adds a column, this statement breaks (or worse, puts values in the wrong columns). Always list the columns explicitly.

## Leaving columns out: NULL and DEFAULT

Columns you don't mention get their **default** value, or `NULL` if there's no default:

```sql
INSERT INTO products (id, name, category, price)
VALUES (8, 'Sticky notes', 'stationery', 60);

SELECT name, stock FROM products WHERE id = 8;  -- stock is 0, the column's DEFAULT
```

```sql
INSERT INTO orders (id, customer_id, order_date)
VALUES (6, 6, '2024-06-03');

SELECT id, status FROM orders WHERE id = 6;     -- status is 'pending' (default)
```

A `NOT NULL` column without a default **must** be given a value, or the insert fails:

```sql
INSERT INTO products (id, category, price) VALUES (9, 'bags', 999);
-- Error: NOT NULL constraint failed: products.name
```

## Auto-generated ids

In real apps you don't pick ids yourself; the database generates them. Leave the id out:

```sql
INSERT INTO customers (name, email, city, joined)
VALUES ('Isha', 'isha@example.com', 'Goa', '2024-06-05');

SELECT id, name FROM customers WHERE email = 'isha@example.com';  -- gets the next id
```

This works because `id` is an auto-incrementing primary key (`INTEGER PRIMARY KEY` in SQLite, `AUTO_INCREMENT` in MySQL, `SERIAL`/`IDENTITY` in PostgreSQL). Your application can then ask for the new id:

- MySQL: `SELECT LAST_INSERT_ID();`
- SQLite: `SELECT last_insert_rowid();`
- PostgreSQL: add `RETURNING id` to the INSERT.

```sql
-- PostgreSQL (and SQLite 3.35+):
-- INSERT INTO customers (name, city) VALUES ('Dev', 'Surat') RETURNING id;
```

## Inserting several rows at once

```sql
INSERT INTO order_items (order_id, product_id, qty) VALUES
  (6, 1, 2),
  (6, 8, 4),
  (6, 6, 1);
```

One statement, many rows: much faster than separate statements when loading lots of data.

## Copying rows with INSERT ... SELECT

You can insert the result of a query:

```sql
CREATE TABLE pune_customers (
  id    INTEGER PRIMARY KEY,
  name  VARCHAR(100),
  email VARCHAR(100)
);

INSERT INTO pune_customers (id, name, email)
SELECT id, name, email
FROM customers
WHERE city = 'Pune';

SELECT * FROM pune_customers;  -- Asha and Kabir
```

Useful for archiving old data, building report tables or copying data between tables.

## Constraint errors

The database protects your data using the rules you set when creating the table:

```sql
INSERT INTO customers (name, email) VALUES ('Fake Asha', 'asha@example.com');
-- Error: UNIQUE constraint failed: customers.email

INSERT INTO orders (customer_id, order_date) VALUES (999, '2024-06-10');
-- Error in MySQL/PostgreSQL: foreign key violation (there's no customer 999)
```

These errors are good: they stop bad data from getting in. (SQLite only checks foreign keys after `PRAGMA foreign_keys = ON;`.) You'll learn more in the Constraints lesson.

## Quotes inside text

To store a single quote, write it twice:

```sql
INSERT INTO products (name, category, price) VALUES ('Kid''s lunchbox', 'accessories', 299);
```

## SQL injection: the most important security rule

When your application inserts **user input**, never build the SQL by joining strings:

```text
// DANGEROUS (pseudo-code)
sql = "INSERT INTO comments (text) VALUES ('" + userInput + "')"
```

If a user types `'); DROP TABLE comments; --`, they can run their own SQL. This attack is called **SQL injection**, and it's one of the most common ways websites get hacked.

Always use **parameterised queries** (prepared statements), where values are sent separately from the SQL:

```text
// Safe: the ? is filled in by the database driver
sql = "INSERT INTO comments (text) VALUES (?)"
db.execute(sql, [userInput])
```

Every language has this: `?` placeholders in Python's sqlite3 and in Node.js libraries, `$1` in PostgreSQL drivers, PDO prepared statements in PHP, and `$wpdb->prepare()` in WordPress. You'll use this pattern in the Python course.

## Common mistakes

- **Values in a different order** from the column list.
- **Missing quotes** around text or dates (or quotes around numbers, which some databases reject).
- **Setting ids by hand** in real applications; let the database generate them.
- **Building SQL from user input with string concatenation.**

## Exercise

1. Add yourself as a customer without giving an id. Find your new id.
2. Add two new products in one INSERT statement.
3. Create an order for your new customer and add two items to it.
4. Try to insert a customer with an email that already exists and read the error.
5. Create a table `expensive_products` and fill it with every product over ₹1000 using INSERT ... SELECT.

## Summary

- `INSERT INTO table (columns) VALUES (…)` adds a row; always list the columns.
- Missing columns get their DEFAULT or NULL; let the database generate ids.
- Insert many rows in one statement, or copy rows with `INSERT ... SELECT`.
- Constraints reject bad data; parameterised queries prevent SQL injection.

Next, you'll change existing rows with UPDATE.
