---
title: SOLID Principles and Design Patterns in Java
slug: java-oop-solid-and-design-patterns
subject: java-oop
lesson: 11
difficulty: advanced
excerpt: Write maintainable Java with the five SOLID principles explained through simple before/after examples, plus the most asked design patterns - Singleton, Factory, Strategy, Builder and Observer.
---

Knowing classes and inheritance isn't enough. Big codebases need structure so that a change in one place doesn't break five others. **SOLID** is a set of five principles for that, and **design patterns** are proven solutions to common design problems. Both are standard questions in Java interviews from 2 years of experience onwards.

## S: Single Responsibility Principle

> A class should have one reason to change.

```java
// ❌ One class doing everything
class Invoice {
    double total() { ... }
    void printPdf() { ... }        // presentation
    void saveToDatabase() { ... }  // persistence
    void emailCustomer() { ... }   // notification
}

// ✅ Each class has one job
class Invoice { double total() { ... } }
class InvoicePdfPrinter { void print(Invoice i) { ... } }
class InvoiceRepository { void save(Invoice i) { ... } }
class InvoiceMailer { void send(Invoice i) { ... } }
```

When the PDF layout changes, only `InvoicePdfPrinter` changes.

## O: Open/Closed Principle

> Open for extension, closed for modification.

```java
// ❌ Every new payment type means editing this method
double fee(String type, double amount) {
    if (type.equals("UPI")) return 0;
    else if (type.equals("CARD")) return amount * 0.02;
    // add another else-if for each new type...
}

// ✅ Add a new class instead of editing old code
interface Payment { double fee(double amount); }
class Upi implements Payment { public double fee(double a) { return 0; } }
class Card implements Payment { public double fee(double a) { return a * 0.02; } }
class Wallet implements Payment { public double fee(double a) { return a * 0.01; } }   // new: nothing else changes
```

This is polymorphism used as a design principle.

## L: Liskov Substitution Principle

> A subclass must be usable anywhere its parent is expected, without surprises.

The classic violation: `Square extends Rectangle`. Setting a square's width also changes its height, so code that does `r.setWidth(5); r.setHeight(4);` expects area 20 but gets 16. If a subclass has to break the parent's promises (throwing "not supported", ignoring calls), the inheritance is wrong. Use separate classes or a common interface.

## I: Interface Segregation Principle

> Don't force classes to implement methods they don't use.

```java
// ❌ A fat interface
interface Worker { void code(); void test(); void cookLunch(); }

// ✅ Small, focused interfaces
interface Coder { void code(); }
interface Tester { void test(); }
class Developer implements Coder, Tester { ... }
```

## D: Dependency Inversion Principle

> Depend on abstractions, not concrete classes.

```java
// ❌ OrderService is glued to one SMS provider
class OrderService {
    private final Msg91Sms sms = new Msg91Sms();
}

// ✅ Depend on an interface, receive the implementation from outside
interface Notifier { void send(String to, String msg); }

class OrderService {
    private final Notifier notifier;
    OrderService(Notifier notifier) { this.notifier = notifier; }   // constructor injection
    void place(String phone) { notifier.send(phone, "Order placed!"); }
}
```

Now you can swap SMS for email or WhatsApp, or pass a fake in tests. **This is exactly what Spring's dependency injection automates**: you'll see it animated in the Spring course.

## Design patterns you must know

### Singleton: exactly one instance

```java
public final class AppConfig {
    private static final AppConfig INSTANCE = new AppConfig();   // created once, thread-safe
    private AppConfig() { }                                      // nobody else can call new
    public static AppConfig getInstance() { return INSTANCE; }
}
```

Use sparingly: singletons are global state and make testing harder. (In Spring, beans are singletons by default, managed by the container instead.)

### Factory: hide object creation

```java
class NotifierFactory {
    static Notifier create(String channel) {
        return switch (channel) {
            case "sms" -> new SmsNotifier();
            case "email" -> new EmailNotifier();
            default -> throw new IllegalArgumentException("Unknown channel: " + channel);
        };
    }
}
```

