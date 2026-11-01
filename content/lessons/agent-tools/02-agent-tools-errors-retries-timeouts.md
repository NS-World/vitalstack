---
title: "Errors, Retries and Timeouts"
slug: agent-tools-errors-retries-timeouts
subject: agent-tools
lesson: 2
difficulty: intermediate
excerpt: Make agent tools robust: sort errors into retryable and permanent, retry with exponential backoff and jitter, put timeouts on every call, prevent double payments with idempotency keys, and give the model error messages it can act on.
---

Real tools fail: APIs time out, rate limits hit, a database is busy for a second. A demo agent crashes; a production agent **retries what's worth retrying, gives up on what isn't, and never does the same payment twice**. This lesson builds those habits.

::scene The flaky weather API
🌧️ | Fail | The weather API returns "503: busy" on Chip's first call. | chip
⏳ | Wait | Byte waits half a second and tries again. Busy again. Waits 1 second. | byte
✅ | Success | Third try works. The user never knew there was a problem. | dev
💳 | Careful | For payments, Bugsy sends an idempotency key, so a retry can never charge twice. | bugsy
::end

## Two kinds of errors

| Retryable (temporary) | Permanent (don't retry) |
|---|---|
| Timeout, connection reset | 400 bad request, invalid arguments |
| 429 rate limited | 401/403 not allowed |
| 500, 502, 503, 529 overloaded | 404 not found |
| "Database busy" | Business rule: "slot already booked" |

Retrying a permanent error just wastes time. Send it back to the model with a clear message instead.

## Retry with backoff

```python
import random

class Temporary(Exception):
    pass

failures_left = {"get_weather": 2}           # this fake API fails twice, then works

def get_weather(city):
    if failures_left["get_weather"] > 0:
        failures_left["get_weather"] -= 1
        raise Temporary("503 service busy")
    return f"{city}: 27°C, clear"

def with_retries(fn, *args, attempts=4, base=0.5, cap=8.0, sleep=lambda s: None):
    rng = random.Random(42)
    for attempt in range(attempts):
        try:
            return fn(*args)
        except Temporary as e:
            if attempt == attempts - 1:
                raise
            delay = min(cap, base * 2 ** attempt) * rng.uniform(0.5, 1.0)     # backoff + jitter
            print(f"attempt {attempt + 1} failed ({e}); retrying in {delay:.2f}s")
            sleep(delay)

print(with_retries(get_weather, "Chennai"))
```

Output:

```text
attempt 1 failed (503 service busy); retrying in 0.41s
attempt 2 failed (503 service busy); retrying in 0.51s
Chennai: 27°C, clear
```

(The `sleep` argument is a stand-in here so the example runs instantly; in real code pass `time.sleep`.)

::walk python | The backoff schedule
base, cap = 0.5, 8.0
delays = [min(cap, base * 2 ** i) for i in range(6)]
print(delays)
::notes
1: Start with half a second; never wait more than 8 seconds. // vars: base=0.5, cap=8.0
2: Each retry doubles the wait: 0.5, 1, 2, 4, 8… capped at 8. // deep: Jitter (multiplying by a random 0.5–1.0, as above) spreads retries out, so a thousand agents that failed together don't all retry at the same instant and knock the API over again.
3: The cap stops waits from growing forever. // out: [0.5, 1.0, 2.0, 4.0, 8.0, 8.0]
::end

Also respect a `Retry-After` header when the API sends one, and remember that the official AI SDKs already retry their own API calls; you add retries around **your** tools.

## Timeouts on everything

A tool that hangs freezes the whole agent. Give every call a deadline:

```python
import time
from concurrent.futures import ThreadPoolExecutor, TimeoutError as Timeout

def slow_search(query):
    time.sleep(0.3)
    return ["result"]

with ThreadPoolExecutor(max_workers=1) as pool:
    future = pool.submit(slow_search, "python jobs pune")
    try:
        print(future.result(timeout=0.05))
    except Timeout:
        print("search timed out after 0.05s: tell the model and let it decide")
```

Output:

```text
search timed out after 0.05s: tell the model and let it decide
```

HTTP libraries have a `timeout=` parameter: always set it. Also set an overall limit for the whole agent run.

## Idempotency: never do it twice

If a payment call times out, did it go through? Retrying blindly could charge the customer twice. An **idempotency key** fixes it: the server remembers the key and returns the first result for repeats.

```python
processed = {}

def pay(order_id, amount, key):
    if key in processed:
        return processed[key] + " (duplicate ignored)"
    processed[key] = f"charged ₹{amount} for {order_id}"
    return processed[key]

key = "order-1042-attempt"                  # the same key for every retry of this action
print(pay("1042", 799, key))
print(pay("1042", 799, key))
```

Output:

```text
charged ₹799 for 1042
charged ₹799 for 1042 (duplicate ignored)
```

Payment providers support this (often as an `Idempotency-Key` header). Build the same into your own write tools.

## Errors the model can act on

Compare what the model receives:

- ❌ `Error 400`
- ✅ `"date must be YYYY-MM-DD; you sent 06/11/2026. Today is 2026-11-01."`

The second tells it exactly how to fix the call. Return errors as normal tool results (marked as errors), never as crashes.

::tip Log every retry with the tool name and error type. If one tool needs retries on 20% of calls, that's a reliability bug to fix at the source, not something retries should hide.

## Summary

- Retry temporary errors with exponential backoff and jitter; don't retry permanent ones.
- Put a timeout on every tool call and on the whole run.
- Use idempotency keys so retried writes can't happen twice.
- Return clear, actionable error messages to the model.
