## capstone-agent-research
Q: A grounding check removes claims whose...
- [x] Quote isn't found in the cited source
- [ ] Source is short
- [ ] Text is long
> The invented claim cited a real source with a fake quote.
Q: A coverage eval checks...
- [x] Whether the report contains facts a good answer must include
- [ ] The report's font
- [ ] The number of workers
> Gaps show missing sources or missing facts.
Q: Research workers should have...
- [x] Read-only tools, because web pages are untrusted
- [ ] Payment tools
- [ ] Admin access
> No outbound actions means injection can't do harm.

## capstone-agent-support
Q: Where should the refund policy be enforced?
- [x] In the tool's code
- [ ] Only in the system prompt
- [ ] By the customer
> Prompts can be talked around; code can't.
Q: An angry message like 'worst service ever' should...
- [x] Escalate quickly to a human
- [ ] Get an automatic refund
- [ ] Be ignored
> Fast hand-off matters for upset customers.
Q: An end-state eval for a support agent checks...
- [x] What happened to each order
- [ ] How polite the greeting was only
- [ ] The agent's name
> Only eligible orders should change.

## capstone-agent-coding
Q: The coding agent loop is...
- [x] Run tests → read failure → inspect code → exact edit → re-run tests
- [ ] Rewrite the whole project at once
- [ ] Delete failing tests
> Small, verified steps.
Q: Why do edit tools require exactly one match?
- [x] So an edit can't silently change the wrong place
- [ ] To slow the agent down
- [ ] Files have only one line
> Ask for more context if it's ambiguous.
Q: Before merging an agent's change, a human should...
- [x] Review the diff and the test results
- [ ] Merge without looking
- [ ] Delete the branch
> The agent proposes; people decide.

## capstone-agent-browser
Q: Browser agents work better with...
- [x] A compact list of interactive elements than raw HTML
- [ ] The whole page source every step
- [ ] No view of the page
> Short snapshots fit the context.
Q: Why compare exact hostnames for the allow-list?
- [x] startswith checks are fooled by lookalike domains
- [ ] Exact checks are slower
- [ ] Hostnames don't matter
> vitalstack.co.in.evil.example is not your site.
Q: A click on 'Buy premium bundle ₹2,999' should...
- [x] Pause and ask the user
- [ ] Run immediately
- [ ] Be retried 5 times
> Purchases need a human.
