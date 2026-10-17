## problem-two-sum
Q: What's the optimal time for Two Sum?
- [x] O(n) with a hash map
- [ ] O(n²) is the best possible
- [ ] O(log n)
> Store value → index; look up target - x.

Q: What do you store in the map?
- [x] Each value and its index
- [ ] Each pair's sum
- [ ] Sorted values
> So you can return indexes.

Q: Why check the map before inserting the current number?
- [x] So a number isn't paired with itself
- [ ] For speed only
- [ ] It doesn't matter
> E.g. target 6 with a single 3.

## problem-contains-duplicate
Q: Fastest general approach?
- [x] A set: return True when a number is already in it
- [ ] Sort and binary search
- [ ] Nested loops
> O(n) time, O(n) space.

Q: A one-line Python check is…
- [x] len(set(nums)) != len(nums)
- [ ] nums.sort()
- [ ] max(nums) == min(nums)
> Duplicates shrink the set.

Q: Space-saving alternative?
- [x] Sort, then compare neighbours (O(n log n), O(1) extra)
- [ ] Use recursion
- [ ] No alternative
> Trade time for space.

## problem-valid-anagram
Q: Two strings are anagrams when…
- [x] They have the same letter counts
- [ ] They have the same length only
- [ ] They're reverses
> Counter(s) == Counter(t).

Q: Quick first check?
- [x] Different lengths → not anagrams
- [ ] Same first letter
- [ ] Same last letter
> Return False early.

Q: Complexity of counting?
- [x] O(n)
- [ ] O(n²)
- [ ] O(log n)
> Sorting would be O(n log n).

## problem-group-anagrams
Q: What key groups anagrams together?
- [x] The sorted word (or a letter-count tuple)
- [ ] The word length
- [ ] The first letter
> 'eat', 'tea', 'ate' → 'aet'.

Q: Which structure collects the groups?
- [x] A dict of key → list of words
- [ ] A set
- [ ] A stack
> defaultdict(list) is handy.

Q: Why a tuple of 26 counts instead of sorting?
- [x] O(k) per word instead of O(k log k)
- [ ] Tuples sort faster
- [ ] Lists can't be keys but sorted strings can't either
> Lists aren't hashable; tuples are.

## problem-top-k-frequent-elements
Q: First step?
- [x] Count frequencies with a hash map
- [ ] Sort the array
- [ ] Reverse it
> Counter(nums).

Q: Heap approach complexity?
- [x] O(n log k)
- [ ] O(n²)
- [ ] O(1)
> Keep a min-heap of size k.

Q: Which approach achieves O(n)?
- [x] Bucket sort by frequency
- [ ] Full sort
- [ ] Binary search
> Index buckets by count.

## problem-product-of-array-except-self
Q: Why not divide the total product?
- [x] Division isn't allowed and zeros break it
- [ ] Division is slow
- [ ] It's O(n²)
> Use prefix and suffix products.

Q: answer[i] equals…
- [x] product of items left of i × product of items right of i
- [ ] total / nums[i] always
- [ ] nums[i] squared
> Two passes.

Q: Extra space beyond the output?
- [x] O(1) with a running suffix variable
- [ ] O(n²)
- [ ] O(n log n)
> Reuse the output array for prefixes.

## problem-longest-consecutive-sequence
Q: Key trick?
- [x] Put numbers in a set and start counting only from sequence starts (x-1 not in set)
- [ ] Sort and count
- [ ] Use a heap
> Each number is visited a constant number of times.

Q: Time complexity?
- [x] O(n)
- [ ] O(n log n)
- [ ] O(n²)
> Sorting would be O(n log n).

Q: For [100, 4, 200, 1, 3, 2] the answer is…
- [x] 4
- [ ] 3
- [ ] 6
> 1, 2, 3, 4.

## problem-valid-palindrome
Q: Which characters are compared?
- [x] Only letters and digits, ignoring case
- [ ] All characters
- [ ] Only vowels
> 'A man, a plan…' is valid.

Q: Pattern?
- [x] Two pointers from both ends
- [ ] Sliding window
- [ ] Stack only
> Skip non-alphanumerics.

Q: Extra space with two pointers?
- [x] O(1)
- [ ] O(n)
- [ ] O(n²)
> No cleaned copy needed.

