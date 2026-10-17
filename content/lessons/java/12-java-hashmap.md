---
title: Java HashMap and Maps
slug: java-hashmap
subject: java
lesson: 12
difficulty: intermediate
excerpt: Store key-value pairs with HashMap, LinkedHashMap and TreeMap, count frequencies with merge and getOrDefault, iterate entries, and see how hashCode picks a bucket.
---

A **Map** stores **key → value** pairs, like a dictionary: word → meaning, roll number → student, product ID → price. Looking up a value by its key is extremely fast. Maps are one of the most used structures in real Java code and in interviews.

## HashMap basics

```java
import java.util.HashMap;
import java.util.Map;

public class Prices {
    public static void main(String[] args) {
        Map<String, Integer> price = new HashMap<>();
        price.put("apple", 120);
        price.put("banana", 40);
        price.put("mango", 150);
        price.put("apple", 130);                     // same key: value replaced

        System.out.println(price.get("mango"));       // 150
        System.out.println(price.get("grapes"));      // null: key not found
        System.out.println(price.getOrDefault("grapes", 0));   // 0
        System.out.println(price.containsKey("banana"));       // true
        System.out.println(price.size());             // 3
        price.remove("banana");
        System.out.println(price.keySet());           // keys (order not guaranteed)
    }
}
```

- Keys are **unique**; `put` with an existing key replaces the value.
- `get` returns `null` for missing keys. Prefer `getOrDefault`.
- `HashMap` allows one `null` key and many `null` values.

## Looping over a map

```java
Map<String, Integer> stock = new HashMap<>(Map.of("pen", 50, "book", 12, "bag", 3));

for (Map.Entry<String, Integer> e : stock.entrySet()) {
    System.out.println(e.getKey() + " → " + e.getValue());
}

stock.forEach((item, qty) -> {
    if (qty < 5) System.out.println("Low stock: " + item);
});
```

Use `entrySet()` when you need both key and value; it avoids a second lookup per key.

## The counting pattern

Counting how often things appear is the most common map use, in real code and interviews:

```java
import java.util.Map;
import java.util.TreeMap;

public class WordCount {
    public static void main(String[] args) {
        String text = "java is fun and java is powerful and java is everywhere";
        Map<String, Integer> count = new TreeMap<>();      // sorted by key
        for (String w : text.split(" ")) {
            count.merge(w, 1, Integer::sum);               // add 1, or start at 1
        }
        System.out.println(count);
    }
}
```

```text
{and=2, everywhere=1, fun=1, is=3, java=3, powerful=1}
```

`merge(key, 1, Integer::sum)` means "if the key is new, store 1; otherwise add 1 to the old value". The older style is `count.put(w, count.getOrDefault(w, 0) + 1);`.

## Grouping with computeIfAbsent

```java
Map<Character, List<String>> byLetter = new TreeMap<>();
for (String name : List.of("Asha", "Arjun", "Bala", "Chitra", "Bhavna")) {
    byLetter.computeIfAbsent(name.charAt(0), k -> new ArrayList<>()).add(name);
}
// {A=[Asha, Arjun], B=[Bala, Bhavna], C=[Chitra]}
```

## How HashMap works inside

A HashMap is an array of **buckets**. To store a key, Java:

1. Calls `key.hashCode()` to get a number.
2. Mixes the bits and uses it to choose a bucket index.
3. Stores the entry there. Two keys in the same bucket (a **collision**) are chained in a list (or a balanced tree if the bucket gets long, since Java 8).

When the map is 75% full (the **load factor**), it doubles its buckets and redistributes entries. Watch real `hashCode()` values pick buckets, including the famous collision `"Aa"` vs `"BB"`:

::viz hashmap-buckets

On average `get` and `put` are **O(1)**. That's why maps power caches, indexes and lookups.

::tip If you use your own class as a key, you **must** override both `equals()` and `hashCode()` consistently. Otherwise two "equal" keys land in different buckets and `get` returns null. The OOP course covers this.

## HashMap vs LinkedHashMap vs TreeMap

| Map | Order | get/put |
|---|---|---|
| `HashMap` | no guaranteed order | O(1) average |
| `LinkedHashMap` | insertion order (or access order for LRU caches) | O(1) average |
| `TreeMap` | sorted by key | O(log n) |

`TreeMap` also has handy navigation: `firstKey()`, `lastKey()`, `floorKey(x)`, `ceilingKey(x)`, `headMap`, `tailMap`.

## Immutable maps

```java
Map<String, String> capitals = Map.of("India", "New Delhi", "Japan", "Tokyo");
// capitals.put("France", "Paris");   // UnsupportedOperationException
```

## Common mistakes

- Expecting HashMap to keep insertion order. Use `LinkedHashMap`.
- Calling `get(key)` and unboxing a `null` to `int`: `int n = map.get("x");` throws NullPointerException if missing.
- Mutable objects as keys: if a key's fields change after insertion, its hash changes and it gets "lost".
- Modifying a map while iterating with for-each.

## Exercise

1. Count the frequency of each character in "programming".
2. Find the first non-repeating character in a string (hint: LinkedHashMap).
3. Given a list of (name, city) pairs, group names by city.
4. Two Sum: given an array and a target, return the indexes of two numbers that add up to the target, in O(n) using a map.

::hint Hint for 2 | Count with a LinkedHashMap, then loop over its entries and return the first with count 1.
::hint Hint for 4 | For each number x, check if `target - x` is already in the map (value → index). If not, store x.

## Interview corner

- **How does HashMap work internally?** Array of buckets; hashCode → bucket index; collisions chained (treeified after 8 entries per bucket); resize at 0.75 load factor.
- **HashMap vs Hashtable?** Hashtable is old and synchronized, and allows no null keys/values. Use HashMap, or ConcurrentHashMap for threads.
- **Why must equals and hashCode be consistent?** Equal objects must have equal hash codes, or lookups fail.
- **Time complexity of get?** O(1) average; O(log n) worst case in a treeified bucket since Java 8.

## Summary

- Maps store unique keys with values; HashMap gives O(1) average lookups.
- `getOrDefault`, `merge`, `computeIfAbsent` make counting and grouping easy.
- LinkedHashMap keeps insertion order; TreeMap keeps keys sorted.
- Keys need consistent `equals` and `hashCode`.

Next: exceptions, how Java handles errors without crashing.
