---
title: Java Methods
slug: java-methods
subject: java
lesson: 10
difficulty: beginner
excerpt: Write reusable Java methods with parameters and return values, understand pass-by-value, overload methods, use varargs and watch recursion on the call stack.
---

A **method** is a named block of code that does one job. Instead of copying the same 10 lines in five places, you write a method once and **call** it five times. Methods make programs shorter, clearer and easier to fix.

## Defining and calling a method

```java
public class Methods {
    static int square(int n) {        // define
        return n * n;
    }

    static void greet(String name) {  // no return value
        System.out.println("Namaste, " + name + "!");
    }

    public static void main(String[] args) {
        greet("Riya");                         // call
        int result = square(9);                // call and keep the result
        System.out.println(result);            // 81
        System.out.println(square(3) + square(4));   // 25
    }
}
```

Anatomy: `static int square(int n)`

- `static`: belongs to the class, so `main` (also static) can call it without an object. (You'll learn about non-static methods in the OOP course.)
- `int`: the **return type**. `void` means "returns nothing".
- `square`: the name, in camelCase, usually a verb.
- `(int n)`: **parameters**: inputs the method needs.
- `return n * n;` sends a value back and ends the method.

## Multiple parameters and early return

```java
static double finalPrice(double price, int discountPercent) {
    if (discountPercent <= 0) return price;           // early return
    return price - price * discountPercent / 100.0;
}

static boolean isEligible(int age, boolean hasId) {
    return age >= 18 && hasId;
}
```

## Pass by value

Java **always** passes a copy of the value. For primitives, the method gets a copy of the number:

```java
static void tryToChange(int x) {
    x = 100;
}

int a = 5;
tryToChange(a);
System.out.println(a);   // 5: the original is untouched
```

For objects and arrays, the copied value is the **reference**. So the method can modify the object it points to, but can't make the caller's variable point somewhere else:

```java
static void addBonus(int[] marks) {
    marks[0] += 5;           // changes the caller's array
}
```

## Method overloading

Several methods can share a name if their **parameter lists differ**:

```java
static int add(int a, int b) { return a + b; }
static int add(int a, int b, int c) { return a + b + c; }
static double add(double a, double b) { return a + b; }

add(2, 3);        // 5   → first version
add(2, 3, 4);     // 9   → second
add(2.5, 1.5);    // 4.0 → third
```

Java picks the version by the arguments. A different **return type alone** is not enough to overload.

## Varargs: any number of arguments

```java
static int sum(int... nums) {        // nums is an int[]
    int total = 0;
    for (int n : nums) total += n;
    return total;
}

sum();              // 0
sum(4, 5);          // 9
sum(1, 2, 3, 4);    // 10
```

`String.format` and `printf` use varargs, that's how they accept any number of values.

## Scope: where variables live

Variables declared inside a method (or block) exist only there:

```java
static void demo() {
    int x = 10;
    if (x > 5) {
        int y = 20;      // y only exists inside this if-block
    }
    // System.out.println(y);   // error: cannot find symbol
}
```

## Recursion: a method calling itself

A recursive method solves a problem by calling itself on a smaller version, until it hits a **base case**. Each call gets its own frame on the **call stack**. Play the animation to watch frames being pushed and popped:

::viz call-stack {"n": 4}

```java
public class Factorial {
    static long factorial(int n) {
        if (n <= 1) return 1;            // base case: stop here
        return n * factorial(n - 1);     // recursive case
    }

    public static void main(String[] args) {
        System.out.println(factorial(5));   // 120
        System.out.println(factorial(20));  // 2432902008176640000
    }
}
```

Without a base case, calls pile up until Java throws **`StackOverflowError`**.

::tip Think of recursion as a stack of plates: each call adds a plate; the base case starts removing them, and each plate's result is used by the one below.

## Good method habits

- **One job per method.** If you need "and" to describe it, split it.
- **Short:** aim for under ~20 lines.
- **Clear names:** `calculateGst`, `isValidEmail`, `printReceipt`.
- **Return values** rather than printing inside: easier to test and reuse.

## Common mistakes

- Missing `return` in a non-void method (compile error: missing return statement).
- Code after `return` (unreachable statement error).
- Calling a non-static method from `main` without an object.
- Expecting a method to change a primitive variable passed to it.

## Exercise

1. Write `isEven(int n)` returning boolean.
2. Write `max(int a, int b, int c)`.
3. Write `countDigits(long n)`.
4. Write a recursive `power(int base, int exp)`.
5. Write a recursive `sumDigits(int n)`: `sumDigits(1234)` = 10.
6. Overload `area`: one for a circle (radius), one for a rectangle (length, width).

::hint Hint for 4 | Base case: `exp == 0` returns 1. Otherwise `base * power(base, exp - 1)`.
::hint Hint for 5 | `n % 10 + sumDigits(n / 10)`, with base case `n == 0` returns 0.

## Interview corner

- **Is Java pass-by-value or pass-by-reference?** Always pass-by-value. For objects, the value passed is a copy of the reference.
- **Overloading vs overriding?** Overloading = same name, different parameters, same class (compile time). Overriding = subclass redefines a parent method with the same signature (runtime). See the OOP course.
- **What causes StackOverflowError?** Recursion that's too deep or never reaches a base case.

## Summary

- Methods have a return type, a name and parameters; `return` sends a value back.
- Java is pass-by-value; objects are passed as copies of references.
- Overloading reuses a name with different parameters; varargs accepts any count.
- Recursion uses the call stack and needs a base case.

Next: collections, the dynamic lists and sets that real Java programs use every day.
