---
title: "Short-Term Memory: the Context Window"
slug: memory-short-term-context
subject: agent-memory
lesson: 1
difficulty: intermediate
excerpt: An agent's working memory is its context window. Measure what fills it, keep it lean with sliding windows and pinned facts, clear old tool results, compact long runs, and learn why a bigger window isn't a free pass.
---

An LLM remembers nothing between calls. Everything it "knows" about the current task is in the **context window**: the system prompt, the conversation, and every tool result so far. That's short-term memory. Manage it badly and the agent gets slow, expensive and forgetful; manage it well and it stays sharp for hours.

::scene The overflowing whiteboard
🧑‍🏫 | Whiteboard | Chip's whiteboard holds the goal, the chat and every search result. | chip
📜 | Overflow | One search dumped 2 pages of job listings. The goal is pushed far up, almost out of sight. | bugsy
🧽 | Clean | Byte wipes old search results, leaving a one-line note: "12 openings found". | byte
📌 | Pinned | Dev pins the goal at the top. The board is tidy and the agent stays on track. | dev
::end

## What fills the window?

A rough rule: **1 token ≈ 4 characters of English**. Let's measure a short agent run:

```python
def tokens(text):
    return max(1, len(text) // 4)

conversation = [
    {"role": "user", "content": "I'm Meera, preparing for a data analyst interview in 3 weeks."},
    {"role": "assistant", "content": "Great! Let's plan SQL, Excel and statistics."},
    {"role": "tool", "content": "search_results: " + "job listing text " * 120},
    {"role": "assistant", "content": "I found 12 openings in Bengaluru."},
    {"role": "user", "content": "Quiz me on SQL joins."},
]
sizes = [tokens(m["content"]) for m in conversation]
print(sizes, "total:", sum(sizes), f"| tool result share: {sizes[2] / sum(sizes):.0%}")
```

Output:

```text
[15, 11, 514, 8, 5] total: 553 | tool result share: 93%
```

One tool result is almost all of the context. That's typical: **tool results, not chat, are what fill an agent's memory.**

## Clear old tool results

Once the agent has used a result, replace it with a one-line note:

```python
def clear_old_tool_results(messages, keep_last=1):
    tool_idx = [i for i, m in enumerate(messages) if m["role"] == "tool"]
    cleaned = [dict(m) for m in messages]
    for i in tool_idx[:-keep_last] if keep_last else tool_idx:
        cleaned[i]["content"] = "[old tool result cleared: job search, 12 openings found]"
    return cleaned

lean = clear_old_tool_results(conversation, keep_last=0)
print(sum(tokens(m["content"]) for m in lean), "tokens after clearing")
```

Output:

```text
53 tokens after clearing
```

Some AI APIs now offer this automatically ("context editing" or "tool result clearing"), plus **compaction**: summarising the older part of a long run so it can continue.

## Sliding window with pinned messages

::walk python | Keep the goal, drop the middle
msgs = ["m1 (pinned goal)", "m2", "m3", "m4", "m5", "m6"]
keep_last = 3
window = msgs[:1] + msgs[-keep_last:]
print(window)
::notes
1: Six messages; the first states the user's goal. // vars: msgs=6 messages
2: Keep only the 3 most recent messages in full. // vars: keep_last=3
3: Always keep the pinned goal, plus the recent messages. // deep: Many agents pin more than the first message: the task, key decisions and constraints (budget, deadline). Older details move to a summary or long-term memory (next lesson) instead of being lost. // vars: window=4 messages
4: m2 and m3 are dropped; the goal survives. // out: ['m1 (pinned goal)', 'm4', 'm5', 'm6']
::end

## Why not just use a huge window?

Models now accept hundreds of thousands of tokens or more. But:

- **Cost:** every call re-sends the whole context. 100,000 tokens × 30 steps adds up fast.
- **Speed:** longer inputs mean slower responses.
- **Attention:** models can miss details buried in the middle of very long contexts.
- **Noise:** old, irrelevant tool output can distract the model from the current step.

A lean context is usually both cheaper **and** more accurate.

## A context budget

Give each part a share, and check it every turn:

| Part | Budget (example) |
|---|---|
| System prompt + tool definitions | 3,000 tokens |
| Pinned facts (goal, constraints) | 500 |
| Summary of older turns | 1,000 |
| Recent turns | 6,000 |
| Latest tool results | 4,000 |

The API's usage numbers (input tokens per call) tell you if you're over budget.

::tip Put the stable parts (system prompt, tool definitions) first and keep them identical across calls. That makes prompt caching work, which can cut the cost of the repeated part dramatically (see the Production module).

## Summary

- Short-term memory = whatever is in the context window for this call.
- Tool results usually fill it fastest: clear or summarise them after use.
- Use a sliding window with pinned goals and constraints.
- Bigger windows cost more and can be less accurate; keep context lean on purpose.
