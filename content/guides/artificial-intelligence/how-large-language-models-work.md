---
title: How Large Language Models Work (Without the Maths)
slug: how-large-language-models-work
category: artificial-intelligence
tags: LLM, generative AI
order: 5
excerpt: A clear explanation of large language models: tokens, next-word prediction, training, context windows, temperature, why they hallucinate, and how to use them well.
---

**Short answer:** a large language model (LLM) is a neural network trained on enormous amounts of text to do one thing: **predict the next piece of text**. Repeating that prediction, piece by piece, lets it write answers, code, summaries and conversations.

That sounds too simple to explain what chat assistants can do. This guide shows how such a simple goal leads to such capable behaviour, and why the same mechanism explains their mistakes.

## Step 1: Text becomes tokens

Models don't read letters or whole words. Text is split into **tokens**: common words, parts of words, punctuation and spaces.

```text
"Learning Python is fun!"  →  ["Learning", " Python", " is", " fun", "!"]
"unbelievably"             →  ["un", "believ", "ably"]
```

Each token is mapped to a number, and each number to a list of values (a vector) that captures something about its meaning. Words used in similar ways end up with similar vectors: "cat" is closer to "dog" than to "carburettor".

Why it matters to you: usage limits, prices and the model's memory are all measured in tokens. In English, a token averages roughly three-quarters of a word; other languages and code often use more tokens for the same content.

## Step 2: Predict the next token

Given some text, the model calculates a probability for every possible next token:

```text
Input:  "The capital of France is"
Output: " Paris" 92%   " a" 3%   " located" 2%   ...
```

It picks one, adds it to the text, and repeats:

```text
"The capital of France is" → " Paris" → "." → " It" → " is" → " known" → ...
```

That's the whole generation process: one token at a time, each chosen based on everything before it. A long answer is thousands of these predictions in a row.

## Step 3: How it learned to predict so well

### Pre-training

The model starts with random parameters (billions of adjustable numbers) and reads a huge collection of text: books, websites, code, articles. For each position, it guesses the next token, checks the real one, and adjusts its parameters slightly to make the right answer more likely. Repeated trillions of times, this forces the model to absorb grammar, facts, writing styles, programming patterns and a great deal of reasoning-like structure, because all of those help predict text.

To predict the next line of a Python function, it helps to "know" Python. To predict the end of a maths explanation, it helps to follow the steps. Capability emerges from prediction.

### The transformer and attention

Most LLMs use an architecture called the **transformer**. Its key idea is **attention**: when processing each token, the model looks at all the other tokens in the text and decides which ones matter most. In "The trophy didn't fit in the suitcase because **it** was too big", attention helps link "it" to "trophy". Stacking many layers of attention lets the model connect ideas across long passages.

### Fine-tuning and feedback

A pre-trained model is a powerful text predictor but not a helpful assistant: ask it a question and it might continue with more questions. So it's further trained on examples of good conversations, and refined with feedback from people (and other techniques) that reward helpful, accurate and safe answers. This step turns a predictor into an assistant.

## The context window: the model's working memory

The model only "sees" the text currently in its **context window**: your messages, its replies, any documents you pasted, and instructions from the app. That's measured in tokens and is limited, though modern windows are large.

Practical consequences:

- In a very long conversation, early details may fall out of the window or get less attention.
- The model doesn't remember previous conversations unless the app stores and re-inserts them.
- Giving the relevant information **in the prompt** is the most reliable way to get accurate answers about your specific situation.

## Temperature: why answers vary

When choosing the next token, the model doesn't always pick the most likely one. A setting often called **temperature** controls randomness:

- **Low temperature:** picks likely tokens; more predictable and focused. Good for code and facts.
- **High temperature:** more varied choices; more creative, but more likely to go off track.

That's why asking the same question twice can give different answers.

## Why LLMs "hallucinate"

An LLM generates **plausible** text. Usually plausible and true overlap, because it learned from lots of accurate writing. But when it lacks the information, it doesn't stop; it produces text that **sounds** right: a confident fake citation, an API function that doesn't exist, a wrong date.

Ways to reduce this:

- **Give it the source material** and ask it to answer only from that.
- **Ask for sources or reasoning** you can check.
- **Use tools:** many assistants can search the web, run code or query databases, grounding answers in real data.
- **Verify** anything important: run the code, check the docs, confirm the facts.

## What LLMs are great at

- Drafting, rewriting and summarising text.
- Explaining concepts at different levels ("explain recursion to a 12-year-old").
- Writing and explaining code, generating tests, finding likely bugs.
- Transforming formats: notes to email, JSON to a table, English to Hindi.
- Brainstorming options and asking you good questions.

## Where to be careful

- Exact facts, numbers, legal or medical advice: check them.
- Maths and long precise procedures: ask it to use a code or calculation tool, or check the result.
- Recent events: the model's knowledge stops at its training date unless connected to live sources.
- Private data: follow your organisation's rules about what you paste into AI tools.

## How developers use LLMs in apps

Applications call LLMs through an **API**: send a prompt (and settings), get text back. Common patterns:

- **Prompting with instructions:** a "system" message that defines the assistant's role and rules.
- **Retrieval-augmented generation (RAG):** search your own documents for relevant passages, put them in the prompt, and ask the model to answer from them. This is how "chat with your docs" tools work.
- **Tool use / function calling:** the model decides to call functions you provide (search, calculator, database), and you return the results to it.
- **Agents:** a model that plans and takes several steps with tools to complete a task. See [What Are AI Agents?](/what-are-ai-agents/).

If you know Python, calling an LLM API is a few lines of code, which makes the [Python course](/tutorials/python-introduction/) a great starting point.

## Frequently asked questions

### Does the model search the internet for every answer?

Not by itself. The base model answers from patterns learned in training. Some apps add a search tool and pass the results to the model.

### Does an LLM understand what it says?

It builds rich internal representations of language and concepts, which is why it can be so useful, but it doesn't have beliefs or experiences like a person. Treat it as a very capable pattern-based tool, not an authority.

### Why does it sometimes get simple things wrong, like counting letters?

Tokens: the model sees "strawberry" as a few chunks, not individual letters, so letter-level tasks are surprisingly hard for it. Asking it to write and run code for such tasks works far better.

### Is bigger always better?

Larger models are generally more capable, but smaller ones are faster and cheaper, and are often good enough for focused tasks. Many products use a mix.

## Summary

- LLMs split text into **tokens** and predict the next token, one at a time.
- They learn by predicting text across enormous datasets, then are fine-tuned to be helpful.
- The **context window** is their working memory; **temperature** controls randomness.
- They can **hallucinate** because they generate plausible text; give them sources and verify important output.
- Developers use them through APIs with patterns like RAG, tool use and agents.
