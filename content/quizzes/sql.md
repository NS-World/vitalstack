## sql-introduction
Q: What does SQL stand for?
- [x] Structured Query Language
- [ ] Simple Question Language
- [ ] Server Query Logic
> It talks to relational databases.

Q: Which is a relational database?
- [x] PostgreSQL
- [ ] Excel
- [ ] JSON
> MySQL, SQLite, SQL Server and Oracle too.

Q: Why use a database instead of a spreadsheet for an app?
- [x] Many users, rules, relationships and fast queries on large data
- [ ] Spreadsheets can't store numbers
- [ ] Databases are prettier
> Databases enforce constraints and handle concurrency.

## sql-databases-and-tables
Q: What is a primary key?
- [x] A column that uniquely identifies each row
- [ ] The first column
- [ ] A password
> It can't be NULL or duplicated.

Q: What does NULL mean?
- [x] Missing or unknown value
- [ ] Zero
- [ ] An empty string
> Use IS NULL to test it.

Q: Which data type stores exact money values?
- [x] DECIMAL(10,2)
- [ ] FLOAT
- [ ] TEXT
> FLOAT has rounding errors.

## sql-select
Q: Which removes duplicate rows from results?
- [x] DISTINCT
- [ ] UNIQUE
- [ ] DIFFERENT
> SELECT DISTINCT city FROM students.

Q: What does `AS` do in `SELECT price * 1.18 AS total`?
- [x] Names the result column
- [ ] Converts the type
- [ ] Filters rows
> Aliases make results readable.

Q: Why avoid `SELECT *` in applications?
- [x] It fetches unneeded columns and breaks when tables change
- [ ] It's invalid
- [ ] It's slower to type
> List the columns you need.

## sql-where
Q: How do you find rows where email is missing?
- [x] `WHERE email IS NULL`
- [ ] `WHERE email = NULL`
- [ ] `WHERE email == NULL`
> = NULL is never true.

Q: `WHERE name LIKE 'A%'` finds…
- [x] Names starting with A
- [ ] Names ending with A
- [ ] Names containing %
> % matches any characters.

Q: `BETWEEN 10 AND 20` includes…
- [x] Both 10 and 20
- [ ] Neither
- [ ] Only 10
> BETWEEN is inclusive.

## sql-order-by
Q: How do you sort highest first?
- [x] ORDER BY marks DESC
- [ ] ORDER BY marks UP
- [ ] SORT marks
> ASC is the default.

Q: How do you get the top 3 rows?
- [x] ORDER BY ... DESC LIMIT 3
- [ ] TOP ORDER 3
- [ ] LIMIT 3 without ORDER BY
> Without ORDER BY the "top" is random.

Q: Can you sort by several columns?
- [x] Yes: ORDER BY city, name
- [ ] No
- [ ] Only two
> Later columns break ties.

## sql-insert
Q: Why always list the columns in INSERT?
- [x] The query keeps working if columns are added or reordered
- [ ] It's faster
- [ ] It's required
> INSERT INTO t (a, b) VALUES (...).

Q: How do you write the name O'Brien in SQL?
- [x] 'O''Brien'
- [ ] 'O'Brien'
- [ ] "O'Brien" always
> Double the single quote (or use parameters in code).

Q: Can one INSERT add several rows?
- [x] Yes: VALUES (...), (...), (...)
- [ ] No
- [ ] Only in MySQL
> Faster than many single inserts.

## sql-update
Q: What happens with UPDATE without WHERE?
- [x] Every row is updated
- [ ] Nothing
- [ ] Error
> Always check WHERE first (run it as a SELECT).

Q: How do you raise all prices by 10%?
- [x] UPDATE products SET price = price * 1.10
- [ ] UPDATE products price + 10%
- [ ] SET price = 10%
> Calculated updates use the current value.

Q: CASE in an UPDATE lets you…
- [x] Set different values depending on conditions
- [ ] Update several tables
- [ ] Undo changes
> Like if/else inside SQL.

## sql-delete
Q: DELETE vs TRUNCATE vs DROP?
- [x] DELETE removes rows; TRUNCATE empties the table quickly; DROP removes the table
- [ ] They're the same
- [ ] DROP removes rows only
> DROP deletes structure too.

Q: What is a soft delete?
- [x] Marking rows as deleted with a column instead of removing them
- [ ] Deleting slowly
- [ ] Deleting with LIMIT
> Data can be restored and audited.

