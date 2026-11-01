---
title: "Vector Memory with Embeddings"
slug: memory-vector-embeddings
subject: agent-memory
lesson: 3
difficulty: intermediate
excerpt: Give an agent a searchable memory: store memories as vectors, retrieve by meaning, and rank them by relevance, recency and importance, with a runnable scorer and a vector-database version.
---

A facts store works for a few dozen keys. But after months of use, an agent might have thousands of memories: "struggled with window functions on day 3", "scored 9/10 on joins", "mentioned a job at a fintech". To find the right ones for **today's** task, store memories as **vectors** and search by meaning, then rank by how recent and how important they are.

::scene The memory jar
🫙 | The jar | Every useful moment becomes a note in Chip's memory jar, with a date and an importance score. | chip
🔎 | Search | Today's task: "plan SQL practice". Byte pulls out notes that mean something similar. | byte
⏳ | Fresh first | A note from yesterday counts more than one from last month. | dev
⭐ | Important first | "Struggled with window functions" beats "said the weather is nice". | bugsy
::end

## The three scores

| Score | Question it answers |
|---|---|
| **Relevance** | How similar is this memory to the current task? (cosine similarity of embeddings) |
| **Recency** | How long ago was it? (decays each day) |
| **Importance** | How much does it matter? (rated once, when saved) |

Final score = relevance + weight × recency + weight × importance.

::walk python | Scoring one memory
similarity = 0.42
days_ago = 7
importance = 0.8
recency = 0.9 ** days_ago
score = similarity + 0.5 * recency + 0.5 * importance
print(round(recency, 3), round(score, 3))
::notes
1: How close this memory is to today's task. // vars: similarity=0.42
2: It was saved a week ago. // vars: days_ago=7
3: Rated as important when it was saved (0 to 1). // vars: importance=0.8
4: Recency fades by 10% per day. After a week, under half remains. // deep: The decay rate is a design choice: 0.99 per hour suits a busy assistant, 0.9 per day suits a weekly tutor. Some systems reset recency each time a memory is used, so useful memories stay fresh. // vars: recency=0.478
5: Add them up, weighting recency and importance by 0.5 each.
6: A solid score: fairly relevant, quite important, a bit old. // out: 0.478 1.059
::end

## Retrieval you can run

TF-IDF stands in for embeddings here; swap in an embedding model for real meaning-based search.

```python
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

memories = [
    {"text": "Meera prefers short Hinglish explanations", "day": 1, "importance": 0.9},
    {"text": "Meera struggled with SQL window functions", "day": 3, "importance": 0.8},
    {"text": "Meera said the weather in Pune is nice", "day": 4, "importance": 0.1},
    {"text": "Meera scored 9/10 on the SQL joins quiz", "day": 9, "importance": 0.6},
    {"text": "Meera wants to practise SQL interview questions", "day": 10, "importance": 0.7},
]
today, query = 10, "plan today's SQL practice"

texts = [m["text"] for m in memories]
vec = TfidfVectorizer().fit(texts + [query])
sims = cosine_similarity(vec.transform([query]), vec.transform(texts))[0]
for m, s in zip(memories, sims):
    m["score"] = s + 0.5 * 0.9 ** (today - m["day"]) + 0.5 * m["importance"]

for m in sorted(memories, key=lambda m: -m["score"])[:3]:
    print(f"{m['score']:.2f}  {m['text']}")
```

Output:

```text
0.93  Meera wants to practise SQL interview questions
0.83  Meera scored 9/10 on the SQL joins quiz
0.73  Meera struggled with SQL window functions
```

The weather memory never makes the top 3. The agent's context gets the three memories that matter for planning SQL practice.

## With a vector database

```python
import chromadb

db = chromadb.PersistentClient(path="memory_db")
mem = db.get_or_create_collection("meera")
mem.add(ids=["m1", "m2"],
        documents=["Meera prefers short Hinglish explanations", "Meera struggled with SQL window functions"],
        metadatas=[{"day": 1, "importance": 0.9}, {"day": 3, "importance": 0.8}])

hits = mem.query(query_texts=["plan today's SQL practice"], n_results=5)
# re-rank hits with recency and importance from the metadata, then keep the top 3
```

Chroma embeds the text with a default model; you can plug in any embedding model. LangGraph, LlamaIndex and dedicated memory libraries offer similar stores.

## Writing good memories

- **One idea per memory**, in a full sentence with names ("Meera struggled with…", not "struggled").
- **Rate importance when saving** (a quick LLM call: "rate 0–1 how useful this is for future tutoring").
- **Merge duplicates** now and then, and summarise many small memories into one ("Weak in window functions; improving, 6/10 → 8/10").
- **Per user:** never mix memories between users; filter by user id on every query.

::tip Retrieval mistakes look like model mistakes. When an agent "forgets" something, print the memories it retrieved for that step; usually the right memory just wasn't in the top results.

## Summary

- Vector memory stores memories as embeddings and searches them by meaning.
- Rank by relevance + recency + importance, then load only the top few.
- Vector databases like Chroma store memories with metadata for re-ranking.
- Write clear, single-idea memories, merge duplicates, and keep users separate.

🎉 You've finished **Memory and State**.
