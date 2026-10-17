---
title: Spring IoC Container and Dependency Injection
slug: spring-ioc-and-dependency-injection
subject: spring
lesson: 2
difficulty: beginner
excerpt: Watch the Spring container scan, build and wire beans in an animation, then run a real ApplicationContext with @Component, @Service, @Repository and constructor injection. Learn why constructor injection beats field injection.
---

The heart of Spring is the **IoC container** (the `ApplicationContext`). You mark your classes, and the container finds them, creates them in the right order, and passes each one the objects it needs. That's **dependency injection (DI)**.

## Watch the container work

::viz spring-ioc

1. **Scan:** find classes annotated with `@Component` (and its specialisations).
2. **Analyse:** read constructors to build a dependency graph.
3. **Create:** build beans with no dependencies first.
4. **Inject:** pass finished beans into the constructors of beans that need them.
5. **Ready:** every bean is a singleton by default: one shared instance.

## A runnable example

Add `spring-context` (Spring Boot projects already have it):

```xml
<dependency>
  <groupId>org.springframework</groupId>
  <artifactId>spring-context</artifactId>
  <version>6.1.13</version>
</dependency>
```

```java
package com.vitalstack.shop;

public interface Notifier {
    void send(String to, String message);
}
```

```java
package com.vitalstack.shop;

import org.springframework.stereotype.Component;

@Component
public class EmailNotifier implements Notifier {
    @Override
    public void send(String to, String message) {
        System.out.println("EMAIL to " + to + ": " + message);
    }
}
```

```java
package com.vitalstack.shop;

import org.springframework.stereotype.Repository;
import java.util.ArrayList;
import java.util.List;

@Repository
public class OrderRepository {
    private final List<String> orders = new ArrayList<>();
    public int save(String item) { orders.add(item); return orders.size(); }
}
```

```java
package com.vitalstack.shop;

import org.springframework.stereotype.Service;

@Service
public class OrderService {
    private final OrderRepository repo;
    private final Notifier notifier;

    public OrderService(OrderRepository repo, Notifier notifier) {   // constructor injection
        this.repo = repo;
        this.notifier = notifier;
    }

    public void placeOrder(String customer, String item) {
        int id = repo.save(item);
        notifier.send(customer, "Order #" + id + " for " + item + " confirmed");
    }
}
```

```java
package com.vitalstack;

import com.vitalstack.shop.OrderService;
import org.springframework.context.annotation.AnnotationConfigApplicationContext;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.context.annotation.Configuration;
import java.util.Arrays;

@Configuration
@ComponentScan("com.vitalstack.shop")            // where to look for components
public class Main {
    public static void main(String[] args) {
        try (var context = new AnnotationConfigApplicationContext(Main.class)) {
            OrderService service = context.getBean(OrderService.class);
            service.placeOrder("asha@example.com", "Java course");
            service.placeOrder("asha@example.com", "Spring course");

            System.out.println("Same instance? " + (service == context.getBean(OrderService.class)));
            Arrays.stream(context.getBeanDefinitionNames())
                  .filter(n -> !n.startsWith("org.springframework"))
                  .sorted()
                  .forEach(n -> System.out.println("bean: " + n));
        }
    }
}
```

Output:

```text
EMAIL to asha@example.com: Order #1 for Java course confirmed
EMAIL to asha@example.com: Order #2 for Spring course confirmed
Same instance? true
bean: emailNotifier
bean: main
bean: orderRepository
bean: orderService
```

You never wrote `new OrderService(...)`. Spring saw that `OrderService` needs an `OrderRepository` and a `Notifier`, found one bean of each type (`EmailNotifier` implements `Notifier`), and wired them. Bean names default to the class name with a lowercase first letter.

## The stereotype annotations

| Annotation | Use for | Extra behaviour |
|---|---|---|
| `@Component` | any Spring-managed class | – |
| `@Service` | business logic | none (just clearer intent) |
| `@Repository` | data access | translates database exceptions into Spring's `DataAccessException` |
| `@Controller` / `@RestController` | web layer | request handling (Spring MVC) |
| `@Configuration` | classes with `@Bean` methods | proxied so `@Bean` methods return singletons |

## Three ways to inject

```java
// 1. Constructor injection ✅ recommended
@Service
public class OrderService {
    private final OrderRepository repo;
    public OrderService(OrderRepository repo) { this.repo = repo; }   // @Autowired optional with one constructor
}

// 2. Setter injection: for optional dependencies
@Autowired
public void setAuditLog(AuditLog log) { this.log = log; }

// 3. Field injection ❌ avoid
@Autowired
private OrderRepository repo;
```

Why constructor injection wins:

- Dependencies can be `final`: the object is always complete and immutable.
- Required dependencies are obvious from the constructor.
- Easy to test: `new OrderService(fakeRepo, fakeNotifier)` with no Spring needed.
- Circular dependencies fail fast at startup instead of hiding.
- Too many constructor parameters is a visible warning that the class does too much.

::tip With Lombok, `@RequiredArgsConstructor` generates the constructor for all `final` fields. That's why so many Spring Boot classes look like `@Service @RequiredArgsConstructor public class X { private final Y y; }`.

## Testing without Spring

Because `OrderService` just takes interfaces in its constructor, a unit test needs no container:

```java
List<String> sent = new ArrayList<>();
Notifier fake = (to, msg) -> sent.add(msg);
OrderService service = new OrderService(new OrderRepository(), fake);
service.placeOrder("x@y.com", "Book");
assert sent.get(0).contains("Order #1");
```

That's the real payoff of DI.

## Common errors

- **`NoSuchBeanDefinitionException`**: no bean of the required type. Is the class annotated? Is it inside the scanned package?
- **`NoUniqueBeanDefinitionException`**: two beans implement the interface; Spring doesn't know which to inject (next lesson: `@Primary`, `@Qualifier`).
- **Circular dependency**: A needs B and B needs A. Redesign (extract a third class) instead of forcing it.

## Exercise

1. Run the example. Then add an `SmsNotifier implements Notifier` with `@Component`. What error do you get?
2. Add a `DiscountService` bean and inject it into `OrderService` to apply 10% off.
3. Write a unit test for `OrderService` with a fake notifier, without starting Spring.

::hint Hint for 1 | `NoUniqueBeanDefinitionException: expected single matching bean but found 2: emailNotifier,smsNotifier`. You'll fix it in the next lesson.

## Interview corner

- **What is dependency injection?** A design where an object's dependencies are supplied from outside (by the container) instead of created inside.
- **IoC vs DI?** IoC is the principle (the framework controls object creation); DI is the technique that implements it.
- **Constructor vs field injection?** Constructor injection supports final fields, explicit required dependencies and easy testing; field injection hides dependencies and needs reflection.
- **@Component vs @Service vs @Repository?** All register beans; @Service marks business logic, @Repository adds persistence exception translation.

## Summary

- The ApplicationContext scans, creates and wires beans.
- Mark classes with `@Component`/`@Service`/`@Repository`; prefer constructor injection.
- DI makes classes loosely coupled and easy to test.

Next: @Configuration, @Bean, and choosing between multiple beans.
