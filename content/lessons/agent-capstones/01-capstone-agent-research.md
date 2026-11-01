---
title: "Capstone: Research Agent"
slug: capstone-agent-research
subject: agent-capstones
lesson: 1
difficulty: advanced
excerpt: Build a research agent the professional way: a lead agent splits the question, parallel workers gather claims with exact quotes and sources, every claim is checked against its source, and an eval measures coverage. Fully runnable, then wired to real models.
---

This capstone combines the whole track: **orchestrator and workers**, **tools**, **grounding checks**, **evals** and **tracing**. The agent researches "How is UPI changing payments?" and writes a short report where every sentence is backed by a quote from a source, and anything that isn't gets caught.

::scene The fact-checked report
🎯 | Question | Dev: "How is UPI changing payments? Keep it short and sourced." | dev
🗂️ | Split | Byte, the lead, makes three sub-questions: scale, how it works, where it's accepted. | byte
🔎 | Workers | Chip and two helpers collect claims, each with a source and an exact quote. | chip
🧪 | Verified | Bugsy checks every quote against its source. One made-up claim is removed. | bugsy
::end

## The architecture

| Part | Job | Track module |
|---|---|---|
| Lead agent | Split the question, combine findings, write the report | Multi-Agent |
| Workers (parallel) | Search and read; return claims with source + quote | Multi-Agent, Tools |
| Grounding check | Is each quote really in its source? | Evals |
| Coverage eval | Does the report contain the facts a good answer must? | Evals |
| Budget + trace | Max searches per worker; log every step | Production |

## The runnable version

Sources and search are simulated (`.example` sites), so every number is reproducible:

```python
from concurrent.futures import ThreadPoolExecutor

SOURCES = {
    "stats.example/upi": "UPI processed over 16 billion transactions in a single month in 2025.",
    "bank.example/upi-guide": "UPI lets users send money instantly using a virtual payment address.",
    "travel.example/upi-abroad": "Several countries, including Singapore and the UAE, accept UPI payments from Indian travellers.",
    "blog.example/upi-myths": "Some say UPI is unsafe, but most fraud involves users sharing their PIN.",
}
SUBQUESTIONS = {"How big is UPI?": ["billion", "transactions"],
                "How does UPI work?": ["virtual payment address"],
                "Where else is UPI accepted?": ["countries", "accept"]}

def worker(item, budget=3):
    question, keywords = item
    hits = [url for url, text in SOURCES.items() if any(k in text for k in keywords)][:budget]
    return [{"question": question, "claim": SOURCES[url].split(",")[0], "quote": SOURCES[url], "source": url}
            for url in hits]

with ThreadPoolExecutor(max_workers=3) as pool:
    claims = [c for batch in pool.map(worker, SUBQUESTIONS.items()) for c in batch]

claims.append({"question": "How big is UPI?", "claim": "UPI is used by every adult in India",   # a worker hallucination
               "quote": "UPI is used by every adult in India.", "source": "stats.example/upi"})

def grounded(c):
    return c["quote"] in SOURCES.get(c["source"], "")

verified = [c for c in claims if grounded(c)]
for c in verified:
    print(f"- {c['claim']} [{c['source']}]")
print("removed:", [c["claim"] for c in claims if not grounded(c)])
```

Output:

```text
- UPI processed over 16 billion transactions in a single month in 2025. [stats.example/upi]
- UPI lets users send money instantly using a virtual payment address. [bank.example/upi-guide]
- Several countries [travel.example/upi-abroad]
removed: ['UPI is used by every adult in India']
```

Every remaining sentence has a real quote behind it. The invented claim pointed to a real source, but the quote wasn't in it, so the check caught it.

::walk python | One grounding check
source_text = "UPI processed over 16 billion transactions in a single month in 2025."
quote = "UPI is used by every adult in India."
found = quote in source_text
print(found)
::notes
1: The text the worker actually fetched from the source. // vars: source_text=1 sentence
2: The quote the worker claims supports its sentence. // vars: quote=UPI is used by every adult in India.
3: Exact-match check: is the quote really in the source? // deep: Exact matching is strict and cheap. Real pages have messy spacing, so normalise whitespace first; for paraphrased claims, add an LLM judge asking "Does this source support this claim? yes/no" (Evals module). // vars: found=False
4: Not found, so the claim is dropped (or sent back to the worker to fix). // out: False
::end

## Measure coverage

```python
must_include = ["16 billion", "virtual payment address", "Singapore", "cashback"]
report = " ".join(c["quote"] for c in verified)
covered = [fact for fact in must_include if fact in report]
print(f"coverage {len(covered)}/{len(must_include)}; missing: {[f for f in must_include if f not in covered]}")
```

Output:

```text
coverage 3/4; missing: ['cashback']
```

No source mentioned cashback, so the agent rightly didn't claim anything about it. A coverage gap tells you whether to add sources or accept that the facts don't exist. Build 10 such questions and you have an eval suite for the agent.

## The real version

- **Lead and workers:** the async orchestrator from the Multi-Agent module, with worker briefs that say "return JSON claims with source URL and an exact quote".
- **Search and read:** a web search tool (for example Claude's server-side web search) plus a fetch tool that returns clean page text.
- **Writing:** the lead writes the report **only from verified claims**, citing each one.
- **Safety:** web pages are untrusted, so workers have read-only tools and no outbound actions (Safety module).
- **Tracing:** record each worker's searches, tokens and time, and the number of claims removed.

## Portfolio checklist

- [ ] A Streamlit or web UI showing workers' progress live.
- [ ] Reports with clickable sources; removed claims listed in a "not verified" section.
- [ ] An eval of 10 questions with coverage and grounding rates, before and after improvements.
- [ ] Cost and time per report in the README.

::tip The number recruiters remember: "0 unverified claims in 10 test reports". Grounding checks are what turn a research demo into something a company could trust.

## Summary

- Lead splits, parallel workers gather claims with sources and exact quotes.
- Grounding checks remove claims not supported by their source.
- Coverage evals show what the report must include and what's missing.
- Read-only tools, budgets and tracing make it safe and debuggable.
