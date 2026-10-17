---
title: Hibernate Batch Processing and Performance Tuning
slug: hibernate-batching-and-performance
subject: hibernate
lesson: 5
difficulty: advanced
excerpt: Make Hibernate fast. JDBC batching, sequence allocation, flush and clear in loops, StatelessSession, bulk updates, read-only queries, @DynamicUpdate, connection pools and a performance checklist.
---

Hibernate is fast when used well and painfully slow when used carelessly. Most slowness comes from a handful of causes: too many round trips, too much tracked data, and loading more than needed. This lesson covers the fixes senior developers apply.

## 1. JDBC batching

By default every INSERT/UPDATE is a separate round trip to the database. With batching, Hibernate sends many statements in one go:

```xml
<property name="hibernate.jdbc.batch_size" value="50"/>
<property name="hibernate.order_inserts" value="true"/>   <!-- group inserts by table -->
<property name="hibernate.order_updates" value="true"/>
```

In Spring Boot: `spring.jpa.properties.hibernate.jdbc.batch_size=50`.

::tip Batching inserts does **not** work with `GenerationType.IDENTITY`, because Hibernate must run each INSERT immediately to get the generated id. Use `SEQUENCE` with an `allocationSize` (PostgreSQL, Oracle, H2, MariaDB) to batch inserts. On MySQL, which has no sequences, use UUIDs or accept unbatched inserts.

## 2. Insert many rows: flush and clear

```java
package com.vitalstack;

import jakarta.persistence.*;

@Entity
public class Product {
    @Id
    @GeneratedValue(strategy = GenerationType.SEQUENCE, generator = "product_seq")
    @SequenceGenerator(name = "product_seq", allocationSize = 50)
    private Long id;
    private String name;
    private int price;

    protected Product() { }
    public Product(String name, int price) { this.name = name; this.price = price; }
}
```

```java
package com.vitalstack;

import jakarta.persistence.*;
import org.hibernate.SessionFactory;
import org.hibernate.stat.Statistics;

public class Main {
    public static void main(String[] args) {
        EntityManagerFactory emf = Persistence.createEntityManagerFactory("school");
        Statistics stats = emf.unwrap(SessionFactory.class).getStatistics();
        stats.setStatisticsEnabled(true);

        EntityManager em = emf.createEntityManager();
        long start = System.currentTimeMillis();
        em.getTransaction().begin();
        for (int i = 1; i <= 10_000; i++) {
            em.persist(new Product("Product " + i, 100 + i % 900));
            if (i % 50 == 0) {         // same as batch_size
                em.flush();            // send this batch
                em.clear();            // forget the managed objects: memory stays flat
            }
        }
        em.getTransaction().commit();
        em.close();
        System.out.println("10,000 rows in " + (System.currentTimeMillis() - start) + " ms");
        System.out.println("SQL statements prepared: " + stats.getPrepareStatementCount());
        emf.close();
    }
}
```

Output with `hibernate.jdbc.batch_size=50` (H2 in memory, times vary):

```text
10,000 rows in 561 ms
SQL statements prepared: 401
```

Without the batch setting the same loop prepares **10,201** statements: one per row plus the sequence calls. Against a real database over the network, every statement is a round trip, so batching often makes inserts 5-10× faster. (H2 runs in memory, so the time difference is small here.)

Without `clear()`, all 10,000 products and their snapshots stay in the persistence context, and every flush dirty-checks all of them: the loop gets slower and slower. With a sequence `allocationSize` of 50, Hibernate fetches ids once per 50 rows instead of once per row.

## 3. StatelessSession for big jobs

A `StatelessSession` has **no persistence context**: no first-level cache, no dirty checking, no cascades, no lazy loading. Perfect for imports and exports:

```java
SessionFactory sf = emf.unwrap(SessionFactory.class);
try (StatelessSession ss = sf.openStatelessSession()) {
    Transaction tx = ss.beginTransaction();
    for (ProductRow row : rowsFromCsv) {
        ss.insert(new Product(row.name(), row.price()));   // immediate INSERT (batched if configured)
    }
    tx.commit();
}
```

