---
title: "Sentiment Analysis: From TF-IDF to Transformers"
slug: nlp-sentiment-transformers
subject: nlp
lesson: 3
difficulty: intermediate
excerpt: Classify reviews as positive or negative with a TF-IDF + logistic regression baseline you can run, then with a pretrained transformer from Hugging Face, and know when to use which.
---

"Are customers happy?" is one of the first questions every business asks of its text data. **Sentiment analysis** labels each review, tweet or ticket as positive, negative or neutral, and lets you track the mood over thousands of messages.

::scene The mood meter
📥 | 10,000 reviews | Too many to read. The product team needs the mood by tomorrow. | dev
🧪 | Baseline | TF-IDF plus logistic regression, trained in seconds. Already 85% right. | chip
🤗 | Transformer | A pretrained model understands "not bad at all" and sarcasm better. | byte
📊 | Dashboard | Negative reviews spike on Mondays: late weekend deliveries. Problem found. | bugsy
::end

## A fast baseline you can run

```python
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.linear_model import LogisticRegression
from sklearn.pipeline import make_pipeline

train_texts = [
    "loved the food, will order again", "super fast delivery and tasty", "great taste, fresh and hot",
    "excellent packaging, very happy", "best biryani in town", "quick delivery, good portion",
    "food was cold and late", "terrible taste, never again", "delivery took two hours, very bad",
    "order was wrong and rude support", "stale bread, waste of money", "late again, disappointed",
]
train_labels = ["pos"] * 6 + ["neg"] * 6

clf = make_pipeline(TfidfVectorizer(ngram_range=(1, 2)), LogisticRegression())
clf.fit(train_texts, train_labels)
tests = ["tasty and fast", "cold food and late delivery", "not bad at all"]
print(dict(zip(tests, clf.predict(tests).tolist())))
```

Output:

```text
{'tasty and fast': 'pos', 'cold food and late delivery': 'neg', 'not bad at all': 'neg'}
```

The baseline gets the easy ones and fails on "not bad at all": it sees "bad" and votes negative. More data with bigrams helps a bit; real understanding of negation needs a model that reads whole sentences.

## What the model learned

::walk python | Reading the strongest words
weights = {"tasty": 0.62, "fast": 0.55, "late": -0.71, "cold": -0.58, "bad": -0.66}
positive = sorted((w for w in weights if weights[w] > 0), key=weights.get, reverse=True)
negative = sorted((w for w in weights if weights[w] < 0), key=weights.get)
print(positive, negative)
::notes
1: Logistic regression gives every word (feature) a weight. These are illustrative values. // deep: With the pipeline above you'd read them from clf[-1].coef_ and match them to clf[0].get_feature_names_out(). Inspecting the top words is the fastest sanity check of a text model. // vars: weights=5 words
2: Positive weights push towards "pos", strongest first. // vars: positive=['tasty', 'fast']
3: Negative weights push towards "neg", most negative first. // vars: negative=['late', 'bad', 'cold']
4: The model's "reasons", in plain words. If a word like "pune" showed up here, you'd suspect a data problem. // out: ['tasty', 'fast'] ['late', 'bad', 'cold']
::end

## Transformers with Hugging Face

Pretrained transformer models have read billions of sentences, so they understand context, negation and tone. The `transformers` library runs them in three lines:

```python
from transformers import pipeline

sentiment = pipeline("sentiment-analysis")       # downloads a small default English model
print(sentiment(["not bad at all", "the food was cold and the delivery late"]))
# e.g. [{'label': 'POSITIVE', 'score': 0.99...}, {'label': 'NEGATIVE', 'score': 0.99...}]
```

For Indian languages and Hinglish, pick a multilingual model from the Hugging Face Hub (search "sentiment hindi" or "multilingual sentiment") and test it on your own examples. You can also **fine-tune** a model on your labelled reviews (Gen AI track: Fine-tuning module) or simply ask an LLM to classify with a strict output format.

## Choosing an approach

| Approach | Speed & cost | Accuracy | Needs |
|---|---|---|---|
| TF-IDF + logistic regression | Very fast, free | Good on clear text | A few hundred labelled examples |
| Pretrained transformer | Moderate (GPU helps) | Very good, handles context | Little or no labelled data |
| Fine-tuned transformer | Training cost once | Best on your domain | A few thousand labelled examples |
| LLM API with a prompt | Pay per call | Very good, flexible labels | No training; watch cost at scale |

```python
reviews = 50_000
per_review_tokens = 120
print("tokens to classify all reviews with an LLM:", f"{reviews * per_review_tokens:,}")
```

Output:

```text
tokens to classify all reviews with an LLM: 6,000,000
```

Use the free AI API cost calculator to price that before you choose.

::tip Beyond positive/negative, **aspect-based sentiment** asks "positive about what?": taste positive, delivery negative. That's far more useful to a business than one overall score.

## Summary

- TF-IDF + logistic regression: a fast, explainable sentiment baseline.
- It struggles with negation and context; inspect its top words.
- Pretrained transformers (Hugging Face `pipeline`) understand context with no training.
- Choose by data, accuracy needs, speed and cost; consider aspect-based sentiment.

🎉 You've finished **NLP: Working with Text**.
