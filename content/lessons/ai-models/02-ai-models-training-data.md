---
title: "Training Data: Where AI Models Learn From (and How It Is Cleaned)"
slug: ai-models-training-data
subject: ai-models
lesson: 2
difficulty: beginner
excerpt: What text are ChatGPT, Claude and Gemini trained on, why most of it is thrown away, and how engineers remove duplicates, spam and personal data. With runnable Python.
---

"Garbage in, garbage out" is the oldest rule in computing, and it matters more for AI than anywhere else. The single biggest difference between a weak model and a strong one is often not the code but the **data**.

## Where the text comes from

| Source | Why it helps | Typical problems |
|---|---|---|
| Public web pages | Huge variety of topics and writing styles | Spam, ads, copies, low quality, personal data |
| Books and long articles | Long, well-structured reasoning | Copyright: needs licences or permission |
| Encyclopedias | Facts, clear definitions | Small compared with the web |
| Public code | Programming skills and step-by-step logic | Licences, secrets accidentally committed |
| Research papers | Science, maths, careful arguments | Formulas and tables are hard to extract |
| Licensed / partner data | High quality, legal clarity | Expensive |
| Human-written examples | Teaches assistant behaviour (lesson 4) | Slow and costly to produce |

Companies publish only partial details of their data mixes, so treat any exact percentage you see online with caution. What every serious lab agrees on is the **pipeline**.

::scene The data cleaning factory
🌐 | Crawl | A crawler downloads billions of pages. Most are menus, ads and copies of each other. | byte
🧹 | Extract | Code strips the HTML, menus and cookie banners, keeping only the main text. | chip
🗣️ | Detect language | Each document is tagged with its language, so the mix can be balanced. | dev
👯 | Deduplicate | Exact and near-duplicate pages are removed. Repeated text makes a model memorise instead of learn. | bugsy
🚮 | Filter quality | Classifiers drop spam, gibberish, adult and hateful content. | byte
🔒 | Remove private data | Phone numbers, emails and ID numbers are detected and removed or masked. | chip
🧪 | Mix | The final blend of web, books, code and maths is chosen carefully. Then training begins. | dev
::end

## Removing duplicates

The same news story can appear on hundreds of sites. If the model reads it 300 times, it memorises that text instead of learning general patterns. The simplest fix is to fingerprint each document after **normalising** it (lower case, collapse spaces) and keep only the first copy:

```python
import hashlib

docs = [
    "Mumbai rains: local trains delayed.",
    "MUMBAI RAINS:  local trains delayed.",
    "New metro line opens in Pune.",
    "mumbai rains: local trains delayed.",
]

def fingerprint(text):
    clean = " ".join(text.lower().split())
    return hashlib.sha256(clean.encode()).hexdigest()[:12]

seen, kept = set(), []
for d in docs:
    fp = fingerprint(d)
    if fp not in seen:
        seen.add(fp)
        kept.append(d)

print(len(docs), "documents ->", len(kept), "kept")
for d in kept:
    print("-", d)
```

Output:

```text
4 documents -> 2 kept
- Mumbai rains: local trains delayed.
- New metro line opens in Pune.
```

Real pipelines also catch **near** duplicates (a page with one changed word) using techniques like MinHash, which compare overlapping word chunks instead of exact text.

## Watch the fingerprint, line by line

::walk python | Normalise, then fingerprint
text = "MUMBAI RAINS:  local trains delayed."
lower = text.lower()
words = lower.split()
clean = " ".join(words)
print(clean)
::notes
1: A copy of the headline in capitals with a double space. // vars: text=MUMBAI RAINS:  local trains delayed.
2: **lower()** makes every letter small, so "MUMBAI" and "Mumbai" look the same. // vars: lower=mumbai rains:  local trains delayed.
3: **split()** with no argument splits on any run of spaces, so the double space disappears. // deep: split() returns a list of words. Tabs and newlines also count as spaces. // vars: words=['mumbai', 'rains:', 'local', 'trains', 'delayed.']
4: Join the words back with single spaces. Now every copy of the headline becomes the identical string. // vars: clean=mumbai rains: local trains delayed.
5: This normalised text is what gets hashed into a fingerprint. // out: mumbai rains: local trains delayed.
::end

## A tiny quality filter

Quality filters use many signals. Here are three simple ones: too short, too many symbols, or too repetitive.

```python
def looks_good(text):
    words = text.split()
    if len(words) < 6:
        return False, "too short"
    letters = sum(c.isalpha() for c in text)
    if letters / len(text) < 0.6:
        return False, "too many symbols"
    if len(set(words)) / len(words) < 0.5:
        return False, "too repetitive"
    return True, "ok"

samples = [
    "Click here!!! $$$ ### @@@ >>> WIN",
    "buy buy buy buy buy buy buy buy now",
    "Photosynthesis lets plants turn sunlight, water and carbon dioxide into sugar.",
]
for s in samples:
    print(looks_good(s), "|", s[:40])
```

Output:

```text
(False, 'too many symbols') | Click here!!! $$$ ### @@@ >>> WIN
(False, 'too repetitive') | buy buy buy buy buy buy buy buy now
(True, 'ok') | Photosynthesis lets plants turn sunlight
```

Big labs replace rules like these with **classifier models** trained to tell high-quality text from low-quality text, but the idea is the same.

## Removing personal data

Training data must not teach a model someone's phone number. A simple version uses regular expressions:

```python
import re

text = "Call Priya on 98765 43210 or mail priya.k@example.com for notes."
text = re.sub(r"\b\d{5}\s?\d{5}\b", "[PHONE]", text)
text = re.sub(r"\b[\w.]+@[\w.]+\.\w+\b", "[EMAIL]", text)
print(text)
```

Output:

```text
Call Priya on [PHONE] or mail [EMAIL] for notes.
```

::tip This is also why you should never paste passwords, API keys or private customer data into public chatbots or public code repositories: anything published openly can end up in someone's dataset.

## Why the data mix matters

- More **code** in the mix tends to improve step-by-step reasoning, not just programming.
- More **maths** and science papers improve careful problem solving.
- More **languages** (Hindi, Tamil, Bengali…) help the model serve more people, but each needs enough clean text.
- Data with a **cut-off date** is why a model may not know recent events unless it can search the web.

## Summary

- Models learn from web text, books, code, papers, licensed data and human-written examples.
- Most collected text is **removed**: duplicates, spam, low quality and personal data.
- Deduplication = normalise, fingerprint, keep the first copy.
- The final data mix shapes what the model is good at.

Next: pretraining. You'll train a one-parameter "model" with gradient descent, the same idea that trains a model with billions of parameters.
