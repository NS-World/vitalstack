---
title: "Function Calling and JSON Schemas in Depth"
slug: agent-tools-json-schemas
subject: agent-tools
lesson: 1
difficulty: intermediate
excerpt: Master the schemas that tell an agent how to call your tools: types, enums, ranges, nested objects and arrays, required fields, additionalProperties, strict mode, and generating schemas automatically from Python functions.
---

A tool's **JSON Schema** is a contract: it tells the model exactly what inputs are allowed. A loose schema ("date: string") invites mistakes like "next Friday" or "31/02". A tight schema (a pattern, an enum, a range) lets the model get it right first time, and lets your code reject anything else.

::scene The mentor booking desk
📅 | The tool | Dev builds book_slot so the agent can book mentor calls. | dev
🌀 | Loose | With "day: string", Chip sends "next Friday-ish". The booking fails. | chip
📏 | Tight | Byte adds a date pattern, hours 9–17 and an enum for the call type. | byte
🎯 | Right first time | The model now sends {"day": "2026-11-06", "hour": 15, "mode": "video"}. | bugsy
::end

## The schema toolkit

| Keyword | Use | Example |
|---|---|---|
| `type` | string, integer, number, boolean, array, object | `"type": "integer"` |
| `enum` | A fixed list of allowed values | `["video", "audio", "chat"]` |
| `minimum` / `maximum` | Number ranges | hour 9 to 17 |
| `pattern` | A regular expression for strings | `^\d{4}-\d{2}-\d{2}$` |
| `items` | What each array element looks like | a list of strings |
| `required` | Fields that must be present | `["day", "hour"]` |
| `additionalProperties: false` | No extra, made-up fields | rejects `"discount": 100` |
| `description` | Guidance for the model, per field | "Day in YYYY-MM-DD format" |

## A tight schema

```python
book_slot_tool = {
    "name": "book_slot",
    "description": "Book a 30-minute mentor call. Use only after the user has confirmed the day and time.",
    "input_schema": {
        "type": "object",
        "properties": {
            "day": {"type": "string", "pattern": r"^\d{4}-\d{2}-\d{2}$", "description": "Day in YYYY-MM-DD format"},
            "hour": {"type": "integer", "minimum": 9, "maximum": 17, "description": "Start hour, 24-hour clock"},
            "mode": {"type": "string", "enum": ["video", "audio", "chat"]},
            "topics": {"type": "array", "items": {"type": "string"}, "maxItems": 3},
        },
        "required": ["day", "hour", "mode"],
        "additionalProperties": False,
    },
}
print(sorted(book_slot_tool["input_schema"]["properties"]), book_slot_tool["input_schema"]["required"])
```

Output:

```text
['day', 'hour', 'mode', 'topics'] ['day', 'hour', 'mode']
```

Several providers offer a **strict** mode that makes the model's arguments always match the schema. Even then, keep validating in code: business rules (is that slot free?) can't live in a schema.

## Generate schemas from your functions

Writing schemas by hand gets repetitive. Python can read a function's signature and docstring and build one:

```python
import inspect
import json

TYPES = {str: "string", int: "integer", float: "number", bool: "boolean", list: "array"}

def schema_from(fn):
    props, required = {}, []
    for name, p in inspect.signature(fn).parameters.items():
        props[name] = {"type": TYPES[p.annotation]}
        if p.default is inspect.Parameter.empty:
            required.append(name)
    return {"name": fn.__name__, "description": inspect.getdoc(fn),
            "input_schema": {"type": "object", "properties": props, "required": required}}

def lesson_progress(user_id: int, course: str, include_quizzes: bool = False):
    """Get a learner's progress in one course. Use when they ask how far they've got."""

print(json.dumps(schema_from(lesson_progress)))
```

Output:

```text
{"name": "lesson_progress", "description": "Get a learner's progress in one course. Use when they ask how far they've got.", "input_schema": {"type": "object", "properties": {"user_id": {"type": "integer"}, "course": {"type": "string"}, "include_quizzes": {"type": "boolean"}}, "required": ["user_id", "course"]}}
```

This is what helpers in frameworks and SDKs do for you (LangChain's `@tool`, Pydantic models, the Agent SDKs' tool decorators): type hints become types, defaults decide `required`, and the docstring becomes the description.

## Validate against the schema

::walk python | Checking enum and range rules
args = {"day": "2026-11-06", "hour": 19, "mode": "zoom"}
props = book_slot_tool["input_schema"]["properties"]
errors = []
if not props["hour"]["minimum"] <= args["hour"] <= props["hour"]["maximum"]: errors.append("hour must be 9-17")
if args["mode"] not in props["mode"]["enum"]: errors.append("mode must be one of " + ", ".join(props["mode"]["enum"]))
print(errors)
::notes
1: The model asked for 7 pm and "zoom". // vars: args={'day': '2026-11-06', 'hour': 19, 'mode': 'zoom'}
2: Read the rules straight from the schema, so code and schema can never disagree. // vars: props=4 fields
3: Start with no errors. // vars: errors=[]
4: 19 is outside 9–17. // vars: errors=['hour must be 9-17']
5: "zoom" isn't an allowed mode. Listing the allowed values helps the model fix it. // deep: The jsonschema library does all of this (patterns, nested objects, arrays) in one call: jsonschema.validate(args, schema). Use it, or Pydantic, in real projects.
6: Send both errors back as the tool result; the model will retry with a valid call. // out: ['hour must be 9-17', 'mode must be one of video, audio, chat']
::end

## Schema design tips

- **Prefer enums** to free text whenever there's a fixed list.
- **Use formats the model knows:** ISO dates (`2026-11-06`), 24-hour times, currency codes (`INR`).
- **Flat is easier than deeply nested.** Two levels of nesting is usually the most a model handles reliably.
- **Describe every field**, including units ("amount in rupees, not paise").
- **Keep optional fields truly optional**, with sensible defaults in your code.

::tip Read your schema as if you were the model, seeing it for the first time with no other context. Every question you'd have ("which date format? which timezone?") belongs in a description.

## Summary

- JSON Schema defines types, enums, ranges, patterns, arrays and required fields for tool inputs.
- `additionalProperties: false` blocks invented fields; strict modes enforce the schema.
- Generate schemas from function signatures and docstrings to avoid repetition.
- Always validate arguments in code too, reading the rules from the schema.
