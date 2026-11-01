---
title: "What MCP Is and Why It Matters"
slug: mcp-what-and-why
subject: mcp
lesson: 1
difficulty: intermediate
excerpt: The Model Context Protocol from the inside: hosts, clients and servers, stdio and Streamable HTTP transports, the initialize handshake and capabilities, server primitives (tools, resources, prompts), client features (sampling, roots, elicitation) and the security model.
---

The **Model Context Protocol (MCP)** is an open standard for connecting AI applications to tools and data. Write an MCP server once, for your database, your notes or your company API, and any MCP-compatible app can use it: chat apps, IDEs, coding agents and your own agents. This module goes under the hood, then you'll build and connect your own server.

::scene The universal adapter
🏠 | Host | Dev's AI app is the host: it talks to the user and the model. | dev
🔌 | Client | Inside the host, one client connects to each server, like a plug per socket. | byte
🗄️ | Server | Chip's server offers tools and data from a course catalogue. | chip
🤝 | Handshake | Before anything else, they say hello and agree what each side supports. | bugsy
::end

## The architecture

| Piece | Role | Example |
|---|---|---|
| **Host** | The AI application; owns the model and the user interface | Claude Desktop, Claude Code, an IDE, your agent |
| **Client** | A connection from the host to one server (one client per server) | Created by the host |
| **Server** | A program exposing tools, resources and prompts | A GitHub server, a Postgres server, your notes server |

## Transports

| Transport | How | Use |
|---|---|---|
| **stdio** | The host starts the server as a local process and talks over stdin/stdout | Local tools: files, local databases, CLIs |
| **Streamable HTTP** | The server runs as a web service; clients connect over HTTP (with streaming) | Remote and shared servers, with OAuth authentication |

Messages are **JSON-RPC 2.0** on both transports.

## The handshake

Every session starts with `initialize`. Both sides state their protocol version and **capabilities**, so neither uses a feature the other doesn't support:

```python
import json

initialize = {"jsonrpc": "2.0", "id": 0, "method": "initialize",
              "params": {"protocolVersion": "2025-06-18",
                         "capabilities": {"roots": {}, "sampling": {}},
                         "clientInfo": {"name": "study-agent", "version": "1.0"}}}

reply = {"jsonrpc": "2.0", "id": 0,
         "result": {"protocolVersion": "2025-06-18",
                    "capabilities": {"tools": {"listChanged": True}, "prompts": {}},
                    "serverInfo": {"name": "course-catalog", "version": "0.1"}}}

initialized = {"jsonrpc": "2.0", "method": "notifications/initialized"}     # no id: a notification

print("client offers:", sorted(initialize["params"]["capabilities"]))
print("server offers:", sorted(reply["result"]["capabilities"]))
print("notification has an id?", "id" in initialized)
```

Output:

```text
client offers: ['roots', 'sampling']
server offers: ['prompts', 'tools']
notification has an id? False
```

Requests have an `id` and get a reply; **notifications** have no `id` and get no reply.

::walk python | Using only what the server supports
server_caps = {"tools": {"listChanged": True}, "prompts": {}}
wanted = ["tools", "resources", "prompts"]
usable = [f for f in wanted if f in server_caps]
print(usable, server_caps["tools"].get("listChanged", False))
::notes
1: The capabilities from the server's initialize reply. // vars: server_caps=tools + prompts
2: Features our host would like to use. // vars: wanted=['tools', 'resources', 'prompts']
3: Only use features the server announced. This server has no resources, so the host won't ask for any. // vars: usable=['tools', 'prompts']
4: listChanged means the server will send a notification when its tool list changes, so the host knows to call tools/list again. // deep: That's how a server can add tools at runtime, for example after the user logs in, and the host picks them up without restarting. // out: ['tools', 'prompts'] True
::end

## What servers offer

| Primitive | Who decides to use it | Example |
|---|---|---|
| **Tools** | The **model** (via tool calls) | `search_courses`, `create_issue` |
| **Resources** | The **app** or user (attach as context) | `course://python`, a file, a table schema |
| **Prompts** | The **user** (pick from a menu, like a slash command) | "Make a study plan for…" |

## What clients can offer servers

| Feature | Meaning |
|---|---|
| **Sampling** | The server asks the host's model to generate text (so the server needs no API key of its own) |
| **Roots** | The host tells the server which folders or locations it may work in |
| **Elicitation** | The server asks the user for extra input mid-task ("Which repository?") |

## The security model

MCP connects powerful tools to a model, so the host keeps humans in control:

- Users choose which servers to install and should be asked before tools run (especially writes).
- Servers run with the permissions you give them: a server with your GitHub token can do what you can do.
- Tool descriptions and results are **untrusted input** for the model: a malicious server or document can attempt prompt injection.
- Remote servers should use proper authentication (OAuth) and least-privilege tokens.

::tip Before installing any MCP server, read its tool list and ask: "What's the worst thing a confused model could do with these tools?" If the answer is scary, use a read-only version or require approval for every call.

## Summary

- Hosts contain clients; each client connects to one server; messages are JSON-RPC 2.0.
- Transports: stdio for local servers, Streamable HTTP for remote ones.
- `initialize` negotiates versions and capabilities; notifications have no id.
- Servers offer tools, resources and prompts; clients can offer sampling, roots and elicitation.
