---
title: Java Variables and Data Types
slug: java-variables-and-data-types
subject: java
lesson: 3
difficulty: beginner
excerpt: Declare variables, learn Java's eight primitive types, String, var, constants with final, type casting, integer overflow and naming rules, with clear examples.
---

A **variable** is a labelled box that holds a value. In Java every box has a fixed **type**: a box for whole numbers can't hold text. This strictness catches mistakes before your program even runs.

## Declaring variables

```java
public class Variables {
    public static void main(String[] args) {
        int age = 21;                 // type name = value;
        double price = 499.99;
        String city = "Pune";
        boolean isStudent = true;
        char grade = 'A';

        age = 22;                     // change the value (same type)
        System.out.println(city + " " + age + " " + price + " " + isStudent + " " + grade);
    }
}
```

Output:

```text
Pune 22 499.99 true A
```

The pattern is always `type name = value;`. You can declare first and assign later: `int score; score = 90;` but a local variable must have a value before you read it.

## The 8 primitive types

| Type | Size | Holds | Example |
|---|---|---|---|
| `byte` | 1 byte | -128 to 127 | `byte b = 100;` |
| `short` | 2 bytes | -32,768 to 32,767 | `short s = 3000;` |
| `int` | 4 bytes | about ±2.1 billion | `int n = 150000;` |
| `long` | 8 bytes | very large whole numbers | `long views = 8_000_000_000L;` |
| `float` | 4 bytes | decimals (~7 digits) | `float f = 3.14f;` |
| `double` | 8 bytes | decimals (~15 digits) | `double d = 3.14159;` |
| `char` | 2 bytes | one character | `char c = 'J';` |
| `boolean` | – | `true` or `false` | `boolean ok = false;` |

In practice you'll mostly use **`int`, `long`, `double`, `boolean`, `char`**.

- `long` literals end in `L`, `float` literals end in `f`.
- Underscores make big numbers readable: `1_000_000`.
- `char` uses single quotes `'A'`; `String` uses double quotes `"A"`.

## String is not primitive

`String` is a **class** (that's why it starts with a capital S). It holds text and has many useful methods:

```java
String name = "Vital Stack";
System.out.println(name.length());        // 11
System.out.println(name.toUpperCase());   // VITAL STACK
```

You'll learn Strings in depth in the Strings lesson.

## Primitive vs reference: what's in the box?

A primitive variable holds the **value itself**. A reference variable (like a `String` or any object) holds an **address** pointing to an object in memory (the heap). Play the animation to see it:

::viz java-memory

This difference explains many Java behaviours later, like why `==` on Strings can surprise you.

## var: let Java infer the type

Since Java 10, inside methods you can write `var` and Java works out the type from the value:

```java
var count = 10;          // int
var total = 99.5;        // double
var msg = "Hello";       // String
```

The type is still fixed: `count = "ten";` is an error. Use `var` when the type is obvious from the right side.

## Constants with final

```java
final double GST_RATE = 0.18;
final int MAX_LOGIN_ATTEMPTS = 3;
// GST_RATE = 0.12;   // error: cannot assign a value to final variable
```

Constants are written in `UPPER_SNAKE_CASE`.

## Type casting

**Widening** (small → big) happens automatically. **Narrowing** (big → small) needs an explicit cast and may lose data:

```java
public class Casting {
    public static void main(String[] args) {
        int marks = 87;
        double asDouble = marks;            // widening: 87.0
        double avg = 78.9;
        int rounded = (int) avg;            // narrowing: 78 (decimal dropped, not rounded!)
        long big = 3_000_000_000L;
        int broken = (int) big;             // overflow: -1294967296
        System.out.println(asDouble + " " + rounded + " " + broken);
        System.out.println(Math.round(avg));   // 79: proper rounding
    }
}
```

Output:

```text
87.0 78 -1294967296
79
```

## Integer division and overflow

```java
System.out.println(7 / 2);       // 3   (int / int = int)
System.out.println(7 / 2.0);     // 3.5 (one double makes it double)
int max = Integer.MAX_VALUE;     // 2147483647
System.out.println(max + 1);     // -2147483648: overflow wraps around!
```

::tip When counting money, views or IDs that may exceed 2 billion, use `long`. For exact money calculations (no rounding errors), professionals use `BigDecimal`.

## Naming rules and conventions

- Names can contain letters, digits, `_` and `$`, but can't start with a digit.
- Can't be a keyword (`class`, `int`, `public`…).
- Convention: **camelCase** for variables (`totalPrice`, `isLoggedIn`).
- Choose meaningful names: `studentCount`, not `sc`.

## Default values

Fields in a class get defaults (`0`, `0.0`, `false`, `null`). **Local variables** inside methods do not: reading one before assigning is a compile error.

## Common mistakes

- `int price = 99.5;` → error: possible lossy conversion. Use `double`.
- `long l = 3000000000;` → error: integer number too large. Add `L`.
- Expecting `(int) 9.99` to be 10. It's 9.
- `char c = "A";` → double quotes make a String. Use `'A'`.

## Exercise

1. Create variables for a product: name, price, quantity, inStock. Print a sentence using all four.
2. Calculate the total cost (price × quantity) and the total with 18% GST.
3. Store your height in metres as `double` and print it in centimetres as an `int`.
4. What does `System.out.println(10 / 4 + 10 % 4);` print? Guess first, then run it.

::hint Hint for 4 | `10 / 4` is integer division = 2, and `10 % 4` is the remainder = 2. So the answer is 4.

## Interview corner

- **How many primitive types does Java have?** Eight: byte, short, int, long, float, double, char, boolean.
- **Is String a primitive?** No, it's a class in `java.lang`; variables hold references to String objects.
- **What happens on int overflow?** It silently wraps around to the negative side. Use `Math.addExact` to get an exception instead.

## Summary

- Variables have a fixed type: `type name = value;`.
- Eight primitives; `int`, `long`, `double`, `boolean`, `char` are the everyday ones.
- `var` infers types, `final` makes constants, casting converts between types.
- Watch for integer division and overflow.

Next: operators and expressions to calculate and compare.
