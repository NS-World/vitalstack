## frameworks-langgraph
Q: A LangGraph reducer decides...
- [x] How a node's update merges with the old state value
- [ ] Which model to use
- [ ] The UI colours
> Replace by default; append for messages.
Q: tools_condition routes to the tools node when...
- [x] The last message has tool calls
- [ ] Always
- [ ] The user says hello
> Otherwise the graph ends.
Q: interrupt() in a node lets you...
- [x] Pause for human approval and resume later with Command
- [ ] Delete the graph
- [ ] Speed up the model
> State is saved by the checkpointer.

## frameworks-crewai
Q: A CrewAI task guardrail...
- [x] Checks the output and sends it back with feedback if it fails
- [ ] Encrypts the task
- [ ] Deletes agents
> e.g. a 120-word limit.
Q: CrewAI Flows are best for...
- [x] Precise, step-by-step control around crews
- [ ] Drawing images
- [ ] Training models
> Flow for the backbone, crews for creative parts.
Q: Keeping agents and tasks in YAML lets...
- [x] Non-programmers tweak roles without touching code
- [ ] Python run faster
- [ ] Crews skip the model
> Config and code stay separate.

## frameworks-agent-sdks
Q: The Claude Agent SDK provides...
- [x] Claude Code's agent loop with built-in tools, permissions, hooks and MCP
- [ ] Only a chat UI
- [ ] A database
> File, search and shell tools come built in.
Q: In the OpenAI Agents SDK, a handoff...
- [x] Passes the conversation to another agent
- [ ] Ends the program
- [ ] Uploads a file
> e.g. Front desk → Tutor.
Q: A tool allowed as Read and Grep but not Bash means...
- [x] The agent can read code but can't run commands
- [ ] Everything is blocked
- [ ] Bash runs anyway
> Unlisted tools are blocked or need approval.

## frameworks-choosing
Q: Known steps, one agent and no pausing usually means...
- [x] Plain code is enough
- [ ] You need three frameworks
- [ ] Use a managed platform
> Frameworks help when you need what they provide.
Q: A good way to choose between two frameworks is...
- [x] A one-day spike building the same small task in each
- [ ] Picking the most popular on social media
- [ ] Reading only the logo
> Compare code, debugging and cost.
Q: To avoid lock-in, keep...
- [x] Tools, prompts and evals portable
- [ ] Everything inside framework classes
- [ ] No tests
> Then switching takes days, not months.