Q: What can stop a DELETE?
- [x] A foreign key that still references the row
- [ ] An index
- [ ] ORDER BY
> Foreign keys protect related data.

## sql-functions
Q: COUNT(*) vs COUNT(email)?
- [x] COUNT(email) skips NULLs
- [ ] They're identical
- [ ] COUNT(*) skips NULLs
> COUNT(*) counts all rows.

Q: What does COALESCE(phone, 'N/A') do?
- [x] Returns phone, or 'N/A' if phone is NULL
- [ ] Joins strings
- [ ] Counts phones
> Handy for display.

Q: Which gives "if/else" logic in a query?
- [x] CASE WHEN ... THEN ... ELSE ... END
- [ ] IF ... ELSE in SELECT everywhere
- [ ] SWITCH
> CASE works in SELECT, ORDER BY and UPDATE.

## sql-group-by
Q: WHERE vs HAVING?
- [x] WHERE filters rows before grouping; HAVING filters groups
- [ ] They're the same
- [ ] HAVING runs first
> HAVING COUNT(*) > 2.

Q: Which columns may appear in SELECT with GROUP BY?
- [x] Grouped columns and aggregates
- [ ] Any column
- [ ] Only aggregates
> Other columns are ambiguous per group.

Q: How do you count students per city?
- [x] SELECT city, COUNT(*) FROM students GROUP BY city
- [ ] SELECT COUNT(city) FROM students
- [ ] SELECT city FROM students COUNT
> One row per city.

## sql-inner-join
Q: An INNER JOIN returns…
- [x] Only rows with a match in both tables
- [ ] All rows from the left table
- [ ] All rows from both
> Unmatched rows disappear.

Q: Why use table aliases like `o` and `c`?
- [x] Shorter queries and no ambiguous column names
- [ ] They're required
- [ ] They're faster
> o.id vs c.id.

Q: The ON clause usually matches…
- [x] A foreign key to a primary key
- [ ] Two text columns
- [ ] Nothing
> orders.customer_id = customers.id.

## sql-left-join
Q: How do you find customers with no orders?
- [x] LEFT JOIN orders and WHERE orders.id IS NULL
- [ ] INNER JOIN
- [ ] COUNT(*) = 0 with INNER JOIN
> Unmatched rows have NULLs on the right.

Q: Why does a filter on the right table in WHERE turn a LEFT JOIN into an inner one?
- [x] Rows with NULLs on the right are filtered out
- [ ] It doesn't
- [ ] SQL forbids it
> Put such conditions in ON.

Q: A self join is…
- [x] Joining a table to itself, e.g. employees and their managers
- [ ] An error
- [ ] Joining two databases
> Use two aliases.

## sql-constraints-and-keys
Q: A foreign key ensures…
- [x] Referenced rows exist in the other table
- [ ] Values are unique
- [ ] Values are positive
> It protects relationships.

Q: Which constraint enforces a rule like price >= 0?
- [x] CHECK
- [ ] UNIQUE
- [ ] DEFAULT
> The database rejects bad data.

Q: Can a table have several UNIQUE constraints?
- [x] Yes
- [ ] No, only one
- [ ] Only with a primary key
> Email and phone can both be unique.

## sql-indexes
Q: What does an index speed up?
- [x] Lookups, filters, joins and sorting on its columns
- [ ] Inserts always
- [ ] Table creation
> Like a book's index.

Q: The cost of indexes is…
- [x] Slower writes and extra storage
- [ ] Slower reads
- [ ] Nothing
> Index what you query.

Q: Which tool shows whether a query uses an index?
- [x] EXPLAIN
- [ ] DESCRIBE
- [ ] SHOW TABLES
> Look for index scans vs full scans.

## sql-project-library-database
Q: Why separate books, members and loans into tables?
- [x] To avoid duplicate data and link records with keys
- [ ] SQL requires three tables
- [ ] For colours
> Loans reference books and members.

Q: Which query finds overdue loans?
- [x] SELECT ... FROM loans WHERE returned_on IS NULL AND due_on < CURRENT_DATE
- [ ] SELECT * FROM loans
- [ ] DELETE FROM loans
> Unreturned and past the due date.

Q: Which join lists every member with their loan count, including zero?
- [x] LEFT JOIN with COUNT and GROUP BY
- [ ] INNER JOIN
- [ ] CROSS JOIN
> LEFT JOIN keeps members with no loans.
