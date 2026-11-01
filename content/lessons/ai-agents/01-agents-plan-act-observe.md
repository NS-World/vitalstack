---
title: "What an AI Agent Is: the Plan → Act → Observe Loop"
slug: agents-plan-act-observe
subject: ai-agents
lesson: 1
difficulty: intermediate
excerpt: An agent is a language model in a loop that can use tools. See the plan → act → observe loop work step by step in plain Python, with no API key needed.
---

Ask a chatbot "What is 18% GST on ₹2,450 plus ₹120 delivery?" and it **guesses** the number from patterns it has seen. Sometimes it is right. An **agent** does something different: it notices it needs to calculate, **uses a calculator tool**, reads the result, and only then answers.

That is the whole idea:

> **An agent is a language model running in a loop, choosing tools, until the job is done.**

## The three steps of the loop

1. **Plan:** the model looks at the goal and everything that has happened so far, and decides the next step: call a tool, or give the final answer.
2. **Act:** your code (not the model) runs the tool it asked for.
3. **Observe:** the tool's result is added to the history, and the loop goes round again.

The model never touches your computer directly. It only **asks**; your program decides whether to run the tool. That separation is what makes agents safe enough to build.

::viz agent-loop

::tip The model is the **brain**, your loop is the **body**. The brain can only talk. The body has hands (tools), and it decides which requests to carry out.

## A fake model, so we can see every step

Real agents call an LLM API. To see the loop clearly (and run it without a key), we use a tiny **scripted model**: a function that looks at the history and returns either a tool call or an answer, exactly the shape real APIs return.

```python
def fake_model(history):
    """Pretends to be an LLM: reads the history, returns the next step."""
    seen = [h for h in history if h["role"] == "tool"]
    if len(seen) == 0:
        return {"tool": "calculator", "input": "2450 * 0.18"}
    if len(seen) == 1:
        return {"tool": "calculator", "input": "2450 + 441.0 + 120"}
    return {"answer": "GST is ₹441 and the total is ₹3,011."}

print(fake_model([{"role": "user", "content": "GST on 2450?"}]))
```

Output:

```text
{'tool': 'calculator', 'input': '2450 * 0.18'}
```

The reply is **data**, not text to show the user. A dictionary with `"tool"` means "please run this for me"; one with `"answer"` means "I'm done".

## The tools

A tool is an ordinary Python function plus a name the model can ask for. Our calculator only allows digits and arithmetic signs, so the model can't sneak in other code:

```python
def calculator(expr):
    allowed = set("0123456789.+-*/() ")
    if not set(expr) <= allowed:
        return "error: only numbers and + - * / ( ) allowed"
    return str(round(eval(expr), 2))

TOOLS = {"calculator": calculator}

print(calculator("2450 * 0.18"))
print(calculator("__import__('os')"))
```

Output:

```text
441.0
error: only numbers and + - * / ( ) allowed
```

::tip Never trust what the model asks for. Check every tool input like you would check a form a stranger filled in. Here, a whitelist of characters blocks anything that isn't arithmetic.

## The loop

Now the agent itself. Read it slowly: this is the pattern every agent framework is built on.

::walk python | The agent loop, one step at a time
history = [{"role": "user", "content": "18% GST on 2450 plus 120 delivery?"}]
for step in range(5):
    reply = fake_model(history)
    if "answer" in reply:
        print("Agent:", reply["answer"])
        break
    result = TOOLS[reply["tool"]](reply["input"])
    print("used", reply["tool"], "->", result)
    history.append({"role": "tool", "content": result})
::notes
1: The **history** starts with the user's question. Everything the agent knows lives in this list. // deep: Real APIs call this the messages list. Each item has a role (user, assistant, tool) and content. // vars: history=[user question]
2: A safety limit: at most **5 rounds**. An agent without a limit can loop forever and burn money. // deep: Production agents also cap time and tokens, not just steps. // vars: step=0
3: **Plan.** Ask the model what to do next. No tool results yet, so it asks for the calculator. // vars: reply={'tool': 'calculator', 'input': '2450 * 0.18'}
4: Is it a final answer? Not yet, it's a tool call, so we skip the next two lines.
7: **Act.** Look up the tool by name and run it with the model's input: 2450 × 0.18 = **441.0**. // deep: TOOLS[...] is a dictionary lookup. If the model asks for a tool that doesn't exist, this raises KeyError: in lesson 3 we handle that properly. // vars: result=441.0
8: Show what happened. // out: used calculator -> 441.0
9: **Observe.** Add the result to the history, so the model sees it next round. // vars: history=[user question, tool: 441.0]
2: Round two. // vars: step=1
3: The model sees one tool result and asks for the total: 2450 + 441 + 120. // vars: reply={'tool': 'calculator', 'input': '2450 + 441.0 + 120'}
4: Still a tool call.
7: Act: the calculator returns **3011.0**. // vars: result=3011.0
8: Show it. // out: used calculator -> 3011.0
9: Observe: the history now has both results. // vars: history=[user question, tool: 441.0, tool: 3011.0]
2: Round three. // vars: step=2
3: Two results seen, so the model is ready to answer. // vars: reply={'answer': 'GST is ₹441 and the total is ₹3,011.'}
4: It's an answer this time.
5: Print the final answer. // out: Agent: GST is ₹441 and the total is ₹3,011.
6: **break** leaves the loop. Done in 3 rounds, well under the limit of 5.
::end

Here is the same loop as a normal code block, so you can run it:

```python
history = [{"role": "user", "content": "18% GST on 2450 plus 120 delivery?"}]
for step in range(5):
    reply = fake_model(history)
    if "answer" in reply:
        print("Agent:", reply["answer"])
        break
    result = TOOLS[reply["tool"]](reply["input"])
    print("used", reply["tool"], "->", result)
    history.append({"role": "tool", "content": result})
```

Output:

```text
used calculator -> 441.0
used calculator -> 3011.0
Agent: GST is ₹441 and the total is ₹3,011.
```

## What changes with a real model

Only one line: `fake_model(history)` becomes a call to an LLM API that is given the history **and a description of each tool** (its name, what it does, and what input it takes). The model then decides by itself when to use the calculator, a web search, your database, or nothing at all. The loop, the safety limit, the input check and the history stay exactly the same.

## Summary

- An agent = **model + tools + a loop**: plan → act → observe, until it answers.
- The model only **asks** for tools; your code decides and runs them.
- Always cap the number of steps, and check every tool input.
- The history list is the agent's memory for this task.

Next: when is an agent the **wrong** choice? Often a simple fixed workflow is cheaper, faster and more reliable.
