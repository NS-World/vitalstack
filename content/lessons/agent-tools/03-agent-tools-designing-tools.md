---
title: "Designing Tools an LLM Can Use Well"
slug: agent-tools-designing-tools
subject: agent-tools
lesson: 3
difficulty: intermediate
excerpt: The design rules that make or break agents: fewer, task-shaped tools, clear names and descriptions, compact and paginated results, readable ids instead of UUIDs, helpful empty results, and testing tools with the model itself.
---

Most agent problems aren't model problems: they're **tool design** problems. A tool that returns 5,000 lines of raw JSON, or three tools that overlap, confuses even the best model. Good tools are designed for the model the way good apps are designed for people.

::scene The order-lookup mess
🗃️ | Too raw | Chip's list_orders tool dumps 240 orders and 60 fields each. The context fills up. | chip
🧭 | Confused | There are also get_order, find_order and search_orders. Which one? Bugsy can't tell. | bugsy
✂️ | Redesign | Dev merges them into one search_orders tool with filters, 5 results per page and 4 useful fields. | dev
🚀 | Smooth | The agent answers "Where's my last order?" in one call. | byte
::end

## Rule 1: tools shaped like tasks

Don't mirror your database API one endpoint at a time. Make tools that match what the agent needs to **do**:

| API-shaped (worse) | Task-shaped (better) |
|---|---|
| `list_users`, `list_orders`, `list_payments` | `get_customer_summary(email)` returning profile + last orders + open issues |
| `get_order`, `find_order`, `search_orders` | one `search_orders(query, status, limit)` |
| `create_event` + `check_free` + `send_invite` | `schedule_meeting(people, duration, window)` |

Fewer, clearer tools = fewer wrong choices.

## Rule 2: compact, paginated results

```python
import json

orders = [{"id": f"ORD-{1000 + i}", "status": "delivered" if i % 3 else "shipped", "total": 400 + 37 * i,
           "items": [f"item-{i}"], "warehouse_code": "WH-7", "internal_flags": [0, 1, 0], "created": f"2026-10-{1 + i % 28:02d}"}
          for i in range(240)]

def search_orders(status=None, limit=5):
    hits = [o for o in orders if status is None or o["status"] == status]
    shown = [{k: o[k] for k in ("id", "status", "total", "created")} for o in hits[:limit]]
    note = f"showing {len(shown)} of {len(hits)}; add filters or ask for the next page" if len(hits) > limit else ""
    return {"orders": shown, "note": note}

raw_size = len(json.dumps(orders))
designed_size = len(json.dumps(search_orders(status="shipped")))
print(f"raw dump: {raw_size:,} characters | designed result: {designed_size:,} characters")
print(search_orders(status="shipped")["note"])
```

Output:

```text
raw dump: 38,353 characters | designed result: 477 characters
showing 5 of 80; add filters or ask for the next page
```

About 80 times smaller, with only fields the model needs, and a note telling it how to get more.

## Rule 3: readable ids and helpful empty results

- Return `ORD-1042` or a name, not `3f2a9c1e-8b7d-4e0a-…`. Models copy long random ids badly.
- An empty result should **guide**: "No orders for asha@example.com. Did you mean asha.k@example.com?" instead of `[]`.

::walk python | A helpful 'not found' message
emails = ["asha.k@example.com", "ravi@example.com"]
query = "asha@example.com"
close = [e for e in emails if e.split("@")[0].startswith(query.split("@")[0])]
message = f"No customer {query}." + (f" Did you mean {close[0]}?" if close else "")
print(message)
::notes
1: The customers we know. // vars: emails=2 emails
2: The model searched for an email that doesn't exist. // vars: query=asha@example.com
3: Look for near matches: same start of the name before the @. // deep: Real systems use fuzzy matching (edit distance) or search indexes. Even a simple hint like this saves a whole wasted agent step. // vars: close=['asha.k@example.com']
4: A message that tells the model what to try next.
5: Instead of a dead end, the agent can confirm the right email with the user. // out: No customer asha@example.com. Did you mean asha.k@example.com?
::end

## Rule 4: names and descriptions

- **Names:** verb + object, consistent: `search_orders`, `get_order_status`, `cancel_order`. Group related tools with a prefix (`orders_…`, `calendar_…`) when you have many.
- **Descriptions:** what it does, when to use it, when **not** to use it, and what it returns. Example: "Cancel an order that has not shipped. Don't use for returns; use start_return. Returns the refund amount."
- **Danger flagged:** say in the description if a tool changes data or costs money, and require confirmation in code.

## Rule 5: a `response_format` option

Let the model choose detail: `response_format: "concise" | "detailed"`. Concise for browsing, detailed when it needs everything about one item.

## Test tools with the model

Write 10 realistic tasks ("Cancel my last order if it hasn't shipped"), run the agent, and read the transcripts:

- Did it pick the right tool first time?
- Did it pass valid arguments?
- How many calls did it need?
- Where did it get confused? Improve that description or merge those tools.

Many teams even ask the model to review its own tool descriptions after a failed run: "Which description misled you, and how would you rewrite it?"

::tip Count tokens in your tool results. If a single tool result is longer than the conversation before it, it needs a summary, a filter or pagination.

## Summary

- Design task-shaped tools, and merge overlapping ones.
- Return compact, paginated results with only useful fields.
- Use readable ids, and make empty results and errors guide the next step.
- Name and describe tools clearly, then test them with real tasks and refine.

🎉 You've finished **Tool Use in Depth**.
