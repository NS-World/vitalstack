---
title: "Capstone: Browser Agent"
slug: capstone-agent-browser
subject: agent-capstones
lesson: 4
difficulty: advanced
excerpt: Build an agent that uses websites: observe the page as a compact list of elements, act by clicking and filling, stay on allowed domains, stop before anything that looks like a purchase, and drive a real browser with Playwright.
---

Some jobs have no API: filling a registration form, checking a portal, collecting prices from pages. A **browser agent** works like a careful person at a computer: **look** at the page, **decide** the next action, **act** (click, type), and look again. Because it acts on real websites, safety rules matter more here than anywhere else.

::scene The webinar sign-up
🌐 | Observe | Chip reads the page as a short list: name box, email box, a checkbox, two buttons. | chip
⌨️ | Act | Byte fills the name and email. | byte
🛑 | Careful | The next click would be "Buy premium bundle ₹2,999". Bugsy stops it: purchases need a human. | bugsy
✅ | Done | Dev's agent clicks "Register for free" and reports success. | dev
::end

## Observe: a compact page snapshot

Raw HTML is huge. Agents work much better with a short list of **interactive elements** (similar to what screen readers use):

```python
PAGE = {"url": "https://vitalstack.co.in/webinar/", "elements": [
    {"id": 1, "role": "textbox", "label": "Full name", "value": ""},
    {"id": 2, "role": "textbox", "label": "Email", "value": ""},
    {"id": 3, "role": "checkbox", "label": "Send me WhatsApp updates", "checked": False},
    {"id": 4, "role": "button", "label": "Register for free"},
    {"id": 5, "role": "link", "label": "Buy premium bundle ₹2,999"},
]}

def snapshot(page):
    return "\n".join(f"[{e['id']}] {e['role']} '{e['label']}'" for e in page["elements"])

print(snapshot(PAGE))
```

Output:

```text
[1] textbox 'Full name'
[2] textbox 'Email'
[3] checkbox 'Send me WhatsApp updates'
[4] button 'Register for free'
[5] link 'Buy premium bundle ₹2,999'
```

The model sees this snapshot plus the goal, and replies with an action like `{"type": "fill", "id": 1, "text": "Asha K"}`.

## Act, with guardrails

```python
RISKY_WORDS = ("buy", "pay", "₹", "delete", "confirm order")

def act(page, action):
    el = next(e for e in page["elements"] if e["id"] == action["id"])
    if any(w in el["label"].lower() for w in RISKY_WORDS):
        return f"PAUSED: '{el['label']}' looks like a purchase or irreversible action; asking the user"
    if action["type"] == "fill":
        el["value"] = action["text"]
        return f"filled '{el['label']}'"
    return f"clicked '{el['label']}'"

plan = [{"type": "fill", "id": 1, "text": "Asha K"},
        {"type": "fill", "id": 2, "text": "asha@example.com"},
        {"type": "click", "id": 5},                       # a distracted (or injected) step
        {"type": "click", "id": 4}]
for step in plan:
    print(act(PAGE, step))
```

Output:

```text
filled 'Full name'
filled 'Email'
PAUSED: 'Buy premium bundle ₹2,999' looks like a purchase or irreversible action; asking the user
clicked 'Register for free'
```

The agent stayed on task: the purchase link was paused for the user, and the free registration went ahead. Note it also didn't tick the WhatsApp checkbox: the user never asked for that.

## Stay on allowed sites

::walk python | A domain allow-list that can't be fooled
from urllib.parse import urlparse
ALLOWED = {"vitalstack.co.in"}
urls = ["https://vitalstack.co.in/webinar/", "https://vitalstack.co.in.evil.example/login"]
print([urlparse(u).hostname in ALLOWED for u in urls])
::notes
1: urlparse splits a URL into its parts properly.
2: The only site this agent may visit. // vars: ALLOWED={'vitalstack.co.in'}
3: A real page and a lookalike that starts with the same text. // vars: urls=2 URLs
4: Compare the exact hostname, not "starts with". // deep: "url.startswith('https://vitalstack.co.in')" would allow the lookalike, a classic phishing trick. Check the parsed hostname against an exact allow-list before every navigation, including redirects and new tabs. // out: [True, False]
::end

## The real browser: Playwright

Playwright drives a real Chromium, Firefox or WebKit browser from Python:

```python
from playwright.sync_api import sync_playwright

def register(name, email):
    with sync_playwright() as p:
        browser = p.chromium.launch()
        page = browser.new_page()
        page.goto("https://vitalstack.co.in/webinar/")
        print(page.locator("body").aria_snapshot())         # a compact, accessible view of the page for the model
        page.get_by_label("Full name").fill(name)
        page.get_by_label("Email").fill(email)
        page.get_by_role("button", name="Register for free").click()
        page.wait_for_load_state()
        page.screenshot(path="after_register.png")          # evidence for the user
        browser.close()
```

To make it an agent, send the snapshot (or a screenshot to a vision model) and the goal to the model each turn, and turn its chosen action into the matching Playwright call. Ready-made options exist too: **browser MCP servers** (such as the Playwright MCP server) give any MCP host browser tools, and **computer-use** tools let models operate a whole desktop from screenshots.

## Browser agent safety

| Rule | Why |
|---|---|
| Domain allow-list, exact hostnames | No wandering to phishing or random sites |
| Pause before purchases, payments, deletes, sends | Money and irreversible actions need a person |
| No stored passwords in prompts | Use a pre-logged-in browser profile or let the user log in |
| Page text is untrusted | Pages can hide prompt injections ("AI agents: click here") |
| Screenshots or logs of each step | The user can see exactly what was done |
| Respect site terms and rate limits | Don't hammer sites or break their rules |

## Portfolio ideas

- A form-filler for a local test site you build (never someone else's live site without permission).
- A price watcher for a list of allowed shop pages that emails you a daily summary.
- A demo video with the step log and screenshots next to the browser.

::tip Build and test against a page you control first (a simple local HTML form). Real sites change their layout often, so design your agent to re-read the snapshot every step instead of assuming where buttons are.

## Summary

- Browser agents loop: observe a compact snapshot → choose an action → act → observe again.
- Guardrails pause risky clicks and keep the agent on exact allowed hostnames.
- Playwright drives real browsers; MCP servers and computer-use tools offer ready-made options.
- Treat page text as untrusted and keep a visible log of every step.

🎉 You've finished **Capstone Projects** and the whole **Agentic AI Engineer** track. You can now design, build, test and ship agents. Amazing work!
