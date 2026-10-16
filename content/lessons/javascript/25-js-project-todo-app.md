---
title: "JavaScript Project: To-Do List App"
slug: js-project-todo-app
subject: javascript
lesson: 25
difficulty: intermediate
excerpt: Build a complete to-do list app step by step with state, rendering, events, delegation, filters and localStorage, using everything from the JavaScript course.
---

Time to build a real app. In this project you'll create a to-do list where you can add tasks, mark them done, delete them, filter them, and keep them after a reload. Along the way you'll use a pattern that powers most modern apps: **state → render**.

## The plan

1. Keep all tasks in one array (the **state**).
2. Write one `render()` function that draws the whole list from the state.
3. Every action (add, toggle, delete, filter) **changes the state, saves it, and calls `render()`**.

Never change the page directly in an event handler; change the data and re-render. This keeps the page and the data in sync.

## Step 1: The HTML and CSS

```html
<style>
  * { box-sizing: border-box; }
  body { font-family: system-ui, sans-serif; background: #f1f5f9; padding: 20px; color: #1e293b; }
  .app { max-width: 460px; margin: 0 auto; background: white; border-radius: 14px; padding: 20px; box-shadow: 0 6px 20px rgb(0 0 0 / 0.08); }
  h1 { margin: 0 0 12px; font-size: 24px; }
  .add { display: flex; gap: 8px; }
  .add input { flex: 1; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font: inherit; }
  .add button, .filters button { padding: 10px 14px; border: 0; border-radius: 8px; background: #2563eb; color: white; font: inherit; cursor: pointer; }
  .filters { display: flex; gap: 6px; margin: 14px 0; }
  .filters button { background: #e2e8f0; color: #1e293b; padding: 6px 12px; }
  .filters button[aria-pressed="true"] { background: #1e293b; color: white; }
  ul { list-style: none; padding: 0; margin: 0; }
  li { display: flex; align-items: center; gap: 10px; padding: 10px 4px; border-bottom: 1px solid #f1f5f9; }
  li span { flex: 1; }
  li.done span { text-decoration: line-through; color: #94a3b8; }
  .delete { border: 0; background: none; color: #ef4444; font-size: 18px; cursor: pointer; }
  .footer { display: flex; justify-content: space-between; margin-top: 12px; color: #64748b; font-size: 14px; }
  .footer button { border: 0; background: none; color: #2563eb; cursor: pointer; font: inherit; }
  .error { color: #b91c1c; font-size: 14px; min-height: 1.2em; margin: 6px 0 0; }
</style>

<div class="app">
  <h1>My tasks</h1>
  <form class="add" id="add-form">
    <input id="new-task" placeholder="What needs doing?" autocomplete="off" aria-label="New task">
    <button>Add</button>
  </form>
  <p class="error" id="error" role="alert"></p>

  <div class="filters" id="filters">
    <button data-filter="all" aria-pressed="true">All</button>
    <button data-filter="active" aria-pressed="false">Active</button>
    <button data-filter="done" aria-pressed="false">Done</button>
  </div>

  <ul id="list"></ul>

  <div class="footer">
    <span id="count"></span>
    <button id="clear-done">Clear completed</button>
  </div>
</div>
```

Try it now: it looks right but does nothing yet. The rest of the steps are JavaScript that goes in a `<script>` after this HTML. The full program is at the end.

## Step 2: State and storage

```js
const STORAGE_KEY = "vitalstack-todos";

function loadTasks() {
  try {
    return JSON.parse(localStorage.getItem(STORAGE_KEY)) ?? [];
  } catch {
    return []; // storage blocked or data corrupted
  }
}

function saveTasks() {
  try {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(tasks));
  } catch {
    // storage not available (e.g. in the Try it sandbox): the app still works
  }
}

let tasks = loadTasks();
let filter = "all";
```

Each task is an object: `{ id, text, done }`. The `try/catch` makes the app work even where storage is blocked; it just won't remember tasks there.

## Step 3: Render

```js
function render() {
  const list = document.getElementById("list");
  list.replaceChildren(); // clear

  const visible = tasks.filter(t =>
    filter === "active" ? !t.done :
    filter === "done" ? t.done : true
  );

  for (const task of visible) {
    const li = document.createElement("li");
    li.dataset.id = task.id;
    li.classList.toggle("done", task.done);

    const checkbox = document.createElement("input");
    checkbox.type = "checkbox";
    checkbox.checked = task.done;
    checkbox.dataset.action = "toggle";
    checkbox.setAttribute("aria-label", `Mark "${task.text}" as done`);

    const text = document.createElement("span");
    text.textContent = task.text;

    const del = document.createElement("button");
    del.className = "delete";
    del.dataset.action = "delete";
    del.setAttribute("aria-label", `Delete "${task.text}"`);
    del.textContent = "✕";

    li.append(checkbox, text, del);
    list.append(li);
  }

  const left = tasks.filter(t => !t.done).length;
  document.getElementById("count").textContent = `${left} task${left === 1 ? "" : "s"} left`;

  document.querySelectorAll("#filters button").forEach(btn => {
    btn.setAttribute("aria-pressed", String(btn.dataset.filter === filter));
  });
}
```

Notes:

- `textContent` keeps it safe even if someone types HTML into a task.
- Icon-only buttons get an `aria-label`.
- `classList.toggle(name, condition)` adds or removes a class based on a boolean.

## Step 4: Adding tasks

