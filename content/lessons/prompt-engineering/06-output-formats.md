---
title: Tables, Lists and JSON: Controlling the Output
slug: prompt-output-formats
subject: prompt-engineering
lesson: 6
difficulty: intermediate
excerpt: Get answers in exactly the shape you need: tables for comparisons, checklists, word limits, and JSON that a program can read.
---

An answer in the right **shape** saves you more time than an answer that's slightly smarter. If you'll paste it into Excel, ask for a table. If a program will read it, ask for JSON. Say the format and the AI follows it.

## Tables for comparisons

```text
Compare Python, Java and JavaScript for a beginner choosing a first language.
Make a table with rows: Main uses, Learning curve, Jobs in India, Best first project.
Keep each cell under 12 words.
```

| | Python | Java | JavaScript |
|---|---|---|---|
| Main uses | Data, AI, automation | Enterprise apps, Android | Websites, web apps |
| … | … | … | … |

Tables copy straight into Excel or Google Sheets.

## Lists and checklists

```text
Give me a checklist (with ☐ boxes) for preparing for a campus placement
drive, grouped into: 1 month before, 1 week before, the day before.
```

## Length and level

Vague: "short". Precise: "under 80 words", "3 bullet points", "one paragraph", "explain like I'm 12". Precise limits are followed far more reliably.

## Exact templates

When the format must be exact, give the template:

```text
Write a product description for a steel water bottle using exactly this format:

**Name:** (5 words max)
**Best for:** (one line)
**3 features:**
- …
- …
- …
**Price note:** (one line, no numbers)
```

## JSON for programs

**JSON** is the format programs use to exchange data. If you're building something, or need data you can load into Pandas, ask for JSON and show the exact keys:

```text
Extract the details from this message and return only JSON, no other text.
Use exactly these keys: name, phone, city, course, start_month.
Use null for anything not mentioned.

Message: "Hi, I'm Rohan from Indore, 98xxxxxx12, interested in your
data science course starting in January."
```

The model should reply with something like:

```text
{
  "name": "Rohan",
  "phone": "98xxxxxx12",
  "city": "Indore",
  "course": "data science",
  "start_month": "January"
}
```

And Python can read it directly:

```python
import json

reply = '{"name": "Rohan", "phone": "98xxxxxx12", "city": "Indore", "course": "data science", "start_month": "January"}'
lead = json.loads(reply)
print(lead["city"], lead["course"])
```

Output:

```text
Indore data science
```

::tip When you build apps, most AI APIs (OpenAI, Anthropic, Google) offer a **structured output** or JSON mode that guarantees valid JSON matching a schema. In the chat window, "return only JSON with these keys" plus an example is usually enough.

## Code in the format you need

```text
Write a Python function that checks whether a password is strong.
Rules: 8+ characters, one digit, one uppercase letter, one symbol.
Give only the code with comments, then 4 example calls with expected output.
```

::hint Quick check | You need 50 interview questions in a spreadsheet with columns Question, Topic, Difficulty. How do you ask? "Return a table (or CSV) with columns Question, Topic, Difficulty" and say how many rows. CSV pastes straight into Excel.

## Summary

- Name the shape: table, checklist, template, word limit.
- Give the exact template when the format must be exact.
- For programs, ask for JSON with named keys and `null` for missing values.
