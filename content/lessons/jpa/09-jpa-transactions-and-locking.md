---
title: JPA Transactions and Locking
slug: jpa-transactions-and-locking
subject: jpa
lesson: 9
difficulty: advanced
excerpt: Keep data correct when many users act at once. Learn ACID transactions, rollback, the lost update problem, optimistic locking with @Version, pessimistic locks and isolation levels.
---

Two users buy the last seat at the same moment. A teacher and an admin edit the same course simultaneously. Without care, one change overwrites the other or your stock goes negative. **Transactions** and **locking** keep data correct under concurrency.

## ACID in one table

| Property | Meaning | Example |
|---|---|---|
| **Atomicity** | all or nothing | transfer: debit AND credit, or neither |
| **Consistency** | rules always hold | balance never negative, FKs valid |
| **Isolation** | concurrent transactions don't see each other's half-done work | |
| **Durability** | once committed, it survives crashes | |

## Commit and rollback

```java
EntityTransaction tx = em.getTransaction();
try {
    tx.begin();
    Account from = em.find(Account.class, 1L);
    Account to = em.find(Account.class, 2L);
    from.withdraw(500);          // throws if the balance is too low
    to.deposit(500);
    tx.commit();                 // both UPDATEs together
} catch (RuntimeException e) {
    if (tx.isActive()) tx.rollback();   // undo everything
    throw e;
}
```

In Spring this whole pattern becomes one annotation, `@Transactional`, animated in the Spring course.

## The lost update problem

```text
time   Admin (tx 1)                    Teacher (tx 2)
 1     read course: price 499          read course: price 499
 2     set title "Java Pro"            set price 399
 3     commit → UPDATE title, price=499
 4                                     commit → UPDATE title="Java", price=399
                                       ❌ the admin's title change is lost
```

Both transactions read the same version, and the last commit silently wins. JPA's solution is optimistic locking.

## Optimistic locking with @Version

Add a version field:

```java
package com.vitalstack;

import jakarta.persistence.*;

@Entity
public class Course {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String title;
    private int price;

    @Version                      // incremented on every update
    private int version;

    protected Course() { }
    public Course(String title, int price) { this.title = title; this.price = price; }
    public Long getId() { return id; }
    public void setTitle(String t) { title = t; }
    public void setPrice(int p) { price = p; }
    public int getVersion() { return version; }
}
```

Every UPDATE now includes the version in its `WHERE` clause:

```text
update Course set price=?, title=?, version=? where id=? and version=?
```

If another transaction already changed the row, the version doesn't match, 0 rows are updated, and JPA throws `OptimisticLockException`. Let's prove it:

```java
package com.vitalstack;

import jakarta.persistence.*;

public class Main {
    public static void main(String[] args) {
        EntityManagerFactory emf = Persistence.createEntityManagerFactory("school");
        EntityManager setup = emf.createEntityManager();
        setup.getTransaction().begin();
        Course c = new Course("Java", 499);
        setup.persist(c);
        setup.getTransaction().commit();
        setup.close();

        EntityManager admin = emf.createEntityManager();
        EntityManager teacher = emf.createEntityManager();
        admin.getTransaction().begin();
        teacher.getTransaction().begin();
        Course a = admin.find(Course.class, c.getId());     // both read version 0
        Course t = teacher.find(Course.class, c.getId());

        a.setTitle("Java Pro");
        admin.getTransaction().commit();                     // version 0 → 1
        System.out.println("admin saved, version " + a.getVersion());

        t.setPrice(399);
        try {
            teacher.getTransaction().commit();               // still thinks version 0
        } catch (RollbackException e) {
            System.out.println("teacher's save rejected: " + e.getCause().getClass().getSimpleName());
        }
        admin.close(); teacher.close(); emf.close();
    }
}
```

```text
admin saved, version 1
teacher's save rejected: OptimisticLockException
```

Nothing was lost. The app can now tell the teacher "Someone else changed this course. Please reload and try again."

**Optimistic** = assume conflicts are rare, detect them at write time. No database locks are held while users think, so it scales very well. It's the default choice for web apps.

## Pessimistic locking

When conflicts are **frequent** and retrying is expensive (seat booking, stock counters), lock the row while you work:

```java
Seat seat = em.find(Seat.class, seatId, LockModeType.PESSIMISTIC_WRITE);   // SELECT ... FOR UPDATE
if (seat.isBooked()) throw new IllegalStateException("Already booked");
seat.book(userId);
// the lock is released at commit/rollback; other transactions wait at their find()
```

| | Optimistic (`@Version`) | Pessimistic (`PESSIMISTIC_WRITE`) |
|---|---|---|
| How | version check at UPDATE | database row lock (`FOR UPDATE`) |
| Conflicts | detected, exception, retry | prevented, others wait |
| Best for | most web edits, low contention | hot rows, money/stock, short transactions |
| Risks | retries needed | waiting, deadlocks, timeouts |

Keep pessimistic transactions **very short**, and set a lock timeout (`jakarta.persistence.lock.timeout`).

::tip A simple and robust alternative for counters is a single atomic UPDATE: `update Product p set p.stock = p.stock - 1 where p.id = :id and p.stock > 0`. If it returns 0, the item is sold out. No race condition possible.

## Isolation levels

Databases offer isolation levels that trade correctness for concurrency:

| Level | Dirty read | Non-repeatable read | Phantom read |
|---|---|---|---|
| READ UNCOMMITTED | possible | possible | possible |
| READ COMMITTED (PostgreSQL, Oracle default) | ✗ | possible | possible |
| REPEATABLE READ (MySQL default) | ✗ | ✗ | possible* |
| SERIALIZABLE | ✗ | ✗ | ✗ |

\*MySQL InnoDB prevents most phantoms at REPEATABLE READ.

- **Dirty read:** seeing another transaction's uncommitted change.
- **Non-repeatable read:** reading the same row twice and getting different values.
- **Phantom read:** re-running a query and getting new rows.

Usually you keep the database default and use `@Version` or explicit locks where it matters.

## Transaction tips

- Keep transactions **short**: never wait for user input or call slow external APIs inside one.
- Read-only operations can use read-only transactions (Spring: `@Transactional(readOnly = true)`) for small optimisations.
- Handle `OptimisticLockException` with a friendly message or an automatic retry.

## Exercise

1. Add `@Version` to an entity and reproduce the conflict above.
2. Write a "transfer" with rollback when the source balance is too low. Verify neither balance changed.
3. Implement "book seat" using `PESSIMISTIC_WRITE` and explain what a second concurrent booking does.
4. Rewrite a stock decrement as one atomic JPQL update.

::hint Hint for 2 | Throw an exception after the withdraw when the balance goes negative, catch it, roll back, then reload both accounts in a new EntityManager to check.

## Interview corner

- **What is optimistic locking?** Detecting concurrent modifications using a version column; the stale update fails with OptimisticLockException.
- **Optimistic vs pessimistic?** Optimistic detects conflicts at commit without locks; pessimistic locks rows to prevent conflicts.
- **What is a lost update?** Two transactions read the same data and the later write overwrites the earlier one unknowingly.
- **Default isolation in MySQL and PostgreSQL?** MySQL InnoDB: REPEATABLE READ; PostgreSQL: READ COMMITTED.

## Summary

- Transactions make changes atomic; roll back on errors.
- `@Version` gives optimistic locking: stale updates fail instead of overwriting.
- Pessimistic locks (`FOR UPDATE`) suit short, hot-spot operations.
- Isolation levels control what concurrent transactions can see.

Next course: **Hibernate**, the engine behind JPA, with fetching strategies, the N+1 problem and caching.
