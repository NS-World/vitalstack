---
title: "Long-Term Memory and Summaries"
slug: memory-long-term-summaries
subject: agent-memory
lesson: 2
difficulty: intermediate
excerpt: Help agents remember across sessions: running summaries, a facts store that updates instead of duplicating, extracting memories with an LLM, deciding what is worth remembering, and letting users see and delete what's stored.
---

Close the chat, come back next week: does the tutor remember that you prefer Hinglish and struggle with window functions? That needs **long-term memory**: information saved outside the model, then loaded into the context when it's useful. Two simple forms cover most apps: **summaries** of past sessions and a **facts store** about the user.

::scene The tutor's notebook
💬 | Session 1 | Meera says her goal is a data analyst job and she likes Hinglish. | dev
📓 | Notebook | After the chat, Byte writes three facts into Meera's notebook. | byte
🔁 | Update | Two weeks later she moves to Bengaluru. Chip updates "city", not adds a second one. | chip
🗑️ | Her choice | Meera asks "forget my city". Bugsy deletes it and confirms. | bugsy
::end

## Running summaries

At the end of a session (or when the context gets long), condense it. The summariser here is a stand-in for an LLM call:

```python
def summarise(turns):                    # in a real app: one LLM call with a "summarise key facts" prompt
    keep = [t for t in turns if any(w in t.lower() for w in ("goal", "prefer", "weak", "deadline"))]
    return " ".join(keep)

turns = ["Hi! How are you today?", "I am good, a bit nervous about interviews honestly.", "My goal is a data analyst job.",
         "Can you explain that again, more slowly please?", "I prefer Hinglish explanations.", "Thanks, that was really helpful!",
         "My weak area is window functions.", "ok cool, let us take a short break now", "Deadline is 3 weeks."]
summary = summarise(turns)
print(len(" ".join(turns).split()), "words ->", len(summary.split()), "words:", summary)
```

Output:

```text
57 words -> 21 words: My goal is a data analyst job. I prefer Hinglish explanations. My weak area is window functions. Deadline is 3 weeks.
```

Next session, put the summary in the system prompt: "What you know about this learner: …".

## A facts store that updates

Summaries pile up. For stable facts, keep one value per key and **update** it:

```python
memory = {}

def remember(key, value, day):
    old = memory.get(key)
    memory[key] = {"value": value, "day": day}
    return f"updated {key}: {old['value']} -> {value}" if old else f"saved {key}: {value}"

print(remember("city", "Pune", 1))
print(remember("goal", "data analyst job", 1))
print(remember("city", "Bengaluru", 9))
print({k: v["value"] for k, v in memory.items()})
```

Output:

```text
saved city: Pune
saved goal: data analyst job
updated city: Pune -> Bengaluru
{'city': 'Bengaluru', 'goal': 'data analyst job'}
```

::walk python | Merging new facts into old ones
facts = {"city": "Pune", "level": "beginner"}
new = {"city": "Bengaluru", "language": "Hinglish"}
changed = [k for k in new if k in facts and facts[k] != new[k]]
facts.update(new)
print(changed, facts)
::notes
1: What we already know. // vars: facts={'city': 'Pune', 'level': 'beginner'}
2: Facts extracted from today's chat. // vars: new={'city': 'Bengaluru', 'language': 'Hinglish'}
3: Which known facts changed? Worth logging, or confirming with the user. // deep: Contradictions are where memory systems go wrong. If the user says "I'm in Pune" today and "I'm in Bengaluru" tomorrow, the newest usually wins, but for important facts the agent can ask: "Have you moved to Bengaluru?" // vars: changed=['city']
4: Newer values replace old ones; new keys are added.
5: City updated, level kept, language added. // out: ['city'] {'city': 'Bengaluru', 'level': 'beginner', 'language': 'Hinglish'}
::end

## Extracting facts with an LLM

```python
import json
import anthropic

client = anthropic.Anthropic()
EXTRACT = ("From the conversation, extract long-term facts about the user that will help future tutoring: "
           "goal, level, preferred language, weak topics, deadlines. Reply with JSON only, e.g. "
           '{"goal": "...", "weak_topics": ["..."]}. Skip anything sensitive (health, money, passwords, IDs).')

def extract_facts(transcript):
    r = client.messages.create(model="claude-opus-5-5", max_tokens=300, system=EXTRACT,
                               messages=[{"role": "user", "content": transcript}])
    return json.loads(r.content[0].text)
```

Some APIs (Claude's included) also provide a **memory tool**: the model itself decides to read and write memory files, which your code stores wherever you like.

## What's worth remembering?

| Remember | Don't remember (without clear consent) |
|---|---|
| Goals, level, preferred language and style | Health, religion, money details |
| Weak topics and finished lessons | Passwords, OTPs, ID numbers |
| Deadlines and constraints | One-off chit-chat |
| Explicit "please remember…" requests | Anything the user asked to forget |

## Users stay in control

- Show a "What the tutor remembers about you" page.
- Let users edit or delete any fact, and "forget everything".
- Say clearly that memory exists and what it's used for.

This builds trust, and it's what privacy laws (like India's DPDP Act) expect.

::tip Load only the memories relevant to today's task, not the whole notebook. Ten well-chosen facts beat two hundred random ones in the context.

## Summary

- Long-term memory lives outside the model and is loaded into the context when useful.
- Use running summaries for past sessions and a facts store (one value per key) for stable facts.
- Extract facts with an LLM, skip sensitive data, and handle contradictions.
- Let users see, edit and delete what's remembered.
