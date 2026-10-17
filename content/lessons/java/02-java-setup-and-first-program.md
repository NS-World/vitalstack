---
title: Installing Java and Running Programs
slug: java-setup-and-first-program
subject: java
lesson: 2
difficulty: beginner
excerpt: Install the JDK on Windows, Mac or Linux, compile and run with javac and java, try code instantly in JShell, and set up IntelliJ IDEA or VS Code for Java.
---

Let's get Java running on your computer. You will install the JDK, compile a program from the terminal, try JShell, and set up an editor.

## Step 1: Install the JDK

Download a free JDK build. **Eclipse Temurin 21 (LTS)** from adoptium.net is a great choice. Oracle JDK 21 also works.

- **Windows:** download the `.msi` installer and tick "Set JAVA_HOME variable" and "Add to PATH".
- **Mac:** download the `.pkg`, or run `brew install --cask temurin@21`.
- **Linux (Ubuntu):** `sudo apt install openjdk-21-jdk`.

## Step 2: Check the installation

Open a new terminal (Command Prompt / PowerShell on Windows) and run:

```bash
java -version
javac -version
```

You should see something like `openjdk version "21.0.4"`. If you see "command not found", the JDK's `bin` folder is not on your PATH. Reinstall with the PATH option ticked, or add it manually.

## Step 3: Compile and run from the terminal

Create a folder, and a file named `Greeting.java` inside it:

```java
public class Greeting {
    public static void main(String[] args) {
        String name = "Asha";
        System.out.println("Welcome to Java, " + name + "!");
    }
}
```

In the terminal, go to that folder and run:

```bash
javac Greeting.java      # creates Greeting.class (bytecode)
java Greeting            # runs it on the JVM (no .class extension!)
```

Output:

```text
Welcome to Java, Asha!
```

### Shortcut: run a single file directly

Since Java 11 you can skip the `javac` step for single-file programs:

```bash
java Greeting.java
```

Java compiles it in memory and runs it. Perfect for practice.

## Step 4: JShell, Java's playground

JShell (Java 9+) lets you type Java and see results immediately, with no class or `main` needed:

```bash
jshell
```

```text
jshell> int a = 7
a ==> 7

jshell> a * 6
$2 ==> 42

jshell> "java".toUpperCase()
$3 ==> "JAVA"

jshell> /exit
```

Use JShell to test small ideas quickly.

## Step 5: Choose an editor (IDE)

| Editor | Why |
|---|---|
| **IntelliJ IDEA Community** (free) | The most popular Java IDE: smart completion, refactoring, debugger. Recommended. |
| **VS Code** + "Extension Pack for Java" | Lightweight, good if you already use VS Code. |
| **Eclipse** | Older but still used in many companies. |

In IntelliJ: **New Project → Java → choose JDK 21 → Create**. Right-click the `src` folder → **New → Java Class**, type `Main`, then type `psvm` and press Tab: IntelliJ writes the `main` method for you. Type `sout` + Tab for `System.out.println()`.

::tip Learn these IntelliJ shortcuts early: `psvm` (main method), `sout` (println), `Alt+Enter` (quick fix), `Ctrl+Alt+L` (format code), `Shift+F10` (run).

## Command-line arguments

The `String[] args` in `main` receives words typed after the class name:

```java
public class Args {
    public static void main(String[] args) {
        System.out.println("You passed " + args.length + " arguments");
        for (String a : args) {
            System.out.println("- " + a);
        }
    }
}
```

```bash
java Args.java red green
```

```text
You passed 2 arguments
- red
- green
```

## Reading compiler errors

Java's compiler tells you the file, the line and the problem:

```text
Greeting.java:4: error: ';' expected
        System.out.println("Hi")
                                ^
1 error
```

Read the **first** error first: later errors are often caused by it. Line 4, a semicolon is missing.

## Common mistakes

- Running `java Greeting.class` (wrong) instead of `java Greeting`.
- An old Java version on the PATH: check `java -version` and `javac -version` show the same version.
- Saving the file as `Greeting.java.txt` on Windows (turn on "show file extensions").

## Exercise

1. Install the JDK and confirm `java -version` works.
2. Write `About.java` that prints three facts about you, then compile and run it with `javac` and `java`.
3. In JShell, calculate how many seconds are in a day.
4. Modify `Args.java` to print the arguments in reverse order.

::hint Hint for 4 | Loop from `args.length - 1` down to `0`: `for (int i = args.length - 1; i >= 0; i--)`.

## Summary

- Install a JDK (Temurin 21), then check with `java -version`.
- `javac File.java` compiles; `java ClassName` runs. `java File.java` does both for single files.
- JShell is a quick playground; IntelliJ IDEA is the most popular IDE.

Next: variables and data types, the building blocks of every program.
