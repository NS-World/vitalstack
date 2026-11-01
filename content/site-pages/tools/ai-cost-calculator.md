---
title: AI API Cost Calculator (ChatGPT, Claude, Gemini)
slug: ai-cost-calculator
parent: tools
template: page-tool.php
icon: 💸
order: 75
excerpt: Estimate what an AI feature will cost per request, per day and per month, in dollars and rupees, for small, mid-size and frontier models. Paste a prompt to estimate its tokens.
---
## How to use the AI API cost calculator

AI APIs from OpenAI (the GPT models behind ChatGPT), Anthropic (Claude), Google (Gemini) and xAI (Grok) charge **per token**: small pieces of text, roughly ¾ of an English word each. Enter:

- **Input tokens per request:** your instructions, the user's message and any chat history or documents you send. Paste a typical prompt into the box and the calculator estimates this for you.
- **Output tokens per request:** how long the model's answers usually are.
- **Requests per day:** how many times your app calls the model.

The result shows the cost per request, per day and per month for three model sizes, in dollars and rupees.

## Why the three sizes differ so much

Every provider offers big "frontier" models for hard reasoning and small, fast models for simple jobs like sorting emails or extracting fields. The small ones can cost **dozens of times less**. The prices pre-filled here are only examples: open the provider's pricing page and type in the current prices for the models you're comparing.

## Why output costs more than input

The model reads your whole prompt in parallel, but writes its answer one token at a time. Generating is slower for the GPU, so providers charge several times more per output token. Asking for shorter answers is one of the easiest ways to save money.

## Five ways to lower your AI bill

1. **Test a small model first.** If it passes your examples, keep it.
2. **Cap the answer length** with the API's maximum-token setting.
3. **Trim chat history.** Long conversations resend every earlier message.
4. **Use prompt caching** when many requests share the same long instructions or documents.
5. **Batch** jobs that don't need an instant answer; several providers discount batch requests.

## Learn more

Our free course [How AI Models Are Built](/tutorials/ai-models-life-of-a-model/) explains tokens, training and why serving costs what it does. The Gen AI Developer track shows how to get API keys from each provider and control costs in real code.
