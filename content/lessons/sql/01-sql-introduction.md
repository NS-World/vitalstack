---
title: SQL Introduction
slug: sql-introduction
subject: sql
lesson: 1
difficulty: beginner
excerpt: What databases and SQL are, why almost every app uses them, the main database systems, and how to practise SQL for free in your browser.
---

Every app that remembers anything (your orders, messages, followers, progress through a course) stores that data in a **database**. **SQL** (Structured Query Language, often pronounced "sequel") is the language used to talk to most databases. It's one of the most useful and long-lasting skills in tech: developers, data analysts, product managers and marketers all use it.

## What is a database?

A database is an organised collection of data that a program can search and update quickly and safely. The most common kind is a **relational database**, which stores data in **tables**, like spreadsheets:

| id | name | city |
|---|---|---|
| 1 | Asha | Pune |
| 2 | Rohan | Delhi |
| 3 | Meera | Jaipur |

- Each **row** is one record (one customer).
- Each **column** is one piece of information (name, city).
- Tables can be **related** to each other: an `orders` table can point to the customer who placed each order. That's what "relational" means.

## Why not just use a spreadsheet?

Databases are built for things spreadsheets struggle with:

- **Size:** millions or billions of rows, still fast.
- **Many users at once:** thousands of people placing orders at the same moment without overwriting each other.
- **Rules:** every order must belong to a real customer; an email must be unique.
- **Safety:** changes are saved reliably even if the server crashes mid-way.
- **Questions in code:** "total sales per city last month" is one SQL statement.

## Your first SQL query

```sql
SELECT name, city
FROM customers
WHERE city = 'Pune';
```

Read it like English: "Select the name and city from the customers table where the city is Pune." The database answers with a small table of results:

| name | city |
|---|---|
| Asha | Pune |
| Kabir | Pune |

SQL is **declarative**: you describe **what** you want, and the database works out **how** to get it fast.

## What SQL can do

SQL statements fall into a few groups:

- **Query data:** `SELECT` (most of what you'll write).
- **Change data:** `INSERT`, `UPDATE`, `DELETE`.
- **Define structure:** `CREATE TABLE`, `ALTER TABLE`, `DROP TABLE`.
- **Control access:** `GRANT`, `REVOKE` (for administrators).

This course covers the first three groups.

## Popular database systems

| Database | Where you'll meet it |
|---|---|
| **MySQL / MariaDB** | WordPress and most shared hosting (including this site) |
| **PostgreSQL** | Modern web apps and startups; very feature-rich |
| **SQLite** | Inside phones, browsers and apps; a database in one file |
| **SQL Server** | Large companies using Microsoft tools |
| **Oracle** | Banks and big enterprises |

They all speak SQL. The core you'll learn here (SELECT, WHERE, JOIN, GROUP BY…) works the same in all of them; only small details differ, and we'll point them out.

## SQL syntax basics

```sql
-- This is a comment
SELECT name FROM customers;   /* so is this */

select NAME from Customers;   -- keywords aren't case-sensitive
```

- Statements end with a **semicolon**.
- SQL **keywords** are not case-sensitive, but writing them in UPPER CASE is the common style and makes queries easier to read.
- Text values go in **single quotes**: `'Pune'`. (Double quotes are for column or table names in standard SQL.)
- Spaces and new lines don't matter; spread long queries over several lines.
- Whether **table names** and **text comparisons** are case-sensitive depends on the database system and its settings.

## How to practise (free, nothing to install)

You need a database to run queries. The easiest options:

1. **sqliteonline.com** or **DB Fiddle** (db-fiddle.com): run SQL in your browser. Choose SQLite, MySQL or PostgreSQL.
2. **DB Browser for SQLite**: a free desktop app; the whole database is one file.
3. Later, install **MySQL** or **PostgreSQL** locally, or use the database from your hosting panel (phpMyAdmin).

In the next lesson, you'll create the practice database used throughout this course with one copy-and-paste script.

## Where SQL is used

- **Web development:** every login, comment and product page reads from a database.
- **Data analysis:** answering business questions from sales, traffic or app data.
- **Reporting tools** like Looker Studio, Power BI and Metabase are built on SQL.
- **AI and data science:** collecting and cleaning the data that models learn from.

## Common mistakes

- **Double quotes around text values**: use `'Pune'`, not `"Pune"` (MySQL allows both, PostgreSQL doesn't).
- **Forgetting the semicolon** when running several statements.
- **Expecting rows in a particular order** without asking for it (you'll learn `ORDER BY`).

## Exercise

1. Open sqliteonline.com (or any tool above) and run `SELECT 1 + 1;` to check it works.
2. Run `SELECT 'Hello, SQL!' AS greeting;`.
3. Think of an app you use daily. Write down three tables it probably has and three columns in each.

## Summary

- Relational databases store data in related tables of rows and columns.
- SQL is the language for querying and changing that data; you say what you want, not how.
- MySQL, PostgreSQL and SQLite all share the same core SQL.
- Practise for free in the browser with sqliteonline.com or DB Fiddle.

Next, you'll create tables and load the practice database for this course.
