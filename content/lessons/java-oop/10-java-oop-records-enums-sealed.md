---
title: Java Records, Enums and Sealed Classes
slug: java-oop-records-enums-sealed
subject: java-oop
lesson: 10
difficulty: intermediate
excerpt: Modern Java OOP features. Write data classes in one line with records, model fixed sets of values with enums (fields, methods, switch), and control inheritance with sealed classes.
---

Modern Java (16 to 21) added features that make OOP code shorter and safer. You'll see them in every new Spring Boot project and in interviews for Java 17/21 roles.

## Records: data classes in one line

Before records, a simple class holding a name and price needed a constructor, getters, `equals`, `hashCode` and `toString`: 40+ lines. Now:

```java
record Product(String name, double price) { }
```

That one line gives you:

- `private final` fields `name` and `price`
- a constructor `new Product("Pen", 20)`
- accessors `name()` and `price()` (no `get` prefix)
- `equals`, `hashCode` and `toString`

```java
public class RecordDemo {
    record Product(String name, double price) {
        Product {                                   // compact constructor: validation
            if (price < 0) throw new IllegalArgumentException("price < 0");
            name = name.trim();
        }
        double withGst() { return price * 1.18; }   // records can have methods
        static Product free(String name) { return new Product(name, 0); }   // and static factories
    }

    public static void main(String[] args) {
        Product p = new Product("  Notebook ", 120);
        System.out.println(p);                      // Product[name=Notebook, price=120.0]
        System.out.println(p.name() + " " + p.withGst());
        System.out.println(p.equals(new Product("Notebook", 120)));   // true
        System.out.println(Product.free("Sticker"));                   // Product[name=Sticker, price=0.0]
    }
}
```

Records are **immutable** and **can't extend** other classes (they can implement interfaces). Use them for DTOs (data sent to/from APIs), results, keys and values. In Spring Boot, request and response bodies are often records.

## Enums: a fixed set of values

When something can only be one of a few values (days, order statuses, user roles), use an **enum** instead of Strings or ints:

```java
enum OrderStatus { PLACED, PACKED, SHIPPED, DELIVERED, CANCELLED }

OrderStatus s = OrderStatus.SHIPPED;
System.out.println(s);                 // SHIPPED
System.out.println(s.ordinal());       // 2 (position, starts at 0)
System.out.println(OrderStatus.valueOf("PACKED"));   // String → enum
for (OrderStatus st : OrderStatus.values()) System.out.print(st + " ");
```

With Strings, a typo like `"SHIPED"` compiles and causes bugs. With enums, the compiler catches it.

### Enums with fields and methods

Enums are full classes:

```java
public class PlanDemo {
    enum Plan {
        FREE(0, 3), PRO(499, 50), TEAM(1999, 500);

        private final int monthlyPrice;
        private final int maxProjects;

        Plan(int monthlyPrice, int maxProjects) {      // enum constructors are private
            this.monthlyPrice = monthlyPrice;
            this.maxProjects = maxProjects;
        }

        int yearlyPrice() { return monthlyPrice * 10; }   // 2 months free
        boolean allows(int projects) { return projects <= maxProjects; }
    }

    public static void main(String[] args) {
        Plan p = Plan.PRO;
        System.out.println(p + " costs ₹" + p.yearlyPrice() + "/year");   // PRO costs ₹4990/year
        System.out.println(Plan.FREE.allows(5));                            // false
    }
}
```

### Switch on enums

```java
String message = switch (status) {
    case PLACED, PACKED -> "Getting your order ready";
    case SHIPPED -> "On the way";
    case DELIVERED -> "Enjoy!";
    case CANCELLED -> "Refund in 3-5 days";
};
```

With the arrow switch, if you forget a constant the compiler complains: no `default` needed. Enums are also the simplest correct way to make a **singleton** (`enum AppConfig { INSTANCE; }`).

::tip When storing enums in a database with JPA, use `@Enumerated(EnumType.STRING)`. The default (ORDINAL) stores 0, 1, 2… and breaks if you ever reorder the constants.

## Sealed classes: controlled inheritance (Java 17)

Sometimes you want a **closed** set of subtypes: a payment is UPI, Card or Cash, and nothing else. `sealed` lists exactly who may extend:

```java
sealed interface Payment permits Upi, Card, Cash { }
record Upi(String vpa) implements Payment { }
record Card(String last4, String network) implements Payment { }
record Cash() implements Payment { }
```

Combined with pattern-matching switch, the compiler knows **all** the cases:

```java
public class SealedDemo {
    sealed interface Payment permits Upi, Card, Cash { }
    record Upi(String vpa) implements Payment { }
    record Card(String last4, String network) implements Payment { }
    record Cash() implements Payment { }

    static String receipt(Payment p) {
        return switch (p) {                                   // no default needed: all cases covered
            case Upi u -> "Paid via UPI " + u.vpa();
            case Card c -> "Paid by " + c.network() + " card ****" + c.last4();
            case Cash c -> "Paid in cash";
        };
    }

    public static void main(String[] args) {
        System.out.println(receipt(new Upi("asha@okicici")));
        System.out.println(receipt(new Card("4242", "Visa")));
        System.out.println(receipt(new Cash()));
    }
}
```

```text
Paid via UPI asha@okicici
Paid by Visa card ****4242
Paid in cash
```

If someone adds a `Wallet` type to `permits` later, every switch that doesn't handle it becomes a compile error, so nothing is forgotten.

Subclasses of a sealed type must be `final`, `sealed` or `non-sealed`. Records are implicitly final.

## Which to use?

| Need | Use |
|---|---|
| Plain immutable data | `record` |
| A fixed list of constants (maybe with data) | `enum` |
| A fixed set of different **shapes** of data | `sealed` interface + records |
| Rich objects with changing state and behaviour | a normal class |

## Common mistakes

- Trying to add mutable fields or setters to a record (fields are final).
- Using `ordinal()` for business logic or storage.
- Comparing enums with `.equals()` (works, but `==` is preferred and null-safe for enums).

## Exercise

1. Create `record Address(String city, String pin)` that rejects PIN codes that aren't 6 digits.
2. Create an enum `Planet` with mass and radius, and a method for surface gravity.
3. Create an enum `TrafficLight` with a `next()` method (RED → GREEN → YELLOW → RED).
4. Model `Shape` as a sealed interface with `Circle`, `Square` and `Triangle` records, and write `area(Shape s)` with a pattern-matching switch.

::hint Hint for 3 | `TrafficLight next() { return values()[(ordinal() + 1) % values().length]; }` with constants declared in order RED, GREEN, YELLOW.

## Interview corner

- **What is a record?** A concise immutable data carrier; Java generates the constructor, accessors, equals, hashCode and toString.
- **Can an enum have a constructor?** Yes, always private; called once per constant.
- **Why are enums good singletons?** The JVM guarantees one instance per constant, and they're safe against reflection and serialization tricks.
- **What problem do sealed classes solve?** They restrict which classes can extend a type, enabling exhaustive pattern matching.

## Summary

- Records: immutable data in one line, with validation in compact constructors.
- Enums: type-safe constants that can have fields, methods and exhaustive switches.
- Sealed types: a closed hierarchy the compiler can check.

Next: SOLID principles and design patterns, to write OOP code that scales.