## 4. Bulk updates and deletes

Don't load 50,000 entities to change one column:

```java
// ❌ loads everything into memory, 50,000 UPDATEs
for (Product p : em.createQuery("select p from Product p", Product.class).getResultList()) p.raisePrice(10);

// ✅ one statement
em.createQuery("update Product p set p.price = p.price + 10 where p.category = :cat")
  .setParameter("cat", "BOOKS")
  .executeUpdate();
```

Remember: bulk statements skip the persistence context, callbacks and `@Version` increments (unless you include `versioned`/version updates yourself).

## 5. Read-only queries

When you load entities only to display them, tell Hibernate not to keep snapshots:

```java
List<Product> list = em.createQuery("select p from Product p", Product.class)
        .setHint("org.hibernate.readOnly", true)
        .getResultList();
```

In Spring: `@Transactional(readOnly = true)` on the service method does this for the whole transaction. Even better for screens: **DTO projections** (only the needed columns, nothing tracked).

## 6. Fetch only what you need

- Lazy everywhere; JOIN FETCH or entity graphs per use case (lessons 2-3).
- Projections for lists and reports.
- Paginate. Never load "all orders" for a list page.

## 7. @DynamicUpdate

By default Hibernate's UPDATE sets **all** columns (one cached statement per entity). For wide tables where only one column changes, `@DynamicUpdate` generates SQL with only the changed columns:

```java
@Entity
@DynamicUpdate
public class UserProfile { /* 40 columns */ }
```

Trade-off: less data sent, but a statement per combination of changed columns. Use it for wide tables or to reduce lock conflicts on specific columns.

## 8. The database still matters

- **Indexes** on columns used in `WHERE`, `JOIN` and `ORDER BY` (foreign keys especially). Hibernate doesn't create them for you unless you declare `@Index` in `@Table(indexes = ...)`.
- Check slow queries with `EXPLAIN`.
- A properly sized **connection pool** (HikariCP): more connections isn't always faster; start around 10.

## A performance checklist

| Check | Tool |
|---|---|
| How many queries does this request run? | SQL log, `Statistics`, p6spy |
| Any N+1 patterns? | query counts in tests |
| Loading entities for read-only screens? | switch to DTO projections |
| Big inserts/updates? | batching + flush/clear, StatelessSession, bulk JPQL |
| IDENTITY ids blocking batching? | SEQUENCE with allocationSize |
| Missing indexes? | `EXPLAIN`, slow query log |
| Open session in view on? | `spring.jpa.open-in-view=false` |

## Exercise

1. Run the insert demo with and without `hibernate.jdbc.batch_size=50` and compare times.
2. Remove `em.clear()` and insert 50,000 rows. What happens to the time per batch?
3. Rewrite a "deactivate users inactive for 1 year" loop as one bulk JPQL update.
4. Add `@Table(indexes = @Index(columnList = "category"))` to `Product` and check the generated DDL.

::hint Hint for 2 | Each flush dirty-checks every managed entity, so time per batch keeps growing (and memory too). With clear() it stays constant.

## Interview corner

- **How do you insert a million rows with Hibernate?** JDBC batching, SEQUENCE ids with allocationSize, flush/clear every batch, or StatelessSession (or plain JDBC/COPY for extreme cases).
- **Why doesn't batching work with IDENTITY?** The id is only known after each INSERT executes, so Hibernate can't delay and group them.
- **What is a StatelessSession?** A session without persistence context, caching or dirty checking, for bulk operations.
- **How do you make reads cheaper?** Read-only transactions/hints, DTO projections, pagination, proper fetching and indexes.

## Summary

- Batch writes (`jdbc.batch_size` + SEQUENCE ids) and flush/clear in loops.
- Use StatelessSession or bulk JPQL for large jobs.
- Read-only hints and DTO projections make reads lighter.
- Indexes and query counts matter more than clever tricks.

Next: handy Hibernate-specific annotations.
