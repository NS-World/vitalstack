---
title: The DSA Interview Playbook
slug: dsa-interview-playbook
subject: dsa
lesson: 29
difficulty: intermediate
excerpt: Everything in one place. A pattern cheat sheet that maps problem clues to techniques, a 6-step method for any coding interview, how to talk while you code, and a calm 8-week practice plan.
---

You've learned the patterns. This final lesson turns them into a **game plan**: how to recognise which pattern a new problem needs, what to say in the interview room, and how to practise without burning out.

## The pattern cheat sheet

Read the problem, look for the **clue**, reach for the **tool**.

| If the problem says… | Think… | Lesson |
|---|---|---|
| "sorted array", "find a target", "minimum value that works" | Binary search | 9 |
| "pair / triplet with sum", "palindrome", "in place" | Two pointers | 5 |
| "longest / shortest substring or subarray with…" | Sliding window | 6 |
| "sum of a range", "subarray sum equals k" | Prefix sums + hash map | 7 |
| "seen before?", "count", "group", "two sum" | Hash map / set | 4 |
| "maximum subarray" | Kadane | 8 |
| "matching brackets", "undo", "nested" | Stack | 16 |
| "next greater / smaller", "temperatures", "histogram" | Monotonic stack | 17 |
| "cycle in a list", "middle node" | Fast and slow pointers | 15 |
| "tree depth / path / level" | DFS or BFS on trees | 19 |
| "k largest / smallest", "median", "merge k" | Heap | 21 |
| "connected", "network", "fewest steps" | Graph BFS / DFS | 22 |
| "grid", "island", "spread", "maze" | Flood fill / grid BFS | 23 |
| "prerequisites", "order of tasks" | Topological sort | 24 |
| "cheapest route with weights" | Dijkstra | 25 |
| "all combinations / permutations / subsets" | Backtracking | 13 |
| "how many ways", "min/max cost", "is it possible" | Dynamic programming | 26, 27 |
| "intervals", "meetings", "schedule" | Sort + greedy sweep | 28 |

::tip Input size is a giant hint. n ≤ 20: backtracking is fine. n ≤ 1,000: O(n²) works. n up to 100,000 or more: you need O(n log n) or O(n).

Want to drill this? Play **Pattern Detective** in the [Brain Games](/games/#play-pattern-detective): you read a problem in one line and pick the pattern before the timer runs out.

## The 6-step method for any coding question

1. **Understand.** Repeat the problem in your own words. Ask about input size, empty input, duplicates, negative numbers, and what to return when there's no answer.
2. **Examples.** Work through one normal example and one edge case by hand. This often reveals the pattern.
3. **Brute force first.** Say the simple solution and its complexity out loud: "Checking every pair is O(n²)." It proves you can solve it and gives you a baseline.
4. **Optimise.** Ask: "What work am I repeating?" Then reach for the cheat sheet: a hash map to remember, sorting to bring things together, a heap for the best-so-far, DP for repeated subproblems.
5. **Code.** Clean names, small helper functions, no clever tricks you can't explain.
6. **Test.** Walk through your code with the example, line by line, then try the edge cases. Fix bugs calmly: finding your own bug is a positive signal.

## How to talk while you code

Interviewers can't read your mind. Silence feels like being stuck, even when you're not. Narrate:

- "I'll use a dictionary mapping each value to its index so lookups are O(1)."
- "This loop moves the right pointer; the inner while shrinks the window when it becomes invalid."
- "I'm not sure about this edge case. Let me check it with an empty list."

If you're stuck, say what you're thinking: "Sorting would give O(n log n). Could a hash map get O(n)?" Interviewers often give hints, and taking a hint well is a skill they score.

## Complexity: always say it

Finish every solution with time and space complexity and **why**:

```python
def two_sum(nums, target):
    seen = {}                          # value -> index
    for i, x in enumerate(nums):
        if target - x in seen:
            return [seen[target - x], i]
        seen[x] = i
    return []

print(two_sum([2, 7, 11, 15], 9))  # [0, 1]
```

"One pass over n numbers with O(1) dictionary operations: O(n) time. The dictionary may store n items: O(n) space."

## Edge cases checklist

Run through this list before you say "done":

- Empty input, a single element, two elements.
- All items the same; already sorted; sorted in reverse.
- Negative numbers and zero.
- Very large input (would your recursion hit the limit?).
- No valid answer (what do you return?).

## Beyond the code

- **Behavioural questions** matter too, especially at Amazon (leadership principles). Prepare 5 short stories using STAR: Situation, Task, Action, Result.
- **Ask good questions** at the end: about the team, how they review code, what a first project might look like.
- **System design** appears for experienced roles; for freshers and interns, DSA and fundamentals dominate.

## A calm 8-week practice plan

Thirty to sixty focused minutes a day beats a 10-hour weekend.

| Week | Focus | Practise |
|---|---|---|
| 1 | Arrays, strings, hashing, Big O | 8 easy problems |
| 2 | Two pointers, sliding window, prefix sums | 8 problems, mostly easy |
| 3 | Binary search, sorting, stacks | 8 problems, first mediums |
| 4 | Linked lists, recursion, backtracking | 8 problems |
| 5 | Trees, BST, heaps | 8 problems, mostly medium |
| 6 | Graphs, grids, topological sort | 8 problems |
| 7 | Dynamic programming, greedy, intervals | 8 problems |
| 8 | Mixed review and timed mock interviews | 2 mocks + revisit missed problems |

Rules that make it stick:

- **Try for 20–30 minutes**, then read a hint, not the full solution.
- **Re-solve** every problem you needed help with three days later, from scratch.
- **Keep a log**: problem, pattern, the key idea in one sentence.
- **Take brain breaks.** A 2-minute game resets your focus better than scrolling.

## Where to practise on VitalStack

- The **[Interview Problems](/dsa-problems/)** page lists classic questions with their pattern, difficulty and companies that commonly ask them. Filter by pattern to drill one technique at a time, or hit "Random problem" for a surprise.
- Every lesson in this course has a **live visualizer**: change the input and watch the algorithm think. Each one you finish earns XP.
- The **[Brain Games](/games/)** page trains binary search, brackets, Big O and pattern spotting in 2 minutes.

## Interview corner

Companies like **Google, Amazon, Microsoft, Meta, Apple, Adobe, Flipkart, Uber and Goldman Sachs** all run DSA rounds, and the patterns repeat. Interviewers consistently say the same thing: they care less about a perfect answer and more about **clear thinking, communication and testing**. A brute-force solution explained well beats a half-finished clever one.

## Common mistakes

- **Coding immediately** without clarifying or giving examples.
- **Going silent** for minutes at a time.
- **Skipping the brute force** and getting lost chasing the optimal answer.
- **Not testing** the code before saying "done".
- **Memorising solutions** instead of patterns: a small twist breaks memorised answers.

## Exercise

1. Pick three problems from the Interview Problems page with different patterns. For each, write only the clue you spotted and the tool you'd use.
2. Solve one of them out loud, recording yourself. Listen back: were there long silences?
3. Write your own one-page cheat sheet from memory. Compare it with the table above.

## Summary

- Match problem clues to patterns using the cheat sheet; input size hints at the needed complexity.
- Follow the 6 steps: understand, examples, brute force, optimise, code, test.
- Talk through your thinking and always state complexity.
- Practise daily with spaced repetition, and take brain breaks.

Congratulations on finishing the DSA course! Head to the **Interview Problems** page and start your streak.
