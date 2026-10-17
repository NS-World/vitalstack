---
title: JPA Relationships - OneToMany, ManyToOne, ManyToMany
slug: jpa-relationships
subject: jpa
lesson: 6
difficulty: intermediate
excerpt: Map relationships between entities - @ManyToOne, bidirectional @OneToMany with mappedBy, the owning side, cascade, orphanRemoval, @ManyToMany with a join table, @OneToOne and default fetch types.
---

Real data is connected: a course has many lessons, a student enrolls in many courses, a user has one profile. JPA maps these links between objects to foreign keys and join tables.

## The four relationship types

| Annotation | Example | Database |
|---|---|---|
| `@ManyToOne` | many lessons → one course | `lesson.course_id` foreign key |
| `@OneToMany` | one course → many lessons | the same FK, seen from the other side |
| `@ManyToMany` | students ↔ courses | a join table `enrollment(student_id, course_id)` |
| `@OneToOne` | user ↔ profile | a unique FK |

## ManyToOne: the simplest and most important

```java
package com.vitalstack;

import jakarta.persistence.*;

@Entity
public class Lesson {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String title;

    @ManyToOne(fetch = FetchType.LAZY, optional = false)   // the OWNING side: has the FK column
    @JoinColumn(name = "course_id")
    private Course course;

    protected Lesson() { }
    public Lesson(String title) { this.title = title; }

    public String getTitle() { return title; }
    public Course getCourse() { return course; }
    void setCourse(Course course) { this.course = course; }
}
```

The side with the foreign key column is the **owning side**. Only changes on the owning side are written to the database.

## OneToMany: the inverse side, with mappedBy

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

    @OneToMany(mappedBy = "course", cascade = CascadeType.ALL, orphanRemoval = true)
    @OrderColumn(name = "position")
    private List<Lesson> lessons = new ArrayList<>();

    protected Course() { }
    public Course(String title) { this.title = title; }

    // helper methods keep BOTH sides in sync
    public void addLesson(Lesson lesson) {
        lessons.add(lesson);
        lesson.setCourse(this);
    }

    public void removeLesson(Lesson lesson) {
        lessons.remove(lesson);
        lesson.setCourse(null);
    }

    public Long getId() { return id; }
    public String getTitle() { return title; }
    public List<Lesson> getLessons() { return lessons; }
}
```

- `mappedBy = "course"` says "the `course` field in Lesson owns this relationship; I'm just a mirror". Without it, JPA creates an extra join table you didn't want.
- **Helper methods** (`addLesson`) set both sides. If you only add to the list, the FK is never set (owning side unchanged)!

## Cascade and orphan removal

- `cascade = CascadeType.ALL`: operations on the course (persist, merge, remove…) cascade to its lessons. Persist a course → its new lessons are inserted too.
- `orphanRemoval = true`: a lesson removed from the list is **deleted** (it can't exist without a course).

Use cascade only from a parent to children that it **owns** (Course → Lessons, Order → OrderItems). Never cascade REMOVE from `@ManyToOne` (deleting a lesson must not delete the course!).

```java
package com.vitalstack;

import jakarta.persistence.*;