Callers ask for "sms" and don't need to know the class names.

### Strategy: swap algorithms at runtime

```java
import java.util.function.DoubleUnaryOperator;

public class StrategyDemo {
    public static void main(String[] args) {
        DoubleUnaryOperator noDiscount = p -> p;
        DoubleUnaryOperator festive = p -> p * 0.8;
        DoubleUnaryOperator flat200 = p -> Math.max(0, p - 200);

        for (DoubleUnaryOperator strategy : new DoubleUnaryOperator[] { noDiscount, festive, flat200 }) {
            System.out.println(strategy.applyAsDouble(1500));
        }
    }
}
```

```text
1500.0
1200.0
1300.0
```

Each discount rule is a strategy object (here a lambda). The checkout code just calls `applyAsDouble`.

### Builder: readable construction of complex objects

```java
public class BuilderDemo {
    static class Pizza {
        private final String size; private final boolean cheese; private final boolean olives;
        private Pizza(Builder b) { size = b.size; cheese = b.cheese; olives = b.olives; }
        public String toString() { return size + " pizza, cheese=" + cheese + ", olives=" + olives; }

        static class Builder {
            private String size = "Medium"; private boolean cheese; private boolean olives;
            Builder size(String s) { size = s; return this; }
            Builder cheese() { cheese = true; return this; }
            Builder olives() { olives = true; return this; }
            Pizza build() { return new Pizza(this); }
        }
    }

    public static void main(String[] args) {
        Pizza p = new Pizza.Builder().size("Large").cheese().build();
        System.out.println(p);    // Large pizza, cheese=true, olives=false
    }
}
```

Much clearer than `new Pizza("Large", true, false, false, true…)`. Lombok's `@Builder` generates this for you in Spring projects.

### Observer: notify many listeners

```java
interface OrderListener { void onPlaced(String orderId); }

class OrderEvents {
    private final List<OrderListener> listeners = new ArrayList<>();
    void subscribe(OrderListener l) { listeners.add(l); }
    void placed(String id) { listeners.forEach(l -> l.onPlaced(id)); }
}

events.subscribe(id -> System.out.println("Email sent for " + id));
events.subscribe(id -> System.out.println("Stock updated for " + id));
events.placed("ORD-1001");
```

Spring's `ApplicationEvent`s, JavaScript's `addEventListener` and RxJS all build on this idea.

::tip Don't force patterns into code. Learn to recognise the problem first ("I keep adding else-ifs for types" → Strategy/polymorphism; "constructor with 8 parameters" → Builder). Patterns are vocabulary for discussing designs, not goals.

## Exercise

1. Find an SRP violation in code you wrote earlier and split the class.
2. Refactor an `if/else` on a "type" String into polymorphic classes (OCP).
3. Write a `ShippingCostStrategy` interface with `Standard`, `Express` and `FreeAbove999` implementations.
4. Implement a `Notifier` interface with `Sms` and `Email` versions and inject one into `OrderService` through the constructor.

::hint Hint for 3 | `FreeAbove999` returns 0 when the cart total is above 999, otherwise delegates to `Standard`.

## Interview corner

- **Explain SOLID.** Single responsibility, Open/closed, Liskov substitution, Interface segregation, Dependency inversion (be ready with one example each).
- **How do you make a thread-safe singleton?** Eager static final instance, the holder idiom, or an enum.
- **Factory vs Builder?** Factory decides **which** class to create; Builder handles **how** to assemble a complex object step by step.
- **Which patterns does Spring use?** Singleton (beans), Factory (BeanFactory), Proxy (AOP, @Transactional), Template (JdbcTemplate), Observer (events), Strategy, Dependency Injection.

## Summary

- SOLID keeps classes focused, extensible and loosely coupled.
- Dependency inversion + constructor injection is the heart of Spring.
- Know Singleton, Factory, Strategy, Builder and Observer, and when each fits.

Next: a project that puts OOP into practice.
