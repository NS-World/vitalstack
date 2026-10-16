---
title: Python JSON and APIs
slug: python-json-and-apis
subject: python
lesson: 19
difficulty: intermediate
excerpt: Convert between Python and JSON, save and load JSON files, call web APIs with urllib and requests, send data with POST, handle errors and keep API keys secret.
---

Most data on the internet travels as **JSON**, and most online services (weather, payments, maps, AI models) are used through **APIs**. Python is excellent at both. In this lesson you'll convert data to and from JSON, save it in files, and talk to real web APIs.

## JSON and Python

JSON looks almost exactly like Python dictionaries and lists:

```json
{
  "name": "Asha",
  "age": 21,
  "skills": ["Python", "SQL"],
  "active": true,
  "manager": null
}
```

| JSON | Python |
|---|---|
| object `{}` | `dict` |
| array `[]` | `list` |
| string | `str` |
| number | `int` / `float` |
| `true` / `false` | `True` / `False` |
| `null` | `None` |

## Python → JSON text: dumps

```python
import json

user = {"name": "Asha", "age": 21, "skills": ["Python", "SQL"], "active": True, "manager": None}

text = json.dumps(user)
print(text)
print(type(text))          # str

pretty = json.dumps(user, indent=2, ensure_ascii=False)
print(pretty)
```

- `json.dumps` ("dump string") converts Python data to a JSON string.
- `indent=2` makes it readable.
- `ensure_ascii=False` keeps characters like ₹ or Hindi readable instead of `₹` escapes.

Not everything can be converted: sets, dates and custom objects raise a `TypeError`. Convert them first (`list(my_set)`, `my_date.isoformat()`).

## JSON text → Python: loads

```python
import json

text = '{"product": "Notebook", "price": 120, "tags": ["paper", "school"]}'
data = json.loads(text)

print(data["product"], data["price"] * 2)
print(data["tags"][0])
```

Invalid JSON raises `json.JSONDecodeError`; catch it when the text comes from outside your program.

## Saving and loading JSON files

```python
import json

settings = {"theme": "dark", "font_size": 18, "recent": ["lesson-1", "lesson-2"]}

with open("settings.json", "w", encoding="utf-8") as f:
    json.dump(settings, f, indent=2)

with open("settings.json", encoding="utf-8") as f:
    loaded = json.load(f)

print(loaded == settings)   # True
print(loaded["recent"])
```

`dump`/`load` (without the "s") work directly with files. JSON files are a simple, human-readable way to store settings and small amounts of data, and you'll use them in the project lesson.

## What is an API?

A web **API** is a set of URLs that return data for programs. You send an HTTP **request**; the server sends back a **response** with a **status code** and usually a JSON body.

| Status | Meaning |
|---|---|
| 200 | OK |
| 201 | Created |
| 400 | Bad request (your data was wrong) |
| 401 / 403 | Not authenticated / not allowed |
| 404 | Not found |
| 429 | Too many requests (slow down) |
| 500 | Server error |

We'll practise with **JSONPlaceholder** (jsonplaceholder.typicode.com), a free fake API for learning.

## Calling an API with the standard library

No installation needed:

```python
import json
from urllib.request import urlopen

with urlopen("https://jsonplaceholder.typicode.com/users/1", timeout=10) as response:
    user = json.load(response)

print(user["name"])
print(user["address"]["city"])
```

This works, but error handling and sending data are clumsy with `urllib`. Most developers use the `requests` package instead.

## Calling an API with requests

Install it inside your virtual environment first: `pip install requests`.

```python
import requests

response = requests.get(
    "https://jsonplaceholder.typicode.com/posts",
    params={"userId": 1},
    timeout=10,
)

print(response.status_code)          # 200
posts = response.json()              # parse the JSON body
print(len(posts), "posts")
for post in posts[:3]:
    print("-", post["title"])
```

- `params=` builds the query string (`?userId=1`) for you.
- `timeout=` stops your program waiting forever if the server doesn't answer. Always set it.
- `response.json()` parses the body into Python data.

