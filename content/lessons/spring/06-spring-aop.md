---
title: Spring AOP - Aspects, Advice and Proxies
slug: spring-aop
subject: spring
lesson: 6
difficulty: advanced
excerpt: Add logging, timing, security and auditing around methods without touching them. Learn aspects, pointcuts and @Around/@Before/@AfterReturning advice, how Spring proxies work, and the self-invocation trap.
---

Some code belongs everywhere: "log how long this took", "check the user is an admin", "record who changed this". Copying it into every method buries your business logic. **Aspect-Oriented Programming (AOP)** lets you write it once and apply it around many methods.

## Cross-cutting concerns

```java
public Order placeOrder(Order o) {
    long start = System.nanoTime();                 // timing
    log.info("placeOrder called by {}", user());    // logging
    if (!security.canOrder(user())) throw ...;      // security
    try {
        tx.begin();                                 // transactions
        // ↓ the only line that is actually about orders
        Order saved = repo.save(o);
        tx.commit();
        return saved;
    } catch (Exception e) { tx.rollback(); throw e; }
    finally { metrics.record(System.nanoTime() - start); }
}
```

With AOP, the method shrinks to its real job, and the rest is applied by annotations or rules.

## Vocabulary

| Term | Meaning |
|---|---|
| **Aspect** | a class containing cross-cutting logic (`@Aspect`) |
| **Advice** | the code to run, and when: `@Before`, `@After`, `@AfterReturning`, `@AfterThrowing`, `@Around` |
| **Join point** | a point in execution, in Spring always a **method call** on a bean |
| **Pointcut** | an expression selecting join points (which methods) |
| **Proxy** | the wrapper object Spring creates to run advice around the real bean |

## A timing aspect

```java
package com.vitalstack;

import java.lang.annotation.*;

@Target(ElementType.METHOD)
@Retention(RetentionPolicy.RUNTIME)
public @interface Timed { }
```

```java
package com.vitalstack;

import org.aspectj.lang.ProceedingJoinPoint;
import org.aspectj.lang.annotation.*;
import org.springframework.stereotype.Component;

@Aspect
@Component
public class TimingAspect {

    @Around("@annotation(com.vitalstack.Timed)")              // pointcut: methods annotated @Timed
    public Object time(ProceedingJoinPoint jp) throws Throwable {
        long start = System.nanoTime();
        try {
            return jp.proceed();                                // call the real method
        } finally {
            long ms = (System.nanoTime() - start) / 1_000_000;
            System.out.println("[timed] " + jp.getSignature().toShortString() + " took ~" + (ms / 100 * 100) + " ms");
        }
    }

    @AfterThrowing(pointcut = "execution(* com.vitalstack.ReportService.*(..))", throwing = "ex")
    public void logError(Exception ex) {
        System.out.println("[error] " + ex.getMessage());
    }
}
```

```java
package com.vitalstack;

import org.springframework.stereotype.Service;

@Service
public class ReportService {
    @Timed
    public String monthly() throws InterruptedException {
        Thread.sleep(300);                                      // pretend to work
        return "report ready";
    }

    public void broken() {
        throw new IllegalStateException("no data for this month");
    }

    public String selfCall() throws InterruptedException {
        return monthly();                                       // calls itself: NOT through the proxy
    }
}
```

```java
package com.vitalstack;

import org.springframework.context.annotation.*;

@Configuration
@ComponentScan
@EnableAspectJAutoProxy                                         // Spring Boot enables this automatically
public class Main {
    public static void main(String[] args) throws Exception {
        try (var ctx = new AnnotationConfigApplicationContext(Main.class)) {
            ReportService reports = ctx.getBean(ReportService.class);
            System.out.println("bean class: " + reports.getClass().getSimpleName());
            System.out.println(reports.monthly());
            try { reports.broken(); } catch (IllegalStateException ignored) { }
            System.out.println("self call: " + reports.selfCall());
        }
    }
}
```

Output:

```text
bean class: ReportService$$SpringCGLIB$$0
[timed] ReportService.monthly() took ~300 ms
report ready
[error] no data for this month
self call: report ready
```

`ReportService` itself never mentions timing or error logging.

## How it works: proxies

The bean you receive isn't your `ReportService`. It's a generated **proxy subclass** (`ReportService$$SpringCGLIB$$0`). Calls go through the proxy, which runs the advice and then calls the real method:

```text
caller ──▶ [proxy: advice before] ──▶ real ReportService.monthly() ──▶ [proxy: advice after] ──▶ caller
```

Spring uses JDK dynamic proxies for interfaces and CGLIB subclasses for classes. This is how `@Transactional`, `@Async`, `@Cacheable`, `@PreAuthorize` and `@Retryable` all work.

## The self-invocation trap

Look at the last output line: `selfCall()` called `monthly()`, but **no timing was printed**. Inside the object, `this.monthly()` calls the real method directly, bypassing the proxy. The same trap breaks `@Transactional` on internal calls (next lesson).

Fixes: move the method to another bean (cleanest), or inject the bean into itself lazily.

Other proxy limits: advice doesn't apply to `private` or `final` methods, or to objects you create with `new`.

## Pointcut expressions

| Expression | Matches |
|---|---|
| `execution(* com.vitalstack.service.*.*(..))` | any method of any class in the service package |
| `execution(public * *(..))` | any public method |
| `within(com.vitalstack..*)` | anything in the package and sub-packages |
| `@annotation(com.vitalstack.Timed)` | methods with the annotation |
| `bean(*Service)` | beans whose name ends with Service |

## Which advice?

| Advice | Runs | Use for |
|---|---|---|
| `@Before` | before the method | security checks, logging inputs |
| `@AfterReturning` | after success (can read the result) | auditing, metrics |
| `@AfterThrowing` | after an exception | error logging, alerts |
| `@After` | always (like finally) | cleanup |
| `@Around` | wraps the call; must call `proceed()` | timing, retries, caching, transactions |

::tip Use AOP for truly cross-cutting concerns. For regular business logic, plain method calls are easier to read and debug. Too much "magic" makes code hard to follow.

## Exercise

1. Write an `@AuditLog` annotation and an aspect that prints the method name and arguments before each annotated method.
2. Write an `@Around` retry aspect that retries a method up to 3 times when it throws `IOException`.
3. Fix the self-invocation in `ReportService` by moving `monthly()` into a separate `MonthlyReport` bean.

::hint Hint for 1 | `@Before("@annotation(com.vitalstack.AuditLog)") public void log(JoinPoint jp) { System.out.println(jp.getSignature().getName() + " " + Arrays.toString(jp.getArgs())); }`

## Interview corner

- **What is AOP?** A way to modularise cross-cutting concerns (logging, security, transactions) into aspects applied around methods.
- **How does Spring implement AOP?** With runtime proxies (JDK dynamic proxies or CGLIB subclasses) around beans.
- **Advice types?** Before, AfterReturning, AfterThrowing, After (finally), Around.
- **Why doesn't advice run on internal method calls?** Self-invocation calls the target directly, bypassing the proxy.

## Summary

- Aspects hold cross-cutting code; pointcuts choose methods; advice says when it runs.
- Spring wraps beans in proxies; calls through the proxy get the advice.
- Self-invocation, private and final methods bypass proxies.

Next: @Transactional, the most important aspect of all.
