## agents-plan-act-observe
Q: What are the three steps of the agent loop?
- [x] Plan → act → observe
- [ ] Read → write → delete
- [ ] Train → test → deploy
> The model plans the next step, your code acts (runs the tool), and the result is observed.
Q: Who actually runs a tool in an agent?
- [ ] The language model, directly on your computer
- [x] Your program, after checking the model's request
- [ ] The user, by hand
> The model only asks. Your code decides whether to run the tool.
Q: Why does an agent loop need a step limit?
- [ ] To make answers shorter
- [x] So a confused model can't loop forever and run up costs
- [ ] Because Python loops are slow
> Every step can cost money, so always cap steps (and time).

## agents-workflows-vs-agents
Q: Sorting 10,000 support emails into 3 fixed categories every day is best built as…
- [x] A workflow
- [ ] A multi-agent system
- [ ] An agent with web search
> The steps are known and never change, so a workflow is cheaper and easier to test.
Q: Pulling an order ID like "VS-2041" out of an email needs…
- [ ] A large language model
- [x] A regular expression in plain code
- [ ] Human review
> Use a model only for steps that need language understanding.
Q: An agent can send payments to suppliers. What should you add?
- [ ] A higher temperature
- [ ] More tools
- [x] A human approval step
> Costly or irreversible actions need a person to confirm.

## agents-first-agent-40-lines
Q: A tool fails because the model passed "Python" instead of "python". What should the agent do?
- [ ] Crash and show a stack trace
- [x] Send the error message back to the model so it can correct itself
- [ ] Ignore the step silently
> Errors become observations; the model can then retry with the right input.
Q: What is a trace?
- [x] A log of every step the agent took
- [ ] A type of tool
- [ ] The final answer
> Traces show what happened, so you can debug wrong answers.
Q: Why do tool descriptions matter so much?
- [ ] They make the code run faster
- [x] The model chooses tools only from their names and descriptions
- [ ] They are shown to the user
> Vague descriptions lead to wrong tool choices.
