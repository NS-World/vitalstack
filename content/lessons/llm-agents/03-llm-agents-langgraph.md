---
title: "Multi-Step Agents with LangGraph"
slug: llm-agents-langgraph
subject: llm-agents
lesson: 3
difficulty: intermediate
excerpt: Build agents as graphs: state, nodes, edges and conditional edges, a write-check-revise loop you can run in plain Python, the same agent in LangGraph, the prebuilt ReAct agent, checkpoints and human approval.
---

A simple agent loop is fine for small jobs. When an app needs **clear stages** (draft → check → revise), **branches** ("if the check fails, go back") and **pauses for a human**, you draw it as a **graph**. LangGraph is a popular library for exactly this: each step is a node, arrows are edges, and a shared **state** dictionary flows through them.

::scene The essay machine
🗺️ | The map | Two stations: Write and Check. An arrow goes from Write to Check. | dev
📝 | Write | Chip writes a first draft into the shared notebook (the state). | chip
🔎 | Check | Bugsy reads it: "Not clear enough." The arrow points back to Write. | bugsy
🏁 | Finish | Draft 2 passes. The arrow points to END. Byte notes there's also a 3-try limit. | byte
::end

## Graph words in plain English

| Word | Meaning |
|---|---|
| **State** | A dictionary every node can read and update (question, draft, tries…) |
| **Node** | A function: takes the state, returns updates |
| **Edge** | "After this node, go to that one" |
| **Conditional edge** | A router function that picks the next node from the state |
| **END** | Where the graph stops |

## A graph engine in 20 lines (runnable)

Before using the library, build the idea yourself. The "model" here is faked: the check passes on the second draft.

```python
def write(state):
    state["tries"] += 1
    state["draft"] = f"Answer v{state['tries']}"
    return state

def check(state):
    state["ok"] = state["tries"] >= 2
    return state

def route(state):
    return "end" if state["ok"] else "write"

nodes = {"write": write, "check": check}
edges = {"write": "check"}                 # fixed edge; check uses the router

state, node, path = {"tries": 0}, "write", []
while node != "end":
    path.append(node)
    state = nodes[node](state)
    node = edges.get(node) or route(state)
print(" -> ".join(path + ["end"]), "|", state["draft"])
```

Output:

```text
write -> check -> write -> check -> end | Answer v2
```

## The router decides everything

::walk python | A router with a safety limit
def route(state):
    if state["tries"] >= 3:
        return "give_up"
    return "end" if state["ok"] else "write"
print(route({"tries": 1, "ok": False}), route({"tries": 3, "ok": False}), route({"tries": 1, "ok": True}))
::notes
1: A router reads the state and returns the name of the next node.
2: Safety first: after 3 tries, stop trying. // deep: LangGraph also has a global recursion limit (it raises an error after too many steps), but your own limit lets you end gracefully with a message for the user.
3: Go to a "give_up" node that explains politely what went wrong.
4: Otherwise: finished if the check passed, back to writing if not.
5: First draft failed → write again. Third failure → give up. Passed → end. // out: write give_up end
::end

## The same agent in LangGraph

```python
from typing import TypedDict
from langgraph.graph import StateGraph, START, END
from langchain_anthropic import ChatAnthropic

llm = ChatAnthropic(model="claude-opus-5-5")

class State(TypedDict):
    question: str
    draft: str
    tries: int
    ok: bool

def write(state: State):
    msg = llm.invoke(f"Explain for a beginner in 3 lines: {state['question']}")
    return {"draft": msg.content, "tries": state["tries"] + 1}     # return only what changed

def check(state: State):
    verdict = llm.invoke(f"Reply YES if this is correct and clear, otherwise NO.\n\n{state['draft']}")
    return {"ok": verdict.content.strip().upper().startswith("YES")}

def route(state: State):
    if state["ok"] or state["tries"] >= 3:
        return END
    return "write"

graph = StateGraph(State)
graph.add_node("write", write)
graph.add_node("check", check)
graph.add_edge(START, "write")
graph.add_edge("write", "check")
graph.add_conditional_edges("check", route)
app = graph.compile()

print(app.invoke({"question": "What is recursion?", "tries": 0, "ok": False})["draft"])
```

## The prebuilt tool agent

For the common "model + tools in a loop" pattern, LangGraph ships a ready-made ReAct-style agent:

```python
from langgraph.prebuilt import create_react_agent
from langchain_core.tools import tool

@tool
def lesson_minutes(topic: str) -> int:
    """Minutes needed to finish the remaining lessons of a topic."""
    return {"python": 54, "sql": 28}.get(topic.lower(), 0)

agent = create_react_agent(llm, tools=[lesson_minutes])
result = agent.invoke({"messages": [("user", "How long to finish Python and SQL?")]})
print(result["messages"][-1].content)
```

## Checkpoints and human approval

Compile with a **checkpointer** (for example `MemorySaver`) and every step of the state is saved per conversation `thread_id`. That gives you:

- **Memory across turns**: the next message continues the same state.
- **Human-in-the-loop**: pause before a risky node ("send email", "make payment"), show the plan to a person, and resume only after approval.
- **Time travel**: replay or fix a run from an earlier step while debugging.

::tip Draw the graph on paper before coding: boxes for nodes, arrows for edges, a diamond for each router. If you can't draw it, the agent is probably too vague, and a plain workflow may be better.

## Summary

- LangGraph models an agent as state + nodes + edges + routers.
- Nodes return state updates; conditional edges choose the next node.
- Always add a try or step limit in the router.
- Use the prebuilt ReAct agent for simple tool loops, and checkpointers for memory and human approval.
