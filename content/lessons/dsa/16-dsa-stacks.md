---
title: Stacks
slug: dsa-stacks
subject: dsa
lesson: 16
difficulty: beginner
excerpt: Last in, first out. Use Python lists as stacks, match brackets (animated), build a min stack, evaluate expressions, simplify file paths and decode strings.
---

A **stack** is a pile where you can only add to the top and take from the top: **Last In, First Out (LIFO)**. It's one of the simplest data structures, and it quietly powers undo buttons, the browser back button, bracket checking in code editors and every function call your program makes.

## The plate stack analogy

At a wedding buffet, clean plates are stacked. Staff put new plates **on top**; guests take plates **from the top**. Nobody pulls a plate from the middle. The last plate placed is the first plate taken.

| Operation | Meaning | Python list | Cost |
|---|---|---|---|
| push | add to the top | `stack.append(x)` | O(1) |
| pop | remove from the top | `stack.pop()` | O(1) |
| peek | look at the top | `stack[-1]` | O(1) |
| is empty | | `not stack` | O(1) |

```python
stack = []
stack.append("page1")
stack.append("page2")
stack.append("page3")
print(stack.pop())   # page3
print(stack[-1])     # page2
print(len(stack))    # 2
```

That's exactly how the **back button** works: each visited page is pushed; "back" pops.

## When to think "stack"

Use a stack when the **most recent** unfinished thing must be handled first:

- Brackets: the most recently opened bracket must close first.
- Undo: the most recent action is undone first.
- Nested structures: HTML tags, folders, function calls.
- "Go back to the previous state" in a walk (DFS uses a stack).

## Valid parentheses

::viz valid-parentheses {"s": "{[()()]}"}

```python
def is_valid(s):
    pairs = {")": "(", "]": "[", "}": "{"}
    stack = []
    for ch in s:
        if ch in "([{":
            stack.append(ch)
        elif not stack or stack.pop() != pairs[ch]:
            return False
    return not stack

print(is_valid("{[()()]}"))  # True
print(is_valid("([)]"))      # False
print(is_valid("(("))        # False
print(is_valid(""))          # True
```

Three ways to fail: a closing bracket with nothing open, a mismatch, or brackets left open at the end. Try your own strings in the visualizer, or play **Bracket Blitz**.

## Min stack: get the minimum in O(1)

Design a stack that also returns its smallest element instantly. Store, with each item, the minimum **at that moment**:

```python
class MinStack:
    def __init__(self):
        self.items = []            # pairs: (value, min so far)

    def push(self, x):
        current_min = min(x, self.items[-1][1]) if self.items else x
        self.items.append((x, current_min))

    def pop(self):
        return self.items.pop()[0]

    def top(self):
        return self.items[-1][0]

    def get_min(self):
        return self.items[-1][1]

s = MinStack()
for x in [5, 3, 7, 2]:
    s.push(x)
print(s.get_min())  # 2
s.pop()
print(s.get_min())  # 3
```

When 2 is popped, the minimum "remembered" by 7 is 3, so it's still correct. Every operation is O(1).

## Evaluate reverse Polish notation

In `["2", "1", "+", "3", "*"]`, numbers are pushed; an operator pops two numbers and pushes the result. This is how calculators and compilers evaluate expressions.

```python
def eval_rpn(tokens):
    stack = []
    for t in tokens:
        if t in "+-*/":
            b, a = stack.pop(), stack.pop()      # careful: b is popped first
            if t == "+": stack.append(a + b)
            elif t == "-": stack.append(a - b)
            elif t == "*": stack.append(a * b)
            else: stack.append(int(a / b))       # truncate toward zero
        else:
            stack.append(int(t))
    return stack[0]

print(eval_rpn(["2", "1", "+", "3", "*"]))                      # 9
print(eval_rpn(["4", "13", "5", "/", "+"]))                     # 6
```

## Simplify a file path

`"/home//user/./docs/../photos/"` → `"/home/user/photos"`. Folder names are pushed; `..` pops; `.` and empty parts are ignored.

```python
def simplify_path(path):
    stack = []
    for part in path.split("/"):
        if part == "..":
            if stack:
                stack.pop()
        elif part and part != ".":
            stack.append(part)
    return "/" + "/".join(stack)

print(simplify_path("/home//user/./docs/../photos/"))  # /home/user/photos
print(simplify_path("/../"))                           # /
```

## Decode string

`"3[a2[c]]"` → `"accaccacc"`. When we meet `[`, push the current text and repeat count; at `]`, pop and repeat:

```python
def decode_string(s):
    stack, current, num = [], "", 0
    for ch in s:
        if ch.isdigit():
            num = num * 10 + int(ch)
        elif ch == "[":
            stack.append((current, num))
            current, num = "", 0
        elif ch == "]":
            prev, times = stack.pop()
            current = prev + current * times
        else:
            current += ch
    return current

print(decode_string("3[a2[c]]"))     # accaccacc
print(decode_string("2[ab]3[x]y"))   # ababxxxy
```

The stack remembers the outer context while we work on the inner one. That's the essence of handling **nesting**.

## The call stack connection

Every function call is pushed onto the program's **call stack**, and returning pops it. That's why recursion (lesson 12) and stacks are two sides of the same coin: any recursive algorithm can be rewritten with an explicit stack, as you'll see with DFS.

## Interview corner

Valid Parentheses, Min Stack, Evaluate Reverse Polish Notation, Decode String, Simplify Path and Implement Queue using Stacks are commonly reported at **Amazon, Google, Microsoft, Meta, Bloomberg and Adobe**. Interviewers like candidates who say *why* a stack fits: "the most recent unmatched item must be handled first."

::tip Whenever a problem involves nesting or "undo the most recent", reach for a stack.

## Common mistakes

- **Popping from an empty stack** (`IndexError`): check `if stack` first.
- **Forgetting to check the stack is empty at the end** (unclosed brackets).
- **Operand order** in RPN: the second pop is the left operand.
- **Using `list.pop(0)`**, which is a queue operation and O(n).

## Exercise

1. Play the visualizer with `"(]"` and `"((({}))"`. Where does each one fail?
2. Write `remove_adjacent_duplicates(s)` ("abbaca" → "ca"). Hint: push letters; pop when the top equals the new letter.
3. Write `backspace_compare(s, t)` where `#` means backspace ("ab#c" and "ad#c" → True).

## Summary

- A stack is LIFO: push and pop at the top, both O(1).
- Use it for brackets, undo, nesting and "most recent first" problems.
- Store extra info with each item (like the running minimum) for O(1) queries.
- Recursion uses the call stack; DFS can use an explicit stack.

Next, you'll learn the **monotonic stack**, a stack that stays sorted and answers "next greater element" questions.
