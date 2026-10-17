---
title: Hibernate Lazy Loading, Proxies and LazyInitializationException
slug: hibernate-lazy-loading-and-proxies
subject: hibernate
lesson: 2
difficulty: intermediate
excerpt: How Hibernate loads related data only when you touch it, what proxies and persistent collections are, why LazyInitializationException happens, and four correct ways to fix it.
---

Load a course and Hibernate doesn't immediately load its 50 lessons, its teacher and their profile. It loads them **lazily**: only when your code first touches them. This saves huge amounts of work, but it's also behind the most famous Hibernate error: `LazyInitializationException`.

## Lazy vs eager

- **EAGER**: load the related data immediately, always.
- **LAZY**: load it on first access.

Defaults (from JPA): `@ManyToOne`/`@OneToOne` are EAGER; `@OneToMany`/`@ManyToMany` are LAZY. Best practice: **LAZY everywhere**, then load what each use case needs.

## How lazy loading works

For a lazy `@ManyToOne`, Hibernate puts a **proxy** in the field: a generated subclass of your entity that holds only the id. For a lazy collection, it puts a **PersistentBag/PersistentSet**: a list that knows how to load itself.

```text
Lesson ──course──▶ Course$HibernateProxy (id = 1, not initialised)
                         │ first call to getTitle()
                         ▼
                   SELECT ... FROM course WHERE id = 1  → now initialised
```

Calling `getId()` on a proxy doesn't trigger a query (the id is already known). Any other getter does, **if the Session is still open**.

## See it happen

```java
package com.vitalstack;

import jakarta.persistence.*;
import java.util.ArrayList;
import java.util.List;

@Entity
public class Course {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String title;

    @OneToMany(mappedBy = "course", cascade = CascadeType.ALL)
    private List<Lesson> lessons = new ArrayList<>();

    protected Course() { }
    public Course(String title) { this.title = title; }
    public void add(Lesson l) { lessons.add(l); l.course = this; }
    public Long getId() { return id; }
    public String getTitle() { return title; }
    public List<Lesson> getLessons() { return lessons; }
}
```

```java
package com.vitalstack;

import jakarta.persistence.*;

@Entity
public class Lesson {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String title;

    @ManyToOne(fetch = FetchType.LAZY)
    Course course;

    protected Lesson() { }
    public Lesson(String title) { this.title = title; }
    public String getTitle() { return title; }
    public Course getCourse() { return course; }
}
```

```java
package com.vitalstack;

import jakarta.persistence.*;
import org.hibernate.Hibernate;
import org.hibernate.LazyInitializationException;

public class Main {
    public static void main(String[] args) {
        EntityManagerFactory emf = Persistence.createEntityManagerFactory("school");
        EntityManager em = emf.createEntityManager();
        em.getTransaction().begin();
        Course java = new Course("Java");
        java.add(new Lesson("Variables"));
        java.add(new Lesson("Loops"));
        em.persist(java);
        em.getTransaction().commit();
        em.close();

        EntityManager em2 = emf.createEntityManager();
        System.out.println("-- load course");
        Course c = em2.find(Course.class, java.getId());
        System.out.println("lessons loaded? " + Hibernate.isInitialized(c.getLessons()));
        System.out.println("-- touch lessons");
        System.out.println("count: " + c.getLessons().size());          // SELECT lessons now
        System.out.println("-- load a lesson");
        Lesson l = em2.find(Lesson.class, 1L);
        System.out.println("course is a proxy: " + l.getCourse().getClass().getSimpleName().contains("Proxy")
                + ", id without query: " + l.getCourse().getId());
        em2.close();                                                       // Session closed

        EntityManager em3 = emf.createEntityManager();
        Course detached = em3.find(Course.class, java.getId());
        em3.close();
        try {
            detached.getLessons().size();                                  // too late!
        } catch (LazyInitializationException e) {
            System.out.println("ERROR: " + e.getMessage());
        }
        emf.close();
    }
}
```

Output:

