---
title: Java File Handling
slug: java-file-io
subject: java
lesson: 15
difficulty: intermediate
excerpt: Read and write text files in Java with the modern Files and Path API, process big files line by line, append data, list folders, and handle CSV files safely.
---

Programs often need to save data that survives after they close: reports, logs, settings, CSV exports. Java's modern `java.nio.file` API (`Path` and `Files`) makes file handling short and safe.

## Paths

A `Path` represents a file or folder location:

```java
import java.nio.file.Path;

Path file = Path.of("data", "students.csv");    // data/students.csv (works on every OS)
System.out.println(file.getFileName());          // students.csv
System.out.println(file.toAbsolutePath());       // full path on your computer
```

Relative paths are resolved from the folder where you run the program.

## Writing and reading a whole file

```java
import java.io.IOException;
import java.nio.file.*;
import java.util.List;

public class Notes {
    public static void main(String[] args) throws IOException {
        Path path = Path.of("notes.txt");

        Files.writeString(path, "Learn Java\nPractise daily\n");          // create or overwrite
        Files.writeString(path, "Build projects\n", StandardOpenOption.APPEND);  // add to the end

        String all = Files.readString(path);                              // whole file as one String
        System.out.print(all);

        List<String> lines = Files.readAllLines(path);                    // one String per line
        System.out.println(lines.size() + " lines");                      // 3 lines

        Files.write(path, List.of("one", "two", "three"));                // write a list of lines
        System.out.println(Files.exists(path) + " " + Files.size(path) + " bytes");
    }
}
```

Output:

```text
Learn Java
Practise daily
Build projects
3 lines
true 14 bytes
```

`throws IOException` on `main` is fine for small scripts. In real apps, catch `IOException` and show a helpful message.

## Big files: read line by line

`readAllLines` loads everything into memory. For a 2 GB log file, stream it instead:

```java
import java.io.IOException;
import java.nio.file.*;
import java.util.stream.Stream;

public class ErrorCount {
    public static void main(String[] args) {
        try (Stream<String> lines = Files.lines(Path.of("server.log"))) {
            long errors = lines.filter(l -> l.contains("ERROR")).count();
            System.out.println("Errors: " + errors);
        } catch (IOException e) {
            System.out.println("Could not read log: " + e.getMessage());
        }
    }
}
```

`Files.lines` reads lazily, one line at a time, so memory stays tiny. Put it in try-with-resources so the file is closed.

## BufferedReader and BufferedWriter

The classic approach, still common in older code and useful for fine control:

```java
import java.io.*;
import java.nio.file.*;

public class CopyUpper {
    public static void main(String[] args) throws IOException {
        Files.writeString(Path.of("in.txt"), "hello\nworld\n");
        try (BufferedReader in = Files.newBufferedReader(Path.of("in.txt"));
             BufferedWriter out = Files.newBufferedWriter(Path.of("out.txt"))) {
            String line;
            while ((line = in.readLine()) != null) {     // null = end of file
                out.write(line.toUpperCase());
                out.newLine();
            }
        }
        System.out.print(Files.readString(Path.of("out.txt")));   // HELLO WORLD on two lines
    }
}
```

## Working with a CSV file

```java
import java.io.IOException;
import java.nio.file.*;
import java.util.*;

public class CsvReport {
    record Sale(String product, int qty, double price) {}

    public static void main(String[] args) throws IOException {
        Path csv = Path.of("sales.csv");
        Files.write(csv, List.of("product,qty,price", "Pen,10,15.5", "Book,3,220", "Bag,1,1499"));

        List<Sale> sales = new ArrayList<>();
        for (String line : Files.readAllLines(csv).subList(1, 4)) {     // skip the header
            String[] p = line.split(",");
            sales.add(new Sale(p[0], Integer.parseInt(p[1]), Double.parseDouble(p[2])));
        }
        double total = sales.stream().mapToDouble(s -> s.qty() * s.price()).sum();
        System.out.printf("Total sales: ₹%.2f%n", total);    // Total sales: ₹2314.00
    }
}
```

Real CSV files can contain commas inside quotes, so for production use a library like **OpenCSV** or **Apache Commons CSV**.

## Folders

```java
Path dir = Path.of("reports");
Files.createDirectories(dir);                       // creates parent folders too, no error if it exists
Files.writeString(dir.resolve("may.txt"), "Report");
try (var files = Files.list(dir)) {
    files.forEach(f -> System.out.println(f.getFileName()));
}
Files.copy(dir.resolve("may.txt"), dir.resolve("may-backup.txt"), StandardCopyOption.REPLACE_EXISTING);
Files.deleteIfExists(dir.resolve("may-backup.txt"));
```

::tip Always specify the character encoding when files may contain Hindi, emojis or ₹: `Files.readString(path, StandardCharsets.UTF_8)`. The `Files` methods already default to UTF-8, but older `FileReader` code may not.

## Common mistakes

- Forgetting the file path is relative to where you **run** the program, not where the `.java` file is.
- Reading huge files with `readAllLines` (OutOfMemoryError).
- Not closing streams: use try-with-resources.
- Overwriting a file when you meant to append: pass `StandardOpenOption.APPEND`.

## Exercise

1. Write a program that saves 5 to-do items to `todo.txt` and then reads and numbers them.
2. Count the words in a text file.
3. Read `marks.csv` (name,marks) and write `result.csv` with an extra Pass/Fail column.
4. Append a timestamped line (`LocalDateTime.now()`) to `log.txt` every time the program runs.

::hint Hint for 2 | Read the whole file, then `text.trim().split("\\s+").length`. `\\s+` splits on any run of spaces or new lines.

## Interview corner

- **`Files.readAllLines` vs `Files.lines`?** The first loads everything into a List; the second returns a lazy Stream, better for large files.
- **Why use buffered streams?** They read/write in chunks, reducing slow disk operations.
- **What is serialization?** Converting an object to bytes (e.g. `ObjectOutputStream`) to save or send it. Modern apps usually prefer JSON.

## Summary

- `Path.of(...)` points to files; `Files` reads, writes, copies and deletes.
- `writeString`/`readString` for small files; `Files.lines` in try-with-resources for big ones.
- Append with `StandardOpenOption.APPEND`; create folders with `createDirectories`.

Next: put everything together in a project.
