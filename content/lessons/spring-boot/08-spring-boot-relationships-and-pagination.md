---
title: Relationships, Pagination and Performance in a Spring Boot API
slug: spring-boot-relationships-and-pagination
subject: spring-boot
lesson: 8
difficulty: advanced
excerpt: Expose one-to-many data in a REST API without N+1 queries or JSON loops - nested resources, @EntityGraph, DTO mapping, Pageable endpoints with sorting, and counting queries to prove it's fast.
---

Real APIs return related data: a course with its lessons, a student with enrollments. This is where most Spring Boot apps get slow or crash with JSON recursion. Let's do it properly.

## The model

```java
package com.vitalstack.school;

import jakarta.persistence.*;
import java.util.ArrayList;
import java.util.List;

@Entity
public class Course {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String title;
    private int price;

    @OneToMany(mappedBy = "course", cascade = CascadeType.ALL, orphanRemoval = true)
    @OrderBy("position")
    private List<Lesson> lessons = new ArrayList<>();

    protected Course() { }
    public Course(String title, int price) { this.title = title; this.price = price; }

    public Lesson addLesson(String title) {
        Lesson l = new Lesson(this, title, lessons.size() + 1);
        lessons.add(l);
        return l;
    }

    public Long getId() { return id; }
    public String getTitle() { return title; }
    public int getPrice() { return price; }
    public List<Lesson> getLessons() { return lessons; }
}
```

```java
package com.vitalstack.school;

import jakarta.persistence.*;

@Entity
public class Lesson {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String title;
    private int position;

    @ManyToOne(fetch = FetchType.LAZY, optional = false)
    private Course course;

    protected Lesson() { }
    Lesson(Course course, String title, int position) { this.course = course; this.title = title; this.position = position; }

    public Long getId() { return id; }
    public String getTitle() { return title; }
    public int getPosition() { return position; }
}
```

## DTOs shaped for each endpoint

Never serialise the entities: `Course → lessons → course → lessons…` loops forever, and lazy collections explode outside transactions.

```java
package com.vitalstack.school;

import java.util.List;

public final class Dtos {
    private Dtos() { }

    public record CourseSummary(Long id, String title, int price, int lessonCount) { }
    public record LessonView(Long id, int position, String title) { }
    public record CourseDetail(Long id, String title, int price, List<LessonView> lessons) {
        static CourseDetail from(Course c) {
            return new CourseDetail(c.getId(), c.getTitle(), c.getPrice(),
                    c.getLessons().stream().map(l -> new LessonView(l.getId(), l.getPosition(), l.getTitle())).toList());
        }
    }
    public record PageResponse<T>(List<T> items, int page, int size, long total, int totalPages) { }
    public record NewLesson(String title) { }
}
```

## Repository: fetch what each endpoint needs

```java
package com.vitalstack.school;

import com.vitalstack.school.Dtos.CourseSummary;
import org.springframework.data.domain.*;
import org.springframework.data.jpa.repository.*;
import java.util.Optional;

public interface CourseRepository extends JpaRepository<Course, Long> {

    @EntityGraph(attributePaths = "lessons")                 // course + lessons in ONE query
    Optional<Course> findWithLessonsById(Long id);

    // list page: a DTO projection with a count, no lessons loaded at all
    @Query(value = """
            select new com.vitalstack.school.Dtos$CourseSummary(c.id, c.title, c.price, cast(size(c.lessons) as int))
            from Course c""",
           countQuery = "select count(c) from Course c")
    Page<CourseSummary> summaries(Pageable pageable);
}
```

## Service and controller

```java
package com.vitalstack.school;

import com.vitalstack.school.Dtos.*;
import org.springframework.data.domain.*;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import java.util.NoSuchElementException;

@Service
@Transactional(readOnly = true)
public class CourseService {
    private final CourseRepository repo;

    public CourseService(CourseRepository repo) { this.repo = repo; }

    public PageResponse<CourseSummary> list(Pageable pageable) {
        Page<CourseSummary> p = repo.summaries(pageable);
        return new PageResponse<>(p.getContent(), p.getNumber(), p.getSize(), p.getTotalElements(), p.getTotalPages());
    }

    public CourseDetail detail(long id) {
        return repo.findWithLessonsById(id).map(CourseDetail::from)
                .orElseThrow(() -> new NoSuchElementException("Course " + id + " not found"));
    }

    @Transactional
    public LessonView addLesson(long courseId, String title) {
        Course c = repo.findById(courseId).orElseThrow(() -> new NoSuchElementException("Course " + courseId + " not found"));
        Lesson l = c.addLesson(title);
        repo.flush();                                                      // get the generated id now
        return new LessonView(l.getId(), l.getPosition(), l.getTitle());
    }
}
```

```java
package com.vitalstack.school;

import com.vitalstack.school.Dtos.*;
import org.springframework.data.domain.Pageable;
import org.springframework.data.web.PageableDefault;
import org.springframework.http.HttpStatus;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/courses")
public class CourseController {
    private final CourseService service;

    public CourseController(CourseService service) { this.service = service; }

    @GetMapping                                   // ?page=0&size=2&sort=price,desc
    public PageResponse<CourseSummary> list(@PageableDefault(size = 10, sort = "id") Pageable pageable) {
        return service.list(pageable);
    }

    @GetMapping("/{id}")
    public CourseDetail detail(@PathVariable long id) { return service.detail(id); }

    @PostMapping("/{id}/lessons")                 // nested resource: lessons belong to a course
    @ResponseStatus(HttpStatus.CREATED)
    public LessonView addLesson(@PathVariable long id, @RequestBody NewLesson body) {
        return service.addLesson(id, body.title());
    }
}
```

