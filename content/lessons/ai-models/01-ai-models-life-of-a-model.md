---
title: "From Data to ChatGPT: The Life of an AI Model"
slug: ai-models-life-of-a-model
subject: ai-models
lesson: 1
difficulty: beginner
excerpt: How are ChatGPT, Claude, Gemini and Grok actually made? A beginner-friendly tour of the six stages every large language model goes through, from raw text to the chat box.
---

You type a question, press Enter, and a few seconds later a paragraph appears that reads like a thoughtful person wrote it. It feels like magic. It isn't. Every assistant you have used, **ChatGPT** (OpenAI), **Claude** (Anthropic), **Gemini** (Google) and **Grok** (xAI), was built through the same six stages. Once you know them, a lot of confusing AI news suddenly makes sense.

This course needs no maths and no coding experience. Where there is code, it is short Python you can run in our [online Python compiler](/tools/python-online-compiler/).

## The film version

Press play. Six scenes, one per stage.

::scene The life of an AI model
📚 | Stage 1: Collect | Engineers gather a huge library of text: web pages, books, code and papers. Then they throw most of it away. | dev
🔤 | Stage 2: Tokenize | All that text is chopped into small pieces called **tokens**, and every piece gets a number. | chip
🏋️ | Stage 3: Pretrain | Thousands of GPUs play one game for weeks: **guess the next token**. Every wrong guess nudges billions of numbers. | byte
🎓 | Stage 4: Fine-tune | The model now knows a lot but rambles. Humans show it thousands of good question-and-answer examples. | dev
⚖️ | Stage 5: Align | People (and AI helpers) compare answers and say which is better. The model learns to be helpful, honest and safe. | bugsy
🚀 | Stage 6: Serve | The finished model goes onto servers. Your question travels there, and the answer streams back token by token. | chip
::end

## Stage 1: Collect the data

A model can only learn what it reads. Companies collect **trillions of tokens** of text: public web pages, books, encyclopedias, research papers, code from public repositories, and licensed or purchased data. Then comes the unglamorous part, which takes most of the effort: **cleaning**. Spam, duplicates, broken pages and harmful content are filtered out. Lesson 2 shows how.

## Stage 2: Turn text into numbers

Computers only work with numbers, so text is split into **tokens** (whole words or pieces of words) and each token gets an ID from a fixed vocabulary of tens of thousands of entries.

```python
vocab = {"Chai": 101, " is": 7, " the": 3, " best": 58, "!": 2}
sentence = ["Chai", " is", " the", " best", "!"]
print([vocab[t] for t in sentence])
```

Output:

```text
[101, 7, 3, 58, 2]
```

From here on, the model never sees letters, only these numbers.

## Stage 3: Pretraining, the expensive part

The model starts as a giant pile of random numbers called **parameters** (or weights): billions of them. Training is one simple game repeated trillions of times:

1. Show the model some text with the next token hidden.
2. The model guesses a probability for every possible next token.
3. Measure how wrong the guess was (the **loss**).
4. Nudge every parameter slightly in the direction that would have made the guess better.

After enough rounds, the only way to guess well is to have learned grammar, facts, reasoning patterns and even some coding. Nobody programs those skills in; they **emerge** from guessing. This stage runs on thousands of GPUs for weeks or months and is the main reason frontier models cost so much to build.

## Stage 4: Fine-tuning, from know-it-all to assistant

A freshly pretrained model is a brilliant autocomplete, not an assistant. Ask it "How do I boil an egg?" and it might continue with three more questions, as if writing a quiz. So it is trained further on examples of **instructions with good answers**, written or reviewed by people. This is called **supervised fine-tuning** (SFT).

## Stage 5: Alignment, teaching good judgement

Next the model writes several answers to the same question, and people (and increasingly other AI models following written guidelines) rank them. A training method then shifts the model towards answers that ranked high: clearer, more honest, more careful with harmful requests. You'll meet the names **RLHF** and **Constitutional AI** in lesson 4.

## Stage 6: Serving: the chat box

The trained model is copied onto servers full of GPUs. When you hit Enter, your text (plus the conversation so far) is tokenized, run through the model, and the answer is generated **one token at a time** and streamed back. Every token costs computing power, which is why API prices are quoted **per million tokens**.

::tip A useful mental model: **pretraining gives knowledge, fine-tuning gives manners, alignment gives judgement, serving gives speed.** When an AI company announces a "new model", it usually changed one or more of these stages.

## Quick check: which stage is it?

```python
news = {
    "New model trained on 2x more text": "pretrain",
    "Chatbot now refuses fewer harmless requests": "align",
    "Answers stream twice as fast": "serve",
    "Better at following formatting instructions": "fine-tune",
}
for headline, stage in news.items():
    print(f"{stage:>9} <- {headline}")
```

Output:

```text
 pretrain <- New model trained on 2x more text
    align <- Chatbot now refuses fewer harmless requests
    serve <- Answers stream twice as fast
fine-tune <- Better at following formatting instructions
```

## Summary

| Stage | What happens | What it gives the model |
|---|---|---|
| 1. Collect | Gather and clean text | Something to learn from |
| 2. Tokenize | Text becomes numbers | A language computers can process |
| 3. Pretrain | Guess the next token, trillions of times | Knowledge and skills |
| 4. Fine-tune | Learn from good examples | Assistant behaviour |
| 5. Align | Learn from human (and AI) preferences | Judgement and safety |
| 6. Serve | Run on GPUs for millions of users | Speed and scale |

Next: how the training data is collected and cleaned, with a tiny duplicate-remover you can run.
