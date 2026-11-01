---
title: "Errors, Retries, Rate Limits and Cost Control"
slug: llm-apis-errors-retries-cost
subject: llm-apis
lesson: 6
difficulty: intermediate
excerpt: Handle every API error the right way, retry with exponential backoff, stay within rate limits, and estimate and cap your AI bill before it surprises you. With runnable Python.
---

In production, API calls fail: networks drop, providers get overloaded, you hit your rate limit, or you send a bad request. A robust app knows **which errors to retry and which to fix**, and never lets a loop silently burn your credits.

::scene When things go wrong
🚦 | 429 Too many requests | You sent requests faster than your plan allows. Wait and try again. | byte
🔥 | 500 / 529 Server busy | The provider is overloaded. Not your fault: retry after a short pause. | chip
🚫 | 400 Bad request | Your request is wrong (bad model name, too long, wrong format). Retrying won't help: fix the code. | bugsy
🔑 | 401 Unauthorized | The key is missing, wrong or revoked. Check the environment variable. | dev
📈 | Backoff | Wait 1s, then 2s, then 4s, with a little randomness, so thousands of clients don't retry at the same moment. | byte
::end

## Retry or fix?

| Status | Meaning | What to do |
|---|---|---|
| 400 | Bad request | Fix your code (don't retry) |
| 401 / 403 | Key missing, invalid or not allowed | Fix configuration (don't retry) |
| 404 | Wrong model name or URL | Fix configuration |
| 408 / timeout | Took too long | Retry; for long answers use streaming |
| 429 | Rate limit | Retry after waiting (respect a `retry-after` header if given) |
| 500, 502, 503, 529 | Provider problem / overloaded | Retry with backoff |

The official SDKs already retry some of these automatically (for example, the Anthropic Python SDK retries twice by default) and expose typed errors:

```python
import anthropic

client = anthropic.Anthropic(max_retries=3)
try:
    r = client.messages.create(model="claude-opus-5-5", max_tokens=500,
                               messages=[{"role": "user", "content": "Hi"}])
except anthropic.AuthenticationError:
    print("Check ANTHROPIC_API_KEY")
except anthropic.BadRequestError as e:
    print("Fix the request:", e.message)
except anthropic.RateLimitError:
    print("Slow down and retry later")
except anthropic.APIStatusError as e:
    print("Provider error", e.status_code)
except anthropic.APIConnectionError:
    print("Network problem")
```

Catch the specific errors first and the general ones last, so you can treat "your bug" and "try again later" differently.

## Exponential backoff, tested without a network

Here's a retry helper you can reuse with any provider. We test it against a fake API that fails twice, then succeeds:

```python
import random

class RetryableError(Exception):
    pass

def with_backoff(call, max_tries=5, base=1.0, sleep=lambda s: None):
    for attempt in range(1, max_tries + 1):
        try:
            return call()
        except RetryableError:
            if attempt == max_tries:
                raise
            wait = base * 2 ** (attempt - 1) * random.uniform(0.8, 1.2)
            print(f"attempt {attempt} failed, waiting {wait:.1f}s")
            sleep(wait)

responses = iter([RetryableError(), RetryableError(), "Here is your answer"])
def flaky_api():
    r = next(responses)
    if isinstance(r, Exception):
        raise r
    return r

random.seed(3)
print(with_backoff(flaky_api))
```

Output:

```text
attempt 1 failed, waiting 0.9s
attempt 2 failed, waiting 2.0s
Here is your answer
```

In real code, pass `sleep=time.sleep`. The fake `sleep` makes the test instant.

## Estimating cost before you launch

Check the provider's pricing page for current per-million-token prices, then estimate. The prices below are **examples**:

```python
def monthly_cost(requests_per_day, in_tokens, out_tokens, in_price, out_price):
    per_request = in_tokens / 1e6 * in_price + out_tokens / 1e6 * out_price
    return per_request * requests_per_day * 30

options = {"small fast model": (0.10, 0.50), "mid model": (2.00, 10.00), "frontier model": (4.00, 20.00)}
for name, (pin, pout) in options.items():
    usd = monthly_cost(2000, in_tokens=1200, out_tokens=300, in_price=pin, out_price=pout)
    print(f"{name:>16}: ${usd:,.2f}/month")
```

Output:

```text
small fast model: $16.20/month
       mid model: $324.00/month
  frontier model: $648.00/month
```

Same app, 40× difference. Test whether the small model is good enough for your task before paying for the big one. Our free **AI API cost calculator** (Tools menu) does this with your own numbers.

## A budget guard

Never rely only on the provider's dashboard. Track spend in your own app and stop before a limit:

::walk python | Stop before the bill surprises you
budget = 1.00
spent = 0.0
cost_per_call = 0.35
for call in range(1, 6):
    if spent + cost_per_call > budget:
        break
    spent += cost_per_call
print(f"calls: {call - 1}, spent: ${spent:.2f}")
::notes
1: Today's budget for this feature: **$1.00**. // vars: budget=1.0
2: Nothing spent yet. // vars: spent=0.0
3: Each call costs about $0.35 (estimated from usage tokens). // vars: cost_per_call=0.35
4: Try up to 5 calls. // vars: call=1
5: Would this call go over budget? 0 + 0.35 ≤ 1: no, carry on.
7: Spent $0.35. // vars: spent=0.35
4: Call 2. // vars: call=2
7: Spent $0.70. // vars: spent=0.7
4: Call 3. // vars: call=3
5: 0.70 + 0.35 = 1.05: that's over budget!
6: **break**: stop before spending. Better a polite "try again tomorrow" than a surprise bill. // deep: In a real app, store spend per user and per day in a database, and also set a hard monthly limit in the provider's console as a second safety net.
8: Two calls made, $0.70 spent, budget respected. // out: calls: 2, spent: $0.70
::end

## Five habits that cut costs

1. **Pick the smallest model that passes your tests.**
2. **Set `max_tokens`** sensibly so answers can't run on forever.
3. **Trim chat history** (lesson 3) and keep system prompts tight.
4. **Use prompt caching** for long, repeated instructions or documents.
5. **Batch** non-urgent jobs: several providers offer cheaper asynchronous batch APIs (for example, Anthropic's Message Batches at a discount).

## Summary

- Retry **429 and 5xx** with exponential backoff and jitter; **fix** 400/401/404.
- Use the SDK's typed errors, most specific first.
- Estimate cost from tokens × price; small models can be dozens of times cheaper.
- Guard spend in your own code *and* set limits in the console.
