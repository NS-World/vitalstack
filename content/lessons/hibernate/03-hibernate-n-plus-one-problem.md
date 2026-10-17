---
title: The N+1 Query Problem and How to Fix It
slug: hibernate-n-plus-one-problem
subject: hibernate
lesson: 3
difficulty: advanced
excerpt: Watch one query turn into N+1 in an animation, measure it with Hibernate statistics, and fix it with JOIN FETCH, entity graphs, @BatchSize and DTO projections. The most asked Hibernate interview question.
---

Your page lists 100 authors with their book counts. Locally it's fast. In production, with real data, it takes 4 seconds. The logs show **101 queries** for one page. Welcome to the **N+1 problem**, the number one Hibernate performance issue and the number one Hibernate interview question.

## What N+1 means

1. **1 query** loads the list of N authors.
2. Then, for **each** author, accessing a lazy relationship (`a.getBooks()`) fires **1 more query**.

Total: **N + 1** queries. With 5 authors that's 6; with 500 it's 501 round trips to the database.

Change the number of authors and switch between lazy loading and JOIN FETCH:

::viz n-plus-one {"n": 5, "mode": "lazy loading"}

The code looks innocent, which is why the problem is so common:

```java
List<Author> authors = em.createQuery("select a from Author a", Author.class).getResultList();
for (Author a : authors) {
    System.out.println(a.getName() + ": " + a.getBooks().size());   // a query per author!
}
```

It often hides inside templates, JSON serialisation or `toString()`, far from the original query.

## Measure it

Turn on Hibernate statistics and count the SQL statements. The demo below loads 5 authors with three books each, four different ways:

```java
package com.vitalstack;

import jakarta.persistence.*;
import java.util.ArrayList;
import java.util.List;

@Entity
public class Author {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String name;

    @OneToMany(mappedBy = "author", cascade = CascadeType.ALL)
    private List<Book> books = new ArrayList<>();

    protected Author() { }
    public Author(String name) { this.name = name; }
    public void add(Book b) { books.add(b); b.author = this; }
    public String getName() { return name; }
    public List<Book> getBooks() { return books; }
}
```

```java
package com.vitalstack;

import jakarta.persistence.*;

@Entity
public class Book {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String title;

    @ManyToOne(fetch = FetchType.LAZY)
    Author author;

    protected Book() { }
    public Book(String title) { this.title = title; }
}
```

```java
package com.vitalstack;

public record AuthorBooks(String name, long books) { }
```

```java
package com.vitalstack;

import jakarta.persistence.*;
import org.hibernate.SessionFactory;
import org.hibernate.stat.Statistics;
import java.util.List;
import java.util.function.Function;

public class Main {
    static EntityManagerFactory emf;

    static void measure(String label, Function<EntityManager, List<Author>> query) {
        Statistics stats = emf.unwrap(SessionFactory.class).getStatistics();
        stats.clear();
        EntityManager em = emf.createEntityManager();
        long totalBooks = 0;
        for (Author a : query.apply(em)) totalBooks += a.getBooks().size();
        em.close();
        System.out.printf("%-22s %2d queries (%d books)%n", label, stats.getPrepareStatementCount(), totalBooks);
    }

    public static void main(String[] args) {
        emf = Persistence.createEntityManagerFactory("school");
        emf.unwrap(SessionFactory.class).getStatistics().setStatisticsEnabled(true);
        EntityManager em = emf.createEntityManager();
        em.getTransaction().begin();
        for (int i = 1; i <= 5; i++) {
            Author a = new Author("Author " + i);
            for (int j = 1; j <= 3; j++) a.add(new Book("Book " + i + "." + j));
            em.persist(a);
        }
        em.getTransaction().commit();
        em.close();

        measure("lazy loading (N+1)", e -> e.createQuery("select a from Author a", Author.class).getResultList());

        measure("JOIN FETCH", e -> e.createQuery(
                "select a from Author a join fetch a.books", Author.class).getResultList());

        measure("entity graph", e -> {
            EntityGraph<Author> g = e.createEntityGraph(Author.class);
            g.addAttributeNodes("books");
            return e.createQuery("select a from Author a", Author.class)
                    .setHint("jakarta.persistence.fetchgraph", g).getResultList();
        });

        EntityManager dto = emf.createEntityManager();
        emf.unwrap(SessionFactory.class).getStatistics().clear();
        List<AuthorBooks> rows = dto.createQuery(
                "select new com.vitalstack.AuthorBooks(a.name, count(b)) from Author a left join a.books b group by a.name",
                AuthorBooks.class).getResultList();
        System.out.printf("%-22s %2d queries (%d rows)%n", "DTO projection",
                emf.unwrap(SessionFactory.class).getStatistics().getPrepareStatementCount(), rows.size());
        dto.close();
        emf.close();
    }
}
```

