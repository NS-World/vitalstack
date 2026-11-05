---
title: AI Token Counter and Prompt Cost Estimator
slug: token-counter
parent: tools
template: page-tool.php
icon: 🔢
order: 180
excerpt: Paste a prompt to see characters, words and an estimate of AI tokens, plus what it would cost for 1 and 10,000 requests at a price you choose.
---
## What is a token?

AI language models do not read letters or whole words. They read **tokens**: common chunks of text. In English, one token is roughly **four characters** or **three quarters of a word**, so 100 words is about 130 tokens. Hindi, Gujarati and other non-Latin scripts usually need more tokens for the same meaning.

Everything about using an AI model is measured in tokens:

- **Price**: providers charge per million input tokens and per million output tokens. Output usually costs several times more than input.
- **Context window**: the maximum tokens the model can consider at once, including your prompt, any documents and its own answer.
- **Speed**: longer outputs take longer to generate.

## How to use the counter

Paste your prompt or document. The table shows characters, words and an **estimated** token count. Enter an example input price in dollars per million tokens (check your provider's current pricing page) to see the cost of one request and of 10,000 requests. Small numbers grow quickly at scale.

The count is an estimate. Each provider splits text slightly differently; use your provider's own token counting API when you need the exact number.

## Five ways to cut AI costs

1. **Shorter system prompts.** Remove repeated instructions and examples the model does not need.
2. **Send only relevant context.** Retrieval (RAG) sends the top few chunks, not the whole book.
3. **Cap output length** with a maximum tokens setting and clear instructions like "answer in 3 bullets".
4. **Use a smaller model first**, and only escalate hard questions to a bigger one.
5. **Cache repeated prefixes** when your provider supports prompt caching.

Want the full picture? Try the [AI cost calculator](/tools/ai-cost-calculator/) and the free lessons in the [Gen AI path](/tutorial-category/prompt-engineering/).
