---
title: Hibernate Introduction and Architecture
slug: hibernate-introduction
subject: hibernate
lesson: 1
difficulty: intermediate
excerpt: What Hibernate adds on top of JPA, its architecture (SessionFactory, Session, Transaction, dialects), how to use the native Session API, HQL, and the Hibernate-only features you'll meet in real projects.
---

**Hibernate** is the engine that powers most Java applications' database access. It's the default JPA provider in Spring Boot, and has been around since 2001. In the JPA course you used Hibernate through the standard API. This course looks under the hood: how it works, its extra features, and how to make it **fast**.

## JPA vs Hibernate (again, but deeper)

```text
Your code ──▶ JPA API (EntityManager, @Entity, JPQL)      ← portable standard
                 │
                 ▼  implemented by
            Hibernate ORM (Session, HQL, extra annotations, caching, batching…)
                 │
                 ▼
            JDBC driver ──▶ MySQL / PostgreSQL / Oracle / H2
```

Everything in the JPA course works the same. Hibernate adds:

- The native **Session** API (a superset of EntityManager).
- **HQL**: a superset of JPQL.
- Extra annotations: `@NaturalId`, `@BatchSize`, `@Formula`, `@DynamicUpdate`, `@SQLRestriction`, `@Immutable`, `@CreationTimestamp`…
- Second-level and query caches.
- **Envers** auditing, multi-tenancy, statistics, StatelessSession and more.

## Architecture

| Component | JPA name | Role |
|---|---|---|
| `SessionFactory` | `EntityManagerFactory` | built once at startup from mappings + config; thread-safe; holds metadata, connection pool, second-level cache |
| `Session` | `EntityManager` | a unit of work; holds the persistence context; **not** thread-safe |
| `Transaction` | `EntityTransaction` | begin/commit/rollback |
| `Dialect` | – | generates database-specific SQL (pagination, types, functions) |
| `ConnectionProvider` | – | gets JDBC connections (HikariCP in Spring Boot) |

In fact, in Hibernate 6 `SessionFactory` **extends** `EntityManagerFactory` and `Session` **extends** `EntityManager`. You can switch between them:

```java
Session session = entityManager.unwrap(Session.class);                 // JPA → Hibernate
SessionFactory sf = entityManagerFactory.unwrap(SessionFactory.class);
```

## Using Hibernate natively

```java
package com.vitalstack;

import jakarta.persistence.*;
import org.hibernate.Session;
import org.hibernate.SessionFactory;

public class Main {
    public static void main(String[] args) {
        SessionFactory sf = Persistence.createEntityManagerFactory("school").unwrap(SessionFactory.class);

        sf.inTransaction(session -> {                          // begin + commit/rollback for you
            session.persist(new Course("Java", 499));
            session.persist(new Course("Hibernate", 899));
        });

        try (Session session = sf.openSession()) {
            Course c = session.get(Course.class, 1L);            // like em.find
            System.out.println("get: " + c.getTitle());

            Course byTitle = session.bySimpleNaturalId(Course.class).load("Hibernate");   // @NaturalId lookup
            System.out.println("natural id: " + byTitle.getPrice());

            long count = session.createSelectionQuery("select count(*) from Course", Long.class)   // HQL
                                .getSingleResult();
            System.out.println("count: " + count);
        }
        sf.close();
    }
}
```

```java
package com.vitalstack;

import jakarta.persistence.*;
import org.hibernate.annotations.NaturalId;

@Entity
public class Course {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @NaturalId                       // a unique business key Hibernate can look up and cache
    private String title;
    private int price;

    protected Course() { }
    public Course(String title, int price) { this.title = title; this.price = price; }
    public String getTitle() { return title; }
    public int getPrice() { return price; }
}
```

Output:

```text
get: Java
natural id: 899
count: 2
```

Notice HQL lets you skip `select c` in simple queries (`from Course`), and `sessionFactory.inTransaction(...)` handles commit and rollback.

## Configuration options you'll see

| Property (Spring Boot: `spring.jpa.properties.` prefix) | Purpose |
|---|---|
| `hibernate.show_sql`, `hibernate.format_sql` | print SQL (development) |
| `hibernate.hbm2ddl.auto` | `none` / `validate` / `update` / `create-drop` |
| `hibernate.jdbc.batch_size` | batch inserts/updates (lesson 5) |
| `hibernate.default_batch_fetch_size` | load lazy collections in batches (lesson 3) |
| `hibernate.cache.use_second_level_cache` | enable 2nd-level cache (lesson 4) |
| `hibernate.generate_statistics` | count queries, cache hits, timings |

## save() vs persist(), load() vs get()

Old Hibernate code uses `session.save()`, `saveOrUpdate()`, `update()` and `load()`. These are **deprecated** in Hibernate 6. Use the JPA-style methods:

| Old | Use instead |
|---|---|
| `save(e)` | `persist(e)` |
| `update(detached)` / `saveOrUpdate(e)` | `merge(e)` |
| `load(Class, id)` | `getReference(Class, id)` |
| `get(Class, id)` | `get` or `find` (same) |

Interviewers still ask "save vs persist": `save` returns the generated id immediately and works outside transactions in old versions; `persist` returns void and follows JPA semantics.

::tip In Spring Boot you almost never touch Session directly. But knowing the architecture helps you read Hibernate's logs and tune its properties, which is where real performance wins come from.

## Exercise

1. Unwrap a `Session` from an `EntityManager` and call `session.getStatistics()` after a few queries (enable `hibernate.generate_statistics`).
2. Add `@NaturalId` to a `Student.email` field and load a student by email.
3. Rewrite a JPQL query as HQL using the short `from Course where price > 500` form.

::hint Hint for 1 | `sf.getStatistics().getPrepareStatementCount()` tells you how many SQL statements ran. It's great for catching N+1 problems in tests.

## Interview corner

- **What is Hibernate?** An ORM framework and the most popular JPA implementation.
- **SessionFactory vs Session?** SessionFactory is heavyweight, thread-safe, created once; Session is a lightweight, non-thread-safe unit of work with its own persistence context.
- **What is a dialect?** The component that adapts generated SQL to a specific database.
- **get vs load?** get hits the database and returns null if missing; load (getReference) returns a proxy and throws ObjectNotFoundException when used if the row doesn't exist.

## Summary

- Hibernate implements JPA and adds Session, HQL, extra annotations, caching and tuning options.
- SessionFactory once, Session per unit of work; both are JPA objects underneath.
- Prefer persist/merge/getReference over deprecated save/update/load.

Next: lazy loading and proxies, the source of many Hibernate surprises.
