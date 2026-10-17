## dsa-introduction
Q: What's the secret to solving many interview problems?
- [x] Recognising a small set of patterns
- [ ] Memorising thousands of solutions
- [ ] Typing fast
> Most problems are variations of ~15 patterns.

Q: What's the first step of the 6-step method?
- [x] Understand the problem and ask clarifying questions
- [ ] Start coding immediately
- [ ] Optimise
> Confirm inputs, outputs and edge cases first.

Q: A data structure is…
- [x] A way of organising data so operations are efficient
- [ ] A programming language
- [ ] A database
> Algorithms are the step-by-step procedures.

## dsa-big-o
Q: What is the time complexity of a single loop over n items?
- [x] O(n)
- [ ] O(1)
- [ ] O(n²)
> Work grows linearly with input size.

Q: Which is fastest for large n?
- [x] O(log n)
- [ ] O(n)
- [ ] O(n log n)
> Binary search is O(log n).

Q: O(2n + 10) simplifies to…
- [x] O(n)
- [ ] O(2n)
- [ ] O(10)
> Drop constants and lower-order terms.

## dsa-arrays-and-strings
Q: Reading arr[i] costs…
- [x] O(1)
- [ ] O(n)
- [ ] O(log n)
> The address is computed directly.

Q: Inserting at the front of a Python list costs…
- [x] O(n)
- [ ] O(1)
- [ ] O(log n)
> Every item shifts right.

Q: "In place" means…
- [x] Modifying the input using O(1) extra space
- [ ] Sorting first
- [ ] Using recursion
> Interviewers often ask for it.

## dsa-hashing
Q: Average lookup in a hash set is…
- [x] O(1)
- [ ] O(n)
- [ ] O(log n)
> That's why "seen before?" checks use sets.

Q: Two Sum in O(n) uses…
- [x] A hash map from value to index, checking target - x
- [ ] Two nested loops
- [ ] Sorting only
> Store what you need, look up what's missing.

Q: How do you group anagrams?
- [x] Use the sorted word (or letter counts) as a dictionary key
- [ ] Compare every pair
- [ ] Sort the list of words
> Same key → same group.

## dsa-two-pointers
Q: Converging two pointers usually needs…
- [x] A sorted array
- [ ] A hash map
- [ ] A tree
> Move the left or right pointer based on the sum.

Q: The reader/writer style is used to…
- [x] Remove duplicates or filter in place
- [ ] Sort an array
- [ ] Search a tree
> The writer marks where the next kept item goes.

Q: 3Sum typically runs in…
- [x] O(n²) after sorting
- [ ] O(n)
- [ ] O(n³) at best
> Fix one number, two-pointer the rest.

## dsa-sliding-window
Q: When do you think "sliding window"?
- [x] Contiguous subarrays/substrings with a condition (longest, shortest, sum)
- [ ] Trees
- [ ] Sorting problems
> Grow the right edge, shrink the left.

Q: A fixed-size window moves by…
- [x] Adding the new right item and removing the left item
- [ ] Recomputing the whole window
- [ ] Sorting it
> O(n) instead of O(n·k).

Q: Longest substring without repeating characters uses…
- [x] A variable window with a set/map of characters
- [ ] Binary search
- [ ] Recursion
> Shrink when a duplicate appears.

## dsa-prefix-sum
Q: With prefix sums, the sum of arr[i..j] is…
- [x] prefix[j+1] - prefix[i]
- [ ] prefix[j] + prefix[i]
- [ ] arr[j] - arr[i]
> O(1) per range query after O(n) setup.

Q: Counting subarrays with sum k uses…
- [x] Prefix sums plus a hash map of seen prefix counts
- [ ] Sorting
- [ ] Two nested loops only
> Look for prefix - k.

Q: Product of array except self can be solved…
- [x] With prefix and suffix products, no division
- [ ] Only with division
- [ ] With sorting
> Left products times right products.

