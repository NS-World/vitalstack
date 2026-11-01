---
title: "CrewAI in Depth"
slug: frameworks-crewai
subject: agent-frameworks
lesson: 2
difficulty: advanced
excerpt: Build production-style crews: project layout with YAML agents and tasks, custom tools, structured task outputs, task guardrails that retry bad results, sequential vs hierarchical processes, memory, and Flows for step-by-step control.
---

In the Gen AI track you met CrewAI's agents, tasks and crews. Real crews need more structure: config files instead of giant Python blocks, **custom tools**, **structured outputs** you can trust, **guardrails** that send bad work back, and **Flows** when you need precise control over the order of things.

::scene The content studio
📁 | Config | Byte keeps every role and task in two tidy YAML files. | byte
🧰 | Tools | Chip gives the researcher a custom "course catalogue" tool. | chip
🚧 | Guardrail | The writer's post is 180 words; the limit is 120. Bugsy's guardrail sends it back. | bugsy
🌊 | Flow | Dev wraps the crew in a Flow: research → write → check → publish, step by step. | dev
::end

## Project layout

`crewai create crew course_promo` makes a project like:

```text
course_promo/
  src/course_promo/
    config/agents.yaml     # roles, goals, backstories
    config/tasks.yaml      # descriptions, expected outputs
    crew.py                # wires agents, tasks and tools together
    tools/                 # your custom tools
    main.py                # crewai run starts here
```

```yaml
# config/agents.yaml
researcher:
  role: Course researcher
  goal: Find the 3 most useful facts about {course} for beginners
  backstory: You check every fact against the course catalogue.
writer:
  role: Friendly copywriter
  goal: Write a short, honest post that makes beginners want to try {course}
  backstory: You write simply and never exaggerate.
```

Keeping prompts in YAML means non-programmers can tweak roles without touching code.

## Custom tools

```python
from crewai.tools import tool

@tool("Course catalogue")
def course_catalogue(slug: str) -> str:
    """Return title, level, length and lesson count for a course slug like 'python'."""
    data = {"python": "Python Basics, beginner, 4 hours, 12 lessons"}
    return data.get(slug, "unknown course")
```

## Structured output and guardrails

Ask a task for a Pydantic model (`output_pydantic=Post`) to get typed results. Then add a **guardrail**: a function that checks the output and either accepts it or sends it back with feedback. Here's the logic, runnable:

```python
def word_limit_guardrail(text, limit=120):
    words = len(text.split())
    if words > limit:
        return False, f"Too long: {words} words. Cut to at most {limit}."
    return True, text

drafts = ["word " * 180, "word " * 150, "word " * 110]    # what the writer agent produces on each try
for attempt, draft in enumerate(drafts, 1):
    ok, result = word_limit_guardrail(draft)
    print(f"try {attempt}:", "accepted" if ok else result)
    if ok:
        break
```

Output:

```text
try 1: Too long: 180 words. Cut to at most 120.
try 2: Too long: 150 words. Cut to at most 120.
try 3: accepted
```

In CrewAI, you attach the function to the task (in recent versions: `Task(..., guardrail=word_limit_guardrail)`); the framework retries the task with your feedback, up to a retry limit.

::walk python | A guardrail's contract
def guardrail(text):
    if "guaranteed job" in text.lower():
        return False, "Remove job guarantees: we can't promise that."
    return True, text
print(guardrail("Learn Python. Guaranteed job in 30 days!"))
::notes
1: A guardrail takes the task's output.
2: A business rule: no promises of jobs (honest marketing). // deep: Guardrails are perfect for rules a model may forget: banned claims, required disclaimers, word limits, valid JSON. Code checks are cheap and certain; use an LLM check only for fuzzy rules.
3: Reject with feedback the agent can act on.
4: Otherwise accept, passing the output through.
5: The draft broke the rule, so it goes back with a clear instruction. // out: (False, "Remove job guarantees: we can't promise that.")
::end

## Processes

| Process | How it works | Use when |
|---|---|---|
| `Process.sequential` | Tasks run in order; each gets the previous outputs as context | The steps are known |
| `Process.hierarchical` | A manager agent (set `manager_llm`) delegates tasks and reviews results | The work needs dynamic assignment |

Set `memory=True` on the crew to let agents keep short- and long-term memory across tasks and runs.

## Flows: when you need control

Crews are autonomous. **Flows** are event-driven Python: you decide exactly what runs after what, and can mix plain code, single LLM calls and whole crews:

```python
from crewai.flow.flow import Flow, start, listen

class PromoFlow(Flow):
    @start()
    def pick_course(self):
        return "python"

    @listen(pick_course)
    def write_post(self, course):
        return PromoCrew().crew().kickoff(inputs={"course": course}).raw   # PromoCrew: the crew from crew.py

    @listen(write_post)
    def save(self, post):
        open("post.md", "w").write(post)

PromoFlow().kickoff()
```

A good rule: **Flow for the backbone, crews for the creative parts.**

::tip Run the same crew 5 times on the same input and compare outputs. If they vary wildly, tighten `expected_output`, add guardrails, or turn that part into a Flow step.

## Summary

- Keep agents and tasks in YAML; wire them in `crew.py`.
- Add custom tools with `@tool`, structured outputs with Pydantic, and guardrails that retry bad work.
- Sequential for known steps, hierarchical for delegation; `memory=True` for memory.
- Use Flows for precise, step-by-step control around crews.
