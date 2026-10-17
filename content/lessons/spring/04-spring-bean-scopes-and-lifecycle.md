---
title: Spring Bean Scopes and Lifecycle
slug: spring-bean-scopes-and-lifecycle
subject: spring
lesson: 4
difficulty: intermediate
excerpt: Compare singleton and prototype scopes in an animation, learn request and session scopes, hook into the lifecycle with @PostConstruct and @PreDestroy, lazy beans, and the prototype-in-singleton trap.
---

Spring decides **how many** instances of a bean exist and **when** they are created and destroyed. Getting this wrong causes some of the nastiest bugs: one user seeing another user's data.

## Singleton vs prototype, animated

Switch the scope and watch whether `a` and `b` point to the same cart:

::viz bean-scope {"scope": "singleton"}

| Scope | Instances | Typical use |
|---|---|---|
| `singleton` (default) | one per container | stateless services, repositories, controllers |
| `prototype` | a new one every time it's requested | stateful helper objects |
| `request` (web) | one per HTTP request | request-specific data |
| `session` (web) | one per user session | user preferences, wizard state |
| `application` (web) | one per ServletContext | like singleton, app-wide |

## The golden rule of singletons

Singletons are shared by **every request and every user, on many threads at once**. So:

- ✅ Keep them **stateless**: only final dependencies, no per-user fields.
- ❌ Never store "current user", "current order" or counters in a singleton's fields.

```java
@Service
public class CartService {
    private List<String> items = new ArrayList<>();   // ❌ every user shares ONE cart!
}
```

Store per-user state in the database, the session, or pass it as method parameters.

## Lifecycle callbacks

```java
package com.vitalstack;

import jakarta.annotation.PostConstruct;
import jakarta.annotation.PreDestroy;
import org.springframework.context.annotation.*;
import org.springframework.stereotype.Component;

@Configuration
@ComponentScan
public class Main {

    @Component
    static class PriceCache {
        PriceCache() { System.out.println("1. constructor"); }

        @PostConstruct                      // after dependencies are injected
        void load() { System.out.println("2. @PostConstruct: warm up the cache"); }

        @PreDestroy                         // before the container shuts down
        void close() { System.out.println("4. @PreDestroy: release resources"); }
    }

    @Component
    @Scope("prototype")
    static class Ticket {
        private static int counter = 0;
        final int number = ++counter;
    }

    public static void main(String[] args) {
        try (var ctx = new AnnotationConfigApplicationContext(Main.class)) {
            System.out.println("3. container ready");
            Ticket t1 = ctx.getBean(Ticket.class);
            Ticket t2 = ctx.getBean(Ticket.class);
            System.out.println("tickets: " + t1.number + ", " + t2.number + " (same object? " + (t1 == t2) + ")");
            PriceCache c1 = ctx.getBean(PriceCache.class), c2 = ctx.getBean(PriceCache.class);
            System.out.println("cache singleton? " + (c1 == c2));
        }
    }
}
```

Output:

```text
1. constructor
2. @PostConstruct: warm up the cache
3. container ready
tickets: 1, 2 (same object? false)
cache singleton? true
4. @PreDestroy: release resources
```

The full lifecycle of a singleton:

1. Instantiate (constructor) → 2. Inject dependencies → 3. `Aware` callbacks → 4. `BeanPostProcessor` before-init → 5. `@PostConstruct` / `afterPropertiesSet()` / `initMethod` → 6. `BeanPostProcessor` after-init (**proxies for @Transactional etc. are created here**) → 7. Ready → 8. `@PreDestroy` / `destroy()` on shutdown.

::tip Spring does **not** call `@PreDestroy` on prototype beans: once handed out, they're yours to clean up.

## Eager vs lazy

Singletons are created **at startup** (eager). Good: configuration errors show up immediately, not on the first user request. Mark rarely used, expensive beans `@Lazy` to create them on first use.

## The prototype-in-singleton trap

If a singleton gets a prototype injected through its constructor, it receives **one** prototype instance, once, at startup, and keeps it forever. The prototype effectively becomes a singleton.

Fix: ask for a new instance each time with `ObjectProvider`:

```java
@Service
public class TicketService {
    private final ObjectProvider<Ticket> tickets;

    public TicketService(ObjectProvider<Ticket> tickets) { this.tickets = tickets; }

    public Ticket issue() {
        return tickets.getObject();     // a fresh prototype every call
    }
}
```

## Request and session scopes

In web apps you can scope beans to a request or session. Because they're injected into singletons, Spring injects a **proxy** that finds the right instance for the current request:

```java
@Component
@SessionScope                       // = @Scope(value = "session", proxyMode = TARGET_CLASS)
public class RecentlyViewed {
    private final Deque<String> courses = new ArrayDeque<>();
    public void add(String slug) { courses.addFirst(slug); if (courses.size() > 5) courses.removeLast(); }
    public List<String> list() { return List.copyOf(courses); }
}
```

## Exercise

1. Run the example and add a second `@PostConstruct` bean that depends on `PriceCache`. Which `@PostConstruct` runs first?
2. Inject the prototype `Ticket` into a singleton's constructor, call `getBean` on the singleton twice and print the ticket number. Then fix it with `ObjectProvider`.
3. Find the bug: a `@Service` with a field `private int requestCount;` incremented in every call. What goes wrong under load, and how would you fix it?

::hint Hint for 3 | Many threads update the same field at once, so counts get lost (race condition). Use an `AtomicInteger`, or better, a metrics library like Micrometer.

## Interview corner

- **Default scope?** Singleton: one instance per ApplicationContext.
- **Are singleton beans thread-safe?** Not automatically. They're safe only if stateless or properly synchronised.
- **Singleton vs prototype?** One shared instance vs a new instance per request to the container; Spring doesn't manage prototype destruction.
- **How do you inject a prototype into a singleton?** ObjectProvider/Provider, lookup method injection (`@Lookup`), or a scoped proxy.
- **Bean lifecycle?** Instantiate → populate → aware callbacks → BeanPostProcessors → init (@PostConstruct) → ready → destroy (@PreDestroy).

## Summary

- Singleton (default) beans are shared: keep them stateless.
- Prototype gives a new instance per lookup; use ObjectProvider inside singletons.
- `@PostConstruct` for setup after injection, `@PreDestroy` for cleanup.

Next: externalised configuration with properties and profiles.
