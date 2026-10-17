---
title: Spring Framework Introduction
slug: spring-introduction
subject: spring
lesson: 1
difficulty: beginner
excerpt: What the Spring Framework is, the problems it solves (tight coupling, boilerplate, plumbing), its main modules, how Spring differs from Spring Boot, and why it dominates Java back-end jobs.
---

**Spring** is the most popular framework for building Java applications. Banks, e-commerce sites, Netflix, and most Java back-end jobs in India use it. Spring Boot (next course) is built on top of it, so understanding Spring's core ideas makes everything in Spring Boot click.

## The problem Spring solves

Imagine an `OrderService` that needs a repository, a payment gateway and an email sender:

```java
public class OrderService {
    private final OrderRepository repo = new MySqlOrderRepository(new HikariDataSource(config));
    private final PaymentGateway payments = new RazorpayGateway("key", "secret");
    private final EmailSender email = new SmtpEmailSender("smtp.gmail.com", 587);

    public void placeOrder(Order order) { ... }
}
```

Problems:

- **Tight coupling:** `OrderService` decides which database, which payment provider and which email server. Changing any of them means editing this class.
- **Hard to test:** you can't test `placeOrder` without a real database, a real payment account and a real mail server.
- **Duplicated setup:** every class that needs a database creates its own connection pool.
- **Plumbing everywhere:** transactions, security checks and logging get copied into every method.

## Spring's answer

1. **Inversion of Control (IoC) and Dependency Injection (DI):** your classes **declare** what they need; Spring **creates and connects** them.

```java
@Service
public class OrderService {
    private final OrderRepository repo;
    private final PaymentGateway payments;
    private final EmailSender email;

    public OrderService(OrderRepository repo, PaymentGateway payments, EmailSender email) {
        this.repo = repo;            // Spring passes these in
        this.payments = payments;
        this.email = email;
    }
}
```

Now `OrderService` depends only on interfaces. Swap Razorpay for Paytm, or pass fakes in tests, without touching it.

2. **AOP (Aspect-Oriented Programming):** add transactions, security and logging **around** methods with annotations like `@Transactional`, instead of copying code.

3. **Ready-made integrations:** database access, web MVC, REST, security, messaging, caching, scheduling, testing.

## The Spring ecosystem

| Project | What it gives you |
|---|---|
| **Spring Framework** (core) | IoC container, DI, AOP, transactions, MVC, JDBC support |
| **Spring Boot** | auto-configuration, embedded server, starters: run apps with almost no setup |
| **Spring Data JPA** | repositories with no implementation code |
| **Spring Security** | authentication, authorisation, JWT/OAuth2 |
| **Spring Cloud** | microservices: config server, service discovery, gateways |
| **Spring Batch** | large batch jobs |
| **Spring WebFlux** | reactive, non-blocking web apps |

## Spring vs Spring Boot

| | Spring Framework | Spring Boot |
|---|---|---|
| What | the core framework | an opinionated way to use Spring |
| Configuration | you configure everything (XML or Java config) | auto-configured with sensible defaults |
| Server | deploy a WAR to Tomcat | embedded Tomcat: `java -jar app.jar` |
| Dependencies | pick compatible versions yourself | "starters" with tested versions |
| Use today | understanding the foundations, older projects | almost every new project |

Spring Boot **is** Spring: same annotations, same container, same concepts. Boot just removes the setup work.

## Key vocabulary

- **Bean**: an object created and managed by Spring.
- **ApplicationContext**: the Spring container that creates, wires and manages beans.
- **Dependency injection**: Spring supplies a bean's dependencies (usually through the constructor).
- **Component scanning**: Spring finds classes annotated with `@Component`, `@Service`, `@Repository`, `@Controller`.
- **Configuration class**: a class with `@Configuration` and `@Bean` methods that define beans.
- **Proxy**: a wrapper Spring puts around a bean to add behaviour (transactions, security).

::tip You'll learn faster if you remember one sentence: *Spring creates your objects, connects them, and wraps them with extra behaviour.* Every lesson in this course is one part of that sentence.

## Exercise

1. List the dependencies of a `CourseService` that saves courses, sends a welcome email and charges a fee.
2. Rewrite its constructor so it receives those dependencies instead of creating them.
3. Explain why the rewritten class is easier to test.

::hint Hint for 3 | In a test you can pass a fake `EmailSender` that just records the messages, and a fake payment gateway that always succeeds or always fails, with no network calls.

## Interview corner

- **What is Spring?** A comprehensive Java framework providing an IoC container, DI, AOP and integrations for building enterprise applications.
- **What is IoC?** Inversion of Control: the framework, not your code, creates and wires objects.
- **Spring vs Spring Boot?** Boot is Spring with auto-configuration, starters and an embedded server.
- **What is a bean?** An object instantiated, assembled and managed by the Spring container.

## Summary

- Spring removes tight coupling and plumbing with IoC/DI and AOP.
- The ecosystem covers web, data, security, cloud and batch.
- Spring Boot is the fast way to use Spring; the core concepts are the same.

Next: IoC and dependency injection, animated.
