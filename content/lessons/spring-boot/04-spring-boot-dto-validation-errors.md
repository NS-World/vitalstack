---
title: DTOs, Validation and Error Handling in Spring Boot
slug: spring-boot-dto-validation-errors
subject: spring-boot
lesson: 4
difficulty: intermediate
excerpt: Separate API models from entities with DTO records, validate input with Bean Validation, return clean RFC 7807 ProblemDetail errors with @RestControllerAdvice, and map 400, 404 and 409 correctly.
---

A professional API rejects bad input with clear messages, never leaks internal details, and returns consistent error responses. This lesson adds three things to the course API: **DTOs**, **validation** and **error handling**.

## Why DTOs?

A DTO (Data Transfer Object) is a class shaped for the API, separate from the entity shaped for the database.

| Returning entities directly | Using DTOs |
|---|---|
| exposes every column (even `passwordHash`!) | exposes exactly what clients need |
| JSON changes when the table changes | the API contract stays stable |
| lazy relationships → errors or JSON loops | flat, predictable JSON |
| clients can set fields they shouldn't (`id`, `published`) | request DTOs accept only allowed fields |

Records are perfect DTOs:

```java
package com.vitalstack.courses;

import jakarta.validation.constraints.*;

public record CourseRequest(
        @NotBlank(message = "Title is required")
        @Size(max = 100, message = "Title must be at most 100 characters")
        String title,

        @NotNull(message = "Level is required")
        Level level,

        @Min(value = 0, message = "Price can't be negative")
        @Max(value = 50000, message = "Price looks too high")
        int price) { }
```

```java
package com.vitalstack.courses;

public record CourseResponse(Long id, String title, Level level, int price) {
    static CourseResponse from(Course c) {
        return new CourseResponse(c.getId(), c.getTitle(), c.getLevel(), c.getPrice());
    }
}
```

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
    @Column(nullable = false, unique = true, length = 100)
    private String title;
    @Enumerated(EnumType.STRING)
    private Level level;
    private int price;

    protected Course() { }
    public Course(String title, Level level, int price) { this.title = title; this.level = level; this.price = price; }

    public void update(String title, Level level, int price) { this.title = title; this.level = level; this.price = price; }
    public Long getId() { return id; }
    public String getTitle() { return title; }
    public Level getLevel() { return level; }
    public int getPrice() { return price; }
}
```

```java
package com.vitalstack.courses;

import org.springframework.data.jpa.repository.JpaRepository;

public interface CourseRepository extends JpaRepository<Course, Long> {
    boolean existsByTitleIgnoreCase(String title);
}
```

## Domain exceptions

```java
package com.vitalstack.courses;

public class CourseNotFoundException extends RuntimeException {
    public CourseNotFoundException(long id) { super("Course " + id + " was not found"); }
}
```

```java
package com.vitalstack.courses;

public class DuplicateCourseException extends RuntimeException {
    public DuplicateCourseException(String title) { super("A course titled '" + title + "' already exists"); }
}
```

## The service speaks in DTOs and exceptions

```java
package com.vitalstack.courses;

import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

@Service
@Transactional
public class CourseService {
    private final CourseRepository repo;

    public CourseService(CourseRepository repo) { this.repo = repo; }

    @Transactional(readOnly = true)
    public CourseResponse get(long id) {
        return repo.findById(id).map(CourseResponse::from).orElseThrow(() -> new CourseNotFoundException(id));
    }

    public CourseResponse create(CourseRequest req) {
        String title = req.title().trim();                     // normalise first, then check duplicates
        if (repo.existsByTitleIgnoreCase(title)) throw new DuplicateCourseException(title);
        return CourseResponse.from(repo.save(new Course(title, req.level(), req.price())));
    }

    public CourseResponse update(long id, CourseRequest req) {
        Course c = repo.findById(id).orElseThrow(() -> new CourseNotFoundException(id));
        c.update(req.title().trim(), req.level(), req.price());     // dirty checking saves it
        return CourseResponse.from(c);
    }
}
```

## @Valid in the controller

```java
package com.vitalstack.courses;

import jakarta.validation.Valid;
import org.springframework.http.*;
import org.springframework.web.bind.annotation.*;
import java.net.URI;

@RestController
@RequestMapping("/api/courses")
public class CourseController {
    private final CourseService service;

    public CourseController(CourseService service) { this.service = service; }

    @GetMapping("/{id}")
    public CourseResponse get(@PathVariable long id) { return service.get(id); }

    @PostMapping
    public ResponseEntity<CourseResponse> create(@Valid @RequestBody CourseRequest req) {
        CourseResponse created = service.create(req);
        return ResponseEntity.created(URI.create("/api/courses/" + created.id())).body(created);
    }

    @PutMapping("/{id}")
    public CourseResponse update(@PathVariable long id, @Valid @RequestBody CourseRequest req) {
        return service.update(id, req);
    }
}
```

`@Valid` runs the annotations on `CourseRequest` before your method is called. If anything fails, Spring throws `MethodArgumentNotValidException` and your method never runs.

## Consistent errors with ProblemDetail

Spring Boot 3 supports **RFC 7807 Problem Details**, a standard JSON error format. One class handles errors for every controller:

```java
package com.vitalstack.courses;

