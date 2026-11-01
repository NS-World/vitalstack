---
title: "LangChain: Chains, Prompt Templates and Output Parsers"
slug: langchain-chains-prompts-parsers
subject: langchain
lesson: 1
difficulty: intermediate
excerpt: What LangChain is for, prompt templates, chat models, output parsers and composing them into chains with the pipe (|) syntax, with a tiny runnable re-implementation so you see what happens inside.
---

LangChain is the most widely used framework for building LLM apps in Python and JavaScript. It gives you building blocks (prompts, models, parsers, retrievers, tools) that snap together. Knowing it helps with job interviews and with reading other people's AI code, even if you sometimes choose plain SDK calls instead.

::scene Snap-together AI
🧱 | Blocks | A prompt template, a chat model and an output parser are three separate blocks. | dev
🔗 | Chain | The pipe symbol joins them: prompt | model | parser. Input flows left to right. | chip
🔁 | Swap | Change the model block from GPT to Claude or Gemini, and nothing else changes. | byte
📦 | Reuse | The chain becomes one object you can call, batch, stream or plug into a bigger app. | bugsy
::end

## A real LangChain chain

```python
from langchain_core.prompts import ChatPromptTemplate
from langchain_core.output_parsers import StrOutputParser
from langchain_anthropic import ChatAnthropic          # or: from langchain_openai import ChatOpenAI

prompt = ChatPromptTemplate.from_messages([
    ("system", "You explain {topic} to {audience} in 3 short bullet points."),
    ("user", "{question}"),
])
model = ChatAnthropic(model="claude-opus-5-5", max_tokens=500)   # reads ANTHROPIC_API_KEY
chain = prompt | model | StrOutputParser()

print(chain.invoke({"topic": "UPI", "audience": "school students", "question": "How does UPI work?"}))
```

Install with `pip install langchain-core langchain-anthropic` (or `langchain-openai`, `langchain-google-genai`). LangChain changes quickly, so check its docs for current package names.

## What `|` actually does (runnable)

Each block is a **Runnable** with an `invoke` method; `|` creates a new Runnable that calls them in order. Here's the idea in 20 lines:

```python
class Runnable:
    def __init__(self, fn):
        self.fn = fn
    def invoke(self, x):
        return self.fn(x)
    def __or__(self, other):
        return Runnable(lambda x: other.invoke(self.invoke(x)))

prompt = Runnable(lambda v: f"Explain {v['topic']} to {v['audience']} in one line.")
fake_model = Runnable(lambda text: {"content": f"(model reply to: {text})"})
parser = Runnable(lambda msg: msg["content"].upper())

chain = prompt | fake_model | parser
print(chain.invoke({"topic": "UPI", "audience": "kids"}))
```

Output:

```text
(MODEL REPLY TO: EXPLAIN UPI TO KIDS IN ONE LINE.)
```

::walk python | Data flowing through prompt | model | parser
values = {"topic": "UPI", "audience": "kids"}
text = f"Explain {values['topic']} to {values['audience']}."
message = {"role": "assistant", "content": "UPI sends money instantly."}
answer = message["content"]
print(answer)
::notes
1: The chain's input: a dictionary of template variables. // vars: values={'topic': 'UPI', 'audience': 'kids'}
2: **Prompt template:** fills the variables into text (really, a list of chat messages). // vars: text=Explain UPI to kids.
3: **Chat model:** sends it to the LLM and returns a message object. // deep: In LangChain this is an AIMessage with .content, plus metadata like token usage. Swapping providers only changes this block. // vars: message={'role': 'assistant', 'content': 'UPI sends money instantly.'}
4: **Output parser:** turns the message into what your code wants: plain text here, or JSON or a Pydantic object. // vars: answer=UPI sends money instantly.
5: Three small steps, one reusable chain. // out: UPI sends money instantly.
::end

## Structured output

```python
from pydantic import BaseModel

class Flashcard(BaseModel):
    front: str
    back: str

structured = model.with_structured_output(Flashcard)
card = structured.invoke("Make a flashcard about Python lists.")
print(card.front, "->", card.back)
```

`with_structured_output` uses the provider's native tool or JSON features (see *Working with LLM APIs*, lesson 5).

## Useful Runnable features

| Method | Does |
|---|---|
| `chain.invoke(x)` | One input → one output |
| `chain.batch([x1, x2])` | Many inputs, in parallel |
| `chain.stream(x)` | Stream tokens as they arrive |
| `RunnableParallel` | Run several chains on the same input at once |
| `.with_retry()` / `.with_fallbacks([...])` | Retry, or fall back to another model |

## When to use LangChain, and when not to

- **Use it** when you combine many parts (retrievers, tools, memory, several models) or want its integrations with hundreds of services.
- **Skip it** for simple apps: a direct SDK call is fewer moving parts and easier to debug. Many teams prototype with LangChain and keep only what they need.

::tip LangChain's abstractions hide the actual prompt. When something goes wrong, print or trace the final messages sent to the model (LangSmith, or `set_debug(True)`): most bugs become obvious once you see the real prompt.

## Summary

- LangChain = composable building blocks (Runnables) for LLM apps.
- `prompt | model | parser` builds a chain; `|` calls each block in turn.
- Output parsers and `with_structured_output` give text, JSON or Pydantic objects.
- Great for complex apps and integrations; plain SDK calls are fine for simple ones.
