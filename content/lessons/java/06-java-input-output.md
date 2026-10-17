---
title: Java User Input with Scanner
slug: java-input-output
subject: java
lesson: 6
difficulty: beginner
excerpt: Read numbers and text from the keyboard with Scanner, avoid the nextInt/nextLine trap, validate input, and print neat output with printf.
---

Programs become interesting when they react to the user. In Java, the easiest way to read keyboard input is the `Scanner` class.

## Reading input with Scanner

```java
import java.util.Scanner;

public class Hello {
    public static void main(String[] args) {
        Scanner in = new Scanner(System.in);

        System.out.print("Your name: ");
        String name = in.nextLine();

        System.out.print("Your age: ");
        int age = in.nextInt();

        System.out.println("Hi " + name + ", next year you'll be " + (age + 1));
        in.close();
    }
}
```

```text
Your name: Asha Kumar
Your age: 21
Hi Asha Kumar, next year you'll be 22
```

- `import java.util.Scanner;` brings the class in (it's not in `java.lang`).
- `new Scanner(System.in)` reads from the keyboard.

| Method | Reads |
|---|---|
| `nextLine()` | the whole line (with spaces) |
| `next()` | one word (up to a space) |
| `nextInt()` | an int |
| `nextDouble()` | a double |
| `nextBoolean()` | true/false |
| `hasNextInt()` | checks if the next token is an int (doesn't read) |

## The nextInt() + nextLine() trap

```java
System.out.print("Age: ");
int age = in.nextInt();          // user types 21 and presses Enter
System.out.print("City: ");
String city = in.nextLine();     // returns "" immediately!
```

`nextInt()` reads `21` but leaves the **Enter** (newline) behind. The next `nextLine()` reads that empty leftover. Fix: call `in.nextLine();` once after `nextInt()` to consume it, or read every line with `nextLine()` and convert:

```java
int age = Integer.parseInt(in.nextLine().trim());
```

::tip Reading everything with `nextLine()` and converting yourself is the safest habit. It avoids the trap completely.

## Validating input

If the user types `abc` where you expect a number, `nextInt()` throws `InputMismatchException`. Check first:

```java
import java.util.Scanner;

public class SafeInput {
    public static void main(String[] args) {
        Scanner in = new Scanner(System.in);
        System.out.print("Enter marks (0-100): ");
        while (!in.hasNextInt()) {
            System.out.print("Numbers only, try again: ");
            in.next();                      // throw away the bad token
        }
        int marks = in.nextInt();
        System.out.println(marks >= 40 ? "Pass" : "Fail");
    }
}
```

## Printing with printf

`printf` formats output neatly, great for tables and bills:

```java
public class Bill {
    public static void main(String[] args) {
        String[] items = {"Notebook", "Pen set", "Backpack"};
        double[] prices = {120, 250.5, 1899};
        double total = 0;
        System.out.printf("%-10s %10s%n", "Item", "Price");
        for (int i = 0; i < items.length; i++) {
            System.out.printf("%-10s %10.2f%n", items[i], prices[i]);
            total += prices[i];
        }
        System.out.printf("%-10s %10.2f%n", "TOTAL", total);
    }
}
```

```text
Item            Price
Notebook       120.00
Pen set        250.50
Backpack      1899.00
TOTAL         2269.50
```

| Format | Meaning |
|---|---|
| `%d` | integer |
| `%f`, `%.2f` | decimal, 2 decimal places |
| `%s` | String |
| `%c` | char |
| `%n` | new line (works on every OS) |
| `%10s` | right-align in 10 spaces |
| `%-10s` | left-align in 10 spaces |
| `%,d` | thousands separator: `1,000,000` |

## A mini program: simple calculator

```java
import java.util.Scanner;

public class Calculator {
    public static void main(String[] args) {
        Scanner in = new Scanner(System.in);
        System.out.print("First number: ");
        double a = Double.parseDouble(in.nextLine());
        System.out.print("Operator (+ - * /): ");
        String op = in.nextLine().trim();
        System.out.print("Second number: ");
        double b = Double.parseDouble(in.nextLine());

        double result = switch (op) {
            case "+" -> a + b;
            case "-" -> a - b;
            case "*" -> a * b;
            case "/" -> a / b;
            default -> Double.NaN;
        };
        System.out.printf("%.2f %s %.2f = %.2f%n", a, op, b, result);
    }
}
```

(You'll learn `switch` properly in the next lesson.)

## Other ways to read input

- `BufferedReader` with `InputStreamReader`: faster, used in competitive programming.
- Command-line `args`: values passed when starting the program.
- Files: covered in the File I/O lesson.

## Common mistakes

- Forgetting `import java.util.Scanner;`.
- The `nextInt()` then `nextLine()` empty-line trap.
- Creating a new `Scanner` inside a loop: create one and reuse it.
- Closing a `Scanner` on `System.in` and then trying to read again: closing it closes the keyboard stream too.

## Exercise

1. Ask for length and width, then print the area and perimeter of a rectangle.
2. Ask for a temperature in Celsius and print Fahrenheit with one decimal place.
3. Ask for 3 subject marks and print the total, the average (2 decimals) and the grade.
4. Print a multiplication table for a number the user enters, nicely aligned with `printf`.

::hint Hint for 4 | `System.out.printf("%2d x %2d = %3d%n", n, i, n * i);` inside a loop from 1 to 10.

## Summary

- `Scanner` reads from `System.in`: `nextLine`, `nextInt`, `nextDouble`, `hasNextInt`.
- Avoid the `nextInt` + `nextLine` trap by reading lines and parsing them.
- `printf` with `%d`, `%.2f`, `%s`, `%-10s` and `%n` makes output neat.

Next: making decisions with if, else and switch.
