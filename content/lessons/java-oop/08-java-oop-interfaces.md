---
title: Java Interfaces
slug: java-oop-interfaces
subject: java-oop
lesson: 8
difficulty: intermediate
excerpt: Define contracts with interfaces, implement several at once, use default and static methods, Comparable and Comparator, functional interfaces and programming to an interface.
---

An **interface** is a **contract**: a list of methods a class promises to provide. Any class that `implements` the interface can be used wherever that interface is expected. Interfaces are everywhere in Java: `List`, `Comparable`, `Runnable`, and every Spring service.

## Defining and implementing

```java
public class InterfaceDemo {
    public static void main(String[] args) {
        Shape[] shapes = { new Circle(2), new Rectangle(3, 4) };
        double total = 0;
        for (Shape s : shapes) {
            System.out.println(s.label());
            total += s.area();
        }
        System.out.printf("Total area: %.2f%n", total);
    }
}

interface Shape {
    double area();                      // public and abstract automatically
    default String label() {            // a default method has a body
        return getClass().getSimpleName() + " (" + String.format("%.2f", area()) + ")";
    }
}

class Circle implements Shape {
    private final double r;
    Circle(double r) { this.r = r; }
    public double area() { return Math.PI * r * r; }
}

class Rectangle implements Shape {
    private final double w, h;
    Rectangle(double w, double h) { this.w = w; this.h = h; }
    public double area() { return w * h; }
}
```

```text
Circle (12.57)
Rectangle (12.00)
Total area: 24.57
```

Change the sizes in the animation and watch one `area()` call reach different classes:

::viz interface-shapes

- Interface methods are `public abstract` by default. The implementing method must be `public`.
- A class can implement **many** interfaces: `class Duck implements Flyer, Swimmer`.
- Interfaces can extend other interfaces.

## Multiple interfaces

```java
interface Flyer   { default String move() { return "flies"; } }
interface Swimmer { String swim(); }

class Duck implements Flyer, Swimmer {
    public String swim() { return "paddles"; }
}

Duck d = new Duck();
System.out.println(d.move() + " and " + d.swim());   // flies and paddles
```

If two interfaces have the same default method, the class **must** override it and choose (`Flyer.super.move()`). That's how Java avoids the diamond problem.

## Static methods and constants

```java
interface Gst {
    double RATE = 0.18;                       // public static final constant
    static double withGst(double price) {     // called as Gst.withGst(100)
        return price * (1 + RATE);
    }
}
```

## Comparable: natural ordering

Implement `Comparable<T>` so objects can be sorted:

```java
import java.util.*;

public class Ranking {
    public static void main(String[] args) {
        List<Student> list = new ArrayList<>(List.of(
            new Student("Asha", 88), new Student("Ravi", 95), new Student("Meera", 72)));
        Collections.sort(list);
        System.out.println(list);                                        // [Ravi=95, Asha=88, Meera=72]
        list.sort(Comparator.comparing(s -> s.name));                   // a different order on demand
        System.out.println(list);                                        // [Asha=88, Meera=72, Ravi=95]
    }
}

class Student implements Comparable<Student> {
    final String name;
    final int marks;
    Student(String name, int marks) { this.name = name; this.marks = marks; }

    @Override
    public int compareTo(Student other) {        // negative: this first; positive: other first
        return Integer.compare(other.marks, this.marks);   // higher marks first
    }

    @Override public String toString() { return name + "=" + marks; }
}
```

- `Comparable`: one **natural** order built into the class.
- `Comparator`: any number of **external** orders: `Comparator.comparing(...)`, `.reversed()`, `.thenComparing(...)`.

## Functional interfaces and lambdas

An interface with exactly **one abstract method** is a functional interface, and can be implemented with a lambda:

```java
@FunctionalInterface
interface DiscountRule {
    double apply(double price);
}

DiscountRule festive = price -> price * 0.8;
DiscountRule flat100 = price -> Math.max(0, price - 100);
System.out.println(festive.apply(1000) + " " + flat100.apply(1000));   // 800.0 900.0
```

`Runnable`, `Comparator`, `Predicate`, `Function` are all functional interfaces.

## Programming to an interface

Declare variables, parameters and return types as **interfaces**, not concrete classes:

```java
List<String> names = new ArrayList<>();            // ✅ can switch to LinkedList later
ArrayList<String> names2 = new ArrayList<>();      // ❌ locked in
```

In Spring Boot you'll write `interface CourseRepository` and Spring creates the implementation for you. Your services depend only on the interface, which makes them easy to test with fake implementations.

::tip Interfaces describe **capabilities** ("can be compared", "can be paid with", "can be saved"). Name them as adjectives or roles: `Comparable`, `Payable`, `Searchable`, `PaymentGateway`.

## Common mistakes

- Making the implementing method package-private (it must be `public`).
- Putting state in interfaces (only constants are allowed).
- Creating an interface for every single class "just because": add one when you have, or truly expect, multiple implementations, or for testing boundaries.
- `compareTo` using subtraction (`a - b`) with big numbers can overflow. Use `Integer.compare`.

## Exercise

1. Create `Playable` with `play()` and `stop()`; implement it in `Song` and `Video`.
2. Make a `Product` class `Comparable` by price, then sort with a `Comparator` by name and by rating.
3. Create `PaymentGateway` with `pay(double)`, implement `RazorpayGateway` and `PaytmGateway` (just print messages), and a `Checkout` class that accepts any gateway in its constructor.
4. Create a `Validator<T>` functional interface and write lambdas that validate an email and a phone number.

::hint Hint for 3 | `class Checkout { private final PaymentGateway gateway; Checkout(PaymentGateway g) { gateway = g; } }`. This is dependency injection, which Spring automates.

## Interview corner

- **Can an interface have method bodies?** Yes: `default` and `static` methods (Java 8+) and `private` methods (Java 9+).
- **Interface vs abstract class?** Interfaces: no instance state, multiple implementation. Abstract classes: state, constructors, single inheritance.
- **What is a marker interface?** An interface with no methods (e.g. `Serializable`) that tags a class.
- **Comparable vs Comparator?** Comparable: natural order inside the class (`compareTo`). Comparator: separate ordering objects (`compare`).

## Summary

- Interfaces are contracts; classes `implements` them, and can implement many.
- Default and static methods add behaviour without breaking implementers.
- `Comparable` gives natural ordering; `Comparator` gives custom orderings.
- Program to interfaces for flexible, testable code.

Next: the special methods every object has: equals, hashCode and toString.
