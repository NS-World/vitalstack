---
title: "An AI API with FastAPI"
slug: deploy-fastapi-ai-api
subject: deploy-ai-apps
lesson: 2
difficulty: intermediate
excerpt: Wrap your AI feature in a real web API with FastAPI: request and response models, validation errors, an API key header, error handling, a health check, caching repeated questions, and streaming.
---

A Streamlit page is great for one app. But when a mobile app, a website and a WhatsApp bot all need the same AI feature, you build it once as an **API**: a URL other programs call with JSON. **FastAPI** is the most popular Python framework for this: fast, typed and with automatic interactive docs.

::scene One brain, many apps
🧠 | One API | Dev puts the tutor prompt behind POST /ask. | dev
📱 | Many callers | The website, the Android app and a bot all call the same URL. | chip
🚫 | Bad input | Bugsy sends an empty question. The API answers 422 with a clear message, without calling the model. | bugsy
⚡ | Cache | Byte notices 30% of questions repeat. Cached answers return instantly and cost nothing. | byte
::end

## The API

```python
import os
import secrets
from typing import Literal

import anthropic
from fastapi import FastAPI, Header, HTTPException
from pydantic import BaseModel, Field

app = FastAPI(title="Study Buddy API")
client = anthropic.Anthropic()

class Ask(BaseModel):
    question: str = Field(min_length=3, max_length=2000)
    level: Literal["beginner", "intermediate"] = "beginner"

class Answer(BaseModel):
    answer: str
    input_tokens: int
    output_tokens: int

@app.get("/health")
def health():
    return {"status": "ok"}

@app.post("/ask", response_model=Answer)
def ask(body: Ask, x_api_key: str = Header()):
    if not secrets.compare_digest(x_api_key, os.environ["APP_KEY"]):
        raise HTTPException(status_code=401, detail="Invalid API key")
    try:
        r = client.messages.create(model="claude-opus-5-5", max_tokens=800,
                                   system=f"Explain clearly for a {body.level} learner, with one example.",
                                   messages=[{"role": "user", "content": body.question}])
    except anthropic.APIError:
        raise HTTPException(status_code=502, detail="AI service unavailable, please retry")
    return Answer(answer=r.content[0].text, input_tokens=r.usage.input_tokens,
                  output_tokens=r.usage.output_tokens)
```

Run it with `uvicorn main:app --reload` and open `http://localhost:8000/docs`: FastAPI builds a page where you can try every endpoint. Callers send the key in an `x-api-key` header.

## What validation does for you

Pydantic checks every request before your code runs. Here's the same idea by hand, so you can see the logic:

::walk python | Validating a request body
body = {"question": "  ", "level": "expert"}
q = body.get("question", "").strip()
errors = []
if not q: errors.append("question is empty")
if body.get("level") not in ("beginner", "intermediate"): errors.append("level must be beginner or intermediate")
print(422 if errors else 200, errors)
::notes
1: A bad request: a blank question and a level we don't support. // vars: body={'question': '  ', 'level': 'expert'}
2: Strip spaces so "   " counts as empty. // vars: q=
3: Collect every problem, not just the first, so the caller can fix them all at once. // vars: errors=[]
4: Problem 1. // vars: errors=['question is empty']
5: Problem 2. // deep: In FastAPI, the Literal type in the Ask model does this automatically and lists the allowed values in the error. Validation also protects your wallet: bad requests never reach the paid model.
6: 422 means "I understood the request, but the data is invalid". // out: 422 ['question is empty', 'level must be beginner or intermediate']
::end

## Status codes your API should use

| Code | Meaning | When |
|---|---|---|
| 200 | OK | Answer returned |
| 401 | Unauthorised | Missing or wrong API key |
| 422 | Invalid data | Validation failed |
| 429 | Too many requests | The caller hit your rate limit |
| 502 | Bad gateway | The AI provider failed; tell the caller to retry |

## Cache repeated questions

Many users ask the same things. Normalise the question and cache the answer:

```python
import hashlib

cache = {}
calls = 0

def fake_llm(question):
    global calls
    calls += 1
    return f"Answer to: {question}"

def ask_cached(question):
    key = hashlib.sha256(question.strip().lower().encode()).hexdigest()[:12]
    if key in cache:
        return cache[key], "cache"
    cache[key] = fake_llm(question)
    return cache[key], "model"

for q in ["What is RAG?", "  what is rag?", "What is MCP?"]:
    answer, source = ask_cached(q)
    print(f"{source:<5} | {answer}")
print("model calls:", calls)
```

Output:

```text
model | Answer to: What is RAG?
cache | Answer to: What is RAG?
model | Answer to: What is MCP?
model calls: 2
```

Three questions, two paid calls. In production, use Redis with an expiry time, and don't cache personal or time-sensitive answers. Providers also offer **prompt caching** for long, repeated system prompts, which cuts cost on every call.

## Streaming from an API

For long answers, return text as it's generated with `StreamingResponse`:

```python
from fastapi.responses import StreamingResponse

@app.post("/ask/stream")
def ask_stream(body: Ask):
    def gen():
        with client.messages.stream(model="claude-opus-5-5", max_tokens=800,
                                    messages=[{"role": "user", "content": body.question}]) as s:
            yield from s.text_stream
    return StreamingResponse(gen(), media_type="text/plain")
```

::tip Return token counts (like `input_tokens` above) in every response and log them. When your bill grows, you'll know exactly which endpoint and which customer caused it.

## Summary

- FastAPI + Pydantic give you typed requests, automatic validation and docs at `/docs`.
- Protect the endpoint with an API key, and return clear status codes.
- Catch provider errors and return 502 instead of crashing.
- Cache repeated questions, and stream long answers.
