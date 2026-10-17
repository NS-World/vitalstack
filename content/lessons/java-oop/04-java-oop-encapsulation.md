---
title: Java Encapsulation
slug: java-oop-encapsulation
subject: java-oop
lesson: 4
difficulty: beginner
excerpt: Protect object data with private fields, getters, setters and validation, learn the four access modifiers, packages, and build immutable classes, with a live bank-account animation.
---

Imagine anyone in your program could write `account.balance = -50000;`. Bugs would be everywhere and impossible to trace. **Encapsulation** means hiding an object's data and only allowing changes through methods that enforce the rules.

## The ATM analogy

At an ATM you can deposit and withdraw, but you can't open the machine and edit the bank's database. The ATM checks your PIN, your balance, the daily limit. Your object should work the same way: **private data, public methods with checks**.

## Private fields, public methods

Try different operations in the animation, including withdrawing too much and depositing a negative amount:

::viz encapsulation

```java
public class BankAccount {
    private final String owner;     // private: only this class can touch it
    private double balance;

    public BankAccount(String owner, double opening) {
        if (opening < 0) throw new IllegalArgumentException("Opening balance can't be negative");
        this.owner = owner;
        this.balance = opening;
    }

    public void deposit(double amount) {
        if (amount <= 0) throw new IllegalArgumentException("Amount must be positive");
        balance += amount;
    }

    public void withdraw(double amount) {
        if (amount <= 0) throw new IllegalArgumentException("Amount must be positive");
        if (amount > balance) throw new IllegalStateException("Insufficient funds");
        balance -= amount;
    }

    public double getBalance() { return balance; }      // read-only access
    public String getOwner() { return owner; }

    public static void main(String[] args) {
        BankAccount acc = new BankAccount("Asha", 1000);
        acc.deposit(500);
        acc.withdraw(200);
        System.out.println(acc.getOwner() + ": " + acc.getBalance());   // Asha: 1300.0
        try {
            acc.withdraw(5000);
        } catch (IllegalStateException e) {
            System.out.println("Blocked: " + e.getMessage());           // Blocked: Insufficient funds
        }
    }
}
```

From outside the class, `acc.balance = -50000;` is now a **compile error**. The only ways to change the balance are `deposit` and `withdraw`, and both validate.

## Getters and setters

- A **getter** (`getBalance()`, or `isActive()` for booleans) returns a field's value.
- A **setter** (`setEmail(...)`) changes it, ideally with validation.

```java
public class User {
    private String email;

    public String getEmail() { return email; }

    public void setEmail(String email) {
        if (email == null || !email.contains("@")) {
            throw new IllegalArgumentException("Invalid email");
        }
        this.email = email.trim().toLowerCase();
    }
}
```

::tip Don't auto-generate a setter for every field. Ask "should outsiders change this?" A bank balance should have `deposit`/`withdraw`, not `setBalance`. An id should have no setter at all.

## The four access modifiers

| Modifier | Same class | Same package | Subclass (other package) | Everywhere |
|---|---|---|---|---|
| `private` | ✅ | ❌ | ❌ | ❌ |
| *(none)* "package-private" | ✅ | ✅ | ❌ | ❌ |
| `protected` | ✅ | ✅ | ✅ | ❌ |
| `public` | ✅ | ✅ | ✅ | ✅ |

Rule of thumb: **fields private, methods public only if needed**, everything else as restricted as possible.

## Packages

Packages are folders that group related classes and control visibility:

```java
package com.vitalstack.bank;          // first line of the file

import java.util.List;                 // use classes from other packages

public class BankAccount { ... }
```

The file lives in `com/vitalstack/bank/BankAccount.java`. Package names are lowercase, usually your domain reversed.

## Immutable classes

The safest objects are those that **can't change** after creation. Like `String`, they are automatically thread-safe and can be shared freely.

```java
public final class Money {                 // final: no subclass can break the rules
    private final long paise;              // final fields: set once
    private final String currency;

    public Money(long paise, String currency) {
        this.paise = paise;
        this.currency = currency;
    }

    public Money plus(Money other) {       // returns a NEW object instead of changing this one
        return new Money(this.paise + other.paise, currency);
    }

    public long getPaise() { return paise; }
}
```

Recipe for immutability: `final` class, `private final` fields, no setters, return new objects for changes, and make defensive copies of mutable things like lists (`List.copyOf(list)`).

## Defensive copies

```java
public class Course {
    private final List<String> lessons;

    public Course(List<String> lessons) {
        this.lessons = List.copyOf(lessons);      // caller's later changes don't affect us
    }

    public List<String> getLessons() {
        return lessons;                           // List.copyOf is already unmodifiable
    }
}
```

Without the copy, a caller could keep a reference to the list and change your object's insides.

## Common mistakes

- Public fields "for convenience".
- Setters with no validation, making encapsulation pointless.
- Returning internal mutable lists directly.
- Getters with side effects (a getter should not change state).

## Exercise

1. Create a `Student` class with private `name` and `marks`; the setter must reject marks outside 0-100.
2. Create `Thermostat` with a private temperature that can only be changed by `increase()`/`decrease()` within 16-30 °C.
3. Make an immutable `Point` class with `x`, `y` and a `move(dx, dy)` method returning a new Point.
4. Create a `Playlist` class that holds songs in a private list, with `addSong`, `removeSong` and a `getSongs` that returns an unmodifiable view.

::hint Hint for 4 | Return `Collections.unmodifiableList(songs)` or `List.copyOf(songs)` from `getSongs()`.

## Interview corner

- **What is encapsulation?** Bundling data with the methods that operate on it and restricting direct access (private fields + controlled public methods).
- **Encapsulation vs abstraction?** Encapsulation hides **data** (how state is protected); abstraction hides **complexity** (what an object does vs how).
- **How to create an immutable class?** final class, private final fields, no setters, defensive copies, return new objects for changes.
- **protected vs default?** protected also allows access from subclasses in other packages.

## Summary

- Keep fields private; expose behaviour through validated public methods.
- Access modifiers: private < package-private < protected < public.
- Immutable objects (final fields, no setters) are simple and thread-safe.
- Make defensive copies of mutable data going in and out.

Next: inheritance, building new classes on top of existing ones.
