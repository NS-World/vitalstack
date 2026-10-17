---
title: Hibernate Best Practices and Common Mistakes
slug: hibernate-best-practices
subject: hibernate
lesson: 7
difficulty: advanced
excerpt: A practical checklist from real projects - mapping, fetching, transactions, equals/hashCode for entities, DTOs, schema migrations, logging and testing - plus a slow-page case study you can fix step by step.
---

You now know how Hibernate works. This final lesson turns that into habits. These are the rules experienced teams follow, and the mistakes code reviewers look for.

## Mapping

- ✅ `FetchType.LAZY` on every `@ManyToOne` and `@OneToOne`.
- ✅ `@Enumerated(EnumType.STRING)` for enums.
- ✅ `SEQUENCE` ids with an `allocationSize` (or UUIDs) where the database supports it; IDENTITY on MySQL is fine but disables insert batching.
- ✅ Bidirectional relationships only when you navigate both ways; keep both sides in sync with helper methods.
- ✅ `Set` for `@ManyToMany`; turn link tables with extra data into entities.
- ❌ `CascadeType.ALL` or `REMOVE` on `@ManyToOne` or many-to-many.
- ❌ Relationships in `toString()`, `equals()` or `hashCode()`.

## equals and hashCode for entities

Entities are tricky: the id is `null` before persisting, and proxies are subclasses. A safe pattern compares by id when it exists and keeps hashCode constant:

```java
@Override
public boolean equals(Object o) {
    if (this == o) return true;
    if (!(o instanceof Course other)) return false;
    return id != null && id.equals(other.getId());     // getter: works through proxies
}

@Override
public int hashCode() {
    return getClass().hashCode();                        // constant: safe across persist
}
```

Or base equality on an immutable natural id (email, ISBN, slug). Never use mutable fields.

## Fetching

- ✅ Decide what to load **per use case**: JOIN FETCH, entity graphs or DTO projections.
- ✅ Turn on `hibernate.default_batch_fetch_size` (e.g. 16-50) as a safety net.
- ✅ `spring.jpa.open-in-view=false`.
- ❌ EAGER "to fix" LazyInitializationException.
- ❌ JOIN FETCH of two collections at once, or JOIN FETCH + pagination on a collection.

## Transactions and units of work

- ✅ Transactions at the **service** layer (`@Transactional`), short and focused.
- ✅ Read-only transactions for queries.
- ✅ `@Version` on entities edited by users.
- ❌ HTTP calls, emails or file processing inside transactions.
- ❌ Catching exceptions inside a transaction and continuing as if nothing happened (the Session may be in an inconsistent state; roll back).

## API boundaries

- ✅ Return **DTOs/records** from controllers, not entities. It avoids lazy-loading errors, infinite JSON recursion, leaking internal fields (password hashes!) and accidental updates.
- ✅ Validate input with Bean Validation before it reaches entities.

## Schema

- ✅ Manage the schema with **Flyway** or **Liquibase** migrations; set `hbm2ddl.auto=validate` (or `none`) in production.
- ✅ Add **indexes** for foreign keys and frequent filters.
- ❌ `hbm2ddl.auto=update` in production: it never drops or renames safely and can't migrate data.

## Observability and testing

- ✅ Log SQL in development; use p6spy or datasource-proxy to see parameters.
- ✅ Assert query counts in integration tests for critical endpoints.
- ✅ Test against the same database engine as production with **Testcontainers** (H2 hides dialect differences).
- ✅ Enable `hibernate.generate_statistics` temporarily when investigating.

## Case study: a slow course page

The course page takes 3 seconds. The log shows:

```text
select ... from course where id=?
select ... from teacher where id=?
select ... from lesson where course_id=?
select ... from quiz where lesson_id=?      ← ×40 (one per lesson)
select ... from review where course_id=?    ← loads 2,000 reviews
select ... from student where id=?          ← ×2,000 (one per review author)
```

What's wrong and how do we fix it?

1. **Quizzes N+1** (40 queries): fetch lessons with quizzes in one query (`join fetch l.quiz`), or set `default_batch_fetch_size`.
2. **2,000 reviews loaded** to show 5 on the page and an average: use a **paginated DTO query** for the latest 5 reviews and an aggregate query `select avg(r.rating), count(r)` for the summary.
3. **Students N+1** (2,000 queries): the DTO query joins the student name directly: `select new ReviewView(r.rating, r.text, s.name) from Review r join r.student s where r.course.id = :id order by r.createdAt desc`.
4. **Teacher**: a lazy `@ManyToOne` fetched in the course query: `join fetch c.teacher`.

Result: 4,042 queries → 4 queries, 3 s → 40 ms. No caching, no new hardware: just correct fetching.

::tip When a page is slow, count the queries before anything else. In most Hibernate apps, the fix is fetching less and in fewer round trips.

## Interview quick-fire

- **Hibernate vs JPA?** Implementation vs specification.
- **Entity states?** Transient, managed, detached, removed.
- **What is dirty checking?** Automatic UPDATEs for changed managed entities at flush.
- **N+1 and fixes?** JOIN FETCH, entity graphs, batch fetching, DTOs.
- **LazyInitializationException?** Lazy access after the session closed; fetch what you need inside the transaction.
- **First vs second-level cache?** Per session vs shared, opt-in.
- **get vs load / find vs getReference?** Immediate SELECT (null if missing) vs proxy (exception when used).
- **Optimistic locking?** `@Version` column checked in UPDATE's WHERE.
- **How to batch inserts?** `jdbc.batch_size`, sequences, flush/clear, StatelessSession.

## Exercise

1. Review an entity class you've written against the mapping checklist. What would you change?
2. Write the DTO query for the case study's "latest 5 reviews with student names".
3. Write a Flyway migration `V2__add_course_level.sql` that adds a `level` column with a default.

::hint Hint for 2 | Use `setMaxResults(5)` with `order by r.createdAt desc` and a constructor expression into a `ReviewView` record.

## Summary

- Lazy by default, fetch per use case, DTOs at the edges.
- Short service-layer transactions, `@Version` for user edits.
- Migrations for schema, indexes for speed, query counts in tests.

**Congratulations, you've finished the Hibernate course!** Next, learn **Spring** and **Spring Boot**, where all of this runs with `@Transactional`, Spring Data repositories and almost no boilerplate.
