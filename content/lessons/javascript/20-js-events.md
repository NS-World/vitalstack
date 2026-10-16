---
title: JavaScript Events
slug: js-events
subject: javascript
lesson: 20
difficulty: intermediate
excerpt: Respond to clicks, typing and keys with addEventListener, use the event object, prevent default actions, and handle many elements with event delegation.
---

An **event** is something that happens on the page: a click, a key press, a form submit, the page finishing loading. JavaScript can **listen** for events and run a function when they happen. Events are what turn a static page into an interactive app.

## addEventListener

```html
<button id="like">♡ Like</button>
<p id="count">0 likes</p>

<script>
  const button = document.getElementById("like");
  const countEl = document.getElementById("count");
  let likes = 0;

  button.addEventListener("click", () => {
    likes++;
    countEl.textContent = `${likes} like${likes === 1 ? "" : "s"}`;
  });
</script>
```

`element.addEventListener(eventType, handlerFunction)`:

- The first argument is the event name as a string, without "on": `"click"`, not `"onclick"`.
- The second is the function to run, called the **event handler** or **listener**.

Pass the function itself; don't call it:

```js
function sayHi() { console.log("Hi"); }

// button.addEventListener("click", sayHi);   // correct
// button.addEventListener("click", sayHi()); // wrong: runs immediately, once
```

## Common events

| Event | Fires when |
|---|---|
| `click` | An element is clicked (or activated with Enter/Space on buttons) |
| `input` | The value of a field changes, on every keystroke |
| `change` | A field's value is committed (select, checkbox, or input losing focus) |
| `submit` | A form is submitted |
| `keydown` | A key is pressed |
| `focus` / `blur` | An element gains / loses focus |
| `mouseenter` / `mouseleave` | The pointer moves onto / off an element |
| `scroll` | The page or an element scrolls |
| `DOMContentLoaded` | The HTML has been fully read |

## The event object

Every handler receives an **event object** with details about what happened:

```html
<button id="btn">Click me anywhere</button>

<script>
  document.getElementById("btn").addEventListener("click", event => {
    console.log("Type:", event.type);
    console.log("Clicked element:", event.target.textContent);
    console.log("Position:", event.clientX, event.clientY);
    console.log("Shift held?", event.shiftKey);
  });
</script>
```

`event.target` is the element that was actually clicked; it's the most-used property.

## Live input

```html
<label for="name">Your name</label>
<input id="name" type="text">
<p id="preview">Hello, stranger!</p>

<script>
  const input = document.getElementById("name");
  const preview = document.getElementById("preview");

  input.addEventListener("input", () => {
    const name = input.value.trim();
    preview.textContent = `Hello, ${name || "stranger"}!`;
  });
</script>
```

The `input` event fires on every change, perfect for live previews, search-as-you-type and character counters.

## Keyboard events

```html
<p>Press any key (click inside the result first).</p>
<p id="key"></p>

<script>
  document.addEventListener("keydown", event => {
    document.getElementById("key").textContent = `You pressed: ${event.key}`;
    if (event.key === "Escape") {
      console.log("Escape pressed: close the popup!");
    }
  });
</script>
```

`event.key` gives the key's value: `"a"`, `"Enter"`, `"Escape"`, `"ArrowUp"`.

## Preventing the default action

Some events have a built-in browser action: a link navigates, a form submits and reloads the page. `event.preventDefault()` stops it:

```html
<form id="search">
  <input name="q" placeholder="Search" required>
  <button>Search</button>
</form>
<p id="out"></p>

<script>
  document.getElementById("search").addEventListener("submit", event => {
    event.preventDefault(); // no page reload
    const query = event.target.elements.q.value;
    document.getElementById("out").textContent = `Searching for "${query}"…`;
  });
</script>
```

Listen for `submit` on the **form**, not `click` on the button. That way, pressing Enter in a field also works.

## Event bubbling

When you click an element, the event first fires on it, then **bubbles up** through every ancestor to `document`:

```html
<div id="outer" style="padding:20px;background:#e0e7ff">
  Outer
  <button id="inner">Inner button</button>
</div>

<script>
  document.getElementById("outer").addEventListener("click", () => console.log("outer heard it"));
  document.getElementById("inner").addEventListener("click", () => console.log("button clicked"));
</script>
```

Clicking the button logs both messages. You can stop bubbling with `event.stopPropagation()`, but you'll rarely need to. Bubbling is actually very useful…

## Event delegation

Instead of adding a listener to every item (including items added later), add **one** listener to the parent and check `event.target`:

```html
<ul id="todo">
  <li>Buy milk <button data-action="delete">✕</button></li>
  <li>Study CSS <button data-action="delete">✕</button></li>
</ul>
<button id="add">Add task</button>

<script>
  const list = document.getElementById("todo");
  let n = 1;

  list.addEventListener("click", event => {
    const btn = event.target.closest('[data-action="delete"]');
    if (!btn) return;            // the click wasn't on a delete button
    btn.closest("li").remove();
  });

  document.getElementById("add").addEventListener("click", () => {
    const li = document.createElement("li");
    li.innerHTML = `New task ${n++} <button data-action="delete">✕</button>`;
    list.append(li);
  });
</script>
```

New tasks can be deleted too, without adding new listeners. Delegation is simpler, uses less memory, and handles dynamic content automatically.

## Removing listeners and one-time listeners

```js
function onResize() {
  console.log("Window width:", window.innerWidth);
}

window.addEventListener("resize", onResize);
window.removeEventListener("resize", onResize); // needs the same named function

document.addEventListener("click", () => console.log("only once"), { once: true });
```

## Accessibility reminder

Attach click handlers to real `<button>` and `<a>` elements. They work with the keyboard automatically. A clickable `<div>` doesn't.

## Common mistakes

- **Calling the handler** instead of passing it: `addEventListener("click", fn())`.
- **Using `"onclick"`** as the event name.
- **Listening to the button's click** instead of the form's `submit`.
- **Adding listeners inside a loop** to many items instead of delegating.
- **Forgetting `preventDefault()`** and wondering why the page reloads.

## Exercise

1. Build a counter with "+", "−" and "Reset" buttons.
2. Create a textarea with a live "120 characters left" counter.
3. Make a form that shows the entered email below it on submit, without reloading.
4. Close a "popup" div when the user presses Escape.
5. Build a list where clicking any item toggles a `done` class, using event delegation.

## Summary

- `addEventListener(type, handler)` runs code when events happen.
- The event object tells you what happened; `event.target` is the element involved.
- `preventDefault()` stops built-in actions like form submission.
- Events bubble up, which enables event delegation: one listener on a parent handles many children.

Next, you'll handle forms and validate user input with JavaScript.
