---
title: "Plan-and-Execute"
slug: reasoning-plan-and-execute
subject: agent-reasoning
lesson: 2
difficulty: intermediate
excerpt: For long tasks, plan first and then execute: a planner writes steps with dependencies, an executor runs them in order (and in parallel where possible), and the agent re-plans when a step fails.
---

ReAct decides one step at a time, which works for short tasks. For a long job ("make a class performance report from this Google Sheet") it can wander. **Plan-and-execute** splits the work: a **planner** writes the whole plan first, an **executor** carries out each step, and if something fails, the planner **re-plans**.

::scene The class report
🗺️ | Plan | Byte writes 5 steps: collect marks, clean, averages, toppers, write the report. | byte
🔗 | Order | Averages and toppers both need clean data, but not each other: they can run side by side. | dev
💥 | Problem | Step 1 fails: "sheet not shared". Chip doesn't panic. | chip
🔁 | Re-plan | Bugsy adds "ask the teacher for access" and the plan continues. | bugsy
::end

## A plan with dependencies

Each step lists the steps it needs. Python's standard library can order them:

```python
from graphlib import TopologicalSorter

plan = {
    "collect_marks": set(),
    "clean_data": {"collect_marks"},
    "average_per_subject": {"clean_data"},
    "find_toppers": {"clean_data"},
    "write_report": {"average_per_subject", "find_toppers"},
}
print(list(TopologicalSorter(plan).static_order()))
```

Output:

```text
['collect_marks', 'clean_data', 'average_per_subject', 'find_toppers', 'write_report']
```

## What can run in parallel?

::walk python | Finding steps that are ready
done = {"collect_marks", "clean_data"}
plan = {"average_per_subject": {"clean_data"}, "find_toppers": {"clean_data"}, "write_report": {"average_per_subject", "find_toppers"}}
ready = [s for s, needs in plan.items() if needs <= done]
print(ready)
::notes
1: Steps already finished. // vars: done={'collect_marks', 'clean_data'}
2: Steps still to do, with what each needs. // vars: plan=3 steps left
3: A step is ready when everything it needs is done (needs ⊆ done). // deep: Running ready steps at the same time cuts total time. Here, averages and toppers can be computed by two workers in parallel; write_report must wait for both.
4: Two steps can start right now, side by side. // out: ['average_per_subject', 'find_toppers']
::end

## Execute and re-plan on failure

```python
attempts = {}

def execute(step):
    attempts[step] = attempts.get(step, 0) + 1
    if step == "collect_marks" and attempts[step] == 1:
        raise RuntimeError("sheet not shared with the agent")
    return f"{step}: ok"

def replan(failed_step, error):
    # in a real agent: send the plan, the failed step and the error to the planner model
    return ["ask_teacher_for_access", failed_step]

queue = ["collect_marks", "clean_data", "average_per_subject", "find_toppers", "write_report"]
log, replans = [], 0
while queue:
    step = queue.pop(0)
    if step == "ask_teacher_for_access":
        log.append("ask_teacher_for_access: email sent, access granted")
        continue
    try:
        log.append(execute(step))
    except RuntimeError as e:
        replans += 1
        if replans > 2:
            log.append("stopping: too many re-plans")
            break
        log.append(f"{step}: FAILED ({e}) -> re-plan")
        queue = replan(step, e) + queue
print("\n".join(log))
```

Output:

```text
collect_marks: FAILED (sheet not shared with the agent) -> re-plan
ask_teacher_for_access: email sent, access granted
collect_marks: ok
clean_data: ok
average_per_subject: ok
find_toppers: ok
write_report: ok
```

Notice the limit on re-plans: an agent that re-plans forever is as bad as one that loops forever.

## With real models

```python
import json
import anthropic

client = anthropic.Anthropic()
PLANNER = ("You are a planner. Break the task into 3-7 steps. Reply with JSON only: "
           '[{"id": "s1", "do": "...", "needs": []}, ...]. Each step must be doable with these tools: '
           "read_sheet, compute_stats, write_doc.")

def make_plan(task):
    r = client.messages.create(model="claude-opus-5-5", max_tokens=800, system=PLANNER,
                               messages=[{"role": "user", "content": task}])
    return json.loads(r.content[0].text)
```

The executor can be a smaller, cheaper model (or plain code) that does one step at a time with the tools. The expensive model is only used for planning and re-planning.

## ReAct or plan-and-execute?

| | ReAct | Plan-and-execute |
|---|---|---|
| Best for | Short, exploratory tasks | Long tasks with clear stages |
| Cost | One big model for every step | Big model plans; cheap model or code executes |
| Visibility | Decides as it goes | You can show the plan and ask for approval first |
| Weakness | Can wander | The plan can be wrong; needs re-planning |

Many real agents mix them: plan at the top level, ReAct inside each step.

::tip Show the plan to the user before running it ("Here's what I'll do: 1… 2… 3… OK?"). It catches misunderstandings early, and users love seeing what the agent intends.

## Summary

- Plan-and-execute = a planner writes steps with dependencies; an executor runs them.
- Ready steps (all dependencies done) can run in parallel.
- On failure, re-plan, with a limit on how many times.
- Use a strong model to plan and cheaper models or code to execute.
