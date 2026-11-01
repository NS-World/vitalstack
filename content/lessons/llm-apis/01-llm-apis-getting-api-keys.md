---
title: "Getting API Keys: OpenAI (ChatGPT), Claude, Gemini and Grok, Plus Free Options"
slug: llm-apis-getting-api-keys
subject: llm-apis
lesson: 1
difficulty: intermediate
excerpt: Step by step, how to create a developer account, add credits and get an API key from OpenAI, Anthropic, Google and xAI, what it costs, which free options exist, and how to store the key safely.
---

To use ChatGPT, Claude, Gemini or Grok **from your own code**, you need an **API key**: a long secret string that identifies your account and lets the provider bill you. This lesson walks through getting one from each major provider, the free options for learning, and the one rule you must never break.

::scene Your first API key
🧑‍💻 | Step 1 | Create a developer account on the provider's console. It's separate from the chat app. | dev
💳 | Step 2 | Add billing: usually prepaid credits on a card. Some providers have a free tier for testing. | byte
🔑 | Step 3 | Click "Create API key". You'll see the full key only once, so copy it now. | chip
🧰 | Step 4 | Save it in an environment variable or a .env file, never inside your code. | bugsy
🧪 | Step 5 | Run a tiny test request and check the usage page: a few paise, not a few thousand rupees. | dev
::end

## Chat subscription ≠ API access

A ChatGPT Plus, Claude Pro, Gemini or SuperGrok subscription pays for the **chat app**. API usage is a **separate account and bill**, charged per token. Students often pay for the app and are surprised the API still asks for credits; now you know why.

## The four big providers

The steps are similar everywhere. Console layouts change often, so if a button has moved, look for "API keys" and "Billing" in the console menu.

| | OpenAI (GPT, the models behind ChatGPT) | Anthropic (Claude) | Google (Gemini) | xAI (Grok) |
|---|---|---|---|---|
| Where | platform.openai.com | Anthropic's developer console (via anthropic.com → API) | Google AI Studio (aistudio.google.com) | console.x.ai |
| Billing | Prepaid credits | Prepaid credits | **Free tier** with rate limits; paid usage through Google Cloud billing | Prepaid credits |
| Environment variable | `OPENAI_API_KEY` | `ANTHROPIC_API_KEY` | `GEMINI_API_KEY` | `XAI_API_KEY` |
| Python package | `pip install openai` | `pip install anthropic` | `pip install google-genai` | `pip install openai` (xAI's API is OpenAI-compatible) |

**Paying from India:** consoles usually accept international credit and debit cards. Some Indian debit cards (and RuPay cards) are declined for international online payments; enabling international usage in your bank app or using a credit card usually fixes it. Taxes such as GST may be added to the invoice.

::tip Start small: add the minimum credit amount, then set a **monthly spending limit** or budget alert in the console. A bug in a loop can make thousands of requests in minutes.

## Free and cheap ways to learn

You don't need to spend money to learn API programming:

1. **Gemini free tier** (Google AI Studio): enough requests for learning projects, with rate limits. Free-tier data may be used to improve Google's products, so don't send private data.
2. **Open-weight models on your own computer** with tools like **Ollama** or **LM Studio**: download a small Llama, Mistral, Qwen or Gemma model and call it from Python with no key and no cost. A laptop with 8–16 GB of RAM can run small models.
3. **A fake client** for testing your code (lesson 2): most of your app's logic can be tested without any real API call at all.
4. Some hosting platforms offer free trial credits for open models. Note that **Groq** (a fast inference company, with a "q") is different from **Grok** (xAI's model).

## Storing the key: the one rule

> **Never put an API key in your code, a screenshot, a public GitHub repository or a frontend (JavaScript that runs in the browser).**

Bots scan GitHub for leaked keys within minutes and spend your credits. Keep keys in environment variables:

```bash
# macOS / Linux (current terminal only)
export ANTHROPIC_API_KEY="your-key-here"

# Windows PowerShell
$env:ANTHROPIC_API_KEY = "your-key-here"
```

Or put them in a `.env` file that is listed in `.gitignore`, and load it with the `python-dotenv` package. Your code then reads the key from the environment:

```python
import os

os.environ["DEMO_API_KEY"] = "sk-demo-1234567890abcdef"   # pretend it was set in the terminal

key = os.environ.get("DEMO_API_KEY")
if not key:
    raise SystemExit("Set DEMO_API_KEY first")
print("key loaded:", key[:7] + "…" + key[-4:])
```

Output:

```text
key loaded: sk-demo…cdef
```

Print only a masked version when debugging, never the full key.

## Check your key with a mini "validator"

Each provider's keys have a recognisable prefix. This helper catches the common mistake of pasting the wrong key into the wrong variable:

```python
PREFIXES = {"OPENAI_API_KEY": "sk-", "ANTHROPIC_API_KEY": "sk-ant-", "XAI_API_KEY": "xai-"}

def check(var, value):
    expected = PREFIXES.get(var)
    if expected and not value.startswith(expected):
        return f"{var}: doesn't look right (expected it to start with '{expected}')"
    if len(value) < 20:
        return f"{var}: too short, probably copied only part of it"
    return f"{var}: looks OK"

print(check("ANTHROPIC_API_KEY", "sk-proj-abc123def456ghi789"))
print(check("XAI_API_KEY", "xai-123"))
print(check("OPENAI_API_KEY", "sk-proj-abc123def456ghi789"))
```

Output:

```text
ANTHROPIC_API_KEY: doesn't look right (expected it to start with 'sk-ant-')
XAI_API_KEY: too short, probably copied only part of it
OPENAI_API_KEY: looks OK
```

Key formats can change, so treat this as a sanity check, not a guarantee. Google AI Studio keys have their own format.

## If a key leaks

1. **Revoke it immediately** in the console (delete or disable the key).
2. Create a new key and update your environment.
3. Check the usage page for unexpected spending, and contact the provider's support if you see abuse.
4. Remove the key from your Git history; deleting the file in a new commit is not enough.

## Summary

- API access is a **separate** account and bill from chat subscriptions.
- OpenAI, Anthropic and xAI use prepaid credits; Google's Gemini has a free tier.
- Learn for free with the Gemini free tier, local open models (Ollama, LM Studio) or a fake client.
- Keys live in **environment variables**, never in code; set spending limits; revoke leaked keys at once.

Next: your first real API call to each of the four providers, and a provider-neutral function you can test without spending anything.
