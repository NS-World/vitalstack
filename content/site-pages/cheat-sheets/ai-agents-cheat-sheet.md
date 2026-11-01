---
title: AI Agents Cheat Sheet
slug: ai-agents-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: 🦾
order: 140
excerpt: AI agents on one page - the agent loop, workflow vs agent, tool schemas, the Claude tool-use loop, memory types, ReAct and plan-and-execute, MCP, frameworks, evals, safety rules and production checklists.
---
## The loop

```python
messages = [{"role": "user", "content": task}]
for step in range(MAX_STEPS):                       # always a step limit
    r = client.messages.create(model=MODEL, max_tokens=1024, tools=TOOLS, messages=messages)
    messages.append({"role": "assistant", "content": r.content})
    if r.stop_reason != "tool_use":
        break                                       # final answer
    results = [{"type": "tool_result", "tool_use_id": b.id, "content": run_tool(b.name, b.input)}
               for b in r.content if b.type == "tool_use"]
    messages.append({"role": "user", "content": results})
```

## Workflow or agent?

| Use a workflow (your code decides the steps) | Use an agent (the model decides) |
|---|---|
| You can write the steps down | Steps depend on what it finds |
| Needs to be cheap and predictable | Open-ended tasks, research, debugging |
| Easy to test | Worth extra cost and testing effort |

## A good tool

```json
{"name": "search_orders",
 "description": "Find a customer's orders. Use before answering any order question. Returns at most 5.",
 "input_schema": {"type": "object",
   "properties": {"email": {"type": "string"},
                  "status": {"type": "string", "enum": ["shipped", "delivered", "returned"]}},
   "required": ["email"], "additionalProperties": false}}
```

Few, task-shaped tools · compact results · readable ids · errors that say how to fix the call.

## Memory

| Type | Holds | How |
|---|---|---|
| Short-term | The current task | Context window; clear old tool results |
| Long-term | Facts across sessions | Summaries + a facts store (one value per key) |
| Vector | Many past events | Embeddings ranked by relevance + recency + importance |

## Patterns

- **ReAct:** reason → act → observe, repeat.
- **Plan-and-execute:** plan steps with dependencies, execute, re-plan on failure.
- **Reflection:** draft → critique with real feedback (tests, sources) → revise.
- **Orchestrator-workers:** lead splits a broad task; parallel workers return short reports.
- **Hand-offs:** triage routes to specialists with a context packet.

## MCP in one line each

Host (the AI app) · client (one per server) · server (tools, resources, prompts) · JSON-RPC · `tools/list`, `tools/call` · stdio locally, Streamable HTTP remotely.

## Safety rules

- Lethal trifecta = private data + untrusted content + a way out. Remove a leg or require approval.
- Policy in code: allow / ask / deny, deny unknown tools, limits on amounts.
- Model-written code runs in a sandbox: timeout, no secrets, no network.
- Treat every tool result and web page as untrusted data.

## Production checklist

Evals with end-state checks and pass^k · tracing with tokens and latency per step · model routing · prompt caching · step and spending limits · background jobs for long tasks · canary releases.

Go deeper in the [Agentic AI Engineer roadmap](/roadmaps/agentic-ai-engineer-roadmap/).
