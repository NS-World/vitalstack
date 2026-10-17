---
title: Java Loops
slug: java-loops
subject: java
lesson: 8
difficulty: beginner
excerpt: Repeat work with for, while, do-while and the enhanced for-each loop, control loops with break and continue, nested loops for patterns, and avoid infinite loops.
---

Loops repeat code. Printing 1,000 invoices, checking every item in a cart, retrying a failed login: all loops. Java has four kinds.

## The for loop

Use `for` when you know how many times to repeat:

```java
public class Table {
    public static void main(String[] args) {
        int n = 7;
        for (int i = 1; i <= 10; i++) {
            System.out.println(n + " x " + i + " = " + (n * i));
        }
    }
}
```

A `for` has three parts: `for (start; condition; update)`.

1. `int i = 1` runs once at the beginning.
2. `i <= 10` is checked **before** each round; if false, the loop ends.
3. `i++` runs **after** each round.

Counting down or in steps:

```java
for (int i = 10; i >= 1; i--) { System.out.print(i + " "); }       // 10 9 8 ... 1
for (int i = 0; i <= 20; i += 5) { System.out.print(i + " "); }    // 0 5 10 15 20
```

## The while loop

Use `while` when you don't know how many rounds you'll need, only the stopping condition:

```java
public class DigitSum {
    public static void main(String[] args) {
        int n = 98765, sum = 0;
        while (n > 0) {
            sum += n % 10;   // take the last digit
            n /= 10;         // drop the last digit
        }
        System.out.println(sum);   // 35
    }
}
```

## The do-while loop

`do-while` checks the condition **after** the body, so it always runs **at least once**. Perfect for menus:

```java
import java.util.Scanner;

public class Menu {
    public static void main(String[] args) {
        Scanner in = new Scanner(System.in);
        int choice;
        do {
            System.out.println("1. Start quiz  2. Scores  0. Exit");
            choice = Integer.parseInt(in.nextLine());
            System.out.println("You chose " + choice);
        } while (choice != 0);
    }
}
```

## The enhanced for (for-each)

To visit every item in an array or collection, for-each is simplest:

```java
int[] marks = {78, 92, 65, 88};
int total = 0;
for (int m : marks) {
    total += m;
}
System.out.println("Average: " + (double) total / marks.length);   // 80.75
```

Read it as "for each `m` in `marks`". You don't get the index, and you can't modify the array through `m`. Use a normal `for` when you need the index.

## break and continue

- `break` exits the loop immediately.
- `continue` skips the rest of this round and moves to the next.

```java
public class FirstMultiple {
    public static void main(String[] args) {
        for (int i = 1; i <= 50; i++) {
            if (i % 3 != 0) continue;        // skip non-multiples of 3
            if (i % 7 == 0) {
                System.out.println("First multiple of 3 and 7: " + i);   // 21
                break;
            }
        }
    }
}
```

## Nested loops

A loop inside a loop. The inner loop finishes all its rounds for **each** round of the outer loop:

```java
public class Stars {
    public static void main(String[] args) {
        int rows = 4;
        for (int r = 1; r <= rows; r++) {
            for (int c = 1; c <= r; c++) {
                System.out.print("* ");
            }
            System.out.println();
        }
    }
}
```

```text
* 
* * 
* * * 
* * * * 
```

Pattern programs like this are very common in fresher interviews and college exams. Nested loops over n items run n × n times, which is **O(n²)**. See the DSA course's Big-O lesson for why that matters.

### Labelled break

To exit **both** loops at once, label the outer loop:

```java
outer:
for (int i = 0; i < 5; i++) {
    for (int j = 0; j < 5; j++) {
        if (i * j == 6) {
            System.out.println("Found " + i + " x " + j);
            break outer;
        }
    }
}
```

## Infinite loops

```java
int i = 0;
while (i < 5) {
    System.out.println(i);
    // forgot i++ → runs forever!
}
```

Always make sure something inside the loop moves it towards the stopping condition. `while (true)` with a `break` inside is fine when used on purpose (servers, game loops).

## Which loop when?

| Loop | Use when |
|---|---|
| `for` | you know the count, or need the index |
| for-each | you visit every item of an array/collection |
| `while` | you repeat until something happens |
| `do-while` | the body must run at least once (menus, input validation) |

## Common mistakes

- Off-by-one: `i <= arr.length` instead of `i < arr.length` → `ArrayIndexOutOfBoundsException`.
- Semicolon after the loop header: `for (...);` makes an empty loop.
- Changing the loop variable inside a `for` body: confusing, avoid.

## Exercise

1. Print all even numbers from 1 to 50 in one line.
2. Calculate the factorial of 10 with a loop (use `long`).
3. Print the first 15 Fibonacci numbers.
4. Check whether a number is prime (loop up to the square root).
5. Print this pyramid for 4 rows:

```text
   *
  ***
 *****
*******
```

::hint Hint for 4 | Loop `i` from 2 while `i * i <= n`. If `n % i == 0`, it's not prime.
::hint Hint for 5 | Row r has `rows - r` spaces then `2 * r - 1` stars.

## Interview corner

- **while vs do-while?** do-while runs the body at least once; while may run zero times.
- **Can you use for-each to remove items from a list?** No: it throws `ConcurrentModificationException`. Use an Iterator's `remove()` or `list.removeIf(...)`.
- **Print 1 to 100 without a loop?** Recursion: `void print(int n) { if (n > 100) return; System.out.println(n); print(n + 1); }`.

## Summary

- `for` for counted loops, `while` for condition loops, `do-while` for at-least-once, for-each for collections.
- `break` exits, `continue` skips a round, labels break outer loops.
- Nested loops multiply work: n × n rounds.

Next: arrays, to store many values in one variable.
