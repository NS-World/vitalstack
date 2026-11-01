---
title: "Your First Agent in 40 Lines of Python"
slug: agents-first-agent-40-lines
subject: ai-agents
lesson: 3
difficulty: intermediate
excerpt: Build a complete study-planner agent with two tools, tool descriptions, error handling, a step limit and a trace, in about 40 lines of plain Python.
---

In lesson 1 the loop was tiny. A real agent needs four more things:

1. **Tool descriptions**, so the model knows what each tool does.
2. **Error handling**: the model will sometimes ask for a tool that doesn't exist, or pass bad input.
3. **A step limit** that ends the job politely instead of looping forever.
4. **A trace**: a log of every step, so you can debug what happened.

Our agent helps a student plan study time: "I have 3 lessons left in Python and 2 in SQL. How many minutes do I need, and which should I start with?"

## Step 1: tools with descriptions

Each tool has a **name**, a **description** written for the model, and the function itself. Real APIs send the name and description (plus an input schema) to the model with every request.

```python
LESSON_MINUTES = {"python": 18, "sql": 14, "html": 10}

def minutes_for(course, lessons):
    if course not in LESSON_MINUTES:
        raise ValueError(f"unknown course '{course}'")
    return LESSON_MINUTES[course] * lessons

def pick_first(a, b):
    return a if LESSON_MINUTES[a] <= LESSON_MINUTES[b] else b

TOOLS = {
    "minutes_for": {"fn": minutes_for,
                    "about": "Minutes needed for N lessons of a course."},
    "pick_first":  {"fn": pick_first,
                    "about": "Which of two courses has shorter lessons."},
}

for name, t in TOOLS.items():
    print(f"{name}: {t['about']}")
```

Output:

```text
minutes_for: Minutes needed for N lessons of a course.
pick_first: Which of two courses has shorter lessons.
```

::tip Write tool descriptions like instructions for a new intern: what it does, what to pass, what comes back. The model chooses tools **only** from these words, so vague descriptions cause wrong tool choices.

## Step 2: a model that makes a mistake

Our stand-in model follows a script. On purpose, its first request uses a wrong course name ("Python" with a capital P): real models make exactly this kind of slip.

```python
SCRIPT = [
    {"tool": "minutes_for", "args": {"course": "Python", "lessons": 3}},
    {"tool": "minutes_for", "args": {"course": "python", "lessons": 3}},
    {"tool": "minutes_for", "args": {"course": "sql", "lessons": 2}},
    {"tool": "pick_first",  "args": {"a": "python", "b": "sql"}},
    {"answer": "You need 82 minutes. Start with SQL: its lessons are shorter."},
]

def fake_model(history):
    turn = sum(1 for h in history if h["role"] == "tool")
    return SCRIPT[turn]
```

## Step 3: the agent, with errors and a limit

The important part is **what happens when a tool fails**. We don't crash. We send the error back to the model as an observation, so it can correct itself, just like a person reading an error message.

```python
def run_agent(goal, max_steps=6):
    history = [{"role": "user", "content": goal}]
    for step in range(1, max_steps + 1):
        reply = fake_model(history)
        if "answer" in reply:
            print(f"[{step}] answer")
            return reply["answer"]
        name, args = reply["tool"], reply["args"]
        try:
            if name not in TOOLS:
                raise ValueError(f"no tool called '{name}'")
            result = TOOLS[name]["fn"](**args)
            print(f"[{step}] {name}{args} -> {result}")
        except Exception as e:
            result = f"ERROR: {e}"
            print(f"[{step}] {name} failed: {e}")
        history.append({"role": "tool", "content": str(result)})
    return "Stopped: step limit reached."

print(run_agent("3 Python + 2 SQL lessons: time needed, and which first?"))
```

Output:

```text
[1] minutes_for failed: unknown course 'Python'
[2] minutes_for{'course': 'python', 'lessons': 3} -> 54
[3] minutes_for{'course': 'sql', 'lessons': 2} -> 28
[4] pick_first{'a': 'python', 'b': 'sql'} -> sql
[5] answer
You need 82 minutes. Start with SQL: its lessons are shorter.
```

The printed lines are the **trace**. Step 1 failed, the error went back into the history, and in step 2 the model fixed its own mistake. Without the `try/except`, the whole program would have crashed on step 1.

## Watch the error recovery, line by line

::walk python | How an error becomes a second chance
history = []
try:
    result = minutes_for("Python", 3)
except Exception as e:
    result = f"ERROR: {e}"
history.append(result)
result = minutes_for("python", 3)
history.append(result)
print(history)
::notes
1: An empty history for this small demo. // vars: history=[]
2: **try** says: run the next block, but if anything goes wrong, don't crash.
3: The model asked for "Python" with a capital P. Our tool raises **ValueError**: the dictionary only has "python". // deep: Python dictionary keys are case-sensitive: "Python" and "python" are different keys. Tools should either normalise input (course.lower()) or give a clear error, like this one.
4: The error is caught here, and its message is stored in **e**.
5: Instead of crashing, we turn the error into **text** the model can read. // vars: result=ERROR: unknown course 'Python'
6: **Observe:** the error message goes into the history like any other result. // vars: history=["ERROR: unknown course 'Python'"]
7: Next round, the model reads that error and retries with "python". 3 lessons × 18 minutes = **54**. // vars: result=54
8: The good result joins the history. // vars: history=["ERROR: unknown course 'Python'", 54]
9: The history shows the whole story: a mistake, then the fix. // out: ["ERROR: unknown course 'Python'", 54]
::end

## Step 4: the step limit saves you

What if the model never stops asking for tools? Let's make a broken model that always repeats the same request:

```python
def stuck_model(history):
    return {"tool": "pick_first", "args": {"a": "html", "b": "sql"}}

fake_model = stuck_model
print(run_agent("Plan my week", max_steps=3))
```

Output:

```text
[1] pick_first{'a': 'html', 'b': 'sql'} -> html
[2] pick_first{'a': 'html', 'b': 'sql'} -> html
[3] pick_first{'a': 'html', 'b': 'sql'} -> html
Stopped: step limit reached.
```

Three steps, then a clean stop. With a real API, every one of those steps costs money, so a step limit is not optional.

## Your agent checklist

- ✅ Tools have clear names and descriptions.
- ✅ Unknown tools and bad input become **error messages** for the model, not crashes.
- ✅ A **step limit** ends runaway loops.
- ✅ A **trace** prints every step for debugging.
- ✅ Dangerous tools (payments, emails, deleting) need **human approval** (lesson 2).

Swap `fake_model` for a real LLM API call that receives the history and the tool descriptions, and this is a working agent. In the next module you'll do exactly that, with JSON schemas for tool inputs.
