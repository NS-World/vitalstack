---
title: "Connect an MCP Server to an Agent"
slug: mcp-connect-agent
subject: mcp
lesson: 3
difficulty: intermediate
excerpt: Use your MCP server from real apps and your own code: add it to Claude Code and desktop apps, write a Python MCP client, convert MCP tools into Claude tool definitions, bridge tool calls and results, handle several servers, and control permissions.
---

Your server works in Inspector. Now give it to an agent. There are two ways: plug it into an **existing host** (Claude Code, Claude Desktop, an IDE) with a few lines of config, or write **your own host**: an agent that connects to MCP servers and passes their tools to a model.

::scene Plugging in
⚙️ | Config | Dev adds one line to Claude Code. The course tools appear instantly. | dev
🐍 | Own agent | Chip writes a Python client that starts the server and lists its tools. | chip
🔁 | Bridge | Byte converts MCP tools into the model's tool format, and tool results back again. | byte
🛂 | Permission | Bugsy makes write tools ask for approval. Read tools run freely. | bugsy
::end

## Option 1: an existing host

- **Claude Code:** `claude mcp add course-catalog -- python /path/to/server.py`, then ask "Which intermediate courses are under 2 hours?"
- **Claude Desktop and many IDEs:** add an entry to the app's MCP configuration (a server name, the command `python` and the args `["/path/to/server.py"]`), then restart.
- **Remote servers:** add the server's URL; the host handles OAuth sign-in if the server needs it.

The host lists your tools, shows them to the model, asks the user for permission as configured, and runs the calls.

## Option 2: your own agent

### Connect with the Python SDK

```python
import asyncio
from mcp import ClientSession, StdioServerParameters
from mcp.client.stdio import stdio_client

async def main():
    params = StdioServerParameters(command="python", args=["server.py"])
    async with stdio_client(params) as (read, write):
        async with ClientSession(read, write) as session:
            await session.initialize()
            tools = await session.list_tools()
            print([t.name for t in tools.tools])
            result = await session.call_tool("course_detail", {"slug": "sql"})
            print(result.content[0].text)

asyncio.run(main())
```

### Convert MCP tools for the model

MCP describes tools with `inputSchema`; the Claude API calls the same thing `input_schema`. With more than one server, prefix names so they can't clash:

```python
mcp_tools = {
    "courses": [{"name": "search_courses", "description": "Find courses by level and length.",
                 "inputSchema": {"type": "object", "properties": {"level": {"type": "string"},
                                                                  "max_minutes": {"type": "integer"}}}},
                {"name": "course_detail", "description": "Get one course by slug.",
                 "inputSchema": {"type": "object", "properties": {"slug": {"type": "string"}}, "required": ["slug"]}}],
    "notes": [{"name": "search", "description": "Search my study notes.",
               "inputSchema": {"type": "object", "properties": {"query": {"type": "string"}}, "required": ["query"]}}],
}

def to_claude(server, tool):
    return {"name": f"{server}__{tool['name']}", "description": tool.get("description", ""),
            "input_schema": tool["inputSchema"]}

claude_tools = [to_claude(server, t) for server, tools in mcp_tools.items() for t in tools]
route = {t["name"]: t["name"].split("__", 1) for t in claude_tools}
print([t["name"] for t in claude_tools])
print(route["notes__search"])
```

Output:

```text
['courses__search_courses', 'courses__course_detail', 'notes__search']
['notes', 'search']
```

When the model calls `notes__search`, the `route` table tells your code to call tool `search` on the `notes` server.

### Convert results back

::walk python | From an MCP result to a tool_result
result = {"content": [{"type": "text", "text": "SQL for Analysis (beginner, 180 min)"}], "isError": False}
text = "\n".join(c["text"] for c in result["content"] if c["type"] == "text")
tool_result = {"type": "tool_result", "tool_use_id": "toolu_07", "content": text, "is_error": result["isError"]}
print(tool_result["content"], tool_result["is_error"])
::notes
1: What session.call_tool returned: a list of content items plus an error flag. // vars: result=1 text item, isError=False
2: Join the text items (results can also hold images or embedded resources). // deep: For image content you can pass an image block to the model instead of text. Keep large results short: summarise or truncate before sending them back.
3: Wrap it as a tool_result with the tool_use_id from the model's request, and carry over the error flag.
4: Ready to send back to the model in the next user message. // out: SQL for Analysis (beginner, 180 min) False
::end

### The full loop

```python
import anthropic

client = anthropic.Anthropic()

async def agent(question, sessions):          # sessions: {"courses": ClientSession, "notes": ClientSession}
    messages = [{"role": "user", "content": question}]
    for _ in range(8):
        r = client.messages.create(model="claude-opus-5-5", max_tokens=1024,
                                   tools=claude_tools, messages=messages)
        messages.append({"role": "assistant", "content": r.content})
        if r.stop_reason != "tool_use":
            return "".join(b.text for b in r.content if b.type == "text")
        results = []
        for b in r.content:
            if b.type == "tool_use":
                server, name = route[b.name]
                out = await sessions[server].call_tool(name, b.input)
                text = "\n".join(c.text for c in out.content if c.type == "text")
                results.append({"type": "tool_result", "tool_use_id": b.id, "content": text,
                                "is_error": out.isError})
        messages.append({"role": "user", "content": results})
```

Frameworks can do this bridging for you: the Claude Agent SDK and OpenAI Agents SDK accept MCP servers directly, LangChain has MCP adapters, and the Claude API offers an MCP connector for remote servers.

## Permissions in your own host

```python
READ_ONLY = {"courses__search_courses", "courses__course_detail", "notes__search"}

def needs_approval(tool_name):
    return tool_name not in READ_ONLY

print(needs_approval("courses__course_detail"), needs_approval("notes__delete_note"))
```

Output:

```text
False True
```

Anything not on the read-only list waits for a human "yes". New tools a server adds later are blocked until you review them.

::tip Start your own host with exactly one server and one tool. Get that round trip perfect (call, result, error) before adding more. Debugging five servers at once is painful.

## Summary

- Existing hosts: add the server with one command or a config entry.
- Your own host: connect with `ClientSession`, list tools, convert `inputSchema` → `input_schema`.
- Prefix tool names per server and keep a routing table.
- Convert results back to `tool_result` blocks, and require approval for non-read-only tools.

🎉 You've finished **MCP: Model Context Protocol**.
