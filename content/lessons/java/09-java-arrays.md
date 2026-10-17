---
title: Java Arrays
slug: java-arrays
subject: java
lesson: 9
difficulty: beginner
excerpt: Store many values in one variable with Java arrays, loop over them, find max and average, use 2D arrays for grids, and save time with the Arrays utility class.
---

Imagine storing marks for 60 students in 60 separate variables. Impossible to manage! An **array** stores many values of the same type under one name, in numbered slots.

## Creating arrays

```java
int[] marks = {78, 92, 65, 88, 71};       // values known up front
String[] cities = new String[3];          // 3 empty slots: null, null, null
double[] prices = new double[4];          // 4 slots, all 0.0
cities[0] = "Delhi";
cities[1] = "Mumbai";
cities[2] = "Chennai";
```

- Indexes start at **0**; the last index is `length - 1`.
- `marks.length` (no brackets: it's a field, not a method) gives the size.
- The size is **fixed** once created. Need a growing list? Use `ArrayList` (Collections lesson).
- New arrays get default values: `0`, `0.0`, `false`, or `null` for objects.

```text
index:   0    1    2    3    4
marks: [ 78 | 92 | 65 | 88 | 71 ]
```

## Reading and looping

```java
public class Marks {
    public static void main(String[] args) {
        int[] marks = {78, 92, 65, 88, 71};
        System.out.println("First: " + marks[0] + ", last: " + marks[marks.length - 1]);

        int total = 0, best = marks[0];
        for (int i = 0; i < marks.length; i++) {
            total += marks[i];
            if (marks[i] > best) best = marks[i];
        }
        System.out.println("Total " + total + ", best " + best + ", average " + (double) total / marks.length);
    }
}
```

```text
First: 78, last: 71
Total 394, best 92, average 78.8
```

Accessing `marks[5]` here throws **`ArrayIndexOutOfBoundsException`**, the most common array bug.

## The Arrays helper class

`java.util.Arrays` has ready-made tools:

```java
import java.util.Arrays;

public class ArraysDemo {
    public static void main(String[] args) {
        int[] nums = {42, 7, 19, 3, 25};
        System.out.println(Arrays.toString(nums));        // [42, 7, 19, 3, 25]
        Arrays.sort(nums);
        System.out.println(Arrays.toString(nums));        // [3, 7, 19, 25, 42]
        System.out.println(Arrays.binarySearch(nums, 19)); // 2 (array must be sorted)
        int[] copy = Arrays.copyOf(nums, 3);
        System.out.println(Arrays.toString(copy));        // [3, 7, 19]
        int[] filled = new int[4];
        Arrays.fill(filled, 9);
        System.out.println(Arrays.toString(filled));      // [9, 9, 9, 9]
        System.out.println(Arrays.stream(nums).sum());    // 96
    }
}
```

::tip Printing an array directly (`System.out.println(nums)`) shows something like `[I@1b6d3586`, the type and a hash. Use `Arrays.toString(nums)`.

## Arrays are references

An array variable holds a **reference**. Assigning it doesn't copy the array; both names point to the same one:

```java
int[] a = {1, 2, 3};
int[] b = a;          // same array, two names
b[0] = 99;
System.out.println(a[0]);    // 99!
int[] c = a.clone();  // a real copy
c[1] = 50;
System.out.println(a[1]);    // 2
```

Comparing with `a == c` checks whether they're the same array object. To compare contents, use `Arrays.equals(a, c)`.

## Reversing an array in place

The classic two-pointer technique (see the DSA course for more):

```java
int[] arr = {1, 2, 3, 4, 5};
for (int l = 0, r = arr.length - 1; l < r; l++, r--) {
    int tmp = arr[l];
    arr[l] = arr[r];
    arr[r] = tmp;
}
// arr is now [5, 4, 3, 2, 1]
```

## 2D arrays: grids and tables

A 2D array is an array of arrays: rows and columns.

```java
public class Grid {
    public static void main(String[] args) {
        int[][] scores = {
            {80, 75, 90},    // student 0
            {60, 85, 70},    // student 1
        };
        System.out.println(scores[1][2]);          // 70: row 1, column 2
        for (int r = 0; r < scores.length; r++) {
            int sum = 0;
            for (int c = 0; c < scores[r].length; c++) {
                sum += scores[r][c];
            }
            System.out.println("Student " + r + " total: " + sum);
        }
    }
}
```

```text
70
Student 0 total: 245
Student 1 total: 215
```

Use 2D arrays for game boards, seating charts, matrices and images.

## Arrays and methods

You can pass arrays into methods and return them:

```java
static int max(int[] values) {
    int best = values[0];
    for (int v : values) if (v > best) best = v;
    return best;
}
```

Since the method gets a reference, it can change the caller's array.

## Common mistakes

- Using `i <= arr.length` in loops (off by one).
- Forgetting arrays have fixed size.
- `arr.length()` with brackets (that's for Strings). Arrays use `arr.length`.
- Assuming `b = a` copies the array.

## Exercise

1. Find the second largest number in an array.
2. Count how many numbers in an array are even.
3. Rotate an array left by one position: `[1,2,3,4]` → `[2,3,4,1]`.
4. Add two 3×3 matrices and print the result.
5. Check if an array is sorted in ascending order.

::hint Hint for 1 | Keep two variables, `first` and `second`. When you find a bigger number, the old `first` becomes `second`.
::hint Hint for 3 | Save `arr[0]`, shift every element one place left, then put the saved value at the end.

## Interview corner

- **Default value of an int array element?** 0. Of a String array element? `null`.
- **Can you change an array's size?** No. Create a new one (`Arrays.copyOf`) or use `ArrayList`.
- **Where are arrays stored?** On the heap; the variable holds a reference.
- **Time to access `arr[i]`?** O(1): Java computes the memory address directly.

## Summary

- Arrays hold a fixed number of same-type values, indexed from 0.
- Loop with `for` (index needed) or for-each.
- `Arrays.toString`, `sort`, `copyOf`, `fill`, `equals` save time.
- Array variables are references; 2D arrays are arrays of arrays.

Next: methods, to organise code into reusable pieces.