import org.springframework.http.*;
import org.springframework.web.bind.MethodArgumentNotValidException;
import org.springframework.web.bind.annotation.*;
import java.util.Map;
import java.util.TreeMap;

@RestControllerAdvice
public class ApiExceptionHandler {

    @ExceptionHandler(CourseNotFoundException.class)
    public ProblemDetail notFound(CourseNotFoundException e) {
        ProblemDetail p = ProblemDetail.forStatusAndDetail(HttpStatus.NOT_FOUND, e.getMessage());
        p.setTitle("Course not found");
        return p;
    }

    @ExceptionHandler(DuplicateCourseException.class)
    public ProblemDetail conflict(DuplicateCourseException e) {
        ProblemDetail p = ProblemDetail.forStatusAndDetail(HttpStatus.CONFLICT, e.getMessage());
        p.setTitle("Duplicate course");
        return p;
    }

    @ExceptionHandler(MethodArgumentNotValidException.class)
    public ProblemDetail invalid(MethodArgumentNotValidException e) {
        Map<String, String> fields = new TreeMap<>();
        e.getBindingResult().getFieldErrors().forEach(f -> fields.put(f.getField(), f.getDefaultMessage()));
        ProblemDetail p = ProblemDetail.forStatusAndDetail(HttpStatus.BAD_REQUEST, "Some fields are invalid");
        p.setTitle("Validation failed");
        p.setProperty("errors", fields);
        return p;
    }
}
```

```java
package com.vitalstack.courses;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

@SpringBootApplication
public class CourseApiApplication {
    public static void main(String[] args) {
        SpringApplication.run(CourseApiApplication.class, args);
    }
}
```

## The responses

```text
POST /api/courses {"title":"Java Basics","level":"BEGINNER","price":499}
← 201 {"id":1,"title":"Java Basics","level":"BEGINNER","price":499}

POST /api/courses {"title":"","level":null,"price":-5}
← 400 {"type":"about:blank","title":"Validation failed","status":400,"detail":"Some fields are invalid",
       "instance":"/api/courses","errors":{"level":"Level is required","price":"Price can't be negative","title":"Title is required"}}

POST /api/courses {"title":"java basics","level":"BEGINNER","price":10}
← 409 {"type":"about:blank","title":"Duplicate course","status":409,
       "detail":"A course titled 'java basics' already exists","instance":"/api/courses"}

GET /api/courses/99
← 404 {"type":"about:blank","title":"Course not found","status":404,
       "detail":"Course 99 was not found","instance":"/api/courses/99"}
```

Front-end developers can now show the `errors` map next to each form field.

## Validation annotations

| Annotation | Checks |
|---|---|
| `@NotNull` | not null |
| `@NotBlank` | String not null/empty/whitespace |
| `@NotEmpty` | collection/String not empty |
| `@Size(min, max)` | length / size |
| `@Min`, `@Max`, `@Positive`, `@PositiveOrZero` | numbers |
| `@Email`, `@Pattern(regexp)` | formats |
| `@Past`, `@Future` | dates |
| `@Valid` on a nested field | validate nested objects / list elements |

Create your own with a custom `@Constraint` annotation when needed (e.g. `@IndianPinCode`).

::tip Validate twice, in different places: **Bean Validation** for format (required, length, range) at the API edge, and **business rules** (unique title, enough stock) in the service, throwing domain exceptions. The database constraints (unique, not null) are the last safety net.

## Status codes for errors

| Situation | Status |
|---|---|
| invalid input / validation | 400 Bad Request |
| not authenticated | 401 Unauthorized |
| authenticated but not allowed | 403 Forbidden |
| resource doesn't exist | 404 Not Found |
| conflict with current state (duplicate, version) | 409 Conflict |
| business rule violated on valid input | 422 Unprocessable Entity |
| unexpected bug | 500 (never send stack traces to clients!) |

## Exercise

1. Add `@Size(min = 3)` to title and check the 400 response for "Go".
2. Add an `email` field to a `StudentRequest` with `@Email` and a custom message.
3. Handle `OptimisticLockingFailureException` and return a 409 ProblemDetail.
4. Add a catch-all `@ExceptionHandler(Exception.class)` that logs the error and returns a generic 500 ProblemDetail without internal details.

::hint Hint for 4 | Log with `log.error("Unexpected error", e)` and return `ProblemDetail.forStatusAndDetail(HttpStatus.INTERNAL_SERVER_ERROR, "Something went wrong. Please try again.")`.

## Interview corner

- **Why use DTOs instead of entities in APIs?** Stable contracts, no over-exposure, no lazy-loading/JSON recursion issues, control over input fields.
- **How does @Valid work?** It triggers Bean Validation (Hibernate Validator) on the argument; failures raise MethodArgumentNotValidException.
- **@ControllerAdvice vs @RestControllerAdvice?** The latter adds @ResponseBody so handler results become JSON.
- **What is ProblemDetail?** Spring's implementation of RFC 7807, a standard JSON format for HTTP API errors.

## Summary

- Request/response DTO records decouple the API from entities.
- `@Valid` + constraint annotations validate input automatically.
- Domain exceptions + `@RestControllerAdvice` + `ProblemDetail` give consistent 400/404/409 errors.

Next: configuration, profiles and logging.
