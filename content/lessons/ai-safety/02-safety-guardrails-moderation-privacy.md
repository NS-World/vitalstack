---
title: "Guardrails, Moderation and Privacy"
slug: safety-guardrails-moderation-privacy
subject: ai-safety
lesson: 2
difficulty: intermediate
excerpt: Put guardrails around an LLM app: input and output checks, keeping the bot on topic, moderation for harmful content, redacting personal data (emails, phone numbers, PAN, Aadhaar) before it reaches a model, and privacy basics under India's DPDP Act.
---

An LLM is a powerful engine. **Guardrails** are the brakes, mirrors and seat belts around it: checks that run before the model sees the input and after it writes the output. They keep a shopping bot from giving medical advice, stop personal data from leaking into logs, and catch promises your business can't keep.

::scene The bank helpdesk bot
🏦 | The bot | Chip answers questions for a small bank's customers. | chip
🧾 | Before | A customer pastes their PAN and phone number. Byte masks them before the model sees anything. | byte
🙅 | Off topic | "Which stock should I buy?" Chip politely says it can't give investment advice. | bugsy
✅ | After | Dev's output check blocks a reply that promised a refund nobody approved. | dev
::end

## Where guardrails sit

```
user input → [input guardrails] → model → [output guardrails] → user
```

| Input guardrails | Output guardrails |
|---|---|
| Redact personal data | Block unapproved promises (refunds, discounts) |
| Block off-topic or harmful requests | Check format (valid JSON, length) |
| Flag prompt injection signals | Remove links and emails you didn't provide |
| Rate-limit each user | Moderate for harmful content |

## Redacting personal data

Send the model only what it needs. Mask personal details first:

```python
import re

RULES = [("EMAIL", r"[\w.+-]+@[\w-]+\.[\w.]+"),
         ("PHONE", r"(?:\+91[\s-]?)?\b[6-9]\d{9}\b"),
         ("PAN", r"\b[A-Z]{5}\d{4}[A-Z]\b"),
         ("AADHAAR", r"\b\d{4}\s?\d{4}\s?\d{4}\b")]

def redact(text):
    found = {}
    for label, pattern in RULES:
        text, n = re.subn(pattern, f"[{label}]", text)
        if n:
            found[label] = n
    return text, found

msg = "I'm Asha, asha.k@example.com, call 9876543210. PAN ABCDE1234F, Aadhaar 1234 5678 9012."
clean, found = redact(msg)
print(clean)
print(found)
```

Output:

```text
I'm Asha, [EMAIL], call [PHONE]. PAN [PAN], Aadhaar [AADHAAR].
{'EMAIL': 1, 'PHONE': 1, 'PAN': 1, 'AADHAAR': 1}
```

The model can still help ("I've noted your details"), but the raw numbers never leave your server or land in a provider's logs. Regex catches common formats only; production systems often add a dedicated PII-detection model.

## Keeping the bot on topic

A short system prompt plus a cheap classifier call works well:

```python
import anthropic

client = anthropic.Anthropic()
def on_topic(question):
    reply = client.messages.create(
        model="claude-opus-5-5", max_tokens=5,
        system="Classify the user message for a bank helpdesk. Reply with exactly ALLOWED or OFF_TOPIC. "
               "Account, card, loan and app questions are ALLOWED. Investment tips, medical or legal advice are OFF_TOPIC.",
        messages=[{"role": "user", "content": question}])
    return reply.content[0].text.strip() == "ALLOWED"
```

For harmful content (hate, self-harm, violence), use a **moderation** step: some providers offer a free moderation endpoint, and there are open safety classifier models you can host yourself. For self-harm topics, always reply kindly and show a helpline instead of a refusal.

## Output guardrails

::walk python | Blocking a promise the bot can't make
answer = {"reply": "Your refund is approved!", "promised_refund": True}
can_promise = False
too_long = len(answer["reply"]) > 300
blocked = answer["promised_refund"] and not can_promise
print(blocked, too_long)
::notes
1: Ask the model for structured output, including a flag for whether it promised a refund. // vars: answer=reply + promised_refund=True
2: Your business rule: the bot is never allowed to approve refunds by itself. // vars: can_promise=False
3: A simple format check: replies over 300 characters are too long for a chat bubble. // vars: too_long=False
4: The real guardrail: a promise the bot isn't allowed to make. // deep: Rules like this live in your code, not just the prompt. A prompt can be ignored or injected; an if statement can't. // vars: blocked=True
5: Blocked. Replace the reply with "I've passed your refund request to our team; you'll hear back within 2 days." // out: True False
::end

## Privacy basics for Indian apps

India's **Digital Personal Data Protection (DPDP) Act, 2023** applies to apps that handle personal data of people in India. In simple terms:

- Tell users what data you collect and why, and get clear consent.
- Collect only what you need, and delete it when you're done.
- Keep it secure, and let users ask for correction or deletion.
- Check where your AI provider stores data and whether it trains on your inputs (most business API plans don't by default; read the terms).

This is a simple overview, not legal advice. For a real product, check the current rules with a lawyer.

::tip Log redacted text, not raw text. Debug logs are one of the most common places personal data leaks from AI apps.

## Summary

- Guardrails are code checks before and after the model.
- Redact personal data (email, phone, PAN, Aadhaar) before sending it to a model or a log.
- Keep bots on topic with a system prompt and a cheap classifier; moderate harmful content.
- Enforce business rules in code, and follow privacy basics like consent and minimal data.
