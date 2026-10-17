---
title: Jmix Data Access with DataManager and Fetch Plans
slug: jmix-data-access
subject: jmix
lesson: 5
difficulty: advanced
excerpt: Load and save entities from Java code with DataManager's fluent API, control loading with fetch plans, understand security-aware vs unconstrained access, write services, and react to entity events.
---

Views load data declaratively. In services, scheduled jobs and event handlers you work with data in Java through **DataManager**, Jmix's main data access API. It's similar to Spring Data repositories, but security-aware and fetch-plan-aware.

## DataManager basics

```java
@Component
public class CourseService {
    private final DataManager dataManager;

    public CourseService(DataManager dataManager) { this.dataManager = dataManager; }

    public List<Course> beginnerCourses() {
        return dataManager.load(Course.class)
                .query("select c from Course c where c.level = :level order by c.title")
                .parameter("level", CourseLevel.BEGINNER)
                .list();
    }

    public Optional<Course> byTitle(String title) {
        return dataManager.load(Course.class)
                .query("select c from Course c where lower(c.title) = lower(:t)")
                .parameter("t", title)
                .optional();
    }

    public Course byId(UUID id) {
        return dataManager.load(Course.class).id(id).one();      // throws if not found
    }

    public Course createCourse(String title, Category category) {
        Course c = dataManager.create(Course.class);              // new instance with UUID assigned
        c.setTitle(title);
        c.setCategory(category);
        c.setLevel(CourseLevel.BEGINNER);
        return dataManager.save(c);                                // INSERT, returns the saved instance
    }

    public void raisePrice(Course course, BigDecimal percent) {
        course.setPrice(course.getPrice().multiply(BigDecimal.ONE.add(percent.movePointLeft(2))));
        dataManager.save(course);                                  // UPDATE
    }

    public void delete(Course course) {
        dataManager.remove(course);                                // soft delete if the entity has the trait
    }
}
```

Key points:

- `load(...).query(...)` with named parameters (`:level`) only, never string concatenation.
- `list()`, `one()` (exactly one, else exception), `optional()`.
- `.firstResult(n).maxResults(m)` for paging.
- `save(...)` accepts several entities and saves them **in one transaction**: `dataManager.save(course, lesson1, lesson2)`.
- Use `SaveContext` for complex saves with removals.

## Fetch plans: loading related data correctly

Entities loaded by DataManager are detached. If a reference wasn't loaded, accessing it later fails. A **fetch plan** says what to load:

```java
List<Course> courses = dataManager.load(Course.class)
        .query("select c from Course c")
        .fetchPlan(fp -> fp.addFetchPlan(FetchPlan.BASE)          // all local attributes
                           .add("category", FetchPlan.BASE)         // + the category
                           .add("lessons", FetchPlan.BASE))         // + the lessons
        .list();
```

- `FetchPlan.BASE` (`_base`): all local (non-reference) attributes.
- `FetchPlan.INSTANCE_NAME` (`_instance_name`): only what's needed for the display name.
- Named fetch plans can also be declared once in a `fetch-plans.xml` file and reused by name.

Jmix builds efficient queries from the plan (joins or batch loading), avoiding the N+1 problem automatically.

## Security: DataManager vs UnconstrainedDataManager

`DataManager` **applies the current user's permissions**: entity operations (read/create/update/delete) and row-level policies. A user without permission to read salaries simply doesn't get those rows or attributes.

For system code that must see everything (a nightly job, a migration), use the unconstrained variant explicitly:

```java
@Component
public class NightlyReport {
    private final UnconstrainedDataManager system;

    public NightlyReport(UnconstrainedDataManager system) { this.system = system; }

    public long countAllCourses() {
        return system.load(Course.class).all().list().size();
    }
}
```

(`dataManager.unconstrained()` returns the same thing.) Be deliberate about it: unconstrained access in user-facing code is a security hole.

## Services and transactions

Put business logic in Spring beans. DataManager operations each run in their own transaction; for several operations that must succeed together, save them in one call or use Spring's `@Transactional` with a `TransactionalDataManager`.

```java
@Component
public class EnrollmentService {
    private final DataManager dataManager;

    public EnrollmentService(DataManager dataManager) { this.dataManager = dataManager; }

    public Enrollment enroll(Student student, Course course) {
        long active = dataManager.loadValue(
                        "select count(e) from Enrollment e where e.student = :s and e.completed = false", Long.class)
                .parameter("s", student)
                .one();
        if (active >= 3) throw new IllegalStateException("Finish a course first");
        Enrollment e = dataManager.create(Enrollment.class);
        e.setStudent(student);
        e.setCourse(course);
        return dataManager.save(e);
    }
}
```

`loadValue` / `loadValues` return scalar values and aggregates (counts, sums) instead of entities.

## Entity events

React to data changes in one place, whatever saved the data (a view, a service, the REST API):

```java
@Component
public class CourseEventListener {

    @EventListener
    public void onCourseSaving(final EntitySavingEvent<Course> event) {
        Course c = event.getEntity();
        c.setTitle(c.getTitle().trim());                          // normalise before saving
    }

    @TransactionalEventListener
    public void onCourseChanged(final EntityChangedEvent<Course> event) {
        if (event.getType() == EntityChangedEvent.Type.CREATED) {
            // after commit: send a notification, update a search index…
        }
    }
}
```

- `EntitySavingEvent` / `EntityLoadingEvent`: change the entity before it's saved or after it's loaded.
- `EntityChangedEvent`: what changed (old values available); with `@TransactionalEventListener` it runs **after the commit**, the right place for emails and integrations.

::tip Same rule as Spring Boot: views stay thin, services hold business rules, events handle side effects. Your Jmix code then stays testable with normal Spring Boot tests (`@SpringBootTest` + `DataManager`).

## Exercise

1. Write `CourseService.totalPriceByCategory()` using `loadValues` with `group by`.
2. Load courses with their lessons using a fetch plan and print the lesson count of each.
3. Add an `EntitySavingEvent` listener that rejects lessons with `durationMinutes > 180`.
4. Call your service from a button in the course list view and show the result in a notification.

::hint Hint for 1 | `dataManager.loadValues("select c.category.name, sum(c.price) from Course c group by c.category.name").properties("category", "total").list()` returns `KeyValueEntity` objects; read them with `kv.getValue("total")`.

## Interview corner

- **What is DataManager?** Jmix's main API to load, save and remove entities, applying security and fetch plans.
- **DataManager vs UnconstrainedDataManager?** The first enforces the current user's permissions; the second bypasses them for system operations.
- **What problem do fetch plans solve?** They define the object graph to load, avoiding lazy-loading errors and N+1 queries.
- **When does EntityChangedEvent fire?** On entity changes; with @TransactionalEventListener it's handled after the transaction commits.

## Summary

- `DataManager.load(...).query(...).parameter(...).list()/one()/optional()`, plus `create`, `save`, `remove`.
- Fetch plans load exactly the graph you need.
- DataManager is security-aware; use UnconstrainedDataManager deliberately.
- Entity events centralise normalisation and side effects.

Next: securing the app with roles.
