---
title: Roles, Audience and Context
slug: prompt-roles-context
subject: prompt-engineering
lesson: 3
difficulty: beginner
excerpt: Use roles and audience to control tone and depth, give the AI the background it needs, and build reusable prompt templates.
---

"Explain APIs" gets a different answer depending on who's asking and who's answering. Two quick additions control that: a **role** for the AI and an **audience** for the answer.

## Give the AI a role

Starting with "You are…" sets the expertise, vocabulary and point of view:

```text
You are a senior Java developer reviewing a junior's code.
Point out bugs first, then style issues. Be kind but direct.
```

```text
You are a patient school teacher. Explain fractions to a 10-year-old
using a pizza example.
```

```text
You are an interviewer at a product company. Ask me one SQL interview
question at a time, wait for my answer, then give feedback and a score
out of 10.
```

The last one turns the AI into a mock interviewer: one of the most useful study tricks there is.

## Name the audience

The role decides who **writes**; the audience decides who **reads**:

| Same topic: "What is cloud computing?" | Effect |
|---|---|
| "…for my grandmother" | everyday comparisons, no jargon |
| "…for a BCA student's exam answer" | definitions, types, advantages, diagrams in words |
| "…for a CTO deciding between AWS and Azure" | costs, trade-offs, migration risks |

## Give it the background

The AI only knows what's in the conversation. Paste or describe what it needs:

```text
Context: I run a small bakery in Surat. Most orders come on WhatsApp.
I want more customers on weekdays, when sales are 40% lower.
Task: Suggest 5 low-cost weekday promotions I can announce on WhatsApp
and Instagram. For each, write the message I'd post.
```

Without the context, you'd get generic marketing tips. With it, you get messages you can post today.

::tip Most chat tools let you save background you use often: "custom instructions", "memory" or "projects". Put your role, goals and preferred style there once instead of repeating them every time.

## Build reusable templates

When a prompt works well, keep it with blanks to fill in:

```text
You are an expert {SUBJECT} tutor.
Explain {TOPIC} to a {LEVEL} learner who already knows {KNOWS}.
Use one real-life example and one short code/worked example.
End with 3 quiz questions (answers at the very end).
```

Fill it in: SUBJECT = Python, TOPIC = dictionaries, LEVEL = beginner, KNOWS = lists. You now have a personal tutor for any topic in 10 seconds.

## Persona prompts for studying

```text
Act as a strict examiner. I'll explain how HashMap works in Java.
Find every gap or mistake in my explanation, then give me a model answer.
```

Explaining a topic and getting critiqued is one of the fastest ways to learn, and the AI never gets tired of listening.

::hint Quick check | You want a beginner-friendly answer, but the AI keeps using heavy jargon. What do you add? An audience ("for someone with no technical background") and a constraint ("explain any technical term the first time you use it").

## Summary

- A role ("You are…") sets expertise and tone.
- An audience ("…for a beginner") sets depth and vocabulary.
- Give the AI the facts about your situation; it can't guess them.
- Save prompts that work as templates.
