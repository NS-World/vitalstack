---
title: "Embeddings: Meaning as Numbers"
slug: llm-embeddings
subject: llm-basics
lesson: 2
difficulty: intermediate
excerpt: Embeddings turn words and sentences into lists of numbers where similar meanings sit close together. Compute cosine similarity by hand and build a mini semantic search.
---

Computers can't compare meanings directly. "Car" and "automobile" share no letters, yet mean the same. **Embeddings** solve this: they turn each word, sentence or document into a **list of numbers** (a vector) so that **similar meanings get similar numbers**.

Every modern AI app uses them: search that understands meaning, recommendations, RAG chatbots over your documents, duplicate detection.

## A tiny embedding by hand

Real embeddings have hundreds or thousands of numbers, learned by a model. To see the idea, let's invent 3-number embeddings where each position means something we can name: **[is it food?, is it tech?, is it sweet?]**

```python
emb = {
    "mango":   [0.9, 0.0, 0.8],
    "laddoo":  [0.8, 0.0, 0.9],
    "samosa":  [0.9, 0.0, 0.1],
    "laptop":  [0.0, 0.9, 0.0],
    "python":  [0.0, 1.0, 0.1],
}
print(emb["mango"], emb["laptop"])
```

Output:

```text
[0.9, 0.0, 0.8] [0.0, 0.9, 0.0]
```

Mango and laddoo both score high on "food" and "sweet". Laptop scores high only on "tech". In a real model nobody names the positions; the model discovers useful directions by itself during training.

## Measuring closeness: cosine similarity

To compare two vectors we use **cosine similarity**: 1 means "pointing the same way" (same meaning), 0 means unrelated.

::walk python | Cosine similarity, step by step
a = [0.9, 0.0, 0.8]
b = [0.8, 0.0, 0.9]
dot = sum(x * y for x, y in zip(a, b))
len_a = sum(x * x for x in a) ** 0.5
len_b = sum(y * y for y in b) ** 0.5
cos = dot / (len_a * len_b)
print("mango vs laddoo:", round(cos, 3))
::notes
1: The embedding for **mango**: food 0.9, tech 0, sweet 0.8. // vars: a=[0.9, 0.0, 0.8]
2: The embedding for **laddoo**: very similar numbers. // vars: b=[0.8, 0.0, 0.9]
3: The **dot product**: multiply matching positions and add. 0.72 + 0 + 0.72 = **1.44**. // deep: zip pairs the lists position by position: (0.9, 0.8), (0.0, 0.0), (0.8, 0.9). A big dot product means both vectors are large in the same positions. // vars: dot=1.44
4: The **length** of a: √(0.81 + 0 + 0.64) ≈ **1.204**. // deep: This is Pythagoras in 3 dimensions. ** 0.5 is the square root. // vars: len_a=1.2041594578792296
5: The length of b: also ≈ **1.204** (same numbers, swapped). // vars: len_b=1.2041594578792296
6: Divide the dot product by both lengths. This removes size and keeps only **direction**. // deep: Dividing by the lengths means a long document and a short sentence about the same topic still score high together. // vars: cos=0.9931034482758621
7: 0.993: almost identical meaning. // out: mango vs laddoo: 0.993
::end

Let's wrap that in a function and compare a few pairs:

```python
def cosine(a, b):
    dot = sum(x * y for x, y in zip(a, b))
    return dot / ((sum(x * x for x in a) ** 0.5) * (sum(y * y for y in b) ** 0.5))

for w1, w2 in [("mango", "laddoo"), ("mango", "samosa"), ("mango", "laptop"), ("laptop", "python")]:
    print(f"{w1:>7} vs {w2:<7} {cosine(emb[w1], emb[w2]):.2f}")
```

Output:

```text
  mango vs laddoo  0.99
  mango vs samosa  0.82
  mango vs laptop  0.00
 laptop vs python  1.00
```

Mango is close to laddoo (sweet food), fairly close to samosa (food, but not sweet), and unrelated to laptop.

## A mini semantic search

This is how "search by meaning" works: embed the question, embed every document, return the closest.

```python
def search(query_vec, k=2):
    scores = [(cosine(query_vec, v), w) for w, v in emb.items()]
    return [w for s, w in sorted(scores, reverse=True)[:k]]

something_sweet = [0.7, 0.0, 1.0]
print(search(something_sweet))
```

Output:

```text
['laddoo', 'mango']
```

The query "something sweet to eat" never mentioned laddoo, but its vector points the same way. In a real app, an **embedding model** creates both the query vector and the document vectors, and a **vector database** finds the nearest ones among millions in milliseconds.

::tip This is the heart of **RAG** (Retrieval-Augmented Generation): embed your documents once, then for each question find the closest chunks and give them to the LLM to answer from. You'll build one in the RAG module.

## Summary

- An **embedding** is a list of numbers that represents meaning.
- Similar meanings → vectors pointing in similar directions.
- **Cosine similarity** measures this: 1 = same, 0 = unrelated.
- Semantic search = embed everything, return the nearest vectors.
