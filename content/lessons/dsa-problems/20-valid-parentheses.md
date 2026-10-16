---
title: Valid Parentheses
slug: problem-valid-parentheses
subject: dsa-problems
lesson: 20
difficulty: beginner
level: Easy
pattern: Stack
companies: Amazon, Google, Meta, Microsoft, Bloomberg, LinkedIn, Spotify
excerpt: Are the brackets balanced and correctly nested? Push opening brackets on a stack; each closing bracket must match the top.
---

The classic stack problem. Your code editor does this every time it highlights a matching bracket.

## The problem

Given a string of `()[]{}` characters, return `True` if every bracket is closed by the same type, in the correct order.

## Examples

| s | answer |
|---|---|
| `"()[]{}"` | True |
| `"{[()()]}"` | True |
| `"(]"` | False |
| `"([)]"` | False |
| `"(("` | False |

## Hints

::hint Hint 1 | The most recently opened bracket must be the first one closed. "Last in, first out" means a stack.
::hint Hint 2 | On an opening bracket, push it. On a closing bracket, the top of the stack must be its partner.
::hint Hint 3 | At the end, the stack must be empty (nothing left open).

## The easy idea: stacking plates

Every opening bracket is a plate put on a pile. A closing bracket must match the **top** plate, and then removes it. If it doesn't match, or there's no plate, the string is invalid. Play it, then try your own brackets:

::viz valid-parentheses {"s": "{[()()]}"}

## Solution

```python
def is_valid(s):
    pairs = {")": "(", "]": "[", "}": "{"}
    stack = []
    for ch in s:
        if ch in pairs:                       # closing bracket
            if not stack or stack.pop() != pairs[ch]:
                return False
        else:
            stack.append(ch)                  # opening bracket
    return not stack

print(is_valid("()[]{}"))    # True
print(is_valid("{[()()]}"))  # True
print(is_valid("(]"))        # False
print(is_valid("([)]"))      # False
print(is_valid("(("))        # False
print(is_valid(")"))         # False
```

## Complexity

- **Time O(n)**, **space O(n)** for the stack in the worst case.

## Follow-ups

- **Only one bracket type?** A counter is enough: +1 for `(`, −1 for `)`, never below 0, ending at 0.
- **Minimum additions to make it valid?** Count unmatched opens and unmatched closes.

```python
def min_add_to_make_valid(s):
    open_count = additions = 0
    for ch in s:
        if ch == "(":
            open_count += 1
        elif open_count:
            open_count -= 1
        else:
            additions += 1          # a ")" with nothing to match
    return additions + open_count

print(min_add_to_make_valid("())"))   # 1
print(min_add_to_make_valid("((("))   # 3
```

Want to practise under time pressure? Play **Bracket Blitz** in the [Brain Games](/games/#play-bracket-blitz).

## Similar problems

Generate Parentheses, Longest Valid Parentheses, Minimum Remove to Make Valid Parentheses, Decode String.

Learn the pattern: [Stacks](/tutorials/dsa-stacks/).
