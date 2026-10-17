---
title: Java Inheritance
slug: java-oop-inheritance
subject: java-oop
lesson: 5
difficulty: intermediate
excerpt: Reuse code with extends, override methods with @Override, call parent code with super, see method lookup climb the class hierarchy in an animation, and learn when to prefer composition.
---

Many classes share common parts. A `Dog`, a `Cat` and a `Cow` all eat and sleep. Instead of writing `eat()` three times, put it in an `Animal` class once and let the others **inherit** it. That's **inheritance**: a new class (child/subclass) builds on an existing one (parent/superclass).

## extends

```java
public class Main {
    public static void main(String[] args) {
        Puppy p = new Puppy();
        p.speak();   // Woof!           (from Dog)
        p.sleep();   // naps all day    (Puppy's own)
        p.eat();     // eats food       (from Animal)
        p.fetch();   // fetches the ball (from Dog)
    }
}

class Animal {
    void eat()   { System.out.println("eats food"); }
    void sleep() { System.out.println("sleeps"); }
    void speak() { System.out.println("makes a sound"); }
}

class Dog extends Animal {
    @Override
    void speak() { System.out.println("Woof!"); }     // replace the parent's version
    void fetch() { System.out.println("fetches the ball"); }   // add something new
}

class Puppy extends Dog {
    @Override
    void sleep() { System.out.println("naps all day"); }
}
```

## How Java finds the method

When you call `p.speak()`, Java starts at the object's **actual class** and walks **up** the hierarchy until it finds the method. Pick an object and a method in the animation and watch the search climb:

::viz inheritance {"type": "Puppy", "method": "speak"}

Try `Cat` + `fetch`: the search reaches the top without finding it. In real Java, that's a **compile error**, because the compiler checks that the method exists for the variable's type.

## Overriding and @Override

A subclass **overrides** a method by defining one with the **same name and parameters**. Always add `@Override`: the compiler then checks that you really are overriding something. If you mistype `speek()`, you get an error instead of a silent bug.

Rules for overriding:

- Same name and parameter list.
- Return type the same (or a subtype).
- Access can't be more restrictive (a `public` method can't become `private`).
- `private`, `static` and `final` methods can't be overridden.

## super: talk to the parent

`super` calls the parent's version of a method or constructor:

```java
public class Payroll {
    public static void main(String[] args) {
        Employee e = new Employee("Ravi", 50000);
        Manager m = new Manager("Asha", 80000, 200000);
        System.out.println(e.describe());   // Ravi earns ₹600000.0
        System.out.println(m.describe());   // Asha earns ₹1160000.0 (manager)
    }
}

class Employee {
    protected String name;
    protected double salary;

    Employee(String name, double salary) {
        this.name = name;
        this.salary = salary;
    }

    double annualPay() { return salary * 12; }

    String describe() { return name + " earns ₹" + annualPay(); }
}

class Manager extends Employee {
    private final double bonus;

    Manager(String name, double salary, double bonus) {
        super(name, salary);            // must be the first line: build the Employee part
        this.bonus = bonus;
    }

    @Override
    double annualPay() {
        return super.annualPay() + bonus;    // reuse the parent's calculation
    }

    @Override
    String describe() { return super.describe() + " (manager)"; }
}
```

- If the parent has no no-argument constructor, the child **must** call `super(...)` explicitly.
- Notice that `describe()` in `Employee` calls `annualPay()`, and for a Manager the **overridden** version runs. That's polymorphism, the next lesson.

## The Object class

Every class in Java secretly extends `java.lang.Object`. That's where `toString()`, `equals()`, `hashCode()` and `getClass()` come from. You'll override them in a later lesson.

## Types of inheritance in Java

```text
Single:        A ← B
Multilevel:    A ← B ← C          (Animal ← Dog ← Puppy)
Hierarchical:  A ← B, A ← C       (Animal ← Dog, Animal ← Cat)
Multiple:      A, B ← C           ✗ not allowed with classes
```

Java does **not** allow a class to extend two classes (the "diamond problem": if both parents have `speak()`, which one wins?). A class can, however, implement **many interfaces**.

## final: stop inheritance

```java
final class Rupee { }            // no class can extend Rupee
class Shape {
    final double PI = 3.14159;
    final void draw() { }        // subclasses can't override draw()
}
```

## Composition: "has a" vs "is a"

Use inheritance only for true **is-a** relationships: a Manager **is an** Employee. For **has-a**, put one object inside another:

```java
class Engine {
    void start() { System.out.println("Engine started"); }
}

class Car {
    private final Engine engine = new Engine();   // Car HAS an Engine
    void drive() {
        engine.start();
        System.out.println("Car is moving");
    }
}
```

`class Car extends Engine` would be wrong: a car isn't an engine. **Favour composition over inheritance**: it's more flexible, and deep inheritance trees become hard to change.

::tip A quick test: say it out loud. "A Puppy is a Dog" ✅ inheritance. "A Car is an Engine" ❌ composition.

## Common mistakes

- Forgetting `super(...)` when the parent has only constructors with parameters.
- Accidentally overloading instead of overriding (different parameters) without `@Override`.
- Using inheritance just to reuse a couple of methods (use composition or a helper class).
- Deep hierarchies (more than 2-3 levels) that are hard to follow.

## Exercise

1. Create `Vehicle` (brand, `move()`), then `Car` and `Bike` that override `move()`.
2. Create `Account` with `withdraw()`, and `SavingsAccount` that overrides it to keep a minimum balance of ₹1000.
3. Create `Shape` with `area()` returning 0, then `Circle` and `Square` that override it.
4. Model a `Library` that **has** a list of `Book`s (composition), with `addBook` and `findByAuthor`.

::hint Hint for 2 | In `SavingsAccount.withdraw`, check `balance - amount >= 1000` and then call `super.withdraw(amount)`.

## Interview corner

- **Why doesn't Java support multiple inheritance of classes?** To avoid the diamond problem and complexity. Interfaces provide multiple inheritance of type.
- **Can a constructor be inherited?** No. But a child constructor calls a parent constructor with `super(...)` (implicitly `super()` if you don't).
- **What is method overriding?** A subclass providing its own implementation of a parent method with the same signature, chosen at runtime.
- **Composition vs inheritance?** Inheritance = is-a, tight coupling; composition = has-a, flexible. Prefer composition.

## Summary

- `extends` lets a class inherit fields and methods; Java searches up the hierarchy to find a method.
- Override with `@Override`; call the parent with `super`.
- Java has single class inheritance; every class extends `Object`.
- Prefer composition for has-a relationships.

Next: polymorphism, where inheritance really pays off.
