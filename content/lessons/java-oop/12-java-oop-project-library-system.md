---
title: "Java OOP Project: Library Management System"
slug: java-oop-project-library-system
subject: java-oop
lesson: 12
difficulty: advanced
excerpt: Design and build a Library Management System in Java using classes, encapsulation, inheritance, interfaces, enums, records, custom exceptions and polymorphic fine rules.
---

"Design a library management system" is one of the most common Low-Level Design (LLD) interview questions. Let's build a working version that uses everything from this course.

## Requirements

- The library has **books** (with copies) and **members**.
- Members can **borrow** and **return** books.
- **Students** can borrow up to 3 books for 14 days; **faculty** up to 10 books for 30 days.
- Late returns are **fined**: ₹5/day for students, ₹2/day for faculty.
- You can search books by title or author.

## Step 1: Find the classes

| Noun | Becomes |
|---|---|
| Book | `record Book(isbn, title, author)` |
| Member, Student, Faculty | abstract class `Member` + subclasses (different rules → polymorphism) |
| Loan | a class linking a member, a book and dates |
| Library | the service that enforces the rules |
| Errors | `LibraryException` |

## Step 2: The full program

```java
import java.time.LocalDate;
import java.time.temporal.ChronoUnit;
import java.util.*;

public class LibraryApp {

    // ---------- domain ----------
    record Book(String isbn, String title, String author) { }

    static class LibraryException extends RuntimeException {
        LibraryException(String message) { super(message); }
    }

    abstract static class Member {
        private final String id;
        private final String name;

        Member(String id, String name) { this.id = id; this.name = name; }

        String id() { return id; }
        String name() { return name; }

        abstract int maxBooks();
        abstract int loanDays();
        abstract int finePerDay();

        @Override public String toString() { return name + " (" + getClass().getSimpleName() + ")"; }
    }

    static class Student extends Member {
        Student(String id, String name) { super(id, name); }
        int maxBooks() { return 3; }
        int loanDays() { return 14; }
        int finePerDay() { return 5; }
    }

    static class Faculty extends Member {
        Faculty(String id, String name) { super(id, name); }
        int maxBooks() { return 10; }
        int loanDays() { return 30; }
        int finePerDay() { return 2; }
    }

    static class Loan {
        final Member member;
        final Book book;
        final LocalDate due;

        Loan(Member member, Book book, LocalDate borrowedOn) {
            this.member = member;
            this.book = book;
            this.due = borrowedOn.plusDays(member.loanDays());     // polymorphic rule
        }

        int fine(LocalDate returnedOn) {
            long late = ChronoUnit.DAYS.between(due, returnedOn);
            return late > 0 ? (int) late * member.finePerDay() : 0; // polymorphic rule
        }
    }

    // ---------- service ----------
    static class Library {
        private final Map<String, Book> books = new HashMap<>();
        private final Map<String, Integer> copies = new HashMap<>();
        private final List<Loan> loans = new ArrayList<>();

        void addBook(Book b, int count) {
            books.put(b.isbn(), b);
            copies.merge(b.isbn(), count, Integer::sum);
        }

        int available(String isbn) { return copies.getOrDefault(isbn, 0); }

        List<Book> search(String text) {
            String q = text.toLowerCase();
            return books.values().stream()
                    .filter(b -> b.title().toLowerCase().contains(q) || b.author().toLowerCase().contains(q))
                    .sorted(Comparator.comparing(Book::title))
                    .toList();
        }

        Loan borrow(Member m, String isbn, LocalDate today) {
            Book book = Optional.ofNullable(books.get(isbn))
                    .orElseThrow(() -> new LibraryException("No book with ISBN " + isbn));
            if (available(isbn) == 0) throw new LibraryException("'" + book.title() + "' is not available");
            long current = loans.stream().filter(l -> l.member == m).count();
            if (current >= m.maxBooks()) throw new LibraryException(m.name() + " has reached the limit of " + m.maxBooks());
            copies.merge(isbn, -1, Integer::sum);
            Loan loan = new Loan(m, book, today);
            loans.add(loan);
            return loan;
        }

        int giveBack(Member m, String isbn, LocalDate today) {
            Loan loan = loans.stream()
                    .filter(l -> l.member == m && l.book.isbn().equals(isbn))
                    .findFirst()
                    .orElseThrow(() -> new LibraryException(m.name() + " didn't borrow " + isbn));
            loans.remove(loan);
            copies.merge(isbn, 1, Integer::sum);
            return loan.fine(today);
        }
    }

    // ---------- demo ----------
    public static void main(String[] args) {
        Library lib = new Library();
        lib.addBook(new Book("111", "Wings of Fire", "A. P. J. Abdul Kalam"), 2);
        lib.addBook(new Book("222", "Clean Code", "Robert C. Martin"), 1);
        lib.addBook(new Book("333", "Head First Java", "Kathy Sierra"), 1);

        Member asha = new Student("S1", "Asha");
        Member prof = new Faculty("F1", "Dr. Rao");
        LocalDate day1 = LocalDate.of(2026, 1, 1);

        System.out.println(lib.search("java"));
        Loan l1 = lib.borrow(asha, "222", day1);
        System.out.println(asha + " borrowed " + l1.book.title() + ", due " + l1.due);

        try {
            lib.borrow(prof, "222", day1);
        } catch (LibraryException e) {
            System.out.println("Error: " + e.getMessage());
        }

        Loan l2 = lib.borrow(prof, "111", day1);
        System.out.println(prof + " borrowed " + l2.book.title() + ", due " + l2.due);

        int fineAsha = lib.giveBack(asha, "222", day1.plusDays(20));   // 6 days late
        int fineProf = lib.giveBack(prof, "111", day1.plusDays(20));   // on time
        System.out.println("Fines: Asha ₹" + fineAsha + ", Dr. Rao ₹" + fineProf);
        System.out.println("Clean Code copies now: " + lib.available("222"));
    }
}
```