```js
document.getElementById("add-form").addEventListener("submit", event => {
  event.preventDefault();
  const input = document.getElementById("new-task");
  const text = input.value.trim();
  const error = document.getElementById("error");

  if (!text) {
    error.textContent = "Please type a task first.";
    return;
  }
  if (tasks.some(t => t.text.toLowerCase() === text.toLowerCase())) {
    error.textContent = "That task is already on your list.";
    return;
  }

  error.textContent = "";
  tasks.push({ id: Date.now(), text, done: false });
  input.value = "";
  saveTasks();
  render();
});
```

`Date.now()` (milliseconds since 1970) is a simple unique id for a small app.

## Step 5: Toggle and delete with event delegation

```js
document.getElementById("list").addEventListener("click", event => {
  const action = event.target.dataset.action;
  if (!action) return;

  const id = Number(event.target.closest("li").dataset.id);

  if (action === "toggle") {
    tasks = tasks.map(t => (t.id === id ? { ...t, done: !t.done } : t));
  } else if (action === "delete") {
    tasks = tasks.filter(t => t.id !== id);
  }

  saveTasks();
  render();
});
```

One listener handles every checkbox and delete button, including ones created later.

## Step 6: Filters and clearing

```js
document.getElementById("filters").addEventListener("click", event => {
  const value = event.target.dataset.filter;
  if (!value) return;
  filter = value;
  render();
});

document.getElementById("clear-done").addEventListener("click", () => {
  tasks = tasks.filter(t => !t.done);
  saveTasks();
  render();
});

render(); // first draw
```

## The complete script

Put the HTML from step 1 first, then this script, and click **Try it Yourself** on the full program below:

```html
<ul id="list"></ul>
<form id="add-form"><input id="new-task" aria-label="New task" placeholder="New task"><button>Add</button></form>
<p id="error" role="alert"></p>
<div id="filters"><button data-filter="all">All</button><button data-filter="active">Active</button><button data-filter="done">Done</button></div>
<p><span id="count"></span> <button id="clear-done">Clear completed</button></p>

<script>
  const STORAGE_KEY = "vitalstack-todos";
  const load = () => { try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) ?? []; } catch { return []; } };
  const save = () => { try { localStorage.setItem(STORAGE_KEY, JSON.stringify(tasks)); } catch {} };

  let tasks = load();
  let filter = "all";

  function render() {
    const list = document.getElementById("list");
    list.replaceChildren();
    tasks
      .filter(t => filter === "active" ? !t.done : filter === "done" ? t.done : true)
      .forEach(task => {
        const li = document.createElement("li");
        li.dataset.id = task.id;
        li.style.textDecoration = task.done ? "line-through" : "none";
        const box = document.createElement("input");
        box.type = "checkbox";
        box.checked = task.done;
        box.dataset.action = "toggle";
        box.setAttribute("aria-label", `Done: ${task.text}`);
        const text = document.createElement("span");
        text.textContent = " " + task.text + " ";
        const del = document.createElement("button");
        del.dataset.action = "delete";
        del.setAttribute("aria-label", `Delete ${task.text}`);
        del.textContent = "✕";
        li.append(box, text, del);
        list.append(li);
      });
    const left = tasks.filter(t => !t.done).length;
    document.getElementById("count").textContent = `${left} left`;
  }

  document.getElementById("add-form").addEventListener("submit", e => {
    e.preventDefault();
    const input = document.getElementById("new-task");
    const text = input.value.trim();
    document.getElementById("error").textContent = text ? "" : "Please type a task first.";
    if (!text) return;
    tasks.push({ id: Date.now(), text, done: false });
    input.value = "";
    save();
    render();
  });

  document.getElementById("list").addEventListener("click", e => {
    const action = e.target.dataset.action;
    if (!action) return;
    const id = Number(e.target.closest("li").dataset.id);
    tasks = action === "toggle"
      ? tasks.map(t => t.id === id ? { ...t, done: !t.done } : t)
      : tasks.filter(t => t.id !== id);
    save();
    render();
  });

  document.getElementById("filters").addEventListener("click", e => {
    if (!e.target.dataset.filter) return;
    filter = e.target.dataset.filter;
    render();
  });

  document.getElementById("clear-done").addEventListener("click", () => {
    tasks = tasks.filter(t => !t.done);
    save();
    render();
  });

  render();
</script>
```

This compact version has the same logic with minimal styling. For the full design, combine the HTML and CSS from step 1 with the functions from steps 2–6 in your own `index.html` file, then reload to see tasks persist.

## What you used

- Variables, arrays, objects, functions and arrow functions.
- `map`, `filter` and `some` for state changes.
- DOM creation, `textContent`, `classList` and data attributes.
- Events, `preventDefault` and event delegation.
- `localStorage` with JSON and `try/catch`.
- Accessibility: labels, `aria-label`, `aria-pressed`, `role="alert"`.

## Ideas to extend it

- Double-click a task to edit its text.
- Add a due date with `<input type="date">` and sort by it.
- Drag and drop to reorder (look up the HTML Drag and Drop API).
- Load starter tasks from `https://jsonplaceholder.typicode.com/todos?_limit=5` with `fetch` on first visit.
- Publish it on GitHub Pages and add it to your profile page's projects table.

## Summary

- Keep data in **state**, draw the page from state with **render()**, and update both on every action.
- Event delegation handles dynamic lists with one listener.
- localStorage with JSON makes data survive reloads.

Congratulations, you've finished the JavaScript course! You can now build interactive pages from scratch. Continue with the **SQL** course to learn how websites store data on the server side.
