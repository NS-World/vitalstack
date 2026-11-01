---
title: "Tokens and Next-Token Prediction"
slug: llm-tokens-next-token
subject: llm-basics
lesson: 1
difficulty: intermediate
excerpt: A large language model does one thing: predict the next token. Build a tiny next-word predictor in plain Python and see how text generation really works.
---

Every large language model (LLM), however clever it looks, does **one job**: given some text, it predicts **what comes next**. It writes an answer one small piece at a time, adding each piece to the text and predicting again.

Those small pieces are called **tokens**.

## What is a token?

A token is a chunk of text: often a whole common word, sometimes part of a word or a single character. A model has a fixed **vocabulary** of tokens (tens of thousands) and turns your text into a list of token numbers before doing anything else.

```python
vocab = {"I": 0, " love": 1, " learn": 2, "ing": 3, " Python": 4, "!": 5}

def tokenize(pieces):
    return [vocab[p] for p in pieces]

print(tokenize(["I", " love", " learn", "ing", " Python", "!"]))
```

Output:

```text
[0, 1, 2, 3, 4, 5]
```

"learning" became two tokens, `" learn"` + `"ing"`. Splitting rare words into common parts lets a model handle words it has never seen whole.

::tip A rough rule for English: **1 token ≈ ¾ of a word**, so 100 words ≈ 130 tokens. Hindi and other languages often need more tokens per word. API prices and context limits are counted in tokens, not words.

## Predicting the next word by counting

Real models learn their predictions with billions of numbers. But the *idea* fits in a few lines: read lots of text, count which word tends to follow which, and predict the most common follower.

```python
from collections import Counter, defaultdict

text = ("i love python . i love sql . i learn python . "
        "python is fun . sql is fun . i love python")
words = text.split()

follows = defaultdict(Counter)
for a, b in zip(words, words[1:]):
    follows[a][b] += 1

print(follows["i"])
print(follows["love"])
```

Output:

```text
Counter({'love': 3, 'learn': 1})
Counter({'python': 2, 'sql': 1})
```

After "i", the word "love" came 3 times and "learn" once. So the model's **probabilities** for the next word after "i" are 75% "love" and 25% "learn".

## Generating text, one token at a time

Generation is a loop: predict the next word, add it, predict again. Watch it work:

::walk python | Writing a sentence one word at a time
sentence = ["i"]
for _ in range(3):
    last = sentence[-1]
    nxt = follows[last].most_common(1)[0][0]
    sentence.append(nxt)
print(" ".join(sentence))
::notes
1: We start with one word, the **prompt**: "i". // vars: sentence=['i']
2: Generate 3 more words. Each round adds exactly one. // deep: Real models loop until they produce a special end-of-text token or hit the max_tokens limit you set.
3: Look at the **last word** so far. // vars: last=i
4: Ask the counts: after "i", "love" won (3 vs 1). // deep: most_common(1) returns [('love', 3)]; [0][0] picks the word. Always picking the top word is called greedy decoding. Lesson 4 shows how temperature adds variety. // vars: nxt=love
5: Add it to the sentence. // vars: sentence=['i', 'love']
3: Round two: the last word is now "love". // vars: last=love
4: After "love", "python" (2) beats "sql" (1). // vars: nxt=python
5: Add it. // vars: sentence=['i', 'love', 'python']
3: Round three: last word "python". // vars: last=python
4: After "python" we saw ".", "is" and "." again: "." wins with 2. // vars: nxt=.
5: Add the full stop. // vars: sentence=['i', 'love', 'python', '.']
6: Join and print. A sentence, written by counting! // out: i love python .
::end

## What a real LLM adds

Our counter only looks at **one** previous word. That's why it can't write anything sensible beyond a few words. A real LLM:

- looks at **thousands of previous tokens** at once (its **context window**, lesson 4);
- represents each token as a list of numbers that captures meaning (**embeddings**, lesson 2);
- decides which earlier tokens matter for the next one (**attention**, lesson 3);
- learned all this from trillions of tokens of text, not 22 words.

But the output step is the same as ours: a probability for every token in the vocabulary, then pick one, add it, repeat.

::tip This explains a famous weakness: LLMs predict **likely** text, not **true** text. If a wrong answer "sounds right", the model can produce it confidently. Lesson 5 is about this.

## Summary

- Text is split into **tokens**; models work with token numbers.
- An LLM predicts a probability for **every possible next token**.
- Text is generated **one token at a time** in a loop.
- Prices and limits are counted in tokens (≈ ¾ word each in English).
