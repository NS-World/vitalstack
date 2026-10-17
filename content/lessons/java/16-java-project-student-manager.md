---
title: "Java Project: Student Grade Manager"
slug: java-project-student-manager
subject: java
lesson: 16
difficulty: intermediate
excerpt: Build a complete console app in Java that adds students, calculates grades, shows toppers with streams, and saves everything to a CSV file. Uses every concept from this course.
---

Time to build something real. You'll make a **Student Grade Manager**: a menu-driven console app that stores students, calculates grades, shows statistics, and saves data to a file so it's still there next time. This project uses records, collections, maps, streams, exceptions, file handling and Scanner.

## What it does

```text
=== Student Grade Manager ===
1. Add student
2. List students
3. Statistics
4. Search by name
5. Save and exit
Choose: 1
Name: Asha
Marks (0-100): 91
Saved Asha (A+)
```

## Step 1: The data

A `record` is perfect for simple data. We add a method to calculate the grade:

```java
record Student(String name, int marks) {
    String grade() {
        if (marks >= 90) return "A+";
        if (marks >= 75) return "A";
        if (marks >= 60) return "B";
        if (marks >= 40) return "C";
        return "F";
    }
}
```

## Step 2: The full program

Save as `GradeManager.java` and run with `java GradeManager.java`.

```java
import java.io.IOException;
import java.nio.file.*;
import java.util.*;
import java.util.stream.Collectors;

public class GradeManager {

    record Student(String name, int marks) {
        String grade() {
            if (marks >= 90) return "A+";
            if (marks >= 75) return "A";
            if (marks >= 60) return "B";
            if (marks >= 40) return "C";
            return "F";
        }
    }

    private static final Path FILE = Path.of("students.csv");
    private static final List<Student> students = new ArrayList<>();
    private static final Scanner in = new Scanner(System.in);

    public static void main(String[] args) {
        load();
        while (true) {
            System.out.println("\n=== Student Grade Manager ===");
            System.out.println("1. Add student\n2. List students\n3. Statistics\n4. Search by name\n5. Save and exit");
            String choice = ask("Choose: ");
            switch (choice) {
                case "1" -> addStudent();
                case "2" -> listStudents();
                case "3" -> statistics();
                case "4" -> search();
                case "5" -> { save(); System.out.println("Bye!"); return; }
                default -> System.out.println("Please choose 1-5.");
            }
        }
    }

    static String ask(String prompt) {
        System.out.print(prompt);
        return in.hasNextLine() ? in.nextLine().trim() : "5";
    }

    static void addStudent() {
        String name = ask("Name: ");
        if (name.isEmpty() || name.contains(",")) {
            System.out.println("Name can't be empty or contain commas.");
            return;
        }
        int marks;
        try {
            marks = Integer.parseInt(ask("Marks (0-100): "));
            if (marks < 0 || marks > 100) throw new IllegalArgumentException("out of range");
        } catch (IllegalArgumentException e) {   // NumberFormatException is a subclass
            System.out.println("Marks must be a whole number from 0 to 100.");
            return;
        }
        Student s = new Student(name, marks);
        students.add(s);
        System.out.println("Saved " + s.name() + " (" + s.grade() + ")");
    }

    static void listStudents() {
        if (students.isEmpty()) { System.out.println("No students yet."); return; }
        System.out.printf("%-15s %5s %5s%n", "Name", "Marks", "Grade");
        students.stream()
                .sorted(Comparator.comparing(Student::name, String.CASE_INSENSITIVE_ORDER))
                .forEach(s -> System.out.printf("%-15s %5d %5s%n", s.name(), s.marks(), s.grade()));
    }

    static void statistics() {
        if (students.isEmpty()) { System.out.println("No students yet."); return; }
        IntSummaryStatistics st = students.stream().mapToInt(Student::marks).summaryStatistics();
        System.out.printf("Students: %d | Average: %.1f | Highest: %d | Lowest: %d%n",
                st.getCount(), st.getAverage(), st.getMax(), st.getMin());

        List<String> toppers = students.stream()
                .filter(s -> s.marks() == st.getMax())
                .map(Student::name).toList();
        System.out.println("Topper(s): " + String.join(", ", toppers));

        Map<String, Long> byGrade = students.stream()
                .collect(Collectors.groupingBy(Student::grade, TreeMap::new, Collectors.counting()));
        byGrade.forEach((g, n) -> System.out.println(g + ": " + "*".repeat(n.intValue()) + " " + n));
    }

    static void search() {
        String q = ask("Search: ").toLowerCase();
        students.stream()
                .filter(s -> s.name().toLowerCase().contains(q))
                .forEach(s -> System.out.println(s.name() + " → " + s.marks() + " (" + s.grade() + ")"));
    }

    static void load() {
        if (!Files.exists(FILE)) return;
        try {
            for (String line : Files.readAllLines(FILE)) {
                String[] p = line.split(",");
                if (p.length == 2) students.add(new Student(p[0], Integer.parseInt(p[1].trim())));
            }
            System.out.println("Loaded " + students.size() + " students.");
        } catch (IOException | NumberFormatException e) {
            System.out.println("Could not load saved data: " + e.getMessage());
        }
    }

    static void save() {
        List<String> lines = students.stream().map(s -> s.name() + "," + s.marks()).toList();
        try {
            Files.write(FILE, lines);
            System.out.println("Saved " + lines.size() + " students to " + FILE);
        } catch (IOException e) {
            System.out.println("Could not save: " + e.getMessage());
        }
    }
}
```

## How it works

- **`record Student`**: an immutable data class with a `grade()` method (OOP in action).
- **`List<Student>`**: the in-memory store.
- **Switch expression** with arrows for the menu.
- **Exceptions**: invalid marks are caught, so the app never crashes on bad input.
- **Streams**: sorting, statistics (`summaryStatistics`), grouping by grade with `TreeMap` for sorted output.
- **Files**: data loads at start and saves on exit as CSV.

Sample statistics output:

```text
Students: 4 | Average: 76.5 | Highest: 91 | Lowest: 52
Topper(s): Asha
A: * 1
A+: * 1
B: * 1
C: * 1
```

## Make it yours: challenges

1. **Edit and delete:** add menu options to update a student's marks and remove a student.
2. **Multiple subjects:** store a `Map<String, Integer>` of subject → marks and compute the average.
3. **Duplicate check:** don't allow two students with the same name (case-insensitive).
4. **Report file:** export a nicely formatted `report.txt`.
5. **Rank list:** print students with their rank (ties share the same rank).

::hint Hint for 3 | Before adding, check `students.stream().anyMatch(s -> s.name().equalsIgnoreCase(name))`.
::hint Hint for 5 | Sort by marks descending. Keep the previous marks and rank; if marks are equal, reuse the rank, otherwise rank = position + 1.

::tip Put this project on GitHub with a README and a screenshot of the output. Recruiters look for small, complete, working projects, not tutorials copied line by line.

## What's next?

You now know core Java. Continue with:

1. **Java OOP**: classes, objects, inheritance, polymorphism and interfaces, with animations.
2. **DSA**: arrays, hashing, recursion and interview patterns.
3. **Servlet, JPA and Spring Boot**: build real web back-ends.

## Summary

- You built a complete menu-driven app with data persistence.
- It combined records, collections, streams, exceptions, file I/O and input handling.
- Extending projects with your own features is the best way to master Java.
