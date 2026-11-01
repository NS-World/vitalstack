---
title: "What Makes an Agent: Plan, Act, Observe"
slug: llm-agents-what-makes-an-agent
subject: llm-agents
lesson: 1
difficulty: intermediate
excerpt: Chatbot, workflow or agent? The five parts of every LLM agent (model, tools, loop, memory, stop rule), a runnable travel-booking agent you can trace step by step, and what each step costs.
---

A chatbot answers. An **agent** gets something done: it decides which tool to use, looks at the result, and decides again, until the job is finished. Inside an app, that is the difference between "Here are some flights to Goa" and "I found the cheapest daytime flight that fits your budget and it's ready to book."

::scene The Goa trip
🧳 | The goal | "Book me a daytime flight to Goa under ₹4,500." | dev
🔍 | Act | Chip, the agent, calls the flight search tool and gets three options. | chip
👀 | Observe | The first daytime flight is ₹5,400: too costly. Chip checks the next one. | bugsy
✅ | Done | ₹4,100 at 13:15 fits. Chip stops and reports, instead of looping forever. | byte
::end

## The five parts of every agent

| Part | What it is | In our example |
|---|---|---|
| **Model** | The LLM that decides the next step | Chooses "search" or "check budget" |
| **Tools** | Functions the model may call | `search_flights`, `within_budget` |
| **Loop** | Call model → run tool → feed result back | The `for` loop below |
| **Memory** | What the model can see from earlier steps | The `history` list |
| **Stop rule** | A final answer, or a step limit | `"final"` or 5 steps max |

## Chatbot, workflow or agent?

- **Chatbot:** one question, one answer. No tools, no loop.
- **Workflow:** you wrote the steps in code (search → filter → reply). Predictable and cheap.
- **Agent:** the model chooses the steps. Flexible, but slower, costlier and harder to test.

Start with the simplest one that works. Use an agent only when you can't write the steps down in advance.

## A travel agent you can run

We use a **fake model**, a plain function that behaves like an LLM choosing actions, so you can see every step without an API key. Lesson 2 swaps in a real model.

```python
FLIGHTS = {"Goa": [("6:40", 5400), ("13:15", 4100), ("21:00", 3600)]}

def search_flights(city):
    return FLIGHTS.get(city, [])

def within_budget(price, budget=4500):
    return price <= budget

def fake_model(history):
    if not history:
        return {"tool": "search_flights", "args": {"city": "Goa"}}
    daytime = [f for f in history[0]["result"] if int(f[0].split(":")[0]) < 18]
    checked = len(history) - 1
    if checked and history[-1]["result"] is True:
        time, price = daytime[checked - 1]
        return {"final": f"Book the {time} flight for ₹{price:,}"}
    return {"tool": "within_budget", "args": {"price": daytime[checked][1]}}

TOOLS = {"search_flights": search_flights, "within_budget": within_budget}
history = []
for step in range(1, 6):
    action = fake_model(history)
    if "final" in action:
        print("answer:", action["final"])
        break
    result = TOOLS[action["tool"]](**action["args"])
    history.append({"tool": action["tool"], "result": result})
    print(f"step {step}: {action['tool']}({action['args']}) -> {result}")
```

Output:

```text
step 1: search_flights({'city': 'Goa'}) -> [('6:40', 5400), ('13:15', 4100), ('21:00', 3600)]
step 2: within_budget({'price': 5400}) -> False
step 3: within_budget({'price': 4100}) -> True
answer: Book the 13:15 flight for ₹4,100
```

Notice the agent never looked at the 21:00 flight: it's cheapest, but it isn't daytime. The model followed the goal, not just the price.

::walk python | The loop in miniature
steps = [{"tool": "search_flights"}, {"tool": "within_budget"}, {"final": "Book the 13:15 flight"}]
log = []
for s in steps:
    if "final" in s:
        log.append("done")
        break
    log.append(s["tool"])
print(log, len(log))
::notes
1: Three decisions a model might return, one per turn: two tool calls, then a final answer. // vars: steps=3 actions
2: An empty log, like the trace a real agent keeps. // vars: log=[]
3: The agent loop: one turn per decision.
4: Is this a final answer? That's the stop rule. // deep: Real APIs signal this too: Claude returns stop_reason "end_turn" instead of "tool_use", and OpenAI-style APIs return a message with no tool calls.
5: Record that we finished.
6: Leave the loop. Without a stop rule, an agent can loop and spend money forever.
7: Otherwise, record the tool that was used. // vars: log=['search_flights', 'within_budget']
8: Three entries: two actions and the finish. // out: ['search_flights', 'within_budget', 'done'] 3
::end

## Every step is a model call

Each turn sends the whole conversation so far, so the prompt grows:

```python
prompt_tokens, growth_per_step = 900, 350
calls = [prompt_tokens + i * growth_per_step for i in range(4)]
print(calls, "total input tokens:", sum(calls))
```

Output:

```text
[900, 1250, 1600, 1950] total input tokens: 5700
```

A 4-step agent used over six times the tokens of a single 900-token chat reply. That's why you give agents a step limit and short tool results.

::tip When you design an agent feature, write the stop rule first: "finish when X, or after N steps, or when the user must decide." It's the most common thing beginners forget.

## Summary

- An agent = model + tools + loop + memory + stop rule.
- Chatbots answer, workflows follow your steps, agents choose their own steps.
- The loop: model picks an action → your code runs the tool → the result goes back to the model.
- Every step is another model call, so cost and time grow with each step.
