---
title: Useful Hibernate Annotations - Soft Delete, Timestamps, Formula and More
slug: hibernate-useful-annotations
subject: hibernate
lesson: 6
difficulty: advanced
excerpt: Hibernate-only annotations that save real work - @CreationTimestamp, @UpdateTimestamp, soft delete with @SoftDelete and @SQLRestriction, @Formula, @Immutable, @NaturalId, @ColumnDefault, @Check and Envers auditing.
---

Beyond standard JPA, Hibernate ships annotations that solve common problems in one line. You'll find these all over real Spring Boot codebases.

## Automatic timestamps

```java
package com.vitalstack;

import jakarta.persistence.*;
import org.hibernate.annotations.*;
import java.time.Instant;

@Entity
@SoftDelete                                            // DELETE becomes "mark as deleted"
public class Post {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String title;
    private int likes;

    @CreationTimestamp                                  // set once on INSERT
    private Instant createdAt;

    @UpdateTimestamp                                    // refreshed on every UPDATE
    private Instant updatedAt;

    @Formula("likes * 2 + length(title)")               // computed by SQL, read-only
    private int score;

    protected Post() { }
    public Post(String title, int likes) { this.title = title; this.likes = likes; }
    public Long getId() { return id; }
    public String getTitle() { return title; }
    public int getScore() { return score; }
    public Instant getCreatedAt() { return createdAt; }
}
```

No `@PrePersist` code needed for `createdAt`/`updatedAt`.

## Soft delete

Many apps never truly delete data (for audits, undo, analytics). Hibernate 6.4+ has `@SoftDelete`: `em.remove()` runs an UPDATE that sets a `deleted` flag, and **every query automatically skips deleted rows**.

```java
package com.vitalstack;

import jakarta.persistence.*;

public class Main {
    public static void main(String[] args) {
        EntityManagerFactory emf = Persistence.createEntityManagerFactory("school");
        EntityManager em = emf.createEntityManager();
        em.getTransaction().begin();
        Post hello = new Post("Hello", 10);
        em.persist(hello);
        em.persist(new Post("Learn Hibernate", 25));
        em.getTransaction().commit();
        System.out.println("created at set? " + (hello.getCreatedAt() != null));

        em.getTransaction().begin();
        em.remove(hello);                                   // soft delete
        em.getTransaction().commit();
        em.clear();

        em.createQuery("select p from Post p", Post.class).getResultList()
          .forEach(p -> System.out.println(p.getTitle() + " (score " + p.getScore() + ")"));
        Object rows = em.createNativeQuery("select count(*) from Post").getSingleResult();
        System.out.println("rows really in the table: " + rows);
        em.close();
        emf.close();
    }
}
```

```text
created at set? true
update Post set deleted=true where id=? and deleted=false
Learn Hibernate (score 65)
rows really in the table: 2
```

The JPQL query returned only the non-deleted post, while the table still has both rows. For older Hibernate versions the same idea is done with `@SQLDelete(sql = "UPDATE post SET deleted = true WHERE id = ?")` plus `@SQLRestriction("deleted = false")` (formerly `@Where`).

::tip Soft-deleted rows still count for unique constraints. If users can re-register with an email after deleting their account, include the deleted flag in the unique index or anonymise the old row.

## @Formula: computed columns

`@Formula` maps a field to an SQL expression evaluated in the SELECT: totals, scores, counts from other tables:

```java
@Formula("(select count(*) from comment c where c.post_id = id)")
private int commentCount;
```

It's read-only and computed at load time. Keep formulas cheap: they run for every load.

## @Immutable

```java
@Entity
@Immutable                 // never updated: Hibernate skips dirty checking
public class ExchangeRate { ... }
```

Great for audit logs, history tables and database views mapped as entities.

## @NaturalId

A unique business key (email, ISBN, slug) that Hibernate can look up by and cache:

```java
@NaturalId
@Column(unique = true)
private String slug;

Post p = session.bySimpleNaturalId(Post.class).load("learn-hibernate");
```

## Schema helpers

```java
@ColumnDefault("0")                              // DEFAULT 0 in the DDL
private int likes;

@Check(constraints = "price >= 0")               // CHECK constraint
private int price;

@JdbcTypeCode(SqlTypes.JSON)                     // store a Map or record as JSON (PostgreSQL jsonb etc.)
private Map<String, Object> settings;
```

## Envers: full change history

Add `hibernate-envers` and annotate an entity with `@Audited`. Hibernate keeps an `_AUD` table with every version of every row, plus who/when via a revision entity:

```java
@Entity
@Audited
public class Course { ... }

AuditReader reader = AuditReaderFactory.get(em);
List<Number> revisions = reader.getRevisions(Course.class, courseId);
Course old = reader.find(Course.class, courseId, revisions.get(0));   // the course as it was
```

Perfect for "who changed the price and when?" requirements in banking, HR and admin panels.

## Quick reference

| Need | Annotation |
|---|---|
| created / updated time | `@CreationTimestamp`, `@UpdateTimestamp` |
| soft delete | `@SoftDelete` (6.4+) or `@SQLDelete` + `@SQLRestriction` |
| computed value | `@Formula` |
| never updated | `@Immutable` |
| business key lookup | `@NaturalId` |
| update only changed columns | `@DynamicUpdate` |
| load lazy collections in batches | `@BatchSize` |
| DB default / check | `@ColumnDefault`, `@Check` |
| JSON column | `@JdbcTypeCode(SqlTypes.JSON)` |
| history | `@Audited` (Envers) |

## Exercise

1. Add `@CreationTimestamp` and `@UpdateTimestamp` to an entity and update it twice. Check the values.
2. Implement soft delete on a `Comment` entity and prove that queries skip deleted comments.
3. Add a `@Formula` that counts the lessons of a course.
4. Map a read-only `@Immutable` entity to a database view `course_stats`.

::hint Hint for 3 | `@Formula("(select count(*) from Lesson l where l.course_id = id)")`. Inside a formula, `id` refers to the current row's column.

## Interview corner

- **How do you implement soft delete in Hibernate?** `@SoftDelete` in 6.4+, or `@SQLDelete` to turn DELETE into UPDATE plus `@SQLRestriction` to filter queries.
- **What does @Formula do?** Maps a read-only property to an SQL expression evaluated when the entity is loaded.
- **What is Hibernate Envers?** An auditing module that records entity versions in audit tables.
- **@CreationTimestamp vs @PrePersist?** Both set values at insert; the annotation needs no code and is Hibernate-specific.

## Summary

- Timestamps, soft delete, formulas, immutability and natural ids are one annotation each.
- Envers adds full history with `@Audited`.
- These are Hibernate-specific: fine for most apps, which use Hibernate anyway.

Next: best practices and a review of the whole course.
