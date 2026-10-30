---
title: Step-by-Step Thinking and Prompt Chains
slug: prompt-step-by-step
subject: prompt-engineering
lesson: 5
difficulty: intermediate
excerpt: Get better answers to hard questions by asking the AI to reason step by step, and break big jobs into a chain of smaller prompts.
---

For a quick fact, a direct question is fine. For maths, logic, debugging or planning, the AI does much better when it works through the problem in steps instead of jumping to the answer.

## Ask it to think step by step

```text
A shop sells pens at ₹12 each, or a pack of 5 for ₹50. Asha needs 23 pens.
What is the cheapest way to buy them, and how much does it cost?
Work through it step by step, then give the final answer on its own line.
```

Because the model writes one token at a time, writing out the steps gives it room to calculate: each step becomes part of the text it builds on. Packs of 5 cost ₹10 per pen, so the plan is 4 packs (20 pens, ₹200) plus 3 single pens (₹36) = **₹236**, which beats buying 5 packs (25 pens) for ₹250.

Many newer models already reason internally ("thinking" or "reasoning" modes). Asking for steps still helps you **check** the logic, which matters more than speed.

## Give it a method

Even better, tell it how to approach the problem:

```text
I'm getting a NullPointerException in the Java method below.
1. First, explain in one sentence what the method is supposed to do.
2. List every variable that could be null at the line that fails.
3. For each, say how it could become null.
4. Then suggest the smallest fix, with the corrected code.

(paste code and the full error message here)
```

This mirrors how an experienced developer debugs, and you learn the method while reading the answer.

## Break big jobs into a chain

One giant prompt ("Build me a complete study app") produces something shallow. A **prompt chain** splits the job, and the output of each step feeds the next:

1. "List the features a study-planner app for college students should have. Group them as must-have and nice-to-have."
2. "For the must-have features, design the database tables (table name, columns, types)."
3. "Write the SQL `CREATE TABLE` statements for that design."
4. "Write Python functions to add and list study sessions using those tables."

Between steps, **you** review and correct. That's where your judgement keeps the AI on track.

::tip The same chain works for writing: outline → draft one section at a time → edit for tone → check facts. Asking for a whole 2,000-word report in one go gives you the weakest version of it.

## Let it ask you questions

When you're not sure what details matter, flip it around:

```text
I want to start a YouTube channel teaching Excel to small business owners.
Before giving any advice, ask me up to 5 questions you need answered
to give a specific plan. Ask them one at a time.
```

## Self-check

Ask the AI to review its own answer against your requirements:

```text
Now check your answer: does it meet every requirement in my first message?
List any it misses, then give a corrected version.
```

::hint Quick check | You asked for a 30-day fitness plan and got generic advice. What's a better approach? A chain: first ask it to ask you questions about your goal, schedule and level, then request the plan week by week.

## Summary

- For reasoning tasks, ask for steps, then the final answer on its own line.
- Give a method ("first… then…") for debugging and analysis.
- Split big jobs into a chain of smaller prompts, reviewing between steps.
- Let the AI ask clarifying questions, and ask it to check its own work.
