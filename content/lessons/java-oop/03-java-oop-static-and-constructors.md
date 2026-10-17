---
title: Java Static Members and Constructor Overloading
slug: java-oop-static-and-constructors
subject: java-oop
lesson: 3
difficulty: beginner
excerpt: Learn the difference between static and instance members, shared counters, static utility methods, static blocks, constructor overloading and initialisation order.
---

Some data belongs to **each object** (every student has their own name). Some belongs to the **class as a whole** (the total number of students, the school's name). The `static` keyword marks class-level members.

## Instance vs static fields

```java
public class Main {
    public static void main(String[] args) {
        Student a = new Student("Asha");
        Student b = new Student("Ravi");
        Student c = new Student("Meera");
        System.out.println(Student.count);               // 3
        System.out.println(c.id + " " + c.name);          // 3 Meera
        System.out.println(Student.school);               // VitalStack Academy
    }
}

class Student {
    static String school = "VitalStack Academy";   // one copy, shared by all
    static int count = 0;                           // shared counter

    String name;                                    // one copy per object
    int id;

    Student(String name) {
        this.name = name;
        count++;                                    // update the shared counter
        this.id = count;                            // give each student a unique id
    }
}
```

```text
 Class area (one copy)           Heap (one per object)
 ┌──────────────────────┐        ┌───────────────┐ ┌───────────────┐
 │ school = "Vital…"    │        │ name = "Asha" │ │ name = "Ravi" │ ...
 │ count  = 3           │        │ id   = 1      │ │ id   = 2      │
 └──────────────────────┘        └───────────────┘ └───────────────┘
```

Access static members through the **class name**: `Student.count`. (Java also allows `a.count`, but that's misleading, so avoid it.)

## Static methods

A static method belongs to the class and doesn't need an object. It **cannot** use instance fields or `this`, because there's no particular object.

```java
class MathUtils {
    static int square(int x) { return x * x; }
    static boolean isPrime(int n) {
        if (n < 2) return false;
        for (int i = 2; (long) i * i <= n; i++) if (n % i == 0) return false;
        return true;
    }
}

int s = MathUtils.square(12);         // 144
boolean p = MathUtils.isPrime(97);    // true
```

That's why `main` is static: the JVM calls it before any object exists. And it's why Java's own utility classes are static: `Math.max()`, `Arrays.sort()`, `Integer.parseInt()`.

| | Instance member | Static member |
|---|---|---|
| Belongs to | each object | the class |
| Accessed with | `object.member` | `ClassName.member` |
| Can use `this`? | yes | no |
| Memory | one per object | one in total |

## Constants: static final

```java
class Config {
    static final double GST_RATE = 0.18;
    static final int MAX_CART_ITEMS = 50;
}
```

`static final` means one shared copy that never changes: perfect for constants.

## Constructor overloading

Give users several ways to create an object:

```java
class Pizza {
    String size;
    String crust;
    int toppings;

    Pizza() {                                   // all defaults
        this("Medium", "Thin", 0);
    }

    Pizza(String size) {                        // choose size only
        this(size, "Thin", 0);
    }

    Pizza(String size, String crust, int toppings) {   // full control
        this.size = size;
        this.crust = crust;
        this.toppings = toppings;
    }

    double price() {
        double base = switch (size) {
            case "Small" -> 199;
            case "Large" -> 449;
            default -> 299;
        };
        return base + toppings * 49;
    }
}

new Pizza().price();                       // 299.0
new Pizza("Large").price();                // 449.0
new Pizza("Small", "Cheese burst", 3).price();   // 346.0
```

Chaining with `this(...)` keeps the real setup code in one place. `this(...)` must be the **first line** of a constructor.

## Static blocks and initialisation order

A `static { }` block runs **once**, when the class is first loaded. Instance initialiser blocks `{ }` run before every constructor:

```java
public class Main {
    public static void main(String[] args) {
        new Demo();
        new Demo();
    }
}

class Demo {
    static { System.out.println("1. static block (class loaded)"); }
    { System.out.println("2. instance block"); }
    Demo() { System.out.println("3. constructor"); }
}
```

```text
1. static block (class loaded)
2. instance block
3. constructor
2. instance block
3. constructor
```

## Common mistakes

- Using an instance field inside a static method: "non-static variable cannot be referenced from a static context". Create an object, or make the method non-static.
- Making fields static "to make the error go away": then every object shares one value, a serious bug.
- Overusing static: it makes code hard to test. Prefer objects for anything with state.

## Exercise

1. Add a static counter to a `Car` class that counts how many cars were created.
2. Create a `Temperature` utility class with static `cToF` and `fToC` methods.
3. Create an `Order` class whose constructor assigns ids like `ORD-1001`, `ORD-1002`… using a static counter.
4. Give a `Ticket` class three constructors: (movie), (movie, seat), (movie, seat, price). Chain them.

::hint Hint for 3 | `static int next = 1001;` then in the constructor `this.id = "ORD-" + next++;`

## Interview corner

- **Why is main static?** So the JVM can call it without creating an object.
- **Can a static method access instance variables?** Not directly; it has no `this`. It needs an object reference.
- **Can we override a static method?** No. A subclass can declare one with the same signature (hiding), but there's no runtime polymorphism.
- **When does a static block run?** Once, when the class is loaded by the JVM.

## Summary

- Static members belong to the class (one copy); instance members belong to each object.
- Static methods can't use `this`; use them for utilities.
- `static final` makes constants.
- Overload constructors and chain them with `this(...)` as the first statement.

Next: encapsulation, protecting your object's data.
