---
title: Build Your First REST API with Spring Boot
slug: spring-boot-first-rest-api
subject: spring-boot
lesson: 2
difficulty: beginner
excerpt: Design REST endpoints, build a full CRUD API for courses with @RestController, return correct status codes with ResponseEntity, and test it with curl or Postman.
---

A **REST API** lets other programs (a React or Angular front end, a mobile app, another service) work with your data over HTTP using JSON. Let's build a complete CRUD API for courses, step by step.

## REST design in one table

Resources are **nouns** in the URL; the HTTP method is the **verb**:

| Action | Method + URL | Success status |
|---|---|---|
| List courses | `GET /api/courses` | 200 OK |
| Get one course | `GET /api/courses/{id}` | 200 OK (404 if missing) |
| Create a course | `POST /api/courses` | 201 Created + Location header |
| Replace a course | `PUT /api/courses/{id}` | 200 OK |
| Delete a course | `DELETE /api/courses/{id}` | 204 No Content |

Use plural nouns (`/courses`), lowercase, hyphens for multiple words (`/course-reviews`), and nest for ownership (`/courses/5/lessons`).

## The code

A record for the data:

```java
package com.vitalstack.courses;

public record Course(Long id, String title, String level, int price) { }
```

A service that holds the data (in memory for now; the next lesson adds a database):

```java
package com.vitalstack.courses;

import org.springframework.stereotype.Service;
import java.util.*;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.atomic.AtomicLong;

@Service
public class CourseService {
    private final Map<Long, Course> courses = new ConcurrentHashMap<>();
    private final AtomicLong nextId = new AtomicLong();

    public CourseService() {
        create(new Course(null, "Java Basics", "BEGINNER", 499));
        create(new Course(null, "Spring Boot", "INTERMEDIATE", 799));
    }

    public List<Course> findAll() {
        return courses.values().stream().sorted(Comparator.comparing(Course::id)).toList();
    }

    public Optional<Course> findById(long id) { return Optional.ofNullable(courses.get(id)); }

    public Course create(Course c) {
        long id = nextId.incrementAndGet();
        Course saved = new Course(id, c.title(), c.level(), c.price());
        courses.put(id, saved);
        return saved;
    }

    public Optional<Course> update(long id, Course c) {
        if (!courses.containsKey(id)) return Optional.empty();
        Course updated = new Course(id, c.title(), c.level(), c.price());
        courses.put(id, updated);
        return Optional.of(updated);
    }

    public boolean delete(long id) { return courses.remove(id) != null; }
}
```

The controller maps HTTP to the service:

```java
package com.vitalstack.courses;

import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.support.ServletUriComponentsBuilder;
import java.net.URI;
import java.util.List;

@RestController
@RequestMapping("/api/courses")
public class CourseController {
    private final CourseService service;

    public CourseController(CourseService service) { this.service = service; }

    @GetMapping
    public List<Course> all() { return service.findAll(); }

    @GetMapping("/{id}")
    public ResponseEntity<Course> one(@PathVariable long id) {
        return ResponseEntity.of(service.findById(id));              // 200 with body, or 404
    }

    @PostMapping
    public ResponseEntity<Course> create(@RequestBody Course course) {
        Course saved = service.create(course);
        URI location = ServletUriComponentsBuilder.fromCurrentRequest()
                .path("/{id}").buildAndExpand(saved.id()).toUri();
        return ResponseEntity.created(location).body(saved);         // 201 + Location header
    }

    @PutMapping("/{id}")
    public ResponseEntity<Course> update(@PathVariable long id, @RequestBody Course course) {
        return ResponseEntity.of(service.update(id, course));
    }

    @DeleteMapping("/{id}")
    public ResponseEntity<Void> delete(@PathVariable long id) {
        return service.delete(id) ? ResponseEntity.noContent().build() : ResponseEntity.notFound().build();
    }
}
```

And the application class:

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

## Try it with curl

```bash
curl localhost:8080/api/courses
curl localhost:8080/api/courses/1
curl -i -X POST localhost:8080/api/courses -H "Content-Type: application/json" \
     -d '{"title":"React","level":"BEGINNER","price":599}'
curl -X PUT localhost:8080/api/courses/3 -H "Content-Type: application/json" \
     -d '{"title":"React 19","level":"BEGINNER","price":649}'
curl -i -X DELETE localhost:8080/api/courses/2
curl -i localhost:8080/api/courses/99
```

Responses:

```text
GET    /api/courses     → 200 [{"id":1,"title":"Java Basics","level":"BEGINNER","price":499},{"id":2,"title":"Spring Boot","level":"INTERMEDIATE","price":799}]
GET    /api/courses/1   → 200 {"id":1,"title":"Java Basics","level":"BEGINNER","price":499}
POST   /api/courses     → 201 Location: http://localhost:8080/api/courses/3  {"id":3,"title":"React","level":"BEGINNER","price":599}
PUT    /api/courses/3   → 200 {"id":3,"title":"React 19","level":"BEGINNER","price":649}
DELETE /api/courses/2   → 204
GET    /api/courses/99  → 404
```

Jackson converted records to JSON and JSON back into records automatically. On Windows PowerShell, use `curl.exe` or **Postman** / **Insomnia** / IntelliJ's HTTP client, which are friendlier for JSON.

## The layers

```text
HTTP ──▶ Controller (web: parse request, pick status) ──▶ Service (business rules) ──▶ Repository (data)
```

Even in this small app, keeping the controller thin pays off: the service can be reused by a scheduled job or a message listener, and tested without HTTP.

::tip `ResponseEntity.of(optional)` is a neat shortcut: 200 with the body if present, 404 if empty. And always return **201 + Location** for creates: front-end developers will thank you.

## Common mistakes

- Verbs in URLs (`/getCourses`, `/createCourse`): let the HTTP method be the verb.
- Returning 200 for everything (even errors) with `{"success": false}`.
- Missing `Content-Type: application/json` on POST/PUT → 415 Unsupported Media Type.
- Putting business logic in controllers.

## Exercise

1. Add `GET /api/courses?level=BEGINNER` filtering (use `@RequestParam(required = false)`).
2. Add `PATCH /api/courses/{id}/price` that accepts `{"price": 399}` and changes only the price.
3. Add a `lessons` count field and `GET /api/courses/{id}/summary` returning a smaller JSON object.
4. Test every endpoint with Postman and save the requests as a collection.

::hint Hint for 2 | Accept `@RequestBody Map<String, Integer> body` (or a small `record PriceChange(int price)`) and build a new Course with the new price.

## Interview corner

- **What makes an API RESTful?** Resources identified by URLs, standard HTTP methods, stateless requests, representations (JSON), and proper status codes.
- **PUT vs PATCH?** PUT replaces the whole resource; PATCH changes part of it.
- **Which status for create? delete?** 201 Created (with Location); 204 No Content.
- **Is GET idempotent? POST?** GET, PUT, DELETE are idempotent; POST is not.

## Summary

- Nouns in URLs, HTTP methods as verbs, correct status codes.
- `@RestController` + mappings + `ResponseEntity` give full control.
- Controller → service → repository layering keeps code clean.

Next: store courses in a real database with Spring Data JPA.
