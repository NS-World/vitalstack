---
title: SQL Databases and Tables
slug: sql-databases-and-tables
subject: sql
lesson: 2
difficulty: beginner
excerpt: Create tables with CREATE TABLE, choose data types, understand primary keys and NULL, and load the practice shop database used in every lesson of this course.
---

Before you can query data, it needs a home: a **table** with defined columns. In this lesson you'll learn how tables are created, choose data types, and set up the small online-shop database used for every example in the rest of the course.

## Creating a table

```sql
CREATE TABLE customers (
  id      INTEGER PRIMARY KEY,
  name    VARCHAR(100) NOT NULL,
  city    VARCHAR(50),
  joined  DATE
);
```

- `CREATE TABLE customers` creates a table named `customers`.
- Inside the brackets, each line defines a **column**: its name, its **data type**, and optional rules.
- `PRIMARY KEY` marks the column that uniquely identifies each row.
- `NOT NULL` means the column must always have a value.

## Common data types

| Type | Stores | Example |
|---|---|---|
| `INTEGER` / `INT` | Whole numbers | 42 |
| `DECIMAL(10,2)` | Exact decimals, e.g. money | 1499.00 |
| `VARCHAR(n)` | Text up to n characters | 'Asha' |
| `TEXT` | Long text | A blog post |
| `DATE` | A date | '2024-05-01' |
| `TIMESTAMP` / `DATETIME` | Date and time | '2024-05-01 14:30:00' |
| `BOOLEAN` | true / false | TRUE |

Pick the type that matches the data: store prices in `DECIMAL`, not text, so you can add them up; store dates as `DATE` so you can sort and compare them. (SQLite is relaxed about types; MySQL and PostgreSQL enforce them.)

Write dates in the **ISO format** `'YYYY-MM-DD'`. Every database understands it, and it sorts correctly.

## Primary keys

Every table should have a **primary key**: a column (usually `id`) whose value is unique for every row and never empty. It's how other tables refer to a row, and how you update or delete exactly one record.

Databases can generate ids automatically:

```sql
-- MySQL
-- id INT AUTO_INCREMENT PRIMARY KEY

-- PostgreSQL
-- id SERIAL PRIMARY KEY      (or: id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY)

-- SQLite
-- id INTEGER PRIMARY KEY     (automatically increases)
```

## NULL: the missing value

`NULL` means "no value / unknown". It's not zero and not an empty string. If a customer didn't tell you their city, `city` is `NULL`. You'll learn how to search for NULL in the WHERE lesson.

## The practice database

Our shop has four related tables:

- **customers**: who buys.
- **products**: what we sell.
- **orders**: one row per order, pointing to a customer.
- **order_items**: which products (and how many) are in each order.

Copy this whole script into sqliteonline.com (or DB Fiddle with SQLite/MySQL/PostgreSQL) and run it:

```sql
CREATE TABLE customers (
  id      INTEGER PRIMARY KEY,
  name    VARCHAR(100) NOT NULL,
  email   VARCHAR(100) UNIQUE,
  city    VARCHAR(50),
  joined  DATE
);

CREATE TABLE products (
  id        INTEGER PRIMARY KEY,
  name      VARCHAR(100) NOT NULL,
  category  VARCHAR(50),
  price     DECIMAL(10,2) NOT NULL,
  stock     INTEGER DEFAULT 0
);

CREATE TABLE orders (
  id           INTEGER PRIMARY KEY,
  customer_id  INTEGER NOT NULL REFERENCES customers(id),
  order_date   DATE NOT NULL,
  status       VARCHAR(20) DEFAULT 'pending'
);

CREATE TABLE order_items (
  order_id    INTEGER NOT NULL REFERENCES orders(id),
  product_id  INTEGER NOT NULL REFERENCES products(id),
  qty         INTEGER NOT NULL,
  PRIMARY KEY (order_id, product_id)
);

INSERT INTO customers (id, name, email, city, joined) VALUES
  (1, 'Asha',  'asha@example.com',  'Pune',   '2024-01-10'),
  (2, 'Rohan', 'rohan@example.com', 'Delhi',  '2024-02-03'),
  (3, 'Meera', 'meera@example.com', 'Jaipur', '2024-02-20'),
  (4, 'Kabir', 'kabir@example.com', 'Pune',   '2024-03-15'),
  (5, 'Sara',  NULL,                'Mumbai', '2024-04-01');

INSERT INTO products (id, name, category, price, stock) VALUES
  (1, 'Notebook',     'stationery',  120.00,  50),
  (2, 'Pen set',      'stationery',  250.00,  30),
  (3, 'Headphones',   'electronics', 1499.00, 0),
  (4, 'USB cable',    'electronics', 299.00,  75),
  (5, 'Backpack',     'bags',        1899.00, 12),
  (6, 'Water bottle', 'accessories', 350.00,  40),
  (7, 'Desk lamp',    'electronics', 899.00,  8);

INSERT INTO orders (id, customer_id, order_date, status) VALUES
  (1, 1, '2024-05-01', 'delivered'),
  (2, 2, '2024-05-03', 'delivered'),
  (3, 1, '2024-05-10', 'shipped'),
  (4, 3, '2024-05-12', 'pending'),
  (5, 4, '2024-05-15', 'delivered');

INSERT INTO order_items (order_id, product_id, qty) VALUES
  (1, 1, 3), (1, 2, 1),
  (2, 5, 1),
  (3, 4, 2),
  (4, 1, 5), (4, 6, 1),
  (5, 3, 1);
```

Check it worked:

```sql
SELECT * FROM products;
```

You should see 7 products. Keep this database open; every following lesson uses it. (If you close the browser tab, just run the script again.)

## How the tables relate

```text
customers (id) ──< orders (customer_id)
orders (id)    ──< order_items (order_id)
products (id)  ──< order_items (product_id)
```

One customer can have **many** orders; one order can have **many** items; one product can appear in **many** orders. Notice that Sara has no orders and the desk lamp has never been sold. These cases will matter when you learn JOINs.

## Changing and removing tables

```sql
ALTER TABLE customers ADD COLUMN phone VARCHAR(20);
-- DROP TABLE customers;   -- deletes the table and ALL its data. Be careful!
```

`ALTER TABLE` changes an existing table's structure. `DROP TABLE` deletes it permanently; there is no undo.

## Naming conventions

- Use lowercase names with underscores: `order_items`, `customer_id`.
- Table names are usually plural (`customers`); column names singular.
- Name foreign key columns `<table>_id`: `customer_id`.
- Avoid spaces and reserved words like `order`, `select` or `user` as names.

## Common mistakes

- **Storing numbers or dates as text**, which breaks sorting and maths ("100" sorts before "20").
- **No primary key**, making it impossible to target one row reliably.
- **Running DROP TABLE on real data** without a backup.
- **Using reserved words** like `order` as table names (that's why ours is `orders`).

## Exercise

1. Run the practice script and check each table with `SELECT * FROM table_name;`.
2. Write a `CREATE TABLE` statement for a `reviews` table with id, product_id, rating (1–5), comment and created date.
3. Add a `phone` column to `customers` with `ALTER TABLE`.

## Summary

- `CREATE TABLE` defines columns with names, data types and rules.
- Choose proper types: `INTEGER`, `DECIMAL`, `VARCHAR`, `DATE`.
- Every table needs a primary key; `NULL` means "no value".
- The practice shop database (customers, products, orders, order_items) is used throughout the course.

Next, you'll read data from tables with SELECT.
