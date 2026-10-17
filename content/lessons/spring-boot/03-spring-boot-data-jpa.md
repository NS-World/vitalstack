---
title: Spring Data JPA - Repositories Without Code
slug: spring-boot-data-jpa
subject: spring-boot
lesson: 3
difficulty: intermediate
excerpt: Connect Spring Boot to H2 or MySQL, map entities, get CRUD for free with JpaRepository, write derived query methods and @Query, paginate and sort, and seed data at startup.
---

In the JPA course you wrote `EntityManager` code and transactions by hand. **Spring Data JPA** goes further: you declare an **interface**, and Spring generates the implementation at startup, including queries derived from method names.

## Dependencies and configuration

Starters: `spring-boot-starter-data-jpa` plus a driver (`com.h2database:h2` for learning, `com.mysql:mysql-connector-j` or `org.postgresql:postgresql` for real databases).

```ini
# application.properties
spring.datasource.url=jdbc:h2:mem:courses
spring.jpa.hibernate.ddl-auto=create-drop
spring.jpa.open-in-view=false
spring.jpa.show-sql=false
spring.h2.console.enabled=true
```

For MySQL you'd change just the connection:

```ini
spring.datasource.url=jdbc:mysql://localhost:3306/courses
spring.datasource.username=courses
spring.datasource.password=${DB_PASSWORD}
spring.jpa.hibernate.ddl-auto=validate
```

Boot auto-configures the DataSource (HikariCP pool), Hibernate, the EntityManagerFactory and the transaction manager from these few lines.

## The entity

```java
package com.vitalstack.courses;

public enum Level { BEGINNER, INTERMEDIATE, ADVANCED }
```

```java
package com.vitalstack.courses;

import jakarta.persistence.*;

@Entity
public class Course {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false, length = 100)
    private String title;

    @Enumerated(EnumType.STRING)
    private Level level;

    private int price;
    private boolean published;

    protected Course() { }
    public Course(String title, Level level, int price, boolean published) {
        this.title = title; this.level = level; this.price = price; this.published = published;
    }

    public Long getId() { return id; }
    public String getTitle() { return title; }
    public Level getLevel() { return level; }
    public int getPrice() { return price; }
    public boolean isPublished() { return published; }
    public void setPrice(int price) { this.price = price; }
}
```

## The repository: just an interface

```java
package com.vitalstack.courses;

import org.springframework.data.domain.*;
import org.springframework.data.jpa.repository.*;
import org.springframework.data.repository.query.Param;
import java.util.List;
import java.util.Optional;

public interface CourseRepository extends JpaRepository<Course, Long> {

    // derived queries: Spring reads the method name and writes the JPQL
    List<Course> findByLevel(Level level);
    List<Course> findByTitleContainingIgnoreCase(String text);
    List<Course> findByPublishedTrueAndPriceLessThanOrderByPriceAsc(int max);
    Optional<Course> findFirstByOrderByPriceDesc();
    long countByLevel(Level level);
    boolean existsByTitleIgnoreCase(String title);

    // paging
    Page<Course> findByPublishedTrue(Pageable pageable);

    // explicit JPQL when names get too long
    @Query("select avg(c.price) from Course c where c.level = :level")
    Double averagePrice(@Param("level") Level level);

    // bulk update
    @Modifying
    @Query("update Course c set c.price = c.price - c.price * :pct / 100 where c.level = :level")
    int discount(@Param("level") Level level, @Param("pct") int pct);
}
```

`JpaRepository` already gives you: `save`, `saveAll`, `findById`, `findAll` (with `Sort` or `Pageable`), `count`, `existsById`, `deleteById`, `delete`, `flush` and more. No implementation class anywhere!

## Derived query keywords

| Method name piece | JPQL |
|---|---|
| `findByTitle` | `where title = ?` |
| `...And...`, `...Or...` | `and`, `or` |
| `LessThan`, `GreaterThanEqual`, `Between` | `<`, `>=`, `between` |
| `Containing`, `StartingWith`, `IgnoreCase` | `like %x%`, `like x%`, `upper(...)` |
| `In`, `IsNull`, `True`, `False` | `in (...)`, `is null`, `= true` |
| `OrderByPriceDesc` | `order by price desc` |
| `findFirst`, `findTop3` | limit 1 / 3 |
| `countBy`, `existsBy`, `deleteBy` | count / exists / delete |

Typos in method names fail at **startup**, not at runtime. A nice safety net.

## Using it in a service and controller

```java
package com.vitalstack.courses;

import org.springframework.data.domain.*;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import java.util.List;

@Service
@Transactional(readOnly = true)
public class CourseService {
    private final CourseRepository repo;

    public CourseService(CourseRepository repo) { this.repo = repo; }

    public List<Course> search(String q) { return repo.findByTitleContainingIgnoreCase(q); }
    public Page<Course> page(int page, int size) {
        return repo.findByPublishedTrue(PageRequest.of(page, size, Sort.by("price").descending()));
    }
    public List<Course> cheap(int max) { return repo.findByPublishedTrueAndPriceLessThanOrderByPriceAsc(max); }
    public Double average(Level level) { return repo.averagePrice(level); }

    @Transactional                                  // writes need a read-write transaction
    public int discount(Level level, int pct) { return repo.discount(level, pct); }
}
```