## dsa-kadane
Q: Kadane's algorithm finds…
- [x] The maximum subarray sum in O(n)
- [ ] The minimum element
- [ ] Sorted order
> Extend the current sum or start fresh.

Q: When does Kadane restart the current sum?
- [x] When starting fresh at the current item beats extending
- [ ] Every 3 items
- [ ] Never
> current = max(x, current + x).

Q: "Best time to buy and sell stock" is related because…
- [x] You track the best result while scanning once
- [ ] It needs sorting
- [ ] It's a graph problem
> Track the minimum price so far.

## dsa-binary-search
Q: Binary search requires…
- [x] Sorted data (or a monotonic condition)
- [ ] A hash map
- [ ] A linked list
> Each step halves the search space.

Q: Binary search's time complexity is…
- [x] O(log n)
- [ ] O(n)
- [ ] O(1)
> 1 million items need about 20 steps.

Q: "Binary search on the answer" means…
- [x] Searching over possible answers with a yes/no check
- [ ] Searching unsorted arrays
- [ ] Sorting with binary trees
> E.g. minimum speed to finish in time.

## dsa-sorting
Q: Insertion sort is good for…
- [x] Small or nearly sorted arrays
- [ ] Huge random arrays
- [ ] Linked lists only
> Nearly sorted input runs close to O(n).

Q: Python's built-in sort is…
- [x] Timsort, O(n log n) and stable
- [ ] Bubble sort
- [ ] O(n²)
> Use it in real code.

Q: A stable sort…
- [x] Keeps equal items in their original order
- [ ] Never crashes
- [ ] Uses no memory
> Matters when sorting by several keys.

## dsa-merge-quick-sort
Q: Merge sort's time complexity is…
- [x] O(n log n) always
- [ ] O(n²) always
- [ ] O(n)
> It needs O(n) extra space.

Q: Quick sort's worst case is…
- [x] O(n²), with bad pivots
- [ ] O(n log n)
- [ ] O(log n)
> Random pivots make it unlikely.

Q: Divide and conquer means…
- [x] Split the problem, solve the parts, combine the results
- [ ] Loop twice
- [ ] Use a hash map
> Merge sort divides then merges.

## dsa-recursion
Q: Every recursive function needs…
- [x] A base case
- [ ] A loop
- [ ] Global variables
> Without it: infinite recursion.

Q: Memoization…
- [x] Stores results of subproblems to avoid recomputing them
- [ ] Deletes the stack
- [ ] Sorts results
> Fibonacci goes from O(2ⁿ) to O(n).

Q: Each recursive call uses…
- [x] A frame on the call stack
- [ ] A new thread
- [ ] Nothing
> Deep recursion can overflow the stack.

## dsa-backtracking
Q: The backtracking template is…
- [x] Choose, explore, un-choose
- [ ] Sort, search, return
- [ ] Push, pop, peek
> Undo the choice before trying the next.

Q: How many subsets does a set of n items have?
- [x] 2ⁿ
- [ ] n²
- [ ] n!
> Each item is in or out.

Q: Pruning means…
- [x] Stopping early when a path can't lead to a valid answer
- [ ] Removing duplicates after
- [ ] Sorting the output
> It saves huge amounts of work.

## dsa-linked-lists
Q: Inserting after a known node costs…
- [x] O(1)
- [ ] O(n)
- [ ] O(log n)
> Just change pointers.

Q: Accessing the k-th node costs…
- [x] O(k)
- [ ] O(1)
- [ ] O(log n)
> You must walk from the head.

Q: Why use a dummy head node?
- [x] It removes special cases when the real head changes
- [ ] To save memory
- [ ] It's required in Python
> Return dummy.next at the end.

## dsa-fast-slow-pointers
Q: How do you find a linked list's middle in one pass?
- [x] Slow moves 1 step, fast moves 2; when fast ends, slow is in the middle
- [ ] Count then walk again only
- [ ] Use a stack
> The tortoise and hare.

Q: How do you detect a cycle?
- [x] If fast ever meets slow, there's a cycle
- [ ] Check for None at the end
- [ ] Sort the list
> Floyd's algorithm, O(1) space.

