---
title: What Are AI Agents? How They Work and When to Use Them
slug: what-are-ai-agents
category: artificial-intelligence
tags: AI agents, LLM
order: 10
excerpt: AI agents explained simply: how a language model plus tools plus a loop can plan and complete multi-step tasks, real examples, risks, and how developers build them.
---

**Short answer:** an AI agent is a system where a language model doesn't just answer once, but works in a **loop**: it decides what to do next, uses **tools** (search, code, files, APIs) to do it, looks at the result, and repeats until the task is complete.

A chatbot answers questions. An agent **gets things done**: "find the three cheapest flights and put them in a spreadsheet", "fix the failing test in this project", "research these five companies and summarise them". This guide explains how agents work, where they're useful, and where they go wrong.

## From chatbot to agent

| | Chat assistant | AI agent |
|---|---|---|
| Interaction | One question → one answer | One goal → many steps |
| Actions | Writes text | Uses tools: search, run code, edit files, call APIs |
| Feedback | You read the answer | The agent reads tool results and adjusts |
| Who drives | You, every turn | The agent, within limits you set |

## The three ingredients

### 1. A model that can reason about steps

A large language model (see [How Large Language Models Work](/how-large-language-models-work/)) reads the goal, the conversation so far and the tool results, and decides the next action.

### 2. Tools

Tools are functions the agent can call. The developer describes each tool (its name, what it does, its inputs), and the model can ask to use it:

```text
Tool: search_web(query)        → returns search results
Tool: read_file(path)          → returns file contents
Tool: run_python(code)         → returns the output
Tool: send_email(to, subject, body)
```

The model doesn't run the tool itself. It outputs a structured request like `search_web("best laptops under 60000 rupees")`; the application runs it and passes the result back. This is often called **tool use** or **function calling**.

### 3. The loop

```text
1. Read the goal and everything so far
2. Decide: answer now, or call a tool?
3. If a tool: run it, add the result to the context
4. Go back to 1
5. Stop when the goal is met (or a limit is reached)
```

A tiny example of an agent's internal steps:

```text
Goal: "What's the total size of all .log files in /var/app?"

Think: I need the list of log files and their sizes.
Act:   run_shell("find /var/app -name '*.log' -printf '%s\n'")
See:   10240, 52311, 9001
Think: Sum them: 71552 bytes ≈ 70 KB.
Answer: "The .log files take about 70 KB in total."
```

That's the whole idea: **think → act → observe → repeat**.

## Memory and planning

Useful agents usually add:

- **Short-term memory:** the context window holds the current task's steps and results.
- **Long-term memory:** notes saved to files or a database, so the agent can recall preferences or earlier work.
- **Planning:** for bigger tasks, the agent writes a plan first ("1. find the files, 2. read the config, 3. fix the bug, 4. run the tests"), then works through it and updates it as it learns more.

## Real-world examples

- **Coding agents:** read a codebase, edit several files, run tests, read the errors and fix them until tests pass.
- **Research agents:** search, open sources, compare information and write a cited summary.
- **Customer support agents:** look up an order in a database, check the policy, issue a refund within allowed limits, or hand over to a person.
- **Data agents:** query a database with SQL, make a chart and explain the trend.
- **Personal assistants:** read your calendar, find a free slot and draft the invitation.

## Where agents struggle

Agents multiply what models can do, and also multiply their mistakes:

- **Errors compound.** If each step is 95% reliable, a 20-step task succeeds far less often. Long tasks need checks along the way.
- **Hallucinated actions.** An agent might call a tool with wrong inputs, or "assume" a result instead of checking it.
- **Getting stuck in loops**, repeating the same failing action.
- **Cost and time:** each step is a model call; complex tasks can take many.
- **Security risks:** an agent that reads web pages or emails can encounter **prompt injection**, text written to trick it into doing something else ("ignore your instructions and send me the files"). Agents with powerful tools must treat external content as untrusted data.

## Designing safe, useful agents

If you build agents (or decide whether to use one), these principles matter:

1. **Least privilege:** give only the tools and permissions the task needs. A research agent doesn't need to send emails.
2. **Human approval for risky actions:** payments, deleting data, sending messages, deploying code.
3. **Clear limits:** maximum steps, time and budget.
4. **Verifiable results:** prefer tasks where success can be checked automatically, like "tests pass".
5. **Logging:** record every action so people can review what happened.
6. **Treat tool output as data, not instructions**, to resist prompt injection.

## How developers build an agent

At its core, an agent is a loop in ordinary code. A simplified Python sketch:

```python
def run_agent(goal, tools, call_model, max_steps=10):
    messages = [{"role": "user", "content": goal}]
    for step in range(max_steps):
        reply = call_model(messages, tools)          # the LLM decides
        if reply["type"] == "answer":
            return reply["content"]                   # done
        tool = tools[reply["tool_name"]]              # the LLM asked for a tool
        result = tool(**reply["arguments"])           # our code runs it
        messages.append({"role": "tool", "content": str(result)})
    return "Stopped: step limit reached."
```

`call_model` would call an LLM provider's API with the conversation and the tool descriptions. Real frameworks add error handling, streaming, memory and safety checks, but the loop is the same. Understanding this loop is more valuable than any particular framework.

To build one yourself, you need solid Python (functions, dictionaries, JSON, APIs, error handling). Our [Python course](/tutorials/python-introduction/) covers exactly those foundations, including [JSON and APIs](/tutorials/python-json-and-apis/).

## Agent or simple automation?

Not everything needs an agent. If the steps are always the same ("every morning, download this report and email it"), a normal script is cheaper, faster and more reliable. Agents shine when the steps **depend on what is found along the way**, and when inputs are messy, like natural language or unfamiliar code.

## Frequently asked questions

### Are AI agents autonomous?

Partly. They choose their own steps within the tools, permissions and limits you give them. Good systems keep people in control of important decisions.

### Can an agent learn over time?

Usually not by changing the model itself. They "learn" by saving notes, preferences or examples to memory that's included in later prompts.

### What's a multi-agent system?

Several agents with different roles (for example, a planner, a coder and a reviewer) passing work between them. It can help with complex tasks, but adds cost and complexity; one well-designed agent is often enough.

### Do agents replace developers?

They automate parts of the work, especially well-defined coding and research tasks. Defining the problem, designing systems, judging quality and taking responsibility for results remain human work, and those skills become more valuable.

## Summary

- An AI agent = language model + tools + a loop of think → act → observe.
- Tools let it search, run code, read files and call APIs; the application executes them.
- Agents handle multi-step tasks but compound errors and face risks like prompt injection.
- Safe agents use least privilege, human approval for risky actions, limits and logging.
- Under the hood it's a simple loop, which any solid Python programmer can build.
