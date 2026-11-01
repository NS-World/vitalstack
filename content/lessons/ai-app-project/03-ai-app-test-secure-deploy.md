---
title: "Project: Test, Secure and Deploy Your AI App"
slug: ai-app-test-secure-deploy
subject: ai-app-project
lesson: 3
difficulty: intermediate
excerpt: Write automated tests for an AI app with a fake model, keep the API key in Streamlit secrets, add per-user limits and a spending cap, then deploy StudyBuddy online for free.
---

Your app works on your laptop. Before you share it, it needs three things: **tests** (so changes don't break it), **protection** (so nobody drains your API credits) and **hosting** (so others can use it).

::scene Launch day
🧪 | Test | Automated tests run in a second, with a fake model. Every change is checked. | bugsy
🔐 | Secure | The API key lives in hosting secrets, never in GitHub. Each visitor gets a daily limit. | dev
💰 | Cap | A spending cap stops the app before your credits run out. | byte
☁️ | Deploy | Push to GitHub, connect a free host, add the secret, and the app is live. | chip
📣 | Share | You post the link in your class group. Feedback starts coming in. | dev
::end

## 1. Automated tests (no key, no cost)

Put tests in `test_core.py` and run them with `pytest` (or Python's built-in `unittest`). They use a fake model that returns exactly what each test needs, including **bad** model output, which real models produce sometimes:

```python
import json

def valid_questions(quiz, notes):
    return [q for q in quiz.get("questions", [])
            if len(q.get("options", [])) == 4 and q.get("answer") in (0, 1, 2, 3)
            and q.get("quote", "").lower() in notes.lower()]

NOTES = "Mitochondria are the powerhouse of the cell. Ribosomes make proteins."

def q(quote, answer=0, n_opts=4):
    return {"question": "?", "options": ["a", "b", "c", "d"][:n_opts], "answer": answer,
            "explanation": "", "quote": quote}

tests = {
    "keeps a good question": len(valid_questions({"questions": [q("Ribosomes make proteins")]}, NOTES)) == 1,
    "drops an invented quote": valid_questions({"questions": [q("Ribosomes store DNA")]}, NOTES) == [],
    "drops a bad answer index": valid_questions({"questions": [q("Ribosomes make proteins", answer=4)]}, NOTES) == [],
    "drops 3-option questions": valid_questions({"questions": [q("Ribosomes make proteins", n_opts=3)]}, NOTES) == [],
    "survives empty output": valid_questions({}, NOTES) == [],
}
for name, passed in tests.items():
    print("PASS" if passed else "FAIL", name)
```

Output:

```text
PASS keeps a good question
PASS drops an invented quote
PASS drops a bad answer index
PASS drops 3-option questions
PASS survives empty output
```

With pytest, each of these becomes a `def test_...():` function with an `assert`. Add a test every time you find a new way the model's output can go wrong.

## 2. Keep the key secret

Locally, Streamlit reads secrets from `.streamlit/secrets.toml`:

```toml
# .streamlit/secrets.toml  (add this file to .gitignore!)
ANTHROPIC_API_KEY = "your-key"
DAILY_LIMIT = 5
```

```python
import os
import streamlit as st

os.environ.setdefault("ANTHROPIC_API_KEY", st.secrets["ANTHROPIC_API_KEY"])
```

```text
# .gitignore
.streamlit/secrets.toml
.env
__pycache__/
```

On the hosting platform you paste the same values into its **Secrets** settings instead of committing the file.

## 3. Per-visitor limits and a spending cap

Anyone with the link can use your app, so limit it. This version counts per browser session and caps total daily spend; for a bigger app, store counts in a database keyed by a logged-in user.

::walk python | Two safety nets before every AI call
daily_limit = 5
session_count = 5
spent_today = 0.82
cap = 1.00
est_cost = 0.03
blocked = session_count >= daily_limit or spent_today + est_cost > cap
print("blocked:", blocked)
::notes
1: Each visitor gets **5** quizzes a day. // vars: daily_limit=5
2: This visitor has already made 5. // vars: session_count=5
3: The whole app has spent $0.82 today (summed from usage tokens). // vars: spent_today=0.82
4: Hard cap for the app: **$1.00 a day**. // vars: cap=1.0
5: A quiz costs about $0.03 (estimated from tokens × price). // vars: est_cost=0.03
6: Blocked if **either** net catches it: the visitor's limit, or the app's budget. Here the visitor limit triggers. // deep: The visitor limit protects you from one person; the spending cap protects you from many people at once (or a bot). You need both. // vars: blocked=True
7: Show "You've used today's free quizzes. Come back tomorrow!" instead of calling the model. // out: blocked: True
::end

```python
import streamlit as st

LIMIT = int(st.secrets.get("DAILY_LIMIT", 5))
st.session_state.setdefault("used", 0)

if st.button("✨ Make my quiz", type="primary"):
    if st.session_state.used >= LIMIT:
        st.info("You've used today's free quizzes. Come back tomorrow!")
    else:
        st.session_state.used += 1
        # ... make_quiz(...) as before
```

Also set a **monthly spending limit** in the provider's console as the final safety net.

## 4. Deploy for free

Popular options (check each site for current free-plan limits):

| Host | How | Good for |
|---|---|---|
| **Streamlit Community Cloud** | Push to a public GitHub repo → "New app" → pick `app.py` → add secrets | The easiest path for Streamlit apps |
| **Hugging Face Spaces** | Create a Space with the Streamlit option, upload files, add secrets | AI demos and portfolios |
| **Render / Railway** | Connect the repo, set the start command `streamlit run app.py --server.port $PORT`, add environment variables | When you'll add a backend API later |

Your repository needs a `requirements.txt` listing the packages:

```text
streamlit
anthropic
```

## 5. After launch

- Watch the provider's **usage page** daily for the first week.
- Add a "👍 / 👎 this question" button and save feedback: it shows you which prompts need work.
- Keep a list of 20 real notes from users (with permission) as your **evaluation set**, and rerun it before changing models or prompts.

## Your portfolio checklist

- [ ] README with a screenshot, what it does, and how to run it
- [ ] Tests passing (`pytest`)
- [ ] No keys in the repo (search your Git history!)
- [ ] Live link that works
- [ ] A short "What I'd improve next" section (PDF upload, flashcards, spaced repetition)

## Summary

- Test the logic with a fake model, including bad outputs.
- Keys go in Streamlit secrets or host environment variables, never in Git.
- Two safety nets: per-visitor limits and a daily spending cap, plus the console limit.
- Deploy free on Streamlit Community Cloud, Hugging Face Spaces or Render.

🎉 You've built and shipped a real AI app. Add it to your CV and LinkedIn with the live link: projects like this are what interviewers ask about.
