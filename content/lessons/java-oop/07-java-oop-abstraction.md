---
title: Java Abstraction and Abstract Classes
slug: java-oop-abstraction
subject: java-oop
lesson: 7
difficulty: intermediate
excerpt: Hide complexity with abstraction, write abstract classes and abstract methods, use the template method pattern, and learn when to choose an abstract class over an interface.
---

When you drive a car, you use the steering wheel, accelerator and brake. You don't think about fuel injection or ABS sensors. **Abstraction** means showing **what** something does and hiding **how** it does it. In Java, abstraction is built with **abstract classes** and **interfaces**.

## Abstract classes

An `abstract` class is a partial blueprint: it can't be instantiated on its own, and it can declare **abstract methods** (no body) that subclasses **must** implement.

```java
public class Shapes {
    public static void main(String[] args) {
        // Shape s = new Shape("x");     // error: Shape is abstract
        Shape[] shapes = { new Circle(2), new Rectangle(3, 4) };
        for (Shape s : shapes) System.out.println(s.describe());
    }
}

abstract class Shape {
    private final String name;

    Shape(String name) { this.name = name; }

    abstract double area();              // no body: every shape MUST provide one

    String describe() {                  // normal method shared by all shapes
        return String.format("%s with area %.2f", name, area());
    }
}

class Circle extends Shape {
    private final double r;
    Circle(double r) { super("Circle"); this.r = r; }
    @Override double area() { return Math.PI * r * r; }
}

class Rectangle extends Shape {
    private final double w, h;
    Rectangle(double w, double h) { super("Rectangle"); this.w = w; this.h = h; }
    @Override double area() { return w * h; }
}
```

```text
Circle with area 12.57
Rectangle with area 12.00
```

- An abstract class **can** have fields, constructors and normal methods.
- A concrete subclass must implement **all** abstract methods (or be abstract itself).
- You can't write `new Shape(...)`, but you can have `Shape` variables pointing to subclasses.

## Template method pattern

A powerful use of abstract classes: the parent defines the **steps** of an algorithm, and subclasses fill in the details.

```java
public class Reports {
    public static void main(String[] args) {
        System.out.println(new SalesReport().generate());
        System.out.println(new AttendanceReport().generate());
    }
}

abstract class ReportGenerator {
    // the template: fixed order of steps (final so subclasses can't break it)
    public final String generate() {
        return header() + "\n" + body() + "\n" + footer();
    }

    protected String header() { return "=== VitalStack Report ==="; }
    protected abstract String body();                 // each report decides this
    protected String footer() { return "Generated automatically"; }
}

class SalesReport extends ReportGenerator {
    protected String body() { return "Sales this week: ₹1,24,500"; }
}

class AttendanceReport extends ReportGenerator {
    protected String body() { return "Attendance: 92%"; }
    @Override protected String footer() { return "Sent to HR"; }
}
```

```text
=== VitalStack Report ===
Sales this week: ₹1,24,500
Generated automatically
=== VitalStack Report ===
Attendance: 92%
Sent to HR
```

Spring uses this pattern a lot (`JdbcTemplate`, `AbstractController`…).

## Abstraction in everyday Java

You already use abstraction constantly:

- `List<String> list = new ArrayList<>();`: you program against `List` without knowing how `ArrayList` manages its array.
- `Files.readString(path)` hides OS file system details.
- `System.out.println` hides how text reaches your screen.

## Abstract class vs interface

| | Abstract class | Interface |
|---|---|---|
| Methods | abstract and concrete | abstract, plus `default` and `static` methods |
| Fields | any instance fields | only `public static final` constants |
| Constructors | yes | no |
| Inheritance | a class extends **one** | a class implements **many** |
| Use for | closely related classes sharing state/code | a capability many unrelated classes can have |
| Example | `Shape`, `Employee`, `ReportGenerator` | `Comparable`, `Runnable`, `Payment` |

Rule of thumb: start with an **interface**. Use an abstract class when subclasses need to share fields or common code. Often you'll use both: an interface for the contract and an abstract class with shared code that implements it.

## Common mistakes

- Trying to instantiate an abstract class.
- Forgetting to implement an abstract method (compile error: "is not abstract and does not override abstract method").
- Abstract classes with no abstract methods and no shared code: probably not needed.
- Over-abstracting too early. Abstract when you see real duplication.

## Exercise

1. Create an abstract `Employee` with abstract `calculatePay()`, and `FullTimeEmployee` (monthly salary) and `Contractor` (hours × rate) subclasses. Print total payroll.
2. Create an abstract `Game` with a template `play()` method: `start()`, `takeTurns()`, `end()`. Implement `Cricket` and `Chess` versions.
3. Can an abstract class have a constructor? Write a small example to check.

::hint Hint for 3 | Yes. It runs when a subclass object is created, through `super(...)`. Use it to initialise the shared fields.

## Interview corner

- **What is abstraction?** Hiding implementation details and exposing only essential behaviour.
- **Can an abstract class have a constructor?** Yes; it's called via `super()` from subclasses.
- **Can an abstract class have no abstract methods?** Yes; it just can't be instantiated.
- **Can a method be both abstract and final?** No: abstract must be overridden, final can't be.
- **Abstract class vs interface?** See the table: state and constructors vs multiple implementation and capabilities.

## Summary

- Abstraction shows what, hides how.
- Abstract classes can't be instantiated and may force subclasses to implement abstract methods.
- The template method pattern fixes the steps and lets subclasses fill in details.
- Prefer interfaces for capabilities; abstract classes for shared state and code.

Next: interfaces in depth.
