---
title: "Evaluating a RAG App"
slug: rag-evaluation
subject: rag
lesson: 5
difficulty: intermediate
excerpt: Measure RAG quality properly: a test set of questions, retrieval metrics (hit rate, MRR), answer metrics (faithfulness, correctness), LLM-as-judge, and a regression check before every change.
---

"It looks fine when I try it" is not evaluation. A RAG app has two parts that fail differently, the retriever and the generator, so you measure both, on a fixed set of questions, every time you change anything.

::scene The RAG report card
📝 | Test set | 50 real questions, each with the correct answer and the document that contains it. | dev
🔎 | Retrieval grade | Did the right chunk appear in the top 5? How high? | chip
🗣️ | Answer grade | Was the answer correct, and did it stick to the sources? | byte
📈 | Compare | New chunk size? New model? Rerun the report card before shipping. | bugsy
::end

## Build a test set

Start with 30–100 questions from real users (or written by people who know the documents), each with:

- the **expected answer** (short), and
- the **source chunk(s)** that contain it.

Include hard cases: questions whose answer isn't in the documents (the bot should say so), questions needing two documents, and tricky wording.

## Retrieval metrics

```python
test = [
    {"q": "paid leave days", "gold": "c1", "retrieved": ["c1", "c4", "c9"]},
    {"q": "sick leave note", "gold": "c2", "retrieved": ["c7", "c2", "c1"]},
    {"q": "flight approval", "gold": "c5", "retrieved": ["c6", "c8", "c3"]},
    {"q": "lost laptop", "gold": "c6", "retrieved": ["c6", "c1", "c2"]},
]

hit_rate = sum(t["gold"] in t["retrieved"] for t in test) / len(test)
mrr = sum(1 / (t["retrieved"].index(t["gold"]) + 1) if t["gold"] in t["retrieved"] else 0 for t in test) / len(test)
print(f"hit rate@3: {hit_rate:.2f}  MRR: {mrr:.2f}")
print("missed:", [t["q"] for t in test if t["gold"] not in t["retrieved"]])
```

Output:

```text
hit rate@3: 0.75  MRR: 0.62
missed: ['flight approval']
```

- **Hit rate@k:** how often the right chunk is in the top k.
- **MRR (mean reciprocal rank):** rewards finding it near the top (1st = 1, 2nd = 0.5, 3rd = 0.33).
- The **missed** list tells you exactly what to fix: here, the travel policy chunk isn't being found (chunking? missing synonyms? needs hybrid search?).

::walk python | Reciprocal rank for one question
retrieved = ["c7", "c2", "c1"]
gold = "c2"
rank = retrieved.index(gold) + 1
rr = 1 / rank
print(rank, rr)
::notes
1: What the retriever returned, best first. // vars: retrieved=['c7', 'c2', 'c1']
2: The chunk that actually holds the answer. // vars: gold=c2
3: It's in position 2 (index 1, plus 1). // vars: rank=2
4: Reciprocal rank: 1 ÷ 2. // deep: Averaging this over all questions gives MRR. A perfect retriever scores 1.0; finding nothing scores 0 for that question. // vars: rr=0.5
5: Found, but not first: half credit. // out: 2 0.5
::end

## Answer metrics

| Metric | Question | How to check |
|---|---|---|
| **Correctness** | Does it match the expected answer? | Exact/number match, or an LLM judge |
| **Faithfulness** (groundedness) | Is every claim supported by the retrieved chunks? | Citation checks (lesson 4), LLM judge |
| **Refusal accuracy** | Does it say "not found" when it should? | Questions with no answer in the docs |
| **Helpfulness** | Is it clear, complete, the right length? | Human review of a sample |

## LLM-as-judge

A strong model grades answers against the expected answer with a strict rubric. Keep the judge's output structured:

```python
JUDGE = """You are grading a policy chatbot.
Question: {q}
Expected answer: {expected}
Chatbot answer: {answer}
Reply with JSON only: {{"correct": true/false, "grounded": true/false, "reason": "<one sentence>"}}"""

def judge_prompt(q, expected, answer):
    return JUDGE.format(q=q, expected=expected, answer=answer)

print(judge_prompt("How many paid leave days?", "18 days per year", "You get 18 days [doc 1].").splitlines()[3])
```

Output:

```text
Chatbot answer: You get 18 days [doc 1].
```

Check the judge itself: grade 20 answers by hand and make sure the judge agrees with you most of the time before trusting it at scale. Libraries such as Ragas, DeepEval and TruLens package these metrics.

## A regression gate

```python
baseline = {"hit_rate": 0.86, "correct": 0.82, "grounded": 0.95}
candidate = {"hit_rate": 0.90, "correct": 0.84, "grounded": 0.91}
ship = all(candidate[m] >= baseline[m] - 0.01 for m in baseline)
print("ship the change?", ship, "| worse on:", [m for m in baseline if candidate[m] < baseline[m] - 0.01])
```

Output:

```text
ship the change? False | worse on: ['grounded']
```

The new version retrieves and answers better but hallucinates more: don't ship it until groundedness recovers.

::tip Evaluate retrieval first. If the right chunk isn't retrieved, no prompt or model can produce the right answer. Most RAG improvements come from chunking, metadata filters and hybrid search, not from a bigger LLM.

## Summary

- Keep a fixed test set with expected answers and gold chunks, including "not in the docs" questions.
- Retrieval: hit rate@k and MRR; list the misses.
- Answers: correctness, faithfulness, refusal accuracy; LLM judges checked against humans.
- Gate every change: no metric may get worse.

🎉 You've finished **Retrieval-Augmented Generation (RAG)**.
