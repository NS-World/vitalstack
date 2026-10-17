---
title: JPA Introduction - What is ORM?
slug: jpa-introduction
subject: jpa
lesson: 1
difficulty: beginner
excerpt: Why writing JDBC by hand gets painful, what Object-Relational Mapping solves, how JPA, Hibernate and Spring Data JPA relate, and what the code looks like before and after.
---

Java works with **objects**: a `Course` with a title and a list of lessons. Databases work with **tables**: rows and columns linked by foreign keys. Moving data between the two by hand is repetitive and error-prone. **JPA (Jakarta Persistence API)** does it for you.

## The pain of plain JDBC

Saving and loading one simple object with JDBC:

```java
// Save
String sql = "INSERT INTO course (title, price, level) VALUES (?, ?, ?)";
try (Connection c = ds.getConnection(); PreparedStatement ps = c.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
    ps.setString(1, course.getTitle());
    ps.setInt(2, course.getPrice());
    ps.setString(3, course.getLevel().name());
    ps.executeUpdate();
    try (ResultSet keys = ps.getGeneratedKeys()) {
        if (keys.next()) course.setId(keys.getLong(1));
    }
}

// Load
try (Connection c = ds.getConnection(); PreparedStatement ps = c.prepareStatement("SELECT * FROM course WHERE id = ?")) {
    ps.setLong(1, id);
    try (ResultSet rs = ps.executeQuery()) {
        if (rs.next()) {
            Course course = new Course();
            course.setId(rs.getLong("id"));
            course.setTitle(rs.getString("title"));
            course.setPrice(rs.getInt("price"));
            course.setLevel(Level.valueOf(rs.getString("level")));
            return course;
        }
    }
}
```

Now multiply that by 40 tables, add relationships (a course has lessons, lessons have quizzes), updates of only changed columns, caching, and different SQL dialects for MySQL vs PostgreSQL vs Oracle. That's a lot of boring, bug-prone code.

## The same with JPA

```java
em.persist(course);                          // INSERT, id filled in automatically
Course c = em.find(Course.class, 1L);        // SELECT and map to an object
c.setPrice(399);                             // UPDATE happens automatically at commit
```

You describe the mapping once with annotations, and JPA writes the SQL:

```java
@Entity
public class Course {
    @Id @GeneratedValue
    private Long id;
    private String title;
    private int price;
    @Enumerated(EnumType.STRING)
    private Level level;
    // getters and setters
}
```

## What is ORM?

**Object-Relational Mapping** bridges the two worlds:

| Java world | Database world |
|---|---|
| class `Course` | table `course` |
| object / instance | row |
| field `title` | column `title` |
| object reference `lesson.course` | foreign key `lesson.course_id` |
| `List<Lesson>` in Course | rows in `lesson` pointing to the course |
| inheritance | single table / joined tables |

## JPA vs Hibernate vs Spring Data JPA

These names confuse everyone at first:

```text
Spring Data JPA   ← writes repository classes for you (findByTitle…)
      │ uses
JPA (jakarta.persistence)   ← the STANDARD: annotations + EntityManager API (a specification)
      │ implemented by
Hibernate  (or EclipseLink, OpenJPA)   ← the ENGINE that actually generates SQL
      │ uses
JDBC   ← talks to the database driver
```

- **JPA** is a specification: interfaces and annotations (`@Entity`, `EntityManager`).
- **Hibernate** is the most popular implementation. It also has extra features beyond JPA (next course).
- **Spring Data JPA** sits on top and removes even more boilerplate (Spring Boot course).

Write against JPA annotations and APIs; you'll be using Hibernate underneath in 90% of projects.

::tip Package names: modern JPA (3.x, used by Spring Boot 3 and Hibernate 6) uses `jakarta.persistence.*`. Old tutorials use `javax.persistence.*`. Don't mix them.

## What JPA gives you

- Mapping classes to tables with annotations.
- CRUD without SQL: `persist`, `find`, `merge`, `remove`.
- Automatic change tracking ("dirty checking"): modify an object, and the UPDATE is generated.
- Relationships: one-to-many, many-to-one, many-to-many.
- **JPQL**: queries written against classes and fields, portable across databases.
- Caching, lazy loading, optimistic locking, schema generation.

## When ORM isn't the right tool

- Heavy reporting with complex SQL: native SQL or a query library (jOOQ) can be clearer.
- Bulk operations on millions of rows: use batch SQL.
- Very simple apps: `JdbcTemplate` might be enough.

Most business apps, though, benefit hugely from JPA. It's in nearly every Java back-end job description.

## Exercise

1. Write the `CREATE TABLE` you'd expect for the `Course` entity above.
2. Name three things JDBC code makes you do by hand that JPA automates.
3. Explain in one sentence each: JPA, Hibernate, Spring Data JPA.

::hint Hint for 1 | `CREATE TABLE course (id BIGINT PRIMARY KEY, title VARCHAR(255), price INT NOT NULL, level VARCHAR(255))`. `int` maps to NOT NULL because a primitive can't be null.

## Interview corner

- **What is ORM?** A technique to map objects to relational tables so code works with objects instead of SQL and result sets.
- **JPA vs Hibernate?** JPA is a specification (API); Hibernate is an implementation of it with extra features.
- **What is an entity?** A lightweight persistent class annotated with `@Entity`, whose instances map to table rows.
- **Advantages of JPA over JDBC?** Less boilerplate, database independence, automatic dirty checking, caching, relationship mapping, portable queries.

## Summary

- JDBC requires manual SQL and mapping; JPA automates it with annotations.
- JPA = standard; Hibernate = implementation; Spring Data JPA = repositories on top.
- Entities map to tables, fields to columns, references to foreign keys.

Next: set up JPA and save your first entity.
