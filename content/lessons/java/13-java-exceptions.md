---
title: Java Exception Handling
slug: java-exceptions
subject: java
lesson: 13
difficulty: intermediate
excerpt: Handle errors gracefully with try, catch and finally, understand checked vs unchecked exceptions, throw and throws, try-with-resources and custom exceptions.
---

Things go wrong: a user types "abc" instead of a number, a file is missing, the network drops. Without handling, Java prints a scary stack trace and the program stops. **Exception handling** lets you catch the problem and respond gracefully.

## What an exception looks like

```java
public class Crash {
    public static void main(String[] args) {
        int[] marks = {80, 90};
        System.out.println(marks[5]);
        System.out.println("This never prints");
    }
}
```

```text
Exception in thread "main" java.lang.ArrayIndexOutOfBoundsException: Index 5 out of bounds for length 2
	at Crash.main(Crash.java:4)
```

Read stack traces from the top: the exception type, the message, then where it happened (file and line).

## try and catch

Put risky code in `try`; handle problems in `catch`. Step through the animation and try `ten / 2` and `10 / 2` too:

::viz exception-flow {"expr": "10 / 0"}

```java
public class SafeDivide {
    public static void main(String[] args) {
        String[] inputs = {"10", "abc", "0"};
        for (String s : inputs) {
            try {
                int n = Integer.parseInt(s);
                System.out.println("100 / " + n + " = " + (100 / n));
            } catch (NumberFormatException e) {
                System.out.println("'" + s + "' is not a number");
            } catch (ArithmeticException e) {
                System.out.println("Can't divide by zero");
            } finally {
                System.out.println("-- checked " + s);
            }
        }
    }
}
```

```text
100 / 10 = 10
-- checked 10
'abc' is not a number
-- checked abc
Can't divide by zero
-- checked 0
```

- When an exception happens, Java **skips the rest of the try** and jumps to the first matching `catch`.
- `finally` runs **always**: success, exception, even after `return`. Use it for cleanup.
- Catch several types in one block with `|`: `catch (NumberFormatException | ArithmeticException e)`.

## The exception family tree

```text
Throwable
├── Error                  (serious JVM problems: OutOfMemoryError, StackOverflowError; don't catch)
└── Exception
    ├── IOException, SQLException, ...      ← checked
    └── RuntimeException                    ← unchecked
        ├── NullPointerException
        ├── ArithmeticException
        ├── ArrayIndexOutOfBoundsException
        ├── NumberFormatException (via IllegalArgumentException)
        └── IllegalStateException
```

## Checked vs unchecked

| | Checked | Unchecked (RuntimeException) |
|---|---|---|
| Examples | `IOException`, `SQLException` | `NullPointerException`, `IllegalArgumentException` |
| Compiler forces handling? | Yes: catch it or declare `throws` | No |
| Usually means | something outside your control failed | a bug or invalid input |

```java
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;

public class ReadFile {
    static String load(String file) throws IOException {     // "I might throw this"
        return Files.readString(Path.of(file));
    }

    public static void main(String[] args) {
        try {
            System.out.println(load("notes.txt"));
        } catch (IOException e) {
            System.out.println("Could not read file: " + e.getMessage());
        }
    }
}
```

## throw: raise your own exception

```java
static void withdraw(double balance, double amount) {
    if (amount <= 0) {
        throw new IllegalArgumentException("Amount must be positive");
    }
    if (amount > balance) {
        throw new IllegalStateException("Insufficient balance");
    }
    System.out.println("Withdrawn " + amount);
}
```

- `throw` (singular, inside a method) raises an exception.
- `throws` (plural, in the method signature) declares what might be thrown.

## Custom exceptions

Create meaningful exceptions for your domain:

```java
public class InsufficientFundsException extends Exception {
    private final double shortBy;

    public InsufficientFundsException(double shortBy) {
        super("Insufficient funds, short by ₹" + shortBy);
        this.shortBy = shortBy;
    }

    public double getShortBy() { return shortBy; }
}
```

Extend `Exception` for checked, `RuntimeException` for unchecked. Most modern frameworks (like Spring) prefer unchecked exceptions.

## try-with-resources

Files, database connections and network sockets must be closed. `try (...)` closes them automatically, even on error:

```java
import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;

public class FirstLine {
    public static void main(String[] args) {
        try (BufferedReader reader = new BufferedReader(new FileReader("data.txt"))) {
            System.out.println(reader.readLine());
        } catch (IOException e) {
            System.out.println("Problem: " + e.getMessage());
        }   // reader.close() is called automatically
    }
}
```

Any class implementing `AutoCloseable` works here.

## Best practices

- **Catch specific exceptions**, not `Exception` or `Throwable`.
- **Never swallow exceptions** silently: `catch (Exception e) {}` hides bugs. At least log them.
- **Validate early**: check inputs and throw `IllegalArgumentException` with a clear message.
- **Don't use exceptions for normal flow** (like ending a loop). They're slower and confusing.
- **Keep the cause** when wrapping: `throw new ServiceException("Save failed", e);`.

::tip `NullPointerException` is the most common Java exception. Avoid it by checking for null, using `Objects.requireNonNull`, returning empty lists instead of null, or `Optional` (Streams lesson). Java 14+ even tells you exactly which variable was null.

## Common mistakes

- Catching `Exception` before a more specific one (compile error: already caught).
- Using `finally` to `return` a value (it overrides the try's return or hides exceptions).
- Forgetting to close resources (use try-with-resources).

## Exercise

1. Read two numbers as Strings, divide them, and handle bad numbers and division by zero with clear messages.
2. Write `validateAge(int age)` that throws `IllegalArgumentException` if age is negative or above 150.
3. Create a checked `InvalidMarksException` and throw it when marks are outside 0-100.
4. What does this print? `try { return 1; } finally { System.out.println("finally"); }`

::hint Hint for 4 | It prints "finally" and then the method returns 1. finally runs before the method actually returns.

## Interview corner

- **Checked vs unchecked?** Checked must be caught or declared (compile-time check); unchecked extends RuntimeException and doesn't.
- **throw vs throws?** `throw` raises an exception object; `throws` declares it in the signature.
- **Does finally always run?** Almost: except if the JVM exits (`System.exit`) or crashes.
- **Error vs Exception?** Errors are serious JVM problems you shouldn't catch; exceptions are conditions your program can handle.
- **What is try-with-resources?** A try that automatically closes `AutoCloseable` resources.

## Summary

- `try` risky code, `catch` specific exceptions, `finally` for cleanup.
- Checked exceptions must be handled or declared; unchecked usually signal bugs.
- `throw` your own exceptions with clear messages; create custom ones for your domain.
- try-with-resources closes files and connections automatically.

Next: lambdas and streams, modern Java's way to process data.
