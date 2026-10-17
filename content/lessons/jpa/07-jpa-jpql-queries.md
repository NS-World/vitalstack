---
title: JPQL Queries, Joins, Projections and Pagination
slug: jpa-jpql-queries
subject: jpa
lesson: 7
difficulty: intermediate
excerpt: Query entities with JPQL using parameters, joins, JOIN FETCH, aggregates, GROUP BY, DTO projections with records, pagination, named queries, native SQL and the Criteria API.
---

`find()` only loads by id. For everything else (search, filters, reports), JPA has **JPQL**: a query language that looks like SQL but works with **entities and fields**, not tables and columns.

## The setup

```java
package com.vitalstack;

import jakarta.persistence.*;

@Entity
public class Course {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String title;
    private String level;
    private int price;

    @ManyToOne(fetch = FetchType.LAZY)
    private Teacher teacher;

    protected Course() { }
    public Course(String title, String level, int price, Teacher teacher) {
        this.title = title; this.level = level; this.price = price; this.teacher = teacher;
    }
    public String getTitle() { return title; }
    public int getPrice() { return price; }
    public Teacher getTeacher() { return teacher; }
}
```

```java
package com.vitalstack;

import jakarta.persistence.*;

@Entity
public class Teacher {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String name;

    protected Teacher() { }
    public Teacher(String name) { this.name = name; }
    public String getName() { return name; }
}
```

```java
package com.vitalstack;

public record LevelStats(String level, long courses, double avgPrice) { }
```

## Queries in action

```java
package com.vitalstack;

import jakarta.persistence.*;
import java.util.List;

public class Main {
    public static void main(String[] args) {
        EntityManagerFactory emf = Persistence.createEntityManagerFactory("school");
        EntityManager em = emf.createEntityManager();
        em.getTransaction().begin();
        Teacher ravi = new Teacher("Ravi"), meera = new Teacher("Meera");
        em.persist(ravi); em.persist(meera);
        em.persist(new Course("Java", "Beginner", 499, ravi));
        em.persist(new Course("Spring Boot", "Intermediate", 799, ravi));
        em.persist(new Course("React", "Beginner", 599, meera));
        em.persist(new Course("Hibernate", "Advanced", 899, meera));
        em.getTransaction().commit();
        em.clear();

        // 1. Filter with a named parameter
        List<Course> cheap = em.createQuery(
                "select c from Course c where c.price < :max order by c.price", Course.class)
            .setParameter("max", 700)
            .getResultList();
        cheap.forEach(c -> System.out.println("cheap: " + c.getTitle() + " " + c.getPrice()));

        // 2. Join through a relationship
        List<String> ravisCourses = em.createQuery(
                "select c.title from Course c join c.teacher t where t.name = :name", String.class)
            .setParameter("name", "Ravi")
            .getResultList();
        System.out.println("Ravi teaches " + ravisCourses);

        // 3. JOIN FETCH: load courses AND their teachers in one query
        List<Course> all = em.createQuery(
                "select c from Course c join fetch c.teacher order by c.title", Course.class)
            .getResultList();
        all.forEach(c -> System.out.println(c.getTitle() + " by " + c.getTeacher().getName()));

        // 4. Aggregates + GROUP BY straight into a record (DTO projection)
        List<LevelStats> stats = em.createQuery(
                "select new com.vitalstack.LevelStats(c.level, count(c), avg(c.price)) " +
                "from Course c group by c.level order by c.level", LevelStats.class)
            .getResultList();
        stats.forEach(System.out::println);

        // 5. Pagination
        List<Course> page2 = em.createQuery("select c from Course c order by c.id", Course.class)
            .setFirstResult(2)          // skip 2 rows (page size 2 → page 2)
            .setMaxResults(2)
            .getResultList();
        System.out.println("page 2: " + page2.stream().map(Course::getTitle).toList());

        // 6. A single value
        Long total = em.createQuery("select sum(c.price) from Course c", Long.class).getSingleResult();
        System.out.println("total = " + total);
        em.close();
        emf.close();
    }
}
```

Output:

```text
cheap: Java 499
cheap: React 599
Ravi teaches [Java, Spring Boot]
Hibernate by Meera
Java by Ravi
React by Meera
Spring Boot by Ravi
LevelStats[level=Advanced, courses=1, avgPrice=899.0]
LevelStats[level=Beginner, courses=2, avgPrice=549.0]
LevelStats[level=Intermediate, courses=1, avgPrice=799.0]
page 2: [React, Hibernate]
total = 2796
```

## JPQL vs SQL

