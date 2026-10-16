---
title: JavaScript Async/Await
slug: js-async-await
subject: javascript
lesson: 24
difficulty: intermediate
excerpt: Understand asynchronous JavaScript, Promises and the event loop, then write clear async code with async/await, try/catch, and parallel requests with Promise.all.
---

Some things take time: downloading data, waiting for a timer, reading a file. JavaScript doesn't stop and wait for them; it carries on and handles the result later. This is **asynchronous** code. `async` and `await` let you write it in a way that reads like normal, top-to-bottom code.

## Synchronous vs asynchronous

```js
console.log("1. Order coffee");

setTimeout(() => {
  console.log("3. Coffee is ready");
}, 1000);

console.log("2. Read the news while waiting");
```

Output: 1, 2, then 3 after a second. `setTimeout` schedules the function for later and JavaScript continues immediately. This keeps pages responsive: the browser never freezes while waiting for a slow network.

JavaScript runs one thing at a time. Waiting tasks are handed to the browser, and when they're done, their callbacks are queued and run once the current code finishes. This mechanism is called the **event loop**.

## Promises in brief

A **Promise** is an object representing a value that isn't ready yet. It's in one of three states:

- **pending**: still waiting,
- **fulfilled**: finished with a value,
- **rejected**: failed with an error.

```js
function wait(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

wait(500).then(() => console.log("Half a second passed"));
```

`fetch()` returns a Promise, `response.json()` returns a Promise, and many modern browser APIs do too.

## async and await

```js
function wait(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function makeTea() {
  console.log("Boiling water…");
  await wait(500);
  console.log("Steeping tea…");
  await wait(500);
  console.log("Tea is ready!");
}

makeTea();
console.log("(The rest of the page keeps working meanwhile)");
```

- `async` before a function makes it an **async function**. It always returns a Promise.
- Inside it, `await` pauses **that function** until the Promise settles, then gives you its value.
- The rest of the program isn't blocked; only the async function waits.

## fetch with async/await

Compare with the `.then()` version from the last lesson:

```js
async function loadTodo() {
  const response = await fetch("https://jsonplaceholder.typicode.com/todos/1");
  const todo = await response.json();
  console.log(`Task: ${todo.title}`);
}

loadTodo();
```

Each step on its own line, in order. Much easier to read, especially with several steps.

## Error handling with try/catch

```js
async function loadUser(id) {
  try {
    const response = await fetch(`https://jsonplaceholder.typicode.com/users/${id}`);
    if (!response.ok) {
      throw new Error(`User ${id} not found (HTTP ${response.status})`);
    }
    const user = await response.json();
    console.log(`${user.name} lives in ${user.address.city}`);
  } catch (error) {
    console.error("Could not load user:", error.message);
  } finally {
    console.log("Request finished.");
  }
}

loadUser(3);
loadUser(999);
```

- `try { … }` runs the code.
- If anything inside throws an error (or an awaited Promise rejects), execution jumps to `catch (error) { … }`.
- `finally { … }` always runs, which is useful for hiding a loading spinner.

## Returning values from async functions

An async function returns a Promise, so the caller must `await` it too:

```js
async function getUserName(id) {
  const res = await fetch(`https://jsonplaceholder.typicode.com/users/${id}`);
  const user = await res.json();
  return user.name;
}

async function main() {
  const name = await getUserName(1);
  console.log("Name:", name);

  const notAwaited = getUserName(2);
  console.log(notAwaited); // a Promise, not the name!
}

main();
```

Modern browsers also allow `await` at the top level of **module** scripts (`<script type="module">`), without wrapping it in a function.

## Running requests in parallel

`await` one after another makes requests wait for each other:

```js
async function sequential() {
  console.time("sequential");
  const a = await fetch("https://jsonplaceholder.typicode.com/users/1").then(r => r.json());
  const b = await fetch("https://jsonplaceholder.typicode.com/users/2").then(r => r.json());
  console.timeEnd("sequential");
  console.log(a.name, b.name);
}

async function parallel() {
  console.time("parallel");
  const [a, b] = await Promise.all([
    fetch("https://jsonplaceholder.typicode.com/users/1").then(r => r.json()),
    fetch("https://jsonplaceholder.typicode.com/users/2").then(r => r.json())
  ]);
  console.timeEnd("parallel");
  console.log(a.name, b.name);
}

sequential().then(parallel);
```

`Promise.all([...])` starts all the Promises at once and waits for all of them. It's usually much faster when requests don't depend on each other. If any one fails, `Promise.all` rejects; use `Promise.allSettled` if you want every result, successful or not.

## A complete example: search users

```html
<style>
  body { font-family: system-ui, sans-serif; }
  .card { border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px; margin-top: 8px; }
</style>
<form id="f">
  <label for="id">User id (1–10)</label>
  <input id="id" type="number" min="1" max="10" value="1">
  <button id="btn">Load</button>
</form>
<div id="out"></div>

<script>
  const out = document.getElementById("out");
  const btn = document.getElementById("btn");

  async function showUser(id) {
    out.textContent = "Loading…";
    btn.disabled = true;
    try {
      const res = await fetch(`https://jsonplaceholder.typicode.com/users/${id}`);
      if (!res.ok) throw new Error(`No user with id ${id}`);
      const user = await res.json();
      out.innerHTML = "";
      const card = document.createElement("div");
      card.className = "card";
      card.textContent = `${user.name} · ${user.email} · ${user.company.name}`;
      out.append(card);
    } catch (err) {
      out.textContent = err.message;
    } finally {
      btn.disabled = false;
    }
  }

  document.getElementById("f").addEventListener("submit", e => {
    e.preventDefault();
    showUser(document.getElementById("id").value);
  });

  showUser(1);
</script>
```

Loading state, error state, disabled button while waiting, and safe `textContent` output: a solid pattern to copy.

## Common mistakes

- **Forgetting `await`**, so you get a Promise instead of the value.
- **Using `await` outside an async function** (in a normal script).
- **Awaiting in a loop** when requests could run in parallel with `Promise.all`.
- **No try/catch**, so one failed request causes an unhandled error.
- **`forEach` with an async callback**: it doesn't wait. Use `for...of` with `await` instead.

## Exercise

1. Write `async function countdown()` that logs 3, 2, 1, "Go!" with a one-second `wait` between each.
2. Rewrite your users list from the previous lesson with async/await and try/catch.
3. Fetch users 1, 2 and 3 in parallel with `Promise.all` and log their names.
4. Add a loading message and a disabled button to your user search.

## Summary

- Asynchronous code lets JavaScript keep working while waiting for slow tasks.
- Promises represent future values; `async` functions return Promises.
- `await` pauses an async function until a Promise settles; use try/catch/finally for errors.
- `Promise.all` runs independent requests in parallel.

Next, you'll build a complete To-Do List app using everything from this course.
