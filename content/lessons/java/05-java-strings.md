---
title: Java Strings
slug: java-strings
subject: java
lesson: 5
difficulty: beginner
excerpt: Create and use Strings in Java, the most useful String methods, why == fails and equals() works, the string pool, immutability, StringBuilder and text blocks.
---

Text is everywhere: names, emails, messages, JSON. In Java, text is handled by the `String` class. Strings look simple, but they have a few surprises that every Java interview asks about.

## Creating Strings

```java
String course = "Java";                 // string literal (recommended)
String other = new String("Java");      // forces a new object (rarely needed)
String empty = "";
```

## Useful String methods

```java
public class StringMethods {
    public static void main(String[] args) {
        String s = "  Learn Java at VitalStack  ";
        String t = s.trim();                              // remove spaces at both ends
        System.out.println(t);                            // Learn Java at VitalStack
        System.out.println(t.length());                   // 24
        System.out.println(t.toUpperCase());              // LEARN JAVA AT VITALSTACK
        System.out.println(t.charAt(0));                  // L
        System.out.println(t.indexOf("Java"));            // 6
        System.out.println(t.contains("Stack"));          // true
        System.out.println(t.substring(6, 10));           // Java  (end index excluded)
        System.out.println(t.replace("Java", "Spring"));  // Learn Spring at VitalStack
        System.out.println(t.startsWith("Learn"));        // true
        System.out.println(String.join("-", "a", "b", "c"));  // a-b-c
        String[] words = t.split(" ");
        System.out.println(words.length);                 // 4
        System.out.println("".isEmpty() + " " + "  ".isBlank());  // true true
    }
}
```

Indexes start at **0**. `substring(start, end)` includes `start` but **excludes** `end`.

## == vs equals(): the classic trap

`==` on objects compares **addresses** (is it the same object?). `.equals()` compares **content** (same characters?). Watch it happen:

::viz string-pool

```java
public class Compare {
    public static void main(String[] args) {
        String a = "java";
        String b = "java";
        String c = new String("java");
        System.out.println(a == b);          // true: both point to the same pooled literal
        System.out.println(a == c);          // false: c is a separate object
        System.out.println(a.equals(c));     // true: same characters
        System.out.println("JAVA".equalsIgnoreCase(a));   // true
    }
}
```

**Rule: always compare Strings with `.equals()`.** `a == b` being true above is an optimisation (the **string pool**: Java reuses identical literals), not something to rely on.

::tip Write `"admin".equals(role)` instead of `role.equals("admin")`. If `role` is null, the first form returns false; the second crashes with NullPointerException.

## Strings are immutable

A String can never change. Methods like `toUpperCase()` return a **new** String:

```java
String name = "asha";
name.toUpperCase();              // result thrown away!
System.out.println(name);        // asha
name = name.toUpperCase();       // keep the new String
System.out.println(name);        // ASHA
```

Why immutable? It makes Strings safe to share between threads, safe as HashMap keys, and lets the string pool reuse them.

## StringBuilder: build text efficiently

Because Strings are immutable, `+=` in a loop creates a new String every time. For many changes, use `StringBuilder`:

```java
public class Builder {
    public static void main(String[] args) {
        StringBuilder sb = new StringBuilder();
        for (int i = 1; i <= 5; i++) {
            sb.append(i).append(i < 5 ? ", " : "");
        }
        System.out.println(sb);                    // 1, 2, 3, 4, 5
        System.out.println(sb.reverse());          // 5 ,4 ,3 ,2 ,1
        StringBuilder word = new StringBuilder("level");
        System.out.println(word.reverse().toString().equals("level"));   // true: palindrome
    }
}
```

`StringBuilder` is mutable: `append`, `insert`, `delete`, `reverse` change the same object. (`StringBuffer` is the older, thread-safe, slower version.)

## Formatting text

```java
String name = "Ravi";
double total = 1234.5;
String msg = String.format("Hi %s, your total is ₹%.2f", name, total);
System.out.println(msg);                     // Hi Ravi, your total is ₹1234.50
System.out.println("%s scored %d%%".formatted("Neha", 92));   // Neha scored 92%
```

`%s` = text, `%d` = whole number, `%.2f` = decimal with 2 places, `%%` = a percent sign.

## Text blocks (Java 15+)

For multi-line text like JSON or SQL, use triple quotes:

```java
String json = """
        {
          "course": "Java",
          "lessons": 16
        }
        """;
System.out.print(json);
```

## Looping over characters

```java
public class CountVowels {
    public static void main(String[] args) {
        String text = "VitalStack teaches Java";
        int vowels = 0;
        for (char ch : text.toLowerCase().toCharArray()) {
            if ("aeiou".indexOf(ch) >= 0) vowels++;
        }
        System.out.println("Vowels: " + vowels);   // Vowels: 8
    }
}
```

## Converting to and from Strings

```java
int n = Integer.parseInt("42");          // "42" → 42
double d = Double.parseDouble("3.5");    // "3.5" → 3.5
String s1 = String.valueOf(99);          // 99 → "99"
String s2 = "" + 99;                     // also works
```

`Integer.parseInt("abc")` throws `NumberFormatException`. You'll learn to handle that in the Exceptions lesson.

## Common mistakes

- Comparing with `==` instead of `.equals()`.
- Forgetting Strings are immutable: calling `s.trim()` without saving the result.
- `substring` end index off by one.
- Building big strings with `+=` inside loops: use `StringBuilder`.

## Exercise

1. Check if a word is a palindrome (ignore case).
2. Count how many times the letter `a` appears in "Java and JavaScript are different".
3. Turn "vital stack java course" into "Vital Stack Java Course".
4. Mask an email: "asha.kumar@gmail.com" → "a*********@gmail.com".

::hint Hint for 3 | Split on spaces, then for each word take `w.substring(0, 1).toUpperCase() + w.substring(1)` and join with a StringBuilder.
::hint Hint for 4 | Find the `@` with `indexOf("@")`. Keep the first character, add `"*".repeat(at - 1)`, then add `substring(at)`.

## Interview corner

- **Why are Strings immutable in Java?** Security (paths, URLs can't be changed after checks), thread safety, caching of hashCode and the string pool.
- **String vs StringBuilder vs StringBuffer?** String is immutable; StringBuilder is mutable and fast; StringBuffer is mutable and synchronized (thread-safe but slower).
- **How many objects does `new String("java")` create?** Up to two: the pooled literal "java" (if not already in the pool) and the new object on the heap.

## Summary

- Strings are immutable objects with many helpful methods.
- Compare content with `.equals()`, never `==`.
- Use `StringBuilder` for building text in loops, `String.format` for formatting, text blocks for multi-line text.

Next: reading user input and printing formatted output.
