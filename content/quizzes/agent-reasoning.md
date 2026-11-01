## reasoning-react
Q: ReAct stands for...
- [x] Reason + Act
- [ ] React.js for agents
- [ ] Repeat Action
> Think, act, observe, repeat.
Q: Why did the lesson's calculator parse arithmetic instead of using eval?
- [x] eval on model text could run arbitrary code
- [ ] eval is slower
- [ ] eval can't add numbers
> Never eval untrusted text.
Q: In modern APIs, ReAct's 'Action:' text is replaced by...
- [x] Native tool calls with JSON arguments
- [ ] Regex only
- [ ] Longer prompts
> And thinking modes replace 'Thought:' text.

## reasoning-plan-and-execute
Q: A plan step is 'ready' when...
- [x] Everything it depends on is done
- [ ] It's the longest
- [ ] It was written last
> Ready steps can run in parallel.
Q: When a step fails, plan-and-execute agents...
- [x] Re-plan, with a limit on how many times
- [ ] Always stop forever
- [ ] Skip the error silently
> e.g. add 'ask for access' then retry.
Q: A cost advantage of plan-and-execute is...
- [x] A strong model plans while cheaper models or code execute
- [ ] It needs no model
- [ ] It's always one call
> Use the expensive model only where it matters.

## reasoning-reflection
Q: Reflection works best when the critique uses...
- [x] Real feedback like test results or sources
- [ ] The model's opinion only
- [ ] No feedback
> Grounded signals make self-correction reliable.
Q: A good stopping rule for reflection is...
- [x] Stop when checks pass or after 2–3 rounds
- [ ] Never stop
- [ ] Stop after 100 rounds
> Each round costs another call.
Q: Why use a separate critic prompt?
- [x] A distinct reviewer catches more problems
- [ ] It's cheaper
- [ ] Critics don't need prompts
> Don't let the writer grade its own homework.
