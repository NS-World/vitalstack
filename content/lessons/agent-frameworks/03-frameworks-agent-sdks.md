---
title: "Claude Agent SDK and OpenAI Agents SDK"
slug: frameworks-agent-sdks
subject: agent-frameworks
lesson: 3
difficulty: advanced
excerpt: The two provider agent SDKs side by side: the Claude Agent SDK (the agent loop behind Claude Code, with built-in file, search and shell tools, permissions, MCP and hooks) and the OpenAI Agents SDK (agents, function tools, handoffs, guardrails and tracing).
---

AI labs now ship their own agent toolkits. The **Claude Agent SDK** gives you the same agent loop that powers Claude Code, with built-in tools for files, search and shell commands. The **OpenAI Agents SDK** is a lightweight library of agents, tools, **handoffs** between agents, guardrails and tracing. Both save you from writing the loop yourself.

::scene Two toolkits
🧰 | Claude Agent SDK | Chip gets a ready-made workshop: read files, search code, run commands, with permission checks. | chip
🤝 | OpenAI Agents SDK | Byte sets up a front desk agent that hands customers to the right specialist. | byte
🔌 | Same tools | Dev writes one tool spec and turns it into both formats. | dev
🛂 | Permissions | Bugsy allows Read and Grep, but blocks Bash. | bugsy
::end

## Claude Agent SDK

Install `claude-agent-sdk` (Python) or `@anthropic-ai/claude-agent-sdk` (TypeScript), and set `ANTHROPIC_API_KEY`.

```python
import asyncio
from claude_agent_sdk import query, ClaudeAgentOptions

async def main():
    options = ClaudeAgentOptions(
        system_prompt="You are a careful code reviewer. Report problems; don't edit files.",
        allowed_tools=["Read", "Grep", "Glob"],     # read-only built-in tools
        max_turns=10,
        cwd="./my_project",
    )
    async for message in query(prompt="Find functions in src/ that have no docstring.", options=options):
        print(message)

asyncio.run(main())
```

What you get for free: the agent loop, built-in tools (read, write, edit, search, shell, web), context management for long runs, permission modes, **hooks** (run your code before or after a tool call, for example to block or log it), subagents, and MCP servers.

### Custom tools as an in-process MCP server

```python
from claude_agent_sdk import tool, create_sdk_mcp_server, ClaudeAgentOptions

@tool("lesson_minutes", "Minutes left to finish a course", {"course": str})
async def lesson_minutes(args):
    minutes = {"python": 54, "sql": 28}.get(args["course"], 0)
    return {"content": [{"type": "text", "text": f"{minutes} minutes"}]}

study = create_sdk_mcp_server(name="study", version="1.0.0", tools=[lesson_minutes])
options = ClaudeAgentOptions(mcp_servers={"study": study}, allowed_tools=["mcp__study__lesson_minutes"])
```

Tools from MCP servers are named `mcp__<server>__<tool>`, and only tools you allow can run:

::walk python | How allowed tools work
allowed = ["Read", "Grep", "mcp__study__lesson_minutes"]
requested = ["Read", "Bash", "mcp__study__lesson_minutes"]
blocked = [t for t in requested if t not in allowed]
print(blocked)
::notes
1: The tools this agent may use: two read-only built-ins and our custom tool. // vars: allowed=3 tools
2: The tools the model tried to use during a run. // vars: requested=3 tools
3: Anything not allowed is blocked (or, in other permission modes, sent to a human for approval). // deep: Combine allowed_tools with hooks for finer rules: allow Bash, but a pre-tool hook rejects any command containing "rm" or "curl". Start strict and loosen only what you need.
4: Shell access was blocked, so the reviewer can read code but never run commands. // out: ['Bash']
::end

## OpenAI Agents SDK

Install `openai-agents` and set `OPENAI_API_KEY`.

```python
from agents import Agent, Runner, function_tool

@function_tool
def lesson_minutes(course: str) -> int:
    """Minutes left to finish a course."""
    return {"python": 54, "sql": 28}.get(course, 0)

tutor = Agent(name="Tutor", instructions="Help learners plan study time.", tools=[lesson_minutes],
              model="MODEL_NAME")
billing = Agent(name="Billing", instructions="Answer questions about payments and refunds.", model="MODEL_NAME")
front_desk = Agent(name="Front desk", instructions="Hand off to Tutor for study questions, Billing for payments.",
                   handoffs=[tutor, billing], model="MODEL_NAME")

result = Runner.run_sync(front_desk, "How long will it take me to finish Python?")
print(result.final_output)
```

Key ideas: **agents** (instructions + tools + model), **handoffs** (one agent passes the conversation to another), **guardrails** (checks on input or output that can stop a run) and built-in **tracing** of every step. It can also be used with other providers' models through adapters.

## One tool spec, two formats

Keep tools framework-agnostic and generate each format:

```python
spec = {"name": "lesson_minutes", "description": "Minutes left to finish a course", "params": {"course": "string"}}

def json_schema(s):
    return {"type": "object", "properties": {k: {"type": v} for k, v in s["params"].items()},
            "required": list(s["params"])}

claude_tool = {"name": spec["name"], "description": spec["description"], "input_schema": json_schema(spec)}
openai_tool = {"type": "function", "name": spec["name"], "description": spec["description"],
               "parameters": json_schema(spec)}
print(sorted(claude_tool), sorted(openai_tool))
print(claude_tool["input_schema"] == openai_tool["parameters"])
```

Output:

```text
['description', 'input_schema', 'name'] ['description', 'name', 'parameters', 'type']
True
```

The schema is identical; only the wrapper keys differ.

## Side by side

| | Claude Agent SDK | OpenAI Agents SDK |
|---|---|---|
| Core idea | The Claude Code agent loop as a library | Small set of primitives: agents, handoffs, guardrails |
| Built-in tools | Files, search, shell, web, and more | Hosted tools (web search, file search, code) plus your functions |
| Multi-agent | Subagents | Handoffs, and agents used as tools |
| Safety controls | Permission modes, allowed tools, hooks | Guardrails, tool approval |
| Extending | MCP servers (in-process or external) | Function tools, MCP servers |
| Best fit | Agents that work on files, code and systems | Conversational multi-agent apps |

Both change quickly: read the current docs before you build.

::tip Whichever SDK you pick, write your tools as plain, tested Python functions first, then register them. Switching frameworks later becomes a small change instead of a rewrite.

## Summary

- The Claude Agent SDK provides Claude Code's loop: built-in tools, permissions, hooks, subagents and MCP.
- The OpenAI Agents SDK provides agents, function tools, handoffs, guardrails and tracing.
- Tool schemas are the same JSON Schema in both; only the wrapper differs.
- Lock down tools with allowed lists, hooks or guardrails.
