---
title: "Capstone: Coding Agent"
slug: capstone-agent-coding
subject: agent-capstones
lesson: 3
difficulty: advanced
excerpt: Build a small coding agent that fixes a real bug: tools to read files, make exact edits and run tests in an isolated folder, a loop that reads the failure and patches the code, a diff for review, and the safety rules real coding agents follow.
---

Coding agents like Claude Code, Copilot's agent mode and Cursor's agent all follow the same loop: **run the tests, read the failure, look at the code, make a small edit, run the tests again**. In this capstone you build a mini version that really edits files and really runs tests, inside a throwaway folder.

::scene The discount bug
🛒 | Bug report | "A 10% discount on ₹300 gives ₹290!" The discount is subtracted as rupees, not percent. | dev
🧪 | Run tests | Chip runs the tests: one fails, total was 290 but should be 270. | chip
🔧 | Exact edit | Byte reads cart.py and replaces one exact line. | byte
✅ | Green | Tests pass. Bugsy reviews the diff before anything is merged. | bugsy
::end

## The workspace and the tools

```python
import difflib
import shutil
import subprocess
import sys
import tempfile
from pathlib import Path

work = Path(tempfile.mkdtemp())                      # an isolated, throwaway project folder
(work / "cart.py").write_text("def total(prices, discount=0):\n    return sum(prices) - discount\n")
(work / "test_cart.py").write_text(
    "from cart import total\n"
    "assert total([100, 200]) == 300\n"
    "assert total([100, 200], discount=10) == 270, total([100, 200], discount=10)\n"
    "print('all tests passed')\n")

def inside(path):
    p = (work / path).resolve()
    if not p.is_relative_to(work.resolve()):
        raise PermissionError("path outside the workspace")
    return p

def read_file(path):
    return inside(path).read_text()

def edit_file(path, old, new):
    text = read_file(path)
    if text.count(old) != 1:
        return f"error: expected exactly one match, found {text.count(old)}"
    inside(path).write_text(text.replace(old, new))
    return "edited"

def run_tests():
    p = subprocess.run([sys.executable, "test_cart.py"], cwd=work, capture_output=True, text=True, timeout=10)
    lines = (p.stdout + p.stderr).strip().splitlines()
    return ("PASS" if p.returncode == 0 else "FAIL") + ": " + lines[-1]
```

These are the same kinds of tools real coding agents have: read, **exact-match edit** and run a command, all restricted to the project folder, with a timeout.

## The agent loop

The decisions are scripted (what a model would choose after seeing each result), but the edits and tests are real:

```python
original = read_file("cart.py")
steps = [("run_tests", ()),
         ("read_file", ("cart.py",)),
         ("edit_file", ("cart.py", "sum(prices) - discount", "sum(prices) * (100 - discount) / 100")),
         ("run_tests", ())]
TOOLS = {"run_tests": run_tests, "read_file": read_file, "edit_file": edit_file}

for name, args in steps:
    result = TOOLS[name](*args)
    print(f"{name}: {result.strip().splitlines()[-1] if name == 'read_file' else result}")
    if name == "run_tests" and result.startswith("PASS"):
        break

diff = difflib.unified_diff(original.splitlines(), read_file("cart.py").splitlines(), "a/cart.py", "b/cart.py", lineterm="")
print("\n".join(list(diff)[2:]))
shutil.rmtree(work)
```

Output:

```text
run_tests: FAIL: AssertionError: 290
read_file:     return sum(prices) - discount
edit_file: edited
run_tests: PASS: all tests passed
@@ -1,2 +1,2 @@
 def total(prices, discount=0):
-    return sum(prices) - discount
+    return sum(prices) * (100 - discount) / 100
```

The first test run gave the agent the exact clue (`290`), the edit was one precise change, and the second run proved it. The diff is what a human reviews before merging.

::walk python | Why edits need an exact, unique match
text = "return sum(prices) - discount"
old = "sum(prices) - discount"
count = text.count(old)
new_text = text.replace(old, "sum(prices) * (100 - discount) / 100") if count == 1 else text
print(count, new_text)
::notes
1: A line from the file. // vars: text=return sum(prices) - discount
2: The text the model wants to replace. // vars: old=sum(prices) - discount
3: How many times does it appear? // vars: count=1
4: Edit only when it appears exactly once; otherwise refuse and ask the model to include more surrounding text. // deep: Exact-match edits are how many real coding agents edit files: they're small, reviewable and can't silently change the wrong place. Rewriting whole files invites accidental changes elsewhere.
5: One match, one precise change. // out: 1 return sum(prices) * (100 - discount) / 100
::end

## The real version

- **Model:** a tool loop (Tool Use module) with these tools plus `list_files` and `search` (grep). Or build on the **Claude Agent SDK**, which already provides read, edit, search and shell tools with permissions.
- **Instructions:** "Reproduce the bug with a test first. Make the smallest change. Run the tests. Never edit tests to make them pass. Stop after 10 steps."
- **Sandbox:** run tests in a container with no network and the project mounted (Safety module).
- **Output:** a branch and a pull request with the diff, test results and a short explanation, for a human to review.

## Safety rules for coding agents

| Rule | Why |
|---|---|
| Work on a branch or a copy, never directly on main | Easy to throw away |
| Restrict file tools to the project folder | No touching `~/.ssh` or system files |
| Allow-list commands (tests, linters) | No `rm -rf`, no `curl … | sh` |
| Timeouts on every command | No hanging builds |
| No secrets in the environment | Model-written code can't leak them |
| Human review of every diff | The agent proposes; people decide |

## Portfolio ideas

- Point it at a small repo of your own with 5 planted bugs; report how many it fixes and in how many steps.
- Add a "write a failing test first" step and measure if it improves the fix rate.
- Compare two models or prompts on the same bug set (that's an eval!).

::tip Coding agents are only as good as the tests. If a project has no tests, the agent's first job should be writing them, with a human checking they describe the right behaviour.

## Summary

- Coding agents loop: run tests → read failure → inspect code → exact edit → re-run tests.
- Tools are restricted to the workspace, edits are exact and unique, commands have timeouts.
- The diff and test results go to a human for review.
- Sandboxes, branches and command allow-lists keep it safe.
