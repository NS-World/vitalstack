---
title: "Memory for Chatbots: Buffers, Windows and Summaries"
slug: langchain-memory-chatbots
subject: langchain
lesson: 2
difficulty: intermediate
excerpt: Give chatbots memory: full buffers, sliding windows, running summaries and per-user session stores, with LangChain's message history and a runnable implementation of each strategy.
---

LLM APIs are stateless: every request starts from zero. A chatbot "remembers" only because your app stores the conversation and sends it again. **Memory** is the strategy for what to keep, what to drop and what to compress as conversations grow.

::scene Remembering the right things
💬 | Turn 1 | "I'm Asha, preparing for the GATE exam in February." | dev
🗂️ | Buffer | Keep every message: perfect memory, but the bill grows with each turn. | chip
🪟 | Window | Keep only the last 6 messages: cheap, but "Asha" and "GATE" fall out. | bugsy
📝 | Summary | Keep a running summary ("Asha, GATE in Feb, weak at DBMS") plus recent turns. Best of both. | byte
::end

## Three strategies, runnable

```python
history = [
    ("user", "Hi, I'm Asha and my GATE exam is in February."),
    ("ai", "Great, Asha! Which subjects worry you?"),
    ("user", "DBMS and computer networks."),
    ("ai", "Let's start with DBMS normal forms."),
    ("user", "What is 2NF?"),
    ("ai", "2NF removes partial dependencies on a composite key."),
    ("user", "And 3NF?"),
]

buffer = history                                   # 1. everything
window = history[-4:]                              # 2. the last 4 messages
summary = "Asha, GATE exam in February, weak in DBMS and networks; studying normal forms."
summarised = [("system", "Conversation so far: " + summary)] + history[-2:]   # 3. summary + recent

for name, mem in (("buffer", buffer), ("window", window), ("summary", summarised)):
    text = " ".join(m for _, m in mem)
    print(f"{name:>7}: {len(text.split()):>3} words | knows the exam date? {'February' in text}")
```

Output:

```text
 buffer:  39 words | knows the exam date? True
 window:  19 words | knows the exam date? False
summary:  26 words | knows the exam date? True
```

The summary keeps the important facts at a fraction of the size of the full buffer.

::walk python | Updating a running summary
summary = "Asha, GATE in February."
new_turns = ["Weak in DBMS.", "Wants daily practice questions."]
prompt = f"Update the summary with the new facts, under 25 words.\nSummary: {summary}\nNew: {' '.join(new_turns)}"
updated = "Asha, GATE in February, weak in DBMS, wants daily practice questions."
print(len(updated.split()), "words")
::notes
1: The summary so far. // vars: summary=Asha, GATE in February.
2: Messages that are about to drop out of the recent window. // vars: new_turns=['Weak in DBMS.', 'Wants daily practice questions.']
3: Ask a cheap, fast model to merge them into the summary, with a length limit. // deep: Run this every few turns, not every turn, to save cost. Keep names, dates, preferences and decisions; drop greetings and chit-chat. // vars: prompt=Update the summary…
4: The model's updated summary (shown here as the expected result). // vars: updated=Asha, GATE in February, weak in DBMS, wants daily practice questions.
5: Still tiny, but nothing important lost. // out: 11 words
::end

## Sessions: one memory per user

A real app stores histories by **session ID**, in a database or cache (Redis, PostgreSQL), not in a Python variable:

```python
from langchain_core.chat_history import InMemoryChatMessageHistory
from langchain_core.runnables.history import RunnableWithMessageHistory
from langchain_core.prompts import ChatPromptTemplate, MessagesPlaceholder

store = {}
def get_history(session_id):
    return store.setdefault(session_id, InMemoryChatMessageHistory())

prompt = ChatPromptTemplate.from_messages([
    ("system", "You are a friendly GATE tutor."),
    MessagesPlaceholder("history"),
    ("user", "{question}"),
])
bot = RunnableWithMessageHistory(prompt | model, get_history,
                                 input_messages_key="question", history_messages_key="history")
bot.invoke({"question": "I'm Asha."}, config={"configurable": {"session_id": "asha-123"}})
bot.invoke({"question": "What's my name?"}, config={"configurable": {"session_id": "asha-123"}})
```

(`InMemoryChatMessageHistory` is for demos; swap in a database-backed history for production. For agents, LangGraph's checkpointers play the same role: Agentic AI track.)

## Long-term memory

Some facts should last across sessions ("Asha prefers Hindi explanations"). Store them as short **facts** in a database or vector store, and retrieve the relevant ones into the system prompt each time (a small RAG over the user's own profile). Always let users see and delete what's stored about them.

::tip Decide what NOT to remember. Don't store passwords, OTPs, card numbers or health details a user mentions in passing. Redact them before saving history (see *Working with LLM APIs*, lesson 7).

## Summary

- Memory = what conversation history you resend.
- Buffer (all), window (recent N), summary (compressed + recent): trade cost against recall.
- Store histories per session ID in a real database.
- Long-term facts go in a store and are retrieved when relevant; redact sensitive data.
