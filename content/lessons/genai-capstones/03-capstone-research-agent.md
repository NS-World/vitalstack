---
title: "Capstone: Research Agent that Searches and Summarises"
slug: capstone-research-agent
subject: genai-capstones
lesson: 3
difficulty: intermediate
excerpt: Build an agent that researches a question: plan sub-questions, search, read sources, take notes, avoid duplicates, stop on a budget, and write a short report where every claim has a source. With a runnable simulation and a real web-search version.
---

"Compare the top 3 free Python courses for beginners." A good human researcher would break this into smaller questions, search, open a few sources, take notes and write a summary with links. A **research agent** does the same loop, and this capstone builds it with a budget, so it can't wander off and spend all your credits.

::scene The library sprint
🗂️ | Plan | Byte splits the big question into 3 small ones. | byte
🔎 | Search | Chip searches each one and skips sources it has already read. | chip
📝 | Notes | Dev keeps short notes, each tagged with its source link. | dev
⏱️ | Budget | After 6 tool calls Bugsy says "Stop and write." The report cites every claim. | bugsy
::end

## The design

| Part | Job |
|---|---|
| **Planner** | Turns the question into 2–4 sub-questions |
| **Search tool** | Returns titles + URLs for a query |
| **Read tool** | Returns the useful text of one URL |
| **Notes** | Short facts, each with its source |
| **Budget** | Max tool calls (and max tokens) |
| **Writer** | A report where every claim cites a note |

## A runnable simulation

Search and pages are faked, so you can see the loop's logic clearly:

```python
SEARCH = {
    "free python course beginners": ["site-a.example/python", "site-b.example/learn", "site-a.example/python"],
    "python course with projects": ["site-c.example/projects", "site-b.example/learn"],
}
PAGES = {
    "site-a.example/python": "Course A: 40 short videos, quizzes, certificate.",
    "site-b.example/learn": "Course B: interactive browser exercises, no install needed.",
    "site-c.example/projects": "Course C: 10 mini projects, community forum.",
}

def research(sub_questions, budget=6):
    seen, notes, calls = set(), [], 0
    for q in sub_questions:
        calls += 1
        for url in SEARCH.get(q, []):
            if url in seen or calls >= budget:
                continue
            seen.add(url)
            calls += 1
            notes.append({"fact": PAGES[url], "source": url})
    return notes, calls

notes, calls = research(["free python course beginners", "python course with projects"])
for n in notes:
    print(f"- {n['fact']} [{n['source']}]")
print("tool calls used:", calls)
```

Output:

```text
- Course A: 40 short videos, quizzes, certificate. [site-a.example/python]
- Course B: interactive browser exercises, no install needed. [site-b.example/learn]
- Course C: 10 mini projects, community forum. [site-c.example/projects]
tool calls used: 5
```

Five calls: two searches and three reads. The duplicate links were skipped, which saves budget for new sources.

## Why a budget matters

::walk python | Stopping on budget
budget = 6
calls = 0
for planned in ["search", "read", "read", "search", "read", "read", "read"]:
    if calls >= budget:
        break
    calls += 1
print(calls, "of 7 planned steps")
::notes
1: At most 6 tool calls for this question. // vars: budget=6
2: Nothing used yet. // vars: calls=0
3: The agent wanted 7 steps. // deep: Agents keep wanting "one more search". A fixed budget (calls, tokens or time) forces them to work with what they have, and makes cost predictable per question.
4: Check the budget before every step, not after.
5: Out of budget: stop and write the report with the notes so far.
6: Count the step.
7: The seventh read never happened. // out: 6 of 7 planned steps
::end

## The real version with Claude's web search tool

Claude can search the web through a server-side tool, so you don't need a separate search API:

```python
import anthropic

client = anthropic.Anthropic()
question = "Compare the top 3 free Python courses for complete beginners in 2026."
r = client.messages.create(
    model="claude-opus-5-5", max_tokens=2000,
    tools=[{"type": "web_search_20260209", "name": "web_search", "max_uses": 5}],   # the budget
    system=("You are a careful research assistant. Break the question into sub-questions, search, "
            "and write a short report: a comparison table, then 3 bullet takeaways. "
            "Every claim must cite a source. If sources disagree, say so."),
    messages=[{"role": "user", "content": question}])
print("".join(b.text for b in r.content if b.type == "text"))
```

Using another provider or your own search API? Wrap it as a normal tool (Function Calling lesson) and keep the same budget and notes.

## Quality checks

- **Every claim has a source.** Reject or regenerate sentences without one.
- **Sources are diverse:** not five pages from one site.
- **Fresh enough:** prefer recent pages for fast-changing topics.
- **Untrusted text:** web pages can contain prompt injection. Your agent has read-only tools, so the worst case is a bad note, never a harmful action.

## Make it portfolio-ready

- A Streamlit page: question in, live progress ("searching…", "reading 3 of 5…"), report out.
- Save reports as Markdown or PDF.
- An eval: 10 questions with facts a good report must contain; track coverage and citation rate.
- Show cost per report (tokens × price) in the UI.

::tip Show the agent's steps to the user as it works. People trust a report much more when they can see which sources were read, and it makes your demo video far more impressive.

## Summary

- Research agent = plan → search → read → note (with sources) → write, under a budget.
- Skip duplicate sources and check the budget before every step.
- Use a server-side web search tool or wrap your own search API.
- Require citations for every claim, and treat web text as untrusted.
