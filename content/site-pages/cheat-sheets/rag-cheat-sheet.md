---
title: RAG Cheat Sheet (Retrieval-Augmented Generation)
slug: rag-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: 📚
order: 130
excerpt: RAG on one page - the pipeline, chunking settings, embeddings and vector databases, hybrid search and re-ranking, a grounded prompt template, citations, evaluation metrics and the usual fixes.
---
## The pipeline

```
documents → extract text → chunk (+ metadata) → embed → vector store
question  → embed → retrieve top-k → (re-rank) → prompt with chunks → answer + citations
```

## Chunking

| Setting | Typical start | Notes |
|---|---|---|
| Chunk size | 300–800 tokens | Smaller = precise, larger = more context |
| Overlap | 10–20% | Keeps sentences that cross a boundary |
| Split on | Headings → paragraphs → sentences | Follow the document's structure |
| Metadata | source, page, section, date | Needed for citations and filters |

## Retrieval

| Technique | Use when |
|---|---|
| Vector search (embeddings) | Questions use different words than the docs |
| Keyword search (BM25) | Exact terms, codes, names, error messages |
| Hybrid (both) | Most real apps |
| Re-ranking | Retrieve 20–50, re-rank, keep the best 3–8 |
| Metadata filters | "Only HR policies", "only 2026 documents" |

Rule: use the **same embedding model** for documents and questions.

## A grounded prompt

```text
Answer the question using ONLY the sources below.
Cite each fact like [S2]. If the sources don't contain the answer,
say "I couldn't find this in the documents."

<sources>
[S1] (handbook.pdf, p.12) ...
[S2] (leave-policy.pdf, p.3) ...
</sources>

Question: {question}
```

## Evaluation

| Metric | Question |
|---|---|
| Retrieval hit rate / recall@k | Is the right chunk in the top k? |
| Faithfulness | Is every claim supported by the retrieved text? |
| Answer correctness | Does it match the reference answer? |
| Citation validity | Do cited sources exist in what was retrieved? |
| Refusal rate | Does it say "not found" when it should? |

## Symptoms and fixes

| Symptom | Likely fix |
|---|---|
| Right answer exists but isn't found | Better chunking, hybrid search, more k, re-ranking |
| Finds it, answers wrongly | Clearer prompt, fewer but better chunks |
| Makes things up | "Only from sources" rule, refuse when nothing retrieved, citation checks |
| Slow or costly | Smaller k, cache answers, shorter chunks |
| Old information | Re-index on change, date metadata and filters |

Build it step by step in the premium [RAG module](/tutorial-category/rag/) and the [Chat with your PDFs capstone](/tutorial-category/genai-capstones/).
