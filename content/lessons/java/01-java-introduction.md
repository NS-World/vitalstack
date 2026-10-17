---
title: Java Introduction
slug: java-introduction
subject: java
lesson: 1
difficulty: beginner
excerpt: What Java is, why it powers banks, Android and big companies, how the JDK, JRE and JVM fit together, and your very first Java program explained line by line.
---

Java is one of the most used programming languages in the world. Banking systems, Android apps, Flipkart and Amazon back-ends, Infosys and TCS projects, Minecraft: all built with Java. If you want a job as a back-end or full-stack developer in India, Java is the language most companies hire for.

## Why learn Java?

- **Write once, run anywhere.** The same Java program runs on Windows, Mac, Linux and servers without changes.
- **Huge job market.** Service companies (TCS, Infosys, Wipro, Accenture) and product companies (Amazon, Flipkart, Paytm) use Java.
- **Strong and safe.** Java checks your types before the program runs, so many mistakes are caught early.
- **Massive ecosystem.** Spring Boot, Hibernate, Android, Kafka, Elasticsearch: all Java.
- **Great for learning OOP.** Java is built around classes and objects, the way large software is designed.

## The magic trick: bytecode and the JVM

Most languages turn your code into machine code for one type of computer. Java does something smarter:

```text
  Hello.java   ──javac──▶   Hello.class   ──java (JVM)──▶   runs on any OS
 (your code)   compiler     (bytecode)       virtual machine
```

1. You write code in a `.java` file.
2. The compiler `javac` turns it into **bytecode** (a `.class` file). Bytecode is not for any specific CPU.
3. The **JVM** (Java Virtual Machine) reads the bytecode and runs it on your computer. There is a JVM for every operating system.

Think of bytecode as a movie with subtitles in a universal language, and the JVM as the local translator in every country.

## JDK, JRE and JVM

| Name | What it is | Who needs it |
|---|---|---|
| **JVM** | Runs bytecode, manages memory, does garbage collection | Built into the JRE |
| **JRE** | JVM + standard libraries: everything needed to *run* Java | Users running Java apps |
| **JDK** | JRE + compiler (`javac`) + tools: everything needed to *write* Java | You, the developer |

As a developer you install the **JDK**. Use an LTS (long-term support) version: **Java 21** or 17.

## Your first Java program

```java
public class Hello {
    public static void main(String[] args) {
        System.out.println("Hello, VitalStack!");
    }
}
```

Output:

```text
Hello, VitalStack!
```

Line by line:

- `public class Hello` – every Java program lives inside a **class**. The file must be named `Hello.java` (same as the public class).
- `public static void main(String[] args)` – the **entry point**. The JVM starts running your program here. You will understand each word soon:
  - `public`: the JVM can call it from outside.
  - `static`: no object needed to call it.
  - `void`: it returns nothing.
  - `String[] args`: command-line arguments.
- `System.out.println(...)` – prints a line of text to the console.
- Every statement ends with a **semicolon** `;` and blocks are wrapped in **curly braces** `{ }`.

::tip Java is case-sensitive. `System` and `system` are different. `Main` and `main` are different. Most beginner errors are a capital letter or a missing semicolon.

## Printing more

```java
public class Intro {
    public static void main(String[] args) {
        System.out.println("Line one");
        System.out.print("Same ");
        System.out.print("line");
        System.out.println();              // just a new line
        System.out.println(10 + 20);       // 30
        System.out.println("10 + 20 = " + (10 + 20));
    }
}
```

`println` adds a new line at the end; `print` does not. The `+` joins text with values.

## Comments

```java
// A single-line comment

/* A comment that
   spans many lines */

/**
 * A Javadoc comment: used to generate documentation.
 */
```

Comments are ignored by the compiler. Use them to explain **why**, not **what**.

## Where Java is used

- **Back-end web development:** Spring Boot REST APIs (you will learn this in this site's Spring Boot course).
- **Android apps:** Android was built on Java (Kotlin now shares the stage).
- **Enterprise software:** banking, insurance, telecom, government systems.
- **Big data:** Hadoop, Kafka, Spark, Elasticsearch.
- **Business apps:** Jmix lets you build full apps in Java quickly.

## Common mistakes

- File name and class name don't match (`hello.java` with `class Hello`).
- Missing semicolon or closing brace.
- Writing `system.out.println` instead of `System.out.println`.
- Forgetting the exact `main` signature, so the JVM can't find where to start.

## Exercise

1. Write a program that prints your name, your city and your favourite language on three lines.
2. Print `5 * 7 = 35` using a calculation, not by typing 35.
3. Use `print` twice and `println` once to make one line: `Java is fun!`.

::hint Hint for 2 | `System.out.println("5 * 7 = " + (5 * 7));` The brackets make Java multiply before joining text.

## Interview corner

- **Why is Java platform independent?** Because the compiler produces bytecode, and each OS has its own JVM that runs that bytecode.
- **JDK vs JRE vs JVM?** JDK = tools to develop; JRE = what you need to run; JVM = the engine inside that executes bytecode.
- **Is the JVM platform independent?** No! Bytecode is platform independent. The JVM itself is different for each OS.

## Summary

- Java code (`.java`) is compiled by `javac` into bytecode (`.class`), which runs on the JVM on any OS.
- Install the JDK (Java 21 LTS) to write Java.
- Every program needs a class and a `main` method; statements end with `;`.

Next, you will install Java and run your first program on your own computer.