Q: To remove the n-th node from the end in one pass…
- [x] Move fast n steps ahead, then move both until fast reaches the end
- [ ] Reverse the list
- [ ] Use recursion only
> slow then sits before the target.

## dsa-stacks
Q: A stack is…
- [x] Last in, first out (LIFO)
- [ ] First in, first out
- [ ] Sorted
> Like a pile of plates.

Q: Valid parentheses is solved by…
- [x] Pushing openers and matching them when closers arrive
- [ ] Counting characters only
- [ ] Sorting
> The stack must be empty at the end.

Q: In Python, a list works as a stack using…
- [x] append and pop
- [ ] insert(0) and pop(0)
- [ ] sort
> Both are O(1) at the end.

## dsa-monotonic-stack
Q: "Next greater element" problems suggest…
- [x] A monotonic stack
- [ ] Binary search
- [ ] A heap
> Each item is pushed and popped once: O(n).

Q: In "daily temperatures", the stack stores…
- [x] Indexes of days still waiting for a warmer day
- [ ] Temperatures sorted
- [ ] Answers
> Pop when a warmer day arrives.

Q: The time complexity of a monotonic stack pass is…
- [x] O(n)
- [ ] O(n²)
- [ ] O(n log n)
> Every element enters and leaves once.

## dsa-queues
Q: A queue is…
- [x] First in, first out (FIFO)
- [ ] Last in, first out
- [ ] Random access
> Like a ticket counter line.

Q: Why use collections.deque instead of a list for queues?
- [x] popleft is O(1); list.pop(0) is O(n)
- [ ] Lists can't store numbers
- [ ] deque sorts items
> Shifting a list is slow.

Q: Which traversal uses a queue?
- [x] Breadth-first search (level order)
- [ ] Depth-first search
- [ ] Inorder traversal
> Process level by level.

## dsa-trees
Q: Which traversal visits left, root, right?
- [x] Inorder
- [ ] Preorder
- [ ] Postorder
> Preorder is root first; postorder root last.

Q: The height of a tree is solved recursively as…
- [x] 1 + max(height(left), height(right))
- [ ] left + right
- [ ] Count all nodes
> Trust the recursion for subtrees.

Q: Level-order traversal uses…
- [x] A queue
- [ ] A stack
- [ ] A heap
> BFS on a tree.

## dsa-bst
Q: In a BST, every left subtree value is…
- [x] Smaller than the node
- [ ] Larger than the node
- [ ] Equal to the node
> Right subtree values are larger.

Q: Inorder traversal of a BST gives…
- [x] Sorted order
- [ ] Reverse order
- [ ] Random order
> A handy validity check.

Q: Search in a balanced BST costs…
- [x] O(log n)
- [ ] O(n) always
- [ ] O(1)
> Unbalanced trees can degrade to O(n).

## dsa-heaps
Q: A min-heap's top is…
- [x] The smallest element
- [ ] The largest element
- [ ] The newest element
> heapq is a min-heap.

Q: To find the k largest items efficiently…
- [x] Keep a min-heap of size k
- [ ] Sort descending always
- [ ] Use a stack
> O(n log k).

Q: The running median uses…
- [x] Two heaps: a max-heap for the lower half, a min-heap for the upper half
- [ ] One sorted list
- [ ] A queue
> Balance their sizes.

## dsa-graphs
Q: An adjacency list stores…
- [x] For each node, the list of its neighbours
- [ ] A full n×n matrix
- [ ] Only edges' weights
> Memory O(V + E).

Q: BFS finds…
- [x] Shortest paths in unweighted graphs
- [ ] Shortest paths with weights
- [ ] Cycles only
> It explores in waves.

Q: Why track visited nodes?
- [x] To avoid infinite loops on cycles
- [ ] For sorting
- [ ] To count edges
> Graphs can loop back.

