---
title: Spring Boot Introduction
slug: spring-boot-introduction
subject: spring-boot
lesson: 1
difficulty: beginner
excerpt: What Spring Boot adds to Spring - starters, auto-configuration and an embedded server - watch an app start up in an animation, create a project with Spring Initializr and understand its structure.
---

**Spring Boot** is how almost every new Java back-end is built today. It takes the Spring Framework and removes the setup: no XML, no external Tomcat, no hunting for compatible library versions. You write business code; Boot configures the rest.

## Spring Boot in three ideas

1. **Starters**: one dependency pulls in a tested set of libraries. `spring-boot-starter-web` = Spring MVC + Jackson + embedded Tomcat + validation support.
2. **Auto-configuration**: Boot looks at your classpath and properties and creates the beans you'd otherwise configure by hand. H2 on the classpath? You get a DataSource. Spring Data JPA? You get an EntityManagerFactory and transaction manager.
3. **Embedded server**: the app is a plain `java -jar app.jar` with Tomcat inside. No WAR deployment.

Plus production features: health checks, metrics, externalised config and profiles.

## Watch an app start

Switch between starters and see what auto-configuration creates:

::viz spring-boot-startup {"deps": "web + data-jpa"}

## The whole application

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

`@SpringBootApplication` combines three annotations:

| Annotation | Does |
|---|---|
| `@SpringBootConfiguration` | this is a configuration class |
| `@EnableAutoConfiguration` | turn on auto-configuration |
| `@ComponentScan` | scan this package **and its sub-packages** for components |

That last point matters: keep the application class in the **root package** (`com.vitalstack.courses`) and put everything else below it (`...courses.web`, `...courses.service`), or your beans won't be found.

## Create a project with Spring Initializr

Go to **start.spring.io** (or IntelliJ: New Project → Spring Boot):

- Project: **Maven**, Language: **Java**, Spring Boot: latest **3.x**
- Group: `com.vitalstack`, Artifact: `course-api`
- Java: **21** (or 17)
- Dependencies: **Spring Web**, **Spring Data JPA**, **H2 Database**, **Validation**, **Spring Boot DevTools**

Download, unzip, open in your IDE and run the main class. You'll see:

```text
Tomcat started on port 8080 (http) with context path '/'
Started CourseApiApplication in 1.9 seconds
```

Visit `http://localhost:8080`: a "Whitelabel Error Page" (404), because there are no endpoints yet. That's success!

## Project structure

```text
course-api/
├── pom.xml                         ← dependencies (starters) + spring-boot-maven-plugin
├── mvnw, mvnw.cmd                  ← Maven wrapper: no Maven install needed (./mvnw spring-boot:run)
└── src/
    ├── main/
    │   ├── java/com/vitalstack/courses/
    │   │   └── CourseApiApplication.java
    │   └── resources/
    │       ├── application.properties    ← configuration
    │       ├── static/                   ← static files (CSS, JS, images)
    │       └── templates/                ← Thymeleaf HTML templates
    └── test/java/...                     ← tests
```

The parent POM manages versions, so starters have no version numbers:

```xml
<parent>
  <groupId>org.springframework.boot</groupId>
  <artifactId>spring-boot-starter-parent</artifactId>
  <version>3.3.4</version>
</parent>

<dependencies>
  <dependency>
    <groupId>org.springframework.boot</groupId>
    <artifactId>spring-boot-starter-web</artifactId>
  </dependency>
  <dependency>
    <groupId>org.springframework.boot</groupId>
    <artifactId>spring-boot-starter-data-jpa</artifactId>
  </dependency>
</dependencies>
```

## Common starters

| Starter | Gives you |
|---|---|
| `spring-boot-starter-web` | REST APIs and MVC with embedded Tomcat |
| `spring-boot-starter-data-jpa` | JPA + Hibernate + Spring Data repositories |
| `spring-boot-starter-validation` | Bean Validation (`@NotBlank`, `@Email`…) |
| `spring-boot-starter-security` | authentication and authorisation |
| `spring-boot-starter-test` | JUnit 5, Mockito, AssertJ, MockMvc |
| `spring-boot-starter-actuator` | health, metrics, info endpoints |
| `spring-boot-starter-thymeleaf` | server-side HTML templates |
| `spring-boot-starter-mail`, `-cache`, `-amqp`, `-data-redis`… | integrations |

## Running and building

```bash
./mvnw spring-boot:run          # run in development
./mvnw clean package            # build target/course-api-0.0.1-SNAPSHOT.jar
java -jar target/course-api-0.0.1-SNAPSHOT.jar --server.port=9090
```

**DevTools** restarts the app automatically when you recompile, and disables template caching in development.

::tip To see what auto-configuration did and why, start the app with `--debug`. The "CONDITIONS EVALUATION REPORT" lists every auto-configuration that matched or didn't. It's the best way to understand Boot's "magic".

## Exercise

1. Generate a project on start.spring.io with Spring Web and run it.
2. Change the port to 9090 in `application.properties` (`server.port=9090`) and restart.
3. Run with `--debug` and find `DataSourceAutoConfiguration` in the report. Did it match? Why or why not?

::hint Hint for 3 | Without a database driver or Spring Data on the classpath, DataSourceAutoConfiguration doesn't match ("did not find required class"). Add H2 and it will.

## Interview corner

- **What is Spring Boot?** An opinionated extension of Spring offering starters, auto-configuration, an embedded server and production-ready features.
- **What does @SpringBootApplication do?** Combines @SpringBootConfiguration, @EnableAutoConfiguration and @ComponentScan.
- **How does auto-configuration work?** Configuration classes guarded by conditions (@ConditionalOnClass, @ConditionalOnMissingBean, @ConditionalOnProperty) create beans when their conditions match.
- **What is a starter?** A dependency descriptor that brings a consistent set of libraries for a feature.

## Summary

- Spring Boot = Spring + starters + auto-configuration + embedded server.
- `@SpringBootApplication` in the root package scans everything below it.
- Create projects at start.spring.io; run with `./mvnw spring-boot:run` or `java -jar`.

Next: build your first REST API.
