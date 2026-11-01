## multi-agent-orchestrator-workers
Q: Orchestrator-workers is best for tasks that are...
- [x] Broad and split into independent parts
- [ ] Tightly connected step by step
- [ ] One simple question
> e.g. comparing several products.
Q: Workers should return...
- [x] Short reports, not their whole conversation
- [ ] Every page they read
- [ ] Nothing
> That keeps the lead's context small.
Q: Compared with one agent, multi-agent systems usually...
- [x] Use several times more tokens
- [ ] Use fewer tokens
- [ ] Use no model
> Worth it only for broad, valuable tasks.

## multi-agent-handoffs-shared-state
Q: A shared state 'blackboard' lets...
- [x] Every agent read the notes others wrote
- [ ] Agents hide information
- [ ] Users edit the code
> Tech knew about the refund without asking.
Q: A hand-off packet should include...
- [x] A summary, open issues and the user's own words
- [ ] Only the agent's name
- [ ] The full raw logs always
> The user shouldn't have to repeat themselves.
Q: Why set a hand-off limit?
- [x] To stop agents passing the user back and forth forever
- [ ] To save disk space
- [ ] Hand-offs are illegal
> Ping-pong is a classic multi-agent bug.
