---
title: "Chat History and System Prompts: Building a Chatbot That Remembers"
slug: llm-apis-chat-history-system-prompts
subject: llm-apis
lesson: 3
difficulty: intermediate
excerpt: LLM APIs have no memory. Learn how chatbots remember by resending the conversation, how system prompts set behaviour, and how to stop long chats from getting expensive.
---

Here's a surprise for most beginners: **the API forgets everything after each request.** ChatGPT and Claude seem to remember your name because the app **sends the whole conversation again** with every new message. Your chatbot must do the same.

::scene Why the model "remembers"
💬 | Turn 1 | You say "My name is Asha." The app sends just that message. | dev
🧠 | Forget | The answer comes back, and the server keeps nothing. The model's memory is wiped. | byte
📜 | Turn 2 | You ask "What's my name?" The app sends BOTH messages, plus the model's first reply. | chip
✅ | Remember | The model reads the history and answers "Asha". The memory lives in your app, not the model. | dev
::end

## The messages list

Every provider uses a list of messages with **roles**: `user` (the person), `assistant` (the model's earlier replies), and a **system prompt** for standing instructions. With Claude the system prompt is a separate `system=` argument; OpenAI-style APIs put it as a message with role `system` (or `developer`) first.

```python
import anthropic

client = anthropic.Anthropic()
history = []

def chat(user_text):
    history.append({"role": "user", "content": user_text})
    r = client.messages.create(
        model="claude-opus-5-5", max_tokens=1000,
        system="You are Byte, a friendly coding tutor. Answer in 3 sentences or fewer.",
        messages=history,
    )
    reply = "".join(b.text for b in r.content if b.type == "text")
    history.append({"role": "assistant", "content": reply})
    return reply

# chat("My name is Asha.")
# chat("What's my name?")   -> mentions Asha, because history was resent
```

## Try it free with a fake model

This fake model "remembers" only what's in the messages it receives, exactly like a real one:

```python
def fake_model(system, messages):
    names = [m["content"].split("is ")[-1].strip(".") for m in messages
             if m["role"] == "user" and m["content"].startswith("My name is")]
    last = messages[-1]["content"]
    if "my name" in last.lower() and "?" in last:
        return f"Your name is {names[-1]}." if names else "You haven't told me your name."
    return "Nice to meet you!"

history = []
def chat(text, keep_history=True):
    msgs = history + [{"role": "user", "content": text}] if keep_history else [{"role": "user", "content": text}]
    reply = fake_model("You are a tutor.", msgs)
    history.append({"role": "user", "content": text})
    history.append({"role": "assistant", "content": reply})
    return reply

print(chat("My name is Asha."))
print(chat("What's my name?"))
print(chat("What's my name?", keep_history=False))
```

Output:

```text
Nice to meet you!
Your name is Asha.
You haven't told me your name.
```

Without the history, the same question fails. That's the whole secret.

## Writing a good system prompt

The system prompt is where you define the assistant's job. A good one covers:

1. **Role and audience:** "You are a patient tutor for first-year engineering students."
2. **What to do:** "Explain with an everyday Indian example, then a code example."
3. **Limits:** "If you don't know, say so. Don't write full assignment solutions; give hints."
4. **Format:** "Use short paragraphs. Code in Python unless asked."

```python
def build_system(role, audience, rules, fmt):
    lines = [f"You are {role} for {audience}."]
    lines += [f"- {r}" for r in rules]
    lines.append(f"Format: {fmt}")
    return "\n".join(lines)

print(build_system("a patient Python tutor", "beginners",
                   ["Give hints before full answers.", "Say 'I'm not sure' when unsure."],
                   "short paragraphs, code in Python"))
```

Output:

```text
You are a patient Python tutor for beginners.
- Give hints before full answers.
- Say 'I'm not sure' when unsure.
Format: short paragraphs, code in Python
```

## Long chats get expensive

Because the whole history is resent, **every turn costs more than the last**. Watch the input tokens grow:

::walk python | Why turn 10 costs more than turn 1
per_message = 120
total_in = 0
for turn in range(1, 4):
    sent = (2 * turn - 1) * per_message
    total_in += sent
print("input tokens after 3 turns:", total_in)
::notes
1: Say each message (yours or the model's) is about **120 tokens**. // vars: per_message=120
2: A running total of input tokens billed. // vars: total_in=0
3: Turn 1. // vars: turn=1
4: We send 1 message (the first question): 120 tokens. // vars: sent=120
5: Billed so far: 120. // vars: total_in=120
3: Turn 2. // vars: turn=2
4: Now we send 3 messages: question 1, the reply, question 2: **360** tokens. // vars: sent=360
5: Billed so far: 480. // vars: total_in=480
3: Turn 3. // vars: turn=3
4: Five messages: **600** tokens, five times turn 1. // deep: Input tokens grow linearly per turn, so the total over a conversation grows roughly with the square of its length. // vars: sent=600
5: Billed so far: 1,080. // vars: total_in=1080
6: Three short turns already cost 9× the first message. // out: input tokens after 3 turns: 1080
::end

Ways to keep long chats affordable:

- **Trim:** keep only the last N messages (simple, but the bot forgets early details).
- **Summarise:** replace old messages with a short summary written by a cheap model.
- **Prompt caching:** providers can charge less for a repeated beginning of the prompt (long system prompts benefit most).

```python
def trim(history, keep_last=6):
    return history[-keep_last:]

long_chat = [{"role": "user" if i % 2 == 0 else "assistant", "content": f"msg {i}"} for i in range(20)]
short = trim(long_chat)
print(len(long_chat), "->", len(short), "| first kept:", short[0]["content"], short[0]["role"])
```

Output:

```text
20 -> 6 | first kept: msg 14 user
```

Keep the trimmed history starting with a `user` message, as above: most APIs require the conversation to start with the user.

## Summary

- The API is **stateless**: your app stores and resends the history.
- Roles: `user`, `assistant`, plus a **system prompt** for standing instructions.
- A good system prompt sets role, audience, rules and format.
- Long chats cost more each turn; trim, summarise or cache.
