---
title: "ReAct: Reason and Act"
slug: reasoning-react
subject: agent-reasoning
lesson: 1
difficulty: intermediate
excerpt: The pattern behind most agents: think, act, observe, repeat. Run a classic text-based ReAct loop with a safe calculator, parse actions, and see how native tool calling and thinking models implement the same idea today.
---

**ReAct** (Reason + Act) is the idea that made LLM agents practical: instead of answering in one go, the model **writes a thought**, **takes an action** (a tool call), reads the **observation**, and repeats. Thinking between actions lets it plan the next step from real results instead of guessing.

::scene The GST question
❓ | Question | "What does the course cost with GST?" Chip doesn't know the price or the rate. | dev
💭 | Thought | "I need the price first." Action: look up the price. Observation: ₹2,499. | chip
🧮 | Act again | "Now the GST rate." 18%. "Multiply 2,499 by 1.18." The calculator says 2,948.82. | byte
✅ | Answer | "₹2,948.82 including 18% GST." Every number came from a tool, none was guessed. | bugsy
::end

## The format

The original ReAct prompt asked the model to write in a fixed format:

```text
Thought: what I should do next
Action: tool_name[input]
Observation: (filled in by your code)
... repeat ...
Final Answer: the answer
```

## A ReAct loop you can run

The model's outputs are scripted so you can watch the loop. Note the calculator: it **parses** arithmetic safely instead of using `eval` on model text.

```python
import ast
import operator
import re

OPS = {ast.Add: operator.add, ast.Sub: operator.sub, ast.Mult: operator.mul, ast.Div: operator.truediv}

def calc(node):
    if isinstance(node, ast.Constant) and isinstance(node.value, (int, float)):
        return node.value
    if isinstance(node, ast.BinOp) and type(node.op) in OPS:
        return OPS[type(node.op)](calc(node.left), calc(node.right))
    raise ValueError("only + - * / on numbers")

def calculate(expr):
    return str(round(calc(ast.parse(expr, mode="eval").body), 2))

FACTS = {"course_price": "2499", "gst_rate": "18%"}
TOOLS = {"lookup": lambda key: FACTS.get(key, "unknown"), "calculate": calculate}

SCRIPT = [
    "Thought: I need the course price first.\nAction: lookup[course_price]",
    "Thought: Now the GST rate.\nAction: lookup[gst_rate]",
    "Thought: Price times 1.18.\nAction: calculate[2499 * 1.18]",
    "Thought: I have everything.\nFinal Answer: ₹2,948.82 including 18% GST",
]

transcript = ""
for step, output in enumerate(SCRIPT, 1):
    transcript += output + "\n"
    if "Final Answer:" in output:
        print("answer:", output.split("Final Answer:")[1].strip())
        break
    name, arg = re.search(r"Action: (\w+)\[(.*)\]", output).groups()
    observation = TOOLS[name](arg)
    transcript += f"Observation: {observation}\n"
    print(f"step {step}: {name}[{arg}] -> {observation}")
print(len(transcript.splitlines()), "lines in the transcript the model would see")
```

Output:

```text
step 1: lookup[course_price] -> 2499
step 2: lookup[gst_rate] -> 18%
step 3: calculate[2499 * 1.18] -> 2948.82
answer: ₹2,948.82 including 18% GST
11 lines in the transcript the model would see
```

The **transcript** grows each step and is sent back to the model, so it always sees its earlier thoughts and observations.

::walk python | Parsing the model's action
import re
output = "Thought: Now the GST rate.\nAction: lookup[gst_rate]"
match = re.search(r"Action: (\w+)\[(.*)\]", output)
name, arg = match.groups()
print(name, arg)
::notes
1: Regular expressions pull the action out of free text.
2: One step of model output: a thought, then an action. // vars: output=Thought + Action
3: Find "Action: name[input]": the tool name is word characters, the input is whatever is inside the brackets. // deep: Text parsing is fragile: the model might write "Action : lookup (gst_rate)" and the regex fails. That's exactly why native tool calling (next section) replaced this in production; but the loop is identical.
4: Split the match into the tool name and its input. // vars: name=lookup, arg=gst_rate
5: Your code now runs lookup("gst_rate") and appends the observation. // out: lookup gst_rate
::end

## ReAct today

Modern APIs build ReAct in:

| Classic ReAct | Today |
|---|---|
| "Thought:" text | **Thinking** or reasoning modes: the model reasons internally between tool calls |
| "Action: tool[input]" text | **Native tool calls** with JSON arguments (Tool Use module) |
| "Observation:" text | `tool_result` messages |
| Regex parsing | No parsing: structured blocks from the API |

So when you write a tool loop with Claude (with thinking turned on), you're running ReAct. The ideas still matter: observe before acting again, and ground every fact in a tool result.

## ReAct in a system prompt

Even with native tools, a short instruction helps:

```text
Work step by step. Before each tool call, decide what you still need to know.
After each result, check whether it answers the question or changes your plan.
Never state a number that didn't come from a tool result or the user.
```

## Where ReAct struggles

- **Long tasks:** it decides one step at a time, so it can wander. Plan-and-execute (next lesson) helps.
- **Loops:** repeating the same action. Detect repeated tool calls with the same input and stop.
- **Bad observations:** if a tool returns wrong data, the model trusts it. Validate tool outputs.

::tip Log the whole transcript (thoughts, actions, observations) for every run. When an answer is wrong, you can see exactly which step went off track.

## Summary

- ReAct = reason → act → observe, repeated until a final answer.
- The growing transcript is the agent's working memory.
- Native tool calling and thinking modes are today's built-in ReAct.
- Ground facts in tool results, detect loops, and validate observations.
