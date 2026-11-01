---
title: "Serving AI to Millions: Inference, GPUs, Speed and Pricing"
slug: ai-models-serving-inference
subject: ai-models
lesson: 5
difficulty: beginner
excerpt: What happens between pressing Enter and seeing ChatGPT's answer? Inference, batching, the KV cache, quantization, and why AI APIs charge per token, with runnable Python.
---

Training happens once. **Inference**, running the trained model to answer questions, happens billions of times a day. For a company like OpenAI, Anthropic, Google or xAI, making inference fast and cheap matters as much as making the model smart.

::scene The journey of one question
⌨️ | You | You type "Explain black holes simply" and press Enter. | dev
🧩 | Tokenizer | Your text, and the conversation so far, become a list of token numbers. | chip
🏢 | Data centre | The request joins a queue at a data centre full of GPUs, batched together with thousands of other users. | byte
⚡ | Prefill | The model reads your whole prompt at once, in parallel. This part is fast per token. | bugsy
🔂 | Decode | Then it writes the answer one token at a time; each new token needs another pass through the model. | dev
📡 | Stream | Each token is sent back as soon as it's ready, which is why answers appear word by word. | chip
::end

## Prefill vs decode: why output tokens cost more

There are two phases:

1. **Prefill:** the model processes your *input* tokens. They are all known in advance, so the GPU handles them in parallel.
2. **Decode:** the model generates *output* tokens **one after another**, because each new token depends on the previous one.

Decoding uses the GPU less efficiently, so most providers charge **several times more per output token than per input token**. That's the reason behind a common API pricing shape: "₹X per million input tokens, ₹Y (higher) per million output tokens".

```python
def api_cost(input_tokens, output_tokens, in_price, out_price):
    """Prices are per million tokens, in any currency."""
    return input_tokens / 1e6 * in_price + output_tokens / 1e6 * out_price

# A chatbot answer: 1,500 tokens in (instructions + chat history), 400 out.
# Example prices only; always check the provider's pricing page.
cost = api_cost(1_500, 400, in_price=2.00, out_price=10.00)
print(f"one answer: ${cost:.4f}")
print(f"10,000 answers a day: ${cost * 10_000:,.2f}")
```

Output:

```text
one answer: $0.0070
10,000 answers a day: $70.00
```

Notice that the 400 output tokens cost more than the 1,500 input tokens. Try different numbers in our free **AI API cost calculator** (in the Tools menu).

## The KV cache: don't re-read what you already read

When writing token number 300, the model needs information from the previous 299 tokens. Recomputing all of them every step would be wasteful, so servers keep a **KV cache** (keys and values from the attention layers) in GPU memory. It makes decoding far faster, but uses a lot of memory, which is one reason very long conversations are slower and pricier.

**Prompt caching** is the same idea across requests: if many requests start with the same long instructions, the provider can reuse that work and charge less for the cached part. Several providers, including Anthropic, OpenAI and Google, offer it.

## Batching: one GPU, many users

A GPU is most efficient when doing many multiplications at once. Servers group requests from many users into a **batch** and run them together.

::walk python | Why batching helps
gpu_ms_per_step = 30
users = 8
alone = users * gpu_ms_per_step
batched = gpu_ms_per_step + users * 2
print("one at a time:", alone, "ms")
print("batched:", batched, "ms")
::notes
1: Say one decoding step takes the GPU about 30 ms, mostly spent loading the model's weights from memory. // deep: For large models, decoding is "memory-bound": moving billions of weights from GPU memory dominates the time, not the arithmetic itself. // vars: gpu_ms_per_step=30
2: Eight users are waiting for their next token. // vars: users=8
3: Serving them one by one loads the weights 8 times: **240 ms**. // vars: alone=240
4: Batched, the weights are loaded once and each extra user adds only a little arithmetic (say 2 ms): **46 ms**. // deep: These are illustrative numbers. Real systems use "continuous batching", adding and removing users from the batch at every step. // vars: batched=46
5: Print the slow way. // out: one at a time: 240 ms
6: About 5× faster for everyone, on the same hardware. // out: batched: 46 ms
::end

## Making models smaller and faster

| Technique | What it does | Trade-off |
|---|---|---|
| **Quantization** | Store weights with fewer bits (for example 8 or 4 instead of 16) | Much less memory; tiny quality loss if done well |
| **Distillation** | Train a small "student" model to imitate a big "teacher" | Small, fast models like the "mini", "flash" or "haiku" tiers |
| **Speculative decoding** | A small model drafts several tokens; the big one checks them in one go | Faster output, same quality |
| **Mixture of Experts (MoE)** | Only part of the model runs for each token | Big capacity at lower cost per token |

```python
params = 8e9                     # an 8-billion-parameter model
for bits in (16, 8, 4):
    gb = params * bits / 8 / 1e9
    print(f"{bits:>2}-bit weights: {gb:.0f} GB of memory")
```

Output:

```text
16-bit weights: 16 GB of memory
 8-bit weights: 8 GB of memory
 4-bit weights: 4 GB of memory
```

That's how open models like Llama or Mistral can run on a gaming laptop: a 4-bit 8B model fits in about 4 GB (plus some working memory).

::tip **Why there are "big" and "small" versions of every model family** (for example GPT mini models, Claude Haiku vs Opus, Gemini Flash vs Pro): small models are cheaper and faster for simple tasks like sorting emails; big models are worth it for hard reasoning and coding. Picking the right size is one of the easiest ways to cut an AI bill.

## Summary

- **Inference** = running the model for users; it happens billions of times, so efficiency matters.
- **Prefill** reads your prompt in parallel; **decode** writes one token at a time, so output tokens cost more.
- The **KV cache**, **prompt caching** and **batching** make serving faster and cheaper.
- **Quantization**, **distillation**, **speculative decoding** and **MoE** make models smaller or faster.

Next: the major model families compared, closed and open, and how to choose one.
