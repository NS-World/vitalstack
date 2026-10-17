---
title: Java Operators and Expressions
slug: java-operators
subject: java
lesson: 4
difficulty: beginner
excerpt: Arithmetic, assignment, comparison, logical, increment and ternary operators in Java, operator precedence, short-circuit evaluation and the Math class.
---

Operators are the symbols that do work on values: add, compare, combine conditions. You'll use them in every line of logic you write.

## Arithmetic operators

| Operator | Meaning | Example | Result |
|---|---|---|---|
| `+` | add | `7 + 3` | `10` |
| `-` | subtract | `7 - 3` | `4` |
| `*` | multiply | `7 * 3` | `21` |
| `/` | divide | `7 / 3` | `2` (int division) |
| `%` | remainder (modulus) | `7 % 3` | `1` |

`%` is incredibly useful: `n % 2 == 0` checks for even numbers, `n % 10` gives the last digit.

```java
public class Digits {
    public static void main(String[] args) {
        int n = 4729;
        System.out.println("Last digit: " + n % 10);        // 9
        System.out.println("Without last: " + n / 10);      // 472
        System.out.println("Is even? " + (n % 2 == 0));     // false
    }
}
```

## Assignment and compound assignment

```java
int score = 10;
score += 5;   // score = score + 5  → 15
score -= 3;   // 12
score *= 2;   // 24
score /= 5;   // 4
score %= 3;   // 1
```

## Increment and decrement

```java
int a = 5;
int b = a++;   // b = 5, then a becomes 6  (post-increment)
int c = ++a;   // a becomes 7, then c = 7  (pre-increment)
System.out.println(a + " " + b + " " + c);   // 7 5 7
```

Post (`a++`) uses the old value then increments; pre (`++a`) increments first. Interviewers love asking this. In real code, use `a++` on its own line to keep things clear.

## Comparison operators

All return a `boolean`:

| Operator | Meaning |
|---|---|
| `==` | equal to |
| `!=` | not equal |
| `>` `<` | greater / less than |
| `>=` `<=` | greater or equal / less or equal |

::tip Use `==` for primitives only. To compare Strings or other objects, use `.equals()`. You'll see exactly why in the Strings lesson.

## Logical operators

| Operator | Meaning | True when |
|---|---|---|
| `&&` | AND | both sides are true |
| `\|\|` | OR | at least one side is true |
| `!` | NOT | flips true/false |

```java
int age = 20;
boolean hasId = true;
boolean canEnter = age >= 18 && hasId;      // true
boolean discount = age < 12 || age > 60;    // false
System.out.println(canEnter + " " + discount + " " + !canEnter);
```

### Short-circuit evaluation

`&&` stops as soon as the left side is false; `||` stops as soon as the left side is true. This protects you from errors:

```java
String name = null;
if (name != null && name.length() > 3) {   // safe: length() never runs when name is null
    System.out.println("Long name");
}
```

## The ternary operator

A one-line if/else that **returns a value**:

```java
int marks = 72;
String result = marks >= 40 ? "Pass" : "Fail";
System.out.println(result);   // Pass
```

Format: `condition ? valueIfTrue : valueIfFalse`. Great for simple choices; use a normal `if` for anything complex.

## Operator precedence

Like maths, some operators go first:

1. `()` brackets
2. `++` `--` `!` (unary)
3. `*` `/` `%`
4. `+` `-`
5. `<` `>` `<=` `>=`
6. `==` `!=`
7. `&&`
8. `||`
9. `?:`
10. `=` `+=` …

```java
System.out.println(2 + 3 * 4);       // 14, not 20
System.out.println((2 + 3) * 4);     // 20
System.out.println("Sum: " + 2 + 3); // Sum: 23 (text joining goes left to right!)
System.out.println("Sum: " + (2 + 3)); // Sum: 5
```

When in doubt, add brackets. They make code clearer for humans too.

## The Math class

```java
public class MathDemo {
    public static void main(String[] args) {
        System.out.println(Math.max(12, 30));      // 30
        System.out.println(Math.min(12, 30));      // 12
        System.out.println(Math.abs(-7));          // 7
        System.out.println(Math.pow(2, 10));       // 1024.0
        System.out.println(Math.sqrt(144));        // 12.0
        System.out.println(Math.round(4.5));       // 5
        System.out.println(Math.ceil(4.1));        // 5.0
        System.out.println(Math.floor(4.9));       // 4.0
        int dice = (int) (Math.random() * 6) + 1;  // 1 to 6
        System.out.println(dice >= 1 && dice <= 6);  // true
    }
}
```

## Common mistakes

- `if (x = 5)` → assignment, not comparison. Java gives a compile error for ints (good!). Use `==`.
- Expecting `5 / 2` to be `2.5`. Use `5 / 2.0` or cast one side to `double`.
- Mixing `&` with `&&`: single `&` doesn't short-circuit.
- `"Total: " + a + b` joins text instead of adding. Wrap the sum in brackets.

## Exercise

1. Given seconds = 3725, print it as `1 h 2 min 5 s` using `/` and `%`.
2. Write an expression that is true when a year is a leap year: divisible by 4 and not by 100, unless divisible by 400.
3. Use the ternary operator to print "Adult" or "Minor".
4. Predict the output: `int x = 3; int y = x++ + ++x;` What are x and y?

::hint Hint for 2 | `(year % 4 == 0 && year % 100 != 0) || year % 400 == 0`
::hint Hint for 4 | `x++` gives 3 (x becomes 4), `++x` makes x 5 and gives 5. So y = 8, x = 5.

## Interview corner

- **`i++` vs `++i`?** Post-increment returns the old value; pre-increment returns the new one.
- **`&&` vs `&`?** `&&` short-circuits; `&` always evaluates both sides (and also works as bitwise AND on ints).
- **What is `"5" + 3 + 2`?** `"532"`. And `3 + 2 + "5"`? `"55"`. Evaluation is left to right.

## Summary

- Arithmetic: `+ - * / %`; watch for integer division.
- Comparisons return booleans; logical `&&`, `||`, `!` combine them with short-circuiting.
- Ternary `?:` picks between two values; brackets beat memorising precedence.
- `Math` has max, min, abs, pow, sqrt, round, random and more.

Next: Strings, the most used class in Java.
