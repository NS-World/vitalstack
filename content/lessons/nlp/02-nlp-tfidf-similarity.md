---
title: "TF-IDF, Text Similarity and Search"
slug: nlp-tfidf-similarity
subject: nlp
lesson: 2
difficulty: intermediate
excerpt: Turn documents into vectors with bag-of-words and TF-IDF, compare them with cosine similarity, build a mini search engine, and measure retrieval with precision and recall. With scikit-learn.
---

How does a help centre find the right FAQ for "refund not received"? How do you spot duplicate complaints? Before neural networks, and still very often today, the answer is **TF-IDF**: a clever way to turn text into numbers that highlights the words that matter.

::scene Finding the needle
📚 | 500 FAQs | A customer types "money not refunded yet". Which answer fits? | dev
🔢 | Vectors | Every FAQ becomes a vector of word weights. Rare, specific words weigh more. | chip
📐 | Similarity | Cosine similarity finds the FAQs pointing in the same direction as the question. | byte
🎯 | Answer | "How long do refunds take?" is the top match. | bugsy
::end

## Bag of words

Count each word in each document:

```python
from sklearn.feature_extraction.text import CountVectorizer

docs = ["refund not received", "how long does a refund take", "change delivery address", "delivery is late"]
cv = CountVectorizer()
counts = cv.fit_transform(docs)
print(cv.get_feature_names_out().tolist())
print(counts.toarray()[0].tolist())
```

Output:

```text
['address', 'change', 'delivery', 'does', 'how', 'is', 'late', 'long', 'not', 'received', 'refund', 'take']
[0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0]
```

## TF-IDF: important words weigh more

**TF** (term frequency): how often a word appears in this document. **IDF** (inverse document frequency): how rare the word is across all documents. TF × IDF is high for words frequent here but rare elsewhere.

::walk python | IDF by hand
import math
docs_with_word = {"refund": 2, "delivery": 2, "address": 1}
n_docs = 4
idf = {w: round(math.log((1 + n_docs) / (1 + df)) + 1, 3) for w, df in docs_with_word.items()}
print(idf)
::notes
1: math for the logarithm.
2: In how many of our 4 documents each word appears. // vars: docs_with_word={'refund': 2, 'delivery': 2, 'address': 1}
3: Total documents. // vars: n_docs=4
4: scikit-learn's smoothed IDF: log((1 + n) / (1 + df)) + 1. // deep: The +1s avoid dividing by zero and keep every word's weight positive. A word in every document gets the minimum weight of 1, so it barely helps tell documents apart. // vars: idf={'refund': 1.511, 'delivery': 1.511, 'address': 1.916}
5: "address" is rarer, so it gets a higher weight: it's more informative. // out: {'refund': 1.511, 'delivery': 1.511, 'address': 1.916}
::end

## A mini FAQ search engine

```python
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

faqs = [
    "How long does a refund take to reach my bank?",
    "How do I change my delivery address?",
    "My order is late, where is it?",
    "How do I cancel my order?",
    "Can I pay with UPI or cash on delivery?",
]
tfidf = TfidfVectorizer(stop_words="english", ngram_range=(1, 2))
faq_vectors = tfidf.fit_transform(faqs)

def search(query, k=2):
    scores = cosine_similarity(tfidf.transform([query]), faq_vectors)[0]
    best = scores.argsort()[::-1][:k]
    return [(faqs[i], round(float(scores[i]), 2)) for i in best]

print(search("refund not reached bank"))
print(search("cancel order"))
```

Output:

```text
[('How long does a refund take to reach my bank?', 0.47), ('Can I pay with UPI or cash on delivery?', 0.0)]
[('How do I cancel my order?', 1.0), ('My order is late, where is it?', 0.25)]
```

Notice the first query matched despite "reached" vs "reach": stemming or lemmatisation would raise the score further. And TF-IDF can't know that "money back" means "refund": that needs **embeddings** (meaning-based vectors), covered in the Gen AI track's RAG module.

## Measuring a search system

| Metric | Question |
|---|---|
| Precision@k | Of the top k results, how many were relevant? |
| Recall@k | Of all relevant documents, how many appear in the top k? |
| F-score | Balance of the two |

```python
relevant = {"faq1", "faq4"}
returned = ["faq1", "faq3", "faq4"]
hits = len(relevant & set(returned))
print("precision@3:", round(hits / len(returned), 2), "| recall@3:", hits / len(relevant))
```

Output:

```text
precision@3: 0.67 | recall@3: 1.0
```

## Other text distances

- **Levenshtein (edit) distance:** how many single-character edits turn one string into another: great for typos ("Bengaluru" vs "Bengluru").
- **Jaccard similarity:** overlap of word sets ÷ union: quick duplicate detection.

::tip TF-IDF + logistic regression is still a strong, fast baseline for text classification (spam, ticket routing, topic tagging). Try it before reaching for a transformer.

## Summary

- Bag of words counts; TF-IDF weighs rare, specific words higher.
- Cosine similarity between TF-IDF vectors powers simple search and duplicate detection.
- Measure retrieval with precision@k and recall@k.
- TF-IDF matches words, not meaning; embeddings fix that.
