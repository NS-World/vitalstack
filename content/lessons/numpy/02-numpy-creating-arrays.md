---
title: Creating NumPy Arrays
slug: numpy-creating-arrays
subject: numpy
lesson: 2
difficulty: beginner
excerpt: Make arrays from lists, ranges, zeros, ones and random numbers, and read their shape, size and type.
---

Most of the time you won't type numbers by hand. NumPy can build arrays of any size in one line. This lesson shows the ways you'll use every day, and how to read an array's **shape**.

## From a list (1-D and 2-D)

A list gives a **1-D** array (one row). A list of lists gives a **2-D** array (a table of rows and columns):

```python
import numpy as np

prices = np.array([120, 80, 45])
table = np.array([[1, 2, 3],
                  [4, 5, 6]])

print(prices.shape, prices.ndim)
print(table.shape, table.ndim)
print(table.size)
```

Output:

```text
(3,) 1
(2, 3) 2
6
```

- `shape` is the size along each dimension: `(2, 3)` means 2 rows and 3 columns.
- `ndim` is the number of dimensions.
- `size` is the total number of elements: 2 × 3 = 6.

## Ranges: arange and linspace

`np.arange(start, stop, step)` works like Python's `range`, but returns an array. The stop value is not included:

```python
import numpy as np

print(np.arange(5))
print(np.arange(2, 11, 2))
print(np.linspace(0, 1, 5))
```

Output:

```text
[0 1 2 3 4]
[ 2  4  6  8 10]
[0.   0.25 0.5  0.75 1.  ]
```

`np.linspace(start, stop, num)` is different: you say *how many* numbers you want, and NumPy spaces them evenly, including the stop value. It's perfect for drawing smooth charts.

## Zeros, ones and a constant

Useful as a starting point that you fill in later:

```python
import numpy as np

print(np.zeros(4))
print(np.ones((2, 3)))
print(np.full((2, 2), 7))
```

Output:

```text
[0. 0. 0. 0.]
[[1. 1. 1.]
 [1. 1. 1.]]
[[7 7]
 [7 7]]
```

For 2-D, pass the shape as a tuple: `(2, 3)`, with brackets.

## Random numbers

Simulations, games and machine learning need random data. Create a generator, give it a **seed** so you get the same numbers every run, then ask for numbers:

```python
import numpy as np

rng = np.random.default_rng(42)
print(rng.integers(1, 7, size=5))
print(rng.random(3).round(2))
```

Output:

```text
[1 5 4 3 3]
[0.7  0.09 0.98]
```

`rng.integers(1, 7, size=5)` rolls a dice 5 times (1 to 6; the 7 is excluded). `rng.random(3)` gives 3 decimals between 0 and 1.

::tip Always set a seed when you share code or train a model. With the same seed, everyone gets the same "random" numbers, so results can be compared.

## Choosing the data type

You can pick the type yourself with `dtype`, or convert later with `astype`:

```python
import numpy as np

a = np.array([1, 2, 3], dtype=float)
b = np.array([3.7, 1.2, 9.9]).astype(int)
print(a)
print(b)
```

Output:

```text
[1. 2. 3.]
[3 1 9]
```

`astype(int)` cuts off the decimal part; it doesn't round. Use `np.round(x)` first if you want rounding.

::hint Quick check | What is the shape of `np.zeros((3, 4))`? It has 3 rows and 4 columns, so the shape is `(3, 4)` and the size is 12.

## Practice

1. Make an array of the even numbers from 0 to 20.
2. Make a 3 × 3 array filled with 5.
3. Simulate 10 dice rolls with a seed of 7 and print them.

## Summary

| You want | Use |
|---|---|
| An array from data | `np.array(list)` |
| A range of numbers | `np.arange(start, stop, step)` |
| N evenly spaced numbers | `np.linspace(start, stop, n)` |
| An empty starting array | `np.zeros(shape)` / `np.ones(shape)` |
| Random numbers | `np.random.default_rng(seed)` |
