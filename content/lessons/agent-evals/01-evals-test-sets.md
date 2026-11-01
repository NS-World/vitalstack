---
title: "Test Sets for Agents"
slug: evals-test-sets
subject: agent-evals
lesson: 1
difficulty: advanced
excerpt: Evaluate agents like a pro: tasks with checkable end states, trajectory checks on which tools were used, efficiency in steps, running each case several times, pass@k versus pass^k, and growing the suite from real failures.
---

Testing a chatbot means checking one answer. Testing an **agent** means checking a whole journey: did it reach the right **end state**, did it use the right **tools**, how many **steps** did it take, and does it succeed **every time** or just sometimes? Agents are non-deterministic, so one lucky run proves little.

::scene Exam for the refund agent
📋 | Test cases | Byte writes 3 tasks with the correct end state and the right tool for each. | byte
🔁 | Three runs each | Chip runs every case 3 times. Agents don't always behave the same. | chip
⚠️ | Wobble | One run refunds an order that wasn't even delivered. Bugsy flags it. | bugsy
📊 | Report | Dev sees which cases are reliable (3/3) and which aren't. | dev
::end

## What to check

| Check | Question | Example |
|---|---|---|
| **End state** | Is the world in the right state afterwards? | Order 1042 status = "refunded" |
| **Trajectory** | Did it use the right tools, and avoid forbidden ones? | Called `refund`, never called `delete_account` |
| **Efficiency** | How many steps or tokens? | ≤ 5 steps |
| **Answer** | Did the final message say the right thing? | Mentions the refund amount |
| **Reliability** | Does it pass on every run? | 3/3 runs pass |

End-state checks are the most valuable: they test what actually happened, not what the agent *said* happened.

## A runnable agent eval

The "agent" is simulated: it usually does the right thing but sometimes refunds by mistake, like a real agent having an off day.

```python
import random

cases = [
    {"id": "refund-delivered", "expect_tool": "refund", "expect_state": "refunded"},
    {"id": "refund-not-delivered", "expect_tool": "escalate", "expect_state": "escalated"},
    {"id": "track-order", "expect_tool": "track", "expect_state": "unchanged"},
]

def fake_agent(case, rng):
    if rng.random() < 0.2:                                   # an occasional wrong decision
        return {"tools": ["refund"], "state": "refunded", "steps": 6}
    return {"tools": [case["expect_tool"]], "state": case["expect_state"], "steps": 3}

rng = random.Random(7)
k = 3
for case in cases:
    runs = [fake_agent(case, rng) for _ in range(k)]
    passed = [r["state"] == case["expect_state"] and case["expect_tool"] in r["tools"] for r in runs]
    steps = sum(r["steps"] for r in runs) / k
    print(f"{case['id']:<21} {sum(passed)}/{k}  all passed: {all(passed)!s:<5}  avg steps: {steps:.1f}")
```

Output:

```text
refund-delivered      3/3  all passed: True   avg steps: 4.0
refund-not-delivered  2/3  all passed: False  avg steps: 4.0
track-order           1/3  all passed: False  avg steps: 5.0
```

A single run per case could easily have shown "all green". Running each case several times exposes the wobble.

## pass@k vs pass^k

::walk python | Two ways to count success
p = 0.9
k = 3
pass_at_k = 1 - (1 - p) ** k
pass_hat_k = p ** k
print(round(pass_at_k, 3), round(pass_hat_k, 3))
::notes
1: Suppose the agent solves a task 90% of the time. // vars: p=0.9
2: We try it 3 times. // vars: k=3
3: pass@k: the chance that **at least one** of 3 tries succeeds. Useful when a human picks the best attempt. // vars: pass_at_k=0.999
4: pass^k: the chance that **all** 3 tries succeed. That's what matters when real customers each get one try. // deep: An agent that's "90% accurate" fails about 1 in 4 groups of three customers. For customer-facing agents, track pass^k: reliability, not best-case ability. // vars: pass_hat_k=0.729
5: Nearly certain to succeed once, but only 73% to succeed every time. // out: 0.999 0.729
::end

## Building the suite

1. **Start with 20–30 real tasks** from logs or the people who'll use the agent, each with a checkable end state.
2. **Include the hard cases:** missing information, impossible requests, policy edge cases, prompt-injection attempts.
3. **Add a case for every bug** you fix, so it can't return.
4. **Use a sandbox environment** (a test database, fake email) so the agent can act for real without harm, and reset it before every run.
5. **Track results over time**: pass rate, pass^k, average steps, cost per task.

## Simulated users

For conversational agents, a second LLM can **play the user** ("You're a customer whose order is late; you get annoyed if asked the same thing twice"), so you can test multi-turn conversations automatically.

::tip Read failing transcripts, not just scores. A failure might be the agent's fault, or a badly written test (an "expected" answer that was actually wrong). Fix whichever is broken.

## Summary

- Agent evals check end state, trajectory, efficiency, answer and reliability.
- Run each case several times: agents are non-deterministic.
- pass@k measures "can it ever?"; pass^k measures "does it always?".
- Build suites from real tasks, hard cases and fixed bugs, in a resettable sandbox.