Output:

```text
lazy loading (N+1)      6 queries (15 books)
JOIN FETCH              1 queries (15 books)
entity graph            1 queries (15 books)
DTO projection          1 queries (5 rows)
```

Same data, **6× fewer round trips**. With 500 authors: 501 vs 1.

## Fix 1: JOIN FETCH

```java
select a from Author a join fetch a.books
```

Loads authors and books in **one** SQL join. Hibernate 6 removes duplicate authors automatically (older versions needed `select distinct a`).

## Fix 2: Entity graphs

Keep the query, declare what to fetch separately. In Spring Data JPA:

```java
@EntityGraph(attributePaths = "books")
List<Author> findAll();
```

## Fix 3: Batch fetching (@BatchSize)

When you can't change the query (e.g. it's generated), make lazy loading smarter: load the books for **many authors at once** using `IN (...)`:

```java
@OneToMany(mappedBy = "author")
@BatchSize(size = 25)
private List<Book> books;
```

Or globally: `hibernate.default_batch_fetch_size=25`. N+1 becomes 1 + ⌈N/25⌉ queries: 500 authors → 21 queries. Many teams turn this on globally as a safety net.

## Fix 4: DTO projections

If the screen only needs names and counts, don't load entities at all. One aggregate query returns exactly what's needed, with no tracking overhead.

## Which fix when?

| Situation | Best fix |
|---|---|
| A specific screen needs parents + children | JOIN FETCH or entity graph |
| Read-only list / report / API response | DTO projection |
| Many places, generic code, safety net | `@BatchSize` / `default_batch_fetch_size` |
| Paginated parents with child collections | page the parents, then batch-fetch or a second query with `where a in :page` |

::tip Two traps: fetching **two** collections with JOIN FETCH at once (`join fetch a.books join fetch a.awards`) creates a Cartesian product (or a `MultipleBagFetchException` for Lists). Fetch one collection per query. And JOIN FETCH a collection + `setMaxResults` makes Hibernate paginate **in memory**.

## Detecting N+1 early

- Watch the SQL log in development (`show_sql`), or use a tool like **p6spy** to log SQL with parameters.
- Assert statement counts in tests with `Statistics.getPrepareStatementCount()`, or libraries like **Hypersistence Utils** / **datasource-proxy**.
- Disable open-session-in-view so lazy loads in the web layer fail loudly instead of quietly running hundreds of queries.

## Exercise

1. Run the demo with 50 authors. How many queries does lazy loading run?
2. Add `@BatchSize(size = 10)` on `books` and re-run the lazy version. Predict the count first.
3. Add `Book → Publisher` (`@ManyToOne(fetch = LAZY)`) and print each book's publisher. Fix the new N+1 with a fetch join `join fetch a.books b join fetch b.publisher`.

::hint Hint for 2 | 1 query for authors + ⌈50 / 10⌉ = 5 batch queries for books = 6 queries.

## Interview corner

- **What is the N+1 problem?** One query loads N parents, then N extra queries load each parent's lazy association.
- **How do you fix it?** JOIN FETCH, entity graphs, batch fetching (@BatchSize), or DTO projections.
- **Does EAGER fix N+1?** No. EAGER on a collection or ToOne relationship is often loaded with separate queries per row, still N+1, and now on every query.
- **How do you detect it?** SQL logging, Hibernate statistics, query-count assertions in tests.

## Summary

- N+1 = 1 query for the list + 1 per item for a lazy relationship.
- Fix with JOIN FETCH, entity graphs, @BatchSize or DTO projections.
- Measure with statistics; fetch one collection per query; don't paginate fetch-joined collections.

Next: the second-level cache.
