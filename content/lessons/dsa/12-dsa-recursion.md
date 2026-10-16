---
title: Recursion
slug: dsa-recursion
subject: dsa
lesson: 12
difficulty: intermediate
excerpt: Functions that call themselves, explained with a live call tree. Base cases, the call stack, the "trust the recursion" mindset, memoization, and classic problems like power, palindromes and Tower of Hanoi.
---

**Recursion** is when a function solves a problem by calling **itself** on a smaller version of the same problem. It powers trees, graphs, backtracking, merge sort and dynamic programming. It feels like magic at first; this lesson makes it feel like common sense.

## The movie hall analogy

You're in a dark cinema and want to know your row number. You can't count from the front, so you ask the person in front: "Which row are you in?" They don't know either, so they ask the person in front of **them**, and so on. The person in row 1 knows the answer: "Row 1!" Each person then adds 1 and passes the answer back.

```python
def my_row(person):
    if person.in_front is None:      # base case: front row
        return 1
    return my_row(person.in_front) + 1
```

Every recursive function has the same two parts:

1. **Base case:** a problem so small you know the answer directly.
2. **Recursive case:** shrink the problem, call yourself, and use the answer.

## Factorial

5! = 5 × 4 × 3 × 2 × 1. Notice 5! = 5 × 4!

```python
def factorial(n):
    if n <= 1:                # base case
        return 1
    return n * factorial(n - 1)

print(factorial(5))  # 120
```

## The call stack

Each call waits for the call it made. The computer keeps the waiting calls on the **call stack**:

```text
factorial(4)
  = 4 * factorial(3)
        = 3 * factorial(2)
              = 2 * factorial(1)
                    = 1          ← base case: start returning
              = 2 * 1 = 2
        = 3 * 2 = 6
  = 4 * 6 = 24
```

Each waiting call uses memory, so recursion depth n means **O(n) space**. Python stops at about 1,000 levels deep with a `RecursionError`; for deep problems use a loop or raise the limit carefully.

## Watch a recursion tree

Fibonacci: fib(n) = fib(n−1) + fib(n−2), with fib(0) = 0 and fib(1) = 1. Each call makes **two** calls, so the calls form a tree:

::viz fib-tree {"n": 5, "memo": "no"}

Notice fib(3) and fib(2) are computed **again and again**. The number of calls grows roughly like 2ⁿ: fib(40) would need hundreds of millions of calls.

## Memoization: remember answers

If a function is called with the same input again, return the saved answer. Replay the visualizer with **Use memo = yes** and watch the tree shrink:

::viz fib-tree {"n": 6, "memo": "yes"}

```python
def fib(n, memo=None):
    if memo is None:
        memo = {}
    if n <= 1:
        return n
    if n in memo:
        return memo[n]
    memo[n] = fib(n - 1, memo) + fib(n - 2, memo)
    return memo[n]

print(fib(10))  # 55
print(fib(80))  # 23416728348467685
```

From O(2ⁿ) to **O(n)**. Python can do this for you:

```python
from functools import lru_cache

@lru_cache(maxsize=None)
def fib2(n):
    return n if n <= 1 else fib2(n - 1) + fib2(n - 2)

print(fib2(50))  # 12586269025
```

Memoization is the doorway to **dynamic programming** (lesson 26).

## The "leap of faith" mindset

Don't trace every call in your head. Instead:

1. Decide exactly what your function **returns** for an input.
2. **Assume** it already works for smaller inputs.
3. Use that to solve the current input.
4. Make sure the base case is correct and every call gets closer to it.

### Sum of a list

"`total(nums)` returns the sum. If I trust `total(nums[1:])`, the answer is the first item plus that."

```python
def total(nums):
    if not nums:
        return 0
    return nums[0] + total(nums[1:])

print(total([4, 2, 7]))  # 13
```

(Slicing copies the list each time, so this is O(n²); in real code pass an index instead. It's fine for learning the idea.)

### Is it a palindrome?

```python
def is_pal(s):
    if len(s) <= 1:
        return True
    return s[0] == s[-1] and is_pal(s[1:-1])

print(is_pal("level"))  # True
print(is_pal("lever"))  # False
```

### Fast power: x to the n in O(log n)

x¹⁰ = (x⁵)². Halving the exponent each time gives O(log n):

```python
def power(x, n):
    if n == 0:
        return 1
    half = power(x, n // 2)
    return half * half if n % 2 == 0 else half * half * x

print(power(2, 10))  # 1024
print(power(3, 5))   # 243
```

### Tower of Hanoi

Move n disks from rod A to rod C, never placing a bigger disk on a smaller one. The recursive insight: move n−1 disks out of the way to B, move the biggest to C, then move the n−1 disks from B onto it.

```python
def hanoi(n, src="A", dst="C", via="B", moves=None):
    if moves is None:
        moves = []
    if n == 0:
        return moves
    hanoi(n - 1, src, via, dst, moves)
    moves.append(f"{src}->{dst}")
    hanoi(n - 1, via, dst, src, moves)
    return moves

print(hanoi(2))       # ['A->B', 'A->C', 'B->C']
print(len(hanoi(4)))  # 15
```

2ⁿ − 1 moves. Try it yourself in the **Tower of Hanoi** brain game.

## Recursion vs loops

Anything recursive can be written with a loop and an explicit stack, and vice versa.

- Use **recursion** when the problem is naturally nested: trees, graphs, divide and conquer, backtracking.
- Use a **loop** for simple repetition, or when recursion would be very deep.

## Interview corner

Recursion itself is rarely the question, but it's inside almost every tree, graph and backtracking problem asked at **Google, Amazon, Microsoft and Meta**. Interviewers listen for: a clear base case, the recursive relation in words, and the time/space complexity (including stack depth). Pow(x, n), Fibonacci with memoization and Generate Parentheses are common direct questions.

::tip Say your recursive function's contract out loud: "`depth(node)` returns the height of the tree rooted at node." Most bugs disappear once the contract is clear.

## Common mistakes

- **Missing or wrong base case** → infinite recursion and `RecursionError`.
- **Not making progress** towards the base case (for example, calling `f(n)` from `f(n)`).
- **Forgetting to return** the recursive result.
- **Using a mutable default argument** like `memo={}`, which is shared between calls; use `None`.
- **Ignoring stack space** in complexity analysis.

## Exercise

1. In the visualizer, count the calls for fib(6) with and without memo.
2. Write `count_down(n)` that prints n down to 1 recursively.
3. Write `reverse_string(s)` recursively.
4. Write `sum_digits(n)` recursively (`sum_digits(1234)` → 10).

## Summary

- Recursion = base case + a call on a smaller problem.
- Each call waits on the call stack: depth n costs O(n) space.
- Repeated work makes naive recursion exponential; memoization fixes it.
- Trust the recursion: define what the function returns and build on smaller answers.

Next, you'll use recursion to explore choices with **backtracking**.
