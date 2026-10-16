---
title: "DOM: Selecting Elements"
slug: js-dom-selecting-elements
subject: javascript
lesson: 18
difficulty: intermediate
excerpt: What the DOM is, and how to find elements with getElementById, querySelector and querySelectorAll, loop over results, and move around the element tree.
---

So far, your JavaScript has mostly printed to the console. Now it's time to work with the page itself. To change anything on a page, you first have to **find** it. This lesson explains the DOM and the methods for selecting elements.

## What is the DOM?

When the browser loads HTML, it builds a tree of objects in memory called the **DOM** (Document Object Model). Every element, attribute and piece of text becomes an object that JavaScript can read and change.

```html
<body>
  <h1>Title</h1>
  <ul>
    <li>One</li>
    <li>Two</li>
  </ul>
</body>
```

becomes a tree:

```text
document
└── html
    └── body
        ├── h1  → "Title"
        └── ul
            ├── li → "One"
            └── li → "Two"
```

The global `document` object is the entry point. When JavaScript changes the DOM, the browser updates the page immediately.

## getElementById

```html
<h1 id="title">Hello</h1>

<script>
  const title = document.getElementById("title");
  console.log(title);
  console.log(title.textContent);
</script>
```

Finds the one element with that `id`. Note: no `#` here, just the name. Returns `null` if nothing matches.

## querySelector: any CSS selector

`querySelector` accepts **any CSS selector** and returns the **first** matching element:

```html
<div class="card"><h2>Card one</h2></div>
<div class="card"><h2>Card two</h2></div>
<input type="email" name="email">

<script>
  const firstCard = document.querySelector(".card");
  const heading = document.querySelector(".card h2");
  const emailInput = document.querySelector('input[name="email"]');

  console.log(firstCard.textContent.trim());
  console.log(heading.textContent);
  console.log(emailInput.type);
</script>
```

Everything you learned about CSS selectors works here: classes, ids (`#title`), attributes, `:first-child`, descendants and more. That's why most developers use `querySelector` for everything.

## querySelectorAll: all matches

```html
<ul>
  <li class="task">Learn HTML</li>
  <li class="task done">Learn CSS</li>
  <li class="task">Learn JavaScript</li>
</ul>

<script>
  const tasks = document.querySelectorAll(".task");
  console.log(tasks.length); // 3

  tasks.forEach((task, i) => {
    console.log(i + 1, task.textContent);
  });

  const done = document.querySelectorAll(".task.done");
  console.log("Done:", done.length);
</script>
```

`querySelectorAll` returns a **NodeList**: a list that supports `forEach`, `length` and `for...of`, but not `map` or `filter`. Convert it to a real array when you need those:

```html
<p class="price">120</p>
<p class="price">450</p>
<p class="price">99</p>

<script>
  const prices = [...document.querySelectorAll(".price")]
    .map(p => Number(p.textContent));
  console.log(prices, "total:", prices.reduce((a, b) => a + b, 0));
</script>
```

## Searching inside an element

You can call `querySelector` on any element, not just `document`, to search only inside it:

```html
<article id="post">
  <h2>Post title</h2>
  <p class="meta">5 min read</p>
</article>
<p class="meta">Footer meta</p>

<script>
  const post = document.getElementById("post");
  const meta = post.querySelector(".meta"); // only inside the article
  console.log(meta.textContent);            // "5 min read"
</script>
```

This is faster on big pages and avoids grabbing the wrong element.

## Older methods

You'll see these in older code:

```js
// document.getElementsByClassName("card")  → live HTMLCollection
// document.getElementsByTagName("p")        → live HTMLCollection
```

They return "live" collections that update automatically when the page changes, and they don't support `forEach`. `querySelectorAll` is simpler and is the usual choice today.

## Moving around the tree

Once you have an element, you can reach its relatives:

```html
<ul id="menu">
  <li>Home</li>
  <li id="active">Tutorials</li>
  <li>Blog</li>
</ul>

<script>
  const active = document.getElementById("active");

  console.log(active.parentElement.id);              // "menu"
  console.log(active.previousElementSibling.textContent); // "Home"
  console.log(active.nextElementSibling.textContent);     // "Blog"

  const menu = document.getElementById("menu");
  console.log(menu.children.length);                  // 3
  console.log(menu.firstElementChild.textContent);    // "Home"

  console.log(active.closest("ul").id);               // "menu": nearest ancestor matching a selector
</script>
```

`closest()` is especially useful in event handling: from a clicked button, find the card it belongs to.

## Always check for null

If no element matches, you get `null`, and using it causes an error:

```js
const missing = document.querySelector(".does-not-exist");
console.log(missing); // null

// missing.textContent = "x"; // TypeError: Cannot set properties of null

if (missing) {
  missing.textContent = "Found";
}
missing?.classList.add("x"); // optional chaining: does nothing if null
```

If you see "Cannot read properties of null", check that the selector is spelled correctly and that the script runs after the element exists (remember `defer`).

## Data attributes as hooks

A clean way to select elements for JavaScript is a `data-` attribute:

```html
<button data-action="save">Save</button>
<button data-action="delete">Delete</button>

<script>
  const saveBtn = document.querySelector('[data-action="save"]');
  console.log(saveBtn.dataset.action); // "save"
</script>
```

Keeping `class` for CSS and `data-*` for JavaScript means a designer can rename classes without breaking scripts.

## Common mistakes

- **Adding `#` in getElementById**: `getElementById("#title")` finds nothing.
- **Forgetting the dot** in querySelector: `querySelector("card")` looks for a `<card>` element.
- **Using `querySelector` when you need all matches**, or calling `.textContent` on a NodeList.
- **Running the script before the HTML exists.**

## Exercise

Create a page with a heading, three `.item` paragraphs (one with class `featured`) and a `<ul>` of links, then:

1. Select the heading by id and log its text.
2. Select all `.item` elements and log how many there are.
3. Select only the featured item with a combined selector.
4. Get the text of every link as an array with spread and `map`.
5. From the second list item, log its previous and next siblings.

## Summary

- The DOM is the browser's tree of objects for the page; `document` is the entry point.
- `getElementById` finds one element by id; `querySelector` finds the first CSS match.
- `querySelectorAll` returns all matches as a NodeList; spread it into an array for `map`/`filter`.
- Navigate with `parentElement`, `children`, siblings and `closest`; always handle `null`.

Next, you'll change the content, attributes and styles of the elements you've selected.
