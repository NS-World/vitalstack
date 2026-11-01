---
title: "Why Models Hallucinate and How to Reduce It"
slug: llm-hallucinations
subject: llm-basics
lesson: 5
difficulty: intermediate
excerpt: LLMs sometimes state false things with full confidence. Learn why, and five practical defences, including grounding answers in sources and checking them with code.
---

A student asks a chatbot for "three research papers on exam stress in Indian colleges". It replies with three neat citations: authors, titles, journals, years. Two of them **don't exist**. The model didn't lie on purpose; it did what it always does: produce **likely-looking text**.

This is called **hallucination**: fluent, confident output that is not true.

## Why it happens

From lesson 1: a model predicts the next token from patterns. A citation *pattern* (Name, Year, "Title", Journal) is extremely common in its training data, so it can produce one smoothly, even when it doesn't know a real paper. Nothing inside the model checks facts against the world.

Hallucinations are most likely when:

- the question is about **rare or recent** facts (little or no training data);
- the prompt **pushes for an answer** ("give me exactly 3 papers");
- exact details matter: **numbers, names, dates, quotes, links, code APIs**.

## Defence 1: give it the facts (grounding)

Instead of asking the model to remember, **put the facts in the prompt** and tell it to answer only from them. This is what RAG does. Here's a tiny version: the "model" may only answer from the notes it's given, and must say so when the answer isn't there.

```python
NOTES = {
    "refund": "Refunds are possible within 7 days if under 30% of lessons were opened.",
    "devices": "Premium works on one phone and one computer at a time.",
    "certificate": "A certificate is given after finishing every lesson and quiz in a track.",
}

def grounded_answer(question):
    q = question.lower()
    hits = [text for key, text in NOTES.items() if key in q]
    if not hits:
        return "I don't know: that isn't in my notes."
    return hits[0] + " [source: help notes]"

print(grounded_answer("How does the refund work?"))
print(grounded_answer("Can I get a job guarantee?"))
```

Output:

```text
Refunds are possible within 7 days if under 30% of lessons were opened. [source: help notes]
I don't know: that isn't in my notes.
```

Two things matter here: the answer **cites its source**, and the model is **allowed to say "I don't know"**. Models given permission to refuse hallucinate much less.

## Defence 2: check the output with code

Never trust numbers blindly. When the model returns structured data, verify it with plain code before showing it:

::walk python | Catching a made-up total
items = [("Python course", 1999), ("Stickers", 150)]
model_total = 2249
real_total = sum(price for name, price in items)
if model_total != real_total:
    print("Model said", model_total, "but the real total is", real_total)
else:
    print("Total checked:", real_total)
::notes
1: The real cart: two items with their prices. // vars: items=[('Python course', 1999), ('Stickers', 150)]
2: The model summarised the cart and said the total was **2,249**. It looks believable. // deep: Models are surprisingly weak at exact arithmetic, because they predict digits as tokens instead of calculating. That's why agents use calculator tools (Agentic AI track, lesson 1). // vars: model_total=2249
3: Our code adds the prices itself: 1999 + 150 = **2,149**. // deep: The generator expression takes each (name, price) pair and keeps only the price, then sum() adds them. // vars: real_total=2149
4: They don't match: the model was off by exactly 100.
5: We catch it before any customer sees the wrong bill. // out: Model said 2249 but the real total is 2149
::end

## Defence 3: ask for less, and lower the temperature

- Ask for what the model can do reliably: "Summarise this paragraph", not "Name 10 statistics about Indian EdTech".
- Use **temperature 0–0.2** for factual tasks (lesson 4). Creativity is not what you want here.

## Defence 4: make it show its sources and quotes

Ask for **exact quotes** from the provided text alongside each claim. Then check, with code, that each quote really appears in the source:

```python
source = "Premium works on one phone and one computer at a time."
quotes = ["one phone and one computer", "up to five devices"]
for q in quotes:
    print(f"{q!r}:", "found" if q in source else "NOT in source")
```

Output:

```text
'one phone and one computer': found
'up to five devices': NOT in source
```

A quote that isn't in the source is a red flag for the whole answer.

## Defence 5: keep a human in the loop

For anything that matters (medical, legal, money, exam answers, public posts), a person reviews before it's used. Design your app so that's easy: show sources, highlight numbers, and make "report a wrong answer" one click.

::tip The best mindset: treat an LLM like a **very fast, very well-read intern** who sometimes makes things up confidently. Give them the documents, ask for sources, and check the important parts.

## Summary

- Hallucination = confident, fluent, **false** output, caused by predicting likely text.
- Highest risk: rare facts, exact numbers, names, links and citations.
- Defences: **ground** answers in provided text, allow "I don't know", **verify with code**, use low temperature, require **quotes**, and keep a **human** in the loop.

🎉 You have finished **How LLMs Work**. You now understand tokens, embeddings, attention, sampling and hallucinations: the foundation for every Gen AI app you'll build next.
