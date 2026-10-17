---
title: Java Classes and Objects
slug: java-oop-classes-and-objects
subject: java-oop
lesson: 2
difficulty: beginner
excerpt: Write classes with fields, methods and constructors, use the this keyword, see objects and references on the heap in an animation, and learn what null means.
---

In this lesson you'll write proper classes with constructors, understand `this`, and see exactly what happens in memory when you create objects.

## A class with a constructor

Setting every field by hand (`rex.name = "Rex"`) is repetitive and error-prone. A **constructor** sets up the object as it's created:

```java
public class Main {
    public static void main(String[] args) {
        Student a = new Student("Asha", 1, 88.5);
        Student b = new Student("Ravi", 2, 35);
        System.out.println(a.describe());   // 1: Asha (88.5) PASS
        System.out.println(b.describe());   // 2: Ravi (35.0) FAIL
    }
}

class Student {
    String name;
    int rollNo;
    double marks;

    Student(String name, int rollNo, double marks) {   // constructor: same name as class, no return type
        this.name = name;
        this.rollNo = rollNo;
        this.marks = marks;
    }

    boolean hasPassed() {
        return marks >= 40;
    }

    String describe() {
        return rollNo + ": " + name + " (" + marks + ")" + (hasPassed() ? " PASS" : " FAIL");
    }
}
```

- A constructor has the **same name as the class** and **no return type** (not even `void`).
- It runs automatically when you call `new Student(...)`.
- If you write no constructor, Java gives you a free **default constructor** with no parameters. Once you write any constructor, the free one disappears.

## The this keyword

Inside a class, `this` means "the current object". In the constructor, `this.name = name;` means "set **this object's** name field to the `name` parameter". Without `this`, the parameter would hide the field and `name = name;` would do nothing useful.

`this` is also used to call another constructor (constructor chaining):

```java
class Product {
    String title;
    double price;
    int stock;

    Product(String title, double price) {
        this(title, price, 0);       // call the 3-argument constructor
    }

    Product(String title, double price, int stock) {
        this.title = title;
        this.price = price;
        this.stock = stock;
    }
}
```

## Objects live on the heap

Local variables live on the **stack** (one frame per method call). Objects created with `new` live on the **heap**. A variable like `Student a` doesn't hold the object; it holds a **reference** (like an address) to it. Step through:

::viz java-memory

This explains a lot:

```java
Student x = new Student("Meera", 3, 91);
Student y = x;            // copies the reference, NOT the object
y.marks = 50;
System.out.println(x.marks);   // 50.0: x and y point to the same object
System.out.println(x == y);    // true: same object
```

## null: a reference to nothing

```java
Student s = null;          // points to no object
System.out.println(s == null);   // true
s.describe();              // NullPointerException!
```

Calling a method on `null` is the famous **NullPointerException**. Make sure a reference points to an object before using it.

## Garbage collection

When no variable refers to an object anymore, it becomes **garbage**. Java's **garbage collector** automatically frees that memory. You never call `delete` like in C++.

```java
Student temp = new Student("Temp", 9, 0);
temp = null;   // the old object is now unreachable → eligible for garbage collection
```

## Instance methods use the object's data

Methods without `static` are **instance methods**: they work on a particular object's fields. That's why you call them on an object: `a.describe()`. Inside, `marks` means `this.marks`.

```java
class Counter {
    int count;
    void increment() { count++; }     // changes THIS object's count
}

Counter c1 = new Counter(), c2 = new Counter();
c1.increment(); c1.increment(); c2.increment();
System.out.println(c1.count + " " + c2.count);   // 2 1
```

## toString: printing objects nicely

Printing an object shows something like `Student@1b6d3586` unless you override `toString()`:

```java
@Override
public String toString() {
    return "Student{name=" + name + ", marks=" + marks + "}";
}
```

Now `System.out.println(a)` prints `Student{name=Asha, marks=88.5}`. You'll learn more about `@Override` in the inheritance lesson.

## One file, many classes?

A `.java` file can contain only **one public class**, named like the file. Other non-public classes may be in the same file (handy for practice, like above), but in real projects each class gets its own file inside **packages** (folders like `com.vitalstack.school`).

## Common mistakes

- Writing `void Student(...)`: with a return type it's a method, not a constructor!
- Forgetting `this.` when the parameter has the same name as the field.
- Using an object before `new` (NullPointerException).
- Assuming `y = x` copies the object.

## Exercise

1. Create a `Book` class with title, author, price and a constructor. Add `applyDiscount(int percent)`.
2. Create a `Rectangle` class with `area()` and `perimeter()` methods. Create three rectangles and print the biggest area.
3. Create a `BankAccount` with owner and balance, and methods `deposit` and `withdraw` (refuse if the balance is too low).
4. Predict the output, then run it:

```java
Counter a = new Counter();
Counter b = a;
a.increment();
b.increment();
System.out.println(a.count);
```

::hint Hint for 4 | `a` and `b` refer to the same Counter object, so both increments change one count. It prints 2.

## Interview corner

- **What is a constructor?** A special method that initialises a new object; same name as the class, no return type, called by `new`.
- **Can a constructor be private?** Yes: used in singletons and factory methods so others can't create objects freely.
- **Where are objects stored?** On the heap; references to them can be on the stack (local variables) or in other objects' fields.
- **What is garbage collection?** The JVM automatically reclaiming memory of objects that are no longer reachable.

## Summary

- Classes have fields, methods and constructors; `new` calls the constructor.
- `this` refers to the current object and can chain constructors.
- Objects live on the heap; variables hold references; `null` means no object.
- Unreachable objects are cleaned up by the garbage collector.

Next: more constructor tricks and the `static` keyword.
