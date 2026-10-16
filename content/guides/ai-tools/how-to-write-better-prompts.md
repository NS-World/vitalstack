---
title: How to Write Better AI Prompts: A Practical Guide
slug: how-to-write-better-prompts
category: ai-tools
tags: prompting, AI assistants
order: 3
excerpt: A practical framework for writing prompts that get useful answers from AI assistants, with before-and-after examples for coding, writing, learning and data tasks.
---

**Short answer:** a good prompt gives the AI what a smart new colleague would need: the **goal**, the **context**, the **constraints**, the **format** you want, and ideally an **example**. Vague prompts get generic answers; specific prompts get useful ones.

AI assistants are only as helpful as the instructions they receive. This guide gives you a simple framework and real before-and-after examples you can adapt today.

## Why prompts matter

A language model predicts a likely response to your text (see [How Large Language Models Work](/how-large-language-models-work/)). If your prompt could mean ten different things, you'll get an average of all ten. If it's precise, the most likely response is the one you actually want.

Compare:

```text
Weak:   Write about Python.

Strong: Write a 150-word introduction to Python for a complete beginner
        who wants to analyse sales data in Excel. Mention one thing Python
        does better than Excel, avoid jargon, and end with a suggested first step.
```

The second prompt answers every question the AI would otherwise guess: who it's for, how long, what to include, what tone, how to end.

## The framework: G-C-C-F-E

### Goal: what do you want?

Start with the task in one clear sentence. Use action verbs: *explain, write, fix, compare, summarise, list, rewrite, review*.

### Context: what does it need to know?

Who's the audience? What have you already tried? What's the situation? The AI knows nothing about your project, your level or your constraints unless you say so.

### Constraints: what are the rules?

Length, tone, language, things to avoid, tools or versions to use: "Use only the standard library", "no more than 5 bullet points", "keep my original meaning".

### Format: how should the answer look?

Bullet list, table, numbered steps, code only, JSON with specific keys, an email ready to send. If you'll paste the answer somewhere, say how it needs to look.

### Example: show what good looks like

One short example often beats a paragraph of description. Show the style, structure or output you want.

You don't need all five every time. For quick questions, goal plus context is often enough. For anything you'll reuse or publish, use all five.

## Before and after: coding

```text
Weak:
My code doesn't work, fix it.
[code]

Strong:
This Python function should return the average of a list of numbers,
but it crashes with "ZeroDivisionError" when the list is empty.

[code]

1. Explain in one sentence why it crashes.
2. Fix it so an empty list returns 0.
3. Keep the function name and signature the same.
4. Add two quick test cases using assert.
```

What changed: the expected behaviour, the exact error, the constraint (same signature) and the output format (explanation, fix, tests).

**Tip:** always paste the **exact error message** and say what you expected to happen. "It doesn't work" forces the AI to guess.

## Before and after: learning a concept

```text
Weak:
Explain recursion.

Strong:
I'm learning JavaScript and understand loops and functions, but recursion
confuses me. Explain recursion using a real-life analogy first, then a
simple JavaScript example that counts down from 5, then show me the same
thing as a loop so I can compare. Finish with one practice exercise
(without the answer).
```

Asking for an analogy, a comparison with something you already know, and an exercise turns the AI into a tutor instead of an encyclopaedia.

## Before and after: writing

```text
Weak:
Write an email to my manager about leave.

Strong:
Write a short, polite email to my manager, Priya, asking for leave on
the 14th and 15th for a family wedding. I've already finished the report
due that week and Rohan has agreed to cover urgent messages. Tone: friendly
but professional. Under 120 words. Include a subject line.
```

The details make the email ready to send, instead of a template full of [brackets].

## Before and after: data tasks

```text
Weak:
Analyse this data.

Strong:
Below is CSV data of monthly sales (columns: month, region, revenue).
1. Which region grew the most from the first to the last month, in %?
2. Are there any months that look like errors or outliers?
3. Give the answer as a short table, then 2–3 sentences of insight.
If something can't be determined from the data, say so instead of guessing.

[data]
```

The last line is important: **give the AI permission to say "I don't know"**. It reduces made-up answers.

## Techniques that work

### Assign a role (lightly)

"Act as a senior Python reviewer" or "You're a patient tutor for beginners" sets the tone and depth. It helps, but specifics about the task matter more than the role.

### Ask for steps before the answer

For reasoning problems, "work through this step by step, then give the final answer" often improves accuracy, and lets you see where it went wrong.

### Break big tasks into smaller prompts

Instead of "build me a complete website", go step by step: plan the pages → write the HTML for the home page → add the CSS → add the contact form. You stay in control and can correct course early.

### Iterate: the first answer is a draft

Reply with what to change: "Shorter", "more examples", "use a table", "the second point is wrong because…". Conversation is part of prompting.

### Ask it to ask you questions

"Before you answer, ask me up to 3 questions about anything that's unclear." Great for plans, designs and anything personal.

### Use delimiters for pasted content

Separate instructions from material with clear markers:

```text
Summarise the article between the triple quotes in 5 bullet points
for a busy executive.

"""
[article text]
"""
```

This helps the model tell your instructions apart from the content.

## Prompts to save and reuse

```text
Code review:
Review this [language] code for bugs, security issues and readability.
List problems in order of importance, with a fix for each. Don't rewrite
the whole file. [code]

Explain an error:
I got this error: [error]. Here's the code: [code]. Explain what it means
in plain English, the most likely cause, and how to fix it.

Study plan:
Create a [n]-week plan to learn [topic] for someone who knows [current
skills] and has [hours] hours a week. Include weekly goals and one small
project per week.

Summarise:
Summarise the text below in [n] bullet points for [audience]. Keep numbers
and names exact. [text]
```

## Limits to remember

- **Check facts and code.** A great prompt reduces errors but doesn't remove them. Run the code; verify claims that matter.
- **Don't paste secrets** (passwords, API keys, private customer data) into AI tools unless your organisation allows it.
- **Your judgement is the final step**, especially for anything published, legal, medical or financial.

## Frequently asked questions

### Do I need special "magic words"?

No. Clear, specific, complete instructions beat tricks. Write as you would to a capable colleague who knows nothing about your situation.

### Should prompts be long?

As long as needed, no longer. Add details that change the answer; skip filler like "please kindly".

### Why do I get different answers to the same prompt?

Models use some randomness when generating text. If you need consistency, be more specific, give an example of the exact format, or ask for a fixed structure.

### Is prompt engineering a real job skill?

Yes, as part of other jobs. Developers building AI features, writers, analysts and support teams all benefit from writing clear prompts. It's really the skill of clear communication.

## Summary

- Good prompts include **Goal, Context, Constraints, Format and an Example**.
- Paste exact errors, define the audience, and specify the output format.
- Break big tasks into steps, iterate on drafts, and let the AI ask clarifying questions.
- Give permission to say "I don't know", and always verify important output.