## Handling errors properly

```python
import requests

def fetch_user(user_id):
    try:
        response = requests.get(
            f"https://jsonplaceholder.typicode.com/users/{user_id}", timeout=10
        )
        response.raise_for_status()           # error for 4xx and 5xx
        return response.json()
    except requests.Timeout:
        print("The server took too long.")
    except requests.HTTPError as error:
        print(f"HTTP error: {error.response.status_code}")
    except requests.RequestException as error:
        print("Network problem:", error)
    return None

print(fetch_user(2)["name"])
print(fetch_user(9999))                      # 404 → prints an error, returns None
```

Networks fail. A robust program expects that and responds gracefully.

## Sending data: POST

```python
import requests

new_post = {"title": "Learning APIs", "body": "Python makes this easy.", "userId": 1}

response = requests.post(
    "https://jsonplaceholder.typicode.com/posts",
    json=new_post,          # converts to JSON and sets the Content-Type header
    timeout=10,
)

print(response.status_code)   # 201
print(response.json())        # the server's copy, with a new id
```

`json=` does the conversion for you. Other methods: `requests.put`, `requests.patch`, `requests.delete`.

## API keys and secrets

Real APIs (weather, maps, payments, AI) usually need a **key** to identify you, sent in a header:

```python
import os

api_key = os.environ.get("WEATHER_API_KEY")
if not api_key:
    print("Set the WEATHER_API_KEY environment variable first.")
else:
    headers = {"Authorization": f"Bearer {api_key}"}
    # response = requests.get("https://api.example.com/weather", headers=headers,
    #                         params={"city": "Pune"}, timeout=10)
```

**Never write keys directly in your code**, especially code you put on GitHub. Bots scan public repositories for keys within minutes. Keep them in **environment variables** (or a `.env` file listed in `.gitignore`, loaded with the `python-dotenv` package).

## Being a good API citizen

- Read the API's documentation and **rate limits**. Getting a `429` means slow down; `time.sleep()` between requests helps.
- **Cache** results you reuse instead of requesting them again.
- Respect the terms of use, especially for scraping or storing data.

## A complete small example

```python
import json
import requests

def top_todos(user_id, limit=5):
    response = requests.get(
        "https://jsonplaceholder.typicode.com/todos",
        params={"userId": user_id},
        timeout=10,
    )
    response.raise_for_status()
    todos = response.json()
    done = sum(1 for t in todos if t["completed"])
    summary = {
        "user_id": user_id,
        "total": len(todos),
        "completed": done,
        "open_titles": [t["title"] for t in todos if not t["completed"]][:limit],
    }
    with open(f"user_{user_id}_summary.json", "w", encoding="utf-8") as f:
        json.dump(summary, f, indent=2)
    return summary

print(json.dumps(top_todos(1), indent=2))
```

Fetch → process → save as JSON: the shape of countless real automation scripts.

## Common mistakes

- **Confusing `dumps`/`loads` (strings) with `dump`/`load` (files).**
- **Single quotes in JSON**: `{'a': 1}` is valid Python but invalid JSON.
- **No timeout**, so the program hangs.
- **Not checking the status code** before using `response.json()`.
- **API keys in source code.**

## Exercise

1. Save a dictionary of your favourite books to `books.json` and load it back.
2. Fetch `https://jsonplaceholder.typicode.com/users` and print each user's name and email.
3. Count how many todos are completed for user 3.
4. Send a POST request creating a new todo and print the response.
5. Handle the error when requesting `/users/9999`.

## Summary

- `json.dumps`/`loads` convert between Python and JSON text; `dump`/`load` work with files.
- APIs return JSON over HTTP; check status codes and always set timeouts.
- `requests` makes GET and POST calls easy; `raise_for_status()` turns HTTP errors into exceptions.
- Keep API keys in environment variables, never in code.

Next, you'll build a complete expense tracker that brings the whole Python course together.
