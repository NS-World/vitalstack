---
title: "Prompting for Code: Specs, Tests and Reviews"
slug: coding-assistants-prompting-for-code
subject: ai-coding-assistants
lesson: 2
difficulty: intermediate
excerpt: Get correct code from an AI assistant: write a clear spec with examples, write the tests first, let the tests catch the AI's first attempt, ask for small steps, and use the AI as a code reviewer.
---

"Write a function to validate phone numbers" gets you *a* function. "Write `is_valid_mobile(s)` for Indian mobile numbers; here are 5 examples it must pass" gets you **the** function you need. The secret of good AI coding is the same as good engineering: a clear spec, and tests that prove the code works.

::scene The phone number bug
📋 | Vague ask | Dev: "Validate phone numbers." The AI writes 10-digits-only code. | dev
🧪 | Tests first | Byte writes 5 examples, including "+91 98765 43210" and a number starting with 5. | byte
❌ | Caught | The first AI version fails 3 of the 5 tests. Nobody had to read it line by line. | bugsy
✅ | Fixed | Chip shows the failures to the AI. Version 2 passes them all. | chip
::end

## Step 1: a spec with examples

::walk python | Turning a spec into a prompt
spec = {"function": "is_valid_mobile(s)", "rules": ["10 digits starting 6-9", "allow +91 and spaces"], "examples": 5}
prompt = f"Write {spec['function']}. Rules: {'; '.join(spec['rules'])}. Make all {spec['examples']} tests pass. Don't change the tests."
print(prompt.split(".")[0], len(prompt.split()))
::notes
1: A spec is the name and signature, the rules and the examples. Write it before you ask. // vars: spec=function + 2 rules + 5 examples
2: Build the prompt from the spec. The last sentence matters: some assistants "fix" failing tests by editing the tests. // deep: For agent tools, add the test command too ("run pytest tests/test_phone.py"), so the agent can check its own work before it tells you it's done.
3: A short, precise 20-word prompt beats a long vague one. // out: Write is_valid_mobile(s) 20
::end

## Step 2: tests first, then let them judge the AI

```python
import re

CASES = [("9876543210", True), ("+91 9876543210", True), ("12345", False),
         ("5876543210", False), ("98765 43210", True)]

def failures(fn):
    return [(given, fn(given), want) for given, want in CASES if fn(given) != want]

def ai_version_1(s):                      # a typical first attempt from a vague prompt
    return s.isdigit() and len(s) == 10

def ai_version_2(s):                      # after showing the AI the failing tests
    s = re.sub(r"[\s-]", "", s)
    s = s[3:] if s.startswith("+91") else s
    return bool(re.fullmatch(r"[6-9]\d{9}", s))

print("v1 failures:", failures(ai_version_1))
print("v2 failures:", failures(ai_version_2))
```

Output:

```text
v1 failures: [('+91 9876543210', False, True), ('5876543210', True, False), ('98765 43210', False, True)]
v2 failures: []
```

Version 1 looked fine and would pass a quick glance. The tests found three real bugs instantly. That's why "tests first" is the most powerful AI coding habit.

## Step 3: small steps

Agents do better with a sequence of small, checkable tasks than one giant request:

1. "Read `orders.py` and explain how discounts are calculated. Don't change anything yet."
2. "Write failing tests for a 10% festive discount."
3. "Make the tests pass with the smallest change."
4. "Refactor for readability; keep all tests green."

Asking it to **explain or plan first** catches misunderstandings before any code is written.

## Step 4: the AI as reviewer

AI is also a tireless code reviewer. A useful review prompt:

```text
Review this diff as a senior developer. List, in order of severity:
1. Bugs or wrong behaviour (with the line).
2. Security problems (injection, secrets, unsafe input handling).
3. Missing tests or edge cases.
4. Readability improvements.
Say "no issues" for a category if there are none. Don't rewrite the code.
```

Use it on your own code before asking a human to review, and on AI-written code before you merge it.

## Prompt patterns that work

| Instead of | Try |
|---|---|
| "Make it better" | "Reduce this function to under 20 lines without changing behaviour; tests must pass" |
| "Fix the bug" | "This input gives X, expected Y. Here is the stack trace. Find the cause before fixing." |
| "Write an API" | "FastAPI endpoint POST /orders, body schema below, return 201 with the order id, 422 on bad input" |
| Pasting the whole project | Point to the 2–3 relevant files and the test command |

::tip When the AI gets something wrong twice, stop and rewrite your spec instead of arguing in the chat. A fresh, clearer prompt usually beats a long thread of corrections.

## Summary

- Give a spec: signature, rules and concrete examples.
- Write tests first; let them judge the AI's code, and tell it not to edit the tests.
- Work in small steps: explain → test → implement → refactor.
- Use AI as a reviewer with a clear checklist.