Output:

```text
[Book[isbn=333, title=Head First Java, author=Kathy Sierra]]
Asha (Student) borrowed Clean Code, due 2026-01-15
Error: 'Clean Code' is not available
Dr. Rao (Faculty) borrowed Wings of Fire, due 2026-01-31
Fines: Asha ₹30, Dr. Rao ₹0
Clean Code copies now: 1
```

## Where each OOP idea appears

- **Encapsulation:** `Library` keeps its maps private; you can only change them through `addBook`, `borrow`, `giveBack`, which enforce the rules.
- **Inheritance + abstraction:** `Member` is abstract; `Student` and `Faculty` fill in the rules.
- **Polymorphism:** `Loan` calls `member.loanDays()` and `member.finePerDay()` without knowing the member type. Adding `Guest` members means adding one class.
- **Records:** `Book` is immutable data with free equals/hashCode/toString.
- **Custom exception:** `LibraryException` carries clear error messages.
- **Collections and streams:** maps for stock, a list of loans, streams for search.

## Extend it (great for your portfolio)

1. **Reservations:** if a book is unavailable, let a member join a queue (use `ArrayDeque`); notify the next member when it's returned (Observer pattern).
2. **Membership expiry:** add an expiry date; expired members can't borrow.
3. **Persistence:** save books and loans to CSV files (Java course lesson 15), or later to a database with JPA.
4. **Menu:** add a Scanner-driven console menu.
5. **REST API:** after the Spring Boot course, expose `/books` and `/loans` endpoints.

::hint Hint for 1 | Keep `Map<String, Deque<Member>> waitlist`. In `giveBack`, if the waitlist for that ISBN isn't empty, `poll()` the next member and print a notification.

::tip In an LLD interview, talk before you code: list requirements, identify classes and relationships, then discuss how your design handles a new requirement ("what if we add e-books?"). That conversation is what's being judged.

## What's next?

- **Servlet & JSP**: put Java on the web.
- **JPA & Hibernate**: store these objects in a real database.
- **Spring & Spring Boot**: build production-ready back-ends using all these OOP ideas.

## Summary

- You designed classes from requirements and built a working system.
- Polymorphic rules (`maxBooks`, `loanDays`, `finePerDay`) make new member types easy to add.
- Encapsulated services enforce business rules in one place.
