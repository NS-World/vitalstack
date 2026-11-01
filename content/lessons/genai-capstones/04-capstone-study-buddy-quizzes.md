---
title: "Capstone: AI Study Buddy that Makes Quizzes from Notes"
slug: capstone-study-buddy-quizzes
subject: genai-capstones
lesson: 4
difficulty: intermediate
excerpt: Turn notes into a smart revision coach: generate quiz questions as JSON, validate every question, grade answers, track weak topics, and schedule reviews with spaced repetition (Leitner boxes).
---

Most quiz generators stop at "here are 5 questions". A real study buddy **remembers** what you got wrong and asks it again at the right time. This capstone adds the two things that make learning stick: **validated questions** and **spaced repetition**.

::scene The revision coach
📒 | Notes in | Dev pastes notes on Python lists and dictionaries. | dev
❓ | Quiz out | Chip generates 5 multiple-choice questions as JSON. Byte throws out one broken question. | chip
❌ | Weak spot | Bugsy gets the dictionary questions wrong twice. | bugsy
📅 | Comes back | Those cards return tomorrow; the easy ones wait a week. Learning sticks. | byte
::end

## Step 1: generate questions as JSON

```python
import json
import anthropic

client = anthropic.Anthropic()
PROMPT = ("Make 5 multiple-choice questions from the notes. Reply with only JSON: a list of objects with "
          "keys question, options (4 strings), answer (index 0-3), topic, explanation. "
          "Questions must be answerable from the notes alone.")

def make_quiz(notes):
    r = client.messages.create(model="claude-opus-5-5", max_tokens=1500, system=PROMPT,
                               messages=[{"role": "user", "content": f"<notes>{notes}</notes>"}])
    return json.loads(r.content[0].text)
```

For stricter results, use structured output with a JSON schema (Working with LLM APIs module).

## Step 2: validate every question

Generated questions are sometimes broken: 3 options, an answer index out of range, duplicate options. Check before showing:

```python
quiz = [
    {"question": "Which brackets make a list?", "options": ["()", "[]", "{}", "<>"], "answer": 1, "topic": "lists"},
    {"question": "How do you read a key safely?", "options": ["d[k]", "d.get(k)", "d.key(k)"], "answer": 1, "topic": "dicts"},
    {"question": "Are lists mutable?", "options": ["Yes", "No", "Only empty ones", "Yes"], "answer": 0, "topic": "lists"},
    {"question": "What does len({'a': 1, 'b': 2}) return?", "options": ["1", "2", "3", "4"], "answer": 7, "topic": "dicts"},
    {"question": "Which method adds one item to a list?", "options": ["add", "append", "push", "insert_end"], "answer": 1, "topic": "lists"},
]

def problems(q):
    found = []
    if len(q["options"]) != 4:
        found.append("needs 4 options")
    if len(set(q["options"])) != len(q["options"]):
        found.append("duplicate options")
    if not 0 <= q["answer"] < len(q["options"]):
        found.append("answer index out of range")
    return found

good = []
for q in quiz:
    issues = problems(q)
    if issues:
        print("dropped:", q["question"][:30], issues)
    else:
        good.append(q)
print(len(good), "questions kept")
```

Output:

```text
dropped: How do you read a key safely? ['needs 4 options']
dropped: Are lists mutable? ['duplicate options']
dropped: What does len({'a': 1, 'b': 2} ['answer index out of range']
2 questions kept
```

Ask the model to regenerate the dropped ones, or just show the good ones.

## Step 3: track weak topics

```python
answers = [("lists", True), ("dicts", False), ("lists", True), ("dicts", False), ("dicts", True)]
stats = {}
for topic, ok in answers:
    right, total = stats.get(topic, (0, 0))
    stats[topic] = (right + ok, total + 1)
for topic, (right, total) in stats.items():
    print(f"{topic}: {right}/{total} = {right / total:.0%}")
```

Output:

```text
lists: 2/2 = 100%
dicts: 1/3 = 33%
```

Dictionaries need work. The buddy can now generate extra dictionary questions, or explain the topic again in simpler words.

## Step 4: spaced repetition with Leitner boxes

Every card lives in a box. Right answer → move up a box (review later). Wrong → back to box 1 (review tomorrow):

::walk python | Moving a card between boxes
INTERVAL_DAYS = {1: 1, 2: 3, 3: 7, 4: 21}
box = 2
for correct in [True, False, True]:
    box = min(box + 1, 4) if correct else 1
print(box, "next review in", INTERVAL_DAYS[box], "days")
::notes
1: Box number → days until the next review. Higher boxes wait longer. // vars: INTERVAL_DAYS={1: 1, 2: 3, 3: 7, 4: 21}
2: This card starts in box 2. // vars: box=2
3: Three reviews: right, wrong, right.
4: Right → up one box (max 4). Wrong → straight back to box 1. // deep: This simple rule follows the "spacing effect": reviewing just before you forget is the most efficient way to remember. Apps like Anki use a more detailed version of the same idea. // vars: box=3 → 1 → 2
5: The mistake reset the card; one right answer moved it to box 2, so it's back in 3 days. // out: 2 next review in 3 days
::end

## Make it portfolio-ready

- **UI:** Streamlit page with "Paste notes", "Start quiz", a score card and a "Due today" list.
- **Storage:** SQLite table of cards (question, box, next_review_date) per user.
- **Explain mistakes:** after a wrong answer, show the explanation and offer "Explain simpler".
- **Hindi/Hinglish mode:** one line in the system prompt.
- **Eval:** 30 generated questions checked by hand; report the % valid and % answerable from the notes.

::tip Make the buddy fun: streaks, a "boss question" at the end of each session, and Chip celebrating a 5-in-a-row. Small game elements keep learners coming back every day.

## Summary

- Generate quiz questions as JSON and validate each one before use.
- Track accuracy per topic to find weak spots.
- Spaced repetition (Leitner boxes) schedules each card at the right time.
- Package it with storage, a UI and an eval for a strong portfolio project.

🎉 You've finished **Capstone Projects** and the whole **Gen AI Developer** track. Amazing work!
