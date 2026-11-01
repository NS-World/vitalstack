---
title: "Why RAG: Answers from Your Own Data"
slug: rag-why-rag
subject: rag
lesson: 1
difficulty: intermediate
excerpt: Retrieval-Augmented Generation explained: why LLMs need your documents, the retrieve-then-generate flow, RAG vs fine-tuning vs long context, and a complete mini RAG system in plain Python.
---

Ask ChatGPT about your college's exam rules, your company's leave policy or yesterday's price list and it can't know: that information wasn't in its training data. **Retrieval-Augmented Generation (RAG)** fixes this: first **retrieve** the relevant pieces of your documents, then let the model **generate** an answer from them.

::scene The open-book exam
📕 | Closed book | Without RAG, the model answers from memory. For your private documents it guesses, or hallucinates. | bugsy
🔎 | Look it up | With RAG, the app first searches your documents for the passages that match the question. | chip
📋 | Open book | Those passages are pasted into the prompt: "Answer using only this." | dev
✅ | Grounded | The answer is correct, current, and cites the page it came from. | byte
::end

## The RAG pipeline

**Indexing (once):** load documents → split into chunks → embed each chunk → store in a vector database.
**Answering (every question):** embed the question → find the most similar chunks → put them in the prompt → the LLM answers with citations.

## A complete mini RAG in plain Python

```python
import re
from collections import Counter
import math

docs = {
    "leave-policy.pdf p2": "Employees get 18 days of paid leave per year. Unused leave up to 10 days carries over.",
    "leave-policy.pdf p3": "Sick leave of 12 days is separate. A doctor's note is needed for more than 2 days.",
    "it-policy.pdf p1": "Laptops must be locked when unattended. Report lost devices to IT within 24 hours.",
    "travel-policy.pdf p4": "Train travel is AC 3-tier for trips under 8 hours. Flights need manager approval.",
}

def words(text):
    return re.findall(r"[a-z0-9]+", text.lower())

def score(question, passage):
    q, p = Counter(words(question)), Counter(words(passage))
    overlap = sum((q & p).values())
    return overlap / math.sqrt(len(words(passage)))

def retrieve(question, k=2):
    ranked = sorted(docs.items(), key=lambda kv: score(question, kv[1]), reverse=True)
    return ranked[:k]

def build_prompt(question):
    context = "\n".join(f"[{src}] {text}" for src, text in retrieve(question))
    return ("Answer using ONLY the context. Cite the source in brackets. "
            "If the answer isn't there, say you don't know.\n\n"
            f"Context:\n{context}\n\nQuestion: {question}")

print(build_prompt("How many days of paid leave do I get?"))
```

Output:

```text
Answer using ONLY the context. Cite the source in brackets. If the answer isn't there, say you don't know.

Context:
[leave-policy.pdf p2] Employees get 18 days of paid leave per year. Unused leave up to 10 days carries over.
[leave-policy.pdf p3] Sick leave of 12 days is separate. A doctor's note is needed for more than 2 days.

Question: How many days of paid leave do I get?
```

Send that prompt to any LLM (lesson 2 of *Working with LLM APIs*) and it answers "18 days per year [leave-policy.pdf p2]". Real systems replace the word-overlap `score` with **embeddings** (lesson 3), so "time off" also finds "leave".

::walk python | Scoring one passage against the question
question = "paid leave days"
passage = "employees get 18 days of paid leave"
q, p = set(question.split()), passage.split()
overlap = sum(1 for w in p if w in q)
score = round(overlap / len(p) ** 0.5, 2)
print(overlap, score)
::notes
1: The user's question (already cleaned). // vars: question=paid leave days
2: One candidate passage from the documents. // vars: passage=employees get 18 days of paid leave
3: Question words as a set; passage as a list of words. // vars: q={'paid', 'leave', 'days'}
4: Count passage words that appear in the question: days, paid, leave = 3. // vars: overlap=3
5: Divide by √length so long passages don't win just by being long. // deep: This is a toy version of BM25, the classic keyword-search score used by Elasticsearch and many RAG systems in "hybrid search" alongside embeddings. // vars: score=1.13
6: Higher score = more relevant. The top-scoring chunks go into the prompt. // out: 3 1.13
::end

## RAG vs fine-tuning vs long context

| Approach | Best for | Weakness |
|---|---|---|
| **RAG** | Facts that change, private documents, citations | Needs a good retrieval pipeline |
| **Fine-tuning** | Style, format, a narrow skill | Doesn't reliably add new facts; retraining when facts change |
| **Long context** (paste everything) | A few documents, one-off questions | Expensive and slower per question; doesn't scale to thousands of files |

Most company chatbots over policies, manuals, product catalogues and support articles use RAG.

::tip The "say you don't know" instruction matters as much as retrieval. A RAG bot that admits "that's not in the documents" builds far more trust than one that improvises.

## Summary

- RAG = retrieve relevant chunks, then generate an answer grounded in them, with citations.
- Indexing: chunk → embed → store. Answering: embed question → search → prompt → answer.
- Prefer RAG for changing or private facts; fine-tuning for style and skills.
- Always allow "I don't know" and show sources.
