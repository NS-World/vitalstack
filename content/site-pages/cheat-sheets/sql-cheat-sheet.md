---
title: SQL Cheat Sheet
slug: sql-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: 🗃️
order: 30
excerpt: SQL queries on one page - SELECT, WHERE, ORDER BY, JOINs, GROUP BY, HAVING, INSERT, UPDATE, DELETE, constraints, indexes and window functions. Printable.
---
## Querying

```sql
SELECT name, city FROM students;
SELECT DISTINCT city FROM students;
SELECT name, marks * 1.1 AS boosted FROM students;
SELECT * FROM students ORDER BY marks DESC, name LIMIT 10;
```

## Filtering

```sql
WHERE marks >= 40 AND city = 'Pune'
WHERE city IN ('Pune', 'Delhi')
WHERE marks BETWEEN 60 AND 80          -- inclusive
WHERE name LIKE 'A%'                   -- starts with A
WHERE email IS NULL                    -- never = NULL
WHERE NOT (age < 18)
```

## Aggregates and grouping

```sql
SELECT COUNT(*), AVG(marks), MAX(marks), MIN(marks), SUM(fee) FROM students;

SELECT city, COUNT(*) AS students, AVG(marks) AS avg_marks
FROM students
WHERE active = TRUE              -- filter rows first
GROUP BY city
HAVING COUNT(*) > 5              -- then filter groups
ORDER BY students DESC;
```

## Joins

```sql
-- only matching rows
SELECT o.id, c.name FROM orders o
JOIN customers c ON c.id = o.customer_id;

-- all customers, even without orders
SELECT c.name, COUNT(o.id) AS orders
FROM customers c
LEFT JOIN orders o ON o.customer_id = c.id
GROUP BY c.name;

-- customers with no orders
SELECT c.* FROM customers c
LEFT JOIN orders o ON o.customer_id = c.id
WHERE o.id IS NULL;
```

| Join | Returns |
|---|---|
| `INNER JOIN` | rows matching in both tables |
| `LEFT JOIN` | all left rows + matches (NULL if none) |
| `RIGHT JOIN` | all right rows + matches |
| `FULL OUTER JOIN` | all rows from both |
| `CROSS JOIN` | every combination |

## Changing data

```sql
INSERT INTO students (name, city, marks) VALUES ('Asha', 'Pune', 88), ('Ravi', 'Delhi', 72);
UPDATE students SET marks = marks + 5 WHERE id = 7;     -- never forget WHERE
DELETE FROM students WHERE id = 7;
INSERT INTO archive SELECT * FROM students WHERE year < 2020;
```

## Functions

```sql
UPPER(name), LOWER(name), LENGTH(name), TRIM(name), SUBSTRING(name, 1, 3)
CONCAT(first, ' ', last), COALESCE(phone, 'N/A'), ROUND(price, 2)
CURRENT_DATE, NOW(), EXTRACT(YEAR FROM joined_on)
CASE WHEN marks >= 75 THEN 'A' WHEN marks >= 40 THEN 'Pass' ELSE 'Fail' END
```

## Tables and constraints

```sql
CREATE TABLE students (
  id         INT PRIMARY KEY AUTO_INCREMENT,     -- SERIAL / IDENTITY in PostgreSQL
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(150) UNIQUE,
  marks      INT CHECK (marks BETWEEN 0 AND 100),
  city_id    INT REFERENCES cities(id),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE students ADD COLUMN phone VARCHAR(15);
DROP TABLE IF EXISTS temp;
```

## Indexes

```sql
CREATE INDEX idx_students_city ON students(city);
CREATE INDEX idx_orders_cust_date ON orders(customer_id, created_at);   -- column order matters
EXPLAIN SELECT * FROM students WHERE city = 'Pune';
```

Index columns used in `WHERE`, `JOIN` and `ORDER BY`; indexes slow down writes slightly.

## Subqueries, CTEs and window functions

```sql
SELECT name FROM students WHERE marks > (SELECT AVG(marks) FROM students);

WITH city_avg AS (
  SELECT city, AVG(marks) AS avg_marks FROM students GROUP BY city
)
SELECT * FROM city_avg WHERE avg_marks > 70;

SELECT name, city, marks,
       RANK() OVER (PARTITION BY city ORDER BY marks DESC) AS rank_in_city,
       SUM(fee) OVER (ORDER BY joined_on) AS running_total
FROM students;
```

## Transactions

```sql
BEGIN;
UPDATE accounts SET balance = balance - 500 WHERE id = 1;
UPDATE accounts SET balance = balance + 500 WHERE id = 2;
COMMIT;          -- or ROLLBACK;
```

## Order of execution

`FROM` → `JOIN` → `WHERE` → `GROUP BY` → `HAVING` → `SELECT` → `DISTINCT` → `ORDER BY` → `LIMIT`