```text
-- load course
select c1_0.id,c1_0.title from Course c1_0 where c1_0.id=?
lessons loaded? false
-- touch lessons
select l1_0.course_id,l1_0.id,l1_0.title from Lesson l1_0 where l1_0.course_id=?
count: 2
-- load a lesson
course is a proxy: false, id without query: 1
select c1_0.id,c1_0.title from Course c1_0 where c1_0.id=?
ERROR: failed to lazily initialize a collection of role: com.vitalstack.Course.lessons: could not initialize proxy - no Session
```

(The lesson and its course are already in this Session's persistence context, so no SQL runs and `getCourse()` returns the real object here, not a proxy. In a fresh Session, you'd get a `Course$HibernateProxy`.)

## Why LazyInitializationException happens

The entity was loaded in a Session that's now **closed**. Later code (often a view template, a JSON serializer, or a `toString()`) touches a lazy relationship, and there's no Session left to run the query.

Typical in Spring: a `@Transactional` service returns an entity, the transaction ends, and the controller or Jackson then accesses `course.getLessons()`.

## Four correct fixes

**1. Fetch what you need in the query (JOIN FETCH):**

```java
Course c = em.createQuery(
    "select c from Course c join fetch c.lessons where c.id = :id", Course.class)
    .setParameter("id", id).getSingleResult();
```

**2. Entity graphs:** declare what to load without changing the JPQL:

```java
EntityGraph<Course> graph = em.createEntityGraph(Course.class);
graph.addAttributeNodes("lessons");
Course c = em.find(Course.class, id, Map.of("jakarta.persistence.fetchgraph", graph));
```

In Spring Data: `@EntityGraph(attributePaths = "lessons")` on a repository method.

**3. Map to a DTO inside the transaction:** return exactly the data the screen needs, so nothing lazy escapes:

```java
record CourseView(String title, List<String> lessonTitles) { }
// inside the @Transactional service method:
return new CourseView(c.getTitle(), c.getLessons().stream().map(Lesson::getTitle).toList());
```

**4. Initialise explicitly** inside the transaction: `Hibernate.initialize(c.getLessons())`.

## Fixes to avoid

- **Switching to EAGER**: now *every* query loads the lessons, even where unneeded, and it often causes N+1 queries (next lesson).
- **Open Session in View** (Spring's `spring.jpa.open-in-view=true`, the default!): keeps the Session open until the view renders, so lazy loading "just works", but queries run from the web layer, connections are held longer, and N+1 problems hide. Spring Boot logs a warning for this. Set it to `false` and fetch properly.
- `hibernate.enable_lazy_load_no_trans=true`: opens a new connection for every lazy access. An anti-pattern.

::tip Proxies are subclasses, so `course.getClass() == Course.class` can be false and `instanceof` checks on proxies of a hierarchy can surprise you. Use `Hibernate.getClass(obj)` or `Hibernate.unproxy(obj)` when the real class matters, and write `equals` with `instanceof` and getters, not direct field access.

## Exercise

1. Run the example, then add `join fetch` to load a course with lessons and access them after closing the EntityManager. No exception?
2. Use an entity graph to load a lesson with its course.
3. Write a `CourseSummary` record with title and lesson count using a JPQL projection (no lazy loading at all).

::hint Hint for 3 | `select new com.vitalstack.CourseSummary(c.title, size(c.lessons)) from Course c` or `count(l)` with a `left join c.lessons l group by c.title`.

## Interview corner

- **What is lazy loading?** Deferring the loading of associated data until it's first accessed, using proxies and persistent collections.
- **What causes LazyInitializationException?** Accessing an uninitialised lazy association after the Session/persistence context is closed.
- **How do you fix it?** JOIN FETCH, entity graphs, DTO projections, or initialising inside the transaction, not EAGER or open-session-in-view.
- **What is a Hibernate proxy?** A runtime subclass of the entity holding the id, which loads the real data on first access.

## Summary

- LAZY loads on access via proxies and persistent collections; prefer it everywhere.
- Accessing lazy data after the Session closes throws LazyInitializationException.
- Fix with JOIN FETCH, entity graphs or DTOs; avoid EAGER and open-session-in-view.

Next: the N+1 query problem, animated.
