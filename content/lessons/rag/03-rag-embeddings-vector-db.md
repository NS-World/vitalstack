---
title: "Embeddings and Vector Databases"
slug: rag-embeddings-vector-db
subject: rag
lesson: 3
difficulty: intermediate
excerpt: Turn chunks into embeddings with an embedding model, store them in a vector database (Chroma, FAISS, pgvector, Pinecone), and run fast similarity search, with a NumPy vector store you can run.
---

Keyword search finds "leave" but misses "time off" and "chutti". **Embeddings** search by meaning: texts about the same idea get vectors pointing the same way, whatever words they use. A **vector database** stores millions of them and finds the nearest ones in milliseconds.

::scene A library sorted by meaning
🗣️ | The question | "How much time off do I get?" contains neither "leave" nor "paid". | dev
🧭 | Embed | The embedding model turns the question into a list of numbers: a point in meaning-space. | chip
📍 | Nearby | The paid-leave chunk sits right next to it in that space. | byte
⚡ | Index | A vector database finds the nearest neighbours among millions of chunks in milliseconds. | bugsy
::end

## Getting embeddings

Embeddings come from an **embedding model**, either an API (OpenAI, Google, Cohere, Voyage AI and others offer them) or an open model you run yourself:

```python
from sentence_transformers import SentenceTransformer

model = SentenceTransformer("all-MiniLM-L6-v2")        # small open model, 384 numbers per text
vectors = model.encode(["How much time off do I get?", "Employees get 18 days of paid leave."],
                       normalize_embeddings=True)
print(vectors.shape, float(vectors[0] @ vectors[1]))     # similarity of the two texts
```

Use the **same model** for documents and questions, and re-embed everything if you switch models.

## A vector store in NumPy (runnable)

Here we fake small 4-number "embeddings" (dimensions: leave, money, travel, IT) to see exactly what a vector database does:

```python
import numpy as np

chunks = ["Employees get 18 days of paid leave per year.",
          "Salary is credited on the last working day.",
          "Flights need manager approval.",
          "Report lost laptops to IT within 24 hours."]
emb = np.array([[0.9, 0.1, 0.0, 0.0],
                [0.1, 0.9, 0.0, 0.0],
                [0.1, 0.2, 0.9, 0.0],
                [0.0, 0.0, 0.1, 0.9]])
emb = emb / np.linalg.norm(emb, axis=1, keepdims=True)    # normalise once

def search(query_vec, k=2):
    q = np.array(query_vec) / np.linalg.norm(query_vec)
    scores = emb @ q                                       # cosine similarity for every chunk at once
    top = np.argsort(scores)[::-1][:k]
    return [(chunks[i], round(float(scores[i]), 2)) for i in top]

time_off_question = [0.8, 0.3, 0.1, 0.0]                  # "How much time off do I get?"
print(search(time_off_question))
```

Output:

```text
[('Employees get 18 days of paid leave per year.', 0.96), ('Salary is credited on the last working day.', 0.45)]
```

The leave chunk wins, even though the question never said "leave".

::walk python | One similarity score
q = [0.8, 0.3, 0.1, 0.0]
c = [0.9, 0.1, 0.0, 0.0]
dot = sum(a * b for a, b in zip(q, c))
cos = dot / ((sum(a * a for a in q) ** 0.5) * (sum(b * b for b in c) ** 0.5))
print(round(dot, 2), round(cos, 2))
::notes
1: The question's embedding: mostly "leave", a bit "money". // vars: q=[0.8, 0.3, 0.1, 0.0]
2: The paid-leave chunk's embedding. // vars: c=[0.9, 0.1, 0.0, 0.0]
3: Dot product: 0.72 + 0.03 = 0.75. // vars: dot=0.75
4: Divide by both lengths: cosine similarity. // deep: If vectors are normalised to length 1 when stored, cosine similarity is just the dot product: that's why the NumPy store normalises once and then does a single matrix multiplication. // vars: cos=0.96
5: 0.96: almost the same direction. This chunk goes into the prompt. // out: 0.75 0.96
::end

## Real vector databases

| Option | Type | Good for |
|---|---|---|
| **Chroma** | Open source, embedded in Python | Prototypes, small apps |
| **FAISS** | Library from Meta | Very fast search on one machine |
| **pgvector** | PostgreSQL extension | Keep vectors next to your app's normal data |
| **Pinecone, Weaviate, Qdrant, Milvus** | Managed or self-hosted servers | Millions to billions of vectors, filtering, scaling |

```python
import chromadb

client = chromadb.PersistentClient(path="rag_db")
col = client.get_or_create_collection("handbook")
col.add(ids=["c1", "c2"], documents=[chunks[0], chunks[2]],
        metadatas=[{"source": "handbook.pdf", "page": 2}, {"source": "travel.pdf", "page": 4}])
hits = col.query(query_texts=["How much time off do I get?"], n_results=1)   # Chroma embeds with a default model
print(hits["documents"], hits["metadatas"])
```

Large stores use **approximate nearest neighbour (ANN)** indexes such as HNSW: they trade a tiny bit of accuracy for huge speed-ups.

::tip Combine embeddings with keyword search ("hybrid search"). Embeddings catch meaning; keywords catch exact terms like product codes, names and section numbers ("Rule 4.2.1") that embeddings can blur.

## Summary

- Embeddings place texts with similar meaning close together.
- Use one embedding model for both chunks and questions.
- Search = cosine similarity (a dot product on normalised vectors), top-k.
- Chroma/FAISS for small projects; pgvector or managed vector DBs at scale; add hybrid keyword search.
