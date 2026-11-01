---
title: "A Chat UI with Streamlit"
slug: deploy-streamlit-chat-ui
subject: deploy-ai-apps
lesson: 1
difficulty: intermediate
excerpt: Build a ChatGPT-style chat page in about 30 lines of Python with Streamlit: chat bubbles, session memory, streaming replies, keeping history short, a per-user limit, secrets, and free deployment on Streamlit Community Cloud.
---

You have a prompt that works. Now people need a page to use it. **Streamlit** turns a Python script into a web app, and it has ready-made chat parts: message bubbles, an input box at the bottom and streaming text. No HTML, CSS or JavaScript needed.

::scene From script to shareable app
🐍 | A script | Dev has a tutor prompt that works in the terminal. Nobody else can use it. | dev
💬 | Bubbles | Chip adds st.chat_message and st.chat_input: it looks like a real chat app. | chip
🧠 | Memory | Byte keeps the conversation in st.session_state, so follow-up questions make sense. | byte
🌍 | Shared | Bugsy deploys it for free and sends the link to the whole class. | bugsy
::end

## The whole app

Save as `app.py`, put your key in `.streamlit/secrets.toml`, then run `streamlit run app.py`:

```python
import streamlit as st
import anthropic

st.set_page_config(page_title="Study Chat", page_icon="💬")
st.title("💬 Study Chat")
client = anthropic.Anthropic(api_key=st.secrets["ANTHROPIC_API_KEY"])

if "messages" not in st.session_state:
    st.session_state.messages = []

for m in st.session_state.messages:                    # redraw the chat so far
    with st.chat_message(m["role"]):
        st.markdown(m["content"])

if prompt := st.chat_input("Ask about any lesson…"):
    st.session_state.messages.append({"role": "user", "content": prompt})
    with st.chat_message("user"):
        st.markdown(prompt)

    def stream():
        with client.messages.stream(model="claude-opus-5-5", max_tokens=1024,
                                    system="You are a friendly tutor for beginners. Keep answers short, with one example.",
                                    messages=st.session_state.messages[-19:]) as s:   # odd number: starts with a user turn
            yield from s.text_stream

    with st.chat_message("assistant"):
        reply = st.write_stream(stream())              # types the answer out live
    st.session_state.messages.append({"role": "assistant", "content": reply})
```

How it works:

- Streamlit **reruns the whole script** on every click or message. `st.session_state` is the box that survives reruns, so the chat history lives there.
- `st.chat_input` returns the text when the user presses Enter (and `None` otherwise).
- `st.write_stream` shows text as it arrives and returns the full reply.

## Keep the history short

Long chats get slow and costly, because the whole history is sent every time. Keep only the most recent messages that fit a budget:

```python
history = [{"role": "user", "content": "What is a list in Python?"},
           {"role": "assistant", "content": "A list is an ordered collection, like [1, 2, 3]."},
           {"role": "user", "content": "And a tuple?"},
           {"role": "assistant", "content": "A tuple is like a list you cannot change, like (1, 2, 3)."},
           {"role": "user", "content": "When should I use which?"}]

def trim(history, max_words=30):
    kept, words = [], 0
    for m in reversed(history):
        w = len(m["content"].split())
        if words + w > max_words:
            break
        kept.insert(0, m)
        words += w
    while kept and kept[0]["role"] != "user":         # the conversation must start with the user
        kept.pop(0)
    return kept

print([m["content"][:20] for m in trim(history)])
```

Output:

```text
['And a tuple?', 'A tuple is like a li', 'When should I use wh']
```

Real apps count tokens instead of words, and often replace the old part with a short summary.

## A limit per user

A public AI page without limits can run up a big bill overnight. Count messages per user:

::walk python | A simple message limit
from collections import defaultdict
used = defaultdict(int)
LIMIT = 3
def allow(user):
    used[user] += 1
    return used[user] <= LIMIT
print([allow("asha") for _ in range(4)])
::notes
1: defaultdict gives every new user a starting count of 0.
2: Messages used per user. // vars: used={}
3: Three free messages (per day, in a real app). // vars: LIMIT=3
4: Called before every model request.
5: Count this message. // vars: used={'asha': 4}
6: Allowed while the count is within the limit. // deep: st.session_state resets when the user reloads the page, so a real limit must be stored on the server: a database or Redis, keyed by login or IP address, with a daily reset.
7: Asha's fourth message is refused. Show "Daily limit reached: come back tomorrow, or upgrade." // out: [True, True, True, False]
::end

## Deploying for free

1. Push `app.py` and a `requirements.txt` (`streamlit`, `anthropic`) to a GitHub repository. Never commit `secrets.toml`.
2. On Streamlit Community Cloud, create an app from that repository.
3. Paste your key into the app's **Secrets** settings.
4. Share the link. Every push to GitHub redeploys the app.

Hugging Face Spaces is another free option for demos.

## Nice extras

| Feature | Streamlit piece |
|---|---|
| Clear chat button | `st.sidebar.button("New chat")` → reset `st.session_state.messages` |
| Pick a level | `st.sidebar.selectbox("Level", ["Beginner", "Intermediate"])` → add to the system prompt |
| Upload notes | `st.file_uploader` → send text as context (see the RAG module) |
| Login | Streamlit's built-in authentication options, or put the app behind your site login |

::tip Add a small line under the input: "AI can make mistakes. Check important answers." It sets honest expectations and builds trust with users.

## Summary

- `st.chat_message`, `st.chat_input` and `st.write_stream` build a chat UI in a few lines.
- `st.session_state` keeps the history across Streamlit's reruns.
- Trim history to control cost, and store per-user limits on the server.
- Deploy free from GitHub, with keys in the platform's secrets settings.
