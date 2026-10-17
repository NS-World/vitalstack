---
title: What is Object-Oriented Programming?
slug: java-oop-introduction
subject: java-oop
lesson: 1
difficulty: beginner
excerpt: Understand OOP with real-life analogies, see how a class becomes many objects in an animation, and meet the four pillars: encapsulation, inheritance, polymorphism and abstraction.
---

So far you've written programs as a list of steps. That works for small programs. But a banking app, Swiggy or an online course site has thousands of moving parts. **Object-Oriented Programming (OOP)** organises code around **objects**: things that hold their own data and know how to do their own work.

## The real-life idea

Look around you. Everything is an object:

- A **phone** has data (brand, battery level, contacts) and behaviour (call, charge, take photo).
- A **bank account** has data (owner, balance) and behaviour (deposit, withdraw).
- A **student** has data (name, roll number, marks) and behaviour (attend class, take exam).

OOP lets you model software the same way. Instead of loose variables and functions, you build objects that bundle **data (fields)** and **behaviour (methods)** together.

## Class vs object

- A **class** is a **blueprint**: it describes what every object of that kind has and can do.
- An **object** is one real thing **built from** the blueprint, with its own values.

Think of a cookie cutter (class) and cookies (objects). One cutter, many cookies, each can have different toppings. Play the animation: one `Dog` class, three different dogs.

::viz class-object

```java
public class Main {
    public static void main(String[] args) {
        Dog rex = new Dog();       // create an object with new
        rex.name = "Rex";
        rex.age = 3;

        Dog bella = new Dog();     // another object, separate data
        bella.name = "Bella";
        bella.age = 5;

        rex.bark();                // Rex says Woof!
        bella.bark();              // Bella says Woof!
    }
}

class Dog {
    String name;            // field (data)
    int age;

    void bark() {           // method (behaviour)
        System.out.println(name + " says Woof!");
    }
}
```

`new Dog()` creates a new object in memory. Each object has its **own copy** of the fields: changing `rex.age` never touches `bella`.

## Why OOP?

| Problem in big programs | How OOP helps |
|---|---|
| Data changed from anywhere, causing bugs | **Encapsulation** hides data behind methods |
| Same code copied into many places | **Inheritance** and composition reuse code |
| Huge `if/else` chains for each type | **Polymorphism** lets each type handle itself |
| Too much detail everywhere | **Abstraction** shows only what matters |
| Hard to divide work in a team | Classes are natural units to split work |

That's why Java, C#, Kotlin, Python and TypeScript all support OOP, and why every Java interview asks about it.

## The four pillars (a preview)

1. **Encapsulation**: keep data private and change it only through methods with rules. *An ATM lets you withdraw, but never lets you edit the bank's balance directly.*
2. **Inheritance**: a new class reuses and extends an existing one. *A `SavingsAccount` is an `Account` with interest.*
3. **Polymorphism**: one call, many behaviours. *`animal.speak()` makes a dog bark and a cat meow.*
4. **Abstraction**: show what something does, hide how. *You press the brake pedal without knowing hydraulics.*

Each pillar gets its own lesson with an animation in this course.

## Thinking in objects

Before writing code, ask:

1. **What are the nouns?** (Student, Course, Enrollment, Teacher) → candidate classes.
2. **What does each know?** (name, email, fee) → fields.
3. **What does each do?** (enroll, pay, complete lesson) → methods.
4. **How are they related?** (a Course **has** Lessons; a Teacher **is a** User) → composition and inheritance.

::tip Practise by modelling things around you: a cricket match, a movie ticket booking, a library. Write the class names, fields and methods on paper before coding. Interviewers often ask exactly this ("design a parking lot").

## Common mistakes

- Thinking class = object. The class is the plan; objects are built from it with `new`.
- Making everything public and changing fields from anywhere (breaks encapsulation).
- Creating huge "god classes" that do everything. Keep each class focused on one job.

## Exercise

1. List 3 fields and 3 methods for each: `MobilePhone`, `Movie`, `ShoppingCart`.
2. Write a `Car` class with `brand`, `speed` and methods `accelerate()` and `brake()`. Create two cars and call their methods.
3. Identify the classes in this statement: "A customer places an order containing products and pays using UPI or card."

::hint Hint for 3 | Nouns: Customer, Order, Product, Payment (with UpiPayment and CardPayment as kinds of Payment).

## Interview corner

- **What is OOP?** A programming style that organises software into objects combining data and behaviour, built on encapsulation, inheritance, polymorphism and abstraction.
- **Class vs object?** A class is a blueprint/template; an object is an instance of it in memory with its own state.
- **Is Java 100% object-oriented?** No, because of primitive types (`int`, `double`…) and static members.

## Summary

- OOP models software as objects with fields (data) and methods (behaviour).
- A class is the blueprint; `new` creates objects, each with its own data.
- Four pillars: encapsulation, inheritance, polymorphism, abstraction.

Next: classes and objects in depth, including how objects live in memory.
