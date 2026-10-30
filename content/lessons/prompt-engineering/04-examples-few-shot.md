---
title: Teaching by Example: Few-Shot Prompts
slug: prompt-few-shot
subject: prompt-engineering
lesson: 4
difficulty: intermediate
excerpt: Show the AI one to three examples of what you want, the fastest way to control style, format and tricky judgement calls.
---

Describing a style in words is hard. Showing it is easy. When you include examples of the input and the output you want, the AI copies the pattern. This is called **few-shot prompting** (one example is "one-shot"; none is "zero-shot").

## Zero-shot vs few-shot

**Zero-shot**, instructions only:

```text
Classify the sentiment of this review as positive, negative or mixed:
"Delivery was quick but the shoes are too tight."
```

That works for simple tasks. But what does "mixed" mean to you? Should the answer include a reason? Examples answer those questions without long rules:

```text
Classify each review. Follow the examples exactly.

Review: "Battery lasts two days, love it."
Sentiment: positive | Reason: battery life

Review: "Screen cracked in a week, support never replied."
Sentiment: negative | Reason: durability, support

Review: "Great camera, but it heats up while gaming."
Sentiment: mixed | Reason: camera good, heating bad

Review: "Delivery was quick but the shoes are too tight."
Sentiment:
```

The AI now answers in the same one-line format, with the same kind of reason, ready to paste into a spreadsheet.

## Examples teach style

Want posts in your brand's voice? Show two real ones:

```text
Here are two of our Instagram captions:

1. "Monday = new week, new loop 🔁 Today's lesson: for-loops in 5 minutes. Link in bio!"
2. "Your code has a bug? Good. That's where learning starts 🐞 #CodingLife"

Write 3 more captions in the same style for these lessons:
- Python dictionaries
- SQL JOINs
- Git branches
```

## Examples teach judgement

For decisions where rules are fuzzy, examples work far better than definitions:

```text
Decide if each customer message needs a human agent.

Message: "Where is my order #4521?"  → Bot (order tracking)
Message: "I was charged twice and my bank wants a letter from you." → Human (billing dispute)
Message: "Do you deliver to Nashik?" → Bot (FAQ)

Message: "The item arrived broken, and it was a gift for tomorrow." →
```

## Tips for good examples

- **Make them varied.** If all your examples are positive reviews, the AI leans positive.
- **Cover the tricky cases.** One "mixed" example teaches more than three obvious ones.
- **Keep the format identical** in every example, down to punctuation.
- **2 to 5 examples** is usually enough. More costs tokens and rarely helps.

::tip Developers use few-shot prompts inside apps too: an AI feature that extracts invoice details or tags support tickets usually has a few hand-picked examples built into its prompt.

::hint Quick check | Your AI keeps writing long explanations when you only want a one-word label. Fix? Add 2–3 examples where the answer is just the label. Examples beat "be brief".

## Summary

- Few-shot = include input → output examples in the prompt.
- Examples control format, style and judgement better than rules.
- Use varied, tricky, consistently formatted examples; 2–5 is plenty.
