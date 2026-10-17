---
title: Testing Spring Boot Applications
slug: spring-boot-testing
subject: spring-boot
lesson: 7
difficulty: intermediate
excerpt: Write fast unit tests with JUnit 5 and Mockito, test controllers with @WebMvcTest and MockMvc, repositories with @DataJpaTest, full flows with @SpringBootTest, and real databases with Testcontainers.
---

Tests let you change code without fear. Spring Boot gives you everything in one starter (`spring-boot-starter-test`: JUnit 5, Mockito, AssertJ, MockMvc, JSONPath) and **test slices** that start only the part of the app you're testing, so tests stay fast.

## The testing pyramid

```text
        /\        few   @SpringBootTest: whole app, real HTTP (slow, broad)
       /  \             
      /----\      some  slices: @WebMvcTest, @DataJpaTest (medium)
     /      \           
    /--------\    many  plain unit tests: no Spring at all (milliseconds)
```

We'll test the course API from the DTO and validation lesson (`CourseService`, `CourseController`, `CourseRepository`, `ApiExceptionHandler`).

## 1. Unit tests with Mockito (no Spring)

```java
package com.vitalstack.courses;

import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.extension.ExtendWith;
import org.mockito.InjectMocks;
import org.mockito.Mock;
import org.mockito.junit.jupiter.MockitoExtension;
import java.util.Optional;

import static org.assertj.core.api.Assertions.*;
import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.*;

@ExtendWith(MockitoExtension.class)
class CourseServiceTest {

    @Mock CourseRepository repo;              // a fake repository
    @InjectMocks CourseService service;       // the real service, with the fake injected

    @Test
    void createsCourseWhenTitleIsNew() {
        when(repo.existsByTitleIgnoreCase("Docker")).thenReturn(false);
        when(repo.save(any(Course.class))).thenAnswer(inv -> inv.getArgument(0));

        CourseResponse res = service.create(new CourseRequest(" Docker ", Level.BEGINNER, 399));

        assertThat(res.title()).isEqualTo("Docker");          // trimmed
        verify(repo).save(any(Course.class));
    }

    @Test
    void rejectsDuplicateTitle() {
        when(repo.existsByTitleIgnoreCase("Java")).thenReturn(true);

        assertThatThrownBy(() -> service.create(new CourseRequest("Java", Level.BEGINNER, 1)))
                .isInstanceOf(DuplicateCourseException.class)
                .hasMessageContaining("already exists");
        verify(repo, never()).save(any());
    }

    @Test
    void missingCourseThrowsNotFound() {
        when(repo.findById(42L)).thenReturn(Optional.empty());
        assertThatThrownBy(() -> service.get(42)).isInstanceOf(CourseNotFoundException.class);
    }
}
```

These run in milliseconds. Constructor injection is what makes them so easy.

## 2. Controller tests with @WebMvcTest

`@WebMvcTest` starts only the web layer (controllers, advice, JSON conversion, validation). Services are replaced with mocks:

```java
package com.vitalstack.courses;

import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.WebMvcTest;
import org.springframework.boot.test.mock.mockito.MockBean;
import org.springframework.http.MediaType;
import org.springframework.test.web.servlet.MockMvc;

import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.when;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.*;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.*;

@WebMvcTest(CourseController.class)
class CourseControllerTest {

    @Autowired MockMvc mvc;
    @MockBean CourseService service;      // Spring Boot 3.4+: @MockitoBean

    @Test
    void returnsCourseAsJson() throws Exception {
        when(service.get(1)).thenReturn(new CourseResponse(1L, "Java Basics", Level.BEGINNER, 499));

        mvc.perform(get("/api/courses/1"))
           .andExpect(status().isOk())
           .andExpect(jsonPath("$.title").value("Java Basics"))
           .andExpect(jsonPath("$.price").value(499));
    }

    @Test
    void returns404ProblemDetail() throws Exception {
        when(service.get(99)).thenThrow(new CourseNotFoundException(99));

        mvc.perform(get("/api/courses/99"))
           .andExpect(status().isNotFound())
           .andExpect(jsonPath("$.detail").value("Course 99 was not found"));
    }

    @Test
    void rejectsInvalidBody() throws Exception {
        mvc.perform(post("/api/courses")
                .contentType(MediaType.APPLICATION_JSON)
                .content("{\"title\":\"\",\"level\":\"BEGINNER\",\"price\":-1}"))
           .andExpect(status().isBadRequest())
           .andExpect(jsonPath("$.errors.title").value("Title is required"))
           .andExpect(jsonPath("$.errors.price").value("Price can't be negative"));
    }

    @Test
    void createReturns201WithLocation() throws Exception {
        when(service.create(any())).thenReturn(new CourseResponse(7L, "Docker", Level.BEGINNER, 399));

        mvc.perform(post("/api/courses")
                .contentType(MediaType.APPLICATION_JSON)
                .content("{\"title\":\"Docker\",\"level\":\"BEGINNER\",\"price\":399}"))
           .andExpect(status().isCreated())
           .andExpect(header().string("Location", "/api/courses/7"));
    }
}
```

