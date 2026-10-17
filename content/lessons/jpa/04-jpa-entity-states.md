---
title: EntityManager and Entity States
slug: jpa-entity-states
subject: jpa
lesson: 4
difficulty: intermediate
excerpt: Learn the four entity states (transient, managed, detached, removed) with an animation, use persist, find, merge, remove and detach correctly, and see automatic dirty checking generate UPDATEs.
---

The most important idea in JPA is that every entity object is in one of **four states**. Once you understand the states, the "magic" (automatic updates, LazyInitializationException, merge surprises) becomes predictable.

## The four states, animated

Step through a course's whole life: created, saved, edited, closed, merged back and deleted. Watch which SQL runs at each step:

::viz entity-states

| State | Meaning | Tracked? | In the database? |
|---|---|---|---|
| **Transient** (new) | created with `new`, unknown to JPA | no | no |
| **Managed** | attached to an open persistence context | **yes**: changes become SQL | yes (or will be at flush) |
| **Detached** | was managed, but the EntityManager closed or detached it | no | yes |
| **Removed** | scheduled for deletion | yes | until flush/commit |

```text
            new                persist()                close()/detach()/clear()
 (none) ───────▶ TRANSIENT ─────────────▶ MANAGED ─────────────────────────▶ DETACHED
                                ▲  │  ▲                                          │
                    find()/query│  │  └──────────────── merge() ─────────────────┘
                                   │ remove()
                                   ▼
                                REMOVED ──commit──▶ deleted
```

## Running it for real

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
    public void setTitle(String title) { this.title = title; }
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

        EntityManager em = emf.createEntityManager();
        em.getTransaction().begin();
        Course c = new Course("Java", 499);                 // TRANSIENT
        em.persist(c);                                       // MANAGED
        System.out.println("contains? " + em.contains(c));   // true
        em.getTransaction().commit();                        // INSERT

        em.getTransaction().begin();
        c.setTitle("Java Basics");                           // no method call needed...
        em.getTransaction().commit();                        // ...UPDATE (dirty checking)
        em.close();                                          // c is now DETACHED

        c.setTitle("Java 101");                              // nobody is watching this change

        EntityManager em2 = emf.createEntityManager();
        em2.getTransaction().begin();
        Course managed = em2.merge(c);                       // SELECT, copy state onto a MANAGED copy
        System.out.println("same object? " + (managed == c));   // false!
        em2.getTransaction().commit();                       // UPDATE

        em2.getTransaction().begin();
        em2.remove(managed);                                 // REMOVED
        em2.getTransaction().commit();                       // DELETE
        System.out.println("found after delete: " + em2.find(Course.class, c.getId()));
        em2.close();
        emf.close();
    }
}
```

Output:

```text
insert into Course (price,title,id) values (?,?,default)
contains? true
update Course set price=?,title=? where id=?
select c1_0.id,c1_0.price,c1_0.title from Course c1_0 where c1_0.id=?
same object? false
update Course set price=?,title=? where id=?
delete from Course where id=?
select c1_0.id,c1_0.price,c1_0.title from Course c1_0 where c1_0.id=?
found after delete: null
```

## Dirty checking: updates without save()

When an entity becomes managed, Hibernate keeps a **snapshot** of its values. At flush/commit, it compares the current values with the snapshot and generates UPDATEs for anything that changed. There is no `update()` method in JPA, and you don't need one.

::tip This is why calling `save()` after modifying a managed entity in Spring Data is unnecessary inside a `@Transactional` method. And it's why accidentally modifying an entity (e.g. formatting a name for display) inside a transaction can silently change your database!

## The EntityManager methods

| Method | Does | SQL |
|---|---|---|
| `persist(newEntity)` | transient → managed | INSERT at flush (IDENTITY ids insert immediately) |
| `find(Class, id)` | load by primary key; null if missing | SELECT (unless already in the context) |
| `getReference(Class, id)` | a lazy proxy; no SELECT until used | later, or EntityNotFoundException |
| `merge(detached)` | copy state onto a managed instance and **return it** | SELECT if not loaded, UPDATE at flush |
| `remove(managed)` | managed → removed | DELETE at flush |
| `detach(e)` / `clear()` / `close()` | stop tracking one / all entities | none |
| `refresh(e)` | reload from the database, discarding changes | SELECT |
| `flush()` | send pending SQL now (still inside the transaction) | INSERT/UPDATE/DELETE |

## The merge trap

`merge` **returns** a managed copy; the object you passed stays detached:

```java
Course managed = em.merge(detached);
detached.setPrice(1);   // ❌ ignored: not tracked
managed.setPrice(1);    // ✅ tracked
```

Also, `persist` on a detached entity (one with an id from the database) throws `EntityExistsException`/`PersistentObjectException`. New → persist; existing and detached → merge.

## remove needs a managed entity

```java
em.remove(em.find(Course.class, 5L));              // ✅
em.remove(detachedCourse);                          // ❌ IllegalArgumentException: Removing a detached instance
em.remove(em.merge(detachedCourse));                // ✅ (but a find is clearer)
```

## Where detached entities come from

- The EntityManager closed (end of a web request).
- Entities were serialised (sent to a client, stored in a session).
- `clear()` or `detach()` was called.

Accessing a **lazy** relationship on a detached entity throws the famous `LazyInitializationException` (Hibernate course).

## Common mistakes

- Using the argument of `merge` instead of its return value.
- Calling `persist` for updates.
- Removing a detached entity.
- Long-lived EntityManagers holding thousands of managed entities (memory + slow flushes). Keep units of work short.

## Exercise

1. Run the example. Then comment out `em.getTransaction().commit()` after `setTitle("Java Basics")` and see what changes.
2. Load a course with `find`, call `em.detach(course)`, change the price and commit. Is there an UPDATE?
3. Use `getReference` for a non-existing id and print the title. What exception do you get, and when?

::hint Hint for 2 | No UPDATE: after detach the entity isn't tracked, so dirty checking ignores it.

## Interview corner

- **Entity states?** Transient, managed (persistent), detached, removed.
- **persist vs merge?** persist makes a new entity managed (same instance); merge copies a detached entity's state onto a managed instance and returns that instance.
- **What is dirty checking?** At flush, the provider compares managed entities with their snapshots and issues UPDATEs for changes.
- **find vs getReference?** find hits the database (or cache) and returns null if missing; getReference returns a proxy without a query and fails later if the row doesn't exist.

## Summary

- Entities are transient, managed, detached or removed.
- Managed entities are tracked: changes become UPDATEs automatically.
- Use persist for new objects, merge's return value for detached ones, remove on managed ones.

Next: the persistence context, the cache behind all this.
