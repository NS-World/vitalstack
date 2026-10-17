---
title: SQL Online Playground
slug: sql-playground
parent: tools
template: page-tool.php
icon: 🗄️
order: 20
excerpt: Practise SQL online with a ready-made sample database. Run SELECT, JOIN, GROUP BY and more in your browser, free.
---
## How to use the SQL playground

The playground starts with a small sample database about an online school: **students**, **courses** and **enrollments**. Click a table on the right to see its rows, pick an example query, or write your own and press **Run** (Ctrl + Enter).

You can also change data with `INSERT`, `UPDATE` and `DELETE`, or create your own tables. **Reset database** brings back the original sample data.

## The sample database

| Table | Columns |
|---|---|
| `students` | id, name, city, age |
| `courses` | id, title, level, price |
| `enrollments` | student_id, course_id, score, enrolled_on |

## Exercises to try

1. List students from Delhi, youngest first.
2. Show each course with the number of students enrolled (include courses with nobody: `LEFT JOIN`).
3. Find the student with the highest average score.
4. Add a new course and enrol two students in it.
5. Which city has the most students?

Stuck? The [SQL tutorials](/tutorial-category/sql/) explain every clause step by step.

## Good to know

- This is **SQLite**, running entirely in your browser. Almost everything you learn here works the same in MySQL and PostgreSQL; small differences (like `LIMIT` vs `TOP`, or date functions) are explained in the lessons.
- Your last query is remembered on this device. Data changes are lost when you reload the page.