public class Main {
    public static void main(String[] args) {
        EntityManagerFactory emf = Persistence.createEntityManagerFactory("school");
        EntityManager em = emf.createEntityManager();

        em.getTransaction().begin();
        Course java = new Course("Java");
        java.addLesson(new Lesson("Variables"));
        java.addLesson(new Lesson("Loops"));
        java.addLesson(new Lesson("Methods"));
        em.persist(java);                                   // cascades to the 3 lessons
        em.getTransaction().commit();

        em.getTransaction().begin();
        Lesson loops = java.getLessons().get(1);
        java.removeLesson(loops);                           // orphanRemoval → DELETE
        em.getTransaction().commit();
        em.clear();

        Course loaded = em.find(Course.class, java.getId());
        System.out.println(loaded.getTitle() + " has " + loaded.getLessons().size() + " lessons:");
        loaded.getLessons().forEach(l -> System.out.println("- " + l.getTitle()));
        em.close();
        emf.close();
    }
}
```

```text
Java has 2 lessons:
- Variables
- Methods
```

## Fetch types: LAZY vs EAGER

| Annotation | Default fetch |
|---|---|
| `@ManyToOne`, `@OneToOne` | **EAGER** (loaded immediately with a join or extra query) |
| `@OneToMany`, `@ManyToMany` | **LAZY** (loaded when first accessed) |

**Best practice: make everything LAZY** (`fetch = FetchType.LAZY` on `@ManyToOne`/`@OneToOne`) and load what each use case needs with a fetch join or entity graph (next lesson). EAGER relationships load data you often don't need and are a common cause of slow apps (see the N+1 problem in the Hibernate course).

## ManyToMany

```java
@Entity
public class Student {
    @Id @GeneratedValue private Long id;
    private String name;

    @ManyToMany
    @JoinTable(name = "enrollment",
               joinColumns = @JoinColumn(name = "student_id"),
               inverseJoinColumns = @JoinColumn(name = "course_id"))
    private Set<Course> courses = new HashSet<>();
}

// in Course (inverse side):
@ManyToMany(mappedBy = "courses")
private Set<Student> students = new HashSet<>();
```

Use a `Set` for many-to-many (Hibernate handles removals more efficiently than with a `List`).

::tip The moment a link needs **its own data** (enrolled on which date, progress %, grade), replace `@ManyToMany` with a real entity `Enrollment` that has two `@ManyToOne`s. In practice, most many-to-many relationships end up this way.

## OneToOne

```java
@Entity
public class UserAccount {
    @Id @GeneratedValue private Long id;

    @OneToOne(mappedBy = "user", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private Profile profile;
}

@Entity
public class Profile {
    @Id private Long id;

    @OneToOne(fetch = FetchType.LAZY)
    @MapsId                          // share the primary key with the user
    private UserAccount user;
    private String bio;
}
```

`@MapsId` makes the profile's id equal to the user's id: simple and efficient.

## Avoid these traps

- **toString/equals/hashCode including relationships**: printing a course prints lessons, which print the course… → StackOverflowError, or triggers lazy loading everywhere. Exclude relationships.
- **JSON serialisation of entities** with bidirectional links → infinite recursion. Return DTOs.
- **Bidirectional everything**: only add the inverse side (`@OneToMany`) if you navigate that way.

## Exercise

1. Map `Order` (1) → `OrderItem` (many) with cascade, orphan removal and helper methods. Create an order with 3 items in one persist.
2. Add `Author` ↔ `Book` many-to-many with a join table.
3. Replace student ↔ course many-to-many with an `Enrollment` entity that stores `enrolledOn` and `progress`.
4. What happens if you call `course.getLessons().add(lesson)` without setting `lesson.setCourse(course)`?

::hint Hint for 4 | Lesson is the owning side. Its `course` field is null, so with `optional = false` you get an error, otherwise the FK is saved as NULL and the lesson isn't linked.

## Interview corner

- **What is the owning side?** The side holding the foreign key (usually `@ManyToOne`); only its changes update the FK.
- **What does mappedBy do?** Marks the inverse side and points to the owning field, preventing a duplicate join table/FK.
- **Default fetch types?** ToOne = EAGER, ToMany = LAZY. Best practice: LAZY everywhere.
- **cascade vs orphanRemoval?** Cascade propagates operations from parent to children; orphanRemoval deletes children removed from the parent's collection.

## Summary

- `@ManyToOne` owns the FK; `@OneToMany(mappedBy)` mirrors it; keep both sides in sync with helper methods.
- Cascade from parents to owned children; orphanRemoval deletes removed children.
- Prefer LAZY fetching; turn rich many-to-many links into entities.

Next: querying with JPQL.
