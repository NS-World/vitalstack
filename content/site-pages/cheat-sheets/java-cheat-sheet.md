---
title: Java Cheat Sheet
slug: java-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: ☕
order: 60
excerpt: Core Java on one page - types, strings, arrays, collections, OOP, interfaces, records, enums, exceptions, lambdas and streams, with Java 21 syntax. Printable.
---
## Program and types

```java
public class Main {
    public static void main(String[] args) {
        int age = 21; long views = 3_000_000_000L; double price = 499.5;
        boolean ok = true; char grade = 'A'; String name = "Asha";
        var total = price * 2;                // type inferred
        final double GST = 0.18;              // constant
        System.out.printf("%s is %d%n", name, age);
    }
}
```

| Conversion | Code |
|---|---|
| String → int | `Integer.parseInt("42")` |
| int → String | `String.valueOf(42)` |
| double → int | `(int) 9.99` → 9 (truncates) |
| rounding | `Math.round(9.5)` → 10 |

## Strings

```java
s.length(); s.charAt(0); s.substring(2, 5); s.indexOf("a");
s.toUpperCase(); s.trim(); s.isBlank(); s.replace("a", "b");
s.split(","); String.join("-", list); s.contains("x");
a.equals(b); a.equalsIgnoreCase(b);         // never == for content
var sb = new StringBuilder().append("a").append(1); sb.reverse();
"Hi %s".formatted(name);
String json = """
    {"course": "Java"}
    """;
```

## Control flow

```java
if (m >= 90) { } else if (m >= 75) { } else { }
String season = switch (month) {
    case "DEC", "JAN" -> "Winter";
    default -> "Other";
};
for (int i = 0; i < n; i++) { }
for (String s : list) { }
while (n > 0) { n /= 10; }
do { } while (choice != 0);
```

## Arrays

```java
int[] a = {5, 2, 9}; int[] b = new int[10]; int[][] grid = new int[3][4];
a.length; Arrays.sort(a); Arrays.toString(a); Arrays.fill(b, -1);
int[] copy = Arrays.copyOf(a, a.length);
```

## Collections

```java
List<String> list = new ArrayList<>(List.of("a", "b"));
list.add("c"); list.get(0); list.remove("a"); list.size(); list.contains("b");
list.sort(Comparator.naturalOrder()); list.removeIf(s -> s.isEmpty());

Set<Integer> seen = new HashSet<>(); seen.add(3); seen.contains(3);
Map<String, Integer> count = new HashMap<>();
count.merge(word, 1, Integer::sum);
count.getOrDefault("x", 0);
for (var e : count.entrySet()) { e.getKey(); e.getValue(); }

Deque<Integer> stack = new ArrayDeque<>(); stack.push(1); stack.pop();
Queue<Integer> queue = new ArrayDeque<>(); queue.offer(1); queue.poll();
PriorityQueue<Integer> minHeap = new PriorityQueue<>();
PriorityQueue<Integer> maxHeap = new PriorityQueue<>(Comparator.reverseOrder());
```

| Need | Use |
|---|---|
| ordered list | `ArrayList` |
| unique items | `HashSet` (`LinkedHashSet` keeps order, `TreeSet` sorts) |
| key → value | `HashMap` (`LinkedHashMap`, `TreeMap`) |
| stack / queue | `ArrayDeque` |
| min/max first | `PriorityQueue` |

## OOP

```java
public abstract class Account {
    private double balance;                       // encapsulation
    protected Account(double opening) { balance = opening; }
    public void deposit(double amt) {
        if (amt <= 0) throw new IllegalArgumentException("Positive only");
        balance += amt;
    }
    public double getBalance() { return balance; }
    public abstract double interestRate();        // abstraction
}

public class Savings extends Account {           // inheritance
    public Savings(double opening) { super(opening); }
    @Override public double interestRate() { return 0.04; }   // polymorphism
}

interface Payment { double fee(double amount); default String name() { return "Payment"; } }
```

| Modifier | Visible in |
|---|---|
| `private` | class |
| (none) | package |
| `protected` | package + subclasses |
| `public` | everywhere |

## Records, enums, sealed

```java
record Point(int x, int y) { Point { if (x < 0) throw new IllegalArgumentException(); } }
enum Level { BEGINNER, INTERMEDIATE, ADVANCED }
sealed interface Shape permits Circle, Square { }
record Circle(double r) implements Shape { }
record Square(double side) implements Shape { }
double area = switch (shape) {
    case Circle c -> Math.PI * c.r() * c.r();
    case Square s -> s.side() * s.side();
};
if (obj instanceof String s) { s.length(); }
```

## Exceptions

```java
try (var reader = Files.newBufferedReader(Path.of("a.txt"))) {
    reader.readLine();
} catch (IOException | NumberFormatException e) {
    System.err.println(e.getMessage());
} finally { }
throw new IllegalStateException("Insufficient funds");
class AppException extends RuntimeException { AppException(String m) { super(m); } }
```

## Lambdas and streams

```java
List<String> top = students.stream()
    .filter(s -> s.marks() >= 75)
    .sorted(Comparator.comparingInt(Student::marks).reversed())
    .map(Student::name)
    .limit(3)
    .toList();
double avg = nums.stream().mapToInt(Integer::intValue).average().orElse(0);
Map<String, List<Student>> byCity = students.stream().collect(Collectors.groupingBy(Student::city));
Map<String, Long> counts = words.stream().collect(Collectors.groupingBy(w -> w, Collectors.counting()));
String csv = names.stream().collect(Collectors.joining(", "));
Optional<Student> best = students.stream().max(Comparator.comparingInt(Student::marks));
```

## Files and dates

```java
Files.writeString(Path.of("a.txt"), "hello");
String text = Files.readString(Path.of("a.txt"));
List<String> lines = Files.readAllLines(Path.of("a.txt"));
LocalDate.now().plusDays(14); LocalDateTime.now(); Duration.ofMinutes(90);
```

## Run

```bash
javac Main.java && java Main      # or simply: java Main.java
jshell                            # quick experiments
```
