---
title: Writing Clear Prompts
slug: prompt-clear-instructions
subject: prompt-engineering
lesson: 2
difficulty: beginner
excerpt: The building blocks of a good prompt (task, context, examples, format and constraints), with before-and-after examples.
---

Most bad AI answers come from prompts that leave the AI guessing. Every guess is a chance to get it wrong. This lesson gives you a simple structure that removes the guessing. Step through the parts:

::viz prompt-anatomy

You won't need all six parts every time. A one-line question is fine for a one-line answer. But the more an answer matters, the more parts you include.

## 1. Start with a clear task

Use one strong verb: *explain, summarise, rewrite, compare, list, fix, translate, plan*.

```text
❌ Something about my resume
✅ Rewrite the summary section of my resume below to sound more confident.
```

## 2. Give the context it can't guess

The AI doesn't know your level, your goal or your audience. Tell it:

```text
❌ Explain recursion.
✅ Explain recursion to a first-year B.Tech student who understands loops
   but has never written a function that calls itself.
```

## 3. Say what the answer should look like

```text
✅ Answer in exactly 3 bullet points, each under 20 words.
✅ Give me a table with columns: Topic, Why it matters, Free resource.
✅ Keep it under 150 words and end with one practice question.
```

Without a format, the AI picks one for you, usually long paragraphs.

## 4. Add constraints

```text
✅ Use simple English; avoid jargon.
✅ Only use Python's standard library, no extra packages.
✅ Don't change the meaning, only fix grammar.
```

## Before and after

```text
Make a study plan.
```

The AI has to guess the subject, duration, your level and how much time you have. You'll get a generic plan.

```text
Make a 4-week study plan to learn SQL for data analyst interviews.
I'm a final-year BCom student, comfortable with Excel, and can study
1 hour on weekdays and 3 hours on weekends.
Format: a table with Week, Topics, Practice task, and a mini project at
the end of each week. Use free resources only.
```

Same request, but now the plan fits **you**.

## Put the important part first or last

In a long prompt, say the task in the first line, put long material (an article, your code, data) in the middle, and repeat the key instruction at the end:

```text
Summarise the meeting notes below for my manager.

"""
(paste the notes here)
"""

Summary: 5 bullet points, decisions first, then open questions.
```

Triple quotes (or `<notes>` tags) mark clearly where the pasted text starts and ends, so the AI doesn't confuse it with your instructions.

::tip Treat the first answer as a draft. Reply with what to change: "Shorter", "Use an example from cricket", "Make point 3 more specific". Iterating is part of prompting, not a failure.

::hint Quick check | Which part is missing here: "Write an email asking for leave"? Context (who it's to, which dates, why) and format (formal or friendly, length). Add them and the email is ready to send.

## Summary

- Lead with a clear task verb.
- Add the context the AI can't guess: audience, level, goal.
- Specify the format and constraints.
- Separate pasted material with quotes or tags, and iterate on the draft.
