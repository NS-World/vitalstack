---
title: Spring Boot Actuator, Docker and Deployment
slug: spring-boot-actuator-and-deployment
subject: spring-boot
lesson: 9
difficulty: intermediate
excerpt: Get your API production-ready - health checks and metrics with Actuator, building an executable jar, a small Docker image, configuration with environment variables, database migrations with Flyway, and deploying to a VPS or cloud platform.
---

Your API works on your laptop. Production needs more: health checks for load balancers, metrics to spot problems, safe database migrations, and a repeatable way to ship. Spring Boot covers all of it.

## Actuator: health and metrics

Add `spring-boot-starter-actuator`, then choose what to expose:

```yaml
# application.yml
management:
  endpoints:
    web:
      exposure:
        include: health,info,metrics,prometheus
  endpoint:
    health:
      show-details: when-authorized
      probes:
        enabled: true          # /actuator/health/liveness and /readiness for Kubernetes
info:
  app:
    name: course-api
    version: 1.0.0
```

| Endpoint | Shows |
|---|---|
| `/actuator/health` | `{"status":"UP"}`, plus database, disk space and other checks |
| `/actuator/info` | app name, version, git commit |
| `/actuator/metrics` | JVM memory, CPU, HTTP request counts and timings, connection pool usage |
| `/actuator/prometheus` | the same metrics in Prometheus format (add `micrometer-registry-prometheus`) |
| `/actuator/loggers` | view and change log levels at runtime |

```bash
curl localhost:8080/actuator/health
{"status":"UP"}
curl localhost:8080/actuator/metrics/http.server.requests
```

Load balancers and Kubernetes call `/actuator/health` to decide whether to send traffic to an instance.

::tip Never expose all endpoints publicly (`include: "*"`): `/env`, `/heapdump` and `/loggers` reveal secrets and internals. Expose only what you need, and protect the rest with Spring Security or a separate management port (`management.server.port=8081`, not open to the internet).

## Database migrations with Flyway

`ddl-auto=update` is fine while learning, but production needs **versioned, reviewed** schema changes. Add `flyway-core` (plus `flyway-mysql` or `flyway-database-postgresql`), then write SQL files:

```text
src/main/resources/db/migration/
├── V1__create_course.sql
├── V2__create_lesson.sql
└── V3__add_course_level.sql
```

```sql
-- V3__add_course_level.sql
ALTER TABLE course ADD COLUMN level VARCHAR(20) NOT NULL DEFAULT 'BEGINNER';
CREATE INDEX idx_course_level ON course(level);
```

On startup, Flyway runs the new scripts in order and records them in `flyway_schema_history`. Set `spring.jpa.hibernate.ddl-auto=validate` so Hibernate only checks that entities match the schema.

## Build an executable jar

```bash
./mvnw clean package          # runs the tests too
java -jar target/course-api-1.0.0.jar
```

The jar contains your code, all libraries and Tomcat. That single file is your deployable.

## Docker

A **multi-stage** Dockerfile builds the jar and then copies it into a small runtime image:

```dockerfile
# Dockerfile
FROM eclipse-temurin:21-jdk AS build
WORKDIR /app
COPY .mvn .mvn
COPY mvnw pom.xml ./
RUN ./mvnw -q dependency:go-offline
COPY src src
RUN ./mvnw -q -DskipTests package

FROM eclipse-temurin:21-jre-alpine
WORKDIR /app
RUN addgroup -S app && adduser -S app -G app
USER app
COPY --from=build /app/target/*.jar app.jar
EXPOSE 8080
ENTRYPOINT ["java", "-XX:MaxRAMPercentage=75", "-jar", "app.jar"]
```

```bash
docker build -t course-api .
docker run -p 8080:8080 \
  -e SPRING_PROFILES_ACTIVE=prod \
  -e SPRING_DATASOURCE_URL=jdbc:postgresql://db:5432/courses \
  -e SPRING_DATASOURCE_USERNAME=courses \
  -e SPRING_DATASOURCE_PASSWORD=secret \
  -e JWT_SECRET=a-very-long-random-secret-value \
  course-api
```

Or skip the Dockerfile: `./mvnw spring-boot:build-image` creates an optimised image with Cloud Native Buildpacks.

### Run the app and a database together

```yaml
# docker-compose.yml
services:
  db:
    image: postgres:16-alpine
    environment:
      POSTGRES_DB: courses
      POSTGRES_USER: courses
      POSTGRES_PASSWORD: secret
    volumes: [dbdata:/var/lib/postgresql/data]
  api:
    build: .
    ports: ["8080:8080"]
    environment:
      SPRING_PROFILES_ACTIVE: prod
      SPRING_DATASOURCE_URL: jdbc:postgresql://db:5432/courses
      SPRING_DATASOURCE_USERNAME: courses
      SPRING_DATASOURCE_PASSWORD: secret
    depends_on: [db]
volumes:
  dbdata:
```

`docker compose up --build` starts both.

## Where to deploy

| Option | Good for | Notes |
|---|---|---|
| **VPS** (Hostinger VPS, DigitalOcean, AWS Lightsail) | full control, low cost | install Docker, run compose, put **Nginx** in front for HTTPS (Let's Encrypt) |
| **Render / Railway / Fly.io** | quick deploys from GitHub | set env vars in their dashboard; managed PostgreSQL available |
| **AWS Elastic Beanstalk / Azure App Service / Google Cloud Run** | scalable, managed | Cloud Run runs your container and scales to zero |
| **Kubernetes** | many services, big teams | uses the liveness/readiness probes above |

Note: regular **shared web hosting** (the kind used for WordPress and PHP) can't run Java apps. You need a VPS or a platform that runs containers or jars.

## Production checklist

- ✅ `prod` profile; secrets only from environment variables.
- ✅ Flyway migrations; `ddl-auto=validate`.
- ✅ HTTPS (Nginx/Caddy or the platform's TLS).
- ✅ Health checks and basic metrics; log to stdout (the platform collects logs).
- ✅ Enough memory: `-XX:MaxRAMPercentage=75` makes the JVM respect container limits.
- ✅ Graceful shutdown: `server.shutdown=graceful` finishes in-flight requests before stopping.
- ✅ CORS restricted to your front-end domain.
- ✅ Backups of the database.

## Exercise

1. Add Actuator and open `/actuator/health` and `/actuator/metrics/jvm.memory.used`.
2. Add a custom health indicator that reports DOWN when a payment provider is unreachable (`implements HealthIndicator`).
3. Convert your schema to Flyway migrations `V1__init.sql` and `V2__seed.sql`.
4. Build the Docker image and run it with `docker compose up`.

::hint Hint for 2 | `@Component class PaymentHealth implements HealthIndicator { public Health health() { return reachable() ? Health.up().build() : Health.down().withDetail("provider", "razorpay").build(); } }`

## Interview corner

- **What is Spring Boot Actuator?** Production-ready endpoints for health, metrics, info, environment and more.
- **How do you manage schema changes?** Versioned migrations with Flyway or Liquibase, never ddl-auto=update in production.
- **How do you containerise a Spring Boot app?** A multi-stage Dockerfile with a JRE base image, or `spring-boot:build-image` (Buildpacks).
- **How do you pass configuration to a container?** Environment variables (relaxed binding), mounted config files, or a config server/secret manager.

## Summary

- Actuator gives health checks and metrics; expose only what's needed.
- Flyway versions your schema; Hibernate just validates.
- Ship a jar or a Docker image configured by environment variables, behind HTTPS.

Next: the capstone project that puts the whole course together.
