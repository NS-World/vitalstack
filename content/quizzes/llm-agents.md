## llm-agents-what-makes-an-agent
Q: What makes an agent different from a chatbot?
- [x] It chooses tools, looks at results and decides the next step in a loop
- [ ] It uses a bigger font
- [ ] It never calls tools
> Chatbots answer; agents act until the job is done.
Q: Which part stops an agent from looping forever?
- [x] The stop rule: a final answer or a step limit
- [ ] The tool descriptions
- [ ] The temperature
> Write the stop rule first.
Q: Why do agents cost more than a single chat reply?
- [x] Every step is another model call with a growing context
- [ ] Agents use special expensive tokens
- [ ] They don't cost more
> A 4-step agent can use 6× the tokens of one reply.

## llm-agents-function-calling-tools
Q: When a model 'calls a tool', who runs the function?
- [x] Your code runs it and sends back the result
- [ ] The model runs it on its own servers
- [ ] Nobody
> The model only asks; your program does the work.
Q: A tool_result must carry...
- [x] The same id as the tool_use block it answers
- [ ] The user's password
- [ ] A random id
> The id links the result to the request.
Q: The model sends adults as the text "2" instead of a number. Best response:
- [x] Return a clear error result so it can fix the call
- [ ] Crash the program
- [ ] Ignore the argument silently
> Errors are results too; the model retries.

## llm-agents-langgraph
Q: In LangGraph, a node is...
- [x] A function that reads the state and returns updates
- [ ] A database table
- [ ] A CSS class
> Edges connect nodes; routers choose the next one.
Q: A conditional edge uses...
- [x] A router function that picks the next node from the state
- [ ] A fixed arrow only
- [ ] Random choice
> e.g. back to 'write' if the check failed.
Q: Why add a try limit in the router?
- [x] So a failing check can't loop forever
- [ ] To make the graph prettier
- [ ] LangGraph requires 3 tries
> End gracefully after N attempts.

## llm-agents-crewai
Q: In CrewAI, each task's output...
- [x] Becomes context for the next task
- [ ] Is deleted
- [ ] Goes to a random agent
> That's how work passes between roles.
Q: Why make expected_output concrete?
- [x] Vague expected outputs make crews ramble
- [ ] It's only decoration
- [ ] CrewAI ignores it
> e.g. '3 bullet points with a source URL each'.
Q: A three-agent crew compared with one good prompt usually...
- [x] Costs more calls, so use it only for multi-skill jobs
- [ ] Is always cheaper
- [ ] Needs no model
> Teams add cost and failure points.

## llm-agents-mcp
Q: MCP turns the 'N apps × M services' problem into...
- [x] N + M pieces: one protocol per app, one server per service
- [ ] N × M × 2
- [ ] Nothing changes
> That's why it's called 'USB-C for AI apps'.
Q: Which MCP method does a host call to discover a server's tools?
- [x] tools/list
- [ ] files/delete
- [ ] get_all
> Then tools/call uses one.
Q: Before installing an MCP server you should...
- [x] Check its source and which tools it exposes
- [ ] Give it all your passwords
- [ ] Install every server you find
> Servers run with your permissions.
