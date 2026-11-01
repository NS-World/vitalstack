---
title: "When to Fine-Tune (and When Not To)"
slug: ft-when-to-fine-tune
subject: fine-tuning
lesson: 2
difficulty: intermediate
excerpt: Prompting, RAG or fine-tuning? A decision guide with costs, what fine-tuning is good at (style, format, narrow skills, smaller models), how to build a training dataset, and how to measure the gain.
---

"Should we fine-tune our own model?" is one of the most common questions in AI teams, and the answer is usually "not yet". Fine-tuning is powerful for some problems and the wrong tool for others. This lesson gives you a clear way to decide.

::scene The three tools
💬 | Prompting | Change the instructions and examples. Minutes of work, no training. Try this first. | dev
📚 | RAG | Give the model the right documents at question time. Best for facts that change. | chip
🎓 | Fine-tuning | Train the model on hundreds or thousands of examples. Best for style, format and narrow skills. | byte
⚖️ | Decide | Match the problem to the tool, and measure before and after. | bugsy
::end

## The decision guide

| Your problem | Best first tool |
|---|---|
| The model doesn't know your company's facts or documents | **RAG** |
| Facts change weekly (prices, policies, stock) | **RAG** |
| Output format or tone is inconsistent | **Prompting** with examples, then fine-tuning if still inconsistent |
| You need a very specific style (your brand voice, a legal drafting style) | **Fine-tuning** |
| A narrow repeated task (classify tickets into 40 categories) at huge volume | **Fine-tune a small model** (cheaper and faster than a big one) |
| The model can't do the task at all, even with good prompts | Probably a different or bigger model, not fine-tuning |

::walk python | A quick decision function
def choose(needs_new_facts, facts_change_often, style_problem, volume_per_day):
    if needs_new_facts or facts_change_often:
        return "RAG"
    if style_problem and volume_per_day > 10000:
        return "fine-tune a small model"
    return "better prompts first" if style_problem else "prompting"
print(choose(False, False, True, 50000))
::notes
1: Four questions about the problem.
2: Missing or changing facts? Fine-tuning is a poor way to add facts: the model may still mix them up, and you'd retrain on every change. // deep: Research and practice both show fine-tuning mostly changes behaviour and style, while knowledge is better supplied at question time (RAG). Many production systems combine both.
3: Use RAG for facts.
4: A style or format problem, at high volume?
5: Fine-tuning a small model can match a big model on one narrow task at a fraction of the cost and latency.
6: Otherwise, improve the prompt (add examples, be precise) before training anything.
7: Ticket classification with a formatting problem at 50,000 a day: fine-tune a small model. // out: fine-tune a small model
::end

## The cost picture

```python
daily_requests = 50_000
tokens_per_request = 800
big_price, small_price = 4.00, 0.40          # example $ per million tokens
big = daily_requests * tokens_per_request / 1e6 * big_price * 30
small = daily_requests * tokens_per_request / 1e6 * small_price * 30
training_once = 300                           # example one-time fine-tuning cost in $
print(f"big model: ${big:,.0f}/month | fine-tuned small model: ${small:,.0f}/month + ${training_once} once")
print("payback in days:", round(training_once / ((big - small) / 30), 1))
```

Output:

```text
big model: $4,800/month | fine-tuned small model: $480/month + $300 once
payback in days: 2.1
```

At high volume, a fine-tuned small model can pay for itself quickly. At 100 requests a day, it rarely does. (Prices are illustrative; check your provider.)

## Building the training dataset

Most fine-tuning uses **chat-formatted examples**: an input and the ideal output.

```python
import json

examples = [
    {"messages": [{"role": "system", "content": "Classify the support ticket. Reply with one label."},
                  {"role": "user", "content": "Refund not received after 10 days"},
                  {"role": "assistant", "content": "refund_delay"}]},
    {"messages": [{"role": "system", "content": "Classify the support ticket. Reply with one label."},
                  {"role": "user", "content": "App crashes when I open my orders"},
                  {"role": "assistant", "content": "app_bug"}]},
]
jsonl = "\n".join(json.dumps(e) for e in examples)
print(len(jsonl.splitlines()), "training lines;", examples[0]["messages"][-1]["content"])
```

Output:

```text
2 training lines; refund_delay
```

Quality beats quantity: a few hundred to a few thousand **correct, consistent, varied** examples usually beat 50,000 noisy ones. Remove personal data, hold back a test set, and include hard and unusual cases.

## Ways to fine-tune

- **Provider fine-tuning APIs:** upload a JSONL file, the provider trains and hosts the model (several major providers offer this for some of their models).
- **Open models yourself:** Hugging Face `transformers` + `peft` (LoRA/QLoRA, next lesson), on a rented GPU or Colab.

## Measure the gain

Always compare on the same held-out test set: base model + best prompt **vs** fine-tuned model. If fine-tuning doesn't clearly win on accuracy, cost or latency, don't ship it.

::tip Keep the base-model prompt you tested against. When a new, stronger base model comes out, rerun that comparison: sometimes the new model with a good prompt beats your fine-tuned old one, and you can skip retraining.

## Summary

- Facts → RAG; style, format and narrow high-volume tasks → fine-tuning; always try prompting first.
- Fine-tuning a small model can slash cost at high volume.
- Training data: consistent, high-quality chat examples in JSONL, with a held-out test set.
- Measure base + prompt vs fine-tuned on the same test set before shipping.
