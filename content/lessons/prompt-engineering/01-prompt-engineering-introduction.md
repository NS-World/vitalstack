---
title: Prompt Engineering Introduction
slug: prompt-engineering-introduction
subject: prompt-engineering
lesson: 1
difficulty: beginner
excerpt: What prompt engineering is, how AI chatbots like ChatGPT, Claude and Gemini actually produce answers, and why the way you ask changes what you get.
---

A **prompt** is what you type into an AI tool. **Prompt engineering** is the skill of writing prompts that get useful, accurate answers on the first try instead of the fifth. It needs no coding, so it's useful for everyone: students, developers, teachers, marketers, shop owners.

The same AI can give a vague, generic reply or a precise, ready-to-use one. The difference is almost always the prompt.

## The tools

You'll use the same skills in all of them:

| Tool | Made by | Good to know |
|---|---|---|
| ChatGPT | OpenAI | The most widely used chatbot |
| Claude | Anthropic | Strong at writing, long documents and code |
| Gemini | Google | Built into Google apps and Android |
| Microsoft Copilot | Microsoft | Inside Windows, Edge and Office |
| GitHub Copilot, Cursor | GitHub, Anysphere | AI inside your code editor |

Most have a free version that is more than enough to practise this course.

## How a chatbot writes an answer

These tools are built on a **large language model (LLM)**. An LLM was trained on a huge amount of text to do one thing: **predict the next piece of text**. Pieces of text are called **tokens**; a token is a word or part of a word. Step through it:

::viz llm-next-token

The model reads your prompt, scores every possible next token, picks one, adds it, and repeats until the answer is complete. It doesn't look facts up in a database (unless the tool adds web search); it continues text in the way that seems most likely given everything it has read.

That explains three things you'll notice:

1. **Your prompt is the starting text.** Clear, detailed starting text leads to a better continuation.
2. **Answers vary.** Ask twice and you may get different wording, because the next token is partly chosen at random. A setting called **temperature** controls how much.
3. **It can sound confident and still be wrong.** Producing likely-sounding text isn't the same as checking facts. This is called **hallucination**, and you'll learn to manage it in the last lesson.

## Same question, two prompts

A vague prompt:

```text
Tell me about Python.
```

You'll get a general essay that tries to cover everything for nobody in particular.

A better prompt:

```text
I'm a commerce student with no coding experience. In 5 short bullet points,
explain what Python is used for in finance and accounting jobs, with one
real example task for each.
```

Now the AI knows **who** you are, **what** you want, **how much** and **in what shape**. The answer is shorter and far more useful.

::tip You don't need magic words or secret tricks. Write the way you'd brief a smart new colleague who knows nothing about your situation: who it's for, what you need, and what a good answer looks like.

## What this course covers

1. Writing clear instructions
2. Roles and context
3. Teaching by example (few-shot prompts)
4. Step-by-step thinking and breaking big tasks down
5. Getting tables, lists and JSON back
6. Using AI to learn coding and to work faster
7. Limits, safety and using AI responsibly

::hint Quick check | Why might the same prompt give a different answer each time? Because the model picks each next token with some randomness. It's normal; ask it to be more specific, or regenerate.

## Summary

- A prompt is your instruction; prompt engineering is writing it well.
- LLMs write by predicting one token at a time, starting from your prompt.
- Clear prompts say who it's for, what you want, how much and in what format.
