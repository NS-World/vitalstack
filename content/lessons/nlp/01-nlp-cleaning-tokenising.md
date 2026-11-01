---
title: "NLP Basics: Cleaning and Tokenising Text"
slug: nlp-cleaning-tokenising
subject: nlp
lesson: 1
difficulty: intermediate
excerpt: Prepare text for machine learning: lowercasing, removing noise, tokenisation, stop words, stemming vs lemmatisation, n-grams, and handling Hinglish and emojis, with runnable Python.
---

Customer reviews, tweets, support tickets, WhatsApp feedback: most of the world's data is text. **Natural Language Processing (NLP)** turns it into something a model can learn from. The first step is always cleaning and splitting the text.

::scene From messy review to clean tokens
💬 | Raw | "Delivery was SOOO late!!! 😡 Food cold... not ordering again #fail" | bugsy
🧹 | Clean | Lowercase, strip the hashtag and extra punctuation, keep the meaning. | chip
✂️ | Tokenise | Split into words: delivery, was, so, late, food, cold, not, ordering, again. | dev
🌱 | Normalise | "ordering" → "order": one form per word, so counts add up. | byte
::end

## Cleaning

```python
import re

review = "Delivery was SOOO late!!! 😡 Food cold... not ordering again #fail https://t.co/x1"

def clean(text):
    text = text.lower()
    text = re.sub(r"https?://\S+", " ", text)          # links
    text = re.sub(r"#(\w+)", r"\1", text)              # keep hashtag word, drop #
    text = re.sub(r"(.)\1{2,}", r"\1", text)            # sooo -> so
    text = re.sub(r"[^a-z0-9\s']", " ", text)           # punctuation and emojis
    return " ".join(text.split())

print(clean(review))
```

Output:

```text
delivery was so late food cold not ordering again fail
```

::tip Don't remove words blindly. "not" is usually in stop-word lists, but deleting it flips "not good" into "good". For sentiment, keep negations. Emojis can be valuable signals too: you can map 😡 to a word like "angry" instead of deleting it.

## Tokenisation and stop words

```python
STOP = {"was", "the", "a", "an", "is", "and", "to", "again"}
tokens = clean(review).split()
content = [t for t in tokens if t not in STOP]
print(tokens)
print(content)
```

Output:

```text
['delivery', 'was', 'so', 'late', 'food', 'cold', 'not', 'ordering', 'again', 'fail']
['delivery', 'so', 'late', 'food', 'cold', 'not', 'ordering', 'fail']
```

Libraries like **NLTK** and **spaCy** provide proper tokenisers (handling "don't", "Dr.", "₹1,200") and stop-word lists for many languages.

## Stemming vs lemmatisation

::walk python | A tiny suffix-stripping stemmer
words = ["ordering", "ordered", "orders", "delivery", "studies"]
suffixes = ("ing", "ed", "s")
stems = []
for w in words:
    stems.append(next((w[:-len(s)] for s in suffixes if w.endswith(s) and len(w) > len(s) + 2), w))
print(stems)
::notes
1: Words that mean nearly the same thing should count as one. // vars: words=['ordering', 'ordered', 'orders', 'delivery', 'studies']
2: Common English endings to chop off. // vars: suffixes=('ing', 'ed', 's')
3: Collect the results.
4: For each word…
5: …remove the first matching suffix, if the word is long enough. // deep: Real stemmers like Porter have dozens of rules. Lemmatisers (spaCy, WordNet) use a dictionary and grammar instead, so "studies" becomes "study" and "better" becomes "good". Stemmers are fast but crude; lemmatisers are slower but give real words. // vars: stems=['order', 'order', 'order', 'delivery', 'studie']
6: "order" ×3 now count together; "studie" shows why crude stemming can produce non-words. // out: ['order', 'order', 'order', 'delivery', 'studie']
::end

## N-grams: keep a little word order

Single words lose order: "not good" and "good, not bad" look similar. **Bigrams** (pairs of neighbouring words) keep some of it.

```python
tokens = "food was not good".split()
bigrams = [" ".join(pair) for pair in zip(tokens, tokens[1:])]
print(bigrams)
```

Output:

```text
['food was', 'was not', 'not good']
```

"not good" is now a feature of its own.

## Indian-language realities

- **Hinglish / code-mixing:** "khana bahut late aaya, not happy". Models need examples in this style; multilingual models (like IndicBERT or MuRIL, and modern LLMs) handle it far better than English-only tools.
- **Scripts:** Devanagari, Tamil, Bengali text needs Unicode-aware tokenisers.
- **Spelling variety:** "bahut", "bohot", "bahot". Normalising common variants helps.

## Summary

- Clean carefully: lowercase, remove links and noise, but keep meaning (negations, useful emojis).
- Tokenise into words; remove stop words only when it helps the task.
- Stemming chops suffixes (fast, crude); lemmatisation finds dictionary forms (accurate).
- N-grams keep short-range word order; code-mixed Indian text needs multilingual tools.
