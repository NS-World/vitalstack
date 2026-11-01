## mcp-what-and-why
Q: In MCP, the host is...
- [x] The AI application that contains the clients and the model
- [ ] The server's database
- [ ] The JSON file
> Hosts contain one client per server.
Q: Which transport suits a remote, shared MCP server?
- [x] Streamable HTTP
- [ ] stdio
- [ ] Email
> stdio is for local servers started by the host.
Q: A JSON-RPC notification differs from a request because it...
- [x] Has no id and gets no reply
- [ ] Is always an error
- [ ] Is encrypted
> e.g. notifications/initialized.

## mcp-build-server
Q: FastMCP builds a tool's input schema from...
- [x] The function's type hints
- [ ] The file size
- [ ] The server name
> And the docstring becomes the description.
Q: Why must a stdio MCP server log to stderr?
- [x] stdout carries the protocol messages
- [ ] stderr is faster
- [ ] Logging is forbidden
> A stray print can break the connection.
Q: A quick way to test an MCP server by hand is...
- [x] MCP Inspector
- [ ] Deleting it
- [ ] Opening it in Excel
> List tools, call them and read resources.

## mcp-connect-agent
Q: To pass MCP tools to the Claude API you rename...
- [x] inputSchema to input_schema
- [ ] name to title
- [ ] tools to functions
> The schema itself stays the same.
Q: Why prefix tool names with the server name?
- [x] So tools from different servers can't clash
- [ ] To make names longer
- [ ] MCP requires it
> And a routing table finds the right server.
Q: New tools a server adds later should be...
- [x] Blocked until reviewed, unless read-only by your policy
- [ ] Allowed automatically
- [ ] Hidden from logs
> Approval for anything not on the allow-list.
