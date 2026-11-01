---
title: "Cost and Latency"
slug: production-cost-latency
subject: agent-production
lesson: 1
difficulty: advanced
excerpt: Make agents cheaper and faster: measure cost per step, route easy steps to smaller models, run independent tool calls in parallel, cap output length, cut steps, and stream so users see progress straight away.
---

A demo agent that costs ₹8 and takes 20 seconds per question is fine. At 10,000 questions a day it's a ₹24 lakh monthly bill and a lot of impatient users. Production work is mostly about **cost per task** and **time per task**, and the biggest wins are usually simple.

::scene The expensive assistant
💸 | The bill | Dev's agent uses the biggest model for every step, even "is this about billing?". | dev
🧭 | Route | Byte sends easy steps (classify, extract) to a small model. Same quality, a fraction of the cost. | byte
⚡ | Parallel | Chip runs three lookups at once instead of one after another. | chip
📺 | Stream | Bugsy streams the reply, so users see words in under a second. | bugsy
::end

## Cost per step

```python
PRICES = {"large": (5.00, 25.00), "small": (1.00, 5.00)}      # example $ per million tokens (input, output)
steps = [("classify request", "small", 800, 20), ("plan", "large", 3000, 400),
         ("5 lookups", "small", 9000, 500), ("extract fields", "small", 6000, 300), ("write reply", "large", 4000, 300)]

def cost(model, tin, tout):
    pin, pout = PRICES[model]
    return (tin * pin + tout * pout) / 1e6

all_large = sum(cost("large", tin, tout) for _, _, tin, tout in steps)
routed = sum(cost(model, tin, tout) for _, model, tin, tout in steps)
print(f"all large: ${all_large:.4f} | routed: ${routed:.4f} | saving {1 - routed / all_large:.0%}")
print(f"per month at 10,000 tasks/day: ${all_large * 300000:,.0f} vs ${routed * 300000:,.0f}")
```

Output:

```text
all large: $0.1520 | routed: $0.0724 | saving 52%
per month at 10,000 tasks/day: $45,600 vs $21,720
```

Small models are excellent at classification, extraction and simple lookups. Keep the big model for planning and the final answer. (Prices are illustrative; check current pricing.)

## Parallel tool calls

::walk python | Sequential vs parallel
tool_ms = [400, 650, 300]
sequential = sum(tool_ms)
parallel = max(tool_ms)
print(sequential, parallel)
::notes
1: Three independent lookups (order, delivery, payment) and how long each takes. // vars: tool_ms=[400, 650, 300]
2: One after another: add them up. // vars: sequential=1350
3: All at once: you only wait for the slowest. // deep: Models can request several tools in one turn (parallel tool calls). Run them with asyncio or a thread pool, and return all results together in the next message. Only independent calls can run in parallel: if one needs another's result, it must wait. // vars: parallel=650
4: Less than half the waiting time, for free. // out: 1350 650
::end

## The latency checklist

| Fix | Why it helps |
|---|---|
| **Fewer steps** | Each step is a full model round trip; combine tools, give better instructions |
| **Shorter inputs** | Trim history and tool results (Memory module) |
| **Shorter outputs** | Output tokens are slow to generate: set `max_tokens`, ask for brevity |
| **Smaller models** for simple steps | Faster and cheaper |
| **Parallel** independent calls | Wait for the slowest, not the sum |
| **Prompt caching** | Repeated long prefixes process faster and cheaper (next lesson) |
| **Streaming** | Users see the first words in well under a second |

## Measure: time to first token

```python
token_times_ms = [620, 650, 690, 720, 760, 800, 850, 900]     # when each streamed piece arrived
total_ms = token_times_ms[-1]
print(f"first words after {token_times_ms[0]} ms, full reply after {total_ms} ms")
print(f"without streaming the user stares at nothing for {total_ms} ms")
```

Output:

```text
first words after 620 ms, full reply after 900 ms
without streaming the user stares at nothing for 900 ms
```

Perceived speed is mostly about **time to first token**. Also show progress for agent steps: "Checking your order…", "Looking up delivery…".

## Set budgets and alerts

- A max number of steps and tokens per task (stop gracefully when hit).
- A daily spending limit in your provider console.
- Dashboards for cost per task and p95 latency (lesson 3).
- Batch APIs for work that can wait (overnight reports): often about half the price.

::tip Before optimising, log cost and time per step for 100 real tasks. Usually one or two steps cause most of the cost. Fix those first and ignore the rest.

## Summary

- Measure cost per step and per task; route easy steps to smaller models.
- Run independent tool calls in parallel.
- Cut steps, inputs and outputs; stream and show progress.
- Set step, token and spending limits, and use batch APIs for non-urgent work.
