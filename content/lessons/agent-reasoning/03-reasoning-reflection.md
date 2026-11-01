---
title: "Reflection and Self-Correction"
slug: reasoning-reflection
subject: agent-reasoning
lesson: 3
difficulty: intermediate
excerpt: Let agents check and improve their own work: the generate-critique-revise loop, why real feedback (tests, tools, sources) beats self-opinion, a runnable code agent that fixes itself from failing tests, and when to stop.
---

Humans rarely get things right first time: we write, check and fix. **Reflection** gives agents the same habit: produce a draft, **critique** it, then **revise**. It works best when the critique uses **real feedback** (test results, tool output, sources) instead of the model just asking itself "Is this good?".

::scene Draft, check, fix
✍️ | Draft 1 | Chip writes a slugify() function. It turns "Python & AI!" into "--python-&-ai!--". Ugly. | chip
🧪 | Check | Byte's tests fail and report exactly which input broke. | byte
🔧 | Draft 2, 3 | Chip reads the failures and fixes the code twice. | dev
🏁 | Pass | All tests pass. Bugsy notes the loop had a 3-round limit, just in case. | bugsy
::end

## The loop

```
generate → critique (with real feedback if possible) → revise → … until good or out of rounds
```

## A self-correcting code agent

Each draft below is what a model might write after seeing the previous draft's failing tests:

```python
DRAFTS = [
    "def slugify(t):\n    return t.lower().replace(' ', '-')",
    "import re\ndef slugify(t):\n    return re.sub(r'[^a-z0-9]+', '-', t.lower())",
    "import re\ndef slugify(t):\n    return re.sub(r'[^a-z0-9]+', '-', t.lower()).strip('-')",
]
TESTS = [("Hello World", "hello-world"), ("  Python & AI!  ", "python-ai"), ("Top 10 Tips", "top-10-tips")]

def run_tests(code):
    ns = {}
    exec(code, ns)                       # in a real agent: run in a sandbox (see the Safety module)
    return [(given, ns["slugify"](given), want) for given, want in TESTS if ns["slugify"](given) != want]

for attempt, draft in enumerate(DRAFTS, 1):
    failed = run_tests(draft)
    print(f"draft {attempt}: {len(TESTS) - len(failed)}/{len(TESTS)} passed", failed[:1])
    if not failed:
        break
```

Output:

```text
draft 1: 2/3 passed [('  Python & AI!  ', '--python-&-ai!--', 'python-ai')]
draft 2: 2/3 passed [('  Python & AI!  ', '-python-ai-', 'python-ai')]
draft 3: 3/3 passed []
```

The critique here isn't an opinion: it's the exact failing input, what the code returned and what was expected. That's the most useful feedback a model can get.

## Critique with a rubric

When there are no tests (an email, a summary, a plan), give the critic a rubric and ask for a yes/no per item:

::walk python | Turning a critique into an action
critique = {"correct": True, "clear": False, "under_100_words": True}
issues = [k for k, ok in critique.items() if not ok]
action = "revise: " + ", ".join(issues) if issues else "accept"
print(action)
::notes
1: The critic model's verdict on each rubric item, returned as JSON. // vars: critique=3 checks
2: Collect the items that failed. // vars: issues=['clear']
3: Revise only what's broken, or accept if nothing is. // deep: Specific critiques ("not clear: the second sentence uses 'idempotent' without explaining it") help far more than "make it better". Ask the critic to quote the problem and suggest a fix.
4: The next generation call gets the draft plus "fix: clear". // out: revise: clear
::end

```python
import anthropic

client = anthropic.Anthropic()
def critique(draft, task):
    r = client.messages.create(model="claude-opus-5-5", max_tokens=300,
        system=("You review drafts. Reply with JSON: {\"correct\": bool, \"clear\": bool, "
                "\"under_100_words\": bool, \"fix\": \"one specific suggestion\"}"),
        messages=[{"role": "user", "content": f"Task: {task}\n\nDraft:\n{draft}"}])
    return r.content[0].text
```

## When reflection helps (and when it doesn't)

| Helps a lot | Helps little |
|---|---|
| Code with tests | "Is this answer correct?" with no way to check |
| Answers checked against sources | Facts the model doesn't know (it can't self-correct missing knowledge) |
| Format and rubric checks | Taste and style, where the critic just disagrees with itself |

Without external feedback, a model may "fix" a correct answer into a wrong one. Prefer grounded signals: tests, tool results, retrieved sources, validators.

## Stopping rules

- A maximum number of rounds (2–3 is common).
- Stop when the checks pass.
- Stop if a revision makes the score worse, and keep the best version so far.
- Track cost: each round is at least one more model call.

::tip Use a different prompt (or even a different model) for the critic than for the writer. A separate "reviewer" role catches more problems than asking the writer to grade its own homework.

## Summary

- Reflection = generate → critique → revise, in a loop with a limit.
- Real feedback (tests, sources, validators) makes self-correction reliable.
- Use a rubric with yes/no items when there are no tests.
- Stop on success, on a round limit, or when quality stops improving.

🎉 You've finished **Reasoning Patterns**.
