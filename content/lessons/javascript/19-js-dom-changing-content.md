---
title: "DOM: Changing Content"
slug: js-dom-changing-content
subject: javascript
lesson: 19
difficulty: intermediate
excerpt: Change text, HTML, attributes, classes and styles, create and insert new elements, remove elements, and render a list from data.
---

You can find elements; now you'll change them. Updating text, toggling classes, creating new elements from data: this is how every dynamic interface works, from a "likes" counter to a whole shopping cart.

## Changing text

```html
<h2 id="status">Loading…</h2>

<script>
  const status = document.getElementById("status");
  status.textContent = "Ready!";
</script>
```

`textContent` sets plain text. Any HTML tags in the string are shown as text, not interpreted, which makes it **safe** for user input.

## Changing HTML

```html
<div id="box"></div>

<script>
  document.getElementById("box").innerHTML = `
    <h3>New section</h3>
    <p>This HTML was added by <strong>JavaScript</strong>.</p>
  `;
</script>
```

`innerHTML` parses the string as HTML. It's convenient, but:

- **Never** put user-typed text into `innerHTML`; it can run malicious code (XSS).
- Setting it replaces everything inside the element, including event listeners on old children.

## Attributes

```html
<img id="photo" src="https://picsum.photos/id/10/200/120" alt="Forest" width="200" height="120">
<a id="link" href="#">A link</a>

<script>
  const photo = document.getElementById("photo");
  photo.src = "https://picsum.photos/id/1015/200/120";
  photo.alt = "River in a valley";

  const link = document.getElementById("link");
  link.setAttribute("href", "https://developer.mozilla.org/");
  link.setAttribute("target", "_blank");
  console.log(link.getAttribute("href"));
  link.removeAttribute("target");
</script>
```

Common attributes are available as properties (`img.src`, `a.href`, `input.value`, `button.disabled`). For others, use `setAttribute`, `getAttribute` and `removeAttribute`.

### Data attributes

```html
<div id="product" data-price="499" data-in-stock="true">Backpack</div>

<script>
  const product = document.getElementById("product");
  console.log(product.dataset.price);    // "499" (always a string)
  console.log(product.dataset.inStock);  // "true": data-in-stock becomes inStock
  product.dataset.price = "449";
</script>
```

## Classes: the best way to change styles

```html
<style>
  .card { padding: 12px; border: 1px solid #ccc; margin-bottom: 6px; }
  .highlight { background: #fef08a; }
  .hidden { display: none; }
</style>
<div class="card" id="c1">Card one</div>
<div class="card" id="c2">Card two</div>

<script>
  const c1 = document.getElementById("c1");
  c1.classList.add("highlight");
  c1.classList.remove("card");
  c1.classList.add("card");
  console.log(c1.classList.contains("highlight")); // true

  const c2 = document.getElementById("c2");
  c2.classList.toggle("hidden"); // adds it if missing, removes it if present
  c2.classList.toggle("hidden");
</script>
```

Define the look in CSS, and use JavaScript only to add or remove classes. This keeps styling in one place and is easy to change later.

## Inline styles

```html
<p id="note">Watch me change.</p>

<script>
  const note = document.getElementById("note");
  note.style.color = "white";
  note.style.backgroundColor = "#2563eb";  // background-color → backgroundColor
  note.style.padding = "8px 12px";
  note.style.borderRadius = "6px";
</script>
```

CSS property names with hyphens become camelCase. Inline styles are handy for values calculated in JavaScript, like a progress bar width or a position, but use classes for everything else.

## Creating and inserting elements

```html
<ul id="list">
  <li>First</li>
</ul>

<script>
  const list = document.getElementById("list");

  const li = document.createElement("li");
  li.textContent = "Added at the end";
  list.append(li);

  const top = document.createElement("li");
  top.textContent = "Added at the start";
  list.prepend(top);

  const after = document.createElement("li");
  after.textContent = "Added after the first original item";
  list.children[1].after(after);
</script>
```

1. `document.createElement("tag")` makes a new element (not yet on the page).
2. Set its content, classes and attributes.
3. Insert it with `append` (end), `prepend` (start), `before` or `after`.

There's also `insertAdjacentHTML("beforeend", "<li>…</li>")` to insert HTML strings without replacing existing content.

## Removing elements

```html
<p id="ad">This message will be removed.</p>
<ul id="items"><li>A</li><li>B</li><li>C</li></ul>

<script>
  document.getElementById("ad").remove();

  const items = document.getElementById("items");
  items.lastElementChild.remove();       // remove C
  // items.replaceChildren();            // would remove all children
</script>
```

## Rendering a list from data

This pattern, data in, HTML out, is the heart of every modern web app:

```html
<style>
  .course { border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px; margin: 6px 0; font-family: system-ui, sans-serif; }
  .course small { color: #64748b; }
</style>
<div id="courses"></div>

<script>
  const courses = [
    { title: "HTML", lessons: 20, level: "Beginner" },
    { title: "CSS", lessons: 20, level: "Beginner" },
    { title: "JavaScript", lessons: 25, level: "Intermediate" }
  ];

  const container = document.getElementById("courses");

  courses.forEach(course => {
    const div = document.createElement("div");
    div.className = "course";

    const h3 = document.createElement("h3");
    h3.textContent = course.title;

    const small = document.createElement("small");
    small.textContent = `${course.lessons} lessons · ${course.level}`;

    div.append(h3, small);
    container.append(div);
  });
</script>
```

Change the data array and the page follows. Using `textContent` for each value keeps it safe, even if the data came from users.

## Performance tip

Each change to the page can make the browser recalculate the layout. When adding many elements, build them first and insert them in one go:

```js
const fragment = document.createDocumentFragment();
for (let i = 1; i <= 100; i++) {
  const li = document.createElement("li");
  li.textContent = `Item ${i}`;
  fragment.append(li);
}
document.body.append(fragment); // one insertion instead of 100
console.log(document.querySelectorAll("li").length);
```

## Common mistakes

- **Using `innerHTML` with user input** (security risk).
- **Setting `style` for everything** instead of toggling classes.
- **Writing `style.background-color`**; it must be `style.backgroundColor`.
- **Creating an element but forgetting to insert it.**
- **Expecting `dataset` values to be numbers**; convert with `Number()`.

## Exercise

1. Create a paragraph and change its text, colour and font size with JavaScript.
2. Toggle a `.dark` class on `<body>` (define the dark styles in CSS).
3. Given an array of five tasks, create one `<li>` for each in a `<ul>`.
4. Add a class `done` to every second task.
5. Remove the last task from the list.

## Summary

- `textContent` sets safe text; `innerHTML` sets HTML (never with user input).
- Change attributes with properties or `setAttribute`; read `data-*` with `dataset`.
- Use `classList.add/remove/toggle` for styling; `style` for calculated values.
- `createElement` + `append` builds new content; `remove()` deletes it.

Next, you'll make pages respond to the user with events.
