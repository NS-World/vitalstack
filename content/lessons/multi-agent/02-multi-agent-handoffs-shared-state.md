---
title: "Hand-offs and Shared State"
slug: multi-agent-handoffs-shared-state
subject: multi-agent
lesson: 2
difficulty: advanced
excerpt: Pass work between specialist agents without losing the plot: triage routing, hand-offs with a context packet, a shared state "blackboard", a hand-off limit to stop ping-pong, and the customer-support example end to end.
---

In a support desk, the front desk sends you to billing, and billing sends you to tech support. Each time, you hope they **read your file** instead of asking you to explain again. Multi-agent hand-offs work the same way: a **triage** agent routes the conversation, specialists do their part, and a **shared state** keeps everyone on the same page.

::scene Charged twice, app crashing
😤 | The problem | Ravi: "I was charged twice and now the app crashes on login!" | dev
🚦 | Triage | Byte spots "charged" and sends him to billing first. | byte
💳 | Billing | Chip finds the duplicate ₹499 charge, starts a refund, notes it, and hands off to tech. | chip
🛠️ | Tech | Bugsy reads the shared notes, so Ravi doesn't repeat himself, and sends a fix. | bugsy
::end

## Triage, specialists and a shared state

```python
def triage(message):
    m = message.lower()
    if any(w in m for w in ("refund", "payment", "charged")):
        return "billing"
    if any(w in m for w in ("error", "crash", "login")):
        return "tech"
    return "general"

def billing(state):
    state["notes"].append("billing: duplicate charge of ₹499 found, refund started")
    return "tech" if "crash" in state["issue"] else None          # hand off, or finish

def tech(state):
    state["notes"].append("tech: login crash is a known bug, fixed in v2.3.1; update link sent")
    return None

AGENTS = {"billing": billing, "tech": tech}
state = {"customer": "Ravi", "issue": "I was charged twice and now the app crashes on login",
         "notes": [], "handoffs": 0}

current = triage(state["issue"])
while current and state["handoffs"] < 3:                # hand-off limit stops ping-pong
    print("->", current)
    next_agent = AGENTS[current](state)
    if next_agent:
        state["handoffs"] += 1
    current = next_agent
print(*state["notes"], sep="\n")
```

Output:

```text
-> billing
-> tech
billing: duplicate charge of ₹499 found, refund started
tech: login crash is a known bug, fixed in v2.3.1; update link sent
```

The `state` dictionary is the **blackboard**: every agent reads it and writes its notes there. Tech knew about the refund without asking.

## What to pass in a hand-off

Don't dump the whole history on the next agent, and don't send nothing. Send a **context packet**:

::walk python | Building a hand-off packet
history = ["user: charged twice", "billing: refund started", "user: also app crashes", "billing: passing you to tech"]
handoff = {"to": "tech", "summary": "Duplicate charge refunded. Open issue: app crashes on login.", "last_user_message": history[2]}
print(handoff["to"], "|", handoff["last_user_message"])
::notes
1: The conversation so far. In real life it could be 40 messages long. // vars: history=4 messages
2: The packet: who it's for, a short summary of what's done and what's open, and the user's own words. // deep: The user's exact words matter: summaries can drop details ("it crashes only on Android"). Many systems pass the summary, the last user message and the shared notes, and let the next agent fetch more history only if it needs it.
3: Tech gets a clear brief and Ravi's own description of the problem. // out: tech | user: also app crashes
::end

## Hand-offs in the SDKs

- **OpenAI Agents SDK:** list specialists in `handoffs=[billing, tech]`; the model hands off by calling a generated hand-off tool, and the conversation moves to that agent.
- **LangGraph:** each agent is a node; a router (or a `Command(goto="tech")`) moves control, and the graph state is the blackboard.
- **CrewAI:** tasks pass outputs as context; a hierarchical manager can delegate.
- **Plain code:** exactly the loop above, with real LLM calls inside each agent.

## Common problems and fixes

| Problem | Fix |
|---|---|
| Ping-pong (billing → tech → billing → …) | Hand-off limit; triage reviews after 2 hand-offs |
| User repeats themselves | Shared notes + context packet |
| Two agents edit the same thing | One owner per field in the state; others only read it |
| Lost responsibility | Each hand-off records who has the conversation now |
| Wrong specialist | Let specialists hand back to triage with a reason |

::tip Log every hand-off with from, to and reason. Reviewing those logs shows you exactly where your routing rules or agent instructions need work.

## Summary

- A triage agent routes; specialists handle their part and can hand off.
- A shared state (blackboard) keeps notes visible to every agent.
- Hand-off packets carry a summary, open issues and the user's own words.
- Limit hand-offs, give each state field one owner, and log every transfer.

🎉 You've finished **Multi-Agent Systems**.
