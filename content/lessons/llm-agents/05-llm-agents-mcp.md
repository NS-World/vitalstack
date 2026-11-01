---
title: "MCP: Connecting Agents to Apps and Data"
slug: llm-agents-mcp
subject: llm-agents
lesson: 5
difficulty: intermediate
excerpt: The Model Context Protocol in plain words: hosts, clients and servers, tools, resources and prompts, the JSON-RPC messages behind tools/list and tools/call, a tiny server in Python, connecting it to an AI app, and staying safe.
---

Every AI app wants to connect to the same things: files, GitHub, databases, calendars, Slack. Without a standard, each app writes its own connector for each service. The **Model Context Protocol (MCP)** is an open standard that fixes this: build a connector once as an **MCP server**, and any app that speaks MCP can use it. People call it "USB-C for AI apps".

::scene One plug for everything
🔌 | Before | Five AI apps × eight services = forty custom connectors. Dev is exhausted. | dev
🧩 | MCP | Each service ships one MCP server. Each app speaks MCP once. | byte
🗒️ | Notes server | Chip builds a small server that lets any AI app read study notes. | chip
🛡️ | Careful | Bugsy checks which tools it exposes before anyone installs it. | bugsy
::end

## Why a standard helps

::walk python | Connectors with and without MCP
apps = 5
tools = 8
without_mcp = apps * tools
with_mcp = apps + tools
print(without_mcp, with_mcp)
::notes
1: Five AI apps (chat apps, IDEs, your own agent). // vars: apps=5
2: Eight services (files, GitHub, a database…). // vars: tools=8
3: Without a standard, every app needs its own connector for every service. // vars: without_mcp=40
4: With MCP, each app implements the protocol once and each service ships one server. // deep: This is the same reason USB, HTTP and SQL drivers took off: an N × M problem becomes N + M. // vars: with_mcp=13
5: 40 pieces of glue code shrink to 13. // out: 40 13
::end

## The three roles

| Role | Example |
|---|---|
| **Host** | The AI app the user talks to (Claude Desktop, Claude Code, an IDE, your agent) |
| **Client** | The part of the host that keeps a connection to one server |
| **Server** | A small program exposing capabilities (your notes, a database, GitHub) |

A server can offer three kinds of things:

- **Tools:** actions the model can call (`search_notes`, `create_issue`).
- **Resources:** data the app can read (a file, a table, `notes://dsa`).
- **Prompts:** reusable prompt templates the user can pick.

## What actually travels on the wire

MCP messages are **JSON-RPC 2.0**. Here is a toy server handler so you can see the real message shapes:

```python
import json

NOTES = {"dsa": "Revise heaps and graphs", "sql": "Practise window functions"}

def handle(request):
    if request["method"] == "tools/list":
        result = {"tools": [{"name": "get_note", "description": "Read a study note by topic",
                             "inputSchema": {"type": "object", "properties": {"topic": {"type": "string"}},
                                             "required": ["topic"]}}]}
    elif request["method"] == "tools/call":
        topic = request["params"]["arguments"]["topic"]
        result = {"content": [{"type": "text", "text": NOTES.get(topic, "No note for that topic")}],
                  "isError": topic not in NOTES}
    else:
        return {"jsonrpc": "2.0", "id": request["id"], "error": {"code": -32601, "message": "Method not found"}}
    return {"jsonrpc": "2.0", "id": request["id"], "result": result}

listed = handle({"jsonrpc": "2.0", "id": 1, "method": "tools/list"})
print([t["name"] for t in listed["result"]["tools"]])
print(json.dumps(handle({"jsonrpc": "2.0", "id": 2, "method": "tools/call",
                         "params": {"name": "get_note", "arguments": {"topic": "sql"}}})))
print(handle({"jsonrpc": "2.0", "id": 3, "method": "files/delete"})["error"]["message"])
```

Output:

```text
['get_note']
{"jsonrpc": "2.0", "id": 2, "result": {"content": [{"type": "text", "text": "Practise window functions"}], "isError": false}}
Method not found
```

The host asks `tools/list`, shows the tools to the model, and when the model picks one, sends `tools/call`. Unknown methods get a standard error.

## A real MCP server in Python

The official Python SDK hides the JSON-RPC for you:

```python
from mcp.server.fastmcp import FastMCP

mcp = FastMCP("study-notes")
NOTES = {"dsa": "Revise heaps and graphs", "sql": "Practise window functions"}

@mcp.tool()
def get_note(topic: str) -> str:
    """Read a study note by topic, e.g. 'dsa' or 'sql'."""
    return NOTES.get(topic.lower(), "No note for that topic")

@mcp.resource("notes://{topic}")
def note_resource(topic: str) -> str:
    return NOTES.get(topic, "")

if __name__ == "__main__":
    mcp.run()          # talks over stdio by default
```

The function name, type hints and docstring become the tool's name, input schema and description.

## Connecting it to an app

- **Claude Code:** `claude mcp add study-notes -- python server.py`
- **Claude Desktop and many IDEs:** add the server to the app's MCP config file (a JSON entry with the command `python` and args `["server.py"]`).
- **Remote servers:** run over HTTP so many users can connect, with proper authentication.

Then just ask: "What did I note for SQL?" and the app calls `get_note`.

## Staying safe

An MCP server runs with **your** permissions. Before installing one:

- Install only from sources you trust, and read which tools it exposes.
- Prefer read-only tools; require confirmation for anything that writes, deletes, pays or sends.
- Treat text that comes back from tools as data, not instructions: a web page or file can contain a hidden prompt injection.
- Never put secrets in tool descriptions or results.

::tip Start your own MCP journey with a read-only server over something you use daily, like your notes folder or a small SQLite database. It's the fastest way to make an AI app genuinely useful to you.

## Summary

- MCP is an open standard connecting AI apps (hosts) to servers that expose tools, resources and prompts.
- Messages are JSON-RPC: `tools/list` to discover, `tools/call` to use.
- The Python SDK's FastMCP turns decorated functions into tools.
- Add servers to Claude Code, Claude Desktop or IDEs, and review permissions before you install.

🎉 You've finished **AI Agents and Tool Use**.