## problem-container-with-most-water
Q: Area between lines i and j is…
- [x] min(h[i], h[j]) × (j - i)
- [ ] max(h[i], h[j]) × (j - i)
- [ ] h[i] + h[j]
> Water is limited by the shorter line.

Q: Which pointer moves?
- [x] The one at the shorter line
- [ ] The taller one
- [ ] Both always
> Moving the taller can't increase area.

Q: Complexity?
- [x] O(n)
- [ ] O(n²)
- [ ] O(log n)
> One pass with two pointers.

## problem-three-sum
Q: First step?
- [x] Sort the array
- [ ] Build a hash set
- [ ] Reverse it
> Then two-pointer for each fixed number.

Q: How do you avoid duplicate triplets?
- [x] Skip equal neighbouring values for the fixed number and pointers
- [ ] Use a list
- [ ] Ignore duplicates
> Sorting makes duplicates adjacent.

Q: Overall complexity?
- [x] O(n²)
- [ ] O(n³)
- [ ] O(n log n)
> n fixed numbers × O(n) scan.

## problem-trapping-rain-water
Q: Water above bar i equals…
- [x] min(maxLeft, maxRight) - height[i] (if positive)
- [ ] height[i]
- [ ] maxLeft + maxRight
> Bounded by the lower wall.

Q: Optimal space?
- [x] O(1) with two pointers
- [ ] O(n²)
- [ ] O(n) is required
> Move the side with the smaller max.

Q: Answer for [0,1,0,2,1,0,1,3,2,1,2,1]?
- [x] 6
- [ ] 4
- [ ] 9
> The classic example.

## problem-best-time-to-buy-and-sell-stock
Q: Track while scanning…
- [x] The minimum price so far and the best profit
- [ ] The maximum price only
- [ ] Every pair
> profit = price - minSoFar.

Q: Complexity?
- [x] O(n) time, O(1) space
- [ ] O(n²)
- [ ] O(n log n)
> One pass.

Q: If prices only fall, the answer is…
- [x] 0
- [ ] Negative
- [ ] The largest drop
> Don't trade.

## problem-longest-substring-without-repeating
Q: Pattern?
- [x] Variable sliding window
- [ ] Binary search
- [ ] DP table
> Expand right, shrink left on a repeat.

Q: What does the window track?
- [x] Characters (or last indexes) inside it
- [ ] Sorted letters
- [ ] Counts of all strings
> A set or map.

Q: Answer for 'abcabcbb'?
- [x] 3
- [ ] 2
- [ ] 8
> 'abc'.

## problem-minimum-window-substring
Q: When do you shrink the window?
- [x] When it contains all required characters
- [ ] Every step
- [ ] Never
> Shrink to find the minimum.

Q: What do you track?
- [x] Counts needed and how many requirements are satisfied
- [ ] Only the window length
- [ ] A stack
> Avoid re-checking the whole map.

Q: Complexity?
- [x] O(m + n)
- [ ] O(m × n)
- [ ] O(n²)
> Each pointer moves at most n times.

## problem-subarray-sum-equals-k
Q: Why not a sliding window?
- [x] Negative numbers break the shrink logic
- [ ] Windows are slow
- [ ] It works fine
> Use prefix sums.

Q: What does the map store?
- [x] How many times each prefix sum has appeared
- [ ] Indexes of zeros
- [ ] Sorted sums
> Count prefix - k each step.

Q: Initial map entry?
- [x] {0: 1}
- [ ] {}
- [ ] {k: 1}
> Subarrays starting at index 0.

## problem-maximum-subarray
Q: Algorithm?
- [x] Kadane's
- [ ] Dijkstra's
- [ ] Merge sort
> current = max(x, current + x).

Q: For all-negative arrays, the answer is…
- [x] The largest (least negative) number
- [ ] 0
- [ ] The sum
> Initialise with nums[0].

Q: Complexity?
- [x] O(n)
- [ ] O(n²)
- [ ] O(n log n)
> Single pass.

## problem-binary-search
Q: Safe midpoint in languages with overflow?
- [x] lo + (hi - lo) // 2
- [ ] (lo + hi) * 2
- [ ] hi - lo
> Avoids integer overflow.

