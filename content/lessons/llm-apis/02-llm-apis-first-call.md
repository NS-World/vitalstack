---
title: "Your First API Call in Python: Claude, OpenAI, Gemini and Grok"
slug: llm-apis-first-call
subject: llm-apis
lesson: 2
difficulty: intermediate
excerpt: The minimal Python code to call Claude, OpenAI's GPT models, Google Gemini and xAI Grok, what comes back, and how to wrap them in one function you can test for free with a fake client.
---

Every LLM API works the same way underneath: your program sends an **HTTPS request** with your key, a model name and your messages; the provider runs the model and sends back the answer plus **usage** (how many tokens you were billed for).

::scene What one API call does
🐍 | Your code | Your Python script builds a request: model name, your message, and a limit on answer length. | dev
🔑 | Authenticate | The SDK adds your API key from the environment to the request headers. | bugsy
🌍 | Travel | The request goes over HTTPS to the provider's servers. | byte
🧠 | Think | The model reads your message and generates an answer, token by token. | chip
📦 | Response | You get back JSON: the answer text, why it stopped, and how many tokens were used. | dev
::end

## The four providers, side by side

Each SDK reads its key from the environment variable set up in lesson 1. **Model names change often**, so copy the current one from each provider's "models" documentation page. The names below are examples.

### Claude (Anthropic)

```python
import anthropic

client = anthropic.Anthropic()          # reads ANTHROPIC_API_KEY
response = client.messages.create(
    model="claude-opus-5-5",            # or a smaller, cheaper model such as claude-haiku-5-5
    max_tokens=1000,
    messages=[{"role": "user", "content": "Explain recursion in one sentence."}],
)
for block in response.content:
    if block.type == "text":
        print(block.text)
print(response.usage.input_tokens, response.usage.output_tokens)
```

### OpenAI (GPT models)

```python
from openai import OpenAI

client = OpenAI()                       # reads OPENAI_API_KEY
response = client.responses.create(
    model="MODEL_NAME",                 # copy a current model name from OpenAI's docs
    input="Explain recursion in one sentence.",
)
print(response.output_text)
```

### Google Gemini

```python
from google import genai

client = genai.Client()                 # reads GEMINI_API_KEY
response = client.models.generate_content(
    model="MODEL_NAME",                 # e.g. a current "flash" model for the free tier
    contents="Explain recursion in one sentence.",
)
print(response.text)
```

### xAI Grok

xAI's API is **OpenAI-compatible**: you use the `openai` package with a different address.

```python
import os
from openai import OpenAI

client = OpenAI(api_key=os.environ["XAI_API_KEY"], base_url="https://api.x.ai/v1")
response = client.chat.completions.create(
    model="MODEL_NAME",                 # copy a current Grok model name from xAI's docs
    messages=[{"role": "user", "content": "Explain recursion in one sentence."}],
)
print(response.choices[0].message.content)
```

::tip Many tools (Ollama for local models, several inference hosts) also offer OpenAI-compatible endpoints. Learn that request shape once and you can talk to dozens of models by changing `base_url` and `model`.

## What's in a response

All providers return roughly the same pieces, with different field names:

| Piece | Claude | OpenAI (Responses API) | Gemini | Grok (OpenAI-style) |
|---|---|---|---|---|
| Answer text | `content` blocks of type `text` | `output_text` | `text` | `choices[0].message.content` |
| Why it stopped | `stop_reason` | `status` | candidates' finish reason | `choices[0].finish_reason` |
| Tokens billed | `usage.input_tokens` / `output_tokens` | `usage` | `usage_metadata` | `usage` |

## One function for every provider

Real apps hide the provider behind **one function**, so switching models is a one-line change and the rest of the app can be tested without a key. First, a fake client with the same shape as a real one:

```python
class FakeLLM:
    """Pretends to be an LLM API: same inputs, canned answers, zero cost."""
    def __init__(self, answers):
        self.answers = answers
        self.calls = []

    def complete(self, system, messages, max_tokens=500):
        self.calls.append(messages[-1]["content"])
        text = self.answers.get(messages[-1]["content"], "I don't know.")
        tokens_in = sum(len(m["content"].split()) for m in messages)
        return {"text": text, "input_tokens": tokens_in, "output_tokens": len(text.split())}

def ask(llm, question, system="You are a helpful tutor."):
    reply = llm.complete(system, [{"role": "user", "content": question}])
    return reply["text"]

fake = FakeLLM({"What is 2+2?": "4", "Capital of India?": "New Delhi"})
print(ask(fake, "What is 2+2?"))
print(ask(fake, "Capital of India?"))
print(ask(fake, "Meaning of life?"))
print("calls made:", len(fake.calls))
```

Output:

```text
4
New Delhi
I don't know.
calls made: 3
```

To go live, write a small class with the same `complete()` method that calls a real SDK, for example Claude:

```python
import anthropic

class ClaudeLLM:
    def __init__(self, model="claude-opus-5-5"):
        self.client = anthropic.Anthropic()
        self.model = model

    def complete(self, system, messages, max_tokens=500):
        r = self.client.messages.create(model=self.model, max_tokens=max_tokens,
                                        system=system, messages=messages)
        text = "".join(b.text for b in r.content if b.type == "text")
        return {"text": text, "input_tokens": r.usage.input_tokens,
                "output_tokens": r.usage.output_tokens}

# ask(ClaudeLLM(), "Explain recursion in one sentence.")
```

`ask()` doesn't change at all. This pattern (an interface plus swappable implementations) is how professional AI apps support several providers and stay testable.

## Watch the fake client work

::walk python | A free, fake API call
answers = {"Hi": "Hello! How can I help?"}
messages = [{"role": "user", "content": "Hi"}]
question = messages[-1]["content"]
text = answers.get(question, "I don't know.")
usage = {"in": len(question.split()), "out": len(text.split())}
print(text, usage)
::notes
1: Canned answers stand in for the model. No network, no key, no cost. // vars: answers={'Hi': 'Hello! How can I help?'}
2: The same **messages** list shape that real APIs use: a role and the content. // vars: messages=[{'role': 'user', 'content': 'Hi'}]
3: The latest user message is always the last item, **messages[-1]**. // vars: question=Hi
4: Look up the reply, with a safe default if we have none. // deep: dict.get(key, default) never raises KeyError, so an unexpected question doesn't crash your test. // vars: text=Hello! How can I help?
5: Count "tokens" (here, just words) like a real API reports usage. // vars: usage={'in': 1, 'out': 5}
6: Same shape as a real reply: text plus usage. // out: Hello! How can I help? {'in': 1, 'out': 5}
::end

## Summary

- An API call = key + model + messages → answer + stop reason + token usage.
- Claude: `anthropic`; OpenAI: `openai`; Gemini: `google-genai`; Grok: `openai` with xAI's `base_url`.
- Always copy current model names from the provider's docs.
- Wrap providers behind one function and test with a **fake client**, so most of your code runs free.
