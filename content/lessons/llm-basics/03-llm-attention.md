---
title: "Transformers and Attention, Visually"
slug: llm-attention
subject: llm-basics
lesson: 3
difficulty: intermediate
excerpt: Attention lets each word look at the other words that matter for it. Compute attention weights with softmax in plain Python and see why transformers understand context.
---

Read this sentence: *"Riya put the laptop in her bag because **it** was raining."* What does **it** refer to? Not the laptop, not the bag: the weather. You knew instantly because you looked at the **other words** for clues.

**Attention** gives a model the same ability. When processing each token, the model scores how relevant every other token is, then mixes in information from the relevant ones. A **transformer** is a stack of many attention layers, and it's the "T" in GPT.

## Step 1: relevance scores

For each pair of tokens, attention computes a **score**: how much should token A look at token B? In a real model these come from learned vectors (queries and keys). Here we'll use made-up scores for the word **"bank"** in *"I sat by the river bank"*:

```python
words  = ["I", "sat", "by", "the", "river"]
scores = [0.2, 1.0, 0.5, 0.1, 3.0]   # how relevant each word is to "bank"
for w, s in zip(words, scores):
    print(f"{w:>6}: {s}")
```

Output:

```text
     I: 0.2
   sat: 1.0
    by: 0.5
   the: 0.1
 river: 3.0
```

"river" gets the highest score: it tells us this is a river bank, not a money bank.

::viz llm-attention

## Step 2: softmax turns scores into percentages

Scores can be any number. **Softmax** converts them into weights that are all positive and add up to 1 (100%), while making the big scores stand out even more.

::walk python | Softmax: from scores to attention weights
import math
scores = [0.2, 1.0, 0.5, 0.1, 3.0]
exps = [math.exp(s) for s in scores]
total = sum(exps)
weights = [e / total for e in exps]
print([round(w, 2) for w in weights])
print("river gets", round(weights[4] * 100), "% of the attention")
::notes
1: The **math** module gives us exp(), the exponential function. // deep: exp(x) = e^x where e ≈ 2.718. It is always positive and grows fast: exp(0) = 1, exp(1) ≈ 2.7, exp(3) ≈ 20.1.
2: The five relevance scores for "bank". // vars: scores=[0.2, 1.0, 0.5, 0.1, 3.0]
3: Apply **exp** to each score. Every value becomes positive, and 3.0 becomes about **20.1**, far ahead of the others. // deep: This is why softmax "sharpens": a gap of 2 in scores becomes a ratio of about 7× after exp. // vars: exps=[1.22, 2.72, 1.65, 1.11, 20.09]
4: Add them up: about **26.8**. // vars: total=26.78
5: Divide each by the total, so the weights **sum to 1**. // vars: weights=[0.05, 0.1, 0.06, 0.04, 0.75]
6: Print the weights, rounded. // out: [0.05, 0.1, 0.06, 0.04, 0.75]
7: "river" gets three quarters of the attention. // out: river gets 75 % of the attention
::end

## Step 3: mix in the information

Each word also carries a **value** vector (what it contributes). The new meaning of "bank" is the weighted mix of all values. Let's use 2-number values: **[nature, money]**.

```python
import math

def softmax(xs):
    exps = [math.exp(x) for x in xs]
    return [e / sum(exps) for e in exps]

values = {"I": [0, 0], "sat": [0.1, 0], "by": [0.1, 0],
          "the": [0, 0], "river": [1.0, 0]}

w = softmax(scores)
bank = [sum(w[i] * values[words[i]][d] for i in range(5)) for d in range(2)]
print("bank means [nature, money]:", [round(x, 2) for x in bank])
```

Output:

```text
bank means [nature, money]: [0.77, 0.0]
```

After attention, "bank" leans strongly to **nature**. In *"I deposited cash at the bank"*, the word "cash" would get the attention instead, and the same word would come out meaning **money**. That's how one word gets different meanings in different sentences.

## What a real transformer adds

- **Learned scores:** each token produces a *query* ("what am I looking for?") and a *key* ("what do I contain?"); the score is query · key, a dot product like in lesson 2.
- **Many heads:** dozens of attention "heads" run in parallel, each spotting a different kind of link (grammar, who-did-what, topic).
- **Many layers:** the output of one attention layer feeds the next, building richer meaning each time. Large models stack 30 to 100+ layers.
- **Masking:** when generating, a token may only look **backwards**, never at future tokens it hasn't written yet.

::tip Attention compares **every token with every other token**, so the work grows with the square of the text length. Double the prompt, roughly 4× the attention work. That's a big reason long prompts are slower and cost more.

## Summary

- **Attention** lets each token look at the others and decide which matter.
- **Scores → softmax → weights** that add up to 1.
- The token's new meaning is a **weighted mix** of the others' values.
- A **transformer** stacks many attention layers and heads.