Q: Loop condition for the classic template?
- [x] while lo <= hi
- [ ] while lo < hi + 2
- [ ] while True
> Then move lo = mid + 1 or hi = mid - 1.

Q: Not found returns…
- [x] -1
- [ ] 0
- [ ] lo always
> As the problem specifies.

## problem-search-in-rotated-sorted-array
Q: Key observation?
- [x] At least one half around mid is sorted
- [ ] The array is fully sorted
- [ ] Use a hash map
> Check if the target lies in the sorted half.

Q: Complexity?
- [x] O(log n)
- [ ] O(n)
- [ ] O(n log n)
> Still binary search.

Q: What breaks the simple version?
- [x] Duplicates
- [ ] Negative numbers
- [ ] Even length
> Duplicates can hide which half is sorted.

## problem-koko-eating-bananas
Q: What do you binary search over?
- [x] The eating speed k
- [ ] The piles' indexes
- [ ] The hours
> Binary search on the answer.

Q: Hours at speed k?
- [x] sum(ceil(pile / k))
- [ ] sum(pile) / k only
- [ ] max(pile) / k
> Each pile takes whole hours.

Q: Search range?
- [x] 1 to max(piles)
- [ ] 0 to h
- [ ] 1 to sum(piles) always
> Speed max(piles) finishes any pile in an hour.

## problem-valid-parentheses
Q: Data structure?
- [x] Stack
- [ ] Queue
- [ ] Heap
> Push openers, match closers.

