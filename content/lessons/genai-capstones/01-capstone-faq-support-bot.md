---
title: "Capstone: Customer Support Chatbot over a Company FAQ"
slug: capstone-faq-support-bot
subject: genai-capstones
lesson: 1
difficulty: intermediate
excerpt: Build a portfolio-ready support bot for an online shop: find the right FAQ, answer only from it, hand off to a human when unsure, measure accuracy with a test set, and see exactly where word matching fails.
---

This capstone builds the most requested AI feature in business: a support bot that answers customer questions **from the company's own FAQ**, says "let me connect you to a person" when it isn't sure, and never invents policies. You'll build the retrieval yourself, so you understand every piece.

::scene The shop that never sleeps
🛍️ | The shop | It's 2 a.m. A customer asks: "How many days for delivery?" | dev
🔎 | Find | Chip searches the FAQ and finds the delivery answer, with a confidence score. | chip
💬 | Answer | Byte writes a friendly reply using only that FAQ text. | byte
🙋 | Hand off | "Do you sell gift cards?" isn't in the FAQ. Bugsy's rule: low confidence → a human follows up. | bugsy
::end

## The plan

```
question → find the best FAQ (with a score) → score high? → LLM answers from that FAQ
                                             → score low?  → polite hand-off + support ticket
```

## Step 1: find the best FAQ

We start with TF-IDF word matching (from the NLP module), which runs anywhere:

```python
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

FAQ = [
    ("How long does delivery take?", "Delivery takes 3-5 working days in metro cities and up to 7 days elsewhere."),
    ("What is the return policy?", "You can return unused items within 7 days of delivery for a full refund."),
    ("Do you offer cash on delivery?", "Yes, cash on delivery is available for orders under ₹10,000, with a ₹50 fee."),
    ("How do I track my order?", "Open My Orders and tap Track, or use the link in your SMS."),
    ("Can I change my delivery address?", "You can change the address before the order ships, from My Orders."),
]
docs = [q + " " + a for q, a in FAQ]
vec = TfidfVectorizer(stop_words="english").fit(docs)
M = vec.transform(docs)

def best_faq(question):
    sims = cosine_similarity(vec.transform([question]), M)[0]
    i = int(sims.argmax())
    return i, float(sims[i])

for q in ["how many days for delivery", "can I return shoes", "when will my parcel arrive", "do you sell gift cards"]:
    i, score = best_faq(q)
    print(f"{score:.2f} | {q:<28} | {FAQ[i][0] if score >= 0.2 else 'hand off to a human'}")
```

Output:

```text
0.63 | how many days for delivery   | How long does delivery take?
0.67 | can I return shoes           | What is the return policy?
0.00 | when will my parcel arrive   | hand off to a human
0.00 | do you sell gift cards       | hand off to a human
```

Two great matches and two hand-offs. "Gift cards" really isn't in the FAQ, so handing off is correct. But "when will my parcel arrive" **is** a delivery question: it just uses different words. Word matching can't see that "parcel arrive" means "delivery". Swap TF-IDF for **embeddings** (RAG module) and it matches by meaning. That upgrade is the first improvement in your portfolio write-up.

## Step 2: the hand-off rule

::walk python | Deciding: answer or hand off
score = 0.31
THRESHOLD = 0.2
angry = "worst" in "this is the worst service".lower()
action = "human" if score < THRESHOLD or angry else "bot"
print(action)
::notes
1: The retrieval score for the best FAQ. // vars: score=0.31
2: Below this, we don't trust the match. Tune it on your test set (step 4). // vars: THRESHOLD=0.2
3: Some messages need a person whatever the score: angry customers, legal threats, payment failures. // deep: A cheap sentiment or keyword check (or a small classifier call) can route these. Fast hand-off for upset customers is one of the biggest drivers of good support ratings. // vars: angry=True
4: Hand off if the match is weak OR the customer is upset.
5: The score was fine, but the customer is angry, so a human takes over. // out: human
::end

## Step 3: answer only from the FAQ

```python
import anthropic

client = anthropic.Anthropic()
SYSTEM = ("You are the support assistant for an online shop. Answer ONLY using the FAQ entry inside <faq> tags. "
          "If it doesn't answer the question, say you'll connect the customer to a team member. "
          "Be warm and brief (max 3 sentences). Never invent policies, prices or dates.")

def reply(question):
    i, score = best_faq(question)
    if score < 0.2:
        return "I'm not sure about that one. I've passed your question to our team, and they'll reply within 4 hours."
    q, a = FAQ[i]
    r = client.messages.create(model="claude-opus-5-5", max_tokens=200, system=SYSTEM,
                               messages=[{"role": "user", "content": f"<faq>{q} {a}</faq>\n\nCustomer: {question}"}])
    return r.content[0].text
```

## Step 4: measure it

A test set of real-looking questions with the FAQ that should answer them (or `None` for "hand off"):

```python
tests = [("delivery time to Pune?", 0), ("return policy for shoes", 1), ("is COD available", 2),
         ("track my order", 3), ("change address after ordering", 4), ("do you have a store in Delhi", None)]

correct = 0
for question, want in tests:
    i, score = best_faq(question)
    got = i if score >= 0.2 else None
    correct += got == want
print(f"routing accuracy: {correct}/{len(tests)}")
```

Output:

```text
routing accuracy: 6/6
```

Run this after every change (new threshold, embeddings, more FAQs). It tells you if you improved or broke something.

## Make it portfolio-ready

- **Upgrade:** embeddings + a vector store; the FAQ loaded from a CSV the support team edits.
- **UI:** a Streamlit chat page or a website widget (Deploying AI Apps module).
- **Safety:** redact phone numbers and emails before logging; refuse instructions hidden in messages.
- **Metrics:** % answered by the bot, % handed off, thumbs up/down per answer.
- **Write-up:** a short README with the architecture diagram, your test accuracy before and after embeddings, and a 30-second demo GIF.

::tip Recruiters love numbers. "Answered 72% of test questions automatically with 0 invented policies" is far stronger than "built a chatbot".

## Summary

- Retrieve the best FAQ, answer only from it, and hand off when the score is low or the customer is upset.
- Word matching misses synonyms; embeddings fix that.
- A small test set gives you an accuracy number to improve and report.
