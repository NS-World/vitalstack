---
title: "LangGraph in Depth"
slug: frameworks-langgraph
subject: agent-frameworks
lesson: 1
difficulty: advanced
excerpt: Go beyond the basics of LangGraph: state reducers, the message list with add_messages, ToolNode and tools_condition, checkpointers and threads, interrupts for human approval, and streaming steps to a UI.
---

In the Gen AI track you drew a simple graph. Production agents need more: a **message list** that grows correctly, a ready-made **tool node**, conversations saved per **thread**, and **pauses** for human approval. LangGraph has a building block for each. This lesson shows how they fit together, starting with the idea that confuses most people: **reducers**.

::scene The relay race notebook
📓 | Shared state | All nodes write in one notebook. But how should a new page combine with old ones? | dev
➕ | Reducers | Byte's rule: messages are appended; the "status" line is overwritten. | byte
🧰 | Tool node | Chip's model asks for tools; a ToolNode runs them and writes the results back. | chip
⏸️ | Pause | Before "send_refund", the graph stops and waits for Bugsy's approval. | bugsy
::end

## Reducers: how updates combine

A node returns **updates**, not the whole state. For each key, a **reducer** decides how the update merges with the old value. The default is "replace"; for lists of messages you want "append":

```python
import operator

def apply_update(state, update, reducers):
    new = dict(state)
    for key, value in update.items():
        reduce = reducers.get(key)
        new[key] = reduce(state.get(key), value) if reduce else value
    return new

reducers = {"messages": operator.add}          # like Annotated[list, add_messages]
state = {"messages": ["user: refund my order"], "status": "new"}
state = apply_update(state, {"messages": ["ai: checking order 1042"], "status": "checking"}, reducers)
state = apply_update(state, {"messages": ["tool: order 1042 delivered 3 days ago"]}, reducers)
print(state["status"], "|", state["messages"])
```

Output:

```text
checking | ['user: refund my order', 'ai: checking order 1042', 'tool: order 1042 delivered 3 days ago']
```

`status` was replaced; `messages` kept growing. LangGraph's `add_messages` reducer also updates a message in place if it has the same id, which is useful for streaming and edits.

::walk python | Replace vs append
old = ["hi"]
replace = ["bye"]
append = old + ["bye"]
print(replace, append)
::notes
1: The current value of a list in the state. // vars: old=['hi']
2: With no reducer, the node's update simply replaces the old value. // vars: replace=['bye']
3: With an append reducer (operator.add), the update is added to the end. // deep: Forgetting the reducer on the messages key is a classic LangGraph bug: every node wipes the conversation and the model "forgets" everything. If your agent seems to lose history, check this first. // vars: append=['hi', 'bye']
4: Same update, very different results. // out: ['bye'] ['hi', 'bye']
::end

## A tool agent with ToolNode

```python
from typing import Annotated, TypedDict
from langchain_anthropic import ChatAnthropic
from langchain_core.tools import tool
from langgraph.graph import StateGraph, START
from langgraph.graph.message import add_messages
from langgraph.prebuilt import ToolNode, tools_condition
from langgraph.checkpoint.memory import MemorySaver

@tool
def order_status(order_id: str) -> str:
    """Look up an order's delivery status by id, e.g. 1042."""
    return {"1042": "delivered 3 days ago"}.get(order_id, "unknown order")

tools = [order_status]
llm = ChatAnthropic(model="claude-opus-5-5").bind_tools(tools)

class State(TypedDict):
    messages: Annotated[list, add_messages]

def assistant(state: State):
    return {"messages": [llm.invoke(state["messages"])]}

g = StateGraph(State)
g.add_node("assistant", assistant)
g.add_node("tools", ToolNode(tools))
g.add_edge(START, "assistant")
g.add_conditional_edges("assistant", tools_condition)   # tool call → "tools", otherwise → END
g.add_edge("tools", "assistant")
app = g.compile(checkpointer=MemorySaver())

config = {"configurable": {"thread_id": "customer-77"}}
app.invoke({"messages": [("user", "Where is order 1042?")]}, config)
reply = app.invoke({"messages": [("user", "And can I still return it?")]}, config)   # same thread: remembers
print(reply["messages"][-1].content)
```

- `ToolNode` runs whichever tools the model asked for and adds the results as tool messages.
- `tools_condition` routes to `"tools"` when the last message has tool calls, otherwise ends.
- The **checkpointer** saves the state after every step, per `thread_id`. Use a database-backed checkpointer (SQLite, Postgres) in production.

## Human approval with interrupts

```python
from langgraph.types import interrupt, Command

def refund(state: State):
    decision = interrupt({"question": "Approve a ₹799 refund for order 1042?"})   # the graph pauses here
    if decision != "approve":
        return {"messages": [("ai", "Okay, I won't refund it.")]}
    return {"messages": [("ai", "Refund of ₹799 sent.")]}

# Later, after a human clicks Approve in your UI:
# app.invoke(Command(resume="approve"), config)
```

`interrupt` saves the state and stops. Your UI shows the question; when the person answers, `Command(resume=...)` continues from exactly that point, even hours later.

## Streaming steps to a UI

```python
from langgraph.graph import StateGraph      # app and config come from the tool-agent example above

for event in app.stream({"messages": [("user", "Where is order 1042?")]}, config, stream_mode="updates"):
    print(event)          # one dict per finished node: show "Checking order…", "Found it" in the UI
```

::tip Draw your graph with `app.get_graph().draw_mermaid()` and paste it in your README or PR. It's the fastest way for teammates (and future you) to understand the agent.

## Summary

- Nodes return updates; reducers decide how they merge (replace by default, append for messages).
- `ToolNode` + `tools_condition` give you the standard tool loop.
- Checkpointers save state per `thread_id`: memory across turns, recovery and time travel.
- `interrupt` and `Command(resume=…)` add human approval; `stream` shows progress.
