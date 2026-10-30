---
title: NumPy Introduction
slug: numpy-introduction
subject: numpy
lesson: 1
difficulty: beginner
excerpt: What NumPy is, why every data science and AI library is built on it, how to install it, and your first array.
---

**NumPy** (Numerical Python) is the library that makes Python fast at maths. A Python list can hold numbers, but it handles them one by one. A NumPy **array** holds numbers in one tight block of memory and works on all of them at once.

That's why almost every data and AI tool is built on top of NumPy: Pandas, Matplotlib, scikit-learn, TensorFlow and PyTorch all speak "array".

## Install and import

NumPy is already included in Google Colab, Anaconda and Jupyter. On your own computer, install it once with pip:

```bash
pip install numpy
```

Then import it at the top of your program. Everyone uses the short name `np`:

```python
import numpy as np

print(np.__version__)
```

Output (your version may be newer):

```text
2.2.6
```

## Your first array

```python
import numpy as np

marks = np.array([72, 85, 64, 90, 58])
print(marks)
print(type(marks))
```

Output:

```text
[72 85 64 90 58]
<class 'numpy.ndarray'>
```

`ndarray` means "n-dimensional array". It prints without commas, which is an easy way to tell an array from a list.

## Lists vs arrays: maths on everything at once

Suppose every student gets 5 bonus marks. With a list you need a loop:

```python
marks_list = [72, 85, 64, 90, 58]
with_bonus = [m + 5 for m in marks_list]
print(with_bonus)
```

Output:

```text
[77, 90, 69, 95, 63]
```

With NumPy you write the maths once and it applies to every element. This is called **vectorisation**:

```python
import numpy as np

marks = np.array([72, 85, 64, 90, 58])
print(marks + 5)
print(marks * 2)
print(marks > 70)
```

Output:

```text
[77 90 69 95 63]
[144 170 128 180 116]
[ True  True False  True False]
```

Careful: with a plain list, `marks_list * 2` does something completely different. It repeats the list instead of doubling the numbers:

```python
print([72, 85] * 2)
```

Output:

```text
[72, 85, 72, 85]
```

::tip Vectorised code is shorter, easier to read and usually 10 to 100 times faster than a Python loop, because the loop runs inside NumPy's compiled C code.

## One type per array

A list can mix anything. An array stores one type for every element, which is part of what makes it fast:

```python
import numpy as np

a = np.array([1, 2, 3])
b = np.array([1.5, 2, 3])
print(a.dtype, b.dtype)
```

Output:

```text
int64 float64
```

Because `1.5` is a float, NumPy turned every number in `b` into a float. On Windows you may see `int32` instead of `int64`; both are whole numbers.

## Where NumPy is used

- **Data analysis:** every Pandas column is a NumPy array underneath.
- **Images:** a photo is an array of pixels (height × width × 3 colours).
- **Machine learning:** your data, the model's weights and its predictions are all arrays.
- **Science and finance:** simulations, statistics and fast calculations.

::hint Quick check | Is `np.array([1, 2, 3]) * 3` equal to `[3, 6, 9]` or `[1, 2, 3, 1, 2, 3, 1, 2, 3]`? NumPy multiplies every element, so the answer is `[3 6 9]`. Only a plain list repeats.

## Summary

- NumPy gives Python fast arrays for numbers; import it as `np`.
- `np.array([...])` turns a list into an array.
- Maths on an array applies to every element at once (vectorisation), with no loop.
- All elements in an array share one type, shown by `.dtype`.
