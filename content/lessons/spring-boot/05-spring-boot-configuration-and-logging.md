---
title: Spring Boot Configuration, Profiles and Logging
slug: spring-boot-configuration-and-logging
subject: spring-boot
lesson: 5
difficulty: intermediate
excerpt: Configure Spring Boot with application.yml, typed @ConfigurationProperties, dev/test/prod profiles and environment variables, and log properly with SLF4J, log levels, log files and request logging.
---

The same jar should run on your laptop, a test server and production, with different databases, secrets and log levels. Spring Boot's configuration system and logging make this easy.

## application.yml

YAML is popular for its nesting. It's equivalent to `application.properties`:

```yaml
# application.yml
spring:
  application:
    name: course-api
  datasource:
    url: jdbc:h2:mem:courses
  jpa:
    open-in-view: false

server:
  port: 8080
  servlet:
    context-path: /

app:
  catalog:
    max-page-size: 50
    featured-courses: [java-basics, spring-boot]
    signup-bonus-xp: 100

logging:
  level:
    root: info
    com.vitalstack: debug
    org.hibernate.SQL: debug           # log SQL statements
```

Use one format per project (YAML or properties) to avoid confusion. If both exist, properties wins for the same key.

## Typed settings

```java
package com.vitalstack.courses;

import jakarta.validation.constraints.*;
import org.springframework.boot.context.properties.ConfigurationProperties;
import org.springframework.validation.annotation.Validated;
import java.util.List;

@Validated
@ConfigurationProperties(prefix = "app.catalog")
public record CatalogProperties(
        @Min(1) @Max(200) int maxPageSize,
        List<String> featuredCourses,
        @PositiveOrZero int signupBonusXp) { }
```

Register it with `@ConfigurationPropertiesScan` on the application class (or `@EnableConfigurationProperties(CatalogProperties.class)`) and inject it anywhere:

```java
@Service
public class CatalogService {
    private final CatalogProperties props;
    public CatalogService(CatalogProperties props) { this.props = props; }

    public int pageSize(int requested) { return Math.min(requested, props.maxPageSize()); }
}
```

If `max-page-size` is set to 0, the app **refuses to start** with a clear message. Bad configuration is caught at deploy time, not at 2 am.

## Profiles in Spring Boot

```text
src/main/resources/
├── application.yml          ← common defaults
├── application-dev.yml      ← local development
├── application-test.yml     ← automated tests
└── application-prod.yml     ← production
```

```yaml
# application-prod.yml
spring:
  datasource:
    url: jdbc:postgresql://${DB_HOST}:5432/courses
    username: ${DB_USER}
    password: ${DB_PASSWORD}
  jpa:
    hibernate:
      ddl-auto: validate
logging:
  level:
    root: warn
    com.vitalstack: info
```

Activate with `--spring.profiles.active=prod` or the `SPRING_PROFILES_ACTIVE=prod` environment variable. In tests, `@ActiveProfiles("test")`.

You can also keep several profiles in **one** YAML file separated by `---` and `spring.config.activate.on-profile: prod`.

## Environment variables everywhere

Any property can be overridden by an environment variable with relaxed binding:

| Property | Environment variable |
|---|---|
| `server.port` | `SERVER_PORT` |
| `spring.datasource.password` | `SPRING_DATASOURCE_PASSWORD` |
| `app.catalog.max-page-size` | `APP_CATALOG_MAXPAGESIZE` |

This is how Docker, Kubernetes, Render, Railway and most hosts configure Spring Boot apps.

## Logging with SLF4J

Spring Boot uses **SLF4J** with **Logback** by default. Get a logger per class:

```java
package com.vitalstack.courses;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.stereotype.Service;

@Service
public class EnrollmentService {
    private static final Logger log = LoggerFactory.getLogger(EnrollmentService.class);

    public void enroll(long studentId, long courseId) {
        log.debug("Enrolling student {} in course {}", studentId, courseId);   // {} placeholders: no string building if disabled
        try {
            // ... business logic ...
            log.info("Student {} enrolled in course {}", studentId, courseId);
        } catch (RuntimeException e) {
            log.error("Enrollment failed for student {} in course {}", studentId, courseId, e);   // exception last
            throw e;
        }
    }
}
```

(With Lombok, `@Slf4j` creates the `log` field for you.)

### Log levels

| Level | Use for |
|---|---|
| `ERROR` | something failed and needs attention |
| `WARN` | unexpected but handled (retry succeeded, deprecated usage) |
| `INFO` | important business events (order placed, app started) |
| `DEBUG` | details for developers |
| `TRACE` | very detailed (SQL parameters, loops) |

Set levels per package in configuration, and change them at runtime with Actuator's `/actuator/loggers` endpoint (lesson 9).

### Log files and format

```yaml
logging:
  file:
    name: logs/course-api.log      # also write to a file (rotated automatically, 10 MB by default)
  logback:
    rollingpolicy:
      max-history: 14              # keep 14 days
```

For log platforms (ELK, Loki, Datadog), Spring Boot 3.4+ supports structured JSON logs with `logging.structured.format.console=ecs`.

::tip Never log passwords, OTPs, full card numbers, tokens or personal data like Aadhaar numbers. Logs are copied to many places and kept for a long time. Log ids, not secrets.

## Common mistakes

- Indentation errors in YAML (use spaces, never tabs).
- String concatenation in logs (`"user " + id`) instead of `{}` placeholders.
- Logging an exception's message but not the exception itself (you lose the stack trace).
- Committing `application-prod.yml` with real passwords.

## Exercise

1. Convert your `application.properties` to `application.yml`.
2. Create `CatalogProperties` and print the featured courses at startup with a `CommandLineRunner`.
3. Add dev and prod profiles with different log levels and a different `server.port`, and switch between them.
4. Add DEBUG and INFO logs to your service, then run with `logging.level.com.vitalstack=info` and confirm the DEBUG lines disappear.

::hint Hint for 2 | `@Bean CommandLineRunner show(CatalogProperties p) { return args -> log.info("Featured: {}", p.featuredCourses()); }` plus `@ConfigurationPropertiesScan` on the application class.

## Interview corner

- **properties vs yml?** Same features; YAML is hierarchical and more readable for nested config.
- **How do you configure different environments?** Profiles (`application-{profile}.yml`) plus environment variables for secrets.
- **Which logging framework does Spring Boot use?** SLF4J API with Logback by default (Log4j2 optional).
- **Why use `{}` placeholders?** The message is only built if the level is enabled, and it's cleaner.

## Summary

- `application.yml` + profiles + environment variables configure every environment from one jar.
- `@ConfigurationProperties` records give typed, validated settings.
- Log with SLF4J at the right level, with placeholders, without secrets.

Next: securing the API with Spring Security and JWT.
