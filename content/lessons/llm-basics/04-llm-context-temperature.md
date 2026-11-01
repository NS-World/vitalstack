---
title: "Context Windows, Temperature and Sampling"
slug: llm-context-temperature
subject: llm-basics
lesson: 4
difficulty: intermediate
excerpt: Why the same prompt gives different answers, what temperature and top-k really do, and why models "forget" in long chats. With runnable Python you can tweak.
---

Ask an LLM the same question twice and you may get two different answers. Paste a very long document and it may "forget" the start. Both come from settings you control: **temperature**, **sampling** and the **context window**.

## Temperature: how adventurous the model is

Remember from lesson 1: the model gives a probability for every possible next token. Then it **picks** one. Temperature changes the probabilities before the pick:

- **Low temperature (0 to 0.3):** the top choice gets almost all the probability. Answers are focused and repeatable. Good for code, facts, data extraction.
- **High temperature (0.8 to 1.2):** probabilities spread out. Answers are varied and creative, and more likely to go wrong. Good for brainstorming, stories, names.

Technically, temperature **divides the scores** before softmax:

::walk python | What temperature does to the probabilities
import math
scores = {"tea": 2.0, "coffee": 1.0, "juice": 0.2}
for t in [0.5, 1.0, 2.0]:
    exps = {w: math.exp(s / t) for w, s in scores.items()}
    total = sum(exps.values())
    print(t, {w: round(e / total, 2) for w, e in exps.items()})
::notes
1: exp() for softmax, as in lesson 3.
2: The model's raw scores for the next word after "I'd like a cup of". Tea is the favourite. // vars: scores={'tea': 2.0, 'coffee': 1.0, 'juice': 0.2}
3: Try three temperatures. First **t = 0.5** (cool). // vars: t=0.5
4: Divide every score by 0.5, which **doubles** them: 4, 2, 0.4. Bigger gaps → the favourite dominates. // deep: Dividing by a small t stretches the differences between scores; after exp they become huge ratios. As t gets close to 0 the top token gets ~100%: that's "greedy" decoding. // vars: exps={'tea': 54.6, 'coffee': 7.39, 'juice': 1.49}
5: Total for softmax. // vars: total=63.48
6: Tea gets **86%**. Almost always tea. // out: 0.5 {'tea': 0.86, 'coffee': 0.12, 'juice': 0.02}
3: Now **t = 1.0**: the scores are used as they are. // vars: t=1.0
4: exp of the original scores. // vars: exps={'tea': 7.39, 'coffee': 2.72, 'juice': 1.22}
5: Total. // vars: total=11.33
6: Tea 65%: still the favourite, but coffee has a real chance. // out: 1.0 {'tea': 0.65, 'coffee': 0.24, 'juice': 0.11}
3: Finally **t = 2.0** (hot). // vars: t=2.0
4: Dividing by 2 **halves** the scores: 1, 0.5, 0.1. The gaps shrink. // vars: exps={'tea': 2.72, 'coffee': 1.65, 'juice': 1.11}
5: Total. // vars: total=5.47
6: Much flatter: juice now gets **20%**. More variety, more surprises. // out: 2.0 {'tea': 0.5, 'coffee': 0.3, 'juice': 0.2}
::end

## Sampling: actually picking a token

With probabilities ready, the model **samples**: like rolling a weighted die. We can simulate it. A fixed `seed` makes the "random" picks repeatable so you see the same output as here:

```python
import math, random

def probs(scores, t):
    exps = {w: math.exp(s / t) for w, s in scores.items()}
    total = sum(exps.values())
    return {w: e / total for w, e in exps.items()}

scores = {"tea": 2.0, "coffee": 1.0, "juice": 0.2}
random.seed(7)
for t in [0.5, 2.0]:
    p = probs(scores, t)
    picks = random.choices(list(p), weights=list(p.values()), k=1000)
    print(t, {w: picks.count(w) for w in scores})
```

Output:

```text
0.5 {'tea': 855, 'coffee': 125, 'juice': 20}
2.0 {'tea': 491, 'coffee': 299, 'juice': 210}
```

Out of 1,000 answers, the cool model said "tea" 855 times (close to its 86%); the hot one only 491 times (close to 50%).

## Top-k and top-p: cutting off nonsense

Even at a sensible temperature, there are thousands of very unlikely tokens, and once in a while one gets picked and derails the answer. Two common filters:

- **top-k:** keep only the **k** most likely tokens, then sample among them.
- **top-p (nucleus):** keep the smallest set of tokens whose probabilities add up to **p** (say 0.9).

```python
def top_k(p, k):
    best = sorted(p.items(), key=lambda kv: kv[1], reverse=True)[:k]
    total = sum(v for _, v in best)
    return {w: round(v / total, 2) for w, v in best}

print(top_k(probs(scores, 1.0), 2))
```

Output:

```text
{'tea': 0.73, 'coffee': 0.27}
```

"juice" is removed and the remaining two share the probability.

## The context window: the model's working memory

The **context window** is the maximum number of tokens the model can see at once: your system prompt + the whole chat so far + any documents + the answer it is writing. Modern models handle from tens of thousands to over a million tokens, but every token costs money and time.

```python
window = 8000
system, document, chat, answer = 500, 6200, 900, 600
used = system + document + chat + answer
print("tokens used:", used, "of", window)
print("fits!" if used <= window else f"too long by {used - window} tokens")
```

Output:

```text
tokens used: 8200 of 8000
too long by 200 tokens
```

When a chat grows past the window, apps must drop or **summarise** old messages. That's why a chatbot can "forget" what you said an hour ago.

::tip Practical settings: use **temperature 0–0.2** for code, data extraction and anything you'll check automatically; **0.7–1.0** for writing and ideas. Keep prompts short and relevant: the model pays attention to everything in the window, including clutter.

## Summary

- **Temperature** sharpens (low) or flattens (high) the probabilities.
- **Sampling** picks a token at random using those probabilities; a seed makes it repeatable.
- **top-k / top-p** remove very unlikely tokens.
- The **context window** limits how much text the model sees at once; long chats must be trimmed or summarised.
