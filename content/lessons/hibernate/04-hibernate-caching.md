---
title: Hibernate Caching - First-Level, Second-Level and Query Cache
slug: hibernate-caching
subject: hibernate
lesson: 4
difficulty: advanced
excerpt: Understand Hibernate's three caches, enable the second-level cache with JCache, choose cache concurrency strategies, cache queries and collections, and learn when caching helps or hurts.
---

Reading the same data from the database again and again is wasteful. Hibernate has three caching layers. One is always on; the other two are opt-in and powerful when used for the **right** data.

## The three caches

| Cache | Scope | Default | Stores |
|---|---|---|---|
| **First-level** | one Session / EntityManager | always on | managed entities (the persistence context) |
| **Second-level (L2)** | whole SessionFactory, shared by all sessions | off | entity data by id (and collections, natural ids) |
| **Query cache** | whole SessionFactory | off | ids returned by specific queries |

```text
em.find(Course, 1)
   │
   ├─▶ 1st-level (this session)?   hit → return the same object
   ├─▶ 2nd-level (shared)?         hit → build the object, no SQL
   └─▶ database                    SELECT → store in both caches
```

You met the first-level cache in the JPA course. Let's look at the second-level cache.

## Enabling the second-level cache

Hibernate needs a cache provider. **JCache (JSR-107)** with **Ehcache** or **Caffeine** is the common choice:

```xml
<dependency>
  <groupId>org.hibernate.orm</groupId>
  <artifactId>hibernate-jcache</artifactId>
  <version>6.5.2.Final</version>
</dependency>
<dependency>
  <groupId>com.github.ben-manes.caffeine</groupId>
  <artifactId>jcache</artifactId>
  <version>3.1.8</version>
</dependency>
```

Properties:

```xml
<property name="hibernate.cache.use_second_level_cache" value="true"/>
<property name="hibernate.cache.region.factory_class" value="jcache"/>
<property name="jakarta.persistence.sharedCache.mode" value="ENABLE_SELECTIVE"/>
<!-- create cache regions automatically (otherwise Hibernate logs a warning per region) -->
<property name="hibernate.javax.cache.missing_cache_strategy" value="create"/>
```

`ENABLE_SELECTIVE` means only entities marked `@Cacheable` are cached. Then mark the entity:

```java
package com.vitalstack;

import jakarta.persistence.*;
import org.hibernate.annotations.Cache;
import org.hibernate.annotations.CacheConcurrencyStrategy;

@Entity
@Cacheable
@Cache(usage = CacheConcurrencyStrategy.READ_WRITE)
public class Country {
    @Id
    private String code;
    private String name;

    protected Country() { }
    public Country(String code, String name) { this.code = code; this.name = name; }
    public String getName() { return name; }
}
```

## Proof: no SQL on the second session

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

        EntityManager setup = emf.createEntityManager();
        setup.getTransaction().begin();
        setup.persist(new Country("IN", "India"));
        setup.getTransaction().commit();
        setup.close();
        emf.getCache().evictAll();                     // start with an empty L2 cache
        stats.clear();

        for (int i = 1; i <= 3; i++) {
            EntityManager em = emf.createEntityManager();   // a NEW session each time
            System.out.println("session " + i + ": " + em.find(Country.class, "IN").getName());
            em.close();
        }
        System.out.println("SQL statements: " + stats.getPrepareStatementCount());
        System.out.println("L2 hits: " + stats.getSecondLevelCacheHitCount()
                + ", misses: " + stats.getSecondLevelCacheMissCount());
        emf.close();
    }
}
```

```text
select c1_0.code,c1_0.name from Country c1_0 where c1_0.code=?
session 1: India
session 2: India
session 3: India
SQL statements: 1
L2 hits: 2, misses: 1
```

Three different sessions, **one** query.

## Concurrency strategies

| Strategy | Use for |
|---|---|
| `READ_ONLY` | data that never changes (countries, currencies): fastest; updates throw an error |
| `NONSTRICT_READ_WRITE` | rarely updated, short staleness acceptable |
| `READ_WRITE` | read-mostly data that does change; uses soft locks for consistency |
| `TRANSACTIONAL` | JTA/XA environments |

## Caching collections

Collections are cached separately; mark them too:

```java
@OneToMany(mappedBy = "course")
@Cache(usage = CacheConcurrencyStrategy.READ_WRITE)
private List<Lesson> lessons;
```

The collection cache stores the **ids** of the lessons; the lessons themselves must also be `@Cacheable`, or each one is loaded with a query (an N+1 in disguise!).

## The query cache

`find` by id uses the L2 cache, but queries don't, unless you enable the query cache and mark the query:

```xml
<property name="hibernate.cache.use_query_cache" value="true"/>
```

```java
List<Country> all = em.createQuery("select c from Country c order by c.name", Country.class)
        .setHint("org.hibernate.cacheable", true)
        .getResultList();
```

The query cache stores the **list of ids** for that query and parameters. Hibernate **invalidates it whenever any row in the tables involved changes**, so it only pays off for queries on tables that rarely change.

## When NOT to cache

- Frequently updated data (orders, stock, balances): constant invalidation, little benefit, stale-data risk.
- Huge tables accessed randomly: the cache just fills memory.
- Data changed by other applications or direct SQL: Hibernate won't know, so cached data goes stale.
- Multiple app servers with a local (in-memory) cache: each server has its own copy. Use a distributed cache (Hazelcast, Infinispan, Redis-based) or short expiry times.

Good candidates: **reference data** (countries, categories, plans, settings), read-mostly product catalogues, user roles.

::tip Measure before and after. Many apps get bigger wins from fixing N+1 problems and adding indexes than from caching. In Spring Boot, `@Cacheable` from Spring's cache abstraction (caching method results) is often simpler than Hibernate's L2 cache for API responses.

## Exercise

1. Enable the L2 cache for a `Category` entity and confirm with statistics that the second `find` in a new session runs no SQL.
2. Update the category in one session, then load it in another. Is the new value returned? Why?
3. Cache the query `select c from Category c order by c.name` and check `getQueryCacheHitCount()`.

::hint Hint for 2 | Yes: with READ_WRITE, Hibernate updates (or invalidates) the cache entry when the transaction commits, so other sessions see the new value.

## Interview corner

- **First-level vs second-level cache?** First-level is per Session and always on; second-level is shared across sessions, optional, and needs a provider.
- **What is the query cache?** A cache of query results (entity ids) keyed by query and parameters, invalidated when related tables change.
- **Cache concurrency strategies?** READ_ONLY, NONSTRICT_READ_WRITE, READ_WRITE, TRANSACTIONAL.
- **What data should you cache?** Read-mostly, small to medium reference data that's accessed often.

## Summary

- First-level cache: per session, automatic.
- Second-level cache: shared, opt-in with `@Cacheable` + a provider like JCache/Caffeine/Ehcache.
- Query cache: caches result ids, invalidated on any change to the tables.
- Cache reference data; don't cache volatile data.

Next: batch processing and performance tuning.
