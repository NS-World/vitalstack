---
title: "SQL Project: Library Database"
slug: sql-project-library-database
subject: sql
lesson: 15
difficulty: intermediate
excerpt: Design, build and query a complete library database with authors, books, members and loans, using tables, constraints, joins, grouping and indexes.
---

Time to put the whole course together. You'll design a database for a small library, create it with constraints, load sample data, and answer real questions a librarian would ask, from "which books are overdue?" to "who are our most active readers?".

Run everything in sqliteonline.com, DB Browser for SQLite or any SQLite tool. (Small changes for MySQL and PostgreSQL are noted.)

## Step 1: Design

What does a library need to track?

- **Authors**: name, country.
- **Books**: title, year, genre, number of copies. Each book has one author (we'll keep it simple).
- **Members**: name, email, join date.
- **Loans**: which member borrowed which book, when, when it's due, and when it came back.

Relationships:

```text
authors 1 ──< books 1 ──< loans >── 1 members
```

One author writes many books. One book can be loaned many times; one member can borrow many books. `loans` connects books and members, and also stores details about each borrowing.

## Step 2: Create the tables

```sql
PRAGMA foreign_keys = ON;

CREATE TABLE authors (
  id       INTEGER PRIMARY KEY,
  name     VARCHAR(100) NOT NULL,
  country  VARCHAR(50)
);

CREATE TABLE books (
  id         INTEGER PRIMARY KEY,
  title      VARCHAR(200) NOT NULL,
  author_id  INTEGER NOT NULL REFERENCES authors(id),
  genre      VARCHAR(50),
  year       INTEGER CHECK (year > 0),
  copies     INTEGER NOT NULL DEFAULT 1 CHECK (copies >= 0)
);

CREATE TABLE members (
  id      INTEGER PRIMARY KEY,
  name    VARCHAR(100) NOT NULL,
  email   VARCHAR(100) NOT NULL UNIQUE,
  joined  DATE NOT NULL
);

CREATE TABLE loans (
  id           INTEGER PRIMARY KEY,
  book_id      INTEGER NOT NULL REFERENCES books(id),
  member_id    INTEGER NOT NULL REFERENCES members(id),
  loaned_on    DATE NOT NULL,
  due_on       DATE NOT NULL,
  returned_on  DATE,
  CHECK (due_on >= loaned_on)
);

CREATE INDEX idx_books_author ON books (author_id);
CREATE INDEX idx_loans_book ON loans (book_id);
CREATE INDEX idx_loans_member ON loans (member_id);
```

Design decisions:

- `returned_on` is NULL while a book is still out. That single column tells us the loan's status.
- CHECK constraints stop impossible data (negative copies, due before loaned).
- Foreign key columns are indexed for fast joins.

## Step 3: Load sample data

```sql
INSERT INTO authors (id, name, country) VALUES
  (1, 'R. K. Narayan', 'India'),
  (2, 'Agatha Christie', 'UK'),
  (3, 'Paulo Coelho', 'Brazil'),
  (4, 'Ruskin Bond', 'India'),
  (5, 'Yuval Noah Harari', 'Israel');

INSERT INTO books (id, title, author_id, genre, year, copies) VALUES
  (1, 'Malgudi Days', 1, 'fiction', 1943, 3),
  (2, 'The Guide', 1, 'fiction', 1958, 2),
  (3, 'Murder on the Orient Express', 2, 'mystery', 1934, 2),
  (4, 'And Then There Were None', 2, 'mystery', 1939, 1),
  (5, 'The Alchemist', 3, 'fiction', 1988, 4),
  (6, 'The Blue Umbrella', 4, 'children', 1980, 2),
  (7, 'Sapiens', 5, 'history', 2011, 3);

INSERT INTO members (id, name, email, joined) VALUES
  (1, 'Asha', 'asha@example.com', '2024-01-05'),
  (2, 'Rohan', 'rohan@example.com', '2024-02-11'),
  (3, 'Meera', 'meera@example.com', '2024-03-02'),
  (4, 'Kabir', 'kabir@example.com', '2024-04-18');

INSERT INTO loans (id, book_id, member_id, loaned_on, due_on, returned_on) VALUES
  (1, 1, 1, '2024-05-01', '2024-05-15', '2024-05-12'),
  (2, 3, 1, '2024-05-14', '2024-05-28', NULL),
  (3, 5, 2, '2024-05-03', '2024-05-17', '2024-05-20'),
  (4, 7, 2, '2024-05-21', '2024-06-04', NULL),
  (5, 5, 3, '2024-05-10', '2024-05-24', '2024-05-22'),
  (6, 6, 3, '2024-06-01', '2024-06-15', NULL),
  (7, 1, 1, '2024-06-02', '2024-06-16', NULL),
  (8, 4, 2, '2024-06-03', '2024-06-17', NULL);
```

Kabir has never borrowed anything, and *The Guide* has never been loaned. Those cases matter for the LEFT JOIN questions.

## Step 4: Answer real questions

We'll pretend **today is 2024-06-10**, so results don't depend on when you run them. In a real app you'd use `DATE('now')` (SQLite), `CURDATE()` (MySQL) or `CURRENT_DATE` (PostgreSQL).

### 1. Every book with its author

```sql
SELECT b.title, a.name AS author, b.year
FROM books b
JOIN authors a ON a.id = b.author_id
ORDER BY a.name, b.year;
```

### 2. Books currently on loan, and who has them

```sql
SELECT b.title, m.name AS member, l.due_on
FROM loans l
JOIN books b   ON b.id = l.book_id
JOIN members m ON m.id = l.member_id
WHERE l.returned_on IS NULL
ORDER BY l.due_on;
```

Five books are out: Murder on the Orient Express (Asha), Sapiens (Rohan), The Blue Umbrella (Meera), Malgudi Days (Asha) and And Then There Were None (Rohan).

### 3. Overdue loans

```sql
SELECT m.name, m.email, b.title, l.due_on
FROM loans l
JOIN books b   ON b.id = l.book_id
JOIN members m ON m.id = l.member_id
WHERE l.returned_on IS NULL
  AND l.due_on < '2024-06-10'
ORDER BY l.due_on;
```

Asha's *Murder on the Orient Express* (due 28 May) and Rohan's *Sapiens* (due 4 June). This is the list for reminder emails.

### 4. Copies available right now

```sql
SELECT b.title,
       b.copies,
       COUNT(l.id) AS on_loan,
       b.copies - COUNT(l.id) AS available
FROM books b
LEFT JOIN loans l ON l.book_id = b.id AND l.returned_on IS NULL
GROUP BY b.id, b.title, b.copies
ORDER BY available, b.title;
```

The LEFT JOIN keeps books with no active loans, and the condition sits in **ON** so they aren't filtered out. *And Then There Were None* has 0 copies available.

### 5. Most popular books (all-time loans)

```sql
SELECT b.title, COUNT(l.id) AS times_borrowed
FROM books b
LEFT JOIN loans l ON l.book_id = b.id
GROUP BY b.id, b.title
ORDER BY times_borrowed DESC, b.title;
```

*Malgudi Days* and *The Alchemist* lead with 2 loans each; *The Guide* shows 0.

### 6. Most active members

```sql
SELECT m.name,
       COUNT(l.id) AS loans,
       SUM(CASE WHEN l.id IS NOT NULL AND l.returned_on IS NULL THEN 1 ELSE 0 END) AS still_out
FROM members m
LEFT JOIN loans l ON l.member_id = m.id
GROUP BY m.id, m.name
ORDER BY loans DESC, m.name;
```

Asha and Rohan have 3 loans each (2 still out), Meera 2 (1 still out), Kabir 0.

Why `l.id IS NOT NULL`? Kabir has no loans, so the LEFT JOIN gives him one row where **every** loan column is NULL, including `returned_on`. Without the extra check, that empty row would count as "1 book still out". Whenever you test for NULL on the right side of a LEFT JOIN, ask yourself: does NULL mean "no value" or "no matching row"?

### 7. Members who returned a book late

```sql
SELECT DISTINCT m.name
FROM loans l
JOIN members m ON m.id = l.member_id
WHERE l.returned_on > l.due_on;
```

Rohan returned *The Alchemist* three days late.

### 8. Loans per genre

```sql
SELECT b.genre, COUNT(*) AS loans
FROM loans l
JOIN books b ON b.id = l.book_id
GROUP BY b.genre
ORDER BY loans DESC;
```

### 9. Authors whose books were never borrowed

```sql
SELECT a.name
FROM authors a
JOIN books b      ON b.author_id = a.id
LEFT JOIN loans l ON l.book_id = b.id
GROUP BY a.id, a.name
HAVING COUNT(l.id) = 0;
```

Empty: every author has at least one borrowed book. (*The Guide* was never borrowed, but its author's other book was.) Change the query to list **books** never borrowed instead.

## Step 5: Day-to-day operations

```sql
-- Kabir borrows The Guide today
INSERT INTO loans (book_id, member_id, loaned_on, due_on)
VALUES (2, 4, '2024-06-10', '2024-06-24');

-- Asha returns Murder on the Orient Express
UPDATE loans
SET returned_on = '2024-06-10'
WHERE id = 2;

-- The library buys another copy of And Then There Were None
UPDATE books SET copies = copies + 1 WHERE id = 4;
```

Each statement targets exact rows by id. Try breaking the rules too: a loan for book 99, or a due date before the loan date. The constraints refuse them.

## Ideas to extend it

- Add a `fines` table: ₹5 per day late, calculated from `returned_on - due_on`.
- Support books with several authors using a `book_authors` junction table.
- Add a `reservations` table for books with no copies available.
- Connect it to a program: the Python course shows how to query SQLite from Python.

## What you've learned

In 15 lessons you've learned to design tables, protect data with constraints, insert, update and delete safely, filter, sort, group and join, and make it all fast with indexes. That's the SQL most developers and analysts use every day.

## Summary

- Good design starts with entities (authors, books, members) and relationships (loans).
- Constraints and indexes are part of the design, not an afterthought.
- JOINs, LEFT JOINs, GROUP BY and CASE answer real business questions.
- Keep "today" as a parameter in practice queries; use the database's date function in real apps.

Next, continue with the **Python** course, where you'll write programs that work with data like this.
