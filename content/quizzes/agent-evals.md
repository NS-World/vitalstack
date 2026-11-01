## evals-test-sets
Q: The most valuable agent check is usually...
- [x] The end state: what actually changed in the world
- [ ] What the agent said it did
- [ ] The length of the reply
> Check the order status, not the message.
Q: Why run each eval case several times?
- [x] Agents are non-deterministic
- [ ] To make reports longer
- [ ] It's never needed
> One lucky run proves little.
Q: An agent succeeds 90% of the time. pass^3 (all three succeed) is about...
- [x] 73%
- [ ] 99.9%
- [ ] 90%
> 0.9³ ≈ 0.729.

## evals-llm-judge
Q: Why keep critical rubric items as hard gates?
- [x] An average can hide a disaster like invented facts
- [ ] They're easier to type
- [ ] Judges ignore them
> One critical failure fails the reply.
Q: To cancel position bias in pairwise judging you...
- [x] Run each comparison in both orders
- [ ] Always put your favourite first
- [ ] Use a smaller model
> Count only consistent verdicts.
Q: Cohen's kappa measures...
- [x] Agreement between judge and humans beyond chance
- [ ] Model speed
- [ ] Token count
> 80% agreement gave kappa 0.52: only moderate.

## evals-tracing-observability
Q: A span is...
- [x] One step inside a trace, like a model or tool call
- [ ] A whole website
- [ ] An API key
> Spans nest inside the run.
Q: Which latency number best reflects unhappy users?
- [x] p95
- [ ] The fastest run
- [ ] The average only
> The slowest 5% complain.
Q: A failed production trace should become...
- [x] A new eval test case
- [ ] Deleted immediately
- [ ] A blog post
> Fix it once; the eval keeps it fixed.
