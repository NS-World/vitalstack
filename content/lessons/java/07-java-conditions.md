---
title: Java If, Else and Switch
slug: java-conditions
subject: java
lesson: 7
difficulty: beginner
excerpt: Make decisions in Java with if, else if, else, nested conditions, the classic switch statement and the modern switch expression with arrows and yield.
---

Programs need to make decisions: is the password correct? Is the cart empty? Did the student pass? Conditions let your code take different paths.

## if and else

```java
public class Grades {
    public static void main(String[] args) {
        int marks = 76;

        if (marks >= 90) {
            System.out.println("Grade A+");
        } else if (marks >= 75) {
            System.out.println("Grade A");
        } else if (marks >= 60) {
            System.out.println("Grade B");
        } else if (marks >= 40) {
            System.out.println("Grade C");
        } else {
            System.out.println("Fail");
        }
    }
}
```

Output: `Grade A`. Java checks from top to bottom and runs **only the first** block whose condition is true.

- The condition must be a `boolean`. `if (marks)` is an error in Java (unlike JavaScript or Python).
- Braces are optional for a single statement, but **always use them**. It prevents bugs when you add a second line later.

## Combining conditions

```java
int age = 19;
boolean hasTicket = true;
boolean vip = false;

if ((age >= 18 && hasTicket) || vip) {
    System.out.println("Welcome to the concert!");
}
```

## Nested if

```java
boolean loggedIn = true;
String role = "admin";

if (loggedIn) {
    if ("admin".equals(role)) {
        System.out.println("Open admin dashboard");
    } else {
        System.out.println("Open user dashboard");
    }
} else {
    System.out.println("Please sign in");
}
```

Deep nesting gets hard to read. A cleaner style is the **guard clause**: handle the "exit early" cases first.

```java
static String dashboard(boolean loggedIn, String role) {
    if (!loggedIn) return "Please sign in";
    if ("admin".equals(role)) return "Admin dashboard";
    return "User dashboard";
}
```

## The classic switch statement

When comparing one value against many fixed options, `switch` is cleaner than a long `else if` chain:

```java
int day = 3;
switch (day) {
    case 1:
        System.out.println("Monday");
        break;
    case 2:
        System.out.println("Tuesday");
        break;
    case 3:
        System.out.println("Wednesday");
        break;
    default:
        System.out.println("Another day");
}
```

**Don't forget `break`!** Without it, Java "falls through" and runs the next cases too:

```java
int n = 1;
switch (n) {
    case 1: System.out.println("one");
    case 2: System.out.println("two");      // also printed!
    default: System.out.println("many");    // also printed!
}
```

switch works with `int`, `char`, `String`, enums (and their wrapper types).

## The modern switch expression (Java 14+)

Arrows `->` never fall through, and the switch can **return a value**:

```java
public class Seasons {
    public static void main(String[] args) {
        String month = "OCT";
        String season = switch (month) {
            case "DEC", "JAN", "FEB" -> "Winter";
            case "MAR", "APR", "MAY" -> "Summer";
            case "JUN", "JUL", "AUG", "SEP" -> "Monsoon";
            case "OCT", "NOV" -> "Post-monsoon";
            default -> "Unknown";
        };
        System.out.println(season);    // Post-monsoon
    }
}
```

- Several labels share one case: `case "DEC", "JAN", "FEB"`.
- No `break` needed.
- Need several statements? Use a block and `yield`:

```java
int fee = switch (plan) {
    case "student" -> 0;
    case "pro" -> {
        int base = 499;
        yield base - 50;     // yield returns the value from a block
    }
    default -> 999;
};
```

::tip Prefer the arrow form in new code. It's shorter, safer (no forgotten `break`) and the compiler checks that every enum value is covered.

## Pattern matching for switch (Java 21)

Java 21 can switch on the **type** of an object:

```java
static String describe(Object o) {
    return switch (o) {
        case Integer i when i > 100 -> "big number " + i;
        case Integer i -> "number " + i;
        case String s -> "text of length " + s.length();
        case null -> "nothing";
        default -> "something else";
    };
}
```

You'll use this more after the OOP course.

## Common mistakes

- `if (x = 5)` instead of `if (x == 5)`.
- Comparing Strings with `==` in conditions: use `.equals()`.
- A semicolon after the condition: `if (ok); { ... }` makes the block run always!
- Forgetting `break` in classic switch.
- Order of `else if` matters: put the most specific conditions first (`>= 90` before `>= 75`).

## Exercise

1. Read a number and print whether it's positive, negative or zero, and whether it's even or odd.
2. Read three numbers and print the largest using only `if/else`.
3. Electricity bill: first 100 units ₹5 each, next 100 units ₹7, above 200 units ₹10. Calculate the bill for any number of units.
4. Use a switch expression to convert a number 1-7 into a day name.

::hint Hint for 3 | For 250 units: 100 × 5 + 100 × 7 + 50 × 10. Use `Math.min` or an if/else chain to split units into slabs.

## Interview corner

- **What types can switch work on?** byte, short, char, int, their wrappers, String, enums, and (Java 21) any type with pattern matching.
- **What is fall-through?** In classic switch, without `break` execution continues into the next case.
- **Switch statement vs expression?** A statement performs actions; an expression produces a value (with `->` or `yield`).

## Summary

- `if / else if / else` picks the first true branch; conditions must be boolean.
- Use guard clauses to avoid deep nesting.
- Classic switch needs `break`; the modern arrow switch returns values and never falls through.

Next: loops, to repeat work without repeating code.
