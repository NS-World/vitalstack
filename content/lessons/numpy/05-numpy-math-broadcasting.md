---
title: NumPy Maths and Broadcasting
slug: numpy-math-broadcasting
subject: numpy
lesson: 5
difficulty: intermediate
excerpt: Element-wise maths, universal functions, and broadcasting, the rule that lets arrays of different shapes work together.
---

You've seen that `marks + 5` adds 5 to every element. This lesson explains the full set of maths NumPy can do on arrays, and **broadcasting**, the rule that decides how arrays of different shapes are combined.

## Element-wise maths

When two arrays have the same shape, maths is done position by position:

```python
import numpy as np

price = np.array([100, 250, 40])
qty = np.array([2, 1, 5])
print(price * qty)
print((price * qty).sum())
```

Output:

```text
[200 250 200]
650
```

That's a whole shopping bill in two lines: each price times its quantity, then the total.

## Universal functions (ufuncs)

NumPy has fast versions of common maths functions that work on every element:

```python
import numpy as np

x = np.array([1, 4, 9, 16])
print(np.sqrt(x))
print(np.round(np.log10(x), 2))
print(np.abs(np.array([-3, 2, -1])))
```

Output:

```text
[1. 2. 3. 4.]
[0.   0.6  0.95 1.2 ]
[3 2 1]
```

Others you'll use: `np.exp`, `np.sin`, `np.cos`, `np.power`, `np.maximum`, `np.clip`.

## Broadcasting: different shapes

What if the shapes don't match? Adding a single number to an array already worked, because NumPy **stretched** the number to every position. The same idea works for rows and columns. Watch how a row is added to a whole table:

::viz np-broadcast {"row": [10, 20, 30]}

```python
import numpy as np

m = np.array([[1, 2, 3],
              [4, 5, 6],
              [7, 8, 9]])
row = np.array([10, 20, 30])
print(m + row)
```

Output:

```text
[[11 22 33]
 [14 25 36]
 [17 28 39]]
```

**The rule:** compare shapes from the right. Two sizes are compatible when they're equal, or when one of them is 1 (or missing). The size-1 side is stretched.

| Shapes | Works? | Why |
|---|---|---|
| (3, 3) + (3,) | ✅ | the row is stretched down 3 times |
| (3, 3) + (3, 1) | ✅ | the column is stretched across |
| (3, 3) + (2,) | ❌ | 3 and 2 don't match and neither is 1 |

A real example: give each subject its own weight in a final score.

```python
import numpy as np

scores = np.array([[80, 70, 90],
                   [60, 85, 75]])
weights = np.array([0.5, 0.3, 0.2])
print(scores * weights)
print((scores * weights).sum(axis=1))
```

Output:

```text
[[40.  21.  18. ]
 [30.  25.5 15. ]]
[79.  70.5]
```

Each row is one student; `.sum(axis=1)` adds across each row, giving one final score per student.

::hint Error to recognise | `ValueError: operands could not be broadcast together with shapes (3,3) (2,)` means the shapes break the rule above. Print both `.shape` values and check them from the right.

## Normalising data (a machine learning favourite)

Models learn better when every column is on a similar scale. Broadcasting does it in one line:

```python
import numpy as np

data = np.array([[150, 50000],
                 [180, 80000],
                 [165, 65000]])
scaled = (data - data.min(axis=0)) / (data.max(axis=0) - data.min(axis=0))
print(scaled)
```

Output:

```text
[[0.  0. ]
 [1.  1. ]
 [0.5 0.5]]
```

`data.min(axis=0)` gives the minimum of each column, shape (2,); broadcasting subtracts it from every row. Now height and salary are both between 0 and 1.

## Summary

- Same shapes: maths is element by element.
- Ufuncs like `np.sqrt` and `np.log` work on every element, fast.
- Broadcasting stretches size-1 (or missing) dimensions; compare shapes from the right.
- It powers everyday tasks like weighted scores and scaling features.
