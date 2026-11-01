---
title: "Caching"
slug: production-caching
subject: agent-production
lesson: 2
difficulty: advanced
excerpt: Three caches every agent needs: prompt caching for long repeated prefixes (system prompts, tools, documents), tool-result caching with expiry times, and response caching, plus how to keep caches from serving stale or other users' data.
---

Agents repeat themselves: the same 10,000-token system prompt and tool list on every call, the same exchange-rate lookup fifty times an hour, the same "What's your refund policy?" all day. **Caching** stops you paying, and waiting, for the same work twice.

::scene Don't do it twice
📚 | Same prefix | Every call sends Chip's 12,000-token rulebook. Byte caches it: later calls read it at a tiny price. | byte
💱 | Same lookup | The exchange rate is fetched once and reused for 5 minutes. | chip
⏰ | Expiry | After 5 minutes the cached rate expires and is fetched fresh. | dev
🔒 | Per user | Bugsy makes sure one customer never sees another's cached order. | bugsy
::end

## 1. Prompt caching

Providers can cache the **processed prefix** of a prompt. Mark where the stable part ends; later calls with the same prefix reuse it. With Claude:

```python
import anthropic

client = anthropic.Anthropic()
RULEBOOK = open("support_rulebook.md").read()          # long, rarely changes

r = client.messages.create(
    model="claude-opus-5-5", max_tokens=500,
    system=[{"type": "text", "text": RULEBOOK, "cache_control": {"type": "ephemeral"}}],
    messages=[{"role": "user", "content": "Can I return a phone after 10 days?"}])
print(r.usage.cache_creation_input_tokens, r.usage.cache_read_input_tokens)
```

The first call **writes** the cache (a little more than normal input price); calls within the cache lifetime **read** it at a small fraction of the normal price, and faster. Other providers cache long repeated prefixes automatically. Check your provider's current pricing and cache lifetime.

```python
system_tokens, calls, price = 12_000, 50, 3.00          # example: $3 per million input tokens
no_cache = calls * system_tokens * price / 1e6
with_cache = (system_tokens * 1.25 + (calls - 1) * system_tokens * 0.10) * price / 1e6   # example write/read multipliers
print(f"rulebook cost for {calls} calls: ${no_cache:.2f} without cache vs ${with_cache:.3f} with cache")
```

Output:

```text
rulebook cost for 50 calls: $1.80 without cache vs $0.221 with cache
```

**Rules for cache hits:** put stable content first (tools, system prompt, big documents), changing content last (the conversation), and don't change the prefix between calls: even a timestamp at the top breaks the cache.

## 2. Tool-result caching with expiry

```python
class TTLCache:
    def __init__(self, ttl, clock):
        self.ttl, self.clock, self.data = ttl, clock, {}

    def get(self, key):
        hit = self.data.get(key)
        if hit and self.clock() - hit[1] < self.ttl:
            return hit[0]
        return None

    def set(self, key, value):
        self.data[key] = (value, self.clock())

now = [0]                                               # a fake clock in seconds
cache = TTLCache(ttl=300, clock=lambda: now[0])
fetches = 0

def usd_to_inr():
    global fetches
    cached = cache.get("usd_inr")
    if cached is not None:
        return cached
    fetches += 1
    rate = 83.1 + fetches * 0.1                         # pretend the live rate moves
    cache.set("usd_inr", rate)
    return rate

for t in (0, 60, 299, 301, 400):
    now[0] = t
    print(f"t={t:>3}s rate={usd_to_inr():.1f}")
print("real fetches:", fetches)
```

Output:

```text
t=  0s rate=83.2
t= 60s rate=83.2
t=299s rate=83.2
t=301s rate=83.3
t=400s rate=83.3
real fetches: 2
```

Five lookups, two real fetches. Pick the expiry (TTL) by how fast the data changes: seconds for stock levels, minutes for exchange rates, a day for a product catalogue.

## Cache keys: include everything that matters

::walk python | A safe cache key
def key(tool, args, user):
    return f"{user}:{tool}:{sorted(args.items())}"
print(key("order_status", {"id": "1042"}, "u7"))
::notes
1: A cache key must include everything that can change the result.
2: The user id comes first, so customer u7's cached data can never be served to u8. Arguments are sorted, so {a, b} and {b, a} share one entry. // deep: Leaving the user out of the key is a real-world data leak: one customer asks "show my orders", the next gets the first customer's cached answer. Also include the tool version, so a code change doesn't serve old-format results.
3: The key for this lookup. // out: u7:order_status:[('id', '1042')]
::end

## 3. Response caching

For identical common questions (FAQs), cache the final answer, keyed by the normalised question plus the knowledge-base version. Be careful with **semantic caching** (reusing answers for "similar" questions): "Can I return shoes?" and "Can I return shoes I wore?" are similar but have different answers. Use it only with a high similarity threshold and for non-personal answers.

## What never to cache (or cache only per user)

- Personal data and account details (cache per user, short TTL, or not at all).
- Anything after a write: invalidate the order-status cache when the order changes.
- Safety-relevant checks (permissions must be checked every time).

::tip Watch your cache hit rate. For prompt caching, the API's usage fields (cache read vs creation tokens) show it directly. A sudden drop usually means someone added something that changes on every call to the top of the prompt.

## Summary

- Prompt caching: stable content first, mark the prefix, keep it identical; big savings on repeated calls.
- Tool-result caching with TTLs matched to how fast data changes.
- Cache keys include user, arguments and versions; invalidate after writes.
- Response caching for common questions, but be careful with semantic caching and personal data.
