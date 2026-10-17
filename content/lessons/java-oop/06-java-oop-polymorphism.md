---
title: Java Polymorphism
slug: java-oop-polymorphism
subject: java-oop
lesson: 6
difficulty: intermediate
excerpt: One call, many behaviours. See dynamic method dispatch in an animation, compare compile-time overloading with runtime overriding, upcasting, downcasting, instanceof and pattern matching.
---

**Polymorphism** means "many forms". The same method call does different things depending on the actual object. It lets you write code that works with a general type (`Animal`) and still get specific behaviour (`Woof!`, `Meow!`). It's the most powerful idea in OOP and a favourite interview topic.

## One loop, many behaviours

Add or remove animals in the input and watch each object answer the **same** `speak()` call differently:

::viz polymorphism

```java
import java.util.List;

public class Zoo {
    public static void main(String[] args) {
        List<Animal> zoo = List.of(new Dog(), new Cat(), new Cow(), new Dog());
        for (Animal a : zoo) {
            System.out.println(a.getClass().getSimpleName() + ": " + a.speak());
        }
    }
}

abstract class Animal {
    abstract String speak();
}
class Dog extends Animal { String speak() { return "Woof!"; } }
class Cat extends Animal { String speak() { return "Meow!"; } }
class Cow extends Animal { String speak() { return "Moo!"; } }
```

```text
Dog: Woof!
Cat: Meow!
Cow: Moo!
Dog: Woof!
```

The loop doesn't know or care which animal it has. Add a `Duck` class tomorrow and **this loop doesn't change**. That's the real benefit: code that's open for new types without editing old code.

## Upcasting: a parent reference to a child object

```java
Animal a = new Dog();     // upcasting: always safe, automatic
a.speak();                // "Woof!": the Dog's version runs
```

- The **variable type** (`Animal`) decides which methods you're **allowed to call** (checked at compile time).
- The **object type** (`Dog`) decides which version **actually runs** (decided at runtime).

This runtime choice is called **dynamic method dispatch** (or late binding).

## Compile-time vs runtime polymorphism

| | Compile-time (static) | Runtime (dynamic) |
|---|---|---|
| Achieved by | method **overloading** | method **overriding** |
| Decided by | the compiler, from argument types | the JVM, from the object's real class |
| Example | `print(int)` vs `print(String)` | `Dog.speak()` vs `Cat.speak()` |

```java
class Printer {
    void print(int n)    { System.out.println("int: " + n); }
    void print(String s) { System.out.println("text: " + s); }
}
new Printer().print(5);       // int: 5      (chosen at compile time)
new Printer().print("hi");    // text: hi
```

## Downcasting and instanceof

Sometimes you need the specific type back, for example to call `fetch()` which only `Dog` has:

```java
Animal a = new Dog();
// a.fetch();               // compile error: Animal has no fetch()

if (a instanceof Dog) {
    Dog d = (Dog) a;        // downcasting: explicit
    d.fetch();
}
```

Casting to the wrong type throws `ClassCastException` at runtime, so check with `instanceof` first.

### Pattern matching (Java 16+)

Shorter and safer: test and cast in one step:

```java
if (a instanceof Dog d) {
    d.fetch();              // d is already a Dog here
}
```

And with switch (Java 21):

```java
String sound = switch (a) {
    case Dog d -> "dog says " + d.speak();
    case Cat c -> "cat says " + c.speak();
    default -> "unknown animal";
};
```

::tip If your code is full of `instanceof` checks, it's a sign you should move that behaviour **into the classes** as an overridden method. Let polymorphism do the work.

## A practical example: payments

```java
public class Checkout {
    static void pay(Payment p, double amount) {          // works for ANY payment type
        System.out.printf("%s: pay ₹%.2f (fee ₹%.2f)%n", p.name(), amount + p.fee(amount), p.fee(amount));
    }

    public static void main(String[] args) {
        for (Payment p : new Payment[] { new Upi(), new Card(), new NetBanking() }) {
            pay(p, 1000);
        }
    }
}

interface Payment {
    double fee(double amount);
    String name();
}

class Upi implements Payment {
    public double fee(double amount) { return 0; }
    public String name() { return "UPI"; }
}

class Card implements Payment {
    public double fee(double amount) { return amount * 0.02; }
    public String name() { return "Card"; }
}

class NetBanking implements Payment {
    public double fee(double amount) { return 10; }
    public String name() { return "Net banking"; }
}
```

```text
UPI: pay ₹1000.00 (fee ₹0.00)
Card: pay ₹1020.00 (fee ₹20.00)
Net banking: pay ₹1010.00 (fee ₹10.00)
```

Adding Wallet payments later means adding one class. `pay()` never changes. Spring, Hibernate and most frameworks are built on exactly this idea.

## What is NOT polymorphic

- **Fields**: `a.name` uses the variable's type, not the object's. (Another reason to keep fields private.)
- **Static methods**: chosen by the reference type (method hiding, not overriding).
- **Private and final methods**: can't be overridden.

## Common mistakes

- Expecting overloading to pick a method based on the object's runtime type (it uses the declared type).
- Downcasting without `instanceof` → `ClassCastException`.
- Long `if (x instanceof A) ... else if (x instanceof B)` chains instead of overriding.

## Exercise

1. Create `Shape` with `area()` and `perimeter()`, subclasses `Circle`, `Rectangle`, `Triangle`. Put them in a list and print the total area.
2. Create `Notification` with `send(String msg)`, implemented by `EmailNotification`, `SmsNotification` and `PushNotification`. Send one message through all of them.
3. Predict the output:

```java
class A { void show() { System.out.println("A"); } }
class B extends A { void show() { System.out.println("B"); } }
A obj = new B();
obj.show();
```

::hint Hint for 3 | The object is a B, and `show()` is overridden, so it prints "B" even though the variable type is A.

## Interview corner

- **What is polymorphism?** The ability of one interface (method call, reference type) to represent many forms (behaviours of different classes).
- **Types in Java?** Compile-time (overloading) and runtime (overriding / dynamic dispatch).
- **What is dynamic method dispatch?** The JVM picks the overridden method based on the actual object at runtime.
- **Can you achieve polymorphism with interfaces?** Yes, that's the most common way in modern Java.

## Summary

- Polymorphism: one call, behaviour decided by the real object.
- Overloading = compile-time; overriding = runtime (dynamic dispatch).
- Upcasting is automatic; downcast safely with `instanceof` pattern matching.
- Polymorphic code accepts new types without changes.

Next: abstraction with abstract classes.
