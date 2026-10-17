---
title: Java ArrayList and Collections
slug: java-arraylist-and-collections
subject: java
lesson: 11
difficulty: intermediate
excerpt: Use ArrayList, LinkedList, HashSet and TreeSet, understand how an ArrayList grows, generics, wrapper classes, autoboxing, iterating safely and the Collections utility class.
---

Arrays have a fixed size. Real apps need lists that grow and shrink: a shopping cart, a list of students, search results. Java's **Collections Framework** provides ready-made data structures for this.

## The big picture

```text
                 Collection
          ┌──────────┼──────────┐
         List        Set        Queue
   ArrayList     HashSet      ArrayDeque
   LinkedList    LinkedHashSet PriorityQueue
                 TreeSet
       Map (separate): HashMap, LinkedHashMap, TreeMap
```

- **List**: ordered, allows duplicates, access by index.
- **Set**: no duplicates.
- **Queue/Deque**: add at one end, remove from the other.
- **Map**: key → value pairs (next lesson).

## ArrayList: the list you'll use most

```java
import java.util.ArrayList;
import java.util.List;

public class Cart {
    public static void main(String[] args) {
        List<String> cart = new ArrayList<>();
        cart.add("Notebook");
        cart.add("Pen");
        cart.add("Backpack");
        cart.add(1, "Pencil");                 // insert at index 1
        System.out.println(cart);              // [Notebook, Pencil, Pen, Backpack]
        System.out.println(cart.get(2));       // Pen
        System.out.println(cart.size());       // 4
        System.out.println(cart.contains("Pen"));   // true
        cart.set(0, "Diary");                  // replace
        cart.remove("Pencil");                 // remove by value
        cart.remove(0);                        // remove by index
        System.out.println(cart);              // [Pen, Backpack]
        for (String item : cart) {
            System.out.println("- " + item);
        }
    }
}
```

- `List<String>` uses **generics**: the list only accepts Strings, checked at compile time.
- `new ArrayList<>()`: the empty `<>` (diamond) lets Java infer the type.
- Declaring the variable as `List` (the interface) and creating an `ArrayList` is good practice: you can switch implementation later.

## How ArrayList grows

Inside, an ArrayList is a normal array. When it's full, Java creates a bigger array (about 1.5×) and copies everything over. Watch it:

::viz arraylist-grow {"n": 12}

That's why `add()` at the end is fast **on average** (O(1)), while `add(0, x)` or `remove(0)` shifts every element (O(n)).

## Wrapper classes and autoboxing

Collections can't hold primitives. Use the **wrapper** classes: `Integer`, `Double`, `Boolean`, `Character`, `Long`…

```java
List<Integer> nums = new ArrayList<>();
nums.add(5);             // autoboxing: int 5 → Integer
int first = nums.get(0); // unboxing: Integer → int
```

::tip Trap: `list.remove(1)` on a `List<Integer>` removes the element at **index** 1. To remove the value 1, write `list.remove(Integer.valueOf(1))`.

Another trap: compare `Integer` objects with `.equals()`, not `==`. `==` only works by accident for small values (-128 to 127 are cached).

## LinkedList

A `LinkedList` stores nodes that point to each other. Adding/removing at the **ends** is O(1), but `get(i)` must walk the chain: O(n). In practice, `ArrayList` is faster for most uses; reach for `ArrayDeque` when you need a queue or stack.

| Operation | ArrayList | LinkedList |
|---|---|---|
| `get(i)` | O(1) | O(n) |
| add at end | O(1) amortised | O(1) |
| add/remove at front | O(n) | O(1) |
| memory | compact | extra pointers per node |

## Sets: no duplicates

```java
import java.util.*;

public class Tags {
    public static void main(String[] args) {
        List<String> raw = List.of("java", "spring", "java", "sql", "spring");
        Set<String> unique = new HashSet<>(raw);           // fast, no order guaranteed
        Set<String> inOrder = new LinkedHashSet<>(raw);    // keeps insertion order
        Set<String> sorted = new TreeSet<>(raw);           // sorted
        System.out.println(unique.size());     // 3
        System.out.println(inOrder);           // [java, spring, sql]
        System.out.println(sorted);            // [java, spring, sql]
        System.out.println(unique.add("java"));   // false: already there
    }
}
```

`HashSet` lookups (`contains`) are O(1) on average, which makes sets perfect for "have I seen this before?" checks.

## Iterating and removing safely

Removing inside a for-each throws `ConcurrentModificationException`. Use `removeIf` or an `Iterator`:

```java
List<Integer> marks = new ArrayList<>(List.of(35, 80, 22, 90, 41));
marks.removeIf(m -> m < 40);               // [80, 90, 41]

Iterator<Integer> it = marks.iterator();
while (it.hasNext()) {
    if (it.next() > 85) it.remove();       // [80, 41]
}
```

## The Collections utility class

```java
List<Integer> nums = new ArrayList<>(List.of(5, 2, 9, 1));
Collections.sort(nums);                    // [1, 2, 5, 9]
Collections.reverse(nums);                 // [9, 5, 2, 1]
Collections.shuffle(nums);                 // random order
System.out.println(Collections.max(nums)); // 9
List<String> fixed = Collections.unmodifiableList(new ArrayList<>(List.of("a")));
```

`List.of(...)` creates an **immutable** list: calling `add` on it throws `UnsupportedOperationException`. Wrap it in `new ArrayList<>(...)` when you need to change it.

## Sorting objects

```java
List<String> names = new ArrayList<>(List.of("Ravi", "Anu", "Mohammed", "Zoya"));
names.sort(Comparator.comparing(String::length));            // by length
names.sort(Comparator.reverseOrder());                       // Z to A
```

You'll see `Comparator` and lambdas (`m -> m < 40`) in detail in the Streams lesson.

## Common mistakes

- `List<int>`: not allowed. Use `List<Integer>`.
- Modifying a `List.of(...)` list.
- Removing inside for-each.
- Using `LinkedList` "because inserts are fast" and then calling `get(i)` in a loop (O(n²)).

## Exercise

1. Store 5 student names in an ArrayList, add one, remove one, and print the list sorted.
2. Remove duplicates from `[3, 1, 3, 2, 1, 5]` while keeping the original order.
3. Find the common elements of two lists using sets (`retainAll`).
4. Remove all names shorter than 4 letters with `removeIf`.

::hint Hint for 2 | `new ArrayList<>(new LinkedHashSet<>(list))` keeps the first occurrence of each value in order.

## Interview corner

- **ArrayList vs LinkedList?** Array-backed vs node-based. ArrayList wins for random access and is usually faster overall.
- **ArrayList vs array?** ArrayList grows dynamically and only holds objects; arrays are fixed-size and can hold primitives.
- **Default capacity of ArrayList?** 10 (allocated on first add); it grows by about 50% when full.
- **HashSet vs TreeSet?** HashSet: O(1) average, no order. TreeSet: O(log n), sorted.
- **What is fail-fast?** Iterators throw ConcurrentModificationException if the collection is changed structurally during iteration (except through the iterator).

## Summary

- Use `List<T> list = new ArrayList<>()` by default; `Set` for uniqueness.
- ArrayList grows by copying into a bigger array; adding at the end is amortised O(1).
- Collections hold objects: wrappers and autoboxing bridge primitives.
- Use `removeIf` or an Iterator to remove while looping.

Next: HashMap, the key-value structure behind caches, counters and lookups.
