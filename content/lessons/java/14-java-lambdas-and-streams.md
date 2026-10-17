---
title: Java Lambdas and Streams
slug: java-lambdas-and-streams
subject: java
lesson: 14
difficulty: intermediate
excerpt: Write short functions with lambdas and method references, process collections with the Stream API (filter, map, sorted, collect, groupingBy) and avoid nulls with Optional.
---

Java 8 changed how Java code looks. **Lambdas** let you pass small functions around, and **Streams** let you process collections in a clear "pipeline" style: filter this, transform that, collect the result. Every modern Java codebase and Spring Boot project uses them.

## From loop to stream

Find the names of students who scored above 75, in upper case, sorted:

```java
// The classic loop way
List<String> result = new ArrayList<>();
for (Student s : students) {
    if (s.marks() > 75) {
        result.add(s.name().toUpperCase());
    }
}
Collections.sort(result);

// The stream way
List<String> result = students.stream()
        .filter(s -> s.marks() > 75)
        .map(s -> s.name().toUpperCase())
        .sorted()
        .toList();
```

The stream version reads like the problem statement.

## Lambdas

A **lambda** is a short anonymous function: `parameters -> body`.

```java
x -> x * 2                          // one parameter
(a, b) -> a + b                     // two parameters
() -> System.out.println("Hi")      // no parameters
s -> {                              // a block body needs return
    String t = s.trim();
    return t.toUpperCase();
}
```

A lambda can be used wherever Java expects a **functional interface**, an interface with exactly one abstract method:

| Interface | Method | Meaning | Example |
|---|---|---|---|
| `Predicate<T>` | `test(T) → boolean` | a yes/no check | `s -> s.isEmpty()` |
| `Function<T,R>` | `apply(T) → R` | transform | `s -> s.length()` |
| `Consumer<T>` | `accept(T)` | do something | `s -> System.out.println(s)` |
| `Supplier<T>` | `get() → T` | produce a value | `() -> new ArrayList<>()` |
| `BinaryOperator<T>` | `apply(T,T) → T` | combine two | `(a, b) -> a + b` |
| `Comparator<T>` | `compare(T,T) → int` | ordering | `(a, b) -> a.length() - b.length()` |

## Method references

When a lambda just calls one existing method, use `::`:

```java
names.forEach(s -> System.out.println(s));
names.forEach(System.out::println);          // same thing

names.stream().map(s -> s.toUpperCase());
names.stream().map(String::toUpperCase);     // same thing
```

## A complete stream example

```java
import java.util.*;
import java.util.stream.*;

public class StreamDemo {
    record Student(String name, String city, int marks) {}

    public static void main(String[] args) {
        List<Student> students = List.of(
            new Student("Asha", "Pune", 88),
            new Student("Ravi", "Delhi", 72),
            new Student("Meera", "Pune", 95),
            new Student("Karan", "Delhi", 64),
            new Student("Zoya", "Mumbai", 81)
        );

        List<String> toppers = students.stream()
                .filter(s -> s.marks() >= 80)
                .sorted(Comparator.comparingInt(Student::marks).reversed())
                .map(Student::name)
                .toList();
        System.out.println(toppers);                         // [Meera, Asha, Zoya]

        double avg = students.stream().mapToInt(Student::marks).average().orElse(0);
        System.out.println(avg);                             // 80.0

        Map<String, List<String>> byCity = students.stream()
                .collect(Collectors.groupingBy(Student::city, TreeMap::new,
                         Collectors.mapping(Student::name, Collectors.toList())));
        System.out.println(byCity);     // {Delhi=[Ravi, Karan], Mumbai=[Zoya], Pune=[Asha, Meera]}

        Map<String, Long> countByCity = students.stream()
                .collect(Collectors.groupingBy(Student::city, TreeMap::new, Collectors.counting()));
        System.out.println(countByCity);                     // {Delhi=2, Mumbai=1, Pune=2}

        boolean anyFail = students.stream().anyMatch(s -> s.marks() < 40);
        System.out.println(anyFail);                         // false

        String names = students.stream().map(Student::name).collect(Collectors.joining(", "));
        System.out.println(names);                           // Asha, Ravi, Meera, Karan, Zoya
    }
}
```

(`record` creates a simple data class in one line. You'll meet records properly in the OOP course.)

## How a stream pipeline works

1. **Source:** `list.stream()`, `Arrays.stream(arr)`, `Stream.of(...)`, `IntStream.range(0, 10)`.
2. **Intermediate operations** (lazy, return a new stream): `filter`, `map`, `sorted`, `distinct`, `limit`, `skip`, `flatMap`, `peek`.
3. **Terminal operation** (runs the pipeline): `toList`, `collect`, `forEach`, `count`, `sum`, `reduce`, `anyMatch`, `findFirst`, `min`, `max`.

Nothing runs until the terminal operation. And a stream can be used **only once**.

```java
int sumOfSquaresOfEvens = IntStream.rangeClosed(1, 10)
        .filter(n -> n % 2 == 0)
        .map(n -> n * n)
        .sum();                 // 4 + 16 + 36 + 64 + 100 = 220
```

## reduce

`reduce` combines all elements into one:

```java
int product = Stream.of(1, 2, 3, 4, 5).reduce(1, (a, b) -> a * b);   // 120
```

## Optional: a box that may be empty

Methods like `findFirst` and `max` return `Optional`, because there might be no result:

```java
Optional<Student> best = students.stream().max(Comparator.comparingInt(Student::marks));
System.out.println(best.map(Student::name).orElse("nobody"));   // Meera

Optional<Student> fromChennai = students.stream().filter(s -> s.city().equals("Chennai")).findFirst();
System.out.println(fromChennai.isPresent());                     // false
```

Use `orElse`, `orElseGet`, `orElseThrow`, `map`, `ifPresent` instead of calling `get()` blindly. Optional makes "might be missing" explicit and prevents NullPointerExceptions.

::tip Don't overuse streams. A simple loop is fine and sometimes clearer, especially when you need indexes, early exits with complex logic, or checked exceptions.

## Common mistakes

- Reusing a stream after a terminal operation: `IllegalStateException`.
- Changing external variables inside a lambda (they must be effectively final).
- Using `peek` or `map` for side effects like saving to a database.
- `Optional.get()` without checking: defeats the purpose.

## Exercise

Given `List<Integer> nums = List.of(5, 12, 7, 3, 18, 12, 9, 4);`

1. Collect the even numbers into a list.
2. Get the distinct numbers sorted in descending order.
3. Find the sum of numbers greater than 6.
4. Find the second largest distinct number.
5. Given a list of words, build a map of word length → list of words.

::hint Hint for 4 | `nums.stream().distinct().sorted(Comparator.reverseOrder()).skip(1).findFirst()`
::hint Hint for 5 | `words.stream().collect(Collectors.groupingBy(String::length))`

## Interview corner

- **What is a functional interface?** An interface with exactly one abstract method (optionally marked `@FunctionalInterface`), so it can be implemented with a lambda.
- **map vs flatMap?** `map` transforms each element to one element; `flatMap` transforms each element to a stream and flattens them into one stream.
- **Intermediate vs terminal operations?** Intermediate are lazy and return streams; terminal trigger processing and produce a result.
- **Stream vs Collection?** A collection stores data; a stream processes data once, lazily, without storing it.

## Summary

- Lambdas are short functions; method references (`Class::method`) are even shorter.
- Streams: source → intermediate operations → terminal operation.
- `filter`, `map`, `sorted`, `collect(groupingBy)`, `reduce` cover most needs.
- `Optional` represents a value that might be missing.

Next: reading and writing files.