| SQL | JPQL |
|---|---|
| `SELECT * FROM course WHERE price < 700` | `select c from Course c where c.price < :max` |
| table names, column names | entity names, field names (case-sensitive!) |
| `JOIN teacher t ON t.id = c.teacher_id` | `join c.teacher t` (follows the relationship) |
| database-specific functions | portable functions: `upper`, `lower`, `length`, `concat`, `coalesce`… |

JPQL can't do everything SQL can (e.g. some window functions), but Hibernate 6 supports a lot of modern SQL in HQL.

## Always use parameters

```java
// ❌ SQL injection risk, and no statement caching
em.createQuery("select c from Course c where c.title = '" + title + "'");
// ✅
em.createQuery("select c from Course c where c.title = :title").setParameter("title", title);
```

## JOIN vs JOIN FETCH

- `join c.teacher t` lets you **filter** on the teacher, but the teachers aren't loaded: accessing `getTeacher().getName()` later triggers extra queries.
- `join fetch c.teacher` **loads** the teachers in the same query. This is the main cure for the N+1 problem (Hibernate course).

::tip Don't use `join fetch` together with pagination on a collection (`join fetch c.lessons` + `setMaxResults`): Hibernate has to load everything and paginate in memory (it warns you). Paginate the parents first, then fetch their collections in a second query.

## DTO projections

`select new com.vitalstack.LevelStats(...)` creates plain objects instead of managed entities. They're not tracked (no snapshots, no dirty checking), so they're **faster and lighter** for read-only screens and reports. Records are perfect for this.

## Named queries

Define reusable queries on the entity (validated at startup!):

```java
@Entity
@NamedQuery(name = "Course.byLevel", query = "select c from Course c where c.level = :level")
public class Course { ... }

em.createNamedQuery("Course.byLevel", Course.class).setParameter("level", "Beginner").getResultList();
```

## Bulk updates and deletes

```java
int updated = em.createQuery("update Course c set c.price = c.price * 9 / 10 where c.level = :lvl")
        .setParameter("lvl", "Beginner")
        .executeUpdate();
```

Bulk statements go straight to the database and **bypass the persistence context**: entities already loaded keep their old values. Run them in their own transaction or `clear()` afterwards.

## Native SQL

When you need database-specific SQL:

```java
List<Object[]> rows = em.createNativeQuery(
        "SELECT level, COUNT(*) FROM course GROUP BY level").getResultList();
```

## Criteria API (dynamic queries)

For search screens where filters are optional, the type-safe Criteria API builds queries in code:

```java
CriteriaBuilder cb = em.getCriteriaBuilder();
CriteriaQuery<Course> q = cb.createQuery(Course.class);
Root<Course> c = q.from(Course.class);
List<Predicate> where = new ArrayList<>();
if (level != null) where.add(cb.equal(c.get("level"), level));
if (maxPrice != null) where.add(cb.le(c.get("price"), maxPrice));
q.select(c).where(where.toArray(Predicate[]::new));
List<Course> result = em.createQuery(q).getResultList();
```

In Spring Data JPA you'll mostly use derived query methods and `@Query` instead, with Specifications for dynamic filters.

## Common mistakes

- Using table/column names instead of entity/field names in JPQL.
- `getSingleResult()` with no or multiple rows → `NoResultException` / `NonUniqueResultException`.
- Forgetting `distinct` when fetch-joining collections in old Hibernate versions (Hibernate 6 de-duplicates automatically).
- Loading whole entities for a read-only list where a projection would do.

## Exercise

1. Find all courses whose title contains "a" (case-insensitive), sorted by price descending.
2. Count courses per teacher using a record projection `TeacherCount(name, count)`.
3. Write a paginated query for page N with page size 10.
4. Give every Advanced course a 20% discount with one bulk update.

::hint Hint for 1 | `where lower(c.title) like :q` with `.setParameter("q", "%a%")`.

## Interview corner

- **JPQL vs SQL?** JPQL queries entities and fields, is portable, and returns objects; SQL queries tables/columns.
- **JOIN vs JOIN FETCH?** JOIN for filtering/joining; JOIN FETCH also initialises the association in the same query.
- **How do you paginate in JPA?** `setFirstResult(offset)` and `setMaxResults(size)`.
- **Why use DTO projections?** They avoid managed-entity overhead and fetch only needed columns.

## Summary

- JPQL works with entities, fields and relationships; always use parameters.
- JOIN FETCH loads related data in one query; projections into records are fast and safe for reads.
- Paginate with `setFirstResult`/`setMaxResults`; bulk updates bypass the persistence context.

Next: inheritance mapping and lifecycle callbacks.
