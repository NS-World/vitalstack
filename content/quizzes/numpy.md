## numpy-introduction
Q: What does `np.array([1, 2, 3]) * 2` give?
- [x] `[2 4 6]`
- [ ] `[1 2 3 1 2 3]`
- [ ] An error
> NumPy multiplies every element. Only a plain list repeats.
Q: Why is NumPy faster than a Python loop over a list?
- [ ] It uses more memory
- [x] The work runs in compiled code on a tight block of numbers (vectorisation)
- [ ] It skips some elements
> Vectorised operations avoid the slow Python loop.
Q: What is the usual short name for NumPy?
- [ ] `num`
- [x] `np`
- [ ] `npy`
> `import numpy as np` is the convention everyone uses.

## numpy-creating-arrays
Q: What is the shape of `np.zeros((3, 4))`?
- [x] `(3, 4)`
- [ ] `(4, 3)`
- [ ] `(12,)`
> 3 rows and 4 columns.
Q: What does `np.arange(2, 10, 3)` return?
- [ ] `[2 5 8 11]`
- [x] `[2 5 8]`
- [ ] `[3 6 9]`
> Start at 2, step 3, stop before 10.
Q: Why set a seed with `np.random.default_rng(42)`?
- [ ] To make numbers bigger
- [x] To get the same "random" numbers every run
- [ ] To make it faster
> Seeds make results repeatable.

## numpy-indexing-slicing
Q: For a 2-D array `m`, what does `m[:, 1]` return?
- [ ] Row 1
- [x] Column 1 from every row
- [ ] The element at (1, 1)
> `:` means all rows.
Q: How do you keep only values above 50 in array `a`?
- [x] `a[a > 50]`
- [ ] `a.filter(50)`
- [ ] `a[50:]`
> A comparison makes a True/False mask that selects elements.
Q: Which combines two conditions correctly?
- [ ] `a > 10 and a < 20`
- [x] `(a > 10) & (a < 20)`
- [ ] `a > 10 & a < 20`
> Use `&` with brackets around each condition.

## numpy-reshape-stack
Q: What does `-1` mean in `a.reshape(2, -1)`?
- [x] NumPy works out that size for you
- [ ] Reverse the array
- [ ] Remove the last element
> With 12 elements and 2 rows, -1 becomes 6.
Q: What does `np.vstack([a, b])` do?
- [ ] Puts arrays side by side
- [x] Puts arrays on top of each other (more rows)
- [ ] Adds them element by element
> v = vertical.
Q: What is the shape of the transpose of a (5, 2) array?
- [ ] `(5, 2)`
- [x] `(2, 5)`
- [ ] `(10,)`
> `.T` swaps rows and columns.

## numpy-math-broadcasting
Q: Can a (3, 3) array be added to a (3,) array?
- [x] Yes, the row is stretched down
- [ ] No, shapes must be identical
- [ ] Only with a loop
> Broadcasting compares shapes from the right; 3 matches 3.
Q: Which pair cannot be broadcast together?
- [ ] (3, 3) and (3, 1)
- [ ] (4, 2) and (2,)
- [x] (3, 3) and (2,)
> 3 and 2 differ and neither is 1.
Q: What does `np.sqrt(np.array([4, 9]))` return?
- [x] `[2. 3.]`
- [ ] `[16 81]`
- [ ] An error
> Ufuncs work on every element.

## numpy-statistics
Q: What does `argmax()` return?
- [ ] The largest value
- [x] The position of the largest value
- [ ] The average
> Use it to find which item was the best.
Q: For a table of students × subjects, which gives one average per subject?
- [x] `marks.mean(axis=0)`
- [ ] `marks.mean(axis=1)`
- [ ] `marks.mean()`
> axis=0 collapses the rows, leaving one result per column.
Q: Why is the median sometimes better than the mean?
- [ ] It's always larger
- [x] A few extreme values don't pull it away
- [ ] It's faster to compute
> The median is the middle value of the sorted data.