Q: When is the string invalid?
- [x] A closer doesn't match the top, or the stack isn't empty at the end
- [ ] It has more than 2 brackets
- [ ] It starts with (
> Both conditions matter.

Q: Is '([)]' valid?
- [x] No
- [ ] Yes
- [ ] Depends on length
> Brackets must close in order.

## problem-min-stack
Q: How is getMin O(1)?
- [x] Store the current minimum with each pushed item (or a second stack)
- [ ] Sort on every call
- [ ] Scan the stack
> Pop restores the previous min.

Q: Which operations must be O(1)?
- [x] push, pop, top and getMin
- [ ] Only push
- [ ] Only getMin
> All four.

Q: Extra space?
- [x] O(n)
- [ ] O(1)
- [ ] O(n²)
> One min per element.

## problem-daily-temperatures
Q: Pattern?
- [x] Monotonic stack of indexes
- [ ] Two pointers
- [ ] Binary search
> Pop colder days when a warmer one arrives.

Q: Answer for a popped index i at day j is…
- [x] j - i
- [ ] temps[j]
- [ ] 1
> Days waited.

Q: Complexity?
- [x] O(n)
- [ ] O(n²)
- [ ] O(n log n)
> Each index pushed and popped once.

## problem-largest-rectangle-in-histogram
Q: Pattern?
- [x] Monotonic increasing stack
- [ ] Sliding window
- [ ] Greedy sort
> Pop when a shorter bar arrives.

Q: When a bar is popped, its rectangle's width is…
- [x] From the new stack top + 1 to the current index - 1
- [ ] Always 1
- [ ] The whole array
> It's bounded by smaller bars on both sides.

Q: Answer for [2,1,5,6,2,3]?
- [x] 10
- [ ] 12
- [ ] 6
> Bars 5 and 6, width 2.

## problem-reverse-linked-list
Q: Iterative approach uses…
- [x] prev, curr and next pointers
- [ ] A stack only
- [ ] Sorting
> Point curr.next back to prev.

Q: Space for the iterative version?
- [x] O(1)
- [ ] O(n)
- [ ] O(n²)
> Recursion would use O(n) stack.

Q: What is returned?
- [x] prev (the new head)
- [ ] curr
- [ ] The old head
> curr ends as None.

## problem-merge-two-sorted-lists
Q: Handy trick?
- [x] A dummy head node
- [ ] Reversing both
- [ ] Converting to arrays
> Return dummy.next.

Q: At each step attach…
- [x] The smaller of the two current nodes
- [ ] The larger one
- [ ] Both
> Then advance that list.

Q: When one list ends…
- [x] Attach the rest of the other list
- [ ] Stop and drop it
- [ ] Start over
> It's already sorted.

## problem-linked-list-cycle
Q: Algorithm?
- [x] Floyd's fast and slow pointers
- [ ] Sorting
- [ ] Binary search
> They meet if there's a cycle.

Q: Space?
- [x] O(1)
- [ ] O(n)
- [ ] O(log n)
> A visited set would be O(n).

Q: If fast reaches None…
- [x] There's no cycle
- [ ] There is a cycle
- [ ] Restart
> The list ends.

## problem-lru-cache
Q: Classic structure?
- [x] Hash map + doubly linked list
- [ ] Array only
- [ ] Heap
> O(1) get and put.

Q: What happens on get?
- [x] Move the item to the most-recent end
- [ ] Delete it
- [ ] Nothing
> It was just used.

Q: When capacity is exceeded…
- [x] Evict the least recently used item
- [ ] Evict the newest
- [ ] Double capacity
> From the old end of the list.

## problem-maximum-depth-of-binary-tree
Q: Recursive formula?
- [x] 1 + max(depth(left), depth(right))
- [ ] depth(left) + depth(right)
- [ ] 1 + min(...)
> Empty tree depth 0.

Q: Iterative approach?
- [x] BFS counting levels
- [ ] Binary search
- [ ] Sorting
> Each level adds 1.

Q: Complexity?
- [x] O(n)
- [ ] O(log n)
- [ ] O(n²)
> Visit every node.

## problem-invert-binary-tree
Q: What do you do at each node?
- [x] Swap its left and right children
- [ ] Delete it
- [ ] Sort it
> Then recurse.

Q: Base case?
- [x] Node is None → return None
- [ ] Leaf → error
- [ ] Root only
> Empty subtrees stay empty.

Q: Complexity?
- [x] O(n)
- [ ] O(1)
- [ ] O(n log n)
> Each node once.

## problem-binary-tree-level-order-traversal
Q: Structure?
- [x] A queue (BFS)
- [ ] A stack
- [ ] A heap
> Process level by level.

Q: How do you know a level ended?
- [x] Process len(queue) nodes per round
- [ ] Count to 2
- [ ] Use recursion depth only
> Snapshot the size first.

Q: Output shape?
- [x] A list of lists, one per level
- [ ] A flat list
- [ ] A dict
> [[3], [9, 20], [15, 7]].

## problem-validate-binary-search-tree
Q: Why isn't checking only parent-child enough?
- [x] Every node must fit the bounds of all ancestors
- [ ] It is enough
- [ ] Leaves don't count
> Pass (low, high) bounds down.

Q: Another valid check?
- [x] Inorder traversal must be strictly increasing
- [ ] Preorder must be sorted
- [ ] Count nodes
> BST inorder is sorted.

Q: Complexity?
- [x] O(n)
- [ ] O(log n)
- [ ] O(n²)
> Each node once.

## problem-lowest-common-ancestor
Q: Recursive idea?
- [x] If p and q are found in different subtrees, the current node is the LCA
- [ ] Always return the root
- [ ] Sort the nodes
> Return the non-null side otherwise.

Q: If the current node is p or q…
- [x] Return it
- [ ] Ignore it
- [ ] Return None
> A node can be its own ancestor.

Q: For a BST, you can instead…
- [x] Walk down using the values of p and q
- [ ] Use BFS only
- [ ] Sort
> Split point is the LCA.

## problem-kth-largest-element
Q: Heap approach?
- [x] Min-heap of size k; its top is the answer
- [ ] Max-heap of size 1
- [ ] Stack
> O(n log k).

Q: Average O(n) approach?
- [x] Quickselect
- [ ] Bubble sort
- [ ] BFS
> Partition like quick sort.

Q: Is it the k-th distinct largest?
- [x] No, duplicates count
- [ ] Yes
- [ ] Only for k=1
> [3,2,3,1,2,4,5,5,6], k=4 → 4.

## problem-merge-k-sorted-lists
Q: Efficient approach?
- [x] Min-heap of the k current heads
- [ ] Merge one by one with a nested loop
- [ ] Sort all values then rebuild
> O(N log k).

Q: Alternative?
- [x] Divide and conquer merging pairs
- [ ] Reverse all lists
- [ ] Use a stack
> Also O(N log k).

Q: What goes into the heap?
- [x] (value, list index, node)
- [ ] Only nodes
- [ ] Whole lists
> The index breaks ties safely.

## problem-number-of-islands
Q: Approach?
- [x] Flood fill (DFS/BFS) from each unvisited land cell
- [ ] Sort the grid
- [ ] Binary search
> Count how many fills you start.

Q: Why mark cells visited?
- [x] So each island is counted once
- [ ] To speed up sorting
- [ ] It's optional
> Or overwrite '1' with '0'.

Q: Complexity?
- [x] O(rows × cols)
- [ ] O(n²) per cell
- [ ] O(log n)
> Each cell once.

## problem-rotting-oranges
Q: Algorithm?
- [x] Multi-source BFS from all rotten oranges
- [ ] DFS from one orange
- [ ] DP
> Levels are minutes.

Q: When is the answer -1?
- [x] A fresh orange is unreachable
- [ ] No oranges
- [ ] All rotten at start
> Count fresh oranges.

Q: Answer when there are no fresh oranges?
- [x] 0
- [ ] -1
- [ ] 1
> Nothing needs to rot.

## problem-course-schedule
Q: You can finish all courses when…
- [x] The prerequisite graph has no cycle
- [ ] There are fewer than 10 courses
- [ ] Every course has a prerequisite
> Topological order exists.

Q: Kahn's algorithm counts…
- [x] How many nodes were processed with in-degree 0
- [ ] Edges
- [ ] Leaves
> All processed → no cycle.

Q: Complexity?
- [x] O(V + E)
- [ ] O(V²) always
- [ ] O(log V)
> Linear in graph size.

## problem-network-delay-time
Q: Algorithm?
- [x] Dijkstra from the source
- [ ] BFS ignoring weights
- [ ] Kruskal
> Weighted shortest paths.

Q: The answer is…
- [x] The maximum shortest distance to any node
- [ ] The sum of distances
- [ ] The minimum edge
> When the last node hears the signal.

Q: If some node is unreachable…
- [x] Return -1
- [ ] Return 0
- [ ] Ignore it
> Not every node received the signal.

## problem-word-ladder
Q: Algorithm?
- [x] BFS where words are nodes differing by one letter
- [ ] DFS for any path
- [ ] Sorting
> BFS gives the shortest sequence.

Q: Speed-up for neighbours?
- [x] Wildcard patterns like h*t mapping to words
- [ ] Compare every pair each time
- [ ] Sort the dictionary
> Pre-group words by pattern.

Q: If endWord isn't in the list…
- [x] Return 0
- [ ] Return 1
- [ ] Add it
> No transformation is possible.

## problem-subsets
Q: How many subsets for n items?
- [x] 2ⁿ
- [ ] n!
- [ ] n²
> Each item in or out.

Q: Backtracking choice at each index?
- [x] Include or exclude the item
- [ ] Sort or not
- [ ] Push twice
> Then recurse.

Q: Why copy the current path when saving?
- [x] The path list keeps changing as you backtrack
- [ ] For speed
- [ ] It's not needed
> Append path[:] (a copy).

## problem-permutations
Q: How many permutations of n distinct items?
- [x] n!
- [ ] 2ⁿ
- [ ] n²
> 3 items → 6.

Q: How do you avoid reusing an item?
- [x] Track used items (a set or boolean array)
- [ ] Sort first
- [ ] Use a heap
> Un-mark when backtracking.

Q: Complexity is about…
- [x] O(n · n!)
- [ ] O(n)
- [ ] O(2ⁿ)
> n! results of length n.

## problem-combination-sum
Q: Can a candidate be reused?
- [x] Yes, unlimited times
- [ ] No
- [ ] Only once more
> Recurse with the same index.

Q: Pruning idea?
- [x] Sort candidates and stop when one exceeds the remaining target
- [ ] Never prune
- [ ] Use BFS
> Later ones are larger.

Q: How do you avoid duplicate combinations like [2,3] and [3,2]?
- [x] Only consider candidates from the current index onward
- [ ] Use a set of lists
- [ ] Reverse results
> Order is fixed.

## problem-climbing-stairs
Q: Recurrence?
- [x] ways(n) = ways(n-1) + ways(n-2)
- [ ] ways(n) = 2 × ways(n-1)
- [ ] ways(n) = n
> Last step is 1 or 2.

Q: ways(5) equals…
- [x] 8
- [ ] 5
- [ ] 10
> 1, 2, 3, 5, 8.

Q: Space-optimised version uses…
- [x] Two variables
- [ ] An n×n table
- [ ] A stack
> Like Fibonacci.

## problem-house-robber
Q: Recurrence?
- [x] best(i) = max(best(i-1), best(i-2) + nums[i])
- [ ] best(i) = sum(nums)
- [ ] best(i) = nums[i]
> Skip or rob house i.

Q: Answer for [2,7,9,3,1]?
- [x] 12
- [ ] 11
- [ ] 10
> 2 + 9 + 1.

Q: Space?
- [x] O(1) with two variables
- [ ] O(n²)
- [ ] O(n log n)
> Only the last two values matter.

## problem-coin-change
Q: dp[a] means…
- [x] The minimum coins to make amount a
- [ ] The number of coins available
- [ ] The largest coin
> dp[0] = 0.

Q: Why does greedy (largest coin first) fail?
- [x] Some coin systems need smaller coins, e.g. coins [1,3,4] for 6
- [ ] It's always slower
- [ ] It works always
> Greedy gives 4+1+1, optimal is 3+3.

Q: If amount can't be made…
- [x] Return -1
- [ ] Return 0
- [ ] Return infinity
> dp stays at infinity.

## problem-longest-increasing-subsequence
Q: Simple DP complexity?
- [x] O(n²)
- [ ] O(n)
- [ ] O(2ⁿ)
> dp[i] = 1 + max dp[j] for j < i with smaller value.

Q: Faster approach?
- [x] Patience sorting with binary search, O(n log n)
- [ ] Sorting the array
- [ ] BFS
> Keep smallest tails.

Q: Answer for [10,9,2,5,3,7,101,18]?
- [x] 4
- [ ] 3
- [ ] 5
> 2, 3, 7, 101.

## problem-longest-common-subsequence
Q: If text1[i] == text2[j]…
- [x] dp[i][j] = 1 + dp[i-1][j-1]
- [ ] dp[i][j] = 0
- [ ] dp[i][j] = max(...) only
> Otherwise take the max of skipping one.

Q: Subsequence vs substring?
- [x] A subsequence need not be contiguous
- [ ] They're identical
- [ ] Substrings skip letters
> Order matters, gaps allowed.

Q: Complexity?
- [x] O(m × n)
- [ ] O(m + n)
- [ ] O(2^(m+n))
> Fill the table.

## problem-edit-distance
Q: Allowed operations?
- [x] Insert, delete, replace
- [ ] Swap only
- [ ] Sort
> Each costs 1.

Q: Edit distance from 'horse' to 'ros'?
- [x] 3
- [ ] 2
- [ ] 4
> The classic example.

Q: If characters match, dp[i][j] is…
- [x] dp[i-1][j-1]
- [ ] dp[i-1][j-1] + 1
- [ ] 0
> No operation needed.

## problem-merge-intervals
Q: First step?
- [x] Sort by start
- [ ] Sort by length
- [ ] Reverse
> Then merge neighbours.

Q: When do two sorted intervals overlap?
- [x] next.start <= current.end
- [ ] next.end < current.start
- [ ] Never
> Extend the end with max.

Q: Complexity?
- [x] O(n log n)
- [ ] O(n²)
- [ ] O(n)
> Dominated by sorting.

## problem-meeting-rooms-ii
Q: Approach?
- [x] Sort by start; min-heap of end times
- [ ] Count meetings
- [ ] Sort by duration
> Reuse a room if the earliest ending meeting finished.

Q: The answer is…
- [x] The maximum heap size
- [ ] The number of meetings
- [ ] The longest meeting
> Rooms in use at the peak.

Q: Alternative?
- [x] Sweep line over sorted starts and ends
- [ ] BFS
- [ ] Union-find
> Count overlaps.

## problem-jump-game
Q: Greedy idea?
- [x] Track the furthest index reachable so far
- [ ] Try every path
- [ ] Sort jumps
> Fail if i > furthest.

Q: Complexity?
- [x] O(n)
- [ ] O(n²)
- [ ] O(2ⁿ)
> One pass.

Q: For [3,2,1,0,4] the answer is…
- [x] False
- [ ] True
- [ ] Depends
> Everyone gets stuck at index 3.
