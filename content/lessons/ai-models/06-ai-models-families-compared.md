---
title: "ChatGPT vs Claude vs Gemini vs Grok vs Llama: AI Model Families Explained"
slug: ai-models-families-compared
subject: ai-models
lesson: 6
difficulty: beginner
excerpt: Who makes which AI model, open vs closed weights, chat apps vs APIs, and a simple checklist for choosing the right model for studying, coding or building an app.
---

New model names appear almost every week, which makes AI feel impossible to keep up with. The trick is to stop memorising version numbers and learn the **families** and the **questions to ask**. Version numbers change; these don't.

## The main families

| Family | Made by | Chat app | Weights | Known for |
|---|---|---|---|---|
| **GPT** | OpenAI (USA) | ChatGPT | Closed (some open-weight releases) | Started the chatbot boom in late 2022; large ecosystem of tools and plugins |
| **Claude** | Anthropic (USA) | Claude | Closed | Long documents, coding and careful writing; Constitutional AI safety approach |
| **Gemini** | Google (USA) | Gemini | Closed (open **Gemma** family too) | Built to be multimodal from the start; integrated with Google products |
| **Grok** | xAI (USA) | Grok, inside X | Closed (some older versions opened) | Access to posts on X; conversational style |
| **Llama** | Meta (USA) | Meta AI | **Open weights** | Popular base for open-source apps and fine-tuning |
| **Mistral** | Mistral AI (France) | Le Chat | Mix of open and closed | Efficient models, strong in European languages |
| **DeepSeek** | DeepSeek (China) | DeepSeek chat | **Open weights** for major releases | Strong reasoning models at low training cost, widely discussed in 2025 |
| **Qwen** | Alibaba (China) | Qwen chat | Many **open-weight** sizes | Wide range of model sizes, strong multilingual support |

Each family has several sizes and versions at any time, usually a large, most capable model and smaller, faster, cheaper ones. Check each provider's models page for current names.

::scene Picking a model, the movie
🎯 | Question 1 | What's the job? Chatting and studying, writing code, or building a product for customers? | dev
🖼️ | Question 2 | Do you need images, PDFs, audio or video, or just text? | chip
🔐 | Question 3 | Can your data leave your computer? If not, an open-weight model you run yourself may be the answer. | bugsy
💸 | Question 4 | How many requests a day? At scale, a small fast model can cost a tiny fraction of a large one. | byte
🧪 | Question 5 | Test two or three models on 20 real examples from your task. Your results beat any leaderboard. | dev
::end

## Closed vs open weights

- **Closed models** (most GPT, Claude, Gemini, Grok versions) run only on the company's servers. You use them through a chat app or a paid **API**. You get top quality and no hardware to manage, but your requests go to the provider.
- **Open-weight models** (Llama, Mistral's open models, DeepSeek, Qwen, Gemma) can be downloaded and run on your own computer or server. You control the data and can fine-tune them, but you need suitable hardware and engineering effort. "Open weights" doesn't always mean fully open source: the training data and code are often not released, and licences vary.

## Chat app vs API: they're different products

| | Chat app (ChatGPT, Claude, Gemini, Grok) | API (for developers) |
|---|---|---|
| Who it's for | People chatting | Programs calling the model |
| How you pay | Free tier or monthly subscription | Pay per token used (prepaid credits or monthly bill) |
| Account | Your normal login | A developer console account and an **API key** |
| A subscription includes the other? | **No.** A ChatGPT Plus or Claude Pro subscription does not include API credits, and vice versa | |

The **Gen AI Developer** premium track shows how to get API keys from each provider, keep them safe, and call every major model from Python.

## A simple model-choice helper

```python
def suggest(task, private_data=False, requests_per_day=100):
    if private_data:
        return "open-weight model on your own server (e.g. a Llama, Mistral or Qwen size that fits your GPU)"
    if task in ("hard reasoning", "complex coding", "long documents"):
        return "a large frontier model (top GPT, Claude or Gemini tier)"
    if requests_per_day > 10_000:
        return "a small fast tier (mini / flash / haiku-class) to keep costs low"
    return "a mid-size model from any major provider; test 2-3 on real examples"

print(suggest("complex coding"))
print(suggest("sort support emails", requests_per_day=50_000))
print(suggest("summarise contracts", private_data=True))
```

Output:

```text
a large frontier model (top GPT, Claude or Gemini tier)
a small fast tier (mini / flash / haiku-class) to keep costs low
open-weight model on your own server (e.g. a Llama, Mistral or Qwen size that fits your GPU)
```

## Benchmarks: useful, but not the whole story

Leaderboards test models on exam-style questions, coding problems and maths. They help you shortlist, but:

- models can be tuned to do well on popular benchmarks;
- your task (Hindi customer emails, your company's PDFs, your codebase) may behave differently;
- speed, price, context length and reliability matter as much as a few percentage points of accuracy.

::tip The best habit for any developer: keep a small **evaluation set** of 20–50 real examples from your own use case, and rerun it whenever you consider switching models. You'll learn to build one in the Agentic AI Engineer track.

## AI tools built on these models

Most AI products you hear about are built **on top of** these families through their APIs: coding assistants in your editor (for example GitHub Copilot, Cursor, Claude Code), writing tools, browser extensions, meeting note-takers and customer-support bots. When a tool says "powered by GPT" or "powered by Claude", it is calling that company's API, exactly what you'll learn to do in the premium track.

## Summary

- Learn the **families** (GPT, Claude, Gemini, Grok, Llama, Mistral, DeepSeek, Qwen), not version numbers.
- **Closed** models = best convenience via app or API; **open-weight** = control and privacy, more work.
- Chat subscriptions and API usage are **separate** products and bills.
- Choose by task, data privacy, volume and your own tests, not leaderboards alone.

🎉 You've finished **How AI Models Are Built**. You now understand data, tokens, pretraining, fine-tuning, alignment and serving, which is more than most people who use these tools every day.
