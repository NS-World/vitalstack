---
title: "Choosing (or Skipping) a Framework"
slug: frameworks-choosing
subject: agent-frameworks
lesson: 4
difficulty: intermediate
excerpt: Plain code, LangGraph, CrewAI, the Claude Agent SDK, the OpenAI Agents SDK or a managed agent platform? A practical decision guide, a weighted scoring tool you can run with your own needs, and how to avoid lock-in.
---

Every month brings a new agent framework. The question isn't "which is best?" but "**which is best for this project and this team?**" Sometimes the answer is **no framework at all**: about 40 lines of Python, as you built in Agent Basics.

::scene The framework buffet
🍽️ | Too many choices | Dev sees ten frameworks and wants all of them. | dev
📝 | Requirements | Byte asks: how much control? How fast to build? Memory? Many agents? | byte
⚖️ | Score | Chip weights each need and scores the options. | chip
🧭 | Decision | Bugsy: "For this project, LangGraph, and tools stay plain functions so we can switch." | bugsy
::end

## Question 1: do you need a framework?

::walk python | The no-framework test
steps_known = True
needs_pause_resume = False
agents = 1
use = "plain code workflow" if steps_known and not needs_pause_resume and agents == 1 else "consider a framework"
print(use)
::notes
1: Can you write the steps down in advance? // vars: steps_known=True
2: Do runs need to pause for hours (human approval) and resume later? // vars: needs_pause_resume=False
3: One agent, or several working together? // vars: agents=1
4: Known steps, no pausing, one agent: plain code wins: easiest to read, debug and test. // deep: Frameworks add value when you need what they've already built well: durable state, checkpoints, human-in-the-loop, multi-agent routing, tracing. If you need none of these, they're extra layers between you and your bug. // out: plain code workflow
::end

## The options

| Option | Strength | Watch out for |
|---|---|---|
| **Plain API + your loop** | Full control, no dependencies, easy to debug | You build memory, retries and tracing yourself |
| **LangGraph** | Explicit graphs, checkpoints, interrupts, streaming | More concepts to learn |
| **CrewAI** | Fast role-based teams, YAML config, Flows | Less fine control inside crews |
| **Claude Agent SDK** | Claude Code's loop with file, search and shell tools, permissions, hooks | Built around Claude models |
| **OpenAI Agents SDK** | Simple agents, handoffs, guardrails, tracing | Fewer built-in workflow controls than graph frameworks |
| **Managed agent platforms** | Hosted loop, sandboxes, scheduling, no servers to run | Less control; check data and pricing terms |

## Score it with your own needs

```python
options = {
    "Plain API loop":    {"control": 5, "speed_to_build": 3, "memory_state": 1, "multi_agent": 2, "portable": 5},
    "LangGraph":         {"control": 5, "speed_to_build": 3, "memory_state": 5, "multi_agent": 4, "portable": 4},
    "CrewAI":            {"control": 3, "speed_to_build": 5, "memory_state": 4, "multi_agent": 5, "portable": 4},
    "Claude Agent SDK":  {"control": 4, "speed_to_build": 5, "memory_state": 4, "multi_agent": 4, "portable": 2},
    "OpenAI Agents SDK": {"control": 4, "speed_to_build": 5, "memory_state": 3, "multi_agent": 4, "portable": 3},
}
weights = {"control": 3, "speed_to_build": 2, "memory_state": 2, "multi_agent": 1, "portable": 1}   # one team's priorities

scores = {name: sum(weights[k] * v for k, v in ratings.items()) for name, ratings in options.items()}
for name, score in sorted(scores.items(), key=lambda kv: -kv[1]):
    print(f"{score:>3}  {name}")
```

Output:

```text
 39  LangGraph
 36  CrewAI
 36  Claude Agent SDK
 35  OpenAI Agents SDK
 30  Plain API loop
```

These ratings are examples, not a verdict. Change the weights to match **your** project: a hackathon cares about `speed_to_build`; a bank's support agent cares about `control` and `memory_state`.

## Test with a spike

Before committing, build the **same small task** (for example, "look up an order and draft a reply, with approval before sending") in your top two choices, in a day each. Compare:

- Lines of code and how readable they are.
- How easy it was to debug a failure.
- How you'd add approval, tracing and tests.
- Cost and latency per run.

## Avoiding lock-in

- **Tools are plain functions** with tests; registering them is a thin layer.
- **Prompts live in files**, not scattered through framework code.
- **Evals are framework-independent**: inputs, expected outcomes, a scoring script. You can run them against any implementation.
- **Use MCP** for integrations, so any host or framework can use them.

## A sensible default path

1. Start with a plain loop or workflow.
2. Add LangGraph (or similar) when you need state, branches and human approval.
3. Use CrewAI or SDK handoffs when the work really splits into roles.
4. Use the Claude Agent SDK when the agent works on files, code and systems.
5. Consider a managed platform when you'd rather not run the infrastructure.

::tip Your choice isn't forever. Teams that kept tools, prompts and evals portable switch frameworks in days. Teams that didn't spend months.

## Summary

- First ask whether you need a framework at all; known steps and one agent usually don't.
- Each option has clear strengths: pick by weighted needs, not hype.
- Run a one-day spike in your top two choices.
- Keep tools, prompts and evals portable to avoid lock-in.

🎉 You've finished **Frameworks Compared**.
