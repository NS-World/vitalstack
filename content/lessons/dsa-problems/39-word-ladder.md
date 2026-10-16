---
title: Word Ladder
slug: problem-word-ladder
subject: dsa-problems
lesson: 39
difficulty: advanced
level: Hard
pattern: Graphs
companies: Amazon, Meta, Google, LinkedIn, Microsoft, Snapchat
excerpt: Change one letter at a time to turn one word into another. Words are nodes, one-letter changes are edges, and BFS finds the shortest ladder.
---

There's no graph in the input, but the moment you see "fewest steps", think **BFS** and build the graph yourself.

## The problem

Transform `begin` into `end` by changing **one letter at a time**; every intermediate word must be in `word_list`. Return the number of words in the shortest such sequence (including both ends), or 0 if impossible.

## Example

`begin = "hit"`, `end = "cog"`, `word_list = ["hot", "dot", "dog", "lot", "log", "cog"]`

`hit → hot → dot → dog → cog` → **5**

## Hints

::hint Hint 1 | Each word is a node. Two words are connected if they differ by exactly one letter.
::hint Hint 2 | "Shortest sequence" in an unweighted graph → BFS.
::hint Hint 3 | To find neighbours fast, try changing each position to every letter a–z and check the set of words.

## The easy idea: a game of stepping stones

Each valid word is a stepping stone. From a stone you can jump to any stone that differs by one letter. BFS explores all stones 1 jump away, then 2 jumps, and so on; the first time it lands on the end word, that's the fewest jumps. Watch BFS spread in waves on a small graph:

::viz graph-bfs {"edges": "hit-hot, hot-dot, hot-lot, dot-dog, lot-log, dot-lot, dog-log, dog-cog, log-cog", "start": "hit"}

## Solution

```python
from collections import deque
from string import ascii_lowercase

def ladder_length(begin, end, word_list):
    words = set(word_list)
    if end not in words:
        return 0
    q = deque([(begin, 1)])
    seen = {begin}
    while q:
        word, steps = q.popleft()
        if word == end:
            return steps
        for i in range(len(word)):
            for ch in ascii_lowercase:
                nxt = word[:i] + ch + word[i + 1:]
                if nxt in words and nxt not in seen:
                    seen.add(nxt)
                    q.append((nxt, steps + 1))
    return 0

words = ["hot", "dot", "dog", "lot", "log", "cog"]
print(ladder_length("hit", "cog", words))                         # 5
print(ladder_length("hit", "cog", ["hot", "dot", "dog", "lot", "log"]))  # 0
```

## Complexity

- **Time O(N · L · 26)** for N words of length L (each word tries L × 26 changes; building each new string costs O(L) more).
- **Space O(N)** for the queue and the seen set.

## Speed-up: bidirectional BFS

Search from both ends at once and stop when the two searches meet. Each side only has to go half as deep, which is dramatically faster on big dictionaries, a great follow-up to mention.

## Similar problems

Word Ladder II (return all shortest ladders), Minimum Genetic Mutation, Open the Lock, Shortest Path in Binary Matrix.

Learn the pattern: [Graphs: BFS and DFS](/tutorials/dsa-graphs/).
