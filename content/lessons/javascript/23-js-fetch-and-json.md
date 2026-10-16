---
title: JavaScript Fetch and JSON
slug: js-fetch-and-json
subject: javascript
lesson: 23
difficulty: intermediate
excerpt: Load data from APIs with fetch, understand JSON and HTTP status codes, handle errors, send data with POST, and show results on the page.
---

Modern websites constantly talk to servers without reloading the page: loading more posts as you scroll, checking whether a username is free, showing the weather. The `fetch()` function makes these requests, and the data usually comes back as **JSON**.

## What is an API?

An **API** (Application Programming Interface) is a set of URLs a server offers for programs (rather than people) to request data. Instead of an HTML page, the server answers with raw data, usually in JSON.

For practice, we'll use **JSONPlaceholder** (`jsonplaceholder.typicode.com`), a free fake API made for learning.

## What is JSON?

JSON (JavaScript Object Notation) is a text format for data that looks almost exactly like JavaScript objects:

```json
{
  "id": 1,
  "title": "Learn fetch",
  "completed": false,
  "tags": ["javascript", "api"],
  "author": { "name": "Asha" }
}
```

Differences from JavaScript objects: keys must be in **double quotes**, strings must use double quotes, and there are no functions, comments or `undefined`. You convert between JSON text and JavaScript values with `JSON.stringify()` and `JSON.parse()`, as in the previous lesson.

## Your first fetch

```js
fetch("https://jsonplaceholder.typicode.com/todos/1")
  .then(response => response.json())
  .then(todo => {
    console.log(todo);
    console.log(`Task: ${todo.title}`);
  });

console.log("This line runs first!");
```

Run it and notice the order: "This line runs first!" appears **before** the data. Network requests take time, so `fetch` doesn't wait. It returns a **Promise**, an object representing a result that will arrive later. `.then()` says what to do when it does:

1. `fetch(url)` sends the request and gives a Promise of a **response**.
2. `response.json()` reads the body and parses the JSON, which also takes time, so it returns another Promise.
3. The second `.then` receives the actual data.

In the next lesson you'll learn `async`/`await`, which makes this read like normal top-to-bottom code. Many developers find it easier; we'll show both styles.

## HTTP status codes

Every response has a **status code**:

| Code | Meaning |
|---|---|
| 200 | OK |
| 201 | Created (after a successful POST) |
| 400 | Bad request (the data sent was wrong) |
| 401 / 403 | Not logged in / not allowed |
| 404 | Not found |
| 500 | Server error |

**Important:** `fetch` only fails (rejects) on **network** problems. A 404 or 500 still counts as a "successful" response, so you must check `response.ok` (true for 200–299) yourself:

```js
fetch("https://jsonplaceholder.typicode.com/todos/999999")
  .then(response => {
    console.log("Status:", response.status, "OK?", response.ok);
    if (!response.ok) {
      throw new Error(`Request failed with status ${response.status}`);
    }
    return response.json();
  })
  .then(data => console.log(data))
  .catch(error => console.error("Something went wrong:", error.message));
```

`.catch()` handles any error in the chain: network failures and the error we threw.

## Showing API data on the page

```html
<style>
  body { font-family: system-ui, sans-serif; }
  li { padding: 6px 0; border-bottom: 1px solid #eee; }
  .done { color: #94a3b8; text-decoration: line-through; }
</style>
<h3>Tasks from the API</h3>
<p id="status">Loading…</p>
<ul id="list"></ul>

<script>
  fetch("https://jsonplaceholder.typicode.com/todos?_limit=8")
    .then(res => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    })
    .then(todos => {
      document.getElementById("status").textContent = `${todos.length} tasks loaded`;
      const list = document.getElementById("list");
      todos.forEach(todo => {
        const li = document.createElement("li");
        li.textContent = todo.title;
        if (todo.completed) li.classList.add("done");
        list.append(li);
      });
    })
    .catch(err => {
      document.getElementById("status").textContent = `Couldn't load tasks (${err.message}).`;
    });
</script>
```

Always show a **loading state** and a friendly **error message**. Networks are slow and unreliable, especially on phones.

## Query parameters

Many APIs accept options in the URL after `?`:

```js
const params = new URLSearchParams({ userId: 1, _limit: 3 });
const url = `https://jsonplaceholder.typicode.com/posts?${params}`;
console.log(url);

fetch(url)
  .then(res => res.json())
  .then(posts => posts.forEach(p => console.log(p.id, p.title)));
```

`URLSearchParams` builds the query string and escapes special characters for you.

## Sending data with POST

```js
const newPost = { title: "My first post", body: "Hello API!", userId: 1 };

fetch("https://jsonplaceholder.typicode.com/posts", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify(newPost)
})
  .then(res => {
    console.log("Status:", res.status); // 201 Created
    return res.json();
  })
  .then(created => console.log("Server replied:", created));
```

The second argument to `fetch` sets options:

- `method`: `"GET"` (the default), `"POST"`, `"PUT"`, `"PATCH"`, `"DELETE"`.
- `headers`: extra information; `Content-Type` says the body is JSON.
- `body`: the data, as a string (use `JSON.stringify`), or a `FormData` object.

(JSONPlaceholder pretends to save it and returns the result with a new `id`, but nothing is really stored.)

## CORS in one paragraph

Browsers only let a page read responses from **another** domain if that server allows it, through headers. This rule is called **CORS**. Public APIs designed for browsers allow it. If you see a "blocked by CORS policy" error, the problem is the server's settings, not your JavaScript; you'd need to call that API from your own server instead.

## API keys

Many real APIs (weather, maps, AI) require a key. Never put **secret** keys in front-end JavaScript: anyone can read your page's code. Keep them on a server and have your front end call your own server.

## Common mistakes

- **Forgetting `return`** inside `.then` when chaining, so the next step gets `undefined`.
- **Not checking `response.ok`**: a 404 page gets parsed as data and fails strangely.
- **Using data before it arrives** (outside the `.then`).
- **Sending an object as `body`** without `JSON.stringify`.
- **No loading or error state** on the page.

## Exercise

1. Fetch `https://jsonplaceholder.typicode.com/users` and log every user's name and city.
2. Show the users as a list on the page with a "Loading…" message.
3. Add an input where the user types a number 1–10; fetch and show that user's details on submit.
4. Request a user that doesn't exist (id 999) and show a friendly error.
5. Send a POST request with a new to-do item and log the response.

## Summary

- `fetch(url)` makes HTTP requests and returns a Promise; `response.json()` parses JSON.
- Check `response.ok`; `fetch` only rejects on network errors.
- Use `.then()` for results and `.catch()` for errors; always show loading and error states.
- Send data with `method: "POST"`, a JSON header and `JSON.stringify(body)`.

Next, you'll write the same code more clearly with async and await.