## dsa-grids
Q: Number of islands is solved by…
- [x] DFS/BFS flood fill from each unvisited land cell
- [ ] Sorting the grid
- [ ] Binary search
> Each fill marks one island.

Q: Rotting oranges uses…
- [x] Multi-source BFS starting from all rotten oranges
- [ ] DFS from one orange
- [ ] Dynamic programming
> Each BFS level is one minute.

Q: How many neighbours does a cell have with 4-directional moves?
- [x] Up to 4
- [ ] 8
- [ ] 2
> Check bounds at edges.

## dsa-topological-sort
Q: Topological sort works on…
- [x] Directed acyclic graphs (DAGs)
- [ ] Any graph
- [ ] Trees only
> A cycle makes ordering impossible.

Q: Kahn's algorithm starts with…
- [x] Nodes that have in-degree 0
- [ ] The largest node
- [ ] Random nodes
> Remove them and update neighbours.

Q: Course Schedule asks…
- [x] Whether prerequisites contain a cycle
- [ ] The shortest course
- [ ] The cheapest path
> If all courses are ordered, no cycle.

## dsa-shortest-paths
Q: Dijkstra's algorithm needs…
- [x] Non-negative edge weights
- [ ] Negative weights
- [ ] Unweighted edges only
> It uses a min-heap of distances.

Q: Dijkstra with a heap runs in about…
- [x] O((V + E) log V)
- [ ] O(V!)
- [ ] O(1)
> Efficient for sparse graphs.

Q: Union-find is great for…
- [x] Tracking connected components and detecting cycles
- [ ] Sorting
- [ ] Shortest weighted paths
> With path compression it's nearly O(1).

## dsa-dynamic-programming
Q: DP applies when a problem has…
- [x] Overlapping subproblems and optimal substructure
- [ ] No recursion
- [ ] Sorted input
> Store answers to subproblems.

Q: Top-down vs bottom-up DP?
- [x] Recursion + memoization vs filling a table iteratively
- [ ] They're unrelated
- [ ] Bottom-up uses recursion
> Both give the same results.

Q: Climbing stairs (1 or 2 steps) follows…
- [x] ways(n) = ways(n-1) + ways(n-2)
- [ ] ways(n) = n²
- [ ] ways(n) = 2n
> It's the Fibonacci pattern.

## dsa-dp-2d
Q: Longest common subsequence of "ABCBDAB"-style strings uses…
- [x] A 2D table indexed by positions in both strings
- [ ] A stack
- [ ] Sorting
> dp[i][j] for prefixes.

Q: Edit distance counts…
- [x] The minimum insertions, deletions and replacements to turn one word into another
- [ ] Matching letters
- [ ] Word length difference
> Used in spell-checkers.

Q: Unique paths in an m×n grid (right/down moves) uses…
- [x] dp[r][c] = dp[r-1][c] + dp[r][c-1]
- [ ] dp[r][c] = r * c
- [ ] BFS
> Paths arrive from above or the left.

## dsa-greedy-intervals
Q: Interval problems usually start by…
- [x] Sorting the intervals (by start or end)
- [ ] Hashing them
- [ ] Reversing them
> Then sweep once.

Q: To fit the most non-overlapping meetings, sort by…
- [x] End time
- [ ] Start time
- [ ] Duration
> The earliest-ending meeting leaves most room.

Q: Meeting rooms II (minimum rooms) can use…
- [x] A min-heap of end times
- [ ] A stack
- [ ] Binary search only
> The heap size is the rooms in use.

## dsa-interview-playbook
Q: What should you do before coding in an interview?
- [x] Clarify, give examples, and explain a brute force then a better approach
- [ ] Code silently
- [ ] Ask for the answer
> Talking through your plan is part of the score.

Q: After coding, you should…
- [x] Walk through an example, check edge cases and state the complexity
- [ ] Submit immediately
- [ ] Rewrite in another language
> Testing shows care.

Q: Which is an important edge case to check?
- [x] Empty input
- [ ] The colour of the editor
- [ ] Variable name length
> Also single elements, duplicates and negatives.
