---
title: "Tracing and Observability"
slug: evals-tracing-observability
subject: agent-evals
lesson: 3
difficulty: advanced
excerpt: See inside every agent run: traces and spans, recording model calls, tool calls, tokens, latency and errors, building a tiny tracer, finding the slowest and costliest steps, and the tools teams use (OpenTelemetry, LangSmith, Langfuse, Phoenix).
---

A user says "the agent was slow and gave a weird answer". Without tracing, you're guessing. With a **trace**, you can open that exact run and see every model call, every tool call, the inputs and outputs, how long each took and what it cost. Observability turns agent debugging from detective work into reading a timeline.

::scene The slow answer mystery
🐢 | Complaint | "It took 3 seconds and quoted the wrong delivery date." | dev
🧵 | Open the trace | Byte opens the run: agent → model → tool → model, with timings. | byte
🔍 | Found it | The second model call took 1.2 s; the tool returned yesterday's cached date. | chip
🩹 | Fixed | Bugsy shortens the context and fixes the cache. The next trace is green. | bugsy
::end

## Traces and spans

- A **trace** is one whole run (one user request).
- A **span** is one step inside it: a model call, a tool call, a retrieval. Spans nest: the run span contains the model and tool spans.
- Each span records **attributes**: model, tokens, tool name, status, error message.

## A tiny tracer

A fake clock keeps the numbers the same every time you run it:

```python
import contextlib

readings = iter([0, 5, 905, 910, 1060, 1065, 2265, 2270])    # fake clock in milliseconds
spans, stack = [], []

@contextlib.contextmanager
def span(name, **attrs):
    start = next(readings)
    record = {"name": name, "depth": len(stack), "attrs": attrs}
    spans.append(record)
    stack.append(name)
    try:
        yield record
    finally:
        record["ms"] = next(readings) - start
        stack.pop()

with span("agent_run", user="u42"):
    with span("llm_call", input_tokens=1800, output_tokens=120):
        pass
    with span("tool:delivery_date", status="ok", cached=True):
        pass
    with span("llm_call", input_tokens=2300, output_tokens=210):
        pass

for s in spans:
    print("  " * s["depth"] + f"{s['name']:<18} {s['ms']:>5} ms  {s['attrs']}")
```

Output:

```text
agent_run           2270 ms  {'user': 'u42'}
  llm_call             900 ms  {'input_tokens': 1800, 'output_tokens': 120}
  tool:delivery_date   150 ms  {'status': 'ok', 'cached': True}
  llm_call            1200 ms  {'input_tokens': 2300, 'output_tokens': 210}
```

The tree shows the run took 2.27 s, and the second model call alone took 1.2 s. The tool result was cached, which is where the stale date came from.

## Ask questions of your traces

::walk python | Finding the slowest step
steps = {"llm_call #1": 900, "tool:delivery_date": 150, "llm_call #2": 1200}
slowest = max(steps, key=steps.get)
share = steps[slowest] / sum(steps.values())
print(slowest, f"{share:.0%}")
::notes
1: Durations of the steps inside the run, from the trace. // vars: steps=3 spans
2: Which step took longest? // vars: slowest=llm_call #2
3: How much of the total was it? // deep: Across thousands of traces you'd look at percentiles (p50, p95) per span type, not single runs. Slow model calls usually mean long inputs (trim context) or long outputs (lower max_tokens, ask for brevity). // vars: share=0.533...
4: One model call was over half the time: the first thing to optimise. // out: llm_call #2 53%
::end

## Cost per run from spans

```python
price_in, price_out = 3.00, 15.00          # example $ per million tokens
llm = [s["attrs"] for s in spans if s["name"] == "llm_call"]
tokens_in = sum(a["input_tokens"] for a in llm)
tokens_out = sum(a["output_tokens"] for a in llm)
cost = (tokens_in * price_in + tokens_out * price_out) / 1e6
print(f"{len(llm)} model calls, {tokens_in:,} in / {tokens_out} out tokens, ${cost:.4f} per run")
```

Output:

```text
2 model calls, 4,100 in / 330 out tokens, $0.0173 per run
```

Multiply by runs per day and you know the daily bill for this agent, per feature and per customer.

## What to record

| Record | Why |
|---|---|
| Model, prompt version, tokens in/out | Cost and regressions after prompt changes |
| Tool name, arguments, result size, status | Tool bugs and slow tools |
| Latency per span | Where time goes |
| Errors and retries | Reliability |
| User and session ids (not personal data) | Find a reported run quickly |
| Eval or feedback scores | Link quality to specific runs |

Redact personal data before it's stored (Safe AI Apps module).

## Tools teams use

- **OpenTelemetry:** the open standard for traces; many AI libraries emit OpenTelemetry spans, so you can send them to any compatible backend.
- **LLM observability platforms** such as LangSmith, Langfuse and Arize Phoenix: trace viewers, cost dashboards, datasets and evals in one place (some are open source and self-hostable).
- **Built-in tracing** in agent SDKs (for example, the OpenAI Agents SDK traces runs by default) and provider usage dashboards.

## From traces to evals

The best eval cases come from traces: when a run fails in production, save its input as a new test case, fix the agent, and the eval suite keeps it fixed forever.

::tip Add a trace id to every response your agent sends (even hidden in the API response). When a user reports a problem, ask for it, or look it up by time, and you'll be reading the exact run in seconds.

## Summary

- A trace is one run; spans are its nested steps with attributes.
- Record model calls, tool calls, tokens, latency, errors and versions.
- Use traces to find slow and costly steps and to compute cost per run.
- OpenTelemetry and LLM observability platforms collect and display traces; failed traces become eval cases.

🎉 You've finished **Evals and Tracing**.
