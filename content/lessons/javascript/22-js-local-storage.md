---
title: JavaScript Local Storage
slug: js-local-storage
subject: javascript
lesson: 22
difficulty: intermediate
excerpt: Save data in the browser with localStorage and sessionStorage, store objects with JSON, handle errors, and remember a user's theme between visits.
---

Normally, everything in a page's JavaScript is forgotten when the page reloads. **Web Storage** lets you save small amounts of data in the visitor's browser so it's still there next time: a dark-mode preference, a draft message, items in a to-do list.

**Note:** the Try it Yourself editor runs your code in a secure sandbox where storage is turned off, so the examples below show an error there. To run them, paste them into the browser console (F12 → Console) on any page, or into an HTML file on your computer.

## localStorage basics

```js
// Save
localStorage.setItem("username", "Asha");

// Read
const name = localStorage.getItem("username");
console.log(name); // "Asha"

// Remove one item
localStorage.removeItem("username");

// Remove everything this site stored
// localStorage.clear();

console.log(localStorage.getItem("username")); // null: not found
```

Data in `localStorage`:

- Is stored as **key–value pairs of strings**.
- **Stays** after the tab is closed, the browser is restarted, even the computer is restarted, until your code or the user deletes it.
- Belongs to one **origin** (protocol + domain + port). Other websites can't read it.
- Is limited to about 5 MB per site.

## sessionStorage

```js
sessionStorage.setItem("step", "2");
console.log(sessionStorage.getItem("step"));
```

`sessionStorage` works the same way, but is cleared when the tab is closed, and each tab has its own copy. Use it for temporary things like progress through a multi-step form.

## Everything is a string

```js
localStorage.setItem("count", 5);
const count = localStorage.getItem("count");
console.log(count, typeof count); // "5" "string"
console.log(Number(count) + 1);   // 6
```

Numbers and booleans come back as strings. Convert them when you read them.

## Storing objects and arrays with JSON

Saving an object directly gives you the useless string `"[object Object]"`. Convert it to JSON text first:

```js
const settings = { theme: "dark", fontSize: 18, lessonsDone: [1, 2, 5] };

localStorage.setItem("settings", JSON.stringify(settings));

const saved = JSON.parse(localStorage.getItem("settings"));
console.log(saved.theme);        // "dark"
console.log(saved.lessonsDone);  // [1, 2, 5]
```

- `JSON.stringify(value)` turns objects and arrays into a JSON string.
- `JSON.parse(string)` turns it back into an object.

You'll use JSON again with `fetch` in the next lesson.

## Safe helper functions

Storage can fail: the user may have blocked it, the browser may be in a strict private mode, or the saved data may be corrupted. Wrap it in helpers with `try...catch`:

```js
function save(key, value) {
  try {
    localStorage.setItem(key, JSON.stringify(value));
    return true;
  } catch (error) {
    console.warn("Could not save:", error.message);
    return false;
  }
}

function load(key, fallback) {
  try {
    const raw = localStorage.getItem(key);
    return raw === null ? fallback : JSON.parse(raw);
  } catch (error) {
    return fallback;
  }
}

save("progress", { course: "javascript", lesson: 22 });
console.log(load("progress", {}));
console.log(load("missing-key", { course: "html", lesson: 1 }));
```

Your app should always work even when storage isn't available; it just won't remember things. (`try...catch` catches errors so the program doesn't crash; you'll see more of it in the Async/Await lesson.)

## Example: remember the theme

Save this as an HTML file and open it in your browser:

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Theme demo</title>
  <style>
    body { font-family: system-ui, sans-serif; padding: 24px; transition: background 0.2s, color 0.2s; }
    body.dark { background: #0f172a; color: #e2e8f0; }
  </style>
</head>
<body>
  <h1>Theme demo</h1>
  <button id="toggle">Toggle dark mode</button>

  <script>
    const KEY = "theme";
    const body = document.body;

    function getSavedTheme() {
      try { return localStorage.getItem(KEY); } catch { return null; }
    }

    if (getSavedTheme() === "dark") body.classList.add("dark");

    document.getElementById("toggle").addEventListener("click", () => {
      body.classList.toggle("dark");
      try {
        localStorage.setItem(KEY, body.classList.contains("dark") ? "dark" : "light");
      } catch {}
    });
  </script>
</body>
</html>
```

Switch to dark mode, reload the page: it stays dark. This is how VitalStack's own theme toggle works.

## Example: auto-save a draft

```js
// const textarea = document.querySelector("#message");
// textarea.value = localStorage.getItem("draft") ?? "";
// textarea.addEventListener("input", () => {
//   localStorage.setItem("draft", textarea.value);
// });
// form.addEventListener("submit", () => localStorage.removeItem("draft"));
```

If the user accidentally closes the tab, their text is still there when they return.

## Seeing stored data

In Chrome or Edge: F12 → **Application** → **Local Storage** → your site. In Firefox: F12 → **Storage**. You can view, edit and delete items there, which is very useful when debugging.

## What NOT to store

- **Passwords, tokens for important accounts, payment details** or any secret. Any JavaScript running on the page (including a malicious third-party script) can read localStorage.
- **Large data** (images, big files): it's small and slow. Use IndexedDB for large amounts.
- **Data that must be shared** between devices or users: that belongs on a server.

## Common mistakes

- **Storing objects without `JSON.stringify`** → `"[object Object]"`.
- **Forgetting that values come back as strings.**
- **Calling `JSON.parse` on `null`** or corrupted data without a fallback.
- **Storing sensitive information.**

## Exercise

1. Save your name in localStorage, reload the page, and read it back in the console.
2. Store an array of three favourite lessons with JSON and read it back.
3. Build a page that counts how many times you've visited it.
4. Make a textarea that auto-saves a draft and restores it on reload.

## Summary

- `localStorage` keeps strings across visits; `sessionStorage` only for the tab's life.
- `setItem`, `getItem`, `removeItem` and `clear` manage data.
- Use `JSON.stringify` and `JSON.parse` for objects and arrays.
- Wrap storage in `try...catch`, and never store secrets.

Next, you'll load data from the internet with fetch and JSON.