Missing courses become a clean 404, as in the DTO and validation lesson:

```java
package com.vitalstack.school;

import org.springframework.http.*;
import org.springframework.web.bind.annotation.*;
import java.util.NoSuchElementException;

@RestControllerAdvice
public class ApiErrors {
    @ExceptionHandler(NoSuchElementException.class)
    public ProblemDetail notFound(NoSuchElementException e) {
        return ProblemDetail.forStatusAndDetail(HttpStatus.NOT_FOUND, e.getMessage());
    }
}
```

```java
package com.vitalstack.school;

import org.springframework.boot.CommandLineRunner;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.context.annotation.Bean;

@SpringBootApplication
public class SchoolApplication {
    public static void main(String[] args) { SpringApplication.run(SchoolApplication.class, args); }

    @Bean
    CommandLineRunner seed(CourseRepository repo) {
        return args -> {
            String[][] data = { {"Java Basics", "499", "Variables", "Loops", "Methods"},
                                {"Spring Boot", "799", "REST", "Data JPA"},
                                {"React", "599", "JSX", "State", "Hooks", "Router"} };
            for (String[] d : data) {
                Course c = new Course(d[0], Integer.parseInt(d[1]));
                for (int i = 2; i < d.length; i++) c.addLesson(d[i]);
                repo.save(c);
            }
        };
    }
}
```

```ini
# application.properties
spring.jpa.open-in-view=false
spring.jpa.properties.hibernate.default_batch_fetch_size=25
spring.data.web.pageable.max-page-size=50
```

## The responses

```text
GET /api/courses?page=0&size=2&sort=price,desc
← {"items":[{"id":2,"title":"Spring Boot","price":799,"lessonCount":2},
            {"id":3,"title":"React","price":599,"lessonCount":4}],
   "page":0,"size":2,"total":3,"totalPages":2}

GET /api/courses/1
← {"id":1,"title":"Java Basics","price":499,"lessons":[{"id":1,"position":1,"title":"Variables"},
   {"id":2,"position":2,"title":"Loops"},{"id":3,"position":3,"title":"Methods"}]}

POST /api/courses/1/lessons {"title":"Arrays"}
← 201 {"id":10,"position":4,"title":"Arrays"}

GET /api/courses/99
← 404 {"type":"about:blank","title":"Not Found","status":404,"detail":"Course 99 not found","instance":"/api/courses/99"}
```

## Proving it's efficient

With `spring.jpa.show-sql=true` you'll see:

- `GET /api/courses`: **2 queries** (the page of summaries with a `count` subquery for lessons, and the total count), however many courses are on the page.
- `GET /api/courses/1`: **1 query** (course left join lessons, from `@EntityGraph`).

A naive version (`findAll(pageable)` returning entities, mapped with `c.getLessons().size()`) runs 1 + N queries, and with open-in-view disabled it throws `LazyInitializationException` instead.

## Pagination best practices

- **Always paginate** list endpoints, and cap the size (`spring.data.web.pageable.max-page-size`).
- Use a **stable sort** (include `id`) so items don't jump between pages.
- Return your own `PageResponse` DTO rather than Spring's `Page` (whose JSON format isn't a stable contract).
- For infinite scroll over huge tables, consider **keyset pagination** (`where id > :lastSeenId order by id limit 20`): it stays fast deep into the list, unlike large `OFFSET`s.

::tip `spring.jpa.open-in-view=false` is the single most useful line in this lesson. It turns hidden N+1 queries in the web layer into loud errors during development, so you fix fetching where it belongs: in the repository.

## Exercise

1. Add `GET /api/courses/{id}/lessons?page=0&size=2` returning paginated lessons of a course.
2. Add `DELETE /api/courses/{courseId}/lessons/{lessonId}` (orphanRemoval deletes it).
3. Add a `level` to Course and a `?level=` filter to the list endpoint using a derived query `Page<...> findByLevel(Level level, Pageable p)`.
4. Turn on `show-sql` and count the queries for each endpoint.

::hint Hint for 1 | Add a `LessonRepository` with `Page<Lesson> findByCourseId(Long courseId, Pageable pageable)` and map to `LessonView`.

## Interview corner

- **How do you avoid N+1 in Spring Data JPA?** `@EntityGraph`, JOIN FETCH queries, DTO projections, batch fetching.
- **How do you return entities with bidirectional relationships as JSON?** Don't: map to DTOs (or use @JsonIgnore/@JsonManagedReference as a last resort).
- **Offset vs keyset pagination?** Offset is simple but slows down for large offsets; keyset uses the last seen key and stays fast.
- **What does open-in-view do?** Keeps the persistence context open during view rendering/serialisation, hiding lazy loading in the web layer.

## Summary

- Shape DTOs per endpoint; never serialise entities with relationships.
- `@EntityGraph` for details, DTO projections for lists, batch fetching as a safety net.
- Paginate everything, cap page sizes, disable open-in-view.

Next: monitoring with Actuator and deploying with Docker.
