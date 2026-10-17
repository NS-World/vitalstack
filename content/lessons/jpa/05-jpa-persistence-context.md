---
title: The Persistence Context and First-Level Cache
slug: jpa-persistence-context
subject: jpa
lesson: 5
difficulty: intermediate
excerpt: Discover why calling find() twice runs only one query, how the identity guarantee works, when Hibernate flushes, write-behind, and how to process large data without running out of memory.
---

Every `EntityManager` has a **persistence context**: a map of all the managed entities it has loaded, keyed by type and id. It's also called the **first-level cache**. It explains why JPA sometimes runs fewer queries than you expect, and sometimes more memory than you'd like.

## Watch the cache work

::viz persistence-context

```java
package com.vitalstack;

import jakarta.persistence.*;

@Entity
public class Course {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String title;
    private int price;

    protected Course() { }
    public Course(String title, int price) { this.title = title; this.price = price; }

    public Long getId() { return id; }
    public String getTitle() { return title; }
    public int getPrice() { return price; }
    public void setPrice(int price) { this.price = price; }
}
```

```java
package com.vitalstack;

import jakarta.persistence.*;

public class Main {
    public static void main(String[] args) {
        EntityManagerFactory emf = Persistence.createEntityManagerFactory("school");
        EntityManager setup = emf.createEntityManager();
        setup.getTransaction().begin();
        setup.persist(new Course("Java", 499));
        setup.getTransaction().commit();
        setup.close();

        EntityManager em = emf.createEntityManager();
        System.out.println("-- first find");
        Course a = em.find(Course.class, 1L);          // SELECT
        System.out.println("-- second find");
        Course b = em.find(Course.class, 1L);          // no SQL: from the persistence context
        System.out.println("same object? " + (a == b));

        em.getTransaction().begin();
        a.setPrice(399);
        System.out.println("-- commit");
        em.getTransaction().commit();                  // UPDATE
        em.close();

        EntityManager em2 = emf.createEntityManager();
        System.out.println("-- new EntityManager");
        Course c = em2.find(Course.class, 1L);         // new context: SELECT again
        System.out.println(c.getTitle() + " now costs " + c.getPrice());
        em2.close();
        emf.close();
    }
}
```

Output:

```text
-- first find
select c1_0.id,c1_0.price,c1_0.title from Course c1_0 where c1_0.id=?
-- second find
same object? true
-- commit
update Course set price=?,title=? where id=?
-- new EntityManager
select c1_0.id,c1_0.price,c1_0.title from Course c1_0 where c1_0.id=?
Java now costs 399
```

## The identity guarantee

Within one persistence context, **one database row = one Java object**. `a == b` is true. This prevents two different copies of the same course with conflicting changes. Queries respect it too: if a JPQL query returns a row that's already managed, you get the existing object (the query still runs; the cache only short-circuits `find` by id).

## Scope

The persistence context lives as long as its EntityManager:

- **Plain JPA:** until you call `em.close()`.
- **Spring:** usually one per transaction (`@Transactional` method). Each web request gets a fresh one.

So the first-level cache is **short-lived and private**. It's not a shared application cache: that's the optional **second-level cache** (Hibernate course).

## Flushing: when SQL is actually sent

JPA uses **write-behind**: `persist`, changes and `remove` are collected and sent later, at **flush**. Flush happens:

1. When the transaction **commits**.
2. **Before a JPQL/native query** whose results could be affected (flush mode AUTO), so the query sees your pending changes.
3. When you call `em.flush()` explicitly.

Flushing sends SQL but doesn't commit: a later rollback still undoes everything.

Why delay? Hibernate can order statements to avoid constraint problems, skip updates for values changed back, and **batch** many inserts into one round trip (with `hibernate.jdbc.batch_size`).

## Memory: the hidden cost

Every managed entity, plus its snapshot for dirty checking, stays in memory until the context ends. Loading 1,000,000 rows in one transaction can crash your app with `OutOfMemoryError` and makes each flush slower.

For big jobs, process in chunks and clear the context:

```java
int batch = 50;
em.getTransaction().begin();
for (int i = 1; i <= 100_000; i++) {
    em.persist(new Course("Course " + i, 99));
    if (i % batch == 0) {
        em.flush();       // send the pending inserts
        em.clear();       // detach everything: free memory
    }
}
em.getTransaction().commit();
```

For read-only reports, use DTO projections or read-only queries (lesson 7) so entities aren't tracked at all.

::tip "Why does my change not show up?" and "Why is the old value still there?" often come down to the persistence context: a value you changed is pending (not flushed yet), or the entity was loaded before someone else changed the row. Use `em.refresh(entity)` to reload it.

## Common mistakes

- Thinking the first-level cache shares data across users or requests (it doesn't).
- Running JDBC/native SQL updates and expecting already-loaded entities to change (they don't; refresh or clear).
- Huge transactions that load or insert everything at once.
- Calling `flush()` everywhere "to be safe": it removes batching benefits.

## Exercise

1. Run the example and confirm the second `find` prints no SQL.
2. Inside one EntityManager, persist a course, then run `select c from Course c` before committing. Is the new course in the results? Why?
3. Insert 1,000 courses with and without `flush/clear` every 50 and compare the time.

::hint Hint for 2 | Yes: with flush mode AUTO, Hibernate flushes pending inserts before running a query that touches the Course table.

## Interview corner

- **What is the persistence context?** The set of managed entity instances for an EntityManager, guaranteeing one instance per row and tracking changes.
- **What is the first-level cache?** The persistence context acting as a cache for `find` by id; enabled always, scoped to the EntityManager/transaction.
- **When does flush happen?** At commit, before relevant queries (AUTO), or on explicit `flush()`.
- **flush vs commit?** Flush sends SQL; commit ends the transaction and makes changes permanent.

## Summary

- The persistence context keeps one managed object per row and remembers snapshots.
- Repeated `find` by id is served from it; queries still hit the database.
- SQL is written at flush (commit, before queries, or explicit).
- Clear the context in big batch jobs to save memory.

Next: relationships between entities.
