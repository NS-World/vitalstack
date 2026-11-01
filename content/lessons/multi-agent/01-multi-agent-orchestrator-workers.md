---
title: "Orchestrator and Workers"
slug: multi-agent-orchestrator-workers
subject: multi-agent
lesson: 1
difficulty: advanced
excerpt: The most useful multi-agent pattern: a lead agent splits a big task, worker agents research pieces in parallel with fresh context, and the lead combines the results. With runnable parallel workers, good worker briefs and an honest look at the token cost.
---

Some tasks are **wide**: compare ten products, review twenty files, research five sub-questions. One agent doing them one after another gets slow, and its context fills with details. The **orchestrator-workers** pattern splits the work: a lead agent plans and delegates, **workers** each handle one piece in parallel with a clean context, and the lead combines their short reports.

::scene The laptop hunt
🎯 | The ask | "Which laptop is best for a student who travels?" Dev gives the job to the lead agent, Byte. | dev
🗂️ | Split | Byte sends three workers off, one per laptop, each with a clear brief. | byte
⚡ | In parallel | Chip and two helpers check battery, weight and price at the same time. | chip
🏆 | Combine | Byte reads three short reports and recommends Laptop C. Bugsy checks the maths. | bugsy
::end

## The pattern

```
             ┌── worker 1 (fresh context) ──┐
lead agent ──┼── worker 2 (fresh context) ──┼──> lead agent combines → answer
             └── worker 3 (fresh context) ──┘
```

## Parallel workers you can run

```python
from concurrent.futures import ThreadPoolExecutor

SPECS = {"Laptop A": {"price": 52000, "battery_h": 10, "weight_kg": 1.4},
         "Laptop B": {"price": 45000, "battery_h": 7, "weight_kg": 1.8},
         "Laptop C": {"price": 61000, "battery_h": 14, "weight_kg": 1.2}}

def orchestrator(task):
    return [{"laptop": name, "brief": f"For '{task}': report battery, weight and price of {name}"} for name in SPECS]

def worker(sub):                                   # in a real system: its own LLM call with its own tools
    s = SPECS[sub["laptop"]]
    score = s["battery_h"] * 2 - s["weight_kg"] * 5 - s["price"] / 10000
    return {"laptop": sub["laptop"], "score": round(score, 1),
            "note": f"{s['battery_h']}h battery, {s['weight_kg']} kg, ₹{s['price']:,}"}

subtasks = orchestrator("best laptop for a student who travels")
with ThreadPoolExecutor(max_workers=3) as pool:
    reports = list(pool.map(worker, subtasks))      # all three run at the same time

for r in reports:
    print(f"{r['laptop']}: score {r['score']:>5} | {r['note']}")
print("recommend:", max(reports, key=lambda r: r["score"])["laptop"])
```

Output:

```text
Laptop A: score   7.8 | 10h battery, 1.4 kg, ₹52,000
Laptop B: score   0.5 | 7h battery, 1.8 kg, ₹45,000
Laptop C: score  15.9 | 14h battery, 1.2 kg, ₹61,000
recommend: Laptop C
```

Each worker returns a **short report**, not its whole conversation. The lead's context stays small even if each worker read many pages.

## Writing a good worker brief

Vague briefs make workers duplicate work or wander. Every brief should say:

| Part | Example |
|---|---|
| **Objective** | "Find battery life, weight and price of Laptop C in India" |
| **Output format** | "Reply in 3 bullet points with a source link each" |
| **Tools and sources** | "Use web search; prefer the maker's site and two reviews" |
| **Boundaries** | "Don't research other laptops; stop after 5 searches" |

## With real models, in parallel

```python
import asyncio
import anthropic

client = anthropic.AsyncAnthropic()

async def run_worker(brief):
    r = await client.messages.create(model="claude-opus-5-5", max_tokens=600,
        system="You are a research worker. Follow the brief exactly and reply in 3 bullets.",
        messages=[{"role": "user", "content": brief}])
    return r.content[0].text

async def lead(task):
    briefs = [s["brief"] for s in orchestrator(task)]
    reports = await asyncio.gather(*(run_worker(b) for b in briefs))      # parallel calls
    summary = await client.messages.create(model="claude-opus-5-5", max_tokens=800,
        messages=[{"role": "user", "content": f"Task: {task}\n\nWorker reports:\n" + "\n\n".join(reports)
                   + "\n\nRecommend one option and explain why in 5 lines."}])
    return summary.content[0].text
```

## The honest cost

::walk python | Multi-agent token cost
single_agent_tokens = 20000
workers = 3
per_worker = 15000
orchestrator_tokens = 8000
multi = workers * per_worker + orchestrator_tokens
print(multi, round(multi / single_agent_tokens, 1))
::notes
1: One agent doing the whole job one step at a time. // vars: single_agent_tokens=20000
2: Three workers. // vars: workers=3
3: Each worker has its own system prompt, tools and research. // vars: per_worker=15000
4: The lead plans, then reads all the reports. // vars: orchestrator_tokens=8000
5: Total tokens across all agents. // deep: Multi-agent systems often use several times the tokens of a single agent. They pay off when the task is broad and valuable enough that faster, more thorough answers are worth it, not for simple questions. // vars: multi=53000
6: About 2.6× the tokens: faster and broader, but not cheaper. // out: 53000 2.6
::end

## When to use it

- ✅ Broad tasks that split into **independent** parts (research, comparisons, reviewing many files).
- ✅ When one context window can't hold all the details.
- ❌ Tightly connected tasks where every step depends on the last (most coding changes).
- ❌ Simple questions: a single call is cheaper and just as good.

::tip Scale effort to the question: 1 worker for a simple fact, 3 for a comparison, more only for deep research. Put that rule in the lead agent's prompt, or it may spin up ten workers to answer "What's the capital of Kerala?"

## Summary

- A lead agent splits a broad task; workers handle pieces in parallel with fresh context.
- Workers return short reports, keeping the lead's context small.
- Good briefs state objective, output format, tools and boundaries.
- It costs more tokens; use it for broad, valuable tasks.
