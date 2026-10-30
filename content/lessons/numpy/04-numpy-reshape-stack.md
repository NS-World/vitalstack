---
title: Reshaping and Joining Arrays
slug: numpy-reshape-stack
subject: numpy
lesson: 4
difficulty: intermediate
excerpt: Change an array's shape with reshape, flatten and transpose, and join arrays with concatenate, vstack and hstack.
---

Data rarely arrives in the shape you need. A model may want a column instead of a row, or you may need to glue two months of data together. This lesson covers the shape tools.

## reshape: same numbers, new shape

`reshape(rows, cols)` rearranges the elements without changing them. The total must stay the same: 12 elements can be 3 × 4, 4 × 3, 2 × 6 or 6 × 2, but not 5 × 2.

```python
import numpy as np

a = np.arange(1, 13)
print(a.reshape(3, 4))
print(a.reshape(2, -1))
```

Output:

```text
[[ 1  2  3  4]
 [ 5  6  7  8]
 [ 9 10 11 12]]
[[ 1  2  3  4  5  6]
 [ 7  8  9 10 11 12]]
```

`-1` means "work it out for me": with 12 elements and 2 rows, NumPy computes 6 columns.

::viz np-reshape {"rows": 2, "cols": 6}

A very common case in machine learning: turning a 1-D list of values into one column, because scikit-learn expects a table of features:

```python
import numpy as np

sizes = np.array([650, 800, 1200])
X = sizes.reshape(-1, 1)
print(X)
print(X.shape)
```

Output:

```text
[[ 650]
 [ 800]
 [1200]]
(3, 1)
```

## Flatten and transpose

```python
import numpy as np

m = np.array([[1, 2, 3],
              [4, 5, 6]])
print(m.flatten())
print(m.T)
print(m.T.shape)
```

Output:

```text
[1 2 3 4 5 6]
[[1 4]
 [2 5]
 [3 6]]
(3, 2)
```

- `flatten()` turns any shape back into one long row.
- `.T` (transpose) swaps rows and columns: row 0 becomes column 0.

## Joining arrays

```python
import numpy as np

jan = np.array([[10, 12], [14, 9]])
feb = np.array([[11, 15], [13, 8]])

print(np.vstack([jan, feb]))
print(np.hstack([jan, feb]))
```

Output:

```text
[[10 12]
 [14  9]
 [11 15]
 [13  8]]
[[10 12 11 15]
 [14  9 13  8]]
```

- `vstack` (vertical) puts arrays **on top of each other**: more rows.
- `hstack` (horizontal) puts them **side by side**: more columns.
- `np.concatenate([a, b], axis=0)` is the general version: `axis=0` stacks rows, `axis=1` stacks columns.

::hint Remember | `axis=0` goes down the rows, `axis=1` goes across the columns. You'll meet `axis` again with sums and averages.

## Splitting

```python
import numpy as np

a = np.arange(10)
first, second = np.split(a, 2)
print(first, second)
```

Output:

```text
[0 1 2 3 4] [5 6 7 8 9]
```

## Practice

1. Reshape `np.arange(24)` into 4 rows and let NumPy find the columns.
2. Stack the arrays `[1, 2, 3]` and `[4, 5, 6]` into a 2 × 3 table.
3. What is the shape of the transpose of a (5, 2) array?

## Summary

| Task | Code |
|---|---|
| New shape, same data | `a.reshape(r, c)` (use `-1` for one size) |
| One long row | `a.flatten()` |
| Swap rows and columns | `a.T` |
| Stack as rows / columns | `np.vstack`, `np.hstack` |
| Split into parts | `np.split(a, n)` |
