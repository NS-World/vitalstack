---
title: "Streaming Responses: Show Answers Word by Word"
slug: llm-apis-streaming
subject: llm-apis
lesson: 4
difficulty: intermediate
excerpt: Why chat apps stream answers, how to stream with the Claude, OpenAI and Gemini SDKs, and how to build and test a streaming loop with a Python generator, no API key needed.
---

A long answer can take 20–30 seconds to generate. If your app waits for the whole thing, users stare at a spinner and leave. **Streaming** sends each piece of text as soon as the model writes it, so the first words appear in about a second. It also avoids timeouts on very long answers.

::scene Waiting vs streaming
⏳ | Without streaming | The user waits 25 seconds looking at a spinner, then a wall of text appears at once. | byte
🌊 | With streaming | The first words arrive in about a second and keep flowing, like someone typing. | chip
🙂 | Same answer | Same model, same cost, same total time. It just feels far faster. | dev
::end

## Streaming with the SDKs

```python
import anthropic

client = anthropic.Anthropic()
with client.messages.stream(
    model="claude-opus-5-5",
    max_tokens=4000,
    messages=[{"role": "user", "content": "Write a 5-line poem about monsoon in Mumbai."}],
) as stream:
    for text in stream.text_stream:
        print(text, end="", flush=True)
    final = stream.get_final_message()      # the full message, with usage
print("\ntokens:", final.usage.output_tokens)
```

```python
from openai import OpenAI

client = OpenAI()
stream = client.responses.create(model="MODEL_NAME", input="Write a 5-line poem.", stream=True)
for event in stream:
    if event.type == "response.output_text.delta":
        print(event.delta, end="", flush=True)
```

```python
from google import genai

client = genai.Client()
for chunk in client.models.generate_content_stream(model="MODEL_NAME", contents="Write a 5-line poem."):
    print(chunk.text, end="", flush=True)
```

`flush=True` makes Python print immediately instead of buffering. In a web app you would send each piece to the browser instead (Server-Sent Events or WebSockets; Streamlit's `st.write_stream` does this for you, see the project module).

## Build a streaming loop you can test for free

A stream is just a sequence of small pieces. In Python, a **generator** (a function with `yield`) is the perfect fake:

```python
import time

def fake_stream(text, delay=0.0):
    for word in text.split(" "):
        time.sleep(delay)          # a real model takes time per token
        yield word + " "

def show(stream):
    parts = []
    for piece in stream:
        parts.append(piece)
        print(piece, end="", flush=True)
    print()
    return "".join(parts).strip()

full = show(fake_stream("Rain taps the window, chai steams, the city slows down."))
print("words:", len(full.split()))
```

Output:

```text
Rain taps the window, chai steams, the city slows down.
words: 10
```

Your display code (`show`) doesn't care whether the stream comes from a real model or the fake one. That's how you unit-test streaming UIs.

## Watch a generator hand out pieces

::walk python | yield: one piece at a time
def pieces():
    yield "Hello"
    yield " world"
gen = pieces()
first = next(gen)
second = next(gen)
print(first + second)
::notes
1: A function with **yield** is a generator: calling it doesn't run it yet. // deep: Python pauses the function at each yield and resumes from the same spot on the next request. That's exactly how SDKs hand you stream chunks.
4: Calling pieces() returns a generator object; no code inside has run yet. // vars: gen=<generator>
5: **next()** runs until the first yield and hands back "Hello". The function pauses there. // vars: first=Hello
6: The next call resumes after the first yield and returns " world". // vars: second= world
7: Glue the pieces together, just like building the full answer from a stream. // out: Hello world
::end

## Handling the end of a stream

Always check **why** the stream ended. If it stopped because it hit `max_tokens`, the answer is cut off mid-sentence: tell the user, or continue.

```python
def finish_message(stop_reason):
    return {
        "end_turn": "Done.",
        "max_tokens": "The answer was cut off. Ask me to continue.",
        "refusal": "I can't help with that one.",
    }.get(stop_reason, "Stopped: " + stop_reason)

for reason in ("end_turn", "max_tokens", "tool_use"):
    print(reason, "->", finish_message(reason))
```

Output:

```text
end_turn -> Done.
max_tokens -> The answer was cut off. Ask me to continue.
tool_use -> Stopped: tool_use
```

Claude reports this as `stop_reason`; OpenAI-style APIs as `finish_reason` (`stop`, `length`…); Gemini as a finish reason on each candidate.

## Summary

- Streaming shows the first words in about a second; same answer and cost.
- Claude: `client.messages.stream(...)` + `text_stream`; OpenAI: `stream=True`; Gemini: `generate_content_stream`.
- Fake streams with a **generator** to test your UI for free.
- Always check why the stream stopped (complete, cut off, refused).
