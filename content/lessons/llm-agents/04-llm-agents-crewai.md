---
title: "Teams of Agents with CrewAI"
slug: llm-agents-crewai
subject: llm-agents
lesson: 4
difficulty: intermediate
excerpt: Split a job across specialist agents: roles, goals and backstories, tasks with expected outputs, sequential and hierarchical crews, a runnable three-agent crew in plain Python, and what a crew costs.
---

Some jobs are easier for a **team of specialists** than one generalist: a researcher gathers facts, a writer drafts, an editor polishes. **CrewAI** is a framework built around that idea. You describe each agent's **role**, give the team **tasks**, and the crew passes work from one member to the next.

::scene The newsroom
🕵️ | Researcher | Byte collects three solid facts about UPI. Only facts, no writing. | byte
✍️ | Writer | Chip turns the facts into a short, friendly paragraph. | chip
✂️ | Editor | Bugsy adds a title and counts the words. Short and sharp. | bugsy
📰 | Published | Dev reads one clean post, built by three focused agents. | dev
::end

## The building blocks

| CrewAI piece | Meaning |
|---|---|
| **Agent** | A role (`"Researcher"`), a goal, a backstory, optional tools and an LLM |
| **Task** | A description, the **expected output**, and which agent does it |
| **Crew** | The agents + tasks + a **process** |
| **Process** | `sequential` (one after another) or `hierarchical` (a manager agent delegates) |

## A three-agent crew you can run

Each "agent" here is a simple function, so you can watch the hand-offs. In CrewAI, each would be an LLM with its own role prompt.

```python
NOTES = {"UPI": ["launched in 2016", "run by NPCI", "handles billions of payments a month"]}

crew = [
    {"role": "Researcher", "do": lambda topic, ctx: NOTES[topic]},
    {"role": "Writer", "do": lambda topic, ctx: f"{topic} was " + ", is ".join(ctx[:2]) + ", and " + ctx[2] + "."},
    {"role": "Editor", "do": lambda topic, ctx: f"# Why {topic} matters ({len(ctx.split())} words) | {ctx}"},
]

ctx = None
for member in crew:
    ctx = member["do"]("UPI", ctx)            # each member gets the previous member's output
    print(f"{member['role']:<10} -> {ctx}")
```

Output:

```text
Researcher -> ['launched in 2016', 'run by NPCI', 'handles billions of payments a month']
Writer     -> UPI was launched in 2016, is run by NPCI, and handles billions of payments a month.
Editor     -> # Why UPI matters (16 words) | UPI was launched in 2016, is run by NPCI, and handles billions of payments a month.
```

The key idea: **each task's output becomes the next task's context**.

## The same crew in CrewAI

```python
from crewai import Agent, Task, Crew, Process

researcher = Agent(role="Researcher", goal="Find 3 accurate, recent facts about {topic}",
                   backstory="A careful fact-checker who never guesses.", llm="anthropic/claude-opus-5-5")
writer = Agent(role="Writer", goal="Explain {topic} to beginners in one short paragraph",
               backstory="A friendly teacher who loves simple words.", llm="anthropic/claude-opus-5-5")

research = Task(description="Research {topic}.", expected_output="3 bullet-point facts with sources",
                agent=researcher)
post = Task(description="Write a 60-word post from the research.", expected_output="One paragraph, no jargon",
            agent=writer, context=[research])

crew = Crew(agents=[researcher, writer], tasks=[research, post], process=Process.sequential)
print(crew.kickoff(inputs={"topic": "UPI"}))
```

Give the researcher a search tool (CrewAI has a tools package, or wrap your own function) so its facts come from real sources.

## What a crew costs

::walk python | Estimating a crew's tokens
agents = 3
calls_per_agent = 4
tokens_per_call = 1500
total = agents * calls_per_agent * tokens_per_call
print(total, round(total / 1e6 * 3, 3))
::notes
1: A three-member crew. // vars: agents=3
2: Each agent may think, call tools and retry: about 4 model calls each. // vars: calls_per_agent=4
3: Role prompt + context + output per call. // vars: tokens_per_call=1500
4: Multiply it out. // deep: Hierarchical crews add a manager agent that also makes calls, so they usually cost more than sequential ones. Measure real runs with your provider's usage numbers before promising prices to users. // vars: total=18000
5: 18,000 tokens, about $0.054 at an example price of $3 per million. Cheap once, costly at 10,000 users a day. // out: 18000 0.054
::end

## When a team helps (and when it doesn't)

- **Helps:** the job has distinct skills (research vs writing vs code review), you want clear hand-offs you can inspect, or each role needs different tools.
- **Doesn't:** a single good prompt does the job. Three agents cost about three times as much and add more places to go wrong.

::tip Make every task's `expected_output` concrete ("3 bullet points with a source URL each"). Vague expected outputs are the number-one reason crews ramble.

## Summary

- CrewAI = agents (role, goal, backstory, tools) + tasks (description, expected output) + a process.
- Sequential crews pass each task's output to the next; hierarchical crews add a manager.
- Teams are great for multi-skill jobs, but cost more calls; measure before scaling.
