---
title: "Structured Output: Getting Reliable JSON from an LLM"
slug: llm-apis-structured-output
subject: llm-apis
lesson: 5
difficulty: intermediate
excerpt: Make LLMs return JSON your code can trust, using schemas, Pydantic and the providers' structured-output features, and validate everything with plain Python before you use it.
---

A chatbot can answer in free text. An **app** usually needs data: a list of quiz questions, a product category, a sentiment score. If the model replies "Sure! Here's your JSON:" followed by almost-JSON, your code crashes. **Structured output** solves this.

::scene From chatty text to clean data
🗯️ | The problem | You asked for JSON. The model answered "Sure! Here you go:" plus JSON with a missing bracket. | byte
📐 | A schema | You describe the exact shape you want: field names, types, which fields are required. | chip
🔒 | Constrained | The provider's structured-output mode makes the model follow that shape. | dev
✅ | Validate | Your code still checks the values before trusting them: right type, sensible range. | bugsy
::end

## Ask with a schema (Claude + Pydantic)

With the Anthropic SDK you can describe the shape as a **Pydantic** model; `messages.parse()` returns a validated object:

```python
from pydantic import BaseModel
import anthropic

class Quiz(BaseModel):
    question: str
    options: list[str]
    answer_index: int
    explanation: str

client = anthropic.Anthropic()
response = client.messages.parse(
    model="claude-opus-5-5",
    max_tokens=2000,
    messages=[{"role": "user", "content": "One multiple-choice question about Python lists."}],
    output_format=Quiz,
)
quiz = response.parsed_output        # a Quiz object
print(quiz.question, quiz.options[quiz.answer_index])
```

OpenAI and Gemini have equivalent features (a JSON schema or Pydantic model passed with the request; check each provider's "structured outputs" docs). The idea is the same everywhere: **describe the shape, let the API enforce it.**

## Always validate anyway

Structure doesn't guarantee *sense*. The model might return `answer_index: 7` for 4 options. Validate before using:

```python
import json

def parse_quiz(raw):
    data = json.loads(raw)
    errors = []
    for field in ("question", "options", "answer_index"):
        if field not in data:
            errors.append(f"missing {field}")
    if not errors:
        if not isinstance(data["options"], list) or len(data["options"]) < 2:
            errors.append("need at least 2 options")
        elif not 0 <= data["answer_index"] < len(data["options"]):
            errors.append("answer_index out of range")
    return data if not errors else errors

good = '{"question": "len([1,2,3])?", "options": ["2", "3", "4"], "answer_index": 1}'
bad = '{"question": "len([1,2])?", "options": ["1", "2"], "answer_index": 5}'
print(parse_quiz(good)["options"][1])
print(parse_quiz(bad))
```

Output:

```text
3
['answer_index out of range']
```

## Cleaning up "almost JSON"

If you're using a model or mode without structured output, extract the JSON part before parsing. This handles the common "here's your JSON" wrapper and code fences:

```python
import json, re

def extract_json(text):
    m = re.search(r"\{.*\}", text, re.S)       # from the first { to the last }
    if not m:
        raise ValueError("no JSON object found")
    return json.loads(m.group(0))

fence = "`" * 3                                # a Markdown code fence
reply = f'Sure! Here is your result:\n{fence}json\n{{"sentiment": "positive", "score": 0.92}}\n{fence}\nHope this helps!'
data = extract_json(reply)
print(data["sentiment"], data["score"])
```

Output:

```text
positive 0.92
```

## Validation, line by line

::walk python | Trust, but verify
import json
raw = '{"sentiment": "happy", "score": 1.4}'
data = json.loads(raw)
allowed = {"positive", "negative", "neutral"}
ok_label = data["sentiment"] in allowed
ok_score = 0 <= data["score"] <= 1
print(ok_label, ok_score)
::notes
1: json turns text into Python dicts and lists.
2: What the model sent. It's valid JSON, but look closely at the values. // vars: raw={"sentiment": "happy", "score": 1.4}
3: **json.loads** parses it without complaint: the syntax is fine. // vars: data={'sentiment': 'happy', 'score': 1.4}
4: Our app only understands three labels. // vars: allowed={'positive', 'negative', 'neutral'}
5: "happy" isn't one of them. // vars: ok_label=False
6: A score of 1.4 is outside 0–1. // vars: ok_score=False
7: Valid JSON, invalid data. This check is what stops a bad value from reaching your database. // out: False False
::end

## Tool use: structured output's big sibling

**Tool use** (also called function calling) is structured output aimed at *actions*: you describe a function and its parameters, and the model replies with the function name and JSON arguments. It's how agents work, and the Agentic AI Engineer track covers it in depth.

## Summary

- Apps need data, not chat: use the provider's **structured output** with a schema (Pydantic in Python).
- Always **validate values** (ranges, allowed labels) before using them.
- Without structured mode, extract the `{...}` part and parse carefully.
- Tool use applies the same idea to actions.
