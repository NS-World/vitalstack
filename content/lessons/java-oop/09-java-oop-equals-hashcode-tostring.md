---
title: Java equals, hashCode and toString
slug: java-oop-equals-hashcode-tostring
subject: java-oop
lesson: 9
difficulty: intermediate
excerpt: Override the Object methods correctly. Learn why == differs from equals, the equals-hashCode contract, what breaks in HashMap and HashSet when you get it wrong, and a clean toString.
---

Every Java class inherits three important methods from `Object`: `toString()`, `equals()` and `hashCode()`. Their default versions are rarely what you want for your own classes, and getting `equals`/`hashCode` wrong causes some of the sneakiest bugs in Java.

## toString: readable objects

```java
class Book {
    String title; String author;
    Book(String title, String author) { this.title = title; this.author = author; }
}

System.out.println(new Book("Wings of Fire", "A. P. J. Abdul Kalam"));
// Book@6d06d69c   ← class name + hash: useless
```

Override it:

```java
@Override
public String toString() {
    return "Book{title='" + title + "', author='" + author + "'}";
}
// Book{title='Wings of Fire', author='A. P. J. Abdul Kalam'}
```

`toString` is used by `println`, string concatenation, debuggers and logs. Always override it for data classes.

## == vs equals

- `==` asks: are these **the same object** (same address)?
- `equals()` asks: are these objects **meaningfully equal** (same content)?

The default `Object.equals` is just `==`. So two different `Book` objects with the same title and author are **not** equal until you override `equals`. (You saw the same issue with Strings and the string pool.)

## Overriding equals correctly

```java
import java.util.Objects;

class Book {
    private final String isbn;
    private final String title;

    Book(String isbn, String title) { this.isbn = isbn; this.title = title; }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;                          // same object
        if (!(o instanceof Book other)) return false;        // null or different type
        return isbn.equals(other.isbn);                      // compare what defines identity
    }

    @Override
    public int hashCode() {
        return Objects.hash(isbn);                           // same fields as equals!
    }

    @Override
    public String toString() { return title + " [" + isbn + "]"; }
}
```

Note the parameter type is `Object`, not `Book`. Writing `equals(Book o)` **overloads** instead of overriding, and collections will never call it. `@Override` catches that mistake.

## The contract

1. **Reflexive:** `a.equals(a)` is true.
2. **Symmetric:** if `a.equals(b)` then `b.equals(a)`.
3. **Transitive:** if `a.equals(b)` and `b.equals(c)` then `a.equals(c)`.
4. **Consistent:** same result every time if nothing changed.
5. `a.equals(null)` is false.
6. **If `a.equals(b)`, then `a.hashCode() == b.hashCode()`.** (The reverse isn't required: different objects may share a hash.)

## Why hashCode matters: the HashSet bug

`HashMap` and `HashSet` first use `hashCode()` to pick a **bucket**, then `equals()` to compare inside it (revisit the HashMap animation in the Java course). If you override `equals` but **not** `hashCode`, two equal objects usually land in different buckets:

```java
import java.util.*;

public class HashBug {
    static class Point {
        final int x, y;
        Point(int x, int y) { this.x = x; this.y = y; }
        @Override public boolean equals(Object o) {
            return o instanceof Point p && p.x == x && p.y == y;
        }
        // hashCode NOT overridden!
    }

    static class FixedPoint {
        final int x, y;
        FixedPoint(int x, int y) { this.x = x; this.y = y; }
        @Override public boolean equals(Object o) {
            return o instanceof FixedPoint p && p.x == x && p.y == y;
        }
        @Override public int hashCode() { return Objects.hash(x, y); }
    }

    public static void main(String[] args) {
        Set<Point> broken = new HashSet<>();
        broken.add(new Point(1, 2));
        System.out.println(broken.contains(new Point(1, 2)));     // false (almost always)!

        Set<FixedPoint> fixed = new HashSet<>();
        fixed.add(new FixedPoint(1, 2));
        System.out.println(fixed.contains(new FixedPoint(1, 2))); // true
    }
}
```

## Rules of thumb

- **Always override `equals` and `hashCode` together**, using the **same fields**.
- Use fields that **don't change** (an id, ISBN, email). If a field used in `hashCode` changes while the object is in a HashSet, the object gets "lost" in the wrong bucket.
- Use `Objects.equals(a, b)` for null-safe field comparison and `Objects.hash(...)` for hashCode.
- Let your IDE generate them (IntelliJ: Alt+Insert → equals() and hashCode()), or use a **record**, which does it for you.

## Records do it automatically

```java
record Point(int x, int y) { }

Point a = new Point(1, 2), b = new Point(1, 2);
System.out.println(a.equals(b));             // true
System.out.println(a.hashCode() == b.hashCode());   // true
System.out.println(a);                        // Point[x=1, y=2]
```

Records generate `equals`, `hashCode` and `toString` from their components. More in the next lesson.

::tip JPA/Hibernate entities are a special case: their id may be null until saved. Many teams compare entities by id only when it's not null, or by a natural business key. You'll see this in the JPA course.

## Common mistakes

- `equals(Book other)` instead of `equals(Object o)` (overloading, not overriding).
- Overriding `equals` without `hashCode`.
- Using mutable fields in `hashCode`.
- Using `getClass() != o.getClass()` vs `instanceof` inconsistently across a hierarchy (pick one approach).

## Exercise

1. Create an `Employee` class where two employees are equal if they have the same employee id. Add both to a `HashSet` and check its size.
2. Remove the `hashCode` override and test again. What changes?
3. Write a `Money` class (amount in paise + currency) with proper `equals`, `hashCode` and `toString` like `₹12.50`.
4. Rewrite `Money` as a record and compare the amount of code.

::hint Hint for 1 | Two `new Employee(101, "Asha")` objects should give a set of size 1 only when both equals and hashCode use the id.

## Interview corner

- **What's the contract between equals and hashCode?** Equal objects must have equal hash codes. Unequal objects may share a hash code.
- **What happens if you override only equals?** HashMap/HashSet lookups fail for equal objects because they land in different buckets.
- **Can two different objects have the same hashCode?** Yes, that's a collision; equals decides.
- **Why make keys immutable?** If a key's hash changes after insertion, the map can't find it.

## Summary

- Override `toString` for readable output.
- `==` compares references; `equals` compares meaning.
- Override `equals(Object)` and `hashCode()` together using the same immutable fields.
- Records generate all three automatically.

Next: modern Java data types: records, enums and sealed classes.