```java
package com.vitalstack.courses;

import org.springframework.data.domain.Page;
import org.springframework.web.bind.annotation.*;
import java.util.List;
import java.util.Map;

@RestController
@RequestMapping("/api/courses")
public class CourseController {
    private final CourseService service;

    public CourseController(CourseService service) { this.service = service; }

    @GetMapping("/search")
    public List<Course> search(@RequestParam String q) { return service.search(q); }

    @GetMapping("/cheap")
    public List<Course> cheap(@RequestParam(defaultValue = "700") int max) { return service.cheap(max); }

    public record CoursePage(List<String> items, int page, int totalPages, long total) { }
    public record LevelStats(Level level, Double averagePrice) { }

    @GetMapping
    public CoursePage page(@RequestParam(defaultValue = "0") int page,
                           @RequestParam(defaultValue = "2") int size) {
        Page<Course> p = service.page(page, size);
        return new CoursePage(p.getContent().stream().map(Course::getTitle).toList(),
                p.getNumber(), p.getTotalPages(), p.getTotalElements());
    }

    @GetMapping("/stats/{level}")
    public LevelStats stats(@PathVariable Level level) {
        return new LevelStats(level, service.average(level));
    }

    @PostMapping("/discount/{level}")
    public Map<String, Integer> discount(@PathVariable Level level, @RequestParam int pct) {
        return Map.of("updated", service.discount(level, pct));
    }
}
```

## Seeding data at startup

```java
package com.vitalstack.courses;

import org.springframework.boot.CommandLineRunner;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.context.annotation.Bean;
import java.util.List;

@SpringBootApplication
public class CourseApiApplication {
    public static void main(String[] args) {
        SpringApplication.run(CourseApiApplication.class, args);
    }

    @Bean
    CommandLineRunner seed(CourseRepository repo) {
        return args -> repo.saveAll(List.of(
                new Course("Java Basics", Level.BEGINNER, 499, true),
                new Course("Spring Boot", Level.INTERMEDIATE, 799, true),
                new Course("Hibernate Deep Dive", Level.ADVANCED, 999, true),
                new Course("React", Level.BEGINNER, 599, true),
                new Course("JavaScript Draft", Level.BEGINNER, 199, false)));
    }
}
```

(Alternatively, put `INSERT`s in `src/main/resources/data.sql`.)

## Results

```text
GET  /api/courses/search?q=java       → [{"id":1,"title":"Java Basics",…}, {"id":5,"title":"JavaScript Draft",…}]
GET  /api/courses/cheap?max=700       → [Java Basics (499), React (599)]   (drafts excluded)
GET  /api/courses?page=0&size=2       → {"items":["Hibernate Deep Dive","Spring Boot"],"page":0,"totalPages":2,"total":4}
GET  /api/courses?page=1&size=2       → {"items":["React","Java Basics"],"page":1,"totalPages":2,"total":4}
GET  /api/courses/stats/BEGINNER      → {"level":"BEGINNER","averagePrice":432.3333333333333}
POST /api/courses/discount/BEGINNER?pct=10 → {"updated":3}
```

::tip `Pageable` works straight from request parameters too: declare `Pageable pageable` as a controller parameter and call `/api/courses?page=1&size=20&sort=price,desc`. But return a DTO (not `Page` itself) to keep a stable JSON format.

## Common mistakes

- `@Modifying` queries without a write transaction → `TransactionRequiredException`.
- Huge derived names: switch to `@Query`.
- `findAll()` on big tables: always paginate lists.
- Returning entities with lazy relationships from controllers (LazyInitializationException or JSON loops). Use DTOs: next lesson.

## Exercise

1. Add `findByLevelOrderByTitle(Level level)` and expose it as `GET /api/courses/level/{level}`.
2. Add `findTop3ByPublishedTrueOrderByPriceDesc()` for "premium courses".
3. Write a `@Query` that returns the total price of published courses per level as a record projection.
4. Open the H2 console at `/h2-console` (JDBC URL `jdbc:h2:mem:courses`) and look at the table.

::hint Hint for 3 | `@Query("select new com.vitalstack.courses.LevelTotal(c.level, sum(c.price)) from Course c where c.published = true group by c.level") List<LevelTotal> totals();`

## Interview corner

- **What is Spring Data JPA?** A Spring module that generates repository implementations from interfaces, including derived queries.
- **CrudRepository vs JpaRepository?** JpaRepository extends PagingAndSortingRepository/ListCrudRepository and adds JPA-specific methods like flush and batch deletes.
- **How do derived queries work?** Spring parses the method name into a JPQL query at startup.
- **What does @Modifying do?** Marks a @Query as an update/delete statement (needs a transaction).

## Summary

- Configure the datasource in properties; Boot sets up Hibernate and transactions.
- `interface X extends JpaRepository<Entity, Id>` gives CRUD, paging and sorting.
- Derived query methods and `@Query` cover most queries; `@Modifying` for bulk updates.

Next: DTOs, validation and clean error responses.
