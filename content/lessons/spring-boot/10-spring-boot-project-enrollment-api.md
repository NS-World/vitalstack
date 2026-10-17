---
title: "Spring Boot Project: Course Enrollment API"
slug: spring-boot-project-enrollment-api
subject: spring-boot
lesson: 10
difficulty: advanced
excerpt: Build a complete, layered REST API - students, courses and enrollments with progress tracking, validation, business rules, ProblemDetail errors, an efficient leaderboard query and tests. Portfolio-ready.
---

Let's build a real back-end like the one behind an e-learning site: students enroll in courses, complete lessons, and see a leaderboard. It brings together everything from this course: entities and relationships, Spring Data JPA, DTOs, validation, transactions, error handling and efficient queries.

## Features

- `POST /api/students`: register a student (unique email).
- `GET /api/courses`: list courses.
- `POST /api/enrollments`: enroll a student in a course (no duplicates, max 3 active courses).
- `POST /api/enrollments/{id}/progress`: complete lessons (progress can't exceed 100%).
- `GET /api/students/{id}/enrollments`: a student's courses with progress.
- `GET /api/leaderboard`: top students by completed lessons.

## Package structure

```text
com.vitalstack.enroll
├── EnrollApplication.java      (main + seed data)
├── domain/                     (entities + repositories)
├── service/                    (business rules, transactions)
└── web/                        (controllers, DTOs, error handling)
```

## Domain

```java
package com.vitalstack.enroll.domain;

import jakarta.persistence.*;

@Entity
public class Student {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    @Column(nullable = false)
    private String name;
    @Column(nullable = false, unique = true)
    private String email;

    protected Student() { }
    public Student(String name, String email) { this.name = name; this.email = email; }
    public Long getId() { return id; }
    public String getName() { return name; }
    public String getEmail() { return email; }
}
```

```java
package com.vitalstack.enroll.domain;

import jakarta.persistence.*;

@Entity
public class Course {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String title;
    private int totalLessons;

    protected Course() { }
    public Course(String title, int totalLessons) { this.title = title; this.totalLessons = totalLessons; }
    public Long getId() { return id; }
    public String getTitle() { return title; }
    public int getTotalLessons() { return totalLessons; }
}
```

```java
package com.vitalstack.enroll.domain;

import jakarta.persistence.*;
import java.time.LocalDate;

@Entity
@Table(uniqueConstraints = @UniqueConstraint(columnNames = { "student_id", "course_id" }))
public class Enrollment {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @ManyToOne(fetch = FetchType.LAZY, optional = false)
    private Student student;

    @ManyToOne(fetch = FetchType.LAZY, optional = false)
    private Course course;

    private int completedLessons;
    private LocalDate enrolledOn;

    @Version
    private int version;                               // safe concurrent progress updates

    protected Enrollment() { }
    public Enrollment(Student student, Course course) {
        this.student = student; this.course = course; this.enrolledOn = LocalDate.now();
    }

    /** Business rule lives with the data. */
    public void completeLessons(int count) {
        if (count <= 0) throw new IllegalArgumentException("Count must be positive");
        completedLessons = Math.min(course.getTotalLessons(), completedLessons + count);
    }

    public boolean isFinished() { return completedLessons >= course.getTotalLessons(); }
    public int percent() { return completedLessons * 100 / course.getTotalLessons(); }

    public Long getId() { return id; }
    public Student getStudent() { return student; }
    public Course getCourse() { return course; }
    public int getCompletedLessons() { return completedLessons; }
}
```

```java
package com.vitalstack.enroll.domain;

import org.springframework.data.jpa.repository.JpaRepository;

public interface StudentRepository extends JpaRepository<Student, Long> {
    boolean existsByEmailIgnoreCase(String email);
}
```

```java
package com.vitalstack.enroll.domain;

import org.springframework.data.jpa.repository.JpaRepository;

public interface CourseRepository extends JpaRepository<Course, Long> { }
```

```java
package com.vitalstack.enroll.domain;

import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.*;
import java.util.List;

public interface EnrollmentRepository extends JpaRepository<Enrollment, Long> {
    boolean existsByStudentIdAndCourseId(Long studentId, Long courseId);

    @Query("select count(e) from Enrollment e where e.student.id = :sid and e.completedLessons < e.course.totalLessons")
    long countActive(Long sid);

    @EntityGraph(attributePaths = "course")                       // no N+1 when listing
    List<Enrollment> findByStudentIdOrderByEnrolledOnDesc(Long studentId);

    @Query("""
           select new com.vitalstack.enroll.domain.LeaderboardRow(s.name, sum(e.completedLessons), count(e))
           from Enrollment e join e.student s
           group by s.id, s.name
           order by sum(e.completedLessons) desc, s.name""")
    List<LeaderboardRow> leaderboard(Pageable top);
}
```

```java
package com.vitalstack.enroll.domain;

public record LeaderboardRow(String student, long lessonsCompleted, long courses) { }
```

## Service: business rules and transactions

```java
package com.vitalstack.enroll.service;

import com.vitalstack.enroll.domain.*;
import org.springframework.data.domain.PageRequest;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import java.util.List;
import java.util.NoSuchElementException;

@Service
@Transactional
public class EnrollmentService {
    public static final int MAX_ACTIVE = 3;

    private final StudentRepository students;
    private final CourseRepository courses;
    private final EnrollmentRepository enrollments;

    public EnrollmentService(StudentRepository s, CourseRepository c, EnrollmentRepository e) {
        this.students = s; this.courses = c; this.enrollments = e;
    }

    public Student register(String name, String email) {
        String normalised = email.trim().toLowerCase();
        if (students.existsByEmailIgnoreCase(normalised)) throw new BusinessRuleException("Email already registered");
        return students.save(new Student(name.trim(), normalised));
    }

    public Enrollment enroll(long studentId, long courseId) {
        Student s = students.findById(studentId).orElseThrow(() -> new NoSuchElementException("Student " + studentId + " not found"));
        Course c = courses.findById(courseId).orElseThrow(() -> new NoSuchElementException("Course " + courseId + " not found"));
        if (enrollments.existsByStudentIdAndCourseId(studentId, courseId)) throw new BusinessRuleException("Already enrolled in " + c.getTitle());
        if (enrollments.countActive(studentId) >= MAX_ACTIVE) throw new BusinessRuleException("Finish a course first: max " + MAX_ACTIVE + " active courses");
        return enrollments.save(new Enrollment(s, c));
    }

    public Enrollment progress(long enrollmentId, int lessons) {
        Enrollment e = enrollments.findById(enrollmentId).orElseThrow(() -> new NoSuchElementException("Enrollment " + enrollmentId + " not found"));
        e.completeLessons(lessons);                      // dirty checking + @Version
        return e;
    }

    @Transactional(readOnly = true)
    public List<Enrollment> enrollmentsOf(long studentId) { return enrollments.findByStudentIdOrderByEnrolledOnDesc(studentId); }

    @Transactional(readOnly = true)
    public List<LeaderboardRow> leaderboard(int top) { return enrollments.leaderboard(PageRequest.of(0, top)); }

    @Transactional(readOnly = true)
    public List<Course> courses() { return courses.findAll(); }
}
```

```java
package com.vitalstack.enroll.service;

public class BusinessRuleException extends RuntimeException {
    public BusinessRuleException(String message) { super(message); }
}
```

## Web layer: DTOs, controller, errors

```java
package com.vitalstack.enroll.web;

import com.vitalstack.enroll.domain.Enrollment;
import jakarta.validation.constraints.*;

public final class Api {
    private Api() { }

    public record RegisterRequest(@NotBlank @Size(max = 60) String name, @NotBlank @Email String email) { }
    public record EnrollRequest(@NotNull Long studentId, @NotNull Long courseId) { }
    public record ProgressRequest(@Min(1) @Max(50) int lessons) { }

    public record StudentView(Long id, String name, String email) { }
    public record CourseView(Long id, String title, int totalLessons) { }
    public record EnrollmentView(Long id, String course, int completed, int percent, boolean finished) {
        static EnrollmentView of(Enrollment e) {
            return new EnrollmentView(e.getId(), e.getCourse().getTitle(), e.getCompletedLessons(), e.percent(), e.isFinished());
        }
    }
}
```

```java
package com.vitalstack.enroll.web;

import com.vitalstack.enroll.domain.LeaderboardRow;
import com.vitalstack.enroll.service.EnrollmentService;
import com.vitalstack.enroll.web.Api.*;
import jakarta.validation.Valid;
import org.springframework.http.HttpStatus;
import org.springframework.web.bind.annotation.*;
import java.util.List;

@RestController
@RequestMapping("/api")
public class EnrollmentController {
    private final EnrollmentService service;

    public EnrollmentController(EnrollmentService service) { this.service = service; }

    @PostMapping("/students")
    @ResponseStatus(HttpStatus.CREATED)
    public StudentView register(@Valid @RequestBody RegisterRequest r) {
        var s = service.register(r.name(), r.email());
        return new StudentView(s.getId(), s.getName(), s.getEmail());
    }

    @GetMapping("/courses")
    public List<CourseView> courses() {
        return service.courses().stream().map(c -> new CourseView(c.getId(), c.getTitle(), c.getTotalLessons())).toList();
    }

    @PostMapping("/enrollments")
    @ResponseStatus(HttpStatus.CREATED)
    public EnrollmentView enroll(@Valid @RequestBody EnrollRequest r) {
        return EnrollmentView.of(service.enroll(r.studentId(), r.courseId()));
    }

    @PostMapping("/enrollments/{id}/progress")
    public EnrollmentView progress(@PathVariable long id, @Valid @RequestBody ProgressRequest r) {
        return EnrollmentView.of(service.progress(id, r.lessons()));
    }

    @GetMapping("/students/{id}/enrollments")
    public List<EnrollmentView> mine(@PathVariable long id) {
        return service.enrollmentsOf(id).stream().map(EnrollmentView::of).toList();
    }

    @GetMapping("/leaderboard")
    public List<LeaderboardRow> leaderboard(@RequestParam(defaultValue = "10") int top) {
        return service.leaderboard(Math.min(top, 50));
    }
}
```

```java
package com.vitalstack.enroll.web;

import com.vitalstack.enroll.service.BusinessRuleException;
import org.springframework.http.*;
import org.springframework.web.bind.MethodArgumentNotValidException;
import org.springframework.web.bind.annotation.*;
import java.util.*;

@RestControllerAdvice
public class ApiErrors {
    @ExceptionHandler(NoSuchElementException.class)
    public ProblemDetail notFound(NoSuchElementException e) {
        return ProblemDetail.forStatusAndDetail(HttpStatus.NOT_FOUND, e.getMessage());
    }

    @ExceptionHandler(BusinessRuleException.class)
    public ProblemDetail rule(BusinessRuleException e) {
        return ProblemDetail.forStatusAndDetail(HttpStatus.CONFLICT, e.getMessage());
    }

    @ExceptionHandler(MethodArgumentNotValidException.class)
    public ProblemDetail invalid(MethodArgumentNotValidException e) {
        Map<String, String> fields = new TreeMap<>();
        e.getBindingResult().getFieldErrors().forEach(f -> fields.put(f.getField(), f.getDefaultMessage()));
        ProblemDetail p = ProblemDetail.forStatusAndDetail(HttpStatus.BAD_REQUEST, "Invalid request");
        p.setProperty("errors", fields);
        return p;
    }
}
```

## Application and seed data

```java
package com.vitalstack.enroll;

import com.vitalstack.enroll.domain.*;
import org.springframework.boot.CommandLineRunner;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.context.annotation.Bean;
import java.util.List;

@SpringBootApplication
public class EnrollApplication {
    public static void main(String[] args) { SpringApplication.run(EnrollApplication.class, args); }

    @Bean
    CommandLineRunner seed(CourseRepository courses) {
        return args -> courses.saveAll(List.of(
                new Course("Java Basics", 16), new Course("Java OOP", 12), new Course("Spring Boot", 10),
                new Course("React", 12), new Course("SQL", 15)));
    }
}
```

```ini
# application.properties
spring.jpa.open-in-view=false
```

## A session with the API

```text
POST /api/students {"name":"Asha","email":"Asha@Example.com"}       → 201 {"id":1,"name":"Asha","email":"asha@example.com"}
POST /api/students {"name":"Ravi","email":"ravi@example.com"}       → 201 {"id":2,"name":"Ravi","email":"ravi@example.com"}
POST /api/students {"name":"Copy","email":"asha@example.com"}       → 409 "Email already registered"
POST /api/students {"name":"","email":"not-an-email"}               → 400 errors: email, name
POST /api/enrollments {"studentId":1,"courseId":1}                   → 201 {"id":1,"course":"Java Basics","completed":0,"percent":0,"finished":false}
POST /api/enrollments {"studentId":1,"courseId":1}                   → 409 "Already enrolled in Java Basics"
POST /api/enrollments {"studentId":1,"courseId":2}                   → 201
POST /api/enrollments {"studentId":1,"courseId":3}                   → 201
POST /api/enrollments {"studentId":1,"courseId":4}                   → 409 "Finish a course first: max 3 active courses"
POST /api/enrollments/3/progress {"lessons":10}                     → 200 {"id":3,"course":"Spring Boot","completed":10,"percent":100,"finished":true}
POST /api/enrollments {"studentId":1,"courseId":4}                   → 201 (a slot is free again)
POST /api/enrollments/1/progress {"lessons":8}                      → 200 {"id":1,"course":"Java Basics","completed":8,"percent":50,"finished":false}
POST /api/enrollments {"studentId":2,"courseId":1}                   → 201
POST /api/enrollments/5/progress {"lessons":5}                      → 200
GET  /api/leaderboard?top=5    → [{"student":"Asha","lessonsCompleted":18,"courses":4},{"student":"Ravi","lessonsCompleted":5,"courses":1}]
GET  /api/students/99/enrollments → 200 []
POST /api/enrollments {"studentId":99,"courseId":1}                  → 404 "Student 99 not found"
```

## What makes this production-style

- **Layers:** web (DTOs, validation, HTTP), service (rules, transactions), domain (entities, repositories).
- **Business rules** in the service and the entity (`completeLessons` caps progress), not in controllers.
- **Database constraints** back up the rules (unique email, unique student+course).
- **Optimistic locking** on progress updates.
- **Efficient reads:** `@EntityGraph` for a student's courses, one aggregate query for the leaderboard.
- **Consistent errors:** 400 / 404 / 409 ProblemDetails.

## Make it your portfolio project

1. Add **JWT security** (lesson 6): students can only see and update their own enrollments; admins manage courses.
2. Add **tests** (lesson 7): unit tests for the enrollment rules, `@WebMvcTest` for the controller, `@DataJpaTest` for the leaderboard query.
3. Switch to **PostgreSQL + Flyway** and add a **Dockerfile** (lesson 9).
4. Build a **React or Angular** front end for it (next courses on VitalStack).
5. Write a README with the endpoints, how to run it, and screenshots. Deploy it and put the link on your CV and LinkedIn.

::hint Hint for 1 | In the controller, read the user from `@AuthenticationPrincipal Jwt jwt` and check that the enrollment's student email equals `jwt.getSubject()`, or use `@PreAuthorize` with a custom bean method.

::tip Interviewers love "walk me through a project you built". With this API you can explain layering, transactions, validation, N+1 avoidance, optimistic locking and error design, which covers most Spring Boot interview questions.

## Summary

You built a complete Spring Boot API with relationships, rules, validation, transactions, efficient queries and clean errors. **Congratulations on finishing the Spring Boot course!**
