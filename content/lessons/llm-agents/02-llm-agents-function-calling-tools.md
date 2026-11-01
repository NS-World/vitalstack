---
title: "Function Calling and Tools"
slug: llm-agents-function-calling-tools
subject: llm-agents
lesson: 2
difficulty: intermediate
excerpt: Give a real LLM tools: tool definitions with JSON schemas, the tool_use and tool_result messages, a complete Claude tool loop, validating arguments, returning errors, and choosing between parallel and forced tool calls.
---

**Function calling** (also called **tool use**) is how an LLM asks your code to do something. The model never runs code itself. It replies with "please call `get_weather` with `{"city": "Pune"}`", your program runs the function, and you send the result back. This lesson shows the exact messages.

::scene The waiter and the kitchen
🧑‍🍳 | The kitchen | Your Python functions can do real work: weather, database, email. | dev
📋 | The menu | You give the model a menu: each tool's name, description and inputs. | byte
🛎️ | The order | The model replies: "get_weather, city = Pune". It doesn't cook. It orders. | chip
🍽️ | Served | Your code runs the tool and sends back "29°C, light rain". The model writes the answer. | bugsy
::end

## Step 1: describe the tool

A tool definition has a **name**, a **description** (written for the model: say *when* to use it) and an **input schema** (JSON Schema).

```python
weather_tool = {
    "name": "get_weather",
    "description": "Get the current weather for a city. Use it when the user asks about weather, rain or temperature.",
    "input_schema": {
        "type": "object",
        "properties": {"city": {"type": "string", "description": "City name, e.g. Pune"}},
        "required": ["city"],
    },
}
print(weather_tool["name"], "needs", weather_tool["input_schema"]["required"])
```

Output:

```text
get_weather needs ['city']
```

## Step 2: the round trip

When the model wants a tool, the response has `stop_reason == "tool_use"` and a `tool_use` block with an `id`. You answer with a `tool_result` block that carries the **same id**. Here is that round trip with a recorded response, so it runs without a key:

```python
import json

fake_response = {
    "stop_reason": "tool_use",
    "content": [
        {"type": "text", "text": "Let me check."},
        {"type": "tool_use", "id": "toolu_01", "name": "get_weather", "input": {"city": "Pune"}},
    ],
}
WEATHER = {"Pune": "29°C, light rain"}

def run_tool(name, args):
    if name == "get_weather":
        return WEATHER.get(args["city"], "unknown city")
    return f"error: no tool named {name}"

results = []
for block in fake_response["content"]:
    if block["type"] == "tool_use":
        results.append({"type": "tool_result", "tool_use_id": block["id"],
                        "content": run_tool(block["name"], block["input"])})
next_message = {"role": "user", "content": results}
print(json.dumps(next_message, ensure_ascii=False))
```

Output:

```text
{"role": "user", "content": [{"type": "tool_result", "tool_use_id": "toolu_01", "content": "29°C, light rain"}]}
```

## The complete loop with a real model (Claude)

```python
import anthropic

client = anthropic.Anthropic()          # reads ANTHROPIC_API_KEY from the environment
messages = [{"role": "user", "content": "Should I carry an umbrella in Pune today?"}]

for _ in range(5):                       # step limit
    response = client.messages.create(model="claude-opus-5-5", max_tokens=1024,
                                      tools=[weather_tool], messages=messages)
    messages.append({"role": "assistant", "content": response.content})
    if response.stop_reason != "tool_use":
        print(next(b.text for b in response.content if b.type == "text"))
        break
    results = [{"type": "tool_result", "tool_use_id": b.id, "content": run_tool(b.name, b.input)}
               for b in response.content if b.type == "tool_use"]
    messages.append({"role": "user", "content": results})
```

Other providers (OpenAI, Gemini, Grok) use the same idea with different field names: tools are listed with a JSON schema, the reply contains tool calls with an id, and you send back results tagged with that id. Use their current docs and a `MODEL_NAME` of your choice.

## Step 3: never trust the arguments

The model usually sends valid arguments, but not always. Check them before running anything:

::walk python | Checking tool arguments
schema = {"required": ["city", "date"], "types": {"city": str, "date": str, "adults": int}}
args = {"city": "Goa", "adults": "2"}
missing = [k for k in schema["required"] if k not in args]
wrong = [k for k, t in schema["types"].items() if k in args and not isinstance(args[k], t)]
print(missing, wrong)
::notes
1: What the tool expects: two required fields, and a type for each field. // vars: schema=required + types
2: What the model sent: no date, and adults as the text "2", not the number 2. // vars: args={'city': 'Goa', 'adults': '2'}
3: Which required fields are missing? // vars: missing=['date']
4: Which fields have the wrong type? // deep: In production use a real validator (jsonschema or Pydantic) instead of hand-written checks. Many APIs also offer a strict mode that forces arguments to match the schema. // vars: wrong=['adults']
5: Send both problems back to the model as an error result, and it will usually fix its call on the next turn. // out: ['date'] ['adults']
::end

## Errors are results too

If a tool fails, don't crash. Return the error as the tool result (Claude accepts `"is_error": True` on a `tool_result`) with a helpful message like "date is required, format YYYY-MM-DD". The model reads it and tries again.

## Useful options

| Option | What it does |
|---|---|
| Parallel tool calls | The model may ask for several tools in one turn (weather for 3 cities). Run them all and return all results together. |
| `tool_choice` | `auto` (model decides), `any` (must use some tool), or a specific tool (forces structured output). |
| Few, clear tools | 3–10 well-described tools work better than 50 vague ones. |

::tip Write tool descriptions like instructions for a new teammate: what it does, when to use it, when NOT to use it, and what the inputs look like. Better descriptions fix more agent bugs than any clever code.

## Summary

- The model requests a tool; your code runs it and returns a `tool_result` with the same id.
- Tools need a name, a "when to use" description and a JSON input schema.
- Loop until `stop_reason` is no longer `tool_use`, with a step limit.
- Validate arguments, and return errors as results so the model can recover.
