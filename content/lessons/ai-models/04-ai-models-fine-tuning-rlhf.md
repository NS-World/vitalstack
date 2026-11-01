---
title: "Fine-Tuning, RLHF and Safety: How a Model Becomes an Assistant"
slug: ai-models-fine-tuning-rlhf
subject: ai-models
lesson: 4
difficulty: beginner
excerpt: Supervised fine-tuning, reward models, RLHF, Constitutional AI and DPO explained simply, with runnable Python. Learn why ChatGPT, Claude and Gemini behave so differently from a raw model.
---

After pretraining, a model is like a student who has read the whole library but never had a conversation. Ask it "What's a good name for a cat?" and it might reply "What's a good name for a dog? What's a good name for a fish?", because in its training data lists of questions are common. **Post-training** fixes this. It is a much smaller amount of data and compute than pretraining, but it decides how the model *behaves*.

::scene From autocomplete to assistant
🤖 | Before | Asked "How do I make chai?", the raw model continues with more recipe titles. It completes text; it doesn't help. | byte
✍️ | Step 1: Show | People write thousands of example conversations: a request, then an ideal helpful answer. | dev
📈 | Step 2: Compare | The model writes two answers to the same request. A reviewer picks the better one. | chip
🏅 | Step 3: Reward | A reward model learns to predict which answers people prefer. | bugsy
🔁 | Step 4: Practise | The assistant writes answers, gets scored, and shifts towards higher scores. | dev
🛡️ | Step 5: Red-team | Testers try to trick it into harmful answers, and those cases go back into training. | bugsy
🙂 | After | The same question now gets a clear, friendly, step-by-step recipe. | chip
::end

## Step 1: Supervised fine-tuning (SFT)

The model is trained on examples of **prompt → ideal response**, using the same next-token learning as pretraining, just on far fewer, far better examples (thousands to millions instead of trillions of tokens).

```python
sft_examples = [
    {"prompt": "Explain recursion to a 10-year-old.",
     "response": "Recursion is when something is defined using a smaller copy of itself..."},
    {"prompt": "Write a polite leave request to my manager.",
     "response": "Dear Ms. Rao, I would like to request leave on..."},
]
for ex in sft_examples:
    print(len(ex["prompt"].split()), "->", len(ex["response"].split()), "words")
```

Output:

```text
5 -> 12 words
8 -> 10 words
```

After SFT, the model answers instead of continuing. But good answers can differ in many subtle ways (tone, accuracy, safety), and those are hard to capture with examples alone.

## Step 2–4: Learning from preferences (RLHF)

It's easier for a person to say "A is better than B" than to write the perfect answer. **RLHF** (Reinforcement Learning from Human Feedback) uses exactly that:

1. Collect pairs of answers to the same prompt, with a human choice of which is better.
2. Train a **reward model** that gives any answer a score predicting human preference.
3. Use reinforcement learning to update the assistant so its answers get higher reward, while staying close to its SFT version (so it doesn't learn weird tricks to game the score).

Here is a toy reward model: a scoring function built from what reviewers liked.

```python
def reward(answer):
    score = 0
    if "step" in answer.lower():
        score += 2          # reviewers liked step-by-step answers
    if "i'm not sure" in answer.lower():
        score += 1          # and honesty when unsure
    if "guaranteed" in answer.lower():
        score -= 3          # and disliked overconfident promises
    score -= max(0, len(answer.split()) - 40) * 0.1   # and rambling
    return round(score, 1)

a = "Step 1: boil water. Step 2: add tea leaves and spices. Step 3: add milk and sugar."
b = "This guaranteed perfect chai recipe will change your life forever!"
print("A:", reward(a), "| B:", reward(b))
```

Output:

```text
A: 2.0 | B: -3.0
```

A real reward model is itself a neural network trained on hundreds of thousands of comparisons, not hand-written rules, but it plays the same role: turning "what people prefer" into a number the training can push towards.

## Watch a preference pair become a training signal

::walk python | From a human choice to a learning signal
import math
score_chosen = 2.0
score_rejected = -3.0
gap = score_chosen - score_rejected
p_correct = 1 / (1 + math.exp(-gap))
loss = -math.log(p_correct)
print(round(p_correct, 3), round(loss, 3))
::notes
1: math gives us exp and log.
2: The reward model's score for the answer the human **chose**. // vars: score_chosen=2.0
3: Its score for the answer the human **rejected**. // vars: score_rejected=-3.0
4: The gap: how strongly the reward model agrees with the human. Bigger is better. // vars: gap=5.0
5: A **sigmoid** turns the gap into a probability: "how likely the reward model is to pick the human's choice". // deep: 1 / (1 + e^−5) ≈ 0.993. This is the Bradley-Terry model used to train reward models from comparisons. // vars: p_correct=0.9933
6: The training loss is low when that probability is high. Small loss = the reward model already agrees. // deep: If the reward model had preferred the rejected answer, the gap would be negative, the probability low, and the loss large, which pushes its weights to fix the mistake. // vars: loss=0.0067
7: Probability 0.993, loss 0.007: this pair is already learned. // out: 0.993 0.007
::end

## Other alignment methods you'll hear about

| Method | Idea in one line | Who talks about it |
|---|---|---|
| **RLHF** | Humans compare answers → reward model → reinforcement learning | Widely used across labs, popularised by OpenAI's InstructGPT work |
| **Constitutional AI / RLAIF** | An AI model compares answers using a written list of principles (a "constitution"), reducing the human labelling needed | Introduced by Anthropic for Claude |
| **DPO** (Direct Preference Optimization) | Learn from preference pairs directly, with no separate reward model or RL step | A simpler, popular method from academic research, common in open models |
| **Red-teaming** | Experts try to break the model on purpose; failures become new training data | Done by every major lab before release |

::tip This is why the same question gets different "personalities" from ChatGPT, Claude, Gemini and Grok. Their pretraining data overlaps a lot; their **post-training choices** (examples, reviewer guidelines, principles) differ.

## Safety is a balance

Too little alignment and a model may help with harmful requests or invent facts confidently. Too much and it refuses harmless questions ("over-refusal"). Labs measure both and tune between them, which is why model updates sometimes say "fewer unnecessary refusals" or "more careful with medical advice".

## Summary

- **SFT** teaches the assistant format from good examples.
- **Reward models** turn human preferences into scores; **RLHF** pushes the model towards higher scores.
- **Constitutional AI** uses written principles and AI feedback; **DPO** learns from preference pairs directly.
- Post-training is small compared with pretraining but decides behaviour, tone and safety.

Next: how a finished model is served to millions of people, and why output tokens cost more than input tokens.
