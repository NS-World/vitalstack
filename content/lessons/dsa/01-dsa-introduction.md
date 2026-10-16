---
title: DSA Introduction
slug: dsa-introduction
subject: dsa
lesson: 1
difficulty: beginner
excerpt: What data structures and algorithms really are, why every big tech interview tests them, the 15 patterns that solve most problems, and the 6-step method you will use in every lesson.
---

**DSA** stands for **Data Structures and Algorithms**. It sounds scary, but you already use both every day. A to-do list is a data structure. "Look through my bag until I find my keys" is an algorithm. This course turns those everyday ideas into tools that make programs fast, and that get you through coding interviews at companies like Google, Amazon and Microsoft.

## Two simple definitions

- A **data structure** is a way to **store and organise** data so it is easy to use. Examples: a list, a stack of plates, a phone book, a family tree, a map of cities and roads.
- An **algorithm** is a **step-by-step recipe** for solving a problem. Examples: "check every item", "always look in the middle", "follow the roads from city to city".

The right data structure makes the algorithm easy. Finding a word in a dictionary is fast because the words are **sorted**: you open the middle, see if your word comes before or after, and throw away half the book. That trick has a name, **binary search**, and you can watch it work right now:

::viz binary-search {"arr": [3, 9, 14, 21, 27, 35, 42, 56, 63, 78], "target": 56}

Press **Play**, then change the numbers and the target and press **Visualize**. Every pattern in this course has a live animation like this one.

## Why DSA matters

### Speed that users can feel

Searching 1,000,000 sorted items one by one can take up to 1,000,000 checks. Binary search needs **20**. The same computer, the same data, a 50,000× difference, just from choosing a better algorithm.

### Interviews at almost every tech company

Big tech companies (Google, Amazon, Microsoft, Meta, Apple, Adobe, Flipkart, Uber and many startups) use DSA problems in interviews because they show how you think: can you break a problem down, choose the right tool, and explain the trade-offs?

### Better everyday code

Knowing that a `set` checks membership in O(1) while a `list` needs O(n) changes how you write ordinary programs, not just interview answers.

## The secret: patterns, not problems

There are thousands of practice problems online. Nobody memorises them all, and you don't need to. Most interview problems are variations of about **15 patterns**:

| Pattern | Use it when you see… | Lesson |
|---|---|---|
| Hash map / set | "Have I seen this before?", counting, pairs | [Hashing](/tutorials/dsa-hashing/) |
| Two pointers | Sorted array, pairs, palindromes | [Two Pointers](/tutorials/dsa-two-pointers/) |
| Sliding window | Longest / shortest **contiguous** part | [Sliding Window](/tutorials/dsa-sliding-window/) |
| Prefix sums | Many range-sum questions | [Prefix Sums](/tutorials/dsa-prefix-sum/) |
| Binary search | Sorted data, "find the smallest x that works" | [Binary Search](/tutorials/dsa-binary-search/) |
| Stack | Matching brackets, "most recent" | [Stacks](/tutorials/dsa-stacks/) |
| Monotonic stack | "Next greater / smaller element" | [Monotonic Stack](/tutorials/dsa-monotonic-stack/) |
| Linked list tricks | Reverse, merge, find the middle | [Linked Lists](/tutorials/dsa-linked-lists/) |
| Fast & slow pointers | Cycles, middle of a list | [Fast & Slow Pointers](/tutorials/dsa-fast-slow-pointers/) |
| Tree DFS / BFS | Anything with a tree | [Binary Trees](/tutorials/dsa-trees/) |
| Graph BFS / DFS | Networks, mazes, grids | [Graphs](/tutorials/dsa-graphs/) |
| Heap | "Top k", "k-th largest", scheduling | [Heaps](/tutorials/dsa-heaps/) |
| Backtracking | "All combinations / permutations" | [Backtracking](/tutorials/dsa-backtracking/) |
| Dynamic programming | "Number of ways", "minimum cost" with overlapping choices | [Dynamic Programming](/tutorials/dsa-dynamic-programming/) |
| Greedy + sorting | Intervals, scheduling | [Greedy and Intervals](/tutorials/dsa-greedy-intervals/) |

Learn the pattern once, and you can solve dozens of problems with it.

## The 6-step method (use it every time)

Good problem solvers follow the same steps. Interviewers love to see them out loud.

1. **Understand.** Repeat the problem in your own words. What is the input? What must you return?
2. **Examples.** Work through a small example by hand. Add tricky ones: empty input, one item, duplicates, negatives.
3. **Brute force.** What is the simplest correct solution, even if it is slow? Say its complexity.
4. **Optimise.** Which pattern removes the wasted work? (This course teaches you to spot it.)
5. **Code.** Write clean code with good names.
6. **Test.** Walk through your examples with the code, line by line. Fix bugs. State the final time and space complexity.

### A tiny example of the method

**Problem:** return `True` if a list contains any duplicate number.

- **Understand:** input a list of numbers; output True/False.
- **Examples:** `[1, 2, 3, 1]` → True. `[1, 2, 3]` → False. `[]` → False.
- **Brute force:** compare every pair: two loops, O(n²).
- **Optimise:** "Have I seen this before?" → that's the hash set pattern: O(n).

```python
def contains_duplicate(nums):
    seen = set()
    for x in nums:
        if x in seen:
            return True
        seen.add(x)
    return False

print(contains_duplicate([1, 2, 3, 1]))  # True
print(contains_duplicate([1, 2, 3]))     # False
print(contains_duplicate([]))            # False
```

That is the whole journey of this course in miniature: spot the pattern, write a few clean lines.

## How this course works

Every lesson has:

- A **real-life analogy** so the idea sticks.
- A **live visualizer**: play it, step through it, and try your own input.
- **Python code** that you can copy and run. (Python reads almost like English, which keeps the focus on the idea. The same patterns work in Java, C++ or JavaScript.)
- **Solved problems**, from easy to interview-level.
- An **interview corner**: where the pattern shows up and what interviewers look for.
- An **exercise** and a short **summary**.

::tip Learn in order, one lesson a day. When you are tired, play a two-minute brain game and come back fresh.

## What you need first

- Basic Python: variables, lists, loops, functions and dictionaries. If you are new, do the first 13 lessons of our [Python course](/tutorials/python-introduction/).
- Patience. Everyone finds DSA hard at first. Every expert was once confused by recursion.

## Common mistakes when learning DSA

- **Memorising solutions** instead of understanding the pattern. A small twist then breaks everything.
- **Jumping straight to code** without examples. Most bugs come from skipping steps 1–3.
- **Grinding hundreds of random problems.** 3–5 problems per pattern, understood deeply, beat 300 rushed ones.
- **Ignoring complexity.** "It works" is only half an answer in interviews.

## Exercise

1. Write down two data structures and two algorithms you use in daily life.
2. Play the binary search visualizer above with target `21`, then with a number that is **not** in the list. How many steps did each take?
3. Use the 6-step method to write `count_evens(nums)`, returning how many numbers are even. State its complexity.

## Summary

- Data structures store data; algorithms are step-by-step recipes.
- The right choice can make code thousands of times faster.
- About 15 patterns cover most interview problems.
- Use the 6 steps every time: understand, examples, brute force, optimise, code, test.

Next, you'll learn **Big O notation**, the language for describing how fast an algorithm is.