No server, no database: MockMvc calls the DispatcherServlet directly. If your app uses Spring Security, these tests also run through the security filters: add `@WithMockUser(roles = "ADMIN")` on tests that need a logged-in user.

## 3. Repository tests with @DataJpaTest

```java
package com.vitalstack.courses;

import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.orm.jpa.DataJpaTest;

import static org.assertj.core.api.Assertions.assertThat;

@DataJpaTest                                       // JPA + embedded H2 + rollback after each test
class CourseRepositoryTest {

    @Autowired CourseRepository repo;

    @Test
    void findsTitlesIgnoringCase() {
        repo.save(new Course("Spring Boot", Level.INTERMEDIATE, 799));

        assertThat(repo.existsByTitleIgnoreCase("spring boot")).isTrue();
        assertThat(repo.existsByTitleIgnoreCase("react")).isFalse();
    }
}
```

Each test runs in a transaction that's **rolled back**, so tests don't affect each other.

## 4. Full integration tests with @SpringBootTest

```java
package com.vitalstack.courses;

import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.boot.test.web.client.TestRestTemplate;
import org.springframework.http.*;

import static org.assertj.core.api.Assertions.assertThat;

@SpringBootTest(webEnvironment = SpringBootTest.WebEnvironment.RANDOM_PORT)
class CourseApiIntegrationTest {

    @Autowired TestRestTemplate http;

    @Test
    void createThenFetch() {
        var created = http.postForEntity("/api/courses",
                new CourseRequest("Kubernetes", Level.ADVANCED, 999), CourseResponse.class);
        assertThat(created.getStatusCode()).isEqualTo(HttpStatus.CREATED);

        var fetched = http.getForEntity(created.getHeaders().getLocation(), CourseResponse.class);
        assertThat(fetched.getBody().title()).isEqualTo("Kubernetes");
    }
}
```

The whole app starts on a random port and the test talks to it over real HTTP.

## Running the tests

```bash
./mvnw test
```

```text
[INFO] Tests run: 9, Failures: 0, Errors: 0, Skipped: 0
[INFO] BUILD SUCCESS
```

## Testcontainers: test against the real database

H2 behaves differently from MySQL/PostgreSQL in places. **Testcontainers** starts a real database in Docker for your tests:

```java
@SpringBootTest
@Testcontainers
class PostgresIntegrationTest {
    @Container
    @ServiceConnection                          // Boot 3.1+: configures the DataSource automatically
    static PostgreSQLContainer<?> db = new PostgreSQLContainer<>("postgres:16-alpine");

    // tests now run against PostgreSQL 16
}
```

::tip Name tests after behaviour (`rejectsDuplicateTitle`), test one thing each, and follow **Arrange → Act → Assert**. When a bug is reported, first write a test that fails because of it, then fix the code: the bug can never silently return.

## Exercise

1. Add a unit test for `CourseService.update` that checks the entity's fields change.
2. Add a `@WebMvcTest` for `PUT /api/courses/{id}` returning 404 for a missing course.
3. Add a `@DataJpaTest` for a derived query like `findByLevel`.
4. Measure: run `./mvnw test` and compare the time of the unit tests vs `@SpringBootTest` in the report.

::hint Hint for 2 | `when(service.update(eq(5L), any())).thenThrow(new CourseNotFoundException(5));` then perform a PUT with a valid JSON body and expect `status().isNotFound()`.

## Interview corner

- **@SpringBootTest vs @WebMvcTest vs @DataJpaTest?** Whole application context vs web layer only vs JPA layer only (with an embedded DB and rollback).
- **@Mock vs @MockBean?** @Mock is plain Mockito (unit tests); @MockBean (or @MockitoBean) replaces a bean inside the Spring test context.
- **What is MockMvc?** A way to test Spring MVC controllers by simulating HTTP requests without a server.
- **Why Testcontainers?** Tests run against the same database engine as production, catching dialect and migration issues.

## Summary

- Many fast unit tests with Mockito; fewer slice tests; a few full integration tests.
- `@WebMvcTest` + MockMvc for controllers, `@DataJpaTest` for repositories, `@SpringBootTest` for end-to-end.
- Testcontainers gives real databases in tests.

Next: relationships, pagination and performance in a real API.
